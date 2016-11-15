<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once 'ftp.php';

class Sftp extends Ftp {

    public function connect() {
        $this->load->helper('phpseclib');
        phpseclib();
        $this->loadConnectionString();
        $_exOptions = $this->getOptions($this->ftp_log_id);
        $this->_ftp_status = json_decode($_exOptions);
        if ($this->ftp_host != '') {
            if (sizeof($this->_ftp_status) == 0) {
                $this->_ftp_status = array('index' => 'connect', 'class' => 'info', 'message' => 'Connecting to: ' . $this->ftp_host);
                $this->updateOptions();
            }

            $this->_ftp_instance = new Net_SFTP($this->ftp_host, $this->ftp_port);
            if ($this->_ftp_instance) {

                $this->_ftp_status = array('index' => 'connect', 'class' => 'success', 'message' => 'Connected: ' . $this->ftp_host);
                $this->updateOptions();

                $this->_ftp_status = array('index' => 'login', 'class' => 'info', 'message' => 'Logging in at: ' . $this->ftp_host);
                $this->updateOptions();

                $login_result = $this->_ftp_instance->login($this->ftp_username, $this->ftp_password);
                //ftp_pasv($this->_ftp_instance, !$this->ftp_mode);

                if (!$login_result) {
                    $this->_ftp_status = array('index' => 'login', 'class' => 'error', 'message' => 'Could not login to server. Please check your user details and try again.');
                    $this->updateOptions();

                    //die('error');
                } else {
                    if (sizeof($this->_ftp_status) == 3) {
                        $this->_ftp_status = array('index' => 'login', 'class' => 'success', 'message' => 'Successfully logged in to: ' . $this->ftp_host);
                        $this->updateOptions();
                    }

                    return $this->_ftp_instance;
                }
            } else {
                $this->_ftp_status = array('index' => 'login', 'class' => 'error', 'message' => 'Could not connect to ' . $this->ftp_host . '. Please check your connection details and try again.');
                $this->updateOptions();
            }
        }
    }

    public function get_file_list() {
        $this->dir = $_POST['dir'];
        $this->connect();

        if ($this->_ftp_instance) {
            $dir = ($this->dir == '.') ? 'root/' : $this->dir . "/";

            $this->_ftp_status = array('index' => 'other', 'class' => 'info', 'message' => 'Directory listing: ' . $this->showingPath($dir) . '. Please wait...');
            $this->updateOptions();
            $contents = $this->_ftp_instance->nlist($this->dir);
            $_files = array();

            if ($contents && count($contents) > 0) {
                foreach ($contents as $files) {
                    //$fsize = ftp_size($this->_ftp_instance, $files);
                    $fsize = "N/A"; //($fsize != -1) ? formatBytes($fsize) : "N/A";
                    //$lastmdate = ftp_mdtm($this->_ftp_instance, $files);
                    $lastmdate = "N/A"; //($lastmdate != -1) ? date ("d, M Y", $lastmdate) : "N/A";
                    $_files[] = array('fileUrl' => $files, 'fileSize' => $fsize, 'lastModifiedDate' => $lastmdate);
                }
            }

            $this->_ftp_status = array('index' => 'other', 'class' => 'success', 'message' => 'Directory listed');
            $this->updateOptions();
        }

        //foreach($contents as)
        $_files['details'] = array('dir' => $this->dir, 'ftp_log_id' => $this->ftp_log_id, 'ftp_domain' => $this->ftp_domain, 'ftp_host' => $this->ftp_host, 'connected' => $this->_ftp_instance ? 1 : 0);
        echo json_encode($_files, true);
        //$old_status=get_option('$_POST['host']');
    }

