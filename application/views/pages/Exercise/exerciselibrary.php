<div id="wrap-index">
<!-- Login header nav !-->
<?php echo $topHeader; ?>
	<div class="container" id="home"><!-- <div class="rest-nav-head"> -->
		<div class="">
			<?php $session = Session::instance();
			$Urlparam = urldecode(Request::current()->param( 'id' ));
			if ($session->get('success')): ?>
				<div class="row bannermsg">
					<div class="col-sm-12 col-xs-12 col-md-12 banner success alert alert-success">
						<a data-ajax="false" data-role="none" href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
						<?php echo $session->get_once('success') ?>
					</div>
				</div>
			<?php endif;
			if ($session->get('error')): ?>
				<div class="row bannermsg">
					<div class="col-sm-12 col-xs-12 col-md-12 banner errors alert alert-danger">
						<a data-ajax="false" data-role="none" href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
						<?php echo $session->get_once('error') ?>
					</div>
				</div>
			<?php endif; 
			if(empty($Urlparam) && $Urlparam != '0'){ ?>
				<div class="exercise-nav-index">
					<div class="row">
						<div class="page-head">
							<div class="col-xs-3 aligncenter">
								<a href="<?php echo URL::base(TRUE).'dashboard/index'; ?>" title="<?php echo __('Back'); ?>" data-ajax="false" data-role="none">
									<i class="fa fa-caret-left iconsize"></i>
								</a>
							</div>
							<div class="col-xs-8 aligncenter centerheight"><?php echo __('Exercise Library'); ?></div>
							<div class="col-xs-3 aligncenter"></div>
						</div>
					</div>
					<hr>
					<?php if(Helper_Common::hasAccess('Create Exercise')){ ?>
						<div class="row tour-step tour-step-eleven">
							<a href="<?php echo URL::base(TRUE).'exercise/exerciserecord?act=lib'; ?>" data-ajax="false" data-role="none">
								<div class="col-xs-12 page-head-row">
									<div class="col-xs-3 aligncenter">
										<i class="fa fa-plus iconsize2 activedatacol"></i>
									</div>
									<div class="col-xs-8 activedatacol" title="<?php echo __('Create A New Exercise Record'); ?>">
										<?php echo __('Create A New Exercise Record'); ?>
									</div>
								</div>
							</a>
						</div>
						<hr>
					<?php } ?>
					<div class="row tour-step tour-step-myxr">
						<a href="<?php echo (($myxrcisecnt > 0) ? URL::base(TRUE).'exercise/exerciselibrary/myexercise' : 'javascript:void(0);'); ?>" data-ajax="false" data-role="none">
							<div class="col-xs-12 page-head-row">
								<div class="col-xs-3 aligncenter">
									<i class="fa fa-folder-o iconsize2 <?php echo (($myxrcisecnt > 0) ? 'activedatacol' : 'datacol');?>"></i>
								</div>
								<div class="col-xs-8 <?php echo (($myxrcisecnt > 0) ? 'activedatacol' : 'datacol');?>" title="<?php echo __('My Exercises'); ?>">
									<?php echo __('My Exercises'); ?>&nbsp;(<?php echo number_format($myxrcisecnt); ?>)
								</div>
							</div>
						</a>
					</div>
					<hr>
					<div class="row tour-step tour-step-samplexr">
						<a href="<?php echo (($samplexrcisecnt > 0) ? URL::base(TRUE).'exercise/exerciselibrary/sampleexercise' : 'javascript:void(0);');?>" data-ajax="false" data-role="none">
							<div class="col-xs-12 page-head-row">
								<div class="col-xs-3 aligncenter">
									<i class="fa fa-folder-o iconsize2 <?php echo (($samplexrcisecnt > 0) ? 'activedatacol' : 'datacol');?>"></i>
								</div>
								<div class="col-xs-8 <?php echo (($samplexrcisecnt > 0) ? 'activedatacol' : 'datacol');?>" title="<?php echo __('Sample Exercises'); ?>">
									<?php echo __('Sample Exercises'); ?>&nbsp;(<?php echo number_format($samplexrcisecnt); ?>)
								</div>
							</div>
						</a>
					</div>
					<hr>
					<div class="row tour-step tour-step-sharexr">
						<a href="<?php echo (($sharedxrcisecnt > 0) ? URL::base(TRUE).'exercise/exerciselibrary/sharedexercise' : 'javascript:void(0);');?>" data-ajax="false" data-role="none">
							<div class="col-xs-12 page-head-row">
								<div class="col-xs-3 aligncenter">
									<i class="fa fa-folder-o iconsize2 <?php echo (($sharedxrcisecnt > 0) ? 'activedatacol' : 'datacol');?>"></i>
								</div>
								<div class="col-xs-8 <?php echo (($sharedxrcisecnt > 0) ? 'activedatacol' : 'datacol');?>" title="<?php echo __('Shared Exercises'); ?>">
									<?php echo __('Shared Exercises'); ?>&nbsp;(<?php echo number_format($sharedxrcisecnt); ?>)
									<?php if(isset($sharedunreadcnt) && $sharedunreadcnt>0){ ?>
										<span class="actioncount"><?php echo '&nbsp;&nbsp;'.$sharedunreadcnt.'&nbsp;&nbsp;';?></span>
									<?php } ?>
								</div>
							</div>
						</a>
					</div>
				</div>
			<?php }else{
				$foldertitle = ''; $activefolderid = 0;
				if($Urlparam == 'myexercise' || $Urlparam == '0'){
					$foldertitle = 'My Exercises ('.number_format($myxrcisecnt).')';
					$activefolderid = 0;
				}elseif($Urlparam == 'sampleexercise' || $Urlparam == '1' || $Urlparam == '2'){
					$foldertitle = 'Sample Exercises ('.number_format($samplexrcisecnt).')';
					$activefolderid = 2;
				}elseif($Urlparam == 'sharedexercise' || $Urlparam == '3'){
					$foldertitle = 'Shared Exercises ('.number_format($sharedxrcisecnt).')';
					$activefolderid = 3;
				} ?>
				<!-- exercise gallery -->
				<div id="record-gallery">
					<div class="gallery-div" data-activefolder="<?php echo $Urlparam; ?>">
						<form action="" method="post" id="filterDataForm" data-ajax="false" data-role="none">
							<div class="row search-header-row">
								<div class="page-head">
									<div class="col-xs-2 aligncenter">
										<a href="<?php echo URL::base(TRUE).'exercise/exerciselibrary/'; ?>" title="<?php echo __('Back'); ?>" class="triangle" data-ajax="false" data-role="none">
											<i class="fa fa-caret-left iconsize"></i>
										</a>
									</div>
									<div class="col-xs-8 aligncenter centerheight" id="Lib-title"><?php echo $foldertitle; ?></div>
									<div class="col-xs-2 aligncenter"></div>
								</div>
								<div class="xrpage-searcher col-xs-12">
									<div class="row">
										<div class="col-xs-9 aligncenter xr-searcher">
											<div id="xr_filter_search" class="tour-step tour-step-seventeen">
												<input type="text" name="autosearch" class="searchtext form-control input-sm" placeholder="<?php echo __('Search by exercise name'); ?>..." onfocus="this.value = this.value;" data-ajax="false" data-role="none"/>
												<span class="searchclear fa fa-remove" style="display: none;"></span>
												<button class="btn btn-default show-searchfilter filter-close" type="button" data-class="filter_this" title="<?php echo __('Show/Hide Filters'); ?>" data-ajax="false" data-role="none"><i class="fa fa-caret-down iconsize filter-i"></i></button>
											</div>
										</div>
										<div class="col-xs-3 aligncenter filter-div btns-block">
											<div class="fetchbtn">
												<div id="xr_filter_toggle" class="btn btn-default btncol tour-step tour-step-eighteen" data-class="fetch_this" title="<?php echo __('Fetch Records'); ?>"><?php echo __('Fetch'); ?></div>
												<div id="xr_filter_reset" class="btn btn-default btncol reset_this" style="display: none;" title="<?php echo __('Reset Search'); ?>"><?php echo __('Reset'); ?></div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-xs-12 sorting-div" style="display: none;">
									<div class="col-xs-12 filter-tools">
										<div class="row">
											<div class="col-xs-6">
												<div id="xr_filter_search_sort">
													<div style="float:left" class="tour-step tour-step-nineteen">
														<label for="sortby" style="float:left;padding: 5px 0;"><?php echo __('Sort By'); ?>:</label>
														<select id="sortby" name="sortby" class="sortby" style="width: 160px;" data-ajax="false" data-role="none">
															<option value="asc" selected="selected">A-Z</option>
															<option value="desc">Z-A</option>
															<option value="date_created">Created (Most Recent)</option>
															<option value="date_modified">Modified (Most Recent)</option>
														</select>
													</div>
												</div>
											</div>
											<div class="col-xs-6 btns-block fetchbtn">
												<div id="xr_filter_clear" class="btn btn-default pull-right" onclick="resetAllFilters();" title="<?php echo __('Clear Filters'); ?>" style="margin-left: 15px;"><?php echo __('Clear'); ?></div>
												<div id="xr_filter_close" class="btn btn-default pull-right" onclick="closeFiltersContainer();" title="<?php echo __('Close Filter'); ?>"><?php echo __('Close'); ?></div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<hr>
							<div class="row gallery-contnr scrollablefilter scrollablepadd" style="display:none;">
								<div class="bodycontent exerciselib row">
									<div class="border">
										<div class="filter_heading exer1"><?php echo __('Target Muscle'); ?></div>
										<div class="exer2">
											<select id="musprim" name="musprim" class="selectpicker" data-live-search="true" data-ajax="false" data-role="none">
												<option value="">Select an option</option>
												<option value="1">Abs</option>
												<option value="2">Abductors</option>
												<option value="3">Adductors</option>
												<option value="4">Biceps</option>
												<option value="5">Calves</option>
												<option value="6">Chest</option>
												<option value="7">Forearms</option>
												<option value="8">Glutes</option>
												<option value="9">Hams</option>
												<option value="10">Lats</option>
												<option value="11">Low Back</option>
												<option value="12">Mid Back</option>
												<option value="13">Neck</option>
												<option value="14">Quads</option>
												<option value="15">Shoulders</option>
												<option value="16">Traps</option>
												<option value="17">Triceps</option>
											</select>
										</div>
										<div class="exer3"><a href="javascript:void(0);" class="filter_sub_btn select_none activedatacol" data-ajax="false" data-role="none"><i><?php echo __('Clear'); ?></i></a></div>
									</div>
									<div class="exerciselibrary">
										<div class="exercisemarkleft">
											<div class="exercisemarkleft">
												<ul class="select_list_muscle" id="listmuscle">
													<li class="list_muscle muscle_id-13">&plus;</li> <!--Neck-->
													<li class="list_muscle muscle_id-15">&plus;</li> <!--Shoulders-->
													<li class="list_muscle muscle_id-6">&plus;</li>	<!--Chest-->
													<li class="list_muscle muscle_id-4">&plus;</li>	<!--Biceps-->
													<li class="list_muscle muscle_id-7">&plus;</li>	<!--Forearm-->
													<li class="list_muscle muscle_id-1">&plus;</li>	<!--Abs-->
													<li class="list_muscle muscle_id-3">&plus;</li>	<!--Adductors-->
													<li class="list_muscle muscle_id-14">&plus;</li> <!--Quads-->
													<!--<li class="list_muscle muscle_id-18">&plus;</li>--> <!--Feet-->
												</ul>
											</div>
										</div>
										<div id="exercisemarkimage" class="exercisemarkcenter"><img src="<?php echo URL::base().'assets/img/anatomy/anatomy.jpg'; ?>" /></div>
										<div class="exercisemarkright">
											<div class="left">
												<ul class="select_list_muscle" id="listmuscle2">
													<li class="list_muscle muscle_id-16">&plus;</li> <!--Traps-->
													<li class="list_muscle muscle_id-10">&plus;</li> <!--Lats-->
													<li class="list_muscle muscle_id-17">&plus;</li> <!--Triceps-->
													<li class="list_muscle muscle_id-12">&plus;</li> <!--Mid Back-->
													<li class="list_muscle muscle_id-11">&plus;</li> <!--Low Back-->
													<li class="list_muscle muscle_id-8">&plus;</li>	<!--Glutes-->
													<li class="list_muscle muscle_id-2">&plus;</li>	<!--Abductors-->
													<li class="list_muscle muscle_id-9">&plus;</li>	<!--Hams-->
													<li class="list_muscle muscle_id-5">&plus;</li>	<!--Calves-->
												</ul>
											</div>
										</div>
									</div>
								</div>
								<hr>
								<div class="bodycontent exercisetypes">
									<div class="filterTitle title=types col-sm-12">
										<div class="filter_heading col-sm-12"><?php echo __('Exercise Types'); ?></div>
										<div class="filter_column col-sm-12">
											<div class="filter_subhead col-sm-12">
												<a href="javascript:void(0);" class="filter_sub_btn select_all" data-ajax="false" data-role="none"><?php echo __('Select All'); ?></a>
												<span class="filter_sub_divider">|</span>
												<a href="javascript:void(0);" class="filter_sub_btn select_none" data-ajax="false" data-role="none"><?php echo __('Select None'); ?></a>
											</div>
											<?php $exerciseType = Model::instance('Model/workouts')->getcheckboxes('type','unit_','_title','_id','unit_gendata','type','0');
											if(count($exerciseType)>0){
												foreach($exerciseType as $keys => $values){ ?>
													<div class="col-sm-12">
														<input type="checkbox" name="exercisetypes[]" id="type-<?php echo $values['type_id'];?>" class="type_chkbx" value="<?php echo $values['type_id'];?>" tabindex="<?php echo $keys;?>" data-ajax="false" data-role="none">
														<label for="type-<?php echo $values['type_id'];?>"><?php echo ucwords($values['type_title']);?></label>
														<div style="clear:left;"></div>
													</div>
											<?php }
											} ?>
										</div>
									</div> <!-- [END] filter_levelType -->
									<div style="clear:both;"></div>
								</div>
								<hr>
								<div class="bodycontent exerciseequips">
									<div class="filterTitle title=equips col-sm-12">
										<div class="filter_heading col-sm-12"><?php echo __('Equipment Type'); ?></div>
										<div class="filter_column col-sm-12">
											<div class="filter_subhead col-sm-12">
												<a href="javascript:void(0);" class="filter_sub_btn select_all" data-ajax="false" data-role="none"><?php echo __('Select All'); ?></a>
												<span class="filter_sub_divider">|</span>
												<a href="javascript:void(0);" class="filter_sub_btn select_none" data-ajax="false" data-role="none"><?php echo __('Select None'); ?></a>
											</div>
											<?php $exerciseEquip = Model::instance('Model/workouts')->getcheckboxes('equip','unit_','_title','_id','unit_gendata','equip','0');
											if(count($exerciseEquip)>0){
												foreach($exerciseEquip as $keys => $values){ ?>
													<div class="col-sm-12">
														<input type="checkbox" name="exerciseequips[]" id="equip-<?php echo $values['equip_id'];?>" class="type_chkbx" value="<?php echo $values['equip_id'];?>" tabindex="<?php echo $keys;?>" data-ajax="false" data-role="none">
														<label for="equip-<?php echo $values['equip_id'];?>"><?php echo ucwords($values['equip_title']);?></label>
														<div style="clear:left;"></div>
													</div>
											<?php }
											} ?>
										</div>
									</div> <!-- [END] filter_equipType -->
									<div style="clear:both;"></div>
								</div>
								<hr>
								<div class="bodycontent exerciselevels">
									<div class="filterTitle title=levels col-sm-12">
										<div class="filter_heading col-sm-12"><?php echo __('Training Level Type'); ?></div>
										<div class="filter_column col-sm-12">
											<div class="filter_subhead col-sm-12">
												<a href="javascript:void(0);" class="filter_sub_btn select_all" data-ajax="false" data-role="none"><?php echo __('Select All'); ?></a>
												<span class="filter_sub_divider">|</span>
												<a href="javascript:void(0);" class="filter_sub_btn select_none" data-ajax="false" data-role="none"><?php echo __('Select None'); ?></a>
											</div>
											<?php  $exerciseLevel = Model::instance('Model/workouts')->getcheckboxes('level','unit_','_title','_id','unit_gendata','level','0');
											if(count($exerciseLevel)>0){
												foreach($exerciseLevel as $keys => $values){ ?>
													<div class="col-sm-12">
														<input type="checkbox" name="exerciselevels[]" id="level-<?php echo $values['level_id'];?>" class="type_chkbx" value="<?php echo $values['level_id'];?>" tabindex="<?php echo $keys;?>" data-ajax="false" data-role="none">
														<label for="level-<?php echo $values['level_id'];?>"><?php echo ucwords($values['level_title']);?></label>
														<div style="clear:left;"></div>
													</div>
												<?php }
											} ?>
										</div>
									</div> <!-- [END] filter_levelType -->
									<div style="clear:both;"></div>
								</div>
								<hr>
								<div class="bodycontent exercisesports">
									<div class="filterTitle title=sports col-sm-12">
										<div class="filter_heading col-sm-12"><?php echo __('Sport Type'); ?></div>
										<div class="filter_column col-sm-12">
											<div class="filter_subhead col-sm-12">
												<a href="javascript:void(0);" class="filter_sub_btn select_all" data-ajax="false" data-role="none"><?php echo __('Select All'); ?></a>
												<span class="filter_sub_divider">|</span>
												<a href="javascript:void(0);" class="filter_sub_btn select_none" data-ajax="false" data-role="none"><?php echo __('Select None'); ?></a>
											</div>
											<?php $exerciseSport = Model::instance('Model/workouts')->getcheckboxes('sport','unit_','_title','_id','unit_gendata','sport','0');
											if(count($exerciseSport)>0){
												foreach($exerciseSport as $keys => $values){ ?>
													<div class="col-sm-12">
														<input type="checkbox" name="exercisesports[]" class="type_chkbx" id="sport-<?php echo $values['sport_id'];?>" value="<?php echo $values['sport_id'];?>" tabindex="<?php echo $keys;?>" data-ajax="false" data-role="none">
														<label for="sport-<?php echo $values['sport_id'];?>"><?php echo ucwords($values['sport_title']);?></label>
														<div style="clear:left;"></div>
													</div>
											<?php }
											} ?>
										</div>
									</div> <!-- [END] filter_levelType -->
									<div style="clear:both;"></div>
								</div>	
								<hr>		
								<div class="bodycontent exerciseactions">
									<div class="filterTitle title=actions col-sm-12">
										<div class="filter_heading col-sm-12"><?php echo __('Force Movement Type'); ?></div>
										 <div class="filter_column col-sm-12">
											 <div class="filter_subhead col-sm-12">
												<a href="javascript:void(0);" class="filter_sub_btn select_all" data-ajax="false" data-role="none"><?php echo __('Select All'); ?></a>
												<span class="filter_sub_divider">|</span>
												<a href="javascript:void(0);" class="filter_sub_btn select_none" data-ajax="false" data-role="none"><?php echo __('Select None'); ?></a>
											</div>
											<?php $exerciseForce = Model::instance('Model/workouts')->getcheckboxes('force','unit_','_title','_id','unit_gendata','force','0');
											if(count($exerciseForce)>0){
												foreach($exerciseForce as $keys => $values){ ?>
													<div class="col-sm-12">
														<input type="checkbox" name="exerciseactions[]" id="force-<?php echo $values['force_id'];?>" class="type_chkbx" value="<?php echo $values['force_id'];?>" tabindex="<?php echo $keys;?>" data-ajax="false" data-role="none">
														<label for="force-<?php echo $values['force_id'];?>"><?php echo ucwords($values['force_title']);?></label>
														<div style="clear:left;"></div>
													</div>
											<?php }
											} ?>
										</div>
									</div> <!-- [END] filter_levelType -->
									<div style="clear:both;"></div>
								</div>
								<hr>
								<div class="bodycontent exercisetags">
									<div class="filterTitle title=tags col-sm-12">
										<div class="filter_heading col-sm-12"><?php echo __('Tags'); ?></div>
										<div class="filter_column col-sm-12">
											<div class="col-sm-12" data-enhance="false" data-role="none" data-ajax="false">
												<input type="text" class="form-control exercisetags" name="exercisetags" value="" placeholder="Tags" data-role="tagsinput" data-ajax="false"/>
											</div>
										</div>
									</div> <!-- [END] filter_levelType -->
									<div style="clear:both;"></div> 
								</div>
							</div>
							<input type="hidden" name="XrFolderId" id="XrFolderId" value="<?php echo $activefolderid; ?>"/>
							<button type="submit" id="btn_filtersubmit" style="display:none;" data-ajax="false" data-role="none"></button>
						</form>
						<!-- exercise list ul -->
						<ul class="data" style="display: none;"></ul>
						<div class="nothingfound" style="display: none;">
							<div class="nofiles"></div>
							<span><?php echo __('No files here'); ?>.</span>
						</div>
					</div>
					<div class="row gallery-empty-row">
						<div class="col-sm-12 show-searchfilter search-icon filter-close" data-class="filter_this" title="<?php echo __('Show Filters'); ?>"">
							<i class="fa fa-search iconsize2"></i>
						</div>
					</div>
				</div>
			<?php } ?>
		</div>
	</div>
