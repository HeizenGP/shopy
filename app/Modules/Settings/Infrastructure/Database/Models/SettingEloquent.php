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

    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        static::saved(function ($setting) {
            \App\Helpers\ShopHelper::clearCache();
        });

        static::deleted(function ($setting) {
            \App\Helpers\ShopHelper::clearCache();
        });
    }
}
