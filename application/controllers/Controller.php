<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Controller
 *
 * @author DA Zhen
 */
class Controller extends CI_Controller {

    //page layout
    protected $__environment = 'frontend';
    protected $__layout = '';
    protected $__module_group = '';
    protected $__module_type = MODULE_TYPE_HOME;
    protected $__module_name = MODULE_NAME_HOME;
    //authentication & auhorization
    protected $__need_authentication = true;
    protected $__authorized_actions = null;
    protected $__unauthorized_actions = null;
    protected $__account = null;
    protected $__page_title = '';
    //request & response
    protected $__hasError = false;
    protected $__messages = null;
    protected $__adding_message = true;
    protected $__requestData = null;
    protected $__responseData = null;
    protected $__scripts = null;
    //model & data
    protected $__model = null;
    protected $__pagination_link = null;
    protected $__entity = null;
    protected $__entities = null;
    protected $__total_count = 0;
    //codeeditor specific
    protected $__record_active_time = true; //will be set to false when is access from cron
    protected $__fetching_last_active_time = false; //will be set to false when is access from cron
    protected $__settings = null;
    protected $__options = null;
    protected $__sessionLifeTime = 0;
    protected $__cacheTime = 0;
    protected $__storage_type = 'local';
    //db related
    protected $db_username;
    protected $db_password;
    protected $db_prefix;
    //server & client ip
    protected $__server_ip;
    protected $__client_ip;

    function __construct() {
        parent::__construct();

        if ($this->__fetching_last_active_time = strpos($_SERVER['REQUEST_URI'], '/editor/online') > -1) {
            $this->__record_active_time = false;
        }

        $time = gmdate('Y-m-d H:i:s');
        $this->__init();

        if ($this->__account = $this->session->userdata('account')) {
            if ($this->__record_active_time) {
                $this->setLastActiveTime($time);
                $this->session->set_userdata('sessionLifeTime', $this->__sessionLifeTime);
            }
        }

        //$this->__log_access_info();
        $this->__check_login();

        $this->__load_settings();
        $this->__load_options();
    }

    private function __init() {
        $this->__server_ip = $_SERVER['SERVER_ADDR'] != '::1' ? $_SERVER['SERVER_ADDR'] : '127.0.0.1';
        $this->__client_ip = $_SERVER['REMOTE_ADDR'] != '::1' ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1';
        $model = $this->____load_model('WebsiteSettingModel');
        $sessionHandler = $model->getSessionHandler();
        $this->__sessionLifeTime = ((int) $model->getUserSessionLifetime()) * 60;
        if ((strcmp($sessionHandler, 'None') == 0)) {
            $this->__sessionLifeTime = -1;
        }
        if (!empty($sessionHandler)) {
            $sessionHandler = strtolower($sessionHandler);
        }
        $sesionUpdateTime = 0;
        if ($this->__sessionLifeTime > 0) {
            if ($this->__fetching_last_active_time) {
                $sesionUpdateTime = $this->__sessionLifeTime * 2;
            } else {
                $sesionUpdateTime = $this->__sessionLifeTime / 2;
            }
        }
        $this->load->library('Session', array('sess_expiration' => $this->__sessionLifeTime, 'sess_use_database' => (strcmp($sessionHandler, 'Database') == 0), 'sess_time_to_update' => $sesionUpdateTime));

        //Cache options
        $cacheEnabled = $model->getCacheEnabled();
        $cacheHandler = $model->getCacheHandler();
        $this->__cacheTime = $model->getCacheTime();

        if (!empty($cacheHandler)) {
            $cacheHandler = strtolower($cacheHandler);
        }
        if ($cacheEnabled) {
            if (in_array($cacheHandler, array('file', 'apc', 'memcache'))) {
                $this->load->driver('cache', array('adapter' => $cacheHandler));
            }
        }
    }

    protected function isLocalRequest() {
        return ($this->__server_ip == $this->__client_ip);
    }

    protected function setLastActiveTime($time) {
        $this->__save_account(array('last_active_time' => $time));
    }

    protected function getOnlineTime() {
        if (!$this->__account) {
            return -1;
        }
        return $this->session->userdata('sessionLifeTime') - (strtotime(gmdate('Y-m-d H:i:s')) - strtotime($this->__account->last_active_time));
    }

    protected function setCache($name, $value) {
        if ($this->cache) {
            $this->cache->save($name, $value, $this->__cacheTime);
            return true;
        }

        return true;
    }

    protected function getCache($name) {
        if ($this->cache) {
            return $this->cache->get($name);
        }

        return null;
    }

    protected function __check_login($redirect = true) {
        $action = $this->uri->rsegments[2];
        if (!$this->__need_authentication) {
            if ($this->__authorized_actions == null || !in_array($action, $this->__authorized_actions)) {
                return true;
            }
        } else {
            if ($this->__unauthorized_actions && in_array($action, $this->__unauthorized_actions)) {
                return true;
            }
        }

        if ($this->__account == null) {
            if ($redirect) {
                if ($this->isAjax()) {
                    $this->addErrorMessage('Your session has expired. Please login again.');
                    $this->ajaxResponse(array('sessionExpired' => true, 'loginUrl' => $this->__get_login_url()));
                } else {
                    $returnUrl = current_url();
                    $this->redirect($this->__get_login_url() . '?returnUrl=' . urlencode($returnUrl));
                }
            }
            return false;
        }

        return true;
    }

    protected function isLoggedIn() {
        return $this->__account != null;
    }

    protected function isAccountActive() {
        return $this->__account->status == USER_STATUS_ACTIVE;
    }

    protected function isAccountSuspended() {
        return $this->__account->status == USER_STATUS_SUSPENDED;
    }

    protected function __load_settings() {
        $list = $this->____load_model('WebsiteSettingModel')->search();
        $settings = new stdClass();
        if (count($list) > 0) {
            foreach ($list as $setting) {
                $field_name = $setting->field_name;
                $settings->$field_name = $setting->field_value;
            }
        }
        $this->__settings = $settings;
    }

