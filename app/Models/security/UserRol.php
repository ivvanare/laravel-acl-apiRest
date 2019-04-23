<?php

namespace App\models\security;

use Illuminate\Database\Eloquent\Model;
use App\Models\security\Role;
use App\User;

class UserRol extends Model
{
    protected $table = 'users_role';
    public $timestamp = true;
    protected $fillable = ['user_id', 'role_id'];

    public function users()
    {
        return $this->belongsToMany(User::class,'id', 'user_id' );
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class,'id', 'role_id' );
    }
}
