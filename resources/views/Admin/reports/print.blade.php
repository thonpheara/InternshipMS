<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Official Internship Placement Report - {{ date('Y-m-d') }}</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Kantumruy+Pro:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', 'Kantumruy Pro', sans-serif;
        }
        body {
            background-color: #f8fafc;
            color: #0f172a;
            padding: 2rem;
            font-size: 12px;
            line-height: 1.5;
        }
        .report-card {
            max-width: 1000px;
            margin: 0 auto;
            background: #ffffff;
            padding: 2.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .university-name {
            font-size: 18px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #0f172a;
        }
        .office-name {
            font-size: 13px;
            color: #475569;
            font-weight: 600;
            margin-top: 2px;
        }
        .doc-title {
            font-size: 16px;
            font-weight: 800;
            margin-top: 12px;
            color: #047857;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .meta-bar {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            color: #64748b;
            margin-bottom: 1.5rem;
            padding: 0.5rem 0;
            border-bottom: 1px solid #e2e8f0;
        }
        .stats-summary {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        .stat-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            text-align: center;
        }
        .stat-label {
            font-size: 10px;
            text-transform: uppercase;
            font-weight: 700;
            color: #64748b;
        }
        .stat-value {
            font-size: 18px;
            font-weight: 800;
            color: #0f172a;
            margin-top: 2px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            margin-bottom: 2rem;
        }
        th {
            background-color: #f1f5f9;
            color: #334155;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 10px;
            padding: 8px 10px;
            border: 1px solid #cbd5e1;
            text-align: left;
        }
        td {
            padding: 8px 10px;
            border: 1px solid #e2e8f0;
            vertical-align: top;
        }
        tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
        }
        .badge-accepted {
            background-color: #d1fae5;
            color: #065f46;
        }
        .badge-other {
            background-color: #e2e8f0;
            color: #334155;
        }
        .signature-section {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
            margin-top: 3rem;
            padding-top: 1rem;
            text-align: center;
        }
        .sig-box {
            font-size: 11px;
        }
        .sig-title {
            font-weight: 700;
            color: #334155;
            margin-bottom: 50px;
        }
        .sig-line {
            border-top: 1px dashed #94a3b8;
            margin: 0 20px 6px;
        }
        .sig-name {
            color: #64748b;
            font-size: 10px;
        }
        .print-toolbar {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #ffffff;
            padding: 10px 16px;
            border-radius: 12px;
            box-shadow: 0 10px 25px -5px rgb(0 0 0 / 0.2);
            border: 1px solid #e2e8f0;
            display: flex;
            gap: 8px;
            z-index: 100;
        }
        .btn-print {
            background: #059669;
            color: #ffffff;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 12px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-print:hover {
            background: #047857;
        }
        .btn-close {
            background: #f1f5f9;
            color: #475569;
            border: none;
            padding: 8px 12px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 12px;
            cursor: pointer;
        }

        @media print {
            body {
                background: #ffffff;
                padding: 0;
            }
            .report-card {
                box-shadow: none;
                padding: 0;
                max-width: 100%;
            }
            .print-toolbar {
                display: none;
            }
            @page {
                size: A4 portrait;
                margin: 1.5cm;
            }
        }
    </style>
