<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once 'Controller.php';

class Template extends Controller {

    public function __construct() {
        parent::__construct();
        $this->__load_model('TemplateModel');
        $this->__type_model = $this->____load_model('TemplateTypeModel');
        $this->__feature_template_model = $this->____load_model('FeatureTemplateModel');
    }

    public function index() {
        $this->__list();
    }

    public function __list() {
        $templates = $this->__model->search();
        $types = $this->__type_model->search();
        $allowed = $this->__get_feature('allow_template');
        $feature_id = $this->__get_feature('feature_id');
        $available_templates = array();
        $list = $this->__feature_template_model->search(array('feature_id' => $feature_id));
        if (count($list) > 0) {
            foreach ($list as $template) {
                $available_templates[] = $template->template_id;
            }
        }
        $this->load->view('editor/template', array('templates' => $templates, 'types' => $types, 'allowed' => $allowed, 'available_templates' => $available_templates));
    }

    public function save() {
        $this->load->view('editor/template_save');
    }

    public function create() {
        $dir = $this->input->post('dir');
        if ($dir != '') {
            $dir = rtrim($dir, '/') . '/';
        }
        $template_id = $this->input->post('template_id');
        $path = $this->getWorkshop() . $dir;

        $template = $this->__model->entity($template_id);
        $template_file = realpath(APPPATH) . '/..' . $template->file_url;
        $this->load->library('Zipper', null, 'zipper');
        if ($template->archive == 1) {
            if ($this->isStorageLocal()) {
                $this->zipper->extract($template_file, $path);
            } else {
                $this->zipper->extractToAWS($template_file, $path, $this->getAWSServer(), $this->getAWSFileModel(), $this->getTempDir(), $this->__account->id);
            }
        } else {
            //$file = $path . $template->title . '-' . $template->version . '.' . end(explode('.', $template_file));
            $file = $path . $template->file_name;
            if ($this->isStorageLocal()) {
                copy($template_file, $file);
            } else {
                $url = $this->getAWSServer()->uploadFromFile($file, $template_file);
                if ($url === false) {
                    echo json_encode(array('status' => false, 'msg' => 'Error occurred while saving file.'));
                } else {
                    $size = filesize($template_file);
                    $this->getAWSFileModel()->updateFile($file, $url, $size);
                }
            }
        }

        echo json_encode(array(
            'status' => true,
            'dir' => $dir
        ));
    }

}
