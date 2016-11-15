<ol class="breadcrumb 2">
    <li><a href="<?php echo SystemModule()->getBasePath(); ?>admin"><i class="entypo-home"></i>Home</a></li>
    <li class="active"><strong>Transaction Details</strong></li>
</ol>

<h1><span>Transaction Details</span></h1>

<br />

<?php echo get_status(); ?>
<form action="" method="post">
<?php
	$list=$db->get_results("select  
	CONCAT(u.first_name,' ', u.last_name) as fullname,
	ut.user_type_name,
	t.transaction_id,
	t.amount,
	t.user_id,
	t.user_type_id,
	t.order_id,
	t.start_date,
	t.end_date,
	t.status,
	t.discount,
	t.payment_status 
	FROM 
	" . prefix . "transaction t,
	" . prefix . "user u,
	" . prefix . "user_type ut 
	where  
	t.user_id=u.user_id AND 
	t.user_type_id=ut.user_type_id  
	order by t.display_order ASC");
	?>
	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="table table-bordered table-striped datatable dataTable">
		<colgroup>
			<col />
			<col />
			<col />
			<col />
			<col />
			<col />
			<col />
			<col />
			<col />
		</colgroup>
		<thead>
			<tr>
				<th align="left" class="sorting_asc">Full Name</th>
				<th align="left">Package Name</th>
				<th align="left">Gateway</th>
				<th align="left">Transaction ID</th>
				<th align="left">Time Remaining</th>
				<th align="left" class="status">Status</th>
				<th align="left">Payment Status</th>
				<th align="left">Amount</th>
				<th align="left">Promo Amount</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$total	= 0;
			$active	= 0;
			
			foreach($list as $detail){
				$now 			= new DateTime();
				$future_date 	= new DateTime($detail->start_date);
				$interval 		= $future_date->diff($now);
				
				$d = $interval->format("%d");
				$m = $interval->format("%m");
				$y = $interval->format("%y");
				$h = $interval->format("%h");
				
				if($detail->payment_status=="S"){
					$payment_status="Paid";
					$total=$total+$detail->amount;
				}else if($detail->payment_status=="C"){
					$payment_status="Cancled";
				}else if(($detail->payment_status=='P') && ($d=='0' && $m=='0' && $y=='0' && $h=='0')){
					$payment_status="Process";
				}else if(($detail->payment_status=='P') && ($d!='0' || $m!='0' || $y!='0' || $h!='0')){
					$payment_status="Unknown";
				}
			?>
			<tr>
				<td align="left">
					<a href="?page=user/new.php&action=edit&id=<?php echo $detail->user_id; ?>"><?php echo $detail->fullname; ?></a>
				</td>
				<td align="left">
					<a href="?page=user/editor_user_type.php&action=edit&id=<?php echo $detail->user_type_id; ?>"><?php echo $detail->user_type_name; ?></a>
				</td>
				<td>
					Payment Gateway Name Here
				</td>
				<td align="left">
					<a href="?page=transaction/details.php&action=edit&id=<?php echo $detail->transaction_id; ?>"><?php echo $detail->order_id; ?></a>
				</td>
				<td align="left">
					<?php
					$time_remaining='';
					
					if($payment_status=='Paid'){
						$now 			= new DateTime();
						$future_date 	= new DateTime($detail->end_date);
						$interval 		= $future_date->diff($now);
						
						if($future_date>$now){
							$m=$interval->format("%m");
							$d=$interval->format("%d");
							$h=$interval->format("%h");
						if($m!=0){
							$time_remaining.=$m." months, ";
						}
						if($d!=0){
							$time_remaining.=$d." days, ";
						}
						if($h!=0){
							$time_remaining.=$h." Hours";
						}
						
						$active++;
						
						}else{
							$time_remaining="Completed";
						}
					}
					echo ($time_remaining!='')?$time_remaining:'----------';
					?>
				</td>
				<td align="left">
					<input class="status" name="status" type="submit" value="transaction|transaction_id|<?php echo $detail->transaction_id; ?>|status" style="background-image:url(images/<?php echo $status=($time_remaining!='' && $time_remaining!='Completed' && $detail->status=='Y')?'active.gif':'inactive.gif'; ?>);" />
				</td>
				<td align="left">
					<?php echo $payment_status; ?>
				</td>
				<td align="left">
					<span data-original-title="Dicounts" data-content="Discounts will be applied to yearly plans only." data-placement="left" data-trigger="hover" data-toggle="popover" style="cursor: pointer;">
						<?php echo get_settings('currency').' ' .number_format($detail->amount,2); ?>
					</span>
				</td>
				<td align="left">
					<?php echo $detail->discount; ?> %Off
				</td>
			</tr>
			<?php
			}
			?>
		</tbody>
	</table>
	
	<div style="float: right; margin-right: 0; margin-top: -6px; margin-bottom: 0; text-align:right;">
		Total Transactions: <?php echo get_settings('currency').' ' .number_format($total,2); ?>
		<br />
        # Active Users: <?php echo $active; ?>
	</div>

	<div class="clearfix"></div>
</form>