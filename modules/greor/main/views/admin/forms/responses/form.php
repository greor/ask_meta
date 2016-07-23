<?php defined('SYSPATH') or die('No direct access allowed.');

	echo View_Admin::factory('layout/breadcrumbs', array(
		'breadcrumbs' => $breadcrumbs
	));
	
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
	$view_tpl = Route::url('admin', array(
		'controller' => $CONTROLLER_NAME['responses'],
		'action' => 'view',
		'id' => '{id}',
		'query' => Helper_Page::make_query_string($query_array),
	));
	$mark_tpl = Route::url('admin', array(
		'controller' => $CONTROLLER_NAME['responses'],
		'action' => 'mark',
		'id' => '{id}',
		'query' => Helper_Page::make_query_string($query_array),
	));
?>
	<table class="table table-bordered table-striped">
		<colgroup>
			<col class="span1">
			<col class="span2">
			<col class="span3">
			<col class="span1">
			<col class="span2">
		</colgroup>
		<thead>
			<tr>
				<th><?php echo __('ID'); ?></th>
				<th><?php echo __('E-mail'); ?></th>
				<th><?php echo __('Text'); ?></th>
				<th><?php echo __('Created'); ?></th>
				<th><?php echo __('Actions'); ?></th>
			</tr>
		</thead>
		<tbody>
<?php 
		foreach ($list as $_orm):
			$class = (bool) $_orm->new ? 'new'  : '';
?>
			<tr class="<?php echo $class; ?>">
				<td><?php echo $_orm->id ?></td>
				<td>
<?php
					echo HTML::chars($_orm->email);
?>
				</td>
				<td>
					<div class="list-text-preview">
<?php 
						echo UTF8::substr($_orm->text, 0, 255);
?>
					</div>
				</td>
				<td>
<?php 
					if ($_orm->created != '0000-00-00 00:00:00') {
						echo $_orm->created; 
					}
?>
				</td>
				<td>
<?php 
					echo '<div class="btn-group">';
					
						echo HTML::anchor(str_replace('{id}', $_orm->id, $view_tpl), '<i class="icon-file"></i> '.__('View'), array(
							'class' => 'btn',
							'title' => __('View'),
						));
						
						if ($_orm->new) {
							echo '<a class="btn dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span></a>';
							echo '<ul class="dropdown-menu">';
								echo '<li>', HTML::anchor(str_replace('{id}', $_orm->id, $mark_tpl), '<i class="icon-ok"></i> '.__('Mark as read'), array(
									'title' => __('Mark as read'),
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
	$query_array = array(
		'owner' => $OWNER,
	);
	$filter_query = Request::current()->query('filter');
	if ( ! empty($filter_query)) {
		$query_array['filter'] = $filter_query;
	}
	if ( ! empty($BACK_URL)) {
		$query_array['back_url'] = $BACK_URL;
	}
	$link = Route::url('admin', array(
		'controller' => $CONTROLLER_NAME['structure'],
		'action' => 'form',
		'id' => $orm_form->id,
		'query' => Helper_Page::make_query_string($query_array),
	));
	
	echo $paginator->render($link);
