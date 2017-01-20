<?php
defined('SYSPATH') or die('No direct access allowed.');
class Model_User extends Model_Auth_User
{
   protected $_table_name = 'users';
   protected $_primary_key = 'id';
   public function get_users_list()
   {
      $query = DB::select()->from('users')->execute();
      $list  = $query->as_array();
      return $list;
   }
   public function validate_user_login($arr)
   {
      return Validation::factory($arr)->rule('user_email', 'not_empty')->rule('password', 'not_empty')->rule('user_email', 'Model_User::userSiteCheck', array(
         $arr['user_email'],
         ':validation'
      ));
   }
   public function validate_user_password($arr)
   {
      return Validation::factory($arr)->rule('new_pass', 'not_empty')->rule('conf_pass', 'not_empty')->rule('new_pass', 'Model_User::resetPasswordCheck', array(
         $arr['new_pass'],
         $arr['conf_pass'],
         ':validation'
      ));
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
   public static function userSiteCheck($user_email, Validation $validation)
   {
      $cur_site_id = Session::instance()->get('current_site_id');
      if (stripos($user_email, '@') && !empty($cur_site_id)) {
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
            $userSites = Helper_Common::getAllSiteIdByUser($userid);
            if (!in_array($cur_site_id, $userSites)) {
               $validation->error('user_email', 'userEmailNotRegisteredWithSite');
               Session::instance()->set('changeto_site_id', $userSites[0]);
            }
         }
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
   public function get_users_name_list()
   {
      $query = DB::select('id', 'username')->from('users')->execute();
      $list  = $query->as_array();
      return $list;
   }
	
   public function get_username_byid($id)
   {
      $query   = DB::select('username')->from('users')->where('id', '=', $id)->execute();
      $results = $query->as_array();
      if ($results) {
         return $results[0]['username'];
      }
      return FALSE;
   }
   public function get_username_byname($name, $ignore_id, $limit = 5)
   {
      $sql   = 'SELECT id, CONCAT(user_fname, " ", user_lname) as username from users where id !="' . $ignore_id . '" AND security_code !="" AND user_access NOT IN (6,8) AND (user_fname like "' . $name . '%" OR user_lname like "' . $name . '%") limit 0,' . $limit;
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list;
   }
   public function get_username_bynamesiteId($name, $ignore_id, $limit = 5, $siteIds)
   {
      $siteIds = (empty($siteIds) ? (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0') : $siteIds);
      $sql     = 'SELECT u.id, CONCAT(u.user_fname, " ", u.user_lname) as username from users as u join user_sites as us on u.id=us.user_id where us.status=1 AND us.site_id in (' . $siteIds . ') AND u.id !="' . $ignore_id . '" AND u.user_access NOT IN (6,8) AND (u.user_fname COLLATE UTF8_GENERAL_CI like "' . $name . '%" OR u.user_lname COLLATE UTF8_GENERAL_CI like "' . $name . '%") limit 0,' . $limit;
      $query   = DB::query(Database::SELECT, $sql);
      $list    = $query->execute()->as_array();
      return $list;
   }
   public function get_useremail_byid($id)
   {
      $query   = DB::select('email')->from('users')->where('id', '=', $id)->execute();
      $results = $query->as_array();
      if ($results) {
         return $results[0]['email'];
      }
      return FALSE;
   }
   public function updateUserSites($updateStr, $condtnStr)
   {
      $sql   = "update user_sites set " . $updateStr . " WHERE " . $condtnStr;
      $query = DB::query(Database::UPDATE, $sql);
      return $query->execute();
   }
   public function updateSiteIdbyUser($site_id, $user_id)
   {
      $sql   = "update users set site_id='" . $site_id . "' WHERE id = '" . $user_id . "'";
      $query = DB::query(Database::UPDATE, $sql);
      return $query->execute();
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
   public function get_user_details($email,$sid)
   {
      $sql   = 'select * from users as u join user_sites as us on us.user_id=u.id where u.user_email="'.$email.'"';
		if($sid!=''){
			$sql   .= 'and us.site_id = '.$sid;
		}
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return ($list) ? $list[0] : false;
   }
}