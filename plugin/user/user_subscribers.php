<?php
if(isset($_GET['id'])){		
    SystemModule()->Delete(
		array(
			'TableName'	=> prefix.'user_subscription',
			'DeleteId'	=> $_GET['id'],
			'Query'		=> '',
		)
    );
	set_status('success','Successfully deleted.');
	}
?>

<ol class="breadcrumb 2">
    <li><a href="<?php echo SystemModule()->getBasePath(); ?>admin"><i class="entypo-home"></i>Home</a></li>
    <li class="active"><strong>User Subscriptions</strong></li>
</ol>

<h1>
    <span>User Subscriptions</span> 
   <!-- <a href="?page=user/editor_user_subscriber.php" class="btn btn-orange btn-sm" type="button">Add New Subscription</a> -->
</h1>

<br />

<?php echo get_status(); ?>
<form action="" method="post">
<?php

	$list=$db->get_results("select 
	u.first_name, 
	u.last_name,
	t.user_type_name,
	DATE_FORMAT(s.start_date,'%d %b %Y %H:%i') as start_date, 
	s.end_date, 
	s.amount,
	s.status,
	s.subscription_id, 
	CONCAT(u.first_name,' ', u.last_name) as fullname 
	from " . prefix . "user u,
	" . prefix . "user_type t,
	".prefix."user_subscription s  
	where 
	s.user_type_id=t.user_type_id AND 
	u.user_id=s.user_id AND 
	s.status='Y'");
	$data=array('fields'=>
		array(
			
			'User Name' => array(
				'data_field'	=> 'fullname',
				'css'			=> ''
			),
			'User Plans' => array(
				'data_field'	=> 'user_type_name',
				'css'			=> ''
			),
			'Start Date' => array(
				'data_field'	=> 'start_date',
				'css'			=> ''
			),
			'End Date' => array(
				'data_field' 	=> 'end_date',
				'css' 			=> ''
			),
			'Amount' => array(
				'data_field' 	=> 'amount',
				'css' 			=> ''
			),
			'Status' => array(
				'data_field'	=> 'status',
				'css'			=> 'status'
			),
			
			'Action' => array(
				'data_field'	=> 'action',
				'css'			=> 'action'
			)
		),
		'object'				=> $list,
		'table_name'			=> 'user_subscription',
		'edit_file_name'        =>'editor_user_subscriber.php',
		'unique_key_field'		=> 'subscription_id'
	);
	echo GridView($data)->render();
	?>
</form>