<div class="max-w-2xl">
    <div class="bg-white rounded-lg shadow p-6">
        <form wire:submit="save" class="space-y-5">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">姓名 <span class="text-red-500">*</span></label>
                <input wire:model="name" type="text" class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('name') border-red-400 @enderror">
                @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                <input wire:model="email" type="email" class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('email') border-red-400 @enderror">
                @error('email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">密碼 <span class="text-red-500">*</span></label>
                <input wire:model="password" type="password" class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('password') border-red-400 @enderror">
                @error('password') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">確認密碼 <span class="text-red-500">*</span></label>
                <input wire:model="password_confirmation" type="password" class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">狀態</label>
                <select wire:model="status" class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="pending">待審核</option>
                    <option value="active">啟用</option>
                    <option value="inactive">停用</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">角色 <span class="text-red-500">*</span></label>
                <div class="space-y-2 border border-gray-200 rounded-lg p-4">
                    @foreach ($allRoles as $role)
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox"
                                   wire:model="selectedRoles"
                                   value="{{ $role->id }}"
                                   class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="text-sm text-gray-700">{{ $role->name }}</span>
                            @if ($role->description)
                                <span class="text-xs text-gray-400">— {{ $role->description }}</span>
                            @endif
                        </label>
                    @endforeach
                </div>
                @error('selectedRoles') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                        class="px-6 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">
                    建立帳號
                </button>
                <a href="{{ route('admin.users.index') }}"
                   class="px-6 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200">
                    取消
                </a>
            </div>

        </form>
    </div>
</div>
