<?php defined('SYSPATH') or die('No direct access allowed.'); ?>

	<div class="row">
		<div class="span9" style="text-align: right;">
			<form class="form-inline">
				<label for="page_select"><?php echo __('Page'); ?>:</label>
<?php 
				echo Form::select('page_id', $MODULE_PAGES, $MODULE_PAGE_ID, array(
					'id' => 'page_select'
				)); 
?>
			</form>
			<script>
				$(document).ready(function(){
					$('#page_select').change(function(){
						var _page = $('option:selected', '#page_select').val();
						window.location = window.location.pathname + '?page=' + _page;
					});
				});
			</script>
		</div>
	</div>