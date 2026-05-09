# Milestone Tasks

## 這份文件做什麼

- 用來說明每個 milestone 的 tasks file 如何建立與維護
- 每個 milestone 必須有且只有一個 tasks file
- tasks file 是 task 詳細狀態的 source of truth；`index.md` 只保存摘要與短引用

## 檔案位置與命名

- tasks file 放在 `vibe-coding/milestones/tasks/`
- 檔名格式為 `<milestone-id>.md`
- 範例：`vibe-coding/milestones/tasks/M001.md`

## Task 狀態

- `todo`: 尚未開始
- `in_progress`: 正在處理
- `blocked`: 因依賴或阻塞暫時不能處理
- `done`: 已完成
- `skipped`: 明確略過，不納入必要完成條件

## 時間與工時

- `started_at`: task 進入 `in_progress` 時記錄
- `completed_at`: task 進入 `done` 時記錄
- `actual_duration`: 實際投入工時，格式為 `00h:00m`
- `elapsed_calendar_duration`: 頭尾時間跨度，以天為單位並保留小數點兩位
- `actual_duration` 不可由 `started_at` 和 `completed_at` 直接猜測

## Manual Pending

- manual pending 代表使用者基於商業決策手動暫緩某個 task
- AI 不可把一般 `todo`、`blocked`、`in_progress` 自動視為 manual pending
- manual pending 的短引用可同步到 `index.md` 的 `manual_pending_task_refs`

## 範例

```md
# M001 Tasks

## Milestone

- milestone_id: M001
- title: 範例 milestone
- status: planned
- started_at: -
- completed_at: -
- actual_duration: 00h:00m
- elapsed_calendar_duration: 0.00

## Task Summary

- todo: 1
- in_progress: 0
- blocked: 0
- done: 0
- skipped: 0

## Tasks

| task_id | status | title | dependency_task_ids | blocker_summary | manual_pending | started_at | completed_at | actual_duration | elapsed_calendar_duration | completion_evidence |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | ---: | --- |
| T001 | todo | 範例 task | - | - | false | - | - | 00h:00m | 0.00 | - |
```

## 空白模板

```md
# <milestone-id> Tasks

## Milestone

- milestone_id:
- title:
- status:
- started_at:
- completed_at:
- actual_duration: 00h:00m
- elapsed_calendar_duration: 0.00

## Task Summary

- todo:
- in_progress:
- blocked:
- done:
- skipped:

## Tasks

| task_id | status | title | dependency_task_ids | blocker_summary | manual_pending | started_at | completed_at | actual_duration | elapsed_calendar_duration | completion_evidence |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | ---: | --- |
| T001 | todo |  | - | - | false | - | - | 00h:00m | 0.00 | - |
```

## 正式資料

### 建議流程

- 先在 `index.md` 建立 milestone entry
- 再依 `tasks_file_path` 建立對應 tasks file
- 每次更新 task 狀態後，同步更新 `index.md` 的 `task_status_summary`
- 若使用者手動將 task 標記為 pending，才同步短引用到 `index.md` 的 `manual_pending_task_refs`

## 同步規則

- `index.md` 中的 `task_status_summary` 必須由 tasks file 統計而來
- `index.md` 中的 `manual_pending_task_refs` 必須指向 tasks file 中存在的 task
- milestone 標記為 `completed` 前，必須確認必要 tasks 都是 `done` 或 `skipped`
