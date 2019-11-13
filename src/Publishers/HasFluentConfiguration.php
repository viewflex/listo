<?php

namespace Viewflex\Listo\Publishers;

use Viewflex\Ligero\Publishers\HasFluentConfiguration as HasLigeroFluentConfiguration;

/**
 * Convenient fluent configuration of Publisher components.
 */
trait HasFluentConfiguration
{
    
    use HasLigeroFluentConfiguration;
    
    
    ## ----- Breadcrumbs, Query Menus, Sort Menu

    /**
     * @param array $breadcrumbs_config
     * @return $this
     */
    public function setBreadcrumbsConfig($breadcrumbs_config)
    {
        $this->getConfig()->setBreadcrumbsConfig($breadcrumbs_config);
        return $this;
    }

    /**
     * @param array $query_menus_config
     * @return $this
     */
    public function setQueryMenusConfig($query_menus_config)
    {
        $this->getConfig()->setQueryMenusConfig($query_menus_config);
        return $this;
    }

    /**
     * @param array $sort_menu_sorts
     * @return $this
     */
    public function setSortMenuSorts($sort_menu_sorts)
    {
        $this->getConfig()->setSortMenuSorts($sort_menu_sorts);
        return $this;
    }
    
}
