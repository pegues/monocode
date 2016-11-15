<?php
class muser extends CI_Model{

	public function get_plan_items(){
		$plans=$this->db->query("select * from sys_user_type where user_type_id>1 AND status='Y' 
		order by display_order ASC");
		return $plans->result();
	}
	

	
	public function addNewPlan($user_id,$new_plan_id,$amount,$today,$end_date){
		$data=array(
					'user_id'=>$user_id,
					'user_type_id'=>$new_plan_id,
					'amount'=>$amount,
					'start_date'=>$today,
					'end_date'=>$end_date		
					);
			$this->db->insert('sys_user_subscription',$data);		
	}	
	public function workspace_update($user_id='',$workspace=''){
		
	}
	public function change_plan($new_plan_id,$user_id){
		$click=$this->db->query("update sys_user set user_type='".$new_plan_id."' 
		where user_id='".$user_id."'");
	}	
	
	
	//New
	public function update_user_detail($user_id,$newplan,$amount,$start_date,$end_date,$order_id,$discount){
		$data=array(
					'order_id'=>$order_id,
					'user_id'=>$newplan,
					'user_type_id'=>$newplan,
					'amount'=>$amount,
					'start_date'=>$start_date,
					'end_date'=>$end_date,
					'discount'=>$discount,
					'status'=>'N',
					'payment_status'=>'P'
					);
			$this->db->insert('sys_transaction',$data);	
		
	}
	public function get_user_plan_detail_by_plan_id($id){
        echo '-----------------------------------------------';
		$detail=$this->db->query("
		select * from 
		sys_user_type ut,sys_features ft,sys_user_type_to_features utft where 
		(ut.user_type_id=utft.user_type_id AND utft.feature_id=ft.feature_id) AND
		ut.user_type_id='".$id."'
		");
		return $detail->result(); 
	}
	public function get_user_plan_detail_by_user_id($id){
        echo '-----------------------------------------------';
		$detail=$this->db->query("
		select * from 
		sys_user u,sys_user_type ut,sys_features ft,sys_user_type_to_features utft where 
		(u.user_type=ut.user_type_id) AND u.user_id='".$id."' AND
		(ut.user_type_id=utft.user_type_id AND utft.feature_id=ft.feature_id)
		");
		return $detail->result(); 
	}
	public function get_features_of_plan($plan_type){
		$plans=$this->db->query("
		select* from sys_features f, 
		sys_user_type_to_features l 
		where 
		l.user_type_id='".$plan_type."' AND 
		l.feature_id=f.feature_id AND 
		f.status='Y' order by 
		f.display_order ASC
		");
		$all_array=array();
		$all=$plans->result();
		if(isset($all[0])){
			$all_array[]=array('editor','yes','Sceditor Included');
			if(isset($all[0]->allow_ftp)){
			$all_array[]=array('ftp',$all[0]->allow_ftp,'FTP Included');
			}
			if(isset($all[0]->allow_svn)){
			$all_array[]=array('svn',$all[0]->allow_svn,'SVN Included');
			}
			if(isset($all[0]->work_space)){
			$all_array[]=array('workspace','yes',$all[0]->work_space. " workspace");
			}
			if(isset($all[0]->work_space)){
			$all_array[]=array('database','yes',$all[0]->work_space. " database");
			}
		}
		return $all_array;
	}
	public function get_email_template_by_template_id($id){
		$detail=$this->db->query("select * from sys_email_template where email_template_id='".$id."'");
		return $detail->result(); 
	}
	
}

	