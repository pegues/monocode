<script type="text/javascript">
function CallBack(key,value){
	if(key=='parent'){
		if(value!=''){
			$("#parent-"+value).prop('checked',true);
			//alert("test " + value);
		}
	}
	if(key=='nav_sep'){
		if(value=='Y'){
			$("#nav_sep").prop('checked',true);
			//alert("test " + value);
		}
	}
}
</script>
<?php

if(isset($_POST['save'])){
	$_POST['parent']	= $_POST['parents'];
	$sep				= isset($_POST['nav_sep'])?'Y':'N';
	$_POST['nav_sep']	= $sep;
	$id					= SystemModule()->Save(
		array(
			'TableName' => prefix.'editor'
		),
		array(
			'name',
			'data_action',
			'action_type',
			'shortcut_key',
			'nav_sep',
			'parent',
			'module_desc',
		)
	);
	set_status('success','Editor module successfully saved.');
}
SystemModule()->SetupEdit();
?>

<h2>
	<span>Editor Module</span> 
	<a class="btn btn-orange btn-sm" href="?page=editor/editor.php">Back to Modules List</a>
</h2>

<br/>

<?php echo get_status(); ?>

<form method="post" action="" role="form" class="form-horizontal form-groups-bordered">
	<div class="row">
		<div class="col-md-6">
			<div class="panel panel-primary" data-collapsed="0">
			
				<div class="panel-heading">
					<div class="panel-title">
						Editor Module Information
					</div>
				</div>
				
				<div class="panel-body">
					<div class="form-horizontal form-groups-bordered">
						<div class="form-group">
							<label class="col-sm-3 control-label">Module Name</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" name="name" id="name" placeholder="Module Name" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Action Type</label>
							<div class="col-sm-9">
                            <select name="action_type" class="form-control" id="action_type">
                            	<option value="popup">Load Popup</option>
                                <option value="opentab">Open in Tab</option>
                                <option value="callphp">Call PHP function</option>
                                <option value="calljs">Call JS</option>
                            </select>
								
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Module Action</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" name="data_action" id="data_action" placeholder="Module Action" />
							</div>
						</div>
						
						
						
						<div class="form-group">
							<label class="col-sm-3 control-label">Shortcut Key</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" autocomplete="off" name="shortcut_key" id="shortcut_key" placeholder="Add Shortcut Key" />
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-3 control-label">Use Nav Seprator</label>
							<div class="col-sm-9">
								<div id="label-switch" class="make-switch" data-on-label="Yes" data-off-label="No">
									<input type="checkbox" name="nav_sep" id="nav_sep" />
								</div>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-3 control-label">Module Description</label>
							<div class="col-sm-9">
								<textarea class="form-control" name="module_desc" placeholder="Enter editor module description"></textarea>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-3 control-label"></label>
							<div class="col-sm-9">
								<button class="btn btn-blue" name="save" type="submit">Save Module</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="col-md-6">
			<div class="panel panel-primary" data-collapsed="0">
			
				<div class="panel-heading">
					<div class="panel-title">
						Editor Sublevel Selection
					</div>
				</div>
				
				<div class="panel-body">
					<div class="col-lg-12 nopadding" style="margin-top: 5px; margin-top: 5px; float: left; overflow-y: auto; overflow-x: hidden; height: 400px;">
						<ul class="tree">
							<li>
								<a class="user_class">
									<input type="radio" checked="checked" value="0" name="parents" id="parent-0">
									<label for="parent-0">Parent Level</label>
								</a>
							</li>
							<?php
								$data=$db->get_results("select * from ".prefix."editor where parent='0'");
								$sel="";
								$ifnot=isset($_GET['id'])?$_GET['id']:'eeeee-xxx';
								foreach($data as $pages){
									if($pages->editor_id!=$ifnot){
										$cur_post=isset($_POST['parents'])?$_POST['parents']:'';
										$sel=($cur_post==$pages->editor_id)?'checked="checked"':'';
							?>
							<li>
								<a class="user_class">
									<input type="radio" <?php echo $sel; ?> value="<?php echo $pages->editor_id; ?>" name="parents" id="parent-<?php echo $pages->editor_id; ?>" />
									<label for="parent-<?php echo $pages->editor_id; ?>"><?php echo $pages->name; ?></label>
								</a>
								<?php echo getsub($db,$pages->editor_id,$ifnot);?>
							</li>
							<?php
									}
								}
							?>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>

<!-- Bottom Scripts -->
<script src="assets/js/gsap/main-gsap.js"></script>
<script src="assets/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js"></script>
<script src="assets/js/bootstrap.js"></script>
<script src="assets/js/joinable.js"></script>
<script src="assets/js/resizeable.js"></script>
<script src="assets/js/neon-api.js"></script>
<script src="assets/js/bootstrap-switch.min.js"></script>

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