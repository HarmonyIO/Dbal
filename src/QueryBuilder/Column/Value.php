<?php declare(strict_types=1);

namespace HarmonyIO\Dbal\QueryBuilder\Column;

use HarmonyIO\Dbal\QueryBuilder\QuoteStyle;

class Value implements Column
{
    /** @var QuoteStyle */
    private $quoteStyle;

    /** @var int|string */
    private $value;

    /** @var string|null */
    private $alias;

    /**
     * @param int|string|mixed[] $value
     */
    public function __construct(QuoteStyle $quoteStyle, $value, ?string $alias = null)
    {
        $this->quoteStyle = $quoteStyle;
        $this->value      = $value;
        $this->alias      = $alias;
    }

    public function getTable(): ?string
    {
        return null;
    }

    public function getName(): string
    {
        return $this->value;
    }

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function toSqlWithoutAlias(): string
    {
        $value = '?';

        if (is_array($this->value)) {
            $value = implode(', ', array_fill(0, count($this->value), '?'));
        }

        return $value;
    }

    public function toSql(): string
    {
        $value = $this->toSqlWithoutAlias();

        $alias = '';

        if ($this->alias !== null) {
            $alias = ' AS ' . $this->quoteStyle->getValue() . $this->alias . $this->quoteStyle->getValue();
        }

        return $value . $alias;
    }
}
