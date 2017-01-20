<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Exercise extends Controller_Website
{
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
        }
        $wkoutcount               = $samplecount = $sharedcount = 0;
        $workoutModel             = ORM::factory('workouts');
        $this->template->title    = 'Exercise Dashboard';
        $site_id                  = ($this->session->get('current_site_id') ? $this->session->get('current_site_id') : '0');
        $workoutModel             = ORM::factory('workouts');
        $parentFolderArray        = array();
        $userId                   = $this->globaluser->pk();
        $datevalue                = Helper_Common::get_default_datetime();
        /** activity feed starts **/
        $activity_feed            = array();
        $activity_feed["user"]    = $userId;
        $activity_feed["site_id"] = $site_id;
        /** activity feed ends **/
        $activity_feed["type_id"] = $activity_feed["action_type"] = $activity_feed["json_data"] = '';
        if (HTTP_Request::POST == $this->request->method()) {
            $method    = $this->request->post('f_method');
            $save_edit = $this->request->post('save_edit');
            if (!empty($method) && trim($method) == 'add_workout') {
                $inputArray['wkout_group']      = '0';
                $inputArray['wkout_title']      = $this->request->post('wkout_title');
                $inputArray['wkout_color']      = $this->request->post('wrkoutcolor');
                $inputArray['wkout_order']      = '1';
                $inputArray['user_id']          = $userId;
                $inputArray['status_id']        = '1';
                $inputArray['access_id']        = $this->globaluser->user_access;
                $inputArray['wkout_focus']      = $this->request->post('wkout_focus');
                $inputArray['wkout_poa']        = '0';
                $inputArray['wkout_poa_time']   = '0';
                $inputArray['parent_folder_id'] = '0';
                $inputArray['created_date']     = $datevalue;
                $inputArray['modified_date']    = $datevalue;
                $_POST['wkout_id']              = $workoutModel->insertWorkoutDetails($inputArray);
                foreach ($_POST['exercise_title'] as $keys => $values) {
                    if (!empty($values) && trim($values) != '') {
                        $res = $workoutModel->addWorkoutSetFromworkout($_POST, $keys, $userId);
                    }
                }
                $activity_feed["feed_type"]   = '2';
                $activity_feed["action_type"] = '1';
                $activity_feed["type_id"]     = $_POST['wkout_id'];
                Helper_Common::createActivityFeed($activity_feed);
                $this->session->set('success', 'Successfully <b>' . $inputArray['wkout_title'] . '</b> Workout Record was added!!!');
                if ($save_edit == 1)
                    $this->redirect('exercise/workoutrecord/' . $_POST['wkout_id'] . '/?act=edit');
            }
        }
        $this->render();
        $this->template->content->wkoutcount  = $workoutModel->getTotalWkoutCnt($userId);
        $this->template->content->samplecount = $workoutModel->getTotalSampleCnt($userId);
        $this->template->content->sharedcount = $workoutModel->getTotalSharedCnt($userId);
        $sharedcntArray                       = $workoutModel->getSharedunreadCnt($userId);
        $samplecntArray                       = $workoutModel->getSampleWkoutunreadCnt($userId);
        $sharedCntread                        = (!empty($sharedcntArray['totalreadids']) ? explode('#', $sharedcntArray['totalreadids']) : array());
        $sampleCntread                        = (!empty($samplecntArray['totalreadids']) ? explode('#', $samplecntArray['totalreadids']) : array());
        $sharedcnt                            = $sharedcntArray['totalshare'] - (count($sharedCntread) > 0 ? (count($sharedCntread) - 2) : 0);
        $samplecnt                            = $samplecntArray['totalsample'] - (count($sampleCntread) > 0 ? (count($sampleCntread) - 2) : 0);
        $this->template->content->sharedcnt   = $sharedcnt;
        $this->template->content->samplecnt   = $samplecnt;
    }
    public function action_myworkout()
    {
        $parentFolderId           = urldecode($this->request->param('id'));
        $site_id                  = ($this->session->get('current_site_id') ? $this->session->get('current_site_id') : '0');
        $workoutModel             = ORM::factory('workouts');
        $parentFolderArray        = array();
        $datevalue                = Helper_Common::get_default_datetime();
        /** activity feed starts **/
        $activity_feed            = array();
        $activity_feed["user"]    = $this->globaluser->pk();
        $activity_feed["site_id"] = $site_id;
        /** activity feed ends **/
        $activity_feed["type_id"] = $activity_feed["action_type"] = $activity_feed["json_data"] = '';
        if (HTTP_Request::POST == $this->request->method()) {
            $method    = $this->request->post('f_method');
            $save_edit = $this->request->post('save_edit');
            if (!empty($method) && trim($method) == 'addfolder') {
                $inputArray['folder_title']     = $this->request->post('folder_name');
                $inputArray['created_by']       = $inputArray['user_id'] = $this->globaluser->pk();
                $inputArray['parent_folder_id'] = $this->request->post('f_foldid');
                $inputArray['created_date']     = $datevalue;
                $inputArray['modified_date']    = $datevalue;
                $folderid                       = $workoutModel->insertFolderDetails($inputArray);
                $activity_feed["feed_type"]     = '1';
                $activity_feed["action_type"]   = '1';
                $activity_feed["type_id"]       = $folderid;
                $activity_feed["json_data"]     = json_encode($parentFolderId);
                Helper_Common::createActivityFeed($activity_feed);
                $this->session->set('success', 'Successfully <b>' . $inputArray['folder_title'] . '</b> Folder was added!!!');
            } elseif (!empty($method) && trim($method) == 'editfolder') {
                $inputArray['folder_title']     = $this->request->post('folder_name');
                $inputArray['parent_folder_id'] = $this->request->post('f_foldid');
                $inputArray['modified_by']      = $inputArray['user_id'] = $this->globaluser->pk();
                $inputArray['modified_date']    = $datevalue;
                $workoutModel->updateFolderDetails($inputArray, $this->request->post('f_id'));
                $activity_feed["feed_type"]   = '1';
                $activity_feed["action_type"] = '26';
                $activity_feed["type_id"]     = $this->request->post('f_id');
                $activity_feed["json_data"]   = json_encode(array(
                    'wkoutfolder' => $parentFolderId,
                    'oldname' => $this->request->post('old_folder_name')
                ));
                Helper_Common::createActivityFeed($activity_feed);
                $this->session->set('success', 'Successfully <b>' . $inputArray['folder_title'] . '</b> Folder was updated!!!');
            } elseif (!empty($method) && trim($method) == 'add_workout') {
                $inputArray['wkout_group']      = '0';
                $inputArray['wkout_title']      = $this->request->post('wkout_title');
                $inputArray['wkout_color']      = $this->request->post('wrkoutcolor');
                $inputArray['wkout_order']      = '1';
                $inputArray['user_id']          = $this->globaluser->pk();
                $inputArray['status_id']        = '1';
                $inputArray['access_id']        = $this->globaluser->user_access;
                $inputArray['wkout_focus']      = $this->request->post('wkout_focus');
                $inputArray['wkout_poa']        = '0';
                $inputArray['wkout_poa_time']   = '0';
                $inputArray['parent_folder_id'] = $parentFolderId;
                $inputArray['created_date']     = $datevalue;
                $inputArray['modified_date']    = $datevalue;
                $_POST['wkout_id']              = $workoutModel->insertWorkoutDetails($inputArray);
                foreach ($_POST['exercise_title'] as $keys => $values) {
                    if (!empty($values) && trim($values) != '') {
                        $res = $workoutModel->addWorkoutSetFromworkout($_POST, $keys, $this->globaluser->pk());
                    }
                }
                $activity_feed["feed_type"]   = '2';
                $activity_feed["action_type"] = '1';
                $activity_feed["type_id"]     = $_POST['wkout_id'];
                $activity_feed["json_data"]   = json_encode($parentFolderId);
                Helper_Common::createActivityFeed($activity_feed);
                $this->session->set('success', 'Successfully <b>' . $inputArray['wkout_title'] . '</b> Workout Record was added!!!');
                if ($save_edit == 1)
                    $this->redirect('exercise/workoutrecord/' . $_POST['wkout_id'] . '/?act=edit');
            } elseif ($method == 'copy_up') {
                $workid                       = $this->request->post('workout_id');
                $createdWkoutId               = $workoutModel->doCopyForExerciseSetsById('workout', $this->globaluser->pk(), '0', $workid, 'up');
                $activity_feed["feed_type"]   = '2';
                $activity_feed["action_type"] = '22';
                $activity_feed["json_data"]   = json_encode(array(
                    'wkout' => $createdWkoutId,
                    'wkoutfolder' => $parentFolderId
                ));
                $activity_feed["type_id"]     = $workid;
                Helper_Common::createActivityFeed($activity_feed);
                $this->session->set('success', 'Copied Workout Plan Successfully!!!');
            } elseif ($method == 'copy_down') {
                $workid                       = $this->request->post('workout_id');
                $createdWkoutId               = $workoutModel->doCopyForExerciseSetsById('workout', $this->globaluser->pk(), '0', $workid, 'down');
                $activity_feed["feed_type"]   = '2';
                $activity_feed["action_type"] = '22';
                $activity_feed["json_data"]   = json_encode(array(
                    'wkout' => $createdWkoutId,
                    'wkoutfolder' => $parentFolderId
                ));
                $activity_feed["type_id"]     = $workid;
                Helper_Common::createActivityFeed($activity_feed);
                $this->session->set('success', 'Copied Workout Plan Successfully!!!');
            } elseif ($method == 'delete_single') {
                $workid = $this->request->post('workout_id');
                if ($workoutModel->doDeleteForExerciseSetsById('workout', '0', $workid, $this->globaluser->pk())) {
                    $activity_feed["feed_type"]   = '2';
                    $activity_feed["action_type"] = '2';
                    $activity_feed["json_data"]   = json_encode(array(
                        'wkoutfolder' => $parentFolderId
                    ));
                    $activity_feed["type_id"]     = $workid;
                    Helper_Common::createActivityFeed($activity_feed);
                    $this->session->set('success', 'Deleted Workout Plan Successfully!!!');
                }
                $this->redirect('exercise/myworkout');
            } elseif ($method == 'assignAdd') {
                $assignArr['wkout_id']         = $this->request->post('selected_wkout_id');
                $assignArr['assigned_by']      = $assignArr['assigned_for'] = $this->globaluser->pk();
                $assignArr['modified_by']      = $this->globaluser->pk();
                $assignArr['from_wkout']       = '0';
                $assignArr['assigned_date']    = Helper_Common::get_default_date($this->request->post('selected_date'));
                $assignArr['created']          = $assignArr['modified'] = Helper_Common::get_default_datetime();
                $wkoutAssignId                 = $workoutModel->addToWkoutAssign($assignArr, $this->globaluser->pk());
                $activity_feed["feed_type"]    = '2';
                $activity_feed["action_type"]  = '22';
                $activity_feed["type_id"]      = $assignArr['wkout_id'];
                $activity_feed["context_date"] = Helper_Common::get_default_datetime($assignArr['assigned_date']);
                $activity_feed["json_data"]    = json_encode(array(
                    'wkoutassign' => $wkoutAssignId
                ));
                Helper_Common::createActivityFeed($activity_feed);
                /*** email -automation Start ***/
				$emailNotifyArray['wkout_assign_id'] = $wkoutAssignId;
				$emailNotifyArray['triggerby_date']  = Helper_Common::get_default_date($assignArr['assigned_date']);
				$emailNotifyArray['triggerby_time']  = Helper_Common::getUserEmailTime();
				$workoutModel->insertEmailNotify($emailNotifyArray);
				/*** email -automation End ***/
                $this->session->set('success', 'Assigned Workout Plan Successfully!!!');
            } elseif ($method == 'share_wkout') {
                $wkout_id              = $this->request->post('share_wkout_id');
                $shared_msg            = $this->request->post('share_msg');
                $selectedUser          = $this->request->post('seletedUser');
                $selectedSite          = $this->request->post('seletedSite');
				$assign_option		   = $this->request->post('is_share_assing');
				$assign_dates		   = $this->request->post('sharedates');
                $shareworkoutmodel     = ORM::factory('admin_shareworkout');
				$assign_dates_array	   = array();
				if($assign_option == 'on' && !empty($assign_dates)){
					$assign_dates_array = explode(',',$assign_dates);
				}
                $assignArr['user_ids'] = $assignArr['site_ids'] = '';
                if (isset($selectedSite[0]))
                    $assignArr['site_ids'] = explode(',', $selectedSite[0]);
                if (isset($selectedUser[0]))
                    $assignArr['user_ids'] = explode(',', $selectedUser[0]);
                foreach ($assignArr['site_ids'] as $key => $value) {
                    foreach ($assignArr['user_ids'] as $keys => $values) {
                        $allsites = Helper_Common::getAllSiteIdByUser($values);
                        if (in_array($value, $allsites)) {
                            $wkoutShareId   = $workoutModel->doCopyForExerciseSetsById('workoutToshare', array(
                                'shared_by' => $this->globaluser->pk(),
                                'shared_for' => $values,
                                'shared_msg' => $shared_msg,
                                'site_id' => $value
                            ), '0', $wkout_id, '');
							
							$activity_feed["feed_type"]   = 2;
                            $activity_feed["action_type"] = 7;
                            $activity_feed["user"]        = $this->globaluser->pk();
                            $activity_feed["site_id"]     = $this->session->get('current_site_id');
                            $activity_feed["type_id"]     = $wkout_id;
							
							if(isset($assign_dates_array) && count($assign_dates_array) > 0){
								$assignIds = array();
								foreach($assign_dates_array as $keydate => $valuedate){
									$shareAssign = array();
									$shareAssign['wkout_share_id'] 	 = $wkoutShareId;
									$shareAssign['assigned_user_id'] = $values;
									$shareAssign['assign_date'] 	 = Helper_Common::get_default_date($valuedate);
									$assignIds[$keydate] = $workoutModel->insertShareAssign($shareAssign);
								}
								$this->_sendShareAssignEmailToUser($wkoutShareId, $value, $values, $activity_feed,$assignIds);
							}
							$this->_sendShareEmailToUser($wkoutShareId, $value, $values, $activity_feed);
                        }
                    }
                }
                $this->session->set('success', 'Shared Workout Plan Successfully!!!');
            } elseif ($method == 'add_rating') {
                $rating['unit_id']            = $this->request->post('unit_id');
                $rating['rate_value']         = $this->request->post('slider-1');
                $rating['rate_comments']      = $this->request->post('rating_msg');
                $rating['created_date']       = $rating['modified_date'] = Helper_Common::get_default_datetime();
                $rateId                       = $workoutModel->insertRatingDetails($rating, $this->globaluser->pk());
                $activity_feed["feed_type"]   = '5';
                $activity_feed["action_type"] = '25';
                $activity_feed["type_id"]     = $rating['unit_id'];
                Helper_Common::createActivityFeed($activity_feed);
                $this->session->set('success', 'Rating created successfully!!!');
            } elseif ($method == 'add_tag_to_xr') {
                $add_tag['unit_id']   = $this->request->post('unit_id');
                $add_tag['tag-input'] = $this->request->post('xrtag-input');
                $workoutModel->insertUnitTagById($add_tag['tag-input'], $add_tag['unit_id']);
                $this->session->set('success', 'Tagged Successfully!!!');
            } elseif (!empty($method)) {
                $workouts = $this->request->post('workouts');
                $folders  = $this->request->post('folders');
                if (is_array($workouts) && count($workouts) > 0) {
                    foreach ($workouts as $keys => $values) {
                        if (trim($method) == 'copy') {
                            $wkoutId                      = $workoutModel->doCopyForWorkoutFolderById('workout', $this->globaluser->pk(), $values, $this->request->post('parent_folder_id'));
                            $activity_feed["feed_type"]   = '2';
                            $activity_feed["action_type"] = '22';
                            $activity_feed["json_data"]   = json_encode(array(
                                'wkout' => $wkoutId,
                                'wkoutfolder' => $parentFolderId
                            ));
                            $activity_feed["type_id"]     = $values;
                            Helper_Common::createActivityFeed($activity_feed);
                            $this->session->set('success', 'Successfully copied!!!');
                        } elseif (trim($method) == 'delete') {
                            $workoutModel->doDeleteForWorkoutFolderById('workout', $this->globaluser->pk(), $values, $this->request->post('parent_folder_id'));
                            $activity_feed["feed_type"]   = '2';
                            $activity_feed["action_type"] = '2';
                            $activity_feed["type_id"]     = $values;
                            $activity_feed["json_data"]   = json_encode(array(
                                'wkoutfolder' => $parentFolderId
                            ));
                            Helper_Common::createActivityFeed($activity_feed);
                            $this->session->set('success', 'Successfully deleted!!!');
                        }
                    }
                }
                if (is_array($folders) && count($folders) > 0) {
                    foreach ($folders as $keys => $values) {
                        if (trim($method) == 'copy') {
                            $folderId                     = $workoutModel->doCopyForWorkoutFolderById('folder', $this->globaluser->pk(), $values, $this->request->post('parent_folder_id'));
                            $activity_feed["feed_type"]   = '1';
                            $activity_feed["action_type"] = '22';
                            $activity_feed["type_id"]     = $folderId;
                            Helper_Common::createActivityFeed($activity_feed);
                            $this->session->set('success', 'Successfully copied!!!');
                        } elseif (trim($method) == 'delete') {
                            $workoutModel->doDeleteForWorkoutFolderById('folder', $this->globaluser->pk(), $values, $this->request->post('parent_folder_id'));
                            $activity_feed["feed_type"]   = '1';
                            $activity_feed["action_type"] = '2';
                            $activity_feed["type_id"]     = $values;
                            Helper_Common::createActivityFeed($activity_feed);
                            $this->session->set('success', 'Successfully deleted!!!');
                        }
                    }
                }
            }
            $this->redirect('exercise/myworkout/' . (!empty($parentFolderId) ? $parentFolderId : ''));
        }
        if (!empty($parentFolderId) && is_numeric($parentFolderId)) {
            $title             = 'My workout plans folder';
            $parentFolderArray = $workoutModel->getFolderDetailsByUser($this->globaluser->pk(), $parentFolderId);
            if ($parentFolderId != $this->session->get('parent_folder_no')) {
                $this->session->set('parent_folder_no', $parentFolderId);
                $activity_feed["feed_type"]   = '1';
                $activity_feed["action_type"] = '15';
                $activity_feed["type_id"]     = $parentFolderId;
                Helper_Common::createActivityFeed($activity_feed);
            }
            $myworkoutDetails = $workoutModel->getWorkoutDetailsByUser($this->globaluser->pk(), $parentFolderId);
        } else {
            if (empty($parentFolderId))
                $parentFolderId = 'mywkoutfolder';
            if ($parentFolderId != $this->session->get('parent_folder_no')) {
                $this->session->set('parent_folder_no', 'mywkoutfolder');
                $activity_feed["feed_type"]   = '22';
                $activity_feed["action_type"] = '15';
                $activity_feed["type_id"]     = $parentFolderId;
                Helper_Common::createActivityFeed($activity_feed);
            }
            $title            = 'My workout plans';
            $myworkoutDetails = $workoutModel->getWorkoutDetailsByUser($this->globaluser->pk());
        }
        $myworkouts = array();
        if (isset($myworkoutDetails) && count($myworkoutDetails) > 0) {
            foreach ($myworkoutDetails as $keys => $values) {
                $myworkouts[$keys] = $values;
            }
        }
        $this->template->title = $title;
        $this->render();
        $this->template->content->myworkouts     = $myworkouts;
        $this->template->content->parentFolder   = $parentFolderArray;
        $this->template->content->parentFolderId = (!empty($parentFolderId) ? $parentFolderId : 0);
    }
    public function action_sampleworkout()
    {
        $parentFolderId           = urldecode($this->request->param('id'));
        $site_id                  = ($this->session->get('current_site_id') ? $this->session->get('current_site_id') : '0');
        $workoutModel             = ORM::factory('workouts');
        $parentFolderArray        = array();
        $datevalue                = Helper_Common::get_default_datetime();
        /** activity feed starts **/
        $activity_feed            = array();
        $activity_feed["user"]    = $this->globaluser->pk();
        $activity_feed["site_id"] = $site_id;
        /** activity feed ends **/
        if (HTTP_Request::POST == $this->request->method()) {
            $activity_feed["type_id"] = $activity_feed["action_type"] = $activity_feed["json_data"] = '';
            $method                   = $this->request->post('f_method');
            $parent_folder_id         = $this->request->post('parent_folder_id');
            if (empty($parent_folder_id))
                $parent_folder_id = '0';
            if (!empty($method)) {
                $postbuttonArr = explode('_', $method);
                $recordId      = (isset($postbuttonArr[2]) ? $postbuttonArr[2] : '0');
                if (isset($recordId) && !empty($recordId) && is_numeric($recordId)) {
                    if (trim($method) == 'copy_workout_' . $recordId) {
                        $wkoutId                      = $workoutModel->doSampleCopyForWorkoutFolderById('sampleworkout', $this->globaluser->pk(), $recordId, $parent_folder_id);
                        $activity_feed["feed_type"]   = '15';
                        $activity_feed["action_type"] = '22';
                        $activity_feed["json_data"]   = json_encode(array(
                            'wkout' => $wkoutId
                        ));
                        $activity_feed["type_id"]     = $recordId;
                        Helper_Common::createActivityFeed($activity_feed);
                        $this->session->set('success', 'Successfully copied!!!');
                    } elseif (trim($method) == 'copy_folder_' . $recordId) {
                        $wkoutFoldId                  = $workoutModel->doSampleCopyForWorkoutFolderById('', $this->globaluser->pk(), $recordId, $parent_folder_id);
                        $activity_feed["feed_type"]   = '15';
                        $activity_feed["action_type"] = '22';
                        $activity_feed["json_data"]   = json_encode('to My Workout plans folders');
                        $activity_feed["type_id"]     = $wkoutFoldId;
                        Helper_Common::createActivityFeed($activity_feed);
                        $this->session->set('success', 'Successfully copied!!!');
                    }
                    $this->redirect('exercise/myworkout');
                } elseif ($method == 'add_rating') {
                    $rating['unit_id']            = $this->request->post('unit_id');
                    $rating['rate_value']         = $this->request->post('slider-1');
                    $rating['rate_comments']      = $this->request->post('rating_msg');
                    $rating['created_date']       = $rating['modified_date'] = Helper_Common::get_default_datetime();
                    $rateId                       = $workoutModel->insertRatingDetails($rating, $this->globaluser->pk());
                    $activity_feed["feed_type"]   = '5';
                    $activity_feed["action_type"] = '25';
                    $activity_feed["type_id"]     = $rating['unit_id'];
                    Helper_Common::createActivityFeed($activity_feed);
                    $this->session->set('success', 'Rating created successfully!!!');
                    $this->redirect('exercise/sampleworkout/' . $parent_folder_id);
                } elseif ($method == 'assignAdd') {
                    $assignArr['wkout_sample_id']  = $this->request->post('selected_sampe_id');
                    $assignArr['assigned_by']      = $assignArr['assigned_for'] = $this->globaluser->pk();
                    $assignArr['modified_by']      = $this->globaluser->pk();
                    $assignArr['from_wkout']       = '1';
                    $assignArr['assigned_date']    = Helper_Common::get_default_date($this->request->post('selected_date'));
                    $assignArr['created']          = $assignArr['modified'] = Helper_Common::get_default_datetime();
                    $wkoutAssignId                 = $workoutModel->addToWkoutAssign($assignArr, $this->globaluser->pk());
                    $activity_feed["feed_type"]    = '15';
                    $activity_feed["action_type"]  = '22';
                    $activity_feed["json_data"]    = json_encode(array(
                        'wkoutassign' => $wkoutAssignId
                    ));
                    $activity_feed["type_id"]      = $assignArr['wkout_sample_id'];
                    $activity_feed["context_date"] = Helper_Common::get_default_datetime($assignArr['assigned_date']);
                    Helper_Common::createActivityFeed($activity_feed);
					/*** email -automation Start ***/
					$emailNotifyArray['wkout_assign_id'] = $wkoutAssignId;
					$emailNotifyArray['triggerby_date']  = Helper_Common::get_default_date($assignArr['assigned_date']);
					$emailNotifyArray['triggerby_time']  = Helper_Common::getUserEmailTime();
					$workoutModel->insertEmailNotify($emailNotifyArray);
					/*** email -automation End ***/
                    $this->session->set('success', 'Assigned Workout Plan Successfully!!!');
                } elseif ($method == 'add_tag_to_xr') {
                    $add_tag['unit_id']   = $this->request->post('unit_id');
                    $add_tag['tag-input'] = $this->request->post('xrtag-input');
                    $workoutModel->insertUnitTagById($add_tag['tag-input'], $add_tag['unit_id']);
                    $this->session->set('success', 'Tagged Successfully!!!');
                }
            }
        }
        if (!empty($parentFolderId) && is_numeric($parentFolderId)) {
            $title             = 'Sample workout plans folder';
            $parentFolderArray = $workoutModel->getSampleFolderDetailsByUser(0, $parentFolderId);
            if ($parentFolderId != $this->session->get('parent_folder_no')) {
                $this->session->set('parent_folder_no', $parentFolderId);
                $activity_feed["feed_type"]   = '20';
                $activity_feed["action_type"] = '15';
                $activity_feed["type_id"]     = $parentFolderId;
                Helper_Common::createActivityFeed($activity_feed);
            }
            $myworkouts = $workoutModel->getSampleWorkoutDetails(0, $parentFolderId);
        } else {
            if (empty($parentFolderId))
                $parentFolderId = 'sample';
            if ($parentFolderId != $this->session->get('parent_folder_no')) {
                $this->session->set('parent_folder_no', 'sample');
                $activity_feed["feed_type"]   = '20';
                $activity_feed["action_type"] = '15';
                $activity_feed["type_id"]     = $parentFolderId;
                Helper_Common::createActivityFeed($activity_feed);
            }
            $title      = 'Sample workout plans';
            $myworkouts = $workoutModel->getSampleWorkoutDetails();
        }
        $updateReadCountArr = $workoutModel->getSampleWkoutunreadDetails($this->globaluser->pk(), $parentFolderId);
        if (!empty($updateReadCountArr)) {
            $replaceContent = $updateReadCountArr['wkoutidsreplace'];
            $replaceArr     = explode('#', $updateReadCountArr['wkoutidsreplace']);
            $appendArr      = explode('#', $updateReadCountArr['wkoutids']);
            $appendResArr   = array_diff($appendArr, $replaceArr);
            $appendContent  = implode('#', $appendResArr);
            if (!empty($appendContent)) {
                $insertArr['wkoutids']     = $replaceContent . $appendContent . '#';
                $insertArr['wkout_type']   = '2';
                $insertArr['read_by']      = $this->globaluser->pk();
                $insertArr['site_id']      = $site_id;
                $insertArr['status_id']    = 1;
                $insertArr['created_date'] = $insertArr['modified_date'] = $datevalue;
                $workoutModel->updateReadStatus($replaceContent, $insertArr);
            }
        }
        $this->template->title = $title;
        $this->render();
        $this->template->content->myworkouts     = $myworkouts;
        $this->template->content->parentFolder   = $parentFolderArray;
        $this->template->content->parentFolderId = (!empty($parentFolderId) ? $parentFolderId : 0);
    }
    public function action_sharedworkout()
    {
        $parentFolderId           = urldecode($this->request->param('id'));
        $shareWkoutId             = '0';
        $site_id                  = ($this->session->get('current_site_id') ? $this->session->get('current_site_id') : '0');
        $workoutModel             = ORM::factory('workouts');
        $parentFolderArray        = array();
        $datevalue                = Helper_Common::get_default_datetime();
        /** activity feed starts **/
        $activity_feed            = array();
        $activity_feed["user"]    = $this->globaluser->pk();
        $activity_feed["site_id"] = $site_id;
        /** activity feed ends **/
        if (HTTP_Request::POST == $this->request->method()) {
            $activity_feed["type_id"] = $activity_feed["action_type"] = $activity_feed["json_data"] = '';
            $method                   = $this->request->post('f_method');
            if (!empty($method)) {
                $postbuttonArr    = explode('_', $method);
                $recordId         = (isset($postbuttonArr[2]) ? $postbuttonArr[2] : '0');
                $parent_folder_id = $this->request->post('parent_folder_id');
                if (empty($parent_folder_id))
                    $parent_folder_id = '0';
                if (!empty($recordId)) {
                    if (trim($method) == 'copy_workout_' . $recordId) {
                        $wkoutId                      = $workoutModel->doShareCopyForWorkoutFolderById('shareworkout', $this->globaluser->pk(), $recordId, $parent_folder_id);
                        $activity_feed["feed_type"]   = '12';
                        $activity_feed["action_type"] = '22';
                        $activity_feed["json_data"]   = json_encode(array(
                            'wkout' => $wkoutId
                        ));
                        $activity_feed["type_id"]     = $recordId;
                        Helper_Common::createActivityFeed($activity_feed);
                        $this->session->set('success', 'Successfully copied!!!');
                    } elseif (trim($method) == 'copy_folder_' . $recordId) {
                        $wkFolderId                   = $workoutModel->doShareCopyForWorkoutFolderById('sharefolder', $this->globaluser->pk(), $recordId, $parent_folder_id);
                        $activity_feed["feed_type"]   = '12';
                        $activity_feed["action_type"] = '22';
                        $activity_feed["json_data"]   = json_encode('to My Workout plans folders');
                        $activity_feed["type_id"]     = $wkFolderId;
                        Helper_Common::createActivityFeed($activity_feed);
                        $this->session->set('success', 'Successfully copied!!!');
                    }
                    $this->redirect('exercise/myworkout');
                } elseif ($method == 'add_rating') {
                    $rating['unit_id']            = $this->request->post('unit_id');
                    $rating['rate_value']         = $this->request->post('slider-1');
                    $rating['rate_comments']      = $this->request->post('rating_msg');
                    $rating['created_date']       = $rating['modified_date'] = Helper_Common::get_default_datetime();
                    $rateId                       = $workoutModel->insertRatingDetails($rating, $this->globaluser->pk());
                    $activity_feed["feed_type"]   = '5';
                    $activity_feed["action_type"] = '25';
                    $activity_feed["type_id"]     = $rating['unit_id'];
                    Helper_Common::createActivityFeed($activity_feed);
                    $this->session->set('success', 'Rating created successfully!!!');
                    $this->redirect('exercise/shareworkout/' . $parent_folder_id);
                } elseif ($method == 'assignAdd') {
                    $assignArr['wkout_share_id']   = $this->request->post('selected_share_id');
                    $assignArr['assigned_by']      = $assignArr['assigned_for'] = $this->globaluser->pk();
                    $assignArr['modified_by']      = $this->globaluser->pk();
                    $assignArr['from_wkout']       = '1';
                    $assignArr['assigned_date']    = Helper_Common::get_default_date($this->request->post('selected_date'));
                    $assignArr['created']          = $assignArr['modified'] = Helper_Common::get_default_datetime();
                    $wkoutAssignId                 = $workoutModel->addToWkoutAssign($assignArr, $this->globaluser->pk());
                    $activity_feed["feed_type"]    = '12';
                    $activity_feed["action_type"]  = '22';
                    $activity_feed["type_id"]      = $assignArr['wkout_share_id'];
                    $activity_feed["context_date"] = Helper_Common::get_default_datetime($assignArr['assigned_date']);
                    $activity_feed["json_data"]    = json_encode(array(
                        'wkoutassign' => $wkoutAssignId
                    ));
                    Helper_Common::createActivityFeed($activity_feed);
					/*** email -automation Start ***/
					$emailNotifyArray['wkout_assign_id'] = $wkoutAssignId;
					$emailNotifyArray['triggerby_date']  = Helper_Common::get_default_date($assignArr['assigned_date']);
					$emailNotifyArray['triggerby_time']  = Helper_Common::getUserEmailTime();
					$workoutModel->insertEmailNotify($emailNotifyArray);
					/*** email -automation End ***/
                    $this->session->set('success', 'Assigned Workout Plan Successfully!!!');
                } elseif ($method == 'hide_single') {
                    $workid = $this->request->post('workout_id');
                    if ($workoutModel->doDeleteForExerciseSetsById('Sharedworkout', '0', $workid, $this->globaluser->pk())) {
                        $activity_feed["feed_type"]   = '12';
                        $activity_feed["action_type"] = '21';
                        $activity_feed["type_id"]     = $workid;
                        Helper_Common::createActivityFeed($activity_feed);
                    }
                    $this->session->set('success', 'Shared Workout Plan was Removed Successfully!!!');
                } elseif ($method == 'add_tag_to_xr') {
                    $add_tag['unit_id']   = $this->request->post('unit_id');
                    $add_tag['tag-input'] = $this->request->post('xrtag-input');
                    $workoutModel->insertUnitTagById($add_tag['tag-input'], $add_tag['unit_id']);
                    $this->session->set('success', 'Tagged Successfully!!!');
                }
            }
            $this->redirect('exercise/sharedworkout/');
        }
        if (!empty($parentFolderId) && is_numeric($parentFolderId)) {
            $title             = 'Shared workout plans folder';
            $parentFolderArray = $workoutModel->getShareFolderDetailsByUser($this->globaluser->pk(), $parentFolderId);
            if ($parentFolderId != $this->session->get('parent_folder_no')) {
                $this->session->set('parent_folder_no', $parentFolderId);
                $activity_feed["feed_type"]   = '21';
                $activity_feed["action_type"] = '15';
                $activity_feed["type_id"]     = $parentFolderId;
                Helper_Common::createActivityFeed($activity_feed);
            }
            $myworkouts = $workoutModel->getSharedWorkoutDetails($this->globaluser->pk(), $parentFolderId);
            if (empty($parentFolderArray) && empty($myworkouts)) {
                $shareWkoutId   = $parentFolderId;
                $parentFolderId = '';
                $myworkouts     = $workoutModel->getSharedWorkoutDetails($this->globaluser->pk());
            }
        } else {
            if (empty($parentFolderId))
                $parentFolderId = 'shared';
            if ($parentFolderId != $this->session->get('parent_folder_no')) {
                $this->session->set('parent_folder_no', 'shared');
                $activity_feed["feed_type"]   = '21';
                $activity_feed["action_type"] = '15';
                $activity_feed["type_id"]     = $parentFolderId;
                Helper_Common::createActivityFeed($activity_feed);
            }
            $title      = 'Shared workout plans';
            $myworkouts = $workoutModel->getSharedWorkoutDetails($this->globaluser->pk());
        }
        $updateReadCountArr = $workoutModel->getSharedunreadDetails($this->globaluser->pk(), 0, $parentFolderId);
        if (!empty($updateReadCountArr)) {
            $replaceContent = $updateReadCountArr['wkoutidsreplace'];
            $replaceArr     = explode('#', $updateReadCountArr['wkoutidsreplace']);
            $appendArr      = explode('#', $updateReadCountArr['wkoutids']);
            $appendResArr   = array_diff($appendArr, $replaceArr);
            $appendContent  = implode('#', $appendResArr);
            if (!empty($appendContent)) {
                $insertArr['wkoutids']     = $replaceContent . $appendContent . '#';
                $insertArr['wkout_type']   = '1';
                $insertArr['read_by']      = $this->globaluser->pk();
                $insertArr['site_id']      = $site_id;
                $insertArr['status_id']    = 1;
                $insertArr['created_date'] = $insertArr['modified_date'] = $datevalue;
                $workoutModel->updateReadStatus($replaceContent, $insertArr);
            }
        }
        $this->template->title = $title;
        $this->render();
        $this->template->content->myworkouts     = $myworkouts;
        $this->template->content->shareWkoutId   = $shareWkoutId;
        $this->template->content->parentFolder   = $parentFolderArray;
        $this->template->content->parentFolderId = (!empty($parentFolderId) ? $parentFolderId : 0);
    }
    public function action_workoutrecord()
    {
        $this->template->title    = 'Workout Record';
        $workoutModel             = ORM::factory('workouts');
        $workid                   = urldecode($this->request->param('id'));
        $site_id                  = ($this->session->get('current_site_id') ? $this->session->get('current_site_id') : '0');
        $exerciseid               = urldecode($this->request->param('eid'));
        $datevalue                = Helper_Common::get_default_datetime();
        /** activity feed starts **/
        $activity_feed            = array();
        $activity_feed["user"]    = $this->globaluser->pk();
        $activity_feed["site_id"] = $site_id;
        $folderId                 = '';
        $curfolderId              = (isset($_GET['fid']) && $_GET['fid'] != '' ? $_GET['fid'] : '');
        /** activity feed ends **/
        if (empty($workid))
            $this->redirect('exercise/myworkout');
        $this->render();
        if (HTTP_Request::POST == $this->request->method()) {
            $method                   = $this->request->post('f_method');
            $save_edit                = $this->request->post('save_edit');
            $start_journal            = $this->request->post('start_journal');
            $folderId                 = $this->request->post('parent_folder_id');
            $folderId                 = (!empty($folderId) ? $folderId : '');
            $activity_feed["type_id"] = $activity_feed["action_type"] = $activity_feed["json_data"] = '';
            if ($save_edit == 2 || $save_edit == 5) {
				if(empty($start_journal)){
					$activity_feed["feed_type"]   = '2';
					$activity_feed["action_type"] = '44';
					$activity_feed["type_id"]     = $workid;
					$activity_feed["json_data"]   = json_encode('without saving');
					Helper_Common::createActivityFeed($activity_feed);
				}
				$this->session->get_once('old_wkout_no');
                $this->session->get_once('old_preview_wkout_no');
				if($save_edit == 2){
					if (empty($start_journal))
						$this->redirect('exercise/workoutrecord/' . $workid . '/?act=edit');
					else
						$this->redirect('exercise/workoutrecord/' . $start_journal . '/' . $exerciseid . '/?act=edit&fid=' . $curfolderId);
				}else{
					$this->redirect('exercise/myworkout/'.$folderId);
				}
            }
            if (!empty($method)) {
                if (empty($workid))
                    $workid = $this->request->post('workout_id');
                if (!empty($workid)) {
                    if ($method == 'copy_up') {
                        $createdWkoutId               = $workoutModel->doCopyForExerciseSetsById('workout', $this->globaluser->pk(), '0', $workid, 'up');
                        $activity_feed["feed_type"]   = '2';
                        $activity_feed["action_type"] = '22';
                        $activity_feed["type_id"]     = $createdWkoutId;
                        Helper_Common::createActivityFeed($activity_feed);
                        $this->session->set('success', 'Copied Workout Plan Successfully!!!');
                    } elseif ($method == 'copy_down') {
                        $createdWkoutId               = $workoutModel->doCopyForExerciseSetsById('workout', $this->globaluser->pk(), '0', $workid, 'down');
                        $activity_feed["feed_type"]   = '2';
                        $activity_feed["action_type"] = '22';
                        $activity_feed["type_id"]     = $createdWkoutId;
                        Helper_Common::createActivityFeed($activity_feed);
                        $this->session->set('success', 'Copied Workout Plan Successfully!!!');
                    } elseif ($method == 'delete') {
                        if ($workoutModel->doDeleteForExerciseSetsById('workout', '0', $workid, $this->globaluser->pk())) {
                            $activity_feed["feed_type"]   = '2';
                            $activity_feed["action_type"] = '2';
                            $activity_feed["type_id"]     = $workid;
                            Helper_Common::createActivityFeed($activity_feed);
                            $this->session->set('success', 'Deleted Workout Plan Successfully!!!');
                        }
                        $this->redirect('exercise/myworkout/' . $folderId);
                    } elseif ($method == 'assignAdd') {
                        $assignArr['wkout_id']         = $this->request->post('selected_wkout_id');
                        $assignArr['assigned_by']      = $assignArr['assigned_for'] = $this->globaluser->pk();
                        $assignArr['modified_by']      = $this->globaluser->pk();
                        $assignArr['from_wkout']       = '0';
                        $assignArr['assigned_date']    = Helper_Common::get_default_date($this->request->post('selected_date'));
                        $assignArr['created']          = $assignArr['modified'] = Helper_Common::get_default_datetime();
                        $wkoutAssignId                 = $workoutModel->addToWkoutAssign($assignArr, $this->globaluser->pk());
                        $activity_feed["feed_type"]    = '12';
                        $activity_feed["action_type"]  = '5';
                        $activity_feed["type_id"]      = $assignArr['wkout_id'];
                        $activity_feed["context_date"] = Helper_Common::get_default_datetime($assignArr['assigned_date']);
                        $activity_feed["json_data"]    = json_encode(array(
                            'assigned' => $wkoutAssignId
                        ));
                        Helper_Common::createActivityFeed($activity_feed);
						/*** email -automation Start ***/
						$emailNotifyArray['wkout_assign_id'] = $wkoutAssignId;
						$emailNotifyArray['triggerby_date']  = Helper_Common::get_default_date($assignArr['assigned_date']);
						$emailNotifyArray['triggerby_time']  = Helper_Common::getUserEmailTime();
						$workoutModel->insertEmailNotify($emailNotifyArray);
						/*** email -automation End ***/
                        $this->session->set('success', 'Assigned Workout Plan Successfully!!!');
                    } elseif ($method == 'add_new_log_skip') {
                        $loggedArray['note_wkout_intensity'] = $this->request->post('slider-1');
                        $loggedArray['note_wkout_remarks']   = $this->request->post('note_wkout_remarks');
                        $loggedArray['from_wkout']           = '0';
                        $loggedArray['assigned_by']          = $loggedArray['assigned_for'] = $this->globaluser->pk();
                        $loggedArray['modified_by']          = $this->globaluser->pk();
                        $loggedArray['assigned_date']        = Helper_Common::get_default_date();
                        $loggedArray['created']              = $loggedArray['modified'] = Helper_Common::get_default_datetime();
                        $wkoutLogId                          = $workoutModel->createWkoutLogBywkoutId('workout', $workid, $loggedArray, $this->globaluser->pk());
                        $activity_feed["feed_type"]          = '2';
                        $activity_feed["action_type"]        = '6';
                        $activity_feed["type_id"]            = $wkoutLogId;
                        $activity_feed["json_data"]          = json_encode(' on ' . $loggedArray['assigned_date']);
                        Helper_Common::createActivityFeed($activity_feed);
                        $this->session->set('success', 'Logged Workout Plan Successfully!!!');
                    } elseif ($method == 'add_tag_to_xr') {
                        $add_tag['unit_id']   = $this->request->post('unit_id');
                        $add_tag['tag-input'] = $this->request->post('xrtag-input');
                        $workoutModel->insertUnitTagById($add_tag['tag-input'], $add_tag['unit_id']);
                        $this->session->set('success', 'Tagged Successfully!!!');
                    }
                } elseif ($method == 'add_rating') {
                    $rating['unit_id']            = $this->request->post('unit_id');
                    $rating['rate_value']         = $this->request->post('slider-1');
                    $rating['rate_comments']      = $this->request->post('rating_msg');
                    $rating['created_date']       = $rating['modified_date'] = Helper_Common::get_default_datetime();
                    $rateId                       = $workoutModel->insertRatingDetails($rating, $this->globaluser->pk());
                    $activity_feed["feed_type"]   = '5';
                    $activity_feed["action_type"] = '25';
                    $activity_feed["type_id"]     = $rating['unit_id'];
                    Helper_Common::createActivityFeed($activity_feed);
                    $this->session->set('success', 'Rating created successfully!!!');
                }
            } else if (isset($_POST['wrkoutname_hidden']) && !empty($_POST['wrkoutname_hidden']) && !empty($workid) && $method == '') {
                $updateArray['wkout_title'] = $workouttitle = $this->request->post('wrkoutname_hidden');
                $updateArray['wkout_color'] = $this->request->post('wrkoutcolor');
                $updateArray['wkout_focus'] = $this->request->post('wkout_focus');
                if (empty($start_journal)) {
                    $workoutModel->updateWkoutDetails($updateArray, $workid);
                    $added                        = 0;
                    $activity_feed["feed_type"]   = '2';
                    $activity_feed["action_type"] = '26';
                    $activity_feed["type_id"]     = $workid;
                    Helper_Common::createActivityFeed($activity_feed);
                } else {
                    $updateArray["modified"]    = $datevalue;
                    $updateArray['created']     = $datevalue;
                    $updateArray['modified_by'] = $updateArray['assigned_by'] = $updateArray['assigned_for'] = $this->globaluser->pk();
                    $updateArray['wkout_id']    = $this->request->post('wkout_id');
                    if ($start_journal == 'startwkout') {
                        $activity_feed["feed_type"] = '2';
                        $updateArray['from_wkout']  = '0';
                    } elseif ($start_journal == 'startassign') {
                        $activity_feed["feed_type"] = '13';
                        $updateArray['from_wkout']  = '3';
                    } elseif ($start_journal == 'startsample') {
                        $activity_feed["feed_type"] = '15';
                        $updateArray['from_wkout']  = '2';
                    } elseif ($start_journal == 'startshare') {
                        $activity_feed["feed_type"] = '12';
                        $updateArray['from_wkout']  = '1';
                    } elseif ($start_journal == 'startwklog') {
                        $activity_feed["feed_type"] = '11';
                        $updateArray['from_wkout']  = '4';
                    }
                    $updateArray['wkout_group']      = '0';
                    $updateArray['wkout_order']      = '1';
                    $updateArray['status_id']        = '1';
                    $updateArray['user_id']          = $this->globaluser->pk();
                    $updateArray['created_date']     = $datevalue;
                    $updateArray['modified_date']    = $datevalue;
                    $updateArray['parent_folder_id'] = $curfolderId;
                    $workid                          = $_POST['wkout_id'] = $workoutModel->insertWorkoutDetails($updateArray);
                    $activity_feed["action_type"]    = '22';
                    $activity_feed["type_id"]        = $updateArray['wkout_id'];
                    $activity_feed["json_data"]      = json_encode(array(
                        'wkoutlog' => $workid,
                        'wkoutfolder' => $curfolderId
                    ));
                    Helper_Common::createActivityFeed($activity_feed);
                }
                if (isset($_POST['exercise_title']) && count($_POST['exercise_title']) > 0) {
                    foreach ($_POST['exercise_title'] as $keys => $values) {
                        if (!empty($values) && trim($values) != '' && isset($_POST['goal_remove'][$keys]) && empty($_POST['goal_remove'][$keys])) {
                            $goal_id                        = $keys;
                            $updateArray                    = array();
                            $updateArray['goal_order']      = (($_POST['goal_order'][$keys]) ? $_POST['goal_order'][$keys] : '0');
                            $updateArray['title']           = $_POST['exercise_title'][$keys];
                            $updateArray['goal_title_self'] = '1';
							$updateArray['goal_unit_id']   	= 0;
                            if (isset($_POST['exercise_unit'][$keys])){
								$exerciseUnitArray = explode('_',$_POST['exercise_unit'][$keys]);
								if(isset($exerciseUnitArray[1]) && is_numeric($exerciseUnitArray[1])){
									$updateArray['goal_title_self'] = 0;
									$updateArray['goal_unit_id']	= $exerciseUnitArray[1];
								}else{
									$updateArray['goal_unit_id']	= 0;
								}
							}
                            $updateArray['goal_resist']    = $_POST['exercise_resistance'][$keys];
                            $updateArray['goal_resist_id'] = $_POST['exercise_unit_resistance'][$keys];
                            $updateArray['goal_reps']      = $_POST['exercise_repetitions'][$keys];
                            $updateArray['goal_time_hh']   = $updateArray['goal_time_mm'] = $updateArray['goal_time_ss'] = '';
                            if (!empty($_POST['exercise_time'][$keys])) {
                                $timeArray                   = explode(":", $_POST['exercise_time'][$keys]);
                                $updateArray['goal_time_hh'] = $timeArray['0'];
                                $updateArray['goal_time_mm'] = $timeArray['1'];
                                $updateArray['goal_time_ss'] = $timeArray['2'];
                            }
                            $updateArray['goal_dist']     = $_POST['exercise_distance'][$keys];
                            $updateArray['goal_dist_id']  = $_POST['exercise_unit_distance'][$keys];
                            $updateArray['goal_rate']     = $_POST['exercise_rate'][$keys];
                            $updateArray['goal_rate_id']  = $_POST['exercise_unit_rate'][$keys];
                            $updateArray['goal_int_id']   = $_POST['exercise_innerdrive'][$keys];
                            $updateArray['goal_angle']    = $_POST['exercise_angle'][$keys];
                            $updateArray['goal_angle_id'] = $_POST['exercise_unit_angle'][$keys];
                            $updateArray['goal_rest_mm']  = $updateArray['goal_rest_ss'] = '';
                            if (!empty($_POST['exercise_rest'][$keys])) {
                                $resttimeArray               = explode(":", $_POST['exercise_rest'][$keys]);
                                $updateArray['goal_rest_mm'] = $resttimeArray['0'];
                                $updateArray['goal_rest_ss'] = $resttimeArray['1'];
                            }
                            $goal_remarks                  = $_POST['exercise_remark'][$keys];
                            $updateArray['goal_remarks']   = (!empty($goal_remarks) ? strip_tags($goal_remarks) : '');
                            $updateArray['primary_time']   = (($_POST['primary_time'][$keys]) ? $_POST['primary_time'][$keys] : '0');
                            $updateArray['primary_dist']   = (($_POST['primary_dist'][$keys]) ? $_POST['primary_dist'][$keys] : '0');
                            $updateArray['primary_reps']   = (($_POST['primary_reps'][$keys]) ? $_POST['primary_reps'][$keys] : '0');
                            $updateArray['primary_resist'] = (($_POST['primary_resist'][$keys]) ? $_POST['primary_resist'][$keys] : '0');
                            $updateArray['primary_rate']   = (($_POST['primary_rate'][$keys]) ? $_POST['primary_rate'][$keys] : '0');
                            $updateArray['primary_angle']  = (($_POST['primary_angle'][$keys]) ? $_POST['primary_angle'][$keys] : '0');
                            $updateArray['primary_rest']   = (($_POST['primary_rest'][$keys]) ? $_POST['primary_rest'][$keys] : '0');
                            $updateArray['primary_int']    = (($_POST['primary_int'][$keys]) ? $_POST['primary_int'][$keys] : '0');
							if(is_numeric($keys) && empty($start_journal)){
								$workoutModel->updateExerciseSet($updateArray, $goal_id);
							}else{
								$updateArray['goal_title'] = $updateArray['title'];
                                $updateArray['wkout_id']   = $this->request->post('wkout_id');
                                $res                       = $workoutModel->addWorkoutSetFromExistworkout($updateArray, $this->globaluser->pk(), $workid);
								$_POST['goal_order'][$res] = $_POST['goal_order'][$keys];
								$_POST['goal_remove'][$res]= $_POST['goal_remove'][$keys];
								unset($_POST['goal_order'][$keys]);
								unset($_POST['goal_remove'][$keys]);
							}
                        }
                    }
                }
                if (isset($_POST['goal_order']) && count($_POST['goal_order']) > 0) {
                    foreach ($_POST['goal_order'] as $keys => $values) {
                        $workoutModel->updateGoalOrder($values, $workid, $keys, $this->globaluser->pk());
                    }
                }
                if (isset($_POST['goal_remove']) && count($_POST['goal_remove']) > 0) {
                    foreach ($_POST['goal_remove'] as $keys => $values) {
                        if ($values != '0') {
                            $workoutModel->doDeleteForExerciseSetsById('exerciseSet', $keys, $workid, $this->globaluser->pk());
                        }
                    }
                }
                if (!empty($start_journal)) {
					$this->session->get_once('old_wkout_no');
					$this->session->get_once('old_preview_wkout_no');
                    $this->session->set('success', 'Successfully <b>' . $workouttitle . '</b> Workout Record was added!!!');
                    $this->redirect('exercise/myworkout/' . $curfolderId);
                } else {
                    if (isset($added) && $added == 1)
                        $this->session->set('success', 'Created Exercise Set Successfully!!!');
                    else if (isset($added) && $added == 2)
                        $this->session->set('success', 'Edited Exercise Set Successfully!!!');
                    else
                        $this->session->set('success', 'Edited Workout Plans Successfully!!!');
                }
            }
            if ($save_edit == 1) {
                $this->session->get_once('old_wkout_no');
                $this->session->get_once('old_preview_wkout_no');
                $this->redirect('exercise/workoutrecord/' . $workid . '/?act=edit');
            } else{
				$this->session->get_once('old_wkout_no');
                $this->session->get_once('old_preview_wkout_no');
                $this->redirect('exercise/myworkout/' . $folderId);
			}
        }
        if (!empty($workid) && !is_numeric($workid)) {
            $this->template->content->startJournal = $workid;
            if ($workid == 'startwkout') {
                $this->template->content->workoutRecord  = $workoutModel->getworkoutById($this->globaluser->pk(), $exerciseid);
                $exerciseRecord = $workoutModel->getExerciseSets('wkout', $exerciseid);
                $this->template->content->exerciseRecord = Helper_Common::exerciseSetsCombine($exerciseRecord,'wkout_id');
                $this->template->content->wkout_id       = $this->template->content->workoutRecord['wkout_id'];
            } elseif ($workid == 'startassign') {
                $this->template->content->workoutRecord  = $workoutModel->getAssignworkoutById($exerciseid, $this->globaluser->pk());
                $exerciseRecord = $workoutModel->getExerciseSets('assigned', $exerciseid);
				$this->template->content->exerciseRecord = Helper_Common::exerciseSetsCombine($exerciseRecord,'wkout_assign_id');
                $this->template->content->wkout_id       = $this->template->content->workoutRecord['wkout_assign_id'];
            } elseif ($workid == 'startsample') {
                $this->template->content->workoutRecord  = $workoutModel->getSampleworkoutById('0', $exerciseid);
                $exerciseRecord = $workoutModel->getSampleExerciseSet($exerciseid);
				$this->template->content->exerciseRecord = Helper_Common::exerciseSetsCombine($exerciseRecord,'wkout_sample_id');
                $this->template->content->wkout_id       = $this->template->content->workoutRecord['wkout_sample_id'];
            } elseif ($workid == 'startshare') {
                $this->template->content->workoutRecord  = $workoutModel->getShareworkoutById($this->globaluser->pk(), $exerciseid);
                $exerciseRecord = $workoutModel->getExerciseSets('shared', $exerciseid);
				$this->template->content->exerciseRecord = Helper_Common::exerciseSetsCombine($exerciseRecord,'wkout_share_id');
                $this->template->content->wkout_id       = $this->template->content->workoutRecord['wkout_share_id'];
            } elseif ($workid == 'startwklog') {
                $this->template->content->workoutRecord  = $workoutModel->getLoggedworkoutById($exerciseid);
                $exerciseRecord = $workoutModel->getExerciseSets('wkoutlog', $exerciseid);
				$this->template->content->exerciseRecord = Helper_Common::exerciseSetsCombine($exerciseRecord,'wkout_log_id');
                $this->template->content->wkout_id       = $this->template->content->workoutRecord['wkout_log_id'];
            }
            if (empty($this->template->content->workoutRecord))
                $this->redirect('exercise/myactionplans/' . $date );
            $this->template->content->focusRecord = $workoutModel->getAllFocus();
            $workid                               = $exerciseid;
        } elseif (!empty($workid) && is_numeric($workid)) {
            if ($workid != $this->session->get('old_wkout_no') && !empty($_GET['act'])) {
                $this->session->set('old_wkout_no', $workid);
                $activity_feed["feed_type"]   = '2';
                $activity_feed["action_type"] = '15';
                $activity_feed["type_id"]     = $workid;
                $activity_feed["json_data"]   = json_encode('edit-mode');
                Helper_Common::createActivityFeed($activity_feed);
            } elseif ($workid != $this->session->get('old_preview_wkout_no') && empty($_GET['act'])) {
                $this->session->set('old_preview_wkout_no', $workid);
                $activity_feed["feed_type"]   = '2';
                $activity_feed["action_type"] = '42';
                $activity_feed["type_id"]     = $workid;
                Helper_Common::createActivityFeed($activity_feed);
            }
            $this->template->content->exerciseSetId = '';
            $this->template->content->workoutRecord = $workoutModel->getworkoutById($this->globaluser->pk(), $workid);
            if (empty($this->template->content->workoutRecord))
                $this->redirect('exercise/myworkout');
            $this->template->content->focusRecord    = $workoutModel->getAllFocus();
            $exerciseRecord = $workoutModel->getExerciseSet($workid);
			$this->template->content->exerciseRecord = Helper_Common::exerciseSetsCombine($exerciseRecord);
            $this->template->content->wkout_id       = $this->template->content->workoutRecord['wkout_id'];
        } else
            $this->redirect('exercise/myworkout');
        $this->template->content->popupAct     = (!empty($_GET['act']) ? $_GET['act'] : '');
        $this->template->content->workoutId    = trim($workid);
        $this->template->content->save         = (!empty($_GET['p']) ? $_GET['p'] : '');
        $this->template->content->userId       = $this->globaluser->pk();
        $this->template->content->popupEdit    = (!empty($_GET['edit']) ? $_GET['edit'] : '');
        $this->template->content->colorsRecord = $workoutModel->getColors();
    }
    public function action_assignedplan()
    {
        $this->template->title = 'Assigned Plan';
        $workoutModel          = ORM::factory('workouts');
        $workassignid          = urldecode($this->request->param('id'));
        $site_id               = ($this->session->get('current_site_id') ? $this->session->get('current_site_id') : '0');
        $exerciseid            = urldecode($this->request->param('eid'));
        $popupAct              = (!empty($_GET['act']) ? $_GET['act'] : '');
        $allowEdit             = (isset($_GET['edit']) ? $_GET['edit'] : '');
        $datevalue             = Helper_Common::get_default_datetime();
        $date                  = (!empty($_GET['date']) ? $_GET['date'] : '');
        $todayDate             = Helper_Common::get_default_date();
        $datediff              = strtotime($date) - strtotime($todayDate);
        $difference            = floor($datediff / (60 * 60 * 24));
        if ($difference < 0)
            $date = $todayDate;
        else
            $date = Helper_Common::get_default_date($date . " 00:00:00");
        /** activity feed starts **/
        $activity_feed            = array();
        $save_edit                = 0;
        $activity_feed["user"]    = $this->globaluser->pk();
        $activity_feed["site_id"] = $site_id;
        /** activity feed ends **/
        if (empty($workassignid))
            $this->redirect('exercise/myactionplans/' . $date);
        $this->render();
        if (HTTP_Request::POST == $this->request->method()) {
            $save_edit                = $this->request->post('save_edit');
            $method                   = $this->request->post('f_method');
            $start_journal            = $this->request->post('start_journal');
            $activity_feed["type_id"] = $activity_feed["action_type"] = $activity_feed["json_data"] = '';
			if ($save_edit == 2 || $save_edit == 5) {
				if(empty($start_journal)){
					$activity_feed["feed_type"]   = '13';
					$activity_feed["action_type"] = '44';
					$activity_feed["type_id"]     = $_POST["wkout_assign_id"];
					$activity_feed["json_data"]   = json_encode('without saving');
					$activity_feed["context_date"] = Helper_Common::get_default_datetime($this->request->post('assigned_date'));
					Helper_Common::createActivityFeed($activity_feed);
				}
				$this->session->get_once('old_wkout_assign_no');
				$this->session->get_once('old_preview_wkout_assign_no');
				if($save_edit == 2){
					if (empty($start_journal))
						$this->redirect('exercise/assignedplan/' . $_POST["wkout_assign_id"] . '?act=edit');
					else
						$this->redirect('exercise/assignedplan/' . $start_journal . '/' . $exerciseid . '/?act=edit');
				}else{
					$this->redirect('exercise/myactionplans/'.date('Y-m-d',strtotime($this->request->post('assigned_date').' 00:00:00')));
				}
            }
            if (!empty($method)) {
                if (empty($workassignid))
                    $workassignid = $this->request->post('wkout_assign_id');
                if (!empty($workassignid)) {
                    if ($method == 'add_new_log_comp') {
                        $assignedArray['note_wkout_intensity'] = $this->request->post('slider-1');
                        $assignedArray['note_wkout_remarks']   = $this->request->post('note_wkout_remarks');
                        $assignedArray['created']              = $datevalue;
                        $assignedArray['from_wkout']           = "3";
                        $assignedArray['modified']             = $datevalue;
                        $assignedArray['modified_by']          = $this->globaluser->pk();
						$assignedArray['assigned_date']    	   = $workoutModel->getAssignDateById($workassignid);
                        $wkoutLog                              = $workoutModel->createWkoutLogBywkoutId('assigned', $workassignid, $assignedArray, $this->globaluser->pk());
                        $activity_feed["feed_type"]            = '13';
                        $activity_feed["action_type"]          = '6';
                        $activity_feed["type_id"]              = $workassignid;
                        $activity_feed["json_data"]            = json_encode(' on ' . $assignedArray['assigned_date'] . ' as Completed');
                        Helper_Common::createActivityFeed($activity_feed);
                        $this->session->set('success', 'Logged Assigned Plan Marked as Completed!!!');
                    } elseif ($method == 'add_new_log_skip') {
                        $assignedArray['note_wkout_intensity'] = $this->request->post('slider-1');
                        $assignedArray['note_wkout_remarks']   = $this->request->post('note_wkout_remarks');
                        $assignedArray['created']              = $datevalue;
                        $assignedArray['from_wkout']           = "3";
                        $assignedArray['modified']             = $datevalue;
                        $assignedArray['modified_by']          = $this->globaluser->pk();
                        $assignedArray['assigned_date']        = Helper_Common::get_default_date();
                        //$assignedArray['wkout_status'] 	   	   = '2'; // 2 -skipped
                        $wokoutLog                             = $workoutModel->createWkoutLogBywkoutId('assigned', $workassignid, $assignedArray, $this->globaluser->pk());
                        $activity_feed["feed_type"]            = '13';
                        $activity_feed["action_type"]          = '6';
                        $activity_feed["type_id"]              = $workassignid;
                        $activity_feed["json_data"]            = json_encode(' on ' . $assignedArray['assigned_date'] . ' as Skipped');
                        Helper_Common::createActivityFeed($activity_feed);
                        $this->session->set('success', 'Logged Assigned Plan Marked as Skipped!!!');
                    } elseif ($method == 'copy') {
						$oldAssignedDate               = $workoutModel->getAssignDateById($workassignid);
                        $workoutAssign                = $workoutModel->doCopyForExerciseSetsById('workoutAssign', $this->globaluser->pk(), '0', $workassignid, 'copy');
                        $activity_feed["feed_type"]   = '15';
                        $activity_feed["action_type"] = '22';
                        $activity_feed["type_id"]     = $workoutAssign;
                        Helper_Common::createActivityFeed($activity_feed);
						/*** email -automation Start ***/
						$emailNotifyArray['wkout_assign_id'] = $workoutAssign;
						$emailNotifyArray['triggerby_date']  = Helper_Common::get_default_date($oldAssignedDate);
						$emailNotifyArray['triggerby_time']  = Helper_Common::getUserEmailTime();
						$workoutModel->insertEmailNotify($emailNotifyArray);
						/*** email -automation End ***/
                        $this->session->set('success', 'Copied Assigned Plan Successfully!!!');
                    } elseif ($method == 'cancel') {
                        $activity_feed["feed_type"]    = '13';
                        $activity_feed["action_type"]  = '2';
                        $oldAssignedDate               = $workoutModel->getAssignDateById($workassignid);
                        $activity_feed["context_date"] = Helper_Common::get_default_datetime($oldAssignedDate);
                        $workoutModel->updateMarkedStatus($workassignid, '3');
                        $activity_feed["type_id"] = $workassignid;
                        Helper_Common::createActivityFeed($activity_feed);
						/*** email -automation Start ***/
						$workoutModel->updateEmailNotifyByAssignId($workassignid);
						/*** email -automation End ***/
                        $this->session->set('success', 'Assigned Plan Cancelled Successfully!!!');
                    } elseif ($method == 'assignAdd') {
                        $assignArr['wkout_assign_id'] = $this->request->post('selected_wkout_assign_id');
                        $oldAssignedDate              = $workoutModel->getAssignDateById($assignArr['wkout_assign_id']);
                        $assignArr['modified_by']     = $this->globaluser->pk();
                        $assignArr['from_wkout']      = "3";
                        $assignArr['assigned_date']   = Helper_Common::get_default_date($this->request->post('selected_date'));
                        $assignArr['modified']        = Helper_Common::get_default_datetime();
                        $workoutModel->addToReassignWkoutAssign($assignArr);
                        $activity_feed["feed_type"]    = '13';
                        $activity_feed["action_type"]  = '24';
                        $activity_feed["type_id"]      = $assignArr['wkout_assign_id'];
                        $activity_feed["json_data"]    = json_encode(Helper_Common::get_default_datetime($assignArr['assigned_date']));
                        $activity_feed["context_date"] = Helper_Common::get_default_datetime($oldAssignedDate);
                        Helper_Common::createActivityFeed($activity_feed);
						/*** email -automation Start ***/
						$emailNotifyArray['triggerby_date']  = Helper_Common::get_default_date($assignArr['assigned_date']);
						$emailNotifyArray['triggerby_time']  = Helper_Common::getUserEmailTime();
						$workoutModel->updateEmailNotifyByAssignId($oldAssignedDate, $emailNotifyArray);
						/*** email -automation End ***/
                        $this->session->set('success', 'Assigned Plan Re-assigned Successfully!!!');
                    } elseif ($method == 'add_new_wkout') {
                        $wkoutId                      = $workoutModel->doCopyForExerciseSetsById('workoutAssignToNewWkout', $this->globaluser->pk(), '0', $workassignid, 'copy');
                        $activity_feed["feed_type"]   = '13';
                        $activity_feed["action_type"] = '22';
                        $activity_feed["type_id"]     = $workassignid;
                        $activity_feed["json_data"]   = json_encode('New Workout');
                        Helper_Common::createActivityFeed($activity_feed);
                        $this->session->set('success', 'Assigned Plan add as New Workout Successfully!!!');
                    } elseif ($method == 'add_tag_to_xr') {
                        $add_tag['unit_id']   = $this->request->post('unit_id');
                        $add_tag['tag-input'] = $this->request->post('xrtag-input');
                        $workoutModel->insertUnitTagById($add_tag['tag-input'], $add_tag['unit_id']);
                        $this->session->set('success', 'Tagged Successfully!!!');
                    }
                } elseif ($method == 'add_rating') {
                    $rating['unit_id']            = $this->request->post('unit_id');
                    $rating['rate_value']         = $this->request->post('slider-1');
                    $rating['rate_comments']      = $this->request->post('rating_msg');
                    $rating['created_date']       = $rating['modified_date'] = Helper_Common::get_default_datetime();
                    $rateId                       = $workoutModel->insertRatingDetails($rating, $this->globaluser->pk());
                    $activity_feed["feed_type"]   = '5';
                    $activity_feed["action_type"] = '25';
                    $activity_feed["type_id"]     = $rating['unit_id'];
                    Helper_Common::createActivityFeed($activity_feed);
                    $this->session->set('success', 'Rating created successfully!!!');
                }
            } else if (isset($_POST['wrkoutname_hidden']) && !empty($_POST['wrkoutname_hidden']) && !empty($workassignid) && $method == '') {
                $updateArray['wkout_title']   = $this->request->post('wrkoutname_hidden');
                $updateArray['wkout_color']   = $this->request->post('wrkoutcolor');
                $updateArray['wkout_focus']   = $this->request->post('wkout_focus');
                $updateArray["modified"]      = $datevalue;
                $updateArray["assigned_date"] = Helper_Common::get_default_date($this->request->post('assigned_date'));
                if (empty($start_journal)) {
                    $activity_feed["feed_type"] = '13';
                    $workoutModel->updateAssignedWkoutDetails($updateArray, $workassignid);
                    $activity_feed["action_type"]  = '26';
                    $activity_feed["type_id"]      = $workassignid;
                    $activity_feed["context_date"] = Helper_Common::get_default_datetime($updateArray["assigned_date"]);
                    Helper_Common::createActivityFeed($activity_feed);
                } else {
                    $updateArray['created']     = $datevalue;
                    $updateArray['modified_by'] = $updateArray['assigned_by'] = $updateArray['assigned_for'] = $this->globaluser->pk();
                    $updateArray['wkout_id']    = $this->request->post('wkout_id');
                    if ($start_journal == 'startwkout') {
                        $activity_feed["feed_type"] = '2';
                        $updateArray['from_wkout']  = '0';
                    } elseif ($start_journal == 'startassign') {
                        $activity_feed["feed_type"] = '13';
                        $updateArray['from_wkout']  = '3';
                    } elseif ($start_journal == 'startsample') {
                        $activity_feed["feed_type"] = '15';
                        $updateArray['from_wkout']  = '2';
                    } elseif ($start_journal == 'startshare') {
                        $activity_feed["feed_type"] = '12';
                        $updateArray['from_wkout']  = '1';
                    } elseif ($start_journal == 'startwklog') {
                        $activity_feed["feed_type"] = '11';
                        $updateArray['from_wkout']  = '4';
                    }
                    $workassignid                  = $workoutModel->addToWkoutAssignCustom($updateArray, $this->globaluser->pk());
                    $activity_feed["action_type"]  = '22';
                    $activity_feed["type_id"]      = $updateArray['wkout_id'];
                    $activity_feed["json_data"]    = json_encode(array(
                        'wkoutassign' => $workassignid
                    ));
                    $activity_feed["context_date"] = Helper_Common::get_default_datetime($updateArray["assigned_date"]);
                    Helper_Common::createActivityFeed($activity_feed);
					/*** email -automation Start ***/
					$emailNotifyArray['wkout_assign_id'] = $workassignid;
					$emailNotifyArray['triggerby_date']  = Helper_Common::get_default_date($updateArray["assigned_date"]);
					$emailNotifyArray['triggerby_time']  = Helper_Common::getUserEmailTime();
					$workoutModel->insertEmailNotify($emailNotifyArray);
					/*** email -automation End ***/
                }
                if (isset($_POST['exercise_title']) && count($_POST['exercise_title']) > 0) {
                    foreach ($_POST['exercise_title'] as $keys => $values) {
                        if (!empty($values) && trim($values) != '' && isset($_POST['goal_remove'][$keys])) {
                            $goal_id                        = $keys;
                            $updateArray                    = array();
                            $updateArray['goal_order']      = (($_POST['goal_order'][$keys]) ? $_POST['goal_order'][$keys] : '0');
                            $updateArray['title']           = $_POST['exercise_title'][$keys];
                            $updateArray['goal_title_self'] = '1';
                            if (isset($_POST['exercise_unit'][$keys])){
								$exerciseUnitArray = explode('_',$_POST['exercise_unit'][$keys]);
								if(isset($exerciseUnitArray[1]) && is_numeric($exerciseUnitArray[1])){
									$updateArray['goal_title_self'] = 0;
									$updateArray['goal_unit_id']	= $exerciseUnitArray[1];
								}else{
									$updateArray['goal_unit_id']	= 0;
								}
							}
                            $updateArray['goal_resist']    = $_POST['exercise_resistance'][$keys];
                            $updateArray['goal_resist_id'] = $_POST['exercise_unit_resistance'][$keys];
                            $updateArray['goal_reps']      = $_POST['exercise_repetitions'][$keys];
                            if ($_POST['exercise_time'][$keys]) {
                                $timeArray                   = explode(":", $_POST['exercise_time'][$keys]);
                                $updateArray['goal_time_hh'] = $timeArray['0'];
                                $updateArray['goal_time_mm'] = $timeArray['1'];
                                $updateArray['goal_time_ss'] = $timeArray['2'];
                            }
                            $updateArray['goal_dist']     = $_POST['exercise_distance'][$keys];
                            $updateArray['goal_dist_id']  = $_POST['exercise_unit_distance'][$keys];
                            $updateArray['goal_rate']     = $_POST['exercise_rate'][$keys];
                            $updateArray['goal_rate_id']  = $_POST['exercise_unit_rate'][$keys];
                            $updateArray['goal_int_id']   = $_POST['exercise_innerdrive'][$keys];
                            $updateArray['goal_angle']    = $_POST['exercise_angle'][$keys];
                            $updateArray['goal_angle_id'] = $_POST['exercise_unit_angle'][$keys];
                            if ($_POST['exercise_rest'][$keys]) {
                                $resttimeArray               = explode(":", $_POST['exercise_rest'][$keys]);
                                $updateArray['goal_rest_mm'] = $resttimeArray['0'];
                                $updateArray['goal_rest_ss'] = $resttimeArray['1'];
                            }
                            $goal_remarks                  = $_POST['exercise_remark'][$keys];
                            $updateArray['goal_remarks']   = (!empty($goal_remarks) ? strip_tags($goal_remarks) : '');
                            $updateArray['primary_time']   = (($_POST['primary_time'][$keys]) ? $_POST['primary_time'][$keys] : '0');
                            $updateArray['primary_dist']   = (($_POST['primary_dist'][$keys]) ? $_POST['primary_dist'][$keys] : '0');
                            $updateArray['primary_reps']   = (($_POST['primary_reps'][$keys]) ? $_POST['primary_reps'][$keys] : '0');
                            $updateArray['primary_resist'] = (($_POST['primary_resist'][$keys]) ? $_POST['primary_resist'][$keys] : '0');
                            $updateArray['primary_rate']   = (($_POST['primary_rate'][$keys]) ? $_POST['primary_rate'][$keys] : '0');
                            $updateArray['primary_angle']  = (($_POST['primary_angle'][$keys]) ? $_POST['primary_angle'][$keys] : '0');
                            $updateArray['primary_rest']   = (($_POST['primary_rest'][$keys]) ? $_POST['primary_rest'][$keys] : '0');
                            $updateArray['primary_int']    = (($_POST['primary_int'][$keys]) ? $_POST['primary_int'][$keys] : '0');
							if(is_numeric($keys) && empty($start_journal)){
								$workoutModel->updateAssignExerciseSet($updateArray, $goal_id);
							}else{
								$updateArray['goal_title'] = $updateArray['title'];
                                $updateArray['wkout_id']   = $this->request->post('wkout_id');
                                $res                       = $workoutModel->addAssignWorkoutSetFromExistworkout($updateArray, $this->globaluser->pk(), $workassignid);
								$_POST['goal_order'][$res] = $_POST['goal_order'][$keys];
								$_POST['goal_remove'][$res]= $_POST['goal_remove'][$keys];
								unset($_POST['goal_order'][$keys]);
								unset($_POST['goal_remove'][$keys]);
							}
                        }
                    }
                }
                if (isset($_POST['goal_order']) && count($_POST['goal_order']) > 0) {
                    foreach ($_POST['goal_order'] as $keys => $values) {
                        $workoutModel->updateAssignedGoalOrder($values, $workassignid, $keys, $this->globaluser->pk());
                    }
                }
                if (isset($_POST['goal_remove']) && count($_POST['goal_remove']) > 0) {
                    foreach ($_POST['goal_remove'] as $keys => $values) {
                        if ($values != '0')
                            $workoutModel->doDeleteForExerciseSetsById('AssignexerciseSet', $keys, $workassignid, $this->globaluser->pk());
                    }
                }
                if (!empty($start_journal))
                    $this->session->set('success', 'Assigned Plan Created Successfully!!!');
                else
                    $this->session->set('success', 'Edited Assigned Plans Successfully!!!');
            }
			if ($save_edit == 1){
				$this->session->get_once('old_wkout_assign_no');
				$this->session->get_once('old_preview_wkout_assign_no');
				$this->redirect('exercise/assignedplan/' . $workassignid . '/?act=edit');
			}else{
				$this->session->get_once('old_wkout_assign_no');
				$this->session->get_once('old_preview_wkout_assign_no');
				$this->redirect('exercise/myactionplans/' . $date);
			}
        }
        if (!empty($workassignid) && !is_numeric($workassignid)) {
            $this->template->content->startJournal = $workassignid;
            if ($workassignid == 'startwkout') {
                $this->template->content->workoutRecord  = $workoutModel->getworkoutById($this->globaluser->pk(), $exerciseid);
				$exerciseRecord = $workoutModel->getExerciseSets('wkout', $exerciseid);
				$this->template->content->exerciseRecord = Helper_Common::exerciseSetsCombine($exerciseRecord,'wkout_id');
                $this->template->content->wkout_id       = $this->template->content->workoutRecord['wkout_id'];
            } elseif ($workassignid == 'startassign') {
                $this->template->content->workoutRecord  = $workoutModel->getAssignworkoutById($exerciseid, $this->globaluser->pk());
				$exerciseRecord = $workoutModel->getExerciseSets('assigned', $exerciseid);
				$this->template->content->exerciseRecord = Helper_Common::exerciseSetsCombine($exerciseRecord,'wkout_assign_id');
                $this->template->content->wkout_id       = $this->template->content->workoutRecord['wkout_assign_id'];
            } elseif ($workassignid == 'startsample') {
                $this->template->content->workoutRecord  = $workoutModel->getSampleworkoutById('0', $exerciseid);
				$exerciseRecord = $workoutModel->getSampleExerciseSet($exerciseid);
				$this->template->content->exerciseRecord = Helper_Common::exerciseSetsCombine($exerciseRecord,'wkout_sample_id');
                $this->template->content->wkout_id       = $this->template->content->workoutRecord['wkout_sample_id'];
            } elseif ($workassignid == 'startshare') {
                $this->template->content->workoutRecord  = $workoutModel->getShareworkoutById($this->globaluser->pk(), $exerciseid);
				$exerciseRecord = $workoutModel->getExerciseSets('shared', $exerciseid);
				$this->template->content->exerciseRecord = Helper_Common::exerciseSetsCombine($exerciseRecord,'wkout_share_id');
                $this->template->content->wkout_id       = $this->template->content->workoutRecord['wkout_share_id'];
            } elseif ($workassignid == 'startwklog') {
                $this->template->content->workoutRecord  = $workoutModel->getLoggedworkoutById($exerciseid);
				$exerciseRecord = $workoutModel->getExerciseSets('wkoutlog', $exerciseid);
				$this->template->content->exerciseRecord = Helper_Common::exerciseSetsCombine($exerciseRecord,'wkout_log_id');
                $this->template->content->wkout_id       = $this->template->content->workoutRecord['wkout_log_id'];
            }
            if (empty($this->template->content->workoutRecord))
                $this->redirect('exercise/myactionplans/' . $date);
            $this->template->content->focusRecord = $workoutModel->getAllFocus();
            $workassignid                         = $exerciseid;
        } else if (!empty($workassignid) && is_numeric($workassignid)) {
            $this->template->content->workoutRecord = $workoutModel->getAssignworkoutById($workassignid, $this->globaluser->pk());
            if ($workassignid != $this->session->get('old_wkout_assign_no') && !empty($_GET['act'])) {
                $this->session->set('old_wkout_assign_no', $workassignid);
                $activity_feed["feed_type"]    = '13';
                $activity_feed["action_type"]  = '15';
                $activity_feed["type_id"]      = $workassignid;
                $activity_feed["json_data"]    = json_encode('edit-mode');
                $activity_feed["context_date"] = Helper_Common::get_default_datetime($this->template->content->workoutRecord['assigned_date']);
                Helper_Common::createActivityFeed($activity_feed);
            } elseif ($workassignid != $this->session->get('old_preview_wkout_assign_no') && empty($_GET['act'])) {
                $this->session->set('old_preview_wkout_assign_no', $workassignid);
                $activity_feed["feed_type"]    = '13';
                $activity_feed["action_type"]  = '42';
                $activity_feed["type_id"]      = $workassignid;
                $activity_feed["context_date"] = Helper_Common::get_default_datetime($this->template->content->workoutRecord['assigned_date']);
                Helper_Common::createActivityFeed($activity_feed);
            }
            $this->template->content->exerciseSetId = '';
            if (empty($this->template->content->workoutRecord))
                $this->redirect('exercise/myactionplans/' . $date);
            $this->template->content->focusRecord    = $workoutModel->getAllFocus();
			$exerciseRecord = $workoutModel->getExerciseSets('assign', $workassignid);
			$this->template->content->exerciseRecord = Helper_Common::exerciseSetsCombine($exerciseRecord,'wkout_assign_id');
            $this->template->content->wkout_id       = $this->template->content->workoutRecord['wkout_id'];
        } else
            $this->redirect('exercise/myactionplans/' . $date);
        $this->template->content->popupAct        = $popupAct;
        $this->template->content->dateValue       = (!isset($this->template->content->startJournal) && isset($this->template->content->workoutRecord['assigned_date']) ? $this->template->content->workoutRecord['assigned_date'] : $date);
        $this->template->content->workoutassignId = trim($workassignid);
        $this->template->content->save            = (!empty($_GET['p']) ? $_GET['p'] : '');
        $this->template->content->userId          = $this->globaluser->pk();
        $this->template->content->popupEdit       = (!empty($_GET['edit']) ? $_GET['edit'] : '');
        $this->template->content->colorsRecord    = $workoutModel->getColors();
    }
    public function action_workoutlog()
    {
        $this->template->title = 'Workout Log';
        $workoutModel          = ORM::factory('workouts');
        $worklogid             = urldecode($this->request->param('id'));
        $site_id               = ($this->session->get('current_site_id') ? $this->session->get('current_site_id') : '0');
        $exerciseid            = urldecode($this->request->param('eid'));
        $popupAct              = (!empty($_GET['act']) ? $_GET['act'] : '');
        $datevalue             = Helper_Common::get_default_datetime();
        $date                  = (!empty($_GET['date']) ? $_GET['date'] : '');
        $todayDate             = Helper_Common::get_default_date();
        $datediff              = strtotime($date) - strtotime($todayDate);
        $difference            = floor($datediff / (60 * 60 * 24));
        if ($difference > 0)
            $date = Helper_Common::get_default_date();
        else
            $date = Helper_Common::get_default_date($date . ' 00:00:00');
        /** activity feed starts **/
        $activity_feed            = array();
        $save_edit                = 0;
        $activity_feed["user"]    = $this->globaluser->pk();
        $activity_feed["site_id"] = $site_id;
        /** activity feed ends **/
        if (empty($worklogid))
            $this->redirect('exercise/myactionplans/' . $date);
        $this->render();
        if (HTTP_Request::POST == $this->request->method()) {
            $save_edit                = $this->request->post('save_edit');
            $method                   = $this->request->post('f_method');
            $start_journal            = $this->request->post('start_journal');
            $activity_feed["type_id"] = $activity_feed["action_type"] = $activity_feed["json_data"] = '';
            if ($save_edit == 2 || $save_edit == 5) {
				if(empty($start_journal)){
					$activity_feed["feed_type"]    = '11';
					$activity_feed["action_type"]  = '44';
					$activity_feed["type_id"]      = $worklogid;
					$activity_feed["json_data"]    = json_encode('without saving');
					$activity_feed["context_date"] = Helper_Common::get_default_datetime($this->request->post('selected_date_hidden'));
					Helper_Common::createActivityFeed($activity_feed);
				}
				$this->session->get_once('old_wkout_log_no');
				$this->session->get_once('old_preview_wkout_log_no');
				if($save_edit == 2){
					if (empty($start_journal))
						$this->redirect('exercise/workoutlog/' . $worklogid . '?act=edit');
					else
						$this->redirect('exercise/workoutlog/' . $start_journal . '/' . $exerciseid . '/?act=edit');
				}else{
					$this->redirect('exercise/myactionplans/'.date('Y-m-d',strtotime($this->request->post('selected_date_hidden').' 00:00:00')));
				}
            }
            if ($method == 'add_rating') {
                $rating['unit_id']            = $this->request->post('unit_id');
                $rating['rate_value']         = $this->request->post('slider-1');
                $rating['rate_comments']      = $this->request->post('rating_msg');
                $rating['created_date']       = $rating['modified_date'] = $datevalue;
                $rateId                       = $workoutModel->insertRatingDetails($rating, $this->globaluser->pk());
                $activity_feed["feed_type"]   = '5';
                $activity_feed["action_type"] = '25';
                $activity_feed["type_id"]     = $rating['unit_id'];
                Helper_Common::createActivityFeed($activity_feed);
                $this->session->set('success', 'Rating created successfully!!!');
            } elseif ($method == 'add_new_wkout_from_log') {
                $wkoutId                      = $workoutModel->doCopyForExerciseSetsById('workoutLogToNewWkout', $this->globaluser->pk(), '0', $worklogid, 'copy');
                $activity_feed["feed_type"]   = '2';
                $activity_feed["action_type"] = '1';
                $activity_feed["type_id"]     = $wkoutId;
                Helper_Common::createActivityFeed($activity_feed);
                $this->session->set('success', 'Successfully Added as New Workout!!!');
            } elseif ($method == "delete") {
                $assignedArray['modified']  = $datevalue;
                $assignedArray['status_id'] = '4'; // deleted
                $workoutModel->updateLoggedWkoutDetails($assignedArray, $worklogid);
                $activity_feed["feed_type"]    = '11';
                $activity_feed["action_type"]  = '23';
                $activity_feed["type_id"]      = $worklogid;
                $activity_feed["context_date"] = Helper_Common::get_default_datetime($this->request->post('selected_date_hidden'));
                Helper_Common::createActivityFeed($activity_feed);
                $this->session->set('success', 'Successfully deleted from Journal!!!');
                $this->redirect('exercise/myactionplans/' . $todayDate);
            } elseif ($method == 'add_new_log_start' || $method == 'add_new_log_end') {
                $loggArray['note_wkout_intensity'] = $this->request->post('slider-1');
                $loggArray['note_wkout_remarks']   = $this->request->post('note_wkout_remarks');
                $loggArray['created']              = $loggArray['modified'] = $datevalue;
                $loggArray['wkout_id']             = $worklogid;
                $loggArray['from_wkout']           = '4';
                $loggArray['assigned_date']        = Helper_Common::get_default_date($this->request->post('selected_date_hidden'));
                $loggArray['modified_by']          = $this->globaluser->pk();
                $wkoutLog                          = $workoutModel->createWkoutLogBywkoutId('wkoutlog', $worklogid, $loggArray, $this->globaluser->pk());
                $activity_feed["feed_type"]        = '11';
                $activity_feed["action_type"]      = '22';
                $activity_feed["type_id"]          = $wkoutLog;
                $activity_feed["json_data"]        = json_encode(' on ' . $loggArray['assigned_date']);
                $activity_feed["context_date"]     = Helper_Common::get_default_datetime($this->request->post('selected_date_hidden'));
                Helper_Common::createActivityFeed($activity_feed);
                $this->session->set('success', 'Successfully Duplicated to New Journal!!');
            } elseif ($method == 'assignAdd') {
                $assignArr['wkout_log_id']     = $worklogid;
                $assignArr['modified_by']      = $assignArr['assigned_by'] = $assignArr['assigned_for'] = $this->globaluser->pk();
                $assignArr['assigned_date']    = Helper_Common::get_default_date($this->request->post('selected_date'));
                $assignArr['created']          = $assignArr['modified'] = $datevalue;
                $assignArr['from_wkout']       = '4';
                $assignId                      = $workoutModel->addToWkoutAssign($assignArr, $this->globaluser->pk());
                $activity_feed["feed_type"]    = '13';
                $activity_feed["action_type"]  = '24';
                $activity_feed["type_id"]      = $assignArr['wkout_log_id'];
                $activity_feed["context_date"] = Helper_Common::get_default_datetime($assignArr['assigned_date']);
                $activity_feed["json_data"]    = json_encode(array(
                    'assigned' => $assignId
                ));
                Helper_Common::createActivityFeed($activity_feed);
				/*** email -automation Start ***/
				$emailNotifyArray['wkout_assign_id'] = $assignId;
				$emailNotifyArray['triggerby_date']  = Helper_Common::get_default_date($assignArr['assigned_date']);
				$emailNotifyArray['triggerby_time']  = Helper_Common::getUserEmailTime();
				$workoutModel->insertEmailNotify($emailNotifyArray);
				/*** email -automation End ***/
                $this->session->set('success', 'Journal Entry was Assigned Successfully!!!');
            } elseif ($method == 'add_tag_to_xr') {
                $add_tag['unit_id']   = $this->request->post('unit_id');
                $add_tag['tag-input'] = $this->request->post('xrtag-input');
                $workoutModel->insertUnitTagById($add_tag['tag-input'], $add_tag['unit_id']);
                $this->session->set('success', 'Tagged Successfully!!!');
            } elseif (isset($_POST['wrkoutname_hidden']) && !empty($_POST['wrkoutname_hidden']) && !empty($worklogid) && $method == '') {
                $countTotal                          = $this->request->post('s_row_count');
                $updateArray['wkout_title']          = $this->request->post('wrkoutname_hidden');
                $updateArray['wkout_color']          = $this->request->post('wrkoutcolor');
                $updateArray['wkout_focus']          = $this->request->post('wkout_focus');
                $updateArray['assigned_date']        = $logDate = Helper_Common::get_default_date($this->request->post('selected_date_hidden'));
                $updateArray['note_wkout_intensity'] = $this->request->post('per_intent_hidden');
                $updateArray['note_wkout_remarks']   = $this->request->post('per_remarks_hidden');
                $updateArray['modified']             = $datevalue;
                $updateArray['site_id']              = $site_id;
                $activity_feed["context_date"]       = Helper_Common::get_default_datetime($this->request->post('selected_date_hidden'));
                if (empty($start_journal)) {
                    $activity_feed["feed_type"] = '11';
                    $workoutModel->updateLoggedWkoutDetails($updateArray, $worklogid);
                    $activity_feed["action_type"] = '26';
                    $activity_feed["type_id"]     = $worklogid;
                    Helper_Common::createActivityFeed($activity_feed);
                } else {
                    $updateArray['created']     = $datevalue;
                    $updateArray['modified_by'] = $this->globaluser->pk();
                    $updateArray['wkout_id']    = $exerciseid;
                    if ($start_journal == 'startwkout') {
                        $activity_feed["feed_type"] = '2';
                        $updateArray['from_wkout']  = '0';
                    } elseif ($start_journal == 'startassign') {
                        $activity_feed["feed_type"] = '13';
                        $updateArray['from_wkout']  = '3';
                    } elseif ($start_journal == 'startsample') {
                        $activity_feed["feed_type"] = '15';
                        $updateArray['from_wkout']  = '2';
                    } elseif ($start_journal == 'startshare') {
                        $activity_feed["feed_type"] = '12';
                        $updateArray['from_wkout']  = '1';
                    } elseif ($start_journal == 'startwklog') {
                        $activity_feed["feed_type"] = '11';
                        $updateArray['from_wkout']  = '4';
                    }
                    $worklogid                    = $workoutModel->createWkoutLogByCustom($updateArray, $this->globaluser->pk());
                    $activity_feed["action_type"] = '22';
                    $activity_feed["json_data"]   = json_encode(array(
                        'wkoutlog' => $worklogid
                    ));
                    $activity_feed["type_id"]     = $exerciseid;
                    Helper_Common::createActivityFeed($activity_feed);
                }
                $countval = $countvalskip = 0;
                if (isset($_POST['exercise_title']) && count($_POST['exercise_title']) > 0) {
                    foreach ($_POST['exercise_title'] as $keys => $values) {
                        if (!empty($values) && trim($values) != '' && isset($_POST['goal_remove'][$keys])) {
                            $goal_id                        = $keys;
                            $updateArray                    = array();
                            $updateArray['goal_order']      = (($_POST['goal_order'][$keys]) ? $_POST['goal_order'][$keys] : '0');
                            $updateArray['title']           = $_POST['exercise_title'][$keys];
                            $updateArray['goal_title_self'] = '1';
							$updateArray['goal_unit_id']   	= 0;
                            if (isset($_POST['exercise_unit'][$keys])){
								$exerciseUnitArray = explode('_',$_POST['exercise_unit'][$keys]);
								if(isset($exerciseUnitArray[1]) && is_numeric($exerciseUnitArray[1])){
									$updateArray['goal_title_self'] = 0;
									$updateArray['goal_unit_id']	= $exerciseUnitArray[1];
								}else{
									$updateArray['goal_unit_id']	= 0;
								}
							}
                            $updateArray['goal_resist']    = $_POST['exercise_resistance'][$keys];
                            $updateArray['goal_resist_id'] = $_POST['exercise_unit_resistance'][$keys];
                            $updateArray['goal_reps']      = $_POST['exercise_repetitions'][$keys];
                            if ($_POST['exercise_time'][$keys]) {
                                $timeArray                   = explode(":", $_POST['exercise_time'][$keys]);
                                $updateArray['goal_time_hh'] = $timeArray['0'];
                                $updateArray['goal_time_mm'] = $timeArray['1'];
                                $updateArray['goal_time_ss'] = $timeArray['2'];
                            }
                            $updateArray['goal_dist']     = $_POST['exercise_distance'][$keys];
                            $updateArray['goal_dist_id']  = $_POST['exercise_unit_distance'][$keys];
                            $updateArray['goal_rate']     = $_POST['exercise_rate'][$keys];
                            $updateArray['goal_rate_id']  = $_POST['exercise_unit_rate'][$keys];
                            $updateArray['goal_int_id']   = $_POST['exercise_innerdrive'][$keys];
                            $updateArray['goal_angle']    = $_POST['exercise_angle'][$keys];
                            $updateArray['goal_angle_id'] = $_POST['exercise_unit_angle'][$keys];
                            if ($_POST['exercise_rest'][$keys]) {
                                $resttimeArray               = explode(":", $_POST['exercise_rest'][$keys]);
                                $updateArray['goal_rest_mm'] = $resttimeArray['0'];
                                $updateArray['goal_rest_ss'] = $resttimeArray['1'];
                            }
                            $goal_remarks                  = $_POST['exercise_remark'][$keys];
                            $updateArray['goal_remarks']   = (!empty($goal_remarks) ? strip_tags($goal_remarks) : '');
                            $updateArray['primary_time']   = (($_POST['primary_time'][$keys]) ? $_POST['primary_time'][$keys] : '0');
                            $updateArray['primary_dist']   = (($_POST['primary_dist'][$keys]) ? $_POST['primary_dist'][$keys] : '0');
                            $updateArray['primary_reps']   = (($_POST['primary_reps'][$keys]) ? $_POST['primary_reps'][$keys] : '0');
                            $updateArray['primary_resist'] = (($_POST['primary_resist'][$keys]) ? $_POST['primary_resist'][$keys] : '0');
                            $updateArray['primary_rate']   = (($_POST['primary_rate'][$keys]) ? $_POST['primary_rate'][$keys] : '0');
                            $updateArray['primary_angle']  = (($_POST['primary_angle'][$keys]) ? $_POST['primary_angle'][$keys] : '0');
                            $updateArray['primary_rest']   = (($_POST['primary_rest'][$keys]) ? $_POST['primary_rest'][$keys] : '0');
                            $updateArray['primary_int']    = (($_POST['primary_int'][$keys]) ? $_POST['primary_int'][$keys] : '0');
                            $updateArray['set_status']     = (($_POST['markedstatus'][$keys] && !empty($_POST['markedstatus'][$keys])) ? $_POST['markedstatus'][$keys] : '0');
                            if (isset($updateArray['set_status']) && ($updateArray['set_status'] == '1'))
                                $countval += 1;
                            if (isset($updateArray['set_status']) && ($updateArray['set_status'] == '2'))
                                $countvalskip += 1;
                            $updateArray['note_set_intensity'] = (($_POST['per_intent'][$keys]) ? $_POST['per_intent'][$keys] : '0');
                            $updateArray['note_set_remarks']   = (($_POST['per_remarks'][$keys]) ? $_POST['per_remarks'][$keys] : '0');
							if(is_numeric($keys) && empty($start_journal)){
								$workoutModel->updateLogExerciseSet($updateArray, $goal_id);
							}else{
								$updateArray['goal_title'] = $updateArray['title'];
                                $res                       = $workoutModel->addLoggedWorkoutSetFromExist($updateArray, $this->globaluser->pk(), $worklogid);
								$_POST['goal_order'][$res] = $_POST['goal_order'][$keys];
								$_POST['goal_remove'][$res]= $_POST['goal_remove'][$keys];
								unset($_POST['goal_order'][$keys]);
								unset($_POST['goal_remove'][$keys]);
							}
                        }
                    }
                }
                $loggedArray = $logxrArray = array();
                if ($save_edit == '3') {
                    $loggedArray['wkout_status'] = '1'; // 1 -completed
                    $logxrArray['set_status']    = '1';
                    $workoutModel->updateLoggedWkoutDetails($loggedArray, $worklogid);
                    $workoutModel->updateLoggedWkoutXRDetails($logxrArray, $worklogid);
                    $save_edit = '0';
                } else if ($save_edit == '4') {
                    $loggedArray['wkout_status'] = '2'; // 1 -skipped
                    $logxrArray['set_status']    = '2';
                    $workoutModel->updateLoggedWkoutDetails($loggedArray, $worklogid);
                    $workoutModel->updateLoggedWkoutXRDetails($logxrArray, $worklogid);
                    $save_edit = '0';
                } else {
                    if ($countTotal == $countval || $countTotal == ($countval +$countvalskip))
                        $workoutModel->updateLoggedWkoutDetails(array(
                            'wkout_status' => '1'
                        ), $worklogid);
                    elseif ($countTotal == $countvalskip)
                        $workoutModel->updateLoggedWkoutDetails(array(
                            'wkout_status' => '2'
                        ), $worklogid);
                    elseif ($countTotal > ($countval + $countvalskip)) {
                        $workoutModel->updateLoggedWkoutDetails(array(
                            'wkout_status' => '3'
                        ), $worklogid);
                    }
                }
                if (isset($_POST['goal_order']) && count($_POST['goal_order']) > 0) {
                    foreach ($_POST['goal_order'] as $keys => $values) {
                        $workoutModel->updateLogGoalOrder($values, $worklogid, $keys, $this->globaluser->pk());
                    }
                }
                if (isset($_POST['goal_remove']) && count($_POST['goal_remove']) > 0) {
                    foreach ($_POST['goal_remove'] as $keys => $values) {
                        if ($values != '0')
                            $workoutModel->doDeleteForExerciseSetsById('LogexerciseSet', $keys, $worklogid, $this->globaluser->pk());
                    }
                }
                if (!empty($start_journal)) {
                    $this->session->set('success', 'Created Workout Log Successfully!!!');
                } else {
                    $this->session->set('success', 'Edited Workout Log Successfully!!!');
                }
            }
			if ($save_edit == '0'){
				$this->session->get_once('old_wkout_log_no');
				$this->session->get_once('old_preview_wkout_log_no');
				$this->redirect('exercise/myactionplans/' . $logDate);
			}elseif ($save_edit == 1) {
				$this->session->get_once('old_wkout_log_no');
				$this->session->get_once('old_preview_wkout_log_no');
				if (!empty($worklogid) && is_numeric($worklogid))
					$this->redirect('exercise/workoutlog/' . $worklogid . '?act=edit');
				else
					$this->redirect('exercise/myactionplans/' . $todayDate);
			}
        }
        if (!empty($worklogid) && !is_numeric($worklogid)) {
            $this->template->content->startJournal = $worklogid;
            if ($worklogid == 'startwkout') {
                $this->template->content->workoutRecord  = $workoutModel->getworkoutById($this->globaluser->pk(), $exerciseid);
				$exerciseRecord = $workoutModel->getExerciseSets('wkout', $exerciseid);
				$this->template->content->exerciseRecord = Helper_Common::exerciseSetsCombine($exerciseRecord,'wkout_id');
                $this->template->content->wkout_id       = $this->template->content->workoutRecord['wkout_id'];
            } elseif ($worklogid == 'startassign') {
                $this->template->content->workoutRecord  = $workoutModel->getAssignworkoutById($exerciseid, $this->globaluser->pk());
				$exerciseRecord = $workoutModel->getExerciseSets('assigned', $exerciseid);
				$this->template->content->exerciseRecord = Helper_Common::exerciseSetsCombine($exerciseRecord,'wkout_assign_id');
                $this->template->content->wkout_id       = $this->template->content->workoutRecord['wkout_assign_id'];
            } elseif ($worklogid == 'startsample') {
                $this->template->content->workoutRecord  = $workoutModel->getSampleworkoutById('0', $exerciseid);
				$exerciseRecord = $workoutModel->getSampleExerciseSet($exerciseid);
				$this->template->content->exerciseRecord = Helper_Common::exerciseSetsCombine($exerciseRecord,'wkout_sample_id');
                $this->template->content->wkout_id       = $this->template->content->workoutRecord['wkout_sample_id'];
            } elseif ($worklogid == 'startshare') {
                $this->template->content->workoutRecord  = $workoutModel->getShareworkoutById($this->globaluser->pk(), $exerciseid);
				$exerciseRecord = $workoutModel->getExerciseSets('shared', $exerciseid);
				$this->template->content->exerciseRecord = Helper_Common::exerciseSetsCombine($exerciseRecord,'wkout_share_id');
                $this->template->content->wkout_id       = $this->template->content->workoutRecord['wkout_share_id'];
            } elseif ($worklogid == 'startwklog') {
                $this->template->content->workoutRecord  = $workoutModel->getLoggedworkoutById($exerciseid);
				$exerciseRecord = $workoutModel->getExerciseSets('wkoutlog', $exerciseid);
				$this->template->content->exerciseRecord = Helper_Common::exerciseSetsCombine($exerciseRecord,'wkout_log_id');
                $this->template->content->wkout_id       = $this->template->content->workoutRecord['wkout_log_id'];
            }
            if (empty($this->template->content->workoutRecord))
                $this->redirect('exercise/myactionplans/' . $date);
            $this->template->content->focusRecord = $workoutModel->getAllFocus();
            $worklogid                            = $exerciseid;
        } else if (!empty($worklogid) && is_numeric($worklogid)) {
            $this->template->content->workoutRecord = $workoutModel->getLoggedworkoutById($worklogid);
            if ($worklogid != $this->session->get('old_wkout_log_no') && !empty($_GET['act'])) {
                $this->session->set('old_wkout_log_no', $worklogid);
                $activity_feed["feed_type"]    = '11';
                $activity_feed["action_type"]  = '15';
                $activity_feed["type_id"]      = $worklogid;
                $activity_feed["json_data"]    = json_encode('edit-mode');
                $activity_feed["context_date"] = Helper_Common::get_default_datetime($this->template->content->workoutRecord['assigned_date']);
                Helper_Common::createActivityFeed($activity_feed);
            } elseif ($worklogid != $this->session->get('old_preview_wkout_log_no') && empty($_GET['act'])) {
                $this->session->set('old_preview_wkout_log_no', $worklogid);
                $activity_feed["feed_type"]    = '11';
                $activity_feed["action_type"]  = '42';
                $activity_feed["type_id"]      = $worklogid;
                $activity_feed["context_date"] = Helper_Common::get_default_datetime($this->template->content->workoutRecord['assigned_date']);
                Helper_Common::createActivityFeed($activity_feed);
            }
            if (empty($this->template->content->workoutRecord))
                $this->redirect('exercise/myactionplans/' . $date);
            $this->template->content->focusRecord    = $workoutModel->getAllFocus();
			$exerciseRecord = $workoutModel->getExerciseSets('wkoutlog', $worklogid);
			$this->template->content->exerciseRecord = Helper_Common::exerciseSetsCombine($exerciseRecord,'wkout_log_id');
            $this->template->content->wkout_id       = $this->template->content->workoutRecord['wkout_log_id'];
        } else
            $this->redirect('exercise/myactionplans/' . $date);
        $this->template->content->popupAct     = $popupAct;
        $this->template->content->dateValue    = (!isset($this->template->content->startJournal) && isset($this->template->content->workoutRecord['assigned_date']) ? $this->template->content->workoutRecord['assigned_date'] : $date);
        $this->template->content->workoutlogid = trim($worklogid);
        $this->template->content->save         = (!empty($_GET['p']) ? $_GET['p'] : '');
        $this->template->content->userId       = $this->globaluser->pk();
        $this->template->content->popupEdit    = (!empty($_GET['edit']) ? $_GET['edit'] : '');
        $this->template->content->colorsRecord = $workoutModel->getColors();
    }
    public function action_exerciselibrary()
    {
        $this->template->title = 'Exercise Library';
        $user_id               = Auth::instance()->get_user()->pk();
		// For Beginner
		if(Auth::instance()->get_user()->user_profile ==1)
			$this->redirect('dashboard/index/');
		
        $site_id               = ($this->session->get('current_site_id') ? $this->session->get('current_site_id') : '0');
        $workoutModel          = ORM::factory('workouts');
        $Urlparam              = urldecode(Request::current()->param('id'));
        $this->render();
        if (HTTP_Request::POST == $this->request->method()) {
            $exerciseid = urldecode($this->request->post('xrid'));
            $method     = $this->request->post('f_method');
            $submitfrom = $this->request->post('submitfrom');
            // echo '<pre>';print_r($_POST);exit;
            if (!empty($method) && isset($exerciseid)) {
                if ($method == 'copy') {
                    $resdata = $workoutModel->CopyDeleteExerciseRecById('exerciseRecord', $exerciseid, $method);
                    if ($resdata['success']) {
                        $this->session->set('success', 'Exercise record copied successfully!!!');
                    } else {
                        $this->session->set('error', 'Error occurred while copy!!!');
                    }
                } elseif ($method == 'delete') {
                    $resdata = $workoutModel->CopyDeleteExerciseRecById('exerciseRecord', $exerciseid, $method);
                    if ($resdata['success']) {
                        $this->session->set('success', 'Exercise record deleted successfully!!!');
                    } else {
                        $this->session->set('error', 'Error occurred while delete!!!');
                    }
                } elseif ($method == 'xr-tagging') {
                    if (isset($_POST['xrtag-input']) && (isset($_POST['xrunitid']) && !empty($_POST['xrunitid']))) {
                        if ($workoutModel->insertUnitTagById($_POST['xrtag-input'], $_POST['xrunitid']) === 'no-tag') {
                            $this->session->set('error', 'No tags inserted for this exercise!!!');
                        } elseif ($workoutModel->insertUnitTagById($_POST['xrtag-input'], $_POST['xrunitid'])) {
                            $this->session->set('success', 'Exercise record tagged Successfully!!!');
                        } else {
                            $this->session->set('error', 'Error occurred while tagging!!!');
                        }
                    }
                } elseif ($method == 'add_rating') {
                    $rating['unit_id']       = $this->request->post('unit_id');
                    $rating['rate_value']    = $this->request->post('slider-1');
                    $rating['rate_comments'] = $this->request->post('rating_msg');
                    $rating['created_date']  = $rating['modified_date'] = Helper_Common::get_default_datetime();
                    $rateId                  = $workoutModel->insertRatingDetails($rating, $user_id);
                    if (is_int($rateId)) {
                        $workoutModel->insertActivityFeed(5, 25, $rating['unit_id']);
                        $this->session->set('success', 'Exercise record rated successfully!!!');
                    } else {
                        $this->session->set('error', 'Error occured while rating exercise record!!!');
                    }
                } elseif ($method == 'share_exercise') {
                    $sharemsg             = false;
                    $unit_id              = $this->request->post('xr_share_id');
                    $shared_msg           = $this->request->post('xr_share_msg');
                    $selectedUser         = $this->request->post('seletedUser');
                    $selectedSite         = $this->request->post('seletedSite');
                    $shareArr['user_ids'] = $shareArr['site_ids'] = '';
                    if (isset($selectedSite[0])) {
                        $shareArr['site_ids'] = explode(',', $selectedSite[0]);
                    }
                    if (isset($selectedUser[0])) {
                        $shareArr['user_ids'] = explode(',', $selectedUser[0]);
                    }
                    foreach ($shareArr['site_ids'] as $key => $siteid) {
                        foreach ($shareArr['user_ids'] as $keys => $userid) {
                            $allsites = Helper_Common::getAllSiteIdByUser($userid);
                            if (in_array($siteid, $allsites)) {
                                $exerciseShareId = $workoutModel->CopyDeleteExerciseRecById('exerciseRecord', $unit_id, 'share', array(
                                    'shared_by' => $user_id,
                                    'shared_for' => $userid,
                                    'shared_msg' => $shared_msg,
                                    'site_id' => $siteid
                                ));
                                $sharingact      = $this->_sendExerciseShareEmailToUser($exerciseShareId['shared_xrid'], $siteid, $userid, $unit_id);
                                if ($sharingact) {
                                    $sharemsg = true;
                                }
                            }
                        }
                    }
                    if ($sharemsg) {
                        $this->session->set('success', 'Exercise record shared successfully!!!');
                    } else {
                        $this->session->set('error', 'Error occured while sharing exercise record!!!');
                    }
                }
            }
            if (!empty($method)) {
                if ($submitfrom == '1' || $submitfrom == '2') {
                    $this->redirect('exercise/exerciselibrary/sampleexercise');
                } elseif ($submitfrom == '3') {
                    $this->redirect('exercise/exerciselibrary/sharedexercise');
                } else {
                    $this->redirect('exercise/exerciselibrary/myexercise');
                }
            } else {
                $this->redirect('exercise/exerciselibrary');
            }
        }
        $this->template->content->myxrcisecnt     = $workoutModel->getExerciseCountByFolder(0);
        $this->template->content->samplexrcisecnt = $workoutModel->getExerciseCountByFolder(2);
        $this->template->content->sharedxrcisecnt = $workoutModel->getExerciseCountByFolder(3);
        if ($Urlparam == 'sharedexercise' || $Urlparam == '3') {
            $sharedxrReadArr = $workoutModel->getSharedXrUnreadDetails($user_id);
            if (!empty($sharedxrReadArr)) {
                $replaceContent = $sharedxrReadArr['unitidsreplace'];
                $replaceArr     = explode('#', $sharedxrReadArr['unitidsreplace']);
                $appendArr      = explode('#', $sharedxrReadArr['unitids']);
                $appendResArr   = array_diff($appendArr, $replaceArr);
                $appendContent  = implode('#', $appendResArr);
                if (!empty($appendContent)) {
                    $insertArr['wkoutids']     = $replaceContent . $appendContent . '#';
                    $insertArr['xr_type']      = '1';
                    $insertArr['read_by']      = $user_id;
                    $insertArr['site_id']      = $site_id;
                    $insertArr['status_id']    = 1;
                    $insertArr['created_date'] = $insertArr['modified_date'] = Helper_Common::get_default_datetime();
                    $workoutModel->updateUnitReadStatus($replaceContent, $insertArr);
                }
            }
        }
        $sharedxrcntArray   = $workoutModel->getSharedXrUnreadCount($user_id);
        $sharedxrCntread    = (!empty($sharedxrcntArray['totalxrreadids']) ? explode('#', $sharedxrcntArray['totalxrreadids']) : array());
        $sharedxr_unreadcnt = (!empty($sharedxrcntArray['totalsharedxr']) ? $sharedxrcntArray['totalsharedxr'] : 0);
        if (count($sharedxrCntread) > 0) {
            $sharedxr_unreadcnt = ($sharedxrcntArray['totalsharedxr'] > (count($sharedxrCntread) - 2) ? $sharedxrcntArray['totalsharedxr'] - (count($sharedxrCntread) - 2) : (count($sharedxrCntread) - 2) - $sharedxrcntArray['totalsharedxr']);
        }
        $this->template->content->sharedunreadcnt = $sharedxr_unreadcnt;
    }
    public function action_exerciserecord()
    {
        $this->template->title = 'Exercise';
        $userid                = Auth::instance()->get_user()->pk();
        $site_id               = ($this->session->get('current_site_id') ? $this->session->get('current_site_id') : '0');
        $workoutModel          = ORM::factory('workouts');
        $this->render();
        $XrRecid     = urldecode($this->request->param('id'));
        $exerciseid  = urldecode($this->request->param('eid'));
        $getunitdata = $getmusothdata = $getequipothdata = $getseqdata = $gettaglist = array();
        $formtype    = '';
        $xrid        = '';
        $openfrom    = (!empty($_GET['act']) && !empty($_GET['act']) ? $_GET['act'] : 'indx');
        if (HTTP_Request::POST == $this->request->method()) {
            $XrRecid       = urldecode($this->request->post('xrid'));
            $method        = $this->request->post('f_method');
            $startExercise = $this->request->post('startExercise');
            // echo "<pre>"; print_r($_POST);exit;
            if (!empty($method)) {
                if (($method == 'save' || $method == 'save-edit') && !empty($XrRecid)) {
                    $resdata = $workoutModel->UpdateExerciseRecByIdData($_POST, $XrRecid);
                    if ($resdata['success']) {
                        $xrid = $resdata['xrid'];
                        $this->session->set('success', 'Exercise record modified successfully!!!');
                    } else {
                        $this->session->set('error', 'Error occurred while updating!!!');
                    }
                } else if (($method == 'save' || $method == 'save-edit') && empty($XrRecid)) {
                    $resdata = $workoutModel->InsertExerciseRecByIdData($_POST);
                    if ($resdata['success']) {
                        $xrid = $resdata['xrid'];
                        $this->session->set('success', 'Exercise record created successfully!!!');
                    } else {
                        $this->session->set('error', 'Error occurred while creating!!!');
                    }
                }
            }
            if ($method == 'save-edit') {
                $this->redirect('exercise/exerciserecord/' . (!empty($xrid) ? $xrid : (!empty($XrRecid) ? $XrRecid : '')) . '?act=' . $openfrom);
            } elseif ($method != 'save-edit' && $openfrom == 'indx') {
                $this->redirect('dashboard/index');
            } else {
                $this->redirect('exercise/exerciselibrary/');
            }
        }
        if (!empty($XrRecid) && is_numeric($XrRecid)) {
            $getunitdata     = $workoutModel->getExerciseById($XrRecid);
            $getmusothdata   = $workoutModel->getMusOthByUnitId($XrRecid);
            $getequipothdata = $workoutModel->getEquipOthByUnitId($XrRecid);
            $getseqdata      = $workoutModel->getSequencesByUnitId($XrRecid);
            $gettaglist      = $workoutModel->getUnitTagsById($XrRecid);
            // activity feed for open
            $activitydtl     = $workoutModel->getActivityFeedDetail(array(
                'feed_type' => '5',
                'action_type' => '15',
                'user' => $userid,
                'site_id' => $site_id,
                'type_id' => $XrRecid
            ));
            $feedjson        = array();
            if (count($activitydtl) > 0 && !empty($activitydtl)) {
                $feed_time = strtotime($activitydtl[0]['created_date']);
                $curr_time = strtotime(Helper_Common::get_default_datetime());
                $hour      = abs($curr_time - $feed_time) / (60 * 60);
                if ($hour > 1) {
                    $feedjson['text'] = 'in edit-mode';
                    $workoutModel->insertActivityFeed(5, 15, $XrRecid, $feedjson);
                } else {
                }
            } else {
                $feedjson['text'] = 'in edit-mode';
                $workoutModel->insertActivityFeed(5, 15, $XrRecid, $feedjson);
            }
            $formtype                      = 'edit';
            $this->template->content->xrid = $XrRecid;
        } elseif (!empty($XrRecid) && !is_numeric($XrRecid) && !empty($exerciseid)) {
            $this->template->content->startExercise = $XrRecid;
            if ($XrRecid == 'startmyxr' || $XrRecid == 'startsamplexr' || $XrRecid == 'startsharedxr') {
                $getunitdata     = $workoutModel->getExerciseById($exerciseid);
                $getmusothdata   = $workoutModel->getMusOthByUnitId($exerciseid);
                $getequipothdata = $workoutModel->getEquipOthByUnitId($exerciseid);
                $getseqdata      = $workoutModel->getSequencesByUnitId($exerciseid);
                $gettaglist      = $workoutModel->getUnitTagsById($exerciseid);
            }
            // activity feed for open
            $activitydtl = $workoutModel->getActivityFeedDetail(array(
                'feed_type' => '5',
                'action_type' => '15',
                'user' => $userid,
                'site_id' => $site_id,
                'type_id' => $exerciseid
            ));
            $feedjson    = array();
            if (count($activitydtl) > 0 && !empty($activitydtl)) {
                $feed_time = strtotime($activitydtl[0]['created_date']);
                $curr_time = strtotime(Helper_Common::get_default_datetime());
                $hour      = abs($curr_time - $feed_time) / (60 * 60);
                if ($hour > 1) {
                    $feedjson['text'] = 'in edit-mode';
                    $workoutModel->insertActivityFeed(5, 15, $exerciseid, $feedjson);
                } else {
                }
            } else {
                $feedjson['text'] = 'in edit-mode';
                $workoutModel->insertActivityFeed(5, 15, $exerciseid, $feedjson);
            }
            $formtype                      = 'edit';
            $this->template->content->xrid = $exerciseid;
        }
        $this->template->content->openfrom         = $openfrom;
        $this->template->content->formtype         = $formtype;
        $this->template->content->exerciseArray    = $getunitdata;
        $this->template->content->exerciseMusOth   = $getmusothdata;
        $this->template->content->exerciseEquipOth = $getequipothdata;
        $this->template->content->exerciseSeq      = $getseqdata;
        $this->template->content->exerciseTags     = $gettaglist;
    }
    public function action_exerciseimages()
    {
        $this->template->title = 'Images';
        $folderid              = urldecode($this->request->param('id'));
        $subfolderid           = urldecode($this->request->param('eid'));
        $imagelibrary          = ORM::factory('imagelibrary');
        $saveact               = (isset($_GET['action']) && !empty($_GET['action'])) ? $_GET['action'] : '';
        $saveactid             = (isset($_GET['imgid']) && !empty($_GET['imgid'])) ? $_GET['imgid'] : '';
        $this->render();
        $getsubfolders = array();
        $getfolderitem = array();
        $imgdatamethod = '';
        $site_id       = ($this->session->get('current_site_id') ? $this->session->get('current_site_id') : '0');
        if (HTTP_Request::POST == $this->request->method()) {
            $imgdatamethod = $this->request->post('saveimgdata');
            $imgdelete     = $this->request->post('delete_btn');
            $imgtaginsert  = $this->request->post('insertimgtag');
            $changestatus  = $this->request->post('changeimgstatus');
            $duplicate     = $this->request->post('duplicateimg');
            // echo '<pre>';print_r($_POST);exit;
            if (!empty($imgdatamethod) && ($imgdatamethod == 'savecontinue' || $imgdatamethod == 'saveclose')) {
                if (isset($_POST['curr_imgid']) && !empty($_POST['curr_imgid'])) {
                    $saveid = $_POST['curr_imgid'];
                    if (isset($_POST['croppedData']) && !empty($_POST['croppedData'])) {
                        if ($imagelibrary->updateImgUrlById($_POST)) {
                            $this->session->set('success', 'Image successfully updated!!!');
                        } else {
                            $this->session->set('error', 'Error occurred while updating image!!!');
                        }
                    } elseif (!empty($_POST['imgdata-title']) && !empty($_POST['imgdata-status'])) {
                        if ($imagelibrary->updataImgDataById($_POST)) {
                            $this->session->set('success', 'Image data successfully updated!!!');
                        } else {
                            $this->session->set('error', 'Error occurred while updating image data!!!');
                        }
                    } else {
                        $this->session->set('error', 'Error occurred, please check the given details!!!');
                    }
                }
            }
            if (!empty($imgdelete) && $imgdelete == 'delete') {
                if (isset($_POST['curr_imgid']) && !empty($_POST['curr_imgid'])) {
                    if ($imagelibrary->deleteImgById($_POST)) {
                        $this->session->set('success', 'Image successfully deleted!!!');
                    } else {
                        $this->session->set('error', 'This image used by some other record(s), cannot delete this image!!!');
                    }
                }
            }
            if (!empty($imgtaginsert) && $imgtaginsert == 'inserttag') {
                if (!isset($_POST['check_act']) && isset($_POST['curr_imgid']) && !empty($_POST['curr_imgid'])) {
                    if ($imagelibrary->insertImgTagById($_POST) === 'no-tag') {
                        $this->session->set('error', 'No tags inserted for this image!!!');
                    } elseif ($imagelibrary->insertImgTagById($_POST)) {
                        $this->session->set('success', 'Image tagged successfully!!!');
                    } else {
                        $this->session->set('error', 'Error occurred while tagging!!!');
                    }
                } elseif (isset($_POST['check_act']) && !empty($_POST['check_act'])) {
                    if (!empty($_POST['imgtag-input'])) {
                        if ($imagelibrary->insertImgTagforMultiple($_POST)) {
                            $this->session->set('success', 'Image(s) tagged successfully!!!');
                        } else {
                            $this->session->set('error', 'Error occurred while tagging!!!');
                        }
                    } else {
                        $this->session->set('error', 'Please enter any tag!!!');
                    }
                }
            }
            if (!empty($duplicate) && $duplicate == 'duplicate') {
                if (isset($_POST['curr_imgid']) && !empty($_POST['curr_imgid'])) {
                    if ($imagelibrary->doCopyAndDuplicateImages($_POST)) {
                        $this->session->set('success', 'Image successfully duplicated!!!');
                    } else {
                        $this->session->set('error', 'Error occurred while duplicate this image!!!');
                    }
                }
            }
            if (!empty($changestatus) && $changestatus == 'changestatus') {
                if (isset($_POST['check_act']) && !empty($_POST['check_act'])) {
                    if (!empty($_POST['imgchecked-status'])) {
                        if ($imagelibrary->updateImgStatusforMultiple($_POST)) {
                            $this->session->set('success', 'Status successfully updated!!!');
                        } else {
                            $this->session->set('error', 'Error occurred while updated status!!!');
                        }
                    } else {
                        $this->session->set('error', 'Please select any one status!!!');
                    }
                }
            }
            if (!empty($imgdatamethod) && $imgdatamethod == 'savecontinue') {
                if (isset($_POST['croppedData']) && !empty($_POST['croppedData'])) {
                    $this->redirect('exercise/exerciseimages/' . $folderid . '/' . $subfolderid . '/?action=editImg&imgid=' . $saveid);
                } else {
                    $this->redirect('exercise/exerciseimages/' . $folderid . '/' . $subfolderid . '/?action=editImgData&imgid=' . $saveid);
                }
            } else {
                $this->redirect('exercise/exerciseimages/' . $folderid . '/' . $subfolderid);
            }
        }
        if (!empty($subfolderid) && !empty($folderid)) {
            $getfolderitem                       = $imagelibrary->getFolderImages($subfolderid, $folderid);
            $this->template->content->subfolders = '';
            $this->template->content->folderitem = $getfolderitem;
            $this->template->content->foldername = $imagelibrary->getImgFolderName($subfolderid);
        } elseif (!empty($folderid)) {
            if ($folderid != '2') {
                $getsubfolders = $imagelibrary->getSubImgFolder($folderid);
                if (count($getsubfolders) > 0) {
                    $this->template->content->subfolders = $getsubfolders;
                    $this->template->content->foldername = $imagelibrary->getImgFolderName($folderid);
                }
                if (empty($subfolderid) && count($getsubfolders) <= 0) {
                    $getfolderitem                       = $imagelibrary->getFolderImages(0, $folderid);
                    $this->template->content->subfolders = '';
                    $this->template->content->folderitem = $getfolderitem;
                    $this->template->content->foldername = $imagelibrary->getImgFolderName($folderid);
                }
            } else {
                $getfolderitem                       = $imagelibrary->getFolderImages(5, $folderid);
                $this->template->content->subfolders = '';
                $this->template->content->folderitem = $getfolderitem;
                $this->template->content->foldername = $imagelibrary->getImgFolderName($folderid);
            }
        } else {
            $this->template->content->partentfolder = $imagelibrary->getParentImgFolder();
            if (isset($this->template->content->partentfolder) && count($this->template->content->partentfolder) > 0) {
                $parentFoldertemp = $this->template->content->partentfolder;
                foreach ($parentFoldertemp as $keys => $values) {
                    if ($values['folder_id'] == '1')
                        $this->template->content->partentfolder[$keys]['countval'] = $imagelibrary->getImgCountByFolder(0, 1);
                    else if ($values['folder_id'] == '2')
                        $this->template->content->partentfolder[$keys]['countval'] = $imagelibrary->getImgCountByFolder(0, 2);
                    else if ($values['folder_id'] == '3')
                        $this->template->content->partentfolder[$keys]['countval'] = $imagelibrary->getImgCountByFolder(0, 3);
                    else if ($values['folder_id'] == '6')
                        $this->template->content->partentfolder[$keys]['countval'] = $imagelibrary->getImgCountByFolder(0, 6);
                }
            }
        }
        if (!isset($this->template->content->partentfolder) && !empty($folderid) && empty($subfolderid)) {
            $this->template->content->profileimgcnt  = $imagelibrary->getImgCountByFolder(4, $folderid);
            $this->template->content->exerciseimgcnt = $imagelibrary->getImgCountByFolder(5, $folderid);
        }
        $this->template->content->parentFolderId = $folderid;
        $this->template->content->subFolderId    = $subfolderid;
        $this->template->content->exerciseStatus = $imagelibrary->getunitsbytable('unit_status');
        $this->template->content->saveaction     = $saveact;
        $this->template->content->saveactionid   = $saveactid;
    }
    public function _sendExerciseShareEmailToUser($sharedXrId, $site_id, $user_id, $unit_id)
    {
        $smtpmodel         = ORM::factory('admin_smtp');
        $shareworkoutmodel = ORM::factory('admin_shareworkout');
        $workoutModel      = ORM::factory('workouts');
        $current_site_id   = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
        if (isset($sharedXrId) && is_numeric($sharedXrId) && !empty($sharedXrId)) {
            $sites                 = Helper_Common::hasSiteAccess($site_id);
            $user                  = $shareworkoutmodel->getuserdetails($user_id);
            $user                  = $user[0];
            $templateArray         = $smtpmodel->getSendingMailTemplate(array(
                'type_name' => 'notification - shared workout','site_id' => $site_id
            ));
            $encryptedmessage      = Helper_Common::encryptPassword($user['user_email'] . '####' . $user['security_code'] . '####exerciserecord');
            $exerciseUrl           = URL::site(NULL, 'http') . (isset($sites["slug"]) ? $sites["slug"] : '') . "/index/autoredirect/" . $sharedXrId . "/" . $encryptedmessage;
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
                ($sites) ? $sites["name"] : '',
                $exerciseUrl
            ), $templateArray['body']);
            $messageArray          = array(
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
            if (is_array($templateArray) && !empty($templateArray) && !empty($templateArray['smtp_id']) && false) {
                $hostAddress = explode("://", $templateArray['smtp_host']);
                $emailMailer = Email::dynamicMailer('smtp', array(
                    'hostname' => trim($hostAddress['1']),
                    'port' => $templateArray['smtp_port'],
                    'username' => $templateArray['smtp_user'],
                    'password' => Helper_Common::decryptPassword($templateArray['smtp_pass']),
                    'encryption' => trim($hostAddress['0'])
                ));
            } else {
                $emailMailer = Email::dynamicMailer('', array());
            }
            Email::sendBysmtp($emailMailer, $messageArray);
            /*Activity Feed */
            if (!empty($user_id)) {
                $feedjson   = array();
                $feedjson[] = $user_id;
                $workoutModel->insertActivityFeed(5, 7, $unit_id, $feedjson);
            }
        }
        return true;
    }
    public function action_myactioncalendar()
    {
        $this->template->title = 'My Action Calendar';
        $workoutModel          = ORM::factory('workouts');
        $smtpmodel             = ORM::factory('admin_smtp');
        $site_id               = ($this->session->get('current_site_id') ? $this->session->get('current_site_id') : '0');
        $datevalue             = Helper_Common::get_default_datetime();
        $getdate               = $flag = '';
        $getdate               = $this->request->param('id');
        if (empty($getdate))
            $getdate = $datevalue;
        if (isset($_GET['act']) && !empty($_GET['act']))
            $flag = trim($_GET['act']);
        /** activity feed starts **/
        $activity_feed            = array();
        $activity_feed["user"]    = $this->globaluser->pk();
        $activity_feed["site_id"] = $site_id;
        /** activity feed ends **/
        if (HTTP_Request::POST == $this->request->method()) {
            $method       = $this->request->post('f_method');
            $save_edit    = $this->request->post('save_edit');
            $workassignid = $this->request->post('wkout_assign_id');
            $worklogid    = $this->request->post('wkout_log_id');
            if (empty($worklogid))
                $worklogid = $this->request->post('wkoutlog_id');
            if ($save_edit == '2')
                $this->redirect('exercise/myactioncalendar/' . $getdate);
            if ($method == 'assignAdd') {
                $assignArr['wkout_id']            = $this->request->post('selected_wkout_id');
                $assignArr['wkout_sample_id']     = $this->request->post('selected_sampe_id');
                $assignArr['wkout_share_id']      = $this->request->post('selected_share_id');
                $assignArr['wkout_assign_id']     = $this->request->post('selected_wkout_assign_id');
                $assignArr['wkout_assign_dup_id'] = $this->request->post('selected_wkout_assign_dup_id');
                $assignArr['wkout_log_id']        = $this->request->post('selected_log_id');
                if (!empty($assignArr['wkout_id'])) {
                    $assignArr['modified_by']     = $assignArr['assigned_by'] = $assignArr['assigned_for'] = $this->globaluser->pk();
                    $assignArr['assigned_date']   = Helper_Common::get_default_date($this->request->post('selected_date'));
                    $assignArr['created']         = $assignArr['modified'] = $datevalue;
                    $assignArr['from_wkout']      = '0';
                    $assignId                     = $workoutModel->addToWkoutAssign($assignArr, $this->globaluser->pk());
                    $activity_feed["feed_type"]   = '2';
                    $activity_feed["action_type"] = '22';
                    $activity_feed["type_id"]     = $assignArr['wkout_id'];
                    $activity_feed["json_data"]   = json_encode(array(
                        'wkoutassign' => $assignId
                    ));
                    Helper_Common::createActivityFeed($activity_feed);
					/*** email -automation Start ***/
					$emailNotifyArray['wkout_assign_id'] = $assignId;
					$emailNotifyArray['triggerby_date']  = Helper_Common::get_default_date($assignArr['assigned_date']);
					$emailNotifyArray['triggerby_time']  = Helper_Common::getUserEmailTime();
					$workoutModel->insertEmailNotify($emailNotifyArray);
					/*** email -automation End ***/
                    $this->session->set('success', 'Workout Plan Assigned Successfully Added!!!');
                    if ($save_edit == 1)
                        $this->redirect('exercise/assignedplan/' . $assignId . '?act=edit&edit=0');
                } elseif (!empty($assignArr['wkout_sample_id'])) {
                    $assignArr['modified_by']     = $assignArr['assigned_by'] = $assignArr['assigned_for'] = $this->globaluser->pk();
                    $assignArr['assigned_date']   = Helper_Common::get_default_date($this->request->post('selected_date'));
                    $assignArr['created']         = $assignArr['modified'] = $datevalue;
                    $assignArr['from_wkout']      = '2';
                    $assignId                     = $workoutModel->addToWkoutAssign($assignArr, $this->globaluser->pk());
                    $activity_feed["feed_type"]   = '15';
                    $activity_feed["action_type"] = '22';
                    $activity_feed["type_id"]     = $assignArr['wkout_sample_id'];
                    $activity_feed["json_data"]   = json_encode(array(
                        'wkoutassign' => $assignId
                    ));
                    Helper_Common::createActivityFeed($activity_feed);
					/*** email -automation Start ***/
					$emailNotifyArray['wkout_assign_id'] = $assignId;
					$emailNotifyArray['triggerby_date']  = Helper_Common::get_default_date($assignArr['assigned_date']);
					$emailNotifyArray['triggerby_time']  = Helper_Common::getUserEmailTime();
					$workoutModel->insertEmailNotify($emailNotifyArray);
					/*** email -automation End ***/
                    $this->session->set('success', 'Workout Plan Assigned Successfully Added!!!');
                    if ($save_edit == 1)
                        $this->redirect('exercise/assignedplan/' . $assignId . '?act=edit&edit=0');
                } elseif (!empty($assignArr['wkout_share_id'])) {
                    $assignArr['modified_by']     = $assignArr['assigned_by'] = $assignArr['assigned_for'] = $this->globaluser->pk();
                    $assignArr['assigned_date']   = Helper_Common::get_default_date($this->request->post('selected_date'));
                    $assignArr['created']         = $assignArr['modified'] = $datevalue;
                    $assignArr['from_wkout']      = '1';
                    $assignId                     = $workoutModel->addToWkoutAssign($assignArr, $this->globaluser->pk());
                    $activity_feed["feed_type"]   = '12';
                    $activity_feed["action_type"] = '22';
                    $activity_feed["type_id"]     = $assignArr['wkout_share_id'];
                    $activity_feed["json_data"]   = json_encode(array(
                        'wkoutassign' => $assignId
                    ));
                    Helper_Common::createActivityFeed($activity_feed);
					/*** email -automation Start ***/
					$emailNotifyArray['wkout_assign_id'] = $assignId;
					$emailNotifyArray['triggerby_date']  = Helper_Common::get_default_date($assignArr['assigned_date']);
					$emailNotifyArray['triggerby_time']  = Helper_Common::getUserEmailTime();
					$workoutModel->insertEmailNotify($emailNotifyArray);
					/*** email -automation End ***/	
                    $this->session->set('success', 'Workout Plan Assigned Successfully Added!!!');
                    if ($save_edit == 1)
                        $this->redirect('exercise/assignedplan/' . $assignId . '?act=edit&edit=0');
                } elseif (!empty($assignArr['wkout_assign_dup_id'])) {
                    $assignArr['wkout_assign_id']  = $assignArr['wkout_assign_dup_id'];
                    $assignArr['modified_by']      = $assignArr['assigned_by'] = $assignArr['assigned_for'] = $this->globaluser->pk();
                    $assignArr['assigned_date']    = Helper_Common::get_default_date($this->request->post('selected_date'));
                    $assignArr['created']          = $assignArr['modified'] = $datevalue;
                    $assignArr['from_wkout']       = '3';
                    $oldAssignedDate               = $workoutModel->getAssignDateById($assignArr['wkout_assign_id']);
                    $activity_feed["context_date"] = Helper_Common::get_default_datetime($oldAssignedDate);
                    $assignId                      = $workoutModel->addToWkoutAssign($assignArr, $this->globaluser->pk());
                    $activity_feed["feed_type"]    = '13';
                    $activity_feed["action_type"]  = '22';
                    $activity_feed["type_id"]      = $assignArr['wkout_assign_id'];
                    $activity_feed["json_data"]    = json_encode(array(
                        'wkoutassign' => $assignId
                    ));
                    Helper_Common::createActivityFeed($activity_feed);
					/*** email -automation Start ***/
					$emailNotifyArray['wkout_assign_id'] = $assignId;
					$emailNotifyArray['triggerby_date']  = Helper_Common::get_default_date($assignArr['assigned_date']);
					$emailNotifyArray['triggerby_time']  = Helper_Common::getUserEmailTime();
					$workoutModel->insertEmailNotify($emailNotifyArray);
					/*** email -automation End ***/
                    $this->session->set('success', 'Assigned Workout Plan Successfully Duplicated!!!');
                    $this->redirect('exercise/myactioncalendar/' . $assignArr['assigned_date']);
                } elseif (!empty($assignArr['wkout_assign_id'])) {
                    $assignArr['wkout_assign_id'] = $this->request->post('selected_wkout_assign_id');
                    $oldAssignedDate              = $workoutModel->getAssignDateById($assignArr['wkout_assign_id']);
                    $assignArr['modified_by']     = $this->globaluser->pk();
                    $assignArr['assigned_date']   = Helper_Common::get_default_date($this->request->post('selected_date'));
                    $assignArr['modified']        = Helper_Common::get_default_datetime();
                    $workoutModel->addToReassignWkoutAssign($assignArr);
                    $activity_feed["feed_type"]    = '13';
                    $activity_feed["action_type"]  = '24';
                    $activity_feed["type_id"]      = $assignArr['wkout_assign_id'];
                    $activity_feed["json_data"]    = json_encode(Helper_Common::get_default_datetime($assignArr['assigned_date']));
                    $activity_feed["context_date"] = Helper_Common::get_default_datetime($oldAssignedDate);
                    Helper_Common::createActivityFeed($activity_feed);
					/*** email -automation Start ***/
					$emailNotifyArray['triggerby_date']  = Helper_Common::get_default_date($assignArr['assigned_date']);
					$emailNotifyArray['triggerby_time']  = Helper_Common::getUserEmailTime();
					$workoutModel->updateEmailNotifyByAssignId($oldAssignedDate, $emailNotifyArray);
					/*** email -automation End ***/
                    $this->session->set('success', 'Assigned Plan Re-assigned Successfully!!!');
                } elseif (!empty($assignArr['wkout_log_id'])) {
                    $assignArr['modified_by']      = $assignArr['assigned_by'] = $assignArr['assigned_for'] = $this->globaluser->pk();
                    $assignArr['assigned_date']    = Helper_Common::get_default_date($this->request->post('selected_date'));
                    $assignArr['created']          = $assignArr['modified'] = $datevalue;
                    $assignArr['from_wkout']       = '4';
                    $assignId                      = $workoutModel->addToWkoutAssign($assignArr, $this->globaluser->pk());
                    $activity_feed["feed_type"]    = '11';
                    $activity_feed["action_type"]  = '22';
                    $activity_feed["type_id"]      = $assignArr['wkout_log_id'];
                    $activity_feed["json_data"]    = json_encode(array(
                        'wkoutassign' => $assignId
                    ));
                    $activity_feed["context_date"] = Helper_Common::get_default_datetime($assignArr['assigned_date']);
                    Helper_Common::createActivityFeed($activity_feed);
					/*** email -automation Start ***/
					$emailNotifyArray['wkout_assign_id'] = $assignId;
					$emailNotifyArray['triggerby_date']  = Helper_Common::get_default_date($assignArr['assigned_date']);
					$emailNotifyArray['triggerby_time']  = Helper_Common::getUserEmailTime();
					$workoutModel->insertEmailNotify($emailNotifyArray);
					/*** email -automation End ***/
                    $this->session->set('success', 'Workout Plan Assigned Successfully Added!!!');
                    if ($save_edit == 1)
                        $this->redirect('exercise/assignedplan/' . $assignId . '?act=edit&edit=0');
                }
            } elseif ($method == 'add_rating') {
                $rating['unit_id']            = $this->request->post('unit_id');
                $rating['rate_value']         = $this->request->post('slider-1');
                $rating['rate_comments']      = $this->request->post('rating_msg');
                $rating['created_date']       = $rating['modified_date'] = Helper_Common::get_default_datetime();
                $rateId                       = $workoutModel->insertRatingDetails($rating, $this->globaluser->pk());
                $activity_feed["feed_type"]   = '5';
                $activity_feed["action_type"] = '25';
                $activity_feed["type_id"]     = $rating['unit_id'];
                Helper_Common::createActivityFeed($activity_feed);
                $this->session->set('success', 'Rating created successfully!!!');
            } elseif (!empty($worklogid) && ($method == 'add_new_log_start' || $method == 'add_new_log_end')) {
                $loggArray['note_wkout_intensity'] = $this->request->post('slider-1');
                $loggArray['note_wkout_remarks']   = $this->request->post('note_wkout_remarks');
                $loggArray['created']              = $loggArray['modified'] = $datevalue;
                $loggArray['wkout_id']             = $worklogid;
                $loggArray['from_wkout']           = '4';
                $loggArray['assigned_date']        = Helper_Common::get_default_date($this->request->post('selected_date_hidden'));
                $loggArray['modified_by']          = $this->globaluser->pk();
                $wkoutLog                          = $workoutModel->createWkoutLogBywkoutId('wkoutlog', $worklogid, $loggArray, $this->globaluser->pk());
                $activity_feed["feed_type"]        = '11';
                $activity_feed["action_type"]      = '22';
                $activity_feed["type_id"]          = $worklogid;
                $activity_feed["json_data"]        = json_encode(array(
                    'wkoutlog' => $wkoutLog
                ));
                $activity_feed["context_date"]     = Helper_Common::get_default_datetime($loggArray['assigned_date']);
                Helper_Common::createActivityFeed($activity_feed);
                $this->session->set('success', 'Successfully Duplicated to New Journal!!!');
                if ($save_edit == 1)
                    $this->redirect('exercise/workoutlog/' . $wkoutLog . '?act=edit&edit=0');
                else
                    $this->redirect('exercise/myactioncalendar/' . $getdate . '?act=log');
            } elseif ($method == 'add_new_log_comp' || $method == 'add_new_log_skip') {
                if (!empty($workassignid)) {
                    $assignedArray['note_wkout_intensity'] = $this->request->post('slider-1');
                    $assignedArray['note_wkout_remarks']   = $this->request->post('note_wkout_remarks');
                    $assignedArray['created']              = $datevalue;
                    $assignedArray['wkout_id']             = $workassignid;
                    $assignedArray['from_wkout']           = '3';
					$assignedArray['assigned_date']		   = $this->request->post('selected_date');
					if(empty($assignedArray['assigned_date']))
						$assignedArray['assigned_date']    = $workoutModel->getAssignDateById($workassignid);
                    $assignedArray['modified_by']          = $this->globaluser->pk();
                    $assignedArray['modified']             = $datevalue;
					$assignedArray['associated_assign_id'] = $workassignid;
                    if ($method == 'add_new_log_comp') {
                        $assignedArray['wkout_status'] = '1'; // 1 -completed
                        $activity_json_data = ' as Completed';
                        $message            = 'Successfully Marked as Completed!!!';
                    } else {
                        $assignedArray['wkout_status'] = '2'; // 2 -skipped
                        $activity_json_data = ' as Skipped';
                        $message            = 'Successfully Marked as Skipped!!!';
                    }
                    $wkoutLog                      = $workoutModel->createWkoutLogBywkoutId('assigned', $workassignid, $assignedArray, $this->globaluser->pk());
					$workoutModel->updateMarkedStatus($workassignid, $assignedArray['wkout_status']);
                    $activity_feed["feed_type"]    = '11';
                    $activity_feed["action_type"]  = '1';
                    $activity_feed["type_id"]      = $wkoutLog;
                    $activity_feed["json_data"]    = json_encode($activity_json_data);
                    $activity_feed["context_date"] = Helper_Common::get_default_datetime($assignedArray['assigned_date']);
                    Helper_Common::createActivityFeed($activity_feed);
                    $this->session->set('success', $message);
                    if ($save_edit == 1)
                        $this->redirect('exercise/workoutlog/' . $wkoutLog . '?act=edit&edit=0');
                } elseif (!empty($worklogid)) {
                    $loggedArray['note_wkout_intensity'] = $this->request->post('slider-1');
                    $loggedArray['note_wkout_remarks']   = $this->request->post('note_wkout_remarks');
                    $loggedArray['modified']             = $datevalue;
                    if ($method == 'add_new_log_comp') {
                        $loggedArray['wkout_status']  = '1'; // 1 -completed
                        $logxrArray['set_status']     = '1';
                        $activity_json_data           = ' as Completed';
                        $activity_feed["action_type"] = '28';
                        $message                      = 'Successfully Marked as Completed!!!';
                    } else {
                        $loggedArray['wkout_status']  = '2'; // 2 -skipped
                        $logxrArray['set_status']     = '2';
                        $activity_json_data           = ' as Skipped';
                        $activity_feed["action_type"] = '29';
                        $message                      = 'Successfully Marked as Skipped!!!';
                    }
                    $workoutModel->updateLoggedWkoutDetails($loggedArray, $worklogid);
                    $workoutModel->updateLoggedWkoutXRDetails($logxrArray, $worklogid);
                    $oldAssignedDate               = $workoutModel->getAssignDateByLogId($worklogid);
                    $activity_feed["feed_type"]    = '7';
                    $activity_feed["type_id"]      = $worklogid;
                    $activity_feed["json_data"]    = json_encode($activity_json_data);
                    $activity_feed["context_date"] = Helper_Common::get_default_datetime($oldAssignedDate);
                    Helper_Common::createActivityFeed($activity_feed);
                    $this->session->set('success', $message);
                }
                $this->redirect('exercise/myactioncalendar/' . $getdate . '?act=log');
            } elseif ($method == 'add_workout') {
                $methodReq  = $this->request->post('method');
                $user_id    = $this->globaluser->pk();
                $countTotal = $this->request->post('s_row_count_xr');
                if (!empty($methodReq)) {
                    $wkoutArray['from_wkout'] = $this->request->post('from_wkout');
                    $wkoutArray['wkout_id']   = $this->request->post('wrkout_id');
                }
                $wkoutArray['wkout_title']    = $this->request->post('wkout_title');
                $wkoutArray['wkout_color']    = $this->request->post('wrkoutcolor');
                $wkoutArray['wkout_focus']    = $this->request->post('wkout_focus');
                $wkoutArray['wkout_group']    = $wkoutArray['wkout_poa'] = $wkoutArray['wkout_poa_time'] = '0';
                $wkoutArray['wkout_order']    = '1';
                $wkoutArray['status_id']      = '1';
                $wkoutArray['created']        = $wkoutArray['created_date'] = $datevalue;
                $wkoutArray['user_id']        = $user_id;
                $wkoutArray['modified']       = $wkoutArray['modified_date'] = $datevalue;
                $wkoutArray['site_id']        = $site_id;
                $wkoutId                      = $_POST['wkout_id'] = $workoutModel->insertWorkoutDetails($wkoutArray);
                $activity_feed["feed_type"]   = '2';
                $activity_feed["action_type"] = '1';
                $activity_feed["type_id"]     = $wkoutId;
                Helper_Common::createActivityFeed($activity_feed);
                if (isset($_POST['exercise_title']) && !empty($_POST['exercise_title'])) {
                    foreach ($_POST['exercise_title'] as $keys => $values) {
                        if (!empty($values) && trim($values) != '') {
                            $workoutModel->addWorkoutSetFromworkout($_POST, $keys, $this->globaluser->pk());
                            $workoutModel->addLoggedWorkoutSetFromworkout($_POST, $keys, $user_id, $wkoutId);
                        }
                    }
                }
                $this->session->set('success', 'Workout Record Created Successfully!!!');
                if ($save_edit == 1)
                    $this->redirect('exercise/workoutrecord/' . $wkoutId . '?act=edit&edit=0');
                else
                    $this->redirect('exercise/myactioncalendar/' . $getdate . '?act=log');
            } elseif ($method == 'add_workoutlog') {
                $methodReq  = $this->request->post('method');
                $user_id    = $this->globaluser->pk();
                $countval   = $countvalskip = 0;
                $countTotal = $this->request->post('s_row_count_xr');
                if (!empty($methodReq)) {
                    $loggedArray['from_wkout'] = $this->request->post('from_wkout');
                    $loggedArray['wkout_id']   = $this->request->post('wrkout_id');
                }
                $loggedArray['wkout_title']          = $this->request->post('wkout_title');
                $loggedArray['wkout_color']          = $this->request->post('wrkoutcolor');
                $loggedArray['wkout_focus']          = $this->request->post('wkout_focus');
                $loggedArray['wkout_group']          = $loggedArray['wkout_poa'] = $loggedArray['wkout_poa_time'] = '0';
                $loggedArray['note_wkout_intensity'] = $this->request->post('slider-1');
                $loggedArray['note_wkout_remarks']   = $this->request->post('note_wkout_remarks');
                $loggedArray['created']              = $datevalue;
				if($this->request->post('selected_date') != '')
					$dateVal                         = Helper_Common::get_default_date($this->request->post('selected_date'));
				else if($this->request->post('selected_date_hidden') != '')
					$dateVal = Helper_Common::get_default_date($this->request->post('selected_date_hidden'));
                $loggedArray['assigned_date']        = (!empty($dateVal) ? $dateVal : Helper_Common::get_default_date());
                $loggedArray['modified_by']          = $this->globaluser->pk();
                $loggedArray['modified']             = $datevalue;
                $loggedArray['site_id']              = $site_id;
                $wkoutLog                            = $workoutModel->createWkoutLogByCustom($loggedArray, $user_id);
                $activity_feed["feed_type"]          = '11';
                $activity_feed["action_type"]        = '6';
                $activity_feed["type_id"]            = $wkoutLog;
                $activity_feed["context_date"]       = Helper_Common::get_default_datetime($loggedArray['assigned_date']);
                Helper_Common::createActivityFeed($activity_feed);
                if (isset($_POST['exercise_title']) && !empty($_POST['exercise_title'])) {
                    foreach ($_POST['exercise_title'] as $keys => $values) {
                        if (!empty($values) && trim($values) != '') {
                            if (isset($_POST['markedstatus'][$keys]) && ($_POST['markedstatus'][$keys] == '1'))
                                $countval += 1;
							if (isset($_POST['markedstatus'][$keys]) && ($_POST['markedstatus'][$keys] == '2'))
                                $countvalskip += 1;
                            $workoutModel->addLoggedWorkoutSetFromworkout($_POST, $keys, $user_id, $wkoutLog);
                        }
                    }
                }
                if ($countTotal == $countval || $countTotal == ($countval + $countvalskip))
                    $workoutModel->updateLoggedWkoutDetails(array(
                        'wkout_status' => '1'
                    ), $wkoutLog);
                elseif ($countTotal == $countvalskip)
                    $workoutModel->updateLoggedWkoutDetails(array(
                        'wkout_status' => '2'
                    ), $wkoutLog);
				elseif ($countTotal > ($countval + $countvalskip))
                    $workoutModel->updateLoggedWkoutDetails(array(
                        'wkout_status' => '3'
                    ), $wkoutLog);
                $this->session->set('success', 'Workout Journal Created Successfully!!!');
                if ($save_edit == 1)
                    $this->redirect('exercise/workoutlog/' . $wkoutLog . '?act=edit&edit=0');
                else
                    $this->redirect('exercise/myactioncalendar/' . $getdate . '?act=log');
            }elseif ($method == "delete" && !empty($worklogid)) {
                $assignedArray['modified']  = $datevalue;
                $assignedArray['status_id'] = '4'; // deleted
                $workoutModel->updateLoggedWkoutDetails($assignedArray, $worklogid);
                $activity_feed["feed_type"]   = '11';
                $activity_feed["action_type"] = '23';
                $activity_feed["type_id"]     = $worklogid;
                Helper_Common::createActivityFeed($activity_feed);
                $this->session->set('success', 'Successfully deleted from Journal!!!');
                $this->redirect('exercise/myactioncalendar/' . $getdate . '?act=log');
            } elseif ($method == 'cancel' && !empty($workassignid)) {
                $activity_feed["feed_type"]    = '13';
                $activity_feed["action_type"]  = '2';
                $activity_feed["type_id"]      = $workassignid;
                $oldAssignedDate               = $workoutModel->getAssignDateById($workassignid);
                $activity_feed["context_date"] = Helper_Common::get_default_datetime($oldAssignedDate);
                $workoutModel->updateMarkedStatus($workassignid, '3');
                Helper_Common::createActivityFeed($activity_feed);
				/*** email -automation Start ***/
				$workoutModel->updateEmailNotifyByAssignId($workassignid);
				/*** email -automation End ***/		
                $this->session->set('success', 'Successfully cancelled!!!');
                $this->redirect('exercise/myactioncalendar/' . $getdate);
            } else if ($method == 'add_new_wkout_from_log') {
                $wkoutId                      = $workoutModel->doCopyForExerciseSetsById('workoutLogToNewWkout', $this->globaluser->pk(), '0', $worklogid, 'copy');
                $activity_feed["feed_type"]   = '11';
                $activity_feed["action_type"] = '22';
                $activity_feed["type_id"]     = $worklogid;
                $activity_feed["json_data"]   = json_encode(array(
                    'wkout' => $wkoutId
                ));
                Helper_Common::createActivityFeed($activity_feed);
                $this->session->set('success', 'Successfully Added as New Workout!!!');
                $this->redirect('exercise/myactioncalendar/' . $getdate . '?act=log');
            } elseif ($method == 'add_new_wkout') {
                $wkoutId                      = $workoutModel->doCopyForExerciseSetsById('workoutAssignToNewWkout', $this->globaluser->pk(), '0', $workassignid, 'copy');
                $activity_feed["feed_type"]   = '13';
                $activity_feed["action_type"] = '22';
                $activity_feed["type_id"]     = $workassignid;
                $activity_feed["json_data"]   = json_encode(array(
                    'wkout' => $wkoutId
                ));
                Helper_Common::createActivityFeed($activity_feed);
                $this->session->set('success', 'Successfully Added as New Workout!!!');
            } elseif ($method == 'add_new_assign') {
                $methodReq = $this->request->post('method');
                $user_id   = $this->globaluser->pk();
                if ($methodReq != '') {
                    $updateArray['wkout_id']   = $this->request->post('wkout_id');
                    $updateArray['from_wkout'] = $this->request->post('from_wkout');
                }
                $updateArray['wkout_title']    = $this->request->post('wkout_title');
                $updateArray['wkout_color']    = $this->request->post('wrkoutcolor');
                $updateArray['assigned_date']  = Helper_Common::get_default_date($this->request->post('selected_date_hidden'));
                $updateArray['wkout_focus']    = $this->request->post('wkout_focus');
                $updateArray['wkout_group']    = $updateArray['wkout_poa'] = $updateArray['wkout_poa_time'] = '0';
                $updateArray['user_id']        = $updateArray['assigned_by'] = $updateArray['modified_by'] = $updateArray['assigned_for'] = $user_id;
                $updateArray['created']        = $updateArray['modified'] = Helper_Common::get_default_datetime();
                $wkout_assign_id               = $workoutModel->addToWkoutAssignCustom($updateArray, $user_id);
                $activity_feed["feed_type"]    = '13';
                $activity_feed["action_type"]  = '1';
                $activity_feed["type_id"]      = $wkout_assign_id;
                $activity_feed["context_date"] = Helper_Common::get_default_datetime($updateArray['assigned_date']);
                Helper_Common::createActivityFeed($activity_feed);
                if (isset($_POST['exercise_title']) && !empty($_POST['exercise_title'])) {
                    foreach ($_POST['exercise_title'] as $keys => $values) {
                        if (!empty($values) && trim($values) != '') {
                            $res = $workoutModel->addAssignWorkoutSetFromworkout($_POST, $keys, $user_id, $wkout_assign_id);
                        }
                    }
                }
				/*** email -automation Start ***/
				$emailNotifyArray['wkout_assign_id'] = $wkout_assign_id;
				$emailNotifyArray['triggerby_date']  = Helper_Common::get_default_date($updateArray['assigned_date']);
				$emailNotifyArray['triggerby_time']  = Helper_Common::getUserEmailTime();
				$workoutModel->insertEmailNotify($emailNotifyArray);
				/*** email -automation End ***/
                $this->session->set('success', 'Assigned Plans Created Successfully!!!');
                if ($save_edit == 1)
                    $this->redirect('exercise/assignedplan/' . $wkout_assign_id . '?act=edit&edit=0');
            } elseif ($method == 'add_tag_to_xr') {
                $add_tag['unit_id']   = $this->request->post('unit_id');
                $add_tag['tag-input'] = $this->request->post('xrtag-input');
                $workoutModel->insertUnitTagById($add_tag['tag-input'], $add_tag['unit_id']);
                $this->session->set('success', 'Tagged Successfully!!!');
            } elseif ($method == 'addLog' || $method == 'addAssign') {
                $addType                 = $this->request->post('addtype');
                $wkoutId                 = $this->request->post('addid');
                $wkoutDate               = $this->request->post('adddate');
                $datevalue               = Helper_Common::get_default_datetime();
                $updateArray['wkout_id'] = $wkoutId;
                if ($addType == 'wrkout') {
                    $workoutRecord              = $workoutModel->getworkoutById($this->globaluser->pk(), $wkoutId);
                    $exerciseRecord             = $workoutModel->getExerciseSets('wkout', $wkoutId);
                    $activity_feed["feed_type"] = '2';
                    $updateArray['from_wkout']  = '0';
                } elseif ($addType == 'assigned') {
                    $workoutRecord              = $workoutModel->getAssignworkoutById($wkoutId, $this->globaluser->pk());
                    $exerciseRecord             = $workoutModel->getExerciseSets('assigned', $wkoutId);
                    $activity_feed["feed_type"] = '13';
                    $updateArray['from_wkout']  = '3';
                } elseif ($addType == 'sample') {
                    $workoutRecord              = $workoutModel->getSampleworkoutById('0', $wkoutId);
                    $exerciseRecord             = $workoutModel->getSampleExerciseSet($wkoutId);
                    $activity_feed["feed_type"] = '15';
                    $updateArray['from_wkout']  = '2';
                } elseif ($addType == 'shared') {
                    $workoutRecord              = $workoutModel->getShareworkoutById($this->globaluser->pk(), $wkoutId);
                    $exerciseRecord             = $workoutModel->getExerciseSets('shared', $wkoutId);
                    $activity_feed["feed_type"] = '12';
                    $updateArray['from_wkout']  = '1';
                } elseif ($addType == 'wkoutlog') {
                    $workoutRecord              = $workoutModel->getLoggedworkoutById($wkoutId);
                    $exerciseRecord             = $workoutModel->getExerciseSets('wkoutlog', $wkoutId);
                    $activity_feed["feed_type"] = '11';
                    $updateArray['from_wkout']  = '4';
                }
                $updateArray['wkout_title']    = $workoutRecord['wkout_title'];
                $updateArray['wkout_color']    = $workoutRecord['wkout_color'];
                $updateArray['wkout_focus']    = $workoutRecord['wkout_focus'];
                $updateArray["modified"]       = $datevalue;
                $updateArray['created']        = $datevalue;
                $updateArray['modified_by']    = $updateArray['assigned_by'] = $updateArray['assigned_for'] = $this->globaluser->pk();
                $updateArray['wkout_group']    = $workoutRecord['wkout_group'];
                $updateArray['wkout_order']    = '1';
                $updateArray['status_id']      = '1';
                $updateArray['user_id']        = $this->globaluser->pk();
                $updateArray['created_date']   = $datevalue;
                $updateArray['modified_date']  = $datevalue;
                $updateArray['assigned_date']  = $logDate = Helper_Common::get_default_date($wkoutDate);
                $activity_feed["context_date"] = Helper_Common::get_default_datetime($updateArray['assigned_date']);
                $activity_feed["action_type"]  = '22';
                $activity_feed["type_id"]      = $updateArray['wkout_id'];
                if ($method == 'addLog') {
                    $updateArray['wkout_status'] = '1';
                    if ($addType == 'assigned') {
                        $updateArray['marked_status'] = '1';
                        $workid                       = $workoutModel->createWkoutLogByassignId($wkoutId, $updateArray, false);
                    } else
                        $workid = $workoutModel->createWkoutLogByCustom($updateArray, $this->globaluser->pk());
                    $activity_feed["json_data"] = json_encode(array(
                        'wkoutlog' => $workid
                    ));
                } else {
                    $workid                     = $workoutModel->addToWkoutAssignCustom($updateArray, $this->globaluser->pk());
                    $activity_feed["json_data"] = json_encode(array(
                        'wkoutassign' => $workid
                    ));
                }
                Helper_Common::createActivityFeed($activity_feed);
                $count = 0;
                if (isset($exerciseRecord) && count($exerciseRecord) > 0) {
                    foreach ($exerciseRecord as $keys => $values) {
                        if (is_array($values) && !empty($values)) {
                            $values['goal_order'] = $count + 1;
                            if ($method == 'addLog')
                                $values['set_status'] = '1';
                            $values['wkout_id'] = $workid;
                            if ($method == 'addLog')
                                $workoutModel->addLoggedWorkoutSetFromExist($values, $this->globaluser->pk(), $workid);
                            else
                                $workoutModel->addAssignWorkoutSetFromExistworkout($values, $this->globaluser->pk(), $workid);
                        }
                    }
                }
                if ($method == 'addLog') {
                    $this->session->set('success', 'Created Workout Log Successfully!!!');
                    $this->redirect('exercise/myactioncalendar/' . $logDate . '?act=log');
                } else {
					/*** email -automation Start ***/
					$emailNotifyArray['wkout_assign_id'] = $workid;
					$emailNotifyArray['triggerby_date']  = Helper_Common::get_default_date($updateArray['assigned_date']);
					$emailNotifyArray['triggerby_time']  = Helper_Common::getUserEmailTime();
					$workoutModel->insertEmailNotify($emailNotifyArray);
					/*** email -automation End ***/
                    $this->session->set('success', 'Created Assigned Plan Successfully!!!');
                    $this->redirect('exercise/myactioncalendar/' . $logDate);
                }
            }
            $this->redirect('exercise/myactioncalendar/' . $getdate);
        }
        $this->render();
        $this->template->content->getdate = $getdate;
        $this->template->content->flag    = $flag;
    }
    public function _sendShareAssignEmailToUser($workoutId, $site_id, $user_id, $activity_feed, $assignIds)
    {
        $smtpmodel         	= ORM::factory('admin_smtp');
        $shareworkoutmodel 	= ORM::factory('admin_shareworkout');
        $workoutModel     	= ORM::factory('workouts');
        if (isset($workoutId) && is_numeric($workoutId) && !empty($workoutId)) {
            $sites         	= Helper_Common::hasSiteAccess($site_id);
            $user           = $shareworkoutmodel->getuserdetails($user_id);
            $user           = $user[0];
            $templateArray  = $smtpmodel->getSendingMailTemplate(array(
                'type_name' => 'notification - shared / assignment workout','site_id' => $site_id
            ));
			$user_Role_name = $workoutModel->getRoleNameByUserId($this->globaluser->pk());
            $encryptedmsgall = Helper_Common::encryptPassword($user['user_email'] . '####' . $user['security_code'].'####sharedassignworkoutall');
			$encryptedmessage = Helper_Common::encryptPassword($user['user_email'] . '####' . $user['security_code'].'####sharedassignworkout');
            $activityjson["from_wkout"] = ($activity_feed["feed_type"] == 2 ? "myworkout" : ($activity_feed["feed_type"] == 15 ? "sample" : ($activity_feed["feed_type"] == 13 ? "assigned" : ($activity_feed["feed_type"] == 11 ? "logged" : ''))));
            $activityjson["sharedto"]   = $user_id;
            $wkoutUrl                   = '<p><a href="'.URL::site(NULL, 'http') . (isset($sites["slug"]) ? $sites["slug"] : '') . "/index/autoredirect/" . $workoutId . "/" . $encryptedmsgall.'" target="_blank" title="Click here to Approve All the Assignments" style="color: #1b9af7;">Click here to Approve All the Assignments</a></p>';
			$wkoutArray		= $workoutModel->getShareAssignworkoutById($workoutId,$this->globaluser->pk(),$user_id);
			$assign_content = '';
			foreach($wkoutArray as $keys => $values){
				$wkoutTitle = $values['wkout_title'];
				$clientdate = Helper_Common::change_default_date_dob($values['assign_date']);
				$atagUrl 	= URL::site(NULL, 'http') . (isset($sites["slug"]) ? $sites["slug"] : '') . '/index/autoredirect/' . $values['id'] . '/' . $encryptedmessage;
				$assign_content .= '<p><strong> - '.date('l',strtotime($clientdate)).', '.$clientdate.'</strong>	<a href="'.$atagUrl.'" target="_blank" title="Approve this Assignment" style="color: #1b9af7;">Approve this Assignment</a></p><p>&nbsp;</p>';
			}
            $templateArray['body']      = str_replace(array(
				'[Sender_Name]','[Sender_Role]','[Workout_Title]','[Assign_Count]','[Site_Title]','[Assign_Content]','[AcceptLink]'
            ), array(
                ucfirst(strtolower($this->globaluser->user_fname)),$user_Role_name['name'],$wkoutTitle,count($wkoutArray),($sites ? $sites["name"] : ''),$assign_content,$wkoutUrl
            ), $templateArray['body']);
            if (true) {
                $messageArray = array(
                    'subject' => str_replace(array(
                        '[Sender_Name]'
                    ), array(
                        ucfirst(strtolower($this->globaluser->user_fname))
                    ), $templateArray['subject']),
                    'from' => (!empty($templateArray['smtp_replyto']) ? $templateArray['smtp_replyto'] : 'info@myworkouts.com'),
                    'fromname' => (!empty($templateArray['smtp_from']) ? $templateArray['smtp_from'] : 'My workouts'),
                    'to' => $user["user_email"],
                    'replyto' => (!empty($templateArray['smtp_replyto']) ? $templateArray['smtp_replyto'] : 'info@myworkouts.com'),
                    'toname' => ucfirst(strtolower($user['user_fname'])) . ' ' . ucfirst(strtolower($user['user_lname'])),
                    'body' => ORM::factory('admin_smtp')->merge_keywords($templateArray['body'], $this->session->get('current_site_id')),
                    'type' => 'text/html'
                );
                if (is_array($templateArray) && !empty($templateArray) && !empty($templateArray['smtp_id']) && true) {
                    $hostAddress = explode("://", $templateArray['smtp_host']);
                    $emailMailer = Email::dynamicMailer('smtp', array(
                        'hostname' => trim($hostAddress['1']),
                        'port' => $templateArray['smtp_port'],
                        'username' => $templateArray['smtp_user'],
                        'password' => Helper_Common::decryptPassword($templateArray['smtp_pass']),
                        'encryption' => trim($hostAddress['0'])
                    ));
                } else
                    $emailMailer = Email::dynamicMailer('', array());
                Email::sendBysmtp($emailMailer, $messageArray);
            }
            /******************* Activity Feed *********************/
            if ($activityjson) {
                $activity_feed["json_data"] = json_encode($activityjson);
                Helper_Common::createActivityFeed($activity_feed);
            }
            /******************* Activity Feed *********************/
        }
        return true;
    }
    public function _sendShareEmailToUser($workoutId, $site_id, $user_id, $activity_feed)
    {
        $smtpmodel         	= ORM::factory('admin_smtp');
        $shareworkoutmodel 	= ORM::factory('admin_shareworkout');
        if (isset($workoutId) && is_numeric($workoutId) && !empty($workoutId)) {
            $sites          = Helper_Common::hasSiteAccess($site_id);
            $user           = $shareworkoutmodel->getuserdetails($user_id);
            $user           = $user[0];
            $templateArray  = $smtpmodel->getSendingMailTemplate(array(
                'type_name' => 'notification - shared workout','site_id' => $site_id
            ));
            $encryptedmessage = Helper_Common::encryptPassword($user['user_email'] . '####' . $user['security_code'] . '####sharedworkout');
            $activityjson["from_wkout"] = ($activity_feed["feed_type"] == 2 ? "myworkout" : ($activity_feed["feed_type"] == 15 ? "sample" : ($activity_feed["feed_type"] == 13 ? "assigned" : ($activity_feed["feed_type"] == 11 ? "logged" : ''))));
            $activityjson["sharedto"]   = $user_id;
            $wkoutUrl                   = '<p><a href="'.URL::site(NULL, 'http') . (isset($sites["slug"]) ? $sites["slug"] : '') . "/index/autoredirect/" . $workoutId . "/" . $encryptedmessage.'" target="_blank" title="Click here to View this Shared Workout plan" style="color: #1b9af7;">Click here to View this Shared Workout plan</a></p>';
            $templateArray['body']      = str_replace(array(
                '[trainer_name]',
				'[first_name]',
                '[site_title]',
                '[share_workout_plan_link]'
            ), array(
                ucfirst(strtolower($this->globaluser->user_fname)),
				ucfirst(strtolower($user['user_fname'])),
                ($sites) ? $sites["name"] : '',
                $wkoutUrl
            ), $templateArray['body']);
            if (true) {
                $messageArray = array(
                    'subject' => str_replace(array(
                        '[trainer_name]'
                    ), array(
                        ucfirst(strtolower($this->globaluser->user_fname))
                    ), $templateArray['subject']),
                    'from' => (!empty($templateArray['smtp_replyto']) ? $templateArray['smtp_replyto'] : 'info@myworkouts.com'),
                    'fromname' => (!empty($templateArray['smtp_from']) ? $templateArray['smtp_from'] : 'My workouts'),
                    'to' => $user["user_email"],
                    'replyto' => (!empty($templateArray['smtp_replyto']) ? $templateArray['smtp_replyto'] : 'info@myworkouts.com'),
                    'toname' => ucfirst(strtolower($user['user_fname'])) . ' ' . ucfirst(strtolower($user['user_lname'])),
                    'body' => ORM::factory('admin_smtp')->merge_keywords($templateArray['body'], $this->session->get('current_site_id')),
                    'type' => 'text/html'
                );
                if (is_array($templateArray) && !empty($templateArray) && !empty($templateArray['smtp_id']) && true) {
                    $hostAddress = explode("://", $templateArray['smtp_host']);
                    $emailMailer = Email::dynamicMailer('smtp', array(
                        'hostname' => trim($hostAddress['1']),
                        'port' => $templateArray['smtp_port'],
                        'username' => $templateArray['smtp_user'],
                        'password' => Helper_Common::decryptPassword($templateArray['smtp_pass']),
                        'encryption' => trim($hostAddress['0'])
                    ));
                } else
                    $emailMailer = Email::dynamicMailer('', array());
                Email::sendBysmtp($emailMailer, $messageArray);
            }
            /******************* Activity Feed *********************/
            if ($activityjson) {
                $activity_feed["json_data"] = json_encode($activityjson);
                Helper_Common::createActivityFeed($activity_feed);
            }
            /******************* Activity Feed *********************/
        }
        return true;
    }
    public function action_upload()
    {
        $rootdir   = DOCROOT . 'assets/images/dynamic/exercise/';
        $imgdir    = $rootdir . 'img/';
        $thumbdir  = $rootdir . 'thumb/';
        $data      = $_POST['imgData'];
        $filename  = $_POST['imgName'];
        $now       = '_' . strtotime(Helper_Common::get_default_datetime()) . '.png';
        $imgname   = 'img' . $now;
        $thumbname = 'thumb_img' . $now;
        $imgfile   = $imgdir . $imgname;
        $thumbfile = $thumbdir . $thumbname;
        $data      = substr($data, strpos($data, ",") + 1);
        $data      = base64_decode($data);
        if (file_put_contents($imgfile, $data)) {
            $flag = 1;
        } else {
            $flag = 0;
        }
        if ($flag == 1) {
            Image::factory($imgfile)->resize(100, 100, Image::AUTO)->save($thumbfile);
            /*Image::factory($imgfile)
            ->resize(200, 200, Image::AUTO)
            ->save($thumbdir2);*/
        }
        $values = array(
            $flag,
            $thumbname,
            $imgname,
            $filename
        );
        echo json_encode($values);
        die();
    }
    public function action_upload_img()
    {
        if ($this->request->method() == Request::POST) {
            if (isset($_FILES['file'])) {
                $filename = $this->_save_image($_FILES['file']);
            }
        }
    }
    protected function _save_image($image)
    {
        $directory = DOCROOT . 'assets/images/dynamic/exercise/';
        if (!Upload::valid($image) OR !Upload::not_empty($image) OR !Upload::type($image, array(
            'jpg',
            'jpeg',
            'png',
            'gif'
        ))) {
            return FALSE;
        }
        //chmod($directory,0777);
        if ($file = Upload::save($image, NULL, $directory)) {
            $filename = $_FILES['file']['name'];
            Image::factory($file)
            // ->resize(200, 200, Image::AUTO)
                ->save($directory . $filename);
            // Delete the temporary file
            unlink($file);
            return $filename;
        }
        return FALSE;
    }
    public function action_form_process1()
    {
        $responseArray = array();
        $exerciseModel = ORM::factory('exercise');
        // Post the form data
        // =======================
        // For both New or Update,
        // the static form data is 
        // processed here before
        // submitting to database.
        $errors        = array(); // array to hold validation errors
        $data          = array(); // array to pass back data
        $unit_id       = '';
        // Validate the variables
        // ====================== 
        // append any errors to $errors array
        if (empty($_POST['ajax_title'])) {
            $errors['title'] = 'Exercise Title is required.';
        }
        if (empty($_POST['ajax_muscle'])) {
            $errors['musprim'] = 'Primary Muscle is required.';
        }
        if (empty($_POST['ajax_equip'])) {
            $errors['equip'] = 'Equipment is required.';
        }
        //	if (empty($_POST['ajax_type'])) {
        //		  $errors['type'] = 'Exercise Type is required.';
        //	}
        //	if (empty($_POST['ajax_mech'])) {
        //		  $errors['mech'] = 'Exercise Mechanics is required.';
        //	}
        //	if (empty($_POST['ajax_level'])) {
        //		  $errors['level'] = 'Level is required.';
        //	}
        //	if (empty($_POST['ajax_sport'])) {
        //		  $errors['sport'] = 'Sport is required.';
        //	}
        //	if (empty($_POST['ajax_force'])) {
        //		  $errors['force'] = 'Force is required.';
        //	}
        // Return a response 
        // =================
        // if there are any errors
        // in our errors array, 
        // return a success boolean of false
        // ---------------------------------
        if (!empty($errors)) {
            // if there are items
            // in our errors array, 
            // return those errors
            // --------------------------
            $data['success'] = false;
            $data['errors']  = $errors;
        } else {
            // If there are NO validation Errors,
            // ---------------------------------
            // 1) Process the static form data 
            // ###################***********# 
            // General Data
            if (isset($_POST['ajax_unit_id']) && $_POST['ajax_unit_id'] != '') {
                $inputArray['unit_id'] = $unit_id = trim($_POST['ajax_unit_id']);
            }
            $inputArray['title']         = $title = trim($_POST['ajax_title']);
            $inputArray['unit_status']   = $unit_status = trim($_POST['ajax_status']);
            $inputArray['unit_access']   = $unit_access = trim($_POST['ajax_access']);
            // Exercise Data
            $inputArray['feat_img']      = $feat_img = trim($_POST['ajax_feat_image']);
            $inputArray['feat_vid']      = $feat_vid = trim($_POST['ajax_feat_video']);
            $inputArray['unit_type']     = $unit_type = trim($_POST['ajax_type']);
            $inputArray['unit_muscle']   = $unit_muscle = trim($_POST['ajax_muscle']);
            $inputArray['unit_equip']    = $unit_equip = trim($_POST['ajax_equip']);
            $inputArray['unit_mech']     = $unit_mech = trim($_POST['ajax_mech']);
            $inputArray['unit_level']    = $unit_level = trim($_POST['ajax_level']);
            $inputArray['unit_sport']    = $unit_sport = trim($_POST['ajax_sport']);
            $inputArray['unit_force']    = $unit_force = trim($_POST['ajax_force']);
            // Descriptions
            $inputArray['unit_descbr']   = $unit_descbr = trim($_POST['ajax_descbr']);
            $inputArray['unit_descfull'] = $unit_descfull = trim($_POST['ajax_descfull']);
            // Write the data to the database
            // ==============================
            if ($unit_id == "") { // New
                // CASE 1
                // ================
                // CREATE new unit
                // record from Blank
                $res             = $exerciseModel->insertLibraryDetails($inputArray);
                $data['unit_id'] = $res;
            }
            // CASE 2
            // ===================
            // TAB 1 unit_id exists
            elseif ($unit_id !== "") { // Update
                $data['unit_id'] = $unit_id; // Add UNIT_ID var to 
            }
            // 2) Return success array
            // ########################****
            $data['success'] = true;
            $data['message'] = 'Success!';
        }
        // return all our $data
        // to an AJAX call
        echo json_encode($data);
        $this->response->body(json_encode($responseArray));
    }
    function formatBytes($size, $precision = 2)
    {
        $base     = log($size, 1024);
        $suffixes = array(
            '',
            'K',
            'M',
            'G',
            'T'
        );
        return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
    }
    public function compress_imgsize($source, $destination, $quality)
    {
        $info = getimagesize($source);
        if ($info['mime'] == 'image/jpeg')
            $image = imagecreatefromjpeg($source);
        elseif ($info['mime'] == 'image/gif')
            $image = imagecreatefromgif($source);
        elseif ($info['mime'] == 'image/png')
            $image = imagecreatefrompng($source);
        if (imagejpeg($image, $destination, $quality)) {
            return true;
        }
        return false;
    }
    public function action_uploadImg()
    {
        $this->auto_render = FALSE;
        $imagelibrary      = ORM::factory('imagelibrary');
        if ($_GET['action'] == 'upload') {
            if (isset($_POST) && !empty($_POST)) {
                if ($_POST['upfolder'] != 0) {
                    $subfid = $_POST['upfolder'];
                } else {
                    $subfid = $_POST['subfolder'];
                }
                if ($_POST['parentfolder'] != 0) {
                    $fid = $_POST['parentfolder'];
                } else {
                    $fid = $_POST['currfolder'];
                }
                if ($_POST['replaceflag'] == 'replace' && !empty($_POST['imageid'])) {
                    $replaceflag = true;
                } else {
                    $replaceflag = false;
                }
                if (isset($_POST['uploadfrom']) && $_POST['uploadfrom'] == 'template') {
                    $funcprefx  = 'popup';
                    $classprefx = 'mdl_';
                } else {
                    $funcprefx  = '';
                    $classprefx = '';
                }
            }
            // print_r($_FILES['uploadfile']);exit;
            if (isset($_FILES['uploadfile'])) {
                if (!$_FILES['uploadfile']['error']) {
					$imgchecked = Session::instance()->get('imgchecked');
                    $valid_file = true;
                    $rootdir    = DOCROOT . 'assets/images/dynamic/exercise/';
                    $imgdir     = $rootdir . 'img';
                    $thumbdir   = $rootdir . 'thumb';
                    $now        = '_' . (strtotime(Helper_Common::get_default_datetime()) + rand()) . $imgchecked;
                    $file_name  = $_FILES['uploadfile']['name'];
                    $file_size  = $_FILES['uploadfile']['size'];
                    $file_tmp   = $_FILES['uploadfile']['tmp_name'];
                    $file_type  = $_FILES['uploadfile']['type'];
                    $file_ext   = pathinfo($file_name, PATHINFO_EXTENSION);
                    $file_ext   = strtolower($file_ext);
                    $imgname    = 'img' . $now . '.' . $file_ext;
                    $thumbname  = 'thumb_img' . $now . '.' . $file_ext;
                    $imgfile    = $imgdir . '/' . $imgname;
                    $thumbfile  = $thumbdir . '/' . $thumbname;
                    $expensions = array(
                        "jpeg",
                        "jpg",
                        "png"
                    );
                    if (!in_array($file_ext, $expensions)) {
                        $valid_file = false;
                        echo json_encode(array(
                            'success' => false,
                            'divImage' => 'Extension not allowed, please choose JPEG, JPG and PNG file.'
                        ));
                        return;
                    }
                    if ($file_size > (2560000)) { //can't be larger than 2mb
                        $valid_file = false;
                        echo json_encode(array(
                            'success' => false,
                            'divImage' => 'Oops! File\'s size is too large.'
                        ));
                        return;
                    }
                    if ($valid_file) {
						Session::instance()->set('imgchecked', $imgchecked++);
                        if ($file_size > (1536000)) { //if larger than 1.5 kb
                            if ($this->compress_imgsize($file_tmp, $imgfile, 20)) {
                                $file_size = filesize($imgfile);
                                $flag      = 1;
                            } else {
                                $flag = 0;
                            }
                        } elseif ($file_size > (512000)) { //if larger than 500 kb
                            if ($this->compress_imgsize($file_tmp, $imgfile, 30)) {
                                $file_size = filesize($imgfile);
                                $flag      = 1;
                            } else {
                                $flag = 0;
                            }
                        } else { //if lesser than 500 kb
                            if ($this->compress_imgsize($file_tmp, $imgfile, 70)) {
                                $file_size = filesize($imgfile);
                                $flag      = 1;
                            } else {
                                $flag = 0;
                            }
                        }
                        // echo $this->formatBytes($file_size,2);exit;
                        if ($flag == 1) {
                            /*resize for thumb image*/
                            Image::factory($imgfile)->resize(100, 100, Image::AUTO)->save($thumbfile);
                            /*image insertion and ui formation*/
                            if (!empty($imgname) && !empty($file_name)) {
                                $info       = pathinfo($file_name);
                                $file_title = basename($file_name, '.' . $info['extension']);
                                $img_url    = 'assets/images/dynamic/exercise/img/' . $imgname;
                                if ($replaceflag) {
                                    $imglist = $imagelibrary->ReplaceImg($file_title, $img_url, $fid, $subfid, $_POST['imageid']);
                                } else {
                                    $imglist = $imagelibrary->InsertImg($file_title, $img_url, $fid, $subfid);
                                }
                                foreach ($imglist as $key => $value) {
                                    if (!empty($funcprefx)) {
                                        $checkbox = '';
                                    } else {
                                        $checkbox = '<div class="checkbox-checker col-xs-2 col-sm-2" style="display: none;">
											<div class="checkboxcolor">
												<label>
													<input data-role="none" data-ajax="false" type="checkbox" class="checkhidden" name="check_act[]" value="' . $value['img_id'] . '">
													<span class="cr checkbox-circle"><i class="cr-icon fa fa-check"></i></span>
												</label>
											</div>
										</div>';
                                    }
                                    $attribute = 'data-itemid="' . $value['img_id'] . '" data-itemname="' . $value['img_title'] . '" data-itemurl="' . $value['img_url'] . '" data-itemtype="upload"';
                                    echo json_encode(array(
                                        'success' => true,
                                        'divImage' => '<li class="imgRecord" id="' . $value['img_id'] . '">
											<div class="imgRecordDataFrame col-xs-12 col-sm-12">
												<a href="javascript:void(0);" class="col-xs-10 col-sm-10 imgFrame-left" data-ajax="false" data-role="none">
													' . $checkbox . '
													<div class="' . (empty($checkbox) ? 'col-xs-4 col-sm-4' : 'col-xs-3 col-sm-3') . ' ' . $classprefx . 'thumb-img" ' . $attribute . ' onclick="' . $funcprefx . 'triggerImgPrevModal(this);" style="background-image: url(' . URL::base() . $value['img_url'] . ');"></div>
													<div class="' . (empty($checkbox) ? 'col-xs-8 col-sm-8' : 'col-xs-7 col-sm-7') . ' ' . $classprefx . 'img-itemname text-left">
														<div class="altimgtitle break-img-name">' . $value['img_title'] . '</div>
														<div class="img-info">' . $this->formatBytes($file_size, 2) . '&nbsp;&nbsp;' . Helper_Common::UserDateFormat() . '</div>
													</div>
												</a>
												<a href="javascript:void(0);" class="col-xs-2 col-sm-2 imgFrame-right ' . $classprefx . 'upload-imgrow" ' . $attribute . ' onclick="' . $funcprefx . 'triggerImgOptionModal(this);" title="' . __("Options") . '" data-ajax="false" data-role="none">
													<div class="col-sm-12 col-xs-12"><i class="fa fa-chevron-right iconsize2"></i></div>
												</a>
											</div>
										</li>'
                                    ));
                                }
                                unlink($file_tmp);
                                return;
                            } else {
                                echo json_encode(array(
                                    'success' => false,
                                    'divImage' => 'Ooops! Your upload triggered error. Please upload image with valid file name'
                                ));
                                unlink($file_tmp);
                                return;
                            }
                        }
                    } else {
                        unlink($file_tmp);
                    }
                } else {
                    echo json_encode(array(
                        'success' => false,
                        'divImage' => 'Ooops! Your upload triggered the following error: ' . $_FILES['uploadfile']['error']
                    ));
                    return;
                }
            }
        } else {
            echo json_encode(array(
                'success' => true
            ));
        }
        return;
    }
    public function action_printshare()
    {
        $workoutModel                 = ORM::factory('workouts');
        $activityModel                = ORM::factory('activityfeed');
        $exportid_workout             = $_GET['idexport'];
        $type_workout                 = $_GET['workouttype'];
        $fromAdmin                    = (isset($_GET['fromAdmin']) ? $_GET['fromAdmin'] : false);
        $user_id                      = $this->globaluser->pk();
        $site_id                      = ($this->session->get('current_site_id') ? $this->session->get('current_site_id') : '0');
        /******************* Activity Feed *********************/
        $activity_feed                = array();
        $activity_feed["action_type"] = 43;
        $activity_feed["user"]        = $user_id; // user id
        $activity_feed["site_id"]     = $site_id;
        /******************* Activity Feed *********************/
        if ($fromAdmin) {
            $user_id = 0;
        }
        $this->render();
        if (!empty($exportid_workout) && is_numeric($exportid_workout)) {
            if ($type_workout == "logged") {
                $workoutRecord              = $workoutModel->getLoggedworkoutById($exportid_workout);
                $this->template->title      = 'Journal Workout Plan - ' . $workoutRecord['wkout_title'];
                $exerciseRecord             = $workoutModel->getExerciseSets('wkoutlog', $exportid_workout);
                $activity_feed["feed_type"] = 11;
            } elseif ($type_workout == "shared") {
                $workoutRecord              = $workoutModel->getShareworkoutById($user_id, $exportid_workout);
                $this->template->title      = 'Shared Workout Plan - ' . $workoutRecord['wkout_title'];
                $exerciseRecord             = $workoutModel->getExerciseSets('shared', $exportid_workout);
                $activity_feed["feed_type"] = 12;
            } elseif ($type_workout == "sample") {
                $workoutRecord              = $workoutModel->getSampleworkoutById('0', $exportid_workout);
                $this->template->title      = 'Sample Workout Plan - ' . $workoutRecord['wkout_title'];
                $exerciseRecord             = $workoutModel->getSampleExerciseSet($exportid_workout);
                $activity_feed["feed_type"] = 15;
            } elseif ($type_workout == "assigned") {
                $workoutRecord              = $workoutModel->getAssignworkoutById($exportid_workout, $user_id);
                $this->template->title      = 'Assigned Workout Plan - ' . $workoutRecord['wkout_title'];
                $exerciseRecord             = $workoutModel->getExerciseSets('assigned', $exportid_workout);
                $activity_feed["feed_type"] = 13;
            } else {
                $workoutRecord              = $workoutModel->getworkoutById($user_id, $exportid_workout);
                $this->template->title      = 'My Workout Plan - ' . $workoutRecord['wkout_title'];
                $exerciseRecord             = $workoutModel->getExerciseSet($exportid_workout);
                $activity_feed["feed_type"] = 2;
            }
            $activity_feed["type_id"]   = $exportid_workout;
            $activity_feed["json_data"] = json_encode('Print');
            Helper_Common::createActivityFeed($activity_feed);
            $exerciseUnitsDetail = array();
            $temporary           = array();
            if (isset($exerciseRecord) && count($exerciseRecord) > 0) {
                foreach ($exerciseRecord as $keys => $val) {
                    $exerciseUnits = array();
                    if (!empty($val['goal_unit_id']) && !isset($temporary[$val['goal_unit_id']])) {
                        $temporary[$val['goal_unit_id']]                      = $val['goal_unit_id'];
                        $exerciseRecordData                                   = $workoutModel->getExerciseById($val['goal_unit_id']);
                        $exerciseUnits[$val['goal_unit_id'] . '_data']        = $exerciseRecordData;
                        $exerciseUnits[$val['goal_unit_id'] . '_seqdata']     = $workoutModel->getSequencesByUnitId($val['goal_unit_id'], 0, 5);
                        $exerciseUnits[$val['goal_unit_id'] . '_relateddata'] = $workoutModel->getRelatedExercises($val['goal_unit_id'], $exerciseRecordData['musprim_id'], $exerciseRecordData['type_id'], 0, 5);
                        $exerciseUnitsDetail[]                                = $exerciseUnits;
                    }
                }
            }
            // echo "<pre>";print_r($exerciseUnitsDetail);die();
            $this->template->content->workoutRecord       = $workoutRecord;
            $this->template->content->exerciseRecord      = $exerciseRecord;
            $this->template->content->exerciseUnitsDetail = $exerciseUnitsDetail;
            $this->template->content->focusRecord         = $workoutModel->getAllFocus();
            $this->template->content->repetitions         = $workoutModel->getInnerDrive();
        } else {
            $this->redirect('exercise/myworkout');
        }
        $this->template->content->workoutId = trim($exportid_workout);
        $this->template->content->type      = trim($type_workout);
    }
	public function action_myactionplans()
    {
        $this->template->title = 'My Action Calendar';
        $workoutModel          = ORM::factory('workouts');
        $smtpmodel             = ORM::factory('admin_smtp');
        $site_id               = ($this->session->get('current_site_id') ? $this->session->get('current_site_id') : '0');
        $datevalue             = Helper_Common::get_default_datetime();
        $getdate               = $this->request->param('id');
        if (empty($getdate))
            $getdate = $datevalue;
        /** activity feed starts **/
        $activity_feed            = array();
        $activity_feed["user"]    = $this->globaluser->pk();
        $activity_feed["site_id"] = $site_id;
        /** activity feed ends **/
        if (HTTP_Request::POST == $this->request->method()) {
			$typeName     = $this->request->post('typeName');
			$curFlag      = $this->request->post('curFlag');
			$attachId     = $this->request->post('attachId');
			$clickedId    = $this->request->post('clickedId');
			
            $method       = $this->request->post('f_method');
            $save_edit    = $this->request->post('save_edit');
            $workassignid = $this->request->post('wkout_assign_id');
            $worklogid    = $this->request->post('wkout_log_id');
            if (empty($worklogid))
                $worklogid = $this->request->post('wkoutlog_id');
            if ($save_edit == '2')
                $this->redirect('exercise/myactionplans/' . $getdate);
			if(!empty($clickedId) && !empty($typeName)){
				if($typeName == 'logged'){
					$getdate = $workoutModel->getAssignDateByLogId($clickedId);
					if($curFlag=='2'){
						$assignedArray['modified']  = $datevalue;
						$assignedArray['status_id'] = '4'; // deleted
						$workoutModel->updateLoggedWkoutDetails($assignedArray, $clickedId);
						$activity_feed["feed_type"]   = '11';
						$activity_feed["action_type"] = '23';
						$activity_feed["type_id"]     = $clickedId;
						Helper_Common::createActivityFeed($activity_feed);
						$this->session->set('success', 'Successfully updated!!!');
					}else{
						$loggedArray = array();
						$loggedArray['note_wkout_intensity'] = $loggedArray['note_wkout_remarks']   = '';
						$loggedArray['modified']      = $datevalue;
						$loggedArray['wkout_status']  = '2'; // 2 -skipped
						$logxrArray['set_status']     = '2';
						$activity_json_data           = ' as Skipped';
						$activity_feed["action_type"] = '29';
						$message                      = 'Successfully Marked as Skipped!!!';
						$workoutModel->updateLoggedWkoutDetails($loggedArray, $clickedId);
						$workoutModel->updateLoggedWkoutXRDetails($logxrArray, $clickedId);
						$activity_feed["feed_type"] = '7';
						$activity_feed["type_id"]   = $clickedId;
						$activity_feed["json_data"] = json_encode($activity_json_data);
						Helper_Common::createActivityFeed($activity_feed);
						$this->session->set('success', $message);
					}
				}else{
					$changeFlag    = '1'; $assignedArray = array();
					$assignedArray['note_wkout_intensity'] = $assignedArray['note_wkout_remarks']   = '';
					$assignedArray['created']       = $datevalue;
					$assignedArray['wkout_id']      = $clickedId;
					$assignedArray['from_wkout']    = '3';
					$assignedArray['assigned_date'] = $getdate = $workoutModel->getAssignDateById($clickedId);
					$assignedArray['modified_by']   = $this->globaluser->pk();
					$assignedArray['modified']      = $datevalue;
					$assignedArray['associated_assign_id'] = $clickedId;
					$assignedArray['wkout_status'] = '1'; // 1 -completed
					$wkoutLog = $workoutModel->createWkoutLogBywkoutId('assigned', $clickedId, $assignedArray, $this->globaluser->pk());
					$workoutModel->updateMarkedStatus($clickedId, $assignedArray['wkout_status']);
					$activity_feed["feed_type"]   = '13';
					$activity_feed["action_type"] = '6';
					$activity_feed["type_id"]     = $clickedId;
					$activity_feed["json_data"]   = json_encode(array('wkoutlog'=>$wkoutLog,'text'=>' as Completed'));
					Helper_Common::createActivityFeed($activity_feed);
					$this->session->set('success', 'Successfully Marked as Completed!!!');
				}
			}else if ($method == 'assignAdd') {
                $assignArr['wkout_id']            = $this->request->post('selected_wkout_id');
                $assignArr['wkout_sample_id']     = $this->request->post('selected_sampe_id');
                $assignArr['wkout_share_id']      = $this->request->post('selected_share_id');
                $assignArr['wkout_assign_id']     = $this->request->post('selected_wkout_assign_id');
                $assignArr['wkout_assign_dup_id'] = $this->request->post('selected_wkout_assign_dup_id');
                $assignArr['wkout_log_id']        = $this->request->post('selected_log_id');
                if (!empty($assignArr['wkout_id'])) {
                    $assignArr['modified_by']      = $assignArr['assigned_by'] = $assignArr['assigned_for'] = $this->globaluser->pk();
                    $assignArr['assigned_date']   = $getdate = Helper_Common::get_default_date($this->request->post('selected_date'));
                    $assignArr['created']          = $assignArr['modified'] = $datevalue;
                    $assignArr['from_wkout']       = '0';
                    $assignId                      = $workoutModel->addToWkoutAssign($assignArr, $this->globaluser->pk());
                    $activity_feed["feed_type"]    = '13';
                    $activity_feed["action_type"]  = '1';
                    $activity_feed["type_id"]      = $assignId;
                    $activity_feed["context_date"] = Helper_Common::get_default_datetime($assignArr['assigned_date']);
                    Helper_Common::createActivityFeed($activity_feed);
					/*** email -automation Start ***/
					$emailNotifyArray['wkout_assign_id'] = $assignId;
					$emailNotifyArray['triggerby_date']  = Helper_Common::get_default_date($assignArr['assigned_date']);
					$emailNotifyArray['triggerby_time']  = Helper_Common::getUserEmailTime();
					$workoutModel->insertEmailNotify($emailNotifyArray);
					/*** email -automation End ***/
                    $this->session->set('success', 'Workout Plan Assigned Successfully Added!!!');
                    if ($save_edit == 1)
                        $this->redirect('exercise/assignedplan/' . $assignId . '?act=edit&edit=0');
                } elseif (!empty($assignArr['wkout_sample_id'])) {
                    $assignArr['modified_by']      = $assignArr['assigned_by'] = $assignArr['assigned_for'] = $this->globaluser->pk();
                    $assignArr['assigned_date']  = $getdate  = Helper_Common::get_default_date($this->request->post('selected_date'));
                    $assignArr['created']          = $assignArr['modified'] = $datevalue;
                    $assignArr['from_wkout']       = '2';
                    $assignId                      = $workoutModel->addToWkoutAssign($assignArr, $this->globaluser->pk());
                    $activity_feed["feed_type"]    = '13';
                    $activity_feed["action_type"]  = '1';
                    $activity_feed["type_id"]      = $assignId;
                    $activity_feed["context_date"] = Helper_Common::get_default_datetime($assignArr['assigned_date']);
                    Helper_Common::createActivityFeed($activity_feed);
					/*** email -automation Start ***/
					$emailNotifyArray['wkout_assign_id'] = $assignId;
					$emailNotifyArray['triggerby_date']  = Helper_Common::get_default_date($assignArr['assigned_date']);
					$emailNotifyArray['triggerby_time']  = Helper_Common::getUserEmailTime();
					$workoutModel->insertEmailNotify($emailNotifyArray);
					/*** email -automation End ***/
                    $this->session->set('success', 'Workout Plan Assigned Successfully Added!!!');
                    if ($save_edit == 1)
                        $this->redirect('exercise/assignedplan/' . $assignId . '?act=edit&edit=0');
                } elseif (!empty($assignArr['wkout_share_id'])) {
                    $assignArr['modified_by']      = $assignArr['assigned_by'] = $assignArr['assigned_for'] = $this->globaluser->pk();
                    $assignArr['assigned_date']  = $getdate  = Helper_Common::get_default_date($this->request->post('selected_date'));
                    $assignArr['created']          = $assignArr['modified'] = $datevalue;
                    $assignArr['from_wkout']       = '1';
                    $assignId                      = $workoutModel->addToWkoutAssign($assignArr, $this->globaluser->pk());
                    $activity_feed["feed_type"]    = '13';
                    $activity_feed["action_type"]  = '1';
                    $activity_feed["type_id"]      = $assignId;
                    $activity_feed["context_date"] = Helper_Common::get_default_datetime($assignArr['assigned_date']);
                    Helper_Common::createActivityFeed($activity_feed);
					/*** email -automation Start ***/
					$emailNotifyArray['wkout_assign_id'] = $assignId;
					$emailNotifyArray['triggerby_date']  = Helper_Common::get_default_date($assignArr['assigned_date']);
					$emailNotifyArray['triggerby_time']  = Helper_Common::getUserEmailTime();
					$workoutModel->insertEmailNotify($emailNotifyArray);
					/*** email -automation End ***/
                    $this->session->set('success', 'Workout Plan Assigned Successfully Added!!!');
                    if ($save_edit == 1)
                        $this->redirect('exercise/assignedplan/' . $assignId . '?act=edit&edit=0');
                } elseif (!empty($assignArr['wkout_assign_dup_id'])) {
                    $assignArr['wkout_assign_id']  = $assignArr['wkout_assign_dup_id'];
                    $assignArr['modified_by']      = $assignArr['assigned_by'] = $assignArr['assigned_for'] = $this->globaluser->pk();
                    $assignArr['assigned_date']  = $getdate  = Helper_Common::get_default_date($this->request->post('selected_date'));
                    $assignArr['created']          = $assignArr['modified'] = $datevalue;
                    $assignArr['from_wkout']       = '3';
                    $oldAssignedDate               = $workoutModel->getAssignDateById($assignArr['wkout_assign_id']);
                    $activity_feed["context_date"] = Helper_Common::get_default_datetime($oldAssignedDate);
                    $assignId                      = $workoutModel->addToWkoutAssign($assignArr, $this->globaluser->pk());
                    $activity_feed["feed_type"]    = '13';
                    $activity_feed["action_type"]  = '22';
                    $activity_feed["type_id"]      = $assignArr['wkout_assign_id'];
                    $activity_feed["json_data"]    = json_encode(Helper_Common::get_default_datetime($assignArr['assigned_date']));
                    Helper_Common::createActivityFeed($activity_feed);
					/*** email -automation Start ***/
					$emailNotifyArray['wkout_assign_id'] = $assignId;
					$emailNotifyArray['triggerby_date']  = Helper_Common::get_default_date($assignArr['assigned_date']);
					$emailNotifyArray['triggerby_time']  = Helper_Common::getUserEmailTime();
					$workoutModel->insertEmailNotify($emailNotifyArray);
					/*** email -automation End ***/
                    $this->session->set('success', 'Assigned Workout Plan Successfully Duplicated!!!');
                    $this->redirect('exercise/myactionplans/' . $assignArr['assigned_date']);
                } elseif (!empty($assignArr['wkout_assign_id'])) {
                    $assignArr['wkout_assign_id'] = $this->request->post('selected_wkout_assign_id');
                    $oldAssignedDate              = $workoutModel->getAssignDateById($assignArr['wkout_assign_id']);
                    $assignArr['modified_by']     = $this->globaluser->pk();
                    $assignArr['assigned_date']  = $getdate  = Helper_Common::get_default_date($this->request->post('selected_date'));
                    $assignArr['modified']        = Helper_Common::get_default_datetime();
                    $workoutModel->addToReassignWkoutAssign($assignArr);
                    $activity_feed["feed_type"]    = '13';
                    $activity_feed["action_type"]  = '24';
                    $activity_feed["type_id"]      = $assignArr['wkout_assign_id'];
                    $activity_feed["json_data"]    = json_encode(Helper_Common::get_default_datetime($assignArr['assigned_date']));
                    $activity_feed["context_date"] = Helper_Common::get_default_datetime($oldAssignedDate);
                    Helper_Common::createActivityFeed($activity_feed);
					/*** email -automation Start ***/
					$emailNotifyArray['triggerby_date']  = Helper_Common::get_default_date($assignArr['assigned_date']);
					$emailNotifyArray['triggerby_time']  = Helper_Common::getUserEmailTime();
					$workoutModel->updateEmailNotifyByAssignId($oldAssignedDate, $emailNotifyArray);
					/*** email -automation End ***/
                    $this->session->set('success', 'Assigned Plan Re-assigned Successfully!!!');
                } elseif (!empty($assignArr['wkout_log_id'])) {
                    $assignArr['modified_by']      = $assignArr['assigned_by'] = $assignArr['assigned_for'] = $this->globaluser->pk();
                    $assignArr['assigned_date']  = $getdate  = Helper_Common::get_default_date($this->request->post('selected_date'));
                    $assignArr['created']          = $assignArr['modified'] = $datevalue;
                    $assignArr['from_wkout']       = '4';
                    $assignId                      = $workoutModel->addToWkoutAssign($assignArr, $this->globaluser->pk());
                    $activity_feed["feed_type"]    = '11';
                    $activity_feed["action_type"]  = '5';
                    $activity_feed["type_id"]      = $assignId;
                    $activity_feed["context_date"] = Helper_Common::get_default_datetime($assignArr['assigned_date']);
                    Helper_Common::createActivityFeed($activity_feed);
					/*** email -automation Start ***/
					$emailNotifyArray['wkout_assign_id'] = $assignId;
					$emailNotifyArray['triggerby_date']  = Helper_Common::get_default_date($assignArr['assigned_date']);
					$emailNotifyArray['triggerby_time']  = Helper_Common::getUserEmailTime();
					$workoutModel->insertEmailNotify($emailNotifyArray);
					/*** email -automation End ***/
                    $this->session->set('success', 'Workout Plan Assigned Successfully Added!!!');
                    if ($save_edit == 1)
                        $this->redirect('exercise/assignedplan/' . $assignId . '?act=edit&edit=0');
                }
            } elseif ($method == 'add_rating') {
                $rating['unit_id']            = $this->request->post('unit_id');
                $rating['rate_value']         = $this->request->post('slider-1');
                $rating['rate_comments']      = $this->request->post('rating_msg');
                $rating['created_date']       = $rating['modified_date'] = Helper_Common::get_default_datetime();
                $rateId                       = $workoutModel->insertRatingDetails($rating, $this->globaluser->pk());
                $activity_feed["feed_type"]   = '5';
                $activity_feed["action_type"] = '25';
                $activity_feed["type_id"]     = $rating['unit_id'];
                Helper_Common::createActivityFeed($activity_feed);
                $this->session->set('success', 'Rating created successfully!!!');
            } elseif (!empty($worklogid) && ($method == 'add_new_log_start' || $method == 'add_new_log_end')) {
                $loggArray['note_wkout_intensity'] = $this->request->post('slider-1');
                $loggArray['note_wkout_remarks']   = $this->request->post('note_wkout_remarks');
                $loggArray['created']              = $loggArray['modified'] = $datevalue;
                $loggArray['wkout_id']             = $worklogid;
                $loggArray['from_wkout']           = '4';
                $loggArray['assigned_date']    = $getdate    = Helper_Common::get_default_date($this->request->post('selected_date_hidden'));
                $loggArray['modified_by']          = $this->globaluser->pk();
                $wkoutLog                          = $workoutModel->createWkoutLogBywkoutId('wkoutlog', $worklogid, $loggArray, $this->globaluser->pk());
                $activity_feed["feed_type"]        = '11';
                $activity_feed["action_type"]      = '22';
                $activity_feed["type_id"]          = $wkoutLog;
                $activity_feed["context_date"]     = Helper_Common::get_default_datetime($loggArray['assigned_date']);
                Helper_Common::createActivityFeed($activity_feed);
                $this->session->set('success', 'Successfully Duplicated to New Journal!!!');
                if ($save_edit == 1)
                    $this->redirect('exercise/workoutlog/' . $wkoutLog . '?act=edit&edit=0');
                else
                    $this->redirect('exercise/myactionplans/' . $getdate);
            } elseif ($method == 'add_new_log_comp' || $method == 'add_new_log_skip') {
                if (!empty($workassignid)) {
                    $assignedArray['note_wkout_intensity'] = $this->request->post('slider-1');
                    $assignedArray['note_wkout_remarks']   = $this->request->post('note_wkout_remarks');
                    $assignedArray['created']              = $datevalue;
                    $assignedArray['wkout_id']             = $workassignid;
                    $assignedArray['from_wkout']           = '3';
                    $assignedArray['assigned_date']	 = $getdate   = $this->request->post('selected_date');
					if(empty($assignedArray['assigned_date']))
						$assignedArray['assigned_date'] = $getdate   = $workoutModel->getAssignDateById($workassignid);
                    $assignedArray['modified_by']          = $this->globaluser->pk();
                    $assignedArray['modified']             = $datevalue;
					$assignedArray['associated_assign_id'] = $workassignid;
                    if ($method == 'add_new_log_comp') {
                        $assignedArray['wkout_status'] = '1'; // 1 -completed
                        $activity_json_data = ' on ' . $assignedArray['assigned_date'] . ' as Completed';
                        $message            = 'Successfully Marked as Completed!!!';
                    } else {
                        $assignedArray['wkout_status'] = '2'; // 2 -skipped
                        $activity_json_data = ' on ' . $assignedArray['assigned_date'] . ' as Skipped';
                        $message            = 'Successfully Marked as Skipped!!!';
                    }
                    $wkoutLog                     = $workoutModel->createWkoutLogBywkoutId('assigned', $workassignid, $assignedArray, $this->globaluser->pk());
					$workoutModel->updateMarkedStatus($workassignid, $assignedArray['wkout_status']);
                    $activity_feed["feed_type"]   = '11';
                    $activity_feed["action_type"] = '1';
                    $activity_feed["type_id"]     = $wkoutLog;
                    $activity_feed["json_data"]   = json_encode($activity_json_data);
                    Helper_Common::createActivityFeed($activity_feed);
                    $this->session->set('success', $message);
                    if ($save_edit == 1)
                        $this->redirect('exercise/workoutlog/' . $wkoutLog . '?act=edit&edit=0');
                } elseif (!empty($worklogid)) {
                    $loggedArray['note_wkout_intensity'] = $this->request->post('slider-1');
                    $loggedArray['note_wkout_remarks']   = $this->request->post('note_wkout_remarks');
					$getdate = $workoutModel->getAssignDateByLogId($worklogid);
                    $loggedArray['modified']             = $datevalue;
                    if ($method == 'add_new_log_comp') {
                        $loggedArray['wkout_status']  = '1'; // 1 -completed
                        $logxrArray['set_status']     = '1';
                        $activity_json_data           = ' as Completed';
                        $activity_feed["action_type"] = '28';
                        $message                      = 'Successfully Marked as Completed!!!';
                    } else {
                        $loggedArray['wkout_status']  = '2'; // 2 -skipped
                        $logxrArray['set_status']     = '2';
                        $activity_json_data           = ' as Skipped';
                        $activity_feed["action_type"] = '29';
                        $message                      = 'Successfully Marked as Skipped!!!';
                    }
                    $workoutModel->updateLoggedWkoutDetails($loggedArray, $worklogid);
                    $workoutModel->updateLoggedWkoutXRDetails($logxrArray, $worklogid);
                    $activity_feed["feed_type"] = '7';
                    $activity_feed["type_id"]   = $worklogid;
                    $activity_feed["json_data"] = json_encode($activity_json_data);
                    Helper_Common::createActivityFeed($activity_feed);
                    $this->session->set('success', $message);
                }
                $this->redirect('exercise/myactionplans/' . $getdate);
            } elseif ($method == 'add_workout') {
                $methodReq  = $this->request->post('method');
                $user_id    = $this->globaluser->pk();
                $countTotal = $this->request->post('s_row_count_xr');
                if (!empty($methodReq)) {
                    $wkoutArray['from_wkout'] = $this->request->post('from_wkout');
                    $wkoutArray['wkout_id']   = $this->request->post('wrkout_id');
                }
                $wkoutArray['wkout_title']    = $this->request->post('wkout_title');
                $wkoutArray['wkout_color']    = $this->request->post('wrkoutcolor');
                $wkoutArray['wkout_focus']    = $this->request->post('wkout_focus');
                $wkoutArray['wkout_group']    = $wkoutArray['wkout_poa'] = $wkoutArray['wkout_poa_time'] = '0';
                $wkoutArray['wkout_order']    = '1';
                $wkoutArray['status_id']      = '1';
                $wkoutArray['created']        = $wkoutArray['created_date'] = $datevalue;
                $wkoutArray['user_id']        = $user_id;
                $wkoutArray['modified']       = $wkoutArray['modified_date'] = $datevalue;
                $wkoutArray['site_id']        = $site_id;
                $wkoutId                      = $_POST['wkout_id'] = $workoutModel->insertWorkoutDetails($wkoutArray);
                $activity_feed["feed_type"]   = '2';
                $activity_feed["action_type"] = '1';
                $activity_feed["type_id"]     = $wkoutId;
                Helper_Common::createActivityFeed($activity_feed);
                if (isset($_POST['exercise_title']) && !empty($_POST['exercise_title'])) {
                    foreach ($_POST['exercise_title'] as $keys => $values) {
                        if (!empty($values) && trim($values) != '') {
                            $workoutModel->addWorkoutSetFromworkout($_POST, $keys, $this->globaluser->pk());
                            $workoutModel->addLoggedWorkoutSetFromworkout($_POST, $keys, $user_id, $wkoutId);
                        }
                    }
                }
                $this->session->set('success', 'Workout Record Created Successfully!!!');
                if ($save_edit == 1)
                    $this->redirect('exercise/workoutrecord/' . $wkoutId . '?act=edit&edit=0');
                else
                    $this->redirect('exercise/myactionplans/' . $getdate);
            } elseif ($method == 'add_workoutlog') {
                $methodReq  = $this->request->post('method');
                $user_id    = $this->globaluser->pk();
                $countval   = $countvalskip = 0;
                $countTotal = $this->request->post('s_row_count_xr');
                if (!empty($methodReq)) {
                    $loggedArray['from_wkout'] = $this->request->post('from_wkout');
                    $loggedArray['wkout_id']   = $this->request->post('wrkout_id');
                }
                $loggedArray['wkout_title']          = $this->request->post('wkout_title');
                $loggedArray['wkout_color']          = $this->request->post('wrkoutcolor');
                $loggedArray['wkout_focus']          = $this->request->post('wkout_focus');
                $loggedArray['wkout_group']          = $loggedArray['wkout_poa'] = $loggedArray['wkout_poa_time'] = '0';
                $loggedArray['note_wkout_intensity'] = $this->request->post('slider-1');
                $loggedArray['note_wkout_remarks']   = $this->request->post('note_wkout_remarks');
                $loggedArray['created']              = $datevalue;
				if($this->request->post('selected_date') != '')
					$dateVal                         = Helper_Common::get_default_date($this->request->post('selected_date'));
				else if($this->request->post('selected_date_hidden') != '')
					$dateVal = Helper_Common::get_default_date($this->request->post('selected_date_hidden'));
                $loggedArray['assigned_date']  = $getdate      = (!empty($dateVal) ? $dateVal : Helper_Common::get_default_date());
                $loggedArray['modified_by']          = $this->globaluser->pk();
                $loggedArray['modified']             = $datevalue;
                $loggedArray['site_id']              = $site_id;
                $wkoutLog                            = $workoutModel->createWkoutLogByCustom($loggedArray, $user_id);
                $activity_feed["feed_type"]          = '11';
                $activity_feed["action_type"]        = '1';
                $activity_feed["type_id"]            = $wkoutLog;
                Helper_Common::createActivityFeed($activity_feed);
                if (isset($_POST['exercise_title']) && !empty($_POST['exercise_title'])) {
                    foreach ($_POST['exercise_title'] as $keys => $values) {
                        if (!empty($values) && trim($values) != '') {
                            if (isset($_POST['markedstatus'][$keys]) && ($_POST['markedstatus'][$keys] == '1'))
                                $countval += 1;
							if (isset($_POST['markedstatus'][$keys]) && ($_POST['markedstatus'][$keys] == '2'))
                                $countvalskip += 1;
                            $workoutModel->addLoggedWorkoutSetFromworkout($_POST, $keys, $user_id, $wkoutLog);
                        }
                    }
                }
                if ($countTotal == $countval || $countTotal == ($countval + $countvalskip))
                    $workoutModel->updateLoggedWkoutDetails(array(
                        'wkout_status' => '1'
                    ), $wkoutLog);
                elseif ($countTotal == $countvalskip)
                    $workoutModel->updateLoggedWkoutDetails(array(
                        'wkout_status' => '2'
                    ), $wkoutLog);
				elseif ($countTotal > ($countval + $countvalskip))
                    $workoutModel->updateLoggedWkoutDetails(array(
                        'wkout_status' => '3'
                    ), $wkoutLog);
                $this->session->set('success', 'Workout Journal Created Successfully!!!');
                if ($save_edit == 1)
                    $this->redirect('exercise/workoutlog/' . $wkoutLog . '?act=edit&edit=0');
                else
                    $this->redirect('exercise/myactionplans/' . $getdate);
            } elseif ($method == "delete" && !empty($worklogid)) {
                $assignedArray['modified']  = $datevalue;
                $assignedArray['status_id'] = '4'; // deleted
                $workoutModel->updateLoggedWkoutDetails($assignedArray, $worklogid);
                $activity_feed["feed_type"]   = '11';
                $activity_feed["action_type"] = '23';
                $activity_feed["type_id"]     = $worklogid;
                Helper_Common::createActivityFeed($activity_feed);
                $this->session->set('success', 'Successfully deleted from Journal!!!');
				$getdate = $workoutModel->getAssignDateByLogId($worklogid);
                $this->redirect('exercise/myactionplans/' . $getdate);
            } elseif ($method == 'cancel' && !empty($workassignid)) {
                $activity_feed["feed_type"]    = '13';
                $activity_feed["action_type"]  = '2';
                $activity_feed["type_id"]      = $workassignid;
                $oldAssignedDate       = $getdate    = $workoutModel->getAssignDateById($workassignid);
                $activity_feed["context_date"] = Helper_Common::get_default_datetime($oldAssignedDate);
                $workoutModel->updateMarkedStatus($workassignid, '3');
                Helper_Common::createActivityFeed($activity_feed);
				/*** email -automation Start ***/
				$workoutModel->updateEmailNotifyByAssignId($workassignid);
				/*** email -automation End ***/	
                $this->session->set('success', 'Successfully cancelled!!!');
                $this->redirect('exercise/myactionplans/' . $getdate);
            } else if ($method == 'add_new_wkout_from_log') {
                $wkoutId                      = $workoutModel->doCopyForExerciseSetsById('workoutLogToNewWkout', $this->globaluser->pk(), '0', $worklogid, 'copy');
                $activity_feed["feed_type"]   = '2';
                $activity_feed["action_type"] = '1';
                $activity_feed["type_id"]     = $wkoutId;
                Helper_Common::createActivityFeed($activity_feed);
                $this->session->set('success', 'Successfully Added as New Workout!!!');
				$getdate = $workoutModel->getAssignDateByLogId($worklogid);
                $this->redirect('exercise/myactionplans/' . $getdate);
            } elseif ($method == 'add_new_wkout') {
                $wkoutId                      = $workoutModel->doCopyForExerciseSetsById('workoutAssignToNewWkout', $this->globaluser->pk(), '0', $workassignid, 'copy');
                $activity_feed["feed_type"]   = '13';
                $activity_feed["action_type"] = '22';
                $activity_feed["type_id"]     = $workassignid;
                $activity_feed["json_data"]   = json_encode('New Workout');
                Helper_Common::createActivityFeed($activity_feed);
                $this->session->set('success', 'Successfully Added as New Workout!!!');
				$getdate = $workoutModel->getAssignDateById($workassignid);
            } elseif ($method == 'add_new_assign') {
                $methodReq = $this->request->post('method');
                $user_id   = $this->globaluser->pk();
                if ($methodReq != '') {
                    $updateArray['wkout_id']   = $this->request->post('wkout_id');
                    $updateArray['from_wkout'] = $this->request->post('from_wkout');
                }
                $updateArray['wkout_title']    = $this->request->post('wkout_title');
                $updateArray['wkout_color']    = $this->request->post('wrkoutcolor');
                $updateArray['assigned_date'] = $getdate = Helper_Common::get_default_date($this->request->post('selected_date_hidden'));
                $updateArray['wkout_focus']    = $this->request->post('wkout_focus');
                $updateArray['wkout_group']    = $updateArray['wkout_poa'] = $updateArray['wkout_poa_time'] = '0';
                $updateArray['user_id']        = $updateArray['assigned_by'] = $updateArray['modified_by'] = $updateArray['assigned_for'] = $user_id;
                $updateArray['created']        = $updateArray['modified'] = Helper_Common::get_default_datetime();
                $wkout_assign_id               = $workoutModel->addToWkoutAssignCustom($updateArray, $user_id);
                $activity_feed["feed_type"]    = '13';
                $activity_feed["action_type"]  = '1';
                $activity_feed["type_id"]      = $wkout_assign_id;
                $activity_feed["context_date"] = Helper_Common::get_default_datetime($updateArray['assigned_date']);
                Helper_Common::createActivityFeed($activity_feed);
                if (isset($_POST['exercise_title']) && !empty($_POST['exercise_title'])) {
                    foreach ($_POST['exercise_title'] as $keys => $values) {
                        if (!empty($values) && trim($values) != '') {
                            $res = $workoutModel->addAssignWorkoutSetFromworkout($_POST, $keys, $user_id, $wkout_assign_id);
                        }
                    }
                }
				/*** email -automation Start ***/
				$emailNotifyArray['wkout_assign_id'] = $wkout_assign_id;
				$emailNotifyArray['triggerby_date']  = Helper_Common::get_default_date($updateArray['assigned_date']);
				$emailNotifyArray['triggerby_time']  = Helper_Common::getUserEmailTime();
				$workoutModel->insertEmailNotify($emailNotifyArray);
				/*** email -automation End ***/
                $this->session->set('success', 'Assigned Plans Created Successfully!!!');
                if ($save_edit == 1)
                    $this->redirect('exercise/assignedplan/' . $wkout_assign_id . '?act=edit&edit=0');
            } elseif ($method == 'add_tag_to_xr') {
                $add_tag['unit_id']   = $this->request->post('unit_id');
                $add_tag['tag-input'] = $this->request->post('xrtag-input');
                $workoutModel->insertUnitTagById($add_tag['tag-input'], $add_tag['unit_id']);
                $this->session->set('success', 'Tagged Successfully!!!');
            } elseif ($method == 'addLog' || $method == 'addAssign') {
                $addType   = $this->request->post('addtype');
                $wkoutId   = $this->request->post('addid');
                $wkoutDate = $this->request->post('adddate');
                $datevalue = Helper_Common::get_default_datetime();
				$updateArray['wkout_id']    = $wkoutId;
                if ($addType == 'wrkout') {
                    $workoutRecord              = $workoutModel->getworkoutById($this->globaluser->pk(), $wkoutId);
                    $exerciseRecord             = $workoutModel->getExerciseSets('wkout', $wkoutId);
                    $activity_feed["feed_type"] = '2';
                    $updateArray['from_wkout']  = '0';
                } elseif ($addType == 'assigned') {
                    $workoutRecord              = $workoutModel->getAssignworkoutById($wkoutId, $this->globaluser->pk());
                    $exerciseRecord             = $workoutModel->getExerciseSets('assigned', $wkoutId);
                    $activity_feed["feed_type"] = '13';
                    $updateArray['from_wkout']  = '3';
                } elseif ($addType == 'sample') {
                    $workoutRecord              = $workoutModel->getSampleworkoutById('0', $wkoutId);
                    $exerciseRecord             = $workoutModel->getSampleExerciseSet($wkoutId);
                    $activity_feed["feed_type"] = '15';
                    $updateArray['from_wkout']  = '2';
                } elseif ($addType == 'shared') {
                    $workoutRecord              = $workoutModel->getShareworkoutById($this->globaluser->pk(), $wkoutId);
                    $exerciseRecord             = $workoutModel->getExerciseSets('shared', $wkoutId);
                    $activity_feed["feed_type"] = '12';
                    $updateArray['from_wkout']  = '1';
                } elseif ($addType == 'wkoutlog') {
                    $workoutRecord              = $workoutModel->getLoggedworkoutById($wkoutId);
                    $exerciseRecord             = $workoutModel->getExerciseSets('wkoutlog', $wkoutId);
                    $activity_feed["feed_type"] = '11';
                    $updateArray['from_wkout']  = '4';
                }
                $updateArray['wkout_title']    = $workoutRecord['wkout_title'];
                $updateArray['wkout_color']    = $workoutRecord['wkout_color'];
                $updateArray['wkout_focus']    = $workoutRecord['wkout_focus'];
                $updateArray["modified"]       = $datevalue;
                $updateArray['created']        = $datevalue;
                $updateArray['modified_by']    = $updateArray['assigned_by'] = $updateArray['assigned_for'] = $this->globaluser->pk();
                $updateArray['wkout_group']    = $workoutRecord['wkout_group'];
                $updateArray['wkout_order']    = '1';
                $updateArray['status_id']      = '1';
                $updateArray['user_id']        = $this->globaluser->pk();
                $updateArray['created_date']   = $datevalue;
                $updateArray['modified_date']  = $datevalue;
                $updateArray['assigned_date']  = $getdate = Helper_Common::get_default_date($wkoutDate);
                $activity_feed["action_type"]  = '22';
				$activity_feed["type_id"]      = $updateArray['wkout_id'];
                $activity_feed["context_date"] = Helper_Common::get_default_datetime($updateArray['assigned_date']);
                if ($method == 'addLog') {
                    $updateArray['wkout_status']  = '1';
                    if ($addType == 'assigned') {
                        $updateArray['marked_status'] = '1';
                        $workid                       = $workoutModel->createWkoutLogByassignId($wkoutId, $updateArray, false);
                    } else
                        $workid = $workoutModel->createWkoutLogByCustom($updateArray, $this->globaluser->pk());
					$activity_feed["json_data"]    = json_encode(array('wkoutlog'=>$workid));
                } else{
                    $workid = $workoutModel->addToWkoutAssignCustom($updateArray, $this->globaluser->pk());
					$activity_feed["json_data"]    = json_encode(array('wkoutassign'=>$workid));
				}
                Helper_Common::createActivityFeed($activity_feed);
                $count = 0;
                if (isset($exerciseRecord) && count($exerciseRecord) > 0) {
                    foreach ($exerciseRecord as $keys => $values) {
                        if (is_array($values) && !empty($values)) {
                            $values['goal_order'] = $count + 1;
                            if ($method == 'addLog')
                                $values['set_status'] = '1';
                            $values['wkout_id'] = $workid;
                            if ($method == 'addLog')
                                $workoutModel->addLoggedWorkoutSetFromExist($values, $this->globaluser->pk(), $workid);
                            else
                                $workoutModel->addAssignWorkoutSetFromExistworkout($values, $this->globaluser->pk(), $workid);
                        }
                    }
                }
                if ($method == 'addLog') {
                    $this->session->set('success', 'Created Workout Log Successfully!!!');
                } else {
					/*** email -automation Start ***/
					$emailNotifyArray['wkout_assign_id'] = $workid;
					$emailNotifyArray['triggerby_date']  = Helper_Common::get_default_date($updateArray['assigned_date']);
					$emailNotifyArray['triggerby_time']  = Helper_Common::getUserEmailTime();
					$workoutModel->insertEmailNotify($emailNotifyArray);
					/*** email -automation End ***/
                    $this->session->set('success', 'Created Assigned Plan Successfully!!!');
                }
            } elseif ($method == 'share_journal' || $method == 'share_assign') {
				if($method == 'share_journal'){
					$wkout_id   = $this->request->post('share_journal_id');
					$sharetype 	= 'workoutLogToshare';
					$feed_type	= '11';
					$message	= 'Shared Workout Journal Successfully!!!';
				}else{
					$wkout_id   = $this->request->post('share_assign_id');
					$sharetype 	= 'workoutAssignToshare';
					$feed_type	= '13';
					$message	= 'Shared Assigned Workout Plan  Successfully!!!';
				}
                $shared_msg     = $this->request->post('share_msg');
                $selectedUser   = $this->request->post('seletedUser');
                $selectedSite   = $this->request->post('seletedSite');
				$assign_option	= $this->request->post('is_share_assing');
				$assign_dates	= $this->request->post('sharedates');
                $shareworkoutmodel     = ORM::factory('admin_shareworkout');
				$assign_dates_array	   = array();
				if($assign_option == 'on' && !empty($assign_dates)){
					$assign_dates_array = explode(',',$assign_dates);
				}
                $assignArr['user_ids'] = $assignArr['site_ids'] = '';
                if (isset($selectedSite[0]))
                    $assignArr['site_ids'] = explode(',', $selectedSite[0]);
                if (isset($selectedUser[0]))
                    $assignArr['user_ids'] = explode(',', $selectedUser[0]);
                foreach ($assignArr['site_ids'] as $key => $value) {
                    foreach ($assignArr['user_ids'] as $keys => $values) {
                        $allsites = Helper_Common::getAllSiteIdByUser($values);
                        if (in_array($value, $allsites)) {
                            $wkoutShareId   = $workoutModel->doCopyForExerciseSetsById($sharetype, array(
                                'shared_by' => $this->globaluser->pk(),
                                'shared_for' => $values,
                                'shared_msg' => $shared_msg,
                                'site_id' => $value
                            ), '0', $wkout_id, '');
							
							$activity_feed["feed_type"]   = $feed_type;
                            $activity_feed["action_type"] = 7;
                            $activity_feed["user"]        = $this->globaluser->pk();
                            $activity_feed["site_id"]     = $this->session->get('current_site_id');
                            $activity_feed["type_id"]     = $wkout_id;
							
							if(isset($assign_dates_array) && count($assign_dates_array) > 0){
								$assignIds = array();
								foreach($assign_dates_array as $keydate => $valuedate){
									$shareAssign = array();
									$shareAssign['wkout_share_id'] 	 = $wkoutShareId;
									$shareAssign['assigned_user_id'] = $values;
									$shareAssign['assign_date'] 	 = Helper_Common::get_default_date($valuedate);
									$assignIds[$keydate] = $workoutModel->insertShareAssign($shareAssign);
								}
								$this->_sendShareAssignEmailToUser($wkoutShareId, $value, $values, $activity_feed,$assignIds);
							}
							$this->_sendShareEmailToUser($wkoutShareId, $value, $values, $activity_feed);
                        }
                    }
                }
                $this->session->set('success', $message);
            } 
            $this->redirect('exercise/myactionplans/' . $getdate);
        }
        $this->render();
        $this->template->content->getdate = $getdate;
    }
} // End Welcome