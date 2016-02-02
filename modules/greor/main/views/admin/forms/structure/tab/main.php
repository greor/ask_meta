<?php defined('SYSPATH') or die('No direct access allowed.');

	$orm = $helper_orm->orm();
	$labels = $orm->labels();
	$required = $orm->required_fields();

/**** owner ****/
	
	echo Form::hidden('owner', $OWNER);

/**** active ****/
	
	echo View_Admin::factory('form/checkbox', array(
		'field' => 'active',
		'errors' => $errors,
		'labels' => $labels,
		'required' => $required,
		'orm_helper' => $helper_orm,
	));

/**** captcha ****/
	
	echo View_Admin::factory('form/checkbox', array(
		'field' => 'captcha',
		'errors' => $errors,
		'labels' => $labels,
		'required' => $required,
		'orm_helper' => $helper_orm,
	));

/**** public_date ****/
	
	echo View_Admin::factory('form/date', array(
		'field' => 'public_date',
		'errors' => $errors,
		'labels' => $labels,
		'required' => $required,
		'orm' => $orm,
	));

/**** close_date ****/
	
	echo View_Admin::factory('form/date', array(
		'field' => 'close_date',
		'errors' => $errors,
		'labels' => $labels,
		'required' => $required,
		'orm' => $orm,
		'set_default' => FALSE,
	));

/**** title ****/

	echo View_Admin::factory('form/control', array(
		'field' => 'title',
		'errors' => $errors,
		'labels' => $labels,
		'required' => $required,
		'controls' => Form::input('title', $orm->title, array(
			'id' => 'title_field',
			'class' => 'input-xxlarge',
		))
	));
	
/**** email ****/

	echo View_Admin::factory('form/control', array(
		'field' => 'email',
		'errors' => $errors,
		'labels' => $labels,
		'required' => $required,
		'controls' => Form::input('email', $orm->email, array(
			'id' => 'email_field',
			'class' => 'input-xxlarge',
		))
	));

/**** text_show_top ****/

	echo View_Admin::factory('form/checkbox', array(
		'field' => 'text_show_top',
		'errors' => $errors,
		'labels' => $labels,
		'required' => $required,
		'orm_helper' => $helper_orm,
	));
	
/**** text ****/

	echo View_Admin::factory('form/control', array(
		'field' => 'text',
		'errors' => $errors,
		'labels' => $labels,
		'required' => $required,
		'controls' => Form::textarea('text', $orm->text, array(
			'id' => 'text_field',
			'class' => 'text_editor',
		)),
	));
	
?>

