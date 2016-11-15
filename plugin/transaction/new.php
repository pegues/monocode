<?php 
if(isset($_POST['first_name'])){
	if(file_exists(get_option('editor_work_space_path').$_POST['user_name'].'/')){
	}else{
		$oldumask = umask(0);
		mkdir(get_option('editor_work_space_path').$_POST['user_name'].'/', '0777', true);
		umask($oldumask);
	}
	
	$sq='';
	
	if(isset($_GET['id'])){
		$sq= " AND user_id!='".$_GET['id']."'";
	}
	
	$sets='no';
	$_POST['status']='Y';
	$res=$db->get_results("select* from ".prefix."user where (email='".$_POST['email']."' OR user_name='".$_POST['user_name']."')".$sq);
	
	if(sizeof($res)==0){
		$_POST['registered_date']=date("Y-m-d H:i:s");
		if($_POST['password']==''){
			$_POST['password']=$_POST['oldpwd'];
			
		}else{
			$_POST['password']=md5($_POST['password']);
		}
			$id=SystemModule()->Save(
				array(
					'TableName'=>prefix.'user'
				),
				array(
					'first_name',
					'last_name',
					'email',
					'address',
					'password',
					'phone',
					'user_name',
					'status',
					'user_type',
					'registered_date'
				)
			);
			set_status('success','Saved.');
	}else{
		set_status('success','The username or email address is already in use.');
		$sets='yes';
	}
}
?>

<form action="" method="post" role="form" id="form1" class="form validate">
	<ol class="breadcrumb 2">
		<li><a href="<?php echo SystemModule()->getBasePath() ; ?>admin"><i class="entypo-home"></i>Home</a></li>
		<li class="active"><strong>User</strong></li>
	</ol>
	
	<h1>
		<span>User Information</span>
		<a href="?page=user/user.php" class="btn btn-orange btn-sm">Back to Users List</a>
	</h1>
	
	<br/>
	
	<?php echo get_status(); ?>
	
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-primary" data-collapsed="0">
			
				<div class="panel-heading">
					<div class="panel-title">
						Details
					</div>
				</div>
				
				<div class="panel-body">
					<div class="form-horizontal form-groups-bordered">
			
						<div class="form-group">
							<label class="col-sm-3 control-label">First Name</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" name="first_name" value="<?php echo (isset($_POST['first_name']) && $sets=='yes') ?$_POST['first_name']:''; ?>" id="first_name" data-message-required="First name is required." data-validate="required"  />
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-3 control-label">Last Name</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" name="last_name" value="<?php echo (isset($_POST['last_name']) && $sets=='yes')?$_POST['last_name']:''; ?>" id="last_name" data-message-required="Last name is required." data-validate="required" />
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-3 control-label">Email Address</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" name="email" value="<?php echo (isset($_POST['email']) && $sets=='yes')?$_POST['email']:''; ?>" id="email"  data-message-required="Email address is required." data-validate="required"  />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Username</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" name="user_name" value="<?php echo (isset($_POST['user_name']) && $sets=='yes')?$_POST['user_name']:''; ?>" id="user_name"  data-message-required="Username is required." data-validate="required" /><span class="status-icon"></span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">Password</label>
							<div class="col-sm-9">
<input <?php echo (!isset($_GET['id']))?'data-message-required="Password is required." data-validate="required"':''; ?> type="password" class="form-control" autocomplete="off" name="password" id="password" /><input type="hidden" id="oldpwd"  style="width:0px; height:0px;" name="oldpwd" />
							</div>
						</div>
						
								
						<div class="form-group">
							<label class="col-sm-3 control-label">User Plan</label>
							<div class="col-sm-9">
								<select name="user_type" class="form-control" id="user_type"   data-message-required="User plan is required." data-validate="required">
									<option value="">Select User Plan</option>
									<?php
									echo SystemModule()->SetComboBox(array(
										'TableName'	=> prefix.'user_type',
										'Text'		=> 'user_type_name',
										'Query'		=> ''
									))
									?>
								</select>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-3 control-label">Phone Number</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" name="phone" value="<?php echo (isset($_POST['phone']) && $sets=='yes')?$_POST['phone']:''; ?>" id="phone" />
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-3 control-label">Address</label>
							<div class="col-sm-9">
								<textarea class="form-control" name="address"><?php echo (isset($_POST['address']) && $sets=='yes')?$_POST['address']:''; ?></textarea>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-3 control-label"></label>
							<div class="col-sm-9">
								<button class="btn btn-blue" type="submit" name="save">
									<?php if(isset($_GET['id']) && isset($_GET['action']) && $_GET['action']=='edit'){ ?>
										Update User Details
									<?php }else{ ?>
										Save User Details
									<?php } ?>
                                </button>
							</div>
						</div>
						
					</div>
				</div>
				
				<?php  
					if(isset($_GET['id']) &&   isset($_GET['action']) && $_GET['action']=='edit'){
						SystemModule()->SetupEdit();
					}
				?>
			</div>
		</div>
    </div>
</form>

<div class="clearfix"></div>
<script type="text/javascript">
window.onload = function() {
 var myInput = document.getElementById('password');
 myInput.onpaste = function(e) {
   e.preventDefault();
 }
}
function CallBack(key,value){
	if(key=='password'){
		$("input[name='password']").val('');
		$("#oldpwd").val(value);
	}
	if(key=='user_name' && value!=''){
		$("#user_name").prop('readonly',true);
		
	}
}
$(document).ready(function(e) {
    $("#user_name").change(function(){
		 	$('.status-icon').html(' <img src="<?php echo SystemModule()->getBasePath().'admin/assets/images/small_loader.gif';?>"> Please wait...');
            $.ajax({
                url: '<?php echo SystemModule()->getBasePath(); ?>admin/ajax/checkUser/',
                type: 'POST',
				data:{'user_name':$('#user_name').val(),'user_id':'<?php echo (isset($_GET['id']))?$_GET['id']:''; ?>'},
                dataType: 'json',
                success: function(result) {
                    $('.status-icon').text(result[0].msg);
					 $('.status-icon').css({'color':result[0].color})
                }
            });
            return false;
	})
});

</script>
<style type="text/css">
span.validate-has-error {
	color: #f00 !important;
	}
</style>