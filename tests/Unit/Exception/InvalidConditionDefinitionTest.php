<?php declare(strict_types=1);

namespace HarmonyIO\DbalTest\Unit\Exception;

use HarmonyIO\Dbal\Exception\InvalidConditionDefinition;
use HarmonyIO\PHPUnitExtension\TestCase;

class InvalidConditionDefinitionTest extends TestCase
{
    public function testFormatsMessage(): void
    {
        $this->expectException(InvalidConditionDefinition::class);
        $this->expectExceptionMessage('`The definition` is not a valid condition definition.');

        throw new InvalidConditionDefinition('The definition');
    }
}
