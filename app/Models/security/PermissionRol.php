<?php

namespace App\models\security;

use Illuminate\Database\Eloquent\Model;
use App\Models\security\Permissions;
use App\Models\security\Roles;

class PermissionRol extends Model
{
    
    protected $table = 'permissions_role';
    public $timestamps = false;
    protected $fillable = ['permissions_id', 'role_id'];

    public function permission()
    {
        return $this->belongsToMany(Permission::class,'id', 'permission_id' );
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class,'id', 'role_id' );
    }
}
