@props([
    'variant' => 'primary',
    'href'    => null,
    'size'    => 'md',
])

@php
$variantMap = [
    'primary'       => 'bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700',
    'secondary'     => 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600',
    'danger'        => 'bg-red-600 text-white font-medium rounded-lg hover:bg-red-700',
    'link'          => 'text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 font-medium',
    'link-gray'     => 'text-gray-500 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 font-medium',
    'link-danger'   => 'text-red-500 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 font-medium',
    // 手機 bottom-sheet 專用（含自己的 padding 與 block layout，忽略 $size）
    'sheet-primary' => 'block w-full py-2.5 text-center text-sm font-medium text-indigo-600 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-700 rounded-lg hover:bg-indigo-50 dark:hover:bg-gray-700',
    'sheet-default' => 'block w-full py-2.5 text-center text-sm font-medium text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700',
    'sheet-danger'  => 'block w-full py-2.5 text-center text-sm font-medium text-red-600 dark:text-red-400 border border-red-200 dark:border-red-800 rounded-lg hover:bg-red-50 dark:hover:bg-gray-700',
];
$sizeMap = [
    'sm' => 'px-3 py-1.5 text-xs',
    'md' => 'px-4 py-2 text-sm',
    'lg' => 'px-6 py-2 text-sm',
];
$isSheet = str_starts_with($variant, 'sheet-');
$isLink  = str_starts_with($variant, 'link');
$variantClass = $variantMap[$variant] ?? $variantMap['primary'];
$sizeClass    = ($isSheet || $isLink) ? '' : ($sizeMap[$size] ?? $sizeMap['md']);
$baseClass    = $isSheet ? $variantClass : trim("inline-flex items-center $sizeClass $variantClass");
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $baseClass]) }}>{{ $slot }}</a>
@else
    <button {{ $attributes->merge(['class' => $baseClass]) }}>{{ $slot }}</button>
@endif
