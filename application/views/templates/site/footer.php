<?php defined('SYSPATH') OR die('No direct access allowed.'); 
$session = Session::instance();
$siteurl = $data['siteurl'];//$session->get('siteurl');
$siteid = $data['siteid'];//$session->get('siteid');
$bg_class = 'bg-class';
$font_class = 'font-class';
if(isset($data['bg_color']) && $data['bg_color']!='') {
	$bg_color = $data['bg_color'];
}
$sitesmodel = ORM::factory('admin_sites');
$footerMenus = $sitesmodel->get_site_footer_menu($data['siteidpk']);
$socialnet_url = $sitesmodel->getgeneraltable("site_id = '".$data['siteidpk']."' ",'sitehomepages');
//echo "<pre>";print_r($socialnet_url);echo "</pre>";
 ?>
<!-- FOOTER STARTS HERE -->
    <footer id="footer" class='footer_section'>
		  <div class="footer-wrapper footer-top-bg <?php echo $bg_class;?>">
            <div class="container">
					 <div class="row show-grid">
						  <div class="span12 col-lg-12">
							<div class="row show-grid">
								<?php if(isset($footerMenus) && count($footerMenus)>0) { ?> 
									<div class="span2 col-lg-2 footer-left">
										<ul>
											<?php foreach($footerMenus as $key => $value) { 
												if($key%4==0 && $key!=0) { ?>
													</ul>
													</div>
													<div class="span2 col-lg-2 footer-left">
													<ul>
												<?php } ?>
												<li><a data-ajax="false" data-role="none" class="<?php echo $font_class;?>" href="<?php echo $value['url'];?>"><?php echo $value['title'];?></a></li>
											<?php } ?>
										</ul>
									</div>
									<?php 
								} else { ?>
									<div class="span2 col-lg-2 footer-left">
										<ul>
											<li><a data-ajax="false" data-role="none" class="<?php echo $font_class;?>" href="#"><?php echo __('Help'); ?></a></li>
											<li><a data-ajax="false" data-role="none" class="<?php echo $font_class;?>" href="#"><?php echo __('FAQs'); ?></a></li>
											<li><a data-ajax="false" data-role="none" class="<?php echo $font_class;?>" href="#"><?php echo __('Contact Us'); ?></a></li>
											<li><a data-ajax="false" data-role="none" class="<?php echo $font_class;?>" href="#"><?php echo __('Site Map'); ?></a></li>
										</ul>
									</div>
									<div class="span2 col-lg-2 footer-center">
										<ul>
											<li><a data-ajax="false" data-role="none" class="<?php echo $font_class;?>" href="#"><?php echo __('PRIVACY'); ?></a></li>
										</ul>
									</div>
									<div class="span2 col-lg-2 footer-center">
										<ul>
											<li><a data-ajax="false" data-role="none" class="<?php echo $font_class;?>" href="#"><?php echo __('T&Cs'); ?></a></li>
										</ul>
									</div>
								<?php } 
								if(isset($footerMenus) && count($footerMenus)>0) {
									$menuCount = count($footerMenus);
									if($menuCount<=4) { ?>
										<div class="span2 col-lg-2 footer-center">&nbsp;</div>
										<div class="span2 col-lg-2 footer-center">&nbsp;</div>
									<?php } else if($menuCount<=8) { ?>
										<div class="span2 col-lg-2 footer-center">&nbsp;</div>
									<?php }
								} ?>
								<div class="span2 col-lg-2 footer-center">&nbsp;</div>
								<!-- FOOTER: NAVIGATION LINKS -->
								<div class="span4 col-lg-4 footer-right">
									<h4 class="center-title <?php echo $font_class;?>"><?php echo __('Connect With Us'); ?></h4>
									<ul class="social-links">
										<?php if(isset($socialnet_url[0]['facebook_status']) && $socialnet_url[0]['facebook_status'] == 1){ ?>
										<li>
											<a data-ajax="false" data-role="none" href="<?php echo (isset($socialnet_url[0]['social_facebook_url']) && $socialnet_url[0]['social_facebook_url'] != '' ? $socialnet_url[0]['social_facebook_url'] : '#');?>"><i class="icon-facebook fa fa-facebook"></i></a>
										</li>
										<?php } if(isset($socialnet_url[0]['instagram_status']) && $socialnet_url[0]['instagram_status'] == 1){ ?>
										<li>
											<a data-ajax="false" data-role="none" href="<?php echo (isset($socialnet_url[0]['social_twitter_url']) && $socialnet_url[0]['social_twitter_url'] != '' ? $socialnet_url[0]['social_twitter_url'] : '#');?>"><i class="icon-instagram fa fa-instagram"></i></a>
										</li>
										<?php } if(isset($socialnet_url[0]['pinterest_status']) && $socialnet_url[0]['pinterest_status'] == 1){ ?>
										<li>
											<a data-ajax="false" data-role="none" href="<?php echo (isset($socialnet_url[0]['social_linkedin_url']) && $socialnet_url[0]['social_linkedin_url'] != ''  ? $socialnet_url[0]['social_linkedin_url'] : '#');?>"><i class="icon-pinterest fa fa-pinterest"></i></a>
										</li>
										<?php } ?>
									</ul>
									<div class="news_message"></div>
									<!--form class="subscribe-form" id="subscribe" action="" method="post"-->
									<input data-role="none" data-ajax="false" type="email" id="subemail" name="subemail" required placeholder="email" value="" />
									<input data-ajax="false" data-role="none" type="button" name="subscribe"  value="subscribe" onclick='site_subscriber()' />
									<!--/form-->
								</div>
							</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom footer-bottom-bg ">
				<div class="container">
					 <div class="row show-grid">
						  <!-- FOOTER: COPYRIGHT TEXT -->
                    <div class="span12 col-lg-12">
								<p class="<?php echo $font_class;?>"><?php
								
								$demo = "Results may vary. Exercise and healthy diet are necessary to achieve and maintain weight loss. Please consult your healthcare professional before starting our program.";
								echo __($demo);//(isset($sitecontent[0]['footer_content']) ? $sitecontent[0]['footer_content'] : __($demo));?></p>
                    </div>
                </div>
            </div>
        </div>
	 </footer>
