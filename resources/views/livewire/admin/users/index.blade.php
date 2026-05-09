<div>
    {{-- Flash 訊息 --}}
    @if (session('success'))
        <div class="mb-4 px-4 py-3 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="mb-4 px-4 py-3 bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-300 rounded-lg text-sm">
            {{ session('error') }}
        </div>
    @endif

    {{-- 工具列 --}}
    <div class="flex flex-col sm:flex-row gap-3 mb-6">
        <input wire:model.live.debounce.300ms="search"
               type="text"
               placeholder="搜尋姓名或 Email..."
               class="flex-1 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">

        <select wire:model.live="statusFilter"
                class="rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option value="">全部狀態</option>
            <option value="pending">待審核</option>
            <option value="active">啟用</option>
            <option value="inactive">停用</option>
        </select>

        <a href="{{ route('admin.users.create') }}"
           class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">
            + 新增帳號
        </a>
    </div>

    {{-- 資料表 --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">姓名 / Email</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">角色</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">狀態</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">最後登入</th>
                    <th class="px-6 py-3 text-right font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">操作</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($users as $user)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900 dark:text-gray-100">{{ $user->name }}</div>
                            <div class="text-gray-400 dark:text-gray-500">{{ $user->email }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1">
                                @foreach ($user->roles as $role)
                                    <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300">
                                        {{ $role->name }}
                                    </span>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusMap = [
                                    'pending'  => ['label' => '待審核', 'class' => 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-300'],
                                    'active'   => ['label' => '啟用',   'class' => 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300'],
                                    'inactive' => ['label' => '停用',   'class' => 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400'],
                                ];
                                $s = $statusMap[$user->status] ?? ['label' => $user->status, 'class' => 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400'];
                            @endphp
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $s['class'] }}">
                                {{ $s['label'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-500 dark:text-gray-400">
                            {{ $user->last_login_at?->format('Y-m-d H:i') ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('admin.users.edit', $user) }}"
                               class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 font-medium">編輯</a>

                            <button wire:click="toggleStatus({{ $user->id }})"
                                    class="text-gray-500 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 font-medium">
                                {{ $user->isActive() ? '停用' : '啟用' }}
                            </button>

                            @if ($confirmingDeleteId === $user->id)
                                <span class="text-red-600 dark:text-red-400 font-medium">確認刪除？</span>
                                <button wire:click="delete({{ $user->id }})" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 font-medium">是</button>
                                <button wire:click="cancelDelete" class="text-gray-500 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 font-medium">否</button>
                            @else
                                <button wire:click="confirmDelete({{ $user->id }})"
                                        class="text-red-500 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 font-medium">刪除</button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400 dark:text-gray-500">查無帳號資料</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $users->links() }}
        </div>
    </div>
</div>
