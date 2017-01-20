<?php
defined('SYSPATH') or die('No direct access allowed.');
class Model_Networks extends Model
{
   public function insert($table,$array){
		$result = DB::insert($table,array_keys($array))->values(array_values($array))->execute();
		return $result[0];
	}
	public function update($table,$array){
		$sql = DB::update($table)->set(array('chat_req_status' => $array["chat_req_status"],'chat_req_msg' => $array["chat_req_msg"],'chat_req_on' => $array["chat_req_on"]))->where('chat_req_userid', '=', $array["chat_req_userid"])->and_where('chat_req_to', '=', $array["chat_req_to"]);
		$res = $sql->execute();
		return $res;
	}
	public function update_resend($from,$to){
		$chat_req_id = $this->get_request_id($from,$to);
		$sql   = "update chat_log set chat_log_resend=0 WHERE chat_req_id = '".$chat_req_id."'";
		$query = DB::query(Database::UPDATE, $sql);
		return $query->execute();
	}
	public function update_request($req_status,$from,$to){
		$datetime = Helper_Common::get_default_datetime();
		$chat_req_id = $this->get_request_id($from,$to);
		$sql   = "update chat_request set chat_req_confirmedon='".$datetime."' , chat_req_status='" . $req_status . "', is_read=0 WHERE chat_req_id = '" . $chat_req_id . "' and chat_req_to = '" . $to . "'";
		$query = DB::query(Database::UPDATE, $sql);
		return $query->execute();
	}
	public function check_request($table,$user,$to){
		$sql   = 'select * from '.$table.' where chat_req_userid='.$user.' and chat_req_to='.$to;
		$query = DB::query(Database::SELECT, $sql);
		$list  = $query->execute()->as_array();
		return ($list) ? true : false;
	}
	public function get_user_details($uid)
   {
		$sql   = 'select * from users as u join user_sites as us on us.user_id=u.id where u.id="'.$uid.'"';
		$query = DB::query(Database::SELECT, $sql);
		$list  = $query->execute()->as_array();
		return ($list) ? $list[0] : false;
   }
	public function get_network_searchusers($siteid,$roleid,$search)
   {
		$sql = "select	spf.* from	site_user_profile as spf join users as u on u.id=spf.userid	join user_sites as us on us.user_id=spf.userid and us.status=1	join sites as s on s.id=us.site_id  join roles_users as rs on rs.user_id = spf.userid join roles as r  on rs.role_id = r.id where us.site_id in ($siteid) and s.is_active = '1' and s.is_deleted = '0' and (spf.firstname like '%$search%' or spf.lastname like '%$search%') ".(($roleid!='')?"and rs.role_id in ($roleid)":"")." group by u.id";
		
		$query = DB::query(Database::SELECT, $sql);
		$list  = $query->execute()->as_array();
		return ($list)?$list:false;
	}
	public function get_network_users($siteid,$roleid)
   {
		$sql = "select spf.* from site_user_profile as spf join users as u on u.id=spf.userid join user_sites as us on us.user_id=spf.userid and us.status=1 join sites as s on s.id=us.site_id join roles_users as rs on rs.user_id = spf.userid join roles as r  on rs.role_id = r.id where us.site_id in ($siteid) and rs.role_id in ($roleid)";
		$query = DB::query(Database::SELECT, $sql);
		$list  = $query->execute()->as_array();
		return ($list)?$list:false;
	}
	public function get_network_users_only($userid,$search, $role)
    {
		$qry = "SELECT
					cr.*,u.user_fname as firstname, u.id as userid, u.user_lname as lastname, if(spf.profile_img is NULL, spf.profile_img, u.avatarid) as profile_img, spf.background
				FROM
					chat_request as cr
					join users as u on u.id = cr.chat_req_to
					join chat_log as log on (log.chat_req_id = cr.chat_req_id)
					join site_user_profile as spf on (spf.userid=cr.chat_req_to OR spf.userid=cr.chat_req_userid)
					join roles_users as rs on rs.user_id = spf.userid join roles as r  on rs.role_id = r.id
				WHERE cr.chat_req_userid = $userid ".(trim($search)!='' ? "AND ( u.user_fname like '%$search%' OR u.user_lname like '%$search%' )" : "")."
				 ".(($role!='')?"and rs.role_id in ($role)":"")."
				group by log.chat_req_id";
		//echo $qry;
		$query = DB::query(Database::SELECT, $qry);
		$list  = $query->execute()->as_array();
		return (isset($list[0]['chat_req_id']) && empty($list[0]['chat_req_id']) ? false : (isset($list[0]['chat_req_id']) && !empty($list[0]['chat_req_id']) ? $list : false) ) ;
	}
	public function get_network_users_unread_count($userid,$from)
    {
		//echo $from;
		$qry = "SELECT count(log.chat_log_id) as unreadcnt FROM chat_request as cr join users as u on u.id = cr.chat_req_to	join chat_log as log on (log.chat_req_id = cr.chat_req_id AND log.receiver_read_status =0 ) left join site_user_profile as spf on spf.userid=cr.chat_req_to WHERE	cr.chat_req_to = $userid ";
		
		if($from){
			$qry .= " and cr.chat_req_userid =$from ";
			//echo $qry;
		}
		
		$query = DB::query(Database::SELECT, $qry);
		$list  = $query->execute()->as_array();
		return (isset($list[0]['unreadcnt']) && $list[0]['unreadcnt'] > 0 ? $list[0]['unreadcnt'] : '');
	}
	
