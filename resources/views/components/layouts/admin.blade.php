<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name') }} - 後台管理</title>
    {{-- 同步讀取 localStorage，在頁面首次繪製前預套用偏好，避免閃爍 --}}
    <script data-navigate-once>
        if (localStorage.getItem('admin_theme') === 'dark') {
            document.documentElement.classList.add('dark');
        }
        if (localStorage.getItem('admin_sidebar') === 'collapsed') {
            document.documentElement.setAttribute('data-sidebar', 'collapsed');
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        .layout-root { display: flex; height: 100vh; overflow: hidden; }
        .layout-main { flex: 1 1 0%; min-width: 0; display: flex; flex-direction: column; overflow: hidden; }
        .layout-header { height: 4rem; display: flex; align-items: center; padding-left: 1.5rem; padding-right: 1.5rem; flex-shrink: 0; }
        .layout-content { flex: 1 1 0%; overflow-y: auto; padding: 1.5rem; }

        /* 桌機側邊欄：flex 佈局，寬度可收合 */
        @media (min-width: 768px) {
            .sidebar { width: 16rem; flex-shrink: 0; transition: width 300ms; overflow: hidden; }
            .sidebar.collapsed { width: 4rem; }
            html[data-sidebar="collapsed"] .sidebar { width: 4rem; }
        }

        /* 手機側邊欄：固定 overlay drawer，預設隱藏 */
        @media (max-width: 767px) {
            .sidebar {
                position: fixed;
                top: 0; left: 0; bottom: 0;
                width: 16rem;
                transform: translateX(-100%);
                transition: transform 300ms ease;
                z-index: 50;
                overflow: hidden;
            }
            .sidebar.mobile-open { transform: translateX(0); }
            .sidebar-desktop-toggle { display: none; }
        }

        /* 桌機：隱藏手機專屬元素 */
        @media (min-width: 768px) {
            .sidebar-mobile-toggle { display: none; }
            .sidebar-mobile-backdrop { display: none !important; }
        }

        /* 手機側邊欄遮罩：定位由 CSS 控制，不依賴 Tailwind */
        .sidebar-mobile-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 40;
        }

        /* 表格操作欄：手機隱藏欄位與行內按鈕，改為點列彈出 */
        @media (max-width: 767px) {
            .table-desktop-actions { display: none; }
            .table-action-th       { display: none; }
            .table-action-td       { padding: 0 !important; width: 0; }
        }

        /* 手機行點擊游標提示 */
        .mobile-clickable-row { cursor: default; }
        @media (max-width: 767px) {
            .mobile-clickable-row { cursor: pointer; }
        }

        /* 手機操作底部彈出表 */
        .row-action-overlay {
            position: fixed;
            inset: 0;
            z-index: 50;
            display: flex;
            align-items: flex-end;
            justify-content: center;
            background: rgba(0, 0, 0, 0.45);
            transition: opacity 200ms ease;
        }
        .row-action-card {
            position: relative;
            width: 100%;
            max-width: 480px;
            border-radius: 1rem 1rem 0 0;
            padding: 1.25rem 1.25rem 2rem;
            transition: transform 280ms cubic-bezier(0.32, 0.72, 0, 1);
        }
        /* Alpine transition helper：進入前 / 離開後的隱藏狀態 */
        .row-overlay-hidden { opacity: 0; }
        .row-overlay-hidden .row-action-card { transform: translateY(4rem); }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{--
        首次載入情境：Alpine 尚未啟動，透過 alpine:init 事件註冊。
        data-navigate-once：Livewire SPA 導航時不重新執行此 script，保留既有 store 狀態。
    --}}
    <script data-navigate-once>
    (function () {
        function registerStore() {
            Alpine.store('appearance', {
                theme: localStorage.getItem('admin_theme') || 'light',
                sidebarOpen: localStorage.getItem('admin_sidebar') !== 'collapsed',
                mobileOpen: false,

                applyTheme: function () {
                    document.documentElement.classList.toggle('dark', this.theme === 'dark');
                },
                toggleTheme: function () {
                    this.theme = this.theme === 'dark' ? 'light' : 'dark';
                    localStorage.setItem('admin_theme', this.theme);
                    this.applyTheme();
                },
                toggleSidebar: function () {
                    this.sidebarOpen = !this.sidebarOpen;
                    localStorage.setItem('admin_sidebar', this.sidebarOpen ? 'expanded' : 'collapsed');
                    if (this.sidebarOpen) {
                        document.documentElement.removeAttribute('data-sidebar');
                    } else {
                        document.documentElement.setAttribute('data-sidebar', 'collapsed');
                    }
                },
                toggleMobile: function () {
                    this.mobileOpen = !this.mobileOpen;
                },
                closeMobile: function () {
                    this.mobileOpen = false;
                },
                setTheme: function (value) {
                    this.theme = value;
                    localStorage.setItem('admin_theme', value);
                    this.applyTheme();
                },
                setSidebar: function (value) {
                    this.sidebarOpen = value;
                    localStorage.setItem('admin_sidebar', value ? 'expanded' : 'collapsed');
                    if (value) {
                        document.documentElement.removeAttribute('data-sidebar');
                    } else {
                        document.documentElement.setAttribute('data-sidebar', 'collapsed');
                    }
                },
                reset: function () {
                    this.setTheme('light');
                    this.setSidebar(true);
                }
            });
        }

        if (window.Alpine) {
            registerStore();
        } else {
            document.addEventListener('alpine:init', registerStore);
        }

        // Livewire SPA 導航後，<html> attributes 會被重設，dark class 因此遺失。
        // 每次導航完成後重新呼叫 applyTheme() 補回正確的 class。
        document.addEventListener('livewire:navigated', function () {
            if (window.Alpine) {
                Alpine.store('appearance').applyTheme();
                Alpine.store('appearance').closeMobile();
            }
        });
    })();
    </script>
    @stack('styles')
    @livewireStyles
