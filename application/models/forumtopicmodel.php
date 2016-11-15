<?php

require_once 'Model.php';

class ForumTopicModel extends Model {

    public function __construct() {
        parent::__construct();
        $this->__config(ENTITY_NAME_FORUM_TOPIC, 'id');
    }

    public function entity($id) {

        $this->db->select("category.name category_name, category.color category_color", false);
        $this->db->join(ENTITY_NAME_FORUM_CATEGORY . ' category', $this->__entity_name . '.category_id = ' . 'category.id', 'LEFT');
        $this->db->select("user.user_name user_name", false);
        $this->db->join(ENTITY_NAME_USER . ' user', $this->__entity_name . '.user_id = ' . 'user.user_id', 'LEFT');
        return parent::entity($id);
    }

    public function __set_filter($params) {
        if (isset($params['slug'])) {
            $this->db->where('slug like ', $params['slug'] . '%');
            return;
        }

        $this->db->select("category.name category_name, category.color category_color", false);
        $this->db->join(ENTITY_NAME_FORUM_CATEGORY . ' category', $this->__entity_name . '.category_id = ' . 'category.id', 'LEFT');
        $this->db->select("user.user_name user_name", false);
        $this->db->join(ENTITY_NAME_USER . ' user', $this->__entity_name . '.user_id = ' . 'user.user_id', 'LEFT');

        if (isset($params['category_id']) && $params['category_id'] > 0) {
            $this->db->where('category_id', $params['category_id']);
        }

        if (isset($params['q']) && ($q = $params['q'])) {
            $this->db->where('(title like "%' . $q . '%" or ' . $this->db->dbprefix . $this->__entity_name . '.id in (select distinct(topic_id) from ' . $this->db->dbprefix . ENTITY_NAME_FORUM_POST . ' where cooked like "%' . $q . '%"))');
        }

        $this->db->order_by($this->__entity_name . '.pinned', 'desc');
        if (isset($params['sort']) && ($sort = $params['sort'])) {
            if ($sort == 'l') {
                $this->db->order_by($this->__entity_name . '.updated_at', 'desc');
            } else if ($sort == 't') {
                $this->db->order_by($this->__entity_name . '.reply_count', 'desc');
            }
        } else {
            $this->db->order_by($this->__entity_name . '.display_order', 'asc');
        }
    }

    public function getBySlug($slug) {
        $this->db->select($this->__entity_name . ".*");
        $this->db->where($this->__entity_name . '.slug', $slug);
        $this->db->select("category.name category_name, category.color category_color", false);
        $this->db->join(ENTITY_NAME_FORUM_CATEGORY . ' category', $this->__entity_name . '.category_id = ' . 'category.id', 'LEFT');
        $this->db->select("user.user_name user_name", false);
        $this->db->join(ENTITY_NAME_USER . ' user', $this->__entity_name . '.user_id = ' . 'user.user_id', 'LEFT');
        $query = $this->db->get($this->__entity_name);
        return $this->__valid_entity($query->row());
    }

    public function saveViewCount($id, $count) {
        return $this->save(array(
                    'id' => $id,
                    'views' => $count
        ));
    }

}

?>
