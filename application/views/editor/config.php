<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
	<title>Configuration</title>
	
	<script type="text/javascript">
		$(document).ready(function (e) {
			$('body').css({'overflow': 'hidden'}).addClass('config');
			
			$("input").keyup(function () {
				parent.configInput($(this).attr('data-key'), $(this).val());
			})
			
			$("select").change(function () {
				parent.configSelect($(this).attr('data-key'), $(this).val());
			})
			
			$(window).bind('keydown', function (event) {
				if (event.ctrlKey && event.which == 122) {
					parent.sceditor.call(" base.fullScreen()", {});
				}
			});
			
			<?php /* Accordion Navigation Items */ ?>
			$('li.accbttn.editorfunctionality').on('click', function () { accordionNav('editorfunctionality') });
			$('li.accbttn.javascript').on('click', function () 			{ accordionNav('javascript') });
			$('li.accbttn.appearance').on('click', function () 			{ accordionNav('appearance') });
			<?php /*
			$('li.accbttn.versioncontrol').on('click', function () 		{ accordionNav('versioncontrol') });
			*/ ?>
			$('li.accbttn.systemsettings').on('click', function () 		{ accordionNav('systemsettings') });
			$('li.accbttn.toolbarsettings').on('click', function () 	{ accordionNav('toolbarsettings') });
			$('li.accbttn.databasesettings').on('click', function () 	{ accordionNav('databasesettings') });
			$('li.accbttn.searchsettings').on('click', function () 		{ accordionNav('searchsettings') });
			$('li.accbttn.terminalsettings').on('click', function () 	{ accordionNav('terminalsettings') });
			$('li.accbttn.subversiongit').on('click', function () 		{ accordionNav('subversiongit') });
			$('li.accbttn.managewidgets').on('click', function () 		{ accordionNav('managewidgets') });
			
			<?php /* Load Widgets Info Function */ ?>
			widgetsInfo();
		});
		
		function cancelled() {
			$("input").each(function () {
				parent.configInput($(this).attr('data-key'), $(this).attr('data-value'));
			});
			
			$("select").each(function () {
				parent.configSelect($(this).attr('data-key'), $(this).attr('data-value'));
				
				if ($(this).attr('data-key') == 'editor_page_theme') {
					parent.configSelect('cancle' + $(this).attr('data-key'), $(this).attr('data-value'));
				}
			});
			
			parent.scpopupclose();
		}
		
		function backclose() {
			$("input").each(function () {
				parent.configInput($(this).attr('data-key'), $(this).attr('data-value'));
			});
			
			$("select").each(function () {
				parent.configSelect($(this).attr('data-key'), $(this).attr('data-value'));
			});
		}
		
		function function_save() {
			$("#config").submit();
		}
		
		<?php /* Accordion Function */ ?>
		function accordionNav(bttnClassName) {
			$('div.accordionitem, li.accbttn').removeClass('active');
			$('div.accordionitem.' + bttnClassName).addClass('active');
			$('li.accbttn.' + bttnClassName).addClass('active');
		}
		
		<?php /* Editor Widgets Info Function */ ?>
		function widgetsInfo() {
			
			<?php /* Show Click Button on Mouse Enter */ ?>
			$('li.widgetitem').mouseenter(function () {
				$(this).find('div.widgetitemdesc_click').hide().stop(true, true).fadeIn();
				$(this).find('div.widgetitemdesc_click').on('click', function () {
					$(this).find('div.widgetitemdesc_info').fadeIn().addClass('active').css({display: 'block'});
				});
			});
			
			<?php /* Hide Click Button (and Info if Open) on Mouse Leave */ ?>
			$('li.widgetitem').mouseleave(function () {
				$(this).find('div.widgetitemdesc_click').show().stop(true, true).fadeOut();
				$('li.widgetitem div.widgetitemdesc_info').fadeOut().removeClass('active');
			});
			
			<?php /* Show Info on Click */ ?>
			$('li.widgetitem').find('div.widgetitemdesc_click').on('click', function () {
				$('li.widgetitem div.widgetitemdesc_info').fadeOut().removeClass('active');
				$(this).parent().find('div.widgetitemdesc_info').delay(150).fadeIn().addClass('active');
			});
			
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
<body data-width='100%' data-height='100%' data-controls="{'Save Configuration':'function_save','Close':'close'}">
    <div class="infopopup" style="height: 100%;">
        <form id="config" action="" method="post" class="" style="height: 100%;">
            <div class="infooptionscontainer" style="padding-right: 0; height: 100%; overflow: hidden;"> <!-- -->

                <?php /* Tab Header: Start */ ?>
                <div class="tabsectionheader">
                    <div class="tabsectionheader_inside">

                        <div class="tabsectionheadercol twocol left">
                            <div class="tabsectionheadercol_inside">
                                <i class="fa fa-caret-down"></i>Configuration Navigation

                                <div class="clear"></div>
                            </div>

                            <div class="clear"></div>
                        </div>

                        <div class="tabsectionheadercol twocol right">
                            <div class="tabsectionheadercol_inside">
                                <i class="fa fa-caret-down"></i>Configuration Features

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
				
                <?php /* Configuration Wrapper: Start */ ?>
                <div class="accordion sidebaracc">
                    <div class="accordion_inside">
						
                        <?php /* Configuration Navigation: Start */ ?>
                        <div class="accordionnav">
                            <div class="accordionnav_inside">
                                <ul class="accordionnavlist">
                                    <li class="accbttn editorfunctionality active">
                                        <div>Editor Functionality <i class="fa fa-caret-right"></i></div>
                                    </li>
                                    <li class="accbttn appearance">
                                        <div>Appearance <i class="fa fa-caret-right"></i></div>
                                    </li>
									<?php /*
                                    <li class="accbttn versioncontrol">
                                        <div>Version Control <i class="fa fa-caret-right"></i></div>
                                    </li>
									*/ ?>
                                    <li class="accbttn systemsettings">
                                        <div>System Settings <i class="fa fa-caret-right"></i></div>
                                    </li>
                                    <li class="accbttn javascript">
                                        <div>Javascript <i class="fa fa-caret-right"></i></div>
                                    </li>
                                    <li class="accbttn toolbarsettings">
                                        <div>Toolbar Settings <i class="fa fa-caret-right"></i></div>
                                    </li>
                                    <li class="accbttn databasesettings">
                                        <div>Database Settings <i class="fa fa-caret-right"></i></div>
                                    </li>
                                    <li class="accbttn searchsettings">
                                        <div>Search Settings <i class="fa fa-caret-right"></i></div>
                                    </li>
                                    <li class="accbttn terminalsettings">
                                        <div>Terminal Settings <i class="fa fa-caret-right"></i></div>
                                    </li>
                                    <li class="accbttn subversiongit">
                                        <div>Subversion/GIT <i class="fa fa-caret-right"></i></div>
                                    </li>
                                    <li class="accbttn managewidgets">
                                        <div>Manage Widgets <i class="fa fa-caret-right"></i></div>
                                    </li>
                                </ul>
								
                                <div class="clear"></div>
                            </div>
							
                            <div class="clear"></div>
                        </div>
                        <?php /* Configuration Navigation: End */ ?>
						
                        <?php /* Configuration Sections: Start */ ?>
                        <div class="accordionsections">
                            <div class="accordionsections_inside">
								
                                <?php /* Editor Functionality: Start */ ?>
                                <div class="accordionitem editorfunctionality active">
                                    <div class="infopopuptitle">
                                        <span>Editor Functionality</span>
                                    </div>
									
                                    <div class="infopopupsubsectitle">
                                        <span>Editor Options</span>
                                    </div>
									
                                    <div class="infopopupoptions">
                                        
										<?php /* Syntax Mode: Start */ ?>
										<div class="infopopupoptrow first">
                                            <label>Syntax Mode</label>
                                            <div class="infopopupfield">
                                                <?php echo get_editor_syntax(get_option('editor_default_mode')); ?>
                                                <div class="fieldinfo">
													This option sets the default code syntax mode for the editor.
												</div>

                                                <div class="clear"></div>
                                            </div>

                                            <div class="clear"></div>
                                        </div>
										<?php /* Syntax Mode: End */ ?>
										
										<?php /* Font Size: Start */ ?>
                                        <div class="infopopupoptrow">
                                            <label>Font Size</label>
                                            <div class="infopopupfield">
                                                <?php echo get_font_size(get_option('font_size')); ?>
                                                <div class="fieldinfo">
													Changes the font size in the code editor.
												</div>

                                                <div class="clear"></div>
                                            </div>

                                            <div class="clear"></div>
                                        </div>
										<?php /* Font Size: End */ ?>
										
										<?php /* Tab Spaces: Start */ ?>
                                        <div class="infopopupoptrow">
                                            <label>Tab Spaces</label>
                                            <div class="infopopupfield">
                                                <input type="text" id="editor_tab_spaces" data-key='setTabSize' class="text" value="<?php echo get_option('editor_tab_spaces'); ?>" name="editor_tab_spaces" />
                                                <div class="fieldinfo">
													Number of spaces for a single tab.
												</div>

                                                <div class="clear"></div>
                                            </div>

                                            <div class="clear"></div>
                                        </div>
										<?php /* Tab Spaces: End */ ?>
										
										<?php /* Show Print Margin: Start */ ?>
                                        <div class="infopopupoptrow">
                                            <label>Show Print Margin</label>
                                            <div class="infopopupfield">
                                                <?php echo get_print_margin(get_option('show_print_margin')); ?>
                                                <div class="fieldinfo">
													Display the print margin if this option is enabled.
												</div>

                                                <div class="clear"></div>
                                            </div>

                                            <div class="clear"></div>
                                        </div>
										<?php /* Show Print Margin: End */ ?>
										
										<?php /* Make Editor Read Only: Start */ ?>
                                        <div class="infopopupoptrow">
                                            <label>Make Editor Read Only</label>
                                            <div class="infopopupfield">
                                                <?php echo get_red_only(get_option('editor_read_only')); ?>
                                                <div class="fieldinfo">
													All code editor tabs will be read-only if this open is set to true.
												</div>

                                                <div class="clear"></div>
                                            </div>

                                            <div class="clear"></div>
                                        </div>
										<?php /* Make Editor Read Only: End */ ?>
										
										<?php /* Code Folding: Start */ ?>
                                        <div class="infopopupoptrow">
                                            <label>Code Folding</label>
                                            <div class="infopopupfield">
                                                <?php echo get_code_folding(get_option('editor_code_folding')); ?>
                                                <div class="fieldinfo">
													Positions the code folding handle.
												</div>

                                                <div class="clear"></div>
                                            </div>

                                            <div class="clear"></div>
                                        </div>
										<?php /* Code Folding: End */ ?>
										
										<?php /* Key Binding: Start */ ?>
                                        <div class="infopopupoptrow">
                                            <label>Key Binding</label>
                                            <div class="infopopupfield">
                                                <?php echo get_editor_key_binding(get_option('editor_key_binding')); ?>
                                                <div class="fieldinfo">
													Set the key binding to Ace, Vim, or Emacs.
												</div>

                                                <div class="clear"></div>
                                            </div>

                                            <div class="clear"></div>
                                        </div>
										<?php /* Key Binding: End */ ?>
										
										<?php /* Soft Wrap: Start */ ?>
                                        <div class="infopopupoptrow">
                                            <label>Soft Wrap</label>
                                            <div class="infopopupfield">
                                                <?php echo get_editor_soft_wrap(get_option('editor_soft_wrap')); ?>
                                                <div class="fieldinfo">
													Soft wrap is the break resulting from line wrap or word wrap. You can choose to turn this option off, set it to 40 or 80 characters, or set to free. You must save your change before being available.
												</div>

                                                <div class="clear"></div>
                                            </div>

                                            <div class="clear"></div>
                                        </div>
										<?php /* Soft Wrap: End */ ?>
										
										<?php /* Full Line Selection: Start */ ?>
                                        <div class="infopopupoptrow">
                                            <label>Full Line Selection</label>
                                            <div class="infopopupfield">
                                                <?php echo get_editor_selection_style(get_option('editor_selection_style')); ?>
                                                <div class="fieldinfo">
													Select all code on the current line.
												</div>

                                                <div class="clear"></div>
                                            </div>

                                            <div class="clear"></div>
                                        </div>
										<?php /* Full Line Selection: End */ ?>
										
										<?php /* Highlight Active Line: Start */ ?>
                                        <div class="infopopupoptrow">
                                            <label>Highlight Active Line</label>
                                            <div class="infopopupfield">
                                                <?php echo get_highlight_line(get_option('editor_highlight_line')); ?>
                                                <div class="fieldinfo">
													Highlight the current line where the cursor's positioned.
												</div>

                                                <div class="clear"></div>
                                            </div>

                                            <div class="clear"></div>
                                        </div>
										<?php /* Highlight Active Line: End */ ?>
										
										<?php /* Show Invisibles: Start */ ?>
                                        <div class="infopopupoptrow">
                                            <label>Show Invisibles</label>
                                            <div class="infopopupfield">
                                                <?php echo get_show_invisibles(get_option('editor_show_invisibles')); ?>
                                                <div class="fieldinfo">
													Displays invisible characters.
												</div>

                                                <div class="clear"></div>
                                            </div>

                                            <div class="clear"></div>
                                        </div>
										<?php /* Show Invisibles: End */ ?>
										
										<?php /* Show Indent Guides: Start */ ?>
                                        <div class="infopopupoptrow">
                                            <label>Show Indent Guides</label>
                                            <div class="infopopupfield">
                                                <?php echo get_show_indent_guides(get_option('editor_show_indent_guides')); ?>
                                                <div class="fieldinfo">
													Visually display indent levels.
												</div>

                                                <div class="clear"></div>
                                            </div>

                                            <div class="clear"></div>
                                        </div>
										<?php /* Show Indent Guides: End */ ?>
										
										<?php /* Animate Scroll: Start */ ?>
                                        <div class="infopopupoptrow">
                                            <label>Animate Scroll</label>
                                            <div class="infopopupfield">
                                                <?php echo get_editor_animated_scroll(get_option('editor_animated_scroll')); ?>
                                                <div class="fieldinfo">
													Provides scrolling through content using an animated scroll effect.
												</div>

                                                <div class="clear"></div>
                                            </div>

                                            <div class="clear"></div>
                                        </div>
										<?php /* Animate Scroll: End */ ?>
										
										<?php /* Show Gutter: Start */ ?>
                                        <div class="infopopupoptrow">
                                            <label>Show Gutter</label>
                                            <div class="infopopupfield">
                                                <?php echo get_show_gutter(get_option('show_gutter')); ?>
                                                <div class="fieldinfo">
													The gutter contains the line number, notices, code folding and more.
												</div>
												
                                                <div class="clear"></div>
                                            </div>
											
                                            <div class="clear"></div>
                                        </div>
										<?php /* Show Gutter: End */ ?>
										
										<?php /* Use Soft Tab: Start */ ?>
                                        <div class="infopopupoptrow">
                                            <label>Use Soft Tab</label>
                                            <div class="infopopupfield">
                                                <?php echo get_editor_use_soft_tab(get_option('use_soft_tab')); ?>
                                                <div class="fieldinfo">
													Soft tabs are tabs made up of single character spaces.
												</div>
												
                                                <div class="clear"></div>
                                            </div>
											
                                            <div class="clear"></div>
                                        </div>
										<?php /* Use Soft Tab: End */ ?>
										
										<?php /* Highlight Selected Word: Start */ ?>
                                        <div class="infopopupoptrow">
                                            <label>Highlight Selected Word</label>
                                            <div class="infopopupfield">
                                                <?php echo get_editor_highlight_selected_word(get_option('highlight_selected_word')); ?>
                                                <div class="fieldinfo">
													Highlight the current string.
												</div>
												
                                                <div class="clear"></div>
                                            </div>
											
                                            <div class="clear"></div>
                                        </div>
										<?php /* Highlight Selected Word: End */ ?>
										
										<?php /* Enable Behaviors: Start */ ?>
                                        <div class="infopopupoptrow">
                                            <label>Enable Behaviors</label>
                                            <div class="infopopupfield">
                                                <?php echo get_enable_behaviors(get_option('editor_enable_behaviors')); ?>
                                                <div class="fieldinfo">
													"Behaviors" in this case is the auto-pairing of special characters, like quotation marks, parenthesis, or brackets.
												</div>
												
                                                <div class="clear"></div>
                                            </div>
											
                                            <div class="clear"></div>
                                        </div>
										<?php /* Enable Behaviors: End */ ?>
										
										<?php /* Fade Fold Widgets: Start */ ?>
                                        <div class="infopopupoptrow">
                                            <label>Fade Fold Widgets</label>
                                            <div class="infopopupfield">
                                                <?php echo get_editor_fade_fold_widgets(get_option('fade_fold_widgets')); ?>
												<div class="fieldinfo">
													This option fades out and hides the fold handles when not in use. When hovering over the gutter, the fold handles fade back in for use.
												</div>
												
                                                <div class="clear"></div>
                                            </div>
											
                                            <div class="clear"></div>
                                        </div>
										<?php /* Fade Fold Widgets: End */ ?>
										
										<?php /* Elastic Tabstops: Start */ ?>
                                        <div class="infopopupoptrow">
                                            <label>Elastic Tabstops</label>
                                            <div class="infopopupfield">
                                                <?php echo get_elastic_tabstops(get_option('elastic_tabstops')); ?>
												<div class="fieldinfo">
													Move tabstops to fit the text between them and align them with matching tabstops on adjacent lines.
												</div>
												
                                                <div class="clear"></div>
                                            </div>
											
                                            <div class="clear"></div>
                                        </div>
										<?php /* Elastic Tabstops: End */ ?>
										
										<?php /* Incremental Search: Start */ ?>
                                        <?php /*
                                        <div class="infopopupoptrow">
											<label>Incremental Search</label>
											<div class="infopopupfield">
												<select class="select">
													<option>true</option>
													<option>false</option>
												</select>
												
												<div class="clear"></div>
											</div>
											
											<div class="clear"></div>
                                        </div>
                                        */ ?>
										<?php /* Incremental Search: End */ ?>
										
										<?php /* Show Token Information: Start */ ?>
                                        <div class="infopopupoptrow">
                                            <label>Show Token Info</label>
                                            <div class="infopopupfield">
                                                <?php echo get_show_token_info(get_option('show_token_info')); ?>
												<div class="fieldinfo">
													Show or hide token information.
												</div>
												
                                                <div class="clear"></div>
                                            </div>
											
                                            <div class="clear"></div>
                                        </div>
										<?php /* Show Token Information: End */ ?>
										
										<?php /* Scroll Past End: Start */ ?>
                                        <div class="infopopupoptrow">
                                            <label>Scroll Past End</label>
                                            <div class="infopopupfield">
                                                <?php echo get_editor_scroll_past_end(get_option('scroll_past_end')); ?>
												<div class="fieldinfo">
													Allows scrolling beyond the end of the code in the current file.
												</div>
												
                                                <div class="clear"></div>
                                            </div>
											
                                            <div class="clear"></div>
                                        </div>
										<?php /* Scroll Past End: End */ ?>
										
										<?php /* Enabling Word Wrapping: Start */ ?>
                                        <div class="infopopupoptrow">
                                            <label>Enable Word Wrapping</label>
                                            <div class="infopopupfield">
                                                <?php echo get_word_wrap(get_option('editor_toggle_word_wrapping')); ?>
                                                <div class="fieldinfo">
													Break lines between words rather than within words, when possible.
												</div>
												
                                                <div class="clear"></div>
                                            </div>
											
                                            <div class="clear"></div>
                                        </div>
										<?php /* Enable Word Wrapping: End */ ?>
										
										<?php /* Enable Autocomplete: Start */ ?>
                                        <div class="infopopupoptrow">
                                            <label>Enable Autocomplete</label>
                                            <div class="infopopupfield">
                                                <?php echo get_autocomplete(get_option('get_autocomplete')); ?>
												<div class="fieldinfo">
													A context-aware code completion feature that speeds up the process of coding by reducing typos and other common mistakes.
												</div>
												
                                                <div class="clear"></div>
                                            </div>
											
                                            <div class="clear"></div>
                                        </div>
										<?php /* Enable Autocomplete: End */ ?>
										
										<?php /* Enable Status Bar: Start */ ?>
                                        <div class="infopopupoptrow">
                                            <label>Enable Status Bar</label>
                                            <div class="infopopupfield">
                                                <?php echo get_statusbar(get_option('statusbar')); ?>
                                                <div class="fieldinfo">
													Show or hide the status bar located below the code editor pane.
												</div>
												
                                                <div class="clear"></div>
                                            </div>
											
                                            <div class="clear"></div>
                                        </div>
										<?php /* Enable Status Bar: End */ ?>
										
										<?php /* Enable Tag Bar: Start */ ?>
                                        <div class="infopopupoptrow">
                                            <label>Enable Tag Bar</label>
                                            <div class="infopopupfield">
                                                <?php echo get_tagbar(get_option('tagbar')); ?>
                                                <div class="fieldinfo">
													This option shows the hierarchy of your html structure based on your cursor's current position. The Status Bar and code folding must be turned on in order for this feature to work.
												</div>
												
                                                <div class="clear"></div>
                                            </div>
											
                                            <div class="clear"></div>
                                        </div>
										<?php /* Enable Tag Bar: End */ ?>
										
										<?php /* Split View: Start */ ?>
										<!--
                                        <div class="infopopupoptrow last">
                                            <label>Split View</label>
                                            <div class="infopopupfield">
                                                <?php echo get_split(get_option('split')); ?>
												
                                                <div class="clear"></div>
                                            </div>
											
                                            <div class="clear"></div>
                                        </div>
										-->
										<?php /* Split View: End */ ?>
										
                                        <div class="clear"></div>
                                    </div>
									
                                    <div class="clear"></div>
                                </div>
                                <?php /* Editor Functionality: End */ ?>
								
                                <!-- Javascript: Start -->
                                <div class="accordionitem javascript">
                                    <div class="infopopuptitle">
                                        <span>Javascript</span>
                                    </div>
                                    <div class="infopopupsubsectitle">
                                        <span>JSHint</span>
                                    </div>
                                    <div class="infopopupoptions">
                                        <div class="infopopupoptrow first">
                                            <label>Enable JSHint</label>
                                            <div class="infopopupfield">
                                                <?php echo get_js_jshint_enable(get_option('js_jshint_enable')); ?>
                                                <div class="clear"></div>
                                            </div>

                                            <div class="clear"></div>
                                        </div>
                                        <div class="infopopupoptrow last">
                                            <label>ECMAScript 6</label>
                                            <div class="infopopupfield">
                                                <?php echo get_js_jshint_esnext(get_option('js_jshint_esnext')); ?>

                                                <div class="clear"></div>
                                            </div>

                                            <div class="clear"></div>
                                        </div>

                                        <div class="infopopupoptrow last">
                                            <label>Mozilla JavaScript Extensions</label>
                                            <div class="infopopupfield">
                                                <?php echo get_js_jshint_moz(get_option('js_jshint_moz')); ?>

                                                <div class="clear"></div>
                                            </div>

                                            <div class="clear"></div>
                                        </div>
                                        
                                        <div class="infopopupoptrow last">
                                            <label>Development Mode</label>
                                            <div class="infopopupfield">
                                                <?php echo get_js_jshint_devel(get_option('js_jshint_devel')); ?>

                                                <div class="clear"></div>
                                            </div>

                                            <div class="clear"></div>
                                        </div>
                                        
                                        <div class="infopopupoptrow last">
                                            <label>Modern Browsers</label>
                                            <div class="infopopupfield">
                                                <?php echo get_js_jshint_browser(get_option('js_jshint_browser')); ?>

                                                <div class="clear"></div>
                                            </div>

                                            <div class="clear"></div>
                                        </div>
                                        
                                        <div class="infopopupoptrow last">
                                            <label>Node.js</label>
                                            <div class="infopopupfield">
                                                <?php echo get_js_jshint_node(get_option('js_jshint_node')); ?>

                                                <div class="clear"></div>
                                            </div>

                                            <div class="clear"></div>
                                        </div>
                                        
                                        <div class="infopopupoptrow last">
                                            <label>Missing Semicolons</label>
                                            <div class="infopopupfield">
                                                <?php echo get_js_jshint_lastsemic(get_option('js_jshint_lastsemic')); ?>

                                                <div class="clear"></div>
                                            </div>

                                            <div class="clear"></div>
                                        </div>
                                        
                                        <div class="infopopupoptrow last">
                                            <label>Maximum amount of warnings</label>
                                            <div class="infopopupfield">
                                                <?php echo get_js_jshint_maxerr(get_option('js_jshint_maxerr')); ?>

                                                <div class="clear"></div>
                                            </div>

                                            <div class="clear"></div>
                                        </div>
                                        
                                        <div class="infopopupoptrow last">
                                            <label>Expects Assignments or Function Calls</label>
                                            <div class="infopopupfield">
                                                <?php echo get_js_jshint_expr(get_option('js_jshint_expr')); ?>

                                                <div class="clear"></div>
                                            </div>

                                            <div class="clear"></div>
                                        </div>
                                        
                                        <div class="infopopupoptrow last">
                                            <label>Use Global Strict Mode</label>
                                            <div class="infopopupfield">
                                                <?php echo get_js_jshint_globalstrict(get_option('js_jshint_globalstrict')); ?>

                                                <div class="clear"></div>
                                            </div>

                                            <div class="clear"></div>
                                        </div>
                                        
                                        <div class="clear"></div>
                                    </div>

                                    <div class="clear"></div>
                                </div>
                                <!-- Javascript: End -->

                                <?php /* Appearance: Start */ ?>
                                <div class="accordionitem appearance">
                                    <div class="infopopuptitle">
                                        <span>Appearance</span>
                                    </div>
									
                                    <div class="infopopupsubsectitle">
                                        <span>Theming</span>
                                    </div>
									
                                    <div class="infopopupoptions">
                                        
										<?php /* Color Theme: Start */ ?>
										<div class="infopopupoptrow first">
                                            <label>Application Color Theme</label>
                                            <div class="infopopupfield">
                                                <?php echo get_page_theme(get_option('editor_page_theme')); ?>
												<div class="fieldinfo">
													Change the visual appearance of the code editor. You must save your change before being available.
												</div>
												
                                                <div class="clear"></div>
                                            </div>
											
                                            <div class="clear"></div>
                                        </div>
										<?php /* Color Theme: End */ ?>
										
										<?php /* Editor Syntax Theme: Start */ ?>
                                        <div class="infopopupoptrow last">
                                            <label>Editor Syntax Theme</label>
                                            <div class="infopopupfield">
                                                <?php echo get_editor_theme(get_option('editor_theme')); ?>
												<div class="fieldinfo">
													Change the code editor pane syntax theme. You must save your change before being available.
												</div>
												
                                                <div class="clear"></div>
                                            </div>
											
                                            <div class="clear"></div>
                                        </div>
										<?php /* Editor Syntax Theme: End */ ?>
										
                                        <div class="clear"></div>
                                    </div>
									
                                    <div class="clear"></div>
                                </div>
                                <?php /* Appearance: End */ ?>
								
								<?php /*
                                <!-- Version Control: Start -->
                                <div class="accordionitem versioncontrol">
                                    <div class="infopopuptitle">
                                        <span>Version Control</span>
                                    </div>
									
									<!--
                                    <div class="infopopupsubsectitle">
                                        <span>Subsection Title Here</span>
                                    </div>
									-->
									
                                    <div class="infopopupoptions">
                                        <div class="infopopupoptrow first">
                                            <!--<label></label>-->
                                            <div class="infopopupfield" style="padding-left: 0;">
												This feature is currently under development, and will be made available once all tests have been passed.
												
                                                <div class="clear"></div>
                                            </div>
											
                                            <div class="clear"></div>
                                        </div>
										
                                        <div class="clear"></div>
                                    </div>
									
                                    <div class="clear"></div>
                                </div>
                                <!-- Version Control: End -->
								*/ ?>
								
                                <?php /* System Settings: Start */ ?>
                                <div class="accordionitem systemsettings">
                                    <div class="infopopuptitle">
                                        <span>System Settings</span>
                                    </div>
									
                                    <div class="infopopupsubsectitle">
                                        <span>Enable/Disable Lazy Loading</span>
                                    </div>
									
                                    <div class="infopopupoptions">
                                        
										<?php /* Enable/Disable Lazy Loading: Start */ ?>
										<div class="infopopupoptrow first">
                                            <label>Enable/Disable Lazy Loading</label>
                                            <div class="infopopupfield">
                                                <?php echo get_lazyloading_enable(get_option('lazyloading')); ?>
                                                <div class="fieldinfo">
													Ordinarily, the system loader automatically loads the initial program and all of its dependent components at the same time. In lazy loading, also known as dynamic function loading, dependents are only loaded as they are specifically requested. Lazy loading can be used to improve the performance if most of the dependent components are never actually used. Enabling this option may increase the performance of the code editor application. You must save your change before being available.
												</div>

                                                <div class="clear"></div>
                                            </div>

                                            <div class="clear"></div>
                                        </div>
										<?php /* Enable/Disable Lazy Loading: End */ ?>
										
                                        <div class="clear"></div>
                                    </div>

                                    <div class="clear"></div>
                                </div>
                                <?php /* System Settings: End */ ?>

                                <?php /* Toolbar Settings: Start */ ?>
                                <div class="accordionitem toolbarsettings">
                                    <div class="infopopuptitle">
                                        <span>Toolbar Settings</span>
                                    </div>
									
                                    <div class="infopopupsubsectitle">
                                        <span>Enable/Disable Toolbar</span>
                                    </div>
									
                                    <div class="infopopupoptions">
										
										<?php /* Enable/Disable Toolbar: Start */ ?>
										<div class="infopopupoptrow first">
                                            <label>Enable/Disable Toolbar</label>
                                            <div class="infopopupfield">
                                                <?php echo get_toolbar_enable(get_option('toolbar_enable')); ?>
                                                <div class="fieldinfo">
													You can choose to have a toolbar above all open file tabs. Available features in the toolbar are tab refresh, increase and decrease font size, cut, copy, paste, and more. You must save your change before being available.
												</div>

                                                <div class="clear"></div>
                                            </div>

                                            <div class="clear"></div>
                                        </div>
										<?php /* Enable/Disable Toolbar: End */ ?>
										
                                        <div class="clear"></div>
                                    </div>

                                    <div class="clear"></div>
                                </div>
                                <?php /* Toolbar Settings: End */ ?>

                                <?php /* Database Settings: Start */ ?>
                                <div class="accordionitem databasesettings">
                                    <div class="infopopuptitle">
                                        <span>Database Settings</span>
                                    </div>
									
                                    <div class="infopopupsubsectitle">
                                        <span>phpMyAdmin</span>
                                    </div>
									
                                    <div class="infopopupoptions">
										
										<?php /* How to Open phpMyAdmin: Start */ ?>
										<div class="infopopupoptrow first">
                                            <label>How to Open phpMyAdmin</label>
                                            <div class="infopopupfield">
                                                <?php echo get_phpmyadmin_open(get_option('phpmyadmin_open')); ?>
                                                <div class="fieldinfo">
													phpMyAdmin can either be opened in a tab within the editor interface, or you can choose to have it open in another browser tab. The benefit of a separate browser tab would allow you to separate the code editor and phpMyAdmin into two independent screens. You must save your change before being available.
												</div>
												
                                                <div class="clear"></div>
                                            </div>
											
                                            <div class="clear"></div>
                                        </div>
										<?php /* How to Open phpMyAdmin: End */ ?>
										
                                        <div class="clear"></div>
                                    </div>
									
                                    <div class="clear"></div>
                                </div>
                                <?php /* Database Settings: End */ ?>
								
                                <?php /* Search Settings: Start */ ?>
                                <div class="accordionitem searchsettings">
                                    <div class="infopopuptitle">
                                        <span>Search Settings</span>
                                    </div>
									
                                    <div class="infopopupsubsectitle">
                                        <span>Search Result</span>
                                    </div>
									
                                    <div class="infopopupoptions">
										
										<?php /* How to Show Search Results: Start */ ?>
										<div class="infopopupoptrow first">
                                            <label>How to Show Search Results</label>
                                            <div class="infopopupfield">
                                                <?php echo get_search_collapse(get_option('search_collapse')); ?>
                                                <div class="fieldinfo">
													If you choose to have your search results expanded, all results will be uncollapsed when search completes. This option may cause performance issues if you have too many results. If you experience stability issues, choose to have all search results collapsed. You must save your change before being available.
												</div>
												
                                                <div class="clear"></div>
                                            </div>
											
                                            <div class="clear"></div>
                                        </div>
										<?php /* How to Show Search Results: End */ ?>
										
                                        <div class="clear"></div>
                                    </div>
									
                                    <div class="clear"></div>
                                </div>
                                <?php /* Search Settings: End */ ?>
								
                                <?php /* Terminal Settings: Start */ ?>
                                <div class="accordionitem terminalsettings">
                                    <div class="infopopuptitle">
                                        <span>Terminal Settings</span>
                                    </div>
									
									<?php /*
                                    <div class="infopopupsubsectitle">
                                        <span>Subsection Title Here</span>
                                    </div>
									*/ ?>
									
                                    <div class="infopopupoptions">
                                        
										<?php /* Feature Coming Soon: Start */ ?>
										<div class="infopopupoptrow first">
                                            <?php /* <label></label> */ ?>
                                            <div class="infopopupfield" style="padding-left: 0;">
												This feature is currently under development, and will be made available once all tests have been passed.
												
                                                <div class="clear"></div>
                                            </div>
											
                                            <div class="clear"></div>
                                        </div>
										<?php /* Feature Coming Soon: End */ ?>
										
                                        <div class="clear"></div>
                                    </div>
									
                                    <div class="clear"></div>
                                </div>
                                <?php /* Terminal Settings: End */ ?>
								
                                <?php /* Subversion/GIT: Start */ ?>
                                <div class="accordionitem subversiongit">
                                    <div class="infopopuptitle">
                                        <span>Subversion/GIT</span>
                                    </div>
									
									<?php /*
                                    <div class="infopopupsubsectitle">
                                        <span>Subsection Title Here</span>
                                    </div>
									*/ ?>
									
                                    <div class="infopopupoptions">
										
										<?php /* Feature Coming Soon: Start */ ?>
										<div class="infopopupoptrow first">
                                            <?php /* <label>Blank</label> */ ?>
                                            <div class="infopopupfield" style="padding-left: 0;">
												This feature is currently under development, and will be made available once all tests have been passed.
												
                                                <div class="clear"></div>
                                            </div>
											
                                            <div class="clear"></div>
                                        </div>
										<?php /* Feature Coming Soon: End */ ?>
										
                                        <div class="clear"></div>
                                    </div>
									
                                    <div class="clear"></div>
                                </div>
                                <?php /* Subversion/GIT: End */ ?>
								
                                <?php /* Manage Widgets: Start */ ?>
                                <div class="accordionitem managewidgets">
                                    <div class="infopopuptitle">
                                        <span>Manage Widgets</span>
                                    </div>
									
                                    <p style="padding: 10px 8px 15px;">Below is a list of all available widgets. By default all widgets are disabled. If you wish to use any of the widgets, select the checkbox next to 'enable'. To disable a widget, uncheck the checkbox for the ones you want to remove from your sidebar tools editor interface.</p>
									
                                    <div class="infooptionscontainer widget-container" id="widget-container">
										<?php include 'widget.php'; ?>
									</div>
									
                                    <div class="clear"></div>
                                </div>
                                <?php /* Manage Widgets: End */ ?>
								
                                <div class="clear"></div>
                            </div>
							
                            <div class="clear"></div>
                        </div>
                        <?php /* Configuration Sections: End */ ?>
						
                        <div class="clear"></div>
                    </div>
					
                    <div class="clear"></div>
                </div>
                <?php /* Configuration Wrapper: End */ ?>
				
                <div class="clear"></div>
            </div>
        </form>
		
        <div class="clear"></div>
    </div>
</body>
</html>