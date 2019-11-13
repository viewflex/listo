<?php

namespace Viewflex\Listo\Publishers;

use Viewflex\Ligero\Publishers\PublisherApiTrait as LigeroPublisherApiTrait;

/**
 * Methods of the Listo PublisherApi class.
 */
trait PublisherApiTrait
{
    use LigeroPublisherApiTrait;

    /**
     * Returns a pager in logical or relative context.
     *
     * @param string $context
     * @return array
     */
    protected function pager($context = 'relative') {

        $found = $this->getQuery()->found();
        $limit = $this->getQueryLimit();
        $start = $this->getQueryStart(); // Get start based on start or page param, or 0.
        $route = $this->getRoute();

        $logical_offset = $start % $limit; // Offset from logical page start.
        $current_logical_start = $start - $logical_offset; // Logical page start.

        $current_logical_page = $this->logicalPage($start); // Logical page the starting row is on, offset or not.
        $num_logical_pages = $this->numPages($found, $limit);

        $current_relative_page = $this->relativePage($start); // Relative page the starting row is on, if not offset.
        $num_relative_pages = $logical_offset == 0 ? $this->numPages($found, $limit) : $this->numPages($found, $limit, $start);

        $nav_base_params = $this->getNavUrlBaseParameters();

        if ($context == 'logical') {

            $page_num = $current_logical_page;
            $num_pages = $num_logical_pages;
            $use_page_number = $this->getConfig()->getPaginationConfig()['use_page_number'];

            if ($current_logical_start < $limit) {

                if ($start === 0) {
                    // We're really on the first logical page.
                    $prev_page = $first_page = null;
                } else {
                    // Start is offset but less than limit, enable prev/first controls.
                    if ($use_page_number) {
                        $first_page = $route.$this->urlQueryWithPage($nav_base_params, strval(1));
                        $prev_page = $route.$this->urlQueryWithPage($nav_base_params, strval($current_logical_page - 1));
                    } else {
                        $first_page = $route.$this->urlQueryWithStart($nav_base_params, strval(0));
                        $prev_page = $route.$this->urlQueryWithStart($nav_base_params, strval(0));
                    }
                }

            } else {

                if ($use_page_number) {
                    $first_page = $route.$this->urlQueryWithPage($nav_base_params, strval(1));
                    $prev_page = $route.$this->urlQueryWithPage($nav_base_params, strval($current_logical_page - 1));
                } else {
                    $first_page = $route.$this->urlQueryWithStart($nav_base_params, strval(0));
                    $prev_page = $route.$this->urlQueryWithStart($nav_base_params, strval($current_logical_start - $limit));
                }
            }

            if (($current_logical_start + $limit) >= $found)
                $next_page = $last_page = null; // We're already on last page, offset or not.
            else {
                if ($use_page_number) {
                    $next_page = $route.$this->urlQueryWithPage($nav_base_params, strval($current_logical_page + 1));
                    $last_page = $route.$this->urlQueryWithPage($nav_base_params, strval( ceil($found / $limit) ));
                } else {
                    $next_page = $route.$this->urlQueryWithStart($nav_base_params, strval($current_logical_start + $limit));
                    $last_page = $route.$this->urlQueryWithStart($nav_base_params, strval((ceil($found / $limit) - 1) * $limit));
                }
            }
        }
        else { // relative

            $page_num = $current_relative_page;
            $num_pages = $num_relative_pages;

            if ($start < $limit) {
                if ($logical_offset == 0)
                    $prev_page = $first_page = null; // We're already on first page.
                else {
                    $first_page = $route.$this->urlQueryWithStart($nav_base_params, strval(0));
                    $prev_page = $route.$this->urlQueryWithStart($nav_base_params, strval($current_logical_start));
                }
            }
            else {
                $first_page = $route.$this->urlQueryWithStart($nav_base_params, strval(0));
                $prev_page = $route.$this->urlQueryWithStart($nav_base_params, strval($start - $limit));
            }

            if (($start + $limit) >= $found)
                $next_page = $last_page = null; // We're already on last page.
            else {
                $next_page = $route.$this->urlQueryWithStart($nav_base_params, strval($start + $limit));
                $last_page = $route.$this->urlQueryWithStart($nav_base_params, strval($start + ((ceil(($found - $start) / $limit) - 1) * $limit)));
            }
        }

        return [
            'context'           =>  $context,
            'pages'             =>  [
                'first'             =>  $first_page,
                'prev'              =>  $prev_page,
                'next'              =>  $next_page,
                'last'              =>  $last_page
            ],
            'page_num'          =>  $page_num,
            'num_pages'         =>  $num_pages
        ];
    }


