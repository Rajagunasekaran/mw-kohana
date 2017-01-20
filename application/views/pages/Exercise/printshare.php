<script>
window.onload = function () {
    window.print();
}
</script>
<table style="width:100%;">
	<tr>
		<td style="width:50%">
			<table style="width:60%">
				<tr>
					<td><?php echo __('Name'); ?></td>
					<td style="border-bottom: 0.5px dotted #000;border-radius: 0;">test user</td>
				</tr>
				<tr>
					<td><?php echo __('Date'); ?></td>
					<td style="border-bottom: 0.5px dotted #000;border-radius: 0;"><?php echo Helper_Common::UserDateFormat();?></td>
				</tr>
				<tr>
					<td><?php echo __('Workout Plan'); ?></td>
					<td style="border-bottom: 0.5px dotted #000;border-radius: 0;"><?php echo ucfirst($workoutRecord['wkout_title']);?></td>
				</tr>
				<tr>
					<td><?php echo __('Workout Focus'); ?></td>
					<td style="border-bottom: 0.5px dotted #000;border-radius: 0;"> 
						<?php foreach($focusRecord as $keys => $values){ 
							if($values['focus_id'] == $workoutRecord['wkout_focus']) echo ucfirst($values['focus_opt_title']);
						}; ?>
					</td>
				</tr>
			</table>
		</td>
		<td style="width:50%">
			<table style="width:100%;text-align:right;">
				<tr>
					<td>
						<a data-ajax="false" data-role="none" href="<?php echo URL::base(TRUE).'index'; ?>" style="text-decoration:none;display:block;"><img src="<?php echo URL::site('assets/img/moblogo.png'); ?>" width="50px;"/></a>
					</td>
					<td style="vertical-align:middle;color: #000;font-weight: bold;font-size: 16px;margin: auto;width:28%"><?php echo __('Workout Plan'); ?></td>
				</tr>
				<tr>
					<td></td><td><?php echo __('Site Title'); ?></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<div style="padding-top:25px;"></div>
