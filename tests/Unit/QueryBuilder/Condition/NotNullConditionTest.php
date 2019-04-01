<?php declare(strict_types=1);

namespace HarmonyIO\DbalTest\Unit\QueryBuilder\Condition;

use HarmonyIO\Dbal\QueryBuilder\Column\Field;
use HarmonyIO\Dbal\QueryBuilder\Condition\NotNullCondition;
use HarmonyIO\Dbal\QueryBuilder\QuoteStyle;
use HarmonyIO\PHPUnitExtension\TestCase;

class NotNullConditionTest extends TestCase
{
    /** @var QuoteStyle */
    private $quoteStyle;

    //phpcs:ignore SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingReturnTypeHint
    public function setUp()
    {
        $this->quoteStyle = new QuoteStyle(QuoteStyle::MYSQL);
    }

    public function testToSql(): void
    {
        $condition = new NotNullCondition(new Field($this->quoteStyle, 'column', 'table'));

        $this->assertSame('`table`.`column` IS NOT NULL', $condition->toSql());
    }
}
