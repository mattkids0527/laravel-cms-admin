# blade-ui-components.md

## 這份文件做什麼

- 定義 `resources/views/components/` 下 Blade UI 元件的層次、命名規範與 API 規則
- 作為新增元件或修改現有元件時的一致性基準
- AI 在生成 Blade 元件或使用元件時必須遵守此文件規則

---

## 元件層次

```
resources/views/components/
├── ui/        ← 原子層：通用 UI 元件，不含後台業務邏輯
├── admin/     ← 複合層：後台管理專用元件，可組合 ui/ 元件
└── layouts/   ← 佈局層：頁面框架，不放業務 UI 細節
```

### 各層職責

| 層次 | 目錄 | 職責 | 可引用 |
|------|------|------|--------|
| 原子 | `ui/` | 按鈕、輸入框、徽章等通用元件 | 不引用其他元件 |
| 複合 | `admin/` | 資料表格、頁面標題、工具列等後台組合元件 | 可引用 `ui/` |
| 佈局 | `layouts/` | 整頁框架（sidebar、header、main） | 可引用 `ui/`、`admin/` |

---

## 現有元件清單

### `ui/` 原子元件

| 元件 | 使用語法 | 主要 Props |
|------|----------|-----------|
| `button.blade.php` | `<x-ui.button>` | `variant`, `href`, `size` |
| `badge.blade.php` | `<x-ui.badge>` | `color` |
| `alert.blade.php` | `<x-ui.alert>` | `type` |
| `card.blade.php` | `<x-ui.card>` | — |
| `form-input.blade.php` | `<x-ui.form-input>` | `label`, `name`, `type`, `required` |
| `form-checkbox.blade.php` | `<x-ui.form-checkbox>` | `label`, `name` |
| `form-select.blade.php` | `<x-ui.form-select>` | `label`, `name`, `options` |

### `admin/` 複合元件

| 元件 | 使用語法 | Slots |
|------|----------|-------|
| `data-table.blade.php` | `<x-admin.data-table>` | `head`, `body`, `footer`（可選） |
| `page-header.blade.php` | `<x-admin.page-header>` | 預設 slot（放按鈕）；prop: `title` |
| `toolbar.blade.php` | `<x-admin.toolbar>` | — |
| `mobile-row-action.blade.php` | `<x-admin.mobile-row-action>` | 預設 slot |

---

## 命名規範

- **檔名**：`kebab-case.blade.php`（例：`form-input.blade.php`）
- **使用語法**：`<x-{層次}.{元件名}>` 對應目錄結構
  - `ui/button.blade.php` → `<x-ui.button>`
  - `admin/data-table.blade.php` → `<x-admin.data-table>`
  - `layouts/admin.blade.php` → `<x-layouts.admin>`（由 Livewire class 宣告，不直接在 view 使用）

---

## 元件 API 設計規則

### 1. Props 宣告

所有 props 必須用 `@props([...])` 宣告，並提供預設值：

```blade
@props([
    'variant' => 'primary',
    'href'    => null,
    'size'    => 'md',
])
```

### 2. HTML 屬性透傳

使用 `$attributes->merge()` 允許呼叫端傳入額外的 HTML 屬性：

```blade
{{-- 正確 --}}
<button {{ $attributes->merge(['class' => $baseClass]) }}>

{{-- 錯誤 --}}
<button class="{{ $baseClass }}">  {{-- 會覆蓋呼叫端傳入的 class --}}
```

### 3. Variant 樣式用 Array Map

多個樣式變體用 PHP array 管理，不用 if/else 或 @switch：

```blade
@php
$variantMap = [
    'primary'   => 'bg-indigo-600 text-white hover:bg-indigo-700',
    'secondary' => 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600',
    'danger'    => 'bg-red-600 text-white hover:bg-red-700',
];
$variantClass = $variantMap[$variant] ?? $variantMap['primary'];
@endphp
```

### 4. 不在元件內寫 JavaScript

元件不嵌入 Alpine.js `x-data`、`x-on:click` 等指令。Alpine 互動由呼叫端負責：

```blade
{{-- 正確：呼叫端加 Alpine --}}
<x-ui.button x-on:click="open = !open">切換</x-ui.button>

{{-- 錯誤：元件內寫死 Alpine 邏輯 --}}
{{-- button.blade.php 內：<button x-on:click="open = !open"> --}}
```

---

## Dark Mode 配對規則

每個顏色類別都必須配對 `dark:` 對應，禁止只寫 light mode 顏色。

| 用途 | Light | Dark |
|------|-------|------|
| 卡片/面板背景 | `bg-white` | `dark:bg-gray-800` |
| 頁面底色 | `bg-gray-100` | `dark:bg-gray-900` |
| 次要背景（表頭） | `bg-gray-50` | `dark:bg-gray-700` |
| 主文字 | `text-gray-900` | `dark:text-gray-100` |
| 次文字 | `text-gray-700` | `dark:text-gray-300` |
| 輔助文字 | `text-gray-500` | `dark:text-gray-400` |
| 邊框（卡片） | `border-gray-200` | `dark:border-gray-700` |
| 邊框（輸入框） | `border-gray-300` | `dark:border-gray-600` |
| 分隔線 | `divide-gray-200` | `dark:divide-gray-700` |
| 輸入框背景 | `bg-white` | `dark:bg-gray-700` |
| 主色文字 | `text-indigo-600` | `dark:text-indigo-400` |
| 主色按鈕 | `bg-indigo-600` | — （按鈕本身不需 dark，用 hover 即可） |
| 危險色 | `text-red-500` / `text-red-600` | `dark:text-red-400` |

---

## Slot 使用規則

### 單一內容：預設 slot

```blade
{{-- card.blade.php --}}
<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
    {{ $slot }}
</div>
```

### 多個區塊：具名 slot

```blade
{{-- data-table.blade.php --}}
<table>
    <thead><tr>{{ $head }}</tr></thead>
    <tbody>{{ $body }}</tbody>
</table>
```

呼叫方用 `<x-slot:name>` 語法：

```blade
<x-admin.data-table>
    <x-slot:head>
        <th>名稱</th>
    </x-slot:head>
    <x-slot:body>
        <tr>...</tr>
    </x-slot:body>
</x-admin.data-table>
```

### 可選 slot：用 `@isset` 判斷

```blade
@isset($footer)
    <div class="border-t border-gray-200 dark:border-gray-700 px-6 py-4">
        {{ $footer }}
    </div>
@endisset
```

---

## 新增元件的時機

| 情況 | 動作 |
|------|------|
| 同樣 HTML 結構在 3 個以上 view 重複出現 | 抽成 `ui/` 元件 |
| 後台管理頁的複合結構（表格+工具列+分頁）重複出現 | 確認 `admin/` 是否已有，若無則新增 |
| 只在單一模組內使用的特定 UI | 放在模組 `resources/views/livewire/` 內用 `@include` 引用，不進 `components/` |

---

## 禁止事項

| 禁止 | 原因 |
|------|------|
| `style="..."` inline style | 破壞 Tailwind 一致性，難以 dark mode 適配 |
| 只寫 light mode 顏色不加 `dark:` | 深色模式顯示異常 |
| 在 `ui/` 元件內使用 `wire:*` 屬性 | `ui/` 是通用元件，不應依賴 Livewire |
| 用 `@include('components.ui.button')` | 應改用 `<x-ui.button>` Blade 元件語法 |
| `$attributes->class(...)` | 應用 `$attributes->merge(['class' => ...])` |
| 在元件內寫死 Alpine.js 邏輯 | Alpine 互動由呼叫端控制 |
