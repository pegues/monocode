<?php
/*
  Template Name : My Account (File Explorer)
 */
?>

<link href="<?php echo base_url(); ?>core/css/contextmenu.css" rel="stylesheet" media="all" />
<?php /* <link href="<?php echo base_url(); ?>core/css/jquery.dataTables.css" rel="stylesheet" media="all" /> */ ?>
<link href="<?php echo base_url(); ?>themes/frontend/monocode/css/filemanager.css" rel="stylesheet" media="all" />

<?php /* File Explorer: Start */ ?>
<div class="scfileexplorer">
    <div class="scfileexplorer_inside">
		
        <?php /* File Explorer Header: Start */ ?>
        <div class="scexpheader">
            <div class="scexpheader_inside">
				
                <div class="scexpheadercol">
                    <div class="scexpheadercol_inside">
                        <h3>
                            <?php if (isset($entity) && $entity) { ?>
                                <span class="scexpoutputtitle">Files for</span>
                                <span class="scexpoutputwkspnme"><?php echo $entity['ws_name']; ?></span>
                            <?php } else { ?>
                                <span class="scexpoutputtitle">No workspace selected</span>
                            <?php } ?>
                        </h3>
						
                        <div class="clear"></div>
                    </div>
					
                    <div class="clear"></div>
                </div>
				
                <?php /* Columns: Start */ ?>
                <div>
					
                    <?php /* Left Column: Start */ ?>
                    <div class="scexpheadercol left">
                        <div class="scexpheadercol_inside">
							
                            <ul class="filebreadcrumblist">
                                <?php if (isset($entity) && $entity) { ?>
                                    <li><a href="#"><?php echo $entity['ws_name']; ?></a></li>
                                    <li><span class="sep"><i class="fa fa-angle-right"></i></span></li>
                                    <li><a href="#">accounts</a></li>
                                    <li><span class="sep"><i class="fa fa-angle-right"></i></span></li>
                                    <li><a href="#">clients</a></li>
                                    <li><span class="sep"><i class="fa fa-angle-right"></i></span></li>
                                    <li><a href="#">js</a></li>
                                <?php } ?>
                            </ul>
							
                            <div class="clear"></div>
                        </div>
						
                        <div class="clear"></div>
                    </div>
                    <?php /* Left Column: End */ ?>
					
                    <?php /* Right Column: Start */ ?>
                    <div class="scexpheadercol right">
                        <div class="scexpheadercol_inside">
                            <form>
                                
								<?php /* Controls: Start */ ?>
                                <div class="scheadercontrolsholder">
                                    <ul class="filexpcontrolslist">
                                        <li class="filexpcontrol delete">
                                            <a href="#delete" data-tool-action="delete">
												<span>Delete</span>
											</a>
                                        </li>
                                        <li class="filexpcontrol refresh">
                                            <a href="#refresh" data-tool-action="refresh">
												<span>Refresh</span>
											</a>
                                        </li>
                                        <li class="filexpcontrol history">
                                            <a href="#history" data-tool-action="history">
												<span>Go Up</span>
											</a>
                                        </li>
                                    </ul>
									
									<div class="clear"></div>
                                </div>
								<?php /* Controls: End */ ?>
								
								<?php /* Workspace Dropdown: Start */ ?>
                                <div class="scexpheaderdropdown scformrow">
									<label for="">Select Workspace:</label>
									<div class="scfield">
										<select id="" 
											class="select selectworkspace" 
											name="ws" 
											onchange="$(this).closest('form').submit();">
											<option value="">Please Select</option>
											<?php
											if (isset($entities) && count($entities) > 0) {
												foreach ($entities as $ws => $ety) {
													?>
													<option value="<?php echo $ws; ?>" <?php echo isset($entity) && $entity && $entity['ws_directory'] == $ws ? 'selected="selected"' : '' ?>><?php echo $ety['ws_name']; ?></option>
													<?php
												}
											}
											?>
										</select>
										
										<div class="clear"></div>
									</div>
									
                                    <div class="clear"></div>
                                </div>
                                <?php /* Workspace Dropdown: End */ ?>
								
                                <?php /* Sort By Dropdown: Start * / ?>
                                <div class="scexpheaderdropdown">
                                    <label for="">Sort by:</label>
                                    <select id="" class="" name="sort">
                                        <option value="">Please Select</option>
                                        <option value="">Name</option>
                                        <option value="">Date Modified</option>
                                        <option value="">Date Created</option>
                                        <option value="">Size</option>
                                        <option value="">------------</option>
                                        <option value="">Ascending</option>
                                        <option value="">Descending</option>
                                    </select>
									
                                    <div class="clear"></div>
                                </div>
                                <?php / * Sort By Dropdown: End */ ?>
								
                            </form>
							
                            <div class="clear"></div>
                        </div>
						
                        <div class="clear"></div>
                    </div>
                    <?php /* Right Column: End */ ?>
					
                    <div class="clear"></div>
                </div>
                <?php /* Columns: End */ ?>
				
                <div class="clear"></div>
            </div>
			
            <div class="clear"></div>
        </div>
        <?php /* File Explorer Header: End */ ?>
		
        <?php /* File Explorer Output: Start */ ?>
        <div class="scexpoutput">
            <div class="scexpoutput_inside">
				
                <?php /* File List: Start */ ?>
                <div class="scexpoutputcol left">
                    <div class="scexpoutputcol_inside">
                        <table id="fileGrid" class="" cellspacing="0" width="100%" data-workspace = '<?php echo isset($entity) && $entity ? json_encode($entity) : ''; ?>' data-dir='<?php echo isset($entity) && $entity ? $entity['ws_directory'] . '/' : ''; ?>'>
                            
							<?php /* Data Header: Start */ ?>
                            <thead class="scexpdataheader">
                                <tr class="scexpdatarow">
                                    <th class="scexpdatarowitem check sorting_disabled">
                                        <input type="checkbox" id="selectall" class="scexpitemcheckbox" />
                                    </th>
                                    <th class="scexpdatarowitem name">
                                        <span class="scexpitemtxt">Name</span>
                                    </th>
                                    <th class="scexpdatarowitem date">
                                        <span class="scexpitemtxt">Date Modified</span>
                                    </th>
                                    <th class="scexpdatarowitem size">
                                        <span class="scexpitemtxt">Size</span>
                                    </th>
                                </tr>
                            </thead>
                            <?php /* Data Header: End */ ?>
							
                            <?php /* Data Items: Start */ ?>
                            <tbody class="scexpdataoutput"></tbody>
                            <?php /* Data Items: End */ ?>
							
                        </table>
						
                        <div class="clear"></div>
                    </div>
					
                    <div class="clear"></div>
                </div>
                <?php /* File List: End */ ?>
				
                <div class="clear"></div>
            </div>
			
            <div class="clear"></div>
        </div>
        <?php /* File Explorer Output: End */ ?>
		
        <div class="clear"></div>
    </div>
	
    <div class="clear"></div>
