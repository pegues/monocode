<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
        <title>New Folder</title>

        <link href="<?php echo base_url(); ?>core/css/popupcontent.css" rel="stylesheet" media="all" />
        <script src="<?php echo base_url(); ?>core/js/jquery-1.10.2.min.js" type="text/javascript"></script>

        <script type="text/javascript">
            $(function() {
                $("[name='fileName']").val($.trim(parent.sceditor.call('base.file.getSelected()').find(">div.icon>span").html()));
            });

            function cancel() {
                /*
                 $("input").each(function () {
                 parent.configInput($(this).attr('data-key'), $(this).attr('data-value'));
                 });
                 
                 $("select").each(function () {
                 parent.configSelect($(this).attr('data-key'), $(this).attr('data-value'));
                 
                 if ($(this).attr('data-key') == 'editor_page_theme') {
                 parent.configSelect('cancle' + $(this).attr('data-key'), $(this).attr('data-value'));
                 }
                 });
                 */

                //parent.location.reload();
                parent.sceditor.call('base.closePopup()', {}, POPUP_ID);
            }

            function rename() {
                var obj = $("#form").serializeArray()[0];
                obj.value = $.trim(obj.value);
                var name = obj.value;
                if (name == "") {
                    parent.sceditor.call("base.notify()", {msg: 'Please enter the file name.', 'type': 'error'});
                    return  false;
                }
                if (name.indexOf("\"") > -1
                        || name.indexOf("\\") > -1
                        || name.indexOf("/") > -1
                        || name.indexOf("|") > -1
                        || name.indexOf(":") > -1
                        || name.indexOf("?") > -1
                        || name.indexOf("*") > -1
                        || name.indexOf("<") > -1
                        || name.indexOf(">") > -1
                        ) {
                    parent.sceditor.call("base.notify()", {msg: 'The file name is not valid. Please try another name.', 'type': 'error'});

                    return;
                }
                parent.sceditor.call('base.file.rename1()', name, POPUP_ID);
                return false;
            }
        </script>
    </head>
    <body data-width='400px' data-height='140px' data-controls="{'Rename':'rename','Cancel':'cancel'}">
        <div class="infopopup">

            <form id="form" method="post" onsubmit="return rename();">

                <div class="infopopuptitle">
                    <span>Please enter the name.</span>
                </div>

                <div class="infooptionscontainer"> <!-- -->
                    <div class="infopopupsubtitle">
                        <span>
                            Name: <input type="text" name="fileName" style="width: 290px" />
                        </span>  
                        <div class="clear"></div>
                    </div>

                    <div class="clear"></div>
                </div>

            </form>
            <div class="clear"></div>
        </div>
    </body>
</html>