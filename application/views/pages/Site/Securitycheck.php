<?php echo $header; 
$bg_class = 'bg-class';
$font_class = 'font-class';

?>
<div class="main-wrapper after-nav">
<div id="wrap-index" >
  <!-- Login header nav !-->
  
  <div class="container" id="home">
	<div class="row">
		<form data-role="none" data-ajax="false" action="" id="header-form" method="post">
		<div id="content" class="col-md-12 clearfix" >
			<div class="">
				<div class="clearfix">
					<h2 class="accessible_elem"><?php echo __('Enter Security Code'); ?></h2>
				</div>
				<hr>
				<div class="row">
					<?php $session = Session::instance();
						if ($session->get('flash_error_message')): ?>
					  <div class="banner warning" >
						<?php echo $session->get_once('flash_error_message') ?>
					  </div>
					 <?php endif ?>
				</div>
				<div class="col-xs-12 col-md-12">
					<div class="confirmemailboxwrapper">
						<div class="col-xs-12 col-md-12 confirmemailbox">
							<div class="identify_email <?php echo $font_class;?>"><?php echo __('Please check your email for messages with your code'); ?>.</div>
							<input data-role="none" data-ajax="false" value="<?php echo (isset($userDetail->security_code) && !empty($userDetail->security_code) ? $userDetail->security_code : '');?>" type="text" class="form-control input-lg" name="security_code" placeholder="enter code here" required>
						 </div>
						 <div class="col-xs-12 col-md-12 confirmemailbox">
							<div class="lbl2 <?php echo $font_class;?>"><?php echo __('we send your code to'); ?>:</div>
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
						<button data-role="none" data-ajax="false" type="submit" name="identify_submit" class="btn btn-primary btncontinue <?php echo $bg_class.' '.$font_class;?>"><?php echo __('Continue'); ?></button>
						<a data-role="none" data-ajax="false" href="<?php echo $site_url; ?>" class="btn btn-primary btncancel <?php echo $bg_class.' '.$font_class;?>"><?php echo __('Cancel'); ?></a>
						</div>
					
					</div>
				</div>
			</div>
			</form>
		</div>
	</div>
</div>
</div>
<?php echo $footer; ?>