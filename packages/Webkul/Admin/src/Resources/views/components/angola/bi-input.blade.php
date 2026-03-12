@props([
    'name'  => 'bi_number',
    'value' => '',
    'label' => null,
])

<div class="flex flex-col gap-1">
    @if($label)
        <label class="text-xs font-medium text-gray-600 dark:text-gray-300">
            {{ $label }}
        </label>
    @endif

    <input
        type="text"
        name="{{ $name }}"
        value="{{ $value }}"
        v-mask-bi
        placeholder="000000000AA000"
        maxlength="14"
        class="flex min-h-[39px] w-full rounded-md border px-3 py-2 text-sm text-gray-600 font-mono tracking-wider transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400"
    />
    <span class="text-xs text-gray-400 dark:text-gray-500">
        @lang('admin::app.common.angola-address.bi-format')
    </span>
</div>
