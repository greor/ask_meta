<?php defined('SYSPATH') or die('No direct access allowed.');

	if (empty($paginator)) {
		return;
	}

	$uid = uniqid();
	
	echo '<div id="pages-std-', $uid, '" class="page-control">';
	if (isset($paginator['previous'])) {
		echo HTML::anchor($paginator['previous'], 'prev', array(
			'class' => 'prev'
		));
	}
	
		echo '<ul class="list">';
		foreach ($paginator['items'] as $_item) {
			$_class = '';
			if (isset($_item['current'])) {
				$_class = 'active';
			}
			
			echo '<li class="', $_class, '">';
			
			if (empty($_class) AND ! empty($_item['link'])) {
				echo HTML::anchor($_item['link'], $_item['title']);
			} else {
				echo '<span>', HTML::chars($_item['title']), '</span>';
			}
			
			echo '</li>';
		}
		echo '</ul>';
		
		if (isset($paginator['next'])) {
			echo HTML::anchor($paginator['next'], 'next', array(
				'class' => 'next'
			));
		}
	
	echo '</div>';
?>
	<div class="paginator" id="pages-<?php echo $uid; ?>"></div>
	<script type="text/javascript">
		$(function(){
			$('#pages-std-<?php echo $uid; ?>').hide();

			var baseUrl = function(num) {
				return '<?php echo $paginator['link']; ?>'+num+'<?php echo $paginator['hash']; ?>';
			};
			
			var pager = new Paginator(
				"pages-<?php echo $uid; ?>",
				<?php echo $paginator['page_count']; ?>,
				10,
				<?php echo $paginator['current']; ?>,
				baseUrl
			);

			var $pager = $('#pages-<?php echo $uid; ?>');
			var hash = "#"+$pager.closest('.tab-pane').attr('id');
			var $navTab = $pager.closest('.tabbable')
				.find("[data-toggle=tab][href="+hash+"]");
			
			if ($navTab.length) {
				$navTab.on('shown', function(){
					Paginator.resizePaginator(pager);
				});
			}
		});
	</script>