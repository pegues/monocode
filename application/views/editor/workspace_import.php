<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
	<title>Project Import</title>
	
	<link href="<?php echo base_url(); ?>core/css/popupcontent.css" data-default-theme="<?php echo get_option("editor_page_theme") ?>" rel="stylesheet" media="all" />
	<link href="<?php echo base_url(); ?>core/css/fontawesome/font-awesome.css" rel="stylesheet" media="all" />
    <link href="<?php echo base_url(); ?>core/css/contextmenu.css" rel="stylesheet" media="all" />
	<link href="<?php echo base_url(); ?>core/css/jquery.dataTables.css" rel="stylesheet" media="all" />
	<link href="<?php echo base_url(); ?>core/alert/css/alert.min.css" rel="stylesheet" />
	<link href="<?php echo base_url(); ?>core/alert/themes/<?php echo get_option("editor_page_theme") ?>/theme.css" rel="stylesheet" />
	<link href="<?php echo base_url(); ?>themes/editor/<?php echo get_option("editor_page_theme") ?>/theme.css" rel="stylesheet" data-default-theme="<?php echo get_option("editor_page_theme") ?>" media="all" />
	
	<script src="<?php echo base_url(); ?>core/js/jquery-1.10.2.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>core/js/jquery-ui-1.10.4.custom.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>core/js/contextmenu.js"></script>
    <script src="<?php echo base_url(); ?>core/js/jquery.dataTables.min.js"></script>
	<script src="<?php echo base_url(); ?>core/alert/js/alert.min.js"></script>
	
    <script type="text/javascript">
    $(document).ready(function(e) {
    <?php
    if (isset($messages) && $messages && sizeof($messages) > 0) {
        foreach ($messages as $message) {
            ?>parent.sceditor.call("base.notify()", <?php echo json_encode($message); ?>);<?php
        }
    }
    ?>
    });
	function cancelled() {
		$("input").each(function() {
			parent.configInput($(this).attr('data-key'), $(this).attr('data-value'));
		});
		
		$("select").each(function() {
			parent.configSelect($(this).attr('data-key'), $(this).attr('data-value'));
			
			if ($(this).attr('data-key') == 'editor_page_theme') {
				parent.configSelect('cancle' + $(this).attr('data-key'), $(this).attr('data-value'));
			}
		});
		parent.sceditor.call("base.closePopup()", {}, POPUP_ID);
	}
	
	function function_save() {
		$("#config").submit();
	}
    </script>
</head>
<body data-width='600px' data-height='250px' data-controls="{'Import Files':'function_save','Close':'cancelled'}">
	<div class="infopopup">
		<form id="config" action="" method="post" enctype="multipart/form-data" class="">
			<div class="infooptionscontainer" style="height: auto; overflow: visible;"> <!-- -->
				<?php ?>
				<div class="infopopupsubtitle">
					<span>Import ZIP Folder</span>
				</div>
				<div class="infopopupoptions">
					<div class="infopopupoptrow first">
						<label>Choose Zip Folders</label>
						<div class="infopopupfield">
							<input type="file" name="upload" accept="application/zip" />

							<div class="clear"></div>
						</div>

						<div class="clear"></div>
					</div>
					<div class="infopopupoptrow last">
						<label>Choose Workspace</label>
						<div class="infopopupfield">
							<?php
							$dbwc = (int) get_user_feature('work_space');
							$_ws = get_option('ws');
							$_ws = json_decode($_ws, true);

							$index = 0;

							if (is_array($_ws) && sizeof($_ws) > 0) {
								?>
								<select name="workspace" id="workspace">
									<?php
									foreach ($_ws as $key => $ws) {
										if ($ws['ws_status'] == 'enable') {
											?>
											<option value="<?php echo $key; ?>" <?php echo ($ws['ws_active'] == 'true') ? 'selected="selected"' : ''; ?>>
												<?php echo $ws['ws_name']; ?>
											</option>
											<?php
										}
									}
									?>
								</select>
								<?php
							}
							?>

							<div class="clear"></div>
						</div>

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