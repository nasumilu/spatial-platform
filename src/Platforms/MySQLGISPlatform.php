<?php

namespace Nasumilu\DBAL\Platforms;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Platforms\MySQL80Platform;
use Nasumilu\DBAL\Schema\FeatureClass;
use Nasumilu\DBAL\Schema\MySQLGISSchemaManager;

class MySQLGISPlatform extends MySQL80Platform implements SpatialPlatform
{
    public function createSchemaManager(Connection $connection): MySQLGISSchemaManager
    {
        return new MySQLGISSChemaManager($connection, $this);
    }

    public function getConvertGeometryToDatabaseValueSQL($sqlExpr, ?int $srid)
    {
        $srid ??= 0;
        return "ST_GeomFromWKB($sqlExpr, $srid)";
    }

    public function getConvertGeometryToPHPValueSQL($sqlExpr): string
    {
        return "ST_AsBinary($sqlExpr)";
    }

    public function getGeometryTypeSQLDeclaration(string $type, ?int $srid): string
    {
        $srid ??= 0;
        return "$type srid $srid";
    }

    public function getListGeometryColumnsSQL(string $database): string
    {
        return 'SELECT TABLE_SCHEMA       as db_name,
                       null               as schema_name,
                       TABLE_NAME         as table_name,
                       COLUMN_NAME        as geometry_column,
                       SRS_ID             as srid,
                       GEOMETRY_TYPE_NAME as type
                FROM information_schema.ST_GEOMETRY_COLUMNS
                WHERE TABLE_SCHEMA = ' .  $this->quoteStringLiteral($database);
    }

    public function getFeatureExtentSQL(FeatureClass $feature): string
    {
        $table = $feature->getTable()->getQuotedName($this);
        $column = $feature->getGeometry()->getQuotedName($this);
        return "SELECT ST_ENVELOPE($column) FROM $table";
    }
}