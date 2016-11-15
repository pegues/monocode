<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="description" content="Monocode" />
        <meta name="author" content="Monocode" />

        <title><?php echo ($page_title ? ($page_title . ' - ') : '') . $settings->site_name; ?></title>

        <base href="<?php echo base_url(); ?>" />

        <link rel="stylesheet" href="<?php echo base_url(); ?>themes/frontend/monocode/css/styles.css" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>themes/frontend/monocode/css/font-awesome.min.css" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>themes/frontend/monocode/css/passrev.css" />

        <script src="<?php echo base_url(); ?>themes/frontend/monocode/js/jquery-1.11.0.min.js"></script>
        <script src="<?php echo base_url(); ?>themes/frontend/monocode/js/jquery.validate.min.js"></script>
        <script src="<?php echo base_url(); ?>themes/frontend/monocode/js/jquery.ba-throttle-debounce.min.js"></script>
        <script src="<?php echo base_url(); ?>themes/frontend/monocode/js/monocode.js"></script>
        <script src="<?php echo base_url(); ?>themes/frontend/monocode/js/passrev.js"></script>
        <script>
            var DEBUG = <?php echo DEBUG ? 'true' : 'false'; ?>;
        </script>
        <!--[if lt IE 9]>
        <script src="<?php echo base_url(); ?>themes/frontend/monocode/js/ie8-responsive-file-warning.js"></script>
        <![endif]-->

        <?php /* HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries */ ?>
        <!--[if lt IE 9]>
                <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
                <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->

    </head>
    <body>

        <?php /* Main Wrapper: Start */ ?>
        <div class="wrapper">

            <?php /* Inner Wrapper: Start */ ?>
            <div class="wrapper_inside">

                <?php /* Header: Start */ ?>
                <section class="sec-header">
                    <div class="wrapper_header">

                        <?php if (isset($account) && $account) { ?>
                            <div class="container headertop">
                                <div class="container_inside">
                                    <ul id="authstatustoplist" class="authstatustoplist loggedin">
                                        <li class="parent first odd openeditor">
                                            <a href="<?php base_url(); ?>account/files">
                                                <i class="fa fa-hdd-o"></i>
                                                <span>File Explorer</span>
                                            </a>
                                        </li>
                                        <li class="parent first odd openeditor">
                                            <a href="<?php base_url(); ?>editor">
                                                <i class="fa fa-code"></i>
                                                <span>Open Editor</span>
                                            </a>
                                        </li>
                                        <li class="parent last even loggedin">
                                            <span>Logged in as <?php echo $this->session->userdata('user_name'); ?></span>
                                        </li>
                                    </ul>

                                    <div class="clear"></div>
                                </div>

                                <div class="clear"></div>
                            </div>
                        <?php } ?>

                        <div class="container header">
                            <div class="container_inside">

                                <?php /* Logo: Start */ ?>
                                <div class="sitelogo">
                                    <div class="logo_outer">
                                        <a class="logo_inner" href="<?php echo base_url(); ?>">
                                            <span class="icon-monocode"></span>
                                            <span class="logo_text">Monocode</span>
                                        </a>
                                    </div>

                                    <div class="clear"></div>
                                </div>
                                <?php /* Logo: End */ ?>

                                <?php /* Navigation: Start */ ?>
                                <?php include('navigation.php'); ?>
                                <?php /* Navigation: End */ ?>

                                <div class="clear"></div>
                            </div>

                            <div class="clear"></div>
                        </div>

                        <div class="clear"></div>
                    </div>
                </section>
                <?php /* Header: End */ ?>
