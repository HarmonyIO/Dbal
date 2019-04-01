<?php declare(strict_types=1);

namespace HarmonyIO\DbalTest\Unit\QueryBuilder\Condition;

use HarmonyIO\Dbal\QueryBuilder\Column\Field;
use HarmonyIO\Dbal\QueryBuilder\Column\Value;
use HarmonyIO\Dbal\QueryBuilder\Condition\MatchingCondition;
use HarmonyIO\Dbal\QueryBuilder\QuoteStyle;
use HarmonyIO\PHPUnitExtension\TestCase;

class MatchingConditionTest extends TestCase
{
    /** @var QuoteStyle */
    private $quoteStyle;

    //phpcs:ignore SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingReturnTypeHint
    public function setUp()
    {
        $this->quoteStyle = new QuoteStyle(QuoteStyle::MYSQL);
    }

    public function testToSqlWithValuesOnBothLeftAndRightSide(): void
    {
        $this->assertSame(
            '? = ?',
            (new MatchingCondition(new Value($this->quoteStyle, 1), '=', new Value($this->quoteStyle, 1)))->toSql()
        );
    }

    public function testToSqlWithValueOnLeftSide(): void
    {
        $condition = (new MatchingCondition(
            new Field($this->quoteStyle, 'column'),
            '=',
            new Value($this->quoteStyle, 1)
        ));

        $this->assertSame(
            '`column` = ?',
            $condition->toSql()
        );
    }

    public function testToSqlWithValueOnRightSide(): void
    {
        $condition = (new MatchingCondition(
            new Value($this->quoteStyle, 1),
            '=',
            new Field($this->quoteStyle, 'column')
        ));

        $this->assertSame(
            '? = `column`',
            $condition->toSql()
        );
    }

    public function testToSqlWithLiteralValueOnRightSide(): void
    {
        $condition = (new MatchingCondition(
            new Field($this->quoteStyle, 'column'),
            '=',
            1
        ));

        $this->assertSame(
            '`column` = ?',
            $condition->toSql()
        );
    }

    public function testToSqlWithFieldsOnBothLeftAndRightSide(): void
    {
        $condition = (new MatchingCondition(
            new Field($this->quoteStyle, 'column1'),
            '=',
            new Field($this->quoteStyle, 'column2')
        ));

        $this->assertSame(
            '`column1` = `column2`',
            $condition->toSql()
        );
    }
}
