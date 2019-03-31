<?php declare(strict_types=1);

namespace HarmonyIO\DbalTest\Unit\QueryBuilder\Column;

use HarmonyIO\Dbal\QueryBuilder\Column\Field;
use HarmonyIO\Dbal\QueryBuilder\Column\FieldSet;
use HarmonyIO\Dbal\QueryBuilder\QuoteStyle;
use HarmonyIO\PHPUnitExtension\TestCase;

class FieldSetTest extends TestCase
{
    public function testToSql(): void
    {
        $this->assertSame('`column1`, `column1`', (new FieldSet(
            new Field(new QuoteStyle(QuoteStyle::MYSQL), 'column1'),
            new Field(new QuoteStyle(QuoteStyle::MYSQL), 'column1')
        ))->toSql());
    }
}
