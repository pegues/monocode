<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

define('ACTION_CONTAINERS', 'containers');
define('ACTION_CONTAINER', 'container');
define('ACTION_CREATE', 'create');
define('ACTION_RENAME', 'rename');
define('ACTION_DELETE', 'delete');
define('ACTION_START', 'start');
define('ACTION_STOP', 'stop');
define('ACTION_UPDATE', 'update');

class SandboxServer {

    private $url = '';
    private $login = '';
    private $token = '';
    private $action = '';
    private $error = null;
    public $__debugging = false;

    public function __construct($config) {
        $this->url = $config['url'];
        $this->login = $config['login'];
        $this->token = $config['token'];
    }

    function setAction($action) {
        $this->action = $action;
        return $this;
    }

    function getAction() {
        return $this->action;
    }

    function setError($error) {
        $this->error = $error;
    }

    function getError() {
        return $this->error;
    }

    function clearError() {
        $this->error = null;
    }

    function query($params = array(), $action = null, $isPost = true) {
        if (!empty($action)) {
            $this->setAction($action);
        }
        if (empty($this->action)) {
            return false;
        }

        $data = json_decode($this->__query($this->action, $params, $isPost));

        if (!$data) {
            $error = new stdClass();
            $error->code = 0;
            $error->msg = 'Unknown error.';
            $this->setError($error);
        } else if (isset($data->status) && $data->status != 'success') {
            $error = new stdClass();
            $error->code = 101;
            $error->msg = $data->reason;
            $this->setError($error);
            return false;
        }

        return $data;
    }

    function __query($action, $params, $isPost = true) {
        $url = $this->url . $action;
        $params = http_build_query($params);
        $this->__log('URL: ' . $url . '<br />');
        $this->__log('Params: ' . ($params) . '<br />');

        if (!$isPost) {
            $url .= '?' . $params;
            $this->__log('GET URL: ' . $url . '<br />');
        }
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        if ($isPost) {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
        }
        $result = curl_exec($curl); // run the whole process
        curl_close($curl);

        $this->__log('Result: ' . $result . '<br />');
        return $result;
    }

    private function __log($msg) {
        if ($this->__debugging) {
            print_r($msg);
        }
    }

    function containers() {
        return $this->setAction(ACTION_CONTAINERS)->query();
    }

    function container($domain) {
        if ($result = $this->setAction(ACTION_CONTAINER)->query(array('domain' => $domain), null, false)) {
            return $result->container;
        }

        return false;
    }

    function create($user, $workspace, $userId, $workspaceId) {
        if ($result = $this->setAction(ACTION_CREATE)->query(array('user' => $user, 'workspace' => $workspace, 'userid' => $userId, 'workspaceid' => $workspaceId))) {
            return $result->domain;
        }

        return false;
    }

    function rename($domain, $user, $workspace) {
        if ($result = $this->setAction(ACTION_RENAME)->query(array('domain' => $domain, 'user' => $user, 'workspace' => $workspace))) {
            return $result->domain;
        }

        return false;
    }

    function delete($domain) {
        return $this->setAction(ACTION_DELETE)->query(array('domain' => $domain));
    }

    function start($domain, $reset = false, $userId = null, $workspaceId = null) {
        $data = array('domain' => $domain);
        if ($reset) {
            $data = array_merge($data, array('reset' => $reset, 'userid' => $userId, 'workspaceid' => $workspaceId));
        }
        return $this->setAction(ACTION_START)->query($data);
    }

    function stop($domain, $reset = false) {
        $data = array('domain' => $domain);
        if ($reset) {
            $data['reset'] = $reset;
        }
        return $this->setAction(ACTION_STOP)->query($data);
    }

    function update($path) {
        return $this->setAction(ACTION_UPDATE)->query(array('s3path' => $path));
    }

}

?>
