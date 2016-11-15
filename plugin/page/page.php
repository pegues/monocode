<?php
if(isset($_GET['id'])){
    SystemModule()->Delete(
		array(
			'TableName'	=> prefix.'cms',
			'DeleteId'	=> $_GET['id'],
			'Query'		=> '',
		)
    );
	set_status('success','CMS page successfully deleted.');
}
?>

<ol class="breadcrumb 2">
    <li><a href="<?php echo SystemModule()->getBasePath(); ?>admin"><i class="entypo-home"></i>Home</a></li>
    <li class="active"><strong>Content Pages</strong></li>
</ol>

<h1>
	<span>Content Pages</span> 
    <a href="?page=page/new.php" class="btn btn-orange btn-sm" type="button">Create New Page</a>
</h1>

<br/>

<?php echo get_status();


 ?>

<form action="" method="post">
	<?php
	$list=$db->get_results("select  
	c.cms_id, 
	c.cms_title, 
	c.display_order, 
	c.status, 
	c.page_position_id, 
	p.page_position_id, 
	p.page_position_name from 
	" . prefix . "cms c left join ". prefix . "page_position p ON c.page_position_id=p.page_position_id order by c.display_order ASC");
	 	$data=array('fields'=>
		array(
			'ID'=>array(
				'data_field'	=> 'sn',
				'css'			=> 'sn'
			),
			'Page Name'=>array(
				'data_field'	=> 'cms_title',
				'css'			=> ''
			),
			'Page Position Name'=>array(
				'data_field'	=> 'page_position_name',
				'css'			=> 'action'
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
			'table_name'		=> 'cms',
			'unique_key_field'	=> 'cms_id'
	);
	echo GridView($data)->render();
	?>
</form>