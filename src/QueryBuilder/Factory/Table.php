<?php declare(strict_types=1);

namespace HarmonyIO\Dbal\QueryBuilder\Factory;

use HarmonyIO\Dbal\Exception\InvalidTableDefinition;
use HarmonyIO\Dbal\QueryBuilder\Identifier\Table as TableObject;
use HarmonyIO\Dbal\QueryBuilder\QuoteStyle;

class Table
{
    /** @var QuoteStyle */
    private $quoteStyle;

    public function __construct(QuoteStyle $quoteStyle)
    {
        $this->quoteStyle = $quoteStyle;
    }

    public function buildFromString(string $string): TableObject
    {
        $pattern = '~^(?P<table>[^\s]+)(?:\s+as\s+(?P<alias>[^\s]+))?$~i';

        if (preg_match($pattern, $string, $tableParts) !== 1) {
            throw new InvalidTableDefinition($string);
        }

        return new TableObject($this->quoteStyle, $tableParts['table'], $tableParts['alias'] ?? null);
    }
}
