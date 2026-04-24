<?php

enum UserRoleEnum: string
{
    case ADMIN = 'ADMIN';
    case USER = 'USER';

    public static function default(): self
    {
        return self::USER;
    }
}
