<?php

require_once 'Model.php';

class OptionModel extends Model {

    public function __construct() {
        parent::__construct();
        $this->__config('options', 'option_id');
    }

    public function __set_filter($params) {
        $this->db->where('user_id', isset($params['user_id']) && $params['user_id'] != '' ? $params['user_id'] : 0);
    }

    public function update($name, $value, $user_id = 0) {
        $this->db->where('option_key', $name);
        $this->db->where('user_id', $user_id);
        $query = $this->db->get($this->__entity_name);
        $option = $this->__valid_entity($query->row());
        if ($option == null) {
            return $this->save(array(
                        'user_id' => $user_id,
                        'option_key' => $name,
                        'option_value' => $value
            ));
        } else {
            return $this->save(array('option_id' => $option->option_id, 'option_value' => $value));
        }
    }
    
    public function get($name, $user_id = 0) {
        $this->db->where('option_key', $name);
        $this->db->where('user_id', $user_id);
        $query = $this->db->get($this->__entity_name);
        $option = $this->__valid_entity($query->row());
        if ($option == null) {
            return null;
        } else {
            return $option->option_value;
        }
        
    }
    
    public function deleteByUser($userId) {
        $this->db->where('user_id', $userId);
        return $this->db->delete($this->__entity_name);
    }
    
    public function getProjectCount() {
        $this->db->where('option_key', 'ws');
        $this->db->where('user_id >', 0);
        $rows = $this->db->get($this->__entity_name)->result();
        $count = 0;
        if (count($rows) > 0) {
            foreach($rows as $row) {
                if ($row->option_value) {
                    $count += count(json_decode($row->option_value));
                }
                
            }
        }
        
        return $count;
    }

}

?>
