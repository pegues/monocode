<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once 'EditorController.php';

class File extends EditorController {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        return $this->get();
    }

    public function get() {
        if ($this->session->userdata('loggedin') != 'true') {
            exit;
        }
        $path = $this->input->get('path');
        if ($path == null || $path == '') {
            echo 'invalid file';
            return;
        }

        $tempFile = false;
        if (strripos($path, $this->getTempDir()) > -1) {
            $tempFile = true;
        } else {
            $path = $this->getWorkshop() . $path;
        }
        if ($this->isStorageAWS() && !$tempFile) {
            $content = $this->getAWSServer()->download($path);
            if ($content === false) {
                echo 'Error occurred while downloading file.';
            } else {
                echo htmlentities($content);
            }
        } else {
            if (!file_exists($path)) {
                echo 'The file does not exist';
                return;
            }
            echo htmlentities(file_get_contents($path));
        }
    }

    public function getFromContext() {
        $context = $this->input->get('context');
        if ($context == null || $context == '') {
            echo 'invalid context.';
            return;
        }
        $path = realpath(APPPATH) . '/..' . CONTEXT_TEMPLATE_DIR . $context;
        if (!file_exists($path)) {
            echo '';
            return;
        }

        echo htmlentities(file_get_contents($path));
    }

    public function create() {
        if ($this->session->userdata('loggedin') != 'true') {
            exit;
        }
        $user_id = $this->session->userdata('user_id');
        $path = $this->input->post('fileURL');
        $name = $this->input->post('name');
        $type = $this->input->post('type');
        if ($type == null || $type == '') {
            $type = 'file';
        }

        if ($type == 'dir') {
            return $this->__create_dir();
        }
        $ext = $this->input->post('ext');
        if ($ext == null || $ext == '') {
            $ext = 'php';
        }

        $txt = "/* New File */";
        $full_path = $this->getWorkshop() . $path;

        if ($this->checkFileCount($this->fetchWorkspace($path), 1)) {
            if ($this->isStorageAWS()) {
                $name .= '.' . $ext;
                $name = $this->getAWSFileModel()->getNewFileName($name, $full_path, $user_id);
                $url = $this->getAWSServer()->upload($full_path . $name, $txt);
                if ($url === false) {
                    echo 'Error occurred while creating file.';
                    return;
                } else {
                    $size = strlen($txt);
                    $this->getAWSFileModel()->createFile($name, $full_path, $url, $user_id, $type, $size);
                }
            } else {
                $createName = $name;
                if (file_exists($full_path . $name . '.php')) {
                    for ($i = 0; $i <= 50; $i++) {
                        $createName = $name . "-" . $i;

                        if (!file_exists($full_path . $createName . '.php')) {
                            $new = fopen($full_path . $createName . '.php', "w");
                            fwrite($new, $txt);
                            fclose($new);
                            break;
                        }
                    }
                } else {
                    $new = fopen($full_path . $createName . '.php', "w");
                    $txt = "/* New File */";
                    fwrite($new, $txt);
                    fclose($new);
                }
                $name = $createName . '.php';
            }
        }

        $url = $path . $name;
        $id = $this->__filename2id(htmlentities($url));
        $this->ajaxResponse(array('name' => $name, 'id' => $id, 'url' => 'url', 'type' => 'file'));
    }

    public function __create_dir() {
        if ($this->session->userdata('loggedin') != 'true') {
            exit;
        }
        $user_id = $this->session->userdata('user_id');
        $dir = $this->input->post('dir');
        $name = $this->input->post('name');

        $path = $this->getWorkshop() . $dir;
        if ($this->isStorageAWS()) {
            $name = $this->getAWSFileModel()->getNewFileName($name, $path, $user_id);
            $this->getAWSFileModel()->createDir($name, $path, $user_id);
        } else {
            $createName = $name;
            if (file_exists($path . $name)) {
                for ($i = 0; $i <= 50; $i++) {
                    $createName = $name . "-" . $i;

                    if (!file_exists($path . $createName)) {
                        $umask = umask(0);
                        mkdir($path . $createName, 0777);
                        umask($umask);
                        break;
                    }
                }
            } else {
                $umask = umask(0);
                $ret = mkdir($path . $createName, 0777);
                umask($umask);
            }
            $name = $createName;
        }
        $url = $dir . $name . '/';
        $id = $this->__filename2id(htmlentities($url));
        $this->ajaxResponse(array('name' => $name, 'id' => $id, 'url' => $url, 'type' => 'dir', 'dir' => $dir));
    }

    public function save() {
        if ($this->session->userdata('loggedin') != 'true') {
            exit;
        }

        $url = $this->input->post('url');
        $content = $this->input->post('content');
        $size = strlen($content);

        if (strripos($url, $this->getTempDir()) > -1) { //is temp file
            file_put_contents($url, $content);
            $this->ajaxResponse();
        }

        if ($this->checkDiskUsage($size)) {
            $file = $this->getWorkshop() . $url;
            if ($this->isStorageAWS()) {
                $url = $this->getAWSServer()->upload($file, $content);
                if ($url === false) {
                    $this->addErrorMessage('Error occurred while saving file.');
                } else {
                    $this->getAWSFileModel()->updateFile($file, $url, $size);
                    $this->syncS3FileWithSandbox($file);
                }
            } else {
                file_put_contents($file, $content);
            }
        }
        $this->ajaxResponse();
    }

    public function saveAs() {
        $content = $this->input->post('content');
        $size = strlen($content);
        $dir = $this->input->post('dir');
        $name = $this->input->post('name');
        $type = $this->input->post('type');

        if ($type == null || $type == '') {
            $type = 'file';
        }
        if ($type == 'dir') {
            return $this->__create2_dir();
        }

        $full_path = $this->getWorkshop() . $dir;
        $file = $full_path . $name;

        if ($this->checkFileCount(1) && $this->checkDiskUsage($size)) {
            if ($this->isStorageLocal()) {
                file_put_contents($file, $content);
            } else {
                $url = $this->getAWSServer()->upload($file, $content);
                if ($url === false) {
                    $this->addErrorMessage('Error occurred while saving file.');
                    return;
                } else {
                    $this->getAWSFileModel()->createFile($name, $full_path, $url, $this->__account->id, $type, $size);
                    $this->syncS3FileWithSandbox($file);
                }
            }

            $url = $dir . $name;
            $id = $this->__filename2id(htmlentities($url));
            $this->addToResponseData(array('id' => $id, 'name' => $name, 'dir' => $dir, 'url' => $url, 'type' => $type));
        }
        $this->ajaxResponse();
    }

    public function create2() {
        $user_id = $this->__account->id;

        $dir = $this->input->post('dir');
        if ($dir != '') {
            $dir = rtrim($dir, '/') . '/';
        }
        $path = $this->getWorkshop() . $dir;
        $name = $this->input->post('name');
        $type = $this->input->post('type');
        if ($type == null || $type == '') {
            $type = 'file';
        }
        if ($type == 'dir') {
            return $this->__create2_dir();
        }

        $content = "/* Untitled file */";
        $size = strlen($content);

        if ($this->checkFileCount(1) && $this->checkDiskUsage($size)) {
            if ($this->isStorageLocal()) {
                file_put_contents($this->filterPath($path . $name), $content);
            } else {
                $url = $this->getAWSServer()->upload($path . $name, $content);
                if ($url === false) {
                    $this->addErrorMessage('Error occurred while creating file.');
                } else {
                    $this->getAWSFileModel()->createFile($name, $path, $url, $user_id, $type, $size);
                }
            }
        }
        $this->ajaxResponse();
    }

    public function __create2_dir() {
        $user_id = $this->__account->id;

        $workspace = $this->input->post('workspace');
        $dir = $this->input->post('dir');
        if ($dir != '') {
            $dir = rtrim($dir, '/') . '/';
        }
        $path = $this->getWorkshop() . $workspace . '/' . $dir;
        $name = $this->input->post('name');

        if ($this->isStorageLocal()) {
            $umask = umask(0);
            mkdir($path . $name, 0777);
            umask($umask);
        } else {
            $this->getAWSFileModel()->createDir($name, $path, $user_id);
        }

        $this->ajaxResponse();
    }

    public function delete() {
        $urls = $this->input->post('urls');
        foreach ($urls as $url) {
            $this->__delete_file_or_dir($this->getWorkshop() . $url);
        }

        $this->ajaxResponse();
    }

    public function deleteFiles() {
        foreach ($_POST as $file) {
            $dir = $file['dir'];
            if ($dir != '') {
                $dir = rtrim($dir, '/') . '/';
            }
            $this->__delete_file_or_dir($this->getWorkshop() . $dir . $file['fileUrl']);
        }
    }

    public function deleteTempFiles() {
        $urls = $this->input->post('urls');
        foreach ($urls as $url) {
            $this->__delete_file_or_dir_local($url);
        }

        $this->ajaxResponse();
    }

    public function rename() {
        $dir = $this->input->post('dir');
        $type = $this->input->post('type');
        if ($dir != '') {
            $dir = rtrim($dir, '/') . '/';
        }
        $path = $this->getWorkshop() . $dir;
        $from = $path . $this->input->post('from');
        $to = $path . $this->input->post('to');
        if ($this->isStorageLocal()) {
            rename($from, $to);
        } else {
            $this->__rename_aws($from, $to);
        }

        $name = $this->input->post('to');
        $url = $dir . $name;
        if ($type == 'dir') {
            $url .= '/';
        }
        $id = $this->__filename2id(htmlentities($url));
        $this->ajaxResponse(array('id' => $id, 'name' => $name, 'dir' => $dir, 'url' => $url, 'type' => $type));
    }

    public function __rename_aws($from, $to) {
        $from = rtrim($from, '/');
        $to = rtrim($to, '/');

        $file = $this->getAWSFileModel()->getByPath($from);
        $url = '';
        if ($file->type == 'dir') {
            $files = $this->getAWSFileList($from . '/', true);
            if (sizeof($files) > 0) {
                foreach ($files as $f) {
                    $this->__rename_aws($from . '/' . $f->name, $to . '/' . $f->name);
                }
            }
        } else {
            $url = $this->getAWSServer()->copy($to, $from);
            $this->getAWSServer()->delete($from);
        }

        $name = end(explode('/', $to));
        $path = rtrim($to, $name);
        $this->getAWSFileModel()->save(array('id' => $file->id, 'path' => $path, 'name' => $name));
    }

    public function __exists($path) {
        if ($this->isStorageLocal()) {
            return file_exists($path);
        } else if ($this->isStorageAWS()) {
            $file = $this->getAWSFileModel()->getByPath($path);
            return $file != null;
        }
    }

    public function __get_type($path) {
        if ($this->isStorageLocal()) {
            return is_dir($path) ? 'dir' : 'file';
        } else if ($this->isStorageAWS()) {
            return $this->getAWSFileModel()->getByPath($path)->type;
        }
    }

    public function copy() {
        $files = $this->input->post('files');
        $target_dir = $this->input->post('target_dir');
        $command = $this->input->post('command');
        $target_files = array();

        foreach ($files as $file) {
            $name = $file['name'];
            $dir = $file['dir'];
            $type = $file['type'];

            $url = $dir . $name;
            $from = $this->getWorkshop() . $url;
            $target_url = $target_dir . $name;
            $to = $this->getWorkshop() . $target_url;

            if ($type == 'dir') {
                $ext = '';
            } else {
                $ext = $this->__ext($name);
            }
            if ($this->__exists($to)) {
                $old_type = $this->__get_type($to);
                if ($type != $old_type) {
                    $this->addErrorMessage("The " . ($type == "dir" ? "file" : "directory") . " '$name' already exists.");
                }
                if ($type == 'file') {
                    $src = rtrim($name, '.' . $ext);
                    $i = 1;
                    do {
                        $name = $src . '_' . ($i ++) . '.' . $ext;
                        $target_url = $target_dir . $name;
                        $to = $this->getWorkshop() . $target_url;
                    } while ($this->__exists($to));
                }
            }

            if ($command == 'copy' || $command == 'duplicate') {
                if (!$this->checkFileCount($this->getFileCount($url))) {
                    break;
                }

                if (!$this->checkDiskUsage($this->getDiskUsage($url))) {
                    break;
                }
            }

            if ($this->isStorageLocal()) {
                $this->__copy($from, $to, $command);
            } else {
                $this->__copy_aws($from, $to, $command);
            }

            $target_url = $target_dir . $name;
            if ($type == 'dir') {
                $target_url .= '/';
            }
            $id = $this->__filename2id(htmlentities($target_url));
            $target_files[] = array('id' => $id, 'name' => $name, 'url' => $target_url, 'type' => $type);
        }

        $this->ajaxResponse(array('files' => $target_files, 'dir' => $target_dir));
    }

    function __copy($from, $to, $command) {
        if (is_dir($from)) {
            if (!file_exists($to)) {
                $umask = umask(0);
                mkdir($to, 0777);
                umask($umask);
            }
            $files = array_diff(scandir($from), array('.', '..'));
            foreach ($files as $file) {
                $this->__copy($from . '/' . $file, $to . '/' . $file, $command);
            }

            if ($command != 'copy' && $command != 'duplicate') {
                rmdir($from);
            }
        } else if (is_file($from)) {
            copy($from, $to);
            if ($command != 'copy' && $command != 'duplicate') {
                chmod($from, 0750);
                unlink($from);
            }
        }

        return false;
    }

    public function __copy_aws($from, $to, $command) {
        $from = rtrim($from, '/');
        $to = rtrim($to, '/');

        $file = $this->getAWSFileModel()->getByPath($from);
        $url = '';
        if ($file->type == 'dir') {
            if (!$this->getAWSFileModel()->getByPath($to)) {
                $name = end(explode('/', $to));
                $path = rtrim($to, $name);
                $file->id = 0;
                $file->path = $path;
                $this->getAWSFileModel()->save((array) $file);
            }
            $files = $this->getAWSFileList($from . '/', true);
            if (sizeof($files) > 0) {
                foreach ($files as $f) {
                    $this->__copy_aws($from . '/' . $f->name, $to . '/' . $f->name);
                }
            }
        } else {
            $url = $this->getAWSServer()->copy($to, $from);
            if (!$this->getAWSFileModel()->getByPath($to)) {
                $name = end(explode('/', $to));
                $path = rtrim($to, $name);
                $file->id = 0;
                $file->path = $path;
                $file->url = $url;
                $this->getAWSFileModel()->save((array) $file);
            }
        }

        if ($command != 'copy' && $command != 'duplicate') {
            if ($file->type != 'dir') {
                $this->getAWSServer()->delete($from);
            }
            $this->getAWSFileModel()->deleteByPath($from);
        }
    }

    public function dir() {
        if ($this->session->userdata('loggedin') != 'true') {
            exit;
        }
        $user_id = $this->session->userdata('user_id');

        $dir = $this->input->post('dir');
        $type = $this->input->post('type');
        if ($type == null) {
            $type = '';
        }

        if ($dir != '') {
            $dir = rtrim($dir, '/') . '/';
        }
        $path = $this->getWorkshop() . $dir;
        $files = array();
        if ($this->isStorageLocal()) {

            if (file_exists($path)) {
                $list = scandir($path);
                natcasesort($list);

                if (count($list) > 2) { /* The 2 accounts for . and .. */
                    foreach ($list as $file) {

                        if (file_exists($path . $file) && $file != '.' && $file != '..' && is_dir($path . $file)) {
                            $files[] = array('name' => $file, 'type' => 'dir', 'url' => $dir . $file . '/');
                        }
                    }
                    if ($type != "dir") {
                        foreach ($list as $file) {
                            $url = $dir . $file;
                            if (file_exists($path . $file) && $file != '.' && $file != '..' && !is_dir($path . $file)) {
                                $files[] = array('id' => $this->__filename2id(htmlentities($url)), 'name' => $file, 'url' => $url, 'type' => 'file');
                            }
                        }
                    }
                }
            }
        } else {
            $list = $this->getAWSFileList($path, true, $type);
            foreach ($list as $file) {
                $url = $dir . $file->name;
                if ($file->type == 'dir') {
                    $url .= '/';
                }
                $files[] = array(
                    'name' => $file->name,
                    'id' => $this->__filename2id(htmlentities($url)),
                    'url' => $url,
                    'type' => $file->type
                );
            }
        }

        if (count($files) > 0 && $this->input->post('restore')) {
            $states = $this->____load_model('FileTreeStateModel')->search(array('user_id' => $user_id, 'url' => $dir));
            if (count($states) > 0) {
                foreach ($files as $key => $file) {
                    if ($file['type'] == 'dir') {
                        foreach ($states as $key1 => $state) {
                            if ($state->url == $file['url']) {
                                $files[$key]['restore'] = 1;
                                unset($states[$key1]);
                                break;
                            }
                        }
                    }
                }
            }
        }
        $this->ajaxResponse(array('files' => $files, 'dir' => $dir));
    }

    //[2015-07-16 D.A. Zhen] Function that loads the file in the specified @dir
    public function dir2json() {
        $dir = $this->input->post('dir');
        $type = $this->input->post('type');
        if ($type == null) {
            $type = '';
        }

        if ($dir != '') {
            $dir = rtrim($dir, '/') . '/';
        } else {
            $this->addErrorMessage('Please specify the directory.');
            $this->ajaxResponse();
        }
        $path = $this->getWorkshop() . $dir;
        $files = array();
        if ($this->isStorageLocal()) {

            if (file_exists($path)) {
                $list = scandir($path);
                natcasesort($list);
                $dirindex = 0;
                $fileindex = 0;

                if (count($list) > 2) { /* The 2 accounts for . and .. */
                    foreach ($list as $file) {
                        $fsize = filesize($path . $file) ? $this->__bytes2size(filesize($path . $file)) : 'N/A';
                        $date = filemtime($path . $file) ? date("Y-m-d", filemtime($path . $file)) : 'N/A';
                        $dirindex++;

                        if (file_exists($path . $file) && $file != '.' && $file != '..' && is_dir($path . $file)) {
                            $files[] = array('fileUrl' => $file, 'fileSize' => $fsize, 'lastModifiedDate' => $date);
                        }
                    }
                    if ($type != "dir") {
                        foreach ($list as $file) {
                            $fsize = filesize($path . $file) ? $this->__bytes2size(filesize($path . $file)) : 'N/A';
                            $date = filemtime($path . $file) ? date("Y-m-d", filemtime($path . $file)) : 'N/A';
                            $fileindex++;

                            if (file_exists($path . $file) && $file != '.' && $file != '..' && !is_dir($path . $file)) {
                                $files[] = array('fileId' => $this->__filename2id(htmlentities($dir . $file)), 'fileUrl' => $file, 'fileSize' => $fsize, 'lastModifiedDate' => $date);
                            }
                        }
                    }
                }
            }
        } else {
            $list = $this->getAWSFileList($path, true, $type);
            foreach ($list as $file) {
                $files[] = array(
                    'fileUrl' => $file->name,
                    'fileId' => $this->__filename2id(htmlentities($dir . $file->name)),
                    'fileSize' => ($file->type == 'dir' ? 'N/A' : $this->__bytes2size($file->size)),
                    'lastModifiedDate' => DateTime::createFromFormat('Y-m-d H:i:s', $file->modified_time)->format('Y-m-d'),
                );
            }
        }
        $files['details'] = array('dir' => $dir);
        $this->ajaxResponse(array('files' => $files, 'dir' => $dir));
    }

    public function saveToLocal() {
        if ($this->session->userdata('loggedin') != 'true') {
            exit;
        }

        $content = $this->input->post('content');
        $name = $this->input->post('name');

        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . $name);
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . strlen($content));
        echo $content;
    }

    public function download() {
        if ($this->session->userdata('loggedin') != 'true') {
            exit;
        }

        $dir = $this->input->post('dir');
        if ($dir != '') {
            $dir = rtrim($dir, '/') . '/';
        }
        $name = $this->input->post('name');
        $path = $this->getWorkshop() . $dir . $name;

        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . $name);
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');

        if ($this->isStorageAWS()) {
            $content = $this->getAWSServer()->download($path);
            header('Content-Length: ' . strlen($content));
            if ($content === false) {
                echo 'Error occurred while downloading file.';
            } else {
                echo htmlentities($content);
            }
        } else {
            header('Content-Length: ' . filesize($path));
            echo file_get_contents($path);
        }
    }

    private function assembleKeyword($needle, $options) {
        if (!isset($options['regExp']) || !$options['regExp']) {
            $needle = $this->escapeRegEx($needle);
        }

        if (isset($options['wholeWord']) && $options['wholeWord']) {
            $needle = "\\b" . $needle . "\\b";
        }

        $needle = '/' . $needle . '/';

        if (!isset($options['caseSensitive']) || !$options['caseSensitive']) {
            $needle .= 'i';
        }

        return $needle;
    }

    private function escapeRegEx($str) {
        return preg_quote(preg_replace('!((http|ftp|https):\/\/)?([\w\-_]+(?:(?:\.[\w\-_]+)+))([\w\-\.,@?^=%&:/~\+#]*[\w\-\@?^=%&/~\+#])?!', '\\$1', $str));
    }

    private function assembleFilter($filter) {
        $filter = explode(';', $filter);
        if (sizeof($filter) <= 0) {
            return null;
        }
        $conditions = array();
        foreach ($filter as $f) {
            $cond = $this->getFileNameComponents($f);
            $nameCond = str_replace('*', '.*', $this->escapeRegEx($cond['name']));
            $extCond = str_replace('*', '.*', $this->escapeRegEx($cond['ext']));
            if ($nameCond != null || $extCond != null) {
                $conditions[] = array('name' => $nameCond, 'ext' => $extCond);
            }
        }
        if (sizeof($conditions) <= 0) {
            return null;
        }

        return $conditions;
    }

    private function getFileNameComponents($fileName) {
        $name = trim($fileName);
        $ext = null;
        if (strlen($name) <= 0) {
            $name = null;
        }
        $components = explode('.', $name);
        if (sizeof($components) > 1) {
            $ext = end($components);
            if (strlen($components[1]) <= 0) {
                $ext = null;
            }
        }
        if ($ext) {
            $name = rtrim($name, $ext);
        }
        $name = rtrim($name, '.');

        return array('name' => $name, 'ext' => $ext);
    }

    private function __match($file) {
        $filter = $this->__searchOptions['filter'];
        if ($filter == null) {
            return true;
        }

        $components = $this->getFileNameComponents($file);
        $name = $this->escapeRegEx($components['name']);
        $ext = $this->escapeRegEx($components['ext']);

        foreach ($filter as $condition) {
            $nameCond = $condition['name'] != null ? '/' . $condition['name'] . '/i' : null;
            $extCond = $condition['ext'] != null ? '/' . $condition['ext'] . '/i' : null;
            //var_dump($nameCond, $extCond, $name, $ext);exit;
            if (($nameCond == null || ($name != null && preg_match($nameCond, $name))) && ($extCond == null || ($ext != null && preg_match($extCond, $ext)))) {
                return true;
            }
        }
        return false;
    }

    public function search() {
        $needle = $this->input->post('needle');
        $options = $this->input->post('options');
        $keyword = $this->assembleKeyword($needle, $options);
        /* TEST
          $matches = array();
          $count = preg_match($keyword, '<table333 border="1" width="100%" style="font-family: Arial, Helvetica, Sans-serif; color: #000; border-collapse: collapse;', $matches, PREG_OFFSET_CAPTURE, 98);
          echo json_encode(array(
          'needle' => $needle,
          'keyword' => $keyword,
          'match' => $matches,
          'count' => $count,

          ));
          exit; */
        $filter = $this->input->post('filter');
        $workspace = $this->input->post('workspace');
        $dir = trim($this->input->post('dir'), '/');
        $subfolder = (bool) $this->input->post('subfolder');
        if (strlen($dir) > 0) {
            $dir .= '/';
        }
        $path = $workspace . $dir;
        $this->__searchOptions = array(
            'keyword' => $keyword,
            'filter' => $this->assembleFilter($filter),
            'subfolder' => $subfolder,
        );
        $this->__searches = array();

        if ($this->isStorageLocal()) {
            $this->__search($path);
        } else {
            $this->__search_aws($path);
        }

        $this->ajaxResponse(array('searches' => $this->__searches));
    }

    public function __search($dir) {
        $path = $this->getWorkshop() . $dir;
        if (file_exists($path)) {
            $list = scandir($path);
            natcasesort($list);

            if (count($list) > 2) { /* The 2 accounts for . and .. */
                $files = array();
                foreach ($list as $file) {
                    if ($file == '.' || $file == '..') {
                        continue;
                    }
                    if (is_dir($path . $file)) {
                        if ($this->__searchOptions['subfolder']) {
                            $this->__search($dir . $file . '/');
                        }
                    } else {
                        $files[] = $file;
                    }
                }
                if (sizeof($files) > 0) {
                    foreach ($files as $file) {
                        if (!$this->__match($file)) {
                            continue;
                        }

                        if ($ranges = $this->____search($path . $file)) {
                            $this->__searches[] = array(
                                'fileId' => $this->__filename2id(htmlentities($dir . $file)),
                                'fileURL' => $dir . $file,
                                'fileName' => $file,
                                'ranges' => $ranges
                            );
                        }
                    }
                }
            }
        }
    }

    public function __search_aws($dir) {
        $path = $this->getWorkshop() . $dir;
        $list = $this->getAWSFileList($path, !$this->__searchOptions['subfolder'], 'file');
        if (sizeof($list) > 0) {
            foreach ($list as $file) {
                if (!$this->__match($file->name)) {
                    continue;
                }

                $tempfile = $this->getTempDir() . UUID::v4() . '.' . end(explode('.', $file->name));
                $url = str_replace($this->getWorkshop(), '', $file->path) . $file->name;
                try {
                    $this->getAWSServer()->download2LocalFile($file->path . $file->name, $tempfile);
                    if ($ranges = $this->____search($tempfile)) {
                        $this->__searches[] = array(
                            'fileId' => $this->__filename2id(htmlentities($url)),
                            'fileURL' => $url,
                            'fileName' => $file->name,
                            'ranges' => $ranges
                        );
                    }
                } catch (Exception $ex) {
                    $this->addErrorMessage($url . ' does not exist.');
                }
                unlink($tempfile);
            }
        }
    }

    public function ____search($path) {
        $ranges = array();
        $lines = file($path);
        if (sizeof($lines) <= 0) {
            return null;
        }
        $lineNo = 0;
        foreach ($lines as $line) {
            if (preg_match_all($this->__searchOptions['keyword'], $line, $matches, PREG_OFFSET_CAPTURE)) {
                $matches = $matches[0];
                foreach ($matches as $match) {
                    $startColumn = $match[1];
                    $endColumn = $startColumn + strlen($match[0]);
                    $ranges[] = array(
                        'line' => strlen($line) > 1000 /* Check if the line is too lengthy */ ? '' : utf8_encode($line),
                        'start' => array('row' => $lineNo, 'column' => $startColumn),
                        'end' => array('row' => $lineNo, 'column' => $endColumn)
                    );
                }
            }
            $lineNo ++;
        }

        if (sizeof($ranges) <= 0) {
            return null;
        }

        return $ranges;
    }

    public function replace() {
        $needle = $this->input->post('needle');
        $options = $this->input->post('options');
        $keyword = $this->assembleKeyword($needle, $options);
        $replace = $this->input->post('replace');
        $filter = $this->input->post('filter');
        $workspace = $this->input->post('workspace');
        $dir = trim($this->input->post('dir'), '/');
        $subfolder = (bool) $this->input->post('subfolder');
        if (strlen($dir) > 0) {
            $dir .= '/';
        }
        $path = $workspace . $dir;

        $this->__searchOptions = array(
            'keyword' => $keyword,
            'replace' => $replace,
            'filter' => $this->assembleFilter($filter),
            'subfolder' => $subfolder,
        );

        $replacedCount = 0;
        if ($this->isStorageLocal()) {
            $replacedCount = $this->__replace($path);
        } else {
            $replacedCount = $this->__replace_aws($path);
        }

        $this->ajaxResponse(array('replacedCount' => $replacedCount));
    }

    public function __replace($dir) {
        $replacedCount = 0;
        $path = $this->getWorkshop() . $dir;
        if (file_exists($path)) {
            $list = scandir($path);
            natcasesort($list);

            if (count($list) > 2) { /* The 2 accounts for . and .. */
                $files = array();
                foreach ($list as $file) {
                    if ($file == '.' || $file == '..') {
                        continue;
                    }
                    if (is_dir($path . $file)) {
                        if ($this->__searchOptions['subfolder']) {
                            $this->__search($dir . $file . '/');
                        }
                    } else {
                        $files[] = $file;
                    }
                }
                if (sizeof($files) > 0) {
                    foreach ($files as $file) {
                        if (!$this->__match($file)) {
                            continue;
                        }

                        $replacedCount += $this->____replace($path . $file);
                    }
                }
            }
        }

        return $replacedCount;
    }

    public function __replace_aws($path) {
        $replacedCount = 0;
        $list = $this->getAWSFileList($path, $this->__searchOptions['subfolder'], 'file');
        if (sizeof($list) > 0) {
            foreach ($list as $file) {
                if (!$this->__match($file)) {
                    continue;
                }

                $tempfile = $this->getTempDir() . UUID::v4() . '.' . end(explode('.', $file->name));
                $url = str_replace($this->getWorkshop(), '', $file->path) . $file->name;
                try {
                    $this->getAWSServer()->download2LocalFile($file->path . $file->name, $tempfile);
                    if (($count = $this->____replace($tempfile)) > 0) {

                        $replacedCount += $count;
                    }
                    unlink($tempfile);
                } catch (Exception $ex) {
                    $this->addErrorMessage($url . ' does not exist.');
                }
            }
        }
        return $replacedCount;
    }

    public function ____replace($path) {
        $count = 0;
        $content = file_get_contents($path);
        $content = preg_replace($this->__searchOptions['keyword'], $this->__searchOptions['replace'], $content, -1, $count);
        if ($count > 0) {
            file_put_contents($path, $content);
        }
        return $count;
    }

    function __bytes2size($bytes) {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }

        return $bytes;
    }

    function __ext($name) {
        return preg_replace('/^.*\./', '', $name);
    }

    function saveState() {
        if ($this->session->userdata('loggedin') != 'true') {
            exit;
        }
        $user_id = $this->session->userdata('user_id');

        $url = $this->input->post('url');
        $state = $this->input->post('state');

        $stateModel = $this->____load_model('FileTreeStateModel');
        if ($state == 'expand') {
            $stateModel->saveByURL($user_id, $url);
        } else {
            $stateModel->deleteByURL($user_id, $url);
        }
    }

    function getState() {
        if ($this->session->userdata('loggedin') != 'true') {
            exit;
        }
        $user_id = $this->session->userdata('user_id');

        $url = $this->input->post('url');

        $state = $this->____load_model('FileTreeStateModel')->getByURL($user_id, $url);
        $this->ajaxResponse(array('restore' => $state ? 1 : 0));
    }

    function fetchWorkspace($dir) {
        if ($workspace = $this->fetchWorkspace0($dir)) {
            return $workspace . '/';
        }

        return null;
    }

    public function upload() {
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            if (isset($_FILES['file']) && $_FILES['file'] && sizeof($_FILES['file']['name']) > 0) {
                $files = $_FILES['file'];
                $uploaded_count = 0;
                $count = sizeof($files['name']);
                for ($i = 0; $i < $count; $i++) {
                    $dir = $this->input->post('dir');
                    $name = $files['name'][$i];
                    $path = $this->getWorkshop() . $dir;
                    $file = $path . $name;
                    $tmp_path = $files['tmp_name'][$i];
                    $size = $files['size'][$i];

                    if (!$this->checkFileCount(1) || !$this->checkDiskUsage($size)) {
                        break;
                    }

                    if ($this->isStorageLocal()) {
                        move_uploaded_file($tmp_path, $file);
                    } else {
                        $url = $this->getAWSServer()->uploadFromFile($file, $tmp_path);
                        if ($url === false) {
                            $this->addErrorMessage('Error occurred while saving file.');
                        } else {
                            if (!$this->getAWSFileModel()->updateFile($file, $url, $size)) {
                                $this->getAWSFileModel()->createFile($name, $path, $url, $this->__account->id, 'file', $size);
                            }
                        }
                    }
                    $uploaded_count ++;
                }

                if ($uploaded_count > 0) {
                    $this->addSuccessMessage($this->__tweakForPlural($uploaded_count, 'files') . ' uploaded successfully.');
                }

                $this->ajaxResponse();
            }
        } else {
            $this->load->view('editor/uploadFiles');
        }
    }

    //[2015-03-25] Function that extracts file specified in @url
    public function extract() {
        if ($this->isPost()) {
            $dir = $this->input->post('dir');
            $url = $this->input->post('url');
            $file = $this->getWorkshop() . $url;
            $this->load->library('Zipper', null, 'zipper');
            if ($this->isStorageAWS()) {
                $tempFile = $this->getTempFileName($this->__ext($file));
                $this->getAWSServer()->download2LocalFile($file, $tempFile);
                $file = $tempFile;
            }
            $info = $this->zipper->getFileInfo($file);
            if ($this->checkFileCount($info['count']) && $this->checkDiskUsage($info['size'])) {
                if ($this->isStorageLocal()) {
                    $this->zipper->extract($file, $this->getWorkshop() . $dir);
                } else {
                    $this->zipper->extractToAWS($file, $this->getWorkshop() . $dir, $this->getAWSServer(), $this->getAWSFileModel(), $this->getTempDir(), $this->session->userdata('user_id'));
                    unlink($file);
                }

                $this->addSuccessMessage("The selected file has been extracted successfully.");
            }
            $this->ajaxResponse(array('dir' => $dir));
        }
    }

    //[2015-03-25] Function that compresses files specified in @urls
    public function compress() {
        $urls = $this->input->post('urls');
        foreach ($urls as $key => $url) {
            $urls[$key] = $this->getWorkshop() . rtrim($url, '/');
        }
        $dir = $this->input->post('dir');
        $name = $this->input->post('name');
        if ($name == null || $name == '') {
            $name = pathinfo(rtrim($dir), PATHINFO_FILENAME) . '.zip';
        }
        $path = $this->getWorkshop() . $dir;
        $file = $path . $name;

        //using temporary archive file
        $tempFile = $this->getTempFileName($this->__ext($name));
        $this->load->library('Zipper', null, 'zipper');
        if ($this->isStorageLocal()) {
            $this->zipper->create($tempFile, $urls);
        } else {
            $fileList = array();
            foreach ($urls as $key => $url) {
                $f = $this->getAWSFileModel()->getByPath($url);
                $fileList[$key] = ($f->type == 'dir') ? $this->getAWSFileList($url) : null; //if the file is dir, loads the list of files in the directory
            }
            $this->zipper->createFromAWS($tempFile, $urls, $fileList, $this->getAWSServer(), $this->getTempDir());
        }

        $size = filesize($tempFile);
        if ($this->checkFileCount(1) && $this->checkDiskUsage($size)) { //check file count and usage limit
            if ($this->isStorageLocal()) {
                rename($tempFile, $file);
            } else {
                $url = $this->getAWSServer()->uploadFromFile($file, $tempFile);
                if ($url === false) {
                    $this->addErrorMessage('Error occurred while saving file.');
                    return;
                } else {
                    $size = filesize($tempFile);
                    $this->getAWSFileModel()->createFile($name, $path, $url, $this->__account->id, 'file', $size);
                    $this->syncS3FileWithSandbox($file);
                }
                unlink($tempFile);
            }
            $this->addSuccessMessage("The selected files has been compressed successfully.");
        }

        $this->ajaxResponse(array('dir' => $dir));
    }

    public function openFromLocal() {
        $file = $_FILES['localfile'];
        $name = $file['name'];
        $tmpPath = $this->getTempDir() . $name;
        move_uploaded_file($file['tmp_name'], $tmpPath);
        $this->addToResponseData('id', $this->__filename2id($tmpPath));
        $this->addToResponseData('name', $name);
        $this->addToResponseData('path', $tmpPath);
        $this->view('popup_localfile');
    }

    public function compressAs() {
        $this->view('popup_compressas');
    }

    public function extractInto() {
        $this->view('popup_extractinto');
    }

}
