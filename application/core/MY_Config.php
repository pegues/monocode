<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MY_Config
 *
 * @author zhen
 */
class MY_Config extends CI_Config {

    public function __construct() {
        if (isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
            $_SERVER['HTTPS'] = $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' ? 'on' : 'off';
        }

        parent::__construct();
    }

}
