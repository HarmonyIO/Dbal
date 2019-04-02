<?php declare(strict_types=1);

namespace HarmonyIO\DbalTest\Unit\QueryBuilder;

use HarmonyIO\Dbal\QueryBuilder\Limit;
use HarmonyIO\PHPUnitExtension\TestCase;

class LimitTest extends TestCase
{
    public function testToSql(): void
    {
        $this->assertSame('LIMIT 10', (new Limit(10))->toSql());
    }
}
