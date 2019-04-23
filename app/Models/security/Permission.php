<?php

namespace App\models\security;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'permissions';

    public $timestamps = true;
    
    protected $fillable = ['name', 'display_name', 'sort', 'module'];
}
