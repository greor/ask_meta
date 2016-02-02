<?php defined('SYSPATH') or die('No direct access allowed.');

	if ($list->count() <= 0) {
		return;
	}

	$edit_tpl = Route::url('admin', array(
		'controller' => 'sites',
		'action' => 'edit',
		'id' => '{id}',
	));
	
	$delete_tpl = Route::url('admin', array(
		'controller' => 'sites',
		'action' => 'delete',
		'id' => '{id}',
	));
	$url_base = URL::base();
?>
	<table class="table table-bordered table-striped kr-table-sites">
		<colgroup>
			<col class="span1">
			<col class="span3">
			<col class="span2">
			<col class="span2">
			<col class="span1">
		</colgroup>
		<thead>
			<tr>
				<th><?php echo __('ID'); ?></th>
				<th><?php echo __('Name'); ?></th>
				<th><?php echo __('Type'); ?></th>
				<th><?php echo __('Code'); ?></th>
				<th><?php echo __('Actions'); ?></th>
			</tr>
		</thead>
		<tbody>
<?php 
		foreach ($list as $_orm):
?>
			<tr>
				<td><?php echo $_orm->id; ?></td>
				<td>
<?php 
					if ( (bool) $_orm->active) {
						echo '<i class="icon-eye-open"></i>&nbsp;';
					} else {
						echo '<i class="icon-eye-open" style="background: none;"></i>&nbsp;';
					}
					echo HTML::chars($_orm->name);
?>
				</td>
				<td><?php echo Kohana::$config->load('_sites.type.'.$_orm->type); ?></td>
				<td>
<?php
					$_link = $url_base.'?'.http_build_query(array(
						'region' => $_orm->code
					));
					echo HTML::anchor($_link, $_orm->code, array(
						'target' => '_blank',
					));
?>					
				</td>
				<td>
<?php 
					if ($ACL->is_allowed($USER, $_orm, 'edit')) {
					
						echo '<div class="btn-group">';
					
							echo HTML::anchor(str_replace('{id}', $_orm->id, $edit_tpl), '<i class="icon-edit"></i> '.__('Edit'), array(
								'class' => 'btn',
								'title' => __('Edit'),
							));
						
							echo '<a class="btn dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span></a>';
							echo '<ul class="dropdown-menu">';
							echo '<li>', HTML::anchor(str_replace('{id}', $_orm->id, $delete_tpl), '<i class="icon-remove"></i> '.__('Delete'), array(
								'class' => 'delete_button',
								'title' => __('Delete'),
							)), '</li>';
							echo '</ul>';
						
						echo '</div>';
					}
?>
				</td>
			</tr>
<?php 
		endforeach;
?>
		</tbody>
	</table>
<?php
		$link = Route::url('admin', array(
			'controller' => 'sites',
		));
		echo $paginator->render($link);
