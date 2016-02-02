<?php defined('SYSPATH') or die('No direct access allowed.');

	if ($list->count() <= 0) {
		return;
	}

	$edit_tpl = Route::url('admin', array(
		'controller' => 'admins',
		'action' => 'edit',
		'id' => '{id}',
	));

	$delete_tpl = Route::url('admin', array(
		'controller' => 'admins',
		'action' => 'delete',
		'id' => '{id}',
	));
?>

	<table class="table table-bordered table-striped">
		<colgroup>
			<col class="span1">
			<col class="span5">
			<col class="span1">
			<col class="span1">
			<col class="span1">
		</colgroup>
		<thead>
			<tr>
				<th><?php echo __('ID'); ?></th>
				<th><?php echo __('Login'); ?></th>
				<th><?php echo __('Role'); ?></th>
				<th><?php echo __('Last login'); ?></th>
				<th><?php echo __('Actions'); ?></th>
			</tr>
		</thead>
		<tbody>
<?php 
		foreach ($list as $_orm):
			if ( (bool) $_orm->active) {
				$active_action_class = 'icon-eye-close';
				$active_action_title = __('Deactivate');
			} else {
				$active_action_class = 'icon-eye-open';
				$active_action_title = __('Activate');
			}
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
					echo HTML::chars($_orm->username);
?>					
				</td>
				<td><?php echo $_orm->role; ?></td>
				<td><?php echo $_orm->last_login ? date('Y-m-d H:i', $_orm->last_login) : '---' ?></td>
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
	$link = Route::url( 'admin', array(
		'controller' => 'admins',
	));

	echo $paginator->render($link);

