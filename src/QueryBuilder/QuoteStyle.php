<?php declare(strict_types=1);

namespace HarmonyIO\Dbal\QueryBuilder;

use HarmonyIO\Dbal\Enum\Enum;

class QuoteStyle extends Enum
{
    public const POSTGRESQL = '"';
    public const MYSQL      = '`';
}
