<?php

namespace App\Modules\Cart\Infrastructure\Database\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CartEloquent extends Model
{
    protected $table = 'carts';

    protected $fillable = [
        'user_id',
        'session_id',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(CartItemEloquent::class, 'cart_id');
    }
}
