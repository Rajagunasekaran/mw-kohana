<?php
defined('SYSPATH') or die('No direct access allowed.');
class Model_Userprofile extends Model_Auth_User
{
   protected $_table_name = 'users';
   protected $_primary_key = 'id';
   public function get_user_ratings($userid)
   {
      $sql   = "SELECT round(sum(rating)/count(*)) as rate FROM site_trainer_rating WHERE  userid = '" . $userid . "'"; //die;
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list;
   }
   public function check_rate($userid, $ratedby)
   {
      $sql   = "SELECT * FROM site_trainer_rating WHERE  userid = '" . $userid . "' and ratedby='" . $ratedby . "'";
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list;
   }
   public function addrating($array)
   {
      $array["ratedon"] = Helper_Common::get_default_datetime();
      $results          = DB::insert('site_trainer_rating', array_keys($array))->values(array_values($array))->execute();
      return $results[0];
   }
   public function get_trainer_profile($authid, $siteid, $roleid)
   {
      /*
      $sql = "select
					users.id as userid, users.*, user_status.status as userstatus, site_user_profile.firstname, site_user_profile.lastname,
					 site_user_profile.business, site_user_profile.profile_img, site_user_profile.qualifications, site_user_profile.achievements,site_user_profile.specialties,site_user_profile.otherspecialties,site_user_profile.background
				from user_sites
				join users on users.id = user_sites.user_id
				join user_status on user_status.id = user_sites.status
				join roles_users on roles_users.user_id = users.id
				join roles on roles_users.role_id = roles.id
				left join site_user_profile on site_user_profile.userid = users.id
				where
					roles_users.role_id in ($roleid)
					and user_sites.status =1
               and user_sites.site_id IN ($siteid)
					
				";
      if ($authid) {
         $sql .= " and users.id = " . $authid;
      }
      $sql .= " group by users.id";
      */
      $sql   = "select
                     users.id as userid, users.*, user_status.status as userstatus, spf.firstname, spf.lastname,
                     spf.business, spf.profile_img, spf.qualifications, spf.achievements,spf.specialties,spf.otherspecialties,spf.background
               from
                  user_sites join users on users.id = user_sites.user_id
                  join user_status on user_status.id =  user_sites.status
                  join roles_users on roles_users.user_id = users.id
                  join roles  on roles_users.role_id = roles.id
                  ";
      $sql .= " join site_user_profile as spf on spf.userid = user_sites.user_id";
      $sql .= " where
                   user_sites.status =1";                  //roles_users.role_id in (". $roleid . ") and
      if($siteid !=''){
			$sql .= " and user_sites.site_id IN (" . $siteid . ")";
		}
      if ($authid) {
         $sql .= " and users.id in(" . $authid.")";
      }
      //echo "<pre>";echo $sql;echo "</pre>";		 die;
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list;
   }
   
   
    public function get_site_trainer_with_profile($siteid, $roleid ,$userId)
   {
      $sql   = "select
                     users.id as userid, users.*, user_status.status as userstatus, spf.firstname, spf.lastname,
                     spf.business, spf.profile_img, spf.qualifications, spf.achievements,spf.specialties,spf.otherspecialties,spf.background,cr.chat_req_id
               from
                  user_sites join users on users.id = user_sites.user_id
                  join user_status on user_status.id =  user_sites.status
                  join roles_users on roles_users.user_id = users.id
                  join roles on roles_users.role_id = roles.id
				  left join chat_request as cr on (cr.chat_req_to = users.id AND cr.chat_req_userid = '".$userId."' AND cr.chat_req_status=1)
                  ";
      $sql .= " join site_user_profile as spf on spf.userid = user_sites.user_id";
      $sql .= " where
                  roles_users.role_id in (". $roleid . ") and user_sites.status =1";
      if($siteid !=''){
			$sql .= " and user_sites.site_id IN (" . $siteid . ")";
		}
      $sql .= " group by users.id order by  spf.firstname asc";
		//echo $sql;
      $query = DB::query(Database::SELECT, $sql);
      $list  = $query->execute()->as_array();
      return $list;
   }
   
