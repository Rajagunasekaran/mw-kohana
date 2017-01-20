<!--- Top nav && left nav--->
<?php echo $topnav.$leftnav;
echo $XRciseCreate;
echo $imglibrary;
echo $imgeditor2;
?>
<!--- Top nav && left nav --->
<!-- Content Wrapper. Contains page content -->
<div id="page-wrapper">
	<div class="container-fluid">
		<!-- Page Heading -->
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Edit Workout Records</h1>
				<ol class="breadcrumb">
					<li><i class="fa fa-dashboard"></i>  <a href="<?php echo URL::base().'admin/index'; ?>">Dashboard</a></li>
					<li class="active"><i class="fa fa-edit"></i> Edit Workout Records</li>
				</ol>
         </div>
		</div>
      <!-- /.row -->
<?php /*********************************************** Edit Section **************************************************/ ?>

	<form action="" method="post" id="form-workoutrec" data-ajax="false" data-role="none">
	<input type="hidden" value="<?php echo $save;?>" name="save_edit" id="save_edit"/>
	<input type="hidden" value="<?php echo (isset($workoutRecord['parent_folder_id']) ? $workoutRecord['parent_folder_id'] :'0');?>" name="parent_folder_id"/>
	<div class="row" id="errormsgdivtag">
		<?php $session = Session::instance();
			if ($session->get('success')): ?>
		  	<div class="banner success alert alert-success">
				<a data-ajax="false" data-role="none" href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><span class="successmsg"><?php echo $session->get_once('success') ?></span>
		  	</div>
		<?php endif ?>
	</div>
	<div class="row">
		<div class="border">
			<div class="col-xs-3 aligncenter">
				<a data-ajax='false' data-text="<?php echo __('Clicking BACK or CANCEL will discard any changes. Clicking MORE will display record options such as SAVE. Continue with exiting?');?>" href="<?php echo URL::base().'admin/workout/browse'; ?>" class="triangle confirm" data-notename="hide_confirm_wkout" data-allow="<?php echo (Helper_Common::getAllowAllAccessByUser($session->get('user_allow_page'),'is_confirm_wkout_hidden') ? 'false' : 'true');?>">
					<i class="fa fa-caret-left iconsize"></i>
				</a>
			</div>
			<div class="col-xs-6 editrecordtitle" style="text-align:center"><?php echo __('Workout Record'); ?> <span class="editmode hide"> : <?php echo __('Edit Data'); ?></span><span class="editmodesets hide"> : <?php echo __('Edit Sets'); ?></span></div>
			<div class="col-xs-3 aligncenter save-icon-button">
				<i class="fa fa-ellipsis-h iconsize" onclick="getTemplateOfWorkoutAction();"></i>
			</div>
		</div>
	</div>
	<div id="expended">
		<div class="row">
			<div class="mobpadding">
				<div class="border full showexercisetitleOne">
					<div class="col-xs-3 borderright">
						<div class="" style="float:left">
							<i class="glyphicon wrkoutcolor <?php echo $workoutRecord['color_title'];?>"></i>
						</div>
					</div>
					<div class="col-xs-9 activedatacol alignleft">
						<div class="">
							<div class="col-xs-6 alignleft datacol">
								<?php echo ucfirst($workoutRecord['wkout_title']);?>
							</div>
						</div>
					</div>
				</div>
				<div class="border full showexercisetitleTwo hide">
					<div class="col-xs-3 borderright">
						<?php echo __('Title'); ?>
					</div>
					<div class="col-xs-9 activedatacol alignleft">
						<div class="pointers colormodelpopup">
							<div class="col-xs-8 alignleft colormodelpopup" style="padding:0px">
								<textarea data-ajax="false" data-role="none" type="text" id="wrkoutname_hidden" class="wrkoutname_hidden input-sm form-control" name="wrkoutname_hidden" placeholder="click to Enter custom title"><?php echo ucfirst($workoutRecord['wkout_title']);?></textarea>
							</div>
							<div data-toggle="collapse" data-target=".navbar-collapse-colors">
								<i id="wrkoutcolortext" class="glyphicon wrkoutcolor <?php echo $workoutRecord['color_title'];?>"style="float:left;width:20px;height:20px;top:4px;margin:0 5px"></i>
								<input type="hidden" name="wrkoutcolor" id="wrkoutcolor" value="<?php echo $workoutRecord['color_id'];?>" >
							</div>
							<div><span data-toggle="collapse" data-target=".navbar-collapse-colors" ><i class="fa iconsize fa-caret-down"></i></span></div>
							<div class="collapse navbar-collapse-colors" style="position: absolute;top: 43px; background-color:#fff; border:1px solid #ededed; z-index:999;"><div class="lt-left"><div class="row"><div class="col-xs-12 textcenter"><?php echo __('Optional color marker'); ?></div></div><br>
									<?php 
										if(isset($colorsRecord) && count($colorsRecord)>0){
											foreach($colorsRecord as $keys => $values){
												if($keys % 4 == 0) { ?>
													<div class="row"><div class="col-xs-12">
												<?php } ?>
												<div class="col-xs-3 colormodel"><a data-role="none" data-ajax="false" href="javascript:void(0)" ><i onclick="return selectcolor($(this));" class="colorcircle glyphicon <?php echo $values['color_title'].( isset($workoutRecord['color_title']) && ($values['color_title'] == $workoutRecord['color_title']) ? ' activecircle' :'')?>"><span style="display:none" class="choosenclr"><?php echo $values['color_id'];?></span></i></a></div>
												<?php if($keys % 4 == 3) { ?>
													</div></div><br>
												<?php } 													
												} ?>
											<div><button data-ajax="false" data-role="none" type="button" class="btn btn-default" style="float:right;margin:5px;" data-toggle="collapse" data-target=".navbar-collapse-colors"><?php echo __('clear'); ?></button></div>
									<?php 	}
									?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="mobpadding">
				<div class="border borderbottom">
					<div class="col-xs-3 borderright">
						<?php echo __('Focus'); ?>
					</div>
					<div class="col-xs-9">
						<div class="col-xs-6 alignleft datacol selectdropdownOne">
							<?php foreach($focusRecord as $keys => $values){ if($values['focus_id'] == $workoutRecord['wkout_focus']) echo ucfirst($values['focus_opt_title']);};?>
						</div>
						<div class="dropdown hide selectdropdownTwo">
							<label id="dropdown">
							<select data-role="none" data-ajax="false"  name="wkout_focus" class="activedatacol">
								<option value="">Select an overall focus</option>
								<?php if(!empty($focusRecord) && count($focusRecord)>0){ 
										foreach($focusRecord as $keys => $values){
								?>
									<option <?php echo ($workoutRecord['wkout_focus'] == $values['focus_id'] ? 'selected' : '');?> value="<?php echo $values['focus_id'];?>"><?php echo ucfirst($values['focus_opt_title']);?></option>
								<?php	} 
									 } ?>
							</select>
							</label>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="mobpadding">
			<div class="border full aligncenter opendiv" onclick="toggleDivTitle();">
				<i id="expendeddiv" class="fa fa-caret-up iconsize"></i>
			</div>
		</div>
	</div>
	
	<div class="collapse navbar-collapse-actions innerpage">
		<div class="row">
			<div class="mobpadding">
				<div class="border full optionmenu">
					<div id="createwkout" class="menuactive"><button data-ajax='false' data-role="none" class="btn btn-default" id="createwkoutbtn" onclick="return createNewExerciseSet('','last');"><i class="fa fa-plus"></i></button><br><span class="inactivedatacol">new set</span></div>
					<div class="hide allowhide"><button data-ajax='false' data-role="none" name="f_method" class="btn btn-default" onclick="return checkallItems(this)"><i class="fa fa-check-circle-o"></i></button><br><span class="inactivedatacol">all/none</span></div>	
					<div class="hide allowhide"><button data-ajax='false' data-role="none" class="btn btn-default" type="button" onclick="return createCopyXrPopup();"><i class="fa fa-files-o datacol allowActive"></i></button><br><span class="inactivedatacol">clone</span></div>
					<div class="hide allowhide"><button data-ajax='false' data-role="none" class="btn btn-default" type="button" onclick="return deleteExerciseSet();"><i class="fa fa-times datacol allowActive"></i></button><br><span class="inactivedatacol">delete</span></div>
					<div class="borderright"></div>
					<div class=""><button data-ajax='false' data-role="none" name="f_method" id="editxr" onclick="return editExercistSets(this);" class="btn btn-default"><i class="fa fa-list-ul"></i></button><button data-ajax='false' data-role="none" name="f_method" id="refresh" onclick="return editWorkout(this);" class="btn btn-default hide"><i class="fa fa-refresh"></i></button><br><span class="inactivedatacol">sets/list</span></div>
				</div>
			</div>
		</div>
	</div>
	<div class="scrollablepadd scrollablediv" id="scrollablediv-len">
		<input type="hidden" id="s_row_count" name="s_row_count" value="<?php echo count($exerciseRecord['uniqueset']);?>"/>
		<input type="hidden" id="s_row_count_flag" name="s_row_count_flag" value="<?php echo count($exerciseRecord['uniqueset']);?>"/>
		<input type="hidden" id="wkout_id" name="wkout_id" value="<?php echo $wkout_id?>"/>
		<ul data-ajax="false" data-inset="false" data-role="none" data-autodividers="false" class="sTreeBase bgC4" style="border:1px solid #ededed;">
