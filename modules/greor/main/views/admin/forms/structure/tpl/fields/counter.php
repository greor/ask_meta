<?php defined('SYSPATH') or die('No direct access allowed.'); ?>

	<div class="js-settigs-holder" data-type="counter">
		<input class="js-field-id" type="hidden" name="set[{{id}}][id]" value="{{id}}" />
		<div class="control-group">
			<label class="control-label" for="js-field-label-{{id}}">Название<span class="required">*</span>:</label>
			<div class="controls">
				<input class="js-field-label" id="js-field-label-{{id}}" type="text" name="set[{{id}}][title]" value="{{title}}" />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="js-field-default-{{id}}">Значение по умолчанию:</label>
			<div class="controls">
				<input class="js-field-default" id="js-field-default-{{id}}" type="text" name="set[{{id}}][default]" value="{{default}}" />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="js-field-required-{{id}}">Обязательное поле:</label>
			<div class="controls">
				<input type="hidden" name="set[{{id}}][required]" value="0" />
				<input class="js-field-required" type="checkbox" id="js-field-required-{{id}}" name="set[{{id}}][required]" value="1" {{#required}}checked="checked"{{/required}} />
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label" for="js-field-range-{{id}}">Диапазон значений:</label>
			<div class="controls">
			{{#range}}
				<input class="small" id="js-field-range-{{id}}" type="text" name="set[{{id}}][range][from]" value="{{from}}" placeholder="0" />
				&nbsp;&ndash;&nbsp;
				<input class="small" type="text" name="set[{{id}}][range][to]" value="{{to}}" placeholder="100" />
			{{/range}}
			{{^range}}
				<input class="small" id="js-field-range-{{id}}" type="text" name="set[{{id}}][range][from]" value="" placeholder="0" />
				&nbsp;&ndash;&nbsp;
				<input class="small" type="text" name="set[{{id}}][range][to]" value="" placeholder="100" />
			{{/range}}
			</div>
		</div>
	</div>