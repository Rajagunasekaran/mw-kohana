<?php
/**
 * Extension of the Kohana URL helper class.
 */
class Helper_Common extends Kohana_URL
{
   /**
    * Fetches the URL to the current request uri.
    *
    * @param   bool  make absolute url
    * @param   bool  add protocol and domain (ignored if relative url)
    * @return  string
    */
   public static function currentAdminUrl($absolute = FALSE, $protocol = FALSE)
   {
      $current_url = 'admin/' . strtolower(Request::current()->controller()) . '/' . strtolower(Request::current()->action());
      return $current_url;
   }
	public static function currentUrl($absolute = FALSE, $protocol = FALSE)
   {
      $current_url = strtolower(Request::current()->controller()) . '/' . strtolower(Request::current()->action());
      return $current_url;
   }
   public static function getAddtoHomeStatus()
	{
		$site_id  		= Session::instance()->get('current_site_id');
		$user 			= Auth::instance()->get_user();
		$workoutModel 	= ORM::factory('workouts');
		if($user->pk() && !empty($site_id)){
			return $workoutModel->getAddToHomeEnd($site_id, $user->pk());
		}
		return false;
	}
   public static function getAllowAllAccessByUser($page_id, $flag =''){
		$site_id  		= Session::instance()->get('current_site_id');
		$user 			= Auth::instance()->get_user();
		$workoutModel 	= ORM::factory('workouts');
		if($user->pk() && !empty($site_id) && !empty($page_id)){
			$returnArray = $workoutModel->getAllowAccess($page_id, $user->pk());
			if($flag){
				if(is_array($returnArray) && count($returnArray)>0)
					return $returnArray[$flag];
				return '0';
			}else
				return $returnArray;
		}
		return false;
   }
   public static function updateEmailAutomation($assignId,$method = 'send'){
	    $workoutModel 	= ORM::factory('workouts');
		$assignArray = array();
		if($method == 'send')
			$assignArray['is_mail_send'] = 1;
		$workoutModel->updateEmailNotifyByIdStatus($assignId,$assignArray);
   }
   public static function getPageIdbyName($page_name){
	   $workoutModel 	= ORM::factory('workouts');
	   return $workoutModel->checkPageName($page_name);
   }
   public static function getAccessNotify(){
	   
   }
   public static function emailTemplateStatusArray()
   {
      $statusArray = array(
         '1' => 'Published',
         '2' => 'Unpublished',
         '3' => 'Delete'
      );
      return $statusArray;
   }
   public static function getDeliveryStatusArray()
   {
      $statusArray = array(
         '0' => 'No',
         '1' => 'Yes'
      );
      return $statusArray;
   }
   public static function encryptPassword($string)
   {
      $encrypt        = Encrypt::instance();
      $encrypted_data = $encrypt->encode($string);
     if ($encrypted_data)
        {
            $encrypted_data = strtr(
                    $encrypted_data,
                    array(
                        '+' => '*',
                        '=' => '-',
                        '/' => '~',
						'>' => ')',
						'<' => '('
                    )
                );
        }
      return $encrypted_data;
   }
   public static function decryptPassword($string)
   {
      $encrypt      = Encrypt::instance();
     $string = strtr(
                $string,
                array(
                    '*' => '+',
                    '-' => '=',
                    '~' => '/',
					')' => '>',
					'(' => '<'
                )
        );
      $decoded_data = $encrypt->decode($string);
      return $decoded_data;
   }
   public static function genderArray()
   {
      $statusArray = array(
         '1' => 'Male',
         '2' => 'Female'
      );
      return $statusArray;
   }
   public static function change_default_datetime($datetime){
	  $datetimeFormat 	= date('Y-m-d H:i:s',strtotime($datetime));
	  $user_timezone 	= Session::instance()->get('user_timezone');
	  if(empty($user_timezone))
		$user_timezone  = Session::instance()->get('site_timezone');
	  $user_dateformat 	= Session::instance()->get('user_datetimeformat');
	  if(empty($user_dateformat))
		$user_dateformat 	= Session::instance()->get('site_datetimeformat');
	  if(!empty($user_timezone))
		$timezone_array = explode(" ", $user_timezone);
	  else
		$timezone_array = explode(" ", 'Australia/Sydney (+10|00)');
	  if(empty($user_dateformat))
		$user_dateformat = 'Y-m-d H:i:s';
	  $datetimecur 	= new DateTime($datetimeFormat);
	  $la_timecur 	= new DateTimeZone($timezone_array[0]);
	  $datetimecur->setTimezone($la_timecur);
	  return $datetimecur->format((string) $user_dateformat);
   }
   
