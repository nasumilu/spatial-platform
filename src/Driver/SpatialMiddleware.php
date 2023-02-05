<?php

namespace Nasumilu\DBAL\Driver;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver;
use Doctrine\DBAL\Driver\API\ExceptionConverter;
use Doctrine\DBAL\Driver\Middleware;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Doctrine\DBAL\Types\Type;
use Nasumilu\DBAL\Platforms\SpatialPlatform;
use Nasumilu\DBAL\Types\GeometryType;

abstract class SpatialMiddleware implements Middleware, Driver
{

    private Driver $driver;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        if(!Type::hasType('geometry')) {
            Type::addType('geometry', GeometryType::class);
        }
    }

    public function wrap(Driver $driver): Driver
    {
        $this->driver = $driver;
        return $this;
    }

    public function connect(array $params): Driver\Connection
    {
        return $this->driver->connect($params);
    }

    public function getSchemaManager(Connection $conn, AbstractPlatform $platform): AbstractSchemaManager
    {
        $platform = $this->getDatabasePlatform();
        return $platform->createSchemaManager($conn, $platform);
    }

    public function getExceptionConverter(): ExceptionConverter
    {
        return $this->driver->getExceptionConverter();
    }
}