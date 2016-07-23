<?php defined('SYSPATH') or die('No direct access allowed.');

	$orm = $helper_orm->orm();
	$labels = array(
		'noname_type' => 'Type',
		'noname_name' => 'Name',
		'noname_mmt' => 'MMT',
		'noname_code' => 'Code',
	) + $orm->labels();
	$required = array(
		'code'
	) + $orm->required_fields();
	
/**** active ****/

	if ($ACL->is_allowed($USER, $orm, 'active_change')) {
		echo View_Admin::factory('form/checkbox', array(
			'field' => 'active',
			'errors' => $errors,
			'labels' => $labels,
			'required' => $required,
			'orm_helper' => $helper_orm,
		));
	}

/**** type ****/

	if ($orm->id == SITE_ID_MASTER OR ! $ACL->is_allowed($USER, $orm, 'edit_type')) {
		echo View_Admin::factory('form/control', array(
			'field' => 'noname_type',
			'errors' => $errors,
			'labels' => $labels,
			'required' => $required,
			'controls' => Form::input('noname_type', Kohana::$config->load('_sites.type.'.$orm->type), array(
				'id' => 'noname_type_field',
				'class' => 'input-xxlarge',
				'readonly' => 'readonly',
			)),
		));
	} else {
		$_types = Kohana::$config->load('_sites.type');
		unset($_types['master']);
		
		echo View_Admin::factory('form/control', array(
			'field' => 'type',
			'errors' => $errors,
			'labels' => $labels,
			'required' => $required,
			'controls' => Form::select('type', $_types, $orm->type, array(
				'id' => 'type_field',
				'class' => 'input-xxlarge',
			)),
		));
	}


/**** name ****/

	if ( ! $ACL->is_allowed($USER, $orm, 'edit_name')) {
		echo View_Admin::factory('form/control', array(
			'field' => 'noname_name',
			'errors' => $errors,
			'labels' => $labels,
			'required' => $required,
			'controls' => Form::input('noname_name', $orm->name, array(
				'id' => 'noname_name_field',
				'class' => 'input-xxlarge',
				'readonly' => 'readonly',
			)),
		));
	} else {
		echo View_Admin::factory('form/control', array(
			'field' => 'name',
			'errors' => $errors,
			'labels' => $labels,
			'required' => $required,
			'controls' => Form::input('name', $orm->name, array(
				'id' => 'name_field',
				'class' => 'input-xxlarge',
			)),
		));
	}

/**** code ****/

	if ($orm->id != SITE_ID_MASTER) {
		if ( ! $ACL->is_allowed($USER, $orm, 'edit_code') ) {
			echo View_Admin::factory('form/control', array(
				'field' => 'noname_code',
				'errors' => $errors,
				'labels' => $labels,
				'required' => $required,
				'controls' => Form::input('noname_code', $orm->code, array(
					'id' => 'noname_code_field',
					'class' => 'input-xxlarge',
					'readonly' => 'readonly',
				)),
			));
		} else {
			echo View_Admin::factory('form/control', array(
				'field' => 'code',
				'errors' => $errors,
				'labels' => $labels,
				'required' => $required,
				'controls' => Form::input('code', $orm->code, array(
					'id' => 'code_field',
					'class' => 'input-xxlarge',
				)),
			));
		}
	} 

/**** logo ****/
		
	echo View_Admin::factory('form/image', array(
		'field' => 'logo',
		'value' => $orm->logo,
		'orm_helper' => $helper_orm,
		'errors' => $errors,
		'labels' => $labels,
		'required' => $required,
	));
		
/**** image ****/
		
	if ($orm->id != SITE_ID_MASTER) {
		echo View_Admin::factory('form/image', array(
			'field' => 'image',
			'value' => $orm->image,
			'orm_helper' => $helper_orm,
			'errors' => $errors,
			'labels' => $labels,
			'required' => $required,
		));
	}

/**** mmt ****/

	if ( ! $ACL->is_allowed($USER, $orm, 'edit_mmt')) {
		echo View_Admin::factory('form/control', array(
			'field' => 'noname_mmt',
			'errors' => $errors,
			'labels' => $labels,
			'required' => $required,
			'controls' => Form::input('noname_mmt', $orm->mmt, array(
				'id' => 'noname_mmt_field',
				'class' => 'input-xxlarge',
				'readonly' => 'readonly',
			)),
		));
	} else {
		echo View_Admin::factory('form/control', array(
			'field' => 'mmt',
			'errors' => $errors,
			'labels' => $labels,
			'required' => $required,
			'controls' => Form::select('mmt', Kohana::$config->load('_sites.mmt'), $orm->mmt, array(
				'id' => 'mmt_field',
				'class' => 'input-xxlarge',
			)),
		));
	}
	
/**** sharing_image ****/
		
	echo View_Admin::factory('form/image', array(
		'field' => 'sharing_image',
		'value' => $orm->sharing_image,
		'orm_helper' => $helper_orm,
		'errors' => $errors,
		'labels' => $labels,
		'required' => $required,
	));

/**** additional params block ****/

	echo View_Admin::factory('form/seo', array(
		'item' => $orm,
		'errors' => $errors,
		'labels' => $labels,
		'required' => $required,
	));
	