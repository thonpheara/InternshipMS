<x-layout>
    <div class="space-y-6">

        <!-- Institutional Metric KPI Cards (4 Metrics Overview) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- 1. Total Accounts -->
            <div class="p-5 rounded-2xl bg-white border border-[#E5E7EB] shadow-xs hover:border-[#059669]/40 transition-colors">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-500">Total Accounts</span>
                    <div class="w-10 h-10 rounded-xl bg-[#D1FAE5] text-[#059669] border border-[#A7F3D0]/60 flex items-center justify-center">
                        <i class="fa-solid fa-users w-4.5 h-4.5"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <h3 class="text-2xl font-black text-[#111827]">{{ $stats['total_accounts'] }}</h3>
                    <p class="text-xs text-gray-500 mt-1">Total registered users</p>
                </div>
            </div>

            <!-- 2. Student Interns -->
            <div class="p-5 rounded-2xl bg-white border border-[#E5E7EB] shadow-xs hover:border-[#059669]/40 transition-colors">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-500">Student Interns</span>
                    <div class="w-10 h-10 rounded-xl bg-[#D1FAE5] text-[#059669] border border-[#A7F3D0]/60 flex items-center justify-center">
                        <i class="fa-solid fa-graduation-cap w-4.5 h-4.5"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <h3 class="text-2xl font-black text-[#111827]">{{ $stats['total_students'] }}</h3>
                    <p class="text-xs text-gray-500 mt-1">Enrolled university candidates</p>
                </div>
            </div>

            <!-- 3. Host Companies -->
            <div class="p-5 rounded-2xl bg-white border border-[#E5E7EB] shadow-xs hover:border-[#059669]/40 transition-colors">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-500">Host Companies</span>
                    <div class="w-10 h-10 rounded-xl bg-[#D1FAE5] text-[#059669] border border-[#A7F3D0]/60 flex items-center justify-center">
                        <i class="fa-solid fa-building w-4.5 h-4.5"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-baseline justify-between">
                    <h3 class="text-2xl font-black text-[#111827]">{{ $stats['total_companies'] }}</h3>
                    @if(!empty($stats['pending_companies']) && $stats['pending_companies'] > 0)
                        <a href="{{ route('admin.companies.index', ['status' => 'pending']) }}" class="text-[10px] font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-md border border-amber-200 hover:bg-amber-100 transition-colors">
                            {{ $stats['pending_companies'] }} Needs Review
                        </a>
                    @else
                        <a href="{{ route('admin.companies.index') }}" class="text-[10px] font-bold text-[#059669] hover:underline">
                            Manage &rarr;
                        </a>
                    @endif
                </div>
                <p class="text-xs text-gray-500 mt-1">Registered employer partners</p>
            </div>

            <!-- 4. Confirmed Placements -->
            <div class="p-5 rounded-2xl bg-white border border-[#E5E7EB] shadow-xs hover:border-[#059669]/40 transition-colors">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-500">Confirmed Placements</span>
                    <div class="w-10 h-10 rounded-xl bg-[#D1FAE5] text-[#059669] border border-[#A7F3D0]/60 flex items-center justify-center">
                        <i class="fa-solid fa-award w-4.5 h-4.5"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-baseline justify-between">
                    <h3 class="text-2xl font-black text-[#059669]">{{ $stats['total_placements'] }}</h3>
                    <a href="{{ route('admin.reports.index', ['status' => 'accepted']) }}" class="text-[10px] font-bold text-[#059669] hover:underline">
                        View Report &rarr;
                    </a>
                </div>
                <p class="text-xs text-gray-500 mt-1">Students placed in internships</p>
            </div>
        </div>

        <!-- Analytics & Job Moderation Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch lg:h-[calc(100vh-19rem)]">
            
            <!-- Left: Application & Placement Trends Chart (lg:col-span-7) -->
            <div class="lg:col-span-7 p-6 sm:p-7 rounded-3xl bg-white border border-[#E5E7EB] shadow-xs flex flex-col justify-between h-full min-h-0"
                 x-data="applicantGrowthChart({{ \Illuminate\Support\Js::from($chartData) }})">
                
                <!-- Card Header with Title & Period Filter -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 shrink-0">
                    <div>
                        <h4 class="text-lg font-bold text-[#111827] tracking-tight">Application & Placement Trends</h4>
                        <p class="text-xs sm:text-sm text-gray-500 mt-0.5 font-medium">Monthly student applications submitted vs. accepted placements</p>
                    </div>

                    <!-- Toggle Pill Switcher -->
                    <div class="inline-flex items-center gap-1 self-start sm:self-auto bg-gray-50/80 p-1 rounded-2xl border border-gray-100 shrink-0">
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
                <div class="relative w-full flex-1 min-h-[160px] my-2">
                    <canvas id="applicantGrowthCanvas" class="w-full h-full"></canvas>
                </div>

                <!-- Bottom Legend matching user choice: Total Applied vs Accepted Placements -->
                <div class="flex items-center justify-center gap-7 pt-2 pb-1 shrink-0">
                    <div class="flex items-center gap-2.5">
                        <span class="w-7 h-3 rounded-xs bg-[#2563EB] inline-block"></span>
                        <span class="text-xs font-semibold text-gray-700">Total Applied</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <span class="w-7 h-3 rounded-xs bg-[#10B981] inline-block"></span>
                        <span class="text-xs font-semibold text-gray-700">Accepted Placements</span>
                    </div>
                </div>
            </div>

            <!-- Right: Postings Needing Moderation (lg:col-span-5) -->
            <div class="lg:col-span-5 p-6 rounded-3xl bg-white border border-[#E5E7EB] shadow-xs flex flex-col justify-between h-full min-h-0 space-y-4">
                <div class="flex-1 flex flex-col min-h-0">
                    <div class="flex items-center justify-between gap-3 pb-3 border-b border-[#E5E7EB] shrink-0">
                        <h4 class="text-sm font-bold text-[#111827] flex items-center gap-2 min-w-0">
                            <i class="fa-solid fa-square-check w-4 h-4 text-[#059669] shrink-0"></i>
                            <span class="whitespace-nowrap truncate">Postings Needing Moderation</span>
                        </h4>
                        <a href="{{ route('admin.approvals.index') }}" class="text-xs font-semibold text-[#059669] hover:underline whitespace-nowrap shrink-0">Review Queue</a>
                    </div>

                    @if ($pendingPosts->isNotEmpty())
                        <div class="divide-y divide-gray-100 flex-1 overflow-y-auto min-h-0 pr-1 my-1">
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
                        <div class="flex-1 flex flex-col items-center justify-center py-8 text-center text-gray-400 text-xs">
                            <i class="fa-solid fa-check-double w-8 h-8 mx-auto mb-2 text-[#059669]"></i>
                            No pending employer job postings awaiting moderation.
                        </div>
                    @endif
                </div>

                <div class="pt-3 border-t border-gray-100 text-right shrink-0">
                    <a href="{{ route('admin.approvals.index') }}" class="text-xs font-semibold text-[#059669] hover:text-[#047857] inline-flex items-center gap-1">
                        Open Full Approvals Queue <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            </div>

        </div>

    </div>

    <!-- Chart.js Alpine Controller Logic -->
    <script>
        function applicantGrowthChart(serverData) {
            return {
                period: 'thisYear',
                chart: null,
                dataSets: serverData || {
                    thisYear: {
                        applied: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                        accepted: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
                    },
                    lastYear: {
                        applied: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                        accepted: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
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

                    const currentApplied = this.dataSets[this.period]?.applied || [];
                    const currentAccepted = this.dataSets[this.period]?.accepted || [];

                    this.chart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                            datasets: [
                                {
                                    label: 'Total Applied',
                                    data: currentApplied,
                                    backgroundColor: '#2563EB',
                                    borderRadius: { topLeft: 6, topRight: 6, bottomLeft: 0, bottomRight: 0 },
                                    borderSkipped: false,
                                    barPercentage: 0.65,
                                    categoryPercentage: 0.55
                                },
                                {
                                    label: 'Accepted Placements',
                                    data: currentAccepted,
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
                                    beginAtZero: true,
                                    border: {
                                        display: true,
                                        color: '#E5E7EB'
                                    },
                                    ticks: {
                                        precision: 0,
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
                    if (this.chart && this.dataSets[period]) {
                        this.chart.data.datasets[0].data = this.dataSets[period].applied;
                        this.chart.data.datasets[1].data = this.dataSets[period].accepted;
                        this.chart.update();
                    }
                }
            };
        }
    </script>
</x-layout>

