<div class="max-w-lg">
    <div class="bg-white rounded-lg shadow p-6">
        <form wire:submit="save" class="space-y-5">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">角色名稱 <span class="text-red-500">*</span></label>
                <input wire:model="name" type="text"
                       class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('name') border-red-400 @enderror">
                @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">描述 <span class="text-gray-400 font-normal text-xs">（選填）</span></label>
                <input wire:model="description" type="text"
                       class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                        class="px-6 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">
                    建立角色
                </button>
                <a href="{{ route('admin.roles.index') }}"
                   class="px-6 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200">
                    取消
                </a>
            </div>

        </form>
    </div>
</div>
