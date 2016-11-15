<?php
function create_slug($string,$db ) {
	$result = preg_replace("/[^a-zA-Z0-9]+/", "-", $string);
	$query='';
	if(isset($_GET['id'])){
		$query=" AND page_position_id!='".$_GET['id']."'";
	}
	$res=$db->get_results("select* from ".prefix."page_position where page_position_slug='$result'".$query);
	if(sizeof($res)==0){
	return strtolower($result);
	}else{
		return strtolower($result).'-'.sizeof($res);
	}
}
if(isset($_POST['page_position_name'])){
	//print_r($_GET); die();
	$_POST['page_position_slug']=create_slug($_POST['page_position_name'],$db);
		$id=SystemModule()->Save(
			array(
				'TableName'=>prefix.'page_position'
			),
			array(
				'page_position_name',
				'page_position_slug',
				
			)
		);
		set_status('success','Page Position successfully saved.');
	
}
SystemModule()->SetupEdit(array('TableName'=>prefix.'page_position'));
?>

<form method="post" action="" role="form" id="form1" class="form validate">
	<ol class="breadcrumb 2">
		<li><a href="<?php echo SystemModule()->getBasePath() ; ?>admin"><i class="entypo-home"></i>Home</a></li>
		<li class="active"><strong>New Page Position</strong></li>
	</ol>

	<h1>
		<span>Page Position</span> 
		<a class="btn btn-orange btn-sm" href="?page=page/page_position.php">Back to Page Positions List</a>
	</h1>
	
	<br/>
  
	<?php echo get_status(); ?>
	
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-primary" data-collapsed="0">
				<div class="panel-body">
					<div class="form-horizontal form-groups-bordered">
						
						<div class="form-group">
							<label class="col-sm-3 control-label">Page Position Name</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" name="page_position_name" id="menu_position_name"  data-message-required="Title is Required." data-validate="required" value=""/>
							</div>
						</div>                             
                        
						<div class="form-group">
							<label class="col-sm-3 control-label"></label>
							<div class="col-sm-9">
								<button type="submit" name="publish" class="btn btn-green btn-lg">
									<span>Save</span>
								</button>
                                
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>
<style type="text/css">
span.validate-has-error{
	color: #f00 !important;
}
</style>
