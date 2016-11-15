<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
        <title>Find, Replace, Find in Files</title>

        <link href="<?php echo base_url(); ?>core/css/popupcontent.css" rel="stylesheet" media="all" />
        <link href="<?php echo base_url(); ?>core/css/fontawesome/font-awesome.css" rel="stylesheet" media="all" />
        <link href="<?php echo base_url(); ?>themes/editor/<?php echo get_option("editor_page_theme") ?>/theme.css" rel="stylesheet" data-default-theme="<?php echo get_option("editor_page_theme") ?>" media="all" />
        <script src="<?php echo base_url(); ?>core/js/jquery-1.10.2.min.js" type="text/javascript"></script>

        <script type="text/javascript">
            var options = null;
            function loadOption() {
                var value = options[this.name];
                if (typeof value != 'undefined') {
                    var type = this.type ? this.type.toLowerCase() : '';
                    if (type == 'text') {
                        this.value = value;
                    } else {
                        this.checked = this.value == value;
                    }
                }
            }
            function saveOption() {
                var name = this.name;
                var type = this.type ? this.type.toLowerCase() : '';
                var value = this.value;
                if (type == 'checkbox') {
                    options[name] = this.checked ? value : '';
                } else {
                    options[name] = value;
                }
                
                parent.sceditor.call("base.saveOption('search');", options);
            }
            $(document).ready(function (e) {
                $('body').css({'overflow': 'hidden'}).addClass('find');

                // Accordion Navigation Items
                $('li.accbttn').on('click', function () {
                    $('div.accordionitem, li.accbttn').removeClass('active');
                    var tab = $(this).addClass('active').data('tab');
                    var first = $('div.accordionitem[data-tab="' + tab + '"]')
                            .addClass('active')
                            .find("input[name]").each(loadOption).get(0);
                    if (first) {
                        $(first).focus().select();
                    }
                });

                $("#find-form").submit(find);
                $("#replace-form").submit(replace);
                $("#findinfiles-form").submit(findInFiles);
                
                options = eval('(<?php echo isset($options['search']) && $options['search'] ? str_replace("'", "\'", str_replace("\\", "\\\\", $options['search'])) : 'null' ?>)');
                if (!options) {
                    options = {};
                }

                var editor = parent.sceditor.call("base.getEditorInstance(base.tab.getActiveId())");
                if (editor) {
                    editor = editor.instance;
                    var range = editor.selection.getRange();
                    if (range.start.row == range.end.row && range.start.column == range.end.column) {
                        enableReplaceInSelection(true);
                        range = editor.selection.getWordRange(range.start.row, range.start.column);
                    }
                    options.needle = editor.session.getTextRange(range);
                }

                options.dir = parent.sceditor.call("base.file.getSelectedDir()").replace(parent.sceditor.call("base.workspace.getActiveDirectory()"), '');

                $('li.accbttn[data-tab="<?php echo $_GET['tab']; ?>"]').click();

                $("input[name]").change(saveOption);
            });

            function cancelled() {
                parent.sceditor.call("base.closePopup()", {}, POPUP_ID);
            }
            
            function enableReplaceInSelection(enable) {
                $("input[name='replaceInSelection']").attr("disabled", enable);
                //$("input[name='replaceInSelection']").parent().css({opacity: '0.40'});
            }

            function find() {
                var params = $(this).serializeArray();
                var findmode = 0;
                var needle = "";
                var options = {};
                for (var i = 0, param = null; param = params[i++]; ) {
                    if (param.name == 'needle') {
                        needle = param.value;
                    } else if (param.name == 'findmode') {
                        findmode = parseInt(param.value);
                    } else {
                        options[param.name] = parseInt(param.value) > 0;
                    }
                }
                if (needle.length <= 0) {
                    parent.sceditor.call("base.notify()", {msg: 'Please enter the search string.', 'type': 'error'});
                    return false;
                }

                var obj = {mode: findmode, needle: needle, options: options};
                //try {
                parent.sceditor.call("base.file.search()", obj, POPUP_ID);
                //} catch (e) {}
                return false;
            }

            function replace() {
                var params = $(this).serializeArray();
                var findmode = 0;
                var needle = "";
                var replace = "";
                var options = {};
                for (var i = 0, param = null; param = params[i++]; ) {
                    if (param.name == 'needle') {
                        needle = param.value;
                    } else if (param.name == 'replace') {
                        replace = param.value;
                    } else if (param.name == 'findmode') {
                        findmode = parseInt(param.value);
                    } else {
                        options[param.name] = parseInt(param.value) > 0;
                    }
                }

                if (needle.length <= 0) {
                    parent.sceditor.call("base.notify()", {msg: 'Please enter the search string.', 'type': 'error'});
                    return false;
                }

                var obj = {mode: findmode, needle: needle, replace: replace, options: options};
                //try {
                parent.sceditor.call("base.file.replace()", obj);
                //} catch (e) {}
                return false;
            }

            function findInFiles() {
                var params = $(this).serializeArray();
                var findmode = 0;
                var needle = "";
                var replace = "";
                var filter = "";
                var workspace = "";
                var dir = "";
                var subfolder = "";
                var options = {};

                for (var i = 0, param = null; param = params[i++]; ) {
                    if (param.name == 'needle') {
                        needle = param.value;
                    } else if (param.name == 'replace') {
                        replace = param.value;
                    } else if (param.name == 'filter') {
                        filter = param.value;
                    } else if (param.name == 'workspace') {
                        workspace = param.value;
                    } else if (param.name == 'dir') {
                        dir = param.value;
                    } else if (param.name == 'subfolder') {
                        subfolder = parseInt(param.value);
                    } else if (param.name == 'findmode') {
                        findmode = parseInt(param.value);
                    } else {
                        options[param.name] = parseInt(param.value);
                    }
                }

                if (needle.length <= 0) {
                    parent.sceditor.call("base.notify()", {msg: 'Please enter the search string.', 'type': 'error'});
                    return false;
                }

                var obj = {needle: needle, replace: replace, filter: filter, workspace: workspace, dir: dir, subfolder: subfolder, options: options};
                //try {
                if (findmode == 0) {
                    parent.sceditor.call("base.file.searchInFiles()", obj, POPUP_ID);
                } else if (findmode == 1) {
                    parent.sceditor.call("base.file.replaceInFiles()", obj, POPUP_ID);
                }
                //} catch (e) {}
                return false;
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
    <body data-width='650px' data-height='450px' data-controls="{'Close':'cancelled'}">
        <div class="infopopup" style="height: 100%;">
            <div class="infooptionscontainer" style="padding-right: 0; height: 100%;"> <!-- -->

                <?php /* Find, Replace, Find in Files: Start */ ?>
                <div class="accordion headertabs">
                    <div class="accordion_inside">

                        <?php /* Find/Replace/Find in Files Navigation: Start */ ?>
                        <div class="accordionnav">
                            <div class="accordionnav_inside">
                                <ul class="accordionnavlist">
                                    <li class="accbttn" data-tab="find">
                                        <div>Find</div>
                                    </li>
                                    <li class="accbttn" data-tab="replace">
                                        <div>Replace</div>
                                    </li>
                                    <li class="accbttn" data-tab="findinfiles">
                                        <div>Find in Files</div>
                                    </li>
                                </ul>

                                <div class="clear"></div>
                            </div>

                            <div class="clear"></div>
                        </div>
                        <?php /* Find/Replace/Find in Files Navigation: End */ ?>

                        <?php /* Find/Replace/Find in Files Sections: Start */ ?>
                        <div class="accordionsections">
                            <div class="accordionsections_inside">

                                <?php /* Find: Start */ ?>
                                <div class="accordionitem" data-tab="find">
                                    <form id="find-form" class="" style="height: 100%;">
                                        <div class="searchfilesholder">

                                            <?php /* Options Container: Start */ ?>
                                            <div class="infopopupoptions">

                                                <?php /* Fields: Start */ ?>
                                                <div class="infopopupoptrow first last">
                                                    <label for="">Find what:</label>
                                                    <div class="infopopupfield">
                                                        <input type="text" name="needle" id="needle" class="text" value="" />

                                                        <div class="clear"></div>
                                                    </div>

                                                    <div class="clear"></div>
                                                </div>
                                                <?php /* Fields: End */ ?>
                                                <?php /* Checkbox Options: Start */ ?>
                                                <div class="infopopupoptrow">
                                                    <div class="infopopupfield checkbox">
                                                        <input type="checkbox" name="wholeWord" id="" class="checkbox" value="1" />Match whole word only

                                                        <div class="clear"></div>
                                                    </div>
                                                    <div class="infopopupfield checkbox">
                                                        <input type="checkbox" name="caseSensitive" id="" class="checkbox" value="1" />Match case

                                                        <div class="clear"></div>
                                                    </div>
                                                    <div class="infopopupfield checkbox">
                                                        <input type="checkbox" name="wrap" id="" class="checkbox" value="1" checked="checked" />Wrap around

                                                        <div class="clear"></div>
                                                    </div>

                                                    <div class="clear"></div>
                                                </div>
                                                <?php /* Checkbox Options: End */ ?>

                                                <div class="clear"></div>
                                            </div>
                                            <?php /* Options Container: End */ ?>

                                            <?php /* Control Buttons Column: Start */ ?>
                                            <div class="infopopupoptionsbuttons">
                                                <input type="hidden" name="findmode" value="0" />
                                                <button class="function_button" onclick="$(this).closest('form')[0].findmode.value = 0;">
                                                    <span>Find Next</span>
                                                </button>
                                                <button class="function_button" onclick="$(this).closest('form')[0].findmode.value = 1;">
                                                    <span>Find All in All Opened Documents</span>
                                                </button>
                                                <button class="function_button" onclick="$(this).closest('form')[0].findmode.value = 2;">
                                                    <span>Find All in Current Document</span>
                                                </button>

                                                <div class="clear"></div>
                                            </div>
                                            <?php /* Control Buttons Column: End */ ?>

                                            <div class="clear"></div>
                                        </div>

                                        <?php /* Bottom Options: Start */ ?>
                                        <div class="infoptionsbottom">
                                            <div class="infoptionsbottom_inside">

                                                <?php /* Column: Start */ ?>
                                                <div class="infooptionsbottomcol colspan9">
                                                    <div class="infooptionsbottomcol_inside">
                                                        <div class="infooptionsbottomcol_holder">

                                                            <?php /* Radio Options: Start */ ?>
                                                            <div class="infopopupoptrow">

                                                                <div class="infopopupsubsectitle">
                                                                    <span>Search Mode</span>

                                                                    <div class="clear"></div>
                                                                </div>

                                                                <div class="infopopupfield radio">
                                                                    <input type="radio" id="" class="radio" name="regExp" value="0" checked="checked" />Normal

                                                                    <div class="clear"></div>
                                                                </div>

                                                                <!--
                                                                <div class="infopopupfield radio">
                                                                        <input type="radio" id="" class="radio" name="findsearchmode" value="" />Extended (\n, \r, \t, \0, \x...)
                                                                        
                                                                        <div class="clear"></div>
                                                                </div>
                                                                -->

                                                                <div class="infopopupfield radio">
                                                                    <input type="radio" id="" class="radio" name="regExp" value="1" />Regular expression

                                                                    <div class="clear"></div>
                                                                </div>

                                                                <div class="clear"></div>
                                                            </div>
                                                            <?php /* Radio Options: End */ ?>

                                                            <div class="clear"></div>
                                                        </div>

                                                        <div class="clear"></div>
                                                    </div>

                                                    <div class="clear"></div>
                                                </div>
                                                <?php /* Column: End */ ?>

                                                <?php /* Column: Start */ ?>
                                                <div class="infooptionsbottomcol colspan3">
                                                    <div class="infooptionsbottomcol_inside">
                                                        <div class="infooptionsbottomcol_holder">

                                                            <?php /* Radio Options: Start */ ?>
                                                            <div class="infopopupoptrow">

                                                                <div class="infopopupsubsectitle">
                                                                    <span>Direction</span>

                                                                    <div class="clear"></div>
                                                                </div>

                                                                <div class="infopopupfield radio">
                                                                    <input type="radio" id="" class="radio" name="backwards" value="1" />Up

                                                                    <div class="clear"></div>
                                                                </div>
                                                                <div class="infopopupfield radio">
                                                                    <input type="radio" id="" class="radio" name="backwards" value="0" checked="checked" />Down

                                                                    <div class="clear"></div>
                                                                </div>

                                                                <div class="clear"></div>
                                                            </div>
                                                            <?php /* Radio Options: End */ ?>

                                                            <div class="clear"></div>
                                                        </div>

                                                        <div class="clear"></div>
                                                    </div>

                                                    <div class="clear"></div>
                                                </div>
                                                <?php /* Column: End */ ?>

                                                <div class="clear"></div>
                                            </div>

                                            <div class="clear"></div>
                                        </div>
                                        <?php /* Bottom Options: End */ ?>
                                    </form>										

                                    <div class="clear"></div>
                                </div>
                                <?php /* Find: End */ ?>

                                <?php /* Replace: Start */ ?>
                                <div class="accordionitem" data-tab="replace">
                                    <div class="searchfilesholder">
                                        <form id="replace-form" class="" style="height: 100%;">

                                            <?php /* Options Container: Start */ ?>
                                            <div class="infopopupoptions">

                                                <?php /* Fields: Start */ ?>
                                                <div class="infopopupoptrow first">
                                                    <label for="">Find what:</label>
                                                    <div class="infopopupfield">
                                                        <input type="text" name="needle" id="" class="text" value="" />

                                                        <div class="clear"></div>
                                                    </div>

                                                    <div class="clear"></div>
                                                </div>

                                                <div class="infopopupoptrow last">
                                                    <label for="">Replace with:</label>
                                                    <div class="infopopupfield">
                                                        <input type="text" name="replace" id="" class="text" value="" />

                                                        <div class="clear"></div>
                                                    </div>

                                                    <div class="clear"></div>
                                                </div>
                                                <?php /* Fields: End */ ?>

                                                <?php /* Checkbox Options: Start */ ?>
                                                <div class="infopopupoptrow">
                                                    <div class="infopopupfield checkbox">
                                                        <input type="checkbox" name="wholeWord" id="" class="checkbox" value="1" />Match whole word only

                                                        <div class="clear"></div>
                                                    </div>
                                                    <div class="infopopupfield checkbox">
                                                        <input type="checkbox" name="caseSensitive" id="" class="checkbox" value="1" />Match case

                                                        <div class="clear"></div>
                                                    </div>
                                                    <div class="infopopupfield checkbox">
                                                        <input type="checkbox" name="wrap" id="" class="checkbox" value="1" checked="checked" />Wrap around

                                                        <div class="clear"></div>
                                                    </div>

                                                    <div class="clear"></div>
                                                </div>
                                                <?php /* Checkbox Options: End */ ?>

                                                <div class="clear"></div>
                                            </div>
                                            <?php /* Options Container: End */ ?>

                                            <?php /* Control Buttons Column: Start */ ?>
                                            <div class="infopopupoptionsbuttons">
                                                <input type="hidden" name="findmode" value="0" />

                                                <?php /* Buttons: Start */ ?>
                                                <button class="function_button" onclick="$(this).closest('form')[0].findmode.value = 0;">
                                                    <span>Find Next</span>
                                                </button>
                                                <button class="function_button" onclick="$(this).closest('form')[0].findmode.value = 1;">
                                                    <span>Replace</span>
                                                </button>
                                                <button class="function_button" onclick="$(this).closest('form')[0].findmode.value = 2;">
                                                    <span>Replace All</span>
                                                </button>
                                                <button class="function_button" onclick="$(this).closest('form')[0].findmode.value = 3;">
                                                    <span>Replace All in All Opened Document</span>
                                                </button>
                                                <?php /* Buttons: End */ ?>

                                                <?php /* Checkbox Options: Start */ ?>
                                                <div class="infopopupoptrow">

                                                    <div class="infopopupfield checkbox">
                                                        <input id="" class="checkbox" type="checkbox" name="replaceInSelection" value="1" />Replace In Selection

                                                        <div class="clear"></div>
                                                    </div>

                                                    <div class="clear"></div>
                                                </div>
                                                <?php /* Checkbox Options: End */ ?>

                                                <div class="clear"></div>
                                            </div>
                                            <?php /* Control Buttons Column: End */ ?>

                                            <div class="clear"></div>
                                        </form>
                                    </div>

                                    <?php /* Bottom Options: Start */ ?>
                                    <div class="infoptionsbottom">
                                        <div class="infoptionsbottom_inside">

                                            <?php /* Column: Start */ ?>
                                            <div class="infooptionsbottomcol colspan9">
                                                <div class="infooptionsbottomcol_inside">
                                                    <div class="infooptionsbottomcol_holder">

                                                        <?php /* Radio Options: Start */ ?>
                                                        <div class="infopopupoptrow">

                                                            <div class="infopopupsubsectitle">
                                                                <span>Search Mode</span>

                                                                <div class="clear"></div>
                                                            </div>

                                                            <div class="infopopupfield radio">
                                                                <input type="radio" id="" class="radio" name="regExp" value="0" checked="checked" />Normal

                                                                <div class="clear"></div>
                                                            </div>

                                                            <!--
                                                            <div class="infopopupfield radio">
                                                                    <input type="radio" id="" class="radio" name="findsearchmode" value="" />Extended (\n, \r, \t, \0, \x...)
                                                                    
                                                                    <div class="clear"></div>
                                                            </div>
                                                            -->

                                                            <div class="infopopupfield radio">
                                                                <input type="radio" id="" class="radio" name="regExp" value="1" />Regular expression

                                                                <div class="clear"></div>
                                                            </div>

                                                            <div class="clear"></div>
                                                        </div>
                                                        <?php /* Radio Options: End */ ?>

                                                        <div class="clear"></div>
                                                    </div>

                                                    <div class="clear"></div>
                                                </div>

                                                <div class="clear"></div>
                                            </div>
                                            <?php /* Column: End */ ?>

                                            <?php /* Column: Start */ ?>
                                            <div class="infooptionsbottomcol colspan3">
                                                <div class="infooptionsbottomcol_inside">
                                                    <div class="infooptionsbottomcol_holder">

                                                        <?php /* Radio Options: Start */ ?>
                                                        <div class="infopopupoptrow">

                                                            <div class="infopopupsubsectitle">
                                                                <span>Direction</span>

                                                                <div class="clear"></div>
                                                            </div>

                                                            <div class="infopopupfield radio">
                                                                <input type="radio" id="" class="radio" name="backwards" value="1" />Up

                                                                <div class="clear"></div>
                                                            </div>
                                                            <div class="infopopupfield radio">
                                                                <input type="radio" id="" class="radio" name="backwards" value="0" checked="checked" />Down

                                                                <div class="clear"></div>
                                                            </div>

                                                            <div class="clear"></div>
                                                        </div>
                                                        <?php /* Radio Options: End */ ?>

                                                        <div class="clear"></div>
                                                    </div>

                                                    <div class="clear"></div>
                                                </div>

                                                <div class="clear"></div>
                                            </div>
                                            <?php /* Column: End */ ?>

                                            <div class="clear"></div>
                                        </div>

                                        <div class="clear"></div>
                                    </div>
                                    <?php /* Bottom Options: End */ ?>

                                    <div class="clear"></div>
                                </div>
                                <?php /* Replace: End */ ?>

                                <?php /* Find in Files: Start */ ?>
                                <div class="accordionitem" data-tab="findinfiles">
                                    <div class="searchfilesholder">
                                        <form id="findinfiles-form" class="" style="height: 100%;">

                                            <?php /* Options Container: Start */ ?>
                                            <div class="infopopupoptions">

                                                <?php /* Fields: Start */ ?>
                                                <div class="infopopupoptrow first">
                                                    <label for="">Find what:</label>
                                                    <div class="infopopupfield">
                                                        <input type="text" id="" name="needle" class="text" value="" />

                                                        <div class="clear"></div>
                                                    </div>

                                                    <div class="clear"></div>
                                                </div>

                                                <div class="infopopupoptrow">
                                                    <label for="">Replace with:</label>
                                                    <div class="infopopupfield">
                                                        <input type="text" id="" name="replace" class="text" value="" />

                                                        <div class="clear"></div>
                                                    </div>

                                                    <div class="clear"></div>
                                                </div>

                                                <div class="infopopupoptrow">
                                                    <label for="">Filters:</label>
                                                    <div class="infopopupfield">
                                                        <input type="text" id="" class="text" name="filter" value="*.*" />

                                                        <div class="clear"></div>
                                                    </div>

                                                    <div class="clear"></div>
                                                </div>

                                                <div class="infopopupoptrow">
                                                    <label for="workspace">Workspace:</label>
                                                    <div class="infopopupfield">
                                                        <?php
                                                        $dbwc = (int) get_user_feature('work_space');
                                                        $_ws = get_option('ws');
                                                        $_ws = json_decode($_ws, true);

                                                        $index = 0;
                                                        if (is_array($_ws) && sizeof($_ws) > 0) {
                                                            ?>
                                                            <select id="" class="select" name="workspace" id="workspace">
                                                                <?php
                                                                foreach ($_ws as $key => $ws) {
                                                                    if ($ws['ws_status'] == 'enable') {
                                                                        ?>
                                                                        <option value="<?php echo $key; ?>/" <?php echo ($ws['ws_active'] == 'true') ? 'selected="selected"' : ''; ?>>
                                                                            <?php echo $ws['ws_name']; ?>
                                                                        </option>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                            <?php
                                                        }
                                                        ?>

                                                        <div class="clear"></div>
                                                    </div>

                                                    <div class="clear"></div>
                                                </div>

                                                <div class="infopopupoptrow last">
                                                    <label for="">Directory:</label>
                                                    <div class="infopopupfield">
                                                        <input type="text" name="dir" id="" class="text" value="/" />

                                                        <div class="clear"></div>
                                                    </div>

                                                    <div class="clear"></div>
                                                </div>
                                                <?php /* Fields: End */ ?>

                                                <div class="clear"></div>
                                            </div>
                                            <?php /* Options Container: End */ ?>

                                            <?php /* Control Buttons Column: Start */ ?>
                                            <div class="infopopupoptionsbuttons">

                                                <input type="hidden" name="findmode" value="0" />

                                                <?php /* Buttons: Start */ ?>
                                                <button class="function_button" onclick="$(this).closest('form')[0].findmode.value = 0;">
                                                    <span>Find All</span>
                                                </button>
                                                <button class="function_button" onclick="$(this).closest('form')[0].findmode.value = 1;">
                                                    <span>Replace in Files</span>
                                                </button>
                                                <?php /* Buttons: End */ ?>

                                                <?php /* Checkbox Options: Start */ ?>
                                                <div class="infopopupoptrow">

                                                    <!--
                                                    <div class="infopopupfield checkbox">
                                                            <input type="checkbox" id="" class="checkbox" value="1" />Follow current doc.
                                                            
                                                            <div class="clear"></div>
                                                    </div>
                                                    -->

                                                    <div class="infopopupfield checkbox">
                                                        <input type="checkbox" id="" class="checkbox" name="subfolder" value="1" checked="checked" />In all sub-folders

                                                        <div class="clear"></div>
                                                    </div>

                                                    <!--
                                                    <div class="infopopupfield checkbox">
                                                            <input type="checkbox" id="" class="checkbox" value="1" />In hidden folders
                                                            
                                                            <div class="clear"></div>
                                                    </div>
                                                    -->

                                                    <div class="infopopupfield checkbox">
                                                        <input type="checkbox" name="wholeWord" id="" class="checkbox" value="1" />Match whole word only

                                                        <div class="clear"></div>
                                                    </div>
                                                    <div class="infopopupfield checkbox">
                                                        <input type="checkbox" name="caseSensitive" id="" class="checkbox" value="1" />Match case

                                                        <div class="clear"></div>
                                                    </div>

                                                    <div class="clear"></div>
                                                </div>
                                                <?php /* Checkbox Options: End */ ?>

                                                <div class="clear"></div>
                                            </div>
                                            <?php /* Control Buttons Column: End */ ?>

                                            <div class="clear"></div>
                                        </form>                                                                        
                                    </div>

                                    <?php /* Bottom Options: Start */ ?>
                                    <div class="infoptionsbottom">
                                        <div class="infoptionsbottom_inside">

                                            <?php /* Column: Start */ ?>
                                            <div class="infooptionsbottomcol colspan12">
                                                <div class="infooptionsbottomcol_inside">
                                                    <div class="infooptionsbottomcol_holder">

                                                        <?php /* Radio Options: Start */ ?>
                                                        <div class="infopopupoptrow">

                                                            <div class="infopopupsubsectitle">
                                                                <span>Search Mode</span>

                                                                <div class="clear"></div>
                                                            </div>

                                                            <div class="infopopupfield radio">
                                                                <input type="radio" id="" class="radio" name="regExp" value="0" checked="checked" />Normal

                                                                <div class="clear"></div>
                                                            </div>

                                                            <!--
                                                            <div class="infopopupfield radio">
                                                                    <input type="radio" id="" class="radio" name="findsearchmode" value="" />Extended (\n, \r, \t, \0, \x...)
                                                                    
                                                                    <div class="clear"></div>
                                                            </div>
                                                            -->

                                                            <div class="infopopupfield radio">
                                                                <input type="radio" id="" class="radio" name="regExp" value="1" />Regular expression

                                                                <div class="clear"></div>
                                                            </div>

                                                            <div class="clear"></div>
                                                        </div>
                                                        <?php /* Radio Options: End */ ?>

                                                        <div class="clear"></div>
                                                    </div>

                                                    <div class="clear"></div>
                                                </div>

                                                <div class="clear"></div>
                                            </div>
                                            <?php /* Column: End */ ?>

                                            <div class="clear"></div>
                                        </div>

                                        <div class="clear"></div>
                                    </div>
                                    <?php /* Bottom Options: End */ ?>

                                    <div class="clear"></div>
                                </div>
                                <?php /* Find in Files: End */ ?>

                                <div class="clear"></div>
                            </div>

                            <div class="clear"></div>
                        </div>
                        <?php /* Find/Replace/Find in Files Sections: End */ ?>

                        <div class="clear"></div>
                    </div>

                    <div class="clear"></div>
                </div>
                <?php /* Find, Replace, Find in Files: End */ ?>

                <div class="clear"></div>
            </div>

            <div class="clear"></div>
        </div>
    </body>
</html>