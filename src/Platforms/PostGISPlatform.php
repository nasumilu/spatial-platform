<?php

namespace Nasumilu\DBAL\Platforms;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Platforms\PostgreSQLPlatform;
use Doctrine\DBAL\Schema\Column;
use Nasumilu\DBAL\Schema\FeatureClass;
use Nasumilu\DBAL\Schema\PostGISSchemaManager;

/**
 */
class PostGISPlatform extends PostgreSQLPlatform implements SpatialPlatform
{


    public function getListGeometryColumnsSQL(string $database): string
    {
        return "SELECT f_table_catalog   as db_name,
                       f_table_schema    as schema_name,
                       f_table_name      as table_name,
                       f_geometry_column as geometry_column,
                       coord_dimension,
                       srid,
                       type
                FROM geometry_columns";
    }

    public function createSchemaManager(Connection $connection): PostGISSchemaManager
    {
        return new PostGISSchemaManager($connection, $this);
    }

    public function getGeometryTypeSQLDeclaration(string $type, int | null $srid): string
    {
        $srid ??= 0;
        return "geometry($type, $srid)";
    }

    public function getConvertGeometryToDatabaseValueSQL($sqlExpr, int | null $srid): string
    {
        $srid ??= 0;
        return "ST_GeomFromWKB($sqlExpr, $srid)";
    }

    public function getConvertGeometryToPHPValueSQL($sqlExpr): string
    {
        return "ST_AsBinary($sqlExpr)";
    }

    protected function initializeDoctrineTypeMappings(): void {
        parent::initializeDoctrineTypeMappings();
        $this->doctrineTypeMapping = array_merge(
            $this->doctrineTypeMapping,
            [
                'geometry' => 'geometry'
            ]
        );
    }

    public function getFeatureExtentSQL(FeatureClass $feature): string
    {
        $table = $feature->getTable()->getQuotedName($this);
        $column = $feature->getGeometry()->getQuotedName($this);

        return "SELECT ST_EXTENT($column) FROM $table";
    }
}