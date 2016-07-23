<?php defined('SYSPATH') or die('No direct access allowed.'); ?>

	<table class="table js-row-item" style="margin-bottom:0;" data-id="{{id}}">
		<colgroup>
			<col class="span3">
			<col class="span2">
			<col class="span2">
			<col class="span2">
		</colgroup>
		<tbody>
			<tr>
				<td>
					<span class="js-field-title">{{title}}</span>
					<span class="required js-field-required-mark" style="display: none;">*</span>
				</td>
				<td>
					<div class="btn-group">
						<input class="js-field-type" type="hidden" name="set[{{id}}][type]" value="{{type}}" />
						<input class="js-field-position" type="hidden" name="set[{{id}}][position]" value="{{position}}" />
		
						<button class="btn dropdown-toggle" data-toggle="dropdown" type="button">
							<span class="js-field-type-title" style="width:250px;display:inline-block;">Выберете тип поля</span>
							<span class="caret"></span>
						</button>
						<ul class="dropdown-menu js-select-type">
<?php 
						foreach ($field_types as $_k => $_v) {
							echo '<li>', HTML::anchor('#'.$_k, $_v), '</li>';
						}
?>
						</ul>
					</div>
				</td>
				<td class="kr-drop-down">
					<button class="btn js-settings-button" type="button" data-toggle="button" {{^init}}disabled="disabled"{{/init}}>
						<span class="icon-wrench"></span>&nbsp;<?php echo __('Settings'); ?>
					</button>
				</td>
				<td class="kr-action">
<?php
					echo HTML::anchor('#', '<i class="icon-arrow-down"></i>', array(
						'class' => 'btn js-row-down',
						'title' => __('Move down'),
					));
					echo HTML::anchor('#', '<i class="icon-arrow-up"></i>', array(
						'class' => 'btn js-row-up',
						'title' => __('Move up'),
					));
					echo HTML::anchor('#', '<i class="icon-remove"></i>', array(
						'class' => 'btn js-row-delete',
						'title' => __('Delete'),
					));
?>			
				</td>
			</tr>
			<tr class="js-settings-dropdown display-none" style="background-color:#f5f5f5;">
				<td colspan="4">{{{settings}}}</td>
			</tr>
		</tbody>
	</table>