<?php
	$totalRestTime = '00:00';
	$totalRest = 0;
	$order = 0;
	$overallorder = 1;
	if(isset($exerciseRecord['uniqueset']) && count($exerciseRecord['uniqueset'])>0){
	foreach($exerciseRecord['uniqueset'] as $keys => $values){
		$order++;
		if(!empty($values['goal_unit_id'])){
			$exactOrder = $order.'_'.$values['goal_unit_id'];
			$data_id 	= $values['goal_unit_id'];
		}else{
			$data_id 	= '0_'.$wkout_id;
			$exactOrder = $order.'_0_'.$wkout_id;
		}
?>
	<li data-orderval="<?php echo $order;?>" data-role="none" class="bgC4" data-inner-cnt="<?php echo (isset($exerciseRecord['setdetails'][$keys]) ? count($exerciseRecord['setdetails'][$keys]) : 0);?>" data-id='<?php echo str_replace($overallorder.'_','',$keys);?>' data-title='<?php echo base64_encode($values['goal_title']);?>' data-module="<?php echo (isset($exerciseRecord['setdetails'][$keys]) && count($exerciseRecord['setdetails'][$keys])>1 ? 'item_sets' : 'item_set');?>" id="itemSet_<?php echo $wkout_id.'_'.$exactOrder;?>"><!--wrkoutid.'_'.order.'_'.goal_unit_id-->
		<div id="itemset_<?php echo $exactOrder;?>" class="row">
			<input type="hidden" class="seq_order_combine_up" id="<?php echo 'goal_order_combine_'.$exactOrder;?>" name="goal_order_combine[<?php echo $exactOrder;?>]" value="<?php echo $order;?>"/>
			<div class="mobpadding">
				<div class="border full">
					<div class="checkboxchoosen col-xs-1 row-no-padding" style="display:none;">
						<div class="checkboxcolor">
							<label>
								<input id="checkbox_col_<?php echo $exactOrder;?>" onclick="enableButtons(this);" data-role="none" data-ajax="false" type="checkbox" class="checkhidden" name="exercisesets[]" value="<?php echo $exactOrder;?>">
								<span class="cr checkbox-circle"><i class="cr-icon fa fa-check"></i></span>
							</label>
						</div>
					</div>
					<div class="col-xs-8 navdescrip row-no-padding">
						<div class="col-xs-4 navimage activelink datacol row-no-padding" <?php if($values['goal_unit_id']){?> onclick="getTemplateOfExerciseRecordAction('<?php echo $order.'_'.$values['goal_unit_id'];?>',this,'<?php echo $order;?>');" <?php } ?>>
							<?php if($values['goal_unit_id']) { ?>
								<?php if(file_exists($values['img_url'])){ ?>
									<img width="75px" class="img-responsive pointers" src="<?php echo URL::base().$values['img_url'];?>" title="<?php echo ucfirst($values['img_title']);?>"/>
								<?php }else{ ?>
									<i class="fa fa-file-image-o pointers" style="font-size:50px;"></i>
								<?php } ?>
							<?php }else { ?>
								<i class="fa fa-pencil-square" style="font-size:50px;"></i>
							<?php } ?>
						</div>
						<div class="col-xs-8 pointers activelink datacol row-no-padding">
							<div class="navimagedetails">
								<div class="navimgdet1" onclick="editWorkoutRecord('<?php echo $exactOrder;?>','preview');"><b><?php echo (($values['goal_alt'] > 0) ? '<span>Alt</span> ' : '');?><?php echo ucfirst($values['goal_title']);?></b></div>
								<div class="exercisesetdiv">
								<?php $setIds = array();
								if(isset($exerciseRecord['setdetails'][$keys]) && count($exerciseRecord['setdetails'][$keys])>0){
									foreach($exerciseRecord['setdetails'][$keys] as $key => $value){
										$setIds[] = $order.'_'.$value['goal_id'];
								?>
								<?php echo ($key > 0 ? '<hr>' : '');?>
								<div data-id="<?php echo $order.'_'.$value['goal_id'];?>" class="navimgdet2" id="set_id_<?php echo $order.'_'.$value['goal_id'];?>">
									<input type="hidden" class="seq_order_up" id="<?php echo 'goal_order_'.$value['goal_id'];?>" name="goal_order[<?php echo $value['goal_id'];?>]" value="<?php echo $overallorder;?>"/>
									<input type="hidden" id="<?php echo 'goal_remove_'.$value['goal_id'];?>" name="goal_remove[<?php echo $value['goal_id'];?>]" value="0"/>
									<div class="xrsets col-xs-9">
									<?php $parameter = $parameter1 =  '' ;$hidden_time ="00:00:00";$hidden_rest_time ="00:00"; $flag = false;
									  	$parameter .= '<a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail datacol exercise_time_div">';
									  	if($value['goal_time_hh']>0 || $value['goal_time_mm'] >0 || $value['goal_time_ss'] >0){
											$parameter .= (($value['primary_time']) ? '<span class="ashstrick">*</span> ' : '').'<span>'.substr(sprintf("%02d", $value['goal_time_hh']),0,2).':'.substr(sprintf("%02d", $value['goal_time_mm']),0,2).':'.substr(sprintf("%02d", $value['goal_time_ss']),0,2).'</span>';
											$hidden_time = substr(sprintf("%02d", $value['goal_time_hh']),0,2).':'.substr(sprintf("%02d", $value['goal_time_mm']),0,2).':'.substr(sprintf("%02d", $value['goal_time_ss']),0,2);
											$flag = true;
									  	}
									  	$parameter .= '</a><a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail datacol exercise_distance_div">';
									  	if($value['goal_dist']>0 && $value['goal_dist_id']>0){
											$parameter .= ($flag ? ' /// ' : '').(($value['primary_dist']) ? '<span class="ashstrick">*</span> ' : '').$value['goal_dist'].' <span>'.Model::instance('Model/workouts')->getGoalValues('dist',$value['goal_dist_id']).'</span>';
											$flag = true;
									  	}
									  	$parameter .= '</a><a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail datacol exercise_repetitions_div">';
									  	if($value['goal_reps']>0){
											$parameter .= ($flag ? ' /// ' : '').(($value['primary_reps']) ? '<span class="ashstrick">*</span> ' : '').$value['goal_reps'].' <span>reps</span>';
											$flag = true;
										}
									  	$parameter .= '</a><a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail datacol exercise_resistance_div">';
										if($value['goal_resist']>0 && $value['goal_resist_id']>0){
											$parameter .= ($flag ? ' /// ' : '').(($value['primary_resist']) ? '<span class="ashstrick">*</span> ' : '').' x '.$value['goal_resist'].' <span>'.Model::instance('Model/workouts')->getGoalValues('resist',$value['goal_resist_id']).'</span>';
										}
									  	$parameter .= '</a>';
										$parameter1 .= '<a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail datacol exercise_rate_div">';
										if($value['goal_rate']>0 && $value['goal_rate_id']>0){
											$parameter1 .= ($flag ? ' /// ' : '').(($value['primary_rate']) ? '<span class="ashstrick">*</span> ' : '').'<span>@'.$value['goal_rate'].' '.Model::instance('Model/workouts')->getGoalValues('rate',$value['goal_rate_id']).'</span> ';
										}
										$parameter1 .= '</a><a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail datacol exercise_angle_div">';
										if($value['goal_angle']>0 && $value['goal_angle_id']>0){
											$parameter1 .= ($flag ? ' /// ' : '').(($value['primary_angle']) ? '<span class="ashstrick">*</span> ' : '').'<span>'.$value['goal_angle'].'%'.Model::instance('Model/workouts')->getGoalValues('angle',$value['goal_angle_id']).'</span> ';
										}
										$parameter1 .= '</a><a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail datacol exercise_innerdrive_div">';
										$intvalue = Model::instance('Model/workouts')->getGoalValues('int',$value['goal_int_id']);
										if($intvalue>0){
											$parameter1 .= ($flag ? ' /// ' : '').(($value['primary_int']) ? '<span class="ashstrick">*</span> ' : '').'<span>'.$intvalue.' int</span>';
										}
										$parameter1 .= '</a><a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail datacol exercise_rest_div">';
										if($value['goal_rest_mm'] + $value['goal_rest_ss']>0){
											if($value['goal_rest_mm'] >0 ||  $value['goal_rest_ss']>0){
												$parameter1 .= ($flag ? ' /// ' : '').(($value['primary_rest']) ? '<span class="ashstrick">*</span> ' : '').' <span>'.$value['goal_rest_mm'];
												$totalRest += $value['goal_rest_mm'] * 60;
												if($value['goal_rest_ss']>0 && $value['goal_rest_ss'] < 10)
													$parameter1 .=  ':0'.$value['goal_rest_ss'];
												else
													$parameter1 .=  ':'.substr(sprintf("%02d", $value['goal_rest_ss']),0,2);
												$hidden_rest_time = $value['goal_rest_mm'].':'.substr(sprintf("%02d", $value['goal_rest_ss']),0,2);
												$totalRest += $value['goal_rest_ss'];
												$parameter1 .=  ' rest</span>';
											}
										}
										$parameter1 .= '</a>';
										echo (trim(strip_tags($parameter)) != '' ? (trim(str_replace(strip_tags($parameter1),'&nbsp;','')) != '' ? $parameter.' / '.$parameter1 : $parameter.$parameter1) : $parameter.$parameter1);
									?>
									</div>
									<div class="col-xs-1 navbarmenu row-no-padding">
										<a data-ajax='false' class="editchoosenIconTwo hide" href="javascript:void(0)" style="cursor: move;"><i class="fa fa-bars panel-draggable"></i></a>
										<i class="fa fa-ellipsis-h iconsize listoptionpop hide" class="navbar-toggle" onclick="getTemplateOfExerciseSetAction('<?php echo $exactOrder;?>','<?php echo $value['goal_id'];?>','link');"></i>
										<input type="hidden" data-keyval="<?php echo $order.'_'.$value['goal_id'];?>" class="exercise_title_xr_hidden" id="exercise_title_<?php echo $value['goal_id'];?>" name="exercise_title[<?php echo $value['goal_id'];?>]" value="<?php echo $value['goal_title'];?>"/><input type="hidden" class="exercise_unit_xr_hidden" data-keyval="<?php echo $order.'_'.$value['goal_id'];?>" id="exercise_unit_<?php echo $value['goal_id'];?>" name="exercise_unit[<?php echo $value['goal_id'];?>]" value="<?php echo ($value['goal_unit_id'] > 0 ? $order.'_'.$value['goal_unit_id'] : '');?>"/><input type="hidden" data-keyval="<?php echo $order.'_'.$value['goal_id'];?>" id="exercise_resistance_<?php echo $value['goal_id'];?>" name="exercise_resistance[<?php echo $value['goal_id'];?>]" value="<?php echo $value['goal_resist'];?>"/><input type="hidden" data-keyval="<?php echo $order.'_'.$value['goal_id'];?>" id="exercise_unit_resistance_<?php echo $value['goal_id'];?>" name="exercise_unit_resistance[<?php echo $value['goal_id'];?>]" value="<?php echo $value['goal_resist_id'];?>"/><input type="hidden" data-keyval="<?php echo $order.'_'.$value['goal_id'];?>" id="exercise_repetitions_<?php echo $value['goal_id'];?>" name="exercise_repetitions[<?php echo $value['goal_id'];?>]" value="<?php echo $value['goal_reps'];?>"/><input type="hidden" data-keyval="<?php echo $order.'_'.$value['goal_id'];?>" id="exercise_time_<?php echo $value['goal_id'];?>" name="exercise_time[<?php echo $value['goal_id'];?>]" value="<?php echo $hidden_time;?>"/><input type="hidden" data-keyval="<?php echo $order.'_'.$value['goal_id'];?>" id="exercise_distance_<?php echo $value['goal_id'];?>" name="exercise_distance[<?php echo $value['goal_id'];?>]" value="<?php echo $value['goal_dist'];?>"/><input type="hidden" data-keyval="<?php echo $order.'_'.$value['goal_id'];?>" id="exercise_unit_distance_<?php echo $value['goal_id'];?>" name="exercise_unit_distance[<?php echo $value['goal_id'];?>]" value="<?php echo $value['goal_dist_id'];?>"/><input type="hidden" data-keyval="<?php echo $order.'_'.$value['goal_id'];?>" id="exercise_rate_<?php echo $value['goal_id'];?>" name="exercise_rate[<?php echo $value['goal_id'];?>]" value="<?php echo $value['goal_rate'];?>"/><input type="hidden" data-keyval="<?php echo $order.'_'.$value['goal_id'];?>" id="exercise_unit_rate_<?php echo $value['goal_id'];?>" name="exercise_unit_rate[<?php echo $value['goal_id'];?>]" value="<?php echo $value['goal_rate_id'];?>"/><input type="hidden" data-keyval="<?php echo $order.'_'.$value['goal_id'];?>" id="exercise_innerdrive_<?php echo $value['goal_id'];?>" name="exercise_innerdrive[<?php echo $value['goal_id'];?>]" value="<?php echo $value['goal_int_id'];?>"/><input type="hidden" data-keyval="<?php echo $order.'_'.$value['goal_id'];?>" id="exercise_angle_<?php echo $value['goal_id'];?>" name="exercise_angle[<?php echo $value['goal_id'];?>]" value="<?php echo $value['goal_angle'];?>"/><input type="hidden" data-keyval="<?php echo $order.'_'.$value['goal_id'];?>" id="exercise_unit_angle_<?php echo $value['goal_id'];?>" name="exercise_unit_angle[<?php echo $value['goal_id'];?>]" value="<?php echo $value['goal_angle_id'];?>"/><input type="hidden" data-keyval="<?php echo $order.'_'.$value['goal_id'];?>" id="exercise_rest_<?php echo $value['goal_id'];?>" name="exercise_rest[<?php echo $value['goal_id'];?>]" value="<?php echo $hidden_rest_time;?>"/><input type="hidden" data-keyval="<?php echo $order.'_'.$value['goal_id'];?>" id="exercise_remark_<?php echo $value['goal_id'];?>" name="exercise_remark[<?php echo $value['goal_id'];?>]" value="<?php echo $value['goal_remarks'];?>"/><input type="hidden" data-keyval="<?php echo $order.'_'.$value['goal_id'];?>" id="primary_time_<?php echo $value['goal_id'];?>" name="primary_time[<?php echo $value['goal_id'];?>]" value="<?php echo $value['primary_time'];?>"/><input type="hidden" data-keyval="<?php echo $order.'_'.$value['goal_id'];?>" id="primary_dist_<?php echo $value['goal_id'];?>" name="primary_dist[<?php echo $value['goal_id'];?>]" value="<?php echo $value['primary_dist'];?>"/><input type="hidden" data-keyval="<?php echo $order.'_'.$value['goal_id'];?>" id="primary_reps_<?php echo $value['goal_id'];?>" name="primary_reps[<?php echo $value['goal_id'];?>]" value="<?php echo $value['primary_reps'];?>"/><input type="hidden" data-keyval="<?php echo $order.'_'.$value['goal_id'];?>" id="primary_resist_<?php echo $value['goal_id'];?>" name="primary_resist[<?php echo $value['goal_id'];?>]" value="<?php echo $value['primary_resist'];?>"/><input type="hidden" data-keyval="<?php echo $order.'_'.$value['goal_id'];?>" id="primary_rate_<?php echo $value['goal_id'];?>" name="primary_rate[<?php echo $value['goal_id'];?>]"  value="<?php echo $value['primary_rate'];?>"/><input type="hidden" data-keyval="<?php echo $order.'_'.$value['goal_id'];?>" id="primary_angle_<?php echo $value['goal_id'];?>" name="primary_angle[<?php echo $value['goal_id'];?>]" value="<?php echo $value['primary_angle'];?>"/><input type="hidden" data-keyval="<?php echo $order.'_'.$value['goal_id'];?>" id="primary_rest_<?php echo $value['goal_id'];?>" name="primary_rest[<?php echo $value['goal_id'];?>]" value="<?php echo $value['primary_rest'];?>"/><input type="hidden" data-keyval="<?php echo $order.'_'.$value['goal_id'];?>" id="primary_int_<?php echo $value['goal_id'];?>" name="primary_int[<?php echo $value['goal_id'];?>]" value="<?php echo $value['primary_int'];?>"/>
									</div>
								</div>
								<?php $overallorder++; }}?>
								</div>
								<input type="hidden" value="<?php echo (count($setIds) >0 ? implode($setIds,',') : '');?>" id="itemSet_<?php echo $wkout_id.'_'.$exactOrder.'_hidden';?>"/>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</li>
<?php }
	}
