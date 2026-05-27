<?php

namespace App\Models;

use App\Modules\Auth\Infrastructure\Database\Models\UserEloquent;

class User extends UserEloquent
{
    // Extends UserEloquent to integrate with the Auth module
}