   public function get_site_trainer_without_profile($siteid, $roleid, $uids)
   {
      
      $sql   = "select
                     users.id as userid, users.*, user_status.status as userstatus, spf.firstname, spf.lastname,
                     spf.business, spf.profile_img, spf.qualifications, spf.achievements,spf.specialties,spf.otherspecialties,spf.background
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
   /*for user profile - by G.R*/
   public function profile_report_totalcnt_by_type($type, $which, $useractivedate, $userid, $is_front){
      if(empty($type)){
         return;
      }
      $currentdate = Helper_Common::get_default_datetime();
      $site_ids = Helper_Common::getAllSiteId();
      $siteid = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
      $siteids = ((!$is_front && $siteid == '1') || $is_front ? $site_ids : $siteid);
      $currentdatetime = (!empty($which) && ($which == 'last' || $which == 'past')) ? date('Y-m-d 23:59:59', strtotime('last day of previous month')) : $currentdate;
      $sql = '';
      if($type == 'login'){
         $sql = "SELECT COUNT(*) AS totalcnt FROM activity_feed af WHERE af.feed_type = 10 AND af.action_type = 12 AND af.user = ".$userid." AND af.site_id IN (".$siteids.") AND af.created_date BETWEEN '".$useractivedate."' AND '".$currentdatetime."'";
      }else if($type == 'workout-assign'){
         $sql = "SELECT COUNT(DISTINCT wag.wkout_assign_id) AS totalcnt
            FROM wkout_assign_gendata AS wag 
            JOIN wkout_assign_goal_gendata AS wagg ON wag.wkout_assign_id = wagg.wkout_assign_id 
            JOIN wkout_assign_goal_vars AS wagv ON wagg.goal_id = wagv.goal_id 
            JOIN unit_gendata AS ugd  ON ugd.unit_id = wagg.goal_unit_id 
            JOIN sites AS s ON s.id = wag.site_id 
            WHERE wag.user_id = ".$userid." AND wag.status_id = 1 AND wagg.status_id = 1 AND wag.site_id IN (".$siteids.")
            AND wag.assigned_date BETWEEN '".$useractivedate."' AND '".$currentdatetime."' AND s.is_active = '1' AND is_deleted='0'
            ORDER BY wag.assigned_date ASC";
      }else if($type == 'workout-log'){
         $sql = "SELECT COUNT(DISTINCT wlg.wkout_log_id) AS totalcnt
            FROM wkout_log_gendata AS wlg 
            JOIN wkout_log_goal_gendata AS wlgg ON wlg.wkout_log_id = wlgg.wkout_log_id 
            JOIN wkout_log_goal_vars AS wlgv ON wlgg.goal_id = wlgv.goal_id 
            JOIN unit_gendata AS ugd  ON ugd.unit_id = wlgg.goal_unit_id 
            JOIN sites AS s ON s.id = wlg.site_id 
            WHERE wlg.user_id = ".$userid." AND wlg.status_id = 1 AND wlg.wkout_status = 1 AND wlgg.status_id = 1 AND wlg.site_id IN (".$siteids.")
            AND wlg.assigned_date BETWEEN '".$useractivedate."' AND '".$currentdatetime."' AND s.is_active = '1' AND is_deleted='0'
            ORDER BY wlg.assigned_date ASC";
      }
      // echo $sql;
      if(!empty($sql)){
         $totalcount = DB::query(Database::SELECT,$sql)->execute()->as_array();
         return isset($totalcount[0]) ? $totalcount[0] : array('totalcnt'=>0);
      }
   }
   public function profile_report_cnt_by_period($type, $for, $which, $userid, $is_front){
      if(empty($type) || empty($which) || empty($for)){
         return;
      }
      $currentdate = Helper_Common::get_default_datetime();
      $site_ids = Helper_Common::getAllSiteId();
      $siteid = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
      $siteids = ((!$is_front && $siteid == '1') || $is_front ? $site_ids : $siteid);
      $weekstartson = (Session::instance()->get('user_week_starts') ? Session::instance()->get('user_week_starts') : '1');
      $weekstart = Helper_Common::get_user_week_start_date($weekstartson);
      if($for == 'week'){
         $whichweekstart = (!empty($which) && ($which == 'last' || $which == 'past')) ? date('Y-m-d 00:00:00', strtotime($weekstart. '-7 day')) : $weekstart;
         $whichweekend = (!empty($which) && ($which == 'last' || $which == 'past')) ? date('Y-m-d 23:59:59', strtotime($weekstart. '-1 day')) : $currentdate;
      }else{
         $whichmonthstart = (!empty($which) && ($which == 'last' || $which == 'past')) ? date('Y-m-d 00:00:00', strtotime("first day of previous month")) : date('Y-m-d 00:00:00',strtotime("first day of this month"));
         $whichmonthend = (!empty($which) && ($which == 'last' || $which == 'past')) ? date('Y-m-d 23:59:59', strtotime("last day of previous month")) : $currentdate;
      }
      $sql = '';
      if($type == 'login'){
         if($for == 'week'){
            $sql = "SELECT COUNT(*) AS weeklycnt FROM activity_feed af WHERE af.feed_type = 10 AND af.action_type = 12 AND af.user = ".$userid." AND af.site_id IN (".$siteids.") AND af.created_date BETWEEN '".$whichweekstart."' AND '".$whichweekend."'";
         }elseif($for == 'month'){
            $sql = "SELECT COUNT(*) AS monthlycnt FROM activity_feed af WHERE af.feed_type = 10 AND af.action_type = 12 AND af.user = ".$userid." AND af.site_id IN (".$siteids.") AND af.created_date BETWEEN '".$whichmonthstart."' AND '".$whichmonthend."'";
         }
      }elseif($type == 'workout-assign'){
         $sql = "SELECT COUNT(DISTINCT wag.wkout_assign_id) AS weeklycnt
            FROM wkout_assign_gendata AS wag 
            JOIN wkout_assign_goal_gendata AS wagg ON wag.wkout_assign_id = wagg.wkout_assign_id 
            JOIN wkout_assign_goal_vars AS wagv ON wagg.goal_id = wagv.goal_id 
            JOIN unit_gendata AS ugd  ON ugd.unit_id = wagg.goal_unit_id 
            JOIN sites AS s ON s.id = wag.site_id 
            WHERE wag.user_id = ".$userid." AND wag.status_id = 1 AND wagg.status_id = 1 AND wag.site_id IN (".$siteids.")
            AND wag.assigned_date BETWEEN '".$whichweekstart."' AND '".$whichweekend."' AND s.is_active = '1' AND is_deleted='0'
            ORDER BY wag.assigned_date ASC";
      }elseif($type == 'workout-log'){
         $sql = "SELECT COUNT(DISTINCT wlg.wkout_log_id) as weeklycnt
            FROM wkout_log_gendata AS wlg 
            JOIN wkout_log_goal_gendata AS wlgg ON wlg.wkout_log_id = wlgg.wkout_log_id 
            JOIN wkout_log_goal_vars AS wlgv ON wlgg.goal_id = wlgv.goal_id 
            JOIN unit_gendata AS ugd  ON ugd.unit_id = wlgg.goal_unit_id 
            JOIN sites AS s ON s.id = wlg.site_id 
            WHERE wlg.user_id = ".$userid." AND wlg.status_id = 1 AND wlg.wkout_status = 1 AND wlgg.status_id = 1 AND wlg.site_id IN (".$siteids.")
            AND wlg.assigned_date BETWEEN '".$whichweekstart."' AND '".$whichweekend."' AND s.is_active = '1' AND is_deleted='0'
            ORDER BY wlg.assigned_date ASC";
      }
      // echo $sql;
      if(!empty($sql)){
         $reportcount = DB::query(Database::SELECT,$sql)->execute()->as_array();
         return isset($reportcount[0]) ? $reportcount[0] : array('weeklycnt'=>0);
      }
   }
   public function get_cardio_report_chart($post){
      $is_front  = (isset($post['is_front']) && $post['is_front'] ? true : false);
      $user_id = $post['userid'];
      $site_ids = Helper_Common::getAllSiteId();
      $siteid = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
      $siteids = ((!$is_front && $siteid == '1') || $is_front ? $site_ids : $siteid);
      $currentdate = Helper_Common::get_default_date();
      $past2weeksstart = date('Y-m-d', strtotime($currentdate. '-14 day'));
      $past2weeksend = $currentdate;
      if(!isset($post['fdate']) || empty($post['fdate'])){
         $post['fdate'] = $past2weeksstart;
      }
      if(!isset($post['tdate']) || empty($post['tdate'])){
         $post['tdate'] = $past2weeksend;
      }
      $sql = "SELECT wlgg.goal_id, wlgg.goal_unit_id, wlgg.goal_title, wlg.assigned_date, CONCAT(wlgv.goal_time_hh, ':', wlgv.goal_time_mm, ':', wlgv.goal_time_ss) AS goal_time, wlgv.goal_dist, wlgv.goal_rate, sd.dist_title, sr.rate_title, s.id AS siteid
         FROM wkout_log_gendata AS wlg 
         JOIN wkout_log_goal_gendata AS wlgg ON wlg.wkout_log_id = wlgg.wkout_log_id 
         JOIN wkout_log_goal_vars AS wlgv ON wlgg.goal_id = wlgv.goal_id 
         JOIN unit_gendata AS ugd  ON ugd.unit_id = wlgg.goal_unit_id
         JOIN sites AS s ON s.id = wlg.site_id
         LEFT JOIN set_dist AS sd ON sd.dist_id = wlgv.goal_dist_id
         LEFT JOIN set_rate AS sr ON sr.rate_id = wlgv.goal_rate_id
         WHERE wlg.user_id = ".$user_id." AND ugd.type_id = 1 AND wlg.status_id = 1 AND wlgg.set_status = 1 AND wlgg.status_id = 1 AND wlg.site_id IN (".$siteids.")
         AND wlg.assigned_date BETWEEN '".$post['fdate']."' AND '".$post['tdate']."' AND s.is_active = '1' AND is_deleted='0'
         ORDER BY wlg.assigned_date ASC";
      // echo $sql;
      $query = DB::query(Database::SELECT, $sql);
      $list = $query->execute()->as_array();
      return $list;
   }
   public function get_cardio_report_chart_vars($post){
      $is_front  = (isset($post['is_front']) && $post['is_front'] ? true : false);
      $user_id = $post['userid'];
      $site_ids = Helper_Common::getAllSiteId();
      $siteid = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
      $siteids = ((!$is_front && $siteid == '1') || $is_front ? $site_ids : $siteid);
      $sql = "SELECT wlgg.goal_id, wlgg.goal_unit_id, wlgg.goal_title, wlg.assigned_date, CONCAT(wlgv.goal_time_hh, ':', wlgv.goal_time_mm, ':', wlgv.goal_time_ss) AS goal_time, wlgv.goal_dist, wlgv.goal_rate, sd.dist_title, sr.rate_title, s.id AS siteid
         FROM wkout_log_gendata AS wlg 
         JOIN wkout_log_goal_gendata AS wlgg ON wlg.wkout_log_id = wlgg.wkout_log_id 
         JOIN wkout_log_goal_vars AS wlgv ON wlgg.goal_id = wlgv.goal_id 
         JOIN unit_gendata AS ugd  ON ugd.unit_id = wlgg.goal_unit_id
         JOIN sites AS s ON s.id = wlg.site_id
         LEFT JOIN set_dist AS sd ON sd.dist_id = wlgv.goal_dist_id
         LEFT JOIN set_rate AS sr ON sr.rate_id = wlgv.goal_rate_id
         WHERE wlg.user_id = ".$user_id." AND ugd.type_id = 1 AND wlg.status_id = 1 AND wlgg.set_status = 1 AND wlgg.status_id = 1 AND wlg.site_id IN (".$siteids.")
         AND wlg.assigned_date = '".$post['cardiodate']."' AND s.is_active = '1' AND is_deleted='0'
         ORDER BY wlg.assigned_date ASC";
      // echo $sql;
      $query = DB::query(Database::SELECT, $sql);
      $list = $query->execute()->as_array();
      return $list;
   }
   public function get_cardio_frequent($post){
      $is_front  = (isset($post['is_front']) && $post['is_front'] ? true : false);
      $user_id = $post['userid'];
      $site_ids = Helper_Common::getAllSiteId();
      $siteid = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
      $siteids = ((!$is_front && $siteid == '1') || $is_front ? $site_ids : $siteid);
      $currentdate = Helper_Common::get_default_date();
      $past2weeksstart = date('Y-m-d', strtotime($currentdate. '-14 day'));
      $past2weeksend = $currentdate;
      if(!isset($post['fdate']) || empty($post['fdate'])){
         $post['fdate'] = $past2weeksstart;
      }
      if(!isset($post['tdate']) || empty($post['tdate'])){
         $post['tdate'] = $past2weeksend;
      }
      $sql = "SELECT COUNT(wlgg.goal_id) AS freq_count, GROUP_CONCAT( wlgg.goal_id ) AS freq_id, wlgg.goal_unit_id, wlgg.goal_title, img.img_id, img.img_url
         FROM wkout_log_gendata AS wlg 
         JOIN wkout_log_goal_gendata AS wlgg ON wlg.wkout_log_id = wlgg.wkout_log_id 
         JOIN wkout_log_goal_vars AS wlgv ON wlgg.goal_id = wlgv.goal_id 
         JOIN unit_gendata AS ugd  ON ugd.unit_id = wlgg.goal_unit_id 
         JOIN sites AS s ON s.id = wlg.site_id 
         JOIN img AS img ON img.img_id = ugd.feat_img 
         WHERE wlg.user_id = ".$user_id." AND ugd.type_id = 1 AND wlg.status_id = 1 AND wlgg.set_status = 1 AND wlgg.status_id = 1 AND wlg.site_id IN (".$siteids.") 
         AND wlg.assigned_date BETWEEN '".$post['fdate']."' AND '".$post['tdate']."' AND s.is_active = '1' AND is_deleted='0'
         GROUP BY wlgg.goal_unit_id ORDER BY freq_count DESC LIMIT 3";
      // echo $sql;
      $query = DB::query(Database::SELECT, $sql);
      $list = $query->execute()->as_array();
      return $list;
   }
   public function get_cardio_frequent_average($unitid, $which, $useractivedate, $user_id, $is_front){
      $site_ids = Helper_Common::getAllSiteId();
      $siteid = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
      $siteids = ((!$is_front && $siteid == '1') || $is_front ? $site_ids : $siteid);
      $currentdate = Helper_Common::get_default_date();
      $fdate = $useractivedate;
      $tdate = (!empty($which) && ($which == 'last' || $which == 'past')) ? date('Y-m-d', strtotime('last day of previous month')) : $currentdate;
      $sql = "SELECT COUNT(wlgg.goal_id) AS total_cnt, wlgg.goal_unit_id, wlgg.goal_title
         FROM wkout_log_gendata AS wlg 
         JOIN wkout_log_goal_gendata AS wlgg ON wlg.wkout_log_id = wlgg.wkout_log_id 
         JOIN wkout_log_goal_vars AS wlgv ON wlgg.goal_id = wlgv.goal_id 
         JOIN unit_gendata AS ugd  ON ugd.unit_id = wlgg.goal_unit_id 
         JOIN sites AS s ON s.id = wlg.site_id 
         WHERE wlg.user_id = ".$user_id." AND ugd.type_id = 1 AND wlg.status_id = 1 AND wlgg.set_status = 1 AND wlgg.status_id = 1 AND wlg.site_id IN (".$siteids.") 
         AND wlgg.goal_unit_id = ".$unitid." AND wlg.assigned_date BETWEEN '".$fdate."' AND '".$tdate."' AND s.is_active = '1' AND is_deleted='0'";
      // echo $sql;
      $query = DB::query(Database::SELECT, $sql);
      $list = $query->execute()->as_array();
      return (isset($list[0]) ? $list[0] : $list);
   }
   public function get_cardio_frequent_report($param, $for, $which, $user_id, $is_front){
      $site_ids = Helper_Common::getAllSiteId();
      $siteid = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
      $siteids = ((!$is_front && $siteid == '1') || $is_front ? $site_ids : $siteid);
      $currentdate = Helper_Common::get_default_date();
      $weekstartson = (Session::instance()->get('user_week_starts') ? Session::instance()->get('user_week_starts') : '1');
      $weekstart = Helper_Common::get_user_week_start_date($weekstartson);
      $queryres = array();
      if(isset($param) && !empty($param)){
         if($for == 'week'){
            $whichperiodstart = (!empty($which) && ($which == 'last' || $which == 'past')) ? date('Y-m-d', strtotime($weekstart. '-7 day')) : $weekstart;
            $whichperiodend = (!empty($which) && ($which == 'last' || $which == 'past')) ? date('Y-m-d', strtotime($weekstart. '-1 day')) : $currentdate;
         }else{
            $whichperiodstart = (!empty($which) && ($which == 'last' || $which == 'past')) ? date('Y-m-d', strtotime("first day of previous month")) : date('Y-m-d', strtotime("first day of this month"));
            $whichperiodend = (!empty($which) && ($which == 'last' || $which == 'past')) ? date('Y-m-d', strtotime("last day of previous month")) : $currentdate;
         }
         $sql = "SELECT wlgg.goal_id, wlgg.goal_unit_id, wlgg.goal_title, wlgv.goal_dist, CONCAT( wlgv.goal_time_hh,  ':', wlgv.goal_time_mm,  ':', wlgv.goal_time_ss ) AS goal_time, wlg.assigned_date, sd.dist_title
            FROM wkout_log_gendata AS wlg 
            JOIN wkout_log_goal_gendata AS wlgg ON wlg.wkout_log_id = wlgg.wkout_log_id 
            JOIN wkout_log_goal_vars AS wlgv ON wlgg.goal_id = wlgv.goal_id 
            JOIN unit_gendata AS ugd  ON ugd.unit_id = wlgg.goal_unit_id 
            JOIN sites AS s ON s.id = wlg.site_id 
            LEFT JOIN set_dist AS sd ON sd.dist_id = wlgv.goal_dist_id
            WHERE wlg.user_id = ".$user_id." AND ugd.type_id = 1 AND wlg.status_id = 1 AND wlgg.set_status = 1 AND wlgg.status_id = 1 AND wlg.site_id IN (".$siteids.") 
            AND wlgg.goal_id IN(".$param.") AND wlg.assigned_date BETWEEN '".$whichperiodstart."' AND '".$whichperiodend."' AND s.is_active = '1' AND is_deleted='0'
            GROUP BY wlgg.goal_id";
         // echo $sql;
         $queryres = DB::query(Database::SELECT, $sql)->execute()->as_array();
      }
      return $queryres;
   }
   public function get_strength_report($param, $for, $which, $user_id, $is_front){
      $site_ids = Helper_Common::getAllSiteId();
      $siteid = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
      $siteids = ((!$is_front && $siteid == '1') || $is_front ? $site_ids : $siteid);
      $currentdate = Helper_Common::get_default_date();
      $weekstartson = (Session::instance()->get('user_week_starts') ? Session::instance()->get('user_week_starts') : '1');
      $weekstart = Helper_Common::get_user_week_start_date($weekstartson);
      if($for == 'week'){
         $whichperiodstart = (!empty($which) && ($which == 'last' || $which == 'past')) ? date('Y-m-d', strtotime($weekstart. '-7 day')) : $weekstart;
         $whichperiodend = (!empty($which) && ($which == 'last' || $which == 'past')) ? date('Y-m-d', strtotime($weekstart. '-1 day')) : $currentdate;
      }else{
         $whichperiodstart = (!empty($which) && ($which == 'last' || $which == 'past')) ? date('Y-m-d', strtotime("first day of previous month")) : date('Y-m-d', strtotime("first day of this month"));
         $whichperiodend = (!empty($which) && ($which == 'last' || $which == 'past')) ? date('Y-m-d', strtotime("last day of previous month")) : $currentdate;
      }
      $sql = "SELECT wlgg.goal_id, wlgv.goal_reps, wlgv.goal_resist, umc.muscle_id, umc.muscle_title, sr.resist_title
         FROM wkout_log_gendata AS wlg 
         JOIN wkout_log_goal_gendata AS wlgg ON wlg.wkout_log_id = wlgg.wkout_log_id 
         JOIN wkout_log_goal_vars AS wlgv ON wlgg.goal_id = wlgv.goal_id 
         JOIN unit_gendata AS ugd  ON ugd.unit_id = wlgg.goal_unit_id 
         JOIN unit_muscle AS umc ON umc.muscle_id = ugd.musprim_id
         JOIN sites AS s ON s.id = wlg.site_id 
         LEFT JOIN set_resist AS sr ON sr.resist_id = wlgv.goal_resist_id 
         WHERE wlg.user_id = ".$user_id." AND ugd.musprim_id = ".$param." AND wlg.status_id = 1 AND wlgg.set_status = 1 AND wlgg.status_id = 1 AND wlg.site_id IN (".$siteids.") 
         AND wlg.assigned_date BETWEEN '".$whichperiodstart."' AND '".$whichperiodend."' AND s.is_active = '1' AND is_deleted='0'
         GROUP BY wlgg.goal_id";
      // echo $sql;
      $queryres = DB::query(Database::SELECT, $sql)->execute()->as_array();
      return $queryres;
   }
   public function get_personal_best($param, $useractivedate, $user_id, $is_front){
      $site_ids = Helper_Common::getAllSiteId();
      $siteid = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
      $siteids = ((!$is_front && $siteid == '1') || $is_front ? $site_ids : $siteid);
      $currentdate = Helper_Common::get_default_date();
      $user_weight = (Session::instance()->get('user_weight') ? Session::instance()->get('user_weight') : '1');
      $sql = "SELECT wlg.wkout_id, wlg.wkout_log_id, wlgg.goal_id, ugd.unit_id, ugd.title, umc.muscle_id, umc.muscle_title, wlgv.goal_resist , sr.resist_title
         FROM wkout_log_gendata AS wlg 
         JOIN wkout_log_goal_gendata AS wlgg ON wlg.wkout_log_id = wlgg.wkout_log_id 
         JOIN wkout_log_goal_vars AS wlgv ON wlgg.goal_id = wlgv.goal_id 
         JOIN unit_gendata AS ugd  ON ugd.unit_id = wlgg.goal_unit_id 
         JOIN unit_muscle AS umc ON umc.muscle_id = ugd.musprim_id
         JOIN sites AS s ON s.id = wlg.site_id 
         LEFT JOIN set_resist AS sr ON sr.resist_id = wlgv.goal_resist_id
         WHERE wlg.user_id = ".$user_id." AND ugd.musprim_id = ".$param." AND wlg.status_id = 1 AND wlgg.set_status = 1 AND wlgg.status_id = 1 AND ugd.status_id = 1 AND wlg.wkout_id != 0 AND wlg.site_id IN (".$siteids.") 
         AND wlg.assigned_date BETWEEN '".$useractivedate."' AND '".$currentdate."' AND wlgv.goal_resist_id = ".$user_weight." AND s.is_active = '1' AND is_deleted='0' 
         GROUP BY wlgv.goal_resist, wlg.assigned_date ORDER BY wlgv.goal_resist DESC, wlg.assigned_date DESC LIMIT 1";
      // echo $sql;
      $queryres = DB::query(Database::SELECT, $sql)->execute()->as_array();
      $presonalbest = (isset($queryres[0]) ? $queryres[0] : $queryres);
      return $presonalbest;
   }
   public function getUserMeasurement($userid){
      $siteid = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
      $sql = "SELECT IF(a.height = 0, (SELECT height FROM user_measurement WHERE height!=0 ORDER BY modified_date DESC LIMIT 1), a.height) AS height, IF(a.weight = 0, (SELECT weight FROM user_measurement WHERE weight!=0 ORDER BY modified_date DESC LIMIT 1), a.weight) AS weight, IF(a.weight = 0, (SELECT weight_unit FROM user_measurement WHERE weight!=0 ORDER BY modified_date DESC LIMIT 1), a.weight_unit) AS weight_unit, a.modified_date FROM `user_measurement` AS a WHERE a.modified_date = (SELECT MAX(b.modified_date) FROM `user_measurement` AS b WHERE b.site_id=".$siteid." AND b.user_id=".$userid." ORDER BY b.modified_date DESC LIMIT 1)";
      $query = DB::query(Database::SELECT,$sql);
      $check = $query->execute()->as_array();
      $res = isset($check[0]) ? $check[0] : $check;
      return $res;
   }
   public function checkUserMeasurement($userid, $type=''){
      $currentdate = Helper_Common::get_default_datetime();
      $siteid = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
      $res = false;
      $which = '';
      if($type == 'week'){
         $weekstartson = (Session::instance()->get('user_week_starts') ? Session::instance()->get('user_week_starts') : '1');
         $weekstart = Helper_Common::get_user_week_start_date($weekstartson);
         $whichweekstart = (!empty($which) && ($which == 'last' || $which == 'past')) ? date('Y-m-d 00:00:00', strtotime($weekstart. '-7 day')) : $weekstart;
         $whichweekend = (!empty($which) && ($which == 'last' || $which == 'past')) ? date('Y-m-d 23:59:59', strtotime($weekstart. '-1 day')) : $currentdate;
          $sql = "SELECT height, weight, weight_unit FROM `user_measurement` WHERE site_id=".$siteid." AND user_id=".$userid." AND modified_date BETWEEN '".$whichweekstart."' AND '".$whichweekend."'";
      }else{
         $currentdatestart = date('Y-m-d 00:00:00', strtotime($currentdate));
         $currentdateend = $currentdate;
         $sql = "SELECT height, weight, weight_unit FROM `user_measurement` WHERE site_id=".$siteid." AND user_id=".$userid." AND modified_date BETWEEN '".$currentdatestart."' AND '".$currentdateend."'";
      }
      $query = DB::query(Database::SELECT,$sql);
      $check = $query->execute()->as_array();
      if(!empty($check) && count($check) > 0){
         $res = true;
      }
      return $res;
   }
   public function checkInitUserMeasurement($userid){
      $siteid = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
      $res = false;
      $sql = "SELECT height, weight, weight_unit, init_measure FROM `user_measurement` WHERE site_id=".$siteid." AND user_id=".$userid." AND init_measure = 1";
      $query = DB::query(Database::SELECT,$sql);
      $check = $query->execute()->as_array();
      if(!empty($check) && count($check) > 0){
         $res = true;
      }
      return $res;
   }
   public function getUserMeasurementForWeek($userid, $which){
      $currentdate = Helper_Common::get_default_datetime();
      $siteid = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
      $weekstartson = (Session::instance()->get('user_week_starts') ? Session::instance()->get('user_week_starts') : '1');
      $weekstart = Helper_Common::get_user_week_start_date($weekstartson);
      $whichweekstart = (!empty($which) && ($which == 'last' || $which == 'past')) ? date('Y-m-d 00:00:00', strtotime($weekstart. '-7 day')) : $weekstart;
      $whichweekend = (!empty($which) && ($which == 'last' || $which == 'past')) ? date('Y-m-d 23:59:59', strtotime($weekstart. '-1 day')) : $currentdate;
      $sql = "SELECT a.height, a.weight, a.weight_unit, a.modified_date FROM `user_measurement` a WHERE a.modified_date = (SELECT max(b.modified_date) FROM `user_measurement` b WHERE b.site_id=".$siteid." AND b.user_id=".$userid." AND b.modified_date BETWEEN '".$whichweekstart."' AND '".$whichweekend."' ORDER BY b.modified_date DESC LIMIT 1)";
      $query = DB::query(Database::SELECT,$sql);
      $check = $query->execute()->as_array();
      $res = isset($check[0]) ? $check[0] : $check;
      return $res;
   }
   public function insertUserMeasurement($column, $param, $userid){
      $currentdate = Helper_Common::get_default_datetime();
      $siteid = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
      $columns = 'user_id, site_id, '.$column.', created_date, modified_date';
      $params = $userid.', '.$siteid.', '.$param.', "'.$currentdate.'", "'.$currentdate.'"';
      $sql = "INSERT INTO user_measurement ($columns) VALUES ($params)";
      $query = DB::query(Database::INSERT,$sql)->execute();
      return true;
   }
   public function updateUserMeasurement($params, $userid){
      $currentdate = Helper_Common::get_default_datetime();
      $currentdatestart = date('Y-m-d 00:00:00', strtotime($currentdate));
      $currentdateend = $currentdate;
      $siteid = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
      $sql = "UPDATE user_measurement SET ".$params.", modified_date = '".$currentdate."' WHERE site_id=".$siteid." AND user_id=".$userid." AND modified_date BETWEEN '".$currentdatestart."' AND '".$currentdateend."'";
      $query = DB::query(Database::UPDATE,$sql)->execute();
      return true;
   }
   public function getUserMeasurementAnswer($user_id){
      $siteid = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
      $sql = "SELECT sqa.id, sqa.sqid, sqa.sqoid, IF(sqa.sqoid=0, sqa.answer, sqo.option) AS answer, sq.answer_field as field_id
      FROM sitequestionanswers AS sqa 
      JOIN sitequestions AS sq ON sqa.sqid=sq.id 
      LEFT JOIN sitequestionoptions AS sqo ON sqa.sqoid=sqo.id 
      WHERE sq.q_type=1 AND sqa.status=0 AND sqa.user_id=".$user_id." AND sqa.site_id=".$siteid." AND sqa.sqid IN (16,17) GROUP BY sqa.sqid";
      $query = DB::query(Database::SELECT,$sql);
      $list = $query->execute()->as_array();
      $returnKeyArray = array('height','weight');
      $returnArray = array();
      if(!empty($list) && count($list)>0){
         foreach($list as $keys => $values){
            $returnArray[$returnKeyArray[$keys]] = $values['answer'];
         }
      }
      return $returnArray;
   }
   public function getUserMeasurementAnswerDetail($user_id, $for){
      $siteid = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
      if($for == 'height'){
         $answerfor = 'sqa.sqid = 16';
      }else if($for == 'weight'){
         $answerfor = 'sqa.sqid = 17';
      }else{
         $answerfor = 'sqa.sqid IN (16,17)';
      }
      $sql = "SELECT sqa.sqid AS question_id, sqa.sqoid AS option_id, sq.answer_field AS field_id, sqa.id AS answer_id, IF(sqa.sqoid=0, sqa.answer, sqo.option) AS answer
      FROM sitequestionanswers AS sqa 
      JOIN sitequestions AS sq ON sqa.sqid=sq.id 
      LEFT JOIN sitequestionoptions AS sqo ON sqa.sqoid=sqo.id 
      WHERE sq.q_type=1 AND sqa.status=0 AND sqa.user_id=".$user_id." AND sqa.site_id=".$siteid." AND ".$answerfor." GROUP BY sqa.sqid";
      $query = DB::query(Database::SELECT,$sql);
      $list = $query->execute()->as_array();
      $returnArray = array();
      if(!empty($list) && count($list)>0){
         foreach($list as $keys => $values){
            $returnArray[$values['question_id']] = $values;
         }
      }
      return $returnArray;
   }
   public function insertUserMeasurementAnswer($user_id, $for, $insertval){
      $siteid = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
      $answerfor = '';
      $resultval = false;
      if($for == 'height'){
         $answerfor = 'id = 16';
      }else if($for == 'weight'){
         $answerfor = 'id = 17';
      }
      if(empty($answerfor)){
         return $resultval;
      }
      $sql = "SELECT id, answer_field AS field_id FROM sitequestions WHERE q_type=1 AND ".$answerfor;
      $query = DB::query(Database::SELECT,$sql);
      $list = $query->execute()->as_array();
      if(!empty($list) && count($list)>0){
         foreach($list as $keys => $values){
            if($values['field_id'] == 1 || $values['field_id'] == 5){
               $updateheight = DB::insert('sitequestionanswers', array('site_id', 'user_id', 'sqid', 'sqoid', 'answer') )->values(array($siteid, $user_id, $values['id'], 0, $updateval))->execute();
               $resultval = true;
            }else{
               $sqlselectopt = "SELECT id FROM sitequestionoptions WHERE status=0 AND sqid=".$values['id']." AND `option`=".$updateval;
               $selectopt = DB::query(Database::SELECT,$sqlselectopt)->execute()->as_array();
               $resultval = false;
               if(isset($selectopt[0])){
                  $updateopt = $selectopt[0];
                  $updateheight =  DB::insert('sitequestionanswers', array('site_id', 'user_id', 'sqid', 'sqoid', 'answer') )->values(array($siteid, $user_id, $values['id'], $updateopt['id'], ''))->execute();
                  $resultval = true;
               }
            }
         }
      }else{
         $resultval = false;
      }
      return $resultval;
   }
   public function updateUserMeasurementAnswer($user_id, $for, $updateval){
      $siteid = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
      $answerfor = '';
      $resultval = false;
      if($for == 'height'){
         $answerfor = 'id = 16';
      }else if($for == 'weight'){
         $answerfor = 'id = 17';
      }
      if(empty($answerfor)){
         return $resultval;
      }
      $sql = "SELECT id, answer_field AS field_id FROM sitequestions WHERE q_type=1 AND ".$answerfor;
      $query = DB::query(Database::SELECT,$sql);
      $list = $query->execute()->as_array();
      if(!empty($list) && count($list)>0){
         foreach($list as $keys => $values){
            if($values['field_id'] == 1 || $values['field_id'] == 5){
               $sqlupdateheight = "UPDATE sitequestionanswers SET answer=".$updateval." WHERE sqoid=0 AND user_id=".$user_id." AND site_id=".$siteid." AND sqid=".$values['id'];
               $updateheight = DB::query(Database::UPDATE,$sqlupdateheight)->execute();
               $resultval = true;
            }else{
               $sqlselectopt = "SELECT id FROM sitequestionoptions WHERE status=0 AND sqid=".$values['id']." AND `option`=".$updateval;
               $selectopt = DB::query(Database::SELECT,$sqlselectopt)->execute()->as_array();
               $resultval = false;
               if(isset($selectopt[0])){
                  $updateopt = $selectopt[0];
                  $sqlupdateheight = "UPDATE sitequestionanswers SET sqoid=".$updateopt['id']." WHERE sqoid!=0 AND user_id=".$user_id." AND site_id=".$siteid." AND sqid=".$values['id'];
                  $updateheight = DB::query(Database::UPDATE,$sqlupdateheight)->execute();
                  $resultval = true;
               }
            }
         }
      }else{
         $resultval = false;
      }
      return $resultval;
   }
   public function getUserDeviceAnswerDetail($user_id, $qid){
      $siteid = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
      $sql = "SELECT sqa.sqid AS q_id, sqa.sqoid AS option_id, sqo.`option` AS name
      FROM sitequestionanswers AS sqa 
      JOIN sitequestions AS sq ON sqa.sqid=sq.id 
      JOIN sitequestionoptions AS sqo ON sqa.sqoid=sqo.id 
      WHERE sqo.status=0 AND sqa.status=0 AND sqa.user_id=".$user_id." AND sqa.site_id=".$siteid." AND sq.id=".$qid." GROUP BY sqa.sqoid ORDER BY sqo.sequence ASC";
      $query = DB::query(Database::SELECT,$sql);
      $returnArray = $query->execute()->as_array();
      return $returnArray;
   }
   public function checkUserDeviceAnswer($user_id, $qid, $sqoid){
      $siteid = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
      $res = false;
      $sql = "SELECT sqa.* FROM sitequestionanswers AS sqa 
      JOIN sitequestions AS sq ON sqa.sqid=sq.id 
      JOIN sitequestionoptions AS sqo ON sqa.sqoid=sqo.id 
      WHERE sqo.status=0 AND sqa.user_id=".$user_id." AND sqa.site_id=".$siteid." AND sqa.sqid=".$qid." AND sqa.sqoid=".$sqoid." GROUP BY sqa.sqoid";
      $query = DB::query(Database::SELECT,$sql);
      $check = $query->execute()->as_array();
      if(!empty($check) && count($check) > 0){
         $res = true;
      }
      return $res;
   }
   public function insertUserDevice($user_id, $qid, $sqoid){
      $siteid = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
      $resultval = false;
      if(empty($qid) || empty($sqoid)){
         return $resultval;
      }
      $updateheight = DB::insert('sitequestionanswers', array('site_id', 'user_id', 'sqid', 'sqoid', 'answer') )->values(array($siteid, $user_id, $qid, $sqoid, ''))->execute();
      $resultval = true;
      return $resultval;
   }
   public function updateUserDevice($user_id, $qid, $sqoid){
      $siteid = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
      $resultval = false;
      if(empty($qid) || empty($sqoid)){
         return $resultval;
      }
      $sqlupdateheight = "UPDATE sitequestionanswers SET status=0 WHERE sqoid=".$sqoid." AND user_id=".$user_id." AND site_id=".$siteid." AND sqid=".$qid;
      $updateheight = DB::query(Database::UPDATE,$sqlupdateheight)->execute();
      $resultval = true;
      return $resultval;
   }
   public function deleteUserDevice($user_id, $qid, $sqoid){
      if(empty($qid) || empty($sqoid)){
         return $resultval;
      }
      $siteid = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
      $resultval = false;
      if(!empty($sqoid)){
         $sqoid = implode(',', $sqoid);
         $sqlupdateheight = "UPDATE sitequestionanswers SET status=1 WHERE status=0 AND sqoid NOT IN(".$sqoid.") AND user_id=".$user_id." AND site_id=".$siteid." AND sqid=".$qid;
         $updateheight = DB::query(Database::UPDATE,$sqlupdateheight)->execute();
         $resultval = true;
      }
      return $resultval;
   }
}