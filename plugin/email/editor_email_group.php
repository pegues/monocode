<?php 
if(isset($_POST['email_group_name'])){

			$id=SystemModule()->Save(
				array(
					'TableName'=>prefix.'email_group'
				),
				array(
					'email_group_name',
					)
			);
			set_status('success','Saved.');
}
SystemModule()->SetupEdit(array('TableName'=>prefix.'email_group'));
?>

	
	<ol class="breadcrumb 2">
		<li>
			<a href="<?php echo SystemModule()->getBasePath() ; ?>admin"><i class="entypo-home"></i>Home</a>
		</li>
		<li class="active">
			<strong>Email Group</strong>
		</li>
	</ol>
	
	<h1>
		<span>Email Group</span>
		<a href="?page=email/email_group.php" class="btn btn-orange btn-sm">Back to Email Group List</a>
	</h1>
	
	<br/>
	
	<?php echo get_status(); ?>

	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary" data-collapsed="0">
			
				<div class="panel-heading">
					<div class="panel-title">
						Email Group Information
					</div>
				</div>
				
				<div class="panel-body">
					<div class="form-horizontal form-groups-bordered">
						<form action="" method="post">
						<div class="form-group">
							<label class="col-sm-3 control-label">Group Name</label>
							<div class="col-sm-9"><input type="text" class="form-control" name="email_group_name" id="email_group_name" value="" />
							</div>
						</div>
                                                
                       	<div class="form-group">
							<label class="col-sm-3 control-label"></label>
							<div class="col-sm-9">
								<button class="btn btn-blue" type="submit" name="save">Add/Update Email Group</button>
							</div>
						</div>
						</form>
					</div>
			</div>
            </div>
            </div>
            </div>
			
		

		