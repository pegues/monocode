<?php

require_once 'Model.php';

class WidgetModel extends Model {

    public function __construct() {
        parent::__construct();
        $this->__config(ENTITY_NAME_WIDGET, 'widget_id');
    }
    
    public function entity($id) {
        $this->db->select("concat(thumbnail_res.path, thumbnail_res.file_name) as thumbnail_url", false);
        $this->db->join(ENTITY_NAME_RESOURCE . ' thumbnail_res', $this->__entity_name . '.thumbnail_id = ' . 'thumbnail_res.rid', 'LEFT');
        $this->db->select("concat(icon_res.path, icon_res.file_name) as icon_url", false);
        $this->db->join(ENTITY_NAME_RESOURCE . ' icon_res', $this->__entity_name . '.icon_id = ' . 'icon_res.rid', 'LEFT');
        $this->db->select("concat(file_res.path, file_res.file_name) as file_url, file_res.origin_name as file_name", false);
        $this->db->join(ENTITY_NAME_RESOURCE . ' file_res', $this->__entity_name . '.file_id = ' . 'file_res.rid', 'LEFT');
        
        return parent::entity($id);
    }

    public function __set_filter($params) {
        if (isset($params['ids']) && $params['ids'] && count($params['ids']) > 0) {
            $this->db->where('widget_id in (' . trim(implode(',', $params['ids']), ' ,') . ')');
        }
        $this->db->order_by('display_order', 'asc');
        $this->db->select("concat(thumbnail_res.path, thumbnail_res.file_name) as thumbnail_url", false);
        $this->db->join(ENTITY_NAME_RESOURCE . ' thumbnail_res', $this->__entity_name . '.thumbnail_id = ' . 'thumbnail_res.rid', 'LEFT');
        $this->db->select("concat(icon_res.path, icon_res.file_name) as icon_url", false);
        $this->db->join(ENTITY_NAME_RESOURCE . ' icon_res', $this->__entity_name . '.icon_id = ' . 'icon_res.rid', 'LEFT');
        $this->db->select("concat(file_res.path, file_res.file_name) as file_url, file_res.origin_name as file_name", false);
        $this->db->join(ENTITY_NAME_RESOURCE . ' file_res', $this->__entity_name . '.file_id = ' . 'file_res.rid', 'LEFT');
        $this->db->order_by($this->__entity_name . '.display_order', 'asc');
    }
    
    public function get_by_title_version($title, $version) {
        $this->db->where('title', $title);
        $this->db->where('version', $version);
        return $this->__valid_entity($this->db->get($this->__entity_name)->row());
    }

}

?>
