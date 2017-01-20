	<!--- Top nav && left nav--->
	<?php //echo $topnav.$leftnav;?>
	<!--- Top nav && left nav --->
      <!-- Content Wrapper. Contains page content -->
      <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Assign Sites
                        </h1>
                    </div>
                </div>
                <!-- /.row -->
				<?php if(isset($errors) && count($errors)>0) { ?>
				<div class="row">
					<div class="col-lg-12">
						<div class="alert alert-danger">
						  <?php foreach($errors as $key => $value) {?>
							<i class="fa fa-exclamation-triangle"></i><span><?php echo $value; ?></span>
						  <?php } ?>
						</div>
					</div>
				</div>
				<?php } ?> 
				
                <div class="row message-row" style="display:none;">
                    <div class="col-lg-12">
                        <div class="alert alert-success">
                          <i class="fa fa-check"></i><span></span>
                        </div>
                    </div>
                </div>
				<div class="row">
                    <div class="col-lg-12">
						
                        <form role="form" method="post" action="" id="assign-site">
							<div class="container-fluid">
								<div class="row">
									<div class="col-lg-12">
										<div class="form-group">
											<label>Email</label>
											<!--<input type="text" class="form-control" readonly value="<?php //if(!empty($userDetails['user_email'])) { echo $userDetails['user_email']; } ?>" name="user_email">-->
											<input type="text" class="form-control user_email" readonly value="" name="user_email"/>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12">
										<div class="form-group">
                                            <label>User Level</label>
                                            <!--<input type="hidden" name="hidden-userid" value="<?php //echo (isset($userDetails['id']) ? $userDetails['id'] : '');?>">
								            <input type="text" class="form-control" readonly name="hidden-userrole" value="<?php //echo (isset($userDetails['role_name']) ? $userDetails['role_name'] : '');?>">-->
											<input type="hidden" name="hidden-userid" class="user_id" value="">
								            <input type="text" class="form-control role_name" readonly name="hidden-userrole" value="">
                                        </div>
                                    </div>
                                </div>
								<div class="row">
									<div class="col-lg-12">
										<div class="form-group">
                                            <label>Sites</label>
                                            <?php
                                            /*    $asite = array();
                                                foreach($assignedSites as $asignSite){$asite[] = $asignSite['site_id'];}*/
                                            ?>
                                            
                                            <!--<select class="form-control" name="site_ids[]" id="site_ids" multiple="multiple">
                                                <?php /*foreach($sites as $site){?>
                                                <option <?php if(in_array($site['id'],$asite)){echo "selected";} ?> value="<?php echo $site['id'];?>"><?php echo $site['name'];?></option>
                                                <?php }*/?>
                                            </select>-->
                                            <select class="form-control sites_name" name="site_ids[]" id="site_ids" multiple="multiple">
												
											</select>
                                        </div>
                                        
                                    </div>
                                </div>
                                <div class="row">
									<div class="col-lg-12">
										<button type="button" name="submit" name="createuser" class="btn btn-default" onclick="submitAssignSite();">Save</button>										
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