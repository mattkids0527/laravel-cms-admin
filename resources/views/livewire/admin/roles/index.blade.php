<div>
    <x-ui.alert type="success" :message="session('success')" />
    <x-ui.alert type="error" :message="session('error')" />

    <div class="flex justify-end mb-6">
        <x-ui.button variant="primary" href="{{ route('admin.roles.create') }}">+ 新增角色</x-ui.button>
    </div>

    <x-admin.data-table>
        <x-slot:head>
            <th class="px-6 py-3 text-left font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">角色名稱</th>
            <th class="px-6 py-3 text-left font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">描述</th>
            <th class="px-6 py-3 text-left font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">帳號數</th>
            <th class="px-6 py-3 text-left font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">類型</th>
            <th class="px-6 py-3 text-right font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider table-action-th">操作</th>
        </x-slot:head>
        <x-slot:body>
            @forelse ($roles as $role)
                <tr x-data="{ open: false }"
                    @click="if(window.innerWidth < 768) open = true"
                    class="mobile-clickable-row hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-gray-100">{{ $role->name }}</td>
                    <td class="px-6 py-4 text-gray-500 dark:text-gray-400">{{ $role->description ?? '-' }}</td>
                    <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $role->users_count }}</td>
                    <td class="px-6 py-4">
                        @if ($role->is_protected)
                            <x-ui.badge color="red">系統保護</x-ui.badge>
                        @else
                            <x-ui.badge color="gray">一般</x-ui.badge>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right relative table-action-td">

                        {{-- 桌機：行內操作 --}}
                        <div class="table-desktop-actions space-x-2">
                            <x-ui.button variant="link" href="{{ route('admin.roles.edit', $role) }}">編輯</x-ui.button>
                            @if (! $role->is_protected)
                                @if ($confirmingDeleteId === $role->id)
                                    <span class="text-red-600 dark:text-red-400 font-medium">確認刪除？</span>
                                    <x-ui.button variant="link-danger" wire:click="delete({{ $role->id }})">是</x-ui.button>
                                    <x-ui.button variant="link-gray" wire:click="cancelDelete">否</x-ui.button>
                                @else
                                    <x-ui.button variant="link-danger" wire:click="confirmDelete({{ $role->id }})">刪除</x-ui.button>
                                @endif
                            @endif
                        </div>

                        {{-- 手機：點列底部彈出操作表 --}}
                        <x-admin.mobile-row-action :title="$role->name" :subtitle="$role->description">
                            <x-ui.button variant="sheet-primary" href="{{ route('admin.roles.edit', $role) }}">編輯</x-ui.button>
                            @if (! $role->is_protected)
                                @if ($confirmingDeleteId === $role->id)
                                    <x-ui.button variant="sheet-danger" wire:click="delete({{ $role->id }})">確認刪除</x-ui.button>
                                    <x-ui.button variant="sheet-default" wire:click="cancelDelete" @click="open = false">取消刪除</x-ui.button>
                                @else
                                    <x-ui.button variant="sheet-danger" wire:click="confirmDelete({{ $role->id }})">刪除角色</x-ui.button>
                                @endif
                            @endif
                        </x-admin.mobile-row-action>

                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-400 dark:text-gray-500">尚無角色資料</td>
                </tr>
            @endforelse
        </x-slot:body>
    </x-admin.data-table>
</div>
