<?php

namespace Viewflex\Listo\Contracts;

use Viewflex\Ligero\Contracts\PublisherConfigInterface as LigeroPublisherConfigInterface;

interface PublisherConfigInterface extends LigeroPublisherConfigInterface
{

    /*
    |--------------------------------------------------------------------------
    | Breadcrumbs
    |--------------------------------------------------------------------------
    */

    /**
     * Get breadcrumbs configuration.
     *
     * @return array
     */
    public function getBreadcrumbsConfig();

    /**
     *  Set breadcrumbs configuration.
     * 
     * @param array $breadcrumbs_config
     */
    public function setBreadcrumbsConfig($breadcrumbs_config);

    /*
    |--------------------------------------------------------------------------
    | Query Menus
    |--------------------------------------------------------------------------
    */

    /**
     * Get query menus configuration.
     *
     * @return array
     */
    public function getQueryMenusConfig();

    /**
     * Set query menus configuration.
     * 
     * @param array $query_menus_config
     */
    public function setQueryMenusConfig($query_menus_config);

    /*
    |--------------------------------------------------------------------------
    |  Sort Menu
    |--------------------------------------------------------------------------
    */

    /**
     * Get named sorts used in sort menu.
     *
     * @return array
     */
    public function getSortMenuSorts();

    /**
     * Set named sorts used in sort menu.
     * 
     * @param array $sort_menu_sorts
     */
    public function setSortMenuSorts($sort_menu_sorts);

    
}
