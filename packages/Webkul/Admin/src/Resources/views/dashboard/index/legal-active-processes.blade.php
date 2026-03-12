{!! view_render_event('admin.dashboard.index.legal_active_processes.before') !!}

<!-- Processos Activos Widget -->
<v-dashboard-active-processes>
    <x-admin::shimmer.dashboard.index.over-all />
</v-dashboard-active-processes>

{!! view_render_event('admin.dashboard.index.legal_active_processes.after') !!}

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-dashboard-active-processes-template"
    >
        <template v-if="isLoading">
            <div class="light-shimmer-bg dark:shimmer h-[100px] rounded-lg"></div>
        </template>

        <template v-else>
            <div class="grid grid-cols-2 gap-4 max-sm:grid-cols-1">
                <!-- Processos Activos -->
                <div class="flex flex-col gap-2 rounded-lg border border-gray-200 bg-white px-4 py-5 dark:border-gray-800 dark:bg-gray-900">
                    <div class="flex items-center gap-2">
                        <span class="icon-leads text-xl text-blue-600 dark:text-blue-400"></span>
                        <p class="text-xs font-medium text-gray-600 dark:text-gray-300">
                            @lang('admin::app.dashboard.index.legal.active-processes')
                        </p>
                    </div>
                    <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                        @{{ report.active_count ?? 0 }}
                    </p>
                </div>

                <!-- Processos Urgentes -->
                <div class="flex flex-col gap-2 rounded-lg border border-red-200 bg-red-50 px-4 py-5 dark:border-red-900 dark:bg-red-950">
                    <div class="flex items-center gap-2">
                        <span class="icon-warning text-xl text-red-600 dark:text-red-400"></span>
                        <p class="text-xs font-medium text-red-600 dark:text-red-400">
                            @lang('admin::app.dashboard.index.legal.urgent-processes')
                        </p>
                    </div>
                    <p class="text-2xl font-bold text-red-600 dark:text-red-400">
                        @{{ report.urgent_count ?? 0 }}
                    </p>
                </div>
            </div>
        </template>
    </script>

    <script type="module">
        app.component('v-dashboard-active-processes', {
            template: '#v-dashboard-active-processes-template',

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
                    const params = Object.assign({}, filters, { type: 'active-processes' });

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
