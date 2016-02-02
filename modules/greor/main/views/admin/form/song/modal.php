<?php defined('SYSPATH') or die('No direct access allowed.'); ?>

	<div id="songsModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h3 id="myModalLabel"><?php echo __('Songs'); ?></h3>
		</div>
		<div class="modal-body">
			<div class="singers-list">
				<table class="table table-hover table-bordered">
					<thead>
						<tr><th><?php echo __('Singer'); ?></th></tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
		<div class="modal-footer">
			<button class="btn btn-primary apply-changes" data-dismiss="modal" aria-hidden="true"><?php echo __('Save'); ?></button>
			<button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo __('Cancel'); ?></button>
		</div>
	</div>