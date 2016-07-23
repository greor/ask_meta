<?php defined('SYSPATH') or die('No direct access allowed.'); 

	$required = empty($required) ? array() : $required;
	$errors = empty($errors) ? array() : $errors;

	$controls = Form::hidden($field, $value, array(
		'id' => 'song_id_hidden'
	));
	$controls .= '<div class="input-append input-song">'.Form::input('disabled_field[song_name]', $song_name, array(
		'class' => 'input-xlarge',
		'readonly' => 'readonly',
		'id' => 'song_id_name'
	));
	$controls .= HTML::anchor('#', __('Edit'), array(
		'class' => 'btn js-action-edit',
		'data-source' => $singers_list_link,
	)).'</div>';
	
	echo View_Admin::factory('form/control', array(
		'field' => $field,
		'errors' => $errors,
		'labels' => $labels,
		'required' => $required,
		'controls_class' => 'song-controls',
		'controls' => $controls,
	));

	echo View_Admin::factory('form/song/modal');
?>
	<script type="text/javascript" src="<?php echo $ASSETS; ?>vendor/dynatable-0.3.1/jquery.dynatable.js"></script>
	<script type="text/javascript" src="<?php echo $ASSETS; ?>js/songs.js"></script>

