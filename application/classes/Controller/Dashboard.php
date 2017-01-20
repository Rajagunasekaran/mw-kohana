<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Dashboard extends Controller_Website {

	public function _construct() {
         parent::__construct($request, $response);
    } 
		
	public function action_index()
	{
		if (!Auth::instance()->logged_in()) {
			if($this->request->param('site_name')){
				$this->redirect(URL::site(NULL, 'http').'site/'.$this->request->param('site_name'));
			}
		}
		$this->template->title = '';
		$workoutModel 		   = ORM::factory('workouts');
		$site_id = ($this->session->get('current_site_id') ? $this->session->get('current_site_id') : '0');
		$this->render();
		if (HTTP_Request::POST == $this->request->method()){
			$methodaction = $this->request->post('method');
			$save_edit 	  = $this->request->post('save_edit');
			$method 	  = $this->request->post('f_method');
			$XrRecid 	  = $this->request->post('xrid');
			$formtype 	  = $xrid='';
			$user_id	  = $this->globaluser->pk();
			if($save_edit == 2)
				$this->redirect('dashboard/index');
			/******************* Activity Feed *********************/
			$activity_feed = array();
			$activity_feed["user"]  		= $user_id;
			$activity_feed["site_id"]  		= $site_id;
			/******************* Activity Feed *********************/
			if($method =='add_workoutlog'){
				$countval	= $countvalskip = 0;
				$countTotal 	  				= $this->request->post('s_row_count_xr');
				$workoutRecord['wkout_title']   = $this->request->post('wkout_title');
				$workoutRecord['wkout_color']   = $this->request->post('wrkoutcolor');
				$workoutRecord['wkout_focus']   = $this->request->post('wkout_focus');
				$workoutRecord['wkout_group'] 	= $workoutRecord['wkout_poa'] = $workoutRecord['wkout_poa_time'] ='0';
				$workoutRecord['assigned_date'] = Helper_Common::get_default_date($this->request->post('selected_date_hidden'));
				$workoutRecord['note_wkout_intensity'] = $this->request->post('note_wkout_intensity');
				$workoutRecord['note_wkout_remarks']   = $this->request->post('note_wkout_remarks');
				$workoutRecord['site_id'] 			   = $site_id;
				$workoutRecord['user_id'] = $workoutRecord['modified_by'] = $workoutRecord['assigned_for'] =$user_id;
				$workoutRecord['created']  = 	$workoutRecord['modified'] = Helper_Common::get_default_datetime();
				$wkout_log_id = $workoutModel->createWkoutLogByCustom($workoutRecord, $user_id);
				$activity_feed["feed_type"]   	= 11;
				$activity_feed["action_type"] 	= '1';
				$activity_feed["type_id"]     	= $wkout_log_id;
				$activity_feed["context_date"]	= Helper_Common::get_default_datetime($workoutRecord['assigned_date']);
				Helper_Common::createActivityFeed($activity_feed);
				if(isset($_POST['exercise_title']) && !empty($_POST['exercise_title'])){
					foreach($_POST['exercise_title'] as $keys => $values){
						if(!empty($values) && trim($values) !=''){
							if(isset($_POST['markedstatus'][$keys]) && ($_POST['markedstatus'][$keys]=='1'))
								$countval += 1;
							if(isset($_POST['markedstatus'][$keys]) && ($_POST['markedstatus'][$keys]=='2'))
								$countvalskip += 1;
							$res=$workoutModel->addLoggedWorkoutSetFromworkout($_POST, $keys, $user_id, $wkout_log_id);
						}
					}
				}
				if($save_edit == '3'){
					$loggedArray['wkout_status']= '1'; // 1 -completed
					$logxrArray['set_status'] 	= '1';
					$workoutModel->updateLoggedWkoutDetails($loggedArray,$wkout_log_id);
					$workoutModel->updateLoggedWkoutXRDetails($logxrArray,$wkout_log_id);
					$save_edit = '0';
				}else if($save_edit == '4'){
					$loggedArray['wkout_status']= '2'; // 1 -skipped
					$logxrArray['set_status'] 	= '2';
					$workoutModel->updateLoggedWkoutDetails($loggedArray,$wkout_log_id);
					$workoutModel->updateLoggedWkoutXRDetails($logxrArray,$wkout_log_id);
					$save_edit = '0';
				}else{
					if($countTotal == $countval || $countTotal == ($countval +$countvalskip))
						$workoutModel->updateLoggedWkoutDetails(array('wkout_status'=>'1'), $wkout_log_id);
					elseif($countTotal == $countvalskip)
						$workoutModel->updateLoggedWkoutDetails(array('wkout_status'=>'2'), $wkout_log_id);
					elseif($countTotal > ($countval + $countvalskip))
						$workoutModel->updateLoggedWkoutDetails(array('wkout_status'=>'3'), $wkout_log_id);
				}
				$this->session->set('success','Workout Journal Created Successfully!!!');
				if($save_edit == 1)
					$this->redirect('exercise/workoutlog/'.$wkout_log_id.'?act=edit&edit=0');
			}elseif($method =='add_new_assign'){
				$updateArray['wkout_title']   = $this->request->post('wkout_title');
				$updateArray['wkout_color']   = $this->request->post('wrkoutcolor');
				$updateArray['assigned_date'] = Helper_Common::get_default_date($this->request->post('selected_date_hidden'));
				$updateArray['wkout_focus']   = $this->request->post('wkout_focus');
				$updateArray['wkout_group']   = $updateArray['wkout_poa'] = $updateArray['wkout_poa_time'] ='0';
				$updateArray['user_id'] = $updateArray['assigned_by'] = $updateArray['modified_by'] = $updateArray['assigned_for'] = $user_id;
				$updateArray['created'] = $updateArray['modified'] = Helper_Common::get_default_datetime();
				$wkout_assign_id = $workoutModel->addToWkoutAssignCustom($updateArray, $user_id);
				$activity_feed["feed_type"]   	= '13';
				$activity_feed["action_type"] 	= '1';
				$activity_feed["type_id"]     	= $wkout_assign_id;
				$activity_feed["context_date"]	= Helper_Common::get_default_datetime($updateArray['assigned_date']);
				Helper_Common::createActivityFeed($activity_feed);
				if(isset($_POST['exercise_title']) && !empty($_POST['exercise_title'])){
					foreach($_POST['exercise_title'] as $keys => $values){
						if(!empty($values) && trim($values) !=''){
							$res=$workoutModel->addAssignWorkoutSetFromworkout($_POST, $keys , $user_id, $wkout_assign_id);
						}
					}
				}
				/*** email -automation Start ***/
				$emailNotifyArray['wkout_assign_id'] = $wkout_assign_id;
				$emailNotifyArray['triggerby_date']  = Helper_Common::get_default_date($updateArray['assigned_date']);
				$emailNotifyArray['triggerby_time']  = Helper_Common::getUserEmailTime();
				$workoutModel->insertEmailNotify($emailNotifyArray);
				/*** email -automation End ***/
				$this->session->set('success','Assigned Plans Created Successfully!!!');
				if($save_edit == 1)
					$this->redirect('exercise/assignedplan/'.$wkout_assign_id.'?act=edit&edit=0');
			}elseif($method =='add_workout'){
				$wkoutArray['wkout_title']   = $this->request->post('wkout_title');
				$wkoutArray['wkout_color']   = $this->request->post('wrkoutcolor');
				$wkoutArray['wkout_focus']   = $this->request->post('wkout_focus');
				$wkoutArray['wkout_group']   = $wkoutArray['wkout_poa'] = $wkoutArray['wkout_poa_time'] ='0';
				$wkoutArray['wkout_order']	 = '1';
				$wkoutArray['status_id']	 = '1';
				$wkoutArray['created'] = $wkoutArray['created_date'] 	 = Helper_Common::get_default_datetime();
				$wkoutArray['user_id'] 		 = $user_id;
				$wkoutArray['modified'] = $wkoutArray['modified_date']	 = Helper_Common::get_default_datetime();
				$wkoutArray['site_id'] 	   	 = $site_id;
				$wkoutId = $_POST['wkout_id'] =  $workoutModel->insertWorkoutDetails($wkoutArray);
				$activity_feed["feed_type"]   	= '2';
				$activity_feed["action_type"] 	= '1';
				$activity_feed["type_id"]     	= $wkoutId;
				Helper_Common::createActivityFeed($activity_feed);
				if(isset($_POST['exercise_title']) && !empty($_POST['exercise_title'])){
					foreach($_POST['exercise_title'] as $keys => $values){
						if(!empty($values) && trim($values) !=''){
							$workoutModel->addWorkoutSetFromworkout($_POST, $keys , $this->globaluser->pk());
							$workoutModel->addLoggedWorkoutSetFromworkout($_POST, $keys, $user_id, $wkoutId);
						}
					}
				}
				$this->session->set('success','Workout Record Created Successfully!!!');
				if($save_edit == 1)
					$this->redirect('exercise/workoutrecord/'.$wkoutId.'?act=edit&edit=0');
			}elseif($method=='add_rating'){
				$rating['unit_id'] 	  = $this->request->post('unit_id');
				$rating['rate_value'] = $this->request->post('slider-1');
				$rating['rate_comments'] = $this->request->post('rating_msg');
				$rating['created_date']  = 	$rating['modified_date'] = Helper_Common::get_default_datetime();
				$rateId = $workoutModel->insertRatingDetails($rating, $this->globaluser->pk());
				$activity_feed["feed_type"]   	= '5';
				$activity_feed["action_type"] 	= '25';
				$activity_feed["type_id"]     	= $rating['unit_id'];
				Helper_Common::createActivityFeed($activity_feed);
				$this->session->set('success','Rating created successfully!!!');
			}elseif($method == 'add_tag_to_xr'){
				$add_tag['unit_id'] 	= $this->request->post('unit_id');
				$add_tag['tag-input']   = $this->request->post('xrtag-input');
				$workoutModel->insertUnitTagById($add_tag['tag-input'],$add_tag['unit_id']);
				$this->session->set('success','Tagged Successfully!!!');
			}elseif($method == 'addLog' || $method == 'addAssign'){
				$addType 	= $this->request->post('addtype');
				$wkoutId 	= $this->request->post('addid');
				$wkoutDate 	= $this->request->post('adddate');
				$datevalue	= Helper_Common::get_default_datetime();
				$updateArray['wkout_id'] 	  = $wkoutId;
				if($addType == 'wrkout'){
					$workoutRecord = $workoutModel->getworkoutById($this->globaluser->pk(),$wkoutId);
					$exerciseRecord= $workoutModel->getExerciseSets('wkout',$wkoutId);
					$updateArray['from_wkout'] 	  = '0';
					$activity_feed["feed_type"]   = '2';
				}elseif($addType == 'assigned'){
					$workoutRecord = $workoutModel->getAssignworkoutById($wkoutId, $this->globaluser->pk());
					$exerciseRecord= $workoutModel->getExerciseSets('assigned',$wkoutId);
					$updateArray['from_wkout'] 	  = '3';
					$activity_feed["feed_type"]   = '13';
				}elseif($addType == 'sample'){
					$workoutRecord = $workoutModel->getSampleworkoutById('0',$wkoutId);
					$exerciseRecord= $workoutModel->getSampleExerciseSet($wkoutId);
					$updateArray['from_wkout'] 	  = '2';
					$activity_feed["feed_type"]   = '15';
				}elseif($addType == 'shared'){
					$workoutRecord = $workoutModel->getShareworkoutById($this->globaluser->pk(), $wkoutId);
					$exerciseRecord= $workoutModel->getExerciseSets('shared',$wkoutId);
					$updateArray['from_wkout'] 	  = '1';
					$activity_feed["feed_type"]   = '12';
				}elseif($addType == 'wkoutlog'){
					$workoutRecord = $workoutModel->getLoggedworkoutById($wkoutId);
					$exerciseRecord= $workoutModel->getExerciseSets('wkoutlog',$wkoutId);
					$updateArray['from_wkout'] 	  = '4';
					$activity_feed["feed_type"]   = '11';
				}
				$updateArray['wkout_title'] = $workoutRecord['wkout_title'];
				$updateArray['wkout_color'] = $workoutRecord['wkout_color'];
				$updateArray['wkout_focus'] = $workoutRecord['wkout_focus'];
				$updateArray["modified"] 	= $datevalue;
				$updateArray['created']  	= $datevalue;
				$updateArray['modified_by']	= $updateArray['assigned_by'] = $updateArray['assigned_for'] = $this->globaluser->pk();
				$updateArray['wkout_group'] 	  = $workoutRecord['wkout_group'];
				$updateArray['wkout_order'] 	  = '1';
				$updateArray['status_id'] 	  	  = '1';
				$updateArray['user_id'] 	  	  = $this->globaluser->pk();
				$updateArray['created_date'] 	  = $datevalue;
				$updateArray['modified_date'] 	  = $datevalue;
				$updateArray['assigned_date'] 	  =	$logDate   = Helper_Common::get_default_date($wkoutDate);
				$activity_feed["action_type"] 	  = '22';
				$activity_feed["type_id"]      	  = $updateArray['wkout_id'];
				if($method == 'addLog'){
					$updateArray['wkout_status']  = '1';
					if($addType == 'assigned'){
						$updateArray['marked_status'] = '1';
						$workid = $workoutModel->createWkoutLogByassignId($wkoutId,$updateArray,false);
					}else
						$workid = $workoutModel->createWkoutLogByCustom($updateArray,$this->globaluser->pk());
					$activity_feed["json_data"]    = json_encode(array('wkoutlog'=>$workid));
				}else{
					$workid 	= $workoutModel->addToWkoutAssignCustom($updateArray,$this->globaluser->pk());
					$activity_feed["json_data"]    = json_encode(array('wkoutassign'=>$workid));
				}
				Helper_Common::createActivityFeed($activity_feed);
				$count = 0;
				if(isset($exerciseRecord) && count($exerciseRecord)>0){
					foreach($exerciseRecord as $keys => $values){
						if(is_array($values) && !empty($values)){
							$values['goal_order'] 		= $count+1;
							if($method == 'addLog')
								$values['set_status']	= '1';
							$values['wkout_id'] 	  = $workid;
							if($method == 'addLog')
								$workoutModel->addLoggedWorkoutSetFromExist($values, $this->globaluser->pk(), $workid);
							else
								$workoutModel->addAssignWorkoutSetFromExistworkout($values, $this->globaluser->pk(), $workid);
						}
					}
				}
				if($method == 'addLog'){
					$this->session->set('success','Created Workout Log Successfully!!!');
					$this->redirect('exercise/myactionplans/'.$logDate);
				}else{
					/*** email -automation Start ***/
					$emailNotifyArray['wkout_assign_id'] = $workid;
					$emailNotifyArray['triggerby_date']  = Helper_Common::get_default_date($updateArray['assigned_date']);
					$emailNotifyArray['triggerby_time']  = Helper_Common::getUserEmailTime();
					$workoutModel->insertEmailNotify($emailNotifyArray);
					/*** email -automation End ***/
					$this->session->set('success','Created Assigned Plan Successfully!!!');
					$this->redirect('exercise/myactionplans/'.$logDate);
				}
			}elseif($method == 'addExercise'){
				if(isset($_POST['addtype']) && isset($_POST['addid']) && $_POST['addid'] != ''){
					if($_POST['addtype'] == 'myexercise' || $_POST['addtype'] == 'sampleexercise' || $_POST['addtype'] == 'sharedexercise'){
						$resdata = $workoutModel->CopyDeleteExerciseRecById('exerciseRecord', $_POST['addid'], 'new_create');
						if($resdata['success']){
							$this->session->set('success','Exercise record successfully created!!!');
						}else{
							$this->session->set('error','Error occured, while creating exercise record!!!');
						}
					}
				}else{
					$this->session->set('error','Error occured, cannot create this record!!!');
				}
			}else if(($method =='save' || $method =='save-edit') && !empty($XrRecid) && isset($_POST['requestflag']) && $_POST['requestflag']=='dashboard'){
				$resdata = $workoutModel->UpdateExerciseRecByIdData($_POST, $XrRecid);
				if($resdata['success']){
					$xrid = $resdata['xrid'];
					$formtype = 'edit';
					$this->session->set('success','Exercise record successfully modified!!!');
				}else{
					$this->session->set('error','Error occurred while updating!!!');
				}
			}else if(($method =='save' || $method =='save-edit') && empty($XrRecid) && isset($_POST['requestflag']) && $_POST['requestflag']=='dashboard'){
				$resdata = $workoutModel->InsertExerciseRecByIdData($_POST);
				if($resdata['success']){
					$xrid = $resdata['xrid'];
					$formtype = 'create';
					$this->session->set('success','Exercise record successfully created!!!');
				}else{
					$this->session->set('error','Error occurred while creating!!!');
				}
			}
			if($method=='save-edit' && isset($_POST['requestflag']) && $_POST['requestflag']=='dashboard'){
				$this->redirect('exercise/exerciserecord/'.(!empty($xrid) ? $xrid : (!empty($XrRecid) ? $XrRecid : '')).'?act=indx');
			}else{
				$this->redirect('dashboard/index');
			}
		}
		$todayDate = Helper_Common::get_default_date();
		$previousDate = Helper_Common::get_default_date('-1 day');
		$cacheFileName = DOCROOT."assets/cache/".$todayDate.'-ExerciseDayRecord';
		if(file_exists($cacheFileName)){
			$this->template->content->exerciseDay =  unserialize(file_get_contents($cacheFileName));
			$oldcacheFileName		   = DOCROOT."assets/cache/".$previousDate.'-ExerciseDayRecord';
			if(file_exists($oldcacheFileName)){
				chmod($oldcacheFileName, 0777);
				unlink($oldcacheFileName);
			}
		}else
			$this->template->content->exerciseDay = $workoutModel->getExerciseOfDay();
		$this->template->content->todayPlans =  $workoutModel->getAssignedTodayWorkouts($this->globaluser->pk(),$todayDate);
		$sharedcntArray	= $workoutModel->getSharedunreadCnt($this->globaluser->pk());
		$samplecntArray = $workoutModel->getSampleWkoutunreadCnt($this->globaluser->pk());
		$sharedCntread  = (!empty($sharedcntArray['totalreadids']) ? explode('#',$sharedcntArray['totalreadids']) : array());
		$sampleCntread  = (!empty($samplecntArray['totalreadids']) ? explode('#',$samplecntArray['totalreadids']) : array());
		$sharedcnt		= $sharedcntArray['totalshare'] - (count($sharedCntread)>0 ? (count($sharedCntread) - 2) : 0);
		$samplecnt		= $samplecntArray['totalsample'] - (count($sampleCntread)>0 ? (count($sampleCntread) - 2) : 0);
		$this->template->content->overallcnt = $sharedcnt + $samplecnt;
		// xr-lib share count
		$sharedxrcntArray = $workoutModel->getSharedXrUnreadCount($this->globaluser->pk());
		$sharedxrCntread = (!empty($sharedxrcntArray['totalxrreadids']) ? explode('#',$sharedxrcntArray['totalxrreadids']) : array());
		$sharedxr_unreadcnt = (!empty($sharedxrcntArray['totalsharedxr']) ? $sharedxrcntArray['totalsharedxr'] : 0);
		if(count($sharedxrCntread) > 0){
			$sharedxr_unreadcnt = ($sharedxrcntArray['totalsharedxr'] > (count($sharedxrCntread) - 2) ? $sharedxrcntArray['totalsharedxr'] - (count($sharedxrCntread) - 2) : (count($sharedxrCntread) - 2) - $sharedxrcntArray['totalsharedxr']);
		}
		$this->template->content->sharedxrunreadcnt = $sharedxr_unreadcnt;
	}
} // End Welcome
