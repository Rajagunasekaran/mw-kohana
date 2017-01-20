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
                            <?php echo (isset($site_language['Device List'])) ? $site_language['Device List'] : 'Device List'; ?>
                        </h1>
                        <ol class="breadcrumb">
                            <li>
                                <i class="fa fa-dashboard"></i>  <a href="<?php echo URL::base().'admin/index'; ?>"><?php echo (isset($site_language['Dashboard'])) ? $site_language['Dashboard'] : 'Dashboard'; ?></a>
                            </li>
                            <li class="active">
                                <i class="fa fa-edit"></i> <?php echo (isset($site_language['Email Template List'])) ? $site_language['Email Template List'] : 'Email Template List'; ?>
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
				
				<div class="row">
					<div class="col-lg-12">
						<div  id="share_msg" class="">
							
						</div>
					</div>
				</div>
			
                <div class="row">
				<?php //print_r($SmtpDetails); ?>
				<?php if(isset($device_details) && count($device_details)>0) { 
				$statusArray = Helper_Common::emailTemplateStatusArray();
				?>
				
                <div class="col-lg-12">
                        <h2>Device List</h2>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th><?php echo (isset($site_language['Device Name'])) ? $site_language['Device Name'] : 'Device Name'; ?></th>
										<th><?php echo (isset($site_language['Status'])) ? $site_language['Status'] : 'Status'; ?></th>
										<?php if(Helper_Common::hasAccess('Modify Device')) { ?>
											<th><?php echo (isset($site_language['Action'])) ? $site_language['Action'] : 'Action'; ?></th>
										<?php } ?>
									</tr>
                                </thead>
                                <tbody>
								<?php
									foreach($device_details as $key => $value) { 
								?>
								
                                    <tr>
                                        <td><?php echo $value['name']; ?></td>
										<td><?php if($value['status'] == 0 ){echo "Active";} 
												elseif($value['status'] == 1 ){echo "Deactive";}	?></td>
										<?php if(Helper_Common::hasAccess('Modify Template')) { ?>
											<td>
											<select  class="form-control selectAction" onchange="deviceAction(this.value,<?php echo $value['id'] ?>);">
													<option value="">Choose Action</option>
													<option value="edit">Edit Device</option>
													<option value="delete">Delete Device</option>
												</select> 
											</td>
										<?php } ?>
									</tr>
								<?php } ?>	
									
                                </tbody>
                            </table>
                        </div>
                    </div>
				<?php } else { echo "No Records Found..."; }?>
				</div>
                <!-- /.row -->
				
				
            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

    
    <input type="hidden" id="site_id" value="<?php echo $site_id;?>" />
</div>
    <!-- /#wrapper -->
</body>



<div id="confirm" class="modal fade" tabindex="-1" role="dialog">
  <div class="vertical-alignment-helper">
  <div class="modal-dialog">
	<div class="modal-content">
	  <div class="modal-body">
		Are you sure?
	  </div>
	  <div class="modal-footer">
		<button type="button" data-dismiss="modal" class="btn btn-primary" id="delete">Delete</button>
		<button type="button" data-dismiss="modal" class="btn">Cancel</button>
	  </div>
	</div>
  </div>
  </div>
</div>

