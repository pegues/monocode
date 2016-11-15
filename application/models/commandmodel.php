<?php

require_once 'Model.php';

class CommandModel extends Model {

    public function __construct() {
        parent::__construct();
        $this->__config(ENTITY_NAME_COMMAND, 'command_id');
    }
    
    public function entity($id) {
        
        return parent::entity($id);
    }

    public function __set_filter($params) {
        $this->db->order_by('display_order', 'asc');
        $this->db->select("type.command_type_name", false);
        $this->db->join(ENTITY_NAME_COMMAND_TYPE. ' type', $this->__entity_name . '.type_id = ' . 'type.command_type_id', 'LEFT');
        $this->db->order_by($this->__entity_name . '.display_order', 'asc');
    }
}

?>