<table width="100%" style="font-size:14px;">
	<thead>
		<tr>
			<th style="border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000; color: #1b9af7;width:10px;border-radius: 0;"><?php echo __('Set'); ?></th>
			<th style="border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000; color: #1b9af7;width:150px;border-radius: 0;"><?php echo __('Exercise'); ?></th>
			<th style="border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000; color: #1b9af7;width:100px;border-radius: 0;word-wrap: normal;word-break: normal;white-space: normal;"><?php echo __('Repetitions'); ?></th>
			<th style="border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000; color: #1b9af7;width:100px;border-radius: 0;word-wrap: normal;word-break: normal;white-space: normal;"><?php echo __('Resistance'); ?></th>
			<th style="border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000; color: #1b9af7;width:100px;border-radius: 0;word-wrap: normal;word-break: normal;white-space: normal;"><?php echo __('Time'); ?></th>
			<th style="border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000; color: #1b9af7;width:100px;border-radius: 0;word-wrap: normal;word-break: normal;white-space: normal;"><?php echo __('Distance'); ?></th>
			<th style="border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000; color: #1b9af7;width:100px;border-radius: 0;word-wrap: normal;word-break: normal;white-space: normal;"><?php echo __('Rate'); ?></th>
			<th style="border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000; color: #1b9af7;width:100px;border-radius: 0;word-wrap: normal;word-break: normal;white-space: normal;"><?php echo __('Inner Drive'); ?></th>
			<th style="border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000; color: #1b9af7;width:100px;border-radius: 0;word-wrap: normal;word-break: normal;white-space: normal;"><?php echo __('Angle'); ?></th>
			<th style="border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000; color: #1b9af7;width:100px;border-radius: 0;word-wrap: normal;word-break: normal;white-space: normal;"><?php echo __('Rest After'); ?></th>
			<th style="border-bottom: 0.5px dotted #000; color: #1b9af7;width: 150px;border-radius: 0;"><?php echo __('Remarks'); ?></th>
		</tr>
	</thead>
	<tbody>
	<?php if(isset($exerciseRecord) && count($exerciseRecord)>0){
	foreach($exerciseRecord as $keys => $values){
		$reps = $resist = $time = $dist = $rate = $int = $angle = $xr = $rest = $remark = $styleBgurl = '' ;
		if(!empty($values['goal_unit_id'])){
			if(file_exists($values['img_url']))
				$styleBgurl = URL::site($values['img_url']);
			else
				$styleBgurl = URL::site('assets/img/fa-file-image-o.png');
		}else{
			$styleBgurl = URL::site('assets/img/fa-pencil-square.png');
		}
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
		$xrimg = !empty($styleBgurl) ? '<img style="border:1px solid grey;" src="'.$styleBgurl.'" width="70px;"/>' : '';
		?>
			<tr style="background-color:<?php echo ($keys % 2 == 0 ? '#FFFFFF' : '#F1F1F1');?> ">
				<td style="border-bottom: 0.5px dotted #000; border-left: 0.5px dotted #000; border-right: 0.5px dotted #000;border-radius: 0;"><?php echo $keys+1;?></td>
				<td style="border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000; vertical-align: middle;border-radius: 0">
					<table style="width:100%;">
						<tr>
							<td style="width:20%; padding-right:10px;"><?php echo $xrimg; ?></td>
							<td style="vertical-align:middle;"><?php echo ucfirst($values['goal_title']);?></td>
							<td style="vertical-align:middle;text-align: right;">[<a style="text-decoration:none;" data-ajax="false" href="#ExrId<?php echo $values['goal_unit_id']; ?>"><?php echo __('Ref.ID'); ?><?php echo $values['goal_unit_id']; ?></a>]</td>
						</tr>
					</table>
				</td>
				<td style="border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000; vertical-align: middle;border-radius: 0"><?php echo $reps;?></td>
				<td style="border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000; vertical-align: middle;border-radius: 0"><?php echo $resist;?></td>
				<td style="border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000; vertical-align: middle;border-radius: 0"><?php echo $time;?></td>
				<td style="border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000; vertical-align: middle;border-radius: 0"><?php echo $dist;?></td>
				<td style="border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000; vertical-align: middle;border-radius: 0"><?php echo $rate;?></td>
				<td style="border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000; vertical-align: middle;border-radius: 0"><?php echo $int;?></td>
				<td style="border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000; vertical-align: middle;border-radius: 0"><?php echo $angle;?></td>
				<td style="border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000; vertical-align: middle;border-radius: 0"><?php echo $rest;?></td>
				<td style="border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000; vertical-align: middle;border-radius: 0"><?php echo $values['goal_remarks'];?></td>
			</tr>
	<?php } 
		} ?>
	</tbody>
