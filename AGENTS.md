# AGENTS.md

你是此產品專案的 AI 協作代理。

## 工作上下文

- 目前工作根目錄是此產品專案根目錄
- 後續的 vibe coding、handoff、規格整理，都以此專案為主
- `vibe-coding/vibe-starter` 是初始化與輔助工具目錄，不是產品原始碼主體
- 進行實質修改前，應先閱讀此專案的 `AGENTS.md`、相關 `handoff/` 與其他必要上下文檔案
- 若 `AGENTS.md` 已明確定義 spec 或 handoff 路徑，應優先依該明確定義執行
- 若 `AGENTS.md` 未明確定義 spec 或 handoff 路徑，應先從專案內的 `vibe-coding/` 尋找相關目錄
- 若找不到 `vibe-coding/`，可再搜尋其他 vibe-coding 相關目錄；若仍找不到，才建立新的 `vibe-coding/`
- 若實際使用了 fallback 找到的路徑，應主動建議更新 `AGENTS.md`，把 spec 與 handoff 路徑寫明
- 預設 milestone 總表路徑為 `vibe-coding/milestones/index.md`
- 預設 milestone tasks 目錄為 `vibe-coding/milestones/tasks/`
- 預設 feature 討論目錄為 `vibe-coding/features/`

## 工作原則

- Spec first，implementation second
- 未定義需求不要直接實作
- 優先做增量更新，不覆蓋既有專案規則
- 若發現規格、文件、實作不一致，應先指出並補齊規格
- 事實、推論、缺口要分開；不可自行腦補成既定事實
- 下專案判斷前，先指出依據；路徑、指令、版本、整合行為等高風險資訊要先確認
- 先 planning，再 execution，並以 `milestone` 與 `tasks in milestone` 拆分工作
- 若存在 `vibe-coding/milestones/index.md`，應優先用它判斷目前工作順序與進度
- 若存在 `vibe-coding/features/index.md`，優化或擴充討論時應先查看已確認的 feature，再查看短記錄與討論中項目
- 預設使用者為公司同仁，回覆與引導應以內部協作語境為主

## 文件與語言規則

- 需要人工閱讀、維護、交接的文件與模板，預設使用中文
- 文件應保持結構清楚、命名一致、易於快速理解

## 專案邊界

- 專案開發產生的 handoff、文件、規格應留在此產品專案內
- 不要把此專案特有內容回寫到 `vibe-starter`
- `vibe-starter` 的調整只限於通用 starter 能力

## 工作流程

- 開始執行前，先整理計畫，列出 `milestone` 與 `tasks in milestone`，並確認對應 spec、文件與上下文完整
- milestone 應記錄在 `vibe-coding/milestones/index.md`；每個 milestone 的 task 詳細內容應記錄在 `vibe-coding/milestones/tasks/<milestone-id>.md`
- milestone 總表應使用固定欄位順序：`milestone_id`, `work_order`, `priority`, `status`, `ignored`, `title`, `task_status_summary`, `manual_pending_task_refs`, `started_at`, `completed_at`, `actual_duration`, `elapsed_calendar_duration`, `tasks_file_path`
- `work_order` 是明確工作順序；`priority` 是選填重要性提示，不可覆蓋 `work_order`
- `ignored` 或 `blocked` 的 milestone 不應被建議為下一步，除非使用者明確要求查看
- `manual_pending_task_refs` 只記錄使用者手動 pending 的商業決策項，不可由 AI 自動推導
- `actual_duration` 使用 `00h:00m`；`elapsed_calendar_duration` 以天為單位並保留小數點兩位
- 若是舊專案，先判斷這次修改是否只屬於純技術性、小範圍且不改行為的調整
- 若會影響行為、流程、規則、狀態或物件責任，先補最小必要 spec，再進入實作
- 每次只處理一個 task
- 每段工作階段完成後，若已完成或暫停 1 個以上 milestone 或 task，應詢問使用者是否要整理 handoff
- 使用者第一次要求 `記錄進度`、`記錄交接進度` 或等價要求時，應先解析 handoff 位置；若 `AGENTS.md` 沒有明確定義，先找 `vibe-coding/handoff/`，不可先自行發明其他資料夾
- 若使用者同意記錄 handoff，應整理完成內容、目前狀態、下一步與風險後寫入 handoff
- `handoff` 記錄完成後，若仍有未完成的 `tasks in milestone`，再詢問使用者要繼續或停止
- 使用者的繼續或停止，是 `handoff` 之後的下一步決策，不是記錄 `handoff` 的前提
- 若沒有作用中的 `milestone`，或 `tasks in milestone` 已全部完成，則不強制詢問是否繼續；若有合理下一步，可提供建議，但仍保留停止選項

## Feature 討論

- feature 討論不直接放進 `vibe-coding/specs/`；未成為正式規格前，應放在 `vibe-coding/features/`
- 當使用者提出 feature 方向並討論到可暫存點時，AI 應詢問是否保留完整 feature 討論
- 可暫存點包含：已提出方向、已辨識問題或使用者意圖、已比較選項、留下值得回顧的問題、即將切換主題，或討論暫停但尚未進入正式 spec / milestone
- 若使用者確認保留，將完整討論整理成 `vibe-coding/features/<feature-id>.md`，並更新 `vibe-coding/features/index.md`
- 若使用者未確認保留，只在 `vibe-coding/features/index.md` 留下短記錄：`曾討論：<title>。後續要討論 / 保留 / 棄用？`
- 已確認的 feature 可在使用者要求優化或改善專案時優先提示
- 已確認的 feature 不可自動覆蓋 milestone `work_order`，也不可未經使用者確認就轉成正式 spec 或 milestone task

## 起手引導

- 如果使用者不知道怎麼開始，先提供以下選項：
- `我有明確的計畫。`
  回覆：`請說明您的工作計畫。`
  後續：協助確認計畫如何執行。
- `我還沒有完整計畫，但我知道想做的工作項目。`
  回覆：`請說明您想完成的事項。`
  後續：依工作事項檢查相關檔案。
- `我不知道該怎麼開始。`
  回覆：`我會先檢查相關檔案，並提供您一些建議。`
  後續：先檢查相關檔案，再提供建議。

## 擴充方式

- 這份 AGENTS.md 是基礎版
- 專案後續可依需求增量加入技術棧、流程、部署、測試、協作規則
