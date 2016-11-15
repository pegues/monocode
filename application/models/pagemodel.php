<?php

require_once 'Model.php';

class PageModel extends Model {

    public function __construct() {
        parent::__construct();
        $this->__config(ENTITY_NAME_PAGE, 'id');
    }
    
    public function entity($id) {
        
        return parent::entity($id);
    }

    public function __set_filter($params) {
        $this->db->order_by($this->__entity_name . '.display_order', 'asc');
    }
}

?>
