<?php declare(strict_types=1);

namespace HarmonyIO\Dbal\QueryBuilder\Column;

use HarmonyIO\Dbal\QueryBuilder\QueryPart;

final class FieldSet implements QueryPart
{
    /** @var Column[] */
    private $fields;

    public function __construct(Column ...$fields)
    {
        $this->fields = $fields;
    }

    public function toSql(): string
    {
        $fieldsSql = array_reduce($this->fields, static function (array $fieldsSql, Column $field) {
            $fieldsSql[] = $field->toSql();

            return $fieldsSql;
        }, []);

        return implode(', ', $fieldsSql);
    }
}
