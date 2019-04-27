<?php declare(strict_types=1);

namespace HarmonyIO\Dbal\QueryBuilder\Statement;

use HarmonyIO\Dbal\QueryBuilder\Condition\MatchingCondition;
use HarmonyIO\Dbal\QueryBuilder\QueryPart;

class Set implements QueryPart
{
    /** @var MatchingCondition */
    private $condition;

    public function __construct(MatchingCondition $matchingCondition)
    {
        $this->condition = $matchingCondition;
    }

    public function toSql(): string
    {
        return $this->condition->toSql();
    }

    /**
     * @return mixed[]
     */
    public function getParameters(): array
    {
        return $this->condition->getParameters();
    }
}
