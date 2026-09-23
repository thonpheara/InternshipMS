<x-layout>
    <div class="space-y-6">

        <!-- Institutional Metric KPI Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="p-5 rounded-2xl bg-white border border-[#E5E7EB] shadow-xs hover:border-[#059669]/40 transition-colors">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-500">Total Cohort Students</span>
                    <div class="w-10 h-10 rounded-xl bg-[#D1FAE5] text-[#059669] border border-[#A7F3D0]/60 flex items-center justify-center">
                        <i class="fa-solid fa-users w-4.5 h-4.5"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-baseline justify-between">
                    <h3 class="text-2xl font-black text-[#111827]">{{ $stats['total_students'] }}</h3>
                    <span class="text-xs font-bold text-[#059669]">{{ $stats['eligible_students'] }} Eligible</span>
                </div>
                <p class="text-xs text-gray-500 mt-1">Enrolled university candidates</p>
            </div>

            <div class="p-5 rounded-2xl bg-white border border-[#E5E7EB] shadow-xs hover:border-[#059669]/40 transition-colors">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-500">Pending Job Approvals</span>
                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 border border-amber-200 flex items-center justify-center">
                        <i class="fa-solid fa-clock-rotate-left w-4.5 h-4.5"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-baseline justify-between">
                    <h3 class="text-2xl font-black {{ $stats['pending_posts'] > 0 ? 'text-amber-600' : 'text-[#111827]' }}">
                        {{ $stats['pending_posts'] }}
                    </h3>
                    <span class="text-xs font-semibold text-gray-500">Company Postings</span>
                </div>
                <p class="text-xs text-gray-500 mt-1">Requires coordinator check</p>
            </div>

            <div class="p-5 rounded-2xl bg-white border border-[#E5E7EB] shadow-xs hover:border-[#059669]/40 transition-colors">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-500">Active Placements</span>
                    <div class="w-10 h-10 rounded-xl bg-[#D1FAE5] text-[#059669] border border-[#A7F3D0]/60 flex items-center justify-center">
                        <i class="fa-solid fa-briefcase w-4.5 h-4.5"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-baseline justify-between">
                    <h3 class="text-2xl font-black text-[#111827]">{{ $stats['active_placements'] }}</h3>
                    <span class="text-xs font-bold {{ $stats['unassigned_supervisors'] > 0 ? 'text-rose-600' : 'text-[#059669]' }}">
                        {{ $stats['unassigned_supervisors'] }} Unassigned
                    </span>
                </div>
                <p class="text-xs text-gray-500 mt-1">Ongoing student internships</p>
            </div>

            <div class="p-5 rounded-2xl bg-white border border-[#E5E7EB] shadow-xs hover:border-[#059669]/40 transition-colors">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-500">Total Hours Logged</span>
                    <div class="w-10 h-10 rounded-xl bg-[#D1FAE5] text-[#059669] border border-[#A7F3D0]/60 flex items-center justify-center">
                        <i class="fa-solid fa-business-time w-4.5 h-4.5"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-baseline justify-between">
                    <h3 class="text-2xl font-black text-[#111827]">{{ number_format($stats['total_hours_logged'], 0) }}h</h3>
                    <span class="text-xs font-semibold text-gray-500">Cohort Total</span>
                </div>
                <p class="text-xs text-gray-500 mt-1">Verified timesheet hours</p>
            </div>
        </div>

        <!-- Analytics & Job Moderation Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">
            
            <!-- Left: Applicant Growth Analytics Chart (lg:col-span-7 xl:col-span-8) -->
            <div class="lg:col-span-7 xl:col-span-8 p-6 sm:p-7 rounded-3xl bg-white border border-[#E5E7EB] shadow-xs flex flex-col justify-between"
                 x-data="applicantGrowthChart()">
                
                <!-- Card Header with Title & Period Filter -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h4 class="text-lg font-bold text-[#111827] tracking-tight">Applicant Growth Analytics</h4>
                        <p class="text-xs sm:text-sm text-gray-500 mt-0.5 font-medium">Monthly candidate acquisition per department</p>
                    </div>

                    <!-- Toggle Pill Switcher -->
                    <div class="inline-flex items-center gap-1 self-start sm:self-auto bg-gray-50/80 p-1 rounded-2xl border border-gray-100">
                        <button type="button" 
                                @click="setPeriod('thisYear')" 
                                :class="period === 'thisYear' ? 'bg-[#2563EB] text-white shadow-xs font-semibold' : 'text-gray-600 hover:text-[#111827] bg-transparent font-medium'"
                                class="px-4 py-1.5 text-xs rounded-xl transition-all cursor-pointer">
                            This Year
                        </button>
                        <button type="button" 
                                @click="setPeriod('lastYear')" 
                                :class="period === 'lastYear' ? 'bg-[#2563EB] text-white shadow-xs font-semibold' : 'text-gray-600 hover:text-[#111827] bg-transparent font-medium'"
                                class="px-4 py-1.5 text-xs rounded-xl transition-all cursor-pointer">
                            Last Year
                        </button>
                    </div>
                </div>

                <!-- Chart Canvas Area -->
                <div class="relative w-full h-[280px] sm:h-[300px] mt-6">
                    <canvas id="applicantGrowthCanvas" class="w-full h-full"></canvas>
                </div>

                <!-- Bottom Legend matching user screenshot -->
                <div class="flex items-center justify-center gap-7 pt-4 pb-1">
                    <div class="flex items-center gap-2.5">
                        <span class="w-7 h-3 rounded-xs bg-[#2563EB] inline-block"></span>
                        <span class="text-xs font-medium text-gray-600">Engineering</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <span class="w-7 h-3 rounded-xs bg-[#10B981] inline-block"></span>
                        <span class="text-xs font-medium text-gray-600">Design</span>
                    </div>
                </div>
            </div>

            <!-- Right: Postings Needing Moderation (lg:col-span-5 xl:col-span-4) -->
            <div class="lg:col-span-5 xl:col-span-4 p-6 rounded-3xl bg-white border border-[#E5E7EB] shadow-xs flex flex-col justify-between space-y-4">
                <div>
                    <div class="flex items-center justify-between pb-3 border-b border-[#E5E7EB]">
                        <h4 class="text-sm font-bold text-[#111827] uppercase tracking-wider flex items-center gap-2">
                            <i class="fa-solid fa-square-check w-4 h-4 text-[#059669]"></i>
                            Postings Needing Moderation
                        </h4>
                        <a href="{{ route('admin.approvals.index') }}" class="text-xs font-semibold text-[#059669] hover:underline">Review Queue</a>
                    </div>

                    @if ($pendingPosts->isNotEmpty())
                        <div class="divide-y divide-gray-100 max-h-[330px] overflow-y-auto pr-1">
                            @foreach ($pendingPosts as $post)
                                <div class="py-3.5 flex items-start justify-between gap-3">
                                    <div class="min-w-0 flex-1">
                                        <h5 class="text-xs font-bold text-[#111827] truncate">{{ $post->title }}</h5>
                                        <p class="text-xs text-gray-600 mt-0.5 truncate">{{ $post->companyProfile->company_name ?? 'Employer' }} • {{ $post->location }}</p>
                                        <span class="text-[11px] text-gray-400 mt-0.5 block">Deadline: {{ \Carbon\Carbon::parse($post->deadline)->format('M d, Y') }}</span>
                                    </div>
                                    <div class="flex items-center gap-1.5 shrink-0">
                                        <form action="{{ route('admin.approvals.update', $post) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit" class="px-3 py-1.5 text-xs font-bold rounded-xl bg-[#059669] hover:bg-[#047857] text-white shadow-xs transition-all cursor-pointer">
                                                Approve
                                            </button>
                                        </form>
                                        <a href="{{ route('admin.approvals.index') }}" class="p-1 text-gray-400 hover:text-[#111827]" title="View details">
                                            <i class="fa-solid fa-ellipsis-vertical w-4 h-4"></i>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="py-16 text-center text-gray-400 text-xs">
                            <i class="fa-solid fa-check-double w-8 h-8 mx-auto mb-2 text-[#059669]"></i>
                            No pending employer job postings awaiting moderation.
                        </div>
                    @endif
                </div>

                <div class="pt-3 border-t border-gray-100 text-right">
                    <a href="{{ route('admin.approvals.index') }}" class="text-xs font-semibold text-[#059669] hover:text-[#047857] inline-flex items-center gap-1">
                        Open Full Approvals Queue <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            </div>

        </div>

    </div>

    <!-- Chart.js Alpine Controller Logic -->
    <script>
        function applicantGrowthChart() {
            return {
                period: 'thisYear',
                chart: null,
                dataSets: {
                    thisYear: {
                        engineering: [64, 77, 89, 80, 94, 109, 124, 129, 144],
                        design: [39, 51, 59, 57, 67, 73, 87, 91, 104]
                    },
                    lastYear: {
                        engineering: [48, 56, 68, 62, 72, 85, 95, 102, 118],
                        design: [26, 35, 42, 40, 50, 58, 65, 71, 80]
                    }
                },
                init() {
                    this.$nextTick(() => {
                        this.renderChart();
                    });
                },
                renderChart() {
                    const canvas = document.getElementById('applicantGrowthCanvas');
                    if (!canvas || typeof Chart === 'undefined') return;
                    const ctx = canvas.getContext('2d');

                    this.chart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
                            datasets: [
                                {
                                    label: 'Engineering',
                                    data: this.dataSets.thisYear.engineering,
                                    backgroundColor: '#2563EB',
                                    borderRadius: { topLeft: 6, topRight: 6, bottomLeft: 0, bottomRight: 0 },
                                    borderSkipped: false,
                                    barPercentage: 0.65,
                                    categoryPercentage: 0.55
                                },
                                {
                                    label: 'Design',
                                    data: this.dataSets.thisYear.design,
                                    backgroundColor: '#10B981',
                                    borderRadius: { topLeft: 6, topRight: 6, bottomLeft: 0, bottomRight: 0 },
                                    borderSkipped: false,
                                    barPercentage: 0.65,
                                    categoryPercentage: 0.55
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            interaction: {
                                intersect: false,
                                mode: 'index'
                            },
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    backgroundColor: '#111827',
                                    titleColor: '#FFFFFF',
                                    bodyColor: '#F3F4F6',
                                    padding: 10,
                                    cornerRadius: 8,
                                    usePointStyle: true
                                }
                            },
                            scales: {
                                x: {
                                    grid: {
                                        display: false
                                    },
                                    border: {
                                        display: false
                                    },
                                    ticks: {
                                        color: '#6B7280',
                                        font: {
                                            family: "'Plus Jakarta Sans', sans-serif",
                                            size: 12
                                        }
                                    }
                                },
                                y: {
                                    min: 0,
                                    max: 160,
                                    border: {
                                        display: true,
                                        color: '#E5E7EB'
                                    },
                                    ticks: {
                                        stepSize: 20,
                                        color: '#6B7280',
                                        font: {
                                            family: "'Plus Jakarta Sans', sans-serif",
                                            size: 12
                                        }
                                    },
                                    grid: {
                                        color: '#F3F4F6',
                                        drawTicks: false
                                    }
                                }
                            }
                        }
                    });
                },
                setPeriod(period) {
                    this.period = period;
                    if (this.chart) {
                        this.chart.data.datasets[0].data = this.dataSets[period].engineering;
                        this.chart.data.datasets[1].data = this.dataSets[period].design;
                        this.chart.update();
                    }
                }
            };
        }
    </script>
</x-layout>

