<input type="file" id="openner" style="height: 0; width: 0; opacity: 0; visibility: hidden;" />
<input type="input" id="tempMemory" style="height: 0; width: 0; opacity: 0; visibility: hidden;" />
<a href="" id="popupHandle" style="height: 0; width: 0; opacity: 0; visibility: hidden;"></a>

<?php /* ACE Editor JS: Start */ ?>
<script src="<?php echo base_url(); ?>core/ace/src-min/ace.js" data-ace-base="core/ace/src-min"></script>
<script src="<?php echo base_url(); ?>core/ace/src-min/ext-language_tools.js" ></script>
<script src="<?php echo base_url(); ?>core/ace/src-min/ext-statusbar.js"></script>
<script src="<?php echo base_url(); ?>core/ace/src-min/ext-elastic_tabstops_lite.js"></script>
<script src="<?php echo base_url(); ?>core/ace/src-min/ext-token_tooltip.js"></script>
<script src="<?php echo base_url(); ?>core/ace/src-min/keybinding-emacs.js"></script>
<script src="<?php echo base_url(); ?>core/ace/src-min/keybinding-vim.js"></script>

<script type="text/javascript">
    var sceditor;
	
    $(document).ready(function(e) {
        
        <?php /* Ace Editor: Start */ ?>
        sceditor = $("body").sceditor({
            activeWorkspace						: <?php echo $active_workspace ? json_encode($active_workspace) : 'null'; ?>,
            baseUrl								: "<?php echo base_url(); ?>",
            configAce: {
                enableBasicAutocompletion		: false,
                enableWelcome					: "<?php echo get_option('enableWelcome'); ?>",
                setTheme						: "<?php echo get_option("editor_theme") ?>",
                setMode							: "<?php echo get_option("editor_default_mode") ?>",
                setTabSize						: "<?php echo get_option("editor_tab_spaces") ?>",
                setUseWrapMode					: ("<?php echo get_option("editor_toggle_word_wrapping") ?>" != "false"),
                fontSize						: "<?php echo get_option("font_size") ?>",
                setShowPrintMargin				: "<?php echo get_option("show_print_margin") ?>",
                setReadOnly						: "<?php echo get_option("editor_read_only") ?>",
                showGutter						: ("<?php echo get_option("show_gutter") ?>" != "false"),
                useSoftTab						: ("<?php echo get_option("use_soft_tab") ?>" != "false"),
                highlightSelectedWord					: ("<?php echo get_option("highlight_selected_word") ?>" == "true"),
                fadeFoldWidgets						: ("<?php echo get_option("fade_fold_widgets") ?>" == "true"),
                showTokenInfo					: ("<?php echo get_option("show_token_info") ?>" == "true"),
                folding							: "<?php echo get_option("editor_code_folding") ?>",
                keyBinding						: "<?php echo get_option("editor_key_binding") ?>",
                highlightActiveLine				: ("<?php echo get_option("editor_highlight_line") ?>" != "false"),
                showIndentGuides				: ("<?php echo get_option("editor_show_indent_guides") ?>" != "false"),
                showInvisibles					: ("<?php echo get_option("editor_show_invisibles") ?>" != "false"),
                enableBehaviors					: ("<?php echo get_option("editor_enable_behaviors") ?>" == "true"),
                treeNavigation					: '.sitenavdropdown',
                autoSetMode						: "<?php echo get_option("auto_file_extension_detect_mode") ?>",
                tabListUl						: "#editorfilelist",
                statusBar						: "<?php echo (get_option("statusbar") == '') ? 'inactive' : get_option("statusbar"); ?>",
                tagBar							: "<?php echo (get_option("tagbar") == '') ? 'inactive' : get_option("tagbar"); ?>",
                toolbar							: "<?php echo (get_option("toolbar_enable") == '1'); ?>",
                imageExtension					: <?php echo json_encode(imageExtensions(), true); ?>,
                search_collapse					: "<?php echo (get_option("search_collapse") == '1'); ?>",
                lazyloading						: "<?php echo (get_option("lazyloading") != '0' ? 'true' : 'false'); ?>",
                animatedScroll					: "<?php echo get_option('editor_animated_scroll'); ?>" == "true",
                setOptions: {
                    enableBasicAutocompletion	: ("<?php echo get_option('get_autocomplete'); ?>" != "false"),
                    useWorker					: ("<?php echo get_option('js_jshint_enable'); ?>" == "true"),
                    showTokenInfo				: ("<?php echo get_option('show_token_info'); ?>" == "true"),
                    wrap						: "<?php echo get_option("editor_soft_wrap") ?>",
                    selectionStyle				: ("<?php echo get_option('editor_selection_style'); ?>"),
                    scrollPastEnd                               : ("<?php echo get_option("scroll_past_end") ?>" != "false"),
                }
            },
            
            commands							: <?php echo json_encode($commands); ?>,
            widgets								: <?php echo json_encode($widgets); ?>,
            widget_options						: <?php echo isset($options['widget_options']) && $options['widget_options'] ? $options['widget_options'] : 'null'; ?>,
            layout_options						: <?php echo isset($options['layout_options']) && $options['layout_options'] ? $options['layout_options'] : 'null'; ?>
        });
        <?php /* Ace Editor: End */ ?>
		
		<?php /* Popup: Start */ ?>
        $('#popupHandle').scpopup({
            linkType		: "iframe",
            scWidth			: "auto",
            scHeight		: "auto",
            popupPosition	: "fixed"
        });
		<?php /* Popup: End */ ?>
		
		<?php /* NotifyJS: Start */ ?>
		$.notify.addStyle("bootstrap", {
			html: "<div>\n<span data-notify-text></span>\n</div>",
			classes: {
				base: {
					"padding"				: "12px 15px",
					"padding-left"			: "25px",
					"font-size"				: "13px",
					"font-weight"			: "normal",
					"white-space"			: "nowrap",
					"border"				: "1px solid #fbeed5",
					"background-color"		: "#fcf8e3",
					"background-position"	: "3px center",
					"background-repeat"		: "no-repeat",
					
					"text-shadow"			: "0 1px 0 rgba(255, 255, 255, 0.5)",
					"-webkit-border-radius"	: "1px",
					"-moz-border-radius"	: "1px",
					"border-radius"			: "1px"
				},
				error: {
					"color"					: "#b94a48",
					"border-color"			: "#eed3d7",
					"background-color"		: "#f2dede",
					"background-image"		: "url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAtRJREFUeNqkVc1u00AQHq+dOD+0poIQfkIjalW0SEGqRMuRnHos3DjwAH0ArlyQeANOOSMeAA5VjyBxKBQhgSpVUKKQNGloFdw4cWw2jtfMOna6JOUArDTazXi/b3dm55socPqQhFka++aHBsI8GsopRJERNFlY88FCEk9Yiwf8RhgRyaHFQpPHCDmZG5oX2ui2yilkcTT1AcDsbYC1NMAyOi7zTX2Agx7A9luAl88BauiiQ/cJaZQfIpAlngDcvZZMrl8vFPK5+XktrWlx3/ehZ5r9+t6e+WVnp1pxnNIjgBe4/6dAysQc8dsmHwPcW9C0h3fW1hans1ltwJhy0GxK7XZbUlMp5Ww2eyan6+ft/f2FAqXGK4CvQk5HueFz7D6GOZtIrK+srupdx1GRBBqNBtzc2AiMr7nPplRdKhb1q6q6zjFhrklEFOUutoQ50xcX86ZlqaZpQrfbBdu2R6/G19zX6XSgh6RX5ubyHCM8nqSID6ICrGiZjGYYxojEsiw4PDwMSL5VKsC8Yf4VRYFzMzMaxwjlJSlCyAQ9l0CW44PBADzXhe7xMdi9HtTrdYjFYkDQL0cn4Xdq2/EAE+InCnvADTf2eah4Sx9vExQjkqXT6aAERICMewd/UAp/IeYANM2joxt+q5VI+ieq2i0Wg3l6DNzHwTERPgo1ko7XBXj3vdlsT2F+UuhIhYkp7u7CarkcrFOCtR3H5JiwbAIeImjT/YQKKBtGjRFCU5IUgFRe7fF4cCNVIPMYo3VKqxwjyNAXNepuopyqnld602qVsfRpEkkz+GFL1wPj6ySXBpJtWVa5xlhpcyhBNwpZHmtX8AGgfIExo0ZpzkWVTBGiXCSEaHh62/PoR0p/vHaczxXGnj4bSo+G78lELU80h1uogBwWLf5YlsPmgDEd4M236xjm+8nm4IuE/9u+/PH2JXZfbwz4zw1WbO+SQPpXfwG/BBgAhCNZiSb/pOQAAAAASUVORK5CYII=)"
				},
				success: {
					"color"					: "#468847",
					"border-color"			: "#d6e9c6",
					"background-color"		: "#dff0d8",
					"background-image"		: "url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAutJREFUeNq0lctPE0Ecx38zu/RFS1EryqtgJFA08YCiMZIAQQ4eRG8eDGdPJiYeTIwHTfwPiAcvXIwXLwoXPaDxkWgQ6islKlJLSQWLUraPLTv7Gme32zoF9KSTfLO7v53vZ3d/M7/fIth+IO6INt2jjoA7bjHCJoAlzCRw59YwHYjBnfMPqAKWQYKjGkfCJqAF0xwZjipQtA3MxeSG87VhOOYegVrUCy7UZM9S6TLIdAamySTclZdYhFhRHloGYg7mgZv1Zzztvgud7V1tbQ2twYA34LJmF4p5dXF1KTufnE+SxeJtuCZNsLDCQU0+RyKTF27Unw101l8e6hns3u0PBalORVVVkcaEKBJDgV3+cGM4tKKmI+ohlIGnygKX00rSBfszz/n2uXv81wd6+rt1orsZCHRdr1Imk2F2Kob3hutSxW8thsd8AXNaln9D7CTfA6O+0UgkMuwVvEFFUbbAcrkcTA8+AtOk8E6KiQiDmMFSDqZItAzEVQviRkdDdaFgPp8HSZKAEAL5Qh7Sq2lIJBJwv2scUqkUnKoZgNhcDKhKg5aH+1IkcouCAdFGAQsuWZYhOjwFHQ96oagWgRoUov1T9kRBEODAwxM2QtEUl+Wp+Ln9VRo6BcMw4ErHRYjH4/B26AlQoQQTRdHWwcd9AH57+UAXddvDD37DmrBBV34WfqiXPl61g+vr6xA9zsGeM9gOdsNXkgpEtTwVvwOklXLKm6+/p5ezwk4B+j6droBs2CsGa/gNs6RIxazl4Tc25mpTgw/apPR1LYlNRFAzgsOxkyXYLIM1V8NMwyAkJSctD1eGVKiq5wWjSPdjmeTkiKvVW4f2YPHWl3GAVq6ymcyCTgovM3FzyRiDe2TaKcEKsLpJvNHjZgPNqEtyi6mZIm4SRFyLMUsONSSdkPeFtY1n0mczoY3BHTLhwPRy9/lzcziCw9ACI+yql0VLzcGAZbYSM5CCSZg1/9oc/nn7+i8N9p/8An4JMADxhH+xHfuiKwAAAABJRU5ErkJggg==)"
				},
				info: {
					"color"					: "#3a87ad",
					"border-color"			: "#bce8f1",
					"background-color"		: "#d9edf7",
					"background-image"		: "url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAYAAACNiR0NAAAABmJLR0QA/wD/AP+gvaeTAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAB3RJTUUH3QYFAhkSsdes/QAAA8dJREFUOMvVlGtMW2UYx//POaWHXg6lLaW0ypAtw1UCgbniNOLcVOLmAjHZolOYlxmTGXVZdAnRfXQm+7SoU4mXaOaiZsEpC9FkiQs6Z6bdCnNYruM6KNBw6YWewzl9z+sHImEWv+vz7XmT95f/+3/+7wP814v+efDOV3/SoX3lHAA+6ODeUFfMfjOWMADgdk+eEKz0pF7aQdMAcOKLLjrcVMVX3xdWN29/GhYP7SvnP0cWfS8caSkfHZsPE9Fgnt02JNutQ0QYHB2dDz9/pKX8QjjuO9xUxd/66HdxTeCHZ3rojQObGQBcuNjfplkD3b19Y/6MrimSaKgSMmpGU5WevmE/swa6Oy73tQHA0Rdr2Mmv/6A1n9w9suQ7097Z9lM4FlTgTDrzZTu4StXVfpiI48rVcUDM5cmEksrFnHxfpTtU/3BFQzCQF/2bYVoNbH7zmItbSoMj40JSzmMyX5qDvriA7QdrIIpA+3cdsMpu0nXI8cV0MtKXCPZev+gCEM1S2NHPvWfP/hL+7FSr3+0p5RBEyhEN5JCKYr8XnASMT0xBNyzQGQeI8fjsGD39RMPk7se2bd5ZtTyoFYXftF6y37gx7NeUtJJOTFlAHDZLDuILU3j3+H5oOrD3yWbIztugaAzgnBKJuBLpGfQrS8wO4FZgV+c1IxaLgWVU0tMLEETCos4xMzEIv9cJXQcyagIwigDGwJgOAtHAwAhisQUjy0ORGERiELgG4iakkzo4MYAxcM5hAMi1WWG1yYCJIcMUaBkVRLdGeSU2995TLWzcUAzONJ7J6FBVBYIggMzmFbvdBV44Corg8vjhzC+EJEl8U1kJtgYrhCzgc/vvTwXKSib1paRFVRVORDAJAsw5FuTaJEhWM2SHB3mOAlhkNxwuLzeJsGwqWzf5TFNdKgtY5qHp6ZFf67Y/sAVadCaVY5YACDDb3Oi4NIjLnWMw2QthCBIsVhsUTU9tvXsjeq9+X1d75/KEs4LNOfcdf/+HthMnvwxOD0wmHaXr7ZItn2wuH2SnBzbZAbPJwpPx+VQuzcm7dgRCB57a1uBzUDRL4bfnI0RE0eaXd9W89mpjqHZnUI5Hh2l2dkZZUhOqpi2qSmpOmZ64Tuu9qlz/SEXo6MEHa3wOip46F1n7633eekV8ds8Wxjn37Wl63VVa+ej5oeEZ/82ZBETJjpJ1Rbij2D3Z/1trXUvLsblCK0XfOx0SX2kMsn9dX+d+7Kf6h8o4AIykuffjT8L20LU+w4AZd5VvEPY+XpWqLV327HR7DzXuDnD8r+ovkBehJ8i+y8YAAAAASUVORK5CYII=)"
				},
				warn: {
					"color"					: "#c09853",
					"border-color"			: "#fbeed5",
					"background-color"		: "#fcf8e3",
					"background-image"		: "url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAUCAMAAAC6V+0/AAABJlBMVEXr6eb/2oD/wi7/xjr/0mP/ykf/tQD/vBj/3o7/uQ//vyL/twebhgD/4pzX1K3z8e349vK6tHCilCWbiQymn0jGworr6dXQza3HxcKkn1vWvV/5uRfk4dXZ1bD18+/52YebiAmyr5S9mhCzrWq5t6ufjRH54aLs0oS+qD751XqPhAybhwXsujG3sm+Zk0PTwG6Shg+PhhObhwOPgQL4zV2nlyrf27uLfgCPhRHu7OmLgAafkyiWkD3l49ibiAfTs0C+lgCniwD4sgDJxqOilzDWowWFfAH08uebig6qpFHBvH/aw26FfQTQzsvy8OyEfz20r3jAvaKbhgG9q0nc2LbZxXanoUu/u5WSggCtp1anpJKdmFz/zlX/1nGJiYmuq5Dx7+sAAADoPUZSAAAAAXRSTlMAQObYZgAAAAFiS0dEAIgFHUgAAAAJcEhZcwAACxMAAAsTAQCanBgAAAAHdElNRQfdBgUBGhh4aah5AAAAlklEQVQY02NgoBIIE8EUcwn1FkIXM1Tj5dDUQhPU502Mi7XXQxGz5uVIjGOJUUUW81HnYEyMi2HVcUOICQZzMMYmxrEyMylJwgUt5BljWRLjmJm4pI1hYp5SQLGYxDgmLnZOVxuooClIDKgXKMbN5ggV1ACLJcaBxNgcoiGCBiZwdWxOETBDrTyEFey0jYJ4eHjMGWgEAIpRFRCUt08qAAAAAElFTkSuQmCC)"
				}
			}
		});
		<?php /* NotifyJS: End */ ?>
		
		<?php /* Context Menu: Start */ ?>
        var option = 
			{
				alias: 'filetreecontextmenu', width: 230, items: [
					{text: "Open", alias: "open", action: onClick},
					{type: "splitLine"},
					{
						text: "New", alias: "new", type: "group", width: 160, items: [
							{text: "New File", alias: "newFile", action: onClick},
							{text: "New Folder", alias: "newFolder", action: onClick},
							{type: "splitLine"},
							{text: "PHP File", alias: "newPHPFile", action: onClick},
							{text: "PHP Class", alias: "newPHPClass", action: onClick},
							{type: "splitLine"},
							{text: "HTML File", alias: "newHTML", action: onClick},
							{text: "JavaScript File", alias: "newJavaScript", action: onClick},
							{text: "TypeScript File", alias: "newTypeScript", action: onClick},
							{text: "CoffeeScript File", alias: "newCoffeeScript", action: onClick},
							{text: "XSLT Stylesheet", alias: "newXSLT", action: onClick},
							{text: "CSS File", alias: "newCSS", action: onClick}
						]
					},
					{type: "splitLine"},
					{text: "Preview Project ...", alias: "previewProject", action: onClick},
					{text: "Preview File", alias: "previewFile", action: onClick},
					{type: "splitLine"},
					{text: "Cut", alias: "cut", action: onClick},
					{text: "Copy", alias: "copy", action: onClick},
					
					/*
					{text: "Copy Path", alias: "1-5", action: onClick},
					{text: "Copy Reference", alias: "1-6", action: onClick},
					*/
					
					{text: "Paste", alias: "paste", action: onClick},
					{text: "Delete", alias: "delete", action: onClick},
					{text: "Duplicate", alias: "duplicate", action: onClick},
					
					/*
					{text: "Jump to Source", alias: "1-10", action: onClick},
					{type: "splitLine"},
					{text: "Deploy to (S)FTP Server", alias: "1-8", action: onClick},
					*/
					
					{type: "splitLine"},
					{
						text: "Refactor", alias: "refactor", type: "group", width: 125, items: [
							{text: "Rename", alias: "rename", action: onClick},
							{type: "splitLine"},
							{text: "Move...", alias: "moveTo", action: onClick},
							{text: "Copy...", alias: "copyTo", action: onClick}
						]
					},
					{type: "splitLine"},
					{text: "Search Files Here", alias: "search", action: onClick},
					{text: "Upload Files", alias: "uploadFiles", action: onClick},
					{type: "splitLine"},
					{text: "Compress Selected File(s)", alias: "compress", action: onClick},
					{text: "Compress Selected File(s) As ...", alias: "compressAs", action: onClick},
					{text: "Extract Selected Archive", alias: "extract", action: onClick},
					{text: "Extract Selected Archive Into ...", alias: "extractInto", action: onClick},
					
					/*
					{type: "splitLine"},
					{
						text: "Add to Favorites", alias: "1-13", type: "group", width: 220, items: [
							{text: "Add To New Favorites List", alias: "4-1", action: onClick}
						]
					},
					{type: "splitLine"},
					{
						text: "Git",, alias: "1-14", type: "group", width: 300, items: [
							{text: "Commit File...", alias: "5-1", action: onClick},
							{text: "Add", alias: "5-2", action: onClick},
							{type: "splitLine"},
							{text: "Annotate", alias: "5-3", action: onClick},
							{text: "Show Current Revision", alias: "5-4", action: onClick},
							{text: "Compare with the Same Repository Version", alias: "5-5", action: onClick},
							{text: "Compare with Latest Repository Version", alias: "5-6", action: onClick},
							{text: "Compare with...", alias: "5-7", action: onClick},
							{text: "Compare with Branch...", alias: "5-8", action: onClick},
							{text: "Show History", alias: "5-9", action: onClick},
							{text: "Show History for Selection", alias: "5-10", action: onClick},
							{type: "splitLine"},
							{
								text: "Repository", alias: "5-11", type: "group", width: 190, items: [
									{text: "Branches...", alias: "6-1", action: onClick},
									{text: "Tag Files...", alias: "6-2", action: onClick},
									{text: "Merge Changes...", alias: "6-3", action: onClick},
									{text: "Stash Changes...", alias: "6-4", action: onClick},
									{text: "UnStash Changes...", alias: "6-5", action: onClick},
									{text: "Reset HEAD...", alias: "6-6", action: onClick},
									{type: "splitLine"},
									{text: "Fetch", alias: "6-7", action: onClick},
									{text: "Pull...", alias: "6-8", action: onClick},
									{text: "Push...", alias: "6-9", action: onClick},
									{type: "splitLine"},
									{text: "Rebase...", alias: "6-10", action: onClick}
								]
							},
						]
					}
					*/

				], onShow: onShow,
				onContextMenu: onContextMenu
			};

        function onClick() {
            return sceditor.call("base.contextmenu.onClick()", this.data.alias);
        }

        function onShow(menu) {
            return sceditor.call("base.contextmenu.onShow()", menu);
        }

        function onContextMenu(e) {
            return sceditor.call("base.contextmenu.onContextMenu()", e);
        }

        $("#filetree").contextmenu(option);
    });
</script>

</body>
</html>