</head>
<body>

    <!-- Floating Print Controls -->
    <div class="print-toolbar">
        <button class="btn-print" onclick="window.print()">
            <i class="fa-solid fa-print"></i> Print Document
        </button>
        <button class="btn-close" onclick="window.close()">
            Close
        </button>
    </div>

    <div class="report-card">
        <!-- University Header -->
        <div class="header">
            <div class="university-name">University of South-East Asia (USEA)</div>
            <div class="office-name">Faculty of Science and Technology • Office of Internship & Career Placement</div>
            <div class="doc-title">Official Internship Placement & Candidate Outcome Report</div>
        </div>

        <!-- Meta Bar -->
        <div class="meta-bar">
            <div>
                <strong>Generated On:</strong> {{ now()->format('F d, Y - h:i A') }}
            </div>
            <div>
                <strong>Scope / Filter:</strong> 
                Status: {{ ucfirst(str_replace('_', ' ', $status)) }} 
                • Dept: {{ ucfirst($department) }} 
                • Year: {{ $year === 'all' ? 'All Years' : $year }}
            </div>
            <div>
                <strong>Document ID:</strong> IMS-REP-{{ date('Ymd-His') }}
            </div>
        </div>

        <!-- Summary KPIs -->
        <div class="stats-summary">
            <div class="stat-box">
                <div class="stat-label">Total Applications Evaluated</div>
                <div class="stat-value">{{ number_format($totalApplications) }}</div>
            </div>
            <div class="stat-box">
                <div class="stat-label">Confirmed Placements (Accepted)</div>
                <div class="stat-value" style="color: #047857;">{{ number_format($totalAccepted) }}</div>
            </div>
            <div class="stat-box">
                <div class="stat-label">Placement Success Rate</div>
                <div class="stat-value">{{ $placementRate }}%</div>
            </div>
        </div>

        <!-- Report Table -->
        <table>
            <thead>
                <tr>
                    <th style="width: 30px; text-align: center;">#</th>
                    <th>Candidate Details</th>
                    <th>Academic Program</th>
                    <th>Host Company</th>
                    <th>Internship Position</th>
                    <th>Stipend</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($applications as $index => $app)
                    @php
                        $student = $app->studentProfile;
                        $user = $student?->user;
                        $post = $app->internshipPost;
                        $company = $post?->companyProfile;
                    @endphp
                    <tr>
                        <td style="text-align: center; font-weight: 700;">{{ $index + 1 }}</td>
                        <td>
                            <strong>{{ $user?->name ?? 'N/A' }}</strong><br>
                            <span style="color: #64748b; font-size: 10px;">{{ $user?->email }}</span>
                        </td>
                        <td>
                            <strong>{{ $student?->student_id_number ?: 'ID: N/A' }}</strong><br>
                            <span>{{ $student?->major ?: 'N/A' }}</span><br>
                            <span style="color: #64748b; font-size: 10px;">GPA: {{ $student?->gpa !== null ? number_format($student->gpa, 2) : 'N/A' }}</span>
                        </td>
                        <td>
                            <strong>{{ $company?->company_name ?? 'N/A' }}</strong><br>
                            <span style="color: #64748b; font-size: 10px;">{{ $company?->location }}</span>
                        </td>
                        <td>
                            <strong>{{ $post?->title }}</strong><br>
                            <span style="color: #64748b; font-size: 10px;">{{ $post?->category }}</span>
                        </td>
                        <td>
                            {{ $post?->stipend ? '$' . number_format($post->stipend, 0) : 'Standard' }}
                        </td>
                        <td>
                            <span class="badge {{ $app->status === 'accepted' ? 'badge-accepted' : 'badge-other' }}">
                                {{ ucfirst(str_replace('_', ' ', $app->status)) }}
                            </span>
                        </td>
                        <td>
                            {{ $app->applied_at ? $app->applied_at->format('Y-m-d') : ($app->created_at ? $app->created_at->format('Y-m-d') : 'N/A') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 2rem; color: #64748b;">
                            No candidate placement records match the specified criteria.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Signature Section -->
        <div class="signature-section">
            <div class="sig-box">
                <div class="sig-title">Prepared By (Admin)</div>
                <div class="sig-line"></div>
                <div class="sig-name">Internship Program Coordinator</div>
            </div>
            <div class="sig-box">
                <div class="sig-title">Verified By</div>
                <div class="sig-line"></div>
                <div class="sig-name">Head of Academic Department</div>
            </div>
            <div class="sig-box">
                <div class="sig-title">Approved By</div>
                <div class="sig-line"></div>
                <div class="sig-name">Dean / Faculty Director</div>
            </div>
        </div>

    </div>

</body>
</html>
