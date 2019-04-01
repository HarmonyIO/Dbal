<?php declare(strict_types=1);

namespace HarmonyIO\DbalTest\Unit\QueryBuilder\Condition;

use HarmonyIO\Dbal\QueryBuilder\Column\Field;
use HarmonyIO\Dbal\QueryBuilder\Column\Value;
use HarmonyIO\Dbal\QueryBuilder\Condition\InCondition;
use HarmonyIO\Dbal\QueryBuilder\QuoteStyle;
use HarmonyIO\PHPUnitExtension\TestCase;

class InConditionTest extends TestCase
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
        $condition = new InCondition(new Value($this->quoteStyle, 3), [1, 2, 3]);

        $this->assertSame('? IN (?, ?, ?)', $condition->toSql());
    }

    public function testToSqlWithFieldAsColumn(): void
    {
        $condition = new InCondition(new Field($this->quoteStyle, 'column', 'table'), [1, 2, 3]);

        $this->assertSame('`table`.`column` IN (?, ?, ?)', $condition->toSql());
    }
}
