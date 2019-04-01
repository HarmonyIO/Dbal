<?php declare(strict_types=1);

namespace HarmonyIO\DbalTest\Unit\QueryBuilder\Factory;

use HarmonyIO\Dbal\Exception\InvalidTableDefinition;
use HarmonyIO\Dbal\QueryBuilder\Factory\Table;
use HarmonyIO\Dbal\QueryBuilder\QuoteStyle;
use HarmonyIO\PHPUnitExtension\TestCase;

class TableTest extends TestCase
{
    /** @var Table */
    private $tableFactory;

    //phpcs:ignore SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingReturnTypeHint
    public function setUp()
    {
        $this->tableFactory = new Table(new QuoteStyle(QuoteStyle::MYSQL));
    }

    public function testBuildFromStringThrowsOnInvalidDefinition(): void
    {
        $this->expectException(InvalidTableDefinition::class);
        $this->expectExceptionMessage('`foo ast bar` is not a valid table definition.');

        $this->tableFactory->buildFromString('foo ast bar');
    }

    public function testBuildFromStringWithOnlyTableName(): void
    {
        $table = $this->tableFactory->buildFromString('foo');

        $this->assertSame('foo', $table->getName());
        $this->assertNull($table->getAlias());
    }

    public function testBuildFromStringWithTableNameAndAlias(): void
    {
        $table = $this->tableFactory->buildFromString('foo as alias');

        $this->assertSame('foo', $table->getName());
        $this->assertSame('alias', $table->getAlias());
    }

    public function testBuildFromStringHandlesAliasCaseInsensitive(): void
    {
        $table = $this->tableFactory->buildFromString('foo aS alias');

        $this->assertSame('foo', $table->getName());
        $this->assertSame('alias', $table->getAlias());
    }
}
