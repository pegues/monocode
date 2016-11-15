<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once 'Controller.php';
	class Ftp extends Controller {
		var $_ftp_instance='';
		var $_ftp_status=array();
		var $ftp_log_id='';
		var $ftp_host='';
		var $ftp_username='';
		var $ftp_password='';
		var $ftp_port='';
		var $ftp_domain='';
		var $file_to_upload='';
		var $to='';
		var $from='';
		
		public function connect(){
			$_exOptions=$this->getOptions($this->ftp_log_id);
			$this->_ftp_status=json_decode($_exOptions);
			
			if($this->ftp_host!=''){
				if(sizeof($this->_ftp_status)==0){
					$this->_ftp_status[]=array('index'=>'connect','class'=>'warning','message'=>'Connecting to ' . $this->ftp_host . '.');
					$this->updateOptions();
				}
				
				$this->_ftp_instance=ftp_connect($this->ftp_host);
				
				if($this->_ftp_instance){
					if(sizeof($this->_ftp_status)==1){
						$this->_ftp_status[]=array('index'=>'connect','class'=>'success','message'=>'Connected to ' . $this->ftp_host . '.');
						$this->updateOptions();
					}
					
					if(sizeof($this->_ftp_status)==2){
						$this->_ftp_status[]=array('index'=>'login','class'=>'warning','message'=>'Logging in....');
						$this->updateOptions();
					}
					
					$login_result = ftp_login($this->_ftp_instance, $this->ftp_username, $this->ftp_password);
					ftp_pasv($this->_ftp_instance, TRUE);
					
					if(!$login_result){
						$this->_ftp_status[]=array('index'=>'login','class'=>'error','message'=>'Could not login to the server. Please check your user details and try again.');
						$this->updateOptions();
						
						die('error');
					}else{
						if(sizeof($this->_ftp_status)==3){
							$this->_ftp_status[]=array('index'=>'login','class'=>'success','message'=>'Successfully logged in to ' . $this->ftp_host.'.');
							$this->updateOptions();
						}
						return $this->_ftp_instance;
					}
				}else{
					$this->_ftp_status[]=array('index'=>'login','class'=>'error','message'=>'Could not connect to ' . $this->ftp_host . '. Please check your connection settings and try again.');
					$this->updateOptions();
					$this->_ftp_status[]=array('index'=>'login','class'=>'error','message'=>'...........');
					$this->updateOptions();
				}
			}
		}
		
		// FTP New/Edit Connection Popup
		public function ftpdetails() {
			$this->load->view('editor/popup_ftp_connection_details');
		}
		
		public function get_ftp_list(){
			$this->load->view('editor/options');
			echo $existingOptions=get_option('ftps');
		}
		
		public function delete_array(){
			$this->load->view('editor/options');
			
			if(isset($_POST['ftp_log_id'])){
				$old=get_option('ftps');
				$old=json_decode($old,true);
				
				foreach($_POST['ftp_log_id'] as $newkey){
					foreach($old as $key=>$val){
						if($key==$newkey){
							unset($old[$key]);
						}
					}
				}
				update_option('ftps',json_encode($old,true));
			}
		}
		
		// FTP Connection Tab
		public function ftpconnection() {
			$this->load->view('editor/ftp_connection');
		}
		
		public function get_file_list(){
			$this->load->view('editor/options');
			$rec=json_decode(get_option('ftps'),true);
			$this->ftp_log_id=$_POST['ftp_log_id'];
			$this->ftp_host=$rec[$this->ftp_log_id]['ftp_host'];
			$this->ftp_domain=$rec[$this->ftp_log_id]['ftp_domain'];
			$this->ftp_username=$rec[$this->ftp_log_id]['ftp_username'];
			$this->ftp_password=$rec[$this->ftp_log_id]['ftp_password'];
			$this->ftp_port=$rec[$this->ftp_log_id]['ftp_port'];
			$this->ftp_directory=$_POST['ftp_directory'];
			$this->connect();
			
			if($this->_ftp_instance){
				$dir=($this->ftp_directory=='.')?'root/':$this->ftp_directory."/";
				$this->_ftp_status[]=array('index'=>'other','class'=>'warning','message'=>'Directory listing (' . $dir . ') Please wait...');
				$this->updateOptions();
				$contents = ftp_nlist($this->_ftp_instance, $this->ftp_directory);
				$this->_ftp_status[]=array('index'=>'other','class'=>'success','message'=>'Directory listed.');
				$this->updateOptions();
			}
			
			//foreach($contents as)
			$contents['details']=array('ftp_directory'=>$this->ftp_directory,'ftp_log_id'=>$this->ftp_log_id,'ftp_domain'=>$this->ftp_domain,'ftp_host'=>$this->ftp_host);
			echo json_encode($contents,true);
			//$old_status=get_option('$_POST['host']');
		}
		
		public function upload_to_ftp(){
			$this->load->view('editor/options');
			$rec=json_decode(get_option('ftps'),true);
			$this->ftp_log_id=$_POST['ftp_log_id'];
			$this->ftp_host=$rec[$this->ftp_log_id]['ftp_host'];
			$this->ftp_domain=$rec[$this->ftp_log_id]['ftp_domain'];
			$this->ftp_username=$rec[$this->ftp_log_id]['ftp_username'];
			$this->ftp_password=$rec[$this->ftp_log_id]['ftp_password'];
			$this->ftp_port=$rec[$this->ftp_log_id]['ftp_port'];
			$this->ftp_directory=$_POST['to'];
			$this->to=$_POST['to'];
			$this->from=$_POST['from'];
			$this->connect();
			
			if($this->_ftp_instance){
				$dir=($this->to=='.') ? 'root/' : $this->to . "/";
				$this->_ftp_status[]=array('index'=>'other','class'=>'warning','message'=>'Copying file to (' . $dir.basename($this->from) . '). Please wait...');
				$this->updateOptions();
				$uploaders = ftp_put($this->_ftp_instance, $this->to.'/'.basename($this->from),$this->from,FTP_ASCII);
				
				if($uploaders){
					$this->_ftp_status[]=array('index'=>'other','class'=>'success','message'=>'Successfully uploaded.');
					$this->updateOptions();
				}else{
					$this->_ftp_status[]=array('index'=>'other','class'=>'error','message'=>'Could not copy to the server. Please try again later.');
					$this->updateOptions();
				}
				
				$this->_ftp_status[]=array('index'=>'other','class'=>'warning','message'=>'Refreshing (' . $dir . '). Please wait...');
				$this->updateOptions();
				$contents = ftp_nlist($this->_ftp_instance, $this->ftp_directory);
				$this->_ftp_status[]=array('index'=>'other','class'=>'success','message'=>'Directory successfully refreshed.');
				$this->updateOptions();
			}
			
			//foreach($contents as)
			$contents['details']=array('ftp_directory'=>$this->ftp_directory,'ftp_log_id'=>$this->ftp_log_id,'ftp_domain'=>$this->ftp_domain,'ftp_host'=>$this->ftp_host);
			echo json_encode($contents,true);
			//$old_status=get_option('$_POST['host']');
		}
		
		public function rename_local(){
			rename($_POST['from'],$_POST['to']);
		}
		
		public function ftp_rename(){
			$this->load->view('editor/options');
			$rec=json_decode(get_option('ftps'),true);
			$this->ftp_log_id=$_POST['ftp_log_id'];
			$this->ftp_host=$rec[$this->ftp_log_id]['ftp_host'];
			$this->ftp_domain=$rec[$this->ftp_log_id]['ftp_domain'];
			$this->ftp_username=$rec[$this->ftp_log_id]['ftp_username'];
			$this->ftp_password=$rec[$this->ftp_log_id]['ftp_password'];
			$this->ftp_port=$rec[$this->ftp_log_id]['ftp_port'];
			$this->ftp_directory=$_POST['ftp_directory'];
			$this->to=$_POST['to'];
			$this->from=$_POST['from'];
			$this->connect();
			
			if($this->_ftp_instance){
				$dir=($this->ftp_directory=='.')?'root/':$this->ftp_directory."/";
				$this->_ftp_status[]=array('index'=>'other','class'=>'warning','message'=>'Renaming file to ' . $this->to . '. Please wait...');
				$this->updateOptions();
				$uploaders = ftp_rename($this->_ftp_instance,$this->from,$this->to);
				
				if($uploaders){
					$this->_ftp_status[]=array('index'=>'other','class'=>'success','message'=>'File successfully renamed.');
					$this->updateOptions();
				}else{
					$this->_ftp_status[]=array('index'=>'other','class'=>'error','message'=>'Could not rename file. Please try again later.');
					$this->updateOptions();
				}
				
				$this->_ftp_status[]=array('index'=>'other','class'=>'warning','message'=>'Refreshing (' . $dir . '). Please wait...');
				$this->updateOptions();
				$contents = ftp_nlist($this->_ftp_instance, $this->ftp_directory);
				$this->_ftp_status[]=array('index'=>'other','class'=>'success','message'=>'Directory successfully refreshed.');
				$this->updateOptions();
			}
			$contents['details']=array('ftp_directory'=>$this->ftp_directory,'ftp_log_id'=>$this->ftp_log_id,'ftp_domain'=>$this->ftp_domain,'ftp_host'=>$this->ftp_host);
			echo json_encode($contents,true);
		}
		
		public function ftp_to_local(){
			$this->load->view('editor/options');
			$rec=json_decode(get_option('ftps'),true);
			$this->ftp_log_id=$_POST['ftp_log_id'];
			$this->ftp_host=$rec[$this->ftp_log_id]['ftp_host'];
			$this->ftp_domain=$rec[$this->ftp_log_id]['ftp_domain'];
			$this->ftp_username=$rec[$this->ftp_log_id]['ftp_username'];
			$this->ftp_password=$rec[$this->ftp_log_id]['ftp_password'];
			$this->ftp_port=$rec[$this->ftp_log_id]['ftp_port'];
			$this->ftp_directory=$_POST['ftp_directory'];
			$this->to=$this->filterPath($_POST['to']);
			$this->from=$this->filterPath($_POST['from']);
			$this->connect();
			
			if($this->_ftp_instance){
				$dir=($this->to=='.')?'root/':$this->to."/";
				$this->_ftp_status[]=array('index'=>'other','class'=>'warning','message'=>'Copying file(s) to ('.$this->filterPath($dir.basename($this->from)).'). Please wait...');
				$this->updateOptions();
				$uploaders =ftp_get($this->_ftp_instance, $this->filterPath($this->to.'/'.basename($this->from)),$this->from,FTP_BINARY);
				
				if($uploaders){
					$this->_ftp_status[]=array('index'=>'other','class'=>'success','message'=>'File(s) successfully copied.');
					$this->updateOptions();
				}else{
					$this->_ftp_status[]=array('index'=>'other','class'=>'error','message'=>'Could not copy file(s). Please try again later.');
					$this->updateOptions();
				}
				
				$this->_ftp_status[]=array('index'=>'other','class'=>'warning','message'=>'Refreshing (' . $this->filterPath($dir) . '). Please wait...');
				$this->updateOptions();
				$contents = ftp_nlist($this->_ftp_instance, $this->ftp_directory);
				$this->_ftp_status[]=array('index'=>'other','class'=>'success','message'=>'Directory successfully refreshed.');
				$this->updateOptions();
			}
			
			//foreach($contents as)
			$contents['details']=array('ftp_directory'=>$this->ftp_directory,'ftp_log_id'=>$this->ftp_log_id,'ftp_domain'=>$this->ftp_domain,'ftp_host'=>$this->ftp_host);
			echo json_encode($contents,true);
			//$old_status=get_option('$_POST['host']');
		}
		
		public function getstatus(){
			$this->load->view('editor/options');
			echo get_option($_POST['ftp_log_id']);
		}
		
		public function updateOptions(){
			$this->load->view('editor/options');
			update_option($this->ftp_log_id,json_encode($this->_ftp_status,true));
		}
		
		public function getOptions(){
			$this->load->view('editor/options');
			return get_option($this->ftp_log_id);
		}
		
		public function ftp_create_new(){
			$this->load->view('editor/options');
			$rec=json_decode(get_option('ftps'),true);
			$this->ftp_log_id=$_POST['ftp_log_id'];
			$this->ftp_host=$rec[$this->ftp_log_id]['ftp_host'];
			$this->ftp_domain=$rec[$this->ftp_log_id]['ftp_domain'];
			$this->ftp_username=$rec[$this->ftp_log_id]['ftp_username'];
			$this->ftp_password=$rec[$this->ftp_log_id]['ftp_password'];
			$this->ftp_port=$rec[$this->ftp_log_id]['ftp_port'];
			$this->ftp_directory=$_POST['ftp_directory'];
			$this->fileName=$_POST['fileName'];
			$this->connect();
			
			if($this->_ftp_instance){
				if($_POST['type']=='file'){
					$fp = fopen('php://temp', 'r+');
					fwrite($fp, "/* untitled document */");
					rewind($fp);
					
					if(@ftp_fput($this->_ftp_instance,$this->ftp_directory.'/'.$this->fileName, $fp, FTP_ASCII)){
						$this->_ftp_status[]=array('index'=>'other','class'=>'warning','message'=>'The file ' . $this->fileName . ' was successfully created.');
						$this->updateOptions();
					}else{
						$this->_ftp_status[]=array('index'=>'other','class'=>'warning','message'=>'Error while creating the file ' . $this->fileName . '. Please check your file name to see if it already exists, then try again.');
						$this->updateOptions();
					}
				}else{
					if (@ftp_mkdir($this->_ftp_instance,$this->ftp_directory.'/'.$this->fileName)) {
						$this->_ftp_status[]=array('index'=>'other','class'=>'warning','message'=>'Successfully created the directory ' . $this->fileName . '.');
						$this->updateOptions();
					}
					else {
						$this->_ftp_status[]=array('index'=>'other','class'=>'warning','message'=>'Error while creating the directory ' . $this->fileName . '. Check your directory name to see if it already exists, then try again.');
						$this->updateOptions();
					}
				}
			}
			
			$contents = ftp_nlist($this->_ftp_instance, $this->ftp_directory);
			$this->_ftp_status[]=array('index'=>'other','class'=>'success','message'=>'Directory successfully refreshed.');
			$this->updateOptions();
			$contents['details']=array('ftp_directory'=>$this->ftp_directory,'ftp_log_id'=>$this->ftp_log_id);
			echo json_encode($contents,true);
		}
		
		public function get_local_filelist(){
			$this->ftp_directory=$_POST['ftp_directory'].'/';
			$this->ftp_log_id=$_POST['ftp_log_id'];
			$dires_files=array();
			
			if( file_exists($this->ftp_directory)){
				$files = scandir($this->ftp_directory);
				natcasesort($files);
				$dirindex=0;
				$fileindex=0;

				if( count($files) > 2 ) { /* The 2 accounts for . and .. */
					foreach($files as $file ) {
						$dirindex++;
						
						if( file_exists($this->ftp_directory.$file) && $file != '.' && $file != '..' && is_dir($this->ftp_directory . $file) ) {
							$dires_files[]=$this->ftp_directory.$file;
						}
					}
					foreach($files as $file ) {
						$fileindex++;
						
						if(file_exists($this->ftp_directory.$file) && $file != '.' && $file != '..' && !is_dir($this->ftp_directory.$file) ) {
							$dires_files[]=$this->ftp_directory.$file;
						}
					}
				}
			}
			
			$dires_files['details']=array('ftp_directory'=>$this->ftp_directory,'ftp_log_id'=>$this->ftp_log_id);
			echo json_encode($dires_files,true);
		}
		
		public function recursiveDelete($directory){
			if(!(@ftp_rmdir($this->_ftp_instance, $directory) || @ftp_delete($this->_ftp_instance, $directory))) {
				$filelist = @ftp_nlist($this->_ftp_instance, $directory);
				foreach($filelist as $file) {
					if(substr($file,-1)!='.'){
						$this->_ftp_status[]=array('index'=>'other','class'=>'warning','message'=>'Deleting the file ' . $file . '.');
						$this->updateOptions();
						$this->recursiveDelete($file);
					}
				}
				$this->recursiveDelete($directory);
			}else{
				$this->_ftp_status[]=array('index'=>'other','class'=>'success','message'=>$directory . ' has been successfully deleted.');
				$this->updateOptions();
			}
		}
		
		public function delete_ftp_file(){
			$this->load->view('editor/options');
			$rec=json_decode(get_option('ftps'),true);
			$this->ftp_log_id=$_POST[1]['ftp_log_id'];
			$this->ftp_host=$rec[$this->ftp_log_id]['ftp_host'];
			$this->ftp_domain=$rec[$this->ftp_log_id]['ftp_domain'];
			$this->ftp_username=$rec[$this->ftp_log_id]['ftp_username'];
			$this->ftp_password=$rec[$this->ftp_log_id]['ftp_password'];
			$this->ftp_port=$rec[$this->ftp_log_id]['ftp_port'];
			$this->ftp_directory=$_POST['1']['directory'];
			$this->fileUrl=$_POST['1']['fileUrl'];
			$this->connect();
			
			if($this->_ftp_instance){
				$dir=($this->ftp_directory=='.')?'root/':$this->ftp_directory."/";
				
				foreach($_POST as $key=>$value){
					$this->recursiveDelete($value['fileUrl']);
				}
				
				$this->_ftp_status[]=array('index'=>'other','class'=>'warning','message'=>'Refreshing ('.$dir.'). Please wait...');
				$this->updateOptions();
				$contents = ftp_nlist($this->_ftp_instance, $this->ftp_directory);
				$this->_ftp_status[]=array('index'=>'other','class'=>'success','message'=>'Directory successfully refreshed.');
				$this->updateOptions();
			}
			
			$contents['details']=array('ftp_directory'=>$this->ftp_directory,'ftp_log_id'=>$this->ftp_log_id,'ftp_domain'=>$this->ftp_domain,'ftp_host'=>$this->ftp_host);
			echo json_encode($contents,true);
		}
		
		public function past(){
			$this->load->view('editor/options');
			$rec=json_decode(get_option('ftps'),true);
			
			if(is_array($_POST)){
				$this->ftp_log_id=$_POST[1]['ftp_log_id'];
				$this->ftp_host=$rec[$this->ftp_log_id]['ftp_host'];
				$this->ftp_domain=$rec[$this->ftp_log_id]['ftp_domain'];
				$this->ftp_username=$rec[$this->ftp_log_id]['ftp_username'];
				$this->ftp_password=$rec[$this->ftp_log_id]['ftp_password'];
				$this->ftp_port=$rec[$this->ftp_log_id]['ftp_port'];
				$this->past_directory=$_POST[1]['past_directory'];
				$this->copy_directory=$_POST[1]['copy_directory'];
				$this->from=$_POST[1]['from'];
				$this->to=$_POST[1]['to'];
				
				$this->connect();
				
				foreach($_POST as $key=>$value){
					$past_to=$this->filterPath($value['past_directory'].'/'.basename($value['fileUrl']));
					$copy_from=$this->filterPath($value['copy_directory'].'/'.basename($value['fileUrl']));
					
					if($value['from']=='local' && ($value['to']=='remote' || $value['to']=='server')){
						$this->_ftp_status[]=array(
							'index'		=> 'other',
							'class'		=> 'success',
							'message'	=> 'Copying file to '.$past_to
						);
						$this->updateOptions();
						$uploaders =@ftp_put($this->_ftp_instance,$past_to,$copy_from,FTP_ASCII);
						
						if($uploaders){
							$this->_ftp_status[]=array(
								'index'		=> 'other',
								'class'		=> 'success',
								'message'	=> 'Successfully copied.'
							);
							
							$this->updateOptions();
							
							if(isset($value['file_action']) && $value['file_action']=='cut'){
								$this->Delete($copy_from);
							}
						}else{
							$this->_ftp_status[]=array(
								'index'		=> 'other',
								'class'		=> 'error',
								'message'	=> 'Could not copy [' . $past_to . ']. Please note that you cannot copy or paste this directory(folder).'
							);
							
							$this->updateOptions();
						}
					}
					
					if(($value['from']=='remote' || $value['from']=='server') && $value['to']=='local'){
						$this->_ftp_status[]=array(
							'index'		=> 'other',
							'class'		=> 'success',
							'message'	=> 'Copying file to '.$past_to
						);
						
						$this->updateOptions();
						$uploaders=@ftp_get($this->_ftp_instance,$past_to,$copy_from,FTP_ASCII);
						
						if($uploaders){
								$this->_ftp_status[]=array(
									'index'=>'other',
									'class'=>'success',
									'message'=>$past_to . ' has been successfully copied.'
								);
							$this->updateOptions();
							
							if(isset($value['file_action']) && $value['file_action']=='cut'){
								$this->recursiveDelete($copy_from);
							}
						}else{
							$this->_ftp_status[]=array(
								'index'		=> 'other',
								'class'		=> 'error',
								'message'	=> 'Could not copy to ' . $past_to . '. If you are trying to copy a directory, directory copying is not supported in this version.'
							);
							$this->updateOptions();
						}
					}
				}
			}
			
			$dires='';
			
			if($this->_ftp_instance){
				if($this->to=='local' && ($this->from='server' || $this->from='remote')){
					$dires=$this->filterPath($this->copy_directory);
				}else if($this->from=='local' && ($this->to=='server' || $this->to=='remote')){
					$dires=$this->filterPath($this->past_directory);
				}
				
				if($dires!=''){
					$this->_ftp_status[]=array('index'=>'other','class'=>'warning','message'=>'Refreshing (' . $this->filterPath($dires) . '). Please wait...');
					$this->updateOptions();
					$contents = ftp_nlist($this->_ftp_instance, $dires);
					$this->_ftp_status[]=array('index'=>'other','class'=>'success','message'=>'Directory successfully refreshed.');
					$this->updateOptions();
				}
			}
			
			$contents['details']=array('ftp_directory'=>$this->filterPath($dires),'ftp_log_id'=>$this->ftp_log_id,'ftp_domain'=>$this->ftp_domain,'ftp_host'=>$this->ftp_host);
			echo json_encode($contents,true);
		}
		
		public function delete_files_local(){
			$this->load->view('editor/options');
			$rec=json_decode(get_option('ftps'),true);
			$this->ftp_log_id=$_POST[1]['ftp_log_id'];
			$this->ftp_host=$rec[$this->ftp_log_id]['ftp_host'];
			$this->ftp_domain=$rec[$this->ftp_log_id]['ftp_domain'];
			$this->ftp_username=$rec[$this->ftp_log_id]['ftp_username'];
			$this->ftp_password=$rec[$this->ftp_log_id]['ftp_password'];
			$this->ftp_port=$rec[$this->ftp_log_id]['ftp_port'];
			$this->ftp_directory=$_POST['1']['directory'];
			$this->fileUrl=$_POST['1']['fileUrl'];
			$this->connect();
			
			if($this->_ftp_instance){
				foreach($_POST as $key=>$value){
					$this->Delete($value['fileUrl']);
				}
			}
		}
		
		function Delete($path){
			if (is_dir($path) === true) {
				$files = array_diff(scandir($path), array('.', '..'));
				
				foreach ($files as $file) {
					$this->Delete(realpath($path) . '/' . $file);
				}
				
				return rmdir($path);
			}
			else if (is_file($path) === true) {
				chmod($path, 0750);
				
				return unlink($path);
			}
			
			return false;
		}
		
		public function createInLocal(){
			$this->ftp_directory=$_POST['ftp_directory'];
			$this->fileName=$_POST['fileName'];
			
			if($_POST['type']=='file'){
				file_put_contents($this->filterPath($this->ftp_directory . '/' . $this->fileName), "/* Untitled file */");
			}else{
				mkdir($this->filterPath($this->ftp_directory.'/'.$this->fileName));
			}
		}
		
		public function filterPath($e){
			return preg_replace('#/+#','/',$e);
		}
		
		public function editRemoteFile(){
			$this->load->view('editor/options');
			$rec=json_decode(get_option('ftps'),true);
			$this->ftp_log_id=$_POST['ftp_log_id'];
			$this->ftp_host=$rec[$this->ftp_log_id]['ftp_host'];
			$this->ftp_domain=$rec[$this->ftp_log_id]['ftp_domain'];
			$this->ftp_username=$rec[$this->ftp_log_id]['ftp_username'];
			$this->ftp_password=$rec[$this->ftp_log_id]['ftp_password'];
			$this->ftp_port=$rec[$this->ftp_log_id]['ftp_port'];
			$this->ftp_directory=$_POST['ftp_directory'];
			$this->fileUrl=$_POST['fileUrl'];
			$this->temp=$this->filterPath($this->getWorkpath() . '/temp/' . basename($this->fileUrl));
			
			$this->connect();
			
			if($this->_ftp_instance){
				$this->_ftp_status[]=array(
					'index'		=> 'other',
					'class'		=> 'warning',
					'message'	=> 'Copying '.$this->fileUrl .' file to '.$this->temp.'.'
				);
				$this->updateOptions();
				$uploaders=@ftp_get($this->_ftp_instance,$this->temp,$this->fileUrl,FTP_ASCII);
				
				if($uploaders){
					$this->_ftp_status[]=array(
						'index'		=> 'other',
						'class'		=> 'success',
						'message'	=> 'Successfully copied '.$this->temp.'.'
					);
					
					$this->updateOptions();
					
					$this->_ftp_status[]=array(
						'index'		=> 'other',
						'class'		=> 'warning',
						'message'	=> 'Registering into database Please wait...'
					);
					
					$this->updateOptions();
					$editing=json_decode(get_option('remote_edit'),true);
					$isImage=isImage($this->temp)?'-static':'-editor';
					
					$editing[$this->code($this->temp,true).$isImage]=array(
						'ftp_log_id'	=> $this->ftp_log_id,
						'ftp_directory'	=> $this->ftp_directory,
						'fileUrl'		=> $this->fileUrl,
						'tempUrl'		=> $this->temp,
					);
					
					$newOption=json_encode($editing,true);
					update_option('remote_edit',$newOption);
					
					echo '{"base":"'.base_url().'","ftp_log_id":"'.$this->ftp_log_id.'","data":{"id":"'.$this->code($this->temp,true).$isImage.'","file":"'.$this->temp.'","dataTitle":"'.basename($this->temp).'"}}';
				}else{
					$this->_ftp_status[]=array(
						'index'		=> 'other',
						'class'		=> 'error',
						'message'	=> 'Could not copy to '.$this->temp.'. Please check your internet connection and try again.'
					);
					
					$this->updateOptions();
				}
			}
		}
		
		public function saveRemoteFile(){
			$this->load->view('editor/options');
			
			$this->file_id=$_POST['file_id'];
			$makeArray=json_decode(get_option('remote_edit'),true);
			
			if(!isset($makeArray[$this->file_id])) return false;
			
			$rec=json_decode(get_option('ftps'),true);
			
			$this->_ftp_status[]=array(
				'index'		=> 'other',
				'class'		=> 'warning',
				'message'	=> 'Collecting information to update. Please wait...'
			);
			
			$this->updateOptions();
			$this->ftp_log_id=$makeArray[$this->file_id]['ftp_log_id'];
			$this->ftp_host=$rec[$this->ftp_log_id]['ftp_host'];
			$this->ftp_domain=$rec[$this->ftp_log_id]['ftp_domain'];
			$this->ftp_username=$rec[$this->ftp_log_id]['ftp_username'];
			$this->ftp_password=$rec[$this->ftp_log_id]['ftp_password'];
			$this->ftp_port=$rec[$this->ftp_log_id]['ftp_port'];
			$this->ftp_directory=$makeArray[$this->file_id]['ftp_directory'];
			$this->fileUrl=$makeArray[$this->file_id]['fileUrl'];
			$this->connect();
			
			if($this->_ftp_instance){
				$this->_ftp_status[]=array(
					'index'		=> 'other',
					'class'		=> 'warning',
					'message'	=> 'Updating '.$this->fileUrl .'.'
				);
				
				$this->updateOptions();
				$fp = fopen('php://temp', 'r+');
				fwrite($fp,$_POST['fileContent']);
				rewind($fp);
				$uploaders=@ftp_fput($this->_ftp_instance,$this->fileUrl, $fp, FTP_ASCII);
				
				if($uploaders){
					echo "success";
					
					$this->_ftp_status[]=array(
						'index'		=> 'other',
						'class'		=> 'success',
						'message'	=> 'Successfully updated '.$this->ftp_directory.'.'
					);
					
					$this->updateOptions();
					
					$this->_ftp_status[]=array(
						'index'		=> 'other',
						'class'		=> 'warning',
						'message'	=> 'Removing registry from database. Please wait...'
					);
					
					$this->updateOptions();
				}else{
					echo "success";
					
					$this->_ftp_status[]=array(
						'index'		=> 'other',
						'class'		=> 'error',
						'message'	=> 'Could not copy to '.$this->ftp_directory.'. Please check your internet connection and try again.'
					);
					
					$this->updateOptions();
				}
			}
		}
		
		function code($data,$enc=true){
			if ($enc == true) {
				$output = base64_encode (convert_uuencode ($data));
			} else {
				$output = convert_uudecode (base64_decode ($data));
			}
			
			$result = preg_replace("/[^a-zA-Z0-9]+/", "", $output);
			
			return strtolower($result);
		}
	}
?>