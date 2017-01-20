<div id="wrap-index" style="overflow:hidden">
  <!-- Login header nav !-->
  <?php echo $topHeader;?>
  <div class="container" id="home">
	<div class="row">
		<div class="col-md-12">
			<div class="row">
				<?php $session = Session::instance(); ?>
				<?php if ($session->get('user_fname_error')): ?>
				  <div class="banner warning" style="text-align:center;color:red;">
					<?php echo __($session->get_once('user_fname_error')); ?>
				  </div>
				 <?php endif ?>
				 <?php if ($session->get('user_lname_error')): ?>
				  <div class="banner warning" style="text-align:center;color:red;">
					<?php echo __($session->get_once('user_lname_error')); ?>
				  </div>
				 <?php endif ?>
				<?php if ($session->get('user_email_error')): ?>
				  <div class="banner warning" style="text-align:center;color:red;">
					<?php echo __($session->get_once('user_email_error')); ?>
				  </div>
				<?php endif ?>
				<?php if ($session->get('password_error')): ?>
				  <div class="banner warning" style="text-align:center;color:red;">
					<?php echo __($session->get_once('password_error')); ?>
				  </div>
				 <?php endif ?>
				 <?php if ($session->get('birthday_year_error')): ?>
				  <div class="banner warning" style="text-align:center;color:red;">
					<?php echo __($session->get_once('birthday_year_error')); ?>
				  </div>
				 <?php endif ?>
				 <?php if (isset($_GET['cookie']) && $_GET['cookie']=='0') : ?>
				  <div class="banner warning" style="text-align:center;color:red;">
					<?php echo __('Please enable your browser cookie'); ?>
				  </div>
				 <?php endif ?>
			</div>
			<form action="" method="post" class="form" role="form">
					<h2><b><?php echo __('Create your account'); ?></b></h2>
					<h4><?php echo __('Get training today with loads of valuable resources and features'); ?>.</h4>
					<div class="row">
						<div class="col-xs-6 col-md-6">
							<input class="form-control input-lg" name="user_fname" placeholder="First Name" type="text" required="true" value="<?php echo ($session->get('user_fname') ? $session->get('user_fname') : '');?>"/>
						</div>
						<div class="col-xs-6 col-md-6">
							<input class="form-control input-lg" name="user_lname" placeholder="Surname" type="text" required="true" value="<?php echo ($session->get('user_lname') ? $session->get('user_lname') : '');?>"/>
						</div>
					</div>
					<input class="form-control input-lg" name="user_email" placeholder="Email" type="text" required="true" value="<?php echo ($session->get('user_email') ? $session->get('user_email') : '');?>"/>
					<input class="form-control input-lg" name="user_reenter_email" placeholder="Re-enter email" type="text" required="true" value="<?php echo ($session->get_once('user_reenter_email') ? $session->get_once('user_reenter_email') : '');?>"/>
					<input class="form-control input-lg" name="password" placeholder="New Password" type="password" required="true"/>
					<label for=""><?php echo __('Birthday'); ?></label>
					<div class="row">
						<div class="col-xs-4 col-md-4">
							<select class="form-control input-lg" name="birthday_month" required>
								<option value="">Month</option>
								<?php
										$monthArray = array('1'=>"Jan",'2'=>"Feb",'3'=>"Mar",'4'=>"Apr",'5'=>"May",'6'=>"Jun",'7'=>"Jul",'8'=>"Aug",'9'=>"Sep",'10'=>"Oct",'11'=>"Nov",'12'=>"Dec");
										foreach ($monthArray as $mkay => $month) {
											echo '<option value='.$mkay.' '.(($session->get('birthday_month') && $session->get_once('birthday_month')==$mkay )? 'selected=""' : '').'>'.$month.'</option>';
										}
								?>
							</select>
						</div>
						<div class="col-xs-4 col-md-4">
							<select class="form-control input-lg" name="birthday_day" required>
								<option value="">Day</option>
								<?php $dayArray = range(31, 1);
									foreach ($dayArray as $day) {
										echo '<option value='.$day.' '.(($session->get('birthday_day') && $session->get_once('birthday_day')==$day )? 'selected=""' : '').' >'.$day.'</option>';
									}
								?>
							</select>
						</div>
						<div class="col-xs-4 col-md-4">
							<select class="form-control input-lg" name="birthday_year" required>
								<option value="">Year</option>
								<?php 
									$years = range(date('Y'), date('Y')-110);
									foreach ($years as $yr) {
										echo '<option value='.$yr.' '.(($session->get('birthday_year') && $session->get_once('birthday_year')==$yr )? 'selected=""' : '').'>'.$yr.'</option>';
									}
								?>
							</select>
						</div>
					</div>
					<label class="radio-inline">
						<input type="radio" name="user_gender" <?php echo (($session->get('user_gender') && $session->get_once('user_gender')=='1' )? 'checked=""' : (!($session->get('user_gender')) ? 'checked=""' : ''));?> id="male" value="1" /><?php echo __('Male'); ?>
					</label>
					<label class="radio-inline">
						<input type="radio" name="user_gender" id="female" <?php echo (($session->get('user_gender') && $session->get_once('user_gender')=='2' )? 'checked=""' : '');?> value="2" /><?php echo __('Female'); ?>
					</label>
					<br />
					<span class="help-block"><?php echo __('By clicking Create my account, you agree to our Terms and that you have read our Data Use Policy, including our Cookie Use'); ?>.</span>
					<button class="btn btn-lg btn-primary btn-block signup-btn" type="submit" name="signup"><?php echo __('Create my account'); ?></button>
			</form>
		
		</div>
	</div>
  </div>
