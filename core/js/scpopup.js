(function($) {
    $.fn.scpopup = function(options) {

        var defaults = {
            // Settings Variables
            linkType: "iframe", // iframe, inline, html, image
            scWidth: "65%", // Width of popup container (in px, % or auto)
            scHeight: "auto", // Height of popup container (in px, % or auto)
            scPadding: "10px", // Height of popup container (in px, % or auto)
            popupMaxWidth: "700px;", // Max width of popup container (in px, % or auto)
            popupMaxHeight: "auto", // Max width of popup container (in px, % or auto)
            popupTheme: "", // Popup theme name (is an additional class added to parent)
            activeClass: "active", // Class name to use for active elements
            popupPosition: "fixed", // absolute, fixed
            draggableModal: true, // Boolean
            title: "", // absolute, fixed
            effectOpen: "", // Popup opening effect
            effectClose: "", // Popup closing effect
            url: "", // Popup closing effect
            titleHeight: "70",
            backdropFadeIn: "150", // Popup Backdrop FadeIn Time
            popupFadeIn: "300", // Popup Content Frame FadeIn Time
            backdropFadeOut: "300", // Popup Backdrop FadeOut Time
			showBackdrop: true, // Set to true to show backdrop
            popupFadeOut: "150", // Popup Content Frame FadeOut Time
            scMeasurement: 'px', // Used only if dimensions haven't been added
            htmlContent: "<h2>Title</h2><p>This content will go into the popup.</p>" // Must set linkType to html
        };

        var options = $.extend(defaults, options);

        // Functions to Specify Width and Height of Popup
        function adjustSize(scW, scH, animate) {
            var lf = $(window).width();
            var left = (lf / 2) - (parseInt(scW) / 2);
            var hw = $(window).height();
            var _h = (animate) ? scH + parseInt(options.titleHeight) : scH;

            if (_h > (hw - 100)) {
                _h = hw - 100;
            }

            var top = (hw / 2) - (parseInt(_h) / 2);

            if (animate == true) {
                if (options.scMeasurement == '%') {
                    if (scW > 100) {
                        scW = "80";
                        alert("Sorry, you can't define a percentage greater than 100. Your width will automatically be set to 80%.");
                    }
                    left = (100 - scW) / 2;
                }
                $('#scpopup').css({'position': options.popupPosition})
					.animate({
						'left': left + options.scMeasurement,
						'top': top,
						'width': scW + options.scMeasurement,
						'height': _h,
						'opacity': 1
					}, 500);
            } else {
                $('#scpopup')
					.css({
						'position': options.popupPosition,
						'left': left + 'px',
						'top': top + 'px',
						'width': parseInt(scW) + 'px',
						'height': parseInt(_h) + 'px'
					})
            }
            $('#scpopup').find('iframe').height(parseInt(_h - 46) - parseInt(options.titleHeight))
        }

        $(this).addClass('scpopuplink');

        // Click Event: Open
        $(this).on('click', function(e) {
            e.preventDefault();

            var title = (options.title != '') ? options.title : $(this).data('title');

            $('div.popupbackdrop,#scpopup').remove();
			
			// Show backdrop if set to true
			if(options.showBackdrop == true){
				$('body').append('<div class="popupbackdrop"></div>');
			}
			
			// Show popup container
            $('body').append('<div id="scpopup" class="scpopup">' +
								'<div id="scpopupouter">' +
									'<div id="scpopupinner">' +
										
										'<div id="scpopupclose"></div>' +
										'<div id="scpopuptitle"></div>' +
										'<div id="scpopupsubtitle"></div>' +
										
										'<div id="scpopupholder">' +
											'<div id="scpopupcontent">' +
												'<div class="infopopuptitle">' +
													'<span>' + title + '</span>' +
												'</div>' +
												
												'<div id="loaderContainer">' +
													'<span class="loadingText">Loading...</span>' +
													'<div class="pro-bar-container color-midnight-blue">' +
														'<div class="pro-bar bar-100 wet-asphalt" data-pro-bar-percent="100">' +
															'<div class="pro-bar-candy candy-ltr"></div>' +
														'</div>' +
													'</div>' +
												'</div>' +
												
												'<div class="clear"></div>' +
											'</div>' +
											
											'<div class="clear"></div>' +
										'</div>' +
										
										'<div class="clear"></div>' +
									'</div>' +
									
									'<div class="clear"></div>' +
								'</div>' +
								
								'<div class="clear"></div>' +
							'</div>'
                    );

            // Set Width and Height of Outer Container
            var _scwidth = (options.scWidth == 'auto') ? '300px' : options.scWidth;
            var _scheight = (options.scHeight == 'auto') ? '150px' : options.scHeight;

            // Add class 'draggable' if 'draggableModal' set to 'true'
            if (options.draggableModal == true) {
                $('#scpopup').addClass('draggable');
                $('#scpopup').draggable();
                //$('div.popupbackdrop').remove();
                //$('div.popupbackdrop').css({ display : 'none'});
            }

            adjustSize(_scwidth, _scheight, false);
            $('#scpopup').addClass(options.popupTheme);

            // Setting Body/HTML tags to 100% width and height
            $('body', 'html').css({'width': '100%', 'height': '100%'});
            $('body').addClass('scpopupactive');

            // Transitions
            $('div.popupbackdrop').fadeIn(options.backdropFadeIn).addClass(options.activeClass);
            $('#scpopup').fadeIn(options.popupFadeIn).addClass(options.activeClass);

            // Empty Title/Subtitle Holders on Click
            $('#scpopuptitle, #scpopupsubtitle').empty();

            // Load Title/Subtitles on Click
            $('<span></span>').text($(this).attr('title')).appendTo('#scpopuptitle');
            $('<span></span>').text($(this).attr('alt')).appendTo('#scpopupsubtitle');
            var URL = '';

            if ($.trim($(this).attr('data-url')) != '' && options.linkType == 'iframe') {
                URL = $(this).attr('data-url');
            } else {
                if ($(this).attr('href') != '' && options.linkType == 'iframe') {
                    URL = $(this).attr('href');
                } else {
                    alert("URL not found to load content.");
                }
            }

            // Link Type (linkType)
            if (options.linkType == 'iframe') {
                $('#scpopupcontent').append(
					$('<iframe>', {
						src: URL,
						id: 'config',
						style: 'opacity: 0; height: 100%',
						load: function() {
							$(this).show();

							var newWidth = 0;
							var newHeight = 0;
							var _h = $(this).contents().find("body").data('height');

							if (options.scHeight == 'auto' && typeof (_h) != 'undefined') {
								newHeight = parseInt(_h) + (parseInt(options.scPadding) * 2);
							} else if (options.scHeight == 'auto') {
								newHeight = parseInt($(this).contents().find("body").height()) + (parseInt(options.scPadding) * 2);
							} else {
								newHeight = parseInt(options.scHeight);
							}

							var _w = $(this).contents().find("body").data('width');

							if (options.scWidth == 'auto' && typeof (_w) != 'undefined') {
								newWidth = parseInt(_w) + (parseInt(options.scPadding) * 2);
								options.scMeasurement = get_me(_w)
							} else if (options.scWidth == 'auto') {
								newWidth = parseInt($(this).contents().find("body").width()) + (parseInt(options.scPadding) * 2);
								options.scMeasurement = get_me($(this).contents().find("body").width());
							} else {
								newWidth = parseInt(options.scWidth);
								options.scMeasurement = get_me(options.scWidth);
							}

							$(this).contents().find("body").removeAttr('style');
							$("#loaderContainer").animate({opacity: 0}, 200, function() {
								$(this).remove();
							});
							
							adjustSize(newWidth, newHeight, true);
							$(this).delay(500).animate({opacity: 1});
							var _controls = $(this).contents().find("body").data('controls');
							
							if ($('#scpopupcontent .infopopupcontrols')[0] == null) {
								var controlls = '<div class="infopopupcontrols">' + '<div class="infopopupcontrolsinside">';

								if (typeof (_controls) != 'undefined') {
									var _Obj = eval("[" + _controls + "]");
									_Obj = _Obj.hasOwnProperty(0) ? _Obj[0] : {};
									$.each(_Obj, function(key, value) {
										var pass = _Obj[key];
										controlls = controlls + '<input type="submit" id="save' + key + '" onclick="popups(this)" data-action="' + _Obj[key] + '" class="button save popups" value="' + key + '" name="save" />';
									});
								}
								controlls = controlls + '<div class="clear"></div>' + '</div>' + '<div class="clear"></div>' + '</div>';
								$('#scpopupcontent').append(controlls)
							}
						}
					})
				)
            } else if (options.linkType == 'inline') {
                //console.log('inline');
            } else if (options.linkType == 'html') {
                $('#scpopupcontent').empty().append(options.htmlContent);
            } else if (options.linkType == 'image') {
                //console.log('image');
            }
        });

        $('body').on('click', 'div.popupbackdrop', function(e) {
            e.preventDefault();

            $('#scpopupclose').trigger('click');
        });

        $('body').on('click', '#scpopupclose', function(e) {
            e.preventDefault();

            $('body').removeClass('scpopupactive');
            $('div.popupbackdrop').delay(50).fadeOut(options.backdropFadeOut).removeClass(options.activeClass);
            $('#scpopup').delay(25).fadeOut(options.popupFadeOut).removeClass(options.activeClass);
        });
    };
})(jQuery);

function scpopupclose() {
    $('#scpopupclose').trigger('click');
}

function get_me(e) {
    try {
        var last2 = e.slice(-1);

        if (last2 == "%") {
            return '%';
        } else {
            return 'px';
        }
    } catch (e) {
        return "px";
    }
}

function configInput(key, value) {
    $("#tempMemory").attr('data-key', key);
    $("#tempMemory").attr('data-value', value);
    $("#tempMemory").trigger("click");
}

function configSelect(key, value) {
    $("#tempMemory").attr('data-key', key);
    $("#tempMemory").attr('data-value', value);
    $("#tempMemory").trigger("click");
}

function popups(e) {
    var e = $(e)
    var _controls = e.data('action');

    if (typeof (_controls) != 'undefined') {
        try {
            eval("document.getElementById('config').contentWindow." + _controls + "()");
        } catch (e) {
			console.log(e);
            alert(_controls)
            try {
                eval(_controls + "()");
            } catch (e) {
                console.log(e);
                alert("Function not found to execute.");
            }
        }
    }
}
