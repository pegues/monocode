<script type="text/javascript">
function CallBack(key,value){
	if(key=='parent'){
		if(value!=''){
			$("#parent-"+value).prop('checked',true);
			//alert("test " + value);
		}
	}
	if(key=='status'){
		if(value=='Y'){
			$("#status").prop('checked',true);
			//alert("test " + value);
		}else{
			$("#status").prop('checked',false);
		}
	}
}
</script>

<?php
$state='no';

function create_slug( $string,$db ) {
	$result = preg_replace("/[^a-zA-Z0-9]+/", "-", $string);
	$query='';
	if(isset($_GET['id'])){
		$query=" AND cms_id!='".$_GET['id']."'";
	}
	$res=$db->get_results("select* from ".prefix."cms where cms_slug='$result'".$query);
	if(sizeof($res)==0){
	return strtolower($result);
	}else{
		return strtolower($result).'-'.sizeof($res);
	}
}

function page_already($result,$db) {
	$query='';
	if(isset($_GET['id'])){
		$query=" AND cms_id!='".$_GET['id']."'";
	}
	$res=$db->get_results("select* from ".prefix."cms where cms_title='$result'".$query);
	if(sizeof($res)==0){
		return 'ok';
	}else{
		return 'no-ok';
	}
}

$id=isset($_GET['id'])?$_GET['id']:'fasdfads';

if(isset($_POST['cms_title'])){
	if(isset($_POST['status_page'])){
		$_POST['status']='Y';
	}else{
		$_POST['status']='N';
	}
	$_POST['cms_parent']=$_POST['cms_parent'];
	$_POST['cms_slug']=create_slug($_POST['cms_title'],$db);
	
	if(page_already($_POST['cms_title'],$db)=='ok'){
		$id=SystemModule()->Save(
			array(
				'TableName'=>prefix.'cms'
			),
			array(
				'cms_title',
				'cms_content',
				'cms_parent',
				'cms_slug',
				'templates',
				'status',
				'page_position_id'
			)
		);
		set_status('success','CMS page successfully saved.');
	}else{
		$state='yes';
		set_status('notice','There is already a page title.');
	}
}
SystemModule()->SetupEdit(array('TableName'=>prefix.'cms'));
?>

<form method="post" action="" role="form" id="form1" class="form validate">
	<ol class="breadcrumb 2">
		<li><a href="<?php echo SystemModule()->getBasePath() ; ?>admin"><i class="entypo-home"></i>Home</a></li>
		<li class="active"><strong>New Content Page</strong></li>
	</ol>

	<h1>
		<span>Content Page</span> 
		<a class="btn btn-orange btn-sm" href="?page=page/page.php">Back to Pages List</a>
	</h1>
	
	<br/>
  
	<?php echo get_status(); ?>
	
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-primary" data-collapsed="0">
				<div class="panel-body">
					<div class="form-horizontal form-groups-bordered">
						
						<div class="form-group">
							<label class="col-sm-3 control-label">Page Title</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" name="cms_title" id="cms_title" data-message-required="Title is Required." data-validate="required" value="<?php echo (isset($_POST['cms_title'])&& ($state=='yes'))?$_POST['cms_title']:''; ?>" />
							</div>
						</div>
							
						<div class="form-group">
							<label class="col-sm-3 control-label">Parent Page</label>
							<div class="col-sm-9">
								<select class="form-control" name="cms_parent" id="cms_parent">
									<option value="0">No Parent</option>
									<?php
										echo SystemModule()->SetComboBox(array(
											'TableName'	=> prefix.'cms',
											'Text'		=> 'cms_title',
											'Ignore'	=> $id
										))
									?>
								</select>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-3 control-label">Select Template</label>
							<div class="col-sm-9">
                            <?php 
							$temps=get_template();
							?>
								<select class="form-control" name="templates" id="templates">
									<option value="post.php">Default</option>
									<?php
									
									if(is_array($temps) && sizeof($temps)>0){
										foreach($temps as $tels){
										?>
											<option value="<?php echo $tels[1]; ?>"><?php echo $tels[0]; ?></option>
                                        <?php
										}
									}
									?>
								</select>
							</div>
						</div>
                        <?php
						$page_position=$db->get_results("select * from ".prefix."page_position");
						?>
                        <div class="form-group">
							<label class="col-sm-3 control-label">Page Position</label>
							<div class="col-sm-9">
								<select class="form-control" name="page_position_id" id="page_position_id">
                                <?php
								foreach($page_position as $val){ 
								?>
                                <option value="<?php echo $val->page_position_id; ?>"><?php echo $val->page_position_name; ?></option>
                                <?php 
								} 
								?>
                                </select>
							</div>
						</div>
                        
                        <div class="form-group">

							<label class="col-sm-3 control-label">Page Published</label>
							<div class="col-sm-9">
								<div id="label-switch" class="make-switch" data-on-label="Yes" data-off-label="No">
									<input type="checkbox" name="status_page" checked="checked" id="status" />
								</div>
							</div>
						</div>
                        
						<div class="form-group">
							<label class="col-sm-3 control-label">Page Contents</label>
							<div class="col-sm-9">
								<textarea class="form-control ckeditor" id="ckeditor" name="cms_content"><?php echo (isset($_POST['cms_content'])&& ($state=='yes'))?$_POST['cms_content']:''; ?></textarea>
                                
							</div>
						</div>
							
						<div class="form-group">
							<label class="col-sm-3 control-label"></label>
							<div class="col-sm-9">
								<button type="submit" name="publish" class="btn btn-green btn-lg">
									<span>Save Changes</span>
								</button>
                                
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>

<?php
    function getsub($db,$parent,$ifnot){
        $lost='<ul class="tree sub">';
		$data=$db->get_results("select * from ".prefix."editor where parent='$parent'");
		$sel="";
		if(sizeof($data)>0){
			foreach($data as $pages){
				if($pages->editor_id!=$ifnot){
					$cur_post=isset($_POST['parents'])?$_POST['parents']:'';
					$sel=($cur_post==$pages->editor_id)?'checked="checked"':'';
					$lost.='<li><a class="user_class"><input type="radio" '.$sel.' value="'.$pages->editor_id.'" name="parents" id="parent-'.$pages->editor_id.'"><label for="parent-'.$pages->editor_id.'">'.$pages->name.'</label></a>';
					$lost.=getsub($db,$pages->editor_id,$ifnot);
					$lost.='</li>';
				}
			}
		}
        $lost.='</ul>';
        return $lost;
    }
?>
<style type="text/css">
span.validate-has-error{
	color: #f00 !important;
}
</style>
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
<script src="assets/js/gsap/main-gsap.js"></script>

<script src="assets/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js"></script>
<script src="assets/js/bootstrap.js"></script>
<script src="assets/js/joinable.js"></script>
<script src="assets/js/resizeable.js"></script>
<script src="assets/js/neon-api.js"></script>
<script src="assets/js/bootstrap-switch.min.js"></script>