   public static function change_default_date_dob($datetime){
	  $datetimeFormat 	= date('Y-m-d H:i:s',strtotime($datetime));
	  $user_timezone 	= Session::instance()->get('user_timezone');
	  if(empty($user_timezone))
		$user_timezone  = Session::instance()->get('site_timezone');
	  $user_dateformat 	= Session::instance()->get('user_dateformat');
	  if(empty($user_dateformat))
		$user_dateformat 	= Session::instance()->get('site_dateformat');
	  if(!empty($user_timezone))
		$timezone_array = explode(" ", $user_timezone);
	  else
		$timezone_array = explode(" ", 'Australia/Sydney (+10|00)');
	  if(empty($user_dateformat))
		$user_dateformat = 'Y-m-d';
	  $datetimecur 	= new DateTime($datetimeFormat);
	  $la_timecur 	= new DateTimeZone($timezone_array[0]);
	  $datetimecur->setTimezone($la_timecur);
	  return $datetimecur->format((string) $user_dateformat);
   }
   public static function change_default_date_static($datetime, $user_timezone, $user_dateformat){
	  $datetimeFormat 	= date('Y-m-d H:i:s',strtotime($datetime));
	  if(!empty($user_timezone))
		$timezone_array = explode(" ", $user_timezone);
	  else
		$timezone_array = explode(" ", 'Australia/Sydney (+10|00)');
	  if(empty($user_dateformat))
		$user_dateformat = 'Y-m-d';
	  $datetimecur 	= new DateTime($datetimeFormat);
	  $la_timecur 	= new DateTimeZone($timezone_array[0]);
	  $datetimecur->setTimezone($la_timecur);
	  return $datetimecur->format((string) $user_dateformat);
   }
   public static function get_default_date($givenDate = '',$format = ''){
	  if(empty($givenDate))
		$datetimecur 	= new DateTime();
	  else{
		$datetimeFormat = date('Y-m-d',strtotime($givenDate));
		$datetimecur 	= new DateTime($datetimeFormat);
	  }
	  $la_timecur 	= new DateTimeZone('Australia/Sydney');
	  $datetimecur->setTimezone($la_timecur);
	  return $datetimecur->format((!empty($format) ? $format : 'Y-m-d'));
   }
   public static function get_default_time($givenDate = '', $format= ''){
	  if(empty($givenDate))
		$datetimecur 	= new DateTime();
	  else{
		$datetimeFormat = date('Y-m-d H:i:s',strtotime($givenDate));
		$datetimecur 	= new DateTime($datetimeFormat);
	  }
	  $la_timecur 	= new DateTimeZone('Australia/Sydney');
	  $datetimecur->setTimezone($la_timecur);
	  return $datetimecur->format((!empty($format) ? $format : 'H:i:s'));
   }
   
   public static function get_default_datetime($givenDate = ''){
	  if(empty($givenDate))
		$datetimecur 	= new DateTime();
	  else{
		$datetimeFormat = date('Y-m-d H:i:s',strtotime($givenDate));
		$datetimecur 	= new DateTime($datetimeFormat);
	  }
	  $la_timecur 	= new DateTimeZone('Australia/Sydney');
	  $datetimecur->setTimezone($la_timecur);
	  return $datetimecur->format('Y-m-d H:i:s');
   }
   public static function createActivityFeed($activity_feed){
	  $activityfeedModel	= ORM::factory('activityfeed');
	  return $activityfeedModel->insert('activity_feed',$activity_feed);
   }
   public static function getUserEmailTime($user_id = '',$site_id =''){
	  if(empty($user_id)){
		  $user_id = Auth::instance()->get_user()->pk();
		  $site_id = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
	  }
	  $sql   = "SELECT timezone, time_to_send_email FROM user_settings WHERE user_id=$user_id and site_id=$site_id";
      $query = DB::query(Database::SELECT, $sql);
      $result  = $query->execute()->as_array();
      if (isset($result) && count($result) > 0) {
         return self::get_default_time($result[0]['time_to_send_email'],"H:i:00");
      }
      return '00:00:00';
   }
   public static function time_ago($date)
   {
      if (empty($date)) {
         return "No date provided";
      }
      $periods   = array(
         "second",
         "minute",
         "hour",
         "day",
         "week",
         "month",
         "year",
         "decade"
      );
      $lengths   = array(
         "60",
         "60",
         "24",
         "7",
         "4.35",
         "12",
         "10"
      );
      $now       = time();
      $unix_date = strtotime($date);
      // check validity of date
      if (empty($unix_date)) {
         return "Bad date";
      }
      // is it future date or past date
      if ($now > $unix_date) {
         $difference = $now - $unix_date;
         $tense      = "ago";
      } else {
         $difference = $unix_date - $now;
         $tense      = "from now";
      }
      for ($j = 0; $difference >= $lengths[$j] && $j < count($lengths) - 1; $j++) {
         $difference /= $lengths[$j];
      }
      $difference = round($difference);
      if ($difference != 1) {
         $periods[$j] .= "s";
      }
      return "$difference $periods[$j] {$tense}";
   }
   public static function get_age($date)
   {
      /*
      $birthDate = date('d/m/Y', strtotime($date));
      //explode the date to get month, day and year
      $birthDate = explode("/", $birthDate);
      //get age from date or birthdate
      $age       = (date("md", date("U", mktime(0, 0, 0, $birthDate[0], $birthDate[1], $birthDate[2]))) > date("md") ? ((date("Y") - $birthDate[2]) - 1) : (date("Y") - $birthDate[2]));
      */
      //$age ='5';
      $from = new DateTime($date);
      $to   = new DateTime('today');
      $age  = $from->diff($to)->y;
      return $age;
   }
   /*****************************************Prabakaran Starts*******************/
	