    protected function __get_option_model() {
        return $this->____load_model('OptionModel');
    }

    protected function __load_options() {
        $options = array();
        $list = $this->__get_option_model()->search();
        foreach ($list as $option) {
            $options[$option->option_key] = $option->option_value;
        }
        $user_id = (int) $this->session->userdata('user_id');
        if ($user_id > 0) {
            $list = $this->__get_option_model()->search(array('user_id' => $user_id));
            foreach ($list as $option) {
                $options[$option->option_key] = $option->option_value;
            }
        }
        $this->__options = $options;
        $this->__storage_type = isset($this->__options['editor_work_space_storage']) ? $this->__options['editor_work_space_storage'] : 'local';
    }

    protected function __save_option($name, $value) {
        $this->__options[$name] = $value;
        $this->__get_option_model()->update($name, $value, $this->__account->id);
    }

    public function __get_option($name) {
        return isset($this->__options[$name]) ? $this->__options[$name] : null;
    }

    protected function isStorageAWS() {
        return $this->__storage_type != 'local';
    }

    protected function isStorageLocal() {
        return $this->__storage_type == 'local' || !$this->isStorageAWS();
    }

    // AWS Server Configuration
    protected function getAWSServer() {
        if (!isset($this->awsServer)) {
            $access_key = $this->__options['editor_aws_access_key'];
            $secret_key = $this->__options['editor_aws_secret_key'];
            $bucket_name = $this->__options['editor_aws_bucket'];

            $config = array(
                'access_key' => $access_key,
                'secret_key' => $secret_key,
                'bucket_name' => $bucket_name,
            );
            $this->load->library('AWSServer', $config, 'awsServer');
            $this->awsServer->setController($this);
        }
        return $this->awsServer;
    }

    protected function getAWSFileModel() {
        if (!isset($this->__aws_file_model)) {
            $this->__aws_file_model = $this->____load_model('AWSFileModel');
        }

        return $this->__aws_file_model;
    }

    protected function getAWSFileList($path, $strict = false, $type = '') {
        $user_id = $this->__account->id;
        return $this->getAWSFileModel()->search(array('user_id' => $user_id, 'path' => $path, 'strict' => $strict, 'type' => $type));
    }

    protected function getAWSFileCount($path, $strict = false, $user_id = 0) {
        return $this->getAWSFileModel()->__total_count(array('user_id' => $user_id, 'path' => $path, 'strict' => $strict, 'type' => 'file'));
    }

    protected function getAWSDiskUsage($path, $strict = false, $user_id = 0) {
        return $this->getAWSFileModel()->__total_size(array('user_id' => $user_id, 'path' => $path, 'strict' => $strict, 'type' => 'file'));
    }

    function __filename2id($data, $enc = true) {
        if ($enc == true) {
            $output = base64_encode(convert_uuencode($data));
        } else {
            $output = convert_uudecode(base64_decode($data));
        }
        $result = preg_replace("/[^a-zA-Z0-9]+/", "", $output);

        return strtolower($result);
    }

    function __is_image($name) {
        $ext = preg_replace('/^.*\./', '', $name);
        if (in_array($ext, $this->__image_extensions())) {
            return true;
        } else {
            return false;
        }
    }

    // Image Extensions
    function __image_extensions() {
        return array('bmp', 'gif', 'jpeg', 'jpg', 'png');
    }

    protected function getWorkpath() {
        if ($this->isStorageLocal()) {
            return str_replace("\\", "/", $this->__get_local_work_path());
        } else {
            return '/';
        }
    }

    protected function __get_local_work_path() {
        $path = $this->__options['editor_work_space_path'];
        if (strcmp(substr($path, strlen($path) - 1), '/') != 0) {
            $path .= '/';
        }
        return $path;
    }

    //workshop functions
    protected function getWorkshop() {
        return $this->getWorkpath() . $this->__get_workshop() . '/';
    }

    protected function __get_workshop() {
        return $this->__account->workshop;
    }

    protected function __create_workshop($name) {
        $workshop = $this->getWorkpath() . $name . '/';
        if ($this->isStorageLocal()) {
            $this->mkdir($workshop);
        }
    }

    protected function __remove_workshop() {
        $this->__delete_file_or_dir($this->getWorkshop());
    }

    //workspace functions
    protected function getActiveWorkspace() {
        return $this->getWorkshop() . $this->__get_active_workspace_directory() . '/';
    }

    protected function __get_active_workspace() {
        $workspaces = $this->__get_workspaces();
        if ($workspaces && sizeof($workspaces) > 0) {
            foreach ($workspaces as $workspace) {
                if (isset($workspace['ws_active']) && $workspace['ws_active'] == 'true') {
                    return $workspace;
                }
            }
        }

        return null;
    }

    protected function __get_active_workspace_directory() {
        if ($workspace = $this->__get_active_workspace()) {
            return $workspace['ws_directory'];
        }

        return null;
    }

    protected function __get_workspaces() {
        return isset($this->__options['ws']) && $this->__options['ws'] ? json_decode($this->__options['ws'], true) : null;
    }

    protected function __get_workspace($id) {
        $workspaces = $this->__get_workspaces();
        if ($workspaces && isset($workspaces[$id])) {
            return $workspaces[$id];
        }

        return null;
    }

    protected function __get_workspace_name($id) {
        if (!$id) {
            return null;
        }

        if ($workspace = $this->__get_workspace($id)) {
            return $workspace['ws_name'];
        }

        return null;
    }

