<?php

$common = include('common.php');
$override = [

    /*
    |--------------------------------------------------------------------------
    | Domain Localization
    |--------------------------------------------------------------------------
    */

    ## Labels for columns and values:

    'id'                    => 'ID',
    'active'                => 'Active',
    'name'                  => 'Name',
    'category'              => 'Category',
    'subcategory'           => 'Subcategory',
    'description'           => 'Description',
    'price'                 => 'Price',
    'created_at'            => 'Created At',
    'updated_at'            => 'Updated At',


    ## Strings for true and false values of boolean columns,
    ## keyed by this format: column_name.{'_true'|'_false'}:

    'active_true'           => 'Active',
    'active_false'          => 'Inactive',


    ## Text for 'All' choices in query menus:

    'all'                   => [
        'active'                => 'All',
        'category'              => 'Categories',
        'subcategory'           => 'Subcategories'
    ],


    ## Labels for named sorts defined in config:

    'sorts'                 => [
        'default'               => 'Default',
        'id'                    => 'ID',
        'active'                => 'Active',
        'name'                  => 'Name',
        'category'              => 'Category',
        'subcategory'           => 'Subcategory',
        'price'                 => 'Price',
    ]
    
    
    
];

return [
    'ui' => array_merge($common, $override)
];
