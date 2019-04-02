<?php declare(strict_types=1);

namespace HarmonyIO\DbalTest\Unit\QueryBuilder\SqlFunction;

use HarmonyIO\Dbal\QueryBuilder\Column\Field;
use HarmonyIO\Dbal\QueryBuilder\QuoteStyle;
use HarmonyIO\Dbal\QueryBuilder\SqlFunction\Min;
use HarmonyIO\PHPUnitExtension\TestCase;

class MinTest extends TestCase
{
    public function testGetTableReturnsNullWhenNotDefined(): void
    {
        $this->assertNull(
            (new Min(
                new QuoteStyle(QuoteStyle::MYSQL),
                new Field(new QuoteStyle(QuoteStyle::MYSQL), 'column')
            ))->getTable()
        );
    }

    public function testGetTable(): void
    {
        $this->assertSame(
            'table',
            (new Min(
                new QuoteStyle(QuoteStyle::MYSQL),
                new Field(new QuoteStyle(QuoteStyle::MYSQL), 'column', 'table')
            ))->getTable()
        );
    }

    public function testGetName(): void
    {
        $this->assertSame(
            'column',
            (new Min(
                new QuoteStyle(QuoteStyle::MYSQL),
                new Field(new QuoteStyle(QuoteStyle::MYSQL), 'column')
            ))->getName()
        );
    }

    public function testGetAliasReturnsNullWhenNotDefined(): void
    {
        $this->assertNull(
            (new Min(
                new QuoteStyle(QuoteStyle::MYSQL),
                new Field(new QuoteStyle(QuoteStyle::MYSQL), 'column')
            ))->getAlias()
        );
    }

    public function testGetAlias(): void
    {
        $this->assertSame(
            'alias',
            (new Min(
                new QuoteStyle(QuoteStyle::MYSQL),
                new Field(new QuoteStyle(QuoteStyle::MYSQL), 'column', 'table', 'alias')
            ))->getAlias()
        );
    }

    public function testGetToSqlWithoutAlias(): void
    {
        $this->assertSame(
            'MIN(`table`.`column`)',
            (new Min(
                new QuoteStyle(QuoteStyle::MYSQL),
                new Field(new QuoteStyle(QuoteStyle::MYSQL), 'column', 'table', 'alias')
            ))->toSqlWithOutAlias()
        );
    }

    public function testToSql(): void
    {
        $this->assertSame(
            'MIN(`table`.`column`) AS `alias`',
            (new Min(
                new QuoteStyle(QuoteStyle::MYSQL),
                new Field(new QuoteStyle(QuoteStyle::MYSQL), 'column', 'table', 'alias')
            ))->toSql()
        );
    }
}