    protected function __remove_workspaces($wss) {
        if (!is_array($wss)) {
            $wss = array($wss);
        }

        $workspaces = $this->__get_workspaces();
        $count = 0;
        $need_activation = false;
        foreach ($wss as $ws) {
            $this->__delete_file_or_dir($this->getWorkshop() . $ws . '/');
            $workspace = $workspaces[$ws];
            if ($this->isStorageAWS() && isset($workspace['ws_domain']) && $workspace['ws_domain']) {
                if (!$this->getSandboxServer()->delete($workspace['ws_domain'])) {
                    $this->processSandboxServerError();
                }
            }
            if ($workspace['ws_active']) {
                $need_activation = true;
            }
            unset($workspaces[$ws]);
            $count ++;
        }
        if ($need_activation && sizeof($workspaces) > 0) {
            $keys = array_keys($workspaces);
            $workspaces[$keys[0]]['ws_active'] = true;
        }
        $this->__save_option('ws', json_encode($workspaces, true));

        return $count;
    }

    protected function __add_workspace($name, $type = 'php') {
        if (!($workspaces = $this->__get_workspaces())) {
            $workspaces = array();
        }

        $total_count = (int) $this->__get_feature('work_space');
        if (sizeof($workspaces) >= $total_count) {
            $this->addErrorMessage("You've exceeded the maximum number of workspaces ($total_count workspaces) you can create.");
            return null;
        }

        foreach ($workspaces as $ws) {
            if ($ws['ws_name'] == $name) {
                $this->addErrorMessage("The workspace with same name already exists. Please enter another name.");
                return null;
            }
        }

        $id = 'ws-' . UUID::v4();
        $workspace = array(
            'ws_directory' => $id,
            'ws_name' => $name,
            'ws_type' => $type,
            'ws_status' => 'enable',
            'ws_active' => (sizeof($workspaces) == 0)
        );

        if ($this->isStorageLocal()) {
            $this->mkdir($this->getWorkshop() . $id);
        } else {
            if ($domain = $this->getSandboxServer()->create($this->__account->username, $workspace['ws_name'], $this->__account->workshop, $workspace['ws_directory'])) {
                $workspace['ws_domain'] = $domain;
            } else {
                $this->addErrorMessage('Failed to create sandbox domain for the workspace.');
                $this->processSandboxServerError();
            }
        }

        $workspaces[$id] = $workspace;
        $this->__save_option('ws', json_encode($workspaces, true));

        return $workspace;
    }

    public function syncS3FileWithSandbox($file) {
        $file = ltrim($file, '/');
        $workspace = $this->__get_workspace($this->fetchWorkspaceDirectory($file));
        if ($workspace && isset($workspace['ws_domain']) && $workspace['ws_domain']) {
            if (!$this->getSandboxServer()->update($file)) {
                $this->processSandboxServerError();
            }
        }
    }

    protected function startSandboxes() {
        if ($this->__get_feature('allow_sandbox_live')) {
            return;
        }

        $workspaces = $this->__get_workspaces();
        if ($workspaces && sizeof($workspaces) > 0) {
            foreach ($workspaces as $workspace) {
                if (isset($workspace['ws_domain']) && $workspace['ws_domain']) {
                    $this->getSandboxServer()->start($workspace['ws_domain']);
                }
            }
        }
    }

    protected function stopSandboxes() {
        if ($this->__get_feature('allow_sandbox_live')) {
            return;
        }

        $workspaces = $this->__get_workspaces();
        if ($workspaces && sizeof($workspaces) > 0) {
            foreach ($workspaces as $workspace) {
                if (isset($workspace['ws_domain']) && $workspace['ws_domain']) {
                    $this->getSandboxServer()->stop($workspace['ws_domain']);
                }
            }
        }
    }

    function fetchWorkspaceDirectory($file) {
        $w = explode('/', $file);
        if (sizeof($w) > 0) {
            return $w[1];
        }

        return null;
    }

    // Sandbox Server Configurations
    protected function getSandboxServer() {
        if (!isset($this->sandboxServer)) {
            $config = array(
                'url' => 'http://52.0.170.95:9292/api/',
                'login' => '',
                'token' => ''
            );
            $this->load->library('SandboxServer', $config, 'sandboxServer');
        }
        return $this->sandboxServer;
    }

    protected function processSandboxServerError() {
        $this->addErrorMessage('Sandbox Error: ' . $this->getSandboxServer()->getError()->msg);
    }

    protected function sandboxTest() {
        $this->getSandboxServer()->__debugging = true;
        $type = $this->input->get('type');
        if ($type == 'list') {
            var_dump($this->getSandboxServer()->containers());
        } else if ($type == 'create') {
            $workspace = 'ws-' . time();
            $this->getSandboxServer()->create('zhen', $workspace, 'user-zhen', $workspace);
        } else if ($type == 'rename') {
            $workspace = 'ws-' . time();
            $this->getSandboxServer()->rename($this->input->get('domain'), 'zhen', $workspace);
        } else if ($type == 'delete') {
            $this->getSandboxServer()->delete($this->input->get('domain'));
        } else if ($type == 'update') {
            $path = 'user-zhen/ws-1426398040/index.php';
            $content = 'Hi, This is php file. updated: ' . time() . '<br />';
            $this->getAWSServer()->upload($path, $content);
            $this->getSandboxServer()->update($path);
        }
    }

    function __delete_file_or_dir($path) {
        if ($this->isStorageLocal()) {
            $this->__delete_file_or_dir_local($path);
        } else {
            $this->__delete_file_or_dir_aws($path);
        }
    }

    function __delete_file_or_dir_local($path) {
        if (is_dir($path) === true) {
            $files = array_diff(scandir($path), array('.', '..'));
            if (count($files) > 0) {
                foreach ($files as $file) {
                    $this->__delete_file_or_dir_local(realpath($path) . '/' . $file);
                }
            }

            return rmdir($path);
        } else if (is_file($path) === true) {
            chmod($path, 0750);
            unlink($path);
        }
    }

    function __delete_file_or_dir_aws($path) {
        $path = rtrim($path, '/');
        $file = $this->getAWSFileModel()->getByPath($path);
        if (!$file || $file->type == 'dir') {
            $files = $this->getAWSFileList($path . '/');
            if (sizeof($files) > 0) {
                foreach ($files as $f) {
                    if ($f->type != 'dir') {
                        $this->getAWSServer()->delete($f->path . $f->name);
                    }
                    $this->getAWSFileModel()->delete($f->id);
                }
            }
        }
        if ($file) {
            if ($file->type != 'dir') {
                $this->getAWSServer()->delete($path);
            }
            $this->getAWSFileModel()->delete($file->id);
        }
    }

