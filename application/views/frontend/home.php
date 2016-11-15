
<!-- Homepage Banner Slider -->
<link rel="stylesheet" href="<?php echo base_url(); ?>themes/frontend/monocode/css/masterslider.css" />
<script src="<?php echo base_url(); ?>themes/frontend/monocode/js/masterslider/masterslider.min.js"></script>
<script src="<?php echo base_url(); ?>themes/frontend/monocode/js/masterslider/jquery.easing.min.js"></script>

<!-- Animations -->
<script src="<?php echo base_url(); ?>themes/frontend/monocode/js/greensock/TweenMax.min.js"></script>
<script src="<?php echo base_url(); ?>themes/frontend/monocode/js/jquery.lettering-0.6.1.min.js"></script>
<script src="<?php echo base_url(); ?>themes/frontend/monocode/js/jquery.superscrollorama.js"></script>

<script>
    $(document).ready(function () {
		
        /**
         * Banner Slider
         */
        var slider = new MasterSlider();
		
        // adds Arrows navigation control to the slider.
        slider.control("arrows");
        slider.control("timebar", {insertTo: "#masterslider"});
        slider.control("bullets");
		
        slider.setup("masterslider", {
            //width: 1400, // slider standard width
            //height: 580, // slider standard height
            autoHeight: true,
            space: 1,
            layout: "fullwidth",
            loop: true,
            preload: 0,
            autoplay: true
        });
		
        //SyntaxHighlighter.all();
		
        /**
         * Animations
         */
        var controller = $.superscrollorama();
		
        /* GUIDE
         controller.addTween("#fade", 
         TweenMax.from($("#fade"),.5,{
         css:{opacity:0}}),
         0, // scroll duration of tween (0 means autoplay)
         200); // offset the start of the tween by 200 pixels
         */
		
        // Laptop Image
        controller.addTween("div.userfeaturedcol.leftimg img",
            TweenMax.from($("div.userfeaturedcol.leftimg img"),
                .5 + Math.random(),
                {css: {opacity: 0, margin: "0 0 0 -250px"}})
		);
		
        // Image Group Descriptions
        controller.addTween("img.imgsystemgroup_desc",
            TweenMax.from($("img.imgsystemgroup_desc"),
                .5 + Math.random(),
                {css: {opacity: 0, top: "-150px"}}),
            0,325
		);
		
        // Image Group
        controller.addTween("img.imgsystemgroup",
            TweenMax.from($("img.imgsystemgroup"),
                .5 + Math.random(),
                {css: {opacity: 0, bottom: "-150px"}})
		);
		
        // Featured Images: Row 1 (Image)
        controller.addTween("li.featureitem.row1 div.featureditem_img",
            TweenMax.from($("li.featureitem.row1 div.featureditem_img"),
                .25 + Math.random(),
                {css: {opacity: 0, margin: "0 0 -40px"}}),
            0,-300
		);
		
        // Featured Images: Row 1 (Description)
        controller.addTween("li.featureitem.row1 div.featureitem_desc",
            TweenMax.from($("li.featureitem.row1 div.featureitem_desc"),
                .25 + Math.random(),
                {css: {opacity: 0}}),
            0,-200
        );
		
        // Featured Images: Row 2 (Image)
        controller.addTween("li.featureitem.row2 div.featureditem_img",
            TweenMax.from($("li.featureitem.row2 div.featureditem_img"),
                .25 + Math.random(),
                {css: {opacity: 0, margin: "0 0 -40px"}}),
            0,-300
        );
		
        // Featured Images: Row 2 (Description)
        controller.addTween("li.featureitem.row2 div.featureitem_desc",
            TweenMax.from($("li.featureitem.row2 div.featureitem_desc"),
                .25 + Math.random(),
                {css: {opacity: 0}}),
            0,-200
		);
		
        // Featured Images: Row 3 (Image)
        controller.addTween("li.featureitem.row3 div.featureditem_img",
            TweenMax.from($("li.featureitem.row3 div.featureditem_img"),
                .25 + Math.random(),
                {css: {opacity: 0, margin: "0 0 -40px"}}),
            0,-300
		);
		
        // Featured Images: Row 3 (Description)
        controller.addTween("li.featureitem.row3 div.featureitem_desc",
            TweenMax.from($("li.featureitem.row3 div.featureitem_desc"),
                .25 + Math.random(),
                {css: {opacity: 0}}),
            0,-200
        );
		
        // Assign Height of Image to Image Parent Container
        imgSystem();
        $(window).on("resize", function () {
            imgSystem()
        });
		
        $("a.featureditem_link").mouseenter(function () {
            $(this).find("i").stop(true, true).animate({opacity: "1.00", top: "50%"});
            $(this).find("span.featureditem_cover").stop(true, true).animate({opacity: "1.00"});
        });
		
        $("a.featureditem_link").mouseleave(function () {
            $(this).find("i").animate({opacity: "0", top: "-10%"});
            $(this).find("span.featureditem_cover").animate({opacity: "0"});
        });
    });
	
	// Function used for Image Height to Parent
    function imgSystem() {
        var imgSysWidth = $("img.imgsystemgroup").width();
        var imgSysHeight = $("img.imgsystemgroup").height();
        $("div.presentation_imgsystemgroup").css({height: imgSysHeight});
        $("img.imgsystemgroup,img.imgsystemgroup_desc").css({marginLeft: -(imgSysWidth / 2)});
    }
