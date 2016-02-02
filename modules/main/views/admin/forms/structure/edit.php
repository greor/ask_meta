<?php defined('SYSPATH') or die('No direct access allowed.');

	echo View_Admin::factory('layout/breadcrumbs', array(
		'breadcrumbs' => $breadcrumbs
	));

	$orm = $helper_orm->orm();
	$labels = $orm->labels();
	$required = $orm->required_fields();

	$query_array = array(
		'owner' => $OWNER
	);
	if ( ! empty($BACK_URL)) {
		$query_array['back_url'] = $BACK_URL;
	}
	
	if ($orm->loaded()) {
		$query_array = Paginator::query(Request::current(), $query_array);
		$action = Route::url('admin', array(
			'controller' => $CONTROLLER_NAME['structure'],
			'action' => 'edit',
			'id' => $orm->id,
			'query' => Helper_Page::make_query_string($query_array),
		));
	} else {
		$action = Route::url('admin', array(
			'controller' => $CONTROLLER_NAME['structure'],
			'action' => 'edit',
			'query' => Helper_Page::make_query_string($query_array),
		));
	}
	
	echo View_Admin::factory('layout/error')
		->set('errors', $errors);
?>

	<form method="post" action="<?php echo $action; ?>" enctype="multipart/form-data" class="form-horizontal" >
		<div class="tabbable">
			<ul class="nav nav-tabs">
<?php
				echo '<li class="active">', HTML::anchor('#tab-main', __('Main'), array(
					'data-toggle' => 'tab'
				)), '</li>'; 
				echo '<li>', HTML::anchor('#tab-fields', __('Fields'), array(
					'data-toggle' => 'tab'
				)), '</li>'; 
?>
				<!-- #tab-nav-insert# -->
			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="tab-main">
<?php
					echo View_Admin::factory('forms/structure/tab/main', array(
						'helper_orm' => $helper_orm,
						'errors' => $errors,
					)); 
?>
				</div>
				<div class="tab-pane" id="tab-fields" style="min-height:400px;overflow: visible;">
<?php
					echo View_Admin::factory('forms/structure/tab/fields', array(
						'helper_orm' => $helper_orm,
						'fields_types' => $fields_types,
						'fields_std' => $fields_std,
						'fields' => $fields,
						'errors' => $errors,
					)); 
?>
				</div>
				<!-- #tab-pane-insert# -->
			</div>
		</div>
<?php
		echo View_Admin::factory('form/submit_buttons');
?>	
	</form>
