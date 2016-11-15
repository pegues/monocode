<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once 'Controller.php';
	class Common extends Controller {
		
		public function ws(){
			$this->load->view('editor/options');
			$_ws=get_option('ws');
			$_ws=json_decode($_ws,true);
			$_ws[$_POST['ws_id']]['ws_name']=$_POST['ws_name'];
			update_option('ws',json_encode($_ws,true));
		}
		
		public function ws_change(){
			$this->load->view('editor/options');
			$_ws=get_option('ws');
			$_ws=json_decode($_ws,true);
			$_obj=array();
			
			foreach($_ws as $key=>$valu){
				$_obj[$key]=array('ws_directory'=>$valu['ws_directory'],'ws_name'=>$valu['ws_name'],'ws_status'=>'enable','ws_active'=>'false');
			}
			
			$_obj[$_POST['ws_directory']]['ws_active']='true';
			update_option('ws',json_encode($_obj,true));
			
			if ($this->isStorageLocal()) {
				if (!is_dir($this->getActiveWorkspace())) {
					$umask = umask(0);
					mkdir($this->getActiveWorkspace(), 0777);
					umask($umask);
				}
			}
		}
		
		public function ws_import(){
			$this->load->view('editor/workspace_import', array('workpath' => $this->getWorkpath(), 'workshop' => $this->getWorkshop()));
		}
		
		public function ws_delete(){
			$this->load->view('editor/options');
			$_ws=get_option('ws');
			$_ws=json_decode($_ws,true);
			
			if(isset($_ws[$_POST['ws_id']])){
				unset($_ws[$_POST['ws_id']]);
				$this->Delete($this->input->post('path').'/'.$_POST['ws_id']);
			}
			
			update_option('ws',json_encode($_ws,true));
			$_ws['avai']=$this->__get_feature('work_space');
			echo json_encode($_ws,true);
		}
		
		public function ws_add(){
			$this->load->view('editor/options');
			$_ws=get_option('ws');
			$_ws=json_decode($_ws,true);
			
			if(!is_array($_ws) && sizeof($_ws)==0){
				$_ws=array();
			}
			
			if(sizeof($_ws)<$this->__get_feature('work_space')){
				$t = 'ws-' . UUID::v4();
				$act=(sizeof($_ws)==0)?'true':'false';
				
				$_ws[$t]=array('ws_directory'=>$t,'ws_name'=>'New Workspace','ws_status'=>'enable','ws_active'=>$act);
				
				if(!is_dir($this->getWorkshop().$t)){
					$umask=umask(0);
					mkdir($this->getWorkshop().$t, 0777);
					umask($umask);
				}
				
				update_option('ws',json_encode($_ws,true));
			}
			
			$_ws['avai']=$this->__get_feature('work_space');
			echo json_encode($_ws,true);
		}
		
		function Delete($path){
			if (is_dir($path) === true){
				$files = array_diff(scandir($path), array('.', '..'));
				
				foreach ($files as $file){
					$this->Delete(realpath($path) . '/' . $file);
				}
				
				return rmdir($path);
			}
			else if (is_file($path) === true){
				chmod($path, 0750);
				
				return unlink($path);
			}
			
			return false;
		}
		
		public function ws_remove(){
			$this->load->view('editor/options');
			$_ws=get_option('ws');
			$_ws=json_decode($_ws,true);
			$_ws[$_POST['ws_id']]['active']='true';
			update_option('ws',json_encode($_ws,true));
		}
		
		public function inport_zip($e){
			ob_start();
			$temp = strtolower($_FILES['import']['name']);
			$n = preg_replace("/[^a-z0-9_-s.]/i","",$temp);
			
			if (is_uploaded_file($_FILES['uploadfile']['tmp_name'])) {
				$result = move_uploaded_file($_FILES['uploadfile']['tmp_name'],'../theme_zip/'.$n );
				
				if ($result == 1){
					$zip = new ZipArchive;
					$res = $zip->open('../theme_zip/'.$n);
					
					if ($res === TRUE) {
						$zip->extractTo(PATH);
						$zip->close();
					} else {
						
					}
				}
			}
		}
		
		public function export_zip(){
			$this->load->view('editor/options');
			$_ws=get_option('ws');
			$_ws=json_decode($_ws,true);
			$name=md5($this->__get_active_workspace_directory().'-'.get_user_id().'-'.date("Y-m-d"));
			$zip=new Zipper();
			$zip->open('backups/'.$name.'.zip',Zipper::CREATE);
			$zip->addDir('workspace/' . $this->__get_workshop() . '/' . $this->__get_active_workspace_directory() . '/');
		
			$this->sendMail(
				$this->session->userdata("user_email"),
				"Please find download link of your workspace",
				"Hi ".$this->session->userdata("user_name").",<br> 
				At your request we have prepared a download link of your workspace: ".$_ws[$this->__get_active_workspace_directory()]['ws_name']." . <br><br>Download Link: ".base_url()."backups/".$name.".zip<br><br>
				Sincerely,<br>
				".get_settings('site_name')." Team"
			);
		}
		
		public function file_new(){
			$file=$_POST['fileURL'];
			$createName=$_POST['fileName'];
			
			if(file_exists($_POST['fileURL'].$_POST['fileName'].'.php')){
				for($i=0; $i<=50; $i++){
					$createName=$_POST['fileName']."-".$i;
					
					if(!file_exists($_POST['fileURL'].$createName.'.php')){
						$new=fopen($_POST['fileURL'].$createName.'.php', "w") ;	
						$txt = "/* New File */";
						fwrite($new, $txt);
						fclose($new);
						break;
					}
				}
			}else{
				$new=fopen($_POST['fileURL'].$createName.'.php', "w") ;
				$txt = "/* New File */";
				fwrite($new, $txt);
				fclose($new);
			}
			echo $createName.'.php';
		}
	}
	
	class Zipper extends ZipArchive {
		public function addDir($path) {
			$this->addEmptyDir($path);
			$nodes = glob($path . '/*');
			
			foreach ($nodes as $node) {
				if (is_dir($node)) {
					$this->addDir($node);
				} else if (is_file($node))  {
					$this->addFile($node);
				}
			}
		}
	}
?>