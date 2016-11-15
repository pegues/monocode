<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
	<title>About</title>
	
	<link href="<?php echo base_url(); ?>core/css/popupcontent.css" rel="stylesheet" media="all" />
	<link href="<?php echo base_url(); ?>themes/editor/<?php echo get_option("editor_page_theme") ?>/theme.css" rel="stylesheet" data-default-theme="<?php echo get_option("editor_page_theme") ?>" media="all" />
	<script src="<?php echo base_url(); ?>core/js/jquery-1.10.2.min.js" type="text/javascript"></script>
	
	<script type="text/javascript">
	$(document).ready(function(e) {
		$('body').css({'overflow' : 'hidden'}).addClass('about');
	});
	
	function cancelled(){
		$("input").each(function(){
			parent.configInput($(this).attr('data-key'),$(this).attr('data-value'));
		});
		$("select").each(function(){
			parent.configSelect($(this).attr('data-key'),$(this).attr('data-value'));
		});
		
		parent.sceditor.call("base.closePopup()", {}, POPUP_ID);
	}
	
	function backclose(){
		$("input").each(function(){
			parent.configInput($(this).attr('data-key'),$(this).attr('data-value'));
		});
		$("select").each(function(){
			parent.configSelect($(this).attr('data-key'),$(this).attr('data-value'));
		});	
	}
	</script>
</head>
<body data-width='500px' data-controls="{'Close':'cancelled'}">
<div class="infopopup">
	<form id="" class="">
		
		<div class="infopopuptitle">
			<span>About Monocode</span>
		</div>
		
		<div class="infooptionscontainer" style="padding: 0 5px;"> <!-- -->
			<p>Monocode provides you all the features you need for developing applications, with all the benefits of the cloud. Access your files from anywhere, any time. Use any browser, and any device.</p>
			
			<p>Copyright &copy; <?php echo date('Y'); ?> Monocode, LLC. All Rights Reserved.
			
			<div class="clear"></div>
		</div>
	</form>
	
	<div class="clear"></div>
</div>
</body>
</html>