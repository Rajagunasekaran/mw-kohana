  <?php //echo "test"; die;?>
	<!--- Top nav && left nav--->
	<?php echo $topnav.$leftnav;?>
	<!--- Top nav && left nav --->
		<!-- Content Wrapper. Contains page content -->
		<div id="page-wrapper">

				<div class="container-fluid pageexebrowse">

					 <!-- Page Heading -->
					 <div class="row">
						  <div class="col-lg-12">
								<h1 class="page-header">
							<?php echo (isset($site_language['Browse'])) ? $site_language['Browse'] : 'Browse';?> 
									<?php $session = Session::instance();
							 if($default_status==2){
								if($session->get('current_site_name')!=''){echo '"'.$session->get('current_site_name').'"';}
							 }	?>
							
								<?php echo ($default_status==1) ? __("Default Exercise Record(s)") : __("Sample Exercise Record(s)"); ?>
								
								</h1>
						
								<ol class="breadcrumb">
									 <li>
										  <i class="fa fa-dashboard"></i>  <a href="<?php echo URL::base().'admin/index'; ?>"><?php echo __('Dashboard'); ?></a>
									 </li>
									 <li class="active">
										  <i class="fa fa-edit"></i>
										  
										 <?php echo ($default_status==1)?__("Default Exercise Records"):__("Sample Exercise Records"); ?>
									 </li>
								</ol>
						  </div>
					</div>
					 <!-- /.row -->
			 		<?php $session = Session::instance();
					if ($session->get('flash_success')){ ?>
					<div class="banner alert alert-success">
						<?php echo $session->get_once('flash_success') ?>
					</div>
					<?php }
				  	if ($session->get('flash_error')){ ?>
					<div class="banner alert alert-danger">
						<?php echo $session->get_once('flash_error') ?>
				  	</div>
					<?php } ?>
					 
				<?php if(isset($success) && $success!='') {  ?>
					<div class="row">
						<div class="col-lg-12">
							<div class="alert alert-success">
							  <i class="fa fa-check"></i><span><?php echo $success;?></span>
							</div>
						</div>
					</div>
				<?php } ?>
				<div class="ajax-info-alert" style="display:none;"></div>
				<div class="del-sucess" style="display:none;">
					<div class="row">
						<div class="col-lg-12">
							<div class="alert alert-success">
							  <i class="fa fa-check"></i><span></span>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<h2 class="col-lg-6">
						<?php if(isset($template_details_all) && count($template_details_all)>0) { echo count($template_details_all); }?> 
						<?php echo ($default_status==1)?__("Exercise Record(s)"):__("Sample Exercise Record(s)"); ?>
					</h2>
					<div class="col-lg-6"> 
					<!--
					<a class="btn btn-default Add_Exercise_Record" href="<?php echo URL::base().'admin/exercise/create';?>" style="float:right;">Add Exercise Record</a> --> </div>
				</div>
				<!--<div class="row">
					<div class="col-lg-12">
						<button class="btn btn-default refine" type="button" value="" onclick="showAdvanceSearch();"> <i data-class="filter_this" class="fa fa-caret-down bluecol search-filter iconsize"></i></button>
					</div>
				</div>-->
				<?php 
					$src_url = URL::base().'admin/exercise/sample'.(!empty($default_status) ? '?d='.$default_status : '');
				?>
				<form class="advnce-srch-frm" action="<?php echo $src_url;?>" method="post">
				<div class="gallery-div">
					
						<div class="topsearch">
							
							<div class="border">
								<div class="col-xs-11">
									<div id="xr_filter_search" class="">
										<input type="text" name="autosearch" class="searchtext form-control input-sm" placeholder="<?php echo __('Search by exercise name'); ?>..." value="<?php if(isset($searchval['autosearch'])){echo $searchval['autosearch'];}?>">
										<span class="searchclear fa fa-remove" style="display:<?php if(isset($searchval['autosearch']) && $searchval['autosearch'] != ''){echo 'block';}else{echo 'none';}?>;"></span>
									</div>
								</div>
								<div class="col-xs-1" id="advancsearch" onclick="showAdvanceSearch();">
									<i class="fa fa-caret-down search-filter iconsize" data-class="filter_this"></i>
								</div>
							</div>
							<div class="border">
							<div class="col-sm-10 filter_exe">
									<label for="sortby"><?php echo __('Sort By'); ?> :</label>
									<select id="sortby" name="sortby" class="sortby selectpicker selectAction" data-live-search="true" style="width:250px;">
									<option value="asc" <?php if(isset($searchval['sortby']) && $searchval['sortby'] == 'asc'){echo 'selected';}?>>A-Z</option>
									<option value="desc" <?php if(isset($searchval['sortby']) && $searchval['sortby'] == 'desc'){echo 'selected';}?>>Z-A</option>
									<option value="date_created" <?php if(isset($searchval['sortby']) && $searchval['sortby'] == 'date_created'){echo 'selected';}?>>Created (Most Recent)</option>
									<option value="date_modified" <?php if(isset($searchval['sortby']) && $searchval['sortby'] == 'date_modified'){echo 'selected';}?>>Modified (Most Recent)</option>
									</select>
							</div>
							<div class="col-sm-1 topsearchbtn">
								<!--<button class="btn btn-default" type="button" onclick="getAdvanceSearchRecords()">Fetch Records</button>-->
								<input type='hidden' name='pageval' value='<?php if(isset($_REQUEST['page']) && $_REQUEST['page'] != ''){echo $_REQUEST['page'];}?>' />
								<input type="button" class="btn btn-default btncol fetch-record" id="getexerciseresult" value="Fetch Records"/>
								<!--<input type="reset" class="btn btn-default" id="Reset" value="Reset" onclick="clearForm(this.form);"/>-->
							</div>
							<div class="col-sm-1 topsearchbtn">
								<input type="button" class="btn btn-default btncol resetserach" id="getexerciseresult" value="Reset Search"/>
							</div>
						</div>
						</div>
						<div class="col-lg-6"></div>
						<div class="col-lg-12 advance-search-contnr " style="display:none;border: 1px solid #ddd;">
							<div class="col-lg-12">
								<h3><?php echo __('Advanced Search'); ?></h3>
							</div>
							<div class="">
								<!--Tab Start-->
								<div class="col-sm-12">
									<ul class="nav nav-tabs">
										<li class="active"><a data-toggle="tab" href="#exerciselib"><?php echo __('Target Muscle'); ?></a></li>
										<li><a data-toggle="tab" href="#exercisetypes"><?php echo __('Exercise Types'); ?></a></li>
										<li><a data-toggle="tab" href="#exerciseequips"><?php echo __('Equipment Type'); ?></a></li>
										<li><a data-toggle="tab" href="#exerciselevels"><?php echo __('Training Level Type'); ?></a></li>
										<li><a data-toggle="tab" href="#exercisesports"><?php echo __('Sport Type'); ?></a></li>
										<li><a data-toggle="tab" href="#exerciseactions"><?php echo __('Force Movement Type'); ?></a></li>
										<li><a data-toggle="tab" href="#exercisestatus"><?php echo __('Status'); ?></a></li>
										<li><a data-toggle="tab" href="#futured"><?php echo __('Futured'); ?></a></li>
										<li><a data-toggle="tab" href="#exercisetags"><?php echo __('Tags'); ?></a></li>
									</ul>
								</div>
								<div class="tab-content">
									<div id="exerciselib" class="tab-pane fade in active">
										<div class="bodycontent exerciselib">
											
											<div class="border">
												<div class="exer1"><?php echo __('Target Muscle'); ?></div>
												<div class="exer2">
													<select id="musprim" name="musprim" class="selectpicker selectAction" data-live-search="true">
														<option value="">Select an option</option>
														<option value="1" <?php if(isset($searchval['musprim']) && $searchval['musprim'] == 1){echo 'selected';}?>>Abs</option>
														<option value="2" <?php if(isset($searchval['musprim']) && $searchval['musprim'] == 2){echo 'selected';}?>>Abductors</option>
														<option value="3" <?php if(isset($searchval['musprim']) && $searchval['musprim'] == 3){echo 'selected';}?>>Adductors</option>
														<option value="4" <?php if(isset($searchval['musprim']) && $searchval['musprim'] == 4){echo 'selected';}?>>Biceps</option>
														<option value="5" <?php if(isset($searchval['musprim']) && $searchval['musprim'] == 5){echo 'selected';}?>>Calves</option>
														<option value="6" <?php if(isset($searchval['musprim']) && $searchval['musprim'] == 6){echo 'selected';}?>>Chest</option>
														<option value="7" <?php if(isset($searchval['musprim']) && $searchval['musprim'] == 7){echo 'selected';}?>>Forearms</option>
														<option value="8" <?php if(isset($searchval['musprim']) && $searchval['musprim'] == 8){echo 'selected';}?>>Glutes</option>
														<option value="9" <?php if(isset($searchval['musprim']) && $searchval['musprim'] == 9){echo 'selected';}?>>Hams</option>
														<option value="10" <?php if(isset($searchval['musprim']) && $searchval['musprim'] == 10){echo 'selected';}?>>Lats</option>
														<option value="11" <?php if(isset($searchval['musprim']) && $searchval['musprim'] == 11){echo 'selected';}?>>Low Back</option>
														<option value="12" <?php if(isset($searchval['musprim']) && $searchval['musprim'] == 12){echo 'selected';}?>>Mid Back</option>
														<option value="13" <?php if(isset($searchval['musprim']) && $searchval['musprim'] == 13){echo 'selected';}?>>Neck</option>
														<option value="14" <?php if(isset($searchval['musprim']) && $searchval['musprim'] == 14){echo 'selected';}?>>Quads</option>
														<option value="15" <?php if(isset($searchval['musprim']) && $searchval['musprim'] == 15){echo 'selected';}?>>Shoulders</option>
														<option value="16" <?php if(isset($searchval['musprim']) && $searchval['musprim'] == 16){echo 'selected';}?>>Traps</option>
														<option value="17" <?php if(isset($searchval['musprim']) && $searchval['musprim'] == 17){echo 'selected';}?>>Triceps</option>
													</select>
												</div>
												<div class="exer3"><a href="javascript:void(0);" class="filter_sub_btn select_none"><?php echo __('Clear'); ?></a></div>
											</div>
											<div class="exerciselibrary">
												<div class="exercisemarkleft">
													<div class="exercisemarkleft">
														<ul class="select_list_muscle" id="listmuscle">
															<li class="list_muscle muscle_id-13 <?php if(isset($searchval['musprim']) && $searchval['musprim'] == 13){echo 'xr_target_selected';}?>">+</li>	<!--Neck-->
															<li class="list_muscle muscle_id-15 <?php if(isset($searchval['musprim']) && $searchval['musprim'] == 15){echo 'xr_target_selected';}?>">+</li>	<!--Shoulders-->
															<li class="list_muscle muscle_id-6 <?php if(isset($searchval['musprim']) && $searchval['musprim'] == 6){echo 'xr_target_selected';}?>">+</li>	<!--Chest-->
															<li class="list_muscle muscle_id-4 <?php if(isset($searchval['musprim']) && $searchval['musprim'] == 4){echo 'xr_target_selected';}?>">+</li>	<!--Biceps-->
															<li class="list_muscle muscle_id-7 <?php if(isset($searchval['musprim']) && $searchval['musprim'] == 7){echo 'xr_target_selected';}?>">+</li>	<!--Forearm-->
															<li class="list_muscle muscle_id-1 <?php if(isset($searchval['musprim']) && $searchval['musprim'] == 1){echo 'xr_target_selected';}?>">+</li>	<!--Abs-->
															<li class="list_muscle muscle_id-3 <?php if(isset($searchval['musprim']) && $searchval['musprim'] == 3){echo 'xr_target_selected';}?>">+</li>	<!--Adductors-->
															<li class="list_muscle muscle_id-14 <?php if(isset($searchval['musprim']) && $searchval['musprim'] == 14){echo 'xr_target_selected';}?>">+</li>	<!--Quads-->
															<!--
															<li class="xr_target muscle_id-18">+</li>--><!--Feet-->
														</ul>
													</div>
												</div>
												<div id="exercisemarkimage" class="exercisemarkcenter">
													<?php if(isset($searchval['musprim']) && $searchval['musprim'] == 5){?>
														<img src="<?php echo URL::base_lang().'assets/img/anatomy/anatomy_18.jpg'; ?>" />
													<?php }else if(isset($searchval['musprim']) && $searchval['musprim'] == 9){?>
														<img src="<?php echo URL::base_lang().'assets/img/anatomy/anatomy_17.jpg'; ?>" />
													<?php }else if(isset($searchval['musprim']) && $searchval['musprim'] == 2){?>
														<img src="<?php echo URL::base_lang().'assets/img/anatomy/anatomy_16.jpg'; ?>" />
													<?php }else if(isset($searchval['musprim']) && $searchval['musprim'] == 8){?>
														<img src="<?php echo URL::base_lang().'assets/img/anatomy/anatomy_15.jpg'; ?>" />
													<?php }else if(isset($searchval['musprim']) && $searchval['musprim'] == 11){?>
														<img src="<?php echo URL::base_lang().'assets/img/anatomy/anatomy_14.jpg'; ?>" />
													<?php }else if(isset($searchval['musprim']) && $searchval['musprim'] == 12){?>
														<img src="<?php echo URL::base_lang().'assets/img/anatomy/anatomy_13.jpg'; ?>" />
													<?php }else if(isset($searchval['musprim']) && $searchval['musprim'] == 17){?>
														<img src="<?php echo URL::base_lang().'assets/img/anatomy/anatomy_12.jpg'; ?>" />
													<?php }else if(isset($searchval['musprim']) && $searchval['musprim'] == 10){?>
														<img src="<?php echo URL::base_lang().'assets/img/anatomy/anatomy_11.jpg'; ?>" />
													<?php }else if(isset($searchval['musprim']) && $searchval['musprim'] == 16){?>
														<img src="<?php echo URL::base_lang().'assets/img/anatomy/anatomy_10.jpg'; ?>" />
													<?php }else if(isset($searchval['musprim']) && $searchval['musprim'] == 14){?>
														<img src="<?php echo URL::base_lang().'assets/img/anatomy/anatomy_08.jpg'; ?>" />
													<?php }else if(isset($searchval['musprim']) && $searchval['musprim'] == 3){?>
														<img src="<?php echo URL::base_lang().'assets/img/anatomy/anatomy_07.jpg'; ?>" />
													<?php }else if(isset($searchval['musprim']) && $searchval['musprim'] == 1){?>
														<img src="<?php echo URL::base_lang().'assets/img/anatomy/anatomy_06.jpg'; ?>" />
													<?php }else if(isset($searchval['musprim']) && $searchval['musprim'] == 7){?>
														<img src="<?php echo URL::base_lang().'assets/img/anatomy/anatomy_05.jpg'; ?>" />
													<?php }else if(isset($searchval['musprim']) && $searchval['musprim'] == 4){?>
														<img src="<?php echo URL::base_lang().'assets/img/anatomy/anatomy_04.jpg'; ?>" />
													<?php }else if(isset($searchval['musprim']) && $searchval['musprim'] == 6){?>
														<img src="<?php echo URL::base_lang().'assets/img/anatomy/anatomy_03.jpg'; ?>" />
													<?php }else if(isset($searchval['musprim']) && $searchval['musprim'] == 15){?>
														<img src="<?php echo URL::base_lang().'assets/img/anatomy/anatomy_02.jpg'; ?>" />
													<?php }else if(isset($searchval['musprim']) && $searchval['musprim'] == 13){?>
														<img src="<?php echo URL::base_lang().'assets/img/anatomy/anatomy_01.jpg'; ?>" />
													<?php }else{?>
														<img src="<?php echo URL::base_lang().'assets/img/anatomy/anatomy.jpg'; ?>" />
													<?php }?>
												</div>
												<div class="exercisemarkright">
													<div class="left">
														<ul class="select_list_muscle" id="listmuscle2">
															<li class="list_muscle muscle_id-16 <?php if(isset($searchval['musprim']) && $searchval['musprim'] == 16){echo 'xr_target_selected';}?>">+</li>	<!--Traps-->
															<li class="list_muscle muscle_id-10 <?php if(isset($searchval['musprim']) && $searchval['musprim'] == 10){echo 'xr_target_selected';}?>">+</li>	<!--Lats-->
															<li class="list_muscle muscle_id-17 <?php if(isset($searchval['musprim']) && $searchval['musprim'] == 17){echo 'xr_target_selected';}?>">+</li>	<!--Triceps-->
															<li class="list_muscle muscle_id-12 <?php if(isset($searchval['musprim']) && $searchval['musprim'] == 12){echo 'xr_target_selected';}?>">+</li>	<!--Mid Back-->
															<li class="list_muscle muscle_id-11 <?php if(isset($searchval['musprim']) && $searchval['musprim'] == 11){echo 'xr_target_selected';}?>">+</li>	<!--Low Back-->
															<li class="list_muscle muscle_id-8 <?php if(isset($searchval['musprim']) && $searchval['musprim'] == 8){echo 'xr_target_selected';}?>">+</li>	<!--Glutes-->
															<li class="list_muscle muscle_id-2 <?php if(isset($searchval['musprim']) && $searchval['musprim'] == 2){echo 'xr_target_selected';}?>">+</li>	<!--Abductors-->
															<li class="list_muscle muscle_id-9 <?php if(isset($searchval['musprim']) && $searchval['musprim'] == 9){echo 'xr_target_selected';}?>">+</li>	<!--Hams-->
															<li class="list_muscle muscle_id-5 <?php if(isset($searchval['musprim']) && $searchval['musprim'] == 5){echo 'xr_target_selected';}?>">+</li>	<!--Calves-->
														</ul>
													</div>
												</div>
											</div>
											
										</div>
									</div>
								
								
									<div id="exercisetypes" class="tab-pane fade">
										<?php if(isset($searchval['exercisetypes'])){$ExerciseTypesArray = array_flip($searchval['exercisetypes']);} ?>
										<div class="bodycontent exercisetypes">
											<div class="filterTitle title=types col-sm-12">
												<div class="filter_heading col-sm-12"><h4><?php echo __('Exercise Types'); ?></h4></div>
												<div class="filter_column col-sm-12">
													<div class="filter_subhead col-sm-12">
														<a href="javascript:void(0);" class="filter_sub_btn select_all"><?php echo __('Select All'); ?></a>
														<span class="filter_sub_divider">|</span>
														<a href="javascript:void(0);" class="filter_sub_btn select_none"><?php echo __('Select None'); ?></a>
													</div>
													<?php $exerciseType = Model::instance('Model/workouts')->getcheckboxes('type','unit_','_title','_id','unit_gendata','type','0');
													if(count($exerciseType)>0){
														foreach($exerciseType as $keys => $values){ $key = $keys + 1;?>
															<div class="col-sm-12">
																<input type="checkbox" 	name="exercisetypes[]"  <?php if(isset($ExerciseTypesArray[$key])){echo 'checked';}?> class="type_chkbx" value="<?php echo $values['type_id'];?>" tabindex="<?php echo $keys;?>">
																<label for="exercisetypes[]"><?php echo ucwords($values['type_title']);?></label>
																
															</div>
													<?php }
													} ?>
												</div>
											</div> <!-- [END] filter_levelType -->
											<div style="clear:both;"></div>      
										</div>
									</div>
									<div id="exerciseequips" class="tab-pane fade">
										<?php if(isset($searchval['exerciseequips'])){$exerciseequipsArray = array_flip($searchval['exerciseequips']);}?>
										<div class="bodycontent exerciseequips">     
											<div class="filterTitle title=equips col-sm-12">
												<div class="filter_heading col-sm-12"><h4><?php echo __('Equipment Type'); ?></h4></div>
												<div class="filter_column col-sm-12">
													<div class="filter_subhead col-sm-12">
														<a href="javascript:void(0);" class="filter_sub_btn select_all"><?php echo __('Select All'); ?></a>
														<span class="filter_sub_divider">|</span>
														<a href="javascript:void(0);" class="filter_sub_btn select_none"><?php echo __('Select None'); ?></a>
													</div>
													<?php $exerciseEquip = Model::instance('Model/workouts')->getcheckboxes('equip','unit_','_title','_id','unit_gendata','equip','0');
													if(count($exerciseEquip)>0){
														foreach($exerciseEquip as $keys => $values){ $key = $values['equip_id'];?>
															<div class="col-sm-12">
																<input type="checkbox"	name="exerciseequips[]" <?php if(isset($exerciseequipsArray[$key])){echo 'checked';}?> 	class="type_chkbx" value="<?php echo $values['equip_id'];?>" tabindex="<?php echo $keys;?>">
																<label for="exerciseequips[]"><?php echo ucwords($values['equip_title']);?></label>
																
															</div>
													<?php }
													} ?>
												</div>
											</div> <!-- [END] filter_equipType -->
											<div style="clear:both;"></div>					
										</div>
									</div>
									<div id="exerciselevels" class="tab-pane fade">
										<?php if(isset($searchval['exerciselevels'])){$exerciselevelsArray = array_flip($searchval['exerciselevels']);}?>
										<div class="bodycontent exerciselevels">
											<div class="filterTitle title=levels col-sm-12">
												<div class="filter_heading col-sm-12"><h4><?php echo __('Training Level Type'); ?></h4></div>
												<div class="filter_column col-sm-12">
													<div class="filter_subhead col-sm-12">
														<a href="javascript:void(0);" class="filter_sub_btn select_all"><?php echo __('Select All'); ?></a>
														<span class="filter_sub_divider">|</span>
														<a href="javascript:void(0);" class="filter_sub_btn select_none"><?php echo __('Select None'); ?></a>
													</div>
													<?php  $exerciseLevel = Model::instance('Model/workouts')->getcheckboxes('level','unit_','_title','_id','unit_gendata','level','0');
													if(count($exerciseLevel)>0){
														foreach($exerciseLevel as $keys => $values){ $key = $values['level_id'];?>
															<div class="col-sm-12">
																<input type="checkbox"	name="exerciselevels[]" <?php if(isset($exerciselevelsArray[$key])){echo 'checked';}?>	class="type_chkbx" value="<?php echo $values['level_id'];?>" tabindex="<?php echo $keys;?>">
																<label for="exerciselevels[]"><?php echo ucwords($values['level_title']);?></label>
																
															</div>
														<?php }
													} ?>
												</div>
											</div> <!-- [END] filter_levelType -->
											<div style="clear:both;"></div>					
										</div>
									</div>
									<div id="exercisesports" class="tab-pane fade">
										<?php if(isset($searchval['exercisesports'])){$exercisesportsArray = array_flip($searchval['exercisesports']);}?>
										<div class="bodycontent exercisesports">
											<div class="filterTitle title=sports col-sm-12">
												<div class="filter_heading col-sm-12"><h4><?php echo __('Sport Type'); ?></h4></div>
												<div class="filter_column col-sm-12">
													<div class="filter_subhead col-sm-12">
														<a href="javascript:void(0);" class="filter_sub_btn select_all"><?php echo __('Select All'); ?></a>
														<span class="filter_sub_divider">|</span>
														<a href="javascript:void(0);" class="filter_sub_btn select_none"><?php echo __('Select None'); ?></a>
													</div>
													<?php $exerciseSport = Model::instance('Model/workouts')->getcheckboxes('sport','unit_','_title','_id','unit_gendata','sport','0');
													if(count($exerciseSport)>0){
														foreach($exerciseSport as $keys => $values){ $key = $values['sport_id'];?>
															<div class="col-sm-12">
																<input type="checkbox"	name="exercisesports[]" <?php if(isset($exercisesportsArray[$key])){echo 'checked';}?>	class="type_chkbx" value="<?php echo $values['sport_id'];?>" tabindex="<?php echo $keys;?>">
																<label for="exercisesports[]"><?php echo ucwords($values['sport_title']);?></label>
																
															</div>
													<?php }
													} ?>
												</div>
											</div> <!-- [END] filter_levelType -->
											<div style="clear:both;"></div>
										</div>	
									</div>
									<div id="exerciseactions" class="tab-pane fade">									
										<?php if(isset($searchval['exerciseactions'])){$exerciseactionsArray = array_flip($searchval['exerciseactions']);}?>			
										<div class="bodycontent exerciseactions">
											<div class="filterTitle title=actions col-sm-12">
												<div class="filter_heading col-sm-12"><h4><?php echo __('Force Movement Type'); ?></h4></div>
												 <div class="filter_column col-sm-12">
													 <div class="filter_subhead col-sm-12">
														<a href="javascript:void(0);" class="filter_sub_btn select_all"><?php echo __('Select All'); ?></a>
														<span class="filter_sub_divider">|</span>
														<a href="javascript:void(0);" class="filter_sub_btn select_none"><?php echo __('Select None'); ?></a>
													</div>
													<?php $exerciseForce = Model::instance('Model/workouts')->getcheckboxes('force','unit_','_title','_id','unit_gendata','force','0');
													if(count($exerciseForce)>0){
														foreach($exerciseForce as $keys => $values){ $key = $values['force_id'];?>
															<div class="col-sm-12">
																<input type="checkbox"	name="exerciseactions[]"  <?php if(isset($exerciseactionsArray[$key])){echo 'checked';}?>	class="type_chkbx" value="<?php echo $values['force_id'];?>" tabindex="<?php echo $keys;?>">
																<label for="exerciseactions[]"><?php echo ucwords($values['force_title']);?></label>
																
															</div>
													<?php }
													} ?>
												</div>
											</div> <!-- [END] filter_levelType -->
											<div style="clear:both;"></div>       
										</div>
									</div>
									<div id="exercisestatus" class="tab-pane fade">
										<div class="bodycontent exercisestatus">
											<div class="filterTitle title=status col-sm-12">
												<div class="filter_heading col-sm-12"><h4><?php echo __('Status'); ?></h4> <?php if(!empty($searchval['statusfilter'])) { $exercisestatusArray = array_flip($searchval['statusfilter']);}?></div>
												<div class="filter_column col-sm-12">
													<div class="filter_subhead col-sm-12">
														<a href="javascript:void(0);" class="filter_sub_btn select_all"><?php echo __('Select All'); ?></a>
														<span class="filter_sub_divider">|</span>
														<a href="javascript:void(0);" class="filter_sub_btn select_none"><?php echo __('Select None'); ?></a>
													</div>
													<?php 
														if(isset($status)) {
															foreach($status as $keys => $value) { $key = $keys + 1;?>
																<div class="col-sm-12">
																	<input type="checkbox"	name="statusfilter[]" <?php if(isset($exercisestatusArray[$key])){echo 'checked';}?>	class="type_chkbx" value="<?php echo $value['id'];?>" tabindex="<?php echo  $value['status_title'];?>">
																	<label for="statusfilter[]"><?php echo ucwords($value['status_title']);?></label>
																	
																</div>
														<?php }
														} ?>
												</div>
											</div> <!-- [END] filter_levelType -->
											<div style="clear:both;"></div>      
										</div>
									</div>
									<div id="futured" class="tab-pane fade">
										<div class="bodycontent futured">
											<div class="filterTitle title=futured col-sm-12">
												<div class="filter_heading col-sm-12"><h4><?php echo __('Futured'); ?></h4></div>
												<div class="filter_column col-sm-12">
														<div class="col-sm-12">
															<input type="checkbox"	name="futured_filter" <?php if(isset($searchval['futured_filter']) && $searchval['futured_filter'] ==1 ){echo 'checked';}?>   class="type_chkbx" value="1" tabindex="">
															<label for="futured_filter"><?php echo ucwords('featured');?></label>
														</div>
													
												</div>
											</div> <!-- [END] filter_levelType -->
											<div style="clear:both;"></div>      
										</div>
									</div>
									<div id="exercisetags" class="tab-pane fade">
										<div class="bodycontent exercisetags">
											<div class="filterTitle title=tags col-sm-12">
												<div class="filter_heading col-sm-12"><h4><?php echo __('Tags'); ?></h4></div>
												<div class="filter_column col-sm-12">
													<div class="col-sm-12">
														<input type="text" class="form-control exercisetags" name="exercisetags" value="" placeholder="Tags" data-role="tagsinput"/>
													</div>
												</div>
											</div> <!-- [END] filter_levelType -->
											<div style="clear:both;"></div> 
										</div>
									</div>
								
								<!--Tab End-->
								
								<?php /*
								<div class="row bodycontent exercisetags">
									<div class="filterTitle title=tags col-sm-12">
										<div class="filter_heading col-sm-12"><?php echo __('Tags Type'); ?></div>
										<div class="filter_column col-sm-12">
											<div class="filter_subhead col-sm-12">
												<a href="javascript:void(0);" class="filter_sub_btn select_all"><?php echo __('Select All'); ?></a>
												<span class="filter_sub_divider">|</span>
												<a href="javascript:void(0);" class="filter_sub_btn select_none"><?php echo __('Select None'); ?></a>
											</div>
											<?php $exerciseTag = Model::instance('Model/workouts')->getcheckboxes('tag','','_title','_id','unit_gendata','tag','0');
											if(count($exerciseTag)>0){
												foreach($exerciseTag as $keys => $values){ $key = $values['tag_id'];?>
													<div class="col-sm-12">
														<input type="checkbox"	name="exercisetags[]" <?php if(isset($exercisetagsArray[$key])){echo 'checked';}?>	class="type_chkbx" value="<?php echo $values['tag_id'];?>" tabindex="<?php echo $keys;?>">
														<label for="exercisetags[]"><?php echo ucwords($values['tag_title']);?></label>
														<div style="clear:left;"></div>
													</div>
											<?php }
											} ?>
										</div>
									</div> <!-- [END] filter_levelType -->
									<div style="clear:both;"></div> 
								</div>
								*/ ?>
							</div>
							
							
							<?php /* if(isset($filter_details) && count($filter_details)>0) { 
								$i = 0;
								foreach($filter_details as $key => $value) { ?>
									<div class="col-lg-4">
										<div class="form-group">
											<label><?php echo ($key=='equip') ? 'Equipment' : ucfirst($key); ?>:</label>
											<?php foreach($value as $sub_key => $sub_val) { 
												$id_str = $key.'_id';
												$title_str = $key.'_title'; ?>
												<div class="checkbox">
													<label>
														<input type="checkbox" value="<?php echo $sub_val[$id_str];?>" name="<?php echo $key; ?>[]"><?php echo ucfirst($sub_val[$title_str]);?>
													</label>
												</div>
											<?php } ?>
										</div>
									</div>
									<?php if($i%3==2) { ?>
										<div class="clearfix"></div>
									<?php }  
									$i++;
								}  
							} */?>
								
						</div>
						
					</form>
				</div>
							
							
							<?php /* if(isset($filter_details) && count($filter_details)>0) { 
								$i = 0;
								foreach($filter_details as $key => $value) { ?>
									<div class="col-lg-4">
										<div class="form-group">
											<label><?php echo ($key=='equip') ? 'Equipment' : ucfirst($key); ?>:</label>
											<?php foreach($value as $sub_key => $sub_val) { 
												$id_str = $key.'_id';
												$title_str = $key.'_title'; ?>
												<div class="checkbox">
													<label>
														<input type="checkbox" value="<?php echo $sub_val[$id_str];?>" name="<?php echo $key; ?>[]"><?php echo ucfirst($sub_val[$title_str]);?>
													</label>
												</div>
											<?php } ?>
										</div>
									</div>
									<?php if($i%3==2) { ?>
										<div class="clearfix"></div>
									<?php }  
									$i++;
								}  
							} */
							
							
							?>
								
						</div>
						</form>
						<?php if(isset($template_details) && count($template_details)>0) { ?>
					<div class="row gallery-div">
						<div class="topsearch topsearch_white">
							<div class="border" >
								<div class="col-sm-6 showentries">
									<label for="Show"><?php echo __('Shows'); ?> :</label>
									<form class="" name='show' id='show' method="get" action="<?php echo URL::base().'admin/exercise/sample/';?>">
									<?php if($default_status){ ?>
									<input type='hidden' name='d' id='d' value='<?php echo $default_status; ?>'>
									<?php } ?>
									<select id="lim" name="lim" class="sortby selectpicker selectAction" data-live-search="true" onchange="$('#show').submit();">
										<option value=''>Choose</option>
										<?php
										for($f=10; $f<=100; $f=$f+10){
												$select='';if( isset($lim) && $f==$lim){ $select='selected="selected"'; }
												echo "<option value='$f'  $select>$f</option>";
										}
										?>
									</select>
									entries
									</form>
								</div>
								<div class="col-sm-6"  >
									 
										<?php if(Helper_Common::hasAccess('Manage Exercise')) { ?>
											<select name="moteactions[]" id="moteactions" class="form-control selectAction right exerciseaction" >
												<option value="" selected="selected">Select an Option</option>
												<?php if(Helper_Common::hasAccess('Create Exercise')) { ?>
													<!--option value="createsample">Create New Exercise Record</option-->
												<?php } ?>
												<option value="duplicate"><?=__("Duplicate selected")?></option>
												<option value="delete"><?=__("Delete selected")?></option>
												<option value="tag"><?=__("Tag selected")?></option>
												<option value="share"><?=__("Share selected")?></option>
												<?php if(Helper_Common::is_admin()){ if($default_status==2){ ?>
														<option value="default"><?=__("Copy selected to Default")?></option>
												<?php } } ?>
												<option value="exportdefault"><?=__("Export this list")?></option>
												<option value="exportselecteddefault"><?=__("Export selected")?></option>
												<!---option value="reportEmail">Email</option>
												<option value="Excel">Excel</option>
												<option value="PDF">PDF</option-->
												
											</select>
											<?php } ?>
										
								</div>
							</div>
						</div>
					</div>
				<?php } ?>
				<div class="clearfix"></div>
				<div class="row">
				<div class="col-sm-12" >
					<?php if(isset($template_details) && count($template_details)>0) { ?>
						<div class="table-responsive">
						  	<table class="table table-bordered table-hover table-striped " id="exercise-table" >
								<thead>
									<tr>
										<th class="chkbox-header"><input type="checkbox"  name="row_index[]" id="select-all" /></th>
										<th><?php echo __('Preview'); ?></th>
										<th><?php echo __('Title'); ?></th>
										<th><?php echo __('Status'); ?></th>
										<th><?php echo __('Type'); ?></th>
										<th><?php echo __('Primary'); ?></th>
										<th><?php echo __('Equipment'); ?></th>
										<th><?php echo __('Tags'); ?></th>
										<?php if(Helper_Common::hasAccess('Manage Exercise')) { ?>
											<th><?php echo __('Actions'); ?></th>
										<?php } ?>
									</tr>
								</thead>
								<tbody id="table-content-contnr">
								<?php  //echo count($template_details);
										 //echo '<pre>';print_r($template_details);echo '</pre>';
								foreach($template_details as $key => $value) { ?>
								<tr id="row-<?php echo $value['id'];?>">
									<td class="tabl-chkbox checkselect"><input type="checkbox" name="row_index[]" class="chkbox-item exe_select" value="<?php echo $value['id'];?>" /></td>
									<td class="click-prev">
									<img src="<?php echo $value['featimg'];?>" width="50"/></td>
									<td class="ex_name"><?php echo $value['name']; ?></td>
												  <td><?php echo substr($value['status'], 0, 3);?></td>
									<td><?php echo $value['type']; ?></td>
									<td><?php echo $value['muscle']; ?></td>
									<td><?php echo $value['equip']; ?></td>
									<td class="tagsection"><?php  if(isset($value['tagdetails']) && !empty($value['tagdetails'])){$tags = explode('@@',$value['tagdetails']);echo implode(", ", $tags);}?></td>
									<?php if(Helper_Common::hasAccess('Manage Exercise')) { ?>
										<td>
											<select id="<?php echo $value['id'];?>" name="exerciseaction" class="exeselect selectAction ex-single-action form-control" >
												<option value="" selected="selected">Select an Option</option>
												<?php if($value['default_status'] == '1' && (Helper_Common::is_manager() || Helper_Common::is_trainer())){ ?>
													<option value="sampledefaulthide"><?=__("Hide")?></option>
												<?php } ?>
												<?php if($value['default_status'] == '0' || Helper_Common::is_admin()) { ?>
												<option value="edit"><?=__("Edit this record")?></option>
												<?php } ?>
												<option value="view"><?=__("View this record")?></option>
												<option value="duplicate"><?=__("Duplicate this record")?></option>
												<?php if($value['user_id'] == Auth::instance()->get_user()->pk()) { ?>
												<option value="delete"><?=__("Delete this record")?></option>
												<?php } ?>
												<option value="view_related"><?=__("View related exercises")?></option>
												<option value="tag"><?=__("Tag this record")?></option>
												<?php if(Helper_Common::is_admin() || Helper_Common::is_manager()  || Helper_Common::is_trainer()){ ?>
													<option value="share"><?=__("Share this record")?></option>
												<?php } if(Helper_Common::is_admin()){
													if($value['default_status']==2){ ?>
														<option value="default"><?=__("Copy to Default")?></option>
													<?php } 
												} ?>
												<!--option value="rate"><?=__("Rate this exercise")?></option-->
												<!--option value="share" disabled="disabled"><?=__("Share this record")?></option-->
												<option value="feedback" disabled ><?=__("Feedback for this record")?></option>
												<!--option value="more">"More"</option>-->
											</select>												
										</td>
									 <?php } ?>
									<!--
									<td>
										<a href="javascript:void(0);"><i class="fa fa-edit"></i></a>
									</td>
									  <td><a  href="javascript:void(0);"><i class="fa fa-remove"></i></a></td> --> <!-- onclick="deleteRecord('<?php //echo $value['id'];?>')" -->
								</tr>
								<?php } ?>
								</tbody>
							</table>
							<div class="exercise_tbl_pg" > <?php echo $pagination; ?> </div>
						</div>
					<?php } else { echo '<div class="table-responsive col-lg-12">'.__('No Records Found').'...</div>'; $no=1; }?>
				</div>
				<!-- /.row -->
				</div>
				</div>
			</div>
			<!-- /.container-fluid -->
		  </div>
		  <!-- /#page-wrapper -->
	 </div>
	 <!-- /#wrapper -->
