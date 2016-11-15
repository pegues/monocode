(function($){
	$.extend($.fn, { 
		fileTree: function(o, h,dire) {
			// Defaults
			
			if( !o ) var o = {};
			if( o.root == undefined ) o.root = '/';
			if( o.script == undefined ) o.script = 'jqueryFileTree.php';
			if( o.folderEvent == undefined ) o.folderEvent = 'click';
			if( o.expandSpeed == undefined ) o.expandSpeed= 500;
			if( o.collapseSpeed == undefined ) o.collapseSpeed= 500;
			if( o.expandEasing == undefined ) o.expandEasing = null;
			if( o.collapseEasing == undefined ) o.collapseEasing = null;
			if( o.multiFolder == undefined ) o.multiFolder = true;
			if( o.loadMessage == undefined ) o.loadMessage = 'Loading...';
			
			$(this).each( function() {
				
				function showTree(c, t) {
					$(c).addClass('wait');
					$(".jqueryFileTree.start").remove();
					$.post(o.script, { dir: t }, function(data) {
						$(c).find('.start').html('');
						$(c).removeClass('wait').append(data);
						if( o.root == t ) $(c).find('UL:hidden').show(); else $(c).find('UL:hidden').slideDown({ duration: o.expandSpeed, easing: o.expandEasing });
						//bindTree(c);
					});
				}
				
				
					$('body').on(o.folderEvent, "ul.jqueryFileTree a", function() {
						if( $(this).parent().hasClass('directory') ) {
							if( $(this).parent().hasClass('collapsed') ) {
								// Expand
								dire($(this).attr('date-file-id'),$(this),'open');
								if( !o.multiFolder ) {
									$(this).parent().parent().find('UL').slideUp({ duration: o.collapseSpeed, easing: o.collapseEasing });
									$(this).parent().parent().find('LI.directory').removeClass('expanded').addClass('collapsed');
								}
								$(this).parent().find('UL').remove(); // cleanup
								showTree( $(this).parent(), $(this).attr('rel'));
								$(this).parent().removeClass('collapsed').addClass('expanded');
							} else {
								// Collapse
								dire($(this).attr('date-file-id'),$(this),'remove');
								$(this).parent().find('UL').slideUp({ duration: o.collapseSpeed, easing: o.collapseEasing });
								$(this).parent().removeClass('expanded').addClass('collapsed');
							}
						} else { 
							h({'file':$(this).attr('rel'),'id':$(this).attr('data-file-id'),'dataUrl':$(this).attr('data-url'),'dataTitle':$(this).attr('data-title'),'dataOpen':$(this).attr('data-open')}); 
						}
						return false;
					});
					// Prevent A from triggering the # on non-click events
					//if( o.folderEvent.toLowerCase != 'click' ) $(t).find('LI A').bind('click', function() { return false; });
				
				// Loading message
				//$(this).html('<ul class="jqueryFileTree start"><li class="wait">' + o.loadMessage + '<li></ul>');
				// Get the initial file list
				//alert("test")
				//showTree( $(this), escape(o.root) );
			});
		}
		
	});
	//showTree( $(this), escape(o.root) );
})(jQuery);