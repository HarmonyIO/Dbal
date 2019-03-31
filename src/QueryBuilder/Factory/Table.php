<?php declare(strict_types=1);

namespace HarmonyIO\Dbal\QueryBuilder\Factory;

use HarmonyIO\Dbal\Exception\InvalidFieldDefinition;
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
        $pattern = '~^(?P<table>.+)(?:\s+as\s+(?P<alias>.+))?$~i';

        if (preg_match($pattern, $string, $tableParts) !== 1) {
            throw new InvalidFieldDefinition($string);
        }

        return new TableObject($this->quoteStyle, $tableParts['table'], $fieldParts['alias'] ?? null);
    }
}
