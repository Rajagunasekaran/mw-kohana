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
                            "<?php echo ucfirst($site_name);?>" <?php echo (isset($site_language['Delivery Settings'])) ? $site_language['Delivery Settings'] : 'Delivery Settings'; ?>
                        </h1>
                        <ol class="breadcrumb">
                            <li>
                                <i class="fa fa-dashboard"></i>  <a href="<?php echo URL::base().'admin/index'; ?>"><?php echo (isset($site_language['Dashboard'])) ? $site_language['Dashboard'] : 'Dashboard'; ?></a>
                            </li>
                            <li class="active">
                                <i class="fa fa-edit"></i> <?php echo (isset($site_language['Delivery Settings'])) ? $site_language['Delivery Settings'] : 'Delivery Settings'; ?>
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
<?php if(!empty($deliveryDetails)) { ?>
                <div class="col-lg-12">
                        <h2 class="col-xs-7 col-sm-7 col-lg-6 no-margin-top"><?php echo (isset($site_language['Delivery'])) ? $site_language['Delivery'] : 'Delivery'; ?></h2>
						<div class="col-xs-5 col-sm-5 col-lg-6"><a class="btn btn-default" href="<?php echo URL::base().'admin/email/delivery/'.$site_id;?>" style="float:right;"><?php echo (isset($site_language['Add'])) ? $site_language['Add'] : 'Add'; ?> <?php echo (isset($site_language['Delivery'])) ? $site_language['Delivery'] : 'Delivery'; ?></a></div>
                        <div class="table-responsive col-sm-12 col-lg-12">
                            <table class="table table-bordered table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th><?php echo (isset($site_language['Title'])) ? $site_language['Title'] : 'Title'; ?></th>
                                        <th><?php echo (isset($site_language['Template'])) ? $site_language['Template'] : 'Template'; ?></th>
                                        <th><?php echo (isset($site_language['Right Away status'])) ? $site_language['Right Away status'] : 'Right Away status'; ?></th>
                                        <th><?php echo (isset($site_language['Send Date'])) ? $site_language['Send Date'] : 'Send Date'; ?></th>
										<th><?php echo (isset($site_language['Trigger by'])) ? $site_language['Trigger by'] : 'Trigger by'; ?> <?php echo (isset($site_language['Days'])) ? $site_language['Days'] : 'Days'; ?></th>
										<th><?php echo (isset($site_language['Trigger by'])) ? $site_language['Trigger by'] : 'Trigger by'; ?> <?php echo (isset($site_language['Hours/Minutes'])) ? $site_language['Hours/Minutes'] : 'Hours/Minutes'; ?></th>
										<th><?php echo (isset($site_language['Last Modified'])) ? $site_language['Last Modified'] : 'Last Modified'; ?></th>
										<th><?php echo (isset($site_language['Status'])) ? $site_language['Status'] : 'Status'; ?></th>
										<th><?php echo (isset($site_language['Action'])) ? $site_language['Action'] : 'Action'; ?></th>
										<!-- <th>Edit</th>
                                        <th>Delete</th> -->
									
                                    </tr>
                                </thead>
                                <tbody>
								<?php
									foreach($deliveryDetails as $delivery) { 
								?>
								
                                    <tr>
                                        <td><?php echo ucfirst($delivery['delivery_name']); ?></td>
                                        <td><?php echo ucfirst($delivery['template_name']); ?></td>
                                        <td><?php echo (($delivery['is_rightaway']) ? 'Yes' : 'No'); ?></td>
                                        <td><?php echo $delivery['send_date']; ?></td>
										<td><?php echo (($delivery['triggerby_days']) ? $delivery['triggerby_days'].(($delivery['triggerby_days']>1) ? ' days' : ' day') : '-'); ?></td>
										<td>
											<?php 
												$timeArray =explode(":",$delivery['triggerby_hours']);
												$timeString = '-';
												if($timeArray[0]>0)
													$timeString .= $timeArray[0].(($timeArray[0]>1) ? ' hours' : ' hour');
												if($timeArray[1]>0)
													$timeString .= $timeArray[1].(($timeArray[1]>1) ? ' minutes' : ' minute');
											echo $timeString; 
											?>
										</td>
										<td><?php echo date('Y-m-d',strtotime($delivery['modified_date'])); ?></td>
										<td><?php echo (($delivery['is_active']) ? 'Active' : 'Inactive'); ?></td>
										<td>
											<select  class="form-control selectAction" onchange="deliveryAction(this.value,'<?php echo $delivery['delivery_id'];?>');">
												<option value=""><?php echo (isset($site_language['Action'])) ? $site_language['Action'] : 'Action'; ?></option>
												<option value="edit">Edit Delivery</option>
												<option value="delete">Delete Delivery</option>
											</select>
										</td>
										<!-- <td><a href="<?php //echo URL::base().'admin/email/delivery/'.$site_id.'/'.$delivery['delivery_id']; ?>"><i class="fa fa-edit"></i></a></td>
                                        <td><a href="<?php //echo URL::base().'admin/email/deliverysettings/'.$site_id.'/'.$delivery['delivery_id']; ?>"><i class="fa fa-remove"></i></a></td>-->
                                    </tr>

								<?php } ?>	
									
                                </tbody>
                            </table>
                        </div>
                    </div>
					<?php } else { echo "No Records Found..."; }?>
					
                <!-- /.row -->

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    
<input type="hidden" name="site_id" id="site_id" value="<?php if(!empty($site_id)) { echo $site_id; } ?>" />
</body>

