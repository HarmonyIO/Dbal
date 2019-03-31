<?php declare(strict_types=1);

namespace HarmonyIO\Dbal\QueryBuilder\Order;

use HarmonyIO\Dbal\Enum\Enum;

final class Direction extends Enum
{
    public const ASC  = 'ASC';
    public const DESC = 'DESC';
}
