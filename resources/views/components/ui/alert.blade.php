@props(['type' => 'success', 'message' => null])

@php
$typeClasses = [
    'success' => 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300',
    'error'   => 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-300',
    'warning' => 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-300',
    'info'    => 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-300',
];
$colorClass = $typeClasses[$type] ?? $typeClasses['success'];
@endphp

@if($message)
<div class="mb-4 px-4 py-3 rounded-lg text-sm {{ $colorClass }}">
    {{ $message }}
</div>
@endif
