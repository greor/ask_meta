<?php defined('SYSPATH') or die('No direct access allowed.'); ?>

	<div class="btn-group pull-right" style="margin-right:8px;">
		<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
<?php 
			echo __('Actions'); 
?>
			<span class="caret"></span>
		</button>

		<ul class="dropdown-menu">
<?php
			echo '<li>', HTML::anchor('#', __('Add field'), array(
				'class' => 'js-add-row',
				'title' => __('Add field'),
			)), '</li>';
			echo '<li>', HTML::anchor('#', __('Add standard fields'), array(
				'class' => 'js-add-standard-rows',
				'title' => __('Full name, city, email, phone'),
			)), '</li>';
			echo '<li class="divider"></li>';
			echo '<li>', HTML::anchor('#', __('Delete all fields'), array(
				'class' => 'js-delete-all',
				'title' => __('Delete all'),
			)), '</li>';
?>		
		</ul>
		<script>
			window.stdRows = <?php echo json_encode($fields_std); ?>;
		</script>
	</div>
	<div class="clearfix"></div>
	<br>
	
	<div class="js-row-holder"></div>
	
	<script id="tpl-row" type="text/x-tmpl-mustache">
<?php
		echo View_Admin::factory('forms/structure/tpl/row', array(
			'field_types' => $fields_types,
		));
?>
	</script>
<?php
	foreach ($fields_types as $_k => $_v) {
		echo '
			<script id="tpl-settings-'.$_k.'" type="text/x-tmpl-mustache">
				'.View_Admin::factory('forms/structure/tpl/fields/'.$_k).'
			</script>
		';
	}
?>	
	<script>
		window.renderRows = <?php echo json_encode($fields); ?>;
	</script>
	<script type="text/javascript" src="<?php echo $ASSETS; ?>js/forms.js"></script>
