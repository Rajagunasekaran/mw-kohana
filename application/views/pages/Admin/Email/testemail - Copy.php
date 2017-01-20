	<!--- Top nav && left nav--->
	<?php echo $topnav.$leftnav;?>
	<!--- Top nav && left nav --->
      <!-- Content Wrapper. Contains page content -->
      <?php if(isset($template_type_single) && count($template_type_single)>0) {
		foreach($template_type_single as $key => $value) {
			$type_id		= $value['type_id'];
			$type_name		= $value['type_name'];
			$template_id	= $value['template_id'];
		}
	  } ?>
	  <div id="page-wrapper">
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Test Email
                        </h1>
                        <ol class="breadcrumb">
                            <li>
                                <i class="fa fa-dashboard"></i>  <a href="<?php echo URL::base().'admin/index'; ?>">Dashboard</a>
                            </li>
                            <li class="active">
                                <i class="fa fa-edit"></i>  Test Email
                            </li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->
				<?php if(isset($errors) && count($errors)>0) { 
				$labelArray = array(
									'test_email'		=> 'Test Email',
									'test_email_array'	=> 'Test Email',
									'template_id'		=> 'Template'
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
						<a class="btn btn-default" href="javascript:void(0);" onclick="showTestEmailForm();" style="margin-bottom:15px;"> Add Test Email</a>
					 </div>
				</div>
				<div class="row" id="addEmailFormContnr" <?php if(!isset($errors['test_email'])){ ?>style="display:none;" <?php } ?>>
					 <div class="col-lg-12">
						<form role="form" method="post" action="">
							<div class="container-fluid">
								<div class="row">
									 <div class="col-lg-4">
										<div class="form-group">
											<label>Add Test Email</label>
											<input type="text" class="form-control" value="" name="test_email">
										</div>
									 </div>
									 <div class="col-lg-2" style="margin:24px 0;">
										<button type="submit" name="add-email" class="btn btn-default">Add Email</button>
									 </div>
								</div>
							</div>
						</form>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12">
						<form role="form" method="post" action="">
							<div class="container-fluid">
								<div class="row">
									<div class="col-lg-6">
										<div class="form-group select2contnr">
											<label>Test Email</label>
											 <select multiple="true" name="test_email_array[]" id="test_email_array" class="form-control select2">
												<?php if(isset($test_email_array) && count($test_email_array)>0) { 
													foreach($test_email_array as $key => $value){  ?>
														<option value="<?php echo $value['test_email'];?>" selected="selected"><?php echo $value['test_email'];?></option>
													<?php } 
												} ?>
											</select>
										</div>
									</div>
								</div>
								<div class="row">
									 <div class="col-lg-6">
										<div class="form-group">
											<label>Template </label>
											<?php if(isset($template_name_array) && count($template_name_array)>0) { ?>
												<select class="form-control" name="template_id">
													<option value="">Select</option>
													<?php foreach($template_name_array as $key => $value){ ?>
														<option value="<?php echo $value['template_id'];?>"<?php if(isset($template_id) && $template_id!='' && $template_id==$value['template_id']) {  echo 'selected'; } ?>><?php echo $value['template_name'];?></option>
													<?php } ?>
												</select> 
											<?php } ?>
										</div>
									 </div>
								</div>
								<div class="row">
									<div class="col-lg-6">
										<button type="submit" name="send-email" class="btn btn-default">Send Test Email</button>
									</div>
								</div?
							</div>
						</form>
					</div>
				</div>
			</div>
            <!-- /.container-fluid -->
		</div>
        <!-- /#page-wrapper -->
	</div>
    <!-- /#wrapper -->

    <!-- jQuery -->
</body>