</script>

<?php include('includes/layout/messages.php') ?>

<?php /* Homepage Slider: Start */ ?>
<section class="sec-banner">
    <div class="wrapper_banner">
        <div class="container bannerslider homepage">
			
            <?php /* Banner: Start */ ?>
            <div class="master-slider ms-skin-default" id="masterslider">

                <?php /* Slide 1: Start */ ?>
                <div class="ms-slide slide-1" data-delay="14">

                    <!-- slide background -->
                    <img src="<?php echo base_url(); ?>themes/frontend/monocode/images/masterslider/blank.gif"
                         data-src="<?php echo base_url(); ?>media/bannerslider/banner_editor_on_laptop.jpg"
                         alt="Slide 1 Background" />

                </div>
                <?php /* Slide 1: End */ ?>

                <?php /* Slide 2: Start */ ?>
                <div class="ms-slide slide-2" data-delay="14">
					
                    <!-- slide background -->
                    <img src="<?php echo base_url(); ?>themes/frontend/monocode/images/masterslider/blank.gif" 
                        data-src="<?php echo base_url(); ?>media/bannerslider/banner_editor_on_ipad.jpg" 
                        alt="Slide 2 background" />
					
                </div>
                <?php /* Slide 2: End */ ?>
				
                <?php /* Slide 3: Start */ ?>
                <div class="ms-slide slide-3" data-delay="10">
					
                    <!-- slide background -->
                    <img src="<?php echo base_url(); ?>themes/frontend/monocode/images/masterslider/blank.gif" 
                        data-src="<?php echo base_url(); ?>media/bannerslider/banner_keyboard_on_desk_01.jpg" 
                        alt="Slide 3 background" />
					
                </div>
                <?php /* Slide 3: End */ ?>
				
                <?php /* Slide 4: Start */ ?>
                <div class="ms-slide slide-4" data-delay="15">
					
                    <!-- slide background -->
                    <img src="<?php echo base_url(); ?>themes/frontend/monocode/images/masterslider/blank.gif" 
                        data-src="<?php echo base_url(); ?>media/bannerslider/banner_keyboard_on_desk_02.jpg" 
                        alt="Slide 4 background" />
					
                </div>
                <?php /* Slide 4: End */ ?>
				
            </div>
            <?php /* Banner: End */ ?>
			
            <div class="clear"></div>
        </div>
		
        <div class="clear"></div>
    </div>
</section>
<?php /* Homepage Slider: End */ ?>

<?php /* Application Feature Slides: Start */ ?>
<section class="sec-appfeatured">
    <div class="wrapper_appfeatured">
        <div class="container appfeatured contentoutput">
            <div>
                <h2 class="section-heading aligncenter">Development in the cloud has never been better.</h2>
				
                <p class="aligncenter">Monocode provides you all the features you need for developing applications, with all the benefits of the cloud. Access your files from anywhere, any time. Use any browser, and any device.</p>
            </div>
			
            <img src="<?php echo base_url(); ?>media/images/sec_banner_appfeatured.png" alt="Application Features Image" />
			
            <div class="clear"></div>
        </div>
		
        <div class="clear"></div>
    </div>
