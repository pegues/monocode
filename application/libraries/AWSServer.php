<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * CodeIgniter AWS S3 Integration Library
 * 
 * @package    CodeIgniter AWS S3 Integration Library
 * @author     scriptigniter <scriptigniter@gmail.com>
 * @link       http://www.scriptigniter.com/cis3demo/
 */
require 'awssdk/autoload.php';

use Aws\S3\S3Client;

class AWSServer {

    private $bucket_name = '';
    private $client = null;
    private $__controller = null;

    public function __construct($config) {
        $access_key = $config['access_key'];
        $secret_key = $config['secret_key'];
        $bucket_name = $config['bucket_name'];
        //var_dump($access_key, $secret_key, $bucket_name);

        $this->bucket_name = $bucket_name; //The bucket name you want to use for your project
        $this->client = S3Client::factory(array(
                    'key' => $access_key,
                    'secret' => $secret_key,
        ));
    }
    
    public function setController($controller) {
        $this->__controller = $controller;
    }

    function upload($file, $content) {
        $data = array(
            'Bucket' => $this->bucket_name,
            'Key' => $file,
            'Body' => $content,
            'Metadata' => array('mode' => '33188')
        );
        $try = 1;
        $sleep = 1;
        do {
            $result = $this->client->putObject($data);
            if ($result) {
                $this->__controller->syncS3FileWithSandbox($file);
                return $result['ObjectURL'];
            }
            sleep($sleep);
            $sleep *= 2;
        } while (++$try < 3);

        //$this->set_error('upload_destination_error');
        return false;
    }

    function uploadFromFile($file, $path) {
        $data = array(
            'Bucket' => $this->bucket_name,
            'Key' => $file,
            'SourceFile' => $path,
            'Metadata' => array('mode' => '33188')
        );
        $try = 1;
        $sleep = 1;
        do {
            $result = $this->client->putObject($data);
            if ($result) {
                $this->__controller->syncS3FileWithSandbox($file);
                return $result['ObjectURL'];
            }
            sleep($sleep);
            $sleep *= 2;
        } while (++$try < 3);

        //$this->set_error('upload_destination_error');
        return false;
    }

    function copy($target, $source) {
        $data = array(
            'Bucket' => $this->bucket_name,
            'Key' => $target,
            'CopySource' => $this->bucket_name . $source,
            'Metadata' => array('mode' => '33188')
        );
        $try = 1;
        $sleep = 1;
        do {
            $result = $this->client->copyObject($data);
            //var_dump($result);
            if ($result) {
                $this->__controller->syncS3FileWithSandbox($file);
                return $result['ObjectURL'];
            }
            sleep($sleep);
            $sleep *= 2;
        } while (++$try < 3);

        //$this->set_error('upload_destination_error');
        return false;
    }
    
    function download($file) {
        $data = array(
            'Bucket' => $this->bucket_name,
            'Key' => $file,
        );
        $try = 1;
        $sleep = 1;
        do {
            $result = $this->client->getObject($data);
            if ($result) {
                return $result['Body'];
            }
            sleep($sleep);
            $sleep *= 2;
        } while (++$try < 3);

        //$this->set_error('upload_destination_error');
        return false;
    }

    function download2LocalFile($file, $path) {
        //var_dump($file);exit;
        $data = array(
            'Bucket' => $this->bucket_name,
            'Key' => $file,
            'SaveAs' => $path
        );
        $try = 1;
        $sleep = 1;
        do {
            $result = $this->client->getObject($data);
            if ($result) {
                return $result['Body']->getUri();
            }
            sleep($sleep);
            $sleep *= 2;
        } while (++$try < 3);

        //$this->set_error('upload_destination_error');
        return false;
    }

    function delete($file) {
        $file = rtrim($file, '/');
        $data = array(
            'Bucket' => $this->bucket_name,
            'Key' => $file,
        );
        $try = 1;
        $sleep = 1;
        //Try multiple times(3 times) to Delete the file if not deleted in one go by any reason.
        do {
            $result = $this->client->deleteObject($data);
            //var_dump($file, $result);
            if ($result) {
                $this->__controller->syncS3FileWithSandbox($file);
                return $result;
            }
            
            sleep($sleep);
            $sleep *= 2;
        } while (++$try < 3);

        //$this->set_error('upload_destination_error');
        return false;
        
    }

}
