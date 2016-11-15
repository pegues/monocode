<?php /* If Logged In: Start */ ?>
<?php if (isset($entity) && $entity) { ?>
<div class="memcurplan">
	<div class="memcurplan_inner">
		
		<h3>Your current plan is 
			<strong>
				<?php 
					echo $entity->user_type_name;
					if ($account->trial_taken == TRIAL_BEING_TAKEN) {
						echo ' Trial';
					}
				?>
			</strong>
		</h3>

		<a href="<?php echo $base_url; ?>details/<?php echo $entity->user_type_id; ?>" class="button red allfeaturess">Click here to view all features</span></a>
		
		<div class="clear"></div>
	</div>
	
	<div class="clear"></div>
</div>
<?php } ?>
<?php /* If Logged In: End */ ?>

<?php /* If Not Logged In: Start */ ?>
<?php if (!(isset($account) && $account)) { ?>
<div class="planheroinfo">
	<div class="planherotop">
		<div class="planherotopcol left">
			<h2>Instant Monocode</h2>
			
			<h3>Register in seconds. Instant setup. Secure in the Cloud.</h3>
			
			<div class="clear"></div>
		</div>
		
		<div class="planherotopcol right">
			
			<div>
				<a href="<?php echo base_url(); ?>guest/register" class="button teal">Create Free Account Now</a>
			</div>
			
			<div class="planherotophaveacc">
				Already have an account? <a href="<?php echo base_url(); ?>guest/login">Login now</a>
			</div>
			
			<div class="clear"></div>
		</div>
		
		<div class="clear"></div>
	</div>
	
	<ul class="planinfolist">
		<li>Get started in minutes</li>
		<li>&bull;</li>
		<li>We take care of hosting</li>
		<li>&bull;</li>
		<li>Site always up to date</li>
		<li>&bull;</li>
		<li>Secure, Stable, and Reliable</li>
	</ul>
	
	<div class="clear"></div>
</div>
<?php } ?>
<?php /* If Not Logged In: End */ ?>

<?php /* Plans: Start */ ?>
<div class="plansholder">
    <?php
    $plannum = 0;
	$oddeven = 0;
    foreach ($entities as $ety) {
        $plannum++;
        ?>
		
        <?php /* Plan Item: Start */ ?>
        <div class="planitem <?php echo 'plannum' . $plannum; echo ++$oddeven%2 ? ' odd' : ' even'; ?>">
			<div class="planitem_outer">
				<div class="planitem_inner">
					
					<?php /* If In Trial: Start */ ?>
					<?php if (isset($account) && $account && $account->trial_taken == TRIAL_BEING_TAKEN && $account->user_type == $ety->user_type_id) { ?>
					<div class="currentlyintrial">
						<div class="currentlyintrial_inner"></div>
					</div>
					<?php } ?>
					<?php /* If In Trial: End */ ?>
					
					<?php /* Plan Item Top: Start */ ?>
					<div class="planitemtop">
						<div class="planitemtop_inside">
							
							<?php /* Info: Start */ ?>
							<div class="planinfo">
								<div class="planinfo_inside">
									<h3><?php echo $ety->user_type_name; ?></h3>
									
									<?php if ($ety->amount == '0' || $ety->amount == '') { ?>
									<h4>
										<span class="priceamt">
											<span class="price">
												<span class="camount">Free</span>
											</span>
										</span>
										<span class="pricetxt top">No price to get started!</span>
										<span class="pricetxt bottom">Upgrade at any time</span>
									</h4>
									
									<?php } else { ?>
									<h4>
										<span class="priceamt">
											<span class="price">
												<?php echo 
													'<sup class="csymbol">' . $settings->currency . '</sup>' . 
													'<span class="camount">' . $ety->amount . '</span>' . 
													'<span class="pricemonthly">/' . 'month</span>';
												?>
											</span>
										</span>
										<span class="pricetxt top">Price is per month (billed annually)</span>
										<span class="pricetxt bottom">
											<?php echo $settings->currency . ($ety->amount + (($ety->amount) * .2)) . ' (billed monthly)'; ?>
										</span>
									</h4>
									<?php } ?>
									
									<div class="clear"></div>
								</div>
								
								<div class="clear"></div>
							</div>
							<?php /* Info: End */ ?>
							
							<div class="clear"></div>
						</div>
						
						<div class="clear"></div>
					</div>
					<?php /* Plan Item Top: End */ ?>
					
					<?php /* Plan Item Bottom: Start */ ?>
					<div class="planitembottom">
						<div class="planitembottom_inside">
							
							<?php /* Plan Features: Start */ ?>
							<div class="planfeatures">
								<div class="planfeatures_inside">
									
									<?php /* Features: Start */ ?>
									<ul class="planfeatureslist">
										<?php
										if (isset($ety->feature_details) && $ety->feature_details && count($ety->feature_details) > 0) {
											foreach ($ety->feature_details as $detail) {
												?>
												<li class="<?php echo ++$plannum%2 ? 'even' : 'odd'; ?>">
													<span><?php echo $detail; ?></span>
												</li>
												<?php
											}
										}
                                        if ((!isset($account) || !$account->bt_used) && $settings->trial_period && $ety->amount > 0) {
                                            echo "<li class='planfeature_trial'>$settings->trial_duration $settings->trial_duration_unit" . ($settings->trial_duration > 1 ? '' : '') . " Trial Period</li>";
                                        }
										?>
									</ul>
									<?php /* Features: End */ ?>
									
									<div class="clear"></div>
								</div>
								
								<div class="clear"></div>
							</div>
							<?php /* Plan Features: End */ ?>
							
							<?php /* Signup/Upgrade/Downgrade: Start */ ?>
							<?php
							if (!isset($entity) || !$entity) {
								$text = 'Start Now';
								$url = $base_url . 'start/' . $ety->user_type_id;
							} else if ($entity->display_order < $ety->display_order) {
								$text = 'Upgrade to ' . $ety->user_type_name;
								$url = $base_url . 'upgrade/' . $ety->user_type_id;
							} else if ($entity->display_order > $ety->display_order) {
								$text = 'Downgrade to ' . $ety->user_type_name;
								$url = $base_url . 'downgrade/' . $ety->user_type_id;
							} else {
								$url = current_url() . '#';
								$text = "This is your current plan";
							}
							?>
							<div class="plansignup">
								<div class="plansignup_inside">
									<a href='<?php echo $url; ?>' class="button teal planbttn basic">
										<span><?php echo $text; ?></span>
									</a>
									
									<div class="clear"></div>
								</div>
								
								<div class="clear"></div>
							</div>
							<?php /* Signup/Upgrade/Downgrade: End */ ?>
							
							<div class="clear"></div>
						</div>
						
						<div class="clear"></div>
					</div>
					<?php /* Plan Item Bottom: End */ ?>
					
					<div class="clear"></div>
				</div>
				
				<div class="clear"></div>
			</div>
			
            <div class="clear"></div>
        </div>
        <?php /* Plan Item: End */ ?>
		
    <?php } ?>
	
    <div class="clear"></div>
