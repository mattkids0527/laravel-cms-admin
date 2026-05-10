# Handoff：M007–M008 Blade UI 元件庫建立與 Views 重構

- **日期**：2026-05-10
- **里程碑**：M007（completed）、M008（completed）

---

## 完成內容

### M007：Blade UI 元件庫建立

建立 11 個 Blade Anonymous Components，分三個 tier：

**Tier 1 — 基礎 UI（`resources/views/components/ui/`）**

| 元件 | 說明 |
|------|------|
| `x-ui.alert` | Flash 訊息，`type`：success / error / warning / info |
| `x-ui.badge` | 狀態徽章，`color`：green / gray / yellow / red / indigo / blue；`shape`：pill / square |
| `x-ui.button` | 按鈕，`variant`：primary / secondary / danger / link / link-gray / link-danger / sheet-primary / sheet-default / sheet-danger；`size`：sm / md / lg；`href` 有值時渲染 `<a>`，無值時渲染 `<button>` |
| `x-ui.card` | 卡片容器，`padding`：true（預設，含 p-6）/ false（overflow-hidden，適合表格外框） |

**Tier 2 — 表單（`resources/views/components/ui/`）**

| 元件 | 說明 |
|------|------|
| `x-ui.form-input` | 文字輸入 + label + 驗證錯誤，`name` prop 對應 `$errors->has($name)` |
| `x-ui.form-select` | Select 下拉 + label + 驗證錯誤 |
| `x-ui.form-checkbox` | Checkbox + label，`$slot` 可放描述文字 |

**Tier 3 — 版面（`resources/views/components/admin/`）**

| 元件 | 說明 |
|------|------|
| `x-admin.page-header` | 區塊標題 + 右側 action slot |
| `x-admin.toolbar` | 搜尋 input + `filters` slot + `actions` slot，`wire:*` 屬性透傳至 input |
| `x-admin.data-table` | 表格外框，`head` / `body` slot（必填），`footer` slot（選填，用於分頁） |
| `x-admin.mobile-row-action` | 手機 bottom-sheet，`title` / `subtitle` prop，依賴父層 `<tr x-data="{ open: false }">` 的 `open` 狀態 |

### M008：Views 重構

共重構 9 個 view，1 個跳過（`auth/login.blade.php`）：

| 檔案 | 主要改動 |
|------|----------|
| `dashboard.blade.php` | 3 個 stat card → `x-ui.card` |
| `roles/create.blade.php` | card + form-input + button |
| `settings/appearance-settings.blade.php` | 3 個 section → `x-ui.card` |
| `users/create.blade.php` | card + form-input × 4 + form-select + form-checkbox + button |
| `users/edit.blade.php` | 同上；角色 disabled 邏輯保留 raw HTML |
| `roles/edit.blade.php` | card + form-input + button；保護警告（amber border）保留 raw HTML |
| `users/index.blade.php` | alert × 2 + toolbar + data-table + badge + button + mobile-row-action |
| `roles/index.blade.php` | alert + data-table + badge + button + mobile-row-action |
| `menu-permissions/index.blade.php` | alert + card × 2 + form-checkbox + button |

---

## 重要技術決策

### 1. `sheet-*` button variant 設計
`x-ui.button` 的 `sheet-primary / sheet-default / sheet-danger` 是手機 bottom-sheet 專用變體，包含自己的 `block w-full py-2.5 text-center` layout，會忽略 `size` prop。目的是讓 mobile 操作按鈕可以統一用 `x-ui.button` 而不需另外記憶 class。

### 2. form-input 的 label + hint 組合
`x-ui.form-input` 的 `label` prop 只支援純字串。若 label 需要 hint 說明文字（如「留空則不變更」、「（選填）」），應手動寫 `<label>` 後接 `<x-ui.form-input>` 不傳 `label` prop：

```blade
<div>
    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
        新密碼 <span class="text-gray-400 dark:text-gray-500 font-normal text-xs">（留空則不變更）</span>
    </label>
    <x-ui.form-input name="password" type="password" wire:model="password" />
</div>
```

### 3. 保留 raw HTML 的兩個場景
以下場景刻意保留 raw HTML，不強制使用元件：
- `roles/edit.blade.php` 的保護角色警告（amber 背景 + border 的 notice 樣式，不同於 flash 訊息）
- `users/edit.blade.php` 的角色 disabled 狀態（`opacity-50 cursor-not-allowed` 需套在 `<label>` 上，元件不支援此 disabled 樣式）

### 4. toolbar 的篩選 select 不使用 form-select
`x-ui.form-select` 會包 `<div>` wrapper（用於 label + 錯誤顯示）。在 toolbar flex 列中只需要裸露的 `<select>`，因此 filter 區的 select 保留 raw HTML。

### 5. Tailwind 掃描路徑已自動涵蓋
`tailwind.config.js` 的 content 已包含 `./resources/views/**/*.blade.php`，所有新建元件路徑無需額外設定。

---

## 目前狀態

- M001–M008 全部 completed
- 11 個 Blade 元件已建立，9 個 view 已重構使用元件
- `npm run build` 無錯誤

---

## 下一步建議

- 若新增後台頁面，直接使用元件庫，不再需要手動撰寫重複 HTML
- 若需新增元件（如 modal、pagination wrapper、breadcrumb），在 `resources/views/components/ui/` 或 `resources/views/components/admin/` 擴充
- `auth/login.blade.php` 目前為 raw HTML，若未來需要統一 guest 頁面風格，可另建 `x-guest.card` 等元件

---

## 風險與注意事項

- `x-admin.mobile-row-action` 依賴父層 `<tr x-data="{ open: false }">` 的 Alpine 作用域，若在非表格情境使用需自行提供 `open` 變數
- `admin.blade.php` 的 `<style>` block 仍集中管理所有響應式 CSS（含 sidebar、bottom-sheet 等），元件本身不引入新 CSS；未來若需要可統一移至 `resources/css/app.css` 的 `@layer components`
- 新增響應式行為時，仍須遵循 M006 handoff 的原則：使用 CSS class + `<style>` block，不依賴 Tailwind responsive prefix（`md:` 等）
