<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
	<title>Template Manager</title>
	
	<link href="<?php echo base_url(); ?>core/css/popupcontent.css" rel="stylesheet" media="all" />
	<link href="<?php echo base_url(); ?>core/css/fontawesome/font-awesome.css" rel="stylesheet" media="all" />
	<link href="<?php echo base_url(); ?>core/alert/css/alert.min.css" rel="stylesheet" />
	<link href="<?php echo base_url(); ?>core/alert/themes/<?php echo get_option("editor_page_theme") ?>/theme.css" rel="stylesheet" />
	<link href="<?php echo base_url(); ?>themes/editor/<?php echo get_option("editor_page_theme") ?>/theme.css" rel="stylesheet" data-default-theme="<?php echo get_option("editor_page_theme") ?>" media="all" />
	
	<script src="<?php echo base_url(); ?>core/js/jquery-1.10.2.min.js" type="text/javascript"></script>
	<script src="<?php echo base_url(); ?>core/alert/js/alert.min.js"></script>
	
	<script type="text/javascript">
	$(document).ready(function(e) {
		$('div.templateitem_holder').click(function() {
			var obj = $(this);
			
			if (obj.hasClass("disabled")) {
				return;
			}
			
			if (obj.hasClass("selected")) {
				obj.removeClass("selected");
				
				return;
			}
			
			$("#template-container div.templateitem_holder").removeClass("selected");
			obj.addClass("selected");
			obj = null;
		});
		
		// Accordion Initial Load
		$('li.accbttn.General').addClass('active');
		$('div.accordionitem.General').addClass('active');
		
		// Accordion Navigation Items
		$('li.accbttn.General'	 ).on('click', function(){ accordionNav('General') });
		$('li.accbttn.Platforms' ).on('click', function(){ accordionNav('Platforms') });
		$('li.accbttn.Frameworks').on('click', function(){ accordionNav('Frameworks') });
		$('li.accbttn.Engines'	 ).on('click', function(){ accordionNav('Engines') });
	});
	
	function close() {
		$("input").each(function() {
			parent.configInput($(this).attr('data-key'), $(this).attr('data-value'));
		});
		$("select").each(function() {
			parent.configSelect($(this).attr('data-key'), $(this).attr('data-value'));
		});
		
		parent.scpopupclose();
	}
	
	function backclose() {
		$("input").each(function() {
			parent.configInput($(this).attr('data-key'), $(this).attr('data-value'));
		});
		$("select").each(function() {
			parent.configSelect($(this).attr('data-key'), $(this).attr('data-value'));
		});
	}
	
	function create() {
		var template = $("#template-container div.templateitem_holder.selected");
		
		if (template[0] == null) {
			
			if($('body').hasClass('templatesdisabled')){
				$.alert.open({type: 'alert', content: 'You aren\'t allowed to use templates. Please upgrade<br/>your account to enable templates.'});
			}else{
				$.alert.open({type: 'alert', content: 'Please select a template.'});
			}
			
			return;
		}
		parent.sceditor.call("base.template.openSaveWnd()", template.data("template-id"));
	}
	
	// Accordion Function
	function accordionNav(bttnClassName){
		$('div.accordionitem, li.accbttn').removeClass('active');
		$('div.accordionitem.' + bttnClassName).addClass('active');
		$('li.accbttn.' + bttnClassName).addClass('active');
	}
	</script>
	
	<style>
	html, body {
		height: 100%;
		}
		body {
			margin: 0;
			padding: 0;
			
			-webkit-box-sizing: border-box;
			-moz-box-sizing: 	border-box;
			box-sizing: 		border-box;
			}
	
	* {
		-webkit-box-sizing: border-box;
		-moz-box-sizing: 	border-box;
		box-sizing: 		border-box;
		}
	</style>
