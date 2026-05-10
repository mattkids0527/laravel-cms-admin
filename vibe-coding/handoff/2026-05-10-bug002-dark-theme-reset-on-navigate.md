# Handoff：BUG-002 表單儲存後暗色主題被重置（resolved）

- **日期**：2026-05-10
- **狀態**：resolved（已解決）
- **bug_ref**：BUG-002

---

## 問題描述

在後台以暗色主題操作時，填寫帳號或角色表單並儲存後，頁面跳轉至 index 頁面的當下，暗色主題會自動切換回亮色主題。重新整理頁面後主題恢復正常（localStorage 資料正確）。

---

## 根本原因

所有表單 save 使用 `$this->redirect(route(...), navigate: true)`，觸發 Livewire SPA 導航。

Livewire SPA 導航換頁時，會將 `<html>` 元素的 attributes **同步為新頁面的 `<html>` tag 內容**。由於新頁面的 `<html lang="zh-TW">` 沒有 `class` attribute，這個同步動作會**清除動態加上去的 `class="dark"`**。

此後沒有任何機制重新呼叫 `applyTheme()`，導致 `dark` class 永久遺失，頁面維持亮色模式，直到下次完整 reload。

受影響的 Livewire components（都使用 `navigate: true`）：
- `app/Livewire/Admin/Users/UserCreate.php` line 48
- `app/Livewire/Admin/Users/UserEdit.php` line 85
- `app/Livewire/Admin/Roles/RoleCreate.php` line 30
- `app/Livewire/Admin/Roles/RoleEdit.php` line 43

---

## 修復方式

**修改一個檔案**：`resources/views/components/layouts/admin.blade.php`

### 1. 兩個 inline `<script>` 加上 `data-navigate-once`

```html
<script data-navigate-once>
    if (localStorage.getItem('admin_theme') === 'dark') { ... }
</script>

<script data-navigate-once>
(function () { ... })();
</script>
```

`data-navigate-once` 告訴 Livewire SPA 導航時不重新執行這些 script，Alpine store 狀態因此保留不重建。

### 2. 新增 `livewire:navigated` 事件監聽器

```javascript
document.addEventListener('livewire:navigated', function () {
    if (window.Alpine) {
        Alpine.store('appearance').applyTheme();
    }
});
```

`livewire:navigated` 在 Livewire SPA 導航完成後（DOM 完整更新後）觸發。此時呼叫 `applyTheme()` 根據 store 中儲存的 `theme` 值（正確的 `'dark'`）重新設定 `<html>` 的 `class`。

---

## 技術補充

- `applyTheme()` 做的事：`document.documentElement.classList.toggle('dark', this.theme === 'dark')`
- `theme` 值在整個 SPA 導航過程中都是正確的（localStorage 未被清除），`applyTheme()` 重新執行後就能正確補回 `dark` class
- 此修復對首次頁面載入（full reload）無影響，`data-navigate-once` 只影響 SPA 導航時的行為

---

## 驗證

- 暗色模式下新增/編輯帳號、新增/編輯角色，儲存後跳轉至 index 頁面，主題維持暗色 ✓
- 亮色模式下操作同上，主題維持亮色 ✓
- 頁面重新整理（F5）後主題根據 localStorage 正確恢復 ✓
- 頂部主題切換按鈕仍正常運作 ✓
