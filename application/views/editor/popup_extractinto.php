<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
	<title>Extract Into ...</title>
	
	<link href="<?php echo base_url(); ?>core/css/popupcontent.css" rel="stylesheet" media="all" />
	<link href="<?php echo base_url(); ?>core/css/jqueryFileTree.css" rel="stylesheet" media="all" />
	<link href="<?php echo base_url(); ?>core/css/fontawesome/font-awesome.css" rel="stylesheet" media="all" />
	<link href="<?php echo base_url(); ?>core/css/contextmenu.css" rel="stylesheet" media="all" />
	<link href="<?php echo base_url(); ?>core/css/jquery.dataTables.css" rel="stylesheet" media="all" />
	<link href="<?php echo base_url(); ?>themes/editor/<?php echo get_option("editor_page_theme") ?>/theme.css" rel="stylesheet" data-default-theme="<?php echo get_option("editor_page_theme") ?>" media="all" />
	
	<script src="<?php echo base_url(); ?>core/js/jquery-1.10.2.min.js" type="text/javascript"></script>
	<script src="<?php echo base_url(); ?>core/js/jquery-ui-1.10.4.custom.min.js" type="text/javascript"></script>
	<script src="<?php echo base_url(); ?>core/js/contextmenu.js"></script>
	<script src="<?php echo base_url(); ?>core/js/jquery.dataTables.min.js"></script>

	<script type="text/javascript">
		var lastObject = null;
		var fileGrid = null;

		/*
		 var option = {width: 190, items: [
		 {text: "New Directory", icon: "sample-css/ei0021-16.gif", alias: "newDirectory", action: menuAction},
		 {text: "Rename", icon: "sample-css/ei0021-16.gif", alias: "rename", action: menuAction},
		 {text: "Delete", icon: "sample-css/ei0021-16.gif", alias: "delete", action: menuAction},
		 ], onShow: applyrule,
		 onContextMenu: BeforeContextMenu
		 };
		 */

		var options = {
			"paging": false,
			"ordering": true,
			"info": false,
			"bFilter": false,
			"aoColumns": [
				{"sWidth": "57%"},
				{"sWidth": "18%"},
				{"sWidth": "25%"}
			],
			"scrollCollapse": true,
			"scrollY": "172px",
                        "aaSorting": [[1, '']],
                        "oLanguage": {
                            "sEmptyTable": "Empty"
                        }
		}

		var dirGrid = null;
		var timer = null;

		$(document).ready(function(e) {
			dirGrid = $('div#dir-explorer > table.fileGrid').dataTable(options);
			$('body').css({'overflow': 'hidden'}).addClass('ftp');

			$('body').on('dblclick', '#dir-explorer tbody>tr.directory', function() {
				var json = $("div.gridParent#dir-explorer").data("required");
				var data = eval("(" + json + ")");
				if (data.dir != '') {
					data.dir += "/";
				}
				data.dir += $(this).data('url');
				//$("div.gridParent#dir-explorer").data("required",JSON.stringify(data));

				loadDir(data);
			});

			/*
			 $("body").on('blur focusout ', '.rename', function(e) {
			 e.stopImmediatePropagation();
			 
			 var json = $(this).parents("div.gridParent").data("required");
			 json = eval("(" + json + ")");
			 var directory = json.dir;
			 var lastObject = $('table.fileGrid > tbody > tr.renaming');
			 
			 if ($(this).data('new') != 'yes') {
			 var old_name = $(this).data('old');
			 var new_name = $(this).val();
			 
			 if (new_name != '') {
			 var url = lastObject.data('url');
			 $(this).parent('td').append(new_name);
			 $(this).remove();
			 } else {
			 $(this).parent('td').append(old_name);
			 }
			 
			 $(this).parents('tr').removeClass('renaming');
			 $(this).remove();
			 parent.sceditor.call('base.saveas.rename()', {
			 'dir': directory,
			 'from': old_name,
			 'to': new_name,
			 });
			 } else if ($(this).data('new') == 'yes') {
			 var name = $(this).val();
			 var defaultName = lastObject.hasClass('directory') ? 'New Folder' : 'Untitled.txt';
			 
			 if (name != '') {
			 var url = lastObject.data('url');
			 $(this).parent('td').append(name);
			 $(this).remove();
			 } else {
			 $(this).parent('td').append(defaultName);
			 }
			 
			 var extClass = (getExtension(name) == '') ? ' directory ' : ' file ext_' + getExtension(name);
			 var type = (lastObject.hasClass('directory')) ? 'dir' : 'file';
			 $(this).parents('tr').removeClass('renaming').addClass(extClass);
			 $(this).remove();
			 
			 parent.sceditor.call('base.saveas.createNew()', {
			 'dir': directory,
			 'fileName': name,
			 'type': type,
			 })
			 }
			 });
			 
			 $("body").on('keydown', '.rename', function(e) {
			 var code = (e.keyCode ? e.keyCode : e.which);
			 
			 if (code == 13) {
			 $('.rename').trigger('blur');
			 }
			 });
			 */
			$("body").on("change", "#workspace", function() {
				var data = {
					'dir': $(this).val()
				};
				//$("#dir-explorer").data("required",JSON.stringify(data));
				loadDir(data);
			})

			//$("table.fileGrid tbody tr,.ftpconnfilescol_navtree").contextmenu(option);

			$("body").on('mousedown', 'table.fileGrid tbody>tr', function(e) {
				var code = (e.keyCode ? e.keyCode : e.which);
				if (!$(this).hasClass('renaming')) {
					$('.rename').trigger('blur');
				}

				lastObject = $(this);

				if (e.ctrlKey && code == 2) {
					if (isCopied == 0) {
						$(this).parents('table.fileGrid').find('tbody > tr').removeClass('list_selected').removeClass('list_hover');
					}
				} else {
					//if(!e.ctrlKey && code == 1){ 
					$(this).parents('table.fileGrid').find('tbody > tr').removeClass('list_selected').removeClass('list_hover');
					//}
				}

				if ($(this).hasClass('list_selected')) {
					if (!e.ctrlKey && code == 1) {
						$(this).removeClass('list_hover').removeClass('list_selected');
					}
				} else {
					$(this).removeClass('list_hover').addClass('list_selected');
				}
			})

			$(".ftpconnfileslist li i").click(function() {
				var object = $("div.gridParent");
				var json = object.data("required");
				json = eval("(" + json + ")");
				var directory = json.dir;
				
				switch ($(this).data('tool-action')) {

					case "refresh":
						var _obj = {'dir': directory};
						loadDir(_obj);

						break;

					case "delete":
						var createData = '';
						var index = 0;

						$.each(object.find("tbody tr.list_selected"), function() {
							index++;
                            createData = createData + "'" + index + "':{'fileUrl':'" + $(this).data('url') + "','dir':'" + directory + "', 'type':'" + ($(this).hasClass('directory') ? 'dir': '') + "'},";
						})
						deleteFiles(eval("({" + createData + "})"));

						break;

					case "history":
						var paths = '';
						var base = "";
						var toM = base.split("/");
						var newDir = directory;
						var dir = newDir.split("/");
						paths = base;

						if (dir.length > toM.length) {
							paths = RemoveLastDirectoryPartOf(newDir, 2);
						}

						var _obj = {
							'dir': paths,
						};

						loadDir(_obj);
						break;
				}
			})

			$(window).load(function() {
				$("table.dataTable").animate({opacity: 1}, 1000);
			})

			/* Connect to FTP */
                        setTimeout(function() {
                            $("#workspace").change();
                        }, 100);
		});

		function RemoveLastDirectoryPartOf(the_url, e) {
			try {
				var the_arr = the_url.replace(/([^:]\/)\/+/g, "$1").split('/');
				var newA = new Array();

				for (var i = 0; i < the_arr.length - e; i++) {
					newA.push(the_arr[i]);
				}

				return(newA.join('/'));
			} catch (ee) {
				return the_url.replace(/([^:]\/)\/+/g, "$1");
			}
		}

		/*
		 var old_name = null;
		 
		 function menuAction() {
		 var realObj = null;
		 realObj = $("div.last_object");
		 var json = realObj.data("required");
		 json = eval("(" + json + ")");
		 
		 if (json == null) {
		 realObj = $("div.last_object");
		 json = realObj.data("required");
		 json = eval("(" + json + ")");
		 }
		 
		 var directory = json.dir;
		 ;
		 var menuItem = $(this).find('span.contextmenuicon');
		 
		 switch (this.data.alias) {
		 case 'rename':
		 if (!menuItem.hasClass('b-m-idisable')) {
		 old_name = lastObject.find('td').first().html();
		 lastObject.addClass('renaming');
		 lastObject.find('td').first().empty().append('<input data-old="' + old_name + '" class="rename" type="text" value="' + old_name + '" />');
		 }
		 
		 break;
		 
		 case 'delete':
		 if (!menuItem.hasClass('b-m-idisable')) {
		 var createData = '';
		 var index = 0;
		 
		 $.each(realObj.find("tbody tr.list_selected"), function() {
		 index++;
        createData = createData + "'" + index + "':{'fileUrl':'" + $(this).data('url') + "','dir':'" + directory + "', 'type':'" + ($(this).hasClass('directory') ? 'dir': '') + "'},";
		 })
		 
		 createData = createData + '';
		 deleteFiles(eval("({" + createData + "})"));
		 }
		 
		 break;
		 
		 case 'newFile':
		 if (!menuItem.hasClass('b-m-idisable')) {
		 $("div.gridParent").find('tbody>tr').removeClass('list_selected')
		 realObj.find("tbody").append("<tr draggable='true' data-url='" + directory + "'/Untitled.txt' class='file list_selected renaming'><td><input data-new='yes' class='rename' type='text' value='Untitled.txt' /></td><td>0KB</td><td><?php echo date("d, M Y") ?></td></tr>");
		 }
		 
		 break;
		 
		 case 'newDirectory':
		 if (!menuItem.hasClass('b-m-idisable')) {
		 $("div.gridParent").find('tbody>tr').removeClass('list_selected')
		 realObj.find("tbody").append("<tr draggable='true' data-url='" + directory + "'/New Folder' class='directory list_selected renaming'><td><input data-new='yes' class='rename' type='text' value='New Folder' /></td><td>N/A</td><td><?php echo date("d, M Y") ?></td></tr>");
		 }
		 
		 break;
		 
		 case 'openFile':
		 if (!menuItem.hasClass('b-m-idisable')) {
		 $("div#dir-explorer").find("tbody > tr.list_selected.file").trigger('dblclick');
		 }
		 
		 break;
		 }
		 
		 $("table.fileGrid tbody tr,.ftpconnfilescol_navtree").contextmenu(option);
		 $(window).trigger('resize')
		 }
		 
		 function applyrule(menu) {
		 if (this.id == "target2") {
		 menu.applyrule({name: "target2",
		 disable: true,
		 items: ["1-2", "2-3", "2-4", "1-6"]
		 });
		 }
		 else {
		 menu.applyrule({name: "all",
		 disable: true,
		 items: []
		 });
		 }
		 }
		 
		 function BeforeContextMenu() {
		 return this.id != "target3";
		 }
		 */

		function loadDir(data) {
			data.type = "dir";
			$.post('<?php echo base_url(); ?>file/dir2json', data, function(data) {
				updateDirExplorer(data);
			});
		}
		
		function deleteFiles(e) {
			$.post('<?php echo base_url(); ?>file/deleteFiles/', e, function(data) {
				loadDir({'dir': e['1'].dir});
			})
		}
		
		function updateDirExplorer(e) {
			try {
				var array = eval("(" + e + ")");
                array = array.files;
				dirGrid.fnClearTable();

				$.each(array, function(key, data) {
					if (key != 'details' && (getFileName(data.fileUrl).charAt(0) != '.' && $.trim(getFileName(data.fileUrl)) != '.' && $.trim(getFileName(data.fileUrl)) != '..')) {
						var ext = 'file ext_' + getExtension(data.fileUrl);

						if (getExtension(data.fileUrl) == '') {
							ext = 'directory';
							var str = "<tr data-url='" + data.fileUrl + "' draggable='true' class='" + ext + "'><td>" + getFileName(data.fileUrl) + "</td><td>" + data.fileSize + "</td><td>" + data.lastModifiedDate + "</td></tr>";

							dirGrid.fnAddTr($(str)[0]);
						}
					}
				})

				$.each(array, function(key, data) {
					if (key != 'details' && (getFileName(data.fileUrl).charAt(0) != '.' && $.trim(getFileName(data.fileUrl)) != '.' && $.trim(getFileName(data.fileUrl)) != '..')) {
						var ext = 'file ext_' + getExtension(data.fileUrl);

						if (getExtension(data.fileUrl) != '') {
							var str = "<tr data-url='" + data.fileUrl + "' draggable='true' class='" + ext + "'><td>" + getFileName(data.fileUrl) + "</td><td>" + data.fileSize + "</td><td>" + data.lastModifiedDate + "</td></tr>";

							dirGrid.fnAddTr($(str)[0]);
						}
					}
				})

				//console.log(dirGrid);
				var str = "{'dir':'" + remove_slashes(array['details'].dir) + "'}";
				$("div#dir-explorer.gridParent").data('required', str);

			} catch (e) {
				console.log(e)
			}

			//$("table.fileGrid tbody tr,.ftpconnfilescol_navtree").contextmenu(option);
			$(window).trigger('resize');
		}

		function remove_slashes(e) {
			return e.replace(/([^:]\/)\/+/g, "$1")
		}

		function connectionAction() {
			$(".connection_info > i").removeClass('green').toggleClass("background");
		}

		function getExtension(e) {
			try {
				var page = e.substring(e.lastIndexOf('/') + 1);
				var re = /(?:\.([^.]+))?$/;
				return $.trim(re.exec(page)[1]);
			} catch (e) {
				return '';
			}
		}

		function getFileName(e) {
			try {
				var nameArray = unescape(e).split('/');
				return nameArray[nameArray.length - 1];
			} catch (e) {
				return e;
			}
		}

		function extract() {
			$("div.gridParent").find("tbody > tr.copied").length;
			var json = $("div.gridParent").data("required");
			json = eval("(" + json + ")");
			var directory = json.dir;

			parent.sceditor.call('base.file.extractInto()', {
				'dir': directory
			}, POPUP_ID);
		}

		function close() {
			parent.sceditor.call("base.closePopup()", {}, POPUP_ID);
		}

		function create_new_workspace() {
			var name = $("#new_workspace").val();
			name = $.trim(name);
			
			if (name == '') {
                                parent.sceditor.call("base.notify()", {msg: 'Please enter the workspace name.', 'type': 'error'});
				return;
			}
			
			var nameValidator = /^[A-Za-z0-9']{0,100}$/;
			
			if (!nameValidator.test(name)) {
                                parent.sceditor.call("base.notify()", {msg: 'You may only use letters and numbers.', 'type': 'error'});
				return;
			}
			
			var e = {name: name};
			$.ajax({
				type: "POST",
				url: "<?php echo base_url(); ?>workspace/create",
				dataType: "json",
				data: e
			}).done(function(data) {
				var status = data.status;
				
				if (status == true) {
					var ws = data.ws;
					$("#workspace").find("option").attr('selected', false);
					$("<option selected='selected' value='" + ws.ws_directory + "/'>" + ws.ws_name + "</option>").appendTo("#workspace");
					$("#workspace").change();
					parent.sceditor.call("base.workspace.changed()");
				} else {
                    parent.sceditor.call("base.notify()", {msg: 'Unknow error', 'type': 'error'});
					return;
				}

				//console.log(msg)
			}).error(function(msg) {
				console.log(msg);
			})
		}
	</script>

	<style>
		html, body {
			width: 100%;
			height: 100%;
		}

		* {
			-webkit-box-sizing: border-box;
			-moz-box-sizing: 	border-box;
			box-sizing: 		border-box;
		}

		body {
			margin: 0;
			padding: 13px 13px 0;
		}

		.background {
			color: #f00 !important;
		}
		.green {
			color: #063 !important;
		}

		.connection_info > i{
			-webkit-transition: background 1.0s ease-in-out;
			-moz-transition:    background 1.0s ease-in-out;
			-ms-transition:     background 1.0s ease-in-out;
			transition:         background 1.0s ease-in-out;
		}

		div.ftpconn {}

		/* FTP Connection Details */
		div.ftpconn_details {
			margin: 0 0 10px;
			border-bottom: 1px solid #e9e9e9;
		}
		div.ftpconndetails_inside {
			padding: 0 0 10px;
			border-bottom: 1px solid #bebebe;
		}
		div.ftpconndetails_col {
			float: left;
		}
		div.ftpconndetails_col.host {
			width: 25%;
		}
		div.ftpconndetails_col.user {
			width: 25%;
		}
		div.ftpconndetails_col.pwd {
			width: 25%;
		}
		div.ftpconndetails_col.port {
			width: 10%;
		}
		div.ftpconndetails_col.type {
			width: 15%;
		}

		div.ftprow_inside {
			padding: 5px;
		}
		div.ftpconndetails_col.host div.ftprow_inside { padding-left: 0; }
		div.ftpconndetails_col.type div.ftprow_inside { padding-right: 0; }

		div.ftpconndetails_col label {
			padding: 0 0 5px;
			display: block;
			width: 100%;
		}
		div.ftpconndetails_col div.infopopupfield {
			float: none;
			width: 100%;
		}

		/* Fields */
		div.ftpconndetails_col input.ftptext,
		div.ftpconndetails_col select.ftpselect {
			width: 100%;
		}

		/* Buttons */
		input.button {
			margin: 0 0 0 7px;
			padding: 6px 15px;
			width: 100%;
			color: #575757;
			font-size: 12px;
			text-transform: uppercase;
			cursor: pointer;
			border: 1px solid #ababab;
			background: -moz-linear-gradient(center top , #e5e5e5 0%, #f0f0f0 100%) repeat scroll 0 0 rgba(0, 0, 0, 0);

			text-shadow: 1px 1px 0 rgba(255, 255, 255, 0.2);
			border-radius: 3px;
			border-radius: 3px;
			border-radius: 3px;
			-webkit-box-sizing: border-box;
			-moz-box-sizing: 	border-box;
			box-sizing: 		border-box;
			-webkit-transition: border-color 0.3s ease-in-out,
				-webkit-box-shadow 0.3s ease-in-out;
			-moz-transition: 	border-color 0.3s ease-in-out,
				-moz-box-shadow 0.3s ease-in-out;
			-ms-transition: 	border-color 0.3s ease-in-out,
				-ms-box-shadow 0.3s ease-in-out;
			-o-transition: 		border-color 0.3s ease-in-out,
				-o-box-shadow 0.3s ease-in-out;
			transition: 		border-color 0.3s ease-in-out,
				box-shadow 0.3s ease-in-out;
		}
		input.button.saveftp {}

		/* FTP Files */
		div.ftpconn_files {}
		div.ftpconnfiles_inside {}

		/* FTP Connection Files Columns */
		div.ftpconnfiles_col {
			width: 48%;
			/*border: 1px solid #e9e9e9;*/

			-webkit-box-sizing: border-box;
			-moz-box-sizing: 	border-box;
			box-sizing: 		border-box;
		}
		div.ftpconnfiles_col.left 	{ float: left; }
		div.ftpconnfiles_col.middle	{ float: left; }
		div.ftpconnfiles_col.right 	{ float: right; }

		div.ftpconnfiles_col.middle { width: 4%; border-width: 0; }

		div.ftpconnfilescol_inside {
			/*border: 1px solid #bebebe;*/
		}
		div.ftpconnfiles_col.middle div.ftpconnfilescol_inside { border-width: 0; }

		/* Controls */
		div.ftpconnfiles_controls {
			/* border-bottom: 1px solid #e9e9e9; */
		}
		ul.ftpconnfileslist {
			margin: 0;
			padding: 0;
		}
		ul.ftpconnfileslist li {
			margin: 0;
			padding: 0;
			display: block;
			float: left;
		}

		/* Control Item */
		ul.ftpconnfileslist li.ftpconnfilecontrol {
			margin: 0 10px 0 0;
		}
		ul.ftpconnfileslist li.ftpconnfilecontrol.folder {
			margin-left: 5px;
		}
		ul.ftpconnfileslist li.ftpconnfilecontrol a {
			color: #131313;

			-webkit-transition: color 0.3s ease-in-out;
			-moz-transition: 	color 0.3s ease-in-out;
			-ms-transition: 	color 0.3s ease-in-out;
			-o-transition: 		color 0.3s ease-in-out;
			transition: 		color 0.3s ease-in-out;
		}
		ul.ftpconnfileslist li.ftpconnfilecontrol:hover a {
			color: #f5b53c;
		}

		/* Server Info */
		ul.ftpconnfileslist li.serverinfo {
			float: right;
		}

		/* FTP Transfer Direction */
		div.ftpconnfilescol_filenav {
			height: 160px;
			margin-top:70px;
		}
		ul.ftptransferdireclist {
			margin: 0;
			padding: 0;
			list-style-type: none;
		}
		li.ftptransfer {
			padding: 0 10px;
			display: block;
			width: 100%;
			font-size: 30px;
			font-weight: normal;
			text-align: center;

			-webkit-box-sizing: border-box;
			-moz-box-sizing: 	border-box;
			box-sizing: 		border-box;
		}
		li.ftptransfer.left {}
		li.ftptransfer.right {}

		li.ftptransfer a {
			margin: 0 0 10px;
			padding: 5px;
			display: block;
			color: #787878;

			-webkit-transition: color 0.3s ease-in-out;
			-moz-transition: 	color 0.3s ease-in-out;
			-ms-transition: 	color 0.3s ease-in-out;
			-o-transition: 		color 0.3s ease-in-out;
			transition: 		color 0.3s ease-in-out;
		}
		li.ftptransfer.right a { margin-top: 35px; }
		li.ftptransfer a:hover {}

		/* File Tree */
		div.ftpconnfilescol_navtree {
			height: 200px;
			/*background-color: #d0d0d0;*/
			/*
			overflow-x: hidden;
			overflow-y: scroll;
			margin: 0;
			padding: 5px !important;
			*/
		}
		div.dataTables_scroll { position: relative; height: 100%; }
		div.dataTables_scrollHead {
			position: absolute;
			top: 0;
			left: 0;
			width: 100%;
		}
		div.dataTables_scrollBody {
			/*
			position: relative;
			padding: 0;
			height: 91% !important;
			*/
		}
		.dataTables_wrapper.no-footer .dataTables_scrollBody {
			border-bottom: 0 none;
		}

		/* FTP Log */
		div.ftpconn_log {}
		div.ftpconnlog_inside {}

		/* FTP Log Textarea */
		textarea.ftpconnlog,div.ftpconnlog {
			padding: 5px 8px;
			width: 100%;
			/* height: 170px; */
			height: 100%;
			color: #000;
			font-family: Monospace;
			font-size: 11px;
			/*border: 1px solid #bebebe;*/
			background-color: #fff;

			-webkit-box-sizing: border-box;
			-moz-box-sizing: 	border-box;
			box-sizing: 		border-box;
			overflow-y: scroll;
		}
		textarea.ftpconnlog:hover {}

		div.ftpconnlog span {
			width: 100%;
			height: auto;
			float: left;
		}

		div.ftpconnlog .warning {
			color: #0F0;

		}
		div.ftpconnlog .error {
			color: #F00;

		}
		div.ftpconnlog .success {
			color: #360;
		}

		ul.jqueryFileTree:first-child {
			padding: 0 !important;
		}
		ul.jqueryFileTree {
			min-height: 100%;
		}
		ul.jqueryFileTree li {
			background-position: 5px 3px !important;
			cursor: pointer;
			float: left;
			padding-bottom: 4px;
			padding-top: 3px;
			width: 94%;
			border: 1px dashed transparent;
			margin-bottom: 2px;

			-webkit-border-radius: 	3px;
			-moz-border-radius: 	3px;
			border-radius: 			3px;
		}
		ul.jqueryFileTree a:hover {
			background-color: transparent;
		}

		table.dataTable.row-border tbody th,
		table.dataTable.row-border tbody td,
		table.dataTable.display tbody th,
		table.dataTable.display tbody td {
			/*border-top: 1px solid #fff;
			border-bottom: 1px solid #ddd;*/
		}
		table.dataTable tbody tr.list_selected td {
			background-color: #bdf !important;
		}
		table.dataTable tbody tr.list_selected td:last-child {}
		table.dataTable tbody tr.list_selected td:first-child {}

		table.dataTable tbody tr.copied td {
			background-color: #ccc !important;
		}
		table.dataTable tbody tr.copied td:last-child {
			border-bottom-right-radius: 3px !important;
			border-top-right-radius: 3px !important;
			border-right: 1px dashed #999 !important;
		}
		table.dataTable tbody tr.copied td:first-child {
			border-bottom-left-radius: 3px!important;
			border-top-left-radius: 3px!important;
			border-left: 1px dashed #999!important;
		}

		table.dataTable tbody tr.sortable-placeholder td {
			border-top: 1px dashed #f00;
			border-bottom: 1px dashed #f00;
			height: 17px;
		}
		table.dataTable tbody tr.sortable-placeholder td:first-child {
			border-left: 1px dashed #f00;
			height: 17px;
		}
		table.dataTable tbody tr.sortable-placeholder td:last-child {
			border-right: 1px dashed #f00;
			height: 17px;
		}
		tbody tr td span{
			visibility:hidden;
		}

		ul.jqueryFileTree > li.copied {
			background-color: #ccc;
			border: 1px dashed #999;

			-webkit-border-radius: 	3px;
			-moz-border-radius: 	3px;
			border-radius: 			3px;
		}
		ul.jqueryFileTree > li.list_hover {
			background-color: #cfc;
			border: 1px dashed #fff;

			-webkit-border-radius: 	3px;
			-moz-border-radius: 	3px;
			border-radius: 			3px;
		}

		#cmroot > .disable,#cmroot > .b-m-idisable {
			background-color: #f00;
		}
		ul.jqueryFileTree li input.rename {
			border: 0;
			width: auto;
			float: left;
		}
		table.dataTable {
			table-layout: fixed !important;
		}
		.dataTables_scrollBody {
			overflow-x: hidden !important;
		}
		.dataTables_scrollBody {
			/* height: 100% !important; */
		}
		.connection_info {
			float:right !important;
			float: right !important;
			padding-left: 7px !important;
			padding-right: 2px !important;
		}
		.connection_info i {
			color: #d0d0d0;
		}

		#workspace {
			/* position: absolute; */
			margin-top: -3px;
			}
		.text {
			border: 1px solid #a9a9a9;
			box-sizing: border-box;
			color: #353535;
			font-size: 13px;
			height: 30px;
			margin: 0;
			padding: 5px;
			transition: border-color 0.3s ease-in-out 0s, box-shadow 0.3s ease-in-out 0s;
			width: 100%;

			-webkit-border-radius: 	3px;
			-moz-border-radius: 	3px;
			border-radius: 			3px;
			}
	</style>
