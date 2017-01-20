	<style type="text/css">
	.ocpLegacyBold {font-weight: 400;font-size: 16px;color: #337ab7;}
	.ocpArticleContent p, .ocpArticleContent span {color: #363636;font-size: 14px;line-height: 1.286em;padding: 0;}
	.ocpArticleContent .panel { margin-bottom: 0px; background-color: #CCCCCC; }
	.ocpArticleContent .panel .ocpArticleContent .panel{background-color: #e9e9e9;}
	.toggletitle { padding: 10px; font-size: 15px; } 
	.toggletitle:hover { cursor: pointer; }
	.select2-container{padding:0; width:100%;}
	.table-responsive {border-bottom: 1px solid #ccc;padding: 5px 0;}
	.table-responsive .table-responsive{padding:0;}
	.table-responsive .select2-container .select2-choice{border-radius:0;}
	.banded{ max-width: 600px; width: 100%; }
	.fa.fa-pencil { float: right; font-size: 20px; font-style: normal; font-weight: normal; }
	.fa.fa-pencil:hover {color:#23527c;}
	.innersetting{padding: 12px 0 5px;display: inline-block;position: relative;border-bottom: 1px solid #ccc;}
	.innersetting.last{border:none;}
	.innersetting .col-lg-6{padding:0px;display: inline-block; vertical-align: middle; line-height: 35px;}
	
	.accordsetting {     border: 1px solid #ddd; border-radius: 0; clear: both; height: 50px; width: 100%;}
	.accordsetting .col1 { padding: 15px; }
	.accordsetting .col3 { padding: 13px; }
	.accordsetting .col2 { padding-top: 5px; }
	.accord i.fa { font-size: 20px; color: rgb(0, 102, 255); cursor: pointer; width: 100%; padding-bottom: 2px; }
	#manageintegrationsaction { background: #fff none repeat scroll 0 0; }
	.topbottom { padding: 15px 0px;  }
	.accordswitch .bootstrap-switch { border-radius: 3px; height: 21px; }
	.accordswitch .bootstrap-switch .bootstrap-switch-container > span { border-radius: 0px; }
	.accordswitch .bootstrap-switch .bootstrap-switch-container > span.bootstrap-switch-handle-off { color: #fff; }
	</style>
    <!--- Top nav && left nav--->
	<?php echo $topnav.$leftnav; ?>
	<!--- Top nav && left nav --->
      <!-- Content Wrapper. Contains page content -->
      <div id="page-wrapper">
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                        <?php $session = Session::instance();
							if($session->get('current_site_name')!=''){echo '"'.$session->get('current_site_name').'"';}?> <?php echo __('Preference Defaults'); ?>
                        </h1>
                        <ol class="breadcrumb">
                            <li>
                                <i class="fa fa-dashboard"></i>  <a href="<?php echo URL::base().'admin/index'; ?>"><?php echo __('Dashboard'); ?></a>
                            </li>
                            <li class="active">
                                <i class="fa fa-edit"></i> <?php echo __('Preference Defaults Settings'); ?>
                            </li>
                        </ol>
                    </div>
                </div><!-- /.row -->
				<div class="row" id="mes_suc" style="display:none;">
					<div class="col-lg-12">
						<div class="alert alert-success">
						  <i class="fa fa-check"></i><span></span>
						</div>
					</div>
				</div>
                <div class="row">				
					<div class="col-lg-12">
					<div class="banded">
							<h2 class="commonheading"><?php echo __('Preference Defaults Settings'); ?></h2>
							<div class="table-responsive col-lg-12 ocpArticleContent">
								<div class="panel">
									<div data-toggle="collapse" data-target="#managetimezoneaction" class="toggletitle"><?php echo __('Timezone'); ?><i class="fa fa-pencil"></i></div>
									<div  id="managetimezoneaction" class="collapse">
									    <div class="col-lg-12 innersetting">
											<div class="col-lg-6"><?php echo __('Country'); ?></div>
											<div class="col-lg-6">
												<select name="right manageraction[]" id="countryaction" class="selectAction col-lg-12">
													<option value="">Country</option>
													<?php foreach($countrylist as $c) {?>
													<option value="<?php echo $c['id']?>" <?php if(isset($setting_contry)){if($setting_contry == $c['id']){ echo 'selected';}}?>><?php echo $c['countryname']?></option>
													<?php }?>
										        </select>
											</div>
										</div>
									    <div class="col-lg-12 innersetting">
											<div class="col-lg-6"><?php echo __('Timezone'); ?></div>
											<div class="col-lg-6">
												<select name="right manageraction[]" id="timezoneaction" class="selectAction col-lg-12">
													<option value="">Timezone</option>
													<?php if(!empty($timezonelist)) {
														foreach($timezonelist as $t) { ?>
															<option value="<?php echo  $t;?>" <?php if(isset($setting_zone)){if($setting_zone == $t){ echo 'selected';}}?>><?php echo  $t;?></option>
													    <?php } 
													}?>
										        </select>
											</div>
										</div>
										<div class="col-lg-12 innersetting">
											<div class="col-lg-6"><?php echo __('Local Time'); ?></div>
											<div class="col-lg-6"><p class="LocalTime"><?php if(isset($Preference_Defaults[0]['time_format']) && $Preference_Defaults[0]['time_format'] != ''){echo date($Preference_Defaults[0]['time_format'], time());}else {echo date('h:i:s A', time());}?></p></div>
										</div>
										<div class="col-lg-12 innersetting">
											<div class="col-lg-6">
												<button class="btn btn-primary" id="timezonesubmit" name="submit" type="submit"><?php echo __('Save'); ?></button>
												&nbsp;&nbsp;
												<button class="btn btn-default" id="timezonecancel" name="cancel" type="submit"><?php echo __('Cancel'); ?></button>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="table-responsive col-lg-12 ocpArticleContent hide">
								<div class="panel">
									<div data-toggle="collapse" data-target="#managetimeformataction" class="toggletitle"><?php echo __('Time Format'); ?><i class="fa fa-pencil"></i></div>
									<div  id="managetimeformataction" class="collapse">
										<div class="col-lg-12 innersetting">
											<div class="col-lg-6"><?php echo __('Time Format'); ?></div>
											<div class="col-lg-6">
												<select name="right manageraction[]" id="timeformataction" class="selectAction col-lg-12">
												   <option value="" >Time Format</option>
													<option value="h:i:s A" <?php if(isset($Preference_Defaults[0]['time_format'])){if($Preference_Defaults[0]['time_format'] == "h:i:s A"){ echo 'selected';}}?>>h:i:s A</option>
													<option value="h:i:s a" <?php if(isset($Preference_Defaults[0]['time_format'])){if($Preference_Defaults[0]['time_format'] == "h:i:s a"){ echo 'selected';}}?>>h:i:s a</option> 										
												</select>
											</div>
										</div>
										<div class="col-lg-12 innersetting">
											<div class="col-lg-6"><?php echo __('Local Time'); ?></div>
											<div class="col-lg-6"><p class="LocalTime"><?php if(isset($Preference_Defaults[0]['time_format']) && $Preference_Defaults[0]['time_format'] != ''){echo date($Preference_Defaults[0]['time_format'], time());}else {echo date('h:i:s A', time());}?></p></div>
										</div>
										<div class="col-lg-12 innersetting">
											<div class="col-lg-6">
												<button class="btn btn-primary" id="timeformatsubmit" name="submit" type="submit"><?php echo __('Save'); ?></button>
												&nbsp;&nbsp;
												<button class="btn btn-default" id="timeformatcancel" name="cancel" type="submit"><?php echo __('Cancel'); ?></button>
											</div>
										</div>
								    </div>
								</div>
							</div>
							<div class="table-responsive col-lg-12 ocpArticleContent">
								<div class="panel">
									<div data-toggle="collapse" data-target="#managelanguageaction" class="toggletitle"><?php echo __('Language'); ?><i class="fa fa-pencil"></i></div>
									<div  id="managelanguageaction" class="collapse">
										<select name="right manageraction[]" id="languageaction" class="selectAction col-lg-12">
											<?php 
											$currentlang = isset($Preference_Defaults[0]['language']) ? $Preference_Defaults[0]['language'] : 1;
											foreach($languagelist as $language){ ?>
                                   	<option value="<?php echo $language['language_id'];?>" <?php if($currentlang == $language['language_id']){ echo 'selected';}?>><?php echo $language['name'];?></option>
                                	<?php } ?>
										</select>
									</div>
								</div>
							</div>
							<div class="table-responsive col-lg-12 ocpArticleContent">
								<div class="panel">
									<div data-toggle="collapse" data-target="#managedateformataction" class="toggletitle"><?php echo __('Date Format'); ?><i class="fa fa-pencil"></i></div>
									<div  id="managedateformataction" class="collapse">
										<div class="col-lg-12 innersetting">
											<div class="col-lg-6"><?php echo __('Date Format'); ?></div>
											<div class="col-lg-6">
												<select name="right manageraction[]" id="dateformataction" class="selectAction col-lg-12">
												    <option value="" >Date Format</option>
													<option value="Y-M-d" <?php if(isset($Preference_Defaults[0]['date_format'])){if($Preference_Defaults[0]['date_format'] == "Y-M-d"){ echo 'selected';}}?>>yyyy - MMM - dd</option>
													<option value="Y-m-d" <?php if(isset($Preference_Defaults[0]['date_format'])){if($Preference_Defaults[0]['date_format'] == "y-m-d"){ echo 'selected';}}?>>yyyy - mm - dd</option>
			                                        <option value="Y/M/d" <?php if(isset($Preference_Defaults[0]['date_format'])){if($Preference_Defaults[0]['date_format'] == "Y/M/d"){ echo 'selected';}}?>>yyyy / MMM / dd</option>
													<option value="Y/m/d" <?php if(isset($Preference_Defaults[0]['date_format'])){if($Preference_Defaults[0]['date_format'] == "y/m/d"){ echo 'selected';}}?>>yyyy / mm / dd</option>
													<option value="Y.M.d" <?php if(isset($Preference_Defaults[0]['date_format'])){if($Preference_Defaults[0]['date_format'] == "Y.M.d"){ echo 'selected';}}?>>yyyy . MMM . dd</option>
													<option value="Y.m.d" <?php if(isset($Preference_Defaults[0]['date_format'])){if($Preference_Defaults[0]['date_format'] == "y.m.d"){ echo 'selected';}}?>>yyyy . mm . dd</option>
												</select>
											</div>
										</div>
										<div class="col-lg-12 innersetting">
											<div class="col-lg-6"><?php echo __('Current Date Format'); ?></div>
											<div class="col-lg-6"><p class="Localdate"><?php if(isset($Preference_Defaults[0]['date_format']) && $Preference_Defaults[0]['date_format'] != ''){echo date($Preference_Defaults[0]['date_format']);}else{echo date('y-m-d');}?></p></div>
										</div>
										<div class="col-lg-12 innersetting">
											<div class="col-lg-6">
												<button class="btn btn-primary" id="dateformatsubmit" name="submit" type="submit"><?php echo __('Save'); ?></button>
												&nbsp;&nbsp;
												<button class="btn btn-default" id="dateformatcancel" name="cancel" type="submit"><?php echo __('Cancel'); ?></button>
											</div>
										</div>
								    </div>
								</div>
							</div>
							<div class="table-responsive col-lg-12 ocpArticleContent">
							    <div class="panel">
									<div data-toggle="collapse" data-target="#manageweekaction" class="toggletitle"><?php echo __('Week (Starts On)'); ?><i class="fa fa-pencil"></i></div>
									<div  id="manageweekaction" class="collapse">
										<select name="right manageraction[]" id="weekaction" class="selectAction col-lg-12">
											<option value="1" <?php if(isset($Preference_Defaults[0]['week_sarts_on'])){if($Preference_Defaults[0]['week_sarts_on'] == 1){ echo 'selected';}}?>>Monday</option>
											<option value="2" <?php if(isset($Preference_Defaults[0]['week_sarts_on'])){if($Preference_Defaults[0]['week_sarts_on'] == 2){ echo 'selected';}}?>>Tuesday</option>
											<option value="3" <?php if(isset($Preference_Defaults[0]['week_sarts_on'])){if($Preference_Defaults[0]['week_sarts_on'] == 3){ echo 'selected';}}?>>Wednesday</option>
											<option value="4" <?php if(isset($Preference_Defaults[0]['week_sarts_on'])){if($Preference_Defaults[0]['week_sarts_on'] == 4){ echo 'selected';}}?>>Thursday</option>
											<option value="5" <?php if(isset($Preference_Defaults[0]['week_sarts_on'])){if($Preference_Defaults[0]['week_sarts_on'] == 5){ echo 'selected';}}?>>Friday</option>
											<option value="6" <?php if(isset($Preference_Defaults[0]['week_sarts_on'])){if($Preference_Defaults[0]['week_sarts_on'] == 6){ echo 'selected';}}?>>Saturday</option>
											<option value="7" <?php if(isset($Preference_Defaults[0]['week_sarts_on'])){if($Preference_Defaults[0]['week_sarts_on'] == 7){ echo 'selected';}}?>>Sunday</option>
										</select>
								    </div>
								</div>
							</div>
							<div class="table-responsive col-lg-12 ocpArticleContent">
							    <div class="panel">
									<div data-toggle="collapse" data-target="#managemeasurementsaction" class="toggletitle"><?php echo __('Measurements'); ?><i class="fa fa-pencil"></i></div>
									<div  id="managemeasurementsaction" class="collapse">
										<div class="col-lg-12 innersetting">
											<div class="col-lg-6"><?php echo __('Weight'); ?>:</div>
											<div class="col-lg-6">
												<select name="right manageraction[]" id="weightaction" class="selectAction col-lg-12">
												    <option value="" >Select Your Option</option>
													<?php if( !empty($measurements_weight)){
																foreach($measurements_weight as $keys=>$values){?>
															<option value="<?php echo $values['resist_id'] ?>" <?php if(isset($Preference_Defaults[0]['Weight'])){if($Preference_Defaults[0]['Weight'] == $values['resist_id']){ echo 'selected';}}?>><?php echo $values['resist_title'] ?></option>
															
													<?php } }?>		
													
												</select>
											</div>
										</div>
										<div class="col-lg-12 innersetting">
											<div class="col-lg-6"><?php echo __('Distance'); ?>:</div>
											<div class="col-lg-6">
												<select name="right manageraction[]" id="distanceaction" class="selectAction col-lg-12">
												   <option value="" >Select Your Option</option>
												   <?php if( !empty($measurements_distance)){
																foreach($measurements_distance as $keys=>$values){?>
															<option value="<?php echo $values['dist_id'] ?>" <?php if(isset($Preference_Defaults[0]['Distance'])){if($Preference_Defaults[0]['Distance'] == $values['dist_id']){ echo 'selected';}}?>><?php echo $values['dist_title'] ?></option>	
													<?php } }?>					
													
												</select>
											</div>
										</div>
										<div class="col-lg-12 innersetting">
											<div class="col-lg-6">
												<button class="btn btn-primary" id="measurementsubmit" name="submit" type="submit"><?php echo __('Save'); ?></button>
												&nbsp;&nbsp;
												<button class="btn btn-default" id="measurementcancel" name="cancel" type="submit"><?php echo __('Cancel'); ?></button>
											</div>
										</div>
								    </div>
								</div>
							</div>
							<!--<div class="table-responsive col-lg-12 ocpArticleContent">
							    <div class="panel">
									<div data-toggle="collapse" data-target="#managenotificationsaction" class="toggletitle">Notifications<i class="fa fa-pencil"></i></div>
									<div  id="managenotificationsaction" class="collapse">
										<select name="right manageraction[]" id="notificationsaction" class="selectAction col-lg-12">
											<option value="">Notifications</option>
											<option value="1" <?php if(isset($Preference_Defaults[0]['notifications'])){if($Preference_Defaults[0]['notifications'] == 1){ echo 'selected';}}?>>Yes</option>
											<option value="2" <?php if(isset($Preference_Defaults[0]['notifications'])){if($Preference_Defaults[0]['notifications'] == 2){ echo 'selected';}}?>>No</option> 											
										</select>
								     </div>
								</div>
							</div>-->
							<div class="table-responsive col-lg-12 ocpArticleContent">
							    <div class="panel">
									<div data-toggle="collapse" data-target="#manageemailfrequency" class="toggletitle"><?php echo __('Email frequency'); ?><i class="fa fa-pencil"></i></div>
									<div  id="manageemailfrequency" class="collapse">
										<div class="table-responsive col-lg-12 ocpArticleContent">
											<div class="panel">
												<div data-toggle="collapse" data-target="#manageemailfrequency1" class="toggletitle"><?php echo __('Updates and news'); ?><i class="fa fa-pencil"></i></div>
												<div  id="manageemailfrequency1" class="collapse">
													<div class="col-lg-12 innersetting">
														<div class="col-lg-6"><?php echo __('Network updates'); ?></div>
														<div class="col-lg-6">
															<select name="right manageraction[]" id="Networksaction" class="selectAction col-lg-12">
																<option value="1" <?php if(isset($Preference_Defaults[0]['Network_updates'])){if($Preference_Defaults[0]['Network_updates'] == 1){ echo 'selected';}}?>>Weekly Digest</option>
																<option value="2" <?php if(isset($Preference_Defaults[0]['Network_updates'])){if($Preference_Defaults[0]['Network_updates'] == 2){ echo 'selected';}}?>>No Email</option>
															</select>
														</div>
													</div>
													<div class="col-lg-12 innersetting">
														<div class="col-lg-6"><?php echo __('Assignment reminder'); ?></div>
														<div class="col-lg-6">
															<select name="right manageraction[]" id="Assignmentreminder" class="selectAction col-lg-12">
																<!-- <?php for($i=0;$i<=2;$i++){?>
																	<option value="<?php echo $i; ?>"<?php if(isset($Preference_Defaults[0]['Assignment_upcoming_reminder'])){if($Preference_Defaults[0]['Assignment_upcoming_reminder'] == $i){ echo 'selected';}}?>> <?php echo $i; ?> </option>
																<?php } ?> -->
																<option value="1" <?php if(isset($Preference_Defaults[0]['Assignment_upcoming_reminder'])){ if($Preference_Defaults[0]['Assignment_upcoming_reminder'] == 1){ echo 'selected'; }} ?>>Today</option>
																<option value="2" <?php if(isset($Preference_Defaults[0]['Assignment_upcoming_reminder'])){if($Preference_Defaults[0]['Assignment_upcoming_reminder'] == 2){ echo 'selected';}}?>>No Email</option>
															</select>
														</div>
										            </div>
													<div class="col-lg-12 innersetting">
														<div class="col-lg-6"><?php echo __('Assignment you missed day after'); ?></div>
														<div class="col-lg-6">
															<!--select name="right manageraction[]" id="Assignmentmissed" class="selectAction col-lg-12">
																<?php for($i=1;$i<=10;$i++){?>
																	<option value="<?php echo $i;?>" <?php if(isset($Preference_Defaults[0]['Assignment_you_missed'])){if($Preference_Defaults[0]['Assignment_you_missed'] == $i){ echo 'selected';}}?>> <?php echo $i; ?> </option>
																<?php } ?>
																<option value="11" <?php if(isset($Preference_Defaults[0]['Assignment_you_missed'])){if($Preference_Defaults[0]['Assignment_you_missed'] == 11){ echo 'selected';}}?>>Weekly Digest</option>
																<option value="12" <?php if(isset($Preference_Defaults[0]['Assignment_you_missed'])){if($Preference_Defaults[0]['Assignment_you_missed'] == 12){ echo 'selected';}}?>>No Email</option>
															</<select></select>ect-->
																<label for="Assignmentreminderact"><input type="radio" name="Assignmentmissed" id="Assignmentmissedact" <?php echo ((isset($Preference_Defaults[0]['Assignment_you_missed']) && $Preference_Defaults[0]['Assignment_you_missed']=='1') ? 'checked=""' : ''); ?> value="1" required="true" >&nbsp;Active</label>&nbsp;&nbsp;<label for="Assignmentreminderinact"><input type="radio" name="Assignmentmissed" id="Assignmentmissedinact" <?php echo ((isset($Preference_Defaults[0]['Assignment_you_missed']) && $Preference_Defaults[0]['Assignment_you_missed']=='2') ? 'checked=""' : ''); ?> value="2" required="true" >&nbsp;Inactive</label>
														  	</div>
													</div>
													<div class="col-lg-12 innersetting last">
														<div class="col-lg-6"><?php echo __('Shared Workout Plan received'); ?></div>
														<div class="col-lg-6">
															<select name="right manageraction[]" id="received" class="selectAction col-lg-12">
																<option value="1" <?php if(isset($Preference_Defaults[0]['Shared_Workout_Plan_received'])){if($Preference_Defaults[0]['Shared_Workout_Plan_received'] == 1){ echo 'selected';}}?>>Individual Email</option>
																<option value="2" <?php if(isset($Preference_Defaults[0]['Shared_Workout_Plan_received'])){if($Preference_Defaults[0]['Shared_Workout_Plan_received'] == 2){ echo 'selected';}}?>>No Email</option>
															</select>
														</div>
													</div>
													<div class="col-lg-12 innersetting">
														<div class="col-lg-6">
															<button class="btn btn-primary" id="Updatessubmit" name="submit" type="submit"><?php echo __('Save'); ?></button>
															&nbsp;&nbsp;
															<button class="btn btn-default" id="Updatescancel" name="cancel" type="submit"><?php echo __('Cancel'); ?></button>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="table-responsive col-lg-12 ocpArticleContent">
											<div class="panel">
												<div data-toggle="collapse" data-target="#manageemailfrequency2" class="toggletitle"><?php echo __('Messages from other users'); ?><i class="fa fa-pencil"></i></div>
												<div  id="manageemailfrequency2" class="collapse">
													<div class="col-lg-12 innersetting">
														<div class="col-lg-6"><?php echo __('Sharing'); ?></div>
														<div class="col-lg-6">
															<select name="right manageraction[]" id="Sharing" class="selectAction col-lg-12">
																<option value="1" <?php if(isset($Preference_Defaults[0]['Sharing'])){if($Preference_Defaults[0]['Sharing'] == 1){ echo 'selected';}}?>>Individual Email</option>
																<option value="2" <?php if(isset($Preference_Defaults[0]['Sharing'])){if($Preference_Defaults[0]['Sharing'] == 2){ echo 'selected';}}?>>Weekly Digest</option>
																<option value="3" <?php if(isset($Preference_Defaults[0]['Sharing'])){if($Preference_Defaults[0]['Sharing'] == 3){ echo 'selected';}}?>>No Email</option>
															</select>
														</div>
													</div>
													<div class="col-lg-12 innersetting">
														<div class="col-lg-6"><?php echo __('Invitation to connect'); ?></div>
														<div class="col-lg-6">
															<select name="right manageraction[]" id="Invitation" class="selectAction col-lg-12">
																<option value="1" <?php if(isset($Preference_Defaults[0]['Invitation_to_connect'])){if($Preference_Defaults[0]['Invitation_to_connect'] == 1){ echo 'selected';}}?>>Individual Email</option>
																<option value="2" <?php if(isset($Preference_Defaults[0]['Invitation_to_connect'])){if($Preference_Defaults[0]['Invitation_to_connect'] == 2){ echo 'selected';}}?>>Weekly Digest</option>
																<option value="3" <?php if(isset($Preference_Defaults[0]['Invitation_to_connect'])){if($Preference_Defaults[0]['Invitation_to_connect'] == 3){ echo 'selected';}}?>>No Email</option>
															</select>
														</div>
										            </div>
													<div class="col-lg-12 innersetting">
														<div class="col-lg-6">
															<button class="btn btn-primary" id="userssubmit" name="submit" type="submit"><?php echo __('Save'); ?></button>
															&nbsp;&nbsp;
															<button class="btn btn-default" id="userscancel" name="cancel" type="submit"><?php echo __('Cancel'); ?></button>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="table-responsive col-lg-12 ocpArticleContent">
											<div class="panel">
												<div data-toggle="collapse" data-target="#manageemailfrequency3" class="toggletitle"><?php echo __('Messages from My Workouts team'); ?><i class="fa fa-pencil"></i></div>
												<div  id="manageemailfrequency3" class="collapse">
													<div class="col-lg-12 innersetting">
														<div class="col-lg-6"><?php echo __('Details on new features, tips and special offers'); ?></div>
														<div class="col-lg-6">
															<select name="right manageraction[]" id="special" class="selectAction col-lg-12">
																<option value="1" <?php if(isset($Preference_Defaults[0]['new_features_tips_special_offers'])){if($Preference_Defaults[0]['new_features_tips_special_offers'] == 1){ echo 'selected';}}?>>Individual Email</option>
																<option value="2" <?php if(isset($Preference_Defaults[0]['new_features_tips_special_offers'])){if($Preference_Defaults[0]['new_features_tips_special_offers'] == 2){ echo 'selected';}}?>>Weekly Digest</option>
																<option value="3" <?php if(isset($Preference_Defaults[0]['new_features_tips_special_offers'])){if($Preference_Defaults[0]['new_features_tips_special_offers'] == 3){ echo 'selected';}}?>>No Email</option>
															</select>
														</div>
													</div>
													<div class="col-lg-12 innersetting">
														<div class="col-lg-6"><?php echo __('Receive email alerts for Exercises and Workouts you may be interested in'); ?></div>
														<div class="col-lg-6">
															<select name="right manageraction[]" id="Exercises" class="selectAction col-lg-12">
																<option value="1" <?php if(isset($Preference_Defaults[0]['Receive_email_alerts_for_Exercises_and_Workouts'])){if($Preference_Defaults[0]['Receive_email_alerts_for_Exercises_and_Workouts'] == 1){ echo 'selected';}}?>>Daily Email</option>
																<option value="2" <?php if(isset($Preference_Defaults[0]['Receive_email_alerts_for_Exercises_and_Workouts'])){if($Preference_Defaults[0]['Receive_email_alerts_for_Exercises_and_Workouts'] == 2){ echo 'selected';}}?>>Weekly Digest</option>
																<option value="3" <?php if(isset($Preference_Defaults[0]['Receive_email_alerts_for_Exercises_and_Workouts'])){if($Preference_Defaults[0]['Receive_email_alerts_for_Exercises_and_Workouts'] == 3){ echo 'selected';}}?>>No Email</option>
															</select>
														</div>
										            </div>
													<div class="col-lg-12 innersetting">
														<div class="col-lg-6">
															<button class="btn btn-primary" id="Workoutssubmit" name="submit" type="submit"><?php echo __('Save'); ?></button>
															&nbsp;&nbsp;
															<button class="btn btn-default" id="Workoutscancel" name="cancel" type="submit"><?php echo __('Cancel'); ?></button>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="table-responsive col-lg-12 ocpArticleContent">
											<div class="panel">
												<div data-toggle="collapse" data-target="#manageemailfrequency4" class="toggletitle"><?php echo __('Time of day'); ?><i class="fa fa-pencil"></i></div>
												<div  id="manageemailfrequency4" class="collapse">
													<div class="col-lg-12 innersetting removeborderset">
														<div class="col-lg-6"><?php echo __('Preference for what time of day to send/email these notifications'); ?></div>
														<div class="col-lg-6">
															<select name="right manageraction[]" id="timeofday" class="selectAction col-lg-12">
																<?php
																$tsfor = '';
																if(isset($Preference_Defaults[0]['time_format']) && $Preference_Defaults[0]['time_format']!=''){
																	$tfor = explode(' ',$Preference_Defaults[0]['time_format']);
																    if($tfor[1] == 'a') {$tsfor = 'am';}else{$tsfor = 'AM';}
																}
																	   
																$start=strtotime('00:00');
																$end=strtotime('23:30');
																
																for ($halfhour=$start;$halfhour<=$end;$halfhour=$halfhour+30*60) {
																
																	if(date('h:i:s A',$halfhour) == '08:30:00 AM'){
																	$sel='selected';
																	}else{
																	$sel='';
																	}
																	if(isset($Preference_Defaults[0])){
																		if($Preference_Defaults[0]['time_format']==''){?>
																			<?php if($Preference_Defaults[0]['time_to_send_email'] == ''){?>
																			<option value="<?php echo date('h:i:s A',$halfhour);?>" <?php echo $sel;?>><?php echo date('h:i:s A',$halfhour);?></option>
																			<?php }else{?>
																			<option value="<?php echo date('h:i:s A',$halfhour);?>" <?php if(isset($Preference_Defaults[0]['time_to_send_email']) && $Preference_Defaults[0]['time_to_send_email']!= ''){if($Preference_Defaults[0]['time_to_send_email'] == date('h:i:s A',$halfhour)){ echo 'selected';}}?>><?php echo date('h:i:s A',$halfhour);?></option> 
																			<?php }?>
																		<?php }else{?>
																			<?php if($Preference_Defaults[0]['time_to_send_email'] == ''){?>
																			<option value="<?php echo date($Preference_Defaults[0]['time_format'],$halfhour);?>" <?php echo $sel;?>><?php echo date($Preference_Defaults[0]['time_format'],$halfhour);?></option>
																			<?php }else{?>
                                                          	<option value="<?php echo date($Preference_Defaults[0]['time_format'],$halfhour);?>" <?php if(isset($Preference_Defaults[0]['time_to_send_email']) && $Preference_Defaults[0]['time_to_send_email']!= ''){if($Preference_Defaults[0]['time_to_send_email'] == date($Preference_Defaults[0]['time_format'],$halfhour)){ echo 'selected';}}?>><?php echo date($Preference_Defaults[0]['time_format'],$halfhour);?></option> 
																			<?php }?>
																		<?php }?>
																	<?php }else{?>
																		<option value="<?php echo date('h:i:s A',$halfhour);?>" <?php echo $sel;?> ><?php echo date('h:i:s A',$halfhour);?></option>
																	<?php }
																	/*if(isset($Preference_Defaults[0]['time_format']) && $Preference_Defaults[0]['time_format']!=''){?>
																	<option value="<?php echo date($Preference_Defaults[0]['time_format'],$halfhour);?>" <?php if(isset($Preference_Defaults[0]['time_to_send_email']) && $Preference_Defaults[0]['time_to_send_email']!= ''){if($Preference_Defaults[0]['time_to_send_email'] == date($Preference_Defaults[0]['time_format'],$halfhour)){ echo 'selected';}}else if(date($Preference_Defaults[0]['time_format'],$halfhour) == "08:30:00 $tsfor"){echo 'selected';}?>><?php echo date($Preference_Defaults[0]['time_format'],$halfhour);?></option>
																	<?php }else{?>
																	<option value="<?php echo date('g:i:s a',$halfhour);?>" <?php echo $sel;?> ><?php echo date('g:i:s a',$halfhour);?></option>
																	<?php }*/
																} ?>
					  										</select>
														</div>
													</div>
												</div>
											</div>
										</div>
								    </div>
								</div>
							</div>
							<div class="table-responsive col-lg-12 ocpArticleContent">
							    <div class="panel">
									<div data-toggle="collapse" data-target="#manageintegrationsaction" class="toggletitle"><?php echo __('Device Integrations'); ?><i class="fa fa-pencil"></i></div>
									<div  id="manageintegrationsaction" class="collapse">
										 
										<?php ///echo $selected_device ;
 										foreach($device_Integrations as $device_Integrations_key => $device_Integrations_value) {
												if($device_Integrations_value["status"]==0 && $device_Integrations_value["status_device"]!=null){
													 //if($v["status"]==0){
														?>
												<div class="accordsetting">
													<div class="col1 col-lg-6 col-xs-4"> <?php echo $device_Integrations_value['name']; ?></div>
														<div class="col2 col-lg-4 col-xs-4 text-center">
															<input class="integrationsaction" id="mybutton" type="checkbox"  data-tt-size="big"  data-tt-palette="blue"  value="<?php echo $device_Integrations_value['id'];?>" <?php if( !empty($selected_device) ){if(in_array($device_Integrations_value['id'], $selected_device, true)){ echo 'checked';}} ?>>
														</div>
														<div class="col3 col-lg-2 col-xs-4">
															<div class="accord  text-right"><div data-toggle="collapse" data-target="#fitbit<?php echo $device_Integrations_key;?>"><i class="fa fa-chevron-down"></i></div></div>
														</div>
										
										
													</div>
										<div  id="fitbit<?php echo $device_Integrations_key;?>" class="collapse col-lg-12 ">
											<div class="row topbottom"><div class="col-sm-6 col-xs-6"><?php echo __('Use My Profile information'); ?></div><div class="col-sm-6 col-xs-6 text-right accordswitch"><input type="checkbox" data-size="mini" name="my-checkbox" checked></div></div>
											<div class="row topbottom"><div class="col-sm-6 col-xs-6"><?php echo __('Sync My Data'); ?></div><div class="col-sm-6 col-xs-6 text-right accordswitch"><input type="checkbox" data-size="mini" name="my-checkbox" checked></div></div>
											<div class="row topbottom"><div class="col-sm-6 col-xs-6"><?php echo __('Notify me When Sync Data'); ?></div><div class="col-sm-6 col-xs-6 text-right accordswitch"><input type="checkbox" data-size="mini" name="my-checkbox" checked></div></div>
											<!--
											<div class="row topbottom"><div class="col-sm-6 col-xs-6">List 4</div><div class="col-sm-6 col-xs-6 text-right accordswitch"><input type="checkbox" data-size="mini" name="my-checkbox" checked></div></div> -->
										</div>
										<div class="clearfix"></div>
										<?php
												}
								} ?>
								    </div>
								</div>
							</div>
							
						</div>	
                    </div>						
				</div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->
    <!-- jQuery -->
</body>

