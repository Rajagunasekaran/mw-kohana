<?php echo $header; 
$bg_class = 'bg-class';
$font_class = 'font-class';
?>
<div class="main-wrapper after-nav">
<div id="wrap-index" class="recover-wrap" >
   <!-- Login header nav !-->
  
  <?php if(empty($userid)) { ?>
	  <div class="container" id="home">
		<div class="row">
			<form action="" id="header-form-recover" method="post" data-ajax="false">
			<div id="content" class="col-md-12 clearfix" >
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
									  <div class="banner warning" >
										<?php echo $session->get_once('flash_error_message') ?>
									  </div>
									 <?php endif ?>
								</div>
								<div class="row">
									<div class="col-xs-12 col-md-12">
										<div for="identify_email" class="lbl email-phone <?php echo $font_class;?>"><?php echo __('Email'); ?></div>
										<input data-role="none" data-ajax="false" type="text"  name="identify_email" class="inp emailphone"  required>
										<div class="lt-right btnarea">
											<button data-role="none" data-ajax="false" type="submit" name="identify_search" class="btn btn-primary account-searchbtn <?php echo $bg_class.' '.$font_class;?>"><?php echo __('Search'); ?></button><a data-role="none" data-ajax="false" href="<?php echo $site_url; ?>" class="btn btn-primary <?php echo $bg_class.' '.$font_class;?>"><?php echo __('Cancel'); ?></a>
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
			<form action="" id="header-form-recover-submit" method="post" data-ajax="false">
			<div id="content" class="col-md-12 clearfix" >
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
									  <div class="banner warning" >
										<?php echo $session->get_once('flash_error_message') ?>
									  </div>
									 <?php endif ?>
								</div>
                                <?php 
								
								if(isset($userDetail->user_email) && !empty($userDetail->user_email)){ 
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
                                            	<input data-role="none" data-ajax="false" type="radio" id="send_email" class="" checked="1" value="send_email" name="recover_method"></div>
                                       		<div class="resetpass"><?php echo __('Email me a link to reset my password'); ?></div>
                                       		<div class="secretnote"><span><?php echo __('Email'); ?> : </span><?php echo $email;?></div>
                                   		 </div>
                                        
                                        <?php } else { ?>
                                          <div class="emailme-question col-md-12 clearfix">
                                    		<div class="reset_option">
                                            	<input data-role="none" data-ajax="false" type="radio" id="send_email" class="" checked="1" value="send_email" name="recover_method"></div>
                                       		 <div class="resetpass"><?php echo __('Text me a code to reset my password'); ?></div>
                                       		<div class="secretnote"><span><?php echo __('Phone'); ?> : </span><?php echo $phone;?></div>
                                   		 </div>                             
                                        
                                         
                                        <?php } ?>
                                    
                                </div>
								
		
							</div>
							<div class="row">
								<div class="btn-wrapper">
                                
                                <input data-role="none" data-ajax="false" type="submit" class="btn btn-primary reset_action <?php echo $bg_class.' '.$font_class;?>" id="reset_action" name="reset_action" value="Continue">
                                
                                <a data-role="none" data-ajax="false" href="<?php echo $site_url.'forgotpassword/recover/'; ?>" class="btn btn-primary notyou <?php echo $bg_class.' '.$font_class;?>"><span><?php echo __('Not You'); ?>?</span></a>
                                
								<div class="nologner"><a data-role="none" data-ajax="false" href="javascript:void(0);"><?php echo __('No longer have access to these'); ?>?</a></div>
								</div>
							</div>
						</div>
					</div>
				</div>
				</form>
			</div>
		</div>
	</div>
<?php } 
if($session->get('flash_pwdresetmail_message') != ''){
	$session->set('flash_pwdresetmail_message','') ; ?>
	<script type="text/javascript">
		$(document).ready(function($){
			$("#popuppwdresetinsite").modal('show');
		});
		$(document).on("click","#siteredirectyes, #closesiteredirectyes",function(){
			window.location.href="<?php echo URL::base(true).'site/'.$session->get('current_site_slug');?>";
		});
	</script>
<div id="popuppwdresetinsite" class="modal fade" role="dialog">
	<div class="vertical-alignment-helper">
	  <div class="modal-dialog">
	    <!-- Modal content-->
	    <div class="modal-content">
	      <div class="modal-header" style="border-bottom:0">
	        <button data-role="none" data-ajax="false" type="button" class="close" id="closesiteredirectyes" data-dismiss="modal">&times;</button>
	      </div>
	      <div class="modal-body">
	      <?php echo __('A password reset confirmation link has been emailed. Please check your registered email to continue.');?>
	      </div>
	      <div class="modal-footer" style="border-top:0">
	      	<button data-role="none" data-ajax="false" type="button" class="btn btn-default" id="siteredirectyes"><?php echo __('ok'); ?></button>
	      </div>
	    </div>
	  </div>
	</div>
</div>
<?php } ?>

</div>
<?php echo $footer;
?>
