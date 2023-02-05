<?php

namespace Nasumilu\DBAL\Tests\Platforms;

use Nasumilu\DBAL\Platforms\PostGISPlatform;
use PHPUnit\Framework\TestCase;

class PostGISPlatformTest extends TestCase
{

    public function testGetGeometryTypeSQLDeclaration() {
        $platform = new PostGISPlatform();
        $this->assertEquals("geometry(point, 0)", $platform->getGeometryTypeSQLDeclaration('point', null));
        $this->assertEquals("geometry(point, 4326)", $platform->getGeometryTypeSQLDeclaration('point', 4326));
    }

}