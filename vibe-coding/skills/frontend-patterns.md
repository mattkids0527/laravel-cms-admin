# frontend-patterns.md

## 這份文件做什麼

- 定義此專案前端開發的 Tailwind CSS 書寫規範、色彩系統、Alpine.js 使用邊界
- 說明響應式設計模式與 Livewire + Alpine 整合方式
- AI 在生成或修改 Blade view 時應遵守此文件的樣式與互動規範

> 元件 API 規則請參考 `blade-ui-components.md`
> 模組 view 結構請參考 `livewire-module-views.md`

---

## 技術棧

| 技術 | 版本 | 職責 |
|------|------|------|
| Livewire | 3.x | 後端驅動的互動元件（表單、列表、搜尋） |
| Tailwind CSS | 3.x | Utility-first 樣式 |
| Alpine.js | 3.x | 純 UI 狀態管理（展開/隱藏/tooltip） |
| Vite | 5.x | 前端打包（單入口：`resources/css/app.css` + `resources/js/app.js`） |

---

## 色彩系統

此專案使用固定色彩語彙，新增樣式必須從此表取用，不使用其他顏色。

### 語意色彩

| 語意 | Light Mode | Dark Mode |
|------|-----------|-----------|
| 主色（Primary） | `indigo-600` | `indigo-400` |
| 成功（Success） | `green-600` | `green-400` |
| 危險（Danger） | `red-600` / `red-500` | `red-400` |
| 警告（Warning） | `yellow-500` | `yellow-400` |

### 中性色（灰階）

| 用途 | Light | Dark |
|------|-------|------|
| 頁面背景 | `gray-100` | `gray-900` |
| 卡片/面板背景 | `white` | `gray-800` |
| 次要背景（表頭、hover 底色） | `gray-50` | `gray-700` |
| 主文字 | `gray-900` | `gray-100` |
| 次文字（標籤、說明） | `gray-700` | `gray-300` |
| 輔助文字（placeholder、時間戳） | `gray-500` | `gray-400` |
| 邊框（卡片、容器） | `gray-200` | `gray-700` |
| 邊框（輸入框） | `gray-300` | `gray-600` |
| 分隔線（表格行、hr） | `gray-200` / `divide-gray-200` | `gray-700` / `divide-gray-700` |
| 輸入框背景 | `white` | `gray-700` |

---

## Tailwind Class 書寫順序

在同一個元素上，依以下順序排列 Tailwind classes（可讀性一致性）：

```
[佈局/定位] → [間距] → [尺寸] → [背景/邊框/圓角] → [文字] → [互動/狀態] → [響應式前綴] → [dark: 配對]
```

範例：

```blade
{{-- 好的寫法 --}}
<div class="flex items-center gap-3 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">

{{-- 避免隨機排列 --}}
<div class="text-sm hover:bg-gray-50 dark:border-gray-700 px-4 dark:text-gray-300 bg-white flex gap-3 rounded-lg border-gray-200 border items-center dark:bg-gray-800 py-2 text-gray-700">
```

---

## Alpine.js 使用邊界

### 何時用 Alpine

- **純 UI 狀態**：展開/收合、顯示/隱藏、tooltip、modal 開關
- **不需要後端的即時互動**：側邊欄收合、手機 drawer、主題切換

### 何時用 Livewire

- **需要後端資料**：表單提交、列表搜尋、分頁、驗證
- **有副作用的操作**：新增、編輯、刪除

### 禁止用 Alpine 做的事

- 發送 HTTP 請求（用 Livewire action 代替）
- 管理業務資料（用 Livewire property 代替）
- 全域狀態管理（用 Livewire 或 localStorage 代替）

---

## Alpine.js 常用模式

### 展開/收合

```blade
<div x-data="{ open: false }">
    <button x-on:click="open = !open">切換</button>
    <div x-show="open" x-transition>
        <!-- 內容 -->
    </div>
</div>
```

### 狀態持久化至 localStorage

```blade
<div x-data="{
    open: JSON.parse(localStorage.getItem('sidebar') ?? 'true')
}" x-on:click="open = !open; localStorage.setItem('sidebar', JSON.stringify(open))">
```

### 初始化時從 localStorage 恢復（避免 FOUC）

在 `<head>` 用 inline script 提前讀取（已在 `layouts/admin.blade.php` 處理）：

```html
<script>
    if (localStorage.getItem('theme') === 'dark' ||
        (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
    }
</script>
```

---

## Livewire + Alpine 整合

### 在 Alpine 中呼叫 Livewire action

```blade
<div x-data>
    <button x-on:click="$wire.save()">儲存</button>
    <button x-on:click="$wire.delete({{ $id }})">刪除</button>
</div>
```

### 同步 Livewire property 到 Alpine（@entangle）

```blade
<div x-data="{ open: $wire.entangle('isOpen') }">
    <div x-show="open">...</div>
</div>
```

### 避免衝突

- 同一個 `<input>` 不要同時用 `wire:model` 和 `x-model`
- 若需要 Alpine 讀取 Livewire 的值，用 `$wire.property` 而非 `x-model`

---

## 響應式設計規範

### 原則

- **Mobile-first**：先寫手機樣式，再用 breakpoint prefix 覆寫桌機
- Breakpoints：`sm:` (640px) / `md:` (768px) / `lg:` (1024px) / `xl:` (1280px)

### 表格欄位隱藏策略

```blade
{{-- 手機隱藏，中等以上顯示 --}}
<th class="hidden md:table-cell ...">建立時間</th>
<td class="hidden md:table-cell ...">{{ $item->created_at }}</td>

{{-- 始終顯示（主要識別欄） --}}
<th class="px-6 py-3 ...">名稱</th>
```

### 手機 vs 桌機操作按鈕

- 手機：用 `<x-admin.mobile-row-action>`（bottom-sheet 展開）
- 桌機：用行內 `<x-ui.button variant="link">` 連結

```blade
{{-- 手機操作 --}}
<td class="md:hidden ...">
    <x-admin.mobile-row-action>...</x-admin.mobile-row-action>
</td>

{{-- 桌機操作 --}}
<td class="hidden md:table-cell ...">
    <x-ui.button variant="link" href="...">編輯</x-ui.button>
</td>
```

### Grid 佈局

```blade
{{-- 表單欄位 2 欄（手機單欄） --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <x-ui.form-input .../>
    <x-ui.form-input .../>
</div>
```

---

## 禁止事項

| 禁止 | 原因 |
|------|------|
| `style="..."` inline style | 破壞 Tailwind 一致性，難以追蹤 |
| 使用非色彩系統的顏色（如 `blue-500`, `purple-600`） | 破壞視覺一致性 |
| 只寫 light mode 顏色不加 `dark:` 對應 | 深色模式顯示異常 |
| 用 Alpine 發送 fetch/axios 請求 | 業務資料操作由 Livewire 負責 |
| 引入新的前端依賴（Vue, React 等）未討論 | 架構決策需先討論確認 |
