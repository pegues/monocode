<?php
if(isset($_GET['id'])){
    SystemModule()->Delete(
		array(
			'TableName'	=> prefix.'editor',
			'DeleteId'	=> $_GET['id'],
			'Query'		=> '',
		)
    );
}
?>
<ol class="breadcrumb bc-2">
    <li><a href="<?php echo SystemModule()->getBasePath(); ?>admin"><i class="entypo-home"></i>Home</a></li>
    <li class="active"><strong>Editor</strong></li>
</ol>

<h2>
	<span>Editor Modules</span> 
    <a href="?page=editor/new.php" class="btn btn-orange btn-sm" type="button">Add New</a>
</h2>

<?php echo get_status(); ?>

<form action="" method="post">
	<?php
	$list=$db->get_results("select* from " . prefix . "editor order by display_order ASC");

	$data=array('fields'=>
		array(
			'ID' => array(
				'data_field'	=> 'sn',
				'css'			=> 'sn'
			),
			'Module Name' => array(
				'data_field'	=> 'name',
				'css'			=> ''
			),
			'Module Action'	=> array(
				'data_field'	=> 'data_action',
				'css'			=> ''
			),
			'Orders' => array(
				'data_field'	=> 'display_order',
				'css'			=> 'display_order'
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
		'object'			=> $list,
		'table_name'		=> 'editor',
		'edit_file_name'	=> 'new.php',
		'unique_key_field'	=> 'editor_id'
	);
	echo GridView($data)->render();
	?>
</form>