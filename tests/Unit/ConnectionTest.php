<?php declare(strict_types=1);

namespace HarmonyIO\DbalTest\Unit;

use Amp\Mysql\ConnectionConfig as MysqlConfig;
use Amp\Postgres\ConnectionConfig as PostgresConfig;
use HarmonyIO\Dbal\Connection;
use HarmonyIO\PHPUnitExtension\TestCase;
use function Amp\Mysql\pool as mysqlPool;
use function Amp\Postgres\pool as postgresPool;

class ConnectionTest extends TestCase
{
    public function testSelectSetsFieldsWithPostgresPool(): void
    {
        $pool  = postgresPool(
            new PostgresConfig('127.0.0.1', PostgresConfig::DEFAULT_PORT, 'username', 'password', 'databasename')
        );

        $connection = new Connection($pool);

        $this->assertSame('SELECT "column1", "column2"', $connection->select('column1', 'column2')->getQuery());
    }

    public function testSelectSetsFieldsWithMysqlPool(): void
    {
        $pool  = mysqlPool(
            new MysqlConfig('127.0.0.1', MysqlConfig::DEFAULT_PORT, 'username', 'password', 'databasename')
        );

        $connection = new Connection($pool);

        $this->assertSame('SELECT `column1`, `column2`', $connection->select('column1', 'column2')->getQuery());
    }

    public function testUpdateWithPostgresPool(): void
    {
        $pool  = postgresPool(
            new PostgresConfig('127.0.0.1', PostgresConfig::DEFAULT_PORT, 'username', 'password', 'databasename')
        );

        $connection = new Connection($pool);

        $this->assertSame('UPDATE "table"', $connection->update('table')->getQuery());
    }

    public function testUpdateWithMysqlPool(): void
    {
        $pool  = mysqlPool(
            new MysqlConfig('127.0.0.1', MysqlConfig::DEFAULT_PORT, 'username', 'password', 'databasename')
        );

        $connection = new Connection($pool);

        $this->assertSame('UPDATE `table`', $connection->update('table')->getQuery());
    }

    public function testDeleteWithPostgresPool(): void
    {
        $pool  = postgresPool(
            new PostgresConfig('127.0.0.1', PostgresConfig::DEFAULT_PORT, 'username', 'password', 'databasename')
        );

        $connection = new Connection($pool);

        $this->assertSame('DELETE FROM "table"', $connection->delete('table')->getQuery());
    }

    public function testDeleteWithMysqlPool(): void
    {
        $pool  = mysqlPool(
            new MysqlConfig('127.0.0.1', MysqlConfig::DEFAULT_PORT, 'username', 'password', 'databasename')
        );

        $connection = new Connection($pool);

        $this->assertSame('DELETE FROM `table`', $connection->delete('table')->getQuery());
    }

    public function testInsertWithPostgresPool(): void
    {
        $pool  = postgresPool(
            new PostgresConfig('127.0.0.1', PostgresConfig::DEFAULT_PORT, 'username', 'password', 'databasename')
        );

        $connection = new Connection($pool);

        $this->assertSame('INSERT INTO "table" ("column1") VALUES (?)', $connection->insert('table')->value('column1', 'value1')->getQuery());
    }

    public function testInsertWithMysqlPool(): void
    {
        $pool  = mysqlPool(
            new MysqlConfig('127.0.0.1', MysqlConfig::DEFAULT_PORT, 'username', 'password', 'databasename')
        );

        $connection = new Connection($pool);

        $this->assertSame('INSERT INTO `table` (`column1`) VALUES (?)', $connection->insert('table')->value('column1', 'value1')->getQuery());
    }
}
