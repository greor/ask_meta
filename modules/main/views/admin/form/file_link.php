<?php
	$item = $orm_helper->orm();
?>
<div class="control-group <?php if (isset($errors[ $file_field ])) echo 'error' ?>">
	<label class="control-label" for="<?php echo $file_field; ?>_field">
		<?php
			echo __($labels[ $file_field ]),
				in_array($file_field, $required) ? '<span class="required">*</span>' : '';
		?>&nbsp;:
	</label>

	<div class="controls">
	<?php
		if ( ! empty( $item->$file_field ) )
		{
			echo HTML::anchor($orm_helper->file_uri( $file_field ), __('Download'), array(
				'class'		=> 'file-link',
				'target' 	=> '_blank',
			));
		}
	?>
	</div>
</div>

