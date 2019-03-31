<?php declare(strict_types=1);

namespace HarmonyIO\Dbal\QueryBuilder\Order;

use HarmonyIO\Dbal\QueryBuilder\Column\Column;
use HarmonyIO\Dbal\QueryBuilder\QueryPart;

final class Order implements QueryPart
{
    /** @var Column */
    private $field;

    /** @var Direction */
    private $direction;

    public function __construct(Column $field, Direction $direction)
    {
        $this->field      = $field;
        $this->direction  = $direction;
    }

    public function toSql(): string
    {
        return $this->field->toSql() . ' ' . $this->direction->getValue();
    }
}
