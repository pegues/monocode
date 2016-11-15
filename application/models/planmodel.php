<?php

require_once 'Model.php';

class PlanModel extends Model {

    public function __construct() {
        parent::__construct();
        $this->__config(ENTITY_NAME_USER_TYPE, 'user_type_id');
    }

    public function __set_filter($params) {
        $this->db->where('user_type_id >', 1);
        $this->db->where('status', 'Y');
        $this->db->order_by('display_order', 'asc');
    }

}

?>
