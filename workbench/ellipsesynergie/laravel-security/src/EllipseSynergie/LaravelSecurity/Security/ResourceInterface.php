<?php namespace EllipseSynergie\LaravelSecurity\Security;

interface ResourceInterface {
	
	/**
	 * Check if the user has access to the ressource
	 *
	 * @param User $user
	 * @param int $id
	 * @param string $resource
	 * @return bool
	 */
	public function hasAccess($user, $id, $resource = null);	
	
	/**
	 * Get the resource associate to the route
	 * 
	 * @param string $route
	 * @return string
	 */
	public function getResourceFromRoute($route);
}