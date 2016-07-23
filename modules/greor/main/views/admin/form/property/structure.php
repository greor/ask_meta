<?php defined('SYSPATH') or die('No direct access allowed.'); ?>

	<div class="tabbable">
<?php
		$nav_tpl = '
			<li class="{CLASS}">
				<a href="#{ID}" data-toggle="tab">{TITLE}</a>
			</li>
		';
		$content_tpl = '
			<div class="tab-pane {CLASS}" id="{ID}">{CONTENT}</div>
		';
		
		$nav_list = $content_list = array();
		$class = 'active';
		foreach ($item['value'] as $_t => $_i) {
			$_id = md5($_i['name']);
			$nav_list[] = str_replace(array(
				'{CLASS}', '{ID}', '{TITLE}'
			), array(
				$class, $_id, __($_t)
			), $nav_tpl);
			
			$_content = View_Admin::factory('form/property/field', array(
				'item' => $_i,
				'title' => $_t
			));
			
			$content_list[] = str_replace(array(
				'{CLASS}', '{ID}', '{CONTENT}'
			), array(
				$class, $_id, $_content
			), $content_tpl);
			$class = '';
		}
?>	
		<ul class="nav nav-tabs"><?php echo implode('', $nav_list);?></ul>
		<div class="tab-content"><?php echo implode('', $content_list);?></div>
	</div>