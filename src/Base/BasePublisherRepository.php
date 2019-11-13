<?php

namespace Viewflex\Listo\Base;

use Illuminate\Database\Eloquent\Builder;
use Viewflex\Ligero\Base\BasePublisherRepository as LigeroBasePublisherRepository;
use Viewflex\Listo\Contracts\PublisherRepositoryInterface;
use Viewflex\Listo\Exceptions\PublisherRepositoryException;

/**
 * The base Publisher Repository class, used as the default.
 * Modify properties via setters, or extend and customize.
 */
class BasePublisherRepository extends LigeroBasePublisherRepository implements PublisherRepositoryInterface
{
    /*
    |--------------------------------------------------------------------------
    | Database Read Operations
    |--------------------------------------------------------------------------
    |
    */
    
    /**
     * @param string $name
     * @param string $scope
     * @param string $type
     * @return array
     * @throws PublisherRepositoryException
     */
    public function getDistinctColumn($name = 'id', $scope = 'qu ery', $type = 'string')
    {
        $builder = $this->columnQuery($name, $scope);

        $results = function($builder) {
            list($keys, $values) = array_divide(array_dot($builder->get()->toArray()));
            return $values;
        };

        $values = $this->cacheQuery($results, $builder);
        $this->logQuery('... getDistinctColumn('.$name.', '.$scope.', '.$type.'): '.$builder->getQuery()->toSql());

        switch ($type) {
            case 'string':
                $values = array_filter($values, 'strlen');
                break;

            case 'boolean':
                $values = array_map('intval', $values);
                break;

            default: // numeric or other type
                break;
        }

        return $values;
    }

    /*
    |--------------------------------------------------------------------------
    | Query Builder Functions
    |--------------------------------------------------------------------------
    */

    /**
     * Return a Builder instance for getting distinct values
     * of specified column, within the required search scope.
     * The wider the scope, the more records returned, so
     * be careful with large data sets, especially when
     * querying columns with many distinct values.
     *
     * @param string $name
     * @param string $scope
     * @return Builder
     */
    protected function columnQuery($name = '', $scope = 'query')
    {
        $builder = $this->mapSelect([$name])->distinct()->orderBy($this->mapColumn($name), 'ASC');

        switch ($scope)
        {
            case 'query':
            {
                // Use full set of query criteria
                $builder = $this->queryCriteria($builder);
                break;
            }

            case 'global':
            {
                // Don't use any search parameters
                break;
            }
        }

        return $builder;
    }
    
    
    
}