<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Ajax extends Controller {

	public function before()
	{
		parent::before();
		$uf = (isset($_GET['user_from']) && $_GET['user_from'] != '' ? $_GET['user_from'] : 'front');
		$cp	= (isset($_GET['cp']) && $_GET['cp'] != '' ? $_GET['cp'] : '1');
		Session::instance()->set('user_from',$uf);
		Session::instance()->set('user_allow_page',$cp);
	}
	public function __construct(Request $request, Response $response)
	{
		parent::__construct($request, $response);
	}
	public function action_profile()
	{
		$method			= Arr::get($_GET, 'method');
		$data		    = Arr::get($_GET, 'data');
		$user 			= Auth::instance()->get_user();
		if(!empty($method) && trim($method) == 'cancel' && $user->pk()){
			//$userModel 	 = ORM::factory('user');
			echo 'success';
			die();
		}
	}
	public function action_refresh()
	{	
		$activityModel 	= ORM::factory('activityfeed');
		$datetime 		= Helper_Common::get_default_datetime();
		Session::instance()->regenerate();
		if(Auth::instance()->logged_in()){
			$user 			= Auth::instance()->get_user();
			$activity_feed  = array();
			if ($user->pk()) {
				/*********************Activiy Feed**********************/
				$activity_feed["feed_type"]   	= 14;  // This get from feed_type table
				$activity_feed["action_type"]  	= 30; // This get from action_type table  
				$activity_feed["type_id"]    	= $user->pk(); 
				$activity_feed["created_date"]  = $datetime;
				$activity_feed["modified_date"] = $datetime;
				$activity_feed["user"]    		= $user->pk();
				$activity_feed["site_id"]  = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
				$activity_result = DB::insert('activity_feed', array_keys($activity_feed) )->values(array_values($activity_feed))->execute();
				/******************* Activity Feed *********************/
				echo 'true';
			}else
				echo 'false';
		}else
			echo 'false';
		die();
	}
	public function action_updateAddtoHome()
	{
		$action			= Arr::get($_GET, 'action');
		$site_id  		= Session::instance()->get('current_site_id');
		$user = Auth::instance()->get_user();
		$workoutModel 	= ORM::factory('workouts');
		if(!empty($action) && trim($action) == 'addtohome' && $user->pk() && !empty($site_id)){
			$workoutModel->insertAddToHomeEnd($site_id, $user->pk());
			$this->response->body('success');
		}
	}
	public function action_resnedactivation(){
		$usermodel = ORM::factory('user');
		$smtpmodel = ORM::factory('admin_smtp');
		$user = array();
		$session  	= Session::instance();
		$site_id 	= $session->get('current_site_id');
		$usermail 	= $session->get_once('user_email');
		if(!empty($usermail)){
			$userunreg  = $usermodel->get_user_details($usermail,$site_id);
			$currentDate	= Helper_Common::get_default_datetime();	
			$user['user_email']		= $userunreg['user_email'];
			$user['user_id']		= $userunreg['user_id'];
			$user['user_site_id']	= $userunreg['site_id'];
			$user['activation_code']= md5(microtime().rand());
			$user['date_created'] 	= $currentDate;
			$user['user_fname']		= $userunreg['user_fname'];
			$user['user_lname']		= $userunreg['user_lname'];
			
			/******************* Activity Feed *********************/
			$activity_feed = array();
			$activity_feed["feed_type"]   	= 33;
			$activity_feed["action_type"]  	= 47;
			$activity_feed["type_id"]    	= $activity_feed["user"]  = $user['user_id'];
			$activity_feed["site_id"]  		= $site_id;
			Helper_Common::createActivityFeed($activity_feed);
			/******************* Activity Feed *********************/
			$useractivation = '<a href="'.URL::site(NULL, 'http').'site/'.$session->get('current_site_slug').'/page/activate/'.$user['activation_code'].'?redo=1'.'" target="_blank" title="Click here to activate your account" style="color: #1b9af7;">Click here to activate your account</a>';
			if(!empty($user['user_email'])){
				DB::update('users')->set(array('activation_code' => $user['activation_code'],'last_updated' => $user['date_created']))->where('user_email', '=', $user['user_email'])->execute();
				$templateArray = $smtpmodel->getSendingMailTemplate(array('type_name' => 'Register','site_id'=>$site_id));
				if(is_array($templateArray) && !empty($templateArray) && !empty($templateArray['smtp_id'])){
					$templateArray['body'] = str_replace(array('[FirstName]','[ActivationLink]'), array(ucfirst(strtolower($user['user_fname'])), $useractivation), $templateArray['body']);
					$messageArray = array('subject'	=> $templateArray['subject'],
						'from' 	=> (!empty($templateArray['smtp_replyto']) ? $templateArray['smtp_replyto'] : 'info@myworkouts.com'),
						'fromname'=> (!empty($templateArray['smtp_from']) ? $templateArray['smtp_from'] : 'My workouts'),
						'to'		=> $user['user_email'],
						'replyto'=> (!empty($templateArray['smtp_replyto']) ? $templateArray['smtp_replyto'] : 'info@myworkouts.com'),
						'toname'	=> ucfirst(strtolower($user['user_fname'])).' '.ucfirst(strtolower($user['user_lname'])),
						'body'	=> $smtpmodel->merge_keywords($templateArray['body'], $site_id),
						'type'	=> 'text/html');
					$hostAddress = explode("://",$templateArray['smtp_host']);
					$emailMailer = Email::dynamicMailer('smtp',array(
						'hostname'   => trim($hostAddress['1']), 
						'port' 	    => $templateArray['smtp_port'], 
						'username'   => $templateArray['smtp_user'],   
						'password'   => Helper_Common::decryptPassword($templateArray['smtp_pass']),
						'encryption' => trim($hostAddress['0'])
						)
					);
				}else{
					$emailMailer = Email::dynamicMailer('',array());
				}
				if(isset($messageArray) && is_array($messageArray)) {
					if(Email::sendBysmtp($emailMailer,$messageArray)){
						echo true;
						Session::instance()->set('flash_resendactivation_popup',false);
						Session::instance()->set('flash_success_popup', 'A registration activation link has been resent to the email address you provided. This activation code will expire in 24 hours.');
					}
				}
			}
		}
		$this->redirect('index');
		echo false;
		die;
	}
	public function action_dologin(){
		$response 	= $data = array();
		$session  	= Session::instance();
		$usermodel 	= ORM::factory('user');
		$siteModel 	= ORM::factory('site');
		$hompage 	= ORM::factory('homepage');
		$session->set('user_from', 'front');
		if (!Helper_Common::isCookieEnable()) {
			$response['success'] = false;
			$response['redirect'] = '?cookie=0&form=login';
		}else{
			if (HTTP_Request::POST == $this->request->method()){
				$site_id  	= $session->get('current_site_id');
				if($site_id ==''){
					$slug_id 	 = $this->request->post('siteId');
					$sites 		 = $hompage->get_sitesby_id($slug_id);
					$site_id 	 = $sites["id"];
					$session->set('current_site_id', $site_id);
					$session->set('current_site_name', $sites['name']);
					$session->set('current_site_slug', $sites['slug']);
					$session->set('current_site_agelimit', $sites['min_agelimit']);
					$random = rand(1111111111,9999999999);
					$session->set('loginack',$random);
				}
				$loginack	= $this->request->post('loginack');
				$site_id  	= $session->get('current_site_id');
				$sitename 	= ($session->get('current_site_slug') ? $session->get('current_site_slug').'/' : '');
				$loginackpost = $this->request->post('loginack');
				if(strcmp($loginack,$loginackpost) != 0){
					$response['success'] = false;
					$session->set('common_error', 'Authentication Key Invalid');
					$response['redirect'] = URL::base().'site/' . $sitename;
				}else{
					$remember  = (bool) $this->request->post('remember');
					$validator = $usermodel->validate_user_login(arr::extract($_POST, array(
						'user_email',
						'password'
					)));
					if ($validator->check()) {
						$user = Auth::instance()->login($this->request->post('user_email'), $this->request->post('password'), $remember);
						// If successful, redirect user
						if (isset($user) && !empty($user)) {
							$user_id      = Auth::instance()->get_user()->id;
							$site_idvalue = $session->get('current_site_id');
							$usermodel->updateSiteIdbyUser($site_idvalue, $user_id);
							// update to user_sites
							$update_str = 'last_login=now()';
							$condtn_str = 'user_id=' . $user_id . ' and site_id=' . $site_id;
							$usermodel->updateUserSites($update_str, $condtn_str);
							$list = $siteModel->checkanswers($site_id, $user_id);
							$session->set('newly_loggedin',$user_id) ;
							$response['redirect'] = URL::base().'site/' . $sitename . '/questions';
							$response['success'] 		= true;
							if (isset($list) && is_array($list) && count($list) > 0)
								$response['redirect'] = URL::base(true). 'dashboard/index';
						} else {
							$userunreg = $usermodel->get_user_details($this->request->post('user_email'),$site_id);
							if(isset($userunreg) && $userunreg["user_access"]==6){
								$session->set("flash_resendactivation_popup",true);
							}else{
								$session->set('common_error', 'Username or password is invalid');
							}
							$session->set("user_email",$this->request->post('user_email'));
							$response['success'] 		= false;
							$response['redirect'] 		= URL::base().'site/' . $sitename;
						}
					} else {
						//mdh check for user site
						if($session->get('changeto_site_id') != ''){
							$idsite=$session->get('changeto_site_id') ;
							$siteinfodetails  = $hompage->get_sitesby_id($idsite);
							$session->set('current_site_id',$siteinfodetails['id']) ;
							$session->set('current_site_slug',$siteinfodetails['slug']) ;
							$session->set('current_site_name',$siteinfodetails['name']) ;
							$siteallsettings = Helper_Common::selectgeneralfn('site_settings','*','site_id="'.$idsite.'"');
							$language_details = Helper_Common::getlanguage(isset($siteallsettings[0]['language']) ? $siteallsettings[0]['language'] : 1 );
							if ($language_details != null)
								$session->set('lang', $language_details);
							else
								$session->set('lang', 'en');
							I18n::lang('front-'.$idsite.'-'.$session->get('lang').'-'.strtolower(Request::current()->directory()).'-'.strtolower(Request::current()->controller()));
							$response['success'] 		= false;
							$response['redirect'] 		= URL::base().'site/' . $sitename;
						}else{
							$data['error_messages'] = $validator->errors('errors/en');
						}
					}
					if (isset($data['error_messages']) && !empty($data['error_messages'])) {
						$response['success'] 		= false;
						foreach ($data['error_messages'] as $keys => $value) {
							$session->set($keys . '_header_error', $value);
						}
						$session->set('flush_user_email',$this->request->post('user_email'));
						$response['redirect'] 		= URL::base().'site/' . $sitename;
					}
				}
			}
		}
		$this->response->body(json_encode($response));
	}
	public function action_wkoutorder()
	{
		$responseArray  = array();
		$action			= Arr::get($_POST, 'action');
		$data			= Arr::get($_POST, 'data');
		$parentid       = Arr::get($_POST, 'parentid');
		$user = Auth::instance()->get_user();
		if(!empty($action) && trim($action) == 'seq_order' && $user->pk()){
			$workoutModel 	 = ORM::factory('workouts');
			// itemworkout/folder_13_14_1  => type_id_workoutassignid_workoutseqorder
			//unset($data[0]);
			$dataArray  =  array();
			ksort($data);
			if(is_array($data) && count($data)>0){
				foreach($data as $keys => $values){
					if(!empty($values))
						$dataArray[] = $values;
				}
			}
			if(is_array($dataArray) && count($dataArray)>0){
				$seqorder = 0;
				$i = 0;
				$updateParam = '';
				//foreach($dataArray as $keys => $values){
					//echo "<pre>";print_r($dataArray);die();
					foreach($dataArray as $keyss => $valuess){
						if( isset($valuess['module']) && $valuess['module']=='item_parent'){
							$seqorder = '1';
							$parentArray = explode('_',$valuess['id']);
							if(isset($valuess['children']) && count($valuess['children'])>0){
								$parent_id = $parentArray[1];
								$wkseq_ids =  '';
								$elementId 		   = $valuess['children']['id'];
								$elementParentArray= $elementArray = array();
								$parent_folder_id  = $folder_id = $workout_id  = 0;
								if(!empty($elementId)){
									$elementArray = explode('_',$elementId);
								}
								$wkseq_id	   	   = $elementArray['2'];
								
								if($elementArray['0'] == 'itemfolder'){
									$folder_id = $elementArray['1'];
								}else{
									$workout_id = $elementArray['1'];
								}
								$workout_folder_id = $elementArray['1'];
								$old_wkseq_order   = $elementArray['3'];
								$new_wkseq_order   = 1;
								$wkseq_ids =  $wkseq_id;
								if(!empty($parentid)){
									$workoutModel->updateWkoutSeqOrderParentFolderMin($parentid, $wkseq_ids, $user->pk());
								}
								/******************* Activity Feed *********************/
								$activity_feed = array();
								$activity_feed["feed_type"]  = (!empty($folder_id) ? '1' : '2');
								$activity_feed["action_type"]= 4;  // This get from action_type table  
								$activity_feed["user"]	= $user->pk();
								$activity_feed["type_id"]  = (!empty($folder_id) ? $folder_id : $workout_id);
								$activity_feed["site_id"]  = Session::instance()->get('current_site_id');
								if(!empty($parent_id)){
									$fetch_field  = 'folder_title';
									$fetch_condtn = 'id=' . $parent_id;
									$result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_folders', $fetch_field, $fetch_condtn);
									if (isset($result) && count($result) > 0) {
										$activity_feed["json_data"]= json_encode($result[0][$fetch_field]);
									}
								}else
									$activity_feed["json_data"]= json_encode('My Workout Plans');
								Helper_Common::createActivityFeed($activity_feed);
								/******************* Activity Feed *********************/
								$workoutModel->updateWkoutorder($new_wkseq_order,$wkseq_id , $folder_id, $workout_id, $parent_id, $user->pk());
								if(!empty($wkseq_ids)){
									$workoutModel->updateWkoutSeqOrderParentFolder($parent_id, $wkseq_ids, $user->pk());
								}
							}
						}elseif(isset($valuess['module']) && $valuess['module']!='item_parent' && isset($valuess['id'])){
							if( isset($valuess['module']) && isset($valuess['children'])){
									$wkseq_ids =  '';
									$elementId 		   = $valuess['children']['id'];
									$elementparent	   = $valuess['id'];
									$elementParentArray= $elementArray = array();
									$parent_folder_id  = $folder_id = $workout_id  = 0;
									if(!empty($elementId)){
										$elementArray = explode('_',$elementId);
									}
									if(!empty($elementparent)){
										$elementParentArray = explode('_',$elementparent);
										$parent_folder_id   = $elementParentArray['1'];
									}
									$wkseq_id	   	   = $elementArray['2'];
									
									if($elementArray['0'] == 'itemfolder'){
										$folder_id = $elementArray['1'];
									}else{
										$workout_id = $elementArray['1'];
									}
									$workout_folder_id = $elementArray['1'];
									$new_wkseq_order   = 1;
									$wkseq_ids =  $wkseq_id;
									/******************* Activity Feed *********************/
									$activity_feed = array();
									$activity_feed["feed_type"]  = (!empty($folder_id) ? '1' : '2');
									$activity_feed["action_type"]= 4; 
									$activity_feed["user"]	= $user->pk();
									$activity_feed["type_id"]  = (!empty($folder_id) ? $folder_id : $workout_id);
									$activity_feed["site_id"]  = Session::instance()->get('current_site_id');
									if(!empty($parent_folder_id)){
										$fetch_field  = 'folder_title';
										$fetch_condtn = 'id=' . $parent_folder_id;
										$result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_folders', $fetch_field, $fetch_condtn);
										if (isset($result) && count($result) > 0) {
											$activity_feed["json_data"]= json_encode($result[0][$fetch_field]);
										}
									}else
										$activity_feed["json_data"]= json_encode('My Workout Plans');
									Helper_Common::createActivityFeed($activity_feed);
									/******************* Activity Feed *********************/
									$workoutModel->updateWkoutorder($new_wkseq_order,$wkseq_id , $folder_id, $workout_id, $parent_folder_id, $user->pk());
									if(!empty($wkseq_ids)){
										$workoutModel->updateWkoutSeqOrderParentFolder($parent_folder_id, $wkseq_ids, $user->pk());
									}
									if(!empty($parentid)){
										$workoutModel->updateWkoutSeqOrderParentFolderMin($parentid, $wkseq_ids, $user->pk());
									}
							}
							$elementId 		   = $valuess['id'];
							$elementParentArray= $elementArray = array();
							$parent_folder_id  = $folder_id = $workout_id  = 0;
							if(!empty($elementId)){
								$elementArray = explode('_',$elementId);
							}
							$parent_folder_id  = $parentid;
							$wkseq_id	   	   = $elementArray['2'];
							
							if($elementArray['0'] == 'itemfolder'){
								$folder_id = $elementArray['1'];
							}else{
								$workout_id = $elementArray['1'];
							}
							$workout_folder_id = $elementArray['1'];
							$old_wkseq_order   = $elementArray['3'];
							$new_wkseq_order   = $i+1 - $seqorder;
							$workoutModel->updateWkoutorder($new_wkseq_order,$wkseq_id , $folder_id, $workout_id, $parent_folder_id, $user->pk());
							if(!empty($folder_id))
								$responseArray[$elementId] = $workoutModel->getwkoutSeqOrder($folder_id,$user->pk());
							if(!empty($parentid)){
								//$workoutModel->updateWkoutSeqOrderParentFolderMin($parentid, $wkseq_id, $user->pk());
							}
						}
						$i += 1;
					}
					//}
				}
			$this->response->body(json_encode((!empty($responseArray) ? $responseArray : true)));
		}elseif(!empty($action) && trim($action) == 'getbydate' && $user->pk()){
			$workoutModel 	 = ORM::factory('workouts');
			$updateParam = '';
			$records = $workoutModel->getAssignedWorkouts($user->pk(),date('Y-m-d',strtotime($data)));
			if(is_array($records) && count($records)>0){
				foreach($records as $keys => $values){
					$updateParam	.='<div class="row">';
					$updateParam	.='<div class="mobpadding">';
					$updateParam	.='<div class="border full">';
					$updateParam	.='<div class="colorchoosen col-xs-3">';
					$updateParam	.='<i class="glyphicon hotpink colorcenter"></i>';
					$updateParam	.='</div><div class="col-xs-6 aligncenter activedatacol">';
					$updateParam	.= $values['wkout_title'];
					$updateParam	.='</div><div class="col-xs-3 aligncenter">';
					$updateParam	.='<i class="fa fa-cog iconsize2 activedatacol"></i></div>';
					$updateParam	.='</div></div></div><br>';
	
				}
			}
			$responseArray = $updateParam;
			$this->response->body($responseArray);
		}elseif(!empty($action) && trim($action) == 'goal_seq_order' && $user->pk()){
			$workoutModel 	 = ORM::factory('workouts');
			// itemSet_15_211_0  => type/wkout_id/goal_id/goal_order
			if(is_array($data) && count($data)>0){
				foreach($data as $keys => $values){
					if(is_array($values) && count($values)>0){
						foreach($values as $keyss => $valuess){
							if(isset($valuess['module']) && $valuess['module'] =='item_set' && isset($valuess['id']) && $valuess['id'] != 'itemSet_0_0_0'){
								$elementArray 	   = array();
								$parent_folder_id  = $folder_id = $workout_id  = 0;
								if(!empty($valuess['id'])){
									$elementArray = explode('_',$valuess['id']);
								}
								if(count($elementArray) > 0){
									$workout_id 	  = $elementArray['1'];
									$goal_id		  = $elementArray['2'];
									$old_goal_order   = $elementArray['3'];
									$new_goal_order   = $keyss+1;
									if($old_goal_order != $new_goal_order)
										$workoutModel->updateGoalOrder($new_goal_order, $workout_id, $goal_id, $user->pk());
								}
							}
						}
					}
				}
			}
			$this->response->body(true);
		}
	}
	public function action_updateHide()
	{
		$action			= Arr::get($_GET, 'action');
		$page_id		= Arr::get($_GET, 'page_id');
		$type			= Arr::get($_GET, 'type');
		$type			= Arr::get($_GET, 'type');
		$user 			= Auth::instance()->get_user();
		$flag			= Arr::get($_GET,'checkedFlag');
		$workoutModel 	= ORM::factory('workouts');
		$page_id		= Session::instance()->get('user_allow_page');
		if(!empty($action) && trim($action) == 'updateTour' && $user->pk() && !empty($page_id) && !empty($type)){
			$flag = ($flag == 'true' ? '1' : '0');
			$type = str_replace('hide_','is_',$type).'_hidden';
			$workoutModel->updateDontshow($page_id, $user->pk(),$type,$flag);
			$this->response->body(json_encode('success'));
		}elseif(!empty($action) && trim($action) == 'getaccess' && $user->pk() && !empty($page_id)){
			$this->response->body($workoutModel->getAllowAccess($page_id, $user->pk()));
		}
	}
	
	public function action_wkoutall()
	{
		$responseArray  = '';
		$from			= Arr::get($_GET, 'from');
		$to				= Arr::get($_GET, 'to');
		$curdate = Helper_Common::get_default_date();
		$curtime = Helper_Common::get_default_time('','H:s:i');
		$user 	 = Auth::instance()->get_user();
		$workoutModel 	= ORM::factory('workouts');
		if($user->pk()){
			$allPlanWrkouts   = $workoutModel->getAllPlannedWorkouts($user->pk(),date('Y-m-d',strtotime($from)),date('Y-m-d',strtotime($to)));
			$responseArray['success'] = '1';
			$responseArray['result']  = array();
			if(is_array($allPlanWrkouts) && count($allPlanWrkouts)>0){
				foreach($allPlanWrkouts as $keys => $values){
					$current 	= strtotime($curdate);
					$datediff 	= strtotime($values['assigned_date']) - $current;
					$difference = floor($datediff/(60*60*24));
					$responseArray['result'][$keys]['id'] 	 = $values['wkoutplan_id'];
					$responseArray['result'][$keys]['title']   	 = $values['wkout_title'];
					$responseArray['result'][$keys]['url'] 	 	 = '';
					$responseArray['result'][$keys]['class']   	 = '';
					$responseArray['result'][$keys]['color']   	 = trim($values['color_title']);
					if($values['wkoutplan_type'] == 'assigned'){
						$responseArray['result'][$keys]['type']  = 'assigned';
						$responseArray['result'][$keys]['clickPre']	 = ' getAssignedwrkoutpreview('."'".$values['wkout_id']."','".$values['wkoutplan_id']."','".$values['assigned_date']."','".($values['from_wkout'] == '0' ? $values['assigned_by'] : '0')."'".')';
						$responseArray['result'][$keys]['clickOpt']  = 'getTemplateOfAssignAction('."'".$values['wkout_id']."','".$values['wkoutplan_id']."','".$values['assigned_date']."','".($values['from_wkout'] == '0' ? $values['assigned_by'] : '0')."','".addslashes($values['wkout_title'])."','".(empty($values['journal_status']) ? '1' : (empty($values['marked_status']) || $values['marked_status']!='1' ? '1' : ''))."','1'".')';
						$responseArray['result'][$keys]['start']   = strtotime($values['assigned_date'].$curtime).'000';
						$responseArray['result'][$keys]['end'] 	   = strtotime($values['assigned_date'].$curtime).'000';
						$responseArray['result'][$keys]['assignflag']= $assignFlag =  (!empty($values['journal_status']) && !empty($values['marked_status']) ? $values['marked_status'] : (!empty($values['journal_status']) && $values['marked_status']!='1' ? '' : ($difference < 0 ? '3' : '')));
						$responseArray['result'][$keys]['iconflag']= '';
						if($difference >= 0 && $values['journal_status'] == $values['marked_status']){
							$responseArray['result'][$keys]['clickenable']  = '<label><input type="checkbox" value="'.$values['wkoutplan_id'].'" name="assignstatus[]" class="checkassignstatus" data-ajax="false" data-role="none" onclick="changeStatusAssign('."'".$values['wkoutplan_id']."','assigned','','0','','','".$values['assigned_date']."'".');"><span style="border-radius: 20%;margin-left:10px;" class="cr checkbox-circle"><i class="cr-icon fa fa-check"></i></span></label>';
							$responseArray['result'][$keys]['customtxt'] = 'Assignment - No Journal Logged';
						}else{
							$responseArray['result'][$keys]['clickenable'] = '<span onclick="'.$responseArray['result'][$keys]['clickOpt'].'";><i class="fa '.($assignFlag ==1 ? 'fa-check-square-o iconsize greenicon' : ($assignFlag ==2 || $assignFlag ==0  ? 'fa-minus-square-o iconsize pinkicon' : ($assignFlag ==3 ? 'fa-exclamation-circle iconsize' : 'fa-ellipsis-h'))).'"></i></span>';
							$responseArray['result'][$keys]['customtxt'] = 'Assignment - Incomplete - No Journal Logged';
						}
					}else{
						$responseArray['result'][$keys]['type']  = 'logged';
						$responseArray['result'][$keys]['clickPre']= 'getjournalwrkoutpreview('."'".$values['wkout_id']."','".$values['wkoutplan_id']."','".$values['assigned_date']."','".($values['from_wkout'] == '0' ? $values['user_id'] : '0')."'".')';
						$responseArray['result'][$keys]['clickOpt'] = 'getTemplateOfAssignActionByJournal('."'".$values['wkout_id']."','".$values['wkoutplan_id']."','".$values['assigned_date']."','".($values['from_wkout'] == '0' ? $values['user_id'] : '0')."','".addslashes($values['wkout_title'])."','".(empty($values['wkout_status']) || $values['wkout_status'] =='3' ? '1' : '')."','".$values['xr_unmark_count']."'".')';
						$responseArray['result'][$keys]['start']   = strtotime($values['assigned_date'].$curtime).'000';
						$responseArray['result'][$keys]['end'] 	   = strtotime($values['assigned_date'].$curtime).'000';
						$responseArray['result'][$keys]['iconflag'] = $iconflag = ($difference < 0 || ($values['wkout_status'] != '' && $values['wkout_status']) ? $values['wkout_status'] : '');
						$responseArray['result'][$keys]['assignflag']= '';
						$responseArray['result'][$keys]['clickenable']= '<span onclick="changeStatusAssign('."'".$values['wkoutplan_id']."','logged','".$values['associated_id']."','".$values['wkout_status']."','".$values['intensity']."','".$values['remark']."','".$values['assigned_date']."','".$values['xr_unmark_count'].'###'.$values['wkout_id'].'###'.($values['from_wkout'] == '0' ? $values['user_id'] : '0').'###'.addslashes($values['wkout_title'])."'".');"><i class="fa '.($iconflag==1 ? 'fa-check-square-o iconsize greenicon' : ($iconflag ==2 ? 'fa-minus-square-o iconsize pinkicon' : ($iconflag ==3 || ($iconflag !='' && $iconflag=='0') ? 'fa-exclamation-circle iconsize' : 'fa-ellipsis-h'))).'"></i></span>';
						$responseArray['result'][$keys]['customtxt'] = ($iconflag==1 ? (!empty($values['associated_id']) ? 'Journal - Completed - From Assignment' : 'Journal - Completed - Not From Assignment') : ($iconflag ==2 ? (!empty($values['associated_id']) ? 'Journal - Skipped - From Assignment' : 'Journal - Skipped - Not From Assignment') : ($iconflag ==3 || ($iconflag !='' && $iconflag=='0') ? (!empty($values['associated_id']) ? 'Journal - Incomplete - From Assignment' : 'Journal - Incomplete - Not From Assignment') : '')));
					}
				}
			}
		}
		$responseArray = json_encode($responseArray);
		$this->response->body($responseArray);		
	}
	public function action_wkoutassign()
	{
		$responseArray  = '';
		$from			= Arr::get($_GET, 'from');
		$to				= Arr::get($_GET, 'to');
		$user = Auth::instance()->get_user();
		if($user->pk()){
			$curdate = Helper_Common::get_default_date();
			$curtime = Helper_Common::get_default_time('','H:s:i');
			$workoutModel 	 = ORM::factory('workouts');
			$assignWrkouts   = $workoutModel->getAssignedWorkouts($user->pk(),date('Y-m-d',strtotime($from)),date('Y-m-d',strtotime($to)));
			$responseArray['success'] = '1';
			$responseArray['result']  = array();
			if(is_array($assignWrkouts) && count($assignWrkouts)>0){
				foreach($assignWrkouts as $keys => $values){
					$current 	= strtotime($curdate);
					$datediff 	= strtotime($values['assigned_date']) - $current;
					$difference = floor($datediff/(60*60*24));
					$responseArray['result'][$keys]['id'] 	   = $values['wkout_assign_id'];
					$responseArray['result'][$keys]['title']   = $values['wkout_title'];
					$responseArray['result'][$keys]['url'] 	   = '';
					$responseArray['result'][$keys]['class']   = '';
					$responseArray['result'][$keys]['color']   = trim($values['color_title']);
					$responseArray['result'][$keys]['clickPre']= ' getAssignedwrkoutpreview('."'".$values['wkout_id']."','".$values['wkout_assign_id']."','".$values['assigned_date']."','".($values['from_wkout'] == '0' ? $values['assigned_by'] : '0')."'".')';
					$responseArray['result'][$keys]['clickOpt'] = ' getTemplateOfAssignAction('."'".$values['wkout_id']."','".$values['wkout_assign_id']."','".$values['assigned_date']."','".($values['from_wkout'] == '0' ? $values['assigned_by'] : '0')."','".addslashes($values['wkout_title'])."','".(empty($values['journal_status']) ? '1' : (empty($values['marked_status']) || $values['marked_status']!='1' ? '1' : ''))."'".')';
					$responseArray['result'][$keys]['start']   = strtotime($values['assigned_date'].$curtime).'000';
					$responseArray['result'][$keys]['end'] 	   = strtotime($values['assigned_date'].$curtime).'000';
					$responseArray['result'][$keys]['assignflag']= (!empty($values['journal_status']) && !empty($values['marked_status']) ? $values['marked_status'] : (!empty($values['journal_status']) && $values['marked_status']!='1' ? '' : ($difference < 0 ? '2' : '')));
					$responseArray['result'][$keys]['iconflag']= '';
				}
			}
			$responseArray = json_encode($responseArray);
		}
		$this->response->body($responseArray);
	}
	public function action_wkoutjournal()
	{
		$responseArray  = '';
		$from			= Arr::get($_GET, 'from');
		$to				= Arr::get($_GET, 'to');
		$curdate = Helper_Common::get_default_date();
		$curtime = Helper_Common::get_default_time('','H:s:i');
		$user = Auth::instance()->get_user();
		if($user->pk()){
			$workoutModel 	 = ORM::factory('workouts');
			$journalWrkouts   = $workoutModel->getJournalWorkouts($user->pk(),date('Y-m-d',strtotime($from)),date('Y-m-d',strtotime($to)));
			$responseArray['success'] = '1';
			$responseArray['result']  = array();
			if(is_array($journalWrkouts) && count($journalWrkouts)>0){
				foreach($journalWrkouts as $keys => $values){
					$current 	= strtotime($curdate);
					$datediff 	= strtotime($values['assigned_date']) - $current;
					$difference = floor($datediff/(60*60*24));
					$responseArray['result'][$keys]['id'] 	   = $values['wkout_log_id'];
					$responseArray['result'][$keys]['title']   = $values['wkout_title'];
					$responseArray['result'][$keys]['url'] 	   = '';
					$responseArray['result'][$keys]['class']   = '';
					$responseArray['result'][$keys]['color']   = trim($values['color_title']);
					$responseArray['result'][$keys]['clickPre']= ' getjournalwrkoutpreview('."'".$values['wkout_id']."','".$values['wkout_log_id']."','".$values['assigned_date']."','".($values['from_wkout'] == '0' ? $values['user_id'] : '0')."'".')';
					$responseArray['result'][$keys]['clickOpt'] = ' getTemplateOfAssignActionByJournal('."'".$values['wkout_id']."','".$values['wkout_log_id']."','".$values['assigned_date']."','".($values['from_wkout'] == '0' ? $values['user_id'] : '0')."','".addslashes($values['wkout_title'])."'".')';
					$responseArray['result'][$keys]['start']   = strtotime($values['assigned_date'].$curtime).'000';
					$responseArray['result'][$keys]['end'] 	   = strtotime($values['assigned_date'].$curtime).'000';
					$responseArray['result'][$keys]['iconflag']= ($difference < 0 || ($values['wkout_status'] != '' && $values['wkout_status']) ? $values['wkout_status'] : '');
					$responseArray['result'][$keys]['assignflag']= '';
				}
			}
			$responseArray = json_encode($responseArray);
		}
		$this->response->body($responseArray);
	}
	public function action_get_update_imglist(){
		$response='';
		if(isset($_POST) && !empty($_POST)){
			$workoutModel = ORM::factory('workouts');
			if($_POST['request']=='updatelist'){
				$result=$workoutModel->update_images($_POST);
			}
			elseif($_POST['request']=='search' && $_POST['imgsearch']!=''){
				$result=$workoutModel->get_images($_POST['imgsearch']);
			}
			else{
				$result=$workoutModel->get_images('');				
			}

			if(isset($result) && count($result)>0){
				foreach($result as $keys => $values){
					$response .= '<div class="col-sm-12 col-xs-12">';
						$response .= '<div class="imgRecord col-sm-12 col-xs-12" id="'.$values['img_id'].'">';
							$response .= '<div class="col-sm-3 col-xs-3 thumb-img img-itemname" data-itemid="'.$values['img_id'].'" data-itemname="'.ucfirst($values['img_title']).'" data-itemurl="'.$values['img_url'].'" style="background-image: url('.URL::base().$values['img_url'].');"></div>';
							$response .= '<div class="col-sm-7 col-xs-7"><a class="img-itemname" href="javascript:void(0);" title="Upload" data-itemid="'.$values['img_id'].'" data-itemname="'.ucfirst($values['img_title']).'" data-itemurl="'.$values['img_url'].'">'. ucfirst($values['img_title']) .'</a></div>';
							$response .= '<div class="col-sm-2 col-xs-2"><span class="fa fa-remove unpub"></span></div>';
						$response .= '</div>';
					$response .= '</div>';
				}
			}
		}
		$this->response->body(json_encode($response));
	}

	public function action_exerciseRecordGallery(){
		if(isset($_POST) && !empty($_POST)){
			$workoutModel = ORM::factory('workouts');
			$xrciseRecord=$workoutModel->get_exerciseRecordGallery($_POST);
			echo json_encode( array("items"=>$xrciseRecord) );
		}
	}
	public function action_exerciseUnitData(){
		if(isset($_POST['xr_id']) && !empty($_POST['xr_id'])){
			$workoutModel = ORM::factory('workouts');
			$xrciseunitdata=$workoutModel->get_exerciseUnitData($_POST['xr_id']);
			echo json_encode( array("items"=>$xrciseunitdata) );
		}
	}
	public function action_imgFilter(){
		if(isset($_POST) && !empty($_POST)){
			$imagelibrary = ORM::factory('imagelibrary');
			$filteredimg = array();
			if(!empty($_POST['fid']) || !empty($_POST['subid'])){
				$filteredimg=$imagelibrary->get_Imageslistbyfilter($_POST);
			}else{
			}
			echo json_encode( array("items"=>$filteredimg) );
		}
	}
	public function action_tagnames(){
		$imagelibrary = ORM::factory('imagelibrary');
		$tagnameslist = $imagelibrary->get_tagnames();
		$tagname = array();
		//["sports","injury risk","baseball","basketball","jumping","explosive","vertical"]
		if(count($tagnameslist) > 1){
			foreach($tagnameslist as $keys => $values)
				$tagname[$values['tag_id']]= $values['tag_title'];
		}
		echo json_encode( array("tagnames"=>$tagname) );
	}
	public function action_exerciseDataToLoad(){
		$imagelibrary = ORM::factory('imagelibrary');
		$workoutModel = ORM::factory('workouts');
		$tagnameslist = $imagelibrary->get_tagnames();
		$musclelist = $workoutModel->getcheckboxes('muscle','unit_','_title','_id','unit_gendata','muscle','0');
		$equiplist = $workoutModel->getcheckboxes('equip','unit_','_title','_id','unit_gendata','equip','0');
		$tagname = array();
		if(count($tagnameslist) > 1){
			foreach($tagnameslist as $keys => $values)
				$tagname[$values['tag_id']]= $values['tag_title'];
		}
		$musclename = array();
		if(count($musclelist) > 1){
			foreach($musclelist as $keys => $values)
				$musclename[$values['muscle_id']]= $values['muscle_title'];
		}
		$equipname = array();
		if(count($equiplist) > 1){
			foreach($equiplist as $keys => $values)
				$equipname[$values['equip_id']]= $values['equip_title'];
		}
		echo json_encode( array("tagnames"=>$tagname, 'musclenames'=>$musclename, 'equipnames'=>$equipname) );
	}
	
	public function action_getwkxrtagname(){
		
		if(isset($_POST) && !empty($_POST)){
			$wk_id = $this->request->post('wk_id');
			$mymodel = ORM::factory('workouts');
			$existing_usertags = $mymodel->getUnitTagsById($wk_id);
			//print_r($existing_usertags); die;
			echo json_encode($existing_usertags); 
		}	
		
	}	
	
	public function action_ajaxGetImageCommonTags(){
		$imagelibrary = ORM::factory('imagelibrary');
		if ($this->request->is_ajax())
			$this->auto_render = FALSE;
		if (isset($_GET) && count($_GET) > 0) {
			$imgids = $_GET['imgids'];
			$imgtags=$imagelibrary->ajaxGetImageCommonTagsById($imgids);
			$it = array();
			if (count($imgtags) > 0) {
				foreach ($imgtags as $imgtag) {
					$it[] = $imgtag['tag_title'];
				}
			}
			$response = array();
			$response['img_tags'] = implode(",", $it);
			$response['success'] = true;
			echo $this->request->response = json_encode($response);
		}
	}
	
	public function action_subscribe(){
		$hompage = ORM::factory('homepage');
		$result=$hompage->subscribe($_GET);
		echo $result;
		exit;
	}
	public function action_CreateExerciseRecord(){
		$userid = Auth::instance()->get_user()->pk();
		$site_id = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
		$workoutModel = ORM::factory('workouts');
		$response = array(); $response['xrid'] = $response['title'] = $response['img_url'] = ''; $response['flag'] = false;
		if (HTTP_Request::POST == $this->request->method()){
			// echo '<pre>'; print_r($_POST);exit;
			$action = $this->request->post('action');
			$type = $this->request->post('type');
			$xrid = $this->request->post('xrid');
			if(!empty($_POST) && $action == 'create'){
				if(!empty($type) && ($type == 'save'|| $type == 'save-edit') && !empty($xrid)){
					$resdata = $workoutModel->UpdateExerciseRecByIdData($_POST, $xrid);
					if($resdata['success']){
						$response['xrid'] = $resdata['xrid'] ? $resdata['xrid'] : '';
						$response['title'] = $resdata['title'] ? $resdata['title'] : '';
						$response['img_url'] = $resdata['img_url'] ? URL::base().$resdata['img_url'] : '';
						$response['act'] = 'updated';
						$response['flag'] = true;
					}else{
						$response['flag'] = false;
					}
				}elseif(!empty($type) && ($type == 'save'|| $type == 'save-edit') && empty($xrid)){
					$resdata = $workoutModel->InsertExerciseRecByIdData($_POST);
					if($resdata['success']){
						$response['xrid'] = $resdata['xrid'] ? $resdata['xrid'] : '';
						$response['title'] = $resdata['title'] ? $resdata['title'] : '';
						$response['img_url'] = $resdata['img_url'] ? URL::base().$resdata['img_url'] : '';
						$response['act'] = 'created';
						$response['flag'] = true;
					}else{
						$response['flag'] = false;
					}
				}
			}elseif(!empty($_POST) && $action == 'addExercise'){
				if(isset($_POST['addtype']) && isset($_POST['addid']) && $_POST['addid'] != ''){
					if($_POST['addtype'] == 'myexercise' || $_POST['addtype'] == 'sampleexercise' || $_POST['addtype'] == 'sharedexercise'){
						$resdata = $workoutModel->CopyDeleteExerciseRecById('exerciseRecord', $_POST['addid'], 'new_create');
						if($resdata['success']){
							$response['xrid'] = $resdata['xrid'] ? $resdata['xrid'] : '';
							$response['title'] = $resdata['title'] ? $resdata['title'] : '';
							$response['img_url'] = $resdata['img_url'] ? URL::base().$resdata['img_url'] : '';
							$response['flag'] = true;
						}else{
							$response['flag'] = false;
						}
					}
				}
			}
		}
		$this->response->body(json_encode($response));
	}
	public function action_getAjaxImgLibraryHtml(){
		$folderid = (isset($_POST['fid']) && !empty($_POST['fid']) ? $_POST['fid'] : '');
		$subfolderid = (isset($_POST['subfid']) && !empty($_POST['subfid']) ? $_POST['subfid'] : '');
		$imgdatamethod = (isset($_POST['process']) && !empty($_POST['process']) ? $_POST['process'] : '');
		$saveid = (isset($_POST['curr_imgid']) && !empty($_POST['curr_imgid']) ? $_POST['curr_imgid'] : '');
		$imagelibrary = ORM::factory('imagelibrary');
		$getsubfolders = $getfolderitem = array(); $message = '';
		$site_id = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
		if(!empty($imgdatamethod) && ($imgdatamethod == 'savecontinue' || $imgdatamethod == 'saveclose')){
			if(isset($_POST['curr_imgid']) && !empty($_POST['curr_imgid'])){
				if(isset($_POST['croppedData']) && !empty($_POST['croppedData'])){
					if($imagelibrary->updateImgUrlById($_POST)){
						$message = array('flag'=>1, 'msg'=>'Image successfully updated!!!');
					}else{
						$message = array('flag'=>0, 'msg'=>'Error occurred while updating image!!!');
					}
				}else{
					$message = array('flag'=>0, 'msg'=>'Error occurred while updating image!!!');
				}
			}else{
				$message = array('flag'=>0, 'msg'=>'Error occurred while updating image!!!');
			}
		}
		if(!empty($subfolderid) && !empty($folderid)){
			$getfolderitem = $imagelibrary->getFolderImages($subfolderid, $folderid);
			$subfolders = '';
			$folderitem = $getfolderitem;
			$foldername = $imagelibrary->getImgFolderName($subfolderid);
		}else if(!empty($folderid)){
			if($folderid !='2'){
				$getsubfolders = $imagelibrary->getSubImgFolder($folderid);
				if(count($getsubfolders)>0){
					$subfolders = $getsubfolders;
					$foldername = $imagelibrary->getImgFolderName($folderid);
				}
				if(empty($subfolderid) && count($getsubfolders)<=0){
					$getfolderitem = $imagelibrary->getFolderImages($subfolderid, $folderid);
					$subfolders = '';
					$folderitem = $getfolderitem;
					$foldername = $imagelibrary->getImgFolderName($folderid);
				}
			}else{
				$getfolderitem = $imagelibrary->getFolderImages(5, $folderid);
				$subfolders = '';
				$folderitem = $getfolderitem;
				$foldername = $imagelibrary->getImgFolderName($folderid);
			}
		}else{
			$partentfolder = $imagelibrary->getParentImgFolder();
			if(isset($partentfolder) && count($partentfolder) > 0){
				$parentFoldertemp = $partentfolder;
				foreach($parentFoldertemp as $keys => $values){
					if($values['folder_id'] == '1')
						$partentfolder[$keys]['countval'] = $imagelibrary->getImgCountByFolder(0, 1);
					else if($values['folder_id'] == '2')
						$partentfolder[$keys]['countval'] = $imagelibrary->getImgCountByFolder(0, 2);
					else if($values['folder_id'] == '3')
						$partentfolder[$keys]['countval'] = $imagelibrary->getImgCountByFolder(0, 3);
					else if($values['folder_id'] == '6')
						$partentfolder[$keys]['countval'] = $imagelibrary->getImgCountByFolder(0, 6);
				}
			}
		}
		if(!isset($partentfolder) && !empty($folderid) && empty($subfolderid)){
			$profileimgcnt = $imagelibrary->getImgCountByFolder(4, $folderid);
			$exerciseimgcnt = $imagelibrary->getImgCountByFolder(5, $folderid);
		}
		$response = '<!-- forlder & listing start -->
		<div class="mdl_img-lib-folder identityclass'.$folderid.'">
			<div id="sticky-header">
				<div class="row">
					<div class="page-head">
						<div class="col-xs-3 aligncenter">
							<a href="javascript:void(0);" id="mdl_folderBack" title="'.__("Back").'" data-ajax="false" data-role="none">
								<i class="fa fa-caret-left iconsize"></i>
							</a>
						</div>
						<div class="col-xs-6 aligncenter centerheight page-title">';
							if(!empty($foldername) && count($foldername > 0)) {
								$response .= __(ucfirst($foldername[0]['folder_title']));
							}else{
								$response .= __("Images");
							}
							if($folderid!='' && $folderid!=1 || $subfolderid!=''){
								$searchclass = '';
							}else{
								$searchclass = ' hide';
							}
							if($subfolderid!=''){
								$currentfolder = $subfolderid;
							}else{
								$currentfolder = $folderid;
							}
							$response .= '<input type="hidden" id="mdl_parentFolderId" name="mdl_parentFolderId" value="'.$folderid.'">
							<input type="hidden" id="mdl_subFolderId" name="mdl_subFolderId" value="'.$subfolderid.'">
							<input type="hidden" id="mdl_currentFolderId" name="mdl_currentFolderId" value="'.$currentfolder.'">
						</div>
						<div class="col-xs-3 aligncenter">
							<div class="filter-search'.$searchclass.'" title="'.__("Search Images").'"> 
								<a href="#" data-toggle="modal" data-target="#mdl_popupfilteract-modal" data-ajax="false" data-role="none"><i class="fa fa-search iconsize2"></i></a>
							</div>
						</div>
					</div>
				</div>
				<hr>';
				if(isset($folderitem['itemlist']) && count($folderitem['itemlist'])>0){
					if($folderitem['itemlist'][0]['parentfolder_id']==1){
						$response .='<div class="listing-header header-toggle">
							<div class="row">
								<div class="col-xs-12">
									<a href="javascript:void(0);" class="upload-image" title="'.__("Upload Images").'" onclick="popuptriggerSelectFolderModal();" data-ajax="false" data-role="none">
										<div class="col-xs-9 header-cell aligncenter">
											<i class="fa fa-plus iconsize2 activedatacol"></i>
										</div>
									</a>
									<div class="col-xs-3 header-cell aligncenter datacol">
										<i class="fa fa-list-ul iconsize2 datacol"></i>
									</div>
								</div>
							</div>
							<hr>
						</div>';
					}
				}
			$response .= '</div>'; //sticky header end
			$class = $class1 = '';
			if(!empty($folderitem['itemlist']) || (isset($foldername) && ($folderid!=1))){
				$class = "hide";
			}
			if(!empty($partentfolder) && count($partentfolder > 0)){ 
				$class1 = 'activedatacol';
			}else{ 
				$class1 = 'activedatacol';
			}
			$response .= '<div id="mdl_imgupload-link" class="'.$class.'">
				<div class="row">
					<a href="javascript:void(0);" id="mdl_trigger-uploader" class="upload-image" title="'.__("Upload Images").'" onclick="popuptriggerSelectFolderModal();" data-ajax="false" data-role="none">
						<div class="col-xs-12">
							<div class="header-cell">
								<div class="col-xs-3 aligncenter">
									<i class="fa fa-plus iconsize2 '.$class1.'"></i>
								</div>
								<div class="col-xs-6 '.$class1.'">'.__("Upload Images").'</div>
								<div class="col-xs-3"></div>
							</div>
						</div>
					</a>
				</div>
				<hr>
			</div>'; // mdl_imgupload-link end
			$hideclass = $class = $class1 = '';
			if(!empty($partentfolder) && count($partentfolder) > 0){ }else{ $hideclass .= "hide"; }
			$response .='<div id="mdl_parentfolder-div" class="'.$hideclass.'">';
				if(!empty($partentfolder) && count($partentfolder > 0)){ 
					$foldercount=count($partentfolder)-1;
					foreach($partentfolder as $key => $value){
						$response .=' <div class="row">';
							if(empty($value['countval']) || $value['countval'] == 0){ $class = 'datacol'; $class1 = ''; }else{ $class = 'activedatacol'; $class1 = 'f-parent'; }
							$p_imgcnt = number_format($value['countval']);
							$response .='<a href="javascript:void(0);" id="'.$value['folder_id'].'" class="mdl_folderclk-btn '.$class1.'" data-ajax="false" data-role="none">
								<div class="col-xs-12 page-head-row">
									<div class="col-xs-3 aligncenter">
										<i class="fa fa-folder-o iconsize2 folderclick '.$class.'"></i>
									</div>
									<div class="col-xs-6 folderclick '.$class.'">'.__(ucfirst($value['folder_title'])).'&nbsp;('.$p_imgcnt.')</div>
									<div class="col-xs-3"></div>
								</div>
							</a>
						</div>';
						if($foldercount!=$key){ $response .='<hr>'; }
					}
				}
			$response .='</div>'; // mdl_parentfolder-div end
			$hideclass = $class = $class1 = '';
			if(!empty($subfolders) && count($subfolders) > 0){ }else{ $hideclass .= "hide"; }
			$response .='<div id="mdl_subfolder-div" class="'.$hideclass.'">';
				if(!empty($subfolders) && count($subfolders > 0)){ 
					$subfoldercount=count($subfolders)-1;
					foreach($subfolders as $subkey=>$subvalue){
						$response .='<div class="row">';
							if($subvalue['folder_id']!=4){ $class = 'activedatacol'; $class1 = 'f-child'; }else{ $class = 'datacol'; $class1 = ''; }
							if($subvalue['folder_id']==4){ $s_imgcnt = number_format($profileimgcnt); }
							elseif($subvalue['folder_id']==5){ $s_imgcnt = number_format($exerciseimgcnt); }
							$response.='<a href="javascript:void(0);" id="'.$subvalue['folder_id'].'" class="mdl_folderclk-btn '.$class1.'" data-ajax="false" data-role="none">
								<div class="col-xs-12 page-head-row">
									<div class="col-xs-3 aligncenter">
										<i class="fa fa-folder-o iconsize2 folderclick '.$class.'"></i>
									</div>
									<div class="col-xs-6 folderclick '.$class.'">'.__(ucfirst($subvalue['folder_title'])).'&nbsp;('.$s_imgcnt.')</div>
									<div class="col-xs-3"></div>
								</div>
							</a>
						</div>';
						if($subfoldercount!=$subkey){ $response .='<hr>'; }
					}
				}
			$response .='</div>'; // mdl_subfolder-div end
			if(isset($folderitem['itemlist']) && count($folderitem['itemlist'])>0){ $item = '';
				$response .= '<!-- img item listing start--><input type="hidden" id="filter_fid" name="filter_fid" value="'.$folderitem['itemlist'][0]['parentfolder_id'].'">
				<input type="hidden" id="filter_subfid" name="filter_subfid" value="'.$folderitem['itemlist'][0]['subfolder_id'].'">
				<ul class="img-listing" id="mdl_img_listing">';
				foreach($folderitem['itemlist'] as $keys => $values){
					$dummyicon = '';
					if(empty($values['img_url'])||!file_exists($values['img_url'])){
						$dummyicon = '<i class="fa fa-file-image-o datacol" style="font-size:50px;"></i>';
					}
					$attributes = 'data-itemid="'.$values['img_id'].'" data-itemname="'.ucfirst($values['img_title']).'" data-itemurl="'.$values['img_url'].'" data-itemtype="folder"';
					$response .= '<li class="imgRecord" id="'.$values['img_id'].'">
						<div class="imgRecordDataFrame col-xs-12 col-sm-12">
							<a href="javascript:void(0);" class="col-xs-10 col-sm-10 imgFrame-left" data-ajax="false" data-role="none">
								<div class="col-xs-4 col-sm-4 mdl_thumb-img" '.$attributes.' onclick="popuptriggerImgPrevModal(this);" '.
								(!empty($values['img_url']) && file_exists($values['img_url']) ? 'style="background-image: url('.URL::base().$values['img_url'].');">' : '>'.$dummyicon).
								'</div>
								<div class="col-xs-8 col-sm-8 mdl_img-itemname" '.$attributes.' onclick="popuptriggerImgOptionModal(this);">
									<div class="altimgtitle break-img-name">'.ucfirst($values['img_title']).'</div><div class="item-info">'.$values['default'].'</div>';
									$i=0; $tags = ''; $taglist = '';
									if(!empty($values['taglist']) && count($values['taglist'])>0){
										foreach($values['taglist'] as $tagkeys => $tagvalues){ 
											if($tagvalues['img_id'] == $values['img_id']){
												if($i==0){
													$tags .= $tagvalues['tag_title']; $taglist .= $tagvalues['tag_title'];
												} else {
													$tags .= ', '.$tagvalues['tag_title']; $taglist .= ','.$tagvalues['tag_title'];
												}
											$i++;
											}
										}
										if($tags != ''){
											$response .= '<div class="img-tags"><span class="info-bold">'.__('Tags').': </span>'.$tags.'</div>';
										}
									}
								$response .= '</div>
							</a>
							<a class="col-xs-2 col-sm-2 insert-this-img text-center imgFrame-right" '.$attributes.' title="Insert this Image" data-ajax="false" data-role="none"><div class="col-xs-12 col-sm-12"><i class="fa fa-sign-in iconsize2"></i></div></a>
						</div>
					</li>';
				}
				// mdl_img_listing end
				$response .= '</ul><!-- img item listing end -->
				<div class="nothingfound" style="display: none;">
					<div class="nofiles"></div>
					<span>'.__("No image files here").'.</span>
				</div>';
				$response .= '<script type="text/javascript">
					if($("#mdl_img_listing").length){$("#mdl_img_listing").bind("scroll",function(ev){$("html, body").animate({ scrollTop: $("#mdl_img_listing").position().top }, "slow"); var scrollTop = Math.round($(this).scrollTop()); var scrollHeight = $(this)[0].scrollHeight; if (mdl_loadAjaxSend) {if (scrollTop + $(this).innerHeight() == scrollHeight || scrollTop + $(this).innerHeight() == scrollHeight - 1 || scrollTop + $(this).innerHeight() == scrollHeight + 1) {mdl_loadAjaxSend = false; setTimeout(function() {ev.preventDefault(); if (ev.handled !== true) {ev.handled = true; popuptriggerShowMoreImage(ev); } }, 200); } } }); if(getBrowserZoomLevel() < 100){ popupAutoShowMore(); } }
				</script>';
			}
		$response .= '</div><!-- folder & listing end -->';
		echo json_encode(array('content'=>$response, 'imgid'=>$saveid, 'saveaction'=>$imgdatamethod, 'message'=>$message));
	}
	public function action_getAjaxExerciseCreateHtml(){
		$userid = Auth::instance()->get_user()->pk();
		$site_id = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
		$workoutModel = ORM::factory('workouts');
		$getunitdata = $getmusothdata = $getequipothdata = $getseqdata = $gettaglist = array();
		$response = $formtype = $xrid = '';
		// echo "<pre>"; print_r($_POST);exit;
		if(HTTP_Request::POST == $this->request->method()){
			$XrRecid 		= $this->request->post('xrid');
			$action 			= $this->request->post('action');
			$requestFrom 	= $this->request->post('requestFrom');
			$actionFrom 	= $this->request->post('actionFrom');
			$addrequest 	= $this->request->post('addrequest');
		}
		if(!empty($XrRecid) && is_numeric($XrRecid)){
			$getunitdata		= $workoutModel->getExerciseById($XrRecid);
			$getmusothdata		= $workoutModel->getMusOthByUnitId($XrRecid);
			$getequipothdata	= $workoutModel->getEquipOthByUnitId($XrRecid);
			$getseqdata 		= $workoutModel->getSequencesByUnitId($XrRecid);
			$gettaglist 		= $workoutModel->getUnitTagsById($XrRecid);
			$xrid 				= $XrRecid;
		}
		$formtype				= $action;
		$exerciseArray			= $getunitdata;
		$exerciseMusOth		= $getmusothdata;
		$exerciseEquipOth		= $getequipothdata;
		$exerciseSeq			= $getseqdata;
		$exerciseTags			= $gettaglist;
		$response = '<div class="modal-header">
			<div class="row">
				<div class="popup-title">
					<div class="col-xs-3 aligncenter">
						<a href="javascript:void(0);" class="triangle confirm" data-onclick="triggerCloseXrciseCreateModal()" data-allow="'.(Helper_Common::getAllowAllAccessByUser((Session::instance()->get('user_allow_page') ? Session::instance()->get('user_allow_page') : '1'), 'is_confirm_xr_create_hidden') ? 'false' : 'true').'" data-notename="hide_confirm_xr_create" data-text="Clicking BACK or CANCEL will discard any changes. Clicking MORE will display record options such as SAVE. Continue with exiting?" data-ajax="false" data-role="none" title="'.__('Back').'">
							<i class="fa fa-caret-left iconsize"></i>
						</a>
					</div>
					<div class="col-xs-6 aligncenter">'.(isset($XrRecid) && !empty($XrRecid) && empty($addrequest) ? __('Modify Exercise Record') : __('Create a New Exercise Record')).'</div>
					<div class="col-xs-3 aligncenter">
						<button type="button" class="btn btn-default submitTabsBtn activedatacol" data-toggle="modal" data-target="#xrcisesaveopt-modal" data-ajax="false" data-role="none">'.__('more').'</button>
					</div>
				</div>
			</div>';
			if(isset($XrRecid) && !empty($XrRecid) && empty($addrequest)){
				$response .= '<hr>
				<div class="row">
					<div class="popup-title">
						<div class="col-xs-12" style="font-size: .9em;">
							<div class="textcenter wkoutfocus"><b>'.(isset($exerciseArray['title']) ? $exerciseArray['title'] : '').'</b></div>
						</div>
					</div>
				</div>';
			}
		$response .= '</div>
		<!-- modal body -->
		<div class="modal-body">
			<div id="create-record">
				<div class="xrwrapper-div">
					<div class="xrwrappers">
						<form id="xrRecInsertForm" class="form-horizontal" method="post" action="" data-ajax="false" data-role="none">
							<div class="form-group has-feedback has-error hide" id="messageContainer">
								<div class="col-xs-12">
									<div>'.__('Please Fill The Required Fields').'</div>
								</div>
							</div>
							<!-- form content -->
							<div class="tab-content">
								<div class="row">
									<div class="mobpadding exersetcolumn-xr">
										<div class="border-xr full">
											<div class="col-xs-3 firstcell borderright">
												<p class="labelcol">'.__('Exercise Title').' <span class="activedatacol">*</span></p>
											</div>
											<div class="col-xs-9 secondcell datacol">
												<div class="form-group">
													<input type="text" tabindex="1" class="form-control" id="xru_title" value="'.(isset($exerciseArray['title']) ? $exerciseArray['title'].(!empty($addrequest) ? '_copy' : '') : '').'" name="xru_title" placeholder="'.__('Title').'" onfocus="this.value = this.value;" data-ajax="false" data-role="none"/>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="mobpadding exersetcolumn-xr">
										<div class="border-xr full">
											<div class="col-xs-3 firstcell borderright">
												<p class="labelcol">'.__('Type of Activity').' <span class="activedatacol">*</span></p>
											</div>
											<div class="col-xs-9 secondcell datacol">
												<div class="form-group">';
													$exerciseType = $workoutModel->getcheckboxes('type','unit_','_title','_id','unit_gendata','type','0');
													if(isset($exerciseType) && count($exerciseType)>0) {
														$response .= '<div class="dropdown selectdropdownTwo">
															<select tabindex="2" class="" id="xru_type" name="xru_type" data-ajax="false" data-role="none">
																<option value="">Select an option</option>';
																foreach($exerciseType as $key => $value) {
																	$response .= '<option value="'.$value['type_id'].'"'.(isset($exerciseArray['type_id']) && $exerciseArray['type_id'] == $value['type_id'] ? "selected" : "").'>'. $value['type_title'].'</option>';
																}
															$response .= '</select>
														</div>';
													}
												$response .= '</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row hide">
									<div class="mobpadding exersetcolumn-xr">
										<div class="border-xr full">
											<div class="col-xs-3 firstcell borderright">
												<p class="labelcol">'.__('Status').' <span class="activedatacol">*</span></p>
											</div>
											<div class="col-xs-9 secondcell datacol">
												<div class="form-group">';
													$exerciseStatus = $workoutModel->getcheckboxes('status','unit_','_title','_id','unit_gendata','status','0');
													if(isset($exerciseStatus) && count($exerciseStatus)>0) {
														$response .= '<div class="dropdown selectdropdownTwo">
															<select tabindex="3" class="" id="xru_status" name="xru_status" data-ajax="false" data-role="none">';
																foreach($exerciseStatus as $key => $value) {
																	$response .= '<option value="'.$value['status_id'].'"'.((isset($exerciseArray['status_id']) && $exerciseArray['status_id'] == $value['status_id']) || (!isset($exerciseArray['status_id']) && $value['status_id'] == 1) ? "selected" : "").'>'.$value['status_title'].'</option>';
																}
															$response .= '</select>
														</div>';
													}
												$response .= '</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="mobpadding exersetcolumn-xr">
										<div class="border-xr full">
											<div class="col-xs-3 firstcell borderright">
												<p class="labelcol">'.__('Featured Image').' <span class="activedatacol">*</span></p>
											</div>
											<div class="col-xs-9 secondcell datacol">
												<div class="feat_img form-group img-div">
													<span class="img_thmb datacol textcenter">
														<img class="img-responsive img-thumbnail uploaded_image_thmb" id="intro_feature" tabindex="4" src="'.(isset($exerciseArray['img_url']) ? URL::base().$exerciseArray['img_url'] : URL::base().'assets/images/icons/picture-icon.png').'" alt="'.__('Feature Image').'">
														<div class="img-placeholder inactivedatacol">'.__('Click image to zoom or edit').'</div>
													</span>
													<input type="hidden" class="img_selected" id="xru_featImage" name="xru_featImage" value="'.(isset($exerciseArray['feat_img']) && $exerciseArray['feat_img'] != 0 && $exerciseArray['feat_img']!='' ? $exerciseArray['feat_img'] : '').'">
													<div class="img-opt">
														<div class="trigger-imgopt" id="introclear" data-imgtagid="intro_feature" data-hidnimgid="xru_featImage" href="'.URL::base().'assets/images/icons/picture-icon.png'.'"></div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="mobpadding exersetcolumn-xr">
										<div class="border-xr full">
											<div class="col-xs-3 firstcell borderright">
												<p class="labelcol">'.__('Muscles Involved').' <span class="activedatacol">*</span></p>
											</div>
											<div class="col-xs-9 secondcell datacol">
												<div class="form-group">
													<ul id="muscle_lists" class="muscle-lists" data-role="none" data-ajax="false">
														<li>';
															if(isset($exerciseArray['muscle_id']) && !empty($exerciseArray['muscle_id']) && isset($exerciseArray['muscle_title']) && !empty($exerciseArray['muscle_title'])) {
																$response .= '<span class="tag-item label label-info" id="'.$exerciseArray['muscle_id'].'"><label class="radio-primary"><input type="radio" name="xru_musprim" value="'.$exerciseArray['muscle_id'].'" checked="" title="Primary Muscle" data-role="none" data-ajax="false">'.$exerciseArray['muscle_title'].'</label><span data-role="remove"></span></span> ';
																if(isset($exerciseMusOth) && count($exerciseMusOth)>0){
																	foreach ($exerciseMusOth as $extactMusoth) {
																		if(isset($extactMusoth['musoth_id']) && !empty($extactMusoth['musoth_id']) && isset($extactMusoth['muscle_title']) && !empty($extactMusoth['muscle_title'])) {
																			$response .= '<span class="tag-item label label-info" id="'.$extactMusoth['musoth_id'].'"><label class="radio-primary"><input type="radio" name="xru_musprim" value="'.$extactMusoth['musoth_id'].'" title="Primary Muscle" data-role="none" data-ajax="false">'.$extactMusoth['muscle_title'].'</label><span data-role="remove"></span><input type="hidden" class="Othermuscle" name="chkdMusOth[]" value="'.$extactMusoth['musoth_id'].'"></span> ';
																		}
																	}
																}
															}else{
																$response .= '<input type="hidden" name="xru_musprim" value="">';																
															}
														$response .= '</li>
													</ul>';
													$muscle = $workoutModel->getcheckboxes('muscle','unit_','_title','_id','unit_gendata','muscle','0');
													if(isset($muscle) && count($muscle)>0) {
														$response .= '<div class="dropdown selectdropdownTwo muscle-selectbox" style="display: none;">
															<select tabindex="5" class="" id="list_muscles" data-ajax="false" data-role="none">
																<option value="" selected="">Select an option</option>';
																foreach($muscle as $key => $value){
																	$response .= '<option value="'.$value['muscle_id'].'">'.$value['muscle_title'].'</option>';
																}
															$response .= '</select>
														</div>
														<a href="javascript:void(0);" tabindex="5" class="btn btn-default btn-sm add-muscle" onclick="showMuscleSelectbox();" data-ajax="false" data-role="none"><i class="fa fa-plus"></i> Add a muscle</a>';
													}
												$response .= '</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="mobpadding exersetcolumn-xr">
										<div class="border-xr full">
											<div class="col-xs-3 firstcell borderright">
												<p class="labelcol">'.__('Equipment').' <span class="activedatacol">*</span></p>
											</div>
											<div class="col-xs-9 secondcell datacol">
												<div class="form-group">
													<ul id="equip_lists" class="equip-lists" data-role="none" data-ajax="false">
														<li>';
															if(isset($exerciseArray['equip_id']) && !empty($exerciseArray['equip_id']) && isset($exerciseArray['equip_title']) && !empty($exerciseArray['equip_title'])) {
																$response .= '<span class="tag-item label label-info" id="'.$exerciseArray['equip_id'].'"><label class="radio-primary"><input type="radio" name="xru_equip" value="'.$exerciseArray['equip_id'].'" checked="" title="Primary Muscle" data-role="none" data-ajax="false">'.$exerciseArray['equip_title'].'</label><span data-role="remove"></span></span> ';
																if(isset($exerciseEquipOth) && count($exerciseEquipOth)>0){
																	foreach ($exerciseEquipOth as $extactEquipoth) {
																		if(isset($extactEquipoth['equipoth_id']) && !empty($extactEquipoth['equipoth_id']) && isset($extactEquipoth['equip_title']) && !empty($extactEquipoth['equip_title'])) {
																			$response .= '<span class="tag-item label label-info" id="'.$extactEquipoth['equipoth_id'].'"><label class="radio-primary"><input type="radio" name="xru_equip" value="'.$extactEquipoth['equipoth_id'].'" title="Primary Muscle" data-role="none" data-ajax="false">'.$extactEquipoth['equip_title'].'</label><span data-role="remove"></span><input type="hidden" class="Otherequip" name="chkdEquipOth[]" value="'.$extactEquipoth['equipoth_id'].'"></span> ';
																		}
																	}
																}
															}else{
																$response .= '<input type="hidden" name="xru_equip" value="">';
															}
														$response .= '</li>
													</ul>';
													$equip = $workoutModel->getcheckboxes('equip','unit_','_title','_id','unit_gendata','equip','0'); 
													if(isset($equip) && count($equip)>0) {
														$response .= '<div class="dropdown selectdropdownTwo equip-selectbox" style="display: none;">
															<select tabindex="6" class="" id="list_equipments" data-ajax="false" data-role="none">
																<option value="" selected="">Select an option</option>';
																foreach($equip as $key => $value) {
																	$response .= '<option value="'.$value['equip_id'].'">'.$value['equip_title'].'</option>';
																}
															$response .= '</select>
														</div>
														<a href="javascript:void(0);" tabindex="6" class="btn btn-default btn-sm add-equip" onclick="showEquipmentSelectbox();" data-ajax="false" data-role="none"><i class="fa fa-plus"></i> Add a equipment</a>';
													}
												$response .= '</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="mobpadding exersetcolumn-xr">
										<div class="border-xr full">
											<div class="col-xs-3 firstcell borderright">
												<p class="labelcol">'.__('Mechanics Type').' <span class="activedatacol">*</span></p>
											</div>
											<div class="col-xs-9 secondcell datacol">
												<div class="form-group">';
													$mech = $workoutModel->getcheckboxes('mech','unit_','_title','_id','unit_gendata','mech','0');
													if(isset($mech) && count($mech)>0) {
														$response .= '<div class="dropdown selectdropdownTwo">
															<select tabindex="7" class="" id="xru_mech" name="xru_mech" data-ajax="false" data-role="none">
																<option value="">Select an option</option>';
																foreach($mech as $key => $value) {
																	$response .= '<option value="'.$value['mech_id'].'"'.(isset($exerciseArray['mech_id']) && $exerciseArray['mech_id'] == $value['mech_id'] ? "selected" : "").'>'.$value['mech_title'].'</option>';
																}
															$response .= '</select>
														</div>';
													}
												$response .= '</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="mobpadding exersetcolumn-xr">
										<div class="border-xr full">
											<div class="col-xs-3 firstcell borderright">
												<p class="labelcol">'.__('Exercise Level').' <span class="activedatacol">*</span></p>
											</div>
											<div class="col-xs-9 secondcell datacol">
												<div class="form-group">';
													$level = Model::instance('Model/workouts')->getcheckboxes('level','unit_','_title','_id','unit_gendata','level','0');
													if(isset($level) && count($level)>0) {
														$response .= '<div class="dropdown selectdropdownTwo">
															<select tabindex="8" class="" id="xru_level" name="xru_level" data-ajax="false" data-role="none">';
																foreach($level as $key => $value) {
																	$response .= '<option value="'.$value['level_id'].'"'.((isset($exerciseArray['level_id']) && $exerciseArray['level_id'] == $value['level_id']) || (!isset($exerciseArray['level_id']) && $value['level_id'] == 1) ? "selected" : "").'>'.$value['level_title'].'</option>';
																}
															$response .= '</select>
														</div>';
													}
												$response .= '</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="mobpadding exersetcolumn-xr">
										<div class="border-xr full">
											<div class="col-xs-3 firstcell borderright">
												<p class="labelcol">'.__('Sport').' <span class="activedatacol">*</span></p>
											</div>
											<div class="col-xs-9 secondcell datacol">
												<div class="form-group">';
													$sport = $workoutModel->getcheckboxes('sport','unit_','_title','_id','unit_gendata','sport','0');
													if(isset($sport) && count($sport)>0) {
														$response .= '<div class="dropdown selectdropdownTwo">
															<select tabindex="9" class="" id="xru_sports" name="xru_sports" data-ajax="false" data-role="none">';
																foreach($sport as $key => $value) {
																	$response .= '<option value="'.$value['sport_id'].'"'.((isset($exerciseArray['sport_id']) && $exerciseArray['sport_id'] == $value['sport_id']) || (!isset($exerciseArray['sport_id']) && $value['sport_id'] == 2) ? "selected" : "").'>'.$value['sport_title'].'</option>';
																}
															$response .= '</select>
														</div>';
													}
												$response .= '</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="mobpadding exersetcolumn-xr">
										<div class="border-xr full">
											<div class="col-xs-3 firstcell borderright">
												<p class="labelcol">'.__('Force Movement').' <span class="activedatacol">*</span></p>
											</div>
											<div class="col-xs-9 secondcell datacol">
												<div class="form-group">';
													$force = $workoutModel->getcheckboxes('force','unit_','_title','_id','unit_gendata','force','0');
													if(isset($force) && count($force)>0) {
														$response .= '<div class="dropdown selectdropdownTwo">
															<select tabindex="10" class="" id="xru_force" name="xru_force" data-ajax="false" data-role="none">
																<option value="">Select an option</option>';
																foreach($force as $key => $value) {
																	$response .= '<option value="'.$value['force_id'].'"'.(isset($exerciseArray['force_id']) && $exerciseArray['force_id'] == $value['force_id'] ? "selected" : "").'>'.$value['force_title'].'</option>';
																}
															$response .= '</select>
														</div>';
													}
												$response .= '</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="mobpadding exersetcolumn-xr">
										<div class="border-xr full">
											<div class="col-xs-3 firstcell borderright">
												<p class="labelcol">'.__('Description').
													'&nbsp;<img class="info-icon" src="'.URL::base().'assets/images/icons/information.png'.'">
													<span class="tooltip hide">
														This section is aimed to provide GENERAL details about this exercise or movement.<br><br><strong>Note:</strong>Share up to 1,000 characters of content.<br><br><em>Note: </em>This information WILL NOT scroll vertically.<br><br>
													</span>
												</p>
											</div>
											<div class="col-xs-9 secondcell datacol">
												<div class="form-group">
													<textarea tabindex="11" placeholder="'.__('No content, click to update').'." name="xru_descbr" id="xru_descbr" class="form-control" data-ajax="false" data-role="none">'.(isset($exerciseArray['descbr']) ? $exerciseArray['descbr'] : '').'</textarea>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="mobpadding exersetcolumn-xr">
										<div class="border-xr full">
											<div class="form-group">
												<div class="tab_heading aligncenter seq_title">'.__('Sequence Instruction').'</div>
												<div class="seqerror">
													<div class="col-xs-12">
														<small></small>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row collapse navbar-collapse-actions innerpage" style="display: block;">
									<div class="mobpadding">
										<div class="border full optionmenu">
											<div id="addSeq" class="menuactive">
												<button class="btn seq_btn btn-default" type="button" tabindex="12" data-class="add_seq" data-ajax="false" data-role="none"><i class="fa fa-plus"></i></button>
												<br><span class="inactivedatacol">'.__('new step').'</span>
											</div>
											<div class="allowhide hide">
												<button id="checkseq" class="btn btn-default" type="button" tabindex="14" onclick="return checkAllItems(this)" data-ajax="false" data-role="none"><i class="fa fa-check-circle-o"></i></button>
												<br><span class="inactivedatacol">'.__('all/none').'</span>
											</div>
											<div class="allowhide hide">
												<button id="deleteseq" class="btn btn-default" type="button" tabindex="15" onclick="return deleteSeqItem();" data-ajax="false" data-role="none"><i class="fa fa-times allowActive datacol"></i></button>
												<br><span class="inactivedatacol">'.__('delete').'</span>
											</div>
											<div class="borderright"></div>
											<div class="">
												<button id="editseq" class="btn btn-default" type="button" tabindex="13" onclick="return editSeqenceList(this);" data-ajax="false" data-role="none"><i class="fa fa-list-ul"></i></button>
												<button id="refreshseq" class="btn btn-default hide" type="button" onclick="return exitEditSeqenceList(this);" data-ajax="false" data-role="none"><i class="fa fa-refresh"></i></button>
												<br><span class="inactivedatacol">'.__('steps/list').'</span>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<ul id="seq_list" class="" data-role="none" data-ajax="false">';
										if(isset($exerciseSeq) && count($exerciseSeq)>0) {
											$si = 1; $rd = count($exerciseSeq)-1; $ri=0;
											foreach ($exerciseSeq as $seqkey => $seqvalues) {
												$response .= '<li class="seq_order='.$si.' seq-panel">
													<div class="row">
														<div class="mobpadding exersetcolumn-xr">
															<div class="border-xr full">
																<!--.seq_img -->
																<div class="col-xs-3 firstcell borderright">
																	<div class="seq-check form-group checkbox-checker col-xs-4" style="display: none;">
																		<div class="checkboxcolor">
																			<label>
																				<input type="checkbox" class="checkhidden" name="check_act[]" value="'.$si.'" data-role="none" data-ajax="false">
																				<span class="cr checkbox-circle"><i class="cr-icon fa fa-check"></i></span>
																			</label>
																		</div>
																	</div>
																	<div class="seq-img form-group img-div col-xs-12">
																		<span class="img_thmb datacol textcenter">
																			<img id="seq-feature'.$si.'" class="img-responsive img-thumbnail uploaded_image_thmb" tabindex="16" src="'.(isset($seqvalues['img_url']) ? '/'.$seqvalues['img_url'] : URL::base().'assets/images/icons/picture-icon.png').'" alt="'.__('Sequence Image').'">
																		</span>
																		<input type="hidden" class="img_selected" id="seq_img'.$si.'" name="seqImg[]" value="'.(isset($seqvalues['img_id']) ? $seqvalues['img_id'] : '').'">
																		<div class="img-opt">
																			<div class="trigger-imgopt" id="seqclear'.$si.'" data-imgtagid="seq-feature'.$si.'" data-hidnimgid="seq_img'.$si.'" href="'. URL::base().'assets/images/icons/picture-icon.png'.'"></div>
																		</div>
																	</div>
																</div>
																<!--seq_desc -->
																<div class="col-xs-9 secondcell datacol">
																	<div class="seq-desc form-group">
																		<textarea id="seqDesc'.$si.'" tabindex="17" placeholder="'.__('No content, click to update').'." class="seq_desc form-control" name="seqDesc[]" data-ajax="false" data-role="none">'.(isset($seqvalues['seq_desc']) ? $seqvalues['seq_desc'] : '').'</textarea>
																	</div>
																</div>
																<div class="col-xs-2 aligncenter seq-sort hide">
																	<span class="seq-move fa fa-arrows iconsize2"></span>
																</div>
															</div>
														</div>
													</div>
												</li>';
												$si++; $rd--; $ri++;
											}
										} else {}
									$response .= '</ul>
								</div>
								<div class="row">
									<div class="mobpadding exersetcolumn-xr">
										<div class="border-xr full">
											<div class="col-xs-3 firstcell borderright">
												<p class="labelcol">'.__('Video Demo').
													'&nbsp;<img class="info-icon" src="'.URL::base().'assets/images/icons/information.png'.'">
													<span class=" tooltip hide">
														Share a video link from youtube.<br><br>This video will be displayed in the lightbox pop-up.<br><br>This video will be visible when the record\'s <em>ad banner</em> has been clicked form the lightbox pop-up.
													</span>
												</p>
											</div>
											<div class="col-xs-9 secondcell datacol" onclick="return false;">
												<div class="form-group">
													<input type="text" tabindex="18" value="'.(isset($exerciseArray['feat_vid']) ? $exerciseArray['feat_vid'] : '').'" placeholder="'.__('Youtube or Vimeo URL here').'" id="xru_featVideo" name="xru_featVideo" class="form-control" data-ajax="false" data-role="none">
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="mobpadding exersetcolumn-xr">
										<div class="border-xr full">
											<div class="col-xs-3 firstcell borderright">
												<p class="labelcol">'.__('Other Remarks').
													'&nbsp;<img class="info-icon" src="'.URL::base().'assets/images/icons/information.png'.'">
													<span class="tooltip hide">
														This section is aimed to provide more thorough details, benefits &amp; applications.<br><br><strong>Note:</strong>Share up to 1,000 characters of content.<br><br>This information will scroll vertically.
													</span>
												</p>
											</div>
											<div class="col-xs-9 secondcell datacol">
												<div class="form-group">
													<textarea name="xru_descfull" id="xru_descfull" class="form-control" tabindex="19" placeholder="'.__('No content, click to update').'." data-ajax="false" data-role="none">'.(isset($exerciseArray['descfull']) ? $exerciseArray['descfull'] : '').'</textarea>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="mobpadding exersetcolumn-xr">
										<div class="border-xr full">
											<div class="col-xs-3 firstcell borderright">
												<p class="labelcol">'.__('Tags').'</p>
											</div>
											<div class="col-xs-9 secondcell datacol">
												<div class="form-group">
													<div class="tags-block" data-enhance="false" data-role="none" data-ajax="false" tabindex="20">';
														if(isset($exerciseTags) && count($exerciseTags)>0){
															$tagarr=array();
															foreach ($exerciseTags as $tagkey => $tagvalue) {
																$tagarry[] = ($tagvalue['tag_title']);
															}
															$tagval = implode(',', $tagarry);
														}
														$response .= '<input type="text" class="form-control xru_Tags" name="xru_Tags" placeholder="Tags" value="'.(!empty($tagval) ? $tagval : '').'" data-role="tagsinput" data-ajax="false"/>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div><!-- /end .tab-content-->
							<button class="btn hide" type="submit" id="btn_saveclose" value="save" name="f_method" data-ajax="false" data-role="none"></button>
							<button class="btn hide" type="submit" id="btn_savecontn" value="save-edit" name="f_method" data-ajax="false" data-role="none"></button>
							<input type="hidden" id="requestflag" name="requestflag" value="'.(!empty($requestFrom) ? $requestFrom : '').'"/>
							<input type="hidden" id="actionflag" name="actionflag" value="'.(!empty($actionFrom) ? $actionFrom : '').'"/>
							<input type="hidden" id="xrid" name="xrid" value="'.(!empty($XrRecid) && is_numeric($XrRecid) && empty($addrequest) ? $XrRecid : '').'" data-addid="'.(!empty($XrRecid) && is_numeric($XrRecid) && !empty($addrequest) ? $XrRecid : '').'" data-addtype="'.(!empty($XrRecid) && is_numeric($XrRecid) && !empty($addrequest) ? $addrequest : '').'"/>
						</form>
					</div>
				</div>
			</div>
		</div><!-- modal body -->
		<div class="modal-footer">
			<button id="btn_revert" class="btn btn-default pull-left" type="button" data-ajax="false" data-role="none">'.__('Reset').'</button>
			<button type="button" class="btn btn-default confirm" data-onclick="triggerCloseXrciseCreateModal();" data-allow="'.(Helper_Common::getAllowAllAccessByUser((Session::instance()->get('user_allow_page') ? Session::instance()->get('user_allow_page') : '1'), 'is_confirm_xr_create_hidden') ? 'false' : 'true').'" data-notename="hide_confirm_xr_create" data-text="Clicking BACK or CANCEL will discard any changes. Clicking MORE will display record options such as SAVE. Continue with exiting?" title="'.__('Cancel').'" data-ajax="false" data-role="none" style="margin-right: 20px;">'.__('Cancel').'</button>
			<button type="button" class="btn btn-default submitTabsBtn activedatacol" data-toggle="modal" data-target="#xrcisesaveopt-modal" data-ajax="false" data-role="none">'.__('more').'</button>
		</div>'.HTML::script('assets/js/pages/front/exercisecreate.js');
		echo json_encode(array('content'=>$response));
	}
	public function action_getAjaxExerciseShareHtml(){
		$userid = Auth::instance()->get_user()->pk();
		$current_site_id = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
		$current_site_name = (Session::instance()->get('current_site_name') ? Session::instance()->get('current_site_name') : '0');
		$workoutModel = ORM::factory('workouts');
		$response = '';
		// echo "<pre>"; print_r($_POST);exit;
		if(HTTP_Request::POST == $this->request->method()){
			$action 	= $this->request->post('action');
			$Xrid 	= $this->request->post('xrid');
			$title 	= $this->request->post('title');
			$actFrom	= $this->request->post('actFrom');
			$reqFrom	= $this->request->post('reqFrom');
			$option	= $this->request->post('option');
		}
		if(!empty($action) && $action=='shareExercise'){
			$response = '<div class="vertical-alignment-helper">
				<div class="modal-dialog">
					<div class="modal-content">
						<form id="form_shareExercise" data-ajax="false" action="" method="post">';
							if($actFrom == 'page' && $reqFrom == 'admin' && ($option == 'multiple' || $option == 'single')){
								if(!empty($Xrid) && is_array($Xrid)){
									$response .= '<input type="hidden" id="xr_share_id" name="xr_share_id[]" value="'.implode(',', $Xrid).'"/>';
								}
							}else{
								$response .= '<input type="hidden" id="xr_share_id" name="xr_share_id" value="'.$Xrid.'"/>';
							}
							$response .= '<div class="modal-header">
								<div class="row">
									<div class="mobpadding">
										<div class="border">
											<div class="col-xs-2">
												<a data-role="none" data-ajax="false" href="javascript:void(0);" onclick="closeModelwindow('."'sharexrcise-modal'".');" class="triangle">
													<i class="fa fa-chevron-left iconsize2"></i>
												</a>
											</div>
											<div class="col-xs-8 optionpoptitle">Share Exercise</div>
											<div class="col-xs-2">
												<button name="f_method" class="btn btn-default activedatacol share-xrcise" type="'.($actFrom == 'page' && $reqFrom == 'front' ? 'submit' : 'button').'" '.(Helper_Common::is_admin() || Helper_Common::is_manager() || Helper_Common::is_trainer() ? 'onclick="return checkValidAdminXrShareInfo();"' : 'onclick="return checkValidXrShareInfo();"').' value="share_exercise"  data-ajax="false" data-role="none">share</button>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="modal-body">
								<div class="aligncenter">
									<div class="col-xs-12 share-errormsg error-color hide">sdfsdfsdfsdf</div>
								</div>
								<div class="row">
									<div class="col-xs-12">
										<div class="form-group" data-ajax="false" data-role="none">
											<label class="control-label">Exercise:</label>';
											if($actFrom == 'page' && $reqFrom == 'admin' && ($option == 'multiple' || $option == 'single')){
												if(!empty($title) && is_array($title)){
													$response .= '<div class="bootstrap-tagsinput-preview">
														<div class="taginfo">';
															foreach ($title as $key => $value) {
																$response .= '<span class="tag label label-info">'.$value.'</span> ';
															}
														$response .= '</div>
													</div>';
												}
											}elseif(!empty($title) && !is_array($title)){
												$response .= '<div class="bootstrap-tagsinput-preview">
													<div class="taginfo">
														<span class="tag label label-info">'.$title.'</span>
													</div>
												</div>';
											}else{
												$response .= '<input type="text" class="form-control sharetitle-input-tag" name="sharetitle-input" value="" placeholder="Title" data-role="tagsinput"/> ';
											}
										$response .='</div>
										<div class="form-group" style="display:'.(Helper_Common::is_admin() || Helper_Common::is_manager() || Helper_Common::is_trainer() ? 'block' : 'none').'">
											<label class="control-label" for="xr_site_names">Site(s):</label>
											<input type="text" class="form-control" id="xr_site_names" name="seletedSite[]" style="width: 100%;" data-ajax="false" data-role="none"/>
										</div>
										<div class="form-group">
											<label class="control-label" for="xr_user_names">Recipient(s):</label>
											<input type="text" class="form-control" id="xr_user_names" name="seletedUser[]" style="width: 100%;" data-ajax="false" data-role="none"/>';
											if($actFrom == 'page' && $reqFrom == 'admin'){
												$response .= '<a href="javascript:void(0);" id="ad_filter" class="hide" style="float:right;font-size:10px" onclick="fiters_user();">Advanced Filter Options</a>';
											}
										$response .= '</div>
										<div class="form-group">
											<label class="control-label" for="xr_share_msg">Message:</label>
											<textarea id="xr_share_msg"name="xr_share_msg" class="form-control" placeholder="Enter Message" style="resize: none; data-ajax="false" data-role="none""></textarea>
										</div>
									</div>
								</div>
							</div>
							<div class="modal-footer">
							   <button type="button" class="btn btn-default" onclick="closeModelwindow('."'sharexrcise-modal'".');" data-role="none" data-ajax="false">close</button>
					   		<button name="f_method" class="btn btn-default activedatacol share-xrcise" type="'.($actFrom == 'page' && $reqFrom == 'front' ? 'submit' : 'button').'" '.(Helper_Common::is_admin() || Helper_Common::is_manager() || Helper_Common::is_trainer() ? 'onclick="return checkValidAdminXrShareInfo();"' : 'onclick="return checkValidXrShareInfo();"').' value="share_exercise"  data-ajax="false" data-role="none">share</button>
							</div>
						</form>
					</div>
				</div>
			</div>';
		}
		echo json_encode(array('content'=>$response, 'siteid'=>$current_site_id, 'sitename'=>$current_site_name));
	}
	// for exercise lib modal
	public function action_insertTagFromXrciseModal(){
		$workoutModel = ORM::factory('workouts');
		$msg = ''; $xrcisetags = '';
		if(isset($_POST['action']) && $_POST['action'] == 'xr-tagging'){
			if(isset($_POST['xrtag-input']) && (isset($_POST['xrunitid']) && !empty($_POST['xrunitid']))){
				$tagresult = $workoutModel->insertUnitTagById($_POST['xrtag-input'], $_POST['xrunitid']);
				if($tagresult === 'no-tag'){
					$msg = 'fail';
				}elseif($tagresult){
					$msg = 'success';
				}else{
					$msg = 'error';
				}
				$xrcisetags = $workoutModel->getUnitTagsById($_POST['xrunitid']);
			}
		}else{
			$msg = 'error';
		}
		echo json_encode(array('msg'=>$msg, 'xrtags'=>$xrcisetags));
	}
	public function action_insertRatingFromXrciseModal(){
		$workoutModel = ORM::factory('workouts');
		$user_id = Auth::instance()->get_user()->pk();
		$msg = ''; $ratingflag = true;
		if(isset($_POST['action']) && $_POST['action'] == 'xr-rating' && isset($_POST['slider-1'])){
			$rating['unit_id'] = $_POST['unit_id'];
			$rating['rate_value'] = $_POST['slider-1'];
			$rating['rate_comments'] = $_POST['rating_msg'];
			$rating['created_date']  = $rating['modified_date'] = Helper_Common::get_default_datetime();
			$rateId = $workoutModel->insertRatingDetails($rating, $user_id);
			if(is_int($rateId)){
				$workoutModel->insertActivityFeed(5, 25, $rating['unit_id']);
				$msg = 'success';
			}else{
				$msg = 'error';
			}
			$ratingflag = $workoutModel->isUserRatedbyUnitId($rating['unit_id'], $user_id);
		}else{
			$msg = 'fail';
		}
		echo json_encode(array('msg'=>$msg, 'ratings'=>$ratingflag));
	}
	public function action_shareExerciseFromXrciseModal(){
		$workoutModel = ORM::factory('workouts');
		$site_id = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
		$user_id = Auth::instance()->get_user()->pk();
		$action = $this->request->post('action');
		$unit_id = $this->request->post('xr_share_id');
		$shared_msg= $this->request->post('xr_share_msg');
		$selectedUser = $this->request->post('seletedUser');
		$selectedSite = $this->request->post('seletedSite');
		$shareArr['user_ids'] = $shareArr['site_ids'] = '';
		$msg = 'error';
		if(isset($selectedSite[0])){
			$shareArr['site_ids'] = explode(',', $selectedSite[0]);
		}
		if(isset($selectedUser[0])){
			$shareArr['user_ids'] = explode(',', $selectedUser[0]);
		}
		if(!empty($action) && $action=='sharing'){
			foreach($shareArr['site_ids'] as $key => $siteid){
				foreach($shareArr['user_ids'] as $keys => $userid){
					$allsites = Helper_Common::getAllSiteIdByUser($userid);
					if(in_array($siteid, $allsites)){
						$exerciseShareId = $workoutModel->CopyDeleteExerciseRecById('exerciseRecord', $unit_id, 'share', array('shared_by'=>$user_id, 'shared_for'=>$userid, 'shared_msg'=>$shared_msg, 'site_id'=>$siteid));
						$sharingact = $this->_sendExerciseShareEmailToUser($exerciseShareId['shared_xrid'], $siteid, $userid, $unit_id);
						if($sharingact){
							$msg = 'success';
						}
					}
				}
			}
		}
		echo json_encode(array('msg'=>$msg));
	}
	public function action_getAjaxShowMoreImages(){
		$imagelibrary 	= ORM::factory('imagelibrary');
		$folderid 		= (isset($_GET['fid']) && !empty($_GET['fid']) ? $_GET['fid'] : '');
		$subfolderid 	= (isset($_GET['subfid']) && !empty($_GET['subfid']) ? $_GET['subfid'] : '');
		$slimit 		= (isset($_GET['slimit']) && !empty($_GET['slimit']) ? $_GET['slimit'] : '0');
		$elimit 		= (isset($_GET['elimit']) && !empty($_GET['elimit']) ? $_GET['elimit'] : '10');
		$moreitems = '';
		if(!empty($folderid) || !empty($subfolderid)){
			if($folderid == '2')
				$subfolderid = '5';
			$moreitems = $imagelibrary->getFolderImages($subfolderid, $folderid, $slimit, $elimit);
		}else{
		}
		echo json_encode( array("items"=>$moreitems) );
	}
	public function action_getAjaxSeqImages(){
		$workoutModel 	= ORM::factory('workouts');
		$exerciseid = (isset($_POST['recordid']) && !empty($_POST['recordid']) ? $_POST['recordid'] : 0);
		$getseqdata = '';
		if(!empty($exerciseid)){
			$getseqdata = $workoutModel->getSequencesByUnitId($exerciseid);
			$getseqdataval = array();
			foreach($getseqdata as $keys => $values){
				if(!empty($values['img_url']) && file_exists($values['img_url']))
					$getseqdataval[] = $values;
			}
		}
		echo json_encode( array("items"=>$getseqdataval) );
	}
	public function action_fetchXrRecordsByFolder(){
		if(isset($_POST) && !empty($_POST)){
			$xrciseRecord = array(array('itemcount' => 0, 'folder' => ''));
			$workoutModel = ORM::factory('workouts');
			$current_site_id = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
			$sitedata = $workoutModel->getSiteTableData($current_site_id);
			$samplexrflag = $sitedata[0]['exercise_records'];
			if(($samplexrflag==0 || $samplexrflag=='')){
			}else{
				$xrciseRecord = $workoutModel->getExerciseRecordsByFolder($_POST);
			}
			echo json_encode( array("items"=>$xrciseRecord) );
		}
	}
	public function action_ajaxInsertActivityfeed(){
		if(isset($_POST) && !empty($_POST['actid'])){
			$workoutModel = ORM::factory('workouts');
			$actid = $_POST['actid'];
			$method = $_POST['method'];
			$type = $_POST['type'];
			$feedtype = $actiontype = ''; $feedjson = array();
			if($type == 'exercise'){
				$feedtype = 5;
			}elseif($type == 'image'){
				$feedtype = 9;
			}elseif($type == 'image data'){
				$feedtype = 16;
			}
			if($method == 'previewed'){
				$actiontype = 42;
			}elseif($method == 'opened'){
				$actiontype = 15;
				$feedjson['text'] = 'in edit-mode';
			}elseif($method == 'exited'){
				$actiontype = 44;
				$feedjson['text'] = 'without saving';
			}
			echo json_encode($workoutModel->insertActivityFeed($feedtype, $actiontype, $actid, $feedjson));
		}
	}
	public function _sendExerciseShareEmailToUser($sharedXrId, $site_id, $user_id, $unit_id){
		$smtpmodel    			= ORM::factory('admin_smtp');
		$shareworkoutmodel 	= ORM::factory('admin_shareworkout');
		$workoutModel			= ORM::factory('workouts');
		$current_site_id = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
		if (isset($sharedXrId) && is_numeric($sharedXrId) && !empty($sharedXrId)) {
			$sites			= Helper_Common::hasSiteAccess($site_id);
			$user				= $shareworkoutmodel->getuserdetails($user_id);
			$user				= $user[0];
			$templateArray = $smtpmodel->getSendingMailTemplate(array(
			    'type_name' => 'notification - shared workout'
			));
			$encryptedmessage = Helper_Common::encryptPassword($user['user_email'].'####'.$user['security_code'].'####exerciserecord');
			$exerciseUrl = URL::site(NULL, 'http').(isset($sites["slug"]) ? $sites["slug"] : '')."/index/autoredirect/".$sharedXrId."/".$encryptedmessage;
			$templateArray['body'] = str_replace(array(
					'workout',
					'Workout Plan',
					'Workout Plans',
					'[trainer_name]',
					'[site_title]',
					'[share_exercise_plan_link]'
				), array(
					'exercise',
					'Exercise',
					'Exercises',
					ucfirst(strtolower(Auth::instance()->get_user()->user_fname)),
					($sites) ? $sites["name"] : '', $exerciseUrl ), $templateArray['body']
				);
			$messageArray = array(
				'subject' => str_replace(array(
					'Workout Plan',
					'[trainer_name]'
				), array(
					'Exercise',
					ucfirst(strtolower(Auth::instance()->get_user()->user_fname))
				), $templateArray['subject']),
				'from' => (!empty($templateArray['smtp_replyto']) ? $templateArray['smtp_replyto'] : 'info@myworkouts.com'),
				'fromname' => (!empty($templateArray['smtp_from']) ? $templateArray['smtp_from'] : 'My workouts'),
				'to' => $user["user_email"],
				'replyto' => (!empty($templateArray['smtp_replyto']) ? $templateArray['smtp_replyto'] : 'info@myworkouts.com'),
				'toname' => ucfirst(strtolower($user['user_fname'])) . ' ' . ucfirst(strtolower($user['user_lname'])),
				'body' => ORM::factory('admin_smtp')->merge_keywords($templateArray['body'], $current_site_id),
				'type' => 'text/html'
			);
			if(is_array($templateArray) && !empty($templateArray) && !empty($templateArray['smtp_id']) && false){
				$hostAddress = explode("://", $templateArray['smtp_host']);
				$emailMailer = Email::dynamicMailer('smtp', array(
					'hostname' => trim($hostAddress['1']),
					'port' => $templateArray['smtp_port'],
					'username' => $templateArray['smtp_user'],
					'password' => Helper_Common::decryptPassword($templateArray['smtp_pass']),
					'encryption' => trim($hostAddress['0'])
				));
			}else{
				$emailMailer = Email::dynamicMailer('', array());
			}
			Email::sendBysmtp($emailMailer, $messageArray);
			/*Activity Feed */
			if(!empty($user_id)){
				$feedjson = array();
				$feedjson[] = $user_id;
				$workoutModel->insertActivityFeed(5, 7, $unit_id, $feedjson);
			}
		}
		return true;
	}

	public function action_getrecipient(){
		$usermodel             = ORM::factory('admin_subscriber');
		$adminworkoutmodel     = ORM::factory('admin_workouts');
		$adminusermodel        = ORM::factory('admin_user');
		$roleid                                      = $adminusermodel->user_role_load_by_name('Register');
	 // $siteid                                    = $this->current_site_id;
		$focusRecord      						   = $adminworkoutmodel->getAllFocus();
		$role                                        = Helper_Common::get_role("manager");
		$manager                                     = Helper_Common::get_role_by_users($role, '');
	  // $this->template->content->manager          = $manager;
		$role                                        = Helper_Common::get_role("trainer");
		$trainer                                     = Helper_Common::get_role_by_users($role, '');
		// $this->template->content->trainer            = $trainer;
		$role                                        = Helper_Common::get_role("register");
		$subscriber                                  = Helper_Common::get_role_by_users($role, '');
		//$response['focusRecord']    = $focusRecord;
	  // $response['role']    		  = $role;
		$response['subscriber']     = $subscriber;
		$response['success']        = true;
		echo $this->request->response = json_encode($subscriber);
	}
	public function action_getFeedDetails()
	{
		if ($this->request->is_ajax())
			$this->auto_render = FALSE;
		$usermodel = ORM::factory('admin_user');
		$user_id   = $_POST['id'];
		$is_front  = (isset($_POST['is_front']) && $_POST['is_front'] ? true : false);
		$is_popup  = (isset($_POST['popupFlag']) && $_POST['popupFlag'] ? true : false);
		$current_site_id = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
		if (isset($user_id) && $user_id != '') {
			$user_details = $usermodel->get_user_by_condtn('user_fname,user_lname,user_gender,user_mobile', 'id=' . $user_id);
			$role_name    = $usermodel->getUsersRoleNamesByUserId($user_id);
			if (isset($user_details) && count($user_details) > 0) {
				$user_name         = '<span id="fname">' . $user_details[0]['user_fname'] . '</span> <span id="lname">' . $user_details[0]['user_lname'] . "</span>";
				$user_gender       = '<span id="gender">' .($user_details[0]['user_gender'] == '1' ? 'Male' : 'Female'). '</span>';
				$user_phone       	= '<span id="phone">' .$user_details[0]['user_mobile']. '</span>';
				$data['user_name'] 	= $user_name;
				$data['user_gender'] 	= $user_gender;
				$data['user_phone'] 	= $user_phone;
			}
			if (isset($role_name) && count($role_name) > 0) {
				$role_name_str     = implode(',', $role_name);
				$data['role_name'] = $role_name_str;
				//if ($data['role_name'] == 'register') {
				$get_register_details = $usermodel->get_user_by_condtn('user_dob', 'id=' . $user_id);
				if (isset($get_register_details) && count($get_register_details) > 0) {
					$data['user_dob'] = date('d M Y', strtotime($get_register_details[0]['user_dob']));
					$data['user_age'] = Helper_Common::get_age(date('d-m-Y', strtotime($get_register_details[0]['user_dob'])));
				}
				//}
			}
			$admin_subscriber_model = ORM::factory('admin_subscriber');
			$usertags               = $admin_subscriber_model->get_user_tags($user_id, $current_site_id);
			if ($usertags) {
				$ut = array();
				if (count($usertags) > 0) {
					foreach ($usertags as $usertag) {
						$ut[] = $usertag['tag_title'];
					}
				}
				$data['user_tags'] = implode(",", $ut);
			}
			$questionmodel         = ORM::factory('admin_questions');
			$answers = $questionmodel->getSingleAnswers($user_id);
			if(!empty($answers) && count($answers)>0){
				$height = $answers['height'] / 100;
				$bmiRate = round(($answers['weight'] / $height) / $height );
				if($bmiRate >= 18.5 && $bmiRate <= 24.9){
					$data['user_bmi']  = $bmiRate.' = Normal (18.5-24.9)';
				}else if($bmiRate >= 25 && $bmiRate <= 29.9){
					$data['user_bmi']  = $bmiRate.' = Overweight (25-29.9)';
				}else if($bmiRate >= 30 && $bmiRate <= 34.9){
					$data['user_bmi']  = $bmiRate.' = Obese(30-34.9)';
				}else if($bmiRate >= 35 && $bmiRate <= 39.9){
					$data['user_bmi']  = $bmiRate.' = Severly Obese (35 - 39.9)';
				}else if($bmiRate >= 40){
					$data['user_bmi']  = $bmiRate.' = Morbix Obese (40+)';
				}
			}
			$offset             = 0;
			$limit              = 20;
			$feed_details_array_all = $usermodel->get_feed_details($user_id, ($is_front ? '' : ($current_site_id == '1' ? '' : $current_site_id)), '', '', '');
			$feed_details_array = $usermodel->get_feed_details($user_id, ($is_front ? '' : ($current_site_id == '1' ? '' : $current_site_id)), '', $limit, $offset);
			if (isset($feed_details_array) && count($feed_details_array) > 0) {
				if($is_popup){
					$feed_details = '<div class="panel-body">
					<input type="hidden" id="af_limit_popup" value="' . $limit . '">
					<input type="hidden" id="af_all_popup" value="' . count($feed_details_array_all) . '">
					<input type="hidden" id="af_showmore_popup" value="' . $offset . '">
					<input type="hidden" id="af_userids_popup" value="' . $user_id . '">
					<input type="hidden" id="af_popup" value="1">
					<input type="hidden" id="af_site_popup" value="' . ($is_front ?  '' : ($current_site_id == '1' ? '' : $current_site_id)) . '">
					<div class="list-group" id=\'act_feed_popup\'>';
				}else{
					$feed_details = '<div class="panel-body">
					<input type="hidden" id="af_limit" value="' . $limit . '">
					<input type="hidden" id="af_all" value="' . count($feed_details_array_all) . '">
					<input type="hidden" id="af_showmore" value="' . $offset . '">
					<input type="hidden" id="af_userids" value="' . $user_id . '">
					<input type="hidden" id="af_site" value="' . ($is_front ?  '' : ($current_site_id == '1' ? '' : $current_site_id)) . '">
					<div class="list-group" id=\'act_feed\'>';
				}
				foreach ($feed_details_array as $key => $value) {
					$string = Helper_Activityfeed::activity_index($value , $is_front,$is_popup);
					$feed_details .= $string;
				}
				$feed_details .= '</div>';
				$feed_details.='</div><script>$(".panel-body").bind("scroll", function(e){ if($(this).scrollTop() + $(this).innerHeight()>=$(this)[0].scrollHeight){ if($("div#act_feed").length){show_more(e);}}});</script>';
				$data['feed_details'] = $feed_details;
			} else {
				$data['feed_details'] = '<div class="panel-body">No feed found</div>';
			}
			$offset                = 0;
			$limit                 = 20;
			$loggoal_details_array = $usermodel->get_loggoal_details($user_id, ($current_site_id == '1' ? '' : $current_site_id), $limit, $offset);
			$loggoalmain_array     = $musmainarray = $listgoalmustype = $listgoalmusname = $others_listar = $chloggoalmain_array = '';
			if (is_array($loggoal_details_array) && count($loggoal_details_array) > 0) {
				foreach ($loggoal_details_array as $key_val => $value_loggoal) {
					if ($value_loggoal['wkout_status'] == 1) {
						$musmainarray[$value_loggoal['musprim_id']][]  = $value_loggoal['goal_id'];
						$listgoalmustype[]                             = $value_loggoal['musprim_id'];
						$listgoalmusname[$value_loggoal['musprim_id']] = $value_loggoal['muscle_title'];
					}
				}
			}
			$key_val2set = 0;
			$liste       = array(); $strengthreportArr = array();
			if (is_array($listgoalmustype) && count($listgoalmustype) > 0) {
				foreach ($listgoalmustype as $key_val1 => $value_loggoal1) {
					if (isset($listgoalmusname[$value_loggoal1]) && isset($musmainarray[$value_loggoal1])) {
						if (!in_array($value_loggoal1, $liste) && round((count($musmainarray[$value_loggoal1]) / count($loggoal_details_array)) * 100) > 1) {
							$loggoalmain_array[$key_val2set]['label']      = $listgoalmusname[$value_loggoal1];
							$loggoalmain_array[$key_val2set]['data']       = round((count($musmainarray[$value_loggoal1]) / count($loggoal_details_array)) * 100);
							$chloggoalmain_array[$key_val2set]['label']    = $listgoalmusname[$value_loggoal1];
							$chloggoalmain_array[$key_val2set]['value']    = round((count($musmainarray[$value_loggoal1]) / count($loggoal_details_array)) * 100);
							$chloggoalmain_array[$key_val2set]['orgvalue'] = count($musmainarray[$value_loggoal1]);
							$strengthreportArr[$value_loggoal1] = $listgoalmusname[$value_loggoal1];//array for below strenght report generation
							array_push($liste, $value_loggoal1);
							$key_val2set++;
						} else if (!in_array($value_loggoal1, $liste)) {
							$others_listar = round((count($musmainarray[$value_loggoal1]) / count($loggoal_details_array)) * 100);
							array_push($liste, $value_loggoal1);
						}
					}
				}
			}
			if (count($loggoalmain_array) <= 1 && $others_listar != '') {
				$loggoalmain_array[$key_val2set]['label'] = 'Others';
				$loggoalmain_array[$key_val2set]['data']  = $others_listar;
			}
			if (count($chloggoalmain_array) <= 1 && $others_listar != '') {
				$chloggoalmain_array[$key_val2set]['label'] = 'Others';
				$chloggoalmain_array[$key_val2set]['data']  = $others_listar;
			}
			/*strength report generation*/
			$strength_report = '';
			if(isset($strengthreportArr) && count($strengthreportArr) > 0){
				$j=0;
				foreach ($strengthreportArr as $muscleid => $musclename) {
					$strength_report .= $this->strengthReport($muscleid, $musclename, $user_id, $is_front);
					if(count($strengthreportArr) != ($j+1)){
						$strength_report .='<hr>';
					}
					$j++;
				}
			}
			$data['contentchart']      = $loggoalmain_array;
			$data['profiledetails']    = $this->profiledetails($user_id, $is_front);
			$data['profilereports']    = $this->profilereports($user_id, $is_front);
			$data['checkcontentchart'] = $chloggoalmain_array;
			$data['strengthreport'] 	= preg_replace('/\<hr>$/', '', $strength_report);
			$data['totalgoal']         = count($loggoal_details_array);
			$data['success']		   = (isset($data) && count($data) > 0 ? true : false);
			$this->response->body(json_encode($data));
		}
	}
	public function action_getmorefeeddetails(){
		$user_id = Auth::instance()->get_user()->pk();
		if(isset($_POST) && count($_POST)>0) {
			$offset 	= $_POST["offset"];
			$limit 		= $_POST["limit"];
			$userids 	= $_POST["userids"];
			$site 		= $_POST["site"];
			$tot_feed_details = Model::instance('Model/admin/user')->get_feed_details($userids,$site,'','','');
			$tot_af = ($tot_feed_details)?count($tot_feed_details):0;		
			$feed_details = Model::instance('Model/admin/user')->get_feed_details($userids,$site,'',$limit,$offset);
			$string = "";$cnt = 0;
			if(isset($feed_details) && count($feed_details)>0) { 
				foreach($feed_details as $key => $value) {
					$cnt++;
					echo Helper_Activityfeed::activity_index($value,true);
				}
			}
			$tot = $offset+$cnt;
			echo $string;
		}
	}
	public function action_getStaticTemplate(){
		$action = isset($_GET['action']) ? $_GET['action'] : '';
		$method = isset($_GET['method']) ? $_GET['method'] : '';
		$modelType = isset($_GET['modelType']) ? $_GET['modelType'] : '';
		$firstName = $lastName = $email = $phone = '';
		if(Auth::instance()->logged_in()){
			$user 	= Auth::instance()->get_user();
			$firstName 	= ucfirst($user->user_fname);
			$lastName 	= ucfirst($user->user_lname);
			$email		= $user->user_email;
			$phone 		= $user->user_mobile;
		}
		if(isset($action) && $action=='contactus'){
			$response = '<div class="vertical-alignment-helper">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<div class="mobpadding">
								<div class="border">
									<div class="col-xs-2">
										<a href="javascript:void(0);" title="'.__('Back').'" class="triangle" onclick="closeModelwindow('."'userModal'".');" data-ajax="false" data-role="none">
											<i class="fa fa-chevron-left iconsize2"></i>
										</a>
									</div>
									<div class="col-xs-8 optionpoptitle">'.__('Contact Us').'</div>
									<div class="col-xs-2"></div>
								</div>
							</div>
						</div>
						<div class="modal-body">
							<div class="page_content">
								<div class="row">
									<div class="col-sm-12">
										<form method="post" id="'.$modelType.'" enctype="multipart/form-data">
											<div class="form-group">
												<label>'.__('First Name').'*</label>
												<input class="form-control" type="text" name="firstname" value="'.$firstName.'" required="true" data-role="none" data-ajax="false">
											</div>
											<div class="form-group">
												<label>'.__('Last Name').'</label>
												<input class="form-control" type="text" name="lastname" value="'.$lastName.'" data-role="none" data-ajax="false">
											</div>
											<div class="form-group">
												<label>'.__('Email').'*</label>
												<input class="form-control" type="email" name="email" value="'.$email.'" required="true" data-role="none" data-ajax="false">
											</div>
											<div class="form-group">
												<label>'.__('Phone').'</label>
												<input class="form-control" type="text" name="phone" value="'.$phone.'" data-role="none" data-ajax="false">
											</div>
											<div class="form-group">
												<label>'.__('Message').'*</label>
												<textarea class="form-control" name="message" rows="3" required="true" data-role="none" data-ajax="false"></textarea>
											</div>
											<div class="form-group">
												<label>'.__('Upload image if any').'</label>
												<div class="file-upload">
													<div class="btn btn-primary fileUpload" type="button" title="'.__('Upload Image File').'" data-ajax="false" data-role="none">
														<span class="fa fa-upload"></span>&nbsp;&nbsp;'.__('Upload Image').'<input type="file" id="contact-file" name="image" value="" data-role="none" data-ajax="false">
													</div>
													<span class="fileUpload-name"></span>
												</div>
											</div>
											<div class="hide">
												<button id="contact-submit" data-role="none" data-ajax="false" type="submit" class="btn">Submit</button>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button data-role="none" data-ajax="false" type="button" class="btn btn-default" onclick="closeModelwindow('."'userModal'".');" style="margin-right:10px;">'.__('Close').'</button>
							<button data-role="none" data-ajax="false" type="button" class="btn btn-default activedatacol" onclick="$('."'#contact-submit'".').trigger('."'click'".')">'.__('Contact Us').'</button>
						</div>
					</div>
				</div>
			</div>';
		}
		$this->response->body(json_encode($response));
	}
	public function action_contactus() {
		$current_site_id = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
		$sites = Helper_Common::site_data($current_site_id);
		$siteModel = ORM::factory('site');
		$smtpmodel = ORM::factory('admin_smtp');
		$response['data'] =  false;
		if ($this->request->method() == HTTP_Request::POST && count($sites) > 0) {
			$post = $_POST;
			$role = Helper_Common::get_role("admin");
			$admin = Helper_Common::get_role_by_users($role, '');   // Admin & Super Admin Role Users For All sites
			$role = Helper_Common::get_role("manager");
			$manager = Helper_Common::get_role_by_users($role, $current_site_id);   // Only For Site Managers 
			$users = array();
			if($admin && $sites["is_contact"]==1){
				$users = $admin;
			}
			else if($manager && $sites["is_contact"]==0){
				//$users = $manager;
				foreach($manager as $k=>$v){
					if($v["contact_status"]==1){
						$users[] = $v;
					}
				}
			}
			$post['attachment'] = ''; $valid_file = false; $flag = 1;
			if(isset($_FILES['image'])){
				if(!$_FILES['image']['error']){
					$rootdir = DOCROOT.'assets/uploads/manage/contactus/';
					$file_name = $_FILES['image']['name'];
					$file_size = $_FILES['image']['size'];
					$file_tmp = $_FILES['image']['tmp_name'];
					$file_type= $_FILES['image']['type'];
					$file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
					$file_ext = strtolower($file_ext);
					$expensions = array("jpeg","jpg","png");
					$valid_file = true;
					if(!in_array($file_ext, $expensions)){
						$valid_file = false;
						$response['data'] = false;
						$response['msg'] = 'Extension not allowed, please choose JPEG, JPG and PNG file.';
						unlink($file_tmp);
						$this->response->body(json_encode($response));
						return;
					}
					if($file_size > (2560000)){ //can't be larger than 2mb
						$valid_file = false;
						$response['data'] = false;
						$response['msg'] = 'Oops! File\'s size is too large.';
						unlink($file_tmp);
						$this->response->body(json_encode($response));
						return;
					}
				}
			}
			$post['dated'] = Helper_Common::get_default_datetime();
			$result = DB::insert('sitecontact', array_keys($post) )->values(array_values($post))->execute();
			if(isset($result) && count($users)>0){
				$contact_id = $result[0];
				$xrc = 0;
				if($valid_file){
					$imgname ='imgAttached_'.$contact_id.'.'.$file_ext;
					$imgfile = $rootdir.$imgname;
					$post['attachment'] = 'assets/uploads/manage/contactus/'.$imgname;
					if($this->compress_imgsize($file_tmp, $imgfile, 50)){
						$flag = 1;
					}else{
						$flag = 0;
					}
					if($flag){
						DB::update('sitecontact')->set(array('attachment' => $post['attachment']))->where('id', '=', $contact_id)->execute();
					}
					unlink($file_tmp);
				}
				foreach($users as $k=>$v){
					$con = array();
					$con["siteid"]    = $current_site_id;
					$con["contact_id"]= $contact_id;
					$con["userid"]    = $v["id"];
					//$con["email"]    = $v["email"];
					$res = DB::insert('sitecontact_mapping', array_keys($con) )->values(array_values($con))->execute();
					$messageArray ='';
					if(isset($res)){
						/*********************************Send Mail Box Email to Super Admin Role Users & Site Managers********************/
						$templateArray = $smtpmodel->getSendingMailTemplate(array('type_name' => 'Contact Us','site_id' => $current_site_id));
						if(is_array($templateArray) && !empty($templateArray) && !empty($templateArray['smtp_id'])){
							$templateArray['subject'] = str_replace(array('[Site]'),array($sites["name"]),$templateArray['subject']);
							$templateArray['body'] = str_replace(array('[Site]','[fname]','[lname]','[email]','[phone]','[message]'), array($sites["name"],$post["firstname"],$post["lastname"],$post["email"],$post["phone"],$post["message"]),$templateArray['body']);
							if(!empty($post['attachment']) && $flag){
								$templateArray['body'] .= '<p>Attached Image:&nbsp;&nbsp;</br><a href="'.URL::site(NULL, 'http').$post['attachment'].'" target="_blank"><img alt="Attached Image" src="'.URL::site(NULL, 'http').$post['attachment'].'" width="250px";></a></p>';
							}
							if($xrc==0){
								DB::update('sitecontact')->set(array('subject' =>$templateArray['subject']))->set(array('mailcontent' =>$templateArray['body']))
								->where('id', '=', $contact_id)->execute();
							}
							$xrc++;
							// print_r($templateArray);die;
							$messageArray = array('subject'	=> $templateArray['subject'],
								'from' 	=> (!empty($templateArray['smtp_replyto']) ? $templateArray['smtp_replyto'] : 'info@myworkouts.com'),
								'fromname'=> (!empty($templateArray['smtp_from']) ? $templateArray['smtp_from'] : 'My workouts'),
								'to'	=> 'mani@yopmail.com',
								//'to'		=> $v["user_email"],
								//'cc'     => "prabakaran@versatile-soft.com",
								'ccname' => "Developer", 
								'replyto'=> (!empty($templateArray['smtp_replyto']) ? $templateArray['smtp_replyto'] : 'info@myworkouts.com'),
								'toname'	=> ucfirst(strtolower($v['user_fname'])).' '.ucfirst(strtolower($v['user_lname'])),
								'body'	=> ORM::factory('admin_smtp')->merge_keywords($templateArray['body'], $current_site_id),
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
						}else{
							$emailMailer = Email::dynamicMailer('',array());
						}
						if(is_array($messageArray)) {
							Email::sendBysmtp($emailMailer, $messageArray); 
						}
						/*********************************Send Mail Box Email to Super Admin Role Users & Site Managers********************/
					}
				}
				$response['data'] = true;
			}else{
				$response['data'] = false;
				$response['msg'] = '';
			}
		}
		$this->response->body(json_encode($response));
	}
	public function compress_imgsize($source, $destination, $quality) {
		$info = getimagesize($source);
		if ($info['mime'] == 'image/jpeg') $image = imagecreatefromjpeg($source);
		elseif ($info['mime'] == 'image/gif') $image = imagecreatefromgif($source);
		elseif ($info['mime'] == 'image/png') $image = imagecreatefrompng($source);
		if(imagejpeg($image, $destination, $quality)){
			return true;
		}
		return false;
	}
	public function action_updateUserProfileInst(){
		$site_id = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
		$user_id = Auth::instance()->get_user()->pk();
		$questionmodel = ORM::factory('admin_questions');
		$userprofile = ORM::factory('userprofile');
		$workoutModel = ORM::factory('workouts');
		$updateflag = false;
		$activity_feed = array();
		$user_bmi = $weight_info = $user_device = $user_dob = '';
		//for get user weight unit
		$user_weight = (Session::instance()->get('user_weight') ? Session::instance()->get('user_weight') : '1');
		if($user_weight != 3 && $user_weight != 4)
			$set_resistance = Helper_Common::selectgeneralfn('set_resist', 'resist_title', 'resist_id = '.$user_weight);
		$weight_unit = 'kg';
		if(isset($set_resistance) && count($set_resistance) > 0){
			$weight_unit = (isset($set_resistance[0]['resist_title']) ? $set_resistance[0]['resist_title'] : 'kg');
		}
		// print_r($_POST);exit();
		if(isset($_POST)) {
			$id = (isset($_POST['user_id']) ? $_POST['user_id'] : '');
			if(!empty($id)){
				$updatefield = '';
				if(isset($_POST['gender']) && !empty($_POST['gender'])){
					$updatefield = 'user_gender = '.$_POST['gender'];
					$feedjson = 'to '.($_POST['gender'] == '1' ? 'Male' : 'Female');
					$workoutModel->insertActivityFeed(19, 32, $user_id, $feedjson);
				}else if(isset($_POST['birthdate']) && !empty($_POST['birthdate'])){
					$updatefield = 'user_dob = "'.Helper_Common::get_default_date($_POST['birthdate']).'"';
					$user_dob = Helper_Common::change_default_date_dob($_POST['birthdate']);
					$feedjson = 'to ';
					$contextdate = Helper_Common::get_default_datetime($_POST['birthdate']);
					$workoutModel->insertActivityFeed(19, 50, $user_id, $feedjson, $contextdate);
				}else if(isset($_POST['phone_no']) && !empty($_POST['phone_no'])){
					$updatefield = 'user_mobile = "'.$_POST['phone_no'].'"';
					$feedjson = 'to '.$_POST['phone_no'];
					$workoutModel->insertActivityFeed(19, 33, $user_id, $feedjson);
				}else if(isset($_POST['height']) && !empty($_POST['height'])){
					$updatefield = '';
					$checkrow = $userprofile->checkUserMeasurement($id, 'today');
					if($checkrow){
						$setfield = 'height = '.$_POST['height'];
						$userprofile->updateUserMeasurement($setfield, $id);
					}else{
						$columns = 'height';
						$values = $_POST['height'];
						$userprofile->insertUserMeasurement($columns, $values, $id);
					}
					$feedjson = 'to '.$_POST['height'].' cm';
					$workoutModel->insertActivityFeed(19, 49, $user_id, $feedjson);
					$updateflag = true;
				}else if(isset($_POST['weight']) && !empty($_POST['weight'])){
					$updatefield = '';
					$checkrow = $userprofile->checkUserMeasurement($id, 'today');
					if($checkrow){
						$setfield = "weight = ".$_POST['weight'].", weight_unit = '".$weight_unit."'";
						$userprofile->updateUserMeasurement($setfield, $id);
					}else{
						$columns = 'weight, weight_unit';
						$values = $_POST['weight'].", '".$weight_unit."'";
						$userprofile->insertUserMeasurement($columns, $values, $id);
					}
					$feedjson = 'to '.$_POST['weight'].' '.$weight_unit;
					$workoutModel->insertActivityFeed(19, 38, $user_id, $feedjson);
					$updateflag = true;
				}else if(isset($_POST['initheight']) && !empty($_POST['initheight'])){
					$updatefield = '';
					$updateflag = false;
					$answerdetail = $userprofile->getUserMeasurementAnswerDetail($id, '16');
					if(count($answerdetail) > 0){
						$retunval = $userprofile->updateUserMeasurementAnswer($id, 'height', $_POST['initheight']);
						if($retunval){
							$updateflag = true;
						}
					}else{
						$retunval = $userprofile->insertUserMeasurementAnswer($id, 'height', $_POST['initheight']);
						if($retunval){
							$updateflag = true;
						}
					}
				}else if(isset($_POST['initweight']) && !empty($_POST['initweight'])){
					$updatefield = '';
					$updateflag = false;
					$answerdetail = $userprofile->getUserMeasurementAnswerDetail($id, '17');
					if(count($answerdetail) > 0){
						$retunval = $userprofile->updateUserMeasurementAnswer($id, 'weight', $_POST['initweight']);
						if($retunval){
							$updateflag = true;
						}
					}else{
						$retunval = $userprofile->insertUserMeasurementAnswer($id, 'weight', $_POST['initweight']);
						if($retunval){
							$updateflag = true;
						}
					}
				}else if(isset($_POST['device']) && !empty($_POST['device'])){
					$updatefield = '';
					$updateflag = false;
					$devicevalues = $_POST['device'];
					$device_qid = $_POST['qid'];
					if(!empty($devicevalues)){
						foreach($devicevalues as $deviceval){
							$devicecheck = $userprofile->checkUserDeviceAnswer($id, $device_qid, $deviceval);
							if($devicecheck){
								$retunval = $userprofile->updateUserDevice($id, $device_qid, $deviceval);
								if($retunval){
									$updateflag = true;
								}
							}else{
								$retunval = $userprofile->insertUserDevice($id, $device_qid, $deviceval);
								if($retunval){
									$updateflag = true;
								}
							}
						}
						$devicedelete = $userprofile->deleteUserDevice($id, $device_qid, $devicevalues);
					}
					$devicename = $userprofile->getUserDeviceAnswerDetail($id, $device_qid);
					$deviceall = array();
					if(!empty($devicename)){
						foreach($devicename as $device) {
							$deviceall[] = $device['name'];
						}
					}
					$user_device = implode(', ', $deviceall);
				}
				if(!empty($updatefield)){
					Helper_Common::updategeneralfn('users', $updatefield, 'id='.$id);
					$updateflag = true;
				}
				if($updateflag && (isset($_POST['height']) || isset($_POST['weight']))){
					$useranswers = $userprofile->getUserMeasurementAnswer($id);
					$usermeasure = $userprofile->getUserMeasurement($id);
					if((!empty($useranswers) && count($useranswers) > 0) || (!empty($usermeasure) && count($usermeasure) > 0)){
						$height = (isset($usermeasure['height']) && !empty($usermeasure['height']) ? $usermeasure['height'] : (isset($useranswers['height']) && !empty($useranswers['height']) ? $useranswers['height'] : ''));
						$weight = (isset($usermeasure['weight']) && !empty($usermeasure['weight']) ? $usermeasure['weight'] : (isset($useranswers['weight']) && !empty($useranswers['weight']) ? $useranswers['weight'] : ''));
						if(!empty($weight)){
							$weightunit = (isset($usermeasure['weight_unit']) && !empty($usermeasure['weight_unit']) ? $usermeasure['weight_unit'] : 'kg');
							$weight_forbmi = Helper_Common::convertToMyWeightUnit('1', $weightunit, $weight);
							$user_bmi = Helper_Common::calculateBMI($height, $weight_forbmi);//(cm, kg)
						}
					}
					// calulate week diff
					$TW_weight = $LW_weight = 0;
					$TW_weightdtl = $userprofile->getUserMeasurementForWeek($id, 'this');
					if(!empty($TW_weightdtl['weight']) && !empty($TW_weightdtl['weight_unit'])){
						$TW_weight = Helper_Common::convertToMyWeightUnit($user_weight, $TW_weightdtl['weight_unit'], $TW_weightdtl['weight']);
						$TW_weight = (float)number_format($TW_weight, 2, '.', '');
					}
					$LW_weightdtl = $userprofile->getUserMeasurementForWeek($id, 'last');
					if(!empty($LW_weightdtl['weight']) && !empty($LW_weightdtl['weight_unit'])){
						$LW_weight = Helper_Common::convertToMyWeightUnit($user_weight, $LW_weightdtl['weight_unit'], $LW_weightdtl['weight']);
						$LW_weight = (float)number_format($LW_weight, 2, '.', '');
					}
					$W_weightdiff = '';
					if($TW_weight > 0 && $LW_weight > 0){
						$W_weightdiff = ($TW_weight - $LW_weight);
					}
					$weight_info = (!empty($W_weightdiff) ? ($W_weightdiff > 0 ? '+'.$W_weightdiff : $W_weightdiff).' '.__('from last week') : '');
				}
			}
		}
		$this->response->body(json_encode(array($updateflag, $user_bmi, $weight_info, $weight_unit, $user_device, $user_dob)));
	}
	public function profiledetails($user_id, $is_front){
		$current_userid = Auth::instance()->get_user()->pk();
		$fid = $user_id;
		if($is_front){
			$userdetails = Auth::instance()->get_user();
			$fid = $userdetails->pk();
		}else{
			$userdetails = ORM::factory('user')->where('id', '=', $fid)->find(); 
		}
		$age = Helper_Common::get_age(date('d-m-Y', strtotime($userdetails->user_dob)));
		$role_names = Model::instance('Model/admin/user')->getUsersRoleNamesByUserId($fid);
		$role_names = array_map('ucfirst', $role_names);
		$all_site_names = Model::instance('Model/admin/sites')->getAllActiveUserSites($fid);
		$siteid = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
		//for get user weight unit
		$w_minval = '40'; $w_maxval = '350'; $h_minval = '120'; $h_maxval = '230';
		$user_weight = (Session::instance()->get('user_weight') ? Session::instance()->get('user_weight') : '1');
		if($user_weight != 3 && $user_weight != 4){
			$set_resistance = Helper_Common::selectgeneralfn('set_resist', 'resist_title', 'resist_id = '.$user_weight);
			if($user_weight == 1){
				$w_minval = '40'; $w_maxval = '160';
			}else{
				$w_minval = '90'; $w_maxval = '350';
			}
		}
		$weight_unit = 'kg';
		if(isset($set_resistance) && count($set_resistance) > 0){
			$weight_unit = (isset($set_resistance[0]['resist_title']) ? $set_resistance[0]['resist_title'] : 'kg');
		}
		// get user height weight from answer and user measurement
		$height = $weight = $user_bmi = $initheight = $initweight = ''; $weight_foruser = $initweight_foruser = 0;
		$userprofile = ORM::factory('userprofile');
		$useranswers = $userprofile->getUserMeasurementAnswer($fid);
		if(!empty($useranswers) && count($useranswers) > 0){
			$initheight = (isset($useranswers['height']) && !empty($useranswers['height']) ? $useranswers['height'] : '');
			$initweight = (isset($useranswers['weight']) && !empty($useranswers['weight']) ? $useranswers['weight'] : '');
			if(!empty($initweight)){
				$initweight_foruser = Helper_Common::convertToMyWeightUnit($user_weight, 'kg', $initweight);
			}
		}
		$usermeasure = $userprofile->getUserMeasurement($fid);
		if((!empty($useranswers) && count($useranswers) > 0) || (!empty($usermeasure) && count($usermeasure) > 0)){
			$height = (isset($usermeasure['height']) && !empty($usermeasure['height']) ? $usermeasure['height'] : (isset($useranswers['height']) && !empty($useranswers['height']) ? $useranswers['height'] : ''));
			$weight = (isset($usermeasure['weight']) && !empty($usermeasure['weight']) ? $usermeasure['weight'] : (isset($useranswers['weight']) && !empty($useranswers['weight']) ? $useranswers['weight'] : ''));
			if(!empty($weight)){
				$weightunit = (isset($usermeasure['weight_unit']) && !empty($usermeasure['weight_unit']) ? $usermeasure['weight_unit'] : 'kg');
				$weight_foruser = Helper_Common::convertToMyWeightUnit($user_weight, $weightunit, $weight);
				$weight_forbmi = Helper_Common::convertToMyWeightUnit('1', $weightunit, $weight);
				$user_bmi = Helper_Common::calculateBMI($height, $weight_forbmi);//(cm, kg)
			}
			// check entry for this week
			$checkweekentry = $userprofile->checkUserMeasurement($fid, 'week');
			if(!$checkweekentry){
				$columns = 'height, weight, weight_unit';
				$values = $height.", ".$weight.", '".$weightunit."'";
				$userprofile->insertUserMeasurement($columns, $values, $fid);
			}else{
				$setfield = "height = ".$height.", weight = ".$weight.", weight_unit = '".$weightunit."'";
				$userprofile->updateUserMeasurement($setfield, $fid);
			}
		}
		$weightofuser = ($weight_foruser > 0 ? (float)number_format($weight_foruser, 2, '.', '') : '');
		$initweightofuser = ($initweight_foruser > 0 ? (float)number_format($initweight_foruser, 2, '.', '') : '');
		// calulate week diff
		$TW_weight = $LW_weight = 0;
		$TW_weightdtl = $userprofile->getUserMeasurementForWeek($fid, 'this');
		if(!empty($TW_weightdtl['weight']) && !empty($TW_weightdtl['weight_unit'])){
			$TW_weight = Helper_Common::convertToMyWeightUnit($user_weight, $TW_weightdtl['weight_unit'], $TW_weightdtl['weight']);
			$TW_weight = (float)number_format($TW_weight, 2, '.', '');
		}
		$LW_weightdtl = $userprofile->getUserMeasurementForWeek($fid, 'last');
		if(!empty($LW_weightdtl['weight']) && !empty($LW_weightdtl['weight_unit'])){
			$LW_weight = Helper_Common::convertToMyWeightUnit($user_weight, $LW_weightdtl['weight_unit'], $LW_weightdtl['weight']);
			$LW_weight = (float)number_format($LW_weight, 2, '.', '');
		}
		$W_weightdiff = '';
		if($TW_weight > 0 && $LW_weight > 0){
			$W_weightdiff = ($TW_weight - $LW_weight);
		}
		$weight_info = (!empty($W_weightdiff) ? ($W_weightdiff > 0 ? '+'.$W_weightdiff : $W_weightdiff).' '.__('from last week') : '');
		// for device
		$questionmodel = ORM::factory('admin_questions');
		$cquestionmodel = ORM::factory('admin_commonquestions');
		$common_q = $questionmodel->getCommonQuestions_status($siteid);
		$commonQ = 0; $devicename = $alldevicename = array();
		if(!empty($common_q) && isset($common_q[0]['common_question'])){
			$commonQ = $common_q[0]['common_question'];
		}
		if($commonQ == '1'){
			$devicequestion = $cquestionmodel->getQuestions();
		}else{
			$devicequestion = $questionmodel->getQuestions($siteid);					
		}
		$deviceQid = '';
		foreach($devicequestion as $qkey => $qvalue){
			$qsearch = $qvalue['question'];//fitness devices
			if(stristr($qsearch, 'fitness device') !== FALSE) {
				$deviceQid = $qvalue['id'];
				$devicename = $userprofile->getUserDeviceAnswerDetail($fid, $deviceQid);
				$alldevicename = $questionmodel->getQuestionOptions($deviceQid);
			}
		}
		$response = '<div class="panel panel-default">
			<ul class="list-group">
				<li class="list-group-item">
					<div data-toggle="detail-1" id="dropdown-detail-1" class="row toggle">
						<div class="col-xs-10"><strong>'.__('About Me').'</strong></div>
						<div class="col-xs-2"><i class="fa fa-chevron-up pull-right"></i></div>
					</div>
					<div style="display: block;" id="detail-1">
						<hr>
						<div class="detail-content">
							<div class="row">
								<div class="col-xs-4 datacol"><strong>'.__('Role').' :</strong></div>
								<div class="col-xs-8 inactivedatacol">
									<span id="role-less">'.(count($role_names) > 2 ? current($role_names).', '.next($role_names) : implode(', ', $role_names)).'</span>'.(count($role_names) > 2 ? '<span id="role-more" class="hide">'.implode(', ', $role_names).'</span>&nbsp;<i data-id="role" class="fa fa-ellipsis-h activedatacol fa_icon showalldetail"></i>' : '').
								'</div>
							</div>
							<div class="row">
								<div class="col-xs-4 datacol"><strong>'.__('Site(s)').' :</strong></div>
								<div class="col-xs-8 inactivedatacol">
									<span id="site-less">'.(count($all_site_names) > 2 ? current($all_site_names).', '.next($all_site_names) : implode(', ', $all_site_names)).'</span>'.(count($all_site_names) > 2 ? '<span id="site-more" class="hide">'.implode(', ', $all_site_names).'</span>&nbsp;<i data-id="site" class="fa fa-ellipsis-h activedatacol fa_icon showalldetail"></i>' : '').
								'</div>
							</div>
							<div class="row">
								<div class="col-xs-4 datacol"><strong>'.__('Activated').' :</strong></div>
								<div class="col-xs-8 inactivedatacol">'.Helper_Common::get_default_datetime($userdetails->date_created).'</div>
							</div>
							<div class="row">
								<div class="col-xs-4 datacol"><strong>'.__('Gender').' :</strong></div>
								<div class="col-xs-8 inactivedatacol">
									<span id="gender-label"> '.($userdetails->user_gender == '1' ? 'Male' : 'Female').'</span>'
									.($current_userid == $fid ? '<div id="gender-edit" class="btn-group hide" data-toggle="buttons">
										<label class="btn'.($userdetails->user_gender == '1' ? ' active' : '').'">
											<input type="radio" value="1" name="user_gender" data-gentext="Male" '.($userdetails->user_gender == '1' ? 'checked=""' : '').'>
											<i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i><span> Male</span>
										</label>
										<label class="btn'.($userdetails->user_gender == '2' ? ' active' : '').'">
											<input type="radio" value="2" name="user_gender" data-gentext="Female" '.($userdetails->user_gender == '2' ? 'checked=""' : '').'>
											<i class="fa fa-circle-o fa-2x"></i><i class="fa fa-dot-circle-o fa-2x"></i><span> Female</span>
										</label>
									</div>
									<i data-id="gender" class="fa fa-pencil activedatacol fa_icon showeditdetail"></i>' : '').
								'</div>
							</div>
							<div class="row">
								<div class="col-xs-4 datacol"><strong>'.__('Birthdate').' :</strong></div>
								<div class="col-xs-8 inactivedatacol">
									<span id="birthdate-label">'.Helper_Common::change_default_date_dob($userdetails->user_dob).'</span>'
									.($current_userid == $fid ? ($is_front ? '<input type="hidden" id="user_birthdate" value="'.Helper_Common::change_default_date_dob($userdetails->user_dob).'"/><i class="fa fa-pencil activedatacol fa_icon change-user_dob"></i>' : 
									'<span id="birthdate-edit" class="col-xs-7 textbox-cover hide">
										<input type="text" id="user_birthdate" name="user_birthdate" class="usernamebutton min-date add-on" value="'.date("d M Y", strtotime($userdetails->user_dob.' 00:00:00')).'" style="background-color: #eee;" date-role="none" data-ajax="false"/>
									</span>
									<i class="fa fa-check activedatacol fa_icon change-user_birthdate hide"></i>
									<i data-id="birthdate" class="fa fa-pencil activedatacol fa_icon showeditdetail" data-value="'.date("d M Y", strtotime($userdetails->user_dob.' 00:00:00')).'"></i>') : '').
								'</div>
							</div>
							<div class="row">
								<div class="col-xs-4 datacol"><strong>'.__('Phone No').' :</strong></div>
								<div class="col-xs-8 inactivedatacol">
									<span id="phoneno-label">'.(!empty($userdetails->user_mobile) ? $userdetails->user_mobile : '-').'</span>'
									.($current_userid == $fid ? '<span id="phoneno-edit" class="col-xs-7 textbox-cover hide">
										<input type="text" class="form-control input-sm" onkeypress="return isNumber(event);" maxlength="15">
									</span>
									<i class="fa fa-check activedatacol fa_icon change-user_mobile hide"></i>
									<i data-id="phoneno" class="fa fa-pencil activedatacol fa_icon showeditdetail" data-value="'.(!empty($userdetails->user_mobile) ? $userdetails->user_mobile : '').'"></i>' : '').
								'</div>
							</div>
							<div class="row">
								<div class="col-xs-4 datacol"><strong>'.__('Links ID').' :</strong></div>
								<div class="col-xs-8 inactivedatacol">'.(!empty($userdetails->links_id) ? $userdetails->links_id : '').'</div>
							</div>
						</div>
					</div>
				</li>
				<li class="list-group-item">
					<div data-toggle="detail-2" id="dropdown-detail-2" class="row toggle">
						<div class="col-xs-10"><strong>'.__('Measurements').'</strong></div>
						<div class="col-xs-2"><i class="fa fa-chevron-down pull-right"></i></div>
					</div>
					<div style="display: none;" id="detail-2">
						<hr>
						<div class="detail-content">
							<div class="row">
								<div class="col-xs-4 datacol"><strong>'.__('Height').' :</strong></div>
								<div class="col-xs-8 inactivedatacol">
									<span id="height-label">'.$height.'</span><span> cm</span>'
									.($current_userid == $fid ? '<i class="fa fa-check activedatacol fa_icon change-user_height hide"></i>
									<i data-id="height" class="fa fa-pencil activedatacol fa_icon add_height" data-value="'.$height.'"></i>
									<span id="height-edit" class="slider-cover hide">
										<input type="range" name="height-slider" id="height-slider" value="'.$height.'" min="'.$h_minval.'" max="'.$h_maxval.'" title="'.$height.'" oninput="displaysliderval(\'height-label\',this);" onchange="displaysliderval(\'height-label\', this);" data-role="none" data-ajax="false"/>
									</span>' : '').
								'</div>
							</div>
							<div class="row">
								<div class="col-xs-4 datacol"><strong>'.__('Weight').' :</strong></div>
								<div class="col-xs-8 inactivedatacol">
									<span id="weight-label">'.$weightofuser.'</span><span class="weight-unit"> '.$weight_unit.'</span>'
									.($current_userid == $fid ? '<i class="fa fa-check activedatacol fa_icon change-user_weight hide"></i>
									<i data-id="weight" class="fa fa-times activedatacol fa_icon hide add_weight" data-value="'.$weightofuser.'"></i>
									<span class="info-blue weight-diff"> '.$weight_info.'</span>
									<span data-id="weight" class="activedatacol add_weight" data-value="'.$weightofuser.'"><i class="fa fa-plus"></i> '.__('Current Weight').'</span>
									<span id="weight-edit" class="slider-cover hide">
								 		<input type="range" name="weight-slider" id="weight-slider" value="'.$weightofuser.'" min="'.$w_minval.'" max="'.$w_maxval.'" title="'.$weightofuser.'" oninput="displaysliderval(\'weight-label\',this);" onchange="displaysliderval(\'weight-label\', this);" data-role="none" data-ajax="false"/>
								 	</span>' : '').
								'</div>
							</div>
							<div class="row">
								<div class="col-xs-4 datacol"><strong>'.__('BMI').' :</strong></div>
								<div class="col-xs-8 inactivedatacol user-bmi">'.$user_bmi.'</div>
							</div>
							<div class="row">
								<div class="col-xs-4 datacol"><strong>'.__('Age').' :</strong></div>
								<div class="col-xs-8 inactivedatacol">'.$age.'</div>
							</div>
						</div>
					</div>
				</li>
				<li class="list-group-item">
					<div data-toggle="detail-3" id="dropdown-detail-3" class="row toggle">
						<div class="col-xs-10"><strong>'.__('Initial Questions').'</strong></div>
						<div class="col-xs-2"><i class="fa fa-chevron-down pull-right"></i></div>
					</div>
					<div style="display:none;" id="detail-3">
						<hr>
						<div class="detail-content">
							<!-- dynamicall insert the COMMON QUESTIONS and Answers -->
							<div class="row">
								<div class="col-xs-4 datacol"><strong>'.__('Height').' :</strong></div>
								<div class="col-xs-8 inactivedatacol">
									<span id="initheight-label">'.$initheight.'</span><span> cm</span>'
									.($current_userid == $fid ? '<i class="fa fa-check activedatacol fa_icon change-user_initheight hide"></i>
									<i data-id="initheight" class="fa fa-pencil activedatacol fa_icon add_initheight" data-value="'.$initheight.'"></i>
									<span id="initheight-edit" class="slider-cover hide">
										<input type="range" name="initheight-slider" id="initheight-slider" value="'.$initheight.'" min="'.$h_minval.'" max="'.$h_maxval.'" title="'.$initheight.'" oninput="displaysliderval(\'initheight-label\',this);" onchange="displaysliderval(\'initheight-label\', this);" data-role="none" data-ajax="false"/>
									</span>' : '').
								'</div>
							</div>
							<div class="row">
								<div class="col-xs-4 datacol"><strong>'.__('Weight').' :</strong></div>
								<div class="col-xs-8 inactivedatacol">
									<span id="initweight-label">'.$initweightofuser.'</span><span class="initweight-unit"> '.$weight_unit.'</span>'
									.($current_userid == $fid ? '<i class="fa fa-check activedatacol fa_icon change-user_initweight hide"></i>
									<i data-id="initweight" class="fa fa-pencil activedatacol fa_icon add_initweight" data-value="'.$initweightofuser.'"></i>
									<span id="initweight-edit" class="slider-cover hide">
										<input type="range" name="initweight-slider" id="initweight-slider" value="'.$initweightofuser.'" min="'.$w_minval.'" max="'.$w_maxval.'" title="'.$initweightofuser.'" oninput="displaysliderval(\'initweight-label\',this);" onchange="displaysliderval(\'initweight-label\', this);" data-role="none" data-ajax="false"/>
									</span>' : '').
								'</div>
							</div>
							<div class="row">
								<div class="col-xs-4 datacol"><strong>'.__('Devices').' :</strong></div>
								<div class="col-xs-8 inactivedatacol">';
									$deviceall = $deviceallid = array();
									if(!empty($devicename)){
										foreach($devicename as $device) {
											$deviceallid[] = $device['option_id'];
											$deviceall[] = $device['name'];
										}
									}
									$response .= '<span id="device-label">'.implode(', ', $deviceall).'</span>';
								 	if($current_userid == $fid){
								 		$response .= '<span id="device-edit" class="select-cover dropdown selectdropdownTwo col-xs-7 hide">
											<select multiple class="bordernone panel-margin fa-blue" style="width:100%;">';
												foreach ($alldevicename as $devicevalue) {
													$response .= '<option value="'.$devicevalue['id'].'">'.$devicevalue['option'].'</option>';
												}
											$response .= '</select>
										</span>
										<i class="fa fa-check activedatacol fa_icon change-user_device hide" data-qid="'.$deviceQid.'"></i>'
										.(!empty($deviceQid) ? '<i data-id="device" class="fa fa-pencil activedatacol fa_icon showeditdetail" data-value="'.implode(',', $deviceallid).'"></i>' : '');
									}
								$response .= '</div>
							</div>
							<!-- dynamicall insert the SITE QUESTIONS and Answers -->
						</div>
					</div>
				</li>
			</ul>
		</div>';
		return $response;
	}
	function infoMessage($type, $value){
		$message ='';
		if(!empty($type) && !empty($value)){
			$message = ($value > 0) ? '<span class="info-green"> +'.$value.' '.__('from last '.$type).'</span>' : '<span class="info-red"> '.$value.' '.__('from last '.$type).'</span>';
		}
		return $message;
	}
	public function userperioddetails($user_id, $is_front){
		$uid = $user_id;
		if($is_front){
			$userdetails = Auth::instance()->get_user();
			$uid = $userdetails->pk();
		}else{
			$userdetails = ORM::factory('user')->where('id', '=', $uid)->find(); 
		}
		$currentdate = Helper_Common::get_default_datetime();
		$useractivedate = Helper_Common::get_default_datetime($userdetails->date_created);
		$ts1 = strtotime($useractivedate, 0); $ts2 = strtotime($currentdate, 0);
		$year1 = date('Y', $ts1); $year2 = date('Y', $ts2);
		$month1 = date('m', $ts1);	$month2 = date('m', $ts2);
		$date_diff = ($ts2 - $ts1);
		$totalweeks = floor($date_diff / 604800); // total weeks of user
		$totalmonths = (($year2 - $year1) * 12) + ($month2 - $month1); // total months of user
		return array('user-id'=>$uid, 'user-activedate'=>$useractivedate, 'user-weeks'=>$totalweeks, 'user-months'=>$totalmonths);
	}
	public function profilereports($user_id, $is_front){
		$userprofile = ORM::factory('userprofile');
		$userperioddetail = $this->userperioddetails($user_id, $is_front);
		$totalweeks = $userperioddetail['user-weeks']; // total weeks of user
		$totalmonths = $userperioddetail['user-months']; // total months of user
		$useractivedate = $userperioddetail['user-activedate']; // user activation date
		//W-week, M-month, T-this, L-last
		/*for login report*/ 
		$T_totallogincnt = $userprofile->profile_report_totalcnt_by_type('login', 'this', $useractivedate, $user_id, $is_front);
		$TW_avglogincnt = ($totalweeks > 0 ? floor($T_totallogincnt['totalcnt'] / $totalweeks) : 0);
		$TM_avglogincnt = ($totalmonths > 0 ? floor($T_totallogincnt['totalcnt'] / $totalmonths) : 0);
		$L_totallogincnt = $userprofile->profile_report_totalcnt_by_type('login', 'last', $useractivedate, $user_id, $is_front);
		$LM_avglogincnt = ($totalmonths > 0 ? floor($L_totallogincnt['totalcnt'] / ($totalmonths-1)) : 0);
		$TW_logincnt = $userprofile->profile_report_cnt_by_period('login', 'week', 'this', $user_id, $is_front);
		$LW_logincnt = $userprofile->profile_report_cnt_by_period('login', 'week', 'last', $user_id, $is_front);
		$W_logindiff = ($TW_logincnt['weeklycnt'] - $LW_logincnt['weeklycnt']);
		$M_logindiff = ($TM_avglogincnt - $LM_avglogincnt);
		/*for workout-assign report*/ 
		$T_totalwkasscnt = $userprofile->profile_report_totalcnt_by_type('workout-assign', 'this', $useractivedate, $user_id, $is_front);
		$TW_avgwkasscnt = ($totalweeks > 0 ? floor($T_totalwkasscnt['totalcnt'] / $totalweeks) : 0);
		$TM_avgwkasscnt = ($totalmonths > 0 ? floor($T_totalwkasscnt['totalcnt'] / $totalmonths) : 0);
		$L_totalwkasscnt = $userprofile->profile_report_totalcnt_by_type('workout-assign', 'last', $useractivedate, $user_id, $is_front);
		$LM_avgwkasscnt = ($totalmonths > 0 ? floor($L_totalwkasscnt['totalcnt'] / ($totalmonths-1)) : 0);
		$TW_wkasscnt = $userprofile->profile_report_cnt_by_period('workout-assign', 'week', 'this', $user_id, $is_front);
		$LW_wkasscnt = $userprofile->profile_report_cnt_by_period('workout-assign', 'week', 'last', $user_id, $is_front);
		$W_wkassdiff = ($TW_wkasscnt['weeklycnt'] - $LW_wkasscnt['weeklycnt']);
		$M_wkassdiff = ($TM_avgwkasscnt - $LM_avgwkasscnt);
		/*for workout-log report*/ 
		$T_totalwklogcnt = $userprofile->profile_report_totalcnt_by_type('workout-log', 'this', $useractivedate, $user_id, $is_front);
		$TW_avgwklogcnt = ($totalweeks > 0 ? floor($T_totalwklogcnt['totalcnt'] / $totalweeks) : 0);
		$TM_avgwklogcnt = ($totalmonths > 0 ? floor($T_totalwklogcnt['totalcnt'] / $totalmonths) : 0);
		$L_totalwklogcnt = $userprofile->profile_report_totalcnt_by_type('workout-log', 'last', $useractivedate, $user_id, $is_front);
		$LM_avgwklogcnt = ($totalmonths > 0 ? floor($L_totalwklogcnt['totalcnt'] / ($totalmonths-1)) : 0);
		$TW_wklogcnt = $userprofile->profile_report_cnt_by_period('workout-log', 'week', 'this', $user_id, $is_front);
		$LW_wklogcnt = $userprofile->profile_report_cnt_by_period('workout-log', 'week', 'last', $user_id, $is_front);
		$W_wklogdiff = ($TW_wklogcnt['weeklycnt'] - $LW_wklogcnt['weeklycnt']);
		$M_wklogdiff = ($TM_avgwklogcnt - $LM_avgwklogcnt);
		$response = '<div class="panel panel-default">
			<ul class="list-group">
				<li class="list-group-item">
					<div data-toggle="detail-6" id="dropdown-detail-6" class="row toggle">
						<div class="col-xs-10"><strong>'.__('Track Record').'</strong></div>
						<div class="col-xs-2"><i class="fa fa-chevron-down pull-right"></i></div>
					</div>
					<div style="display: none;" id="detail-6">
						<hr>
						<div class="detail-content">
							<div class="row">
								<div class="col-xs-12 datacol"><strong>'.__('Logins').' :</strong></div>
								<div class="col-xs-4 datacol">'.__('Total').' :</div>
								<div class="col-xs-8 inactivedatacol">'.$T_totallogincnt['totalcnt'].'</div>
								<div class="col-xs-4 datacol"><span class="info-gray">'.__('This Week').' :</span></div>
								<div class="col-xs-8 inactivedatacol">'.$TW_logincnt['weeklycnt'].$this->infoMessage('week', $W_logindiff).'</div>
							</div>
							<div class="row">
								<div class="col-xs-4 datacol">'.__('Average').' :</div>
								<div class="col-xs-8 inactivedatacol">'.$TW_avglogincnt.'<span class="info-gray">/week</span></div>
								<div class="col-xs-4 datacol"></div>
								<div class="col-xs-8 inactivedatacol">'.$TM_avglogincnt.'<span class="info-gray">/month</span>'.$this->infoMessage('month', $M_logindiff).'</div>
							</div>
							<hr>
							<div class="row">
								<div class="col-xs-12 datacol"><strong>'.__('Workouts Assigned').' :</strong></div>
								<div class="col-xs-4 datacol">'.__('Total').' :</div>
								<div class="col-xs-8 inactivedatacol">'.$T_totalwkasscnt['totalcnt'].'</div>
								<div class="col-xs-4 datacol"><span class="info-gray">'.__('This Week').' :</span></div>
								<div class="col-xs-8 inactivedatacol">'.$TW_wkasscnt['weeklycnt'].$this->infoMessage('week', $W_wkassdiff).'</div>
							</div>
							<div class="row">
								<div class="col-xs-4 datacol">'.__('Average').' :</div>
								<div class="col-xs-8 inactivedatacol">'.$TW_avgwkasscnt.'<span class="info-gray">/week</span></div>
								<div class="col-xs-4 datacol"></div>
								<div class="col-xs-8 inactivedatacol">'.$TM_avgwkasscnt.'<span class="info-gray">/month</span>'.$this->infoMessage('month', $M_wkassdiff).'</div>
							</div>
							<hr>
							<div class="row">
								<div class="col-xs-12 datacol"><strong>'.__('Workout Journals Logged').' :</strong></div>
								<div class="col-xs-4 datacol">'.__('Total').' :</div> 
								<div class="col-xs-8 inactivedatacol">'.$T_totalwklogcnt['totalcnt'].'</div>
								<div class="col-xs-4 datacol"><span class="info-gray">'.__('This Week').' :</span></div>
								<div class="col-xs-8 inactivedatacol">'.$TW_wklogcnt['weeklycnt'].$this->infoMessage('week', $W_wklogdiff).'</div>
							</div>
							<div class="row">
								<div class="col-xs-4 datacol">'.__('Average').' :</div>
								<div class="col-xs-8 inactivedatacol">'.$TW_avgwklogcnt.'<span class="info-gray">/week</span></div>
								<div class="col-xs-4 datacol"></div>
								<div class="col-xs-8 inactivedatacol">'.$TM_avgwklogcnt.'<span class="info-gray">/month</span>'.$this->infoMessage('month', $M_wklogdiff).'</div>
							</div>
						</div>
					</div>
				</li>
				<li class="list-group-item">
					<div data-toggle="detail-9" id="dropdown-detail-9" class="row toggle">
						<div class="col-xs-10"><strong>'.__('Cardio/Endurance Results').'</strong></div>
						<div class="col-xs-2"><i class="fa fa-chevron-down pull-right"></i></div>
					</div>
					<div style="display: none;" id="detail-9">
						<hr>
						<div class="detail-content">
							<div class="row">
								<div class="col-xs-12 datacol"><strong>'.__('Cardio Activity Chart').' :</strong></div>
								<div class="col-xs-12 datacol">
									<div style="clear: both; display: block;" class="row">
										<div class="col-lg-12">
											<div class="panel panel-default">
												<div class="panel-heading">
													<div class="" style="position: relative;">
														<h3 class="panel-title"><i class="fa fa-bar-chart-o fa-fw"></i>'.__('Cardio Activity').'
														<button type="button" class="btn btn-primary btn-sm" data-toggle="collapse" data-target="#cordio-filter">'.__('Options').'</button></h3>
														<div class="pull-right panel-margin cardio-refresh" onclick="updateCardioChart();">
															<i class="fa fa-refresh fa-blue" aria-hidden="true"></i>
														</div>
													</div>
													<div id="cordio-filter" class="row collapse">
														<div class="filter-container">
															<div class="dropdown selectdropdownTwo col-xs-12">
																<select placeholder="Choose Status" name="filterby" id="filterby" class="bordernone panel-margin fa-blue" onchange="changecardiofilterby();">
																	<option value="6">By Distance</option>
																	<option value="7">By Time</option>
																	<option value="" selected>Most Recent</option>
																	<option value="1">Today</option>
																	<option value="2">This Week</option>
																	<option value="3">This Month</option>
																	<option value="5">This Year</option>
																	<option value="4">Custom Date</option>
																</select>
															</div>
															<div class="cardiocusdate col-xs-6">'.
															(!$is_front ? '<input type="text" id="cardiodatefrom" name="cardiodatefrom" class="usernamebutton min-date add-on" placeholder="Select From Date" value="" style="background-color: #eee;" date-role="none" data-ajax="false"/>' :
																'<input type="text" name="cardiodatefrom" id="cardiodatefrom" placeholder="Select From Date" data-format="dd/MM/yyyy" required="true" class="form-control bordernone fa-blue" onclick="birthDayPopup(this);" data-datefor="from" readonly>')
															.'</div>
															<div class="cardiocusdate col-xs-6">'.
															(!$is_front ? '<input type="text" id="cardiodateto" name="cardiodateto" class="usernamebutton min-date add-on" placeholder="Select To Date" value="" style="background-color: #eee;" date-role="none" data-ajax="false"/>' :
																'<input type="text" name="cardiodateto" id="cardiodateto" placeholder="Select To Date" data-format="dd/MM/yyyy" required="true" class="form-control bordernone fa-blue" onclick="birthDayPopup(this);" data-datefor="to" readonly>')
															.'</div>
														</div>
													</div>
												</div>
												<div class="panel-body" style="padding: 0;">
													<div class="clearfix chartdrop">
														<div id="cardiochart" style="height:338px"></div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<hr>
							<div class="row">
								<div class="col-xs-12 datacol"><strong>'.__('Cardio').' :</strong></div>
							</div>
							<div id="cardio-frequent"></div>
						</div>
					</div>
				</li> 
				<li class="list-group-item">
					<div data-toggle="detail-10" id="dropdown-detail-10" class="row toggle">
						<div class="col-xs-10"><strong>'.__('Strength/Resistance Results').'</strong></div>
						<div class="col-xs-2"><i class="fa fa-chevron-down pull-right"></i></div>
					</div>
					<div style="display:none;" id="detail-10">
						<hr>
						<div class="detail-content">
							<div class="row">
								<div class="col-xs-12 datacol"><strong>'.__('Concentration').' :</strong></div>
								<div class="col-xs-12 datacol">
									<div class="row" style="clear:both;" id="morris-donutabove">
										<div class="col-lg-12">
											<div class="panel panel-default">
												<div class="panel-body">
													<div class="responsive-chart"><div id="placeholder" style="width: 100%; height: 300px;"></div></div>
													<div id="chartLegend"></div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<hr>
							<div id="strength-report"></div>
						</div>
					</div>
				</li>
			</ul>
		</div>'.HTML::script('assets/js/userProfile.js');
		return $response;
	}
	public function strengthReport($muscleId, $muscleName, $user_id, $is_front){
		if($muscleId == 18){
			return $response = '';
		}
		$userprofile = ORM::factory('userprofile');
		$userperioddetail = $this->userperioddetails($user_id, $is_front);
		$useractivedate = date('Y-m-d', strtotime($userperioddetail['user-activedate']));
		$user_weight = (Session::instance()->get('user_weight') ? Session::instance()->get('user_weight') : '1');
		$set_resistance = Helper_Common::selectgeneralfn('set_resist', 'resist_title', 'resist_id = '.$user_weight);
		$response = $weight_unit = '';
		if(isset($set_resistance) && count($set_resistance) > 0){
			$weight_unit = (isset($set_resistance[0]['resist_title']) ? $set_resistance[0]['resist_title'] : '');
		}
		//this week
		$TW_strengthreport = $userprofile->get_strength_report($muscleId, 'week', 'this', $user_id, $is_front);
		$TW_reps = $TW_resist = array();
		if(isset($TW_strengthreport) && count($TW_strengthreport) > 0){
			foreach ($TW_strengthreport as $TW_key => $TW_value) {
				$TW_reps[] = $TW_value['goal_reps'];
				$TW_resist[] = Helper_Common::convertToMyWeightUnit($user_weight, $TW_value['resist_title'], $TW_value['goal_resist']);
			}
		}else{
			$TW_resist[] = 0;
		}
		$TW_reps_sum = (count($TW_reps) > 0 ? (float)number_format(array_sum($TW_reps), 2, '.', '') : 0);
		$TW_resist_max = (count($TW_resist) > 0 ? (float)number_format(max($TW_resist), 2, '.', '') : 0);
		//last week
		$LW_reps = $LW_resist = array();
		$LW_strengthreport = $userprofile->get_strength_report($muscleId, 'week', 'last', $user_id, $is_front);
		if(isset($LW_strengthreport) && count($LW_strengthreport) > 0){
			foreach ($LW_strengthreport as $LW_key => $LW_value) {
				$LW_reps[] = $LW_value['goal_reps'];
				$LW_resist[] = Helper_Common::convertToMyWeightUnit($user_weight, $LW_value['resist_title'], $LW_value['goal_resist']);
			}
		}else{
			$LW_resist[] = 0;
		}
		$LW_reps_sum = (count($LW_reps) > 0 ? (float)number_format(array_sum($LW_reps), 2, '.', '') : 0);
		$LW_resist_max = (count($LW_resist) > 0 ? (float)number_format(max($LW_resist), 2, '.', '') : 0);
		$W_repsdiff = ($TW_reps_sum - $LW_reps_sum);
		$W_resistdiff = ($TW_resist_max - $LW_resist_max);
		/*for month*/
		//this month
		$TM_strengthreport = $userprofile->get_strength_report($muscleId, 'month', 'this', $user_id, $is_front);
		$TM_reps = $TM_resist = array();
		if(isset($TM_strengthreport) && count($TM_strengthreport) > 0){
			foreach ($TM_strengthreport as $TM_key => $TM_value) {
				$TM_reps[] = $TM_value['goal_reps'];
				$TM_resist[] = Helper_Common::convertToMyWeightUnit($user_weight, $TM_value['resist_title'], $TM_value['goal_resist']);
			}
		}else{
			$TM_resist[] = 0;
		}
		$TM_reps_sum = (count($TM_reps) > 0 ? (float)number_format(array_sum($TM_reps), 2, '.', '') : 0);
		$TM_resist_max = (count($TM_resist) > 0 ? (float)number_format(max($TM_resist), 2, '.', '') : 0);
		//last month
		$LM_strengthreport = $userprofile->get_strength_report($muscleId, 'month', 'last', $user_id, $is_front);
		$LM_reps = $LM_resist = array();
		if(isset($LM_strengthreport) && count($LM_strengthreport) > 0){
			foreach ($LM_strengthreport as $LM_key => $LM_value) {
				$LM_reps[] = $LM_value['goal_reps'];
				$LM_resist[] = Helper_Common::convertToMyWeightUnit($user_weight, $LM_value['resist_title'], $LM_value['goal_resist']);
			}
		}else{
			$LM_resist[] = 0;
		}
		$LM_reps_sum = (count($LM_reps) > 0 ? (float)number_format(array_sum($LM_reps), 2, '.', '') : 0);
		$LM_resist_max = (count($LM_resist) > 0 ? (float)number_format(max($LM_resist), 2, '.', '') : 0);
		$M_repsdiff = ($TM_reps_sum - $LM_reps_sum);
		$M_resistdiff = ($TM_resist_max - $LM_resist_max);
		// max resistance and xr set
		$personalbest = $userprofile->get_personal_best($muscleId, $useractivedate, $user_id, $is_front);
		$response .= '<div class="miscle-'.$muscleName.'">
			<div class="row">
				<div class="col-xs-12 datacol"><strong>'.$muscleName.' :</strong></div>
			</div>';
			//personal best
			if(isset($personalbest) && count($personalbest) > 0){
				$max_pbreport = (!empty($personalbest['goal_resist']) && ($personalbest['goal_resist'] > 0) ? $personalbest['goal_resist'].' '.$personalbest['resist_title'].' <span class="activedatacol" onclick="getExerciseSetpreviewlog('.$personalbest['goal_id'].', '.$personalbest['wkout_log_id'].', '.$personalbest['wkout_id'].', '.$user_id.');">'.$personalbest['title'].'</span>' : 'n/a');
				$response .= '<div class="row">
					<div class="col-xs-4 datacol"><span class="info-gray">Personal Best :</span></div>
					<div class="col-xs-8 inactivedatacol">'.$max_pbreport.'</div>
				</div>';
			}
			//week
			if(($TW_reps_sum > 0 && $TW_resist_max > 0) || ($TW_reps_sum > 0 && $TW_resist_max < 1) || ($TW_reps_sum < 1 && $TW_resist_max < 1)){
				$response .= '<div class="row">
					<div class="col-xs-4 datacol"><span class="info-gray">This Week :</span></div>
					<div class="col-xs-8 inactivedatacol">'.($TW_reps_sum > 0 ? $TW_reps_sum.' reps'.$this->infoMessage('week', $W_repsdiff) : 'n/a').'</div>
				</div>';
				if($TW_resist_max > 0){
					$response .= '<div class="row">
						<div class="col-xs-4 datacol"></div>
						<div class="col-xs-8 inactivedatacol">'.($TW_resist_max.' '.$weight_unit.' max'.$this->infoMessage('week', $W_resistdiff)).'</div>
					</div>';
				}
			}else if($TW_reps_sum < 1 && $TW_resist_max > 0){
				$response .= '<div class="row">
					<div class="col-xs-4 datacol"><span class="info-gray">This Week :</span></div>
					<div class="col-xs-8 inactivedatacol">'.($TW_resist_max > 0 ? $TW_resist_max.' '.$weight_unit.' max'.$this->infoMessage('week', $W_resistdiff) : 'n/a').'</div>
				</div>';
			}
			//month
			if(($TM_reps_sum > 0 && $TM_resist_max > 0) || ($TM_reps_sum > 0 && $TM_resist_max < 1) || ($TM_reps_sum < 1 && $TM_resist_max < 1)){
				$response .= '<div class="row">
					<div class="col-xs-4 datacol"><span class="info-gray">This Month :</span></div>
					<div class="col-xs-8 inactivedatacol">'.($TM_reps_sum > 0 ? $TM_reps_sum.' reps'.$this->infoMessage('month', $M_repsdiff) : 'n/a').'</div>
				</div>';
				if($TM_resist_max > 0){
					$response .= '<div class="row">
						<div class="col-xs-4 datacol"></div>
						<div class="col-xs-8 inactivedatacol">'.($TM_resist_max.' '.$weight_unit.' max'.$this->infoMessage('month', $M_resistdiff)).'</div>
					</div>';
				}
			}else if($TM_reps_sum < 1 && $TM_resist_max > 0){
				$response .= '<div class="row">
					<div class="col-xs-4 datacol"><span class="info-gray">This Month :</span></div>
					<div class="col-xs-8 inactivedatacol">'.($TM_resist_max > 0 ? $TM_resist_max.' '.$weight_unit.' max'.$this->infoMessage('month', $M_resistdiff) : 'n/a').'</div>
				</div>';
			}
		$response .= '</div>';
		return $response;
	}
	public function cardioFrequent($postdate){
		$is_front  = (isset($postdate['is_front']) && $postdate['is_front'] ? true : false);
		$user_id = $postdate['userid'];
		$userperioddetail = $this->userperioddetails($user_id, $is_front);
		$user_distance = (Session::instance()->get('user_distance') ? Session::instance()->get('user_distance') : '1');
		$set_distance = Helper_Common::selectgeneralfn('set_dist', 'dist_title', 'dist_id = '.$user_distance);
		$response = $distance_unit = '';
		if(isset($set_distance) && count($set_distance) > 0){
			$distance_unit = (isset($set_distance[0]['dist_title']) ? $set_distance[0]['dist_title'] : '');
		}
		$userprofile = ORM::factory('userprofile');
		$currentdate = Helper_Common::get_default_date();
		if(!isset($postdate["fdate"]) || !isset($postdate["tdate"]) || empty($postdate["fdate"]) || empty($postdate["tdate"]) ){
			$postdate["fdate"] = date('Y-m-d', strtotime($currentdate));
			$postdate["tdate"] = $currentdate;
		}
		$cardio_freq = $userprofile->get_cardio_frequent($postdate);
		$response = '';
		if(!empty($cardio_freq) && count($cardio_freq)>0) {
			foreach($cardio_freq as $freq_key => $freq_value){
				$response .= '<div class="cardio-'.($freq_key+1).'">
					<div class="row">
						<div class="col-xs-12">'
							.(!empty($freq_value['img_url']) && file_exists($freq_value['img_url']) ? '<img src="'.URL::base().$freq_value['img_url'].'" alt="..." class="img-thumbnail" style="width:50px; float:left;">' : '<i class="fa fa-file-image-o datacol" style="font-size:45px; float:left;"></i>').
							'<div class="col-xs-9 datacol">'.$freq_value['goal_title'].' :</div>
						</div>
					</div>';
					// this week
					$TW_freq_report = $userprofile->get_cardio_frequent_report($freq_value['freq_id'], 'week', 'this', $user_id, $is_front);
					$TW_dist = $TW_time = array();
					if(isset($TW_freq_report) && count($TW_freq_report) > 0){
						foreach ($TW_freq_report as $TW_key => $TW_value) {
							$TW_time[] = $TW_value['goal_time'];
							$TW_dist[] = Helper_Common::convertToMyDistanceUnit($user_distance, $TW_value['dist_title'], $TW_value['goal_dist']);
						}
					}else{
						$TW_time[] = '00:00:00';
						$TW_dist[] = 0;
					}
					$TW_cardiodist = (count($TW_dist) > 0 ? (float)number_format(array_sum($TW_dist), 2, '.', '') : 0);
					$TW_cardiotime = (count($TW_time) > 0 ? Helper_Common::hoursToMinutes(Helper_Common::sumTime($TW_time)) : 0);
					// last week
					$LW_freq_report = $userprofile->get_cardio_frequent_report($freq_value['freq_id'], 'week', 'last', $user_id, $is_front);
					$LW_dist = $LW_time = array();
					if(isset($LW_freq_report) && count($LW_freq_report) > 0){
						foreach ($LW_freq_report as $LW_key => $LW_value) {
							$LW_time[] = $LW_value['goal_time'];
							$LW_dist[] = Helper_Common::convertToMyDistanceUnit($user_distance, $LW_value['dist_title'], $LW_value['goal_dist']);
						}
					}else{
						$LW_time[] = '00:00:00';
						$LW_dist[] = 0;
					}
					$LW_cardiodist = (count($LW_dist) > 0 ? (float)number_format(array_sum($LW_dist), 2, '.', '') : 0);
					$LW_cardiotime = (count($LW_time) > 0 ? Helper_Common::hoursToMinutes(Helper_Common::sumTime($LW_time)) : 0);
					$W_cardiodistdiff = ($TW_cardiodist - $LW_cardiodist);
					$W_cardiotimediff = ($TW_cardiotime - $LW_cardiotime);
					$response .= '<div class="row">
						<div class="col-xs-4 datacol"><span class="info-gray">'.__('This Week').' :</span></div>
						<div class="col-xs-8 inactivedatacol">'.$TW_cardiodist.' '.$distance_unit.' '.$this->infoMessage('week', $W_cardiodistdiff).'</div>
					</div>
					<div class="row">
						<div class="col-xs-4 datacol"></div>
						<div class="col-xs-8 inactivedatacol">'.$TW_cardiotime.' min '.$this->infoMessage('week', $W_cardiotimediff).'</div>
					</div>';
					//this month
					$TM_freq_report = $userprofile->get_cardio_frequent_report($freq_value['freq_id'], 'month', 'this', $user_id, $is_front);
					$TM_dist = $TM_time = array();
					if(isset($TM_freq_report) && count($TM_freq_report) > 0){
						foreach ($TM_freq_report as $TM_key => $TM_value) {
							$TM_time[] = $TM_value['goal_time'];
							$TM_dist[] = Helper_Common::convertToMyDistanceUnit($user_distance, $TM_value['dist_title'], $TM_value['goal_dist']);
						}
					}else{
						$TM_time[] = '00:00:00';
						$TM_dist[] = 0;
					}
					$TM_cardiodist = (count($TM_dist) > 0 ? (float)number_format(array_sum($TM_dist), 2, '.', '') : 0);
					$TM_cardiotime = (count($TM_time) > 0 ? Helper_Common::hoursToMinutes(Helper_Common::sumTime($TM_time)) : 0);
					//last month
					$LM_freq_report = $userprofile->get_cardio_frequent_report($freq_value['freq_id'], 'month', 'last', $user_id, $is_front);
					$LM_dist = $LM_time = array();
					if(isset($LM_freq_report) && count($LM_freq_report) > 0){
						foreach ($LM_freq_report as $LM_key => $LM_value) {
							$LM_time[] = $LM_value['goal_time'];
							$LM_dist[] = Helper_Common::convertToMyDistanceUnit($user_distance, $LM_value['dist_title'], $LM_value['goal_dist']);
						}
					}else{
						$LM_time[] = '00:00:00';
						$LM_dist[] = 0;
					}
					$LM_cardiodist = (count($LM_dist) > 0 ? (float)number_format(array_sum($LM_dist), 2, '.', '') : 0);
					$LM_cardiotime = (count($LM_time) > 0 ? Helper_Common::hoursToMinutes(Helper_Common::sumTime($LM_time)) : 0);
					$M_cardiodistdiff = ($TM_cardiodist - $LM_cardiodist);
					$M_cardiotimediff = ($TM_cardiotime - $LM_cardiotime);
					$response .= '<div class="row">
						<div class="col-xs-4 datacol"><span class="info-gray">'.__('This Month').' :</span></div>
						<div class="col-xs-8 inactivedatacol">'.$TM_cardiodist.' '.$distance_unit.' '.$this->infoMessage('month', $M_cardiodistdiff).'</div>
					</div>
					<div class="row">
						<div class="col-xs-4 datacol"></div>
						<div class="col-xs-8 inactivedatacol">'.$TM_cardiotime.' min '.$this->infoMessage('month', $M_cardiotimediff).'</div>
					</div>';
					$useractivedate = date('Y-m-d', strtotime($userperioddetail['user-activedate']));
					$TM_totalfreq_cnt = $userprofile->get_cardio_frequent_average($freq_value['goal_unit_id'], 'this', $useractivedate, $user_id, $is_front);
					$TW_avgcardiocnt = ($userperioddetail['user-weeks'] > 0 ? floor($TM_totalfreq_cnt['total_cnt'] / $userperioddetail['user-weeks']) : 0);
					$TM_avgcardiocnt = ($userperioddetail['user-months'] > 0 ? floor($TM_totalfreq_cnt['total_cnt'] / $userperioddetail['user-months']) : 0);
					$LM_totalfreq_cnt = $userprofile->get_cardio_frequent_average($freq_value['goal_unit_id'], 'last', $useractivedate, $user_id, $is_front);
					$LM_avgcardiocnt = ($userperioddetail['user-months'] > 0 ? floor($LM_totalfreq_cnt['total_cnt'] / ($userperioddetail['user-months']-1)) : 0);
					$LM_avgcardiodiff = ($TM_avgcardiocnt - $LM_avgcardiocnt);
					$response .= '<div class="row">
						<div class="col-xs-4 datacol">'.__('Average').' :</div>
						<div class="col-xs-8 inactivedatacol">'.$TW_avgcardiocnt.'<span class="info-gray">/week</span></div>
						<div class="col-xs-4 datacol"></div>
						<div class="col-xs-8 inactivedatacol">'.$TM_avgcardiocnt.'<span class="info-gray">/month</span>'.$this->infoMessage('month', $LM_avgcardiodiff).'</div>
					</div>
				</div>';
				if(count($cardio_freq) != ($freq_key+1)){
					$response .='<hr>';
				}
			}
		}
		if(count($cardio_freq) < 1){
			$response .='<hr><div class="alert alert-warning"><center>No cardio activity on record</center></div>';
		}elseif(count($cardio_freq) > 0 && count($cardio_freq) < 3){
			$response .='<hr><div class="alert alert-warning"><center>No further cardio activity on record</center></div>';
		}
		return $response;
	}
	public function action_cardioReportChart(){
		if ($this->request->is_ajax())
			$this->auto_render = FALSE;
		$user_distance = (Session::instance()->get('user_distance') ? Session::instance()->get('user_distance') : '1');
		$set_distance = Helper_Common::selectgeneralfn('set_dist', 'dist_title', 'dist_id = '.$user_distance);
		$distance_unit = '';
		if(isset($set_distance) && count($set_distance) > 0){
			$distance_unit = (isset($set_distance[0]['dist_title']) ? $set_distance[0]['dist_title'] : '');
		}
		$userprofile = ORM::factory('userprofile');
		$site_id = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
		$week_sarts_on = (Session::instance()->get('user_week_starts') ? Session::instance()->get('user_week_starts') : '1');
		$week = array('1'=>'Monday','2'=>'Tuesday','3'=>'Wednesday','4'=>'Thursday','5'=>'Friday','6'=>'Saturday','7'=>'Sunday');
		$cardio_report = $cardio_freq = $returnArray = $data = array();
		$currentdate = Helper_Common::get_default_date();
		if (isset($_POST["by"])) {
			if ($_POST["by"] == 1) {
				$_POST["fdate"] = date('Y-m-d', strtotime($currentdate));
				$_POST["tdate"] = $currentdate;
			} elseif ($_POST["by"] == 2) {
				$i = 0;
				while (date("l", strtotime( "-$i days" )) != $week[$week_sarts_on]) {
					$i++;
				}
				$from = date("Y-m-d", strtotime( "-$i days" ));
				$_POST["fdate"] = date('Y-m-d', strtotime($from));
				$_POST["tdate"] = $currentdate;
			} elseif ($_POST["by"] == 3) {
				$_POST["fdate"] = date('Y-m-01', strtotime($currentdate));
				$_POST["tdate"] = $currentdate;
			} elseif ($_POST["by"] == 5) {
				$_POST["fdate"] = date('Y-01-01', strtotime($currentdate));
				$_POST["tdate"] = $currentdate;
			} elseif($_POST["by"] == 4 || $_POST["by"] == 6 || $_POST["by"] == 7 || $_POST["by"] == '') {
				$var = $_POST["fdate"];
				$_POST["fdate"] = date('Y-m-d', strtotime($var));
				$var = $_POST["tdate"];
				$_POST["tdate"] = date('Y-m-d', strtotime($var));
			}
			$cardio_report = $userprofile->get_cardio_report_chart($_POST);
			$cardio_freq = $this->cardioFrequent($_POST);
		} // print_r($cardio_report);
		$cardio1 = 'distance'; $cardio2 = 'time'; $cardio3 = 'pace';
		if(is_array($cardio_report) && count($cardio_report)>0){
			foreach($cardio_report as $key => $value){
				$unitid = $value["goal_unit_id"];
				$date = $value["assigned_date"];
				if(!isset($returnArray[$date])){
					$returnArray[$date]['title'] = $value["goal_title"];
					$returnArray[$date][$cardio1] = Helper_Common::convertToMyDistanceUnit($user_distance, $value["dist_title"], $value['goal_dist']);
					$returnArray[$date][$cardio2] = Helper_Common::hoursToSeconds($value["goal_time"]);
				}else{
					$returnArray[$date]['title'] .= ', '.$value["goal_title"];
					$returnArray[$date][$cardio1] += Helper_Common::convertToMyDistanceUnit($user_distance, $value["dist_title"], $value['goal_dist']);
					$returnArray[$date][$cardio2] += Helper_Common::hoursToSeconds($value["goal_time"]);
				}
			}
		} //print_r($returnArray);
		if(isset($returnArray) && is_array($returnArray) && count($returnArray)>0) {
			$k1 = 0;
			foreach($returnArray as $keys => $values){
				$datarow[$k1]['xkey'] = date("Y-m-d", strtotime($keys));
				$total_cardio = array();
				if(isset($values) && is_array($values) && count($values)>0){
					$cardioitem = array();
					$cardioitem['distance'] = ($values[$cardio1] > 0 ? number_format($values[$cardio1], 2, '.', '').' '.$distance_unit : '');
					$cardioitem['time'] = ($values[$cardio2] != '00:00:00' ? Helper_Common::secondsToHours($values[$cardio2]) : '');
					if(isset($_POST["by"]) && $_POST["by"] == 6) {
						$total_cardio[] = $values[$cardio1];
					}else{
						$total_cardio[] = Helper_Common::secondsToHours($values[$cardio2]);
					}
					$datarow[$k1]['title'][$values['title']] = $cardioitem;
				}
				if(isset($_POST["by"]) && $_POST["by"] == 6) {
					$datarow[$k1]['label'] = number_format(array_sum($total_cardio), 2, '.', '');
					$datarow[$k1]['ykey'] = array_sum($total_cardio);
				}else{
					$datarow[$k1]['label'] = Helper_Common::sumTime($total_cardio);
					$datarow[$k1]['ykey'] = Helper_Common::hoursToMinutes($datarow[$k1]['label']);
				}
				$k1++;
			} //print_r($datarow);
		} else {
			$datarow = array();
			$r = 0;
			$datarow[$r]['xkey'] = date("Y-m-d", strtotime($_POST["fdate"]));
			$datarow[$r]['label'] = 0;
			$datarow[$r]['ykey'] = 0;
			$r++;
			$datarow[$r]['xkey'] = date("Y-m-d", strtotime($_POST["tdate"]));
			$datarow[$r]['label'] = 0;
			$datarow[$r]['ykey'] = 0;
		}
		$data["result"] = $datarow;
		$data['frequent'] = $cardio_freq;
		$this->response->body(json_encode($data));
	}
	public function action_cardioReportChartVars(){
		if ($this->request->is_ajax())
			$this->auto_render = FALSE;
		$user_distance = (Session::instance()->get('user_distance') ? Session::instance()->get('user_distance') : '1');
		$set_distance = Helper_Common::selectgeneralfn('set_dist', 'dist_title', 'dist_id = '.$user_distance);
		$distance_unit = '';
		if(isset($set_distance) && count($set_distance) > 0){
			$distance_unit = (isset($set_distance[0]['dist_title']) ? $set_distance[0]['dist_title'] : '');
		}
		$userprofile = ORM::factory('userprofile');
 		$cardio_report = $returnArray = array(); $datarow = '';
		if (isset($_POST["cardiodate"]) && !empty($_POST["cardiodate"])) {
			$cardio_report = $userprofile->get_cardio_report_chart_vars($_POST);
		}
		// print_r($cardio_report);
		$cardio1 = 'distance'; $cardio2 = 'time'; $cardio3 = 'pace';
		if(is_array($cardio_report) && count($cardio_report)>0){
			foreach($cardio_report as $key => $value){
				$goalid = $value["goal_id"];
				$date = $value["assigned_date"];
				$returnArray[$goalid]['title'] = $value["goal_title"];
				$returnArray[$goalid][$cardio1] = Helper_Common::convertToMyDistanceUnit($user_distance, $value["dist_title"], $value['goal_dist']);
				$returnArray[$goalid][$cardio2] = Helper_Common::hoursToSeconds($value["goal_time"]);
				$returnArray[$goalid][$cardio3] = $value["goal_rate"];
				$returnArray[$goalid]['dist'] = $value["dist_title"];
				$returnArray[$goalid]['rate'] = $value["rate_title"];
				$returnArray[$goalid]['siteid'] = $value["siteid"];
			}
		} //print_r($returnArray);
		if(isset($returnArray) && is_array($returnArray) && count($returnArray)>0) {
			$datarow .= '<div class="vertical-alignment-helper">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<div class="mobpadding">
								<div class="border">
									<div class="col-xs-2">
										<a href="javascript:void(0);" title="'.__('Back').'" class="triangle" onclick="closeModelwindow('."'myModal'".');" data-ajax="false" data-role="none">
											<i class="fa fa-chevron-left iconsize2"></i>
										</a>
									</div>
									<div class="col-xs-8 optionpoptitle">'.__('Cardio activity variables for date').' '.$_POST["cardiodate"].'</div>
									<div class="col-xs-2"></div>
								</div>
							</div>
						</div>
						<div class="modal-body">
							<div class="page_content">
								<div class="row">
									<div class="col-xs-12">';
									$k1 = 0;
									foreach($returnArray as $keys => $values){
										$total_cardio = array();
										if(isset($values) && is_array($values) && count($values)>0){
											$datarow .= '<div class="xrset-vars">
												<div class="row">
													<div class="col-xs-12 datacol"><strong>'.$values['title'].' :</strong></div>
												</div>';
												if($values[$cardio2] > 0){
													$datarow .= '<div class="row">
														<div class="col-xs-4 datacol"><span class="info-gray">Time :</span></div>
														<div class="col-xs-8 inactivedatacol">'.Helper_Common::secondsToHours($values[$cardio2]).'</div>
													</div>';
												}
												if($values[$cardio1] > 0){
													$datarow .= '<div class="row">
														<div class="col-xs-4 datacol"><span class="info-gray">Distance :</span></div>
														<div class="col-xs-8 inactivedatacol">'.number_format($values[$cardio1], 2, '.', '').' '.$distance_unit.'</div>
													</div>';
												}
												if($values[$cardio3] > 0){
													$datarow .= '<div class="row">
														<div class="col-xs-4 datacol"><span class="info-gray">Pace :</span></div>
														<div class="col-xs-8 inactivedatacol">'.number_format($values[$cardio3], 2, '.', '').' '.$values['rate'].'</div>
													</div>';
												}
												if($values[$cardio2] == 0 && $values[$cardio1] == 0 && $values[$cardio3] == 0){
													$datarow .= '<span class="info-gray" style="font-size: 0.9em;">n/a</span>';
												}
											$datarow .= '</div>';
											$datarow .= (count($returnArray) != ($k1+1) ? '<hr>' : '');
											$k1++;
										}
									}
									$datarow .= '</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button data-role="none" data-ajax="false" type="button" class="btn btn-default" onclick="closeModelwindow('."'myModal'".');" style="margin-right:10px;">'.__('Close').'</button>
						</div>
					</div>
				</div>
			</div>';
		}
		$this->response->body(json_encode($datarow));
	}
	public function action_redirectToCalendarLogged(){
		$wkoutId = $this->request->post('wkoutId');
		$logId = $this->request->post('wkoutLogId');
		$assigned_date= $this->request->post('assignedDate');
		$ownWkFlag = $this->request->post('ownWkFlag');
		$response = false; $calendarurl = '';
		if(!empty($wkoutId) && !empty($logId) && !empty($assigned_date) && !empty($ownWkFlag)){
			$clickOption = 'getLoggedwrkoutpreview('."'".$wkoutId."','".$logId."','".$assigned_date."','".$ownWkFlag."'".')';
			Session::instance()->set('todayReminder', $clickOption);
			$response = true;
			$calendarurl = URL::base(TRUE).'exercise/myactionplans/'.$assigned_date;
		}
		$this->response->body(json_encode(array('result'=>$response, 'url'=>$calendarurl)));
	}
	public function action_profileconnections(){
		
	}
	public function action_getnotify(){
		$action			= Arr::get($_GET, 'action');
		$site_id  		= Session::instance()->get('current_site_id');
		$site_name_url	= Arr::get($_GET, 'siteslug');
		$site_url		= Arr::get($_GET, 'siteurl');
		$returnArray 	= array('success' => false);
		if(Auth::instance()->logged_in()){
			$user = Auth::instance()->get_user();
			$networkModel 	= ORM::factory('networks');
			$returnArray 	= array('success' => true);
			if(!empty($action) && trim($action) == 'all' && $user->pk() && !empty($site_id)){
				$chat_notify = $networkModel->get_network_users_unread_count($user->pk(),'');
				$returnArray['chatnotify'] = ($chat_notify>0 ? $chat_notify : '');
			}
		}else{
			if(empty($site_name_url))
				$site_name_url = Session::instance()->get('current_site_slug');
			Session::instance()->set('site_return_url',$site_url);
			$returnArray['loginpopup'] = '<div class="vertical-alignment-helper">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-body" style="max-height: 391px; overflow-y: auto; overflow-x: hidden;">
							<div class="page_content">
								<div class="row">
									<div class="col-sm-12">
										<form id="header-form" data-role="none" data-ajax="false" action="/site/'.$site_name_url.'/" class="navbar-form-login" id="header-form" role="form" method="post">
											<div class="form-group aligncenter" style="color:#1b9af7">
												Session was expired, Please Login again!
											</div>
											<div class="form-group">
												<p id="error_msg_login" class="error-msg "></p>
											</div>
											<div class="lt-left">
											  <div class="form-group">
												<label for="user_email" class="font-class">Email</label><br>
												<input data-role="none" data-ajax="false" class="form-control input-sm" id="email" name="user_email" value="" type="text"> 
											  </div>
											  <div class="form-group">
												<label for="password" class="font-class">Password</label><br>
												<input data-role="none" data-ajax="false" class="form-control input-sm" id="pass" name="password" type="password">
											  </div>
											  <div class="checkbox login-btm">
												<label style="float:left;">
												  <input data-role="none" data-ajax="false" name="remember" id="remember" value="1" type="checkbox">   <span class="remember-label font-class" for="remember">Remember me</span>
												</label>
												<label style="float:left;">
												  <a data-role="none" data-ajax="false" href="/site/'.$site_name_url.'/page/recover" alt="Forgotten your password?"><span class="forgot-label font-class">Forgotten your password?</span></a>
												</label>
											  </div>
										  </div>
										  <div class="lt-right">
											<button data-role="none" data-ajax="false" type="submit" name="login" style="float:right;" class="btn btn-primary btn-sm">Login</button>
										  </div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>';
		}
		$this->response->body(json_encode($returnArray));
	}
	public function action_addXrSetTag(){
		$workoutModel = ORM::factory('workouts');
		$add_tag['unit_id'] = $this->request->post('unit_id');
		$add_tag['tag-input'] = $this->request->post('xrtag-input');
		$taginsert = $workoutModel->insertUnitTagById($add_tag['tag-input'], $add_tag['unit_id']);
		if($taginsert){
			$msg = 'Tagged Successfully!!!';
			$flag = 'true';
		} else{ 
			$msg = 'Error occured while insert tag!!!';
			$flag = 'false';
		}
		$this->response->body(json_encode(array('msg' => $msg, 'flag' => $flag)));
	}
} // End Ajax
