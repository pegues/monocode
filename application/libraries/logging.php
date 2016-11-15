<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class Logging {
		private $log_file, $fp;
		public function lfile($path) {
			$this->log_file = $path;
		}
		// write message to the log file
		public function lwrite($message,$script_name='') {
			
			// if file pointer doesn't exist, then open log file
			if (!is_resource($this->fp)) {
				$this->lopen();
			}
			// define script name
			if($script_name==''){
			$script_name = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
			}
			// define current time and suppress E_WARNING if using the system TZ settings
			// (don't forget to set the INI setting date.timezone)
			$time = @date('[Y-m-d H:i:s]');
			$towrite="$time [log-type=$script_name] $message";
			
			fwrite($this->fp,$towrite. PHP_EOL);
		}
		// close log file (it's always a good idea to close a file when you're done with it)
		public function lclose() {
			@fclose($this->fp);
		}
		// open log file (private method)
		private function lopen() {
			// in case of Windows set default log file
			if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
				$log_file_default = 'c:/php/logfile.txt';
			}
			// set default log file for Linux and other systems
			else {
				$log_file_default = '/tmp/logfile.txt';
			}
			// define log file from lfile method or use previously set default
			$lfile = $this->log_file ? $this->log_file : $log_file_default;
			$this->fp = fopen($lfile, 'a') or exit("Can't open $lfile!");
		}
	}
    ?>