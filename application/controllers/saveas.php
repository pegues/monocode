<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once 'Controller.php';
	class saveas extends Controller {
		
		public function get_file_list(){
			$this->load->view('editor/options');
			$rec=json_decode(get_option('ftps'),true);
			$this->ftp_directory=$_POST['ftp_directory'];
			$this->workspace=$_POST['workspace'];
			$this->connect();
			
			if($this->_ftp_instance){
				$dir=($this->ftp_directory=='.')?'root/':$this->ftp_directory."/";
				
				$this->_ftp_status=array('index'=>'other','class'=>'warning','message'=>'Directory listing: '.$this->showingPath($dir).'. Please wait...');
				$this->updateOptions();
				$contents = ftp_nlist($this->_ftp_instance, $this->ftp_directory);
				$_files=array();
				
				foreach($contents as $files){
					//$fsize = ftp_size($this->_ftp_instance, $files);
					$fsize="N/A"; //($fsize != -1)?formatBytes($fsize):"N/A";
					//$lastmdate = ftp_mdtm($this->_ftp_instance, $files);
					$lastmdate="N/A"; //($lastmdate != -1)?date ("d, M Y", $lastmdate):"N/A";
					$_files[]=array('fileUrl'=>$files,'fileSize'=>$fsize,'lastModifiedDate'=>$lastmdate);
				}
				
				$this->_ftp_status=array('index'=>'other','class'=>'success','message'=>'Directory listed.');
				$this->updateOptions();
			}
			
			//foreach($contents as)
			$_files['details']=array('ftp_directory'=>$this->ftp_directory,'ftp_domain'=>$this->ftp_domain,'ftp_host'=>$this->ftp_host,'workspace'=>$this->workspace);
			echo json_encode($_files,true);
			//$old_status=get_option('$_POST['host']');
		}
		
		public function rename_local(){
			rename($_POST['from'],$_POST['to']);
		}
		
		public function download(){
			$name=rawurldecode($_GET['path']);
			htmlentities(file_get_contents($_GET['path']));
			$handle = fopen(basename($name), "w");
			fwrite($handle, file_get_contents($_GET['path']));
			fclose($handle);
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename='.basename($name));
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($name));
			readfile(basename($name));
			
			exit;
		}
		
		public function get_local_filelist(){
			$this->load->view('editor/options');
                        $ftp_directory = $_POST['ftp_directory'];
                        $workspace = $_POST['workspace'];
                        if ($ftp_directory != '') {
                            $ftp_directory .= '/';
                        }
			$path=$this->getWorkshop() . $workspace . '/' . $ftp_directory;
			$dires_files=array();
			
			if( file_exists($path)){
				$files = scandir($path);
				natcasesort($files);
				$dirindex=0;
				$fileindex=0;
				
				if( count($files) > 2 ) { /* The 2 accounts for . and .. */
					foreach($files as $file ) {
						$fsize=filesize($path.$file)?formatBytes(filesize($path.$file)):'N/A';
						$date=filemtime($path.$file)?date ("d, M Y", filemtime($path.$file)):'N/A';
						$dirindex++;
						
						if( file_exists($path.$file) && $file != '.' && $file != '..' && is_dir($path . $file) ) {
							$dires_files[]=array('fileUrl'=>$file,'fileSize'=>$fsize,'lastModifiedDate'=>$date);
						}
					}
					
					foreach($files as $file ) {
						$fsize=filesize($path.$file)?formatBytes(filesize($path.$file)):'N/A';
						$date=filemtime($path.$file)?date ("d, M Y", filemtime($path.$file)):'N/A';
						$fileindex++;
						
						if(file_exists($path.$file) && $file != '.' && $file != '..' && !is_dir($path.$file) ) {
							$dires_files[]=array('fileUrl'=>$file,'fileSize'=>$fsize,'lastModifiedDate'=>$date);
						}
					}
				}
			}
			$dires_files['details']=array('ftp_directory'=>$ftp_directory,'workspace'=>$workspace);
			echo json_encode($dires_files,true);
		}
		
		public function recursiveDelete($directory){
			if( !(@ftp_rmdir($this->_ftp_instance, $directory) || @ftp_delete($this->_ftp_instance, $directory))){
				$filelist = @ftp_nlist($this->_ftp_instance, $directory);
				
				foreach($filelist as $file){
					if(substr($file,-1)!='.'){
						$this->_ftp_status=array('index'=>'other','class'=>'warning','message'=>'Deleting file: '.$this->showingPath($file));
						$this->updateOptions();
						$this->recursiveDelete($file);
					}
				}
				$this->recursiveDelete($directory);
			}else{
				$this->_ftp_status=array('index'=>'other','class'=>'success','message'=>($this->showingPath($directory)).' has successfully been deleted.');
				$this->updateOptions();
			}
		}
		
		public function delete_files_local(){
			$this->load->view('editor/options');
			$rec=json_decode(get_option('ftps'),true);
			$this->ftp_directory=$_POST['1']['ftp_directory'];
			$this->fileUrl=$_POST['1']['fileUrl'];
			
			foreach($_POST as $key=>$value){
				$this->Delete($value['fileUrl']);
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
			$this->ftp_directory=$_POST['directory'];
			$this->fileName=$_POST['fileName'];
			
			if($_POST['type']=='file'){
				file_put_contents($this->filterPath($this->ftp_directory.'/'.$this->fileName), "/* Untitled file */");
			}else{
				mkdir($this->filterPath($this->ftp_directory.'/'.$this->fileName));
			}
		}
		
		public function filterPath($e){
			return preg_replace('#/+#','/',$e);
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
		
		public function showingPath($e){
			$this->load->view('editor/options');
			$workspacepath=$this->getWorkshop();
			$str=str_replace($workspacepath,'',$this->filterPath($e));
			$array=explode('/',$str);
			
			if(is_array($array) && sizeof($array)>0){
				$_ws=get_option('ws');
				$_ws=json_decode($_ws,true);
				$ws_name=(isset($_ws[$array[0]]) && isset($_ws[$array[0]]['ws_name']))?($_ws[$array[0]]['ws_name']):'';
				
				if($ws_name==''){
					return $str;
				}else{
					return str_replace($array[0],$ws_name,$str);
				}
			}else{
				return $str;
			}
		}
		
		public function getstatus(){
			$this->load->library('Logging');
			$this->load->view('editor/options');
			
			$date=date('d-M-Y');
			
			if(!is_dir('logs')){
				mkdir('logs');
			}
			
			if(!is_dir('logs/'.$date)){
				mkdir('logs/'.$date);
			}
			
			$file_name='logs/'.$date.'/'.get_user_id().'-'.$_POST['ftp_log_id'].'.txt';
			$array=array();
			
			if(file_exists($file_name)) {
				$cont=file($file_name);
				foreach ($cont as $line_num=>$line) {
					$array[]=$this->update_class($line);
				}
    		}
			
			if(sizeof($array)>10){
				$array=array_slice($array, -10, 10, true);
			}
			
			echo json_encode($array,true);
		}
		
		public function update_class($e){
			$all=htmlspecialchars($e);
			$class='';
			
			if(strpos($all,'[log-type=warning]')!==false) {
   			 	$class='warning';
				$all=str_replace('[log-type=warning]','', $all);
			}elseif(strpos($all,'[log-type=success]')!==false){
				$class='success';
				$all=str_replace('[log-type=success]','', $all);
			}
			elseif(strpos($all,'[log-type=error]')!==false){
				$class='error';
				$all=str_replace('[log-type=error]','', $all);
			}
			
			$matches = array();
			preg_match("/\[(.*)\]/",$all, $matches);
			
			if(isset($matches[1])){
				$all=str_replace('[' . $matches[1] . ']','<span style="color: #06f;">[' . $matches[1] . ']</span>', $all);
			}
			
			return array('class'=>$class,'message'=>$all);
		}
		
		public function updateOptions(){
			$this->load->library('Logging');
			$this->load->view('editor/options');
			$date=date('d-M-Y');
			
			if(!is_dir('logs')){
				@mkdir('logs');
			}
			
			if(!is_dir('logs/'.$date)){
				@mkdir('logs/'.$date);
			}
			
			$file_name='logs/'.$date.'/'.get_user_id().'-'.$this->ftp_log_id.'.txt';
			$new=false;
			
			if(!file_exists($file_name)){
				file_put_contents($file_name,'');
    		}
			
			$log = new Logging();
			$log->lfile($file_name);
			
			if($this->_ftp_status['index']=='login' || $this->_ftp_status['index']=='connect'){
				$file=file($file_name);
				
				if(count($file)<4 || (strpos($file[count($file)-1],'Disconnected')!==false || 
					strpos($file[count($file)-2],'Disconnected')!==false || 
					strpos($file[count($file)-3],'Disconnected')!==false || 
					strpos($file[count($file)-4],'Disconnected')!==false)){
					
					$log->lwrite($this->_ftp_status['message'],$this->_ftp_status['class']);
				}else{
					if($this->_ftp_status['index']=='connect'){
						//$log->lwrite('Please wait, Trying complete your request..','warning');
					}
				}
				
			}else{
				$log->lwrite($this->_ftp_status['message'],$this->_ftp_status['class']);
			}
			
			$log->lclose();
		}
		
		public function getOptions(){
			$this->load->view('editor/options');
			
			return get_option($this->ftp_log_id);
		}
	}
?>