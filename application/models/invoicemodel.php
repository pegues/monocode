<?php

require_once 'Model.php';

class InvoiceModel extends Model {

    public function __construct() {
        parent::__construct();
        $this->__config(ENTITY_NAME_INVOICE, 'id');
    }

    public function get_by_number($invoice_number) {
        $this->db->where('invoice_number', $invoice_number);
        $query = $this->db->get($this->__entity_name);
        return $this->__valid_entity($query->row());
    }

    public function __set_filter($params) {
        $this->db->select("user.user_name user_name", false);
        $this->db->join(ENTITY_NAME_USER . ' user', $this->__entity_name . '.user_id = ' . 'user.user_id', 'LEFT');

        if (isset($params['user_id']) && $params['user_id'] > 0) {
            $this->db->where($this->__entity_name . '.user_id', $params['user_id']);
        } else {
            $this->db->where($this->__entity_name . '.user_id', $this->__user->user_id);
        }
        
        $this->db->where($this->__entity_name . '.is_refund', 0);
        
        if (isset($params['status'])) {
            $this->db->where($this->__entity_name . '.is_paid', $params['status']);
        }

        $this->db->order_by('invoice_number', 'desc');
    }

}

?>
