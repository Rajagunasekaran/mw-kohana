<?php

defined('SYSPATH') or die('No direct script access.');

class Controller_Search extends Controller {

    public function before() {
        parent::before();
        $uf = (isset($_GET['user_from']) && $_GET['user_from'] != '' ? $_GET['user_from'] : 'front');
        $cp = (isset($_GET['cp']) && $_GET['cp'] != '' ? $_GET['cp'] : '1');
        Session::instance()->set('user_from', $uf);
        Session::instance()->set('user_allow_page', $cp);
    }

    public function __construct(Request $request, Response $response) {
        parent::__construct($request, $response);
    }

    public function action_index() {
        $responseArray = array();
        $action = Arr::get($_GET, 'action');
        echo $query;
        die();
    }

    public function action_getajax() {
        $responseArray = array();
        $action = Arr::get($_GET, 'action');
        $title = Arr::get($_GET, 'title');
        $siteids = Arr::get($_GET, 'siteids');
        $limit = Arr::get($_GET, 'maxRows');
        if (!empty($action) && trim($action) == 'workoutplan') {
            $workoutModel = ORM::factory('workouts');
            $result = $workoutModel->getWorkoutDetails($title, $limit);
            if (isset($result) && !empty($result) && count($result) > 0) {
                foreach ($result as $key => $val) {
                    $responseArray[$key + 1]['weburl'] = URL::base(TRUE) . 'exercise/workoutrecord/' . $val['wkout_id'];
                    $responseArray[$key + 1]['titre'] = $val['wkout_title'];
                    $responseArray[$key + 1]['color'] = $val['color_title'];
                    $responseArray[$key + 1]['id'] = $val['wkout_id'];
                }
            } else {
                $responseArray[1]['weburl'] = 'javascript:void(0)';
                $responseArray[1]['titre'] = 'No records found!!!';
                $responseArray[1]['color'] = '';
                $responseArray[1]['id'] = '';
            }
        } elseif (!empty($action) && trim($action) == 'assingedworkoutplan') {
            $workoutModel = ORM::factory('workouts');
            $result = $workoutModel->getWorkoutDetails($title, $limit);
            if (isset($result) && !empty($result) && count($result) > 0) {
                foreach ($result as $key => $val) {
                    $responseArray[$key + 1]['weburl'] = URL::base(TRUE) . 'exercise/workoutrecord/' . $val['wkout_id'];
                    $responseArray[$key + 1]['titre'] = $val['wkout_title'];
                    $responseArray[$key + 1]['color'] = $val['color_title'];
                    $responseArray[$key + 1]['id'] = $val['wkout_id'];
                }
            } else {
                $responseArray[1]['weburl'] = 'javascript:voir(0)';
                $responseArray[1]['titre'] = 'No records found!!!';
                $responseArray[1]['color'] = '';
                $responseArray[1]['id'] = '';
            }
        } elseif (!empty($action) && trim($action) == 'getusers') {
            $userModel = ORM::factory('user');
            $user = Auth::instance()->get_user();
            $result = $userModel->get_username_bynamesiteId($title, $user->pk(), $limit, $siteids);
            if (isset($result) && !empty($result) && count($result) > 0) {
                foreach ($result as $key => $val) {
                    $responseArray[$key]['text'] = $val['username'];
                    $responseArray[$key]['id'] = $val['id'];
                }
            } else {
                $responseArray['text'] = 'No records found!!!';
                $responseArray['id'] = '0';
            }
        } elseif (!empty($action) && trim($action) == 'getsites') {
            $current_site_id = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
            if (empty($siteids))
                $current_site_id = '';
            $siteModel = ORM::factory('admin_sites');
            $result = $siteModel->get_sites_byname($title, $current_site_id, $limit);
            if (isset($result) && !empty($result) && count($result) > 0) {
                foreach ($result as $key => $val) {
                    $responseArray[$key]['text'] = $val['sitename'];
                    $responseArray[$key]['id'] = $val['id'];
                }
            } else {
                $responseArray['text'] = 'No records found!!!';
                $responseArray['id'] = '0';
            }
        }
        $this->response->body(json_encode($responseArray));
    }

    public function action_getmodelTemplate() {
        $response = '';
        $action = Arr::get($_GET, 'action');
        $method = Arr::get($_GET, 'method');
        $fid = Arr::get($_GET, 'id');
        $foldid = Arr::get($_GET, 'foldid');
        $xrsetid = Arr::get($_GET, 'xrsetid');
        $modelType = Arr::get($_GET, 'modelType');
        $date = Arr::get($_GET, 'date');
        $type = Arr::get($_GET, 'type'); // ''=>wkout , assign => assign_wkout ,log => log_wkout
        $editFlag = Arr::get($_GET, 'editFlag'); //''=>false,true =>true;
        $assignId = Arr::get($_GET, 'assignid');
        $logId = Arr::get($_GET, 'logid');
        $xrId = Arr::get($_GET, 'xrid');
        $ownWkFlag = Arr::get($_GET, 'ownWkFlag'); //''=>false,true =>true;
        $allowTag = Arr::get($_GET, 'allowTag'); //''=>false,true =>true;
        $title = Arr::get($_GET, 'title'); // title of modal
        $goalOrder = Arr::get($_GET, 'goalOrder');
        $datavalues = Arr::get($_GET, 'dataval'); //javascript values - prepopulate json
        $intensity = Arr::get($_GET, 'intensity'); //intensity value
        $remarks = Arr::get($_GET, 'remarks'); //intent remarks
        $fromAdmin = Arr::get($_GET, 'fromAdmin'); //''=>false,true =>true;
        $addOptions = Arr::get($_GET, 'addOptions');
        $requestFrom = Arr::get($_GET, 'requestFrom');
        $actionFrom = Arr::get($_GET, 'actionFrom');
        $showOptions = Arr::get($_GET, 'showOptions');
        $page_id = (Session::instance()->get('user_allow_page') ? Session::instance()->get('user_allow_page') : '1');
        $confirmAction = '';
        if (!empty($title))
            $title = stripslashes($title);
        if ($editFlag)
            $editFlag = true;
        else
            $editFlag = false;
        $workoutModel = ORM::factory('workouts');
        $staticcmsModel = ORM::factory('staticcms');
        $settings_model = ORM::factory('settings');
        $user_timezone = $user_timeformat = $user_dateformat = $user_weight = $user_distance = $user_language = '';
        $user = Auth::instance()->get_user();
        $site_id = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
        $ownWkFlag = (!empty($ownWkFlag) && $ownWkFlag == $user->pk() ? 1 : 0);
        $user_settings = $settings_model->getsettings();
        if (!empty($user_settings) && count($user_settings) > 0) {
            $user_timezone = $user_settings[0]['timezone'];
            $user_timeformat = $user_settings[0]['time_format'];
            $user_dateformat = $user_settings[0]['date_format'];
            $user_weight = $user_settings[0]['Weight'];
            $user_distance = $user_settings[0]['Distance'];
            $user_language = $user_settings[0]['language'];
            $user_xr_variable = (Arr::get($_GET, 'user_from') == 'admin' ? 1 : $user_settings[0]['XRset_extra_variable_flag']);
        }
        if (!empty($action) && trim($action) == 'custommessage') {
            $pgType = ($type != '' ? str_replace('hide_', 'is_', $type) . '_hidden' : '');
            if (!Helper_Common::getAllowAllAccessByUser($page_id, $pgType)) {
                $response = $page_id . '<div class="vertical-alignment-helper"><div class="modal-dialog modal-md"><div class="modal-content aligncenter"><form data-ajax="false" action="" method="post"><div class="modal-body opt-body"><div class="opt-row-detail"><div class="col-xs-12 pointer"><div class="col-xs-12">' . $remarks . '</div></div></div><br><div class="alignleft"><input type="checkbox" name="' . $type . '" value="1" onclick="notifyUpdate(this);" id="' . $type . '"/> <label for="' . $type . '">Don\'t show this dialog again</label></div></div><div class="modal-footer"><button data-dismiss="modal" value="cancel" class="btn btn-default" name="f_method" data-ajax="false" data-role="none">Canel</button><button data-role="none" data-ajax="false" type="button" onclick="confirmPopup(' . "'loggedall'" . ');" class="btn btn-default">ok</button></div></form></div></div></div>';
            }
        } elseif (!empty($action) && trim($action) == 'workoutFolder') {
            $confirmfdAction = Helper_Common::getAllowAllAccessByUser($page_id, 'is_confirm_folder_hidden');
            $response = '<div class="vertical-alignment-helper">
								<div class="modal-dialog modal-md">
								<div class="modal-content">
								<form  data-ajax="false" action="" method="post">
								<div class="modal-header">
									<div class="row">
										<div class="title-header">
											<div class="col-xs-3">
												<a data-role="none" data-ajax="false" href="javascript:void(0);" class="triangle confirm pointers" data-allow="' . ($confirmfdAction ? 'false' : 'true') . '" data-notename="hide_confirm_folder">
													<i class="fa fa-caret-left iconsize"></i>
												</a>
											</div>
											<div class="col-xs-6">
												Folder Record
											</div>
											<div class="col-xs-3 save-icon-button">
												<button data-role="none" data-ajax="false" class="update_folder btn btn-default ' . (isset($fid) && is_numeric($fid) && $method != 'editfolder' && !empty($fid) ? ' datacol' : ' activedatacol') . '" name="f_method_button" ' . (isset($fid) && is_numeric($fid) && !empty($fid) ? 'onclick="return changeFolderName();"' : '') . ' type="submit">ok</button>
											</div>
										</div>
									</div>
								</div>
								<div class="modal-body">
									<div class="row">
										<div class="col-xs-12">
											<div class="col-xs-3">
												<div class="triangle">
													Folder title
												</div>
											</div>
											<div class="col-xs-7 editrecordpoptitle">';
            if (isset($fid) && is_numeric($fid) && !empty($fid)) {
                $result = $workoutModel->getFolderDetails($fid);
                if (isset($result) && !empty($result) && count($result) > 0) {
                    $response .= '<input type="text" id="folder_name" onkeypress="enableSavebutton();" name="folder_name" required="true" class="form-control input-sm" value="' . $result['folder_title'] . '" /><input type="hidden" name="old_folder_name" value="' . $result['folder_title'] . '" /><input type="hidden" name="f_id" value="' . $result['id'] . '" />';
                } else {
                    $response .= '<input onkeypress="enableSavebutton();" value="" id="folder_name" name="folder_name" required="true" class="form-control input-sm"/>';
                }
            } else {
                $response .= '<input value="" id="folder_name" name="folder_name" required="true" class="form-control input-sm"/>';
            }
            $response .= '<input type="hidden" name="f_foldid" value="' . $foldid . '" /><input type="hidden" name="f_method" value="' . $method . '" />
											</div>
											<div class="col-xs-2 save-icon-button">';
            $response .= '<i class="fa fa-times" onclick="' . "$('#folder_name').val('');" . '" style="font-size:30px;"></i>';
            $response .= '</div>
										</div>
									</div>';
            if (isset($fid) && is_numeric($fid) && !empty($fid)) {
                $response .= '<br>
									<hr>
									<div class="row">
										<a data-role="none" data-ajax="false" href="' . URL::base(TRUE) . 'exercise/myworkout/' . $fid . '" >
										<div class="col-xs-12">
											<div class="col-xs-3"><i class="fa fa-folder-open iconsize"></i></div>
											<div class="col-xs-9 activedatacol">Open this folder</div>
										</div>
										</a>
									</div>
								</div>';
            }
            $response .= '<div class="modal-footer">
								   <button data-role="none" data-ajax="false" type="button"  class="btn btn-default" data-dismiss="modal">close</button>
								   <button data-role="none" data-ajax="false" class="btn btn-default update_folder' . (isset($fid) && $method != 'editfolder' && is_numeric($fid) && !empty($fid) ? ' datacol' : ' activedatacol') . '" name="f_method_button" ' . (isset($fid) && is_numeric($fid) && !empty($fid) ? 'onclick="return changeFolderName();"' : '') . ' type="submit">ok</button>
								</div>
								</form>
								</div>
							</div>
						</div>';
        } elseif (!empty($action) && trim($action) == 'workoutColor') {
            $confirmclrAction = Helper_Common::getAllowAllAccessByUser($page_id, 'is_confirm_color_hidden');
            $colorsRecord = $workoutModel->getColors();
            if (!empty($fid))
                $workoutRecord = $workoutModel->getworkoutById($user->pk(), $fid);
            else {
                $workoutRecord = $datavalues;
                $dateval = (empty($date) ? Helper_Common::get_default_date('', 'j-M-Y') : date('j-M-Y', strtotime(trim($date) . " 00:00:00")));
                $wkouttitle = str_replace($dateval, '', $workoutRecord['wkout_title']);
                $workoutRecord['wkout_title'] = trim($wkouttitle);
            }
            $response = '<div class="vertical-alignment-helper">
							<div class="modal-dialog modal-md">
								<div class="modal-content">
								<form onsubmit="return insertInputdata();" data-ajax="false" action="" method="post">
								<div class="modal-header">
									<div class="row">
										<div class="title-header">
											<div class="col-xs-2">
												<a data-role="none" data-ajax="false" data-text="Clicking BACK or CLOSE will discard any changes. Clicking INSERT will apply any content changes. Continue with exiting?" href="javascript:void(0);" class="triangle confirm pointers" data-allow="' . ($confirmclrAction ? 'false' : 'true') . '" data-notename="hide_confirm_color">
													<i class="fa fa-caret-left iconsize"></i>
												</a>
											</div>
											<div class="col-xs-6">
												Workout Record
											</div>
											<div class="col-xs-3 save-icon-button">';
            $response .= '<button data-role="none" data-ajax="false" type="button" class="btn btn-default activedatacol" onclick="insertInputdata();">insert</button>';
            $response .= '</div>
										</div>
									</div>
								</div>
									<div class="modal-body">';
            if (empty($fid)) {
                $response .= '<div class="aligncenter">
												<div class="col-xs-12 errormsg hide" style="color:red;padding-bottom:10px;"></div>
											</div>';
            }
            $response .= '<div class="row">
											<div class="col-xs-12">
												<div class="">Workout Title</div>
											</div>
										 </div>
										 <div class="row">
											<div class="">
												<div class="col-xs-10">';
            if (!empty($fid)) {
                $response .= '<i class="fa fa-pencil-square-o pointers" style="font-size:25px;padding-right:10px;" onclick="' . "$('#wkouttitle').hide(); $('.edit').hide(); $('#wrkoutname').show(); $('.remove').show();$(this).hide();" . '"></i>';
            }
            if (!empty($fid)) {
                $response .= '<span id="wkouttitle" onclick="' . "$('#wkouttitle').hide(); $('#wrkoutname').show(); $('.edit').hide(); $('.remove').show();" . '">' . ucfirst($workoutRecord['wkout_title']) . '</span><textarea style="height:35px;display:none;"  class="form-control" style="display:none" name="wrkoutname" id="wrkoutname">' . ucfirst($workoutRecord['wkout_title']) . '</textarea>';
            } else {
                $response .= '<textarea style="height:35px;" required="true" class="form-control" value="' . ucfirst($workoutRecord['wkout_title']) . '" name="wrkoutname" id="wrkoutname">' . ucfirst($workoutRecord['wkout_title']) . '</textarea><input type="hidden" value="' . ucfirst($workoutRecord['wrkoutcolor']) . '" id="wrkoutcolor" name="wrkoutcolor"><input type="hidden" value="' . ucfirst($workoutRecord['color_title']) . '" id="wrkoutcolortxt" name="wrkoutcolortxt">';
            }
            $response .= '</div><div>';
            $response .= '<i style="font-size:25px;" onclick="' . "$('#wrkoutname').val('');" . '" class="fa fa-times pointers"></i></div>
											</div>
										</div>
										<hr>
										<div class="row"><div class="col-xs-12"><span class="inactivedatacol">Optional color tags</span></div></div><br>';
            if (isset($colorsRecord) && count($colorsRecord) > 0) {
                foreach ($colorsRecord as $keys => $values) {
                    if ($keys % 4 == 0)
                        $response .= '<div class="row"><div class="col-xs-12">';
                    $response .= '<div class="col-xs-3 colormodel"><a data-role="none" data-ajax="false" href="javascript:void(0)" ><i onclick="return selectcolor($(this));" class="colorcircle glyphicon ' . ( isset($workoutRecord['color_title']) && ($values['color_title'] == $workoutRecord['color_title']) ? 'activecircle' : '') . " " . $values['color_title'] . '"><span style="display:none" class="choosenclr">' . $values['color_id'] . '</span></i></a></div>';
                    if ($keys % 4 == 3)
                        $response .= '</div></div><br>';
                }
            }
            $response .= '</div><div class="modal-footer"><button data-allow="' . ($confirmclrAction ? 'false' : 'true') . '" data-role="none" data-notename="hide_confirm_color" data-ajax="false" type="button" class="btn btn-default confirm pointers" data-text="Clicking BACK or CLOSE will discard any changes. Clicking INSERT will apply any content changes. Continue with exiting?" style="margin-right:20px;">close</button><button data-role="none" data-ajax="false" type="button" class="btn btn-default activedatacol" onclick="insertInputdata();">insert</button></div></form></div></div></div>';
        }elseif (!empty($action) && trim($action) == 'exerciseLibrary') {
            $response = ($fromAdmin ? View::factory('templates/admin/exerciselibrary') : View::factory('templates/front/exerciselibrary'));
        } elseif (!empty($action) && trim($action) == 'workoutExercise') {
            if (is_numeric($foldid) && empty($datavalues)) {
                if (!empty($type) && strtolower($type) == 'logged') {
                    $workoutLogArray = $workoutModel->getLoggedworkoutById($logId);
                    if (!empty($foldid))
                        $exerciseRecord = $workoutModel->getExerciseSetDetailsByWkoutLog($logId, $foldid);
                }elseif (!empty($type) && strtolower($type) == 'assigned') {
                    if (!empty($foldid))
                        $exerciseRecord = $workoutModel->getExerciseSetDetailsByAssignWkout($assignId, $foldid);
                }else {
                    if (!empty($fid) || (!empty($foldid) && is_numeric($foldid)))
                        $exerciseRecord = $workoutModel->getExerciseSetDetailsByWorkout($fid, $foldid);
                }
            } else
                $exerciseRecord = $datavalues;
            if ($exerciseRecord['setdetails'] != '')
                $exerciseRecord['setdetails'] = json_decode($exerciseRecord['setdetails'], true);

            $exerciseTitleText = 'Exercise Set Variable';
            $popupTitleArr = array('title' => 'Title', 'remarks' => 'Remarks / Notes', 'time' => 'Time', 'resist' => 'Resistance', 'reps' => 'Repetitions', 'dist' => 'Distance', 'rate' => 'Pace', 'int' => 'Inner Drive', 'angle' => 'Angle', 'rest' => 'Rest After');
            $popupTitle = $popupTitleArr[$method];
            $XRciselib = '';
            if ($method == 'title') {
                $exerciseTitleText = 'Exercise Set Title';
                //if(!empty($fid))
                //$XRciselib =($fromAdmin ? View::factory('templates/admin/exerciselibrary') : View::factory('templates/front/exerciselibrary'));
            } elseif ($method == 'exercisetype')
                $exerciseTitleText = 'Exercise Set Type';
            if (empty($modelType))
                $modelType = 'myModal';
            $response = $XRciselib . '<div class="vertical-alignment-helper ' . ($method == 'title' ? 'hide' : '') . '" ' . ($method == 'title' ? 'id="title-div"' : '') . '>
							<div class="modal-dialog"><div class="modal-content">
							<form onsubmit="return insertExtraToParentHidden(' . "'" . $modelType . "','" . $xrsetid . "'" . ');" data-ajax="false" action="" method="post" id="workoutexercise">
								<div class="modal-header">
									<div class="row">
										<div class="title-header">
											<div class="col-xs-3 aligncenter">
												<a data-role="none" data-ajax="false" href="javascript:void(0);" class="triangle" onclick="closeModelwindow(' . "'" . $modelType . "'" . ');">
													<i class="fa fa-caret-left iconsize"></i>
												</a>
											</div>
											<div class="col-xs-6 aligncenter"><b>' . $exerciseTitleText . '</b></div>
											<div class="col-xs-3 aligncenter">
													<button data-role="none" data-ajax="false" class="btn" onclick="insertExtraToParentHidden(' . "'" . $modelType . "','" . $xrsetid . "'" . ');" name="f_method" style="background-color:#fff" type="button"><i class="fa fa-sign-in" style="font-size:30px;" ></i></button>
												</div>';
            $response .= '</div>
									</div>
									<div class="row">
										<div class="col-xs-12 aligncenter"><b style="font-size:15px;">' . $popupTitle . ($method != 'title' && $method != 'exercisetype' ? '<i class="fa fa-info-circle pointers" style="padding-left:10px;vertical-align: middle;" data-toggle="collapse" data-target="#' . $method . '-collapse"></i>' : '') . '</b></div>
									</div>
								</div>
								<div class="modal-body">';
            $response .= '<div class="col-xs-12 aligncenter error inputnormal hide" style="color:red;">Input field cannot be empty!</div>
												<div class="col-xs-12 aligncenter error unitnormal hide" style="color:red;">Unit cannot be empty!</div>';
            if ($method == 'title') {
                $response .= '<div class="border full">
											<div class="col-xs-8 firstcell borderright">Insert a Record from Exercise Library</div>
											<div class="col-xs-4 secondcell activedatacol">
												<div class="onoffcheckbox">
													<input class="checkboxdrag" name="exerciselib" id="exerciselib" type="checkbox">
												</div>
											</div>
										</div>';
            } elseif ($method != 'title' && $method != 'remarks' && $method != 'exercisetype') {
                $checked = 'checked value="off"';
                $checkedFlag = false;
                $hiddenval = 'off';
                if ($method == 'time' && isset($exerciseRecord['setdetails'][$xrsetid]['primary_time']) && $exerciseRecord['setdetails'][$xrsetid]['primary_time']) {
                    $checked = 'checked value="on"';
                    $checkedFlag = true;
                } elseif ($method == 'resist' && isset($exerciseRecord['setdetails'][$xrsetid]['primary_resist']) && $exerciseRecord['setdetails'][$xrsetid]['primary_resist']) {
                    $checked = 'checked value="on"';
                    $checkedFlag = true;
                } elseif ($method == 'reps' && isset($exerciseRecord['setdetails'][$xrsetid]['primary_reps']) && $exerciseRecord['setdetails'][$xrsetid]['primary_reps']) {
                    $checked = 'checked value="on"';
                    $checkedFlag = true;
                } elseif ($method == 'dist' && isset($exerciseRecord['setdetails'][$xrsetid]['primary_dist']) && $exerciseRecord['setdetails'][$xrsetid]['primary_dist']) {
                    $checked = 'checked value="on"';
                    $checkedFlag = true;
                } elseif ($method == 'rate' && isset($exerciseRecord['setdetails'][$xrsetid]['primary_rate']) && $exerciseRecord['setdetails'][$xrsetid]['primary_rate']) {
                    $checked = 'checked value="on"';
                    $checkedFlag = true;
                } elseif ($method == 'int' && isset($exerciseRecord['setdetails'][$xrsetid]['primary_int']) && $exerciseRecord['setdetails'][$xrsetid]['primary_int']) {
                    $checked = 'checked value="on"';
                    $checkedFlag = true;
                } elseif ($method == 'angle' && isset($exerciseRecord['setdetails'][$xrsetid]['primary_angle']) && $exerciseRecord['setdetails'][$xrsetid]['primary_angle']) {
                    $checked = 'checked value="on" ';
                    $checkedFlag = true;
                } elseif ($method == 'rest' && isset($exerciseRecord['setdetails'][$xrsetid]['primary_rest']) && $exerciseRecord['setdetails'][$xrsetid]['primary_rest']) {
                    $checked = 'checked value="on" ';
                    $checkedFlag = true;
                }
                if ($checkedFlag)
                    $hiddenval = 'on';
                $response .= '<div class="border full">
											<div class="col-xs-8 firstcell borderright">Assign as Main priority for this set</div>
											<div class="col-xs-4 secondcell activedatacol">
												<div class="onoffcheckbox">
													<input class="checkboxdrag" id="' . $method . '" ' . $checked . ' type="checkbox">
												</div>
											</div>
										</div><input id="' . $method . '_hidden" name="' . $method . '_hidden" type="hidden" value="' . $hiddenval . '">';
            }
            if ($method == 'title') {
                $response .= '<div class="row"><div class="mobpadding"><div class="border borderbottom"><div class="col-xs-11"><div id="img-preview-pop" class="col-xs-2">' . (isset($exerciseRecord['img_url']) && !empty($exerciseRecord['img_url']) && false ? '<img src="' . $exerciseRecord['img_url'] . '" width="50px;"/>' : '<i style="font-size:50px;" class="fa fa-file-image-o datacol"></i>') . '</div><div class="col-xs-10"><input value="' . (isset($exerciseRecord['goal_title']) ? $exerciseRecord['goal_title'] : '') . '" id="exercise_title" name="exercise_title" required="true" class="form-control input-lg" style="width:100%"/></div></div><input type="hidden" id="exercise_unit" name="exercise_unit" value="' . (isset($exerciseRecord['goal_unit_id']) && false ? $exerciseRecord['goal_unit_id'] : '0') . '"/><input type="hidden" id="exercise_unit_img" name="exercise_unit_img" value="' . (isset($exerciseRecord['img_url']) ? $exerciseRecord['img_url'] : '') . '"/>';
                $response .= '<div><a data-role="none" data-ajax="false" href="javascript:void(0)" onclick="clearInputField(' . "'exercise_title'" . ');"><i class="fa fa-times"></i></a></div>';
                $response .= '</div></div></div>';
            } elseif ($method == 'resist') {
                $response .= '<div class="row"><div class="mobpadding"><div class="border borderbottom"><div class="col-xs-2 aligncenter"><i id="incIcon" class="fa fa-chevron-up iconsize pointers" onclick="increment(' . "'exercise_resistance'" . ');"></i></div><div class="col-xs-6"><input type="type" readonly="readonly" step="any" min="0" value="' . (isset($exerciseRecord['setdetails'][$xrsetid]['goal_resist']) && !empty($exerciseRecord['setdetails'][$xrsetid]['goal_resist']) && is_numeric($exerciseRecord['setdetails'][$xrsetid]['goal_resist']) && trim($exerciseRecord['setdetails'][$xrsetid]['goal_resist']) > 0 ? $exerciseRecord['setdetails'][$xrsetid]['goal_resist'] : '') . '" id="exercise_resistance" name="exercise_resistance" required="true" class="onlynumber numeric form-control input-lg" style="width:100%"/></div><div class="col-xs-2 aligncenter"><i id="decIcon" class="fa fa-chevron-down iconsize pointers" onclick="decrement(' . "'exercise_resistance'" . ');"></i></div>';
                $response .= '<div class="col-xs-2 aligncenter"><a data-role="none" data-ajax="false" href="javascript:void(0)" onclick="clearInputField(' . "'exercise_resistance'" . ');"><i class="fa fa-times"></i></a></div></div>';
                $response .= '<div class="border borderbottom"><div class="col-xs-3 borderright">Units</div><div class="drop down col-xs-12"><label id="dropdown"><select name="exercise_unit_resistance" id="exercise_unit_resistance" class="activedatacol dropdown"><option ' . ((!isset($exerciseRecord['setdetails'][$xrsetid]['goal_resist_id']) || empty($exerciseRecord['setdetails'][$xrsetid]['goal_resist_id'])) && empty($user_weight) ? "selected" : '') . ' value="0">choose</option>';
                $resistance = $workoutModel->getunitsbytable('set_resist');
                if (isset($resistance) && count($resistance) > 0) {
                    foreach ($resistance as $keys => $values) {
                        $response .= '<option ' . (isset($exerciseRecord['setdetails'][$xrsetid]['goal_resist_id']) && !empty($exerciseRecord['setdetails'][$xrsetid]['goal_resist_id']) && ($values['resist_id'] == $exerciseRecord['setdetails'][$xrsetid]['goal_resist_id']) ? "selected" : (!empty($user_weight) && ($user_weight == $values['resist_id']) ? 'selected' : '')) . ' value="' . $values['resist_id'] . '">' . strtolower($values['resist_title']) . '</option>';
                    }
                }
                $response .= "</select></label></div></div>";
                $response .= '</div></div>';
                $response .= '<div id="' . $method . '-collapse" class="collapse row"><div class="mobpadding"><div class="border borderbottom">
											<div class="col-xs-12">
											<h3>Resistance</h3>
											<p>This referes to the measurable performance "load" factor for this particular exercise set. It is measured by the following sub-variable options:</p><ul class="xrtooltip"><li class="xrtooltiplist">Weight in kg (enter ##)</li><li class="xrtooltiplist">Weight in lb (enter ##)<li><li class="xrtooltiplist">Intensity Setting (enter ##)</li></ul><p>Commonly available as additional resistance on:</p><ul class="xrtooltip"><li class="xrtooltiplist">Elliptical cross-trainer</li><li class="xrtooltiplist">Rowing machine</li><li class="xrtooltiplist">Stationary or recumbent bike</li></ul><p>Other Available in case exercise set might be using equipment without clearly defined resistance measurements. Include any further details in the "remarks" section for this exercise set, including :</p><ul class="xrtooltip"><li class="xrtooltiplist">Elastic bands or cords</li><li class="xrtooltiplist">Ropes</li>
											</ul></div></div></div></div>';
            } elseif ($method == 'reps') {
                $response .= '<div class="row"><div class="mobpadding"><div class="border borderbottom"><div class="col-xs-2 aligncenter"><i id="incIcon" class="fa fa-chevron-up iconsize pointers" onclick="increment(' . "'exercise_repetitions'" . ');"></i></div><div class="col-xs-6"><input type="text" readonly="readonly" step="any" min="0" value="' . (isset($exerciseRecord['setdetails'][$xrsetid]['goal_reps']) && !empty($exerciseRecord['setdetails'][$xrsetid]['goal_reps']) && is_numeric($exerciseRecord['setdetails'][$xrsetid]['goal_reps']) && trim($exerciseRecord['setdetails'][$xrsetid]['goal_reps']) > 0 ? $exerciseRecord['setdetails'][$xrsetid]['goal_reps'] : '') . '" id="exercise_repetitions" name="exercise_repetitions" required="true" class="form-control onlynumber numeric input-lg" style="width:100%"/></div><div class="col-xs-2 aligncenter"><i id="decIcon" class="fa fa-chevron-down iconsize pointers" onclick="decrement(' . "'exercise_repetitions'" . ');"></i></div>';
                $response .= '<div class="col-xs-2 aligncenter"><a data-role="none" data-ajax="false" href="javascript:void(0)" onclick="clearInputField(' . "'exercise_repetitions'" . ');" ><i class="fa fa-times"></i></a></div>';
                $response .= '</div></div></div>';
                $response .= '<div id="' . $method . '-collapse" class="collapse row"><div class="mobpadding"><div class="border borderbottom">
											<div class="col-xs-12">
											<h3>Repetitions</h3>
											<p>This refers to the count of completed cycles intended (or performed) for the selected exercise, movement or pose for this particular exercise set. Repetitions are commonly referred to as "reps".</p><p>Example:</p><p>Five (5) jumps = 5 repetitions of the exercise "Jumps"</p><p>NOTE: To set REPETITIONS as your PRIMARY focus for this exercise set, switch on the switch for "Assign as Main priority for this set".</p></div></div></div></div>';
            } elseif ($method == 'time') {
                $parameter = "24:00:00";
                if ((isset($exerciseRecord['setdetails'][$xrsetid]['goal_time_hh']) && $exerciseRecord['setdetails'][$xrsetid]['goal_time_hh'] > 0) || (isset($exerciseRecord['setdetails'][$xrsetid]['goal_time_mm']) && $exerciseRecord['setdetails'][$xrsetid]['goal_time_mm'] > 0) || (isset($exerciseRecord['setdetails'][$xrsetid]['goal_time_ss']) && $exerciseRecord['setdetails'][$xrsetid]['goal_time_ss']) > 0) {
                    $parameter = substr(sprintf("%02d", $exerciseRecord['setdetails'][$xrsetid]['goal_time_hh']), 0, 2) . ':' . substr(sprintf("%02d", $exerciseRecord['setdetails'][$xrsetid]['goal_time_mm']), 0, 2) . ':' . substr(sprintf("%02d", $exerciseRecord['setdetails'][$xrsetid]['goal_time_ss']), 0, 2);
                }
                $response .= '<div class="row"><div class="mobpadding"><div class="border full">';
                $response .= '<div class="col-xs-10"><div class="input-group bootstrap-timepicker timepicker"><input tabindex="1" type="text" value="' . $parameter . '" id="exercise_time" required="true"  name="exercise_time" readonly="readonly" class="form-control input-sm time-picker1" placeholder="HH:mm:ss"/><span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span></div></div>';
                $response .= '<div class="col-xs-2 aligncenter"><a data-role="none" data-ajax="false" href="javascript:void(0)" onclick="clearInputField(' . "'exercise_time'" . ');"><i class="fa fa-times"></i></a></div>';
                $response .= '</div></div></div>';
                $response .= '<div id="' . $method . '-collapse" class="collapse row"><div class="mobpadding"><div class="border borderbottom">
											<div class="col-xs-12">
											<h3>Time</h3>
											<p>This refers to the total duration intended (or performed) for the selected exercise, movement or pose for this particular exercise set.</p><p>Example1:</p><p><strong>Push Ups :</strong> 00:01:00 for a set of "push ups" = 1 minute of performing the "push up" exercise movement. Afterwards, users can still document how many repetitions they performed in the 1 minute period for this exercise set.</p><p>Example2:</p><p><strong>Run on a Treadmill :</strong> You may not be sure how far you intend to run, but you know you are focused to complete a 15 minute run. Afterwards, users can still document the distance, average pace, etc they performed in the 15 minute period for this exercise set.</p></div></div></div></div>';
            } elseif ($method == 'dist') {
                $response .= '<div class="row"><div class="mobpadding"><div class="border borderbottom"><div class="col-xs-2 aligncenter"><i id="incIcon" class="fa fa-chevron-up iconsize pointers" onclick="increment(' . "'exercise_distance'" . ');"></i></div><div class="col-xs-6"><input type="type" step="any" min="0" value="' . (isset($exerciseRecord['setdetails'][$xrsetid]['goal_dist']) && !empty($exerciseRecord['setdetails'][$xrsetid]['goal_dist']) && is_numeric($exerciseRecord['setdetails'][$xrsetid]['goal_dist']) && trim($exerciseRecord['setdetails'][$xrsetid]['goal_dist']) > 0 ? $exerciseRecord['setdetails'][$xrsetid]['goal_dist'] : '') . '" id="exercise_distance" name="exercise_distance" readonly="readonly" required="true" class="form-control onlynumber numeric input-lg" style="width:100%"/></div><div class="col-xs-2 aligncenter"><i id="decIcon" class="fa fa-chevron-down iconsize pointers" onclick="decrement(' . "'exercise_distance'" . ');"></i></div><div class="col-xs-2 aligncenter"><a data-role="none" data-ajax="false" href="javascript:void(0)" onclick="clearInputField(' . "'exercise_distance'" . ');"><i class="fa fa-times"></i></a></div></div>';
                $response .= '<div class="border full"><div class="col-xs-3 borderright">Units</div><div class="drop down col-xs-12"><label id="dropdown"><select name="exercise_unit_distance" id="exercise_unit_distance" class="activedatacol dropdown"><option ' . ((!isset($exerciseRecord['setdetails'][$xrsetid]['goal_dist_id']) || empty($exerciseRecord['setdetails'][$xrsetid]['goal_dist_id'])) && empty($user_distance) ? "selected" : '') . ' value="0">choose</option>';
                $repetitions = $workoutModel->getunitsbytable('set_dist');
                if (isset($repetitions) && count($repetitions) > 0) {
                    foreach ($repetitions as $keys => $values) {
                        $response .= '<option ' . (isset($exerciseRecord['setdetails'][$xrsetid]['goal_dist_id']) && !empty($exerciseRecord['setdetails'][$xrsetid]['goal_dist_id']) && ($values['dist_id'] == $exerciseRecord['setdetails'][$xrsetid]['goal_dist_id']) ? 'selected' : (!empty($user_distance) && ($user_distance == $values['dist_id']) ? "selected" : '')) . ' value="' . $values['dist_id'] . '">' . strtolower($values['dist_title']) . '</option>';
                    }
                }
                $response .= "</select></label></div></div>";
                $response .= '</div></div>';
                $response .= '<div id="' . $method . '-collapse" class="collapse row"><div class="mobpadding"><div class="border borderbottom">
											<div class="col-xs-12">
											<h3>Distance</h3>
											<p>This refers to the total length, height, or distance intended (or performed) for the selected exercise, movement or pose for this particular exercise set.</p> <p>Example1:</p> <p><strong>Box Jumps :</strong> coach may specify the height of the box setting, such as 60cm. Distance can be used in combination with other exercise set variables, such as "repetitions".</p> <p>Example2:</p> <p><strong>Run on a Treadmill :</strong> Pre-determine the distance for a run, such as 5km.</p> </div></div></div></div>';
            } elseif ($method == 'rate') {
                $response .= '<div class="row"><div class="mobpadding"><div class="border borderbottom"><div class="col-xs-2 aligncenter"><i id="incIcon" class="fa fa-chevron-up iconsize pointers" onclick="increment(' . "'exercise_rate'" . ');"></i></div><div class="col-xs-6"><input type="type" readonly="readonly" step="any" min="0" value="' . (isset($exerciseRecord['setdetails'][$xrsetid]['goal_rate']) && !empty($exerciseRecord['setdetails'][$xrsetid]['goal_rate']) && is_numeric($exerciseRecord['setdetails'][$xrsetid]['goal_rate']) && trim($exerciseRecord['setdetails'][$xrsetid]['goal_rate']) > 0 ? $exerciseRecord['setdetails'][$xrsetid]['goal_rate'] : '') . '" id="exercise_rate" name="exercise_rate" required="true" class="form-control onlynumber numeric input-lg" style="width:100%"/></div><div class="col-xs-2 aligncenter"><i id="decIcon" class="fa fa-chevron-down iconsize pointers" onclick="decrement(' . "'exercise_rate'" . ');"></i></div><div class="col-xs-2 aligncenter"><a data-role="none" data-ajax="false" href="javascript:void(0)" onclick="clearInputField(' . "'exercise_rate'" . ');"><i class="fa fa-times"></i></a></div></div>';
                $response .= '<div class="border full"><div class="col-xs-3 borderright">Units</div><div class="drop down col-xs-12"><label id="dropdown"><select name="exercise_unit_rate" id="exercise_unit_rate" class="activedatacol dropdown"><option value="0" ' . (!isset($exerciseRecord['setdetails'][$xrsetid]['goal_rate_id']) || empty($exerciseRecord['setdetails'][$xrsetid]['goal_rate_id']) ? "selected" : '') . '>choose</option>';
                $repetitions = $workoutModel->getunitsbytable('set_rate');
                if (isset($repetitions) && count($repetitions) > 0) {
                    foreach ($repetitions as $keys => $values) {
                        $response .= '<option ' . (isset($exerciseRecord['setdetails'][$xrsetid]['goal_rate_id']) && ($values['rate_id'] == $exerciseRecord['setdetails'][$xrsetid]['goal_rate_id']) ? "selected" : '') . ' value="' . $values['rate_id'] . '">' . strtolower($values['rate_title']) . '</option>';
                    }
                }
                $response .= "</select></label></div></div>";
                $response .= '</div></div>';
                $response .= '<div id="' . $method . '-collapse" class="collapse row"><div class="mobpadding"><div class="border borderbottom">
											<div class="col-xs-12">
											<h3>Pace</h3>
											<p>This refers to the measurable performance <em>pace</em> or <em>speed</em> factor for this particular exercise set. It is measured by the following sub-variable options:</p><ul class="xrtooltip"><li class="xrtooltiplist">Speed in rpm (enter ##)</li> <li class="xrtooltiplist">Speed in spm (enter ##)</li> <li class="xrtooltiplist">Speed in bpm (enter ##)</li> <li class="xrtooltiplist">Speed in lap/min (enter ##)</li> <li class="xrtooltiplist">Speed in km/h (enter ##)</li> <li class="xrtooltiplist">Speed in mi/h (enter ##)</li></ul></div></div></div></div>';
            } elseif ($method == 'int') {
                $response .= '<div class="row"><div class="mobpadding"><div class="border borderbottom"><div class="col-xs-12 drop down selectToUISlider">
						<div class="sliderview"><a href="javascript:void(0);" tabindex="0" id="handle_speed" class="ui-slider-handle ui-state-default ui-corner-all ui-state-hover" role="slider" aria-labelledby="label_handle_speed" aria-valuemin="0" style="left: 25%;"><span class="ui-slider-tooltip ui-widget-content ui-corner-all"><span class="ttContent"></span></span></a></div><input oninput="showval(this.value);" onchange="showval(this.value);" type="range" class="exercise_innerdrive" name="exercise_innerdrive" id="exercise_innerdrive" value="' . (isset($exerciseRecord['setdetails'][$xrsetid]['goal_int_id']) ? $exerciseRecord['setdetails'][$xrsetid]['goal_int_id'] : '0') . '" min="0" max="19"><select id="innerdrive" class="hide"><option value="0">Select</option>';
                $repetitions = $workoutModel->getInnerDrive();
                if (isset($repetitions) && count($repetitions) > 0) {
                    foreach ($repetitions as $keys => $values) {
                        $response .= '<option value="' . $values['int_id'] . '">' . strtolower($values['int_grp_title']) . '(' . strtolower($values['int_opt_title']) . ')' . '</option>';
                    }
                }
                $response .= '</select></div></div></div></div>';
                $response .= '<div id="' . $method . '-collapse" class="collapse row"><div class="mobpadding"><div class="border full">
											<div class="col-xs-12">
											<h3>Perceived Intensity</h3>
											<p>Often misinterpreted, PI is intended to refer to the <em>self-perceived</em> consideration of 2 factors:</p><ul class="xrtooltip"> <li class="xrtooltiplist">Physical Exertion</li> <li><ul> <li class="xrtooltiplist">How the exercise effected me.</li> </ul></li> <li class="xrtooltiplist">Mental Intensity</li> <li><ul> <li class="xrtooltiplist">How I affected the exercise set.</li> </ul></li></ul><p> For ease and speed of documentation and tracking purposes, Perceived Intensity is simply scored on a 1 –10 scale with the option to select a .5 scoring (for loosely Dzindecisivedz scoring):</p> <ul class="xrtooltip"> <li class="xrtooltiplist">Super Easy</li> <li class="xrtooltiplist">Rather Easy</li><li class="xrtooltiplist">Easy</li> <li class="xrtooltiplist">Fairly Lite</li> <li class="xrtooltiplist">Moderate</li> <li class="xrtooltiplist">Warming Up</li> <li>Feeling it a Bit</li> <li>Rather Challenging</li> <li class="xrtooltiplist">Intense!</li> <li class="xrtooltiplist">Nearly Impossible!</li> </ul>
											</div></div></div></div>';
                $response .= '<script>$(document).ready(function (){ $(".ttContent").text(document.getElementById("innerdrive").options[$("#exercise_innerdrive").val()].text).focus();});function showval(val){$(".ttContent").text(document.getElementById("innerdrive").options[val].text);}</script>';
            } elseif ($method == 'angle') {
                $parameter = '';
                if (isset($exerciseRecord['setdetails'][$xrsetid]['goal_angle_id']) && is_numeric($exerciseRecord['setdetails'][$xrsetid]['goal_angle']) && $exerciseRecord['setdetails'][$xrsetid]['goal_angle_id'] > 0 && $exerciseRecord['setdetails'][$xrsetid]['goal_angle'] > 0) {
                    $parameter = $exerciseRecord['setdetails'][$xrsetid]['goal_angle'];
                }
                $response .= '<div class="row"><div class="mobpadding"><div class="border borderbottom"><div class="col-xs-2 aligncenter"><i id="incIcon" class="fa fa-chevron-up iconsize pointers" onclick="increment(' . "'exercise_angle'" . ');"></i></div><div class="col-xs-6"><input type="type" readonly="readonly" step="any" min="0" value="' . $parameter . '" id="exercise_angle" name="exercise_angle" required="true" class="form-control input-lg onlynumber numeric" style="width:100%"/></div><div class="col-xs-2 aligncenter"><i id="decIcon" class="fa fa-chevron-down iconsize pointers" onclick="decrement(' . "'exercise_angle'" . ');"></i></div>';
                $response .= '<div class="col-xs-2 aligncenter"><a data-role="none" data-ajax="false" href="javascript:void(0)" onclick="clearInputField(' . "'exercise_angle'" . ');"><i class="fa fa-times"></i></a></div></div>';
                $response .= '<div class="border full"><div class="col-xs-3 borderright">Units</div><div class="col-xs-9 drop down"><label id="dropdown"><select name="exercise_unit_angle" id="exercise_unit_angle" class="activedatacol dropdown"><option ' . (!isset($exerciseRecord['setdetails'][$xrsetid]['goal_angle_id']) || empty($exerciseRecord['setdetails'][$xrsetid]['goal_angle_id']) ? "selected" : '') . ' value="0">choose</option>';
                $angles = $workoutModel->getunitsbytable('set_angle');
                if (isset($angles) && count($angles) > 0) {
                    foreach ($angles as $keys => $values) {
                        $response .= '<option ' . (isset($exerciseRecord['setdetails'][$xrsetid]['goal_angle_id']) && ($values['angle_id'] == $exerciseRecord['setdetails'][$xrsetid]['goal_angle_id']) ? "selected" : '') . ' value="' . $values['angle_id'] . '">' . '% ' . strtolower($values['angle_title']) . '</option>';
                    }
                }
                $response .= "</select></label></div></div>";
                $response .= '</div></div>';
                $response .= '<div id="' . $method . '-collapse" class="collapse row"><div class="mobpadding"><div class="border borderbottom">
											<div class="col-xs-12">
											<h3>Angle</h3>
											<p>This variable  is intended to refer to a measurable factor for the angle of the platform. Angle settings are commonly available on treadmills, but also might be considered when performing up- or down-hill sprints. It is measured by the following sub-variable options:</p> <ul class="xrtooltip"> <li class="xrtooltiplist">incline % (enter ##)</li> <li class="xrtooltiplist">decline % (enter ##)</li> </ul></div></div></div></div>';
            } elseif ($method == 'rest') {
                $parameter = '';
                if (isset($exerciseRecord['setdetails'][$xrsetid]['goal_rest_mm']) && isset($exerciseRecord['setdetails'][$xrsetid]['goal_rest_ss']) && $exerciseRecord['setdetails'][$xrsetid]['goal_rest_mm'] + $exerciseRecord['setdetails'][$xrsetid]['goal_rest_ss'] > 0) {
                    if ($exerciseRecord['setdetails'][$xrsetid]['goal_rest_mm'] > 0 || $exerciseRecord['setdetails'][$xrsetid]['goal_rest_ss'] > 0) {
                        $parameter .= $exerciseRecord['setdetails'][$xrsetid]['goal_rest_mm'];
                        $parameter .= ':' . substr(sprintf("%02d", $exerciseRecord['setdetails'][$xrsetid]['goal_rest_ss']), 0, 2);
                    }
                }
                $response .= '<div class="row"><div class="mobpadding"><div class="border full">';
                $response .= '<div class="col-xs-10"><div class="input-group bootstrap-timepicker timepicker"><input type="text" value="' . (!empty($parameter) ? $parameter : '00:00') . '" readonly="readonly" id="exercise_rest" required="true"  name="exercise_rest" class="form-control input-sm time-picker2" placeholder="mm:ss"/><span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span></div></div>';
                $response .= '<div class="col-xs-2 aligncenter"><a data-role="none" data-ajax="false" href="javascript:void(0)" onclick="clearInputField(' . "'exercise_rest'" . ');"><i class="fa fa-times"></i></a></div>';
                $response .= '</div></div></div>';
                $response .= '<div id="' . $method . '-collapse" class="collapse row"><div class="mobpadding"><div class="border borderbottom">
											<div class="col-xs-12">
											<h3>Rest after</h3>
											<p><strong>Rest after set</strong></p> <p>This <em>override</em> is provided to suggest a different rest period after this particular set versus the overall setting for Rest Between Sets (ALL sets) that is available at the bottom of the Session Builder variables.</p></div></div></div></div>';
            } elseif ($method == 'remarks') {
                $response .= '<div class="row"><div class="mobpadding"><div class="border full"><div class="col-xs-12 xr-remark-pad"><textarea id="exercise_remark" name="exercise_remark" required="true" class="form-control input-lg" style="width:100%">' . ((isset($exerciseRecord['setdetails'][$xrsetid]['goal_remarks']) && !empty($exerciseRecord['setdetails'][$xrsetid]['goal_remarks'])) ? $exerciseRecord['setdetails'][$xrsetid]['goal_remarks'] : (isset($exerciseRecord['setdetails'][$xrsetid]['descbr']) ? $exerciseRecord['setdetails'][$xrsetid]['descbr'] : '')) . '</textarea></div>';
                $response .= '</div></div></div>';
                $response .= '<div id="' . $method . '-collapse" class="collapse row"><div class="mobpadding"><div class="border borderbottom">
											<div class="col-xs-12">
											<h3>Remarks</h3>
											<p>Notes intended for use for adding custom content or comments for the exercise set. Suggested additional details, include:</p> <ul class="xrtooltip"> <li class="xrtooltiplist">Equipment</li> <li class="xrtooltiplist">Alternatives</li> <li class="xrtooltiplist">Remarks</li> </ul> </div></div></div></div>';
            }
            $response .= '</div><div class="modal-footer">
				<button style="margin-right: 20px;" data-role="none" data-ajax="false" type="button" class="btn btn-default" onclick="closeModelwindow(' . "'" . $modelType . "'" . ');">close</button><button data-role="none" data-ajax="false" class="btn" onclick="insertExtraToParentHidden(' . "'" . $modelType . "','" . $xrsetid . "'" . ');" name="f_method" style="background-color:#fff" type="button"><i class="fa fa-sign-in" style="font-size:30px;" ></i></button></div></form></div></div></div>';
            $response .= "<script type='text/javascript'>
					$('.time-picker1').datetimepicker({ ignoreReadonly: true,format:'HH:mm:ss' });$('.time-picker2').datetimepicker({ ignoreReadonly: true,format:'mm:ss' }); " . (!empty($exerciseRecord['setdetails'][$xrsetid]['goal_unit_id']) ? " if($('#exerciselib')) { $('#exerciselib').bootstrapSwitch('state', true); $('#" . $modelType . "').modal(" . '"hidecustom"' . ");$('#exerciselib-model').modal(); 
						$('#exerciselib').on('switchChange.bootstrapSwitch', function (event, state) { 
							if(!state) { $('#exercise_unit').val(0); $('#exercise_title').val(''); $('div#img-preview-pop').html('<i class=" . '"fa fa-file-image-o datacol"' . " style=" . '"font-size:50px;"' . ">'); } else { $('#exercise_title').val('');  $('div#img-preview-pop').html('<i class=" . '"fa fa-file-image-o datacol"' . " style=" . '"font-size:50px;"' . ">'); }
						}); 
						}
						$('.checkboxdrag').on('switchChange.bootstrapSwitch', function (event, state) {
							if(state === true) { $('#" . $method . "').val('on'); $('#" . $method . "_hidden').val('on'); }
							else { $('#" . $method . "').val('off');  $('#" . $method . "_hidden').val('off');  $('#" . $method . "_hidden').val('off') }
						});" :
                    "
						$('.checkboxdrag').on('switchChange.bootstrapSwitch', function (event, state) {
							if(state === true) { $('#" . $method . "').val('on'); $('#" . $method . "_hidden').val('on'); }
							else { $('#" . $method . "').val('off');  $('#" . $method . "_hidden').val('off'); }
						});
						") . " if($('#exerciselib')){
								$('#exerciselib').bootstrapSwitch('state', true);
								$('#" . $modelType . "').modal(" . '"hidecustom"' . ");
								$('#exerciselib-model').modal();
								$('#exerciselib').on('switchChange.bootstrapSwitch', function (event, state) {
							if(state && ($('#exercise_unit').val() == '0' || $('#exercise_unit').val() == '')) { $('#" . $modelType . "').modal(" . '"hidecustom"' . ");  $('#exerciselib-model').modal();resetWhileOpen(); 
							}else { $('#exercise_unit').val(0); } 
						});}</script>" . (isset($checkedFlag) ? (($checkedFlag) ? "<script> $(document).ready(function () {  $('#" . $method . "').bootstrapSwitch('state', true); });</script>" : "<script> $(document).ready(function () { $('#" . $method . "').bootstrapSwitch('state', false); });</script>") : "");
        } elseif (!empty($action) && trim($action) == 'previewworkout') {
            $focusRecord = $workoutModel->getAllFocus();
            /* ****************** Activity Feed ******************** */
            $activity_feed = array();
            $activity_feed["action_type"] = 42;
            $activity_feed["site_id"] = $site_id;
            $activity_feed["user"] = $user->pk();
            $param = 'wkout_id';
            if (!empty($method) && (trim($method) == 'addworkoutAssign' || trim($method) == 'addworkoutLog' || $method == 'addworkoutLogwkout' || trim($method) == 'addworkout') && !empty($fid)) {
                if (!empty($type) && strtolower($type) == 'wrkout') {
                    $workoutRecord = $workoutModel->getworkoutById($user->pk(), $fid);
                    $exerciseRecord = $workoutModel->getExerciseSet($fid);
                    $activity_feed["feed_type"] = 2;
                } elseif (!empty($type) && strtolower($type) == 'sample') {
                    $workoutRecord = $workoutModel->getSampleworkoutById('0', $fid);
                    $exerciseRecord = $workoutModel->getSampleExerciseSet($fid);
                    $activity_feed["feed_type"] = 15;
                    $param = 'wkout_sample_id';
                } elseif (!empty($type) && strtolower($type) == 'shared') {
                    $workoutRecord = $workoutModel->getShareworkoutById($user->pk(), $fid);
                    $exerciseRecord = $workoutModel->getExerciseSets('shared', $fid);
                    $activity_feed["feed_type"] = 12;
                    $param = 'wkout_share_id';
                } elseif (!empty($type) && strtolower($type) == 'assigned') {
                    $workoutRecord = $workoutModel->getAssignworkoutById($fid, $user->pk());
                    $exerciseRecord = $workoutModel->getExerciseSets('assigned', $fid);
                    $activity_feed["feed_type"] = 13;
                    $activity_feed["context_date"] = Helper_Common::get_default_datetime($workoutRecord['assigned_date']);
                    $param = 'wkout_assign_id';
                } elseif (!empty($type) && strtolower($type) == 'wkoutlog') {
                    $workoutRecord = $workoutModel->getLoggedworkoutById($fid);
                    $exerciseRecord = $workoutModel->getExerciseSets('wkoutlog', $fid);
                    $activity_feed["feed_type"] = 11;
                    $param = 'wkout_log_id';
                }
                $activity_feed["type_id"] = $fid;
            } else {
                if (!empty($type) && strtolower($type) == 'logged') {
                    $logId = ($fromAdmin ? $fid : $logId);
                    $workoutRecord = $workoutModel->getLoggedworkoutById($logId);
                    $exerciseRecord = $workoutModel->getExerciseSets('wkoutlog', $logId);
                    $activity_feed["feed_type"] = 11;
                    $activity_feed["type_id"] = $logId;
                    $param = 'wkout_log_id';
                } elseif (!empty($type) && strtolower($type) == 'assigned') {
                    $assignId = ($fromAdmin ? $fid : $assignId);
                    $workoutRecord = $workoutModel->getAssignworkoutById($assignId, (!empty($fromAdmin) ? '0' : $user->pk()));
                    $exerciseRecord = $workoutModel->getExerciseSets('assigned', $assignId);
                    $activity_feed["feed_type"] = 13;
                    $activity_feed["type_id"] = $assignId;
                    $activity_feed["context_date"] = Helper_Common::get_default_datetime($workoutRecord['assigned_date']);
                    $param = 'wkout_assign_id';
                } elseif (!empty($type) && strtolower($type) == 'previewshared') {
                    $workoutRecord = $workoutModel->getShareworkoutById((!empty($fromAdmin) ? '0' : $user->pk()), $fid);
                    $exerciseRecord = $workoutModel->getExerciseSets('shared', $fid);
                    $activity_feed["feed_type"] = 12;
                    $activity_feed["type_id"] = $fid;
                    $param = 'wkout_share_id';
                } else {
                    if ($method == 'previewsample' || !empty($type) && strtolower($type) == 'previewsample') {
                        $workoutRecord = $workoutModel->getSampleworkoutById('0', $fid);
                        $exerciseRecord = $workoutModel->getSampleExerciseSet($fid);
                        $activity_feed["feed_type"] = 15;
                        $param = 'wkout_sample_id';
                    } else {
                        $workoutRecord = $workoutModel->getworkoutById((!empty($fromAdmin) ? '0' : $user->pk()), $fid);
                        $exerciseRecord = $workoutModel->getExerciseSet($fid);
                        $activity_feed["feed_type"] = 2;
                    }
                    $activity_feed["type_id"] = $fid;
                }
            }
            if (isset($activity_feed["feed_type"]) && !empty($activity_feed["feed_type"]) && $fromAdmin != true)
                Helper_Common::createActivityFeed($activity_feed);
            /*             * ***************** Activity Feed ******************** */
            $response = '<div class="vertical-alignment-helper">
							<div class="modal-dialog modal-md">
							<div class="modal-content">
							<form id="previewworkout" data-ajax="false" data-role="none" action="" method="post">
								<div class="modal-header">
									<div class="row">
										<div class="popup-title">
											<div class="col-xs-3">
												<a href="javascript:void(0);" data-ajax="false" data-role="none" ' . (trim($method) == 'addworkoutLog' || $method == 'addworkoutLogwkout' || trim($method) == 'addworkoutAssign' || trim($method) == 'addworkout' ? 'onclick="addAssignWorkoutsByDate(' . "'" . date('Y-m-d', strtotime((!empty($date) ? $date . ' 00:00:00' : Helper_Common::get_default_date() . ' 00:00:00'))) . "','" . (!empty($foldid) ? $foldid : 0) . "','0','" . $type . (trim($method) == 'addworkoutLog' ? '-logged' : (trim($method) == 'addworkoutLogwkout' ? '-loggedwkout' : (trim($method) == 'addworkout' ? '-workout' : ''))) . "'" . ');"' : 'data-dismiss="modal"') . '  class="triangle">
													<i class="fa fa-caret-left iconsize"></i>
												</a>
											</div>
											<div class="col-xs-6">' . ((!empty($type) && strtolower($type) == 'assigned') ? "Assigned Plan" : (strtolower($type) == 'logged' ? "Workout Journal" : "Workout")) . ' Preview</div>';
            if ($fromAdmin != true) {
                $response .= '<div class="col-xs-3 save-icon-button activedatacol">';
                if (!empty($method) && (trim($method) == 'addworkoutAssign' || trim($method) == 'addworkoutLog' || $method == 'addworkoutLogwkout' || trim($method) == 'addworkout') && !empty($fid)) {
                    $response .= '<button type="button" data-ajax="false" data-role="none" class="btn btn-default activedatacol" onclick="getOptionsPopup(' . "'" . $fid . "','" . $type . "','" . $method . "','" . $date . "','" . addSlashes(ucfirst($workoutRecord['wkout_title'])) . "'" . ');">insert</button>';
                } else {
                    if ($method != 'previewsample' && $method != 'previewshared') {
                        if (!empty($type) && strtolower($type) == 'assigned') {
                            $current = strtotime(Helper_Common::get_default_date());
                            $datediff = strtotime($workoutRecord['assigned_date']) - $current;
                            $difference = floor($datediff / (60 * 60 * 24));
                        }
                        $response .= '<i class="fa fa-ellipsis-h iconsize" ' . ((!empty($type) && strtolower($type) == 'assigned') ? 'onclick="getTemplateOfAssignAction(' . "'" . $fid . "','" . $assignId . "','" . $workoutRecord['assigned_date'] . "','" . $workoutRecord['assigned_by'] . "','" . addslashes($workoutRecord['wkout_title']) . "','" . (empty($workoutRecord['journal_status']) && $difference >= 0 ? '1' : '') . "'" . ')"' : (strtolower($type) == 'logged' ? 'onclick="getTemplateOfAssignActionByJournal(' . "'" . $fid . "','" . $logId . "','" . $workoutRecord['assigned_date'] . "','" . $workoutRecord['user_id'] . "'" . ')"' : 'onclick="getTemplateOfWorkoutAction(' . "'" . $fid . "','" . $workoutRecord['wks_id'] . "','" . addSlashes(ucfirst($workoutRecord['wkout_title'])) . "'" . ');"')) . '></i>';
                    } else if ($method == 'previewsample' || $method == 'previewshared') {
                        $response .= '<i class="fa fa-ellipsis-h iconsize" onclick="getTemplateOfWorkoutAction(' . "'" . $fid . "','" . $workoutRecord['wks_id'] . "','" . addSlashes(ucfirst($workoutRecord['wkout_title'])) . "'" . ');"' . '></i><br><span class="inactivedatacol"></span>';
                    }
                }
                $response .= '</div>';
            } else {
                $response .= '<div class="col-xs-3 save-icon-button activedatacol"></div>';
            }
            $response .= '</div>
									</div>';
            $response .= '<hr><div class="row"><div class="popup-title"><div class="col-xs-12" style="font-size: .9em;"><i class="glyphicon ' . $workoutRecord['color_title'] . ' prevwk-color-glyph" style="width: 25px;height: 25px;"></i><span class="preCls textcenter">' . ucfirst($workoutRecord['wkout_title']) . '</span></div></div></div>' . ((!empty($type) && strtolower($type) == 'assigned') ? '<div class="row"><div class="popup-title"><div class="col-xs-12"><span class="inactivedatacol" style="font-size: .8em; font-weight: normal;">Assignment Date : <b>' . date('d M Y', strtotime($workoutRecord['assigned_date'] . ' 00:00:00')) . '</b></span></div></div></div>' : ((!empty($type) && (strtolower($type) == 'logged' || strtolower($type) == 'wkoutlog')) ? '<div class="row"><div class="popup-title"><div class="col-xs-12"><span class="inactivedatacol" style="font-size: .8em; font-weight: normal;">Journal Entry Date: <b>' . date('d M Y', strtotime($workoutRecord['assigned_date'])) . '</b></span></div></div></div>' : '')) . '<div class="row"><div class="popup-title"><div class="col-xs-12"><span class="inactivedatacol" style="font-size: .8em; font-weight: normal;">Focus: ';
            foreach ($focusRecord as $keys => $values) {
                if ($values['focus_id'] == $workoutRecord['wkout_focus'])
                    $response .= ucfirst($values['focus_opt_title']);
            }
            $response .= '</span></div></div></div>' . ((!empty($type) && (strtolower($type) == 'logged' || strtolower($type) == 'wkoutlog')) ? '<div class="row"><div class="popup-title"><div class="col-xs-12"><span class="inactivedatacol" style="font-size: .8em; font-weight: normal;">Overall Remarks : <span class="preCls datacol textcenter">' . ( $workoutRecord['note_wkout_intensity'] > 0 ? Model::instance('Model/workouts')->getGoalValues('int', $workoutRecord['note_wkout_intensity']) . ' Perceived Intensity' : '') . '</span><br><span class="preCls datacol textcenter">' . $workoutRecord['note_wkout_remarks'] . '</span></span></div></div></div>' : '' );
            $response .= '</div>
								<div class="modal-body">';
            $response .= '<div class="workout-bodyrow"><div id="scrollablediv-len" class="' . (isset($exerciseRecord) && count($exerciseRecord) > 4 ? 'scrollablepadd scrollablediv' : 'scrollablepadd') . '"><ul style="border:1px solid #ededed;" class="sTreeBase bgC4" data-autodividers="false" data-role="none" data-inset="false" data-ajax="false">';
            $totalRestTime = '00:00';
            $totalRest = 0;
            $reMappedExerciseSet = Helper_Common::exerciseSetsCombine($exerciseRecord, $param, true);
            if (isset($reMappedExerciseSet['uniqueset']) && count($reMappedExerciseSet['uniqueset']) > 0) {
                $order = 0;
                foreach ($reMappedExerciseSet['uniqueset'] as $keys => $val) {
                    if ($val['goal_title'] != 'Click_to_Edit') {
                        $order = $order + 1;
                        $response .= '<li class="bgC4" data-role="none" id="itemSet_0_' . $val['goal_id'] . '_' . $order . '" class="bgC4" data-module="item_set" data-id="' . $val['goal_id'] . '"><div id="itemset_0_' . $val['goal_id'] . '"><div class="border full">';
                        $response .= '<div class="col-xs-3 pointers" ';
                        if (!empty($method) && (trim($method) == 'addworkoutAssign' || trim($method) == 'addworkoutLog' || $method == 'addworkoutLogwkout') && !empty($fid)) {
                            if (!empty($val['unit_id']))
                                $response .= 'onclick="getTemplateOfExerciseRecordActionByType(' . "'" . $val['unit_id'] . "',this" . ');" ';
                        }else {
                            if (!empty($val['unit_id']))
                                $response .= 'onclick="getTemplateOfExerciseRecordAction(' . "'" . $val['unit_id'] . "',this" . ');" ';
                        }
                        if ($val['goal_unit_id']) {
                            if (file_exists($val["img_url"])) {
                                $response .= '><img width="75px" class="img-responsive" src="' . URL::base() . $val['img_url'] . '" title="' . ucfirst($val['img_title']) . '"/>';
                            } else {
                                $response .= '><i class="fa fa-file-image-o datacol"></i>';
                            }
                        } else {
                            $response .= '><i class="fa fa-pencil-square datacol" style="font-size:50px;"></i>';
                        }
                        $response .= '</div>';
                        $response .= '<div class="navimagedetails workoutdetails col-xs-8">';
                        $goal_ids = '';
                        if (isset($reMappedExerciseSet['setdetails'][$keys]) && count($reMappedExerciseSet['setdetails'][$keys]) > 0) {
                            foreach ($reMappedExerciseSet['setdetails'][$keys] as $key => $values) {
                                $goal_ids .= $values['goal_id'] . ',';
                            }
                        }
                        if (!empty($goal_ids))
                            $goal_ids = substr($goal_ids, 0, -1);
                        if (!empty($method) && (trim($method) == 'addworkoutAssign' || trim($method) == 'addworkout' || trim($method) == 'addworkoutLog' || $method == 'addworkoutLogwkout') && !empty($fid)) {
                            $response .= '<div class="labelcol pointers" onclick="getExerciseSetpreviewByType(' . "'" . $goal_ids . "','" . $fid . "','" . $type . "'" . ')" >' . (($val['goal_alt'] > 0) ? '<span>Alt</span> ' : '') . ucfirst($val['goal_title']) . '</div>';
                        } else {
                            if (!empty($type) && strtolower($type) == 'assigned') {
                                $response .= '<div class="labelcol pointers" onclick="getExerciseSetpreview(' . "'" . $goal_ids . "','" . $assignId . "','" . $fid . "','" . $workoutRecord['assigned_by'] . "','" . $workoutRecord['modified_by'] . "'" . ')"  ><div class="navimgdet1"><b>' . (($val['goal_alt'] > 0) ? '<span>Alt</span> ' : '') . ucfirst($val['goal_title']) . '</b></div></div>';
                            } elseif (!empty($type) && strtolower($type) == 'logged') {
                                $response .= '<div class="labelcol activedatacol" onclick="getExerciseSetpreviewlog(' . "'" . $goal_ids . "','" . $logId . "','" . $fid . "','" . $workoutRecord['user_id'] . "','" . $workoutRecord['modified_by'] . "'" . ')" ><div class="navimgdet1"><b>' . (($val['goal_alt'] > 0) ? '<span>Alt</span> ' : '') . ucfirst($val['goal_title']) . '</b></div></div>';
                            } else {
                                $response .= '<div class="labelcol activedatacol" onclick="getExerciseSetpreview(' . "'" . $goal_ids . "','" . $fid . "'" . ')" ><div class="navimgdet1"><b>' . (($val['goal_alt'] > 0) ? '<span>Alt</span> ' : '') . ucfirst($val['goal_title']) . '</b></div></div>';
                            }
                        }
                        if (isset($reMappedExerciseSet['setdetails'][$keys]) && count($reMappedExerciseSet['setdetails'][$keys]) > 0) {
                            foreach ($reMappedExerciseSet['setdetails'][$keys] as $key => $values) {
                                $response .= ($key > 0 ? '<hr>' : '') . '<div class="navimgdet2">';
                                $response .= '<div class="xrsets datacol col-xs-12 ' . ($key > 0 ? 'additioncol' : '') . '">';
                                $parameter = $parameter1 = '';
                                $hidden_time = "00:00:00";
                                $hidden_rest_time = "00:00";
                                $flag = false;
                                if ($values['goal_time_hh'] > 0 || $values['goal_time_mm'] > 0 || $values['goal_time_ss'] > 0) {
                                    $parameter .= (($values['primary_time']) ? '<span class="ashstrick">*</span> ' : '') . '<span>' . substr(sprintf("%02d", $values['goal_time_hh']), 0, 2) . ':' . substr(sprintf("%02d", $values['goal_time_mm']), 0, 2) . ':' . substr(sprintf("%02d", $values['goal_time_ss']), 0, 2);
                                    $flag = true;
                                }
                                if ($values['goal_dist'] > 0 && $values['goal_dist_id'] > 0) {
                                    $parameter .= ($flag ? ' /// ' : '') . (($values['primary_dist']) ? '<span class="ashstrick">*</span> ' : '') . $values['goal_dist'] . ' <span>' . Model::instance('Model/workouts')->getGoalValues('dist', $values['goal_dist_id']);
                                    $flag = true;
                                }
                                if ($values['goal_reps'] > 0) {
                                    $parameter .= ($flag ? ' /// ' : '') . (($values['primary_reps']) ? '<span class="ashstrick">*</span> ' : '') . $values['goal_reps'] . ' <span>reps</span>';
                                    $flag = true;
                                }
                                if ($values['goal_resist'] > 0 && $values['goal_resist_id'] > 0) {
                                    $parameter .= ($flag ? ' /// ' : '') . (($values['primary_resist']) ? '<span class="ashstrick">*</span> ' : '') . ' x ' . $values['goal_resist'] . ' <span>' . Model::instance('Model/workouts')->getGoalValues('resist', $values['goal_resist_id']) . '</span>';
                                }
                                $response .= $parameter;
                                if ($values['goal_rate'] > 0 && $values['goal_rate_id'] > 0) {
                                    $parameter1 = '<span>@' . $values['goal_rate'] . ' ' . Model::instance('Model/workouts')->getGoalValues('rate', $values['goal_rate_id']) . '</span> ';
                                }
                                if ($values['goal_angle'] > 0 && $values['goal_angle_id'] > 0) {
                                    $parameter1 .= '<span>' . $values['goal_angle'] . '%' . Model::instance('Model/workouts')->getGoalValues('angle', $values['goal_angle_id']) . '</span> ';
                                }
                                $intvalue = Model::instance('Model/workouts')->getGoalValues('int', $values['goal_int_id']);
                                if ($intvalue > 0)
                                    $parameter1 .= '<span>' . $intvalue . ' int</span>';
                                if ($values['goal_rest_mm'] + $values['goal_rest_ss'] > 0) {
                                    if ($values['goal_rest_mm'] > 0 || $values['goal_rest_ss'] > 0) {
                                        $parameter1 .= ' <span>' . $values['goal_rest_mm'];
                                        $totalRest += $values['goal_rest_mm'] * 60;
                                        if ($values['goal_rest_ss'] > 0 && $values['goal_rest_ss'] < 10)
                                            $parameter1 .= ':0' . $values['goal_rest_ss'];
                                        else
                                            $parameter1 .= ':' . substr(sprintf("%02d", $values['goal_rest_ss']), 0, 2);
                                        $totalRest += $values['goal_rest_ss'];
                                        $parameter1 .= ' rest</span>';
                                    }
                                }
                                $response .= ($parameter != '' && $parameter1 != '' ? ' / ' . $parameter1 : '');
                                if (!empty($type) && (strtolower($type) == 'logged' || strtolower($type) == 'wkoutlog') && $values['note_set_intensity'] > 0) {
                                    $response .= ($parameter != '' || $parameter1 != '' ? ' / ' : '') . '<span>' . ($values['note_set_intensity'] > 0 ? Model::instance('Model/workouts')->getGoalValues('int', $values['note_set_intensity']) : '0') . ' Perceived Intensity<br>' . (!empty($values['note_set_remarks']) ? $values['note_set_remarks'] : '') . '</span> ';
                                }
                                if (!empty($type) && (strtolower($type) == 'logged' || strtolower($type) == 'wkoutlog')) {
                                    $response .= '<div class="col-xs-1" style="float:right"><i class="fa ' . ((isset($values['set_status']) && !empty($values['set_status'])) ? ($values['set_status'] == '1' ? ' fa-check-square-o greenicon pointers' : ($values['set_status'] == '2' ? ' fa-minus-square-o pinkicon pointers' : ' fa-ellipsis-h ' )) : ($values['set_status'] == '0' ? ' fa-exclamation-circle ' : '')) . ' iconsize listoptionpop"></i></div>';
                                }
                                $response .= '</div></div>';
                            }
                        }
                        $response .= '</div></div></li>';
                    }
                }
                if ($totalRest > 0) {
                    $totalMint = $totalRest / 60;
                    $totalSec = $totalRest % 60;
                    $totalRestTime = sprintf("%02d", $totalMint) . ':' . sprintf("%02d", $totalSec);
                }
                $response .= '</ul></div></div><div class="workout-footerrow hide"><div class="row">';
                $response .= '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                $response .= '<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 border-full"><div> Rest Between Sets </div></div>';
                $response .= '<div class="col-lg-9 col-md-9 col-sm-9 col-xs-9 border-full"><div>' . $totalRestTime . '</div></div>';
                $response .= '</div>';
                $response .= '</div></div>';
            }
            $response .= '</div><div class="modal-footer"><button data-role="none" data-ajax="false" type="button" class="btn btn-default" data-dismiss="modal">close</button></div></form></div></div></div>';
        } elseif (!empty($action) && trim($action) == 'previewExercise') {
            //$foldid as wrkoutid, $fid as exercisesetid
            $param = 'wkout_id';
            if (!empty($type) && (strtolower($type) == 'logged' || strtolower($type) == 'wkoutlog') && (!empty($logId) || !empty($foldid))) {
                if (!empty($logId))
                    $exerciseRecord = $workoutModel->getExerciseSetDetailsByWkoutLog($logId, $fid);
                else
                    $exerciseRecord = $workoutModel->getExerciseSetDetailsByWkoutLog($foldid, $fid);
                $editFlag = true;
                $param = 'wkout_log_id';
            }elseif (!empty($type) && strtolower($type) == 'assigned' && (!empty($assignId) || !empty($foldid))) {
                if (!empty($assignId))
                    $exerciseRecord = $workoutModel->getExerciseSetDetailsByAssignWkout($assignId, $fid);
                else
                    $exerciseRecord = $workoutModel->getExerciseSetDetailsByAssignWkout($foldid, $fid);

                $current = strtotime(Helper_Common::get_default_date());
                $datediff = strtotime($exerciseRecord['assigned_date']) - $current;
                $difference = floor($datediff / (60 * 60 * 24));
                $editFlag = (empty($exerciseRecord['journal_status']) && $difference >= 0 ? true : false);
                $param = 'wkout_assign_id';
            }elseif (!empty($type) && (strtolower($type) == 'share' || strtolower($type) == 'shared') && !empty($foldid)) {
                $exerciseRecord = $workoutModel->getExerciseSetDetailsByShareWkout($foldid, $fid);
                $param = 'wkout_share_id';
            } else if ($type == 'sample') {
                $exerciseRecord = $workoutModel->getSampleExerciseSetDetailsByWorkout($foldid, $fid);
                $param = 'wkout_sample_id';
            } else
                $exerciseRecord = $workoutModel->getExerciseSetDetailsByWorkout($foldid, $fid);
            $reMappedExerciseSet = Helper_Common::exerciseSetsCombine($exerciseRecord, $param, false);

            $unique_set = isset($reMappedExerciseSet['uniqueset']) ? $reMappedExerciseSet['uniqueset'][$foldid] : '';
            $set_details = isset($reMappedExerciseSet['setdetails']) ? $reMappedExerciseSet['setdetails'][$foldid] : '';

            $response = '<div id="preview-exercise" class="vertical-alignment-helper">
							<div class="modal-dialog xrunitdata-modal">
								<div class="modal-content">
								<div class="modal-header">
									<div class="row">
										<div class="popup-title">
											<div class="col-xs-3">';
            if (!empty($type) || ((strtolower($type) == 'assigned' && (!empty($assignId) || !empty($foldid))) || (strtolower($type) == 'logged' && (!empty($logId) || !empty($foldid))))) {
                $response .= '<a href="javascript:void(0);" data-ajax="false" data-role="none" class="triangle" data-dismiss="modal">';
            } else {
                $response .= '<a href="javascript:void(0);" data-ajax="false" data-role="none" onclick="getworkoutpreview(' . $foldid . ')" class="triangle" data-dismiss="modal">';
            }
            $response .= '<i class="fa fa-caret-left iconsize"></i>
												</a>
											</div>
											<div class="col-xs-6">' . (($requestFrom == 'userprofile') ? 'Sourced From :' : 'Exercise Set : Preview') . '</div>
											<div class="col-xs-3 activedatacol">' . ($editFlag ? '<button type="button" class="btn btn-default allow-edit">More</button>' : '') . '</div>
										</div>
									</div>';
            if ($requestFrom == 'userprofile') {
                $response .= '<hr><div class="row">
											<div class="popup-title">
												<div class="col-xs-12" style="font-size: .9em;">
													<i class="glyphicon ' . $exerciseRecord['color_title'] . ' prevwk-color-glyph" style="width: 25px;height: 25px;"></i>
													<span class="preCls textcenter">' . ucfirst($exerciseRecord['wkout_title']) . '</span>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="popup-title">
												<div class="col-xs-12">
													<span class="inactivedatacol" style="font-size: .8em; font-weight: normal;">Journal Entry Date: <b>' . date('d M Y', strtotime($exerciseRecord['assigned_date'] . ' 00:00:00')) . '</b></span>
												</div>
											</div>
										</div>';
                if (!$fromAdmin) {
                    $response .= '<div class="row">
												<div class="popup-title">
													<div class="col-xs-12" style="font-size: .9em;">
														<a href="javascript:void(0);" class="activedatacol" style="padding: 5px; text-decoration: none;" onclick="return redirectToCalendar(' . "'" . $foldid . "','" . $logId . "','" . $exerciseRecord['assigned_date'] . "','" . $ownWkFlag . "'" . ');" data-ajax="false" data-role="none">Preview this Workout Journal</a>
													</div>
												</div>
											</div>';
                }
            }
            $response .= '</div>
								<div class="modal-body">
									<div class="workout-titlerow">
										<div class="row">
											<div class="mobpadding exersetcolumn-xr">
												<div class="border-xr full">
													<div class="col-xs-3 firstcell borderright">
														<p class="labelcol">Exercise Title</p>
													</div>
													<div class="col-xs-9 secondcell datacol">
													<div class="col-xs-4 alignleft" style="padding-left:0px;width:50px;">';
            if (!empty($unique_set['goal_unit_id']) && !empty($unique_set['img_url']) && file_exists($unique_set['img_url'])) {
                $image_url = URL::base() . $unique_set['img_url'];
            } else {
                $image_url = URL::base() . 'assets/images/navimage.png';
            }
            $response .= '<img width="50px;" id="exerciselibimg-preview" class="img-thumbnail" style="max-width:50px;';
            if (empty($unique_set['goal_unit_id'])) {
                $response .= 'display:none';
            }
            $response .= '" alt="' . (isset($unique_set['goal_title']) ? $unique_set['goal_title'] : '') . '" src="' . $image_url . '" >';
            $response .= '</div><div class="col-xs-7" style="height:50px;display:table"><p style="vertical-align: middle; display: table-cell;">' . (isset($unique_set['goal_title']) ? ucfirst($unique_set['goal_title']) : '') . '</p></div>
													</div>
												</div>
											</div>
										</div>';
            if (!empty($set_details) && count($set_details) > 0) {
                if (!empty($set_details) && count($set_details) > 1) {
                    $response .= '<div class="row xrsets-tab" id="xrsets-tab">
											<div class="mobpadding exersetcolumn-xr">
												<div class="border-xr full">
													<div class="col-xs-3 firstcell borderright">
														<p class="labelcol">Sets:</p>
													</div>
													<div class="borderbox col-xs-9 secondcell datacol no-border">
														<ul class="nav nav-tabs setlist-tab">';
                    foreach ($set_details as $setkey => $setvalue) {
                        $response .= '<li class="' . ($setkey == 0 ? 'active' : '') . '" data-setid="' . $setvalue['goal_id'] . '"><a href="#set_' . $setvalue['goal_id'] . '" data-toggle="tab">Set ' . ($setkey + 1) . '</a></li>';
                    }
                    $response .= '</ul>
													</div>
												</div>
											</div>
										</div>';
                }
                $response .= '<div class="tab-content set-tab allow-edit">';
                foreach ($set_details as $setkey => $setvalue) {
                    $basicChecked = 'checked="checked"';
                    $advancedChecked = '';
                    $actioncount = 0;
                    if ((isset($setvalue['goal_rate_id']) && $setvalue['goal_rate_id'] > 0) || (isset($setvalue['goal_int_id']) && $setvalue['goal_int_id'] > 0) || (isset($setvalue['goal_angle_id']) && $setvalue['goal_angle_id'] > 0)) {
                        $advancedChecked = 'checked="checked"';
                        $basicChecked = '';
                        if (isset($setvalue['goal_rate_id']) && $setvalue['goal_rate_id'] > 0)
                            $actioncount += 1;
                        if (isset($setvalue['goal_int_id']) && $setvalue['goal_int_id'] > 0)
                            $actioncount += 1;
                        if (isset($setvalue['goal_angle_id']) && $setvalue['goal_angle_id'] > 0)
                            $actioncount += 1;
                    }
                    $response .= '<div id="set_' . $setvalue['goal_id'] . '" data-setid="' . $setvalue['goal_id'] . '" class="tab-pane fade ' . ($setkey == 0 ? 'in active' : '') . '">
												<div class="row ' . ($user_xr_variable ? '' : 'hide') . '">
													<div class="mobpadding">
														<div class="datatab">
															<div class="col-xs-12 removbotbor">
																<div class="removepading wrapword labelcol">
																	<div class="btn-group">
																		Variables: 
																		<label>
																			<input type="radio" id="basic_' . $setvalue['goal_id'] . '" name="xrtype_' . $setvalue['goal_id'] . '" value="1" onclick="hideXrVariables(this);"' . $basicChecked . ' data-varid="set_' . $setvalue['goal_id'] . '"/> Basic
																		</label> 
																		<label>
																			<input type="radio" id="advance_' . $setvalue['goal_id'] . '" name="xrtype_' . $setvalue['goal_id'] . '" value="2" ' . $advancedChecked . ' onclick="showXrVariables(this);" data-varid="set_' . $setvalue['goal_id'] . '"/> Advanced <span id="showcountXrvariable" class="actioncount ' . ($actioncount == 0 ? 'hide' : '') . '">' . $actioncount . '</span>
																		</label> 
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
																<p class="labelcol">Resistance</p>
															</div>
															<div class="col-xs-9 secondcell datacol">
															<div class="datacol"><p>';
                    $parameter = '';
                    if (isset($setvalue['goal_resist_id']) && $setvalue['goal_resist_id'] > 0) {
                        $parameter = (($setvalue['primary_resist']) ? '<span class="ashstrick">*</span> ' : '') . $setvalue['goal_resist'] . ' <span>' . Model::instance('Model/workouts')->getGoalValues('resist', $setvalue['goal_resist_id']) . '</span>';
                    }
                    $response .= $parameter . '</p>
															</div>
															</div>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="mobpadding exersetcolumn-xr">
														<div class="border-xr full">
															<div class="col-xs-3 firstcell borderright">
																<p class="labelcol">Repetitions</p>
															</div>
															<div class="col-xs-9 secondcell datacol">
															<div class="datacol"><p>';
                    $parameter = '';
                    if (isset($setvalue['goal_reps']) && $setvalue['goal_reps'] > 0) {
                        $parameter = (($setvalue['primary_reps']) ? '<span class="ashstrick">*</span> ' : '') . $setvalue['goal_reps'] . ' reps';
                    }
                    $response .= $parameter . '</p></div>
															</div>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="mobpadding exersetcolumn-xr">
														<div class="border-xr full">
															<div class="col-xs-3 firstcell borderright">
																<p class="labelcol">Time</p>
															</div>
															<div class="col-xs-9 secondcell datacol">
															<div class="datacol"><p>';
                    $parameter = $paratime = '';
                    if ((isset($setvalue['goal_time_hh']) && $setvalue['goal_time_hh'] > 0) || (isset($setvalue['goal_time_mm']) && $setvalue['goal_time_mm'] > 0) || (isset($setvalue['goal_time_ss']) && $setvalue['goal_time_ss']) > 0) {
                        $parameter = (($setvalue['primary_time']) ? '<span class="ashstrick">*</span> ' : '') . '<span>' . substr(sprintf("%02d", $setvalue['goal_time_hh']), 0, 2) . ':' . substr(sprintf("%02d", $setvalue['goal_time_mm']), 0, 2) . ':' . substr(sprintf("%02d", $setvalue['goal_time_ss']), 0, 2) . '</span>';
                        $paratime = $setvalue['goal_time_hh'] . ':' . $setvalue['goal_time_mm'] . ':' . $setvalue['goal_time_ss'];
                    }
                    $response .= $parameter . '</p>
															</div>
															</div>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="mobpadding exersetcolumn-xr">
														<div class="border-xr full">
															<div class="col-xs-3 firstcell borderright">
																<p class="labelcol">Distance</p>
															</div>
															<div class="col-xs-9 secondcell datacol">
															<div class="datacol"><p>';
                    $parameter = '';
                    if (isset($setvalue['goal_dist_id']) && $setvalue['goal_dist_id'] > 0) {
                        $parameter = (($setvalue['primary_dist']) ? '<span class="ashstrick">*</span> ' : '') . $setvalue['goal_dist'] . ' <span>' . Model::instance('Model/workouts')->getGoalValues('dist', $setvalue['goal_dist_id']) . '</span>';
                    }
                    $response .= $parameter . '</p>
															</div>
															</div>
														</div>
													</div>
												</div>
												<div class="row hideadvance ' . (!empty($basicChecked) ? 'hide' : '') . '">
													<div class="mobpadding exersetcolumn-xr">
														<div class="border-xr full">
															<div class="col-xs-3 firstcell borderright">
																<p class="labelcol">Pace</p>
															</div>
															<div class="col-xs-9 secondcell datacol">
															<div class="datacol"><p>';
                    $parameter = '';
                    if (isset($setvalue['goal_rate_id']) && $setvalue['goal_rate_id'] > 0) {
                        $parameter = (($setvalue['primary_rate']) ? '<span class="ashstrick">*</span> ' : '') . $setvalue['goal_rate'] . ' <span>' . Model::instance('Model/workouts')->getGoalValues('rate', $setvalue['goal_rate_id']) . '</span>';
                    }
                    $response .= $parameter . '</p>
															</div>
															</div>
														</div>
													</div>
												</div>
												<div class="row hideadvance ' . (!empty($basicChecked) ? 'hide' : '') . '">
													<div class="mobpadding exersetcolumn-xr">
														<div class="border-xr full">
															<div class="col-xs-3 firstcell borderright">
																<p class="labelcol">Inner Drive</p>
															</div>
															<div class="col-xs-9 secondcell datacol">
															<div class="datacol"><p>';
                    $parameter = '';
                    if (isset($setvalue['goal_int_id']) && $setvalue['goal_int_id'] > 0) {
                        $parameter = (($setvalue['primary_int']) ? '<span class="ashstrick">*</span> ' : '') . '<span>' . Model::instance('Model/workouts')->getGoalValues('int', $setvalue['goal_int_id']) . '</span> Int';
                    }
                    $response .= $parameter . '</p>
															</div>
															</div>
														</div>
													</div>
												</div>
												<div class="row hideadvance ' . (!empty($basicChecked) ? 'hide' : '') . '">
													<div class="mobpadding exersetcolumn-xr">
														<div class="border-xr full">
															<div class="col-xs-3 firstcell borderright">
																<p class="labelcol">Angle</p>
															</div>
															<div class="col-xs-9 secondcell datacol">
															<div class="datacol"><p>';
                    $parameter = '';
                    if (isset($setvalue['goal_angle_id']) && $setvalue['goal_angle_id'] > 0) {
                        $parameter = (($setvalue['primary_angle']) ? '<span class="ashstrick">*</span> ' : '') . $setvalue['goal_angle'] . ' %  <span>' . Model::instance('Model/workouts')->getGoalValues('angle', $setvalue['goal_angle_id']) . '</span>';
                    }
                    $response .= $parameter . '</p>
															</div>
															</div>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="mobpadding exersetcolumn-xr">
														<div class="border-xr full">
															<div class="col-xs-3 firstcell borderright">
																<p class="labelcol">Rest After</p>
															</div>
															<div class="col-xs-9 secondcell datacol">
															<div class="datacol"><p>';
                    $parameter = $pararest = '';
                    if (isset($setvalue['goal_rest_mm']) && isset($setvalue['goal_rest_ss']) && $setvalue['goal_rest_mm'] + $setvalue['goal_rest_ss'] > 0) {
                        if ($setvalue['goal_rest_mm'] > 0 || $setvalue['goal_rest_ss'] > 0) {
                            $parameter = (($setvalue['primary_rest']) ? '<span class="ashstrick">*</span> ' : '') . $setvalue['goal_rest_mm'];
                            if ($setvalue['goal_rest_ss'] > 0 && $setvalue['goal_rest_ss'] < 10)
                                $parameter .= ':0' . $setvalue['goal_rest_ss'];
                            else
                                $parameter .= ':' . substr(sprintf("%02d", $setvalue['goal_rest_ss']), 0, 2);
                            $pararest = str_replace(array('-', '<span class="ashstrick">*</span>'), '', $parameter);
                        }
                    }
                    $response .= $parameter . '</p>
															</div>
															</div>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="mobpadding exersetcolumn-xr">
														<div class="border-xr full">
															<div class="col-xs-3 firstcell borderright">
																<p class="labelcol">Remarks</p>
															</div>
															<div class="col-xs-9 secondcell datacol">';
                    $response .= '<p>' . (isset($setvalue['goal_remarks']) && !empty($setvalue['goal_remarks']) ? $setvalue['goal_remarks'] : (isset($setvalue['descbr'])) ? $setvalue['descbr'] : '') . '</p>';
                    $response .= '</div>
														</div>
													</div>
												</div>
											</div>';
                }
                $response .= '</div>
										</div></div>';
            }
            $response .= '<div class="modal-footer"><button data-role="none" data-ajax="false" type="button" class="btn btn-default" data-dismiss="modal">close</button>' . ($editFlag ? '<button type="button" class="btn btn-default allow-edit">More</button>' : '') . '</div></div></div></div>' . ($editFlag ? '<div tabindex="-1" role="dialog" class="modal fade" id="confirm"><div class="vertical-alignment-helper"><div class="modal-dialog xrunitdata-modal"><div class="modal-content"><div class="modal-body">Would you like to switch to edit-mode to modify this ' . (!empty($logId) ? 'Workout Journal?' : (!empty($assignId) ? 'Assigned Plan?' : 'Workout Record?')) . '</div><div class="modal-footer"><button type="button" class="btn" onclick="$(' . "'#confirm'" . ').modal(' . "'hide'" . ');">cancel</button><a data-ajax="false" data-role="none" href="' . URL::base(TRUE) . 'exercise/' . (!empty($logId) ? 'workoutlog/' : (!empty($assignId) ? 'assignedplan/' : 'workoutrecord/')) . (!empty($logId) ? $logId : (!empty($assignId) ? $assignId : $foldid)) . '/?act=edit&edit=' . $fid . '" class="btn btn-primary">Yes</a></div></div></div></div></div>' : '');
        }elseif (!empty($action) && trim($action) == 'previewExerciseOfDay') {
            if ($method == 'preview-only' || $method == 'xrrecord' || $method == 'samplerecord') {
                $method = 'disallow';
            }
            $exerciseRecord = $workoutModel->getExerciseById($fid);
            $seqRecord = $workoutModel->getSeqFromUnitId($fid);
            $tagCount = count($workoutModel->getUnitTagsById($fid));
            $rating = $workoutModel->getExerciseSetRatingByUnitId($fid);
            $relatedRecords = $workoutModel->getRelatedExercise($fid, $exerciseRecord['muscle_id'], $exerciseRecord['status_id'], $exerciseRecord['type_id']);
            $response = '<div class="vertical-alignment-helper">
							<div class="modal-dialog xrunitdata-modal">
							<div class="modal-content">
								<div class="modal-header">
									<div class="row">
										<div class="popup-title">
											<div class="col-xs-3">';
            if (!empty($type) && (strtolower($type) == 'assigned' || strtolower($type) == 'logged')) {
                $response .= '<a data-role="none" data-ajax="false" href="javascript:void(0);" onclick="closeModelwindow(' . "'" . $modelType . "'" . ');" class="triangle" >';
            } else {
                $response .= '<a data-role="none" data-ajax="false" href="javascript:void(0);"' . (!empty($foldid) ? 'onclick="closeModelwindow(' . "'" . (!empty($modelType) ? $modelType : 'FolderModal' ) . "'" . ');getworkoutpreview(' . "'" . $foldid . "'" . ')"' : ( (!empty($modelType) && $modelType == 'myModal-exercisepreview') ? 'onclick="$(' . "'#exerciselib-model'" . ').show();closeModelwindow(' . "'myModal-exercisepreview'" . ');"' : 'data-dismiss="modal"')) . ' class="triangle" >';
            }
            $response .= '<i class="fa fa-caret-left iconsize"></i>
												</a>
											</div>
											<div class="col-xs-6 xrtitle">Exercise Record : Preview</div>
											<div class="col-xs-3 save-icon-button activedatacol">
												<button class="btn btn-default activedatacol ' . (!empty($type) && (strtolower($type) == 'myexercise' || strtolower($type) == 'sampleexercise' || strtolower($type) == 'sharedexercise') ? '" onclick="' . ($actionFrom == 'exercise' ? 'getXrsetOptionsPopup' : 'xrLibgetXrsetOptionsPopup') . '(' . "'" . $fid . "','" . $type . "','" . (file_exists($exerciseRecord['img_url']) ? URL::base() . $exerciseRecord['img_url'] : '') . "','" . addslashes(ucfirst($exerciseRecord['title'])) . "'" . ')"' : 'hide"') . ' data-role="none" data-ajax="false" type="button">next</button>
											</div>
										</div>
									</div>
								</div>
								<div class="modal-body">
									<div class="workout-titlerow">
										<div class="row">
											<div class="mobpadding exersetcolumn-xr">
												<div class="border-xr full">
													<div class="col-xs-3 firstcell borderright">
														<p class="labelcol">Exercise<br>Title</p>
													</div>
													<div class="col-xs-9 secondcell datacol">
													<span class="preCls datacol textcenter">' . ucfirst($exerciseRecord['title']) . '</span>
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="mobpadding exersetcolumn-xr">
												<div class="border-xr full">
													<div class="col-xs-3 firstcell borderright">
														<p class="labelcol">Exercise<br>Type</p>
													</div>
													<div class="col-xs-9 secondcell datacol">
													<span class="preCls datacol textcenter">' . ucfirst($exerciseRecord['type_title']) . '</span>
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="mobpadding exersetcolumn-xr">
												<div class="border-xr full">
													<div class="col-xs-3 firstcell borderright">
														<p class="labelcol">Featured<br>Image</p>
													</div>
													<div class="col-xs-9 secondcell datacol">
													<span class="preCls datacol textcenter">';
            if (file_exists($exerciseRecord['img_url'])) {
                $response .= '<img width="70px" class="img-responsive img-thumbnail" src="' . URL::base() . $exerciseRecord['img_url'] . '"/>';
            } else {
                $response .= '<i class="fa fa-file-image-o datacol iconsizepre"></i>';
            }
            $response .= '</span>
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="mobpadding exersetcolumn-xr">
												<div class="border-xr full">
													<div class="col-xs-3 firstcell borderright">
														<p class="labelcol">Primary<br>Muscles</p>
													</div>
													<div class="col-xs-9 secondcell datacol">
													<span class="preCls textcenter">' . (!empty($exerciseRecord['muscle_title']) ? ucfirst($exerciseRecord['muscle_title']) : '-') . '</span>
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="mobpadding exersetcolumn-xr">
												<div class="border-xr full">
													<div class="col-xs-3 firstcell borderright">
														<p class="labelcol">Description</p>
													</div>
													<div class="col-xs-9 secondcell datacol">
													<span class="preCls datacol textcenter">' . (strlen($exerciseRecord['descbr']) > 300 ? sub_str(nl2br($exerciseRecord['descbr']), 0, 299) . '...' : nl2br($exerciseRecord['descbr'])) . '</span>
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="mobpadding exersetcolumn-xr">
												<div class="border-xr full">
													<div class="col-xs-3 firstcell borderright">
														<p class="labelcol">Sequence<br>Instruction</p>
													</div>
													<div class="col-xs-9 secondcell datacol" ' . ($seqRecord && $method != 'disallow' ? 'onclick="openSequencePopup(' . "'" . $fid . "'" . ');"' : '') . '>
													<span class="preCls textcenter ' . ($seqRecord ? 'activedatacol pointers' : 'datacol') . '">' . (($seqRecord) ? 'Yes' : 'No') . '</span>
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="mobpadding exersetcolumn-xr">
												<div class="border-xr full">
													<div class="col-xs-3 firstcell borderright">
														<p class="labelcol">Video<br>Demo</p>
													</div>
													<div class="col-xs-9 secondcell datacol" ' . (!empty($exerciseRecord['feat_vid']) && $method != 'disallow' ? 'onclick="openVideoPopup(' . "'" . $exerciseRecord['feat_vid'] . "'" . ');"' : 'onclick="return false;"') . '>
													<span class="preCls textcenter ' . (!empty($exerciseRecord['feat_vid']) ? 'activedatacol' : 'datacol') . '">' . (!empty($exerciseRecord['feat_vid']) ? 'Yes' : 'No') . '</span>
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="mobpadding exersetcolumn-xr">
												<div class="border-xr full">
													<div class="col-xs-3 firstcell borderright">
														<p class="labelcol">Related<br>Exercises</p>
													</div>
													<div class="col-xs-9 secondcell datacol">
													<span class="preCls textcenter ' . ($relatedRecords > 0 ? 'activedatacol' : 'datacol') . '" ' . ($relatedRecords > 0 && $method != 'disallow' ? 'onclick="getRelatedRecords(' . "'" . $exerciseRecord["unit_id"] . "','',''" . ');"' : '') . '>' . $relatedRecords . '</span>
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="mobpadding exersetcolumn-xr">
												<div class="border-xr full">
													<div class="col-xs-3 firstcell borderright">
														<p class="labelcol">Tags</p>
													</div>
													<div class="col-xs-9 secondcell datacol" ' . ($tagCount > 0 && $method != 'disallow' ? 'onclick="getTagOfRecord(' . "'" . $exerciseRecord["unit_id"] . "'" . ')"' : '') . '>
													<span class="preCls textcenter ' . ($tagCount > 0 ? 'activedatacol' : 'datacol') . '">' . $tagCount . '</span>
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="mobpadding exersetcolumn-xr">
												<div class="border-xr full">
													<div class="col-xs-3 firstcell borderright">
														<p class="labelcol">Rating</p>
													</div>
													<div class="col-xs-9 secondcell datacol">
													<span class="preCls textcenter ' . ($rating > 0 ? 'activedatacol' : 'datacol') . '">' . $rating . '/10</span>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="workout-titlerow" id="showmorexr">
										<div class="row">
											<div class="mobpadding exersetcolumn-xr" onclick="showmoreXrdetail();">
												<div class="border-xr full activedatacol aligncenter"><div>View More</div></div>
											</div>
										</div>
									</div>
									<div class="workout-titlerow hide" id="showmorexrdetails">
										<div class="row">
											<div class="mobpadding exersetcolumn-xr">
												<div class="border-xr full">
													<div class="col-xs-3 firstcell borderright">
														<p class="labelcol">Other<br>Muscles</p>
													</div>
													<div class="col-xs-9 secondcell datacol">
													<span class="preCls textcenter">';
            $param = '-  ';
            $additionaMuscleRecord = $workoutModel->getAdditionalMusules($fid);
            if (!empty($additionaMuscleRecord) && count($additionaMuscleRecord) > 0) {
                foreach ($additionaMuscleRecord as $keys => $value) {
                    $param = ucfirst($value['muscle_title']) . ', ';
                }
            }
            $response .= substr($param, 0, -2) . '</span>
													</div>
												</div>
											</div>
										</div>';
            $equipparam = (!empty($exerciseRecord['equip_title']) ? ucfirst($exerciseRecord['equip_title']) : '') . ', ';
            $additionaEquipRecord = $workoutModel->getAdditionalEquip($exerciseRecord['unit_id']);
            if (!empty($additionaEquipRecord) && count($additionaEquipRecord) > 0) {
                foreach ($additionaEquipRecord as $keys => $value) {
                    $equipparam .= ucfirst($value['equip_title']) . ', ';
                }
            }
            $response .= '<div class="row">
											<div class="mobpadding exersetcolumn-xr">
												<div class="border-xr full">
													<div class="col-xs-3 firstcell borderright">
														<p class="labelcol">Equipment<br>Options</p>
													</div>
													<div class="col-xs-9 secondcell datacol">
													<span class="preCls textcenter">' . substr($equipparam, 0, -2) . '</span>
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="mobpadding exersetcolumn-xr">
												<div class="border-xr full">
													<div class="col-xs-3 firstcell borderright">
														<p class="labelcol">Mechanices<br>Type</p>
													</div>
													<div class="col-xs-9 secondcell datacol">
													<span class="preCls datacol textcenter">' . (!empty($exerciseRecord['mech_title']) ? ucfirst($exerciseRecord['mech_title']) : '-') . '</span>
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="mobpadding exersetcolumn-xr">
												<div class="border-xr full">
													<div class="col-xs-3 firstcell borderright">
														<p class="labelcol">Exercise<br>Level</p>
													</div>
													<div class="col-xs-9 secondcell datacol">
													<span class="preCls datacol textcenter">' . (!empty($exerciseRecord['level_title']) ? ucfirst($exerciseRecord['level_title']) : '-') . '</span>
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="mobpadding exersetcolumn-xr">
												<div class="border-xr full">
													<div class="col-xs-3 firstcell borderright">
														<p class="labelcol">Sport</p>
													</div>
													<div class="col-xs-9 secondcell datacol">
													<span class="preCls datacol textcenter">' . (!empty($exerciseRecord['sport_title']) ? ucfirst($exerciseRecord['sport_title']) : '-') . '</span>
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="mobpadding exersetcolumn-xr">
												<div class="border-xr full">
													<div class="col-xs-3 firstcell borderright">
														<p class="labelcol">Force<br>Movement</p>
													</div>
													<div class="col-xs-9 secondcell datacol">
													<span class="preCls datacol textcenter">' . (!empty($exerciseRecord['force_title']) ? ucfirst($exerciseRecord['force_title']) : '-') . '</span>
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="mobpadding exersetcolumn-xr">
												<div class="border-xr full">
													<div class="col-xs-3 firstcell borderright">
														<p class="labelcol">Other<br>Remarks</p>
													</div>
													<div class="col-xs-9 secondcell datacol">
													<span class="preCls datacol textcenter">' . (!empty($exerciseRecord['descbr']) ? ucfirst($exerciseRecord['descbr']) : '-') . '</span>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="workout-titlerow hide" id="hidemorexr">
										<div class="row">
											<div class="mobpadding exersetcolumn-xr" onclick="hidemoreXrdetail();">
												<div class="border-xr full activedatacol aligncenter"><div>View Less</div></div>
											</div>
										</div>
									</div>
									</div><div class="modal-footer"><div class="col-xs-12">';
            if (!empty($type) && (strtolower($type) == 'assigned' || strtolower($type) == 'logged')) {
                $response .= '<a data-role="none" data-ajax="false" href="javascript:void(0);" onclick="closeModelwindow(' . "'" . $modelType . "'" . ');" class="btn btn-default" >';
            } else {
                $response .= '<a data-role="none" data-ajax="false" href="javascript:void(0);"' . (!empty($foldid) ? 'onclick="closeModelwindow(' . "'" . (!empty($modelType) ? $modelType : 'FolderModal' ) . "'" . ');getworkoutpreview(' . "'" . $foldid . "'" . ')"' : ( (!empty($modelType) && $modelType == 'myModal-exercisepreview') ? 'onclick="$(' . "'#exerciselib-model'" . ').show();closeModelwindow(' . "'myModal-exercisepreview'" . ');"' : 'data-dismiss="modal"')) . ' class="btn btn-default" >';
            }
            $response .= 'close</a>' . (!empty($type) && (strtolower($type) == 'myexercise' || strtolower($type) == 'sampleexercise' || strtolower($type) == 'sharedexercise') ? '<button class="btn btn-default activedatacol ' . (!empty($type) && (strtolower($type) == 'myexercise' || strtolower($type) == 'sampleexercise' || strtolower($type) == 'sharedexercise') ? '" onclick="' . ($actionFrom == 'exercise' ? 'getXrsetOptionsPopup' : 'xrLibgetXrsetOptionsPopup') . '(' . "'" . $fid . "','" . $type . "','" . (file_exists($exerciseRecord['img_url']) ? URL::base() . $exerciseRecord['img_url'] : '') . "','" . addslashes(ucfirst($exerciseRecord['title'])) . "'" . ')"' : 'hide"') . ' data-role="none" data-ajax="false" type="button">next</button>' : '') . '</div></div></div>';
            $response .= '</div></div>';
            // preview activity feed
            $workoutModel->insertActivityFeed(5, 42, $fid);
        } elseif (!empty($action) && trim($action) == 'createExercise') {
            $exerciseRecord = array();
            $difference = true;
            $confirmxrAction = Helper_Common::getAllowAllAccessByUser($page_id, 'is_confirm_xr_hidden');
            if (is_numeric($foldid) && empty($datavalues)) {
                if (!empty($type) && strtolower($type) == 'assigned') {
                    if (!empty($foldid))
                        $exerciseRecord = $workoutModel->getExerciseSetDetailsByAssignWkout($assignId, $foldid);
                }else {
                    if (!empty($foldid))
                        $exerciseRecord = $workoutModel->getExerciseSetDetailsByWorkout($fid, $foldid);
                }
            } else
                $exerciseRecord = $datavalues;
            if ($exerciseRecord['setdetails'] != '')
                $exerciseRecord['setdetails'] = json_decode($exerciseRecord['setdetails'], true);

            if (!empty($type) && strtolower($type) == 'assigned') {
                $datetime = Helper_Common::get_default_date();
                $current = strtotime($datetime);
                $datediff = strtotime($date) - $current;
                $difference = floor($datediff / (60 * 60 * 24));
            }
            $response = '<div class="vertical-alignment-helper">
                            <div class="modal-dialog modal-md">
                                <div class="modal-content">
                                    <form data-ajax="false" action="" method="post" id="createExercise">
                                    <div class="modal-header">
                                        <div class="row">
                                            <div class="popup-title">
                                                <div class="col-xs-3">
                                                    <a data-role="none" data-ajax="false" data-text="Clicking BACK or CANCEL will discard any changes. Clicking INSERT will apply any content changes. Continue with exiting?" href="javascript:void(0);" data-notename="hide_confirm_xr" data-allow="' . ($confirmxrAction ? 'false' : 'true') . '" class="triangle confirm pointers" ' . ($method == 'create' && empty($unique_set['goal_title']) ? 'data-onclick="checkTitleExist(' . "'" . (!empty($assignId) ? $assignId : (!empty($logId) ? $logId : $fid)) . '_' . $foldid . "'" . ');"' : '') . ' >
                                                        <i class="fa fa-caret-left iconsize"></i>
                                                    </a>
                                                </div>';
            if (!empty($foldid))
                $response .= '<div class="col-xs-6">Exercise Set ' . ($method != 'preview' ? ': Edit' : '<span class="editmode hide">: Edit</span>') . '</div><input data-keyval="' . $exerciseRecord['goal_id'] . '" type="hidden" value="' . $exerciseRecord['goal_id'] . '" name="goal_id_hidden"/>';
            else
                $response .= '<div class="col-xs-6">Exercise Set</div>';
            if ($method != 'preview') {
                $response .= '<div class="col-xs-3 save-icon-button">';
                if (!empty($type) && strtolower($type) == 'assigned' && !empty($foldid) && (strpos($foldid, 'new') === false))
                    $response .= '<button data-role="none" data-ajax="false" class="btn btn-default activedatacol" type="button" style="background-color:#fff" onclick="return editExercise(' . "'itemSet_" . $assignId . '_' . $foldid . "','" . $goalOrder . "'" . ');">insert</button>';
                elseif (!empty($type) && strtolower($type) == 'logged' && !empty($foldid) && (strpos($foldid, 'new') === false))
                    $response .= '<button data-role="none" data-ajax="false" class="btn btn-default activedatacol" type="button" style="background-color:#fff" onclick="return editExercise(' . "'itemSet_" . $logId . '_' . $foldid . "','" . $goalOrder . "'" . ');">insert</button>';
                elseif (!empty($foldid) && (strpos($foldid, 'new') === false))
                    $response .= '<button data-role="none" data-ajax="false" class="btn btn-default activedatacol" type="button" style="background-color:#fff" onclick="return editExercise(' . "'itemSet_" . $fid . '_' . $foldid . "','" . $goalOrder . "'" . ');">insert</button>';
                else {
                    $response .= '<button data-role="none" data-ajax="false" class="btn btn-default activedatacol" type="button" style="background-color:#fff" onclick="return addnewExercise(' . "'" . (!empty($assignId) ? $assignId : (!empty($logId) ? $logId : $fid)) . '_' . $foldid . "'" . ');">insert</button>';
                }
            } else {
                $response .= '<div class="col-xs-3 save-icon-button activedatacol"';
                $response .= ' onclick="getTemplateOfExerciseSetActionByprev(' . "'" . $foldid . "','myOptionsModalAjax','" . $goalOrder . "'" . ');" >';
                $response .= '<i class="fa fa-ellipsis-h iconsize"></i>';
            }
            $response .= '</div>
                            </div>
                                </div>
                                    </div>
                                        <div class="modal-body">';
            $response .= '<div class="aligncenter">
                            <div class="col-xs-12 errormsg hide" style="color:red;"></div>
                    </div>';
            $response .= '<div class="workout-titlerow">
                            <div class="row">
                                <div class="mobpadding">
                                    <div class="border borderbox">
                                        <div class="col-xs-4 borderright labelcol">
                                                <div>Exercise Title</div>
                                        </div>';
            if ($method != 'preview')
                $response .= '<div id="title" class="col-xs-8 pointers" onclick="getexercisesetTemplateAjaxEdit(' . "'" . $foldid . "','" . $goalOrder . "','title'" . ');"><div class="activedatacol display-flex"><span id="exerciselibimg">';
            else
                $response .= '<div class="col-xs-8"><div class="activedatacol display-flex"><span id="exerciselibimg">';
            if (!empty($exerciseRecord['goal_unit_id'])) {
                if (isset($exerciseRecord['img_url']) && !empty($exerciseRecord['img_url'])) {
                    $exerciseRecordArray = explode('/', $exerciseRecord['img_url']);
                    if ($exerciseRecordArray['0'] == '')
                        $exerciseRecord['img_url'] = str_replace('/assets/', 'assets/', $exerciseRecord['img_url']);
                }
                if (isset($exerciseRecord['img_url']) && !empty($exerciseRecord['img_url']) && file_exists($exerciseRecord['img_url']))
                    $response .= '<img width="50px;" style="cursor:pointer;padding-right: 10px;" src="' . URL::base() . $exerciseRecord['img_url'] . '" /></span><span id="exercise_title_hidden_text" class="' . ($method == 'preview' ? 'preCls datacol' : 'activedatacol' ) . ' textcenter" style="vertical-align: middle;">' . (isset($exerciseRecord['goal_title']) ? $exerciseRecord['goal_title'] : '') . '</span>';
                else
                    $response .= '<i class="fa fa-file-image-o datacol" style="font-size:50px;padding-right: 10px;"></i></span><span id="exercise_title_hidden_text" class="' . ($method == 'preview' ? 'preCls datacol' : 'activedatacol' ) . ' textcenter" style="padding-bottom: 0px;vertical-align: middle;">' . (isset($exerciseRecord['goal_title']) ? $exerciseRecord['goal_title'] : '') . '</span>';
            }else {
                if ($method == 'create')
                    $response .= '<i class="fa fa-pencil-square datacol ' . (empty($exerciseRecord['goal_title']) ? 'hide' : '') . '" style="font-size:50px;padding-right: 10px;"></i></span><span id="exercise_title_hidden_text" class="' . ($method == 'preview' ? 'preCls datacol' : 'activedatacol' ) . ' textcenter" style="padding-bottom: 0px;vertical-align: middle;">' . (isset($exerciseRecord['goal_title']) && !empty($exerciseRecord['goal_title']) ? $exerciseRecord['goal_title'] : '<span class="inactivedatacol">Click to Search or Type</span>') . '</span>';
                else
                    $response .= '<i class="fa fa-pencil-square datacol" style="font-size:50px;padding-right: 10px;"></i></span><span id="exercise_title_hidden_text" class="' . ($method == 'preview' ? 'preCls datacol' : 'activedatacol' ) . ' textcenter" style="padding-bottom: 0px;vertical-align: middle;">' . (isset($exerciseRecord['goal_title']) ? $exerciseRecord['goal_title'] : '') . '</span>';
            }
            $response .= '</div>
                                        <input data-keyval="' . $exerciseRecord['goal_id'] . '" type="hidden" name="exercise_title_hidden" id="exercise_title_hidden" value="' . (isset($exerciseRecord['goal_title']) ? $exerciseRecord['goal_title'] : '') . '" />
                                        <input data-keyval="' . $exerciseRecord['goal_id'] . '" type="hidden" name="exercise_unit_hidden" id="exercise_unit_hidden" value="' . (isset($exerciseRecord['goal_unit_id']) ? $exerciseRecord['goal_unit_id'] : '') . '" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>';
            $allowDuplicate = (isset($exerciseRecord['goal_title']) && !empty($exerciseRecord['goal_title']) ? true : false);
            if (is_array($exerciseRecord['setdetails']) && count($exerciseRecord['setdetails']) > 0) {
                $response .= '<div class="row xrsets-tab" id="xrsets-tab" ' . ((is_array($exerciseRecord['setdetails']) && count($exerciseRecord['setdetails']) > 1) ? '' : 'style="display:none;"') . '>
                                <div class="mobpadding">
                                    <div class="border datatab">
                                        <div class="borderbox col-xs-4 removtopbor">
                                            <div class="removepading wrapword labelcol">Sets:</div>
                                        </div>
                                        <div class="borderbox col-xs-8 removtopbor">
                                            <ul class="nav nav-tabs setlist-tab">';
                $loop = 1;
                foreach ($exerciseRecord['setdetails'] as $setkey => $setvalue) {
                    if (!isset($setvalue['removed_set'])) {
                        $response .= '<li class="' . ($xrsetid == $setkey ? 'in active' : ($loop == 1 && empty($xrsetid) ? 'in active' : '')) . '" data-setid="' . $setkey . '"><a href="#set_' . $setkey . '" data-toggle="tab">Set ' . $loop . '</a><i class="fa fa-times delete-set"></i></li>';
                        $loop++;
                    }
                }
                $response .= '</ul>
                                        </div>
                                    </div>
                                </div>
                            </div>';
                $response .= '<div class="tab-content set-tab">';
                $loop = 0;
                foreach ($exerciseRecord['setdetails'] as $setkey => $setvalue) {
                    if (!isset($setvalue['removed_set'])) {
                        $basicChecked = 'checked="checked"';
                        $advancedChecked = '';
                        $actioncount = 0;
                        if ((isset($setvalue['goal_rate_id']) && $setvalue['goal_rate_id'] > 0) || (isset($setvalue['goal_int_id']) && $setvalue['goal_int_id'] > 0) || (isset($setvalue['goal_angle_id']) && $setvalue['goal_angle_id'] > 0)) {
                            $advancedChecked = 'checked="checked"';
                            $basicChecked = '';
                            if (isset($setvalue['goal_rate_id']) && $setvalue['goal_rate_id'] > 0)
                                $actioncount += 1;
                            if (isset($setvalue['goal_int_id']) && $setvalue['goal_int_id'] > 0)
                                $actioncount += 1;
                            if (isset($setvalue['goal_angle_id']) && $setvalue['goal_angle_id'] > 0)
                                $actioncount += 1;
                        }
                        $response .= '<div id="set_' . $setkey . '" data-setid="' . $setkey . '" class="tab-pane fade ' . ($xrsetid == $setkey ? 'in active' : ($loop == 0 && empty($xrsetid) ? 'in active' : '')) . '">
											<div class="row">
												<div class="mobpadding">
													<div class="datatab">
														<div class="col-xs-12 removbotbor">
															<div class="removepading wrapword labelcol">
																<div class="btn-group">
																	Variables: 
																	<label>
																		<input data-keyval="' . $setkey . '" type="radio" id="basic_' . $setkey . '" name="xrtype_' . $setkey . '" value="1" onclick="hideXrVariables(this);"' . $basicChecked . ' data-varid="set_' . $setkey . '"/> Basic
																	</label> 
																	<label>
																		<input data-keyval="' . $setkey . '" type="radio" id="advance_' . $setkey . '" name="xrtype_' . $setkey . '" value="2" ' . $advancedChecked . ' onclick="showXrVariables(this);" data-varid="set_' . $setkey . '"/> Advanced <span id="showcountXrvariable" class="actioncount ' . ($actioncount == 0 ? 'hide' : '') . '">' . $actioncount . '</span>
																	</label> 
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
											<!-- ============================= -->
											<!-- ======== Repetitions ======== -->
											<!-- ============================= -->
											
											<div class="row">
												<div class="mobpadding">
													<div class="border datatab">
														<div class="borderbox col-xs-4 removbotbor">
															<div class="removepading wrapword labelcol">Repetitions</div>
														</div>';
                        if ($method != 'preview')
                            $response .= '<div id="openlink-reps" class="borderbox activedatacol col-xs-8 removbotbor pointers" onclick="getexercisesetTemplateAjaxEdit(' . "'" . $setkey . "','" . $goalOrder . "','reps'" . ');">';
                        else
                            $response .= '<div class="borderbox activedatacol col-xs-8 removbotbor">';
                        $response .= '<div class="textcenter">
																<span class="' . ($method == 'preview' ? 'preCls datacol' : 'activedatacol' ) . ' exercise_repetitions">';
                        $parameter = '<span class="inactivedatacol">Click to modify</span>';
                        if (isset($setvalue['goal_reps']) && $setvalue['goal_reps'] > 0) {
                            $parameter = (($setvalue['primary_reps']) ? '<span class="ashstrick">*</span> ' : '') . $setvalue['goal_reps'] . ' reps';
                        }
                        $response .= $parameter . '</span>
																<input data-keyval="' . $setkey . '" type="hidden" value="' . (isset($setvalue['goal_reps']) ? $setvalue['goal_reps'] : '0') . '" class="exercise_repetitions_hidden" name="exercise_repetitions_hidden"/>
																<input data-keyval="' . $setkey . '" type="hidden" value="0" class="exercise_unit_repetitions_hidden" name="exercise_unit_repetitions_hidden"/>
															</div>
														</div>
														<!-- removed right column -->
													</div>
												</div>
											</div>
											
											<!-- ============================ -->
											<!-- ======== Resistance ======== -->
											<!-- ============================ -->
											
											<div class="row">
												<div class="mobpadding">
													<div class="border datatab">
														<div class="borderbox col-xs-4 removbotbor">
															<div class="removepading wrapword labelcol">Resistance</div>
														</div>';
                        if ($method != 'preview')
                            $response .= '<div id="openlink-resist" class="borderbox activedatacol col-xs-8 removbotbor pointers" onclick="getexercisesetTemplateAjaxEdit(' . "'" . $setkey . "','" . $goalOrder . "','resist'" . ');">';
                        else
                            $response .= '<div class="borderbox activedatacol col-xs-8 removbotbor">';
                        $response .= '<div class="textcenter">
																<span class="' . ($method == 'preview' ? 'preCls datacol' : 'activedatacol' ) . ' exercise_resistance">';
                        $parameter = '<span class="inactivedatacol">Click to modify</span>';
                        if (isset($setvalue['goal_resist_id']) && $setvalue['goal_resist_id'] > 0 && isset($setvalue['modified_by']) && $setvalue['modified_by']) {
                            $parameter = (($setvalue['primary_resist']) ? '<span class="ashstrick">*</span> ' : '') . $setvalue['goal_resist'] . ' <span>' . Model::instance('Model/workouts')->getAssignGoalVars('resist', $setvalue['goal_resist_id']) . '</span>';
                        } elseif (isset($setvalue['goal_resist_id']) && $setvalue['goal_resist_id'] > 0) {
                            $parameter = (($setvalue['primary_resist']) ? '<span class="ashstrick">*</span> ' : '') . $setvalue['goal_resist'] . ' <span>' . Model::instance('Model/workouts')->getGoalValues('resist', $setvalue['goal_resist_id']) . '</span>';
                        }
                        $response .= $parameter . '</span>
																<input data-keyval="' . $setkey . '" type="hidden" value="' . (isset($setvalue['goal_resist']) ? $setvalue['goal_resist'] : '') . '" class="exercise_resistance_hidden" name="exercise_resistance_hidden"/>
																<input data-keyval="' . $setkey . '" type="hidden" value="' . (isset($setvalue['goal_resist_id']) ? $setvalue['goal_resist_id'] : '') . '" class="exercise_unit_resistance_hidden" name="exercise_unit_resistance_hidden"/>
															</div>
														</div>
													</div>
												</div>
											</div>
											
											<!-- ====================== -->
											<!-- ======== Time ======== -->
											<!-- ====================== -->
											
											<div class="row">
												<div class="mobpadding">
													<div class="border datatab">
														<div class="borderbox col-xs-4 removbotbor">
															<div class="removepading wrapword labelcol">Time</div>
														</div>';
                        if ($method != 'preview')
                            $response .= '<div id="openlink-time" class="borderbox activedatacol col-xs-8 removbotbor pointers" onclick="getexercisesetTemplateAjaxEdit(' . "'" . $setkey . "','" . $goalOrder . "','time'" . ');">';
                        else
                            $response .= '<div class="borderbox activedatacol col-xs-8 removbotbor">';
                        $response .= '<div class="textcenter">
																<span class="' . ($method == 'preview' ? 'preCls datacol' : 'activedatacol' ) . ' exercise_time">';
                        $parameter = '<span class="inactivedatacol">Click to modify</span>';
                        $paratime = "";
                        if ((isset($setvalue['goal_time_hh']) && $setvalue['goal_time_hh'] > 0) || (isset($setvalue['goal_time_mm']) && $setvalue['goal_time_mm'] > 0) || (isset($setvalue['goal_time_ss']) && $setvalue['goal_time_ss'] > 0)) {
                            $parameter = (($setvalue['primary_time']) ? '<span class="ashstrick">*</span> ' : '') . '<span>' . $setvalue['goal_time_hh'] . ':' . $setvalue['goal_time_mm'] . ':' . $setvalue['goal_time_ss'] . '</span>';
                            $paratime = $setvalue['goal_time_hh'] . ':' . $setvalue['goal_time_mm'] . ':' . $setvalue['goal_time_ss'];
                        }
                        $response .= $parameter . '</span>
																<input data-keyval="' . $setkey . '" type="hidden" value="' . $paratime . '" class="exercise_time_hidden" name="exercise_time_hidden"/>
															</div>
														</div>
													</div>
												</div>
											</div>
											
											<!-- ========================== -->
											<!-- ======== Distance ======== -->
											<!-- ========================== -->
											
											<div class="row">
												<div class="mobpadding">
													<div class="border datatab">
												
													<div class="borderbox col-xs-4 removbotbor">
															<div class="removepading wrapword labelcol">Distance</div>
														</div>';
                        if ($method != 'preview')
                            $response .= '<div id="openlink-dist" class="borderbox activedatacol col-xs-8 removbotbor pointers" onclick="getexercisesetTemplateAjaxEdit(' . "'" . $setkey . "','" . $goalOrder . "','dist'" . ');">';
                        else
                            $response .= '<div class="borderbox activedatacol col-xs-8 removbotbor">';
                        $response .= '<div class="textcenter">
																<span class="' . ($method == 'preview' ? 'preCls datacol' : 'activedatacol' ) . ' textcenter exercise_distance">';
                        $parameter = '<span class="inactivedatacol">Click to modify</span>';
                        if (isset($setvalue['goal_dist_id']) && $setvalue['goal_dist_id'] > 0 && isset($setvalue['modified_by']) && $setvalue['modified_by']) {
                            $parameter = (($setvalue['primary_dist']) ? '<span class="ashstrick">*</span> ' : '') . $setvalue['goal_dist'] . ' <span>' . Model::instance('Model/workouts')->getAssignGoalVars('dist', $setvalue['goal_dist_id']) . '</span>';
                        } elseif (isset($setvalue['goal_dist_id']) && $setvalue['goal_dist_id'] > 0) {
                            $parameter = (($setvalue['primary_dist']) ? '<span class="ashstrick">*</span> ' : '') . $setvalue['goal_dist'] . ' <span>' . Model::instance('Model/workouts')->getGoalValues('dist', $setvalue['goal_dist_id']) . '</span>';
                        }
                        $response .= $parameter . '</span>
																<input data-keyval="' . $setkey . '" type="hidden" value="' . (isset($setvalue['goal_dist']) ? $setvalue['goal_dist'] : '') . '" class="exercise_distance_hidden" name="exercise_distance_hidden"/>
																<input data-keyval="' . $setkey . '" type="hidden" value="' . (isset($setvalue['goal_dist_id']) ? $setvalue['goal_dist_id'] : '') . '" class="exercise_unit_distance_hidden" name="exercise_unit_distance_hidden"/>
															</div>
														</div>
													</div>
												</div>
											</div>
											<!-- ====================== -->
											<!-- ======== Pace ======== -->
											<!-- ====================== -->
											<div class="row hideadvance ' . (!empty($basicChecked) ? 'hide' : '') . '">
												<div class="mobpadding">
													<div class="border datatab">
														<div class="borderbox col-xs-4 removbotbor">
															<div class="removepading wrapword labelcol">Pace</div>
														</div>';
                        if ($method != 'preview')
                            $response .= '<div id="openlink-rate" class="borderbox activedatacol col-xs-8 removbotbor pointers" onclick="getexercisesetTemplateAjaxEdit(' . "'" . $setkey . "','" . $goalOrder . "','rate'" . ');">';
                        else
                            $response .= '<div class="borderbox activedatacol col-xs-8 removbotbor">';
                        $response .= '<div class="textcenter">
																<span class="' . ($method == 'preview' ? 'preCls datacol' : 'activedatacol' ) . ' textcenter exercise_rate">';
                        $parameter = '<span class="inactivedatacol">Click to modify</span>';
                        if (isset($setvalue['goal_rate_id']) && $setvalue['goal_rate_id'] > 0 && isset($setvalue['modified_by']) && $setvalue['modified_by']) {
                            $parameter = (($setvalue['primary_rate']) ? '<span class="ashstrick">*</span> ' : '') . $setvalue['goal_rate'] . ' <span>' . Model::instance('Model/workouts')->getAssignGoalVars('rate', $setvalue['goal_id']) . '</span>';
                        } elseif (isset($setvalue['goal_rate_id']) && $setvalue['goal_rate_id'] > 0) {
                            $parameter = (($setvalue['primary_rate']) ? '<span class="ashstrick">*</span> ' : '') . $setvalue['goal_rate'] . ' <span>' . Model::instance('Model/workouts')->getGoalValues('rate', $setvalue['goal_rate_id']) . '</span>';
                        }
                        $response .= $parameter . '</span>
																<input data-keyval="' . $setkey . '" type="hidden" value="' . (isset($setvalue['goal_rate']) ? $setvalue['goal_rate'] : '') . '" class="exercise_rate_hidden" name="exercise_rate_hidden"/>
																<input data-keyval="' . $setkey . '" type="hidden" value="' . (isset($setvalue['goal_rate_id']) ? $setvalue['goal_rate_id'] : '') . '" class="exercise_unit_rate_hidden" name="exercise_unit_rate_hidden"/>
															</div>
														</div>
													</div>
												</div>
											</div>
											<!-- ============================= -->
											<!-- ======== Inner Drive ======== -->
											<!-- ============================= -->
											<div class="row hideadvance ' . (!empty($basicChecked) ? 'hide' : '') . '">
												<div class="mobpadding">
													<div class="border datatab">
													<div class="borderbox col-xs-4 removbotbor">
															<div class="removepading wrapword labelcol">Inner Drive</div>
														</div>';
                        if ($method != 'preview')
                            $response .= '<div id="openlink-int" class="borderbox activedatacol col-xs-8 removbotbor pointers" onclick="getexercisesetTemplateAjaxEdit(' . "'" . $setkey . "','" . $goalOrder . "','int'" . ');">';
                        else
                            $response .= '<div class="borderbox activedatacol col-xs-8 removbotbor">';
                        $response .= '<div class="textcenter">
																<span class="' . ($method == 'preview' ? 'preCls datacol' : 'activedatacol' ) . ' textcenter exercise_innerdrive">';
                        $parameter = '<span class="inactivedatacol">Click to modify</span>';
                        if (isset($setvalue['goal_int_id']) && $setvalue['goal_int_id'] > 0 && isset($setvalue['modified_by']) && $setvalue['modified_by']) {
                            $parameter = (($setvalue['primary_int']) ? '<span class="ashstrick">*</span> ' : '') . '<span>' . Model::instance('Model/workouts')->getAssignGoalVars('int', $setvalue['goal_id']) . '</span> Int';
                        } elseif (isset($setvalue['goal_int_id']) && $setvalue['goal_int_id'] > 0) {
                            $parameter = (($setvalue['primary_int']) ? '<span class="ashstrick">*</span> ' : '') . '<span>' . Model::instance('Model/workouts')->getGoalValues('int', $setvalue['goal_int_id']) . '</span> Int';
                        }
                        $response .= $parameter . '</span>
																<input data-keyval="' . $setkey . '" type="hidden" value="' . (isset($setvalue['goal_int_id']) ? $setvalue['goal_int_id'] : '') . '" class="exercise_innerdrive_hidden" name="exercise_innerdrive_hidden"/>
																<input data-keyval="' . $setkey . '" type="hidden" value="0" class="exercise_unit_innerdrive_hidden" name="exercise_unit_innerdrive_hidden"/>
															</div>
														</div>
													</div>
												</div>
											</div>
											<!-- ======================= -->
											<!-- ======== Angle ======== -->
											<!-- ======================= -->
											<div class="row hideadvance ' . (!empty($basicChecked) ? 'hide' : '') . '">
												<div class="mobpadding">
													<div class="border datatab">
														<div class="borderbox col-xs-4 removbotbor">
															<div class="removepading wrapword labelcol">Angle</div>
														</div>';
                        if ($method != 'preview')
                            $response .= '<div id="openlink-angle" class="borderbox activedatacol col-xs-8 pointers removbotbor" onclick="getexercisesetTemplateAjaxEdit(' . "'" . $setkey . "','" . $goalOrder . "','angle'" . ');">';
                        else
                            $response .= '<div class="borderbox activedatacol col-xs-8 removbotbor">';
                        $response .= '<div class="textcenter">
																<span class="' . ($method == 'preview' ? 'preCls datacol' : 'activedatacol' ) . ' textcenter exercise_angle">';
                        $parameter = '<span class="inactivedatacol">Click to modify</span>';
                        if (isset($setvalue['goal_angle_id']) && $setvalue['goal_angle_id'] > 0 && isset($setvalue['modified_by']) && $setvalue['modified_by']) {
                            $parameter = (($setvalue['primary_angle']) ? '<span class="ashstrick">*</span> ' : '') . $setvalue['goal_angle'] . ' %  <span>' . Model::instance('Model/workouts')->getAssignGoalVars('angle', $setvalue['goal_id']) . '</span>';
                        } elseif (isset($setvalue['goal_angle_id']) && $setvalue['goal_angle_id'] > 0) {
                            $parameter = (($setvalue['primary_angle']) ? '<span class="ashstrick">*</span> ' : '') . $setvalue['goal_angle'] . ' %  <span>' . Model::instance('Model/workouts')->getGoalValues('angle', $setvalue['goal_angle_id']) . '</span>';
                        }
                        $response .= $parameter . '</span>
																<input data-keyval="' . $setkey . '" type="hidden" value="' . (isset($setvalue['goal_angle']) ? $setvalue['goal_angle'] : '') . '" class="exercise_angle_hidden" name="exercise_angle_hidden"/>
																<input data-keyval="' . $setkey . '" type="hidden" value="' . (isset($setvalue['goal_angle_id']) ? $setvalue['goal_angle_id'] : '') . '" class="exercise_unit_angle_hidden" name="exercise_unit_angle_hidden"/>
															</div>
														</div>
													</div>
												</div>
											</div>
											<!-- ============================ -->
											<!-- ======== Rest After ======== -->
											<!-- ============================ -->
											<div class="row">
												<div class="mobpadding">
													<div class="border datatab">
														<div class="borderbox col-xs-4 removbotbor">
															<div class="removepading wrapword labelcol">Rest After</div>
														</div>';
                        if ($method != 'preview')
                            $response .= '<div id="openlink-rest" class="borderbox activedatacol col-xs-8 pointers removbotbor" onclick="getexercisesetTemplateAjaxEdit(' . "'" . $setkey . "','" . $goalOrder . "','rest'" . ');">';
                        else
                            $response .= '<div class="borderbox activedatacol col-xs-8 removbotbor">';
                        $response .= '<div class="textcenter datacol">
																<span class="' . ($method == 'preview' ? 'preCls datacol' : 'activedatacol' ) . ' textcenter exercise_rest">';
                        $parameter = '<span class="inactivedatacol">Click to modify</span>';
                        $pararest = '';
                        if (isset($setvalue['goal_rest_mm']) && isset($setvalue['goal_rest_ss']) && $setvalue['goal_rest_mm'] + $setvalue['goal_rest_ss'] > 0) {
                            if ($setvalue['goal_rest_mm'] > 0 || $setvalue['goal_rest_ss'] > 0) {
                                $parameter = (($setvalue['primary_rest']) ? '<span class="ashstrick">*</span> ' : '') . $setvalue['goal_rest_mm'];
                                if ($setvalue['goal_rest_ss'] > 0)
                                    $parameter .= ':' . $setvalue['goal_rest_ss'];
                                else
                                    $parameter .= ':00';
                            }
                            $pararest = str_replace(array('-', '<span class="ashstrick">*</span>'), '', $parameter);
                        }
                        $response .= $parameter . '</span>
																<input data-keyval="' . $setkey . '" type="hidden" value="' . $pararest . '" class="exercise_rest_hidden" name="exercise_rest_hidden"/>
											        		</div>
														</div>
													</div>
												</div>
											</div>
											<!-- ========================= -->
											<!-- ======== Remarks ======== -->
											<!-- ========================= -->
											<div class="row">
												<div class="mobpadding">
													<div class="border datatab">
														<div class="borderbox col-xs-4">
															<div class="removepading wrapword labelcol">Remarks</div>
														</div>';
                        if ($method != 'preview')
                            $response .= '<div id="openlink-remarks" class="borderbox col-xs-8 datacol pointers" onclick="getexercisesetTemplateAjaxEdit(' . "'" . $setkey . "','" . $goalOrder . "','remarks'" . ');">';
                        else
                            $response .= '<div class="borderbox col-xs-8 datacol">';
                        $response .= '<div class="textcenter datacol"><span class="' . ($method == 'preview' ? 'preCls datacol' : 'activedatacol' ) . ' textcenter exercise_remark">' . ((isset($setvalue['goal_remarks']) && !empty($setvalue['goal_remarks'])) ? $setvalue['goal_remarks'] : (isset($setvalue['descbr']) && !empty($setvalue['descbr']) ? $setvalue['descbr'] : '<span class="inactivedatacol">Click to modify</span>')) . '</span>
															<input data-keyval="' . $setkey . '" type="hidden" value="' . ((isset($setvalue['goal_remarks']) && !empty($setvalue['goal_remarks'])) ? $setvalue['goal_remarks'] : (isset($setvalue['descbr']) ? $setvalue['descbr'] : '')) . '" class="exercise_remark_hidden" name="exercise_remark_hidden"/>
															<input data-keyval="' . $setkey . '" type="hidden" value="' . (isset($setvalue['primary_time']) ? $setvalue['primary_time'] : '0') . '" id="primary_time" class="exercise_priority_hidden" name="primary_time"/>
															<input data-keyval="' . $setkey . '" type="hidden" value="' . (isset($setvalue['primary_dist']) ? $setvalue['primary_dist'] : '0') . '" id="primary_dist" class="exercise_priority_hidden" name="primary_dist"/>
															<input data-keyval="' . $setkey . '" type="hidden" value="' . (isset($setvalue['primary_reps']) ? $setvalue['primary_reps'] : '0') . '" id="primary_reps" class="exercise_priority_hidden" name="primary_reps"/>
															<input data-keyval="' . $setkey . '" type="hidden" value="' . (isset($setvalue['primary_resist']) ? $setvalue['primary_resist'] : '0') . '" id="primary_resist" class="exercise_priority_hidden" name="primary_resist"/>
															<input data-keyval="' . $setkey . '" type="hidden" value="' . (isset($setvalue['primary_rate']) ? $setvalue['primary_rate'] : '0') . '" id="primary_rate" class="exercise_priority_hidden" name="primary_rate"/>
															<input data-keyval="' . $setkey . '" type="hidden" value="' . (isset($setvalue['primary_angle']) ? $setvalue['primary_angle'] : '0') . '" id="primary_angle" class="exercise_priority_hidden" name="primary_angle"/>
															<input data-keyval="' . $setkey . '" type="hidden" value="' . (isset($setvalue['primary_rest']) ? $setvalue['primary_rest'] : '0') . '" id="primary_rest" class="exercise_priority_hidden" name="primary_rest"/>
															<input data-keyval="' . $setkey . '" type="hidden" value="' . (isset($setvalue['primary_int']) ? $setvalue['primary_int'] : '0') . '" id="primary_int" class="exercise_priority_hidden" name="primary_int"/></div>
														</div>
													</div>
												</div>
											</div>
										</div>';
                        $loop++;
                    }
                }
                $response .= '</div>
									<div id="clone-tab" class="row clone-tab ' . ($allowDuplicate ? '' : 'hide') . '">
										<div class="mobpadding">
											<div class="border datatab">
												<div class="borderbox col-xs-12 removtopbor">
													<div class="removepading text-right">
														<button data-role="none" data-ajax="false" class="btn btn-default activedatacol" type="button" style="background-color:#fff" onclick="cloneXrsetTab();">Duplicate</button>
													</div>
												</div>
											</div>
										</div>
									</div>';
            }
            $response .= '</div><div class="modal-footer"><button style="margin-right: 20px;" data-role="none" data-ajax="false" type="button" ' . ($method == 'create' && empty($unique_set['goal_title']) ? 'data-onclick="checkTitleExist(' . "'" . (!empty($assignId) ? $assignId : (!empty($logId) ? $logId : $fid)) . '_' . $foldid . "'" . ');"' : '') . ' class="btn btn-default confirm pointers" data-notename="hide_confirm_xr" data-allow="' . ($confirmxrAction ? 'false' : 'true') . '" data-text="Clicking BACK or CANCEL will discard any changes. Clicking INSERT will apply any content changes. Continue with exiting?">close</button>';
            if ($method != 'preview') {
                if (!empty($type) && strtolower($type) == 'assigned' && !empty($foldid) && (strpos($foldid, 'new') === false))
                    $response .= '<button data-role="none" data-ajax="false" class="btn btn-default activedatacol" type="button" style="background-color:#fff" onclick="return editExercise(' . "'itemSet_" . $assignId . '_' . $foldid . "','" . $goalOrder . "'" . ');">insert</button>';
                elseif (!empty($type) && strtolower($type) == 'logged' && !empty($foldid) && (strpos($foldid, 'new') === false))
                    $response .= '<button data-role="none" data-ajax="false" class="btn btn-default activedatacol" type="button" style="background-color:#fff" onclick="return editExercise(' . "'itemSet_" . $logId . '_' . $foldid . "','" . $goalOrder . "'" . ');">insert</button>';
                elseif (!empty($foldid) && (strpos($foldid, 'new') === false) && (strpos($foldid, 'new') === false))
                    $response .= '<button data-role="none" data-ajax="false" class="btn btn-default activedatacol" type="button" style="background-color:#fff" onclick="return editExercise(' . "'itemSet_" . $fid . '_' . $foldid . "','" . $goalOrder . "'" . ');">insert</button>';
                else {
                    $response .= '<button data-role="none" data-ajax="false" class="btn btn-default activedatacol" type="button" style="background-color:#fff" onclick="return addnewExercise(' . "'" . (!empty($assignId) ? $assignId : (!empty($logId) ? $logId : $fid)) . '_' . $foldid . "'" . ');">insert</button';
                }
            } else {
                $response .= '<button data-role="none" data-ajax="false" class="btn" type="button" style="background-color:#fff" ';
                $response .= ' onclick="getTemplateOfExerciseSetActionByprev(' . "'" . $foldid . "','myOptionsModalAjax','" . $goalOrder . "'" . ');" >';
                $response .= '<i class="fa fa-ellipsis-h iconsize"></i></button>';
            }
            $response .= '</form></div></div></div></div></div>';
            $response .= (($addOptions != 'openlink-time' && $addOptions != 'openlink-rest') ? '<script>setTimeout(function(){' . (!empty($addOptions) ? "$('div#set_" . $xrsetid . "  #" . $addOptions . "').trigger('click')" : '') . '}, 200);</script>' : '<script>' . "$('div#set_" . $xrsetid . " #" . $addOptions . "').trigger('click');" . '</script>');
        } elseif (!empty($action) && trim($action) == 'createNewworkout') {
            $confirmwkAction = Helper_Common::getAllowAllAccessByUser($page_id, 'is_confirm_wkout_hidden');
            $colorsRecord = $workoutModel->getColors();
            $focusRecord = $workoutModel->getAllFocus();
            $workoutRecord = $exerciseRecord = array();
            $allowExistFlag = false;
            $from_wkout = 0;
            if (!empty($fid)) {
                if (!empty($type) && strtolower($type) == 'wrkout') {
                    $workoutRecord = $workoutModel->getworkoutById($user->pk(), $fid);
                    $exerciseRecord = $workoutModel->getExerciseSet($fid);
                } elseif (!empty($type) && strtolower($type) == 'sample') {
                    $workoutRecord = $workoutModel->getSampleworkoutById('0', $fid);
                    $exerciseRecord = $workoutModel->getSampleExerciseSet($fid);
                    $from_wkout = 2;
                } elseif (!empty($type) && strtolower($type) == 'shared') {
                    $workoutRecord = $workoutModel->getShareworkoutById($user->pk(), $fid);
                    $exerciseRecord = $workoutModel->getExerciseSets('shared', $fid);
                    $from_wkout = 1;
                } elseif (!empty($type) && strtolower($type) == 'assigned') {
                    $workoutRecord = $workoutModel->getAssignworkoutById($fid, $user->pk());
                    $exerciseRecord = $workoutModel->getExerciseSets('assigned', $fid);
                    $from_wkout = 3;
                } elseif (!empty($type) && strtolower($type) == 'wkoutlog') {
                    $workoutRecord = $workoutModel->getLoggedworkoutById($fid);
                    $exerciseRecord = $workoutModel->getExerciseSets('wkoutlog', $fid);
                    $from_wkout = 4;
                }
                $allowExistFlag = true;
            }
            $response = '<div class="vertical-alignment-helper">';
            if (!empty($fid) && !empty($type) && ($method == 'addworkoutLog' || $method == 'addworkoutLogwkout' || $method == 'addworkoutAssign' || $method == 'addworkout')) {
                $response .= '<input type="hidden" value="' . $fid . '" name="wkout_id" /><input type="hidden" value="' . $from_wkout . '" name="from_wkout" /><input type="hidden" value="' . $type . '" name="method" />';
            } elseif ($method == 'addworkoutLog' || $method == 'addworkoutLogwkout' || $method == 'addworkoutAssign' || $method == 'addworkout') {
                $response .= '<input type="hidden" value="' . ($method == 'addworkoutLog' || $method == 'addworkoutLogwkout' ? 'logged' : 'assigned') . '" id="type_method" />';
            }
            $response .= '<div class="modal-dialog modal-md"><div class="modal-content">
								<form data-ajax="false" data-role="none" action="" method="post" id="createNewworkout">
								<div class="modal-header">
									<div class="row">
										<div class="popup-title">
											<div class="col-xs-3">
												<a data-role="none" data-ajax="false" data-text="Clicking BACK or CANCEL will discard any changes. Clicking MORE will display record options such as SAVE. Continue with exiting?" class="triangle confirm pointers" data-allow="' . ($confirmwkAction ? 'false' : 'true') . '" data-notename="hide_confirm_wkout" href="javascript:void(0);" ' . ($method == 'addworkoutLog' || $method == 'addworkoutAssign' || $method == 'addworkout' || $method == 'addworkoutLogwkout' ? 'data-onclick="addAssignWorkoutsByDate(' . "'" . date('Y-m-d', strtotime((!empty($date) ? $date . ' 00:00:00' : Helper_Common::get_default_date() . ' 00:00:00'))) . "','" . (!empty($foldid) ? $foldid : 0) . "','0','" . ($method == 'addworkoutLog' ? '-logged' : ($method == 'addworkoutAssign' ? '-assign' : (trim($method) == 'addworkout' ? '-workout' : ($method == 'addworkoutLogwkout' ? '-loggedwkout' : $type)))) . "'" . ');"' : '') . ' >
													<i class="fa fa-caret-left iconsize"></i>
												</a>
											</div>
											<div class="col-xs-6">' . ($method == 'addworkoutAssign' ? (empty($type) ? 'Assigned Plan : custom' : 'Assigned Plan : Edit (' . $workoutRecord['wkout_title'] . ')') : (($method == 'addworkoutLog' || $method == 'addworkoutLogwkout') ? (empty($type) ? 'Workout Journal Plan : custom' : 'Workout Journal Plan : Edit (' . $workoutRecord['wkout_title'] . ')') : 'Workout Record')) . '</div>
											<div class="col-xs-3 save-icon-button activedatacol">
												<button data-role="none" type="submit" data-ajax="false" class="btn btn-default activedatacol" onclick="return confirmPopup(' . "'" . ($method == 'addworkoutLog' || $method == 'addworkoutLogwkout' ? 'logged' : ($method == 'addworkoutAssign' ? 'assigned' : '') ) . "'" . ');" name="f_method" style="background-color:#fff">more</button>
												<input type="hidden" name="save_edit" value="1" id="save_edit"/>
											</div>
										</div>
									</div>
								</div>
								<div class="modal-body">';
            $response .= '<div class="aligncenter">
												<div class="col-xs-12 errormsg hide" style="color:red;"></div>
											</div>';
            $response .= '<div id="expended" class="workout-titlerow" style="display: block;">';
            $wkoutTitle = '';
            $allowHide = true;
            if ($method == 'addworkoutAssign') {
                $response .= '<div class="row">
											<div class="mobpadding">
												<div class="col-xs-3 border-full labelcol removepading wrapword">
													<div>Assignment Date</div>
												</div>
												<div class="col-xs-9 border-full pointers" onclick="getTemplateOfNewAssignAction(' . "$('#selected_date_hidden').val()" . ');">
													<div>
														<div class="alignleft activedatacol">
															<b id="selected_date_hidden_text">' . date('d M Y', strtotime((!empty($date) ? $date . ' 00:00:00' : Helper_Common::get_default_date() . ' 00:00:00'))) . '</b>
															<input type="hidden" value="' . date('d M Y', strtotime((!empty($date) ? $date . ' 00:00:00' : Helper_Common::get_default_date() . ' 00:00:00'))) . '" id="selected_date_hidden" name="selected_date_hidden">
														</div>
													</div>
												</div>
											</div>
										</div>';
                if ($allowExistFlag)
                    $wkoutTitle = ucfirst($workoutRecord['wkout_title']);
                else
                    $wkoutTitle = 'Custom Workout ' . date('d-M-Y', strtotime((!empty($date) ? $date . ' 00:00:00' : Helper_Common::get_default_date('', 'd-M-Y') . ' 00:00:00')));
                $allowHide = false;
            }elseif ($method == 'addworkoutLog' || $method == 'addworkoutLogwkout') {
                $response .= '<div class="row">
											<div class="mobpadding">
												<div class="col-xs-3 border-full labelcol removepading wrapword">
													<div>Journal Date</div>
												</div>
												<div class="col-xs-9 border-full pointers" onclick="getTemplateOfNewLogAction(' . "$('#selected_date_hidden').val()" . ');">
													<div>
														<div class="alignleft activedatacol">
															<b id="selected_date_hidden_text">' . date('d M Y', strtotime((!empty($date) ? $date . ' 00:00:00' : Helper_Common::get_default_date() . ' 00:00:00'))) . '</b>
															<input type="hidden" value="' . date('d M Y', strtotime((!empty($date) ? $date . ' 00:00:00' : Helper_Common::get_default_date() . ' 00:00:00'))) . '" id="selected_date_hidden" name="selected_date_hidden">
															<input type="hidden" value="0" id="is_note_hidden" name="note_wkout_hidden">
															<input type="hidden" value="0" id="per_intent_hidden" name="note_wkout_intensity">
															<input type="hidden" value="" id="per_remarks_hidden" name="note_wkout_remarks">
														</div>
													</div>
												</div>
											</div>
										</div>';
                if ($allowExistFlag)
                    $wkoutTitle = ucfirst($workoutRecord['wkout_title']);
                else
                    $wkoutTitle = 'Custom Workout ' . date('d-M-Y', strtotime((!empty($date) ? $date . ' 00:00:00' : Helper_Common::get_default_date('', 'd-M-Y') . ' 00:00:00')));
                $allowHide = false;
            }
            $response .= '<div class="row">
											<div class="mobpadding" style="position:relative">
												<div class="col-xs-3 border-full labelcol removepading">
													<div>Title</div>
												</div>
												<div class="col-xs-9 border-full pointers">&nbsp;
													<div class="col-xs-10 alignleft colormodelpopup " style="padding:0px"><textarea id="wkout_title" class="form-control input-sm wkout_title" type="text" name="wkout_title" placeholder="click to Enter custom title">' . (!empty($wkoutTitle) ? $wkoutTitle : '') . '</textarea></div><div data-toggle="collapse" data-target=".navbar-collapse-colors"><i id="wrkoutcolortext" class="glyphicon wrkoutcolor ' . (isset($workoutRecord['color_title']) && !empty($workoutRecord['color_title']) ? $workoutRecord['color_title'] : '') . '" style="width:20px;height:20px;"></i><input id="wrkoutcolor" type="hidden" value="' . (isset($workoutRecord['wkout_color']) && !empty($workoutRecord['wkout_color']) ? $workoutRecord['wkout_color'] : '0') . '" name="wrkoutcolor"></div><div><span data-toggle="collapse" data-target=".navbar-collapse-colors" style="float:right;"><i class="fa iconsize fa-caret-down"></i></span></div>
												</div>
												<div class="collapse navbar-collapse-colors" style="position: absolute;right:15px;top:50px ; background-color:#fff;border:1px solid #ededed;z-index:999;"><div class="lt-left"><div class="row"><div class="col-xs-12 textcenter">Optional color marker</div></div><br>';
            if (isset($colorsRecord) && count($colorsRecord) > 0) {
                foreach ($colorsRecord as $keys => $values) {
                    if ($keys % 4 == 0)
                        $response .= '<div class="row"><div class="col-xs-12">';
                    $response .= '<div class="col-xs-3 colormodel"><a data-role="none" data-ajax="false" href="javascript:void(0)" ><i onclick="return selectcolor($(this));" class="colorcircle glyphicon ' . ( isset($workoutRecord['color_title']) && ($values['color_title'] == $workoutRecord['color_title']) ? 'activecircle' : '') . " " . $values['color_title'] . '"><span style="display:none" class="choosenclr">' . $values['color_id'] . '</span></i></a></div>';
                    if ($keys % 4 == 3)
                        $response .= '</div></div><br>';
                }
                $response .= '<div><button data-ajax="false" data-role="none" type="button" class="btn btn-default" style="float:right;margin:5px;" data-toggle="collapse" data-target=".navbar-collapse-colors">clear</button></div>';
            }
            $response .= '</div>
									  </div>';
            $response .= '<div class="row">
											<div class="mobpadding">
												<div class="col-xs-3 border-full labelcol removepading">
													<div>Focus</div>
												</div>
												<div class="col-xs-9 border-full">
														<div class="col-xs-12">
															<div class="row">
																<label id="dropdown">
																<select id="wkout_focus" class="blueicon" name="wkout_focus"><option value="">Select an overall focus</option>';
            if (isset($focusRecord) && count($focusRecord) > 0) {
                foreach ($focusRecord as $keys => $values) {
                    $response .= '<option ' . (isset($workoutRecord['wkout_focus']) && !empty($workoutRecord['wkout_focus']) && $workoutRecord['wkout_focus'] == $values['focus_id'] ? 'selected' : '') . ' value="' . $values['focus_id'] . '">' . ucfirst($values['focus_opt_title']) . '</option>';
                }
            }
            $response .= '</select></div>
															</div>
														</div>
												</div>
											</div>
										</div>
									</div>
									</div>
									<div class="row">
										<div class="mobpadding">
											<div onclick="toggleDivTitle();" class="border full aligncenter opendiv">
												<i class="fa iconsize fa-caret-up" id="expendeddiv"></i>
											</div>
										</div>
									</div>
								<input type="hidden" value="' . (isset($workoutRecord['wkout_color']) && !empty($workoutRecord['wkout_color']) ? $workoutRecord['wkout_color'] : '0') . '" name="wkout_color" id="wkout_color" /><input type="hidden" name="f_method" class="form-control input-sm" value="' . ($method == 'addworkoutAssign' ? 'add_new_assign' : ($method == 'addworkout' ? 'add_workout' : 'add_workoutlog')) . '" />';
            $response .= '<div class="row">
									<div class="mobpadding">
										<div class="popup-title mobpadding"><b>Exercise set</b></div>
									</div>
								</div>
								<div class="row">
									<div class="innerpage">
										<div class="mobpadding">
											<div class="border full optionmenu optionmenupopup">
											<div id="createwkoutpopup" class="menuactive">
												<button data-ajax="false" type="button" data-role="none" class="btn btn-default" id="createwkoutbtn" onclick="return createNewExerciseSet(' . "'','last'" . ');">
													<i class="fa fa-plus"></i>
												</button><br><span class="inactivedatacol">new set</span></div>
											<div class="hide allowhide">
												<button data-ajax="false" type="button" data-role="none" name="f_method" class="btn btn-default" onclick="return checkallItemspopup(this)">
													<i class="fa fa-check-circle-o"></i>
												</button><br><span class="inactivedatacol">all/none</span></div>
											<div class="hide allowhide">
												<button data-ajax="false" data-role="none" class="btn btn-default" type="button" onclick="return createCopyXrPopup();">
													<i class="fa fa-files-o datacol allowActive"></i>
												</button><br><span class="inactivedatacol">clone</span></div>
											<div class="hide allowhide">
												<button data-ajax="false" data-role="none" class="btn btn-default" type="button" onclick="return deleteExerciseSet();">
													<i class="fa fa-times datacol allowActive"></i>
												</button><br><span class="inactivedatacol">delete</span></div>
											<div class="borderright"></div>
											<div class="">
												<button data-ajax="false" type="button" data-role="none" name="f_method" id="editxrpopup" onclick="return editExercistSets(this);" class="btn btn-default">
													<i class="fa fa-list-ul"></i>
												</button><button data-ajax="false" type="button" data-role="none" name="f_method" id="refreshpopup" onclick="return editWorkoutrefresh(this);" class="btn btn-default hide">
													<i class="fa fa-refresh"></i>
												</button><br><span class="inactivedatacol">sets/list</span></div>
											</div>
										</div>
									</div>
									<div id="scrollablediv-len" class="scrollablepadd col-xs-12 ' . (isset($exerciseRecord) && count($exerciseRecord) > 4 ? 'scrollablediv' : '') . '">
											<input type="hidden" value="' . (!empty($exerciseRecord) && count($exerciseRecord) > 0 ? count($exerciseRecord) : '0') . '" name="s_row_count_xr" id="s_row_count_xr">
											<input type="hidden" value="' . (!empty($exerciseRecord) && count($exerciseRecord) > 0 ? count($exerciseRecord) : '0') . '" name="s_row_count_flag" id="s_row_count_flag">
											<ul class="sTreeBase bgC4 ' . (!empty($exerciseRecord) && count($exerciseRecord) > 0 ? '' : '') . '" id="sTree3">';
            if (!empty($exerciseRecord) && count($exerciseRecord) > 0) {
                $order = 1;
                foreach ($exerciseRecord as $keys => $val) {
                    $response .= '<li data-id="new_0_' . $order . '" data-module="item_set_new" class="bgC4 item_add_wkout_noclick" id="itemSetnew_0_0_' . $order . '"><div id="itemsetnew_0_' . $order . '" class="row createworkout"><input type="hidden" class="seq_order_up" value="' . $order . '" name="goal_order_new[]" id="goal_order_new_0_' . $order . '"><input type="hidden" value="0" name="goal_remove_new[]" id="goal_remove_new_0_' . $order . '"><div class="mobpadding"><div class="border full"><div class="checkboxchoosen popupchoosen col-xs-2" style="display:none"><div class="checkboxcolor" style="font-size:20px;"><label><input onclick="enablePopupButtons();" data-role="none" data-ajax="false" type="checkbox" class="checkhiddenpopup" name="exercisesets[]" value="new_0_' . $order . '"><span class="cr checkbox-circle"><i class="cr-icon fa fa-check"></i></span></label></div></div><div class="col-xs-8 navdescrip"><div class="col-xs-4 navimage activelinkpopup datacol" ' . (!empty($val['goal_unit_id']) ? 'onclick="getTemplateOfExerciseRecordAction(' . "'" . $val['goal_unit_id'] . "',this" . ');"' : '') . '>';
                    if (!empty($val['goal_unit_id'])) {
                        if (file_exists($val['img_url'])) {
                            $response .= '<img width="75px" class="activelinkpopup img-responsive pointers" src="' . URL::base() . $val['img_url'] . '" title="' . ucfirst($val['img_title']) . '"/>';
                        } else {
                            $response .= '<i class="fa fa-file-image-o pointers"></i>';
                        }
                    } else {
                        $response .= '<i class="fa fa-pencil-square" style="font-size:50px;"></i>';
                    }
                    $response .= '</div><div class="col-xs-8 pointers activelinkpopup datacol" onclick="editWorkoutRecord(' . "'0_" . $order . "','create'" . ');"><div class="activelinkpopup navimagedetails"><div class="navimgdet1"><b>' . ($val['goal_alt'] > 0 ? '<span>Alt</span> ' : '') . ucfirst($val['goal_title']) . '</b></div><div class="navimgdet2">';
                    $hidden_time = "00:00:00";
                    $hidden_rest_time = "00:00";
                    $flag = false;
                    $response .= '<a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail datacol exercise_time_div">';
                    if ($val['goal_time_hh'] > 0 || $val['goal_time_mm'] > 0 || $val['goal_time_ss'] > 0) {
                        $response .= (($val['primary_time']) ? '<span class="ashstrick">*</span> ' : '') . '<span>' . substr(sprintf("%02d", $val['goal_time_hh']), 0, 2) . ':' . substr(sprintf("%02d", $val['goal_time_mm']), 0, 2) . ':' . substr(sprintf("%02d", $val['goal_time_ss']), 0, 2) . '</span>';
                        $hidden_time = substr(sprintf("%02d", $val['goal_time_hh']), 0, 2) . ':' . substr(sprintf("%02d", $val['goal_time_mm']), 0, 2) . ':' . substr(sprintf("%02d", $val['goal_time_ss']), 0, 2);
                        $flag = true;
                    }
                    $response .= '</a><a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail datacol exercise_distance_div">';
                    if ($val['goal_dist'] > 0 && $val['goal_dist_id'] > 0) {
                        $response .= ($flag ? ' /// ' : '') . (($val['primary_dist']) ? '<span class="ashstrick">*</span> ' : '') . $val['goal_dist'] . ' <span>' . Model::instance('Model/workouts')->getGoalValues('dist', $val['goal_dist_id']) . '</span>';
                        $flag = true;
                    }
                    $response .= '</a><a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail datacol exercise_repetitions_div">';
                    if ($val['goal_reps'] > 0) {
                        $response .= ($flag ? ' /// ' : '') . (($val['primary_reps']) ? '<span class="ashstrick">*</span> ' : '') . $val['goal_reps'] . ' <span>reps</span>';
                        $flag = true;
                    }
                    $response .= '</a><a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail datacol exercise_resistance_div">';
                    if ($val['goal_resist'] > 0 && $val['goal_resist_id'] > 0) {
                        $response .= ($flag ? ' /// ' : '') . (($val['primary_resist']) ? '<span class="ashstrick">*</span> ' : '') . ' x ' . $val['goal_resist'] . ' <span>' . Model::instance('Model/workouts')->getGoalValues('resist', $val['goal_resist_id']) . '</span>';
                    }
                    $response .= '</a></div><div class="navimgdet3 datacol">';
                    $response .= '<a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail datacol exercise_rate_div">';
                    if ($val['goal_rate'] > 0 && $val['goal_rate_id'] > 0) {
                        $response .= (($val['primary_rate']) ? '<span class="ashstrick">*</span> ' : '') . '<span>@' . $val['goal_rate'] . ' ' . Model::instance('Model/workouts')->getGoalValues('rate', $val['goal_rate_id']) . '</span> ';
                    }
                    $response .= '</a>&nbsp;<a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail datacol exercise_angle_div">';
                    if ($val['goal_angle'] > 0 && $val['goal_angle_id'] > 0) {
                        $response .= (($val['primary_angle']) ? '<span class="ashstrick">*</span> ' : '') . '<span>' . $val['goal_angle'] . '%' . Model::instance('Model/workouts')->getGoalValues('angle', $val['goal_angle_id']) . '</span> ';
                    }
                    $response .= '</a>&nbsp;<a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail datacol exercise_innerdrive_div">';
                    $intvalue = Model::instance('Model/workouts')->getGoalValues('int', $val['goal_int_id']);
                    if ($intvalue > 0) {
                        $response .= (($val['primary_int']) ? '<span class="ashstrick">*</span> ' : '') . '<span>' . $intvalue . ' int</span>';
                    }
                    $response .= '</a>&nbsp;<a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail datacol exercise_rest_div">';
                    if ($val['goal_rest_mm'] + $val['goal_rest_ss'] > 0) {
                        if ($val['goal_rest_mm'] > 0 || $val['goal_rest_ss'] > 0) {
                            $response .= (($val['primary_rest']) ? '<span class="ashstrick">*</span> ' : '') . ' <span>' . $val['goal_rest_mm'];
                            if ($val['goal_rest_ss'] > 0 && $val['goal_rest_ss'] < 10)
                                $response .= ':0' . $val['goal_rest_ss'];
                            else
                                $response .= ':' . substr(sprintf("%02d", $val['goal_rest_ss']), 0, 2);
                            $hidden_rest_time = $val['goal_rest_mm'] . ':' . substr(sprintf("%02d", $val['goal_rest_ss']), 0, 2);
                            $response .= ' rest</span>';
                        }
                    }
                    $response .= '</a></div><div class="navimgdet4"></div></div></div>';
                    if ($method == 'addworkoutLog' || $method == 'addworkoutLogwkout') {
                        $response .= '<div class="navremarkdetails activelink hide" onclick="changeWkoutStatusExcise(' . "'0'" . ',this);" style="clear: both;">
												<div class="navimgdet4"><a data-ajax="false" data-role="none" href="javascript:void(0);" style="text-decoration:none" class="datacol exercise_intent_div"></a></div>
												<div class="navimgdet5"><a data-ajax="false" data-role="none" href="javascript:void(0);" style="text-decoration:none" class="datacol exercise_remarks_div"></a></div>
											</div>';
                    }
                    $response .= '</div><div class="col-xs-2 navbarmenu"><a data-ajax="false" class="pointers editchoosenIconTwo editchoosenIconTwoPopup hide" href="javascript:void(0);"><i class="fa fa-bars panel-draggable" style="font-size:25px;cursor: move;"></i></a><i class="fa fa-ellipsis-h iconsize listoptionpoppopup" ' . ($method == 'addworkoutLog' || $method == 'addworkoutLogwkout' ? 'id="markstatus_0_' . $order . '"' : '') . ' onclick="getTemplateOfExerciseSetAction(' . "'0_" . $order . "','link'" . ');"></i>' . ($method == 'addworkoutLog' || $method == 'addworkoutLogwkout' ? '<input type="hidden" value="0" name="markedstatus_new[]" id="markedstatus_0_' . $order . '"/><input type="hidden" value="0" name="edit_status_new[]" id="edit_status_0_' . $order . '"/><input type="hidden" value="0" name="per_intent_new[]" id="per_intent_0_' . $order . '"/><input type="hidden" value="0" name="per_remarks_new[]" id="per_remarks_0_' . $order . '"/><input type="hidden" value="0" name="hide_notes_set_new[]" id="hide_notes_set_0_' . $order . '"/>' : '') . '<input type="hidden" id="exercise_title_new_0_' . $order . '" name="exercise_title_new[]" value="' . $val['goal_title'] . '"/><input type="hidden" id="exercise_unit_new_0_' . $order . '" name="exercise_unit_new[]" value="' . $val['goal_unit_id'] . '"/><input type="hidden" id="exercise_resistance_new_0_' . $order . '" name="exercise_resistance_new[]" value="' . $val['goal_resist'] . '"/><input type="hidden" id="exercise_unit_resistance_new_0_' . $order . '" name="exercise_unit_resistance_new[]" value="' . $val['goal_resist_id'] . '"/><input type="hidden" id="exercise_repetitions_new_0_' . $order . '" name="exercise_repetitions_new[]" value="' . $val['goal_reps'] . '"/><input type="hidden" id="exercise_time_new_0_' . $order . '" name="exercise_time_new[]" value="' . $hidden_time . '"/><input type="hidden" id="exercise_distance_new_0_' . $order . '" name="exercise_distance_new[]" value="' . $val['goal_dist'] . '"/><input type="hidden" id="exercise_unit_distance_new_0_' . $order . '" name="exercise_unit_distance_new[]" value="' . $val['goal_dist_id'] . '"/><input type="hidden" id="exercise_rate_new_0_' . $order . '"/><input type="hidden" id="exercise_unit_rate_new_0_' . $order . '" name="exercise_unit_rate_new[]" value="' . $val['goal_rate_id'] . '"/><input type="hidden" id="exercise_innerdrive_new_0_' . $order . '" name="exercise_innerdrive_new[]" value="' . $val['goal_int_id'] . '"/><input type="hidden" id="exercise_angle_new_0_' . $order . '" name="exercise_angle_new[]" value="' . $val['goal_angle'] . '"/><input type="hidden" id="exercise_unit_angle_new_0_' . $order . '" name="exercise_unit_angle_new[]" value="' . $val['goal_angle_id'] . '"/><input type="hidden" id="exercise_rest_new_0_' . $order . '" name="exercise_rest_new[]" value="' . $hidden_rest_time . '"/><input type="hidden" id="exercise_remark_new_0_' . $order . '" name="exercise_remark_new[]" value="' . $val['goal_remarks'] . '"/><input type="hidden" id="primary_time_new_0_' . $order . '" name="primary_time_new[]" value="' . $val['primary_time'] . '"/><input type="hidden" id="primary_dist_new_0_' . $order . '" name="primary_dist_new[]" value="' . $val['primary_dist'] . '"/><input type="hidden" id="primary_reps_new_0_' . $order . '" name="primary_reps_new[]" value="' . $val['primary_reps'] . '"/><input type="hidden" id="primary_resist_new_0_' . $order . '" name="primary_resist_new[]" value="' . $val['primary_resist'] . '"/><input type="hidden" id="primary_rate_new_0_' . $order . '" name="primary_rate_new[]"  value="' . $val['primary_rate'] . '"/><input type="hidden" id="primary_angle_new_0_' . $order . '" name="primary_angle_new[]" value="' . $val['primary_angle'] . '"/><input type="hidden" id="primary_rest_new_0_' . $order . '" name="primary_rest_new[]" value="' . $val['primary_rest'] . '"/><input type="hidden" id="primary_int_new_0_' . $order . '" name="primary_int_new[]" value="' . $val['primary_int'] . '"/></div></div></div></div></li>';
                    $order = $order + 1;
                }
            }
            $response .= '</ul>
										</div>
									</div>
								</div>';
            $response .= '<div class="modal-footer"><div class="col-xs-12 activedatacol" style="float:right"><div class="col-xs-12 activedatacol"><button data-role="none" data-ajax="false" type="button" class="btn btn-default confirm pointers" data-notename="hide_confirm_wkout" data-allow="' . ($confirmwkAction ? 'false' : 'true') . '" data-text="Clicking BACK or CANCEL will discard any changes. Clicking MORE will display record options such as SAVE. Continue with exiting?">cancel</button>
					<button data-role="none" type="submit" data-ajax="false" class="btn btn-default activedatacol" onclick="return confirmPopup(' . "'" . ($method == 'addworkoutLog' || $method == 'addworkoutLogwkout' ? 'logged' : ($method == 'addworkoutAssign' ? 'assigned' : '') ) . "'" . ');" name="f_method" style="background-color:#fff">more</button></div></div></div></div></form></div></div></div>';
        } elseif (!empty($action) && trim($action) == 'exercisesetaction') {
            $relatedRecords = 0;
            if (!empty($xrId) && is_numeric($xrId)) {
                $exerciseRecord = $workoutModel->getExerciseById($xrId);
                $relatedRecords = $workoutModel->getRelatedExercise($xrId, $exerciseRecord['muscle_id'], $exerciseRecord['status_id'], $exerciseRecord['type_id']);
            }
            $modelType = ($modelType == 'link' ? 'myModal' : $modelType);
            $response = '<div id="exercisesetaction" class="exerciseAct vertical-alignment-helper">
							<div class="modal-dialog modal-md">
								<div class="modal-content">
									<div class="modal-header">';
            $response .= '<div class="row">
												<div class="mobpadding">
													<div class="border">
														<div class="col-xs-2">
															<a data-role="none" data-ajax="false" href="javascript:void(0)"';
            $response .= ' onclick="closeModelwindow(' . "'myModalDuplicate'" . ');closeModelwindow(' . "'" . $modelType . "'" . ');" ';

            $response .= 'class="triangle">
																<i class="fa fa-chevron-left iconsize"></i>
															</a>
														</div>
														<div class="col-xs-8 optionpoptitle">Options for this Excercise Set</div>
														<div class="col-xs-2"></div>
													</div>
												</div>
											</div>';
            $response .= '<hr><div class="row"><div class="popup-title"><div class="col-xs-12" style="font-size: .9em;"><div class="textcenter wkoutfocus"><b>' . ucfirst($title) . '</b></div></div></div></div>';
            $response .= '</div>
										<div class="modal-body opt-body">';
            if ($type == 'logged' && !empty($foldid)) {
                $response .= '<div class="opt-row-detail">
												<a data-role="none" data-ajax="false" href="javascript:void(0);" class="btn btn-default" style="width:100%">
													<div class="col-xs-12 pointer">
														<div class="col-xs-2" onclick="changeTosaveIcon();ChangeWkoutStatusWorkouts(1,' . "'" . $foldid . "','" . $xrsetid . "'" . ');">
															<i class="fa fa-check-square-o iconsize greenicon"></i>
														</div>
														<div class="col-xs-'.(!Helper_Common::getAllowAllAccessByUser($page_id, 'is_note_set_hidden') ? 8 : 10).'" onclick="changeTosaveIcon();ChangeWkoutStatusWorkouts(1,' . "'" . $foldid . "','" . $xrsetid . "'" . ');">Mark as Completed</div>' . (!Helper_Common::getAllowAllAccessByUser($page_id, 'is_note_set_hidden') ? '
														<div class="col-xs-2" onclick="changeTosaveIcon();addWorkoutLogNotes(1,' . "'" . $foldid . "','" . $xrsetid . "'" . ');"> 
															<i class="fa fa-pencil iconsize2" style="border:1px solid #eeeeee;border-radius:5px;"></i>
														</div> ' : '') . '
													</div>
												</a>
											</div>
											<div class="opt-row-detail">
												<a data-role="none" data-ajax="false" href="javascript:void(0);" class="btn btn-default" style="width:100%">
													<div class="col-xs-12 pointer">
														<div class="col-xs-2" onclick="changeTosaveIcon();ChangeWkoutStatusWorkouts(2,' . "'" . $foldid . "','" . $xrsetid . "'" . ');">
															<i class="fa fa-minus-square-o iconsize pinkicon"></i>
														</div>
														<div class="col-xs-'.(!Helper_Common::getAllowAllAccessByUser($page_id, 'is_note_set_hidden') ? 8 : 10).'" onclick="changeTosaveIcon();ChangeWkoutStatusWorkouts(2,' . "'" . $foldid . "','" . $xrsetid . "'" . ');">Mark as Skipped</div>' . (!Helper_Common::getAllowAllAccessByUser($page_id, 'is_note_set_hidden') ? '
														<div class="col-xs-2" onclick="changeTosaveIcon();addWorkoutLogNotes(2,' . "'" . $foldid . "','" . $xrsetid . "'" . ');">
															<i class="fa fa-pencil iconsize2" style="border:1px solid #eeeeee;border-radius:5px;"></i>
														</div>' : '') . '
													</div>
												</a>
											</div>';
            }
            $response .= '<div class="opt-row-detail">
												<a data-role="none" data-ajax="false" class="btn btn-default"';
            if (empty($modelType))
                $response .= 'href="javascript:void(0)" onclick="changeTosaveIcon();closeModelwindow();"';
            else {
                if ($method == 'action-edit')
                    $response .= 'href="javascript:void(0)" data-dismiss="modal" onclick="changeTosaveIcon();editWorkoutRecord(' . "'" . $foldid . "','" . $method . "','" . $goalOrder . '_' . $xrsetid . "'" . ');"';
                else if ($method == 'action-create')
                    $response .= ' href="javascript:void(0)" data-dismiss="modal" onclick="changeTosaveIcon();editWorkoutRecord(' . "'" . $fid . "','create','" . $goalOrder . '_' . $xrsetid . "'" . ');"';
                else
                    $response .= ' data-dismiss="modal" href="javascript:void(0)" onclick="' . ($type == 'logged' ? 'changeTosaveIcon();' : '') . 'editWorkoutRecord(' . "'" . $foldid . "','edit','" . $goalOrder . '_' . $xrsetid . "'" . ');"';
            }
            $response .= 'style="width:100%" >
													<div class="col-xs-12 pointer">
														<div class="col-xs-2"><i class="fa fa-pencil-square-o iconsize"></i></div>
														<div class="col-xs-10">Edit this Set</div>
													</div>
												</a>
											</div>';
            if (!empty($xrId) && $relatedRecords) {
                $response .= '<div class="opt-row-detail">
												<a data-role="none" data-ajax="false" href="javascript:void(0);" class="btn btn-default"';
                if (empty($modelType))
                    $response .= 'href="javascript:void(0)" onclick="changeTosaveIcon();closeModelwindow(' . "'" . $modelType . "'" . ');getRelatedRecords(' . "'" . $xrId . "','true','" . $foldid . "'," . $goalOrder . "'" . ');"';
                else {
                    if ($method == 'action-edit')
                        $response .= 'href="javascript:void(0)" data-dismiss="modal" onclick="closeModelwindow(' . "'" . $modelType . "'" . ');closeModelwindow(' . "'myOptionsModal'" . ');changeTosaveIcon();getRelatedRecords(' . "'" . $xrId . "','true','" . $foldid . "','" . $goalOrder . "'" . ');"';
                    else if ($method == 'action-create')
                        $response .= ' href="javascript:void(0)" data-dismiss="modal" onclick="changeTosaveIcon();closeModelwindow(' . "'" . $modelType . "'" . ');getRelatedRecords(' . "'" . $xrId . "','true','" . $foldid . "','" . $goalOrder . "'" . ');"';
                    else
                        $response .= ' data-dismiss="modal" href="javascript:void(0)" onclick="' . ($type == 'logged' ? 'changeTosaveIcon();' : '') . 'closeModelwindow(' . "'" . $modelType . "'" . ');getRelatedRecords(' . "'" . $xrId . "','true','" . $foldid . "','" . $goalOrder . "'" . ');"';
                }
                $response .= 'style="width:100%">
													<div class="col-xs-12 pointer">
														<div class="col-xs-2"><i class="fa fa-link iconsize"></i></div>
														<div class="col-xs-10">Replace With Related Exercise</div>
													</div>
												</a>
											</div>';
            }
            $response .= '<div class="opt-row-detail">
												<a data-role="none" data-ajax="false" href="javascript:void(0);" class="btn btn-default" onclick="$(' . "'#myModalDuplicate'" . ').modal().show();$(' . "'" . '#exercisesetaction' . "'" . ').hide();"  style="width:100%">
													<div class="col-xs-12 pointer">
														<div class="col-xs-2"><i class="fa fa-files-o iconsize"></i></div>
														<div class="col-xs-10">Duplicate this Set</div>
													</div>
												</a>
											</div>
											<div class="opt-row-detail">
												<button data-dismiss="modal" data-role="none" data-ajax="false" class="btn btn-default" type="button"';
            if ($method == 'action-edit')
                $response .= ' onclick="return doDeleteProcess(' . "'exerciseset','" . $foldid . "'" . ');" ';
            else if ($method == 'action-create')
                $response .= ' onclick="return doDeleteProcess(' . "'exerciseset','" . $fid . "'" . ');" href="javascript:void(0)" data-dismiss="modal" ';
            else
                $response .= ' onclick="return doDeleteProcess(' . "'exerciseset','" . $foldid . "','" . $goalOrder . '_' . $xrsetid . "'" . ');" ';
            $response .= ' style="width:100%">
													<div class="col-xs-12 pointer">
														<div class="col-xs-2"><i class="fa fa-times iconsize"></i></div>
														<div class="col-xs-10">Delete this Set</div>
													</div>
												</button>
											</div>
											<div class="opt-row-detail" style="';
            if (empty($modelType))
                $response .= 'display:none;';
            else
                $response .= '';
            $response .= '">
												<a data-role="none" data-ajax="false" href="javascript:void(0);" class="btn btn-default" style="width:100%" ';
            if ($method == 'createNewWrkout')
                $response .= 'onclick="changeTosaveIcon();$(' . "'#editxrpopup'" . ').click();"';
            else
                $response .= 'onclick="changeTosaveIcon();$(' . "'#editxr'" . ').click();"';
            $response .= ' >
													<div class="col-xs-12 pointer">
														<div class="col-xs-2"><i class="fa fa-arrows iconsize"></i></div>
														<div class="col-xs-10">Move this Set</div>
													</div>
												</a>
											</div>
											<div class="opt-row-detail">
												<a data-role="none" data-ajax="false" href="javascript:void(0);"  onclick="$(' . "'#myModalCreate'" . ').modal().show();$(' . "'" . '#exercisesetaction' . "'" . ').hide();" class="btn btn-default" style="width:100%">
													<div class="col-xs-12 pointer">
														<div class="col-xs-2"><i class="fa fa-plus-square-o iconsize"></i></div>
														<div class="col-xs-10">Insert a New Set</div>
													</div>
												</a>
											</div>
										</div>
										<div class="modal-footer"><button data-ajax="false" data-role="none" type="button" class="btn btn-default activedatacol" data-dismiss="modal">cancel</button></div>
									</div>
							</div>
						</div>
						<div id="myModalDuplicate" class="modal fade bs-example-modal-sm" role="dialog" tabindex="-1">
							<div class="vertical-alignment-helper">
							<div class="modal-dialog modal-md">
								<div class="modal-content">
									<div class="modal-header">';
            $response .= '<div class="row">
											<div class="mobpadding">
												<div class="border">
													<div class="col-xs-2">
														<a data-role="none" data-ajax="false" href="javascript:void(0);" onclick="$(' . "'" . '#exercisesetaction' . "'" . ').show();$(' . "'" . '#myModalDuplicate' . "'" . ').modal(' . "'hide'" . ');" class="triangle">
															<i class="fa fa-chevron-left iconsize"></i>
														</a>
													</div>
													<div class="col-xs-8 optionpoptitle">Duplicate this Set</div>
													<div class="col-xs-2"></div>
												</div>
											</div>
										</div>
									</div>
									<div class="modal-body opt-body">
										<div class="row opt-row-detail">
											<button data-role="none" data-ajax="false" data-dismiss="modal" class="btn btn-default" type="button"';
            if ($method == 'action-create')
                $response .= ' onclick="changeTosaveIcon(); createCopyExerciseCombineSet(' . "'" . $fid . "','up','" . $goalOrder . '_' . $xrsetid . "'" . ');"';
            else
                $response .= ' onclick="changeTosaveIcon(); createCopyExerciseCombineSet(' . "'" . $foldid . "','up','" . $goalOrder . '_' . $xrsetid . "'" . ');"';
            $response .= ' style="width:100%">
												<div class="col-xs-12 pointer">
													<div class="col-xs-2"><i class="fa fa-pencil-square-o iconsize"></i></div>
													<div class="col-xs-10">Insert before this set</div>
												</div>
											</button>
										</div>
										<div class="row opt-row-detail">
											<button data-role="none" data-ajax="false" data-dismiss="modal" ';
            if ($method == 'action-create')
                $response .= ' onclick="changeTosaveIcon(); createCopyExerciseCombineSet(' . "'" . $fid . "','down','" . $goalOrder . '_' . $xrsetid . "'" . ');"';
            else
                $response .= ' onclick="changeTosaveIcon(); createCopyExerciseCombineSet(' . "'" . $foldid . "','down','" . $goalOrder . '_' . $xrsetid . "'" . ');"';

            $response .= ' class="btn btn-default" type="button" style="width:100%">
												<div class="col-xs-12 pointer">
													<div class="col-xs-2"><i class="fa fa-files-o iconsize"></i></div>
													<div class="col-xs-10">Insert after this set</div>
												</div>
											</button>
										</div>
										<div class="row opt-row-detail">
											<button data-role="none" data-ajax="false" data-dismiss="modal" ';
            if ($method == 'action-create')
                $response .= ' onclick="changeTosaveIcon(); createCopyExerciseCombineSet(' . "'" . $fid . "','last','" . $goalOrder . '_' . $xrsetid . "'" . ');"';
            else
                $response .= ' onclick="changeTosaveIcon(); createCopyExerciseCombineSet(' . "'" . $foldid . "','last','" . $goalOrder . '_' . $xrsetid . "'" . ');"';

            $response .= ' class="btn btn-default" type="button" style="width:100%">
												<div class="col-xs-12 pointer">
													<div class="col-xs-2"><i class="fa fa-files-o iconsize"></i></div>
													<div class="col-xs-10">insert at end of list</div>
												</div>
											</button>
										</div>
									</div>
									<div class="modal-footer"><button data-ajax="false" data-role="none" type="button" class="btn btn-default activedatacol" onclick="$(' . "'" . '#exercisesetaction' . "'" . ').show();$(' . "'" . '#myModalDuplicate' . "'" . ').modal(' . "'hide'" . ');">cancel</button></div>
								</div>
							</div></div>
						</div>
						<div id="myModalCreate" class="modal fade bs-example-modal-sm" role="dialog" tabindex="-1">
							<div class="vertical-alignment-helper">
							<div class="modal-dialog modal-md">
								<div class="modal-content">
									<div class="modal-header">';
            $response .= '<div class="row">
											<div class="mobpadding">
												<div class="border">
													<div class="col-xs-2">
														<a data-role="none" data-ajax="false" href="javascript:void(0);" onclick="$(' . "'" . '#exercisesetaction' . "'" . ').show();$(' . "'" . '#myModalCreate' . "'" . ').modal(' . "'hide'" . ');" class="triangle">
															<i class="fa fa-chevron-left iconsize"></i>
														</a>
													</div>
													<div class="col-xs-8 optionpoptitle">Create a New Set</div>
													<div class="col-xs-2"></div>
												</div>
											</div>
										</div>
									</div>
									<div class="modal-body opt-body">
										<div class="row opt-row-detail">
											<button data-role="none" data-ajax="false" data-dismiss="modal" class="btn btn-default" type="button"';
            if ($method == 'action-create')
                $response .= ' onclick="changeTosaveIcon(); createNewExerciseSet(' . "'" . $fid . "','up','" . $goalOrder . '_' . $xrsetid . "'" . ');"';
            else
                $response .= ' onclick="changeTosaveIcon(); createNewExerciseSet(' . "'" . $foldid . "','up','" . $goalOrder . '_' . $xrsetid . "'" . ');"';
            $response .= ' style="width:100%">
												<div class="col-xs-12 pointer">
													<div class="col-xs-2"><i class="fa fa-pencil-square-o iconsize"></i></div>
													<div class="col-xs-10">Insert before this set</div>
												</div>
											</button>
										</div>
										<div class="row opt-row-detail">
											<button data-role="none" data-ajax="false" data-dismiss="modal" ';
            if ($method == 'action-create')
                $response .= ' onclick="changeTosaveIcon(); createNewExerciseSet(' . "'" . $fid . "','down','" . $goalOrder . '_' . $xrsetid . "'" . ');"';
            else
                $response .= ' onclick="changeTosaveIcon(); createNewExerciseSet(' . "'" . $foldid . "','down','" . $goalOrder . '_' . $xrsetid . "'" . ');"';

            $response .= ' class="btn btn-default" type="button" style="width:100%">
												<div class="col-xs-12 pointer">
													<div class="col-xs-2"><i class="fa fa-files-o iconsize"></i></div>
													<div class="col-xs-10">Insert after this set</div>
												</div>
											</button>
										</div>
										<div class="row opt-row-detail">
											<button data-role="none" data-ajax="false" data-dismiss="modal" ';
            $response .= ' onclick="changeTosaveIcon(); createNewExerciseSet(' . "'" . $foldid . "','last','" . $goalOrder . '_' . $xrsetid . "'" . ');"';
            $response .= ' class="btn btn-default" type="button" style="width:100%">
												<div class="col-xs-12 pointer">
													<div class="col-xs-2"><i class="fa fa-files-o iconsize"></i></div>
													<div class="col-xs-10">insert at end of list</div>
												</div>
											</button>
										</div>
									</div>
									<div class="modal-footer"><button data-ajax="false" data-role="none" type="button" class="btn btn-default activedatacol" onclick="$(' . "'" . '#exercisesetaction' . "'" . ').show();$(' . "'" . '#myModalCreate' . "'" . ').modal(' . "'hide'" . ');">cancel</button></div>
								</div>
							</div></div>
						</div><div id="myeditWorkoutModal" class="modal fade bs-example-modal-sm" role="dialog" tabindex="-1"></div>';
        }elseif (!empty($action) && trim($action) == 'xrsettoolbaraction') {
            $relatedRecords = 0;
            if (!empty($xrId)) {
                $exerciseRecord = $workoutModel->getExerciseById($xrId);
                $relatedRecords = $workoutModel->getRelatedExercise($xrId, $exerciseRecord['muscle_id'], $exerciseRecord['status_id'], $exerciseRecord['type_id']);
            }
            $modelType = ($modelType == 'link' ? 'myModal' : $modelType);
            $response = '<div class="vertical-alignment-helper">
							<div class="modal-dialog modal-md">
								<div class="modal-content">
									<div class="modal-header">';
            $response .= '<div class="row">
											<div class="mobpadding">
												<div class="border">
													<div class="col-xs-2">
														<a data-role="none" data-ajax="false" href="javascript:void(0);" onclick="$(' . "'" . $modelType . "'" . ').modal(' . "'hide'" . ');" class="triangle">
															<i class="fa fa-chevron-left iconsize"></i>
														</a>
													</div>
													<div class="col-xs-8 optionpoptitle">Duplicate this Set</div>
													<div class="col-xs-2"></div>
												</div>
											</div>
										</div>
									</div>
									<div class="modal-body opt-body">
										<div class="row opt-row-detail">
											<button data-role="none" data-ajax="false" data-dismiss="modal" class="btn btn-default" type="button"';
            $response .= ' onclick="createCopyExerciseSet(' . "'up'" . ');"';
            $response .= ' style="width:100%">
												<div class="col-xs-12 pointer">
													<div class="col-xs-2"><i class="fa fa-pencil-square-o iconsize"></i></div>
													<div class="col-xs-10">Insert before this set</div>
												</div>
											</button>
										</div>
										<div class="row opt-row-detail">
											<button data-role="none" data-ajax="false" data-dismiss="modal" ';
            $response .= ' onclick="createCopyExerciseSet(' . "'down'" . ');"';
            $response .= ' class="btn btn-default" type="button" style="width:100%">
												<div class="col-xs-12 pointer">
													<div class="col-xs-2"><i class="fa fa-files-o iconsize"></i></div>
													<div class="col-xs-10">Insert after this set</div>
												</div>
											</button>
										</div>
										<div class="row opt-row-detail">
											<button data-role="none" data-ajax="false" data-dismiss="modal" ';
            $response .= ' onclick="createCopyExerciseSet(' . "'last'" . ');"';
            $response .= ' class="btn btn-default" type="button" style="width:100%">
												<div class="col-xs-12 pointer">
													<div class="col-xs-2"><i class="fa fa-files-o iconsize"></i></div>
													<div class="col-xs-10">insert at end of list</div>
												</div>
											</button>
										</div>
									</div>
									<div class="modal-footer"><button data-ajax="false" data-role="none" type="button" class="btn btn-default activedatacol" data-dismiss="modal">cancel</button></div>
								</div>
							</div></div>';
        } elseif (!empty($action) && trim($action) == 'assignOptions') {

            $curdate = Helper_Common::get_default_date();
            $current = strtotime($curdate);
            $datediff = strtotime($date) - $current;
            $difference = floor($datediff / (60 * 60 * 24));
            $response = '<div id="assignOptionsaction" class="vertical-alignment-helper">
								<div id="changeassign" class="modal-dialog modal-md">
									<div id="assign-1" class="modal-content">
										<form action="" method="post"  data-ajax="false">
										<div class="modal-header">';
            $response .= '<input type="hidden" value="' . $fid . '" name="workout_id" />' . (!empty($logId) ? '<input type="hidden" value="' . $logId . '" name="wkout_log_id" />' : '<input type="hidden" value="' . $assignId . '" name="wkout_assign_id" />') . (!empty($date) ? '<input type="hidden" value="' . $date . '" name="selected_date" />' : '');
            $response .= '<div class="row">
												<div class="mobpadding">
													<div class="border">
														<div>
															<a data-ajax="false" data-role="none"  href="javascript:void(0)" onclick="closeModelwindow();" class="triangle">
																<i class="fa fa-chevron-left iconsize"></i>
															</a>
														</div>
														<div class="col-xs-8 optionpoptitle">' . (!empty($title) && !empty($assignId) ? 'Options for this Workout Assignment' : (!empty($title) && !empty($logId) ? 'Options for this Journal' : 'Options for this Workout Assignment')) . '</div>
														<div class="col-xs-2"></div>
													</div>
												</div>
											</div>';
            $response .= (!empty($title) ? '<hr><div class="row"><div class="popup-title"><div class="col-xs-12" style="font-size: .9em;"><div class="textcenter wkoutfocus"><b>' . ucfirst($title) . '</b>' . (!empty($goalOrder) && !empty($assignId) ? '<br><span style="font-weight:normal;"><i class="fa fa-exclamation-circle"></i> This assignment is incomplete due to results not marked as completed or skipped.</span>' : (!empty($goalOrder) && !empty($logId) ? (empty($title) ? '<hr>' : '<br>') . '<span style="font-weight:normal;"><i class="fa fa-exclamation-circle"></i> This journal is incomplete due to  ' . $xrId . ' unmarked exercise sets.</span>' : '')) . '</div></div></div></div>' : (!empty($goalOrder) && !empty($assignId) ? (empty($title) ? '<hr>' : '<br>') . '<span style="font-weight:normal;"><i class="fa fa-exclamation-circle"></i> This assignment is incomplete due to results not marked as completed or skipped.</span>' : (!empty($goalOrder) && !empty($logId) ? '<br><span style="font-weight:normal;"><i class="fa fa-exclamation-circle"></i> This journal is incomplete due to  ' . $xrId . ' unmarked exercise sets.</span>' : '')));
            $response .= '</div>
										<div class="modal-body opt-body">';
            if (!empty($assignId)) {
                if ($difference >= 0) {
                    $response .= '<div class="opt-row-detail">
													<a data-role="none" data-ajax="false" href="' . ((!empty($assignId) && $editFlag !== false && $difference <= 0) ? URL::base(TRUE) . 'exercise/workoutlog/startassign/' . $assignId . '?act=edit' : 'javascript:void(0);') . '" class="btn btn-default" style="width:100%" >
														<div class="col-xs-12 pointer">
															<div class="col-xs-2"><i class="fa fa-angle-double-right iconsize ' . ((!empty($assignId) && $editFlag !== false && $difference <= 0 ) ? 'orangeicon' : 'datacol' ) . '"></i></div>
															<div class="col-xs-10 ' . ((!empty($assignId) && $editFlag !== false && $difference <= 0 ) ? '' : 'datacol' ) . '">Start this Workout plan</div>
														</div>
													</a>
												</div>';
                } else {
                    $response .= '<div class="opt-row-detail">
													<button type="button" data-ajax="false" data-role="none" name="f_method" class="btn btn-default" onclick="return addLogWorkouts(' . "'" . $assignId . "','" . $fid . "','" . Helper_Common::get_default_date() . "','dulicateAssignWkoutLog'" . ');" value="copy" style="width:100%">
														<div class="col-xs-12 pointer">
															<div class="col-xs-2"><i class="fa fa-angle-double-right iconsize orangeicon"></i></div>
															<div class="col-xs-10">Log as New Workout Journal</div>
														</div>
													</button><input type="hidden" value="' . Helper_Common::get_default_date() . '" name="selected_date_hidden" id="selected_date"/>
												</div>';
                }
            }
            $response .= '<div class="opt-row-detail">
												<a data-ajax="false" data-role="none" href="javascript:void(0)" class="btn btn-default" ' . (($difference > 0 && empty($logId)) || ($difference > 0 && !empty($logId)) || ($editFlag !== true && empty($logId)) ? 'onclick="return false;"' : 'onclick="$(' . "'#assign-1'" . ').hide();$(' . "'#changeassign'" . ').removeClass(' . "'modal-md'" . ');$(' . "'#changeassign'" . ').addClass(' . "'bs-example-modal-sm'" . ');$(' . "'#assign-2'" . ').show();$(' . "'" . 'button.add_new_log_comp' . "'" . ').show();$(' . "'" . 'button.add_new_log_skip' . "'" . ').hide();"' ) . '  style="width:100%" >
													<div class="col-xs-12 pointer">
														<div class="col-xs-2"><i class="fa fa-check-square-o iconsize ' . (($difference > 0 && empty($logId)) || ($difference > 0 && !empty($logId)) || ($editFlag !== true && empty($logId)) ? 'datacol' : 'greenicon' ) . '"></i></div>
														<div class="col-xs-10 ' . (($difference > 0 && empty($logId)) || ($difference > 0 && !empty($logId)) || ($editFlag !== true && empty($logId)) ? 'datacol' : '' ) . '">Mark as Completed</div>
														<div class="col-xs-2 hide"><i class="fa fa-pencil iconsize2"></i></div>
													</div>
												</a>
											</div>
											<div class="opt-row-detail">
												<a data-ajax="false" data-role="none" href="javascript:void(0)" class="btn btn-default" ' . (($difference > 0 && empty($logId)) || ($difference > 0 && !empty($logId)) || ($editFlag !== true && empty($logId)) ? 'onclick="return false;"' : 'onclick="$(' . "'#assign-1'" . ').hide();$(' . "'#changeassign'" . ').removeClass(' . "'modal-md'" . ');$(' . "'#changeassign'" . ').addClass(' . "'bs-example-modal-sm'" . ');$(' . "'#assign-2'" . ').show();$(' . "'" . 'button.add_new_log_comp' . "'" . ').hide();$(' . "'" . 'button.add_new_log_skip' . "'" . ').show();"' ) . '  style="width:100%" >
													<div class="col-xs-12 pointer">
														<div class="col-xs-2"><i class="fa fa-minus-square-o iconsize ' . (($difference > 0 && empty($logId)) || ($difference > 0 && !empty($logId)) || ($editFlag !== true && empty($logId)) ? 'datacol' : 'pinkicon' ) . '"></i></div>
														<div class="col-xs-10 ' . (($difference > 0 && empty($logId)) || ($difference > 0 && !empty($logId)) || ($editFlag !== true && empty($logId)) ? 'datacol' : '' ) . '">Mark as Skipped</div>
														<div class="col-xs-2 hide"><i class="fa fa-pencil iconsize2"></i></div>
													</div>
												</a>
											</div>';
            if (!empty($assignId)) {
                if ($method != 'assignedPage') {
                    $response .= '<div class="opt-row-detail">
												<a href="javascript:void(0);" onclick="closeModelwindow();getAssignedwrkoutpreview(' . "'" . $fid . "','" . $assignId . "','" . $date . "','" . $ownWkFlag . "'" . ');" data-ajax="false" data-role="none" class="btn btn-default" style="width:100%">
													<div class="col-xs-12 pointer">
														<div class="col-xs-2"><i class="fa fa-eye iconsize"></i></div>
														<div class="col-xs-10">Preview this Assignment</div>
													</div>
												</a>
											</div>';
                }
                $response .= '<div class="opt-row-detail">
												<a href="' . ($difference >= 0 && $editFlag !== false && !empty($title) && $method != 'assignedPage' ? URL::base(TRUE) . 'exercise/assignedplan/' . $assignId . '/?act=edit&edit=0' : 'javascript:void(0);' ) . '" ' . (isset($ownWkFlag) && $editFlag !== false && !empty($ownWkFlag) && empty($title) && ($difference > 0 || $method == 'assignedPage') ? ' onclick="changeTosaveIcon();				toggleDivTitle();closeModelwindow();"' : '') . ' data-ajax="false" data-role="none" class="btn btn-default" style="width:100%">
													<div class="col-xs-12 pointer">
														<div class="col-xs-2"><i class="fa fa-pencil-square-o iconsize ' . ($difference >= 0 && $editFlag !== false && !empty($title) ? '' : (isset($ownWkFlag) && !empty($ownWkFlag) && empty($title) && $editFlag !== false && ($difference > 0 || $method == 'assignedPage') ? '' : 'datacol') ) . '"></i></div>
														<div class="col-xs-10 ' . ($difference >= 0 && $editFlag !== false && !empty($title) ? '' : (isset($ownWkFlag) && !empty($ownWkFlag) && empty($title) && $editFlag !== false && ($difference > 0 || $method == 'assignedPage') ? '' : 'datacol') ) . '">Edit the Assignment</div>
													</div>
												</a>
											</div>
											';
            } elseif (!empty($logId)) {
                $response .= (!empty($title) ? '<div class="opt-row-detail">
												<a href="javascript:void(0);" onclick="closeModelwindow();getLoggedwrkoutpreview(' . "'" . $fid . "','" . $logId . "','" . $date . "','" . $ownWkFlag . "'" . ');" data-ajax="false" data-role="none" class="btn btn-default" style="width:100%">
													<div class="col-xs-12 pointer">
														<div class="col-xs-2"><i class="fa fa-eye iconsize"></i></div>
														<div class="col-xs-10">Preview this Journal Entry</div>
													</div>
												</a>
											</div>' : '') . '
											<div class="opt-row-detail">
												<a href="' . ($type != 'assignedCalPage' || !empty($title) ? URL::base(TRUE) . 'exercise/workoutlog/' . $logId . '/?act=edit&edit=0' : 'javascript:void(0);' ) . '" ' . (isset($ownWkFlag) && !empty($ownWkFlag) && empty($title) && $type == 'assignedCalPage' ? ' onclick="changeTosaveIcon();				toggleDivTitle();closeModelwindow();"' : '') . '
												data-ajax="false" data-role="none" class="btn btn-default" style="width:100%">
													<div class="col-xs-12 pointer">
														<div class="col-xs-2"><i class="fa fa-pencil-square-o iconsize ' . ($type != 'assignedCalPage' || !empty($title) ? '' : (isset($ownWkFlag) && !empty($ownWkFlag) && empty($title) ? '' : 'datacol') ) . '"></i></div>
														<div class="col-xs-10 ' . ($type != 'assignedCalPage' || !empty($title) ? '' : (isset($ownWkFlag) && !empty($ownWkFlag) && empty($title) && $type == 'assignedCalPage' ? '' : 'datacol') ) . '">Edit this Journal Entry</div>
													</div>
												</a>
											</div>';
            }

            $response .= '<div class="opt-row-detail">
												<a data-ajax="false" data-role="none" href="javascript:void(0)" class="btn btn-default" onclick="$(' . "'#changeassign'" . ').removeClass(' . "'modal-md'" . ');$(' . "'#changeassign'" . ').addClass(' . "'bs-example-modal-sm'" . ');$(' . "'#assign-3'" . ').show();$(' . "'#assign-1'" . ').hide();" style="width:100%">
													<div class="col-xs-12 pointer">
														<div class="col-xs-2"><i class="fa fa-ellipsis-h iconsize"></i></div>
														<div class="col-xs-10">Further Options</div>
													</div>
												</a>
											</div>
										</div>
									</form>
								</div>
								<div id="assign-2" class="modal-content" style="display:none">';
            if (($difference > 0 && empty($logId)) || ($difference > 0 && !empty($logId)) || ($editFlag !== true && empty($logId))) {
                
            } else {
                $response .= '<form action="" method="post"  data-ajax="false">
									<div class="modal-header">';
                $response .= '<input type="hidden" value="' . $fid . '" name="workout_id" />' . (!empty($assignId) ? '<input type="hidden" value="' . $assignId . '" name="wkout_assign_id" />' : (!empty($logId) ? '<input type="hidden" value="' . $logId . '" name="wkout_log_id" />' : ''));
                $response .= '<div class="row">
											<div class="mobpadding">
												<div class="border">
													<div class="col-xs-2">
														<a data-ajax="false" data-text="Clicking BACK or CANCEL will discard any changes. Clicking Notes will mark this Journal is completed or skipped. Continue with exiting?" data-role="none" href="javascript:void(0);" data-onclick="$(' . "'#assign-1'" . ').show();$(' . "'#changeassign'" . ').removeClass(' . "'bs-example-modal-sm'" . ');$(' . "'#changeassign'" . ').addClass(' . "'modal-md'" . ');$(' . "'#assign-2'" . ').hide();" class="triangle confirm pointers" data-allow="' . ($confirmAction ? 'false' : 'true') . '">
															<i class="fa fa-chevron-left iconsize"></i>
														</a>
													</div>
													<div class="col-xs-8 optionpoptitle">Notes for this Workout (Overall)</div>
													<div class="col-xs-2"><button data-role="none" data-ajax="false" data-ajax="false" data-role="none" name="f_method" class="btn btn-default add_new_log_comp" value="add_new_log_comp" style="width:100%"><i class="fa fa-pencil-square-o iconsize"></i></button>
													<button data-role="none" data-ajax="false" data-ajax="false" data-role="none" name="f_method" class="btn btn-default add_new_log_skip" value="add_new_log_skip" style="width:100%"><i class="fa fa-pencil-square-o iconsize"></i></button></div>
												</div>
											</div>
										</div>
									</div>
									<div class="modal-body opt-body">';
                $response .= '<div class="row"><div class="mobpadding"><div class="border full">';
                $response .= '<div class="col-xs-3 borderright">Perceived Intensity</div>';
                $response .= '<div class="col-xs-8 drop down selectToUISlider"><div class="sliderview"><a href="javascript:void(0);" tabindex="0" id="handle_speed" class="ui-slider-handle ui-state-default ui-corner-all ui-state-hover" role="slider" aria-labelledby="label_handle_speed" aria-valuemin="0" style="left: 25%;"><span class="ui-slider-tooltip ui-widget-content ui-corner-all"><span class="ttContent"></span></span></a></div><input oninput="showval(this.value);" onchange="showval(this.value);" type="range" name="slider-1" id="slider-1" value="0" min="0" max="19" data-popup-enabled="true"><select name="note_wkout_intensity"  id="note_wkout_intensity" class="hide"><option value="0">Select</option>';
                $repetitions = $workoutModel->getInnerDrive();
                if (isset($repetitions) && count($repetitions) > 0) {
                    foreach ($repetitions as $keys => $values) {
                        $response .= '<option  value="' . $values['int_id'] . '">' . ucfirst($values['int_grp_title']) . '(' . ucfirst($values['int_opt_title']) . ')' . '</option>';
                    }
                }
                $response .= '</select></div></div></div><script>$(document).ready(function (){$(".ttContent").text(document.getElementById("note_wkout_intensity").options[$("#slider-1").val()].text).focus();});function showval(val){$(".ttContent").text(document.getElementById("note_wkout_intensity").options[val].text);}</script></div>';
                $response .= '<div class="row"><div class="mobpadding"><div class="border full">';
                $response .= '<div class="col-xs-3 borderright">Remarks / Notes</div>';
                $response .= '<div class="col-xs-8"><textarea id="note_wkout_remarks" name="note_wkout_remarks" class="form-control input-lg" style="width:100%"></textarea></div>';
                $response .= '</div></div></div>';
                $response .= '<div class="row"><div class="mobpadding"><div class="">';
                $response .= '<div class="col-xs-12"><input type="checkbox" name="is_hide_note" value="1" id="is_hide_note"/> <label for="is_hide_note">Don\'t show this dialog again</label></div>';
                $response .= '</div></div></div>';
                $response .= '</div>
								<div class="modal-footer"><button data-role="none" data-ajax="false" data-ajax="false" data-role="none" name="f_method" class="btn btn-default add_new_log_comp" value="add_new_log_comp" style="padding-left:10px;">Skip</button><button data-role="none" data-ajax="false" data-ajax="false" data-role="none" name="f_method" class="btn btn-default add_new_log_skip" value="add_new_log_skip" style="padding-left:10px;">Skip</button><button data-role="none" data-ajax="false" data-ajax="false" data-role="none" name="f_method" class="btn btn-default add_new_log_comp" value="add_new_log_comp" style="float:right;"><i class="fa fa-pencil-square-o iconsize"></i></button><button data-role="none" data-ajax="false" data-ajax="false" data-role="none" name="f_method" class="btn btn-default add_new_log_skip" value="add_new_log_skip" style="float:right;"><i class="fa fa-pencil-square-o iconsize"></i></button>
									</div>
									</form>';
            }
            $response .= '</div>
								<div id="assign-3" class="modal-content" style="display:none">
									<form action="" method="post"  data-ajax="false">
									<div class="modal-header">';
            $response .= '<input type="hidden" value="' . $fid . '" name="workout_id" />' . (!empty($assignId) ? '<input type="hidden" value="' . $assignId . '" name="wkout_assign_id" />' : (!empty($logId) ? '<input type="hidden" value="' . $logId . '" name="wkout_log_id" />' : ''));
            $response .= '<div class="row">
											<div class="mobpadding">
												<div class="border">
													<div class="col-xs-2">
														<a data-ajax="false" data-role="none" href="javascript:void(0);" onclick="$(' . "'#assign-1'" . ').show();$(' . "'#changeassign'" . ').removeClass(' . "'bs-example-modal-sm'" . ');$(' . "'#changeassign'" . ').addClass(' . "'modal-md'" . ');$(' . "'#assign-3'" . ').hide();" class="triangle">
															<i class="fa fa-chevron-left iconsize"></i>
														</a>
													</div>
													<div class="col-xs-8 optionpoptitle">' . (!empty($assignId) ? 'Options for this Workout Assignment' : 'Options for this Workout Journal' ) . '</div>
													<div class="col-xs-2"></div>
												</div>
											</div>
										</div>';
            $response .= (!empty($title) ? '<hr><div class="row"><div class="popup-title"><div class="col-xs-12" style="font-size: .9em;"><div class="textcenter wkoutfocus"><b>' . ucfirst($title) . '</b></div></div></div></div>' : '');
            $response .= '</div>
									<div class="modal-body opt-body">';
            if (!empty($assignId)) {
                $response .= '<div class="opt-row-detail hide ' . (isset($ownWkFlag) && !empty($ownWkFlag) && !empty($fid) ? '' : 'datacol') . '">
											<a href="' . (isset($ownWkFlag) && !empty($ownWkFlag) && !empty($fid) ? URL::base(TRUE) . 'exercise/workoutrecord/' . $fid . '/?act=edit' : 'javascript:void(0)') . '" data-ajax="false" data-role="none" class="btn btn-default" style="width:100%">
												<div class="col-xs-12 pointer">
													<div class="col-xs-2"><i class="fa fa-pencil-square-o iconsize ' . (isset($ownWkFlag) && !empty($ownWkFlag) && !empty($fid) ? '' : 'datacol') . '"></i></div>
													<div class="col-xs-10 ' . (isset($ownWkFlag) && !empty($ownWkFlag) && !empty($fid) ? '' : 'datacol') . '">Edit the Original Workout Plan</div>
												</div>
											</a>
										</div>
										<div class="opt-row-detail">
											<a href="javascript:void(0);" data-ajax="false" ' . ($difference > 0 ? ' onclick="return addAssignWorkouts(' . "'" . $assignId . "','" . $fid . "','" . $date . "'" . ');"' : 'onclick="return false;"' ) . ' data-role="none"  class="btn btn-default" style="width:100%">
												<div class="col-xs-12 pointer">
													<div class="col-xs-2"><i class="fa fa-calendar iconsize ' . ($difference > 0 ? '' : 'datacol' ) . '"></i></div>
													<div class="col-xs-10 ' . ($difference > 0 ? '' : 'datacol' ) . '">Reschedule this Assignment</div>
												</div>
											</a>
										</div>
										<div class="opt-row-detail">
											<button data-ajax="false" data-role="none" name="f_method" class="btn btn-default" type="button" onclick="return addAssignWorkoutsByDate(' . "'" . Helper_Common::get_default_date() . "','" . $fid . "','" . $assignId . "','assigned-duplicate'" . ');" value="copy" style="width:100%">
												<div class="col-xs-12 pointer">
													<div class="col-xs-2"><i class="fa fa-files-o iconsize"></i></div>
													<div class="col-xs-10">Duplicate this Assignment</div>
												</div>
											</button>
										</div>';
                $response .= '<div class="opt-row-detail">
											<button data-ajax="false" data-role="none" name="f_method" class="btn btn-default" onclick="$(' . "'#form-workoutrec'" . ').submit();" value="cancel" style="width:100%">
												<div class="col-xs-12 pointer">
													<div class="col-xs-2"><i class="fa fa-times iconsize"></i></div>
													<div class="col-xs-10">Remove this Assignment</div>
												</div>
											</button>
										</div>
										<div class="opt-row-detail">
											<button data-ajax="false" data-role="none" name="f_method" class="btn btn-default" onclick="$(' . "'#form-workoutrec'" . ').submit();" value="add_new_wkout" style="width:100%">
												<div class="col-xs-12 pointer">
													<div class="col-xs-2"><i class="fa fa-files-o iconsize"></i></div>
													<div class="col-xs-10">Save as New Workout</div>
												</div>
											</button>
										</div>
										<div class="opt-row-detail">
											<button data-ajax="false" data-role="none" name="f_method" class="btn btn-default" value="export" type="button" style="width:100%" onclick="shareThisWorkout(' . "'" . $assignId . "','assigned','" . addSlashes($title) . "'" . ');">
												<div class="col-xs-12 pointer">
													<div class="col-xs-2"><i class="fa fa-share-alt iconsize"></i></div>
													<div class="col-xs-10">' . ((Helper_Common::is_admin() || Helper_Common::is_manager() || Helper_Common::is_trainer()) && Helper_Common::hasAccess('Share & Assign') ? 'Share/Assign' : 'Share') . ' this Workout Assignment</div>
												</div>
											</button>
										</div>
										<div class="opt-row-detail">
											<button data-ajax="false" data-role="none" name="f_method" class="btn btn-default" value="export" type="button" style="width:100%" onclick="enableExport(' . "'" . $assignId . "','assigned'" . ');">
												<div class="col-xs-12 pointer">
													<div class="col-xs-2"><i class="fa fa-sign-out iconsize"></i></div>
													<div class="col-xs-10">Export this Workout Assignment</div>
												</div>
											</button>
										</div>';
            } elseif (!empty($logId)) {
                $response .= '<div class="opt-row-detail">
											<button type="button" data-ajax="false" data-role="none" name="f_method" class="btn btn-default" onclick="return addLogWorkouts(' . "'" . $logId . "','" . $fid . "','" . Helper_Common::get_default_date() . "','dulicateWkoutLog'" . ');" value="copy" style="width:100%">
												<div class="col-xs-12 pointer">
													<div class="col-xs-2"><i class="fa fa-files-o iconsize"></i></div>
													<div class="col-xs-10">Duplicate this as a new Journal entry</div>
												</div>
											</button><input type="hidden" value="' . Helper_Common::get_default_date() . '" name="selected_date_hidden" id="selected_date"/>
										</div>
										<div class="opt-row-detail">
											<a href="javascript:void(0);" data-ajax="false" onclick="return addLogWorkouts(' . "'" . $logId . "','" . $fid . "','" . Helper_Common::get_default_date() . "','wkoutassign'" . ');" data-role="none"  class="btn btn-default" style="width:100%">
												<div class="col-xs-12 pointer">
													<div class="col-xs-2"><i class="fa fa-calendar iconsize"></i></div>
													<div class="col-xs-10">Duplicate this as a new Assignment</div>
												</div>
											</a>
										</div>
										<div class="opt-row-detail">
											<button data-ajax="false" data-role="none" name="f_method" class="btn btn-default" type="submit"  value="add_new_wkout_from_log" style="width:100%">
												<div class="col-xs-12 pointer">
													<div class="col-xs-2"><i class="fa fa-files-o iconsize"></i></div>
													<div class="col-xs-10">Duplicate this as a new Workout Plan</div>
												</div>
											</button>
										</div>
										<div class="opt-row-detail">
											<button data-ajax="false" data-role="none" name="f_method" class="btn btn-default" value="delete" type="submit" onclick="return doDeleteLogProcess();"  style="width:100%">
												<div class="col-xs-12 pointer">
													<div class="col-xs-2"><i class="fa fa-times iconsize"></i></div>
													<div class="col-xs-10">Delete this Journal entry</div>
												</div>
											</button>
										</div>
										<div class="opt-row-detail">
											<button data-ajax="false" data-role="none" name="f_method" class="btn btn-default" value="export" type="button" style="width:100%" onclick="shareThisWorkout(' . "'" . $logId . "','logged','" . addSlashes($title) . "'" . ');">
												<div class="col-xs-12 pointer">
													<div class="col-xs-2"><i class="fa fa-share-alt iconsize"></i></div>
													<div class="col-xs-10">' . ((Helper_Common::is_admin() || Helper_Common::is_manager() || Helper_Common::is_trainer()) && Helper_Common::hasAccess('Share & Assign') ? 'Share/Assign' : 'Share') . ' this Journal entry</div>
												</div>
											</button>
										</div>
										<div class="opt-row-detail">
											<button data-ajax="false" data-role="none" name="f_method" class="btn btn-default" value="export" type="button" style="width:100%" onclick="enableExport(' . "'" . $logId . "','logged'" . ');">
												<div class="col-xs-12 pointer">
													<div class="col-xs-2"><i class="fa fa-sign-out iconsize"></i></div>
													<div class="col-xs-10">Export this Journal entry</div>
												</div>
											</button>
										</div>';
            }
            $response .= '</div>
								</form>
								</div>
							</div>
						</div>';
        } elseif (!empty($action) && trim($action) == 'reassignOptions') {
            $response = '<div class="assignOptions vertical-alignment-helper">
								<div class="modal-dialog">
									<div class="modal-content">
									<form data-ajax="false" action="" method="post" id="reassignOptions">
										<div class="modal-header">';
            $response .= '<input type="hidden" value="' . $fid . '" name="workout_id" />
											  <input type="hidden" value="' . $assignId . '" name="wkout_assign_id" />';
            $response .= '<div class="row">
													<div class="mobpadding">
														<div class="border">
															<div class="col-xs-2">
																<a data-ajax="false" data-role="none" href="javascript:void(0);" onclick="closeModelwindow();" class="triangle">
																	<i class="fa fa-chevron-left iconsize"></i>
																</a>
															</div>
															<div class="col-xs-8 optionpoptitle">Options for this Workout Assignment</div>
															<div class="col-xs-2"></div>
														</div>
													</div>
												</div>
											</div>
											<div class="modal-body opt-body">
												<div class="opt-row-detail">
													<a href="javascript:void(0);" data-ajax="false" onclick="return addReAssignWorkouts(' . "'" . $assignId . "','" . $fid . "','" . $date . "'" . ');" data-role="none"  class="btn btn-default" style="width:100%">
														<div class="col-xs-12 pointer">
															<div class="col-xs-2"><i class="fa fa-calendar iconsize"></i></div>
															<div class="col-xs-10">Reschedule this Workout Assignment</div>
														</div>
													</a>
												</div>
												<div class="opt-row-detail">
													<button data-ajax="false" data-role="none" name="f_method" class="btn btn-default" onclick="$(' . "'#form-workoutrec'" . ').submit();" value="copy" style="width:100%">
														<div class="col-xs-12 pointer">
															<div class="col-xs-2"><i class="fa fa-files-o iconsize"></i></div>
															<div class="col-xs-10">Duplicate this Workout Assignment</div>
														</div>
													</button>
												</div>';
            $current = strtotime(Helper_Common::get_default_date());
            $datediff = strtotime($date) - $current;
            $difference = floor($datediff / (60 * 60 * 24));
            $response .= '<div class="opt-row-detail">
													<button data-ajax="false" data-role="none" name="f_method" class="btn btn-default" ' . ($difference >= 0 ? 'onclick="$(' . "'#form-workoutrec'" . ').submit();"' : 'onclick="return false;"' ) . ' value="cancel" style="width:100%">
														<div class="col-xs-12 pointer">
															<div class="col-xs-2"><i class="fa fa-pencil-square-o iconsize ' . ($difference >= 0 ? '' : 'datacol' ) . '"></i></div>
															<div class="col-xs-10 ' . ($difference >= 0 ? '' : 'datacol' ) . '">Cancel this Workout Assignment</div>
														</div>
													</button>
												</div>
											</div>
										</form>
									</div>
								</div>
						</div>';
        } elseif (!empty($action) && trim($action) == 'confirmWorkoutPopup') {
            $confirmrevertAction = Helper_Common::getAllowAllAccessByUser($page_id, 'is_confirm_revert_hidden');
            $response = '<div class="confirmWorkoutAct vertical-alignment-helper">
							<div class="modal-dialog modal-md">
								<div class="modal-content">
									<form data-ajax="false" action="" method="post" id="confirmWorkoutPopup">
									<div class="modal-header">';
            $response .= '<input type="hidden" value="' . $fid . '" name="workout_id" />
										  <input type="hidden" value="' . $foldid . '" name="exerciseset_id" />
										  <input type="hidden" value="' . $foldid . '" name="exercisesets[]" />';
            $response .= '<div class="row">
												<div class="mobpadding">
													<div class="border">
														<div class="col-xs-2">
															<a data-role="none" data-dismiss="modal" data-ajax="false" href="javascript:void(0)" onclick="closeModelwindow(' . "'myModalDuplicate'" . ');closeModelwindow(' . "'" . $modelType . "'" . ');" class="triangle">
																<i class="fa fa-chevron-left iconsize"></i>
															</a>
														</div>
														<div class="col-xs-8 optionpoptitle">';
            if (!empty($type) && $type == 'assigned')
                $response .= 'Options for this Assigned Plan Entry';
            elseif (!empty($type) && $type == 'logged')
                $response .= 'Options for this Journal Entry';
            else
                $response .= 'Options for this Workout Build/Edit';
            $response .= '</div>
														<div class="col-xs-2"></div>
													</div>
												</div>
											</div>
										</div>
										<div class="modal-body opt-body">';
            if ($type == 'logged') {
                $response .= '<div class="opt-row-detail">
													<a data-role="none" data-ajax="false" href="javascript:void(0);" class="btn btn-default" type="button" onclick="saveLogWorkouts(' . "'3'" . ');"  style="width:100%">
														<div class="col-xs-12 pointer">
															<div class="col-xs-2"><i class="fa greenicon fa-check-square-o iconsize"></i></div>
															<div class="col-xs-10">Mark all Completed & Save</div>
														</div>
													</a>
												</div>
												<div class="opt-row-detail">
													<a data-role="none" data-ajax="false" href="javascript:void(0);" class="btn btn-default" type="button" onclick="saveLogWorkouts(' . "'4'" . ');" style="width:100%">
														<div class="col-xs-12 pointer">
															<div class="col-xs-2"><i class="fa fa-minus-square-o pinkicon iconsize"></i></div>
															<div class="col-xs-10">Mark all Skipped & Save</div>
														</div>
													</a>
												</div>';
            }
            $response .= '<div class="opt-row-detail">
												<button data-role="none" data-ajax="false" class="btn btn-default" type="button" ' . (!empty($fid) ? (!empty($type) && $type == 'logged' ? 'onclick="saveLogWorkouts(' . "'0'" . ');"' : 'onclick="$(' . "'" . '#save_edit' . "'" . ').val(0);$(' . "'" . ($method == 'confirmpopup' ? '#createNewworkout' : '#form-workoutrec') . "'" . ').submit();"') : (!empty($type) && $type == 'logged' ? 'onclick="saveLogWorkouts(' . "'0'" . ');"' : (!empty($type) && $type == 'assigned' ? 'onclick="$(' . "'" . '#save_edit' . "'" . ').val(0);$(' . "'" . ($method == 'confirmpopup' ? '#createNewworkout' : '#form-workoutrec') . "'" . ').submit();"' : 'onclick="confirmwkout(' . "'" . $modelType . "','0'" . ');"'))) . ' style="width:100%">
													<div class="col-xs-12 pointer">
														<div class="col-xs-2"><i class="fa fa-pencil-square-o iconsize"></i></div>
														<div class="col-xs-10">Save & Close</div>
													</div>
												</button>
											</div>
											<div class="opt-row-detail">
												<a data-role="none" data-ajax="false" href="javascript:void(0);" class="btn btn-default" type="button" ' . (!empty($fid) ? (!empty($type) && $type == 'logged' ? 'onclick="saveLogWorkouts(' . "'1'" . ');"' : 'onclick="$(' . "'" . '#save_edit' . "'" . ').val(1);$(' . "'" . ($method == 'confirmpopup' ? '#createNewworkout' : '#form-workoutrec') . "'" . ').submit();"') : (!empty($type) && $type == 'logged' ? 'onclick="saveLogWorkouts(' . "'1'" . ');"' : (!empty($type) && $type == 'assigned' ? 'onclick="$(' . "'" . '#save_edit' . "'" . ').val(1);$(' . "'" . ($method == 'confirmpopup' ? '#createNewworkout' : '#form-workoutrec') . "'" . ').submit();"' : 'onclick="confirmwkout(' . "'" . $modelType . "','1'" . ');"'))) . '   style="width:100%">
													<div class="col-xs-12 pointer">
														<div class="col-xs-2"><i class="fa fa-files-o iconsize"></i></div>
														<div class="col-xs-10">Save & Continue Editing</div>
													</div>
												</a>
											</div>
											<div class="opt-row-detail">
												<a data-role="none" data-ajax="false" href="javascript:void(0);" data-text="Clicking BACK or CANCEL will discard any changes. Clicking INSERT will apply any content changes. Continue with exiting?" class="btn btn-default confirm pointers" data-notename="hide_confirm_revert" data-allow="' . ($confirmrevertAction ? 'false' : 'true') . '" type="button" ' . (!empty($fid) ? 'data-onclick="$(' . "'" . '#save_edit' . "'" . ').val(2);$(' . "'" . ($method == 'confirmpopup' ? '#createNewworkout' : '#form-workoutrec') . "'" . ').submit();"' : (!empty($type) && $type == 'logged' ? 'data-onclick="$(' . "'" . '#save_edit' . "'" . ').val(2);$(' . "'" . ($method == 'confirmpopup' ? '#createNewworkout' : '#form-workoutrec') . "'" . ').submit();"' : (!empty($type) && $type == 'assigned' ? 'data-onclick="$(' . "'" . '#save_edit' . "'" . ').val(2);$(' . "'" . ($method == 'confirmpopup' ? '#createNewworkout' : '#form-workoutrec') . "'" . ').submit();"' : 'data-onclick="confirmwkout(' . "'" . $modelType . "','2'" . ');"'))) . ' style="width:100%">
													<div class="col-xs-12 pointer">
														<div class="col-xs-2"><i class="fa fa-times iconsize"></i></div>
														<div class="col-xs-10">Revert to saved</div>
													</div>
												</a>
											</div>
											<div class="opt-row-detail">
												<a data-role="none" data-ajax="false" href="javascript:void(0);" ' . (!empty($fid) ? 'onclick="closeModelwindow(' . "'" . $modelType . "'" . ');createNewExerciseSet();"' : (!empty($type) && $type == 'logged' ? 'onclick="closeModelwindow(' . "'" . $modelType . "'" . ');createNewExerciseSet();"' : (!empty($type) && $type == 'assigned' ? 'onclick="closeModelwindow(' . "'" . $modelType . "'" . ');createNewExerciseSet();"' : 'onclick="closeModelwindow(' . "'" . $modelType . "'" . ');return createNewExerciseSet();"'))) . ' class="btn btn-default" style="width:100%">
													<div class="col-xs-12 pointer">
														<div class="col-xs-2"><i class="fa fa-plus-square-o iconsize"></i></div>
														<div class="col-xs-10">Insert a New Set</div>
													</div>
												</a>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>';
        } elseif (!empty($action) && trim($action) == 'confirmWorkoutLogPopup') {
            $response = '<div class="confirmWorkoutLogAct vertical-alignment-helper">
								<div class="modal-dialog modal-md">
									<div class="modal-content">
										<form data-ajax="false" action="" method="post" id="confirmWorkoutLogPopup">
										<div class="modal-header">';
            $response .= '<input type="hidden" value="' . $fid . '" name="workout_id" />
											  <input type="hidden" value="' . $logId . '" name="workout_log_id" />';
            $response .= '<div class="row">
												<div class="mobpadding">
													<div class="border">
														<div class="col-xs-2">
															<a data-role="none" data-ajax="false" href="javascript:void(0)" onclick="closeModelwindow();" class="triangle">
																<i class="fa fa-chevron-left iconsize"></i>
															</a>
														</div>
														<div class="col-xs-8 optionpoptitle">Options for this Journal Entry</div>
														<div class="col-xs-2"></div>
													</div>
												</div>
											</div>
										</div>
										<div class="modal-body opt-body">
											<div class="opt-row-detail">
												<button data-role="none" data-ajax="false" class="btn btn-default" type="button" onclick="$(' . "'" . '#form-workoutrec' . "'" . ').submit();" style="width:100%">
													<div class="col-xs-12 pointer">
														<div class="col-xs-3"><i class="fa fa-check-square-o iconsize"></i></div>
														<div class="col-xs-9">Save & Close</div>
													</div>
												</button>
											</div>
											<div class="opt-row-detail">
												<a data-role="none" data-ajax="false" href="javascript:void(0);" class="btn btn-default" type="button" onclick="$(' . "'" . '#save_edit' . "'" . ').val(1);$(' . "'" . '#form-workoutrec' . "'" . ').submit();"  style="width:100%">
													<div class="col-xs-12 pointer">
														<div class="col-xs-3"><i class="fa fa-files-o iconsize"></i></div>
														<div class="col-xs-9">Save & Continue</div>
													</div>
												</a>
											</div>
											<div class="opt-row-detail">
												<a data-role="none" data-ajax="false" href="javascript:void(0);" class="btn btn-default" type="button" onclick="$(' . "'" . '#save_edit' . "'" . ').val(2);$(' . "'" . '#form-workoutrec' . "'" . ').submit();"  style="width:100%">
													<div class="col-xs-12 pointer">
														<div class="col-xs-3"><i class="fa fa-times iconsize"></i></div>
														<div class="col-xs-9">Revert to saved</div>
													</div>
												</a>
											</div>
											<div class="opt-row-detail">
												<a data-role="none" data-ajax="false" href="' . URL::base(TRUE) . 'exercise/workoutrecord/' . $fid . '/?act=add" class="btn btn-default" style="width:100%">
													<div class="col-xs-12 pointer">
														<div class="col-xs-3"><i class="fa fa-plus-square-o iconsize"></i></div>
														<div class="col-xs-9">Insert a New Set</div>
													</div>
												</a>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>';
        } elseif (!empty($action) && trim($action) == 'exercisesetviewaction') {
            $response = '<div class="exerciseRecordAct vertical-alignment-helpers">
								<div class="modal-dialog modal-md">
									<div class="modal-content">
										<form data-ajax="false" action="" method="post" id="exercisesetviewaction">
										<div class="modal-header">';
            $response .= '<input type="hidden" value="' . $fid . '" name="workout_id" />
											  <input type="hidden" value="' . $foldid . '" name="exerciseset_id" />
											  <input type="hidden" value="' . $foldid . '" name="exercisesets[]" />';
            $response .= '<div class="row">
												<div class="mobpadding">
													<div class="border">
														<div class="col-xs-2">
															<a data-role="none" data-ajax="false" href="javascript:void(0)" onclick="closeModelwindow(' . "'myModalDuplicate'" . ');closeModelwindow();" class="triangle">
																<i class="fa fa-chevron-left iconsize"></i>
															</a>
														</div>
														<div class="col-xs-8 optionpoptitle">Options for this Excercise Set</div>
														<div class="col-xs-2"></div>
													</div>
												</div>
											</div>
										</div>
										<div class="modal-body opt-body">
											<div class="opt-row-detail">
												<a data-role="none" data-ajax="false" class="btn btn-default"';
            $response .= 'href="javascript:void(0)" onclick="getExerciseSetpreview(' . "'" . $foldid . "'" . ",'" . $fid . "'" . ');"';
            $response .= 'style="width:100%" >
													<div class="col-xs-12 pointer">
														<div class="col-xs-2"><i class="fa fa-eye iconsize"></i></div>
														<div class="col-xs-10">View this Exercise Set</div>
													</div>
												</a>
											</div>
											<div class="opt-row-detail">
												<a data-role="none" data-ajax="false" href="javascript:void(0);" class="btn btn-default" onclick="getRelatedRecords(' . "'" . $foldid . "','',''" . ');"  style="width:100%">
													<div class="col-xs-12 pointer">
														<div class="col-xs-2"><i class="fa fa-link iconsize"></i></div>
														<div class="col-xs-10">View Related Exercise</div>
													</div>
												</a>
											</div>
											<div class="opt-row-detail">
												<button data-role="none" data-ajax="false" class="btn btn-default" type="submit" name="f_method" value="delete" onclick="return false" style="width:100%">
													<div class="col-xs-12 pointer">
														<div class="col-xs-2"><i class="fa fa-tag iconsize"></i></div>
														<div class="col-xs-10">Tag this Exercise</div>
													</div>
												</button>
											</div>
											<div class="opt-row-detail">
												<button data-role="none" data-ajax="false" class="btn btn-default" type="button" name="f_method" value="delete" onclick="return getRateFromUser(' . "'" . $foldid . "'" . ');" style="width:100%">
													<div class="col-xs-12 pointer">
														<div class="col-xs-2"><i class="fa fa-star-o iconsize"></i></div>
														<div class="col-xs-10">Rate this Exercise</div>
													</div>
												</button>
											</div>
											<div class="opt-row-detail">
												<button data-role="none" data-ajax="false" class="btn btn-default" type="button" name="f_method" value="share" onclick="return false" style="width:100%">
													<div class="col-xs-12 pointer">
														<div class="col-xs-2"><i class="fa fa-share-alt iconsize"></i></div>
														<div class="col-xs-10">Share this Exercise</div>
													</div>
												</button>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>';
        } elseif (!empty($action) && trim($action) == 'exerciserecordaction') {
            $exerciseRecord = $workoutModel->relatedExcersise($foldid);
            $seqRecord = $workoutModel->getSequenceImages($foldid);
            $getRelatedImages = array();
            foreach ($seqRecord as $keys => $values) {
                if (!empty($values['img_url']) && file_exists($values['img_url'])) {
                    $getRelatedImages[$values['seq_order']]['img_url'] = URL::base() . $values['img_url'];
                    $getRelatedImages[$values['seq_order']]['seq_desc'] = ucfirst($values['seq_desc']);
                }
            }
            if (empty($getRelatedImages)) {
                $exerciseRecord = $workoutModel->relatedExcersise($foldid);
                if (!empty($foldid) && isset($exerciseRecord['img_url']) && !empty($exerciseRecord['img_url']) && file_exists($exerciseRecord['img_url']))
                    $getRelatedImages[]['img_url'] = URL::base() . $exerciseRecord['img_url'];
            }
            $relatedRecords = $workoutModel->getRelatedExercise($foldid, $exerciseRecord['muscle_id'], $exerciseRecord['status_id'], $exerciseRecord['type_id']);
            if (empty($title) && isset($exerciseRecord['title']))
                $title = $exerciseRecord['title'];
            if ($allowTag !== true)
                $tagCount = count($workoutModel->getUnitTagsById($foldid));
            $response = '<div class="exerciseRecordAct vertical-alignment-helper">
								<div class="modal-dialog bs-example-modal-sm">
									<div class="modal-content">
										<form data-ajax="false" action="" method="post" id="exerciserecordaction">
										<div class="modal-header">';
            $response .= '<input type="hidden" value="' . $fid . '" name="workout_id" />
											  <input type="hidden" value="' . $foldid . '" name="exerciseset_id" />
											  <input type="hidden" value="' . $foldid . '" name="exercisesets[]" />';
            $response .= '<div class="row">
												<div class="mobpadding">
													<div class="border">
														<div class="col-xs-2">
															<a data-role="none" data-ajax="false" href="javascript:void(0)" onclick="closeModelwindow(' . "'myModalDuplicate'" . ');closeModelwindow(' . "'" . $modelType . "'" . ');" class="triangle">
																<i class="fa fa-chevron-left iconsize"></i>
															</a>
														</div>
														<div class="col-xs-8 optionpoptitle">Options for this Excercise Record</div>
														<div class="col-xs-2"></div>
													</div>
												</div>
											</div>
											<hr>
											<div class="row"><div class="popup-title"><div class="col-xs-12">' . (!empty($title) ? '<span class="inactivedatacol break-xr-name" style="font-size: .9em;">' . $title . '</span>' : '') . '</div></div></div>
										</div>
										<div class="modal-body opt-body">';
            if (isset($getRelatedImages) && count($getRelatedImages) > 0) {
                $response .= '<div id="xrimgCarousel" class="opt-row-detail carousel slide carousel-fade aligncenter"><div class="carousel-inner" id="carouselbody">';
                $flag = true;
                foreach ($getRelatedImages as $keys => $values) {
                    $response .= '<div class="item ' . ($flag ? 'active' : '') . '">';
                    $response .= '<img onload="loadimage(this);" src="' . $values['img_url'] . '" alt="' . (!empty($keys) ? 'Sequence ' . $keys : 'Feature Image') . '" class="img-responsive Preview_image slide-img"/><div class="slide-title">' . (isset($values['seq_desc']) ? 'Sequence ' . $keys . (!empty($values['seq_desc']) ? '</div><div>' . $values['seq_desc'] . '</div>' : '</div>') : 'Feature Image</div>');
                    $response .= '</div>';
                    $flag = false;
                }
                if (count($getRelatedImages) > 1)
                    $response .= '</div><a class="left carousel-control" href="#xrimgCarousel" data-slide="prev"><i class="fa fa-chevron-left fa-4"></i></a><a class="right carousel-control" href="#xrimgCarousel" data-slide="next"><i class="fa fa-chevron-right fa-4"></i></a></div>';
                else
                    $response .= '</div></div>';
            }else {
                $response .= '<div class="opt-row-detail">';
                $response .= '<div class="aligncenter"><i id="preview-featimg" class="fa fa-file-image-o datacol" style="font-size:150px;"></i></div>';
                $response .= '</div>';
            }

            $response .= '<div class="opt-row-detail">
												<a data-role="none" data-ajax="false" class="btn btn-default"';
            $response .= 'href="javascript:void(0)" onclick="closeModelwindow(' . "'" . $modelType . "'" . ');getExercisepreviewOfDay(' . "'" . $foldid . "'" . ",'" . $fid . "'" . ');"';
            $response .= 'style="width:100%" >
													<div class="col-xs-12 pointer">
														<div class="col-xs-2"><i class="fa fa-eye iconsize"></i></div>
														<div class="col-xs-10">View this Exercise Record</div>
													</div>
												</a>
											</div>';
            if ($editFlag) {
                $response .= '<div class="opt-row-detail">
													<a href="javascript:void(0)" onclick="' . ($modelType != 'myOptionsModalExerciseRecord' ? 'closeModelwindow(' . "'" . $modelType . "'" . ');' : '' ) . ' getRelatedRecords(' . "'" . $foldid . "','true','" . $goalOrder . '_' . $xrId . "','" . $goalOrder . "'" . ');" style="width:100%"  class="btn btn-default">
														<div class="col-xs-12 pointer">
															<div class="col-xs-2"><i class="fa fa-link iconsize"></i></div>
															<div class="col-xs-10">Replace with Related Exercise</div>
														</div>
													</a>
												</div>';
            } else {
                $response .= '<div class="opt-row-detail hide">
													<a data-role="none" data-ajax="false" href="javascript:void(0);" class="btn btn-default" ' . ($relatedRecords > 0 ? 'onclick="closeModelwindow(' . "'" . $modelType . "'" . ');getRelatedRecords(' . "'" . $foldid . "','true','" . $goalOrder . '_' . $xrId . "','" . $goalOrder . "'" . ');"' : 'onclick="return false;"') . '  style="width:100%">
														<div class="col-xs-12 pointer">
															<div class="col-xs-2"><i class="fa fa-link iconsize"></i></div>
															<div class="col-xs-10 ' . ($relatedRecords > 0 ? '' : 'datacol') . '">View Related Exercise</div>
														</div>
													</a>
												</div>';
            }
            $response .= '<div class="opt-row-detail">
												<a data-role="none" data-ajax="false" href="javascript:void(0);" class="btn btn-default" ' . ($allowTag ? 'onclick="closeModelwindow(' . "'" . $modelType . "'" . ');insertTagOfRecord(' . "'" . $foldid . "'" . ');"' : ($tagCount > 0 ? 'onclick="closeModelwindow(' . "'" . $modelType . "'" . ');getTagOfRecord(' . "'" . $foldid . "'" . ');"' : 'onclick="return false;"')) . '  style="width:100%">
													<div class="col-xs-12 pointer">
														<div class="col-xs-2"><i class="fa fa-tag iconsize"></i></div>
														<div class="col-xs-10 ' . ($allowTag ? '' : ($tagCount > 0 ? '' : 'datacol')) . '">Tag this Exercise</div>
													</div>
												</a>
											</div>
											<div class="opt-row-detail">
												<a data-role="none" data-ajax="false" href="javascript:void(0);" class="btn btn-default" onclick="return getRateFromUser(' . "'" . $foldid . "'" . ');"   style="width:100%">
													<div class="col-xs-12 pointer">
														<div class="col-xs-2"><i class="fa fa-star-o iconsize"></i></div>
														<div class="col-xs-10">Rate this Exercise</div>
													</div>
												</a>
											</div>
											<div class="opt-row-detail hide">
												<a data-role="none" data-ajax="false" href="javascript:void(0);" class="btn btn-default" onclick="return false;"  style="width:100%">
													<div class="col-xs-12 pointer datacol">
														<div class="col-xs-2"><i class="fa fa-share-alt iconsize datacol"></i></div>
														<div class="col-xs-10 datacol">Share this Exercise</div>
													</div>
												</a>
											</div>
										</div>
										<div class="modal-footer"><button data-ajax="false" data-role="none" type="button" class="btn btn-default" data-dismiss="modal">close</button></div>
									</form>
								</div>
							</div>						
						</div>';
        } elseif (!empty($action) && trim($action) == 'actionplanOptions') {
            if ($type == 'myexercise' || $type == "sampleexercise" || $type == "sharedexercise") {
                $href = URL::base(TRUE) . 'exercise/exerciserecord/' . ($type == 'myexercise' ? 'startmyxr' : ($type == 'sampleexercise' ? 'startsamplexr' : ($type == 'sharedexercise' ? 'startsharedxr' : ''))) . '/' . $fid . '?act=indx';
            } else {
                $href = 'javascript:void(0);';
                $typeXr = 'assignedplan/';
                if (!empty($date))
                    $date = date('d-m-Y', strtotime($date . ' 00:00:00'));
                if (!empty($type))
                    $href = URL::base(TRUE) . 'exercise/';
                if (!empty($method) && ($method == 'addworkoutLog' || $method == 'addworkoutLogwkout'))
                    $typeXr = 'workoutlog/';
                if (!empty($method) && $method == 'addworkout')
                    $typeXr = 'workoutrecord/';

                if (strtolower($type) == 'wrkout')
                    $href .= $typeXr . 'startwkout/' . $fid . '?act=edit' . ($method != 'addworkout' ? '&date=' . $date : '');
                else if (strtolower($type) == 'sample')
                    $href .= $typeXr . 'startsample/' . $fid . '?act=edit' . ($method != 'addworkout' ? '&date=' . $date : '');
                else if (strtolower($type) == 'shared')
                    $href .= $typeXr . 'startshare/' . $fid . '?act=edit' . ($method != 'addworkout' ? '&date=' . $date : '');
                else if (strtolower($type) == 'assigned')
                    $href .= $typeXr . 'startassign/' . $fid . '?act=edit' . ($method != 'addworkout' ? '&date=' . $date : '');
                else if (strtolower($type) == 'wkoutlog')
                    $href .= $typeXr . 'startwklog/' . $fid . '?act=edit' . ($method != 'addworkout' ? '&date=' . $date : '');
            }
            $response = '<div class="vertical-alignment-helper">
							<div class="modal-dialog modal-md">
								<div class="modal-content aligncenter">
									<form data-ajax="false" action="" method="post" id="actionplanOptions">
									<div class="modal-header">
										<div class="row">
											<div class="mobpadding">
												<div class="border">
													<div class="col-xs-2">
														<a data-role="none" data-ajax="false" href="javascript:void(0);" onclick="closeModelwindow(' . "'" . $modelType . "'" . ');" class="triangle"><i class="fa fa-chevron-left"></i>
														</a>
													</div>
													<div class="col-xs-8 optionpoptitle">Options for this ' . ($type != 'myexercise' && $type != 'sampleexercise' && $type != 'sharedexercise' ? ($type == 'wrkout' ? '' : ($type == 'sample' ? 'Sample' : ($type == 'shared' ? 'Shared' : ($type == 'assigned' ? 'Assigned' : 'Logged')))) : '') . ($type != 'myexercise' && $type != 'sampleexercise' && $type != 'sharedexercise' ? ' Workout Plan' : ' Exercise Record') . '</div>
													<div class="col-xs-2"></div>
												</div>
											</div>
										</div>';
            $response .= '<hr><div class="row"><div class="popup-title"><div class="col-xs-12" style="font-size: .9em;"><div class="textcenter wkoutfocus"><b>' . ucfirst($title) . '</b></div></div></div></div>';
            $response .= '</div>
									<div class="modal-body opt-body">';
            if (!empty($method) && $method == 'addworkout') {
                $response .= '<div class="row opt-row-detail">
											<div class="col-xs-12">
												<a data-role="none" data-ajax="false" href="' . $href . '" style="width:100%"  class="btn btn-default">
													<div class="col-xs-12">Insert into this ' . ($method == 'addworkout' ? ' Workout Plan' : ($method != "addworkoutLog" && $method != 'addworkoutLogwkout' ? 'Assign Plan' : 'Journal')) . '</div>
												</a>
											</div>
										</div>';
            } else if ($type == 'myexercise' || $type == 'sampleexercise' || $type == 'sharedexercise') {
                $response .= '<div class="row opt-row-detail">
										<div class="col-xs-12">
												<a data-role="none" data-ajax="false" href="' . ($requestFrom == 'dashboard' ? $href . '"' : 'javascript:void(0);" onclick="' . ($actionFrom == 'exercise' ? 'createNewExercise(true);' : 'xrLibcreateNewExercise(true);') . '"') . ' style="width:100%"  class="btn btn-default" data-request="' . $requestFrom . '" data-actionreq="' . $actionFrom . '">
													<div>Edit & Insert this New Exercise Record</div>
												</a>
											</div>
										</div>
										<div class="row opt-row-detail"><input type="hidden" id="addtype" name="addtype" value="' . $type . '"/><input type="hidden" id="addid" name="addid" value="' . $fid . '"/>';
                $response .= '<div class="col-xs-12"><button class="btn btn-default" ' . ($requestFrom == 'dashboard' ? 'type="submit" name="f_method" value="addExercise" ' : 'type="button" id="addExercise" onclick="createExerciseFromCopy();"') . ' data-request="' . $requestFrom . '" style="width:100%" data-role="none" data-ajax="false">Insert into this Exercise Record</button></div></div>';
            } else {
                $response .= '<div class="row opt-row-detail">
											<div class="col-xs-12">
												<a data-role="none" data-ajax="false" href="' . $href . '" style="width:100%"  class="btn btn-default">
													<div class="col-xs-12">Insert into & Edit this ' . ($method != "addworkoutLog" && $method != 'addworkoutLogwkout' ? 'Assign Plan' : 'Journal') . '</div>
												</a>
											</div>
										</div>
										<div class="row opt-row-detail"><input type="hidden" name="addmethod" value="' . $method . '"/><input type="hidden" name="addtype" value="' . $type . '"/><input type="hidden" name="adddate" value="' . $date . '"/><input type="hidden" name="addid" value="' . $fid . '"/>';
                $response .= '<div class="col-xs-12"><button style="width:100%" value="' . ($method != "addworkoutLog" && $method != 'addworkoutLogwkout' ? 'addAssign' : 'addLog') . '" type="submit" class="btn btn-default" name="f_method" data-role="none" data-ajax="false">' . ($method != "addworkoutLog" && $method != 'addworkoutLogwkout' ? 'Insert into this Assign Plan' : 'Insert & Mark as completed') . '</button></div></div>';
            }
            $response .= '</div>
									<div class="modal-footer"><button data-ajax="false" data-role="none" type="button" onclick="closeModelwindow(' . "'" . $modelType . "'" . ');" class="btn btn-default">close</button></div>
									</form>
								</div>
							</div></div>';
        } elseif (!empty($action) && trim($action) == 'workoutaction') {
            $response = '<div id="workoutaction" class="vertical-alignment-helper">
								<div class="modal-dialog modal-md">
									<div class="modal-content">
									<form data-ajax="false" action="" method="post" >
										<div class="modal-header">';
            $response .= '<input type="hidden" value="' . $fid . '" name="workout_id" />';
            $response .= '<div class="row">
												<div class="mobpadding">
													<div class="border">
														<div class="col-xs-2">
															<a data-role="none" data-ajax="false" href="javascript:void(0)" onclick="closeModelwindow();" class="triangle">
																<i class="fa fa-chevron-left iconsize"></i>
															</a>
														</div>
														<div class="col-xs-8 optionpoptitle">Options for this Workout</div>
														<div class="col-xs-2"></div>
													</div>
												</div>
											</div>';
            $response .= '<hr><div class="row"><div class="popup-title"><div class="col-xs-12" style="font-size: .9em;"><div class="textcenter wkoutfocus"><b>' . ucfirst($title) . '</b></div></div></div></div>';
            $response .= '</div>
										<div class="modal-body opt-body">
											<div class="opt-row-detail">
												<a data-role="none" data-ajax="false" href="' . (!empty($fid) ? URL::base(TRUE) . 'exercise/workoutlog/startwkout/' . $fid . '?act=edit"' : 'javascript:void(0);') . '" class="btn btn-default" style="width:100%" >
													<div class="col-xs-12 pointer">
														<div class="col-xs-2"><i class="fa fa-angle-double-right iconsize orangeicon"></i></div>
														<div class="col-xs-10">Start this Workout plan</div>
													</div>
												</a>
											</div>
											<div class="opt-row-detail">
												<a data-role="none" data-ajax="false" href="javascript:void(0)" class="btn btn-default" onclick="addAssignWorkouts(' . "'" . $fid . "'" . ');" style="width:100%" >
													<div class="col-xs-12 pointer">
														<div class="col-xs-2"><i class="fa fa-calendar iconsize"></i></div>
														<div class="col-xs-10">Assign this Workout plan</div>
													</div>
												</a>
											</div>';
            //if(Helper_Common::hasAccess('Manage Workouts')){
            $response .= '<div class="opt-row-detail">
												<a data-role="none" data-ajax="false" class="btn btn-default"';
            if ($type == 'workoutfolder')
                $response .= ' href="' . URL::base(TRUE) . 'exercise/workoutrecord/' . $fid . '?act=edit" ';
            else
                $response .= ' href="javascript:void(0)" onclick="changeTosaveIcon();closeModelwindow();" href="javascript:void(0)" data-toggle="collapse" data-target=".navbar-collapse-actions" ';
            $response .= ' style="width:100%" >
													<div class="col-xs-12 pointer">
														<div class="col-xs-2"><i class="fa fa-pencil-square-o iconsize"></i></div>
														<div class="col-xs-10">Edit this Workout plan</div>
													</div>
												</a>
											</div>';
            $response .= '<div class="opt-row-detail">
												<a data-role="none" data-ajax="false" href="javascript:void(0);" class="btn btn-default" onclick="$(' . "'" . '#workoutaction' . "'" . ').hide();$(' . "'#myModalDuplicate'" . ').modal();$(' . "'" . '#myModalDuplicate' . "'" . ').show();"  style="width:100%">
													<div class="col-xs-12 pointer">
														<div class="col-xs-2"><i class="fa fa-files-o iconsize"></i></div>
														<div class="col-xs-10">Duplicate this Workout plan</div>
													</div>
												</a>
											</div>
											<div class="opt-row-detail">
												<button data-role="none" data-ajax="false" class="btn btn-default" ';
            if ($type == 'workoutfolder')
                $response .= ' type="submit" onclick="return doDeleteProcess();" value="delete_single" name="f_method" ';
            else
                $response .= ' value="delete" type="submit" name="f_method" onclick="return doDeleteProcess(' . "'workoutplan',''" . ');" ';
            $response .= 'style="width:100%">
													<div class="col-xs-12 pointer">
														<div class="col-xs-2"><i class="fa fa-times iconsize"></i></div>
														<div class="col-xs-10">Delete this Workout plan</div>
													</div>
												</button>
											</div>';
            //}
            if (Helper_Common::is_admin() || Helper_Common::is_manager() || Helper_Common::is_trainer()) {
                $response .= '<div class="opt-row-detail">
												<a data-role="none" data-ajax="false" href="javascript:void(0)"  onclick="shareThisWorkout(' . "'" . $fid . "','" . $foldid . "','" . addSlashes($title) . "'" . ')" class="btn btn-default" style="width:100%">
													<div class="col-xs-12 pointer">
														<div class="col-xs-2"><i class="fa fa-share-alt iconsize"></i></div>
														<div class="col-xs-10">' . ((Helper_Common::is_admin() || Helper_Common::is_manager() || Helper_Common::is_trainer()) && Helper_Common::hasAccess('Share & Assign') ? 'Share/Assign' : 'Share') . ' this Workout plan</div>
													</div>
												</a>
											</div>';
            }
            $response .= '<div class="opt-row-detail">
												<button data-ajax="false" data-role="none" name="f_method" class="btn btn-default" value="export" type="button" style="width:100%" onclick="enableExport(' . "'" . $fid . "',''" . ');">
													<div class="col-xs-12 pointer">
														<div class="col-xs-2"><i class="fa fa-sign-out iconsize"></i></div>
														<div class="col-xs-10">Export this Workout plan</div>
													</div>
												</button>
											</div>
										</div>
									</form>
									</div>
								</div>
							</div>
						<div id="myModalDuplicate" class="modal fade bs-example-modal-sm" role="dialog" tabindex="-1">
								<div class="vertical-alignment-helper">
								<div class="modal-dialog modal-md">
									<div class="modal-content">
										<form  data-ajax="false" action="" method="post" id="workoutaction-duplicate">
										<div class="modal-header">';
            $response .= '<input type="hidden" value="' . $fid . '" name="workout_id" />';
            $response .= '<div class="row">
													<div class="mobpadding">
														<div class="border">
															<div class="col-xs-2">
																<a data-role="none" data-ajax="false" href="javascript:void(0);" onclick="$(' . "'" . '#workoutaction' . "'" . ').show();$(' . "'" . '#myModalDuplicate' . "'" . ').modal(' . "'hide'" . ');" class="triangle">
																	<i class="fa fa-chevron-left iconsize"></i>
																</a>
															</div>
															<div class="col-xs-8 optionpoptitle">Duplicate this Workout plan</div>
															<div class="col-xs-2"></div>
														</div>
													</div>
												</div>
											</div>
											<div class="modal-body opt-body">
												<div class="row opt-row-detail">
													<button data-role="none" data-ajax="false" class="btn btn-default" ';
            $response .= ' name="f_method" type="submit" value="copy_up" ';
            $response .= ' style="width:100%">
														<div class="col-xs-12 pointer">
															<div class="col-xs-2"><i class="fa fa-pencil-square-o iconsize"></i></div>
															<div class="col-xs-10">Insert before this Workout plan</div>
														</div>
													</button>
												</div>
												<div class="row opt-row-detail">
													<button data-role="none" data-ajax="false" name="f_method" class="btn btn-default" type="submit" value="copy_down" style="width:100%">
														<div class="col-xs-12 pointer">
															<div class="col-xs-2"><i class="fa fa-files-o iconsize"></i></div>
															<div class="col-xs-10">Insert after this Workout plan</div>
														</div>
													</button>
												</div>
											</div>
										</form>
									</div>
								</div>
						</div>';
        } elseif (!empty($action) && trim($action) == 'otherWorkoutAction') {
            $response = '<div class="otherWorkoutAction vertical-alignment-helper">
							<div class="modal-dialog modal-md">
								<div class="modal-content">
									<form data-ajax="false" action="" method="post" id="otherWorkoutAction">
									<div class="modal-header">';
            $response .= '<input type="hidden" value="' . $fid . '" name="workout_id" />';
            $response .= '<input type="hidden" value="' . $type . '" name="method" />';
            $response .= '<div class="row">
											<div class="mobpadding">
												<div class="border">
													<div class="col-xs-2">
														<a data-role="none" data-ajax="false" href="javascript:void(0)" onclick="closeModelwindow();" class="triangle">
															<i class="fa fa-chevron-left iconsize"></i>
														</a>
													</div>
													<div class="col-xs-8 optionpoptitle">Options for this Workout</div>
													<div class="col-xs-2"></div>
												</div>
											</div>
										</div>';
            $response .= '<hr><div class="row"><div class="popup-title"><div class="col-xs-12" style="font-size: .9em;"><div class="textcenter wkoutfocus"><b>' . ucfirst($title) . '</b></div></div></div></div>';
            $response .= '</div>
									<div class="modal-body opt-body">
										<div class="opt-row-detail">
											<a data-role="none" data-ajax="false" href="javascript:void(0);" class="btn btn-default" style="width:100%" onclick="confirmOtherLogDate(' . "'" . Helper_Common::get_default_date() . "','" . $fid . "'" . ');">
												<div class="col-xs-12 pointer removepading">
													<div class="col-xs-2"><i class="fa fa-angle-double-right iconsize orangeicon"></i></div>
													<div class="col-xs-10">Start this Workout plan</div>
												</div>
											</a>
										</div>
										<div class="opt-row-detail">
											<a data-role="none" data-ajax="false" href="javascript:void(0);" class="btn btn-default" style="width:100%" onclick="getworkoutpreview(' . "'" . $fid . "'" . ');">
												<div class="col-xs-12 pointer removepading">
													<div class="col-xs-2"><i class="fa fa-eye iconsize"></i></div>
													<div class="col-xs-10">Preview this Workout plan</div>
												</div>
											</a>
										</div>
										<div class="opt-row-detail">
											<a data-role="none" data-ajax="false" href="javascript:void(0)" class="btn btn-default" onclick="addAssignWorkouts(' . "'" . $fid . "'" . ');" style="width:100%" >
												<div class="col-xs-12 pointer removepading">
													<div class="col-xs-2"><i class="fa fa-calendar iconsize"></i></div>
													<div class="col-xs-10">Assign this Workout plan</div>
												</div>
											</a>
										</div>
										<div class="opt-row-detail">
											<button data-role="none" data-ajax="false" name="f_method" class="btn btn-default" type="submit" value="copy_workout_' . $fid . '" style="width:100%">
												<div class="col-xs-12 pointer removepading">
													<div class="col-xs-2"><i class="fa fa-files-o iconsize"></i></div>
													<div class="col-xs-10">Duplicate to My Workout Plans</div>
												</div>
											</button>
										</div>';
            if ($type != 'sample') {
                $response .= '<div class="opt-row-detail">
											<button data-role="none" data-ajax="false" class="btn btn-default" type="submit" onclick="return doHideProcess();" value="hide_single" name="f_method" style="width:100%">
												<div class="col-xs-12 pointer removepading">
													<div class="col-xs-2"><i class="fa fa-times iconsize"></i></div>
													<div class="col-xs-10">Delete this Shared Workout</div>
												</div>
											</button>
										</div>';
            }
            $response .= '<div class="opt-row-detail">
											<a data-role="none" data-ajax="false"  onclick="enableExport(' . "'" . $fid . "','" . $type . "'" . ');" href="javascript:void(0)" class="btn btn-default" style="width:100%">
												<div class="col-xs-12 pointer removepading">
													<div class="col-xs-2"><i class="fa fa-sign-out iconsize"></i></div>
													<div class="col-xs-10">Export this Workout plan</div>
												</div>
											</a>
										</div>
									</div>
									</form>
								</div>
							</div>
						</div>';
        } elseif (!empty($action) && trim($action) == 'addAssignWorkouts') {
            if (empty($date))
                $date = Helper_Common::get_default_date();
            $current = strtotime(Helper_Common::get_default_date());
            $datediff = strtotime($date) - $current;
            $difference = floor($datediff / (60 * 60 * 24));
            if ((($difference > 0 || $difference == 0 || $difference < 0) && (($type != 'loggedwkout' && $type != 'logged') || $type == '')) || (($difference == 0 || $difference < 0) && $type != '' && ($type == 'loggedwkout' || $type == 'logged'))) {
                $myworkoutDetailswkout = $workoutModel->getWorkoutDetailsByUser($user->pk(), $foldid);
                $myworkoutDetailswksam = $workoutModel->getSampleWorkoutDetails(0, $foldid);
                $myworkoutDetailswksha = $workoutModel->getSharedWorkoutDetails($user->pk());
                $myworkoutDetailswklog = $workoutModel->getLogworkoutDetails($user->pk());
                $myworkoutDetailswkass = $workoutModel->getAssignworkoutDetails($user->pk());
                if ($method == 'wrkout') {
                    $myworkoutDetails = $workoutModel->getWorkoutDetailsByUser($user->pk(), $foldid);
                    if ($foldid)
                        $parentFolder = $workoutModel->getFolderDetailsByUser($user->pk(), $foldid);
                    if (!empty($fid))
                        $workoutRecord = $workoutModel->getworkoutById($user->pk(), $fid);
                    $title = 'My Workout Plans';
                }else if ($method == 'sample') {
                    $myworkoutDetails = $workoutModel->getSampleWorkoutDetails(0, $foldid);
                    if (!empty($foldid))
                        $parentFolder = $workoutModel->getSampleFolderDetailsByUser('0', $foldid);
                    if (!empty($fid))
                        $workoutRecord = $workoutModel->getSampleworkoutById('0', $fid);
                    $title = 'Sample Workout Plans';
                }else if ($method == 'shared') {
                    $myworkoutDetails = $workoutModel->getSharedWorkoutDetails($user->pk(), $foldid);
                    if (!empty($foldid))
                        $parentFolder = $workoutModel->getShareFolderDetailsByUser($user->pk(), $foldid);
                    if (!empty($fid))
                        $workoutRecord = $workoutModel->getShareworkoutById($user->pk(), $fid);
                    $title = 'Shared Workout Plans';
                }else if ($method == 'wkoutlog' && empty($logId)) {
                    $myworkoutDetails = $workoutModel->getLogworkoutDetails($user->pk());
                    if (!empty($foldid))
                        $parentFolder = array();
                    if (!empty($fid))
                        $workoutRecord = $workoutModel->getLoggedworkoutById($fid, $user->pk());
                    $title = 'From Logged Workouts';
                }else if ($method == 'assigned') {
                    $myworkoutDetails = $workoutModel->getAssignworkoutDetails($user->pk());
                    if (!empty($foldid))
                        $parentFolder = array();
                    if (!empty($fid))
                        $workoutRecord = $workoutModel->getAssignworkoutById($fid, $user->pk());
                    $title = 'From Previous Workout Assignment';
                }elseif (!empty($type) && (!empty($fid) || !empty($assignId) || !empty($logId))) {
                    if (($type == 'logged' || $type == 'loggedwkout') && !empty($logId)) {
                        $myworkoutDetails = array();
                        $workoutRecord = $workoutModel->getLoggedworkoutById($logId, $user->pk());
                    } else if ($type == 'workout' && !empty($fid)) {
                        $myworkoutDetails = array();
                        $workoutRecord = $workoutModel->getworkoutById($user->pk(), $fid);
                    } else if ($type != '' && !empty($assignId))
                        $workoutRecord = $workoutModel->getAssignworkoutById($assignId, $user->pk());
                }
                $response = '<div class="addAssignWorkouts vertical-alignment-helper">
								<div class="modal-dialog">
									<div class="modal-content">
									<form action="" method="post" id="addAssignWorkouts" data-ajax="false">
										<div class="modal-header">';
                $response .= '<div class="row">
												<div class="mobpadding">
													<div class="border">
														<div class="col-xs-2">
															<a data-role="none" data-ajax="false" href="javascript:void(0)"';
                if (!empty($type) && $type == 'duplicate') {
                    $response .= 'onclick="closeModelwindow(' . "'" . $modelType . "'" . ');"';
                } elseif (empty($type)) {
                    if (empty($fid) && empty($foldid) && empty($method))
                        $response .= 'onclick="confirmAssignDate(' . "'" . $date . "'" . ');"';
                    else if (empty($fid) && empty($foldid) && !empty($method))
                        $response .= 'onclick="addAssignWorkoutsByDate(' . "'" . $date . "','0','0','0'" . ');"';
                    else
                        $response .= 'onclick="addAssignWorkoutsByDate(' . "'" . $date . "','" . (isset($parentFolder['parent_folder_id']) ? $parentFolder['parent_folder_id'] : (isset($workoutRecord['parent_folder_id']) ? $workoutRecord['parent_folder_id'] : '0')) . "','0','" . $method . "'" . ');"';
                }elseif ($type == 'workout') {
                    if (empty($fid) && empty($foldid) && empty($method))
                        $response .= 'onclick="closeOptionwindow();"';
                    else if (empty($fid) && empty($foldid) && !empty($method))
                        $response .= 'onclick="addAssignWorkoutsByDate(' . "'" . $date . "','0','0','" . '-' . $type . "'" . ');"';
                    else
                        $response .= 'onclick="addAssignWorkoutsByDate(' . "'" . $date . "','" . (isset($parentFolder['parent_folder_id']) ? $parentFolder['parent_folder_id'] : (isset($workoutRecord['parent_folder_id']) ? $workoutRecord['parent_folder_id'] : '0')) . "','0','" . $method . '-' . $type . "'" . ');"';
                }elseif ($type == 'logged' || $type == 'loggedwkout') {
                    if (!empty($logId))
                        $response .= 'onclick="closeModelwindow(' . "'" . $modelType . "'" . ');"';
                    else if (empty($fid) && empty($foldid) && empty($method)) {
                        if ($type == 'loggedwkout')
                            $response .= 'onclick="confirmLogDate(' . "'" . $date . "'" . ');"';
                        else
                            $response .= 'onclick="closeOptionwindow();"';
                    }else if (empty($fid) && empty($foldid) && !empty($method))
                        $response .= 'onclick="addAssignWorkoutsByDate(' . "'" . $date . "','0','0','" . '-' . $type . "'" . ');"';
                    else
                        $response .= 'onclick="addAssignWorkoutsByDate(' . "'" . $date . "','" . (isset($parentFolder['parent_folder_id']) ? $parentFolder['parent_folder_id'] : (isset($workoutRecord['parent_folder_id']) ? $workoutRecord['parent_folder_id'] : '0')) . "','0','" . $method . '-' . $type . "'" . ');"';
                }else {
                    if ($type == 'dulicateWkoutLog' || $type == 'sampleWkoutLog' || $type == 'shareWkoutLog')
                        $response .= 'onclick="confirmOtherLogDate(' . "'" . $date . "','" . $fid . "'" . ');"';
                    else if ($type != '')
                        $response .= 'onclick="closeModelwindow(' . "'" . $modelType . "'" . ');"';
                    else
                        $response .= 'onclick="addAssignWorkoutsByDate(' . "'" . $date . "','" . (isset($parentFolder['parent_folder_id']) ? $parentFolder['parent_folder_id'] : (isset($workoutRecord['parent_folder_id']) ? $workoutRecord['parent_folder_id'] : '0')) . "','0','" . $method . "'" . ');"';
                }
                $response .= ' class="triangle">
																<i class="fa fa-chevron-left iconsize"></i>
															</a>
														</div>
														<div class="col-xs-8 optionpoptitle">' . ($type != "" && $method == "action" ? "Re-assign a Workout Plan" : ($type == "wkoutLogCal" ? "Assign date to Journal Workout Plan" : ($type == 'logged' || $type == 'loggedwkout' ? (!empty($logId) ? "Duplicate this Journal" : ( $type == 'loggedwkout' ? "Log a Workout Plan" : "Start the Workout Plan")) : ($type == 'dulicateWkoutLog' || $type == 'sampleWkoutLog' || $type == 'shareWkoutLog' || $type == 'dulicateAssignWkoutLog' ? 'Assign date to Journal Workout Plan' : ($type == 'workout' ? "Create a Workout Plan" : "Assign a Workout Plan")))));
                if (isset($parentFolder) && empty($fid))
                    $response .= '<div>Workout Folder : </div><div>' . $parentFolder['folder_title'] . '</div>';
                else if (isset($workoutRecord) && !empty($fid))
                    $response .= '<div>Selected Workout: </div><div>' . $workoutRecord['wkout_title'] . '</div>';
                elseif (!empty($title))
                    $response .= '<div>Workout Folder : </div><div>' . $title . '</div>';
                $response .= '</div>
														<div class="col-xs-2">';
                if (empty($type) || ($type != 'logged' && $type != 'loggedwkout' && $type != 'workout')) {
                    if (isset($workoutRecord) && (!empty($fid) || !empty($assignId)) && $type != 'dulicateAssignWkoutLog')
                        $response .= '<button value="assignAdd" data-role="none" data-ajax="false" class="btn btn-default activedatacol" type="submit" name="f_method" style="background-color:#fff">ok</button>';
                    elseif ($type == 'dulicateWkoutLog' || $type == 'sampleWkoutLog' || $type == 'shareWkoutLog' || $type == 'dulicateAssignWkoutLog')
                        $response .= '<a data-role="none" data-ajax="false" href="javascript:void(0);" onclick="gotoLogPage(' . "'" . ($type == "sampleWkoutLog" ? 'startsample' : ($type == 'dulicateWkoutLog' ? 'startwklog' : ($type == 'dulicateAssignWkoutLog' ? 'startassign' : 'startshare'))) . "','" . $fid . "'" . ')" class="btn btn-default" style="background-color:#fff" >ok</a>';
                    elseif ($method != 'addNewDate' && ($type == 'wkoutAssignCal' || $type == "duplicate"))
                        $response .= '<button value="assignAdd" data-role="none" data-ajax="false" class="btn btn-default activedatacol" type="submit" name="f_method" style="background-color:#fff">ok</button>';
                    elseif ($type != '')
                        $response .= '<button value="assignAdd" data-role="none" data-ajax="false" class="btn btn-default activedatacol" type="button" name="f_method" style="background-color:#fff" onclick="addDatetoAssign(' . "'" . $date . "','" . $modelType . "'" . ');">ok</button>';
                }else if (($type == 'logged' || $type == 'loggedwkout') && !empty($fid)) {
                    $response .= '<button value="journalAdd" data-role="none" data-ajax="false" class="btn" type="submit" onclick="return jounalOptionsConfirm(' . "'" . $fid . "','" . $date . "','" . $method . "'" . ');" name="f_method" style="background-color:#fff">ok</button>';
                } else if ($type == 'workout' && !empty($fid)) {
                    $response .= '<button value="wkoutAdd" data-role="none" data-ajax="false" class="btn" type="submit" onclick="return jounalOptionsConfirm(' . "'" . $fid . "','" . $date . "','" . $method . "'" . ');" name="f_method" style="background-color:#fff">ok</button>';
                }
                $response .= '</div>
													</div>
												</div>
											</div>';
                if (!isset($parentFolder) && empty($foldid) && empty($fid) && !empty($method) && (empty($type) || $type == 'logged' || $type == 'loggedwkout' || $type == 'workout')) {
                    $response .= '<br><div class="opt-row-detail">
												<a data-role="none" class="border full" data-ajax="false" href="javascript:void(0)" onclick="addAssignWorkoutsByDate(' . "'" . $date . "','" . (isset($parentFolder['parent_folder_id']) ? $parentFolder['parent_folder_id'] : '0') . "','0','" . ($type == "logged" || $type == 'loggedwkout' || $type == "workout" ? '-' . $type : '0') . "'" . ')" style="width:100%" >
													<div class="col-xs-12 pointer">
														<div class="col-xs-2"><i class="fa fa-folder-open-o iconsize"></i></div>
														<div class="col-xs-10">Parent Folder:<br><div>';
                    $response .= $title . '</div></div>
													</div>
												</a>
											</div>';
                } elseif (isset($parentFolder) && !empty($parentFolder) && (empty($type) || $type == 'logged' || $type == 'loggedwkout' || $type == 'workout')) {
                    $response .= '<br><div class="opt-row-detail">
												<a data-role="none" class="border full" data-ajax="false" href="javascript:void(0)" onclick="addAssignWorkoutsByDate(' . "'" . $date . "','" . (isset($parentFolder['parent_folder_id']) ? $parentFolder['parent_folder_id'] : (isset($workoutRecord['parent_folder_id']) ? $workoutRecord['parent_folder_id'] : '0')) . "','0','" . $method . ($type == "logged" || $type == 'loggedwkout' || $type == "workout" ? '-' . $type : '') . "'" . ')" style="width:100%" >
													<div class="col-xs-12 pointer">
														<div class="col-xs-2"><i class="fa fa-folder-open-o iconsize"></i></div>
														<div class="col-xs-10">Parent Folder:<br><div>' . $parentFolder['folder_title'] . '</div></div>
													</div>
												</a>
											</div>';
                }
                $response .= '</div><div class="modal-body opt-body">';
                if (!isset($myworkoutDetails) && (empty($type) || $type == 'logged' || $type == 'loggedwkout' || $type == 'workout')) {
                    $response .= '<div class="opt-row-detail">
												<a data-role="none" data-ajax="false" class="activedatacol" href="javascript:void(0)" ' . ($type == 'logged' ? ' onclick="addNewWorkoutlogs(' . "'" . $date . "','0','0','" . $method . "'" . ');" ' : ($type == 'workout' ? ' onclick="createNewworkout();" ' : ($type == 'loggedwkout' ? ' onclick="addNewWorkoutlogs(' . "'" . $date . "','0','0','" . $type . "'" . ');" ' : ' onclick="addNewworkoutAssign(' . "'" . $date . "','0','0','" . $method . "'" . ');" '))) . ' style="width:100%" >
													<div class="col-xs-12 pointer">
														<div class="col-xs-2"><i class="fa fa-plus iconsize"></i></div>
														<div class="col-xs-10">Create Custom Plan</div>
													</div>
												</a>
											</div>
											<hr>
											<div class="opt-row-detail">
												<a data-role="none" data-ajax="false" href="javascript:void(0)" ' . (count($myworkoutDetailswkout) > 0 ? 'class="activedatacol" onclick="addAssignWorkoutsByDate(' . "'" . $date . "','0','0','" . ($type == "logged" || $type == 'loggedwkout' || $type == "workout" ? 'wrkout-' . $type : 'wrkout') . "'" . ');"' : 'class="inactivedatacol" onclick="return false;"') . ' style="width:100%" >
													<div class="col-xs-12 pointer">
														<div class="col-xs-2 ' . (count($myworkoutDetailswkout) > 0 ? '' : 'inactivedatacol') . ' "><i class="fa fa-folder-o iconsize ' . (count($myworkoutDetailswkout) > 0 ? '' : 'inactivedatacol') . '"></i></div>
														<div class="col-xs-10 ' . (count($myworkoutDetailswkout) > 0 ? '' : 'inactivedatacol') . '">From My Workout Plans</div>
													</div>
												</a>
											</div>
											<hr>
											<div class="opt-row-detail">
												<a data-role="none" data-ajax="false" href="javascript:void(0)" ' . (count($myworkoutDetailswksam) > 0 ? 'class="activedatacol" onclick="addAssignWorkoutsByDate(' . "'" . $date . "','0','0','" . ($type == "logged" || $type == 'loggedwkout' || $type == "workout" ? 'sample-' . $type : 'sample') . "'" . ');"' : 'class="inactivedatacol" onclick="return false;"') . ' style="width:100%" >
													<div class="col-xs-12 pointer">
														<div class="col-xs-2 ' . (count($myworkoutDetailswksam) > 0 ? '' : 'inactivedatacol') . '"><i class="fa fa-folder-o iconsize ' . (count($myworkoutDetailswksam) > 0 ? '' : 'inactivedatacol') . '"></i></div>
														<div class="col-xs-10 ' . (count($myworkoutDetailswksam) > 0 ? '' : 'inactivedatacol') . '">From Sample Workout Plans</div>
													</div>
												</a>
											</div>
											<hr>
											<div class="opt-row-detail">
												<a data-role="none" data-ajax="false" href="javascript:void(0)" ' . (count($myworkoutDetailswksha) > 0 ? 'class="activedatacol" onclick="addAssignWorkoutsByDate(' . "'" . $date . "','0','0','" . ($type == "logged" || $type == 'loggedwkout' || $type == "workout" ? 'shared-' . $type : 'shared') . "'" . ');"' : 'class="inactivedatacol" onclick="return false;"') . ' style="width:100%" >
													<div class="col-xs-12 pointer">
														<div class="col-xs-2 ' . (count($myworkoutDetailswksha) > 0 ? '' : 'inactivedatacol') . '"><i class="fa fa-folder-o iconsize ' . (count($myworkoutDetailswksha) > 0 ? '' : 'inactivedatacol') . '"></i></div>
														<div class="col-xs-10 ' . (count($myworkoutDetailswksha) > 0 ? '' : 'inactivedatacol') . '">From Shared Workout Plans</div>
													</div>
												</a>
											</div>
											<hr>
											<div class="opt-row-detail">
												<a data-role="none" data-ajax="false" href="javascript:void(0)" ' . (count($myworkoutDetailswkass) > 0 ? 'class="activedatacol" onclick="addAssignWorkoutsByDate(' . "'" . $date . "','0','0','" . ($type == "logged" || $type == 'loggedwkout' || $type == "workout" ? 'assigned-' . $type : 'assigned') . "'" . ');"' : 'class="inactivedatacol" onclick="return false;"') . ' style="width:100%" >
													<div class="col-xs-12 pointer">
														<div class="col-xs-2 ' . (count($myworkoutDetailswkass) > 0 ? '' : 'inactivedatacol') . '"><i class="fa fa-calendar iconsize ' . (count($myworkoutDetailswkass) > 0 ? '' : 'inactivedatacol') . '"></i></div>
														<div class="col-xs-10 ' . (count($myworkoutDetailswkass) > 0 ? '' : 'inactivedatacol') . '">From Previous Workout Assignment</div>
													</div>
												</a>
											</div>
											<hr>
											<div class="opt-row-detail">
												<a data-role="none" data-ajax="false" href="javascript:void(0)" ' . (count($myworkoutDetailswklog) > 0 ? 'class="activedatacol" onclick="addAssignWorkoutsByDate(' . "'" . $date . "','0','0','" . ($type == "logged" || $type == 'loggedwkout' || $type == "workout" ? 'wkoutlog-' . $type : 'wkoutlog') . "'" . ');"' : 'class="inactivedatacol" onclick="return false;"') . ' style="width:100%" >
													<div class="col-xs-12 pointer">
														<div class="col-xs-2 ' . (count($myworkoutDetailswklog) > 0 ? '' : 'inactivedatacol') . '"><i class="fa fa-calendar iconsize ' . (count($myworkoutDetailswklog) > 0 ? '' : 'inactivedatacol') . '"></i></div>
														<div class="col-xs-10 ' . (count($myworkoutDetailswklog) > 0 ? '' : 'inactivedatacol') . '">From Logged Workouts</div>
													</div>
												</a>
											</div>';
                } else if (empty($fid) && (empty($type) || $type == 'logged' || $type == 'loggedwkout' || $type == 'workout')) {
                    if (isset($myworkoutDetails) && !empty($myworkoutDetails)) {
                        $response .= '<ul class="workout-list' . (count($myworkoutDetails) > 6 ? ' scrollwkout' : '') . '">';
                        foreach ($myworkoutDetails as $keys => $values) {
                            if ((isset($values['wkout_id']) && ($values['wkout_id'] != '0' ) && !isset($values['wkout_assign_id'])) || (isset($values['wkout_sample_id']) && ($values['wkout_sample_id'] != '0' )) || (isset($values['wkout_share_id']) && ($values['wkout_share_id'] != '0' )) || (isset($values['wkout_assign_id']) && ($values['wkout_assign_id'] != '0' )) || (isset($values['wkout_log_id']) && ($values['wkout_log_id'] != '0' ))) {
                                $wkoutId = (isset($values['wkout_sample_id']) ? $values['wkout_sample_id'] : (isset($values['wkout_share_id']) ? $values['wkout_share_id'] : (isset($values['wkout_assign_id']) ? $values['wkout_assign_id'] : (isset($values['wkout_log_id']) ? $values['wkout_log_id'] : (isset($values['wkout_id']) ? $values['wkout_id'] : '0')))));
                                $response .= '<li><div class="opt-row-detail border-xr full xr-setitem">
															<div class="col-xs-10 xrset-combi" ' . ($type == 'logged' || $type == 'loggedwkout' ? 'onclick="addWorkoutlogs(' . "'" . $date . "','" . (isset($values['parent_folder_id']) ? $values['parent_folder_id'] : '0') . "','" . $wkoutId . "','" . $method . ($type == "logged" || $type == 'loggedwkout' || $type == "workout" ? '-' . $type : '') . "'" . ')"' : ($type == 'assigned' || $type == '' ? 'onclick="createNewworkoutAssign(' . "'" . $date . "','" . (isset($values['parent_folder_id']) ? $values['parent_folder_id'] : '0') . "','" . $wkoutId . "','" . $method . ($type == "logged" || $type == 'loggedwkout' || $type == "workout" ? '-' . $type : '') . "'" . ')"' : ($type == 'workout' ? 'onclick="previewworkout(' . "'" . $date . "','" . (isset($values['parent_folder_id']) ? $values['parent_folder_id'] : '0') . "','" . $wkoutId . "','" . $method . "'" . ')"' : ''))) . '>
																<div class="colorchoosen col-xs-2" style="margin-top:3px;">
																	<i class="glyphicon ' . $values['color_title'] . '" style="float:left"></i>
																</div>
																<div class="col-xs-10 wrapword text-left"><div>' . $values['wkout_title'] . '</div><div class="wkoutfocus">' . ucfirst($values['wkout_focus']) . '</div></div>
															</div>
															<div class="col-xs-2 xrset-insert">' . '<i onclick="insertToWkout(' . "'" . $wkoutId . "','" . $method . "','" . $type . "','" . $date . "','" . addSlashes(ucfirst($values['wkout_title'])) . "'" . ');" class="fa fa-sign-in iconsize"></i>' . '</div>
														</div>
													</li>';
                            } else {
                                $response .= '<li><div class="opt-row-detail border-xr full xr-setitem">
															<a data-role="none" data-ajax="false" href="javascript:void(0)" onclick="addAssignWorkoutsByDate(' . "'" . $date . "','" . $values['folder_id'] . "'," . "'0','" . $method . ($type == "logged" || $type == 'loggedwkout' || $type == "workout" ? '-' . $type : '') . "'" . ')" style="width:100%">
																<div class="col-xs-10 xrset-folder">
																	<div class="colorchoosen col-xs-2"><i class="fa fa-folder-o iconsize" style="float:left"></i></div>
																	<div class="col-xs-10 text-left">' . $values['folder_title'] . '<div class="navimgdet3">' . (($values['totalRecords'] > 0 ) ? (($values['totalRecords'] > 1 ) ? 'Records : <span>' . $values['totalRecords'] . '</span>' : 'Record : <span>' . $values['totalRecords'] . '</span>') : 'Record : <span>0</span>') . '</div></div>
																</div>
																<div class="col-xs-2"></div>
															</a>
														</div>
												</li>';
                            }
                        }
                        $response .= '</ul>';
                    }
                } else {
                    $response .= '<div class="opt-row-detail">
														<div class="aligncenter">Selected Date:</div>
														<div class="mobipick-main" style="display:flex;"></div>
														<input type="hidden" name="selected_date" ' . ($type == 'wkoutLogCal' || $type == 'logged-create' || $type == 'dulicateWkoutLog' || $type == 'dulicateAssignWkoutLog' ? ($type == 'dulicateAssignWkoutLog' || $type == 'dulicateWkoutLog' ? 'max="' . Helper_Common::get_default_date('', 'd M Y') . '"' : '') : 'min="' . Helper_Common::get_default_date('', 'd M Y') . '"') . ' value="' . date("d M Y", strtotime($date)) . '" class="min-date-hidden"/>' . (($type == "wkoutAssign" || $type == 'wkoutAssignCal') && !empty($assignId) ? '<input type="hidden" name="selected_wkout_assign_id" value="' . $assignId . '"/>' : ( $method == 'wrkout' ? '<input type="hidden" name="selected_wkout_id" value="' . $fid . '"/>' : ($method == 'sample' ? '<input type="hidden" name="selected_sampe_id" value="' . $fid . '"/>' : ($method == 'shared' ? '<input type="hidden" name="selected_share_id" value="' . $fid . '"/>' : (!empty($logId) ? '<input type="hidden" name="selected_log_id" value="' . $logId . '"/>' : ($method == 'assigned' ? '<input type="hidden" name="selected_wkout_assign_dup_id" value="' . $fid . '"/>' : '<input type="hidden" name="selected_wkout_id" value="' . $fid . '"/>'))))) ) . '
												  </div>';
                }
                $response .= '</div>
									<div class="modal-footer"><button data-ajax="false" data-role="none" type="button" class="btn btn-default activedatacol" data-dismiss="modal">cancel</button>';
                if (empty($type) || ($type != 'logged' && $type != 'loggedwkout' && $type != 'workout')) {
                    if (isset($workoutRecord) && (!empty($fid) || !empty($assignId)) && $type != 'dulicateAssignWkoutLog')
                        $response .= '<button value="assignAdd" data-role="none" data-ajax="false" class="btn btn-default activedatacol" type="submit" name="f_method" style="background-color:#fff">ok</button>';
                    elseif ($type == 'dulicateWkoutLog' || $type == 'sampleWkoutLog' || $type == 'shareWkoutLog' || $type == 'dulicateAssignWkoutLog')
                        $response .= '<a data-role="none" data-ajax="false" href="javascript:void(0);" onclick="gotoLogPage(' . "'" . ($type == "sampleWkoutLog" ? 'startsample' : ($type == 'dulicateWkoutLog' ? 'startwklog' : ($type == 'dulicateAssignWkoutLog' ? 'startassign' : 'startshare'))) . "','" . $fid . "'" . ')" class="btn btn-default" style="background-color:#fff" >ok</a>';
                    elseif ($method != 'addNewDate' && ($type == 'wkoutAssignCal' || $type == "duplicate"))
                        $response .= '<button value="assignAdd" data-role="none" data-ajax="false" class="btn btn-default activedatacol" type="submit" name="f_method" style="background-color:#fff">ok</button>';
                    elseif ($type != '')
                        $response .= '<button value="assignAdd" data-role="none" data-ajax="false" class="btn btn-default activedatacol" type="button" name="f_method" style="background-color:#fff" onclick="addDatetoAssign(' . "'" . $date . "','" . $modelType . "'" . ');">ok</button>';
                }else if (($type == 'logged' || $type == 'loggedwkout') && !empty($fid)) {
                    $response .= '<button value="journalAdd" data-role="none" data-ajax="false" class="btn" type="submit" onclick="return jounalOptionsConfirm(' . "'" . $fid . "','" . $date . "','" . $method . "'" . ');" name="f_method" style="background-color:#fff">ok</button>';
                } else if ($type == 'workout' && !empty($fid)) {
                    $response .= '<button value="wkoutAdd" data-role="none" data-ajax="false" class="btn" type="submit" onclick="return jounalOptionsConfirm(' . "'" . $fid . "','" . $date . "','" . $method . "'" . ');" name="f_method" style="background-color:#fff">ok</button>';
                }
                $response .= (isset($type) && ($type == 'wkoutLogCal' || $type == 'dulicateAssignWkoutLog' || $type == 'duplicate' || $type == 'dulicateWkoutLog' || $type == 'wkoutAssignCal' || $type == 'wkoutAssignCalender') ? ($type != $method ? '<script type="text/javascript">$(document).ready(function(){ if($(".min-date-hidden")){$(".min-date-hidden").mobipick().click();}});</script>' : '<script type="text/javascript">$(document).ready(function(){ if($(".min-date-hidden")){$(".min-date-hidden").mobipick().click();}});</script>') : '') . '</div>
									</form>
									</div>
								</div>
						</div>';
            } else {
                $response = '<div class="addLogWorkouts vertical-alignment-helper">
								<div class="modal-dialog">
									<div class="modal-content">
									<form action="" method="post" id="addLogWorkouts" data-ajax="false">
										<div class="modal-header">
											<div class="row">
												<div class="mobpadding">
													<div class="border">
														<div class="col-xs-2">
															<a data-role="none" data-ajax="false" href="javascript:void(0)" onclick="closeModelwindow(' . "'" . $modelType . "'" . ');" class="triangle"><i class="fa fa-chevron-left iconsize"></i>
															</a>
														</div>
														<div class="col-xs-8 optionpoptitle">Log a Workout Record</div>
														<div class="col-xs-2"></div>
													</div>
												</div>
											</div>
										</div>
										<div class="modal-body opt-body">
											<div class="opt-row-detail">
												<div class="aligncenter">You have choosen Future date!!!.</div>
										  </div>
										</div>
										<div class="modal-footer"><button data-ajax="false" data-role="none" type="button" class="btn btn-default" data-dismiss="modal">cancel</button></div>
										</form>
									</div>
								</div>
							</div>';
            }
        } elseif (!empty($action) && trim($action) == 'addExercise') {
            $xrRecDetailsmy = $xrRecDetailssample = $xrRecDetailsshared = 0;
            if (empty($type)) {
                $xrRecDetailsmy = $workoutModel->getExerciseByType();
                $xrRecDetailssample = $workoutModel->getExerciseByType('sampleexercise');
                $xrRecDetailsshared = $workoutModel->getExerciseByType('sharedexercise');
            }
            if ($type == 'myexercise') {
                $myExerciseDetails = $workoutModel->getExerciseByType();
                if (!empty($fid))
                    $exerciseRecord = $workoutModel->getExerciseByType('', $fid);
                $title = 'My Exercise Records';
            }else if ($type == 'sampleexercise') {
                $myExerciseDetails = $workoutModel->getExerciseByType('sampleexercise');
                if (!empty($fid))
                    $exerciseRecord = $workoutModel->getExerciseByType('sampleexercise', $fid);
                $title = 'Sample Exercise Records';
            }else if ($type == 'sharedexercise') {
                $myExerciseDetails = $workoutModel->getExerciseByType('sharedexercise');
                if (!empty($fid))
                    $exerciseRecord = $workoutModel->getExerciseByType('sharedexercise', $fid);
                $title = 'Shared Exercise Records';
            }
            $response = '<div class="addAssignExercise vertical-alignment-helper">
						<div class="modal-dialog">
							<div class="modal-content">
							<form action="" method="post" id="addAssignExercise" data-ajax="false">
								<div class="modal-header">';
            $response .= '<div class="row">
										<div class="mobpadding">
											<div class="border">
												<div class="col-xs-2">
													<a data-role="none" data-ajax="false" href="javascript:void(0)"' . (!empty($type) ? 'onclick="createExercise();"' : 'data-dismiss="modal"') . ' class="triangle">
														<i class="fa fa-chevron-left iconsize"></i>
													</a>
												</div>
												<div class="col-xs-8 optionpoptitle">Create a Exercise Record</div>
												<div class="col-xs-2"></div>
											</div>
										</div>
									</div>';
            if (empty($fid) && !empty($type)) {
                $response .= '<br><div class="opt-row-detail">
										<a data-role="none" class="border full" data-ajax="false" href="javascript:void(0)" onclick="createExercise();" style="width:100%" >
											<div class="col-xs-12 pointer">
												<div class="col-xs-2"><i class="fa fa-folder-open-o iconsize"></i></div>
												<div class="col-xs-10">Parent Folder:<br><div>' . $title . '</div></div>
											</div>
										</a>
									</div>';
            }
            $response .= '</div><div class="modal-body opt-body">';
            if (!isset($myExerciseDetails) && empty($fid)) {
                $response .= '<div class="opt-row-detail">
										<a data-role="none" data-ajax="false" class="activedatacol" href="javascript:void(0)" onclick="' . ($actionFrom == 'exercise' ? 'createNewExercise();' : 'xrLibcreateNewExercise();') . '" style="width:100%" data-request="' . $requestFrom . '" data-actionreq="' . $actionFrom . '">
											<div class="col-xs-12 pointer">
												<div class="col-xs-2"><i class="fa fa-plus iconsize"></i></div>
												<div class="col-xs-10">Create Custom Exercise Record</div>
											</div>
										</a>
									</div>
									<hr>
									<div class="opt-row-detail">
										<a data-role="none" data-ajax="false" href="javascript:void(0)" ' . (count($xrRecDetailsmy) > 0 ? 'class="activedatacol" onclick="' . ($actionFrom == 'exercise' ? 'createExerciseFromXrLibrary' : 'xrLibcreateExerciseFromXrLibrary') . '(' . "'myexercise'" . ')"' : 'class="inactivedatacol" onclick="return false;"') . ' style="width:100%" >
											<div class="col-xs-12 pointer">
												<div class="col-xs-2 ' . (count($xrRecDetailsmy) > 0 ? '' : 'inactivedatacol') . ' "><i class="fa fa-folder-o iconsize ' . (count($xrRecDetailsmy) > 0 ? '' : 'inactivedatacol') . '"></i></div>
												<div class="col-xs-10 ' . (count($xrRecDetailsmy) > 0 ? '' : 'inactivedatacol') . '">From My Exercise Records</div>
											</div>
										</a>
									</div>
									<hr>
									<div class="opt-row-detail">
										<a data-role="none" data-ajax="false" href="javascript:void(0)" ' . (count($xrRecDetailssample) > 0 ? 'class="activedatacol" onclick="' . ($actionFrom == 'exercise' ? 'createExerciseFromXrLibrary' : 'xrLibcreateExerciseFromXrLibrary') . '(' . "'sampleexercise'" . ')"' : 'class="inactivedatacol" onclick="return false;"') . ' style="width:100%" >
											<div class="col-xs-12 pointer">
												<div class="col-xs-2 ' . (count($xrRecDetailssample) > 0 ? '' : 'inactivedatacol') . '"><i class="fa fa-folder-o iconsize ' . (count($xrRecDetailssample) > 0 ? '' : 'inactivedatacol') . '"></i></div>
												<div class="col-xs-10 ' . (count($xrRecDetailssample) > 0 ? '' : 'inactivedatacol') . '">From Sample Exercise Records</div>
											</div>
										</a>
									</div>
									<hr>
									<div class="opt-row-detail">
										<a data-role="none" data-ajax="false" href="javascript:void(0)" ' . (count($xrRecDetailsshared) > 0 ? 'class="activedatacol" onclick="' . ($actionFrom == 'exercise' ? 'createExerciseFromXrLibrary' : 'xrLibcreateExerciseFromXrLibrary') . '(' . "'sharedexercise'" . ')"' : 'class="inactivedatacol" onclick="return false;"') . ' style="width:100%" >
											<div class="col-xs-12 pointer">
												<div class="col-xs-2 ' . (count($xrRecDetailsshared) > 0 ? '' : 'inactivedatacol') . '"><i class="fa fa-folder-o iconsize ' . (count($xrRecDetailsshared) > 0 ? '' : 'inactivedatacol') . '"></i></div>
												<div class="col-xs-10 ' . (count($xrRecDetailsshared) > 0 ? '' : 'inactivedatacol') . '">From Shared Exercise Records</div>
											</div>
										</a>
									</div>';
            } else if (empty($fid) && !empty($type)) {
                if (isset($myExerciseDetails) && !empty($myExerciseDetails)) {
                    $response .= '<ul class="' . (count($myExerciseDetails) > 6 ? 'scrollwkout' : '') . '">';
                    foreach ($myExerciseDetails as $keys => $values) {
                        $imgpath = '';
                        $response .= '<li><div class="opt-row-detail border-xr full">
											<div class="col-xs-12 pointer">
												<div class="col-xs-10" onclick="getExercisepreviewOption(' . "'" . $values['unit_id'] . "','" . $type . "','xrrecord'" . ');">
													<div class="colorchoosen col-xs-2" style="float:left;margin-top:5px;">';
                        if (file_exists($values["img_url"])) {
                            $response .= '<img width="30px" src="' . URL::base() . $values['img_url'] . '" title="' . ucfirst($values['img_title']) . '"/>';
                        } else {
                            $response .= '<i class="fa fa-file-image-o datacol" style="font-size:25px;"></i>';
                        }
                        if ($values['img_url'] != '') {
                            $imgpath = URL::base() . $values['img_url'];
                        }
                        $response .= '</div>
													<div class="col-xs-9 wrapword text-left"><div>' . ucfirst($values['title']) . '</div></div>
												</div>
												<div class="col-xs-2">' . '<i onclick="insertToExerciseSet(' . "'" . $values['unit_id'] . "','" . $type . "','" . $imgpath . "','" . addSlashes(ucfirst($values['title'])) . "'" . ');" class="fa fa-sign-in iconsize"></i>' . '</div>
											</div>
										</div>
										</li>';
                    }
                    $response .= '</ul>';
                }
            }
            $response .= '</div>
							<div class="modal-footer"><button data-ajax="false" data-role="none" type="button" class="btn btn-default" data-dismiss="modal">cancel</button></div>
							</form>
							</div>
						</div>
				</div>';
        } elseif (!empty($action) && trim($action) == 'workoutStatusChange') {
            $response .= '<div class="vertical-alignment-helper"><div class="modal-dialog"><div class="modal-content">
				<form action="" method="post"  data-ajax="false">
					<div class="modal-header"><input type="hidden" value="' . $date . '" name="selected_date" />' . (!empty($type) && strtolower($type) == 'logged' ? '<input type="hidden" value="' . $fid . '" name="wkout_log_id" />' : '<input type="hidden" value="' . $fid . '" name="wkout_assign_id" />');
            $response .= '<div class="row">
								<div class="mobpadding">
									<div class="border">
										<div class="col-xs-2">
											<a data-ajax="false" data-onclick="$(' . "'input.checkassignstatus'" . ').attr(' . "'checked'" . ', false);" data-text="Clicking BACK or CANCEL will discard any changes. Clicking Notes will mark this Journal is completed or skipped. Continue with exiting?" data-role="none" href="javascript:void(0);" class="triangle confirm pointers" data-allow="' . ($confirmAction ? 'false' : 'true') . '">
												<i class="fa fa-chevron-left iconsize"></i>
											</a>
										</div>
										<div class="col-xs-8 optionpoptitle">Notes for this Workout (Overall)</div>
										<div class="col-xs-2"><button data-role="none" data-ajax="false" data-ajax="false" data-role="none" name="f_method" class="btn btn-default add_new_log_comp" ' . ($goalOrder == '0' ? '' : 'style="display:none"') . ' value="add_new_log_comp" ><i class="fa fa-pencil-square-o iconsize"></i></button>
										<button data-role="none" data-ajax="false" data-ajax="false" data-role="none" name="f_method" class="btn btn-default add_new_log_skip" ' . ($goalOrder == '1' ? '' : 'style="display:none"') . ' value="add_new_log_skip" ><i class="fa fa-pencil-square-o iconsize"></i></button></div>
									</div>
								</div>
							</div>
						</div>
						<div class="modal-body opt-body">';
            $response .= '<div class="row"><div class="mobpadding"><div class="border full">';
            $response .= '<div class="col-xs-3 borderright">Perceived Intensity</div>';
            $response .= '<div class="col-xs-8 drop down selectToUISlider"><div class="sliderview"><a href="javascript:void(0);" tabindex="0" id="handle_speed" class="ui-slider-handle ui-state-default ui-corner-all ui-state-hover" role="slider" aria-labelledby="label_handle_speed" aria-valuemin="0" style="left: 25%;"><span class="ui-slider-tooltip ui-widget-content ui-corner-all"><span class="ttContent"></span></span></a></div><input oninput="showval(this.value);" onchange="showval(this.value);" type="range" name="slider-1" id="slider-1" value="' . (isset($intensity) && !empty($intensity) ? $intensity : '0') . '" min="0" max="19" data-popup-enabled="true"><select name="note_wkout_intensity" id="note_wkout_intensity" class="hide"><option value="0">Select</option>';
            $repetitions = $workoutModel->getInnerDrive();
            if (isset($repetitions) && count($repetitions) > 0) {
                foreach ($repetitions as $keys => $values) {
                    $response .= '<option  value="' . $values['int_id'] . '">' . ucfirst($values['int_grp_title']) . '(' . ucfirst($values['int_opt_title']) . ')' . '</option>';
                }
            }
            $response .= '</select></div></div></div><script>$(document).ready(function (){$(".ttContent").text(document.getElementById("note_wkout_intensity").options[$("#slider-1").val()].text).focus();});function showval(val){$(".ttContent").text(document.getElementById("note_wkout_intensity").options[val].text);}</script></div>';
            $response .= '<div class="row"><div class="mobpadding"><div class="border full">';
            $response .= '<div class="col-xs-3 borderright">Remarks / Notes</div>';
            $response .= '<div class="col-xs-8"><textarea id="note_wkout_remarks" name="note_wkout_remarks" class="form-control input-lg" style="width:100%">' . (!empty($remarks) ? stripslashes($remarks) : '') . '</textarea></div>';
            $response .= '</div></div></div>';
            $response .= '<div class="row"><div class="mobpadding"><div class="">';
            $response .= '<div class="col-xs-12"><input type="checkbox" name="is_hide_note" value="1" id="is_hide_note"/> <label for="is_hide_note">Don\'t show this dialog again</label></div>';
            $response .= '</div></div></div>';
            $response .= '</div>
					<div class="modal-footer"><button data-role="none" data-ajax="false" data-ajax="false" data-role="none" name="f_method" class="btn btn-default add_new_log_comp" value="add_new_log_comp" ' . ($goalOrder == '0' ? 'style="padding-left:10px;"' : 'style="display:none"') . '>Skip</button><button data-role="none" data-ajax="false" data-ajax="false" data-role="none" name="f_method" class="btn btn-default add_new_log_skip" ' . ($goalOrder == '1' ? 'style="padding-left:10px;"' : 'style="display:none"') . ' value="add_new_log_skip" >Skip</button><button data-role="none" data-ajax="false" data-ajax="false" data-role="none" name="f_method" class="btn btn-default add_new_log_comp" value="add_new_log_comp" ' . ($goalOrder == '0' ? 'style="float:right;"' : 'style="display:none"') . ' ><i class="fa fa-pencil-square-o iconsize"></i></button><button data-role="none" data-ajax="false" data-ajax="false" data-role="none" name="f_method" class="btn btn-default add_new_log_skip" ' . ($goalOrder == '1' ? 'style="float:right;"' : 'style="display:none"') . ' value="add_new_log_skip"><i class="fa fa-pencil-square-o iconsize"></i></button></div></form>';
            $response .= '</div></div></div>';
        } elseif (!empty($action) && trim($action) == 'workoutLogConfirm') {
            if ((!Helper_Common::getAllowAllAccessByUser($page_id, 'is_note_hidden') && !empty($type) && $type == 'loggedwkout') || (!Helper_Common::getAllowAllAccessByUser($page_id, 'is_note_set_hidden') && !empty($type) && ($type == 'loggedexercise' || $type == 'xrlog' || $type == 'loggedexerciseconf'))) {
                $response = '<div class="addAssignWorkouts vertical-alignment-helper">';
                $response .= '<div id="workoutLogConfirm" class="modal-dialog">
									<div class="modal-content">';
                $response .= '<form action="" method="post" id="addAssignWorkouts" data-ajax="false">';
                if (!empty($type) && $type == 'wkoutLogFromPrev') {
                    $response .= '<input type="hidden" value="' . $method . '" name="method" /><input type="hidden" value="' . $fid . '" name="' . $method . '_id" />';
                } else {
                    $response .= '<input type="hidden" value="' . $method . '" name="method" /><input type="hidden" value="' . $fid . '" name="workout_id" />' . (!empty($type) && (strtolower($type) == 'loggedwkout' || strtolower($type) == 'loggedexercise' || strtolower($type) == 'loggedexerciseconf') ? '<input type="hidden" value="' . $logId . '" name="wkout_log_id" />' : (!empty($logId) ? '<input type="hidden" value="' . $logId . '" name="wkout_log_id" />' : ''));
                }
                $response .= '<div class="modal-header">
											<div class="row">
												<div class="mobpadding">
													<div class="border">
														<div class="col-xs-2">
															<a data-role="none" data-ajax="false" href="javascript:void(0)" onclick="closeModelwindow(' . "'" . (isset($modelType) ? $modelType : '') . "'" . ');" class="triangle pointers"><i class="fa fa-chevron-left iconsize"></i>
															</a>
														</div>
														<div class="col-xs-8 optionpoptitle">' . (!empty($type) && (strtolower($type) == 'loggedexercise' || strtolower($type) == 'xrlog' || strtolower($type) == 'loggedexerciseconf') ? 'Notes for this Exercise Set' : 'Notes for this  Journal (Overall)') . '</div>
														<div class="col-xs-2">';
                if (!empty($type) && $type == 'loggedwkout') {
                    $response .= '<button id="update_log" data-ajax="false" data-role="none" name="f_method" class="btn btn-default" value="update_log_conf" type="button" onclick="confirmLogDetails(' . "'" . $goalOrder . "'" . ');" ><i class="fa fa-pencil-square-o iconsize"></i></button>';
                } elseif (!empty($type) && ($type == 'loggedexercise' || $type == 'xrlog' || $type == 'loggedexerciseconf')) {
                    $response .= '<button id="update_log" data-ajax="false" data-role="none" name="f_method" class="btn btn-default" value="update_log" type="button" onclick="confirmExerciseDetails(' . "'" . $foldid . "','" . $xrsetid . "','" . $goalOrder . "'" . ');" ><i class="fa fa-pencil-square-o iconsize"></i></button>';
                } else if (!empty($type) && $type == 'wkoutLogFromPrev') {
                    $response .= '<button id="add_new_log_start" data-ajax="false" data-role="none" name="f_method" class="btn btn-default" value="add_new_log_start" type="submit" ><i class="fa fa-pencil-square-o iconsize"></i></button><input type="hidden" value="' . $date . '" name="selected_date_hidden" />';
                } else {
                    $response .= '<button id="add_new_log_start" data-ajax="false" data-role="none" name="f_method" onclick="confirmLogDetails(' . "'" . ($goalOrder >= 0 ? $goalOrder : '1') . "'" . ');" class="btn btn-default" value="add_new_log_start" type="button" ><i class="fa fa-pencil-square-o iconsize"></i></button>';
                }
                $response .= '</div>
													</div>
												</div>
											</div>
										</div>
										<div class="modal-body opt-body">';
                $response .= '<div class="row"><div class="mobpadding"><div class="border full">';
                $response .= '<div class="col-xs-3 borderright">Perceived Intensity</div>';
                $response .= '<div class="col-xs-8 drop down selectToUISlider">
											<div class="sliderview"><a href="javascript:void(0);" tabindex="0" id="handle_speed" class="ui-slider-handle ui-state-default ui-corner-all ui-state-hover" role="slider" aria-labelledby="label_handle_speed" aria-valuemin="0" style="left: 25%;"><span class="ui-slider-tooltip ui-widget-content ui-corner-all"><span class="ttContent"></span></span></a></div>
											<input oninput="showval(this.value);" onchange="showval(this.value);" type="range" name="slider-1" id="slider-1" value="' . (!empty($intensity) ? $intensity : '0') . '" min="0" max="19" data-popup-enabled="true"><select date-role="none" data-native-menu="false" name="note_wkout_intensity"  id="note_wkout_intensity" class="hide"><option selected value="0">Select</option>';
                $repetitions = $workoutModel->getInnerDrive();
                if (isset($repetitions) && count($repetitions) > 0) {
                    foreach ($repetitions as $keys => $values) {
                        $response .= '<option  value="' . $values['int_id'] . '">' . ucfirst($values['int_grp_title']) . '(' . ucfirst($values['int_opt_title']) . ')' . '</option>';
                    }
                }
                $response .= '</select></div></div></div></div>';
                $response .= '<div class="row"><div class="mobpadding"><div class="border full">';
                $response .= '<div class="col-xs-3 borderright">Remarks / Notes</div>';
                $response .= '<div class="col-xs-8"><textarea id="note_wkout_remarks" name="note_wkout_remarks" class="form-control input-lg" style="width:100%">' . (!empty($remarks) ? stripslashes($remarks) : '') . '</textarea></div>';
                $response .= '</div></div></div><br>';
                if (!empty($type) && (strtolower($type) == 'loggedexercise' || strtolower($type) == 'xrlog' || strtolower($type) == 'loggedexerciseconf')) {
                    $response .= '<div class="row"><div class="mobpadding"><div class="">';
                    $response .= '<div class="col-xs-12"><input type="checkbox" name="hide_note_set" onclick="notifyUpdate(this);" value="1" id="hide_note_set"/> <label for="hide_note_set">Don\'t show this dialog again</label></div>';
                    $response .= '</div></div></div>';
                } else {
                    $response .= '<div class="row"><div class="mobpadding"><div class="">';
                    $response .= '<div class="col-xs-12"><input type="checkbox" name="hide_note" onclick="notifyUpdate(this);" value="1" id="hide_note"/> <label for="hide_note">Don\'t show this dialog again</label></div>';
                    $response .= '</div></div></div>';
                }
                $response .= '</div>';
                if (!empty($type) && $type == 'loggedwkout') {
                    $response .= '<div class="modal-footer"><button data-ajax="false" data-role="none" type="button" class="btn btn-default pointers" onclick="skipWorkoutNotesToLog(' . "'" . $goalOrder . "','" . $modelType . "'" . ');">Skip</button><button id="update_log" data-ajax="false" data-role="none" name="f_method" class="btn btn-default" value="update_log_conf" type="button" onclick="confirmLogDetails(' . "'" . $goalOrder . "'" . ');" ><i class="fa fa-pencil-square-o iconsize"></i></button></div>';
                } elseif (!empty($type) && ($type == 'loggedexercise' || $type == 'xrlog')) {
                    $response .= '<div class="modal-footer"><button data-ajax="false" data-role="none" type="button" class="btn btn-default pointers" onclick="skipExerciseNotesToLog(' . "'" . $xrsetid . "','" . $goalOrder . "','" . $modelType . "'" . ');">Skip</button><button id="update_log" data-ajax="false" data-role="none" name="f_method" class="btn btn-default" value="update_log" type="button" onclick="confirmExerciseDetails(' . "'" . $foldid . "','" . $xrsetid . "','" . $goalOrder . "'" . ');" ><i class="fa fa-pencil-square-o iconsize"></i></button></div>';
                } elseif (!empty($type) && $type == 'loggedexerciseconf') {
                    $response .= '<div class="modal-footer"><button data-ajax="false" data-role="none" type="button" class="btn btn-default pointers" onclick="skipExerciseNotes(' . "'" . $foldid . "','2','" . $modelType . "'" . ');">Skip</button><button id="update_log" data-ajax="false" data-role="none" name="f_method" class="btn btn-default" value="update_log" type="button" onclick="confirmExerciseDetails(' . "'" . $foldid . "','" . $xrsetid . "','" . $goalOrder . "'" . ');" ><i class="fa fa-pencil-square-o iconsize"></i></button></div>';
                } else if (!empty($type) && $type == 'wkoutLogFromPrev') {
                    $response .= '<div class="modal-footer"><button id="add_new_log_end" data-ajax="false" data-role="none" name="f_method" class="btn btn-default" value="add_new_log_end" type="submit">Skip</button><button id="add_new_log_start" data-ajax="false" data-role="none" name="f_method" class="btn btn-default" value="add_new_log_start" type="submit" ><i class="fa fa-pencil-square-o iconsize"></i></button></div>';
                } else {
                    $response .= '<div class="modal-footer"><button id="add_new_log_end" data-ajax="false" data-role="none" name="f_method" class="btn btn-default pointers" onclick="confirmLogDetails(' . "'" . ($goalOrder >= 0 ? $goalOrder : '2') . "'" . ');" value="add_new_log_end" type="button">Skip</button><button id="add_new_log_start" data-ajax="false" data-role="none" name="f_method" onclick="confirmLogDetails(' . "'" . ($goalOrder >= 0 ? $goalOrder : '1') . "'" . ');" class="btn btn-default" value="add_new_log_start" type="button" ><i class="fa fa-pencil-square-o iconsize"></i></button></div>';
                }
                $response .= '</form></div>
								</div>
							</div><script>$(document).ready(function (){$(".ttContent").text(document.getElementById("note_wkout_intensity").options[$("#slider-1").val()].text).focus();});function showval(val){$(".ttContent").text(document.getElementById("note_wkout_intensity").options[val].text);}$(document).ready(function(){$(".min-date").mobipick();});</script>';
            }
        } elseif (!empty($action) && trim($action) == 'shareOptions') {
            $response = '<div class="vertical-alignment-helper">
							<div class="modal-dialog">
							<div class="modal-content">
							<form id="sharewkout" data-ajax="false" action="" method="post">
							' . ($type == 'shareassign' ? '<input type="hidden" value="' . $assignId . '" id="share_assign_id" name="share_assign_id" />' : ($type == 'sharejournal' ? '<input type="hidden" value="' . $logId . '" name="share_journal_id" id="share_journal_id"/>' : '<input type="hidden" value="' . $fid . '" id="share_wkout_id" name="share_wkout_id" />')) . '
								<div class="modal-header">
									<div class="row">
										<div class="mobpadding">
											<div class="border">
												<div class="col-xs-2">
													<a data-role="none" data-ajax="false" href="javascript:void(0);" onclick="closeModelwindow(' . "'" . $modelType . "'" . ');" class="triangle">
														<i class="fa fa-chevron-left iconsize2"></i>
													</a>
												</div>
												<div class="col-xs-8 optionpoptitle">' . ($type == 'shareassign' ? 'Share Assigned Plan' : ($type == 'sharejournal' ? 'Share Journal' : 'Share Workout')) . '</div>
												<div class="col-xs-2">
													<button style="background-color:#fff" name="f_method" class="btn btn-default" data-ajax="false" type="submit" ' . (Helper_Common::is_admin() || Helper_Common::is_manager() ? 'onclick="return checkValidAdminInfo();"' : 'onclick="return checkValidInfo();"') . ' value="' . ($type == 'shareassign' ? 'share_assign' : ($type == 'sharejournal' ? 'share_journal' : 'share_wkout')) . '" data-role="none">share</button>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="modal-body">
									<div class="aligncenter">
										<div class="col-xs-12 errormsg hide" style="color:red;padding-bottom:10px;"></div>
									</div>
									<div class="form-group">
										<label class="control-label" for="workout-name">Workout:</label>';
            if (!empty($title)) {
                $response .= '<div class="row" style="margin-bottom:10px;">
												<div class="mobpadding">
												<div class="border full bootstrap-tagsinput-preview" style="height:auto">
													<div class="taginfo">
														<span class="tag label label-info">' . $title . '</span>
													</div>
												</div>
												</div>
											</div>';
            } else {
                $response .= '<input type="text" class="form-control xrtag-input-tag" name="xrtag-input" value="" placeholder="Title" data-role="tagsinput"/> ';
            }
            $response .= '</div>';
            $current_site_id = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
            $current_site_name = (Session::instance()->get('current_site_name') ? Session::instance()->get('current_site_name') : '0');
            if ((Helper_Common::is_admin() || Helper_Common::is_manager() || Helper_Common::is_trainer()) && Helper_Common::hasAccess('Share & Assign')) {
                $response .= '<div class="form-group">
											<label id="is_share_option" class="control-label" for="assignment">Schedule Assignment(s): <div class="onoffcheckbox" style="display:inline-block;"><input class="checkboxdrag" name="is_share_assing" id="is_share_assing" type="checkbox"></div></label>
											<div class="assign_group col-xs-12 hide">
												<div class="col-xs-12">
												<label class="control-label" for="sharedates">Select Date(s):</label><input type="hidden" name="sharedates" id="sharedates"/>
												<div id="sharedate"></div>
												</div>
												<div id="sharedates_text" class="form-control bootstrap-tagsinput-preview" style="min-height:30px;height:auto;"></div>
											</div>
										</div>';
            }if (Helper_Common::is_admin() || Helper_Common::is_manager()) {
                $response .= '<div class="form-group">
											<label class="control-label" for="wkout_site_names">Site(s):</label>
											<input data-ajax="false" data-role="none" type="text" class="input-md form-control" id="wkout_site_names" name="seletedSite[]" style="width: 100%;"/>
										</div>';
            }
            $response .= '<div class="form-group">
											<label class="control-label" for="wkout_user_names">Recipient(s):</label>
											<input data-ajax="false" data-role="none" type="text" class="input-md form-control" value="" id="wkout_user_names" name="seletedUser[]" style="width: 100%;"/>
										</div>';
            $response .= '<div class="form-group">
										<label class="control-label" for="message-text">Message:</label>
										<textarea id="message-text" style="resize: none;height:50px;" name="share_msg" class="form-control"></textarea>
									</div>
								</div>
								<div class="modal-footer">
								   <button data-role="none" data-ajax="false" type="button"  class="btn btn-default" onclick="closeModelwindow(' . "'" . $modelType . "'" . ');">close</button>
								   <button style="background-color:#fff" name="f_method" class="btn btn-default" data-ajax="false" type="submit" ' . (Helper_Common::is_admin() || Helper_Common::is_manager() ? 'onclick="return checkValidAdminInfo();"' : 'onclick="return checkValidInfo();"') . ' value="' . ($type == 'shareassign' ? 'share_assign' : ($type == 'sharejournal' ? 'share_journal' : 'share_wkout')) . '" data-role="none">share</button>
								</div>
								</form>
							</div>
						</div>
					</div><script>$(document).ready(function(){ $("input.checkboxdrag").bootstrapSwitch("size", "small");$("input.checkboxdrag").bootstrapSwitch("onText", " ");$("input.checkboxdrag").bootstrapSwitch("offText", " ");$("input.checkboxdrag").on("switchChange.bootstrapSwitch",function (event, state) {if(state === true){$("div.assign_group").removeClass("hide");}else { $("div.assign_group").addClass("hide");$("label#is_share_option").css("color","#666666"); }});$("div#sharedate").multiDatesPicker({dateFormat: "dd M yy",dayNamesMin: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"], onSelect: function(datestring, dp) {var selecteddate = $("div#sharedate").multiDatesPicker("getDates"); $("#sharedates").val(selecteddate); var seltext = convertText(selecteddate);
					$("div#sharedates_text").html(seltext); }});$("input#wkout_user_names").select2({placeholder: "Search Users",minimumInputLength: 2,multiple:true,
					ajax: {url: "' . URL::base() . 'search/getajax/",data:function(term, page){return{title:term,siteids:$("input#wkout_site_names").val(),maxRows: 5,action : "getusers"};},results: function (data, page) {return { results: data };}}});$("input#wkout_site_names").select2({placeholder: "Search Sites",minimumInputLength: 2,multiple:true,
					ajax: {url: "' . URL::base() . 'search/getajax/",data:function(term, page) {return {title:term,siteids:$("input#wkout_site_names").val(),maxRows: 5,action : "getsites"};},results: function (data, page) {return { results: data };}}}).select2("data",[{"id":"' . $current_site_id . '","text":"' . $current_site_name . '"}]);});</script>';
        } else if (!empty($action) && trim($action) == 'relatedRecords') {
            $RelatedExercises = $sequenceExercises = $exerciseRecord = array();
            $start = 0;
            $limit = 10;
            if ($method == 'relatedRecords') {
                $exerciseRecord = $workoutModel->relatedExcersise($xrId);
                $RelatedExercises = $workoutModel->getRelatedExercises($xrId, $exerciseRecord['musprim_id'], $exerciseRecord['type_id'], $start, $limit);
                $title = 'Related Excercise Records';
            } elseif ($method == 'sequenceRecords') {
                $sequenceExercises = $workoutModel->getSequencesByUnitId($fid, $start, $limit);
                $title = 'Sequence Instruction';
            } elseif ($method == "previewimage") {
                $exerciseRecord_title = array();
                if (($addOptions == 'add' || $addOptions == 'addForPage') && !empty($fid) && is_numeric($fid))
                    $exerciseRecord_title = $workoutModel->getExerciseById($fid);
                $exerciseRecords = $workoutModel->getSequenceImages($fid);
                $getRelatedImages = array();
                foreach ($exerciseRecords as $keys => $values) {
                    if (!empty($values['img_url']) && file_exists($values['img_url'])) {
                        $getRelatedImages[$values['seq_order']]['img_url'] = URL::base() . $values['img_url'];
                        $getRelatedImages[$values['seq_order']]['seq_desc'] = ucfirst($values['seq_desc']);
                    }
                }
                if (empty($getRelatedImages)) {
                    $exerciseRecord = $workoutModel->relatedExcersise($fid);
                    if (!empty($fid) && isset($exerciseRecord['img_url']) && !empty($exerciseRecord['img_url']) && file_exists($exerciseRecord['img_url']))
                        $getRelatedImages[]['img_url'] = URL::base() . $exerciseRecord['img_url'];
                }
                $title = 'Exercise Record Image : Preview';
            }elseif ($method == "previewimageSeq") {
                $exerciseRecord = $workoutModel->getSequenceImage($fid, $foldid);
                $title = 'Exercise Record Sequence Image : Preview';
            } elseif ($method == 'tagRecord') {
                if (!empty($fid) && is_numeric($fid))
                    $exerciseRecord = $workoutModel->getExerciseById($fid);
                $tagRecord = $workoutModel->getUnitTagsById($fid);
                $title = 'Exercise Record Tag' . ($editFlag ? ' : <span class="activedatacol">' . $exerciseRecord['title'] . '</span>' : ' : Preview');
            }elseif ($method == 'xrrate') {
                $rateFlag = $workoutModel->isUserRatedbyUnitId($fid, $user->pk());
                $title = 'Exercise Record : Rating';
            }
            $response = '<div id="' . $method . '" class="vertical-alignment-helper">
							<div class="modal-dialog bs-example-modal-sm">
								<div class="modal-content"><form data-ajax="false" action="" method="post" id="' . $method . '">
									<div class="modal-header">';
            $response .= '<input type="hidden" value="' . $fid . '" name="unit_id" />';
            $response .= '<div class="row">
											<div class="col-sm-12 mobpadding">
												<div class="border">
													<div class="col-xs-2">
														<a data-role="none" data-ajax="false" data-ajax="false" data-role="none" href="javascript:void(0);" onclick="closeModelwindow(' . "'" . $modelType . "'" . ');" class="triangle">
															<i class="fa fa-chevron-left iconsize2"></i>
														</a>
													</div>
													<div class="col-xs-8 optionpoptitle">' . $title . '</div>
													<div class="col-xs-2">';
            if ($method == 'xrrate') {
                if ($addOptions == 'btn-default')
                    $response .= '<button style="background-color:#fff" class="btn" type="button" onclick="triggerXrRatingInsert();" data-ajax="false" data-role="none"><i style="font-size:30px;" class="fa fa-check-square-o"></i></button>';
                elseif ($rateFlag)
                    $response .= '<button style="background-color:#fff" name="f_method" class="btn" data-ajax="false" type="submit" value="add_rating" data-role="none"><i style="font-size:30px;" class="fa fa-check-square-o"></i></button>';
                else
                    $response .= '<button class="close" type="button" type="button" onclick="closeModelwindow(' . "'" . $modelType . "'" . ');" data-role="none" data-ajax="false">&times;</button>';
            }
            $response .= '</div>
												</div>';
            $response .= '</div>
										</div>';
            if (!empty($addOptions) && ($addOptions == 'add' || $addOptions == 'addForPage')) {
                $response .= '<hr>
											<div class="row"><div class="popup-title"><div class="col-xs-12">' . (!empty($exerciseRecord_title['title']) ? '<span class="inactivedatacol break-xr-name" style="font-size: .9em;">' . $exerciseRecord_title['title'] . '</span>' : '') . '</div></div></div>';
            }
            $response .= '</div>
									<div class="modal-body opt-body" id="relatedexc" data-id="' . $editFlag . '">';
            if ($method == 'relatedRecords') {
                if (count($RelatedExercises) > 0) {
                    $start = $limit;
                    foreach ($RelatedExercises as $key => $value) {
                        $response .= '<div data-text="' . ($value['default_status'] == 0 ? 'My Exercise' : ($value['default_status'] == 1 ? 'Default Exercise' : ($value['default_status'] == 2 ? 'Sample Exercise' : '' ))) . '" ' . ($key == '9' ? 'id="view_more" data-order="' . $goalOrder . '" data-start="' . $start . '" data-limit="' . $limit . '" data-xrid="' . $xrId . '" data-oldxrid="' . $fid . '"' : '') . ' class="row itemxr"><div class="mobpadding"><div class="border full">';
                        $response .= '<div class="col-xs-3" onclick="return false">';
                        if (!empty($xrId) && isset($value['img_url']) && !empty($value['img_url']) && file_exists($value['img_url'])) {
                            $response .= '<img width="60px;" id="exerciselibimg" class="img-thumbnail" style="cursor:pointer;';
                            $response .= '" title="' . $value['img_title'] . '" src="' . URL::base() . $value['img_url'] . '"';
                            $response .= '/>';
                        } else {
                            $response .= '<i style="font-size:50px;" class="fa fa-file-image-o datacol"></i>';
                        }
                        $response .= '</div><div class="col-xs-7 break-xr-name" style="border-right:1px solid #eee;padding-left:0px;"><b>' . $value["title"] . '</b><div class="item-info">' . ($value['default_status'] == 0 ? 'from My Records' : ($value['default_status'] == 1 ? (Helper_Common::is_admin() || Helper_Common::is_manager() ? 'from Default Records' : 'from Sample Records') : ($value['default_status'] == 2 ? 'from Sample Records' : ($value['default_status'] == 3 ? 'from Shared Records' : '' )))) . '</div></div><div class="col-xs-2 aligncenter"><a data-role="none" data-ajax="false" ' . ($editFlag ? 'onclick="insertFromRelatedToXrSet(' . "'" . $fid . "','" . $value['unit_id'] . "','" . $goalOrder . "'" . ');"' : 'onclick="return false;"') . ' href="javascript:void(0);"><i class="fa fa-sign-in iconsize ' . ($editFlag ? '' : 'datacol') . '"></i></a></div></div></div><input type="hidden" value="' . (!empty($value['img_url']) ? URL::base() . $value['img_url'] : '') . '" name="popup_hidden_exerciseset_img' . $value['unit_id'] . '" id="popup_hidden_exerciseset_image_opt' . $value['unit_id'] . '"/><input type="hidden" value="' . $value['title'] . '" name="popup_hidden_exerciseset_title' . $value['unit_id'] . '" id="popup_hidden_exerciseset_title_opt' . $value['unit_id'] . '"/></div>';
                    }
                } else {
                    $response .= '<div  class="row">
														<div class="mobpadding">
															<div class="border full">
																<div class="col-xs-12">
																	<center>
																		No Records Found!!!
																	</center>
																</div>
															</div>
														</div>
													</div>';
                }
                if (count($RelatedExercises) == '10')
                    $response .= '<script>var sendAjax = true;$(".modal-body").bind("scroll", function(e) {if ($("div#view_more").length) {var xrid = $("div#view_more").attr("data-xrid");var order = $("div#view_more").attr("data-order");var start = $("div#view_more").attr("data-start");var limit = $("div#view_more").attr("data-limit");var oldxrid = $("div#view_more").attr("data-oldxrid");if (isMobile) {if ($(this).scrollTop() + $(this).innerHeight() == $(this)[0].scrollHeight) {if ($("#view_more").is(":visible")) getRelatedRecordsMore(xrid, oldxrid, order, start, limit, e);}}else{var scrollTop = Math.round($(this).scrollTop());var scrollHeight = $(this)[0].scrollHeight;if(sendAjax){if(scrollTop + $(this).innerHeight() == scrollHeight || scrollTop + $(this).innerHeight() == scrollHeight - 1 || scrollTop + $(this).innerHeight() == scrollHeight + 1){if ($("#view_more").is(":visible")){sendAjax = false;setTimeout(function() {e.preventDefault();if(e.handled !== true) {e.handled = true;getRelatedRecordsMore(xrid, oldxrid, order, start, limit, e);sendAjax = true;}}, 200);}}}}}});if(getBrowserZoomLevel() < 100){ relatedAutoShowMore(); }</script>';
            }elseif ($method == 'sequenceRecords') {
                if (count($sequenceExercises) > 0) {
                    foreach ($sequenceExercises as $key => $value) {
                        $seqOrder = $key + 1;
                        $response .= '<div class="row" style="margin-bottom:10px;">
													<div class="mobpadding">
														<label for="workout-name" class="control-label">Sequence ' . $seqOrder . '</label>
														<div class="border full">
															<div ' . ((!empty($fid) && isset($value['img_url']) && !empty($value['img_url']) && file_exists($value['img_url'])) ? 'onclick="getXrSeqImgPreview(' . "'" . $fid . "','" . $value['seq_id'] . "'" . ');"' : '') . ' class="col-xs-3" style="border-right:1px solid #ddd;border-radius:0px">';
                        if (!empty($fid) && isset($value['img_url']) && !empty($value['img_url']) && file_exists($value['img_url'])) {
                            $response .= '<img width="70px;" id="exerciselibimg" class="img-thumbnail" style="cursor:pointer;';
                            $response .= '" title="' . $value['img_title'] . '" src="' . URL::base() . $value['img_url'] . '"';
                            $response .= '/>';
                        } else {
                            $response .= '<i class="fa fa-file-image-o datacol"></i>';
                        }
                        $response .= '</div>
															<div class="col-xs-9">' . $value['seq_desc'] . '</div>
														</div>
													</div>
												</div>';
                    }
                    $start = $limit;
                    if (count($sequenceExercises) == '10')
                        $response .= '<div id="view_more" class="row">
															<div class="mobpadding">
																<div class="border full">
																	<div class="col-xs-12">
																		<center>
																			<a class="pointers showmore-text activedatacol" data-role="none" data-ajax="false" onclick="openSequencePopupMore(' . $fid . ',' . $start . ',' . $limit . ')"><i class="fa fa-chevron-down"></i>&nbsp;Show More Results</a>
																		</center>
																	</div>
																</div>
															</div>
														</div>';
                }else {
                    $response .= '<div class="row">
														<div class="mobpadding">
															<div class="border full">
																<div class="col-xs-12">
																	<center>
																		No Records Found!!!
																	</center>
																</div>
															</div>
														</div>
													</div>';
                }
                $start = $limit;
                if (count($RelatedExercises) == '10')
                    $response .= '<div id="view_more" class="row">
														<div class="mobpadding">
															<div class="border full">
																<div class="col-xs-12">
																	<center>
																		<a class="pointers showmore-text activedatacol" data-role="none" data-ajax="false" onclick="getSeqRecordsMore(' . $fid . ',' . $start . ',' . $limit . ')"><i class="fa fa-chevron-down"></i>$nbsp;Show More Results</a>
																	</center>
																</div>
															</div>
														</div>
													</div>';
            }else if ($method == 'previewimage') {
                if (isset($getRelatedImages) && count($getRelatedImages) > 0) {
                    $response .= '<div id="xrimgCarousel" class="opt-row-detail carousel slide carousel-fade aligncenter"><div class="carousel-inner" id="carouselbody">';
                    $flag = true;
                    foreach ($getRelatedImages as $keys => $values) {
                        $response .= '<div class="item ' . ($flag ? 'active' : '') . '">';
                        $response .= '<img onload="loadimage(this);" src="' . $values['img_url'] . '" alt="' . (!empty($keys) ? 'Sequence ' . $keys : 'Feature Image') . '" class="img-responsive Preview_image slide-img"><div class="slide-title">' . (isset($values['seq_desc']) ? 'Sequence ' . $keys . (!empty($values['seq_desc']) ? '</div><div>' . $values['seq_desc'] . '</div>' : '</div>') : 'Feature Image</div>');
                        $response .= '</div>';
                        $flag = false;
                    }
                    if (count($getRelatedImages) > 1)
                        $response .= '</div><a class="left carousel-control" href="#xrimgCarousel" data-slide="prev"><i class="fa fa-chevron-left fa-4"></i></a><a class="right carousel-control" href="#xrimgCarousel" data-slide="next"><i class="fa fa-chevron-right fa-4"></i></a></div>';
                    else
                        $response .= '</div></div>';
                }else {
                    $response .= '<div class="opt-row-detail">';
                    $response .= '<div class="aligncenter"><i id="preview-featimg" class="fa fa-file-image-o datacol" style="font-size:150px;"></i></div>';
                    $response .= '</div>';
                }
                if (!empty($addOptions) && $addOptions == 'add') {
                    $response .= '<div class="opt-row-detail">
												<button class="btn btn-default" onclick="closeModelwindow(' . "'myOptionsModalExerciseRecord'" . '); getajaxExercisepreviewOfDay(' . $fid . ',0);" type="button" style="width:100%" data-ajax="false" data-role="none">
													<div class="col-xs-12 pointer">
														<div class="col-xs-2"><i class="fa fa-eye iconsize"></i></div>
														<div class="col-xs-10">' . __('View this Exercise Record') . '</div>
													</div>
												</button>
											</div>
											<div class="opt-row-detail">
												<button class="btn btn-default" id="btn_insert_xrrecord" onclick="insertRecordToParent(this);closeModelwindow(' . "'myOptionsModalExerciseRecord'" . ');" type="button" style="width:100%" data-ajax="false" data-role="none">
													<div class="col-xs-12 pointer">
														<div class="col-xs-2"><i class="fa fa-sign-in iconsize"></i></div>
														<div class="col-xs-10">' . __('Insert into this Exercise Set') . '</div>
													</div>
												</button>
											</div>
											<div class="opt-row-detail">
												<button class="btn btn-default" onclick="triggerXrTagModal();" type="button" style="width:100%" data-ajax="false" data-role="none">
													<div class="col-xs-12 pointer">
														<div class="col-xs-2"><i class="fa fa-tag iconsize"></i></div>
														<div class="col-xs-10">' . __('Tag this Exercise Record') . '</div>
													</div>
												</button>
											</div>
											<div class="opt-row-detail">
												<button class="btn btn-default" onclick="return getRateForXrModalFromUser(' . "'" . $fid . "'" . ');" type="button" style="width:100%" data-ajax="false" data-role="none">
													<div class="col-xs-12 pointer">
														<div class="col-xs-2"><i class="fa fa-star-o iconsize"></i></div>
														<div class="col-xs-10">' . __('Rate this Exercise Record') . '</div>
													</div>
												</a>
											</div>';
                    if (Helper_Common::is_admin() || Helper_Common::is_manager() || Helper_Common::is_trainer()) {
                        $response .= '<div class="opt-row-detail">
													<button class="btn btn-default" onclick="triggerShareExerciseModal(' . "'" . $fid . "'" . ', ' . "'" . $exerciseRecord_title['title'] . "'" . ');" type="button" style="width:100%" data-ajax="false" data-role="none">
														<div class="col-xs-12 pointer">
															<div class="col-xs-2"><i class="fa fa-share-alt iconsize"></i></div>
															<div class="col-xs-10">' . __('Share this Exercise Record') . '</div>
														</div>
													</a>
												</div>';
                    }
                } else if (!empty($addOptions) && $addOptions == 'addForPage') {
                    $userId = Auth::instance()->get_user()->pk();
                    $response .= '<input type="hidden" id="submitfrom" name="submitfrom" value="' . $foldid . '"/>
											<input type="hidden" id="xrid" name="xrid" value="' . $fid . '"/>
											<div class="opt-row-detail">
												<button class="btn btn-default" onclick="getXRcisepreviewOfDay(' . $fid . ', 0);" type="button" style="width:100%" data-ajax="false" data-role="none">
													<div class="col-xs-12 pointer">
														<div class="col-xs-2"><i class="fa fa-eye iconsize"></i></div>
														<div class="col-xs-10">' . __('View this Exercise Record') . '</div>
													</div>
												</button>
											</div>';
                    if (!empty($showOptions) && $showOptions == 'hideopt') {
                        $response .= '<div class="opt-row-detail more-option" style="display:block;">
													<button class="btn btn-default" onclick="showmoreXrImageAndRecordOpt();" type="button" style="width:100%" data-ajax="false" data-role="none">
														<div class="col-xs-12 pointer">
															<div class="col-xs-2"><i class="fa fa-ellipsis-h iconsize"></i></div>
															<div class="col-xs-10">' . __('More Options') . '</div>
														</div>
													</button>
												</div>
												<div class="more-option" style="display:none;">';
                    }
                    if (Helper_Common::is_admin() || ($userId == $exerciseRecord_title['created_by'] && ($foldid == '0' || $foldid == '3')) || (Helper_Common::is_manager() && $foldid != '1')) {
                        $response .= '<div class="opt-row-detail">
													<a href="' . URL::base(TRUE) . 'exercise/exerciserecord/' . $fid . '?act=lib" class="btn btn-default" onclick="closeModelwindow(' . "'xrciselibact-modal'" . ');" type="button" style="width:100%" data-ajax="false" data-role="none">
														<div class="col-xs-12 pointer">
															<div class="col-xs-2"><i class="fa fa-pencil-square-o iconsize"></i></div>
															<div class="col-xs-10">' . __('Edit this Exercise Record') . '<div class="inactivedatacol item-info">' . __('Modify this Record') . '</div></div>
														</div>
													</a>
												</div>';
                    }
                    $response .= '<div class="opt-row-detail">
												<button class="btn btn-default" name="f_method" onclick="return triggerduplicateRecord();" type="submit" value="copy" style="width:100%" data-ajax="false" data-role="none">
													<div class="col-xs-12 pointer">
														<div class="col-xs-2"><i class="fa fa-files-o iconsize"></i></div>
														<div class="col-xs-10">' . __('Duplicate this Exercise Record') . '<div class="inactivedatacol item-info">' . __('Copy to My Exercise Records') . '</div></div>
													</div>
												</button>
											</div>
											<div class="opt-row-detail">
												<button class="btn btn-default" onclick="triggerXrTagModal();" type="button" style="width:100%" data-ajax="false" data-role="none">
													<div class="col-xs-12 pointer">
														<div class="col-xs-2"><i class="fa fa-tag iconsize"></i></div>
														<div class="col-xs-10">' . __('Tag this Exercise Record') . '<div class="inactivedatacol item-info">' . __('Text tags for advanced filtering') . '</div></div>
													</div>
												</button>
											</div>
											<div class="opt-row-detail">
												<button class="btn btn-default" onclick="return getRateForXrModalFromUser(' . "'" . $fid . "'" . ');" type="button" style="width:100%" data-ajax="false" data-role="none">
													<div class="col-xs-12 pointer">
														<div class="col-xs-2"><i class="fa fa-star-o iconsize"></i></div>
														<div class="col-xs-10">' . __('Rate this Exercise Record') . '<div class="inactivedatacol item-info">' . __('Score and provide feedback') . '</div></div>
													</div>
												</a>
											</div>';
                    if (Helper_Common::is_admin() || Helper_Common::is_manager() || Helper_Common::is_trainer()) {
                        $response .= '<div class="opt-row-detail">
													<button class="btn btn-default" onclick="triggerShareExerciseModal(' . "'" . $fid . "'" . ', ' . "'" . $exerciseRecord_title['title'] . "'" . ');" type="button" style="width:100%" data-ajax="false" data-role="none">
														<div class="col-xs-12 pointer">
															<div class="col-xs-2"><i class="fa fa-share-alt iconsize"></i></div>
															<div class="col-xs-10">' . __('Share this Exercise Record') . '</div>
														</div>
													</a>
												</div>';
                    }
                    if (Helper_Common::is_admin() || ($userId == $exerciseRecord_title['created_by'] && ($foldid == '0' || $foldid == '3')) || (Helper_Common::is_manager() && $foldid == '2')) {
                        $response .= '<div class="opt-row-detail">
													<button class="btn btn-default" name="f_method" onclick="return triggerdeleteRecord();" type="submit" value="delete" style="width:100%" data-ajax="false" data-role="none">
														<div class="col-xs-12 pointer">
															<div class="col-xs-2"><i class="fa fa-times iconsize"></i></div>
															<div class="col-xs-10">' . __('Remove this Exercise Record') . '</div>
														</div>
													</button>
												</div>';
                    }
                    if (Helper_Common::hasAccess('Create Exercise')) {
                        $response .= '<div class="opt-row-detail hide">
													<a href="' . URL::base(TRUE) . 'exercise/exerciserecord?act=lib" class="btn btn-default" onclick="closeModelwindow(' . "'xrciselibact-modal'" . ');" type="button" style="width:100%" data-ajax="false" data-role="none">
														<div class="col-xs-12 pointer">
															<div class="col-xs-2"><i class="fa fa-plus-square-o iconsize"></i></div>
															<div class="col-xs-10">' . __('Add a New Exercise') . '</div>
														</div>
													</a>
												</div>';
                    }
                    if (!empty($showOptions) && $showOptions == 'hideopt') {
                        $response .= '</div>';
                    }
                }
            } else if ($method == 'previewimageSeq') {
                $response .= '<div class="opt-row-detail">';
                if (!empty($fid) && isset($exerciseRecord['img_url']) && !empty($exerciseRecord['img_url']) && file_exists($exerciseRecord['img_url'])) {
                    $response .= '<div class="aligncenter"><img src="' . URL::base() . $exerciseRecord['img_url'] . '" id="preview-featimg" class="Preview_image" alt="' . ucfirst($exerciseRecord['img_title']) . '"></div>';
                } else {
                    $response .= '<div class="aligncenter"><i id="preview-featimg" class="fa fa-file-image-o datacol" style="font-size:150px;"></i></div>';
                }
                $response .= '</div>';
            } else if ($method == 'tagRecord') {
                if ($editFlag) {
                    $tagname = '';
                    if (count($tagRecord) > 0) {
                        foreach ($tagRecord as $key => $value) {
                            $tagname .= ucfirst($value['tag_title']) . ',';
                        }
                    }
                    $response .= '<div class="opt-row-detail">
												<div class="row">
													<div class="col-xs-12"><label class="control-label">Tags:</label></div>
													<div class="col-xs-12">
														<input type="text" id="tag_input" class="form-control xrtag-input-tag" autocomplete="off" spellcheck="false" dir="auto"  name="xrtag-input" value="' . substr($tagname, 0, -1) . '" data-role="tagsinput"/>
													</div>
												</div>
											</div>
										</div>
										<div class="modal-footer">
											<input type="hidden" id="unit_id" name="unit_id" value="' . $fid . '" />
											<button type="submit" class="btn btn-default pull-right activedatacol blueicon" name="f_method" value="add_tag_to_xr">Insert</button>
											<button type="button" class="btn btn-default pull-right" data-dismiss="modal" style="margin-right: 20px;">close</button>
											<script>var tagarry=[];$.ajax({url: siteUrl+"ajax/tagnames",dataType : "json",async: false,	encode: true,cache: false}).done(function (data) {var taglist=[];if(data){$.each(data.tagnames,function(i, val){taglist.push({id:i, val:val});});tagarry = taglist;}});var tagnames = new Bloodhound({datumTokenizer: Bloodhound.tokenizers.obj.whitespace("name"),queryTokenizer: Bloodhound.tokenizers.whitespace,local: $.map(tagarry, function (tagname) {return {id : tagname.id,name: tagname.val};})});tagnames.initialize();$("input.xrtag-input-tag").tagsinput({typeaheadjs: [{highlight: true,},{name: "tagnames",displayKey: "name",valueKey: "name",source: tagnames.ttAdapter()}],freeInput: true});$("input.xrtag-input-tag").tagsinput("input").blur(function() { $("input.xrtag-input-tag").tagsinput("add", $(this).val());$(this).val("");});</script>';
                } else {
                    if (count($tagRecord) > 0) {
                        $tagname = '';
                        foreach ($tagRecord as $key => $value) {
                            $tagname .= '<div class="taginfo"><span class="tag label label-info">' . ucfirst($value['tag_title']) . '</span></div>';
                        }
                        $response .= '<div class="row" style="margin-bottom:10px;">
														<div class="mobpadding">
															<div class="border full bootstrap-tagsinput-preview">' . $tagname . '
															</div>
														</div>
													</div>';
                    } else {
                        $response .= '<div class="row">
															<div class="mobpadding">
																<div class="border full">
																	<div class="col-xs-12">
																		<center>
																			No Records Found!!!
																		</center>
																	</div>
																</div>
															</div>
														</div>';
                    }
                }
            } elseif ($method == 'xrrate') {
                if ($rateFlag) {
                    $response .= '<div class="row" style="margin-bottom:10px;">
												<div class="mobpadding">
													<div class="border full">
													<div class="col-xs-3 borderright">Rating</div>';
                    $response .= '<div class="col-xs-8 drop down selectToUISlider"><div class="sliderview"><a href="javascript:void(0);" tabindex="0" id="handle_speed" class="ui-slider-handle ui-state-default ui-corner-all ui-state-hover" role="slider" aria-labelledby="label_handle_speed" aria-valuemin="0" style="left: 25%;"><span class="ui-slider-tooltip ui-widget-content ui-corner-all"><span class="ttContent"></span></span></a></div><input oninput="showval(this.value);" onchange="showval(this.value);" type="range" name="slider-1" id="slider-1" value="0" min="0.0" max="10" data-popup-enabled="true" step="0.5"/>';

                    $response .= '</div><script>$(document).ready(function (){$(".ttContent").text($("#slider-1").val());});function showval(val){$(".ttContent").text($("#slider-1").val());}</script></div></div>
											<div class="mobpadding">
												<div class="border full">
													<div class="col-xs-3 borderright">Comments</div>
													<div class="col-xs-8"><textarea class="form-control" style="resize:none" name="rating_msg"></textarea></div>
												</div>
											</div>
											</div>';
                } else {
                    $response .= '<div class="row" style="margin-bottom:10px;">
												<div class="mobpadding">
													<div class="border full">
													<div class="col-xs-12 aligncenter">Already you are Rated for this Exercise</div>
													</div>
												</div>
											</div>';
                }
            }
            $response .= '</div>' . ($method != 'tagRecord' ? '<div class="modal-footer"><button data-role="none" data-ajax="false" type="button" class="btn btn-default" data-dismiss="modal">close</button></div>' : '') . '
								</div></form>
							</div>
						</div></div>					
					<div id="myOptionsModalExerciseRecord_more" class="modal fade bs-example-modal-sm" role="dialog" tabindex="-1"></div>
					<div id="myOptionsModalExerciseRecord_option" class="modal fade bs-example-modal-sm" role="dialog" tabindex="-1"></div>';
        } elseif (!empty($action) && trim($action) == 'relatedActionOptions') {
            $exerciseRecord = $workoutModel->relatedExcersise($foldid);
            $response = '<div class="vertical-alignment-helper">
							<div class="modal-dialog modal-md">
								<div class="modal-content aligncenter">
									<form data-ajax="false" action="" method="post" id="fromRelatedRecords">
									<div class="modal-header" style="border-bottom:0">
										<div class="row">
											<div class="mobpadding">
												<div class="border">
													<div class="col-xs-2">
														<a href="javascript:void(0);" onclick="closeModelwindow(' . "'" . $modelType . "'" . ');" class="triangle"><i class="fa fa-chevron-left"></i>
														</a>
													</div>
													<div class="col-xs-8 optionpoptitle">Options for this Related Exercise</div>
													<div class="col-xs-2"></div>
												</div>
											</div>
										</div>
									</div>
									<div class="modal-body opt-body">
										<div class="opt-row-detail">
											<a href="javascript:void(0);" style="width:100%"  class="btn btn-default" ' . (empty($fid) ? 'onclick="insertFromRelatedToParent();"' : 'onclick="insertFromRelatedToXrSet();"') . '>
												<div class="col-xs-12 pointer">
													<div class="col-xs-3"><i class="fa fa-sign-in iconsize"></i></div>
													<div class="col-xs-9">Replace with this Exercise</div>
												</div>
											</a>
										</div>
									</div>
									<input type="hidden" value="' . $exerciseRecord['unit_id'] . '" name="popup_hidden_exerciseset" id="popup_hidden_exerciseset_opt"/>
									<input type="hidden" value="' . (!empty($exerciseRecord['img_url']) ? URL::base() . $exerciseRecord['img_url'] : '') . '" name="popup_hidden_exerciseset_img" id="popup_hidden_exerciseset_image_opt"/>
									<input type="hidden" value="' . $exerciseRecord['title'] . '" name="popup_hidden_exerciseset_title" id="popup_hidden_exerciseset_title_opt"/>
									<input type="hidden" value="' . $fid . '" name="popup_hidden_exercisesetold" id="popup_hidden_exercisesetold_opt"/>
									</form>
								</div>
							</div>
						</div>';
        } elseif (!empty($action) && trim($action) == 'exportActionOptions') {
            $exerciseRecord = $workoutModel->relatedExcersise($fid);
            $response = '<div class="vertical-alignment-helper">
							<div class="modal-dialog modal-md">
								<div class="modal-content aligncenter">
									<form data-ajax="false" action="" method="post" id="exportActionOptions">
									<div class="modal-header" style="border-bottom:0">
										<div class="row">
											<div class="mobpadding">
												<div class="border">
													<div class="col-xs-2">
														<a href="javascript:void(0);" onclick="closeModelwindow(' . "'" . $modelType . "'" . ');" class="triangle"><i class="fa fa-chevron-left"></i>
														</a>
													</div>
													<div class="col-xs-8 optionpoptitle">Options for this ' . ($type == 'logged' ? 'Journal' : ($type == 'shared' ? 'Shared' : ($type == 'sample' ? 'Sample' : ($type == 'assigned' ? 'Assigned' : '')))) . ' Workout Plan Export</div>
													<div class="col-xs-2"></div>
												</div>
											</div>
										</div>
									</div>
									<div class="modal-body opt-body">
										<div class="row opt-row-detail">
											<div class="col-xs-12">
												<a href="javascript:void(0);" style="width:100%"  class="btn btn-default" onclick="return sendByEmail(this);" data-exportid="' . $fid . '" data-exporttype="' . $type . '"  data-flagtype="byemail">
													<div class="col-xs-2"><i class="fa fa-envelope-o iconsize"></i></div>
													<div class="col-xs-8">By Email</div>
												</a>
											</div>
										</div>
										<div class="row opt-row-detail">
											<div class="col-xs-12">
												<a href="' . URL::base(TRUE) . 'export/pdfsharegenerator/?workouttype=' . $type . '&idexport=' . $fid . ($fromAdmin ? '&fromAdmin=1' : '') . '" target="_blank" onclick="closeModelwindow(' . "'" . $modelType . "'" . ');"  data-exportid="' . $fid . '" data-exporttype="' . $type . '"  data-flagtype="bypdf" style="width:100%" class="btn btn-default">
													<div class="col-xs-2"><i class="fa fa-file-pdf-o iconsize"></i></div>
													<div class="col-xs-8">By PDF</div>
												</a>
											</div>
										</div>
										<div class="row opt-row-detail">
											<div class="col-xs-12">
												<a href="' . URL::base(TRUE) . 'exercise/printshare/?workouttype=' . $type . '&idexport=' . $fid . ($fromAdmin ? '&fromAdmin=1' : '') . '" target="_blank" onclick="closeModelwindow(' . "'" . $modelType . "'" . ');"   data-exportid="' . $fid . '" data-exporttype="' . $type . '"  data-flagtype="byprint" style="width:100%"  class="btn btn-default">
													<div class="col-xs-2"><i class="fa fa-print iconsize"></i></div>
													<div class="col-xs-8">By Print</div>
												</a>
											</div>
										</div>
									</div>
									</form>
								</div>
							</div>
						</div>';
        } else if (!empty($action) && trim($action) == 'staticpages') {
            $staticCmsContent = '';
            if ($method == 'help-cms') {
                $staticCmsContent = $staticcmsModel->getPageContent('faq-help', $site_id);
                $title = 'FAQ / HELP';
            }
            $response = '<div class="vertical-alignment-helper">
						<div class="modal-dialog bs-example-modal-sm">
							<div class="modal-content">
								<div class="modal-header">';
            $response .= '<div class="row">
											<div class="mobpadding">
												<div class="border">
													<div class="col-xs-2">
														<a data-role="none" data-ajax="false" data-ajax="false" data-role="none" href="javascript:void(0);" onclick="closeModelwindow(' . "'" . $modelType . "'" . ');" class="triangle">
															<i class="fa fa-chevron-left iconsize2"></i>
														</a>
													</div>
													<div class="col-xs-8 optionpoptitle tour-step tour-step-four">' . $title . '</div>
													<div class="col-xs-2"></div>
												</div>';
            $response .= '</div>
										</div>
									</div>
									<div class="modal-body opt-body">
										<div class="row" style="margin-bottom:10px;">
											<div class="mobpadding">
												<div class="full">
												<div class="col-xs-12">' . $staticCmsContent . '</div>
												</div>
											</div>
										</div>';
            $response .= '</div>
									<div class="modal-footer">
									   <button data-role="none" data-ajax="false" type="button" class="btn btn-default" onclick="closeModelwindow(' . "'topheaderpopup'" . ');">close</button>
									</div>
								</div>
							</div></div>';
        } elseif (!empty($action) && trim($action) == 'datepickermodal') {
            $response = '<div class="datepickermodal vertical-alignment-helper">
				<div class="modal-dialog">
					<div class="modal-content">
						<form action="" method="post" id="datepickermodal" data-ajax="false">
							<div class="modal-header">
								<div class="row">
									<div class="mobpadding">
										<div class="border">
											<div class="col-xs-2">
												<a href="javascript:void(0)" data-dismiss="modal" class="triangle second-col" data-role="none" data-ajax="false"><i class="fa fa-chevron-left iconsize2"></i></a>
											</div>
											<div class="col-xs-8 optionpoptitle">Select the Date</div>
											<div class="col-xs-2">
												<button type="button" class="btn btn-default activedatacol" onclick="changeCalenderDateByPicker();" style="background-color:#fff" data-ajax="false" data-role="none">ok</button>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="modal-body opt-body">
								<div class="opt-row-detail second-col">
									<div class="aligncenter">Selected Date:</div>
									<div class="mobipick-main" style="display:flex;"></div>
									<input type="hidden" id="date_chosen" value="' . date("d M Y", strtotime($date)) . '" class="min-date-hidden"/>
								</div>
								<div class="opt-row-detail second-col text-center">
									<button type="button" class="btn btn-default activedatacol" data-ajax="false" data-role="none" onclick="changeCalenderDateToday();">today</button>
								</div>
						  	</div>
						  	<div class="modal-footer">
						  		<button type="button" class="btn btn-default" data-dismiss="modal" data-ajax="false" data-role="none">cancel</button>
								<button type="button" class="btn btn-default activedatacol" onclick="changeCalenderDateByPicker();"  style="background-color:#fff"data-ajax="false" data-role="none">ok</button>
							</div>
						</form>
					</div>
				</div>
			</div><script type="text/javascript">$(document).ready(function(){ if($(".min-date-hidden")){$(".min-date-hidden").mobipick().click();}});</script>';
        } elseif (!empty($action) && trim($action) == 'confirmAssignDate') {
            if ($method == 'dateofbirth') {
                $from = new DateTime(date('Y-m-d', strtotime($date)));
                $today = new DateTime(Helper_Common::get_default_date());
                $difference = $from->diff($today)->y;
                $datetype = (!empty($type) ? $type : '');
            } else {
                $current = strtotime(Helper_Common::get_default_date());
                $datediff = strtotime($date) - $current;
                $difference = floor($datediff / (60 * 60 * 24));
                if ($difference < 0) {
                    $date = Helper_Common::get_default_date();
                    if (!empty($assignId) || !empty($logId)) {
                        $datediff = strtotime($date) - $current;
                        $difference = floor($datediff / (60 * 60 * 24));
                    }
                }
            }
            $response = '<div class="confirmAssignDate vertical-alignment-helper">
								<div class="modal-dialog">
									<div class="modal-content">
										<form action="" method="post" id="confirmAssignDate" data-ajax="false">
										<div class="modal-header">
											<div class="row">
												<div class="mobpadding">
													<div class="border">
														<div class="col-xs-2">' . ($method == 'dateofbirth' ? '<a data-role="none" data-ajax="false" href="javascript:void(0)" data-dismiss="modal" class="triangle second-col"><i class="fa fa-chevron-left iconsize"></i></a>' : '<a data-role="none" data-ajax="false" href="javascript:void(0)" ' . ($type == 'dulicateWkoutLog' || $type == 'wkoutAssignCal' ? 'data-dismiss="modal"' : 'onclick="openBackwindow(' . "'" . $modelType . "'" . ');"') . ' class="triangle first-col"><i class="fa fa-chevron-left"></i></a><a data-role="none" data-ajax="false" href="javascript:void(0)" onclick="openDateCalender(' . "'" . $date . "','1'" . ');" class="triangle second-col hide"><i class="fa fa-chevron-left iconsize"></i></a>') . '
														</div>
														<div class="col-xs-8 optionpoptitle">Specify the Date</div>
														<div class="col-xs-2 activedatacol second-col ' . ($method == 'dateofbirth' ? '"><button style="background-color:#fff" onclick="return updateBirthDate(' . "'" . $datetype . "'" . ');" name="f_method" class="btn btn-default activedatacol" data-ajax="false" type="button" data-role="none">ok</button>' : 'hide"><button style="background-color:#fff" name="f_method" onclick="return addAssignWorkoutsByDate(' . "$('#selected_date').val(),'0','" . (!empty($assignId) ? $assignId : (!empty($logId) ? $logId : (!empty($fid) ? $fid : '0'))) . "','" . (!empty($assignId) ? 'assigned' : (!empty($logId) ? 'wkoutlog' : (!empty($type) ? $type : ''))) . "'" . ');" class="btn btn-default activedatacol" data-ajax="false" type="button" data-role="none">ok</button>') . '
														</div>
													</div>
												</div>
											</div>
										</div>';
            $response .= '<div class="modal-body opt-body">' . ($method == 'dateofbirth' ? '<div class="aligncenter hide error rediconn"></div><div class="opt-row-detail second-col">
															<div class="aligncenter">Selected Date:</div>
															<div class="mobipick-main" style="display:flex;"></div>
															<input type="hidden" id="selected_date" value="' . date("d M Y", strtotime($date)) . '" class="min-date-hidden"/>
														</div>
														<br>' : '<div class="opt-row-detail first-col">
															<a data-role="none" data-ajax="false" href="javascript:void(0)" ' . ($difference >= 0 ? 'onclick="addAssignWorkoutsByDate(' . "'" . $date . "','0','" . (!empty($assignId) ? $assignId : (!empty($logId) ? $logId : (!empty($fid) ? $fid : '0'))) . "','" . (!empty($assignId) ? 'assigned' : (!empty($logId) ? 'wkoutlog' : (!empty($type) ? $type : ''))) . "'" . ');"' : 'onclick="return false;"') . ' style="width:100%" >
																<div class="col-xs-12 pointer">
																	<div class="border-xr full">
																		<div class="col-xs-2"><i class="fa fa-chevron-right iconsize ' . ($difference < 0 ? 'datacol' : '') . '"></i></div>
																		<div class="col-xs-10 ' . ($difference < 0 ? 'datacol' : '') . '">For Today</div>
																	</div>
																</div>
															</a>
														</div>
														<div class="opt-row-detail first-col">
															<a data-role="none" data-ajax="false" href="javascript:void(0)" onclick="openDateCalender(' . "'" . $date . "','0'" . ');" style="width:100%" >
																<div class="col-xs-12 pointer">
																	<div class="border-xr full">
																		<div class="col-xs-2"><i class="fa fa-calendar iconsize"></i></div>
																		<div class="col-xs-10">Specify Date</div>
																	</div>
																</div>
															</a>
														</div>
														<div class="opt-row-detail second-col hide">
															<div class="aligncenter">Selected Date:</div>
															<div class="mobipick-main" style="display:flex;"></div>
															<input type="hidden" id="selected_date" ' . ((!empty($type) && (strpos($type, 'logged') != true)) ? ($type == 'logged-create' || $type == 'dulicateWkoutLog' ? 'max="' . Helper_Common::get_default_date('', 'd M Y') . '"' : 'min="' . Helper_Common::get_default_date('', 'd M Y') . '"') : '') . ' value="' . date("d M Y", strtotime($date)) . '" class="min-date-hidden"/>
														</div>
														<br>
													  </div>');
            $response .= '<div class="modal-footer"><button data-ajax="false" data-role="none" type="button" class="btn btn-default" data-dismiss="modal">cancel</button>' . ($method == 'dateofbirth' ? '<button style="background-color:#fff" name="f_method" class="btn btn-default activedatacol" data-ajax="false" type="button" data-role="none" onclick="return updateBirthDate(' . "'" . $datetype . "'" . ');">ok</button>' : '<button style="background-color:#fff" name="f_method" onclick="return addAssignWorkoutsByDate(' . "$('#selected_date').val(),'0','" . (!empty($assignId) ? $assignId : (!empty($logId) ? $logId : (!empty($fid) ? $fid : '0'))) . "','" . (!empty($assignId) ? 'assigned' : (!empty($logId) ? 'wkoutlog' : (!empty($type) ? $type : ''))) . "'" . ');" class="btn btn-default activedatacol" data-ajax="false" type="button" data-role="none">ok</button>') . '</div>
									</form>
									</div>
								</div>
							</div><script type="text/javascript">$(document).ready(function(){ if($(".min-date-hidden")){$(".min-date-hidden").mobipick().click();}});</script>';
        } elseif (!empty($action) && trim($action) == 'xrRecordactions') {
            $response = '<div class="xrRecordactions vertical-alignment-helper">
								<div class="modal-dialog">
									<div class="modal-content">
										<form action="" method="post" id="xrRecordactions" data-ajax="false">
										<div class="modal-header">
											<div class="row">
												<div class="mobpadding">
													<div class="border">
														<div class="col-xs-2">
															<a data-role="none" data-ajax="false" href="javascript:void(0)" onclick="closeModelwindow(' . "'" . $modelType . "'" . ');" class="triangle"><i class="fa fa-chevron-left iconsize"></i>
															</a>
														</div>
														<div class="col-xs-8 optionpoptitle">Video Demo</div>
														<div class="col-xs-2"></div>
													</div>
												</div>
											</div>
										</div>
										<div class="modal-body opt-body">
											<div class="opt-row-detail">
												<div class="aligncenter"><iframe id="cartoonVideo" width="100%" height="315" src="' . $method . '" frameborder="0" allowfullscreen></iframe></div>
										  </div>
										</div>
										<div class="modal-footer"><button data-ajax="false" data-role="none" type="button" class="btn btn-default" data-dismiss="modal">close</button></div>
										</form>
									</div>
								</div>
							</div>';
        } elseif (!empty($action) && trim($action) == 'editNotification') {
            if (!Helper_Common::getAllowAllAccessByUser($page_id, 'is_edit_notify_hidden')) {
                $response = '<div class="editNotification vertical-alignment-helper">
								<div class="modal-dialog modal-md">
									<div class="modal-content">
										<div class="modal-header">
											<div class="row">
												<div class="mobpadding">
													<div class="border">
														<div class="col-xs-2">
															<a data-role="none" data-ajax="false" href="javascript:void(0)" onclick="closeModelwindow(' . "'" . $modelType . "'" . ');" class="triangle"><i class="fa fa-chevron-left iconsize2"></i>
															</a>
														</div>
														<div class="col-xs-8 optionpoptitle">List Edit Mode</div>
														<div class="col-xs-2">
															<button data-ajax="false" data-role="none" type="button" class="close pointers" data-dismiss="modal">&times;</button>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="modal-body opt-body">
											<div class="opt-row-detail">
												<div class="opt-row-detail">List Edit Mode provides the following options for managing the ' . ($method == 'workout' ? 'listed record entries and folders:' : 'existing exercise set entries:') . '</div><div class="opt-row-detail"><span class="fa fa-arrows"></span> Move (drag) to re-order an entry</div><div class="opt-row-detail"><span class="fa fa-files-o"></span> Clone (copy) any selected entries</div><div class="opt-row-detail"><span class="fa fa-times"></span> Delete any selected entries</div><div class="opt-row-detail">To return to ' . ($method == 'workout' ? 'Record' : 'Sets') . ' Edit Mode, tap <span class="fa fa-refresh"></span> ' . ($method == 'workout' ? '"plans/list"' : '"sets/list"') . ' button again. This will allow you to create or edit entries for individual ' . ($method == 'workout' ? 'records and folders' : 'exericse sets') . '.</div>
												<div class="opt-row-detail"><input type="checkbox" name="hide_edit_notify" onclick="notifyUpdate(this);" value="1" id="hide_edit_notify"/><label for="hide_edit_notify">Don\'t show this dialog again</label></div>
											</div>
										</div>
										<div class="modal-footer"><button data-ajax="false" data-role="none" type="button" class="btn btn-default" data-dismiss="modal">close</button></div>
									</div>
								</div>
							</div>';
            }
        } elseif (!empty($action) && trim($action) == 'showShortcuts') {
            $response = '<div class="vertical-alignment-helper">
								<div class="modal-dialog modal-md">
									<div class="modal-content">
										<div class="modal-header">
											<div class="row">
												<div class="mobpadding">
													<div class="border">
														<div class="col-xs-2">
															<a data-role="none" data-ajax="false" href="javascript:void(0)" onclick="closeModelwindow(' . "'" . $modelType . "'" . ');" class="triangle"><i class="fa fa-chevron-left iconsize"></i>
															</a>
														</div>
														<div class="col-xs-8 optionpoptitle">Options for the Shortcuts</div>
														<div class="col-xs-2"></div>
													</div>
												</div>
											</div>
										</div>
										<div class="modal-body opt-body">
											<div class="opt-row-detail">
												<a onclick="addAssignWorkoutsByDate(' . "'','0','0','-logged'" . ');" class="btn btn-default" style="width:100%" href="javascript:void(0)" data-ajax="false" data-role="none">
													<div class="col-xs-12 pointer">
														<div class="col-xs-2"><i class="fa fa-angle-double-right iconsize2 orangeicon"></i></div>
														<div class="col-xs-10 text-center">Start a Workout</div>
													</div>
												</a>
											</div>';
            //if(Helper_Common::hasAccess('Create Workouts')){
            $response .= '<div class="opt-row-detail">
												<a onclick="addAssignWorkoutsByDate(' . "'','0','0','-workout'" . ');" class="btn btn-default" style="width:100%" href="javascript:void(0)" data-ajax="false" data-role="none">
													<div class="col-xs-12 pointer">
														<div class="col-xs-2"><i class="fa fa-th-list iconsize2"></i></div>
														<div class="col-xs-10 text-center">Create a Workout Plan</div>
													</div>
												</a>
											</div>';
            //}
            $response .= '<div class="opt-row-detail">
												<a onclick="confirmAssignDate(' . "'" . Helper_Common::get_default_date() . "'" . ');" class="btn btn-default" style="width:100%" href="javascript:void(0)" data-ajax="false" data-role="none">
													<div class="col-xs-12 pointer">
														<div class="col-xs-2"><i class="fa fa-calendar iconsize2"></i></div>
														<div class="col-xs-10 text-center">Schedule a Workout Assignment</div>
													</div>
												</a>
											</div>
											<div class="opt-row-detail">
												<a onclick="addAssignWorkoutlogs(' . "'" . Helper_Common::get_default_date() . "'" . ');" class="btn btn-default" style="width:100%" href="javascript:void(0)" data-ajax="false" data-role="none">
													<div class="col-xs-12 pointer">
														<div class="col-xs-2"><i class="fa fa-pencil-square-o iconsize2"></i></div>
														<div class="col-xs-10 text-center">Log a Workout</div>
													</div>
												</a>
											</div>
											<div class="opt-row-detail">
												<a class="btn btn-default" style="width:100%" href="' . URL::base(TRUE) . 'exercise/exerciseimages/1#upload-image" data-ajax="false" data-role="none">
													<div class="col-xs-12 pointer">
														<div class="col-xs-2"><i class="fa fa-image iconsize2"></i></div>
														<div class="col-xs-10 text-center">Upload Images</div>
													</div>
												</a>
											</div>
											<div class="opt-row-detail">
												<a onclick="createExercise();" href="javascript:void(0);" class="btn btn-default" style="width:100%" data-ajax="false" data-role="none">
													<div class="col-xs-12 pointer">
														<div class="col-xs-2"><i class="fa fa-plus iconsize2"></i></div>
														<div class="col-xs-10 text-center">Create an Exercise Record</div>
													</div>
												</a>
											</div>
										</div>
										<div class="modal-footer"><button data-ajax="false" data-role="none" type="button" class="btn btn-default" data-dismiss="modal">close</button></div>
									</div>
								</div>
							</div>';
        } elseif (!empty($action) && trim($action) == 'workoutAddaction') {
            $response = '<div class="vertical-alignment-helper">
								<div class="modal-dialog modal-md">
									<div class="modal-content">
										<div class="modal-header">
											<div class="row">
												<div class="mobpadding">
													<div class="border">
														<div class="col-xs-2">
															<a data-role="none" data-ajax="false" href="javascript:void(0)" onclick="closeModelwindow(' . "'" . $modelType . "'" . ');" class="triangle"><i class="fa fa-chevron-left iconsize"></i>
															</a>
														</div>
														<div class="col-xs-8 optionpoptitle">Options for Create New Workout</div>
														<div class="col-xs-2"></div>
													</div>
												</div>
											</div>
										</div>
										<div class="modal-body opt-body">';
            if (Helper_Common::hasAccess('Create Workouts')) {
                $response .= '<div class="opt-row-detail">
												<a onclick="addAssignWorkoutsByDate(' . "'','0','0','wkout'" . ');" class="btn btn-default" style="width:100%" href="javascript:void(0)" data-ajax="false" data-role="none">
													<div class="col-xs-12 pointer">
														<div class="col-xs-12 text-center">New Workout</div>
													</div>
												</a>
											</div>';
            }
            $response .= '<div class="opt-row-detail">
												<a onclick="addAssignWorkoutsByDate(' . "'" . Helper_Common::get_default_date() . "','0','0',''" . ');" class="btn btn-default" style="width:100%" href="javascript:void(0)" data-ajax="false" data-role="none">
													<div class="col-xs-12 pointer">
														<div class="col-xs-12 text-center">Assign a Workout</div>
													</div>
												</a>
											</div>
											<div class="opt-row-detail">
												<a onclick="addAssignWorkoutsByDate(' . "'" . Helper_Common::get_default_date() . "','0','0','-loggedwkout'" . ');" class="btn btn-default" style="width:100%" href="javascript:void(0)" data-ajax="false" data-role="none">
													<div class="col-xs-12 pointer">
														<div class="col-xs-12 text-center">Log a Workout</div>
													</div>
												</a>
											</div>
										</div>
										<div class="modal-footer"><button data-ajax="false" data-role="none" type="button" class="btn btn-default" data-dismiss="modal">close</button></div>
									</div>
								</div>
							</div>';
        } else if (!empty($action) && $action == 'deviceinfo') {
            $activityModel = ORM::factory('activityfeed');
            if ($fid != '' && count($activityModel->getDeviceInfo($fid)) > 0) {
                $deviceInfo = $activityModel->getDeviceInfo($fid);
                $response .= '<div class="vertical-alignment-helper">
					<div class="modal-dialog modal-md">
						<div class="modal-content">
							<div class="modal-header">
								<div class="mobpadding">
									<div class="border">
										<div class="col-xs-2">
											<a href="javascript:void(0);" title="' . __("Back") . '" data-dismiss="modal" class="triangle" data-ajax="false" data-role="none">
												<i class="fa fa-chevron-left"></i>
											</a>
										</div>
										<div class="col-xs-8 optionpoptitle">' . __("Logged Device Information") . '</div>
										<div class="col-xs-2"></div>
									</div>
								</div>
							</div>
							<div class="modal-body opt-body">
								<div class="opt-row-detail">' . $deviceInfo['extra_info'] . '</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal" data-ajax="false" data-role="none">' . __('Close') . '</button>
							</div>
						</div>
					</div>
				</div>';
            }
        } else if (!empty($action) && $action == 'profileimgoption') {
            $response .= '<div class="vertical-alignment-helper">
					<div class="modal-dialog modal-md">
						<div class="modal-content">
							<div class="modal-header">
								<div class="mobpadding">
									<div class="border">
										<div class="col-xs-2">
											<a href="#" title="' . __("Back") . '" data-dismiss="modal" class="triangle" data-ajax="false" data-role="none">
												<i class="fa fa-chevron-left"></i>
											</a>
										</div>
										<div class="col-xs-8 optionpoptitle">' . __("Options for Image") . '</div>
										<div class="col-xs-2"></div>
									</div>
								</div>
							</div>
							<div class="modal-body opt-body">
								<div class="opt-row-detail">
									<a href="javascript:void(0);" id="btn_profileimgedit" class="btn btn-default" style="width:100%" data-ajax="false" data-role="none">
										<div class="col-xs-12 pointer">
											<div class="col-xs-3"><i class="fa fa-edit iconsize"></i></div>
											<div class="col-xs-9">' . __("Edit") . '</div>
										</div>
									</a>
								</div>
								<div class="opt-row-detail">
									<a href="javascript:void(0);" id="btn_profileimgprev" class="btn btn-default" onclick="profileImgPrevModal(this);" style="width:100%" data-ajax="false" data-role="none">
										<div class="col-xs-12 pointer">
											<div class="col-xs-3"><i class="fa fa-eye iconsize"></i></div>
											<div class="col-xs-9">' . __("Preview") . '</div>
										</div>
									</a>
								</div>
								<div class="opt-row-detail">
									<a href="' . URL::base() . 'assets/img/user_placeholder.png' . '" id="btn_profileimgclear" class="btn btn-default" style="width:100%" data-ajax="false" data-role="none">
										<div class="col-xs-12 pointer">
											<div class="col-xs-3"><i class="fa fa-refresh iconsize"></i></div>
											<div class="col-xs-9">' . __('Clear') . '</div>
										</div>
									</a>
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal" data-ajax="false" data-role="none">' . __('Close') . '</button>
							</div>
						</div>
					</div>
				</div>';
        } elseif (!empty($action) && trim($action) == 'profileactions') {
            $current_site_name = (Session::instance()->get('current_site_name') ? Session::instance()->get('current_site_name') : '0');
            $title = "";
            if ($method == 'profileedit') {
                $title = "Edit this Account";
            } else if ($method == 'profilecancel') {
                $title = "Cancel My Account Confirmation";
            } else if ($method == 'profiledetails') {
                $title = "Profile Details";
            }
            if ($fromAdmin == 0) {
                $fid = $user->pk();
                $userdetails = $user;
            } else {
                $userdetails = ORM::factory('user')->where('id', '=', $fid)->find();
            }
            $userimageid = Helper_Common::profile_photo($fid);
            $current_userid = Auth::instance()->get_user()->pk();
            $response = '<div class="vertical-alignment-helper">
						 <div class="modal-dialog">
							<div class="modal-content">
							<form action="" method="post" id="profileactions" data-ajax="false">
							  <div class="modal-header">
								<div class="row">
									<input date-role="none" type="hidden" name="edit_userid" id="edit_userid" value="' . $userdetails->pk() . '" />
									<div class="title-header">';
            $response .= '<div class="col-xs-3 aligncenter">';
            if ($method == 'profilecancel') {
                $response .= '
											<a data-ajax="false" href="javascript:void(0);" id="backpopup_search" onclick="$(' . "'#" . $modelType . "'" . ').modal(' . "'hide'" . ');$(' . "'#" . $modelType . "'" . ').html(' . "''" . ');" data-role="none" class="triangle" >
												<i class="fa fa-caret-left iconsize"></i>
											</a>
										';
            } elseif ($method != 'profiledetails') {
                $response .= '
											<a data-ajax="false" href="javascript:void(0);" id="backpopup_search" onclick="showUserModel(' . $userdetails->pk() . ',1)" data-role="none" class="triangle" >
												<i class="fa fa-caret-left iconsize"></i>
											</a>
										';
            }
            $response .= '</div><div class="col-xs-6 aligncenter">';
            if ($method != 'profiledetails') {
                $response .= '<b>' . $title . '</b>';
            }
            $response .= '</div><div class="col-xs-3 aligncenter">';
            if ($method == 'profileedit') {
                $response .= '<button data-ajax="false" data-role="none" type="submit" class="btn btn-default activedatacol" >ok</button>';
            } else if ($method == 'profiledetails') {
                $response .= '<button data-ajax="false" data-role="none" type="button" class="close pointers" data-dismiss="modal">&times;</button>';
            }
            $response .= '</div>
									</div>
								</div>
							  </div>
							  <div class="modal-body">';
            if ($method == 'profileedit') {
                $response .= '<div class="workoutprofile">
									<div class="row form-group" data-role="none">
										<div class="col-xs-12"><label for="fusernamech">First Name :</label></div>
										<div class="col-xs-12">
											<input style="width:100%" class="usernamebutton" date-role="none" type="text" name="fusernamech" id="fusernamech"  required="true" value="' . $userdetails->user_fname . '" />
										</div>
									</div>
									<div class="row form-group" data-role="none">
										<div class="col-xs-12"><label for="lusernamech">Last Name :</label></div>
										<div class="col-xs-12">
											<input style="width:100%" class="usernamebutton" date-role="none" type="text" name="lusernamech" id="lusernamech"  required="true" value="' . $userdetails->user_lname . '" />
										</div>
									</div>
									<div class="row form-group" data-role="none">
										<div class="col-xs-12"><label>Date of Birth :</label></div>
										<div class="col-xs-12">
											<input style="width:100%;background-color: #eee;opacity: 1;" id="dobch" name="dobch" date-role="none" type="text" required="true" ' . ($fromAdmin == '1' ? '' : 'onclick="birthDayPopup(this);"') . ' class="usernamebutton min-date add-on" value="' . ($userdetails->user_dob != '' ? date("d M Y", strtotime($userdetails->user_dob . ' 00:00:00')) : '') . '"/>
											<input id="thealtdate" name="thealtdate" type="hidden" value="0" />
										</div>
									</div>
									<div class="row form-group" data-role="none">
										<div class="col-xs-12"><label>Gender :</label></div>
										<div class="col-xs-12">
											<label class="radio-inline">
												<input data-role="none" data-ajax="false" type="radio" name="user_gender" class="user_gender" ' . ($userdetails->user_gender == '1' ? 'checked=""' : '') . ' id="male" value="1" />Male
											</label>
											<label class="radio-inline">
												<input data-role="none" data-ajax="false" type="radio" name="user_gender" class="user_gender" id="female" ' . ($userdetails->user_gender == '2' ? 'checked=""' : '') . ' value="2" />Female
											</label>
										</div>
									</div>
									<div class="row form-group" data-role="none">
										<div class="col-xs-12"><label>Phone Number :</label></div>
										<div class="col-xs-12">
											<input style="width:100%" placeholder="0123456789" class="usernamebutton onlynumberallowed" date-role="none" type="text" name="usernamephone" id="usernamephone" required="true" maxlength="12" value="' . $userdetails->user_mobile . '" />
										</div>
									</div>
									<div class="row">
										<div class="form-group col-xs-12">
											<label for="code">Profile Image</label>
											<a data-ajax="false" data-role="none" style="width:100%" class="btn btn-default edit-imgnew cboxElement" id="btn_imgedit" href="javascript:void(0);">';
                if ($userimageid != '') {
                    $response .= '<img class="prof-img" date-imgid="' . $userdetails->avatarid . '" id="profile_im" src="' . URL::base() . $userimageid . '">';
                } else {
                    $response .= '<img class="prof-img" date-imgid="" id="profile_im" src="' . URL::base() . 'assets/img/user_placeholder.png' . '">';
                }
                $response .= '<div class="img-placeholder inactivedatacol">Click image to modify</div></a>
										</div>
									</div>

								</div>';
            } else if ($method == 'profilecancel') {
                $response .= '<div class="workout-titlerow">
									<div class="row">
										<div class="mobpadding">
										Warning! By cancelling you\'re My Workouts account, you will lose access to all of your workout plans, training activity and progress reports. A confirmation request will be emailed to your registered email address. To confirm your request to cancel you\'re account, follow the de-activation link provided in this email.<br>
										Would you like to proceed with this cancellation request?
										</div>
									</div>
								</div>';
            } else if ($method == 'profiledetails') {
                $response .= HTML::script('assets/js/jquery.flot.js') . HTML::script('assets/js/jquery.flot.pie.js') . HTML::script('assets/js/jquery.flot.resize.js') . '
					<div class="row">
						<div class="card hovercard">
							<div class="col-lg-6 col-xs-6">
								<div class="useravatar">
									<img width="150" src="' . (!empty($userimageid) && file_exists($userimageid) ? URL::base() . $userimageid : URL::base() . 'assets/img/user_placeholder.png' ) . '">
								</div>
							</div>
							<div class="col-lg-6 col-xs-6">
								<div class="card-info">
									<div class="card-title user_name"></div>
									<br>
									<div class="dropdown">
										<input type="hidden" value="" id="user_id">
										<button data-ajax="false" data-role="none" class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" id="action_userpop">Action
										<span class="caret"></span></button>
										<ul class="dropdown-menu">';
                if ($fromAdmin == 1) {
                    $response .= '<li><a data-ajax="false" data-role="none" href="javascript:void(0);" onclick="changeaction(\'questionqns\');">Intial Questionnaire</a></li>
											<li><a data-ajax="false" data-role="none" href="javascript:void(0);" onclick="changeaction(\'taguser\');">Tag User</a></li>
											<li><a data-ajax="false" data-role="none" href="javascript:void(0);" onclick="changeaction(\'sharewkout\');">Share Workout</a></li>
											<li><a data-ajax="false" data-role="none" href="javascript:void(0);" onclick="changeaction(\'sendemail\');">Send Email</a></li>
											<li' . (isset($fid) && $current_userid == $fid ? '' : ' class="hide"') . '><a data-ajax="false" data-role="none" href="javascript:void(0);" onclick="changeeditpro();">Edit this account</a></li>'
                            . (Helper_Common::is_admin() || Helper_Common::is_manager() ? '<li><a data-ajax="false" data-role="none" href="javascript:void(0);" onclick="changeaction(\'status\');">Edit Status</a></li>
											<li><a data-ajax="false" data-role="none" href="javascript:void(0);" onclick="profileChange(\'profilecancel\');">Cancel this account</a></li>' : '' );
                } else {
                    $response .= '<li><a data-ajax="false" data-role="none" href="javascript:void(0);" onclick="changeeditpro();">Edit Profile</a></li>
											<li><a data-ajax="false" data-toggle="modal" data-role="none" href="javascript:void(0);" style="color:gray" data-target="#editAbout">Edit About Me</a></li>
											<li><a data-ajax="false" data-toggle="modal" style="color:gray" data-role="none" href="javascript:void(0);" data-target="#updateBio">Update Bio</a></li>
											<li><a data-ajax="false" data-role="none" href="javascript:void(0);" onclick="profileChange(\'profilecancel\');">Cancel my account</a></li>
											<li><a data-ajax="false" data-toggle="modal" style="color:gray" data-role="none" href="javascript:void(0);" data-target="#myConnections">My Connections</a></li>';
                }
                $response .= '</ul>
									</div>
							 	</div>
							</div>
						</div>
					</div>
					<div id="usertabs" role="group" class="btn-pref btn-group btn-group-justified btn-group-lg">
						<div role="group" class="btn-group">
							<button data-toggle="tab" href="#tab1" class="btn btn-default" id="user" type="button">
								<i class="fa fa-user"></i>
								<br>
								<span class="tab-info">Profile</span>
							</button>
						</div>
						<div role="group" class="btn-group">
							<button data-toggle="tab" href="#tab2" class="btn btn-default" id="activity" type="button">
								<i class="fa fa-list"></i>
								<br>
								<span class="tab-info">Activity</span>
							</button>
						</div>
						<div role="group" class="btn-group">
							<button data-toggle="tab" href="#tab3" class="btn btn-default" id="reports" type="button">
								<i class="fa fa-signal"></i>
								<br>
								<span class="tab-info">Reports</span>
							</button>
						</div>
						<div role="group" class="btn-group">
							<button data-toggle="tab" href="#tab4" class="btn btn-default" id="connections" type="button">
								<i class="fa fa-users"></i>
								<br>
								<span class="tab-info">Connections</span>
							</button>
						</div>
					</div>
					<div class="well">
						<div class="tab-content">
							<div id="tab1" class="tab-pane fade active in"></div>
							<div id="tab2" class="tab-pane fade">
								<div class="row">
									<div class="col-lg-12">
										<div class="feed_row">
											<div class="panel panel-default">
												<div class="panel-heading">
													<h3 class="panel-title"><i class="fa fa-rss fa-fw"></i> Activity Feed</h3>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div id="tab3" class="tab-pane fade">
							</div>
							<div id="tab4" class="tab-pane fade">
								<h3>List of connections with other users.</h3>
							</div>
						</div>
					</div>';
            }
            $response .= '</div>';
            if ($method == 'profilecancel') {
                $response .= '<div class="modal-footer">
										<div class="row">
										<div class="title-header">
										<div class="col-xs-3">
											<a data-role="none" data-ajax="false" href="javascript:void(0);" class="btn btn-default" onclick="$(' . "'#" . $modelType . "'" . ').modal(' . "'hide'" . ');$(' . "'#" . $modelType . "'" . ').html(' . "''" . ');">back</a>
										</div>
										<div class="col-xs-6 aligncenter"></div>
										<div class="col-xs-3 cancelaccountcs">
											<a data-ajax="false" class="btn btn-default" data-role="none" href="javascript:void(0);" data-dismiss="modal" onclick="confirmCancel();">Proceed</a>
										</div>
										</div>
										</div>
									</div>';
            } else if ($method == 'profileedit') {
                $response .= '<div class="modal-footer">
								   <button data-role="none" data-ajax="false" type="button"  class="btn btn-default usermodal-cancel" onclick="showUserModel(' . $userdetails->pk() . ',1)">close</button>
								   <button data-ajax="false" data-role="none" type="submit" class="btn btn-default activedatacol" >ok</button>
								</div>';
            } else {
                $response .= '<div class="modal-footer">
								   <button data-role="none" data-ajax="false" type="button"  class="btn btn-default" onclick="closeModelwindow(' . "'userModal'" . ');">close</button>
								</div>';
            }
            $response .= '</form></div>
						  </div></div><div id="userModalActions"  class="modal fade" role="dialog" tabindex="-1"></div>';
            //if($fromAdmin ==false)
            $response .= '<script type="text/javascript">$(document).ready(function(){$("div#usertabs.btn-pref .btn").click(function () {$("div#usertabs.btn-pref .btn").removeClass("btn-primary").addClass("btn-default");$("div#usertabs i.fa").css("color","#1b9af7");$("div#usertabs .tab").addClass("active");$(this).removeClass("btn-default").addClass("btn-primary");$(this).find("i.fa").css("color","#ffffff");});if($("#fusernamech"))$("#fusernamech").addClass("usernamebutton");if($("#lusernamech"))$("#lusernamech").addClass("usernamebutton"); });</script>';
        }
        $this->response->body($response);
    }

    public function action_getRelatedXrRecordsMore() {
        $workoutModel = ORM::factory('workouts');
        $fid = $_GET['id'];
        $exerciseRecord = $workoutModel->relatedExcersise($fid);
        $start = $_GET['start'];
        $limit = $_GET['lim'];
        $temp = array();
        if (!empty($exerciseRecord) && count($exerciseRecord) > 0) {
            $RelatedExercises = $workoutModel->getRelatedExercises($fid, $exerciseRecord['musprim_id'], $exerciseRecord['type_id'], $start, $limit);
            foreach ($RelatedExercises as $key => $value) {
                if (!empty($fid) && isset($value['img_url']) && !empty($value['img_url']) && file_exists($value['img_url'])) {
                    $temp[$key]["img"] = URL::base() . $value['img_url'];
                }
                $temp[$key]["title"] = $value["title"];
                $temp[$key]["xr_id"] = $value["unit_id"];
                $temp[$key]["muscle_title"] = $value["muscle_title"];
                $temp[$key]["equip_title"] = $value["equip_title"];
                $temp[$key]["xrtype"] = ($value['default_status'] == 0 ? 'from My Records' : ($value['default_status'] == 1 ? (Helper_Common::is_admin() || Helper_Common::is_manager() ? 'from Default Records' : 'from Sample Records') : ($value['default_status'] == 2 ? 'from Sample Records' : ($value['default_status'] == 3 ? 'from Shared Records' : '' ))));
            }
        }
        echo json_encode($temp);
    }

    public function action_getSequenceMore() {
        $workoutModel = ORM::factory('workouts');
        $fid = $_GET['id'];
        $start = $_GET['start'];
        $limit = $_GET['lim'];
        $sequenceRecord = $workoutModel->getSequencesByUnitId($fid, $start, $limit);
        $temp = array();
        if (!empty($sequenceRecord) && count($sequenceRecord) > 0) {
            foreach ($sequenceRecord as $key => $value) {
                if (!empty($fid) && isset($value['img_url']) && !empty($value['img_url']) && file_exists($value['img_url'])) {
                    $temp[$key]["img"] = $value['img_url'];
                }
                $temp[$key]["seq_desc"] = $value["seq_desc"];
                $temp[$key]["img_title"] = $value["img_title"];
            }
        }
        echo json_encode($temp);
    }

    public function action_usersavergen() {
        $action_flag = (isset($_POST['action']) ? $_POST['action'] : "profile");
        if ($action_flag == "profile") {
            $datauser = isset($_POST['post_form']) ? $_POST['post_form'] : "";
            $data['userid'] = $datauser[0]['useridprofile'];
            $user = ORM::factory('user')->where('id', '=', $data['userid'])->find();
            $data['firstname'] = $datauser[0]['fnameuserch'];
            $data['lastname'] = $datauser[0]['lnameuserch'];
            $data['dobuser'] = $datauser[0]['dobch'];
            $data['idavatar'] = $datauser[0]['avatar_id'];
            $data['gender'] = $datauser[0]['usergender'];
            $data['mobile'] = $datauser[0]['userphone'];
            $old_dob = $user->user_dob;
            $old_avatarid = $user->avatarid;
            $old_gender = $user->user_gender;
            $old_mobile = $user->user_mobile;
            Helper_Common::updateuserdetails($data, 'profile');
            echo $old_dob . '==>' . $data['dobuser'] . '<br>' . $old_avatarid . '====>' . $data['idavatar'] . '<br>' . $old_gender . '===>' . $data['gender'] . '<br>' . $old_mobile . '===>' . $data['mobile'];
            if (strtotime($old_dob) != strtotime($data['dobuser'])) {
                /*                 * ***************** Activity Feed ******************** */
                $activity_feed = array();
                $activity_feed["feed_type"] = 19; // This get from feed_type table
                $activity_feed["action_type"] = 31;  // This get from action_type table  
                $activity_feed["type_id"] = $activity_feed["user"] = $user->pk(); // user id
                $activity_feed["site_id"] = Session::instance()->get('current_site_id');
                $activity_feed["context_date"] = Helper_Common::get_default_datetime($data['dobuser']);
                $activity_feed["json_data"] = json_encode('to');
                Helper_Common::createActivityFeed($activity_feed);
                /*                 * ***************** Activity Feed ******************** */
            }if (!empty($data['idavatar']) && $old_avatarid && $old_avatarid != $data['idavatar']) {
                /*                 * ***************** Activity Feed ******************** */
                $activity_feed = array();
                $activity_feed["feed_type"] = 19; // This get from feed_type table
                $activity_feed["action_type"] = 34;  // This get from action_type table  
                $activity_feed["type_id"] = $activity_feed["user"] = $user->pk(); // user id
                $activity_feed["site_id"] = Session::instance()->get('current_site_id');
                Helper_Common::createActivityFeed($activity_feed);
                /*                 * ***************** Activity Feed ******************** */
            }if ($old_gender != $data['gender']) {
                /*                 * ***************** Activity Feed ******************** */
                $activity_feed = array();
                $activity_feed["feed_type"] = 19; // This get from feed_type table
                $activity_feed["action_type"] = 32;  // This get from action_type table  
                $activity_feed["type_id"] = $activity_feed["user"] = $user->pk(); // user id
                $activity_feed["site_id"] = Session::instance()->get('current_site_id');
                $activity_feed["json_data"] = json_encode('to ' . ($data['gender'] == '1' ? 'Male' : 'Female'));
                Helper_Common::createActivityFeed($activity_feed);
                /*                 * ***************** Activity Feed ******************** */
            }if ($old_mobile != $data['mobile']) {
                /*                 * ***************** Activity Feed ******************** */
                $activity_feed = array();
                $activity_feed["feed_type"] = 19; // This get from feed_type table
                $activity_feed["action_type"] = 33;  // This get from action_type table  
                $activity_feed["type_id"] = $activity_feed["user"] = $user->pk(); // user id
                $activity_feed["site_id"] = Session::instance()->get('current_site_id');
                $activity_feed["json_data"] = json_encode('to ' . $data['mobile']);
                Helper_Common::createActivityFeed($activity_feed);
                /*                 * ***************** Activity Feed ******************** */
            }
        }
    }

}

// End Search