</div>
<?php /* Plans: End */ ?>

<?php /* Plan Descriptions: Start */ ?>
<div class="plandescriptions">
    <?php
    $plannum = 0;
	$oddeven = 0;
    foreach ($entities as $ety) {
        $plannum++;
        ?>
		<div class="plandescitem <?php echo ++$oddeven%2 ? 'odd' : 'even'; ?>">
			<p class="planitemdesc"><?php echo $ety->user_type_description; ?></p>
			
			<div class="clear"></div>
		</div>
	<?php } ?>
	
	<div class="clear"></div>
</div>
<?php /* Plan Descriptions: End */ ?>

<?php /* Plans Information: Start */ ?>
<?php if (!isset($entity) || !$entity) { ?>
<div class="mplansinfo">
	<div class="mplansinfo_inner">
		
		<?php /* Left Column: Start */ ?>
		<div class="mplansinfocolumn colleft">
			
			<?php /* Easy Development: Start */ ?>
			<div class="mplansinfoitem easydevelopment">
				<h3>Easy Development</h3>
				
				<p>Creating workspaces, files, projects from a template and more are part of a streamline process very similar to the most popular code editors.</p>
				
				<div class="clear"></div>
			</div>
			<?php /* Easy Development: End */ ?>
			
			<?php /* Stunnding Features: Start */ ?>
			<div class="mplansinfoitem stunningfeatures">
				<h3>Stunning Features</h3>
				
				<p>Calculator widget, color picker, terminal access, FTP, and phpMyAdmin are just a small taste of what's available.</p>
				
				<div class="clear"></div>
			</div>
			<?php /* Stunnding Features: End */ ?>
			
			<?php /* Theming: Start */ ?>
			<div class="mplansinfoitem theming">
				<h3>Theming</h3>
				
				<p>Themes available for both the application as well as the code editor. Select a theme that works for you.</p>
				
				<div class="clear"></div>
			</div>
			<?php /* Theming: End */ ?>
			
			<div class="clear"></div>
		</div>
		<?php /* Left Column: End */ ?>
		
		<?php /* Middle Column: Start */ ?>
		<div class="mplansinfocolumn colmiddle">
			<div class="mplansinfoimg">
				<img src="" alt="" />
			</div>
			
			<div class="clear"></div>
		</div>
		<?php /* Middle Column: End */ ?>
		
		<?php /* Right Column: Start */ ?>
		<div class="mplansinfocolumn colright">
			
			<?php /* FTP/SFTP Access: Start */ ?>
			<div class="mplansinfoitem ftpaccess">
				<h3>FTP/SFTP Access</h3>
				
				<p>FTP client built directly into the application so you can connect to your host to upload and download files.</p>
				
				<div class="clear"></div>
			</div>
			<?php /* FTP/SFTP Access: End */ ?>
			
			<?php /* Cloud Storage: Start */ ?>
			<div class="mplansinfoitem cloudstorage">
				<h3>Cloud Storage</h3>
				
				<p>Access your files with any device, any time, from anywhere. Your files are always accessible, securely.</p>
				
				<div class="clear"></div>
			</div>
			<?php /* Cloud Storage: End */ ?>
			
			<?php /* Database Management: Start */ ?>
			<div class="mplansinfoitem databasemanagement">
				<h3>Database Management</h3>
				
				<p>Creating your databases is as simple as hitting a create button. Then manage your databases using phpMyAdmin.</p>
				
				<div class="clear"></div>
			</div>
			<?php /* Database Management: End */ ?>
			
			<div class="clear"></div>
		</div>
		<?php /* Right Column: End */ ?>
		
		<div class="clear"></div>
	</div>
	
	<div class="clear"></div>
</div>
<?php } ?>
<?php /* Plans Information: End */ ?>