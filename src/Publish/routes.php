<?php

// These routes are endpoints for the Listo demo.


// UI Controller Routes:

// Special actions...
Route::get('listo/items/json', array('as' => 'listo.items.json', 'uses' => '\Viewflex\Listo\Demo\Items\ItemsController@json', 'middleware' => 'web'));
Route::get('listo/items/action', array('as' => 'listo.items.action', 'uses' => '\Viewflex\Listo\Demo\Items\ItemsController@action', 'middleware' => 'web'));

// Resourceful actions...
Route::get('listo/items', array('as' => 'listo.items.index', 'uses' => '\Viewflex\Listo\Publish\Demo\Items\ItemsController@index', 'middleware' => 'web'));
Route::get('listo/items/create', array('as' => 'listo.items.create', 'uses' => '\Viewflex\Listo\Publish\Demo\Items\ItemsController@create', 'middleware' => 'web'));
Route::get('listo/items/{id}', array('as' => 'listo.items.show', 'uses' => '\Viewflex\Listo\Publish\Demo\Items\ItemsController@show', 'middleware' => 'web'));
Route::get('listo/items/{id}/edit', array('as' => 'listo.items.edit', 'uses' => '\Viewflex\Listo\Publish\Demo\Items\ItemsController@edit', 'middleware' => 'web'));
Route::post('listo/items/store', array('as' => 'listo.items.store', 'uses' => '\Viewflex\Listo\Publish\Demo\Items\ItemsController@store', 'middleware' => 'web'));
Route::put('listo/items/{id}', array('as' => 'listo.items.update', 'uses' => '\Viewflex\Listo\Publish\Demo\Items\ItemsController@update', 'middleware' => 'web'));
Route::delete('listo/items/{id}', array('as' => 'listo.items.destroy', 'uses' => '\Viewflex\Listo\Publish\Demo\Items\ItemsController@destroy', 'middleware' => 'web'));


// API Controller Routes:

// Standard CRUD domain actions...
Route::post('api/listo/{key}/find', array('as' => 'api.listo.find', 'uses' => '\Viewflex\Listo\Controllers\ContextApiController@find', 'middleware' => 'api'));
Route::post('api/listo/{key}/findby', array('as' => 'api.listo.findby', 'uses' => '\Viewflex\Listo\Controllers\ContextApiController@findBy', 'middleware' => 'api'));
Route::post('api/listo/{key}/store', array('as' => 'api.listo.store', 'uses' => '\Viewflex\Listo\Controllers\ContextApiController@store', 'middleware' => 'api'));
Route::post('api/listo/{key}/update', array('as' => 'api.listo.update', 'uses' => '\Viewflex\Listo\Controllers\ContextApiController@update', 'middleware' => 'api'));
Route::post('api/listo/{key}/delete', array('as' => 'api.listo.destroy', 'uses' => '\Viewflex\Listo\Controllers\ContextApiController@destroy', 'middleware' => 'api'));

// Custom context actions (defaulting to standard CRUD domain actions)...
Route::post('api/listo/{key}/context/find', array('as' => 'api.listo.context.find', 'uses' => '\Viewflex\Listo\Controllers\ContextApiController@findContext', 'middleware' => 'api'));
Route::post('api/listo/{key}/context/findby', array('as' => 'api.listo.context.findby', 'uses' => '\Viewflex\Listo\Controllers\ContextApiController@findContextBy', 'middleware' => 'api'));
Route::post('api/listo/{key}/context/store', array('as' => 'api.listo.context.store', 'uses' => '\Viewflex\Listo\Controllers\ContextApiController@storeContext', 'middleware' => 'api'));
Route::post('api/listo/{key}/context/update', array('as' => 'api.listo.context.update', 'uses' => '\Viewflex\Listo\Controllers\ContextApiController@updateContext', 'middleware' => 'api'));
Route::post('api/listo/{key}/context/delete', array('as' => 'api.listo.context.destroy', 'uses' => '\Viewflex\Listo\Controllers\ContextApiController@destroyContext', 'middleware' => 'api'));

