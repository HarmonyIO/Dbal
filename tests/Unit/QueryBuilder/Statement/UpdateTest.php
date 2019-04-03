<?php declare(strict_types=1);

namespace HarmonyIO\DbalTest\Unit\QueryBuilder\Statement;

use HarmonyIO\Dbal\QueryBuilder\Condition\Collection;
use HarmonyIO\Dbal\QueryBuilder\Factory\Condition as ConditionFactory;
use HarmonyIO\Dbal\QueryBuilder\Factory\Field as FieldFactory;
use HarmonyIO\Dbal\QueryBuilder\Factory\Table as TableFactory;
use HarmonyIO\Dbal\QueryBuilder\Identifier\Table;
use HarmonyIO\Dbal\QueryBuilder\QuoteStyle;
use HarmonyIO\Dbal\QueryBuilder\Statement\Update;
use HarmonyIO\PHPUnitExtension\TestCase;

class UpdateTest extends TestCase
{
    /** @var QuoteStyle */
    private $quoteStyle;

    /** @var Update */
    private $update;

    //phpcs:ignore SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingReturnTypeHint
    public function setUp()
    {
        $this->quoteStyle = new QuoteStyle(QuoteStyle::MYSQL);
        $tableFactory     = new TableFactory($this->quoteStyle);
        $fieldFactory     = new FieldFactory($this->quoteStyle);
        $conditionFactory = new ConditionFactory($this->quoteStyle, $fieldFactory);

        $this->update = new Update(
            new Table($this->quoteStyle, 'table'),
            $tableFactory,
            $conditionFactory,
            $fieldFactory
        );
    }

    public function testGetQueryWithJustConstructor(): void
    {
        $this->assertSame('UPDATE `table`', $this->update->getQuery());
    }

    public function testSet(): void
    {
        $this->update->set('column1 = ?', 'foobar');

        $this->assertSame('UPDATE `table` SET `column1` = ?', $this->update->getQuery());
    }

    public function testJoin(): void
    {
        $this->update
            ->set('column1 = ?', 'foobar')
            ->join('table2', 'table2.id = table.id')
        ;

        $this->assertSame(
            'UPDATE `table` SET `column1` = ? JOIN `table2` ON `table2`.`id` = `table`.`id`',
            $this->update->getQuery()
        );
    }

    public function testLeftJoin(): void
    {
        $this->update
            ->set('column1 = ?', 'foobar')
            ->leftJoin('table2', 'table2.id = table.id')
        ;

        $this->assertSame(
            'UPDATE `table` SET `column1` = ? LEFT JOIN `table2` ON `table2`.`id` = `table`.`id`',
            $this->update->getQuery()
        );
    }

    public function testRightJoin(): void
    {
        $this->update
            ->set('column1 = ?', 'foobar')
            ->rightJoin('table2', 'table2.id = table.id')
        ;

        $this->assertSame(
            'UPDATE `table` SET `column1` = ? RIGHT JOIN `table2` ON `table2`.`id` = `table`.`id`',
            $this->update->getQuery()
        );
    }

    public function testWhere(): void
    {
        $this->update
            ->set('column1 = ?', 'foobar')
            ->where('column1 = column2')
        ;

        $this->assertSame(
            'UPDATE `table` SET `column1` = ? WHERE `column1` = `column2`',
            $this->update->getQuery()
        );
    }

    public function testWhereDynamic(): void
    {
        $this->update
            ->set('column1 = ?', 'foobar')
            ->where('column.id = ?', 1)
        ;

        $this->assertSame(
            'UPDATE `table` SET `column1` = ? WHERE `column`.`id` = ?',
            $this->update->getQuery()
        );
    }

    public function testAndWhere(): void
    {
        $this->update
            ->set('column1 = ?', 'foobar')
            ->andWhere(static function (Collection $collection): void {
                $collection->where('column1 = column2');
                $collection->where('column3 = column4');
            })
        ;

        $this->assertSame(
            'UPDATE `table` SET `column1` = ? WHERE (`column1` = `column2` AND `column3` = `column4`)',
            $this->update->getQuery()
        );
    }

    public function testOrWhere(): void
    {
        $this->update
            ->set('column1 = ?', 'foobar')
            ->orWhere(static function (Collection $collection): void {
                $collection->where('column1 = column2');
                $collection->where('column3 = column4');
            })
        ;

        $this->assertSame(
            'UPDATE `table` SET `column1` = ? WHERE (`column1` = `column2` OR `column3` = `column4`)',
            $this->update->getQuery()
        );
    }

    public function testOrderAsc(): void
    {
        $this->update
            ->set('column1 = ?', 'foobar')
            ->orderAsc('column')
        ;

        $this->assertSame(
            'UPDATE `table` SET `column1` = ? ORDER BY `column` ASC',
            $this->update->getQuery()
        );
    }

    public function testOrderDesc(): void
    {
        $this->update
            ->set('column1 = ?', 'foobar')
            ->orderDesc('column')
        ;

        $this->assertSame(
            'UPDATE `table` SET `column1` = ? ORDER BY `column` DESC',
            $this->update->getQuery()
        );
    }

    public function testLimit(): void
    {
        $this->update
            ->set('column1 = ?', 'foobar')
            ->limit(10)
        ;

        $this->assertSame(
            'UPDATE `table` SET `column1` = ? LIMIT 10',
            $this->update->getQuery()
        );
    }

    public function testOffset(): void
    {
        $this->update
            ->set('column1 = ?', 'foobar')
            ->offset(10)
        ;

        $this->assertSame(
            'UPDATE `table` SET `column1` = ? OFFSET 10',
            $this->update->getQuery()
        );
    }
}
