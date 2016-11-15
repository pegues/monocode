<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
	<title>Databases</title>
	
	<link href="<?php echo base_url(); ?>core/css/popupcontent.css" rel="stylesheet" media="all" />
	<link href="<?php echo base_url(); ?>core/css/fontawesome/font-awesome.css" rel="stylesheet" media="all" />
	<script src="<?php echo base_url(); ?>core/js/jquery-1.10.2.min.js" type="text/javascript"></script>
	
	<script type="text/javascript">
	$(document).ready(function (e) {
		<?php
		if ($messages && count($messages) > 0) {
			if (!$hasError) {
				?>parent.sceditor.call("base.database.reload()");<?php
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
		var nameValidator = /^[A-Za-z0-9']{0,8}$/;
		form[0].name.value = $.trim(form[0].name.value);
		
		if (form[0].name.value == '') {
			parent.sceditor.call("base.notify()", {msg: 'Please enter the database name.', 'type': 'error'});
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
		//parent.sceditor.call("base.database.reload()", {});
	}
	</script>
	<style>
		body {
			margin-bottom: 0;
			padding-bottom: 0;
		}
	</style>
</head>
<body data-width='400px' data-height="325px" data-controls="{'Save' : 'save','Close':'closeme'}">
	<div class="infopopup">
            <form class="form" method="post" onsubmit="return validateForm();">
			<?php if (isset($old_name)) { ?>
				<input type="hidden" name="old_name" value="<?php echo $old_name; ?>" />
			<?php } ?>
			<div class="infooptionscontainer" style="padding-right: 0;">
				<div class="infopopupoptions">
					<div class="infopopupoptrow first" style="padding: 0;">
						
						<?php /* Database: Start */ ?>
						<div class="newdatabaseconn">
							
							<?php /* Database Details: Start */ ?>
							<div class="newdatabaseconn_details">
								<div class="newdatabaseconndetails_inside">
									<div class="newdatabaseconndetails_col">
										<div class="newdatabaserow_inside">
											<label for="database-name">Database Name</label>
											
											<div class="clear"></div>
										</div>
										
										<div class="clear"></div>
									</div>
									
									<div class="newdatabaseconndetails_col">
										<div class="newdatabaserow_inside">
											<label for="database-name"><?php echo $prefix . '_'; ?></label>
											<div class="infopopupfield">
												<input type="text" maxlength="8" class="text" id="database-name" name="name" value="<?php echo isset($name) ? $name : ''; ?>" placeholder="MyDatabase" />
												
												<div class="clear"></div>
											</div>
											
											<div class="clear"></div>
										</div>
										
										<div class="helper-block">Your database name cannot be longer than 8 characters, and can only contain numbers and letters.</div>
										
										<div class="clear"></div>
									</div>
									
									<div class="clear"></div>
								</div>
								
								<div class="clear"></div>
							</div>
							<?php /* Database Details: End */ ?>
							
							<div class="clear"></div>
						</div>
						<?php /* Database: End */ ?>
						
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