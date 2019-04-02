<?php declare(strict_types=1);

namespace HarmonyIO\DbalTest\Unit\QueryBuilder\SqlFunction;

use HarmonyIO\Dbal\QueryBuilder\Column\Field;
use HarmonyIO\Dbal\QueryBuilder\QuoteStyle;
use HarmonyIO\Dbal\QueryBuilder\SqlFunction\Sum;
use HarmonyIO\PHPUnitExtension\TestCase;

class SumTest extends TestCase
{
    public function testGetTableReturnsNullWhenNotDefined(): void
    {
        $this->assertNull(
            (new Sum(
                new QuoteStyle(QuoteStyle::MYSQL),
                new Field(new QuoteStyle(QuoteStyle::MYSQL), 'column')
            ))->getTable()
        );
    }

    public function testGetTable(): void
    {
        $this->assertSame(
            'table',
            (new Sum(
                new QuoteStyle(QuoteStyle::MYSQL),
                new Field(new QuoteStyle(QuoteStyle::MYSQL), 'column', 'table')
            ))->getTable()
        );
    }

    public function testGetName(): void
    {
        $this->assertSame(
            'column',
            (new Sum(
                new QuoteStyle(QuoteStyle::MYSQL),
                new Field(new QuoteStyle(QuoteStyle::MYSQL), 'column')
            ))->getName()
        );
    }

    public function testGetAliasReturnsNullWhenNotDefined(): void
    {
        $this->assertNull(
            (new Sum(
                new QuoteStyle(QuoteStyle::MYSQL),
                new Field(new QuoteStyle(QuoteStyle::MYSQL), 'column')
            ))->getAlias()
        );
    }

    public function testGetAlias(): void
    {
        $this->assertSame(
            'alias',
            (new Sum(
                new QuoteStyle(QuoteStyle::MYSQL),
                new Field(new QuoteStyle(QuoteStyle::MYSQL), 'column', 'table', 'alias')
            ))->getAlias()
        );
    }

    public function testGetToSqlWithoutAlias(): void
    {
        $this->assertSame(
            'SUM(`table`.`column`)',
            (new Sum(
                new QuoteStyle(QuoteStyle::MYSQL),
                new Field(new QuoteStyle(QuoteStyle::MYSQL), 'column', 'table', 'alias')
            ))->toSqlWithOutAlias()
        );
    }

    public function testToSql(): void
    {
        $this->assertSame(
            'SUM(`table`.`column`) AS `alias`',
            (new Sum(
                new QuoteStyle(QuoteStyle::MYSQL),
                new Field(new QuoteStyle(QuoteStyle::MYSQL), 'column', 'table', 'alias')
            ))->toSql()
        );
    }
}
