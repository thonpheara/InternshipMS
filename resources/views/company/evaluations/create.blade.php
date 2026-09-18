<x-layout title="Submit Evaluation — Internship Management System">
    <div class="space-y-6 max-w-3xl mx-auto">
        
        <div>
            <a href="{{ route('company.evaluations.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-600 hover:text-emerald-600 transition-colors mb-2">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                <span>Back to evaluations</span>
            </a>
            <h2 class="text-2xl font-black tracking-tight text-slate-900">Intern Performance Evaluation</h2>
            <p class="text-xs sm:text-sm text-slate-600 mt-0.5">Evaluating: <strong class="text-slate-900">{{ $placement->studentProfile->user->name }}</strong> ({{ $placement->internshipPost->title }})</p>
        </div>

        <div class="p-6 sm:p-8 rounded-3xl bg-white border border-slate-200/80 shadow-xs">
            <form action="{{ route('company.evaluations.store', $placement) }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="type" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Assessment Milestone *</label>
                    <select id="type" name="type" required class="w-full p-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 text-slate-900 font-semibold">
                        <option value="midterm" {{ old('type') === 'midterm' ? 'selected' : '' }}>Midterm Evaluation (Halfway Review)</option>
                        <option value="final" {{ old('type') === 'final' ? 'selected' : '' }}>Final Evaluation (Internship Completion)</option>
                    </select>
                </div>

                <!-- Rating Criteria Grid (1-5 Scale) -->
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-4">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-700">Quantitative Rating Criteria (Scale: 1 = Unsatisfactory, 5 = Outstanding)</h4>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1">Overall Technical Performance *</label>
                            <select name="performance_rating" required class="w-full p-2 text-xs bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 font-semibold">
                                <option value="5" selected>5 — Outstanding / Exceeds Expectations</option>
                                <option value="4">4 — Very Good / Solid Contributor</option>
                                <option value="3">3 — Satisfactory / Met Core Requirements</option>
                                <option value="2">2 — Needs Improvement</option>
                                <option value="1">1 — Unsatisfactory</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1">Technical Skills & Execution *</label>
                            <select name="technical_skills_rating" required class="w-full p-2 text-xs bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 font-semibold">
                                <option value="5" selected>5 — Exceptional Problem Solving</option>
                                <option value="4">4 — Good Code Quality & Architecture</option>
                                <option value="3">3 — Adequate Coding Ability</option>
                                <option value="2">2 — Struggles with Frameworks</option>
                                <option value="1">1 — Substandard Technical Work</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1">Soft Skills & Communication *</label>
                            <select name="soft_skills_rating" required class="w-full p-2 text-xs bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 font-semibold">
                                <option value="5">5 — Proactive & Team Collaborator</option>
                                <option value="4" selected>4 — Clear Communicator</option>
                                <option value="3">3 — Standard Team Interaction</option>
                                <option value="2">2 — Infrequent Updates</option>
                                <option value="1">1 — Poor Communication</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1">Attendance & Punctuality *</label>
                            <select name="attendance_punctuality_rating" required class="w-full p-2 text-xs bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 font-semibold">
                                <option value="5" selected>5 — Always Punctual & Reliable</option>
                                <option value="4">4 — Occasional Minor Lateness</option>
                                <option value="3">3 — Acceptable Attendance</option>
                                <option value="2">2 — Frequent Absences</option>
                                <option value="1">1 — Unreliable</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="recommendation" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Faculty Academic Recommendation *</label>
                    <select id="recommendation" name="recommendation" required class="w-full p-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 text-slate-900 font-bold">
                        <option value="outstanding" {{ old('recommendation') === 'outstanding' ? 'selected' : '' }}>Outstanding — Highly Commended</option>
                        <option value="satisfactory" {{ old('recommendation') === 'satisfactory' ? 'selected' : '' }}>Satisfactory — Meets Institutional Standards</option>
                        <option value="needs_improvement" {{ old('recommendation') === 'needs_improvement' ? 'selected' : '' }}>Needs Improvement — Conditional Pass</option>
                        <option value="unsatisfactory" {{ old('recommendation') === 'unsatisfactory' ? 'selected' : '' }}>Unsatisfactory — Fail</option>
                    </select>
                </div>

                <div>
                    <label for="comments" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Mentor Narrative Comments & Guidance *</label>
                    <textarea id="comments" 
                              name="comments" 
                              rows="4" 
                              required 
                              placeholder="Provide detailed feedback on projects completed, technical strengths, and areas recommended for professional growth..."
                              class="w-full p-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 text-slate-900">{{ old('comments') }}</textarea>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('company.evaluations.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold text-xs hover:bg-slate-50 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-600 text-white font-bold text-xs hover:bg-emerald-700 shadow-md shadow-emerald-600/20 transition-all flex items-center gap-2">
                        <i data-lucide="check-circle" class="w-4 h-4"></i>
                        <span>Submit Evaluation to University</span>
                    </button>
                </div>
            </form>
        </div>

    </div>
</x-layout>
