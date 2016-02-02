<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Front_Multisite extends Controller_Admin_Front_Base {

	private $_main_site;
	protected $site_time = array();

	
	protected function site_init()
	{
		$request = $this->request->current();

		if ( ! Kohana::$is_cli ) {
			$site_id = (int) $request->query('site_id');
			
			if ($site_id AND $this->acl->is_allowed($this->user, 'site_switcher', 'show')) {
				$request->site_id = $site_id;
				Session::instance()
					->set('SITE_ID', $site_id);
			} else {
				$site_id_sess = (int) Session::instance()->get('SITE_ID');
				if (empty($site_id_sess)) {
					$request->site_id = (int) $this->user->site_id;
					Session::instance()
						->set('SITE_ID', $request->site_id);
				} else {
					$request->site_id = $site_id_sess;
				}
				unset($site_id_sess);
			}
			unset($site_id);
			
			$master_site = DB::select('id')
				->from('sites')
				->where('type', '=', 'master')
				->and_where('active', '>', 0)
				->and_where('delete_bit', '=', 0)
				->execute()
				->current();
			
			if (empty($master_site)) {
				throw new HTTP_Exception_404();
			}
				
			$request->site_id_master = (int) $master_site['id'];
		}

		$site = Arr::get(ORM_Helper_Site::sites() , $request->site_id);
		$this->site($site);
		
		if ( ! defined('IS_MASTER_SITE')) {
			define('IS_MASTER_SITE', (($site['type'] == 'master') ? 1 : 0));
		}
		if ( ! defined('SITE_ID')) {
			define('SITE_ID', $request->site_id);
		}
		if ( ! defined('SITE_ID_MASTER')) {
			define('SITE_ID_MASTER', $request->site_id_master);
		}
		
		ORM_Base::$site_id = SITE_ID;
		ORM_Base::$site_id_master = SITE_ID_MASTER;
		
		$this->site_time['shift'] = Helper_Date::str_to_sec($site['mmt']);
		$this->site_time['ts'] = time() + $this->site_time['shift'];
	}
	
	protected function site_main()
	{
		if (empty($this->_main_site)) {
			$this->_main_site = ORM::factory('site', $this->request->site_id_master)
				->as_array();
		}
	
		return $this->_main_site;
	}

	protected function template_set_global_vars()
	{
		parent::template_set_global_vars();
		
		View::set_global('SITE_SWITCHER', $this->get_site_switcher());
	}
	
	protected function get_site_switcher()
	{
		return ORM::factory('site')
			->find_all()
			->as_array('id', 'name');
	}
	
	protected function element_hide($object_name, $element_id)
	{
		$orm = ORM::factory('hided_List')
			->where('object_name', '=', $object_name)
			->and_where('site_id', '=', SITE_ID)
			->and_where('element_id', '=', $element_id)
			->find();
	
		if ( ! $orm->loaded()) {
			try {
				$orm
					->clear()
					->values(array(
						'object_name' => $object_name,
						'site_id' => SITE_ID,
						'element_id' => $element_id,
					))
					->save();
			} catch (Exception $e) {
				Log::instance()
					->add(Log::ERROR, $e->getMessage().'['.__FILE__.':'.__LINE__.']');
			}
		}
	}
	
	protected function element_show($object_name, $element_id)
	{
		$orm = ORM::factory('hided_List')
			->where('object_name', '=', $object_name)
			->and_where('site_id', '=', SITE_ID)
			->and_where('element_id', '=', $element_id)
			->find();
	
		if ($orm->loaded()) {
			try {
				$orm->delete();
			} catch (Exception $e) {
				Log::instance()
					->add(Log::ERROR, $e->getMessage().'['.__FILE__.':'.__LINE__.']');
			}
		}
	}
	
	protected function get_hided_list($object_name)
	{
		return ORM::factory('hided_List')
			->where('object_name', '=', $object_name)
			->find_all()
			->as_array(NULL, 'element_id');
	}
}