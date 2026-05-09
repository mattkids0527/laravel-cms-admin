# Milestones Index

## 這份文件做什麼

- 用來追蹤此產品專案的 milestone 總覽、工作順序與目前進度
- 人與 AI 都應優先從這份總表判斷目前應處理哪個 milestone
- task 詳細內容不寫在總表，請放在 `vibe-coding/milestones/tasks/<milestone-id>.md`

## 使用規則

- `work_order` 是明確工作順序，AI 提供下一步建議時應優先依它判斷
- `priority` 是選填的重要性提示，可輔助討論，但不可覆蓋 `work_order`
- `ignored = true` 的 milestone 暫時不納入下一步建議，但仍保留在總表中
- `manual_pending_task_refs` 只記錄使用者手動標記 pending 的商業決策項，不可由 AI 自動推導
- `task_status_summary` 只能由對應 tasks file 摘要而來，不可取代 tasks file 中的 task 狀態
- `actual_duration` 使用 `00h:00m`，跨天工時直接累計到小時
- `elapsed_calendar_duration` 使用天為單位並保留小數點兩位

## Milestone 狀態

- `planned`: 已規劃但尚未開始
- `active`: 目前正在處理
- `blocked`: 想處理但被依賴或阻塞卡住
- `ignored`: 暫時不納入工作建議
- `completed`: 已完成
- `cancelled`: 已取消或不再屬於範圍

## 範例

| milestone_id | work_order | priority | status | ignored | title | task_status_summary | manual_pending_task_refs | started_at | completed_at | actual_duration | elapsed_calendar_duration | tasks_file_path |
| --- | ---: | --- | --- | --- | --- | --- | --- | --- | --- | --- | ---: | --- |
| M001 | 1 | high | planned | false | 範例 milestone | todo:0, in_progress:0, blocked:0, done:0, skipped:0 | - | - | - | 00h:00m | 0.00 | vibe-coding/milestones/tasks/M001.md |

## 空白模板

```md
| milestone_id | work_order | priority | status | ignored | title | task_status_summary | manual_pending_task_refs | started_at | completed_at | actual_duration | elapsed_calendar_duration | tasks_file_path |
| --- | ---: | --- | --- | --- | --- | --- | --- | --- | --- | --- | ---: | --- |
|  |  |  | planned | false |  | todo:0, in_progress:0, blocked:0, done:0, skipped:0 | - | - | - | 00h:00m | 0.00 |  |
```

## 正式資料

| milestone_id | work_order | priority | status | ignored | title | task_status_summary | manual_pending_task_refs | started_at | completed_at | actual_duration | elapsed_calendar_duration | tasks_file_path |
| --- | ---: | --- | --- | --- | --- | --- | --- | --- | --- | --- | ---: | --- |
| M001 | 1 | high | completed | false | 專案 Spec 定義 | todo:0, in_progress:0, blocked:0, done:5, skipped:0 | - | 2026-05-10 | 2026-05-10 | 00h:30m | 0.00 | vibe-coding/milestones/tasks/M001.md |
| M002 | 2 | high | completed | false | 帳號與角色管理系統 | todo:0, in_progress:0, blocked:0, done:12, skipped:0 | - | 2026-05-10 | 2026-05-10 | 02h:00m | 0.00 | vibe-coding/milestones/tasks/M002.md |
| M003 | 3 | high | completed | false | 後台選單權限系統 | todo:0, in_progress:0, blocked:0, done:10, skipped:0 | - | 2026-05-10 | 2026-05-10 | 01h:30m | 0.00 | vibe-coding/milestones/tasks/M003.md |
| M004 | 4 | high | completed | false | 後台外觀偏好設定 | todo:0, in_progress:0, blocked:0, done:5, skipped:0 | - | 2026-05-10 | 2026-05-10 | 01h:00m | 0.00 | vibe-coding/milestones/tasks/M004.md |
| M005 | 5 | high | completed | false | 前端工具鏈遷移（Vite + Tailwind Build） | todo:0, in_progress:0, blocked:0, done:8, skipped:0 | - | 2026-05-10 | 2026-05-10 | 00h:30m | 0.00 | vibe-coding/milestones/tasks/M005.md |

## 下一步提示規則

- 優先建議 `active` 且未 ignored、未 blocked 的 milestone
- 若沒有可建議的 `active` milestone，依 `work_order` 建議最前面的 `planned` milestone
- `blocked`、`ignored`、`completed`、`cancelled` 不應作為下一步建議，除非使用者明確要求查看這些狀態
- 使用這份總表提供建議前，應確認總表與對應 tasks file 是否同步