</head>
<body data-width='600' data-height="310" data-controls="{'Cancel' : 'close','Extract' : 'extract'}" style="height: 100%;">
	<div class="infopopup" style="height: 100%;">
		<form id="" class="" style="height: 100%;">
			<div class="infooptionscontainer" style="padding-right: 0; height: 100%;"> <!-- -->
				<div class="infopopupoptions" style="height: 100%;">
					<div class="infopopupoptrow first" style="padding: 0; height: 100%;">
						
						<!-- FTP: Start -->
						<div class="ftpconn" style="position: relative; height: 100%;">
							
							<!-- FTP Files: Start -->
							<div style="position: relative; height: 100%;">
								<div class="ftpconnfiles_inside" style="height: 100%;">
									<!--
									<div style="float:left; margin-bottom:5px; width:100%; height:auto;">
										File Name: 
										<input id="file_name" 
											   class="text" 
											   style="width:357px;" 
											   type="text" 
											   value="<?php echo isset($_GET['filename']) ? $_GET['filename'] : ''; ?>" />
									</div>
									-->
									
									<!-- Left Column: Start -->
									<div class="ftpconnfiles_col left" style="height: auto; width: 100%;">
										<div class="ftpconnfilescol_inside" style="position: relative; height: 255px">
											<div class="ftpconnfiles_controls" style="position: absolute; top: 0; left: 0; z-index: 10; width: 100%;">
												<div class="ftpconnfilescontrols_inside">
													<ul class="ftpconnfileslist">
														<li class="ftpconnfilecontrol delete">
															<a href="#"><span><i class="fa fa-trash-o" data-tool-action='delete'></i></span></a>
														</li>
														<li class="ftpconnfilecontrol refresh">
															<a href="#"><span><i class="fa fa-refresh" data-tool-action='refresh'></i></span></a>
														</li>
														<li class="ftpconnfilecontrol history">
															<a href="#"><span><i class="fa fa-level-up" data-tool-action='history'></i></span></a>
														</li>
														<?php
														$dbwc = (int) get_user_feature('work_space');
														$_ws = get_option('ws');
														$_ws = json_decode($_ws, true);
														
														$index = 0;
														if (is_array($_ws) && sizeof($_ws) > 0) {
															?>
															<li class="ftpconnfilecontrol workspace-list">
																<select name="workspace" id="workspace">
																	<?php
																	foreach ($_ws as $key => $ws) {
																		if ($ws['ws_status'] == 'enable') {
																			?>
																			<option value="<?php echo $key; ?>/" <?php echo ($ws['ws_active'] == 'true') ? 'selected="selected"' : ''; ?>><?php echo $ws['ws_name']; ?></option>
																			<?php
																		}
																	}
																	?>
																</select>
															</li>
															<?php
														}
														?>
														<li class="ftpconnfilecontrol new-workspace">
															<input type="text" name="new_workspace" id="new_workspace" />
															<input type="button" value="New Workspace" onclick="create_new_workspace()" />
														</li>
													</ul>
													
													<div class="clear"></div>
												</div>
												
												<div class="clear"></div>
											</div>
											
											<div class="ftpconnfilescol_navtree gridParent" id="dir-explorer" style="height: 100%;">
												<table class="display fileGrid" cellspacing="0" width="100%">
													<thead>
														<tr>
															<th class="name">Name</th>
															<th class="size">Size</th>
															<th class="date">Last Modified</th>
														</tr>
													</thead>
													<tbody>
													</tbody>
												</table>
												
												<div class="clear"></div>
											</div>
											
											<div class="clear"></div>
										</div>
										
										<div class="clear"></div>
									</div>
									<!-- Left Column: End -->
									
									<!-- Center Column: Start -->
									
									<!-- Center Column: End -->
								</div>
								
								<div class="clear"></div>
							</div>
							<!-- FTP Files: End -->
							
							<!-- FTP Log: Start -->
							
							<!-- FTP Log: End -->
							
							<div class="clear"></div>
						</div>
						<!-- FTP: End -->
						
						<div class="clear"></div>
					</div>
					
					<div class="clear"></div>
				</div>
				
				<div class="clear"></div>
			</div>
		</form>
		
		<div class="clear"></div>
	</div>
</body>
</html>