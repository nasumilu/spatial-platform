<?php

namespace Nasumilu\DBAL\Tests\Schema;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use Nasumilu\DBAL\Driver\MySQLGISMiddleware;
use PHPUnit\Framework\TestCase;

class MySQLSchemaManagerTest extends TestCase
{

    private static Connection $connection;

    public static function setUpBeforeClass(): void
    {
        $params = json_decode($_ENV['MYSQL_CONNECTION'], true);
        $configuration = new Configuration();
        $configuration->setMiddlewares([new MySQLGISMiddleware()]);

        self::$connection = DriverManager::getConnection($params, $configuration);
    }

    /**
     * @throws Exception
     */
    public function testListFeatures() {
        $manager = self::$connection->createSchemaManager();
        $features = $manager->listFeatures();
        $this->assertCount(3, $features);
    }

}