<?php if(!isset($no)){  /* ?>
	<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal" id="deleteModalBtn">Delete </button>
<?php
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
	 <div class="modal-dialog">

		  <!-- Modal content-->
		  <div class="modal-content">
				<div class="modal-body">
					 <button type="button" class="close" data-dismiss="modal">&times;</button>
					 <p>Are you sure yow want to delete?</p>
					 <input type="hidden" name="user_idjs" id="user_idjs" value="0">
					 <button type="button" class="btn btn-danger" id="yesDelete" onclick="deleteUser()">Yes</button>
					 <button type="button" class="btn btn-primary" id="noDelete" data-dismiss="modal">No</button>
				</div>
		  </div>

	 </div>
</div>
*/
 } ?>
 
<!-- Export -->
<div class="modal fade" id="exportModal" tabindex="-1" role="dialog" aria-labelledby="exportModal">
	<div class="vertical-alignment-helper">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<div class="row">
					<div class="popup-title">
						<div class="col-xs-2">
							<a class="triangle" onclick="$('#exportModal').modal('hide');" href="javascript:void(0);">
									<i class="fa fa-chevron-left"></i>
							</a>
						</div>
						<div class="col-xs-8 ratetitle"><?=__("Options for this Default Exercise Export")?></div>
						<div class="col-xs-2 save-icon-button bluecol"></div>
					</div>
					</div>
				</div>
				<div class="modal-body ratebody">
				<input type='hidden' id='selected_exe'>	
			<div class="row opt-row-detail">
				<div class="col-xs-12">
					
					<a onclick="$('#exportModal').modal('hide');listexport('excel',<?php echo ($default_status==2 || $default_status=='all') ? 2 : 1; ?>);" class="btn btn-default"	style="width:100%" href="javascript:void(0);">
						<div class="col-xs-2"><i class="fa fa-file-excel-o iconsize"></i></div>
						<div class="col-xs-8"><?php echo __('By Excel'); ?></div>
					</a>
				</div>
			</div>
			<div class="row opt-row-detail">
				<div class="col-xs-12">
					<a class="btn btn-default" style="width:100%" data-flagtype="bypdf" onclick="$('#exportModal').modal('hide');listexport('pdf',<?php echo ($default_status==2 || $default_status=='all') ? 2 : 1; ?>)" href="javascript:void(0)" >
						<div class="col-xs-2"><i class="fa fa-file-pdf-o iconsize"></i></div>
						<div class="col-xs-8"><?php echo __('By PDF'); ?></div>
					</a>
				</div>
			</div>
			<div class="row opt-row-detail">
				<div class="col-xs-12">
					<a class="btn btn-default" style="width:100%" onclick="$('#EmailModal input#exe').val($('input#selected_exe').val());$('#exportModal').modal('hide');$('#EmailModal').modal('show');"  href="javascript:void(0)">
						<div class="col-xs-2"><i class="fa fa-envelope-o iconsize"></i></div>
						<div class="col-xs-8"><?php echo __('By Mail'); ?></div>
					</a>
				</div>
			</div>
			
					
				</div>
				<!--div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('close'); ?></button>
				</div-->
			</div>
		</div>
	</div>
