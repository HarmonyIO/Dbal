<?php declare(strict_types=1);

namespace HarmonyIO\DbalTest\Unit\QueryBuilder\Statement;

use HarmonyIO\Dbal\QueryBuilder\Column\Field;
use HarmonyIO\Dbal\QueryBuilder\Column\Value as ValueColumn;
use HarmonyIO\Dbal\QueryBuilder\QuoteStyle;
use HarmonyIO\Dbal\QueryBuilder\Statement\Value;
use HarmonyIO\PHPUnitExtension\TestCase;

class ValueTest extends TestCase
{
    public function testGetFieldSql(): void
    {
        $field = new Field(new QuoteStyle(QuoteStyle::MYSQL), 'column');
        $value = new ValueColumn(new QuoteStyle(QuoteStyle::MYSQL), 'foobar');

        $this->assertSame('`column`', (new Value($field, $value))->getFieldSql());
    }

    public function testGetValueSqlWithValue(): void
    {
        $field = new Field(new QuoteStyle(QuoteStyle::MYSQL), 'column');
        $value = new ValueColumn(new QuoteStyle(QuoteStyle::MYSQL), 'foobar');

        $this->assertSame('?', (new Value($field, $value))->getValueSql());
    }

    public function testGetValueSqlWithField(): void
    {
        $field = new Field(new QuoteStyle(QuoteStyle::MYSQL), 'column1');
        $value = new Field(new QuoteStyle(QuoteStyle::MYSQL), 'column2');

        $this->assertSame('`column2`', (new Value($field, $value))->getValueSql());
    }
}
