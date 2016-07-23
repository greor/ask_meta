<?php defined('SYSPATH') or die('No direct access allowed.');

	$orm = $helper_orm->orm();
	$labels = $orm->labels();
	$required = $orm->required_fields();

/**** sm_changefreq ****/

	$_set = Arr::path($orm->table_columns(), 'sm_changefreq.options', array());
	$_options = @array_combine($_set, $_set);
	$_options = (empty($_options)) ? array() : $_options;
	
	echo View_Admin::factory('form/control', array(
		'field' => 'sm_changefreq',
		'errors' => $errors,
		'labels' => $labels,
		'required' => $required,
		'controls' => Form::select('sm_changefreq',  array('' => '') + $_options, $orm->sm_changefreq, array(
			'id' => 'sm_changefreq_field',
			'class' => 'input-xxlarge',
		)),
	));
	
/**** sm_priority ****/

	echo View_Admin::factory('form/control', array(
		'field' => 'sm_priority',
		'errors' => $errors,
		'labels' => $labels,
		'required' => $required,
		'controls' => Form::input('sm_priority', $orm->sm_priority, array(
			'id' => 'sm_priority_field',
			'class' => 'input-xxlarge',
		)),
	));

/**** sm_separate_file ****/

	echo View_Admin::factory('form/checkbox', array(
		'field' => 'sm_separate_file',
		'errors' => $errors,
		'labels' => $labels,
		'required' => $required,
		'orm_helper' => $helper_orm,
	));
