<?php defined('SYSPATH') or die('No direct access allowed.');

	$orm = $helper_orm->orm();
	$labels = array(
		'noname_type' => 'Type',
		'noname_name' => 'Site name',
		'noname_mmt' => 'MMT',
		'noname_code' => 'Code',
	) + $orm->labels();
	$required = array(
		'code'
	) + $orm->required_fields();
	
	
	if ($orm->loaded()) {
		$action = Route::url('admin', array(
			'controller' => 'sites',
			'action' => 'edit',
			'id' => $orm->id,
		));
	} else {
		$action = Route::url('admin', array(
			'controller' => 'sites',
			'action' => 'edit',
		));
	}

	echo View_Admin::factory('layout/error')
		->bind('errors', $errors);
?>
	<form method="post" action="<?php echo $action; ?>" enctype="multipart/form-data" class="form-horizontal">
		<div class="tabbable">
			<ul class="nav nav-tabs kr-nav-tsbs">
<?php
				echo '<li class="active">', HTML::anchor('#tab-main', __('Main'), array(
					'data-toggle' => 'tab'
				)), '</li>'; 
				echo '<li>', HTML::anchor('#tab-properties', __('Properties'), array(
					'data-toggle' => 'tab'
				)), '</li>'; 
?>
			</ul>
			<div class="tab-content kr-tab-content">
				<div class="tab-pane kr-tab-pane active" id="tab-main">
<?php
					echo View_Admin::factory('sites/tab/main', array(
						'helper_orm' => $helper_orm,
						'errors' => $errors,
					)); 
?>
				</div>
				<div class="tab-pane kr-tab-pane" id="tab-properties">
<?php
					echo View_Admin::factory('form/property/list', array(
						'properties' => $properties,
					)); 
?>
				</div>
			</div>
		</div>
		<div class="form-actions">
			<button class="btn btn-primary" type="submit" name="submit" value="save" ><?php echo __('Save'); ?></button>
<?php 
			if (IS_SUPER_USER): 
?>
				<button class="btn btn-primary" type="submit" name="submit" value="save_and_exit" ><?php echo __('Save and Exit'); ?></button>
<?php 
			endif; 
?>
			<button class="btn" name="cancel" value="cancel"><?php echo __('Cancel'); ?></button>
		</div>
	</form>