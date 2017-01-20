	<!--- Top nav && left nav--->
	<?php echo $topnav.$leftnav;?>
	 <?php require_once(APPPATH.'views/templates/admin/usermodal.php');
	  
	  ?>
	<?php //require_once(APPPATH.'views/templates/front/imglib-imgeditor.php');
	echo $imgeditor2; ?>
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
							$session = Session::instance();
							if($session->get('current_site_name')!=''){echo '"'.$session->get('current_site_name').'"';}?> 
							<?php
							echo (isset($site_language['Subscribers'])) ? $site_language['Subscribers'] : 'Subscribers'; 
							?>  
                        </h1>
						
                        <ol class="breadcrumb">
                            <li>
                                <i class="fa fa-dashboard"></i>  <a href="<?php echo URL::base().'admin/index'; ?>">Dashboard</a>
                            </li>
                            <li class="active">
                                <i class="fa fa-edit"></i> Subscribers List 
								
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
				
                <div class="row">
                    <?php //print_r($SmtpDetails); ?>
                    <div class="subscribersearch">
						<div class="subscribeTitle col-lg-12"> 
							<?php if(isset($template_details) && count($template_details)>0) { echo count($template_details); }?> 
							<?php
							echo (isset($site_language['Subscriber(s)'])) ? $site_language['Subscriber(s)'] : 'Subscriber(s)'; 
							?> 
						</div>
						<?php if($datatable==0){?>
								<div class="col-sm-3 showentries">
									<label for="Show">Shows :</label>
									<form class="" action="<?php echo URL::base().'admin/subscriber/browse';?>" method="get">
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
						<div class="col-xs-8 col-lg-8 subscribedropdown downloadusercontainer">
							<input type="hidden" value="<?php echo $roleid;?>" id="roleid"/>
							<!--<a data-target="#EmailModal" data-toggle="modal" href="#" class="btn btn-primary"><i class="fa fa-user"></i> Email</a>
							<a class="btn btn-primary downloaduser" href="<?php echo URL::site('admin/user/get_user_report_as_excel/'.$roleid.'');?>" ><i class="fa fa-user"></i> Excel</a>
							<a class="btn btn-primary downloaduser" href="<?php echo URL::site('admin/user/get_userlist_pdf/'.$roleid.'');?>" target="_blank" ><i class="fa fa-user"></i> PDF</a>-->
							<select name="right moteactions[]" id="moteactions" class="moteactions selectAction">
								<option value=""><?=__("Select an Option")?></option>
								<option value="addsubscriber"><?=__("Add Subscriber")?></option>
								<option value="shareworkout"><?=__("Share Workout")?></option>
								<option value="tagusers"><?=__("Tag Selected Users")?></option>
								<option value="export"><?=__("Export this list")?></option>
								<option value="exportselected"><?=__("Export selected")?></option>
								<option value="sendemail"><?=__("Send Email")?></option>
							</select>
						
						<!--div >
								<a class="btn btn-default"  href="<?php echo URL::base().'admin/user/create/register';?>">Add Subscriber</a>&nbsp;&nbsp;
						</div-->
							<div class="subscribedownarrow" id="advancsearch" onclick="showAdvanceSearch();">
								<i class="fa fa-caret-down search-filter iconsize" data-class="filter_this"></i>
							</div>
						</div>
						
						
					</div>
				</div>
				<div class="row">
				<div class="col-sm-12 advancesubscribersearch">
				<div style="border: 1px solid rgb(221, 221, 221); display:none;" class="advance-search-contnr">
						<div class="col-lg-12">
							<h3>Advanced Search</h3>
							<form class="" action="<?php echo URL::base().'admin/subscriber/browse';?>" method="post">
								<div class="form-group">
									<label for="workout-name" class="control-label">Filter by Recipient Name:</label>           
								   <select    id='subscribername' name='subscribername[]' class="subscribername form-control fullwidth select2-hidden-accessible" style="width: 100%;" multiple>
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
								</div>
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
									<input type='hidden' id='setagerange' name='setagerange' value='15-122'><div id="agerange"></div>										
								</div>
								<div class="subscribefetchbtn topsearchbtn">
									<!--<button class="btn btn-default" type="button" onclick="getAdvanceSearchRecords()">Fetch Records</button>-->
									<input type="hidden" value="" name="pageval">
									<input type="submit" value="Fetch Records" id="subscribersubmit" id="subscribersubmit" class="btn btn-default btncol fetch-record">
									<input type="reset" class="btn btn-default" id="Reset" value="Reset" onclick="window.location.href='<?php echo URL::base()."admin/subscriber/browse"; ?>'"/>
								</div>
							</form>
							
						</div>
				</div>
				</div>
				</div>
				<div class="row">
				<div class="col-lg-12">
				
						<?php if(isset($template_details) && count($template_details)>0) { 
                           
							$gender_array = Helper_Common::genderArray();
                        ?>
                        <div class="table-responsive subscriber-dynamiclist">
                            <table id="suscribeTable" class="table table-bordered table-hover table-striped <?php echo ($datatable==0)?"":"dataTable"; ?> " <?php echo ($datatable==0)?"":"style='width:98%'"; ?>> 
                                <thead>
                                    <tr>
                                        <th class="chkbox-header" style="width: 20px;"><input type="checkbox"  name="row_index[]" id="select-all" /></th>
										<th>Name</th>                         
										<th>Gender</th>
										<th>Last login</th>
                                        <th>Registered</th>
                                        <th>Activated</th>
                                        <th>Status</th>
                                        <th>Tags</th>
										<th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php 
								foreach($template_details as $key => $value) { 
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
                                        <td class="tabl-chkbox checkselect"><input type="checkbox" name="row_index[]" class="chkbox-item subscribeselect" value="<?php echo $value['id'];?>" /></td> 
										<td><a href="javascript:void(0);" onclick="showUserModel('<?php echo $value['id'];?>',1)" id="username_<?php echo $value['id'];?>"><?php echo $value['user_fname'].' '.$value['user_lname']; ?></a></td>
                                        
										<td><?php //echo ucfirst($gender_array[$value['user_gender']]);
										
										foreach($gender_array as $k=>$v){
												if($k==$value['user_gender']){
														echo ucfirst($v);
												}
										}
										?></td>
										
                                      	<td class="dateformatted"><?php echo ($value['last_login'] != "0000-00-00 00:00:00" ? date("j M Y",strtotime($value['last_login'])) : date("j M Y",strtotime($value['date_created'])));?></td>
                                        <td class="dateformatted"><?php echo date("j M Y",strtotime($value['date_created']));?></td>
										<td class="dateformatted"><?php echo date("j M Y",strtotime($value['date_created']));?></td>
										<td class="user-status"><?php echo $value['userstatus'];?></td>
                                        <td class="tagsection">
                                        	<?php  if(isset($value['tagdetails']) && !empty($value['tagdetails'])){
												$tags = explode('@@',$value['tagdetails']);
												echo implode(", ", $tags);
											}?>
                                        </td>
                                        <td>
                                        	<select name="subscriberaction[]" id="<?php echo $value['id'];?>" class="subscriberaction selectAction">
                                            	<option value=""><?=__("Select an Option")?></option>
															<option value="<?php echo "editprofile";?>" >Edit</option>
                                                <!--option value="editstatus">Edit Status</option-->
																<?php foreach($status_array as $status_key => $status_value)
											{ ?>
												<option value="<?php echo $status_value['id'];?>" <?php //if($status_value['id']==$value['status']){ echo 'selected';} ?>><?php echo $status_value['status'];?></option>
												<?php
											} ?>
                                                <option value="taguser">Tag User</option>
                                                <option value="shareworkout">Share Workout</option>         
                                                <option value="sendemail">Send Email</option>
                                            </select>
                                        </td>
										
                                    </tr>

                                <?php } ?>	

                                </tbody>
                            </table>
                        </div><div class="exercise_tbl_pg" > <?php echo (isset($pagination))?$pagination:''; ?> </div>
                    <?php } else { echo "No Records Found..."; }?>
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
<div id="xrprev-modal" class="modal fade" role="dialog"></div>
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
						<div class="col-xs-8 ratetitle"><?=__("Options for this Subscriber List Export")?></div>
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
				<input type="hidden"  name="roleid" id="roleid" value="<?php echo $roleid;?>"/>
				<input type="hidden"  name="uid" id="uid" value=""/>	
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