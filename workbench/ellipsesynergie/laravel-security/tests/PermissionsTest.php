<?php namespace EllipseSynergie\LaravelSecurity;

use Mockery as m;
use EllipseSynergie\LaravelSecurity\Security\Permissions;

/**
 * Test case for permissions
 */
class PermissionsTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Resources we want to tests
	 * 
	 * @var array
	 */
	protected $_resources = array (
		'get user', 
		'post user', 
		'put user', 			
		'delete user', 			
		'get /'
	);

	/**
	 * Roles we want to tests
	 * 
	 * @var array
	 */
	protected $_roles = array (
		'administrator' => true, 
		'guest' => array('get /'), 
		'banned' => false, 
		'user' => array (
			array ('get user', 'put user')
		) 
	);
	
	public function testAdministratorIsAllowed()
	{
		$permissions = new Permissions($this->_roles, $this->_resources);
		$this->assertTrue($permissions->isAllowed('administrator', 'get user'));
	}
	

	public function testGuestIsNotAllowed()
	{
		$permissions = new Permissions($this->_roles, $this->_resources);
		$this->assertFalse($permissions->isAllowed('guest', 'get user'));
	}	

	public function testGuestIsAllowed()
	{
		$permissions = new Permissions($this->_roles, $this->_resources);
		$this->assertTrue($permissions->isAllowed('guest', 'get /'));
	}

	public function testBannedIsNotAllowed()
	{
		$permissions = new Permissions($this->_roles, $this->_resources);
		$this->assertFalse($permissions->isAllowed('banned', 'get /'));
	}	

	public function testUserIsNotAllowedToDelete()
	{
		$permissions = new Permissions($this->_roles, $this->_resources);
		$this->assertFalse($permissions->isAllowed('user', 'delete user'));
	}	

	public function testUserIsAllowedToShow()
	{
		$permissions = new Permissions($this->_roles, $this->_resources);
		$this->assertTrue($permissions->isAllowed('user', 'get user'));
	}
}