<?php /* Workspace Manager Tool Title: Start */ ?>
<div class="filemanager_tooltitle">
    <div class="tooltitlebarwrapper">
		<div class="toolbartitle">Workspace Manager</div>

		<div class="clear"></div>
	</div>

    <div class="clear"></div>
</div>
<?php /* Workspace Manager Tool Title: End */ ?>

<?php /* Workspace Manager Title Separator: Start */ ?>
<div class="filemanager_titlesep"><span></span></div>
<?php /* Workspace Manager Title Separator: End */ ?>

<?php /* Workspace Manager: Start */ ?>
<div class="filemanager_main custon_scroll_bar projectmanager">
    <div class="leftcol_actions_control">
        <ul class="leftcolcontrolslist">
			<li class="even add" 
                data-action-type="calljs" 
                data-action="workspace.create"
                data-file-url="workspace/create" 
                data-title="Create New workspace">
                <i class="fa fa-plus-square"></i>
                <span>Create New workspace</span>
            </li>
            <li class="odd edit" 
                data-action-type="calljs" 
                data-action='workspace.edit' 
                data-file-url="workspace/edit" 
                data-title="Rename workspace">
                <i class="fa fa-edit"></i>
                <span>Rename workspace</span>
            </li>
            <li class="even delete" 
                data-action-type="calljs" 
                data-action="workspace.remove">
                <i class="fa fa-times"></i>
                <span>Delete Selected Workspace</span>
            </li>
            <li class="odd export" 
                data-action-type="calljs" 
                data-action="workspace.export">
                <i class="fa fa-expand"></i>
                <span>Export Selected Workspace</span>
            </li>
            <li class="even import last" 
                data-action-type="popup" 
                data-file-url="workspace/import" 
                data-title="Import Zip File">
                <i class="fa fa-exchange"></i>
                <span>Import Workspace</span>
            </li>
        </ul>

        <div class="clear"></div>
    </div>

    <div class="workspace-list-wrapper">
        <ul id="workspace-list" class="workspace-list grid"></ul>
		
        <div class='clear'></div>

        <div class='workspace-announcement message' id="workspace-announcement-available">
            You have <span class="count"></span> workspace(s) remaining.

            <div class='clear'></div>
        </div>
		
        <div class='workspace-announcement message' id="workspace-announcement-create">
            Click <a data-action-type='calljs' data-action="workspace.create" data-title='Create New workspace' data-file-url='workspace/create'>here</a> to create your first workspace.

            <div class='clear'></div>
        </div>
		
        <div class='workspace-announcement message' id="workspace-announcement-upgrade">
            <a href='<?php echo base_url(); ?>membership'>Upgrade</a> your plan if you need more than <span class="count"></span> workspace(s).
			
            <div class='clear'></div>
        </div>
		
        <div class='workspace-announcement message' id="workspace-announcement-upgrade-for-zero">
            Your account does not come with any workspaces. Please upgrade your account by clicking <a href="<?php echo base_url(); ?>membership">here</a>.
            
			<div class='clear'></div>
        </div>
		
        <div class="clear"></div>
    </div>

    <div class="clear"></div>
</div>
<?php /* Project Manager: End */ ?>
