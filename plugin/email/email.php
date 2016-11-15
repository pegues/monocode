<?php
if(isset($_GET['id'])){
	SystemModule()->Delete(
		array(
			'TableName'	=> prefix.'email',
			'DeleteId'	=> $_GET['id'],
			'Query'		=> '',
		)
    );
	set_status('success','Successfully deleted.');
	}
SystemModule()->SetupEdit(array('TableName'=>prefix.'email'));	
?>

<ol class="breadcrumb 2">
    <li><a href="<?php echo SystemModule()->getBasePath(); ?>admin"><i class="entypo-home"></i>Home</a></li>
    <li class="active"><strong>Emails</strong></li>
</ol>

<h1>
    <span>Email Address</span> 
    <a href="?page=email/new.php" class="btn btn-orange btn-sm" type="button">Add New Email</a>
</h1>

<br />

<?php echo get_status(); ?>
<form action="" method="post">
<?php
	$list=$db->get_results("select e.group_id,e.email_id, e.fullname, e.email_address, e.status, eg.email_group_name,eg.email_group_id from ".prefix."email e,".prefix."email_group eg where e.status='Y' and e.group_id=eg.email_group_id");
	
	$data=array('fields'=>
		array(
			'Name' => array(
				'data_field'	=> 'fullname',
				'css'			=> ''
			),
			
			'Email Address' => array(
				'data_field'	=> 'email_address',
				'css'			=> ''
			),
			'Email Group' => array(
				'data_field' 	=> 'email_group_name',
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
		'table_name'			=> 'email',
		'unique_key_field'		=> 'email_id'
	);
	echo GridView($data)->render();
	?>
</form>