	public function get_particular_users_only($userid)
   {
		$qry = "SELECT
						u.user_fname as firstname, u.id as userid, u.user_lname as lastname, if(spf.profile_img  is NULL, spf.profile_img, u.avatarid) as profile_img, spf.background
					FROM
						users as u 
						join site_user_profile as spf on spf.userid=u.id
					WHERE	u.id = $userid";
		$query = DB::query(Database::SELECT, $qry);
		$list  = $query->execute()->as_array();
		return ($list)?$list[0]:false;
	}
	
	
	public function get_network_user_sites($userid)
    {
		$sql   = "SELECT group_concat(u.site_id) as siteid FROM user_sites as u join sites as s on (u.site_id=s.id) WHERE u.user_id = '" . $userid . "' AND  s.is_deleted=0 AND  s.is_active=1";
		$query = DB::query(Database::SELECT, $sql);
		$list  = $query->execute()->as_array();
		return ($list)?$list[0]:false;
    }
	
	public function get_chats($from,$to,$flag)
	{
		
		$sql  = "
					(
						SELECT
							cr.*,cl.*, concat(u1.user_fname,' ',u1.user_lname) as fromuser, u1.avatarid as fromuserimg,
							concat(u2.user_fname,' ',u2.user_lname) as touser, u2.avatarid as touserimg
						FROM 
							chat_request as cr 
							join chat_log as cl on (cr.chat_req_id=cl.chat_req_id)
							join users as u1 on cr.chat_req_userid = u1.id
							join users as u2 on cr.chat_req_to = u2.id
						where
							(cr.chat_req_userid= $to AND cr.chat_req_to = $from AND cl.chat_log_status = 1 ) ".($flag == true ? '' : '')."
					)
					union
					(
						SELECT
							cr.*,cl.*, concat(u1.user_fname,' ',u1.user_lname) as fromuser, u1.avatarid as fromuserimg,
							concat(u2.user_fname,' ',u2.user_lname) as touser, u2.avatarid as touserimg
						FROM 
							chat_request as cr 
							join chat_log as cl on  (cr.chat_req_id=cl.chat_req_id)
							join users as u1 on cr.chat_req_userid = u1.id
							join users as u2 on cr.chat_req_to = u2.id
						where
							(cr.chat_req_userid= $from AND cr.chat_req_to = $to)
							and cr.chat_req_status=1 AND cl.chat_log_resend=0 AND cl.chat_log_status = 1 ".($flag == true ? '' : '')."
					)
					order by  chat_log_on asc";   //AND cl.receiver_read_status=0
		//echo $sql; die;
		$query = DB::query(Database::SELECT, $sql);
		$list  = $query->execute()->as_array();
		return ($list)?$list:false;
	}
	public function get_request_id($from, $to){
		$sql = "select chat_req_id from chat_request where chat_req_userid ='".$from."' AND chat_req_to = '".$to."'";
		$query = DB::query(Database::SELECT, $sql);
		$list  = $query->execute()->as_array();
		return (isset($list[0]['chat_req_id']) ? $list[0]['chat_req_id'] : false);
	}
}