?>
 </ul>
<?php	if($totalRest > 0){
		$totalMint = $totalRest / 60;
		$totalSec = $totalRest % 60;
		$totalRestTime = substr(sprintf("%02d",$totalMint),0,2).':'.substr(sprintf("%02d",$totalSec),0,2);
	}
?>
	</div>
	<div class="row hide">
		<div class="mobpadding">
			<div class="border full">
				<div class="col-xs-3 borderright">
					<?php echo __('Rest Between Sets'); ?> 
				</div>
				<div class="col-xs-6 datacol alignleft">
					<?php echo $totalRestTime;?>
				</div>
				<div class="col-xs-3">
				</div>
			</div>
		</div>
	</div>
	<div class="removedIds"></div>
	<input type="hidden" id="newlyAddedXr" name="newlyAddedXr" value="0"/>
	<input type="hidden" id="modify_happen" name="modify_happen" value="0"/>
	<div class="row">
		<div class="border">
			<div class="col-xs-6"></div>
			<div class="col-xs-3 aligncenter" style="text-align:center">
			<a data-ajax='false' data-text="<?php echo __('Clicking BACK or CANCEL will discard any changes. Clicking MORE will display record options such as SAVE. Continue with exiting?');?>" href="<?php echo URL::base().'admin/workout/browse'; ?>" class="btn btn-default confirm" data-notename="hide_confirm_wkout" data-allow="<?php echo (Helper_Common::getAllowAllAccessByUser($session->get('user_allow_page'),'is_confirm_wkout_hidden') ? 'false' : 'true');?>"><?php echo (!empty($popupAct) ? __('cancel') : __('back') ); ?></a></div>
			<div class="col-xs-3 aligncenter save-icon-button">
				<i class="fa fa-ellipsis-h iconsize" onclick="getTemplateOfWorkoutAction();"></i>
			</div>
		</div>
	</div>
 </form>
