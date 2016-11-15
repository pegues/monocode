<form action="" class="form" method="post">
	<ol class="breadcrumb 2">
		<li><a href="<?php echo SystemModule()->getBasePath() ; ?>admin"><i class="entypo-home"></i>Home</a></li>
		<li class="active"><strong>User Plan</strong></li>
	</ol>
	
	<h1>
		<span>User Plan</span>
		<a href="?page=user/user_type.php" class="btn btn-orange btn-sm">Back to Plans List</a>
	</h1>
	
	<br/>
	
	<?php
	$list=$db->get_results("
		select 
		CONCAT(u.first_name,' ', u.last_name) as fullname,
		ut.user_type_name,
		t.transaction_id,
		t.amount,
		t.user_id,
		t.user_type_id,
		t.order_id,
		DATE_FORMAT(t.start_date,'%d %b %Y') as start_date,
		DATE_FORMAT(t.start_date,'%d %b %Y') as end_date,
		t.status,
		t.discount,
		t.payment_status 
		FROM 
		" . prefix . "transaction t,
		" . prefix . "user u,
		" . prefix . "user_type ut 
		where  
		t.user_id=u.user_id AND 
		t.user_type_id=ut.user_type_id  AND t.transaction_id='".$_GET['id']."' 
		order by t.display_order ASC
	");
	
	$now 			= new DateTime();
	$future_date 	= new DateTime($list[0]->start_date);
	$interval 		= $future_date->diff($now);
	
	$d = $interval->format("%d");
	$m = $interval->format("%m");
	$y = $interval->format("%y");
	$h = $interval->format("%h");
	
	if($list[0]->payment_status=="S"){
		$payment_status="Paid";
	}else if($list[0]->payment_status=="C"){
		$payment_status="Cancled";
	}else if(($list[0]->payment_status=='P') && ($d=='0' && $m=='0' && $y=='0' && $h=='0')){
		$payment_status="Process";
	}else if(($list[0]->payment_status=='P') && ($d!='0' || $m!='0' || $y!='0' || $h!='0')){
		$payment_status="Unknown";
	} 
	
	$time_remaining='';
	
	if($payment_status=='Paid'){
		$now 			= new DateTime();
		$future_date 	= new DateTime($list[0]->end_date);
		$interval 		= $future_date->diff($now);
		
		if($future_date>$now){
			$m = $interval->format("%m");
			$d = $interval->format("%d");
			$h = $interval->format("%h");
			
			if($m!=0){
				$time_remaining.=$m." months, ";
			}
			if($d!=0){
				$time_remaining.=$d." days, ";
			}
			if($h!=0){
				$time_remaining.=$h." Hours";
			}
		}else{
			$time_remaining="Completed";
		}
	}
	//echo ($time_remaining!='')?$time_remaining:'----------';
	?>

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
							<label class="col-sm-3 control-label">User Name</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" value="<?php echo $list[0]->fullname; ?>" readonly />
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-3 control-label">User Plan</label>
							<div class="col-sm-9">
								<?php $type=$db->get_results("select * from ".prefix."user_type where user_type_id>1"); ?>
								<select class="form-control" name="user_type_id">
									<?php 
									foreach($type as $val){
										$sel='';
										if($list[0]->user_type_id==$val->user_type_id){
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
									<input type="text" name="start_date" class="form-control datepicker" data-start-date="-2d"  value="<?php echo $list[0]->start_date; ?>" data-format="dd M yyyy" />
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
									<input type="text" name="end_date" class="form-control datepicker" data-start-date="-2d" value="<?php echo $list[0]->end_date; ?>" data-format="dd M yyyy" />
									<div class="input-group-addon">
										<a href="#"><i class="entypo-calendar"></i></a>
									</div>
								</div>
							</div>
						</div>
						
						<div class="clearfix"></div>
						
						<div class="form-group">
							<?php $status=($time_remaining!='' && $time_remaining!='Completed' && $list[0]->status=='Y')?'Y':'N'; ?>
							<label class="col-sm-3 control-label">Status</label>
							<div class="col-sm-9">
								<select class="form-control" name="status">
									<option value="Y" <?php echo ($status=='Y')?'selected="selected"':''; ?>>Active</option>
									<option value="N" <?php echo ($status=='N')?'selected="selected"':''; ?>>Inactive</option>
								</select>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-3 control-label"></label>
							<div class="col-sm-9">
								<button class="btn btn-blue" type="submit" name="save">Update</button>
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
						Payment Information
					</div>
				</div>
				<div class="panel-body">
					<div class="form-horizontal form-groups-bordered">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Amount</label>
                            <div class="col-sm-9">
								<input type="text"  class="form-control" name="amount" value="<?php echo $list[0]->amount; ?>" id="amount" />
                            </div>
                        </div>
						
                        <div class="clearfix"></div>
						
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Discount</label>
                            <div class="col-sm-9">
								<input type="text"  class="form-control" name="discount" value="<?php echo $list[0]->discount; ?>" id="discount" />
                            </div>
                        </div>
                       
                        <div class="clearfix"></div>
						
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Payment Status</label>
                            <div class="col-sm-9">
								<select class="form-control" name="payment_status">
									<option <?php echo ($payment_status=='Paid')?'selected="selected"':''; ?> value="S">Success</option>
									<option <?php echo ($payment_status=='Cancled')?'selected="selected"':''; ?> value="C">Cancelled</option>
									<option <?php echo (($payment_status=='Process'))?'selected="selected"':''; ?> value="P">Process</option>
									<option <?php echo (($payment_status=='Unknown'))?'selected="selected"':''; ?> value="P">Unknown</option>
								</select>
                             
                            </div>
                        </div>
						
                        <div class="clearfix"></div>
						
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Note</label>
                            <div class="col-sm-9">
								<textarea class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
				</div>
			</div>
		
			<div class="clearfix"></div>
		</div>
	
		<div class="clearfix"></div>
	</div>
</form>

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
