@props(['placeholder' => '搜尋...'])

<div class="flex flex-col sm:flex-row gap-3 mb-6">
    <input
        type="text"
        placeholder="{{ $placeholder }}"
        {{ $attributes->merge(['class' => 'flex-1 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500']) }}
    >
    @isset($filters)
        {{ $filters }}
    @endisset
    @isset($actions)
        {{ $actions }}
    @endisset
</div>