</div>
<!-- Export -->
<!-- Tag Modal -->
<div id="tagmodal" class="modal fade" tabindex="-1" role="dialog">
	<div class="vertical-alignment-helper">
		<div class="modal-dialog">
		  <div class="modal-content">
			 <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"><?php echo __('Tag Exercise'); ?>:<span id='wkout_modal_title'></span></h4>
			 </div>
			 <div class="modal-body">            
				<div class="form-group">
				  	<label for="recipient-name" class="control-label"><?php echo __('Choose Tags'); ?>:</label>
				  	<input type="hidden" class="form-control tagnames" name="tagnames" placeholder="<?php echo __('Choose Tags'); ?>" value="" id="tagnames" style="width:100%" />
				</div>					
			 </div>
			 <div class="modal-footer"><input type="hidden" name="cwkid" id="cwkid">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('close'); ?></button>
				<button type="button" class="btn btn-primary addextags"><?php echo __('Save changes'); ?></button>
			 </div>
		  </div>
		</div>
 	</div>
</div>
<!-- view exercise rec data modal -->
<div id="xrunitdata-modal" class="modal fade" role="dialog">
	<div class="vertical-alignment-helper">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header aligncenter">
					<div class="row">
						<div class="popup-title">
							<div class="col-xs-2">
								<a href="javascript:void(0);" title="Back" data-dismiss="modal" class="triangle">
									<i class="fa fa-caret-left iconsize"></i>
								</a>
							</div>
							<div class="col-xs-8" id="unitdata-title"> </div>
							<div class="col-xs-2 save-icon-button bluecol">
								<!-- <i class="fa fa-ellipsis-h iconsize" data-toggle="modal" data-target="#xrciselibact-modal"></i> -->
							</div>
						</div>
					</div>
				</div>
				<div class="modal-body">
					<div id="xrRecordData" class="xrRecordData"></div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('close'); ?></button>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- exercise rec preview modal -->
