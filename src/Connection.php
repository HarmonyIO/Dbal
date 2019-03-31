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
use HarmonyIO\Dbal\QueryBuilder\Statement\Select;

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

    public function getQuoteStyling(): QuoteStyle
    {
        return $this->quoteStyle;
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
}
