<?php
class mbackend extends CI_Model{
    public function get_pages_by_position($page_position){
       $res=$this->db->query("select* from sys_cms c,sys_page_position p where c.page_position_id=p.page_position_id AND p.page_position_slug='" . $page_position . "' AND c.cms_parent='0' and c.status='Y' order by c.display_order ASC");
        return $res->result();
    }
}