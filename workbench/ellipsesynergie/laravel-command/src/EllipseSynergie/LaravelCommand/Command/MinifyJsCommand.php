<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class MinifyJsCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'wps:minifyjs';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Minify Js.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire()
	{
		// Validate that minify script is installed
		$returnVal = shell_exec("which uglifyjs");
		
		if (!empty($returnVal)) {

			$this->_path_to_assets = Config::get('minify.path_to_assets');

			$this->_minify();
			$this->_pack();

		} else {

			$this->error("You should install uglifyjs first! (see https://github.com/mishoo/UglifyJS2)");
		}
	}

	/**
	 * Minify files
	 *
	 */
	private function _minify()
	{
		$this->info("Minifying...");

		foreach (Config::get('minify.js.path_to_scan') as $path_to_scan) {

			foreach (File::allFiles($this->_path_to_assets . '/' . $path_to_scan) as $file) {

				$in = $file->getPathname();

				if ($file->getExtension() == 'js' && 
					substr($in, -7) != '.min.js' && 
					substr($in, -8) != '.pack.js') {

					$out = str_replace('.js', '.min.js', $file->getPathname());
					shell_exec("uglifyjs " . $in . " -c -o " . $out);

					$this->line("\t" . $in . " → " . $out);
				}
			}
		}

	} // _minify()

	/**
	 * Packing files
	 *
	 */
	private function _pack()
	{
		foreach (Config::get('minify.js.packages') as $package => $files) {

			$package = $this->_path_to_assets . '/' . $package;

			// 
			$this->info("Packing " . $package);

			// Delete file first
			File::delete($package);

			foreach ($files as $file) {
				$file = $this->_path_to_assets . '/' . $file;
				$this->line("\tAppending " . $file . "...");
				File::append($package, File::get($file));
			}
		}

	} // _pack()

}