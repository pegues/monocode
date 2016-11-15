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

<?php $this->view($layout_path . '../breadcrumb.php'); ?>
<?php /* Page Content: Start */ ?>
<section class="sec-content">
    <div class="wrapper_content">
        <div class="container content">
            <div class="container_inside contentoutput">
                <?php $this->view($layout_path . '../messages.php'); ?>