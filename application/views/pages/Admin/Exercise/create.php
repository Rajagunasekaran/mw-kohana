<!--- Top nav && left nav--->
<?php echo $topnav.$leftnav;?>
<?php echo $imglibrary; ?>
<!--- Top nav && left nav --->
<!-- Content Wrapper. Contains page content -->
<?php if(isset($template_type_single) && count($template_type_single)>0) {
	foreach($template_type_single as $key => $value) {
		$type_id		= $value['type_id'];
		$type_name		= $value['type_name'];
		$template_id	= $value['template_id'];
	}
} ?>
<?php $backurl = URL::base(TRUE).'admin/exercise/browse';
if(isset($openfrom) && !empty($openfrom)){
	$backurl = ($openfrom == 'indx' ? URL::base(TRUE).'admin/workout/browse' : URL::base(TRUE).'admin/exercise/browse');
} ?>
<div id="page-wrapper">
<div class="container-fluid">
 	<!-- Page Heading -->
 	<div class="row">
     	<div class="col-lg-12">
   		<h1 class="page-header">
	        	<?php $session = Session::instance();
				if($session->get('current_site_name')!=''){echo '"'.$session->get('current_site_name').'"';}
				echo (isset($formtype) && $formtype=='edit')?" Modify Exercise Record":" Create Exercise Record"; ?>
         </h1>
         <ol class="breadcrumb">
          	<li>
              	<i class="fa fa-dashboard"></i>  <a href="<?php echo URL::base().'admin/index'; ?>"><?php echo __('Dashboard'); ?></a>
          	</li>
          	<li class="active">
              	<i class="fa fa-edit"></i> 
              	<?php echo (isset($formtype) && $formtype=='edit') ? __("Modify Exercise Record") : __("Create Exercise Record"); ?>
         	</li>
         </ol>
     	</div>
 	</div>
 	<!-- /.row -->
	<?php if ($session->get('flash_success')): ?>
		<div class="row bannermsg">
			<div class="col-sm-12 col-xs-12 col-md-12 banner success alert alert-success">
				<a data-ajax="false" data-role="none" href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<?php echo $session->get_once('flash_success') ?>
			</div>
		</div>
	<?php endif;
	if ($session->get('flash_error')): ?>
		<div class="row bannermsg">
			<div class="col-sm-12 col-xs-12 col-md-12 banner errors alert alert-danger">
				<a data-ajax="false" data-role="none" href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<?php echo $session->get_once('flash_error') ?>
			</div>
		</div>
	<?php endif; ?>
