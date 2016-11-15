<?php
	$bodyClass		= 'contentpage upgradeplan';
	$header_addons 	= '
		<link rel="stylesheet" href="' . base_url() . 'themes/frontend/monocode/css/payprocessing.css" rel="stylesheet" media="all" />
		
		<script>
		jQuery(document).ready(function($){
			// Plan Length
			optionsCheckbox("div.planlengthmain","div.planlength");
			
			// Payment Type
			optionsCheckbox("div.paymenttypemain","div.paymenttype");
			
			// Active/Inactive Payment Methods
			optionsCheckbox("ul.scpaymentoptionslist","li");
		});
		
		// Active/Inactive Payment Methods Function
		function optionsCheckbox(parentSelector,childSelector){
			
			// Hide Radio Button
			$(parentSelector + " input.radio").css({ display: "none" });
			
			// Plan Length/Payment Type Custom Radio Buttons
			customRadioButton = "<div class=\'customradioholder\'><span class=\'outer\'><span class=\'inner\'></span></span></div>";
			
			// "Plan Length" Custom Radio Buttons
			if((parentSelector == "div.planlengthmain")){
				$("div.planlengthmain div.radiobttnholder").append(customRadioButton);
			}
			
			// "Payment Type" Append Custom Radio Buttons
			if((parentSelector == "div.paymenttypemain")){
				$("div.paymenttypemain div.radiobttnholder").append(customRadioButton);
			}
			
			// Click Event
			$(parentSelector + " " + childSelector).on("click", function(){
				
				// Condition
				if(!$(this).hasClass("active") || $(this).hasClass("inactive")){
					
					/**
					  * If Payment Method Selection
					  */
					
					// Add Class to Parent Row
					if( $(this).is(".amex, .active") || 
						$(this).is(".discover, .active") || 
						$(this).is(".mastercard, .active") || 
						$(this).is(".visa, .active")
					){
						$("div.scformrow.ccinfo").removeClass("inactive").addClass("active").slideDown();
					}
					
					if($(this).is(".paypal, .active")){
						$("div.scformrow.ccinfo").removeClass("active").addClass("inactive").slideUp();
					}
					
					// Add inactive Class to All Items
					$(parentSelector + " " + childSelector).removeClass("active").addClass("inactive");
					$(parentSelector + " " + childSelector).find("input.radio").removeClass("active").addClass("inactive");
					
					// Add active Class to Current Item
					$(this).removeClass("inactive").addClass("active");
					
					// Select Radio Button for Active Item
					$(this).find("input.radio").removeClass("inactive").addClass("active").prop("checked", true);
				}
			});
		}
		</script>
	';
	
	// Load Header
	include("includes/header.php");
?>

<?php
	$user_id			= $this->session->userdata('user_id');
	$new_plan_id		= $this->uri->segment(3);
	$current_plan_detail= $this->muser->get_user_plan_detail_by_user_id($user_id);
	$new_plan_detail	= $this->muser->get_user_plan_detail_by_plan_id($new_plan_id);
	$activity			= '';
	if(getObjectValue($current_plan_detail,'display_order')>getObjectValue($new_plan_detail,'display_order')){
		$activity="Down";
	}else{
		$activity="Up";
	}
?>

<?php /* Content Page Banner: Start */ ?>
<section class="sec-banner">
	<div class="wrapper_banner">
		<div class="container banner contentpage">
			
			
			<div class="clear"></div>
		</div>
		
		<div class="clear"></div>
	</div>
</section>
<?php /* Content Page Banner: End */ ?>

<?php /* Breadcrumbs: Start * / ?>
BREADCRUMBS THROWING AN ERROR. NEEDS TO BE FIXED
<?php echo get_breadcrumb(); ?>
<?php / * Breadcrumbs: End */ ?>

