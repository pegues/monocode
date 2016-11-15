<?php

require_once 'Model.php';

class PromocodeModel extends Model {

    public function __construct() {
        parent::__construct();
        $this->__config(ENTITY_NAME_PROMOCODE, 'id');
    }

    public function __set_filter($params) {
        $this->db->order_by('id', 'desc');
    }

    public function get_by_code($code) {
        $this->db->where('code', $code);
        $query = $this->db->get($this->__entity_name);
        return $this->__valid_entity($query->row());
    }

}

?>
