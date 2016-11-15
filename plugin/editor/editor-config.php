<?php 
if(isset($_POST['update'])){
	foreach($_POST as $key=>$val){
		if($val!=''){
			update_option($key,$val);
		}
	}
	set_status('success','Successfully saved.');
}
?>
<form action="" class="form" method="post">
	<ol class="breadcrumb 2">
		<li><a href="<?php echo SystemModule()->getBasePath(); ?>admin"><i class="entypo-home"></i>Home</a></li>
		<li class="active"><strong>Default Editor Configuration---</strong></li>
	</ol>

	<h1><span>Default Editor Configuration</span></h1>

	<br />
	<?php 
	echo get_status(); ?>
	
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-primary" data-collapsed="0">
			
				<div class="panel-body">
					<div class="form-horizontal form-groups-bordered">
			
						<div class="form-group">
							<label class="col-sm-3 control-label">Editor Title</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" name="editor_title" value="<?php echo get_option('editor_title'); ?>" id="editor_title" />
							</div>
						</div>
							
						<div class="form-group">
							<label class="col-sm-3 control-label">Page Theme</label>
							<div class="col-sm-9">
								<?php echo get_page_theme(get_option('editor_page_theme')); ?>
							</div>
						</div>
							
						<div class="form-group">
							<label class="col-sm-3 control-label">Workspace Path</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" name="editor_work_space_path" value="<?php echo get_option('editor_work_space_path'); ?>" id="editor_work_space_path" />
							</div>
						</div>
							
						<div class="form-group">
							<label class="col-sm-3 control-label">Tab Spaces</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" value="<?php echo get_option('editor_tab_spaces'); ?>" name="editor_tab_spaces" id="editor_tab_spaces" />
							</div>
						</div>
							
						<div class="form-group">
							<label class="col-sm-3 control-label">Toggle Word Wrapping</label>
							<div class="col-sm-9">
								<?php echo get_word_wrap(get_option('editor_toggle_word_wrapping')); ?>
							</div>
						</div>
							
						<div class="form-group">
							<label class="col-sm-3 control-label">Font Size</label>
							<div class="col-sm-9">
								<?php echo get_font_size(get_option('font_size')); ?>
							</div>
						</div>
							
						<div class="form-group">
							<label class="col-sm-3 control-label">Show Print Margin</label>
							<div class="col-sm-9">
								<?php echo get_print_margin(get_option('show_print_margin')); ?>
							</div>
						</div>
							
						<div class="form-group">
							<label class="col-sm-3 control-label">Editor Read Only</label>
							<div class="col-sm-9">
								<?php echo get_red_only(get_option('editor_read_only')); ?>
							</div>
						</div>
							
						<div class="form-group">
							<label class="col-sm-3 control-label">Editor Theme</label>
							<div class="col-sm-9">
								<?php echo get_editor_theme(get_option('editor_theme')); ?>
							</div>
						</div>
							
						<div class="form-group">
							<label class="col-sm-3 control-label">Editor Syntax Mode</label>
							<div class="col-sm-9">
								<?php echo get_editor_syntax(get_option('editor_default_mode')); ?>
							</div>
						</div>
							
						<div class="form-group">
							<label class="col-sm-3 control-label">Autodetect Mode by File Extension</label>
							<div class="col-sm-9">
								<?php echo get_auto_detect(get_option('auto_file_extension_detect_mode')); ?>
							</div>
						</div>
							
						<div class="form-group">
							<label class="col-sm-3 control-label"></label>
							<div class="col-sm-9">
								<button class="btn btn-green btn-icon icon-left" name="update" type="submit"><i class="entypo-arrows-ccw"></i>Update Configuration</button>
							</div>
						</div>
						
					</div>
				</div>
			</div>
		</div>
    </div>
</form>

<div class="clearfix"></div>