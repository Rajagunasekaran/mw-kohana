	<!--- Top nav && left nav--->
	<?php echo $topnav.$leftnav;?>
	 <?php require_once(APPPATH.'views/templates/admin/usermodal.php');
	  
	  ?>
	<!--- Top nav && left nav --->
      <!-- Content Wrapper. Contains page content -->
      <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                           <?php $session = Session::instance();
							//if($session->get('current_site_name')!=''){echo '"'.$session->get('current_site_name').'"';}?> <?php echo (isset($site_language['Browse Managers'])) ? $site_language['Browse Managers'] : 'Browse Managers'; ?>
                        </h1>
						
                        <ol class="breadcrumb">
                            <li>
                                <i class="fa fa-dashboard"></i>  <a href="<?php echo URL::base().'admin/index'; ?>"><?php echo (isset($site_language['Dashboard'])) ? $site_language['Dashboard'] : 'Dashboard'; ?></a>
                            </li>
                            <li class="active">
                                <i class="fa fa-edit"></i> <?php echo (isset($site_language['Managers'])) ? $site_language['Managers'] : 'Managers'; ?> <?php echo (isset($site_language['List'])) ? $site_language['List'] : 'List'; ?>
                            </li>
                        </ol>
                    </div>
                </div>
				<div id="mes_suc" class="row" style="display:none;">
					<div class="col-lg-12">
						<div class="alert alert-success">
						  <i class="fa fa-check"></i><span></span>
						</div>
					</div>
				</div>
                <!-- /.row -->
				<?php if(isset($success) && $success!='') {  ?>
				<div class="row">
					<div class="col-lg-12">
						<div class="alert alert-success">
						  <i class="fa fa-check"></i><span><?php echo $success;?></span>
						</div>
					</div>
				</div>
			<?php } ?>
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
                   <div class="col-xs-8 col-lg-8 subscribedropdown downloadusercontainer">
						<div class="subscribeTitle col-lg-12">
							<?php if(isset($template_details) && is_array($template_details) && count($template_details)>0) { echo count($template_details); } ?>
							Manager(s)
 						</div>
						<div class="subscribedropdown downloadusercontainer">
							<select name="right manageraction[]" id="manageraction" class="moteactions selectAction">
								<option value=""><?=__("Select an Option")?></option>
								<option value="addmanager"><?=__("Add Manager")?></option>
								<option value="export"><?=__("Export this list")?></option>
								<option value="exportselected"><?=__("Export selected")?></option>
								<option value="sendemail"><?=__("Send Email")?></option>
							</select>
							<span class="subscribedownarrow" id="advancsearch" onclick="showAdvanceSearch();" style="line-height:5px;">
								<i class="fa fa-caret-down search-filter iconsize" data-class="filter_this"></i>
							</span>
						</div>					
					</div>
				</div>
				
				<?php if(Helper_Common::is_admin()) { ?>
				<div class="row">
				<div class="col-sm-12 advancesubscribersearch">
				<div style="border: 1px solid rgb(221, 221, 221); display:none;" class="advance-search-contnr">
						<div class="col-lg-12">
							<h3>Advanced Search</h3>
							<form class="" action="<?php echo URL::base().'admin/manager/browse';?>" method="post">
								
								<div class="form-group">
									<label for="workout-name" class="control-label">Filter by Site Name:</label>           
								   <select    id='site_list' name='site_list[]' class="site_list form-control fullwidth select2-hidden-accessible" style="width: 100%;" multiple>
										<option value=""></option>
										<?php
										if(isset($site_list) && is_array($site_list) && count($site_list)>0) {
											foreach($site_list as $key => $value) {
												?>
												<option value="<?php echo $value['id'];?>"><?php echo $value['name']; ?></option>
												<?php
											}
										}?>
									</select>
								</div>
								
								<div class="form-group">
									<label for="workout-name" class="control-label">Filter by Recipient Name:</label>           
								   <select    id='subscribername' name='subscribername[]' class="subscribername form-control fullwidth select2-hidden-accessible" style="width: 100%;" multiple>
										<option value=""></option>
										<?php
										if(isset($template_details) && is_array($template_details) && count($template_details)>0) {
											foreach($template_details as $key => $value) {
												?>
												<option value="<?php echo $value['id'];?>"><?php echo $value['user_fname'].' '.$value['user_lname']; ?></option>
												<?php
											}
										}?>
									</select>
								</div>
								<!--
								<div class="clearfix"></div>
								<div class="form-group">
									<label for="workout-name" class="control-label">Filter by existing Recipient Tags:</label>           
								   <select   id='tag_id'  class="tag_id form-control fullwidth select2-hidden-accessible" style="width: 100%;" multiple>
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
								</div> -->
								<div class="clearfix"></div>
								<div class="form-group">
									<label for="workout-name" class="control-label">Filter by Gender:</label>           
								   <select id='gender'  name='gender' class="gender form-control fullwidth select2-hidden-accessible" style="width: 100%;" tabindex="4">
										<option value="">All</option>
										<option value="1">Male</option>
										<option value="2">Female</option>
									</select>
								</div>
								<div class="form-group">
									<label for="recipient-name" class="control-label">Filter by Age (between):<span id='setage'>15 - 122</span></label>
									<!--<input type="range" name="setagerange" id="setagerange" value="50" min="15" max="122" data-popup-enabled="true">-->
									<input type='hidden' id='setagerange' name='setagerange' value='15-122'><div id="agerange"></div>										
								</div>
								<div class="subscribefetchbtn topsearchbtn">
									<!--<button class="btn btn-default" type="button" onclick="getAdvanceSearchRecords()">Fetch Records</button>-->
									<input type="hidden" value="" name="pageval">
									<input type="submit" value="Fetch Records" id="subscribersubmit" id="subscribersubmit" class="btn btn-default btncol fetch-record">
									<input type="reset" class="btn btn-default" id="Reset" value="Reset" onclick="window.location.href='<?php echo URL::base()."admin/manager/browse"; ?>'"/>
								</div>
							</form>
							
						</div>
				</div>
				</div>
				</div>
				
				<?php } ?>
                <div class="row">
				<?php //print_r($SmtpDetails); ?>
				
                <div class="usersmanagersection">
                        
						<div class="col-lg-6 col-xs-6 downloadusercontainer">
							
							   <!--<a data-target="#EmailModal" data-toggle="modal" href="#" class="btn btn-primary"><i class="fa fa-user"></i> Email</a>
							   <a class="btn btn-primary downloaduser" href="<?php echo URL::site('admin/user/get_user_report_as_excel/'.$roleid.'');?>" ><i class="fa fa-user"></i> Excel</a>
							   <a class="btn btn-primary downloaduser" href="<?php echo URL::site('admin/user/get_userlist_pdf/'.$roleid.'');?>" target="_blank" ><i class="fa fa-user"></i> PDF</a>-->
							   <!--a class="btn btn-default downloaduser" href="<?php echo URL::base().'admin/user/create/manager';?>" >Add Manager</a-->
							   
							
						</div>
                        <?php if(isset($template_details) && count($template_details)>0) { 
                            $gender_array = Helper_Common::genderArray(); //echo "<pre>"; print_R($template_details); echo "</pre>";
                        ?>
                        <div class="table-responsive col-lg-12">
                            <table class="table table-bordered table-hover table-striped dataTable">
                                <thead>
                                    <tr>
									    <th class="chkbox-header"><input type="checkbox"  name="row_index[]" id="select-all" /></th>
                                        <th><?php echo (isset($site_language['Name'])) ? $site_language['Name'] : 'Name'; ?></th>
										<!--
                                        <th><?php //echo (isset($site_language['Email'])) ? $site_language['Email'] : 'Email'; ?></th>
										<th><?php //echo (isset($site_language['Gender'])) ? $site_language['Gender'] : 'Gender'; ?></th> -->
										
										<th><?php echo (isset($site_language['sites(s)'])) ? $site_language['Sites(s)'] : 'Sites(s)'; ?></th>
										<!--<th>View Assign Sites</th>
										<th>Edit</th>-->
										<th><?php echo (isset($site_language['Status'])) ? $site_language['Status'] : 'Status'; ?></th>
										<!--th><?php echo __("Contact Status"); ?></th-->
                                        <th><?php echo (isset($site_language['Action'])) ? $site_language['Action'] : 'Action'; ?></th>
                                    </tr>
                                </thead>
                                <tbody>
								<?php foreach($template_details as $key => $value) { 
									$user_email = '';
									if(isset($value['user_email']) && $value['user_email']!='' && isset($value['user_mobile']) && $value['user_mobile']!='') {
										$user_email = $value['user_email'].' / '.$value['user_mobile'];
									} else if(isset($value['user_email']) && $value['user_email']!='') {
										$user_email = $value['user_email'];
									} else if(isset($value['user_mobile']) && $value['user_mobile']!='') {
										$user_email = $value['user_mobile'];
									}
									?>
								    <tr id="row-<?php echo $value['id'];?>">
									    <td class="tabl-chkbox checkselect"><input type="checkbox" name="row_index[]" class="chkbox-item managerselect" value="<?php echo $value['id'];?>" /></td>
                                        <td>
										<a href="javascript:void(0);" onclick="showUserModel('<?php echo $value['id'];?>',1)" id="username_<?php echo $value['id'];?>"><?php echo $value['user_fname'].' '.$value['user_lname']; ?></a> </td>
										
										<!--
										<td><?php //echo $user_email; ?></td>
										<td><?php //echo ucfirst($gender_array[$value['user_gender']]); ?></td> -->
										<td><?php $sites_list = Helper_Common::get_sites_by_subscribers($value['id']);
											echo $sites_list[0]['sites_list'];
										//echo $value['sites_list']; ?></td>
										<!--<td><a href="<?php //echo URL::base().'admin/user/assignSitesToManager/'.$value['id']; ?>">Manage</a></td>-->
										<!--<td><a href="<?php //echo URL::base().'admin/user/viewAssignSitesToManager/'.$value['id']; ?>">View Assign Sites</a></td>
										<td><a href="<?php echo URL::base().'admin/user/edit/'.$value['id']; ?>"><i class="fa fa-edit"></i></a></td>-->
										<td class="statusupdate-<?php echo $value['id'];?>"><?php echo $value['userstatus'];?></td>
                              <!--td  align="center">
									<input class="contact_status" id="mybutton" type="checkbox" data-tt-size="big" data-tt-palette="blue" value="<?php echo $value['id']; ?>"
									 <?php echo (Helper_Common::get_contact_status($value['id'], $current_site_id)==1)?"checked='checked'":""; ?> >
								</td-->
										          <?php if(isset($status_array) && count($status_array)>0) { ?>
										
										<td>
											<select name="manager_status" class="form-control selectAction" onchange="changeManagerStatus('<?php echo $value['id'];?>',this.value);">
											        <option value="" ></option>
											        <option value="<?php echo "edit-".$value['id'];?>" >Edit</option>
												<?php foreach($status_array as $status_key => $status_value) { ?>
													<option value="<?php echo $status_value['id'];?>" <?php //if($status_value['id']==$value['status']){ echo 'selected';} ?>><?php echo $status_value['status'];?></option>
												<?php } ?>
													
												     <option value="sendemail">Send Email</option>
												     <option value="sitesmanage">Manage Sites</option>
													  <option value="contact_status"><?php echo __("Contact Status"); ?></option>
											</select>
										</td>
										<?php } ?>
                                    </tr>

								<?php } ?>	
									
                                </tbody>
                            </table>
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



<?php
if($template_details && is_array($template_details) && count($template_details)>0){
		/*
?>


<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal" id="deleteModalBtn">Delete</button>
<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myassignmanage" id="assignsitesModalBtn">Manage</button>
<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#changeStatusModal" id="assignsitesModalBtn" style="display:none;">Manage</button>
<?php


<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <p>Are you sure you want to delete?</p>
                <input type="hidden" name="user_idjs" id="user_idjs" value="0">
                <button type="button" class="btn btn-danger" id="yesDelete" onclick="deleteUser()">Yes</button>
                <button type="button" class="btn btn-primary" id="noDelete" data-dismiss="modal">No</button>
            </div>
        </div>

    </div>
</div>

<!-- Modal -->
*/
} ?>

<div id="assignsitemanager" class="modal fade" tabindex="-1" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Assign Site</h4>
          </div>
          <div class="modal-body">            
                   <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-2">
                                <p><img src="<?php echo URL::site('assets/img/user_placeholder.png');?>" width="50" height="50"/></p>
                            </div>
                            <div class="col-md-10">                                
                                <p><strong>Name : </strong><span class="user_name"></span></p>                               
                                <p><strong>Email : </strong><span class="user_email"></span></p>
                            </div>
                       </div>
                    </div>
                  <div class="form-group">
                    <label for="recipient-name" class="control-label">Choose Sites:</label>
                    <input type="hidden" class="form-control mangersites" name="mangersites" placeholder="Choose Sites" value="" id="mangersites" style="width:100%"   <?php if(!(Helper_Common::is_admin())) {  echo "disabled = true"; }?> />
                   
                 </div>
          </div>
          <div class="modal-footer"><input type="hidden" name="curid" id="curid">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary addsitetomanager">Save changes</button>
          </div>
        </div>
      </div>
    </div>



<div id="contactsitemanager" class="modal fade" tabindex="-1" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Contact Site</h4>
          </div>
          <div class="modal-body">
				<label for="recipient-name" class="control-label">Choose Sites:</label>
				<div class='form-group siteslist'>
					<?php /*
					<div class='row'>
						<div class="col-xs-6">
							<label for="square-radio-1">TEst</label></li>
						</div>
						<div class="col-xs-6"><input class="contact_status" id="mybutton" type="checkbox" data-tt-size="big" data-tt-palette="blue" value=""></div>
					</div>
					*/
					?>
				</div>
          </div>
          <div class="modal-footer"><input type="hidden" name="curid" id="curid">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <!--button type="button" class="btn btn-primary addsitetomanager">Save changes</button-->
          </div>
        </div>
      </div>
    </div>
    
<div id="changeStatusModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <p>Are you sure you want to change status?</p>
                <input type="hidden" name="user_idjs" id="user_idjs" value="0">
                <button type="button" class="btn btn-danger" id="yesDelete" onclick="deleteUser()">Yes</button>
                <button type="button" class="btn btn-primary" id="noDelete" data-dismiss="modal">No</button>
            </div>
        </div>

    </div>
</div>

<!-- Email Report Modal Start-->
<div class="modal fade" id="EmailModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
<div class="vertical-alignment-helper">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Enter Email to get report</h4>
      </div>
      <div class="modal-body">
	  <div class="response" style="font-size:15px;"></div>
        <form name="email_rep" id="email_report_frm" onsubmit="return email_report_submit()">
				<label>Email Address:</label>
				<input type="email" required name="email_address" id="email_address" value="" class="form-control" />
				<input type="hidden"  name="roleid" id="roleid" value="<?php echo $roleid;?>" />
				<input type="hidden"  name="uid" id="uid" value="" />		
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
<!-- Email Report Modal end-->
<!-- Email send Modal Start-->
<div id="emailmodal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog">
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title">Send Email to <span class="sendto"></span></h4>
		<div class="alert alert-success statusmessage successsend" ></div>
	  </div>
	  <div class="modal-body">            
			  <div class="form-group">
				<label for="recipient-name" class="control-label">Subject:</label>
				<input type="text" class="form-control emailsubject" name="emailsubject" placeholder="Enter Subject" id="emailsubject"  />
			   
			 </div>
			  <div class="form-group">
				<label for="recipient-name" class="control-label">Message:</label>
				<?php echo $editor->editor('emailmessage'); ?>
			   
			 </div>
			 <div class="form-group">
			   <input type="hidden" value="" name="currentmail"  id="currentmail" />
			   
			 </div>
	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		<button type="button" class="btn btn-primary senduseremail">Send Email</button>
	  </div>
	</div>
  </div>
</div>
<!-- Email send Modal end-->
</body>
<div id="xrprev-modal" class="modal fade" role="dialog"></div>
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
						<div class="col-xs-8 ratetitle"><?=__("Options for this Manager List Export")?></div>
						<div class="col-xs-2 save-icon-button bluecol"></div>
					</div>
					</div>
				</div>
				<div class="modal-body ratebody">
				<input type='hidden' id='selected_ids'>
				<input type='hidden' id='selected_roles'>	
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
					<a class="btn btn-default" style="width:100%" onclick="$('#EmailModal input#uid').val($('input#selected_ids').val());$('#exportModal').modal('hide');$('#EmailModal').modal('show');"  href="javascript:void(0)">
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
<script>
$(document).ready(function() {
$("#agerange").slider({
      range: true,
      min: 15,
      max: 122,
      values: [15, 122],
      slide: function(event, ui) {
         $("#setagerange").val(ui.values[0] + "-" + ui.values[1]);
         $("#setage").html(ui.values[0] + " - " + ui.values[1]);
      }
   });
   });
</script>