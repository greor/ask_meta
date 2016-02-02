<?php defined('SYSPATH') or die('No direct access allowed.');

	$orm = $helper_orm->orm();
	$labels = $orm->labels();
	$required = $orm->required_fields();

	if ($orm->loaded()) {
		$action = Route::url('admin', array(
			'controller' => 'admins',
			'action' => 'edit',
			'id' => $orm->id,
		));
	} else {
		$action = Route::url('admin', array(
			'controller' => 'admins',
			'action' => 'edit',
		));
	}

	echo View_Admin::factory('layout/error')->bind('errors', $errors);
?>
	<form method="post" action="<?php echo $action; ?>" enctype="multipart/form-data" class="form-horizontal">
<?php

/**** active ****/

		echo View_Admin::factory('form/checkbox', array(
			'field' => 'active',
			'errors' => $errors,
			'labels' => $labels,
			'required' => $required,
			'orm_helper' => $helper_orm,
		));

/**** site_id ****/

		echo View_Admin::factory('form/control', array(
			'field' => 'site_id',
			'errors' => $errors,
			'labels' => $labels,
			'required' => $required,
			'controls' => Form::select('site_id', $sites, (int) $orm->site_id, array(
				'id' => 'site_id_field',
				'class' => 'input-xxlarge',
			)),
		));
		
/**** role ****/

		echo View_Admin::factory('form/control', array(
			'field' => 'role',
			'errors' => $errors,
			'labels' => $labels,
			'required' => $required,
			'controls' => Form::select('role', $roles, $orm->role, array(
				'id' => 'role_field',
				'class' => 'input-xxlarge',
			)),
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
			)),
		));
		
/**** username ****/

		echo View_Admin::factory('form/control', array(
			'field' => 'username',
			'errors' => $errors,
			'labels' => $labels,
			'required' => $required,
			'controls' => Form::input('username', $orm->username, array(
				'id' => 'username_field',
				'class' => 'input-xxlarge',
			)),
		));

/**** password ****/

		echo View_Admin::factory('form/control', array(
			'field' => 'password',
			'errors' => $errors,
			'labels' => $labels,
			'required' => $required,
			'controls' => Form::password('password', '', array(
				'id' => 'password_field',
				'class' => 'input-xxlarge',
			)),
		));
		
/**** password_confirm ****/

		echo View_Admin::factory('form/control', array(
			'field' => 'password_confirm',
			'errors' => $errors,
			'labels' => $labels,
			'required' => $required,
			'controls' => Form::password('password_confirm', '', array(
				'id' => 'password_confirm_field',
				'class' => 'input-xxlarge',
			)),
		));
		
		echo View_Admin::factory('form/submit_buttons');
?>
	</form>
