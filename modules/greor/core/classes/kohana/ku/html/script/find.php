<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * HTML embedded script tag class.
 *
 * $Id$
 *
 * @package    kubik
 * @author     Sergey Fidyk aka Frame
 * @copyright  (c) 2010 KubikRubik
 * @license    http://kohanaphp.com/license.html
 */
class Kohana_Ku_HTML_Script_Find extends Ku_HTML_Script_File {

	public function render()
	{
		if ($this->_file)
		{
			$file = $this->_file;
			$pathinfo = pathinfo($file);
			$path = Kohana::find_file(
				$pathinfo['dirname'],
				$pathinfo['filename'],
				$pathinfo['extension'] ? $pathinfo['extension'] : 'js'
			);
			if($path)
			{
				$this->_file = $path;
			}
			else
			{
				$this->_file = NULL;
			}
			$html = parent::render();
			$this->_file = $file;
			return $html;
		}
		return parent::render();
	}

} // End Kohana_Ku_HTML_Script_Find