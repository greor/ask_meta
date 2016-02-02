<?php defined('SYSPATH') or die('No direct script access.');

class ORM_Helper_Site extends ORM_Helper_Property_Support {

	protected $_property_config = 'site.properties';
	protected $_safe_delete_field = 'delete_bit';
	protected $_file_fields = array(
		'logo' => array(
			'path' => "upload/images/sites",
			'uri' => NULL,
			'on_delete' => ORM_File::ON_DELETE_RENAME,
			'on_update' => ORM_File::ON_UPDATE_RENAME,
			'allowed_src_dirs' => array(),
		),
		'sharing_image' => array(
			'path' => "upload/images/sharing_image",
			'uri' => NULL,
			'on_delete' => ORM_File::ON_DELETE_RENAME,
			'on_update' => ORM_File::ON_UPDATE_RENAME,
			'allowed_src_dirs' => array(),
		),
		'image' => array(
			'path' => "upload/images/sites_image",
			'uri' => NULL,
			'on_delete' => ORM_File::ON_DELETE_RENAME,
			'on_update' => ORM_File::ON_UPDATE_RENAME,
			'allowed_src_dirs' => array(),
		),
	);
	
	public function file_rules()
	{
		return array(
			'logo' => array(
				array('Ku_File::valid'),
				array('Ku_File::size', array(':value', '3M')),
				array('Ku_File::type', array(':value', 'jpg, jpeg, bmp, png, gif')),
			),
			'sharing_image' => array(
				array('Ku_File::valid'),
				array('Ku_File::size', array(':value', '3M')),
				array('Ku_File::type', array(':value', 'jpg, jpeg, bmp, png, gif')),
			),
			'image' => array(
				array('Ku_File::valid'),
				array('Ku_File::size', array(':value', '3M')),
				array('Ku_File::type', array(':value', 'jpg, jpeg, bmp, png, gif')),
			),
		);
	}
	
	public static function sites()
	{
		static $_sites;
	
		if ( empty($_sites) ) {
			$_sites = array();
			$sites_db = ORM::factory('site')
				->find_all();
			foreach ($sites_db as $item_db) {
				$_item = $item_db->as_array();
				$_item['uri'] = Route::set_region('', $item_db->code);
				$_sites[ $item_db->id ] = $_item;
			}
		}
	
		return $_sites;
	}
	
}