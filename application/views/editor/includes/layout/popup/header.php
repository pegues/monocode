<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">

	<link href="<?php echo base_url(); ?>core/css/popupcontent.css" rel="stylesheet" media="all" />
	<link href="<?php echo base_url(); ?>core/css/fontawesome/font-awesome.css" rel="stylesheet" media="all" />
	<link href="<?php echo base_url(); ?>themes/editor/<?php echo get_option("editor_page_theme") ?>/theme.css" rel="stylesheet" data-default-theme="<?php echo get_option("editor_page_theme") ?>" media="all" />
	
	<script src="<?php echo base_url(); ?>core/js/jquery-1.10.2.min.js"></script>
	<script src="<?php echo base_url(); ?>core/js/sceditormisc.js"></script>
	<script type="text/javascript">
		$(document).ready(function (e) {
			<?php
			if ($messages && count($messages) > 0) {
				foreach ($messages as $message) { ?>
					parent.sceditor.call("base.notify()", {
						msg: "<?php echo $message->msg; ?>", 
						'type': "<?php echo $message->type; ?>"
					});
					<?php
				}
			}
			?>
		});
	</script>
</head>
