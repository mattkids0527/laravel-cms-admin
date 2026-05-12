<div class="max-w-2xl">
    <x-ui.card>
        <form wire:submit="save" class="space-y-5">

            <x-ui.form-input label="姓名" name="name" wire:model="name" required />
            <x-ui.form-input label="Email" name="email" type="email" wire:model="email" required />

            {{-- 密碼欄位含提示文字，手動組 label --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    新密碼 <span class="text-gray-400 dark:text-gray-500 font-normal text-xs">（留空則不變更）</span>
                </label>
                <x-ui.form-input name="password" type="password" wire:model="password" />
            </div>

            <x-ui.form-input label="確認新密碼" name="password_confirmation" type="password" wire:model="password_confirmation" />

            <x-ui.form-select label="狀態" name="status" wire:model="status">
                <option value="pending">待審核</option>
                <option value="active">啟用</option>
                <option value="inactive">停用</option>
            </x-ui.form-select>

            {{-- 角色：自己不可修改自己，保留 raw HTML 處理 disabled 樣式 --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    角色 <span class="text-red-500">*</span>
                    @if ($user->id === auth()->id())
                        <span class="text-xs text-gray-400 dark:text-gray-500 font-normal ml-1">（不可修改自己的角色）</span>
                    @endif
                </label>
                <div class="space-y-2 border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                    @foreach ($allRoles as $role)
                        <label class="flex items-center gap-3 {{ $user->id === auth()->id() ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer' }}">
                            <input type="checkbox"
                                   wire:model="selectedRoles"
                                   value="{{ $role->id }}"
                                   {{ $user->id === auth()->id() ? 'disabled' : '' }}
                                   class="rounded border-gray-300 dark:border-gray-500 text-indigo-600 focus:ring-indigo-500">
                            <span class="text-sm text-gray-700 dark:text-gray-300">{{ $role->name }}</span>
                            @if ($role->description)
                                <span class="text-xs text-gray-400 dark:text-gray-500">— {{ $role->description }}</span>
                            @endif
                        </label>
                    @endforeach
                </div>
                @error('selectedRoles') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-3 pt-2">
                <x-ui.button variant="primary" size="lg" type="submit">儲存變更</x-ui.button>
                <x-ui.button variant="secondary" size="lg" href="{{ route('admin.users.index') }}">取消</x-ui.button>
            </div>

        </form>
    </x-ui.card>
</div>
