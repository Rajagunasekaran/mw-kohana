<!--- Top nav && left nav--->
<?php echo $topnav.$leftnav;?>
<?php require_once(APPPATH.'views/templates/admin/usermodal.php'); ?>
<!--- Top nav && left nav --->
<!-- Content Wrapper. Contains page content -->
<div id="page-wrapper">
	<div class="container-fluid">
		<!-- Page Heading -->
		
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">
					<?php
					echo (isset($site_language['Browse'])) ? $site_language['Browse'] : 'Browse';
					$session = Session::instance();
					if($session->get('current_site_name')!=''){echo '"'.$session->get('current_site_name').'"';	}
					echo (isset($site_language['Trainers'])) ? $site_language['Trainers'] : 'Trainers'; 
					?> 
            </h1>
				<ol class="breadcrumb">
					<li>
						<i class="fa fa-dashboard"></i>  <a href="<?php echo URL::base().'admin/index'; ?>">Dashboard</a>
					</li>
					<li class="active"><i class="fa fa-edit"></i> Trainers List</li>
				</ol>
         </div>
      </div>
		
		<div id="mes_suc" class="row" style="display:none;">
			<div class="col-lg-12">
				<div class="alert alert-success"><i class="fa fa-check"></i><span></span></div>
			</div>
		</div>
      
		<?php
		if(isset($success) && $success!='')
		{  ?>
			<div class="row">
				<div class="col-lg-12">
					<div class="alert alert-success"><i class="fa fa-check"></i><span><?php echo $success;?></span></div>
				</div>
			</div><?php
		} ?>
		
		<div class="del-sucess" style="display:none;">
			<div class="row">
				<div class="col-lg-12"><div class="alert alert-success"><i class="fa fa-check"></i><span></span></div></div>
			</div>
		</div>
		
      <div class="row">
			<div class="userstrainersection">
				<div class="col-lg-6 col-xs-6 trainertitle" style='border:0px solid orange;'><?php
					if(isset($template_details) && count($template_details)>0) { echo count($template_details); }
					echo (isset($site_language['Subscribers'])) ? $site_language['Subscribers'] : 'Subscriber(s)'; 	?>  
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-2 col-lg-2 downloadusercontainer" style='border:0px solid green;'>
				<!--<a data-target="#EmailModal" data-toggle="modal" href="#" class="btn btn-primary"><i class="fa fa-user"></i> Email</a>
				<a class="btn btn-primary downloaduser" href="<?php echo URL::site('admin/user/get_user_report_as_excel/'.$roleid.'');?>" ><i class="fa fa-user"></i> Excel</a>
				<a class="btn btn-primary downloaduser" href="<?php echo URL::site('admin/user/get_userlist_pdf/'.$roleid.'');?>" target="_blank" ><i class="fa fa-user"></i> PDF</a>-->
				<!--a class="btn btn-default" href="<?php echo URL::base().'admin/user/create/trainer';?>">Add Trainer</a-->
				<select name="right traineractions[]" id="traineractions" class="selectAction">
					<option value=""><?=__("Select an Option")?></option>
					<option value="addtrainer"><?=__("Add Trainer")?></option>
					<option value="export"><?=__("Export this list")?></option>
					<option value="exportselected"><?=__("Export selected")?></option>
					<option value="sendemail"><?=__("Send Email")?></option>
				</select>
				<!--<div class="right rightactions">
					<select name="right moteactions[]" id="moteactions" class="moteactions">
						<option value="">Actions</option>
						<option value="addsubscriber">Add Trainer</option>
						<option value="shareworkout">Share Workout</option>                                                
						<option value="tagusers">Tag Selected Users</option>                                        
					</select>
				</div>-->
			</div>
		</div>
		<?php
		if(isset($template_details) && count($template_details)>0)
		{ 
			$gender_array = Helper_Common::genderArray();	?>
			<div class="table-responsive row  col-lg-12" >
				<table class="table table-bordered table-hover table-striped dataTable" >
					<thead>
						<tr>
							<th width='3%' class="chkbox-header"><input type="checkbox"  name="row_index[]" id="select-all" /></th>
							<th width='25%'>Name</th>
							<th width='25%'>Registered</th>
							<th width='25%'>Status</th>										
							<th width='22%'>Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach($template_details as $key => $value)
						{ 
							$user_email = '';
							if(isset($value['user_email']) && $value['user_email']!='' && isset($value['user_mobile']) && $value['user_mobile']!='') {
								$user_email = $value['user_email'].' / '.$value['user_mobile'];
							} else if(isset($value['user_email']) && $value['user_email']!='') {
								$user_email = $value['user_email'];
							} else if(isset($value['user_mobile']) && $value['user_mobile']!='') {
								$user_email = $value['user_mobile'];
							}	?>
							<tr id="row-<?php echo $value['id'];?>">
								<td class="tabl-chkbox checkselect"><input type="checkbox" name="row_index[]" class="chkbox-item trainerselect" value="<?php echo $value['id'];?>" /></td>
								<td><a href="javascript:void(0);" onclick="showUserModel('<?php echo $value['id'];?>',1)" id="username_<?php echo $value['id'];?>"><?php echo $value['user_fname'].' '.$value['user_lname']; ?></a></td>
								<td><?php echo date("j D Y g:i a",strtotime($value['date_created'])); ?></td>
								<td class="statusupdate-<?php echo $value['id'];?>"><?php echo $value['userstatus'];?></td>
								<?php if(isset($status_array) && count($status_array)>0)
								{ ?>
									<td>
										<select name="manager_status" class="form-control selectAction" onchange="changeTrainerStatus('<?php echo $value['id'];?>',this.value);">
											<option value="" ></option>
											<option value="<?php echo "edit-".$value['id'];?>" >Edit</option>
											<!--option value="editstatus<?php echo "-".$value['id'];?>">Edit Status</option-->
											<?php foreach($status_array as $status_key => $status_value)
											{ ?>
												<option value="<?php echo $status_value['id'];?>" <?php //if($status_value['id']==$value['status']){ echo 'selected';} ?>><?php echo $status_value['status'];?></option>
												<?php
											} ?>
											<option value="sendemail">Send Email</option>
										</select>
									</td><?php
								} ?>
							</tr><?php
						}
						?>
					</tbody>
				</table>
			</div><?php
		} else { echo "No Records Found..."; }	?>
	</div>
</div>
<!-- /#wrapper -->
<?php
if($template_details && is_array($template_details) && count($template_details)>0){
		/*
?>
<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal" id="deleteModalBtn">Delete</button>
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
<!-- Email Report Modal Start-->
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

    <!-- jQuery -->
    <script type="text/javascript">
		function deleteSubs(id) {
            $("#deleteModalBtn").click();            
            $("#user_idjs").val(id);			
		}
		function deleteUser() {
            var id = $('#user_idjs').val();
			$.ajax({
                url: "<?php echo URL::site('admin/subscriber/deleteSubscriber'); ?>",
                type: 'POST',
                dataType: 'json',
                data:{'id':id},
                success:function(data){
                    if(data.success) {
                        $('.del-sucess .alert-success span').text(data.message);
                        $('.del-sucess').show();
                        $('#row-'+id).remove();
                        $("#noDelete").click();
                    }
                }
            });
		}
	</script>
</body>
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
						<div class="col-xs-8 ratetitle"><?=__("Options for this Trainer List Export")?></div>
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
