<div id="wrap-index">
<!-- Login header nav !-->
<?php echo $topHeader; ?>
<div class="container" id="home">
	<div class="row bannermsg" id="mes_suc" style="display:none;">
		<div class="banner success alert alert-success"><a data-ajax="false" data-role="none" href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>
	</div>
	<div class="row">
		<div class="page-head">
			<div class="col-xs-3 aligncenter">
				<a href="<?php echo URL::base(TRUE).'dashboard/index/'; ?>" title="<?php echo __('Back'); ?>" data-ajax="false" data-role="none">
					<i class="fa fa-caret-left iconsize"></i>
				</a>
			</div>
			<div class="col-xs-6 aligncenter centerheight"><?php echo __('Preference Settings'); ?></div>
			<div class="col-xs-3 aligncenter"></div>
		</div>
	</div>
	<hr>
	<div class="row settings-row">
		<div class="col-lg-12">
			<div class="banded" id="banded" class="panel-group">
				<div class="table-responsive col-lg-12 ocpArticleContent">
					<div class="panel">
						<div data-toggle="collapse" data-target="#managetimezoneaction" class="toggletitle collapsed"><?php echo __('Timezone'); ?><i class="fa fa-pencil-square-o"></i></div>
						<div id="managetimezoneaction" class="collapse toggle-body">
							<div class="col-lg-12 innersetting">
								<div class="col-lg-6"><?php echo __('Country'); ?>:</div>
								<div class="col-lg-6 dropdown">
									<select name="right manageraction[]" id="countryaction" class="selectAction col-lg-12" data-ajax="false" data-role="none">
										<option value=""><?php echo __('Country'); ?></option>
										<?php foreach($countrylist as $c) { ?>
											<option value="<?php echo $c['id']; ?>" <?php if(isset($setting_contry)){ if($setting_contry == $c['id']){ echo 'selected'; }} ?>><?php echo $c['countryname']; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="col-lg-12 innersetting">
								<div class="col-lg-6"><?php echo __('Timezone'); ?>:</div>
								<div class="col-lg-6 dropdown">
									<select name="right manageraction[]" id="timezoneaction" class="selectAction col-lg-12" data-ajax="false" data-role="none">
										<option value=""><?php echo __('Timezone'); ?></option>
										<?php if(!empty($timezonelist)) {
											foreach($timezonelist as $t) { ?>
												<option value="<?php echo  $t; ?>" <?php if(isset($setting_zone)){ if($setting_zone == $t){ echo 'selected'; }} ?>><?php echo $t; ?></option>
											<?php } 
										} ?>
									</select>
								</div>
							</div>
							<div class="col-lg-12 innersetting">
								<div class="col-lg-6"><?php echo __('Local Time'); ?>:</div>
								<div class="col-lg-6"><div class="LocalTime"><?php if(isset($Preference_Defaults[0]['time_format']) && $Preference_Defaults[0]['time_format'] != ''){ echo date($Preference_Defaults[0]['time_format'], time()); } else { echo date('h:i:s A', time()); } ?></div></div>
							</div>
							<div class="col-lg-12 innersetting">
								<div class="col-lg-6 save-cancel">
									<button class="btn btn-default btncol" id="timezonesubmit" name="submit" type="submit" data-ajax="false" data-role="none"><?php echo __('Save'); ?></button>
									&nbsp;
									<button class="btn btn-default" id="timezonecancel" name="cancel" type="submit" data-ajax="false" data-role="none"><?php echo __('Cancel'); ?></button>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="table-responsive col-lg-12 ocpArticleContent hide">
					<div class="panel">
						<div data-toggle="collapse" data-target="#managetimeformataction" class="toggletitle collapsed"><?php echo __('Time Format'); ?><i class="fa fa-pencil-square-o"></i></div>
						<div  id="managetimeformataction" class="collapse toggle-body">
							<div class="col-lg-12 innersetting">
								<div class="col-lg-6"><?php echo __('Time Format'); ?>:</div>
								<div class="col-lg-6 dropdown">
									<select name="right manageraction[]" id="timeformataction" class="selectAction col-lg-12" data-ajax="false" data-role="none">
										<option value=""><?php echo __('Time Format'); ?></option>
										<option value="h:i:s A" <?php if(isset($Preference_Defaults[0]['time_format'])){ if($Preference_Defaults[0]['time_format'] == "h:i:s A"){ echo 'selected'; }} ?>>h:i:s A</option>
										<option value="h:i:s a" <?php if(isset($Preference_Defaults[0]['time_format'])){ if($Preference_Defaults[0]['time_format'] == "h:i:s a"){ echo 'selected'; }} ?>>h:i:s a</option>
									</select>
								</div>
							</div>
							<div class="col-lg-12 innersetting">
								<div class="col-lg-6">Local Time:</div>
								<div class="col-lg-6">
									<div class="LocalTime">
										<?php if(isset($Preference_Defaults[0]['time_format']) && $Preference_Defaults[0]['time_format'] != ''){ echo date($Preference_Defaults[0]['time_format'], time()); } else { echo date('h:i:s A', time()); } ?>
										</div>
									</div>
							</div>
							<div class="col-lg-12 innersetting">
								<div class="col-lg-6 save-cancel">
									<button class="btn btn-default btncol" id="timeformatsubmit" name="submit" type="submit" data-ajax="false" data-role="none"><?php echo __('Save'); ?></button>
									&nbsp;
									<button class="btn btn-default" id="timeformatcancel" name="cancel" type="submit" data-ajax="false" data-role="none"><?php echo __('Cancel'); ?></button>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="table-responsive col-lg-12 ocpArticleContent">
					<div class="panel">
						<div data-toggle="collapse" data-target="#managelanguageaction" class="toggletitle collapsed"><?php echo __('Language'); ?><i class="fa fa-pencil-square-o"></i></div>
						<div id="managelanguageaction" class="collapse toggle-body">
							<div class="col-lg-12 innerselect">
								<select name="right manageraction[]" id="languageaction" class="selectAction selectwidth" data-ajax="false" data-role="none">
									<?php $currentlang = isset($Preference_Defaults[0]['language']) ? $Preference_Defaults[0]['language'] : 1;
									foreach($languagelist as $language){ ?>
										<option value="<?php echo $language['language_id']; ?>" <?php if($currentlang == $language['language_id']){ echo 'selected'; } ?>><?php echo $language['name']; ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
					</div>
				</div>
				<div class="table-responsive col-lg-12 ocpArticleContent">
					<div class="panel">
						<div data-toggle="collapse" data-target="#managedateformataction" class="toggletitle collapsed"><?php echo __('Date Format'); ?><i class="fa fa-pencil-square-o"></i></div>
						<div id="managedateformataction" class="collapse toggle-body">
							<div class="col-lg-12 innersetting">
								<div class="col-lg-6"><?php echo __('Date Format'); ?>:</div>
								<div class="col-lg-6 dropdown">
									<select name="right manageraction[]" id="dateformataction" class="selectAction col-lg-12" data-ajax="false" data-role="none">
										<option value="">Date Format</option>
										<option value="Y-M-d" <?php if(isset($Preference_Defaults[0]['date_format'])){if($Preference_Defaults[0]['date_format'] == "Y-M-d"){ echo 'selected'; }} ?>>yyyy - MMM - dd</option>
										<option value="Y-m-d" <?php if(isset($Preference_Defaults[0]['date_format'])){if($Preference_Defaults[0]['date_format'] == "Y-m-d"){ echo 'selected'; }} ?>>yyyy - mm - dd</option>
										<option value="Y/M/d" <?php if(isset($Preference_Defaults[0]['date_format'])){if($Preference_Defaults[0]['date_format'] == "Y/M/d"){ echo 'selected'; }} ?>>yyyy / MMM / dd</option>
										<option value="Y/m/d" <?php if(isset($Preference_Defaults[0]['date_format'])){if($Preference_Defaults[0]['date_format'] == "Y/m/d"){ echo 'selected'; }} ?>>yyyy / mm / dd</option>
										<option value="Y.M.d" <?php if(isset($Preference_Defaults[0]['date_format'])){if($Preference_Defaults[0]['date_format'] == "Y.M.d"){ echo 'selected'; }} ?>>yyyy . MMM . dd</option>
										<option value="Y.m.d" <?php if(isset($Preference_Defaults[0]['date_format'])){if($Preference_Defaults[0]['date_format'] == "Y.m.d"){ echo 'selected'; }} ?>>yyyy . mm . dd</option>
									</select>
								</div>
							</div>
							<div class="col-lg-12 innersetting">
								<div class="col-lg-6"><?php echo __('Current Date Format'); ?>:</div>
								<div class="col-lg-6">
									<div class="Localdate">
										<?php if(isset($Preference_Defaults[0]['date_format']) && $Preference_Defaults[0]['date_format'] != ''){ echo date($Preference_Defaults[0]['date_format']); } else { echo date('Y-m-d'); } ?>
									</div>
								</div>
							</div>
							<div class="col-lg-12 innersetting">
								<div class="col-lg-6 save-cancel">
									<button class="btn btn-default btncol" id="dateformatsubmit" name="submit" type="submit" data-ajax="false" data-role="none"><?php echo __('Save'); ?></button>
									&nbsp;
									<button class="btn btn-default" id="dateformatcancel" name="cancel" type="submit" data-ajax="false" data-role="none"><?php echo __('Cancel'); ?></button>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="table-responsive col-lg-12 ocpArticleContent">
					<div class="panel">
						<div data-toggle="collapse" data-target="#manageweekaction" class="toggletitle collapsed"><?php echo __('Week (Starts On)'); ?><i class="fa fa-pencil-square-o"></i></div>
						<div id="manageweekaction" class="collapse toggle-body">
							<div class="col-lg-12 innerselect">
								<select name="right manageraction[]" id="weekaction" class="selectAction selectwidth" data-ajax="false" data-role="none">
									<option value="1" <?php if(isset($Preference_Defaults[0]['week_sarts_on'])){ if($Preference_Defaults[0]['week_sarts_on'] == 1){ echo 'selected'; }} ?>>Monday</option>
									<option value="2" <?php if(isset($Preference_Defaults[0]['week_sarts_on'])){ if($Preference_Defaults[0]['week_sarts_on'] == 2){ echo 'selected'; }} ?>>Tuesday</option>
									<option value="3" <?php if(isset($Preference_Defaults[0]['week_sarts_on'])){ if($Preference_Defaults[0]['week_sarts_on'] == 3){ echo 'selected'; }} ?>>Wednesday</option>
									<option value="4" <?php if(isset($Preference_Defaults[0]['week_sarts_on'])){ if($Preference_Defaults[0]['week_sarts_on'] == 4){ echo 'selected'; }} ?>>Thursday</option>
									<option value="5" <?php if(isset($Preference_Defaults[0]['week_sarts_on'])){ if($Preference_Defaults[0]['week_sarts_on'] == 5){ echo 'selected'; }} ?>>Friday</option>
									<option value="6" <?php if(isset($Preference_Defaults[0]['week_sarts_on'])){ if($Preference_Defaults[0]['week_sarts_on'] == 6){ echo 'selected'; }} ?>>Saturday</option>
									<option value="7" <?php if(isset($Preference_Defaults[0]['week_sarts_on'])){ if($Preference_Defaults[0]['week_sarts_on'] == 7){ echo 'selected'; }} ?>>Sunday</option>
								</select>
							</div>
						</div>
					</div>
				</div>
				<div class="table-responsive col-lg-12 ocpArticleContent">
					<div class="panel">
						<div data-toggle="collapse" data-target="#managemeasurementsaction" class="toggletitle collapsed"><?php echo __('Measurements'); ?><i class="fa fa-pencil-square-o"></i></div>
						<div  id="managemeasurementsaction" class="collapse toggle-body">
							<div class="col-lg-12 innersetting">
								<div class="col-lg-6"><?php echo __('Weight'); ?>:</div>
								<div class="col-lg-6 dropdown">
									<select name="right manageraction[]" id="weightaction" class="selectAction col-lg-12" data-ajax="false" data-role="none">
									 	<option value="">Weight</option>
									 	<?php foreach($weightlist as $wei) { ?>
									 		<option value="<?php echo $wei['resist_id']; ?>" <?php if(isset($Preference_Defaults[0]['Weight'])){if($Preference_Defaults[0]['Weight'] == $wei['resist_id']){ echo 'selected'; }} ?>><?php echo ucfirst($wei['resist_title']); ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="col-lg-12 innersetting">
								<div class="col-lg-6"><?php echo __('Distance'); ?>:</div>
								<div class="col-lg-6 dropdown">
									<select name="right manageraction[]" id="distanceaction" class="selectAction col-lg-12" data-ajax="false" data-role="none">
									 	<option value="">Distance</option>
									 	<?php foreach($distancelist as $dis) { ?>
											<option value="<?php echo $dis['dist_id']; ?>" <?php if(isset($Preference_Defaults[0]['Distance'])){if($Preference_Defaults[0]['Distance'] == $dis['dist_id']){ echo 'selected'; }} ?>><?php echo ucfirst($dis['dist_title']); ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="col-lg-12 innersetting">
								<div class="col-lg-6 save-cancel">
									<button class="btn btn-default btncol" id="measurementsubmit" name="submit" type="submit" data-ajax="false" data-role="none"><?php echo __('Save'); ?></button>
									&nbsp;
									<button class="btn btn-default" id="measurementcancel" name="cancel" type="submit" data-ajax="false" data-role="none"><?php echo __('Cancel'); ?></button>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!--<div class="table-responsive col-lg-12 ocpArticleContent">
					<div class="panel">
						<div data-toggle="collapse" data-target="#managenotificationsaction" class="toggletitle collapsed">Notifications<i class="fa fa-pencil-square-o"></i></div>
						<div id="managenotificationsaction" class="collapse toggle-body">
							<select name="right manageraction[]" id="notificationsaction" class="selectAction col-lg-12" data-ajax="false" data-role="none">
								<option value=""><?php //echo __('Notifications'); ?></option>
								<option value="1" <?php //if(isset($Preference_Defaults[0]['notifications'])){ if($Preference_Defaults[0]['notifications'] == 1){ echo 'selected'; }} ?>>Yes</option>
								<option value="2" <?php //if(isset($Preference_Defaults[0]['notifications'])){ if($Preference_Defaults[0]['notifications'] == 2){ echo 'selected'; }} ?>>No</option>
							</select>
						</div>
					</div>
				</div>-->
				<div class="table-responsive col-lg-12 ocpArticleContent">
					<div class="panel">
						<div data-toggle="collapse" data-target="#manageemailfrequency" class="toggletitle collapsed"><?php echo __('Email frequency'); ?><i class="fa fa-pencil-square-o"></i></div>
						<div  id="manageemailfrequency" class="col-lg-12 collapse">
							<div class="table-responsive col-lg-12 ocpArticleContent">
								<div class="panel">
									<div data-toggle="collapse" data-target="#manageemailfrequency1" class="toggletitle collapsed"><?php echo __('Updates and news'); ?><i class="fa fa-pencil-square-o"></i></div>
									<div id="manageemailfrequency1" class="collapse toggle-body">
										<div class="col-lg-12 innersetting">
											<div class="col-lg-6"><?php echo __('Network updates'); ?>:</div>
											<div class="col-lg-6 dropdown">
												<select name="right manageraction[]" id="Networksaction" class="selectAction col-lg-12" data-ajax="false" data-role="none">
													<option value="1" <?php if(isset($Preference_Defaults[0]['Network_updates'])){ if($Preference_Defaults[0]['Network_updates'] == 1){ echo 'selected'; }} ?>>Weekly Digest</option>
													<option value="2" <?php if(isset($Preference_Defaults[0]['Network_updates'])){ if($Preference_Defaults[0]['Network_updates'] == 2){ echo 'selected'; }} ?>>No Email</option>
												</select>
											</div>
										</div>
										<div class="col-lg-12 innersetting">
											<div class="col-lg-6"><?php echo __('Assignment reminder'); ?>:</div>
											<div class="col-lg-6 dropdown">
												<select name="right manageraction[]" id="Assignmentreminder" class="selectAction col-lg-12" data-ajax="false" data-role="none">
													<option value="1" <?php if(isset($Preference_Defaults[0]['Assignment_upcoming_reminder'])){ if($Preference_Defaults[0]['Assignment_upcoming_reminder'] == 1){ echo 'selected'; }} ?>>Today</option>
													<option value="2" <?php if(isset($Preference_Defaults[0]['Assignment_upcoming_reminder'])){ if($Preference_Defaults[0]['Assignment_upcoming_reminder'] == 2){ echo 'selected'; }} ?>>No Email</option>
												</select>
											</div>
										</div>
										<div class="col-lg-12 innersetting">
											<div class="col-lg-6"><?php echo __('Assignment you missed'); ?>:</div>
											<div class="col-lg-6 dropdown">
												<select name="right manageraction[]" id="Assignmentmissed" class="selectAction col-lg-12" data-ajax="false" data-role="none">
													<?php for($i=1;$i<=10;$i++){ ?>
														<option value="<?php echo $i; ?>" <?php if(isset($Preference_Defaults[0]['Assignment_you_missed'])){ if($Preference_Defaults[0]['Assignment_you_missed'] == $i){ echo 'selected'; }} ?>><?php echo $i; ?></option>
													<?php } ?>
													<option value="11" <?php if(isset($Preference_Defaults[0]['Assignment_you_missed'])){ if($Preference_Defaults[0]['Assignment_you_missed'] == 11){ echo 'selected'; }} ?>>Weekly Digest</option>
													<option value="12" <?php if(isset($Preference_Defaults[0]['Assignment_you_missed'])){ if($Preference_Defaults[0]['Assignment_you_missed'] == 12){ echo 'selected'; }} ?>>No Email</option>
												</select>
											</div>
										</div>
										<div class="col-lg-12 innersetting">
											<div class="col-lg-6"><?php echo __('Shared Workout Plan received'); ?>:</div>
											<div class="col-lg-6 dropdown">
												<select name="right manageraction[]" id="received" class="selectAction col-lg-12" data-ajax="false" data-role="none">
													<option value="1" <?php if(isset($Preference_Defaults[0]['Shared_Workout_Plan_received'])){ if($Preference_Defaults[0]['Shared_Workout_Plan_received'] == 1){ echo 'selected'; }} ?>>Individual Email</option>
													<option value="2" <?php if(isset($Preference_Defaults[0]['Shared_Workout_Plan_received'])){ if($Preference_Defaults[0]['Shared_Workout_Plan_received'] == 2){ echo 'selected'; }} ?>>No Email</option>
												</select>
											</div>
										</div>
										<div class="col-lg-12 innersetting">
											<div class="col-lg-6 save-cancel">
												<button class="btn btn-default btncol" id="Updatessubmit" name="submit" type="submit" data-ajax="false" data-role="none"><?php echo __('Save'); ?></button>
												&nbsp;
												<button class="btn btn-default" id="Updatescancel" name="cancel" type="submit" data-ajax="false" data-role="none"><?php echo __('Cancel'); ?></button>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="table-responsive col-lg-12 ocpArticleContent">
								<div class="panel">
									<div data-toggle="collapse" data-target="#manageemailfrequency2" class="toggletitle collapsed"><?php echo __('Messages from other users'); ?><i class="fa fa-pencil-square-o"></i></div>
									<div id="manageemailfrequency2" class="collapse toggle-body">
										<div class="col-lg-12 innersetting">
											<div class="col-lg-6"><?php echo __('Sharing'); ?>:</div>
											<div class="col-lg-6 dropdown">
												<select name="right manageraction[]" id="Sharing" class="selectAction col-lg-12" data-ajax="false" data-role="none">
													<option value="1" <?php if(isset($Preference_Defaults[0]['Sharing'])){ if($Preference_Defaults[0]['Sharing'] == 1){ echo 'selected'; }} ?>>Individual Email</option>
													<option value="2" <?php if(isset($Preference_Defaults[0]['Sharing'])){ if($Preference_Defaults[0]['Sharing'] == 2){ echo 'selected'; }} ?>>Weekly Digest</option>
													<option value="3" <?php if(isset($Preference_Defaults[0]['Sharing'])){ if($Preference_Defaults[0]['Sharing'] == 3){ echo 'selected'; }} ?>>No Email</option>
												</select>
											</div>
										</div>
										<div class="col-lg-12 innersetting">
											<div class="col-lg-6"><?php echo __('Invitation to connect'); ?>:</div>
											<div class="col-lg-6 dropdown">
												<select name="right manageraction[]" id="Invitation" class="selectAction col-lg-12" data-ajax="false" data-role="none">
													<option value="1" <?php if(isset($Preference_Defaults[0]['Invitation_to_connect'])){ if($Preference_Defaults[0]['Invitation_to_connect'] == 1){ echo 'selected'; }} ?>>Individual Email</option>
													<option value="2" <?php if(isset($Preference_Defaults[0]['Invitation_to_connect'])){ if($Preference_Defaults[0]['Invitation_to_connect'] == 2){ echo 'selected'; }} ?>>Weekly Digest</option>
													<option value="3" <?php if(isset($Preference_Defaults[0]['Invitation_to_connect'])){ if($Preference_Defaults[0]['Invitation_to_connect'] == 3){ echo 'selected'; }} ?>>No Email</option>
												</select>
											</div>
										</div>
										<div class="col-lg-12 innersetting">
											<div class="col-lg-6 save-cancel">
												<button class="btn btn-default btncol" id="userssubmit" name="submit" type="submit" data-ajax="false" data-role="none"><?php echo __('Save'); ?></button>
												&nbsp;
												<button class="btn btn-default" id="userscancel" name="cancel" type="submit" data-ajax="false" data-role="none"><?php echo __('Cancel'); ?></button>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="table-responsive col-lg-12 ocpArticleContent">
								<div class="panel">
									<div data-toggle="collapse" data-target="#manageemailfrequency3" class="toggletitle collapsed"><?php echo __('Messages from My Workouts team'); ?><i class="fa fa-pencil-square-o"></i></div>
									<div id="manageemailfrequency3" class="collapse toggle-body">
										<div class="col-lg-12 innersetting">
											<div class="col-lg-6"><?php echo __('Details on new features, tips and special offers'); ?>:</div>
											<div class="col-lg-6 dropdown">
												<select name="right manageraction[]" id="special" class="selectAction col-lg-12" data-ajax="false" data-role="none">
													<option value="1" <?php if(isset($Preference_Defaults[0]['new_features_tips_special_offers'])){ if($Preference_Defaults[0]['new_features_tips_special_offers'] == 1){ echo 'selected'; }} ?>>Individual Email</option>
													<option value="2" <?php if(isset($Preference_Defaults[0]['new_features_tips_special_offers'])){ if($Preference_Defaults[0]['new_features_tips_special_offers'] == 2){ echo 'selected'; }} ?>>Weekly Digest</option>
													<option value="3" <?php if(isset($Preference_Defaults[0]['new_features_tips_special_offers'])){ if($Preference_Defaults[0]['new_features_tips_special_offers'] == 3){ echo 'selected'; }} ?>>No Email</option>
												</select>
											</div>
										</div>
										<div class="col-lg-12 innersetting">
											<div class="col-lg-6"><?php echo __('Receive email alerts for Exercises and Workouts you may be interested in'); ?>:</div>
											<div class="col-lg-6 dropdown">
												<select name="right manageraction[]" id="Exercises" class="selectAction col-lg-12" data-ajax="false" data-role="none">
													<option value="1" <?php if(isset($Preference_Defaults[0]['Receive_email_alerts_for_Exercises_and_Workouts'])){ if($Preference_Defaults[0]['Receive_email_alerts_for_Exercises_and_Workouts'] == 1){ echo 'selected'; }} ?>>Daily Email</option>
													<option value="2" <?php if(isset($Preference_Defaults[0]['Receive_email_alerts_for_Exercises_and_Workouts'])){ if($Preference_Defaults[0]['Receive_email_alerts_for_Exercises_and_Workouts'] == 2){ echo 'selected'; }} ?>>Weekly Digest</option>
													<option value="3" <?php if(isset($Preference_Defaults[0]['Receive_email_alerts_for_Exercises_and_Workouts'])){ if($Preference_Defaults[0]['Receive_email_alerts_for_Exercises_and_Workouts'] == 3){ echo 'selected'; }} ?>>No Email</option>
												</select>
											</div>
										</div>
										<div class="col-lg-12 innersetting">
											<div class="col-lg-6 save-cancel">
												<button class="btn btn-default btncol" id="Workoutssubmit" name="submit" type="submit" data-ajax="false" data-role="none"><?php echo __('Save'); ?></button>
												&nbsp;
												<button class="btn btn-default" id="Workoutscancel" name="cancel" type="submit" data-ajax="false" data-role="none"><?php echo __('Cancel'); ?></button>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="table-responsive col-lg-12 ocpArticleContent">
								<div class="panel">
									<div data-toggle="collapse" data-target="#manageemailfrequency4" class="toggletitle collapsed"><?php echo __('Time of day'); ?><i class="fa fa-pencil-square-o"></i></div>
									<div id="manageemailfrequency4" class="collapse toggle-body">
										<div class="col-lg-12 innersetting">
											<div class="col-lg-6"><?php echo __('Preference for what time of day to send/email these notifications'); ?>:</div>
											<div class="col-lg-6 dropdown">
												<select name="right manageraction[]" id="timeofday" class="selectAction col-lg-12 sm-width" data-ajax="false" data-role="none">
													<?php $tsfor = '';
													if(isset($Preference_Defaults[0]['time_format']) && $Preference_Defaults[0]['time_format']!=''){
														$tfor = explode(' ',$Preference_Defaults[0]['time_format']);
														if($tfor[1] == 'a') { $tsfor = 'am'; } else { $tsfor = 'AM'; }
													}
													$start=strtotime('00:00');
													$end=strtotime('23:30');
													for ($halfhour=$start; $halfhour<=$end; $halfhour=$halfhour+30*60) {
														if(date('h:i:s A',$halfhour) == '08:30:00 AM'){
															$sel='selected';
														} else {
															$sel='';
														}
														if(isset($Preference_Defaults[0])){
															if($Preference_Defaults[0]['time_format']==''){
																if($Preference_Defaults[0]['time_to_send_email'] == ''){ ?>
																	<option value="<?php echo date('h:i:s A', $halfhour); ?>" <?php echo $sel; ?>><?php echo date('h:i:s A', $halfhour); ?></option>
																<?php } else { ?>
																	<option value="<?php echo date('h:i:s A', $halfhour); ?>" <?php if(isset($Preference_Defaults[0]['time_to_send_email']) && $Preference_Defaults[0]['time_to_send_email']!= ''){ if($Preference_Defaults[0]['time_to_send_email'] == date('h:i:s A', $halfhour)){ echo 'selected'; }} ?>><?php echo date('h:i:s A', $halfhour); ?></option> 
																<?php }
															} else {
																if($Preference_Defaults[0]['time_to_send_email'] == ''){ ?>
																	<option value="<?php echo date($Preference_Defaults[0]['time_format'], $halfhour); ?>" <?php echo $sel; ?>><?php echo date($Preference_Defaults[0]['time_format'],$halfhour); ?></option>
																<?php } else { ?>
																	<option value="<?php echo date($Preference_Defaults[0]['time_format'], $halfhour); ?>" <?php if(isset($Preference_Defaults[0]['time_to_send_email']) && $Preference_Defaults[0]['time_to_send_email']!= ''){ if($Preference_Defaults[0]['time_to_send_email'] == date($Preference_Defaults[0]['time_format'], $halfhour)){ echo 'selected'; }} ?>><?php echo date($Preference_Defaults[0]['time_format'], $halfhour); ?></option> 
																<?php }
															}
														} else { ?>
															<option value="<?php echo date('h:i:s A', $halfhour); ?>" <?php echo $sel; ?> ><?php echo date('h:i:s A', $halfhour); ?></option>
														<?php }
														/*if(isset($Preference_Defaults[0]['time_format']) && $Preference_Defaults[0]['time_format']!=''){ ?>
														<option value="<?php echo date($Preference_Defaults[0]['time_format'],$halfhour); ?>" <?php if(isset($Preference_Defaults[0]['time_to_send_email']) && $Preference_Defaults[0]['time_to_send_email']!= ''){if($Preference_Defaults[0]['time_to_send_email'] == date($Preference_Defaults[0]['time_format'],$halfhour)){ echo 'selected';}}else if(date($Preference_Defaults[0]['time_format'],$halfhour) == "08:30:00 $tsfor"){echo 'selected';} ?>><?php echo date($Preference_Defaults[0]['time_format'],$halfhour); ?></option>
														<?php }else{ ?>
														<option value="<?php echo date('g:i:s a',$halfhour); ?>" <?php echo $sel; ?> ><?php echo date('g:i:s a',$halfhour); ?></option>
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
						<div data-toggle="collapse" data-target="#manageintegrationsaction" class="toggletitle collapsed"><?php echo __('Device Integrations'); ?><i class="fa fa-pencil-square-o"></i></div>
						<div id="manageintegrationsaction" class="collapse toggle-body">
							<?php foreach($device_Integrations as $device_Integrations_key => $device_Integrations_value) { ?>
								<div class="col-sm-12 col-xs-12 innersetting">
									<div class="col-sm-5 col-xs-5"><?php echo $device_Integrations_value['name']; ?></div>
									<div class="col-sm-4 col-xs-4 deviceintegrate-check">
										<div class="on-off-check">
											<input class="integrationsaction" name="right manageraction[]" id="integrationsaction<?php echo $device_Integrations_value['device_id']; ?>" type="checkbox" value="<?php echo $device_Integrations_value['device_id']; ?>" <?php if( !empty($selected_device) ){ if(in_array($device_Integrations_value['device_id'], $selected_device, true)){ echo 'checked'; }} ?> data-on-text="" data-off-text="" data-ajax="false" data-role="none">
										</div>
									</div>
									<div class="col-sm-3 col-xs-3">
										<div data-toggle="collapse" data-target="#deviceOpt<?php echo $device_Integrations_key; ?>" class="pull-right device-opt collapsed">
											<i class="fa fa-chevron-down"></i>
										</div>
									</div>
								</div>
								<div id="deviceOpt<?php echo $device_Integrations_key; ?>" class="collapse col-sm-12 col-xs-12 deviceopt-body">
									<div class="topbottom">
										<div class="col-sm-9 col-xs-9"><?php echo __('Use My Profile Information'); ?></div>
										<div class="col-sm-3 col-xs-3 text-right accordswitch">
											<input class="deviceaction" id="deviceaction" type="checkbox" data-tt-size="big" data-tt-palette="blue" data-ajax="false" data-role="none"/>
										</div>
									</div>
									<div class="topbottom">
										<div class="col-sm-9 col-xs-9"><?php echo __('Sync My Data'); ?></div>
										<div class="col-sm-3 col-xs-3 text-right accordswitch">
											<input class="deviceaction" id="deviceaction" type="checkbox" data-tt-size="big" data-tt-palette="blue" data-ajax="false" data-role="none"/>
										</div>
									</div>
									<div class="topbottom">
										<div class="col-sm-9 col-xs-9"><?php echo __('Notify Me When Sync Data'); ?></div>
										<div class="col-sm-3 col-xs-3 text-right accordswitch">
											<input class="deviceaction" id="deviceaction" type="checkbox" data-tt-size="big" data-tt-palette="blue" data-ajax="false" data-role="none"/>
										</div>
									</div>
								</div>
							<?php } ?>
							<div class="" style="padding: 0; margin: 0; border-right: 1px solid #ddd; border-left: 1px solid #ddd; float: none; display: flex;"></div>
						</div>
					</div>
				</div>
				<div class="table-responsive col-lg-12 ocpArticleContent">
					<div class="panel">
						<div data-toggle="collapse" data-target="#managecommonaction" class="toggletitle collapsed"><?php echo __('Common Settings'); ?><i class="fa fa-pencil-square-o"></i></div>
						<div id="managecommonaction" class="collapse toggle-body">
							<div class="col-lg-12 innersetting">
								<div class="col-lg-6"><?php echo __('Exercise Set Extra Variables'); ?>:</div>
								<?php $xrset =array('0'=>'No','1'=>'Yes');?>
								<div class="col-lg-6 dropdown">
									<select name="right xrsetvariableaction[]" id="xrsetvariableaction" class="selectAction col-lg-12" data-ajax="false" data-role="none">
									 	<?php foreach($xrset as $key =>  $val) { ?>
									 		<option value="<?php echo $key; ?>" <?php if(isset($Preference_Defaults[0]['XRset_extra_variable_flag'])){if($Preference_Defaults[0]['XRset_extra_variable_flag'] == $key){ echo 'selected'; }} ?>><?php echo ucfirst($val); ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="col-lg-12 innersetting">
								<div class="col-lg-6 save-cancel">
									<button class="btn btn-default btncol" id="commonsettingsubmit" name="submit" type="submit" data-ajax="false" data-role="none"><?php echo __('Save'); ?></button>
									&nbsp;
									<button class="btn btn-default" id="commonsettingcancel" name="cancel" type="submit" data-ajax="false" data-role="none"><?php echo __('Cancel'); ?></button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!--a href="https://na-api.preva.com/exerciser-api/oauth/authorize?response_type=code&client_id=activecarrot&redirect_uri=https://precorapi.activecarrot.com?calling_url=http://precor.linkscloud.com.au/precor/auth_response">Connect Preva</a-->
<script type="text/javascript">
function getTime(){
	var preferencetime = '<?php if(isset($Preference_Defaults[0]['time_format']) && $Preference_Defaults[0]['time_format'] != ''){
		echo date($Preference_Defaults[0]['time_format'], time());
	} else {
		echo date('h:i:s A', time());
	} ?>';
	return preferencetime;
}
</script>