<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
  <div class="navbar navbar-default navbar-fixed-top">
	<div class="container">
	  <div class="navbar-header nav-head">
		<button data-role="none" class="navbar-toggle nav-bar tour-step tour-step-eight" data-toggle="collapse" data-target=".navbar-collapse">
		  <span class="icon-bar"></span>
		  <span class="icon-bar"></span>
		  <span class="icon-bar"></span>
		</button>
		<small id="ajaxnotifyone" class="chat-alert label label-danger autoshownotification" style="float:right"><?php echo Session::Instance()->get('chatnotify');?></small>
		<a class="navbar-brand text-hide" data-ajax='false'  href="<?php echo URL::base(TRUE).'index'; ?>"><?php echo $site_title;?></a>
		<div class="col-xs-3">
			<div class="navbar-brand moblogo tour-step tour-step-two"><a data-ajax='false'  href="<?php echo URL::base(TRUE).'index'; ?>"><img onload="checkspeedTest()" src="<?php echo URL::site().'assets/img/mw-icon.png?random='.rand(1111111111,9999999999); ?>"></a></div>
		</div>
	  </div>
	  <?php if (!Auth::instance()->logged_in()){ ?>
		  <div class="collapse navbar-collapse nav-collapse">
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
					  <div class="checkbox login-input" >
						<label>
						  <input type="checkbox" name="remember" id="remember" value="1">   <label for="remember" style="color: #ffffff"><?php echo __('Remember me'); ?></label>
						</label>
						<label style="float:right;margin-top:2px;">
						  <a data-ajax='false'  href="<?php echo URL::site('index/recover');?>" alt="Forgotten your password?"><label style="color: #ffffff"><?php echo __('Forgotten your password'); ?>?</label></a>
						</label>
					  </div>
				  </div>
				  <div class="lt-right">
					<button type="submit" name="login" class="login-btn btn btn-sm btn-default"><?php echo __('Login'); ?></button>
				  </div>
				</form>
		  </div>
	 <?php } else { ?>
		  <div class="collapse navbar-collapse nav-collapse">
			<div class="lt-left">
				<ul class="nav navbar-nav navbar-right">
					<li><a data-ajax='false' href="<?php echo URL::base(TRUE).'index'; ?>"><span class="fa fa-home"></span><?php echo __('Home/Dashboard'); ?></a></li>
					<li><a data-ajax='false' href="javascript:void(0);" onclick="showUserModel()"><span class="fa fa-user"></span><?php echo __('Me'); ?></a></li>
					<li><a data-ajax='false' href="<?php echo URL::base(TRUE).'networks/connections'; ?>"><span class="fa fa-users"></span><?php echo __('Messages'); ?>   <small id="ajaxnotifytwo" class="chat-alert label label-danger autoshownotification" style="float:right"><?php echo Session::Instance()->get('chatnotify');?></small></a></li>
					<li class="hide"><a data-ajax='false' href="javascript:void(0);"><span class="fa fa-bell"></span><?php echo __('Notifications'); ?></a></li>
					<li><a data-ajax='false' href="javascript:void(0);" onclick="contactUsModal()"><span class="fa fa-comment-o"></span><?php echo __('Contact Us'); ?></a></li>
					<li class="tour-step tour-step-nine"><a data-role="none" data-ajax='false' href="javascript:void(0);" onclick="openTopPopup('help');"><span class="fa fa-question"></span><?php echo __('Help &amp; FAQ&rsquo;s'); ?></a></li>
					<li><a data-ajax='false' href="<?php echo URL::base(TRUE).'settings/preference'; ?>"><span class="fa fa-cogs"></span><?php echo __('Preference Settings'); ?></a></li>
					<li><a data-ajax='false' href="<?php echo URL::base(TRUE).'users/trainer'; ?>"><span class="fa fa-star-o"></span><?php echo __('Personal Trainers'); ?></a></li>
					<li><a data-ajax='false' href="<?php echo URL::base(TRUE).'index/logout'; ?>"><span class="fa fa-sign-out"></span><?php echo __('Logout'); ?></a></li>
				</ul>
			</div>
		  </div>
	  <?php } ?>
	</div>
  </div>
  <div id="topheaderpopup" class="modal fade bs-example-modal-sm" role="dialog" data-keyboard="false" data-backdrop="static"></div>
