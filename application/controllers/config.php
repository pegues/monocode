<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once 'EditorController.php';

class Config extends EditorController {

    // Configuration Tab
    public function index() {
        if ($this->isPost()) {
            foreach ($_POST as $key => $val) {
                if ($val != '') {
                    if (is_array($val)) {
                        $val = json_encode($val);
                    }
                    $this->__get_option_model()->update($key, $val, $this->__account->id);
                }
            }
            $this->addSuccessMessage('Options saved successfully.');
            $this->redirect('config');
        }
        $this->__layout = 'tab';
        $this->view('config', array('widgetInfo' => $this->getWidgetInfo()));
    }

    public function getWidgetInfo() {
        $widgets = $this->____load_model('WidgetModel')->search();
        $types = $this->____load_model('WidgetTypeModel')->search();
        $allowed = $this->__get_feature('allow_widget');
        $feature_id = $this->__get_feature('feature_id');
        $available_widgets = array();
        $list = $this->____load_model('FeatureWidgetModel')->search(array('feature_id' => $feature_id));
        if (count($list) > 0) {
            foreach ($list as $widget) {
                $available_widgets[] = $widget->widget_id;
            }
        }
        $widgetInfo = new stdClass();
        $widgetInfo->types = $types;
        $widgetInfo->list = $widgets;
        $widgetInfo->allowed = $allowed;
        $widgetInfo->availableList = $available_widgets;
        return $widgetInfo;
    }
}