    public function ftp_rename() {
        $this->dir = $_POST['dir'];
        $this->to = $_POST['to'];
        $this->from = $_POST['from'];
        $this->connect();

        if ($this->_ftp_instance) {
            $dir = ($this->dir == '.') ? 'root/' : $this->dir;
            $this->_ftp_status = array('index' => 'other', 'class' => 'info', 'message' => 'Renaming file to: ' . $this->showingPath($this->to) . '. Please wait...');
            $this->updateOptions();
            $uploaders = ftp_rename($this->_ftp_instance, $this->dir . "/" . $this->from, $this->dir . "/" . $this->to);

            if ($uploaders) {
                $this->_ftp_status = array('index' => 'other', 'class' => 'success', 'message' => 'Successfully renamed.');
                $this->updateOptions();
            } else {
                $this->_ftp_status = array('index' => 'other', 'class' => 'error', 'message' => 'Could not rename. Try again later.');
                $this->updateOptions();
            }

            $this->_ftp_status = array('index' => 'other', 'class' => 'info', 'message' => 'Refreshing:' . $this->showingPath($dir) . '. Please wait...');
            $this->updateOptions();
            $contents = $this->_ftp_instance->nlist($this->dir);
            $_files = array();

            foreach ($contents as $files) {
                //$fsize = ftp_size($this->_ftp_instance, $files);
                $fsize = "N/A"; //($fsize != -1) ? formatBytes($fsize) : "N/A";
                //$lastmdate = ftp_mdtm($this->_ftp_instance, $files);
                $lastmdate = "N/A"; //($lastmdate != -1) ? date ("d, M Y", $lastmdate) : "N/A";
                $_files[] = array('fileUrl' => $files, 'fileSize' => $fsize, 'lastModifiedDate' => $lastmdate);
            }

            $this->_ftp_status = array('index' => 'other', 'class' => 'success', 'message' => 'Directory refreshed.');
            $this->updateOptions();
        }

        $_files['details'] = array('dir' => $this->dir, 'ftp_log_id' => $this->ftp_log_id, 'ftp_domain' => $this->ftp_domain, 'ftp_host' => $this->ftp_host);
        echo json_encode($_files, true);
    }

    public function ftp_to_local() {
        $this->dir = $_POST['dir'];
        $this->to = $this->filterPath($_POST['to']);
        $this->from = $this->filterPath($_POST['from']);
        $this->connect();

        if ($this->_ftp_instance) {
            $dir = ($this->to == '.') ? 'root/' : $this->to . "/";
            $this->_ftp_status = array('index' => 'other', 'class' => 'info', 'message' => 'Copying file to: ' . $this->showingPath($dir . basename($this->from)) . '. Please wait...');
            $this->updateOptions();

            if (@ftp_chdir($this->_ftp_instance, $this->from)) {
                $this->ftp_copy_to_local($this->from, $this->filterPath($this->to . '/' . basename($this->from)));
            } else {
                $uploaders = $this->_ftp_instance->get($this->from, $this->filterPath($this->to . '/' . basename($this->from)));

                if ($uploaders) {
                    $this->_ftp_status = array('index' => 'other', 'class' => 'success', 'message' => 'Successfully copied.');
                    $this->updateOptions();
                } else {
                    $this->_ftp_status = array('index' => 'other', 'class' => 'error', 'message' => 'Could not copy. Try again later.');
                    $this->updateOptions();
                }
            }

            $this->_ftp_status = array('index' => 'other', 'class' => 'info', 'message' => 'Refreshing: ' . $this->showingPath($dir) . '. Please wait...');
            $this->updateOptions();
            $contents = $this->_ftp_instance->nlist($this->dir);

            $_files = array();

            foreach ($contents as $files) {
                //$fsize = ftp_size($this->_ftp_instance, $files);
                $fsize = "N/A"; //($fsize != -1) ? formatBytes($fsize) : "N/A";
                //$lastmdate = ftp_mdtm($this->_ftp_instance, $files);
                $lastmdate = "N/A"; //($lastmdate != -1) ? date ("d, M Y", $lastmdate) : "N/A";
                $_files[] = array('fileUrl' => $files, 'fileSize' => $fsize, 'lastModifiedDate' => $lastmdate);
            }

            $this->_ftp_status = array('index' => 'other', 'class' => 'success', 'message' => 'Directory refreshed.');
            $this->updateOptions();
        }

        $_files['details'] = array('dir' => $this->dir, 'ftp_log_id' => $this->ftp_log_id, 'ftp_domain' => $this->ftp_domain, 'ftp_host' => $this->ftp_host);
        echo json_encode($_files, true);
    }