    /**
     * Returns an array of page links, indexed by
     * page number with start and url for each.
     *
     * Context determines logical or relative
     * (ie: actual) page start positions.
     *
     * @param string $context
     * @return array
     * @internal param int $start
     */
    protected function pageMenu($context = 'relative')
    {
        $pages = [];
        $found = $this->getQuery()->found();
        $limit = $this->getQueryLimit();
        $start = $this->getQueryStart(); // Get start based on start or page param, or 0.
        $route = $this->getRoute();

        $logical_offset = $start % $limit; // Offset from logical page start.
        $current_logical_start = $start - $logical_offset; // Logical page start.

        $nav_base_params = $this->getNavUrlBaseParameters();
        $max_links = $this->getConfig()->getPaginationConfig()['page_menu']['max_links'];

        if($context == 'relative') {
            // Allow pagination relative to current start row.
            $using_start = $start;
            $use_page_number = false;
        } else {
            // Make links with logical pagination (ie: page 1 = start 0),
            // and if enabled use page number instead of start row.
            $using_start = $current_logical_start;
            $use_page_number = $this->getConfig()->getPaginationConfig()['use_page_number'];
        }

        // How many pages are in the results using given start?
        $num_pages = $this->numPages($found, $limit, $using_start);
        $num_page_links = $num_pages <= $max_links ? $num_pages : $max_links;

        // Derive relative (or logical, depending on start) page number of current start.
        $current_page = $this->relativePage($using_start);

        // Current page (disabled unless there is an offset in logical context).
        $pg = $current_page;

        if (($context == 'logical') && ($logical_offset)) {
            // We're offset from logical page start, so enable page nav to regain logical pagination.
            if($use_page_number) {
                $url = $route.$this->urlQueryWithPage($nav_base_params, strval($this->logicalPage($current_logical_start)));
            } else {
                $url = $route.$this->urlQueryWithStart($nav_base_params, strval($current_logical_start));
            }
        } else {
            $url = null;
        }

        $pages = array_add($pages, $pg, ['start' => $start, 'url' => $url]);

        // Previous pages...
        $num_prev_links = intval(floor($num_page_links / 2));

        $prev = 0;
        if ($using_start > $limit) {

            $i = 1;
            $proceed = true;
            while (($i <= $num_prev_links) && ($proceed)) {

                $pg = $current_page - $i;
                $start_row = $using_start - ($i * $limit);

                if ($start_row < 0) {
                    $start_row = 0;
                    $proceed = false;
                }

                if($use_page_number) {
                    $url = $route.$this->urlQueryWithPage($nav_base_params, strval($pg));
                } else {
                    $url = $route.$this->urlQueryWithStart($nav_base_params, strval($start_row));
                }

                $pages = array_add($pages, $pg, ['start' => $start_row, 'url' => $url]);

                $prev++;
                $i++;
            }
        }

        // Next pages...
        $num_next_links = $num_page_links - count($pages);

        $next = 0;
        $i = 1;
        $proceed = true;
        while (($i <= $num_next_links) && ($proceed)) {

            $pg = $current_page + $i;
            $start_row = $using_start + ($i * $limit);

            if ($start_row + 1 > $found) {
                $proceed = false;
            }
            else {

                if($use_page_number) {
                    $url = $route.$this->urlQueryWithPage($nav_base_params, strval($pg));
                } else {
                    $url = $route.$this->urlQueryWithStart($nav_base_params, strval($start_row));
                }

                $pages = array_add($pages, $pg, ['start' => $start_row, 'url' => $url]);
            }

            $next++;
            $i++;
        }

        // More previous pages?...
        $num_extra_links = $num_page_links - count($pages);

        $i = 1;
        $proceed = true;
        while (($i <= $num_extra_links) && ($proceed)) {

            $pg = $current_page - ($i + $prev);
            $start_row = $using_start - (($i + $prev) * $limit);

            if ($start_row < 0) {
                $start_row = 0;
                $proceed = false;
            }

            if($use_page_number) {
                $url = $route.$this->urlQueryWithPage($nav_base_params, strval($pg));
            } else {
                $url = $route.$this->urlQueryWithStart($nav_base_params, strval($start_row));
            }

            $pages = array_add($pages, $pg, ['start' => $start_row, 'url' => $url]);

            $i++;
        }

        ksort($pages);

        return [
            'context'           =>  $context,
            'pages'             =>  $pages,
            'page_num'          =>  $current_page,
            'num_pages'         =>  $num_pages
        ];
    }



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
    public function getBreadcrumbs()
    {
        if ($this->getConfig()->getControl('breadcrumbs')) {

            $config = $this->getConfig()->getBreadcrumbsConfig();
            $scope_params = []; // The cumulative array of scope params for breadcrumbs.
            $scope = $config['scope'];
            $base_params = $this->getUrlBaseParameters();

            $display_params = $this->getUrlDisplayParameters($config, $base_params);

            // Let's get started...
            $route = $this->getRoute();
            $crumbs = [];

            $home_label = $this->getConfig()->getDomain();

            $url = $route.$this->urlQueryString($display_params);
            $home_crumb = [
                'home' => [
                    'display' => $home_label,
                    'url' => $url
                ]
            ];
            if ($config['show_home'])
                $crumbs = $home_crumb;

            $up = [];
            $deepest = [];
            $title = '';

            // Loop through specified crumb keys...
            foreach ($config['parameters'] as $key) {

                // If available in base_parameters, build a breadcrumb and titlecrumb for this parameter.
                // If using query scope, add this param to cumulative stack of values comprising scope.
                if (array_key_exists($key, $base_params)) {

                    $url_base_params = array_add($scope_params, $key, $base_params[$key]);

                    if ($scope == 'query')
                        $scope_params = array_add($scope_params, $key, $base_params[$key]);

                    // Make the crumb.
                    $url = $route.$this->urlQueryString(array_merge($url_base_params, $display_params));
                    $crumbs[$key] = [
                        'display' => $base_params[$key],
                        'url' => $url
                    ];

                    $title = $this->crumbsTitle($crumbs);

                    // Save next-to-last and last crumbs for link header
                    // and other uses, omitting sort/view/limit params.
                    $generic_url = $route.$this->urlQueryString($url_base_params);
                    $up = !$deepest ? [
                        'parameter' => 'catalog',
                        'display' => $home_label,
                        'url' => $route
                    ] : $deepest;
                    $deepest = [
                        'parameter' => $key,
                        'display' => $title,
                        'url' => $generic_url
                    ];
                }
            }

            $titlecrumbs_sep = $this->getConfig()->ls('ui.symbol.separator.titlecrumbs');

            if (!$title)
                $title = $this->crumbsTitle($crumbs);


            $query_params = $this->getQueryParameters();


            $self = $this->urlSelf();

            return [
                'config'            =>  [
                    'scope'             =>  $config['scope'],
                    'persist_sort'      =>  $config['persist_sort'],
                    'persist_view'      =>  $config['persist_view'],
                    'show_home'         =>  $config['show_home'],
                    'home_label'        =>  $home_label,
                    'separators'        =>  [
                        'breadcrumbs'       =>  $this->getConfig()->ls('ui.symbol.separator.breadcrumbs'),
                        'titlecrumbs'       =>  $titlecrumbs_sep
                    ],
                    'text_ending'       =>  $config['text_ending']
                ],
                'route'             =>  $route,
                'base_parameters'   =>  $base_params,
                'crumbs'            =>  $crumbs,
                'title'             =>  $title,
                'rel'               =>  [
                    'deepest'           =>  $deepest,
                    'up'                =>  $up,
                    'self'              =>  $self
                ]
            ];
        }

        return null;
    }

