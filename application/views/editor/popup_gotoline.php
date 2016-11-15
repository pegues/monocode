<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
	<title>Goto Line</title>
	
	<link href="<?php echo base_url(); ?>core/css/popupcontent.css" rel="stylesheet" media="all" />
	<script src="<?php echo base_url(); ?>core/js/jquery-1.10.2.min.js" type="text/javascript"></script>
	
	<script type="text/javascript">
		$(function() {
			<?php if (isset($_POST["lineNo"])) { ?>
			var lineNo = "<?php echo $_POST["lineNo"] ?>";
			<?php } else {?>
			var lineNo = (parent.sceditor.call("base.getCurrentLine()"));
			<?php } ?>
			$("#lineNo").val(lineNo);
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

			parent.sceditor.call('base.closePopup()', {}, POPUP_ID);
		}

		function function_gotoline() {
			$("#gotoline").submit();
		}

		function callBase(no)
		{
			parent.sceditor.call(" base.gotoLine(" + no + ")", {});
		}
	</script>
</head>
<body data-width='600px'data-height='100px' data-controls="{'Go':'gotoline','Close':'cancelled'}">
	<div class="infopopup">
		<form id="gotoline" action="" method="post">
			<!--
			<div class="infopopuptitle">
				<span>Goto Line</span>
			</div>
			-->

			<div class="infooptionscontainer"> <!-- -->
				<div class="infopopupsubtitle">

					<?php
					if (isset($_POST["lineNo"])) {
						?>
						<script>
							callBase(<?php if (isset($_POST["lineNo"])) echo $_POST["lineNo"] ?>);
						</script>
						<?php
					}
					?>
					<span>
						Goto Line <input id="lineNo" type="number" name="lineNo" value="">
					</span>  

				</div>

				<div class="clear"></div>
			</div>

		</form>

		<div class="clear"></div>
	</div>
</body>
</html>