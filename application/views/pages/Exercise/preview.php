<table style="width:100%">
	<tr>
		<td width="92%">
			<table  style="width:30%">
				<tr>
					<td><?php echo __('Name'); ?></td>
					<td style="border-bottom: 0.5px dotted black;">test user</td>
				</tr>
				<tr>
					<td><?php echo __('Date'); ?></td>
					<td style="border-bottom:0.5px dotted black;"><?php echo Helper_Common::UserDateFormat(); ?></td>
				</tr>
				<tr>
					<td><?php echo __('Workout Plan'); ?></td>
					<td style="border-bottom:0.5px dotted black;"><?php echo ucfirst($workoutRecord['wkout_title']);?></td>
				</tr>
				<tr>
					<td><?php echo __('Workout Focus'); ?></td>
					<td style="border-bottom:0.5px dotted black;"><?php foreach($focusRecord as $keys => $values){ if($values['focus_id'] == $workoutRecord['wkout_focus']) echo ucfirst($values['focus_opt_title']);};?></td>
				</tr>
			</table>
		</td>
		<td valign="top">
			<table style="text-align:right">
				<tr>
					<td>
						<a data-ajax="false" href="<?php echo URL::base(TRUE).'index'; ?>" style="height:50px;display:table;text-decoration:none;"><img src="<?php echo URL::base(TRUE).'assets/img/moblogo.png'; ?>" width="50px;"/><span style="display:table-cell;vertical-align:middle;"><?php echo __('Workout Plan'); ?></span></a>
					</td>
				</tr>
				<tr>
					<td><?php echo __('Site Title'); ?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2" width="100%" style="padding-top:10px;">
			<table width="100%">
				<tr>
					<th style="border-bottom: 0.5px dotted black;border-right: 0.5px dotted black;"><?php echo __('Set'); ?></th>
					<th style="border-bottom: 0.5px dotted black;border-right: 0.5px dotted black;"><?php echo __('Exercise'); ?></th>
					<th style="border-bottom: 0.5px dotted black;border-right: 0.5px dotted black;"><?php echo __('Repetitions'); ?></th>
					<th style="border-bottom: 0.5px dotted black;border-right: 0.5px dotted black;"><?php echo __('Resistance'); ?></th>
					<th style="border-bottom: 0.5px dotted black;border-right: 0.5px dotted black;"><?php echo __('Time'); ?></th>
					<th style="border-bottom: 0.5px dotted black;border-right: 0.5px dotted black;"><?php echo __('Distance'); ?></th>
					<th style="border-bottom: 0.5px dotted black;border-right: 0.5px dotted black;"><?php echo __('Rate'); ?></th>
					<th style="border-bottom: 0.5px dotted black;border-right: 0.5px dotted black;"><?php echo __('Inner Drive'); ?></th>
					<th style="border-bottom: 0.5px dotted black;border-right: 0.5px dotted black;"><?php echo __('Angle'); ?></th>
					<th style="border-bottom: 0.5px dotted black;border-right: 0.5px dotted black;"><?php echo __('Rest After'); ?></th>
					<th style="border-bottom: 0.5px dotted black;"><?php echo __('Remarks'); ?></th>
				</tr>
	<?php if(isset($exerciseRecord) && count($exerciseRecord)>0){
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
	?>
			<tr style="background-color:<?php echo ($keys % 2 == 0 ? '#FFFFFF' : '#F1F1F1');?> ">
				<td style="border-bottom: 0.5px dotted black;border-left: 0.5px dotted black;border-right: 0.5px dotted black;"><?php echo $keys+1;?></td>
				<td style="border-bottom: 0.5px dotted black;border-right: 0.5px dotted black;">
					<table>
						<tr>
							<td width="50px">
								<div style="width: 100%;background: transparent url('<?php echo $styleBgurl;?>')  no-repeat scroll center top / cover ; max-width: 50px; height: 44px;"></div>
							</td>
							<td><?php echo ucfirst($values['goal_title']);?></td>
							<td>#</td>
						</tr>
					</table>
				</td>
				<td style="border-bottom: 0.5px dotted black;border-right: 0.5px dotted black;"><?php echo $reps;?></td>
				<td style="border-bottom: 0.5px dotted black;border-right: 0.5px dotted black;"><?php echo $resist;?></td>
				<td style="border-bottom: 0.5px dotted black;border-right: 0.5px dotted black;"><?php echo $time;?></td>
				<td style="border-bottom: 0.5px dotted black;border-right: 0.5px dotted black;"><?php echo $dist;?></td>
				<td style="border-bottom: 0.5px dotted black;border-right: 0.5px dotted black;"><?php echo $rate;?></td>
				<td style="border-bottom: 0.5px dotted black;border-right: 0.5px dotted black;"><?php echo $int;?></td>
				<td style="border-bottom: 0.5px dotted black;border-right: 0.5px dotted black;"><?php echo $angle;?></td>
				<td style="border-bottom: 0.5px dotted black;border-right: 0.5px dotted black;"><?php echo $rest;?></td>
				<td style="border-bottom: 0.5px dotted black;border-right: 0.5px dotted black;"><?php echo $values['goal_remarks'];?></td>
			</tr>
				
	<?php } 
		} ?>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2" width="100%">
			<?php echo __('Notes for this Workout(Overall)'); ?>:
		</td>
	</tr>
	<tr>
		<td>
			<table>
				<tr>
					<td width="150px;"><?php echo __('Perceived Intensity'); ?>:</td>
					<td valign="left">
						<table style="text-align:left" width="80%">
							<tr>
								<?php if(isset($repetitions) && count($repetitions)>0){
										foreach($repetitions as $keys => $values){ 
											if((int) $values['int_opt_id'] == $values['int_opt_id']){ ?>
									<td width="150" style="border-right: 0.5px dotted black;height:70px"><span style="width:100%;<?php echo (round($workoutRecord['note_wkout_intensity']) >= $values['int_opt_id'] ? 'background-color:black': '');?>">..............................</span></td>
								<?php 		}
										 }
									} ?>
									<td width="150" style="height:70px"><span style="width:100%">..............................</span></td>
							</tr>
							<tr>
								<?php if(isset($repetitions) && count($repetitions)>0){
										foreach($repetitions as $keys => $values){ 
											if((int) $values['int_opt_id'] == $values['int_opt_id']){ ?>
									<td style="white-space: -moz-pre-wrap !important;white-space: -pre-wrap;white-space: -o-pre-wrap;white-space: pre-wrap;word-wrap: break-word;white-space: -webkit-pre-wrap;word-break: break-all;white-space: normal;"><span ><?php echo ucfirst($values['int_grp_title']);?></span></td>
								<?php 		}
										} 
									} ?>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2" width="100%"><?php echo __('Remarks'); ?>:</td>
	</tr>
	<tr>
		<td width="100%" colspan="2" style="border:1px dotted black;"><?php echo $workoutRecord['note_wkout_remarks'];?></td>
	</tr>
</table>