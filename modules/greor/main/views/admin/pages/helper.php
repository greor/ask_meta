<?php defined('SYSPATH') or die('No direct access allowed.');

function draw_sub($childrens, $reference, $tpl, $inactive)
{
	static $depth;
	$depth = (isset($depth)) ? ++$depth : 0;
	
	$ACL = $reference['ACL'];
	$USER = $reference['USER'];
	$modules = $reference['modules'];
	$base_uri_list = $reference['base_uri_list'];
	$status_codes = $reference['status_codes'];
	$page_types = $reference['page_types'];
	$tpl_array = $reference['tpl_array'];
	$hided_list = $reference['hided_list'];
	$query_region = $reference['query_region'];

	$_list = array();
	foreach ($childrens as $_item) {
		$item = $_item['object'];
		$tpl_array = $reference['tpl_array'];
		
		$_attr = array();
		$_attr_title = array();
		
		if ( strpos($base_uri_list[ $item->id ], 'http') === 0 ) {
			$_uri = $base_uri_list[ $item->id ];
		} else {
			$_uri = URL::base().$base_uri_list[ $item->id ];
		}
		$_uri .= $query_region;
		
		$_status_icon = ($item->level > 1) 
			? '<div class="marker"></div>'
			: '';
			
		switch ($item->status) {
			case $status_codes['inactive']:
				$_attr[] = 'inactive';
				$_attr_title[] = 'Неактивно';
				$_link = '<span>'.$_uri.'</span>';
				$_status_icon .= '<i class="icon-ban-circle icon"></i> ';
				break;
			case $status_codes['hidden']:
				$_link = HTML::anchor($_uri, $_uri, array(
					'target' => '_blank',
				));
				$_status_icon .= '<i class="icon-asterisk icon"></i> ';
				break;
			case $status_codes['active']:
				$_link = HTML::anchor($_uri, $_uri, array(
					'target' => '_blank',
				));
				$_status_icon .= '<i class="icon-eye-open icon"></i> ';
				break;
		}
		
		if (in_array($item->id, $hided_list)) {
			$_attr[] = 'hided-element';
		}
		
		if ($item->for_all) {
			$_title = "<strong>{$item->title}</strong>";
		} else {
			$_title = $item->title;
		}
		
		$__list = array();
		if ($ACL->is_allowed($USER, $item, 'edit')) {
			if ( ! Helper_Page::instance()->not_equal($item, 'type', 'module') OR ! empty($item->name)) {
				unset($tpl_array['delete_tpl']);
			}
			unset($tpl_array['visibility_tpl']);
			foreach($tpl_array as $__key => $__tpl) {
				$__list[] = str_replace('--ID--', $item->id, $__tpl);
			}
		} else {
			foreach($tpl_array as $__key => $__tpl) {
				if ($__key == 'visibility_tpl') {
					if ($ACL->is_allowed($USER, $item, 'can_hide')) {
						if (in_array($item->id, $hided_list)) {
							$__list[] = str_replace(array(
								'--ID--','--mode--', '--TITLE--', '--icon-class--'
							), array(
								$item->id, 'show', __('Show'), 'icon-eye-open'
							), $__tpl);
						} else {
							$__list[] = str_replace(array(
								'--ID--','--mode--', '--TITLE--', '--icon-class--'
							), array(
								$item->id, 'hide', __('Hide'), 'icon-eye-close'
							), $tpl_array['visibility_tpl']);
						}
					}
					break;
				}
			}
		}
		$_actions = implode('', $__list);
		
		if ($item->type == 'module') {
			$_descr = __( $modules[ $item->data ]['name'] );
		} else {
			$_descr = $page_types[ $item->type ];
		}
		
		$_childrens = '';
		if ( ! empty($_item['childrens'])) {
			$_childrens = draw_sub(
				$_item['childrens'],
				$reference,
				$tpl,
				($inactive || ($item->status == $status_codes['inactive']))
			);
		};
		
		$_attr = ' class="'.implode(' ', $_attr).'"';
		if ( ! empty($_attr_title)) {
			$_attr .= ' title="'.implode(' ', $_attr_title).'"';
		}
		
		$_list[] = str_replace(
			array('{ATTR}', '{STATUS_ICONS}', '{ACTIONS}', '{TITLE}', '{LINK}', '{DESCRIPTION}', '{CHILDRENS}'),
			array($_attr, $_status_icon, $_actions, $_title, $_link, $_descr, $_childrens),
			$tpl
		);
		
	}
	
	$class = ($depth > 0)
		? 'sub'
		: 'list-pages';
	
	$depth--;
	
	return '<ul class="'.$class.'">'.implode('', $_list).'</ul>';
}