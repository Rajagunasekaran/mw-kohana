<?php
defined('SYSPATH') or die('No direct access allowed.');
class Model_Admin_Sites extends ORM
{
   protected $_table_name = 'sites';
   protected $_primary_key = 'id';
   public function get_allSites_byAdmin()
   {
      $sql   = "SELECT * FROM sites WHERE is_deleted = 0 order by id desc";
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list;
   }
   public function get_existsSites_byAdmin()
   {
      $sql   = "SELECT * FROM sites WHERE is_deleted = 0 AND from_site_id!= 0 order by id desc";
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list;
   }
   public function get_existsSiteFilter_byAdmin($postarr)
   {
      $sql   = "SELECT distinct * FROM sites WHERE is_deleted = 0 AND name like '%".$postarr['seachtext']."%'";
      $order_by = $postarr['sortby'];
      if ($order_by == 1) {
         $sql .= ' order by name asc';
      } elseif ($order_by == 2) {
         $sql .= ' order by name desc';
      } elseif ($order_by == 3) {
         $sql .= ' order by created_at desc';
      } elseif ($order_by == 4) {
         $sql .= ' order by modified_at desc';
      } else {
         $sql .= ' order by name asc';
      }
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list;
   }
   public function getAllSites()
   {
      $sql   = "SELECT * FROM sites WHERE is_deleted = 0 AND is_active = 1";
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list;
   }
   public function update_sites($updateStr, $condtnStr)
   {
      $sql   = "update sites set " . $updateStr . " WHERE " . $condtnStr;
      $query = DB::query(Database::UPDATE, $sql);
      return $query->execute();
   }
   public function getAllAssignedSites($userid)
   {
      $sql   = "SELECT * FROM assign_sites WHERE user_id = '" . $userid . "'";
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list;
   }
   public function getAllUserSites($userid)
   {
      $sql   = "SELECT us.* FROM user_sites as us join sites as s on (s.id= us.site_id) WHERE us.user_id = '" . $userid . "' AND s.is_active = 1 AND is_deleted = 0";
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list;
   }
	public function getAllActiveUserSites($userid)
   {
      $sql   = "SELECT s.id, s.name FROM user_sites as us join sites as s on (s.id= us.site_id) WHERE us.user_id = '" . $userid . "' AND s.is_active = 1 AND is_deleted = 0 and us.status=1";
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
		$user_sites = array();
		if(!empty($list)){
			foreach ($list as $keys=>$values ){
				$user_sites[$values['id']] = ucfirst($values['name']);
			}	
		}
      return $user_sites ;
   }
   public function getAllUserSitesBySampleWkout($userid)
   {
      $sql   = "SELECT us.* FROM user_sites as us join sites as s on (s.id= us.site_id) WHERE us.user_id = '" . $userid . "' AND s.sample_workouts=1 AND s.is_active = 1 AND s.is_deleted = 0";
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list;
   }
   public function getAllUserSitesByDefaultXrcise($userid)
   {
      $sql   = "SELECT us.* FROM user_sites as us join sites as s on (s.id= us.site_id) WHERE us.user_id = '" . $userid . "' AND s.exercise_records=1 AND s.is_active = 1 AND s.is_deleted = 0";
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list;
   }
   public function getAllUserSitesBySampleImg($userid)
   {
      $sql   = "SELECT us.* FROM user_sites as us join sites as s on (s.id= us.site_id) WHERE us.user_id = '" . $userid . "' AND s.sample_images=1 AND s.is_active = 1 AND s.is_deleted = 0";
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list;
   }
   public function getAllUserSitesByuserId($userid)
   {
      $sql   = "SELECT id FROM user_sites WHERE user_id = '" . $userid . "'";
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list;
   }
   public function getAllAssignedSitesByJoin($userid)
   {
      $sql   = "SELECT * FROM assign_sites AS ast JOIN sites As s ON s.id = ast.site_id WHERE ast.user_id = '" . $userid . "'";
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list;
   }
   public function get_site_managers_count($siteid)
   {
      $sql   = "SELECT * FROM assign_sites WHERE site_id = '" . $siteid . "'";
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list;
   }
   public function insertUserSites($array)
   {
      $results = DB::insert('user_sites', array(
         'user_id',
         'site_id',
         'status',
         'last_login',
         'modified_date'
      ))->values(array(
         $array['user_id'],
         $array['site_id'],
         $array['status'],
         $array['last_login'],
         $array['modified_date']
      ))->execute();
      return $results[0];
   }
   public function insertFooterMenu($array)
   {
      $results = DB::insert('sitefootermenu', array(
         'title',
         'url',
         'site_id'
      ))->values(array(
         $array['title'],
         $array['url'],
         $array['site_id']
      ))->execute();
      return $results[0];
   }
   public function deleteFooterMenu($siteId)
   {
      $results = DB::delete('sitefootermenu')->where('site_id', '=', $siteId)->execute();
   }
   public function get_site_footer_menu($siteid)
   {
      $sql   = "SELECT * FROM sitefootermenu WHERE site_id = '" . $siteid . "'";
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list;
   }
	
	
	public function get_site_footer_menu_contact($siteid,$contact_title){
      $sql   = "SELECT * FROM sitefootermenu WHERE site_id = '" . $siteid . "' and title='$contact_title'";
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list;
   }
	public function delete_site_footer_menu_contact($siteid,$contact_title){
      $sql   = "delete from sitefootermenu WHERE site_id = '" . $siteid . "' and title='$contact_title'";
      $query = DB::query(Database::DELETE, $sql);
      $list  = $query->execute();
      return $list;
   }
	
