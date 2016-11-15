<?php

require_once 'EditorController.php';

class Editor extends EditorController {

    public function __construct() {
        parent::__construct();
        $this->__layout = '';
    }

    public function index() {
        if ($this->isAccountSuspended()) {
            $this->addErrorMessage('You cannot access the editor because your account is suspended. Please check why your account is suspended and take the necessary actions to reactivate your account.');
            $this->redirect('account');
        }
        $this->view('editor', array(
            'db_user' => $this->__get_db_user(),
            'phpmyadmin_open' => (isset($this->__options['phpmyadmin_open']) ? $this->__options['phpmyadmin_open'] : 0),
            'commands' => $this->__load_commands(),
            'widgets' => $this->__load_widgets()
        ));
    }

    public function __load_widgets() {
        if (!$this->__get_feature('allow_widget')) {
            return array();
        }

        $enabled_ids = isset($this->__options['widgets']) && $this->__options['widgets'] ? json_decode($this->__options['widgets'], true) : array();
        if (count($enabled_ids) > 0) {
            $feature_id = $this->__get_feature('feature_id');
            $allowed_widgets = $this->____load_model('FeatureWidgetModel')->search(array('feature_id' => $feature_id));
            if (count($allowed_widgets) > 0) {
                foreach ($enabled_ids as $key => $id) { //checking in options if it's enabled
                    $allowed = false;
                    foreach ($allowed_widgets as $alw) { //checking in user plan
                        if ($id == $alw->widget_id) {
                            $allowed = true;
                        }
                    }
                    if (!$allowed) {
                        unset($enabled_ids[$key]);
                    }
                }
            }
        }

        if (count($enabled_ids) > 0) {
            return $this->____load_model('WidgetModel')->search(array('ids' => $enabled_ids));
        }

        return array();
    }

    public function online() {
        $this->ajaxResponse(array('online' => $this->getOnlineTime()));
    }

    public function refresh() {
        $this->ajaxResponse(array('online' => $this->getOnlineTime()));
    }

    public function addactivity() {
        $activity = $this->input->post('activity');
        $existingOptions = $this->__get_option('activity');
        $optionsArray = $existingOptions ? json_decode($existingOptions, true) : array();
        if (isset($activity['isActive']) && $activity['isActive'] && count($optionsArray) > 0) {
            foreach ($optionsArray as $key => $option) {
                $optionsArray[$key]['isActive'] = false;
            }
        }
        $optionsArray[$activity['id']] = $activity;
        $options = json_encode($optionsArray, true);
        $this->__save_option('activity', $options);
    }

    public function saveTabs() {
        $tabs = $this->input->post('tabs');
        $newArray = array();
        if ($tabs && count($tabs) > 0) {
            foreach ($tabs as $tab) {
                $newArray[$tab['id']] = $tab;
            }
        }
        $options = json_encode($newArray, true);
        $this->__save_option('activity', $options);
    }

    public function updateactivity() {
        $this->deleteactivity();
        $this->addactivity();
    }

    public function getactivity() {
        $this->load->view('editor/options');
        $existingOptions = get_option('activity', false);

        if ($existingOptions != '') {
            $optionsArray = json_decode($existingOptions, true);
            $newObj = array();
            if ($optionsArray && count($optionsArray) > 0) {
                foreach ($optionsArray as $key => $val) {
                    delete_option($key);
                    if (strpos($key, 'xey9zenz') === FALSE) {
                        $newObj[$key] = $val;
                    }
                }
            }
            echo json_encode($newObj, true);
        } else {
            echo "{}";
        }
    }

    public function welcome() {
        $this->load->view('editor/welcome-user');
    }

    public function welcome_action() {
        $this->load->view('editor/options');
        update_option('enableWelcome', $_POST['action']);
        $unique_id = $this->input->post('uniqueId');
    }

    public function deleteactivity() {
        $this->load->view('editor/options');
        $explode = array_filter(explode(',', $this->input->post('id')));
        $last_one_id = '';
        $data = null;

        foreach ($explode as $exploded_id) {
            $optionsArray = json_decode(get_option('activity', false), true);

            if ($optionsArray != null) {
                if (array_key_exists($exploded_id, $optionsArray)) {
                    $last_one_id = $exploded_id;
                    $data = $optionsArray[$exploded_id];
                    unset($optionsArray[$exploded_id]);
                    $options = json_encode($optionsArray, true);
                    update_option('activity', $options);
                }
            }
        }

        if ($last_one_id == null || $last_one_id == '') {
            echo('{}');
            return;
        }

        $this->addrecent($last_one_id, $data);
    }

    public function addrecent($unique_id, $data) {
        $options = '';
        $existingOptions = get_option('recentFile', false);
        $optionsArray = json_decode($existingOptions, true);
        if ($optionsArray) {
            $optionsArray = array_reverse($optionsArray);
        }
        $newActiv = array();
        $i = 0;
        $newActiv[$unique_id] = $data;

        if (count($optionsArray) > 0) {
            foreach ($optionsArray as $key => $value) {
                $i++;

                if ($i < 8) {
                    $newActiv[$key] = $value;
                }
            }
        }

        $options = json_encode(array_reverse($newActiv), true);
        update_option('recentFile', $options);
        echo '(' . json_encode($data) . ')';
    }

