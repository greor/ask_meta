<?php defined('SYSPATH') or die('No direct access allowed.');

	$orm = $helper_orm->orm();
	$labels = $orm->labels();
	$required = $orm->required_fields();
	
/**** for_all ****/
	
	if ($ACL->is_allowed($USER, $orm, 'for_all_change')) {
		echo View_Admin::factory('form/checkbox', array(
			'field' => 'for_all',
			'errors' => $errors,
			'labels' => $labels,
			'required' => $required,
			'orm_helper' => $helper_orm,
		));
	}
	
/**** can_hiding ****/
	
	if ($ACL->is_allowed($USER, $orm, 'can_hiding_change')) {
		echo View_Admin::factory('form/checkbox', array(
			'field' => 'can_hiding',
			'errors' => $errors,
			'labels' => $labels,
			'required' => $required,
			'orm_helper' => $helper_orm,
		));
	}
	
/**** parent_id ****/
	
	echo View_Admin::factory('form/control', array(
		'field' => 'parent_id',
		'errors' => $errors,
		'labels' => $labels,
		'required' => $required,
		'controls' => Form::select('parent_id', array(0 => __('- Root page -')) + $pages, (int) $orm->parent_id, array(
			'id' => 'parent_id_field',
			'class' => 'input-xxlarge',
		)),
	));
	
/**** uri ****/
	
	echo View_Admin::factory('form/control', array(
		'field' => 'uri',
		'errors' => $errors,
		'labels' => $labels,
		'required' => $required,
		'controls' => Form::input('uri', $orm->uri, array(
			'id'    => 'uri_field',
			'class' => 'input-xxlarge',
		)).'<label>URL: <div id="uri-preview" base="/base_uri/"></div></label>',
	));
	
/**** status ****/
	
	if ($ACL->is_allowed($USER, $orm, 'status_change')) {
		echo View_Admin::factory('form/control', array(
			'field' => 'status',
			'errors' => $errors,
			'labels' => $labels,
			'required' => $required,
			'controls' => Form::select('status',  Kohana::$config->load('_pages.status'), (int) $orm->status, array(
				'id' => 'status_field',
				'class' => 'input-xxlarge',
			)),
		));
	}
	
/**** page_type ****/
	
	if ($ACL->is_allowed($USER, $orm, 'page_type_change')) {
		echo View_Admin::factory('form/page_type', array(
			'type_field' => 'type',
			'data_field' => 'data',
			'page' => $orm,
			'errors' => $errors,
			'labels' => $labels,
			'required' => $required,
			'modules' => $modules,
			'pages_list' => $pages,
		));
	}	
	
/**** title ****/
	
	echo View_Admin::factory('form/control', array(
		'field' => 'title',
		'errors' => $errors,
		'labels' => $labels,
		'required' => $required,
		'controls' => Form::input('title', $orm->title, array(
			'id' => 'title_field',
			'class' => 'input-xxlarge',
		)),
	));	
	
/**** additional params block ****/
	
	echo View_Admin::factory('form/seo', array(
		'item' => $orm,
		'errors' => $errors,
		'labels' => $labels,
		'required' => $required,
	));
	
	
	
	echo '
		<ul id="base_uri_list" style="display:none;">
			<li id="page_id_0">/</li>
	';
	foreach ($base_uri_list as $id => $base_uri) {
		echo '<li id="page_id_', $id, '">/', $base_uri, '/</li>';
	}
	echo '
		</ul>
	';
?>
	<script>
		$(function(){
			var $uriPreview = $('#uri-preview');
			$('#uri_field').change(function(){
				$uriPreview.html($uriPreview.attr('base') + $(this).val());
			}).keyup(function(){
				$(this).triggerHandler('change');
			}).triggerHandler('change');

			$('#parent_id_field').change(function(){
				var uri = $('#page_id_'+$(this).find(':selected').val()).text();
				$uriPreview.attr('base', uri);
				$('#uri_field').triggerHandler('change');
			}).triggerHandler('change');
		});
	</script>



