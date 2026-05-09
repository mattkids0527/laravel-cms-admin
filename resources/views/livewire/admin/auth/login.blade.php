<div class="w-full max-w-md">
    <div class="bg-white rounded-2xl shadow-lg px-8 py-10">
        <h1 class="text-2xl font-bold text-gray-800 mb-1">後台登入</h1>
        <p class="text-sm text-gray-400 mb-8">{{ config('app.name') }}</p>

        <form wire:submit="login" class="space-y-5">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input wire:model="email"
                       type="email"
                       autofocus
                       class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('email') border-red-400 @enderror">
                @error('email')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">密碼</label>
                <input wire:model="password"
                       type="password"
                       class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('password') border-red-400 @enderror">
                @error('password')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                    class="w-full py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700 transition">
                <span wire:loading.remove wire:target="login">登入</span>
                <span wire:loading wire:target="login">登入中...</span>
            </button>

        </form>
    </div>
</div>
