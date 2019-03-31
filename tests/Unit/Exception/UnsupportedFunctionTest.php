<?php declare(strict_types=1);

namespace HarmonyIO\DbalTest\Unit\Exception;

use HarmonyIO\Dbal\Exception\UnsupportedFunction;
use HarmonyIO\PHPUnitExtension\TestCase;

class UnsupportedFunctionTest extends TestCase
{
    public function testFormatsMessage(): void
    {
        $this->expectException(UnsupportedFunction::class);
        $this->expectExceptionMessage('`The function` is not a supported function.');

        throw new UnsupportedFunction('The function');
    }
}