</section>
<?php /* Application Feature Slides: End */ ?>

<?php /* Featured Info and Current Member Info: Start */ ?>
<section class="sec-userfeatured">
    
	<div class="wrapper_featuredandcurmem">
		
		<?php /* User Featured Information: Start */ ?>
		<div class="wrapper_userfeatured">
			<div class="container userfeatured">
				<div class="container_inside contentoutput">
					
					<div class="userfeatured_columns">
						
						<?php /* Column Image: Start */ ?>
						<div class="userfeaturedcol leftimg">
							<img src="<?php echo base_url(); ?>media/images/sec_screen_laptop.png" alt="Image of Laptop Screen" />
							
							<div class="clear"></div>
						</div>
						<?php /* Column Image: End */ ?>
						
						<?php /* Column Content: Start */ ?>
						<div class="userfeaturedcol right">
							<h2 class="section-heading aligncenter">A simple, powerful development tool.<br/>Everything, all in one place.</h2>
							
							<p>You no longer need a very expensive tool to develop websites for your clients. Monocode has all the features any developer needs to create the simplest to the most complex web applications. Whether you're developing a WordPress site, modifying the code for a custom Joomla extension, or need to create a simple web theme, Monocode has all the tools to make your development tasks easier.</p>
							
							<div class="clear"></div>
						</div>
						<?php /* Column Content: End */ ?>
						
						<div class="clear"></div>
					</div>
					
					<div class="clear"></div>
				</div>
				
				<div class="clear"></div>
			</div>
			
			<div class="clear"></div>
		</div>
		<?php /* User Featured Information: End */ ?>
		
		<?php /* Current Members: Start */ ?>
		<div class="wrapper_currentmembers">
			<div class="container currentmembers">
				<div class="container_inside contentoutput">
					
					<?php /* Stats Output: Start */ ?>
					<div class="currentmemberitems">
						<div class="currentmemberitem col-3 first">
							<div class="currentmemberitem_inside">
								<div class="currentmembericon">
									<i class="fa fa-users"></i>
								</div>
								<div class="currentmembervalue">
									<?php echo number_format($subscribers); ?>
								</div>
								<div class="currentmemberlabel">
									Current Subscribers
								</div>
								
								<div class="clear"></div>
							</div>
							
							<div class="clear"></div>
						</div>
						
						<div class="currentmemberitem col-3">
							<div class="currentmemberitem_inside">
								<div class="currentmembericon">
									<i class="fa fa-random"></i>
								</div>
								<div class="currentmembervalue">
									<?php echo number_format($projects); ?>
								</div>
								<div class="currentmemberlabel">
									Projects
								</div>
								
								<div class="clear"></div>
							</div>
							
							<div class="clear"></div>
						</div>
						
						<div class="currentmemberitem col-3 last">
							<div class="currentmemberitem_inside">
								<div class="currentmembericon">
									<i class="fa fa-file-code-o"></i>
								</div>
								<div class="currentmembervalue">
									<?php echo number_format($files); ?>
								</div>
								<div class="currentmemberlabel">
									Total Files
								</div>
								
								<div class="clear"></div>
							</div>
							
							<div class="clear"></div>
						</div>
						
						<div class="clear"></div>
					</div>
					<?php /* Stats Output: End */ ?>
					
					<div class="clear"></div>
					
					<div class="homesubtitlesep">
						<div class="homesubtitlesep_inside">
							
							<?php if (isset($account) && $account) { ?>
								<a href="<?php echo base_url();?>account/membership" class="button teal">Upgrade your account to add more features!</a>
							<?php } else { ?>
								<a href="<?php echo base_url();?>guest/register" class="button teal">Sign up today and start experiencing easier code development</a>
							<?php } ?>
							
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
		<?php /* Current Members: End */ ?>
		
		<div class="clear"></div>
	</div>
</section>
<?php /* Featured Info and Current Member Info: End */ ?>

