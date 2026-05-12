# Handoff：M009 後端模組化架構遷移（nwidart/laravel-modules）

- **日期**：2026-05-11
- **里程碑**：M009（completed）

---

## 完成內容

將 `app/` 下所有業務邏輯拆分為 5 個 Laravel Module（`nwidart/laravel-modules ^10.0`）：

| 模組 | 路徑 | 包含內容 |
|------|------|----------|
| `Auth` | `Modules/Auth/` | AuthController, Login Livewire, guest layout |
| `Account` | `Modules/Account/` | User model, Role model, Users/Roles Livewire + views, AdminUserSeeder, RoleSeeder |
| `Menu` | `Modules/Menu/` | AdminMenu model, AdminMenuService, CheckAdminMenuPermission middleware, MenuPermissionIndex Livewire + view, AdminMenuSeeder |
| `Settings` | `Modules/Settings/` | AppearanceSettings Livewire + view |
| `Dashboard` | `Modules/Dashboard/` | Dashboard Livewire + view |

各模組均有獨立的 `App/Providers/<ModuleName>ServiceProvider.php`，負責 `loadViewsFrom()`、`loadRoutesFrom()`、`Livewire::component()` 手動註冊。

---

## 重要技術決策

### 1. 模組路由必須明確加入 `web` middleware

`RouteServiceProvider` 的 `Route::middleware('web')` 只包裝 `routes/web.php`，模組路由透過 `loadRoutesFrom()` 載入時**不繼承這個包裝**。若未加 `'web'`，`StartSession` 不會執行，導致：
- `csrf_token()` 回傳空字串
- Livewire AJAX 請求 → 419 → "This page has expired"

**所有模組 `routes/web.php` 必須自行加入 `web`：**
```php
// Guest 路由
Route::middleware('web')->prefix('admin')->name('admin.')->group(function () { ... });

// 需要認證的路由
Route::prefix('admin')->name('admin.')->middleware(['web', 'auth:admin', 'admin.menu'])->group(...)
```

### 2. ServiceProvider 必須在 `module:make` 後立即建立

`module.json` 在建立時即引用 ServiceProvider。若用 `--plain` 建立模組後不立即建立 ServiceProvider PHP 檔案，下一條 artisan 指令就會報錯。正確流程：
1. `php artisan module:make <Name> --plain`
2. 立刻建立 `Modules/<Name>/App/Providers/<Name>ServiceProvider.php`
3. 之後才執行其他 artisan 指令

### 3. Guest Layout 位置

Livewire 3 的 `->layout()` 需要目標是 Blade Component（`resources/views/components/` 下），不支援 module view namespace（`auth::layouts.guest`）。

Guest layout 放在：`resources/views/components/layouts/guest.blade.php`
Login 引用：`->layout('components.layouts.guest')`

### 4. 不遷移範圍

- `resources/views/components/` — 共用 UI 元件，保留原位
- `database/migrations/` — 保留原位
- `app/Http/Middleware/` — Laravel 核心 Middleware 保留
- `app/Providers/` — 保留原有 Providers

---

## 重要設定更新

- `config/auth.php`：`'model' => Modules\Account\App\Models\User::class`
- `app/Http/Kernel.php`：`'admin.menu' => \Modules\Menu\App\Http\Middleware\CheckAdminMenuPermission::class`
- `database/seeders/DatabaseSeeder.php`：引用 `Modules\Account\Database\Seeders\AdminUserSeeder`、`RoleSeeder`、`Modules\Menu\Database\Seeders\AdminMenuSeeder`
- `routes/web.php`：清空業務路由，只保留根路徑重定向

---

## 目前狀態

- M001–M009 全部 completed
- 模組化架構運作正常，登入功能正常
- skills 文件：`vibe-coding/skills/laravel-modules.md`

---

## 下一步建議

- 新增後台功能時，依職責歸入對應模組，或用 `php artisan module:make` 建立新模組
- 新模組記得：① 建立 ServiceProvider ② routes 加 `'web'` middleware
