{!! view_render_event('admin.dashboard.index.legal_upcoming_deadlines.before') !!}

<!-- Prazos a Vencer Widget -->
<v-dashboard-upcoming-deadlines>
    <div class="light-shimmer-bg dark:shimmer h-[200px] rounded-lg"></div>
</v-dashboard-upcoming-deadlines>

{!! view_render_event('admin.dashboard.index.legal_upcoming_deadlines.after') !!}

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-dashboard-upcoming-deadlines-template"
    >
        <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
            <!-- Header -->
            <div class="flex items-center justify-between border-b border-gray-200 px-4 py-3 dark:border-gray-800">
                <div class="flex items-center gap-2">
                    <span class="icon-deadline text-xl text-orange-600 dark:text-orange-400"></span>
                    <h3 class="text-sm font-semibold dark:text-white">
                        @lang('admin::app.dashboard.index.legal.upcoming-deadlines')
                    </h3>

                    <span
                        v-if="report.overdue > 0"
                        class="rounded-full bg-red-100 px-2 py-0.5 text-xs font-bold text-red-700 dark:bg-red-900 dark:text-red-300"
                    >
                        @{{ report.overdue }} @lang('admin::app.dashboard.index.legal.overdue')
                    </span>
                </div>
                <a
                    href="{{ route('admin.deadlines.index') }}"
                    class="text-xs text-blue-600 hover:underline dark:text-blue-400"
                >
                    @lang('admin::app.dashboard.index.legal.view-all')
                </a>
            </div>

            <!-- Body -->
            <div class="p-4">
                <template v-if="isLoading">
                    <div class="space-y-2">
                        <div class="light-shimmer-bg dark:shimmer h-8 rounded"></div>
                        <div class="light-shimmer-bg dark:shimmer h-8 rounded"></div>
                        <div class="light-shimmer-bg dark:shimmer h-8 rounded"></div>
                    </div>
                </template>

                <template v-else-if="report.deadlines && report.deadlines.length > 0">
                    <div class="divide-y divide-gray-100 dark:divide-gray-800">
                        <div
                            v-for="deadline in report.deadlines"
                            :key="deadline.id"
                            class="flex items-start justify-between py-2"
                        >
                            <div class="flex flex-col gap-0.5">
                                <p class="text-sm font-medium text-gray-800 dark:text-gray-200">
                                    @{{ deadline.title }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    @{{ deadline.process_title }}
                                </p>
                            </div>
                            <div
                                class="shrink-0 rounded-full px-2 py-0.5 text-xs font-medium"
                                :class="{
                                    'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300': deadline.days_left <= 1,
                                    'bg-orange-100 text-orange-700 dark:bg-orange-900 dark:text-orange-300': deadline.days_left > 1 && deadline.days_left <= 3,
                                    'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300': deadline.days_left > 3,
                                }"
                            >
                                @{{ deadline.due_date }}
                                <span v-if="deadline.days_left >= 0"> (@{{ deadline.days_left }}d)</span>
                            </div>
                        </div>
                    </div>
                </template>

                <template v-else>
                    <div class="flex flex-col items-center gap-2 py-6 text-center">
                        <span class="icon-deadline text-4xl text-gray-300 dark:text-gray-600"></span>
                        <p class="text-sm text-gray-400 dark:text-gray-500">
                            @lang('admin::app.dashboard.index.legal.no-upcoming-deadlines')
                        </p>
                    </div>
                </template>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-dashboard-upcoming-deadlines', {
            template: '#v-dashboard-upcoming-deadlines-template',

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
                    const params = Object.assign({}, filters, { type: 'upcoming-deadlines' });

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