</div>
<!-- exercise rec action modal -->
<div id="xrciselibact-modal" class="modal fade" role="dialog" tabindex="-1"></div>
<!-- xr tag modal -->
<div id="xrtagging-modal" class="modal fade" role="dialog" tabindex="-1">
	<div class="vertical-alignment-helper">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<form action="" method="post" id="exercise_taginsert" data-ajax="false" data-role="none">
					<div class="modal-header">
						<div class="row">
							<div class="col-sm-12 mobpadding">
								<div class="border">
									<div class="col-xs-2">
										<a href="javascript:void(0);" title="<?php echo __('Back'); ?>" onclick="$('#xrtagging-modal').modal('hidecustom');" class="triangle" data-ajax="false" data-role="none">
											<i class="fa fa-chevron-left"></i>
										</a>
									</div>
									<div class="col-xs-8 optionpoptitle"><?php echo __('Exercise Tags'); ?></div>
									<div class="col-xs-2"></div>
								</div>
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="popup-title">
								<div class="col-xs-12"><span class="inactivedatacol break-xr-name recordtitle xrrecordTitle" style="font-size: .9em;"></span></div>
							</div>
						</div>
					</div>
					<div class="modal-body opt-body">
						<div class="opt-row-detail">
							<div class="row">
								<div class="col-xs-12"><label class="control-label"><?php echo __('Tags'); ?>:</label></div>
								<div class="col-xs-12" data-enhance="false" data-role="none" data-ajax="false">
									<input type="text" class="form-control xrtag-input" name="xrtag-input" value="" data-role="tagsinput" data-ajax="false"/>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<input type="hidden" name="xrunitid" id="xrunit_id" value=""/>
						<button type="submit" class="btn btn-default pull-left" id="btn-insertxrtag" name="f_method" value="xr-tagging" data-ajax="false" data-role="none"><?php echo __('Insert'); ?></button>
						<button type="button" class="btn btn-default pull-right" onclick="$('#xrtagging-modal').modal('hidecustom');" data-ajax="false" data-role="none"><?php echo __('Close'); ?></button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- exercise rec preview modal -->
<div id="xrciseprev-modal" class="modal fade" role="dialog" tabindex="-1"></div>
<!-- share exercise modal -->
<div id="sharexrcise-modal" class="modal fade" role="dialog" tabindex="-1"></div>
<!-- rate exercise modal -->
<div id="rateexrcise-modal" class="modal fade" role="dialog" tabindex="-1"></div>
<!-- related exercise modal -->
<div id="myOptionsModalExerciseRecord" class="modal fade" role="dialog" tabindex="-1"></div>