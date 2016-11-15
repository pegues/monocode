<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
	<title>FTP Connection</title>
	
	<link href="<?php echo base_url(); ?>core/css/popupcontent.css" rel="stylesheet" media="all" />
    <link href="<?php echo base_url(); ?>core/css/jqueryFileTree.css" rel="stylesheet" media="all" />
	<link href="<?php echo base_url(); ?>core/css/fontawesome/font-awesome.css" rel="stylesheet" media="all" />
    <link href="<?php echo base_url(); ?>core/css/contextmenu.css" rel="stylesheet" media="all" />
	<link href="<?php echo base_url(); ?>core/css/jquery.dataTables.css" rel="stylesheet" media="all" />
	<link href="<?php echo base_url(); ?>themes/editor/<?php echo get_option("editor_page_theme") ?>/theme.css" rel="stylesheet" data-default-theme="<?php echo get_option("editor_page_theme") ?>" media="all" />
	
	<script src="<?php echo base_url(); ?>core/js/jquery-2.1.3.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>core/js/jquery-ui.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>core/js/contextmenu.js"></script>
    <script src="<?php echo base_url(); ?>core/js/jquery.dataTables.min.js"></script>
	
	<script type="text/javascript">
	var lastObject 	= null;
	var fileGrid 	= null;
	
	var option = { width: 190, items: [
		{ text: "New File", icon: "sample-css/ei0021-16.gif", alias: "newFile", action: menuAction },
		{ text: "New Directory", icon: "sample-css/ei0021-16.gif", alias: "newDirectory", action: menuAction },
		/*
		{ text: "Cut", icon: "sample-css/wi0126-16.gif", alias: "cut", action: menuAction },
		{ text: "Copy", icon: "sample-css/ac0036-16.gif", alias: "copy", action: menuAction },
		{ text: "Paste", icon: "sample-css/ei0021-16.gif", alias: "past", action: menuAction },
		*/
		{ text: "Rename", icon: "sample-css/ei0021-16.gif", alias: "rename", action: menuAction },
		{ text: "Delete", icon: "sample-css/ei0021-16.gif", alias: "delete", action: menuAction },
		{ text: "Edit File", icon: "sample-css/ei0021-16.gif", alias: "onenFile", action: menuAction },
	], onShow: applyrule,
	onContextMenu: BeforeContextMenu
	};
	
	var options = {
		"paging" 			: false,
		"ordering" 			: true,
		"info" 				: false,
		"bFilter" 			: false,
		"aoColumns"			: [
			{ "sWidth" : "57%" },
			{ "sWidth" : "18%" },
			{ "sWidth" : "25%" }
		],
		"scrollCollapse" 	: true,
		"scrollY"			:"100% !important",
                "aaSorting": [[1, '']],
                "oLanguage": {
                    "sEmptyTable": "Empty"
                }
	}
	
	var localFileGrid 	= null;
	var remoteFileGrid 	= null;
	var timer 			= null;
	
	$(document).ready(function(e) {
		localFileGrid 	= $('div#local > table.fileGrid').dataTable(options);
		remoteFileGrid 	= $('div#remote > table.fileGrid').dataTable(options);
		
		$('body').css({'overflow' : 'hidden'}).addClass('ftp');
		$('body').on('dblclick','#remote tbody > tr.directory',function(){
			var json	= $(this).parents("div#remote.gridParent").data("required");
			var data 	= eval("(" + json + ")");
			
			if (data.dir != '') {
				data.dir += "/";
			}
			
			data.dir += $(this).data('url');
			parent.sceditor.call("base.ftp.reconnectServer()", data);
		});
		
		$('body').on('dblclick','#local tbody > tr.directory',function(){
			var json = $("div#local.gridParent").data("required");
			var data = eval("(" + json + ")");
			
			if (data.dir != '') {
				data.dir += "/";
			}
			
			data.dir += $(this).data('url');
			parent.sceditor.call("base.ftp.reconnectLocal()", data);
		});
		
		$('body').on('dblclick','#local tbody > tr.file',function(){
			var json = $("div#local.gridParent").data("required");
			var data = eval("(" + json + ")");
			var dir = data.dir;
			
			if (dir != '') {
				dir += "/";
			}
			
			var obj = $(this);
			var name = obj.data('url');
			var file = {id: obj.data('id'), url: dir + name, name: name};
			parent.sceditor.call("base.ftp.openLocal()", file);
		});
		
		$('body').on('dblclick','#remote tbody > tr.file',function(){
			var json	= $(this).parents("div.gridParent").data("required");
			json		= eval("(" + json + ")");
			var newjson	= "{'dir':'" + json.dir + "','fileUrl':'" + $(this).data('url') + "','ftp_log_id':'" + json.ftp_log_id + "'}";
			
			parent.sceditor.call('base.ftp.editRemoteFile()',eval("(" + newjson + ")"));
		});
		
		$("body").on('blur focusout ','.rename',function(e){
			e.stopImmediatePropagation();
			
			var json		= $(this).parents("div.gridParent").data("required");
			json			= eval("(" + json + ")");
			var server		= json.server;
			var ftp_log_id	= json.ftp_log_id;
			var directory	= json.dir;
			var lastObject 	= $('table.fileGrid > tbody > tr.renaming');

			if($(this).data('new') != 'yes'){
				var old_name = $(this).data('old');
				var new_name = $(this).val();
				
				if(new_name != ''){
					var url = lastObject.data('url');
					$(this).parent('td').append(new_name);
					$(this).remove();
				}else{
					$(this).parent('td').append(old_name);
				}
				
				$(this).parents('tr').removeClass('renaming');
				$(this).remove();
				parent.sceditor.call('base.ftp.rename()',{'dir':directory,'from':old_name,'to':new_name,'server':server,'ftp_log_id':ftp_log_id});
			}else if($(this).data('new') == 'yes'){
				var name		= $(this).val();
				var defaultName	= lastObject.hasClass('directory') ? 'New Folder' : 'Untitled.txt';
				
				if(name != ''){
					var url=lastObject.data('url');
					$(this).parent('td').append(name);
					$(this).remove();
				}else{
					$(this).parent('td').append(defaultName);
				}
				
				var extClass 	= (getExtension(name)=='') ? ' directory ' : ' file ext_' + getExtension(name);
				var type 		= (lastObject.hasClass('directory')) ? 'dir' : 'file';
				
				$(this).parents('tr').removeClass('renaming').addClass(extClass);
				$(this).remove();
				parent.sceditor.call('base.ftp.createNew()',{'dir':directory,'name':name,'type':type,'server':server,'ftp_log_id':ftp_log_id})
			}
		});
		
		$("body").on('keydown','.rename',function(e){
			var code = (e.keyCode ? e.keyCode : e.which);
			
			if(code == 13){
				$('.rename').trigger('blur');
			}
		});
		
		$("body").on("change","#workspace",function(){
			var json = $("div#local.gridParent").data("required");
			var data = eval("(" + json + ")");
			data.dir = $(this).val();

			parent.sceditor.call("base.ftp.reconnectLocal()", data);
		});
		
		$("body").on('dblclick','table#remote tbody tr.file',function(e){
			e.preventDefault();
			
			if($("table#remote").find("tbody tr.list_selected").length == 1){
				var remote_directory 	= $("table#remote").data('current-directory');
				var fileUrl 			= $(this).data('url');
				var ftp_log_id 			= $(this).data('ftp-log-id');
				var json 				= "{'dir':'" + remote_directory + "','fileUrl':'" + fileUrl + "','ftp_log_id':'" + ftp_log_id + "'}";
				
				parent.sceditor.call('base.ftp.editRemoteFile()',eval("(" + json + ")"));
			}else{
				parent.sceditor.call("base.notify()", {msg: 'Please select only one file', 'type': 'warning'});
			}
		});
		
//		$(".fileGrid").on("sortreceive", function( event, ui ) {
//			var json			= ui.sender.parents("div.gridParent").data("required");
//			json				= eval("(" + json + ")");
//			var from			= json.server;
//			var to				= (from == 'remote') ? 'local' : 'remote';
//			var ftp_log_id		= json.ftp_log_id;
//			var fileUrl			= ui.item.data('url');
//			var copy_directory	= json.dir;
//			var json1 			= $("div#local.gridParent").data("required");
//			var data1 			= eval("(" + json1 + ")");
//			var json			= $("#" + to).data("required");
//			json				= eval("(" + json + ")");
//			var past_directory	= json.dir;
//			
//			var _object			= "{'1':{'from':'" + from + "','copy_directory':'" + copy_directory + "','fileUrl':'" + fileUrl + "','ftp_log_id':'" + ftp_log_id + "','past_directory':'" + past_directory + "','to':'" + to + "'}}";
//			
//			parent.sceditor.call('base.ftp.past()',eval("(" + _object + ")"));
//		});
		
//		$("tbody").sortable({
//			items		: "tr",
//			connectWith	: "tbody",
//			placeholder	: "sortable-placeholder",
//			helper		: "clone",
//			start		: function(event,ui){
//				saveMe 			= $(ui.item).clone();
//				startingList 	= $(ui.item).parent();
//				
//				$(ui.item).show();
//				saveMe.insertBefore($(ui.item)).hide();
//			},
//			stop		: function(event, ui){
//				if(startingList.attr("id") == $(ui.item).parent().attr("id") ) {
//					saveMe.remove();
//				} else {
//					saveMe.show();
//				}
//			},
//			sort: function(event, ui) {
//			}
//		});
		
		$("div.gridParent#local").droppable({
			addClasses: false,
			scope: "remote-files",
			tolerance: "pointer",
			over: function (event, ui) {
				$('body').addClass('has-droppable');
			},
			drop: function (event, ui) {
				$('body').removeClass('has-droppable');
					var json			= ui.draggable.parents("div.gridParent").data("required");
					json				= eval("(" + json + ")");
					var from			= json.server;
					var to				= (from == 'remote') ? 'local' : 'remote';
					var ftp_log_id		= json.ftp_log_id;
					var fileUrl			= ui.draggable.data('url');
					var copy_directory	= json.dir;
					var json1 			= $("div#local.gridParent").data("required");
					var data1 			= eval("(" + json1 + ")");
					var json			= $("#" + to).data("required");
					json				= eval("(" + json + ")");
					var past_directory	= json.dir;
					
					var _object			= "{'1':{'from':'" + from + "','copy_directory':'" + copy_directory + "','fileUrl':'" + fileUrl + "','ftp_log_id':'" + ftp_log_id + "','past_directory':'" + past_directory + "','to':'" + to + "'}}";
					parent.sceditor.call('base.ftp.past()',eval("(" + _object + ")"));
				},
				out: function(event, ui) {
					$('body').removeClass('has-droppable');
				}
			});
			
			$("div.gridParent#remote").droppable({
				addClasses: false,
				scope: "local-files",
				tolerance: "pointer",
				over: function (event, ui) {
					$('body').addClass('has-droppable');
				},
				drop: function (event, ui) {
					$('body').removeClass('has-droppable');
					var json			= ui.draggable.parents("div.gridParent").data("required");
					json				= eval("(" + json + ")");
					var from			= json.server;
					var to				= (from == 'remote') ? 'local' : 'remote';
					var ftp_log_id		= json.ftp_log_id;
					var fileUrl			= ui.draggable.data('url');
					var copy_directory	= json.dir;
					var json1 			= $("div#local.gridParent").data("required");
					var data1 			= eval("(" + json1 + ")");
					var json			= $("#" + to).data("required");
					json				= eval("(" + json + ")");
					var past_directory	= json.dir;
					
					var _object			= "{'1':{'from':'" + from + "','copy_directory':'" + copy_directory + "','fileUrl':'" + fileUrl + "','ftp_log_id':'" + ftp_log_id + "','past_directory':'" + past_directory + "','to':'" + to + "'}}";
					parent.sceditor.call('base.ftp.past()',eval("(" + _object + ")"));
				},
				out: function(event, ui) {
					$('body').removeClass('has-droppable');
				}
			});
			
			$("table.fileGrid tbody tr,.ftpconnfilescol_navtree").contextmenu(option);
			
			$("body").on('mousedown','div.gridParent',function(e){
				var isChilds 	= $(this).find('tbody > tr').length;
				var isCopied 	= $("table.fileGrid").find('tbody>tr.copied').length;
				var code 		= (e.keyCode ? e.keyCode : e.which);
				
				if(code == 3){
					$("div.gridParent").removeClass('past').removeClass('last_object')
					$(this).addClass('past last_object');
				
					if(isCopied > 0){
						var enablePast = false;
						var isCopied = $("table.fileGrid").find('tbody > li.copied').length;
						
						if(isCopied > 0){
							if(code != 1){
								enablePast=true;
							}else{
								$("table.fileGrid").find('tbody > tr').removeClass('copied')
							}
						}
						
						if(isChilds == 0){
							$("#cmroot").find("[data-alias]").removeClass('b-m-idisable');
							$("#cmroot").find("[data-alias='cut']").addClass('b-m-idisable');
							$("#cmroot").find("[data-alias='copy']").addClass('b-m-idisable');
							
							if(enablePast == false){
								$("#cmroot").find("[data-alias='past']").addClass('b-m-idisable');
							}
							
							$("#cmroot").find("[data-alias='rename']").addClass('b-m-idisable');
							$("#cmroot").find("[data-alias='onenFile']").addClass('b-m-idisable');
							$("#cmroot").find("[data-alias='delete']").addClass('b-m-idisable');
						}
					}else{
						if(isChilds == 0){
							$("#cmroot").find("[data-alias]").removeClass('b-m-idisable');
							$("#cmroot").find("[data-alias='cut']").addClass('b-m-idisable');
							$("#cmroot").find("[data-alias='copy']").addClass('b-m-idisable');
							$("#cmroot").find("[data-alias='past']").addClass('b-m-idisable');
							$("#cmroot").find("[data-alias='rename']").addClass('b-m-idisable');
							$("#cmroot").find("[data-alias='onenFile']").addClass('b-m-idisable');
							$("#cmroot").find("[data-alias='delete']").addClass('b-m-idisable');
						}
					}
				}
			});
			
			$("body").on('mousedown','table.fileGrid tbody > tr',function(e){
				var code 		= (e.keyCode ? e.keyCode : e.which);
				var enablePast	= false
				var isCopied	= $("table.fileGrid").find('tbody > tr.copied').length;
				
				if(isCopied > 0){
					if(code != 1){
						enablePast=true;
					}else{
						$("table.fileGrid").find('tbody > tr').removeClass('copied')
					}
				}
				
				if(!$(this).hasClass('renaming')){
					$('.rename').trigger('blur');
				}
				
				lastObject=$(this);
				
				if(e.ctrlKey && code == 2){
					if(isCopied==0){
						$(this).parents('table.fileGrid').find('tbody > tr').removeClass('list_selected').removeClass('list_hover');
					}
				}else{ 
					//if(!e.ctrlKey && code == 1){ 
					$(this).parents('table.fileGrid').find('tbody > tr').removeClass('list_selected').removeClass('list_hover'); 
					//}
				}
				
				if($(this).hasClass('list_selected')){
					if(!e.ctrlKey && code == 1){
						$(this).removeClass('list_hover').removeClass('list_selected');
					}
				}else{
					if(isCopied > 0 && code == 3){
						$(this).removeClass('list_hover')
					}else{
						$(this).removeClass('list_hover').addClass('list_selected');
					}
				}
				
				if($(this).hasClass('file')){
					$("#cmroot").find("[data-alias]").removeClass('b-m-idisable');
					
					if(enablePast == false){
						$("#cmroot").find("[data-alias='past']").addClass('b-m-idisable');
					}
				}else if($(this).hasClass('directory')){
					$("#cmroot").find("[data-alias]").removeClass('b-m-idisable');
					$("#cmroot").find("[data-alias='onenFile']").addClass('b-m-idisable');
					
					if(enablePast == false){
						$("#cmroot").find("[data-alias='past']").addClass('b-m-idisable');
					}
				}
			});
		
			$(".ftp_upload").click(function(){
				$(".ftpconnfiles_col.left li.upload i").trigger("click");
			});
			
			$(".ftp_download").click(function(){
				$(".ftpconnfiles_col.right li.upload i").trigger("click");
			});
			
			$(".ftpconnfileslist li i").click(function(){
				var object 		= $(this).parents("div.ftpconnfiles_col").find("div.gridParent");
				var json		= object.data("required");
				json			= eval("(" + json + ")");
				var server		= json.server;
				var ftp_log_id	= json.ftp_log_id;
				var directory	= json.dir;
				
				switch($(this).data('tool-action')){
					case "openFolder":
						object.find("tbody > tr.list_selected").first().trigger('dblclick');
						
						break;
					case "refresh":
						if (json.server == 'remote') {
							parent.sceditor.call("base.ftp.reconnectServer()", json);
						} else {
							parent.sceditor.call("base.ftp.reconnectLocal()", json);
						}
						
						break;
					case "delete":
						var createData 	= '';
						var index 		= 0;
						
						$.each(object.find("tbody tr.list_selected"),function(){
							index++;
							createData += "'" + index + "':{'fileUrl':'" + $(this).data('url') + "','ftp_log_id':'" + ftp_log_id + "','dir':'" + directory + "','server':'" + server + "', 'type':'" + ($(this).hasClass('directory') ? 'dir': '') + "'},";
						});
						
						parent.sceditor.call("base.ftp.deleteFiles()",eval("({" + createData + "})"));
						
						break;
					case "upload":
						var index = 0;
						var _object='';
						var to='';
						
						$.each(object.find("tbody tr.list_selected"),function(){
							index++;
							var json			= object.data("required");
							json				= eval("(" + json + ")");
							var from			= json.server;
							to					= (from == 'remote') ? 'local' : 'remote';
							var ftp_log_id		= json.ftp_log_id;
							var fileUrl			= $(this).data('url');
							var copy_directory	= json.dir;
							var json			= $("#" + to).data("required");
							json				= eval("(" + json + ")");
							var past_directory	= json.dir;
							
							_object += "'" + index + "':{'from':'" + from + "','copy_directory':'" + copy_directory + "','fileUrl':'" + fileUrl + "','ftp_log_id':'" + ftp_log_id + "','past_directory':'" + past_directory + "','to':'" + to + "'},";	
						})
						
						parent.sceditor.call('base.ftp.past()',eval("({" + _object + "})"));
						
						if(lastObject != null){
							lastObject.parents("div.gridParent").find('tbody > tr').removeClass('copied');
						}
						
						$("#" + to).removeClass('past');
						
						break;
					case "edit":
						if(object.find("tbody tr.list_selected").length == 1){
							object.find("tbody tr.list_selected.file").trigger("dblclick");
						}else{
							parent.sceditor.call("base.notify()", {msg: 'Please select a file', 'type': 'warning'});
						}
					
						break;
					case "history":
						var paths = '';
						if(server == "remote"){
							var paths = RemoveLastDirectoryPartOf(directory,1);
							
							if(paths == ''){
								paths = '.';
							}
							
							json.dir = paths;
							parent.sceditor.call("base.ftp.reconnectServer()",json);
						}else{
							var base 	= "";
							var toM 	= base.split("/");
							var newDir 	= directory
							var dir 	= newDir.split("/");
							paths 		= base;
							
							if(dir.length > toM.length){
								paths=RemoveLastDirectoryPartOf(newDir,2);
							}
							
							json.dir = paths;
							parent.sceditor.call("base.ftp.reconnectLocal()",json);
						}
						
						break;
				}
			});
			
			$(window).load(function(){
				$("table.dataTable").animate({opacity:1},1000)
			});
			
			$(window).resize(function(){
				var _he=parseInt($(window).height()) - 292;
				remoteFileGrid.fnSettings().oScroll.sY = _he + "px";
				localFileGrid.fnSettings().oScroll.sY = _he + "px";
			});
			
			$(window).trigger('resize')
			
			/* Connect to FTP */
			resetWidth();
			$("#workspace").change();
			functionftpconnect();
		});
		
		function RemoveLastDirectoryPartOf(the_url,e) {
			try{
				var the_arr = the_url.replace(/([^:]\/)\/+/g, "$1").split('/');
				var newA = new Array();
				
				for(var i=0; i < the_arr.length-e; i++){
					newA.push(the_arr[i]);
				}
				
				return( newA.join('/') );
			}catch(ee){
				return the_url.replace(/([^:]\/)\/+/g, "$1");
			}
		}
		
		var old_name=null;
		
	function menuAction() {
		var realObj		= null;
		
		if(lastObject != null){
			realObj		= lastObject.parents("div.last_object");
		}else{
			realObj		= $("div.last_object");
		}
		
		//console.log(realObj);
		var json		= realObj.data("required");
		json			= eval("(" + json + ")");
		
		if(json == null){
			realObj		= $("div.last_object");
			json		= realObj.data("required");
			json		= eval("(" + json + ")");
		}
		
		var directory	= json.dir;
		var ftp_log_id		= json.ftp_log_id;
		var server		= json.server;
		var ftp_log_id	= json.ftp_log_id;
		var menuItem	= $(this).find('span.contextmenuicon');
		
		switch (this.data.alias){
			case 'rename':
				if(!menuItem.hasClass('b-m-idisable')){
					old_name=lastObject.find('td').first().html();
					lastObject.addClass('renaming');
					lastObject.find('td').first().empty().append('<input data-old="' + old_name + '" class="rename" type="text" value="' + old_name + '" />');
				}
				
				break;
			case 'delete':
				if(!menuItem.hasClass('b-m-idisable')){
					var createData='';
					var index=0;
					
					$.each(realObj.find("tbody tr.list_selected"),function(){
						index++;
						createData=createData + "'" + index + "':{'fileUrl':'" + $(this).data('url') + "','ftp_log_id':'" + ftp_log_id + "','dir':'" + directory + "','server':'" + server + "', 'type':'" + ($(this).hasClass('directory') ? 'dir': '') + "'},";
					});
					
					createData=createData+'';
					parent.sceditor.call("base.ftp.deleteFiles()",eval("({" + createData + "})"));
				}
				
				break;
			case 'newFile':
				if(!menuItem.hasClass('b-m-idisable')){
					if(lastObject != null && lastObject.parents('div.gridParent').hasClass('past')){
						lastObject.parents("div.gridParent").find('tbody > tr').removeClass('list_selected')
						lastObject.before(
							"<tr data-location='" + server + "' data-url='" + directory + "'/Untitled.txt' data-ftp-log-id='" + ftp_log_id + "' class='file list_selected renaming'>" + 
								"<td>" + 
									"<input data-new='yes' class='rename' type='text' value='Untitled.txt' />" + 
								"</td>" + 
								"<td>0KB</td>" + 
								"<td><?php echo date ("d, M Y") ?></td>" + 
							"</tr>"
						)
					}else{
						$("div.gridParent").find('tbody > tr').removeClass('list_selected')
						realObj.find("tbody").append(
							"<tr data-location='" + server + "' data-url='" + directory + "'/Untitled.txt' data-ftp-log-id='" + ftp_log_id + "' class='file list_selected renaming'>" + 
								"<td>" + 
									"<input data-new='yes' class='rename' type='text' value='Untitled.txt' />" + 
								"</td>" + 
								"<td>0KB</td>" + 
								"<td><?php echo date ("d, M Y") ?></td>" + 
							"</tr>"
						);
					}
				}
				
				break;
			case 'newDirectory':
				if(!menuItem.hasClass('b-m-idisable')){
					if(lastObject != null && lastObject.parents('div.gridParent').hasClass('past')){
						lastObject.parents("div.gridParent").find('tbody > tr').removeClass('list_selected')
						lastObject.before(
							"<tr data-location='" + server + "' data-url='" + directory + "'/New Folder' data-ftp-log-id='" + ftp_log_id + "' class='directory list_selected renaming'>" + 
								"<td>" + 
									"<input data-new='yes' class='rename' type='text' value='New Folder' />" + 
								"</td>" + 
								"<td>N/A</td>" + 
								"<td><?php echo date ("d, M Y") ?></td>" + 
							"</tr>"
						)
					}else{
						$("div.gridParent").find('tbody > tr').removeClass('list_selected')
						realObj.find("tbody").append(
							"<tr data-location='" + server + "' data-url='" + directory + "'/New Folder' data-ftp-log-id='" + ftp_log_id + "' class='directory list_selected renaming'>" + 
								"<td>" + 
									"<input data-new='yes' class='rename' type='text' value='New Folder' />" + 
								"</td>" + 
								"<td>N/A</td>" + 
								"<td><?php echo date ("d, M Y") ?></td>" + 
							"</tr>"
						);
					}
				}
				
				break;
			case 'copy':
				if(!menuItem.hasClass('b-m-idisable')){
					lastObject.data('file-action','copy');
					lastObject.parents("div.gridParent").find('tbody > tr').removeClass('copied')
					lastObject.parents("div.gridParent").find('tbody > tr.list_selected').addClass('copied').data('file-action','copy');
				}
				
				break;
			case 'cut':
				if(!menuItem.hasClass('b-m-idisable')){
					lastObject.data('file-action','cut');
					lastObject.parents("div.gridParent").find('tbody > tr').removeClass('copied')
					lastObject.parents("div.gridParent").find('tbody > tr.list_selected').addClass('copied').data('file-action','cut');
				}
				
				break;
			case 'past':
				if(!menuItem.hasClass('b-m-idisable')){
					var index 		= 0;
					var past 		= $("div.gridParent.past");
					var createData 	= "{";
					
					$.each($("div.gridParent").find("tbody > tr.copied"),function(){
						index++;
						var json			= $(this).parents("div.gridParent").data("required");
						json				= eval("(" + json + ")");
						var from			= json.server;
						var to				= (from == 'remote') ? 'local' : 'remote';
						var ftp_log_id		= json.ftp_log_id;
						var fileUrl			= $(this).data('url');
						var copy_directory	= json.dir;
						var json			= $("#" + to).data("required");
						json				= eval("(" + json + ")");
						var past_directory	= json.dir;
						
						createData += "'" + index + "':{'from':'" + from + "','file_action':'" + $(this).data('file-action') + "','copy_directory':'" + copy_directory + "','fileUrl':'" + fileUrl + "','ftp_log_id':'" + ftp_log_id + "','past_directory':'" + past_directory + "','to':'" + to + "'},";
					})
					
					createData += "}";
					parent.sceditor.call('base.ftp.past()',eval("(" + createData + ")"));
					lastObject.parents("div.gridParent").find('tbody > tr').removeClass('copied')
					past.removeClass('past');
				}
				
				break;
			case 'onenFile':
				if(!menuItem.hasClass('b-m-idisable')){
					if(server == 'remote'){
						if($("div#remote").find("tbody > tr.list_selected").length == 1){
							var json				= $("div.gridParent.past").data("required");
							json					= eval("(" + json + ")");
							var dir					= json.dir;
							var fileUrl				= lastObject.data('url');
							var ftp_log_id			= json.ftp_log_id;
							var newjson				= "{'dir':'" + dir + "','fileUrl':'" + fileUrl + "','ftp_log_id':'" + ftp_log_id + "'}";
							
							parent.sceditor.call('base.ftp.editRemoteFile()',eval("(" + newjson + ")"));
						}else{
							parent.sceditor.call("base.notify()", {msg: 'Please select a file to edit.', 'type': 'warning'});
						}
					}else{
						$("div#local").find("tbody > tr.list_selected.file").trigger('dblclick');
					}
				}
				
				break;
		}
		
		$("table.fileGrid tbody tr,.ftpconnfilescol_navtree").contextmenu(option);
		$(window).trigger('resize')
	}
	
	function resetWidth(){
		$("#local table tbody tr").find("td:eq(0)").width($("#local table thead tr").find("th:eq(0)").width());
		$("#local table tbody tr").find("td:eq(1)").width($("#local table thead tr").find("th:eq(1)").width());
		$("#local table tbody tr").find("td:eq(2)").width($("#local table thead tr").find("th:eq(2)").width());
		$("#remote table tbody tr").find("td:eq(0)").width($("#remote table thead tr").find("th:eq(0)").width());
		$("#remote table tbody tr").find("td:eq(1)").width($("#remote table thead tr").find("th:eq(1)").width());
		$("#remote table tbody tr").find("td:eq(2)").width($("#remote table thead tr").find("th:eq(2)").width());
	}
	
	function applyrule(menu) {
		if (this.id == "target2") {
			menu.applyrule({ name: "target2",
				disable	: true,
				items	: ["1-2", "2-3", "2-4", "1-6"]
			});
		}
		else {
			menu.applyrule({ name: "all",
				disable: true,
				items: []
			});
		}
	}
	
	function BeforeContextMenu() {
		return this.id != "target3";
	}
	
	function updateLog(e){
		var array=eval(e);
		$(".ftpconnlog").empty();
		
		$.each(array, function (key, data) {
			$(".ftpconnlog").append('<span class="' + data['class'] + '">' + data['message'] + '</span>');
			$(".ftpconnlog").scrollTop($(".ftpconnlog")[0].scrollHeight)
		});
		
		connectionAction()
	}
	
	function showProcessing(type, show) {
		var processBar = $("#" + type + "ProcessBar");
		if (!processBar[0]) {
			if (!show) {
				return;
			}
			
			processBar = $("<div id='" + type + "ProcessBar' class='processbar'><img src='<?php echo base_url(); ?>core/images/loading.gif' /></div>").appendTo("body");
		}
		
		if (show) {
			var system = $("div#" + type + ".gridParent").parents(".ftpconnfiles_col");
			var width = system.width();
			var height = system.height();
			var offset = system.offset();
			processBar.css({top: offset.top + 1 + "px", left: offset.left + 1 + "px", width: width + "px", height: height + "px"});
			processBar.show();
		} else {
			processBar.hide();
		}
	}
	
	function updateRemoteServer(e){
        remoteFileGrid.closest('.dataTables_wrapper').hide();
        var status = false;
		
		try{
			var array=eval("(" + e + ")");
			remoteFileGrid.fnClearTable();
			
			$.each(array, function (key, data) {
				if(key != 'details' && (getFileName(data.fileUrl).charAt(0) != '.' && $.trim(getFileName(data.fileUrl)) != '.' && $.trim(getFileName(data.fileUrl)) != '..')){
					var ext='file ext_' + getExtension(data.fileUrl);
					if(getExtension(data.fileUrl) == ''){
						ext='directory';
						var str = 
							"<tr data-location='server' data-url='" + data.fileUrl + "' data-ftp-log-id='" + array['details'].ftp_log_id + "' class='" + ext + "'>" + 
								"<td>" + getFileName(data.fileUrl) + "</td>" + 
								"<td>" + data.fileSize + "</td>" + 
								"<td>" + data.lastModifiedDate + "</td>" + 
							"</tr>";
						remoteFileGrid.fnAddTr($(str)[0]);
					}
				}
			})
			
			$.each(array, function (key, data) {
				if(key != 'details' && (getFileName(data.fileUrl).charAt(0) != '.' && $.trim(getFileName(data.fileUrl)) != '.' && $.trim(getFileName(data.fileUrl)) != '..')){ 
					var ext='file ext_' + getExtension(data.fileUrl);
					if(getExtension(data.fileUrl) != ''){
						var str = 
							"<tr data-location='server' data-url='" + data.fileUrl + "' data-ftp-log-id='" + array['details'].ftp_log_id + "' class='" + ext + "'>" + 
								"<td>" + getFileName(data.fileUrl) + "</td>" + 
								"<td>" + data.fileSize + "</td>" + 
								"<td>" + data.lastModifiedDate + "</td>" + 
							"</tr>";
						remoteFileGrid.fnAddTr($(str)[0]);
					}
				}
			})
			
			remoteFileGrid.fnSettings().aaSorting=[[1,'']];
			var json = $("div#remote.gridParent").data('required');
			var data = eval("(" + json + ")");
			data.dir = array['details'].dir;
			$("div#remote.gridParent").data('required', JSON.stringify(data));
			
			remoteFileGrid.find("tbody tr").draggable({
				appendTo: 'body',
				containment: 'body',
				revert: "invalid",
				helper: 'clone',
				cursor: "not-allowed",
				cursorAt: {top: -10, left: -10},
				delay: 100,
				distance: 10,
				opacity: 0.75,
				zIndex: 100,
				scope: 'remote-files'
			});
			
			if (array['details'].connected == 1) {
				status = true;
			}
		}catch(e){
			console.log(e)
		}
		
		$("table.fileGrid tbody tr,.ftpconnfilescol_navtree").contextmenu(option);
		resetWidth();
		remoteFileGrid.closest('.dataTables_wrapper').show();
		$(window).trigger('resize');
		
		if (status) {
			updateConnectionStatus({'text': 'Connected', 'color': 'green'});
			
			setTimeout(function(){
				$(".connection_info > i").removeClass("background").addClass("green");
			},1000);
		} else {
			updateConnectionStatus({'text': 'Not Connected', 'color': 'red'});
			
			setTimeout(function(){
				$(".connection_info > i").removeClass("green").addClass("background");
			},1000)
		}
	}
	
	function updateLocalServer(e){
		try{
			var array = eval("(" + e + ")");
            array = array.files;
  			localFileGrid.fnClearTable();
			//localFileGrid.find("tbody tr").draggable('destroy');
			
			$.each(array, function (key, data) {
				if(key!='details' && (getFileName(data.fileUrl).charAt(0) != '.' && $.trim(getFileName(data.fileUrl)) != '.' && $.trim(getFileName(data.fileUrl))!='..')){
					var ext='file ext_' + getExtension(data.fileUrl);
					
					if(getExtension(data.fileUrl) == ''){
						ext='directory';
						
						var str = 
							"<tr data-url='" + data.fileUrl + "' data-id='" + data.fileId + "' class='" + ext + "'>" + 
								"<td>" + getFileName(data.fileUrl) + "</td>" + 
								"<td>" + data.fileSize + "</td>" + 
								"<td>" + data.lastModifiedDate + "</td>" + 
							"</tr>";
						
						localFileGrid.fnAddTr($(str)[0]);
					}
				}
			})
			
			$.each(array, function (key, data) {
				if(key != 'details' && (getFileName(data.fileUrl).charAt(0) != '.' && $.trim(getFileName(data.fileUrl)) != '.' && $.trim(getFileName(data.fileUrl)) != '..')){
					var ext='file ext_' + getExtension(data.fileUrl);
					
					if(getExtension(data.fileUrl) != ''){
						var str = 
							"<tr data-url='" + data.fileUrl + "' data-id='" + data.fileId + "' class='" + ext + "'>" + 
								"<td>" + getFileName(data.fileUrl) + "</td>" + 
								"<td>" + data.fileSize + "</td>" + 
								"<td>" + data.lastModifiedDate + "</td>" + 
							"</tr>";
						
						localFileGrid.fnAddTr($(str)[0]);
					}
				}
			})
			
			//console.log(localFileGrid);
			localFileGrid.fnSettings().aaSorting=[[1,'']];
			var json = $("div#local.gridParent").data('required');
			var data = eval("(" + json + ")");
			data.dir = remove_slashes(array['details'].dir);
			$("div#local.gridParent").data('required', JSON.stringify(data));
			
			localFileGrid.find("tbody tr").draggable({
				appendTo: 'body',
				containment: 'body',
				revert: "invalid",
				helper: 'clone',
				cursor: "not-allowed",
				cursorAt: {top: -10, left: -10},
				delay: 100,
				distance: 10,
				opacity: 0.75,
				zIndex: 100,
				scope: 'local-files'
			});
		}catch(e){
			console.log(e)
		}
		
		$("table.fileGrid tbody tr,.ftpconnfilescol_navtree").contextmenu(option);
		$(window).trigger('resize');
		
		resetWidth();
	}
	
	function connectionAction(){
        $(".connection_info>i").removeClass('green').toggleClass("background");
	}
	
	function getExtension(e){
		try{
			var page = e.substring(e.lastIndexOf('/') + 1);
			var re = /(?:\.([^.]+))?$/;
			
			return $.trim(re.exec(page)[1]);
		}catch(e){
			return '';
		}
	}
	
	function getFileName(e){
		try{
			var nameArray = unescape(e).split('/');
			return nameArray[nameArray.length - 1];
		}catch(e){
			return e;
		}
	}
	
	function functionftpconnect(){
		var data = null;
		var json = $("div#remote").data("required");
		data = eval("(" + json + ")");
		parent.sceditor.call("base.ftp.reconnectServer()", data);
	}
	
	function functionftpdisconnect(){
		var json = $("div#remote").data("required");
		var data = eval("(" + json + ")");
		parent.sceditor.call("base.ftp.disconnect()", data.ftp_log_id);
		updateConnectionStatus({'text': 'Disconnected', 'color': 'grey'});
		$(".connection_info > i").removeClass("green").removeClass("background");
	}
	
	function remove_slashes(e){
		return e.replace(/([^:]\/)\/+/g, "$1")
	}
	
	function updateConnectionStatus(e){
		$("span#remoteServerConn").html(e.text).css('color',e.color);
	}
	
	function functionftpclear() {
		var json = $("div#remote").data("required");
		var data = eval("(" + json + ")");
		parent.sceditor.call("base.ftp.clearLog()", data);
	}
	
	function clearLog() {
		$(".ftpconnlog").empty();
	}
	</script>
	
	<style>
	
	/* Globals */
	html, body {
		width: 100%;
		height: 100%;
		}
		body {
			margin: 0;
			padding: 0;
			}
		
	* {
		-webkit-box-sizing: border-box;
		-moz-box-sizing: 	border-box;
		box-sizing: 		border-box;
		}
	
	.background {
        color: #f00 !important;
		}
	.green {
        color: #063 !important;
		}
	
	/* jQuery File Tree */
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
	
	ul.jqueryFileTree > li.copied {
		background-color: #ccc;
		
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

	#cmroot > .disable,
	#cmroot > .b-m-idisable {
		background-color: #f00;
		}
	
	ul.jqueryFileTree li input.rename {
		border: 0;
		width: auto;
		float: left;
		}
	
	body.has-droppable {
		cursor: pointer !important;
		}
	</style>
