<?php declare(strict_types=1);

namespace HarmonyIO\DbalTest\Unit\QueryBuilder\Condition;

use HarmonyIO\Dbal\QueryBuilder\Column\Field;
use HarmonyIO\Dbal\QueryBuilder\Column\Value;
use HarmonyIO\Dbal\QueryBuilder\Condition\AndCondition;
use HarmonyIO\Dbal\QueryBuilder\Condition\MatchingCondition;
use HarmonyIO\Dbal\QueryBuilder\QuoteStyle;
use HarmonyIO\PHPUnitExtension\TestCase;

class AndConditionTest extends TestCase
{
    /** @var QuoteStyle */
    private $quoteStyle;

    //phpcs:ignore SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingReturnTypeHint
    public function setUp()
    {
        $this->quoteStyle = new QuoteStyle(QuoteStyle::MYSQL);
    }

    public function testToSqlWithASingleCondition(): void
    {
        $condition = new MatchingCondition(new Field($this->quoteStyle, 'column', 'table'), '=', new Value($this->quoteStyle, 1));

        $this->assertSame(
            '(`table`.`column` = ?)',
            (new AndCondition($condition))->toSql()
        );
    }

    public function testToSqlWithMultipleConditions(): void
    {
        $this->assertSame(
            '(`table`.`column` = ? AND `table`.`column` = ?)',
            (new AndCondition(
                new MatchingCondition(new Field($this->quoteStyle, 'column', 'table'), '=', new Value($this->quoteStyle, 1)),
                new MatchingCondition(new Field($this->quoteStyle, 'column', 'table'), '=', new Value($this->quoteStyle, 2))
            ))->toSql()
        );
    }
}
