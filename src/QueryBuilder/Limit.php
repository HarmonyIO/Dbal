<?php declare(strict_types=1);

namespace HarmonyIO\Dbal\QueryBuilder;

final class Limit implements QueryPart
{
    /** @var int */
    private $limit;

    public function __construct(int $limit)
    {
        $this->limit = $limit;
    }

    public function toSql(): string
    {
        return 'LIMIT ' . $this->limit;
    }
}
