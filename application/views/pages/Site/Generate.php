<?php echo $header;
$session = Session::instance();
$bg_class = 'bg-class';
$font_class = 'font-class';
 ?>
<div class="main-wrapper after-nav">
<div id="wrap-index" >
  <!-- Login header nav !-->
  
  <div class="container" id="home">
	<div class="row">
		<div id="content" class="create_password clearfix">
			<div class="col-xs-12">
				<div class="h2class">
							<h2 class="accessible_elem">Create a new password</h2>
				</div>
				<hr>
				<div class="clearfix secureinfo">
							<p class="<?php echo $font_class;?>">To help keep your <?php echo $title;?> account secure in future, please select a new password. If any of your other oline accounts (such as email) are you using your current <?php echo $title;?> password, you'll want to select a new, unique password for each of those accounts too.</p>
				</div>
				<div class="form-createpassowrd">
						
						<form action="" class="nav" role="form" method="post">
						  <div class="col-xs-12 form-container">
							<div class="row">
							    <?php 
									if ($session->get('new_pass_error')): ?>
								  <div class="banner warning" >
									<?php echo $session->get_once('new_pass_error') ?>
								  </div>
								 <?php endif ?>
							</div>
							<div class="row">								
								<div class="form-wrapper">
									<div class="rowitem">
										<label class="lbl3">New Password</label>
                                        <input  data-ajax="false" data-role="none" type="password" class="form-control input-sm" name="new_pass" required value="">
									</div>	
                                    <div class="rowitem">
										<label class="lbl3">Confirm Password</label>
                                        <input  data-ajax="false" data-role="none" type="password" class="form-control input-sm" name="conf_pass" required value="">
									</div>
                                    <div class="rowitem actionbtns">
                                    	<input type="hidden" name="identify" value="<?php echo $userid;?>"/>
										<button data-ajax="false" data-role="none" type="submit" name="generate_submit" class="btn btn-primary ctnbtn <?php echo $bg_class.' '.$font_class;?>">Continue</button>
                                    </div>									
								</div>								
							</div>
							
							
						  </div>
						 
						</form>
					
				</div>
			</div>
	</div>
  </div>
  </div>
<?php
if($session->get('flash_success_popup') != ''){
	$session->set('flash_success_popup','') ; ?>
	<script type="text/javascript">
		$(document).ready(function($){
			$("#popuppwdresetinsite").modal('show');
		});
		$(document).on("click","#siteredirectyes, #closesiteredirectyes",function(){
			window.location.href="<?php echo URL::base(true).'site/'.$session->get('current_site_slug');?>";
		});
	</script>
<div id="popuppwdresetinsite" class="modal fade" role="dialog">
	<div class="vertical-alignment-helper">
	  <div class="modal-dialog">
	    <!-- Modal content-->
	    <div class="modal-content">
	      <div class="modal-header" style="border-bottom:0">
	        <button data-role="none" data-ajax="false" type="button" class="close" id="closesiteredirectyes" data-dismiss="modal">&times;</button>
	      </div>
	      <div class="modal-body">
	      <?php echo __('Your account password was successfully reset.');?>
	      </div>
	      <div class="modal-footer" style="border-top:0">
	      	<button data-role="none" data-ajax="false" type="button" class="btn btn-default" id="siteredirectyes"><?php echo __('ok'); ?></button>
	      </div>
	    </div>
	  </div>
	</div>
</div>
<?php } ?>
  </div>
  <?php echo $footer;?>