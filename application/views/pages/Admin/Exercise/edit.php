	<!--- Top nav && left nav--->
	<?php echo $topnav.$leftnav;?>
	<!--- Top nav && left nav --->
		<!-- Content Wrapper. Contains page content -->
		<div id="page-wrapper">
				<div class="container-fluid">
					 <!-- Page Heading -->
					 <div class="row">
						  <div class="col-lg-12">
								<h1 class="page-header">
									 Update Exercise
								</h1>
								<ol class="breadcrumb">
									 <li>
										  <i class="fa fa-dashboard"></i>  <a href="<?php echo URL::base().'admin/index'; ?>">Dashboard</a>
									 </li>
									 <li>
										  Exercise
									 </li>
									 <li class="active">
										  <i class="fa fa-edit"></i> <?php echo (isset($sites->name) ? $sites->name : 'Update Exercise');?>
									 </li>
								</ol>
						  </div>
					 </div>
					 <!-- /.row -->
					 <?php if(isset($errors) && count($errors)>0) {  ?>
						  <div class="row">
								<div class="col-lg-12">
									 <div class="alert alert-danger">						  
										<?php foreach($errors as $key => $value) { ?>
										  <i class="fa fa-exclamation-triangle"></i><span><?php echo $value; ?></span>
										<?php } ?>
									 </div>
								</div>
						  </div>
				<?php }
				//if(isset($success) && $success!='') {  ?>
					<div class="row"> 
						<div class="col-lg-12">
							<div id="create-record"> 
								<div class="xrwrapper-div ">		
									
									<hr>
									<div class="xrwrappers common-class" style="">
										<form id="xrRecInsertForm" class="form-horizontal" method="post" action="">
											<div class="form-group has-feedback has-error hide" id="messageContainer">
												<div class="col-xs-9 col-xs-offset-3">
													<div>Please Fill The Required Fields In All Tabs</div>
												</div>
											</div>
											<div class="alignright refreshbtn pull-right">
												<button id="btn_revert1" class="btn btn-default" type="button"><i class="fa fa-refresh refresh"></i> Clear</button>
											</div>
											<div class="tab-bars">
												<i class="fa fa-bars iconsize2 bluecol collapsed" id="tabs-bar" data-toggle="collapse" data-target="#xrRec-tab" aria-expanded="false"></i>
											</div>
											<ul class="nav nav-pills collapse" id="xrRec-tab" aria-expanded="false">
												<li class="active"><a href="#file" data-toggle="tab">File</a></li>
												<li><a href="#intro" data-toggle="tab">Intro</a></li>
												<li><a href="#details" data-toggle="tab">Details</a></li>
												<li><a href="#data" data-toggle="tab">Data</a></li>
												<li><a href="#tags" data-toggle="tab">Tags</a></li>
											</ul>

											<div class="tab-content">
												<!-- file tab -->
												<div class="tab-pane active" id="file">
													<div class="tab_heading aligncenter libtab-title bluecol">Basic Record Data</div>
													<hr>
													<div class="form-group">
														<div class="col-xs-4"><label class="control-label">Title</label></div>
														<div class="col-xs-8">
															<input type="text" class="form-control" id="xru_title" value="<?php echo (isset($exerciseArray['title'])) ? $exerciseArray['title'] : '';?>" name="xru_title" placeholder="Title" />
														</div>
													</div>
													<hr>
													<?php if(isset($exerciseType) && count($exerciseType)>0) { ?>
													<div class="form-group">
														<div class="col-xs-4"><label class="control-label">Type of Activity</label></div>
														<div class="col-xs-8">
															<div class="dropdown selectdropdownTwo">
																<select tabindex="2" class="" id="xru_type" name="xru_type">
																	<option value="">Select an option</option>
																	<?php foreach($exerciseType as $key => $value) { ?>
																		<option value="<?php echo $value['type_id'];?>"<?php if(isset($exerciseArray['type_id'])&&$exerciseArray['type_id']==$value['type_id']) echo "selected";?>><?php echo $value['type_title']; ?></option>
																	<?php } ?>
																</select>
															</div>
														</div>
													</div>
													<hr>
													<?php } if(isset($exerciseStatus) && count($exerciseStatus)>0) { ?>
													<div class="form-group">
														<div class="col-xs-4"><label class="control-label">Status</label></div>
														<div class="col-xs-8">
															<div class="dropdown selectdropdownTwo">
																<select tabindex="2" class="" id="xru_status" name="xru_status">
																	<option value="">Select an option</option>
																	<?php foreach($exerciseStatus as $key => $value) { ?>
																		<option value="<?php echo $value['status_id']; ?>"<?php if(isset($exerciseArray['status_id'])&&$exerciseArray['status_id']==$value['status_id']) echo "selected";?>><?php echo $value['status_title']; ?></option>
																	<?php } ?>
																</select>
															</div>
														</div>
													</div>
													<hr>
													<?php } ?>
												</div>

												<!-- intro tab -->
												<div class="tab-pane" id="intro">
													<div class="tab_heading aligncenter libtab-title bluecol">Exercise Introduction</div>
													<hr>
													<div class="form-group img-div">
														<div class="col-xs-4"><label class="control-label">Feature Image</label></div>
														<div class="col-xs-5">
															<span class="img_thmb">
																<img alt="Feature Image" class="uploaded_image_thmb" id="intro_feature" src="<?php echo (isset($exerciseArray['img_url'])) ? URL::base_lang().$exerciseArray['img_url'] : URL::base_lang().'assets/images/icons/icon_grey-62.png';?>">
															</span>
															<input type="hidden" class="img_selected" id="xru_featImage" name="xru_featImage" value="<?php echo (isset($exerciseArray['feat_img'])) ? $exerciseArray['feat_img'] : '';?>">
														</div>
														<div class="col-xs-3"></div>
														<div class="col-xs-3 img-opt">
															<i class="fa fa-ellipsis-h iconsize trigger-imgopt" id="introclear" data-imgtagid="intro_feature" data-hidnimgid="xru_featImage" href="<?php echo URL::base_lang().'assets/images/icons/icon_grey-62.png'; ?>"></i>
														</div>
													</div>
													<hr>
													<div class="form-group">
														<div class="col-xs-4"><label class="control-label">Feature Video</label>
															<img class="info-icon" src="<?php echo URL::base_lang().'assets/images/icons/information.png'; ?>">
															<span class="hide tooltip" id="tooltip1">
															<a title="" href="#">
																<span class="tooltip">
																	Share a video link from youtube.<br><br>This video will be displayed in the lightbox pop-up.<br><br>This video will be visible when the record's <em>ad banner</em> has been clicked form the lightbox pop-up.
																</span>
															</a>
															</span>
														</div>
														<div class="col-xs-8">
															<input type="text" tabindex="2" value="<?php echo (isset($exerciseArray['feat_vid'])) ? $exerciseArray['feat_vid'] : '';?>" placeholder="Youtube or Vimeo URL here" id="xru_featVideo" name="xru_featVideo" class="form-control">
														</div>
													</div>
												</div>

												<!-- details tab -->
												<div class="tab-pane" id="details">
													<div class="tab_heading aligncenter libtab-title bluecol">Descriptions</div>
													<hr>					        	
													<div class="form-group">
														<div class="col-xs-4"><label class="control-label">Brief Description</label>
															<img class="info-icon" src="<?php echo URL::base_lang().'assets/images/icons/information.png'; ?>">
															<span  id="tooltip1" class="tooltip hide">
																<a title="" href="#">
																	<span>
																		This section is aimed to provide GENERAL details about this exercise or movement.<br><br><strong>Note:</strong>Share up to 1,000 characters of content.<br><br><em>Note: </em>This information WILL NOT scroll vertically.<br><br>
																	</span>
																</a>
															</span>
														</div>
														<div class="col-xs-8">
															<textarea tabindex="1" placeholder="No content. Click to update." name="xru_descbr" id="xru_descbr" class="form-control"><?php echo (isset($exerciseArray['descbr'])) ? $exerciseArray['descbr'] : '';?></textarea>
														</div>
													</div>
													<hr>
													<div class="form-group">
														<div class="col-xs-4"><label class="control-label">In-Depth Description</label>
															<img class="info-icon" src="<?php echo URL::base_lang().'assets/images/icons/information.png'; ?>">
															<span class="hide tooltip" id="tooltip1">
																<a title="" href="#">												
																	<span >
																		This section is aimed to provide more thorough details, benefits &amp; applications.<br><br><strong>Note:</strong>Share up to 1,000 characters of content.<br><br>This information will scroll vertically.
																	</span>
																</a>
															</span>
														</div>
														<div class="col-xs-8">
															<textarea tabindex="2" placeholder="No content. Click to update." name="xru_descfull" id="xru_descfull" class="form-control"><?php echo (isset($exerciseArray['descfull'])) ? $exerciseArray['descfull'] : '';?></textarea>
														</div>
													</div>
													<hr>
													<div class="tab_heading aligncenter seq_title bluecol">Sequences</div>							
													<hr>
													<div class="form-group">
														<div class="col-xs-12">
															<ul id="seq_list">
															<?php if(isset($exerciseSeq) && count($exerciseSeq)>0) { 
																$si = 1; $rd = count($exerciseSeq)-1; $ri=0;
																foreach ($exerciseSeq as $seqkey => $seqvalues) { ?>
																	<li class="seq_order=<?php echo $si; ?> seq-panel">
																		<div class="row subseq-title">
																			<div class="col-xs-2 aligncenter">
																				<span class="seq-move fa fa-arrows"></span>
																			</div>
																			<div class="col-xs-8 aligncenter">	
																				<span class="seq_title">Sequence <?php echo $si; ?></span>
																			</div>
																			<div class="col-xs-2 aligncenter">	
																				<span class="seq_remove right remove_seq seq_btn fa fa-times" data-class="remove_seq"></span>
																			</div>
																		</div>
																		<hr>
																		<div class="seq_content">
																			<div class="seq_img form-group form-group_seq img-div">
																				<div class="col-xs-4">
																					<label class="control-label" for="seq_img<?php echo $si; ?>">
																						<span>Sequence Image</span>
																					</label> <!-- /END label -->
																				</div>
																				<div class="col-xs-5">
																					<span class="img_thmb">
																						<img alt="Feature Image" class="uploaded_image_thmb" id="seq-feature<?php echo $si; ?>" src="<?php echo (isset($seqvalues['img_url'])) ? '/'.$seqvalues['img_url'] : URL::base_lang().'assets/images/icons/icon_grey-62.png'; ?>">
																					</span>
																					<input type="hidden" value="<?php echo (isset($seqvalues['img_id'])) ? $seqvalues['img_id'] : ''; ?>" id="seq_img<?php echo $si; ?>" name="seqImg[]" class="img_selected">
																				</div>
																				<div class="col-xs-3"></div>
																				<div class="col-xs-3 img-opt">
																					<i class="fa fa-ellipsis-h iconsize trigger-imgopt" id="seqclear<?php echo $si; ?>" data-imgtagid="seq-feature<?php echo $si; ?>" data-hidnimgid="seq_img<?php echo $si; ?>" href="<?php echo URL::base_lang().'assets/images/icons/icon_grey-62.png'; ?>"></i>
																				</div>
																			</div> <!-- /END .seq_img -->
																			<hr>
																			<div class="seq_desc form-group">
																				<div class="col-xs-4">
																					<label class="control-label" for="seqDesc<?php echo $si; ?>">
																						<span>Sequence Description <img class="info-icon" src="<?php echo URL::base_lang().'assets/images/icons/information.png'; ?>">
																							<span id="tooltip1" class="tooltip">
																								<span>
																									This information will scroll vertically.<br>The count of sequences per exercise is unlimited.
																								</span>
																							</span>
																						</span>
																					</label>
																				</div>
																				<div class="col-xs-8">
																					<textarea id="seqDesc<?php echo $si; ?>" placeholder="No content. Click to update." class="seq_desc[] form-control" name="seqDesc[]"><?php echo (isset($seqvalues['seq_desc'])) ? $seqvalues['seq_desc'] : ''; ?></textarea>
																				</div>
																			</div> <!-- /END seq_desc -->
																		</div> <!-- /END seq_content -->
																	</li>
																<?php $si++; $rd--; $ri++; }
																} else { ?>
																	<li class="seq_order=1 seq-panel">
																		<div class="row subseq-title">
																			<div class="col-xs-2 aligncenter">
																				<span class="seq-move fa fa-arrows"></span>
																			</div>
																			<div class="col-xs-8 aligncenter">	
																				<span class="seq_title">Sequence 1</span>
																			</div>
																			<div class="col-xs-2 aligncenter">	
																				<span class="seq_remove right remove_seq seq_btn fa fa-times" data-class="remove_seq"></span>
																			</div>
																		</div>
																		<hr>
																		<div class="seq_content">
																			<div class="seq_img form-group form-group_seq img-div">
																				<div class="col-xs-4">
																					<label class="control-label" for="seq_img1">
																						<span>Sequence Image</span>
																					</label> <!-- /END label -->
																				</div>
																				<div class="col-xs-5">
																					<span class="img_thmb">
																						<img alt="Feature Image" class="uploaded_image_thmb" id="seq-feature1" src="<?php echo URL::base_lang().'assets/images/icons/icon_grey-62.png'; ?>">
																					</span>
																					<input type="hidden" value="" id="seq_img1" name="seqImg[]" class="img_selected">
																				</div>
																				<div class="col-xs-3"></div>
																				<div class="col-xs-3 img-opt">
																					<i class="fa fa-ellipsis-h iconsize trigger-imgopt" id="seqclear1" data-imgtagid="seq-feature1" data-hidnimgid="seq_img1" href="<?php echo URL::base_lang().'assets/images/icons/icon_grey-62.png'; ?>"></i>
																				</div>
																			</div> <!-- /END .seq_img -->
																			<hr>
																			<div class="seq_desc form-group">
																				<div class="col-xs-4">
																					<label class="control-label" for="seqDesc1">
																						<span>Sequence Description <img class="info-icon" src="<?php echo URL::base_lang().'assets/images/icons/information.png'; ?>">
																							<span id="tooltip1" class="tooltip">
																								<span>
																									This information will scroll vertically.<br>The count of sequences per exercise is unlimited.
																								</span>
																							</span>
																						</span>
																					</label>
																				</div>
																				<div class="col-xs-8">
																					<textarea id="seqDesc1" placeholder="No content. Click to update." class="seq_desc[] form-control" name="seqDesc[]"></textarea>
																				</div>
																			</div> <!-- /END seq_desc -->
																		</div> <!-- /END seq_content -->
																	</li>
																<?php } ?>
															</ul>
														</div>
														<!-- <input type="hidden" value="2" name="seqsID"> -->
													</div>
													<div class="form-group">
														<div class="col-xs-12">
															<button class="btn seq_btn add_seq btn-default" value="addSeq" name="addSeq" type="button" data-class="add_seq"><i class="fa fa-plus"></i> Add New Sequence</button>
														</div>
													</div>
													<div class="form-group seqerror">
														<div class="col-xs-12">
															<small></small>
														</div>
													</div>
												</div>

												<!-- data tab -->
												<div class="tab-pane" id="data">
													<div class="tab_heading aligncenter libtab-title bluecol">Filtering Data</div>
													<hr>			        					        	
													<div class="form-group">
														<div class="col-xs-4"><label class="control-label">Main Muscles Worked</label></div>
														<?php $muscle = Model::instance('Model/workouts')->getcheckboxes('muscle','unit_','_title','_id','unit_gendata','muscle','0');
														if(isset($muscle) && count($muscle)>0) { ?>
														<div class="col-xs-8">
															<div class="dropdown selectdropdownTwo">
																<select tabindex="7" class="" id="xru_musprim" name="xru_musprim">
																	<option value="">Select an option</option>
																	<?php foreach($muscle as $key => $value){ ?>
																		<option value="<?php echo $value['muscle_id'];?>"<?php if(isset($exerciseArray['muscle_id'])&&$exerciseArray['muscle_id']==$value['muscle_id']) echo "selected";?>><?php echo $value['muscle_title'];?></option>
																	<?php } ?>
																</select>
															</div>
														</div>
														<?php } ?>
													</div>
													<hr>
													<div class="form-group">
														<div class="col-xs-4 no-margin"><label class="control-label">Additional Muscles Worked</label></div>
														<?php $XrciseMusOth=array();
														if(isset($exerciseMusOth) && count($exerciseMusOth)>0){
															foreach ($exerciseMusOth as $extactMusoth) {
																foreach ($extactMusoth as $key => $value) {
																	if($key == 'musoth_id'){
																		$XrciseMusOth[] = $value;
																	}
																}
															}
														} ?>
														<?php if(isset($muscle) && count($muscle)>0) { ?>
														<div class="col-xs-8">
															<div class="musothFrame left">
																<ul class="musoth_chks col-sm-12">
																	<?php foreach($muscle as $key => $value){ ?>
																		<li id="muscle_id<?php echo $value['muscle_id']?>" class="col-sm-6 musoth_container">
																			<div class="checkbox">
																				<input type="checkbox" tabindex="8" value="<?php echo $value['muscle_id']?>" class="musoth_chkbx" id="muscle_id<?php echo $value['muscle_id']?>" name="chkdMusOth[]"<?php if(count($XrciseMusOth) > 0 && in_array($value['muscle_id'], $XrciseMusOth)==true) echo "checked";?>/>
																				<label for="chkdMusOth[]"><?php echo $value['muscle_title'];?></label>
																				<div style="clear:left;"></div>
																			</div>
																		</li>
																	<?php } ?>
																	<!-- <input type="hidden" value="" id="xru_musothID" name="xru_musothID"> -->
																</ul>
															</div>
														</div>
														<?php } ?>
													</div>
													<hr>
													<?php $equip = Model::instance('Model/workouts')->getcheckboxes('equip','unit_','_title','_id','unit_gendata','equip','0'); 
													if(isset($equip) && count($equip)>0) { ?>
													<div class="form-group">
														<div class="col-xs-4"><label class="control-label">Equipment</label></div>
														<div class="col-xs-8">
															<div class="dropdown selectdropdownTwo">
																<select tabindex="9" class="" id="xru_equip" name="xru_equip">
																	<option value="">Select an option</option>
																	<?php foreach($equip as $key => $value) { ?>
																		<option value="<?php echo $value['equip_id'];?>"<?php if(isset($exerciseArray['equip_id'])&&$exerciseArray['equip_id']==$value['equip_id']) echo "selected";?>><?php echo $value['equip_title'];?></option>
																	<?php } ?>
																</select>
															</div>
														</div>
													</div>
													<hr>
													<?php } 

													$mech = Model::instance('Model/workouts')->getcheckboxes('mech','unit_','_title','_id','unit_gendata','mech','0');
													if(isset($mech) && count($mech)>0) { ?>
													<div class="form-group">
														<div class="col-xs-4"><label class="control-label">Mechanics Type</label></div>
														<div class="col-xs-8">
															<div class="dropdown selectdropdownTwo">
																<select tabindex="10" class="" id="xru_mech" name="xru_mech">
																	<option value="">Select an option</option>
																	<?php foreach($mech as $key => $value) { ?>
																		<option value="<?php echo $value['mech_id'];?>"<?php if(isset($exerciseArray['mech_id'])&&$exerciseArray['mech_id']==$value['mech_id']) echo "selected";?>><?php echo $value['mech_title'];?></option>
																	<?php } ?>
																</select>						
															</div>
														</div>
													</div>
													<hr>
													<?php } 
												
													$level = Model::instance('Model/workouts')->getcheckboxes('level','unit_','_title','_id','unit_gendata','level','0');
													if(isset($level) && count($level)>0) { ?>
													<div class="form-group">
														<div class="col-xs-4"><label class="control-label">Level</label></div>
														<div class="col-xs-8">
															<div class="dropdown selectdropdownTwo">
																<select tabindex="11" class="" id="xru_level" name="xru_level">
																	<option value="">Select an option</option>
																	<?php foreach($level as $key => $value) { ?>
																		<option value="<?php echo $value['level_id'];?>"<?php if(isset($exerciseArray['level_id'])&&$exerciseArray['level_id']==$value['level_id']) echo "selected";?>><?php echo $value['level_title'];?></option>
																	<?php } ?>
																</select>
															</div>
														</div>
													</div>
													<hr>
													<?php } 
													
													$sport = Model::instance('Model/workouts')->getcheckboxes('sport','unit_','_title','_id','unit_gendata','sport','0');
													if(isset($sport) && count($sport)>0) { ?>
													<div class="form-group">
														<div class="col-xs-4"><label class="control-label">sports</label></div>
														<div class="col-xs-8">
															<div class="dropdown selectdropdownTwo">
																<select tabindex="12" class="" id="xru_sports" name="xru_sports">
																	<option value="">Select an option</option>
																	<?php foreach($sport as $key => $value) { ?>
																		<option value="<?php echo $value['sport_id'];?>"<?php if(isset($exerciseArray['sport_id'])&&$exerciseArray['sport_id']==$value['sport_id']) echo "selected";?>><?php echo $value['sport_title'];?></option>
																	<?php } ?>
																</select>
															</div>
														</div>
													</div>
													<hr>
													<?php } 
													
													$force = Model::instance('Model/workouts')->getcheckboxes('force','unit_','_title','_id','unit_gendata','force','0');
													if(isset($force) && count($force)>0) { ?>	
													<div class="form-group">
														<div class="col-xs-4"><label class="control-label">Force / Movement</label></div>
														<div class="col-xs-8">
															<div class="dropdown selectdropdownTwo">
																<select tabindex="13" class="" id="xru_force" name="xru_force">
																	<option value="">Select an option</option>
																	<?php foreach($force as $key => $value) { ?>
																		<option value="<?php echo $value['force_id'];?>"<?php if(isset($exerciseArray['force_id'])&&$exerciseArray['force_id']==$value['force_id']) echo "selected";?>><?php echo $value['force_title'];?></option>
																	<?php } ?>
																</select>
															</div>
														</div>
													</div>
													<?php } ?>
												</div>

												<!-- tags tab -->
												<div class="tab-pane" id="tags">
													<div class="tab_heading aligncenter libtab-title bluecol">Tags</div>	
													<hr>				        	
													<div class="form-group">
														<div class="col-xs-4 no-margin"><label class="control-label">Tags</label></div>
														<div class="col-xs-8 no-margin">
															<?php if(isset($exerciseTags) && count($exerciseTags)>0){ 
																$tagarr=array();
																foreach ($exerciseTags as $tagkey => $tagvalue) {
																	$tagarry[] = ($tagvalue['tag_title']);
																}
																$tagval = implode(',', $tagarry);
															} ?>
															<input type="text" class="form-control xru_Tags" name="xru_Tags" value="<?php echo (!empty($tagval) ? $tagval : ''); ?>" data-role="tagsinput"/>
														</div>
													</div>
												</div>
												
												<!-- Previous/Next buttons -->
												<ul class="pager wizard">
													<li class="previous"><a href="javascript: void(0);"><i class="fa fa-caret-left iconsize"></i> Previous</a></li>
													<li class="next"><a href="javascript: void(0);">Next <i class="fa fa-caret-right iconsize"></i></a></li>
												</ul>
											</div>
											<button type="button" id="submitTabsBtn" class="btn" data-toggle="modal" data-target="#xrcisesaveopt-modal">
												<i class="fa fa-check-square-o"></i>
											</button>
											<button class="btn hide" type="submit" id="btn_saveclose" value="save" name="f_method"></button>
											<button class="btn hide" type="submit" id="btn_savecontn" value="save-edit" name="f_method"></button>
											<div id="xrcisesaveopt-modal" class="modal fade bs-example-modal-sm in" role="dialog">
												<div class="modal-dialog modal-sm">
													<div class="modal-content">
														<div class="modal-header">
															<div class="mobpadding">
																<div class="border">
																	<div class="col-xs-2">
																		<a href="#" title="Back" data-dismiss="modal" class="triangle">
																			<i class="fa fa-chevron-left"></i>
																		</a>
																	</div>
																	<div class="col-xs-8 optionpoptitle">Options for Saving</div>
																	<div class="col-xs-2"></div>
																</div>
															</div>
														</div>
														<div class="modal-body opt-body">
															<div class="opt-row-detail">
																<button class="btn btn-default" id="btn_trgrsave" onclick="triggerXrFormSave();" type="button" style="width:100%">
																	<div class="col-xs-12 pointer">
																		<div class="col-xs-3"><i class="fa fa-save iconsize"></i></div>
																		<div class="col-xs-9">Save</div>
																	</div>
																</button>
															</div>
															<div class="opt-row-detail">
																<button class="btn btn-default" id="btn_trgrsaveedit" onclick="triggerXrFormSaveEdit();" type="button" style="width:100%">
																	<div class="col-xs-12 pointer">
																		<div class="col-xs-3"><i class="fa fa-pencil-square-o iconsize"></i></div>
																		<div class="col-xs-9">Save &amp; Continue Editing</div>
																	</div>
																</button>
															</div>
															<div class="opt-row-detail">
																<button class="btn btn-default" id="btn_revertdata" onclick="triggerXrFormReset();" type="button" style="width:100%">
																	<div class="col-xs-12 pointer">
																		<div class="col-xs-3"><i class="fa fa-refresh iconsize"></i></div>
																		<div class="col-xs-9">Revert to Saved</div>
																	</div>
																</button>
															</div>
															<div class="opt-row-detail">
																<button class="btn btn-default" id="btn_addrecord" onclick="triggerXrUnitCreate();" type="button" style="width:100%">
																	<div class="col-xs-12 pointer">
																		<div class="col-xs-3"><i class="fa fa-plus-square-o iconsize"></i></div>
																		<div class="col-xs-9">Add a New Record</div>
																	</div>
																</button>
															</div>
														</div>
														<div class="modal-footer">
															<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
														</div>
													</div>
												</div>
											</div>
										</form>
									</div>
								</div>
							  </div>
						</div>
					</div>
				<?php //} ?>
					 
					 <!-- /.row -->
				</div>
				<!-- /.container-fluid -->
		  </div>
		  <!-- /#page-wrapper -->
	 </div>
	 <!-- /#wrapper -->
	 <!-- jQuery -->   
</body>