<?php /* Presentation Features: Start */ ?>
<section class="sec-presentation">
    <div class="wrapper_presentation">
        <div class="container presentation">
            <div class="container_inside contentoutput">
				
                <h2 class="section-heading aligncenter">Perfect for any kind of project</h2>
				
                <p>Monocode is a professional development tool that gives you access to your code from anywhere, at any time.</p>
				
				<?php /*
				<ul class="presentationnavlist">
					<li class="odd first">
						<a href="#">
							<span class="presentationicon"><i class="fa fa-code-fork"></i></span>
							<span class="presentationlabel">Multi-Purpose</span>
						</a>
					</li>
					<li class="even">
						<a href="#">
							<span class="presentationicon"><i class="fa fa-keyboard-o"></i></span>
							<span class="presentationlabel">Language Support</span>
						</a>
					</li>
					<li class="odd last">
						<a href="#">
							<span class="presentationicon"><i class="fa fa-plug"></i></span>
							<span class="presentationlabel">Direct Access</span>
						</a>
					</li>
				</ul>
				*/ ?>
				
                <div class="presentation_imgsystemgroup" aria-hidden="true">
                    <img class="imgsystemgroup_desc" src="<?php echo base_url(); ?>media/images/sec_screen_system_group_desc.png" alt="" />
                    <img class="imgsystemgroup" src="<?php echo base_url(); ?>media/images/sec_screen_system_group.png" alt="" />
                </div>

                <ul class="presentationnavlist">
                    <li class="presentationicon tablet"><strong>Tablet Support:</strong> The perfect code editor for your tablet/iPad device.</li>
                    <li class="presentationicon navigation"><strong>Easy Navigation:</strong> The editor has been developed to match the common features of all the popular code editors.</li>
                    <li class="presentationicon tab"><strong>Tab Layout:</strong> We've developed the editor with a very easy-to-use tab structure.</li>
                    <li class="presentationicon editor"><strong>Amazing Code Editor:</strong> We've harnessed the power of ACE code editor to provide highlighting for more than 160 languages!</li>
                    <li class="presentationicon access"><strong>Quick Account Access:</strong> You can quickly access your account details in seconds. The entire application works seamlessly everywhere in the site.</li>
                    <li class="presentationicon devices"><strong>Made for all devices:</strong> The code editor has been made with all devices in mind.</li>
                </ul>

                <div class="clear"></div>
            </div>
			
            <div class="clear"></div>
        </div>
		
        <div class="clear"></div>
    </div>
</section>
<?php /* Presentation Features: End */ ?>

<?php /* Features Tag Line: Start */ ?>
<section class="sec-featurestagline">
    <div class="wrapper_featurestagline">
        <div class="container featurestagline">
            <div class="container_inside contentoutput">
                <h2 class="section-heading">Large Number of Features at your Fingertips</h2>
				
                <p>The code editor comes with a large number of features at your disposal. Some features include the ability to quickly load templates like Drupal, WordPress, Joomla and 50+ others, as well as Widgets like a Calculator, Color Picker, IP Calculator, and more. And we're constantly adding new and improved features to make your development even better.</p>
				
                <div class="clear"></div>
            </div>
			
            <div class="clear"></div>
        </div>
		
        <div class="arrowsecpointer bottom"></div>
		
        <div class="clear"></div>
    </div>
</section>
<?php /* Features Tag Line: End */ ?>

