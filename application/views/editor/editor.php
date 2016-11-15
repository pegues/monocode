<?php /* Editor Page: Start */ ?>
<div class="wrapper">
	
	<?php /* Header Toolbar Region: Start */ ?>
	<div class="headertoolbar ui-layout-north">
		<div class="headertoolbar_inside">
			<?php include('includes/top_nav.php'); ?>
			
			<div class="clear"></div>
		</div>
		
		<div class="clear"></div>
	</div>
	<?php /* Header Toolbar Region: End */ ?>
	
	<?php /* File Manager Holder: Start */ ?>
	<div id="filemanager" class="filemanager_holder ui-layout-west">
		<div class="filemanager_inside">
			
			<?php /* Sidebar Options Toolbar: Start */ ?>
			<div class="filemanager_toolbar">
				<?php include('includes/options_tool_list.php'); ?>
				
				<div class="clear"></div>
			</div>
			<?php /* Sidebar Options Toolbar: End */ ?>
			
			<div class="active" data-layout-type="left_manager" data-manager-id="files_manager_container">
				<?php include("includes/files_manager.php"); ?>
			</div>
			
			<div data-layout-type="left_manager" data-manager-id="workspace_manager_container">
				<?php include("includes/workspace_manager.php"); ?>
			</div>
			
			<div data-layout-type="left_manager" data-manager-id="ftps_manager_container">
				<?php include("includes/ftps_manager.php"); ?>
			</div>
		  
			<div data-layout-type="left_manager" data-manager-id="database_manager_container">
				<?php include("includes/database_manager.php"); ?>
			</div>
			
			<div class="clear"></div>
		</div>
		
		<div class="clear"></div>
	</div>
	<?php /* File Manager Holder: End */ ?>
	
	<?php /* Editor Holder: Start */ ?>
	<div id="mainContent" class="editor_holder ui-layout-center">
		<div class="editor_inside">
			
			<?php /* Editor Tab Bar: Start */ ?>
			<?php include('includes/file_tabs.php'); ?>
			<?php /* Editor Tab Bar: End */ ?>
			
			<div class="clear"></div>
			
			<?php /* Toolbar: Start */ ?>
			<?php include('includes/file_toolbar.php'); ?>
			<?php /* Toolbar: End */ ?>
			
			<?php /* Editor Coder: Start */ ?>
			<div class="editorsurround">
				
				<?php include('welcome.php'); ?>
				
				<div id="statusbar" class="active">
					<div class="statusbarwrapper">
						<span id="tagbar" class="statustaglevels"></span>
						<span class="linecolformat"></span>
					</div>
				</div>
				
				<div class="clear"></div>
			</div>
			<?php /* Editor Coder: End */ ?>
			
			<div class="clear"></div>
		</div>
		
		<div class="clear"></div>
	</div>
	<?php /* Editor Holder: End */ ?>
	
	<?php /* Side Tools Holder: Start */ ?>
	<div class="sidetools_holder ui-layout-east">
		<div class="sidetools_inside">
			<?php include('includes/side_tools.php'); ?>
			
			<div class="clear"></div>
		</div>
		
		<div class="clear"></div>
	</div>
	<?php /* Side Tools Holder: End */ ?>
	
	<?php /* Footer Toolbar Region: Start */ ?>
	<div class="footertoolbar ui-layout-south">
		<div class="footertoolbar_inside">
			
			<div class="footeroutputwrapper">
				<div class="footeroutputwrapper_inside">
					
					<?php /* Terminal: Start */ ?>
					<?php include('includes/terminal.php'); ?>
					<?php /* Terminal: End */ ?>
					
					<?php /* Search Results: Start */ ?>
					<?php include('includes/search_results.php'); ?>
					<?php /* Search Results: End */ ?>
					
					<?php /* Footer Feature Tabs: Start */ ?>
					<?php include('includes/footer_feature_tabs.php'); ?>
					<?php /* Footer Feature Tabs: End */ ?>
					
					<div class="clear"></div>
				</div>
				
				<div class="clear"></div>
			</div>
			
			<div class="clear"></div>
		</div>
		
		<div class="clear"></div>
	</div>
	<?php /* Footer Toolbar Region: End */ ?>

	<div class="clear"></div>
</div>
<?php /* Editor Page: End */ ?>
