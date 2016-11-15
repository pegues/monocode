<?php 
if(isset($_POST['save'])){
//print_r($_POST);
$_POST['start_date']=date_format(date_create($_POST['start_date']),'Y-m-d H:i:s');
$_POST['end_date']=date_format(date_create($_POST['end_date']),'Y-m-d H:i:s');

			$id=SystemModule()->Save(
				array(
					'TableName'=>prefix.'user_subscription'
				),
				array(					
					'start_date',
					'user_type_id',
					'end_date',
					'amount'
					)
			);
			
			set_status('success','Saved.');
			
			echo get_status();
			
}

?>

<form action="" method="post" role="form" id="form1" class="form validate">
	<ol class="breadcrumb 2">
		<li><a href="<?php echo SystemModule()->getBasePath() ; ?>admin"><i class="entypo-home"></i>Home</a></li>
		<li class="active"><strong>User Subscription</strong></li>
	</ol>
	
	<h1>
		<span>User Subscription</span>
		<a href="?page=user/user_subscribers.php" class="btn btn-orange btn-sm">Back to Subscriptions List</a>
	</h1>
	
	<br/>
	
	
	
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-primary" data-collapsed="0">
			
				<div class="panel-heading">
					<div class="panel-title">
						User Subscription Information
					</div>
				</div>
				
				<div class="panel-body">
					<div class="form-horizontal form-groups-bordered">
                    <?php
					$data=$db->get_results("select u.first_name, 
											u.last_name,
											t.user_type_name,
											s.user_type_id,
											DATE_FORMAT(s.start_date,'%d %b %Y') as start_date, 
											DATE_FORMAT(s.end_date,'%d %b %Y') as end_date,  
											s.amount,
											s.subscription_id, 
											CONCAT(u.first_name,' ', u.last_name) as fullname 
											from " . prefix . "user u,
											" . prefix . "user_type t,
											".prefix."user_subscription s  
											where 
											u.user_type=t.user_type_id AND 
											u.user_id=s.user_id");
											//print_r($data);
					?>
                    <div class="form-group">
							<label class="col-sm-3 control-label">User Name</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" readonly name="username" value="<?php echo $data[0]->fullname; ?>" id="fullname"/>
							</div>
						</div>
			
						<div class="form-group">
							<label class="col-sm-3 control-label">User Plan</label>
							<div class="col-sm-9">
                            <?php
							$type=$db->get_results("select * from ".prefix."user_type where user_type_id>1");
							?>
								<select class="form-control" name="user_type_id">
                                <?php 
								foreach($type as $val){
									$sel='';
									if($data[0]->user_type_id==$val->user_type_id){
										$sel="selected='selected'";
									}
									?>
                                <option <?php echo $sel; ?> value="<?php echo $val->user_type_id; ?>"><?php echo $val->user_type_name; ?> 
								<?php } ?>
                                </select>
							</div>
						</div>
                        
						<div class="form-group">
							<label class="col-sm-3 control-label">Start Date</label>
							<div class="col-sm-9">
								
                                <div class="input-group">
        <input type="text" name="start_date" class="form-control datepicker" data-start-date="-2d"  value="<?php echo $data[0]->start_date; ?>" data-format="dd M yyyy">
        
        <div class="input-group-addon">
         <a href="#"><i class="entypo-calendar"></i></a>
        </div>
       </div>
                                
                                
                                                   
							</div>
						</div>	
                        
                        <div class="form-group">
							<label class="col-sm-3 control-label">End Date</label>
							<div class="col-sm-9">
								<div class="input-group">
        <input type="text" name="end_date" class="form-control datepicker" data-start-date="-2d" value="<?php echo $data[0]->end_date; ?>" data-format="dd M yyyy">
        
        <div class="input-group-addon">
         <a href="#"><i class="entypo-calendar"></i></a>
        </div>
       </div>
						</div>
                        </div>		
                        
                        <div class="form-group">
							<label class="col-sm-3 control-label">Amount</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" name="amount" value="<?php echo $data[0]->amount; ?>"/>
                                
							</div>
						</div>							
						<div class="form-group">
							<label class="col-sm-3 control-label"></label>
							<div class="col-sm-9">
								<button class="btn btn-blue" type="submit" name="save">Save Changes
									
                                </button>
							</div>
						</div>
						
					</div>
				</div>
				
				
			</div>
		</div>
    </div>
</form>

<div class="clearfix"></div>
<link rel="stylesheet" href="assets/js/select2/select2-bootstrap.css">
 <link rel="stylesheet" href="assets/js/select2/select2.css">
 <link rel="stylesheet" href="assets/js/selectboxit/jquery.selectBoxIt.css">
 <link rel="stylesheet" href="assets/js/daterangepicker/daterangepicker-bs3.css">
 <link rel="stylesheet" href="assets/js/icheck/skins/minimal/_all.css">
 <link rel="stylesheet" href="assets/js/icheck/skins/square/_all.css">
 <link rel="stylesheet" href="assets/js/icheck/skins/flat/_all.css">
 <link rel="stylesheet" href="assets/js/icheck/skins/futurico/futurico.css">
 <link rel="stylesheet" href="assets/js/icheck/skins/polaris/polaris.css">
 <script src="http://demo.neontheme.com/assets/js/bootstrap-datepicker.js" id="script-resource-12"></script>