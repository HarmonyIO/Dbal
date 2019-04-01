<?php declare(strict_types=1);

namespace HarmonyIO\DbalTest\Unit\QueryBuilder\Factory;

use HarmonyIO\Dbal\Exception\InvalidFieldDefinition;
use HarmonyIO\Dbal\Exception\UnsupportedFunction;
use HarmonyIO\Dbal\QueryBuilder\Column\Field as FieldColumn;
use HarmonyIO\Dbal\QueryBuilder\Factory\Field;
use HarmonyIO\Dbal\QueryBuilder\QuoteStyle;
use HarmonyIO\Dbal\QueryBuilder\SqlFunction\Count;
use HarmonyIO\PHPUnitExtension\TestCase;

class FieldTest extends TestCase
{
    /** @var Field */
    private $fieldFactory;

    //phpcs:ignore SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingReturnTypeHint
    public function setUp()
    {
        $this->fieldFactory = new Field(new QuoteStyle(QuoteStyle::MYSQL));
    }

    public function testBuildFromStringThrowsOnInvalidDefinition(): void
    {
        $this->expectException(InvalidFieldDefinition::class);
        $this->expectExceptionMessage('`co lumn` is not a valid field definition.');

        $this->fieldFactory->buildFromString('co lumn');
    }

    public function testBuildFromStringWithOnlyFieldName(): void
    {
        $field = $this->fieldFactory->buildFromString('column');

        $this->assertInstanceOf(FieldColumn::class, $field);
        $this->assertSame('column', $field->getName());
        $this->assertNull($field->getAlias());
        $this->assertNull($field->getTable());
    }

    public function testBuildFromStringWithFieldNameAndAlias(): void
    {
        $field = $this->fieldFactory->buildFromString('column as alias');

        $this->assertInstanceOf(FieldColumn::class, $field);
        $this->assertSame('column', $field->getName());
        $this->assertSame('alias', $field->getAlias());
        $this->assertNull($field->getTable());
    }

    public function testBuildFromStringWithFieldNameAndTableName(): void
    {
        $field = $this->fieldFactory->buildFromString('table.column');

        $this->assertInstanceOf(FieldColumn::class, $field);
        $this->assertSame('column', $field->getName());
        $this->assertNull($field->getAlias());
        $this->assertSame('table', $field->getTable());
    }

    public function testBuildFromStringWithFieldNameAndTableNameAndAlias(): void
    {
        $field = $this->fieldFactory->buildFromString('table.column as alias');

        $this->assertInstanceOf(FieldColumn::class, $field);
        $this->assertSame('column', $field->getName());
        $this->assertSame('alias', $field->getAlias());
        $this->assertSame('table', $field->getTable());
    }

    public function testBuildFromStringWithFieldNameAndTableNameAndAliasHandlesCaseInsensitive(): void
    {
        $field = $this->fieldFactory->buildFromString('table.column aS alias');

        $this->assertInstanceOf(FieldColumn::class, $field);
        $this->assertSame('column', $field->getName());
        $this->assertSame('alias', $field->getAlias());
        $this->assertSame('table', $field->getTable());
    }

    public function testBuildFromStringThrowsWithOnlyFunction(): void
    {
        $this->expectException(InvalidFieldDefinition::class);
        $this->expectExceptionMessage('`count()` is not a valid field definition.');

        $this->fieldFactory->buildFromString('count()');
    }

    public function testBuildFromStringWithFunction(): void
    {
        $field = $this->fieldFactory->buildFromString('count(column)');

        $this->assertInstanceOf(Count::class, $field);
        $this->assertSame('column', $field->getName());
        $this->assertNull($field->getAlias());
        $this->assertNull($field->getTable());
    }

    public function testBuildFromStringWithFunctionAndTableName(): void
    {
        $field = $this->fieldFactory->buildFromString('count(table.column)');

        $this->assertInstanceOf(Count::class, $field);
        $this->assertSame('column', $field->getName());
        $this->assertNull($field->getAlias());
        $this->assertSame('table', $field->getTable());
    }

    public function testBuildFromStringWithFunctionAndAlias(): void
    {
        $field = $this->fieldFactory->buildFromString('count(column) as alias');

        $this->assertInstanceOf(Count::class, $field);
        $this->assertSame('column', $field->getName());
        $this->assertSame('alias', $field->getAlias());
        $this->assertNull($field->getTable());
    }

    public function testBuildFromStringWithFunctionAndTableNameAndAlias(): void
    {
        $field = $this->fieldFactory->buildFromString('count(table.column) as alias');

        $this->assertInstanceOf(Count::class, $field);
        $this->assertSame('column', $field->getName());
        $this->assertSame('alias', $field->getAlias());
        $this->assertSame('table', $field->getTable());
    }

    public function testBuildFromStringWithFunctionAndTableNameAndAliasHandlesCaseInsensitive(): void
    {
        $field = $this->fieldFactory->buildFromString('counT(table.column) aS alias');

        $this->assertInstanceOf(Count::class, $field);
        $this->assertSame('column', $field->getName());
        $this->assertSame('alias', $field->getAlias());
        $this->assertSame('table', $field->getTable());
    }

    public function testBuildFromStringThrowsOnUnsupportedFunction(): void
    {
        $this->expectException(UnsupportedFunction::class);
        $this->expectExceptionMessage('`unsupported` is not a supported function.');

        $this->fieldFactory->buildFromString('unsupported(column)');
    }
}
