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
class Kohana_Ku_HTML_Script_Embed extends Ku_HTML_Script {

	protected $_tag = 'script';
	protected $_empty = FALSE;
	protected $_required;
	protected $_ignored = array('src');

	public function __construct($content, array $arrtibutes = NULL)
	{
		parent::__construct(NULL, $arrtibutes);
		$this->_cdata = $content;
	}

} // End Kohana_Ku_HTML_Script_Embed