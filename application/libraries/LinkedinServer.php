<?php

session_start();
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'linkedin/linkedinoauth.php';

/**
 * Description of LinkedinServer
 *
 * @author DA Zhen
 */
class LinkedinServer {

    private $api_key = '<YOUR_API_KEY>';
    private $api_secret = '<YOUR_API_SECRET>';
    private $redirect_uri = '<YOUR_REDIRECT_URI>';
    private $client = null;
    private $access_token = null;

    public function __construct($config) {
        $this->api_key = $config['api_key'];
        $this->api_secret = $config['api_secret'];
        $this->redirect_uri = $config['redirect_uri'];
        $this->client = new LinkedinOAuth($this->api_key, $this->api_secret);
    }

    public function getAuthorizationURL() {

        return $this->client->getAuthorizationUrl($this->redirect_uri);
    }

    public function authenticate($code) {
        $access_token = $this->client->fetchAccessToken($code, $this->redirect_uri);
        $this->client->setAccessToken($access_token['access_token']);
        return $access_token;
    }

    public function set_access_token($access_token) {
        $this->access_token = ($access_token);
    }

    public function get_access_token() {
        return $this->access_token;
    }

    public function getAccountInfo() {
        return $this->client->fetch('/v1/people/~');
    }

}
