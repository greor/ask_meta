<?php defined('SYSPATH') or die('No direct access allowed.');

	echo View_Admin::factory('layout/breadcrumbs', array(
		'breadcrumbs' => $breadcrumbs
	));

	$labels = $orm->labels();
	$required = $orm->required_fields();
?>

	<form method="post" action="#" enctype="multipart/form-data" class="form-horizontal" >
<?php
/**** email ****/
		
		echo View_Admin::factory('form/control', array(
			'field' => 'email',
			'labels' => $labels,
			'required' => $required,
			'controls' => Form::input('email', $orm->email, array(
				'id' => 'email_field',
				'class' => 'input-xxlarge',
				'readonly' => 'readonly',
			))
		));
		
/**** created ****/
		
		echo View_Admin::factory('form/control', array(
			'field' => 'created',
			'labels' => $labels,
			'required' => $required,
			'controls' => Form::input('created', $orm->created, array(
				'id' => 'created_field',
				'class' => 'input-xxlarge',
				'readonly' => 'readonly',
			))
		));
		
/**** text ****/
		
		echo View_Admin::factory('form/control', array(
			'field' => 'text',
			'labels' => $labels,
			'required' => $required,
			'controls' => Form::textarea('text', $orm->text, array(
				'id' => 'text_field',
				'class' => 'text-area-clear',
				'readonly' => 'readonly',
			)),
		));
		
		echo View_Admin::factory('form/back_button', array(
			'link' => $BACK_URL
		));
?>		
	</form>