</head>
<body data-width='100%' data-height="100%" data-controls="{'Clear Log' : 'functionftpclear', 'Disconnect' : 'functionftpdisconnect','Connect' : 'functionftpconnect','Close':'close'}" style="height: 100%;">
<div class="infopopup" style="height: 100%;">
	<form id="" class="" style="height: 100%;">
		<div class="infooptionscontainer" style="padding-right: 0; height: 100%;"> <!-- -->
			<div class="infopopupoptions" style="height: 100%;">
				<div class="infopopupoptrow first" style="padding: 0; height: 100%;">
					
					<?php /* FTP: Start */ ?>
					<div class="ftpconn" style="position: relative; height: 100%;">
						<?php
							$rec			= json_decode(get_option('ftps'),true);
							$ftp_log_id		= $this->uri->segment(3);
							$ftp_host		= $rec[$ftp_log_id]['ftp_host'];
							$ftp_domain		= $rec[$ftp_log_id]['ftp_domain'];
							$ftp_username	= $rec[$ftp_log_id]['ftp_username'];
							$ftp_password	= Cipher::decrypt($rec[$ftp_log_id]['ftp_password']);
							$ftp_port		= $rec[$ftp_log_id]['ftp_port'];
							$ftp_protocol	= isset($rec[$ftp_log_id]['ftp_protocol']) ? $rec[$ftp_log_id]['ftp_protocol'] : 'FTP';
						?>
						
						<?php /* FTP Connection Details: Start */ ?>
						<div class="tabsectionheader">
							<div class="tabsectionheader_inside">
								<ul class="tabsectionheaderlist">
									<li class="first">Host: <?php echo $ftp_host; ?></li>
									<li>User: <?php echo $ftp_username; ?></li>
									<li>Port: <?php echo $ftp_port; ?></li>
									<li>Protocol: <?php echo $ftp_protocol; ?></li>
									<li class="last">Transfer Type: ASCII</li>
									<?php /* <li>Password: <?php echo $ftp_password; ?></li> */ ?>
								</ul>
								
								<div class="clear"></div>
							</div>
							
							<div class="tabsectionheadersep"><span></span></div>
							
							<div class="clear"></div>
						</div>
						<?php /* FTP Connection Details: End */ ?>
						
						<?php /* FTP Files: Start */ ?>
						<div class="ftpconn_files">
							<div class="ftpconnfiles_inside">
								
								<?php /* Left Column: Start */ ?>
								<div class="ftpconnfiles_col left">
									<div class="ftpconnfilescol_inside">
										<div class="ftpconnfiles_controls">
											<div class="ftpconnfilescontrols_inside">
												<ul class="ftpconnfileslist">
													<li class="ftpconnfilecontrol folder">
														<span class="option"><i class="fa fa-folder-open" data-tool-action='openFolder'></i></span>
													</li>
													<li class="ftpconnfilecontrol delete">
														<span class="option"><i class="fa fa-trash-o" data-tool-action='delete'></i></span>
													</li>
													<li class="ftpconnfilecontrol edit">
														<span class="option"><i class="fa fa-edit" data-tool-action='edit'></i></span>
													</li>
													<li class="ftpconnfilecontrol upload">
														<span class="option"><i class="fa fa-upload" data-tool-action='upload'></i></span>
													</li>
													<li class="ftpconnfilecontrol refresh">
														<span class="option"><i class="fa fa-refresh" data-tool-action='refresh'></i></span>
													</li>
                                                    <li class="ftpconnfilecontrol history">
														<span class="option"><i class="fa fa-level-up" data-tool-action='history'></i></span>
													</li>
                                                    <?php
													$dbwc=(int)get_user_feature('work_space');
														$_ws = get_option('ws');
														$_ws = json_decode($_ws,true);
														
														$index=0;
														if(is_array($_ws) && sizeof($_ws)>0){
													?>
                                                    <li class="ftpconnfilecontrol workspace">
														<select name="workspace" id="workspace">
                                                        <?php
														foreach($_ws as $key=>$ws){
															if($ws['ws_status']=='enable'){
															?>
                                                        	<option value="<?php echo $key; ?>/" <?php echo ($ws['ws_active']=='true') ? 'selected="selected"' : ''; ?>><?php echo $ws['ws_name']; ?></option>
															<?php
															}
                                                        }
                                                        ?>
                                                        </select>
													</li>
                                                    <?php
														}
													?>
													<li class="serverinfo">
														<span>SC Server: <font color="green">Connected</font></span>
													</li>
												</ul>
												
												<div class="clear"></div>
											</div>
											
											<div class="clear"></div>
										</div>
										
										<div class="ftpconnfilescol_navtree gridParent" data-required="{ftp_log_id: '<?php echo $this->uri->segment(3); ?>', server: 'local'}" id="local">
                                            <table class="display fileGrid ftpfilegrid" cellspacing="0" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th class="name">Name</th>
                                                        <th class="size">Size</th>
                                                        <th class="date">Last Modified</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
    										</table>
											
											<div class="clear"></div>
										</div>
										
										<div class="clear"></div>
									</div>
									
									<div class="clear"></div>
								</div>
								<?php /* Left Column: End */ ?>
								
								<?php /* Center Column: Start */ ?>
								<div class="ftpconnfiles_col middle">
									<div class="ftpconnfilescol_inside">
										<div class="ftpconnfilescol_filenav">
											<ul class="ftptransferdireclist">
												<li class="ftptransfer right ftp_upload"><a href="#"><span><i class="fa fa-chevron-right"></i></span></a></li>
												<li class="ftptransfer left ftp_download"><a href="#"><span><i class="fa fa-chevron-left"></i></span></a></li>
											</ul>
											
											<div class="clear"></div>
										</div>
										
										<div class="clear"></div>
									</div>
									
									<div class="clear"></div>
								</div>
								<?php /* Center Column: End */ ?>
								
								<?php /* Right Column: Start */ ?>
								<div class="ftpconnfiles_col right">
									<div class="ftpconnfilescol_inside">
										<div class="ftpconnfiles_controls">
											<div class="ftpconnfilescontrols_inside">
												<ul class="ftpconnfileslist">
													<li class="ftpconnfilecontrol folder">
														<span class="option"><i class="fa fa-folder-open" data-tool-action='openFolder'></i></span>
													</li>
													<li class="ftpconnfilecontrol delete">
														<span class="option"><i class="fa fa-trash-o" data-tool-action='delete'></i></span>
													</li>
													<li class="ftpconnfilecontrol edit">
														<span class="option"><i class="fa fa-edit" data-tool-action='edit'></i></span>
													</li>
													<li class="ftpconnfilecontrol upload">
														<span class="option"><i class="fa fa-download" data-tool-action='upload'></i></span>
													</li>
													<li class="ftpconnfilecontrol refresh">
														<span class="option"><i class="fa fa-refresh" data-tool-action='refresh'></i></span>
													</li>
                                                    <li class="ftpconnfilecontrol history">
														<span class="option"><i class="fa fa-level-up" data-tool-action='history'></i></span>
													</li>
                                                    <li class="connection_info">
														<i class="fa fa-circle"></i>
													</li>
													<li class="serverinfo">
														FTP Server: <span id="remoteServerConn">Not connected</span>
													</li>
												</ul>
												
												<div class="clear"></div>
											</div>
											
											<div class="clear"></div>
										</div>
										
										<div class="ftpconnfilescol_navtree gridParent" data-required="{ftp_log_id: '<?php echo $this->uri->segment(3); ?>', dir: '.', server: 'remote'}" id="remote">
											<table class="display fileGrid ftpfilegrid" cellspacing="0" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Size</th>
                                                        <th>Last Modified</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
    										</table>
                                            
											<div class="clear"></div>
										</div>
										
										<div class="clear"></div>
									</div>
									
									<div class="clear"></div>
								</div>
								<?php /* Right Column: End */ ?>
								
								<div class="clear"></div>
							</div>
							
							<div class="clear"></div>
						</div>
						<?php /* FTP Files: End */ ?>
						
						<?php /* FTP Log: Start */ ?>
						<div class="ftpconn_log">
							<div class="ftpconnlog_inside">
								<div class="infopopupsubtitle">
									<span>Log</span>
								</div>
								
								<div style="padding: 50px 0 0; height: 100%;">
									<div class="ftpconnlog" style="height: 100%;"></div>
									
									<div class="clear"></div>
								</div>
								
								<div class="clear"></div>
							</div>
							
							<div class="clear"></div>
						</div>
						<?php /* FTP Log: End */ ?>
						
						<div class="clear"></div>
					</div>
					<?php /* FTP: End */ ?>
					
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