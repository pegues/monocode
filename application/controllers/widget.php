<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once 'Controller.php';

class Widget extends Controller {

    public function __construct() {
        parent::__construct();
        $this->__load_model('WidgetModel');
        $this->__type_model = $this->____load_model('WidgetTypeModel');
        $this->__feature_widget_model = $this->____load_model('FeatureWidgetModel');
    }

    public function run($id) {
        if (!($widget = $this->__model->entity($id))) {
            die('Invalid widget.');
        }

        $allowed = false;
        $feature_id = $this->__get_feature('feature_id');
        $allowed_widgets = $this->____load_model('FeatureWidgetModel')->search(array('feature_id' => $feature_id));
        if (count($allowed_widgets) > 0) {
            foreach ($allowed_widgets as $alw) { //checking in user plan
                if ($alw->widget_id == $widget->widget_id) {
                    $allowed = true;
                }
            }
        }

        if (!$allowed) {
            die('Not allowed to use this widget.');
        }

        if (!$widget->archive) {
            $this->redirect($widget->file_url);
        } else {
            $url = $widget->file_url;
            $widget_file = realpath(APPPATH) . '/..' . $url;
            $url = preg_replace('/\..*/', '', $url);
            $path = realpath(APPPATH) . '/..' . $url;
            if (!is_dir($path) && file_exists($path)) {
                $url .= '_';
                $path = realpath(APPPATH) . '/..' . $url;
            }
            
            if (!is_dir($path)) {
                $this->mkdir($path);
                $this->load->library('Zipper', null, 'zipper');
                $this->zipper->extract($widget_file, $path);
            }
            
            $this->redirect($url);
        }
    }
    
    public function saveOption() {
        $widget_id = $this->input->post('id');
        $options = $this->input->post('options');
        $widget_options = isset($this->__options['widget_options']) && $this->__options['widget_options'] ? json_decode($this->__options['widget_options'], true) : array();
        $widget_options[$widget_id] = $options;
        $this->__save_option('widget_options', json_encode($widget_options));
        $this->ajaxResponse();
    }

}
