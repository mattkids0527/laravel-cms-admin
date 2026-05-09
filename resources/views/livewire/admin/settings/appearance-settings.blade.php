<div x-data="{
        theme: $store.appearance.theme,
        sidebarOpen: $store.appearance.sidebarOpen
     }"
     x-init="
         $watch('$store.appearance.theme', val => theme = val);
         $watch('$store.appearance.sidebarOpen', val => sidebarOpen = val);
     ">

    <div class="max-w-lg space-y-6">

        {{-- 顯示主題 --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-base font-semibold text-gray-800 dark:text-gray-100 mb-4">顯示主題</h2>
            <div class="flex gap-3">
                <button
                    @click="$store.appearance.setTheme('light')"
                    :class="theme === 'light'
                        ? 'ring-2 ring-indigo-500 bg-indigo-50 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300'
                        : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'"
                    class="flex-1 py-3 rounded-lg text-sm font-medium transition">
                    亮色模式
                </button>
                <button
                    @click="$store.appearance.setTheme('dark')"
                    :class="theme === 'dark'
                        ? 'ring-2 ring-indigo-500 bg-indigo-50 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300'
                        : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'"
                    class="flex-1 py-3 rounded-lg text-sm font-medium transition">
                    暗色模式
                </button>
            </div>
        </div>

        {{-- 側邊選單 --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-base font-semibold text-gray-800 dark:text-gray-100 mb-4">側邊選單</h2>
            <div class="flex gap-3">
                <button
                    @click="$store.appearance.setSidebar(true)"
                    :class="sidebarOpen
                        ? 'ring-2 ring-indigo-500 bg-indigo-50 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300'
                        : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'"
                    class="flex-1 py-3 rounded-lg text-sm font-medium transition">
                    展開顯示
                </button>
                <button
                    @click="$store.appearance.setSidebar(false)"
                    :class="!sidebarOpen
                        ? 'ring-2 ring-indigo-500 bg-indigo-50 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300'
                        : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'"
                    class="flex-1 py-3 rounded-lg text-sm font-medium transition">
                    收合顯示
                </button>
            </div>
        </div>

        {{-- 重設偏好 --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-base font-semibold text-gray-800 dark:text-gray-100 mb-2">重設偏好</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">將主題重設為亮色、側邊選單重設為展開。</p>
            <button
                @click="$store.appearance.reset()"
                class="px-5 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                一鍵全部重設
            </button>
        </div>

    </div>
</div>