    public function ftp_create_new() {
        $this->dir = $_POST['dir'];
        $this->fileName = $_POST['name'];
        $this->connect();

        if ($this->_ftp_instance) {
            if ($_POST['type'] == 'file') {
                $fp = fopen('php://temp', 'r+');
                fwrite($fp, "/* untitled document */");
                rewind($fp);

                if (@ftp_fput($this->_ftp_instance, $this->dir . '/' . $this->fileName, $fp)) {
                    $this->_ftp_status = array('index' => 'other', 'class' => 'success', 'message' => 'File created: ' . $this->showingPath($this->fileName));
                    $this->updateOptions();
                } else {
                    $this->_ftp_status = array('index' => 'other', 'class' => 'error', 'message' => 'Error while creating file: ' . $this->showingPath($this->fileName) . '. Check the file name to see if it already exists.');
                    $this->updateOptions();
                }
            } else {
                if (@$this->_ftp_instance->mkdir($this->dir . '/' . $this->fileName)) {
                    $this->_ftp_status = array('index' => 'other', 'class' => 'success', 'message' => 'Successfully created directory: ' . $this->showingPath($this->fileName));
                    $this->updateOptions();
                } else {
                    $this->_ftp_status = array('index' => 'other', 'class' => 'error', 'message' => 'Error while creating directory: ' . $this->showingPath($this->fileName) . '. Check the directory name to see if it already exists.');
                    $this->updateOptions();
                }
            }
        }

        $contents = $this->_ftp_instance->nlist($this->dir);
        $_files = array();

        foreach ($contents as $files) {
            //$fsize = ftp_size($this->_ftp_instance, $files);
            $fsize = "N/A"; //($fsize != -1) ? formatBytes($fsize) : "N/A";
            //$lastmdate = ftp_mdtm($this->_ftp_instance, $files);
            $lastmdate = "N/A"; //($lastmdate != -1) ? date ("d, M Y", $lastmdate) : "N/A";
            $_files[] = array('fileUrl' => $files, 'fileSize' => $fsize, 'lastModifiedDate' => $lastmdate);
        }

        $this->_ftp_status = array('index' => 'other', 'class' => 'success', 'message' => 'Directory refreshed.');
        $this->updateOptions();
        $_files['details'] = array('dir' => $this->dir, 'ftp_log_id' => $this->ftp_log_id);
        echo json_encode($_files, true);
    }

    public function recursiveDelete($directory) {
        if ($this->_ftp_instance->delete($directory) || $this->_ftp_instance->delete($directory, true)) {
            $this->_ftp_status = array('index' => 'other', 'class' => 'success', 'message' => ($this->showingPath($directory)) . ' has been successfully deleted.');
            $this->updateOptions();
        }
    }

    public function delete_ftp_file() {
        $files = $this->input->post('files');
        $this->dir = $files['1']['dir'];
        $this->fileUrl = $files['1']['fileUrl'];
        $this->connect();

        if ($this->_ftp_instance) {
            $dir = ($this->dir == '.') ? 'root/' : $this->dir . "/";

            foreach ($files as $key => $value) {
                $this->recursiveDelete($this->dir . '/' . $value['fileUrl']);
            }

            $this->_ftp_status = array('index' => 'other', 'class' => 'info', 'message' => 'Refreshing: ' . $this->showingPath($dir) . '. Please wait...');
            $this->updateOptions();
            $contents = $this->_ftp_instance->nlist($this->dir);

            $_files = array();

            foreach ($contents as $files) {
                //$fsize = ftp_size($this->_ftp_instance, $files);
                $fsize = "N/A"; //($fsize != -1)?formatBytes($fsize):"N/A";
                //$lastmdate = ftp_mdtm($this->_ftp_instance, $files);
                $lastmdate = "N/A"; //($lastmdate != -1)?date ("d, M Y", $lastmdate):"N/A";
                $_files[] = array('fileUrl' => $files, 'fileSize' => $fsize, 'lastModifiedDate' => $lastmdate);
            }

            $this->_ftp_status = array('index' => 'other', 'class' => 'success', 'message' => 'Directory refreshed.');
            $this->updateOptions();
        }

        $_files['details'] = array('dir' => $this->dir, 'ftp_log_id' => $this->ftp_log_id, 'ftp_domain' => $this->ftp_domain, 'ftp_host' => $this->ftp_host);
        echo json_encode($_files, true);
    }

