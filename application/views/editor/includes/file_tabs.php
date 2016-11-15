
<div class="editortabbar">
    
	<?php /* Open New Tab: Start */ ?>
	<div class="newtabitem" data-action="newFile" data-action-type="calljs" data-file-id="new-editor">
        <span></span>
    </div>
	<?php /* Open New Tab: End */ ?>

    <div id="editorfilelistwrapper">
        <ul id="editorfilelist">
            <li style="overflow: hidden; width: 0;"><a>Test</a></li>
            <?php /* Tabs Populated Here */ ?>
        </ul>
    </div>

    <div class="clear"></div>

    <ul id="cloned_items">
        <?php /* Tabs Populated Here */ ?>
    </ul>
	
	<?php /* Tab Controls: Start */ ?>
	<ul id="tabcontrolarrows">
		
		<?php /* View Previous Tabs: Start */ ?>
		<li class="tabcontrolarrow arrowleft first">
			<span>
				<i class="fa fa-caret-left"></i>
			</span>
		</li>
		<?php /* View Previous Tabs: End */ ?>
		
		<?php /* View Next Tabs: Start */ ?>
		<li class="tabcontrolarrow arrowright">
			<span>
				<i class="fa fa-caret-right"></i>
			</span>
		</li>
		<?php /* View Next Tabs: End */ ?>
		
		<?php /* View All Open Files: Start */ ?>
		<li class="tabcontrolarrow arrowdown last">
			<span>
				<i class="fa fa-caret-down"></i>
			</span>
			<ul class="tabdropdownfileslist"></ul>
		</li>
		<?php /* View All Open Files: End */ ?>
		
	</ul>
	<?php /* Tab Controls: End */ ?>

    <div class="clear"></div>
</div>
