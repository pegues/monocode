<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
	<title>Image Information</title>
	
	<link href="<?php echo base_url(); ?>core/css/popupcontent.css" rel="stylesheet" media="all" />
	<link href="<?php echo base_url(); ?>themes/editor/<?php echo get_option("editor_page_theme") ?>/theme.css" rel="stylesheet" data-default-theme="<?php echo get_option("editor_page_theme") ?>" media="all" />
	<script src="<?php echo base_url(); ?>core/js/jquery-1.10.2.min.js" type="text/javascript"></script>
	
	<style>
	html, body {
		width: 100%;
		height: 100%;
		}
		body {
			margin: 0;
			padding: 0;
			}
	
	* {
		-webkit-box-sizing: border-box;
		-moz-box-sizing: 	border-box;
		box-sizing: 		border-box;
		}
	
	img.imagebeingviewed {
		background: url("<?php echo base_url(); ?>core/images/imageview/bkg_imageview_transparency.png") top left;
		}
	</style>
</head>
<body data-width="100%" data-height="100%">
<div class="infopopup" style="height: 100%;">
	<form id="" class="" style="height: 100%;">
		<div class="infooptionscontainer" style="position: relative; padding: 31px 0 0; height: 100%; overflow: hidden;">
            
			<div class="tabsectionheader">
				<div class="tabsectionheader_inside">
					<?php $info=getimagesize($path); ?>
					
					<ul class="tabsectionheaderlist">
						<li class="first"><div>Width: <?php echo isset($info[0]) ? $info[0] : ''; ?>px</div></li>
						<li><div>Height: <?php echo isset($info[1]) ? $info[1] : ''; ?>px</div></li>
						<li><div>Bits: <?php echo isset($info['bits']) ? $info['bits'] : ''; ?></div></li>
						<li><div>Channels: <?php echo isset($info['channels']) ? $info['channels'] : ''; ?></div></li>
						<li class="last"><div>Type: <?php echo isset($info['mime']) ? $info['mime'] : ''; ?></div></li>
					</ul>
					
					<div class="clear"></div>
				</div>
				
				<div class="tabsectionheadersep"><span></span></div>
				
				<div class="clear"></div>
            </div>
			
			<div class="imageviewcontainer">
				<div class="imageviewcontainer_inside">
					
					<?php /*<iframe id="pixlr" src="http://pixlr.com/editor/?image=http://de8bphqo3whcl.cloudfront.net/sites/default/files/styles/article_profile_150x150/public/apis/at1576.png&title=<?php echo basename($path); ?>" frameborder="0" class="imagetabframe" width="100%"></iframe> */ ?>
					
					<?php
					if(file_exists($path)){
						echo '<img src="' . base_url().'image/read/?name='.$path . '" class="imagebeingviewed" style="max-width: ' . (isset($info[0]) ? $info[0] : '') . 'px; max-height: ' . (isset($info[1]) ? $info[1] : '') . 'px" />';
					}else{
						echo "Image not found.";
					}
					?>
					
					<div class="clear"></div>
				</div>
				
				<div class="clear"></div>
			</div>
			
			<div class="clear"></div>
		</div>
	</form>
	
	<div class="clear"></div>
</div>
</body>
</html>
<script type="text/javascript">
/*
$(document).ready(function(e) {
	document.getElementById("pixlr").height=parseInt($(window).height()) - 35;
	
	$(window).resize(function(){
		//console.log($("#pixlr"))
		document.getElementById("pixlr").height=parseInt($(window).height()) - 35;
	})
});
*/
</script>