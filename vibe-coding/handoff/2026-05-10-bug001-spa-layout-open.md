# Handoff：BUG-001 SPA 換頁跑版（resolved）

- **日期**：2026-05-10
- **狀態**：resolved（已解決）
- **bug_ref**：BUG-001
- **影響 milestone**：M004（後台外觀偏好設定）

---

## 問題描述

登入後透過 Livewire Navigate Plugin SPA 換頁進入 dashboard，版面瞬間跑版（sidebar 與主內容區排版崩潰）。重新整理頁面後版面恢復正常。

---

## 根本原因

`Login.php` line 45 使用 `navigate: true` 觸發 SPA 換頁。Tailwind Play CDN 靠 MutationObserver 動態產生 CSS，SPA 換頁時 DOM 已替換但 CSS 尚未注入，造成一個 tick 的空窗期，頁面在無結構 CSS 的狀態下渲染。

---

## 已嘗試的修正（均無效）

### 嘗試 1：sidebar 改用 native CSS
`.sidebar { width: 16rem }` / `.sidebar.collapsed { width: 4rem }` 取代 `w-64`/`w-16`。
結果：sidebar 寬度 OK，外層容器仍跑版。

### 嘗試 2：所有核心版面 class 改用 native CSS
新增 `.layout-root`、`.layout-main`、`.layout-header`、`.layout-content`，取代外層容器的 Tailwind 結構 class。
結果：版面仍跑版，推測 `<aside>` 內部或其他元素仍依賴 Tailwind CDN。

---

## 目前檔案狀態

- `resources/views/components/layouts/admin.blade.php`：已套用嘗試 2（native layout class）
- `app/Livewire/Admin/Auth/Login.php` line 45：仍使用 `navigate: true`
- `vibe-coding/specs/fix/BUG-001-spa-layout-break.md`：完整問題記錄與解法分析

---

## 解決方式

採用方案 A：`Login.php:45` 改為 `navigate: false`（full page reload）。

測試確認版面正常，根因驗證：問題確實為 Livewire Navigate Plugin SPA + Tailwind Play CDN 組合所致。

## 後續注意

- 若未來改用 Vite + Tailwind build，可重新考慮開啟 `navigate: true`
- 詳細分析見：`vibe-coding/specs/fix/BUG-001-spa-layout-break.md`
