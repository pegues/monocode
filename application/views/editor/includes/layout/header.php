<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta charset="ISO-8859-1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title><?php echo get_option("editor_title") ?></title>
	
	<!--link href="<?php echo base_url(); ?>core/css/jquery-ui.min.css" rel="stylesheet" /-->
	<link href="<?php echo base_url(); ?>core/css/core.css" rel="stylesheet" media="all" />
	<link href="<?php echo base_url(); ?>core/css/jquery-ui-1.10.4.custom.css" rel="stylesheet" />
	<link href="<?php echo base_url(); ?>core/css/jquery.mCustomScrollbar.css" rel="stylesheet" />
	<link href="<?php echo base_url(); ?>core/css/layout-complex.css" rel="stylesheet" media="all" />
	<link href="<?php echo base_url(); ?>core/css/jquery.enhsplitter.css" rel="stylesheet" media="all" />
	<link href="<?php echo base_url(); ?>core/css/perfect-scrollbar.css" rel="stylesheet" media="all" />
	<link href="<?php echo base_url(); ?>core/css/contextmenu.css" rel="stylesheet" media="all" />
	<link href="<?php echo base_url(); ?>core/css/scpopup.css" rel="stylesheet" media="all" />
	<link href="<?php echo base_url(); ?>core/css/preloader/pro-bars.min.css" rel="stylesheet" media="all" />
	<link href="<?php echo base_url(); ?>core/css/fontawesome/font-awesome.css" rel="stylesheet" media="all" />
	<link href="<?php echo base_url(); ?>core/alert/css/alert.min.css" rel="stylesheet" />
	<link href="<?php echo base_url(); ?>core/alert/themes/<?php echo get_option("editor_page_theme") ?>/theme.css" rel="stylesheet" />
	
	<link href="<?php echo base_url(); ?>themes/editor/<?php echo get_option("editor_page_theme") ?>/theme.css" rel="stylesheet" data-default-theme="<?php echo get_option("editor_page_theme") ?>" media="all" />
	
	<script src="<?php echo base_url(); ?>core/js/jquery-2.1.3.min.js"></script>
	<!--script src="<?php echo base_url(); ?>core/js/jquery-ui.js"></script-->
	<script src="<?php echo base_url(); ?>core/js/jquery-ui.min.js"></script>
	<script src="<?php echo base_url(); ?>core/js/jquery.layout.min.js"></script>
	<script src="<?php echo base_url(); ?>core/js/jquery.layout.pseudoClose.min-1.1.js"></script>
	<script src="<?php echo base_url(); ?>core/js/jquery.layout.slideOffscreen.min-1.1.js"></script>
	<script src="<?php echo base_url(); ?>core/js/complex.js"></script>
	<script src="<?php echo base_url(); ?>core/js/debug.js"></script>
	<script src="<?php echo base_url(); ?>core/js/jquery.enhsplitter.js"></script>
	<!--<script src="<?php echo base_url(); ?>core/js/jquery.mCustomScrollbar.concat.min.js"></script>-->
	<script src="<?php echo base_url(); ?>core/js/contextmenu.js"></script>
	<script src="<?php echo base_url(); ?>core/js/scpopup.js"></script>
	
    <script src="<?php echo base_url(); ?>core/alert/js/alert.min.js"></script>
	
	<script src="<?php echo base_url(); ?>core/js/notify.min.js"></script>
	
	<script src="<?php echo base_url(); ?>core/js/preloader/smoothscroll.js"></script>
	<script src="<?php echo base_url(); ?>core/js/preloader/visible.min.js"></script>
	<script src="<?php echo base_url(); ?>core/js/preloader/pro-bars.js"></script>
	<script src="<?php echo base_url(); ?>core/js/preloader/backbone.js"></script>
	
	<script src="<?php echo base_url(); ?>core/js/sceditor.js"></script>
	<script src="<?php echo base_url(); ?>core/js/sceditormisc.js"></script>
</head>
<body class="<?php echo get_option("editor_theme") ?>">
	
	<?php /* Initialize Loader: Start */ ?>
	<div class="initialize">
		<div class="initialize_inside">
			<div class="initialize_title">Initializing System</div>
			
			<div class="pro-bar-container color-midnight-blue" style="position: relative; margin-left: 25%; width: 50%;">
				<div class="pro-bar bar-100 wet-asphalt" data-pro-bar-percent="100">
					<div class="pro-bar-candy candy-ltr"></div>
				</div>
			</div>
			
			<div class="initialize_subtext">Loading all your data and settings</div>
		</div>
		
		<div class="clear"></div>
	</div>
	<?php /* Initialize Loader: End */ ?>
