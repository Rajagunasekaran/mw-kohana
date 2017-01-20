<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<div id="wrap-index" style="overflow:hidden">
	<div id="content_wrapper">
		<h2><?php echo __('Welcome back'); ?> <?php echo ucfirst(strtolower($userDetails->user_fname)).' '.ucfirst(strtolower($userDetails->user_lname)); ?></h2>
		<span style="float:right"><a class="navbar-brand" href="<?php echo URL::base(TRUE).'index/logout'; ?>"><?php echo __('logout'); ?></a></span>
	  </div> 
</div>