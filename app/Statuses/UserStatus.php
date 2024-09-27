<?php

namespace App\Statuses;

class UserStatus
{
    public const USER = 1;

    public const ADMIN = 2;

    public static array $statuses = [self::ADMIN, self::User];
}
