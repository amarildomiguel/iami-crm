<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.deadlines.calendar.title')
    </x-slot>

    <div class="flex flex-col gap-4">
        <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 dark:border-gray-800 dark:bg-gray-900">
            <div class="flex flex-col gap-2">
                <x-admin::breadcrumbs name="deadlines.calendar" />
                <div class="text-xl font-bold dark:text-white">
                    @lang('admin::app.deadlines.calendar.title')
                </div>
            </div>

            <div class="flex items-center gap-x-2.5">
                <a href="{{ route('admin.deadlines.index') }}" class="transparent-button">
                    @lang('admin::app.deadlines.calendar.list-btn')
                </a>
                @if (bouncer()->hasPermission('deadlines.create'))
                    <a href="{{ route('admin.deadlines.create') }}" class="primary-button">
                        @lang('admin::app.deadlines.index.create-btn')
                    </a>
                @endif
            </div>
        </div>

        {{-- Alertas de Prazos Urgentes --}}
        @php
            $overdue   = $deadlines->where('status', 'pendente')->filter(fn($d) => $d->due_date->isPast());
            $expiringSoon = $deadlines->where('status', 'pendente')->filter(fn($d) => ! $d->due_date->isPast() && $d->due_date->diffInDays(now()) <= 5);
        @endphp

        @if ($overdue->count())
            <div class="rounded-lg border border-red-200 bg-red-50 p-4 dark:border-red-800 dark:bg-red-900/20">
                <div class="font-semibold text-red-700 dark:text-red-400">
                    @lang('admin::app.deadlines.calendar.overdue', ['count' => $overdue->count()])
                </div>
                <ul class="mt-2 space-y-1">
                    @foreach ($overdue as $deadline)
                        <li class="text-sm text-red-600 dark:text-red-300">
                            {{ $deadline->title }} —
                            <a href="{{ route('admin.leads.view', $deadline->lead_id) }}" class="underline">
                                {{ $deadline->lead?->title }}
                            </a>
                            — @lang('admin::app.deadlines.calendar.due'): {{ $deadline->due_date->format('d/m/Y') }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if ($expiringSoon->count())
            <div class="rounded-lg border border-yellow-200 bg-yellow-50 p-4 dark:border-yellow-800 dark:bg-yellow-900/20">
                <div class="font-semibold text-yellow-700 dark:text-yellow-400">
                    @lang('admin::app.deadlines.calendar.expiring-soon', ['count' => $expiringSoon->count()])
                </div>
                <ul class="mt-2 space-y-1">
                    @foreach ($expiringSoon as $deadline)
                        <li class="text-sm text-yellow-600 dark:text-yellow-300">
                            {{ $deadline->title }} —
                            <a href="{{ route('admin.leads.view', $deadline->lead_id) }}" class="underline">
                                {{ $deadline->lead?->title }}
                            </a>
                            — @lang('admin::app.deadlines.calendar.due'): {{ $deadline->due_date->format('d/m/Y') }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Lista de Todos os Prazos --}}
        <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
            <div class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                @lang('admin::app.deadlines.calendar.all-deadlines')
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <th class="px-3 py-2 text-left text-gray-600 dark:text-gray-400">@lang('admin::app.deadlines.index.datagrid.title')</th>
                            <th class="px-3 py-2 text-left text-gray-600 dark:text-gray-400">@lang('admin::app.deadlines.index.datagrid.process')</th>
                            <th class="px-3 py-2 text-left text-gray-600 dark:text-gray-400">@lang('admin::app.deadlines.index.datagrid.due-date')</th>
                            <th class="px-3 py-2 text-left text-gray-600 dark:text-gray-400">@lang('admin::app.deadlines.index.datagrid.priority')</th>
                            <th class="px-3 py-2 text-left text-gray-600 dark:text-gray-400">@lang('admin::app.deadlines.index.datagrid.status')</th>
                            <th class="px-3 py-2 text-left text-gray-600 dark:text-gray-400">@lang('admin::app.deadlines.index.datagrid.court-deadline')</th>
                            <th class="px-3 py-2 text-left text-gray-600 dark:text-gray-400">@lang('admin::app.deadlines.index.datagrid.lawyer')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($deadlines as $deadline)
                            @php
                                $rowClass = '';
                                if ($deadline->due_date->isPast()) {
                                    $rowClass = 'bg-red-50 dark:bg-red-900/10';
                                } elseif ($deadline->due_date->diffInDays(now()) <= 2) {
                                    $rowClass = 'bg-orange-50 dark:bg-orange-900/10';
                                } elseif ($deadline->due_date->diffInDays(now()) <= 5) {
                                    $rowClass = 'bg-yellow-50 dark:bg-yellow-900/10';
                                }
                            @endphp
                            <tr class="border-b border-gray-100 dark:border-gray-800 {{ $rowClass }}">
                                <td class="px-3 py-2 font-medium dark:text-white">{{ $deadline->title }}</td>
                                <td class="px-3 py-2">
                                    @if ($deadline->lead)
                                        <a href="{{ route('admin.leads.view', $deadline->lead_id) }}" class="text-blue-600 hover:underline">
                                            {{ $deadline->lead->title }}
                                        </a>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="px-3 py-2 dark:text-white">{{ $deadline->due_date->format('d/m/Y') }}</td>
                                <td class="px-3 py-2 dark:text-white">{{ ucfirst($deadline->priority) }}</td>
                                <td class="px-3 py-2 dark:text-white">{{ ucfirst($deadline->status) }}</td>
                                <td class="px-3 py-2 dark:text-white">{{ $deadline->court_deadline ? 'Sim' : 'Não' }}</td>
                                <td class="px-3 py-2 dark:text-white">{{ $deadline->user?->name ?: '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-3 py-6 text-center text-gray-500">
                                    @lang('admin::app.deadlines.calendar.no-deadlines')
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin::layouts>
