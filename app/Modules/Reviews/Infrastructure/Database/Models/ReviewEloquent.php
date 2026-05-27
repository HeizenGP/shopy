<?php

namespace App\Modules\Reviews\Infrastructure\Database\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReviewEloquent extends Model
{
    protected $table = 'reviews';

    protected $fillable = [
        'product_id',
        'user_id',
        'name',
        'rating',
        'comment',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Auth\Infrastructure\Database\Models\UserEloquent::class, 'user_id');
    }
}
