<?php declare(strict_types=1);

namespace HarmonyIO\Dbal\QueryBuilder\Statement;

use HarmonyIO\Dbal\QueryBuilder\Column\Column;
use HarmonyIO\Dbal\QueryBuilder\Column\Field;

class Value
{
    /** @var Field */
    private $field;

    /** @var Column */
    private $value;

    public function __construct(Field $field, Column $value)
    {
        $this->field = $field;
        $this->value = $value;
    }

    public function getFieldSql(): string
    {
        return $this->field->toSql();
    }

    public function getValueSql(): string
    {
        if ($this->value instanceof Field) {
            return $this->value->toSql();
        }

        return '?';
    }

    /**
     * @return int|string|mixed[]|null
     */
    public function getValue()
    {
        return $this->value->getValue();
    }
}
