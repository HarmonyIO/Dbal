<?php declare(strict_types=1);

namespace HarmonyIO\Dbal\QueryBuilder\Condition;

use HarmonyIO\Dbal\QueryBuilder\Column\Field;

final class AndCondition implements Condition
{
    /** @var Condition[] */
    private $conditions;

    public function __construct(Condition ...$conditions)
    {
        $this->conditions = $conditions;
    }

    public function toSql(): string
    {
        $conditionsSql = array_reduce($this->conditions, static function (array $conditionsSql, Condition $condition) {
            $conditionsSql[] = $condition->toSql();

            return $conditionsSql;
        }, []);

        return '(' . implode(' AND ', $conditionsSql) . ')';
    }

    /**
     * @return mixed[]
     */
    public function getParameters(): array
    {
        $parameters = [];

        foreach ($this->conditions as $condition) {
            $parameters = array_merge($parameters, $condition->getParameters());
        }

        return $parameters;
    }
}
