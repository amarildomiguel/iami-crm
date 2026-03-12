<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.documents.view.title')
    </x-slot>

    <div class="flex flex-col gap-4">
        <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 dark:border-gray-800 dark:bg-gray-900">
            <div class="flex flex-col gap-2">
                <x-admin::breadcrumbs name="documents.view" :entity="$document" />
                <div class="text-xl font-bold dark:text-white">{{ $document->title }}</div>
            </div>

            <div class="flex items-center gap-x-2.5">
                @if ($document->file_path)
                    <a
                        href="{{ route('admin.documents.download', $document->id) }}"
                        class="secondary-button"
                    >
                        @lang('admin::app.documents.view.download')
                    </a>
                @endif
                <a href="{{ route('admin.documents.index') }}" class="transparent-button">
                    @lang('admin::app.documents.view.back-btn')
                </a>
            </div>
        </div>

        <div class="flex gap-5">
            <div class="flex flex-1 flex-col gap-4">
                <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                    <div class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                        @lang('admin::app.documents.view.details')
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <div class="text-xs text-gray-500">@lang('admin::app.documents.view.type')</div>
                            <div class="font-medium dark:text-white">{{ $document->document_type }}</div>
                        </div>

                        <div>
                            <div class="text-xs text-gray-500">@lang('admin::app.documents.view.status')</div>
                            <div class="font-medium dark:text-white">{{ ucfirst($document->status) }}</div>
                        </div>

                        <div>
                            <div class="text-xs text-gray-500">@lang('admin::app.documents.view.process')</div>
                            <div class="font-medium dark:text-white">
                                @if ($document->lead)
                                    <a href="{{ route('admin.leads.view', $document->lead->id) }}" class="text-blue-600 hover:underline">
                                        {{ $document->lead->title }}
                                    </a>
                                @else
                                    —
                                @endif
                            </div>
                        </div>

                        <div>
                            <div class="text-xs text-gray-500">@lang('admin::app.documents.view.client')</div>
                            <div class="font-medium dark:text-white">{{ $document->person?->name ?: '—' }}</div>
                        </div>

                        <div>
                            <div class="text-xs text-gray-500">@lang('admin::app.documents.view.due-date')</div>
                            <div class="font-medium dark:text-white">
                                {{ $document->due_date ? $document->due_date->format('d/m/Y') : '—' }}
                            </div>
                        </div>

                        <div>
                            <div class="text-xs text-gray-500">@lang('admin::app.documents.view.filing-date')</div>
                            <div class="font-medium dark:text-white">
                                {{ $document->filing_date ? $document->filing_date->format('d/m/Y') : '—' }}
                            </div>
                        </div>

                        <div>
                            <div class="text-xs text-gray-500">@lang('admin::app.documents.view.court-reference')</div>
                            <div class="font-medium dark:text-white">{{ $document->court_reference ?: '—' }}</div>
                        </div>

                        <div>
                            <div class="text-xs text-gray-500">@lang('admin::app.documents.view.lawyer')</div>
                            <div class="font-medium dark:text-white">{{ $document->user?->name ?: '—' }}</div>
                        </div>
                    </div>

                    @if ($document->description)
                        <div class="mt-4">
                            <div class="text-xs text-gray-500">@lang('admin::app.documents.view.description')</div>
                            <div class="mt-1 text-sm dark:text-white">{{ $document->description }}</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-admin::layouts>
