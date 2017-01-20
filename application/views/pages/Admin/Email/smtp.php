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
                            "<?php echo ucfirst($site_name); ?>" <?php echo (isset($site_language['SMTP Forms'])) ? $site_language['SMTP Forms'] : 'SMTP Forms'; ?>
                        </h1>
                        <ol class="breadcrumb">
                            <li>
                                <i class="fa fa-dashboard"></i>  <a href="<?php echo URL::base().'admin/index'; ?>"><?php echo (isset($site_language['Dashboard'])) ? $site_language['Dashboard'] : 'Dashboard'; ?></a>
                            </li>
                            <li class="active">
                                <i class="fa fa-edit"></i> <?php echo (isset($site_language['SMTP Forms'])) ? $site_language['SMTP Forms'] : 'SMTP Forms'; ?>
                            </li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->
				<?php if(isset($errors) && count($errors)>0) { 
				$labelArray = array(
									'smtphost'		=> 'SMTP Host',
									'smtpport'		=> 'SMTP Port',
									'smtpuser'		=> 'SMTP Username',
									'smtppass'		=> 'SMTP Password',
									'smtpfrom'		=> 'SMTP From',
									'smtpreplyto'	=> 'SMTP Reply To'
								);
				?>
				<div class="row">
					<div class="col-lg-12">
						<div class="alert alert-danger">
						  <?php foreach($errors as $key => $value) { 
							$msg = str_replace($key,$labelArray[$key],$value);
						  ?>
							<i class="fa fa-exclamation-triangle"></i><span><?php echo $msg; ?></span>
						  <?php } ?>
						</div>
					</div>
				</div>
				<?php } 
				if(isset($success) && $success!='') {  ?>
					<div class="row">
						<div class="col-lg-12">
							<div class="alert alert-success">
							  <i class="fa fa-check"></i><span><?php echo $success;?></span>
							</div>
						</div>
					</div>
				<?php } ?>
                <div class="row">
                    <div class="col-lg-6">

                        <form role="form" method="post" action="">

                            <div class="form-group">
                                <label>SMTP <?php echo (isset($site_language['Host'])) ? $site_language['Host'] : 'Host'; ?></label>
                                <input type="text" class="form-control" value="<?php if(!empty($SmtpGet)) { echo $SmtpGet[0]['smtp_host']; } ?>" name="smtphost">
                            </div>
							
							<div class="form-group">
                                <label>SMTP <?php echo (isset($site_language['Port'])) ? $site_language['Port'] : 'Port'; ?></label>
                                <input type="text" class="form-control" value="<?php if(!empty($SmtpGet)) { echo $SmtpGet[0]['smtp_port']; } ?>" name="smtpport">
                            </div>
							
							<div class="form-group">
                                <label>SMTP <?php echo (isset($site_language['Username'])) ? $site_language['Username'] : 'Username'; ?></label>
                                <input type="text" class="form-control" value="<?php if(!empty($SmtpGet)) { echo $SmtpGet[0]['smtp_user']; } ?>" name="smtpuser">
                            </div>
							
							<div class="form-group">
                                <label>SMTP <?php echo (isset($site_language['Password'])) ? $site_language['Password'] : 'Password'; ?></label>
                                <input type="password" class="form-control" value="<?php if(!empty($SmtpGet)) { echo Helper_Common::decryptPassword($SmtpGet[0]['smtp_pass']); } ?>" name="smtppass">
                            </div>
							
							<div class="form-group">
                                <label>SMTP <?php echo (isset($site_language['From Email'])) ? $site_language['From Email'] : 'From Email'; ?></label>
                                <input type="text" class="form-control" value="<?php if(!empty($SmtpGet)) { echo $SmtpGet[0]['smtp_from']; } ?>" name="smtpfrom">
                            </div>
							
							<div class="form-group">
                                <label>SMTP <?php echo (isset($site_language['Reply To Email'])) ? $site_language['Reply To Email'] : 'From Email'; ?></label>
                                <input type="text" class="form-control" value="<?php if(!empty($SmtpGet)) { echo $SmtpGet[0]['smtp_replyto']; } ?>" name="smtpreplyto">
                            </div>
                            
                            <button type="submit" name="submit" class="btn btn-default"><?php echo (isset($site_language['Save SMTP'])) ? $site_language['Save SMTP'] : 'Save SMTP'; ?></button>
							<a class="btn btn-default" href="<?php echo URL::base().'admin/email/smtpsettings/'.$site_id;?>"><?php echo (isset($site_language['SMTP List'])) ? $site_language['SMTP List'] : 'SMTP List'; ?></a>
							<input type="hidden" name="smptid" value="<?php if(!empty($SmtpGet)) { echo $SmtpGet[0]['smtp_id']; } ?>" />
							<input type="hidden" name="site_id" value="<?php echo $site_id;?>" />
                        </form>

                    </div>
                    
                </div>
                <!-- /.row -->

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    

</body>

