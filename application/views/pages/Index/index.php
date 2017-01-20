<div id="wrap-index" style="overflow:hidden">
   <!-- Login header nav !-->
  <div class="container" id="home">
	<div class="row">
		<div class="col-md-7">
			<h3><b><?php echo __('Your last My Workouts login session must\'ve expired. Please login again.'); ?>.<b></h3>
			<img style="display:none" src="<?php echo URL::base().'assets/images/homepage_logo.png';?>" class="img-responsive">
			<hr>
		</div>
		<div class="col-md-5">
			<div class="navbar-collapse nav-collapse">
				<form action="<?php echo URL::base(TRUE).'index'; ?>" class="navbar-form navbar-right" id="header-form" role="form" method="post">
				  <div class="lt-left">
					  <div class="form-group">
						<label for="user_email"><?php echo __('Email'); ?></label><br>
						<input type="text" class="form-control input-sm login-input" id="email" name="user_email" >
					  </div>
					  <div class="form-group">
						<label for="password"><?php echo __('Password'); ?></label><br>
						<input type="password" class="form-control input-sm login-input" id="pass" name="password" >
					  </div>
					  <div class="checkbox login-input">
						<label>
						  <input type="checkbox" name="remember" id="remember" value="1">   <label for="remember" style="color: #ffffff"><?php echo __('Remember me'); ?></label>
						</label>
						<label class="forgot-passwrd hide">
						  <a data-ajax='false'  href="<?php echo URL::site('index/recover');?>" alt="Forgotten your password?"><label class="forgot-passwrd" style="color: #ffffff"><?php echo __('Forgotten your password'); ?>?</label></a>
						</label>
						<label class="join-now  hide" style="margin-top:10px;">
						  <a data-role="none" data-ajax="false" href="javascript:void(0);" alt="Join Now" data-toggle="modal" data-target="#joinModal"><label class="join-now font_class"><?php echo __('Not registered yet? Join now'); ?>.</label></a>
						</label>
					  </div>
				  </div>
				  <div class="lt-right">
					<button type="submit" name="login" class="login-btn btn btn-sm btn-default"><?php echo __('Login'); ?></button>
				  </div>
				</form>
		  </div>
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
 