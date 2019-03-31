<?php declare(strict_types=1);

namespace HarmonyIO\DbalTest\Fixtures\Enum;

use HarmonyIO\Dbal\Enum\Enum;

class EnumWithFoo extends Enum
{
    public const FOO = 'bar';
}
