
<?php /* Database Manager Tool Title: Start */ ?>
<div class="filemanager_tooltitle">
    <div class="tooltitlebarwrapper">
		<div class="toolbartitle">Database Manager</div>

		<div class="clear"></div>
	</div>

    <div class="clear"></div>
</div>
<?php /* Database Manager Tool Title: End */ ?>

<?php /* Database Manager Title Separator: Start */ ?>
<div class="filemanager_titlesep"><span></span></div>
<?php /* Database Manager Title Separator: End */ ?>

<?php /* Database Manager: Start */ ?>
<div class="filemanager_main custon_scroll_bar databasemanager">
    <div class="leftcol_actions_control">
        <ul class="leftcolcontrolslist">
            <?php if ($phpmyadmin_open == 0) { ?>
            <li class="odd connect first" 
                data-action-type="opentab" 
                data-file-url="database/connect"
                data-title="Databases">
                <i class="fa fa-plug"></i>
                <span>Open databases</span>
            </li>
            <?php } else { ?>
            <li class="odd connect first" 
                data-action-type="calljs" 
                data-action="database.connect">
                <i class="fa fa-plug"></i>
                <span>Open databases</span>
            </li>
            <?php } ?>
			<li class="even add" 
                data-action-type="calljs" 
                data-action="database.create"
                data-file-url="database/create" 
                data-title="Create New Database">
                <i class="fa fa-plus-square"></i>
                <span>Create New Database</span>
            </li>
            <li class="odd edit" 
                data-action-type="calljs" 
                data-action='database.rename' 
                data-file-url="database/rename" 
                data-title="Rename Database">
                <i class="fa fa-edit"></i>
                <span>Rename Database</span>
            </li>
            <li class="even delete last" 
                data-action-type="calljs" 
                data-action="database.remove">
                <i class="fa fa-times"></i>
                <span>Delete Selected Database</span>
            </li>
        </ul>

        <div class="clear"></div>
    </div>

    <div class="database-list-wrapper">
        <ul id="database-list" class="database-list grid"></ul>
		
        <div class="clear"></div>

        <div class="database-announcement message" id="database-announcement-available">
            You have <span class="count"></span> database(s) remaining.

            <div class="clear"></div>
        </div>
		
        <div class="database-announcement message" id="database-announcement-create">
            Click <a data-action-type="calljs" data-action="database.create" data-title="Create New Database" data-file-url="database/create">here</a> to create your first database.

            <div class="clear"></div>
        </div>
		
        <div class="database-announcement message" id="database-announcement-upgrade">
            <a href="<?php echo base_url(); ?>membership">Upgrade</a> your plan if you need more than <span class="count"></span> database(s).
			
            <div class="clear"></div>
        </div>
		
        <div class="database-announcement message" id="database-announcement-upgrade-for-zero">
            Your account does not come with any databases. Please upgrade your account by clicking <a href="<?php echo base_url(); ?>membership">here</a>.
            
			<div class="clear"></div>
        </div>
		
        <div class="database-announcement message" id="database-announcement-userinfo" style="display: block;">
            <h4><i class="fa fa-user"></i> Database User Info</h4>
			
			<?php /* Current Database Information: Start */ ?>
            <div class="curdatbaseinfo">
                <div class="curdbusername">User Name: <?php echo $db_user['db_username']; ?></div>
                <div class="curdbpassword">Password: <?php echo $db_user['db_password']; ?></div>
                <div class="curdbprefix">DB Prefix: <?php echo $db_user['db_prefix']; ?></div>
				
				<div class="clear"></div>
            </div>
			<?php /* Current Database Information: End */ ?>
			
            <div class="clear"></div>
        </div>
		
        <div class="clear"></div>
    </div>

    <div class="clear"></div>
</div>
<?php /* Database Manager: End */ ?>
