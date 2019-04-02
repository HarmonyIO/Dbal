<?php declare(strict_types=1);

namespace HarmonyIO\Dbal\QueryBuilder\SqlFunction;

use HarmonyIO\Dbal\QueryBuilder\Column\Column;
use HarmonyIO\Dbal\QueryBuilder\QueryPart;
use HarmonyIO\Dbal\QueryBuilder\QuoteStyle;

final class SqlFunction implements QueryPart, Column
{
    /** @var QuoteStyle */
    private $quoteStyle;

    /** @var string */
    private $function;

    /** @var Column */
    private $column;

    public function __construct(QuoteStyle $quoteStyle, string $function, Column $column)
    {
        $this->quoteStyle = $quoteStyle;
        $this->function   = strtoupper($function);
        $this->column     = $column;
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

    public function toSqlWithOutAlias(): string
    {
        return $this->function . '(' . $this->column->toSqlWithOutAlias() . ')';
    }

    public function toSql(): string
    {
        $sql = $this->toSqlWithOutAlias();

        if ($this->column->getAlias() !== null) {
            $sql .= ' AS ' . $this->quoteStyle->getValue() . $this->column->getAlias() . $this->quoteStyle->getValue();
        }

        return $sql;
    }
}