</table>
<table style="width:100%;font-size:14px;">
	<tr>
		<td colspan="2" width="100%" style="padding-top:25px;">
		</td>
	</tr>
	<tr>
		<td colspan="2" style="width:100%;padding-top:25px;padding-bottom:20px;">
			<?php echo __('Notes for this Workout(Overall)'); ?>:
		</td>
	</tr>
	<tr>
		<td style="width:100%;">
			<table style="width:100%;">
				<tr>
					<td style="width:15%; vertical-align: middle;"><?php echo __('Perceived Intensity'); ?>:</td>
					<?php if(isset($workoutRecord['note_wkout_remarks'])) { ?>
					<td style="width:85%;">
						<table style="text-align:left;border-collapse: collapse;page-break-inside:avoid;" width="100%">
							<tr>
								<?php if(isset($repetitions) && count($repetitions)>0){
									foreach($repetitions as $keys => $values){ 
										if((int) $values['int_opt_id'] == $values['int_opt_id']){  ?>
											<td width="150px" style="border-right: 0.5px dotted black; border-radius: 0;<?php echo (isset($workoutRecord['note_wkout_intensity']) && round($workoutRecord['note_wkout_intensity']) >= $values['int_opt_id'] ? 'border-bottom: 5px dotted green;' : 'border-bottom: 1px dotted black;'); ?>">
											</td>
										<?php }
									}
								}else{ ?>
									
								<?php } ?>
								<td width="150px" style="height:40px;border-bottom: 1px dotted black;border-radius: 0;"></td>
							</tr>
							<tr>
								<?php if(isset($repetitions) && count($repetitions)>0 ){
									foreach($repetitions as $keys => $values){ 
										if((int) $values['int_opt_id'] == $values['int_opt_id']){ ?>
											<td width="150px" style="border-right: 0.5px dotted black;border-radius: 0;<?php echo ( isset($workoutRecord['note_wkout_intensity']) && round($workoutRecord['note_wkout_intensity']) >= $values['int_opt_id'] ? 'border-top: 5px dotted green;' : 'border-top: 1px dotted black;'); ?>">
											</td>
										<?php }
									}
								} ?>
								<td width="150px" style="height:40px;border-top: 1px dotted black; border-radius: 0;"></td>
							</tr>
							<tr>
								<?php if(isset($repetitions) && count($repetitions)>0){
									foreach($repetitions as $keys => $values){ 
										if((int) $values['int_opt_id'] == $values['int_opt_id']){ ?>
											<td style="text-align:right;border-radius: 0;"><?php echo ucfirst($values['int_grp_title']); ?></td>
										<?php }
									} 
								} ?>
							</tr>
						</table>
					</td>
					<?php }else{ ?>
					<td style="width:85%;"><?php echo __('n/a'); ?></td>
					<?php } ?>
				</tr>
			</table>
		</td>
	</tr>
	<?php if(isset($workoutRecord['note_wkout_remarks'])) { ?>
	<tr>
		<td colspan="2" width="100%" style="padding-top:25px;"><?php echo __('Remarks / Notes'); ?>:</td>
	</tr>
	<tr>
		<td width="100%" colspan="2" style="border:1px dotted black;height:100px;"><?php echo $workoutRecord['note_wkout_remarks'];?></td>
	</tr>
	<?php } ?>
