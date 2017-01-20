<?php
defined('SYSPATH') or die('No direct access allowed.');
class Model_Admin_Mailbox extends Model
{
   public function get_unread_message($userid){
      $sql   = "SELECT * FROM sitecontact_mapping as sm join sitecontact as s on sm.contact_id=s.id WHERE sm.status=1 and sm.userid =$userid
                  AND sm.notification_read_status=0 order by s.id desc";
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list;
   }
   public function get_user_message($userid,$filter='',$offset=0,$limit){
      $sql   = "SELECT *,ss.name as sitename,sm.id as smid FROM sitecontact_mapping as sm join sitecontact as s on sm.contact_id=s.id join sites as ss on sm.siteid=ss.id WHERE sm.status=1 and sm.userid =$userid";
      
      if(isset($filter["searchtxt"]) && trim($filter["searchtxt"])!=''){
         $searchtxt = $filter["searchtxt"];
         $sql .= " and (s.firstname like '%$searchtxt%' or s.lastname like '%$searchtxt%' or s.message like '%$searchtxt%' or email like '%$searchtxt%' or phone like '%$searchtxt%' or ss.name like '%$searchtxt%') ";
      }
      if(isset($filter["sortby"]) && $filter["sortby"]=="site"){
         $sql .= " order by ss.name asc ";
      }elseif(isset($filter["sortby"]) && $filter["sortby"]=="name"){
         $sql .= " order by s.firstname asc ";
      }elseif(isset($filter["sortby"]) && $filter["sortby"]=="date"){
         $sql .= " order by s.dated asc ";
      }elseif(isset($filter["sortby"]) && $filter["sortby"]=="read"){
         $sql .= " order by sm.read_status desc ";
      }elseif(isset($filter["sortby"]) && $filter["sortby"]=="unread"){
         $sql .= " order by sm.read_status asc ";
      }else{         
         $sql .= " order by s.id desc ";
      }
      if($limit>=0 && $limit!='' ){
         $sql .= " limit $offset, $limit";
      }
      //echo $sql; die;
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list;
   }
   public function get_user_preview_message($id,$userid){
      $sql   = "SELECT *,sm.id as smid FROM sitecontact_mapping as sm join sitecontact as s on sm.contact_id=s.id WHERE sm.status=1 and sm.userid =$userid and sm.contact_id =$id";
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return (isset($list))?$list[0]:false;
   }
   public function get_unread_mail_message($userid){
      $sql   = "SELECT * FROM sitecontact_mapping as sm join sitecontact as s on sm.contact_id=s.id WHERE sm.status=1 and sm.userid =$userid
                  AND sm.read_status=0 order by s.id desc";
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list;
   }
   public function move_to_trash($smid,$userid){
      $sql = "update sitecontact_mapping set status = 4 WHERE id in ($smid) and userid = ".$userid;				 
		$query = DB::query(Database::UPDATE,$sql);						
		return $query->execute();
   }
   public function set_unread($smid,$userid){
      $sql = "update sitecontact_mapping set read_status = 0 WHERE id in ($smid) and userid = ".$userid;				 
		$query = DB::query(Database::UPDATE,$sql);						
		return $query->execute();
   }
   
   
   public function get_prev_next($id){
      $sql = '(SELECT "next" as type,id FROM sitecontact WHERE `id` > '.$id.' ORDER BY `id` ASC LIMIT 1)
               UNION
               (SELECT "prev" as type,id FROM sitecontact WHERE `id` < '.$id.' ORDER BY `id` DESC LIMIT 1)';
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list;         
   }
}