	public static function get_active_sites()
   {
		$sql   = "SELECT id FROM sites WHERE is_active=1 and is_deleted=0";
      $query = DB::query(Database::SELECT, $sql);
      $result  = $query->execute()->as_array();
      if(isset($result) && is_array($result) && count($result)){
			$temp = array();
			foreach($result as $k=>$v){
				$temp[] = $v["id"];
			}
			return $temp;
		}else{
			return false;
		}
	}
	public static function get_active_sites_withname()
   {
		$sql   = "SELECT id,name FROM sites WHERE is_active=1 and is_deleted=0";
      $query = DB::query(Database::SELECT, $sql);
      $result  = $query->execute()->as_array();
      if(isset($result) && is_array($result) && count($result)){
			$temp = array();
			$x=0;
			foreach($result as $k=>$v){
				$temp[$x]["site_id"] = $v["id"];
				$temp[$x]["name"] = $v["name"];
				$x++;
			}
			return $temp;
		}else{
			return false;
		}
	}
	
	
	public static function get_contact_status($user_id, $site_id = '')
   {
	  $sql   = "SELECT contact_status FROM user_sites WHERE user_id=$user_id and site_id=$site_id";
      $query = DB::query(Database::SELECT, $sql);
      $result  = $query->execute()->as_array();
      if (isset($result) && count($result) > 0) {
         return $result[0]['contact_status'];
      }
      return false;
	}
	
	
   public static function getRateXrData($unitid, $siteid = '')
   {
      $sql = "SELECT xrgd.title,xrgdr.*,concat(u.user_fname,' ',u.user_lname) as user  FROM  unit_gendata as xrgd join unit_gendata_rating as xrgdr on xrgd.unit_id = xrgdr.unit_id join users as u on xrgdr.user_id=u.id where xrgdr.unit_id = $unitid";
      if ($siteid) {
         $sql .= " and xrgdr.site_id=$siteid";
      }
      $sql .= " order by xrgdr.rate_id desc";
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      if (isset($list) && count($list) > 0) {
         $r       = 0;
         $x       = 0;
         $extitle = '';
         foreach ($list as $k => $v) {
            if ($x == 0) {
               $extitle = $v["title"];
            }
            $r = $r + $v["rate_value"];
            $x++;
         }
         $rate             = round($r / $x, 2);
         $temp["title"]    = $extitle;
         $temp["rating"]   = $rate;
         $temp["comments"] = $list;
         return $temp;
      }
      return false;
   }
   public static function get_count_role_by_users($siteid = '')
   {
      $sql = "SELECT  concat(r.id,').',r.name),count(*) as cnt FROM  roles_users as ru join users as u join roles as r  on ru.user_id=u.id and r.id=ru.role_id  join user_sites as us on us.user_id=ru.user_id join  user_status as uss on uss.id=us.status and us.status=1";
      if ($siteid) {
         $sql .= " and us.site_id=$siteid";
      }
      $sql .= " group by r.name order by r.id asc";
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      if (isset($list) && count($list) > 0) {
         return $list;
      }
      return false;
   }
   public static function get_role($arg)
   {
      $sql   = "SELECT id FROM roles WHERE is_delete = 0 AND is_active = 0 AND name = '" . $arg . "'";
      $query = DB::query(Database::SELECT, $sql);
      $role  = $query->execute()->as_array();
      if (isset($role) && count($role) > 0) {
         return $role[0]['id'];
      }
      return false;
   }
   public static function get_role_by_users($roleid, $siteid = '')
   {
      $sql = "SELECT us.contact_status,uss.status as title,us.status, ru.role_id,u.id, concat(u.user_fname,' ',u.user_lname) as username,u.user_fname,u.user_lname, u.user_email,r.name FROM  roles_users as ru join users as u join roles as r  on ru.user_id=u.id and r.id=ru.role_id join user_sites as us on us.user_id=ru.user_id join user_status as uss on uss.id=us.status  and ru.role_id=$roleid and us.status=1";
      if ($siteid) {
         $sql .= " and us.site_id in ($siteid)";
      }
      $sql .= " group by u.id order by u.id  desc";
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      if (isset($list) && count($list) > 0) {
         return $list;
      }
      return false;
   }
   /*****************************************Prabakaran Ends*******************/
   
