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
                            "<?php echo ucfirst($site_name); ?>" <?php echo (isset($site_language['SMTP Settings'])) ? $site_language['SMTP Settings'] : 'SMTP Settings'; ?>
                        </h1>
                        <ol class="breadcrumb">
                            <li>
                                <i class="fa fa-dashboard"></i>  <a href="<?php echo URL::base().'admin/index'; ?>"><?php echo (isset($site_language['Dashboard'])) ? $site_language['Dashboard'] : 'Dashboard'; ?></a>
                            </li>
                            <li class="active">
                                <i class="fa fa-edit"></i> <?php echo (isset($site_language['SMTP Settings'])) ? $site_language['SMTP Settings'] : 'SMTP Settings'; ?>
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
					<h2 class="col-xs-7 col-sm-7 col-lg-6 no-margin-top"><?php echo (isset($site_language['SMTP'])) ? $site_language['SMTP'] : 'SMTP'; ?></h2>
					<?php if(Helper_Common::hasAccess('Create SMTP Settings')) { ?>
						<div class="col-xs-5 col-sm-5 col-lg-6"><a class="btn btn-default" href="<?php echo URL::base().'admin/email/smtp/'.$site_id?>" style="float:right;"><?php echo (isset($site_language['Add SMTP'])) ? $site_language['Add SMTP'] : 'Add SMTP'; ?></a></div>
					<?php } ?>
				</div>
<?php //print_r($SmtpDetails); ?>
<?php if(!empty($SmtpDetails)) { ?>
						<div class="row">
                        <div class="col-lg-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th><?php echo (isset($site_language['Host'])) ? $site_language['Host'] : 'Host'; ?></th>
                                        <th><?php echo (isset($site_language['Port'])) ? $site_language['Port'] : 'Port'; ?></th>
                                        <th><?php echo (isset($site_language['Username'])) ? $site_language['Username'] : 'Username'; ?></th>
                                        <th><?php echo (isset($site_language['From Email'])) ? $site_language['From Email'] : 'From Email'; ?></th>
										<th><?php echo (isset($site_language['Reply To Email'])) ? $site_language['Reply To Email'] : 'Reply To Email'; ?></th>
										<?php if(Helper_Common::hasAccess('Modify SMTP Settings')) { ?>
											<th><?php echo (isset($site_language['Action'])) ? $site_language['Action'] : 'Action'; ?></th>
										<?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
								<?php
									foreach($SmtpDetails as $smtp) { 
								?>
								
                                    <tr>
                                        <td><?php echo $smtp['smtp_host']; ?></td>
                                        <td><?php echo $smtp['smtp_port']; ?></td>
                                        <td><?php echo $smtp['smtp_user']; ?></td>
                                        <td><?php echo $smtp['smtp_from']; ?></td>
										<td><?php echo $smtp['smtp_replyto']; ?></td>
										<?php if(Helper_Common::hasAccess('Modify SMTP Settings')) { ?>
											<td>
												<select  class="form-control selectAction" onchange="smtpAction(this.value,'<?php echo $smtp['smtp_id'];?>');">
													<option value=""><?php //echo (isset($site_language['Action'])) ? $site_language['Action'] : 'Action'; ?></option>
													<option value="edit">Edit SMTP</option>
													<option value="delete">Delete SMTP</option>
												</select>
											</td>
										<?php } ?>
									</tr>

								<?php } ?>	
									
                                </tbody>
                            </table>
                        </div>
                    </div></div>
					<?php } else { echo "No Records Found..."; }?>
					
                <!-- /.row -->

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    
	<input type="hidden" id="site_id" name="site_id" value="<?php echo $site_id;?>" />
</body>

