<?php declare(strict_types=1);

namespace HarmonyIO\DbalTest\Unit\QueryBuilder\Column;

use HarmonyIO\Dbal\QueryBuilder\Column\Value;
use HarmonyIO\Dbal\QueryBuilder\QuoteStyle;
use HarmonyIO\PHPUnitExtension\TestCase;

class ValueTest extends TestCase
{
    public function testGetTableReturnsNull(): void
    {
        $this->assertNull((new Value(new QuoteStyle(QuoteStyle::MYSQL), 'foo'))->getTable());
    }

    public function testGetName(): void
    {
        $this->assertSame('foo', (new Value(new QuoteStyle(QuoteStyle::MYSQL), 'foo'))->getName());
    }

    public function testGetAliasWhenNull(): void
    {
        $this->assertNull((new Value(new QuoteStyle(QuoteStyle::MYSQL), 'foo'))->getAlias());
    }

    public function testGetAlias(): void
    {
        $this->assertSame(
            'alias',
            (new Value(new QuoteStyle(QuoteStyle::MYSQL), 'foo', 'alias'))->getAlias()
        );
    }

    public function testToSqlWithoutAlias(): void
    {
        $this->assertSame(
            '?',
            (new Value(new QuoteStyle(QuoteStyle::MYSQL), 'foo'))->toSql()
        );
    }

    public function testToSqlAsArrayWithoutAlias(): void
    {
        $this->assertSame(
            '?, ?',
            (new Value(new QuoteStyle(QuoteStyle::MYSQL), ['foo', 'bar']))->toSql()
        );
    }

    public function testToSql(): void
    {
        $this->assertSame(
            '? AS `alias`',
            (new Value(new QuoteStyle(QuoteStyle::MYSQL), 'foo', 'alias'))->toSql()
        );
    }

    public function testToSqlAsArray(): void
    {
        $this->assertSame(
            '?, ? AS `alias`',
            (new Value(new QuoteStyle(QuoteStyle::MYSQL), ['foo', 'bar'], 'alias'))->toSql()
        );
    }
}
