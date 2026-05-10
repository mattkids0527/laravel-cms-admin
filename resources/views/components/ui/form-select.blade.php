@props([
    'label'    => null,
    'name'     => '',
    'required' => false,
])

@php
$hasError    = $name && $errors->has($name);
$borderClass = $hasError
    ? 'border-red-400'
    : 'border-gray-300 dark:border-gray-600';
@endphp

<div>
    @if($label)
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            {{ $label }}@if($required)<span class="text-red-500"> *</span>@endif
        </label>
    @endif
    <select
        @if($name) name="{{ $name }}" @endif
        {{ $attributes->merge(['class' => "w-full rounded-lg border $borderClass bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"]) }}
    >
        {{ $slot }}
    </select>
    @if($hasError)
        <p class="mt-1 text-xs text-red-500">{{ $errors->first($name) }}</p>
    @endif
</div>
