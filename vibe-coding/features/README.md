# Features README

## 這份文件做什麼

- 用來說明 `vibe-coding/features/` 如何記錄 feature 方向、討論過程與後續處置
- feature 討論是正式 spec 之前的工作記錄，不是系統 source of truth
- feature 一旦確定要納入系統，應再轉寫到 `vibe-coding/specs/`、`vibe-coding/specs/decisions.md` 或 `vibe-coding/milestones/`

## 使用時機

- 使用者提出一個 feature 方向，但尚未形成正式 spec
- feature 已討論出問題、意圖、選項或待確認問題
- 對話即將切換主題，但 feature 討論值得之後回顧
- 使用者要求優化或改善專案時，需要回顧已確認 feature

## AI 協作規則

- 當 feature 討論到可暫存點時，AI 應詢問使用者是否保留完整討論
- 若使用者確認保留，AI 應建立或更新對應 feature 討論檔，並更新 `index.md`
- 若使用者未確認保留，AI 只在 `index.md` 留下短記錄
- 短記錄格式建議為：`曾討論：<title>。後續要討論 / 保留 / 棄用？`
- 已確認 feature 可作為優化提示，但不可自動覆蓋 milestone `work_order`
- 未經使用者確認，不可把 feature 自動轉成正式 spec 或 milestone task

## Feature 狀態

- `proposed`: 剛提出，尚未開始整理
- `discussing`: 正在討論
- `brief_note`: 只保留短記錄
- `preserved`: 已保留完整討論
- `confirmed`: 使用者確認為後續優先方向
- `accepted_for_spec`: 使用者確認要轉成正式 spec
- `deferred`: 延後處理
- `rejected`: 不追蹤或不採用

## 範例

```md
# FEAT-001 範例 Feature

## 狀態

- status: preserved
- title: 範例 feature
- created_at: 2026-05-05
- updated_at: 2026-05-05

## 問題與意圖

- problem_statement: 使用者需要更快找到待處理項目
- user_intent: 優化日常工作入口

## 討論摘要

- options_considered:
  - 在 dashboard 顯示
  - 在 milestone index 顯示
- open_questions:
  - 是否需要排序規則？

## 後續處置

- decision_summary: 暫時保留，之後確認是否轉 spec
- promoted_spec_refs: -
- related_milestone_refs: -
```

## 空白模板

```md
# <feature-id> <title>

## 狀態

- status:
- title:
- created_at:
- updated_at:

## 問題與意圖

- problem_statement:
- user_intent:

## 討論摘要

- options_considered:
  -
- open_questions:
  -

## 後續處置

- decision_summary:
- promoted_spec_refs:
- related_milestone_refs:
```

## 正式資料

### 建議流程

- 先在 `index.md` 建立或更新 feature entry
- 若使用者確認保留完整討論，再建立 `vibe-coding/features/<feature-id>.md`
- 若使用者只需要短記錄，留在 `index.md` 的 Brief Notes 區塊
- 若使用者確認 feature 要實作或規格化，再轉入正式 spec 或 milestone
