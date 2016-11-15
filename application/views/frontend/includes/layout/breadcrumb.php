<section class="sec-breadcrumb">
    <div class="wrapper_breadcrumbs">
        <div class="container breadcrumbs">
            <ul class="breadcrumbs">
                <li>
                    <a href="<?php echo base_url(); ?>">
                        <i class="fa fa-home"></i>Home
                    </a>
                    <?php if (!(!isset($current_page) || !$current_page || $current_page->link == 'home')) { ?>
                        <span class="separator">
                            <i class="fa fa-angle-right"></i>
                        </span>
                    <?php } ?>
                </li>
				
                <?php
                if (isset($current_page) && $current_page && count($current_page->parents) > 0) {
                    foreach ($current_page->parents as $parent) {
                        ?>
                        <li>
                            <a href="<?php echo $parent->link; ?>">
                                <?php echo $parent->title; ?>
                            </a>
                            <span class="separator">
                                <i class="fa fa-angle-right"></i>
                            </span>
                        </li>
                        <?php
                    }
                }
                ?>
				
                <li>
                    <span class="current"><?php echo $page_title; ?></span>
                </li>
            </ul>
			
            <div class="clear"></div>
        </div>
		
        <div class="clear"></div>
    </div>
</section>