    public function past() {
        $this->load->view('editor/options');
        $rec = json_decode(get_option('ftps'), true);
        $files = $this->input->post('files');
        if (is_array($files)) {
            $this->past_directory = $files[1]['past_directory'];
            $this->copy_directory = $files[1]['copy_directory'];
            $this->from = $files[1]['from'];
            $this->to = $files[1]['to'];

            $this->connect();

            foreach ($files as $key => $value) {
                $past_directory = $value['past_directory'];
                $copy_directory = $value['copy_directory'];
                if ($value['from'] == 'local') {
                    $copy_directory = $this->getWorkshop() . $copy_directory . ($copy_directory == '' ? '' : '/');
                } else {
                    $past_directory = $this->getWorkshop() . $past_directory . ($past_directory == '' ? '' : '/');
                }
                $past_to = $this->filterPath($past_directory . '/' . basename($value['fileUrl']));
                $copy_from = $this->filterPath($copy_directory . '/' . basename($value['fileUrl']));
                //var_dump($past_to, $copy_from);exit;

                if ($value['from'] == 'local' && ($value['to'] == 'remote' || $value['to'] == 'server')) {
                    $this->_ftp_status = array(
                        'index' => 'other',
                        'class' => 'info',
                        'message' => 'Copying file to: ' . $this->showingPath($past_to)
                    );

                    $this->updateOptions();

                    if ($this->isStorageLocal()) {
                        if (!is_dir($copy_from)) {
                            $uploaders = $this->_ftp_instance->put($past_to, $copy_from);
                        }
                    } else {
                        $file = $this->getAWSFileModel()->getByPath($copy_from);
                        if ($file->type != 'dir') {
                            $path = $this->getTempDir() . UUID::v4() . '.' . end(explode('.', $copy_from));
                            $path = $this->getAWSServer()->download2LocalFile($copy_from, $path);
                            $uploaders = $this->_ftp_instance->put($past_to, $path);
                            unlink($path);
                        }
                    }

                    if (isset($uploaders) && $uploaders) {
                        $this->_ftp_status = array(
                            'index' => 'other',
                            'class' => 'success',
                            'message' => 'Successfully copied.'
                        );

                        $this->updateOptions();

                        if (isset($value['file_action']) && $value['file_action'] == 'cut') {
                            $this->Delete($copy_from);
                        }
                    } else {
                        if ($this->isStorageLocal()) {
                            if (is_dir($copy_from)) {
                                $this->ftp_copy_to_ftp($copy_from, $past_to);
                            } else {
                                $this->_ftp_status = array(
                                    'index' => 'other',
                                    'class' => 'error',
                                    'message' => 'Could not copy [' . $this->showingPath($past_to) . ']. Please note that you cannot copy/paste directory(folder).'
                                );

                                $this->updateOptions();
                            }
                        } else {
                            $file = $this->getAWSFileModel()->getByPath($copy_from);

                            if ($file->type == 'dir') {
                                $this->ftp_copy_to_ftp_aws($copy_from, $past_to);
                            } else {
                                $this->_ftp_status = array(
                                    'index' => 'other',
                                    'class' => 'error',
                                    'message' => 'Could not copy [' . $this->showingPath($past_to) . ']. Please note that you cannot copy/paste directory(folder).'
                                );

                                $this->updateOptions();
                            }
                        }
                    }
                }

                if (($value['from'] == 'remote' || $value['from'] == 'server') && $value['to'] == 'local') {
                    $this->_ftp_status = array(
                        'index' => 'other',
                        'class' => 'info',
                        'message' => 'Copying file to: ' . $this->showingPath(str_replace($this->getWorkshop(), '', $past_to))
                    );

                    $this->updateOptions();

                    if ($this->isStorageLocal()) {
                        $uploaders = $this->_ftp_instance->get($copy_from, $past_to);
                    } else {
                        $path = $this->getTempDir() . UUID::v4() . '.' . end(explode('.', $copy_from));
                        if ($uploaders = $this->_ftp_instance->get($copy_from, $path)) {
                            if ($url = $this->getAWSServer()->uploadFromFile($past_to, $path)) {
                                $size = filesize($path);
                                if (!$this->getAWSFileModel()->updateFile($past_to, $url, $size)) {
                                    $name = basename($value['fileUrl']);
                                    $this->getAWSFileModel()->createFile($name, rtrim($past_to, $name), $url, $this->session->userdata('user_id'), 'file', $size);
                                }
                                unlink($path);
                            }
                        }
                    }

                    if ($uploaders) {
                        $this->_ftp_status = array(
                            'index' => 'other',
                            'class' => 'success',
                            'message' => $this->showingPath($past_to) . ' has been successfully copied.'
                        );

                        $this->updateOptions();

                        if (isset($value['file_action']) && $value['file_action'] == 'cut') {
                            $this->recursiveDelete($copy_from);
                        }
                    } else {
                        if (@ftp_chdir($this->_ftp_instance, $copy_from)) {
                            $this->ftp_copy_to_local($copy_from, $past_to);
                        } else {
                            $this->_ftp_status = array(
                                'index' => 'other',
                                'class' => 'error',
                                'message' => 'Could not copy to ' . $this->showingPath($past_to) . '. If you are trying to copy a directory, directory copying is not supported in this version.'
                            );

                            $this->updateOptions();
                        }
                    }
                }
            }
        }

        $dires = '';

        if ($this->_ftp_instance) {

            if ($this->to == 'local' && ($this->from = 'server' || $this->from = 'remote')) {
                $dires = $this->filterPath($this->copy_directory);
            } else if ($this->from == 'local' && ($this->to == 'server' || $this->to == 'remote')) {
                $dires = $this->filterPath($this->past_directory);
            }

            if ($dires != '') {
                $this->_ftp_status = array('index' => 'other', 'class' => 'info', 'message' => 'Refreshing: ' . $this->showingPath($dires) . '. Please wait...');
                $this->updateOptions();
                $contents = $this->_ftp_instance->nlist($dires);
                $_files = array();

                foreach ($contents as $files) {
                    //$fsize = ftp_size($this->_ftp_instance, $files);
                    $fsize = "N/A"; //($fsize != -1)?formatBytes($fsize):"N/A";
                    //$lastmdate = ftp_mdtm($this->_ftp_instance, $files);
                    $lastmdate = "N/A"; //($lastmdate != -1)?date ("d, M Y", $lastmdate):"N/A";
                    $_files[] = array('fileUrl' => $files, 'fileSize' => $fsize, 'lastModifiedDate' => $lastmdate);
                }

                $this->_ftp_status = array('index' => 'other', 'class' => 'success', 'message' => 'Directory refreshed.');
                $this->updateOptions();
            }
        }

        $_files['details'] = array('dir' => $this->filterPath($dires), 'ftp_log_id' => $this->ftp_log_id, 'ftp_domain' => $this->ftp_domain, 'ftp_host' => $this->ftp_host);
        echo json_encode($_files, true);
    }

