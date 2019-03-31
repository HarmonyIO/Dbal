<?php declare(strict_types=1);

namespace HarmonyIO\Dbal\QueryBuilder\Identifier;

use HarmonyIO\Dbal\QueryBuilder\QueryPart;
use HarmonyIO\Dbal\QueryBuilder\QuoteStyle;

final class Table implements QueryPart
{
    /** @var QuoteStyle */
    private $quoteStyle;

    /** @var string */
    private $name;

    /** @var string|null */
    private $alias;

    public function __construct(QuoteStyle $quoteStyle, string $name, ?string $alias = null)
    {
        $this->quoteStyle = $quoteStyle;
        $this->name       = $name;
        $this->alias      = $alias;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function toSql(): string
    {
        $alias = '';

        if ($this->alias !== null) {
            $alias = ' AS ' . $this->quoteStyle->getValue() . $this->alias . $this->quoteStyle->getValue();
        }

        return $this->quoteStyle->getValue() . $this->name . $this->quoteStyle->getValue() . $alias;
    }
}
