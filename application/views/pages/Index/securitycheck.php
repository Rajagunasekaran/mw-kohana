<div id="wrap-index" style="overflow:hidden">
  <!-- Login header nav !-->
  <?php echo $topHeader;?>
  <div class="container" id="home">
	<div class="row">
		<form action="" id="header-form" method="post">
		<div id="content" class="col-md-12 clearfix" style="text-align:center">
			<div class="">
				<div class="clearfix">
					<h2 class="accessible_elem"><?php echo __('Enter Security Code'); ?></h2>
				</div>
				<hr>
				<div class="row">
					<?php $session = Session::instance();
						if ($session->get('flash_error_message')): ?>
					  <div class="banner warning" style="text-align:center;color:red;">
						<?php echo $session->get_once('flash_error_message') ?>
					  </div>
					 <?php endif ?>
				</div>
				<div class="col-xs-12 col-md-12">
					<div class="confirmemailboxwrapper">
						<div class="col-xs-6 col-md-6 confirmemailbox">
							<div class="identify_email"><?php echo __('Please check your email for messages with your code'); ?>.</div>
							<input type="text" class="form-control input-lg" name="security_code" placeholder="enter code here" required>
						 </div>
						 <div class="col-xs-6 col-md-6 confirmemailbox">
							<div class="lbl2"><?php echo __('we send your code to'); ?>:</div>
							<?php $session = Session::instance(); 
								if($session->get('recover_method') && $session->get('recover_method')=='send_email'){
									if(isset($userDetail->user_email) && !empty($userDetail->user_email)){ 
										$emailArray = explode('@',$userDetail->user_email);
										$email		= substr_replace($emailArray[0], '*******', 2, -1).'@'.$emailArray[1];
							?>
								
								<div class="send_email"><?php echo $email;?></div>
								
							<?php }}elseif($session->get('recover_method') && $session->get('recover_method')=='send_sms'){
								$phone = substr_replace($userDetail->user_mobile, +'***********', 2, -2);
							?>
								
								<div class="send_sms"><?php echo $phone;?></div>
								
							<?php } ?>
						 </div>
						 <div class="col-xs-12 btnwrapper">
						<input type="hidden" name="identify" value="<?php echo base64_encode($userDetail->id);?>"/>
						<button type="submit" name="identify_submit" class="btn btn-primary btncontinue"><?php echo __('Continue'); ?></button>
						<a href="<?php echo URL::base(TRUE).'index/login'; ?>" class="btn btn-primary btncancel"><?php echo __('Cancel'); ?></a>
						</div>
					
					</div>
				</div>
			</div>
			</form>
		</div>
	</div>