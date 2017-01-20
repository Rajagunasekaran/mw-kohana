<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Networks extends Controller_Website
{
   public function before()
   {
      parent::before();
      $user_from = (isset($_GET['user_from']) && $_GET['user_from'] != '' ? $_GET['user_from'] : 'front');
      Session::instance()->set('user_from', $user_from);
   }
   public function _construct()
   {
      parent::__construct($request, $response);
   }
   public function action_index()
   {
      if (!Auth::instance()->logged_in()) {
         if ($this->request->param('site_name')) {
            $this->redirect(URL::site(NULL, 'http') . 'site/' . $this->request->param('site_name'));
         }
      } else
         $this->redirect(URL::base(true) . "networks/connections");
      
   }
   
   public function action_savechat()
   {
      $datetime      = Helper_Common::get_default_datetime();
      $networksModel = ORM::factory('networks');
      if ($this->request->method() == HTTP_Request::POST) {
         $array         = array();
         $from          = $this->globaluser->pk();
         $to            = $this->request->post("id");
         $msg           = $this->request->post("msg");
         $getReqId_user = $networksModel->get_request_id($to, $from);
         $getReqId      = $networksModel->get_request_id($from, $to);
         if (is_numeric($getReqId)) {
            $array["chat_req_id"]          = $getReqId;
            $array["chat_log_msg"]         = $msg;
            $array["chat_log_read_status"] = '1';
            $array["chat_log_on"]          = $datetime;
            $updateQry                     = "UPDATE `chat_request` SET `is_read`='0' WHERE chat_req_id='" . $getReqId_user . "'";
            DB::query(Database::UPDATE, $updateQry)->execute();
            echo $networksModel->insert("chat_log", $array);
         }
         echo '';
      }
      die;
   }
   
   public function action_get_chats()
   {
      $networksModel  = ORM::factory('networks');
      $adminusermodel = ORM::factory('admin_user');
      if ($this->request->method() == HTTP_Request::POST) {
         $fromuser    = $this->globaluser->pk();
         $touser      = $this->request->post("id");
         $allFlag     = $this->request->post("allFlag");
         $chats       = $networksModel->get_chats($fromuser, $touser, $allFlag);
         $str         = $spt = $chat_div = '';
         $requestflag = true;
         $removeFlag  = false;
         $updateQry   = "UPDATE `chat_request` SET `is_read`='1' WHERE (chat_req_userid='" . $fromuser . "' AND chat_req_to='" . $touser . "')";
         DB::query(Database::UPDATE, $updateQry)->execute();
         if ($chats) {
            $i = 0;
            foreach ($chats as $k => $v) {
               if (empty($v['sender_read_status']) && $fromuser == $v['chat_req_userid']) {
                  $updateQry = "UPDATE `chat_log` SET `sender_read_status`='1' WHERE (chat_req_id='" . $v['chat_req_id'] . "' AND `sender_read_status`='0')";
                  DB::query(Database::UPDATE, $updateQry)->execute();
               } else if (empty($v['receiver_read_status']) && $fromuser == $v['chat_req_to']) {
                  $updateQry = "UPDATE `chat_log` SET `receiver_read_status`='1' WHERE (chat_req_id='" . $v['chat_req_id'] . "' AND `receiver_read_status`='0')";
                  DB::query(Database::UPDATE, $updateQry)->execute();
               }
               $updateQry = "UPDATE `chat_log` SET `chat_log_read_status`='1' WHERE (chat_req_id='" . $v['chat_req_id'] . "')";
               DB::query(Database::UPDATE, $updateQry)->execute();
               $img  = URL::base() . 'assets/img/user_placeholder.png';
               $time = Helper_Common::get_default_time($v['chat_log_on'], 'h:i A');
               $msg  = $v["chat_log_msg"];
               $name = $chat_class = '';
               if ($v["chat_log_type"] == 1) {
                  if ($touser == $v["chat_req_userid"]) {
                     $chat_class = "left";
                     
                  } elseif ($touser == $v["chat_req_to"]) {
                     $chat_class = "right";
                  }
                  if (isset($v["fromuserimg"]) && $v["fromuserimg"] != "") {
                     $getImg = $adminusermodel->get_users_profile_image($v["fromuserimg"]);
                     if (file_exists($getImg["img_url"])) {
                        $img = URL::base() . $getImg["img_url"];
                     }
                  }
                  $name = ucfirst($v["fromuser"]);
               } elseif ($v["chat_log_type"] == 0) {
                  $from_user = $networksModel->get_user_details($fromuser);
                  $to_user   = $networksModel->get_user_details($touser);
                  $smm       = explode('#@#', $v["chat_log_msg"]);
                  if ($v["chat_log_msg"] == "chat_connected") {
                     $msg = "<b>" . ucfirst($to_user['user_fname'] . " " . $to_user['user_lname']) . "</b> has connect with <b>" . ucfirst($from_user['user_fname'] . " " . $from_user['user_lname']) . "</b>";
                  } elseif ($smm[0] == "chat_declined") {
                     $from_user   = $networksModel->get_user_details($fromuser);
                     $requestflag = false;
                     $msg         = "Your request was declined ";
                     if (($v["chat_req_status"] == 3) || ($v["chat_req_status"] == 2 && $v["chat_log_resend"] == 1)) {
                        $msg .= "<a href='javascript:void(0);' onclick=\"send_request(" . $touser . ")\" >Resend</a>";
                     }
                  } else {
                     $mm = explode('#@#', $v["chat_log_msg"]);
                     if ($mm[0] == "chat_request") {
                        $requestflag = false;
                        $removeFlag  = true;
                        $from_user   = $networksModel->get_user_details($fromuser);
                        $msg         = "<b>" . ucfirst($from_user['user_fname'] . " " . $from_user['user_lname']) . "</b> would like to connect<br>" . $mm[1];
                     } else if ($mm[0] == "request_sent") {
                        $requestflag = false;
                        $removeFlag  = true;
                        $msg         = "Connect request sent ";
                        if ($v["chat_req_status"] != 1 && $v["chat_req_status"] == 2 && $v["chat_log_resend"] == 1) {
                           $msg .= "- <a href='javascript:void(0);' onclick=\"send_request(" . $mm[1] . ")\" >Resend Connect Request</a>";
                        }
                     }
                  }
                  if ($fromuser == $v["chat_req_to"] && $touser == $v["chat_req_userid"]) {
                     $chat_class = 'left';
                     if (isset($v["fromuserimg"]) && $v["fromuserimg"] != "") {
                        $getImg = $adminusermodel->get_users_profile_image($v["fromuserimg"]);
                        if (file_exists($getImg["img_url"])) {
                           $img = URL::base() . $getImg["img_url"];
                        }
                     }
                     $name = ucfirst($v["fromuser"]);
                  } else {
                     $chat_class = 'right';
                  }
                  if ($v["chat_req_status"] == 0 && $fromuser == $v["chat_req_to"]) {
                     $x   = $i - 1;
                     $spt = "<br>
								<div class='btn-$i'>
								<input type='button' class='btn btn-xs btn-primary' value='Accept' onclick='request_ack(1," . $fromuser . "," . $touser . ")'>
								<input type='button' class='btn btn-xs btn-danger' value='Decline' onclick='request_ack(3," . $fromuser . "," . $touser . ")'>
								</div>
								<script type='text/javascript'>
								$('.btn-$x').remove();
								</script>
							";
                     
                  }
                  
               }
               if ($name != '' && $msg != '') {
                  $str .= "<li class='$chat_class clearfix'>
							<span class='chat-img pull-$chat_class'><img src='$img' alt='$name'></span>
							<div class='chat-body clearfix'>
								<div class='header'><strong class='primary-font'>$name</strong><small class='pull-right text-muted'><i class='fa fa-clock-o hide'></i>$time</small></div>
								<p>$msg $spt</p></div></li>";
               }
               $i++;
            }
            $chat_div = $v["chat_req_status"];
         }
         
         $ch_res             = ($allFlag == true && empty($spt) && ($requestflag) ? '1' : $chat_div);
         $temp["content"]    = $str;
         $temp["removeflag"] = ($removeFlag ? '1' : '0');
         $temp["chat"]       = ($ch_res != 1) ? 0 : $ch_res;
         echo json_encode($temp);
      }
      die;
   }
   
   public function action_searchparticularuser()
   {
      if ($this->request->method() == HTTP_Request::POST) {
         $id             = $this->request->post("search");
         $networksModel  = ORM::factory('networks');
         $adminusermodel = ORM::factory('admin_user');
         $user           = $networksModel->get_particular_users_only($id);
         $name           = ($user["firstname"]) ? $user["firstname"] : '';
         $name .= " ";
         $name .= ($user["lastname"]) ? $user["lastname"] : '';
         $name      = ucfirst($name);
         $sub_title = $user["background"];
         $img       = URL::base() . 'assets/img/user_placeholder.png';
         if (isset($user["profile_img"]) && $user["profile_img"] != "") {
            $getImg = $adminusermodel->get_users_profile_image($user["profile_img"]);
            if (file_exists($getImg["img_url"])) {
               $img = URL::base() . $getImg["img_url"];
            }
         }
         $concat = $id . "#@#$name#@#$img#@#$sub_title";
         echo $concat;
      }
      die;
   }
   
   public function action_searchuser()
   {
      $str            = '';
      $networksModel  = ORM::factory('networks');
      $adminusermodel = ORM::factory('admin_user');
      $userid         = $this->globaluser->pk();
      $site_id        = $this->session->get('current_site_id');
      $redirectFlag   = $redirectFlagCalback = 'notfound';
      if ($this->request->method() == HTTP_Request::POST) {
         $search = $this->request->post("search");
         $touser = $this->request->post("to");
         $role   = $this->request->post("role");
         if (is_array($role) && count($role) > 0) {
            $role = implode(",", $role);
         }
         
         $redirectFlag = (!empty($touser) ? 'found' : 'notfound');
         $sites        = $networksModel->get_network_user_sites($userid);
         if (!empty($sites)) {
            $user  = array();
            $users = $networksModel->get_network_users_only($userid, $search, $role);
            if (isset($users) && is_array($users) && count($users) > 0) {
               foreach ($users as $k => $v) {
                  if ($redirectFlagCalback == 'notfound')
                     $redirectFlagCalback = (!empty($touser) && $touser == $v["userid"] ? 'found' : 'notfound');
                  $user[] = $v["chat_req_userid"] . '#@#' . $v["chat_req_to"];
                  $img    = URL::base() . 'assets/img/user_placeholder.png';
                  if (isset($v["profile_img"]) && $v["profile_img"] != "") {
                     $getImg = $adminusermodel->get_users_profile_image($v["profile_img"]);
                     if (file_exists($getImg["img_url"])) {
                        $img = URL::base() . $getImg["img_url"];
                     }
                  }
                  $name = ($v["firstname"]) ? $v["firstname"] : '';
                  $name .= " ";
                  $name .= ($v["lastname"]) ? $v["lastname"] : '';
                  $name      = ucfirst($name);
                  $sub_title = ($v["background"] != '' && strlen($v["background"]) > 25) ? substr($v["background"], 0, 25) . "..." : $v["background"];
                  $concat    = $v["userid"] . "#@#$name#@#$img#@#$sub_title";
                  $times     = (isset($v['chat_req_on'])) ? Helper_Common::time_ago($v['chat_req_on']) : '';
                  $newcnt    = 0;
                  if (empty($v['is_read'])) {
                     $newcnt = $networksModel->get_network_users_unread_count($userid, $v["userid"]);
                  }
                  $str .= '<li class="bounceInDown ' . (!empty($newcnt) ? 'activenew' : '') . '" id="row_' . $v["userid"] . '">
									<a href="javascript:void(0);" onclick="get_request_chats(' . $v["userid"] . ',\'' . $concat . '\',this)" data-disable="' . ($touser == $v["userid"] ? 1 : 0) . '" class="clearfix">
										<img src="' . $img . '" alt="" class="img-circle">
										<div class="friend-name"><strong>' . $name . '</strong></div>
										<div class="last-message text-muted">' . $sub_title . '</div>
										<!--small class="time text-muted">' . $times . '</small-->
										<small class="chat-alert label label-danger">' . (!empty($newcnt) ? $newcnt : '') . '</small>
									</a>
								</li>';
               }
            }
            
            $users = $networksModel->get_network_searchusers($sites["siteid"], $role, $search);
            if (isset($users) && is_array($users) && count($users) > 0 && $search != '') {
               foreach ($users as $k => $v) {
                  if ($redirectFlagCalback == 'notfound')
                     $redirectFlagCalback = (!empty($touser) && $touser == $v["userid"] ? 'found' : 'notfound');
                  $img = URL::base() . 'assets/img/user_placeholder.png';
                  if (isset($v["profile_img"]) && $v["profile_img"] != "") {
                     $getImg = $adminusermodel->get_users_profile_image($v["profile_img"]);
                     if (file_exists($getImg["img_url"])) {
                        $img = URL::base() . $getImg["img_url"];
                     }
                  }
                  $name = ($v["firstname"]) ? $v["firstname"] : '';
                  $name .= " ";
                  $name .= ($v["lastname"]) ? $v["lastname"] : '';
                  $name      = ucfirst($name);
                  $sub_title = ($v["background"] != '' && strlen($v["background"]) > 25) ? substr($v["background"], 0, 25) . "..." : $v["background"];
                  $times     = (isset($v['chat_req_on'])) ? Helper_Common::time_ago($v['chat_req_on']) : '';
                  if ($userid != $v["userid"] && !in_array($userid . '#@#' . $v["userid"], $user)) {
                     $str .= '<li class="bounceInDown" id="row_' . $v["userid"] . '">
								<a href="javascript:void(0);" onclick="send_request(' . $v["userid"] . ')" class="clearfix">
									<img src="' . $img . '" alt="" class="img-circle">
									<div class="friend-name"><strong>' . $name . '</strong></div>
									<div class="last-message text-muted">' . $sub_title . '</div>
									<small class="chat-alert label"><i class="fa fa-user-plus" id="user_' . $v["userid"] . '" ></i></small>
								</a>
							</li>';
                  }
               }
            } else {
               if (strlen($str) == 0) {
                  if (trim($search) != '')
                     $str .= "<li class=\"bounceInDown\">No results found for \"$search\"</li>";
                  else
                     $str .= "<li class=\"bounceInDown\">No data found</li>";
               }
            }
         }
      } else {
         if (trim($search) != '')
            $str .= "<li class=\"bounceInDown\">No data results found for \"$search\"</li>";
         else
            $str .= "<li class=\"bounceInDown\">No data found</li>";
      }
      if ($redirectFlag != $redirectFlagCalback)
         echo 'redirect';
      else
         echo $str;
      die;
   }
   public function action_acknowledgerequest()
   {
      $networksModel = ORM::factory('networks');
      $datetime      = Helper_Common::get_default_datetime();
      if ($this->request->method() == HTTP_Request::POST) {
         $post       = $this->request->post();
         $req_status = $post["type"];
         $from       = $post["from"];
         $to         = $post["to"];
         if ($req_status == 1) {
            $res = $networksModel->update_request($req_status, $from, $to);
            if ($res) {
               $getReqId = $networksModel->get_request_id($from, $to);
               if (is_numeric($getReqId)) {
                  $updateQry = "UPDATE `chat_log` SET `receiver_read_status`='0' WHERE chat_req_id='" . $getReqId . "'";
                  DB::query(Database::UPDATE, $updateQry)->execute();
                  $array                  = array();
                  $array["chat_req_id"]   = $getReqId;
                  $array["chat_log_type"] = 0;
                  $array["chat_log_msg"]  = 'chat_connected';
                  $array["chat_log_on"]   = $datetime;
                  $result                 = $networksModel->insert("chat_log", $array);
               }
               
            }
            $res1 = $networksModel->update_request($req_status, $to, $from);
            if ($res1) {
               $getReqId = $networksModel->get_request_id($to, $from);
               if (is_numeric($getReqId)) {
                  $array                  = array();
                  $array["chat_req_id"]   = $getReqId;
                  $array["chat_log_type"] = 0;
                  $array["chat_log_msg"]  = 'chat_connected';
                  $array["chat_log_on"]   = $datetime;
                  $result                 = $networksModel->insert("chat_log", $array);
               }
            }
            die;
         } elseif ($req_status == 3) {
            $res1 = $networksModel->update_request($req_status, $to, $from);
            if ($res1) {
               $getReqId = $networksModel->get_request_id($from, $to);
               if (is_numeric($getReqId)) {
                  $networksModel->update_resend($from, $to);
                  $array                    = array();
                  $array["chat_req_id"]     = $getReqId;
                  $array["chat_log_msg"]    = 'chat_declined#@#' . $from;
                  $array["chat_log_on"]     = $datetime;
                  $array["chat_log_type"]   = 0;
                  $array["chat_log_resend"] = 1;
                  $result                   = $networksModel->insert("chat_log", $array);
               }
            }
         }
      }
      die;
   }
   public function action_sendrequest()
   {
      $networksModel = ORM::factory('networks');
      $smtpmodel     = ORM::factory('admin_smtp');
      if ($this->request->method() == HTTP_Request::POST) {
         $post                     = $this->request->post();
         $curUserId                = $this->globaluser->pk();
         $datetime                 = Helper_Common::get_default_datetime();
         $array                    = array();
         $array["chat_req_userid"] = $curUserId;
         $array["chat_req_to"]     = $post["reqto"];
         $array["chat_req_msg"]    = $post["msg"];
         $array["chat_req_on"]     = $datetime;
         $array["chat_req_status"] = 0;
         $cres                     = $networksModel->check_request("chat_request", $array["chat_req_userid"], $array["chat_req_to"]);
         if (!$cres)
            $result = $networksModel->insert("chat_request", $array);
         else
            $result = $networksModel->update("chat_request", $array);
         $array                    = array();
         $array["chat_req_userid"] = $post["reqto"];
         $array["chat_req_to"]     = $curUserId;
         $array["chat_req_msg"]    = $post["msg"];
         $array["chat_req_on"]     = $datetime;
         $array["chat_req_status"] = 2;
         $cres                     = $networksModel->check_request("chat_request", $array["chat_req_userid"], $array["chat_req_to"]);
         if (!$cres)
            $result = $networksModel->insert("chat_request", $array);
         else
            $result = $networksModel->update("chat_request", $array);
         
         $from_user              = $networksModel->get_user_details($curUserId);
         $user                   = $networksModel->get_user_details($post["reqto"]);
         $getReqId               = $networksModel->get_request_id($curUserId, $post["reqto"]);
         $array                  = array();
         $array["chat_req_id"]   = $getReqId;
         $array["chat_log_msg"]  = "chat_request#@#" . $post["msg"];
         $array["chat_log_on"]   = $datetime;
         $array["chat_log_type"] = 0;
         $result                 = $networksModel->insert("chat_log", $array);
         
         $networksModel->update_resend($post["reqto"], $curUserId);
         $array                    = array();
         $getReqId                 = $networksModel->get_request_id($post["reqto"], $curUserId);
         $array["chat_req_id"]     = $getReqId;
         $array["chat_log_msg"]    = "request_sent#@#" . $post["reqto"];
         $array["chat_log_on"]     = $datetime;
         $array["chat_log_resend"] = 1;
         $array["chat_log_type"]   = 0;
         $result                   = $networksModel->insert("chat_log", $array);
         
         $site_id = $this->session->get('current_site_id');
         if (!empty($from_user['user_email']) && !empty($user['user_email'])) {
            $templateArray = $smtpmodel->getSendingMailTemplate(array(
               'type_name' => 'Chat Request',
               'site_id' => $site_id
            ));
            if (is_array($templateArray) && !empty($templateArray) && !empty($templateArray['smtp_id'])) {
               $message               = $array["chat_log_msg"];
               $templateArray['body'] = str_replace(array(
                  '[FirstName]',
                  '[Message]'
               ), array(
                  ucfirst(strtolower($user['user_fname'])),
                  $message
               ), $templateArray['body']);
               $messageArray          = array(
                  'subject' => $templateArray['subject'],
                  'from' => (!empty($templateArray['smtp_replyto']) ? $templateArray['smtp_replyto'] : 'info@myworkouts.com'),
                  'fromname' => (!empty($templateArray['smtp_from']) ? $templateArray['smtp_from'] : 'My workouts'),
                  'to' => $user['user_email'],
                  'replyto' => (!empty($templateArray['smtp_replyto']) ? $templateArray['smtp_replyto'] : 'info@myworkouts.com'),
                  'toname' => ucfirst(strtolower($user['user_fname'])) . ' ' . ucfirst(strtolower($user['user_lname'])),
                  'body' => $smtpmodel->merge_keywords($templateArray['body'], $site_id),
                  'type' => 'text/html'
               );
               $hostAddress           = explode("://", $templateArray['smtp_host']);
               $emailMailer           = Email::dynamicMailer('smtp', array(
                  'hostname' => trim($hostAddress['1']),
                  'port' => $templateArray['smtp_port'],
                  'username' => $templateArray['smtp_user'],
                  'password' => Helper_Common::decryptPassword($templateArray['smtp_pass']),
                  'encryption' => trim($hostAddress['0'])
               ));
            } else {
               $emailMailer = Email::dynamicMailer('', array());
            }
            if (isset($messageArray) && is_array($messageArray)) {
               if (Email::sendBysmtp($emailMailer, $messageArray)) {
                  echo true;
               }
            }
         }
      }
   }
   public function action_connections()
   {
      $networksModel  = ORM::factory('networks');
      $adminusermodel = ORM::factory('admin_user');
      if (!Auth::instance()->logged_in()) {
         if ($this->request->param('site_name')) {
            $this->redirect(URL::site(NULL, 'http') . 'site/' . $this->request->param('site_name'));
         }
      }
      $userid = $this->globaluser->pk();
      $touser = urldecode($this->request->param('id'));
      $this->render();
      if (!empty($touser) && is_numeric($touser)) {
         $fromuser    = $this->globaluser->pk();
         $allFlag     = $this->request->post("allFlag");
         $chats       = $networksModel->get_chats($fromuser, $touser, $allFlag);
         $str         = $spt = $chat_div = '';
         $requestflag = true;
         $removeFlag  = false;
         $updateQry   = "UPDATE `chat_request` SET `is_read`='1' WHERE (chat_req_userid='" . $fromuser . "' AND chat_req_to='" . $touser . "')";
         DB::query(Database::UPDATE, $updateQry)->execute();
         if ($chats) {
            $i = 0;
            foreach ($chats as $k => $v) {
               if (empty($v['sender_read_status']) && $fromuser == $v['chat_req_userid']) {
                  $updateQry = "UPDATE `chat_log` SET `sender_read_status`='1' WHERE (chat_req_id='" . $v['chat_req_id'] . "' AND `sender_read_status`='0')";
                  DB::query(Database::UPDATE, $updateQry)->execute();
               } else if (empty($v['receiver_read_status']) && $fromuser == $v['chat_req_to']) {
                  $updateQry = "UPDATE `chat_log` SET `receiver_read_status`='1' WHERE (chat_req_id='" . $v['chat_req_id'] . "' AND `receiver_read_status`='0')";
                  DB::query(Database::UPDATE, $updateQry)->execute();
               }
               $updateQry = "UPDATE `chat_log` SET `chat_log_read_status`='1' WHERE (chat_req_id='" . $v['chat_req_id'] . "')";
               DB::query(Database::UPDATE, $updateQry)->execute();
               $img  = URL::base() . 'assets/img/user_placeholder.png';
               $time = Helper_Common::get_default_time($v['chat_log_on'], 'h:i A');
               $msg  = $v["chat_log_msg"];
               $name = $chat_class = '';
               if ($v["chat_log_type"] == 1) {
                  if ($touser == $v["chat_req_userid"]) {
                     $chat_class = "left";
                     
                  } elseif ($touser == $v["chat_req_to"]) {
                     $chat_class = "right";
                  }
                  if (isset($v["fromuserimg"]) && $v["fromuserimg"] != "") {
                     $getImg = $adminusermodel->get_users_profile_image($v["fromuserimg"]);
                     if (file_exists($getImg["img_url"])) {
                        $img = URL::base() . $getImg["img_url"];
                     }
                  }
                  $name = ucfirst($v["fromuser"]);
               } elseif ($v["chat_log_type"] == 0) {
                  $from_user = $networksModel->get_user_details($fromuser);
                  $to_user   = $networksModel->get_user_details($touser);
                  
                  $smm = explode('#@#', $v["chat_log_msg"]);
                  
                  if ($v["chat_log_msg"] == "chat_connected") {
                     $msg = "<b>" . ucfirst($to_user['user_fname'] . " " . $to_user['user_lname']) . "</b> has connect with <b>" . ucfirst($from_user['user_fname'] . " " . $from_user['user_lname']) . "</b>";
                  } elseif ($smm[0] == "chat_declined") {
                     $from_user   = $networksModel->get_user_details($fromuser);
                     $requestflag = false;
                     $msg         = "Your request was declined";
                     if (($v["chat_req_status"] == 3) || ($v["chat_req_status"] == 2 && $v["chat_log_resend"] == 1)) {
                        $msg .= "<a href='javascript:void(0);' onclick=\"send_request(" . $touser . ")\" >Resend</a>";
                     }
                  } else {
                     $mm = explode('#@#', $v["chat_log_msg"]);
                     if ($mm[0] == "chat_request") {
                        $requestflag = false;
                        $removeFlag  = true;
                        $from_user   = $networksModel->get_user_details($fromuser);
                        $msg         = "<b>" . ucfirst($from_user['user_fname'] . " " . $from_user['user_lname']) . "</b> would like to connect<br>" . $mm[1];
                     } else if ($mm[0] == "request_sent") {
                        $requestflag = false;
                        $removeFlag  = true;
                        $msg         = "Connect request sent ";
                        if ($v["chat_req_status"] != 1 && $v["chat_req_status"] != 3 && $v["chat_log_resend"] == 1) {
                           $msg .= "- <a href='javascript:void(0);' onclick=\"send_request(" . $mm[1] . ")\" >Resend Connect Request</a>";
                        }
                     }
                  }
                  if ($fromuser == $v["chat_req_to"] && $touser == $v["chat_req_userid"]) {
                     $chat_class = 'left';
                     if (isset($v["fromuserimg"]) && $v["fromuserimg"] != "") {
                        $getImg = $adminusermodel->get_users_profile_image($v["fromuserimg"]);
                        if (file_exists($getImg["img_url"])) {
                           $img = URL::base() . $getImg["img_url"];
                        }
                     }
                     $name = ucfirst($v["fromuser"]);
                     
                     
                  } else {
                     $chat_class = 'right';
                  }
                  if ($v["chat_req_status"] == 0 && $fromuser == $v["chat_req_to"]) {
                     $x   = $i - 1;
                     $spt = "<br>
								<div class='btn-$i'>
								<input type='button' class='btn btn-xs btn-primary' value='Accept' onclick='request_ack(1," . $fromuser . "," . $touser . ")'>
								<input type='button' class='btn btn-xs btn-danger' value='Decline' onclick='request_ack(3," . $fromuser . "," . $touser . ")'>
								</div>
								<script type='text/javascript'>
								$('.btn-$x').remove();
								</script>
							";
                     
                  }
                  
               }
               if ($name != '' && $msg != '') {
                  $str .= "<li class='$chat_class clearfix'>
							<span class='chat-img pull-$chat_class'><img src='$img' alt='$name'></span>
							<div class='chat-body clearfix'>
								<div class='header'><strong class='primary-font'>$name</strong><small class='pull-right text-muted'><i class='fa fa-clock-o hide'></i>$time</small></div>
								<p>$msg $spt</p></div></li>";
               }
               $i++;
            }
            $chat_div = $v["chat_req_status"];
         }
         
         
         $ch_res = ($allFlag == 'true' && empty($spt) && ($requestflag) ? '1' : $chat_div);
         
         $this->template->title                  = 'Connections - user';
         $this->template->content->param         = $touser;
         $this->template->content->touserdetails = $networksModel->get_user_details($touser);
         $this->template->content->userchat      = $str;
         $this->template->content->removeflag    = ($removeFlag ? '1' : '0');
         $this->template->content->chat          = ($ch_res != 1) ? 0 : $ch_res;
      } else {
         $this->template->title             = 'Connections';
         $this->template->content->param    = '';
         $role                              = '2,8,7';
         $res                               = $networksModel->get_network_users_only($userid, '', $role);
         $this->template->content->userdata = ($res) ? $res : '';
      }
   }
}
