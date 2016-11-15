<?php

require_once 'Model.php';

class DiscountModel extends Model {

    public function __construct() {
        parent::__construct();
        $this->__config(ENTITY_NAME_DISCOUNT, 'id');
    }
    
    public function __set_filter($params) {
        $this->db->order_by('id', 'asc');
    }
}

?>
