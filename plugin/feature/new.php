<?php
if(isset($_POST['feature_name'])){
	$id=SystemModule()->Save(
		array(
			'TableName'=>prefix.'features'
		),
		array(
			'feature_name',
			'feature_description',
			'work_space',
			'allow_svn',
			'allow_ftp',
		)
	);
	if(isset($_POST['parents']) && is_array($_POST['parents'])){
		SystemModule()->Delete(array('TableName'=>prefix.'features_to_editor','DeleteId'=>'xxx','Query'=>' OR feature_id='.$id));
		foreach ($_POST['parents'] as $val){
			$_POST['editor_id']=$val;
			$_POST['feature_id']=$id;
			SystemModule()->Save(array('TableName'=>prefix.'features_to_editor','Action'=>'insert'),array('editor_id','feature_id'));
		}
	}else{
		SystemModule()->Delete(array('TableName'=>prefix.'features_to_editor','DeleteId'=>'xxx','Query'=>' OR feature_id='.$id));
	}
	set_status('success','Feature successfully saved.');
}
SystemModule()->SetupEdit(array('TableName'=>prefix.'features'));
?>
<form action="" class="form" method="post">
	<?php
		if(isset($_GET['id'])){
			$id= $_GET['id'];
		 }else{
			$id="";
		 }
		$arys=array();
		$set=$db->get_results("select* from " . prefix . "features_to_editor where feature_id='$id'");
		foreach($set as $sit){
			$arys[]=$sit->editor_id;
		}
	?>
	<ol class="breadcrumb 2">
		<li>
			<a href="<?php echo SystemModule()->getBasePath() ; ?>admin"><i class="entypo-home"></i>Home</a>
		</li>
		<li class="active">
			<strong>Package</strong>
		</li>
	</ol>
	
	<h1>
		<span>Package</span>
		<a href="?page=feature/feature.php" class="btn btn-orange btn-sm">Back to Packages List</a>
	</h1>
	
	<br/>
	
	<?php echo get_status(); ?>

	<div class="row">
		<div class="col-md-6">
			<div class="panel panel-primary" data-collapsed="0">
			
				<div class="panel-heading">
					<div class="panel-title">
						Package Information
					</div>
				</div>
				
				<div class="panel-body">
					<div class="form-horizontal form-groups-bordered">
                    
                    <div class="form-group">
							<label class="col-sm-3 control-label">Package Name</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" name="feature_name" value="" id="feature_name" />
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-3 control-label">Package Description</label>
							<div class="col-sm-9">
								<textarea class="form-control" name="feature_description" value="" id="feature_description"></textarea>
							</div>
						</div>
                        
                         <div class="form-group">
							<label class="col-sm-3 control-label">Workspace</label>
							<div class="col-sm-9">
								<select class="form-control" name="work_space" value="" id="work_space">
									<option value="0">0</option>
									<option value="1">1</option>
									<option value="2">2</option>
									<option value="3">3</option>
									<option value="4">4</option>
									<option value="5">5</option>
									<option value="6">6</option>
									<option value="7">7</option>
									<option value="8">8</option>
									<option value="9">9</option>
								</select>
							</div>
						</div>
                        
                         <div class="form-group">
							<label class="col-sm-3 control-label">Allow FTP</label>
							<div class="col-sm-9">
								<select class="form-control" name="allow_ftp" id="allow_ftp">
									<option value="yes">Yes</option>
									<option value="no">No</option>
                                </select>
							</div>
						</div>
                        
                          <div class="form-group">
							<label class="col-sm-3 control-label">Allow SVN</label>
							<div class="col-sm-9">
								<select class="form-control" name="allow_svn" id="allow_svn">
									<option value="yes">Yes</option>
									<option value="no">No</option>
                                </select>
							</div>
						</div>
                        

						<div class="form-group">
							<label class="col-sm-3 control-label"></label>
							<div class="col-sm-9">
								<button class="btn btn-blue" type="submit" name="save">Add/Update Package</button>
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
						Module Access Permissions
					</div>
				</div>
				
				<div class="panel-body">
					<div class="col-lg-12 nopadding" style="margin-top: 5px; margin-top: 5px; float: left; overflow-y: auto; overflow-x: hidden; height: 400px;">
						<ul class="tree">
							<?php
							$data=$db->get_results("select * from ".prefix."editor where parent='0'");
							$sel="";
							$ifnot=isset($_GET['id'])?$_GET['id']:'eeeee-xxx';
							foreach($data as $pages){
								$cur_post=isset($_POST['parents'])?$_POST['parents']:'';
								$sel=(is_array($arys) && in_array($pages->editor_id,$arys))?'checked="checked"':'';
							?>
							<li>
								<a class="user_class">
									<input type="checkbox" <?php echo $sel; ?> value="<?php echo $pages->editor_id; ?>" name="parents[]" id="parent-<?php echo $pages->editor_id; ?>" />
									<label for="parent-<?php echo $pages->editor_id; ?>"><?php echo $pages->name; ?></label>
								</a>
								<?php echo getsub($db,$pages->editor_id,$arys);?>
							</li>
							<?php } ?>
						</ul>
					</div>
				</div>
			</div>
		</div>
		
		<?php
		function getsub($db,$parent,$arys){
			$lost	= '<ul class="tree sub">';
			$data	= $db->get_results("select * from ".prefix."editor where parent='$parent'");
			$sel	= "";
			if(sizeof($data)>0){
				foreach($data as $pages){
					//if($pages->editor_id!=$ifnot){
					$cur_post=isset($_POST['parents'])?$_POST['parents']:'';
					$sel=(is_array($arys) && in_array($pages->editor_id,$arys))?'checked="checked"':'';
					$lost.='<li><a class="user_class"><input type="checkbox" '.$sel.' value="'.$pages->editor_id.'" name="parents[]" id="parent-'.$pages->editor_id.'"><label for="parent-'.$pages->editor_id.'">'.$pages->name.'</label></a>';
					$lost.=getsub($db,$pages->editor_id,$arys);
					$lost.='</li>';
					//}
				}
			}
			$lost.='</ul>';
			return $lost;
		}
		?>
		
        <div class="clearfix"></div>
    </div>
	
	<div class="clearfix"></div>
</form>

<div class="clearfix"></div>

<script type="text/javascript">
	$(document).ready(function(e) {
		$(".tree input[type='checkbox']").click(function(){
			if($(this).is(":checked")){
				$(this).closest("li").find("ul input[type='checkbox']").prop('checked',true);
				$(this).parents("ul").eq(0).parents("li").eq(0).find("a:first>input[type='checkbox']").prop('checked',true);
				$(this).parents("ul").eq(0).parents("li").eq(0).parents("ul").eq(0).parents("li").eq(0).find("a:first>input[type='checkbox']").prop('checked',true);
			}else{
				$(this).closest("li").find("ul input[type='checkbox']").prop('checked',false);
			}
		});
	});
</script>