</head>
<body class="min-h-screen bg-gray-100 dark:bg-gray-950">

    <div class="layout-root" x-data>

        {{-- 手機側邊欄背景遮罩 --}}
        <div x-show="$store.appearance.mobileOpen"
             x-cloak
             @click="$store.appearance.closeMobile()"
             class="sidebar-mobile-backdrop"
             style="display:none">
        </div>

        {{-- 側邊選單 --}}
        <aside :class="{ 'collapsed': !$store.appearance.sidebarOpen, 'mobile-open': $store.appearance.mobileOpen }"
               class="sidebar bg-gray-900 dark:bg-gray-950 text-white flex flex-col">

            {{-- Logo + Toggle --}}
            <div class="h-16 flex items-center px-4 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700 justify-between flex-shrink-0">
                <span class="text-lg font-semibold tracking-wide truncate text-gray-800 dark:text-gray-100"
                      x-show="$store.appearance.sidebarOpen || $store.appearance.mobileOpen" x-cloak>
                    {{ config('app.name') }}
                </span>
                <span class="text-sm font-bold text-gray-800 dark:text-gray-100"
                      x-show="!$store.appearance.sidebarOpen && !$store.appearance.mobileOpen" x-cloak>
                    {{ mb_strtoupper(mb_substr(config('app.name'), 0, 1)) }}
                </span>
                <button @click="$store.appearance.toggleSidebar()"
                        class="sidebar-desktop-toggle p-1 rounded hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-white flex-shrink-0 ml-1">
                    <svg x-show="$store.appearance.sidebarOpen" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
                    </svg>
                    <svg x-show="!$store.appearance.sidebarOpen" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>

            {{-- 動態導覽選單 --}}
            @auth('admin')
            <nav class="flex-1 px-2 py-6 space-y-4 overflow-y-auto">
                @php
                    $visibleMenus = app(\Modules\Menu\App\Services\AdminMenuService::class)->getVisibleMenus(auth('admin')->user());
                @endphp

                @foreach ($visibleMenus as $group)
                    @if ($group->isGroup())
                        <div>
                            <p class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1"
                               x-show="$store.appearance.sidebarOpen || $store.appearance.mobileOpen" x-cloak>
                                {{ $group->name }}
                            </p>
                            <div class="space-y-1">
                                @foreach ($group->children as $item)
                                    <a href="{{ route($item->route_name) }}" wire:navigate
                                       x-data="{ tooltipVisible: false }"
                                       @mouseenter="if (!$store.appearance.sidebarOpen && !$store.appearance.mobileOpen) tooltipVisible = true"
                                       @mouseleave="tooltipVisible = false"
                                       class="relative flex items-center px-3 py-2 rounded-md text-sm font-medium
                                              {{ request()->routeIs(rtrim($item->route_name, '.index') . '*')
                                                    ? 'bg-gray-700 text-white'
                                                    : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                                        <span class="w-6 h-6 rounded-full bg-gray-600 flex items-center justify-center text-xs font-bold flex-shrink-0">
                                            {{ mb_strtoupper(mb_substr($item->name, 0, 1)) }}
                                        </span>
                                        <span class="ml-2 truncate"
                                              x-show="$store.appearance.sidebarOpen || $store.appearance.mobileOpen" x-cloak>
                                            {{ $item->name }}
                                        </span>
                                        <span x-show="tooltipVisible" x-cloak
                                              class="absolute left-full ml-2 px-2 py-1 bg-gray-800 text-white text-xs rounded whitespace-nowrap z-50 pointer-events-none shadow-lg">
                                            {{ $item->name }}
                                        </span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <a href="{{ route($group->route_name) }}" wire:navigate
                           x-data="{ tooltipVisible: false }"
                           @mouseenter="if (!$store.appearance.sidebarOpen && !$store.appearance.mobileOpen) tooltipVisible = true"
                           @mouseleave="tooltipVisible = false"
                           class="relative flex items-center px-3 py-2 rounded-md text-sm font-medium
                                  {{ request()->routeIs($group->route_name)
                                        ? 'bg-gray-700 text-white'
                                        : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                            <span class="w-6 h-6 rounded-full bg-gray-600 flex items-center justify-center text-xs font-bold flex-shrink-0">
                                {{ mb_strtoupper(mb_substr($group->name, 0, 1)) }}
                            </span>
                            <span class="ml-2 truncate"
                                  x-show="$store.appearance.sidebarOpen || $store.appearance.mobileOpen" x-cloak>
                                {{ $group->name }}
                            </span>
                            <span x-show="tooltipVisible" x-cloak
                                  class="absolute left-full ml-2 px-2 py-1 bg-gray-800 text-white text-xs rounded whitespace-nowrap z-50 pointer-events-none shadow-lg">
                                {{ $group->name }}
                            </span>
                        </a>
                    @endif
                @endforeach
            </nav>
            @endauth

            {{-- 底部使用者資訊 + 登出 --}}
            <div class="p-4 border-t border-gray-700 dark:border-gray-800 flex-shrink-0">
                <div class="flex items-center mb-3" :class="($store.appearance.sidebarOpen || $store.appearance.mobileOpen) ? 'space-x-3' : 'justify-center'">
                    <div class="w-8 h-8 rounded-full bg-gray-600 flex items-center justify-center text-sm font-medium flex-shrink-0">
                        {{ mb_strtoupper(mb_substr(auth()->user()->name ?? 'A', 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0"
                         x-show="$store.appearance.sidebarOpen || $store.appearance.mobileOpen" x-cloak>
                        <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name ?? '' }}</p>
                        <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email ?? '' }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit"
                            x-data="{ tooltipVisible: false }"
                            @mouseenter="if (!$store.appearance.sidebarOpen && !$store.appearance.mobileOpen) tooltipVisible = true"
                            @mouseleave="tooltipVisible = false"
                            class="relative w-full text-left px-3 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white rounded-md flex items-center">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span class="ml-2"
                              x-show="$store.appearance.sidebarOpen || $store.appearance.mobileOpen" x-cloak>登出</span>
                        <span x-show="tooltipVisible" x-cloak
                              class="absolute left-full ml-2 px-2 py-1 bg-gray-800 text-white text-xs rounded whitespace-nowrap z-50 pointer-events-none shadow-lg">
                            登出
                        </span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- 主內容區 --}}
        <div class="layout-main">
            {{-- 頂部列 --}}
            <header class="layout-header bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
                {{-- 手機漢堡選單按鈕（桌機隱藏） --}}
                <button @click="$store.appearance.toggleMobile()"
                        class="sidebar-mobile-toggle mr-3 p-1.5 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                <h1 class="text-lg font-semibold text-gray-800 dark:text-gray-100 flex-1">{{ $title ?? '儀表板' }}</h1>

                {{-- 亮色顯示太陽、暗色顯示月亮（代表當前模式）--}}
                <button @click="$store.appearance.toggleTheme()"
                        class="p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <svg x-show="$store.appearance.theme === 'light'" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <svg x-show="$store.appearance.theme === 'dark'" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                </button>
            </header>

            {{-- 頁面內容 --}}
            <main class="layout-content">
                {{ $slot }}
            </main>
        </div>

    </div>

    @livewireScripts
</body>
</html>
