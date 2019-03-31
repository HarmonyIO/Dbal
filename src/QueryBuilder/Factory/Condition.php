<?php declare(strict_types=1);

namespace HarmonyIO\Dbal\QueryBuilder\Factory;

use HarmonyIO\Dbal\Exception\InvalidConditionDefinition;
use HarmonyIO\Dbal\Exception\UnsupportedConditionOperator;
use HarmonyIO\Dbal\QueryBuilder\Column\Column;
use HarmonyIO\Dbal\QueryBuilder\Column\Value;
use HarmonyIO\Dbal\QueryBuilder\Condition\Condition as ConditionObject;
use HarmonyIO\Dbal\QueryBuilder\Condition\InCondition;
use HarmonyIO\Dbal\QueryBuilder\Condition\MatchingCondition;
use HarmonyIO\Dbal\QueryBuilder\Condition\NotInCondition;
use HarmonyIO\Dbal\QueryBuilder\Condition\NotNullCondition;
use HarmonyIO\Dbal\QueryBuilder\Condition\NullCondition;
use HarmonyIO\Dbal\QueryBuilder\QuoteStyle;

class Condition
{
    /** @var QuoteStyle */
    private $quoteStyle;

    /** @var Field */
    private $fieldFactory;

    public function __construct(QuoteStyle $quoteStyle, Field $fieldFactory)
    {
        $this->quoteStyle   = $quoteStyle;
        $this->fieldFactory = $fieldFactory;
    }

    /**
     * @param int|string|mixed[]|null $parameter
     */
    public function buildFromString(string $string, $parameter = null): ConditionObject
    {
        $pattern = '~(\s*(!=|=|<>|<|>|\s+IS(\s+NOT(\s+NULL)?)?|\s+IN)\s*)~i';

        $fields = preg_split($pattern, $string);

        $pattern = '~\s*(!=|=|<>|<|>|\s+IS(?:\s+NOT(?:\s+NULL)?)?|\s+IN)\s*~i';

        if (preg_match($pattern, $string, $matches) !==1) {
            throw new InvalidConditionDefinition($string);
        }

        $field1 = $this->fieldFactory->buildFromString($fields[0]);

        $parameter = $this->buildParameter($fields[1], $parameter);

        $operator = strtoupper(trim($matches[1]));

        switch ($operator) {
            case '!=':
            case '=':
            case '<>':
            case '<':
            case '>':
                return new MatchingCondition($field1, $operator, $parameter);

            case 'IS NULL':
                return new NullCondition($field1);

            case 'IS NOT NULL':
                return new NotNullCondition($field1);

            case 'IN':
                return new InCondition($field1, $parameter);

            case 'NOT IN':
                return new NotInCondition($field1, $parameter);

            default:
                throw new UnsupportedConditionOperator($operator);
        }
    }

    /**
     * @param int|string|mixed[]|null $parameter
     * @return Column|mixed[]
     */
    private function buildParameter(string $field, $parameter)
    {
        if ($field !== '?' && $field !== '(?)') {
            return $this->fieldFactory->buildFromString($field);
        }

        if (is_array($parameter)) {
            return $parameter;
        }

        return new Value($this->quoteStyle, $parameter);
    }
}
