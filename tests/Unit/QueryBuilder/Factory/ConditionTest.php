<?php declare(strict_types=1);

namespace HarmonyIO\DbalTest\Unit\QueryBuilder\Factory;

use HarmonyIO\Dbal\Exception\InvalidConditionDefinition;
use HarmonyIO\Dbal\QueryBuilder\Column\Column;
use HarmonyIO\Dbal\QueryBuilder\Condition\InCondition;
use HarmonyIO\Dbal\QueryBuilder\Condition\MatchingCondition;
use HarmonyIO\Dbal\QueryBuilder\Condition\NotInCondition;
use HarmonyIO\Dbal\QueryBuilder\Condition\NotNullCondition;
use HarmonyIO\Dbal\QueryBuilder\Condition\NullCondition;
use HarmonyIO\Dbal\QueryBuilder\Factory\Condition;
use HarmonyIO\Dbal\QueryBuilder\Factory\Field;
use HarmonyIO\Dbal\QueryBuilder\QuoteStyle;
use HarmonyIO\PHPUnitExtension\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class ConditionTest extends TestCase
{
    /** @var Field|MockObject */
    private $fieldFactory;

    /** @var Condition */
    private $conditionFactory;

    //phpcs:ignore SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingReturnTypeHint
    public function setUp()
    {
        $this->fieldFactory     = $this->createMock(Field::class);
        $this->conditionFactory = new Condition(new QuoteStyle(QuoteStyle::MYSQL), $this->fieldFactory);
    }

    public function testBuildFromStringThrowsOnInvalidDefinition(): void
    {
        $this->expectException(InvalidConditionDefinition::class);
        $this->expectExceptionMessage('`column` is not a valid condition definition.');

        $this->conditionFactory->buildFromString('column');
    }

    public function testBuildFromStringParsesFieldsOnBothSides(): void
    {
        $this->fieldFactory
            ->expects($this->at(0))
            ->method('buildFromString')
            ->willReturn($this->createMock(Column::class))
            ->with('column1')
        ;

        $this->fieldFactory
            ->expects($this->at(1))
            ->method('buildFromString')
            ->willReturn($this->createMock(Column::class))
            ->with('column2')
        ;

        $this->conditionFactory->buildFromString('column1 = column2');
    }

    public function testBuildFromStringParsesArray(): void
    {
        $this->fieldFactory
            ->expects($this->once())
            ->method('buildFromString')
            ->willReturn($this->createMock(Column::class))
            ->with('column1')
        ;

        $this->conditionFactory->buildFromString('column1 = (?)', [1, 2]);
    }

    public function testBuildFromStringParsesFieldOnLeftSide(): void
    {
        $this->fieldFactory
            ->expects($this->once())
            ->method('buildFromString')
            ->willReturn($this->createMock(Column::class))
            ->with('column')
        ;

        $condition = $this->conditionFactory->buildFromString('column = ?');

        $this->assertInstanceOf(MatchingCondition::class, $condition);
    }

    public function testBuildFromStringEqualsMatchingCondition(): void
    {
        $this->fieldFactory
            ->method('buildFromString')
            ->willReturn($this->createMock(Column::class))
            ->with('column')
        ;

        $condition = $this->conditionFactory->buildFromString('column = ?');

        $this->assertInstanceOf(MatchingCondition::class, $condition);
    }

    public function testBuildFromStringNotEqualsMatchingCondition(): void
    {
        $this->fieldFactory
            ->method('buildFromString')
            ->willReturn($this->createMock(Column::class))
            ->with('column')
        ;

        $condition = $this->conditionFactory->buildFromString('column != ?');

        $this->assertInstanceOf(MatchingCondition::class, $condition);
    }

    public function testBuildFromStringGreaterAndLessThanMatchingCondition(): void
    {
        $this->fieldFactory
            ->method('buildFromString')
            ->willReturn($this->createMock(Column::class))
            ->with('column')
        ;

        $condition = $this->conditionFactory->buildFromString('column <> ?');

        $this->assertInstanceOf(MatchingCondition::class, $condition);
    }

    public function testBuildFromStringGreaterThanMatchingCondition(): void
    {
        $this->fieldFactory
            ->method('buildFromString')
            ->willReturn($this->createMock(Column::class))
            ->with('column')
        ;

        $condition = $this->conditionFactory->buildFromString('column > ?');

        $this->assertInstanceOf(MatchingCondition::class, $condition);
    }

    public function testBuildFromStringLessThanMatchingCondition(): void
    {
        $this->fieldFactory
            ->method('buildFromString')
            ->willReturn($this->createMock(Column::class))
            ->with('column')
        ;

        $condition = $this->conditionFactory->buildFromString('column < ?');

        $this->assertInstanceOf(MatchingCondition::class, $condition);
    }

    public function testBuildFromStringIsNullCondition(): void
    {
        $this->fieldFactory
            ->expects($this->once())
            ->method('buildFromString')
            ->willReturn($this->createMock(Column::class))
            ->with('column')
        ;

        $condition = $this->conditionFactory->buildFromString('column is null');

        $this->assertInstanceOf(NullCondition::class, $condition);
    }

    public function testBuildFromStringIsNullConditionHandlesCaseSensitivity(): void
    {
        $this->fieldFactory
            ->expects($this->once())
            ->method('buildFromString')
            ->willReturn($this->createMock(Column::class))
            ->with('column')
        ;

        $condition = $this->conditionFactory->buildFromString('column iS nulL');

        $this->assertInstanceOf(NullCondition::class, $condition);
    }

    public function testBuildFromStringIsNotNullCondition(): void
    {
        $this->fieldFactory
            ->expects($this->once())
            ->method('buildFromString')
            ->willReturn($this->createMock(Column::class))
            ->with('column')
        ;

        $condition = $this->conditionFactory->buildFromString('column is not null');

        $this->assertInstanceOf(NotNullCondition::class, $condition);
    }

    public function testBuildFromStringIsNotNullConditionHandlesCaseSensitivity(): void
    {
        $this->fieldFactory
            ->expects($this->once())
            ->method('buildFromString')
            ->willReturn($this->createMock(Column::class))
            ->with('column')
        ;

        $condition = $this->conditionFactory->buildFromString('column iS noT nulL');

        $this->assertInstanceOf(NotNullCondition::class, $condition);
    }

    public function testBuildFromStringInConditionWithArray(): void
    {
        $this->fieldFactory
            ->expects($this->once())
            ->method('buildFromString')
            ->willReturn($this->createMock(Column::class))
            ->with('column')
        ;

        $condition = $this->conditionFactory->buildFromString('column in (?)', [1, 2]);

        $this->assertInstanceOf(InCondition::class, $condition);
    }

    public function testBuildFromStringInConditionWithArrayHandlesCaseSensitivity(): void
    {
        $this->fieldFactory
            ->expects($this->once())
            ->method('buildFromString')
            ->willReturn($this->createMock(Column::class))
            ->with('column')
        ;

        $condition = $this->conditionFactory->buildFromString('column iN (?)', [1, 2]);

        $this->assertInstanceOf(InCondition::class, $condition);
    }

    public function testBuildFromStringNotInConditionWithArray(): void
    {
        $this->fieldFactory
            ->expects($this->once())
            ->method('buildFromString')
            ->willReturn($this->createMock(Column::class))
            ->with('column')
        ;

        $condition = $this->conditionFactory->buildFromString('column not in (?)', [1, 2]);

        $this->assertInstanceOf(NotInCondition::class, $condition);
    }

    public function testBuildFromStringNotInConditionWithArrayHandlesCaseSensitivity(): void
    {
        $this->fieldFactory
            ->expects($this->once())
            ->method('buildFromString')
            ->willReturn($this->createMock(Column::class))
            ->with('column')
        ;

        $condition = $this->conditionFactory->buildFromString('column noT iN (?)', [1, 2]);

        $this->assertInstanceOf(NotInCondition::class, $condition);
    }
}