<div id="xrciseprev-modal" class="modal fade" role="dialog"></div>
<div id="myOptionsModalExerciseRecord" class="modal fade bs-example-modal-sm" role="dialog" data-keyboard="false" data-backdrop="static"></div>

<!-- Email Report Modal Start-->
<div class="modal fade" id="EmailModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="vertical-alignment-helper">
	  	<div class="modal-dialog" role="document">
			<div class="modal-content">
		  		<form name="email_rep" id="email_report_frm" onsubmit="return exercise_email()">
					<div class="modal-header">
					  	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					  	<h4 class="modal-title" id="myModalLabel"><?php echo __('Enter Email to get report'); ?></h4>
					</div>
					<div class="modal-body">
				  		<div class="response" style="font-size:15px;"></div>
						<label><?php echo __('Email Address'); ?>:</label>
						<input type="email" required name="email_address" id="email_address" value="" class="form-control"/>
						<input type="hidden" name="roleid" id="roleid" value="<?php //echo $roleid;?>"/>
						<input type="hidden" name="d_status" id="d_status" value="<?php echo ($default_status==2 || $default_status=='all') ? 2 : 1; ?>"/>
						<input type="hidden" name="exe" id="exe" value="" />
					</div>
					<div class="modal-footer">
					  	<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('close'); ?></button>
					  	<input class="btn btn-primary" type="submit" name="email_report" value="Email Report" />
					</div>
			  	</form>
			</div>
		</div>
	</div>