    /**
     * Get raw contextual menu values along with
     * their configurations and current logical
     * query states, for use as API response.
     *
     * @return mixed
     */
    public function getQueryMenus()
    {
        if ($this->getConfig()->getControl('query_menus')) {

            // Translate bool|int|string boolean value to string 'true' or 'false'.
            $boolStr = function($v) {
                if ($v !== null) {
                    $v = (($v === false) || ($v === 0) || ($v === '0')) ? 'false' : $v;
                    $v = (($v === true) || ($v === 1) || ($v === '1')) ? 'true' : $v;
                }
                return $v;
            };

            $route = $this->getRoute();
            $config = $this->getConfig()->getQueryMenusConfig();
            $base_params = $this->getUrlBaseParameters();
            if (!$config['persist_keyword'])
                $base_params = array_except($base_params, 'keyword');

            // Get params for sort, view and limit, as determined by config.
            $display_params = $this->getUrlDisplayParameters($config, $base_params);

            // Make a menu for each column specified in config.
            $menus =[];
            foreach ($config['menus'] as $column => $column_config) {

                // Get distinct values for the column.
                $values = $this->query->getDistinctColumn($column, $column_config['scope'], $column_config['type']);

                // Make a menu of GET URLs for the current column.
                // Boolean columns get localized display keys,
                // plus menu with raw values for POST forms.
                $menu = [];
                $raw_menu = [];
                foreach ($values as $value) {

                    // Use this value as the url query parameter for the column menu
                    // item we're building, overwriting existing value (if any).
                    switch ($column_config['scope']) {
                        case 'global':
                            $use_params = array_merge([$column => $value], $display_params);
                            break;

                        default: // query scope
                            $use_params = array_merge(array_except($base_params, $column), [$column => $value], $display_params);
                            break;
                    }

                    // Make the menu item with appropriate display key.
                    switch ($column_config['type']) {
                        case 'string':
                            $menu[$value] = $route.$this->urlQueryString($use_params);
                            break;

                        case 'boolean':
                            // Use localized true/false string as key for boolean.
                            $menu[$this->getConfig()->ls('ui.'.$column.'_'.$boolStr($value))] = $route.$this->urlQueryString($use_params);
                            $raw_menu[$this->getConfig()->ls('ui.'.$column.'_'.$boolStr($value))] = $value;
                            break;

                        default: // numeric or other type
                            $menu[$value] = $route.$this->urlQueryString($use_params);
                            break;
                    }
                }

                // Get parameters for menu's 'All' URL.
                switch ($column_config['scope']) {
                    case 'global':
                        $use_params = $display_params;
                        break;

                    default: // query scope
                        $use_params = array_merge(array_except($base_params, $column), $display_params);
                        break;
                }

                // Get currently 'selected' value of the menu or null.
                if (array_key_exists($column, $base_params)) {

                    // Use localized true/false string as key for boolean.
                    if ($column_config['type'] == 'boolean')
                        $selected = $this->getConfig()->ls('ui.'.$column.'_'.$boolStr($base_params[$column]));
                    else
                        $selected = $base_params[$column];

                } else
                    $selected = null;

                // Make 'All' URL for the column's menu.
                $all = $route.$this->urlQueryString(array_except($use_params, $column));

                // Assemble menu data for the column.
                $column_menu = [
                    'config'        =>  [
                        'scope'         =>  $column_config['scope'],
                        'type'          =>  $column_config['type'],
                        'show_all'      =>  $column_config['show_all'],
                        'all_label'     =>  $this->getConfig()->ls('ui.all.'.$column)
                    ],
                    'menu'          =>  $menu,
                    'selected'      =>  $selected,
                    'all'           =>  [
                        'display'       =>  $this->getConfig()->ls('ui.all.'.$column),
                        'url'           =>  $all
                    ]
                ];

                // For boolean columns, add 'raw' values array (for HTML select menus in POST forms).
                // For 'All' option, use same display string as key, and empty string as 'All' value.
                if ($column_config['type'] == 'boolean') {
                    $column_menu['raw'] = $raw_menu;
                }

                $menus = array_add($menus, $column, $column_menu);
            }

            return [
                'config'            =>  [
                    'persist_sort'      =>  $config['persist_sort'],
                    'persist_view'      =>  $config['persist_view'],
                    'persist_keyword'   =>  $config['persist_keyword']
                ],
                'route'             =>  $route,
                'base_parameters'   =>  $base_params,
                'menus'             =>  $menus

            ];

        }

        return null;
    }

