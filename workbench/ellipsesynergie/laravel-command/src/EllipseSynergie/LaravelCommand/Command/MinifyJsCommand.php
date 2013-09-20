<?php namespace EllipseSynergie\LaravelCommand\Command;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\Facades\Config;

class MinifyJsCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'ellipse:minifyjs';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Minify and packing Javascript.';

	/**
	 * Path to assets directory
	 *
	 * @var string
	 */
	protected $_assetsDirectory;

	/**
	 * Path to javascript directories
	 * 
	 * @var string
	 */
	protected $_directories;

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
			
			$this->_assetsDirectory = Config::get('laravelcommand::minify.assetsDirectory');
			$this->_directories = Config::get('laravelcommand::minify.js.directories');

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

		foreach ($this->_directories as $folder) {

			foreach (File::allFiles($this->_assetsDirectory . '/' . $folder) as $file) {

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

			$package = $this->_assetsDirectory . '/' . $package;

			// 
			$this->info("Packing " . $package);

			// Delete file first
			File::delete($package);

			foreach ($files as $file) {
				$file = $this->_assetsDirectory . '/' . $file;
				$this->line("\tAppending " . $file . "...");
				File::append($package, File::get($file));
			}
		}

	} // _pack()

}