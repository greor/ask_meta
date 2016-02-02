<?php defined('SYSPATH') or die('No direct access allowed.'); 

	$control_id = empty($control_id) ? $field.'_field' : $control_id;
?>
	<div class="control-group">
		<label class="control-label" for="<?php echo $field; ?>_field">
<?php
			echo __($labels[ $field ]), '&nbsp;:&nbsp;';
?>
		</label>
		<div class="controls">
			<span id="<?php echo $field; ?>" class="plaintext">
<?php 
				echo $text; 
?>
			</span>
		</div>
	</div>
