# BUG-001：SPA 換頁後版面跑版

- **狀態**：resolved
- **發現日期**：2026-05-10
- **影響範圍**：登入後首次進入 dashboard（navigate: true）

---

## 症狀

使用者登入後，頁面透過 Livewire Navigate Plugin（SPA 換頁）跳轉到 dashboard，版面瞬間跑版（sidebar 與主內容區排版崩潰），重新整理後恢復正常。

---

## 根本原因分析

### 技術背景

- `Login.php` line 45：`$this->redirect(route('admin.dashboard'), navigate: true)` — 觸發 Livewire Navigate Plugin SPA 換頁
- SPA 換頁時，瀏覽器**不執行完整頁面重載**，只做 DOM morphing
- 版面使用 **Tailwind CDN（Play CDN）**：靠 JavaScript + MutationObserver 動態產生 CSS

### 問題核心

Tailwind Play CDN 的運作流程：
1. CDN script 在 `<head>` 載入後常駐記憶體
2. 監聽 DOM 變動（MutationObserver）
3. 偵測到新 class → 即時產生對應 CSS 並注入 `<style>` tag

**SPA 換頁時序問題**：
1. Livewire Navigate Plugin 替換 `<body>` 內容（DOM morphing）
2. Tailwind CDN observer 觸發，開始產生 CSS
3. 但這有一個 tick 的延遲 → 在這段空窗內，頁面已渲染但 CSS 尚未注入
4. → 版面閃爍 / 跑版

### 已嘗試但無效的修正

#### 嘗試 1：sidebar 改用 native CSS
將 `<aside>` 的寬度從 `w-64`/`w-16` 改為 `.sidebar { width: 16rem }` native CSS。
**結果**：sidebar 本身 OK，但外層容器仍跑版。

#### 嘗試 2：所有核心結構 class 改用 native CSS
新增以下 native CSS class，替換外層容器的 Tailwind 結構 class：

```css
.layout-root    { display: flex; height: 100vh; overflow: hidden; }
.layout-main    { flex: 1 1 0%; min-width: 0; display: flex; flex-direction: column; overflow: hidden; }
.layout-header  { height: 4rem; display: flex; align-items: center; padding-left: 1.5rem; padding-right: 1.5rem; flex-shrink: 0; }
.layout-content { flex: 1 1 0%; overflow-y: auto; padding: 1.5rem; }
```

對應 HTML 替換：
- `<div class="flex h-screen overflow-hidden">` → `<div class="layout-root">`
- `<div class="flex-1 flex flex-col overflow-hidden">` → `<div class="layout-main">`
- `<header class="h-16 ... flex items-center px-6 flex-shrink-0">` → `<header class="layout-header ...">`
- `<main class="flex-1 overflow-y-auto p-6">` → `<main class="layout-content">`

**結果**：版面仍跑版，推測還有其他 Tailwind class 在 SPA 換頁時未及時套用。

---

## 待調查

1. **Livewire Navigate Plugin 是否有 morph 完成的 hook**（如 `navigate:finish` 事件），可以在這個時機點強制重跑 Tailwind CDN scan
2. **`<aside>` 內部的 `flex flex-col`**（sidebar 本身的 flex 結構）是否也是問題來源
3. **`body` 的 `bg-gray-100 dark:bg-gray-950`** 之外，是否有其他 class 影響結構
4. **Tailwind CDN 的 `data-navigate-once`** 機制：確認 CDN script 是否在 SPA 換頁後重新初始化或丟失 observer
5. 考慮改用 `navigate: false`（full page reload）作為 login redirect 的臨時解法，確認問題是否完全消失，以驗證根因
6. 考慮全面棄用 Tailwind CDN，改用 Vite + Tailwind build（根本解法）

---

## 潛在解法方向

### 方案 A：強制 full page reload（最快驗證）
`Login.php` 改為 `$this->redirect(route('admin.dashboard'), navigate: false)`
- 優點：100% 繞過問題，立即見效
- 缺點：失去 SPA 換頁效果

### 方案 B：listen navigate:finish 後 re-trigger Tailwind CDN
在 `<head>` 加入：
```js
document.addEventListener('livewire:navigated', function () {
    if (window.tailwind && typeof tailwind.refresh === 'function') {
        tailwind.refresh();
    }
});
```
- 優點：保留 SPA；治標
- 缺點：Tailwind CDN API 不穩定，可能無效

### 方案 C：所有 `<aside>` 內部結構也改 native CSS（繼續方案 2）
繼續把 `flex flex-col`（aside 內）、`space-y-4`（nav 內）等全部換成 native class
- 優點：不改架構
- 缺點：工程量大，可能沒有終點

### 方案 D：改用 Vite + Tailwind build（根本解法）
移除 CDN，改用 npm + vite build，CSS 在 build time 生成
- 優點：根本解決，效能更好，適合正式環境
- 缺點：需重構前端工具鏈

---

## 解決方案

採用方案 A：`Login.php` line 45 改為 `navigate: false`（full page reload），放棄 SPA 換頁效果。

測試確認版面正常，根因驗證：問題確實為 Livewire Navigate Plugin SPA + Tailwind Play CDN 組合所致。

### 注意事項
- `resources/views/components/layouts/admin.blade.php` 仍保留嘗試 2 的 native layout class（`.layout-root` 等），可視需求回復為 Tailwind class
- 若未來改用 Vite + Tailwind build，可重新考慮開啟 `navigate: true`

## 目前狀態的檔案

- `resources/views/components/layouts/admin.blade.php`：已套用嘗試 2 的修正（native layout class）
- `app/Livewire/Admin/Auth/Login.php` line 45：已改為 `navigate: false`（resolved）
