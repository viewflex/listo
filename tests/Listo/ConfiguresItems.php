<?php

$this
    ->setDomain('Items')
    ->setTranslationFile('items')
    ->setTableName('listo_items')
    ->setModelName('Viewflex\Listo\Publish\Demo\Items\Item')
    ->setResultsColumns([
        'id',
        'active',
        'name',
        'category',
        'subcategory',
        'description',
        'price'
    ])
    ->setWildcardColumns([
        'category'
    ])
    ->setControls([
        'pagination'        => true,
        'breadcrumbs'       => true,
        'query_menus'       => true,
        'keyword_search'    => true,
        'sort_menu'         => true
    ])
    ->setPaginationConfig([
        'pager'             =>  [
            'make'              =>  true,
            'context'           =>  'relative',
        ],
        'page_menu'         =>  [
            'make'              =>  true,
            'context'           =>  'relative',
            'max_links'         =>  5
        ],
        'view_menu'         =>  [
            'make'              =>  true,
            'context'           =>  'relative',
        ],
        'use_page_number'   =>  false
    ])
    ->setBreadcrumbsConfig([
        'parameters'        => [
            'category',
            'subcategory',
            'keyword'
        ],
        'scope'             =>  'query',
        'persist_sort'      =>  true,
        'persist_view'      =>  true,
        'show_home'         =>  true,
        'text_ending'       =>  false
    ])
    ->setQueryMenusConfig([
        'menus'             => [
            'category'          => ['scope' => 'query', 'type' => 'string', 'show_all' => true],
            'subcategory'       => ['scope' => 'query', 'type' => 'string', 'show_all' => true],
            'active'            => ['scope' => 'query', 'type' => 'boolean', 'show_all' => true]
        ],
        'persist_sort'      =>  true,
        'persist_view'      =>  true,
        'persist_keyword'   =>  false
    ])
    ->setKeywordSearchColumns([
        'name',
        'category',
        'subcategory',
        'description'
    ])
    ->setSorts([
        'default'           => ['id' => 'asc'],
        'id'                => ['id' => 'asc', 'name' => 'asc'],
        'name'              => ['name' => 'asc', 'id' => 'asc'],
        'category'          => ['category' => 'asc', 'id' => 'asc'],
        'subcategory'       => ['subcategory' => 'asc', 'id' => 'asc'],
        'price'             => ['price' => 'asc', 'id' => 'asc']
    ])
    ->setSortMenuSorts([
        'default',
        'id',
        'name',
        'category',
        'subcategory',
        'price'
    ])
    ->setQueryRules([
        'id'                => 'numeric|min:1',
        'active'            => 'boolean',
        'name'              => 'max:60',
        'category'          => 'max:25',
        'subcategory'       => 'max:25'
    ])
    ->setRequestRules([
        'id'                => 'numeric|min:1',
        'active'            => 'boolean',
        'name'              => 'max:60',
        'category'          => 'max:25',
        'subcategory'       => 'max:25',
        'description'       => 'max:250',
        'price'             => 'numeric'
    ])
;