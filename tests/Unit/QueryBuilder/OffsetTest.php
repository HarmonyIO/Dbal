<?php declare(strict_types=1);

namespace HarmonyIO\DbalTest\Unit\QueryBuilder;

use HarmonyIO\Dbal\QueryBuilder\Offset;
use HarmonyIO\PHPUnitExtension\TestCase;

class OffsetTest extends TestCase
{
    public function testToSql(): void
    {
        $this->assertSame('OFFSET 10', (new Offset(10))->toSql());
    }
}
