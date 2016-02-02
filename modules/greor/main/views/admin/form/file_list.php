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
			echo Form::file($file_field, array(
					'id'		=>	$file_field.'_field',
					'accept'	=>	$file_type,
				));

			echo Form::select($list_field, $list, '', array(
					'class' => 'input-xlarge',
				));
		?>

		<?php if (isset($errors[ $file_field ])): ?>
			<p class="text-error"><?php echo HTML::chars($errors[ $file_field ]); ?></p>
		<?php endif; ?>

		<?php if ( ! empty( $item->$file_field ) ):?>
			<a href="<?php echo URL::base(), HTML::chars( $orm_helper->file_uri($file_field) ); ?>" title="" target="_blank">
				<?php echo HTML::chars($item->$file_field); ?>
			</a>

			<label class="checkbox file-delete-checkbox" for="<?php echo $file_field; ?>_field_delete">
				<?php
					echo Form::checkbox('delete_fields['.$file_field.']', '1', FALSE, array(
									'id' => $file_field.'_field_delete',
								)),
						__('Delete file');
				?>
			</label>

		<?php endif;?>


<!--		<input type="file" id="file_field" name="file" accept="audio/*" />-->
	</div>
</div>

