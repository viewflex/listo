<?php

namespace Viewflex\Listo\Publishers;

use Viewflex\Ligero\Utility\ModelLoaderTrait;
use Viewflex\Ligero\Utility\RouteHelperTrait;
use Viewflex\Listo\Base\BasePublisherRepository;
use Viewflex\Listo\Contracts\PublisherApiInterface;
use Viewflex\Listo\Contracts\PublisherConfigInterface as Config;
use Viewflex\Listo\Contracts\PublisherRepositoryInterface as Query;
use Viewflex\Listo\Contracts\PublisherRequestInterface as Request;
use Viewflex\Listo\Exceptions\PublisherException;

class PublisherApi implements PublisherApiInterface
{
    use ModelLoaderTrait;
    use PublisherApiTrait;
    use RouteHelperTrait;

//    /**
//     * Override ModelLoaderTrait.
//     *
//     * @return string
//     */
//    public function modelName()
//    {
//        return $this->config->getModelName() ? : 'Viewflex\Listo\Base\BaseModel';
//    }

    /*
    |--------------------------------------------------------------------------
    | Component Objects
    |--------------------------------------------------------------------------
    */

    /**
     * @var Config
     */
    protected $config;

    /**
     * @return Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param Config $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    /**
     * @var Request
     */
    protected $request;

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param Request $request
     */
    public function setRequest($request)
    {
        $this->request = $request;
    }

    /**
     * @var Query
     */
    protected $query;

    /**
     * @return Query
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @param Query $query
     */
    public function setQuery($query)
    {
        $this->query = $query;
        $this->query->setApi($this);
    }

    /**
     * Use injected Config and Request, and injected or base Query.
     *
     * @param Config $config
     * @param Request $request
     * @param Query $query
     * @throws PublisherException
     */
    public function __construct(Config $config, Request $request, Query $query = null)
    {
        $this->setConfig($config);
        $this->setRequest($request);

        if (!$this->modelExists())
            throw new PublisherException('Specified model '.$this->modelName().' cannot be found.');

        if ($query)
            $this->setQuery($query);
        else
            $this->setQuery(new BasePublisherRepository());

    }
    
}
