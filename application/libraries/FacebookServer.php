<?php

session_start();
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'facebook/autoload.php';

use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\GraphUser;
use Facebook\FacebookRedirectLoginHelper;

/**
 * Description of FacebookServer
 *
 * @author DA Zhen
 */
class FacebookServer {

    private $app_id = '<YOUR_APP_ID>';
    private $app_secret = '<YOUR_APP_SECRET>';
    private $redirect_uri = '<YOUR_REDIRECT_URI>';
    private $helper = null;

    public function __construct($config) {
        $this->app_id = $config['app_id'];
        $this->app_secret = $config['app_secret'];
        $this->redirect_uri = $config['redirect_uri'];
        FacebookSession::setDefaultApplication($this->app_id, $this->app_secret);
    }

    public function getAccountInfo() {
        try {
            $session = $this->__get_login_helper()->getSessionFromRedirect();
            $request = (new FacebookRequest($session, 'GET', '/me'));
            $me = $request->execute()->getGraphObject(GraphUser::className());
            return $me;
        } catch (\Facebook\FacebookRequestException $ex) {
            
        } catch (\Exception $ex) {
            
        }
        
        return null;
    }

    public function getAuthenticationURL() {
        return $this->__get_login_helper()->getLoginUrl();
    }

    public function __get_login_helper() {
        if ($this->helper == null) {
            $this->helper = new FacebookRedirectLoginHelper($this->redirect_uri, $this->app_id, $this->app_secret);
        }

        return $this->helper;
    }

}
