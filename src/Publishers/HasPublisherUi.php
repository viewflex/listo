<?php

namespace Viewflex\Listo\Publishers;

use Viewflex\Ligero\Publishers\HasPublisherUi as HasLigeroPublisherUi;

trait HasPublisherUi
{
    use HasLigeroPublisherUi;
    
    /*
    |--------------------------------------------------------------------------
    | Composer Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Use the bootstrap trait to generate some fancy UI elements from data.
     *
     * @return array|null
     */
    public function composeListing()
    {
        return [
            'info' => $this->getConfig()->ls('ui.results.viewing')
                .' '.$this->publisher->getPagination()['viewing']['first']
                .$this->getConfig()->ls('ui.symbol.range').$this->publisher->getPagination()['viewing']['last']
                .' '.$this->getConfig()->ls('ui.results.of').' '.$this->publisher->found()
                .' '.$this->getConfig()->ls('ui.results.records', $this->publisher->found()),
            'page_menu' => $this->pageNav(),
            'breadcrumbs' => $this->breadcrumbs(),
            'query_dropdowns' => $this->queryMenus(),
            'keyword_search' => $this->keywordSearch(),
            'sort_menu' => $this->sortMenu(),
            'view_menu' => $this->viewMenu(),
            'items' => $this->publisher->presentItems(),
            'message' => $this->getMessage(),
            'path' => $this->currentRouteUrlDir(),
            'domain' => $this->getConfig()->getDomain(),
            'trans_prefix' => $this->getConfig()->getTranslationPrefix(),
            'namespace' => $this->getConfig()->getResourceNamespace(),
            'view_prefix' => $this->getConfig()->getDomainViewPrefix(),
            'title' => 'Search Results',
            'query_info' => $this->publisher->getQueryInfo()
        ];
    }

    /**
     * Return array of view data, determined by the current controller method.
     * Actions 'store', 'update', and 'delete' are silent with redirects back.
     * Actions 'edit', 'show', and 'index' query db, while 'create' does not.
     *
     * @return array|null
     */
    public function composeItem()
    {
        $action_method = $this->currentRouteActionMethod();

        $data = [
            'query' => null,
            'item' => null,
            'message' => $this->getMessage(),
            'path' => $this->currentRouteUrlDir(),
            'domain' => $this->getConfig()->getDomain(),
            'trans_prefix' => $this->getConfig()->getTranslationPrefix(),
            'namespace' => $this->getConfig()->getResourceNamespace(),
            'view_prefix' => $this->getConfig()->getDomainViewPrefix(),
            'read_only' => false,
            'back_to' => $this->getBackTo(),
            'query_info' => null,
            'action_method' => $action_method
        ];

        switch ($action_method) {

            case 'show': // Route: (GET) {uri}/{$id} - query and show. No form action uri.
                $data['query'] = $this->publisher->getQueryInfo();
                $data['item'] = $this->publisher->getItems()[0];
                $data['form_action'] = 'show';
                $action_message = $this->getConfig()->ls('ui.title.view_domain_record', ['domain' => $this->getConfig()->getDomain()])
                    .' #'.$this->getRequest()->getQueryInput('id');
                $data['title'] = $action_message;
                $data['info'] = $action_message.': ';
                $data['read_only'] = true;
                break;

            case 'create': // Route: (GET) {uri}/create - show new item form, use inputs if provided.
                $data['item'] = $this->getRequest()->except('id');

                // Use {uri}/store as form action uri.
                $form_action = $this->currentRouteUrlDir().'/store';

                $data['form_action'] = $form_action;
                $action_message = $this->getConfig()->ls('ui.title.new_domain_record', ['domain' => $this->getConfig()->getDomain()]);
                $data['title'] = $action_message;
                $data['info'] = $action_message.': ';
                break;

            case 'edit': // Route: (GET) {uri}/{id}/edit - query and show.
                $data['query'] = $this->publisher->getQueryInfo();
                $data['item'] = $this->publisher->getItems()[0];

                // Use {uri}/{id} as form action uri.
                $form_action = $this->currentRouteUrlDir();

                $data['form_action'] = $form_action;
                $action_message = $this->getConfig()->ls('ui.title.update_domain_record', ['domain' => $this->getConfig()->getDomain()])
                    .' #'.$this->getRequest()->getQueryInput('id');
                $data['title'] = $action_message;
                $data['info'] = $action_message.': ';
                break;

            case 'store': // Route: (POST) {uri}/store - no composition
                break;

            case 'update': // Route: (PUT) {uri}/{id} - no composition
                break;

            case 'destroy': // Fall through to delete

            case 'delete': // Route: (DELETE) {uri}/{id} - no composition
                break;
        }

        return $data;

    }
    
}