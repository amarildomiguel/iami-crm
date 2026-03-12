{!! view_render_event('admin.dashboard.index.legal_lawyer_performance.before') !!}

<!-- Performance por Advogado Widget -->
<v-dashboard-lawyer-performance>
    <div class="light-shimmer-bg dark:shimmer h-[160px] rounded-lg"></div>
</v-dashboard-lawyer-performance>

{!! view_render_event('admin.dashboard.index.legal_lawyer_performance.after') !!}

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-dashboard-lawyer-performance-template"
    >
        <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
            <div class="border-b border-gray-200 px-4 py-3 dark:border-gray-800">
                <div class="flex items-center gap-2">
                    <span class="icon-user text-xl text-emerald-600 dark:text-emerald-400"></span>
                    <h3 class="text-sm font-semibold dark:text-white">
                        @lang('admin::app.dashboard.index.legal.lawyer-performance')
                    </h3>
                </div>
            </div>

            <div class="p-4">
                <template v-if="isLoading">
                    <div class="space-y-2">
                        <div class="light-shimmer-bg dark:shimmer h-8 rounded"></div>
                        <div class="light-shimmer-bg dark:shimmer h-8 rounded"></div>
                        <div class="light-shimmer-bg dark:shimmer h-8 rounded"></div>
                    </div>
                </template>

                <template v-else-if="report.lawyers && report.lawyers.length > 0">
                    <!-- Table Header -->
                    <div class="mb-2 grid grid-cols-4 gap-2 text-xs font-semibold text-gray-500 dark:text-gray-400">
                        <span class="col-span-2">@lang('admin::app.dashboard.index.legal.lawyer')</span>
                        <span class="text-center text-green-600 dark:text-green-400">@lang('admin::app.dashboard.index.legal.won')</span>
                        <span class="text-center text-red-600 dark:text-red-400">@lang('admin::app.dashboard.index.legal.lost')</span>
                    </div>

                    <div class="divide-y divide-gray-100 dark:divide-gray-800">
                        <div
                            v-for="lawyer in report.lawyers"
                            :key="lawyer.name"
                            class="grid grid-cols-4 gap-2 py-2 text-sm"
                        >
                            <div class="col-span-2 flex flex-col">
                                <span class="font-medium text-gray-800 dark:text-gray-200">@{{ lawyer.name }}</span>
                                <span class="text-xs text-gray-400">@{{ lawyer.total }} @lang('admin::app.dashboard.index.legal.processes')</span>
                            </div>
                            <span class="text-center font-semibold text-green-600 dark:text-green-400">@{{ lawyer.won }}</span>
                            <span class="text-center font-semibold text-red-600 dark:text-red-400">@{{ lawyer.lost }}</span>
                        </div>
                    </div>
                </template>

                <template v-else>
                    <div class="flex flex-col items-center gap-2 py-6 text-center">
                        <span class="icon-user text-4xl text-gray-300 dark:text-gray-600"></span>
                        <p class="text-sm text-gray-400 dark:text-gray-500">
                            @lang('admin::app.dashboard.index.legal.no-data')
                        </p>
                    </div>
                </template>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-dashboard-lawyer-performance', {
            template: '#v-dashboard-lawyer-performance-template',

            data() {
                return {
                    report: {},
                    isLoading: true,
                };
            },

            mounted() {
                this.getStats({});
                this.$emitter.on('reporting-filter-updated', this.getStats);
            },

            methods: {
                getStats(filters) {
                    this.isLoading = true;
                    const params = Object.assign({}, filters, { type: 'lawyer-performance' });

                    this.$axios.get("{{ route('admin.dashboard.stats') }}", { params })
                        .then(response => {
                            this.report = response.data.statistics;
                            this.isLoading = false;
                        })
                        .catch(() => { this.isLoading = false; });
                },
            },
        });
    </script>
@endPushOnce