<!-- Modal -->
<div id="myModal" class="modal fade bs-example-modal-sm" role="dialog" tabindex="-1"></div>
<div id="myOptionsModal" class="modal fade bs-example-modal-sm" role="dialog" tabindex="-1"></div>
<div id="mypopupModal" class="modal fade bs-example-modal-sm" role="dialog" tabindex="-1"></div>
<div id="myOptionsModalAjax" class="modal fade bs-example-modal-sm" role="dialog" tabindex="-1"></div>
<div id="myOptionsModalExerciseRecord" class="modal fade bs-example-modal-sm" role="dialog" tabindex="-1"></div>
<script>
	var checked= 1;
	$(document).on('click', '.checkboxcolor label input[type="checkbox"]', function() {
		console.log($(this).attr('data-check'));
		if($(this).prop("checked") == true && typeof($(this).attr('data-check')) == 'undefined'){
			$(this).attr('data-check',checked);
			checked++;
		}
	});
	$(document).ready(function (){
		if($('#scrollablediv-len li'))
			var last = $('#scrollablediv-len li').length;
		else
			var last = 0;
		var count = parseInt(last);
		$('#s_row_count').val(count);
		<?php if(!empty($popupAct)){ ?>
			if($('.listoptionpop')){
				$('a.editchoosenIconTwo').addClass('hide');
				$('i.listoptionpop').removeClass('hide');
			}
		<?php if(!empty($popupEdit)){ ?>
			changeTosaveIcon();
			toggleDivTitle();
			editWorkoutRecord('<?php echo $popupEdit;?>','preview');
		<?php } ?>
		<?php	  if($popupAct == 'add'){
		?>			changeTosaveIcon();
					createNewExerciseSet();
		<?php 	  }else if($popupAct == 'edit'){
		?>			changeTosaveIcon();
					toggleDivTitle();
		<?php 	  } else if($popupAct == 'editset'){
		?>			changeTosaveIcon();
					toggleDivTitle();
					$('#editxr').trigger('click');
		<?php	  }
				}
		?>
	});
</script>
	
	
	
<?php /*********************************************** Edit Section **************************************************/ ?>			
	</div>
</div>

