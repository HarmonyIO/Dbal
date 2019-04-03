<?php declare(strict_types=1);

namespace HarmonyIO\Dbal\QueryBuilder\Condition;

use HarmonyIO\Dbal\QueryBuilder\Factory\Condition as ConditionFactory;

class Collection
{
    /** @var Condition[] */
    private $conditions;

    /** @var ConditionFactory */
    private $conditionFactory;

    public function __construct(ConditionFactory $conditionFactory)
    {
        $this->conditionFactory = $conditionFactory;
    }

    /**
     * @param string|int|null $parameter
     */
    public function where(string $condition, $parameter = null): void
    {
        $this->conditions[] = $this->conditionFactory->buildFromString($condition, $parameter);
    }

    public function andWhere(callable $conditionAdder): void
    {
        $conditionCollection = new Collection($this->conditionFactory);

        $conditionAdder($conditionCollection);

        $this->conditions[] = new AndCondition(...$conditionCollection->getConditions());
    }

    public function orWhere(callable $conditionAdder): void
    {
        $conditionCollection = new Collection($this->conditionFactory);

        $conditionAdder($conditionCollection);

        $this->conditions[] = new OrCondition(...$conditionCollection->getConditions());
    }

    /**
     * @return Condition[]
     */
    public function getConditions(): array
    {
        return $this->conditions;
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
