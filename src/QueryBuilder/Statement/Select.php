<?php declare(strict_types=1);

namespace HarmonyIO\Dbal\QueryBuilder\Statement;

use HarmonyIO\Dbal\QueryBuilder\Column\FieldSet;
use HarmonyIO\Dbal\QueryBuilder\Condition\AndCondition;
use HarmonyIO\Dbal\QueryBuilder\Condition\Collection;
use HarmonyIO\Dbal\QueryBuilder\Condition\Condition;
use HarmonyIO\Dbal\QueryBuilder\Condition\OrCondition;
use HarmonyIO\Dbal\QueryBuilder\Factory\Condition as ConditionFactory;
use HarmonyIO\Dbal\QueryBuilder\Factory\Field as FieldFactory;
use HarmonyIO\Dbal\QueryBuilder\Factory\Table as TableFactory;
use HarmonyIO\Dbal\QueryBuilder\Identifier\Table;
use HarmonyIO\Dbal\QueryBuilder\Join\InnerJoin;
use HarmonyIO\Dbal\QueryBuilder\Join\Join;
use HarmonyIO\Dbal\QueryBuilder\Join\LeftJoin;
use HarmonyIO\Dbal\QueryBuilder\Join\RightJoin;
use HarmonyIO\Dbal\QueryBuilder\Limit;
use HarmonyIO\Dbal\QueryBuilder\Offset;
use HarmonyIO\Dbal\QueryBuilder\Order\Direction;
use HarmonyIO\Dbal\QueryBuilder\Order\Order;
use HarmonyIO\Dbal\QueryBuilder\QuoteStyle;

final class Select
{
    /** @var QuoteStyle */
    private $quoteStyle;

    /** @var FieldSet */
    private $fieldSet;

    /** @var TableFactory */
    private $tableFactory;

    /** @var ConditionFactory */
    private $conditionFactory;

    /** @var FieldFactory */
    private $fieldFactory;

    /** @var From */
    private $from;

    /** @var Join[] */
    private $joins = [];

    /** @var Condition|null */
    private $condition;

    /** @var Order[] */
    private $orders = [];

    /** @var Limit|null */
    private $limit;

    /** @var Offset|null */
    private $offset;

    public function __construct(
        QuoteStyle $quoteStyle,
        FieldSet $fieldSet,
        TableFactory $tableFactory,
        ConditionFactory $conditionFactory,
        FieldFactory $fieldFactory
    ) {
        $this->quoteStyle       = $quoteStyle;
        $this->fieldSet         = $fieldSet;
        $this->tableFactory     = $tableFactory;
        $this->conditionFactory = $conditionFactory;
        $this->fieldFactory     = $fieldFactory;
    }

    public function from(string $table): self
    {
        $this->from = new From(new Table($this->quoteStyle, $table));

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

    /**
     * @param string|int|null $parameter
     */
    public function where(string $condition, $parameter = null): self
    {
        $this->condition = $this->conditionFactory->buildFromString($condition, $parameter);

        return $this;
    }

    public function andWhere(callable $conditionAdder): self
    {
        $conditionCollection = new Collection($this->conditionFactory);

        $conditionAdder($conditionCollection);

        $this->condition = new AndCondition(...$conditionCollection->getConditions());

        return $this;
    }

    public function orWhere(callable $conditionAdder): self
    {
        $conditionCollection = new Collection($this->conditionFactory);

        $conditionAdder($conditionCollection);

        $this->condition = new OrCondition(...$conditionCollection->getConditions());

        return $this;
    }

    public function orderAsc(string $fieldDefinition): self
    {
        $this->orders[] = new Order($this->fieldFactory->buildFromString($fieldDefinition), new Direction(Direction::ASC));

        return $this;
    }

    public function orderDesc(string $fieldDefinition): self
    {
        $this->orders[] = new Order($this->fieldFactory->buildFromString($fieldDefinition), new Direction(Direction::DESC));

        return $this;
    }

    public function limit(int $limit): self
    {
        $this->limit = new Limit($limit);

        return $this;
    }

    public function offset(int $offset): self
    {
        $this->offset = new Offset($offset);

        return $this;
    }

    public function getQuery(): string
    {
        $joins = '';

        if ($this->joins) {
            $joinsSql = array_reduce($this->joins, static function (array $joinsSql, Join $join) {
                $joinsSql[] = $join->toSql();

                return $joinsSql;
            }, []);

            $joins = ' ' . implode(' ', $joinsSql);
        }

        $condition = '';

        if ($this->condition !== null) {
            $condition = ' WHERE ' . $this->condition->toSql();
        }

        $orders = '';

        if ($this->orders) {
            $ordersSql = array_reduce($this->orders, static function (array $ordersSql, Order $order) {
                $ordersSql[] = $order->toSql();

                return $ordersSql;
            }, []);

            $orders = ' ORDER BY ' . implode(', ', $ordersSql);
        }

        $limit = '';

        if ($this->limit !== null) {
            $limit = ' ' . $this->limit->toSql();
        }

        $offset = '';

        if ($this->offset !== null) {
            $offset = ' ' . $this->offset->toSql();
        }

        return 'SELECT ' . $this->fieldSet->toSql() . ' ' . $this->from->toSql() . $joins . $condition . $orders . $limit . $offset;
    }
}
