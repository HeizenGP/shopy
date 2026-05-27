<?php

namespace App\Modules\Auth\Domain\Exceptions;

use DomainException;

class InvalidCredentialsException extends DomainException
{
    public function __construct(string $message = "Credenciales de inicio de sesión inválidas.", int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