    protected function __get_db_user() {
        $db_user = isset($this->__options['db_user']) ? json_decode($this->__options['db_user'], true) : null;
        if ($db_user == null) {
            $db_username = 'sce-' . substr(str_replace('-', '', $this->__get_workshop()), 5, 12);
            $db_password = $this->__gen_new_pass();
            $db_prefix = $db_username;
            $ret = $this->__create_db_user($db_username, $db_password);
            if ($ret) {
                $db_user = array('db_username' => $db_username, 'db_password' => Cipher::encrypt($db_password), 'db_prefix' => $db_prefix);
                $this->__options['db_user'] = json_encode($db_user);
                $this->load->model('OptionModel');
                $this->OptionModel->update('db_user', $this->__options['db_user'], $this->session->userdata('user_id'));
            }
        }
        $db_user['db_password'] = Cipher::decrypt($db_user['db_password']);

        return $db_user;
    }

    protected function __create_db_user($db_username, $db_password) {
        if ($this->__server_ip == '127.0.0.1') {
            $domain = 'localhost';
        } else {
            $domain = '%';
        }
        if ($this->db->query("SELECT * FROM mysql.user WHERE User='$db_username' and Host='$domain'")->num_rows() > 0) {
            return true;
        } else {
            $query = "create user '$db_username'@'$domain' identified by '$db_password'";
            return $this->db->query($query);
        }
    }

    function __gen_new_pass() {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        return substr(str_shuffle($chars), 0, 8);
    }

    protected function __load_db_user() {
        $db_user = $this->__get_db_user();

        $this->__fillObject($this, $db_user, null, true);
    }

    protected function __get_databases() {
        $query = "show databases where `Database` LIKE ?;";
        $ret = $this->db->query($query, '%' . $this->db_prefix . '_%');
        $list = $ret->result_array();
        $data = array();
        if (sizeof($list) > 0) {
            foreach ($list as $db) {
                $data[] = $db['Database'];
            }
        }

        return $data;
    }

    protected function __remove_databases($databases) {
        $count = 0;
        foreach ($databases as $db) {
            $query = "drop database `$db`;";
            if (($ret = $this->db->query($query))) {
                $count ++;
            }
        }

        return $count;
    }

    function __clear_feature() {
        $this->__features = null;
    }

    function __get_feature($feature_name, $user_id = -1) {
        if (!isset($this->__features)) {

            $this->db->select('f.feature_id, f.ftp, f.work_space, f.database, f.allow_ftp, f.allow_svn, f.allow_template, f.allow_widget, f.file_count_limit, f.disk_space_limit, f.disk_space_unit, f.allow_sandbox_live');
            $this->db->join('user_type ut', 'user.user_type=ut.user_type_id');
            $this->db->join('features f', 'ut.feature_id=f.feature_id');
            $this->db->where('user.user_id', $user_id == -1 ? $this->__account->id : $user_id);
            $ret = $this->db->get('user')->result_array();
            if (sizeof($ret) > 0) {
                $this->__features = $ret[0];
            }
        }

        if (isset($this->__features) && $this->__features != null && isset($this->__features[$feature_name])) {
            return $this->__features[$feature_name];
        }

        return null;
    }

    protected function mkdir($path) {
        if (is_dir($path)) {
            return;
        }

        $umask = umask(0);
        mkdir($path, 0777);
        umask($umask);
    }

    protected function getTempDir() {
        $path = realpath(APPPATH) . '/../' . TEMP_DIR . '/';
        $path = str_replace('\\', '/', $path);
        if (!is_dir($path)) {
            $this->mkdir($path);
        }

        return $path;
    }

    protected function getTempFileName($ext = null) {
        return $this->getTempDir() . $this->__get_temp_file_name($ext);
    }

    protected function __get_temp_file_name($ext = null) {
        $filename = UUID::v4();
        if ($ext != null && $ext != '') {
            $filename .= '.' . $ext;
        }

        return $filename;
    }

    protected function getArchiveDir() {
        $path = realpath(APPPATH) . '/../' . ARCHIVE_DIR . '/';
        $path = str_replace('\\', '/', $path);
        if (!is_dir($path)) {
            $this->mkdir($path);
        }

        return $path;
    }

    protected function filterPath($e) {
        return preg_replace('#/+#', '/', $e);
    }

    protected function __load_commands() {
        $commands = $this->____load_model('CommandModel')->search();

        if (isset($this->__options['commands'])) {
            $mycommands = json_decode($this->__options['commands']);
            foreach ($mycommands as $mycommand) {
                foreach ($commands as $command) {
                    if ($command->name == $mycommand->name) {
                        $command->shortcut_key = $mycommand->shortcut_key;
                        $command->shortcut_key_mac = $mycommand->shortcut_key_mac;
                        break;
                    }
                }
            }
        }

        return $commands;
    }

