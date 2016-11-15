<?php

require_once 'Model.php';

class FeatureWidgetModel extends Model {

    public function __construct() {
        parent::__construct();
        $this->__config(ENTITY_NAME_FEATURE_WIDGET, 'feature_to_widget_id');
    }

    public function __set_filter($params) {
        $this->db->where('feature_id', $params['feature_id']);
    }

    public function delete_by_feature($feature_id) {
        return $this->db->delete($this->__entity_name, array('feature_id' => $feature_id));
    }
}

?>
