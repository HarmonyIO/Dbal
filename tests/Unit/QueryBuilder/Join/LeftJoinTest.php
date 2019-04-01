<?php declare(strict_types=1);

namespace HarmonyIO\DbalTest\Unit\QueryBuilder\Join;

use HarmonyIO\Dbal\QueryBuilder\Column\Field;
use HarmonyIO\Dbal\QueryBuilder\Condition\MatchingCondition;
use HarmonyIO\Dbal\QueryBuilder\Identifier\Table;
use HarmonyIO\Dbal\QueryBuilder\Join\LeftJoin;
use HarmonyIO\Dbal\QueryBuilder\QuoteStyle;
use HarmonyIO\PHPUnitExtension\TestCase;

class LeftJoinTest extends TestCase
{
    public function testToSql(): void
    {
        $quoteStyle = new QuoteStyle(QuoteStyle::MYSQL);

        $condition = new MatchingCondition(
            new Field($quoteStyle, 'column', 'table2'),
            '=',
            new Field($quoteStyle, 'column', 'table1')
        );

        $join = new LeftJoin(new Table($quoteStyle, 'table2'), $condition);

        $this->assertSame('LEFT JOIN `table2` ON `table2`.`column` = `table1`.`column`', $join->toSql());
    }
}
