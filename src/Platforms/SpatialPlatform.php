<?php

namespace Nasumilu\DBAL\Platforms;

use Nasumilu\DBAL\Schema\FeatureClass;

interface SpatialPlatform
{

    public function getConvertGeometryToDatabaseValueSQL($sqlExpr, int | null $srid);

    public function getConvertGeometryToPHPValueSQL($sqlExpr): string;

    public function getGeometryTypeSQLDeclaration(string $type, int | null $srid): string;

    public function getListGeometryColumnsSQL(string $database): string;

    public function getFeatureExtentSQL(FeatureClass $feature): string;


}