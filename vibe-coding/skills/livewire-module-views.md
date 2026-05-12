# livewire-module-views.md

## 這份文件做什麼

- 定義 Laravel Modules 架構下，各模組 Livewire views 的放置規則與頁面結構規範
- 說明如何在模組 views 中引用共用 Blade 元件
- 規範 Livewire 屬性綁定、佈局宣告與跨模組 view 隔離邊界

> 後端模組架構規範請參考 `laravel-modules.md`
> UI 元件 API 規則請參考 `blade-ui-components.md`

---

## View 文件放置規則

```
Modules/<ModuleName>/
└── resources/
    └── views/
        └── livewire/
            └── <feature>/       ← 以功能分組（users, roles, tags...）
                ├── index.blade.php
                ├── create.blade.php
                └── edit.blade.php
```

### 共用 vs 模組專屬

| View 類型 | 放置位置 | 引用方式 |
|-----------|----------|----------|
| 通用 UI 元件（button, card, form-input...） | `resources/views/components/ui/` | `<x-ui.*>` |
| 後台複合元件（data-table, page-header...） | `resources/views/components/admin/` | `<x-admin.*>` |
| 模組內可複用的 partial | 模組 `resources/views/livewire/` 內 | `@include('<module>::livewire.partials.xxx')` |
| 只用一次的模組特定 sub-view | 同上，無需抽出 | 直接寫在頁面 view 內 |

**規則：不把模組專屬的 UI 放進 `resources/views/components/`。只有跨模組使用 3 次以上，才提升到共用元件。**

---

## 標準頁面結構

### Index 頁（列表）

```blade
<div>
    <x-admin.page-header title="使用者管理">
        <x-ui.button href="{{ route('admin.users.create') }}">
            新增使用者
        </x-ui.button>
    </x-admin.page-header>

    <x-admin.toolbar
        wire:model.live="search"
        :filters="[]"
    />

    <x-admin.data-table>
        <x-slot:head>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">名稱</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Email</th>
            <th class="relative px-6 py-3"><span class="sr-only">操作</span></th>
        </x-slot:head>
        <x-slot:body>
            @foreach ($users as $user)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $user->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                        <x-ui.button variant="link" href="{{ route('admin.users.edit', $user) }}">編輯</x-ui.button>
                        <x-ui.button variant="link-danger"
                            wire:confirm="確定刪除此使用者？"
                            wire:click="delete({{ $user->id }})">刪除</x-ui.button>
                    </td>
                </tr>
            @endforeach
        </x-slot:body>
        <x-slot:footer>
            {{ $users->links() }}
        </x-slot:footer>
    </x-admin.data-table>
</div>
```

### Create / Edit 頁（表單）

```blade
<div>
    <x-admin.page-header title="新增使用者">
        <x-ui.button variant="secondary" href="{{ route('admin.users.index') }}">
            返回列表
        </x-ui.button>
    </x-admin.page-header>

    <x-ui.card>
        <form wire:submit="save" class="space-y-4">
            <x-ui.form-input
                label="姓名"
                name="name"
                wire:model="name"
                required
            />
            <x-ui.form-input
                label="Email"
                name="email"
                type="email"
                wire:model="email"
                required
            />
            <x-ui.form-select
                label="角色"
                name="role_id"
                wire:model="roleId"
                :options="$roles"
            />

            <div class="flex justify-end gap-3 pt-2">
                <x-ui.button variant="secondary" href="{{ route('admin.users.index') }}">取消</x-ui.button>
                <x-ui.button type="submit" wire:loading.attr="disabled">儲存</x-ui.button>
            </div>
        </form>
    </x-ui.card>
</div>
```

---

## 引用共用元件的方式

一律使用 Blade component 語法，禁止舊式 `@include`：

```blade
{{-- 正確 --}}
<x-ui.button variant="primary">儲存</x-ui.button>
<x-admin.data-table>...</x-admin.data-table>

{{-- 禁止 --}}
@include('components.ui.button')
```

---

## Livewire 屬性綁定規則

| 使用情境 | 語法 |
|---------|------|
| 表單欄位雙向綁定 | `wire:model="propertyName"` |
| 即時搜尋（輸入即觸發） | `wire:model.live="search"` |
| 按鈕觸發 action | `wire:click="methodName"` |
| 刪除確認對話框 | `wire:confirm="確定刪除？" wire:click="delete({{ $id }})"` |
| Submit 時 disable 按鈕 | `wire:loading.attr="disabled"` |
| Submit 時顯示 loading 文字 | `<span wire:loading>處理中...</span>` |
| 傳遞 ID 給 action | `wire:click="delete({{ $item->id }})"` |

---

## Livewire 佈局宣告

在 Livewire component class 用 `#[Layout]` attribute 或 `->layout()` 指定：

```php
// PHP 8 attribute 寫法（推薦）
use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin')]
class UserIndex extends Component { ... }

// 或在 render() 內
public function render()
{
    return view('account::livewire.users.index')
        ->layout('components.layouts.admin');
}
```

| 頁面類型 | 佈局 |
|---------|------|
| 後台管理頁（需認證） | `components.layouts.admin` |
| 登入、忘記密碼等 guest 頁 | `components.layouts.guest` |

---

## 模組 View Namespace

ServiceProvider 的 `loadViewsFrom()` 註冊 namespace 後，模組內引用自己的 view：

```blade
{{-- Account 模組引用自己的 sub-view --}}
@include('account::livewire.users.partials.row')

{{-- Livewire 元件 render() 指定 view --}}
return view('account::livewire.users.index');
```

**跨模組禁止直接引用對方的 view namespace。**
跨模組的 UI 共享透過提升到 `resources/views/components/` 處理。

---

## 表格欄位響應式

手機隱藏次要欄位，保留主要識別欄：

```blade
{{-- 主要欄（手機可見） --}}
<th class="px-6 py-3 ...">名稱</th>

{{-- 次要欄（手機隱藏） --}}
<th class="hidden md:table-cell px-6 py-3 ...">建立時間</th>

{{-- 對應的 td 也要加 hidden md:table-cell --}}
<td class="hidden md:table-cell px-6 py-4 ...">{{ $user->created_at }}</td>
```

手機操作改用 `<x-admin.mobile-row-action>`：

```blade
{{-- 手機：bottom-sheet 操作按鈕 --}}
<td class="md:hidden px-4 py-3 text-right">
    <x-admin.mobile-row-action>
        <x-ui.button variant="sheet-primary" href="{{ route('admin.users.edit', $user) }}">編輯</x-ui.button>
        <x-ui.button variant="sheet-danger" wire:confirm="確定刪除？" wire:click="delete({{ $user->id }})">刪除</x-ui.button>
    </x-admin.mobile-row-action>
</td>

{{-- 桌機：行內連結 --}}
<td class="hidden md:table-cell px-6 py-4 text-right">
    <x-ui.button variant="link" href="...">編輯</x-ui.button>
    <x-ui.button variant="link-danger" wire:click="delete({{ $user->id }})">刪除</x-ui.button>
</td>
```

---

## 禁止事項

| 禁止 | 原因 |
|------|------|
| 模組 A 的 view 用 `module-b::livewire.*` | 破壞模組邊界隔離 |
| `@include('components.ui.*')` 舊語法 | 應改用 `<x-ui.*>` |
| 把模組專屬 view 放進 `resources/views/components/` | 元件層應只放跨模組共用元件 |
| 在 view 直接寫 PHP 查詢（`App\Models\User::all()`） | 資料由 Livewire component 提供，view 只渲染 |
