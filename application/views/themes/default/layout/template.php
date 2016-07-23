<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta charset="utf-8">
	
	<link rel="icon" href="<?php echo $ASSETS; ?>i/favicon.ico" type="image/x-icon"/>
	<title><?php echo HTML::chars($TITLE); ?></title>
<?php
	echo View_Theme::factory('layout/head/tag');
?>
	<script>
		var s = {};
		s.initList = [];
		s.siteName = "<?php echo $SITE['name'];?>";
	</script>
</head>
<body>
<?php 
	echo View_Theme::factory('layout/menu/main', array(
		'menu' => $menu
	));
	
	echo $content;
?>
	<script src="<?php echo $ASSETS; ?>js/plugins/jquery-1.11.3.min.js"></script>
	<script src="<?php echo $ASSETS; ?>js/plugins/tmpl.js"></script>
	
	<script src="<?php echo $ASSETS; ?>js/site.core.js"></script>
	<script src="<?php echo $ASSETS; ?>js/site.menu.js"></script>
	<script src="<?php echo $ASSETS; ?>js/site.handlers.js"></script>
	<script src="<?php echo $ASSETS; ?>js/site.main.js"></script>
	<script src="<?php echo $ASSETS; ?>js/ajax.core.js"></script>
	<script src="<?php echo $ASSETS; ?>js/ajax.main.js"></script>
</body>
</html>
