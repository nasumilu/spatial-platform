<?php

namespace Nasumilu\DBAL\Schema;

interface SpatialSchemaManager
{

    public function listFeatures(): array;

    public function getFeatureExtent(FeatureClass | string $feature): array;

}