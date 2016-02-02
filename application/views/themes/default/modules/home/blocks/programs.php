<?php defined('SYSPATH') or die('No direct script access.'); 

	if (empty($programs['list'])) {
		return;
	}
	
	$page = Page_Route::page_by_alias('programs');
	if ( ! $page) {
		return;
	}

	$list = $programs['list'];
	$properties = $programs['properties'];
	
	$link_tpl = URL::base().Page_Route::uri($page['id'], 'programs', array(
		'uri' => '{uri}',
	));
?>
	<div class="row block all-programms">
		<div class="block-title yellow">Программы эфира</div>
		<div class="row block-content col-center-content max-height" item-bottom-margin="25">
<?php
		foreach ($list as $_orm):
			$_prop_icon = Arr::path($properties, $_orm->id.'.ProgramIcon.value', array());
			$_icon = reset($_prop_icon);
			if ($_icon) {
				$_icon = $_icon['key'];
			}
				
			$_link = str_replace('{uri}', $_orm->uri, $link_tpl);
?>		
			<div class="col-md-15 col-sm-15 col-xs-4 col-xxs-6 obj">
				<a href="<?php echo $_link; ?>">
					<span class="icon <?php echo $_icon; ?>"></span><?php echo HTML::chars($_orm->title); ?>
				</a>
			</div>
<?php
		endforeach;
?>			
		</div>
	</div>

