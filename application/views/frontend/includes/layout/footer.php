<?php
	include("footer_promo.php");
	include("footer_widgets.php");
?>

		<?php /* Footer Top: Start */ ?>
		<section class="sec-footertop">
			<div class="wrapper_footertop">
				<div class="container footertop">
					<div class="container_inside">
						
						<?php /* Footer Columns: Start */ ?>
						<div class="footercolumns">
							
							<?php /* Footer Logo and Info: Start */ ?>
							<div class="footercol logo">
								<div class="footercol_inner">
									<div class="ftr_logo">
										<a href="<?php echo base_url(); ?>">
											<span class="icon-monocode"></span>
											<span class="logo_text">Monocode</span>
										</a>
									</div>
									
									<div class="clear"></div>
								</div>
								
								<div class="clear"></div>
							</div>
							<?php /* Footer Logo and Info: End */ ?>
							
							<?php /* Footer About: Start */ ?>
							<div class="footercol links">
								<div class="footercol_inner">
									<h3>About</h3>
									
									<ul>
										<li><a href="<?php echo base_url(); ?>about">About Monocode</a></li>
										<li><a href="<?php echo base_url(); ?>membership">Membership Pricing</a></li>
										<li><a href="<?php echo base_url(); ?>features">Features</a></li>
										<li><a href="<?php echo base_url(); ?>currentjobs">Current Jobs</a></li>
									</ul>
									
									<div class="clear"></div>
								</div>
								
								<div class="clear"></div>
							</div>
							<?php /* Footer About: End */ ?>
							
							<?php /* Footer Resources: Start */ ?>
							<div class="footercol links">
								<div class="footercol_inner">
									<h3>Resources</h3>
									
									<ul>
										<li><a href="<?php echo base_url(); ?>gettingstarted">Getting Started</a></li>
										<li><a href="<?php echo base_url(); ?>turorials">Tutorials</a></li>
										<li><a href="<?php echo base_url(); ?>howtos">How-to's</a></li>
										<li><a href="<?php echo base_url(); ?>forum">Support Forum</a></li>
									</ul>
									
									<div class="clear"></div>
								</div>
								
								<div class="clear"></div>
							</div>
							<?php /* Footer Resources: End */ ?>
							
							<?php /* Footer Connect: Start */ ?>
							<div class="footercol links">
								<div class="footercol_inner">
									<h3>Connect</h3>
									
									<ul class="footer_social">
										<li class="facebook">
											<a href="<?php echo get_settings('facebook_link'); ?>">Facebook</a>
										</li>
										<li class="twitter">
											<a href="<?php echo get_settings('twitter_link'); ?>">Twitter</a>
										</li>
										<li class="googleplus">
											<a href="<?php echo get_settings('gplus_link'); ?>">Google+</a>
										</li>
									</ul>
									
									<div class="clear"></div>
								</div>
								
								<div class="clear"></div>
							</div>
							<?php /* Footer Connect: End */ ?>
							
							<div class="clear"></div>
						</div>
						<?php /* Footer Columns: End */ ?>
						
						<div class="clear"></div>
					</div>
					
					<div class="clear"></div>
				</div>
				
				<div class="clear"></div>
			</div>
		</section>
		<?php /* Footer Top: End */ ?>
		
    	<?php /* Footer Bottom: Start */ ?>
		<section class="sec-footerbottom">
			<div class="wrapper_footerbottom">
				<div class="container footerbottom">
					<div class="container_inside">
						
						<?php /* Terms, Privacy and Status: Start */ ?>
						<ul class="termsprivacylist">
							<li><a href="<?php echo base_url(); ?>termsofuse">Terms of Use</a></li>
							<li><a href="<?php echo base_url(); ?>privacypolicy">Privacy Policy</a></li>
							<li><a href="<?php echo base_url(); ?>status">Status</a></li>
						</ul>
						<?php /* Terms, Privacy and Status: End */ ?>

						<?php /* Footer Copyright: Start */ ?>
						<div class="footer_copyright">
							Copyright &copy; <?php echo date('Y'); ?> <a href="<?php echo base_url(); ?>">Monocode, LLC</a>. All Rights Reserved.
							
							<div class="clear"></div>
						</div>
						<?php /* Footer Copyright: End */ ?>
						
						<div class="clear"></div>
					</div>
					
					<div class="clear"></div>
				</div>
				
				<div class="clear"></div>
			</div>
		<section>
    	<?php /* Footer Bottom: Start */ ?>
    	
    	<div class="clear"></div>
	</div>
	<?php /* Inner Wrapper: End */ ?>
	
	<div class="clear"></div>
</div>
<?php /* Main Wrapper: End */ ?>

<!-- Script Files Start -->
<?php
if (isset($scripts) && sizeof($scripts) > 0) {
    foreach ($scripts as $script) {
        ?>
        <script src="<?php echo $script; ?>" type="text/javascript"></script>
        <?php
    }
}
?>
<!-- Script Files End -->
</body>
</html>