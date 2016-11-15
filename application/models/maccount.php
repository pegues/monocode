<?php
class maccount extends CI_Model{
    public function check($user,$password){
        $res=$this->db->query("select * from sys_user where (user_name='".$user."' OR email='".$user."') AND status='active' AND user_type>0 AND password='".md5($password)."'");
        return $res->result();
        
    }
    
}