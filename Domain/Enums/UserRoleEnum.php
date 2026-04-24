<?php

enum UserRoleEnum: string
{
    case ADMIN = 'ADMIN';
    case MEMBER = 'MEMBER';
    case REVIEWER = 'REVIEWER';

    public static function default(): self
    {
        return self::MEMBER;
    }
}