</div>

<!-- exercise rec preview modal -->
<div id="xrciseprev-modal" class="modal fade" role="dialog"></div>
<div id="myOptionsModalExerciseRecord" class="modal fade bs-example-modal-sm" role="dialog" data-keyboard="false" data-backdrop="static"></div>	

<input id="default_status" type="hidden" value="" name="default_status">
<input type="hidden" name="role" id="role" value="<?php echo (Helper_Common::hasAccessByDefaultXr($session->get('current_site_id')) ? '1' : '0');?>">
<input type='hidden' name='xrdefault' id='xrdefault' value='<?php echo $default_status; ?>'>
<!-- Set as default -->
<div class="modal fade" id="setexedefaultModal" tabindex="-1" role="dialog" aria-labelledby="setexedefaultModal">
	<div class="vertical-alignment-helper">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<div class="row">
					<div class="popup-title">
					<div class="col-xs-3">
					<a class="triangle" data-dismiss="modal" href="javascript:void(0);" data-ajax="false" data-role="none">
					<i class="fa fa-caret-left iconsize"></i>
					</a>
					</div>
					<div class="col-xs-6"><?php echo __('Set Default Exercise Record'); ?></div>
					<div class="col-xs-3 save-icon-button bluecol">
					<button class="btn" style="background-color:#fff" name="f_method" onclick="set_default_submit();" data-ajax="false" type="button" data-role="none">
					<i class="fa fa-check-square-o" data-toggle="collapse" style="font-size:30px;"></i>
					</button>
					</div>
					</div>
					</div>
				</div>
				<div class="modal-body">
					<form>
						<div class="aligncenter"><span class="errormsg"></span></div>
						<div class="form-group">
							<label for="workout-name" class="control-label"><?php echo __('Select Exercise Records'); ?>:</label>           
							<select id='de_exe_id' class="de_exe_id form-control fullwidth select2-hidden-accessible" style="width: 100%;" multiple style="width:350px;" tabindex="4">
								<option value=""></option>
								<?php
								foreach($template_details_all as $key => $value) {
									echo "<option value='".$value["id"]."' >".$value["name"]."</option>";
								}
								?>
							</select>
						</div>
						<!--div class="form-group">
							<label for="workout-name" class="control-label">Select Sites:</label>
							<?php
							$fetch_field  = "id, name";
							$fetch_condtn = "is_active = 1 and is_deleted=0";
							$listsites = Model::instance('Model/admin/user')->get_table_details_by_condtn('sites',$fetch_field,$fetch_condtn);
							?>
							<select id='site_id' class="site_id form-control fullwidth select2-hidden-accessible" style="width: 100%;" multiple style="width:350px;" tabindex="4">
								<option value=""></option>
								<?php
								if($listsites){
									foreach($listsites as $k=>$v){
										echo "<option value='".$v["id"]."'>".$v["name"]."</option>";
									}
								}
								?>
							</select>
						</div-->
					</form>
				</div>
				<!--div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('close'); ?></button>
				</div-->
			</div>
		</div>
	</div>
