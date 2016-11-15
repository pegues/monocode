<?php
class mhome extends CI_Model{
    public function get_date_for_home(){
        $res=$this->db->query("select * from sys_user");
        return $res->result();
        
    }
    
}