<?php declare(strict_types=1);

namespace HarmonyIO\Dbal\QueryBuilder\Join;

use HarmonyIO\Dbal\QueryBuilder\Condition\Condition;
use HarmonyIO\Dbal\QueryBuilder\Identifier\Table;

final class LeftJoin extends Join
{
    public function __construct(Table $joinedTable, Condition $condition)
    {
        parent::__construct(new JoinType(JoinType::LEFT), $joinedTable, $condition);
    }
}
