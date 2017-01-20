<style>
	.frg-buttons {
		text-align: center;
	}
</style>
<div id="wrap-index" style="overflow:hidden">
   <!-- Login header nav !-->
  <?php //echo $topHeader;?>
  <?php if(isset($userid) && empty($userid)) { ?>
	  <body class="hold-transition login-page">
		<div class="login-box">
		  <div class="login-logo">
				Find Your Account
		  </div>	  
		<div class="login-box-body">
			<form action="" id="header-form" method="post">
				<div id="content" class="col-md-12 clearfix" style="text-align:center">
					<div class="">
						<div class="col-xs-12 col-md-12">
							
							  <div class="lt-left">
								<div class="row">
									<?php $session = Session::instance();
										if ($session->get('flash_error_message')): ?>
									  <div class="banner warning" style="text-align:center;color:red;">
										<?php echo $session->get_once('flash_error_message') ?>
									  </div>
									 <?php endif ?>
									<div class="form-group has-feedback">
										<input type="text"  name="identify_email" class="inp emailphone form-control" placeholder="Email or Phone"  required>
										<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
									</div>
								</div>
								
							  </div>
						</div>
					</div>
					
					<div class="frg-buttons">
						<div class="btn-wrapper">
							<button type="submit" name="identify_search" class="btn btn-primary account-searchbtn">Search</button> &nbsp
							<a href="<?php echo URL::base(TRUE).'admin/index/login'; ?>" class="btn btn-primary">Cancel</a>		
						</div>
					</div>
					
				</div>	
			</form>
		</div>
		
			<div class="row">
				
				</div>
			</div>
		</div><!-- /.login-box -->
	</body>
<?php }else{ ?>
	<body class="hold-transition login-page">
		<div class="login-box">
		  <div class="login-logo">
				Reset Your Password
		  </div>
		<div class="login-box-body">
		  <form action="" id="header-form" method="post">
			  <div class="form-group has-feedback">
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
					<div class="user-name"> <strong> <?php echo ucfirst(strtolower($userDetail->user_fname)).' '.ucfirst(strtolower($userDetail->user_lname));?> </strong></div>
					<div class="lbl1 rp-question">How would you like to reset your password?</div>
					 <?php if(isset($email)){?>
						<div class="emailme-question  clearfix">
							<div class="reset_option">
								<input type="radio" id="send_email" class="" checked="1" value="send_email" name="recover_method">
								Email me a link to reset my password
							</div>
							<div class="secretnote"><span>Email : </span><?php echo $email;?></div>
							<div class="secretnote"></div>
						 </div>
						
						<?php } else { ?>
						  <div class="emailme-question clearfix">
							<div class="reset_option">
								<input type="radio" id="send_email" class="" checked="1" value="send_email" name="recover_method"></div>
							 <div class="resetpass">Text me a code to reset my password</div>
							<div class="secretnote"><span>Phone : </span><?php echo $phone;?></div>
						 </div>   
						<?php } ?>
					
					</div>
				</div> 
				<div class="row">
					<div class="frg-buttons">
						<div class="btn-wrapper">
						
						<input type="submit" class="btn btn-primary reset_action" id="reset_action" name="reset_action" value="Continue">
						
						<a href="<?php echo URL::base(TRUE).'admin/index/recover/'; ?>" class="btn btn-primary notyou"><span>Not You?</span></a>
						<!--
						<div class="nologner"><a href="#">No longer have access to these?</a></div> -->
						</div>
					</div>
				</div>	
			</form>	
		</div>	
		</div><!-- /.login-box -->
	</body>
<!--<script>
  $(function () {
	$('input').iCheck({
	  checkboxClass: 'icheckbox_square-blue',
	  radioClass: 'iradio_square-blue',
	  increaseArea: '20%' // optional
	});
  });
</script> -->
<?php } ?>