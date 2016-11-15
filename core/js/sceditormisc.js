jQuery(document).ready(function ($) {
	
    /**
     * Tooltips
     * To Do: Still needs collision added: http://css-tricks.com/collision-detection/
     */
	
    /* Left Sidebar Managers Action Icons */
    $('ul.leftcolcontrolslist li').on('mouseenter', function (e) {
        e.stopPropagation();
		
        var toolTipSpan = $(this).text();
        var toolTipWidth = $(this).find('div.tooltip').width();
		
        $(this).append('<div class="tooltip" style="width: ' + toolTipWidth + 'px; margin-left: -10px;">' + toolTipSpan + '</div>');
    });
    $('ul.leftcolcontrolslist li').on('mouseleave', function (e) {
        e.stopPropagation();
		
        $(this).find('div.tooltip').remove();
    });
	
	
    /**
     * My Account Dropdown
     */
    $('ul.editoruseracclist li.myaccount').on('click', function (e) {
        $(this).addClass('active');
        $(this).find('ul.editoruseraccdropdown').slideDown(75);
    });
    $('ul.editoruseracclist li.myaccount').mouseleave(function (e) {
        $(this).removeClass('active');
        $(this).find('ul.editoruseraccdropdown').slideUp(75);
    });
	
	
    /**
     * Top Navigation
     */
    $('ul.sitenavigation li.parent').on('click', function () {
        if (!$(this).parent().hasClass('active')) {
            $(this).parent().addClass('active');
			
            // fires initial item clicked
            if ($(this).parent().hasClass('active')) {
                $(this).find('ul.sitenavdropdown.primary').slideDown(75);
            }
			
            // mouseenter
            $('ul.sitenavigation.active li.parent').mouseenter(function () {
				
                if ($(this).parent().hasClass('active')) {
                    $(this).addClass('active');
                    $(this).find('ul.sitenavdropdown.primary').slideDown(75);
                }
				
            });
			
            // mouseleave
            $('ul.sitenavigation.active li.parent').mouseleave(function () {
				
                if ($(this).parent().hasClass('active')) {
                    $(this).removeClass('active');
                    $(this).find('ul.sitenavdropdown.primary').slideUp(50);
                }
				
            });
        }
    });
	
    $('ul.sitenavigation').mouseleave(function () {
        $(this).removeClass('active');
    });
	
	
    /**
     * File List Dropdown
     */
    $('li.tabcontrolarrow.arrowdown').on('click', function (e) {
        $(this).addClass('active');
        $(this).find('ul.tabdropdownfileslist').slideDown(75);
    });
    $('li.tabcontrolarrow.arrowdown').mouseleave(function (e) {
        $(this).removeClass('active');
        $(this).find('ul.tabdropdownfileslist').slideUp(75);
    });
	
	
    /**
     * Toolbar Dropdown Items
     */
    $('li.toolbaroptionsitem').on('click', function (e) {
        $(this).addClass('active');
        $(this).find('ul.toolbaroptionitemlist').fadeIn(75);
    });
    $('li.toolbaroptionsitem').mouseleave(function (e) {
        $(this).removeClass('active');
        $(this).find('ul.toolbaroptionitemlist').fadeOut(75);
    });
	
	
    /**
     * Search Results Accordion
     */
	
    // Top Level Search Item
    $('div.sresultsgroupswrapper')
		.on('click', 'div.sresultstitle', function () {
			$(this).parent().toggleClass('active');
		})
		
		// Subitem Results
		.on('click', 'div.rsresultsticker', function () {
			$(this).parent().toggleClass('active');
		})
		
		.on('click', 'div.sresultitemdata', function () {
			$("div.sresultsgroupswrapper div.sresultitemdata.active").removeClass("active");
			$(this).addClass("active");
		})
		
		.on('dblclick', 'div.sresultitemdata', function (e) {
			e.preventDefault();
			e.stopPropagation();
			e.cancelBubble = true;
			sceditor.call("base.file.showSearch()", $(this));
			
			return false;
		})
		
		.on('selectstart', function (e) {
			e.preventDefault();
			
			return false;
		});
	
	
    /**
     * Keybindings Accordion
     */
    $('div.keybindingssec_title').on('click', function () {
        $(this).parent().parent().toggleClass('active');
    });
	
	
    /**
     * Event binding for clicking tags on status bar
     */
    $("#statusbar .statustaglevels").on('click', ".spathtag", function() {
        var editor = sceditor.call("base.getEditorInstance(base.tab.getActiveId())");
        if (editor) {
            editor = editor.instance;
            editor.selection.setRange($(this).data('range'));
        }
    });
	
	
	/**
	 * Custom Select Field Dropdown Handle
	 */
	var customSelFld = $('select.select');
	if (customSelFld.length > 0){
		$('select.select').wrap('div.customselect');
		$('div.customselect').prepend('div.selecthandle');
	}
	
});