    public function delete_files_local() {
        $this->dir = $_POST['1']['directory'];
        $this->fileUrl = $_POST['1']['fileUrl'];
        $this->connect();

        if ($this->_ftp_instance) {
            foreach ($_POST as $key => $value) {
                $this->Delete($value['fileUrl']);
            }
        }
    }

    function Delete($path) {
        if (is_dir($path) === true) {
            $files = array_diff(scandir($path), array('.', '..'));

            foreach ($files as $file) {
                $this->Delete(realpath($path) . '/' . $file);
            }

            return rmdir($path);
        } else if (is_file($path) === true) {
            chmod($path, 0750);

            return unlink($path);
        }

        return false;
    }

    public function editRemoteFile() {
        $this->dir = $_POST['dir'];
        $this->fileUrl = $_POST['fileUrl'];

        $this->temp = $this->filterPath($this->getTempDir() . basename($this->fileUrl));
        $this->connect();

        if ($this->_ftp_instance) {
            $this->_ftp_status = array(
                'index' => 'other',
                'class' => 'info',
                'message' => 'Downloading ' . $this->showingPath($this->fileUrl) . ' file into temporary directory...'
            );

            $this->updateOptions();
            $uploaders = $this->_ftp_instance->get($this->dir . '/' . $this->fileUrl, $this->temp);

            if ($uploaders) {
                $this->_ftp_status = array(
                    'index' => 'other',
                    'class' => 'success',
                    'message' => 'Successfully downloaded: ' . basename($this->temp)
                );

                $this->updateOptions();

//					$this->_ftp_status=array(
//						'index'		=> 'other',
//						'class'		=> 'info',
//						'message'	=> 'Registering into database. Please wait...'
//					);
//					$this->updateOptions();
                $editing = json_decode(get_option('remote_edit'), true);

                $id = $this->code($this->temp, true);
                $editing[$id] = array(
                    'ftp_log_id' => $this->ftp_log_id,
                    'dir' => $this->dir,
                    'fileUrl' => $this->fileUrl,
                    'tempUrl' => $this->temp,
                );

                $newOption = json_encode($editing, true);
                update_option('remote_edit', $newOption);

                $this->ajaxResponse(array('ftp_log_id' => $this->ftp_log_id, 'file' => array('id' => $id, 'url' => $this->temp, 'name' => basename($this->temp))));
            } else {
                $this->_ftp_status = array(
                    'index' => 'other',
                    'class' => 'error',
                    'message' => 'Could not copy to ' . $this->showingPath($this->temp) . '. Check your internet connection and try again.'
                );

                $this->updateOptions();
            }
        }
    }

    public function refreshRemoteFile() {
        $this->load->view('editor/options');
        $this->file_id = $_POST['file_id'];
        $makeArray = json_decode(get_option('remote_edit'), true);

        if (!isset($makeArray[$this->file_id])) {
            $this->addErrorMessage('No file exists.');
            $this->ajaxResponse();
        }
        $_POST['ftp_log_id'] = $makeArray[$this->file_id]['ftp_log_id'];

        $rec = json_decode(get_option('ftps'), true);

        $this->dir = $makeArray[$this->file_id]['dir'];
        $this->fileUrl = $makeArray[$this->file_id]['fileUrl'];
        $this->temp = $this->filterPath($makeArray[$this->file_id]['tempUrl']);
        $this->connect();

        if ($this->_ftp_instance) {
            $this->_ftp_status = array(
                'index' => 'other',
                'class' => 'info',
                'message' => 'Downloading ' . $this->showingPath($this->fileUrl) . ' file into temporary directory...'
            );

            $this->updateOptions();
            $uploaders = $this->_ftp_instance->get($this->dir . '/' . $this->fileUrl, $this->temp);

            if ($uploaders) {
                $this->_ftp_status = array(
                    'index' => 'other',
                    'class' => 'success',
                    'message' => 'Successfully downloaded: ' . basename($this->temp)
                );

                $this->updateOptions();

                $id = $this->code($this->temp, true);

                $this->ajaxResponse(array('ftp_log_id' => $this->ftp_log_id, 'file' => array('id' => $id, 'url' => $this->temp, 'name' => basename($this->temp))));
            } else {
                $this->_ftp_status = array(
                    'index' => 'other',
                    'class' => 'error',
                    'message' => 'Could not copy to ' . $this->showingPath($this->temp) . '. Check your internet connection and try again.'
                );

                $this->updateOptions();
            }
        }
    }