    /**
     * Get sort menu, including currently selected
     * menu item and localized item display names.
     *
     * @return mixed
     */
    public function getSortMenu()
    {
        if ($this->getConfig()->getControl('sort_menu')) {

            $route = $this->getRoute();
            $sorts = [];
            $params = $this->getQueryParameters();
            $base_params = $this->getUrlBaseParameters();

            $selected = array_key_exists('sort', $params) ? $params['sort'] : null;
            $selected_display = $selected ? $this->getConfig()->ls('ui.sorts.'.$selected) : '';

            $defaults = array_filter($this->getConfig()->getQueryDefaults(), 'strlen');
            $sort_codes = $this->getConfig()->getSortMenuSorts();
            foreach($sort_codes as $code) {

                $display = $this->getConfig()->ls('ui.sorts.'.$code);

                if(array_key_exists('sort', $defaults) && ($defaults['sort'] == $code)) {
                    $url = $route . $this->urlQueryString(array_except($base_params, 'sort'));
                } else {
                    $url = $route . $this->urlQueryString(array_merge(array_except($base_params, 'sort'), ['sort' => $code]));
                }

                $sorts = array_add($sorts, $code, ['display' => $display,  'url' => $url]);
            }

            return [
                'route'             =>  $route,
                'base_parameters'   =>  $base_params,
                'sorts'             =>  $sorts,
                'selected'          =>  $selected,
                'selected_display'  =>  $selected_display,
                'label_sort_by'     =>  $this->getConfig()->ls('ui.label.sort_by')
            ];


        }

        return null;
    }

