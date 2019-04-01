<?php declare(strict_types=1);

namespace HarmonyIO\DbalTest\Unit\QueryBuilder\Identifier;

use HarmonyIO\Dbal\QueryBuilder\Identifier\Table;
use HarmonyIO\Dbal\QueryBuilder\QuoteStyle;
use HarmonyIO\PHPUnitExtension\TestCase;

class TableTest extends TestCase
{
    public function testGetName(): void
    {
        $this->assertSame('table', (new Table(new QuoteStyle(QuoteStyle::MYSQL), 'table'))->getName());
    }

    public function testGetAliasReturnsNullWhenNotDefined(): void
    {
        $this->assertNull((new Table(new QuoteStyle(QuoteStyle::MYSQL), 'table'))->getAlias());
    }

    public function testGetAlias(): void
    {
        $this->assertSame('alias', (new Table(new QuoteStyle(QuoteStyle::MYSQL), 'table', 'alias'))->getAlias());
    }

    public function testToSqlWithoutAlias(): void
    {
        $this->assertSame('`table`', (new Table(new QuoteStyle(QuoteStyle::MYSQL), 'table'))->toSql());
    }

    public function testToSqlWithAlias(): void
    {
        $this->assertSame(
            '`table` AS `alias`',
            (new Table(new QuoteStyle(QuoteStyle::MYSQL), 'table', 'alias'))->toSql()
        );
    }
}
