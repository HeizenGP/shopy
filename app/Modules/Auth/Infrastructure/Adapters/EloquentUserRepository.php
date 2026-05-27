<?php

namespace App\Modules\Auth\Infrastructure\Adapters;

use App\Modules\Auth\Domain\Entities\User;
use App\Modules\Auth\Domain\Repositories\UserRepositoryInterface;
use App\Modules\Auth\Infrastructure\Database\Models\UserEloquent;

class EloquentUserRepository implements UserRepositoryInterface
{
    public function findByEmail(string $email): ?User
    {
        $eloquent = UserEloquent::where('email', $email)->first();
        if (!$eloquent) {
            return null;
        }

        return $this->toDomain($eloquent);
    }

    public function save(User $user): User
    {
        $eloquent = UserEloquent::updateOrCreate(
            ['email' => $user->email],
            [
                'name' => $user->name,
                'password' => $user->password,
            ]
        );

        return $this->toDomain($eloquent);
    }

    private function toDomain(UserEloquent $eloquent): User
    {
        return new User(
            id: $eloquent->id,
            name: $eloquent->name,
            email: $eloquent->email,
            password: $eloquent->password,
            emailVerifiedAt: $eloquent->email_verified_at?->toIso8601String()
        );
    }
}
