<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
	<title>Change Log</title>
	
	<link href="<?php echo base_url(); ?>core/css/popupcontent.css" rel="stylesheet" media="all" />
	<link href="<?php echo base_url(); ?>themes/editor/<?php echo get_option("editor_page_theme") ?>/theme.css" rel="stylesheet" data-default-theme="<?php echo get_option("editor_page_theme") ?>" media="all" />
	<script src="<?php echo base_url(); ?>core/js/jquery-1.10.2.min.js" type="text/javascript"></script>
	
	<script type="text/javascript">
	$(document).ready(function(e) {
		$('body').css({'overflow' : 'hidden'}).addClass('changelog');
	});
	
	function cancelled(){
		$("input").each(function(){
			parent.configInput($(this).attr('data-key'),$(this).attr('data-value'));
		});
		$("select").each(function(){
			parent.configSelect($(this).attr('data-key'),$(this).attr('data-value'));
		});
		
		parent.sceditor.call('base.closePopup()', {}, POPUP_ID);	
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
<body data-width='600px' data-controls="{'Close':'cancelled'}">
<div class="infopopup">
	<form id="" class="">
		
		<div class="infopopuptitle">
			<span>Changelog</span>
		</div>
		
		<div class="infooptionscontainer"> <!-- -->
			<div class="infopopupsubtitle">
				<span>Coming Soon...</span>
			</div>
			
			<div class="clear"></div>
		</div>
	</form>
	
	<div class="clear"></div>
</div>
</body>
</html>