   public static function is_trainerOld()
   {
      $user = Auth::instance()->get_user();
      $role = Model::instance('Model/admin/user')->getUsersRoleByUserId($user->id);
      if (isset($role) && count($role) > 0) {
         $role_id = min($role);
      }
      if ($role_id == 7) {
         return true;
      }
      return false;
   }
   public static function is_trainer()
   {
      $user = Auth::instance()->get_user();
      $role = Model::instance('Model/admin/user')->getAllRoleAccessByUserId($user->id);
     if(isset($role) && count($role)>0){
        if (in_array('7',$role)) {
          return true;
        }
     }
      return false;
   }
   public static function profile_photo($user_id)
   {
      return Model::instance('Model/admin/user')->getUsersProfilePhoto($user_id);
   }
   public static function is_register()
   {
      $user = Auth::instance()->get_user();
      if ($user->user_access == '6' || $user->user_access == '8') 
        return false;
      else
      return true;
   }
   public static function is_managerOld()
   {
      $user = Auth::instance()->get_user();
      $role = Model::instance('Model/admin/user')->getUsersRoleByUserId($user->id);
      if (isset($role) && count($role) > 0) {
         $role_id = min($role);
      }
      if ($role_id == 8) {
         return true;
      }
      return false;
   }
   public static function get_user_roles()
   {
     $user = Auth::instance()->get_user();
     $roles = Model::instance('Model/admin/user')->getAllRoleAccessByUserId($user->id);
	 $return_roles = implode(',',$roles); 
     return $return_roles;
   }
   public static function is_manager()
   {
     $user = Auth::instance()->get_user();
      $role = Model::instance('Model/admin/user')->getAllRoleAccessByUserId($user->id);
     if(isset($role) && count($role)>0){
        if (in_array('8',$role)) {
          return true;
        }
     }
      return false;
   }
   public static function is_admin()
   {
     $user = Auth::instance()->get_user();
     $role = Model::instance('Model/admin/user')->getAllRoleAccessByUserId($user->id);
     if(isset($role) && count($role)>0){
        if (in_array('2',$role)) {
          return true;
        }
     }
      return false;
   }
   public static function is_adminOld()
   {
      $user = Auth::instance()->get_user();
      $role = Model::instance('Model/admin/user')->getUsersRoleByUserId($user->id);
      if (isset($role) && count($role) > 0) {
         $role_id = min($role);
      }
      if ($role_id == 2) {
         return true;
      }
      return false;
   }
   public static function getSiteId()
   {
      $user         = Auth::instance()->get_user();
      $site_details = Model::instance('Model/admin/sites')->getAllAssignedSites($user->id);
      if (isset($site_details) && count($site_details) > 0) {
         $site_id = $site_details[0]['site_id'];
      } else {
         $site_id = 1;
      }
      return $site_id;
   }
    public static function getAllSiteId()
   {
		$user         = Auth::instance()->get_user();
		$site_details = Model::instance('Model/admin/sites')->getAllUserSites($user->id);
		if (isset($site_details) && count($site_details) > 0) {
		foreach($site_details as $keys => $value)
			$list[] = $value['site_id'];
		} else {
			$list[] = 1;
		}
		$return_siteIds = implode(',',$list); 
		return $return_siteIds.(count($list) == '1' ? ',1' : '');
   }
   public static function hasAccessBySampleImage($siteid)
   {
      if ($siteid != '') {
         $fetch_condtn = 'id=' . $siteid;
         $getAccesId   = Model::instance('Model/admin/user')->get_table_details_by_condtn('sites', " * ", $fetch_condtn);
         if (isset($getAccesId) && count($getAccesId) > 0 && (isset($getAccesId[0]['sample_images']) && !empty($getAccesId[0]['sample_images']))) {
            return true;
         }
         return false;
      }
   }
   public static function hasAccessBySampleWkouts($siteid)
   {
      if ($siteid != '') {
         $fetch_condtn = 'id=' . $siteid;
         $getAccesId   = Model::instance('Model/admin/user')->get_table_details_by_condtn('sites', " * ", $fetch_condtn);
         if (isset($getAccesId) && count($getAccesId) > 0 && (isset($getAccesId[0]['sample_workouts']) && !empty($getAccesId[0]['sample_workouts']))) {
            return true;
         }
         return false;
      }
   }
   public static function hasAccessByDefaultXr($siteid)
   {
      if ($siteid != '') {
         $fetch_condtn = 'id=' . $siteid;
         $getAccesId   = Model::instance('Model/admin/user')->get_table_details_by_condtn('sites', " * ", $fetch_condtn);
         if (isset($getAccesId) && count($getAccesId) > 0 && (isset($getAccesId[0]['exercise_records']) && !empty($getAccesId[0]['exercise_records']))) {
            return true;
         }
         return false;
      }
   }
   public static function getAllSiteIdBySampleWkout()
   {
      $user         = Auth::instance()->get_user();
      $site_details = Model::instance('Model/admin/sites')->getAllUserSitesBySampleWkout($user->id);
      if (isset($site_details) && count($site_details) > 0) {
      foreach($site_details as $keys => $value)
       $list[] = $value['site_id'];
      } else {
         $list[] = 1;
      }
     $return_siteIds = implode(',',$list); 
      return $return_siteIds;
   }
   public static function getAllSiteIdByDefaultXrcise()
   {
      $user = Auth::instance()->get_user();
      $site_details = Model::instance('Model/admin/sites')->getAllUserSitesByDefaultXrcise($user->id);
      if (isset($site_details) && count($site_details) > 0) {
      foreach($site_details as $keys => $value)
       $list[] = $value['site_id'];
      } else {
         $list[] = 1;
      }
     $return_siteIds = implode(',',$list); 
      return $return_siteIds;
   }
   public static function getAllSiteIdBySampleImg()
   {
      $user = Auth::instance()->get_user();
      $site_details = Model::instance('Model/admin/sites')->getAllUserSitesBySampleImg($user->id);
      if (isset($site_details) && count($site_details) > 0) {
        foreach($site_details as $keys => $value)
          $list[] = $value['site_id'];
      } else {
         $list[] = 1;
      }
     $return_siteIds = implode(',',$list); 
      return $return_siteIds;
   }
   public static function getAllSiteIdByUser($user_id)
   {
      $site_details = Model::instance('Model/admin/sites')->getAllUserSites($user_id);
      if (isset($site_details) && count($site_details) > 0) {
      foreach($site_details as $keys => $value)
         $list[] = $value['site_id'];
      } else {
         $list[] = 1;
      }
      return $list;
   }
	public static function getAllSiteIdByUserAdmin($user_id)
   {
      $site_details = Model::instance('Model/admin/sites')->getAllUserSites($user_id);
      if (isset($site_details) && count($site_details) > 0) {
      foreach($site_details as $keys => $value)
         $list[$value['site_id']] = $value['site_id'];
      } else {
         $list = array();
      }
      return $list;
   }
   public static function SiteId()
   {
      $user         = Auth::instance()->get_user();
      $site_details = Model::instance('Model/admin/sites')->getAllAssignedSites($user->id);
      if (isset($site_details) && count($site_details) > 0) {
         $site_id = $site_details[0]['site_id'];
      } else {
         $site_id = 1;
      }
      return $site_id;
   }
   public static function hasAccess_old($typeName)
   {
      if ($typeName != '') {
         if (self::is_admin()) {
            return true;
         }
         $condtn     = "name like '" . trim($typeName) . "%'";
         $getAccesId = Model::instance('Model/admin/roleaccess')->getRoleAccessTypeByContn('id', $condtn);
         if (isset($getAccesId) && count($getAccesId) > 0) {
            $accessId        = $getAccesId[0]['id'];
            $roleAccessAttay = Session::instance()->get('roleAccessArray');
            if (isset($roleAccessAttay) && in_array($accessId, $roleAccessAttay)) {
               return true;
            }
         }
         return false;
      }
      return false;
   }
   public static function hasAccess($typeName)
   {
      if ($typeName != '') {
         if (self::is_admin()) {
            return true;
         }
         $condtn     = "name like '" . trim($typeName) . "%'";
         $getAccesId = Model::instance('Model/admin/roleaccess')->getRoleAccessTypeByContn('id', $condtn);
         if (isset($getAccesId) && count($getAccesId) > 0) {
            $accessId        = $getAccesId[0]['id'];
            $roleAccessAttay = Session::instance()->get('roleAccessArray');
            if (isset($roleAccessAttay) && in_array($accessId, $roleAccessAttay)) {
               return true;
            }
         }
         return false;
      }
      return false;
   }
   public static function hasSiteAccess($siteid)
   {
      if ($siteid != '') {
         if (self::is_admin()) {
            //return true;
         }
         $fetch_condtn = 'id=' . $siteid;
         $getAccesId   = Model::instance('Model/admin/user')->get_table_details_by_condtn('sites', " * ", $fetch_condtn);
         if (isset($getAccesId) && count($getAccesId) > 0) {
            return $getAccesId[0];
         }
         return false;
      }
      return false;
   }
   public static function isCookieEnable()
   {
      Cookie::set('test_cookie', 'test');
      if (isset($_COOKIE) && count($_COOKIE) > 0) {
         return true;
      } else {
         return false;
      }
   }
   public static function formatedate($date_value)
   {
      return date("Y-m-d", strtotime($date_value));
   }
   public static function updateuserdetails($array, $flag_set)
   {
      DB::update('users')->set(array(
         'user_fname' => $array['firstname'],
         'user_lname' => $array['lastname'],
         'user_dob' => self::formatedate(str_ireplace("/","-",$array['dobuser'])),
         'avatarid' => $array['idavatar'],
		 'user_mobile' => $array['mobile'],
		 'user_gender' => $array['gender']
      ))->where('id', '=', $array['userid'])->execute();
   }
   public static function Timestamp()
   {
      return date("Y-m-d H:i:s", time());
   }
   public static function UserDateFormat($dateval = '')
   {
      $formateddate = (Session::instance()->get('user_dateformat') ? Session::instance()->get('user_dateformat') : 'd M Y');
      if (!empty($dateval)) {
         return date($formateddate, strtotime(date($dateval)));
      } else {
         return date($formateddate);
      }
   }
   public static function UserTimeFormat($timeval = '')
   {
      $formatedtime = (Session::instance()->get('user_timeformat') ? Session::instance()->get('user_timeformat') : 'h:i:s A');
      if (!empty($timeval)) {
         return date($formatedtime, strtotime($timeval));
      } else {
         return date($formatedtime, time());
      }
   }
   public static function getlanguage($language_idsite){
      $sql_lang = " select * from language where language_id = '".$language_idsite."' ";
     // echo "=============".$sql_lang;
      $query_lang = DB::query(Database::SELECT, $sql_lang);
      $queryres_lang = $query_lang->execute()->as_array();  
      $loginuser_lang = $queryres_lang[0]['iso_code'];
      return $loginuser_lang;
   }
   public static function getidlanguage($siteid){
      $sql_langid = " select * from site_settings where site_id = '".$siteid."' ";  
      $sql_langid = DB::query(Database::SELECT, $sql_langid);
      $queryresid_lang = $sql_langid->execute()->as_array(); 
      if(is_array($queryresid_lang) && count($queryresid_lang) > 0) {
          $loginiduser_lang = $queryresid_lang[0]['language'];
      }else{
          $loginiduser_lang = '1';
      }
      return $loginiduser_lang;
    
   }
   
