	<!--- Top nav && left nav--->
	<?php echo $topnav.$leftnav;?>
	<!--- Top nav && left nav --->
      <!-- Content Wrapper. Contains page content -->
      <style type="text/css">
		.activedeactivesite label { margin-right: 30px; }
		@media (max-width:480px) {
			.with-addon{display: inline-block;overflow: hidden;width: 100%;}
		}
	  </style>
	  <div id="page-wrapper">
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            <?php echo (isset($site_language['Create Sites'])) ? $site_language['Create Sites'] : 'Create Site'; ?>
                        </h1>
                        <ol class="breadcrumb">
                            <li>
                                <i class="fa fa-dashboard"></i>  <a href="<?php echo URL::base().'admin/index'; ?>"><?php echo (isset($site_language['Dashboard'])) ? $site_language['Dashboard'] : 'Dashboard'; ?></a>
                            </li>
                            <li>
                                <?php echo (isset($site_language['Sites'])) ? $site_language['Sites'] : 'Sites'; ?>
                            </li>
                            <li class="active">
                                <i class="fa fa-edit"></i> <?php echo (isset($site_language['Create Sites'])) ? $site_language['Create Sites'] : 'Create Site'; ?>
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
                <?php if(isset($errors) && count($errors)>0) {  ?>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="alert alert-danger">						  
                              <?php foreach($errors as $key => $value) { ?>
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
									<div class="createsitefield">
										<div class="col-xs-3 form-group">
											<label><?php echo (isset($site_language['Name'])) ? $site_language['Name'] : 'Name'; ?></label>
										</div>
										<div class="col-xs-9 form-group">
											<input type="text" class="form-control" value="<?php echo (isset($sites->name) ? $sites->name : '');?>" name="name" id="name">
										</div>
									</div>
								</div>
                                <div class="row">
									<div class="createsitefield">
										<div class="col-xs-3 form-group">
											<label><?php echo (isset($site_language['Site Url'])) ? $site_language['Site Url'] : 'Site Url'; ?></label>
										</div>
										<div class="col-xs-9 form-group">
                                          <div class="input-group with-addon">
                                          <span class="input-group-addon" id="basic-addon3"><?php echo URL::base(true);?>site/</span>
                                          <input type="text" class="form-control" value="<?php echo (isset($sites->slug) ? $sites->slug : '');?>" id="slug" name="slug" aria-describedby="basic-addon3">
                                          </div>
                                        </div>
                                        
									</div>
                                 </div>   
                                <div class="row">
									<div>
										<div class="col-xs-3 form-group">
											<label><?php echo (isset($site_language['Site Status'])) ? $site_language['Site Status'] : 'Site Status'; ?></label>
										</div>
                                        <div class="col-xs-9 activedeactivesite form-group">
											<label><input type="radio" <?php echo (!isset($sites->is_active) || (isset($sites->is_active) && $sites->is_active=='1') ? 'checked=""' : '');?> value="1" name="status"> Active</label>
                                            <label><input type="radio" <?php echo ((isset($sites->is_active) && $sites->is_active=='0') ? 'checked=""' : '');?> value="0" name="status"> Inactive</label>
										</div>
									</div>
								</div>
										  
								<div class="row">
									<div>
										<div class="col-xs-3 form-group"><label><?php echo __("Site \"Contact Us\" Page") ?></label></div>
                              <div class="col-xs-9 activedeactivesite form-group">
											<label>
												<input type="radio" <?php echo (!isset($sites->is_contact) || (isset($sites->is_contact) && $sites->is_contact=='1') ? 'checked=""' : '');?> value="1" name="contact">
												<?php echo __("Admin/Super Admin"); ?></label>
                                 <label>
												<input type="radio" <?php echo ((isset($sites->is_contact) && $sites->is_contact=='0') ? 'checked=""' : '');?> value="0" name="contact">
												<?php echo __("Site Manager"); ?></label>
										</div>
									</div>
								</div>

							  	<div class="row">
									<div class="createsitefield">
										<div class="col-xs-3 form-group">
											<label><?php echo (isset($site_language['Minimum Age Limit'])) ? $site_language['Minimum Age Limit'] : 'Minimum Age Limit'; ?></label>
										</div>
										<div class="col-xs-9 form-group">
											<!--input type="number" class="form-control" value="18" name="min_agelimit" id="min_agelimit"/-->
											<?php
											$str ="
											<label class='range-square-radio'></label>
											<input  type='hidden' class=\"form-control \" id='min_agelimit' name='min_agelimit' value='18' readonly style='width:100%'>
											<div id='agerange'></div>";
											$str .='
												<script>
												$( function() {
												  $( "#agerange" ).slider({
													 range: "max",
													 min: 10,
													 max: 99,
													 value: 18,
													 slide: function( event, ui ) {
														$( "#min_agelimit" ).val( ui.value );
														$( ".range-square-radio" ).html( ui.value );
													 }
												  });
												  $( "#min_agelimit" ).val( $( "#agerange" ).slider( "value" ) );
												  $( ".range-square-radio" ).html( $( "#agerange" ).slider( "value" ) );
												} );
												</script>
											';
											echo $str;
											?>
										</div>
									</div>
								</div>

								<div class="row">
									<div>
										<div class="col-xs-3 form-group">
											<label><?php echo (isset($site_language['Common Questions'])) ? $site_language['Common Questions'] : 'Common Questions'; ?></label>
										</div>
                            	<div class="col-xs-9 activedeactivesite form-group">
											<label><input type="radio" <?php echo (!isset($sites->common_question) || (isset($sites->common_question) && $sites->common_question=='1') ? 'checked=""' : '');?> value="1" name="common_question"
											<?php
											if(!Helper_Common::is_admin()){ echo "disabled"; }
											?>
																						> Active</label>
                                	<label><input type="radio" <?php echo ((isset($sites->common_question) && $sites->common_question=='0') ? 'checked=""' : '');?> value="0" name="common_question" <?php
											if(!Helper_Common::is_admin()){ echo "disabled"; }
											?>> Inactive</label>
										</div>
									</div>
								</div>

								<div class="row"><h3>Site Records</h3></div>
								<div class="row">
									<div>
										<div class="col-xs-3 form-group">
											<label><?php echo (isset($site_language['Default Workouts'])) ? $site_language['Default Workouts'] : 'Default Workouts'; ?></label>
										</div>
                                        <div class="col-xs-9 activedeactivesite form-group">
											<label><input type="radio" <?php echo (!isset($sites->sample_workouts) || (isset($sites->sample_workouts) && $sites->sample_workouts=='1') ? 'checked=""' : '');?> value="1" name="sample_workouts"> Active</label>
                                            <label><input type="radio" <?php echo ((isset($sites->sample_workouts) && $sites->sample_workouts=='0') ? 'checked=""' : '');?> value="0" name="sample_workouts"> Inactive</label>
										</div>
									</div>
								</div>
								<div class="row">
									<div>
										<div class="col-xs-3 form-group">
											<label><?php echo (isset($site_language['Default Exercise Records'])) ? $site_language['Default Exercise Records'] : 'Default Exercises'; ?></label>
										</div>
                                        <div class="col-xs-9 activedeactivesite form-group">
											<label><input type="radio" <?php echo (!isset($sites->exercise_records) || (isset($sites->exercise_records) && $sites->exercise_records=='1') ? 'checked=""' : '');?> value="1" name="exercise_records"> Active</label>
                                            <label><input type="radio" <?php echo ((isset($sites->exercise_records) && $sites->exercise_records=='0') ? 'checked=""' : '');?> value="0" name="exercise_records"> Inactive</label>
										</div>
									</div>
								</div>
								<div class="row">
									<div>
										<div class="col-xs-3 form-group">
											<label><?php echo (isset($site_language['Default Images'])) ? $site_language['Default Images'] : 'Default Images'; ?></label>
										</div>
                                        <div class="col-xs-9 activedeactivesite form-group">
											<label><input type="radio" <?php echo (!isset($sites->sample_images) || (isset($sites->sample_images) && $sites->sample_images=='1') ? 'checked=""' : '');?> value="1" name="sample_images"> Active</label>
                                            <label><input type="radio" <?php echo ((isset($sites->sample_images) && $sites->sample_images=='0') ? 'checked=""' : '');?> value="0" name="sample_images"> Inactive</label>
										</div>
									</div>
								</div>
								
								<?php if(isset($device_integration) && is_array($device_integration) && count($device_integration)>0){?>
								<div class="row"><h3>Device Integrations</h3></div>
								<?php
								foreach($device_integration as $k=>$v){   
									?>
									<div class="row">
										<div>
											<div class="col-xs-3 form-group">
												<label><?php echo $v["name"]; ?></label>
											</div>
											<div class="col-xs-9 activedeactivesite form-group">
												<label>
													<input type="radio" checked='checked'
													  value="0" name="device[<?php echo $v["id"]; ?>]"> Active</label>
												<label>
													<input type="radio" 
														value="1" name="device[<?php echo $v["id"]; ?>]"> Inactive</label>
											</div>
										</div>
									</div>
									<?php
								}
								}?>
									
								
								
								
								
                                <div class="row">
									<div class="col-lg-12">
										<button type="submit" name="submit" name="createuser" class="btn btn-default"><?php echo (isset($site_language['Save'])) ? $site_language['Save'] : 'Save'; ?></button>										
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
<script type="text/javascript">
	$(document).ready(function(){
		$('.activedeactivesite').prev().addClass('activedeactivelabel');
	});
</script>