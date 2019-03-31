<?php declare(strict_types=1);

namespace HarmonyIO\Dbal\QueryBuilder;

interface QueryPart
{
    public function toSql(): string;
}
