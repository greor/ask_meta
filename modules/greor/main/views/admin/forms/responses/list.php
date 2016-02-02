<?php defined('SYSPATH') or die('No direct access allowed.');

	echo View_Admin::factory('layout/breadcrumbs', array(
		'breadcrumbs' => $breadcrumbs
	));
	
	if (Request::current()->is_initial()) {
		echo View_Admin::factory('layout/select', array(
			'options' => $OWNER_LIST,
			'selected' => $OWNER,
			'name' => 'owner',
		));
	}

	if ($list->count() <= 0) {
		return;
	}
	
	$query_array = array(
		'owner' => $OWNER,
	);
	
	if ( ! empty($BACK_URL)) {
		$query_array['back_url'] = $BACK_URL;
	}
	
	$query_array = Paginator::query(Request::current(), $query_array);
	$edit_tpl = Route::url('admin', array(
		'controller' => $CONTROLLER_NAME['structure'],
		'action' => 'edit',
		'id' => '{id}',
		'query' => Helper_Page::make_query_string($query_array),
	));
	
	$open_tpl = Route::url('admin', array(
		'controller' => $CONTROLLER_NAME['responses'],
		'action' => 'form',
		'id' => '{id}',
		'query' => Helper_Page::make_query_string($query_array),
	));
?>
	<table class="table table-bordered table-striped">
		<colgroup>
			<col class="span1">
			<col class="span4">
			<col class="span1">
			<col class="span1">
			<col class="span2">
		</colgroup>
		<thead>
			<tr>
				<th><?php echo __('ID'); ?></th>
				<th><?php echo __('Title'); ?></th>
				<th><?php echo __('Public date'); ?></th>
				<th><?php echo __('Close date'); ?></th>
				<th><?php echo __('Actions'); ?></th>
			</tr>
		</thead>
		<tbody>
<?php 
		foreach ($list as $_orm):
?>
			<tr>
				<td><?php echo $_orm->id ?></td>
				<td>
<?php
					if ( (bool) $_orm->active) {
						echo '<i class="icon-eye-open"></i>&nbsp;';
					} else {
						echo '<i class="icon-eye-open" style="background: none;"></i>&nbsp;';
					}
					echo HTML::chars($_orm->title);
?>
				</td>
				<td><?php echo $_orm->public_date; ?></td>
				<td>
<?php 
					if ($_orm->close_date != '0000-00-00 00:00:00') {
						echo $_orm->close_date; 
					}
?>
				</td>
				<td>
<?php 
					echo '<div class="btn-group">';
					
						echo HTML::anchor(str_replace('{id}', $_orm->id, $open_tpl), '<i class="icon-envelope"></i> '.__('Messages'), array(
							'class' => 'btn',
							'title' => __('Messages'),
						));
						
						if ($ACL->is_allowed($USER, $_orm, 'edit')) {
							echo '<a class="btn dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span></a>';
							echo '<ul class="dropdown-menu">';
								echo '<li>', HTML::anchor(str_replace('{id}', $_orm->id, $edit_tpl), '<i class="icon-edit"></i> '.__('Edit'), array(
									'title' => __('Edit'),
								)), '</li>';
							echo '</ul>';
						}
					echo '</div>';
?>
				</td>
			</tr>
<?php 
		endforeach;
?>
		</tbody>
	</table>
<?php
	if (empty($BACK_URL)) {
		$query_array = array(
			'owner' => $OWNER,
		);
		if ( ! empty($BACK_URL)) {
			$query_array['back_url'] = $BACK_URL;
		}
		$link = Route::url('admin', array(
			'controller' => $CONTROLLER_NAME['responses'],
			'query' => Helper_Page::make_query_string($query_array),
		));
	} else {
		$link = $BACK_URL;
	}
	
	echo $paginator->render($link);
