<?php

namespace Viewflex\Listo;

use Illuminate\Support\ServiceProvider;

class ListoServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		$resource_namespace = 'listo';

		/*
    	|--------------------------------------------------------------------------
    	| Set the Default Internal Namespace for Translations and Views
    	|--------------------------------------------------------------------------
    	*/

		$this->loadTranslationsFrom(__DIR__ . '/Resources/lang', $resource_namespace);
		$this->loadViewsFrom(__DIR__ . '/Resources/views', $resource_namespace);

        /*
        |--------------------------------------------------------------------------
        | Publish Routes File
        |--------------------------------------------------------------------------
        */

        $this->publishes([
            __DIR__.'/Publish/routes.php' => base_path('publish/viewflex/listo/routes.php')
        ], 'listo-routes');
        
		/*
    	|--------------------------------------------------------------------------
    	| Publish the Package Translations and Views to the Working Directory
    	|--------------------------------------------------------------------------
    	*/

		$this->publishes([
			__DIR__ . '/Resources/lang' => base_path('resources/lang/vendor/listo'),
			__DIR__ . '/Resources/views' => base_path('resources/views/vendor/listo')
		], 'listo-resources');

		/*
        |--------------------------------------------------------------------------
        | Publish Routes, Migration and Seeder for the Demo
        |--------------------------------------------------------------------------
        */

		$this->publishes([
            __DIR__.'/Publish/routes.php' => base_path('publish/viewflex/listo/routes.php'),
			__DIR__ . '/Database/Migrations' => base_path('database/migrations'),
			__DIR__ . '/Database/Seeds' => base_path('database/seeds')
		], 'listo-demo');

		/*
        |--------------------------------------------------------------------------
        | Publish Config File to Config Directory, Merge With App Globals
        |--------------------------------------------------------------------------
        */

		$this->publishes([
			__DIR__.'/Config/listo.php' => config_path('listo.php'),
		], 'listo-config');

		$this->mergeConfigFrom(
			__DIR__.'/Config/listo.php', 'listo'
		);

		/*
        |--------------------------------------------------------------------------
        | Publish Routes, Config, Resources, Migration and Seeder
        |--------------------------------------------------------------------------
        */

		$this->publishes([
			__DIR__ . '/Config/listo.php' => config_path('listo.php'),
			__DIR__ . '/Database/Migrations' => base_path('database/migrations'),
			__DIR__ . '/Database/Seeds' => base_path('database/seeds'),
			__DIR__ . '/Publish/Demo' => base_path('publish/viewflex/listo/Demo'),
            __DIR__ . '/Publish/routes.php' => base_path('publish/viewflex/listo/routes.php'),
            __DIR__ . '/Resources/lang' => base_path('resources/lang/vendor/listo'),
            __DIR__ . '/Resources/views' => base_path('resources/views/vendor/listo')
		], 'listo');

	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{

        /*
    	|--------------------------------------------------------------------------
    	| Include the Routes File Published for Customization, if it exists.
    	|--------------------------------------------------------------------------
    	*/

        $published_routes = base_path('publish/viewflex/listo/routes.php');
        if (file_exists($published_routes))
            require $published_routes;
        
		
    }

}
