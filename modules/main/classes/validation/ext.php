<?php defined('SYSPATH') or die('No direct script access.');

class Validation_Ext extends Validation {

	/**
	 * Creates a new Validation_Ext instance.
	 *
	 * @param   array   $array  array to use for validation
	 * @return  Validation_Ext
	 */
	public static function factory(array $array)
	{
		return new Validation_Ext($array);
	}
	
	public function add_empty_rule($name)
	{
		array_push($this->_empty_rules, $name);
	}
	
	/**
	 * Executes all validation rules. This should
	 * typically be called within an if/else block.
	 *
	 *     if ($validation->check())
	 	*     {
	 	*          // The data is valid, do something here
	 	*     }
	 *
	 * @return  boolean
	 */
	 public function check()
	 {
	 	if (Kohana::$profiling === TRUE)
	 	{
	 		// Start a new benchmark
	 		$benchmark = Profiler::start('Validation', __FUNCTION__);
	 	}
	
	 	// New data set
	 	$data = $this->_errors = array();
	
	 	// Store the original data because this class should not modify it post-validation
	 	$original = $this->_data;
	
	 	// Get a list of the expected fields
	 	$expected = Arr::merge(array_keys($original), array_keys($this->_labels));
	
	 	// Import the rules locally
	 	$rules     = $this->_rules;
	
	 	foreach ($expected as $field)
	 	{
	 		// Use the submitted value or NULL if no data exists
	 		$data[$field] = Arr::get($this, $field);
	
	 		if (isset($rules[TRUE]))
	 		{
	 			if ( ! isset($rules[$field]))
	 			{
	 				// Initialize the rules for this field
	 				$rules[$field] = array();
	 			}
	
	 			// Append the rules
	 			$rules[$field] = array_merge($rules[$field], $rules[TRUE]);
	 		}
	 	}
	
	 	// Overload the current array with the new one
	 	$this->_data = $data;
	
	 	// Remove the rules that apply to every field
	 	unset($rules[TRUE]);
	
	 	// Bind the validation object to :validation
	 	$this->bind(':validation', $this);
	 	// Bind the data to :data
	 	$this->bind(':data', $this->_data);
	
	 	// Execute the rules
	 	foreach ($rules as $field => $set)
	 	{
	 		// Get the field value
	 		$value = $this[$field];
	
	 		// Bind the field name and value to :field and :value respectively
	 		$this->bind(array
	 				(
	 				':field' => $field,
	 				':value' => $value,
	 				));
	
	 		foreach ($set as $array)
	 		{
	 			// Rules are defined as array($rule, $params)
	 			list($rule, $params) = $array;
	
	 			foreach ($params as $key => $param)
	 			{
	 				if (is_string($param) AND array_key_exists($param, $this->_bound))
	 				{
	 					// Replace with bound value
	 					$params[$key] = $this->_bound[$param];
	 				}
	 			}
	
	 			// Default the error name to be the rule (except array and lambda rules)
	 			$error_name = $rule;
	
	 			if (is_array($rule))
	 			{
	 				// Allows rule('field', array(':model', 'some_rule'));
	 				if (is_string($rule[0]) AND array_key_exists($rule[0], $this->_bound))
	 				{
	 					// Replace with bound value
	 					$rule[0] = $this->_bound[$rule[0]];
	 				}
	
	 				// This is an array callback, the method name is the error name
	 				$error_name = $rule[1];
	 				$passed = call_user_func_array($rule, $params);
	 			}
	 			elseif ( ! is_string($rule))
	 			{
	 				// This is a lambda function, there is no error name (errors must be added manually)
	 				$error_name = FALSE;
	 				$passed = call_user_func_array($rule, $params);
	 			}
	 			elseif (method_exists('Valid', $rule))
	 			{
	 				// Use a method in this object
	 				$method = new ReflectionMethod('Valid', $rule);
	
	 				// Call static::$rule($this[$field], $param, ...) with Reflection
	 				$passed = $method->invokeArgs(NULL, $params);
	 			}
	 			elseif (strpos($rule, '::') === FALSE)
	 			{
	 				// Use a function call
	 				$function = new ReflectionFunction($rule);
	
	 				// Call $function($this[$field], $param, ...) with Reflection
	 				$passed = $function->invokeArgs($params);
	 			}
	 			else
	 			{
	 				// Split the class and method of the rule
	 				list($class, $method) = explode('::', $rule, 2);
	
	 				// Use a static method call
	 				$method = new ReflectionMethod($class, $method);
	
	 				// Call $Class::$method($this[$field], $param, ...) with Reflection
	 				$passed = $method->invokeArgs(NULL, $params);
	 			}
	
	 			// Ignore return values from rules when the field is empty
	 			$rule_name = is_array($rule) ? $rule[1] : $rule;
	 			if ( ! in_array($rule_name, $this->_empty_rules) AND ! Valid::not_empty($value))
	 				continue;
	
	 				if ($passed === FALSE AND $error_name !== FALSE)
	 				{
	 					// Add the rule to the errors
	 					$this->error($field, $error_name, $params);
	
	 					// This field has an error, stop executing rules
	 					break;
	 				}
	 				elseif (isset($this->_errors[$field]))
	 				{
	 					// The callback added the error manually, stop checking rules
	 					break;
	 				}
	 		}
	 	}
	
	 	// Unbind all the automatic bindings to avoid memory leaks.
	 	unset($this->_bound[':validation']);
	 	unset($this->_bound[':data']);
	 	unset($this->_bound[':field']);
	 	unset($this->_bound[':value']);
	
	
	 	// Restore the data to its original form
	 	$this->_data = $original;
	
	 	if (isset($benchmark))
	 	{
	 		// Stop benchmarking
	 		Profiler::stop($benchmark);
	 	}
	
	 	return empty($this->_errors);
	 }
	

} // End Validation
