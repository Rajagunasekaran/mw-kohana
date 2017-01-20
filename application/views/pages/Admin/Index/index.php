<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<body class="hold-transition login-page">
    <div class="login-box">
      <div class="login-logo">
      <?php echo __('My Workouts'); ?>
      </div>
      <div class="login-box-body">
        <p class="login-box-msg"><?php echo I18n::get('Sign in to start your session'); ?></p>
    <div id="errors">
      <?php if (isset($error_messages) && count($error_messages)>0): ?>
      <div class="message_stack">
        <ul>
          <?php foreach ($error_messages as $error_message): ?>
            <li><?php echo $error_message; ?></li>
          <?php endforeach ?>
        </ul>
      </div>
      <?php endif; ?>
    </div>
        <form action="<?php echo URL::base().'admin/'; //echo URL::site(Request::current()->uri()); ?>" method="post">
          <div class="form-group has-feedback">
            <input type="text" name="user_email" class="form-control" placeholder="<?php echo __('Email / Username'); ?>" required="true">
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="password" name="password" class="form-control" placeholder="<?php echo __('Password'); ?>" required="true">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="row">
            <div class="col-xs-8">
              <div class="checkbox icheck">
                <label>
                  <input type="checkbox" name="remember"> <?php echo __('Remember Me'); ?>
                </label>
              </div>
            </div><!-- /.col -->
            <div class="col-xs-4">
              <button type="submit" class="btn btn-primary btn-block btn-flat"><?php echo __('Sign In'); ?></button>
            </div><!-- /.col -->
          </div>
        </form>

        <a href="<?php echo URL::base().'admin/index/recover'?>"><?php echo __("I forgot my password"); ?></a><br>
      </div><!-- /.login-box-body -->
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

