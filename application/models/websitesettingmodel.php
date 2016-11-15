<?php

require_once 'Model.php';

class WebsiteSettingModel extends Model {

    public function __construct() {
        parent::__construct();
        $this->__config('website_setting', 'website_setting_id');
    }

    public function getSessionHandler() {
        $this->db->where('field_name', 'sessionHandler');
        $entity = $this->__valid_entity($this->db->get($this->__entity_name)->row());
        if ($entity == null) {
            return "";
        } else {
            return $entity->field_value;
        }
    }

    public function getUserSessionLifetime() {
        $this->db->where('field_name', 'userSessionLifetime');
        $entity = $this->__valid_entity($this->db->get($this->__entity_name)->row());
        if ($entity == null) {
            return 0;
        } else {
            return (int) $entity->field_value;
        }
    }

    public function getCacheEnabled() {
        $this->db->where('field_name', 'cache');
        $entity = $this->__valid_entity($this->db->get($this->__entity_name)->row());
        if ($entity == null) {
            return false;
        } else {
            return (strcmp($entity->field_value, 'Enabled') == 0);
        }
    }

    public function getCacheHandler() {
        $this->db->where('field_name', 'cacheHandler');
        $entity = $this->__valid_entity($this->db->get($this->__entity_name)->row());
        if ($entity == null) {
            return 0;
        } else {
            return $entity->field_value;
        }
    }

    public function getCacheTime() {
        $this->db->where('field_name', 'cacheTime');
        $entity = $this->__valid_entity($this->db->get($this->__entity_name)->row());
        if ($entity == null) {
            return 0;
        } else {
            return (int) $entity->field_value;
        }
    }

}

?>
