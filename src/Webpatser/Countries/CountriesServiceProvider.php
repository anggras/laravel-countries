<?php

namespace Webpatser\Countries;

use Illuminate\Support\ServiceProvider;

/**
 * CountryListServiceProvider
 *
 */ 

class CountriesServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->loadViewsFrom(__DIR__.'/../../views', 'laravel-countries');

		// Publish config files
        $this->publishes([
            __DIR__.'/../../config/config.php' => config_path('countries.php'),
        ]);
	}        
        
	/**
	 * Register everything.
	 *
	 * @return void
	 */
	public function register()
	{
	    $this->registerCountries();
	    $this->registerCommands();
	    $this->mergeConfig();
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function registerCountries()
	{
	    $this->app->bind('countries', function($app)
	    {
	        return new Countries();
	    });
	}
	
	/**
	 * Register the artisan commands.
	 *
	 * @return void
	 */
	protected function registerCommands()
	{		    
	    $this->app['command.countries.migration'] = $this->app->share(function($app)
	    {
	        return new MigrationCommand($app);
	    });
	    
	    $this->commands('command.countries.migration');
	}

	/**
     * Merges user's and countries' configs.
     *
     * @return void
     */
    private function mergeConfig()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/config.php', 'laravel-countries'
        );
    }
    
	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('countries');
	}
}