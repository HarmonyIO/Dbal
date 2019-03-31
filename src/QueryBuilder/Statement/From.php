<?php declare(strict_types=1);

namespace HarmonyIO\Dbal\QueryBuilder\Statement;

use HarmonyIO\Dbal\QueryBuilder\Identifier\Table;
use HarmonyIO\Dbal\QueryBuilder\QueryPart;

final class From implements QueryPart
{
    /** @var Table */
    private $table;

    public function __construct(Table $table)
    {
        $this->table = $table;
    }

    public function toSql(): string
    {
        return 'FROM ' . $this->table->toSql();
    }
}