<style type="text/css">
	@media (min-width: 768px) {
	  #joinModal .modal-dialog {
	    width: 750px;
	  }
	}
	@media (min-width: 992px) {
	  #joinModal .modal-dialog {
	    width: 970px;
	  }
	}
	@media (min-width: 1200px) {
	  #joinModal .modal-dialog{width:1170px;}
	}
</style>
<!-- Modal -->
<div class="modal fade" id="joinModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="vertical-alignment-helper">
  		<div class="modal-dialog">
 			<div class="modal-content">
		      <div class="modal-header">
					<div class="row">
						<div class="title-header">
							<div class="col-xs-3 aligncenter"></div>
							<div class="col-xs-6 aligncenter"></div>
							<div class="col-xs-3"><button data-ajax="false" data-role="none" type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button></div>
						</div>
					</div>
				</div>
		      <div class="modal-body">
		         <div id="wrap-index">
					  <!-- Login header nav !-->
					  <?php //echo $topHeader;?>
					  	<div class="" id="home">
							<div class="row">
								<div class="col-md-12">
									<div class="row signup-popup-error">
										<?php $session = Session::instance(); ?>
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
									<form id="site-singup-form" action="<?php echo $siteurl.'page/signup';?>" method="post" class="form" role="form" data-role="none" data-ajax="false">
										<input type="hidden" name="signup_from" value="signupModal"/>
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
										<input data-role="none" data-ajax="false" class="form-control input-lg" name="user_reenter_email" placeholder="Re-enter email" type="text" required="true" value="<?php echo ($flashsuccess && $session->get_once('user_reenter_email') ? $session->get_once('user_reenter_email') : '');?>"/>
										<input data-role="none" data-ajax="false" class="form-control input-lg" id="password" name="password" placeholder="New Password" type="password" required="true"/>
										<input data-role="none" data-ajax="false" class="form-control input-lg" id="reenter_password" name="reenter_password" placeholder="Re-enter password" type="password" required="true"/>
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
													<?php $dayArray = range(31, 1);
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
										<span class="help-block"><?php echo __("By clicking Create my account, you agree to our Terms and that you have read our Data Use Policy, including our Cookie Use"); ?>.</span>
										<button data-ajax="false" data-role="none" class="btn btn-lg btn-primary btn-block signup-btn <?php echo $bg_class;?>" type="submit" name="signup"><?php echo __('Create my account'); ?></button>
									</form>
								</div>
							</div>
					  	</div>
					</div>
		      </div>
		      <div class="modal-footer">
		        <button data-ajax="false" data-role="none" type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Close'); ?></button>
		      </div>
	    	</div>
	    	<!-- /.modal-content -->
	  	</div>
	 	<!-- /.modal-dialog -->
 	</div>
