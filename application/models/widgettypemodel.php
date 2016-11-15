<?php

require_once 'Model.php';

class WidgetTypeModel extends Model {

    public function __construct() {
        parent::__construct();
        $this->__config(ENTITY_NAME_WIDGET_TYPE, 'widget_type_id');
    }

    public function __set_filter($params) {
        $this->db->order_by('display_order', 'asc');
    }

}

?>
