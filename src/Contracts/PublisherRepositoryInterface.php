<?php

namespace Viewflex\Listo\Contracts;

use Viewflex\Ligero\Contracts\PublisherRepositoryInterface as LigeroPublisherRepositoryInterface;

interface PublisherRepositoryInterface extends LigeroPublisherRepositoryInterface
{
    
    /*
    |--------------------------------------------------------------------------
    | Database Read Operations
    |--------------------------------------------------------------------------
    */
    
    /**
     * Returns distinct values in a given column (and optional scope).
     *
     * @param string $name
     * @param string $scope
     * @param string $type
     * @return array
     */
    public function getDistinctColumn($name = 'id', $scope = 'query', $type = 'string');
    
}
