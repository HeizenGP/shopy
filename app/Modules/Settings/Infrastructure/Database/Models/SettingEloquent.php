<?php

namespace App\Modules\Settings\Infrastructure\Database\Models;

use Illuminate\Database\Eloquent\Model;

class SettingEloquent extends Model
{
    protected $table = 'settings';

    protected $fillable = [
        'key',
        'value',
        'description',
    ];
}
