<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class MinifyCssCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'wps:minifycss';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Minify and packing CSS.';

	/**
	 * Path to CSS files
	 *
	 * @var string
	 */
	protected $_path_to_css = '';

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
		$this->_path_to_assets = Config::get('minify.path_to_assets');
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

		foreach (Config::get('minify.css.path_to_scan') as $path_to_scan) {

			foreach (File::allFiles($this->_path_to_assets . '/' . $path_to_scan) as $file) {

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
		foreach (Config::get('minify.css.packages') as $package => $files) {

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