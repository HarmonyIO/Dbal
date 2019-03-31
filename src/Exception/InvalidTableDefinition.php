<?php declare(strict_types=1);

namespace HarmonyIO\Dbal\Exception;

class InvalidTableDefinition extends Exception
{
    public function __construct(string $definition)
    {
        parent::__construct("`$definition` is not a valid table definition.");
    }
}
