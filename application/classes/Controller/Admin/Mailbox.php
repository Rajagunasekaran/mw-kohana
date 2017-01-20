<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Mailbox extends Controller_Admin_Website {

	public function before()
	{
		parent::before();
		$user_from = (isset($_GET['user_from']) && $_GET['user_from'] != '' ? $_GET['user_from'] : 'admin');
		Session::instance()->set('user_from',$user_from);
	}
	public function __construct(Request $request, Response $response)
	{
		parent::__construct($request, $response);
	} 
    
	public function action_index(){
		$this->template->title 	= 'Inbox';
		$this->render();
		$mailboxmodel = ORM::factory('admin_mailbox');
		$userid = Auth::instance()->get_user()->pk();
		
		$post = '';
		if(isset($_GET) && count($_GET)>0 ){
			$post = $_GET;
			//print_r($post); die;
		}
		
		$set = 10;
		$list = $mailboxmodel->get_user_message($userid,$post,'',''); $tot = count($list);
		$page = 1;
		$offset=0;
		$lim = $set;
		if(isset($_GET["page"]) && $_GET["page"]){
			$page = $_GET["page"];
			$offset = ($page*$lim)-$lim;
		}
		
		$list = $mailboxmodel->get_user_message($userid,$post,$offset,$lim);
		$cnt = count($list)%$set;
		
		$next = $prev = 0;
		if($page==1){
			$next = $page+1;
		}elseif( ($cnt==count($list)) ){
			$prev = $page-1;
		}else{
			$prev = $page-1;
			$next = $page+1;
		}
		/*
		echo $cnt."=-----$set----------=".count($list);
		echo "<br>";echo ($offset+1)."--".($lim*$page)."------$tot<br>";echo "<br>";
		echo "<br>$prev-------$next";
		die;
		*/
		$this->template->content->offset  = $offset+1;
		$this->template->content->lim  = ($tot<($lim*$page))?$tot:($lim*$page);
		$this->template->content->tot  = $tot;
		$this->template->content->prev = $prev;
		$this->template->content->next = $next;
		
		$this->template->content->mail= $list;
		
	}
	public function action_preview(){
		$this->template->title 	= 'Preview';
		$this->render();
		$mailboxmodel = ORM::factory('admin_mailbox');
		$smtpmodel = ORM::factory('admin_smtp');
		$userid = Auth::instance()->get_user()->pk();
		$mailid = $this->request->param('id');
		$list = $mailboxmodel->get_user_preview_message($mailid,$userid);
		
		
		
		$user = Auth::instance()->get_user();
		
		$this->template->content->mail= $list;
		
		$page = $mailboxmodel->get_prev_next($mailid);
		if(isset($page) && is_array($page) && count($page)>0){
			foreach($page as $k=>$v)	{
				if($v["type"]=="prev"){
					$this->template->content->prev = $v["id"];
				}
				if($v["type"]=="next"){
					$this->template->content->next = $v["id"];
				}
			}
		}
		
		if(isset($_POST) && count($_POST)>0){
			$post = $_POST;
			//echo "<pre>";
			//print_r($user);
			//print_r($post);
			//print_r($list);
			/*********************************Send Mail Box Email to Super Admin Role Users & Site Managers********************/
			$smtpmodel = ORM::factory('admin_smtp');
			$templateArray = $smtpmodel->getSendingMailTemplate(array('type_name' => 'Contact Us','site_id' => $this->current_site_id));
			if(is_array($templateArray) && !empty($templateArray) && !empty($templateArray['smtp_id'])){
				//$templateArray['subject'] = str_replace(array('[Site]'),array($list["name"]),$templateArray['subject']);
				$templateArray['subject'] = "Reg: Contact Support";
				//$templateArray['body'] = str_replace(array('[Site]','[fname]','[lname]','[email]','[phone]','[message]'),array($list["name"],$list["firstname"],$list["lastname"],$list["email"],$list["phone"],$list["message"]),$templateArray['body']);
				$templateArray['body'] = $post["message"];
				//print_r($templateArray);die;
				$messageArray = array('subject'	=> $templateArray['subject'],
										  'from' 	=> (!empty($templateArray['smtp_replyto']) ? $templateArray['smtp_replyto'] : 'info@myworkouts.com'),
										  'fromname'=> (!empty($templateArray['smtp_from']) ? $templateArray['smtp_from'] : 'My workouts'),
										  'to'		=> $list["email"],
										  //'to'      => "prabakaran@versatile-soft.com", 
										  'replyto'	=> (!empty($templateArray['smtp_replyto']) ? $templateArray['smtp_replyto'] : 'info@myworkouts.com'),
										  'toname'	=> ucfirst(strtolower($list['firstname'])).' '.ucfirst(strtolower($list['lastname'])),
										  'body'	=> ORM::factory('admin_smtp')->merge_keywords($templateArray['body'],$this->session->get('current_site_id')),
										  'type'	=> 'text/html');
				$hostAddress = explode("://",$templateArray['smtp_host']);
				$emailMailer = Email::dynamicMailer('smtp',array(
																  'hostname'   => trim($hostAddress['1']), 
																  'port' 	   => $templateArray['smtp_port'], 
																  'username'   => $templateArray['smtp_user'],   
																  'password'   => Helper_Common::decryptPassword($templateArray['smtp_pass']),
																  'encryption' => trim($hostAddress['0'])
																  )
													);
			}else
				$emailMailer = Email::dynamicMailer('',array());
			if( is_array($messageArray)) {
				Email::sendBysmtp($emailMailer,$messageArray); 
			}
			/*********************************Send Mail Box Email to Super Admin Role Users & Site Managers********************/
			$this->session->set('flash_success', 'Mail Sent Successfully...!');
			$this->redirect('admin/mailbox/');
			die;
		}
	}
	public function action_get_unread_message(){
		if ($this->request->is_ajax()){
         $this->auto_render = FALSE;
		}
		$mailboxmodel = ORM::factory('admin_mailbox');
		$userid = Auth::instance()->get_user()->pk();
		$offset = $_POST["lim"];
		$list = $mailboxmodel->get_user_message($userid,'',$offset,5);
		if(isset($list) && is_array($list) && count($list)>0){
			//echo "<pre>";print_r($list);
			$str = '';
			foreach($list as $k=>$v){
				$v["message"] = (strlen($v["message"])>50)?substr($v["message"],0,47):$v["message"];
				$v["message"] = $v["message"]."...";
				$str .="<li class='message-preview'>
					<a href='".URL::base()."admin/mailbox/preview/".$v["contact_id"]."'   >
						 <div class='media'>
							  <span class='pull-left'><i class='fa fa-user fa-3x'></i></span>
							  <div class='media-body'>
									<h5 class='media-heading'><strong>".$v["id"]."#".$v["firstname"]." ".$v["lastname"]."</strong></h5>
									<p class='small text-muted'><i class='fa fa-clock-o'></i> ".Helper_Common::time_ago($v['dated'])."</p>
									<p>".$v["message"]."</p>
							  </div>
						 </div>
					</a>
				</li>";
				//Update Notification read status
				DB::update('sitecontact_mapping')->set(array('notification_read_status' =>1))->where('contact_id', '=', $v["contact_id"])->where('userid', '=', $userid)->execute();
			}
			$str .= "<li class='message-footer'><a href='".URL::base()."admin/mailbox'>".__("Read All New Messages")."</a></li>";
			$offset = $offset+5;
			$str.= "<script type='text/javascript'>$('#notifylim').val('$offset')</script>";
			echo $str;
		}
		exit;
	}
	public function action_move_to_trash(){
		//print_r($_POST);die;
		$data = array();
		if ($this->request->is_ajax()){
         $this->auto_render = FALSE;
		}
		$mailboxmodel = ORM::factory('admin_mailbox');
		$userid = Auth::instance()->get_user()->pk();
		if(isset($_POST["contact_id"])){
			if(is_array($_POST["contact_id"])){
				$contact_id = implode(",",$_POST["contact_id"]);
			}
			$mailboxmodel->move_to_trash($contact_id,$userid);
			$data['success'] = true;
			$data['message'] = 'Mail Deleted Successfully';	
		}
		echo json_encode($data);
		exit;
	}
	public function action_set_unread(){
		//print_r($_POST);die;
		$data = array();
		if ($this->request->is_ajax()){
         $this->auto_render = FALSE;
		}
		$mailboxmodel = ORM::factory('admin_mailbox');
		$userid = Auth::instance()->get_user()->pk();
		if(isset($_POST["contact_id"])){
			if(is_array($_POST["contact_id"])){
				$contact_id = implode(",",$_POST["contact_id"]);
			}
			$mailboxmodel->set_unread($contact_id,$userid);
			$data['success'] = true;
			$data['message'] = 'Mail status updated Successfully';	
		}
		echo json_encode($data);
		exit;
	}
}
