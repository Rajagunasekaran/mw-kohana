<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Print extends Controller_Admin_Website {

	public function _construct() {
         parent::__construct($request, $response);
    } 
		
	public function action_index()
	{
		$this->redirect('Dashboard/index');
	}
	public function contentworkoutshare($workoutRecord,$focusRecord,$exerciseRecord,$repetitions){
		
		$site_title = "";
		$fetch_field  = 'name';
		$fetch_condtn = 'id=' . $this->current_site_id;
		$result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('sites', $fetch_field, $fetch_condtn);
		if($result)
			$site_title		  = $result[0]["name"];
		
		$content = '<table style="width:100%;font-size:30px;" border=0 align="center">
			<tr><td width="50%">
				<table style="width:30%" border=0>
					<tr>
						<td>Name</td>
						<td class="dotted">'.$workoutRecord['username'].'</td>
					</tr>
					<tr>
						<td>Date</td>
						<td class="dotted">'.Helper_Common::get_default_date('','d M Y').'</td>
					</tr>
					<tr>
						<td>Workout Plan</td>
						<td class="dotted">'.ucfirst($workoutRecord['wkout_title']).'</td>
					</tr>
					<tr>
						<td>Workout Focus</td>
						<td class="dotted">';
						foreach($focusRecord as $keys => $values){ 
							if($values['focus_id'] == $workoutRecord['wkout_focus']) 
							$content .= ucfirst($values['focus_opt_title']);
						};
		   $content .= '</td>
					</tr>
				</table>
				</td>
				<td width="50%" >
				 	<table style="text-align:right" border=0>
						<tr>
							<td style="vertical-align:middle;"  valign="middle">
								<a data-ajax="false" href="'.URL::base(TRUE).'index'.'" style="height:50px;text-decoration:none;">
								<img src="'.URL::base(TRUE).'assets/img/moblogo.png'.'" width="50px;"/> <span style="display:ruby-text" > Workout Plan</span></a>
							</td>
						</tr>
						<tr>
							<td>'.$site_title.'</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td colspan="2" width="100%" style="padding-top:25px;">
				<table width="100%" style="font-size:18px;">
					<tr>
						<th class="dotted-br5">Set</th>
						<th class="dotted-br20">Exercise</th>
						<th class="dotted-br">Repetitions</th>
						<th class="dotted-br">Resistance</th>
						<th class="dotted-br">Time</th>
						<th class="dotted-br">Distance</th>
						<th class="dotted-br">Rate</th>
						<th class="dotted-br">Inner Drive</th>
						<th class="dotted-br">Angle</th>
						<th class="dotted-br">Rest After</th>
						<th class="dotted-b">Remarks</th>
					</tr>';
				if(isset($exerciseRecord) && count($exerciseRecord)>0){
					foreach($exerciseRecord as $keys => $values){
						$reps = $resist = $time = $dist = $rate = $int = $angle = $xr = $rest = $remark = $styleBgurl = '' ;
						if(!empty($values['goal_unit_id']) && file_exists($values['img_url']))
							$styleBgurl = URL::base(TRUE).$values['img_url'];
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
						$xrimg = !empty($styleBgurl) ? '<img src="'.$styleBgurl.'" width="75px;"/>' : '';
						$content .='<tr style="background-color:'.($keys % 2 == 0 ? '#FFFFFF' : '#F1F1F1').'">
							<td class="dotted-blr">'.($keys+1).'</td>
							<td class="dotted-br">
								<table style="width:100%;">
									<tr>
										<td style="width:20%; padding-right:10px; height: 70px;">'.$xrimg.'</td>
										<td style="vertical-align:middle;">'.ucfirst($values['goal_title']).'</td>
										<td style="vertical-align:middle;">#</td>
									</tr>
								</table>
							</td>
							<td class="dotted-br">'.$reps.'</td>
							<td class="dotted-br">'.$resist.'</td>
							<td class="dotted-br">'.$time.'</td>
							<td class="dotted-br">'.$dist.'</td>
							<td class="dotted-br">'.$rate.'</td>
							<td class="dotted-br">'.$int.'</td>
							<td class="dotted-br">'.$angle.'</td>
							<td class="dotted-br">'.$rest.'</td>
							<td class="dotted-br">'.$values['goal_remarks'].'</td>
						</tr>';
					}
				} 
			$content .='</table>
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
						<table style="width:100%;">
							<tr>
								<td style="width:15%; vertical-align: middle;">Perceived Intensity:</td>
								<td style="width:85%;">
									<table style="text-align:left;border-collapse: collapse;" width="100%">
										<tr>';
											if(isset($repetitions) && count($repetitions)>0){
												foreach($repetitions as $keys => $values){ 
													if((int) $values['int_opt_id'] == $values['int_opt_id']){ 
														$content .='<td width="150px" style="border-right: 0.5px dotted black;'.(isset($workoutRecord['note_wkout_intensity']) && round($workoutRecord['note_wkout_intensity']) >= $values['int_opt_id'] ? 'border-bottom: 3px dotted green;' : 'border-bottom: 1px dotted black;').'"></td>';
													}
								 				}
											}
											$content .='<td width="150px" style="height:40px;border-bottom: 1px dotted black;"></td>
										</tr>
										<tr>';
											if(isset($repetitions) && count($repetitions)>0){
												foreach($repetitions as $keys => $values){ 
													if((int) $values['int_opt_id'] == $values['int_opt_id']){ 
														$content .='<td width="150px" style="border-right: 0.5px dotted black;'.( isset($workoutRecord['note_wkout_intensity']) && round($workoutRecord['note_wkout_intensity']) >= $values['int_opt_id'] ? 'border-top: 3px dotted green;' : 'border-top: 1px dotted black;').'"></td>';
													}
								 				}
											}
											$content .='<td width="150px" style="height:40px;border-top: 1px dotted black;"></td>
										</tr>
										<tr>';
										 	if(isset($repetitions) && count($repetitions)>0){
												foreach($repetitions as $keys => $values){ 
													if((int) $values['int_opt_id'] == $values['int_opt_id']){
														$content .='<td class="btm-txt" style="text-align:right;font-size:25x;">'.ucfirst($values['int_grp_title']).'</td>';
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
				<td colspan="2" width="100%">Remarks / Notes:</td>
			</tr>
			<tr>
				<td width="100%" colspan="2" style="border:1px dotted black;height:300px;">'.( (isset($workoutRecord['note_wkout_remarks']))?$workoutRecord['note_wkout_remarks']:'' ).'</td>
			</tr>
		</table>';
		return $content;
		
	}
	
	
	public function action_workouts()
	{
		$type_workout = $_GET['type'];
		$exportid_workout = $_GET['id'];
		$workoutModel = ORM::factory('workouts');
		
		
		$repetitions = $workoutModel->getInnerDrive();
		$focusRecord = $workoutModel->getAllFocus();
		
		/*********************Activiy Feed**********************/
		$activity_feed = array(); 		
		if($type_workout == "logged"){
			$workoutRecord  = $workoutModel->getLoggedworkoutById($exportid_workout);
			$exerciseRecord = $workoutModel->getExerciseSets('wkoutlog',$exportid_workout);
			$activity_feed["feed_type"]   = 7;  // This get from feed_type table
		}elseif($type_workout == "workout"){
			$workoutRecord  = $workoutModel->getworkoutById('',$exportid_workout);
			$exerciseRecord = $workoutModel->getExerciseSets('wkout',$exportid_workout);
			$activity_feed["feed_type"]   = 2;  // This get from feed_type table
		}elseif($type_workout == "sampleworkout"){
			$workoutRecord  = $workoutModel->getSampleworkoutById('',$exportid_workout);
			$exerciseRecord = $workoutModel->getExerciseSets('sample',$exportid_workout);
			$activity_feed["feed_type"]   = 15;  // This get from feed_type table
		}
		
		if(isset($workoutRecord) ){
			$fetch_field  = 'concat(user_fname," ",user_lname) as name';
         $fetch_condtn = 'id=' . $workoutRecord['user_id'];
         $result       = Model::instance('Model/admin/user')->get_table_details_by_condtn('users', $fetch_field, $fetch_condtn);
			$result		  = $result[0];
			$workoutRecord["username"] = $result["name"];
		}
		
		$content = $this->contentworkoutshare($workoutRecord,$focusRecord,$exerciseRecord,$repetitions);
		//echo $content;exit;
		if($content){
			$content.="<script type='text/javascript'>window.print();</script>";
			
			/*********************Activiy Feed**********************/
			$activity_feed["action_type"]  = 16; // This get from action_type table  
			$activity_feed["type_id"]    = $exportid_workout; // Workout Id or User id or Exercise setid or image id or workout folder id or tag id or etc
			$activity_feed["created_date"]  = $datetime;   $activity_feed["modified_date"]  = $datetime;
			$activity_feed["user"]     = $this->globaluser->pk();
			$activity_result = DB::insert('activity_feed', array_keys($activity_feed) )->values(array_values($activity_feed))->execute();
			/*********************Activiy Feed**********************/

			
		}
		echo $content;exit;
		//$this->_generatePDF($content, 'Test PDF');
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
		$stylesheet = file_get_contents(URL::base(TRUE).'plugins/mpdf60/mpdfstyletables.css');
		$mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text				
		$mpdf->WriteHTML($message,2);
		$file_name = 'Logged-'.$title.'-'.date('Ymdhis');
		$mpdf->Output($file_name.'.pdf','I');
		//define('FILE_PATH' , $_SERVER['DOCUMENT_ROOT'].'/assets/uploads/');
		//$mpdf->Output(FILE_PATH .$file_name.'.pdf','F');
		exit;
		//return $file_name;
	}
} // End Export
