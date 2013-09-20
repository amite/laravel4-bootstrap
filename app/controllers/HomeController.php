<?php

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function showWelcome()
	{
		Assets::addJs('assets/js/home.js');
		$this->layout->content = View::make('home');
	}
	
	/**
	 * Hearbeat the application via AJAX request
	 */
	public function getHeartbeat()
	{
		return Response::json('Heartbeat !!!');
	}
	
	/**
	 * Usage example for ajax request
	 */
	public function getAjaxExample()
	{
		return Response::json(Ajax::response(array('content' => 'Homepage content loaded via ajax request')));
	}
}