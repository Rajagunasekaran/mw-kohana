<?php
defined('SYSPATH') or die('No direct access allowed.');

class Model_Admin_Settings extends Model{
    public function getAllTimezone(){
    	$sql = "SELECT * FROM `timezone` ";	
		$query = DB::query(Database::SELECT,$sql);						
		return $query->execute()->as_array();
	}
	public function getAlldevice_Integrations(){
    	$sql = "SELECT * FROM `device_integrations` where status !=2";	
		$query = DB::query(Database::SELECT,$sql);						
		return $query->execute()->as_array();
	}
	public function getsettings(){
		$siteid = Session::instance()->get('current_site_id');
		$sql = "SELECT * FROM `site_settings` where site_id='".$siteid."'";	
		$query = DB::query(Database::SELECT,$sql);						
		return $query->execute()->as_array();
	}
	public function get_user_settings(){
		$siteid = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '1');
		$userid = Auth::instance()->get_user()->pk();
		$sql = "SELECT * FROM `user_settings` WHERE site_id=".$siteid." AND user_id=".$userid;
		$query = DB::query(Database::SELECT,$sql);
		return $query->execute()->as_array();
	}
	public function check_preference($val){
		$siteid = Session::instance()->get('current_site_id');
    	$sql = "SELECT id FROM `site_settings` where site_id='".$siteid."'";	
		$query = DB::query(Database::SELECT,$sql);						
		$check_preference = $query->execute()->as_array();
		if(!empty($check_preference)){
			$condtnStr = 'site_id='.$siteid;
			$sql = "update site_settings set ".$val['update']." = ".$val[$val['update']]." WHERE ".$condtnStr;		 
		    $query = DB::query(Database::UPDATE,$sql);						
		    $query->execute();
			$succ =  "Preference Settings for ".$val['update']." Updated Successfully";
		}else{
			$results = DB::insert('site_settings', array('site_id',$val['update'])) ->values(array($siteid,$val['timezone']))->execute();
		    if(!empty($results[0])){
				$succ =  'Preference Settings Inserted Successfully';
			}
		}
		
	}
	
	public function measurements_weight(){
		$sql = "SELECT * FROM set_resist";	
		$query = DB::query(Database::SELECT,$sql);						
		return $query->execute()->as_array();
	}	
	
	public function measurements_distance(){
		$sql = "SELECT * FROM set_dist";	
		$query = DB::query(Database::SELECT,$sql);						
		return $query->execute()->as_array();
	}
	// Get language list
	public function languagelist(){
		
		$sql = "SELECT * FROM language";	
		$query = DB::query(Database::SELECT,$sql);						
		return $query->execute()->as_array();
	}
	// Get language Data
	public function languagedata($siteid,$language_id){
		
		$sql = "SELECT * FROM language_data as ld LEFT JOIN language as l ON ld.language_id = l.language_id
				WHERE ld.site_id = ".$siteid." and ld.language_id = ".$language_id." order by  ld.id desc";
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
	
	public function get_selected_langue($siteid)
	{
		//Get user language from settings table
		$sql = "SELECT * FROM site_settings where site_id =".$siteid;	
		$query = DB::query(Database::SELECT,$sql);						
		$res = $query->execute()->as_array();
		if(!empty($res[0]['language'])){
		$languageid = (count($res)>0) ? $res[0]['language'] : 1; 
		}else{$languageid = 1 ;}	
		$sql = "SELECT * FROM language where language_id =".$languageid; 
		$query = DB::query(Database::SELECT,$sql);
		$languages = $query->execute()->as_array();		
		
		$data = array();
		$data = $languages[0];
		/*foreach($languages as $lang){
			$data[$languages['language_id']] = $languages['name'];
		}*/
		return $data;
		
	}
	
	
	public function get_current_langue($siteid)
	{
		//Get user language from settings table
		$sql = "SELECT * FROM site_settings where site_id =".$siteid;	
		$query = DB::query(Database::SELECT,$sql);						
		$res = $query->execute()->as_array();
		if(!empty($res[0]['language'])){
		$languageid = (count($res)>0) ? $res[0]['language'] : 1; 
		}else{$languageid = 1 ;}	
		$sql = "SELECT * FROM language_data where language_id =".$languageid." and site_id =".$siteid; 
		$query = DB::query(Database::SELECT,$sql);
		$languages = $query->execute()->as_array();		
		
		$data = array();
		foreach($languages as $lang){
			$data[$lang['language_key']] = $lang['value'];
		}
		return $data;
		
	}
	
	
	public function get_timezone_list()
	{
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
	function get_contry_list()
	{   
	    $sql = "SELECT * FROM `country`";
		$query = DB::query(Database::SELECT,$sql);						
		return $query->execute()->as_array();
	}
	function generate_timezone_list($country='')
	{
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
			/*foreach( $regions as $region )
			{
			$timezones = array_merge( $timezones, DateTimeZone::listIdentifiers( $region ) );
			}*/
			$timezones = array_merge( $timezones, DateTimeZone::listIdentifiers( $region ) );
			$timezone_offsets = array();
			foreach( $timezones as $timezone )
			{
			$tz = new DateTimeZone($timezone);
			$timezone_offsets[$timezone] = $tz->getOffset(new DateTime);
			}
			// sort timezone by offset
			//asort($timezone_offsets);
			$timezone_list = array();
			foreach( $timezone_offsets as $timezone => $offset )
			{
			$offset_prefix = $offset < 0 ? '-' : '+';
			$offset_formatted = gmdate( 'H:i', abs($offset) );
			//$pretty_offset = "UTC${offset_prefix}${offset_formatted}";
			$pretty_offset = "${offset_prefix}${offset_formatted}";
			$timezone_list[$timezone] = "$timezone (${pretty_offset})";
			}
			}
			return $timezone_list;
	}
	
	public function selected_device ($siteid)
	{
		$device_sql 		= "SELECT * FROM sitedevice where site_id = ".$siteid." and status = 0";
		$query				= DB::query(Database::SELECT, $device_sql);
		$selected_device    = $query->execute()->as_array();						
		return $selected_device;
	}
    public function selected_User_device ($siteid)
	{
		$userid = Auth::instance()->get_user()->pk();
		$device_sql 		= "SELECT * FROM userdevice where site_id = ".$siteid." and user_id=".$userid." and status = 0";
		$query				= DB::query(Database::SELECT, $device_sql);
		$selected_device    = $query->execute()->as_array();						
		return $selected_device;
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
}