   /*******************duplicate site start here*************************/
   public function insertEmailTemplatedup($array, $siteid)
   {
      $results = DB::insert('email_template', array(
         'template_name',
         'subject',
         'body',
         'smtp_id',
         'site_id',
         'status'
      ))->values(array(
         $array['template_name'],
         $array['subject'],
         $array['body'],
         $array['smtp_id'],
         $siteid,
         $array['status']
      ))->execute();
      return $results[0];
   }
   public function insertEmailTemplateTypedup($array, $siteid)
   {
      $results = DB::insert('email_template_type', array(
         'type_name',
         'template_id',
         'site_id'
      ))->values(array(
         $array['type_name'],
         $array['template_id'],
         $siteid
      ))->execute();
      return $results[0];
   }
   public function getpagelist($siteid)
   {
      $sql      = "SELECT * FROM page WHERE site_id = '" . $siteid . "'";
      $query    = DB::query(Database::SELECT, $sql);
      $pagelist = $query->execute()->as_array();
      return $pagelist;
   }
   public function insertduplicatePage($array, $siteid)
   {
      $results = DB::insert('page', array(
         'page_title',
         'page_slug',
         'page_content',
         'site_id',
			'onlyadmin',
			'common_status',
         'status'
      ))->values(array(
         $array['page_title'],
         $array['page_slug'],
         $array['page_content'],
         $siteid,
			$array['onlyadmin'],
			$array['common_status'],
         $array['status']
      ))->execute();
      return $results[0];
   }
   public function getsitesliderslist($siteid)
   {
      $sql             = "SELECT * FROM sitesliders WHERE site_id = '" . $siteid . "'";
      $query           = DB::query(Database::SELECT, $sql);
      $sitesliderslist = $query->execute()->as_array();
      return $sitesliderslist;
   }
   public function insertduplicateslider($array, $siteid)
   {
	  $datetime = Helper_Common::get_default_datetime();
      $results = DB::insert('sitesliders', array(
         's_title',
         's_content',
         's_url',
         's_image',
         'tile_color',
         'text_shadow',
         'content_color',
         'content_bgcolor',
         'content_border',
         'is_active',
         'is_delete',
         'date_modified',
         'date_created',
         'site_id'
      ))->values(array(
         $array['s_title'],
         $array['s_content'],
         $array['s_url'],
         $array['s_image'],
         $array['tile_color'],
         $array['text_shadow'],
         $array['content_color'],
         $array['content_bgcolor'],
         $array['content_border'],
         $array['is_active'],
         $array['is_delete'],
         $datetime,
         $datetime,
         $siteid
      ))->execute();
      return $results[0];
   }
   public function getsitesiteblockslist($siteid)
   {
      $sql            = "SELECT * FROM siteblocks WHERE site_id = '" . $siteid . "'";
      $query          = DB::query(Database::SELECT, $sql);
      $siteblockslist = $query->execute()->as_array();
      return $siteblockslist;
   }
   public function insertduplicateblocks($array, $siteid)
   {
	  $datetime = Helper_Common::get_default_datetime();
      $results = DB::insert('siteblocks', array(
         'b_title',
         'b_description',
         'b_url',
         'b_image',
         'is_active',
         'is_delete',
         'date_created',
         'date_modified',
         'site_id'
      ))->values(array(
         $array['b_title'],
         $array['b_description'],
         $array['b_url'],
         $array['b_image'],
         $array['is_active'],
         $array['is_delete'],
         $datetime,
         $datetime,
         $siteid
      ))->execute();
      return $results[0];
   }
   public function getsitesitepartnerslist($siteid)
   {
      $sql          = "SELECT * FROM sitepartners WHERE site_id = '" . $siteid . "'";
      $query        = DB::query(Database::SELECT, $sql);
      $partnerslist = $query->execute()->as_array();
      return $partnerslist;
   }
   public function insertduplicatepartners($array, $siteid)
   {
	  $datetime = Helper_Common::get_default_datetime();
      $results = DB::insert('sitepartners', array(
         'p_title',
         'p_description',
         'p_url',
         'p_image',
         'is_active',
         'is_delete',
         'date_created',
         'date_modified',
         'site_id'
      ))->values(array(
         $array['p_title'],
         $array['p_description'],
         $array['p_url'],
         $array['p_image'],
         $array['is_active'],
         $array['is_delete'],
         $datetime,
         $datetime,
         $siteid
      ))->execute();
      return $results[0];
   }
   public function getsitesitetestimonialslist($siteid)
   {
      $sql              = "SELECT * FROM sitetestimonials WHERE site_id = '" . $siteid . "'";
      $query            = DB::query(Database::SELECT, $sql);
      $testimonialslist = $query->execute()->as_array();
      return $testimonialslist;
   }
   public function insertduplicatetestimonials($array, $siteid)
   {
	  $datetime = Helper_Common::get_default_datetime();
      $results = DB::insert('sitetestimonials', array(
         't_title',
         't_description',
         't_image',
         't_user',
         'is_active',
         'is_delete',
         'site_id',
         'date_created',
         'date_modified'
      ))->values(array(
         $array['t_title'],
         $array['t_description'],
         $array['t_image'],
         $array['t_user'],
         $array['is_active'],
         $array['is_delete'],
         $siteid,
         $datetime,
         $datetime
      ))->execute();
      return $results[0];
   }
   public function getsitehomepageslist($siteid)
   {
      $sql           = "SELECT * FROM sitehomepages WHERE site_id = '" . $siteid . "'";
      $query         = DB::query(Database::SELECT, $sql);
      $homepageslist = $query->execute()->as_array();
      return $homepageslist;
   }
   public function insertduplicatehomepages($array, $siteid)
   {
	  $datetime = Helper_Common::get_default_datetime();
      $results = DB::insert('sitehomepages', array(
         'title',
         'description',
         'video',
         'social_facebook_url',
         'social_twitter_url',
         'social_linkedin_url',
         'footer_content',
         'site_logo',
         'advanced_css',
         'site_id',
         'is_delete',
         'date_created',
         'date_modified'
      ))->values(array(
         $array['title'],
         $array['description'],
         $array['video'],
         $array['social_facebook_url'],
         $array['social_twitter_url'],
         $array['social_linkedin_url'],
         $array['footer_content'],
         $array['site_logo'],
         $array['advanced_css'],
         $siteid,
         $array['is_delete'],
         $datetime,
         $datetime
      ))->execute();
      return $results[0];
   }
   public function getsitefootermenulist($siteid)
   {
      $sql           = "SELECT * FROM sitefootermenu WHERE site_id = '" . $siteid . "'";
      $query         = DB::query(Database::SELECT, $sql);
      $homepageslist = $query->execute()->as_array();
      return $homepageslist;
   }
   public function getoldsiteslug($siteid)
   {
      $sql   = "SELECT slug FROM sites WHERE id = '" . $siteid . "'";
      $query = DB::query(Database::SELECT, $sql);
      $slug  = $query->execute()->as_array();
      return $slug;
   }
   public function insertduplicatefootermenu($array_linkch, $siteid, $duplicate_id)
   {
      $oldsiteslugArray     = $this->getoldsiteslug($duplicate_id);
      $currentsiteslugArray = $this->getoldsiteslug($siteid);
	  $newurl               = str_replace((string)$oldsiteslugArray[0]['slug'], (string)$currentsiteslugArray[0]['slug'], (string)$array_linkch['url']);
	  $results              = DB::insert('sitefootermenu', array(
         'title',
         'url',
         'site_id'
      ))->values(array(
         $array_linkch['title'],
         $newurl,
         $siteid
      ))->execute();
      return $results[0];
   }
   public function getsitePreferencelist($siteid)
   {
      $sql            = "SELECT * FROM site_settings WHERE site_id = '" . $siteid . "'";
      $query          = DB::query(Database::SELECT, $sql);
      $Preferencelist = $query->execute()->as_array();
      return $Preferencelist;
   }
   public function insertduplicatePreference($array, $siteid)
   {
      $results = DB::insert('site_settings', array(
         'site_id',
         'country',
         'timezone',
         'time_format',
         'date_format',
         'language',
         'week_sarts_on',
         'Weight',
         'Distance',
         'Network_updates',
         'Assignment_upcoming_reminder',
         'Assignment_you_missed',
         'Shared_Workout_Plan_received',
         'Sharing',
         'Invitation_to_connect',
         'new_features_tips_special_offers',
         'Receive_email_alerts_for_Exercises_and_Workouts',
         'time_to_send_email',
         'device_integrations'
      ))->values(array(
         $siteid,
         (isset($array['country']) && !empty($array['country']) ? $array['country'] : '7'),
         (isset($array['timezone']) && !empty($array['timezone']) ? $array['timezone'] : 'Australia/Sydney (+10|00)'),
         (isset($array['time_format']) && !empty($array['time_format']) ? $array['time_format'] : 'h:i:s A'),
         (isset($array['date_format']) && !empty($array['date_format']) ? $array['date_format'] : 'Y M d'),
         (isset($array['language']) && !empty($array['language']) ? $array['language'] : '1'),
         (isset($array['week_sarts_on']) && !empty($array['week_sarts_on']) ? $array['week_sarts_on'] : '7'),
         (isset($array['Weight']) && !empty($array['Weight']) ? $array['Weight'] : '1'),
         (isset($array['Distance']) && !empty($array['Distance']) ? $array['Distance'] : '1'),
         (isset($array['Network_updates']) && !empty($array['Network_updates']) ? $array['Network_updates'] : '2'),
         (isset($array['Assignment_upcoming_reminder']) && !empty($array['Assignment_upcoming_reminder']) ? $array['Assignment_upcoming_reminder'] : '2'),
         (isset($array['Assignment_you_missed']) && !empty($array['Assignment_you_missed']) ? $array['Assignment_you_missed'] : '3'),
         (isset($array['Shared_Workout_Plan_received']) && !empty($array['Shared_Workout_Plan_received']) ? $array['Shared_Workout_Plan_received'] : '2'),
         (isset($array['Sharing']) && !empty($array['Sharing']) ? $array['Sharing'] : ''),
         (isset($array['Invitation_to_connect']) && !empty($array['Invitation_to_connect']) ? $array['Invitation_to_connect'] : '2'),
         (isset($array['new_features_tips_special_offers']) && !empty($array['new_features_tips_special_offers']) ? $array['new_features_tips_special_offers'] : '2'),
         (isset($array['Receive_email_alerts_for_Exercises_and_Workouts']) && !empty($array['Receive_email_alerts_for_Exercises_and_Workouts']) ? $array['Receive_email_alerts_for_Exercises_and_Workouts'] : '1'),
         (isset($array['time_to_send_email']) && !empty($array['time_to_send_email']) ? $array['time_to_send_email'] : '12:00:00 AM'),
         (isset($array['device_integrations']) && !empty($array['device_integrations']) ? $array['device_integrations'] : '1')
      ))->execute();
      return $results[0];
   }
   public function getsiteRoleAccesslist($siteid)
   {
      $sql            = "SELECT * FROM roles_access WHERE site_id = '" . $siteid . "'";
      $query          = DB::query(Database::SELECT, $sql);
      $Preferencelist = $query->execute()->as_array();
      return $Preferencelist;
   }
   public function insertduplicateRoleAccess($array, $siteid)
   {
      $results = DB::insert('roles_access', array(
         'role_id',
         'site_id',
         'access_type_id'
      ))->values(array(
         $array['role_id'],
         $siteid,
         $array['access_type_id']
      ))->execute();
      return $results[0];
   }
   public function get_sites_byname($name, $ignore_id, $limit = 5)
   {
      $sql   = 'SELECT id, name as sitename FROM sites WHERE is_deleted = 0 AND is_active = 1 AND id !="' . $ignore_id . '" AND name like "' . $name . '%" limit 0,' . $limit;
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list;
   }
   /*******************duplicate site end here*************************/
	public function getAlldevice()
   {
		$sql   = "SELECT * FROM device_integrations where status=0 ";
		$query = DB::query(Database::SELECT, $sql);
      return $query->execute()->as_array();
   }
   public function getAlldevice_Integrations($sites_id)
   {
		//$siteid =  (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '1');
      $sql   = "SELECT di.*, sd.status as status_device FROM device_integrations as di left join  sitedevice as sd on di.id=sd.device_id and sd.site_id=$sites_id and di.status=0 ";
		
		$query = DB::query(Database::SELECT, $sql);
      return $query->execute()->as_array();
   }
	public function checkdevice($did,$siteid){
		$sql   = "SELECT * FROM sitedevice where device_id=$did and site_id=$siteid";
		$query = DB::query(Database::SELECT, $sql);
      return $query->execute()->as_array();
	}
   //General data fetch from table using site_id - mdh
   public function getgeneraltable($condupsites_id,$modulename){
      $sql   = "SELECT * FROM $modulename where $condupsites_id";
	  //echo "============".$sql;
      $query = DB::query(Database::SELECT, $sql);
      return $query->execute()->as_array();
   }
   //General insert into table using site_id - mdh
   public function insertduplicategeneral($datainsertval,$modulename){
      $results = DB::insert($modulename,array_keys($datainsertval))->values($datainsertval)->execute();
      return $results[0];
   }
   //General update  table using site_id - mdh
   public function updateduplicategeneral($dataupdateval,$modulename,$site_id){
      foreach($dataupdateval as $keydv => $valuedv){
         $query = DB::update($modulename)
         ->set(array('template_id'=>$valuedv))
         ->where('site_id', '=', $site_id)
         ->where('template_id', '=', $keydv)->execute();
      }
   }
   //Get admin user id in string for query - mdh
   public function get_adminuser_list(){
		$tempmodel = ORM::factory('admin_smtp');
		$admin_users = '';
		$admin_user_arr = $tempmodel->get_admin_user();
		if(!empty($admin_user_arr)){
			foreach ($admin_user_arr as $keys=>$values ){
				$admin_users .= $values['user_id'].',';
			}	
		}	
		$user_list = substr($admin_users,0,-1); 
		return $user_list;	
	}
	public function updateUserLastLogin($newSite, $userId)
	{
		$datetime = Helper_Common::get_default_datetime();
		$sql   = 'update user_sites set last_login="' .$datetime. '" WHERE user_id ="'.$userId.'" and site_id ="'.$newSite.'"';
		$query = DB::query(Database::UPDATE, $sql);
		return $query->execute();
	}
}