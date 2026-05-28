<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'permissions';
    protected $fillable = ['key', 'name', 'group_key', 'description'];
    public $timestamps = true;
}