    public function saveRemoteFile() {
        $this->load->view('editor/options');
        $this->file_id = $_POST['file_id'];
        $makeArray = json_decode(get_option('remote_edit'), true);

        if (!isset($makeArray[$this->file_id])) {
            $this->addErrorMessage('No file exists.');
            $this->ajaxResponse();
        }
        $_POST['ftp_log_id'] = $makeArray[$this->file_id]['ftp_log_id'];

        $rec = json_decode(get_option('ftps'), true);

        $this->dir = $makeArray[$this->file_id]['dir'];
        $this->fileUrl = $makeArray[$this->file_id]['fileUrl'];

        $this->_ftp_status = array(
            'index' => 'other',
            'class' => 'info',
            'message' => 'Collecting information to update. Please wait...'
        );

        $this->updateOptions();
        $this->connect();

        if ($this->_ftp_instance) {
            $this->_ftp_status = array(
                'index' => 'other',
                'class' => 'info',
                'message' => 'Updating ' . $this->showingPath($this->fileUrl)
            );

            $this->updateOptions();
            $fp = fopen('php://temp', 'r+');
            fwrite($fp, $_POST['fileContent']);
            rewind($fp);
            $uploaders = $this->_ftp_instance->put($this->dir . '/' . $this->fileUrl, $_POST['fileContent']);

            if ($uploaders) {
                $this->_ftp_status = array(
                    'index' => 'other',
                    'class' => 'success',
                    'message' => 'Successfully updated ' . $this->showingPath($this->dir)
                );

                $this->updateOptions();

//						$this->_ftp_status=array(
//							'index'		=> 'other',
//							'class'		=> 'info',
//							'message'	=> 'Removing registry from database. Please wait...'
//						);
//						
//						$this->updateOptions();
            } else {

                $this->_ftp_status = array(
                    'index' => 'other',
                    'class' => 'error',
                    'message' => 'Could not copy to ' . $this->showingPath($this->dir) . '. Check your internet connection and try again.'
                );

                $this->updateOptions();
            }
        } else {
            $this->addErrorMessage('No ftp connection.');
        }
        $this->ajaxResponse();
    }

    public function deleteRemoteTempFile() {
        $id = $this->input->post('id');
        if ($editingFiles = isset($this->__options['remote_edit']) ? json_decode($this->__options['remote_edit'], true) : null) {
            if (isset($editingFiles[$id])) {
                $this->__delete_file_or_dir_local($editingFiles[$id]['tempUrl']);
                unset($editingFiles[$id]);
                $this->__save_option('remote_edit', json_encode($editingFiles));
            }
        }
        $this->ajaxResponse();
    }

    function ftp_copy_to_ftp($src_dir, $dst_dir) {
        if ($this->_ftp_instance->chdir($dst_dir)) {
            $this->_ftp_status = array(
                'index' => 'other',
                'class' => 'warning',
                'message' => 'Directory already exists on the remote server. Please delete it and try again.'
            );

            $this->updateOptions();
        } else {
            $d = dir($src_dir);
            $this->_ftp_status = array(
                'index' => 'other',
                'class' => 'info',
                'message' => 'Creating directory: ' . $this->showingPath($dst_dir)
            );

            $this->updateOptions();
            $this->_ftp_instance->mkdir($dst_dir);

            $this->_ftp_status = array(
                'index' => 'other',
                'class' => 'success',
                'message' => 'Directory created: ' . $this->showingPath($dst_dir)
            );

            while ($file = $d->read()) { // do this for each file in the directory
                if ($file != "." && $file != "..") { // to prevent an infinite loop
                    if (is_dir($src_dir . "/" . $file)) { // do the following if it is a directory
                        $this->ftp_copy_to_ftp($src_dir . "/" . $file, $dst_dir . "/" . $file); // recursive part
                    } else {
                        $this->_ftp_status = array(
                            'index' => 'other',
                            'class' => 'info',
                            'message' => 'Copying file to: ' . $this->showingPath($dst_dir . "/" . $file) . '. Please wait...'
                        );

                        $this->updateOptions();
                        $upload = $this->_ftp_instance->put($dst_dir . "/" . $file, $src_dir . "/" . $file);

                        if ($upload) {
                            $this->_ftp_status = array(
                                'index' => 'other',
                                'class' => 'success',
                                'message' => 'Successfully copied: ' . $this->showingPath($dst_dir . "/" . $file)
                            );

                            $this->updateOptions();
                        } else {
                            $this->_ftp_status = array(
                                'index' => 'other',
                                'class' => 'error',
                                'message' => 'Could not copy file: ' . $this->showingPath($dst_dir . "/" . $file)
                            );

                            $this->updateOptions();
                        }
                    }
                }

                ob_flush();
                sleep(1);
            }

            $d->close();
        }
    }

