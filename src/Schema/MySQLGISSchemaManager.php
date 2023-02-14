<?php

namespace Nasumilu\DBAL\Schema;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Schema\MySQLSchemaManager;
use Doctrine\DBAL\Schema\PostgreSQLSchemaManager;
use Doctrine\DBAL\Schema\Table;

class MySQLGISSchemaManager extends MySQLSchemaManager implements SpatialSchemaManager
{

    public function __construct(Connection $connection, AbstractPlatform $platform)
    {
        parent::__construct($connection, $platform);
    }

    /**
     * @throws Exception
     */
    public function listFeatures(): array {
        $database = $this->_conn->getDatabase();
        $tableColumnsByTable      = $this->fetchTableColumnsByTable($database);
        $indexColumnsByTable      = $this->fetchIndexColumnsByTable($database);
        $foreignKeyColumnsByTable = $this->fetchForeignKeyColumnsByTable($database);
        $tableOptionsByTable      = $this->fetchTableOptionsByTable($database);

        $sql = $this->_platform->getListGeometryColumnsSQL($database);
        $results = $this->_conn->fetchAllAssociative($sql);
        $features = [];
        foreach($results as $result) {
            $table = new Table(
                $result['table_name'],
                $this->_getPortableTableColumnList(
                    $result['table_name'],
                    $database,
                    $tableColumnsByTable[$result['table_name']]
                ),
                $this->_getPortableTableIndexesList(
                    $indexColumnsByTable[$result['table_name']] ?? [],
                    $result['table_name']
                ),
                [],
                $this->_getPortableTableForeignKeysList(
                    $foreignKeyColumnsByTable[$result['table_name']] ?? [],
                ),
                $tableOptionsByTable[$result['table_name']]
            );
            $features[$result['table_name']] = new FeatureClass($result['srid'], $table);
        }
        return $features;
    }

    /**
     * @throws Exception
     */
    public function getFeatureExtent(FeatureClass | string $feature): array
    {
        $featureClass = $feature;
        if (is_string($feature)) {
            $featureClass = $this->listFeatures()[$feature] ?? null;
        }

        if(!$featureClass instanceof FeatureClass) {
            throw new Exception(sprintf(
                '%s `%s` not found.',
                FeatureClass::class,
                is_object($feature) ? $feature->getName() : $feature
            ));
        }

        $bbox = $this->_conn->fetchOne($this->_platform->getFeatureExtentSQL($featureClass));
        $matches = [];
        preg_match_all('/-?[0-9]\d*(\.\d+)?/', $bbox, $matches,  PREG_SET_ORDER);
        return array_column($matches, 0);
    }
}