@props([
    'name'  => 'nif',
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
        v-mask-nif
        placeholder="000000000"
        maxlength="9"
        inputmode="numeric"
        class="flex min-h-[39px] w-full rounded-md border px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400"
    />
</div>
