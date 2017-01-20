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
                        <h1 class="page-header">
						<?php echo (isset($site_language['Browse'])) ? $site_language['Browse'] : 'Browse';?> 
						<?php echo (isset($site_language['My Workout Records'])) ? $site_language['My Workout Records'] : 'My Workout Records';?>
                        </h1>
						
                        <ol class="breadcrumb">
                            <li>
                                <i class="fa fa-dashboard"></i>  <a href="<?php echo URL::base().'admin/index'; ?>">Dashboard</a>
                            </li>
                            <li class="active">
                                <i class="fa fa-edit"></i> Workout Records List
                            </li>
                        </ol>
                    </div>
						  
						  
                </div>
                <!-- /.row -->
                               
				<div class="row">
					<?php $session = Session::instance();
						if ($session->get('success')): ?>
					  <div class="banner success alert alert-success">
						<a data-ajax="false" data-role="none" href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
						<?php echo $session->get_once('success') ?>
					  </div>
					 <?php endif ?>
				</div>			
				<div class="row">
					<h2 class="col-lg-6">
					<?php if(isset($workout_count) && $workout_count >0) { echo $workout_count; }?> 
					<?php echo (isset($site_language['Workout Record(s)'])) ? $site_language['Workout Record(s)'] : 'Workout Record(s)';?></h2>
					<div class="col-lg-6">
					</div>
				</div>
				
				<div class="gallery-div">
					
					<form class="top-srch-frm" action="<?php echo URL::base().'admin/workout/browse';?>" method="post" >
						<div class="topsearch">
							<div class="border">
								<div class="col-xs-11" >
									<div id="xr_filter_search" class="">
										<input type="text" name="autosearch" class="searchtext form-control input-sm" placeholder="Search for workouts..." value="<?php if(isset($searchval['autosearch'])){echo $searchval['autosearch'];}?>">
										<span class="searchclear fa fa-remove" style="display:<?php if(isset($searchval['autosearch']) && $searchval['autosearch'] != ''){echo 'block';}else{echo 'none';}?>;"></span>
									</div>
								</div>
								<div class="col-xs-1 hide"  onclick="showAdvanceSearch();">
									<i class="fa fa-caret-down search-filter iconsize" data-class="filter_this"></i>
								</div>
							</div>
							<div class="col-lg-12 advance-search-contnr " style="display:none;border: 1px solid #ddd;">
								<div class="col-lg-12">
									<h3>Advanced Search</h3>
								</div>
								<div class="">
									<!--Tab Start-->
									<div class="col-sm-12">
										<ul class="nav nav-tabs ">
											<li class="active"><a data-toggle="tab" href="#wkoutstatus">Status</a></li>
											<li><a data-toggle="tab" href="#futured">Futured</a></li>
										</ul>
									</div>
									
									<div class="tab-content">
										<div id="wkoutstatus" class="tab-pane fade in active">
											<div class="bodycontent wkoutstatus">
												<div class="filterTitle title=types col-sm-12">
													<div class="filter_heading col-sm-12"><h4>Status</h4></div>
													<div class="filter_column left col-sm-12">
														<div class="filter_subhead col-sm-12">
															<a href="javascript:void(0);" class="filter_sub_btn select_all">Select All</a>
															<span class="filter_sub_divider">|</span>
															<a href="javascript:void(0);" class="filter_sub_btn select_none">Select None</a>
														</div>
														<?php  //print_r($wkoutfilter);	
														if(isset($status)) {
															foreach($status as $keys => $value) { $key = $value['id'];?>
																<div class="col-sm-12">
																	<input type="checkbox"	name="wkoutfilter[]" <?php if(!empty($wkoutfilter)){if(in_array($value['id'],$wkoutfilter) ){echo "checked";}}?>   class="type_chkbx" value="<?php echo  $value['id'];?>" tabindex="<?php echo  $value['status_title'];?>">
																	<label for="wkoutfilter[]"><?php echo ucwords( $value['status_title']);?></label>
																	<div style="clear:left;"></div>
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
												<div class="filterTitle title=types col-sm-12">
													<div class="filter_heading col-sm-12"><h4>Futured</h4></div>
													<div class="filter_column left col-sm-12">
															<div class="col-sm-12">
																<input type="checkbox"	name="futured_filter" <?php if(isset($futured_val)){if($futured_val == 1 ){echo "checked";}}?>  class="type_chkbx" value="1" tabindex="">
																<label for="futured_filter"><?php echo ucwords('featured');?></label>
																<div style="clear:left;"></div>
															</div>
														
													</div>
												</div> <!-- [END] filter_levelType -->
												<div style="clear:both;"></div>      
											</div>
										</div>
									</div>	
								</div>			
							</div>
						
							<div class="border">
							<div class="col-sm-10 filter_exe">
									<label for="sortby">Sort By :</label>
									<select id="fsortby" name="fsortby" class="fsortby selectpicker selectAction" data-live-search="true" style="width:250px;">
										<option value="1" <?php if(isset($sortby) && $sortby == 1){echo 'selected';}?>>A-Z</option>
										<option value="2" <?php if(isset($sortby ) && $sortby == 2){echo 'selected';}?>>Z-A</option>
										<option value="3" <?php if(isset($sortby ) && $sortby == 3){echo 'selected';}?>>Created (Most Recent)</option>
										<option value="4" <?php if(isset($sortby ) && $sortby == 4){echo 'selected';}?>>Modified (Most Recent)</option>	
									</select>
							</div>
							<div class="col-sm-1 topsearchbtn">
								<!--<button class="btn btn-default" type="button" onclick="getAdvanceSearchRecords()">Fetch Records</button>-->
								<input type='hidden' name='pageval' value='<?php if(isset($_REQUEST['page']) && $_REQUEST['page'] != ''){echo $_REQUEST['page'];}?>' />
								<input type="submit" class="btn btn-default btncol fetch-record" id="getwkoutresult" value="Fetch Records"/>
								<!--<input type="reset" class="btn btn-default" id="Reset" value="Reset" onclick="clearForm(this.form);"/>-->
							</div>
							<div class="col-sm-1 topsearchbtn">
								<input type="button" class="btn btn-default btncol resetserach" value="Reset Search"/>
							</div>
						</div>
						
						
							
						</div>	
					</form>
					
					
					
					<div class="row gallery-div">
						<div class="topsearch topsearch_white">
							<div class="border" >
								<?php if(isset($workout_details) && count($workout_details)>0) { ?>
								<div class="col-sm-6 showentries">
									<label for="Show">Shows :</label>
									<form class="advnce-srch-frm" action="<?php echo URL::base().'admin/workout/browse';?>" method="get">
									<select id="lim" name="lim" class="sortby selectpicker selectAction" data-live-search="true" onchange="this.form.submit();"  >
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
									<?php } ?>
								</div>
								
								<div class="col-sm-6"  >
									<?php if(Helper_Common::hasAccess('Manage Workouts')) { ?>
											<select name="moteactions" id="moteactions" class="moteactions selectAction" >
												<option value="">Select an Option</option>
												<?php if(Helper_Common::hasAccess('Create Workouts')) { ?>
													<option value="addworkouts">Create Workout Record</option>
												<?php } ?>
												<?php if(isset($workout_details) && count($workout_details)>0) { ?>
												<option value="shareworkout">Share Selected Workout(s)</option>                                                
												<option value="deleteworkouts">Delete Selected Workout(s)</option>                                                
												<option value="tagworkouts">Tag Selected Workouts</option>
												<option value="cpytosample">Copy Selected to Samples</option>
												<?php if(Helper_Common::is_admin()) { ?>
														<option value="cpytodefault">Copy Selected to Default</option>
												<?php } ?>
												<option value="exportall"><?=__("Export this list")?></option>
												<option value="exportselected"><?=__("Export selected")?></option>
												<?php } ?>
											</select>
									<?php } ?>
								</div>
							</div>
						</div>
					</div>					
				</div>
          	<div class="row" style="margin-top:10px;">
				 	<div class="col-lg-12">
                    <?php if(isset($workout_details) && count($workout_details)>0) { ?>
                      	<div class="table-responsive">
									<table class="table table-bordered table-hover table-striped" id="wkouttable">
                              <thead>
                            		<tr>
                            			<th class='chkbox-header'><input type="checkbox" name="wkoutselectall" id='wkoutselectall' /></th>
                            			<th>Color</th>
                            			<th>Workout Title</th>
                            			<th>Workout Focus</th>
                            			<th>Folder</th>
                            			<th>Tags</th>
                            			<?php if(Helper_Common::hasAccess('Manage Workouts')) { ?>
                         				<th >Action</th>
												<?php } ?>
                            		</tr>
                           	</thead>
                           	<tbody id="table-content-contnr">
                     			<?php foreach($workout_details as $key => $value) { ?>
											<tr id="row-<?php echo $value['wkout_id'];?>">
												<td class='tabl-chkbox checkselect'>
													<?php //echo $value['wkout_id'];?>
													<input type="checkbox" name="wkoutselect[]" id="wkoutselect[]" class="wkoutselect" value="<?php echo $value['wkout_id'];?>" />
												</td>
												<td align="center">
													<div class="colorcode <?php echo strtolower($value['color_title']); ?>"></div>
												</td>
												<td>
													<a href="javascript:void(0);" onclick="getworkoutpreview('<?php echo $value['wkout_id'] ?>')">
														<?php echo $value['wkout_title'];?>
													</a>
												</td>
												<td>
													<?php 
													foreach($focusRecord as $keys => $values){
														if($values['focus_id'] == $value['wkout_focus'])
															echo ucfirst($values['focus_opt_title']);
													} ?>
												</td>
												<td>
													<?php echo $value["folder_title"]; ?>
												</td>
												<td class="tagsection"><?php  if(isset($value['tagdetails']) && !empty($value['tagdetails'])){$tags = explode('@@',$value['tagdetails']);echo implode(", ", $tags);}?>
												</td>
												<?php if(Helper_Common::hasAccess('Manage Workouts')) { ?>
												<td>
													<select name="wkoutaction[]" id="<?php echo $value['wkout_id'];?>" class="wkoutaction selectAction" onchange="goto_action('<?php echo $value['wkout_id'];?>','',this.value)">
														<option value="">Select an Option</option>
														<option value="edit">Edit</option>
														<option value="duplicate">Duplicate</option>
														<?php  if(Helper_Common::is_admin() || Helper_Common::is_manager()  || Helper_Common::is_trainer()){ ?>
														<option value="delete">Delete</option>
														<option value="share">Share</option>
														<?php } ?>
														<option value="tag">Tag</option>
														<?php  if(Helper_Common::is_admin() || Helper_Common::is_manager()  || Helper_Common::is_trainer()){ ?>
														<option value="cpytosample">Copy to Sample</option>
														<?php } ?>
														<?php if(Helper_Common::is_admin()) { ?>
														<option value="cpytodefault">Copy to Default</option>
														<?php } ?>
														<!--option value="more">More</option-->
														<option value="export">Export</option>
													</select>
												</td> 
												<?php } ?>
											</tr>
                              <?php } ?>										
                              </tbody>
                          	</table>
								<div class="exercise_tbl_pg" > <?php echo $pagination; ?> </div>
                   	</div>
						<?php } else { echo "No Records Found..."; }?>
                	</div>
					</div>
          		<!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->
 
<!-- Modal -->
<div class="modal fade" id="shareModal" tabindex="-1" role="dialog">
  <div class="vertical-alignment-helper">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Share Workout</h4>
      </div>
      <div class="modal-body">
        <form>
           <div class="form-group">
            <label for="workout-name" class="control-label">Workout(s):</label>
				<select   id='wkout_id' class="wkout_id form-control select2-hidden-accessible" style="width: 100%;" multiple style="width:350px;" tabindex="4">
					<option value=""></option>
					<?php
					if(isset($workout_details) && count($workout_details)>0) {
						foreach($workout_details as $key => $value) {
							?>
							<option value="<?php echo $value['wkout_id'];?>"><?php echo $value['wkout_title'];?></option>
							<?php
						}
					}?>
				</select>
          </div>
		  <?php if((Helper_Common::is_admin() || Helper_Common::is_manager() || Helper_Common::is_trainer()) && Helper_Common::hasAccess('Share & Assign')){ ?>
			<div class="form-group">
				<label id="is_share_option" class="control-label" for="assignment">Schedule Assignment(s): <div class="onoffcheckbox" style="display:inline-block;"><input class="checkboxdrag" name="is_share_assing" id="is_share_assing" type="checkbox"></div></label>
				<div class="assign_group col-xs-12 hide">
					<div class="col-xs-12">
						<label class="control-label" for="sharedates">Select Date(s):</label><input type="hidden" name="sharedates" id="sharedates"/>
						<div id="sharedate"></div>
					</div>
					<div id="sharedates_text" class="form-control bootstrap-tagsinput-preview" style="min-height:30px;height:auto;"></div>
				</div>
			</div>
		  <?php } ?>
          <div class="form-group">
            <label for="recipient-name" class="control-label">Recipient(s):</label>
				<?php
				$temp = array();
				$temp["manager"] = ($manager)?$manager:"";
				$temp["trainer"] = ($trainer)?$trainer:"";
				$temp["register"] = ($subscriber_details)?$subscriber_details:"";
				?>
				<script type="text/javascript">var selectdata = '<?php echo json_encode($temp); ?>';</script>
				<select id='subscriber_id' class="subscriber_id form-control fullwidth select2-hidden-accessible" style="width: 100%;" multiple style="width:350px;" tabindex="4"><option value=""></option>
					<?php
					if(isset($manager) && count($manager)>0 && is_array($manager)) {
						echo '<optgroup label="Managers">';
						foreach($manager as $key => $value) {
							?>
							<option value="<?php echo $value['id'];?>"><?php echo $value['user_fname'].' '.$value['user_lname']; ?></option>
							<?php
						}
						echo '</optgroup>';
					}
					if(isset($trainer) && count($trainer)>0 && is_array($trainer)) {
						echo '<optgroup label="Trainers">';
						foreach($trainer as $key => $value) {
							?>
							<option value="<?php echo $value['id'];?>"><?php echo $value['user_fname'].' '.$value['user_lname']; ?></option>
							<?php
						}
						echo '</optgroup>';
					}
					if(isset($subscriber_details) && is_array($subscriber_details) && count($subscriber_details)>0) {
						echo '<optgroup label="Subscribers">';
						foreach($subscriber_details as $key => $value) {
							?>
							<option value="<?php echo $value['id'];?>"><?php echo $value['user_fname'].' '.$value['user_lname']; ?></option>
							<?php
						}
						echo '</optgroup>';
					}?>
				</select>
				<input type='hidden' class="emp_share" id='subscr_code' name="subscr_code" value='register' >
				<input type='hidden' class="emp_share" id='sub_id' name="sub_id" value='' >
				<input type='hidden' class="emp_share" id='manager_id' name="manager_id" value='' >
				<input type='hidden' class="emp_share" id='trainer_id' name="trainer_id" value='' >
				<input type='hidden' class="emp_share" id='register_id' name="register_id" value='' >
					<!--input type='button' onclick='get_append_data()' value='Click'-->
				<a href='javascript:void(0);' id='ad_filter' style='float:right;font-size:10px' onclick='fiters_user()' >Advanced Filter Options</a>
          </div>
          <div class="form-group">
            <label for="message-text" class="control-label">Message:</label>
            <textarea class="form-control" id="message"></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
		   <input type='hidden' id='from_wkout' name='from_wkout' value='myworkout' >
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick='saveshare()'>Share</button>
      </div>
    </div>
	</div>
  </div>
</div>


 <!-- more Modal  -->
<div class="modal fade" id="moreModal" tabindex="-1" role="dialog">
  <div class="vertical-alignment-helper">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Status</h4>
		  <?php
		  
		  ?>
      </div>
      <div class="modal-body">
        <form>
           <div class="form-group">
            <label for="workout-name" class="control-label">Status: </label>           
            <!--input  type='text' class="form-control wkout_id" id="wkout_id" name= 'wkout_id'-->
				<select id='wk_status' class="wk_status form-control select2-hidden-accessible" style="width: 100%;"  style="width:350px;" tabindex="4">
					
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
            <label for="recipient-name" class="control-label">Featured:</label>
				<select id='featured' class="featured form-control fullwidth select2-hidden-accessible" style="width: 100%;"  style="width:350px;" tabindex="4">			
					<option value="0">No</option>
					<option value="1">Yes</option>
					
				</select>
				<input type='hidden' class="wkoutid" id='wkoutid' name="wkoutid" value='' >
				<!--
				<input type='hidden' class="wkfilter_val" id='wkfilter_val' name="wkoutid" value='<?php if(isset($wkoutfilter)){ foreach($wkoutfilter as $keys=> $values) { if ($values ==1){echo '1';}}} ?>' > -->
				
				
          </div>
        </form>
      </div>
      <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="updatestatus('wkout')">Save</button>
      </div>
    </div>
  </div>
  </div>
</div>


<?php /***Recipients Filter Model***/ ?>
<div class="modal fade" id="recipientsfilterModal" tabindex="-1" role="dialog">
  <div class="vertical-alignment-helper">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <!--button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button-->
		  <i class="fa fa fa-angle-left " data-dismiss="modal" aria-label="Close" style='cursor: pointer;' ></i>
        <center><h4 class="modal-title" id="exampleModalLabel">Select Recipients</h4></center>
		  <?php
		  
		  ?>
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
            <!--input type='hidden' class="form-control fullwidth recipient-name" id="recipient-name" style="width: 100%;" value="34:Donnie Darko,54:Heat,27:No Country for Old Men"  /-->
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
            	<!--input type='hidden' class="form-control fullwidth recipient-name" id="recipient-name" style="width: 100%;" value="34:Donnie Darko,54:Heat,27:No Country for Old Men"  /-->
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

<form name='duplicatewkout' id='duplicatewkout' method='post' action="<?php echo URL::base().'admin/workout/copyworkout'; ?>">
<input type="hidden" name='workout_type' id='workout_type' value=''>
<input type="hidden" name='workout_id' id='workout_id'>
<input type="hidden" name='f_method' id='f_method' value='up'>
</form>	

 <div id="tagmodal" class="modal fade" tabindex="-1" role="dialog">
	<div class="vertical-alignment-helper">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Tag Workout:<span id='wkout_modal_title'></span></h4>
          </div>
          <div class="modal-body">            
					<div class="form-group">
					  <label for="recipient-name" class="control-label">Choose Tags:</label>
					  <input type="hidden" class="form-control tagnames" name="tagnames" placeholder="Choose Tags" value="" id="tagnames" style="width:100%" />
					</div>					
          </div>
          <div class="modal-footer"><input type="hidden" name="cwkid" id="cwkid">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary addwkouttags">Save changes</button>
          </div>
        </div>
      </div>
    </div>
</div>
 <?php require_once(APPPATH.'views/templates/admin/workoutdetails.php');?>
 <?php require_once(APPPATH.'views/pages/Admin/Workout/workout_modals.php');?> 
 <!-- Email Report Modal Start-->
<div class="modal fade" id="EmailModal" tabindex="-1" role="dialog">
<div class="vertical-alignment-helper">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Enter Email to get report</h4>
      </div>
      <div class="modal-body">
	  <div class="response" style="font-size:15px;"></div>
        <form name="email_rep" id="email_report_frm" onsubmit="return email_workout_report_submit()">
				<label>Email Address:</label>
				<input type="email" required name="email_address" id="email_address" value="" class="form-control" />
				<input type="hidden" name="wkoutIds" id="wkoutIds" value="" />
				<input type="hidden" name="wkouttype" id="wkouttype" value="" />
				<input type="hidden"  name="roleid" id="roleid" value="" />
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <input class="btn btn-primary" type="submit" name="email_report" value="Email Report" />
      </div>
	  
	  </form>
    </div>
  </div>
  </div>
</div>

<style>.moteactions { max-width: 250px; display: block; }</style>
<input type='hidden' id='wkt_type' value='wkout'>
<input type="hidden" id="newlyAddedXr" name="newlyAddedXr" value="0"/>
<!-- Export -->
<div class="modal fade" id="exportModal" tabindex="-1" role="dialog" aria-labelledby="exportModal">
	<div class="vertical-alignment-helper">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<div class="row">
					<div class="modal-title">
						<div class="col-xs-2">
							<a class="triangle" onclick="$('#exportModal').modal('hide');" href="javascript:void(0);">
									<i class="fa fa-chevron-left"></i>
							</a>
						</div>
						<div class="col-xs-8 ratetitle"><?=__("Options for this My workout plan Export")?></div>
						<div class="col-xs-2 save-icon-button bluecol"></div>
					</div>
					</div>
				</div>
				<div class="modal-body ratebody">
				<input type='hidden' id='selected_ids'>
			<div class="row opt-row-detail">
				<div class="col-xs-12">
					
					<a onclick="$('#exportModal').modal('hide');listexport('excel');" class="btn btn-default"	style="width:100%" href="javascript:void(0);">
						<div class="col-xs-2"><i class="fa fa-file-excel-o iconsize"></i></div>
						<div class="col-xs-8"><?php echo __('By Excel'); ?></div>
					</a>
				</div>
			</div>
			<div class="row opt-row-detail">
				<div class="col-xs-12">
					<a class="btn btn-default" style="width:100%" data-flagtype="bypdf" onclick="$('#exportModal').modal('hide');listexport('pdf')" href="javascript:void(0)" >
						<div class="col-xs-2"><i class="fa fa-file-pdf-o iconsize"></i></div>
						<div class="col-xs-8"><?php echo __('By PDF'); ?></div>
					</a>
				</div>
			</div>
			<div class="row opt-row-detail">
				<div class="col-xs-12">
					<a class="btn btn-default" style="width:100%" onclick="$('#EmailModal input#wkoutIds').val($('input#selected_ids').val());$('#exportModal').modal('hide');$('#EmailModal').modal('show');" href="javascript:void(0)">
						<div class="col-xs-2"><i class="fa fa-envelope-o iconsize"></i></div>
						<div class="col-xs-8"><?php echo __('By Mail'); ?></div>
					</a>
				</div>
			</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Export -->