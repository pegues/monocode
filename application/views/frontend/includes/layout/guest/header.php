<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1" />
	<title><?php echo $page_title ?></title>
	
	<base href="<?php echo base_url(); ?>" />
	
	<link href='//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700' rel='stylesheet' type='text/css' />
	<link rel="stylesheet" href="<?php echo base_url(); ?>themes/frontend/monocode/css/registration.css" />
	<link rel="stylesheet" href="<?php echo base_url(); ?>themes/frontend/monocode/css/font-awesome.min.css" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>themes/frontend/monocode/css/passrev.css" />
	
	<script src="<?php echo base_url(); ?>themes/frontend/monocode/js/jquery-1.11.0.min.js"></script>
	<script src="<?php echo base_url(); ?>themes/frontend/monocode/js/jquery.validate.min.js" id="script-resource-8"></script>
        <script src="<?php echo base_url(); ?>themes/frontend/monocode/js/passrev.js"></script>
        <script>
            var DEBUG = <?php echo DEBUG ? 'true' : 'false'; ?>;
        </script>
	<script src='https://www.google.com/recaptcha/api.js'></script>
</head>
<body>

	<?php /* Login Form: Start */ ?>
	<div class="wrapper">
		<div class="wrapper_inside">
			<form id="frm1" action="" method="post" autocomplete="off" class="validate">
				<div class="logininside">
					<div class="formitems">
						<div class="logintitle">
							<a href="<?php echo base_url(); ?>" class="sitelogo">Monocode</a>
							<span class="pagesectitle"><?php echo $page_title ?></span>
						</div>
						<?php /* Notifications: Start */ ?>
						<?php
						if (isset($messages) && $messages && count($messages) > 0) {
							foreach ($messages as $message) {
								?>
								<div class="formcol notice <?php echo $message->type; ?>">
									<div class="formgroup">
										<?php echo $message->msg; ?>
									</div>
								</div>
								<?php
							}
						}
						?>
						<?php /* Notifications: End */ ?>
						
						<?php /* Prevent Autocomplete: Start */ ?>
						<div style="display: none;">
							<input />
							<input type="password" />
						</div>
						<?php /* Prevent Autocomplete: Start */ ?>
