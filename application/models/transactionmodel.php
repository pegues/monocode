<?php

require_once 'Model.php';

class TransactionModel extends Model {

    public function __construct() {
        parent::__construct();
        $this->__config(ENTITY_NAME_TRANSACTION, 'id');
    }

    public function get_by_bt_transaction($bt_transaction_id) {
        $this->db->where('bt_transaction_id', $bt_transaction_id);
        $query = $this->db->get($this->__entity_name);
        return $this->__valid_entity($query->row());
    }

}

?>
