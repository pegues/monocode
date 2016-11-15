<?php

require_once 'Model.php';

class NotificationModel extends Model {

    public function __construct() {
        parent::__construct();
        $this->__config(ENTITY_NAME_NOTIFICATION, 'id');
        $this->__set_use_created_time(true);
    }

    public function entity($id) {

        $this->db->select("user.user_name user_name", false);
        $this->db->join(ENTITY_NAME_USER . ' user', $this->__entity_name . '.user_id = ' . 'user.user_id', 'LEFT');
        return parent::entity($id);
    }

    public function __set_filter($params) {
        $this->db->select("user.user_name user_name", false);
        $this->db->join(ENTITY_NAME_USER . ' user', $this->__entity_name . '.user_id = ' . 'user.user_id', 'LEFT');
        
        if (isset($params['user_id']) && $params['user_id'] > 0) {
            $this->db->where($this->__entity_name . '.user_id', $params['user_id']);
        } else {
            $this->db->where($this->__entity_name . '.user_id', $this->__user->user_id);
        }
        
        $this->db->order_by('created_time', 'desc');
    }
}

?>
