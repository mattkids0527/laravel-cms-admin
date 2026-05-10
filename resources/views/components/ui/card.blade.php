@props(['padding' => true])

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-gray-800 rounded-lg shadow ' . ($padding ? 'p-6' : 'overflow-hidden')]) }}>
    {{ $slot }}
</div>
