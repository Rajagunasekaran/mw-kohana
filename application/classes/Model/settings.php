<?php
defined('SYSPATH') or die('No direct access allowed.');

class Model_settings extends Model{
	public function getAllTimezone(){
		$sql = "SELECT * FROM `timezone`";
		$query = DB::query(Database::SELECT,$sql);
		return $query->execute()->as_array();
	}
	public function getAlldevice_Integrations(){
		$siteid = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
		$sql = "SELECT sd.id, di.name, sd.device_id, sd.status, sd.site_id FROM `device_integrations` di, `sitedevice` sd WHERE di.status!=2 AND sd.status=0 AND di.id=sd.device_id AND sd.site_id=".$siteid;
		$query = DB::query(Database::SELECT,$sql);
		return $query->execute()->as_array();
	}
	public function getsettings(){
		$siteid = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
		$userid = '';
		if(Auth::instance()->logged_in())
			$userid = Auth::instance()->get_user()->pk();
		$sql = "SELECT us.`id`, us.`site_id`, us.`user_id`, us.XRset_extra_variable_flag,
			IF(us.`country`!='', us.`country`, ss.`country`) as `country`,
			IF(us.`timezone`!='', us.`timezone`, ss.`timezone`) as `timezone`,
			IF(us.`time_format`!='', us.`time_format`, ss.`time_format`) as `time_format`,
			IF(us.`date_format`!='', us.`date_format`, ss.`date_format`) as `date_format`,
			IF(us.`language`!='', us.`language`, ss.`language`) as `language`,
			IF(us.`week_sarts_on`!='', us.`week_sarts_on`, ss.`week_sarts_on`) as `week_sarts_on`,
			IF(us.`Weight`!='', us.`Weight`, ss.`Weight`) as `Weight`,
			IF(us.`Distance`!='', us.`Distance`, ss.`Distance`) as `Distance`,
			IF(us.`Network_updates`!='', us.`Network_updates`, ss.`Network_updates`) as `Network_updates`,
			IF(us.`Assignment_upcoming_reminder`!='', us.`Assignment_upcoming_reminder`, ss.`Assignment_upcoming_reminder`) as `Assignment_upcoming_reminder`,
			IF(us.`Assignment_you_missed`!='', us.`Assignment_you_missed`, ss.`Assignment_you_missed`) as `Assignment_you_missed`,
			IF(us.`Shared_Workout_Plan_received`!='', us.`Shared_Workout_Plan_received`, ss.`Shared_Workout_Plan_received`) as `Shared_Workout_Plan_received`,
			IF(us.`Sharing`!='', us.`Sharing`, ss.`Sharing`) as `Sharing`,
			IF(us.`Invitation_to_connect`!='', us.`Invitation_to_connect`, ss.`Invitation_to_connect`) as `Invitation_to_connect`,
			IF(us.`new_features_tips_special_offers`!='', us.`new_features_tips_special_offers`, ss.`new_features_tips_special_offers`) as `new_features_tips_special_offers`,
			IF(us.`Receive_email_alerts_for_Exercises_and_Workouts`!='', us.`Receive_email_alerts_for_Exercises_and_Workouts`, ss.`Receive_email_alerts_for_Exercises_and_Workouts`) as `Receive_email_alerts_for_Exercises_and_Workouts`,
			IF(us.`time_to_send_email`!='', us.`time_to_send_email`, ss.`time_to_send_email`) as `time_to_send_email`,
			IF(us.`device_integrations`!='', us.`device_integrations`, ss.`device_integrations`) as `device_integrations` ,
			sd.`dist_title`, sd.`dist_abbrv`, sr.`resist_title`, sr.`resist_abbrv`
			FROM `user_settings` us LEFT JOIN `site_settings` ss ON us.`site_id`=ss.`site_id` 
			LEFT JOIN `set_dist` sd ON IF(us.`Weight`!='', us.`Weight`, ss.`Weight`)=sd.`dist_id`
			LEFT JOIN `set_resist` sr ON IF(us.`Distance`!='', us.`Distance`, ss.`Distance`)=sr.`resist_id` WHERE us.`site_id`=".$siteid. (!empty($userid) ? " AND us.`user_id`=".$userid."" : '')." LIMIT 1";
		$query = DB::query(Database::SELECT,$sql);
		$settings_result = $query->execute()->as_array();
		if(count($settings_result)==0){
			$sql = "SELECT ss.`id`, ss.`site_id`, ss.`country`, ss.`timezone`, ss.`time_format`, ss.`date_format`, ss.`language`, ss.`week_sarts_on`, ss.`Weight`, ss.`Distance`, ss.`Network_updates`, ss.`Assignment_upcoming_reminder`, ss.`Assignment_you_missed`, ss.`Shared_Workout_Plan_received`, ss.`Sharing`, ss.`Invitation_to_connect`, ss.`new_features_tips_special_offers`, ss.`Receive_email_alerts_for_Exercises_and_Workouts`, ss.`time_to_send_email`, ss.`device_integrations`, sd.`dist_title`, sd.`dist_abbrv`, sr.`resist_title`, sr.`resist_abbrv`
			FROM  `site_settings` ss LEFT JOIN `set_dist` sd ON ss.`Weight`=sd.`dist_id`
			LEFT JOIN `set_resist` sr ON  ss.`Distance`=sr.`resist_id` WHERE ss.`site_id`=".$siteid." LIMIT 1";
			$query = DB::query(Database::SELECT,$sql);
			$settings_result = $query->execute()->as_array();
		}
		return $settings_result;
	}
	public function get_site_settings(){
		$siteid = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
		$sql = "SELECT * FROM `site_settings` WHERE site_id=".$siteid;
		$query = DB::query(Database::SELECT,$sql);
		return $query->execute()->as_array();
	}
	public function check_preference($val){
		$siteid = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
		$userid = Auth::instance()->get_user()->pk();
		$sql = "SELECT * FROM `user_settings` WHERE site_id=".$siteid." AND user_id=".$userid;
		$query = DB::query(Database::SELECT,$sql);
		$check_preference = $query->execute()->as_array();
		if(!empty($check_preference)){
			$condtnStr = 'site_id='.$siteid." AND user_id=".$userid;
			$sql = "update user_settings set ".$val['update']." = ".$val[$val['update']]." WHERE ".$condtnStr;
			$query = DB::query(Database::UPDATE,$sql)->execute();
			$succ = "Preference Settings for ".$val['update']." updated successfully!!!";
		}else{
			$results = DB::insert('user_settings', array('site_id', 'user_id', $val['update'])) ->values(array($siteid, $userid, $val['timezone']))->execute();
			if(!empty($results[0])){
				$succ = 'Preference Settings inserted successfully!!!';
			}
		}
	}
	// Get language list
	public function languagelist(){
		$sql = "SELECT * FROM `language`";
		$query = DB::query(Database::SELECT,$sql);
		return $query->execute()->as_array();
	}
	// Get language Data
	public function languagedata($siteid,$language_id){
		$sql = "SELECT * FROM `language_data` as ld LEFT JOIN `language` as l ON ld.language_id = l.language_id WHERE ld.site_id = ".$siteid." AND ld.language_id = ".$language_id." ORDER BY ld.id DESC";
		$query = DB::query(Database::SELECT,$sql);
		return $query->execute()->as_array();
	}
	public function insert_languagedata($siteid,$language_key,$language_values,$language_id){
		$msg_cnt = 0;
		for($i=2; $i<=count($language_values); $i++){
			//$results = DB::insert('language_data', array('language_id','key','value','site_id')) ->values(array($language_id,$language_key[$i],$language_values[$i],$siteid))->execute();
			$sel_value  = DB::query(Database::SELECT, "SELECT * FROM language_data  WHERE language_id = ".$language_id." AND  language_key = '".$language_key[$i]."' AND site_id = ".$siteid."")->execute()->as_array();
			//$sel_value = $query->execute()->as_array();
			if(empty($sel_value)){
				if($language_values[$i] !='' && $language_values[$i] !='' ){
					DB::query(Database::INSERT, "INSERT IGNORE INTO language_data (language_id, language_key, value ,site_id) VALUES (".$language_id.", '".$language_key[$i]."', '".htmlspecialchars($language_values[$i], ENT_QUOTES)."', ".$siteid.")")->execute();
					$msg_cnt++ ;
				}
			}
		}
		return  $msg_cnt;
	}
	public function get_language_value($id){
		$sql = "SELECT * FROM language_data where id =".$id;
		$query = DB::query(Database::SELECT,$sql);
		$res = $query->execute()->as_array();
		return  $res;
	}
	public function  upadate_language_value($value,$id,$actiontype){
		if($actiontype == 'edit'){
			$sql = "UPDATE language_data SET value='".$value."' WHERE id = ".$id."";
			return $res = DB::query(Database::UPDATE,$sql)->execute();
		}elseif($actiontype == 'delete'){
			$sql = "DELETE FROM language_data WHERE id = ".$id;
			$query = DB::query(Database::DELETE, $sql);
			return $query->execute();
		}
	}
	public function add_language_value($language_key,$value,$language_id,$siteid){
		$msg_cnt = 0;
		$sel_value  = DB::query(Database::SELECT, "SELECT * FROM language_data  WHERE language_id = ".$language_id." AND  language_key = '".$language_key."' AND site_id = ".$siteid."")->execute()->as_array();
			//$sel_value = $query->execute()->as_array();
			//echo htmlspecialchars($value, ENT_QUOTES); die;
			if(empty($sel_value)){
				DB::query(Database::INSERT, "INSERT IGNORE INTO language_data (language_id, language_key, value ,site_id) VALUES (".$language_id.", '".$language_key."', '".$value."', ".$siteid.")")->execute();
				$msg_cnt++;
			}
		return 	$msg_cnt;
	}
	public function get_selected_langue($siteid) {
		//Get user language from settings table
		$userid = Auth::instance()->get_user()->pk();
		$sql = "SELECT * FROM `user_settings` WHERE site_id=".$siteid." AND user_id=".$userid;
		$query = DB::query(Database::SELECT,$sql);
		$res = $query->execute()->as_array();
		if(!empty($res[0]['language'])){
			$languageid = (count($res)>0) ? $res[0]['language'] : 1;
		}else{$languageid = 1 ;}
		$sql = "SELECT * FROM language WHERE language_id =".$languageid;
		$query = DB::query(Database::SELECT,$sql);
		$languages = $query->execute()->as_array();
		$data = array();
		$data = $languages[0];
		/*foreach($languages as $lang){
			$data[$languages['language_id']] = $languages['name'];
		}*/
		return $data;
	}
	public function get_current_langue($siteid) {
		//Get user language from settings table
		$userid = Auth::instance()->get_user()->pk();
		$sql = "SELECT * FROM `user_settings` WHERE site_id=".$siteid." AND user_id=".$userid;
		$query = DB::query(Database::SELECT,$sql);
		$res = $query->execute()->as_array();
		if(!empty($res[0]['language'])){
			$languageid = (count($res)>0) ? $res[0]['language'] : 1;
		}else{$languageid = 1 ;}
		$sql = "SELECT * FROM language_data WHERE language_id =".$languageid." and site_id =".$siteid;
		$query = DB::query(Database::SELECT,$sql);
		$languages = $query->execute()->as_array();
		
		$data = array();
		foreach($languages as $lang){
			$data[$lang['language_key']] = $lang['value'];
		}
		return $data;
	}
	public function get_timezone_list() {
		$zones_array = array();
		$timestamp = time();
		foreach(timezone_identifiers_list() as $key => $zone) {
			date_default_timezone_set($zone);
			$zones_array[$key]['zone'] = $zone;
			//$zones_array[$key]['diff_from_GMT'] = 'UTC/GMT ' . date('P', $timestamp);
			$zones_array[$key]['diff_from_GMT'] = date('P', $timestamp);
		}
		return $zones_array;
	}
	function get_contry_list() {
		$sql = "SELECT * FROM `country`";
		$query = DB::query(Database::SELECT,$sql);
		return $query->execute()->as_array();
	}
	function generate_timezone_list($country='') {
		/*static $regions = array(
		DateTimeZone::AFRICA,
		DateTimeZone::AMERICA,
		DateTimeZone::ANTARCTICA,
		DateTimeZone::ASIA,
		DateTimeZone::ATLANTIC,
		DateTimeZone::AUSTRALIA,
		DateTimeZone::EUROPE,
		DateTimeZone::INDIAN,
		DateTimeZone::PACIFIC,
		);*/
		$timezone_list = array();
		if($country!= ''){
			if($country == 1){
				$region = DateTimeZone::AFRICA;
			}
			else if($country == 2){
				$region = DateTimeZone::AMERICA;
			}
			else if($country == 3){
				$region = DateTimeZone::ANTARCTICA;
			}
			else if($country == 4){
				$region = DateTimeZone::ARCTIC;
			}
			else if($country == 5){
				$region = DateTimeZone::ASIA;
			}
			else if($country == 6){
				$region = DateTimeZone::ATLANTIC;
			}
			else if($country == 7){
				$region = DateTimeZone::AUSTRALIA;
			}
			else if($country == 8){
				$region = DateTimeZone::EUROPE;
			}
			else if($country == 9){
				$region = DateTimeZone::INDIAN;
			}
			else if($country == 10){
				$region = DateTimeZone::PACIFIC;
			}
			$timezones = array();
			/*foreach( $regions as $region ) {
				$timezones = array_merge( $timezones, DateTimeZone::listIdentifiers( $region ) );
			}*/
			$timezones = array_merge( $timezones, DateTimeZone::listIdentifiers( $region ) );
			$timezone_offsets = array();
			foreach( $timezones as $timezone ){
				$tz = new DateTimeZone($timezone);
				$timezone_offsets[$timezone] = $tz->getOffset(new DateTime);
			}
			// sort timezone by offset
			//asort($timezone_offsets);
			$timezone_list = array();
			foreach( $timezone_offsets as $timezone => $offset ) {
				$offset_prefix = $offset < 0 ? '-' : '+';
				$offset_formatted = gmdate( 'H:i', abs($offset) );
				//$pretty_offset = "UTC${offset_prefix}${offset_formatted}";
				$pretty_offset = "${offset_prefix}${offset_formatted}";
				$timezone_list[$timezone] = "$timezone (${pretty_offset})";
			}
		}
		return $timezone_list;
	}
	public function user_device_count ($siteid) {
		$userid = Auth::instance()->get_user()->pk();
		// $device_sql = "SELECT count(*) AS device_cnt FROM userdevice WHERE site_id = ".$siteid." AND user_id = ".$userid;
		$device_sql = "SELECT count(*) AS device_cnt FROM `device_integrations` di, `sitedevice` sd, `userdevice` ud WHERE di.status!=2 AND sd.status=0 AND di.id=sd.device_id AND sd.device_id=ud.device_id AND sd.site_id=".$siteid." AND ud.site_id=".$siteid." AND ud.user_id=".$userid;
		$query = DB::query(Database::SELECT, $device_sql);
		$device_cnt = $query->execute()->as_array(); 
		return isset($device_cnt[0]) ? $device_cnt[0] : $device_cnt;
	}
	public function selected_user_device ($siteid) {
		$userid = Auth::instance()->get_user()->pk();
		// $device_sql = "SELECT * FROM userdevice WHERE site_id = ".$siteid." AND user_id = ".$userid." AND status = 0";
		$device_sql = "SELECT di.name, sd.device_id, sd.status, sd.site_id FROM `device_integrations` di, `sitedevice` sd WHERE di.status!=2 AND sd.status=0 AND di.id=sd.device_id AND sd.site_id=".$siteid." AND sd.device_id NOT IN(SELECT ud.device_id FROM `device_integrations` di, `userdevice` ud WHERE di.status!=2 AND ud.status!=0 AND di.id=ud.device_id AND ud.site_id=".$siteid." AND ud.user_id=".$userid.")";
		$query = DB::query(Database::SELECT, $device_sql);
		$selected_device = $query->execute()->as_array();
		return $selected_device;
	}
	public function selected_site_device ($siteid) {
		// $device_sql = "SELECT * FROM sitedevice WHERE site_id = ".$siteid." AND status = 0";
		$device_sql = "SELECT di.name, sd.device_id, sd.status, sd.site_id FROM `device_integrations` di, `sitedevice` sd WHERE di.status!=2 AND sd.status=0 AND di.id=sd.device_id AND sd.site_id=".$siteid;
		$query = DB::query(Database::SELECT, $device_sql);
		$selected_device = $query->execute()->as_array();
		return $selected_device;
	}
	public function getSetTableByName($tableName){
		$sql = "SELECT * FROM $tableName";
		$query = DB::query(Database::SELECT,$sql);
		$list = $query->execute()->as_array();
		return $list;
	}
	public function getLanguageById($langId) {
		$sql = "SELECT name FROM language WHERE language_id =".$langId;
		$query = DB::query(Database::SELECT,$sql);
		$languages = $query->execute()->as_array();
		$data = array();
		$data = $languages[0]['name'];
		return $data;
	}
	public function getWeightById($weightId) {
		$sql = "SELECT resist_title FROM set_resist WHERE resist_id =".$weightId;
		$query = DB::query(Database::SELECT,$sql);
		$languages = $query->execute()->as_array();
		$data = array();
		$data = $languages[0]['resist_title'];
		return $data;
	}
	public function getDistanceById($distanceId) {
		$sql = "SELECT dist_title FROM set_dist WHERE dist_id =".$distanceId;
		$query = DB::query(Database::SELECT,$sql);
		$languages = $query->execute()->as_array();
		$data = array();
		$data = $languages[0]['dist_title'];
		return $data;
	}
	public function insertUserSettings($settings, $userId){
		$results = DB::insert('user_settings', array('site_id', 'user_id', 'country', 'timezone', 'time_format', 'date_format', 'language', 'week_sarts_on', 'Weight', 'Distance', 'Network_updates', 'Assignment_upcoming_reminder', 'Assignment_you_missed', 'Shared_Workout_Plan_received', 'Sharing', 'Invitation_to_connect', 'new_features_tips_special_offers', 'Receive_email_alerts_for_Exercises_and_Workouts', 'time_to_send_email', 'device_integrations')) ->values(array($settings['site_id'], $userId, $settings['country'], $settings['timezone'], $settings['time_format'], $settings['date_format'], $settings['language'], $settings['week_sarts_on'], $settings['Weight'], $settings['Distance'], $settings['Network_updates'], $settings['Assignment_upcoming_reminder'], $settings['Assignment_you_missed'], $settings['Shared_Workout_Plan_received'], $settings['Sharing'], $settings['Invitation_to_connect'], $settings['new_features_tips_special_offers'], $settings['Receive_email_alerts_for_Exercises_and_Workouts'], $settings['time_to_send_email'], $settings['device_integrations']))->execute();
		return $results[0];
	}
}