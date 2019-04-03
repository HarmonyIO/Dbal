<?php declare(strict_types=1);

namespace HarmonyIO\DbalTest\Unit\QueryBuilder\Statement;

use HarmonyIO\Dbal\QueryBuilder\Condition\Collection;
use HarmonyIO\Dbal\QueryBuilder\Factory\Condition as ConditionFactory;
use HarmonyIO\Dbal\QueryBuilder\Factory\Field as FieldFactory;
use HarmonyIO\Dbal\QueryBuilder\Factory\Table as TableFactory;
use HarmonyIO\Dbal\QueryBuilder\Identifier\Table;
use HarmonyIO\Dbal\QueryBuilder\QuoteStyle;
use HarmonyIO\Dbal\QueryBuilder\Statement\Delete;
use HarmonyIO\PHPUnitExtension\TestCase;

class DeleteTest extends TestCase
{
    /** @var QuoteStyle */
    private $quoteStyle;

    /** @var Delete */
    private $delete;

    //phpcs:ignore SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingReturnTypeHint
    public function setUp()
    {
        $this->quoteStyle = new QuoteStyle(QuoteStyle::MYSQL);
        $tableFactory     = new TableFactory($this->quoteStyle);
        $fieldFactory     = new FieldFactory($this->quoteStyle);
        $conditionFactory = new ConditionFactory($this->quoteStyle, $fieldFactory);

        $this->delete = new Delete(
            new Table($this->quoteStyle, 'table'),
            $tableFactory,
            $conditionFactory,
            $fieldFactory
        );
    }

    public function testGetQueryWithJustConstructor(): void
    {
        $this->assertSame('DELETE FROM `table`', $this->delete->getQuery());
    }

    public function testJoin(): void
    {
        $this->delete
            ->join('table2', 'table2.id = table.id')
        ;

        $this->assertSame(
            'DELETE FROM `table` JOIN `table2` ON `table2`.`id` = `table`.`id`',
            $this->delete->getQuery()
        );
    }

    public function testLeftJoin(): void
    {
        $this->delete
            ->leftJoin('table2', 'table2.id = table.id')
        ;

        $this->assertSame(
            'DELETE FROM `table` LEFT JOIN `table2` ON `table2`.`id` = `table`.`id`',
            $this->delete->getQuery()
        );
    }

    public function testRightJoin(): void
    {
        $this->delete
            ->rightJoin('table2', 'table2.id = table.id')
        ;

        $this->assertSame(
            'DELETE FROM `table` RIGHT JOIN `table2` ON `table2`.`id` = `table`.`id`',
            $this->delete->getQuery()
        );
    }

    public function testWhere(): void
    {
        $this->delete
            ->where('column1 = column2')
        ;

        $this->assertSame(
            'DELETE FROM `table` WHERE `column1` = `column2`',
            $this->delete->getQuery()
        );
    }

    public function testWhereDynamic(): void
    {
        $this->delete
            ->where('column.id = ?', 1)
        ;

        $this->assertSame(
            'DELETE FROM `table` WHERE `column`.`id` = ?',
            $this->delete->getQuery()
        );
    }

    public function testAndWhere(): void
    {
        $this->delete
            ->andWhere(static function (Collection $collection): void {
                $collection->where('column1 = column2');
                $collection->where('column3 = column4');
            })
        ;

        $this->assertSame(
            'DELETE FROM `table` WHERE (`column1` = `column2` AND `column3` = `column4`)',
            $this->delete->getQuery()
        );
    }

    public function testOrWhere(): void
    {
        $this->delete
            ->orWhere(static function (Collection $collection): void {
                $collection->where('column1 = column2');
                $collection->where('column3 = column4');
            })
        ;

        $this->assertSame(
            'DELETE FROM `table` WHERE (`column1` = `column2` OR `column3` = `column4`)',
            $this->delete->getQuery()
        );
    }

    public function testOrderAsc(): void
    {
        $this->delete
            ->orderAsc('column')
        ;

        $this->assertSame(
            'DELETE FROM `table` ORDER BY `column` ASC',
            $this->delete->getQuery()
        );
    }

    public function testOrderDesc(): void
    {
        $this->delete
            ->orderDesc('column')
        ;

        $this->assertSame(
            'DELETE FROM `table` ORDER BY `column` DESC',
            $this->delete->getQuery()
        );
    }

    public function testLimit(): void
    {
        $this->delete
            ->limit(10)
        ;

        $this->assertSame(
            'DELETE FROM `table` LIMIT 10',
            $this->delete->getQuery()
        );
    }

    public function testOffset(): void
    {
        $this->delete
            ->offset(10)
        ;

        $this->assertSame(
            'DELETE FROM `table` OFFSET 10',
            $this->delete->getQuery()
        );
    }
}
