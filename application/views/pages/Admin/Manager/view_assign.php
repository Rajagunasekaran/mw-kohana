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
                           Browse Assigned Sites
                        </h1>
						
                        <ol class="breadcrumb">
                            <li>
                                <i class="fa fa-dashboard"></i>  <a href="<?php echo URL::base().'admin/index'; ?>">Dashboard</a>
                            </li>
                            <li class="active">
                                <i class="fa fa-edit"></i> Assigned Sites List
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
				<div class="col-lg-12">
                        <h2 class="col-lg-6">Sites List</h2>
                        <div class="table-responsive col-lg-12">
                            <?php if(isset($template_details) && count($template_details)>0) { ?>
                            <table class="table table-bordered table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Status</th>
                                        <th>Last Updated At</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php 
                                    foreach($template_details as $value) { 
                                ?>
                                    <tr id="row-<?php echo $value['id'];?>">
                                        <td><?php echo $value['name']; ?></td>
                                        <td><?php echo($value['is_active'] ? "Active" : "Not Active"); ?></td>
                                        <td><?php echo $value['modified_at']; ?></td>
                                        <td><a onclick="deleteRecord('<?php echo $value['id'];?>','<?php echo $value['user_id'];?>')" href="javascript:void(0);"><i class="fa fa-remove"></i></a></td>
                                    </tr>
                                <?php } ?>										
                                </tbody>
                            </table>
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

<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal" id="deleteModalBtn">Delete</button>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <p>Are you sure yow want to delete?</p>
                <input type="hidden" name="user_idjs" id="user_idjs" value="0">
                <input type="hidden" name="user_idjs1" id="user_idjs1" value="0">
                <button type="button" class="btn btn-danger" id="yesDelete" onclick="deleteUser()">Yes</button>
                <button type="button" class="btn btn-primary" id="noDelete" data-dismiss="modal">No</button>
            </div>
        </div>

    </div>
</div>



    <!-- jQuery -->
    <script type="text/javascript">
		function deleteRecord(id,uid) {
            $("#deleteModalBtn").click();            
            $("#user_idjs").val(id);			
            $("#user_idjs1").val(uid);			
		}
		function deleteUser() {
            var id = $('#user_idjs').val();
            var uid = $('#user_idjs1').val();
			$.ajax({
                url: "<?php echo URL::site('admin/sites/deleteAssignedSites'); ?>",
                type: 'POST',
                dataType: 'json',
                data:{'id':id,'uid':uid},
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