<?php declare(strict_types=1);

namespace HarmonyIO\DbalTest\Unit\QueryBuilder\Statement;

use HarmonyIO\Dbal\QueryBuilder\Column\Field;
use HarmonyIO\Dbal\QueryBuilder\Column\Value;
use HarmonyIO\Dbal\QueryBuilder\Condition\MatchingCondition;
use HarmonyIO\Dbal\QueryBuilder\QuoteStyle;
use HarmonyIO\Dbal\QueryBuilder\Statement\Set;
use HarmonyIO\PHPUnitExtension\TestCase;

class SetTest extends TestCase
{
    public function testToSql(): void
    {
        $condition = new MatchingCondition(
            new Field(new QuoteStyle(QuoteStyle::MYSQL), 'column1'),
            '=',
            new Value(new QuoteStyle(QuoteStyle::MYSQL), 1)
        );

        $this->assertSame('`column1` = ?', (new Set($condition))->toSql());
    }
}
