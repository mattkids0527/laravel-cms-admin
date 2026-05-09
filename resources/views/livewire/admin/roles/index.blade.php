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
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">角色名稱</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">描述</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">帳號數</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">類型</th>
                    <th class="px-6 py-3 text-right font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">操作</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($roles as $role)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
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
                        <td class="px-6 py-4 text-right space-x-2">
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
