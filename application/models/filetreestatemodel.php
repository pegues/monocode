<?php

require_once 'Model.php';

class FileTreeStateModel extends Model {

    public function __construct() {
        parent::__construct();
        $this->__config(ENTITY_NAME_FILETREE_STATE, 'id');
    }

    public function __set_filter($params) {
        $this->db->where('user_id', $params['user_id']);
        $this->db->where('url like "' . $params['url'] . '%"');
        $this->db->order_by('url');
    }

    public function getByURL($user_id, $url) {
        $this->db->where('user_id', $user_id);
        $this->db->where('url', $url);
        return $this->__valid_entity($this->db->get($this->__entity_name)->row());
    }
    
    public function saveByURL($user_id, $url) {
        if ($this->getByURL($user_id, $url)) {
            return;
        }
        $this->save(array('user_id' => $user_id, 'url' => $url));
    }
    public function deleteByURL($user_id, $url) {
        $this->db->where('user_id', $user_id);
        $this->db->where('url like "' . $url . '%"');
        return $this->db->delete($this->__entity_name);
    }
}

?>
