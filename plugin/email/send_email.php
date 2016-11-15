<?php
if(isset($_POST['send'])){
	$template_details=$db->get_results("select * from ".prefix."email_template where email_template_id='".$_POST['email_template_id']."'");	
		foreach($template_details as $value){
		$email_subject=$value->subject;
		$content=$value->email_template_content;

	foreach($_POST['checkbox'] as $val){
		$check="";
		$name='';	
		$check=$db->get_results("select * from ".prefix."email where email_address='".$val."'");
		if(empty($check)){
			$sec=$db->get_results("select * from ".prefix."user where email='".$val."'");
			if(sizeof($sec)>0){
			$name=$sec[0]->first_name;	
			}else{
				$name="sir/madem";
			}
		}
		else{
			$name=$check[0]->fullname;
		}
		
		$content=str_replace("[fullname]",$name,$content);
		$m=send($to=$val,$from=get_settings('email_address'),$from_name=get_settings('site_name'),$subject=$email_subject,$message=$content);
		if(!$m){
			set_status('notice','We are unable to send emails');
		}else {
			
   			set_status('success','Successfully sent.');
		}
	}
		
		}
	
}
SystemModule()->SetupEdit(array('TableName'=>prefix.'send_email'));
?>
<form action="" class="form" method="post">
	
	<ol class="breadcrumb 2">
		<li>
			<a href="<?php echo SystemModule()->getBasePath() ; ?>admin"><i class="entypo-home"></i>Home</a>
		</li>
		<li class="active">
			<strong>Send Email</strong>
		</li>
	</ol>
	
	<h1>
		<span>Send Email</span>
		<a href="?page=email/email_template.php" class="btn btn-orange btn-sm">Back to Email Template</a>
	</h1>
	
	<br/>
	
	<?php echo get_status(); ?>

	<div class="row">
		<div class="col-md-6">
			<div class="panel panel-primary" data-collapsed="0">
			
				<div class="panel-heading">
					<div class="panel-title">
						Send Mail Information
					</div>
				</div>
				
				<div class="panel-body">
					<div class="form-horizontal form-groups-bordered">
						
						<div class="form-group">
							<label class="col-sm-3 control-label">Email Template</label>
							<div class="col-sm-9">                            
							<select class="form-control" name="email_template_id" value="" id="email_template_id" >
                            <?php
							$template=$db->get_results("select * from ".prefix."email_template");
							foreach($template as $val){ ?>
                            <option value="<?php echo $val->email_template_id; ?>"><?php echo $val->email_template_name; ?></option> 
                            <?php } ?>
                                </select>
							</div>
						</div>                        
                        
						<div class="form-group">
							<label class="col-sm-3 control-label"></label>
							<div class="col-sm-9">
								<button class="btn btn-blue" type="submit" name="send">Send Email</button>
							</div>
						</div>
						
					</div>
				</div>
			</div>
		</div>

		<div class="col-md-6">
			<div class="panel panel-primary" data-collapsed="0">
			
				<div class="panel-heading">
					<div class="panel-title">
						Select Email Group
					</div>
				</div>
				
				<div class="panel-body">
					<div class="col-lg-12 nopadding" style="margin-top: 5px; margin-top: 5px; float: left; overflow-y: auto; overflow-x: hidden; height: 400px;">
						<ul class="tree">
							<?php
							$data=$db->get_results("select * from ".prefix."email_group");
							$ifnot=isset($_GET['id'])?$_GET['id']:'eeeee-xxx';
							foreach($data as $pages){
								
							?>
							<li>
								<a class="user_class">
									<input type="checkbox" value="<?php echo $pages->email_group_id; ?>"id="parent-<?php echo $pages->email_group_id; ?>" />
									<label for="parent-<?php echo $pages->email_group_id; ?>"><?php echo $pages->email_group_name; ?></label>
								</a>
								<?php echo getsub($db,$pages->email_group_id);?>
							</li>
							<?php
							}
							?>
						</ul>
					</div>
				</div>
			</div>
		</div>
		
		<?php
		function getsub($db,$group_id){
			$lost='<ul class="tree sub">';
			if($group_id=='5'){
				
				$data=$db->get_results("select * from ".prefix."user");
			if(sizeof($data)>0){
				foreach($data as $pages){
					$lost.='<li><a class="user_class"><input type="checkbox" value="'.$pages->email.'" name="checkbox[]" id="emails-'.$pages->email.'"><label for="parent-'.$pages->user_id.'">'.$pages->email.'</label></a>';
					
						$lost.='</li>';
					}
				}
			
				
			}else{
				
			
			$data=$db->get_results("select * from ".prefix."email where group_id='$group_id'");
			if(sizeof($data)>0){
				foreach($data as $pages){
					$lost.='<li><a class="user_class"><input type="checkbox" value="'.$pages->email_address.'" name="checkbox[]" id="emails-'.$pages->email_address.'"><label for="emails-'.$pages->email_id.'">'.$pages->email_address.'</label></a>';
					
						$lost.='</li>';
					}
				}
			}
			$lost.='</ul>';
			return $lost;
		}
		?>
		
        <div class="clearfix"></div>
    </div>
	
	<div class="clearfix"></div>   
</form>

<div class="clearfix"></div>

<script type="text/javascript">
	$(document).ready(function(e) {
		$(".tree input[type='checkbox']").click(function(){
			if($(this).is(":checked")){
				$(this).closest("li").find("ul input[type='checkbox']").prop('checked',true);
				$(this).parents("ul").eq(0).parents("li").eq(0).find("a:first>input[type='checkbox']").prop('checked',true);
				$(this).parents("ul").eq(0).parents("li").eq(0).parents("ul").eq(0).parents("li").eq(0).find("a:first>input[type='checkbox']").prop('checked',true);
			}else{
				$(this).closest("li").find("ul input[type='checkbox']").prop('checked',false);
			}
		});
	});
</script>