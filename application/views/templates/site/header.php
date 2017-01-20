<?php defined('SYSPATH') OR die('No direct access allowed.');
$session	= Session::instance();
$siteurl	= (isset($data['siteurl'])) ? $data['siteurl'] : '';
$siteSlug 	= ($session->get('current_site_slug') ? $session->get('current_site_slug').'/' : '');
$siteSlug 	= ($session->get('current_site_slug') ? $session->get('current_site_slug').'/' : '');
$site_title	= (isset($data['title'])) ? $data['title'] : '';
$site_logo	= (isset($data['site_logo'])) ? $data['site_logo'] : '';
$error_msg	= $error_email = '';
if($session->get('common_error')!='') {
	$error_msg = __($session->get_once('common_error')).'<br >';
}
if($session->get('user_email_header_error')!='') { 
	$error_msg .= __($session->get_once('user_email_header_error')).'<br >';
}
if($session->get('password_header_error')!='') {
	$error_msg .= __($session->get_once('password_header_error')).'<br >';
}
if(isset($_GET['cookie']) && $_GET['cookie']=='0' && $_GET['form']=='login') {
	$error_msg .= __('Please enable your browser cookie');
}

if($session->get('flush_user_email') !='')
	$error_email = $session->get_once('flush_user_email');
$bg_class = 'bg-class';
$font_class = 'font-class';
$unique_class = (isset($data['siteid'])) ? $data['siteid'] : '';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <title><?php echo $site_title;?></title>
        <meta name="keywords" content="" />
		<?php echo header('Cache-Control: no-cache, no-store, must-revalidate'); 
			  echo header('Pragma: no-cache');
			  echo header('Expires: 0'); 
		?>
        <meta name="description" content="" />
        <meta name="author" content="" />
        <meta name="robots" content="follow, index" />
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
		<?php if(isset($data['social_image']) && !empty($data['social_image'])){ ?>
			<meta property="og:image" content="<?php echo $data['social_image'];?>" />
		<?php }if(isset($data['social_desc']) && !empty($data['social_desc'])){ ?>
			<meta property="og:description" content="<?php echo htmlspecialchars_decode($data['social_desc']);?> " />
		<?php }if(isset($data['social_video']) && !empty($data['social_video'])){ ?>
			<meta property="og:video" content="<?php echo $data['social_video'];?>" />
		<?php }if(isset($data['social_title'])){ ?>
			<meta property="og:title" content="<?php echo (!empty($data['social_title']) ? $data['social_title'] : $site_title);?>" />
		<?php } ?>
		<meta property="og:url" content="<?php echo URL::site(NULL, 'http').(!empty($siteSlug) ? 'site/'.$siteSlug : '');?>" />
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="mobile-web-app-capable" content="yes">
		<link rel="shortcut icon" href="<?php echo URL::site().'assets/img/mw_favicon.png'; ?>" />

		<link href="<?php echo URL::site().'assets/img/apple-touch-icon-mw152x152.png'; ?>" rel="apple-touch-icon" sizes="152x152" />
		<link href="<?php echo URL::site().'assets/img/apple-touch-icon-mw167x167.png'; ?>" rel="apple-touch-icon" sizes="167x167" />
		<link href="<?php echo URL::site().'assets/img/apple-touch-icon-mw180x180.png'; ?>" rel="apple-touch-icon" sizes="180x180" />
		<link href="<?php echo URL::site().'assets/img/icon-mw192x192.png'; ?>" rel="icon" sizes="192x192" />
		<link href="<?php echo URL::site().'assets/img/icon-mw128x128.png'; ?>" rel="icon" sizes="128x128" />

        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		  <?php echo HTML::style("assets/css/bootstrap.min.css"); ?>
		  <?php echo HTML::style('assets/css/jquery-ui.css'); ?>
		  <?php echo HTML::style('assets/css/jquery.mobile-1.3.0.min.css');?>
		  <?php echo HTML::style("assets/media/css/css3-menu.css"); ?>
		  <?php echo HTML::style("assets/media/css/color_scheme.css"); ?>
		  <?php echo HTML::style("assets/media/css/flickerplate.css"); ?>
		  <?php echo HTML::style("assets/media/css/font-awesome.min.css"); ?>		
		  <?php echo HTML::style("assets/media/css/slick.css"); ?>
		  <?php echo HTML::style("assets/media/css/component.css"); ?>
		  <?php echo HTML::style("assets/media/css/bootstrap-select.css"); ?>
		  <?php echo HTML::style("assets/media/css/custom.css"); ?>
		  <?php echo HTML::style("assets/plugins/iCheck/square/blue.css"); ?>	
		  <?php echo HTML::style("assets/plugins/morris/morris.css"); ?>
		  <?php echo HTML::style("assets/plugins/select2-bootstrap-theme-master/select2.css"); ?>
		  <?php echo HTML::style("assets/plugins/select2-bootstrap-theme-master/select2-bootstrap.css"); ?>
		  <?php echo HTML::style("assets/plugins/cropper/dist/cropper.min.css"); ?>
		  <?php echo HTML::style("assets/plugins/cropper/demo/css/main.css"); ?>
		  <?php echo HTML::style("assets/css/bootstrap-tagsinput.css"); ?>
		  <?php echo HTML::script('assets/js/jquery.js'); ?>
		  <?php //echo HTML::script('assets/js/jquery-ui.js');?>
		  <?php echo HTML::script('assets/js/bootstrap.min.js'); ?>
		  <?php echo HTML::script('assets/plugins/morris/morris.min.js'); ?>
		  <?php echo HTML::script('assets/plugins/morris/raphael.min.js'); ?>
		  <?php echo HTML::script('assets/plugins/morris/morris-profiledata.js'); ?>
		  <script>
			var start = (new Date()).getTime();
			var THRESHOLD = 2500; // a number we will calculate below
			function checkspeedTest() {
				var duration = (new Date()).getTime() - start;
				if (duration < THRESHOLD) {
					//success
					return;
				} else {
					alert('Our test shows that your internet connection might be unstable or running slowly. This may effect your browsing experience.');
				}
			}
			<?php 
				if (!Auth::instance()->logged_in()){ 
					$random = rand(1111111111,9999999999);
					$session->set('loginack',$random);
			?>
			var loginack='<?php echo $random;?>';
			var siteId='<?php echo ($session->get('current_site_id') !='' ? $session->get('current_site_id') : '1'); ?>';
			<?php } ?>
			var user_allow_page = "<?php echo $session->get('user_allow_page');?>";
			var user_from = '<?php  echo 'front'; ?>';
			var site_url='<?php echo $session->get('siteurl');?>';
			var siteUrl='<?php echo URL::site(); ?>';
			var urlwithsite = '<?php echo $siteurl ?>';
			var siteName='<?php echo ($session->get('current_site_name') !='' ? $session->get('current_site_name') : 'site'); ?>';
			var siteAgeLimit='<?php echo ($session->get('current_site_agelimit') !='' ? $session->get('current_site_agelimit') : '18'); ?>';
			var $currentElement;
			$(document).on('mobileinit', function () {
				$.mobile.ignoreContentEnabled = true;
			});
			var isMobile = false; //initiate as false
			// device detection
			if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent) 
				|| /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0,4))){ isMobile = true;
			}
			$(document).ready(function(){
				<?php if((isset($_GET['page']) && $_GET['page']=='contact' && Request::current()->Controller() != 'Contact') || ($session->get_once('popup-page')=='contact' && Request::current()->Controller() != 'Contact')) { ?>
					contactUsModal();
				<?php }?>
				<?php if (!Auth::instance()->logged_in()){ ?>
					if(isMobile)
						$('input#remember').prop('checked', true);
				<?php }?>
			});
			/*language setting*/
			<?php $i18n_dictionary = i18n::load(i18n::lang()); ?>
			// <![CDATA[
			var i18n = {
				<?php $i18n_count = 0;
				foreach ($i18n_dictionary as $key => $val) {
					echo "'". html_entity_decode(htmlentities($key, ENT_QUOTES)) ."':'". html_entity_decode(htmlentities($val, ENT_QUOTES)) ."'". ((count($i18n_dictionary) - 1 == $i18n_count++) ? '' : ',');
				} ?>
			};
			function __(trans_str) {
				return (i18n[trans_str] ? i18n[trans_str] : trans_str);
			};
			// ]]>
		  </script>
		  <script>(function(a,b,c){if(c in b&&b[c]){var d,e=a.location,f=/^(a|html)$/i;a.addEventListener("click",function(a){d=a.target;while(!f.test(d.nodeName))d=d.parentNode;"href"in d&&(d.href.indexOf("http")||~d.href.indexOf(e.host))&&(a.preventDefault(),e.href=d.href)},!1)}})(document,window.navigator,"standalone")</script>
		  <style type="text/css">
			<?php echo (isset($data['site_css']) ? $data['site_css'] : ''); ?> 
		</style>
    </head>
	<body class="site-bg <?php echo $unique_class;?>">
	<div class="navbar navbar-default navbar-fixed-top header-bg <?php echo $bg_class;?>" >
	<div class="container">
	  <div class="row" >
	  <div class="navbar-header">
		<div class="logo-contnr">
			<div class="navbar-brand moblogo">
				<a data-role="none" data-ajax="false" href="<?php echo $siteurl; ?>">
					<?php if( !empty($site_logo)):?>
					<img alt="logo" onload="checkspeedTest()" class="logo-img" title="ReDesigns" src="<?php echo URL::site('assets/uploads/logo/'.$site_logo.'?random='.rand(1111111111,9999999999));?>" >
					<?php else:?>
					<img alt="logo" onload="checkspeedTest()" class="logo-img" title="ReDesign" src="<?php echo URL::site('assets/img/moblogo.png?random='.rand(1111111111,9999999999));?>">
					<?php endif;?>
				</a>
			</div>
		</div>
		<div class="btn-contnr">
			<?php if (!Auth::instance()->logged_in()){ ?>
				<button data-role="none" data-ajax="false" class="navbar-toggle activedatacol" data-toggle="collapse" data-target=".navbar-collapse"><strong>LOGIN</strong></button>
			<?php }else{ ?>
				<button data-role="none" data-ajax="false" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				  <span class="icon-bar"></span>
				  <span class="icon-bar"></span>
				  <span class="icon-bar"></span>
				</button>
				<small id="ajaxnotifyone" class="chat-alert label label-danger autoshownotification" style="float:right"><?php echo Session::Instance()->get('chatnotify');?></small>
			<?php } ?>
		</div>
	  </div>
	  <?php if (!Auth::instance()->logged_in()){ ?>
		  <div id="logincollapse" class="collapse navbar-collapse menu-bg <?php echo $bg_class;?> <?php if($error_msg!='') { echo 'in'; } ?>" style="border-radius:5px;box-shadow: 1px 1px 5px rgba(0,0,0,0.25);">
			<form data-role="none" data-ajax="false" action="<?php echo $siteurl; ?>" class="navbar-form" id="header-form" role="form" method="post">
					<p id="error_msg_login" class="error-msg <?php ($error_msg!='' ? 'hide' : '');?>"><?php echo ($error_msg!='' ? $error_msg : '');?></p>
				  <div class="lt-left">
					  <div class="form-group">
						<label for="user_email" class="<?php echo $font_class;?>"><?php echo __('Email'); ?></label><br>
						<input data-role="none" data-ajax="false" type="text" class="form-control input-sm" id="email" name="user_email" value="<?php echo trim($error_email);?>"> 
					  </div>
					  <div class="form-group password-contnr">
						<label for="password"  class="<?php echo $font_class;?>"><?php echo __('Password'); ?></label><br>
						<input data-role="none" data-ajax="false" type="password" class="form-control input-sm" id="pass" name="password" >
					  </div>
					  <div class="checkbox login-btm" >
						<label>
						  <input data-role="none" data-ajax="false" type="checkbox" name="remember" id="remember" value="1">   <label class="remember-label <?php echo $font_class;?>" for="remember" ><?php echo __('Remember me'); ?></label>
						</label>
						<label class="forgot-passwrd">
						  <a data-role="none" data-ajax="false" href="<?php echo $siteurl.'page/recover';?>" alt="Forgotten your password?"><label class="forgot-label <?php echo $font_class;?>" ><?php echo __('Forgotten your password'); ?>?</label></a>
						</label>
					  </div>
				  </div>
				  <div class="lt-right">
					<a data-role="none" class="btn btn-warning btn-sm" data-ajax="false" href="javascript:void(0);" onclick="$('.navbar-toggle').click();" alt="Join Now" data-toggle="modal" data-target="#joinModal"><span style="color:#fff;font-weight:bold"><?php echo __('Not registered yet? Join Now'); ?>.</span></a>
					<!--<button data-role="none" data-ajax="false" type="submit" name="login" class="btn btn-primary"><?php //echo __('Login'); ?></button> -->
					<button data-role="none" data-ajax="false" type="submit" name="login" style="float:right;" class="btn btn-primary btn-sm"><?php echo __('Login'); ?></button>
				  </div>
				</form>
		  </div>
	 <?php }else { ?>
		  <div class="collapse navbar-collapse menu-bg <?php echo $bg_class;?>">
			<div class="lt-left">
				<ul class="nav navbar-nav navbar-right">
					<li><a data-ajax="false" href="<?php echo URL::base(TRUE).$siteSlug.'index'; ?>"><span class="fa fa-home"></span><?php echo __('Home/Dashboard'); ?></a></li>
					<li><a data-ajax="false" href="javascript:void(0);" onclick="showUserModel()"><span class="fa fa-user"></span><?php echo __('Me'); ?></a></li>
					<li><a data-ajax='false' href="<?php echo URL::base(TRUE).$siteSlug.'networks/connections'; ?>"><span class="fa fa-users"></span><?php echo __('Messages'); ?>   <small id="ajaxnotifytwo" class="chat-alert label label-danger autoshownotification" style="float:right"><?php echo Session::Instance()->get('chatnotify');?></small></a></li>
					<li class="hide"><a data-ajax='false' href="javascript:void(0);"><span class="fa fa-bell"></span><?php echo __('Notifications'); ?></a></li>
					<li><a data-ajax="false" href="javascript:void(0);" onclick="contactUsModal()"><span class="fa fa-comment-o"></span><?php echo __('Contact Us'); ?></a></li>
					<li class="tour-step tour-step-nine"><a data-ajax="false"  href="javascript:void(0);" onclick="openTopPopup('help');"><span class="fa fa-question"></span><?php echo __('Help &amp; FAQ&rsquo;s'); ?></a></li>
					<li><a data-ajax="false"  href="<?php echo URL::base(TRUE).$siteSlug.'settings/preference'; ?>"><span class="fa fa-cogs"></span><?php echo __('Preference Settings'); ?></a></li>
					<li><a data-ajax='false' href="<?php echo URL::base(TRUE).$siteSlug.'users/trainer'; ?>"><span class="fa fa-star-o"></span><?php echo __('Personal Trainers'); ?></a></li>
					<li><a data-ajax="false"  href="<?php echo URL::base(TRUE).$siteSlug.'index/logout'; ?>"><span class="fa fa-sign-out"></span><?php echo __('Logout'); ?></a></li>
				</ul>
			</div>
		  </div>
	  <?php } ?>
	</div>
	 </div>
	</div>