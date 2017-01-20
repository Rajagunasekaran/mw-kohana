<div id="wrap-index" style="overflow:hidden">
  <!-- Login header nav !-->
  <?php echo $topHeader;?>
  <div class="container" id="home">
	<div class="row">
		<div id="content" class="create_password clearfix">
			<div class="col-xs-12">
				<div class="h2class">
							<h2 class="accessible_elem"><?php echo __('Create a new password'); ?></h2>
				</div>
				<hr>
				<div class="clearfix secureinfo">
							<p>To help keep your <?php echo $site_title;?> account secure in future, please select a new password. If any of your other oline accounts (such as email) are you using your current <?php echo $site_title;?> password, you'll want to select a new, unique password for each of those accounts too.</p>
				</div>
				<div class="form-createpassowrd">
					
						<form action="" class="nav" role="form" method="post">
						  <div class="col-xs-12 form-container">
							<div class="row">
							    <?php $session = Session::instance();
									if ($session->get('new_pass_error')): ?>
								  <div class="banner warning" style="text-align:center;color:red;">
									<?php echo $session->get_once('password_error') ?>
								  </div>
								 <?php endif ?>
							</div>
							<div class="row">								
								<div class="form-wrapper">
									<div class="rowitem">
										<label class="lbl3"><?php echo __('New Password'); ?></label>
                                        <input type="password" class="form-control input-sm" name="new_pass" required value="">
									</div>	
                                    <div class="rowitem">
										<label class="lbl3"><?php echo __('Confirm Password'); ?></label>
                                        <input type="password" class="form-control input-sm" name="conf_pass" required value="">
									</div>
                                    <div class="rowitem actionbtns">
                                    	<input type="hidden" name="identify" value="<?php echo $userid;?>"/>
										<button type="submit" name="generate_submit" class="btn btn-primary ctnbtn"><?php echo __('Continue'); ?></button>
                                    </div>									
								</div>								
							</div>
							
							
						  </div>
						 
						</form>
					
				</div>
			</div>
	</div>
  </div>