<?php

require_once 'Model.php';

class ForumPostModel extends Model {

    public function __construct() {
        parent::__construct();
        $this->__config(ENTITY_NAME_FORUM_POST, 'id');
    }

    public function entity($id) {

        $this->db->select("user.user_name user_name", false);
        $this->db->join(ENTITY_NAME_USER . ' user', $this->__entity_name . '.user_id = ' . 'user.user_id', 'LEFT');
        return parent::entity($id);
    }

    public function __set_filter($params) {
        $this->db->select("user.user_name user_name", false);
        $this->db->join(ENTITY_NAME_USER . ' user', $this->__entity_name . '.user_id = ' . 'user.user_id', 'LEFT');
        
        if (isset($params['topic_id']) && $params['topic_id'] > 0) {
            $this->db->where('topic_id', $params['topic_id']);
        }
        if (isset($params['reply']) && $params['reply']) {
            $this->db->where('reply_to_post_number > ', 0);
        }
    }
    
    public function getNewPostNumber($topic_id) {
        $this->db->where('topic_id', $topic_id);
        $this->db->select_max('post_number');
        $query = $this->db->get($this->__entity_name);
        $postNumber = (int) $query->row()->post_number;
        return $postNumber + 1;
    }

}

?>
