<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once 'EditorController.php';

class Database extends EditorController {

    public function __construct() {
        $this->__unauthorized_actions = array('disconnect');
        parent::__construct();
        
        $this->__layout = 'tab';

        $this->__load_db_user();
    }

    public function index() {
        $data['status'] = true;
        $data['msg'] = '';
        $data['list'] = $this->__get_databases();
        $data['limit'] = (int) $this->__get_feature('database');

        echo json_encode($data);
    }

    public function create() {
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $data['prefix'] = $this->db_prefix;
            $name = $this->input->post('name');
            if ($name == null || $name == '') {
                $this->addErrorMessage('Invalid database name.');
                return $this->view('database', $data);
            }
            $data['name'] = $name;

            //Check if can add database
            if (sizeof($this->__get_databases()) >= (int) $this->__get_feature('database')) {
                $this->addErrorMessage('Exceeds the maxium number of databases that can be created.');
                return $this->view('database', $data);
            }

            //Make the database with prefix
            $db_name = $this->db_prefix . '_' . $name;

            //Check if the database is duplicated
            $query = "show databases where `Database` LIKE ?;";
            $ret = $this->db->query($query, $db_name);
            $list = $ret->result_array();
            if (sizeof($list) > 0) {
                $this->addErrorMessage('Duplicated database name');
                return $this->view('database', $data);
            }

            //Crate a new database
            $query = "create database `$db_name`;";
            if ($ret = $this->db->query($query)) {
                $domain = '%';
                $query = "grant all on `$db_name`.* to '$this->db_username'@'$domain';";
                $ret = $this->db->query($query);
                $this->addSuccessMessage('A new database has been created successfully.');
                $this->redirect(current_url());
            } else {
                $this->addErrorMessage('Unknown error!!!');
            }

            $data['status'] = $ret > 0;

            $this->view('database', $data);
        } else {
            $this->view('database', array('prefix' => $this->db_prefix));
        }
    }

    public function rename() {
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $data['status'] = false;
            $data['msg'] = '';
            $data['prefix'] = $this->db_prefix;

            $old_name = $this->input->post('old_name');
            if ($old_name == null || $old_name == '') {
                $data['msg'] = ('Invalid old database name');
                return $this->view('database', $data);
            }

            $data['old_name'] = $old_name;

            $name = $this->input->post('name');
            if ($name == null || $name == '') {
                $data['msg'] = ('Invalid new database name');
                return $this->view('database', $data);
            }

            $data['name'] = $name;

            if ($name == $old_name) {
                $data['status'] = false;
                $this->addInfoMessage('Nothing has been changed.');
                return $this->view('database', $data);
            }

            //Make the database with prefix
            $old_db_name = $this->db_prefix . '_' . $old_name;
            $db_name = $this->db_prefix . '_' . $name;

            //Check if the old database exists
            $query = "show databases where `Database` LIKE ?;";
            $ret = $this->db->query($query, $old_db_name);
            $list = $ret->result_array();
            if (sizeof($list) <= 0) {
                $this->addWarningMessage('No old database exists.');
                return $this->view('database', $data);
            }

            //Check if the database is duplicated
            $query = "show databases where `Database` LIKE ?;";
            $ret = $this->db->query($query, $db_name);
            $list = $ret->result_array();
            if (sizeof($list) > 0) {
                $this->addErrorMessage('Duplicated database name');
                return $this->view('database', $data);
            }

            //Crate a new database
            $query = "create database `$db_name`;";
            if ($ret = $this->db->query($query)) {
                $domain = '%';
                $query = "grant all on `$db_name`.* to '$this->db_username'@'$domain';";
                $ret = $this->db->query($query);
                if ($ret) {
                    $query = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE table_schema='$old_db_name'";
                    $ret = $this->db->query($query);
                    if ($ret) {
                        $list = $ret->result_array();
                        if (sizeof($list) > 0) {
                            foreach ($list as $table) {
                                $table_name = $table['TABLE_NAME'];
                                $this->db->query("RENAME TABLE `$old_db_name`.`$table_name` to `$db_name`.`$table_name`");
                            }
                        }
                        $query = "drop database `$old_db_name`;";
                        if (($ret = $this->db->query($query))) {
                            $data['status'] = true;
                            $data['old_name'] = $name;
                            $this->addSuccessMessage('The database has been changed successfully.');
                            $this->redirect(current_url());
                        }
                    }
                }
            }

            $this->addErrorMessage('Unknow error!!!');
            $this->view('database', $data);
        } else {
            $name = str_replace($this->db_prefix . '_', '', $this->input->get('name'));
            $this->view('database', array('prefix' => $this->db_prefix, 'old_name' => $name, 'name' => $name));
        }
    }

    function delete() {
        $data['status'] = false;
        $data['msg'] = '';
        $names = $this->input->get_post('names');
        if ($names == null || sizeof($names) <= 0) {
            $data['msg'] = ('Invalid database names.');
            die(json_encode($data));
        }

        if (($count = $this->__remove_databases($names)) > 0) {
            $data['msg'] = "$count databases has been deleted successfully.";
        }

        $data['status'] = $count > 0;

        echo json_encode($data);
    }

    public function connect() {
        /* Need to have cookie visible from parent directory */
        session_set_cookie_params(0, '/', '', false);
        /* Create signon session */
        $session_name = 'PMASignonSession';
        session_name($session_name);

        session_start();
        $_SESSION['PMA_single_signon_user'] = $this->db_username;
        $_SESSION['PMA_single_signon_password'] = $this->db_password;

        include(APPPATH . '/config/database.php');
        $_SESSION['PMA_single_signon_host'] = $db['default']['hostname'];
        $_SESSION['PMA_single_signon_port'] = 3306;

        /* Close that session */
        session_write_close();
        header('Location: ' . $this->config->base_url() . 'phpmyadmin/index.php?' . time());
    }

    public function disconnect() {
        $session_name = 'PMASignonSession';
        session_name($session_name);
        session_start();
        session_destroy();
        echo '<script>window.close();</script>';
    }

}
