<?php defined('SYSPATH') or die('No direct script access.');
ini_set('max_execution_time', 0);
ini_set('display_errors',0);
class Controller_General extends Controller {

	public function _construct() {
         parent::__construct($request, $response);
    } 
		
	public function action_index()
	{
		
	}
	public function action_assignworkoutplan(){
		$sql = "SELECT wka.*,wc.*,ud.*,usite.*,ust.* FROM  wkout_assign_gendata AS wka 
		JOIN unit_status AS uns ON (wka.status_id=uns.status_id AND wka.status_id !=4) 
		JOIN sites AS usite ON wka.site_id = usite.id 
		JOIN users AS ud ON wka.user_id = ud.id 
		JOIN user_sites as us ON (us.site_id= wka.site_id AND us.user_id = ud.id)
		JOIN roles AS ua ON wka.access_id = ua.id 
		LEFT JOIN user_settings AS ust ON wka.user_id = ust.user_id 
		LEFT JOIN wkout_color AS wc ON wka.wkout_color=wc.color_id 
		LEFT JOIN wkout_focus_grp AS wf ON wka.wkout_focus=wf.focus_grp_id 
		where wka.status_id != 4 AND wka.is_email = '0'	AND ust.Receive_email_alerts_for_Exercises_and_Workouts != '' AND ust.Receive_email_alerts_for_Exercises_and_Workouts != 3 AND usite.is_active = 1 AND usite.is_deleted = 0 AND wka.marked_status = '0' AND us.status='1' order by wka.assigned_date desc ";
		//echo "===========".$sql; //convert_tz(wka.assigned_date, '+11:00', '+05:30' ) AS dateassignned,
		//die();
		$query 	= DB::query(Database::SELECT,$sql);
		$main_arraycronssch 	= $query->execute()->as_array();
		echo "<pre>";print_r($main_arraycronssch);echo "</pre>";die();
		$dyn_valuetotal = $dyninner_valuetotal = 0;
		$weekuseriddata = $valuetotal = '';
	    //echo HTML::script('assets/js/jquery.js'); 
		//echo "<script type=\"text/javascript\">$(document).ready(function(){ setTimeout(function(){window.location.reload(true)},500); });</script>";
		if(is_array($main_arraycronssch) && count($main_arraycronssch) > 0) {
			$valuetotal = count($main_arraycronssch);
			foreach($main_arraycronssch as $key_val => $value_val){
				$timezone_value = 'Australia/Sydney (+10|00)';
				if($value_val['timezone'] != ''){
					$timezone_value = $value_val['timezone'];
				}else {
					$sqlsite_setting = "SELECT * from site_settings where site_id ='".$value_val['site_id']."' ";
					$query_ss 	= DB::query(Database::SELECT,$sqlsite_setting);
					$main_arrayss 	= $query_ss->execute()->as_array();
					if(isset($main_arrayss[0]['timezone']) && $main_arrayss[0]['timezone'] != '')
						$timezone_value = $main_arrayss[0]['timezone'] ;
				}
				echo "<pre>";print_r($value_val);echo "</pre>";
				echo "<pre>";print_r($timezone_value);echo "</pre>";
				//die();
				$ar_commonlist= explode(" ",$timezone_value);
				$dyninner_valuetotal++;
				/*if(isset($_GET['t']) && $_GET['t'] == '1'){
					date_default_timezone_set('Australia/Sydney');
					$datetime = new DateTime('2016-05-21');
					$la_time = new DateTimeZone($ar_commonlist[0]);
					$datetime->setTimezone($la_time);
					$correctdttime = $datetime->format('Y-m-d');
				}else{*/
					date_default_timezone_set('Australia/Sydney');
					$datetime = new DateTime($value_val['assigned_date']);
					$la_time = new DateTimeZone($ar_commonlist[0]);
					$datetime->setTimezone($la_time);
					$correctdttime = $datetime->format('Y-m-d');
				//}
				if(isset($_GET['t']) && $_GET['t'] == '1'){
					//to get current date as per timezone
					date_default_timezone_set('Australia/Sydney');
					
					$datetimecur = new DateTime('2016-05-22 02:00:00 pm');
					
					$la_timecur = new DateTimeZone($ar_commonlist[0]);
					$datetimecur->setTimezone($la_timecur);
					$currenttdttime = $datetimecur->format('Y-m-d');
					$currenttdtweek = $datetimecur->format('D');
					$currentthhmmss = $datetimecur->format('H:i:s a');
				}else{
					//to get current date as per timezone
					date_default_timezone_set('Australia/Sydney');
					
					$datetimecur = new DateTime();
					
					$la_timecur = new DateTimeZone($ar_commonlist[0]);
					$datetimecur->setTimezone($la_timecur);
					$currenttdttime = $datetimecur->format('Y-m-d');
					$currenttdtweek = $datetimecur->format('D');
					$currentthhmmss = $datetimecur->format('H:i:s a');
				}
				$timeemailsendset = '12:00:00 am';
				if($value_val['time_to_send_email'] != ''){
					$timeemailsendset = $value_val['time_to_send_email'] ;
				}else if(isset($main_arrayss[0]['time_to_send_email']) && $main_arrayss[0]['time_to_send_email'] != ''){
					$timeemailsendset = $main_arrayss[0]['time_to_send_email'] ;
				}
				if(isset($_GET['t']) && $_GET['t'] == '1'){
					$timeemailsendset = '02:00:00 pm';
				}
				$time_in_24_hour_format  = date("H:i:s a", strtotime($timeemailsendset));
				echo $currenttdttime."=:c==a:==".$correctdttime."==day name==".$currenttdtweek."=======".$ar_commonlist[0]."=====".$value_val['Receive_email_alerts_for_Exercises_and_Workouts']."======currentthhmmss=======".$currentthhmmss."==userid:====".$value_val['user_id']."========".$time_in_24_hour_format."===<br/>";
				echo $currenttdttime."=:c==a:==".$correctdttime."==day name==".$currenttdtweek."=======".$ar_commonlist[0]."=====".$value_val['Receive_email_alerts_for_Exercises_and_Workouts']."======currentthhmmss=======".$currentthhmmss."==assigned_by:====".$value_val['assigned_by']."==wid:==".$value_val['wkout_assign_id']."====".$time_in_24_hour_format."===<br/>";
				if($currentthhmmss != $time_in_24_hour_format){
					if($value_val['Receive_email_alerts_for_Exercises_and_Workouts'] == 1){
						//echo $timezone_value."==========".$currenttdttime."=============".$correctdttime."========".$ar_commonlist[0]."===<br/>";die();
						if($currenttdttime == $correctdttime){
							$indata ='';
							$indata['fusername'] = $value_val['user_fname'];
							$indata['lusername'] = $value_val['user_lname'];
							$indata['useremail'] = $value_val['user_email'];
							$indata['userid'] = $value_val['user_id'];
							$encryptedmessage = Helper_Common::encryptPassword($value_val['user_email'].'####'.$value_val['security_code'].'####assignedplan'); 
							$indata['urlactive'] = URL::site(NULL, 'http').$value_val['slug']."/index/autoredirect/".$value_val['wkout_assign_id']."/".$encryptedmessage;
							$indata['decryurlactive'] =Helper_Common::decryptPassword($encryptedmessage); 
							$indata['site_id']		= $value_val['site_id'];
							$sendflag = $this->cropemailschedule($indata);
							if($sendflag != 1){
								$message = "User id:".$indata['userid']."\n\r Workout Assign id: ".$value_val['wkout_assign_id']."\n\r Email id: ".$indata['useremail']." - Email not send";
								$myFile = DOCROOT."assets/cache/".date("Y-m-d-h-i-s")."-WorkoutsReminder";
								file_put_contents($myFile, $message);
								echo "====daily========".$value_val['wkout_assign_id']."===<br/>";
							}else{
								$condtnStr = 'wkout_assign_id = "'.$value_val['wkout_assign_id'].'" ';
								$sql = "update wkout_assign_gendata  set is_email = '1' WHERE ".$condtnStr;	
								$query = DB::query(Database::UPDATE,$sql);						
								$query->execute();
								$dyn_valuetotal++;
								echo "====daily====update====".$value_val['wkout_assign_id']."===<br/>";
							}
						}
					}else if($value_val['Receive_email_alerts_for_Exercises_and_Workouts'] == 2){
						//echo "================".$currenttdtweek."====<br/>";die();
						if($currenttdtweek == 'Wed' ){ //'Sun'
							$datetimecur->modify('+6 days');
							// Saturday
							$datetimecur->setISODate($datetimecur->format('Y'), $datetimecur->format("W"), 6);
							//print "Saturday:" . $datetimecur->format(DATE_ATOM) . "<br/>\n";
							$saturday_val = $datetimecur->format('Y-m-d');
							// Friday
							$datetimecur->setISODate($datetimecur->format('Y'), $datetimecur->format("W"), 5);
							//print "Friday: " . $datetimecur->format(DATE_ATOM) . "<br/>\n";
							$friday_val = $datetimecur->format('Y-m-d');
							// Thursday
							$datetimecur->setISODate($datetimecur->format('Y'), $datetimecur->format("W"), 4);
							//print "Thursday: " . $datetimecur->format(DATE_ATOM) . "<br/>\n";
							$thursday_val = $datetimecur->format('Y-m-d');
							// Wednesday
							$datetimecur->setISODate($datetimecur->format('Y'), $datetimecur->format("W"), 3);
							//print "Wednesday: " . $datetimecur->format(DATE_ATOM) . "<br/>\n";
							$wednesday_val = $datetimecur->format('Y-m-d');
							// Tuesday
							$datetimecur->setISODate($datetimecur->format('Y'), $datetimecur->format("W"), 2);
							//print "Tuesday: " . $datetimecur->format(DATE_ATOM) . "<br/>\n";
							$tuesday_val = $datetimecur->format('Y-m-d');
							// Monday
							$datetimecur->setISODate($datetimecur->format('Y'), $datetimecur->format("W"), 1);
							//print "Monday: " . $datetimecur->format(DATE_ATOM) . "<br/>\n";
							$monday_val = $datetimecur->format('Y-m-d');
							// Sunday
							$datetimecur->setISODate($datetimecur->format('Y'), $datetimecur->format("W"), 0);
							//print "Sunday: " . $datetimecur->format(DATE_ATOM) . "<br/>\n";
							$sunday_val = $datetimecur->format('Y-m-d');
							//if($sunday_val == $correctdttime || $monday_val == $correctdttime || $tuesday_val == $correctdttime || $wednesday_val == $correctdttime || $thursday_val == $correctdttime ||$friday_val == $correctdttime || $saturday_val == $correctdttime){
								$weekuseriddata[$value_val['user_id']][$dyninner_valuetotal]['fusername'] = $value_val['user_fname'];
								$weekuseriddata[$value_val['user_id']][$dyninner_valuetotal]['lusername'] = $value_val['user_lname'];
								$weekuseriddata[$value_val['user_id']][$dyninner_valuetotal]['useremail'] = $value_val['user_email'];
								$weekuseriddata[$value_val['user_id']][$dyninner_valuetotal]['userid'] = $value_val['user_id'];
								$encryptedmessage = Helper_Common::encryptPassword($value_val['user_email'].'####'.$value_val['security_code'].'####assignedplan'); 
								$weekuseriddata[$value_val['user_id']][$dyninner_valuetotal]['urlactive'] = URL::site(NULL, 'http').$value_val['slug']."/index/autoredirect/".$value_val['wkout_assign_id']."/".$encryptedmessage;
								$weekuseriddata[$value_val['user_id']][$dyninner_valuetotal]['decryurlactive'] =Helper_Common::decryptPassword($encryptedmessage); 
								$weekuseriddata[$value_val['user_id']][$dyninner_valuetotal]['site_id']		= $value_val['site_id'];
								$weekuseriddata[$value_val['user_id']][$dyninner_valuetotal]['wk_assign_id']	= $value_val['wkout_assign_id'];
								$weekuseriddata[$value_val['user_id']][$dyninner_valuetotal]['wk_assign_date']	= $value_val['assigned_date'];
							//}
						}
					}
				}
			}
		}
		//print "<pre>";print_r($weekuseriddata);print '</pre>';die();
		$array_checkuserid = array();
		$workoutid_assign = '';
		if(is_array($weekuseriddata) && count($weekuseriddata) > 0){
			foreach($weekuseriddata as $key_wd => $value_wd){
				//echo "<br/>===========".count($value_wd);
				$userid_reset = $activelinkcombine = $decrylinkcombine = '';
				foreach($value_wd as $key_wd1 => $value_wd1){
					if(!in_array($value_wd1['userid'],$array_checkuserid)){
						$userid_reset++;
						$activelinkcombine .= $value_wd1['urlactive']."<br/>";
						$decrylinkcombine .= $value_wd1['decryurlactive']."<br/>";
						$workoutid_assign .= $value_wd1['wk_assign_id'].",";
						if($userid_reset == count($value_wd)){
							$indata ='';
							$indata['fusername'] = $value_wd1['fusername'];
							$indata['lusername'] = $value_wd1['lusername'];
							$indata['useremail'] = $value_wd1['useremail'];
							$indata['userid'] = $value_wd1['userid'];
							$indata['urlactive'] = $activelinkcombine;
							$indata['decryurlactive'] = $decrylinkcombine; 
							$indata['site_id']		= $value_wd1['site_id'];
							$sendflag = $this->cropemailschedule($indata);
							if($sendflag != 1){
								//$message = "User id:".$value_wd1['userid']." Email id: ".$value_wd1['useremail']." - Email not send";
								$message = "User id:".$value_wd1['userid']."\n\r Workout Assign id: ".substr($workoutid_assign,0,-1)."\n\r Email id: ".$value_wd1['useremail']." - Email not send";
								$myFile = DOCROOT."assets/cache/".date("Y-m-d-h-i-s")."-WorkoutsReminder";
								file_put_contents($myFile, $message);
								echo "====weekly========".substr($workoutid_assign,0,-1)."===<br/>";
							}else{
								$condtnStr = 'wkout_assign_id in( '.substr($workoutid_assign,0,-1).') ';
								$sql = "update wkout_assign_gendata  set is_email = '1' WHERE ".$condtnStr;	
								//echo $workoutid_assign."=============".$sql;
								$query = DB::query(Database::UPDATE,$sql);						
								$query->execute();
								$dyn_valuetotal++;
								echo "====weekly===update=====".substr($workoutid_assign,0,-1)."===<br/>";
							}
							array_push($array_checkuserid,$value_wd1['userid']);
						}
					}
				}
			}
		}
		//echo "<br/>====".$valuetotal."=============".$dyn_valuetotal;
		if($valuetotal == $dyn_valuetotal){
			$messageArray = array('subject'	=> "Success Message Workouts Plans",
							  'from' 	=> 'info@myworkouts.com',
							  'fromname'=> 'My workouts',
							  'to'		=> 'testingwebdev2016@gmail.com',
							  'replyto'	=> 'info@myworkouts.com',
							  'toname'	=> 'Super Admin',
							  'body'	=> "All emails sent successfully!!!",
							  'type'	=> 'text/html');
			echo "<span style='background:green;font-weight:bolder;font-size:22px;'>All emails sent successfully!!!</span>";
		}else{
			echo "<span style='background:green;font-weight:bolder;font-size:22px;'>Please check with log file!!!</span>";
		}
		//print "<pre>";print_r($array_checkuserid);print '</pre>';
		die();
	}
	public function action_sendassignemailnotify(){
		date_default_timezone_set('Australia/Sydney');
		$title			= "Reminder - Assigned Workout for Today";
		$starttime 		= date('H:i:00',strtotime('-15 mins'));
		$endtime 		= date('H:i:00',strtotime('+15 mins'));
		$date 			= date('Y-m-d');
		$enddate 		= date('Y-m-d',strtotime('+6 days'));
		$currenttime 	= date('H:i:00 A');
		$weekstartson 	= array('1'=>'Mon','2'=>'Tue','3'=>'Wed','4'=>'Thu','5'=>'Fri','6'=>'Sat','7'=>'Sun');
		$start 	= 0; $limit	= 2;
		$datetimecur 	= new DateTime();
		$currenttdtweek = $datetimecur->format('D');
		$startingDay 	= array_search($currenttdtweek, $weekstartson);
		$weekuseriddata	= array();
		$dyn_valuetotal = $dyninner_valuetotal =  $dyn_valuetotalfailed = 0;
		$datetime		= date('Y-m-d H:i:s');
		$time			= date('H:i:s');
		/** cron_job table **/
		$cron_result = DB::insert('cron_job', array('name', 'cron_start', 'created_date', 'modified_date'))->values(array(addslashes($title), $time, $datetime, $datetime))->execute();
		$cron_id = (isset($cron_result[0]) ? $cron_result[0] : '');
		/** cron_job table **/
		$myCacheFile = fopen(DOCROOT."assets/cache/".date("Y-m-d")."-TodayAssignmentReminder.log", "a");
		do{
			$sql = "SELECT SQL_CALC_FOUND_ROWS wka.*,wc.*,ud.*,usite.*,ust.*,ea.* FROM email_automation as ea JOIN wkout_assign_gendata AS wka ON (wka.wkout_assign_id=ea.wkout_assign_id AND wka.status_id ='1') JOIN sites AS usite ON (ea.site_id = usite.id AND ea.site_id = wka.site_id) JOIN users AS ud ON (ea.user_id = ud.id) JOIN user_sites as us ON (us.user_id = ud.id AND us.status='1' AND us.site_id = wka.site_id) LEFT JOIN user_settings AS ust ON (ea.user_id = ust.user_id AND ust.site_id = wka.site_id) LEFT JOIN wkout_color AS wc ON wka.wkout_color=wc.color_id LEFT JOIN wkout_focus_grp AS wf ON wka.wkout_focus=wf.focus_grp_id WHERE ea.is_active = '0' AND ea.is_delete = '0' AND ea.is_mail_send = '0' AND wka.is_email = '0' AND usite.is_active = 1 AND wka.marked_status = '0' AND usite.is_deleted = 0 AND ea.triggerby_time BETWEEN '".$starttime."' AND '".$endtime."' AND ust.Assignment_upcoming_reminder ='1' AND ea.triggerby_date = '".$date."' order by ea.triggerby_date asc ,ea.triggerby_time asc limit ".$start.",".$limit."";
			$query 	= DB::query(Database::SELECT,$sql);
			$found_records = 0;
			$main_arraycronssch 	= $query->execute()->as_array();
			if(!isset($total_records)){
				$sql1 = 'SELECT FOUND_ROWS() as count;';
				$total_recordsarray 	= DB::query(Database::SELECT,$sql1)->execute()->as_array();
				if(isset($total_recordsarray[0]['count']))
					$total_records = $total_recordsarray[0]['count'];
				else
					$total_records = 0;
				if(!empty($cron_id)){
					$datetime = date('Y-m-d H:i:s');
					DB::update('cron_job')->set(array('fetched_record'=>$total_records,'modified_date'=>$datetime))->where('id', '=', $cron_id)->where('status', '=', '2')->execute();
				}
			}
			if(is_array($main_arraycronssch) && count($main_arraycronssch)>0){
				foreach($main_arraycronssch as $keys => $value_val){
					$found_records++;
					$to_time = strtotime('now');
					$from_time = strtotime($value_val['triggerby_time']);
					if($value_val['Assignment_upcoming_reminder'] == 1){
						if(round(abs($to_time - $from_time) / 60,2) < 3 && round(abs($to_time - $from_time) / 60,2) > 0 ){
							$indata ='';
							$indata['fusername'] = $value_val['user_fname'];
							$indata['lusername'] = $value_val['user_lname'];
							$indata['useremail'] = $value_val['user_email'];
							$indata['userid'] = $value_val['user_id'];
							$encryptedmessage = Helper_Common::encryptPassword($value_val['user_email'].'####'.$value_val['security_code'].'####assignedplan');
							$indata['urlactive'] = '<a href="'.URL::site(NULL, 'http').$value_val['slug']."/index/autoredirect/".$value_val['wkout_assign_id']."/".$encryptedmessage.'" target="_blank" title="'.$value_val['wkout_title'].'" style="color: #1b9af7;">'.$value_val['wkout_title'].'</a>';
							$indata['decryurlactive'] =Helper_Common::decryptPassword($encryptedmessage); 
							$indata['site_id']		= $value_val['site_id'];
							$sendflag = $this->cropemailschedule($indata,'today',$title);
							if($sendflag != 1){
								Helper_Common::updateEmailAutomation($value_val['wkout_assign_id'],'notsend');
								$message = "User id:".$indata['userid']."==> Workout Assign id: ".$value_val['wkout_assign_id']."==>Email id: ".$indata['useremail']." - Email not send\n";
								fwrite($myCacheFile, $message);
								$dyn_valuetotalfailed++;
							}else{
								Helper_Common::updateEmailAutomation($value_val['wkout_assign_id']);
								$dyn_valuetotal++;
							}
						}
					}else if($value_val['Assignment_upcoming_reminder'] == 2 && false){
						$weekuseriddata[$value_val['user_id']][$dyninner_valuetotal]['fusername'] = $value_val['user_fname'];
						$weekuseriddata[$value_val['user_id']][$dyninner_valuetotal]['lusername'] = $value_val['user_lname'];
						$weekuseriddata[$value_val['user_id']][$dyninner_valuetotal]['useremail'] = $value_val['user_email'];
						$weekuseriddata[$value_val['user_id']][$dyninner_valuetotal]['userid'] = $value_val['user_id'];
						$encryptedmessage = Helper_Common::encryptPassword($value_val['user_email'].'####'.$value_val['security_code'].'####assignedplan');
						$weekuseriddata[$value_val['user_id']][$dyninner_valuetotal]['urlactive'] = '<a href="'.URL::site(NULL, 'http').$value_val['slug']."/index/autoredirect/".$value_val['wkout_assign_id']."/".$encryptedmessage.'" target="_blank" title="'.$value_val['wkout_title'].' '.Helper_Common::change_default_date_static($value_val['assigned_date'],$value_val['timezone'],$value_val['date_format']).'" style="color: #1b9af7;">'.$value_val['wkout_title'].' '.Helper_Common::change_default_date_static($value_val['assigned_date'],$value_val['timezone'],$value_val['date_format']).'</a>';
						$weekuseriddata[$value_val['user_id']][$dyninner_valuetotal]['decryurlactive'] =Helper_Common::decryptPassword($encryptedmessage); 
						$weekuseriddata[$value_val['user_id']][$dyninner_valuetotal]['site_id']		= $value_val['site_id'];
						$weekuseriddata[$value_val['user_id']][$dyninner_valuetotal]['wk_assign_id']	= $value_val['wkout_assign_id'];
						$weekuseriddata[$value_val['user_id']][$dyninner_valuetotal]['wk_assign_date']	= $value_val['assigned_date'];
						$dyninner_valuetotal++;
					}
				}
			}
			$start = $start + $limit;
		}while($found_records == $limit && $total_records > $start);
		$array_checkuserid = array();
		$workoutid_assign = '';
		if(is_array($weekuseriddata) && count($weekuseriddata) > 0){
			foreach($weekuseriddata as $key_wd => $value_wd){
				$userid_reset = $activelinkcombine = $decrylinkcombine = '';
				foreach($value_wd as $key_wd1 => $value_wd1){
					if(!in_array($value_wd1['userid'],$array_checkuserid)){
						$userid_reset++;
						$activelinkcombine .= $value_wd1['urlactive']."<br/>";
						$decrylinkcombine .= $value_wd1['decryurlactive']."<br/>";
						$workoutid_assign .= $value_wd1['wk_assign_id'].",";
						if($userid_reset == count($value_wd)){
							$indata ='';
							$indata['fusername'] = $value_wd1['fusername'];
							$indata['lusername'] = $value_wd1['lusername'];
							$indata['useremail'] = $value_wd1['useremail'];
							$indata['userid'] 	 = $value_wd1['userid'];
							$indata['urlactive'] = $activelinkcombine;
							$indata['decryurlactive'] = $decrylinkcombine; 
							$indata['site_id']	 = $value_wd1['site_id'];
							$sendflag = $this->cropemailschedule($indata , 'week',$title);
							if($sendflag != 1){
								Helper_Common::updateEmailAutomation($value_wd1['wk_assign_id'],'notsend');
								$message = "User id:".$value_wd1['userid']."==>Workout Assign id: ".substr($workoutid_assign,0,-1)."==>Email id: ".$value_wd1['useremail']." - Email not send\n";
								fwrite($myCacheFile, $message);
								$dyn_valuetotalfailed++;
							}else{
								Helper_Common::updateEmailAutomation($value_wd1['wk_assign_id']);
								$dyn_valuetotal++;
							}
							array_push($array_checkuserid,$value_wd1['userid']);
						}
					}
				}
			}
		}
		fclose($myCacheFile);
		$time			= date('H:i:s');
		$datetime		= date('Y-m-d H:i:s');
		if(!empty($cron_id)){
			DB::update('cron_job')->set(array('fetched_record'=>$total_records,'mail_send'=>$dyn_valuetotal,'mail_failed'=>$dyn_valuetotalfailed,'cron_end'=>$time,'status'=>'1','modified_date'=>$datetime))->where('id', '=', $cron_id)->where('status', '=', '2')->execute();
		}
		if(!empty($total_records)){
			if($total_records == $dyn_valuetotal){
				echo "<span style='background:green;font-weight:bolder;font-size:22px;'>All emails sent successfully!!!</span>";
			}else{
				echo "<span style='background:green;font-weight:bolder;font-size:22px;'>Please check with log file!!!</span>";
			}
		}else{
			echo "<span style='background:green;font-weight:bolder;font-size:22px;'>Currently No records found!!!</span>";
		}
		die();
	}
	public function action_sendsharedemailnotify(){
		date_default_timezone_set('Australia/Sydney');
		$title			= "Reminder - Shared Workout for Today";
		$starttime 		= date('H:i:00',strtotime('-15 mins'));
		$endtime 		= date('H:i:00',strtotime('+15 mins'));
		$date 			= date('Y-m-d');
		$enddate 		= date('Y-m-d',strtotime('+6 days'));
		$currenttime 	= date('H:i:00 A');
		$weekstartson 	= array('1'=>'Mon','2'=>'Tue','3'=>'Wed','4'=>'Thu','5'=>'Fri','6'=>'Sat','7'=>'Sun');
		$start 	= 0; $limit	= 2;
		$datetimecur 	= new DateTime();
		$currenttdtweek = $datetimecur->format('D');
		$startingDay 	= array_search($currenttdtweek, $weekstartson);
		$weekuseriddata	= array();
		$dyn_valuetotal = $dyninner_valuetotal =  $dyn_valuetotalfailed = 0;
		$datetime		= date('Y-m-d H:i:s');
		$time			= date('H:i:s');
		/** cron_job table **/
		$cron_result = DB::insert('cron_job', array('name', 'cron_start', 'created_date', 'modified_date'))->values(array(addslashes($title), $time, $datetime, $datetime))->execute();
		$cron_id = (isset($cron_result[0]) ? $cron_result[0] : '');
		/** cron_job table **/
		$myCacheFile = fopen(DOCROOT."assets/cache/".date("Y-m-d")."-TodayAssignmentReminder.log", "a");
		do{
			$sql = "SELECT SQL_CALC_FOUND_ROWS wka.*,wc.*,ud.*,usite.*,ust.*,ea.* FROM email_automation as ea JOIN wkout_assign_gendata AS wka ON (wka.wkout_assign_id=ea.wkout_assign_id AND wka.status_id ='1') JOIN sites AS usite ON (ea.site_id = usite.id AND ea.site_id = wka.site_id) JOIN users AS ud ON (ea.user_id = ud.id) JOIN user_sites as us ON (us.user_id = ud.id AND us.status='1' AND us.site_id = wka.site_id) LEFT JOIN user_settings AS ust ON (ea.user_id = ust.user_id AND ust.site_id = wka.site_id) LEFT JOIN wkout_color AS wc ON wka.wkout_color=wc.color_id LEFT JOIN wkout_focus_grp AS wf ON wka.wkout_focus=wf.focus_grp_id where ea.is_active = '0' AND ea.is_delete = '0' AND ea.is_mail_send = '0' AND wka.is_email = '0' AND usite.is_active = 1 AND wka.marked_status = '0' AND usite.is_deleted = 0 AND ea.triggerby_time BETWEEN '".$starttime."' AND '".$endtime."' AND ust.Assignment_upcoming_reminder ='1' AND ea.triggerby_date = '".$date."' order by ea.triggerby_date asc ,ea.triggerby_time asc limit ".$start.",".$limit."";
			$query 	= DB::query(Database::SELECT,$sql);
			$found_records = 0;
			$main_arraycronssch 	= $query->execute()->as_array();
			if(!isset($total_records)){
				$sql1 = 'SELECT FOUND_ROWS() as count;';
				$total_recordsarray 	= DB::query(Database::SELECT,$sql1)->execute()->as_array();
				if(isset($total_recordsarray[0]['count']))
					$total_records = $total_recordsarray[0]['count'];
				else
					$total_records = 0;
				if(!empty($cron_id)){
					$datetime = date('Y-m-d H:i:s');
					DB::update('cron_job')->set(array('fetched_record'=>$total_records,'modified_date'=>$datetime))->where('id', '=', $cron_id)->where('status', '=', '2')->execute();
				}
			}
			if(is_array($main_arraycronssch) && count($main_arraycronssch)>0){
				foreach($main_arraycronssch as $keys => $value_val){
					$found_records++;
					$to_time = strtotime('now');
					$from_time = strtotime($value_val['triggerby_time']);
					if($value_val['Assignment_upcoming_reminder'] == 1){
						if(round(abs($to_time - $from_time) / 60,2) < 3 && round(abs($to_time - $from_time) / 60,2) > 0 ){
							$indata ='';
							$indata['fusername'] = $value_val['user_fname'];
							$indata['lusername'] = $value_val['user_lname'];
							$indata['useremail'] = $value_val['user_email'];
							$indata['userid'] = $value_val['user_id'];
							$encryptedmessage = Helper_Common::encryptPassword($value_val['user_email'].'####'.$value_val['security_code'].'####assignedplan');
							$indata['urlactive'] = '<a href="'.URL::site(NULL, 'http').$value_val['slug']."/index/autoredirect/".$value_val['wkout_assign_id']."/".$encryptedmessage.'" target="_blank" title="'.$value_val['wkout_title'].'" style="color: #1b9af7;">'.$value_val['wkout_title'].'</a>';
							$indata['decryurlactive'] =Helper_Common::decryptPassword($encryptedmessage); 
							$indata['site_id']		= $value_val['site_id'];
							$sendflag = $this->cropemailschedule($indata,'today',$title);
							if($sendflag != 1){
								Helper_Common::updateEmailAutomation($value_val['wkout_assign_id'],'notsend');
								$message = "User id:".$indata['userid']."==> Workout Assign id: ".$value_val['wkout_assign_id']."==>Email id: ".$indata['useremail']." - Email not send\n";
								fwrite($myCacheFile, $message);
								$dyn_valuetotalfailed++;
							}else{
								Helper_Common::updateEmailAutomation($value_val['wkout_assign_id']);
								$dyn_valuetotal++;
							}
						}
					}else if($value_val['Assignment_upcoming_reminder'] == 2 && false){
						$weekuseriddata[$value_val['user_id']][$dyninner_valuetotal]['fusername'] = $value_val['user_fname'];
						$weekuseriddata[$value_val['user_id']][$dyninner_valuetotal]['lusername'] = $value_val['user_lname'];
						$weekuseriddata[$value_val['user_id']][$dyninner_valuetotal]['useremail'] = $value_val['user_email'];
						$weekuseriddata[$value_val['user_id']][$dyninner_valuetotal]['userid'] = $value_val['user_id'];
						$encryptedmessage = Helper_Common::encryptPassword($value_val['user_email'].'####'.$value_val['security_code'].'####assignedplan');
						$weekuseriddata[$value_val['user_id']][$dyninner_valuetotal]['urlactive'] = '<a href="'.URL::site(NULL, 'http').$value_val['slug']."/index/autoredirect/".$value_val['wkout_assign_id']."/".$encryptedmessage.'" target="_blank" title="'.$value_val['wkout_title'].' '.Helper_Common::change_default_date_static($value_val['assigned_date'],$value_val['timezone'],$value_val['date_format']).'" style="color: #1b9af7;">'.$value_val['wkout_title'].' '.Helper_Common::change_default_date_static($value_val['assigned_date'],$value_val['timezone'],$value_val['date_format']).'</a>';
						$weekuseriddata[$value_val['user_id']][$dyninner_valuetotal]['decryurlactive'] =Helper_Common::decryptPassword($encryptedmessage); 
						$weekuseriddata[$value_val['user_id']][$dyninner_valuetotal]['site_id']		= $value_val['site_id'];
						$weekuseriddata[$value_val['user_id']][$dyninner_valuetotal]['wk_assign_id']	= $value_val['wkout_assign_id'];
						$weekuseriddata[$value_val['user_id']][$dyninner_valuetotal]['wk_assign_date']	= $value_val['assigned_date'];
						$dyninner_valuetotal++;
					}
				}
			}
			$start = $start + $limit;
		}while($found_records == $limit && $total_records > $start);
		$array_checkuserid = array();
		$workoutid_assign = '';
		if(is_array($weekuseriddata) && count($weekuseriddata) > 0){
			foreach($weekuseriddata as $key_wd => $value_wd){
				$userid_reset = $activelinkcombine = $decrylinkcombine = '';
				foreach($value_wd as $key_wd1 => $value_wd1){
					if(!in_array($value_wd1['userid'],$array_checkuserid)){
						$userid_reset++;
						$activelinkcombine .= $value_wd1['urlactive']."<br/>";
						$decrylinkcombine .= $value_wd1['decryurlactive']."<br/>";
						$workoutid_assign .= $value_wd1['wk_assign_id'].",";
						if($userid_reset == count($value_wd)){
							$indata ='';
							$indata['fusername'] = $value_wd1['fusername'];
							$indata['lusername'] = $value_wd1['lusername'];
							$indata['useremail'] = $value_wd1['useremail'];
							$indata['userid'] 	 = $value_wd1['userid'];
							$indata['urlactive'] = $activelinkcombine;
							$indata['decryurlactive'] = $decrylinkcombine; 
							$indata['site_id']	 = $value_wd1['site_id'];
							$sendflag = $this->cropemailschedule($indata , 'week',$title);
							if($sendflag != 1){
								Helper_Common::updateEmailAutomation($value_wd1['wk_assign_id'],'notsend');
								$message = "User id:".$value_wd1['userid']."==>Workout Assign id: ".substr($workoutid_assign,0,-1)."==>Email id: ".$value_wd1['useremail']." - Email not send\n";
								fwrite($myCacheFile, $message);
								$dyn_valuetotalfailed++;
							}else{
								Helper_Common::updateEmailAutomation($value_wd1['wk_assign_id']);
								$dyn_valuetotal++;
							}
							array_push($array_checkuserid,$value_wd1['userid']);
						}
					}
				}
			}
		}
		fclose($myCacheFile);
		$time			= date('H:i:s');
		$datetime		= date('Y-m-d H:i:s');
		if(!empty($cron_id)){
			DB::update('cron_job')->set(array('fetched_record'=>$total_records,'mail_send'=>$dyn_valuetotal,'mail_failed'=>$dyn_valuetotalfailed,'cron_end'=>$time,'status'=>'1','modified_date'=>$datetime))->where('id', '=', $cron_id)->where('status', '=', '2')->execute();
		}
		if(!empty($total_records)){
			if($total_records == $dyn_valuetotal){
				echo "<span style='background:green;font-weight:bolder;font-size:22px;'>All emails sent successfully!!!</span>";
			}else{
				echo "<span style='background:green;font-weight:bolder;font-size:22px;'>Please check with log file!!!</span>";
			}
		}else{
			echo "<span style='background:green;font-weight:bolder;font-size:22px;'>Currently No records found!!!</span>";
		}
		die();
	}
	public function cropemailschedule($datasender, $type = 'today', $tempName){
		$smtpmodel 		= ORM::factory('admin_smtp');
		$templateArray 	= $smtpmodel->getSendingMailTemplate(array('type_name' => $tempName));
		$templateArray['body'] = str_replace(
									array(
										'[FirstName]',
										'[assignment_calendar]',
										'[assignment_type]'
									), 
									array(
										ucfirst(strtolower($datasender['fusername'])), 
										$datasender['urlactive'],
										$type 
									),
									$templateArray['body']
								);
		$messageArray = array(
							'subject'	=> $templateArray['subject'],
							'from' 	=> (!empty($templateArray['smtp_replyto']) ? $templateArray['smtp_replyto'] : 'info@myworkouts.com'),
							'fromname'=> (!empty($templateArray['smtp_from']) ? $templateArray['smtp_from'] : 'My workouts'),
							'to'		=> 'testingwebdev2016@gmail.com',
							'replyto'	=> (!empty($templateArray['smtp_replyto']) ? $templateArray['smtp_replyto'] : 'info@myworkouts.com'),
							'toname'	=> ucfirst(strtolower($datasender['fusername'])).' '.ucfirst(strtolower($datasender['lusername'])),
							'body' 	=> ORM::factory('admin_smtp')->merge_keywords($templateArray['body'],$datasender['site_id']),
							'type'	=> 'text/html'
						);
		if(is_array($templateArray) && !empty($templateArray) && !empty($templateArray['smtp_id'])){
			$hostAddress = 	explode("://",$templateArray['smtp_host']);
			$emailMailer = 	Email::dynamicMailer(
								'smtp',array(
								'hostname'   => trim($hostAddress['1']), 
								'port' 	   => $templateArray['smtp_port'], 
								'username'   => $templateArray['smtp_user'],   
								'password'   => Helper_Common::decryptPassword($templateArray['smtp_pass']),
								'encryption' => trim($hostAddress['0']))
						    );
		}
		return Email::sendBysmtp($emailMailer,$messageArray);
	}
	public function action_updateassignautomation(){
		date_default_timezone_set('Australia/Sydney');
		$date 			= date('Y-m-d');
		$workoutModel   = ORM::factory('workouts');
		$sql = "SELECT wka.*,wc.*,ud.*,usite.*,ust.*,ea.id as email_auto_id FROM wkout_assign_gendata AS wka JOIN sites AS usite ON (usite.id = wka.site_id) JOIN users AS ud ON (wka.user_id = ud.id) JOIN user_sites as us ON (us.user_id = ud.id AND us.status='1' AND us.site_id = wka.site_id) JOIN roles AS ua ON wka.access_id = ua.id LEFT JOIN user_settings AS ust ON (wka.user_id = ust.user_id AND ust.site_id = usite.id) LEFT JOIN wkout_color AS wc ON wka.wkout_color=wc.color_id LEFT JOIN wkout_focus_grp AS wf ON wka.wkout_focus=wf.focus_grp_id LEFT JOIN email_automation as ea ON ea.wkout_assign_id = wka.wkout_assign_id WHERE wka.is_email = '0' AND usite.is_active = 1 AND wka.marked_status = '0' AND usite.is_deleted = 0 AND wka.assigned_date >= '".$date."' AND wka.status_id ='1' order by wka.assigned_date asc";
		echo $sql."===<br>";
		$query 	= DB::query(Database::SELECT,$sql);
		$found_records = 0;
		$main_arraycronssch 	= $query->execute()->as_array();
		echo "<pre>";print_r($main_arraycronssch);echo "</pre>";
		if(is_array($main_arraycronssch) && count($main_arraycronssch)>0){
			foreach($main_arraycronssch as $keys => $value_val){
				if(empty($value_val['email_auto_id'])){
					$emailNotifyArray['wkout_assign_id'] = $value_val['wkout_assign_id'];
					$emailNotifyArray['triggerby_date']  = Helper_Common::get_default_date($value_val['assigned_date']);
					$emailNotifyArray['triggerby_time']  = Helper_Common::getUserEmailTime($value_val['user_id'],$value_val['site_id']);
					$workoutModel->insertEmailNotify($emailNotifyArray);
				}
			}
		}
	}
} 


