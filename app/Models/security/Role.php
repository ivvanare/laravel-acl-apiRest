<?php

namespace App\models\security;

use Illuminate\Database\Eloquent\Model;
use App\Models\security\Permissions;
use App\User;


class Role extends Model
{
    protected $table = 'roles';
    protected $timestamp = true;
    protected $fillable = ['name', 'all_permisions', 'sort'];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_role')->withPivot('permission_id');
    }

    public function users()
    {
        return $this->belongsToMany(user::class, 'assigned_roles')->withPivot('user_id');
    }
}