   public static function get_sites_by_subscribers($id){
      
      $sql = "SELECT group_concat(DISTINCT sites.name SEPARATOR ',') as sites_list FROM `user_sites`  join sites on user_sites.site_id = sites.id WHERE user_id =".$id;
      
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list;
   }
	
	public static function get_sites_by_user($id){
      $sql = "SELECT * FROM `user_sites`  join sites on user_sites.site_id = sites.id WHERE user_id =".$id;
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list;
   }
	
   //General update query dh
   public static function updategeneralfn($tablename,$updatefield,$wherecnd){
      $updatequery = "update ".$tablename." set ".$updatefield ." where ".$wherecnd." ";  
      $queryupdate = DB::query(Database::UPDATE, $updatequery)->execute();  
      return $queryupdate;
   }
   //General select query dh
   public static function selectgeneralfn($tablename,$fieldname,$wherecnd){
      $selectquery = "select ".$fieldname." from ".$tablename." where ".$wherecnd." ";
      $queryselect = DB::query(Database::SELECT, $selectquery)->execute()->as_array();  
      return $queryselect;
   }
   //General recurse copy dh
   public static function recurse_copy($src,$dst) { 
       $dir = opendir($src); 
       @mkdir($dst); 
       chmod($dst,0777);
       while(false !== ( $file = readdir($dir)) ) { 
           if (( $file != '.' ) && ( $file != '..' )) { 
               if ( is_dir($src . '/' . $file) ) { 
                   self::recurse_copy($src . '/' . $file,$dst . '/' . $file); 
               } 
               else { 
                   copy($src . '/' . $file,$dst . '/' . $file); 
                   chmod($dst . '/' . $file,0777);
               } 
           } 
       } 
       closedir($dir); 
   } 
   /*for contact status*/
   public static function site_data($site_id) {
    $sql = "SELECT * FROM sites WHERE id=$site_id";
    $query = DB::query(Database::SELECT, $sql);
    $result  = $query->execute()->as_array();
    if (isset($result) && count($result) > 0) {
      return $result[0];
    }
    return false;
  }
  /*by G.R*/
  public static function convertToMyWeightUnit($user_weight='1', $unit, $value=0){
    $equivalweight = 0;
    if($user_weight == '1'){ // kg
      if($unit == 'lb'){// kg = lb x 0.45359237
        $equivalweight = ($value * 0.45359237);
      }else if($unit == 'kg'){
        $equivalweight = $value;
      }
    }else if($user_weight == '2'){ // lb
      if($unit == 'kg'){// lb = kg ÷ 0.45359237
        $equivalweight = ($value / 0.45359237);
      }else if($unit == 'lb'){
        $equivalweight = $value;
      }
    }
    return $equivalweight;
  }
  public static function convertToMyDistanceUnit($user_distance='1', $unit, $value=0){
    $equivaldist = 0;
    if($user_distance == '1'){ // km
      if($unit == 'km'){
        $equivaldist = $value;
      }else if($unit == 'mile'){//km = mi x 1.609344
        $equivaldist = ($value * 1.609344);
      }else if($unit == 'meter'){//km = m ÷ 1000
        $equivaldist = ($value / 1000);
      }else if($unit == 'yard'){//km = yd x 0.0009144
        $equivaldist = ($value * 0.0009144);
      }else if($unit == 'cm'){//km = cm ÷ 100000
        $equivaldist = ($value / 100000);
      }else if($unit == 'feet'){//km = ft x 0.0003048
        $equivaldist = ($value * 0.0003048);
      }
    }else if($user_distance == '2'){ // mile
      if($unit == 'km'){//mi = km ÷ 1.609344
        $equivaldist = ($value / 1.609344);
      }else if($unit == 'mile'){
        $equivaldist = $value;
      }else if($unit == 'meter'){//mi = m ÷ 1609.344
        $equivaldist = ($value / 1609.344);
      }else if($unit == 'yard'){//mi = yd ÷ 1760
        $equivaldist = ($value / 1760);
      }else if($unit == 'cm'){//mi = cm ÷ 160934.4
        $equivaldist = ($value / 160934.4);
      }else if($unit == 'feet'){//mi = ft ÷ 5280
        $equivaldist = ($value / 5280);
      }
    }else if($user_distance == '3'){ // meter
      if($unit == 'km'){//m = km x 1000
        $equivaldist = ($value * 1000);
      }else if($unit == 'mile'){//m = mi x 1609.344
        $equivaldist = ($value * 1609.344);
      }else if($unit == 'meter'){
        $equivaldist = $value;
      }else if($unit == 'yard'){//m = yd x 0.9144
        $equivaldist = ($value * 0.9144);
      }else if($unit == 'cm'){//m = cm ÷ 100
        $equivaldist = ($value / 100);
      }else if($unit == 'feet'){//m = ft x 0.3048
        $equivaldist = ($value * 0.3048);
      }
    }else if($user_distance == '4'){ // yard
      if($unit == 'km'){//yd = km ÷ 0.0009144
        $equivaldist = ($value / 0.0009144);
      }else if($unit == 'mile'){//yd = mi x 1760
        $equivaldist = ($value * 1760);
      }else if($unit == 'meter'){//yd = m ÷ 0.9144
        $equivaldist = ($value / 0.9144);
      }else if($unit == 'yard'){
        $equivaldist = $value;
      }else if($unit == 'cm'){//yd = cm ÷ 91.44
        $equivaldist = ($value / 91.44);
      }else if($unit == 'feet'){//yd = ft ÷ 3
        $equivaldist = ($value / 3);
      }
    }else if($user_distance == '5'){ // cm
      if($unit == 'km'){//cm = km x 100000
        $equivaldist =($value * 100000);
      }else if($unit == 'mile'){//cm = mi x 160934.4
        $equivaldist = ($value * 160934.4);
      }else if($unit == 'meter'){//cm = m x 100
        $equivaldist = ($value * 100);
      }else if($unit == 'yard'){//cm = yd x 91.44
        $equivaldist = ($value * 91.44);
      }else if($unit == 'cm'){
        $equivaldist = $value;
      }else if($unit == 'feet'){//cm = ft x 30.48
        $equivaldist = ($value * 30.48);
      }
    }else if($user_distance == '6'){ // feet
      if($unit == 'km'){//ft = km ÷ 0.0003048
        $equivaldist = ($value / 0.0003048);
      }else if($unit == 'mile'){//ft = mi x 5280
        $equivaldist = ($value * 5280);
      }else if($unit == 'meter'){//ft = m ÷ 0.3048
        $equivaldist = ($value / 0.3048);
      }else if($unit == 'yard'){//ft = yd x 3
        $equivaldist = ($value * 3);
      }else if($unit == 'cm'){//ft = cm ÷ 30.48
        $equivaldist = ($value / 30.48);
      }else if($unit == 'feet'){
        $equivaldist = $value;
      }
    }
    return $equivaldist;
  }
  public static function sumTime($times) {
    $seconds = 0;
    foreach ($times as $time){
      list($hour, $minute, $second) = explode(':', $time);
      $seconds += $hour * 3600;
      $seconds += $minute * 60;
      $seconds += $second;
    }
    $hours = floor($seconds / 3600);
    $seconds -= $hours * 3600;
    $minutes  = floor($seconds / 60);
    $seconds -= $minutes * 60;
    return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
  }
  public static function hoursToMinutes($hours) {
    $minutes = 0; 
    if (strpos($hours, ':') !== false) {
      list($hours, $minutes) = explode(':', $hours);
    }
    return $hours * 60 + $minutes;
  }
  public static function hoursToSeconds($time){
    $time = explode(':', $time);
    return ($time[0]*3600) + ($time[1]*60) + $time[2];
  }
  public static function secondsToHours($seconds){
    $hours = floor($seconds / 3600);
    $mins = floor($seconds / 60 % 60);
    $secs = floor($seconds % 60);
    return self::pad($hours) . ':' . self::pad($mins) . ':' . self::pad($secs);
  }
  public static function pad($val){
    return ($val < 10 ? '0' . $val : $val);
  }
  public static function calculateBMI($height, $weight){
    if(!$height || !$weight){
      return;
    }
    $user_bmi = '';
    $height_in_m = ($height / 100);
    $bmiRate = round(($weight / $height_in_m) / $height_in_m);
    if($bmiRate < 18.5){
      $user_bmi = $bmiRate.' = Underweight (0-18.4)';
    }else if($bmiRate >= 18.5 && $bmiRate <= 24.9){
      $user_bmi = $bmiRate.' = Normal (18.5-24.9)';
    }else if($bmiRate >= 25 && $bmiRate <= 29.9){
      $user_bmi = $bmiRate.' = Overweight (25-29.9)';
    }else if($bmiRate >= 30 && $bmiRate <= 34.9){
      $user_bmi = $bmiRate.' = Obese(30-34.9)';
    }else if($bmiRate >= 35 && $bmiRate <= 39.9){
      $user_bmi = $bmiRate.' = Severly Obese (35-39.9)';
    }else if($bmiRate >= 40){
      $user_bmi = $bmiRate.' = Morbix Obese (40+)';
    }
    return $user_bmi;
  }
  public static function get_user_week_start_date($userweekstart){
    $week = array('1'=>'Monday','2'=>'Tuesday','3'=>'Wednesday','4'=>'Thursday','5'=>'Friday','6'=>'Saturday','7'=>'Sunday');
    $i = 0;
    while (date("l", strtotime( "-$i days" )) != $week[$userweekstart]) {
      $i++;
    }
    $date = date("Y-m-d 00:00:00", strtotime( "-$i days" ));
    return $date;
  }
  public static function exerciseSetsCombine($exerciseRecord , $param = 'wkout_id', $orderFlag =true){
	$reMappedExerciseSet = array();
	$goal_value = $goal_title = '';
	if(is_array($exerciseRecord) && count($exerciseRecord)>0){
		$inc = 1;
		foreach($exerciseRecord as $keys => $values){
			if($orderFlag){
				if($values['goal_unit_id'] == $goal_value && !empty($goal_value)){
					$goal_value 	= $values['goal_unit_id'];
					if(isset($reMappedExerciseSet['uniqueset'][$goal_order.'_'.$goal_value])){
						$maxcount = count($reMappedExerciseSet['setdetails'][$goal_order.'_'.$goal_value]) -1;
						$getLastOrder = $reMappedExerciseSet['setdetails'][$goal_order.'_'.$goal_value][$maxcount]['goal_order'] +1;
						if($values['goal_order'] == $getLastOrder){
							$reMappedExerciseSet['setdetails'][$goal_order.'_'.$goal_value][] = $values;
						}else{
							$goal_value 	= $values['goal_unit_id'];
							$goal_order   	= $values['goal_order'];
							if(!isset($reMappedExerciseSet['uniqueset'][$goal_order.'_'.$goal_value]))
								$reMappedExerciseSet['uniqueset'][$goal_order.'_'.$goal_value] = $values;
							$reMappedExerciseSet['setdetails'][$goal_order.'_'.$goal_value][] = $values;
							$inc++;
						}
					}				
				}else if($values['goal_title'] == $goal_title && empty($goal_value)){
					$goal_value 	= $values['goal_unit_id'];
					$wkout_value 	= $values[$param];
					if(isset($reMappedExerciseSet['uniqueset'][$goal_order.'_0_'.$wkout_value])){
						$maxcount = count($reMappedExerciseSet['setdetails'][$goal_order.'_0_'.$wkout_value]) -1;
						$getLastOrder = $reMappedExerciseSet['setdetails'][$goal_order.'_0_'.$wkout_value][$maxcount]['goal_order'] +1;
						if($values['goal_order'] == $getLastOrder){
							$reMappedExerciseSet['setdetails'][$goal_order.'_0_'.$wkout_value][] = $values;
						}else{
							$goal_value 	= $values['goal_unit_id'];
							$goal_title 	= $values['goal_title'];
							$goal_order   	= $values['goal_order'];
							if(!isset($reMappedExerciseSet['uniqueset'][$goal_order.'_0_'.$wkout_value]))
								$reMappedExerciseSet['uniqueset'][$goal_order.'_0_'.$wkout_value] = $values;
							$reMappedExerciseSet['setdetails'][$goal_order.'_0_'.$wkout_value][] = $values;
							$inc++;
						}
					}				
				}else{
					$goal_value 	= $values['goal_unit_id'];
					$goal_title 	= $values['goal_title'];
					$goal_order   	= $values['goal_order'];
					$wkout_value 	= $values[$param];
					if(!empty($goal_value)){
						if(!isset($reMappedExerciseSet['uniqueset'][$goal_order.'_'.$goal_value]))
							$reMappedExerciseSet['uniqueset'][$goal_order.'_'.$goal_value] = $values;
						$reMappedExerciseSet['setdetails'][$goal_order.'_'.$goal_value][] = $values;
					}else{
						if(!isset($reMappedExerciseSet['uniqueset'][$goal_order.'_0_'.$wkout_value]))
							$reMappedExerciseSet['uniqueset'][$goal_order.'_0_'.$wkout_value] = $values;
						$reMappedExerciseSet['setdetails'][$goal_order.'_0_'.$wkout_value][] = $values;
					}
					$inc++;
				}
			}else{
				
				if(!isset($reMappedExerciseSet['uniqueset'][$values[$param]]))
					$reMappedExerciseSet['uniqueset'][$values[$param]] = $values;
				$reMappedExerciseSet['setdetails'][$values[$param]][] = $values;
			}
		}
	}
	return $reMappedExerciseSet;
  }
  public static function exerciseSetsToArray($exerciseRecord){
	$reMappedExerciseSet['uniqueset'] = array(); // unique XR details
	$reMappedExerciseSet['setdetails'] = array(); // duplicated set details from unique XR details
	if(is_array($exerciseRecord) && count($exerciseRecord)>0){
		foreach($exerciseRecord as $keys => $values){
			if(!empty($values['goal_unit_id'])){
				if(!isset($reMappedExerciseSet['uniqueset'][$values['goal_unit_id']]))
					$reMappedExerciseSet['uniqueset'][$values['goal_unit_id']] = $values;
				$reMappedExerciseSet['setdetails'][$values['goal_unit_id']][] = $values;
			}else{
				if(!isset($reMappedExerciseSet['uniqueset'][$values['goal_title']]))
					$reMappedExerciseSet['uniqueset'][$values['goal_title']] = $values;
				$reMappedExerciseSet['setdetails'][$values['goal_title']][] = $values;
			}
		}
	}
	return $reMappedExerciseSet;
  }
  public static function checkKeyexistsInArray($haystack, $needle){
	if(!is_array($needle)) $needle = array($needle);
	foreach($needle as $what) {
		if(strpos($haystack, $what)!==false) return true;
	}
	return false;
  }
}
?>