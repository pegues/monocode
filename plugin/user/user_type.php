<?php
if(isset($_GET['id'])){
	if($_GET['id']!='2'){
	SystemModule()->Delete(
		array(
			'TableName'	=> prefix.'user_type',
			'DeleteId'	=> $_GET['id'],
			'Query'		=> '',
		)
    );
		set_status('success','Successfully deleted');
	}else{
		set_status('notice','You can\'t delete this user type');
	}
}
?>
<ol class="breadcrumb bc-2">
    <li><a href="<?php echo SystemModule()->getBasePath() ; ?>admin"><i class="entypo-home"></i>Home</a></li>
    <li class="active"><strong>User Plans</strong></li>
</ol>

<h2>
	<span>User Plans</span> 
	<a href="?page=user/editor_user_type.php" class="btn btn-orange btn-sm" type="button">Add New</a> 
</h2>

<br />
<?php echo get_status(); ?>
<form action="" method="post">
	<?php
	$list=$db->get_results("select* from ".prefix."user_type where user_type_id>1 order by display_order ASC");
	$data=array('fields'=>
		array(
			'User Plans'	=> array('data_field'=>'user_type_name','css'=>''),
			'Amount'		=> array('data_field'=>'amount','css'=>''),
			'Orders'		=> array('data_field'=>'display_order','css'=>'display_order'),
			'Status'		=> array('data_field'=>'status','css'=>'status'),
			'Action'		=> array('data_field'=>'action','css'=>'action')
		),
		'object'			=> $list,
		'table_name'		=> 'user_type',
		'edit_file_name'	=> 'editor_user_type.php',
		'unique_key_field'	=> 'user_type_id'
	);
	echo GridView($data)->render();
	?>
</form>