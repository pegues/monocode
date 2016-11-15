<?php

class Zipper {

    private $__zip = null;
    private $__root = null;
    private $__awsServer = null;
    private $__awsFileList = null;
    private $__tempDir = null;
    private $__tempFiles = null;
    private $__workspace_path = null;
    private $__awsFileModel = null;

    public function __construct() {
        $this->__zip = new ZipArchive();
    }

    public function create($zipFilePath, $pathList) {
        if (!is_array($pathList)) { //if single file, makes array from it
            $pathList = array($pathList);
        }
        $this->__zip->open($zipFilePath, ZipArchive::CREATE);
        foreach ($pathList as $path) {
            $path_parts = pathinfo($path);
            $name = $path_parts['basename'];    //name of file
            $dir = $path_parts['dirname'];  //root dir of file
            if (is_dir($path)) {    //if dir, first adds a directory in the archvie, and adds all files of that dir
                $this->__zip->addEmptyDir($name);
                $this->__root = $dir . '/';
                $this->addFiles($name . '/');
            } else if (is_file($path)) {    // just adds the file
                $this->__zip->addFile($path, $name);
            }
        }
        $this->__zip->close();
    }

    public function addFiles($path) {
        $nodes = glob($this->__root . $path . '*');
        foreach ($nodes as $node) { //loop through dir
            $name = str_ireplace($this->__root, '', $node);
            //var_dump($name, '<br />');
            if ($node == '.' || $node == '..') {
                continue;
            }
            if (is_dir($node)) {
                $this->__zip->addEmptyDir($name);
                $this->addFiles($name . '/');
            } else if (is_file($node)) {
                $this->__zip->addFile($node, $name);
            }
        }
    }

    //[2015-03-26 D.A. Zhen] Function that creates archive from aws files
    public function createFromAWS($zipFilePath, $pathList, $fileList, $awsServer, $tempDir) {
        if (!is_array($pathList)) {
            $pathList = array($pathList);
            $fileList = array($fileList);
        }
        $this->__zip->open($zipFilePath, ZipArchive::CREATE);

        $this->__awsServer = $awsServer;
        $this->__tempDir = $tempDir;
        $this->__tempFiles = array();

        foreach ($pathList as $key => $path) {
            $path_parts = pathinfo($path);
            $name = $path_parts['basename'];
            $dir = $path_parts['dirname'];
            if ($fileList[$key]) {    //if dir ($fileList[$key] will not be null if $path is dir), first adds a directory in the archvie, and adds all files of that dir
                $this->__zip->addEmptyDir($name);
                $this->__root = $dir . '/';
                $this->__awsFileList = $fileList[$key];
                $this->addFilesFromAWS($name . '/');
            } else {
                $tempFile = $this->__tempDir . md5($path) . time();
                $this->__awsServer->download2LocalFile($path, $tempFile);
                $this->__zip->addFile($tempFile, $name);
                $this->__tempFiles[] = $tempFile;                
            }
        }

        $this->__zip->close();

        foreach ($this->__tempFiles as $key => $file) {
            unlink($file);
        }
    }

    public function addFilesFromAWS($path) {
        foreach ($this->__awsFileList as $key => $node) {
            if ($node->path != $this->__root . $path) {
                continue;
            }

            $file = $node->path . $node->name;
            $name = str_ireplace($this->__root, '', $file);
            //var_dump($name, '<br />');
            if (strcmp($node->type, 'dir') == 0) {
                $this->__zip->addEmptyDir($name);
                $this->addFilesFromAWS($name . '/');
            } else {
                $tempFile = $this->__tempDir . md5($file) . time();
                $this->__awsServer->download2LocalFile($file, $tempFile);
                $this->__zip->addFile($tempFile, $name);
                $this->__tempFiles[] = $tempFile;
            }

            unset($this->__awsFileList[$key]);
        }
    }

    public function getFileInfo($zip_path) {
        $size = 0;
        $count = 0;
        if ($entry = zip_open($zip_path)) {
            while ($dir_resource = zip_read($entry)) {
                $name = zip_entry_name($dir_resource);
                if ($name[strlen($name) - 1] != '/') {
                    $count++;
                }
                $size += zip_entry_filesize($dir_resource);
            }
            zip_close($entry);
        }
        return array('size' => $size, 'count' => $count);
    }

    public function extract($zipFilePath, $workspace_path) {
        if ($this->__zip->open($zipFilePath)) {
            $this->__zip->extractTo($workspace_path);
            $this->__zip->close();
        }
    }

    public function extractToAWS($zipFilePath, $workspace_path, $awsServer, $awsFileModel, $tempDir, $user_id) {
        $zip_dir = $tempDir . UUID::v4() . '/';
        $umask = umask(0);
        mkdir($zip_dir, 0777);
        umask($umask);
        //var_dump($zip_dir);exit;
        if ($this->__zip->open($zipFilePath)) {
            $this->__zip->extractTo($zip_dir);
            $this->__root = $zip_dir;
            $this->__workspace_path = $workspace_path;
            $this->__awsServer = $awsServer;
            $this->__awsFileModel = $awsFileModel;
            $this->__user_id = $user_id;
            $this->uploadFilesToAWS('');
            $this->__zip->close();
        }

        //rmdir($zip_dir);
    }

    public function uploadFilesToAWS($path) {
        $nodes = glob($this->__root . $path . '*');

        foreach ($nodes as $node) {
            $name = str_ireplace($this->__root, '', $node);
            if ($node == '.' || $node == '..') {
                continue;
            }
            //var_dump($name, '<br />');
            if (is_dir($node)) {
                $this->__awsFileModel->createDir(str_ireplace($path, '', $name), $this->__workspace_path . $path, $this->__user_id);
                $this->uploadFilesToAWS($name . '/');
                //rmdir($node);
            } else if (is_file($node)) {
                $url = $this->__awsServer->uploadFromFile($this->__workspace_path . $name, $this->__root . $name);
                $size = filesize($this->__root . $name);
                $this->__awsFileModel->createFile(str_ireplace($path, '', $name), $this->__workspace_path . $path, $url, $this->__user_id, 'file', $size);
                unlink($node);
            }
        }
    }

}
