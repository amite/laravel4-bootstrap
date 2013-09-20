<?php namespace EllipseSynergie\LaravelSecurity\Security;

use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Role\GenericRole;
use Zend\Permissions\Acl\Resource\GenericResource;

/**
 * This is the ACL component use to handle permissions on the laravel application.
 * We use zendframework/zend-permissions-acl packages in the back.
 */
class Permissions
{
	
	/**
	 * The acl object
	 * @var Zend\Permissions\Acl\Acl
	 */
	public $acl;
	
	/**
	 * Constructor
	 * 
	 * @param array $roles
	 * @param array $resources
	 */
	public function __construct($roles, $resources)
	{
		//Create the resource library used to check if a user have access to a specific resource
		$this->resource = new Resource;
		
		//Create brand new Acl object
		$this->acl = new Acl();
		
		//Add each resources
		foreach ($resources as  $name => $route){
			
			//Add the resource
			$this->acl->addResource(new GenericResource($name));
		}
		
		//Add each roles
		foreach ($roles as $role => $resources){
			
			//Add the role
			$this->acl->addRole(new GenericRole($role));
			
			//If we want to grant all privileges on all resources
			if($resources === true){
				
				//Allow all privileges
				$this->acl->allow($role);
				
			//Else if we have specific privileges for the role
			} elseif(is_array($resources)) {			
			
				//Create each resource permissions
				foreach ($resources as $resource){
					
					//Add resource permissions of the role
					$this->acl->allow($role, $resource);
				}				
			}			
		}
	}	
	
	/**
	 * Check is the user is allowed to the resource on the privilege
	 * 
	 * @param string $resource
	 * @return bool
	 */
	public function isAllowed($role, $resource)
	{
				
		if($this->acl->isAllowed($role, $resource)){
			return true;
		}
		
		return false;		
	}
	
	/**
	 * Check if the user have access to the specific resource
	 * 
	 * @todo refactore this
	 * @param int $id
	 * @param string optional $resource
	 */
	public function hasResourceAccess($id, $resource = null)
	{		

		//WARNING TEMPORARY ADD THIS FOR TESTING ENVIRONNEMENT
		//@todo REMOVE THIS AND REPLACE Token helper by a facade
		if(\App::environment() === 'testing'){
			return true;
		}
		
		//Get the user associate to the token
		list($token_data, $user) = \Token::get(\Input::get('token'));
		
		return $this->resource->hasAccess($user, $id, $resource);		
	}
	
	/**
	 * Get the reponse we need the return when a user has no access to the specific ressource
	 * 
	 * @todo refactore this
	 * @return Response
	 */
	public function noResourceAccessResponse()
	{
		$result = array(
			'uri' => \Request::server('request_uri'),
			'success' => false,
			'errors' => array(\Config::get('error.generic.resource_not_allowed'))
		);
		
		return \Response::json($result, \Config::get('http.error.unauthorized'), array('X-Time' => number_format((microtime(true) - LARAVEL_START), 5)));
	}
}