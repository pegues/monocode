<?php $this->view($layout_path . '../header.php'); ?>

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

<?php /* Page Title: Start */ ?>
<section class="sec-pagetitle">
    <div class="wrapper_pagetitle">
        <div class="container pagetitleholder">
            <h1 class="pagetitle"><?php echo $page_title; ?></h1>

            <div class="clear"></div>
        </div>
		
        <div class="clear"></div>
    </div>
</section>
<?php /* Page Title: End */ ?>

<?php /* Breadcrumbs: Start */ ?>
<section class="sec-breadcrumb">
    <div class="wrapper_breadcrumbs">
        <div class="container breadcrumbs">
            <ul class="breadcrumbs">
                <li>
                    <a href="<?php echo base_url(); ?>">
                        <i class="fa fa-home"></i>Home
                    </a>
                    <span class="separator">
                        <i class="fa fa-angle-right"></i>
                    </span>
                </li>
                <li>
                    <a href="<?php echo $base_url; ?>">
                        My Account
                    </a>
                    <span class="separator">
                        <i class="fa fa-angle-right"></i>
                    </span>
                </li>
                <li>
                    <span class="current"><?php echo $page_title; ?></span>
                </li>
            </ul>
			
            <div class="clear"></div>
        </div>
		
        <div class="clear"></div>
    </div>
</section>
<?php /* Breadcrumbs: End */ ?>

<?php /* Page Content: Start */ ?>
<section class="sec-content">
    <div class="wrapper_content">
        <div class="container content">
            <div class="container_inside contentoutput">
                <div class="maincontent">
					
                    <?php /* My Account Left Column: Start */ ?>
                    <div class="contentcolumn myaccount">
                        <h3>My Account</h3>

                        <ul class="myaccountnavlist">
                            <li class="odd first <?php echo (isset($sub_module) && $sub_module == 'profile') ? 'current' : ''; ?>">
								<a href="<?php echo $base_url; ?>profile">Edit My Profile</a>
							</li>
                            <li class="even <?php echo (isset($sub_module) && $sub_module == 'membership') ? 'current' : ''; ?>">
								<a href="<?php echo $base_url; ?>membership">View Membership Details</a>
							</li>
                            <li class="odd">
								<a href="<?php echo base_url(); ?>membership">Upgrade/Downgrade Plan</a>
							</li>
                            <li class="even <?php echo (isset($sub_module) && $sub_module == 'paymentmethod') ? 'current' : ''; ?>">
								<a href="<?php echo $base_url; ?>paymentmethod">Payment Method</a>
							</li>
                            <li class="odd <?php echo (isset($sub_module) && $sub_module == 'paymentstatus') ? 'current' : ''; ?>">
								<a href="<?php echo $base_url; ?>paymentstatus">Payment Status</a>
							</li>
							<?php /*
                            <li class="even">
								<a href="<?php echo $base_url; ?>editorsettings">Editor Settings</a>
							</li>
							*/ ?>
                            <li class="odd <?php echo (isset($sub_module) && $sub_module == 'invoices') ? 'current' : ''; ?>">
								<a href="<?php echo $base_url; ?>invoices">View Invoices</a>
							</li>
                            <li class="even <?php echo (isset($sub_module) && $sub_module == 'cancel') ? 'current' : ''; ?>">
								<a href="<?php echo $base_url; ?>cancel">Cancel Account</a>
							</li>
                            <li class="odd last <?php echo (isset($sub_module) && $sub_module == 'notifications') ? 'current' : ''; ?>">
								<a href="<?php echo $base_url; ?>notifications">Notifications</a>
							</li>
                        </ul>

                        <div class="clear"></div>
                    </div>
                    <?php /* My Account Left Column: End */ ?>
					
                    <?php /* Main Content: Start */ ?>
                    <div class="maincolumn myaccount">

                        <div class="myaccountcontainer userformcontainer">
                            <h3><?php echo $page_title; ?></h3>
							
                            <?php $this->view($layout_path . '../messages.php'); ?>
