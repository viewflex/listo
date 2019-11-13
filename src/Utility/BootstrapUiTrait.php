<?php

namespace Viewflex\Listo\Utility;

use Viewflex\Ligero\Utility\BootstrapUiTrait as LigeroBootstrapUiTrait;

/**
 * Methods for generating publisher display and UI elements using bootstrap.css.
 */
trait BootstrapUiTrait
{
    use LigeroBootstrapUiTrait;

    /**
     * Using the array of crumbs from publisher API,
     * this helper function makes a breadcrumbs
     * display for use with Bootstrap.css.
     *
     * @return string
     */
    public function breadcrumbs()
    {
        $crumbs = $this->publisher->getBreadcrumbs()['crumbs'];

        $html = '';
        $num_crumbs = count($crumbs);
        if ($num_crumbs > 0) {

            $html = "\n<ol class=\"breadcrumb\">";
            $i = 1;
            foreach ($crumbs as $key => $values) {

                if (($i == $num_crumbs) && ($this->publisher->getBreadcrumbs()['config']['text_ending']))
                    $html .= "\n    <li class=\"active\">".$values['display']."</li>";
                else
                    $html .= "\n    <li><a href=\"".$values['url']."\">".$values['display']."</a></li>";

                $i++;
            }

            $html .= "\n</ol>\n\n";
        }

        return $html;
    }

    /**
     * Get location titlecrumbs string from breadcrumbs.
     * 
     * @return mixed
     */
    public function title()
    {
        return $this->publisher->getBreadcrumbs()['title'];
    }

    /**
     * Returns a group of menus as Bootstrap button dropdowns.
     * In each button dropdown, the 'All' label is the label
     * for the button initially, but when 'selected' has a
     * value, that value is displayed instead, and the
     *  'All' choice is prepended to the menu itself.
     *
     * @return array
     */
    public function queryMenus()
    {
        $api_menus = $this->publisher->getQueryMenus()['menus'];
        $display_menus = [];

        $menu_num = 0;
        foreach ($api_menus as $column => $data) {

            $menu_num++;
            $display_menu_items = [];
            $display_menu = "\n    <li class=\"dropdown\">";

            $menu = $data['menu']; // the distinct choices and their urls

            if ($data['selected']) {
                $button_label = $data['selected'];

                if ($data['config']['show_all']) {
                    $display_menu_items[] = "\n            <li><a href=\"".$data['all']['url']."\">".
                        $data['config']['all_label']."</a></li>";
                }

            } else {
                $button_label = $data['config']['all_label'];
            }

            $display_menu .= "\n        <a href=\"\" class=\"dropdown-toggle\" role=\"button\" id=\"queryMenu".
                strval($menu_num)."\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">".
                $button_label." <span class=\"caret\"></span></a>";


            $display_menu .= "\n        <ul class=\"dropdown-menu\" aria-labelledby=\"queryMenu".strval($menu_num)."\">";

            foreach ($menu as $display => $url) {
                $display_menu_items[] = "\n            ".($display === $data['selected'] ? "<li class=\"disabled\">" : "<li>").
                    "<a href=\"".($display === $data['selected'] ? "#" : $url)."\">".$display."</a></li>";
            }

            foreach ($display_menu_items as $item) {
                $display_menu .= $item;
            }

            $display_menu .= "\n        </ul>\n    </li>\n";
            $display_menus[] = ['column' => $column, 'dropdown' => $display_menu];
        }

        return $display_menus;
    }

    /**
     * Returns a sort menu as Bootstrap dropdown.
     *
     * @return string
     */
    public function sortMenu()
    {
        $data = $this->publisher->getSortMenu();
        $button_label = $data['selected'] ? ($data['label_sort_by'].' '.$data['selected_display']) : $data['label_sort_by'];

        $html = "\n    <li class=\"dropdown\">";

        $html .= "\n        <a href=\"\" class=\"dropdown-toggle\" role=\"button\" id=\"sortMenu".
            "\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">".
            $button_label." <span class=\"caret\"></span></button>";

        $html .= "\n        <ul class=\"dropdown-menu\" aria-labelledby=\"sortMenu\">";

        foreach ($data['sorts'] as $code => $sort) {
            $html .= "\n            ".($code == $data['selected'] ? "<li class=\"disabled\">" : "<li>").
                "<a href=\"".($code == $data['selected'] ? "#" : $sort['url'])."\">".$sort['display']."</a></li>";
        }

        $html .= "\n        </ul>\n    </li>\n";

        return $html;
    }
    
}
