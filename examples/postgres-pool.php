<?php declare(strict_types=1);

namespace HarmonyIO\Dbal\Examples;

use Amp\Postgres\ConnectionConfig;
use HarmonyIO\Dbal\Connection;
use HarmonyIO\Dbal\QueryBuilder\Condition\Collection;
use function Amp\Postgres\pool;

require_once __DIR__ . '/../vendor/autoload.php';

$postgresPool = pool(
    new ConnectionConfig('127.0.0.1', ConnectionConfig::DEFAULT_PORT, 'username', 'password', 'databasename')
);

$connection = new Connection($postgresPool);

$query = $connection
    ->select('id', 'name', 'count(id)', 'users.name as testje')
    ->from('users')
    ->where('foo IN (?)', ['one', 'two'])
    ->join('companies', 'companies.id = users.company_id')
    ->leftJoin('companies', 'companies.id = users.company_id')
    ->rightJoin('companies', 'companies.id IN (?)', ['foo', 'bar'])
    ->orWhere(static function (Collection $conditions): void {
        $conditions->orWhere(static function (Collection $conditions): void {
            $conditions->where('companies.foo = ?', 'bar');
            $conditions->where('companies.foo = ?', 3);
        });
        $conditions->andWhere(static function (Collection $conditions): void {
            $conditions->where('companies.foo = ?', 'bar');
            $conditions->where('companies.foo = ?', 3);
        });
    })
    ->orderDesc('id')
    ->orderASC('id')
    ->limit(10)
    ->offset(3)
    ->getQuery()
;

var_dump($query);
