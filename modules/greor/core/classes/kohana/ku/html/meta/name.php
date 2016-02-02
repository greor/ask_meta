<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Meta tag with name attribute.
 * Provides simple inculision css and js files.
 *
 * $Id$
 *
 * @package    kubik
 * @author     Sergey Fidyk aka Frame
 * @copyright  (c) 2010 KubikRubik
 * @license    http://kohanaphp.com/license.html
 */
class Kohana_Ku_HTML_Meta_Name extends Ku_HTML_Meta {

	protected $_required = array('name', 'content');
	protected $_readonly = array('name');
	protected $_ignored = array('http-equiv');

	public function __construct($name, $content, array $attributes = NULL)
	{
		$attributes['name'] = $name;
		parent::__construct($content, $attributes);
	}

} // End Ku_HTML_Meta_Name