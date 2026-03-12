{!! view_render_event('admin.dashboard.index.legal_upcoming_hearings.before') !!}

<!-- Audiências da Semana Widget -->
<v-dashboard-upcoming-hearings>
    <div class="light-shimmer-bg dark:shimmer h-[200px] rounded-lg"></div>
</v-dashboard-upcoming-hearings>

{!! view_render_event('admin.dashboard.index.legal_upcoming_hearings.after') !!}

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-dashboard-upcoming-hearings-template"
    >
        <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
            <!-- Header -->
            <div class="flex items-center justify-between border-b border-gray-200 px-4 py-3 dark:border-gray-800">
                <div class="flex items-center gap-2">
                    <span class="icon-calendar text-xl text-indigo-600 dark:text-indigo-400"></span>
                    <h3 class="text-sm font-semibold dark:text-white">
                        @lang('admin::app.dashboard.index.legal.upcoming-hearings')
                    </h3>
                </div>
                <a
                    href="{{ route('admin.hearings.index') }}"
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

                <template v-else-if="report.hearings && report.hearings.length > 0">
                    <div class="divide-y divide-gray-100 dark:divide-gray-800">
                        <div
                            v-for="hearing in report.hearings"
                            :key="hearing.id"
                            class="flex items-start justify-between py-2"
                        >
                            <div class="flex flex-col gap-0.5">
                                <p class="text-sm font-medium text-gray-800 dark:text-gray-200">
                                    @{{ hearing.process_title }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    @{{ hearing.type }} — @{{ hearing.court }}
                                </p>
                            </div>
                            <div class="shrink-0 rounded-full bg-indigo-100 px-2 py-0.5 text-xs font-medium text-indigo-700 dark:bg-indigo-900 dark:text-indigo-300">
                                @{{ hearing.scheduled_at }}
                            </div>
                        </div>
                    </div>
                </template>

                <template v-else>
                    <div class="flex flex-col items-center gap-2 py-6 text-center">
                        <span class="icon-calendar text-4xl text-gray-300 dark:text-gray-600"></span>
                        <p class="text-sm text-gray-400 dark:text-gray-500">
                            @lang('admin::app.dashboard.index.legal.no-hearings-this-week')
                        </p>
                    </div>
                </template>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-dashboard-upcoming-hearings', {
            template: '#v-dashboard-upcoming-hearings-template',

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
                    const params = Object.assign({}, filters, { type: 'upcoming-hearings' });

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
