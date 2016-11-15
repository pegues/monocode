
<?php /* FTP Connection Manager Tool Title: Start */ ?>
<div class="filemanager_tooltitle">
    <div class="tooltitlebarwrapper">
		<div class="toolbartitle">FTP Connection Manager</div>

		<div class="clear"></div>
	</div>

    <div class="clear"></div>
</div>
<?php /* FTP Connection Manager Tool Title: End */ ?>

<?php /* FTP Connection Manager Title Separator: Start */ ?>
<div class="filemanager_titlesep"><span></span></div>
<?php /* FTP Connection Manager Title Separator: End */ ?>

<?php /* FTP Connection Manager: Start */ ?>
<div class="filemanager_main custon_scroll_bar ftpmanager">
    <div class="leftcol_actions_control">
        <ul class="leftcolcontrolslist">
            <li class="odd connect first" 
                data-action-type="calljs" 
                data-action="ftp.connectClick">
                <i class="fa fa-plug"></i>
                <span>Connect to Selected</span>
            </li>
            <li class="even add" 
                data-action-type="popup" 
                data-file-url="ftp/ftpdetails" 
                data-title="Create New FTP Connection">
                <i class="fa fa-plus-square"></i>
                <span>Add FTP Connection</span>
            </li>
            <li class="odd edit" 
                data-action='ftp.edit' 
                data-action-type="calljs" 
                data-file-url="ftp/ftpdetails" 
                data-title="Edit FTP Connection">
                <i class="fa fa-edit"></i>
                <span>Edit FTP Connection</span>
            </li>
            <li class="even delete last" 
                data-action='ftp.delete' 
                data-action-type="calljs" 
                data-file-url="ftp/delete_array">
                <i class="fa fa-times"></i>
                <span>Delete Selected</span>
            </li>
        </ul>

        <div class="clear"></div>
    </div>

    <div class="ftpconnectionswrapper">
        <ul id="ftpsavedgridlilist" class="ftpconnectionslist ftpsavedgrid grid">
        </ul>

        <div class="clear"></div>
    </div>

    <div class="clear"></div>
</div>
<?php /* FTP Connection Manager: End */ ?>