</head>
<body data-width='100%' data-height='100%' data-controls="{'Create From Template':'create','Close':'close'}" <?php if (!$allowed) { ?>class="templatesdisabled"<?php } ?>>
	<div class="infopopup" style="height: 100%;">
		<div class="infooptionscontainer" style="padding-right: 0; height: 100%; overflow: hidden;"> <!-- -->
			
			<?php /* Tab Header: Start */ ?>
			<div class="tabsectionheader">
				<div class="tabsectionheader_inside">
					
					<div class="tabsectionheadercol twocol left">
						<div class="tabsectionheadercol_inside">
							<i class="fa fa-caret-down"></i>Sections
							
							<div class="clear"></div>
						</div>
						
						<div class="clear"></div>
					</div>
					
					<div class="tabsectionheadercol twocol right">
						<div class="tabsectionheadercol_inside">
							<i class="fa fa-caret-down"></i>File/Package and Description
							
							<div class="clear"></div>
						</div>
						
						<div class="clear"></div>
					</div>
					
					<div class="clear"></div>
				</div>
				
				<div class="tabsectionheadersep"><span></span></div>
				
				<div class="clear"></div>
			</div>
			<?php /* Tab Header: End */ ?>
			
			<!--
			<?php if (!$allowed) { ?>
			<div class="infooptionscontainer">
				<div class="templatenotices error">
					You are not allowed to use templates. You may upgrade your account by clicking <a target="parent" href='<?php echo base_url(); ?>membership'>here</a>.
				</div>
				
				<div class="clear"></div>
			</div>
			<?php } ?>
			-->
			
			<?php /* Templates List: Start */ ?>
			<div class="infooptionscontainer template_container accordion sidebaracc" id="template-container"> <!-- -->
				
				<?php /* Templates: Start */ ?>
				<div class="accordion_inside">
					
					<?php /* Section Column: Start */ ?>
					<div class="accordionnav">
						<div class="accordionnav_inside">
							<ul class="accordionnavlist">
								<?php
								if (sizeof($types) > 0) {
									foreach ($types as $type) {
										?>
										<li class="accbttn <?php echo $type->template_type_name; ?>">
											<div><?php echo $type->template_type_name; ?> <i class="fa fa-caret-right"></i></div>
										</li>
										<?php
									}
								}
								?>
							</ul>
							
							<div class="clear"></div>
						</div>
						
						<div class="clear"></div>
					</div>
					<?php /* Section Column: End */ ?>
					
					<?php /* File/Package Column: Start */ ?>
					<div class="accordionsections">
						<div class="accordionsections_inside">
							
							<?php
							if (sizeof($types) > 0) {
								foreach ($types as $type) {
								?>
								
								<?php /* Template Section: Start */ ?>
								<div class="accordionitem templatesecwrapper <?php echo $type->template_type_name; ?>" style="padding: 0;">
									
									<?php
									if (sizeof($templates) > 0) {
										foreach ($templates as $template) {
											if ($template->type_id != $type->template_type_id) {
												continue;
											}
											?>
											
											<?php /* Individual Template Item: Start */ ?>
											<div class="templateitem_holder templateitem<?php echo $template->template_id; ?> <?php echo (!$allowed || !in_array($template->template_id, $available_templates) ? 'disabled' : 'enabled'); ?>" data-template-id="<?php echo $template->template_id; ?>">
												<div class="templateitem_inside">
													
													<?php /* Template Item Image: Start */ ?>
													<div class="templateitem_img">
														<img src="<?php echo base_url($template->thumbnail_url); ?>" alt="" class="" />
														
														<div class="clear"></div>
													</div>
													<?php /* Template Item Image: End */ ?>
													
													<?php /* Template Item Title and Description: Start */ ?>
													<div class="templateitem_info">
													
														<?php /* Template Item Title: Start */ ?>
														<div class="templateitem_title">
															<div><?php echo '<strong>' . $template->title . '</strong> ' . $template->version; ?></div>
															
															<div class="clear"></div>
														</div>
														<?php /* Template Item Title: End */ ?>
														
														<?php /* Template Item Description Text: Start */ ?>
														<div class="templateitem_descriptiontxt">
															<div><?php echo $template->description; ?></div>
															
															<div class="clear"></div>
														</div>
														<?php /* Template Item Description Text: End */ ?>
														
														<div class="clear"></div>
													</div>
													<?php /* Template Item Title and Description: End */ ?>
													
													<?php if (!$allowed || !in_array($template->template_id, $available_templates)) { ?>
													<?php /* Template Item Disabled Icon: Start */ ?>
													<div class="templateitem_disabledholder">
														<div class="templateitem_disabledinside">
															<div class="templateitem_disabled_icon">
																<i class="fa fa-lock"></i>
															</div>
															<?php /* Template Item Disabled Icon: End */ ?>
															
															<?php /* Template Item Disabled Info: Start */ ?>
															<div class="templateitem_disabled_info">
																<div>
																	Please upgrade your plan if you want to use this template.<br/>Click <a target="parent" href='<?php echo base_url(); ?>membership'>here</a> to upgrade now.
																</div>
															</div>
														</div>
													</div>
													<?php /* Template Item Disabled Info: End */ ?>
													<?php } ?>
													
													<div class="clear"></div>
												</div>
												
												<div class="clear"></div>
											</div>
											<?php /* Individual Template Item: End */ ?>
											
											<?php
										}
									}
									?>
									
									<div class="clear"></div>
								</div>
								<?php /* Template Section: End */ ?>
									
								<?php
								}
							}
							?>
							
							<div class="clear"></div>
						</div>
						
						<div class="clear"></div>
					</div>
					<?php /* File/Package Column: End */ ?>
					
					<div class="clear"></div>
				</div>
				<?php /* Templates: End */ ?>
				
			</div>
			<?php /* Templates List: Start */ ?>
			
			<div class="clear"></div>
		</div>
		
		<div class="clear"></div>
	</div>
</body>
</html>