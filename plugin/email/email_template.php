<?php
if(isset($_GET['id'])){
	$a=array('1','3','6','5');
	if(!in_array($_GET['id'],$a)){	
    SystemModule()->Delete(
		array(
			'TableName'	=> prefix.'email_template',
			'DeleteId'	=> $_GET['id'],
			'Query'		=> '',
		)
    );
	set_status('success','Successfully deleted.');
	}else{
		set_status('notice','You can not delete this Email Template');
	}
}
?>

<ol class="breadcrumb 2">
    <li><a href="<?php echo SystemModule()->getBasePath(); ?>admin"><i class="entypo-home"></i>Home</a></li>
    <li class="active"><strong>Email Templates</strong></li>
</ol>

<h1>
    <span>Email Templates </span> 
    <a href="?page=email/editor_email_template.php" class="btn btn-orange btn-sm" type="button">Add New Template</a>
</h1>

<br />

<?php echo get_status(); ?>
<form action="" method="post">
<?php
            $list=$db->get_results("select t.display_order,t.email_template_id,g.email_group_name,t.subject,t.email_template_name from ".prefix."email_template t LEFT JOIN ".prefix."email_group g ON t.email_group=g.email_group_id order by t.display_order DESC");
			$data=array('fields'=>
							array(
								'Email Templates Name'=>array('data_field'=>'email_template_name','css'=>''),
								'Email Group'=>array('data_field'=>'email_group_name','css'=>''),
								'Subject'=>array('data_field'=>'subject','css'=>''),
								'ORDERS'=>array('data_field'=>'display_order','css'=>'display_order'),
								/*'Status'=>array('data_field'=>'status','css'=>'status'),*/
								'Action'=>array('data_field'=>'action','css'=>'action')
							),
							'object'=>$list,
							'table_name'=>'email_template',
							'edit_file_name'=>'editor_email_template.php',
							'unique_key_field'=>'email_template_id'
			);
			echo GridView($data)->render();
			
			?>
</form>