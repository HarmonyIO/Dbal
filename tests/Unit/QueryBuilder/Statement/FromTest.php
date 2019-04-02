<?php declare(strict_types=1);

namespace HarmonyIO\DbalTest\Unit\QueryBuilder\Statement;

use HarmonyIO\Dbal\QueryBuilder\Identifier\Table;
use HarmonyIO\Dbal\QueryBuilder\QuoteStyle;
use HarmonyIO\Dbal\QueryBuilder\Statement\From;
use HarmonyIO\PHPUnitExtension\TestCase;

class FromTest extends TestCase
{
    public function testToSql(): void
    {
        $this->assertSame('FROM `table`', (new From(new Table(new QuoteStyle(QuoteStyle::MYSQL), 'table')))->toSql());
    }
}
