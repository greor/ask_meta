<?php defined('SYSPATH') or die('No direct script access.'); 

	if ($promo['mode'] == 'external'):
	
		echo $promo['embed_code'];
	
	elseif ($promo['mode'] == 'internal' AND ! empty($promo['list'])):
	
		$indicator_tpl = '
			<li data-target="#carousel-top-banner" data-slide-to="{index}" class="{class}"></li>
		';
		$item_tpl = '
			<div class="item {class}">
				<a href="{link}" target="{target}">
					<img src="{img_1}" alt="{title}" class="hidden-xs">
					<img src="{img_2}" alt="{title}" class="visible-xs-block hidden-sm">
				</a>
			</div>
		';
		$item_notlink_tpl = '
			<div class="item {class}">
				<img src="{img_1}" alt="{title}" class="hidden-xs">
				<img src="{img_2}" alt="{title}" class="visible-xs-block hidden-sm">
			</div>
		';
	
		$indicators = $list = '';
		$index = 0;
		$class = 'active';
		$orm_helper = ORM_Helper::factory('promo');
		$url_base = URL::base();
		foreach ($promo['list'] as $_orm) {
			
			$indicators .= str_replace(array(
				'{index}', '{class}'
			), array(
				$index, $class
			), $indicator_tpl);
			
			
			$src_1 = $orm_helper->file_uri('image_1', $_orm->image_1);
			$thumb_1 = $url_base.Thumb::uri('promo_890x300', $src_1);
			
			$src_2 = empty($_orm->image_2) ? $src_1 : $orm_helper->file_uri('image_2', $_orm->image_1);
			$thumb_2 = $url_base.Thumb::uri('promo_540x300', $src_1);
			
			if (empty($_orm->link)) {
				$list .= str_replace(array(
					'{class}', '{title}', '{img_1}', '{img_2}'
				), array(
					$class, HTML::chars($_orm->title), $thumb_1, $thumb_2
				), $item_notlink_tpl);
			} else {
				$_target = (strpos($_orm->link, '//') === FALSE) ? '_self' : '_blank';
				$list .= str_replace(array(
					'{class}', '{title}', '{img_1}', '{img_2}', '{link}', '{target}'
				), array(
					$class, HTML::chars($_orm->title), $thumb_1, $thumb_2, $_orm->link, $_target
				), $item_tpl);
			}
			
			$class = '';
			$index++;
		}
?>
		<div id="carousel-top-banner" class="carousel slide" data-ride="carousel">
			<ul class="carousel-indicators">
<?php
				echo $indicators;
?>			
			</ul>
			<a data-ajax="false" href="#carousel-top-banner" role="button" data-slide="prev" class="block-navigation medium-button prev"></a>
			<a data-ajax="false" href="#carousel-top-banner" role="button" data-slide="next" class="block-navigation medium-button next"></a>
			<div class="carousel-inner" role="listbox">
<?php
				echo $list;
?>				
			</div>
		</div>
<?php 
	endif;