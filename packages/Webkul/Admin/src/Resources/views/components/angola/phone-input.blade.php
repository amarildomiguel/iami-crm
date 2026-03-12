@props([
    'name'  => 'phone',
    'value' => '',
    'label' => null,
])

<div class="flex flex-col gap-1">
    @if($label)
        <label class="text-xs font-medium text-gray-600 dark:text-gray-300">
            {{ $label }}
        </label>
    @endif

    <div class="flex items-center gap-0 overflow-hidden rounded-md border border-gray-200 transition-all hover:border-gray-400 dark:border-gray-800 dark:hover:border-gray-400">
        <div class="flex shrink-0 items-center gap-1 border-r border-gray-200 bg-gray-50 px-2 py-2 text-xs text-gray-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
            <span>🇦🇴</span>
            <span>+244</span>
        </div>

        <input
            type="tel"
            name="{{ $name }}"
            value="{{ $value }}"
            v-mask-phone
            placeholder="9XX XXX XXX"
            class="flex h-[39px] w-full bg-white px-3 py-2 text-sm text-gray-600 dark:bg-gray-900 dark:text-gray-300"
        />
    </div>
</div>
