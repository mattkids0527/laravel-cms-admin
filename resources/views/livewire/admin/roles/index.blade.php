<div>
    @if (session('success'))
        <div class="mb-4 px-4 py-3 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300 rounded-lg text-sm">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="mb-4 px-4 py-3 bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-300 rounded-lg text-sm">{{ session('error') }}</div>
    @endif

    <div class="flex justify-end mb-6">
        <a href="{{ route('admin.roles.create') }}"
           class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">
            + 新增角色
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">角色名稱</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">描述</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">帳號數</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">類型</th>
                    <th class="px-6 py-3 text-right font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider table-action-th">操作</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($roles as $role)
                    <tr x-data="{ open: false }"
                        @click="if(window.innerWidth < 768) open = true"
                        class="mobile-clickable-row hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-gray-100">{{ $role->name }}</td>
                        <td class="px-6 py-4 text-gray-500 dark:text-gray-400">{{ $role->description ?? '-' }}</td>
                        <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $role->users_count }}</td>
                        <td class="px-6 py-4">
                            @if ($role->is_protected)
                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300">系統保護</span>
                            @else
                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400">一般</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right relative table-action-td">

                            {{-- 桌機：行內操作 --}}
                            <div class="table-desktop-actions space-x-2">
                                <a href="{{ route('admin.roles.edit', $role) }}"
                                   class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 font-medium">編輯</a>
                                @if (! $role->is_protected)
                                    @if ($confirmingDeleteId === $role->id)
                                        <span class="text-red-600 dark:text-red-400 font-medium">確認刪除？</span>
                                        <button wire:click="delete({{ $role->id }})" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 font-medium">是</button>
                                        <button wire:click="cancelDelete" class="text-gray-500 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 font-medium">否</button>
                                    @else
                                        <button wire:click="confirmDelete({{ $role->id }})"
                                                class="text-red-500 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 font-medium">刪除</button>
                                    @endif
                                @endif
                            </div>

                            {{-- 手機：點列底部彈出操作表 --}}
                            <div x-show="open" x-cloak
                                 x-transition:enter-start="row-overlay-hidden"
                                 x-transition:leave-end="row-overlay-hidden"
                                 class="row-action-overlay"
                                 @click.stop="open = false"
                                 style="display:none">
                                <div class="row-action-card bg-white dark:bg-gray-800" @click.stop>
                                    <p class="text-sm font-semibold text-gray-800 dark:text-gray-100 pb-3 mb-2 border-b border-gray-100 dark:border-gray-700">
                                        {{ $role->name }}
                                        @if ($role->description)
                                            <span class="block text-xs font-normal text-gray-400 dark:text-gray-500 mt-0.5">{{ $role->description }}</span>
                                        @endif
                                    </p>
                                    <div class="space-y-2">
                                        <a href="{{ route('admin.roles.edit', $role) }}"
                                           class="block w-full py-2.5 text-center text-sm font-medium text-indigo-600 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-700 rounded-lg hover:bg-indigo-50 dark:hover:bg-gray-700">
                                            編輯
                                        </a>
                                        @if (! $role->is_protected)
                                            @if ($confirmingDeleteId === $role->id)
                                                <button wire:click="delete({{ $role->id }})"
                                                        class="block w-full py-2.5 text-center text-sm font-medium text-red-600 dark:text-red-400 border border-red-200 dark:border-red-800 rounded-lg hover:bg-red-50 dark:hover:bg-gray-700">
                                                    確認刪除
                                                </button>
                                                <button wire:click="cancelDelete" @click="open = false"
                                                        class="block w-full py-2.5 text-center text-sm font-medium text-gray-500 dark:text-gray-400 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                                                    取消刪除
                                                </button>
                                            @else
                                                <button wire:click="confirmDelete({{ $role->id }})"
                                                        class="block w-full py-2.5 text-center text-sm font-medium text-red-500 dark:text-red-400 border border-red-200 dark:border-red-800 rounded-lg hover:bg-red-50 dark:hover:bg-gray-700">
                                                    刪除角色
                                                </button>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400 dark:text-gray-500">尚無角色資料</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>
</div>
