@push('styles')
<style>
    .perm-layout { flex-direction: row; }
    .perm-aside  { width: 14rem; flex-shrink: 0; }
    @media (max-width: 767px) {
        .perm-layout { flex-direction: column; }
        .perm-aside  { width: 100%; }
    }
</style>
@endpush

<div class="flex perm-layout gap-6">

    {{-- 左：角色列表 --}}
    <div class="perm-aside">
        <x-ui.card :padding="false">
            <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600 text-sm font-medium text-gray-600 dark:text-gray-300">選擇角色</div>
            <ul class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach ($roles as $role)
                    <li>
                        <button wire:click="selectRole({{ $role->id }})"
                                class="w-full text-left px-4 py-3 text-sm transition
                                       {{ $selectedRoleId === $role->id
                                            ? 'bg-indigo-50 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 font-medium'
                                            : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                            {{ $role->name }}
                            @if ($role->is_protected)
                                <span class="ml-1 text-xs text-red-400 dark:text-red-400">（超管）</span>
                            @endif
                        </button>
                    </li>
                @endforeach
            </ul>
        </x-ui.card>
    </div>

    {{-- 右：選單權限勾選 --}}
    <div class="flex-1">
        <x-ui.alert type="success" :message="session('success')" />

        @if (! $selectedRoleId)
            <x-ui.card>
                <div class="py-10 text-center text-gray-400 dark:text-gray-500 text-sm">
                    請從左側選擇一個角色以設定選單權限
                </div>
            </x-ui.card>
        @else
            @php $selectedRole = $roles->firstWhere('id', $selectedRoleId); @endphp

            <x-ui.card :padding="false">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <div>
                        <h2 class="font-semibold text-gray-800 dark:text-gray-100">{{ $selectedRole?->name }}</h2>
                        @if ($selectedRole?->is_protected)
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">超級管理員自動擁有所有權限，此設定僅供參考</p>
                        @endif
                    </div>
                    <x-ui.button variant="primary" wire:click="save">
                        <span wire:loading.remove wire:target="save">儲存權限</span>
                        <span wire:loading wire:target="save">儲存中...</span>
                    </x-ui.button>
                </div>

                <div class="p-6 space-y-6">
                    @foreach ($groups as $group)
                        <div>
                            @if ($group->isGroup())
                                <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-3">
                                    {{ $group->name }}
                                </p>
                                <div class="space-y-2 pl-2">
                                    @foreach ($group->children->where('is_active', true) as $item)
                                        <x-ui.form-checkbox wire:model="selectedMenuIds" :value="$item->id" :label="$item->name">
                                            <span class="text-xs text-gray-400 dark:text-gray-500">{{ $item->route_name }}</span>
                                        </x-ui.form-checkbox>
                                    @endforeach
                                </div>
                            @else
                                <x-ui.form-checkbox wire:model="selectedMenuIds" :value="$group->id" :label="$group->name">
                                    <span class="text-xs text-gray-400 dark:text-gray-500">{{ $group->route_name }}</span>
                                </x-ui.form-checkbox>
                            @endif
                        </div>
                    @endforeach
                </div>
            </x-ui.card>
        @endif
    </div>

</div>