</table><div style="padding-bottom:25px;">&nbsp;</div>
<!-- xr record detailing section -->
<?php foreach ($exerciseUnitsDetail as $key => $value) { ?>
	<div id="<?php echo $key; ?>" style="border:1px dotted #000;padding:5px 10px;page-break-before:always;">
	<?php $cnt = 0;
	foreach ($value as $unitkey => $unitvalue) {
		if(strripos($unitkey,"_data")){
			if(!empty($unitvalue) && count($unitvalue)>0){
				$cnt++; ?>
				<div style="vertical-align:middle;color: #000;font-weight: bold;font-size: 16px;margin: auto;padding-bottom:3px;"><?php echo $cnt; ?>. <?php echo __('Exercise Record'); ?></div><a name="ExrId<?php echo $unitvalue['unit_id']; ?>" style="display:none;"></a>
				<table width="100%" style="border: 0.5px dotted #000;border-radius: 0;vertical-align:middle;" id="ExrId<?php echo $unitvalue['unit_id']; ?>" style="font-size:16px;">
					<tbody>
						<tr>
							<td style="color: #000000;font-weight: 500;border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000;"><?php echo __('Exercise Title'); ?></td>
							<td style="vertical-align:middle;border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000; color: #1b9af7;"><?php echo ucfirst($unitvalue['title']); ?></td>							
						</tr>
						<tr>
							<td style="color: #000000;font-weight: 500;border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000;"><?php echo __('Exercise Type'); ?></td>
							<td style="vertical-align:middle;border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000; color: #1b9af7;"><?php echo (!empty($unitvalue['type_title']) ? ucfirst($unitvalue['type_title']) : ''); ?></td>						
						</tr>
						<tr>
							<?php 
								$xrimgurl = '' ;
								if(!empty($unitvalue['unit_id'])){
									if(file_exists($unitvalue['img_url']))
										$xrimgurl = URL::site($unitvalue['img_url']);
									else
										$xrimgurl = URL::site('assets/img/fa-file-image-o.png');
								}else{
									$xrimgurl = URL::site('assets/img/fa-pencil-square.png');
								}
								$xrimg = !empty($xrimgurl) ? '<img style="border:1px solid grey;" src="'.$xrimgurl.'" width="100px;"/>' : ''; 
							?>
							<td colspan="2">
								<table style="width:100%;">
									<tr>
										<td style="width:20%; padding-right:10px; height: 50px;border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000;"><?php echo $xrimg; ?></td>
										<td style="border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000;">
											<div>
												<div style="width:100%">
													<div style="color: #000000;font-weight: 500;"><?php echo __('Description'); ?></div>
													<hr>
													<div style="display:block;color: #1b9af7;"><?php echo (!empty($unitvalue['descbr']) ? ucfirst($unitvalue['descbr']) : ''); ?></div>
												</div>
											</div>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td style="color: #000000;font-weight: 500;border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000;"><?php echo __('Primary Muscles'); ?></td>
							<td style="vertical-align:middle;border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000; color: #1b9af7;"><?php echo (!empty($unitvalue['muscle_title']) ? ucfirst($unitvalue['muscle_title']) : ""); ?></td>	
						</tr>
						<tr>
							<?php $param = '';
							$additionaMuscleRecord = Model::instance('Model/workouts')->getAdditionalMusules($unitvalue['unit_id']); 
							if(!empty($additionaMuscleRecord) && count($additionaMuscleRecord)>0){
								foreach($additionaMuscleRecord as $keys => $value){
									$param	.= ucfirst($value['muscle_title']).', ';
								}
							} ?>
							<td style="color: #000000;font-weight: 500;border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000;"><?php echo __('Other Muscles Involved'); ?></td>
							<td style="vertical-align:middle;border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000; color: #1b9af7;"><?php echo rtrim($param, ", "); ?></td>	
						</tr>
						<tr>
							<?php $equipparam = (!empty($unitvalue['muscle_title']) ? ucfirst($unitvalue['muscle_title']) : '').', ';
							$additionaEquipRecord = Model::instance('Model/workouts')->getAdditionalEquip($unitvalue['unit_id']); 
							if(!empty($additionaEquipRecord) && count($additionaEquipRecord)>0){
								foreach($additionaEquipRecord as $keys => $value){
									$equipparam	.= ucfirst($value['equip_title']).', ';
								}
							} ?>
							<td style="color: #000000;font-weight: 500;border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000;"><?php echo __('Equipment / Alternatives'); ?></td>
							<td style="vertical-align:middle;border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000; color: #1b9af7;"><?php echo rtrim($equipparam, ", "); ?></td>	
						</tr>
						<tr>
							<td style="color: #000000;font-weight: 500;border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000;"><?php echo __('Mechanices Type'); ?></td>
							<td style="vertical-align:middle;border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000; color: #1b9af7;"><?php echo (!empty($unitvalue['mech_title']) ? ucfirst($unitvalue['mech_title']) : ''); ?></td>	
						</tr>
						<tr>
							<td style="color: #000000;font-weight: 500;border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000;"><?php echo __('Exercise Level'); ?></td>
							<td style="vertical-align:middle;border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000; color: #1b9af7;"><?php echo (!empty($unitvalue['level_title']) ? ucfirst($unitvalue['level_title']) : ''); ?></td>	
						</tr>
						<tr>
							<td style="color: #000000;font-weight: 500;border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000;"><?php echo __('Sport'); ?></td>
							<td style="vertical-align:middle;border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000; color: #1b9af7;"><?php echo (!empty($unitvalue['sport_title']) ? ucfirst($unitvalue['sport_title']) : ''); ?></td>	
						</tr>
						<tr>
							<td style="color: #000000;font-weight: 500;border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000;"><?php echo __('Force / Movement'); ?></td>
							<td style="vertical-align:middle;border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000; color: #1b9af7;"><?php echo (!empty($unitvalue['force_title']) ? ucfirst($unitvalue['force_title']) : ''); ?></td>	
						</tr>
						<tr>
							<td style="color: #000000;font-weight: 500;border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000;"><?php echo __('Other Remarks'); ?></td>
							<td style="vertical-align:middle;border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000; color: #1b9af7;"><?php echo (!empty($unitvalue['descfull']) ? ucfirst($unitvalue['descfull']) : ''); ?></td>	
						</tr>
					</tbody>
				</table>
			<?php }
		}
		if(strripos($unitkey,"_seqdata")){
			if(!empty($unitvalue) && count($unitvalue)>0){
				$cnt++; ?>
				<div style="vertical-align:middle;color: #000;font-weight: bold;font-size: 16px;margin: auto;padding-top:10px;padding-bottom:3px;"><?php echo $cnt; ?>. <?php echo __('Sequence Instruction'); ?></div><table width="100%" style="font-size:14px;">
					<thead>
						<tr>
							<th style="border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000; color: #1b9af7;width:20px;border-radius: 0;"><?php echo __('Sequence'); ?></th>
							<th style="border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000; color: #1b9af7;width:200px;border-radius: 0;"><?php echo __('Sequence Image'); ?></th>
							<th style="border-bottom: 0.5px dotted #000; color: #1b9af7;border-radius: 0;"><?php echo __('Description'); ?></th>
						</tr>
					</thead>
					<tbody>
					<?php foreach ($unitvalue as $seqkey => $seqvalue) {
						$seqimgurl = '' ;
						if(!empty($seqvalue['img_url']) && file_exists($seqvalue['img_url'])){
							$seqimgurl = URL::site($seqvalue['img_url']);
						}
						$seqimg = !empty($seqimgurl) ? '<img style="border:1px solid grey" src="'.$seqimgurl.'" width="60px;"/>' : ''; ?>
						<tr style="background-color:<?php echo ($seqkey % 2 == 0 ? '#FFFFFF' : '#F1F1F1'); ?>">
							<td style="border-bottom: 0.5px dotted #000; border-left: 0.5px dotted #000; border-right: 0.5px dotted #000;border-radius: 0;"><?php echo ($seqkey+1); ?></td>
							<td style="border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000; vertical-align: middle;border-radius: 0"><?php echo $seqimg; ?></td>
							<td style="border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000; vertical-align: middle;border-radius: 0;"><?php echo (!empty($seqvalue['seq_desc']) ? ucfirst($seqvalue['seq_desc']) : ''); ?></td>
						</tr>
					<?php } ?>
					</tbody>
				</table>
			<?php }
		}
		if(strripos($unitkey,"_relateddata")){
			if(!empty($unitvalue) && count($unitvalue)>0){
				$cnt++; ?>
				<div style="vertical-align:middle;color: #000;font-weight: bold;font-size: 16px;margin: auto;padding-top:10px;padding-bottom:3px;"><?php echo $cnt; ?>. <?php echo __('Related Exercise Records'); ?></div><table width="100%" style="font-size:14x;">
					<thead>
						<tr>
							<th style="border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000; color: #1b9af7;width:20px;border-radius: 0;"><?php echo __('Exercise'); ?></th>
							<th style="border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000; color: #1b9af7;width:200px;border-radius: 0;"><?php echo __('Feature Image'); ?></th>
							<th style="border-bottom: 0.5px dotted #000; color: #1b9af7;border-radius: 0;"><?php echo __('Title'); ?></th>
						</tr>
					</thead>
					<tbody>
					<?php foreach ($unitvalue as $relkey => $relvalue) {
						$featimgurl = '' ;
						if(!empty($relvalue['unit_id'])){
							if(file_exists($relvalue['img_url']))
								$featimgurl = URL::site($relvalue['img_url']);
							else
								$featimgurl = URL::site('assets/img/fa-file-image-o.png');
						}else{
							$featimgurl = URL::site('assets/img/fa-pencil-square.png');
						}
						$featimg = !empty($featimgurl) ? '<img style="border:1px solid grey" src="'.$featimgurl.'" width="60px;"/>' : ''; ?>
						<tr style="background-color:<?php echo ($relkey % 2 == 0 ? '#FFFFFF' : '#F1F1F1'); ?>">
							<td style="border-bottom: 0.5px dotted #000; border-left: 0.5px dotted #000; border-right: 0.5px dotted #000;border-radius: 0;"><?php echo ($relkey+1); ?></td>
							<td style="border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000; vertical-align: middle;border-radius: 0"><?php echo $featimg; ?></td>
							<td style="border-bottom: 0.5px dotted #000; border-right: 0.5px dotted #000; vertical-align: middle;border-radius: 0;"><?php echo (!empty($relvalue['title']) ? ucfirst($relvalue['title']) : ''); ?></td>
						</tr>
					<?php } ?>
					</tbody>
				</table>
			<?php }
		}
	} ?>
	</div>
<?php } ?>