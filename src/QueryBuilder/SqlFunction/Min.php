<?php declare(strict_types=1);

namespace HarmonyIO\Dbal\QueryBuilder\SqlFunction;

use HarmonyIO\Dbal\QueryBuilder\Column\Column;
use HarmonyIO\Dbal\QueryBuilder\QueryPart;

final class Min implements QueryPart, Column
{
    /** @var SqlFunction */
    private $function;

    public function __construct(Column $column)
    {
        $this->function = new SqlFunction('min', $column);
    }

    public function getTable(): ?string
    {
        return $this->function->getTable();
    }

    public function getName(): string
    {
        return $this->function->getName();
    }

    public function getAlias(): ?string
    {
        return $this->function->getAlias();
    }

    public function toSqlWitOutAlias(): string
    {
        return $this->function->toSqlWitOutAlias();
    }

    public function toSql(): string
    {
        return $this->function->toSql();
    }
}
