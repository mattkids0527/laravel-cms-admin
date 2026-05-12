<?php

namespace Modules\Menu\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Account\App\Models\Role;

class AdminMenu extends Model
{
    protected $fillable = [
        'parent_id',
        'name',
        'route_name',
        'icon',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(AdminMenu::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(AdminMenu::class, 'parent_id')->orderBy('sort_order');
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_admin_menu_permissions');
    }

    public function isGroup(): bool
    {
        return is_null($this->route_name);
    }
}
