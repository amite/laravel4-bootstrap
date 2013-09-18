<?php 

namespace Security;

use Illuminate\Support\ServiceProvider;

/**
 * Permissions service provider
 */
class PermissionsServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = true;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{		
		$this->app['permissions'] = $this->app->share(function($app)
		{
			return new Permissions(
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
		return array('permissions');
	}

}