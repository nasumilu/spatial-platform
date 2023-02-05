<?php

namespace Nasumilu\DBAL\Types;

use Doctrine\DBAL\Exception\InvalidArgumentException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Nasumilu\DBAL\Platforms\SpatialPlatform;

class GeometryType extends Type
{

    public static int|null $srid = null;

    /**
     * @throws InvalidArgumentException
     */
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        if (!$platform instanceof SpatialPlatform) {
            throw new InvalidArgumentException("Expected a spatial platform!");
        }
        return $platform->getGeometryTypeSQLDeclaration($column['options']['geometryType'], $column['options']['srid'] ?? null);
    }

    public function convertToDatabaseValueSQL($sqlExpr, AbstractPlatform $platform): string
    {
        if (!$platform instanceof SpatialPlatform) {
            throw new InvalidArgumentException("Expected a spatial platform!");
        }
        return $this->convertToDatabaseValueSQL($sqlExpr, self::$srid);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function convertToPHPValueSQL($sqlExpr, $platform): string
    {
        if (!$platform instanceof SpatialPlatform) {
            throw new InvalidArgumentException("Expected a spatial platform!");
        }
        return $platform->getConvertGeometryToPHPValueSQL($sqlExpr);
    }

    public function getName(): string
    {
        return 'geometry';
    }

    public function getBindingType(): int
    {
        return \PDO::PARAM_LOB;
    }
}