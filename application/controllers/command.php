<?php

if (!defined('BASEPATH'))
        exit('No direct script access allowed');
require_once 'Controller.php';

class Command extends Controller {

        public function __construct() {
                parent::__construct();
                $this->__load_model('CommandModel');
                $this->__type_model = $this->____load_model('CommandTypeModel');
        }

        public function index() {
                $this->__list();
        }

        public function __list() {
                $types = $this->__type_model->search();
                $this->load->view('editor/popup_keyboardshortcuts', array('commands' => $this->__load_commands(), 'types' => $types));
        }

        public function save() {
                if ($this->input->post('restore') == 1) {
                        return $this->restore();
                }

                $names = $this->input->post('names');
                $shortcut_keys = $this->input->post('shortcut_keys');
                $shortcut_key_macs = $this->input->post('shortcut_key_macs');

                $commands = array();
                if (is_array($names)) {
                        foreach ($names as $key => $name) {
                                $commands[] = array(
                                        'name' => $name,
                                        'shortcut_key' => $shortcut_keys[$key],
                                        'shortcut_key_mac' => $shortcut_key_macs[$key]
                                );
                        }
                        $this->__options['commands'] = json_encode($commands);
                        $this->OptionModel->update('commands', $this->__options['commands'], $this->session->userdata('user_id'));
                }
                $types = $this->__type_model->search();
                $this->load->view('editor/popup_keyboardshortcuts', array('message' => 'Saved successfully.', 'commands' => $this->__load_commands(), 'types' => $types));
        }

        public function restore() {
                $this->__options['commands'] = '[]';
                $this->OptionModel->update('commands', $this->__options['commands'], $this->session->userdata('user_id'));

                $types = $this->__type_model->search();
                $this->load->view('editor/popup_keyboardshortcuts', array('message' => 'Restored successfully.', 'commands' => $this->__load_commands(), 'types' => $types));
        }

}
