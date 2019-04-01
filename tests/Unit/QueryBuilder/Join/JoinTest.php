<?php declare(strict_types=1);

namespace HarmonyIO\DbalTest\Unit\QueryBuilder\Join;

use HarmonyIO\Dbal\QueryBuilder\Column\Field;
use HarmonyIO\Dbal\QueryBuilder\Condition\MatchingCondition;
use HarmonyIO\Dbal\QueryBuilder\Identifier\Table;
use HarmonyIO\Dbal\QueryBuilder\Join\Join;
use HarmonyIO\Dbal\QueryBuilder\Join\JoinType;
use HarmonyIO\Dbal\QueryBuilder\QuoteStyle;
use HarmonyIO\PHPUnitExtension\TestCase;

class JoinTest extends TestCase
{
    public function testToSql(): void
    {
        $quoteStyle = new QuoteStyle(QuoteStyle::MYSQL);

        $condition = new MatchingCondition(
            new Field($quoteStyle, 'column', 'table2'),
            '=',
            new Field($quoteStyle, 'column', 'table1')
        );

        $join = new class(new JoinType(JoinType::INNER), new Table($quoteStyle, 'table2'), $condition) extends Join
        {
        };

        $this->assertSame('JOIN `table2` ON `table2`.`column` = `table1`.`column`', $join->toSql());
    }
}
