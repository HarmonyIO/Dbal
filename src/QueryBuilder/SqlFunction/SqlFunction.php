<?php declare(strict_types=1);

namespace HarmonyIO\Dbal\QueryBuilder\SqlFunction;

use HarmonyIO\Dbal\QueryBuilder\Column\Column;
use HarmonyIO\Dbal\QueryBuilder\QueryPart;

final class SqlFunction implements QueryPart, Column
{
    /** @var string */
    private $function;

    /** @var Column */
    private $column;

    public function __construct(string $function, Column $column)
    {
        $this->function = strtoupper($function);
        $this->column   = $column;
    }

    public function getTable(): ?string
    {
        return $this->column->getTable();
    }

    public function getName(): string
    {
        return $this->column->getName();
    }

    public function getAlias(): ?string
    {
        return $this->column->getAlias();
    }

    public function toSqlWitOutAlias(): string
    {
        return $this->function . '(' . $this->column->toSqlWitOutAlias() . ')';
    }

    public function toSql(): string
    {
        $sql = $this->toSqlWitOutAlias();

        if ($this->column->getAlias() !== null) {
            $sql .= ' AS ' . $this->column->getAlias();
        }

        return $sql;
    }
}
