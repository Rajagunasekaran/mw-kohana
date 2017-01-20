<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<body class="hold-transition login-page">
    <div class="login-box">
      <div class="login-logo">
			Forgot Password
      </div>
      <div class="login-box-body">
        
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
        <form action="<?php echo URL::site(Request::current()->uri()); ?>" method="post">
          <div class="form-group has-feedback">
            <input type="text" name="email" class="form-control" placeholder="Email" required="true">
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
          </div>
          <div class="row">
            <div class="col-xs-12">
              <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
            </div><!-- /.col -->
          </div>
        </form>

       
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

