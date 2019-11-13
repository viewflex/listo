<?php

namespace Viewflex\Listo\Contracts;

use Viewflex\Ligero\Contracts\PublisherApiInterface as LigeroPublisherApiInterface;
use Viewflex\Listo\Contracts\PublisherConfigInterface as Config;
use Viewflex\Listo\Contracts\PublisherRepositoryInterface as Query;
use Viewflex\Listo\Contracts\PublisherRequestInterface as Request;

interface PublisherApiInterface extends LigeroPublisherApiInterface
{
    /*
    |--------------------------------------------------------------------------
    | Component Objects
    |--------------------------------------------------------------------------
    */

    /**
     * Returns the config used by API.
     *
     * @return Config
     */
    public function getConfig();

    /**
     * Returns the request used by API.
     *
     * @return Request
     */
    public function getRequest();

    /**
     * Returns the query used by API.
     *
     * @return Query
     */
    public function getQuery();

    /*
    |--------------------------------------------------------------------------
    | Initialization
    |--------------------------------------------------------------------------
    */

    /**
     * Sets the query used by API.
     *
     * @param Query $query
     */
    public function setQuery($query);

    /*
    |--------------------------------------------------------------------------
    | Output Bundles
    |--------------------------------------------------------------------------
    */

    /**
     * Get contextual breadcrumbs data.
     *
     * For breadcrumbs, the current sort, view and limit,
     * if available in the URL base parameters, are used
     * if configured via persist_sort and persist_view.
     *
     * The generated title string includes all crumb column values.
     *
     * In query scope: all URL base params are persistent, once added.
     * In global scope: no URL base params are persistent.
     *
     * @return mixed
     */
    public function getBreadcrumbs();

    /**
     * Get raw contextual menu values along with
     * their configurations and current logical
     * query states, for use as API response.
     *
     * @return mixed
     */
    public function getQueryMenus();
    
    /**
     * Get sort menu, including currently selected
     * menu item and localized item display names.
     *
     * @return mixed
     */
    public function getSortMenu();

    /*
    |--------------------------------------------------------------------------
    | Utility Functions
    |--------------------------------------------------------------------------
    */

    /**
     * Given an array of crumbs from getBreadcrumbs,
     * this helper function makes a proper title.
     *
     * @param array $crumbs
     * @return string
     */
    public function crumbsTitle($crumbs = []);

    
}
