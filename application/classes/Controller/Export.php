<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Export extends Controller {

	public function _construct() {
         parent::__construct($request, $response);
    } 
		
	public function action_index()
	{
		$this->redirect('Dashboard/index');
	}
	
	public function action_journal(){
		$logId	= urldecode($this->request->param('id'));
		$type	= urldecode($this->request->param('eid'));
		if(!empty($type) && is_numeric($type)){
			if($type == '1'){
				$adminworkoutmodel     = ORM::factory('admin_workouts');
				$siteid = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
				$sitename = Session::instance()->get('current_site_name');
				$focusRecord        = $adminworkoutmodel->getAllFocus();
				if (Helper_Common::is_manager() || Helper_Common::is_trainer()) {
					$userids = array();
					if (Helper_Common::is_manager()) {
						$user = array_merge($manager, $trainer);
					}
					if (Helper_Common::is_trainer()) {
						$user = $trainer;
					}
					if (isset($user) && is_array($user) && count($user) > 0) {
						foreach ($user as $k => $v) {
							$userids[] = $v["id"];
						}
						$userids = implode(",", $userids);
					}
					$content = $adminworkoutmodel->getWorkoutDetailsByUser($userids, '', $siteid);
				} else {
					$content = $adminworkoutmodel->getWorkoutDetailsByUser('', '', $siteid);
				}
				$title = '"'.$sitename.'" Workout Records List Report';
				$contents = $this->_Gst_report_content($content,$title);		
				$contents = $this->_generatePDF($contents,$title);
			}elseif($type =='2'){
			
			
			}
		}
		echo '==>'.$logId;die();
	}
	public function action_generateWkoutTemp(){
		$workoutModel = ORM::factory('workouts');
		$array_allval = $this->request->post('fetchallvalues');
		$type_workout = $array_allval[0]['exporttype'];
		$exportid_workout = $array_allval[0]['exportid'];
		$flagval_workout = $array_allval[0]['flag_val'];
		$fromAdmin 		 = (isset($array_allval[0]['fromAdmin']) ? $array_allval[0]['fromAdmin'] : false);
		$repetitions = $workoutModel->getInnerDrive();
		$focusRecord = $workoutModel->getAllFocus();
		$user 		 = Auth::instance()->get_user();
		$siteid 	 = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
		$user_id 	 = $user->pk();
		/******************* Activity Feed *********************/
		$activity_feed = array();
		$activity_feed["feed_type"]   	= 10;
		$activity_feed["action_type"]  	= 43;
		$activity_feed["user"]  		= $user_id; // user id
		$activity_feed["site_id"]  		= $siteid;
		/******************* Activity Feed *********************/
		if($fromAdmin){
			$user_id = 0;
		}
		if($type_workout == "logged"){
			$workoutRecord  = $workoutModel->getLoggedworkoutById($exportid_workout);
			$exerciseRecord = $workoutModel->getExerciseSets('wkoutlog',$exportid_workout);
			$activity_feed["feed_type"]   	= 11;
		}elseif($type_workout == "shared"){
			$workoutRecord  = $workoutModel->getShareworkoutById($user_id,$exportid_workout);
			$exerciseRecord = $workoutModel->getExerciseSets('shared',$exportid_workout);
			$activity_feed["feed_type"]   	= 12;
		}elseif($type_workout == "sample"){
			$workoutRecord	= $workoutModel->getSampleworkoutById('0', $exportid_workout);
			$exerciseRecord = $workoutModel->getSampleExerciseSet($exportid_workout);
			$activity_feed["feed_type"]   	= 15;
		}elseif($type_workout == "assigned"){
			$workoutRecord	= $workoutModel->getAssignworkoutById($exportid_workout, $user_id);
			$exerciseRecord = $workoutModel->getExerciseSets('assigned',$exportid_workout);
			$activity_feed["feed_type"]   	= 13;
		}else{
			$workoutRecord	= $workoutModel->getworkoutById($user_id,$exportid_workout);
			$exerciseRecord = $workoutModel->getExerciseSet($exportid_workout);
			$activity_feed["feed_type"]   	= 2;
		}
		$activity_feed["type_id"]    	= $exportid_workout;
		$activity_feed["json_data"]  		= json_encode('Email');
		Helper_Common::createActivityFeed($activity_feed);
		$exerciseUnitsDetail = array();
		$temporary		= array();
		if(isset($exerciseRecord) && count($exerciseRecord)>0){
			foreach($exerciseRecord as $keys => $val){
				$exerciseUnits  = array();
				if(!empty($val['goal_unit_id']) && !isset($temporary[$val['goal_unit_id']])){
					$temporary[$val['goal_unit_id']] = $val['goal_unit_id'];
					$exerciseRecordData	= $workoutModel->getExerciseById($val['goal_unit_id']);
					$exerciseUnits[$val['goal_unit_id'].'_data'] = $exerciseRecordData;
					$exerciseUnits[$val['goal_unit_id'].'_seqdata'] = $workoutModel->getSequencesByUnitId($val['goal_unit_id'], 0, 5);
					$exerciseUnits[$val['goal_unit_id'].'_relateddata'] = $workoutModel->getRelatedExercises($val['goal_unit_id'], $exerciseRecordData['musprim_id'], $exerciseRecordData['type_id'],0,5);
					$exerciseUnitsDetail[] = $exerciseUnits;
				}
			}
		}
		$content = $this->contentworkoutshare($workoutRecord,$focusRecord,$exerciseRecord,$repetitions,$exerciseUnitsDetail);
		if($flagval_workout == 'byemail'){
			$user_detail = Auth::instance()->get_user();
			$config = Kohana::$config->load('emailsetting');	
			$siteid = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
			
			$sitename = Session::instance()->get('current_site_name');		
			$to_address= $user_detail->user_email;
			
			$from_address= 'info@workout.com';
			$from_name= $sitename;
			$title  = $subject    = $workoutRecord['wkout_title'].' Details';
			if(!$content) {
				echo 'no_data';
				exit;
			}
			$email = Email::factory($subject);
			$email->message($content, 'text/html');
			$email->to($to_address);
			$email->from($from_address, $from_name);
			$report['process_success'] = $email->send();
			$report['from_mail'] = $from_address;
			$report['to_mail'] = $to_address;
		}else if($flagval_workout == 'bypdf'){
			//$report['filenamepdf']=$this->_generatePDF($content, 'Test PDF');
			$report['process_success'] = 1;
		}else{
			$report['process_success'] = 1;
		}
		echo json_encode($report);
	}
	public function contentworkoutshare($workoutRecord,$focusRecord,$exerciseRecord,$repetitions,$exerciseUnitsDetail){
		$workoutModel = ORM::factory('workouts');
		$content = '<table style="width:100%;">
			<tr><td style="width:50%">
				<table style="width:100%;font-size:14px;">
					<tr>
						<td>Name:</td>
						<td style="border-bottom: 0.5px dotted #000;border-radius: 0;">test user</td>
					</tr>
					<tr>
						<td>Date:</td>
						<td style="border-bottom: 0.5px dotted #000;border-radius: 0;">'.Helper_Common::get_default_date().'</td>
					</tr>
					<tr>
						<td>Workout Plan:</td>
						<td style="border-bottom: 0.5px dotted #000;border-radius: 0;">'.ucfirst($workoutRecord['wkout_title']).'</td>
					</tr>
					<tr>
						<td>Workout Focus:</td>
						<td style="border-bottom: 0.5px dotted #000;border-radius: 0;">';
						foreach($focusRecord as $keys => $values){ 
							if($values['focus_id'] == $workoutRecord['wkout_focus']) 
							$content .= ucfirst($values['focus_opt_title']);
						};
		   $content .= '</td>
					</tr>
				</table>
				</td>
				<td style="width:50%">
				 	<table style="width:100%;text-align:right;">
						<tr>
							<td>
								<a data-ajax="false" href="'.URL::base(TRUE).'index'.'" style="text-decoration:none;display:block;"><img src="'.URL::site(NULL, 'http').'assets/img/moblogo.png'.'" width="50px;"/></a>
							</td>
							<td style="vertical-align:middle;color: #000;font-weight: bold;font-size: 16px;margin: auto;width:28%">Workout Plan</td>
						</tr>
						<tr>
							<td></td><td>Site Title</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<div style="padding-top:25px;"></div>
		<table width="100%" style="font-size:16px;">
			<thead>
				<tr>
					<th style="border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000; color: #1b9af7;width:10px;border-radius: 0;">Set</th>
					<th style="border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000; color: #1b9af7;width:150px;border-radius: 0;">Exercise</th>
					<th style="border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000; color: #1b9af7;width:100px;border-radius: 0;word-wrap: normal;word-break: normal;white-space: normal;">Repetitions</th>
					<th style="border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000; color: #1b9af7;width:100px;border-radius: 0;word-wrap: normal;word-break: normal;white-space: normal;">Resistance</th>
					<th style="border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000; color: #1b9af7;width:100px;border-radius: 0;word-wrap: normal;word-break: normal;white-space: normal;">Time</th>
					<th style="border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000; color: #1b9af7;width:100px;border-radius: 0;word-wrap: normal;word-break: normal;white-space: normal;">Distance</th>
					<th style="border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000; color: #1b9af7;width:100px;border-radius: 0;word-wrap: normal;word-break: normal;white-space: normal;">Rate</th>
					<th style="border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000; color: #1b9af7;width:100px;border-radius: 0;word-wrap: normal;word-break: normal;white-space: normal;">Inner Drive</th>
					<th style="border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000; color: #1b9af7;width:100px;border-radius: 0;word-wrap: normal;word-break: normal;white-space: normal;">Angle</th>
					<th style="border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000; color: #1b9af7;width:100px;border-radius: 0;word-wrap: normal;word-break: normal;white-space: normal;">Rest After</th>
					<th style="border-bottom: 0.5px dotted #000; color: #1b9af7;width: 150px;border-radius: 0;">Remarks</th>
				</tr>
			</thead>
			<tbody>';
		if(isset($exerciseRecord) && count($exerciseRecord)>0){
			foreach($exerciseRecord as $keys => $values){
				$reps = $resist = $time = $dist = $rate = $int = $angle = $xr = $rest = $remark = $styleBgurl = '' ;
				if(!empty($values['goal_unit_id'])){
					if(file_exists($values['img_url']))
						$styleBgurl = URL::site(NULL, 'http').$values['img_url'];
					else
						$styleBgurl = URL::site(NULL, 'http').'assets/img/fa-file-image-o.png';
				}else
					$styleBgurl = URL::site(NULL, 'http').'assets/img/fa-pencil-square.png';
				if($values['goal_time_hh']>0 || $values['goal_time_mm'] >0 || $values['goal_time_ss']  >0)
					$time = substr(sprintf("%02d", $values['goal_time_hh']),0,2).':'.substr(sprintf("%02d", $values['goal_time_mm']),0,2).':'.substr(sprintf("%02d", $values['goal_time_ss']),0,2);		  
				if($values['goal_dist']>0 && $values['goal_dist_id']>0)
					$dist = $values['goal_dist'].' <span>'.Model::instance('Model/workouts')->getGoalValues('dist',$values['goal_dist_id']);
			  
				if($values['goal_reps']>0)
					$reps = $values['goal_reps'].' <span>reps</span>';
			  
				if($values['goal_resist']>0 && $values['goal_resist_id']>0)
					$resist = 'x '.$values['goal_resist'].' <span>'.Model::instance('Model/workouts')->getGoalValues('resist',$values['goal_resist_id']).'</span>';
			  
				if($values['goal_rate']>0 && $values['goal_rate_id']>0)
					$rate = '<span>@'.$values['goal_rate'].' '.Model::instance('Model/workouts')->getGoalValues('rate',$values['goal_rate_id']).'</span>';
				
				if($values['goal_angle']>0 && $values['goal_angle_id']>0)
					$angle = '<span>'.$values['goal_angle'].'%'.Model::instance('Model/workouts')->getGoalValues('angle',$values['goal_angle_id']).'</span>';
				
				$intvalue = Model::instance('Model/workouts')->getGoalValues('int',$values['goal_int_id']);
				if($intvalue>0)
					$int .= '<span>'.$intvalue.' int</span>';
					
				if($values['goal_rest_mm'] + $values['goal_rest_ss']>0){
					if($values['goal_rest_mm'] >0 ||  $values['goal_rest_ss']>0){
						$rest =  '<span>'.$values['goal_rest_mm'];
						if($values['goal_rest_ss']>0 && $values['goal_rest_ss'] < 10)
							$rest .=  ':0'.$values['goal_rest_ss'];
						else
							$rest .=  ':'.substr(sprintf("%02d", $values['goal_rest_ss']),0,2);
						$rest .=  ' rest</span>';
					}
				}
				$xrimg = !empty($styleBgurl) ? '<img src="'.$styleBgurl.'" width="60px;"/>' : '';
				$content .='<tr style="background-color:'.($keys % 2 == 0 ? '#FFFFFF' : '#F1F1F1').'">
					<td style="border-bottom: 0.5px dotted #000; border-left: 0.5px dotted #000; border-right: 0.5px dotted #000;border-radius: 0;">'.($keys+1).'</td>
					<td style="border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000; vertical-align: middle;border-radius: 0">
						<table style="width:100%;">
							<tr>
								<td style="width:20%; padding-right:10px; height: 50px;">'.$xrimg.'</td>
								<td style="vertical-align:middle;">'.ucfirst($values['goal_title']).'</td>
								<td style="vertical-align:middle;text-align: right;font-size:15px;">[<a style="text-decoration:none;" data-ajax="false" href="#ExrId'.$values['goal_unit_id'].'">Ref.ID'.$values['goal_unit_id'].'</a>]</td>
							</tr>
						</table>
					</td>
					<td style="border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000; vertical-align: middle;border-radius: 0;">'.$reps.'</td>
					<td style="border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000; vertical-align: middle;border-radius: 0;">'.$resist.'</td>
					<td style="border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000; vertical-align: middle;border-radius: 0;">'.$time.'</td>
					<td style="border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000; vertical-align: middle;border-radius: 0;">'.$dist.'</td>
					<td style="border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000; vertical-align: middle;border-radius: 0;">'.$rate.'</td>
					<td style="border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000; vertical-align: middle;border-radius: 0;">'.$int.'</td>
					<td style="border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000; vertical-align: middle;border-radius: 0;">'.$angle.'</td>
					<td style="border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000; vertical-align: middle;border-radius: 0;">'.$rest.'</td>
					<td style="border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000; vertical-align: middle;border-radius: 0;">'.$values['goal_remarks'].'</td>
				</tr>';
			}
		}
	$content .='</tbody></table>

		<table style="width:100%;font-size:28px;">
			<tr>
				<td colspan="2" width="100%" style="padding-top:25px;">
				</td>
			</tr>
			<tr>
				<td colspan="2" style="width:100%;padding-top:25px;padding-bottom:20px;">
					Notes for this Workout(Overall):
				</td>
			</tr>
			<tr>';
				$testflag=true;
				if($testflag){
					$content .='<td style="width:100%;">
						<table style="width:100%;font-size:28px;">
							<tr>
								<td style="width:15%; vertical-align: middle;">Perceived Intensity:</td>
								<td style="width:85%;">
									<table style="text-align:left;border-collapse: collapse; page-break-inside:avoid;" width="100%">
										<tr>';
											if(isset($repetitions) && count($repetitions)>0){
												foreach($repetitions as $keys => $values){ 
													if((int) $values['int_opt_id'] == $values['int_opt_id']){ 
														$content .='<td width="150px" style="border-right: 0.5px dotted black; border-radius: 0;'.(isset($workoutRecord['note_wkout_intensity']) && round($workoutRecord['note_wkout_intensity']) >= $values['int_opt_id'] ? 'border-bottom: 6px dotted green;' : 'border-bottom: 1px dotted black;').'"></td>';
													}
								 				}
											}
											$content .='<td width="150px" style="height:40px;border-bottom: 1px dotted black;border-radius: 0;"></td>
										</tr>
										<tr>';
											if(isset($repetitions) && count($repetitions)>0){
												foreach($repetitions as $keys => $values){ 
													if((int) $values['int_opt_id'] == $values['int_opt_id']){ 
														$content .='<td width="150px" style="border-right: 0.5px dotted black; border-radius: 0;'.( isset($workoutRecord['note_wkout_intensity']) && round($workoutRecord['note_wkout_intensity']) >= $values['int_opt_id'] ? 'border-top: 6px dotted green;' : 'border-top: 1px dotted black;').'"></td>';
													}
								 				}
											}
											$content .='<td width="150px" style="height:40px;border-top: 1px dotted black; border-radius: 0;"></td>
										</tr>
										<tr>';
										 	if(isset($repetitions) && count($repetitions)>0){
												foreach($repetitions as $keys => $values){ 
													if((int) $values['int_opt_id'] == $values['int_opt_id']){
														$content .='<td style="text-align:right;font-size:19x; border-radius: 0;word-wrap: break-word; white-space: pre-wrap;">'.ucfirst($values['int_grp_title']).'</td>';
													}
												} 
											}
										$content .='</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>';
				}
			$content .='</tr>
			<tr>
				<td colspan="2" width="100%" style="padding-top:25px;">Remarks / Notes:</td>
			</tr>
			<tr>
				<td width="100%" colspan="2" style="border:1px dotted black;height:200px;">'.( (isset($workoutRecord['note_wkout_remarks']))?$workoutRecord['note_wkout_remarks']:'' ).'</td>
			</tr>
		</table><div style="padding-bottom:25px;">&nbsp;</div>';
		/*xr record detailing section*/
		foreach ($exerciseUnitsDetail as $key => $value) {
			$content .= '<div id="'.$key.'" style="border:1px dotted #000;padding:5px 10px;page-break-before:always;">';
			$cnt = 0;
			foreach ($value as $unitkey => $unitvalue) {
				if(strripos($unitkey,"_data")){
					if(!empty($unitvalue) && count($unitvalue)>0){
						$cnt++;
						$content .= '<div style="vertical-align:middle;color: #000;font-weight: bold;font-size: 16px;margin: auto;padding-bottom:3px;">'.$cnt.'. Exercise Record</div><a name="ExrId'.$unitvalue['unit_id'].'" style="display:none;"></a><table id="ExrId'.$unitvalue['unit_id'].'" width="100%" style="font-size:16px;">';
							$content .='<tbody>
						<tr style="border: 0.5px dotted #000;">
							<td style="color: #000000;font-weight: 500;">Exercise Title</td>
							<td style="vertical-align:middle; color: #1b9af7;">'.ucfirst($unitvalue['title']).'</td>							
						</tr>
						<tr style="border: 0.5px dotted #000;">
							<td style="color: #000000;font-weight: 500;">Exercise Type</td>
							<td style="vertical-align:middle;color: #1b9af7;">'.(!empty($unitvalue['type_title']) ? ucfirst($unitvalue['type_title']) : '').'</td>						
						</tr>
						<tr style="border: 0.5px dotted #000;">';
							$xrimgurl = '' ;
								if(!empty($unitvalue['unit_id'])){
									if(file_exists($unitvalue['img_url']))
										$xrimgurl = URL::site(NULL, 'http').$unitvalue['img_url'];
									else
										$xrimgurl = URL::site(NULL, 'http').'assets/img/fa-file-image-o.png';
								}else
									$xrimgurl = URL::site(NULL, 'http').'assets/img/fa-pencil-square.png';
								$xrimg = !empty($xrimgurl) ? '<img style="border:1px solid grey;" src="'.$xrimgurl.'" width="100px;"/>' : '<img style="border:1px solid grey;" src="'.URL::site(NULL, 'http').'assets/img/fa-file-image-o.png'.'" width="100px;"/>'; 
					$content .=	'<td colspan="2" style="border: 0.5px dotted #000;">
								<table style="width:100%;border: 0.5px dotted #000;">
									<tr>
										<td style="width:20%; padding-right:10px; height: 50px;">'.$xrimg.'</td>
										<td style="border: 0.5px dotted #000;">
											<table style="width:100%;border: 0.5px dotted #000;">
												<tr>
													<td style="color: #000000;font-weight: 500;">Description</td>
												</tr>
												<tr>
													<td style="display:block;color: #1b9af7;">'.(!empty($unitvalue['descbr']) ? ucfirst($unitvalue['descbr']) : '').'</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr style="border: 0.5px dotted #000;">
							<td style="color: #000000;font-weight: 500;">Primary Muscles</td>
							<td style="vertical-align:middle;color: #1b9af7;">'.(!empty($unitvalue['muscle_title']) ? ucfirst($unitvalue['muscle_title']) : "").'</td>	
						</tr>
						<tr style="border: 0.5px dotted #000;">';
							$param = '';
							$additionaMuscleRecord = Model::instance('Model/workouts')->getAdditionalMusules($unitvalue['unit_id']); 
							if(!empty($additionaMuscleRecord) && count($additionaMuscleRecord)>0){
								foreach($additionaMuscleRecord as $keys => $value){
									$param	.= ucfirst($value['muscle_title']).', ';
								}
							} 
				$content .=	'<td style="color: #000000;font-weight: 500;">Other Muscles Involved</td>
							<td style="vertical-align:middle;color: #1b9af7;">'.rtrim($param, ", ").'</td>	
						</tr>
						<tr style="border: 0.5px dotted #000;">';
							$equipparam = (!empty($unitvalue['muscle_title']) ? ucfirst($unitvalue['muscle_title']) : '').', ';
							$additionaEquipRecord = Model::instance('Model/workouts')->getAdditionalEquip($unitvalue['unit_id']); 
							if(!empty($additionaEquipRecord) && count($additionaEquipRecord)>0){
								foreach($additionaEquipRecord as $keys => $value){
									$equipparam	.= ucfirst($value['equip_title']).', ';
								}
							}
					$content .=	'<td style="color: #000000;font-weight: 500;">Equipment / Alternatives</td>
							<td style="vertical-align:middle;color: #1b9af7;">'.rtrim($equipparam, ", ").'</td>	
						</tr>
						<tr style="border: 0.5px dotted #000;">
							<td style="color: #000000;font-weight: 500;">Mechanices Type</td>
							<td style="vertical-align:middle;color: #1b9af7;">'.(!empty($unitvalue['mech_title']) ? ucfirst($unitvalue['mech_title']) : '').'</td>	
						</tr>
						<tr style="border: 0.5px dotted #000;">
							<td style="color: #000000;font-weight: 500;">Exercise Level</td>
							<td style="vertical-align:middle;color: #1b9af7;">'.(!empty($unitvalue['level_title']) ? ucfirst($unitvalue['level_title']) : '').'</td>	
						</tr>
						<tr style="border: 0.5px dotted #000;">
							<td style="color: #000000;font-weight: 500;">Sport</td>
							<td style="vertical-align:middle;color: #1b9af7;">'.(!empty($unitvalue['sport_title']) ? ucfirst($unitvalue['sport_title']) : '').'</td>	
						</tr>
						<tr style="border: 0.5px dotted #000;">
							<td style="color: #000000;font-weight: 500;">Force / Movement</td>
							<td style="vertical-align:middle;color: #1b9af7;">'.(!empty($unitvalue['force_title']) ? ucfirst($unitvalue['force_title']) : '').'</td>	
						</tr>
						<tr style="border: 0.5px dotted #000;">
							<td style="color: #000000;font-weight: 500;">Other Remarks</td>
							<td style="vertical-align:middle;color: #1b9af7;">'.(!empty($unitvalue['descfull']) ? ucfirst($unitvalue['descfull']) : '').'</td>	
						</tr>
					</tbody></table>';
					}
				}
				if(strripos($unitkey,"_seqdata")){
					if(!empty($unitvalue) && count($unitvalue)>0){
						$cnt++;
						$content .= '<div style="vertical-align:middle;color: #000;font-weight: bold;font-size: 16px;margin: auto;padding-top:10px;padding-bottom:3px;">'.$cnt.'. Sequence Instruction</div><table width="100%" style="font-size:14px;">
							<thead>
								<tr>
									<th style="border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000; color: #1b9af7;width:20px;border-radius: 0;">Sequence</th>
									<th style="border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000; color: #1b9af7;width:200px;border-radius: 0;">Sequence Image</th>
									<th style="border-bottom: 0.5px dotted #000; color: #1b9af7;border-radius: 0;">Description</th>
								</tr>
							</thead>
							<tbody>';
							foreach ($unitvalue as $seqkey => $seqvalue) {
								$seqimgurl = '' ;
								if(!empty($seqvalue['img_url']) && file_exists($seqvalue['img_url'])){
									$seqimgurl = URL::site(NULL, 'http').$seqvalue['img_url'];
								}
								$seqimg = !empty($seqimgurl) ? '<img src="'.$seqimgurl.'" width="60px;"/>' : '';
								$content .='<tr style="background-color:'.($seqkey % 2 == 0 ? '#FFFFFF' : '#F1F1F1').'">
									<td style="border-bottom: 0.5px dotted #000; border-left: 0.5px dotted #000; border-right: 0.5px dotted #000;border-radius: 0;">'.($seqkey+1).'</td>
									<td style="border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000; vertical-align: middle;border-radius: 0">'.$seqimg.'</td>
									<td style="border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000; vertical-align: middle;border-radius: 0;">'.(!empty($seqvalue['seq_desc']) ? ucfirst($seqvalue['seq_desc']) : '').'</td>
								</tr>';
							}
							$content .= '</tbody>
						</table>';
					}
				}
				if(strripos($unitkey,"_relateddata")){
					if(!empty($unitvalue) && count($unitvalue)>0){
						$cnt++;
						$content .= '<div style="vertical-align:middle;color: #000;font-weight: bold;font-size: 16px;margin: auto;padding-top:10px;padding-bottom:3px;">'.$cnt.'. Related Exercise Records</div><table width="100%" style="font-size:14x;">
							<thead>
								<tr>
									<th style="border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000; color: #1b9af7;width:20px;border-radius: 0;">Exercise</th>
									<th style="border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000; color: #1b9af7;width:200px;border-radius: 0;">Feature Image</th>
									<th style="border-bottom: 0.5px dotted #000; color: #1b9af7;border-radius: 0;">Title</th>
								</tr>
							</thead>
							<tbody>';
							foreach ($unitvalue as $relkey => $relvalue) {
								$featimgurl = '' ;
								if(!empty($relvalue['unit_id'])){
									if(file_exists($relvalue['img_url']))
										$featimgurl = URL::site(NULL, 'http').$relvalue['img_url'];
									else
										$featimgurl = URL::site(NULL, 'http').'assets/img/fa-file-image-o.png';
								}else
									$featimgurl = URL::site(NULL, 'http').'assets/img/fa-pencil-square.png';
								$featimg = !empty($featimgurl) ? '<img src="'.$featimgurl.'" width="50px;"/>' : '<img style="border:1px solid grey;" src="'.URL::site(NULL, 'http').'assets/img/fa-file-image-o.png'.'" width="50px;"/>';
								$content .='<tr style="background-color:'.($relkey % 2 == 0 ? '#FFFFFF' : '#F1F1F1').'">
									<td style="border-bottom: 0.5px dotted #000; border-left: 0.5px dotted #000; border-right: 0.5px dotted #000;border-radius: 0;">'.($relkey+1).'</td>
									<td style="border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000; vertical-align: middle;border-radius: 0">'.$featimg.'</td>
									<td style="border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000; vertical-align: middle;border-radius: 0;">'.(!empty($relvalue['title']) ? ucfirst($relvalue['title']) : '').'</td>
								</tr>';
							}
							$content .= '</tbody>
						</table>';
					}
				}
			}
			$content .= '</div>';
		}
		return $content;
	}
	
	
	public function action_pdfsharegenerator()
	{
		$type_workout = $_GET['workouttype'];
		$exportid_workout = $_GET['idexport'];
		$fromAdmin 		 = (isset($_GET['fromAdmin']) ? $_GET['fromAdmin'] : false);
		$workoutModel = ORM::factory('workouts');
		$repetitions = $workoutModel->getInnerDrive();
		$focusRecord = $workoutModel->getAllFocus();
		$user 		 = Auth::instance()->get_user();
		$siteid 	 = (Session::instance()->get('current_site_id') ? Session::instance()->get('current_site_id') : '0');
		$sitename 	 = (Session::instance()->get('current_site_name') ? Session::instance()->get('current_site_name') : '');
		$user_id 	 = $user->pk();
		/******************* Activity Feed *********************/
		$activity_feed = array();
		$activity_feed["action_type"]  	= 43;
		$activity_feed["user"]  		= $user_id; // user id
		$activity_feed["site_id"]  		= $siteid;
		/******************* Activity Feed *********************/
		if($fromAdmin){
			$user_id = 0;
		}
		if($type_workout == "logged"){
			$workoutRecord  = $workoutModel->getLoggedworkoutById($exportid_workout);
			$exerciseRecord = $workoutModel->getExerciseSets('wkoutlog',$exportid_workout);
			$activity_feed["feed_type"]   	= 11;
		}elseif($type_workout == "shared"){
			$workoutRecord  = $workoutModel->getShareworkoutById($user_id,$exportid_workout);
			$exerciseRecord = $workoutModel->getExerciseSets('shared',$exportid_workout);
			$activity_feed["feed_type"]   	= 12;
		}elseif($type_workout == "sample" || $type_workout == "default"){
			$workoutRecord	= $workoutModel->getSampleworkoutById('0', $exportid_workout);
			$exerciseRecord = $workoutModel->getSampleExerciseSet($exportid_workout);
			$activity_feed["feed_type"]   	= 15;
		}elseif($type_workout == "assigned"){
			$workoutRecord	= $workoutModel->getAssignworkoutById($exportid_workout, $user_id);
			$exerciseRecord = $workoutModel->getExerciseSets('assigned',$exportid_workout);
			$activity_feed["feed_type"]   	= 13;
		}else{
			$workoutRecord	= $workoutModel->getworkoutById($user_id,$exportid_workout);
			$exerciseRecord = $workoutModel->getExerciseSet($exportid_workout);
			$activity_feed["feed_type"]   	= 2;
		}
		$activity_feed["type_id"]    	= $exportid_workout;
		$activity_feed["json_data"]  	= json_encode('PDF');
		Helper_Common::createActivityFeed($activity_feed);
		$exerciseUnitsDetail  = array();
		$temporary		= array();
		if(isset($exerciseRecord) && count($exerciseRecord)>0){
			foreach($exerciseRecord as $keys => $val){
				$exerciseUnits  = array();
				if(!empty($val['goal_unit_id']) && !isset($temporary[$val['goal_unit_id']])){
					$temporary[$val['goal_unit_id']] = $val['goal_unit_id'];
					$exerciseRecordData	= $workoutModel->getExerciseById($val['goal_unit_id']);
					$exerciseUnits[$val['goal_unit_id'].'_data'] = $exerciseRecordData;
					$exerciseUnits[$val['goal_unit_id'].'_seqdata'] = $workoutModel->getSequencesByUnitId($val['goal_unit_id'], 0, 5);
					$exerciseUnits[$val['goal_unit_id'].'_relateddata'] = $workoutModel->getRelatedExercises($val['goal_unit_id'], $exerciseRecordData['musprim_id'], $exerciseRecordData['type_id'],0,5);
					$exerciseUnitsDetail[] = $exerciseUnits;
				}
			}
		}
		if(isset($workoutRecord) && $type_workout != "sample"){
			$fetch_field  = 'concat(user_fname," ",user_lname) as name';
         	$fetch_condtn = 'id=' . $workoutRecord['user_id'];
         	$result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('users', $fetch_field, $fetch_condtn);
			$result		  = $result[0];
			$workoutRecord["username"] = $result["name"];
		}
		
		$content = $this->contentworkoutshare($workoutRecord,$focusRecord,$exerciseRecord,$repetitions,$exerciseUnitsDetail);
		$title      = $workoutRecord['wkout_title'].' Details';
		$this->_generatePDF($content, $title);
	}
	
	public function _generatePDF($message, $title)
	{
		$this->auto_render = FALSE;	
		include("./plugins/mpdf60/mpdf.php");
		
		$mpdf=new mPDF('c','A4-L','','',20,20,20,20,10,10); 
		$mpdf->debug=true;
		$mpdf->SetTitle($title);				
		$mpdf->SetDisplayMode('fullwidth');				
		$mpdf->list_indent_first_level = 0;	// 1 or 0 - whether to indent the first level of a list
		$mpdf->SetHTMLFooter('<div style="text-align: center;">{PAGENO}</div>');
		$stylesheet = file_get_contents('./plugins/mpdf60/mpdfstyletables.css');
		$mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text				
		$mpdf->WriteHTML($message,2);
		$file_name = $title.'-'.date('Ymdhis');
		$mpdf->Output($file_name.'.pdf','I');
		//define('FILE_PATH' , $_SERVER['DOCUMENT_ROOT'].'/assets/uploads/');
		//$mpdf->Output(FILE_PATH .$file_name.'.pdf','F');
		exit;
	}
} // End Export
