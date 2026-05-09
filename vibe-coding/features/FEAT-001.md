# FEAT-001：後台外觀偏好設定

## 基本資訊

| 欄位 | 值 |
| --- | --- |
| feature_id | FEAT-001 |
| status | confirmed |
| title | 後台外觀偏好設定 |
| discussed_at | 2026-05-10 |
| updated_at | 2026-05-10 |

## 需求概述

使用者可以在後台自訂外觀偏好，包含深色/淺色主題切換、側邊選單收合，並透過獨立的個人設定頁統一管理與重設。

---

## Feature A：深色 / 淺色主題切換

- **技術方案**：Tailwind CSS `class` 策略，切換時在 `<html>` 加入或移除 `dark` class
- **偏好儲存**：`localStorage`
- **切換入口**：header 放一個 toggle 按鈕

## Feature B：側邊選單收合

- **收合樣式**：icon-only（只顯示圖示，文字隱藏）
- **Tooltip**：icon-only 狀態下，hover 選單項目時顯示選單名稱 tooltip
- **技術方案**：Alpine.js 處理收合狀態
- **偏好儲存**：`localStorage`（記住上次狀態）

## Feature C：外觀偏好設定頁

- **位置**：獨立個人設定頁（路由待規劃）
- **內容**：
  - 主題切換控制（亮色 / 暗色）
  - Sidebar 展開 / 收合控制
  - 一鍵全部重設按鈕
- **重設後預設值**：
  - 主題 → 亮色
  - Sidebar → 展開

---

## 開放問題

- 個人設定頁路由尚未定義（進入正式 spec 時再決定）

## 後續處置

- 尚未進入正式 spec，待使用者確認要規劃 milestone 時再推進