    /**
     * Returns all publisher API data bundles together, along with query info.
     *
     * @return array
     */
    public function getData()
    {
        return [
            'query'                     =>  $this->getQueryInfo(),
            'items'                     =>  $this->getItems(),
            'pagination'                =>  $this->getPagination(),
            'breadcrumbs'               =>  $this->getBreadcrumbs(),
            'query_menus'               =>  $this->getQueryMenus(),
            'keyword_search'            =>  $this->getKeywordSearch(),
            'sort_menu'                 =>  $this->getSortMenu()
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Dynamically Formatted Results
    |--------------------------------------------------------------------------
    */

    /**
     * Returns all publisher data bundles together, along with query info.
     *
     * @return array
     */
    public function presentData()
    {
        return [
            'query'                     =>  $this->getQueryInfo(),
            'items'                     =>  $this->presentItems(),
            'pagination'                =>  $this->getPagination(),
            'breadcrumbs'               =>  $this->getBreadcrumbs(),
            'query_menus'               =>  $this->getQueryMenus(),
            'keyword_search'            =>  $this->getKeywordSearch(),
            'sort_menu'                 =>  $this->getSortMenu()
        ];
    }
    

    /*
    |--------------------------------------------------------------------------
    | Utility Functions
    |--------------------------------------------------------------------------
    */

    /**
     * Returns a proper location title from an array of breadcrumbs.
     *
     * @param array $crumbs
     * @return string
     */
    public function crumbsTitle($crumbs = [])
    {
        $title = '';
        if (count($crumbs) > 0) {
            $sep = $this->getConfig()->ls('ui.symbol.separator.titlecrumbs');

            $i = 0;
            foreach ($crumbs as $key => $values) {
                $title .= $i == 0 ? $values['display'] : $sep.$values['display'];
                $i++;
            }
        }

        return $title;
    }
    
}
