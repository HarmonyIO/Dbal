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
    ->select('column1')
    ->from('table1')
    ->join('table2', 'table2.id = table1.id')
    ->where('column1 not in (?)', [1, 2])
;

var_dump($query->getQuery());
var_dump($query->getParameters());
