<?php namespace EllipseSynergie\LaravelCommand\Command;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\Facades\Config;

class MinifyCssCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'ellipse:minifycss';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Minify and packing CSS.';

	/**
	 * Path to assets folder
	 *
	 * @var string
	 */
	protected $_assetsDirectory;

	/**
	 * Path to css folder
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
		$this->_assetsDirectory = Config::get('laravelcommand::minify.assetsDirectory');
		$this->_directories = Config::get('laravelcommand::minify.css.directories');
		$this->_minify();
		$this->_pack();
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

				if ($file->getExtension() == 'css' && 
					substr($in, -8) != '.min.css' && 
					substr($in, -9) != '.pack.css') {

					$out = str_replace('.css', '.min.css', $file->getPathname());
					$command = 'cat ' . $in . ' | sed -e \'s/^[ \t]*//g; s/[ \t]*$//g; s/\([:{;,]\) /\1/g; s/ {/{/g; s/\/\*.*\*\///g; /^$/d\' | sed -e :a -e \'$!N; s/\n\(.\)/\1/; ta\' > ' . $out;
					
					shell_exec($command);

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
		foreach (Config::get('laravelcommand::minify.css.packages') as $package => $files) {

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