</div>
<!-- Set As Default -->


<!-- Rating -->
<div class="modal fade" id="rateModal" tabindex="-1" role="dialog" aria-labelledby="rateModal">
	<div class="vertical-alignment-helper">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<div class="row">
					<div class="popup-title">
						<div class="col-xs-3">
							<a class="triangle" data-dismiss="modal" href="javascript:void(0);" data-ajax="false" data-role="none"><i class="fa fa-caret-left iconsize"></i></a>
						</div>
						<div class="col-xs-6 ratetitle"><?php echo __('Rate for Exercise Record'); ?></div>
						<div class="col-xs-3 save-icon-button bluecol"></div>
					</div>
					</div>
				</div>
				<div class="modal-body ratebody">
					
					<div class="row listrate">
						<div class="col-xs-12">
							<?php echo __('Lorem Ipsum is simply dummy text of the printing and typesetting industry'); ?>.
						</div>
						<div class="col-sm-3 alignleft">
							<?php echo __('Rated'); ?> : 2
						</div>
						<div class="col-sm-6 alignright">
							by Raju boy on <span class='rateddate'>17 Mar 2016</span>
						</div>
						<div class="col-sm-3">
							<button class="btn rate_approvebtn"  onclick="" data-ajax="false" type="button" data-role="none">
							<i class="fa fa-check-square-o" data-toggle="collapse" ></i>
							</button>
						</div>
					</div>
					
					
				</div>
				<!--div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('close'); ?></button>
				</div-->
			</div>
		</div>
	</div>
