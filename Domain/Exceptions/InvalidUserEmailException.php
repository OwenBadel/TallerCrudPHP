<?php

final class InvalidUserEmailException extends InvalidArgumentException
{
    public static function becauseFormatIsInvalid(string $email): self
    {
        return new self(sprintf('El email "%s" no tiene un formato valido.', $email));
    }

    public static function becauseEmailIsEmpty(): self
    {
        return new self('El email no puede estar vacio.');
    }
}
