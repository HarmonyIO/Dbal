<?php declare(strict_types=1);

namespace HarmonyIO\Dbal\QueryBuilder\Column;

use HarmonyIO\Dbal\QueryBuilder\QuoteStyle;

final class Field implements Column
{
    /** @var QuoteStyle */
    private $quoteStyle;

    /** @var string|null */
    private $table;

    /** @var string */
    private $name;

    /** @var string|null */
    private $alias;

    public function __construct(QuoteStyle $quoteStyle, string $name, ?string $table = null, ?string $alias = null)
    {
        $this->quoteStyle = $quoteStyle;
        $this->table      = $table;
        $this->name       = $name;
        $this->alias      = $alias;
    }

    public function getTable(): ?string
    {
        return $this->table;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function toSqlWithoutAlias(): string
    {
        $sql = '';

        if ($this->table !== null) {
            $sql .= $this->quoteStyle->getValue() . $this->table . $this->quoteStyle->getValue() . '.';
        }

        $sql .= $this->quoteStyle->getValue() . $this->name . $this->quoteStyle->getValue();

        return $sql;
    }

    public function toSql(): string
    {
        $sql = $this->toSqlWithoutAlias();

        if ($this->alias !== null) {
            $sql .= ' AS ' . $this->quoteStyle->getValue() . $this->alias . $this->quoteStyle->getValue();
        }

        return $sql;
    }
}
