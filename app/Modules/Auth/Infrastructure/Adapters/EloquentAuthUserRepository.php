<?php

namespace App\Modules\Auth\Infrastructure\Adapters;

use App\Models\User as EloquentUser;
use App\Modules\Auth\Domain\Entities\AuthUser;
use App\Modules\Auth\Domain\Repositories\AuthUserRepository;
use App\Modules\Auth\Domain\ValueObjects\Email;

class EloquentAuthUserRepository implements AuthUserRepository
{
    public function findByEmail(Email $email): ?AuthUser
    {
        $eloquentUser = EloquentUser::where('email', $email->getValue())->first();

        if (!$eloquentUser) {
            return null;
        }

        return new AuthUser(
            id: $eloquentUser->id,
            name: $eloquentUser->name,
            email: new Email($eloquentUser->email),
            passwordHash: $eloquentUser->password
        );
    }
}
