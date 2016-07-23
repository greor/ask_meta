<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Front_Multisite extends Controller_Front_Base {

	private $_main_site;
	protected $site_time = array();

	protected function init()
	{
		parent::init();
		
		$this->menu_cache_key = 'menu:'.$this->request->site_id;
		$this->menu_handlers[TRUE][] = array($this, 'menu_set_region');
	}
	
	protected function menu_set_region( & $row)
	{
		if ( ! empty($row['sub'])) {
			foreach ($row['sub'] as & $item) {
				$this->menu_set_region($item);
			}
		}
		
		// почему для динамических не надо выставлять регион????
// 		if ($row['is_dynamic'])
// 			continue;
		
		$row['uri'] = Route::set_region($row['uri']);
	}
	
	protected function site_init()
	{
		$request = $this->request->current();
		
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
		
		if ($this->request->current()->is_initial()) {
			View::set_global('MAIN_SITE', $this->site_main());
		}
	}
	
	protected function canonical_url_main_site()
	{
		$current_url = $this->request->initial()->url(TRUE);
		$canonical_href = rtrim(URL::base(TRUE), '/').parse_url($current_url, PHP_URL_PATH);
		$this->head_tags[] = array(
			'tag'  => 'link',
			'attr' => HTML::attributes(array(
				'rel'  => 'canonical',
				'href' => $canonical_href,
			)),
		);
	}
	
	/*
	 * Попытка перейти с главного сайта на региональный элемент
	 * Если да, то редиректим на регион
	 */
	protected function site_redirect($site_id)
	{
		if ( ! IS_MASTER_SITE OR $site_id == SITE_ID) {
			return;
		}
		
		$site = Arr::get(ORM_Helper_Site::sites(), $site_id);
		if (empty($site)) {
			throw new HTTP_Exception_404();
		}
		
		$new_url = URL::base().$this->request->uri();
		$new_url = Route::set_region($new_url, $site['code']);
		$this->request
			->redirect($new_url);
	}

}