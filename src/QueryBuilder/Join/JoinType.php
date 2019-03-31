<?php declare(strict_types=1);

namespace HarmonyIO\Dbal\QueryBuilder\Join;

use HarmonyIO\Dbal\Enum\Enum;

class JoinType extends Enum
{
    public const INNER = 'JOIN';
    public const LEFT  = 'LEFT JOIN';
    public const RIGHT = 'RIGHT JOIN';
}
