<?php

require_once 'Model.php';

class UserModel extends Model {

    public function __construct() {
        parent::__construct();
        $this->__config(ENTITY_NAME_USER, 'user_id');
    }

    public function __set_filter($params) {
        if (!isset($params['all'])) {
            $this->db->where('user_id >', 0);
        }
        if (isset($params['status'])) {
            $this->db->where( $this->__entity_name . '.status', $params['status']);
        }
        if (isset($params['NON-FREE-PLAN'])) {
            $this->db->where( $this->__entity_name . '.user_type > ', FREE_PLAN_ID);
        }
        $this->db->order_by($this->__entity_name . '.display_order', 'asc');
        $this->db->select('ut.user_type_name');
        $this->db->join(ENTITY_NAME_USER_TYPE . ' ut', $this->__entity_name . '.user_type=ut.user_type_id', 'LEFT');
    }

    public function get_by_email_or_name($email) {
        $this->db->or_where(array('email' => $email, 'user_name' => $email));
        $query = $this->db->get($this->__entity_name);
        return $this->__valid_entity($query->row());
    }

    public function get_by_email($email) {
        $this->db->where('email', $email);
        $query = $this->db->get($this->__entity_name);
        return $this->__valid_entity($query->row());
    }

    public function get_by_name($name) {
        $this->db->where('user_name', $name);
        $query = $this->db->get($this->__entity_name);
        return $this->__valid_entity($query->row());
    }

    public function get_by_code($code) {
        $this->db->where('verification_code', $code);
        $query = $this->db->get($this->__entity_name);
        return $this->__valid_entity($query->row());
    }
    
    public function get_by_social($id, $type) {
        $this->db->where('social_id', $id);
        $this->db->where('social_type', $type);
        $query = $this->db->get($this->__entity_name);
        return $this->__valid_entity($query->row());
    }

    function change_password($uid, $pwd, $reset = 0) {
        if ($this->db->update($this->__entity_name, array('password' => $pwd), array($this->__pk_name => $uid)))
            return true;
        return false;
    }

    function gen_new_pass($len = 12) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        return substr(str_shuffle($chars), 0, $len);
    }

    public function exist_super_admin() {
        $this->db->where('level', 1);
        $query = $this->db->get($this->__entity_name);
        return $query->num_rows();
    }
    
    public function exist_sub_id($subId) {
        $this->db->where('workshop like ', $subId . '%');
        $query = $this->db->get($this->__entity_name);
        return $query->num_rows();
    }

    public function get_by_subscription($subscription_id) {
        $this->db->where('subscription_id', $subscription_id);
        $query = $this->db->get($this->__entity_name);
        return $this->__valid_entity($query->row());
    }

}

?>
