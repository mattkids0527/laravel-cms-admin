<?php

namespace Modules\Account\App\Models;

use Modules\Menu\App\Models\AdminMenu;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'is_protected',
    ];

    protected $casts = [
        'is_protected' => 'boolean',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function adminMenuPermissions(): BelongsToMany
    {
        return $this->belongsToMany(AdminMenu::class, 'role_admin_menu_permissions');
    }

    public function isDeletable(): bool
    {
        return ! $this->is_protected && $this->users()->doesntExist();
    }
}
