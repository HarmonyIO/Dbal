<?php declare(strict_types=1);

namespace HarmonyIO\DbalTest\Unit\Exception;

use HarmonyIO\Dbal\Exception\UnsupportedConditionOperator;
use HarmonyIO\PHPUnitExtension\TestCase;

class UnsupportedConditionOperatorTest extends TestCase
{
    public function testFormatsMessage(): void
    {
        $this->expectException(UnsupportedConditionOperator::class);
        $this->expectExceptionMessage('`The operator` is not a supported condition operator.');

        throw new UnsupportedConditionOperator('The operator');
    }
}
