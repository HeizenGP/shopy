<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    protected $table = 'roles';
    protected $fillable = ['key', 'name', 'description'];
    public $timestamps = true;

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_permission')->withTimestamps();
    }
}

