<?php 

return array(
	
	/*
	|--------------------------------------------------------------------------
	| ACL available roles list
	|
	| If you don't want to handle permissions in your application, use only the administrator roles.
	| Also, you can add custom roles for your application and custom resources.
	|
	|--------------------------------------------------------------------------
	*/
	'roles' => array(
				
		/*
		 * Grant all privileges to the administrator roles.
		*/
		'administrator'  => true,

		/*
		 * Granted privileges for guest
		*/
		'guest' => array(),

		/*
		 * Disable all privilege to banned user
		*/
		'banned' => false,

		/*
		 * Granted privileges to the user
		*/
		'user' => array(
			'get index'
		)
	)
);