</div>
<?php $session = Session::instance();
	if ($session->get('flash_success_popup')):?>
	<script type="text/javascript">
		$(document).ready(function(){
			$("#myModal").modal('show');
		});
	</script>
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header" style="border-bottom:0">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <p style="padding:10px;"><?php echo $session->get_once('flash_success_popup');?></p>
      </div>
      <div class="modal-footer" style="border-top:0">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Close'); ?></button>
      </div>
    </div>

  </div>
</div>
<?php endif ;
if ($session->get('forgotPassword')){
?>
<script type="text/javascript">
	$(document).ready(function(){
		$("#ForgotPwdModal").modal('show');
	});
</script>
<div id="ForgotPwdModal" class="modal fade in" role="dialog">
   <form action="<?php echo URL::base(TRUE).'index/recover'; ?>" method="post">
      <div class="modal-dialog modal-lg">
         <div class="modal-content">
            <div class="modal-header">
               <div class="row">
                  <div class="banner warning" style="text-align:center;color:red;">
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
						  	<div class="lbl email-phone" for="identify_email"><?php echo __('Email or Phone'); ?></div>
						  	<input type="text" required="" class="inp emailphone" name="identify_email">
						  	<div class="lt-right btnarea">
							 	<button class="btn btn-primary account-searchbtn" name="identify_search" type="submit"><?php echo __('Search'); ?></button><a class="btn btn-primary" href="<?php echo URL::base(TRUE).'index/login'; ?>"><?php echo __('Go to login'); ?></a>
						  	</div>
					   </div>
				   </div>
	         </div>
				<div class="modal-footer">
	            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Close'); ?></button>
	         </div>
         </div>
      </div>
   </form>
</div>
<?php } 
if ($session->get_once('resendConfirmation')){
?>
<script type="text/javascript">
	$(document).ready(function(){
		$("#resendConfirmation").modal('show');
	});
</script>
<div id="resendConfirmation" class="modal fade in" role="dialog">
   <form action="<?php echo URL::base(TRUE).'index/signup'; ?>" method="post">
      <div class="modal-dialog modal-lg">
         <div class="modal-content">
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
					 			<button class="btn btn-primary account-searchbtn" name="resend" type="submit"><?php echo __('Resend'); ?></button> <button type="button" class="btn btn-default" data-dismiss="modal">	<?php echo __('Cancel'); ?></button>
						  	</div>
					   </div>
				   </div>
            </div>
				<div class="modal-footer">
           		<?php echo __(''); ?><button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Close'); ?></button>
               </div>
         	</div>
      </div>
	  <input type="hidden" name="user_email" value="<?php echo $session->get_once('user_email'); ?>" />
	  <input type="hidden" name="activation_code" value="<?php echo $session->get_once('activation_code'); ?>" />
	  <input type="hidden" name="date_created" value="<?php echo $session->get_once('date_created'); ?>" />
	  <input type="hidden" name="user_fname" value="<?php echo $session->get_once('user_fname'); ?>" />
	  <input type="hidden" name="user_lname" value="<?php echo $session->get_once('user_lname'); ?>" />
   </form>
</div>
<?php } 
?>