<?php /* Page Content: Start */ ?>
<section class="sec-content">
	<div class="wrapper_content">
		<div class="container content">
			<div class="container_inside contentoutput">
				
				<form action="<?php echo base_url(); ?>user/updateplan/<?php echo $new_plan_id; ?>" 
					id="ff1" 
					class="payprocessingform" 
					name="ff1" 
					method="post" 
					action="" 
					enctype="multipart/form-data" 
					onsubmit="return checkForm();" 
					method="post">
					
					<?php /* Payment Processing: Start */ ?>
					<div class="scpayprocessing">
						
						<?php /* Account: Start */ ?>
						<div class="scpayrow account">

							<?php 
							if(isset($_REQUEST["amount"]) && $_REQUEST["amount"] == '0'){
								?>
								You've successfully downgraded your plan.
								<?php
							}
							
							if(getObjectValue($new_plan_detail,'amount') > 0){
								require('payment_form.php');
							}
							?>
							
							<div class="clear"></div>
						</div>
						<?php /* Account: End */ ?>
						
						<?php /* Upgrade: Start */ ?>
						<div class="scpayrow downgrade">
							
							<script type="text/javascript">
								jQuery(document).ready(function($){
									$(".workspace").click(function(){
										var totalChecked = 0;
										
										$(".workspace").each(function(){
											if($(this).is(":checked")){
												totalChecked++;
											}
										});
										
										if(totalChecked > parseInt(<?php echo getObjectValue($new_plan_detail,'work_space'); ?>)){
											$(this).prop('checked',false);
										}
										
									});
								});
							</script>
							
							<h1><?php echo $activity; ?>grade Plan</h1>
							
							<p>You are <?php echo $activity; ?>grading your plan <strong><?php echo getObjectValue($current_plan_detail,'user_type_name'); ?></strong> to <strong><?php echo getObjectValue($new_plan_detail,'user_type_name'); ?></strong></p>
							
							<?php if($activity == 'Down'){ ?>
							<p>In your new plan, you will have only <strong><?php echo getObjectValue($new_plan_detail,'work_space'); ?></strong> workspaces, so please choose the important workspace from your existing <?php echo getObjectValue($current_plan_detail,'work_space'); ?> workspace.</p>
							
							<div class="project_files">
								<?php 
								$new_workshop	= getObjectValue($new_plan_detail,'work_space');
								$_ws			= get_option('ws');
								$_ws			= json_decode($_ws,true);
								$i=0;
								foreach($_ws as $key=>$ws){
									$i++;
									$sel=($i<=$new_workshop) ? 'checked="checked"' : '';
									?>
									<input type="checkbox" name="workspace[]" class="workspace" <?php echo $sel; ?> id="<?php echo $key; ?>" value="<?php echo $key; ?>" />
									
									<label for="<?php echo $key; ?>"><?php echo $ws['ws_name']; ?></label>
									
									<br />
								<?php } ?>
								
								<div class="clear"></div>
							</div>
							<?php } ?>
							
							<br />
							
							<p>In your new plan <strong><?php echo getObjectValue($new_plan_detail,'user_type_name'); ?></strong>, you will have the following features:</p>
							
							<?php $features=$this->muser->get_features_of_plan($new_plan_id); ?>
							<ul class="planfeatureslist">
								<?php
								foreach($features as $items){
									if($items[1] == 'yes'){
								?>
								<li class="odd first">
									<span><?php echo $items[2]; ?></span>
								</li>
								<?php
									}
								}
								?>
								<li class="odd first"><span>And more...</span></li>
							</ul>
							
							<p>
								<?php if(getObjectValue($new_plan_detail,'amount') == '0'){ ?>
								<div class="submit-btn">
									<input type="submit" name="submit" value="Start Now" />
								</div>
								
								<input type="hidden" name="service" value="10" />
								<input type="hidden" name="process" value="yes" />
								<?php } ?>
							</p>
							
							<div class="clear"></div>
						</div>
						<?php /* Upgrade: End */ ?>

						<div class="clear"></div>
					</div>
					<?php /* Payment Processing: End */ ?>
					
				</form>
				
				<div class="clear"></div>
			</div>
			
			<div class="clear"></div>
		</div>
		
		<div class="clear"></div>
	</div>
</section>
<?php /* Page Content: End */ ?>

<?php
	include("includes/footer_promo.php");
	include("includes/footer_widgets.php");
	include("includes/footer.php");
?>