    function validateFilesAndSpaces($cron = false) {
        if (!$this->checkFileCount(0)) {
            if ($this->isAccountActive()) {
                if (!$this->__account->file_disk_usage_alert || $this->__account->file_disk_usage_alert == '') {
                    $this->__save_account(array(
                        'file_disk_usage_alert' => gmdate('Y-m-d H:i:s')
                    ));
                    if ($cron) {
                        $this->logDebug($this->__account->user_name . ': You have too many files.');
                    }
                } else {
                    $days = (strtotime(gmdate('Y-m-d H:i:s')) - strtotime($this->__account->file_disk_usage_alert)) / (3600 * 24);
                    $days = (int) $days;
                    //$this->logDebug('days: ' . $days);
                    if ($days >= 3) {
                        $this->suspendUser($this->__account, SUSPENDED_REASON_TOO_MANY_FILES);
                        $this->sendMailUsingTemplate($this->__account, 'account-space-suspended-notice');
                        $this->notify($this->__account, "Your account has been suspended because you have too many files. Please delete the necessary number of files to restore your account to full functionality.", 'account/files');
                        if ($cron) {
                            $this->logDebug($this->__account->user_name . ': Your account has been suspended  because you have too many files.');
                        }
                    } else if ($days == 2) {
                        $this->sendMailUsingTemplate($this->__account, 'account-files-second-notice');
                        $this->notify($this->__account, "You have too many files (2). Please delete the necessary number of files.", 'account/files');
                        if ($cron) {
                            $this->logDebug($this->__account->user_name . ': You have too many files. (2)');
                        }
                    } else if ($days == 1) {
                        $this->sendMailUsingTemplate($this->__account, 'account-files-first-notice');
                        $this->notify($this->__account, "You have too many files. Please delete the necessary number of files. (1)", 'account/files');
                        if ($cron) {
                            $this->logDebug($this->__account->user_name . ': You have too many files. (1)');
                        }
                    }
                }
            }
            return false;
        } else if (!$this->checkDiskUsage(0)) {
            if ($this->isAccountActive()) {
                if (!$this->__account->file_disk_usage_alert || $this->__account->file_disk_usage_alert == '') {
                    $this->__save_account(array(
                        'file_disk_usage_alert' => gmdate('Y-m-d H:i:s')
                    ));
                    if ($cron) {
                        $this->logDebug($this->__account->user_name . ': You\'re using too much space.');
                    }
                } else {
                    $days = (strtotime(gmdate('Y-m-d H:i:s')) - strtotime($this->__account->file_disk_usage_alert)) / (3600 * 24);
                    $days = (int) $days;
                    //$this->logDebug('days: ' . $days);
                    if ($days >= 3) {
                        $this->suspendUser($this->__account, SUSPENDED_REASON_TOO_MUCH_SPACES);
                        $this->sendMailUsingTemplate($this->__account, 'account-space-suspended-notice');
                        $this->notify($this->__account, "Your account has been suspended because you're using more space than your account allows. Please delete the necessary number of files to restore your account to full functionality.", 'account/files');
                        if ($cron) {
                            $this->logDebug($this->__account->user_name . ': Your account has been suspended because you\'re using more space than your account allows.');
                        }
                    } else if ($days == 2) {
                        $this->sendMailUsingTemplate($this->__account, 'account-space-second-notice');
                        $this->notify($this->__account, "You have too many files (2). Please delete the necessary number of files.", 'account/files');
                        if ($cron) {
                            $this->logDebug($this->__account->user_name . ': You\'re using too much space. (2)');
                        }
                    } else if ($days == 1) {
                        $this->sendMailUsingTemplate($this->__account, 'account-space-first-notice');
                        $this->notify($this->__account, "You have too many files. Please delete the necessary number of files. (1)", 'account/files');
                        if ($cron) {
                            $this->logDebug($this->__account->user_name . ': You\'re using too much space. (1)');
                        }
                    }
                }
            }
            return false;
        } else if ($this->isAccountSuspended()) {
            $this->activateUser($this->__account);
            $this->__save_account(array(
                'file_disk_usage_alert' => null
            ));
            $this->notify($this->__account, "Your account has now been activated.");
        }

        return true;
    }

    function checkFileCount($countToAdd) {
        $fileCount = $this->getFileCount();
        $limit = $this->__get_feature('file_count_limit');
        if ($limit > 0 && $fileCount + $countToAdd > $limit) {
            if ($countToAdd > 0) {
                $this->addErrorMessage("The count of files you can add has been limited.");
            }
            return false;
        }

        return true;
    }

    function checkDiskUsage($spaceToAdd) {
        $diskUsage = $this->getDiskUsage();

        $limit = $this->__get_feature('disk_space_limit');
        $unit = $this->__get_feature('disk_space_unit');
        $unitSizes = array('kb' => 1024, 'mb' => 1024 * 1024, 'gb' => 1024 * 1024 * 1024);
        if (isset($unitSizes[$unit])) {
            $limit *= $unitSizes[$unit];
        }

        if ($limit > 0 && $diskUsage + $spaceToAdd > $limit) {
            if ($spaceToAdd > 0) {
                $this->addErrorMessage("The disk space you can use has been limited.");
            }
            return false;
        }

        return true;
    }

    function getFileCount($url = '', $strict = false) {
        return $this->__getFileCount($url, $strict, $this->__account->uid);
    }

    function __getFileCount($url = '', $strict = false, $user_id = 0) {
        $base = $this->getWorkpath();
        if ($user_id > 0) {
            $base = $this->getWorkshop();
        }
        $path = $base . $url;
        if ($this->isStorageLocal()) {
            if (!file_exists($path)) {
                return 0;
            }
            if (is_file($path)) {
                return 1;
            }

            $files = array_diff(scandir($path), array('.', '..'));
            $count = 0;
            foreach ($files as $file) {
                $count += $this->__getFileCount($url . '/' . $file, $strict, $user_id);
            }

            return $count;
        } else {
            return $this->getAWSFileCount($path, $strict, $user_id);
        }
    }

    function getDiskUsage($url = '', $strict = false, $user_id = 0) {
        $path = $this->getWorkshop() . $url;
        if ($this->isStorageLocal()) {
            if (!file_exists($path)) {
                return 0;
            }
            if (is_file($path)) {
                return filesize($path);
            }
            $files = array_diff(scandir($path), array('.', '..'));
            $usage = 0;
            if (sizeof($files) > 0) {
                foreach ($files as $file) {
                    $usage += $this->getDiskUsage($url . '/' . $file, $strict, $user_id);
                }
            }

            return $usage;
        } else {
            return $this->getAWSDiskUsage($path, $strict, $user_id);
        }
    }

    protected function __load_model($model_name) {
        $this->__model = $this->____load_model($model_name);
    }

