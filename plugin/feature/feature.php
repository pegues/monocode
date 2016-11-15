<?php
if(isset($_GET['id'])){
	SystemModule()->Delete(
		array(
			'TableName'	=> prefix.'features',
			'DeleteId'	=> $_GET['id'],
			'Query'		=> '',
		)
    );
	set_status('success','Successfully deleted.');
	}
SystemModule()->SetupEdit(array('TableName'=>prefix.'features'));	
?>

<ol class="breadcrumb 2">
    <li><a href="<?php echo SystemModule()->getBasePath(); ?>admin"><i class="entypo-home"></i>Home</a></li>
    <li class="active"><strong>Packages</strong></li>
</ol>

<h1>
    <span>Packages</span> 
    <a href="?page=feature/new.php" class="btn btn-orange btn-sm" type="button">Add New Package</a>
</h1>

<br />

<?php echo get_status(); ?>
<form action="" method="post">
	<?php
		$list=$db->get_results("select * from ".prefix."features");
		
		$data=array('fields'=>
			array(
				'Packages Name' => array(
					'data_field'	=> 'feature_name',
					'css'			=> ''
				),
				'# of Workspaces' => array(
					'data_field'	=> 'work_space',
					'css'			=> ''
				),			
				'Allow SVN' => array(
					'data_field'	=> 'allow_svn',
					'css'			=> ''
				),
				'Allow FTP' => array(
					'data_field'	=> 'allow_ftp',
					'css'			=> ''
				),
				'Status' => array(
					'data_field'	=> 'status',
					'css'			=> 'status'
				),
				'Order' => array(
					'data_field'	=> 'display_order',
					'css'			=> ''
				),
				'Action' => array(
					'data_field'	=> 'action',
					'css'			=> 'action'
				)
			),
			'object'				=> $list,
			'table_name'			=> 'features',
			'unique_key_field'		=> 'feature_id'
		);
		echo GridView($data)->render();
	?>
</form>