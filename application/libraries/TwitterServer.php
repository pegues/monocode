<?php

session_start();
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'twitter/twitteroauth.php';

/**
 * Description of TwitterServer
 *
 * @author DA Zhen
 */
class TwitterServer {

    private $consumer_key = '<YOUR_CONSUMER_KEY>';
    private $consumer_secret = '<YOUR_CONSUMER_SECRET>';
    private $redirect_uri = '<YOUR_REDIRECT_URI>';
    private $request_token = null;
    private $access_token = null;

    public function __construct($config) {
        $this->consumer_key = $config['consumer_key'];
        $this->consumer_secret = $config['consumer_secret'];
        $this->redirect_uri = $config['redirect_uri'];
    }

    public function get_request() {
        $connection = new TwitterOAuth($this->consumer_key, $this->consumer_secret);
        $request_token = $connection->getRequestToken($this->redirect_uri);
        $token = $request_token['oauth_token'];
        switch ($connection->http_code) {
            case 200:
                $url = $connection->getAuthorizeURL($token);
                return array('request_token' => $request_token, 'auth_url' => $url);
            default:
                /* Show notification if something went wrong. */
                echo 'Could not connect to Twitter. Refresh the page or try again later.';
                return null;
        }
    }

    public function set_request_token($request_token) {
        $this->request_token = $request_token;
    }

    public function get_request_token() {
        return $this->request_token;
    }

    public function authenticate($auth_token, $verifier) {
        if ($auth_token != $this->request_token['oauth_token']) {
            echo 'Old token. Please refresh the page.';
            return;
        }

        $connection = new TwitterOAuth($this->consumer_key, $this->consumer_secret, $this->request_token['oauth_token'], $this->request_token['oauth_token_secret']);

        /* Request access tokens from twitter */
        $this->access_token = $connection->getAccessToken($verifier);
        if ($connection->http_code == 200) {
            return $this->access_token;
        } else {
            echo 'Error. Please refresh the page';
            return null;
        }
    }

    public function set_access_token($access_token) {
        $this->access_token = ($access_token);
    }

    public function get_access_token() {
        return $this->access_token;
    }

    public function getAccountInfo() {
        $connection = new TwitterOAuth($this->consumer_key, $this->consumer_secret, $this->access_token['oauth_token'], $this->access_token['oauth_token_secret']);
        return $connection->get('account/verify_credentials');
    }

}
