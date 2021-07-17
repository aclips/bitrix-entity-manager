<?php

namespace enoffspb\BitrixEntityManager\Tests\Unit;

use enoffspb\BitrixEntityManager\Tests\Entity\Example;
use enoffspb\BitrixEntityManager\Tests\Table\ExampleTable;
use Bitrix\Main\Application;

abstract class BaseTestCase extends \PHPUnit\Framework\TestCase
{
    private static $tables = [
        ExampleTable::class
    ];

    private static \Bitrix\Main\DB\Connection $connection;

    protected static $entitiesConfig = [
        Example::class => [
            'tableClass' => ExampleTable::class,
        ]
    ];

    public static function setUpBeforeClass(): void
    {
        parent::setUp();

        self::$connection = Application::getConnection();

        self::dropTables();
        self::createTables();
    }

    public static function tearDownAfterClass(): void
    {
        parent::tearDown(); // TODO: Change the autogenerated stub
        self::dropTables();
    }

    private static function createTables()
    {
        foreach(self::$tables as $table) {
            $sqlQueries = $table::getEntity()->compileDbTableStructureDump();

            foreach($sqlQueries as $sql) {
                self::$connection->queryExecute($sql);
            }
        }
    }

    private static function dropTables()
    {
        foreach(self::$tables as $table) {
            $tableName = $table::getTableName();

            $sql = "DROP TABLE IF EXISTS `$tableName`";
            self::$connection->queryExecute($sql);
        }
    }
}
