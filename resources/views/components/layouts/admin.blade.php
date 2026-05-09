<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name') }} - 後台管理</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @livewireStyles
</head>
<body class="min-h-screen bg-gray-100">

    <div class="flex h-screen overflow-hidden">

        {{-- 側邊選單 --}}
        <aside class="w-64 bg-gray-900 text-white flex flex-col flex-shrink-0">
            {{-- Logo --}}
            <div class="h-16 flex items-center px-6 border-b border-gray-700">
                <span class="text-lg font-semibold tracking-wide">{{ config('app.name') }}</span>
            </div>

            {{-- 動態導覽選單 --}}
            @auth('admin')
            <nav class="flex-1 px-4 py-6 space-y-4 overflow-y-auto">
                @php
                    $visibleMenus = app(\App\Services\AdminMenuService::class)->getVisibleMenus(auth('admin')->user());
                @endphp

                @foreach ($visibleMenus as $group)
                    @if ($group->isGroup())
                        <div>
                            <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">
                                {{ $group->name }}
                            </p>
                            <div class="space-y-1">
                                @foreach ($group->children as $item)
                                    <a href="{{ route($item->route_name) }}"
                                       class="flex items-center px-3 py-2 rounded-md text-sm font-medium
                                              {{ request()->routeIs(rtrim($item->route_name, '.index') . '*')
                                                    ? 'bg-gray-700 text-white'
                                                    : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                                        {{ $item->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <a href="{{ route($group->route_name) }}"
                           class="flex items-center px-3 py-2 rounded-md text-sm font-medium
                                  {{ request()->routeIs($group->route_name)
                                        ? 'bg-gray-700 text-white'
                                        : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                            {{ $group->name }}
                        </a>
                    @endif
                @endforeach
            </nav>
            @endauth

            {{-- 登出 --}}
            <div class="p-4 border-t border-gray-700">
                <div class="flex items-center space-x-3 mb-3">
                    <div class="w-8 h-8 rounded-full bg-gray-600 flex items-center justify-center text-sm font-medium">
                        {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name ?? '' }}</p>
                        <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email ?? '' }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit"
                            class="w-full text-left px-3 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white rounded-md">
                        登出
                    </button>
                </form>
            </div>
        </aside>

        {{-- 主內容區 --}}
        <div class="flex-1 flex flex-col overflow-hidden">
            {{-- 頂部列 --}}
            <header class="h-16 bg-white border-b border-gray-200 flex items-center px-6 flex-shrink-0">
                <h1 class="text-lg font-semibold text-gray-800">{{ $title ?? '儀表板' }}</h1>
            </header>

            {{-- 頁面內容 --}}
            <main class="flex-1 overflow-y-auto p-6">
                {{ $slot }}
            </main>
        </div>

    </div>

    @livewireScripts
</body>
</html>
