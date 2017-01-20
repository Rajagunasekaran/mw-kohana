<div id="wrap-index" style="overflow:hidden">
   <!-- Login header nav !-->
  <?php echo $topHeader;?>
  <div class="container" id="home">
	<div class="row">
		<div class="col-md-7">
			<h3><b><?php echo __('My Workouts provides resources to help you to maintain a fit and active lifestyle'); ?>.<b></h3>
			<img style="display:none" src="<?php echo URL::base().'assets/images/homepage_logo.png';?>" class="img-responsive">
			<hr>
		</div>
		<div class="col-md-5">
			<form action="" method="post" class="form" role="form">
					<h2><b><?php echo __('Create your account'); ?></b></h2>
					<h4><?php echo __('Get training today with loads of valuable resources and features'); ?>.</h4>
					<div class="row">
						<div class="col-xs-6 col-md-6">
							<input class="form-control input-lg" name="user_fname" placeholder="First Name" type="text" required="true" autocomplete="off"/>
						</div>
						<div class="col-xs-6 col-md-6">
							<input class="form-control input-lg" name="user_lname" placeholder="Surname" type="text" required="true" autocomplete="off"/>
						</div>
					</div>
					<input class="form-control input-lg" name="user_email" placeholder="Email" type="text" required="true" autocomplete="off"/>
					<input class="form-control input-lg" name="user_reenter_email" placeholder="Re-enter email" type="text" required="true" autocomplete="off"/>
					<input class="form-control input-lg" name="password" placeholder="New Password" type="password" required="true" autocomplete="off"/>
					<label for=""><?php echo __('Birthday'); ?></label>
					<div class="row">
						<div class="col-xs-4 col-md-4">
							<select class="form-control input-lg" name="birthday_month" required autocomplete="off">
								<option value="">Month</option>
								<?php
										$monthArray = array('01'=>"Jan",'02'=>"Feb",'03'=>"Mar",'04'=>"Apr",'05'=>"May",'06'=>"Jun",'07'=>"Jul",'08'=>"Aug",'09'=>"Sep",'10'=>"Oct",'11'=>"Nov",'12'=>"Dec");
										foreach ($monthArray as $mkay => $month) {
											echo '<option value='.$mkay.'>'.$month.'</option>';
										}
								?>
							</select>
						</div>
						<div class="col-xs-4 col-md-4">
							<select class="form-control input-lg" name="birthday_day" required autocomplete="off">
								<option value="">Day</option>
								<?php $dayArray = range(31, 1);
									foreach ($dayArray as $day) {
										echo '<option value='.$day.'>'.$day.'</option>';
									}
								?>
							</select>
						</div>
						<div class="col-xs-4 col-md-4">
							<select class="form-control input-lg" name="birthday_year" required autocomplete="off">
								<option value="">Year</option>
								<?php 
									$years = range(date('Y'), date('Y')-110);
									foreach ($years as $yr) {
										echo '<option value='.$yr.'>'.$yr.'</option>';
									}
								?>
							</select>
						</div>
					</div>
					<label class="radio-inline">
						<input type="radio" name="user_gender" checked="" id="male" value="1" /><?php echo __('Male'); ?>
					</label>
					<label class="radio-inline">
						<input type="radio" name="user_gender" id="female" value="2" /><?php echo __('Female'); ?>
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
	if($session->get('flash_success_popup')) { ?>
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
<?php }elseif($session->get('flash_activation_popup')){ ?>
	<script type="text/javascript">
		$(document).ready(function(){
			$("#myModal").modal('show');
		});
	</script>
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header" style="border-bottom:0">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <p style="padding:10px;"><?php echo $session->get_once('flash_activation_popup');?></p>
      </div>
      <div class="modal-footer" style="border-top:0">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Close'); ?></button>
      </div>
    </div>

  </div>
</div>
<?php } ?>
 