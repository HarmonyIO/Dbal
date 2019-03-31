<?php declare(strict_types=1);

namespace HarmonyIO\Dbal\QueryBuilder\Condition;

use HarmonyIO\Dbal\QueryBuilder\Column\Column;

final class NotNullCondition implements Condition
{
    /** @var Column */
    private $field;

    public function __construct(Column $field)
    {
        $this->field = $field;
    }

    public function toSql(): string
    {
        return $this->field->toSql() . ' IS NOT NULL';
    }
}
