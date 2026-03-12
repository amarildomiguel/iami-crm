{!! view_render_event('admin.dashboard.index.legal_processes_by_area.before') !!}

<!-- Processos por Área Jurídica Widget -->
<v-dashboard-processes-by-area>
    <div class="light-shimmer-bg dark:shimmer h-[240px] rounded-lg"></div>
</v-dashboard-processes-by-area>

{!! view_render_event('admin.dashboard.index.legal_processes_by_area.after') !!}

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-dashboard-processes-by-area-template"
    >
        <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
            <div class="border-b border-gray-200 px-4 py-3 dark:border-gray-800">
                <div class="flex items-center gap-2">
                    <span class="icon-leads text-xl text-purple-600 dark:text-purple-400"></span>
                    <h3 class="text-sm font-semibold dark:text-white">
                        @lang('admin::app.dashboard.index.legal.processes-by-area')
                    </h3>
                </div>
            </div>

            <div class="p-4">
                <template v-if="isLoading">
                    <div class="light-shimmer-bg dark:shimmer h-[180px] rounded"></div>
                </template>

                <template v-else-if="report.areas && report.areas.length > 0">
                    <canvas ref="areaChart" height="200"></canvas>
                </template>

                <template v-else>
                    <div class="flex flex-col items-center gap-2 py-6 text-center">
                        <span class="icon-leads text-4xl text-gray-300 dark:text-gray-600"></span>
                        <p class="text-sm text-gray-400 dark:text-gray-500">
                            @lang('admin::app.dashboard.index.legal.no-data')
                        </p>
                    </div>
                </template>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-dashboard-processes-by-area', {
            template: '#v-dashboard-processes-by-area-template',

            data() {
                return {
                    report: {},
                    isLoading: true,
                    chart: null,
                };
            },

            mounted() {
                this.getStats({});
                this.$emitter.on('reporting-filter-updated', this.getStats);
            },

            methods: {
                getStats(filters) {
                    this.isLoading = true;
                    const params = Object.assign({}, filters, { type: 'processes-by-legal-area' });

                    this.$axios.get("{{ route('admin.dashboard.stats') }}", { params })
                        .then(response => {
                            this.report = response.data.statistics;
                            this.isLoading = false;
                            this.$nextTick(() => this.renderChart());
                        })
                        .catch(() => { this.isLoading = false; });
                },

                renderChart() {
                    if (!this.report.areas || !this.report.areas.length || !this.$refs.areaChart) return;

                    if (this.chart) this.chart.destroy();

                    const isDark = document.documentElement.classList.contains('dark');
                    const textColor = isDark ? '#d1d5db' : '#374151';

                    const colors = [
                        '#6366f1', '#8b5cf6', '#06b6d4', '#10b981',
                        '#f59e0b', '#ef4444', '#ec4899', '#3b82f6',
                        '#84cc16', '#f97316', '#14b8a6', '#a855f7',
                    ];

                    this.chart = new Chart(this.$refs.areaChart, {
                        type: 'doughnut',
                        data: {
                            labels: this.report.areas.map(a => a.label),
                            datasets: [{
                                data: this.report.areas.map(a => a.total),
                                backgroundColor: colors.slice(0, this.report.areas.length),
                                borderWidth: 2,
                                borderColor: isDark ? '#1f2937' : '#ffffff',
                            }],
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        color: textColor,
                                        boxWidth: 12,
                                        font: { size: 11 },
                                    },
                                },
                            },
                        },
                    });
                },
            },
        });
    </script>
@endPushOnce
