{!! view_render_event('admin.dashboard.index.legal_billable_hours.before') !!}

<!-- Horas Facturáveis Widget -->
<v-dashboard-billable-hours>
    <div class="light-shimmer-bg dark:shimmer h-[120px] rounded-lg"></div>
</v-dashboard-billable-hours>

{!! view_render_event('admin.dashboard.index.legal_billable_hours.after') !!}

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-dashboard-billable-hours-template"
    >
        <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
            <div class="border-b border-gray-200 px-4 py-3 dark:border-gray-800">
                <div class="flex items-center gap-2">
                    <span class="icon-clock text-xl text-teal-600 dark:text-teal-400"></span>
                    <h3 class="text-sm font-semibold dark:text-white">
                        @lang('admin::app.dashboard.index.legal.billable-hours')
                    </h3>
                </div>
            </div>

            <div class="p-4">
                <template v-if="isLoading">
                    <div class="light-shimmer-bg dark:shimmer h-12 rounded"></div>
                </template>
                <template v-else>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="flex flex-col gap-1">
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                @lang('admin::app.dashboard.index.legal.total-hours')
                            </p>
                            <p class="text-xl font-bold text-teal-600 dark:text-teal-400">
                                @{{ report.total_hours ?? 0 }}h
                            </p>
                        </div>
                        <div class="flex flex-col gap-1">
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                @lang('admin::app.dashboard.index.legal.total-amount')
                            </p>
                            <p class="text-xl font-bold text-green-600 dark:text-green-400">
                                @{{ report.total_amount ?? '0,00' }} Kz
                            </p>
                        </div>
                    </div>

                    <div
                        v-if="report.by_lawyer && report.by_lawyer.length > 0"
                        class="mt-4 space-y-1 border-t border-gray-100 pt-3 dark:border-gray-800"
                    >
                        <p class="mb-2 text-xs font-semibold text-gray-500 dark:text-gray-400">
                            @lang('admin::app.dashboard.index.legal.by-lawyer')
                        </p>
                        <div
                            v-for="lawyer in report.by_lawyer"
                            :key="lawyer.name"
                            class="flex items-center justify-between"
                        >
                            <p class="text-xs text-gray-700 dark:text-gray-300">@{{ lawyer.name }}</p>
                            <div class="flex gap-3 text-xs">
                                <span class="text-teal-600 dark:text-teal-400">@{{ lawyer.total_hours }}h</span>
                                <span class="text-green-600 dark:text-green-400">@{{ lawyer.total_amount }} Kz</span>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-dashboard-billable-hours', {
            template: '#v-dashboard-billable-hours-template',

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
                    const params = Object.assign({}, filters, { type: 'billable-hours' });

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
