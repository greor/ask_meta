<?php defined('SYSPATH') or die('No direct access allowed.'); 

	if (empty($SITE_SWITCHER) OR ! $ACL->is_allowed($USER, 'site_switcher', 'show')) {
		return;
	}
?>
	<form class="navbar-form pull-right">
<?php
		echo Form::select('site_id', $SITE_SWITCHER, SITE_ID, array(
			'id' => 'site-switcher',
			'class' => 'span2',
		));
?>
	</form>
	<script>
		$(function(){
			$('#site-switcher').change(function(){
				var query = window.location.search.substr(1).split('&');
				var seg = "";
				
				if (query.length > 0) {
					var tmp = [];
					for(var i = 0; i < query.length; i++) {
						if ( ! query[i]) {
							continue;
						}
						var t = query[i].split('=');
						if (t[0] !== "site_id") {
							tmp.push(t[0]+"="+t[1]);
						}
					}

					if (tmp.length > 0) {
						seg = tmp.join("&")+"&";
					}
				}
				
				window.location.href = window.location.pathname+'?'+seg+"site_id="+$(this).val();
			});
		});
	</script>
