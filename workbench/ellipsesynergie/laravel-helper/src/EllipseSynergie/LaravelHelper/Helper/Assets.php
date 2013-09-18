<?php namespace EllipseSynergie\LaravelHelper\Helper;

/**
 * Assets Helper
 *
 * @author Maxime Beaudoin <maxime.beaudoin@ellipse-synergie.com>
 */
class Assets {
	
	/**
	 * Assets base url
	 * 
	 * @var string
	 */
	protected $_baseUrl = 'assets/';
	
	/**
	 * Assets collections
	 * 
	 * @var array
	 */
	protected $_collections = array();
	
	/**
	 * Add one ore more css file
	 * 
	 * @param string|array $files
	 * @return boolean
	 */
	public function addCss($files)
	{
		return $this->add($files, 'css');
	}
	
	/**
	 * Add one ore more js file
	 *
	 * @param string|array $files
	 * @return boolean
	 */
	public function addJs($files)
	{
		return $this->add($files, 'js');
	}
	
	/**
	 * Add assets to be rendered
	 * 
	 * @param string $files
	 * @param string $type
	 * @return boolean
	 */
	public function add($files, $type)
	{
		// If string passed, convert to array
		$files = is_string($files) ? array($files) : $files;
	
		// Load each asset, if file exists
		foreach ($files as $file) {
			$this->_collections[$type][] = $file;
		}
		
		return true;
	}
	
	/**
	 * Render css files
	 * 
	 * @return string
	 */
	public function renderCss($format = '<link rel="stylesheet" href="{{url}}" type="text/css">')
	{
		return $this->render('css', $format);
	}
	
	/**
	 * Render js files
	 *
	 * @return string
	 */
	public function renderJs($format = '<script src="{{url}}"></script>')
	{
		return $this->render('js', $format);
	}
	
	/**
	 * Renders CSS/JS files (returns HTML tags)
	 * 
	 * @return string|null
	 */
	public function render($type, $format)
	{
		// If $type is null, render both types
		if (!$type) { 
			$type = array('css', 'js'); 
		}
	
		// If $type is string, convert to array
		$types = is_string($type) ? array($type) : $type;
	
		$response = array();
	
		foreach ($types as $type) {
			
			//If we have something in the collection
			if (!empty($this->_collections[$type])) {
	
				$collection = $this->_collections[$type];
		
				foreach($collection as $file)
				{
					$response[] = str_replace('{{url}}', $file, $format);
				}
			}
				
		}
		
		//If we have some reponse
		if (!empty($response)) {
			return implode(PHP_EOL, $response);
		}
		
		return null;
	}	

	/**
	 * Reset
	 */
	public function reset()
	{
		$this->_collections = array();
	}
}