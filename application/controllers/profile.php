<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once 'Controller.php';

class Profile extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        if ($this->session->userdata('loggedin') != 'true') {
            redirect(base_url());
        }
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $fname = $this->input->get_post('fname');
            $lname = $this->input->get_post('lname');
            $email = $this->input->get_post('email');
            $this->load->model('UserModel');
            $res = $this->UserModel->save(array(
                'user_id' => $this->session->userdata('user_id'),
                'first_name' => $fname,
                'last_name' => $lname,
                'email' => $email
            ));
            if ($res) {
                $this->session->set_flashdata('profile_saved', "1");
            } else {
                $this->session->set_flashdata('profile_saved', "0");
            }
        }
        redirect(base_url() . 'my-account');
    }

}
