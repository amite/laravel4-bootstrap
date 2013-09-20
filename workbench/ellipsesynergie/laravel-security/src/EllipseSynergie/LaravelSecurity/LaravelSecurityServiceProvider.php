<?php namespace EllipseSynergie\LaravelSecurity;

use Illuminate\Support\ServiceProvider;

class LaravelSecurityServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('ellipsesynergie/laravel-security');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['permissions'] = $this->app->share(function($app)
		{
			return new Security\Permissions(
				$app['config']->get('auth.roles'),
				$app['router']->getRoutes()->all()
			);
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}