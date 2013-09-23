<?php namespace EllipseSynergie\LaravelHelper\Helper;

/**
 * Assets Helper
 *
 * @author Maxime Beaudoin <maxime.beaudoin@ellipse-synergie.com>
 */
class Assets {
	
	/**
	 * Assets collections
	 * 
	 * @var array
	 */
	protected $_collections = array();
	
	/**
	 * Constructor
	 * 
	 * @param array $config
	 */
	public function __construct($config){
		$this->config = $config;
	}
	
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
	 * Add timestamp to the assets using the last modified time of the file
	 * 
	 * @param string $uri
	 * @return string
	 */
	public function versionized($uri, $baseUrl = null){	

		//If we provide a custom url
		if ($baseUrl) {
			
			//
			$url = $baseUrl . '/' . $uri;

		//ELse
		} else {	

			//Generate the full url
			$url = URL::to($uri);
		}
		
		return  $url . '?v=' . filemtime(base_path() . '/public/' . $uri);		
	}
	
	/**
	 * Build URL pointing to the storage
	 *
	 * @param string $uri
	 * @return string
	 */
	public static function storage($uri)
	{	
		//By default we take the S3 bucket
		$baseUrl = $this->config['storage']['bucket'];
	
		// If we have a CDN
		if ($this->config['cdn']['enabled'] === true) {
			$baseUrl = $this->config['cdn']['url'];
		}
	
		return $this->versionized($uri, $baseUrl = null);
	
	} // storage()

	/**
	 * Reset the collections
	 */
	public function reset()
	{
		$this->_collections = array();
	}
}