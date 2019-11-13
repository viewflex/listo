<?php

namespace Viewflex\Listo\Publishers;

use Viewflex\Ligero\Publishers\PublisherTrait as LigeroPublisherTrait;

/**
 * Methods of the Listo Publisher class.
 */
trait PublisherTrait
{
    use LigeroPublisherTrait;
    
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
    public function getBreadcrumbs()
    {
        return $this->getApi()->getBreadcrumbs();
    }

    /**
     * Get raw contextual API menu values along with
     * their configurations and current logical
     * query states, for use as API response.
     *
     * @return mixed
     */
    public function getQueryMenus()
    {
        return $this->getApi()->getQueryMenus();
    }

    /**
     * Get API sort menu, including currently selected
     * menu item and localized item display names.
     *
     * @return mixed
     */
    public function getSortMenu()
    {
        return $this->getApi()->getSortMenu();
    }
    
}