<?php /* Highlights: Start */ ?>
<section class="sec-highlights">
    <div class="wrapper_highlights">
        <div class="container highlights">
            <div class="container_inside contentoutput">
				
                <ul class="homefeatureslist">
					
                    <?php /* Row 1: Start */ ?>
                    <div>
                        <li class="featureitem odd row1 first">
                            <div class="featureitem_inside">
                                <div class="featureditem_img">
                                    <a href="#" class="featureditem_link">
                                        <i class="fa fa-plus-circle"></i>
                                        <span class="featureditem_cover"></span>
                                        <img class="featureditemimg fimg1" 
                                            src="<?php echo base_url(); ?>media/images/sec_featuredimage_configeditor.png" 
                                            alt="" />
                                    </a>
									
                                    <div class="clear"></div>
                                </div>
								
                                <div class="featureitem_desc">
                                    <h3>File Versioning</h3>
									
                                    <p>Duis at ultricies urna. Ut quis nisi sed leo consectetur dapibus. Etiam mauris ante, scelerisque nec ante eget, fermentum tristique risus.</p>
									
                                    <div class="clear"></div>
                                </div>
								
                                <div class="clear"></div>
                            </div>
                        </li>
                        <li class="featureitem even row1">
                            <div class="featureitem_inside">
                                <div class="featureditem_img">
                                    <a href="#" class="featureditem_link">
                                        <i class="fa fa-plus-circle"></i>
                                        <span class="featureditem_cover"></span>
                                        <img class="featureditemimg fimg2" 
                                            src="<?php echo base_url(); ?>media/images/sec_featuredimage_databasemanager.png" 
                                            alt="" />
                                    </a>
									
                                    <div class="clear"></div>
                                </div>
								
                                <div class="featureitem_desc">
                                    <h3>Widgets</h3>
									
                                    <p>Vestibulum in nunc dui. Aenean accumsan risus a nunc accumsan tempor. Nam id gravida lectus. Vivamus sem ex, tristique quis velit vitae, elementum pulvinar tellus.</p>
									
                                    <div class="clear"></div>
                                </div>
								
                                <div class="clear"></div>
                            </div>
                        </li>
                        <li class="featureitem odd row1 last">
                            <div class="featureitem_inside">
                                <div class="featureditem_img">
                                    <a href="#" class="featureditem_link">
                                        <i class="fa fa-plus-circle"></i>
                                        <span class="featureditem_cover"></span>
                                        <img class="featureditemimg fimg3" 
                                            src="<?php echo base_url(); ?>media/images/sec_featuredimage_filemanager.png" 
                                            alt="" />
                                    </a>
									
                                    <div class="clear"></div>
                                </div>
								
                                <div class="featureitem_desc">
                                    <h3>Access from Anywhere</h3>
									
                                    <p>Nulla convallis turpis a neque commodo, at sagittis augue finibus. Nunc vestibulum tincidunt nibh in tristique. Proin venenatis erat porta arcu varius, eget bibendum dolor cursus.</p>
									
                                    <div class="clear"></div>
                                </div>
								
                                <div class="clear"></div>
                            </div>
                        </li>
						
                        <div class="clear"></div>
                    </div>
                    <?php /* Row 1: End */ ?>
					
                    <?php /* Row 2: Start */ ?>
                    <div>
                        <li class="featureitem odd row2 first">
                            <div class="featureitem_inside">
                                <div class="featureditem_img">
                                    <a href="#" class="featureditem_link">
                                        <i class="fa fa-plus-circle"></i>
                                        <span class="featureditem_cover"></span>
                                        <img class="featureditemimg fimg4" 
                                            src="<?php echo base_url(); ?>media/images/sec_featuredimage_ftpmanager.png" 
                                            alt="" />
                                    </a>
									
                                    <div class="clear"></div>
                                </div>
								
                                <div class="featureitem_desc">
                                    <h3>Databases within Account</h3>
									
                                    <p>Nam magna urna, gravida at purus nec, ultricies volutpat lectus. Donec venenatis nulla sed consectetur vulputate. Nunc vehicula, risus at consequat maximus, libero est porta ligula, eu fermentum dui neque vel massa.</p>
									
                                    <div class="clear"></div>
                                </div>
								
                                <div class="clear"></div>
                            </div>
                        </li>
                        <li class="featureitem even row2">
                            <div class="featureitem_inside">
                                <div class="featureditem_img">
                                    <a href="#" class="featureditem_link">
                                        <i class="fa fa-plus-circle"></i>
                                        <span class="featureditem_cover"></span>
                                        <img class="featureditemimg fimg5" 
                                            src="<?php echo base_url(); ?>media/images/sec_featuredimage_templatemanager.png" 
                                            alt="" />
                                    </a>
									
                                    <div class="clear"></div>
                                </div>
								
                                <div class="featureitem_desc">
                                    <h3>Work from Any Computer</h3>
									
                                    <p>Cras vitae eros non orci mattis dignissim. Nam ac nunc purus. Etiam eu sem in tellus congue pulvinar. Etiam nec turpis ut orci lobortis imperdiet. Donec ut justo orci.</p>
									
                                    <div class="clear"></div>
                                </div>
								
                                <div class="clear"></div>
                            </div>
                        </li>
                        <li class="featureitem odd row2 last">
                            <div class="featureitem_inside">
                                <div class="featureditem_img">
                                    <a href="#" class="featureditem_link">
                                        <i class="fa fa-plus-circle"></i>
                                        <span class="featureditem_cover"></span>
                                        <img class="featureditemimg fimg6" 
                                            src="<?php echo base_url(); ?>media/images/sec_featuredimage_workspacemanager.png" 
                                            alt="" />
                                    </a>
									
                                    <div class="clear"></div>
                                </div>
								
                                <div class="featureitem_desc">
                                    <h3>Import and Export Your Work</h3>
									
                                    <p>Nunc nec libero augue. Morbi sodales gravida leo, nec rhoncus sapien scelerisque sed. Vestibulum non erat ac nisl facilisis auctor. Quisque non dolor in diam viverra aliquet at quis leo.</p>
									
                                    <div class="clear"></div>
                                </div>
								
                                <div class="clear"></div>
                            </div>
                        </li>
						
                        <div class="clear"></div>
                    </div>
                    <?php /* Row 2: End */ ?>
					
                    <?php /* Row 3: Start */ ?>
                    <div>
                        <li class="featureitem odd row3 first">
                            <div class="featureitem_inside">
                                <div class="featureditem_img">
                                    <a href="#" class="featureditem_link">
                                        <i class="fa fa-plus-circle"></i>
                                        <span class="featureditem_cover"></span>
                                        <img class="featureditemimg fimg7" 
                                            src="<?php echo base_url(); ?>media/images/sec_featuredimage_keybindings.png" 
                                            alt="" />
                                    </a>
									
                                    <div class="clear"></div>
                                </div>
								
                                <div class="featureitem_desc">
                                    <h3>Easily Transfer Files</h3>
									
                                    <p>Praesent gravida auctor tempor. Etiam interdum consectetur erat, sit amet hendrerit massa cursus sit amet. Morbi condimentum, nisi quis maximus rhoncus, est justo elementum massa, et cursus felis mi at dolor.</p>
									
                                    <div class="clear"></div>
                                </div>
								
                                <div class="clear"></div>
                            </div>
                        </li>
                        <li class="featureitem even row3">
                            <div class="featureitem_inside">
                                <div class="featureditem_img">
                                    <a href="#" class="featureditem_link">
                                        <i class="fa fa-plus-circle"></i>
                                        <span class="featureditem_cover"></span>
                                        <img class="featureditemimg fimg8" 
                                            src="<?php echo base_url(); ?>media/images/sec_featuredimage_phpmyadmin.png" 
                                            alt="" />
                                    </a>
									
                                    <div class="clear"></div>
                                </div>
								
                                <div class="featureitem_desc">
                                    <h3>FTP Connect to Any Server</h3>
									
                                    <p>Proin ut lorem libero. Proin quam leo, volutpat non sagittis ac, rhoncus ut est. In hac habitasse platea dictumst. In vehicula rutrum interdum.</p>
									
                                    <div class="clear"></div>
                                </div>
								
                                <div class="clear"></div>
                            </div>
                        </li>
                        <li class="featureitem odd row3 last">
                            <div class="featureitem_inside">
                                <div class="featureditem_img">
                                    <a href="#" class="featureditem_link">
                                        <i class="fa fa-plus-circle"></i>
                                        <span class="featureditem_cover"></span>
                                        <img class="featureditemimg fimg9" 
                                            src="<?php echo base_url(); ?>media/images/sec_featuredimage_codeeditor.png" 
                                            alt="" />
                                    </a>
									
                                    <div class="clear"></div>
                                </div>
								
                                <div class="featureitem_desc">
                                    <h3>FTP Connect to Any Server</h3>
									
                                    <p>Proin ut lorem libero. Proin quam leo, volutpat non sagittis ac, rhoncus ut est. In hac habitasse platea dictumst. In vehicula rutrum interdum.</p>
									
                                    <div class="clear"></div>
                                </div>
								
                                <div class="clear"></div>
                            </div>
                        </li>
						
                        <div class="clear"></div>
                    </div>
                    <?php /* Row 3: End */ ?>
					
                </ul>
				
                <div class="clear"></div>
            </div>
			
            <div class="clear"></div>
        </div>
		
        <div class="clear"></div>
    </div>
