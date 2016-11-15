<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
	<title>Goto File</title>
	
	<link href="<?php echo base_url(); ?>core/css/popupcontent.css" rel="stylesheet" media="all" />
	<script src="<?php echo base_url(); ?>core/js/jquery-1.10.2.min.js" type="text/javascript"></script>
	
	<script type="text/javascript">
	$(document).ready(function(e) {
		$('body').css({'overflow' : 'hidden'}).addClass('gotofile');
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
<body data-width='600px' data-controls="{'Save Configuration':'function_save','Close':'cancelled'}">
<div class="infopopup">
	<form id="" class="">
		
		<div class="infopopuptitle">
			<span>Goto File...</span>
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