<?php declare(strict_types=1);

namespace HarmonyIO\Dbal\QueryBuilder\Condition;

use HarmonyIO\Dbal\QueryBuilder\Column\Column;
use HarmonyIO\Dbal\QueryBuilder\Column\Field;
use HarmonyIO\Dbal\QueryBuilder\Column\Value;

final class MatchingCondition implements Condition
{
    /** @var Column */
    private $leftValue;

    /** @var string */
    private $type;

    /** @var Column|Value */
    private $rightValue;

    /**
     * @param Column|Value $rightValue
     */
    public function __construct(Column $leftValue, string $type, $rightValue)
    {
        $this->leftValue  = $leftValue;
        $this->type       = $type;
        $this->rightValue = $rightValue;
    }

    public function toSql(): string
    {
        return $this->valueToSql($this->leftValue) . ' ' . $this->type . ' ' . $this->valueToSql($this->rightValue);
    }

    /**
     * @param Column|int|string $value
     */
    private function valueToSql($value): string
    {
        if ($value instanceof Column) {
            return $value->toSql();
        }

        return '?';
    }

    /**
     * @return mixed[]
     */
    public function getParameters(): array
    {
        if ($this->rightValue instanceof Field) {
            return [];
        }

        return [
            $this->rightValue->getValue(),
        ];
    }
}
