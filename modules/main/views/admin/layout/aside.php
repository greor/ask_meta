<?php defined('SYSPATH') or die('No direct access allowed.');

	$replace = empty($replace) ? array() : $replace;
	$query = http_build_query(Request::$current->query());
	$query = (empty($query) ? '' : '?').$query;
	$c_url = URL::site(Request::$current->uri());
	$c_url_q = URL::site(Request::$current->uri().$query);
	$left_arr = '';
	
	if ( ! empty($menu_items)): 
?>
	<div class="well">
		<ul id="aside-main" class="nav nav-list">
<?php 
		foreach ($menu_items as $name => $item) {
			if ($item === NULL) {
		 		echo '<li class="divider"></li>';
		 		continue;
			}
			
			if (empty($item)) {
				continue;
			}
		
			foreach ($replace as $marker => $value) {
				$item['link'] = str_replace($marker, $value, $item['link']);
			}
			$li_class = empty($item['class']) ? '' : ' '.$item['class'];
			if (isset($item['use_query_for_active']) AND $item['use_query_for_active'] == FALSE) {
				$li_class .= ($c_url == $item['link']) ? ' active' : '';
			} else {
				$li_class .= ($c_url_q == $item['link']) ? ' active' : '';
			}
			$icon = empty($item['icon']) ? '' : '<i class="'.$item['icon'].'"></i>';
			
			echo '<li class="nav-header', $li_class, '">', 
				 HTML::anchor($item['link'], $icon.$item['title'], array(
				 	'target' => empty($item['target']) ? '_self' : $item['target'],
				 )), '</li>';
				 
			if ( ! empty($item['sub'])) {
				
				foreach ($item['sub'] as $_name => $_item) {
					foreach ($replace as $marker => $value) {
						$_item['link'] = str_replace($marker, $value, $_item['link']);
					}
						
					$_li_class = empty($_item['class']) ? '' : ' '.$_item['class'];
					if (isset($_item['use_query_for_active']) AND $_item['use_query_for_active'] == FALSE) {
						$_li_class .= ($c_url == $_item['link']) ? ' class="active"' : '';
					} else {
						$_li_class .= ($c_url_q == $_item['link']) ? ' class="active"' : '';
					}
					$_icon = '<i class="'.Arr::get($_item, 'icon', 'icon-cog').'"></i>';
					
					echo '<li', $_li_class, '>',
						 HTML::anchor($_item['link'], $_icon.$_item['title'], array(
							'target' => empty($_item['target']) ? '_self' : $_item['target'],
						 )), '</li>';
				}

				echo '<li class="divider"></li>';
			}
		}
?>
		</ul>
		<script>
		$(function(){
			var $list = $("#aside-main .tab-control");
			var $holder = $("#main");

			$("#aside-main .tab-control").on("click", function(e){
				e.preventDefault();

				$holder.find(".nav-tabs [href="+$(this).attr("href")+"]")
					.click();
			});
		});
		</script>
	</div>
<?php 
	endif; 