</section>
<?php /* Highlights: End */ ?>

<?php /* Application Bullet Points: Start */ ?>
<section class="sec-appbulletpoints">
    <div class="wrapper_appbulletpoints">
        <div class="container appbulletpoints">
            <div class="container_inside contentoutput">
				
				<h2 class="section-heading">Created with your development in mind</h2>
				
				<?php /* Bullet Points List: Start */ ?>
				<div class="appbulletpointslist">
					
					<?php /* Column 1: Start */ ?>
					<div class="appbulletlistitem odd first">
						
						<h3>For your convenience</h3>
						
						<ul>
							<li>Quick Registration</li>
							<li>Social Login/Registration</li>
							<li>Simple Membership System</li>
							<li>No obligation. Cancel at any time</li>
							<li>Accessible with any modern browser</li>
							<li>Secure and reliable</li>
							<li>Lightweight</li>
							<li>Everything stored in the cloud</li>
							<li>Familiar interface</li>
							<li>Perfect for any project</li>
						</ul>
						
						<div class="clear"></div>
					</div>
					<?php /* Column 1: End */ ?>
					
					<?php /* Column 2: Start */ ?>
					<div class="appbulletlistitem even">
						
						<h3>General features</h3>
						
						<ul>
							<li>Syntax highlighting for over 110 languages</li>
							<li>Over 20 themes</li>
							<li>Automatic indent and outdent</li>
							<li>Handles huge documents (nearly 4 million lines)</li>
							<li>Highlight matching parentheses</li>
							<li>Toggle between soft tabs and real tabs</li>
							<li>Displays hidden characters</li>
							<li>Line wrapping</li>
							<li>Code folding</li>
							<li>Cut, copy, and paste functionality</li>
						</ul>
						
						<div class="clear"></div>
					</div>
					<?php /* Column 2: End */ ?>
					
					<?php /* Column 3: Start */ ?>
					<div class="appbulletlistitem odd last">
						
						<h3>Awesome code editor</h3>
						
						<ul>
							<li>Up to 9 workspaces</li>
							<li>FTP connections</li>
							<li>Can create up to 9 databases</li>
							<li>Use phpMyAdmin for databases</li>
							<li>Upload/Download to and from desktop</li>
							<li>Template Manager for creating projects</li>
							<li>Preview projects in browser</li>
							<li>Create files as you need them</li>
							<li>Configure editor to your liking</li>
							<li>New features constantly being added</li>
						</ul>
						
						<div class="clear"></div>
					</div>
					<?php /* Column 3: End */ ?>
					
					<div class="clear"></div>
				</div>
				<?php /* Bullet Points List: End */ ?>
				
                <div class="clear"></div>
            </div>
			
            <div class="clear"></div>
        </div>
		
        <div class="clear"></div>
    </div>
</section>
<?php /* Application Bullet Points: End */ ?>

<?php /* Sign Up Now: Start */ ?>
<section class="sec-signupnow">
    <div class="wrapper_signupnow">
        <div class="container signupnow">
            <div class="container_inside contentoutput aligncenter">
				
				<?php if (!isset($account) || !$account) { ?>
            	<h2>Convinced yet?</h2>

            	<p>Monocode is feature-rich, with new tools being added frequently. Click below to get started and try out Monocode for yourself.</p>

            	<a href="<?php echo base_url();?>guest/register" class="button teal bttn25">Sign Up Now</a>
            	<?php }else{ ?>
            	<h2>Do you need to add more features to your account?</h2>

            	<p>Do you need to more databases, workspaces, FTP accounts and more? Click below to upgrade your account.</p>

            	<a href="<?php echo base_url();?>account/membership" class="button teal">Upgrade your account to add more features</a>
            	<?php } ?>

                <div class="clear"></div>
            </div>
			
            <div class="clear"></div>
        </div>
		
        <div class="clear"></div>
    </div>
</section>
<?php /* Sign Up Now: End */ ?>
