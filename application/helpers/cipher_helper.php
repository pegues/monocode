<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cipher_helper
 *
 * @author DA Zhen
 */
define('PASSPHRASE', 'scedtor!@#$%^&**');

class Cipher {

    public static function encrypt($data) {
        try {
            $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC); //create a random initialization vector to use with CBC encoding
            $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
            $key = PASSPHRASE;
            return base64_encode($iv . mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $data, MCRYPT_MODE_CBC, $iv));
        } catch (Exception $ex) {
            return '';
        }
    }

    public static function decrypt($data) {
        try {
            $data = base64_decode($data);
            $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC); //retrieves the IV, iv_size should be created using mcrypt_get_iv_size()
            $iv = substr($data, 0, $iv_size);
            $data = substr($data, $iv_size); //retrieves the cipher text (everything except the $iv_size in the front)
            $key = PASSPHRASE;
            return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $data, MCRYPT_MODE_CBC, $iv), chr(0));
        } catch (Exception $ex) {
            return '';
        }
    }

}