    function ftp_copy_to_ftp_aws($src_dir, $dst_dir) {
        if (@ftp_chdir($this->_ftp_instance, $dst_dir)) {
            $this->_ftp_status = array(
                'index' => 'other',
                'class' => 'warning',
                'message' => 'Directory already exists on the remote server. Please delete it and try again.'
            );

            $this->updateOptions();
        } else {
            $this->_ftp_status = array(
                'index' => 'other',
                'class' => 'info',
                'message' => 'Creating directory: ' . $this->showingPath($dst_dir)
            );

            $this->updateOptions();
            $this->_ftp_instance->mkdir($dst_dir);

            $this->_ftp_status = array(
                'index' => 'other',
                'class' => 'success',
                'message' => 'Directory created: ' . $this->showingPath($dst_dir)
            );

            $src_dir = rtrim($src_dir, '/') . '/';
            $files = $this->getAWSFileList($src_dir, true);

            if (sizeof($files) > 0) {
                foreach ($files as $f) {
                    $file = $f->name;
                    if ($f->type == 'dir') { // do the following if it is a directory
                        $this->ftp_copy_to_ftp_aws($src_dir . $file, $dst_dir . "/" . $file); // recursive part
                    } else {
                        $this->_ftp_status = array(
                            'index' => 'other',
                            'class' => 'info',
                            'message' => 'Copying file to: ' . $this->showingPath($dst_dir . "/" . $file) . '. Please wait...'
                        );

                        $this->updateOptions();
                        $path = $this->getTempDir() . UUID::v4() . '.' . end(explode('.', $file));
                        $path = $this->getAWSServer()->download2LocalFile($src_dir . $file, $path);
                        $upload = $this->_ftp_instance->put($dst_dir . "/" . $file, $path);
                        unlink($path);

                        if ($upload) {
                            $this->_ftp_status = array(
                                'index' => 'other',
                                'class' => 'success',
                                'message' => 'Successfully copied: ' . $this->showingPath($dst_dir . "/" . $file)
                            );

                            $this->updateOptions();
                        } else {
                            $this->_ftp_status = array(
                                'index' => 'other',
                                'class' => 'error',
                                'message' => 'Could not copy file: ' . $this->showingPath($dst_dir . "/" . $file)
                            );

                            $this->updateOptions();
                        }
                    }

                    ob_flush();
                    sleep(1);
                }
            }
        }
    }

