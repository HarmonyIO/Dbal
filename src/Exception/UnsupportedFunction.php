<?php declare(strict_types=1);

namespace HarmonyIO\Dbal\Exception;

class UnsupportedFunction extends Exception
{
    public function __construct(string $function)
    {
        parent::__construct("`$function` is not a supported function.");
    }
}
