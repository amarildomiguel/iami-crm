<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.documents.index.title')
    </x-slot>

    <div class="flex flex-col gap-4">
        <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
            <div class="flex flex-col gap-2">
                <x-admin::breadcrumbs name="documents" />
                <div class="text-xl font-bold dark:text-white">
                    @lang('admin::app.documents.index.title')
                </div>
            </div>

            <div class="flex items-center gap-x-2.5">
                @if (bouncer()->hasPermission('documents.create'))
                    <a
                        href="{{ route('admin.documents.create') }}"
                        class="primary-button"
                    >
                        @lang('admin::app.documents.index.create-btn')
                    </a>
                @endif
            </div>
        </div>

        {!! view_render_event('admin.documents.index.datagrid.before') !!}

        <x-admin::datagrid :src="route('admin.documents.index')" />

        {!! view_render_event('admin.documents.index.datagrid.after') !!}
    </div>
</x-admin::layouts>