</div>
<!-- /.modal -->
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
			        	<button data-role="none" data-ajax="false" type="button" class="close" data-dismiss="modal" onclick="closeSignupModal();">&times;</button>
			      </div>
			      <div class="modal-body">
			        	<p style="padding:10px;"><?php echo $session->get_once('flash_success_popup');?></p>
			      </div>
			      <div class="modal-footer" style="border-top:0">
			        	<button data-role="none" data-ajax="false" type="button" class="btn btn-default" data-dismiss="modal" onclick="closeSignupModal();"><?php echo __('Close'); ?></button>
			      </div>
		    	</div>
		  	</div>
	  	</div>
	</div>
<?php endif ;




	if ($session->get_once('flash_resendactivation_popup')==true):?>
	<script type="text/javascript">
		$(document).ready(function($){
			
			$("#ResendActivationModal").modal('show');
		});
	</script>
	<!-- Modal -->
	<div id="ResendActivationModal" class="modal fade" role="dialog">
		<div class="vertical-alignment-helper">
		  	<div class="modal-dialog">
				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Resend Account Activation Link?</h4>
					</div>
					<div class="modal-body">
						<p>
							You have not yet confirmed your account
						</p>
						<center>
						<button type="button" class="btn btn-primary" onclick='resend_link()' >Yes</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">No</button>
						</center>
					</div>
					<div class="modal-footer">
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
			   	<input type="hidden" name="signup_from" value="signupModal">
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
			 	<form action="<?php echo $siteurl.'page/signup';?>" method="post" class="form" role="form">
			 		<input type="hidden" name="signup_from" value="signupModal">
	            <div class="modal-header">
                 	<button data-role="none" data-ajax="false" type="button" class="close" data-dismiss="modal" onclick="closeSignupModal();">&times;</button>
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
								 	<button data-role="none" data-ajax="false" class="btn btn-primary account-searchbtn" name="resend" type="submit"><?php echo __('Resend'); ?></button>
								 	<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Cancel'); ?></button>
							  </div>
						   </div>
					   </div>
	            </div>
					<div class="modal-footer">
	               <button data-role="none" data-ajax="false" type="button" class="btn btn-default" data-dismiss="modal" onclick="closeSignupModal();"><?php echo __('Close'); ?></button>
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
<?php } ?>
	</div>
    <!-- END FOOTER -->
    <!-- Placed at the end of the document so the pages load faster -->
