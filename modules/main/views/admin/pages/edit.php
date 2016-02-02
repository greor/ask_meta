<?php defined('SYSPATH') or die('No direct access allowed.');

	$orm = $helper_orm->orm();
	$labels = $orm->labels();
	$required = $orm->required_fields();

	if ($orm->loaded()) {
		$action = Route::url('admin', array(
			'controller' => 'pages',
			'action' => 'edit',
			'id' => $orm->id,
		));
	} else {
		$action = Route::url('admin', array(
			'controller' => 'pages',
			'action' => 'edit',
		));
	}

	echo View_Admin::factory('layout/error')
		->bind('errors', $errors);
?>
	<form method="post" action="<?php echo $action; ?>" enctype="multipart/form-data" class="form-horizontal" >
<div class="tabbable">
			<ul class="nav nav-tabs kr-nav-tsbs">
<?php
				echo '<li class="active">', HTML::anchor('#tab-main', __('Main'), array(
					'data-toggle' => 'tab'
				)), '</li>'; 
				echo '<li>', HTML::anchor('#tab-description', __('Description'), array(
					'data-toggle' => 'tab'
				)), '</li>'; 
				echo '<li>', HTML::anchor('#tab-sitemap', __('Sitemap'), array(
					'data-toggle' => 'tab'
				)), '</li>'; 
				
				if ( ! empty($properties)) {
					echo '<li>', HTML::anchor('#tab-properties', __('Properties'), array(
						'data-toggle' => 'tab'
					)), '</li>';
				}
?>
				<!-- #tab-nav-insert# -->
			</ul>
			<div class="tab-content kr-tab-content">
				<div class="tab-pane kr-tab-pane active" id="tab-main">
<?php
					echo View_Admin::factory('admin/pages/tab/main', array(
						'helper_orm' => $helper_orm,
						'errors' => $errors,
						'pages' => $pages,
						'modules' => $modules,
						'base_uri_list' => $base_uri_list,
					)); 
?>
				</div>
				<div class="tab-pane kr-tab-pane" id="tab-description">
<?php
					echo View_Admin::factory('admin/pages/tab/description', array(
						'helper_orm' => $helper_orm,
						'errors' => $errors,
					)); 
?>
				</div>
				<div class="tab-pane kr-tab-pane" id="tab-sitemap">
<?php
					echo View_Admin::factory('admin/pages/tab/sitemap', array(
						'helper_orm' => $helper_orm,
						'errors' => $errors,
					));
?>
				</div>
<?php
				if ( ! empty($properties)):
?>				
					<div class="tab-pane kr-tab-pane" id="tab-properties">
<?php
						echo View_Admin::factory('form/property/list', array(
							'properties' => $properties,
						)); 
?>
					</div>
<?php
				endif;
?>				
				<!-- #tab-pane-insert# -->
			</div>
		</div>
<?php 


		echo View_Admin::factory('form/submit_buttons');
?>
	</form>