<?php /****************Starts Here*************************/ ?>
<div style='width:100%;margin: auto;border:0px solid red;'>
	<?php /*********************************************************/ ?>
	<?php $workoutModel     = Model::instance('Model/workouts');
	$exerciseType		= $workoutModel->getunitsbytable('unit_type');
	$exerciseStatus	= $workoutModel->getunitsbytable('unit_status');
	$exerciseAccess	= $workoutModel->getunitsbytable('roles'); ?>
	<?/********************************************************************************************/?>
	<!-- xrwrapper -->
	<div id="create-record"> 
		<div class="xrwrapper-div ">
			<div class="row xrwrappers-header-row ">
				<div class="page-head">
					<div class="col-xs-3 aligncenter">
						<a href="<?php echo $backurl; ?>" class="triangle confirm" data-ajax="false" data-role="none" data-allow="<?php echo (Helper_Common::getAllowAllAccessByUser((Session::instance()->get('user_allow_page') ? Session::instance()->get('user_allow_page') : '1'), 'is_confirm_xr_hidden') ? 'false' : 'true'); ?>" data-notename="hide_confirm_xr" data-text="Clicking BACK or CANCEL will discard any changes. Clicking MORE will display record options such as SAVE. Continue with exiting?">
							<i class="fa fa-caret-left iconsize"></i>
						</a>
					</div>
					<div class="col-xs-6 aligncenter centerheight" id="dynXrTitle"><?php echo (isset($formtype) && $formtype=='edit' && !isset($startExercise)) ? __('Modify Exercise Record') : __('Create a New Exercise Record'); ?>
						<?php echo (isset($exerciseArray['title'])) ?'<div class="col-xs-12 record-title">'.$exerciseArray['title'].'</div>' : ''; ?>
					</div>
					<div class="col-xs-3 aligncenter">
						<button type="button" class="btn btn-default submitTabsBtn activedatacol" data-toggle="modal" data-target="#xrcisesaveopt-modal" data-ajax="false" data-role="none"><?php echo __('more'); ?></button>
					</div>
				</div>
			</div>
			<hr>
			<div class="xrwrappers">
				<form id="xrRecInsertForm" class="form-horizontal" method="post" action="" data-ajax="false" data-role="none">
					<div class="form-group has-feedback has-error hide" id="messageContainer">
						<div class="col-xs-12">
							<div><?php echo __('Please Fill The Required Fields'); ?></div>
						</div>
					</div>
					<div class="tab-content">
						<div class="row">
							<div class="mobpadding exersetcolumn-xr">
								<div class="border-xr full">
									<div class="col-xs-3 firstcell borderright">
										<p class="labelcol"><?php echo __('Exercise Title'); ?> <span class="activedatacol">*</span></p>
									</div>
									<div class="col-xs-9 secondcell datacol">
										<div class="form-group">
											<input type="text" tabindex="1" class="form-control" id="xru_title" value="<?php echo (isset($exerciseArray['title']) ? $exerciseArray['title'].(isset($startExercise) && !empty($startExercise) ? '_copy' : '') : ''); ?>" name="xru_title" placeholder="<?php echo __('Title'); ?>" onfocus="this.value = this.value;" data-ajax="false" data-role="none"/>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="mobpadding exersetcolumn-xr">
								<div class="border-xr full">
									<div class="col-xs-3 firstcell borderright">
										<p class="labelcol"><?php echo __('Type of Activity'); ?> <span class="activedatacol">*</span></p>
									</div>
									<div class="col-xs-9 secondcell datacol">
										<div class="form-group">
											<?php $exerciseType = Model::instance('Model/workouts')->getcheckboxes('type','unit_','_title','_id','unit_gendata','type','0');
											if(isset($exerciseType) && count($exerciseType)>0) { ?>
												<div class="dropdown selectdropdownTwo">
													<select tabindex="2" class="" id="xru_type" name="xru_type" data-ajax="false" data-role="none">
														<option value="">Select an option</option>
														<?php foreach($exerciseType as $key => $value) { ?>
															<option value="<?php echo $value['type_id']; ?>"<?php if(isset($exerciseArray['type_id']) && $exerciseArray['type_id'] == $value['type_id']) echo "selected"; ?>><?php echo $value['type_title']; ?></option>
														<?php } ?>
													</select>
												</div>
											<?php } ?>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row hide">
							<div class="mobpadding exersetcolumn-xr">
								<div class="border-xr full">
									<div class="col-xs-3 firstcell borderright">
										<p class="labelcol"><?php echo __('Status'); ?> <span class="activedatacol">*</span></p>
									</div>
									<div class="col-xs-9 secondcell datacol">
										<div class="form-group">
											<?php $exerciseStatus = Model::instance('Model/workouts')->getcheckboxes('status','unit_','_title','_id','unit_gendata','status','0');
											if(isset($exerciseStatus) && count($exerciseStatus)>0) { ?>
												<div class="dropdown selectdropdownTwo">
													<select tabindex="3" class="" id="xru_status" name="xru_status" data-ajax="false" data-role="none">
														<?php foreach($exerciseStatus as $key => $value) { ?>
															<option value="<?php echo $value['status_id']; ?>"<?php if((isset($exerciseArray['status_id']) && $exerciseArray['status_id'] == $value['status_id']) || (!isset($exerciseArray['status_id']) && $value['status_id'] == 1)) echo "selected"; ?>><?php echo $value['status_title']; ?></option>
														<?php } ?>
													</select>
												</div>
											<?php } ?>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="mobpadding exersetcolumn-xr">
								<div class="border-xr full">
									<div class="col-xs-3 firstcell borderright">
										<p class="labelcol"><?php echo __('Featured Image'); ?> <span class="activedatacol">*</span></p>
									</div>
									<div class="col-xs-9 secondcell datacol">
										<div class="feat_img form-group img-div">
											<span class="img_thmb datacol textcenter">
												<img class="img-responsive img-thumbnail uploaded_image_thmb" id="intro_feature" tabindex="4" src="<?php echo (isset($exerciseArray['img_url'])) ? URL::base().$exerciseArray['img_url'] : URL::base().'assets/images/icons/picture-icon.png'; ?>" alt="<?php echo __('Feature Image'); ?>">
												<div class="img-placeholder inactivedatacol"><?php echo __('Click image to zoom or edit'); ?></div>
											</span>
											<input type="hidden" class="img_selected" id="xru_featImage" name="xru_featImage" value="<?php echo (isset($exerciseArray['feat_img']) && $exerciseArray['feat_img']!=0 && $exerciseArray['feat_img']!='' ? $exerciseArray['feat_img'] : ''); ?>">
											<div class="img-opt">
												<div class="trigger-imgopt" id="introclear" data-imgtagid="intro_feature" data-hidnimgid="xru_featImage" href="<?php echo URL::base().'assets/images/icons/picture-icon.png'; ?>"></div>
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
										<p class="labelcol"><?php echo __('Muscles Involved'); ?> <span class="activedatacol">*</span></p>
									</div>
									<div class="col-xs-9 secondcell datacol">
										<div class="form-group">
											<ul id="muscle_lists" class="muscle-lists">
												<li>
													<?php if(isset($exerciseArray['muscle_id']) && !empty($exerciseArray['muscle_id']) && isset($exerciseArray['muscle_title']) && !empty($exerciseArray['muscle_title'])) { ?>
														<span class="tag-item label label-info" id="<?php echo $exerciseArray['muscle_id']; ?>"><label class="radio-primary"><input type="radio" name="xru_musprim" value="<?php echo $exerciseArray['muscle_id']; ?>" checked="" title="Primary Muscle" data-role="none" data-ajax="false"><?php echo $exerciseArray['muscle_title']; ?></label><span data-role="remove"></span></span>
														<?php if(isset($exerciseMusOth) && count($exerciseMusOth)>0){ //additional muscle
															foreach ($exerciseMusOth as $extactMusoth) { 
																if(isset($extactMusoth['musoth_id']) && !empty($extactMusoth['musoth_id']) && isset($extactMusoth['muscle_title']) && !empty($extactMusoth['muscle_title'])) { ?>
																	<span class="tag-item label label-info" id="<?php echo $extactMusoth['musoth_id']; ?>"><label class="radio-primary"><input type="radio" name="xru_musprim" value="<?php echo $extactMusoth['musoth_id']; ?>" title="Primary Muscle" data-role="none" data-ajax="false"><?php echo $extactMusoth['muscle_title']; ?></label><span data-role="remove"></span><input type="hidden" class="Othermuscle" name="chkdMusOth[]" value="<?php echo $extactMusoth['musoth_id']; ?>"></span>
																<?php }
															}
														}
													} else { ?>
														<input type="hidden" name="xru_musprim" value="">
													<?php } ?>
												</li>
											</ul>
											<?php $muscle = Model::instance('Model/workouts')->getcheckboxes('muscle','unit_','_title','_id','unit_gendata','muscle','0');
											if(isset($muscle) && count($muscle)>0) { ?>
												<div class="dropdown selectdropdownTwo muscle-selectbox" style="display: none;">
													<select tabindex="5" class="" id="list_muscles" data-ajax="false" data-role="none">
														<option value="" selected="">Select an option</option>
														<?php foreach($muscle as $key => $value){ ?>
															<option value="<?php echo $value['muscle_id']; ?>"><?php echo $value['muscle_title']; ?></option>
														<?php } ?>
													</select>
												</div>
												<a href="javascript:void(0);" tabindex="5" class="btn btn-default btn-sm add-muscle" onclick="showMuscleSelectbox();" data-ajax="false" data-role="none"><i class="fa fa-plus"></i> Add a muscle</a>
											<?php } ?>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="mobpadding exersetcolumn-xr">
								<div class="border-xr full">
									<div class="col-xs-3 firstcell borderright">
										<p class="labelcol"><?php echo __('Equipment'); ?> <span class="activedatacol">*</span></p>
									</div>
									<div class="col-xs-9 secondcell datacol">
										<div class="form-group">
											<ul id="equip_lists" class="equip-lists">
												<li>
													<?php if(isset($exerciseArray['equip_id']) && !empty($exerciseArray['equip_id']) && isset($exerciseArray['equip_title']) && !empty($exerciseArray['equip_title'])) { ?>
														<span class="tag-item label label-info" id="<?php echo $exerciseArray['equip_id']; ?>"><label class="radio-primary"><input type="radio" name="xru_equip" value="<?php echo $exerciseArray['equip_id']; ?>" checked="" title="Primary Muscle" data-role="none" data-ajax="false"><?php echo $exerciseArray['equip_title']; ?></label><span data-role="remove"></span></span>
														<?php if(isset($exerciseEquipOth) && count($exerciseEquipOth)>0){ //additional equipment
															foreach ($exerciseEquipOth as $extactEquipoth) { 
																if(isset($extactEquipoth['equipoth_id']) && !empty($extactEquipoth['equipoth_id']) && isset($extactEquipoth['equip_title']) && !empty($extactEquipoth['equip_title'])) { ?>
																	<span class="tag-item label label-info" id="<?php echo $extactEquipoth['equipoth_id']; ?>"><label class="radio-primary"><input type="radio" name="xru_equip" value="<?php echo $extactEquipoth['equipoth_id']; ?>" title="Primary Muscle" data-role="none" data-ajax="false"><?php echo $extactEquipoth['equip_title']; ?></label><span data-role="remove"></span><input type="hidden" class="Otherequip" name="chkdEquipOth[]" value="<?php echo $extactEquipoth['equipoth_id']; ?>"></span>
																<?php }
															}
														}
													} else{ ?>
														<input type="hidden" name="xru_equip" value="">
													<?php }?>
												</li>
											</ul>
											<?php $equip = Model::instance('Model/workouts')->getcheckboxes('equip','unit_','_title','_id','unit_gendata','equip','0'); 
											if(isset($equip) && count($equip)>0) { ?>
												<div class="dropdown selectdropdownTwo equip-selectbox" style="display: none;">
													<select tabindex="6" class="" id="list_equipments" data-ajax="false" data-role="none">
														<option value="" selected="">Select an option</option>
														<?php foreach($equip as $key => $value) { ?>
															<option value="<?php echo $value['equip_id']; ?>"><?php echo $value['equip_title']; ?></option>
														<?php } ?>
													</select>
												</div>
												<a href="javascript:void(0);" tabindex="6" class="btn btn-default btn-sm add-equip" onclick="showEquipmentSelectbox();" data-ajax="false" data-role="none"><i class="fa fa-plus"></i> Add a equipment</a>
											<?php } ?>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="mobpadding exersetcolumn-xr">
								<div class="border-xr full">
									<div class="col-xs-3 firstcell borderright">
										<p class="labelcol"><?php echo __('Mechanics Type'); ?> <span class="activedatacol">*</span></p>
									</div>
									<div class="col-xs-9 secondcell datacol">
										<div class="form-group">
											<?php $mech = Model::instance('Model/workouts')->getcheckboxes('mech','unit_','_title','_id','unit_gendata','mech','0');
											if(isset($mech) && count($mech)>0) { ?>
												<div class="dropdown selectdropdownTwo">
													<select tabindex="7" class="" id="xru_mech" name="xru_mech" data-ajax="false" data-role="none">
														<option value="">Select an option</option>
														<?php foreach($mech as $key => $value) { ?>
															<option value="<?php echo $value['mech_id']; ?>"<?php if(isset($exerciseArray['mech_id']) && $exerciseArray['mech_id'] == $value['mech_id']) echo "selected"; ?>><?php echo $value['mech_title']; ?></option>
														<?php } ?>
													</select>
												</div>
											<?php } ?>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="mobpadding exersetcolumn-xr">
								<div class="border-xr full">
									<div class="col-xs-3 firstcell borderright">
										<p class="labelcol"><?php echo __('Exercise Level'); ?> <span class="activedatacol">*</span></p>
									</div>
									<div class="col-xs-9 secondcell datacol">
										<div class="form-group">
											<?php $level = Model::instance('Model/workouts')->getcheckboxes('level','unit_','_title','_id','unit_gendata','level','0');
											if(isset($level) && count($level)>0) { ?>
												<div class="dropdown selectdropdownTwo">
													<select tabindex="8" class="" id="xru_level" name="xru_level" data-ajax="false" data-role="none">
														<?php foreach($level as $key => $value) { ?>
															<option value="<?php echo $value['level_id']; ?>"<?php if((isset($exerciseArray['level_id']) && $exerciseArray['level_id'] == $value['level_id']) || (!isset($exerciseArray['level_id']) && $value['level_id'] == 1)) echo "selected"; ?>><?php echo $value['level_title']; ?></option>
														<?php } ?>
													</select>
												</div>
											<?php } ?>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="mobpadding exersetcolumn-xr">
								<div class="border-xr full">
									<div class="col-xs-3 firstcell borderright">
										<p class="labelcol"><?php echo __('Sport'); ?> <span class="activedatacol">*</span></p>
									</div>
									<div class="col-xs-9 secondcell datacol">
										<div class="form-group">
											<?php $sport = Model::instance('Model/workouts')->getcheckboxes('sport','unit_','_title','_id','unit_gendata','sport','0');
											if(isset($sport) && count($sport)>0) { ?>
												<div class="dropdown selectdropdownTwo">
													<select tabindex="9" class="" id="xru_sports" name="xru_sports" data-ajax="false" data-role="none">
														<?php foreach($sport as $key => $value) { ?>
															<option value="<?php echo $value['sport_id']; ?>"<?php if((isset($exerciseArray['sport_id']) && $exerciseArray['sport_id'] == $value['sport_id']) || (!isset($exerciseArray['sport_id']) && $value['sport_id'] == 2)) echo "selected"; ?>><?php echo $value['sport_title']; ?></option>
														<?php } ?>
													</select>
												</div>
											<?php } ?>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="mobpadding exersetcolumn-xr">
								<div class="border-xr full">
									<div class="col-xs-3 firstcell borderright">
										<p class="labelcol"><?php echo __('Force Movement'); ?> <span class="activedatacol">*</span></p>
									</div>
									<div class="col-xs-9 secondcell datacol">
										<div class="form-group">
											<?php $force = Model::instance('Model/workouts')->getcheckboxes('force','unit_','_title','_id','unit_gendata','force','0');
											if(isset($force) && count($force)>0) { ?>
												<div class="dropdown selectdropdownTwo">
													<select tabindex="10" class="" id="xru_force" name="xru_force" data-ajax="false" data-role="none">
														<option value="">Select an option</option>
														<?php foreach($force as $key => $value) { ?>
															<option value="<?php echo $value['force_id']; ?>"<?php if(isset($exerciseArray['force_id']) && $exerciseArray['force_id'] == $value['force_id']) echo "selected"; ?>><?php echo $value['force_title']; ?></option>
														<?php } ?>
													</select>
												</div>
											<?php } ?>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="mobpadding exersetcolumn-xr">
								<div class="border-xr full">
									<div class="col-xs-3 firstcell borderright">
										<p class="labelcol"><?php echo __('Description'); ?>
											<img class="info-icon" src="<?php echo URL::base().'assets/images/icons/information.png'; ?>">
											<span class="tooltip hide">
												This section is aimed to provide GENERAL details about this exercise or movement.<br><br><strong>Note:</strong>Share up to 1,000 characters of content.<br><br><em>Note: </em>This information WILL NOT scroll vertically.<br><br>
											</span>
										</p>
									</div>
									<div class="col-xs-9 secondcell datacol">
										<div class="form-group">
											<textarea tabindex="11" placeholder="<?php echo __('No content, click to update'); ?>." name="xru_descbr" id="xru_descbr" class="form-control" data-ajax="false" data-role="none"><?php echo (isset($exerciseArray['descbr']) ? $exerciseArray['descbr'] : ''); ?></textarea>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="mobpadding exersetcolumn-xr">
								<div class="border-xr full">
									<div class="form-group">
										<div class="tab_heading aligncenter seq_title"><?php echo __('Sequence Instruction'); ?></div>
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
										<br><span class="inactivedatacol">new step</span>
									</div>
									<div class="allowhide hide">
										<button id="checkseq" class="btn btn-default" type="button" tabindex="14" onclick="return checkAllItems(this)" data-ajax="false" data-role="none"><i class="fa fa-check-circle-o"></i></button>
										<br><span class="inactivedatacol">all/none</span>
									</div>
									<div class="allowhide hide">
										<button id="deleteseq" class="btn btn-default" type="button" tabindex="15" onclick="return deleteSeqItem();" data-ajax="false" data-role="none"><i class="fa fa-times allowActive datacol"></i></button>
										<br><span class="inactivedatacol">delete</span>
									</div>
									<div class="borderright"></div>
									<div class="">
										<button id="editseq" class="btn btn-default" type="button" tabindex="13" onclick="return editSeqenceList(this);" data-ajax="false" data-role="none"><i class="fa fa-list-ul"></i></button>
										<button id="refreshseq" class="btn btn-default hide" type="button" onclick="return exitEditSeqenceList(this);" data-ajax="false" data-role="none"><i class="fa fa-refresh"></i></button>
										<br><span class="inactivedatacol">steps/list</span>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<ul id="seq_list" class="">
								<?php if(isset($exerciseSeq) && count($exerciseSeq)>0) {
									$si = 1; $rd = count($exerciseSeq)-1; $ri=0;
									foreach ($exerciseSeq as $seqkey => $seqvalues) { ?>
										<li class="seq_order=<?php echo $si; ?> seq-panel">
											<div class="row">
												<div class="mobpadding exersetcolumn-xr">
													<div class="border-xr full">
														<!--.seq_img -->
														<div class="col-xs-3 firstcell borderright">
															<div class="seq-check form-group checkbox-checker col-xs-4" style="display: none;">
																<div class="checkboxcolor">
																	<label>
																		<input type="checkbox" class="checkhidden" name="check_act[]" value="<?php echo $si; ?>" data-role="none" data-ajax="false">
																		<span class="cr checkbox-circle"><i class="cr-icon fa fa-check"></i></span>
																	</label>
																</div>
															</div>
															<div class="seq-img form-group img-div col-xs-12">
																<span class="img_thmb datacol textcenter">
																	<img id="seq-feature<?php echo $si; ?>" class="img-responsive img-thumbnail uploaded_image_thmb" tabindex="16" src="<?php echo (isset($seqvalues['img_url'])) ? '/'.$seqvalues['img_url'] : URL::base().'assets/images/icons/picture-icon.png'; ?>" alt="<?php echo __('Sequence Image'); ?>">
																</span>
																<input type="hidden" class="img_selected" id="seq_img<?php echo $si; ?>" name="seqImg[]" value="<?php echo (isset($seqvalues['img_id'])) ? $seqvalues['img_id'] : ''; ?>">
																<div class="img-opt">
																	<div class="trigger-imgopt" id="seqclear<?php echo $si; ?>" data-imgtagid="seq-feature<?php echo $si; ?>" data-hidnimgid="seq_img<?php echo $si; ?>" href="<?php echo URL::base().'assets/images/icons/picture-icon.png'; ?>"></div>
																</div>
															</div>
														</div>
														<!--seq_desc -->
														<div class="col-xs-9 secondcell datacol">
															<div class="seq-desc form-group">
																<textarea id="seqDesc<?php echo $si; ?>" tabindex="17" placeholder="<?php echo __('No content, click to update'); ?>." class="seq_desc form-control" name="seqDesc[]" data-ajax="false" data-role="none"><?php echo (isset($seqvalues['seq_desc']) ? $seqvalues['seq_desc'] : ''); ?></textarea>
															</div>
														</div>
														<div class="col-xs-2 aligncenter seq-sort hide">
															<span class="seq-move fa fa-arrows iconsize2"></span>
														</div>
													</div>
												</div>
											</div>
										</li>
										<?php $si++; $rd--; $ri++;
									}
								} else {} ?>
							</ul>
						</div>
						<div class="row">
							<div class="mobpadding exersetcolumn-xr">
								<div class="border-xr full">
									<div class="col-xs-3 firstcell borderright">
										<p class="labelcol"><?php echo __('Video Demo'); ?>
											<img class="info-icon" src="<?php echo URL::base().'assets/images/icons/information.png'; ?>">
											<span class=" tooltip hide">
												Share a video link from youtube.<br><br>This video will be displayed in the lightbox pop-up.<br><br>This video will be visible when the record's <em>ad banner</em> has been clicked form the lightbox pop-up.
											</span>
										</p>
									</div>
									<div class="col-xs-9 secondcell datacol" onclick="return false;">
										<div class="form-group">
											<input type="text" tabindex="18" value="<?php echo (isset($exerciseArray['feat_vid']) ? $exerciseArray['feat_vid'] : ''); ?>" placeholder="<?php echo __('Youtube or Vimeo URL here'); ?>" id="xru_featVideo" name="xru_featVideo" class="form-control" data-ajax="false" data-role="none">
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="mobpadding exersetcolumn-xr">
								<div class="border-xr full">
									<div class="col-xs-3 firstcell borderright">
										<p class="labelcol"><?php echo __('Other Remarks'); ?>
											<img class="info-icon" src="<?php echo URL::base().'assets/images/icons/information.png'; ?>">
											<span class="tooltip hide">
												This section is aimed to provide more thorough details, benefits &amp; applications.<br><br><strong>Note:</strong>Share up to 1,000 characters of content.<br><br>This information will scroll vertically.
											</span>
										</p>
									</div>
									<div class="col-xs-9 secondcell datacol">
										<div class="form-group">
											<textarea name="xru_descfull" id="xru_descfull" class="form-control" tabindex="19" placeholder="<?php echo __('No content, click to update'); ?>." data-ajax="false" data-role="none"><?php echo (isset($exerciseArray['descfull']) ? $exerciseArray['descfull'] : ''); ?></textarea>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="mobpadding exersetcolumn-xr">
								<div class="border-xr full">
									<div class="col-xs-3 firstcell borderright">
										<p class="labelcol"><?php echo __('Tags'); ?></p>
									</div>
									<div class="col-xs-9 secondcell datacol">
										<div class="form-group">
											<div class="tags-block" data-enhance="false" data-role="none" data-ajax="false" tabindex="20">
												<?php if(isset($exerciseTags) && count($exerciseTags)>0){
													$tagarr=array();
													foreach ($exerciseTags as $tagkey => $tagvalue) {
														$tagarry[] = ($tagvalue['tag_title']);
													}
													$tagval = implode(',', $tagarry);
												} ?>
												<input type="text" class="form-control xru_Tags" name="xru_Tags" value="<?php echo (!empty($tagval) ? $tagval : ''); ?>" placeholder="Tags" data-role="tagsinput" data-ajax="false"/>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="tab-content-footer">
						<div class="tab-footer-btn">
							<button id="btn_revert" class="btn btn-default pull-left" type="button" data-ajax="false" data-role="none"><?php echo __('Reset'); ?></button>
							<a href="<?php echo URL::base(TRUE).'admin/exercise/browse'; ?>" title="<?php echo __('Cancel'); ?>" class="btn btn-default confirm" data-ajax="false" data-notename="hide_confirm_xr" data-role="none" data-allow="<?php echo (Helper_Common::getAllowAllAccessByUser((Session::instance()->get('user_allow_page') ? Session::instance()->get('user_allow_page') : '1'), 'is_confirm_xr_hidden') ? 'false' : 'true'); ?>" data-text="Clicking BACK or CANCEL will discard any changes. Clicking MORE will display record options such as SAVE. Continue with exiting?" style="margin-right: 20px;"><?php echo __('Cancel'); ?></a>
							<button type="button" class="btn btn-default submitTabsBtn activedatacol" data-toggle="modal" data-target="#xrcisesaveopt-modal" data-ajax="false" data-role="none"><?php echo __('more'); ?></button>
						</div>
					</div>
					<button class="btn hide" type="submit" id="btn_saveclose" value="save" name="f_method" data-ajax="false" data-role="none"></button>
					<button class="btn hide" type="submit" id="btn_savecontn" value="save-edit" name="f_method" data-ajax="false" data-role="none"></button>
					<input type="hidden" id="xrid" name="xrid" value="<?php echo (!isset($startExercise) && isset($xrid) ? $xrid : ''); ?>"/>
					<input type="hidden" id="startExercise" name="startExercise" value="<?php echo (isset($startExercise) && isset($xrid) ? $startExercise : ''); ?>"/>
				</form>
			</div>
		</div>
	</div>
	<?/********************************************************************************************/?> 
	<!-- xr saving modal -->
	<div id="xrcisesaveopt-modal" class="modal fade" role="dialog">
		<div class="vertical-alignment-helper">
			<div class="modal-dialog modal-md">
				<div class="modal-content">
					<div class="modal-header">
						<div class="mobpadding">
							<div class="border">
								<div class="col-xs-2">
									<a href="#" title="<?php echo __('Back'); ?>" onclick="$('#xrcisesaveopt-modal').modal('hidecustom');" class="triangle" data-ajax="false" data-role="none">
										<i class="fa fa-chevron-left"></i>
									</a>
								</div>
								<div class="col-xs-8 optionpoptitle"><?php echo __('Options for Saving'); ?></div>
								<div class="col-xs-2"></div>
							</div>
						</div>
					</div>
					<div class="modal-body opt-body">
						<div class="opt-row-detail">
							<button class="btn btn-default" id="btn_trgrsave" onclick="triggerXrFormSave();" type="button" style="width:100%" data-ajax="false" data-role="none">
								<div class="col-xs-12 pointer">
									<div class="col-xs-3"><i class="fa fa-save iconsize"></i></div>
									<div class="col-xs-9"><?php echo __('Save & Close'); ?></div>
								</div>
							</button>
						</div>
						<div class="opt-row-detail">
							<button class="btn btn-default" id="btn_trgrsaveedit" onclick="triggerXrFormSaveEdit();" type="button" style="width:100%" data-ajax="false" data-role="none">
								<div class="col-xs-12 pointer">
									<div class="col-xs-3"><i class="fa fa-pencil-square-o iconsize"></i></div>
									<div class="col-xs-9"><?php echo __('Save & Continue Editing'); ?></div>
								</div>
							</button>
						</div>
						<div class="opt-row-detail">
							<a href="javascript:void(0);" class="btn btn-default confirm" id="btn_revertdata" data-onclick="triggerXrFormReset();" data-allow="<?php echo (Helper_Common::getAllowAllAccessByUser((Session::instance()->get('user_allow_page') ? Session::instance()->get('user_allow_page') : '1'), 'is_confirm_xr_hidden') ? 'false' : 'true'); ?>" data-notename="hide_confirm_xr" data-text="This will discard any changes on this record. Do you want to SAVE or Continue with exiting?" style="width:100%" data-ajax="false" data-role="none">
								<div class="col-xs-12 pointer">
									<div class="col-xs-3"><i class="fa fa-refresh iconsize"></i></div>
									<div class="col-xs-9"><?php echo __('Revert to Saved'); ?></div>
								</div>
							</a>
						</div>
						<div class="opt-row-detail">
							<a href="<?php echo URL::base(TRUE).'admin/exercise/create?act='.$openfrom; ?>" class="btn btn-default" id="btn_addrecord" style="width:100%" data-ajax="false" data-role="none">
								<div class="col-xs-12 pointer">
									<div class="col-xs-3"><i class="fa fa-plus-square-o iconsize"></i></div>
									<div class="col-xs-9"><?php echo __('Create a New Record'); ?></div>
								</div>
							</a>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" onclick="$('#xrcisesaveopt-modal').modal('hidecustom');" data-ajax="false" data-role="none"><?php echo __('Close'); ?></button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- preview feature image modal -->
	<div id="previewimg-modal" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
		<div class="vertical-alignment-helper">
			<div class="modal-dialog">
				<div class="modal-content aligncenter">
					<div class="modal-header">
						<div class="row">
							<div class="popup-title">
								<div class="col-xs-2">
									<a href="javascript:void(0);" title="Back" class="triangle" onclick="$('#previewimg-modal').modal('hidecustom');">
										<i class="fa fa-caret-left iconsize"></i>
									</a>
								</div>
								<div class="col-xs-8"><?php echo __('Preview - Feature Image'); ?></div>
								<div class="col-xs-2"> </div>
							</div>
						</div>
					</div>
					<div class="modal-body" id="preview_featimg">
						<i class="fa fa-file-image-o prevfeat"></i>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" onclick="$('#previewimg-modal').modal('hidecustom');"><?php echo __('Close'); ?></button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- img option modal -->
	<div id="imageoption-modal" class="modal fade bs-example-modal-sm" role="dialog" data-keyboard="false" data-backdrop="static">
		<div class="vertical-alignment-helper">
			<div class="modal-dialog modal-md">
				<div class="modal-content">
					<div class="modal-header">
						<div class="mobpadding">
							<div class="border">
								<div class="col-xs-2">
									<a href="#" title="Back" onclick="$('#imageoption-modal').modal('hidecustom');" class="triangle">
										<i class="fa fa-chevron-left"></i>
									</a>
								</div>
								<div class="col-xs-8 optionpoptitle"><?php echo __('Options for Image'); ?></div>
								<div class="col-xs-2"></div>
							</div>
						</div>
					</div>
					<div class="modal-body opt-body">
						<div class="opt-row-detail">
							<a href="javascript:void(0);" id="btn_imgedit" class="btn btn-default edit-img cboxElement" style="width:100%">
								<div class="col-xs-12 pointer">
									<div class="col-xs-3"><i class="fa fa-edit iconsize"></i></div>
									<div class="col-xs-9"><?php echo __('Edit'); ?></div>
								</div>
							</a>
						</div>
						<div class="opt-row-detail">
							<a href="javascript:void(0);" id="btn_imgpreview" class="btn btn-default img_mgr featimgprev" onclick="triggerImgPreviewModal('intro_feature');" style="width:100%">
								<div class="col-xs-12 pointer">
									<div class="col-xs-3"><i class="fa fa-eye iconsize"></i></div>
									<div class="col-xs-9"><?php echo __('Preview'); ?></div>
								</div>
							</a>
						</div>
						<div class="opt-row-detail">
							<a href="<?php echo URL::base_lang().'assets/images/icons/icon_grey-62.png'; ?>" id="btn_imgclear" data-clearid="" class="btn btn-default img_mgr img_clear" style="width:100%">
								<div class="col-xs-12 pointer">
									<div class="col-xs-3"><i class="fa fa-refresh iconsize"></i></div>
									<div class="col-xs-9"><?php echo __('Clear'); ?></div>
								</div>
							</a>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" onclick="$('#imageoption-modal').modal('hidecustom');"><?php echo __('Close'); ?></button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="xrciseprev-modal" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static"></div>
	<div id="myOptionsModalExerciseRecord" class="modal fade bs-example-modal-sm" role="dialog" data-keyboard="false" data-backdrop="static"></div>
	<?php /*********************************************************/ ?>
</div>
<?php /****************Ends Here*************************/ ?>
</div>
<!-- /.container-fluid -->
</div>
<!-- /#page-wrapper -->
</body>
<script type="text/javascript">
	$('#xrRecInsertForm .tab-content').tooltip();
</script>
<?php echo $imgeditor2; //require_once(APPPATH.'views/templates/front/imglib-imgeditor.php'); ?>