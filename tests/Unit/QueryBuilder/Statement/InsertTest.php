<?php declare(strict_types=1);

namespace HarmonyIO\DbalTest\Unit\QueryBuilder\Statement;

use HarmonyIO\Dbal\QueryBuilder\Factory\Condition as ConditionFactory;
use HarmonyIO\Dbal\QueryBuilder\Factory\Field as FieldFactory;
use HarmonyIO\Dbal\QueryBuilder\Factory\Table as TableFactory;
use HarmonyIO\Dbal\QueryBuilder\Identifier\Table;
use HarmonyIO\Dbal\QueryBuilder\QuoteStyle;
use HarmonyIO\Dbal\QueryBuilder\Statement\Insert;
use HarmonyIO\PHPUnitExtension\TestCase;

class InsertTest extends TestCase
{
    /** @var QuoteStyle */
    private $quoteStyle;

    /** @var Insert */
    private $insert;

    //phpcs:ignore SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingReturnTypeHint
    public function setUp()
    {
        $this->quoteStyle = new QuoteStyle(QuoteStyle::MYSQL);
        $tableFactory     = new TableFactory($this->quoteStyle);
        $fieldFactory     = new FieldFactory($this->quoteStyle);
        $conditionFactory = new ConditionFactory($this->quoteStyle, $fieldFactory);

        $this->insert = new Insert(
            $this->quoteStyle,
            new Table($this->quoteStyle, 'table'),
            $tableFactory,
            $conditionFactory,
            $fieldFactory
        );
    }

    public function testGetQueryWithJustConstructor(): void
    {
        $this->assertSame('INSERT INTO `table` () VALUES ()', $this->insert->getQuery());
    }

    public function testJoin(): void
    {
        $this->insert
            ->value('column1', 'foobar')
            ->join('table2', 'table2.id = table.id')
        ;

        $this->assertSame(
            'INSERT INTO `table` (`column1`) VALUES (?) JOIN `table2` ON `table2`.`id` = `table`.`id`',
            $this->insert->getQuery()
        );
    }

    public function testLeftJoin(): void
    {
        $this->insert
            ->value('column1', 'foobar')
            ->leftJoin('table2', 'table2.id = table.id')
        ;

        $this->assertSame(
            'INSERT INTO `table` (`column1`) VALUES (?) LEFT JOIN `table2` ON `table2`.`id` = `table`.`id`',
            $this->insert->getQuery()
        );
    }

    public function testRightJoin(): void
    {
        $this->insert
            ->value('column1', 'foobar')
            ->rightJoin('table2', 'table2.id = table.id')
        ;

        $this->assertSame(
            'INSERT INTO `table` (`column1`) VALUES (?) RIGHT JOIN `table2` ON `table2`.`id` = `table`.`id`',
            $this->insert->getQuery()
        );
    }
}
