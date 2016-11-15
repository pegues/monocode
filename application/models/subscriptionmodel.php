<?php

require_once 'Model.php';

class SubscriptionModel extends Model {

    public function __construct() {
        parent::__construct();
        $this->__config(ENTITY_NAME_USER_SUBSCRIPTION, 'id');
    }

    public function get_by_order($orderId) {
        $this->db->or_where(array('order_id' => $orderId));
        $query = $this->db->get($this->__entity_name);
        return $this->__valid_entity($query->row());
    }
    
    public function get_by_bt_subscription($bt_subscription_id) {
        $this->db->order_by('id', 'desc');
        $this->db->or_where(array('bt_subscription_id' => $bt_subscription_id));
        $query = $this->db->get($this->__entity_name);
        return $this->__valid_entity($query->row());
    }

}

?>
