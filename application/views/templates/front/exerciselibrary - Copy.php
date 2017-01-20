<div id="exerciselib-template">
	<div id="myModal-exercisepreview" class="modal fade bs-example-modal-sm" role="dialog" tabindex="-1"></div>
	<div id="exerciselib-model" class="modal fade" role="dialog" tabindex="-1">
		<div class="vertical-alignment-helper">
			<div class="modal-dialog inbs-example-modal-lg">
				<div class="modal-content aligncenter">
					<div class="modal-header">
						<div class="row">
							<div class="popup-title">
								<div class="col-xs-2 aligncenter"></div>
								<div class="col-xs-7 aligncenter"><?php echo __('Insert an Exercise'); ?></div>
								<div class="col-xs-3 aligncenter">
									<button class="btn btn-default activedatacol xrliboption" type="button" data-target="#xrliboption-modal" data-ajax="false" data-role="none" data-toggle="modal"><?php echo __('more'); ?></button>
								</div>
							</div>
						</div>
					</div>
					<!-- modal body -->
					<div class="modal-body">
						<!-- gallery -->
						<div class="gallery-div">
							<input type="hidden" id="ref-flag" name="ref-flag" value="">
							<input type="hidden" id="act-flag" name="act-flag" value="">
							<form action="" method="post" id="filterDataForm" data-ajax="false" data-role="none">
								<div class="row search-header-row">
									<div class="popup-searcher col-xs-12">
										<div class="row">
											<div class="col-xs-9 aligncenter xr-searcher">
												<div id="xr_filter_search">
													<input type="text" name="autosearch" class="searchtext form-control input-sm" placeholder="<?php echo __('Search by exercise name'); ?>..." data-ajax="false" data-role="none"/>
													<span class="searchclear fa fa-remove" style="display: none;"></span>
													<button class="btn btn-default show-searchfilter filter-close" type="button" data-class="filter_this" title="<?php echo __('Show/Hide Filters'); ?>" data-ajax="false" data-role="none"><i class="fa fa-caret-down iconsize filter-i"></i></button>
												</div>
											</div>
											<div class="col-xs-3 aligncenter filter-div btns-block">
												<div class="fetchbtn">
													<div id="xr_filter_toggle" class="btn btn-default btncol" data-class="fetch_this" title="<?php echo __('Fetch Records'); ?>"><?php echo __('Fetch'); ?></div>
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
														<div style="float:left">
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
								<div class="row gallery-contnr common-class scrollablefilter scrollablepadd alignleft" style="display:none">
									<div class="bodycontent exerciselib row">
										<div class="border">
											<div class="filter_heading exer1"><?php echo __('Target Muscle'); ?></div>
											<div class="exer2">
												<select id="musprim" name="musprim" class="selectpicker" data-live-search="true" data-ajax="false" data-role="none">
													<option value="" selected="selected">Select an option</option>
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
													<li class="list_muscle muscle_id-6">&plus;</li> <!--Chest-->
													<li class="list_muscle muscle_id-4">&plus;</li> <!--Biceps-->
													<li class="list_muscle muscle_id-7">&plus;</li> <!--Forearm-->
													<li class="list_muscle muscle_id-1">&plus;</li> <!--Abs-->
													<li class="list_muscle muscle_id-3">&plus;</li> <!--Adductors-->
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
													<li class="list_muscle muscle_id-8">&plus;</li> <!--Glutes-->
													<li class="list_muscle muscle_id-2">&plus;</li> <!--Abductors-->
													<li class="list_muscle muscle_id-9">&plus;</li> <!--Hams-->
													<li class="list_muscle muscle_id-5">&plus;</li> <!--Calves-->
												</ul>
											</div>
										</div>
									</div>
									<!-- <div class="exrcisdonebtn"><button class="btn btn-default exdonebtn btncol" type="submit" data-ajax="false" data-role="none">Done</button></div> -->
									</div>
									<hr>
									<div class="bodycontent exercisetypes">
										<div class="filterTitle title=types col-sm-12">
											<div class="filter_heading labelcol col-sm-12"><?php echo __('Exercise Types'); ?></div>
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
															<input type="checkbox" id="type-<?php echo $values['type_id'];?>" name="exercisetypes[]" class="type_chkbx" value="<?php echo $values['type_id'];?>" tabindex="<?php echo $keys;?>" data-ajax="false" data-role="none">
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
											<div class="filter_heading labelcol col-sm-12"><?php echo __('Equipment Type'); ?></div>
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
											<div class="filter_heading labelcol col-sm-12"><?php echo __('Training Level Type'); ?></div>
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
											<div class="filter_heading labelcol col-sm-12"><?php echo __('Sport Type'); ?></div>
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
														<input type="checkbox" name="exercisesports[]" id="sport-<?php echo $values['sport_id'];?>" class="type_chkbx" value="<?php echo $values['sport_id'];?>" tabindex="<?php echo $keys;?>" data-ajax="false" data-role="none">
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
											<div class="filter_heading labelcol col-sm-12"><?php echo __('Force Movement Type'); ?></div>
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
											<div class="filter_column col-sm-12" data-enhance="false" data-role="none" data-ajax="false">
												<div class="col-sm-12"><input type="text" class="form-control exercisetags" name="exercisetags" value="" placeholder="Tags" data-role="tagsinput" data-ajax="false"/></div>
											</div>
										</div> <!-- [END] filter_levelType -->
										<div style="clear:both;"></div>
									</div>
								</div>
								<button type="submit" id="btn_xrfiltersubmit" style="display:none;" data-ajax="false" data-role="none"></button>
							</form>
							<ul class="data" style=""></ul>
							<div class="nothingfound" style="display: none;">
								<div class="nofiles"></div>
								<span><?php echo __('No files here'); ?>.</span>
							</div>
						</div>
						<div class="row gallery-empty-row">
							<div class="col-sm-12 show-searchfilter filter-close" data-class="filter_this" title="<?php echo __('Show Filters'); ?>"">
								<i class="fa fa-search iconsize2"></i>
							</div>
						</div>
					</div><!-- modal body -->
					<div class="modal-footer">
						<button type="button" class="btn btn-default" onclick="$('#exerciselib-model').modal('hidecustom');" data-ajax="false" data-role="none" style="margin-right: 20px;"><?php echo __('Close'); ?></button>
						<button type="button" class="btn btn-default activedatacol xrliboption" data-toggle="modal" data-target="#xrliboption-modal" data-ajax="false" data-role="none" style="margin-right: 10px;"><?php echo __('more'); ?></button>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="libraryactionmodel" class="modal fade" role="dialog" tabindex="-1">
		<div class="vertical-alignment-helper">
			<div class="modal-dialog modal-md">
				<div class="modal-content aligncenter">
					<div class="modal-header" style="border-bottom:0">
						<div class="row">
							<div class="mobpadding">
								<div class="border">
									<div class="col-xs-2">
										<a href="javascript:void(0);" onclick="$('#libraryactionmodel').modal('hide');" class="triangle" data-ajax="false" data-role="none">
											<i class="fa fa-chevron-left"></i>
										</a>
									</div>
									<div class="col-xs-8 optionpoptitle"><?php echo __('Options for this Excercise Record'); ?></div>
									<div class="col-xs-2"></div>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-body opt-body">
						<div class="opt-row-detail">
							<a href="javascript:void(0);" style="width:100%"  class="btn btn-default"  onclick="return insertRecordToParent();" data-ajax="false" data-role="none">
								<div class="col-xs-12 pointer">
									<div class="col-xs-3"><i class="fa fa-sign-in iconsize"></i></div>
									<div class="col-xs-9"><?php echo __('Insert this Record'); ?></div>
								</div>
							</a>
						</div>
					</div>
					<input type="hidden" value="" name="popup_hidden_exerciseset" id="popup_hidden_exerciseset"/>
					<input type="hidden" value="" name="popup_hidden_exerciseset_image" id="popup_hidden_exerciseset_image"/>
					<input type="hidden" value="" name="popup_hidden_exerciseset_title" id="popup_hidden_exerciseset_title"/>
				</div>
			</div>
		</div>
	</div>
	<!-- xrlib more option -->
	<div id="xrliboption-modal" class="modal fade bs-example-modal-sm" role="dialog" tabindex="-1">
		<div class="vertical-alignment-helper">
			<div class="modal-dialog modal-md">
				<div class="modal-content">
					<form action="" id="filteract-form" data-ajax="false" data-role="none">
						<div class="modal-header">
							<div class="mobpadding">
								<div class="border">
									<div class="col-xs-2">
										<a onclick="$('#xrliboption-modal').modal('hide');" class="triangle" data-ajax="false" data-role="none">
											<i class="fa fa-chevron-left iconsize2 pointers"></i>
										</a>
									</div>
									<div class="col-xs-8 optionpoptitle"><?php echo __('Options for this Exercise Library'); ?></div>
									<div class="col-xs-2"></div>
								</div>
							</div>
						</div>
						<div class="modal-body opt-body">
							<div class="opt-row-detail">
								<button data-role="none" data-ajax="false" class="btn btn-default" type="button" onclick="xrLibCreateExercise();" style="width:100%">
									<div class="col-xs-12 pointer">
										<div class="col-xs-3"><i class="fa fa-plus iconsize2"></i></div>
										<div class="col-xs-9 text-center"><?php echo __('Create an Exercise Record'); ?></div>
									</div>
								</button>
							</div>
							<div class="opt-row-detail" onclick="closeAllPopupWindowEdit();">
								<button data-role="none" data-ajax="false" class="btn btn-default" type="button" onclick="" style="width:100%">
									<div class="col-xs-12 pointer">
										<div class="col-xs-3"><i class="fa fa-pencil iconsize2"></i></div>
										<div class="col-xs-9 wrapword"><?php echo __('Write a Title'); ?></div>
									</div>
								</button>
							</div>
							<div class="opt-row-detail" onclick="$('#xrliboption-modal').modal('hide');">
								<a data-role="none" data-ajax="false" href="javascript:void(0);" class="btn btn-default" type="button" onclick="" style="width:100%">
									<div class="col-xs-12 pointer">
										<div class="col-xs-3"><i class="fa fa-search iconsize2"></i></div>
										<div class="col-xs-9 wrapword"><?php echo __('Continue Search'); ?></div>
									</div>
								</a>
							</div>
							<div data-dismiss="modal" class="opt-row-detail" onclick="$('#exerciselib-model').modal('hidecustom');">
								<a data-role="none" data-ajax="false" href="javascript:void(0);" class="btn btn-default" type="button" onclick="" style="width:100%">
									<div class="col-xs-12 pointer">
										<div class="col-xs-3"><i class="fa fa-times iconsize"></i></div>
										<div class="col-xs-9 wrapword"><?php echo __('Cancel Editing Exercise Set Title'); ?></div>
									</div>
								</a>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" onclick="$('#xrliboption-modal').modal('hide');" data-ajax="false" data-role="none"><?php echo __('Close'); ?></button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<!-- image tag modal -->
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
											<a href="#" title="<?php echo __('Back'); ?>" onclick="$('#xrtagging-modal').modal('hidecustom');" class="triangle" data-ajax="false" data-role="none">
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
							<input type="hidden" name="xrunitid" id="xrunit_id" value="" />
							<button type="button" class="btn btn-default pull-left" id="btn-insertxrtag" onclick="insertTagforExercise();" data-ajax="false" data-role="none"><?php echo __('Insert'); ?></button>
							<button type="button" class="btn btn-default pull-right" onclick="$('#xrtagging-modal').modal('hide');" data-ajax="false" data-role="none"><?php echo __('Close'); ?></button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<!-- share exercise modal -->
	<div id="sharexrcise-modal" class="modal fade" role="dialog" tabindex="-1"></div>
	<script type="text/javascript">
	var filtermanager = $('.gallery-div'),
		fileList = filtermanager.find('ul.data');
	fileList.empty().hide();
	var recordcount=0;
	var fltr_limitcnt=0;

	function emptyFilterData(){
		fileList.empty();
		if( $('#xr_filter_reset').is(':visible') ) {
			$('#xr_filter_reset').hide();
		}else{}
	}
	$('#exerciselib-model').on('shown.bs.modal', function(){
		setTimeout(function(){
			$('form#filterDataForm input.searchtext').focus();
		}, 400);
	});
	$('#exerciselib-model').on('hidden.bs.modal', function(){
		var refflag = $('#ref-flag').val();
		var actflag = $('#act-flag').val();
		if(refflag!=''){
			$('#exerciselib-template').remove();
			if(actflag!=''){
				if($('.modal form#createExercise #title').is(':visible')){
					$('.modal form#createExercise #title').trigger('click');
				}
				setTimeout(function(){
					if(actflag=='exercise'){
						createExercise();
					}else if(actflag=='workouts'){
						xrLibCreateExercise();
					}
				}, 800);
			}else{
			}
		}else{
			if(!$('#myOptionsModalAjax').hasClass('in')){
				closeAllPopupWindow();
			}
		}
	});
	$('#xrliboption-modal').on('hidden.bs.modal', function(){
		setTimeout(function(){
			$('form#filterDataForm input.searchtext').focus();
		}, 400);
	});
	/*for tag*/
	var tagarry=[];
	$.ajax({
		url: siteUrl + 'ajax/tagnames',
		dataType : 'json',
		async: false,
		encode: true,
		cache: false
	}).done(function (data) {
		var taglist=[];
		if(data){
			$.each(data.tagnames,function(i, val){
				taglist.push({id:i, val:val}); 
			});
			tagarry = taglist;
		}
	});
	var tagnames = new Bloodhound({
		datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
		queryTokenizer: Bloodhound.tokenizers.whitespace,
		local: $.map(tagarry, function (tagname) {
			return {
				id : tagname.id,
				name: tagname.val
			};
		})
	});
	tagnames.initialize();
	$('input.xrtag-input').tagsinput({
		typeaheadjs: [{
			highlight: true,
		}, {
			name: 'tagnames',
			displayKey: 'name',
			valueKey: 'name',
			source: tagnames.ttAdapter()
		}],
		freeInput: true
	});
	$('input.exercisetags').tagsinput({
		itemValue: 'id',
		itemText: 'name',
		typeaheadjs: [{
			highlight: true,
		},{
			name: 'tagnames',
			displayKey: 'name',
			source: tagnames.ttAdapter()
		}],
		freeInput: true
	});
	$('input.xrtag-input').tagsinput('input').blur(function() {
		$('input.xrtag-input').tagsinput('add', $(this).val());
		$(this).val('');
	});

	$(function(){
		$('body').on('click', 'li.list_muscle', function(e) {
			e.preventDefault();
			if (e.handled !== true) {
				e.handled = true;
				var target_vals = targetHiLiteToggle(e.target);
				var target_id = target_vals[0];
				var target_html = target_vals[1];
				targetMuscle(target_html, target_id);
			}
		});
		$('body').on('change', '#musprim', function(e) {
			e.preventDefault();
			if (e.handled !== true) {
				e.handled = true;
				changeTargetOption(e.target);
				var target_id = $('#musprim').val();
				var target_html = $('#musprim :selected').html();
				var target = $('li.list_muscle.muscle_id-'+target_id);
				targetMuscle(target_html,target_id);
				targetHiLiteToggle(target);
			}
		});
		$('body').on('click', '#xr_filter_toggle', function(e) {
			e.preventDefault();
			if (e.handled !== true) {
				e.handled = true;
				var target_status = $(this).attr('data-class');
				console.log(target_status);
				switch(target_status){
					case 'fetch_this':
						filtermanager.removeClass('active');
						$('#xr_filter_toggle, .gallery-empty-row, .gallery-contnr, .sorting-div').hide();
						$('#xr_filter_reset').show();
						$('.show-searchfilter').removeClass('filter-open').addClass('filter-close');
						$('.show-searchfilter .filter-i').removeClass('fa-caret-up').addClass('fa-caret-down');
						if($('form#filterDataForm input.searchtext').val() == ''){
							$('select[name=sortby]').val('date_created');
						}
						$('#btn_xrfiltersubmit').trigger('click');
						setTimeout(function(){
							$('form#filterDataForm input.searchtext').focus();
						}, 400);
						break;
					default:
						break;
				}
			}
		});
		$('body').on('click', '#xr_filter_reset', function(e) {
			e.preventDefault();
			if (e.handled !== true) {
				e.handled = true;
				var title = 'gallery-contnr';
				if( $('.xr_target_selected').length > 0) {
					$('.xr_target_selected').closest('li').trigger('click');
				}
				$('.'+title+' input[type=checkbox]').prop('checked', false);
				$('input.exercisetags').tagsinput('removeAll');
				$('input.exercisetags').tagsinput('refresh');
				$('select[name=sortby]').val('asc');
				$('.'+title).addClass('active');
				activeFilterBtns(title);
				emptyFilterData();
				$('form#filterDataForm input.searchtext').val('');
				$('.searchclear, .nothingfound').hide();
				fileList.empty().hide();
				$('#xr_filter_toggle, .gallery-empty-row').show();
				setTimeout(function(){
					$('form#filterDataForm input.searchtext').focus();
				}, 400);
			}
		});
		$('body').on('click', '.filter_sub_btn', function(e) {
			e.preventDefault();
			if (e.handled !== true) {
				e.handled = true;
				var target = $(e.target);
				var title = target.closest('.bodycontent').attr("class").split(" ")[1];
				console.log(title);
				if( target.is('.select_all') ){
					$('.'+title+' input[type=checkbox]').prop('checked', true);
					activeFilterBtns(title);
				}else{
					if( title == 'exerciselib' ){
						if( $('.xr_target_selected').length > 0) {
							$('.xr_target_selected').closest('li').trigger('click');
						}
					}else{
						$('.'+title+' input[type=checkbox]').prop('checked', false);
						activeFilterBtns(title);
					}
				}
			}
		});
	});
	$(document).on('click', '.show-searchfilter', function(ev){
		ev.preventDefault();
		if (ev.handled !== true) { 
			ev.handled = true;
			$('.gallery-contnr, .sorting-div').show();
			$('.show-searchfilter').removeClass('filter-close').addClass('filter-open');
			$('.show-searchfilter .filter-i').removeClass('fa-caret-down').addClass('fa-caret-up');
			$('.nothingfound').hide();
			if($(this).attr('data-class') == 'filter_this'){
				$('#xr_filter_reset').hide();
				$('#xr_filter_toggle').show();
				fileList.hide();
			}
			if(filtermanager.hasClass('active')){
				filtermanager.removeClass('active');
				$('.gallery-contnr, .sorting-div').hide();
				$('.show-searchfilter').removeClass('filter-open').addClass('filter-close');
				$('.show-searchfilter .filter-i').removeClass('fa-caret-up').addClass('fa-caret-down');
				if($('ul.data li').length > 0){
					$('#xr_filter_toggle, .gallery-empty-row').hide();
					$('#xr_filter_reset').show();
					fileList.show();
				}else{
					$('.gallery-empty-row').show();
				}
			}else{
				filtermanager.addClass('active').show();
				$('.gallery-empty-row').hide();
			}
			setTimeout(function(){
				$('form#filterDataForm input.searchtext').focus();
			}, 400);
		}
	});
	function resetAllFilters(){
		if( $('.xr_target_selected').length > 0) {
			$('.xr_target_selected').closest('li').trigger('click');
		}
		$('.gallery-contnr input[type=checkbox]').prop('checked', false);
		$('input.exercisetags').tagsinput('removeAll');
		$('input.exercisetags').tagsinput('refresh');
		$('select[name=sortby]').val('asc');
		setTimeout(function(){
			$('form#filterDataForm input.searchtext').val('').focus();
		}, 400);
		$('.searchclear, .nothingfound').hide();
	}
	function closeFiltersContainer(){
		filtermanager.removeClass('active');
		$('.gallery-contnr, .sorting-div').hide();
		$('.show-searchfilter').removeClass('filter-open').addClass('filter-close');
		$('.show-searchfilter .filter-i').removeClass('fa-caret-up').addClass('fa-caret-down');
		if($('ul.data li').length > 0){
			$('#xr_filter_toggle, .gallery-empty-row').hide();
			$('#xr_filter_reset').show();
			fileList.show();
		}else{
			$('#xr_filter_toggle, .gallery-empty-row').show();
			$('#xr_filter_reset').hide();
			fileList.hide();
		}
		setTimeout(function(){
			$('form#filterDataForm input.searchtext').focus();
		}, 400);
	}
	function activeFilterBtns(title){
		if( title != 'bodycontent'){
			var visible_btn = $('.visible');
			var numChkd = $('.'+title+' input:checked').length;
			if ( numChkd > 0 ) {
				visible_btn.addClass('activeFilter');
			}else{
				visible_btn.removeClass('activeFilter');
			} 
		}else{
			$('.activeFilter').removeClass('activeFilter');
		}
	}
	function targetHiLiteToggle(x) {
		var target = $(x);
		var visible_btn = $('.visible');
		if( target.is('.xr_target_selected') ){
			targetHiLiteOff(target,visible_btn);
			var target_html = '';
			var target_id = 0;
		}else{
			var target_vals = targetHiLiteOn(target,visible_btn);
			var target_id = target_vals[0];
			var target_html = target_vals[1];
		}
		return [target_id,target_html];
	}
	function targetHiLiteOff(target,visible_btn) {
		$('li.list_muscle').removeClass('xr_target_selected');
		visible_btn.removeClass('activeFilter');
		$('input[name="target[]"]').val('');
	}
	function targetHiLiteOn(target,visible_btn) {
		$('li.list_muscle').removeClass('xr_target_selected');
		var target_id   = target.attr('class').split('-').pop();
		var target_html = target.html();
		visible_btn.addClass('activeFilter');
		target.addClass('xr_target_selected');
		$('input[name="target[]"]').val(target_id);
		return  [target_id,target_html];  
	}
	function targetMuscle(target_html,target_id){
		t_id = parseInt(target_id); 
		switch (t_id) {
			case 1:   //Abs
				$("#exercisemarkimage img").attr("src","<?php echo URL::base().'assets/img/anatomy/anatomy_06.jpg'; ?>");
				break;
			case 2:   //abductors
				$("#exercisemarkimage img").attr("src","<?php echo URL::base().'assets/img/anatomy/anatomy_16.jpg'; ?>");
				break;
			case 3:   //adductors
				$("#exercisemarkimage img").attr("src","<?php echo URL::base().'assets/img/anatomy/anatomy_07.jpg'; ?>");
				break;
			case 4:   //biceps
				$("#exercisemarkimage img").attr("src","<?php echo URL::base().'assets/img/anatomy/anatomy_04.jpg'; ?>");
				break;
			case 5:   //calves
				$("#exercisemarkimage img").attr("src","<?php echo URL::base().'assets/img/anatomy/anatomy_18.jpg'; ?>");
				break;
			case 6:   //chest
				$("#exercisemarkimage img").attr("src","<?php echo URL::base().'assets/img/anatomy/anatomy_03.jpg'; ?>");
				break;
			case 7:   //Forearm
				$("#exercisemarkimage img").attr("src","<?php echo URL::base().'assets/img/anatomy/anatomy_05.jpg'; ?>");
				break;
			case 8:   //glutes
				$("#exercisemarkimage img").attr("src","<?php echo URL::base().'assets/img/anatomy/anatomy_15.jpg'; ?>");
				break;
			case 9:   //hams
				$("#exercisemarkimage img").attr("src","<?php echo URL::base().'assets/img/anatomy/anatomy_17.jpg'; ?>");
				break;
			case 10:  //lats
				$("#exercisemarkimage img").attr("src","<?php echo URL::base().'assets/img/anatomy/anatomy_11.jpg'; ?>");
				break;
			case 11:  //low back
				$("#exercisemarkimage img").attr("src","<?php echo URL::base().'assets/img/anatomy/anatomy_14.jpg'; ?>");
				break;
			case 12:  //mid back
				$("#exercisemarkimage img").attr("src","<?php echo URL::base().'assets/img/anatomy/anatomy_13.jpg'; ?>");
				break;
			case 13:  //neck
				$("#exercisemarkimage img").attr("src","<?php echo URL::base().'assets/img/anatomy/anatomy_01.jpg'; ?>");
				break;
			case 14:  //quads
				$("#exercisemarkimage img").attr("src","<?php echo URL::base().'assets/img/anatomy/anatomy_08.jpg'; ?>");
				break;
			case 15:  //shoulders
				$("#exercisemarkimage img").attr("src","<?php echo URL::base().'assets/img/anatomy/anatomy_02.jpg'; ?>");
				break;
			case 16:  //traps
				$("#exercisemarkimage img").attr("src","<?php echo URL::base().'assets/img/anatomy/anatomy_10.jpg'; ?>");
				break;
			case 17:  //triceps
				$("#exercisemarkimage img").attr("src","<?php echo URL::base().'assets/img/anatomy/anatomy_12.jpg'; ?>");
				break;
			case 18:  //feet
				$("#exercisemarkimage img").attr("src","<?php echo URL::base().'assets/img/anatomy/anatomy_09.jpg'; ?>");
				break;
			default:
				$("#exercisemarkimage img").attr("src","<?php echo URL::base().'assets/img/anatomy/anatomy.jpg'; ?>");
		}
		var target = $('#musprim option[value="' + target_id + '"]');
		changeTargetOption(target);
	}
	function changeTargetOption(x) {
		var target = $(x);
		thisOne = target.val();
		$('#musprim>option').prop('selected',false);
		$('#musprim option[value="' + thisOne + '"]').prop("selected", true);
	}
	function resetWhileOpen(){
		$('.searchclear').trigger('click');
		setTimeout(function(){
			$('form#filterDataForm input.searchtext').focus();
		}, 400);
	}
	/* Search Input (from FILTERS page) */
	filtermanager.find('form#filterDataForm input.searchtext').on('form#filterDataForm input.searchtext', function(e){
		e.preventDefault();
		var value = $(this).val().trim();
		var t = $(this);
		t.next('span').toggle(Boolean(t.val()));
		if(value.length) {
			filtermanager.addClass('searching'); /* Show Searching "Progress" */
			e.preventDefault(); /* Trigger Submitting the Filter Form */
			$('#btn_xrfiltersubmit').trigger('click');
		} else {
			filtermanager.removeClass('searching');
			emptyFilterData();
			e.preventDefault();
			$('#btn_xrfiltersubmit').trigger('click');
		}
		filtermanager.removeClass('active');
		$('#xr_filter_toggle, .gallery-contnr, .sorting-div').hide();
		$('#xr_filter_reset').show();
		$('.show-searchfilter').removeClass('filter-open').addClass('filter-close');
		$('.show-searchfilter .filter-i').removeClass('fa-caret-up').addClass('fa-caret-down');
	}).on('keyup', function(e){
		e.preventDefault();
		$('select[name=sortby]').val('asc');
		var searchinput = $(this);
		if(e.charCode == 0 && e.keyCode == 0) {
			return false;
		}else if ( e.keyCode == 27 /* escape button */ ) {
			if(searchinput.length>0){
				e.preventDefault()
				$('#btn_xrfiltersubmit').trigger('click');
			}else{}
		}
	});
	/* Search Input (from FILTERS page) */
	filtermanager.find('select#sortby').on('change', function(e) {
		e.preventDefault();
		if (e.handled !== true) {
			e.handled = true;
			filtermanager.removeClass('active');
			$('#xr_filter_toggle, .gallery-contnr, .sorting-div').hide();
			$('#xr_filter_reset').show();
			$('.show-searchfilter').removeClass('filter-open').addClass('filter-close');
			$('.show-searchfilter .filter-i').removeClass('fa-caret-up').addClass('fa-caret-down');
			$('#btn_xrfiltersubmit').trigger('click');
			setTimeout(function(){
				$('form#filterDataForm input.searchtext').focus();
			}, 400);
		}
	});
	/*submit for fetch the exercise records*/
	$('#filterDataForm').submit(function(e) {
		e.preventDefault();
		e.stopImmediatePropagation();
		if(e.handled !== true) {
			e.handled = true;
			fltr_limitcnt=0;
			if($('#ref-flag').val()!=''){
				processFilterData($('#ref-flag').val()); // process form filter data for xrlib mode
			}else{
				processFilterData('init'); // process form filter data for initial mode
			}
		}
	});
	$(document).on('click', '#fltrshowmore-rec', function(e){
		e.preventDefault();
		if(e.handled !== true) {
			e.handled = true;
			$('form#filterDataForm input.searchtext').blur();
			document.getElementById('fltrshowmore-rec').style.pointerEvents = 'none';
			fltr_limitcnt = fltr_limitcnt+10;
			processFilterData('showmore'); // process form filter data for show more
		}
	});
	function processFilterData(opt){
		// Filter : Search Input Text
		var searchText = $('form#filterDataForm input.searchtext').val();
		// Filter : Target Muscle
		var target_muscle = $('form select[name="musprim"]').val();
		// Filter : Exercise Type(s)
		var exercise_type = new Array();
		$('form input[name="exercisetypes[]"]').each(function () {
			if(this.checked){ exercise_type.push($(this).val()); }else{}
		});
		// Filter : Equipment Item(s)
		var equipment = new Array();
		$('form input[name="exerciseequips[]"]').each(function () {
			if(this.checked){ equipment.push($(this).val()); }else{}
		});
		// Filter : Training Level
		var train_level = new Array();
		$('form input[name="exerciselevels[]"]').each(function () {
			if(this.checked){ train_level.push($(this).val()); }else{}
		});
		// Filter : Sport
		var sport_type = new Array();
		$('form input[name="exercisesports[]"]').each(function () {
			if(this.checked){ sport_type.push($(this).val()); }else{}
		});
		// Filter : Force Movement
		var force = new Array();
		$('form input[name="exerciseactions[]"]').each(function () {
			if(this.checked){ force.push($(this).val()); }else{}
		});
		// Filter : Associated Tags
		var tagsitem = $('form input[name="exercisetags"]').tagsinput('items');
		var tags = new Array();
		$.each(tagsitem, function (i, t) {
			tags.push(t.id);
		});
		if((searchText!='' || searchText=='' ) && (target_muscle!='' || exercise_type!='' || equipment!='' || train_level!='' || sport_type!='' || force!='' || tags!='')){
			$('select[name=sortby]').val('date_created');
		}
		var sortby = $('form select[name="sortby"]').val();
		var recordform = $('input#ref-flag').val();
		/*FETCH RECORDS*/
		var dataToSend = {
			searchval :searchText,
			sortby    :sortby,
			musprim   :target_muscle,
			type      :exercise_type,
			equip     :equipment,
			level     :train_level,
			sport     :sport_type,
			force     :force,
			tags      :tags,
			recform   :recordform,
			slimit    :fltr_limitcnt,
			elimit    :10
		};
		// console.log(dataToSend)
		$.ajax({
			type: 'POST',
			url: siteUrl + 'ajax/exerciseRecordGallery',
			data: dataToSend,
			encode: true,
			cache: false,
			success: function(data) {
				var response = [JSON.parse(data)];
				if((opt=='init' || $('#ref-flag').val()!='') && opt!='showmore'){
					fileList.empty().hide();
				}
				if (response) {
					fileList.find('.fltrshowmore-rec').remove();
					if(render(filterRecords(response), searchText)){
						if(recordcount == 10){
							var showmore = '<li class="fltrshowmore-rec hide">';
								showmore += '<div class="xrRecordDataFrame xrrec col-xs-12 col-sm-12">';
									showmore += '<div class="col-sm-3 col-xs-3"></div>';
									showmore += '<div class="col-sm-6 col-xs-6 aligncenter activedatacol" id="fltrshowmore-rec">';
										showmore += '<i class="fa fa-chevron-down activedatacol"></i> <?php echo __("Show More Records"); ?>...';
									showmore += '</div>';
									showmore += '<div class="col-sm-3 col-xs-3"></div>';
								showmore += '</div>';
							showmore += '</li>';
							var showimg = $(showmore);
							showimg.appendTo(fileList);
							// To re-enable:
							document.getElementById('fltrshowmore-rec').style.pointerEvents = 'auto';
						}
						// Show the generated elements
						fileList.show();
						if(opt!='showmore'){
							fileList.scrollTop(0);
						}
					}
				}
			}
		});
		return true;
	}
	function filterRecords(records) {
		var demo = records; // var response = [data] returned from search
		var flag = 0;
		for(var j=0;j<demo.length;j++){
			flag = 1; // flag if response contains data
			demo = demo[j].items;
			recordcount = demo[0].itemcount;
			break;
		}
		demo = flag ? demo : []; // if no content, demo=0, otherwise demo=[array]
		return demo;
	}
	function render(data, searchtext) {
		/* TEST FLAGS for DATA ARRAYS */
		var filteredFiles = [];
		if(Array.isArray(data)) {
			data.forEach(function (d, i) {
				if(i!=0){
					filteredFiles.push(d);
				}
			});
		}
		$('.gallery-empty-row').hide();
		/* Empty the old result and make the new one */
		if(!filteredFiles.length) {
			if(fileList.find('li.xrRecord').length){
				filtermanager.find('.nothingfound').hide();
			}else{
				filtermanager.find('.nothingfound').show();
			}
			return false;
		} else {
			filtermanager.find('.nothingfound').hide();
			filteredFiles.forEach(function(f) {
				var xr_id = escapeHTML(f.id);
				var name = escapeHTML(f.name);
				if($('#ref-flag').val()!=''){
					var onclick1 = 'onclick="getExercisepreviewOption('+xr_id+', \''+$('#ref-flag').val()+'\', \'xrrecord\');"';
					if($('#act-flag').val()=='exercise'){
						var onclick2 = "insertToExerciseSet('"+xr_id+"', '"+$('#ref-flag').val()+"', '"+f.featimg+"', '"+name.replace(/'/g, "\\'")+"');";
						onclick2 = 'onclick="'+onclick2+'"';
					}else{
						var onclick2 = "xrLibinsertToExerciseSet('"+xr_id+"', '"+$('#ref-flag').val()+"', '"+f.featimg+"', '"+name.replace(/'/g, "\\'")+"');";
						onclick2 = 'onclick="'+onclick2+'"';
					}
				}else{
					var onclick1 = 'onclick="getXrImageRecords('+xr_id+', this);"';
					var onclick2 = 'onclick="insertRecordToParent(this);"';
				}
				var rec = '<li class="xrRecord" id="' + xr_id + '">';
				rec += '<div class="xrRecordDataFrame col-xs-12 col-sm-12">';
				rec += '<a href="javascript:void(0);" class="col-xs-10 col-sm-10 xrFrame-left" '+onclick1+' data-imgpath="'+f.featimg+'" data-imgid="'+xr_id+'" data-imgname="'+name+'" data-ajax="false" data-role="none">';
				if(f.featimg.trim() == ''){
					rec += '<div class="col-xs-3 col-sm-3 thumb_img noimg-icon"><i class="fa fa-file-image-o datacol" style="font-size:50px;"></i></div>';
				}else{
					rec += '<div class="col-xs-3 col-sm-3 thumb_img" style="background-image: url(' + f.featimg + ');"></div>';
				}
				rec += '<div class="col-xs-9 col-sm-9 text-left"><span class="xrRecord-title break-xr-name">'+ highlightText(name, searchtext) +'</span><div class="item-info">'+f.default+'</div></div></a>';
				rec += '<a href="javascript:void(0);" class="col-xs-2 col-sm-2 xrFrame-right" '+onclick2+' data-imgpath="'+f.featimg+'" data-imgid="'+xr_id+'" data-imgname="'+name+'" title="Insert this record" data-ajax="false" data-role="none"><div class="col-xs-12 col-sm-12"><i class="fa fa-sign-in iconsize2"></i></div></a>';
				var tagsarr = [];
				f.tags.forEach(function(t) {
					tagsarr.push(t.tag_title);
				});
				rec += '<input type="hidden" id="XrRecTags' + xr_id + '" value="' + tagsarr.join() + '">';
				rec += '</div>';
				rec += '</li>';
				var file = $(rec);
				file.appendTo(fileList);
			});
		}
		return true;
	}
	// for highlighting the search text in list
	function preg_quote( str ) {
		return (str+'').replace(/([\\\.\+\*\?\[\^\]\$\(\)\{\}\=\!\<\>\|\:])/g, "\\$1");
	}
	function highlightText( data, search ) {
		if(search!=''){
			return data.replace( new RegExp( "(" + preg_quote( search ) + ")" , 'gi' ), "<span class='highlight'>$1</span>" );
		}
		return data;
	}
	/* ESCAPE HTML from String */
	function escapeHTML(text) {  
		return text.replace(/\&/g,'&amp;').replace(/\</g,'&lt;').replace(/\>/g,'&gt;');
	}
	// searchbox clear
	$(".searchclear").click(function () {
		setTimeout(function(){
			$(this).hide().prev('input').val('').focus();
		}, 400);
		fileList.empty().hide();
		$('.nothingfound, .gallery-contnr, .sorting-div, #xr_filter_reset').hide();
		$('#xr_filter_toggle, .gallery-empty-row').show();
		$('.show-searchfilter').removeClass('filter-open').addClass('filter-close');
		$('.show-searchfilter .filter-i').removeClass('fa-caret-up').addClass('fa-caret-down');
	});
	/*opens preview images slides-options modal for xr-rec*/
	function getXrImageRecords(xrid, elem){
		$('#xrunit_id').val(xrid);
		var unittags = $('#XrRecTags' + xrid).val();
		$('input.xrtag-input').tagsinput('removeAll');
		$('input.xrtag-input').tagsinput('refresh');
		$('input.xrtag-input').tagsinput('add', unittags);
		var xrId = $(elem).attr('data-imgid');
		var xrimgpath = $(elem).attr('data-imgpath');
		var xrname = $(elem).attr('data-imgname');
		modalName = 'myOptionsModalExerciseRecord';
		$('#'+modalName).html();
		$.ajax({
			url: siteUrl + "search/getmodelTemplate",
			data: {
				action: 'relatedRecords',
				method: 'previewimage',
				id: xrid,
				modelType: modalName,
				addOptions: 'add'
			},
			success: function(content){
				$('#'+modalName).html(content);
				if($('#myOptionsModalExerciseRecord #btn_insert_xrrecord').length){
					$('#myOptionsModalExerciseRecord #btn_insert_xrrecord').attr({'data-imgid':xrId, 'data-imgpath':xrimgpath, 'data-imgname':xrname});
				}
				$('#'+modalName).modal();
				$('span.xrrecordTitle').html(xrname);
			}
		});
	}
	function getRateForXrModalFromUser(xrId) {
		$('#myOptionsModalExerciseRecord').html();
		$.ajax({
			url: siteUrl + "search/getmodelTemplate/",
			data: {
				action: 'relatedRecords',
				method: 'xrrate',
				id: xrId,
				foldid: 0,
				modelType: "myOptionsModalExerciseRecord",
				addOptions: 'btn-default'
			},
			success: function(content) {
				$('#myOptionsModalExerciseRecord').html(content);
				$('#myOptionsModalExerciseRecord').modal();
			}
		});
	}
	function triggerXrTagModal() {
		$('#myOptionsModalExerciseRecord').modal('hide');
		$('#xrtagging-modal').modal();
	}
	function xrLibajaxInsertActivityfeed (xrid, method, type) {
		if(xrid){
			$.post(siteUrl + 'ajax/ajaxInsertActivityfeed', {'actid': xrid, 'method': method, 'type': type}, function(){});
		}
	}
	/*opens the xr-rec create/edit modal*/
	function xrLibcreateNewExercise(opt) {
		if(opt){
		}else{
			opt =false;
		}
		$('#preview-xr-modal-options').modal('hide');
		$('#myOptionsModalExerciseRecord').modal('hide');
		if($('#preview-xr-modal').is(':visible')){
			$('#preview-xr-modal').modal('hide');
		}
		var addreq, xrrecid = '';
		if(opt===true){
			addreq = $('#addtype').val();
			xrrecid = $('#addid').val();
			setTimeout(function(){
				xrLibajaxInsertActivityfeed (xrrecid, 'opened', 'exercise');
			}, 200);
		}else if(opt=='edit'){
			xrrecid = $('#xrRecInsertForm #xrid').val();
			setTimeout(function(){
				xrLibajaxInsertActivityfeed (xrrecid, 'opened', 'exercise');
			}, 300);
		}else if(opt=='reset'){
			addreq = $('#xrRecInsertForm #xrid').attr('data-addtype');
			xrrecid = $('#xrRecInsertForm #xrid').attr('data-addid');
			if(addreq==''){
				xrrecid = $('#xrRecInsertForm #xrid').val();
				setTimeout(function(){
					xrLibajaxInsertActivityfeed (xrrecid, 'exited', 'exercise');
				}, 300);
			}
		}else{
			xrrecid = '';
		}
		$.ajax({
			url : siteUrl + "ajax/getAjaxExerciseCreateHtml",
			type: 'post',
			data : {
				xrid : xrrecid,
				action : 'createExercise',
				requestFrom: 'workouts',
				actionFrom: 'workouts',
				addrequest: addreq
			},
			success : function(content){
				var ajaxData = JSON.parse(content);
				$('#exercisecreate-modal #xrRec-container').empty();
				if(ajaxData.content!=''){
					$('#exercisecreate-modal #xrRec-container').html(ajaxData.content);
					$('#exercisecreate-modal').modal();
				}
			}
		});
		$('#exercisecreate-modal').modal();
	}
	/*opens create option modal for create a xr-rec from xr-lib*/
	function xrLibCreateExercise(xrid, type){
		$('#xrliboption-modal').modal('hidecustom');
		$('#myOptionsModalExerciseRecord').html();
		$.ajax({
			url : siteUrl + "search/getmodelTemplate/",
			data : {
				action : 'addExercise',
				method : '',
				id : xrid,
				modelType : "myOptionsModalExerciseRecord",
				type: type,
				requestFrom: 'workouts',
				actionFrom: 'workouts'
			},
			success : function(content){
				$('#myOptionsModalExerciseRecord').html(content);
				$('#myOptionsModalExerciseRecord').modal();
			}
		});
	}
	/*opens again xr-lib modal for select and create a xr-rec*/
	function xrLibcreateExerciseFromXrLibrary(type){
		$('#myOptionsModalExerciseRecord').modal('hide');
		$('#ref-flag').val(type);
		$('#act-flag').val('workouts');
		$('#exerciselib-model button.xrliboption').addClass('hide');
		$('#xr_filter_toggle').trigger('click');
		$('#exerciselib-model').modal();
	}
	function insertTagforExercise() {
		$.ajax({
			url : siteUrl + 'ajax/insertTagFromXrciseModal',
			method: 'post',
			data : $('#exercise_taginsert').serialize() + '&action=xr-tagging',
			success : function(content){
				var response = JSON.parse(content);
				var newtagsarr = [];
				response.xrtags.forEach(function(tag) {
					newtagsarr.push(tag.tag_title);
				});
				var unitid = $('#xrunit_id').val();
				$('#XrRecTags' + unitid).val(newtagsarr.join());
				if(response.msg=='success'){
					setTimeout(function() {
						$('#exerciselib-model .gallery-div').prepend('<div class="row bannermsg"><div class="col-xs-12 banner success">'+__('Exercise record, tagged successfully')+' !!!</div></div>');
					}, 200);
				}else if(response.msg=='fail'){
					setTimeout(function() {
						$('#exerciselib-model .gallery-div').prepend('<div class="row bannermsg"><div class="col-xs-12 banner errors">'+__('No tags inserted for this exercise')+' !!!</div></div>');
					}, 200);
				}else if(response.msg=='error'){
					setTimeout(function() {
						$('#exerciselib-model .gallery-div').prepend('<div class="row bannermsg"><div class="col-xs-12 banner errors">'+__('Error occurred while tagging')+' !!!</div></div>');
					}, 200);
				}
				$('#xrtagging-modal').modal('hide');
				setTimeout(function() {
					$('#exerciselib-model .bannermsg').fadeOut(10000);
				}, 250);
			}
		});
	}
	function triggerXrRatingInsert() {
		$.ajax({
			url : siteUrl + 'ajax/insertRatingFromXrciseModal',
			method: 'post',
			data : $('#xrrate').serialize() + '&action=xr-rating',
			success : function(content){
				var response = JSON.parse(content);
				if(response.msg=='success'){
					$('#xrtagging-modal').modal('hide');
					setTimeout(function() {
						$('#exerciselib-model .gallery-div').prepend('<div class="row bannermsg"><div class="col-xs-12 banner success">'+__('Exercise record, rated successfully')+' !!!</div></div>');
					}, 200);
				}else if(response.msg=='fail'){
					setTimeout(function() {
						$('#exerciselib-model .gallery-div').prepend('<div class="row bannermsg"><div class="col-xs-12 banner errors">'+__('Already you rated this exercise')+' !!!</div></div>');
					}, 200);
				}else if(response.msg=='error'){
					setTimeout(function() {
						$('#exerciselib-model .gallery-div').prepend('<div class="row bannermsg"><div class="col-xs-12 banner errors">'+__('Error occurred while rating')+' !!!</div></div>');
					}, 200);
				}
				$('#myOptionsModalExerciseRecord').modal('hide');
				setTimeout(function() {
					$('#exerciselib-model .bannermsg').fadeOut(10000);
				}, 250);
			}
		});
	}
	/*insert related xr-rec to xr-set*/
	function insertFromRelatedToParent(){
		var insertId = $('#popup_hidden_exerciseset_opt').val();
		$('#exercise_unit').val(insertId);
		$('#exercise_title').val($('#popup_hidden_exerciseset_title_opt').val());
		if($('#popup_hidden_exerciseset_image_opt').val() !=''){
			if($('div#img-preview-pop').length)
				$('div#img-preview-pop').html('<img style="padding-right:10px;" width="50px;" src="'+$('#popup_hidden_exerciseset_image_opt').val()+'"  />');			
		}else{
			if($('div#img-preview-pop').length)
				$('div#img-preview-pop').html('<i class="fa fa-file-image-o datacol" style="font-size:50px;"></i>');	
		}
		if($('input.exercise_title').is(':visible')){
			setTimeout(function(){
				$('input.exercise_title').focus();
			}, 400);
		}
		$('#myOptionsModalExerciseRecord_option').modal('hide');
		$('#myOptionsModalExerciseRecord').modal('hide');
		$('#myModal-exercisepreview').modal('hide');
		$('.errormsg').hide();
		closeModelwindow('exerciselib-model');
	}
	/*insert the xr-rec to xr-set*/
	function insertRecordToParent(elem){
		var insertId = $(elem).attr('data-imgid');
		$('#exercise_unit').val(insertId);
		$('#exercise_title').val($(elem).attr('data-imgname'));
		$('#exercise_unit_img').val('');
		if($(elem).attr('data-imgpath') !='' && $(elem).attr('data-imgpath')!=undefined){
			$('#exercise_unit_img').val($(elem).attr('data-imgpath'));
			if($('div#img-preview-pop').length)
				$('div#img-preview-pop').html('<img style="padding-right:10px;" width="50px;" src="'+$(elem).attr('data-imgpath')+'"  />');
		}else{
			if($('div#img-preview-pop').length)
				$('div#img-preview-pop').html('<i class="fa fa-file-image-o datacol" style="font-size:50px;"></i>');	
			$('#exercise_unit_img').val();
		}
		if($('input.exercise_title').is(':visible')){
			setTimeout(function(){
				$('input.exercise_title').focus();
			}, 400);
		}
		$('.errormsg').hide();
		insertExtraToParentHidden('myOptionsModalAjax');
		closeModelwindow('exerciselib-model');
	}
	/*opens insert option modal for create xr-rec from xr-lib*/
	function xrLibinsertToExerciseSet(xrsId, type, imgpath, xrstitle){
		xrLibgetXrsetOptionsPopup(xrsId, type, imgpath, xrstitle);
	}
	/*opens insert option modal for create xr-rec from preview xr-rec*/
	function xrLibgetXrsetOptionsPopup(fid, type, image_url, title){
		$('#preview-xr-modal-options').html();
		$.ajax({
			url : siteUrl + "search/getmodelTemplate",
			data : {
				action : 'actionplanOptions',
				method : 'options',
				id : fid,
				foldid : '',
				type : type,
				modelType : "preview-xr-modal-options",
				title : title,
				requestFrom:'workouts',
				actionFrom:'workouts'
			},
			success : function(content){
				$('#preview-xr-modal-options').html(content);
				$('#preview-xr-modal-options').modal();
			}
		});
	}
	/*call and insert xr-rec to xr-set from xr-rec create modal*/
	function insertRecordFromAdded(insertXrId, title, imagepath){
		$('#exercise_unit').val(insertXrId);
		$('#exercise_title').val(title);
		$('#exercise_unit_img').val('');
		if(imagepath!=''){
			$('#exercise_unit_img').val(imagepath);
			if($('div#img-preview-pop').length)
				$('div#img-preview-pop').html('<img style="padding-right:10px;" width="50px;" src="'+imagepath+'"  />');
		}else{
			if($('div#img-preview-pop').length)
				$('div#img-preview-pop').html('<i class="fa fa-file-image-o datacol" style="font-size:50px;"></i>');	
			$('#exercise_unit_img').val();
		}
		if($('input.exercise_title').is(':visible')){
			setTimeout(function(){
				$('input.exercise_title').focus();
			}, 400);
		}
		$('.errormsg').hide();
		if($('#xrcisesaveopt-modal').is(':visible')){
			$('#xrcisesaveopt-modal').modal('hidecustom');
		}
		insertExtraToParentHidden('myOptionsModalAjax');
		$('#exerciselib-model #act-flag').val('');
		closeModelwindowCustom('exercisecreate-modal');
		closeModelwindowCustom('exerciselib-model');
	}
	/*preview the xr-rec from xr-lib xr-rec options modal*/
	function getajaxExercisepreviewOfDay(exerciseId, wkoutId){
		$('#myModal-exercisepreview').html();
		$.ajax({
			url : siteUrl + 'search/getmodelTemplate',
			data : {
				action : 'previewExerciseOfDay',
				method :  'preview', 
				id : exerciseId,
				foldid : wkoutId,
				modelType : 'myModal-exercisepreview'
			},
			success : function(content){
				$('#myModal-exercisepreview').html(content);
				$('#myModal-exercisepreview').modal();
				$('#exerciselib-model').hide();
			}
		});
	}
	function triggerShowMoreRecord(ev) {
		ev.preventDefault();
		if(ev.handled !== true) {
			ev.handled = true;
			if($('#exerciselib-model #fltrshowmore-rec').length){
				$('#exerciselib-model #fltrshowmore-rec').trigger('click');
			}else{
			}
		}
		return false;
	}
	$(document).ready(function(){
		if(fileList.length){
			fileList.bind('scroll', function(ev){
				if($(this).scrollTop() + $(this).innerHeight() == $(this)[0].scrollHeight){
					triggerShowMoreRecord(ev);
				}
			});
		}
	});
	function triggerShareExerciseModal(xruid, xrtitle) {
		$.ajax({
			url: siteUrl + "ajax/getAjaxExerciseShareHtml",
			type: 'post',
			data: {
				action: 'shareExercise',
				xrid: xruid,
				title: xrtitle,
				actFrom : 'template',
				reqFrom: 'front'
			},
			success : function(content){
				var ajaxData = JSON.parse(content);
				$('#sharexrcise-modal').empty();
				if(ajaxData.content!=''){
					$('#sharexrcise-modal').html(ajaxData.content);
					$("input#xr_user_names").select2({ placeholder: "Search Users", minimumInputLength: 2, multiple: true,
						ajax: { url: siteUrl + 'search/getajax/',
							data: function(term, page) {
								return {
									title: term,
									siteids: $("input#xr_site_names").val(),
									maxRows: 5,
									action : "getusers"
								};
							},  
							results: function (data, page) {
								return { results: data };
							}
						}
					});
					$("input#xr_site_names").select2({ placeholder: "Search Sites", minimumInputLength: 2, multiple: true,
						ajax: { url: siteUrl + 'search/getajax/',
							data: function(term, page) {
								return {
									title: term,
									siteids: $("input#xr_site_names").val(),
									maxRows: 5,
									action : "getsites"
								};
							},
							results: function (data, page) {
								return { results: data };
							}
						}
					}).select2("data",[{"id": ajaxData.siteid, "text": ajaxData.sitename}]);
					$('#myOptionsModalExerciseRecord').modal('hide');
					$('#sharexrcise-modal').modal();
				}
			}
		});
	}
	function checkValidXrShareInfo() {
		if ($('input#xr_user_names').val() != ''){
			shareExerciseRecord();
			return true;
		}
		else $('div.share-errormsg').html('Please choose atleast one Recipient').removeClass('hide');
		return false;
	}
	function checkValidAdminXrShareInfo() {
		if ($('input#xr_user_names').val() != '' && $('input#xr_site_names').val() != ''){
			shareExerciseRecord();
			return true;
		}
		else {
			if ($('input#xr_site_names').val() == '') $('div.share-errormsg').html('Please choose atleast one Site').removeClass('hide');
			else $('div.share-errormsg').html('Please choose atleast one Recipient').removeClass('hide')
		};
		return false;
	}
	function shareExerciseRecord() {
		$.ajax({
			url : siteUrl + 'ajax/shareExerciseFromXrciseModal',
			method: 'post',
			data : $('#form_shareExercise').serialize() + '&action=sharing',
			success : function(content){
				var response = JSON.parse(content);
				if(response.msg=='success'){
					$('#sharexrcise-modal').modal('hide');
					setTimeout(function() {
						$('#exerciselib-model .gallery-div').prepend('<div class="row bannermsg"><div class="col-xs-12 banner success">'+__('Exercise record, shared successfully')+' !!!</div></div>');
					}, 200);
				}else{
					setTimeout(function() {
						$('#exerciselib-model .gallery-div').prepend('<div class="row bannermsg"><div class="col-xs-12 banner errors">'+__('Error occurred while sharing')+' !!!</div></div>');
					}, 200);
				}
				setTimeout(function() {
					$('#exerciselib-model .bannermsg').fadeOut(10000);
				}, 250);
			}
		});
	}
	$(document).on('click keyup', '.bootstrap-tagsinput', function(e) {
		e.preventDefault();
		if (e.handled !== true) {
			e.handled = true;
			if($('.tt-menu.tt-open').is(':visible')){
				$('.gallery-contnr').animate({
					scrollTop: $('.gallery-contnr')[0].scrollHeight
				}, 1000);
			}
		}
	});
	$(document).on("keypress", ":input:not(textarea)", function( ev ) {
		var code = ev.keyCode || ev.which;
		if( code === 13 ) {
			ev.preventDefault();
			return false; 
		}
	});
	function closeAllPopupWindowWriteTitle(){
		$('#xrliboption-modal').modal('hidecustom');
		$('#exerciselib').bootstrapSwitch('state', false); 
		if($('input#exercise_title').is(':visible')){
			setTimeout(function(){
				$('input#exercise_title').val('').focus();
			}, 400);
		}
		$('#myOptionsModalAjax').modal();
		if($('#title-div').length)
			$('#title-div').removeClass('hide');
		closeModelwindowCustom('exerciselib-model');
	}
	function closeAllPopupWindow(){
		$('#exerciselib').bootstrapSwitch('state', false);
		if($('input#exercise_title').is(':visible')){
			setTimeout(function(){
				$('input#exercise_title').val('').focus();
			}, 400);
		}		
		$('#myOptionsModalAjax').modal('hide');
		$('#xrliboption-modal').modal('hidecustom');
		if($('#xrliboption-modal.modal-backdrop').length > 0){
			$('#xrliboption-modal.modal-backdrop').remove();
		}
		if($('#title-div').length)
			$('#title-div').removeClass('hide');
		$('#exerciselib-model').modal('hide');
	}
	function closeAllPopupWindowEdit(){
		$('#xrliboption-modal').modal('hidecustom');
		$('#exerciselib').bootstrapSwitch('state', false); 
		if($('input#exercise_title').is(':visible')){
			setTimeout(function(){
				$('input#exercise_title').val('').focus();
			}, 400);
		}
		//$('#myOptionsModalAjax').modal('hidecustom');
		//closeModelwindow('myOptionsModalAjax');
		$('#myOptionsModalAjax').modal();
		if($('#ref-flag').val()!=''){
			$('#ref-flag').val('');
			// $('#act-flag').val('');
			setTimeout(function(){
				xrLibCreateExercise();
			}, 300);
		}
		if($('#title-div').length)
			$('#title-div').removeClass('hide');
		closeModelwindowCustom('exerciselib-model');
	}
	</script>
</div>