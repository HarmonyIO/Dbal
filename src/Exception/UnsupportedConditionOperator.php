<?php declare(strict_types=1);

namespace HarmonyIO\Dbal\Exception;

class UnsupportedConditionOperator extends Exception
{
    public function __construct(string $operator)
    {
        parent::__construct("`$operator` is not a supported condition operator.");
    }
}
