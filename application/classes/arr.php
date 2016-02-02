<?php defined('SYSPATH') or die('No direct script access.');

class Arr extends Kohana_Arr {

	/**
	 * Retrieves muliple single-key values from a list of arrays.
	 *
	 *     // Get all of the "id" values from a result
	 *     $ids = Arr::pluck($result, 'id');
	 *
	 * [!!] A list of arrays is an array that contains arrays, eg: array(array $a, array $b, array $c, ...)
	 *
	 * @param   array   $array     list of arrays to check
	 * @param   string  $key       key to pluck
	 * @param   bool    $preserve  preserve keys
	 * @return  array
	 */
	public static function pluck($array, $key, $preserve = FALSE)
	{
		$values = array();
		if ($preserve) {
			$values = parent::pluck($array, $key);
		} else {
			foreach ($array as $k => $row) {
				if (isset($row[$key])) {
					$values[$k] = $row[$key];
				}
			}
		}
		return $values;
	}

} // End arr