<?php echo HTML::script('assets/media/js/classie.js'); ?>
<?php echo HTML::script('assets/media/js/slick.min.js'); ?>
<?php echo HTML::script('assets/media/js/jquery.imagesloaded.min.js'); ?>
<?php if(Auth::instance()->logged_in()){ 
	echo HTML::script("assets/js/xdate.js");
	echo HTML::script("assets/js/xdate.i18n.js");
	echo HTML::script("assets/js/jquery.mobile-1.3.0.min.js");
	echo HTML::script("assets/js/mobipick.js");
	echo HTML::script("assets/js/SimpleAjaxUploader.js");
	echo HTML::script("assets/js/formValidation.min.js");
	echo HTML::script("assets/js/bootstrap-validate.min.js");
	echo HTML::script('assets/plugins/select2-bootstrap-theme-master/select2.js');
	echo HTML::script("assets/plugins/cropper/dist/cropper.min.js");
	echo HTML::script("assets/plugins/cropper/demo/js/imglib-main.js");
	echo HTML::script("assets/js/jquery.confirm.min.js");
	echo HTML::script("assets/js/typeahead.bundle.min.js");
	echo HTML::script("assets/js/bootstrap-tagsinput.min.js");
} ?>
<?php //echo HTML::script('assets/media/rs-plugin/js/jquery.themepunch.plugins.min.js'); ?>
<?php //echo HTML::script('assets/media/rs-plugin/js/jquery.themepunch.revolution.min.js'); ?>
<?php //echo HTML::script('assets/media/js/revolution.custom.js'); ?>
<?php echo HTML::script('assets/media/js/jquery.validate.min.js'); ?>
<?php echo HTML::script('assets/media/js/modernizr.custom.js'); ?>
<?php echo HTML::script('assets/media/js/jquery.dlmenu.js'); ?>
<?php echo HTML::script('assets/media/js/custom.js'); ?>
<?php echo HTML::script('assets/media/js/bootstrap-select.js'); ?>
<?php echo HTML::script('assets/media/js/validator.min.js'); ?>
<?php echo HTML::script('assets/media/js/min/modernizr-custom-v2.7.1.min.js'); ?>
<?php echo HTML::script('assets/media/js/min/hammer-v2.0.3.min.js'); ?>
<?php echo HTML::script('assets/media/js/min/flickerplate.min.js'); ?>
<?php echo HTML::script('assets/plugins/iCheck/icheck.js'); ?>
<div id="userModal" class="modal fade" role="dialog" tabindex="-1"></div>
<div id="myModal" class="modal fade" role="dialog" tabindex="-1"></div>
<div id="myprofileoptionimagemodal" class="modal fade bs-example-modal-sm" role="dialog" tabindex="-1"></div>
<!-- Modal for displaying the error messages -->
<div id="errorMessage-modal" class="modal fade" role="dialog" tabindex="-1">
	<div class="vertical-alignment-helper">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<div class="mobpadding">
						<div class="border">
							<div class="col-xs-2">
								<a href="#" title="<?php echo __('Back'); ?>" onclick="$('#errorMessage-modal').modal('hide');" class="triangle" data-ajax="false" data-role="none">
									<i class="fa fa-chevron-left"></i>
								</a>
							</div>
							<div class="col-xs-8 optionpoptitle"><?php echo __('Validation Errors'); ?></div>
							<div class="col-xs-2"></div>
						</div>
					</div>
				</div>
				<div class="modal-body">
					<div class="form-group modal-validerror">
						<div class="row">
							<div class="required-err">Please Fill The Required Fields</div>
						</div>
					</div>
					<div id="validation-errors" class="col-xs-12"></div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" onclick="$('#errorMessage-modal').modal('hide');" data-ajax="false" data-role="none"><?php echo __('Close'); ?></button>
				</div>
			</div>
		</div>
	</div>
</div>
<?php if(Auth::instance()->logged_in()){
require_once(APPPATH.'views/templates/front/template-imglibrary.php');
require_once(APPPATH.'views/templates/front/imglib-imgeditor.php');
} ?>
<?php if(Auth::instance()->logged_in()){ ?>
<script>
$(document).ready(function()
{
	if($('small.autoshownotification').length>0){ 
		setInterval(function(){get_notify();},5000);
	}
});
</script>
<?php } ?>
<script type="text/javascript"> 
var siteSlug='<?php echo ($session->get('current_site_slug') !='' ? $session->get('current_site_slug') : ''); ?>';
function site_subscriber(){
	 var em = jQuery("#subemail").val();
	 if (em) {
		  jQuery.ajax({
				url: "<?php echo URL::site(); ?>ajax/subscribe",
				method: 'post',
				data: {	email: em,siteid:'<?php echo $siteid; ?>'	},
				success: function(content) {
					jQuery(".news_message").html("<span style='color:green'><?php echo __('Subscribed successfully'); ?>...!</apan>");
					jQuery("#subemail").val('');
				}
			});
	 }else{
		  jQuery(".news_message").html("Please enter your email to subscribe...!");
	 }
}
$(document).ready(function () {
   $("#password, #reenter_password").keyup(checkPasswordMatch);
});
function checkPasswordMatch() {
    var password = $("#password").val();
    var confirmPassword = $("#reenter_password").val();

    if (password != confirmPassword)
        $("#reenter_password").addClass("warningpwd");
    else
        $("#reenter_password").removeClass("warningpwd");
}
<?php if (Auth::instance()->logged_in()){ ?>
$('body').bind('click', function(e) {
    if($(e.target).closest('.navbar-toggle').length == 0) {
        // click happened outside of .navbar, so hide
        var opened = $('.navbar-collapse').hasClass('collapse in');
        if ( opened === true ) {
            $('.navbar-collapse').collapse('hide');
        }
    }
});
<?php } ?>
</script>
</body>
</html>