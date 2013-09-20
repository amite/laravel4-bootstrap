<?php namespace EllipseSynergie\LaravelSecurity\Security;

use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Role\GenericRole;
use Zend\Permissions\Acl\Resource\GenericResource;

/**
 * This is the Permissions ACL library
 *
 * @author Maxime Beaudoin <maxime.beaudoin@ellipse-synergie.com>
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
		//Create brand new Acl object
		$this->acl = new Acl();
		
		//Add each resources
		foreach ($resources as  $route){
			
			//Add the resource
			$this->acl->addResource(new GenericResource($route));
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
}