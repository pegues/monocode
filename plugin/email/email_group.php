<?php
if(isset($_GET['id'])){
if($_GET['id']!='5'){	
		SystemModule()->Delete(
		array(
                    'TableName'=>prefix.'email_group',
                    'DeleteId'=>$_GET['id'],
                    'Query'=>'',
                    )
    );
	set_status('success','Successfully deleted');	
	}else{
	set_status('notice','You can not delete this Email Group');
	}
}
?>
<ol class="breadcrumb bc-2">
    <li>
        <a href="<?php echo SystemModule()->getBasePath() ; ?>admin"><i class="entypo-home"></i>Home</a>
    </li>
    <li class="active">
        <strong>Email Group</strong>
    </li>
</ol>
<h2>
    <span >Email Group</span> 
    <a href="?page=email/editor_email_group.php" class="btn btn-orange btn-sm" type="button">Add New</a>
</h2><br />
<?php

echo get_status();
?>
<form action="" method="post">
<?php
            $list=$db->get_results("select * from ".prefix."email_group order by display_order ASC");
			$data=array('fields'=>
							array(
								'Email Group Name'=>array('data_field'=>'email_group_name','css'=>''),
								'Status'=>array('data_field'=>'status','css'=>'status'),
								'Action'=>array('data_field'=>'action','css'=>'action')
							),
							'object'=>$list,
							'table_name'=>'email_group',
							'edit_file_name'=>'editor_email_group.php',
							'unique_key_field'=>'email_group_id'
			);
			echo GridView($data)->render();
			
			?>
</form>