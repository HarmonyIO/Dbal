<?php declare(strict_types=1);

namespace HarmonyIO\DbalTest\Unit\QueryBuilder\Condition;

use HarmonyIO\Dbal\QueryBuilder\Condition\Collection;
use HarmonyIO\Dbal\QueryBuilder\Condition\Condition;
use HarmonyIO\Dbal\QueryBuilder\Factory\Condition as ConditionFactory;
use HarmonyIO\PHPUnitExtension\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class CollectionTest extends TestCase
{
    /** @var MockObject|ConditionFactory */
    private $conditionFactory;

    //phpcs:ignore SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingReturnTypeHint
    public function setUp()
    {
        $this->conditionFactory = $this->createMock(ConditionFactory::class);
    }

    public function testWithJustASingleWhereCondition(): void
    {
        $collection = new Collection($this->conditionFactory);

        $this->conditionFactory
            ->expects($this->once())
            ->method('buildFromString')
            ->willReturn($this->createMock(Condition::class))
        ;

        $collection->where('1 = 1');

        $this->assertCount(1, $collection->getConditions());
    }

    public function testWithMultipleWhereConditions(): void
    {
        $collection = new Collection($this->conditionFactory);

        $this->conditionFactory
            ->expects($this->exactly(2))
            ->method('buildFromString')
            ->willReturn($this->createMock(Condition::class))
        ;

        $collection->where('1 = 1');
        $collection->where('1 = 1');

        $this->assertCount(2, $collection->getConditions());
    }
}
