<?php

namespace App\Modules\Auth\Domain\Repositories;

use App\Modules\Auth\Domain\Entities\User;

interface UserRepositoryInterface
{
    public function findByEmail(string $email): ?User;
    public function save(User $user): User;
}
