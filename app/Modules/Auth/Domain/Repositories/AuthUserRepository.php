<?php

namespace App\Modules\Auth\Domain\Repositories;

use App\Modules\Auth\Domain\Entities\AuthUser;
use App\Modules\Auth\Domain\ValueObjects\Email;

interface AuthUserRepository
{
    public function findByEmail(Email $email): ?AuthUser;
}
