<?php

namespace Viewflex\Listo\Base;

use Viewflex\Ligero\Base\BasePublisherConfig as LigeroBasePublisherConfig;
use Viewflex\Listo\Contracts\PublisherConfigInterface;

/**
 * The base Publisher Config class, used as the default.
 * Modify values via setters, or extend and customize.
 * Can override global defaults via config/env files.
 */
class BasePublisherConfig extends LigeroBasePublisherConfig implements PublisherConfigInterface
{

    /**
     * @var string
     */
    protected $package = 'listo';

    /*
    |--------------------------------------------------------------------------
    | Domain, Resource Namespaces, Translation
    |--------------------------------------------------------------------------
    */

    /**
     * @var string
     */
    protected $domain = 'Listo';

    /**
     * @var string
     */
    protected $resource_namespace = 'listo';

    /**
     * @var string
     */
    protected $translation_file = '';

    /*
    |--------------------------------------------------------------------------
    | Query Config - Table, Model
    |--------------------------------------------------------------------------
    */

    /**
     * @var string
     */
    protected $table_name = 'listo_items';

    /**
     * @var string
     */
    protected $model_name = 'Viewflex\Listo\Base\BaseModel';
    
    /*
    |--------------------------------------------------------------------------
    | Toggle for UI Controls
    |--------------------------------------------------------------------------
    */

    /**
     * @var array
     */
    protected $controls = [
        'pagination'        => false,
        'breadcrumbs'       => false,
        'query_menus'       => false,
        'keyword_search'    => false,
        'sort_menu'         => false
    ];

    /*
    |--------------------------------------------------------------------------
    | Breadcrumbs
    |--------------------------------------------------------------------------
    */

    /**
     * Configuration for generating breadcrumbs and titlecrumbs elements.
     *
     * Specify which query parameters to process, in the desired order.
     *
     * Specify the search scope (global|query), persistence of sort
     * and view parameters in generated urls, whether to begin with
     * 'home' link, and whether to show last breadcrumb as text.
     *
     * @var array
     */
    protected $breadcrumbs_config = [
        'parameters'                =>  [
            'keyword'
        ],
        'scope'                     =>  'query',
        'persist_sort'              =>  true,
        'persist_view'              =>  true,
        'show_home'                 =>  true,
        'text_ending'               =>  false
    ];

    /**
     * @return array
     */
    public function getBreadcrumbsConfig()
    {
        return $this->breadcrumbs_config;
    }

    /**
     * @param array $breadcrumbs_config
     */
    public function setBreadcrumbsConfig($breadcrumbs_config)
    {
        $this->breadcrumbs_config = $breadcrumbs_config;
    }
    
    /*
    |--------------------------------------------------------------------------
    | Query Menus
    |--------------------------------------------------------------------------
    */

    /**
     * List of menus to generate for key search fields.
     * Specify scope (global|query) and data type,
     * whether to persist sort, view/limit, keyword.
     *
     * The 'show_all' option adds 'All' choice to menu.
     * Sometimes you may prefer not to offer that choice, eg:
     * to present the domain by sections, not all at once.
     *
     * Any indexed string, boolean, or numeric columns can
     * be used. Caching is used if the option is enabled.
     *
     * @var array
     */
    protected $query_menus_config = [
        'menus'             => [
            'category'          => ['scope' => 'query', 'type' => 'string', 'show_all' => true],
            'subcategory'       => ['scope' => 'query', 'type' => 'string', 'show_all' => true],
            'active'            => ['scope' => 'query', 'type' => 'boolean', 'show_all' => true]
        ],
        'persist_sort'      =>  true,
        'persist_view'      =>  true,
        'persist_keyword'   =>  false
    ];

    /**
     * @return array
     */
    public function getQueryMenusConfig()
    {
        return $this->query_menus_config;
    }

    /**
     * @param array $query_menus_config
     */
    public function setQueryMenusConfig($query_menus_config)
    {
        $this->query_menus_config = $query_menus_config;
    }

    /*
    |--------------------------------------------------------------------------
    |  Sort Menu
    |--------------------------------------------------------------------------
    */
    
    /**
     * Which 'order by' choices should be offered? The $sorts array below defines the list
     * of valid choices, and their actions. The 'default' sort is used internally when
     * neither the configured defaults nor the user input contains a sort parameter.
     *
     * @var array
     */
    protected $sort_menu_sorts = [
        'default',
        'id'
    ];

    /**
     * @return array
     */
    public function getSortMenuSorts()
    {
        return $this->sort_menu_sorts;
    }

    /**
     * @param array $sort_menu_sorts
     */
    public function setSortMenuSorts($sort_menu_sorts)
    {
        $this->sort_menu_sorts = $sort_menu_sorts;
    }


}
