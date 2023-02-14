<?php

namespace Nasumilu\DBAL\Driver;

use Nasumilu\DBAL\Platforms\MySQLGISPlatform;

class MySQLGISMiddleware extends SpatialMiddleware
{

    public function getDatabasePlatform(): MySQLGISPlatform
    {
        return new MySQLGISPlatform();
    }

}