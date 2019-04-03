<?php declare(strict_types=1);

namespace HarmonyIO\DbalTest\Unit\QueryBuilder\Statement;

use HarmonyIO\Dbal\QueryBuilder\Column\Field;
use HarmonyIO\Dbal\QueryBuilder\Column\FieldSet;
use HarmonyIO\Dbal\QueryBuilder\Condition\Collection;
use HarmonyIO\Dbal\QueryBuilder\Factory\Condition as ConditionFactory;
use HarmonyIO\Dbal\QueryBuilder\Factory\Field as FieldFactory;
use HarmonyIO\Dbal\QueryBuilder\Factory\Table as TableFactory;
use HarmonyIO\Dbal\QueryBuilder\QuoteStyle;
use HarmonyIO\Dbal\QueryBuilder\Statement\Select;
use HarmonyIO\PHPUnitExtension\TestCase;

class SelectTest extends TestCase
{
    /** @var QuoteStyle */
    private $quoteStyle;

    /** @var Select */
    private $select;

    //phpcs:ignore SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingReturnTypeHint
    public function setUp()
    {
        $this->quoteStyle = new QuoteStyle(QuoteStyle::MYSQL);
        $tableFactory     = new TableFactory($this->quoteStyle);
        $fieldFactory     = new FieldFactory($this->quoteStyle);
        $conditionFactory = new ConditionFactory($this->quoteStyle, $fieldFactory);

        $this->select = new Select(
            $this->quoteStyle,
            new FieldSet(
                new Field($this->quoteStyle, 'column1'),
                new Field($this->quoteStyle, 'column2')
            ),
            $tableFactory,
            $conditionFactory,
            $fieldFactory
        );
    }

    public function testGetQueryWithJustConstructor(): void
    {
        $this->assertSame('SELECT `column1`, `column2`', $this->select->getQuery());
    }

    public function testFrom(): void
    {
        $this->select->from('table');

        $this->assertSame('SELECT `column1`, `column2` FROM `table`', $this->select->getQuery());
    }

    public function testJoin(): void
    {
        $this->select
            ->from('table')
            ->join('table2', 'table2.id = table.id')
        ;

        $this->assertSame(
            'SELECT `column1`, `column2` FROM `table` JOIN `table2` ON `table2`.`id` = `table`.`id`',
            $this->select->getQuery()
        );
    }

    public function testLeftJoin(): void
    {
        $this->select
            ->from('table')
            ->leftJoin('table2', 'table2.id = table.id')
        ;

        $this->assertSame(
            'SELECT `column1`, `column2` FROM `table` LEFT JOIN `table2` ON `table2`.`id` = `table`.`id`',
            $this->select->getQuery()
        );
    }

    public function testRightJoin(): void
    {
        $this->select
            ->from('table')
            ->rightJoin('table2', 'table2.id = table.id')
        ;

        $this->assertSame(
            'SELECT `column1`, `column2` FROM `table` RIGHT JOIN `table2` ON `table2`.`id` = `table`.`id`',
            $this->select->getQuery()
        );
    }

    public function testWhere(): void
    {
        $this->select
            ->from('table')
            ->where('column1 = column2')
        ;

        $this->assertSame(
            'SELECT `column1`, `column2` FROM `table` WHERE `column1` = `column2`',
            $this->select->getQuery()
        );
    }

    public function testWhereDynamic(): void
    {
        $this->select
            ->from('table')
            ->where('column.id = ?', 1)
        ;

        $this->assertSame(
            'SELECT `column1`, `column2` FROM `table` WHERE `column`.`id` = ?',
            $this->select->getQuery()
        );
    }

    public function testAndWhere(): void
    {
        $this->select
            ->from('table')
            ->andWhere(static function (Collection $collection): void {
                $collection->where('column1 = column2');
                $collection->where('column3 = column4');
            })
        ;

        $this->assertSame(
            'SELECT `column1`, `column2` FROM `table` WHERE (`column1` = `column2` AND `column3` = `column4`)',
            $this->select->getQuery()
        );
    }

    public function testOrWhere(): void
    {
        $this->select
            ->from('table')
            ->orWhere(static function (Collection $collection): void {
                $collection->where('column1 = column2');
                $collection->where('column3 = column4');
            })
        ;

        $this->assertSame(
            'SELECT `column1`, `column2` FROM `table` WHERE (`column1` = `column2` OR `column3` = `column4`)',
            $this->select->getQuery()
        );
    }

    public function testOrderAsc(): void
    {
        $this->select
            ->from('table')
            ->orderAsc('column')
        ;

        $this->assertSame(
            'SELECT `column1`, `column2` FROM `table` ORDER BY `column` ASC',
            $this->select->getQuery()
        );
    }

    public function testOrderDesc(): void
    {
        $this->select
            ->from('table')
            ->orderDesc('column')
        ;

        $this->assertSame(
            'SELECT `column1`, `column2` FROM `table` ORDER BY `column` DESC',
            $this->select->getQuery()
        );
    }

    public function testLimit(): void
    {
        $this->select
            ->from('table')
            ->limit(10)
        ;

        $this->assertSame(
            'SELECT `column1`, `column2` FROM `table` LIMIT 10',
            $this->select->getQuery()
        );
    }

    public function testOffset(): void
    {
        $this->select
            ->from('table')
            ->offset(10)
        ;

        $this->assertSame(
            'SELECT `column1`, `column2` FROM `table` OFFSET 10',
            $this->select->getQuery()
        );
    }
}
