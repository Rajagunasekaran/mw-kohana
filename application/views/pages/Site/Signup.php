<?php echo $header;
$bg_class = 'bg-class';
$font_class = 'font-class';

?>
<div class="main-wrapper after-nav">
<div id="wrap-index" >
  <!-- Login header nav !-->
  <?php //echo $topHeader;?>
  <div class="container" id="home">
	<div class="row">
		<div class="col-md-12">
			<div class="row">
				<?php $session = Session::instance();?>
					<?php $flashsuccess = (!$session->get('flash_success_popup')); ?>
				  	<?php if ($session->get('user_fname_error') || $session->get('user_lname_error') || $session->get('user_email_error') || $session->get('password_error')|| $session->get('birthday_year_error')): ?>
				  	<div class="alert alert-danger signuperror">
				    	<?php if ($session->get('user_fname_error')): ?>
						<i class="fa fa-exclamation-triangle"></i><span>
						<?php echo __($session->get_once('user_fname_error')); ?>
						</span>
						<?php endif ?>
						<?php if ($session->get('user_lname_error')): ?>
						<i class="fa fa-exclamation-triangle"></i><span>
						<?php echo __($session->get_once('user_lname_error')); ?>
						</span>
						<?php endif ?>
						<?php if ($session->get('user_email_error')): ?>
						<i class="fa fa-exclamation-triangle"></i><span>
						<?php echo __($session->get_once('user_email_error')); ?>
						</span>
						<?php endif ?>
					    <?php if ($session->get('password_error')): ?>
						<i class="fa fa-exclamation-triangle"></i><span>
						<?php echo __($session->get_once('password_error')); ?>
						</span>
						<?php endif ?>
					    <?php if ($session->get('birthday_year_error')): ?>
						<i class="fa fa-exclamation-triangle"></i><span>
						<?php echo __($session->get_once('birthday_year_error')); ?>
						</span>
						<?php endif ?>
				  	</div>
				  	<?php endif ?>
				  	<?php if (isset($_GET['cookie']) && $_GET['cookie']=='0' && $_GET['form']=='signup') : ?>
					<div class="alert alert-danger">
						<i class="fa fa-exclamation-triangle"></i><span>
						<?php echo __('Please enable your browser cookie'); ?>
						</span>
					</div>
			  	<?php endif ?>
			</div>
			<form id="site-singup-form" action="" method="post" class="form" role="form" data-ajax="false" data-role="none">
				<h2 class="signup-heading"><b><?php echo __('Create your account'); ?></b></h2>
				<h4><?php echo __('Get training today with loads of valuable resources and features'); ?>.</h4>
				<div class="row">
					<div class="col-xs-6 col-md-6">
						<input data-role="none" data-ajax="false" class="form-control input-lg" name="user_fname" placeholder="First Name" type="text" required="true" value="<?php echo ($flashsuccess && $session->get('user_fname') ? $session->get('user_fname') : '');?>"/>
					</div>
					<div class="col-xs-6 col-md-6">
						<input data-role="none" data-ajax="false" class="form-control input-lg" name="user_lname" placeholder="Surname" type="text" required="true" value="<?php echo ($flashsuccess && $session->get('user_lname') ? $session->get('user_lname') : '');?>"/>
					</div>
				</div>
				<input data-role="none" data-ajax="false" class="form-control input-lg" name="user_email" placeholder="Email" type="text" required="true" value="<?php echo ($flashsuccess && $session->get('user_email') ? $session->get('user_email') : '');?>"/>
				<input data-role="none" data-ajax="false" class="form-control input-lg" name="user_reenter_email" placeholder="Re-enter email" type="text" required="true" value="<?php echo ($flashsuccess && $session->get('user_reenter_email') ? $session->get('user_reenter_email') : '');?>"/>
				<input data-role="none" data-ajax="false" class="form-control input-lg" name="password" placeholder="New Password" type="password" required="true"/>
				<label for=""><?php echo __('Birthday'); ?></label>
				<div class="row">
					<div class="col-xs-4 col-md-4">
						<select data-role="none" data-ajax="false" class="form-control input-lg" name="birthday_month" required>
							<option value="">Month</option>
							<?php
								$monthArray = array('1'=>"Jan",'2'=>"Feb",'3'=>"Mar",'4'=>"Apr",'5'=>"May",'6'=>"Jun",'7'=>"Jul",'8'=>"Aug",'9'=>"Sep",'10'=>"Oct",'11'=>"Nov",'12'=>"Dec");
								foreach ($monthArray as $mkay => $month) {
									if($flashsuccess && $session->get('birthday_month') == $mkay ){
										$selectmonth = 'selected';
									}else{
										$selectmonth = '';
									}
									echo '<option value='.$mkay.' '.$selectmonth.'>'.$month.'</option>';
								}
							?>
						</select>
					</div>
					<div class="col-xs-4 col-md-4">
						<select data-role="none" data-ajax="false" class="form-control input-lg" name="birthday_day" required>
							<option value="">Day</option>
							<?php $dayArray = range(1, 31);
								asort($dayArray);
								foreach ($dayArray as $day) {
									if($flashsuccess && $session->get('birthday_day') == $day ){
										$selectday = 'selected';
									}else{
										$selectday = '';
									}
									echo '<option value='.$day.' '.$selectday.' >'.$day.'</option>';
								}
							?>
						</select>
					</div>
					<div class="col-xs-4 col-md-4">
						<select data-role="none" data-ajax="false" class="form-control input-lg" name="birthday_year" required>
							<option value="">Year</option>
							<?php 
								$years = range(date('Y'), date('Y')-110);
								foreach ($years as $yr) {
									if($flashsuccess && $session->get('birthday_year') == $yr ){
										$selectyear = 'selected';
									}else{
										$selectyear = '';
									}
									echo '<option value='.$yr.' '.$selectyear.'>'.$yr.'</option>';
								}
							?>
						</select>
					</div>
				</div>
				<label class="radio-inline">
					<input data-role="none" data-ajax="false" type="radio" name="user_gender" <?php echo ($flashsuccess && ($session->get('user_gender') && $session->get_once('user_gender')=='1' )? 'checked=""' : (!($session->get('user_gender')) ? 'checked=""' : ''));?> id="male" value="1" /><?php echo __('Male'); ?>
				</label>
				<label class="radio-inline">
					<input data-role="none" data-ajax="false" type="radio" name="user_gender" id="female" <?php echo ($flashsuccess && ($session->get('user_gender') && $session->get_once('user_gender')=='2' )? 'checked=""' : '');?> value="2" /><?php echo __('Female'); ?>
				</label>
				<br />
				<span class="help-block <?php echo $font_class;?>"><?php echo __('By clicking Create my account, you agree to our Terms and that you have read our Data Use Policy, including our Cookie Use'); ?>.</span>
				<button data-role="none" data-ajax="false" class="btn btn-lg btn-primary btn-block signup-btn <?php echo $bg_class;?>" type="submit" name="signup"><?php echo __('Create my account'); ?></button>
			</form>
		</div>
	</div>
  </div>