</div>
<!-- Rating -->


 <!-- more Modal  -->
<div class="modal fade" id="moreModal" tabindex="-1" role="dialog" aria-labelledby="shareModalLabel">
	<div class="vertical-alignment-helper">
	  	<div class="modal-dialog" role="document">
		 <div class="modal-content">
			<div class="modal-header">
			  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			  <h4 class="modal-title" id="exampleModalLabel"><?php echo __('Status'); ?></h4>
			  <?php
			  
			  ?>
			</div>
			<div class="modal-body">
			  <form>
				  <div class="form-group">
					<label for="workout-name" class="control-label"><?php echo __('Status'); ?>: </label>           
					<!--input  type='text' class="form-control wkout_id" id="wkout_id" name= 'wkout_id'-->
					<select id='unit_status' class="unit_status form-control select2-hidden-accessible" style="width: 100%;"  style="width:350px;" tabindex="4">
						
						<?php 
						if(isset($status)) {
							foreach($status as $key => $value) {
								?>
								<option value="<?php echo $value['id'];?>"><?php echo $value['status_title'];?></option>
								<?php
							}
						} ?>
					</select>
				 </div>
				 <div class="form-group">
					<label for="recipient-name" class="control-label"><?php echo __('Featured'); ?>:</label>
					<select id='featured' class="featured form-control fullwidth select2-hidden-accessible" style="width: 100%;"  style="width:350px;" tabindex="4">			
						<option value="0">No</option>
						<option value="1">Yes</option>
						
					</select>
					<input type='hidden' class="unitid" id='unitid' name="unitid" value='' >
					<!--
					<input type='hidden' class="wkfilter_val" id='wkfilter_val' name="wkoutid" value='<?php if(isset($wkoutfilter)){ foreach($wkoutfilter as $keys=> $values) { if ($values ==1){echo '1';}}} ?>' > -->
					
					
				 </div>
			  </form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('close'); ?></button>
			  <button type="button" class="btn btn-primary" onclick='updatestatus()'><?php echo __('Save'); ?></button>
			</div>
		 </div>
	  </div>
  	</div>
