<?php declare(strict_types=1);

namespace HarmonyIO\Dbal;

use Amp\Mysql\Pool as MysqlPool;
use Amp\Postgres\Pool as PostgresqlPool;
use Amp\Sql\Link;
use HarmonyIO\Dbal\QueryBuilder\Column\FieldSet;
use HarmonyIO\Dbal\QueryBuilder\Factory\Condition;
use HarmonyIO\Dbal\QueryBuilder\Factory\Field;
use HarmonyIO\Dbal\QueryBuilder\Factory\Table;
use HarmonyIO\Dbal\QueryBuilder\QuoteStyle;
use HarmonyIO\Dbal\QueryBuilder\Statement\Delete;
use HarmonyIO\Dbal\QueryBuilder\Statement\Insert;
use HarmonyIO\Dbal\QueryBuilder\Statement\Select;
use HarmonyIO\Dbal\QueryBuilder\Statement\Update;

class Connection
{
    /** @var Link */
    private $link;

    /** @var QuoteStyle */
    private $quoteStyle;

    /** @var Field */
    private $fieldFactory;

    /** @var Table */
    private $tableFactory;

    /** @var Condition */
    private $conditionFactory;

    public function __construct(Link $link)
    {
        $this->link = $link;

        if ($this->link instanceof PostgresqlPool) {
            $this->quoteStyle = new QuoteStyle(QuoteStyle::POSTGRESQL);
        }

        if ($this->link instanceof MysqlPool) {
            $this->quoteStyle = new QuoteStyle(QuoteStyle::MYSQL);
        }

        $this->fieldFactory     = new Field($this->quoteStyle);
        $this->tableFactory     = new Table($this->quoteStyle);
        $this->conditionFactory = new Condition($this->quoteStyle, $this->fieldFactory);
    }

    public function select(string ...$fieldDefinitions): Select
    {
        $fields = array_reduce($fieldDefinitions, function (array $fields, string $fieldDefinition) {
            $fields[] = $this->fieldFactory->buildFromString($fieldDefinition);

            return $fields;
        }, []);

        return new Select(
            $this->quoteStyle,
            new FieldSet(...$fields),
            $this->tableFactory,
            $this->conditionFactory,
            $this->fieldFactory
        );
    }

    public function update(string $tableDefinition): Update
    {
        return new Update(
            $this->tableFactory->buildFromString($tableDefinition),
            $this->tableFactory,
            $this->conditionFactory,
            $this->fieldFactory
        );
    }

    public function delete(string $tableDefinition): Delete
    {
        return new Delete(
            $this->tableFactory->buildFromString($tableDefinition),
            $this->tableFactory,
            $this->conditionFactory,
            $this->fieldFactory
        );
    }

    public function insert(string $tableDefinition): Insert
    {
        return new Insert(
            $this->quoteStyle,
            $this->tableFactory->buildFromString($tableDefinition),
            $this->tableFactory,
            $this->conditionFactory,
            $this->fieldFactory
        );
    }
}
