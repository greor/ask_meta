<?php defined('SYSPATH') or die('No direct script access.');

class ORM_Helper_Property_Enum extends ORM_Helper {
	
	public function delete($real_delete, array $where = NULL, $cascade_delete = TRUE, $is_slave_delete = FALSE)
	{
		$model = $this->_orm;
		$table = $model->table_name();
		$primary_key = $model->primary_key();
	
		if (empty($where)) {
			$where = array(array($primary_key, '=', $model->pk()));
		}
	
		$builder = DB::delete($table);
		foreach ($where as $condition) {
			$builder->where($condition[0], $condition[1], $condition[2]);
		}
		$builder->execute($this->_db);
	
		return $this;
	}
	
}