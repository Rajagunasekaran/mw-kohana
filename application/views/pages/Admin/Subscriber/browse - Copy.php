	<!--- Top nav && left nav--->
	<?php echo $topnav.$leftnav;?>
	<!--- Top nav && left nav --->
      <!-- Content Wrapper. Contains page content -->
      <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                           Browse Subscribers
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
                    <?php //print_r($SmtpDetails); ?>
                    <div class="col-lg-12">
                        <h2 class="col-lg-2">Subscribers List </h2>
						 <div class="col-lg-4">
							<select name="action" class="form-control action-dropdown">
								<option value="">Assign</option>
								<option value="share">Share a Workout</option>
								<option value="tag">Tag selected users</option>
							</select>
						</div> 
						<?php if(!Helper_Common::is_trainer()) { ?>
							<div class="col-lg-6"><a class="btn btn-default" href="<?php echo URL::base().'admin/user/create/register';?>" style="float:right;">Add Subscriber</a></div>
						<?php } if(isset($template_details) && count($template_details)>0) { 
                           
							$gender_array = Helper_Common::genderArray();
                        ?>
                        <div class="table-responsive col-lg-12">
                            <table id="suscribeTable" class="table table-bordered table-hover table-striped dataTable"> 
                                <thead>
                                    <tr>
                                        <th class="chkbox-header"><input type="checkbox" name="row_index[]" id="select-all" /></th>
										<th>Name</th>
                                        <?php if(!Helper_Common::is_trainer()) { ?>
											<th>Email / Mobile</th>
                                        <?php } ?>
										<th>Gender</th>
                                        <?php if(!Helper_Common::is_trainer()) { ?>
											<th>Edit</th>
											<th>Delete</th>
										<?php } ?>
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
                                        <td class="tabl-chkbox"><input type="checkbox" name="row_index[]" class="chkbox-item" /></td>
										<td><a href="javascript:void(0);" onclick="showUserModel('<?php echo $value['id'];?>',1)"><?php echo $value['user_fname'].' '.$value['user_lname']; ?></a></td>
                                        <?php if(!Helper_Common::is_trainer()) { ?>
											<td><?php echo $user_email; ?></td>
										<?php } ?>
                                        <td><?php echo ucfirst($gender_array[$value['user_gender']]); ?></td>
                                        <?php if(!Helper_Common::is_trainer()) { ?>
											<td><a href="<?php echo URL::base().'admin/user/edit/'.$value['id']; ?>"><i class="fa fa-edit"></i></a></td>
											<td><a onclick="deleteSubs('<?php echo $value['id'];?>')" href="javascript:void(0);"><i class="fa fa-remove"></i></a></td>
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

<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal" id="deleteModalBtn">Delete</button>
<a href="javascript:void(0);" class="showpopup" data-target="#userModal" data-toggle="modal" style="display:none;">&nbsp;</a>
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
<?php require_once(APPPATH.'views/templates/admin/usermodal.php');?> 


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