    protected function ____load_model($model_name) {
        if (isset($this->$model_name)) {
            return $this->$model_name;
        }
        $this->load->model($model_name);
        $this->$model_name->__set_user($this->__account);
        return $this->$model_name;
    }

    protected function __set_account($user, $force = true) {

        $user->id = $user->user_id;

        $user->uid = $user->user_id;    //[DEPRECATED]

        $user->username = $user->user_name;
        $user->display_name = $user->first_name . ' ' . $user->last_name;

        //for displaying
        $user->address = ''; //fixing problem of login on live server
        //for editor
        //check if workshop exists
        if ($force && ($user->workshop == null || $user->workshop == '')) {
            $user->workshop = 'user-' . UUID::v4();
            $this->getUserModel()->save(array('user_id' => $user->user_id, 'workshop' => $user->workshop));
        }

        $this->__account = $user;
        $this->session->set_userdata('account', $user);

        //[DEPRECATED]
        $this->session->set_userdata("loggedin", 'true');
        $this->session->set_userdata("user_name", $user->user_name);
        $this->session->set_userdata("user_id", $user->user_id);
        $this->session->set_userdata("user_type", $user->user_type);
        $this->session->set_userdata("user_email", $user->email);
        $this->session->set_userdata("user_workshop", $user->workshop);

        $this->__load_options();

        if ($force) {
            $this->__create_workshop($user->workshop);

            //check if active workspace directory exists for local system
            if ($this->isStorageLocal()) {
                if (!is_dir($this->getActiveWorkspace())) {
                    $this->mkdir($this->getActiveWorkspace());
                }
            }
        }

        return TRUE;
    }

    protected function __save_account($user) {
        $user = (array) $user;
        $this->__fillObject($this->__account, $user);
        $user['user_id'] = $this->__account->id;
        $this->__set_account($this->__account);
        return $this->getUserModel()->save($user);
    }

    protected function getUserModel() {
        return $this->____load_model('UserModel');
    }

    protected function __get_login_url() {
        return str_replace('http://', 'https://', base_url() . 'guest/login');
    }

    protected function __set_page_title($title) {
        $this->__page_title = $title;
    }

    protected function __get_base_url() {
        return base_url() . $this->getModuleName() . '/';
    }

    //Form Validation
    protected function validateForm($validateCaptcha = false) {
        $result = true;
        if (!DEBUG) {
            if ($validateCaptcha && !$this->validateCaptcha()) {
                $this->my_form_validation->set_message('g-recaptcha-response', 'Invalid Captcha.');
                $result = false;
            }
        }
        $result = $result && $this->my_form_validation->run();

        if (!$result) {
            $this->flushValidationErrors();
        }

        return $result;
    }

