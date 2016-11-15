<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
	<title>File Upload</title>

	<link href="<?php echo base_url(); ?>core/css/popupcontent.css" rel="stylesheet" media="all" />
	<link href="<?php echo base_url(); ?>core/css/fontawesome/font-awesome.css" rel="stylesheet" media="all" />
	<link href="<?php echo base_url(); ?>core/css/dropzone.css" rel="stylesheet" media="all" />
	<link href="<?php echo base_url(); ?>themes/editor/<?php echo get_option("editor_page_theme") ?>/theme.css" rel="stylesheet" data-default-theme="<?php echo get_option("editor_page_theme") ?>" media="all" />
	
	<script src="<?php echo base_url(); ?>core/js/jquery-1.10.2.min.js" type="text/javascript"></script>
	<script src="<?php echo base_url(); ?>core/js/dropzone.js"></script>

	<script type="text/javascript">
		Dropzone.autoDiscover = false;
		var uploader = null;
		
		$(document).ready(function (e) {
			uploader = new Dropzone('#uploader', {
				url				: "<?php echo $this->config->base_url(); ?>file/upload",
				uploadMultiple	: true,
				autoProcessQueue: false,
				parallelUploads	: 10,
				maxFiles		: 100,
				maxFilesize		: 50,
				completemultiple: function (files) {
					var messages = null;
					
					if (messages = eval("(" + files[0].xhr.response + ")").messages) {
						for (var i = 0, message; message = messages[i++];) {
							parent.sceditor.call("base.notify()", message);
						}
					}
				}
			}).on("queuecomplete", function () {
                uploader.options.autoProcessQueue = false;
				parent.sceditor.call("base.file.reloadDir('" + $("#uploader input[name='dir']").val() + "')");
			}).on("maxfilesexceeded", function(file) { this.removeFile(file); });
		});

		function upload() {
			$("#uploader input[name='dir']").val(parent.sceditor.call("base.file.getSelectedDir()"));
			uploader.processQueue();
            uploader.options.autoProcessQueue = true;
		}
        
        function clear() {
            uploader.removeAllFiles();
        }

		function close() {
			$("input").each(function () {
				parent.configInput($(this).attr('data-key'), $(this).attr('data-value'));
			});
			$("select").each(function () {
				parent.configSelect($(this).attr('data-key'), $(this).attr('data-value'));
			});

			parent.scpopupclose();
		}
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
<body data-width='100%' data-height='100%' data-controls="{'Upload':'upload','Clear':'clear','Close':'close'}">
<div class="infopopup" style="padding-top: 31px; height: 100%;">
	
	<div class="infooptionscontainer" style="padding-right: 0; height: 100%; overflow: hidden;"> <!-- -->
		
		<?php /* Tab Header: Start */ ?>
		<div class="tabsectionheader">
			<div class="tabsectionheader_inside">
				
				<div class="tabsectionheadercol twocol left">
					<div class="tabsectionheadercol_inside">
						<!--<i class="fa fa-caret-down"></i>Configuration Navigation-->
						
						<div class="clear"></div>
					</div>
					
					<div class="clear"></div>
				</div>
				
				<div class="tabsectionheadercol twocol right">
					<div class="tabsectionheadercol_inside">
						<!--<i class="fa fa-caret-down"></i>Configuration Features-->
						
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
		
		<?php /* Configuration Wrapper: Start */ ?>
		<div class="uploadholder">
			<div class="uploadholder_inside">
				<form class="dropzone" id="uploader">
					<input type="hidden" name="dir" />
				</form>
				
				<div class="clear"></div>
			</div>
			
			<div class="clear"></div>
		</div>
		<?php /* Configuration Wrapper: End */ ?>
		
		<div class="clear"></div>
	</div>
	
	<div class="clear"></div>
</div>
</body>
</html>