<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
	<title><?php echo $TITLE; ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link rel="stylesheet" href="<?php echo $ASSETS; ?>vendor/bootstrap/css/bootstrap.css" type="text/css" />
	<link rel="stylesheet" href="<?php echo $ASSETS; ?>vendor/paginator3000/paginator3000.css" type="text/css" />
	<link rel="stylesheet" href="<?php echo $ASSETS; ?>vendor/light-gallery-1.2.0/css/lightgallery.css" type="text/css" />
	<link rel="stylesheet" href="<?php echo $ASSETS; ?>vendor/jquery-ui-1.11.4.full/jquery-ui.css" type="text/css" />
	<link rel="stylesheet" href="<?php echo $ASSETS; ?>vendor/jquery-ui-1.11.4.full/addons/jquery-ui-timepicker-addon.css" type="text/css" />
	<link rel="stylesheet" href="<?php echo $ASSETS; ?>vendor/dynatable-0.3.1/jquery.dynatable.css" type="text/css" />
	<link rel="stylesheet" href="<?php echo $ASSETS; ?>vendor/plupload-2.1.8/js/jquery.ui.plupload/css/jquery.ui.plupload.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="<?php echo $ASSETS; ?>css/admin.css" type="text/css" />
	<link rel="stylesheet" href="<?php echo $ASSETS; ?>vendor/bootstrap/css/bootstrap-responsive.css" type="text/css" />
	
	<?php if (0): ?>
	<link rel="stylesheet" href="<?php echo $ASSETS; ?>vendor/jquery-pretty-photo/style.css" type="text/css" />
	<link rel="stylesheet" href="<?php echo $ASSETS; ?>vendor/jquery-ui-bootstrap/jquery-ui-1.8.16.custom.css" type="text/css" />
	<link rel="stylesheet" href="<?php echo $ASSETS; ?>vendor/jquery-ui-timepicker/style.css" type="text/css" />
	<link rel="stylesheet" href="<?php echo $ASSETS; ?>vendor/plupload/jquery.plupload.queue/css/jquery.plupload.queue.css" type="text/css" />
	<?php endif; ?>
	
	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	
	<script type="text/javascript" src="<?php echo $ASSETS; ?>vendor/jquery/jquery-1.11.3.min.js"></script>
</head>
<body>
	<div class="navbar navbar-inverse navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container">
				<button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
<?php
				echo HTML::anchor(URL::site(), $SITE['name'], array(
					'class' => 'brand'
				));
?>
				<div class="nav-collapse collapse">
<?php 				
					echo View_Admin::factory('layout/menu/top', array(
						'menu' => $menu
					));
					echo View_Admin::factory('layout/site_switcher');
?>				
				</div>
			</div>
		</div>
	</div>
	<div class="container container-main">
		<div class="row">
			<div class="span3 aside">
<?php 
				echo $aside;
?>
			</div>
			<div id="main" class="span9 container-inner">
				<div class="page-header">
					<h1><?php echo $title; ?></h1>
				</div>
<?php 
				echo $content 
?>
			</div>
		</div>
	</div>
	<div class="container container-footer">
		<footer>
			<p class="pull-right">&copy; <?php echo date('Y')?> <a href="http://kubikrubik.ru" target="_blank">KubikRubik</a></p>
		</footer>
	</div>
	
	
	<script type="text/javascript" src="<?php echo $ASSETS; ?>vendor/bootstrap/js/bootstrap.js"></script>
	<script type="text/javascript" src="<?php echo $ASSETS; ?>vendor/ckeditor-4.6.6/ckeditor.js"></script>
	<script type="text/javascript" src="<?php echo $ASSETS; ?>vendor/paginator3000/paginator3000.js"></script>
	<script type="text/javascript" src="<?php echo $ASSETS; ?>vendor/light-gallery-1.2.0/js/lightgallery.min.js"></script>
	<script type="text/javascript" src="<?php echo $ASSETS; ?>vendor/jquery-ui-1.11.4.full/jquery-ui.js"></script>
	<script type="text/javascript" src="<?php echo $ASSETS; ?>vendor/jquery-ui-1.11.4.full/jquery-ui-i18n.js"></script>
	<script type="text/javascript" src="<?php echo $ASSETS; ?>vendor/jquery-ui-1.11.4.full/addons/jquery-ui-timepicker-addon.js"></script>
	<script type="text/javascript" src="<?php echo $ASSETS; ?>vendor/jquery-ui-1.11.4.full/addons/jquery-ui-timepicker-ru.js"></script>
	<script type="text/javascript" src="<?php echo $ASSETS; ?>vendor/mustache-2.2.0/mustache.min.js"></script>
	<script type="text/javascript" src="<?php echo $ASSETS; ?>vendor/store-1.3.18/store.min.js"></script>
	<script type="text/javascript" src="<?php echo $ASSETS; ?>vendor/plupload-2.1.8/js/plupload.full.min.js"></script>
	<script type="text/javascript" src="<?php echo $ASSETS; ?>vendor/plupload-2.1.8/js/i18n/ru.js"></script>
	<script type="text/javascript" src="<?php echo $ASSETS; ?>vendor/plupload-2.1.8/js/jquery.ui.plupload/jquery.ui.plupload.min.js"></script>
	
	
	<?php if (0): ?>
	<script type="text/javascript" src="<?php echo $ASSETS; ?>vendor/jquery-ui/jquery-ui.min.js"></script>
	<script type="text/javascript" src="<?php echo $ASSETS; ?>vendor/jquery-ui/jquery-ui-i18n.js"></script>
	<script type="text/javascript" src="<?php echo $ASSETS; ?>vendor/jquery-ui/themeswitchertool.js"></script>
	<script type="text/javascript" src="<?php echo $ASSETS; ?>vendor/jquery-ui-timepicker/jquery-ui-timepicker-addon.js"></script>
	<script type="text/javascript" src="<?php echo $ASSETS; ?>vendor/jquery-pretty-photo/jquery.prettyPhoto.js"></script>

	<script type="text/javascript" src="<?php echo $ASSETS; ?>vendor/plupload/plupload.full.js"></script>
	<script type="text/javascript" src="<?php echo $ASSETS; ?>vendor/plupload/i18n/ru.js"></script>
	<script type="text/javascript" src="<?php echo $ASSETS; ?>vendor/plupload/jquery.plupload.queue/jquery.plupload.queue.js"></script>

	<?php endif; ?>
	
	
	
	<script type="text/javascript" src="<?php echo $ASSETS; ?>js/admin.js"></script>
	
	
	
</body>
</html>
