<?php
defined('SYSPATH') or die('No direct access allowed.');
class Model_Admin_User extends Model
{
   public function validate_user_login($arr)
   {
      return Validation::factory($arr)->rule('user_email', 'not_empty')->rule('password', 'not_empty')->rule('user_email', 'Model_Admin_User::userSiteCheck', array(
         $arr['user_email'],
         ':validation'
      ));
   }
   public static function userSiteCheck($user_email, Validation $validation)
   {
      if (stripos($user_email, '@')) {
         $userid = DB::select(array(
            DB::expr('id'),
            'userid'
         ))->from('users')->where('user_email', '=', $user_email)->execute()->get('userid');
         if (empty($userid)) {
            $site_id = DB::select(array(
               DB::expr('site_id'),
               'site_id'
            ))->from('users')->where('user_email', '=', $user_email)->execute()->get('site_id');
            $validation->error('user_email', 'userEmailOrPhoneNotMatch');
         } else {
            $userSites = Helper_Common::getAllSiteIdByUserAdmin($userid);
            if (empty($userSites)) {
               $validation->error('user_email', 'userEmailNotAllowToAdmin');
            }
         }
      }
   }
   public function validate_user_password($arr)
   {
      return Validation::factory($arr)->rule('new_pass', 'not_empty')->rule('conf_pass', 'not_empty')->rule('new_pass', 'Model_User::resetPasswordCheck', array(
         $arr['new_pass'],
         $arr['conf_pass'],
         ':validation'
      ));
   }
   public function validate_user_profile($arr)
   {
      //print_r($arr); die;
      /*
      $valid = Validation::factory($arr)
      ->rule('firstname', 'not_empty')
      ->rule('lastname', 'not_empty')
      ->rule('profile_img', 'Model_admin_user::check_profile_img',array($arr["profile_img"],'profile_img',':validation'))
      ->rule('background', 'not_empty')
      ->rule('qualifications', 'Model_admin_user::check_array',array($arr["qualifications"],'qualifications',':validation'))
      ->rule('achievements', 'Model_admin_user::check_array',array($arr["achievements"],'achievements',':validation'))
      ->rule('specialists', 'Model_admin_user::check_array',array($arr["specialists"],'specialists',':validation'))
      
      ;
      */
      $valid = Validation::factory($arr)->rule('specialties', 'Model_admin_user::check_array', array(
         $arr["specialties"],
         'specialties',
         ':validation'
      ));
      /*echo "<pre>";
      print_r($arr);
      print_r($valid);
      die;*/
      return $valid;
   }
   public static function check_array($arr, $field, Validation $validation)
   {
      if (isset($arr) && is_array($arr)) {
         $i = 0;
         foreach ($arr as $k => $v) {
            if (trim($v) == '') {
               $i++;
            }
         }
         if ($i > 0) {
            return $validation->error($field, 'not_empty');
         }
         return true;
      } else {
         return $validation->error($field, 'not_empty');
      }
   }
   public static function check_profile_img($arr, $field, Validation $validation)
   {
      if ($arr == "") {
         return $validation->error($field, 'pleaseuploadprofileimage');
      }
      return true;
   }
   public function validate_user_create($arr)
   {
      return Validation::factory($arr)->rule('user_fname', 'not_empty')->rule('user_lname', 'not_empty')->rule('user_email', 'not_empty')->rule('user_email', 'Model_User::userSignupEmailCheck', array(
         $arr['user_reenter_email'],
         $arr['user_email'],
         ':validation'
      ))->rule('password', 'not_empty')->rule('password', 'min_length', array(
         ':value',
         6
      ))->rule('birthday_month', 'not_empty')->rule('birthday_day', 'not_empty')->rule('birthday_year', 'not_empty')->rule('birthday_year', 'Model_User::userSignupBirthCheck', array(
         $arr['birthday_month'],
         $arr['birthday_day'],
         $arr['birthday_year'],
         ':validation'
      ));
   }
   public static function userSignupEmailCheck($reenter_email, $user_email, Validation $validation)
   {
      if ($reenter_email == $user_email) {
         if (stripos($user_email, '@')) {
            if ((bool) DB::select(array(
               DB::expr('COUNT(*)'),
               'total_count'
            ))->from('users')->where('user_email', '=', $user_email)->execute()->get('total_count')) {
               $query = DB::select('user_access')->from('users')->where('user_email', '=', $user_email)->execute();
               $list  = $query->as_array();
               if (isset($list) && count($list) > 0) {
                  if ($list[0]['user_access'] == 6) {
                     // not yet confirmed
                     $validation->error('user_email', 'userEmailNotConfirmed');
                  } else if ($list[0]['user_access'] == 1) {
                     // confirmed
                     $validation->error('user_email', 'userEmailOrPhoneConfirmed');
                  }
               }
               //$validation->error('user_email', 'userEmailOrPhoneNotUnique');
            } else {
               return true;
            }
         } else {
            $justNums = preg_replace("/[^0-9]/", '', $user_email);
            //eliminate leading 1 if its there
            if (strlen($justNums) == 11)
               $justNums = preg_replace("/^1/", '', $justNums);
            if (is_numeric($justNums)) {
               $query = DB::select('user_access')->from('users')->where('user_mobile', '=', $user_email)->execute();
               $list  = $query->as_array();
               if (isset($list) && count($list) > 0) {
                  if ($list[0]['user_access'] == 6) {
                     // not yet confirmed
                     $validation->error('user_email', 'userMobileNotConfirmed');
                  } else if ($list[0]['user_access'] == 1) {
                     // confirmed
                     $validation->error('user_email', 'userEmailOrPhoneConfirmed');
                  }
               }
               return true;
            }
            $validation->error('user_email', 'userEmailOrPhoneInvalid');
         }
      } else {
         $validation->error('user_email', 'userEmailOrPhoneNotMatch');
      }
   }
   public static function resetPasswordCheck($newpass, $confpass, Validation $validation)
   {
      if (trim($newpass) != trim($confpass)) {
         $validation->error('new_pass', 'passwordIncorrect');
      }
   }
   public static function userSignupBirthCheck($month, $day, $year, Validation $validation)
   {
      $dateofbirth = date_parse($day . '-' . $month . '-' . $year);
      if ($dateofbirth["error_count"] == 0 && $dateofbirth["warning_count"] == 0 && checkdate($dateofbirth["month"], $dateofbirth["day"], $dateofbirth["year"])) {
         if (time() < strtotime('+17 years', strtotime($day . '-' . $month . '-' . $year)))
            $validation->error('birthday_year', 'userDateOfBirthUnder17');
         else
            return true;
      } else {
         $validation->error('birthday_year', 'userDateOfBirthInvalid');
      }
   }
   public function getUserDetails($role_name = '', $search_email = '', $search_role = '')
   {
      $sql   = "SELECT u.id,u.email,u.first_name,u.last_name,u.gender,u.mobile_phone,u.photo,u.date_created,u.is_active,r.name as role_name FROM users as u join roles_users as ru on ru.user_id = u.id join roles as r on r.id=ru.role_id WHERE u.is_delete = 0 AND r.name != 'login' " . ((isset($role_name) && !empty($role_name)) ? ' AND r.name = "' . $role_name . '"' : '') . ((isset($search_email) && !empty($search_email)) ? ' AND u.email like "' . $search_email . '%"' : '') . ((isset($search_role) && !empty($search_role)) ? ' AND r.id = "' . $search_role . '"' : '');
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list;
   }
   public function getUserDetailsUnique($id = '0', $role_name = '')
   {
      $sql = "SELECT u.*,r.name as role_name,r.id as role_id FROM users as u join roles_users as ru on ru.user_id = u.id join roles as r on r.id=ru.role_id WHERE u.deleted = 0 AND r.name != 'login' " . ((isset($id) && !empty($id)) ? ' AND u.id = "' . $id . '"' : '') . ((isset($role_name) && !empty($role_name)) ? ' AND r.name = "' . $role_name . '"' : '');
      ;
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      if ($list)
         return $list[0];
      return FALSE;
   }
   public function getUserDetailsUniqueForUpdate($id = '0', $role_name = '')
   {
      $sql = "SELECT u.*,r.name as role_name,r.id as role_id FROM users as u join roles_users as ru on ru.user_id = u.id join roles as r on r.id=ru.role_id WHERE u.deleted = 0 AND r.name != 'login' AND r.name != 'admin' " . ((isset($id) && !empty($id)) ? ' AND u.id = "' . $id . '"' : '') . ((isset($role_name) && !empty($role_name)) ? ' AND r.name = "' . $role_name . '"' : '');
      ;
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      if ($list)
         return $list[0];
      return FALSE;
   }
   public function get_user_sites($userid)
   {
      $sql   = "SELECT s.*, us.* FROM user_sites as us join sites as s on s.id = us.site_id WHERE us.user_id = '" . $userid . "' and s.is_active=1 and is_deleted=0";
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list;
   }
   public function user_role_load_by_name($rolename)
   {
      $sql   = "SELECT id FROM roles WHERE is_delete = 0 AND is_active = 0 AND name = '" . $rolename . "'";
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list[0]['id'];
   }
   public function user_role_load_by_id($roleid)
   {
      $sql   = "SELECT name FROM roles WHERE is_delete = 0 AND is_active = 0 AND id = '" . $roleid . "'";
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list[0]['name'];
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
   public function getUserRoleByName($role_name)
   {
      $sql   = "SELECT name FROM roles WHERE is_delete = 0 AND is_active = 0" . ((isset($role_name) && !empty($role_name)) ? ' AND name = "' . $role_name . '"' : '');
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
   public function getUsersRoleNamesByUserId($userId)
   {
      $sql   = "SELECT ru.role_id,r.name FROM roles_users AS ru JOIN roles AS r ON r.id=ru.role_id WHERE ru.user_id = '" . $userId . "' and ru.role_id !='1'";
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      $ids   = array();
      if ($list) {
         foreach ($list as $val) {
            $ids[$val['name']] = $val['name'];
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
   public function contact_status_update($user_id, $site_id, $contact_status)
   {
      $sql   = "update user_sites set contact_status = $contact_status WHERE user_id = " . $user_id . " and site_id=" . $site_id;
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
   public function getAllSubscriberCount($site_id)
   {
      $sql   = "SELECT count(*) AS total_subscribers FROM users as u JOIN roles_users as ru ON ru.user_id = u.id JOIN roles as r ON r.id=ru.role_id JOIN user_sites AS us ON us.user_id = u.id WHERE u.deleted = 0 AND r.name = 'register'  and us.site_id in ($site_id)";
		
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list[0]['total_subscribers'];
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
   public function get_feed_details($user_id = '', $site = '', $filter = '', $limit, $offset)
   {
      $sql = "SELECT af.user,af.id,af.json_data,af.created_date,af.type_id,ft.type, at.action, af.context_date
				FROM `activity_feed` AS af
				LEFT JOIN feed_type AS ft ON ( af.feed_type = ft.id )
				LEFT JOIN action_type AS at ON ( af.action_type = at.id ) " . ($site ? "" : ' JOIN sites as s ON (s.id = af.site_id)') . "
				WHERE af.status =0";
      if ($user_id) {
         $sql .= " AND af.user in (" . $user_id . ")";
      }
      if ($site) {
         $sql .= " and af.site_id in (" . $site . ") ";
      }
      if (isset($filter["fdate"]) && isset($filter["tdate"])) {
         $sql .= " and af.created_date between '" . date("Y-m-d 00:00:00", strtotime($filter["fdate"])) . "' and '" . date("Y-m-d 23:59:59", strtotime($filter["tdate"])) . "'";
      }
      if (isset($filter["feedtype"]) && $filter["feedtype"] != '') {
         $sql .= " and af.feed_type in (" . $filter["feedtype"] . ") ";
      }
      $sql .= " order by af.id desc, af.created_date desc";
      if ($limit != '' && $offset >= 0) {
         $sql .= " limit $offset, $limit";
      }
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return ($list) ? $list : false;
   }
   public function get_table_details($table, $field)
   {
      $sql   = "SELECT " . $field . " FROM " . $table;
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list;
   }
   public function get_table_details_by_condtn($table, $field, $condtn = 1)
   {
      $sql   = "SELECT " . $field . " FROM " . $table . " where " . $condtn;
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list;
   }
   public function updateManagerUserStatus($updateStr, $condtnStr)
   {
      $sql   = "update user_role_status set " . $updateStr . " WHERE " . $condtnStr;
      $query = DB::query(Database::UPDATE, $sql);
      return $query->execute();
   }
   public function getAssignedWorkoutPlanCount($user_id, $site_id)
   {
      $sql   = "SELECT count(*) AS total_assign FROM wkout_assign_gendata as w join user_sites as us on us.user_id=w.user_id where w.site_id=$site_id and  w.wkout_id!=0 and w.user_id=" . $user_id . " and w.status_id=1 ";
      //echo $sql; exit;
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list[0]['total_assign'];
   }
   public function updateUserSites($updateStr, $condtnStr)
   {
      $sql   = "update user_sites set " . $updateStr . " WHERE " . $condtnStr;
      $query = DB::query(Database::UPDATE, $sql);
      return $query->execute();
   }
   public function get_useremail_byid($id)
   {
      $query   = DB::select('user_email')->from('users')->where('id', '=', $id)->execute();
      $results = $query->as_array();
      if ($results) {
         return $results[0]['user_email'];
      }
   }
   public function get_multible_useremail_byid($ids)
   {
      $emailarray = array();
      foreach ($ids as $uid) {
         $query   = DB::select('user_email')->from('users')->where('id', '=', $uid)->execute();
         $results = $query->as_array();
         if ($results) {
            array_push($emailarray, $results[0]['user_email']);
         }
      }
      return $emailarray;
   }
   public function get_errorfeeds($etype)
   {
      $sql   = "SELECT * FROM error_feed where status=0";
		if($etype!=''){
			$sql .= " and  error_type=$etype ";
		}
		$sql   .= " order by id desc, read_status asc";
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list;
   }
	
	public function get_errorfeeds_readstatus($etype)
   {
      $sql   = "SELECT * FROM error_feed where status=0 and  read_status=0";
		if($etype!=''){
			$sql .= " and  error_type=$etype ";
		}
		$sql   .= " order by id desc";
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list;
   }
	
   public function get_viewerrorfeeds($id)
   {
      $datetime = Helper_Common::get_default_datetime();
      $sql   = "update error_feed set modified_date='" . $datetime . "', read_status=1 WHERE id=" . $id;
      $query = DB::query(Database::UPDATE, $sql)->execute();
		
		$sql   = "SELECT * FROM error_feed as e
					left join users as u on e.user_id=u.id
					left join sites as s on e.site_id=s.id
					where e.id= $id
					order by e.id desc";
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return ($list) ? $list[0] : '';
   }
	public function remove_error_feed($id)
   {
      $results = DB::delete('error_feed')->where('id', '=', $id);
      $results = $results->execute();
      return $results;
   }
   public function update_error_feed($id, $status)
   {
      $datetime = Helper_Common::get_default_datetime();
      $sql   = "update error_feed set modified_date='" . $datetime . "', status=" . $status . " WHERE id=" . $id;
      $query = DB::query(Database::UPDATE, $sql)->execute();
      return ($query) ? true : false;
   }
   public function get_loggoal_details($user_id, $currentsiteid, $limit, $offset)
   {
      $sql   = 'select wlg.wkout_log_id as wklogid,
					wlg.wkout_id,
					wlg.from_wkout,
					wlg.wkout_group,
					wlg.wkout_title,
					wlg.wkout_color,
					wlg.wkout_log_order	,
					wlg.user_id,
					wlg.status_id ,
					wlg.site_id,
					wlg.access_id,
					wlg.wkout_focus,
					wlg.wkout_poa,
					wlg.wkout_poa_time,
					wlg.assigned_date,
					wlg.created,
					wlg.modified,
					wlg.modified_by,
					wlg.wkout_status, 
					wlg.note_wkout_intensity,
					wlg.note_wkout_remarks,
					wlgg.goal_id,
					wlgg.wkout_log_id as wkgglogid,
					wlgg.goal_unit_id,
					wlgg.goal_group,
					wlgg.goal_title,
					wlgg.goal_title_self,
					wlgg.goal_order,
					wlgg.user_id,
					wlgg.status_id,
					wlgg.set_status,
					wlgg.note_set_intensity,
					wlgg.note_set_remarks,
					ugd.musprim_id,
					umc.muscle_title
					from wkout_log_gendata as wlg 
					join wkout_log_goal_gendata as wlgg on wlg.wkout_log_id = wlgg.wkout_log_id 
					join unit_gendata as ugd  on ugd.unit_id = wlgg.goal_unit_id
					join unit_muscle as umc on umc.muscle_id = ugd.musprim_id
					where wlg.user_id =  "' . $user_id . '" order by ugd.musprim_id asc '; //and wlg.wkout_status = 1 
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list;
   }
   public function getAllRoleAccessByUserId($userId)
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
   public function getUsersProfilePhoto($user_id)
   {
      $sql   = "SELECT i.img_url FROM img AS i LEFT JOIN users AS u ON u.avatarid=i.img_id 
				WHERE i.status_id!=4 AND u.avatarid !='' AND u.id='" . $user_id . "' "; //AND i.subfolder_id=4 
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      if (isset($list['0']['img_url']) && $list['0']['img_url'] != '') {
         return $list['0']['img_url'];
      } else {
         return '';
      }
   }
   public function check_profile_exist($userid)
   {
      $sql   = "SELECT * FROM site_user_profile WHERE userid = '" . $userid . "'";
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return ($list) ? true : false;
   }
   public function get_users($userid)
   {
      $sql   = "SELECT user_fname as firstname, user_lname as lastname FROM users WHERE id = '" . $userid . "'";
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list;
   }
	
	public function get_users_details($userid)
   {
      $sql   = "SELECT id as userid, user_fname as firstname, user_lname as lastname, avatarid as profile_img FROM users WHERE id in (" . $userid . ")";
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list;
   }
	
   public function get_profile($userid)
   {
      $sql   = "SELECT * FROM site_user_profile WHERE userid = '" . $userid . "'";
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list;
   }
   public function insertprofile($array)
   {
      $array["updatedon"] = Helper_Common::get_default_datetime();
      $results            = DB::insert('site_user_profile', array_keys($array))->values(array_values($array))->execute();
      return $results[0];
   }
   public function updateuser($array, $userid)
   {
      $results = DB::update('users')->set($array)->where('id', '=', $userid);
      $results = $results->execute();
      return $results;
   }
   public function updateprofile($array, $userid)
   {
      $array["updatedon"] = Helper_Common::get_default_datetime();;
      $results            = DB::update('site_user_profile')->set($array)->where('userid', '=', $userid);
      //echo $results; die;
      $results            = $results->execute();
      return $results;
   }
   public function check_trainer($user_id)
   {
      $sql   = "SELECT * FROM roles_users WHERE role_id=7 and user_id = '" . $user_id . "'";
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return ($list) ? true : false;
   }
   public function get_users_profile_image($imgid)
   {
      $sql   = "SELECT img_url FROM img WHERE img_id='" . $imgid . "' and status_id=1";
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return ($list) ? $list[0] : false;
   }
	public function remove_profile($userid)
   {
		$results = DB::delete('site_user_profile')->where('userid', '=', $userid);
      $results = $results->execute();
      return $results;
   }
   public function get_user_details($email,$sid)
   {
      $sql   = 'select * from users as u join user_sites as us on us.user_id=u.id where u.user_email="'.$email.'"  and us.site_id = '.$sid;
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return ($list)?$list[0]:false;
   }
	/*****Dashboard Statics****/
	public function get_sharedrecords($fdate,$tdate,$site_id,$user_id){
		$condition = '';
		if($fdate && $tdate) {
         $condition .= " and wsg.created between '" . date("Y-m-d 00:00:00", strtotime($fdate)) . "' and '" . date("Y-m-d 23:59:59", strtotime($tdate)) . "'";
      }
      $qry    = "
				SELECT
					wsg.wkout_share_id, wsg.wkout_title,wsg.from_wkout, wsg.wkout_id, wsg.user_id,
					concat (u.user_fname,' ',u.user_lname) as username,wsg.created,u.avatarid as profile_img
				FROM
					wkout_share_gendata as wsg
					JOIN wkout_share_seq as wss on wss.wkout_share_id = wsg.wkout_share_id 
					JOIN users as u on u.id=wsg.user_id
				WHERE
					wss.shared_by in ($user_id) 
					 $condition
				ORDER BY wsg.created desc
				"; //and wsg.site_id in ($site_id)
      //echo "<pre>";echo $qry;       
		$query  = DB::query(Database::SELECT, $qry);
      $return = $query->execute()->as_array();
		return $return;
	}
   
   public function get_sharedrecords_trainers($fdate,$tdate,$site_id,$trainer_ids){
      if($trainer_ids==''){
         return false;
      }
      $condition = '';
		if($fdate && $tdate) {
         $condition .= " and wsg.created between '" . date("Y-m-d 00:00:00", strtotime($fdate)) . "' and '" . date("Y-m-d 23:59:59", strtotime($tdate)) . "'";
      }
      $qry    = "
				SELECT
					wsg.wkout_share_id, wsg.wkout_title,wsg.from_wkout, wsg.wkout_id, wsg.user_id,
					concat (u.user_fname,' ',u.user_lname) as username,wsg.created,u.avatarid as profile_img,
               wss.shared_by,u1.avatarid as profile_img,concat (u1.user_fname,' ',u1.user_lname) as shared_byname,count(wsg.wkout_share_id) as totalshares_by_trainer               
				FROM
					wkout_share_gendata as wsg
					JOIN wkout_share_seq as wss on wss.wkout_share_id = wsg.wkout_share_id 
					JOIN users as u on u.id=wsg.user_id
               JOIN users as u1 on u1.id=wss.shared_by
				WHERE
					wss.shared_by in ($trainer_ids) and wsg.site_id in ($site_id)
					$condition
               GROUP by wss.shared_by
				ORDER BY totalshares_by_trainer desc , shared_byname asc
				";
      //echo "<pre>";echo $qry;      
		$query  = DB::query(Database::SELECT, $qry);
      $return = $query->execute()->as_array();
		return $return;
	}
   
   
   
	public function get_acshares($a_type,$f_type,$fdate,$tdate,$site_id,$wkout_share_id,$cons){
		$ac_cond = '';
		$wkout_share_id = (is_array($wkout_share_id))?implode(",",$wkout_share_id):$wkout_share_id;
		if($fdate && $tdate) {
         $ac_cond = "and af.created_date between '" . date("Y-m-d 00:00:00", strtotime($fdate)) . "' and '" . date("Y-m-d 23:59:59", strtotime($tdate)) . "'";
      }
		$qry            = "SELECT * FROM activity_feed as af where af.feed_type=$f_type AND af.action_type=$a_type
								AND af.site_id in($site_id) and af.type_id in ($wkout_share_id) $ac_cond $cons  GROUP BY af.type_id";
		
      if($a_type==42 && $fdate && $tdate){
         //echo "<br><br>"; echo $qry;
         //$ans = $this->get_statsshares($a_type,$f_type,$fdate,$tdate,$site_id,$wkout_share_id,$cons);
         //echo "<pre>";
         //print_r($ans);
      }
		
      $query          = DB::query(Database::SELECT, $qry);
		$res            = $query->execute()->as_array();
		$cnt    = (isset($res) && is_array($res)) ? count($res) : 0;
		return $cnt;
	}
   
   public function get_tr($id_cl,$title,$totshares,$totshares2,$totshares4,$shares,$shares1,$shares2,$shares3,$shares4,$week,$week_shared_trainers,$month, $month_shared_trainers)
   {
      $class = $child_class = $icon = '';
		if((Helper_Common::is_manager() || Helper_Common::is_admin()) && isset($_POST["user_id"]) && $_POST["user_id"]=='all')
      {
         $class = "class='accordion-toggle ' data-toggle='collapse' id='$id_cl' data-target='.$id_cl'";
         $child_class = "class='expand-child collapse  $id_cl'";
         $icon = "<i class='chevron_toggleable pull-right fa fa-blue fa-chevron-down pointer_hand'></i>";
      }
      
            $str = "<tr $class>
               <td>
               $title
               $icon
               </td>
               <td align='center' title='Week'>";
                  if($shares==0) {  $str .= "n/a"; }
                  else{ $str.=$shares; }
       $str .="</td>
               <td align='center' title='Week Percentage'>";
                  if($totshares==0){   $str .= "n/a"; }
                  else{ $str .=(($totshares > 0 && $shares>0) ? (ceil(($shares / $totshares) * 100))."%" : "n/a");   }
       $str .="</td>
               <td align='center' title='Varience to Last Week'>";
                  if(($shares - $shares1)==0){  $str .= "n/a"; }
                  else{ $str .=((($shares - $shares1)>0)?("+".($shares - $shares1)):($shares - $shares1));  }
       $str .="</td>   
               <td align='center' title='Month'>";
                  if($shares2==0) {  $str .= "n/a"; }
                  else{ $str.=$shares2; }
       $str .="</td>
               <td align='center' title='Month Percentage'>";
                  if($totshares2 == 0 ){$str .="n/a";}
                  else{ $str .=(($totshares2 > 0 && $shares2>0) ? (ceil(($shares2 / $totshares2) * 100))."%": "n/a");  }
       $str .="</td>
               <td align='center' title='varience to Last Month'>";
                  if(($shares2 - $shares3)==0){$str .= "n/a"; }
                  else{$str .=((($shares2 - $shares3)>0)?("+".($shares2 - $shares3)):($shares2 - $shares3));}
        $str.="</td>
               <td align='center' title='Overall'>";
                  if($shares4==0) {  $str .= "n/a"; }
                  else{ $str.=$shares4; }
       $str .="</td>
               <td align='center' title='overall Percentage'>";
                  if($totshares4 == 0){$str .="n/a";}
                  else{ $str .=(($totshares4 > 0 && $shares4>0 ) ? (ceil(($shares4 / $totshares4) * 100))."%" : "n/a");  }
       $str .="</td>
            </tr>";
      if((Helper_Common::is_manager() || Helper_Common::is_admin()) && isset($_POST["user_id"]) && $_POST["user_id"]=='all' && $week_shared_trainers!='' && $month_shared_trainers!=''
        )
      {
         //For week
         $week_leaders = $week_below_average = '';
         if($shares==0){
            $week_sapt = $week_saptpercent = "n/a";
         }else{
            if(isset($week) && !empty($week) && count($week)>0)
            {
               //echo "<br>$shares----".count($week_shared_trainers);
               $week_sapt = ceil($shares/count($week));
               $week_saptpercent = ceil( ($week_sapt*100)/$totshares );
               $week_saptpercent = ($week_saptpercent>0)?$week_saptpercent."%":"n/a";
               //foreach($week as $k=>$v){
               foreach($week_shared_trainers as $k=>$v){
                  $img = URL::base() . 'assets/img/user_placeholder.png';
                  if (isset($v["profile_img"]) && $v["profile_img"] != "") {
                     $getImg = $this->get_users_profile_image($v["profile_img"]);
                     if (file_exists($getImg["img_url"])) {
                        $img = URL::base() . $getImg["img_url"];
                     }
                  }
                  $count = $v["totalshares_by_trainer"];
                  if($count>=$week_sapt)
                  {
                     $week_leaders .= "<div class='row'>";
                     $week_leaders .= "<a href='".URL::base(true)."admin/dashboard/sharerecords/1_".$v["shared_by"]."' title='This week for ".ucfirst($v["shared_byname"])."'>";
                     $week_leaders .= '<div class="col-xs-2">
                                          <div class="img-circle-div" style="margin-left:20px">
                                             <img class="rounded-corners" style="width:35px;height:35px;" src="'.$img.'">
                                          </div>
                                       </div>
                                       ';
                     //$week_leaders .= "<div class='col-xs-10' style='margin-top:7px'>".ucfirst($v["shared_byname"])." ($count)</div>";
                     $week_leaders .= "<div class='col-xs-10' style='margin-top:7px'>".ucfirst($v["shared_byname"])."</div>";
                     $week_leaders .= "</a>";
                     $week_leaders .= "</div>";
                  }else{
                     $week_below_average .= "<div class='row'>";
                     $week_below_average .= "<a href='".URL::base(true)."admin/dashboard/sharerecords/1_".$v["shared_by"]."' title='This week for ".ucfirst($v["shared_byname"])."'>";
                     $week_below_average .= '<div class="col-xs-2">
                                                <div class="img-circle-div" style="margin-left:20px">
                                                   <img class="rounded-corners" style="width:35px;height:35px;" src="'.$img.'">
                                                </div>
                                             </div>
                                             ';
                     //$week_below_average .= "<div class='col-xs-10' style='margin-top:7px'>".ucfirst($v["shared_byname"])." ($count)</div>";
                     $week_below_average .= "<div class='col-xs-10' style='margin-top:7px'>".ucfirst($v["shared_byname"])."</div>";
                     $week_below_average .= "</a>";
                     $week_below_average .= "</div>";
                  }
               }
            }
            else
            {
               $week_sapt = $week_saptpercent = "n/a";
            }
         }
         //For month
         $month_leaders = $month_below_average = '';
         if($shares2==0){
            $month_sapt = $month_saptpercent = "n/a";
         }else{
            if(isset($month) && !empty($month) && count($month)>0)
            {
               //echo "<br>$shares2----".count($month_shared_trainers);
               $month_sapt = ceil($shares2/count($month));
               $month_saptpercent = ceil( ($month_sapt*100)/$totshares2 );
               $month_saptpercent = ($month_saptpercent>0)?$month_saptpercent."%":"n/a";
               //foreach($month as $k=>$v){
               foreach($month_shared_trainers as $k=>$v){
                  $img = URL::base() . 'assets/img/user_placeholder.png';
                  if (isset($v["profile_img"]) && $v["profile_img"] != "") {
                     $getImg = $this->get_users_profile_image($v["profile_img"]);
                     if (file_exists($getImg["img_url"])) {
                        $img = URL::base() . $getImg["img_url"];
                     }
                  }
                  $count = $v["totalshares_by_trainer"];
                  if($count>=$month_sapt)
                  {
                     $month_leaders .= "<div class='row'>";
                     $month_leaders .= "<a href='".URL::base(true)."admin/dashboard/sharerecords/2_".$v["shared_by"]."' title='This month for ".ucfirst($v["shared_byname"])."'>";
                     $month_leaders .= '<div class="col-xs-2">
                                          <div class="img-circle-div" style="margin-left:20px">
                                             <img class="rounded-corners" style="width:35px;height:35px;" src="'.$img.'">
                                          </div>
                                       </div>
                                       ';
                     //$month_leaders .= "<div class='col-xs-10' style='margin-top:7px'>".ucfirst($v["shared_byname"])." ($count)</div>";
                     $month_leaders .= "<div class='col-xs-10' style='margin-top:7px'>".ucfirst($v["shared_byname"])."</div>";
                     $month_leaders .= "</a>";
                     $month_leaders .= "</div>";
                  }else{
                     $month_below_average .= "<div class='row'>";
                     $month_below_average .= "<a href='".URL::base(true)."admin/dashboard/sharerecords/2_".$v["shared_by"]."' title='This month for ".ucfirst($v["shared_byname"])."'>";
                     $month_below_average .= '<div class="col-xs-2">
                                                <div class="img-circle-div" style="margin-left:20px">
                                                   <img class="rounded-corners" style="width:35px;height:35px;" src="'.$img.'">
                                                </div>
                                             </div>
                                             ';
                     //$month_below_average .= "<div class='col-xs-10' style='margin-top:7px'>".ucfirst($v["shared_byname"])." ($count)</div>";
                     $month_below_average .= "<div class='col-xs-10' style='margin-top:7px'>".ucfirst($v["shared_byname"])."</div>";
                     $month_below_average .= "</a>";
                     $month_below_average .= "</div>";
                  }
               }
            }
            else
            {
               $month_sapt = $month_saptpercent = "n/a";
            }
         }
         $str.="<tr $child_class>
                  <td align='right'>Site Averages per Trainer</td>
                  <td align='center'>$week_sapt</td><td align='center'>$week_saptpercent</td>
                  <td align='center'></td><td align='center'>$month_sapt</td><td align='center'>$month_saptpercent</td>
                  <td align='center'></td><td align='center'></td><td align='center'></td>
               </tr>
               <tr $child_class>
                  <td align='right'>Leaders</td>
                  <td align='left' colspan='3'>".(($week_leaders!='')?$week_leaders:'-')."</td>
                  <td align='left' colspan='3'>".(($month_leaders!='')?$month_leaders:'-')."</td>
                  <td align='center'></td><td align='center'></td>
               </tr>
               <tr $child_class>
                  <td align='right'>Below Average</td>
                  <td align='left' colspan='3'>".(($week_below_average!='')?$week_below_average:'-')."</td>
                  <td align='left' colspan='3'>".(($month_below_average!='')?$month_below_average:'-')."</td>
                  <td align='center'></td><td align='center'></td>
               </tr>
								";
      }
      return $str;
   }
   public function get_statsshares($a_type,$f_type,$fdate,$tdate,$site_id,$wkout_share_id,$cons){
		$ac_cond = '';
		$wkout_share_id = (is_array($wkout_share_id))?implode(",",$wkout_share_id):$wkout_share_id;
		if($fdate && $tdate) {
         $ac_cond = "and af.created_date between '" . date("Y-m-d 00:00:00", strtotime($fdate)) . "' and '" . date("Y-m-d 23:59:59", strtotime($tdate)) . "'";
      }
		$qry            = "select *, count(*) as totalshares_by_trainer from (
                        SELECT af.feed_type,af.action_type,af.site_id,wss.shared_by,u1.avatarid as profile_img,concat (u1.user_fname,' ',u1.user_lname) as shared_byname,count(wsg.wkout_share_id) as totalactivity 
				FROM
					activity_feed as af
					join wkout_share_gendata as wsg on wsg.wkout_share_id=af.type_id
					JOIN wkout_share_seq as wss on wss.wkout_share_id = wsg.wkout_share_id
					JOIN users as u on u.id=af.user
               JOIN users as u1 on u1.id=wss.shared_by
				where
					af.feed_type='$f_type' AND af.action_type='$a_type' AND af.site_id in(209) and
					af.type_id in ($wkout_share_id)
					$ac_cond $cons
				GROUP by af.type_id , wss.shared_by
				ORDER BY shared_byname asc,totalactivity desc ) as new group by shared_by
                        ";
      //echo "<pre>$qry";
		$query          = DB::query(Database::SELECT, $qry);
		$res            = $query->execute()->as_array();
		$cnt    = (isset($res) && is_array($res)) ? $res : 0;
		return $cnt;
	}
   
	/*****Dashboard Statics****/
	
}