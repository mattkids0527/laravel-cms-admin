<div class="max-w-2xl">
    <x-ui.card>
        <form wire:submit="save" class="space-y-5">

            <x-ui.form-input label="姓名" name="name" wire:model="name" required />
            <x-ui.form-input label="Email" name="email" type="email" wire:model="email" required />
            <x-ui.form-input label="密碼" name="password" type="password" wire:model="password" required />
            <x-ui.form-input label="確認密碼" name="password_confirmation" type="password" wire:model="password_confirmation" />

            <x-ui.form-select label="狀態" name="status" wire:model="status">
                <option value="pending">待審核</option>
                <option value="active">啟用</option>
                <option value="inactive">停用</option>
            </x-ui.form-select>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    角色 <span class="text-red-500">*</span>
                </label>
                <div class="space-y-2 border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                    @foreach ($allRoles as $role)
                        <x-ui.form-checkbox wire:model="selectedRoles" :value="$role->id" :label="$role->name">
                            @if ($role->description)
                                <span class="text-xs text-gray-400 dark:text-gray-500">— {{ $role->description }}</span>
                            @endif
                        </x-ui.form-checkbox>
                    @endforeach
                </div>
                @error('selectedRoles') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-3 pt-2">
                <x-ui.button variant="primary" size="lg" type="submit">建立帳號</x-ui.button>
                <x-ui.button variant="secondary" size="lg" href="{{ route('admin.users.index') }}">取消</x-ui.button>
            </div>

        </form>
    </x-ui.card>
</div>
