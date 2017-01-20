<div id="wrap-index" style="overflow:hidden">
   <!-- Login header nav !-->
  <?php echo $topHeader;?>
  <?php if(isset($userid) && empty($userid)) { ?>
	  <div class="container" id="home">
		<div class="row">
			<form action="" id="header-form" method="post">
			<div id="content" class="col-md-12 clearfix" style="text-align:center">
				<div class="">
					<div class="">
						<div class="clearfix">
							<div class="">
								<h2 class="accessible_elem"><?php echo __('Find Your Account'); ?></h2>
							</div>
						</div>
					</div>
					<hr>
					<div class="col-xs-12 col-md-12">
						<div class="">
							  <div class="lt-left">
								<div class="row">
									<?php $session = Session::instance();
										if ($session->get('flash_error_message')): ?>
									  <div class="banner warning" style="text-align:center;color:red;">
										<?php echo $session->get_once('flash_error_message') ?>
									  </div>
									 <?php endif ?>
								</div>
								<br>
								<div class="row">
									<div class="col-xs-12 col-md-12">
										<div for="identify_email" class="lbl email-phone"><?php echo __('Email'); ?></div>
										<input type="text"  name="identify_email" class="inp emailphone"  required>
										<div class="lt-right btnarea">
											<button type="submit" name="identify_search" class="btn btn-primary account-searchbtn"><?php echo __('Search'); ?></button><a href="<?php echo URL::base(TRUE).'index/login'; ?>" class="btn btn-primary"><?php echo __('Cancel'); ?></a>
										</div>
									 </div>
								</div>
							  </div>
						</div>
					</div>
				</div>
				</form>
			</div>
		</div>
	</div>
<?php }else{ ?>
	<div class="container" id="home">
		<div class="row">
			<form action="" id="header-form" method="post">
			<div id="content" class="col-md-12 clearfix" style="text-align:center">
				<div class="">
					<div class="">
						<div class="clearfix">
							<div class="">
								<h2 class="accessible_elem"><?php echo __('Reset Your Password'); ?></h2>
								<hr>
							</div>
							<div class="">
								<div class="row">
									<?php $session = Session::instance();
										if ($session->get('flash_error_message')): ?>
									  <div class="banner warning" style="text-align:center;color:red;">
										<?php echo $session->get_once('flash_error_message') ?>
									  </div>
									 <?php endif ?>
								</div>
                                <?php if(isset($userDetail->user_email) && !empty($userDetail->user_email)){ 
													$emailArray = explode('@',$userDetail->user_email);
													$email		= substr_replace($emailArray[0], '*******', 2, -1).'@'.$emailArray[1];
													}else{
														$phone = substr_replace($userDetail->user_mobile, +'***********', 2, -2);
													}
											?>
                                <div class="reset_password_wrapper">
                                	<div class="user-name"><?php echo ucfirst(strtolower($userDetail->user_fname)).' '.ucfirst(strtolower($userDetail->user_lname));?></div>
                                    <div class="lbl1 rp-question"><?php echo __('How would you like to reset your password'); ?>?</div>
                                     <?php if(isset($email)){?>
                                        <div class="emailme-question col-md-12 clearfix">
                                    		<div class="reset_option">
                                            	<input type="radio" id="send_email" class="" checked="1" value="send_email" name="recover_method"></div>
                                       		<div class="resetpass"><?php echo __('Email me a link to reset my password'); ?></div>
                                       		<div class="secretnote"><span><?php echo __('Email'); ?> : </span><?php echo $email;?></div>
                                   		 </div>
                                        
                                        <?php } else { ?>
                                          <!--
                                          <div class="emailme-question col-md-12 clearfix">
                                    		<div class="reset_option">
                                            	<input type="radio" id="send_email" class="" checked="1" value="send_email" name="recover_method"></div>
                                       		 <div class="resetpass">Text me a code to reset my password</div>
                                       		<div class="secretnote"><span>Phone : </span><?php echo $phone;?></div>
                                   		 </div>                             
                                        
                                         -->
                                        <?php } ?>
                                    
                                </div>
								
		
							</div>
							<div class="row">
								<div class="btn-wrapper">
                                
                                <input type="submit" class="btn btn-primary reset_action" id="reset_action" name="reset_action" value="Continue">
                                
                                <a href="<?php echo URL::base(TRUE).'index/recover/'; ?>" class="btn btn-primary notyou"><span><?php echo __('Not You'); ?>?</span></a>
                                
								<div class="nologner"><a href="#"><?php echo __('No longer have access to these'); ?>?</a></div>
								</div>
							</div>
						</div>
					</div>
				</div>
				</form>
			</div>
		</div>
	</div>
<?php } ?>