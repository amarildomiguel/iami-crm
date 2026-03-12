<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.hearings.view.title')
    </x-slot>

    <div class="flex flex-col gap-4">
        <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 dark:border-gray-800 dark:bg-gray-900">
            <div class="flex flex-col gap-2">
                <x-admin::breadcrumbs name="hearings.view" :entity="$hearing" />
                <div class="text-xl font-bold dark:text-white">
                    {{ $hearing->hearing_type }} — {{ $hearing->court }}
                </div>
            </div>

            <div class="flex items-center gap-x-2.5">
                <a
                    href="{{ route('admin.hearings.index') }}"
                    class="transparent-button"
                >
                    @lang('admin::app.hearings.view.back-btn')
                </a>
            </div>
        </div>

        <div class="flex gap-5">
            <div class="flex flex-1 flex-col gap-4">
                {{-- Detalhes da Audiência --}}
                <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                    <div class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                        @lang('admin::app.hearings.view.details')
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <div class="text-xs text-gray-500">@lang('admin::app.hearings.view.process')</div>
                            <div class="font-medium dark:text-white">
                                @if ($hearing->lead)
                                    <a href="{{ route('admin.leads.view', $hearing->lead->id) }}" class="text-blue-600 hover:underline">
                                        {{ $hearing->lead->title }}
                                    </a>
                                @else
                                    —
                                @endif
                            </div>
                        </div>

                        <div>
                            <div class="text-xs text-gray-500">@lang('admin::app.hearings.view.type')</div>
                            <div class="font-medium dark:text-white">{{ $hearing->hearing_type }}</div>
                        </div>

                        <div>
                            <div class="text-xs text-gray-500">@lang('admin::app.hearings.view.scheduled-at')</div>
                            <div class="font-medium dark:text-white">
                                {{ $hearing->scheduled_at ? $hearing->scheduled_at->format('d/m/Y H:i') : '—' }}
                            </div>
                        </div>

                        <div>
                            <div class="text-xs text-gray-500">@lang('admin::app.hearings.view.status')</div>
                            <div class="font-medium dark:text-white">{{ ucfirst($hearing->status) }}</div>
                        </div>

                        <div>
                            <div class="text-xs text-gray-500">@lang('admin::app.hearings.view.court')</div>
                            <div class="font-medium dark:text-white">{{ $hearing->court }}</div>
                        </div>

                        <div>
                            <div class="text-xs text-gray-500">@lang('admin::app.hearings.view.court-room')</div>
                            <div class="font-medium dark:text-white">{{ $hearing->court_room ?: '—' }}</div>
                        </div>

                        <div>
                            <div class="text-xs text-gray-500">@lang('admin::app.hearings.view.judge')</div>
                            <div class="font-medium dark:text-white">{{ $hearing->judge_name ?: '—' }}</div>
                        </div>

                        <div>
                            <div class="text-xs text-gray-500">@lang('admin::app.hearings.view.lawyer')</div>
                            <div class="font-medium dark:text-white">{{ $hearing->user?->name ?: '—' }}</div>
                        </div>
                    </div>

                    @if ($hearing->notes)
                        <div class="mt-4">
                            <div class="text-xs text-gray-500">@lang('admin::app.hearings.view.notes')</div>
                            <div class="mt-1 text-sm dark:text-white">{{ $hearing->notes }}</div>
                        </div>
                    @endif

                    @if ($hearing->outcome)
                        <div class="mt-4">
                            <div class="text-xs text-gray-500">@lang('admin::app.hearings.view.outcome')</div>
                            <div class="mt-1 text-sm dark:text-white">{{ $hearing->outcome }}</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-admin::layouts>
