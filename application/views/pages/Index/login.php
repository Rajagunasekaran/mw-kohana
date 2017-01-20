<div id="wrap-index" style="overflow:hidden">
   <!-- Login header nav !-->
  <?php echo $topHeader;?>
  <div class="container" id="home">
	<div class="row">
		<div id="content" class="col-md-7 clearfix">
			<div class="">
				<div class="h2class">
                     <h2 class="accessible_elem">My workouts Login</h2>
                </div>    
				<hr>
				<div class="col-xs-12 col-md-12">
					<div class="login_form_container">
						<form action="<?php echo URL::base(TRUE).'index'; ?>" class="navbar-form navbar-right" id="header-form" role="form" method="post">
						  <div class="lt-left">
							<div class="row">
								<?php $session = Session::instance();
									if ($session->get('user_email_error')): ?>
								  <div class="banner warning" style="text-align:center;color:red;">
									<?php echo __($session->get_once('user_email_error')); ?>
								  </div>
								<?php endif ?>
							    <?php if ($session->get('password_error')): ?>
								  <div class="banner warning aligncenter" style="color:red;">
									<?php echo __($session->get_once('password_error')); ?>
								  </div>
								 <?php endif ?>
								 <?php if ($session->get('common_error')): ?>
								  <div class="banner warning aligncenter" style="color:red;">
									<?php echo __($session->get_once('common_error')); ?>
								  </div>
								 <?php endif ?>
								 <?php if (isset($_GET['cookie']) && $_GET['cookie']=='0') : ?>
								  <div class="banner warning cookie-warning aligncenter">
									<div class="aligncenter">
										<h4><?php echo __('Cookies Required'); ?></h4>
										<div style="color:red;"><?php echo __('Cookies are not enabled on your browser.<br>Please enable cookies in your browser preference to continue'); ?></div>
									</div>
								  </div>
								 <?php endif ?>
							</div>
							<div class="row">								
								<div class="form-wrapper login-form">
									<div class="rowitem">
										<label class="lbl3"><?php echo __('Email'); ?></label>
                                        <input type="text" class="form-control input-sm" id="email" name="user_email" required value="<?php echo ($session->get('user_email') ? $session->get_once('user_email') : '');?>">
									</div>	
                                    <div class="rowitem">
										<label class="lbl3"><?php echo __('Confirm Password'); ?></label>
                                        <input type="password" class="form-control input-sm" id="pass" name="password" required value="">
									</div>
                                    <div class="rowitem">
                                    	<label>
								 			 <input type="checkbox" name="remember" id="remember" value="1">   <label for="remember" style="color:#9197a3"><?php echo __('Remember me'); ?></label>
										</label>
										<label style="float:right;margin-top:2px;">
								  
								</label>
                                    </div>
                                    <div class="rowitem">
                                    	<a class="forgot-password" href="<?php echo URL::site('index/recover');?>" alt="Forgotten your password?"><?php echo __('Forgotten your password'); ?>?</a>
                                    </div>
                                    <div class="rowitem actionbtns">
                                    	<button type="submit" name="login" class="btn btn-primary btnlogin"><?php echo __('Login'); ?></button>
                                        <a href="<?php echo URL::site('index/signup');?>" class="btn btn-primary"><?php echo __('Sign up'); ?></a>
                                    </div>									
								</div>								
							</div>
                            
                          
							
						  </div>
						 
						</form>
					</div>
				</div>
			</div>
	</div>
  </div>