<?php defined('SYSPATH') or die('No direct script access.');

class ORM extends Kohana_ORM implements Acl_Resource_Interface {

	/*
	 * FIXME использовать обычный builder
	 */
	public static function exclude_hidden(ORM $orm, $is_master_site)
	{
		if ( ! $is_master_site) {
			$hided_list = ORM::factory('hided_List')
				->where('object_name', '=', $orm->object_name())
				->find_all()
				->as_array(NULL, 'element_id');
	
			if ( ! empty($hided_list)) {
				$orm->where($orm->primary_key(), 'NOT IN', $hided_list);
			}
		}
	}
	
	protected $_required_fields;

	protected $_paginator;

	/**
	 * @see Acl_Resource_Interface::get_resource_id()
	 */
	public function get_resource_id()
	{
		return $this->_object_name;
	}

	public function unique_ext($field, $value)
	{
		$model = ORM::factory($this->object_name())
			->where($field, '=', $value)
			->where('delete_bit', '=', '0')
			->find();

		if ($this->loaded())
		{
			return ( ! ($model->loaded() AND $model->pk() != $this->pk()));
		}

		return ( ! $model->loaded());
	}

	public function checkbox($value)
	{
		if (empty($value))
			return FALSE;
		else
			return TRUE;

	}

	public function required_fields()
	{
		if (isset($this->_required_fields))
			return $this->_required_fields;

		$this->_required_fields = array();

		foreach ($this->rules() as $field => $rules)
		{
			foreach ($rules as $rule)
			{
				$func = reset($rule);

				if (is_string($func) AND ($func == 'not_empty'))
				{
					$this->_required_fields[] = $field;
					continue 2;
				}
			}
		}

		return $this->_required_fields;
	}

	public function paginator($paginator = NULL)
	{
		if ($paginator === NULL)
			return $this->_paginator;

		$this->_paginator = $paginator;

		$this->limit( $this->_paginator->limit() )
			->offset( $this->_paginator->offset() );

		return $this;
	}

	public function save_has_many($name, $exist_ids)
	{
		$affected_ids = array();

		$post_ids = Request::current()->post($name);

		if ($post_ids === NULL)
		{
			$post_ids = array();
		}
		
		$post_ids = array_filter($post_ids, array( $this, '_repeat_filter' ));

		if ( is_array($post_ids))
		{
			if ( ! empty($post_ids))
			{
				$post_ids = array_unique($post_ids);
			}
			else
			{
				$post_ids = array();
			}

			$del_ids = array_diff($exist_ids, $post_ids);
			$add_ids = array_diff($post_ids, $exist_ids);
			
			if ( ! empty($del_ids))
			{
				$this->remove($name, $del_ids);
			}
			if ( ! empty($add_ids))
			{
				$this->add($name, $add_ids);
			}

			$affected_ids = array_diff($exist_ids, $del_ids);
			$affected_ids = $affected_ids + $add_ids;
		}

		if ( ! empty($affected_ids))
		{
			$affected_ids = array_combine( $affected_ids, $affected_ids );
		}

		return $affected_ids;
	}
	
	private function _repeat_filter($v)
	{
		return $v === 0 OR (bool) $v;
	}

}