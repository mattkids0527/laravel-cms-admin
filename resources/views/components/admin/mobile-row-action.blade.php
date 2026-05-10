@props(['title' => null, 'subtitle' => null])

{{--
    依賴父層 <tr x-data="{ open: false }"> 提供的 open 狀態。
    使用 admin.blade.php 中定義的 CSS：row-action-overlay, row-action-card, row-overlay-hidden
--}}
<div x-show="open"
     x-cloak
     x-transition:enter-start="row-overlay-hidden"
     x-transition:leave-end="row-overlay-hidden"
     class="row-action-overlay"
     @click.stop="open = false"
     style="display:none">
    <div class="row-action-card bg-white dark:bg-gray-800" @click.stop>
        @if($title)
            <p class="text-sm font-semibold text-gray-800 dark:text-gray-100 pb-3 mb-2 border-b border-gray-100 dark:border-gray-700">
                {{ $title }}
                @if($subtitle)
                    <span class="block text-xs font-normal text-gray-400 dark:text-gray-500 mt-0.5">{{ $subtitle }}</span>
                @endif
            </p>
        @endif
        <div class="space-y-2">
            {{ $slot }}
        </div>
    </div>
</div>
