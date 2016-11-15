<?php
if(isset($_GET['id'])){
	if($_GET['id']!=$_SESSION[encryption_key.'user_id']){
		
    SystemModule()->Delete(
		array(
			'TableName'	=> prefix.'user',
			'DeleteId'	=> $_GET['id'],
			'Query'		=> '',
		)
    );
	set_status('success','Successfully deleted.');
	}else{
		set_status('notice','You can\'t delete yourself.');
	}
}
?>

<ol class="breadcrumb 2">
    <li><a href="<?php echo SystemModule()->getBasePath(); ?>admin"><i class="entypo-home"></i>Home</a></li>
    <li class="active"><strong>Users</strong></li>
</ol>

<h1>
    <span>User Accounts</span> 
    <a href="?page=user/new.php" class="btn btn-orange btn-sm" type="button">Add New User</a>
</h1>

<br />

<?php echo get_status(); ?>
<form action="" method="post">
<?php
	$list=$db->get_results("select *, CONCAT(u.first_name,' ', u.last_name) as fullname from " . prefix . "user u," . prefix . "user_type t  where u.user_type=t.user_type_id AND u.user_id>0 order by u.display_order ASC");
	$data=array('fields'=>
		array(
			
			'Full Name' => array(
				'data_field'	=> 'fullname',
				'css'			=> ''
			),
			'Username' => array(
				'data_field'	=> 'user_name',
				'css'			=> ''
			),
			'Email Address' => array(
				'data_field'	=> 'email',
				'css'			=> ''
			),
			'User Plans' => array(
				'data_field' 	=> 'user_type_name',
				'css' 			=> ''
			),
			'Last Login' => array(
				'data_field'	=> 'last_login',
				'css'			=> ''
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
		'table_name'			=> 'user',
		'unique_key_field'		=> 'user_id'
	);
	echo GridView($data)->render();
	?>
</form>