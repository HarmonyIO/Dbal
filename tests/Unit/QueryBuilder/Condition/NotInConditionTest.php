<?php declare(strict_types=1);

namespace HarmonyIO\DbalTest\Unit\QueryBuilder\Condition;

use HarmonyIO\Dbal\QueryBuilder\Column\Field;
use HarmonyIO\Dbal\QueryBuilder\Column\Value;
use HarmonyIO\Dbal\QueryBuilder\Condition\NotInCondition;
use HarmonyIO\Dbal\QueryBuilder\QuoteStyle;
use HarmonyIO\PHPUnitExtension\TestCase;

class NotInConditionTest extends TestCase
{
    /** @var QuoteStyle */
    private $quoteStyle;

    //phpcs:ignore SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingReturnTypeHint
    public function setUp()
    {
        $this->quoteStyle = new QuoteStyle(QuoteStyle::MYSQL);
    }

    public function testToSqlWithValueAsColumn(): void
    {
        $condition = new NotInCondition(new Value($this->quoteStyle, 3), [1, 2, 3]);

        $this->assertSame('? NOT IN (?, ?, ?)', $condition->toSql());
    }

    public function testToSqlWithFieldAsColumn(): void
    {
        $condition = new NotInCondition(new Field($this->quoteStyle, 'column', 'table'), [1, 2, 3]);

        $this->assertSame('`table`.`column` NOT IN (?, ?, ?)', $condition->toSql());
    }
}
