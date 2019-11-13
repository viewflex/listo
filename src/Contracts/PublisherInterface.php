<?php

namespace Viewflex\Listo\Contracts;

use Viewflex\Ligero\Contracts\PublisherInterface as LigeroPublisherInterface;
use Viewflex\Listo\Contracts\PublisherApiInterface as Api;
use Viewflex\Listo\Contracts\PublisherConfigInterface as Config;
use Viewflex\Listo\Contracts\PublisherRepositoryInterface as Query;
use Viewflex\Listo\Contracts\PublisherRequestInterface as Request;

interface PublisherInterface extends LigeroPublisherInterface
{
    /*
    |--------------------------------------------------------------------------
    | Component Objects
    |--------------------------------------------------------------------------
    */

    /**
     * Returns the API used by publisher.
     *
     * @return Api
     */
    public function getApi();

    /**
     * Sets the API used by publisher.
     *
     * @param Api $api
     */
    public function setApi($api);

    /**
     * Returns the config used by publisher.
     *
     * @return Config
     */
    public function getConfig();

    /**
     * Returns the request used by publisher.
     *
     * @return Request
     */
    public function getRequest();

    /**
     * Returns the query used by publisher.
     *
     * @return Query
     */
    public function getQuery();
    
    /*
    |--------------------------------------------------------------------------
    | API Data
    |--------------------------------------------------------------------------
    */

    /**
     * Get contextual API breadcrumbs data.
     *
     * @return mixed
     */
    public function getBreadcrumbs();

    /**
     * Get raw contextual API menu values along with
     * their configurations and current logical
     * query states, for use as API response.
     *
     * @return mixed
     */
    public function getQueryMenus();

    /**
     * Get API sort menu, including currently selected
     * menu item and localized item display names.
     *
     * @return mixed
     */
    public function getSortMenu();
    
}
