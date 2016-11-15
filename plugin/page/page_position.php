<?php
if(isset($_GET['action'])&&$_GET['id']!=''){
    SystemModule()->Delete(
		array(
			'TableName'	=> prefix.'page_position',
			'DeleteId'	=> $_GET['id'],
			'Query'		=> '',
		)
    );
	set_status('success','Page Position successfully deleted.');
}
?>

<ol class="breadcrumb 2">
    <li><a href="<?php echo SystemModule()->getBasePath(); ?>admin"><i class="entypo-home"></i>Home</a></li>
    <li class="active"><strong>Page Positions</strong></li>
</ol>

<h1>
	<span>Page Positions</span> 
    <a href="?page=page/new_page_position.php" class="btn btn-orange btn-sm" type="button">Create New Page Position</a>
</h1>

<br/>

<?php echo get_status();


 ?>

<form action="" method="post">
	<?php
	$list=$db->get_results("select* from " . prefix . "page_position order by display_order ASC");
	$data=array('fields'=>
		array(
			'ID'=>array(
				'data_field'	=> 'sn',
				'css'			=> 'sn'
			),
			'Page Position Names'=>array(
				'data_field'	=> 'page_position_name',
				'css'			=> ''
			),
			'Page Position Slugs'=>array(
				'data_field'	=> 'page_position_slug',
				'css'			=> ''
			),
			'Orders'=>array(
				'data_field'	=> 'display_order',
				'css'			=> 'display_order'
			),
			'Status'=>array(
				'data_field'	=> 'status',
				'css'			=> 'status'
			),
			'Action'=>array(
				'data_field'	=> 'action',
				'css'			=> 'action'
			)
		),
			'object'			=> $list,
			'table_name'		=> 'page_position',
			'edit_file_name'=>'new_page_position.php',
			'unique_key_field'	=> 'page_position_id'
	);
	echo GridView($data)->render();
	?>
</form>