    public function dire() {
        $this->load->view('editor/options');
        $leftDire = get_option('leftDire');
        $exis = explode('-', $leftDire);
        $exis = array_filter($exis);

        $c_dire = $this->input->post('dire');
        $c_dire = explode('-', $c_dire);
        $c_dire = array_filter($c_dire);

        foreach ($c_dire as $vals) {
            if (is_array($exis)) {
                if (!in_array($vals, $exis)) {
                    $exis[] = $vals;
                }
            } else {
                $exis[] = $vals;
            }
        }

        $lens = implode('-', $exis);
        echo $lens;
        update_option('leftDire', $lens);
    }

    public function removedire() {
        $this->load->view('editor/options');
        $c_dire = $this->input->post('dire');
        $c_index = $this->input->post('current');
        $c_dire = explode('-', $c_dire);
        $c_dire = array_filter($c_dire);
        $toRemove = array();
        $add = false;

        foreach ($c_dire as $rem) {
            if ($rem == $c_index) {
                $add = true;
            }
            if ($add) {
                $toRemove[] = $rem;
            }
        }

        //print_r($toRemove);
        $leftDire = get_option('leftDire');
        $exis = explode('-', $leftDire);
        $exis = array_filter($exis);
        $newToUpdate = array();

        foreach ($exis as $tt) {
            if (in_array($tt, $toRemove)) {
                
            } else {
                $newToUpdate[] = $tt;
            }
        }

        $lens = implode('-', $newToUpdate);
        update_option('leftDire', $lens);
    }

    // Popup: Save as
    public function saveas() {
        $this->load->view('editor/popup_saveas');
    }

    // Popup: About
    public function about() {
        $this->load->view('editor/popup_about');
    }

    // Popup: Changelog
    public function changelog() {
        $this->load->view('editor/popup_changelog');
    }

    // Popup: Goto File
    public function gotofile() {
        $this->load->view('editor/popup_gotofile');
    }

    // Popup: Goto Line
    public function gotoline() {
        $this->load->view('editor/popup_gotoline');
    }

    // Popup: Keyboard Shortcuts
    public function keyboardshortcuts() {
        $this->load->view('editor/popup_keyboardshortcuts');
    }

    // Popup: New from Template
    public function newfromtemplate() {
        $this->load->view('editor/popup_newfromtemplate');
    }

    // Context: New Folder
    public function newfolder() {
        $this->load->view('editor/popup_newfolder');
    }

    // Popup: Open
    public function fileOpen() {
        $this->load->view('editor/popup_openfile');
    }

    // Popup: Save
    public function configSave($key, $val) {
        $this->load->view('editor/config_save');
    }

    // Context: Rename
    public function rename() {
        $this->load->view('editor/popup_rename');
    }

    // Context: Move To
    public function moveTo() {
        $this->load->view('editor/popup_moveTo');
    }

    // Context: Copy
    public function copyTo() {
        $this->load->view('editor/popup_copyTo');
    }

    // Popup: Find
    public function search() {
        $this->__layout = null;
        $this->view('popup_find');
    }
	
	// Terminal Configuration
    public function terminal() {
        if (!($container = $this->getSandboxServer()->container($this->input->get('domain')))) {
            die('Invalid container.');
        }
        $ip = $container->info->NetworkSettings->IPAddress;

        $this->__layout = null;
        $secret = 'N2U3MTRjNDVlNDUwNDFmOGEyOTIzYzNkYTY5MjUxZmEzZ';
        $authobj = array(
            'api_key' => 'NmVkMDk2MzNiZGYyNDViYWJhZTMxMzU3OWQwOGQ2MDUwY',
            'upn' => $this->__account->username,
            'timestamp' => time() . '0000',
            'signature_method' => 'HMAC-SHA1',
            'api_version' => '1.0'
        );
        $authobj['signature'] = hash_hmac('sha1', $authobj['api_key'] . $authobj['upn'] . $authobj['timestamp'], $secret);
        $valid_json_auth_object = json_encode($authobj);
        $this->view('terminal', array('auth' => $valid_json_auth_object, 'ip' => $ip));
    }

    public function saveOption() {
        $name = $this->input->post('name');
        $value = $this->input->post('value');
        if (is_array($value)) {
            $value = json_encode($value);
        }
        $this->__save_option($name, $value);
        $this->ajaxResponse();
    }

    public function saveLayoutOption() {
        $pane = $this->input->post('name');
        $options = $this->input->post('options');
        $layout_options = isset($this->__options['layout_options']) && $this->__options['layout_options'] ? json_decode($this->__options['layout_options'], true) : array();
        $layout_options[$pane] = $options;
        $this->__save_option('layout_options', json_encode($layout_options));
        $this->ajaxResponse();
    }

}

?>