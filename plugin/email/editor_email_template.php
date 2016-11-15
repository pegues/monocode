<?php 
if(isset($_POST['save'])){
			$id=SystemModule()->Save(
				array(
					'TableName'=>prefix.'email_template'
				),
				array(
					'email_template_name',
					'email_group',
					'subject',
					'email_template_content',
					)
			);
			set_status('success','Saved.');
}
if(isset($_POST['save_send'])){
	$id=SystemModule()->Save(
				array(
					'TableName'=>prefix.'email_template'
				),
				array(
					'email_template_name',
					'email_group',
					'subject',
					'email_template_content',
					)
			);
			header('location:'.'?page=email/send_email.php');

	
}
SystemModule()->SetupEdit(array('TableName'=>prefix.'email_template'));
?>

	
	<ol class="breadcrumb 2">
		<li>
			<a href="<?php echo SystemModule()->getBasePath() ; ?>admin"><i class="entypo-home"></i>Home</a>
		</li>
		<li class="active">
			<strong>Email Template</strong>
		</li>
	</ol>
	
	<h1>
		<span>Email Template</span>
		<a href="?page=email/email_template.php" class="btn btn-orange btn-sm">Back to Email Template List</a>
	</h1>
	
	<br/>
	
	<?php echo get_status(); ?>

	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary" data-collapsed="0">
			
				<div class="panel-heading">
					<div class="panel-title">
						Email Template Information
					</div>
				</div>
				
				<div class="panel-body">
					<div class="form-horizontal form-groups-bordered">
						<form action="" method="post" id="send_email">
						<div class="form-group">
							<label class="col-sm-3 control-label">Email Template Name</label>
							<div class="col-sm-9"><input type="text" class="form-control" name="email_template_name" id="email_template_name" value="" />
							</div>
						</div>
                        
                       <?php 
					   $emailgroup=$db->get_results("select * from ".prefix."email_group");
					   ?> 
                       
                        <div class="form-group">
							<label class="col-sm-3 control-label">Email Group</label>
							<div class="col-sm-9"><select class="form-control" name="email_group" id="email_group">
                            <?php foreach($emailgroup as $val){ ?> 
                            <option value="<?php echo $val->email_group_id; ?>"><?php echo $val->email_group_name; ?></option>
                            <?php } ?>
                            </select>
							</div>
						</div>
                        
                        <div class="form-group">
							<label class="col-sm-3 control-label">Subject</label>
							<div class="col-sm-9"><input type="text" class="form-control" name="subject" id="subject" value="" />
							</div>
						</div>
                        
                        <div class="form-group">
							<label class="col-sm-3 control-label">Email Template Content</label>
							<div class="col-sm-9"><textarea class="form-control ckeditor" name="email_template_content"></textarea>
							</div>
						</div>
                        
                        <div class="form-group">
							<label class="col-sm-3 control-label"></label>
							<div class="col-sm-9">
								<button class="btn btn-blue" type="submit" name="save">Add/Update Email Template</button> &nbsp;&nbsp;&nbsp;<button  name="save_send" class="btn btn-blue">Send Email</button>
							</div>
						</div>
						</form>
					</div>
			</div>
            </div>
            </div>
            </div>
			
	<div class="clearfix"></div>
<link rel="stylesheet" href="assets/js/wysihtml5/bootstrap-wysihtml5.css">
 <link rel="stylesheet" href="assets/js/codemirror/lib/codemirror.css">
 <link rel="stylesheet" href="assets/js/uikit/css/uikit.min.css">
 <link rel="stylesheet" href="assets/js/uikit/addons/css/markdownarea.css">
        <!-- Bottom Scripts -->
 <script src="assets/js/gsap/main-gsap.js"></script>
 <script src="assets/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js"></script>
 <script src="assets/js/bootstrap.js"></script>
 <script src="assets/js/joinable.js"></script>
 <script src="assets/js/resizeable.js"></script>
 <script src="assets/js/neon-api.js"></script>
 <script src="assets/js/wysihtml5/wysihtml5-0.4.0pre.min.js"></script>
 <script src="assets/js/wysihtml5/bootstrap-wysihtml5.js"></script>
 <script src="assets/js/ckeditor/ckeditor.js"></script>
 <script src="assets/js/ckeditor/adapters/jquery.js"></script>
 <script src="assets/js/uikit/js/uikit.min.js"></script>
 <script src="assets/js/codemirror/lib/codemirror.js"></script>
 <script src="assets/js/marked.js"></script>
 <script src="assets/js/uikit/addons/js/markdownarea.min.js"></script>
 <script src="assets/js/codemirror/mode/markdown/markdown.js"></script>
 <script src="assets/js/codemirror/addon/mode/overlay.js"></script>
 <script src="assets/js/codemirror/mode/xml/xml.js"></script>
 <script src="assets/js/codemirror/mode/gfm/gfm.js"></script>
 <script src="assets/js/icheck/icheck.min.js"></script>	