</div>

<div id="xr-duplicateModal" class="modal fade" tabindex="-1" role="dialog">
	<div class="vertical-alignment-helper">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title"><?php echo __('Choose Duplicate Option'); ?></h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<div class="col-xs-12 navimagedetails">
							<div class="navimgdet1">
								<label for="exrtype1"><input type='radio' id="exrtype1" name='exercise_type' checked='checked' value=""> <?php echo __('Duplicate into My Exercises'); ?></label>
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="col-xs-12 navimagedetails">
							<div class="navimgdet1">
								<label for="exrtype2"><input type='radio' id="exrtype2" name='exercise_type' value="sample"> <?php echo __('Duplicate into Sample Exercises'); ?></label>
							</div>
						</div>
					</div>
					<?php if(Helper_Common::is_admin()){ ?>
					<div class="form-group">
						<div class="col-xs-12 navimagedetails">
							<div class="navimgdet1">
								<label for="exrtype3"><input type='radio' id="exrtype3" name='exercise_type' value="default"> <?php echo __('Duplicate into Default Exercises'); ?></label>
							</div>
						</div>
					</div>
					<?php } ?>
				</div>
				<div class="modal-footer"><input type="hidden" name="cwkid" id="cwkid">
					<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Close'); ?></button>
					<button type="button" class="btn btn-primary duplicate-xrrec"><?php echo __('Duplicate'); ?></button>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- share exercise modal -->
<div id="sharexrcise-modal" class="modal fade" role="dialog" tabindex="-1"></div>

<?php /***Recipients Filter Model***/ ?>
<div class="modal fade" id="recipientsfilterModal" tabindex="-1" role="dialog">
	<div class="vertical-alignment-helper">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<div class="row">
						<div class="mobpadding">
							<div class="border">
								<div class="col-xs-2">
									<a data-role="none" data-ajax="false" href="javascript:void(0);" data-dismiss="modal" class="triangle">
										<i class="fa fa-chevron-left iconsize2"></i>
									</a>
								</div>
								<div class="col-xs-8 optionpoptitle">Select Recipients</div>
								<div class="col-xs-2"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-body">
					<form>
						<div class="form-group">
							<label for="workout-name" class="control-label">Filter by Recipient Name:</label>           
							<select id='subscribername' class="subscribername form-control fullwidth select2-hidden-accessible" style="width: 100%;" multiple style="width:350px;" tabindex="4">
								<option value=""></option>
								<?php
								if(isset($subscriber_details) && is_array($subscriber_details) && count($subscriber_details)>0) {
									foreach($subscriber_details as $key => $value) {
										?>
										<option value="<?php echo $value['id'];?>"><?php echo $value['user_fname'].' '.$value['user_lname']; ?></option>
										<?php
									}
								}?>
							</select>
						</div>
						<div class="form-group">
							<label for="recipient-name" class="control-label">Filter by existing Recipient Tags:</label>
							<select id='tag_id' class="tag_id form-control fullwidth select2-hidden-accessible" style="width: 100%;" multiple style="width:350px;" tabindex="4" >
								<option value=""></option>
								<?php
								if(isset($usertags) && count($usertags)>0) {
									foreach($usertags as $key => $value) {
										?>
										<option value="<?php echo $value['tag_id'];?>"><?php echo $value['tag_title']; ?></option>
										<?php
									}
								}?>
							</select>
						</div>
						<div class="form-group">
							<label for="recipient-name" class="control-label">Filter by Gender:</label>
							<select id='gender' class="gender form-control fullwidth select2-hidden-accessible" style="width: 100%;" multiple style="width:350px;" tabindex="4">
								<option value="">All</option>
								<option value="1">Male</option>
								<option value="2">Female</option>
							</select>
						</div>
						<div class="form-group">
						<label for="recipient-name" class="control-label">Filter by Age (between):<span id='setage'>15 - 122</span></label>
							<input type='hidden' id='setagerange' name='setagerange'>
							<div id="agerange"></div>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary" onclick='insertfilterrecipients()'>Insert</button>
				</div>
			</div>
		</div>
	</div>
</div>
</body>