    function ftp_copy_to_local($src_dir, $dst_dir) {
        $dst_dir = rtrim($dst_dir, '/') . '/';

        if ($this->isStorageLocal()) {
            if (!is_dir($dst_dir)) {
                mkdir($dst_dir);
            }
        } else {
            $file = $this->getAWSFileModel()->getByPath($dst_dir);

            if (!$file || $file->type != 'dir') {
                if (substr_count($dst_dir, '/') > 2) {
                    $path = rtrim($dst_dir, '/');
                    $name = substr($path, strrpos($path, '/', -1) + 1);
                    $this->getAWSFileModel()->createDir($name, rtrim($path, $name), $this->session->userdata('user_id'));
                }
            }
        }

        $this->connect();

        $this->_ftp_status = array(
            'index' => 'other',
            'class' => 'info',
            'message' => 'Creating directory: ' . $this->showingPath($dst_dir)
        );

        $this->updateOptions();

        $this->_ftp_status = array(
            'index' => 'other',
            'class' => 'success',
            'message' => 'Directory created: ' . $this->showingPath($dst_dir)
        );

        $this->updateOptions();
        $_data = $this->_ftp_instance->nlist($src_dir);

        foreach ($_data as $file) { // do this for each file in the directory
            if ($file != "." && $file != ".." && substr($file, -1) != '.' && substr($file, -2) != '.' && substr($file, 1) != '.') { // to prevent an infinite loop
                // echo $file; die();
                $names = basename($file);

                if (@ftp_chdir($this->_ftp_instance, $src_dir . "/" . $names)) { // do the following if it is a directory
                    $this->ftp_copy_to_local($src_dir . "/" . $names, $dst_dir . $names); // recursive part
                } else {
                    $this->_ftp_status = array(
                        'index' => 'other',
                        'class' => 'info',
                        'message' => 'Copying file to: ' . $this->showingPath($dst_dir . $names) . '. Please wait...'
                    );

                    $this->updateOptions();

                    if ($this->isStorageLocal()) {
                        $upload = $this->_ftp_instance->get($src_dir . "/" . $names, $dst_dir . $names);
                    } else {
                        $path = $this->getTempDir() . UUID::v4() . '.' . end(explode('.', $names));

                        if ($upload = $this->_ftp_instance->get($src_dir . "/" . $names, $path)) {
                            if ($url = $this->getAWSServer()->uploadFromFile($dst_dir . $names, $path)) {
                                $size = filesize($path);
                                if (!$this->getAWSFileModel()->updateFile($dst_dir . $names, $url, $size)) {
                                    $this->getAWSFileModel()->createFile($names, $dst_dir, $url, $this->session->userdata('user_id'), 'file', $size);
                                }
                                unlink($path);
                            }
                        }
                    }

                    if ($upload) {
                        $this->_ftp_status = array(
                            'index' => 'other',
                            'class' => 'success',
                            'message' => 'Successfully copied: ' . $this->showingPath($dst_dir . $names)
                        );
                        $this->updateOptions();
                    } else {
                        $this->_ftp_status = array(
                            'index' => 'other',
                            'class' => 'error',
                            'message' => 'Could not copy file: ' . $this->showingPath($dst_dir . $names)
                        );

                        $this->updateOptions();
                    }
                }
            }
        }
    }

    function get_local_ftp_file_list() {
        $this->load->view('editor/options');
        $root = $_POST['root'];
        $dires = $_POST['dires'];
        $dires_files = '';

        if (file_exists($root . $dires)) {
            $files = scandir($root . $dires);
            natcasesort($files);
            $dirindex = 0;
            $fileindex = 0;
            $ex_ = get_option('leftDire');
            $ex_ = explode('-', $ex_);
            $arys = array_filter($ex_);

            if (count($files) > 2) { /* The 2 accounts for . and .. */
                $dires_files.="<ul class=\"jqueryFileTree parent-uls\">";

                foreach ($files as $file) {
                    $dirindex++;

                    if (file_exists($root . $dires . $file) && $file != '.' && $file != '..' && is_dir($root . $dires . $file)) {
                        $der_ = '';

                        if (in_array(code(htmlentities($dires . $file)), $arys)) {
                            $der_ = get_dires($root, $dires . $file . '/');
                        }

                        $cels = ($der_ != '') ? 'expanded' : 'collapsed';
                        $dires_files.="<li class=\"directory $cels\"><a href=\"#\" data-dir='" . $dirindex . "' data-file-id='" . code(htmlentities($dires . $file)) . "' rel=\"" . rawurlencode($dires . $file) . "/\">" . htmlentities($file) . "</a>";
                        $dires_files.=$der_;
                        $dires_files.="</li>";
                    }
                }

                // All files
                foreach ($files as $file) {
                    $fileindex++;

                    if (file_exists($root . $dires . $file) && $file != '.' && $file != '..' && !is_dir($root . $dires . $file)) {
                        $ext = preg_replace('/^.*\./', '', $file);
                        $dires_files.="<li class=\"file ext_$ext\"><a href=\"#\" data-url='" . realpath($dires . $file) . "'  data-title='" . htmlentities($file) . "' data-file-id='" . code(htmlentities($dires . $file)) . '-' . (isImage($dires . $file) ? 'static' : 'editor') . "' rel=\"" . rawurlencode($dires . $file) . "\">" . htmlentities($file) . " </a></li>";
                    }
                }

                $dires_files.="</ul>";
            }
        }

        echo $dires_files;
    }

    public function showingPath($e) {
        $this->load->view('editor/options');
        $str = $this->filterPath($e);
        $array = explode('/', $str);

        if (is_array($array) && sizeof($array) > 0) {
            $_ws = get_option('ws');
            $_ws = json_decode($_ws, true);
            $ws_name = (isset($_ws[$array[0]]) && isset($_ws[$array[0]]['ws_name'])) ? ($_ws[$array[0]]['ws_name']) : '';

            if ($ws_name == '') {
                return $str;
            } else {
                return str_replace($array[0], $ws_name, $str);
            }
        } else {
            return $str;
        }
    }

}

?>