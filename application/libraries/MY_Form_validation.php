<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once(SYSDIR . "/libraries/Form_validation.php");

class MY_Form_validation extends CI_Form_validation {

    public function error_array() {
        return $this->_error_messages;
    }
    
    public function clear() {
        $this->_error_array = null;
        $this->_error_messages = null;
    }

}