</div>
<?php 
	if ($session->get('flash_success_popup')):?>
	<script type="text/javascript">
		$(document).ready(function($){
			$("#myModal").modal('show');
		});
	</script>
	<!-- Modal -->
	<div id="myModal" class="modal fade" role="dialog">
		<div class="vertical-alignment-helper">
		  	<div class="modal-dialog">
		    	<!-- Modal content-->
		    	<div class="modal-content">
			      <div class="modal-header" style="border-bottom:0">
			        	<button data-role="none" data-ajax="false" type="button" class="close" data-dismiss="modal">&times;</button>
			      </div>
			      <div class="modal-body">
			        	<p style="padding:10px;"><?php echo $session->get_once('flash_success_popup');?></p>
			      </div>
			      <div class="modal-footer" style="border-top:0">
			        	<button data-role="none" data-ajax="false" type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Close'); ?></button>
			      </div>
		    	</div>
		  	</div>
	  	</div>
	</div>
<?php endif ;
if ($session->get('forgotPassword')){
?>
<script type="text/javascript">
	var $ = jQuery.noConflict();
	$(document).ready(function(){
		$("#ForgotPwdModal").modal('show');
	});
</script>
<div id="ForgotPwdModal" class="modal fade in" role="dialog">
   <div class="vertical-alignment-helper">
      <div class="modal-dialog modal-md">
         <div class="modal-content">
			   <form action="<?php echo URL::base(TRUE).'index/recover'; ?>" method="post">
	            <div class="modal-header">
	               <div class="row">
	                  <div class="banner warning">
								<?php echo $session->get_once('forgotPassword') ?>
						  	</div>
						  	<div class="popup-title">
		                 	<h2 class="accessible_elem" style="margin-left:auto;margin-right:auto;"><?php echo __('Find Your Account'); ?></h2>
	               	</div>
	               </div>
	            </div>
	            <div class="modal-body">
	               <div class="row">
						   <div class="col-xs-12 col-md-12" style="text-align:center;">
							  <div class="lbl email-phone" for="identify_email"><?php echo __('Email'); ?></div>
							  <input data-role="none" data-ajax="false" type="text" required="" class="inp emailphone" name="identify_email">
							  <div class="lt-right btnarea">
								 	<button data-role="none" data-ajax="false" class="btn btn-primary account-searchbtn" name="identify_search" type="submit"><?php echo __('Search'); ?></button><a data-role="none" data-ajax="false" class="btn btn-primary" href="<?php echo URL::base(TRUE).'index/login'; ?>"><?php echo __('Go to login'); ?></a>
							  </div>
						   </div>
					   </div>
	            </div>
					<div class="modal-footer">
	               <button data-role="none" data-ajax="false" type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Close'); ?></button>
	            </div>
            </form>
         </div>
      </div>
   </div>
</div>
<?php } 
if ($session->get_once('resendConfirmation')){
?>
<script type="text/javascript">
	var $ = jQuery.noConflict();
	$(document).ready(function(){
		$("#resendConfirmation").modal('show');
	});
</script>
<div id="resendConfirmation" class="modal fade in" role="dialog">
	<div class="vertical-alignment-helper">
      <div class="modal-dialog modal-md">
         <div class="modal-content">
			 	<form action="" method="post" class="form" role="form">
	            <div class="modal-header">
	               <div class="row">
	                  <div class="popup-title">
	                    <h2 class="accessible_elem" style="margin-left:auto;margin-right:auto;"><?php echo __('Resend Email Confirmation'); ?></h2>
	                  </div>
	               </div>
	            </div>
	            <div class="modal-body">
	               <div class="row">
						   <div class="col-xs-12 col-md-12" style="text-align:center;">
							  <div class="lbl email-phone" for="identify_email"><?php echo __('Are you sure you want to resend confirmation email'); ?>?</div>
							  
							  <div class="lt-right btnarea">
								 <button data-role="none" data-ajax="false" class="btn btn-primary account-searchbtn" name="resend" type="submit"><?php echo __('Resend'); ?></button> <button data-ajax="false" data-role="none" type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Cancel'); ?></button>
							  </div>
						   </div>
					   </div>
	            </div>
					<div class="modal-footer">
	               <button data-role="none" data-ajax="false" type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Close'); ?></button>
	            </div>
				  	<input type="hidden" name="user_email" value="<?php echo $session->get_once('user_email'); ?>" />
				  	<input type="hidden" name="activation_code" value="<?php echo $session->get_once('activation_code'); ?>" />
				  	<input type="hidden" name="date_created" value="<?php echo $session->get_once('date_created'); ?>" />
				  	<input type="hidden" name="user_fname" value="<?php echo $session->get_once('user_fname'); ?>" />
				  	<input type="hidden" name="user_lname" value="<?php echo $session->get_once('user_lname'); ?>" />
			   </form>
         </div>
      </div>
   </div>
</div>






<?php } 
echo $footer;
?>