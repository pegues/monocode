<?php
if(isset($_POST['user_type_name'])){
	$id=SystemModule()->Save(
		array(
			'TableName'=>prefix.'user_type'
		),
		array(
			'user_type_name',
			'user_type_description',
			'amount',
			'discount'
		)
	);
	if(isset($_POST['parents']) && is_array($_POST['parents'])){
		SystemModule()->Delete(array('TableName'=>prefix.'user_type_to_features','DeleteId'=>'xxx','Query'=>' OR user_type_id='.$id));
		foreach ($_POST['parents'] as $val){
			$_POST['feature_id']=$val;
			$_POST['user_type_id']=$id;
			SystemModule()->Save(array('TableName'=>prefix.'user_type_to_features','Action'=>'insert'),array('feature_id','user_type_id'));
		}
	}else{
	SystemModule()->Delete(array('TableName'=>prefix.'user_type_to_features','DeleteId'=>'xxx','Query'=>' OR user_type_id='.$id));	
	}
	set_status('success','User Type successfully saved.');
}
SystemModule()->SetupEdit(array('TableName'=>prefix.'user_type'));
?>
<form action="" class="form" method="post">
	<?php
		if(isset($_GET['id'])){
			$id= $_GET['id'];
		 }else{
			$id="";
		 }
		$arys=array();
		$set=$db->get_results("select* from " . prefix . "user_type_to_features where user_type_id='$id'");
		foreach($set as $sit){
			$arys[]=$sit->feature_id;
		}
	?>
	<ol class="breadcrumb 2">
		<li>
			<a href="<?php echo SystemModule()->getBasePath() ; ?>admin"><i class="entypo-home"></i>Home</a>
		</li>
		<li class="active">
			<strong>User Plan</strong>
		</li>
	</ol>
	
	<h1>
		<span>User Plan</span>
		<a href="?page=user/user_type.php" class="btn btn-orange btn-sm">Back to Plans List</a>
	</h1>
	
	<br/>
	
	<?php echo get_status(); ?>

	<div class="row">
		<div class="col-md-6">
			<div class="panel panel-primary" data-collapsed="0">
			
				<div class="panel-heading">
					<div class="panel-title">
						User Plan Information
					</div>
				</div>
				
				<div class="panel-body">
					<div class="form-horizontal form-groups-bordered">
						
						<div class="form-group">
							<label class="col-sm-3 control-label">User Plan</label>
							<div class="col-sm-9">
                            <?php
							$status_='';
							if(isset($_GET['id']) && ($_GET['id']=='1')){
								$status_=' readonly="readonly" ';
							}
							?>
								<input type="text" <?php echo $status_; ?> class="form-control" name="user_type_name" value="" id="user_type_name" />
							</div>
						</div>                      
                        
                        
                        <div class="form-group">
							<label class="col-sm-3 control-label">Amount</label>
							<div class="col-sm-9">
                          
								<input type="text"  class="form-control" name="amount" value="" id="amount" />
							</div>
						</div>
                         <div class="form-group">
							<label class="col-sm-3 control-label">Yearly Discount(%)</label>
							<div class="col-sm-9">
                          
								<input type="text"  class="form-control" name="discount" value="" id="discount" />
							</div>
						</div>
                        
                         <div class="form-group">
							<label class="col-sm-3 control-label">Description</label>
							<div class="col-sm-9"><textarea class="form-control" name="user_type_description"></textarea>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label"></label>
							<div class="col-sm-9">
								<button class="btn btn-blue" type="submit" name="save">Add/Update User Plan</button>
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
						Package Information
					</div>
				</div>
				
				<div class="panel-body">
					<div class="col-lg-12 nopadding" style="margin-top: 5px; margin-top: 5px; float: left; overflow-y: auto; overflow-x: hidden; height: 400px;">
						<ul class="tree">
							<?php
							$data=$db->get_results("select * from ".prefix."features");
							$sel="";

							foreach($data as $pages){
									$sel=(is_array($arys) && in_array($pages->feature_id,$arys))?'checked="checked"':'';
							?>
							<li>
								<a class="user_class">
									<input type="checkbox" <?php echo $sel; ?> value="<?php echo $pages->feature_id; ?>" name="parents[]" id="parent-<?php echo $pages->feature_id; ?>" />
									<label for="parent-<?php echo $pages->feature_id; ?>"><?php echo $pages->feature_name; ?></label>
								</a>
								
							</li>
							<?php
							}
							?>
						</ul>
					</div>
				</div>
			</div>
		</div>
		
		
		
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

