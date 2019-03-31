<?php declare(strict_types=1);

namespace HarmonyIO\DbalTest\Unit\Enum;

use HarmonyIO\DbalTest\Fixtures\Enum\EnumWithFoo;
use HarmonyIO\PHPUnitExtension\TestCase;

class EnumTest extends TestCase
{
    public function testThrowsOnInvalidEnumValue(): void
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('`invalid` is not a valid enum value for HarmonyIO\DbalTest\Fixtures\Enum\EnumWithFoo.');

        new EnumWithFoo('invalid');
    }

    public function testGetValue(): void
    {
        $this->assertSame('bar', (new EnumWithFoo('bar'))->getValue());
    }
}
