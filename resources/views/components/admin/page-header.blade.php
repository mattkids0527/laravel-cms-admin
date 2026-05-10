@props(['title' => ''])

<div class="flex items-center justify-between mb-6">
    @if($title)
        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">{{ $title }}</h2>
    @endif
    @if($slot->isNotEmpty())
        <div class="flex items-center gap-3">
            {{ $slot }}
        </div>
    @endif
</div>
