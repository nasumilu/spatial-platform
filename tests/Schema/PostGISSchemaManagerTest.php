<?php

namespace Nasumilu\DBAL\Tests\Schema;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use Nasumilu\DBAL\Driver\PostGISMiddleware;
use PHPUnit\Framework\TestCase;

class PostGISSchemaManagerTest extends TestCase
{

    private static Connection $connection;

    public static function setUpBeforeClass(): void
    {
        $params = json_decode($_ENV['POSTGRESQL_CONNECTION'], true);
        $configuration = new Configuration();
        $configuration->setMiddlewares([new PostGISMiddleware()]);

        self::$connection = DriverManager::getConnection($params, $configuration);
    }

    /**
     * @throws Exception
     */
    public function testListFeatures() {
        $manager = self::$connection->createSchemaManager();
        $features = $manager->listFeatures();
        $this->assertCount(1, $features);
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testExtent() {
        $manager = self::$connection->createSchemaManager();
        $extent = $manager->getFeatureExtent('place');
        $this->assertIsArray($extent);
        $this->assertCount(4, $extent);
    }

}