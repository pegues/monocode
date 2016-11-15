<?php
/*
Template Name : Contact Page
*/
?>

<?php
	$bodyClass		= 'contentpage contact';
	$header_addons 	= '<script>console.log("contact page");</script>';
	
	// Load Header
	include("includes/header.php");
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

<?php /* Breadcrumbs: Start */ ?>
<?php echo get_breadcrumb(); ?>
<?php /* Breadcrumbs: End */ ?>

<?php /* Page Content: Start */ ?>
<section class="sec-content">
	<div class="wrapper_content">
		<div class="container content">
			<div class="container_inside contentoutput">
				
				<h3><?php echo $post[0]->cms_title; ?></h3>
				
				<div class="cmscontentoutput">
					<?php echo $post[0]->cms_content; ?>
					
					<div class="clear"></div>
				</div>
				
				<div>
					<div><?php print_r($post); ?></div>
					
					<div>
						test contact page whatever you can place here<br/>
						if ypu need db value, just use print_r($post) then you are done
					</div>
					
					<div class="clear"></div>
				</div>
				
				<div>
					<form>
						<label>Name</label>
						<input type="text" value="" class="" id="" />
					</form>
					
					<div class="clear"></div>
				</div>
				
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