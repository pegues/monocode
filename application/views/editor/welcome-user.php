<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
	<title>User Welcome Tab</title>
	
	<link href="<?php echo base_url(); ?>core/css/popupcontent.css" rel="stylesheet" media="all" />
	<link href="<?php echo base_url(); ?>core/css/fontawesome/font-awesome.css" rel="stylesheet" media="all" />
	<link href="<?php echo base_url(); ?>core/css/contextmenu.css" rel="stylesheet" media="all" />
	<link href="<?php echo base_url(); ?>core/alert/css/alert.min.css" rel="stylesheet" />
	<link href="<?php echo base_url(); ?>core/alert/themes/<?php echo get_option("editor_page_theme") ?>/theme.css" rel="stylesheet" />
	<link href="<?php echo base_url(); ?>themes/editor/<?php echo get_option("editor_page_theme") ?>/theme.css" rel="stylesheet" data-default-theme="<?php echo get_option("editor_page_theme") ?>" media="all" />
	
	<script src="<?php echo base_url(); ?>core/js/jquery-1.10.2.min.js" type="text/javascript"></script>
	<script src="<?php echo base_url(); ?>core/js/contextmenu.js"></script>
	<script src="<?php echo base_url(); ?>core/alert/js/alert.min.js"></script>

	<script type="text/javascript">
	$(document).ready(function(){
		
	});
	</script>
	
	<style>
	html, body {
		height: 100%;
		}
		body {
			margin: 0;
			padding: 0;
			
			-webkit-box-sizing: border-box;
			-moz-box-sizing: 	border-box;
			box-sizing: 		border-box;
			}
	
	* {
		-webkit-box-sizing: border-box;
		-moz-box-sizing: 	border-box;
		box-sizing: 		border-box;
		}
	</style>
</head>
<body data-width='100%' data-height='100%'>
	<div class="infotab" style="height: 100%;">
		<div class="infooptionscontainer" style="padding-right: 0; height: 100%; overflow: hidden;"> <!-- -->
			
			<?php /*
			<div class="wrapper">
				<?php
					$content=$this->mbackend->get_pages_by_position('user-welcome-page');
					
					foreach($content as $val){
					?>
					<div class="holder">
						<h1><?php echo $val->cms_title; ?></h1>
						
						<p><?php echo $val->cms_content; ?></p>
					</div>
					<?php
					}
				?>
				
				<div class="clear"></div>
			</div>
			*/ ?>
			
			<?php /* Tab Header: Start */ ?>
			<div class="tabsectionheader">
				<div class="tabsectionheader_inside">
					<div class="tabsectionheadercol">
						<div class="tabsectionheadercol_inside">
							
							
							<div class="clear"></div>
						</div>
						
						<div class="clear"></div>
					</div>
					
					<div class="clear"></div>
				</div>
				
				<div class="tabsectionheadersep"><span></span></div>
				
				<div class="clear"></div>
			</div>
			<?php /* Tab Header: End */ ?>

			<div class="welcomeinfo">
				<div class="welcomeinfo_inside">
					
					<?php /* Left Column: Start */ ?>
					<div class="welcomeinfo_col left">
						<div class="welcomeinfo_title">Monocode</div>
						
						<div class="welcomeinfo_sectitle">Start</div>
						
						<ul>
							<li data-action-type="calljs" 
								data-action="workspace.create"
								data-file-url="workspace/create" 
								data-title="Create New workspace">
								<span>New Project...</span>
							</li>
							<li><span>Open Project...</span></li>
							<li data-action-type="popup" 
								data-file-url="ftp/ftpdetails" 
								data-title="Create New FTP Connection">
								<span>Connect to server via FTP...</span>
							</li>
						</ul>
						
						<div class="welcomeinfo_sectitle">Recent Files</div>
						
						<ul>
							<li><span>footer.php</span></li>
							<li><span>header.php</span></li>
							<li><span>file_tabs.php</span></li>
							<li><span>core.css</span></li>
						</ul>
						
						<div class="clear"></div>
					</div>
					<?php /* Left Column: End */ ?>
					
					<?php /* Right Column: Start */ ?>
					<div class="welcomeinfo_col right">
						<div class="welcomeinfo_title">Discover the editor</div>
						
						<div class="clear"></div>
					</div>
					<?php /* Right Column: End */ ?>
					
					<div class="clear"></div>
				</div>
				
				<div class="clear"></div>
			</div>
			
			<div class="clear"></div>
		</div>
		
		<div class="clear"></div>
	</div>
</body>
</html>