</div>
<?php /* File Explorer: End */ ?>

<script src="<?php echo base_url(); ?>core/js/contextmenu.js"></script>
<script src="<?php echo base_url(); ?>core/js/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>core/js/jquery.dataTables.ext.types.js"></script>
<script>
    jQuery(document).ready(function ($) {
        
		// Initialization for File Explorer
        initExplorer();
    });
	
    function initExplorer() {
        var grid = $("#fileGrid");
        var options = {
            "paging"	: false,
            "ordering"	: true,
            "info"		: false,
            "bFilter"	: false,
            columnDefs	: [
                { type: 'file-size', targets: 2 }
            ],
            "aoColumns"	: [
                {bSortable: false},
                {type: 'file-name'},
                {type: 'date-modified'},
                {type: 'file-size'}
            ],
            "aaSorting": [],
            "oLanguage": {
                "sEmptyTable": "Empty"
            }
        };
		
        grid.dataTable(options);
        grid.animate({opacity: 1});
		
        $('body')
			.on('click', '#fileGrid tr', function (e) {
				var check = $(this).find('td.scexpdatarowitem.check > input.scexpitemcheckbox');
				
				checkRow(check, !check.prop('checked'));
			}).on('mousedown', '#fileGrid tr', function (e) {
				if (e.which == 3) {
					$("#fileGrid tbody tr")
						.removeClass('active')
						.find('td.scexpdatarowitem.check > input.scexpitemcheckbox')
						.removeClass('active')
						.prop('checked', false);
					
					checkRow($(this).find('td.scexpdatarowitem.check > input.scexpitemcheckbox'), true);
					lastSelectedRow = $(this);
				}
			}).on('change', '#fileGrid td.scexpdatarowitem.check > input.scexpitemcheckbox', function (e) {
				var check = $(this);
				checkRow(check, !check.prop('checked'));
				e.stopPropagation();
				e.cancelBubble = true;
				
				return false;
			}).on('click', '#fileGrid td.scexpdatarowitem.name > a', function () {
				var row = $(this).closest('[data-url]');
				var dir = $("#fileGrid").data('dir');
				var name = row.data('url');
				
				if (row.hasClass('folder')) {
					loadDir(dir + name + "/");
				} else {
					download(dir, name);
				}
				return false;
			}).on('change', '#fileGrid td input.rename', function (e) {
				$(this).addClass('updating');
				$.ajax({
					type: "POST",
					url: "file/rename",
					dataType: "json",
					data: {'dir': $('#fileGrid').data('dir'), 'from': $(this).data('original-name'), 'to': $.trim(this.value)}
				}).done(function (file) {
					$(this).removeClass('updating');
					loadDir($('#fileGrid').data('dir'));
				});
			}).on('keydown', '#fileGrid td input.rename', function (e) {
				if (e.keyCode == 27) {
					setTimeout(function() {
						cancelRename();
					}, 1);
				}
			}).on('blur', '#fileGrid td input.rename', function (e) {
				if (!$(this).hasClass('updating')) {
					setTimeout(function() {
						cancelRename();
					}, 1);
				}
			}).on('click', '#fileGrid td input.rename', function (e) {
				e.stopPropagation();
				e.cancelBubble = true;
				
				return false;
			});
		
        $("#fileGrid th.scexpdatarowitem.check > input.scexpitemcheckbox").click(function () {
            setTimeout(function () {
                selectAllRows();
            });
        });
		
        $('.filebreadcrumblist').on('click', '> li > a', function (e) {
            loadDir(this.href.substr(this.href.indexOf('#') + 1));
			
            return false;
        });
		
        $(".filexpcontrolslist").on('click', '> li > a', function (e) {
            var action = this.href.substr(this.href.indexOf('#') + 1);
            switch (action) {
                case 'delete':
                    __delete();
					
                    break;
                case 'refresh':
                    loadDir($("#fileGrid").data('dir'));
					
                    break;
                case 'history':
                    var dir = $("#fileGrid").data('dir');
                    dir = dir.substr(0, dir.length - 1);
                    var index = dir.lastIndexOf('/');
					
                    if (index > -1) {
                        dir = dir.substr(0, index + 1);
                        loadDir(dir);
                    }
					
                    break;
            }
			
            return false;
        });
		
        var ws = grid.data('workspace');
        var dir = grid.data('dir');
		
        if (ws == null || ws == '') {
            return;
        }
		
        initContextmenu();
        loadDir(dir);
    }
	
    function checkRow(check, checked) {
        if (checked) {
            check.addClass('active');
            check.closest('tr').addClass('active');
        } else {
            check.removeClass('active');
            check.closest('tr').removeClass('active');
        }
		
        check.prop('checked', checked);
    }
	
    function selectAllRows() {
        if ($("#fileGrid th.scexpdatarowitem.check > input.scexpitemcheckbox").prop('checked')) {
            $("#fileGrid tbody tr")
				.addClass('active')
				.find('td.scexpdatarowitem.check > input.scexpitemcheckbox')
				.addClass('active')
				.prop('checked', true);
        } else {
            $("#fileGrid tbody tr")
				.removeClass('active')
				.find('td.scexpdatarowitem.check > input.scexpitemcheckbox')
				.removeClass('active')
				.prop('checked', false);
        }
    }
	
    function download(dir, name) {
        var form = $("form#downloadForm");
		
        if (form.length <= 0) {
            form = $("<form style='display: none;' id='downloadForm' method='post' action='file/download'><input type='text' name='name' /><input type='text' name='dir' /></form>").appendTo('body');
        }
		
        $("[name='name']", form).val(name);
        $("[name='dir']", form).val(dir);
		
        form.submit();
    }
	
    function loadDir(dir) {
        if (dir == null || dir == '') {
            return;
        }
        $.ajax({
            type: "POST",
            url: '<?php echo base_url(); ?>file/dir2json',
            dataType: "json",
            data: {'dir': dir}
        }).done(function (result) {
            result && result.files && updateGrid(result.files, result.dir);
        });
    }
	
    function updateGrid(files, dir) {
        var grid = $("#fileGrid").dataTable();
        grid.data('dir', dir);
        grid.fnClearTable();
		
        var count = 0;
        for (var i = 0, file = null; file = files[i++]; ) {
            count ++;
            var ext = getExtension(file.fileUrl);
			
            if (ext == '') {
                ext = 'folder';
            }
			
            var tr = [
                '<tr class="' + (i % 2 == 0 ? 'odd' : 'even') + ' scexpdatarow scexpdatarow' + i + ' ' + ext + '" data-url="' + file.fileUrl + '">',
                '<td class="scexpdatarowitem check"><input type="checkbox" id="chkbx12" class="scexpitemcheckbox" /></td>',
                '<td class="scexpdatarowitem name"><a class="scexpitemlink"><span class="scexpitemicon"></span><span class="scexpitemtxt ' + ext + '">' + getFileName(file.fileUrl) + '</span></a></td>',
                '<td class="scexpdatarowitem date"><span class="scexpitemtxt ' + ext + '">' + file.lastModifiedDate + '</span></td>',
                '<td class="scexpdatarowitem size"><span class="scexpitemtxt ' + ext + '">' + file.fileSize + '</span></td>',
                '</tr>'
            ];
			
            grid.fnAddTr($(tr.join())[0]);
        }
		
        $("#fileGrid th.scexpdatarowitem.check > input.scexpitemcheckbox").prop('checked', false);
        
        if (!count && dir == $("select[name='ws'] option:selected").val() + "/") {
            $("#fileGrid thead").addClass('empty');
        } else {
            $("#fileGrid thead").removeClass('empty');
        }
		
        updateBreadcrumbList(dir);
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
	
    function updateBreadcrumbList(dir) {
        var workspace = eval('(<?php echo json_encode(isset($entity) && $entity ? $entity : ''); ?>)');
        var bc = $(".filebreadcrumblist");
		
        bc.empty();
		
        var parts = dir.trim().split('/');
        var subdir = "";
		
        for (var i = 0, part = null; part = parts[i++]; ) {
            if (part == '') {
                continue;
            }
			
            subdir += part + "/";
			
            if (part == workspace['ws_directory']) {
                part = workspace['ws_name'];
            }
			
            if (!bc.is(':empty')) {
                $('<li><span class="sep"><i class="fa fa-angle-right"></i></span></li>').appendTo(bc);
            }
			
            $('<li><a href="#' + subdir + '">' + part + '</a></li>').appendTo(bc);
        }
    }
	
    function initContextmenu() {
        var options = {width: 190, items: [
                {text: "Rename", icon: "sample-css/ei0021-16.gif", alias: "rename", action: menuAction},
                {text: "Delete", icon: "sample-css/ei0021-16.gif", alias: "delete", action: menuAction},
            ],
        };
		
        $('#fileGrid tbody').contextmenu(options);
    }
	
    function menuAction() {
        switch (this.data.alias) {
            case 'rename':
                rename();
				
                break;
            case 'delete':
                __delete();
				
                break;
        }
    }
	
    function __delete() {
        var dir = $("#fileGrid").data('dir');
		
        if (dir == null || dir == '') {
            return;
        }
		
        var urls = [];
		
        $("#fileGrid tbody tr.active").each(function () {
            urls[urls.length] = dir + "/" + $(this).data('url');
        });
		
        if (urls == null || urls.length <= 0) {
            return;
        }
		
        $.ajax({
            type: "POST",
            url: '<?php echo base_url(); ?>file/delete',
            dataType: "json",
            data: {'urls': urls}
        }).done(function (result) {
            result && loadDir($("#fileGrid").data('dir'));
        });
    }
	
    var lastSelectedRow = null;
	
    function rename() {
        var container = lastSelectedRow.find('td.name .scexpitemtxt');
        var orgName = container.html();
		
        $('<input class="rename" type="text" value="' + orgName + '" />')
			.data('original-name', orgName)
			.appendTo(container.empty())
			.focus()
			.select();
    }
	
    function cancelRename() {
        var container = lastSelectedRow.find('td.name .scexpitemtxt');
        container.html(container.find("input.rename").data('original-name'));
    }
</script>
