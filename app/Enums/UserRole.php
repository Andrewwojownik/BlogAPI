<?php
declare(strict_types=1);

namespace App\Enums;

enum UserRole: int
{
    case ADMINISTRATOR = 1;
    case EDITOR = 2;
    case USER = 3;
}
