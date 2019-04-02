<?php declare(strict_types=1);

namespace HarmonyIO\DbalTest\Unit\QueryBuilder\SqlFunction;

use HarmonyIO\Dbal\QueryBuilder\Column\Field;
use HarmonyIO\Dbal\QueryBuilder\QuoteStyle;
use HarmonyIO\Dbal\QueryBuilder\SqlFunction\SqlFunction;
use HarmonyIO\PHPUnitExtension\TestCase;

class SqlFunctionTest extends TestCase
{
    public function testGetTableReturnsNullWhenNotDefined(): void
    {
        $this->assertNull(
            (new SqlFunction(
                new QuoteStyle(QuoteStyle::MYSQL),
                'test',
                new Field(new QuoteStyle(QuoteStyle::MYSQL), 'column')
            ))->getTable()
        );
    }

    public function testGetTable(): void
    {
        $this->assertSame(
            'table',
            (new SqlFunction(
                new QuoteStyle(QuoteStyle::MYSQL),
                'test',
                new Field(new QuoteStyle(QuoteStyle::MYSQL), 'column', 'table')
            ))->getTable()
        );
    }

    public function testGetName(): void
    {
        $this->assertSame(
            'column',
            (new SqlFunction(
                new QuoteStyle(QuoteStyle::MYSQL),
                'test',
                new Field(new QuoteStyle(QuoteStyle::MYSQL), 'column')
            ))->getName()
        );
    }

    public function testGetAliasReturnsNullWhenNotDefined(): void
    {
        $this->assertNull(
            (new SqlFunction(
                new QuoteStyle(QuoteStyle::MYSQL),
                'test',
                new Field(new QuoteStyle(QuoteStyle::MYSQL), 'column')
            ))->getAlias()
        );
    }

    public function testGetAlias(): void
    {
        $this->assertSame(
            'alias',
            (new SqlFunction(
                new QuoteStyle(QuoteStyle::MYSQL),
                'test',
                new Field(new QuoteStyle(QuoteStyle::MYSQL), 'column', 'table', 'alias')
            ))->getAlias()
        );
    }

    public function testGetToSqlWithoutAlias(): void
    {
        $this->assertSame(
            'TEST(`table`.`column`)',
            (new SqlFunction(
                new QuoteStyle(QuoteStyle::MYSQL),
                'test',
                new Field(new QuoteStyle(QuoteStyle::MYSQL), 'column', 'table', 'alias')
            ))->toSqlWithOutAlias()
        );
    }

    public function testToSql(): void
    {
        $this->assertSame(
            'TEST(`table`.`column`) AS `alias`',
            (new SqlFunction(
                new QuoteStyle(QuoteStyle::MYSQL),
                'test',
                new Field(new QuoteStyle(QuoteStyle::MYSQL), 'column', 'table', 'alias')
            ))->toSql()
        );
    }
}
