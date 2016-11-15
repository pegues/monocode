<?php 
if(isset($_POST['email_address'])){

			$id=SystemModule()->Save(
				array(
					'TableName'=>prefix.'email'
				),
				array(
					'email_address',
					'group_id',
					'fullname',
					'status'
					)
			);
			set_status('success','Saved.');
}
?>

<form action="" method="post" role="form" id="form1" class="form validate">
	<ol class="breadcrumb 2">
		<li><a href="<?php echo SystemModule()->getBasePath() ; ?>admin"><i class="entypo-home"></i>Home</a></li>
		<li class="active"><strong>Emails</strong></li>
	</ol>
	
	<h1>
		<span>Email Information</span>
		<a href="?page=email/email.php" class="btn btn-orange btn-sm">Back to Email List</a>
	</h1>
	
	<br/>
	
	<?php echo get_status();$sets='no'; ?>
	
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
							<label class="col-sm-3 control-label">Name</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" name="fullname" value="<?php echo (isset($_POST['fullname']) && $sets=='yes') ?$_POST['fullname']:''; ?>" id="fullname" data-message-required="Name is required." data-validate="required"/>
							</div>
						</div>
			
						<div class="form-group">
							<label class="col-sm-3 control-label">Email Address</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" name="email_address" value="<?php echo (isset($_POST['email_address']) && $sets=='yes') ?$_POST['email_address']:''; ?>" id="email_address" data-message-required="Email Address is required." data-validate="required"/>
							</div>
						</div>
                        <?php $groupid= $db->get_results("select * from ".prefix."email_group where email_group_id!='5'"); ?>
						
						<div class="form-group">
							<label class="col-sm-3 control-label">Email Group</label>
							<div class="col-sm-9">
								<select type="text" class="form-control" name="group_id" value="<?php echo (isset($_POST['group_id']) && $sets=='yes')?$_POST['group_id']:''; ?>" id="group_id" data-message-required="Email Group must be selected." data-validate="required">
                                <?php foreach($groupid as $val){ ?>
                                <option value="<?php echo $val->email_group_id; ?>"><?php echo $val->email_group_name; ?></option>
                                <?php } ?>
                                </select>
							</div>
						</div>						
						<div class="form-group">
							<label class="col-sm-3 control-label"></label>
							<div class="col-sm-9">
								<button class="btn btn-blue" type="submit" name="save">
									<?php if(isset($_GET['id']) && isset($_GET['action']) && $_GET['action']=='edit'){ ?>
										Update Email Details
									<?php }else{ ?>
										Save Email Details
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
				data:{'user_name':$('#user_name').val(),'group_id':'<?php echo (isset($_GET['id']))?$_GET['id']:''; ?>'},
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