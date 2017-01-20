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
							?> 
                           <?php $session = Session::instance();
							if($session->get('current_site_name')!=''){echo '"'.$session->get('current_site_name').'"';}?> 
						    <?php
							echo (isset($site_language['Trainers'])) ? $site_language['Trainers'] : 'Trainers'; 
							?> 
                        </h1>
						
                        <ol class="breadcrumb">
                            <li>
                                <i class="fa fa-dashboard"></i>  <a href="<?php echo URL::base().'admin/index'; ?>">Dashboard</a>
                            </li>
                            <li class="active">
                                <i class="fa fa-edit"></i> Trainers List
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
			<?php 
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
			
			
			<div class="row">
				<div class="col-lg-12">
					<div class="alert alert-success ajax_msg" style='display:none;'>
					  <i class="fa fa-check"></i><span></span>
					</div>
				</div>
			</div>
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
                        
                <div class="userstrainersection">
                       
						<div class="col-lg-6 trainertitle">
						<?php if(isset($template_details) && count($template_details)>0) { echo count($template_details); }?> 
						<?php
						echo (isset($site_language['Subscribers'])) ? $site_language['Subscribers'] : 'Subscriber(s)'; 
						?>  
						</div>
							<div class="col-lg-6 downloadusercontainer">
								<!--a class="btn btn-default" style="float:right;" href="javascript:void(0);" onclick='get_trainers_without_profile1()'><i class='fa fa-plus'></i> Add Trainer Profile1</a-->
								<a class="btn btn-default" style="float:right;" href="javascript:void(0);" onclick='get_trainers_without_profile()'><i class='fa fa-plus'></i> Add Trainer Profile</a>
							</div>
                        <?php if(isset($template_details) && count($template_details)>0) { 
                            $gender_array = Helper_Common::genderArray();
                        ?>
                        <div class="table-responsive col-sm-12">
                            <table class="table table-bordered table-hover table-striped dataTable">
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
								<?php  foreach($template_details as $key => $value) { 
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
									    <td class="tabl-chkbox checkselect"><input type="checkbox" name="row_index[]" class="chkbox-item trainerselect" value="<?php echo $value['id'];?>" /></td>
                                        <td>	<a href="javascript:void(0);" onclick="showUserModel('<?php echo $value['id'];?>',1)" id="username_<?php echo $value['id'];?>"><?php echo $value['user_fname'].' '.$value['user_lname']; ?></a></td>
										<td><?php echo date("j D Y g:i a",strtotime($value['date_created'])); ?></td>
										<!--<td><a href="<?php echo URL::base().'admin/user/edit/'.$value['id']; ?>"><i class="fa fa-edit"></i></a></td>-->
										<td class="statusupdate-<?php echo $value['id'];?>"><?php echo $value['userstatus'];?></td>
										<?php if(isset($status_array) && count($status_array)>0) { ?>
										<td>
											<select name="manager_status" class="form-control selectAction" onchange="changeTrainerStatus('<?php echo $value['id'];?>',this.value);">
												<option value="" ></option>
											    <option value="<?php echo "edit-".$value['id'];?>" >Edit</option>
												 <option value="<?php echo "profile-".$value['id'];?>" >Profile Edit</option>
												 <option value="<?php echo "remove_profile-".$value['id'];?>" >Remove Profile</option>
												<?php
												/*
												foreach($status_array as $status_key => $status_value) { ?>
													<option value="<?php echo $status_value['id'];?>" <?php //if($status_value['id']==$value['status']){ echo 'selected';} ?>><?php echo $status_value['status'];?></option>
												<?php } */ ?>
												
												<!--option value="sendemail">Send Email</option-->
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

} ?>

<style type='text/css'>
.round_div {
	border: 1px solid hsl(0, 0%, 85%);
	border-radius: 25px;
	cursor: pointer;
	height: 73px;
}
.useravatar img {
	border: 1px solid hsla(0, 0%, 100%, 0.5);
	border-radius: 50%;
	min-height: 70px;
	min-width: 70px;
	padding: 5px;
}
.tname{
	margin-top:23px;
}
#trainers_list ul {
	list-style-type: none;
}
.usercard{
	padding:3px;
}
</style>
<div id="search-trainer-modal" class="modal fade bs-example-modal-sm" role="dialog">
	<div class="vertical-alignment-helper">
		<div class="modal-dialog ">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">To Create Promo Profile</h4>
				</div>
				<div class="modal-body" >
					<div class="row opt-row-detail">
						<div class="col-xs-12">
							<div class="input-group c-search" >
								<input type="text" id="trainers_list-search" class="form-control" >
								<span class="input-group-btn">
									<button type="button" class="btn btn-default"><span class="fa fa-blue fa-search text-muted"></span></button>
                        </span>
                     </div>
                  </div>
               </div>
					<div id='trainers_list'></div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
</div>



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
<?php
echo "\n"; echo HTML::style("assets/css/font-awesome.min.css");
echo "\n"; echo HTML::style("assets/plugins/multi-select/multiple-select.css");
echo "\n"; echo HTML::script("assets/plugins/multi-select/multiple-select.js");
?>
<style type="text/css">
.ms-choice {
	background: none;
	border: none;
	color: #42ABFF;
	/*margin-top:-15px; */
}
.dropdowna {
	border:0px solid red;
	margin-top:5px;
}
.ms-parent{
	width: 200px !important;
}
.ms-drop.bottom {
   box-shadow: 0 4px 5px hsla(0, 0%, 0%, 0.15);
   top: 100%;
	margin-top:2px;
	width:100%;
}

.userstatus_checkbox,.css-checkbox {
	position:absolute; z-index:-1000; left:-1000px; overflow: hidden; clip: rect(0 0 0 0); height:1px; width:1px; margin:-1px; padding:0; border:0;
	
}
.userstatus_checkbox + span.css-label,.css-checkbox + label.css-label {
	padding-left:20px;
	height:14px; 
	display:inline-block;
	line-height:14px;
	background-repeat:no-repeat;
	background-position: 0 0;
	font-size:14px;
	vertical-align:middle;
	cursor:pointer;

}
.userstatus_checkbox:checked + span.css-label ,.css-checkbox:checked + label.css-label {
	background-position: 0 -14px;
}
span.css-label,label.css-label {
background-image:url('<?php  echo URL::base(TRUE); ?>assets/css/images/checkIcon.png');
-webkit-touch-callout: none;
-webkit-user-select: none;
-khtml-user-select: none;
-moz-user-select: none;
-ms-user-select: none;
user-select: none;
}
</style>
<div id="search-user-modal" class="modal fade bs-example-modal-sm" role="dialog">
	<div class="vertical-alignment-helper">
		<div class="modal-dialog ">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">To Create Promo Profile</h4>
				</div>
				<div class="modal-body" style='height:200px;overflow-y: scroll' >
					<div id='alert_info' style='margin: 0 auto;border:0px solid red;color: red;text-align: center;'></div>
					<div id='users_list' style='margin: 0 auto;border:0px solid red;'>
						<div class="form-group">
							<label for="workout-name" class="control-label">Role(s):</label>   
							<div>
								<select placeholder="Choose Status" id='roles'   multiple="true"    class="bordernone fa-blue" tabindex="4" >
									<option value='admin'>Admin</option>
									<option value='manager'>Manager</option>
									<option value='trainer'>Trainer</option>
								</select>
							</div>
						</div>
						<div class="form-group" id='sel_users'>
							<label for="workout-name" class="control-label">User(s):</label>   
							<div style='width:100%;' >
								<select placeholder="Choose Users" id='users' style='width:100%'  multiple="true"    class=" bordernone fa-blue aamoteactions" tabindex="4">
									<?php
									if(isset($admin) && is_array($admin) && count($admin)>0) {
										echo '<optgroup label="Admin">';
										foreach($admin as $key => $value) {
											?>
											<option value="<?php echo $value['id'];?>"><?php echo $value['user_fname'].' '.$value['user_lname']; ?></option>
											<?php
										}
										echo '</optgroup>';
									}
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
									?>
								</select>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary" onclick='get_subscribers()'>Save</button>
				</div>
			</div>
		</div>
	</div>
</div>




