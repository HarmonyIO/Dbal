<?php declare(strict_types=1);

namespace HarmonyIO\Dbal\QueryBuilder;

final class Offset implements QueryPart
{
    /** @var int */
    private $offset;

    public function __construct(int $offset)
    {
        $this->offset = $offset;
    }

    public function toSql(): string
    {
        return 'OFFSET ' . $this->offset;
    }
}
