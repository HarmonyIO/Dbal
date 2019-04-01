<?php declare(strict_types=1);

namespace HarmonyIO\DbalTest\Unit\QueryBuilder\Order;

use HarmonyIO\Dbal\QueryBuilder\Column\Field;
use HarmonyIO\Dbal\QueryBuilder\Order\Direction;
use HarmonyIO\Dbal\QueryBuilder\Order\Order;
use HarmonyIO\Dbal\QueryBuilder\QuoteStyle;
use HarmonyIO\PHPUnitExtension\TestCase;

class OrderTest extends TestCase
{
    public function testToSqlAscending(): void
    {
        $this->assertSame(
            '`column` ASC',
            (new Order(new Field(new QuoteStyle(QuoteStyle::MYSQL), 'column'), new Direction(Direction::ASC)))->toSql()
        );
    }

    public function testToSqlDescending(): void
    {
        $this->assertSame(
            '`column` DESC',
            (new Order(new Field(new QuoteStyle(QuoteStyle::MYSQL), 'column'), new Direction(Direction::DESC)))->toSql()
        );
    }
}
