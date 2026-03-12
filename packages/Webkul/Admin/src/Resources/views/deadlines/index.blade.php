<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.deadlines.index.title')
    </x-slot>

    <div class="flex flex-col gap-4">
        <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
            <div class="flex flex-col gap-2">
                <x-admin::breadcrumbs name="deadlines" />
                <div class="text-xl font-bold dark:text-white">
                    @lang('admin::app.deadlines.index.title')
                </div>
            </div>

            <div class="flex items-center gap-x-2.5">
                <a
                    href="{{ route('admin.deadlines.calendar') }}"
                    class="secondary-button"
                >
                    @lang('admin::app.deadlines.index.calendar-btn')
                </a>

                @if (bouncer()->hasPermission('deadlines.create'))
                    <a
                        href="{{ route('admin.deadlines.create') }}"
                        class="primary-button"
                    >
                        @lang('admin::app.deadlines.index.create-btn')
                    </a>
                @endif
            </div>
        </div>

        {!! view_render_event('admin.deadlines.index.datagrid.before') !!}

        <x-admin::datagrid :src="route('admin.deadlines.index')" />

        {!! view_render_event('admin.deadlines.index.datagrid.after') !!}
    </div>
</x-admin::layouts>
