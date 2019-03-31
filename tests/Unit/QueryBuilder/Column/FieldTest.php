<?php declare(strict_types=1);

namespace HarmonyIO\DbalTest\Unit\QueryBuilder\Column;

use HarmonyIO\Dbal\QueryBuilder\Column\Field;
use HarmonyIO\Dbal\QueryBuilder\QuoteStyle;
use HarmonyIO\PHPUnitExtension\TestCase;

class FieldTest extends TestCase
{
    public function testGetTableWhenNull(): void
    {
        $this->assertNull((new Field(new QuoteStyle(QuoteStyle::MYSQL), 'column'))->getTable());
    }

    public function testGetTable(): void
    {
        $this->assertSame('table', (new Field(new QuoteStyle(QuoteStyle::MYSQL), 'column', 'table'))->getTable());
    }

    public function testGetName(): void
    {
        $this->assertSame('column', (new Field(new QuoteStyle(QuoteStyle::MYSQL), 'column'))->getName());
    }

    public function testGetAliasWhenNull(): void
    {
        $this->assertNull((new Field(new QuoteStyle(QuoteStyle::MYSQL), 'column'))->getTable());
    }

    public function testGetAlias(): void
    {
        $this->assertSame(
            'alias',
            (new Field(new QuoteStyle(QuoteStyle::MYSQL), 'column', 'table', 'alias'))->getAlias()
        );
    }

    public function testToSqlWithoutTableAndAlias(): void
    {
        $this->assertSame(
            '`column`',
            (new Field(new QuoteStyle(QuoteStyle::MYSQL), 'column'))->toSql()
        );
    }

    public function testToSqlWithoutTable(): void
    {
        $this->assertSame(
            '`column` AS `alias`',
            (new Field(new QuoteStyle(QuoteStyle::MYSQL), 'column', null, 'alias'))->toSql()
        );
    }

    public function testToSqlWithoutAlias(): void
    {
        $this->assertSame(
            '`table`.`column`',
            (new Field(new QuoteStyle(QuoteStyle::MYSQL), 'column', 'table'))->toSql()
        );
    }

    public function testToSql(): void
    {
        $this->assertSame(
            '`table`.`column` AS `alias`',
            (new Field(new QuoteStyle(QuoteStyle::MYSQL), 'column', 'table', 'alias'))->toSql()
        );
    }
}
