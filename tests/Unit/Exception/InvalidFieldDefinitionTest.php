<?php declare(strict_types=1);

namespace HarmonyIO\DbalTest\Unit\Exception;

use HarmonyIO\Dbal\Exception\InvalidFieldDefinition;
use HarmonyIO\PHPUnitExtension\TestCase;

class InvalidFieldDefinitionTest extends TestCase
{
    public function testFormatsMessage(): void
    {
        $this->expectException(InvalidFieldDefinition::class);
        $this->expectExceptionMessage('`The definition` is not a valid field definition.');

        throw new InvalidFieldDefinition('The definition');
    }
}
