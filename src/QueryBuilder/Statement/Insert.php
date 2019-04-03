<?php declare(strict_types=1);

namespace HarmonyIO\Dbal\QueryBuilder\Statement;

use HarmonyIO\Dbal\QueryBuilder\Column\Value as ValueColumn;
use HarmonyIO\Dbal\QueryBuilder\Factory\Condition as ConditionFactory;
use HarmonyIO\Dbal\QueryBuilder\Factory\Field as FieldFactory;
use HarmonyIO\Dbal\QueryBuilder\Factory\Table as TableFactory;
use HarmonyIO\Dbal\QueryBuilder\Identifier\Table;
use HarmonyIO\Dbal\QueryBuilder\Join\InnerJoin;
use HarmonyIO\Dbal\QueryBuilder\Join\Join;
use HarmonyIO\Dbal\QueryBuilder\Join\LeftJoin;
use HarmonyIO\Dbal\QueryBuilder\Join\RightJoin;
use HarmonyIO\Dbal\QueryBuilder\QuoteStyle;

final class Insert
{
    /** @var QuoteStyle */
    private $quoteStyle;

    /** @var Table */
    private $table;

    /** @var TableFactory */
    private $tableFactory;

    /** @var ConditionFactory */
    private $conditionFactory;

    /** @var FieldFactory */
    private $fieldFactory;

    /** @var Value[] */
    private $values = [];

    /** @var Join[] */
    private $joins = [];

    public function __construct(
        QuoteStyle $quoteStyle,
        Table $table,
        TableFactory $tableFactory,
        ConditionFactory $conditionFactory,
        FieldFactory $fieldFactory
    ) {
        $this->quoteStyle       = $quoteStyle;
        $this->table            = $table;
        $this->tableFactory     = $tableFactory;
        $this->conditionFactory = $conditionFactory;
        $this->fieldFactory     = $fieldFactory;
    }

    /**
     * @param int|string|null $value
     */
    public function value(string $fieldDefinition, $value): self
    {
        $this->values[]= new Value(
            $this->fieldFactory->buildFromString($fieldDefinition),
            new ValueColumn($this->quoteStyle, $value)
        );

        return $this;
    }

    /**
     * @param string|int|null $parameter
     */
    public function join(string $joinedTable, string $condition, $parameter = null): self
    {
        $this->joins[] = new InnerJoin(
            $this->tableFactory->buildFromString($joinedTable),
            $this->conditionFactory->buildFromString($condition, $parameter)
        );

        return $this;
    }

    /**
     * @param string|int|null $parameter
     */
    public function leftJoin(string $joinedTable, string $condition, $parameter = null): self
    {
        $this->joins[] = new LeftJoin(
            $this->tableFactory->buildFromString($joinedTable),
            $this->conditionFactory->buildFromString($condition, $parameter)
        );

        return $this;
    }

    /**
     * @param string|int|null $parameter
     */
    public function rightJoin(string $joinedTable, string $condition, $parameter = null): self
    {
        $this->joins[] = new RightJoin(
            $this->tableFactory->buildFromString($joinedTable),
            $this->conditionFactory->buildFromString($condition, $parameter)
        );

        return $this;
    }

    public function getQuery(): string
    {
        $fields = implode(', ', array_reduce($this->values, static function (array $valuesSql, Value $value) {
            $valuesSql[] = $value->getFieldSql();

            return $valuesSql;
        }, []));

        $fields = '(' . $fields . ')';

        $values = implode(', ', array_reduce($this->values, static function (array $valuesSql, Value $value) {
            $valuesSql[] = $value->getValueSql();

            return $valuesSql;
        }, []));

        $values = '(' . $values . ')';

        $joins = '';

        if ($this->joins) {
            $joinsSql = array_reduce($this->joins, static function (array $joinsSql, Join $join) {
                $joinsSql[] = $join->toSql();

                return $joinsSql;
            }, []);

            $joins = ' ' . implode(' ', $joinsSql);
        }

        return 'INSERT INTO ' . $this->table->toSql() . ' ' . $fields . ' VALUES ' . $values . $joins;
    }
}
