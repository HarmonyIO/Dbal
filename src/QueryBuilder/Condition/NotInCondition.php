<?php declare(strict_types=1);

namespace HarmonyIO\Dbal\QueryBuilder\Condition;

use HarmonyIO\Dbal\QueryBuilder\Column\Column;

final class NotInCondition implements Condition
{
    /** @var Column */
    private $field;

    /** @var string[]|int[] */
    private $set;

    /**
     * @param string[]|int[] $set
     */
    public function __construct(Column $field, array $set)
    {
        $this->field = $field;
        $this->set   = $set;
    }

    public function toSql(): string
    {
        return $this->field->toSql() . ' NOT IN ' . $this->setToSql();
    }

    private function setToSql(): string
    {
        $placeHolders = array_fill(0, count($this->set), '?');

        return sprintf('(%s)', implode(', ', $placeHolders));
    }
}
