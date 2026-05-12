# laravel-modules.md

## 這份文件做什麼

- 定義此專案後端採用 `nwidart/laravel-modules` 的模組化架構規範
- 說明現有模組結構、命名慣例與使用邊界，作為 AI 與開發者的協作上下文
- 實作新功能或修改現有功能時，應先確認是否屬於某個模組的職責範圍

---

## 套件資訊

- 套件：`nwidart/laravel-modules ^10.0`（對應 PHP ^8.1 / Laravel 10）
- 模組根目錄：`Modules/`（Laravel 專案根目錄下）
- 模組 namespace 前綴：`Modules\`
- `composer.json` autoload 已包含：`"Modules\\": "Modules/"`

---

## 現有模組

| 模組 | 路徑 | 職責 |
|------|------|------|
| `Auth` | `Modules/Auth/` | 登入、登出（AuthController, Login Livewire） |
| `Account` | `Modules/Account/` | 帳號管理、角色管理（User / Role model, Users / Roles Livewire） |
| `Menu` | `Modules/Menu/` | 後台選單定義、選單權限設定（AdminMenu model, AdminMenuService, CheckAdminMenuPermission middleware） |
| `Settings` | `Modules/Settings/` | 外觀偏好設定（AppearanceSettings Livewire） |
| `Dashboard` | `Modules/Dashboard/` | 儀表板首頁（Dashboard Livewire） |

---

## 模組目錄結構

每個模組遵循以下標準結構：

```
Modules/
└── <ModuleName>/
    ├── App/
    │   ├── Http/
    │   │   ├── Controllers/
    │   │   └── Middleware/
    │   ├── Livewire/
    │   ├── Models/
    │   ├── Providers/
    │   │   └── <ModuleName>ServiceProvider.php
    │   └── Services/
    ├── Database/
    │   └── Seeders/
    ├── resources/
    │   └── views/
    │       └── livewire/
    ├── routes/
    │   └── web.php
    └── module.json
```

---

## 命名規範

| 項目 | 規範 | 範例 |
|------|------|------|
| 模組名稱 | PascalCase | `Account`、`Menu`、`Settings` |
| Namespace | `Modules\<ModuleName>\App\...` | `Modules\Account\App\Models\User` |
| 路由前綴 | 小寫加橫線 | `/admin/users`、`/admin/menu-permissions` |
| View namespace | 小寫模組名稱 | `account::livewire.users.index` |
| Livewire 元件 ID | `<module>::<name>` | `account::users.user-index` |

---

## ServiceProvider 職責

每個模組的 ServiceProvider 負責：
1. `loadViewsFrom()` — 註冊模組 view namespace
2. `loadRoutesFrom()` — 載入模組路由
3. `Livewire::component()` — 手動註冊 Livewire 元件

---

## 跨模組引用規則

- `Account` 模組的 `Role` model 引用 `Menu` 模組的 `AdminMenu`（via `adminMenuPermissions()`）
- `Menu` 模組的 `AdminMenuService` 與 `MenuPermissionIndex` 引用 `Account` 模組的 `User` 與 `Role`
- 跨模組引用使用完整 namespace，不使用 class alias
- 共用邏輯（基礎 Controller、核心 Middleware）保留在 `app/`

---

## 跨模組資料存取：Repository 模式

**原則：任何模組需要讀寫其他模組的資料，必須透過該模組暴露的 Repository interface，禁止直接 `use` 其他模組的 Model。**

### 目錄結構

每個模組在 `App/Repositories/` 下定義 interface 與實作：

```
Modules/<ModuleName>/App/Repositories/
├── Contracts/
│   └── <Entity>RepositoryInterface.php   ← 對外暴露的 contract
└── <Entity>Repository.php                ← 實作
```

### 綁定方式

在模組的 ServiceProvider `register()` 中綁定：

```php
$this->app->bind(
    \Modules\Account\App\Repositories\Contracts\UserRepositoryInterface::class,
    \Modules\Account\App\Repositories\UserRepository::class,
);
```

### 跨模組使用範例

```php
// 正確：Menu 模組透過 interface 取得 User 資料
use Modules\Account\App\Repositories\Contracts\UserRepositoryInterface;

