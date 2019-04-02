<?php declare(strict_types=1);

namespace HarmonyIO\Dbal\QueryBuilder\Column;

use HarmonyIO\Dbal\QueryBuilder\QueryPart;

interface Column extends QueryPart
{
    public function getTable(): ?string;

    public function getName(): string;

    public function getAlias(): ?string;

    public function toSqlWithoutAlias(): string;
}
