@props(['color' => 'gray', 'shape' => 'pill'])

@php
$colorMap = [
    'green'  => 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300',
    'gray'   => 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400',
    'yellow' => 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-300',
    'red'    => 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-300',
    'indigo' => 'bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300',
    'blue'   => 'bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300',
];
$colorClass = $colorMap[$color] ?? $colorMap['gray'];
$shapeClass = $shape === 'pill' ? 'rounded-full' : 'rounded';
@endphp

<span {{ $attributes->merge(['class' => "inline-flex px-2 py-0.5 $shapeClass text-xs font-medium $colorClass"]) }}>
    {{ $slot }}
</span>