    protected function validateCaptcha() {
        $params = http_build_query(array(
            'secret' => $this->__settings->recaptcha_secret,
            'response' => $this->input->post('g-recaptcha-response')
        ));
        $curl = curl_init($this->__settings->recaptcha_url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
        $result = curl_exec($curl);
        curl_close($curl);

        if ($result && $result != '') {
            $obj = json_decode($result);
            return $obj->success;
        }
    }

    protected function addValidationRule($field, $label = '', $rules = '') {
        $this->my_form_validation->set_rules($field, $label, $rules);
    }

    protected function addValidationError($field, $msg) {
        $this->my_form_validation->set_message($field, $msg);
    }

    protected function flushValidationErrors() {
        if ($errors = $this->my_form_validation->error_array()) {
            foreach ($errors as $field => $msg) {
                $this->addErrorMessage($msg, $field);
            }
            $this->my_form_validation->clear();
        }
    }

    protected function addToResponseData($name, $value = null) {
        if ($this->__responseData == null) {
            $this->__responseData = array();
        }

        if (is_array($name)) {
            foreach ($name as $key => $value) {
                $this->addToResponseData($key, $value);
            }
        } else {
            if ($name === 'hasError' || $name === 'messages' || ($this->isAjax() && $name === 'status')) {
                throw new Exception("Please don't use 'hasError' or 'messages' as field of response data.");
            }
            $this->__responseData[$name] = $value;
        }
    }

    protected function stopAddingMessage() {
        $this->__adding_message = false;
    }

    protected function enableAddingMessage() {
        $this->__adding_message = true;
    }

    protected function addSuccessMessage($msg, $field = null, $pinned = false) {
        $this->AddMessage($msg, $field, MSG_TYPE_SUCCESS, $pinned);
    }

    protected function addInfoMessage($msg, $field = null, $pinned = false) {
        $this->AddMessage($msg, $field, MSG_TYPE_INFO, $pinned);
    }

    protected function addDebugMessage($msg) {
        if (DEBUG) {
            $this->addInfoMessage('[DEBUG] ' . $msg, null, true);
        }
    }

    protected function addWarningMessage($msg, $field = null, $pinned = false) {
        $this->AddMessage($msg, $field, MSG_TYPE_WARNING, $pinned);
    }

    protected function addErrorMessage($msg, $field = null, $pinned = false) {
        $this->AddMessage($msg, $field, MSG_TYPE_ERROR, $pinned);
    }

    protected function AddMessage($msg, $field = null, $type = MSG_TYPE_SUCCESS, $pinned = false) {
        if (!$this->__adding_message) {
            return;
        }

        if ($this->__messages == null) {
            $this->__messages = array();
        }
        $obj = new stdClass();
        $obj->field = $field;
        $obj->msg = $msg;
        $obj->type = $type;
        $obj->pinned = $pinned || DEBUG;
        $this->__messages[] = $obj;
        $this->__hasError = $type == MSG_TYPE_ERROR;
    }

    protected function __clear_message() {
        $this->__messages = null;
        $this->__hasError = false;
    }

    protected function hasError() {
        return $this->__hasError;
    }

    protected function isPost() {
        return 'POST' == $_SERVER['REQUEST_METHOD'];
    }

    protected function isAjax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    protected function fetchResponseData($data = null) {
        if ($data != null) {
            $this->addToResponseData($data);
        }

        $responseData = $this->__responseData;
        if ($responseData == null) {
            $responseData = array();
        }

        if (!$this->isAjax()) {
            $responseData['module_group'] = $this->__module_group;
            $responseData['module_type'] = $this->__module_type;
            $responseData['module_name'] = $this->__module_name;
            $responseData['page_title'] = $this->__page_title;

            if ($this->__account) {
                $responseData['account'] = $this->__account;
            }

            if ($this->__module_name != null && $this->__module_name != '') {
                $responseData['base_url'] = $this->__get_base_url();
            }
            if ($this->__scripts && sizeof($this->__scripts) > 0) {
                $responseData['scripts'] = $this->__scripts;
            }

            if ($this->__pagination_link !== null) {
                $responseData['pagination'] = $this->__pagination_link;
            }
            if ($this->__entity != null) {
                $responseData['entity'] = $this->__entity;
            }
            if ($this->__entities != null) {
                $responseData['entities'] = $this->__entities;
                $responseData['total_count'] = $this->__total_count;
            }

            $responseData['settings'] = $this->__settings;
            $responseData['options'] = $this->__options;
        }

        $messages = $this->__messages;
        $flashes = $this->session->flashdata('messages');
        if ($flashes && count($flashes) > 0) {
            if ($messages && count($messages) > 0) {
                $messages = array_merge($messages, $flashes);
            } else {
                $messages = $flashes;
            }
        }
        $responseData['messages'] = $messages;
        $responseData['hasError'] = ($this->__hasError | $this->session->flashdata('hasError'));
        if ($this->isAjax()) {
            $responseData['status'] = !$responseData['hasError'];
        }

        return $responseData;
    }

    protected function ajaxResponse($data = null) {
        echo json_encode($this->fetchResponseData($data));
        exit;
    }

    protected function redirect($uri = '', $method = 'location', $http_response_code = 302) {
        if ($this->__messages) {
            $this->session->set_flashdata('messages', $this->__messages);
            $this->session->set_flashdata('hasError', $this->__hasError);
        }
        redirect($uri, $method, $http_response_code);
        exit;
    }

    public function addScripts($scripts, $absolute = false) {
        if (!is_array($scripts)) {
            $scripts = array($scripts);
        }

        if ($scripts && count($scripts) > 0) {
            if (!$this->__scripts) {
                $this->__scripts = array();
            }
            foreach ($scripts as $script) {
                if (!$absolute) {
                    $script = base_url() . $script;
                }
                $this->__scripts[] = $script;
            }
        }
    }

    protected function view($view, $data = null, $layout = null) {
        $responseData = $this->fetchResponseData($data);

        $view_file = $this->__environment . '/' . $view;
        $view_path = explode('/', $view_file);
        $len = count($view_path);
        if ($len <= 1) {
            $view_path = '';
        } else {
            $view_path = implode('/', array_slice($view_path, 0, $len - 1));
        }
        $view_path .= '/';
        $responseData['view_path'] = $view_path;

        $__layout = null;
        if ($this->__layout !== null || $layout !== null) {
            $__layout = $this->__environment . '/includes/layout/';
            if ($layout != null && strlen($layout) > 0) {
                $__layout .= $layout . '/';
            } else if (strlen($this->__layout) > 0) {
                $__layout .= $this->__layout . '/';
            }

            $responseData['layout_path'] = $__layout;
        }
        if ($__layout) {
            $this->load->view($__layout . 'header.php', $responseData);
        }

        $this->load->view($view_file, $responseData);

        if ($__layout) {
            $this->load->view($__layout . 'footer.php', $responseData);
        }
    }

    protected function __select_environment($environment) {
        $this->__environment = $environment;
    }

    protected function __select_module_group($module_group) {
        $this->__module_group = $module_group;
    }

    protected function __select_module($module_type) {
        $this->__module_type = $module_type;
    }

    protected function __set_module_name($name) {
        $this->__module_name = $name;
    }

    protected function getModuleName() {
        return $this->__module_name;
    }

    protected function setEntity($entity) {
        $this->__entity = $entity;
    }

    protected function setEntities($entities) {
        $this->__entities = $entities;
    }

    protected function paginate($base_url, $total_count, $iccp = PER_PAGE, $params = null) {
        $config['base_url'] = $base_url;
        $config['total_rows'] = $total_count;
        $this->__total_count = $total_count;
        $config['per_page'] = $iccp;
        $config['uri_segment'] = 3;

        $config['full_tag_open'] = '<ul class="paginationlist">';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = '<i class="fa fa-chevron-left"></i><i class="fa fa-chevron-left"></i>';
        $config['first_tag_open'] = '<li class="first">';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = '<i class="fa fa-chevron-right"></i><i class="fa fa-chevron-right"></i>';
        $config['last_tag_open'] = '<li class="last">';
        $config['last_tag_close'] = '</li>';
        $config['prev_link'] = '<i class="fa fa-chevron-left"></i>';
        $config['prev_tag_open'] = '<li class="back">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '<i class="fa fa-chevron-right"></i>';
        $config['next_tag_open'] = '<li class="next">';
        $config['next_tag_close'] = '</li>';
        $config['cur_tag_open'] = "<li><a class='active'>";
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        if ($params != null) {
            $config['suffix'] = '?' . $params;
        }
        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $this->__pagination_link = $this->pagination->create_links();
    }

    function fillEntity($data, $new_fields = null, $force = false) {
        $this->__fillObject($this->__entity, $data, $new_fields, $force);
    }

    function __fillObject($obj, $data, $new_fields = null, $force = false) {
        $data = (array) $data;
        $keys = array();
        $tmp = (array) $obj;
        foreach ($tmp as $key => $val) {
            $keys[] = $key;
        }

        foreach ($data as $key => $val) {
            if (!$force) {
                if ($key == 'last_active_time') {
                    
                }
                if ($new_fields) {
                    if (!in_array($key, $new_fields)) {
                        continue;
                    }
                } else if (!in_array($key, $keys)) {
                    continue;
                }
            }

            $obj->$key = $val;
        }
    }

    function generateEmailMessage($template_slug, $params = null) {
        $mail_template = $this->db->query("select * from sys_email_template where slug='$template_slug'");

        $result = $mail_template->result();
        if (count($result) <= 0) {
            return null;
        }
        $result = $result[0];
        $subject = $result->subject;
        $content = $result->email_template_content;

        if ($params && count($params) > 0) {
            foreach ($params as $key => $value) {
                if ($key == 'user') {
                    foreach ($value as $n => $v) {
                        $content = str_replace("[user.$n]", $v, $content);
                    }
                    if (isset($value->user_name)) {
                        $content = str_replace("[user.name]", $value->user_name, $content);
                    }
                    if (isset($value->first_name) && isset($value->last_name)) {
                        $content = str_replace("[user.fullname]", $value->first_name . ' ' . $value->last_name, $content);
                    }
                } else {
                    $content = str_replace("[$key]", $value, $content);
                }
            }
        }

        return array(
            'subject' => $subject,
            'content' => $this->load->view('email/template', array('email_content' => $content), true)
        );
    }

    function sendMailUsingTemplate($user, $template_slug, $params = null) {
        if (!is_object($user)) {
            $email = $user;
            if (!($user = $this->getUserModel()->get_by_email($user))) {
                $user = new stdClass();
                $user->email = $email;
            }
        }
        if ($params == null) {
            $params = array();
        }
        $params['user'] = $user;
        $message = $this->generateEmailMessage($template_slug, $params);
        $this->sendMail($user->email, $message['subject'], $message['content']);
    }

    function sendMail($to, $subject, $message, $file = null) {
        if (!SENDMAIL) {
            $this->addDebugMessage('Sending mail is disabled.(To: ' . $to . ', Subject: ' . $subject . ')');
            return true;
        }

        if (!isset($this->emailSender) || !$this->emailSender) {
            $config = array(
                'protocol' => 'smtp',
                'smtp_host' => $this->__settings->smtp_host,
                'smtp_port' => $this->__settings->smtp_port,
                'smtp_user' => $this->__settings->smtp_user,
                'smtp_pass' => $this->__settings->smtp_pass,
                'mailtype' => 'html',
                'wordwrap' => 'true'
            );
            $this->load->library('email', $config, 'emailSender');
            $this->emailSender->set_newline("\r\n");
            $this->emailSender->from($this->__settings->site_name . ' <' . $this->__settings->email_address . '>');
        }
        $this->emailSender->to($to);
        $this->emailSender->subject($subject);
        $this->emailSender->message($message);
        if ($file) {
            $this->emailSender->attach($file);
        }
        $ret = $this->emailSender->send();
        if (!$ret) {
            $this->emailSender->print_debugger();
        }
        return $ret;
    }

    protected function logInfo($message) {
        log_message('info', $message);
    }

    protected function logDebug($message) {
        log_message('debug', $message);
        echo nl2br($message) . '<br />';
    }

    protected function logWarning($message) {
        log_message('warning', $message);
    }

    protected function logError($message) {
        log_message('error', $message);
    }

    protected function log($level, $message, $php_error = FALSE) {
        log_message($level, $message, $php_error);
    }

    protected function notify($user, $message, $url = null, $type = MSG_TYPE_INFO) {
        $this->____load_model('NotificationModel')->save(array(
            'user_id' => $user->user_id,
            'message' => $message,
            'url' => $url,
            'sender' => 'Admin',
            'type' => $type
        ));
        //$this->sendMail($user->email, 'Notification', $message);
    }

    protected function suspendUser(&$user, $reason, $block = false) {
        $user->status = !$block ? USER_STATUS_SUSPENDED : USER_STATUS_BLOCKED;
        $user->inactive_reason = $reason;
        return $this->____load_model('UserModel')->save(array(
                    'user_id' => $user->user_id,
                    'status' => $user->status,
                    'inactive_reason' => $user->inactive_reason
        ));
    }

    protected function activateUser($user) {
        $user->status = USER_STATUS_ACTIVE;
        $user->inactive_reason = null;
        return $this->getUserModel()->save(array(
                    'user_id' => $user->user_id,
                    'status' => $user->status,
                    'inactive_reason' => $user->inactive_reason
        ));
    }

    protected function createVerificationCode($username, $email) {
        $seed = $this->____load_model('UserModel')->gen_new_pass();
        $exp = time() + 3600 * 24 * 3;
        $msg = $username . $email . $seed;
        $code = md5($msg . $exp) . '|' . Cipher::encrypt($msg) . '|' . Cipher::encrypt($exp);
        return ($code);
    }

    protected function verify($code) {
        $parts = explode('|', $code);
        if (count($parts) != 3) {
            return false;
        }

        $hash = $parts[0];
        $msg_enc = $parts[1];
        $exp_enc = $parts[2];
        if (!$hash || $hash == '' || !$msg_enc || $msg_enc == '' || !$hash || $hash == '') {
            return false;
        }

        $msg = Cipher::decrypt($msg_enc);
        $exp = Cipher::decrypt($exp_enc);

        if ($hash != md5($msg . $exp)) {
            return false;
        }

        if ($exp < time()) {
            return false;
        }

        if ($user = $this->____load_model('UserModel')->get_by_code($code)) {
            return $user;
        }

        return false;
    }

    public function __tweakForPlural($count, $word, $plural = 's') {
        if ($count <= 1) {
            return $count . ' ' . $word . ' has been';
        } else {
            return $count . ' ' . $word . $plural . ' have been';
        }
    }

    protected function isDebugging() {
        return defined('DEBUG') && DEBUG;
    }

    protected function isDeveloper() {
        return in_array($this->__client_ip, array('60.21.159.62', '60.21.159.162'));
    }

    protected function isLocallyDeveloping() {
        return defined('LOCAL_DEVELOPMENT') && LOCAL_DEVELOPMENT;
    }

}
