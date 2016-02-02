<?php defined('SYSPATH') or die('No direct access allowed.'); ?>

	<div class="row">
		<div class="span9" style="text-align: right;">
			<form class="form-inline">
<?php 
				$uid = uniqid();
				echo Form::select('page_id', $options, $selected, array(
					'id' => 'select-'.$uid
				)); 
?>
			</form>
			<script>
				$(document).ready(function(){
					$('#select-<?php echo $uid; ?>').change(function(){
						var _value = $('option:selected', '#select-<?php echo $uid; ?>').val();
						window.location = window.location.pathname + '?<?php echo $name; ?>=' + _value;
					});
				});
			</script>
		</div>
	</div>