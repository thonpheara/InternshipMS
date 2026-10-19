<?php

namespace App\Http\Controllers\Api;

use App\Models\Application;
use App\Models\StudentProfile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class FileApiController extends BaseApiController
{
    /**
     * Download or view the resume attached to a specific application.
     */
    public function downloadApplicationResume(Request $request, int $applicationId): BinaryFileResponse|JsonResponse
    {
        $user = $request->user();

        $application = Application::with(['studentProfile', 'internshipPost'])->find($applicationId);

        if (!$application) {
            return $this->sendError('Application not found.', [], 404);
        }

        // Authorization check: student applicant, company post owner, or admin
        $isStudentOwner = $user->isStudent() && $user->studentProfile?->id === $application->student_profile_id;
        $isCompanyOwner = $user->isCompany() && $user->companyProfile?->id === $application->internshipPost?->company_profile_id;

        if (!$isStudentOwner && !$isCompanyOwner && !$user->isAdmin()) {
            return $this->sendError('Unauthorized to access this resume.', [], 403);
        }

        $resumePath = $application->getEffectiveResumePath();

        if (!$resumePath) {
            return $this->sendError('No resume file associated with this application.', [], 404);
        }

        $disk = Storage::disk('local')->exists($resumePath) ? 'local' : (Storage::disk('public')->exists($resumePath) ? 'public' : null);

        if (!$disk) {
            return $this->sendError('Resume file not found on server storage.', [], 404);
        }

        $fullPath = Storage::disk($disk)->path($resumePath);
        $filename = basename($resumePath);

        return response()->file($fullPath, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . addcslashes($filename, '"\\') . '"',
        ]);
    }

    /**
     * Download or view a student's default profile resume.
     */
    public function downloadProfileResume(Request $request, ?int $studentId = null): BinaryFileResponse|JsonResponse
    {
        $user = $request->user();

        if ($studentId && ($user->isCompany() || $user->isAdmin())) {
            $student = StudentProfile::find($studentId);
        } else {
            $student = $user->studentProfile;
        }

        if (!$student || !$student->resume_path) {
            return $this->sendError('No profile resume found.', [], 404);
        }

        $resumePath = $student->resume_path;
        $disk = Storage::disk('local')->exists($resumePath) ? 'local' : (Storage::disk('public')->exists($resumePath) ? 'public' : null);

        if (!$disk) {
            return $this->sendError('Resume file not found on storage disk.', [], 404);
        }

        $fullPath = Storage::disk($disk)->path($resumePath);
        $filename = basename($resumePath);

        return response()->file($fullPath, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . addcslashes($filename, '"\\') . '"',
        ]);
    }
}
