<?php

session_start();
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'google/autoload.php';

/**
 * Description of GoogleServer
 *
 * @author DA Zhen
 */
class GoogleServer {

    private $client_id = '<YOUR_CLIENT_ID>';
    private $client_secret = '<YOUR_CLIENT_SECRET>';
    private $redirect_uri = '<YOUR_REDIRECT_URI>';
    private $client = null;

    public function __construct($config) {
        $this->client_id = $config['client_id'];
        $this->client_secret = $config['client_secret'];
        $this->redirect_uri = $config['redirect_uri'];
        $client = new Google_Client();
        $client->setClientId($this->client_id);
        $client->setClientSecret($this->client_secret);
        $client->setRedirectUri($this->redirect_uri);
        $client->addScope("https://www.googleapis.com/auth/userinfo.profile");
        $client->addScope("https://www.googleapis.com/auth/userinfo.email");
        $this->client = $client;
    }

    public function set_access_token($access_token) {
        $this->client->setAccessToken($access_token);
    }

    public function get_access_token() {
        return $this->client->getAccessToken();
    }

    public function authenticate($code) {
        $this->client->authenticate($code);
        return $this->client->getAccessToken();
    }

    public function getAuthenticationURL() {
        return $this->client->createAuthUrl();
    }

    public function getAccountInfo() {
        $service = new Google_Service_Oauth2($this->client);
        return ($service->userinfo->get());
    }

}
