<div class="max-w-lg">
    <x-ui.card>
        <form wire:submit="save" class="space-y-5">

            <x-ui.form-input label="角色名稱" name="name" wire:model="name" required />

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    描述 <span class="text-gray-400 dark:text-gray-500 font-normal text-xs">（選填）</span>
                </label>
                <x-ui.form-input name="description" wire:model="description" />
            </div>

            <div class="flex items-center gap-3 pt-2">
                <x-ui.button variant="primary" size="lg" type="submit">建立角色</x-ui.button>
                <x-ui.button variant="secondary" size="lg" href="{{ route('admin.roles.index') }}">取消</x-ui.button>
            </div>

        </form>
    </x-ui.card>
</div>
