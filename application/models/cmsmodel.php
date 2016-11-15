<?php

require_once 'Model.php';

class CmsModel extends Model {

    public function __construct() {
        parent::__construct();
        $this->__config(ENTITY_NAME_CMS, 'cms_id');
    }

    public function get_by_link($link) {
        $this->db->where('link', $link);
        $query = $this->db->get($this->__entity_name);
        return $this->__valid_entity($query->row());
    }

}

?>
