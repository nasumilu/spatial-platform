<?php

namespace Nasumilu\DBAL\Schema;

use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Schema\Table;
use Nasumilu\DBAL\Types\GeometryType;

class FeatureClass
{

    public function __construct(private readonly int $srid, private readonly Table $table)
    { }

    public function getName(): string {
        return $this->table->getName();
    }

    public function getGeometry(): Column
    {
        $columns = array_filter(
            $this->table->getColumns(),
            fn(Column $column) => $column->getType() instanceof GeometryType
        );
        $count = count($columns);
        if( $count !== 1) {
            throw new Exception("Feature must have one geometry type, found $count!");
        }
        return array_values($columns)[0];
    }

    public function getProperties(): array
    {
        $columns = array_filter(
            $this->table->getColumns(),
            fn(Column $column) => !$column->getType() instanceof GeometryType
        );
        return array_values($columns);
    }

    /**
     * @return int
     */
    public function getSrid(): int
    {
        return $this->srid;
    }

    /**
     * @return Table
     */
    public function getTable(): Table
    {
        return $this->table;
    }

}