<?php declare(strict_types=1);

namespace HarmonyIO\Dbal\QueryBuilder\Factory;

use HarmonyIO\Dbal\Exception\InvalidFieldDefinition;
use HarmonyIO\Dbal\Exception\UnsupportedFunction;
use HarmonyIO\Dbal\QueryBuilder\Column\Column;
use HarmonyIO\Dbal\QueryBuilder\Column\Field as FieldObject;
use HarmonyIO\Dbal\QueryBuilder\QuoteStyle;

class Field
{
    /** @var QuoteStyle */
    private $quoteStyle;

    public function __construct(QuoteStyle $quoteStyle)
    {
        $this->quoteStyle = $quoteStyle;
    }

    public function buildFromString(string $string): Column
    {
        $pattern = '~^(?:(?:(?P<function>[^\s]+)\s*\(\s*(?:(?P<table1>[^\s\(\)]+)(?:\.))?(?P<column1>[^\s\(\)]+)\s*\))|(?:(?P<table2>[^\s\(\)]+)(?:\.))?(?P<column2>[^\s\(\)]+))(?:\s+as\s+(?P<alias>.+))?$~ix';

        if (preg_match($pattern, $string, $fieldParts) !== 1) {
            throw new InvalidFieldDefinition($string);
        }

        if ($fieldParts['function']) {
            return $this->buildFromStringWithFunction($fieldParts);
        }

        return $this->buildField($fieldParts['column2'], $fieldParts['table2'], $fieldParts['alias'] ?? null);
    }

    /**
     * @param mixed[] $fieldData
     */
    private function buildFromStringWithFunction(array $fieldData): Column
    {
        $function = 'HarmonyIO\Dbal\QueryBuilder\SqlFunction\\' . ucfirst(strtolower($fieldData['function']));

        if (!class_exists($function)) {
            throw new UnsupportedFunction($fieldData['function']);
        }

        return new $function(
            $this->quoteStyle,
            $this->buildField($fieldData['column1'], $fieldData['table1'], $fieldData['alias'] ?? null)
        );
    }

    private function buildField(string $name, ?string $table = null, ?string $alias = null): FieldObject
    {
        if ($table === '') {
            $table = null;
        }

        return new FieldObject($this->quoteStyle, $name, $table, $alias);
    }
}
