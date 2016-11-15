<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
	<title>Workspaces</title>

	<link href="<?php echo base_url(); ?>core/css/popupcontent.css" rel="stylesheet" media="all" />
	<link href="<?php echo base_url(); ?>core/css/fontawesome/font-awesome.css" rel="stylesheet" media="all" />
	<link href="<?php echo base_url(); ?>themes/editor/<?php echo get_option("editor_page_theme") ?>/theme.css" rel="stylesheet" data-default-theme="<?php echo get_option("editor_page_theme") ?>" media="all" />
	
	<script src="<?php echo base_url(); ?>core/js/jquery-1.10.2.min.js" type="text/javascript"></script>

	<script type="text/javascript">
		$(document).ready(function (e) {
			<?php
			if ($messages && count($messages) > 0) {
				if (!$hasError) {
					?>parent.sceditor.call("base.workspace.changed()");<?php
				}
				foreach ($messages as $message) {
					?>parent.sceditor.call("base.notify()", {msg: "<?php echo $message->msg; ?>", 'type': "<?php echo $message->type; ?>"});<?php
				}
			}
			?>
			$("form input[name='name']").focus().select();
		});
		
		function validateForm() {
			var form = $("form");
			var nameValidator = /^[A-Za-z0-9']{0,20}$/;
			form[0].name.value = $.trim(form[0].name.value);

			if (form[0].name.value == '') {
				parent.sceditor.call("base.notify()", {msg: 'Please enter the workspace name.', 'type': 'error'});
				return false;
			}

			if (!nameValidator.test(form[0].name.value)) {
				parent.sceditor.call("base.notify()", {msg: 'You may only use letters and numbers.', 'type': 'error'});
				return false;
			}

			return true;
		}
		
		function save() {
			if (validateForm()) {
				var form = $("form");
				form.submit();
			}
		}

		function closeme() {
			parent.sceditor.call('base.closePopup()', {}, POPUP_ID);
		}
	</script>
	<style>
		body {
			margin-bottom: 0;
			padding-bottom: 0;
		}
	</style>
</head>
<body data-width='400px' data-height="155px" data-controls="{'Save' : 'save','Close':'closeme'}">
	<div class="infopopup">
		<form class="form" method="post" onsubmit="return validateForm()">
			<div class="infooptionscontainer" style="padding-right: 0;">
				<div class="infopopupoptions">
					<div class="infopopupoptrow first" style="padding: 0;">

						<!-- workspace: Start -->
						<div class="newworkspaceconn">

							<!-- workspace Details: Start -->
							<div class="newworkspaceconn_details">
								<div class="newworkspaceconndetails_inside">
									<div class="newftpconndetails_col host">
										<div class="newftprow_inside">
											<label for="name">Workspace Name</label>
											<div class="infopopupfield">
												<input type="text" 
													class="text" 
													id="name" 
													name="name" 
													maxlength="20" 
													value="<?php echo isset($name) ? $name : ''; ?>" 
													placeholder="My Workspace" />

												<div class="clear"></div>
											</div>

											<div class="clear"></div>
										</div>

										<div class="clear"></div>
									</div>
									<div class="newftpconndetails_col host">
										<div class="newftprow_inside">
											<label for="type">Protocol</label>
											<div class="infopopupfield">
												<select class="select" name="type" id="type">
													<option value="php">PHP</option>
													<option value="ruby" <?php echo (isset($type) && $type == 'ruby' ? 'selected=""' : ''); ?>>Ruby</option>
												</select>
												<div class="clear"></div>
											</div>

											<div class="clear"></div>
										</div>

										<div class="clear"></div>
									</div>

									<div class="clear"></div>
								</div>

								<div class="clear"></div>
							</div>
							<!-- workspace Details: End -->

							<div class="clear"></div>
						</div>
						<!-- workspace: End -->

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