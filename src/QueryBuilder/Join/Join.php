<?php declare(strict_types=1);

namespace HarmonyIO\Dbal\QueryBuilder\Join;

use HarmonyIO\Dbal\QueryBuilder\Column\Column;
use HarmonyIO\Dbal\QueryBuilder\Condition\Condition;
use HarmonyIO\Dbal\QueryBuilder\Identifier\Table;
use HarmonyIO\Dbal\QueryBuilder\QueryPart;

abstract class Join implements QueryPart
{
    /** @var JoinType */
    private $type;

    /** @var Table */
    private $joinedTable;

    /** @var Column */
    private $condition;

    public function __construct(JoinType $type, Table $joinedTable, Condition $condition)
    {
        $this->type        = $type;
        $this->joinedTable = $joinedTable;
        $this->condition   = $condition;
    }

    public function toSql(): string
    {
        return $this->type->getValue() . ' ' . $this->joinedTable->toSql() . ' ON ' . $this->condition->toSql();
    }
}
