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
                            Add Subscriber
                        </h1>
                        <ol class="breadcrumb">
                            <li>
                                <i class="fa fa-dashboard"></i>  <a href="<?php echo URL::base().'admin/index'; ?>">Dashboard</a>
                            </li>
                            <li class="active">
                                <i class="fa fa-edit"></i> Add Subscriber
                            </li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->
				<?php if(isset($errors) && count($errors)>0) { 
				$labelArray = array(
									'user_fname'	=> 'First Name',
									'user_lname'	=> 'Surname',
									'user_email'	=> 'Email or mobile number',
									'password'		=> 'Password',
									'birthday_month'=> 'Month',
									'birthday_day'	=> 'Day',
									'birthday_year'	=> 'Year',
									'user_gender'	=> 'Gender'
								);
				?>
				<div class="row">
					<div class="col-lg-12">
						<div class="alert alert-danger">
						  <?php foreach($errors as $key => $value) { 
							//$msg = str_replace($key,$labelArray[$key],$value);
						  ?>
							<i class="fa fa-exclamation-triangle"></i><span><?php echo $value; ?></span>
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
                    <div class="col-lg-12">
						
                        <form role="form" method="post" action="">
							<div class="container-fluid">
								<div class="row">
									<div class="col-lg-6">
										<div class="form-group">
											<label>First Name</label>
											<input type="text" class="form-control" value="<?php if(!empty($SmtpGet)) { echo $SmtpGet[0]['smtp_host']; } ?>" name="user_fname">
										</div>
									</div>
									<div class="col-lg-6">
										<div class="form-group">
											<label>Surname</label>
											<input type="text" class="form-control" value="<?php if(!empty($SmtpGet)) { echo $SmtpGet[0]['smtp_host']; } ?>" name="user_lname">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12">
										<div class="form-group">
											<label>Email or mobile number</label>
											<input type="text" class="form-control" value="<?php if(!empty($SmtpGet)) { echo $SmtpGet[0]['smtp_port']; } ?>" name="user_email">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12">
										<div class="form-group">
											<label>Password</label>
											<input type="text" class="form-control" value="<?php if(!empty($SmtpGet)) { echo $SmtpGet[0]['smtp_user']; } ?>" name="password">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-4">
										<div class="form-group">
											<label>Month</label>
											<?php $monthArray = array('1'=>"Jan",'2'=>"Feb",'3'=>"Mar",'4'=>"Apr",'5'=>"May",'6'=>"Jun",'7'=>"Jul",'8'=>"Aug",'9'=>"Sep",'10'=>"Oct",'11'=>"Nov",'12'=>"Dec"); ?>
											<select class="form-control" name="birthday_month">
												<?php foreach($monthArray as $key => $value) { ?>
													<option value="<?php echo $key;?>"><?php echo $value;?></option>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="col-lg-4">
										<div class="form-group">
											<label>Day</label>
											<?php $dayArray = range(31, 1); ?>
											<select class="form-control" name="birthday_day">
												<?php foreach($dayArray as $day) { ?>
													<option value="<?php echo $day;?>"><?php echo $day;?></option>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="col-lg-4">
										<div class="form-group">
											<label>Year</label>
											<?php $years = range(date('Y'), date('Y')-110); ?>
											<select class="form-control" name="birthday_year">
												<?php foreach($years as $yr) { ?>
													<option value="<?php echo $yr;?>"><?php echo $yr;?></option>
												<?php } ?>
											</select>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12">
										<div class="form-group">
											<label>Gender</label> <br />
											<label class="radio-inline">
												<input type="radio" value="1" id="male" checked="" name="user_gender">Male
											</label>
											<label class="radio-inline">
												<input type="radio" value="2" id="female" name="user_gender">Female
											</label>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12">
										<button type="submit" name="submit" class="btn btn-default">Submit Button</button>
										<a class="btn btn-default" href="<?php echo URL::base().'admin/email/smtpsettings'?>">Subscriber List</a>
										<input type="hidden" name="smptid" value="<?php if(!empty($SmtpGet)) { echo $SmtpGet[0]['smtp_id']; } ?>" />
									</div>
								</div>
							</div>
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

