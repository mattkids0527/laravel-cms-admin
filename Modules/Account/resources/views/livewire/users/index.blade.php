<div>
    <x-ui.alert type="success" :message="session('success')" />
    <x-ui.alert type="error" :message="session('error')" />

    <x-admin.toolbar placeholder="搜尋姓名或 Email..." wire:model.live.debounce.300ms="search">
        <x-slot:filters>
            <select wire:model.live="statusFilter"
                    class="rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="">全部狀態</option>
                <option value="pending">待審核</option>
                <option value="active">啟用</option>
                <option value="inactive">停用</option>
            </select>
        </x-slot:filters>
        <x-slot:actions>
            <x-ui.button variant="primary" href="{{ route('admin.users.create') }}">+ 新增帳號</x-ui.button>
        </x-slot:actions>
    </x-admin.toolbar>

    <x-admin.data-table>
        <x-slot:head>
            <th class="px-6 py-3 text-left font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">姓名 / Email</th>
            <th class="px-6 py-3 text-left font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">角色</th>
            <th class="px-6 py-3 text-left font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">狀態</th>
            <th class="px-6 py-3 text-left font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">最後登入</th>
            <th class="px-6 py-3 text-right font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider table-action-th">操作</th>
        </x-slot:head>
        <x-slot:body>
            @forelse ($users as $user)
                @php
                    $statusColor = match($user->status) {
                        'active'   => 'green',
                        'pending'  => 'yellow',
                        'inactive' => 'gray',
                        default    => 'gray',
                    };
                    $statusLabel = match($user->status) {
                        'active'   => '啟用',
                        'pending'  => '待審核',
                        'inactive' => '停用',
                        default    => $user->status,
                    };
                @endphp
                <tr x-data="{ open: false }"
                    @click="if(window.innerWidth < 768) open = true"
                    class="mobile-clickable-row hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-900 dark:text-gray-100">{{ $user->name }}</div>
                        <div class="text-gray-400 dark:text-gray-500">{{ $user->email }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-wrap gap-1">
                            @foreach ($user->roles as $role)
                                <x-ui.badge color="indigo" shape="square">{{ $role->name }}</x-ui.badge>
                            @endforeach
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <x-ui.badge :color="$statusColor">{{ $statusLabel }}</x-ui.badge>
                    </td>
                    <td class="px-6 py-4 text-gray-500 dark:text-gray-400">
                        {{ $user->last_login_at?->format('Y-m-d H:i') ?? '-' }}
                    </td>
                    <td class="px-6 py-4 text-right relative table-action-td">

                        {{-- 桌機：行內操作 --}}
                        <div class="table-desktop-actions space-x-2">
                            <x-ui.button variant="link" href="{{ route('admin.users.edit', $user) }}">編輯</x-ui.button>
                            <x-ui.button variant="link-gray" wire:click="toggleStatus({{ $user->id }})">
                                {{ $user->isActive() ? '停用' : '啟用' }}
                            </x-ui.button>
                            @if ($confirmingDeleteId === $user->id)
                                <span class="text-red-600 dark:text-red-400 font-medium">確認刪除？</span>
                                <x-ui.button variant="link-danger" wire:click="delete({{ $user->id }})">是</x-ui.button>
                                <x-ui.button variant="link-gray" wire:click="cancelDelete">否</x-ui.button>
                            @else
                                <x-ui.button variant="link-danger" wire:click="confirmDelete({{ $user->id }})">刪除</x-ui.button>
                            @endif
                        </div>

                        {{-- 手機：點列底部彈出操作表 --}}
                        <x-admin.mobile-row-action :title="$user->name" :subtitle="$user->email">
                            <x-ui.button variant="sheet-primary" href="{{ route('admin.users.edit', $user) }}">編輯</x-ui.button>
                            <x-ui.button variant="sheet-default" wire:click="toggleStatus({{ $user->id }})" @click="open = false">
                                {{ $user->isActive() ? '停用帳號' : '啟用帳號' }}
                            </x-ui.button>
                            @if ($confirmingDeleteId === $user->id)
                                <x-ui.button variant="sheet-danger" wire:click="delete({{ $user->id }})">確認刪除</x-ui.button>
                                <x-ui.button variant="sheet-default" wire:click="cancelDelete" @click="open = false">取消刪除</x-ui.button>
                            @else
                                <x-ui.button variant="sheet-danger" wire:click="confirmDelete({{ $user->id }})">刪除帳號</x-ui.button>
                            @endif
                        </x-admin.mobile-row-action>

                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-400 dark:text-gray-500">查無帳號資料</td>
                </tr>
            @endforelse
        </x-slot:body>
        <x-slot:footer>
            {{ $users->links() }}
        </x-slot:footer>
    </x-admin.data-table>
</div>
