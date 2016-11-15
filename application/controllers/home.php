<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once 'FrontendController.php';

class Home extends FrontendController {

    public function __construct() {
        $this->__need_authentication = false;
        parent::__construct();
        $this->__layout = '';
    }

    public function index() {
        $subscribers = $this->____load_model('UserModel')->__total_count();
        $projects = $this->____load_model('OptionModel')->getProjectCount();
        $files = $this->__getFileCount();
        $this->view('home', array('subscribers' => $subscribers, 'projects' => $projects, 'files' => $files));
    }

}
