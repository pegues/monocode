<?php

require_once 'Model.php';

class ForumCategoryModel extends Model {

    public function __construct() {
        parent::__construct();
        $this->__config(ENTITY_NAME_FORUM_CATEGORY, 'id');
    }

    public function __set_filter($params) {
        $this->db->order_by('display_order', 'asc');
    }

}

?>
