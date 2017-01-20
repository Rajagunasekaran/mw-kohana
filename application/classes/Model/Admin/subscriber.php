<?php
defined('SYSPATH') or die('No direct access allowed.');
class Model_Admin_Subscriber extends Model
{
   public function getUserDetails($role_name = '', $search_email = '', $search_role = '')
   {
      $sql   = "SELECT u.id,u.email,u.first_name,u.last_name,u.gender,u.mobile_phone,u.photo,u.date_created,u.is_active,r.name as role_name FROM users as u join roles_users as ru on ru.user_id = u.id join roles as r on r.id=ru.role_id WHERE u.is_delete = 0 AND r.name != 'login' " . ((isset($role_name) && !empty($role_name)) ? ' AND r.name = "' . $role_name . '"' : '') . ((isset($search_email) && !empty($search_email)) ? ' AND u.email like "' . $search_email . '%"' : '') . ((isset($search_role) && !empty($search_role)) ? ' AND r.id = "' . $search_role . '"' : '');
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list;
   }
   public function getUserDetailsUnique($id = '0', $role_name = '')
   {
      $sql = "SELECT u.*,r.name as role_name,r.id as role_id FROM users as u join roles_users as ru on ru.user_id = u.id join roles as r on r.id=ru.role_id WHERE u.is_delete = 0 AND r.name != 'login' " . ((isset($id) && !empty($id)) ? ' AND u.id = "' . $id . '"' : '') . ((isset($role_name) && !empty($role_name)) ? ' AND r.name = "' . $role_name . '"' : '');
      ;
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      if ($list)
         return $list[0];
      return FALSE;
   }
   public function getUserRoles()
   {
      $sql   = "SELECT id,name FROM roles WHERE name != 'login' AND is_delete = 0 AND is_active = 0";
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list;
   }
   public function getUserRoleById($role_id)
   {
      $sql   = "SELECT name FROM roles WHERE is_delete = 0 AND is_active = 0" . ((isset($role_id) && !empty($role_id)) ? ' AND id = "' . $role_id . '"' : '');
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list[0]['name'];
   }
   public function getUsersRoleByUserId($userId)
   {
      $sql   = "SELECT role_id FROM roles_users WHERE user_id = '" . $userId . "' and role_id !='1'";
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      $ids   = array();
      if ($list) {
         foreach ($list as $val) {
            $ids[$val['role_id']] = $val['role_id'];
         }
      }
      return $ids;
   }
   public function deleteUser($uid)
   {
      $sql   = "update users set is_delete = '1' WHERE id = " . $uid;
      $query = DB::query(Database::UPDATE, $sql);
      return $query->execute();
   }
   public function get_user_by_condtn($field, $condtn = 1)
   {
      $sql   = "SELECT " . $field . " FROM users where " . $condtn;
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list;
   }
   public function get_users_by_role($role_id = 0)
   {
      $sql   = "SELECT u.* FROM `users` as u JOIN `roles_users` as ru on u.id = ru.user_id JOIN `roles` as r on ru.role_id = r.id WHERE u.deleted = '0' AND r.id = '" . $role_id . "'";
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list;
   }
   /* tags begins*/
   public function random_color_part()
   {
      return str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT);
   }
   public function random_color()
   {
      return "#" . $this->random_color_part() . $this->random_color_part() . $this->random_color_part();
   }
   public function get_user_current_status($userid, $siteid)
   {
      $sql   = "SELECT * FROM user_sites where site_id = '" . $siteid . "' and user_id = '" . $userid . "'";
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list[0]['status'];
   }
   public function update_user_stats($userid, $siteid, $status)
   {
	  $datetime = Helper_Common::get_default_datetime();
      $sql   = "update user_sites set status = '" . $status . "', modified_date = '" . $datetime . "' WHERE  site_id in (" . $siteid . ") and user_id = '" . $userid . "'";
      //echo $sql;
      $query = DB::query(Database::UPDATE, $sql);
      return $query->execute();
   }
   public function get_user_all_status()
   {
      $sql   = "SELECT id,status as text FROM user_status";
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list;
   }
   public function get_user_created_tags($userid, $tag_catid)
   {
      $sql   = "SELECT * FROM tag WHERE tag_cat_id = '" . $tag_catid . "' AND created_by = '" . $userid . "'";
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list;
   }
   public function get_user_tags($userid)
   {
      //$sql = "SELECT t.tag_id,t.tag_title FROM user_tags as ut join tag as t WHERE t.tag_id=ut.tag_id and ut.user_id = '".$userid."'";		
      if (is_array($userid) && count($userid) > 1) {
         $cnt    = count($userid);
         $userid = implode(',', $userid);
         $sql    = "SELECT ut.tag_id,t.tag_title, count(*) as cnt FROM user_tags as ut join tag as t WHERE t.tag_id=ut.tag_id and ut.user_id in (" . $userid . ") group by ut.tag_id having cnt = $cnt";
      } else {
         $userid = (is_array($userid)) ? implode(',', $userid) : $userid;
         $sql    = "SELECT ut.tag_id,t.tag_title FROM user_tags as ut join tag as t WHERE t.tag_id=ut.tag_id and ut.user_id in (" . $userid . ")";
      }
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list;
   }
   public function add_user_tag($tagid, $userid)
   {
      if (is_array($userid)) {
         foreach ($userid as $k => $v) {
            $id[] = DB::insert('user_tags', array(
               'tag_id',
               'user_id'
            ))->values(array(
               $tagid,
               $v
            ))->execute();
         }
      } else {
         list($id) = DB::insert('user_tags', array(
            'tag_id',
            'user_id'
         ))->values(array(
            $tagid,
            $userid
         ))->execute();
      }
      return $id;
   }
   public function add_tag($data)
   {
      //Create tag
	  $datetime = Helper_Common::get_default_datetime();
      $values = array(
         $data['tag_title'],
         $this->random_color(),
         $data['tag_cat_id'],
         $data['created_by'],
         $datetime
      );
      list($id) = DB::insert('tag', array(
         'tag_title',
         'tag_color',
         'tag_cat_id',
         'created_by',
         'created'
      ))->values($values)->execute();
      return $id;
   }
   public function delete_user_tag($userid, $tagid)
   {
      $userid = (is_array($userid)) ? implode(',', $userid) : $userid;
      $sql    = "DELETE FROM user_tags WHERE user_id in ('" . $userid . "') ";
      if ($tagid) {
         $sql .= " and tag_id = $tagid";
      }
      $query = DB::query(Database::DELETE, $sql);
      return $query->execute();
   }
   public function get_user_access_by_condtn($field, $condtn = 1)
   {
      $sql   = "SELECT " . $field . " FROM user_access where " . $condtn;
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list;
   }
   public function update_user($updateStr, $condtnStr)
   {
      $sql   = "update users set " . $updateStr . " WHERE " . $condtnStr;
      $query = DB::query(Database::UPDATE, $sql);
      return $query->execute();
   }
   public function get_subscribers_only()
   {
      $sql   = "SELECT `user_id` , group_concat( `role_id` ) AS roles
				FROM roles_users
				GROUP BY user_id";
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      if (isset($list) && count($list) > 0) {
         $subscriber_ids = '';
         foreach ($list as $key => $value) {
            $roles = explode(",", $value['roles']);
            if (isset($roles) && count($roles) > 0) {
               $role_id = min($roles);
            } else {
               $role_id = $value['roles'];
            }
            if ($role_id == 6) {
               $subscriber_ids .= $value['user_id'] . ',';
            }
         }
         if (isset($subscriber_ids) && $subscriber_ids != '') {
            $subscriber_ids = rtrim($subscriber_ids, ',');
            $subscr_list    = $this->get_user_by_condtn('*', 'id in(' . $subscriber_ids . ')');
            return $subscr_list;
         }
      }
   }
   public function get_listuser_tags($userid)
   {
      $userid = (is_array($userid)) ? implode(',', $userid) : $userid;
      $sql    = "SELECT ut.user_id,group_concat(t.tag_title) as tag_title FROM user_tags as ut join tag as t WHERE t.tag_id=ut.tag_id and ut.user_id in (" . $userid . ") group by ut.user_id";
      $query  = DB::query(Database::SELECT, $sql);
      $list   = $query->execute()->as_array();
      return $list;
   }
   
   public function  get_site_list(){
	    $sql    = "SELECT * from sites where is_active = 1 and is_deleted = 0";
		$query  = DB::query(Database::SELECT, $sql);
		$list   = $query->execute()->as_array();
		return $list;
   }
	public function get_site_subscribers($authid, $siteid, $roleid, $subscriberid ='',$gender='',$limitCurrent = '', $offset = '')
   { 
      $sql   = "select users.*, user_status.status as userstatus, group_concat(DISTINCT tag.tag_title SEPARATOR '@@') as tagdetails from user_sites join users on users.id = user_sites.user_id join user_status on user_status.id =  user_sites.status left outer join user_tags on user_tags.user_id = users.id join roles_users on roles_users.user_id = users.id join roles  on roles_users.role_id = roles.id left outer join tag on tag.tag_id = user_tags.tag_id  join user_sites as us on users.id = us.user_id where roles_users.role_id = '". $roleid . "' and us.status !=4";
      if($siteid !=''){
			$sql .= " and user_sites.site_id IN (" . $siteid . ")";
		}	 
      if($subscriberid !=''){
         $sql .= " and users.id in (".$subscriberid.")";
      }	
      if($gender != ''){
         $sql .= " and users.user_gender =".$gender;
      }
      $sql .= " group by users.id";
		if ($limitCurrent != '') {
         $sql .= " LIMIT " . $offset . " , " . $limitCurrent . "";
      }
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list;
   }
   
   
   public function get_site_trainer_with_profile($siteid, $roleid)
   {
      
      $sql   = "select
                     users.*, user_status.status as userstatus 
               from
                  user_sites join users on users.id = user_sites.user_id
                  join user_status on user_status.id =  user_sites.status
                  join roles_users on roles_users.user_id = users.id
                  join roles  on roles_users.role_id = roles.id
                  ";
      $sql .= " join site_user_profile as spf on spf.userid = user_sites.user_id";
      $sql .= " where
                  roles_users.role_id in (". $roleid . ") and user_sites.status =1";                  
      if($siteid !=''){
			$sql .= " and user_sites.site_id IN (" . $siteid . ")";
		}
      $sql .= " group by users.id";
		//echo $sql;
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list;
   }
   
   public function get_site_trainer_without_profile($siteid, $roleid, $uids)
   {
      
      $sql   = "select
                     users.*, user_status.status as userstatus
               from
                  user_sites join users on users.id = user_sites.user_id
                  join user_status on user_status.id =  user_sites.status
                  join roles_users on roles_users.user_id = users.id
                  join roles  on roles_users.role_id = roles.id
                  ";
      $sql .= " where
                  roles_users.role_id in (". $roleid . ") and user_sites.status =1";

      if($uids)                  
         $sql .=" and users.id not in ($uids)";
                  
      if($siteid !=''){
			$sql .= " and user_sites.site_id IN (" . $siteid . ")";
		}
      $sql .= " group by users.id";
		//echo $sql;
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list;
   }
   
   
   public function get_manager_list($authid, $siteid, $roleid,$limitCurrent = '', $offset = '')
   { //echo $siteid; //die;
      $sql   = "select users.id, users.user_fname ,users.user_lname from user_sites join users on users.id = user_sites.user_id join user_status on user_status.id =  user_sites.status left outer join user_tags on user_tags.user_id = users.id join roles_users on roles_users.user_id = users.id join roles  on roles_users.role_id = roles.id left outer join tag on tag.tag_id = user_tags.tag_id  join user_sites as us on users.id = us.user_id where roles_users.role_id = '" . $roleid . "'";

		if($siteid !=''){
			$sql .= " and user_sites.site_id IN (" . $siteid . ")";
		}	
	
		$sql .= " and us.status !=4 group by users.id";
		
	    if ($limitCurrent != '') {
         $sql .= " LIMIT " . $offset . " , " . $limitCurrent . "";
       }
			//echo $sql; die;
		
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list;
   }
   
   
	public function filtersubscriber($id = '', $gender = '', $from = '', $to = '')
   {
      $where = '';
      if ($id != '') {
         $where[] = "users.id in ($id)";
      }
      if ($gender != '') {
         //$gender = implode(',', $gender);
         if ($gender) {
            $where[] = "users.user_gender in ($gender)";
         }
      } else {
         $where[] = "users.user_gender in (1,2)";
      }
      if ($from != '' && $to != '') {
         $where[] = "floor(datediff (now(), users.user_dob)/365)>$from and floor(datediff (now(), users.user_dob)/365)<$to";
      }
      if ($where) {
         $where = " and " . implode(" and ", $where);
      }
		$siteid     = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '1');
      //$sql = "SELECT id,user_fname, user_lname, user_dob, user_gender, floor(datediff (now(), user_dob)/365) as age FROM users where banned=0 $where";
      $sql   = "select
							users.*, user_status.status as userstatus, group_concat(tag.tag_title SEPARATOR '@@') as tagdetails
					from
							user_sites
					left join users on users.id = user_sites.user_id
					left join user_status on user_status.id =  user_sites.status
					left outer join user_tags on user_tags.user_id = users.id
					left join roles_users on roles_users.user_id = users.id
					left join roles  on roles_users.role_id = roles.id
					left outer join tag on tag.tag_id = user_tags.tag_id
					where
						user_sites.site_id = '" . $siteid . "' $where group by users.id";
		
		//echo $sql; die;
		
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list;
   }
   	   
}