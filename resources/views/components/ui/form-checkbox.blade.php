@props([
    'label'    => '',
    'name'     => '',
    'required' => false,
])

<label class="flex items-center gap-3 cursor-pointer">
    <input
        type="checkbox"
        @if($name) name="{{ $name }}" @endif
        {{ $attributes->merge(['class' => 'rounded border-gray-300 dark:border-gray-500 text-indigo-600 focus:ring-indigo-500']) }}
    >
    <span class="text-sm text-gray-700 dark:text-gray-300">
        {{ $label }}@if($required)<span class="text-red-500"> *</span>@endif
    </span>
    {{ $slot }}
</label>
