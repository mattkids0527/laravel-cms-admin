<div class="max-w-lg">
    <x-ui.card>

        {{-- 系統保護角色警告（amber 邊框樣式，不使用 x-ui.alert 的無邊框設計）--}}
        @if ($role->is_protected)
            <div class="mb-5 px-4 py-3 bg-amber-50 dark:bg-amber-900 border border-amber-200 dark:border-amber-700 text-amber-800 dark:text-amber-300 rounded-lg text-sm">
                此為系統保護角色，名稱不可修改，僅可更新描述。
            </div>
        @endif

        <form wire:submit="save" class="space-y-5">

            {{-- 受保護角色名稱唯讀，保留 raw HTML --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    角色名稱 <span class="text-red-500">*</span>
                </label>
                @if ($role->is_protected)
                    <input type="text" value="{{ $role->name }}" disabled
                           class="w-full rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 px-4 py-2 text-sm text-gray-500 dark:text-gray-500 cursor-not-allowed">
                @else
                    <x-ui.form-input name="name" wire:model="name" />
                @endif
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    描述 <span class="text-gray-400 dark:text-gray-500 font-normal text-xs">（選填）</span>
                </label>
                <x-ui.form-input name="description" wire:model="description" />
            </div>

            <div class="flex items-center gap-3 pt-2">
                <x-ui.button variant="primary" size="lg" type="submit">儲存變更</x-ui.button>
                <x-ui.button variant="secondary" size="lg" href="{{ route('admin.roles.index') }}">取消</x-ui.button>
            </div>

        </form>
    </x-ui.card>
</div>
