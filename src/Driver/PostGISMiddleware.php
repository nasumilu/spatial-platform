<?php

namespace Nasumilu\DBAL\Driver;

use Nasumilu\DBAL\Platforms\PostGISPlatform;

class PostGISMiddleware extends SpatialMiddleware
{

    public function getDatabasePlatform(): PostGISPlatform
    {
        return new PostGISPlatform();
    }

}