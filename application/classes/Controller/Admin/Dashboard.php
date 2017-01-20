<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Admin_Dashboard extends Controller_Admin_Website
{
   public function before()
   {
      parent::before();
      $user_from = (isset($_GET['user_from']) && $_GET['user_from'] != '' ? $_GET['user_from'] : 'admin');
      Session::instance()->set('user_from', $user_from);
   }
   public function __construct(Request $request, Response $response)
   {
      parent::__construct($request, $response);
   }
   public function action_checklogin()
   {
      $user_id = Auth::instance()->get_user()->pk();
      if (isset($user_id)) {
         echo '1';
      } else {
         echo '0';
      }
      die;
   }
   public function action_getfeeddetails()
   {
      $user_id       = Auth::instance()->get_user()->pk();
      $week_sarts_on = (isset($this->site_week_starts_on) && $this->site_week_starts_on != '') ? $this->site_week_starts_on : 1;
      $week          = array(
         '1' => 'Monday',
         '2' => 'Tuesday',
         '3' => 'Wednesday',
         '4' => 'Thursday',
         '5' => 'Friday',
         '6' => 'Saturday',
         '7' => 'Sunday'
      );
      if (isset($_POST) && count($_POST) > 0 && $user_id) {
         $offset   = $_POST["offset"];
         $limit    = $_POST["limit"];
         $userids  = $_POST["userids"];
         $site     = $_POST["site"];
         $filter   = array();
         $is_front = (isset($_POST['is_front']) && $_POST['is_front'] ? true : false);
         $is_popup = (isset($_POST['popupFlag']) && $_POST['popupFlag'] ? true : false);
         //if(isset($_POST["popupFlag"])){
         //}else{
         if (isset($_POST["by"]) || isset($_POST["fdate"]) || isset($_POST["fdate"]) || isset($_POST["feedtype"])) {
            //echo "dfgdfgdf";
            $filter["feedtype"] = (isset($_POST["feedtype"]) && $_POST["feedtype"] != '') ? $_POST["feedtype"] : '';
            $filter["fdate"]    = $_POST["fdate"];
            $filter["tdate"]    = $_POST["tdate"];
            if (isset($_POST["by"]) && $_POST["by"] != 4) {
               if ($_POST["by"] == 1) {
                  $_POST["fdate"] = Helper_Common::get_default_date();
                  $_POST["tdate"] = Helper_Common::get_default_date();
               } elseif ($_POST["by"] == 2) {
                  $i = 0;
                  while (date("l", strtotime("-$i days")) != $week[$week_sarts_on]) {
                     $i++;
                  }
                  $from           = date("Y-m-d 00:00:00", strtotime("-$i days"));
                  $_POST["fdate"] = date('d/m/Y', strtotime($from));
                  $_POST["tdate"] = Helper_Common::get_default_date();
                  //print_r($_POST);
               } elseif ($_POST["by"] == 3) {
                  $_POST["fdate"] = Helper_Common::get_default_date('', '01/m/Y');
                  $_POST["tdate"] = Helper_Common::get_default_date();
               } elseif ($_POST["by"] == 5) {
                  $ii = 0;
                  while (date("l", strtotime("-$ii days")) != $week[$week_sarts_on]) {
                     $ii++;
                  }
                  $ii             = $ii + 7;
                  $from           = date("Y-m-d 00:00:00", strtotime("-$ii days"));
                  $_POST["fdate"] = date('d/m/Y', strtotime($from));
                  $_POST["tdate"] = Helper_Common::get_default_date();
               }
            }
            $var             = $_POST["fdate"];
            $date            = str_replace('/', '-', $var);
            $filter["fdate"] = date('Y-m-d', strtotime($date));
            $var             = $_POST["tdate"];
            $date            = str_replace('/', '-', $var);
            $filter["tdate"] = date('Y-m-d', strtotime($date));
         }
         $user_id          = (is_array($userids) && count($userids) == 1 ? $userids : (!is_array($userids) && is_numeric($userids) ? $userids : array()));
         $tot_feed_details = Model::instance('Model/admin/user')->get_feed_details($user_id, $site, $filter, '', '');
         $tot_af           = ($tot_feed_details) ? count($tot_feed_details) : 0;
         $feed_details     = Model::instance('Model/admin/user')->get_feed_details($user_id, $site, $filter, $limit, $offset);
         $cnt              = 0;
         if (isset($feed_details) && is_array($feed_details) && count($feed_details) > 0) {
            $string = "<script type='text/javascript'>
										$('#af_all').val(" . $tot_af . ");
										$('#af_showmore').val(" . $offset . ");
										</script>";
            foreach ($feed_details as $key => $value) {
               $cnt++;
               echo Helper_Activityfeed::activity_index($value, $is_front, $is_popup);
            }
         }
         $tot = $offset + $cnt;
         echo (isset($string)) ? $string : '';
      }
      //else{	echo "sdfsdf";}
      exit;
   }
   public function action_remove_error_feed()
   {
      if (!(Helper_Common::is_admin())) {
         $this->redirect("admin/dashboard/index");
      }
      $userModel = ORM::factory('admin_user');
      $id        = $this->request->param('id');
      if ($id) {
         if ($userModel->remove_error_feed($id))
            $this->session->set('flash_success', 'Error feed was removed successfully...!');
         else
            $this->session->set('flash_error', 'Invalid Error feed');
      } else {
         $this->session->set('flash_error', 'Error feed not removed');
      }
      $this->redirect("admin/dashboard/error/" . ((isset($_GET["etype"]) && $_GET["etype"] != '' && ($_GET["etype"] == 1 or $_GET["etype"] == 2)) ? $_GET["etype"] : ''));
   }
   public function action_update_error_feed()
   {
      if (!(Helper_Common::is_admin())) {
         $this->redirect("admin/dashboard/index");
      }
      $userModel = ORM::factory('admin_user');
      if (isset($_POST) && count($_POST) > 0) {
         $id     = $_POST["id"];
         $status = $_POST["status"];
         //echo "$id,$status"; die;			
         echo $userModel->update_error_feed($id, $status);
         $this->session->set('flash_success', 'Error feed status was updated successfully...!');
      } else {
         echo false;
      }
      exit;
   }
   public function action_view_error_feed()
   {
      if (!(Helper_Common::is_admin())) {
         $this->redirect("admin/dashboard/index");
      }
      $userModel = ORM::factory('admin_user');
      if (isset($_POST) && count($_POST) > 0) {
         $id         = $_POST["id"];
         $errorfeeds = $userModel->get_viewerrorfeeds($id);
         //print_r($errorfeeds);
         if ($errorfeeds) {
            $errorfeeds["error_type"] = ($errorfeeds["error_type"] == 1) ? "PHP" : "Mysql";
            $icon                     = '';
            if ($errorfeeds["status"] == 1) {
               $icon = "<i class=\"fa fa-check error_check_css_fixed\" id='ck_" . $id . "' onclick=\"update_error_feed(" . $id . ",0)\" title='Click here to change the status'   ></i>";
            } else {
               $icon = "<i class=\"fa fa-check error_check_css\" id='ck_" . $id . "' onclick=\"update_error_feed(" . $id . ",1)\"  title='Click here to change the status'  ></i>";
            }
            $errorfeeds["error_file"] = explode("myworkout", $errorfeeds["error_file"]);
            $errorfeeds["error_file"] = $errorfeeds["error_file"][1];
            $str                      = "<div class='form-group'>
					<dl class='dl-horizontal'>
						<dt>Error File</dt>
						<dd>Line No: " . $errorfeeds["error_line"] . "  " . $errorfeeds["error_file"] . "</dd>
						<dt>Error Text</dt>
						<dd style='word-wrap: break-word;'>" . $errorfeeds["error_text"] . "</dd>
						<dt>Error Type</dt>
						<dd >" . wordwrap($errorfeeds["error_type"]) . "</dd>
						<dt>Created Date</dt>
						<dd>" . date("j M Y h:i:s a", strtotime($errorfeeds['created_date'])) . "</dd>
						<dt>Modified Date</dt>
						<dd>" . date("j M Y h:i:s a", strtotime($errorfeeds['modified_date'])) . "</dd>
						<dt>User</dt>
						<dd>" . $errorfeeds["user_fname"] . ' ' . $errorfeeds["user_lname"] . "</dd>
						<dt>Site</dt>
						<dd>" . $errorfeeds["name"] . "</dd>
						<dt></dt><dd>
							$icon
						</dd>
					</dl>
				</div>";
            $errorfeeds               = $userModel->get_errorfeeds_readstatus('');
            $perrorfeeds              = $userModel->get_errorfeeds_readstatus(1);
            $merrorfeeds              = $userModel->get_errorfeeds_readstatus(2);
            $temp["tot_e"]            = (isset($errorfeeds) && count($errorfeeds) > 0) ? count($errorfeeds) : 0;
            $temp["tot_p"]            = (isset($perrorfeeds) && count($perrorfeeds) > 0) ? count($perrorfeeds) : 0;
            $temp["tot_m"]            = (isset($merrorfeeds) && count($merrorfeeds) > 0) ? count($merrorfeeds) : 0;
            $temp["str"]              = (isset($str) && $str != '') ? $str : 0;
            echo json_encode($temp);
         }
         exit;
      }
   }
   public function action_error()
   {
      $this->render();
      $etype = $this->request->param('id');
      //$this->profiler = new Profiler;
      if (!(Helper_Common::is_admin())) {
         $this->redirect("admin/dashboard/index");
      }
      $userModel                           = ORM::factory('admin_user');
      $this->template->title               = 'Admin Error Feeds';
      $this->template->content->page_title = "Error Feeds";
      if ($etype == 1) {
         $this->template->title               = 'Admin PHP Error Feeds';
         $this->template->content->page_title = " PHP Error Feeds";
      } else if ($etype == 2) {
         $this->template->title               = 'Admin Mysql Error Feeds';
         $this->template->content->page_title = " Mysql Error Feeds";
      }
      $errorfeeds                          = $userModel->get_errorfeeds($etype);
      $this->template->content->errorfeeds = $errorfeeds;
      //$this->template->js_bottom = array('assets/js/pages/admin/errorfeed.js');
   }
   public function action_index()
   {
      $user_id               = Auth::instance()->get_user()->pk();
      $userModel             = ORM::factory('admin_user');
      $this->template->title = 'Admin Dashboard';
      $site                  = Helper_Common::get_active_sites();
      if ($this->current_site_id == 1 && isset($site) && is_array($site) && count($site) > 0) {
         $site = implode(",", $site);
      } else {
         $site = $this->current_site_id;
      }
      $subscriberCount = $userModel->getAllSubscriberCount($site);
      $assignCount     = $userModel->getAssignedWorkoutPlanCount($user_id, $this->current_site_id);
      $this->render();
      $this->template->content->editor          = Ckeditor::instance();
      $adminworkoutmodel                        = ORM::factory('admin_workouts');
      $this->template->content->workout_details = $adminworkoutmodel->getWorkoutDetailsByUser('', '', $this->current_site_id);
      $this->template->content->subscriberCount = $subscriberCount;
      $this->template->content->assignCount     = $assignCount;
      $this->template->content->current_site_id = $this->current_site_id;
   }
   public function action_get_trainers()
   {
      $user_id   = Auth::instance()->get_user()->pk();
      $siteid    = $_POST["siteid"];
      $userModel = ORM::factory('admin_user');
      $role      = Helper_Common::get_role("trainer");
      if ($siteid == 'all') {
         $active_sites = array();
         if (Helper_Common::is_admin()) {
            $active_sites    = Helper_Common::get_active_sites();
            $current_site_id = implode(",", $active_sites);
         } elseif (Helper_Common::is_manager()) {
            $usersiteres = $userModel->get_user_sites($user_id);
            if ($usersiteres) {
               foreach ($usersiteres as $k => $v) {
                  $active_sites[] = $v["site_id"];
               }
               $current_site_id = implode(",", $active_sites);
            }
            $current_site_id = implode(",", $active_sites);
         }
         $sitetrainer = Helper_Common::get_role_by_users($role, $current_site_id);
      } else {
         $sitetrainer = Helper_Common::get_role_by_users($role, $siteid);
      }
      $str = '';
      if (isset($sitetrainer) && count($sitetrainer) > 0 && is_array($sitetrainer)) {
         $str .= "
			<select placeholder='Choose Trainers' name='shs_bythis' id='shs_bythis'  onchange='get_sharestats()' >
			<option value='all'>All</option>";
         foreach ($sitetrainer as $key => $value) {
            $str .= '<option    value="' . $value['id'] . '">' . ucfirst($value['user_fname'] . ' ' . $value['user_lname']) . '</option>';
         }
         $str .= "</select>";
      }
      echo $str;
      die;
   }
   public function action_sharerecords()
   {
      if ($this->request->is_ajax())
         $this->auto_render = FALSE;
      $user_id   = Auth::instance()->get_user()->pk();
      $site_id   = $this->current_site_id;
      $userModel = ORM::factory('admin_user');
      $param     = $this->request->param('id');
      $param     = explode("_", $param);
      $id        = $param[0];
      if ($param["1"]) {
         $user_id = $param[1];
      }
      $userdetails = $userModel->get_users_details($user_id);
      $trainername = '';
      if (isset($userdetails)) {
         $trainername = ucfirst($userdetails["0"]["firstname"] . " " . $userdetails["0"]["lastname"]);
      }
      $week_sarts_on = (isset($this->site_week_starts_on) && $this->site_week_starts_on != '') ? $this->site_week_starts_on : 1;
      $week          = array(
         '1' => 'Monday',
         '2' => 'Tuesday',
         '3' => 'Wednesday',
         '4' => 'Thursday',
         '5' => 'Friday',
         '6' => 'Saturday',
         '7' => 'Sunday'
      );
      if (isset($id) && $id != "") {
         if ($id == '1') {
            $i = 0;
            while (date("l", strtotime("-$i days")) != $week[$week_sarts_on]) {
               $i++;
            }
            $from  = date("Y-m-d 00:00:00", strtotime("-$i days"));
            $fdate = date('Y-m-d', strtotime($from));
            $tdate = Helper_Common::get_default_date();
         } elseif ($id == '2') {
            $fdate = Helper_Common::get_default_date('', 'Y-m-01');
            $tdate = Helper_Common::get_default_date();
         } elseif ($id == '3') {
            //echo "$site_id--$user_id"; die;
            $workouts = $userModel->get_sharedrecords("", "", $site_id, $user_id);
            if (isset($workouts)) {
               $i = 0;
               foreach ($workouts as $k => $v) {
                  //print_R($v["created"]);echo "<br>";
                  if ($i == 0) {
                     $fdate = date("Y-m-d", strtotime($v["created"]));
                  }
               }
            } else {
               $fdate = date("Y-m-d 00:00:00", strtotime("-$i days"));
            }
            //die;
            $tdate = Helper_Common::get_default_date();
         } else {
            $this->redirect("admin/dashboard/index");
         }
      }
      //echo "$fdate------$tdate"; die;
      //$this->template->title = 'Trainer Shared Records';
      $this->template->title = "Trainer : $trainername Stats";
      $this->render();
      $this->template->content->editor              = Ckeditor::instance();
      $this->template->content->param               = $id;
      $this->template->content->userid              = $user_id;
      $this->template->content->trainer_stats_title = "$trainername Stats";
      $this->template->content->fdate               = date("d/m/Y", strtotime($fdate));
      $this->template->content->tdate               = date("d/m/Y", strtotime($tdate));
   }
   public function action_sharestats()
   {
      if ($this->request->is_ajax())
         $this->auto_render = FALSE;
      $user_id       = Auth::instance()->get_user()->pk();
      $userModel     = ORM::factory('admin_user');
      $site_id       = $this->current_site_id;
      $week_sarts_on = (isset($this->site_week_starts_on) && $this->site_week_starts_on != '') ? $this->site_week_starts_on : 1;
      $week          = array(
         '1' => 'Monday',
         '2' => 'Tuesday',
         '3' => 'Wednesday',
         '4' => 'Thursday',
         '5' => 'Friday',
         '6' => 'Saturday',
         '7' => 'Sunday'
      );
      $str           = '';
      $trainer_ids   = '';
      //$_POST["user_id"] = 'all';
      if (Helper_Common::is_trainer() || Helper_Common::is_manager() || Helper_Common::is_admin()) {
         //print_R($_POST); die;
         if (Helper_Common::is_manager() || Helper_Common::is_admin()) {
            $str .= "<h4>";
            $role = Helper_Common::get_role("trainer");
            if ($_POST['siteid'] == 'all') {
               $active_sites = array();
               if (Helper_Common::is_admin()) {
                  $active_sites    = Helper_Common::get_active_sites();
                  $current_site_id = implode(",", $active_sites);
               } elseif (Helper_Common::is_manager()) {
                  $usersiteres = $userModel->get_user_sites($user_id);
                  if ($usersiteres) {
                     foreach ($usersiteres as $k => $v) {
                        $active_sites[] = $v["site_id"];
                     }
                     $current_site_id = implode(",", $active_sites);
                  }
               }
               $site_id = implode(",", $active_sites);
               $str .= "All Sites (" . count($active_sites) . ") ";
            } else {
               $site_id  = $_POST['siteid'];
               $sitename = $userModel->get_table_details_by_condtn("sites", "name", "id in ('$site_id')");
               $str .= (isset($sitename)) ? ucfirst($sitename[0]["name"]) . " - " : "";
            }
            if (isset($_POST["user_id"]) && $_POST["user_id"] != '') {
               $role    = Helper_Common::get_role("trainer");
               $trainer = Helper_Common::get_role_by_users($role, $site_id);
               if ($_POST["user_id"] == 'all' & isset($trainer)) {
                  $us = array();
                  foreach ($trainer as $key => $value) {
                     $us[] = $value["id"];
                  }
                  $user_id = $trainer_ids = implode(",", $us);
               } else {
                  $user_id = $_POST["user_id"];
               }
               //echo "$user_id<pre>";die;
               //echo count($_POST["user_id"])."---".count($trainer)."<br>";
               //print_R($_POST["user_id"]);
               if (isset($trainer) && $_POST["user_id"] == 'all') {
                  $str .= " All Trainers(" . count($trainer) . ")";
               } else {
                  if (isset($trainer)) {
                     $str .= "Trainer : ";
                     foreach ($trainer as $k => $v) {
                        if ($v["id"] == $_POST["user_id"]) {
                           $str .= ucfirst($v["user_fname"] . " " . $v["user_lname"]);
                        }
                     }
                  }
               }
            }
            $str .= "</h4>";
            //echo $str; 
            //die;
         }
         ///////////////////////Is Trainer			
         $i = 0;
         while (date("l", strtotime("-$i days")) != $week[$week_sarts_on]) {
            $i++;
         }
         $from                 = date("Y-m-d 00:00:00", strtotime("-$i days"));
         $fdate                = date('Y-m-d', strtotime($from));
         $tdate                = Helper_Common::get_default_date();
         $workouts             = $userModel->get_sharedrecords($fdate, $tdate, $site_id, $user_id);
         $totshares            = $previewedshares = $assignedshares = $loggedshares = $deletedshares = $exportshares = 0;
         $week_shared_trainers = $week_previews = $week_assigned = $week_logged = $week_deleted = $week_exported = '';
         if ((Helper_Common::is_manager() || Helper_Common::is_admin()) && isset($_POST["user_id"]) && $_POST["user_id"] == 'all') {
            $week_shared_trainers = $userModel->get_sharedrecords_trainers($fdate, $tdate, $site_id, $trainer_ids);
         }
         if ($workouts) {
            $wkout_share_id = array();
            foreach ($workouts as $k => $v) {
               $wkout_share_id[] = $v["wkout_share_id"];
            }
            $totshares       = count($wkout_share_id);
            $previewedshares = $userModel->get_acshares(42, 12, $fdate, $tdate, $site_id, $wkout_share_id, "");
            $assignedshares  = $userModel->get_acshares(22, 12, $fdate, $tdate, $site_id, $wkout_share_id, "and af.json_data like '%assign%'");
            $loggedshares    = $userModel->get_acshares(22, 12, $fdate, $tdate, $site_id, $wkout_share_id, "and af.json_data like '%log%'");
            $deletedshares   = $userModel->get_acshares(21, 12, $fdate, $tdate, $site_id, $wkout_share_id, '');
            $exportshares    = $userModel->get_acshares(43, 12, $fdate, $tdate, $site_id, $wkout_share_id, '');
            if ((Helper_Common::is_manager() || Helper_Common::is_admin()) && isset($_POST["user_id"]) && $_POST["user_id"] == 'all') {
               $week_previews = $userModel->get_statsshares(42, 12, $fdate, $tdate, $site_id, $wkout_share_id, "");
               $week_assigned = $userModel->get_acshares(22, 12, $fdate, $tdate, $site_id, $wkout_share_id, "and af.json_data like '%assign%'");
               $week_logged   = $userModel->get_acshares(22, 12, $fdate, $tdate, $site_id, $wkout_share_id, "and af.json_data like '%log%'");
               $week_deleted  = $userModel->get_acshares(21, 12, $fdate, $tdate, $site_id, $wkout_share_id, '');
               $week_exported = $userModel->get_acshares(43, 12, $fdate, $tdate, $site_id, $wkout_share_id, '');
            }
         }
         //echo "This Week -> $fdate---$tdate<br>";
         //echo 	"<pre>	Tot  S ->$totshares	Prev S ->$previewedshares	Ass  S ->$assignedshares	Log  S ->$loggedshares	Del  S ->$deletedshares		Exp S->$exportshares</pre><hr>";
         /********************************************************************/
         $tdate      = date('Y-m-d', strtotime("-1 days, $fdate"));
         $fdate      = date('Y-m-d', strtotime("-7 days, $fdate"));
         $workouts   = $userModel->get_sharedrecords($fdate, $tdate, $site_id, $user_id);
         $totshares1 = $previewedshares1 = $assignedshares1 = $loggedshares1 = $deletedshares1 = $exportshares1 = 0;
         if ($workouts) {
            $wkout_share_id = array();
            foreach ($workouts as $k => $v) {
               $wkout_share_id[] = $v["wkout_share_id"];
            }
            $totshares1       = count($wkout_share_id);
            $previewedshares1 = $userModel->get_acshares(42, 12, $fdate, $tdate, $site_id, $wkout_share_id, "");
            $assignedshares1  = $userModel->get_acshares(22, 12, $fdate, $tdate, $site_id, $wkout_share_id, "and af.json_data like '%assign%'");
            $loggedshares1    = $userModel->get_acshares(22, 12, $fdate, $tdate, $site_id, $wkout_share_id, "and af.json_data like '%log%'");
            $deletedshares1   = $userModel->get_acshares(21, 12, $fdate, $tdate, $site_id, $wkout_share_id, '');
            $exportshares1    = $userModel->get_acshares(43, 12, $fdate, $tdate, $site_id, $wkout_share_id, '');
         }
         //echo "Last Week -> $fdate---$tdate<br>";
         //echo 	"<pre>	Tot  S ->$totshares1	Prev S ->$previewedshares1	Ass  S ->$assignedshares1	Log  S ->$loggedshares1	Del  S ->$deletedshares1	Exp S->$exportshares1</pre><hr>";
         /********************************************************************/
         $fdate                 = Helper_Common::get_default_date('', 'Y-m-01');
         $tdate                 = Helper_Common::get_default_date();
         $workouts              = $userModel->get_sharedrecords($fdate, $tdate, $site_id, $user_id);
         $totshares2            = $previewedshares2 = $assignedshares2 = $loggedshares2 = $deletedshares2 = $exportshares2 = $month_previews = 0;
         $month_shared_trainers = $month_previews = $month_assigned = $month_logged = $month_deleted = $month_exported = '';
         if ((Helper_Common::is_manager() || Helper_Common::is_admin()) && isset($_POST["user_id"]) && $_POST["user_id"] == 'all') {
            $month_shared_trainers = $userModel->get_sharedrecords_trainers($fdate, $tdate, $site_id, $trainer_ids);
         }
         if ($workouts) {
            $wkout_share_id = array();
            foreach ($workouts as $k => $v) {
               $wkout_share_id[] = $v["wkout_share_id"];
            }
            $totshares2       = count($wkout_share_id);
            $previewedshares2 = $userModel->get_acshares(42, 12, $fdate, $tdate, $site_id, $wkout_share_id, "");
            $assignedshares2  = $userModel->get_acshares(22, 12, $fdate, $tdate, $site_id, $wkout_share_id, "and af.json_data like '%assign%'");
            $loggedshares2    = $userModel->get_acshares(22, 12, $fdate, $tdate, $site_id, $wkout_share_id, "and af.json_data like '%log%'");
            $deletedshares2   = $userModel->get_acshares(21, 12, $fdate, $tdate, $site_id, $wkout_share_id, '');
            $exportshares2    = $userModel->get_acshares(43, 12, $fdate, $tdate, $site_id, $wkout_share_id, '');
            if ((Helper_Common::is_manager() || Helper_Common::is_admin()) && isset($_POST["user_id"]) && $_POST["user_id"] == 'all') {
               $month_previews = $userModel->get_statsshares(42, 12, $fdate, $tdate, $site_id, $wkout_share_id, "");
               $month_assigned = $userModel->get_acshares(22, 12, $fdate, $tdate, $site_id, $wkout_share_id, "and af.json_data like '%assign%'");
               $month_logged   = $userModel->get_acshares(22, 12, $fdate, $tdate, $site_id, $wkout_share_id, "and af.json_data like '%log%'");
               $month_deleted  = $userModel->get_acshares(21, 12, $fdate, $tdate, $site_id, $wkout_share_id, '');
               $month_exported = $userModel->get_acshares(43, 12, $fdate, $tdate, $site_id, $wkout_share_id, '');
            }
         }
         //echo "This Month -> $fdate---$tdate<br>";
         //echo 	"<pre>	Tot  S ->$totshares2	Prev S ->$previewedshares2	Ass  S ->$assignedshares2	Log  S ->$loggedshares2	Del  S ->$deletedshares2	Exp S->$exportshares2</pre><hr>";
         /********************************************************************/
         $tdate      = date('Y-m-d', strtotime("-1 days, $fdate"));
         $fdate      = date('Y-m-d', strtotime("-1 month, $fdate"));
         $workouts   = $userModel->get_sharedrecords($fdate, $tdate, $site_id, $user_id);
         $totshares3 = $previewedshares3 = $assignedshares3 = $loggedshares3 = $deletedshares3 = $exportshares3 = 0;
         if ($workouts) {
            $wkout_share_id = array();
            foreach ($workouts as $k => $v) {
               $wkout_share_id[] = $v["wkout_share_id"];
            }
            $totshares3       = count($wkout_share_id);
            $previewedshares3 = $userModel->get_acshares(42, 12, $fdate, $tdate, $site_id, $wkout_share_id, "");
            $assignedshares3  = $userModel->get_acshares(22, 12, $fdate, $tdate, $site_id, $wkout_share_id, "and af.json_data like '%assign%'");
            $loggedshares3    = $userModel->get_acshares(22, 12, $fdate, $tdate, $site_id, $wkout_share_id, "and af.json_data like '%log%'");
            $deletedshares3   = $userModel->get_acshares(21, 12, $fdate, $tdate, $site_id, $wkout_share_id, '');
            $exportshares3    = $userModel->get_acshares(43, 12, $fdate, $tdate, $site_id, $wkout_share_id, '');
         }
         //echo "Last Month -> $fdate---$tdate<br>";
         //echo 	"<pre>	Tot  S ->$totshares3	Prev S ->$previewedshares3	Ass  S ->$assignedshares3	Log  S ->$loggedshares3	Del  S ->$deletedshares3	Exp S->$exportshares3</pre><hr>";
         /********************************************************************/
         $fdate      = $tdate = '';
         $workouts   = $userModel->get_sharedrecords($fdate, $tdate, $site_id, $user_id);
         $totshares4 = $previewedshares4 = $assignedshares4 = $loggedshares4 = $deletedshares4 = $exportshares4 = 0;
         if ($workouts) {
            $wkout_share_id = array();
            foreach ($workouts as $k => $v) {
               $wkout_share_id[] = $v["wkout_share_id"];
            }
            $totshares4       = count($wkout_share_id);
            $previewedshares4 = $userModel->get_acshares(42, 12, $fdate, $tdate, $site_id, $wkout_share_id, "");
            $assignedshares4  = $userModel->get_acshares(22, 12, $fdate, $tdate, $site_id, $wkout_share_id, "and af.json_data like '%assign%'");
            $loggedshares4    = $userModel->get_acshares(22, 12, $fdate, $tdate, $site_id, $wkout_share_id, "and af.json_data like '%log%'");
            $deletedshares4   = $userModel->get_acshares(21, 12, $fdate, $tdate, $site_id, $wkout_share_id, '');
            $exportshares4    = $userModel->get_acshares(43, 12, $fdate, $tdate, $site_id, $wkout_share_id, '');
         }
         //echo "OVER ALL<br>";
         //echo 	"<pre>	Tot  S ->$totshares4	Prev S ->$previewedshares4	Ass  S ->$assignedshares4	Log  S ->$loggedshares4	Del  S ->$deletedshares4	Exp S->$exportshares4</pre><hr>";
         /********************************************************************/
         $class = $child_class = $icon = '';
         if ((Helper_Common::is_manager() || Helper_Common::is_admin()) && isset($_POST["user_id"]) && $_POST["user_id"] == 'all') {
            $class       = "class='accordion-toggle ' data-toggle='collapse' id='shares' data-target='.shares'";
            $child_class = "class='expand-child collapse  shares'";
            $icon        = "<i class='chevron_toggleable pull-right fa fa-blue fa-chevron-down pointer_hand'></i>";
         }
         $str .= "
						<table class='table table-responsive table-bordered table-hover table-striped' style='width:80%;' align='center' border=1 cellpadding=10 cellspacing=0>
							<thead>
								<tr>
									<th></th>
									<th>WTD Total</th>
									<th></th>
									<th>Variance to Last Week</th>
									<th>MTD Total</th>
									<th></th>
									<th>Variance to Last Month</th>
									<th>Overall Totals</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								
								<tr $class>
									<td >Shared Workouts
									$icon
									</td>
									<td align='center' title='Week'>";
         if ($totshares == 0) {
            $str .= "n/a";
         } else {
            if (((Helper_Common::is_manager() || Helper_Common::is_admin()) && isset($_POST["user_id"]) && $_POST["user_id"] != 'all') || (Helper_Common::is_trainer())) {
               $str .= "<a href='" . URL::base(true) . "admin/dashboard/sharerecords/1_" . $user_id . "' title='This week'>$totshares</a>";
            } else {
               $str .= $totshares;
            }
         }
         $str .= "</td>
									<td align='center'></td>
									<td align='center' title='Varience to Last week'>";
         if (($totshares - $totshares1) == 0) {
            $str .= "n/a";
         } else {
            $str .= ((($totshares - $totshares1) > 0) ? ("+" . ($totshares - $totshares1)) : ($totshares - $totshares1));
         }
         $str .= "</td>									
									<td align='center' title='Month'>";
         if ($totshares2 == 0) {
            $str .= "n/a";
         } else {
            if (((Helper_Common::is_manager() || Helper_Common::is_admin()) && isset($_POST["user_id"]) && $_POST["user_id"] != 'all') || (Helper_Common::is_trainer())) {
               $str .= " <a href='" . URL::base(true) . "admin/dashboard/sharerecords/2_" . $user_id . "' title='This month'>" . $totshares2 . "</a></td>";
            } else {
               $str .= $totshares2;
            }
         }
         $str .= "<td align='center'></td>
									<td align='center' title='Varience to Last month'>";
         if (($totshares2 - $totshares3) == 0) {
            $str .= "n/a";
         } else {
            $str .= ((($totshares2 - $totshares3) > 0) ? ("+" . ($totshares2 - $totshares3)) : ($totshares2 - $totshares3));
         }
         $str .= "</td>
									<td align='center' title='Overall'>";
         if ($totshares4 == 0) {
            $str .= "n/a";
         } else {
            if (((Helper_Common::is_manager() || Helper_Common::is_admin()) && isset($_POST["user_id"]) && $_POST["user_id"] != 'all') || (Helper_Common::is_trainer())) {
               $str .= "<a href='" . URL::base(true) . "admin/dashboard/sharerecords/3_" . $user_id . "' title='Over All'>$totshares4</a></td>";
            } else {
               $str .= $totshares4;
            }
         }
         $str .= "<td align='center'></td>
								</tr>";
         if ((Helper_Common::is_manager() || Helper_Common::is_admin()) && isset($_POST["user_id"]) && $_POST["user_id"] == 'all') {
            //For week
            $week_leaders = $week_below_average = '';
            if ($totshares == 0) {
               $week_sapt = $week_saptpercent = "n/a";
            } else {
               if (isset($week_shared_trainers) && !empty($week_shared_trainers) && count($week_shared_trainers) > 0) {
                  $week_sapt        = ceil($totshares / count($week_shared_trainers));
                  $week_saptpercent = ceil(($week_sapt * 100) / $totshares);
                  $week_saptpercent = ($week_saptpercent > 0) ? $week_saptpercent . "%" : "n/a";
                  foreach ($week_shared_trainers as $k => $v) {
                     $img = URL::base() . 'assets/img/user_placeholder.png';
                     if (isset($v["profile_img"]) && $v["profile_img"] != "") {
                        $getImg = $userModel->get_users_profile_image($v["profile_img"]);
                        if (file_exists($getImg["img_url"])) {
                           $img = URL::base() . $getImg["img_url"];
                        }
                     }
                     $count = $v["totalshares_by_trainer"];
                     if ($count >= $week_sapt) {
                        $week_leaders .= "<div class='row'>";
                        $week_leaders .= "<a href='" . URL::base(true) . "admin/dashboard/sharerecords/1_" . $v["shared_by"] . "' title='This week for " . ucfirst($v["shared_byname"]) . "'>";
                        $week_leaders .= '<div class="col-xs-2">
																	<div class="img-circle-div" style="margin-left:20px">
																		<img class="rounded-corners" style="width:35px;height:35px;" src="' . $img . '">
																	</div>
																</div>
																';
                        //$week_leaders .= "<div class='col-xs-10' style='margin-top:7px'>".ucfirst($v["shared_byname"])." ($count)</div>";
                        $week_leaders .= "<div class='col-xs-10' style='margin-top:7px'>" . ucfirst($v["shared_byname"]) . "</div>";
                        $week_leaders .= "</a>";
                        $week_leaders .= "</div>";
                     } else {
                        $week_below_average .= "<div class='row'>";
                        $week_below_average .= "<a href='" . URL::base(true) . "admin/dashboard/sharerecords/1_" . $v["shared_by"] . "' title='This week for " . ucfirst($v["shared_byname"]) . "'>";
                        $week_below_average .= '<div class="col-xs-2">
																			<div class="img-circle-div" style="margin-left:20px">
																				<img class="rounded-corners" style="width:35px;height:35px;" src="' . $img . '">
																			</div>
																		</div>
																		';
                        //$week_below_average .= "<div class='col-xs-10' style='margin-top:7px'>".ucfirst($v["shared_byname"])." ($count)</div>";
                        $week_below_average .= "<div class='col-xs-10' style='margin-top:7px'>" . ucfirst($v["shared_byname"]) . "</div>";
                        $week_below_average .= "</a>";
                        $week_below_average .= "</div>";
                     }
                  }
               } else {
                  $week_sapt = $week_saptpercent = "n/a";
               }
            }
            //For month
            $month_leaders = $month_below_average = '';
            if ($totshares2 == 0) {
               $month_sapt = $month_saptpercent = "n/a";
            } else {
               if (isset($month_shared_trainers) && !empty($month_shared_trainers) && count($month_shared_trainers) > 0) {
                  $month_sapt        = ceil($totshares2 / count($month_shared_trainers));
                  $month_saptpercent = ceil(($month_sapt * 100) / $totshares2);
                  $month_saptpercent = ($month_saptpercent > 0) ? $month_saptpercent . "%" : "n/a";
                  foreach ($month_shared_trainers as $k => $v) {
                     $img = URL::base() . 'assets/img/user_placeholder.png';
                     if (isset($v["profile_img"]) && $v["profile_img"] != "") {
                        $getImg = $userModel->get_users_profile_image($v["profile_img"]);
                        if (file_exists($getImg["img_url"])) {
                           $img = URL::base() . $getImg["img_url"];
                        }
                     }
                     $count = $v["totalshares_by_trainer"];
                     if ($count >= $month_sapt) {
                        $month_leaders .= "<div class='row'>";
                        $month_leaders .= "<a href='" . URL::base(true) . "admin/dashboard/sharerecords/2_" . $v["shared_by"] . "' title='This month for " . ucfirst($v["shared_byname"]) . "'>";
                        $month_leaders .= '<div class="col-xs-2">
																	<div class="img-circle-div" style="margin-left:20px">
																		<img class="rounded-corners" style="width:35px;height:35px;" src="' . $img . '">
																	</div>
																</div>
																';
                        //$month_leaders .= "<div class='col-xs-10' style='margin-top:7px'>".ucfirst($v["shared_byname"])." ($count)</div>";
                        $month_leaders .= "<div class='col-xs-10' style='margin-top:7px'>" . ucfirst($v["shared_byname"]) . "</div>";
                        $month_leaders .= "</a>";
                        $month_leaders .= "</div>";
                     } else {
                        $month_below_average .= "<div class='row'>";
                        $month_below_average .= "<a href='" . URL::base(true) . "admin/dashboard/sharerecords/2_" . $v["shared_by"] . "' title='This month for " . ucfirst($v["shared_byname"]) . "'>";
                        $month_below_average .= '<div class="col-xs-2">
																			<div class="img-circle-div" style="margin-left:20px">
																				<img class="rounded-corners" style="width:35px;height:35px;" src="' . $img . '">
																			</div>
																		</div>
																		';
                        //$month_below_average .= "<div class='col-xs-10' style='margin-top:7px'>".ucfirst($v["shared_byname"])." ($count)</div>";
                        $month_below_average .= "<div class='col-xs-10' style='margin-top:7px'>" . ucfirst($v["shared_byname"]) . "</div>";
                        $month_below_average .= "</a>";
                        $month_below_average .= "</div>";
                     }
                  }
               } else {
                  $month_sapt = $month_saptpercent = "n/a";
               }
            }
            $str .= "<tr $child_class>
									<td align='right'>Site Averages per Trainer</td>
									<td align='center'>$week_sapt</td><td align='center'>$week_saptpercent</td>
									<td align='center'></td><td align='center'>$month_sapt</td><td align='center'>$month_saptpercent</td>
									<td align='center'></td><td align='center'></td><td align='center'></td>
								</tr>
								<tr $child_class>
									<td align='right'>Leaders</td>
									<td align='left' colspan='3'>" . (($week_leaders != '') ? $week_leaders : '-') . "</td>
									<td align='left' colspan='3'>" . (($month_leaders != '') ? $month_leaders : '-') . "</td>
									<td align='center'></td><td align='center'></td>
								</tr>
								<tr $child_class>
									<td align='right'>Below Average</td>
									<td align='left' colspan='3'>" . (($week_below_average != '') ? $week_below_average : '-') . "</td>
									<td align='left' colspan='3'>" . (($month_below_average != '') ? $month_below_average : '-') . "</td>
									<td align='center'></td><td align='center'></td>
								</tr>
								";
         }
         $str .= $userModel->get_tr("pshares", "Previewed Shares", $totshares, $totshares2, $totshares4, $previewedshares, $previewedshares1, $previewedshares2, $previewedshares3, $previewedshares4, $week_previews, $week_shared_trainers, $month_previews, $month_shared_trainers);
         $str .= $userModel->get_tr("ashares", "Assigned Shares", $totshares, $totshares2, $totshares4, $assignedshares, $assignedshares1, $assignedshares2, $assignedshares3, $assignedshares4, $week_assigned, $week_shared_trainers, $month_assigned, $month_shared_trainers);
         $str .= $userModel->get_tr("lshares", "Logged Shares", $totshares, $totshares2, $totshares4, $loggedshares, $loggedshares1, $loggedshares2, $loggedshares3, $loggedshares4, $week_logged, $week_shared_trainers, $month_logged, $month_shared_trainers);
         $str .= $userModel->get_tr("eshares", "Exported Shares", $totshares, $totshares2, $totshares4, $exportshares, $exportshares1, $exportshares2, $exportshares3, $exportshares4, $week_exported, $week_shared_trainers, $month_exported, $month_shared_trainers);
         $str .= $userModel->get_tr("dshares", "Deleted Shares", $totshares, $totshares2, $totshares4, $deletedshares, $deletedshares1, $deletedshares2, $deletedshares3, $deletedshares4, $week_deleted, $week_shared_trainers, $month_deleted, $month_shared_trainers);
         $str .= "</tbody>
						</table>
					";
         echo $str;
         die;
         ///////////////////////Is Trainer
      }
   }
   public function action_sharedrecordsupdate()
   {
      $user_id        = Auth::instance()->get_user()->pk();
      $site_id        = $this->current_site_id;
      $adminusermodel = ORM::factory('admin_user');
      $week_sarts_on  = (isset($this->site_week_starts_on) && $this->site_week_starts_on != '') ? $this->site_week_starts_on : 1;
      $week           = array(
         '1' => 'Monday',
         '2' => 'Tuesday',
         '3' => 'Wednesday',
         '4' => 'Thursday',
         '5' => 'Friday',
         '6' => 'Saturday',
         '7' => 'Sunday'
      );
      //print_R($_POST);
      if (isset($_POST["user_id"]))
         $user_id = $_POST["user_id"];
      if (isset($_POST["by"]) && $_POST["by"] != 4) {
         if ($_POST["by"] == 1) {
            $_POST["fdate"] = Helper_Common::get_default_date();
            $_POST["tdate"] = Helper_Common::get_default_date();
         } elseif ($_POST["by"] == 2) {
            $i = 0;
            while (date("l", strtotime("-$i days")) != $week[$week_sarts_on]) {
               $i++;
            }
            $from           = date("Y-m-d 00:00:00", strtotime("-$i days"));
            $_POST["fdate"] = date('d/m/Y', strtotime($from));
            $_POST["tdate"] = Helper_Common::get_default_date();
         } elseif ($_POST["by"] == 3) {
            $_POST["fdate"] = Helper_Common::get_default_date('', '01/m/Y');
            $_POST["tdate"] = Helper_Common::get_default_date();
         } elseif ($_POST["by"] == 5) {
            $ii = 0;
            while (date("l", strtotime("-$ii days")) != $week[$week_sarts_on]) {
               $ii++;
            }
            $ii             = $ii + 7;
            $from           = date("Y-m-d 00:00:00", strtotime("-$ii days"));
            $_POST["fdate"] = date('d/m/Y', strtotime($from));
            $_POST["tdate"] = Helper_Common::get_default_date();
         }
      }
      $var            = $_POST["fdate"];
      $date           = str_replace('/', '-', $var);
      $_POST["fdate"] = date('Y-m-d', strtotime($date));
      $var            = $_POST["tdate"];
      $date           = str_replace('/', '-', $var);
      $_POST["tdate"] = date('Y-m-d', strtotime($date));
      //print_R($_POST);
      $condition      = '';
      $ac_cond        = '';
      if ($_POST["fdate"] && $_POST["tdate"]) {
         $condition .= " and wsg.created between '" . date("Y-m-d 00:00:00", strtotime($_POST["fdate"])) . "' and '" . date("Y-m-d 23:59:59", strtotime($_POST["tdate"])) . "'";
         $ac_cond = "and created_date between '" . date("Y-m-d 00:00:00", strtotime($_POST["fdate"])) . "' and '" . date("Y-m-d 23:59:59", strtotime($_POST["tdate"])) . "'";
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
					
				"; // and wsg.site_id in ($site_id)
      //echo $qry;
      $query  = DB::query(Database::SELECT, $qry);
      $return = $query->execute()->as_array();
      $str    = "";
      if ($return) {
         $str .= "<table class='table table-bordered table-hover table-striped shareDataTable' style='width:95%;'>
					<thead>
						<tr>
							<th>User</th>
							<th>Record</th>
							<th>Preview</th>
							<th>Log</th>
							<th>Assign</th>
							<th>Duplicate</th>
							<th>Export</th>
							<th>Delete</th>
							<th class='hide'>Shared Date</th>
						</tr>
					</thead>
					<tbody>";
         foreach ($return as $k => $v) {
            $img = URL::base() . 'assets/img/user_placeholder.png';
            if (isset($v["profile_img"]) && $v["profile_img"] != "") {
               $getImg = $adminusermodel->get_users_profile_image($v["profile_img"]);
               if (file_exists($getImg["img_url"])) {
                  $img = URL::base() . $getImg["img_url"];
               }
            }
            $user           = $v["user_id"];
            $wkout_id       = $v["wkout_id"];
            $wkout_share_id = $v["wkout_share_id"];
            $shared_date    = $v["created"];
            $a_type         = '42';
            $f_type         = '12';
            $qry            = "SELECT * FROM activity_feed where feed_type=$f_type AND action_type=$a_type AND user=$user AND site_id = $site_id and type_id=$wkout_share_id $ac_cond ";
            $query          = DB::query(Database::SELECT, $qry);
            $res            = $query->execute()->as_array();
            $preview_cnt    = (isset($res) && is_array($res)) ? count($res) : 0;
            $a_type         = '22';
            $f_type         = '12';
            $qry            = "SELECT * FROM activity_feed where feed_type=$f_type AND action_type=$a_type AND user=$user AND site_id = $site_id  and type_id=$wkout_share_id  and json_data like '%assign%' $ac_cond";
            $query          = DB::query(Database::SELECT, $qry);
            $res            = $query->execute()->as_array();
            $assign_cnt     = (isset($res) && is_array($res)) ? count($res) : 0;
            $a_type         = '22';
            $f_type         = '12';
            $qry            = "SELECT * FROM activity_feed where feed_type=$f_type AND action_type=$a_type AND user=$user AND site_id = $site_id  and type_id=$wkout_share_id and json_data like '%log%' $ac_cond";
            $query          = DB::query(Database::SELECT, $qry);
            $res            = $query->execute()->as_array();
            $log_cnt        = (isset($res) && is_array($res)) ? count($res) : 0;
            $a_type         = '22';
            $f_type         = '12';
            $qry            = "SELECT * FROM activity_feed where feed_type=$f_type AND action_type=$a_type AND user=$user AND site_id = $site_id  and type_id=$wkout_share_id and json_data like '%\"wkout\"%' $ac_cond";
            $query          = DB::query(Database::SELECT, $qry);
            $res            = $query->execute()->as_array();
            $dup_cnt        = (isset($res) && is_array($res)) ? count($res) : 0;
            $a_type         = '43';
            $f_type         = '12';
            $qry            = "SELECT * FROM activity_feed where feed_type=$f_type AND action_type=$a_type AND user=$user AND site_id = $site_id  and type_id=$wkout_share_id $ac_cond";
            $query          = DB::query(Database::SELECT, $qry);
            $res            = $query->execute()->as_array();
            $export_cnt     = (isset($res) && is_array($res)) ? count($res) : 0;
            //echo "<hr>";
            $a_type         = '21';
            $f_type         = '12';
            $qry            = "SELECT * FROM activity_feed where feed_type=$f_type AND action_type=$a_type AND user=$user AND site_id = $site_id  and type_id=$wkout_share_id $ac_cond";
            $query          = DB::query(Database::SELECT, $qry);
            $res            = $query->execute()->as_array();
            $del            = '-';
            if (isset($res) && is_array($res) && count($res) > 0) {
               $del = date("d M Y H:i", strtotime($res[0]["created_date"]));
            }
            $str .= "<tr>
							<td class='pointer_pro'>
								<div class='row' onclick='showUserModel(" . $v["user_id"] . ",1)'>
									<div class='col-xs-2'>
										<div class='img-circle-div' style='margin-left:20px'>
											<img class='rounded-corners' src='$img' style='width:35px;height:35px;'>
										</div>
									</div>
									<div class='col-xs-10' style='margin-top:7px'>
										" . ucfirst(strtolower($v["username"])) . "
									</div>
								</div>
							</td>
							<td>
							<a href='javascript:void(0);' onclick='viewwkout(" . $v["wkout_id"] . ")'>
							" . $v["wkout_title"] . "
							</a>
							</td>
							<td align='center'>$preview_cnt</td>
							<td align='center'>$log_cnt</td>
							<td align='center'>$assign_cnt</td>
							<td align='center'>$dup_cnt</td>
							<td align='center'>$export_cnt</td>
							<td align='center'>" . $del . "</td>
							<td align='center' class='hide'>$shared_date</td>
						</tr>";
         }
         $str .= "</tbody>
				</table>";
      }
      echo $str;
      die;
   }
   public function action_morrischartupdate()
   {
      if ($this->current_site_id == '1')
         $active_sites = Helper_Common::get_active_sites();
      else
         $active_sites = $this->current_site_id;
      $week_sarts_on = (isset($this->site_week_starts_on) && $this->site_week_starts_on != '') ? $this->site_week_starts_on : 1;
      $week          = array(
         '1' => 'Monday',
         '2' => 'Tuesday',
         '3' => 'Wednesday',
         '4' => 'Thursday',
         '5' => 'Friday',
         '6' => 'Saturday',
         '7' => 'Sunday'
      );
      //$week 			= $this->week; 
      if ($this->request->is_ajax())
         $this->auto_render = FALSE;
      $condition       = $condition1 = '';
      $datarow         = array();
      //print_R($_POST); die;
      $_POST["status"] = $_POST["status"][0];
      if (Helper_Common::is_trainer() && $_POST["type"] == "subscribers" && ($_POST["status"] == 'all' || $_POST["status"] == "workouts" || $_POST["status"] == "exercises")) {
         if (isset($_POST["by"]) && $_POST["by"] != 4) {
            if ($_POST["by"] == 1) {
               $_POST["fdate"] = Helper_Common::get_default_date();
               $_POST["tdate"] = Helper_Common::get_default_date();
            } elseif ($_POST["by"] == 2) {
               $i = 0;
               while (date("l", strtotime("-$i days")) != $week[$week_sarts_on]) {
                  $i++;
               }
               $from           = date("Y-m-d 00:00:00", strtotime("-$i days"));
               $_POST["fdate"] = date('d/m/Y', strtotime($from));
               $_POST["tdate"] = Helper_Common::get_default_date();
            } elseif ($_POST["by"] == 3) {
               $_POST["fdate"] = Helper_Common::get_default_date('', '01/m/Y');
               $_POST["tdate"] = Helper_Common::get_default_date();
            } elseif ($_POST["by"] == 5) {
               $ii = 0;
               while (date("l", strtotime("-$ii days")) != $week[$week_sarts_on]) {
                  $ii++;
               }
               $ii             = $ii + 7;
               $from           = date("Y-m-d 00:00:00", strtotime("-$ii days"));
               $_POST["fdate"] = date('d/m/Y', strtotime($from));
               $_POST["tdate"] = Helper_Common::get_default_date();
            }
         }
         $var            = $_POST["fdate"];
         $date           = str_replace('/', '-', $var);
         $_POST["fdate"] = date('Y-m-d', strtotime($date));
         $var            = $_POST["tdate"];
         $date           = str_replace('/', '-', $var);
         $_POST["tdate"] = date('Y-m-d', strtotime($date));
         //print_r($_POST); die;
         if (isset($_POST) && count($_POST) > 0) {
            if ($_POST["fdate"] && $_POST["tdate"]) {
               $condition .= ' and ';
               $condition .= " created between '" . date("Y-m-d 00:00:00", strtotime($_POST["fdate"])) . "' and '" . date("Y-m-d 23:59:59", strtotime($_POST["tdate"])) . "'";
               $condition1 .= ' and ';
               $condition1 .= " created_date between '" . date("Y-m-d 00:00:00", strtotime($_POST["fdate"])) . "' and '" . date("Y-m-d 23:59:59", strtotime($_POST["tdate"])) . "'";
            }
         }
         $wquery = "
						SELECT
							wsg.wkout_share_id as id,  DATE_FORMAT( wsg.created, '%Y%m%d') as date, wsg.created, 'workouts' as type
						FROM
							wkout_share_gendata as wsg
							JOIN wkout_share_seq as wss on wss.wkout_share_id = wsg.wkout_share_id 
						WHERE
							wsg.status_id=1 and 
							wss.shared_by=" . Auth::instance()->get_user()->pk() . " and wsg.site_id in (" . $this->current_site_id . ") 
							 $condition
						ORDER BY wsg.created asc";
         //echo "<pre>$wquery"; die;
         $equery = "SELECT
								unit_share_id as id, DATE_FORMAT( created_date, '%Y%m%d') as date, created_date as created, 'exercises' as type
							FROM unit_gendata_shared
							where
								status_id=1 and 
								shared_by=" . Auth::instance()->get_user()->pk() . " and site_id in (" . $this->current_site_id . ")
								$condition1
							ORDER BY created_date asc	
							";
         if ($_POST["status"] == "all") {
            $query = " ($wquery) union ($equery)  order by date asc";
         } elseif ($_POST["status"] == "workouts") {
            $query = $wquery;
         } elseif ($_POST["status"] == "exercises") {
            $query = $equery;
         }
         $query  = DB::query(Database::SELECT, $query);
         ///echo $query;
         $return = $query->execute()->as_array();
         $temp   = array();
         /*echo "<pre>";
         print_r($return);
         die;*/
         if ($return) {
            foreach ($return as $k => $v) {
               if ($v["type"] == "exercises") {
                  $temp[$v["date"]][$v["type"]][] = $v["id"];
               } elseif ($v["type"] == "workouts") {
                  $temp[$v["date"]][$v["type"]][] = $v["id"];
               }
            }
            $dataTemp = array();
            $kk       = 0;
            foreach ($temp as $k => $v) {
               if ($kk == 0 && date("Y-m-d", strtotime($k)) != $_POST["fdate"]) {
                  $dataTemp[$kk]["id"] = $_POST["fdate"];
                  if ($_POST["status"] == 'all' || $_POST["status"] == "workouts")
                     $dataTemp[$kk]["site"]["Workouts"] = 0;
                  if ($_POST["status"] == 'all' || $_POST["status"] == "exercises")
                     $dataTemp[$kk]["site"]["Exercises"] = 0;
                  $dataTemp[$kk]["All"] = 0;
                  $kk++;
               }
               $dataTemp[$kk]["id"] = date("Y-m-d", strtotime($k));
               if ($_POST["status"] == "all" || $_POST["status"] == "workouts")
                  $dataTemp[$kk]["site"]["Workouts"] = (isset($v["workouts"]) && is_array($v["workouts"])) ? count($v["workouts"]) : 0;
               if ($_POST["status"] == "all" || $_POST["status"] == "exercises")
                  $dataTemp[$kk]["site"]["Exercises"] = (isset($v["exercises"]) && is_array($v["exercises"])) ? count($v["exercises"]) : 0;
               $dataTemp[$kk]["All"] = ((isset($dataTemp[$kk]["site"]["Exercises"])) ? $dataTemp[$kk]["site"]["Exercises"] : 0) + ((isset($dataTemp[$kk]["site"]["Workouts"])) ? $dataTemp[$kk]["site"]["Workouts"] : 0);
               $kk++;
            }
            if (date("Y-m-d", strtotime($k)) != $_POST["tdate"]) {
               $dataTemp[$kk]["id"] = $_POST["tdate"];
               if ($_POST["status"] == "all" || $_POST["status"] == "workouts")
                  $dataTemp[$kk]["site"]["Workouts"] = 0;
               if ($_POST["status"] == "all" || $_POST["status"] == "exercises")
                  $dataTemp[$kk]["site"]["Exercises"] = 0;
               $dataTemp[$kk]["All"] = 0;
               $kk++;
            }
         } else {
            $kk                   = 0;
            $dataTemp[$kk]["id"]  = $_POST["fdate"];
            $dataTemp[$kk]["All"] = 0;
            $kk++;
            $dataTemp[$kk]["id"]  = $_POST["tdate"];
            $dataTemp[$kk]["All"] = 0;
            $kk++;
         }
         //echo "<pre>";
         //echo $_POST["tdate"]."--".$_POST["tdate"]."<br>";
         //print_R($dataTemp);
         //print_R($temp);
         //print_R($return);
         //die;
         $datarow = $dataTemp;
      } else {
         if (!Helper_Common::is_trainer()) {
            if (isset($active_sites) && is_array($active_sites) && count($active_sites) > 0) {
               $condition = 'us.site_id in (' . implode(",", $active_sites) . ')';
            } else {
               $condition = 'us.site_id in (' . $active_sites . ')';
            }
         } else {
            $condition = 'us.site_id in (' . $this->current_site_id . ')';
         }
         if (isset($_POST["by"]) && $_POST["by"] != 4) {
            if ($_POST["by"] == 1) {
               $_POST["fdate"] = Helper_Common::get_default_date();
               $_POST["tdate"] = Helper_Common::get_default_date();
            } elseif ($_POST["by"] == 2) {
               $i = 0;
               while (date("l", strtotime("-$i days")) != $week[$week_sarts_on]) {
                  $i++;
               }
               $from           = date("Y-m-d 00:00:00", strtotime("-$i days"));
               $_POST["fdate"] = date('d/m/Y', strtotime($from));
               $_POST["tdate"] = Helper_Common::get_default_date();
            } elseif ($_POST["by"] == 3) {
               $_POST["fdate"] = Helper_Common::get_default_date('', '01/m/Y');
               $_POST["tdate"] = Helper_Common::get_default_date();
            } elseif ($_POST["by"] == 5) {
               $ii = 0;
               while (date("l", strtotime("-$ii days")) != $week[$week_sarts_on]) {
                  $ii++;
               }
               $ii             = $ii + 7;
               $from           = date("Y-m-d 00:00:00", strtotime("-$ii days"));
               $_POST["fdate"] = date('d/m/Y', strtotime($from));
               $_POST["tdate"] = Helper_Common::get_default_date();
            }
         }
         $var            = $_POST["fdate"];
         $date           = str_replace('/', '-', $var);
         $_POST["fdate"] = date('Y-m-d', strtotime($date));
         $var            = $_POST["tdate"];
         $date           = str_replace('/', '-', $var);
         $_POST["tdate"] = date('Y-m-d', strtotime($date));
         if (isset($_POST) && count($_POST) > 0) {
            if ($_POST["fdate"] && $_POST["tdate"]) {
               //$condition .= " and DATE_FORMAT( u.date_created, '%Y-%m-%d' ) > ".date("Y-m-d", strtotime($_POST["fdate"]) );
               //$condition .= " and DATE_FORMAT( u.date_created, '%Y-%m-%d' ) between ".date("Y-m-d", strtotime($_POST["fdate"]) )." and ". date("Y-m-d", strtotime($_POST["tdate"]) );
               if ($_POST["type"] == "logged_in") {
                  $type = "us.last_login";
               } else if ($_POST["type"] == "subscribers") {
                  $type = "u.date_created";
               }
               if (strlen($condition) > 0)
                  $condition .= ' and ';
               $condition .= " $type between '" . date("Y-m-d 00:00:00", strtotime($_POST["fdate"])) . "' and '" . date("Y-m-d 23:59:59", strtotime($_POST["tdate"])) . "'";
            }
            if ($_POST["status"]) {
               $_POST["status"] = (!is_array($_POST["status"])) ? $_POST["status"] : implode(",", $_POST["status"]);
               if (strlen($condition) > 0)
                  $condition .= ' and ';
               $condition .= '  us.status in (' . $_POST["status"] . ')';
            }
         }
         $temp_users = '';
         if (Helper_Common::is_trainer()) {
            $query  = "
						SELECT
							wsg.wkout_share_id, wsg.wkout_title,wsg.from_wkout, wsg.wkout_id, wsg.user_id,
							concat (u.user_fname,' ',u.user_lname) as username,wsg.created,u.avatarid as profile_img
						FROM
							wkout_share_gendata as wsg
							JOIN wkout_share_seq as wss on wss.wkout_share_id = wsg.wkout_share_id 
							JOIN users as u on u.id=wsg.user_id
						WHERE
							wss.shared_by=" . Auth::instance()->get_user()->pk() . " and wsg.site_id=" . $this->current_site_id . "
						ORDER BY wsg.created desc";
            $query  = DB::query(Database::SELECT, $query);
            $return = $query->execute()->as_array();
            if ($return) {
               foreach ($return as $k => $v) {
                  $temp_users[] = $v["user_id"];
               }
               $temp_users = implode(",", $temp_users);
            }
         }
         if ($this->current_site_id == '1') {
            $qry = "SELECT count( * ) AS cnt,u.id, u.user_gender, DATE_FORMAT( us.last_login, '%Y-%m-%d' ) AS created_date, us.site_id, us.status, us.last_login, us.modified_date, s.name FROM user_sites us JOIN users AS u ON u.id = us.user_id JOIN sites AS s ON s.id = us.site_id JOIN user_status AS ust ON us.status = ust.id WHERE $type!='0000-00-00' and s.is_active = '1' and is_deleted='0' ";
            $qry .= (Helper_Common::is_trainer() && $temp_users != '') ? " and u.id in ($temp_users) " : '';
            $qry .= ($condition) ? "and $condition " : '';
            $qry .= "GROUP BY u.id, u.user_gender, us.site_id , DATE_FORMAT( $type, '%Y%m%d') order by $type asc";
         } else {
            $qry = "SELECT count( * ) AS cnt, u.user_gender, DATE_FORMAT( $type, '%Y-%m-%d' ) AS created_date, us.site_id, us.status, us.last_login, us.modified_date FROM users u  LEFT JOIN user_sites AS us ON u.id = us.user_id LEFT JOIN user_status AS ust ON us.status = ust.id WHERE $type!='0000-00-00' ";
            $qry .= (Helper_Common::is_trainer() && $temp_users != '') ? " and u.id in ($temp_users) " : '';
            $qry .= ($condition) ? "and $condition " : '';
            $qry .= "GROUP BY DATE_FORMAT( $type, '%Y%m%d' ) , u.user_gender order by $type asc";
         }
         //echo $qry;die();
         $query  = DB::query(Database::SELECT, $qry);
         $return = $query->execute()->as_array();
         //$return = (Helper_Common::is_trainer() && $temp_users != '')?$return:false;
         $d      = $temp = $data = array();
         if ($this->current_site_id == '1') {
            if (is_array($return) && count($return) > 0) {
               foreach ($return as $key => $value) {
                  $status = $value["user_gender"];
                  $userid = $value["id"];
                  $site   = $value["name"];
                  $date   = $value["created_date"];
                  if ($date != "0000-00-00") {
                     $returnArray[$date][$site][$userid] = $value;
                  }
               }
            }
         } else {
            if (is_array($return) && count($return) > 0) {
               foreach ($return as $key => $value) {
                  $status = $value["user_gender"];
                  $date   = $value["created_date"];
                  if ($date != "0000-00-00") {
                     if ($status == '1')
                        $returnArray[$date][$status]['Male'] = $value;
                     else
                        $returnArray[$date][$status]['Female'] = $value;
                  }
               }
            }
         }
         $result = array();
         if (isset($returnArray) && is_array($returnArray) && count($returnArray) > 0) {
            $k1 = 0;
            if ($this->current_site_id == '1') {
               foreach ($returnArray as $keys => $values) {
                  $datarow[$k1]['id'] = date("Y-m-d", strtotime($keys));
                  $allcnt             = 0;
                  if (isset($values) && is_array($values) && count($values) > 0) {
                     foreach ($values as $keys1 => $values1) {
                        $datarow[$k1]['site'][$keys1] = count($values1);
                        $allcnt += count($values1);
                     }
                  }
                  $datarow[$k1]['All'] = $allcnt;
                  $k1++;
               }
            } else {
               foreach ($returnArray as $keys => $values) {
                  $datarow[$k1]['id'] = date("Y-m-d", strtotime($keys));
                  $allcnt             = 0;
                  if (isset($values) && is_array($values) && count($values) > 0) {
                     foreach ($values as $keys1 => $values1) {
                        if (isset($values1['Male'])) {
                           $allcnt += $values1['Male']['cnt'];
                           $datarow[$k1]['site']['Male'] = $values1['Male']['cnt'];
                           if (!isset($datarow[$k1]['site']['Female']))
                              $datarow[$k1]['site']['Female'] = '0';
                        }
                        if (isset($values1['Female'])) {
                           $allcnt += $values1['Female']['cnt'];
                           $datarow[$k1]['site']['Female'] = $values1['Female']['cnt'];
                           if (!isset($datarow[$k1]['site']['Male']))
                              $datarow[$k1]['site']['Male'] = '0';
                        }
                     }
                  }
                  $datarow[$k1]['All'] = $allcnt;
                  $k1++;
               }
            }
            /*****Set From To dates************/
            $testdata = array();
            $x        = 0;
            foreach ($datarow as $a => $y) {
               if ($y["id"] != $_POST["fdate"] && $a == 0) {
                  $testdata[$x]['id']  = date("Y-m-d", strtotime($_POST["fdate"]));
                  $testdata[$x]['All'] = 0;
                  $x++;
               }
               $testdata[$x] = $y;
               $x++;
            }
            if ($y["id"] != $_POST["tdate"]) {
               $testdata[$x]['id']  = date("Y-m-d", strtotime($_POST["tdate"]));
               $testdata[$x]['All'] = 0;
            }
            $datarow = $testdata;
         } else {
            $datarow            = array();
            $r                  = 0;
            $datarow[$r]['id']  = date("Y-m-d", strtotime($_POST["fdate"]));
            $datarow[$r]['All'] = 0;
            $r++;
            $datarow[$r]['id']  = date("Y-m-d", strtotime($_POST["tdate"]));
            $datarow[$r]['All'] = 0;
         }
      }
      $data["result"] = $datarow;
      /*
      echo "<pre>";
      print_r($_POST);
      print_r($data);
      print_r($testdata);
      die;
      */
      $this->response->body(json_encode($data));
   }
}
