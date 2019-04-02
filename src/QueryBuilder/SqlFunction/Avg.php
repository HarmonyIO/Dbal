<?php declare(strict_types=1);

namespace HarmonyIO\Dbal\QueryBuilder\SqlFunction;

use HarmonyIO\Dbal\QueryBuilder\Column\Column;
use HarmonyIO\Dbal\QueryBuilder\QueryPart;
use HarmonyIO\Dbal\QueryBuilder\QuoteStyle;

final class Avg implements QueryPart, Column
{
    /** @var SqlFunction */
    private $function;

    public function __construct(QuoteStyle $quoteStyle, Column $column)
    {
        $this->function   = new SqlFunction($quoteStyle, 'avg', $column);
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

    public function toSqlWithOutAlias(): string
    {
        return $this->function->toSqlWithOutAlias();
    }

    public function toSql(): string
    {
        return $this->function->toSql();
    }
}
