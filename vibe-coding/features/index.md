# Features Index

## 這份文件做什麼

- 用來追蹤 feature 討論、短記錄、已確認方向與後續處置
- 這份文件是 feature 討論入口，不取代正式 spec
- 優化或擴充討論時，應先查看已確認 feature，再查看其他討論記錄

## 使用規則

- `confirmed` feature 可優先作為優化提示
- `brief_note` 只代表曾討論過，不代表已確認
- feature 不可未經使用者確認就轉成正式 spec 或 milestone task
- feature 不可自動覆蓋 milestone `work_order`
- feature 轉成正式 spec 後，`specs/` 才是 source of truth

## Confirmed Features

| feature_id | status | title | prompt_priority | discussion_file | promoted_spec_refs | related_milestone_refs | updated_at |
| --- | --- | --- | --- | --- | --- | --- | --- |

## Active Discussions

| feature_id | status | title | stopping_point_reason | discussion_file | open_questions | updated_at |
| --- | --- | --- | --- | --- | --- | --- |

## Brief Notes

| note_id | title | prompt | updated_at |
| --- | --- | --- | --- |

## Deferred

| feature_id | status | title | reason | discussion_file | updated_at |
| --- | --- | --- | --- | --- | --- |

## Rejected

| feature_id | status | title | reason | discussion_file | updated_at |
| --- | --- | --- | --- | --- | --- |

## 範例

### Confirmed Features

| feature_id | status | title | prompt_priority | discussion_file | promoted_spec_refs | related_milestone_refs | updated_at |
| --- | --- | --- | --- | --- | --- | --- | --- |
| FEAT-001 | confirmed | 範例優化方向 | high | vibe-coding/features/FEAT-001.md | - | - | 2026-05-05 |

### Brief Notes

| note_id | title | prompt | updated_at |
| --- | --- | --- | --- |
| NOTE-001 | 範例短記錄 | 曾討論：範例短記錄。後續要討論 / 保留 / 棄用？ | 2026-05-05 |

## 空白模板

```md
## Confirmed Features

| feature_id | status | title | prompt_priority | discussion_file | promoted_spec_refs | related_milestone_refs | updated_at |
| --- | --- | --- | --- | --- | --- | --- | --- |

## Active Discussions

| feature_id | status | title | stopping_point_reason | discussion_file | open_questions | updated_at |
| --- | --- | --- | --- | --- | --- | --- |

## Brief Notes

| note_id | title | prompt | updated_at |
| --- | --- | --- | --- |

## Deferred

| feature_id | status | title | reason | discussion_file | updated_at |
| --- | --- | --- | --- | --- | --- |

## Rejected

| feature_id | status | title | reason | discussion_file | updated_at |
| --- | --- | --- | --- | --- | --- |
```

## 正式資料

### Confirmed Features

| feature_id | status | title | prompt_priority | discussion_file | promoted_spec_refs | related_milestone_refs | updated_at |
| --- | --- | --- | --- | --- | --- | --- | --- |
| FEAT-001 | confirmed | 後台外觀偏好設定 | high | vibe-coding/features/FEAT-001.md | - | - | 2026-05-10 |

### Brief Note 固定提示

- `曾討論：<title>。後續要討論 / 保留 / 棄用？`
