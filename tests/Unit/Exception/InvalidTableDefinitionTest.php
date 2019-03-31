<?php declare(strict_types=1);

namespace HarmonyIO\DbalTest\Unit\Exception;

use HarmonyIO\Dbal\Exception\InvalidTableDefinition;
use HarmonyIO\PHPUnitExtension\TestCase;

class InvalidTableDefinitionTest extends TestCase
{
    public function testFormatsMessage(): void
    {
        $this->expectException(InvalidTableDefinition::class);
        $this->expectExceptionMessage('`The definition` is not a valid table definition.');

        throw new InvalidTableDefinition('The definition');
    }
}
