<?php declare(strict_types=1);

namespace HarmonyIO\Dbal\Exception;

class InvalidConditionDefinition extends Exception
{
    public function __construct(string $definition)
    {
        parent::__construct("`$definition` is not a valid condition definition.");
    }
}
