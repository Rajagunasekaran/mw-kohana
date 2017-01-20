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
										
										<?php
											if($default_status==0){
												$session = Session::instance();
												if($session->get('current_site_name')!=''){echo '"'.$session->get('current_site_name').'"';}
											}	
										?> 
										
										<?php
										if($default_status==0){
											echo (isset($site_language['Sample Workout Records'])) ? $site_language['Shared Workout Records'] : 'Shared Workout Records';
										}else{
											echo (isset($site_language['Default Workout Records'])) ? $site_language['Default Workout Records'] : 'Default Workout Records';
										}	
										?>
                           
                        </h1>
						
                        <ol class="breadcrumb">
                            <li>
                                <i class="fa fa-dashboard"></i>  <a href="<?php echo URL::base().'admin/index'; ?>">Dashboard</a>
                            </li>
                            <li class="active">
                                <i class="fa fa-edit"></i> <?php echo ($default_status==0)?"Workout Shared Records List":"Workout Default Records List"; ?>
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
					<?php if(isset($template_details_all) && count($template_details_all)>0) { echo count($template_details_all); }?> 
					<?php echo (isset($site_language['Workout Record(s)'])) ? $site_language['Workout Record(s)'] : 'Workout Record(s)';?></h2>
					<div class="col-lg-6">
						<!--a class="btn btn-default shareworkout" href="javascript:void(0);" onclick='check_options("shareModal")' style="float:right;">Share Workout</a--> 
						
						</div>
				</div>
				<div class="gallery-div">
				
					
						
					<?php $src_url = URL::base().'admin/workout/shared';
						if($default_status==1){
							 $src_url .= '?d='.$default_status;
						}	  
					?>	
					
					<form class="top-srch-frm" action="<?php echo $src_url;?>" method="post" >
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
					</form>
					
					
					<div class="row gallery-div">
						<div class="topsearch topsearch_white">
							<div class="border" >
								<?php if(isset($workout_details) && count($workout_details)>0) { ?>
								<div class="col-sm-6 showentries">
									<label for="Show">Shows :</label>
									<form class="advnce-srch-frm" action="<?php echo URL::base().'admin/workout/sample';?>" method="get">
									<?php if($default_status==1){ ?><input type='hidden' name='d' value='<?php echo $default_status; ?>'><?php } ?>
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
								</div>
								<?php } ?>
								<div class="col-sm-6"  >
									 
										<?php 
									if(Helper_Common::hasAccess('Manage Workouts')) { ?>
											<select name="right moteactions[]" id="moteactions" class="moteactions selectAction">
												<option value="">Select an Option</option>
												<?php
												/*
												if($default_status==0){?>
												<option value="addsampleworkouts">Create Sample Workout</option>
												<?php
												}*/
												if(isset($workout_details) && count($workout_details)>0) { ?>
												<!--option value="addworkouts">Add Sample Workout Record</option-->
												<!--option value="shareworkout">Share selected</option-->                                               
												<!--option disabled value="deletesharedworkouts">Delete selected </option-->
												<!--option value="tagworkouts">Tag Selected Workouts</option-->
												<?php if($default_status==0){?>
														<!--option value="reportEmail1">Email</option-->
														<?php if (Helper_Common::is_admin()){
																echo '<option value="cpysharedSample">Copy selected to Sample</option>';
																echo '<option value="cpysharedDefault">Copy selected to Default</option>';
														}
												} ?>
												<option value="exportall"><?=__("Export this list")?></option>
												<option value="exportselected"><?=__("Export selected")?></option>
												<?php } ?>
											</select>
									<?php }  ?>
										
								</div>
							</div>
						</div>
					</div>
				
					
					
				</div>	
                <div class="row" style="margin-top:10px;">
				<div class="col-lg-12">
                    <?php if(isset($workout_details) && count($workout_details)>0) { ?>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped"  id='wkouttable'>
                                    <thead>
                                        <tr>
                                            <th class='chkbox-header'><input type="checkbox" name="wkoutselectall" id='wkoutselectall' /></th>
                                            <th>Color</th>		
                                            <th>Workout Title</th>		
                                            <th>Workout Focus</th>	
                                            <th>Folder</th>	
											<th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="table-content-contnr">
                                    <?php 
										foreach($workout_details as $key => $value) { ?>
                                        <tr id="row-<?php echo $value['wkout_id'];?>">
                                            <td class="tabl-chkbox"><input type="checkbox" name="wkoutselect[]" id="wkoutselect" class="wkoutselect" value="<?php echo $value['wkout_id'];?>" /></td>
                                            <td align="center"><div class="colorcode <?php echo strtolower($value['color_title']); ?>"></div></td>
                                            <td><a onclick="viewwkout('<?php echo $value['wkout_id'];?>','previewshared')" href="javascript:void(0);"><?php echo $value['wkout_title'];?></a></td>
											
                                            <td>
																<?php
															foreach($focusRecord as $keys => $values){
																if($values['focus_id'] == $value['wkout_focus'])
																	echo ucfirst($values['focus_opt_title']);
															}
															?>
																
														  </td>
											<td><?php echo $value["folder_title"]; ?></td>
                                            <?php  if(Helper_Common::hasAccess('Manage Workouts')) { ?>
                                            <td>
												<input type="hidden" id="folder_<?php echo $value['wkout_id'];?>" value="<?php echo $value['folder_id'];?>" >
												<select name="wkoutaction[]" id="<?php echo $value['wkout_id'];?>" class="wkoutaction selectAction"
																onchange="goto_action('<?php echo $value['wkout_id'];?>','<?php echo $value['folder_id'];?>',this.value)">
												<option value="">Select an Option</option>
												<option value="Preview">Preview</option>
												<option value="deleteshared">Delete</option>
												<option value="sharedduplicate">Copy to My workout</option>
												<?php if($default_status==0){?>
													<?php if(Helper_Common::is_admin()) { ?>
															<option value="cpysharedSample">Copy to Sample</option>
															<option value="cpysharedDefault">Copy to Default</option>
													<?php } ?>
															<option value="sharedexport">Export</option>
												<?php } ?>
                                            </select>
											</td> 
											<?php }  ?>
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
<div class="modal fade" id="shareModal" tabindex="-1" role="dialog" aria-labelledby="shareModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Share Workout</h4>
		  <?php
		  
		  ?>
      </div>
      <div class="modal-body">
        <form>
           <div class="form-group">
            <label for="workout-name" class="control-label">Workout(s):</label>           
            <!--input  type='text' class="form-control wkout_id" id="wkout_id" name= 'wkout_id'-->
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
				<?php /*
				<select  data-placeholder="Choose a Workouts..." id='wkout_id' class="form-control fullwidth workout-name chosen-select" style="width: 100%;" multiple style="width:350px;" tabindex="4">
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
				*/ ?>
          </div>
          <div class="form-group">
            <label for="recipient-name" class="control-label">Recipient(s):</label>
            <!--input type='hidden' class="form-control fullwidth recipient-name" id="recipient-name" style="width: 100%;" value="34:Donnie Darko,54:Heat,27:No Country for Old Men"  /-->
				<!--select id='subscriber_id' class="subscriber_id form-control fullwidth select2-hidden-accessible" style="width: 100%;" multiple style="width:350px;" tabindex="4">
					<option value=""></option>
					<?php
					if(isset($subscriber_details) && count($subscriber_details)>0) {
						foreach($subscriber_details as $key => $value) {
							?>
							<option value="<?php echo $value['id'];?>"><?php echo $value['user_fname'].' '.$value['user_lname']; ?></option>
							<?php
						}
					}?>
				</select-->
				
				
				
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
           <a href='javascript:void(0);' style='float:right;font-size:10px' onclick='fiters_user()' >Advanced Filter Options</a>
          </div>
          <div class="form-group">
            <label for="message-text" class="control-label">Message:</label>
            <textarea class="form-control" id="message"></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
		  <?php if($default_status==0){
						echo "<input type='hidden' id='from_wkout' name='from_wkout' value='sample-workout' >";
				}else{
						echo "<input type='hidden' id='from_wkout' name='from_wkout' value='default-workout' >";
				}
		  ?>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick='saveshare()'>Share</button>
      </div>
    </div>
  </div>
</div>

<?php /***Recipients Filter Model***/ ?>
<div class="modal fade" id="recipientsfilterModal" tabindex="-1" role="dialog" aria-labelledby="shareModalLabel">
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
					if(isset($subscriber_details) && count($subscriber_details)>0) {
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
            <label for="recipient-name" class="control-label">Filter by Age (between):<span id='setage'></span></label>
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


<form name='duplicatewkout' id='duplicatewkout' method='post' action="<?php echo URL::base().'admin/workout/copyworkout'; ?>" >
<input type="text" name='workout_type' id='workout_type' value=''>
<input type="hidden" name='workout_id' id='workout_id'>
<input type="hidden" name='f_method' id='f_method' value='up'>
<input type="hidden" name='default_status' id='default_status' value=''>
</form>	

 <div id="duplicateModal" class="modal fade" tabindex="-1" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Choose Workout</h4>
          </div>
          <div class="modal-body">            
				<div class="form-group">
					<div class="col-xs-12 navimagedetails">
						<div class="navimgdet1"><b><input type='radio' id="wktype1" name='workout_type' onclick='$("#workout_type").val("sample");' checked='checked'><label for="wktype1" > Duplicate into Sample Workouts</label></b></div>
					</div>
				</div>
				<div class="form-group">
					<div class="col-xs-12 navimagedetails">
						<div class="navimgdet1"><b><input type='radio' id="wktype2" name='workout_type' onclick='$("#workout_type").val("");'> <label for="wktype2" >Duplicate into Workout plans</label></b></div>
					</div>
				</div>
          </div>
          <div class="modal-footer"><input type="hidden" name="cwkid" id="cwkid">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary addwkouttags" onclick='$("#duplicatewkout").submit()'>Save changes</button>
          </div>
        </div>
      </div>
    </div>


 <div id="tagmodal" class="modal fade" tabindex="-1" role="dialog">
      <div class="modal-dialog vertical-alignment-helper">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Tag Workout</h4>
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
 
<!-- Choose Sample from Mywkout & Shared Wkout -->
<div data-backdrop="static" data-keyboard="false" role="dialog" class="modal fade in" id="choose_sample" style="display: none; padding-left: 17px;">
   <div class="addAssignWorkouts vertical-alignment-helper">
     
         <div class="modal-dialog">
            <div class="modal-content">
               <div class="modal-header">
                  <div class="row">
                     <div class="mobpadding">
                        <div class="border">
                           <div class="col-xs-2">
                              <a class="triangle" onclick="$('#choose_sample').modal('hide')" href="javascript:void(0)" data-ajax="false" data-role="none">
                              <i class="fa fa-chevron-left iconsize"></i>
                              </a>
                           </div>
                           <div class="col-xs-8 optionpoptitle">Create Sample Workout Plan</div>
                           <div class="col-xs-2"></div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="modal-body opt-body">
                  <div class="opt-row-detail">
                     <a style="width:100%" onclick="choose_makesamplewkout('mywkout');" href="javascript:void(0)" data-ajax="false" data-role="none">
                        <div class="col-xs-12 pointer">
                           <div class="col-xs-2"><i class="fa fa-folder-o iconsize"></i></div>
                           <div class="col-xs-10">From My Workout Plans</div>
                        </div>
                     </a>
                  </div>
                  <hr>
                  <div class="opt-row-detail">
                     <a style="width:100%" onclick="choose_makesamplewkout('mysharedwkout');" href="javascript:void(0)" data-ajax="false" data-role="none">
                        <div class="col-xs-12 pointer">
                           <div class="col-xs-2"><i class="fa fa-folder-o iconsize"></i></div>
                           <div class="col-xs-10">From Shared Workout Plans</div>
                        </div>
                     </a>
                  </div>
                  <hr>
                  <div class="opt-row-detail">
                     <a style="width:100%" onclick="createNewworkout()" href="javascript:void(0)" data-ajax="false" data-role="none">
                        <div class="col-xs-12 pointer">
                           <div class="col-xs-2"><i class="fa fa-plus iconsize"></i></div>
                           <div class="col-xs-10">Create Custom Plan</div>
                        </div>
                     </a>
                  </div>
               </div>
               <div class="modal-footer"><button data-dismiss="modal" class="btn btn-default" type="button" data-role="none" data-ajax="false">Cancel</button></div>
            </div>
         </div>
      
   </div>
</div>
<div data-backdrop="static" data-keyboard="false" role="dialog" class="modal fade in" id="listwkouts" style="display: none; padding-left: 17px;">
	<div class="addAssignWorkouts">
      <form data-ajax="false"  method="post" action="">
         <div class="modal-dialog">
            <div class="modal-content">
               <div class="modal-header">
                  <div class="row">
                     <div class="mobpadding">
                        <div class="border">
                           <div class="col-xs-2">
                              <a class="triangle" onclick="$('#listwkouts').modal('hide')" href="javascript:void(0)" data-ajax="false" data-role="none">
                              <i class="fa fa-chevron-left iconsize"></i>
                              </a>
                           </div>
                           <div class="col-xs-8 optionpoptitle">Create Sample Workout Plan</div>
                           <div class="col-xs-2 save-icon-button bluecol">
										<button class="btn" style="background-color:#fff" name="f_method1" id="f_method1" onclick="checklist()"  data-ajax="false" type="button" data-role="none" value=''>
											<i class="fa fa-check-square-o" data-toggle="collapse" style="font-size:30px;"></i>
										</button>
									</div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="modal-body opt-body getwkoutlists">
						
					</div>
               <div class="modal-footer"><button data-dismiss="modal" class="btn btn-default" type="button" data-role="none" data-ajax="false">Cancel</button></div>
            </div>
         </div>
      </form>
   </div>	
</div>
<!-- Choose Sample from Mywkout & Shared Wkout --> 
 <div id="FolderModal" class="modal fade" role="dialog" tabindex="-1"></div>
 <?php require_once(APPPATH.'views/templates/admin/workoutdetails.php');?> 
<?php require_once(APPPATH.'views/pages/Admin/Workout/workout_modals.php');?> 
 <!-- Email Report Modal Start-->
<div class="modal fade" id="EmailModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
				<input type="hidden"  name="roleid" id="roleid" value="<?php //echo $roleid;?>" class="form-control" />
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <input class="btn btn-primary" type="submit" name="email_report" value="Email Report" />
      </div>
	  
	  </form>
    </div>
  </div>
</div>

 <!-- more Modal  -->
<div class="modal fade" id="moreModal" tabindex="-1" role="dialog" aria-labelledby="shareModalLabel">
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
        <button type="button" class="btn btn-primary" onclick="updatestatus('wkoutsample')">Save</button>
      </div>
    </div>
  </div>
</div>
<!-- Email Report Modal Start-->
<div id="myModal" class="modal fade" role="dialog"></div>




<!-- Set as default -->
<div class="modal fade" id="setsampledefaultModal" tabindex="-1" role="dialog" aria-labelledby="setsampledefaultModal">
		<div class='vertical-alignment-helper'>
	<div class="modal-dialog " role="document">
		<div class="modal-content">
			<div class="modal-header">
				<div class="row">
				<div class="popup-title">
				<div class="col-xs-3">
				<a class="triangle" data-dismiss="modal" href="javascript:void(0);" data-ajax="false" data-role="none">
				<i class="fa fa-caret-left iconsize"></i>
				</a>
				</div>
				<div class="col-xs-6">Set Default Sample Workout Plan</div>
				<div class="col-xs-3 save-icon-button bluecol">
				<button class="btn" style="background-color:#fff" name="f_method" onclick="set_sample_submit();" data-ajax="false" type="button" data-role="none">
				<i class="fa fa-check-square-o" data-toggle="collapse" style="font-size:30px;"></i>
				</button>
				<input id="save_edit" type="hidden" value="1" name="save_edit">
				</div>
				</div>
				</div>
			</div>
			<div class="modal-body">
				<form>
					<div class="aligncenter"><span class="errormsg"></span></div>
					<div class="form-group">
						<label for="workout-name" class="control-label">Select Exercise Records:</label>           
						<select id='de_sample_id' class="de_sample_id form-control fullwidth select2-hidden-accessible" style="width: 100%;" multiple style="width:350px;" tabindex="4">
							<option value=""></option>
							<?php
							foreach($template_details_all as $key => $v) {
								echo "<option value='".$v["wkout_id"]."'>".$v["wkout_title"]."</option>";
							}
							?>
						</select>
						<input type='hidden' value='1' name='default_status' id='default_status'  >
					</div>
				</form>
			</div>
			<!--div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div-->
		</div>
	</div>
</div>
		</div>
<!-- Set As Default -->
<input type='hidden' id='wkt_type' value='shared'>
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
					<a class="btn btn-default" style="width:100%" onclick="$('#EmailModal input#wkoutIds').val($('input#selected_ids').val());$('#exportModal').modal('hide');$('#EmailModal').modal('show');"  href="javascript:void(0)">
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