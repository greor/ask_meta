<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Meta tag with http-equiv attribute.
 * Provides simple inculision css and js files.
 *
 * $Id$
 *
 * @package    kubik
 * @author     Sergey Fidyk aka Frame
 * @copyright  (c) 2010 KubikRubik
 * @license    http://kohanaphp.com/license.html
 */
class Kohana_Ku_HTML_Meta_HTTP extends Ku_HTML_Meta {

	protected $_required = array('http-equiv', 'content');
	protected $_readonly = array('http-equiv');
	protected $_ignored = array('name');

	public function __construct($name, $content, array $attributes = NULL)
	{
		$attributes['http-equiv'] = $name;
		parent::__construct($content, $attributes);
	}
} // End Ku_HTML_Meta_HTTP