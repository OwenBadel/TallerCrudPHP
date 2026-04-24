<?php

final class UserNotFoundException extends RuntimeException
{
    public static function byId(string $id): self
    {
        return new self(sprintf('No se encontro un usuario con id "%s".', $id));
    }

    public static function byEmail(string $email): self
    {
        return new self(sprintf('No se encontro un usuario con email "%s".', $email));
    }
}
