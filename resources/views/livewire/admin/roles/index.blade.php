<div>
    @if (session('success'))
        <div class="mb-4 px-4 py-3 bg-green-100 text-green-800 rounded-lg text-sm">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="mb-4 px-4 py-3 bg-red-100 text-red-800 rounded-lg text-sm">{{ session('error') }}</div>
    @endif

    <div class="flex justify-end mb-6">
        <a href="{{ route('admin.roles.create') }}"
           class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">
            + 新增角色
        </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">角色名稱</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">描述</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">帳號數</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">類型</th>
                    <th class="px-6 py-3 text-right font-medium text-gray-500 uppercase tracking-wider">操作</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($roles as $role)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $role->name }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $role->description ?? '-' }}</td>
                        <td class="px-6 py-4 text-gray-700">{{ $role->users_count }}</td>
                        <td class="px-6 py-4">
                            @if ($role->is_protected)
                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">系統保護</span>
                            @else
                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500">一般</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('admin.roles.edit', $role) }}"
                               class="text-indigo-600 hover:text-indigo-900 font-medium">編輯</a>

                            @if (! $role->is_protected)
                                @if ($confirmingDeleteId === $role->id)
                                    <span class="text-red-600 font-medium">確認刪除？</span>
                                    <button wire:click="delete({{ $role->id }})" class="text-red-600 hover:text-red-900 font-medium">是</button>
                                    <button wire:click="cancelDelete" class="text-gray-500 hover:text-gray-800 font-medium">否</button>
                                @else
                                    <button wire:click="confirmDelete({{ $role->id }})"
                                            class="text-red-500 hover:text-red-700 font-medium">刪除</button>
                                @endif
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400">尚無角色資料</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
