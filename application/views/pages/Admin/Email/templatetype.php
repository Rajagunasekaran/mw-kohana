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
	  <style type="text/css">
		.template_type_label{text-align:right;margin-top:5px;}
		@media (max-width:1199px) {
			.template_type_label{text-align:left;}
		}
	  </style>
	  <div id="page-wrapper">
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                          "<?php echo ucfirst($site_name); ?>" <?php echo (isset($site_language['Email Settings'])) ? $site_language['Email Settings'] : 'Email Settings'; ?>
                        </h1>
                        <ol class="breadcrumb">
                            <li>
                                <i class="fa fa-dashboard"></i>  <a href="<?php echo URL::base().'admin/index'; ?>"><?php echo (isset($site_language['Dashboard'])) ? $site_language['Dashboard'] : 'Dashboard'; ?></a>
                            </li>
                            <li class="active">
                                <i class="fa fa-edit"></i>  <?php echo (isset($site_language['Email Settings'])) ? $site_language['Email Settings'] : 'Email Settings'; ?>
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
						<p> Please choose template for your emails</p>
					</div>
				</div>
				<div class="row">
					 <div class="col-lg-6">
						<form role="form" method="post" action="">
							<div class="container-fluid">
								<?php /*if(isset($template_type_array) && count($template_type_array)>0) { 
									foreach($template_type_array as $key => $value) { ?>
										<div class="row">
											 <div class="col-lg-6">
												<div class="form-group template_type_label" >
													<label><?php echo $value['type_name']; ?></label>
													<input type="hidden" name="template_map[<?php echo $key;?>][type_id]" value="<?php echo $value['type_id'];?>" />
												</div>
											 </div>
											 <div class="col-lg-6">
												<div class="form-group">
													<?php if(isset($template_name_array) && count($template_name_array)>0) { ?>
														<select class="form-control selectAction" name="template_map[<?php echo $key; ?>][template_id]">
															<?php foreach($template_name_array as $temp_name_key => $temp_name_value){ ?>
																<option value="<?php echo $temp_name_value['template_id'];?>" <?php if($value['template_id']==$temp_name_value['template_id']){ echo 'selected'; }?>><?php echo $temp_name_value['template_name'];?></option>
															<?php } ?>
														</select>
													<?php } ?>
												</div>
											 </div>
										</div>
									<?php } 
								}*/ ?>
								<?php
								$emailtemplate_static_array = array('Register','Activation','Forgot Password','Contact Us','Password reset','Assign a Workout Record','notification - shared workout');
								$liste =array();
								if($template_type_array_new){
								foreach($emailtemplate_static_array as $key => $value) {
									
								 if( !in_array($value, $template_type_array_new)){
									     array_push($liste, $value);
									}
								}
								}

								if(isset($template_type_array) && count($template_type_array)>0) { 
									foreach($template_type_array as $key => $value) { ?>
										<div class="row">
											 <div class="col-lg-6">
												<div class="form-group template_type_label" >
													<label><?php echo $value['type_name'] ?></label>
													<input type="hidden" name="template_map[<?php echo $key;?>][type_id]" value="<?php echo $value['type_id'];?>" />
												</div>
											 </div>
											 <div class="col-lg-6">
												<div class="form-group">
													<?php if(isset($template_name_array) && count($template_name_array)>0) { ?>
														<select class="form-control selectAction" name="template_map[<?php echo $key; ?>][template_id]">
															<?php foreach($template_name_array as $temp_name_key => $temp_name_value){ ?>
																<option value="<?php echo $temp_name_value['template_id'];?>" <?php if($value['template_id']==$temp_name_value['template_id']){ echo 'selected'; }?>><?php echo $temp_name_value['template_name'];?></option>
															<?php } ?>
														</select>
													<?php } ?>
												</div>
											 </div>
										</div>
									<?php } 
								}
								if(isset($liste) && count($liste)>0) { 
									foreach($liste as $key => $value) { ?>
										<div class="row">
											 <div class="col-lg-6">
												<div class="form-group template_type_label" >
													<label><?php echo $value; ?></label>
													<input type="hidden" value="<?php echo $value; ?>" name="staticlable[<?php echo $key;?>][type_id]" />
												</div>
											 </div>
											 <div class="col-lg-6">
												<div class="form-group">
													<?php if(isset($template_name_array) && count($template_name_array)>0) { ?>
														<select class="form-control selectAction" name="staticlable[<?php echo $key; ?>][template_id]">
														        <option value=''>Select Template</option>
															<?php foreach($template_name_array as $temp_name_key => $temp_name_value){ ?>
																<option value="<?php echo $temp_name_value['template_id'];?>" ><?php echo $temp_name_value['template_name'];?></option>
															<?php } ?>
														</select>
													<?php } ?>
												</div>
											 </div>
										</div>
									<?php } 
								}?>
								<div class="row">
									 <div class="col-lg-6">&nbsp;</div>
									 <div class="col-lg-6">
										<button type="submit" name="submit" class="btn btn-default"><?php echo (isset($site_language['Submit Button'])) ? $site_language['Submit Button'] : 'Submit Button'; ?></button>
									 </div>
								</div>
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
	<script type="text/javascript">
	function loadTemplateType(tempId) {
		window.location.href='<?php echo URL::base().'admin/email/templatetype/'; ?>'+tempId;
	}
	</script>
</body>

