<?php namespace EllipseSynergie\LaravelHelper\Helper;

/**
 * Ajax request Helper
 *
 * @author Maxime Beaudoin <maxime.beaudoin@ellipse-synergie.com>
 */
class AjaxRequest {
	
	/**
	 * Use this when you want to return a json response use by Ajax Request.
	 * This way, it's much easier to standardize each response on the same format
	 * 
	 * @param array $options available options :
	 * bool success
	 * string message
	 * array errors
	 * mixed data
	 * string content
	 * @param bool $response_object
	 * 
	 * @return array()
	 */
	public function formatResponse(array $options = array())
	{							
		//The data to return
		return array(
			'success' => isset($options['success']) ? $options['success'] : false,
			'message' => isset($options['message']) ? $options['message'] : '',
			'errors' => isset($options['errors']) ? $options['errors'] : array(),
			'data' => isset($options['data']) ? $options['data'] : null,
			'content' => isset($options['content']) ? $options['content'] : '',
			'redirect' => isset($options['redirect']) ? $options['redirect'] : null
		);
		
	} //formatResponse()
	
} //AjaxHelper