<?php defined('SYSPATH') or die('No direct script access.');
class Controller_Settings extends Controller_Website {
	public function _construct() {
		parent::__construct($request, $response);
	}
	public function action_index() {

	}
	public function action_preference() {
		$this->template->title = 'Preference Defaults Level Control';
		$this->render();
		$settings_model = ORM::factory('settings');
		$user_id = $this->request->param('id');
		$siteid = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
		$this->template->content->Timezone = $settings_model->getAllTimezone();
		$this->template->content->device_Integrations = $settings_model->getAlldevice_Integrations();
		$user_settings = $settings_model->getsettings();
		if(!empty($user_settings)){
			$Preference_Defaults = $this->template->content->Preference_Defaults = $user_settings;
		}else{
			$Preference_Defaults = $this->template->content->Preference_Defaults = $settings_model->get_site_settings();
		}
		$this->template->content->countrylist 	= $settings_model->get_contry_list();
		$this->template->content->languagelist 	= $settings_model->languagelist();
		$this->template->content->weightlist 	= $settings_model->getSetTableByName('set_resist');
		$this->template->content->distancelist 	= $settings_model->getSetTableByName('set_dist');
		$user_device_cnt = $settings_model->user_device_count($siteid);
		if(isset($user_device_cnt['device_cnt']) && $user_device_cnt['device_cnt'] > 0){
			$selected_device = $settings_model->selected_user_device($siteid);
		}else{
			$selected_device = $settings_model->selected_site_device($siteid);
		}
		$decivice_val = array();
		if(!empty($selected_device)){
			foreach($selected_device as $keys=>$values){ $decivice_val[] = $values['device_id']; } 
		}
		$this->template->content->selected_device = $decivice_val;
		if(isset($Preference_Defaults[0]['timezone']) && $Preference_Defaults[0]['timezone'] != ''){
			$this->template->content->setting_contry = $Preference_Defaults[0]['country'];
			$this->template->content->setting_zone = str_replace('|', ':', $Preference_Defaults[0]['timezone']);
			$this->template->content->timezonelist = $settings_model->generate_timezone_list($Preference_Defaults[0]['country']);
			$timezone_array = explode(" ",$Preference_Defaults[0]['timezone']);
			date_default_timezone_set($timezone_array[0]);
		}
	}
	public function action_generate_timezone_list1() {
	 	if ($this->request->is_ajax()) $this->auto_render = FALSE;
		$timezone_list = array();
		$country = $_POST['country'];
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
			foreach( $timezones as $timezone ) {
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
		$timezone_list_option = '';
		foreach($timezone_list as $t){
			$timezone_list_option .= '<option value="'.$t.'">'.$t.'</option>';
		}
		$response['success'] = true;
		$response['val'] = $timezone_list_option;
		$this->response->body(json_encode($response));
	}
	public function action_add_or_update_settings() {
		if ($this->request->is_ajax()){
			$this->auto_render = FALSE;
		}
		$settings_model = ORM::factory('settings');
		$workoutModel 	= ORM::factory('workouts');
		$siteid = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
		$userid = Auth::instance()->get_user()->pk();
		$sql = "SELECT * FROM `user_settings` WHERE site_id=".$siteid." AND user_id=".$userid;
		$query = DB::query(Database::SELECT,$sql);
		$check_preference = $query->execute()->as_array();
		$activity_feed = array();
		$activity_feed["type_id"]    	= $activity_feed["user"]  = $userid; // user id
		$activity_feed["site_id"]  		= Session::instance()->get('current_site_id');
		$activity_feed["feed_type"]   	= 18; // This get from feed_type table
		if(!empty($check_preference)){
			$condtnStr = 'site_id='.$siteid." AND user_id=".$userid;
			if(isset($_POST['sendmail'])){
				$sql = "update user_settings set ".$_POST['update']." = '".$_POST[$_POST['update']]."' WHERE ".$condtnStr;
				$activity_feed["action_type"]   = 41;
				$activity_feed["json_data"]   	= json_encode('to '.$_POST[$_POST['update']]);
			}else{
				if($_POST['update'] == 'time_format'){
					$tfor = explode(' ',$_POST[$_POST['update']]);
					if($tfor[1] == 'a') {$tsfor = 'am';} else {$tsfor = 'AM';}
					$t_updatefor = explode(' ',$_POST['time_to_send_email']);
					$ft_updatefor = $t_updatefor[0].' '.$tsfor;
					$sql = "update user_settings set ".$_POST['update']." = '".$_POST[$_POST['update']]."',time_to_send_email = '".$ft_updatefor."' WHERE ".$condtnStr;
				}elseif($_POST['update'] == 'common_settings'){
					$sql = "update user_settings set XRset_extra_variable_flag = '".$_POST['xrsetvariableflag']."' WHERE ".$condtnStr;
				}elseif($_POST['update'] == 'week_sarts_on'){
					$weekdays          = array('1' => 'Monday','2' => 'Tuesday','3' => 'Wednesday','4' => 'Thursday','5' => 'Friday','6' => 'Saturday','7' => 'Sunday');
					$sql = "update user_settings set week_sarts_on = '".$_POST['week_sarts_on']."',time_to_send_email = '".$_POST['time_to_send_email']."' WHERE ".$condtnStr;
					$activity_feed["action_type"]   = 48;
					$activity_feed["json_data"]   	= json_encode('to '.$weekdays[$_POST['week_sarts_on']]);
				}else if($_POST['update'] == 'timezone'){
					$timezoneArray = explode('||',$_POST[$_POST['update']]);
					if($check_preference[0]['timezone'] != $timezoneArray[1]){
						$sql = "update user_settings set country = '".$timezoneArray[0]."',timezone = '".$timezoneArray[1]."',time_to_send_email = '".$_POST['time_to_send_email']."' WHERE ".$condtnStr;
						$activity_feed["action_type"]   = 35;
						$activity_feed["json_data"]   	= json_encode('to '.$timezoneArray[1]);
					}
				}else if($_POST['update'] == 'measurements'){
					$measurementsArray = explode('||',$_POST[$_POST['update']]);
					if($check_preference[0]['Weight'] != $measurementsArray[0] || $check_preference[0]['Distance'] !=$measurementsArray[1]){
						$sql = "update user_settings set Weight = '".$measurementsArray[0]."',Distance = '".$measurementsArray[1]."',time_to_send_email = '".$_POST['time_to_send_email']."' WHERE ".$condtnStr;
						if($check_preference[0]['Weight'] != $measurementsArray[0]){
							$activity_feed["action_type"]   = 38;
							$activity_feed["json_data"]   	= (!empty($measurementsArray[0]) ? json_encode('to '.$settings_model->getWeightById($measurementsArray[0])) : '');
						}
						if($check_preference[0]['Distance'] !=$measurementsArray[1]){
							$activity_feed_new["action_type"]   = 39;
							$activity_feed_new["json_data"]   	= (!empty($measurementsArray[1]) ? json_encode('to '.$settings_model->getDistanceById($measurementsArray[1])) : '');
						}
					}
				}else if($_POST['update'] == 'Updates_news'){
					$Updates_newsArray = explode('||',$_POST[$_POST['update']]);
					$sql = "update user_settings set Network_updates = '".$Updates_newsArray[0]."',Assignment_upcoming_reminder = '".$Updates_newsArray[1]."',Assignment_you_missed = '".$Updates_newsArray[2]."',Shared_Workout_Plan_received = '".$Updates_newsArray[3]."',time_to_send_email = '".$_POST['time_to_send_email']."' WHERE ".$condtnStr;
					if($check_preference[0]['Assignment_upcoming_reminder'] != $Updates_newsArray[1]){
						$activity_feed["action_type"]   = 40;
						$activity_feed["json_data"]   	= json_encode('to '.($Updates_newsArray[1]=='1' ? '1 day' : 'No Email'));
					}
				}else if($_POST['update'] == 'messages_users'){
					$messages_usersArray = explode('||',$_POST[$_POST['update']]);
					$sql = "update user_settings set Sharing = '".$messages_usersArray[0]."',Invitation_to_connect = '".$messages_usersArray[1]."',time_to_send_email = '".$_POST['time_to_send_email']."' WHERE ".$condtnStr;
				}else if($_POST['update'] == 'messages_team'){
					$messages_teamArray = explode('||',$_POST[$_POST['update']]);
					$sql = "update user_settings set new_features_tips_special_offers = '".$messages_teamArray[0]."',Receive_email_alerts_for_Exercises_and_Workouts = '".$messages_teamArray[1]."',time_to_send_email = '".$_POST['time_to_send_email']."' WHERE ".$condtnStr;
				}else if($_POST['update'] == 'device_integrations'){
					$device_integrations = $_POST['device_integrations'];
					$device_status = ($_POST['device_state'] == 'true') ? '0' : '1';
					$device_sql = "SELECT * FROM userdevice WHERE $condtnStr AND device_id = ".$device_integrations;
					$query = DB::query(Database::SELECT, $device_sql);
					$get_device = $query->execute()->as_array();
					if(!empty($get_device) && $get_device[0]['status'] == '1'){
						$device_update = "update userdevice set status = 0 where $condtnStr and device_id = ".$device_integrations; //echo $device_del; die;
						$query = DB::query(Database::UPDATE, $device_update)->execute();
						$activity_feed["action_type"]   	= 10;
					}else if(!empty($get_device) && $get_device[0]['status'] == '0'){
						$device_del = "update userdevice set status = 1 where $condtnStr and device_id = ".$device_integrations; //echo $device_del; die;
						$query = DB::query(Database::UPDATE, $device_del)->execute();
						$activity_feed["action_type"]   	= 10;
					}else{
						$result = DB::insert('userdevice', array('site_id', 'user_id', 'device_id', 'status'))->values(array($siteid, $userid, $device_integrations, $device_status))->execute();
						$activity_feed["action_type"]   	= 10;
					}
				}else{
					$allowUpdate = false;
					if($_POST['update'] == 'language' && $_POST[$_POST['update']] != $check_preference[0]['language']){
						$allowUpdate = true;
						$activity_feed["action_type"] = 36;
						$activity_feed["json_data"]   = json_encode('to '.$settings_model->getLanguageById($_POST[$_POST['update']]));
					}elseif($_POST['update'] == 'date_format' && $_POST[$_POST['update']] != $check_preference[0]['date_format']){
						$allowUpdate = true;
						$activity_feed["action_type"] = 37;
						$activity_feed["json_data"]   = json_encode('to '.$_POST[$_POST['update']]);
					}
					if($allowUpdate){
						$sql = "update user_settings set ".$_POST['update']." = '".$_POST[$_POST['update']]."',time_to_send_email = '".$_POST['time_to_send_email']."' WHERE ".$condtnStr;
					}
				}
			}
			if(isset($activity_feed['action_type']))
				Helper_Common::createActivityFeed($activity_feed);
			if(isset($activity_feed_new['action_type'])){
				$activity_feed['action_type'] 	= $activity_feed_new['action_type'];
				$activity_feed['json_data'] 	= $activity_feed_new['json_data'];
				Helper_Common::createActivityFeed($activity_feed);
			}
			if(isset($sql) && !empty($sql))
				$query = DB::query(Database::UPDATE,$sql)->execute();
			$succ = "Preference Settings for ".$_POST['update']." updated successfully!!!";
			if($_POST['update'] == 'timezone'){
				$ajax_timezone_array = explode("||",$_POST[$_POST['update']]);
				$ajax_zone_array = explode(" ",$ajax_timezone_array[1]);
				date_default_timezone_set($ajax_zone_array[0]);
				$Preference_Defaults = $settings_model->getsettings();
				if($Preference_Defaults[0] != ''){
					if($Preference_Defaults[0]['time_format'] != ''){
					  	$response['timeforamt'] =  Helper_Common::get_default_time('','h:i:s A');
					}else{
						$response['timeforamt'] =  Helper_Common::get_default_time('','h:i:s A');
					}
				}
			}
			if($_POST['update'] == 'time_format'){
				$Preference_Defaults = $settings_model->getsettings();
				if($Preference_Defaults[0] != ''){
					if(!empty($Preference_Defaults[0]['timezone'])){
						$ajax_zone_array = explode(" ",$Preference_Defaults[0]['timezone']);
						date_default_timezone_set($ajax_zone_array[0]);
						$response['timeforamt'] =  Helper_Common::get_default_time('','h:i:s A');
					}else{
						$response['timeforamt'] =  Helper_Common::get_default_time('','h:i:s A');
					}
					if(!empty($Preference_Defaults[0]['time_format'])){
						$ajaxoption = '';
						$tfor = explode(' ',$Preference_Defaults[0]['time_format']);
						if($tfor[1] == 'a') {$tsfor = 'am';} else {$tsfor = 'AM';}
						$start=strtotime('00:00');
						$end=strtotime('23:30');
						for ($halfhour=$start; $halfhour<=$end; $halfhour=$halfhour+30*60) {
							$ajaxoption .= '<option value='.date($Preference_Defaults[0]['time_format'],$halfhour).'';
							if(isset($Preference_Defaults[0]['time_to_send_email']) && $Preference_Defaults[0]['time_to_send_email'] != ''){
								if($Preference_Defaults[0]['time_to_send_email'] == date($Preference_Defaults[0]['time_format'],$halfhour)){
									$ajaxoption .= ' selected';
								}
							}else if(date($Preference_Defaults[0]['time_format'],$halfhour) == "08:30:00 $tsfor"){
								$ajaxoption .= ' selected';
							}
							$ajaxoption .= '>'.date($Preference_Defaults[0]['time_format'],$halfhour).'</option>';
						}
						$response['ajaxoption'] = $ajaxoption;
					}
				}
			}
			if($_POST['update'] == 'date_format'){
				if($_POST['update'] != ''){
					$response['dateforamt'] = date($_POST[$_POST['update']]);
				}
			}
		}else{
			if(isset($_POST['sendmail'])){
				$results = DB::insert('user_settings', array('site_id', 'user_id', $_POST['update'])) ->values(array($siteid, $userid, $_POST[$_POST['update']]))->execute();
				$activity_feed["action_type"]   = 41;
				$activity_feed["json_data"]   	= json_encode('to '.$_POST[$_POST['update']]);
			}
			else{
				if($_POST['update'] == 'time_format'){
					$tfor = explode(' ',$_POST[$_POST['update']]);
					if($tfor[1] == 'a') {$tsfor = 'am';} else {$tsfor = 'AM';}
					$t_updatefor = explode(' ',$_POST['time_to_send_email']);
					$ft_updatefor = $t_updatefor[0].' '.$tsfor;
					$results = DB::insert('user_settings', array('site_id', 'user_id', $_POST['update'], 'time_to_send_email')) ->values(array($siteid, $userid, $_POST[$_POST['update']], $ft_updatefor))->execute();
				}elseif($_POST['update'] == 'week_sarts_on'){
					$weekdays          = array('1' => 'Monday','2' => 'Tuesday','3' => 'Wednesday','4' => 'Thursday','5' => 'Friday','6' => 'Saturday','7' => 'Sunday');
					DB::insert('user_settings', array('site_id', 'user_id', 'week_sarts_on', 'time_to_send_email')) ->values(array($siteid, $userid, $_POST['week_sarts_on'],$_POST['time_to_send_email']))->execute();
					$activity_feed["action_type"]   = 48;
					$activity_feed["json_data"]   	= json_encode('to '.$weekdays[$_POST['week_sarts_on']]);
				}else if($_POST['update'] == 'timezone'){
					$timezoneArray = explode('||',$_POST[$_POST['update']]);
					$results = DB::insert('user_settings', array('site_id', 'user_id', 'country', 'timezone', 'time_to_send_email')) ->values(array($siteid, $userid, $timezoneArray[0], $timezoneArray[1], $_POST['time_to_send_email']))->execute();
					$activity_feed["action_type"]   = 35;
					$activity_feed["json_data"]   	= json_encode('to '.$timezoneArray[1]);
				}else if($_POST['update'] == 'measurements'){
					$measurementsArray = explode('||',$_POST[$_POST['update']]);
					$results = DB::insert('user_settings', array('site_id', 'user_id', 'Weight', 'Distance', 'time_to_send_email')) ->values(array($siteid, $userid, $measurementsArray[0], $measurementsArray[1], $_POST['time_to_send_email']))->execute();
					$activity_feed["action_type"]   = 38;
					$activity_feed["json_data"]   	= (!empty($measurementsArray[0]) ? json_encode('to '.$settings_model->getWeightById($measurementsArray[0])) : '');
					$activity_feed_new["action_type"]   = 39;
					$activity_feed_new["json_data"]   	= (!empty($measurementsArray[1]) ? json_encode('to '.$settings_model->getDistanceById($measurementsArray[1])) : '');
				}else if($_POST['update'] == 'Updates_news'){
					$Updates_newsArray = explode('||',$_POST[$_POST['update']]);
					$results = DB::insert('user_settings', array('site_id', 'user_id', 'Network_updates', 'Assignment_upcoming_reminder', 'Assignment_you_missed', 'Shared_Workout_Plan_received', 'time_to_send_email')) ->values(array($siteid, $userid, $Updates_newsArray[0], $Updates_newsArray[1], $Updates_newsArray[2], $Updates_newsArray[3], $_POST['time_to_send_email']))->execute();
					$activity_feed["action_type"]   = 40;
					$activity_feed["json_data"]   	= json_encode('to '.($Updates_newsArray[1]=='1' ? '1 day' : 'No Email'));
				}else if($_POST['update'] == 'messages_users'){
					$messages_usersArray = explode('||',$_POST[$_POST['update']]);
					$results = DB::insert('user_settings', array('site_id', 'user_id', 'Sharing', 'Invitation_to_connect', 'time_to_send_email')) ->values(array($siteid, $userid, $messages_usersArray[0], $messages_usersArray[1], $_POST['time_to_send_email']))->execute();
				}else if($_POST['update'] == 'messages_team'){
					$messages_teamArray = explode('||',$_POST[$_POST['update']]);
					$results = DB::insert('user_settings', array('site_id', 'user_id', 'new_features_tips_special_offers', 'Receive_email_alerts_for_Exercises_and_Workouts', 'time_to_send_email')) ->values(array($siteid, $userid, $messages_teamArray[0], $messages_teamArray[1], $_POST['time_to_send_email']))->execute();
				}else{
					if($_POST['update'] == 'language' && $_POST[$_POST['update']] != $check_preference[0]['language']){
						$activity_feed["action_type"] = 36;
						$activity_feed["json_data"]   = json_encode('to '.$settings_model->getLanguageById($_POST[$_POST['update']]));
					}elseif($_POST['update'] == 'date_format' && $_POST[$_POST['update']] != $check_preference[0]['date_format']){
						$activity_feed["action_type"] = 37;
						$activity_feed["json_data"]   = json_encode('to '.$_POST[$_POST['update']]);
					}
					$results = DB::insert('user_settings', array('site_id', 'user_id', $_POST['update'], 'time_to_send_email')) ->values(array($siteid, $userid, $_POST[$_POST['update']], $_POST['time_to_send_email']))->execute();
				}
			}
			if(isset($activity_feed['action_type']))
				Helper_Common::createActivityFeed($activity_feed);
			if(isset($activity_feed_new['action_type'])){
				$activity_feed['action_type'] 	= $activity_feed_new['action_type'];
				$activity_feed['json_data'] 	= $activity_feed_new['json_data'];
				Helper_Common::createActivityFeed($activity_feed);
			}
			if(!empty($results[0])){
				$succ =  'Preference Settings inserted successfully!!!';
				if($_POST['update'] == 'timezone'){
					$ajax_timezone_array = explode("||",$_POST[$_POST['update']]);
					$ajax_zone_array = explode(" ",$ajax_timezone_array[1]);
					date_default_timezone_set($ajax_zone_array[0]);
					$Preference_Defaults = $settings_model->getsettings();
					if($Preference_Defaults[0] != ''){
						if($Preference_Defaults[0]['time_format'] != ''){
							// $response['timeforamt'] =  date($Preference_Defaults[0]['time_format'], time());
							$response['timeforamt'] =  Helper_Common::get_default_time('','h:i:s A');
						}else{
							$response['timeforamt'] =  Helper_Common::get_default_time('','h:i:s A');
						}
					}
				}
				if($_POST['update'] == 'time_format'){
					$Preference_Defaults = $settings_model->getsettings();
					if($Preference_Defaults[0] != ''){
						if(!empty($Preference_Defaults[0]['timezone'])){
							$ajax_zone_array = explode(" ",$Preference_Defaults[0]['timezone']);
							date_default_timezone_set($ajax_zone_array[0]);
							// $response['timeforamt'] =  date($_POST[$_POST['update']], time());
							$response['timeforamt'] =  Helper_Common::get_default_time('','h:i:s A');
						}else{
							$response['timeforamt'] =  Helper_Common::get_default_time('','h:i:s A');
						}
					}
				}
				if($_POST['update'] == 'date_format'){
					if($_POST['update'] != ''){
						$response['dateforamt'] =  date($_POST[$_POST['update']]);
					}
				}
			}
		}
		$settings_model = ORM::factory('settings');
		$this->user_timezone = $this->user_timeformat = $this->user_dateformat = $this->user_weight = $this->user_distance = $this->user_language = '';
		$user_settings = $settings_model->getsettings();
		if(!empty($user_settings) && count($user_settings)>0){
			$this->user_timezone	= $user_settings[0]['timezone'];
			$this->user_timeformat	= $user_settings[0]['time_format'];
			$this->user_dateformat	= $user_settings[0]['date_format'];
			$this->user_datetimeformat = $this->user_dateformat.' '.$this->user_timeformat;
			$this->user_weight		= $user_settings[0]['Weight'];
			$this->user_distance	= $user_settings[0]['Distance'];
			$this->user_language	= $user_settings[0]['language'];
			$this->user_weekstartson = $user_settings[0]['week_sarts_on'];
			View::set_global('user_timezone'	, $this->user_timezone);
			View::set_global('user_timeformat'	, $this->user_timeformat);
			View::set_global('user_dateformat'	, $this->user_dateformat);
			View::set_global('user_datetimeformat'	, $this->user_datetimeformat);
			View::set_global('user_weight'		, $this->user_weight);
			View::set_global('user_distance'	, $this->user_distance);
			View::set_global('user_language'	, $this->user_language);
			View::set_global('user_weekstartson'	, $this->user_weekstartson);
			$this->session->set('user_timezone', $this->user_timezone);
			$this->session->set('user_timeformat', $this->user_timeformat);
			$this->session->set('user_dateformat', $this->user_dateformat);
			$this->session->set('user_weight', $this->user_weight);
			$this->session->set('user_distance', $this->user_distance);
			$this->session->set('user_datetimeformat', $this->user_datetimeformat);
			$this->session->set('user_language', $this->user_language);
			$this->session->set('user_weekstartson', $this->user_weekstartson);
			$language_idsite = ($this->session->get('user_language') ? $this->session->get('user_language') : '1');
			$loginuser_lang = Helper_Common::getlanguage($language_idsite);
			$this->session->set('user_lang_id',$loginuser_lang);
			if ($loginuser_lang != null)
				$this->session->set('lang', $loginuser_lang);
			else
				$this->session->set('lang', 'en');
			I18n::lang('front-'.$this->session->get('current_site_id').'-'.$this->session->get('lang').'-'.strtolower(Request::current()->controller()));
			if(!empty($this->user_timezone)){
				$timezone_array = explode(" ", $this->user_timezone);
				date_default_timezone_set($timezone_array[0]);
			}
			/*** Get user access based on role ***/
			$role_access_model = ORM::factory('admin_roleaccess');
			$role_id           = Model::instance('Model/admin/exercise')->getUserRole();
			$roleAccessArray   = array();
			if($role_id>0){
				$condtn        = 'role_id=' . $role_id . ' and site_id=' . $this->session->get('current_site_id');
				$getRoleAccess = $role_access_model->getRoleAccessByContn('access_type_id', $condtn);
				if(isset($getRoleAccess) && count($getRoleAccess) > 0) {
					foreach($getRoleAccess as $key => $value)
						$roleAccessArray[] = $value['access_type_id'];
				}
			}
			$this->session->set('roleAccessArray', $roleAccessArray);
		}
		$emailNotifyArray = array('triggerby_time' 	=>	 Helper_Common::getUserEmailTime());
		$workoutModel->updateEmailNotify($emailNotifyArray);
		$response['success'] = true;
		$response['message'] = $succ;
		$this->response->body(json_encode($response));
	}
	function action_send_reminder(){
		$settings_model = ORM::factory('admin_settings');
		$settings = $settings_model->getsettings();
		$updates_news = $settings[0]['Updates_news']; //echo $updates_news;
		$updates_news_arr =explode('||',$updates_news);  //print_r($updates_news_arr);
		//$upcoming_remainder_days = $updates_news_arr[1];
		//$missed_assignment_days = $updates_news_arr[2];
		//print_r($settings);
	}
	
} // End Settings