<?php

require_once 'Model.php';

class AWSFileModel extends Model {

    public function __construct() {
        parent::__construct();
        $this->__config('aws_files', 'id');
    }

    public function __set_filter($params) {
        if (isset($params['user_id']) && $params['user_id'] > 0) {
            $this->db->where('user_id', $params['user_id']);
        }
        if (isset($params['strict']) && $params['strict'] == true) {
            $this->db->where('path', $params['path']);            
        } else {
            $this->db->where('path like ', $params['path'] . '%');
        }
        
        if (isset($params['type']) && $params['type'] != '') {
            $this->db->where('type', $params['type']);
        }

        $this->db->order_by('path');
        $this->db->order_by('type');
        $this->db->order_by('name');
    }

    public function getNewFileName($file_name, $path, $user_id) {
        $name = $file_name;
        $pos = strrpos($file_name, '.', '-1');
        $ext = '';
        if ($pos > -1) {
            $name = substr($file_name, 0, $pos);
            $ext = substr($file_name, $pos + 1);
        }
        $this->db->where('name like', $name . '%' . ($ext != '' ? '.' . $ext : ''));
        $list = $this->search(array('user_id' => $user_id, 'path' => $path, 'strict' => true));
        if (count($list) <= 0) {
            return $name . ($ext != '' ? '.' . $ext : '');
        }
        $max = 0;
        foreach ($list as $file) {
            $part = $file->name;
            $part = str_replace($name . '-', '', $part);
            if ($ext != '') {
                $part = str_replace('.' . $ext, '', $part);
            }
            $index = (int) $part;
            if ($index > $max) {
                $max = $index;
            }
        }

        return $name . '-' . ($max + 1) . ($ext != '' ? '.' . $ext : '');
    }

    public function createFile($name, $path, $url, $user_id, $type, $size) {
        return $this->create($name, $path, $url, $user_id, $type, $size);
    }

    public function createDir($name, $path, $user_id) {
        return $this->create($name, $path, '', $user_id, 'dir');
    }

    public function create($name, $path, $url, $user_id, $type, $size = 0) {
        $time = gmdate('Y-m-d H:i:s');
        return $this->save(array('name' => $name, 'path' => $path, 'url' => $url, 'user_id' => $user_id, 'size' => $size, 'created_time' => $time, 'modified_time' => $time, 'type' => $type));
    }

    public function updateFile($path, $url, $size) {
        if ($file = $this->getByPath($path)) {
            $time = gmdate('Y-m-d H:i:s');
            return $this->save(array('id' => $file->id, 'url' => $url, 'size' => $size, 'modified_time' => $time));
        } else {
            return false;
        }
    }
    
    public function getByPath($path) {
        $this->db->where("concat(path, name)='$path'", null, false);
        return $this->__valid_entity($this->db->get($this->__entity_name)->row());
    }
    
    public function deleteByPath($path) {
        $this->db->where("concat(path, name)='$path'", null, false);
        return $this->db->delete($this->__entity_name);
    }
    
    function __total_size($params) {
        $this->__show_query = true;
        $this->db->select('sum(size) as `usage`');
        $this->__set_filter($params);
        $query = $this->db->get($this->__entity_name);
        return $query->row()->usage;
    }
}

?>