class AdminMenuService
{
    public function __construct(private UserRepositoryInterface $users) {}
}

// 錯誤：直接引用其他模組的 Model
use Modules\Account\App\Models\User; // ← 禁止
```

### 規則摘要

| 情境 | 作法 |
|------|------|
| 同模組內部存取 | 直接使用 Model 或注入本模組 Repository |
| 跨模組讀取資料 | 注入目標模組的 `RepositoryInterface` |
| 跨模組寫入資料 | 同上，或透過 Event 驅動（見下節） |
| 絕對禁止 | 在模組 A 直接 `new`／`use` 模組 B 的 Model |

---

## 跨模組工作流程：Event / Listener 模式

**原則：當一個動作需要觸發多個模組協同完成時，由發起方 fire event，各模組自行監聽並處理，禁止跨模組直接呼叫 Service。**

### 目錄結構

```
Modules/<ModuleName>/App/
├── Events/
│   └── <SomethingHappened>.php
└── Listeners/
    └── <DoSomething>Listener.php
```

### 註冊方式

在模組的 ServiceProvider `boot()` 中使用 `Event::listen()`：

```php
use Illuminate\Support\Facades\Event;

Event::listen(
    \Modules\Account\App\Events\UserDeleted::class,
    \Modules\Menu\App\Listeners\RevokeUserMenuPermissionsListener::class,
);
```

### 範例：刪除帳號時清除選單權限

```
Account 模組                     Menu 模組
─────────────────                ─────────────────────────────
UserRepository::delete()
  → fire UserDeleted event   →   RevokeUserMenuPermissionsListener
                                   → MenuPermissionRepository::revokeAll($userId)
```

```php
// Modules/Account/App/Events/UserDeleted.php
class UserDeleted
{
    public function __construct(public readonly int $userId) {}
}

// Modules/Menu/App/Listeners/RevokeUserMenuPermissionsListener.php
class RevokeUserMenuPermissionsListener
{
    public function __construct(private MenuPermissionRepositoryInterface $permissions) {}

    public function handle(UserDeleted $event): void
    {
        $this->permissions->revokeAll($event->userId);
    }
}
```

### 規則摘要

| 情境 | 作法 |
|------|------|
| 單一模組內的流程 | 直接在 Service 內依序呼叫 |
| 多模組需同步完成 | Fire event；各模組 Listener 處理 |
| 多模組需非同步處理 | Fire event；Listener 實作 `ShouldQueue` |
| 絕對禁止 | 在模組 A 直接 `new`／call 模組 B 的 Service |

---

## 不遷移範圍

- `resources/views/components/` — 共用 Blade UI 元件，集中管理
- `database/migrations/` — 已執行的 migration 保留原位
- Laravel 核心 Middleware（Authenticate, VerifyCsrfToken 等）— 保留在 `app/Http/Middleware/`
- `app/Providers/` — 保留原有 Providers

---

## 模組路由必須明確加入 `web` middleware

`RouteServiceProvider` 的 `Route::middleware('web')` 只包裝 `routes/web.php`。模組路由由 `loadRoutesFrom()` 載入，**不繼承這個包裝**，若不加 `'web'`，`StartSession` 不執行，導致 `csrf_token()` 回傳空字串 → Livewire 419 錯誤。

```php
// guest 路由（Auth 模組）
Route::middleware('web')->prefix('admin')->name('admin.')->group(function () { ... });

// 需要認證的路由
Route::prefix('admin')->name('admin.')->middleware(['web', 'auth:admin', 'admin.menu'])->group(...)
```

---

## 新增模組標準流程

1. `php artisan module:make <ModuleName> --plain`
2. 立即建立 `Modules/<ModuleName>/App/Providers/<ModuleName>ServiceProvider.php`（否則下一條 artisan 指令會失敗）
3. 建立模組目錄結構（App/Livewire, resources/views, routes 等）
4. 在 ServiceProvider 中 register views、routes、Livewire components
5. 新增路由至模組的 `routes/web.php`，**記得加 `'web'` middleware**
