<?php

final class InvalidCredentialsException extends DomainException
{
    public static function becauseCredentialsAreInvalid(): self
    {
        return new self('Las credenciales proporcionadas son invalidas.');
    }

    public static function becauseUserIsInactive(): self
    {
        return new self('El usuario no se encuentra activo.');
    }
}
