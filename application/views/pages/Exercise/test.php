<?php 
$response .='<li data-role="none" class="bgC4" data-id='.$val['goal_id'].' data-module="item_set" id="itemSet_1_"'.$val['goal_id'].'_'.$keys.'"><div id="itemset_'.$workoutRecord['wkout_id'].'_'.$val['goal_id'].'" class="row"><input type="hidden" class="seq_order_up" id="goal_order_'.$val['goal_id'].'" name="goal_order['.$val['goal_id'].']" value="'.$keys.'"/><input type="hidden" id="goal_remove_'.$val['goal_id'].'" name="goal_remove['.$val['goal_id'].']" value="0"/><div class="mobpadding"><div class="border full"><div class="checkboxchoosen col-xs-2" style="display:none;"><div class="checkboxcolor" style="font-size:20px;"><label><input onclick="enableButtons();" data-role="none" data-ajax="false" type="checkbox" class="checkhidden" name="exercisesets[]" value="'.$val['goal_id']'"><span class="cr checkbox-circle"><i class="cr-icon fa fa-check"></i></span></label></div></div><div class="col-xs-8 navdescrip"><div class="col-xs-4 navimage activelink datacol" '.($val['goal_unit_id']) ? 'onclick="getTemplateOfExerciseRecordAction('."'".$val['goal_unit_id']."',this".');"' : '').'>';
if($val['goal_unit_id']) {
	if(file_exists($val['img_url'])){ 
		$response .='<img width="50px" class="img-responsive pointers" src="'.URL::base(TRUE).$val['img_url'].'" title="'.ucfirst($val['img_title']).'"/>';
	}else{
		$response .='<i class="fa fa-file-image-o pointers" style="font-size:50px;"></i>';
	}
}else {
	$response .='<i class="fa fa-pencil-square" style="font-size:50px;"></i>';
}
$response .='</div><div class="pointers activelink datacol" onclick="editWorkoutRecord('."'".$val['goal_id']."','preview'",');"><div class="navimagedetails"><div class="navimgdet1"><b>'.(($val['goal_alt'] > 0) ? '<span>Alt</span> ' : '').ucfirst($val['goal_title']).'</b></div><div class="navimgdet2">';
$parameter = $parameter1 =  '' ;$hidden_time ="00:00:00";$hidden_rest_time ="00:00"; $flag = false;
$response .= '<a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail datacol exercise_time_div">';
if($val['goal_time_hh']>0 || $val['goal_time_mm'] >0 || $val['goal_time_ss']  >0){
	$response .= (($val['primary_time']) ? '<span class="ashstrick">*</span> ' : '').'<span>'.substr(sprintf("%02d", $val['goal_time_hh']),0,2).':'.substr(sprintf("%02d", $val['goal_time_mm']),0,2).':'.substr(sprintf("%02d", $val['goal_time_ss']),0,2).'</span>';
	$hidden_time = substr(sprintf("%02d", $val['goal_time_hh']),0,2).':'.substr(sprintf("%02d", $val['goal_time_mm']),0,2).':'.substr(sprintf("%02d", $val['goal_time_ss']),0,2);
	$flag = true;
}
$response .= '</a><a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail datacol exercise_distance_div">';
if($val['goal_dist']>0 && $val['goal_dist_id']>0){
	$response .= ($flag ? ' /// ' : '').(($val['primary_dist']) ? '<span class="ashstrick">*</span> ' : '').$val['goal_dist'].' <span>'.Model::instance('Model/workouts')->getGoalValues('dist',$val['goal_dist_id']).'</span>';
	$flag = true;
}
$response .= '</a><a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail datacol exercise_repetitions_div">';
if($val['goal_reps']>0){
	$response .= ($flag ? ' /// ' : '').(($val['primary_reps']) ? '<span class="ashstrick">*</span> ' : '').$val['goal_reps'].' <span>reps</span>';
	$flag = true;
}
$response .= '</a><a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail datacol exercise_resistance_div">';
if($val['goal_resist']>0 && $val['goal_resist_id']>0){
	$response .= ($flag ? ' /// ' : '').(($val['primary_resist']) ? '<span class="ashstrick">*</span> ' : '').' x '.$val['goal_resist'].' <span>'.Model::instance('Model/workouts')->getGoalValues('resist',$val['goal_resist_id']).'</span>';
}
$response .= '</a></div><div class="navimgdet3 datacol">';
$response .= '<a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail datacol exercise_rate_div">';
if($val['goal_rate']>0 && $val['goal_rate_id']>0){
	$response .= (($val['primary_rate']) ? '<span class="ashstrick">*</span> ' : '').'<span>@'.$val['goal_rate'].' '.Model::instance('Model/workouts')->getGoalValues('rate',$val['goal_rate_id']).'</span> ';
}
$response .= '</a>&nbsp;<a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail datacol exercise_angle_div">';
if($val['goal_angle']>0 && $val['goal_angle_id']>0){
	$response .= (($val['primary_angle']) ? '<span class="ashstrick">*</span> ' : '').'<span>'.$val['goal_angle'].'%'.Model::instance('Model/workouts')->getGoalValues('angle',$val['goal_angle_id']).'</span> ';
}
$response .= '</a>&nbsp;<a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail datacol exercise_innerdrive_div">';
$intvalue = Model::instance('Model/workouts')->getGoalValues('int',$val['goal_int_id']);
if($intvalue>0){
	$response .= (($val['primary_int']) ? '<span class="ashstrick">*</span> ' : '').'<span>'.$intvalue.' int</span>';
}
$response .= '</a>&nbsp;<a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail datacol exercise_rest_div">';
if($val['goal_rest_mm'] + $val['goal_rest_ss']>0){
	if($val['goal_rest_mm'] >0 ||  $val['goal_rest_ss']>0){
			$response .=  (($val['primary_rest']) ? '<span class="ashstrick">*</span> ' : '').' <span>'.$val['goal_rest_mm'];
			$totalRest += $val['goal_rest_mm'] * 60;
		if($val['goal_rest_ss']>0 && $val['goal_rest_ss'] < 10)
			$response .=  ':0'.$val['goal_rest_ss'];
		else
			$response .=  ':'.substr(sprintf("%02d", $val['goal_rest_ss']),0,2);
		$hidden_rest_time = $val['goal_rest_mm'].':'.substr(sprintf("%02d", $val['goal_rest_ss']),0,2);
		$totalRest += $val['goal_rest_ss'];
		$response .=  ' rest</span>';
	}
}
$response .= '</a></div><div class="navimgdet4"></div></div></div></div><div class="col-xs-2 navbarmenu"><a data-ajax="false" class="editchoosenIconTwo hide" href="javascript:void(0)" style="cursor: move;"><i class="fa fa-bars panel-draggable"></i></a><i class="fa fa-ellipsis-h iconsize listoptionpop hide" class="navbar-toggle" onclick="getTemplateOfExerciseSetAction('."'".$val['goal_id']."','link'".');"></i><input type="hidden" id="exercise_title_'.$val['goal_id'].'" name="exercise_title['.$val['goal_id'].']" value="'.$val['goal_title'].'"/><input type="hidden" id="exercise_unit_'.$val['goal_id'].'" name="exercise_unit['.$val['goal_id'].']" value="'.$val['goal_unit_id'].'"/><input type="hidden" id="exercise_resistance_'.$val['goal_id'].'" name="exercise_resistance['.$val['goal_id'].']" value="'.$val['goal_resist'].'"/><input type="hidden" id="exercise_unit_resistance_'.$val['goal_id'].'" name="exercise_unit_resistance['.$val['goal_id'].']" value="'.$val['goal_resist_id'].'"/><input type="hidden" id="exercise_repetitions_'.$val['goal_id'].'" name="exercise_repetitions['.$val['goal_id'].']" value="'.$val['goal_reps'].'"/><input type="hidden" id="exercise_time_'.$val['goal_id'].'" name="exercise_time['.$val['goal_id'].']" value="'.$hidden_time.'"/><input type="hidden" id="exercise_distance_'.$val['goal_id'].'" name="exercise_distance['.$val['goal_id'].']" value="'.$val['goal_dist'].'"/><input type="hidden" id="exercise_unit_distance_'.$val['goal_id'].'" name="exercise_unit_distance['.$val['goal_id'].']" value="'.$val['goal_dist_id'].'"/><input type="hidden" id="exercise_rate_'.$val['goal_id'].'" name="exercise_rate['.$val['goal_id'].']" value="'.$val['goal_rate'].'"/><input type="hidden" id="exercise_unit_rate_'.$val['goal_id'].'" name="exercise_unit_rate['.$val['goal_id'].']" value="'.$val['goal_rate_id'].'"/><input type="hidden" id="exercise_innerdrive_'.$val['goal_id'].'" name="exercise_innerdrive['.$val['goal_id'].']" value="'.$val['goal_int_id'].'"/><input type="hidden" id="exercise_angle_'.$val['goal_id'].'" name="exercise_angle['.$val['goal_id'].']" value="'.$val['goal_angle'].'"/><input type="hidden" id="exercise_unit_angle_'.$val['goal_id'].'" name="exercise_unit_angle['.$val['goal_id'].']" value="'.$val['goal_angle_id'].'"/><input type="hidden" id="exercise_rest_'.$val['goal_id'].'" name="exercise_rest['.$val['goal_id'].']" value="'.$hidden_rest_time.'"/><input type="hidden" id="exercise_remark_'.$val['goal_id'].'" name="exercise_remark['.$val['goal_id'].']" value="'.$val['goal_remarks'].'"/><input type="hidden" id="primary_time_'.$val['goal_id'].'" name="primary_time['.$val['goal_id'].']" value="'.$val['primary_time'].'"/><input type="hidden" id="primary_dist_<?php echo $val['goal_id'];?>" name="primary_dist[<?php echo $val['goal_id'];?>]" value="'.$val['primary_dist'].'"/><input type="hidden" id="primary_reps_'.$val['goal_id'].'" name="primary_reps['.$val['goal_id'].']" value="'.$val['primary_reps'].'"/><input type="hidden" id="primary_resist_'.$val['goal_id'].'" name="primary_resist['.$val['goal_id'].']" value="'.$val['primary_resist'].'"/><input type="hidden" id="primary_rate_'.$val['goal_id'].'" name="primary_rate['.$val['goal_id'].']"  value="'.$val['primary_rate'].'"/><input type="hidden" id="primary_angle_'.$val['goal_id'].'" name="primary_angle['.$val['goal_id'].']" value="'.$val['primary_angle'].'"/><input type="hidden" id="primary_rest_'.$val['goal_id'].'" name="primary_rest['.$val['goal_id'].']" value="'.$val['primary_rest'].'"/><input type="hidden" id="primary_int_'.$val['goal_id'].'" name="primary_int['.$val['goal_id'].']" value="'.$val['primary_int'].'"/></div></div></div></div></li>';
?>