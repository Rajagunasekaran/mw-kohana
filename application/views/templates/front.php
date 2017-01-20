<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<?php if(Request::current()->controller() == 'Errors'){ ?>
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<?php echo header('Cache-Control: no-cache, no-store, must-revalidate'); 
			  echo header('Pragma: no-cache');
			  echo header('Expires: 0'); 
		?>
		<meta property="og:url" content="<?php echo URL::base(True);?>" />

		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<link rel="shortcut icon" href="<?php echo URL::site().'assets/img/mw_favicon.png'; ?>" />
		<link href="<?php echo URL::site().'assets/img/apple-touch-icon-mw152x152.png'; ?>" rel="apple-touch-icon" sizes="152x152" />
		<link href="<?php echo URL::site().'assets/img/apple-touch-icon-mw167x167.png'; ?>" rel="apple-touch-icon" sizes="167x167" />
		<link href="<?php echo URL::site().'assets/img/apple-touch-icon-mw180x180.png'; ?>" rel="apple-touch-icon" sizes="180x180" />
		<link href="<?php echo URL::site().'assets/img/icon-mw192x192.png'; ?>" rel="icon" sizes="192x192" />
		<link href="<?php echo URL::site().'assets/img/icon-mw128x128.png'; ?>" rel="icon" sizes="128x128" />
		<title><?php echo $title; ?></title>
		<?php 
			echo HTML::style("assets/css/bootstrap.min.css");
			echo HTML::style("assets/css/jquery-ui.css");
		?>
		</head>
		<body><?php if(isset($content)) echo $content; ?></body>
		</html>
<?php }else{ ?>
<?php 
	$session = Session::Instance();
	$disAllowTourSiteIds = array('16');
	$current_site_id = $session->get('current_site_id');
	$allowTour = $allowNotify = true;
	$user_allow_tour = $session->get('user_allow_tour');
	$user_allow_edit_notify = $session->get('user_allow_edit_notify');
	if(in_array($current_site_id,$disAllowTourSiteIds) || $user_allow_tour=='disallow')
		$allowTour = false;
	if($user_allow_edit_notify == 'disallow')
		$allowNotify = false;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php echo header('Cache-Control: no-cache, no-store, must-revalidate'); 
	  echo header('Pragma: no-cache');
	  echo header('Expires: 0'); 
?>

<?php if(isset($social_image) && !empty($social_image)){ ?>
	<meta property="og:image" content="<?php echo $social_image;?>" />
<?php }if(isset($social_desc) && !empty($social_desc)){ ?>
	<meta property="og:description" content="<?php echo $social_desc;?> " />
<?php }if(isset($social_video) && !empty($social_video)){ ?>
	<meta property="og:video" content="<?php echo $social_video;?>" />
<?php }if(isset($social_title)){ ?>
	<meta property="og:title" content="<?php echo (!empty($social_title) ? $social_title : $site_title);?>" />
<?php } ?>
<meta property="og:url" content="<?php echo URL::base(True);?>" />

<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport" />
<meta name="description" content="<?php echo $meta_description ?>" />
<meta name="KEYWORDS" content="<?php echo $meta_keywords ?>" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="mobile-web-app-capable" content="yes">
<link rel="shortcut icon" href="<?php echo URL::site().'assets/img/mw_favicon.png'; ?>" />
<link href="<?php echo URL::site().'assets/img/apple-touch-icon-mw152x152.png'; ?>" rel="apple-touch-icon" sizes="152x152" />
<link href="<?php echo URL::site().'assets/img/apple-touch-icon-mw167x167.png'; ?>" rel="apple-touch-icon" sizes="167x167" />
<link href="<?php echo URL::site().'assets/img/apple-touch-icon-mw180x180.png'; ?>" rel="apple-touch-icon" sizes="180x180" />
<link href="<?php echo URL::site().'assets/img/icon-mw192x192.png'; ?>" rel="icon" sizes="192x192" />
<link href="<?php echo URL::site().'assets/img/icon-mw128x128.png'; ?>" rel="icon" sizes="128x128" />
<title><?php echo $title; ?></title>
<?php 
	echo HTML::style("assets/css/bootstrap.min.css");
	echo HTML::style("assets/css/styles.css");
	echo HTML::style("assets/css/font-awesome.min.css");
	echo HTML::style("assets/css/jquery-ui.css");
	echo HTML::style("assets/css/font.css");
	echo HTML::style("assets/css/bootstrap-switch.css");
	
	if(Request::current()->action() == 'connections')
		echo HTML::style("assets/css/chat-style.css");	
	if(Request::current()->action() == 'tag_template')
		echo HTML::style("assets/css/bootstrap-tagsinput.css");
	if(Request::current()->action() == 'preference')
		echo HTML::style("assets/plugins/tinytoggle/css/tiny-toggle.css");
	// if( Request::current()->action() == 'myworkout'){
	// 	echo HTML::style("assets/plugins/select2-bootstrap-theme-master/select2.css");
	// 	echo HTML::style("assets/plugins/select2-bootstrap-theme-master/select2-bootstrap.css");
	// }
	if($allowTour){
		echo HTML::style("assets/css/bootstrap-tour.css");
	}
?>
<?php if(isset($css)): 
	foreach ($css as $cssfile): 
		echo html::style($cssfile); 
	endforeach; 
endif; ?>
<?php 
	if(Request::current()->controller() != 'Index'){
		echo HTML::style("assets/css/jquery.mobile-1.3.0.min.css");
		echo HTML::style("assets/css/bootstrap-tagsinput.css");
		echo HTML::style("assets/plugins/cropper/dist/cropper.min.css");
		echo HTML::style("assets/plugins/cropper/demo/css/main.css");
		echo HTML::style("assets/css/keyboard.css");
		echo HTML::style("assets/plugins/select2-bootstrap-theme-master/select2.css");
		echo HTML::style("assets/plugins/select2-bootstrap-theme-master/select2-bootstrap.css");
		echo HTML::style("assets/plugins/morris/morris.css");
	}
	if(Helper_Common::currentUrl()=="networks/connections"){
		echo "\n"; echo HTML::style("assets/plugins/multi-select/multiple-select.css");
	}
	if(Request::current()->action() == 'myactioncalendar' || Request::current()->action() == 'myactionplans'){
		echo HTML::style("assets/css/calendar.css");
	} 
	if(isset($head)): ?>
	<?php foreach ($head as $head_content): ?>
		<?php	echo $head_content; ?>
	<?php endforeach; ?>
<?php endif; ?>
<?php 
	$user = Auth::instance()->get_user();
	echo HTML::script('assets/js/moment.js');
	echo HTML::script('assets/js/moment-timezone.js');
	echo HTML::script('assets/js/jquery.js');
	echo HTML::script('assets/js/bootstrap.min.js');
	echo HTML::script('assets/js/jquery-ui.js');
	echo HTML::script('assets/js/bootstrap-switch.js');
	echo HTML::script("assets/js/underscore-min.js");
?>

<?php 
	// if( Request::current()->action() == 'myworkout')
	// 	echo HTML::script('assets/plugins/select2-bootstrap-theme-master/select2.js');
	if(Request::current()->action() == 'myactioncalendar' || Request::current()->action() == 'myactionplans')
		echo HTML::script("assets/js/calendar.js");
	if(Request::current()->action() == 'myworkout' || Request::current()->action() == 'workoutrecord' || Request::current()->action() == 'assignedplan' || Request::current()->action() == 'workoutlog' || Request::current()->action() == 'myactionplans' || (Request::current()->controller() == 'Dashboard' && Request::current()->action() == 'index') || (Request::current()->controller() == 'Exercise' && Request::current()->action() == 'index')){
		echo HTML::style("assets/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css");
	}
	
	if(Request::current()->controller() != 'Index'){ ?>
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
		/*moment.tz.setDefault("Pacific/Tahiti");
		console.log(moment( new Date()).format('MMMM Do YYYY, h:mm:ss a'));*/
		$(document).on('mobileinit', function () {
			$.mobile.ignoreContentEnabled = true;
		});
		var isMobile = false; //initiate as false
		// device detection
		if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent) 
			|| /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0,4))){ isMobile = true;
		}
	</script>
<?php

		echo HTML::script("assets/js/modernizr-custom.js");
		echo HTML::script("assets/js/xdate.js");
		echo HTML::script("assets/js/xdate.i18n.js");
		if(Helper_Common::currentUrl()!="networks/connections"){
				echo HTML::script("assets/js/jquery.mobile-1.4.5.min.js");
				echo HTML::script("assets/js/mobipick.js");
		}
		echo HTML::script("assets/js/typeahead.bundle.min.js");
		echo HTML::script("assets/js/bootstrap-tagsinput.min.js");
		echo HTML::script("assets/js/SimpleAjaxUploader.js");
		echo HTML::script("assets/js/formValidation.min.js");
		echo HTML::script("assets/js/bootstrap-validate.min.js");
		echo HTML::script("assets/plugins/cropper/dist/cropper.min.js");
		echo HTML::script("assets/plugins/cropper/demo/js/imglib-main.js");
		echo HTML::script("assets/js/jquery.keyboard.js");
		echo HTML::script("assets/js/jquery.keyboard.extension-mobile.js");
		echo HTML::script('assets/plugins/select2-bootstrap-theme-master/select2.js');
		echo HTML::script('assets/plugins/morris/morris.min.js');
		echo HTML::script('assets/plugins/morris/raphael.min.js');
		echo HTML::script('assets/plugins/morris/morris-profiledata.js');
	} 
	echo HTML::style("assets/css/addtohomescreen.css");
	echo HTML::script("assets/js/addtohomescreen.js");
?>
<script type="text/javascript">
	var backHrefUrl = '';
	<?php if($session->get('user_allow_addtohome') == 'allow' && false){ ?>
	var ath = addToHomescreen({
		debug: 'android',           // activate debug mode in ios emulation
		skipFirstVisit: false,	// show at first access
		startDelay: 0,          // display the message right away
		lifespan: 0,            // do not automatically kill the call out
		displayPace: 0,         // do not obey the display pace
		privateModeOverride: true,	// show the message in private mode
		maxDisplayCount: 0      // do not obey the max display count
	});
	<?php } ?>
	<?php if($allowTour){?>
		var allowTour = true;
	<?php }else{ ?>
		var allowTour = false;
	<?php } ?>
	<?php if($allowNotify){?>
		var allowNotify = true;
	<?php }else{ ?>
		var allowNotify = false;
	<?php } ?>
	var user_allow_page = "<?php echo $session->get('user_allow_page');?>";
	var siteUrl='<?php echo URL::site(); ?>';
	var siteUrl_Front='<?php echo URL::base(TRUE); ?>';
	var user_from = '<?php  echo 'front'; ?>';
	var imgupurl="<?php echo URL::site('exercise/upload_img'); ?>"; 
	var siteName='<?php echo ($session->get('current_site_name') !='' ? $session->get('current_site_name') : 'site'); ?>';
	var siteSlug='<?php echo ($session->get('current_site_slug') !='' ? $session->get('current_site_slug') : ''); ?>';
	var siteAgeLimit='<?php echo ($session->get('current_site_agelimit') !='' ? $session->get('current_site_agelimit') : '18'); ?>';
	var $currentElement;
	$(document).ready(function(){
		$('body').removeAttr('class');
		if ( $('#wrap-index').parent().is( "div" ) ) {
			$('#wrap-index').parent().removeAttr('class');
			$('#wrap-index').parent().removeAttr('style');
		}
		// if( navigator.userAgent.match(/iPhone|iPad|iPod/i) ) {
			// $(document).on('show.bs.modal','.modal', function() {
			// 	$(this).css({
			// 		bottom: 'auto',
			// 		height: '100%'
			// 	});
			// });
		// }
		<?php if($session->get_once('popup-page')=='contact') { ?>
			contactUsModal();
		<?php }?>
	});
	// if ("standalone" in window.navigator && window.navigator.standalone){ //checks if you're in app mode
 //  		window.location = '<?php echo URL::base(TRUE); ?>';  //the URL you want to refer to.
	// }
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
<?php 
	if(Request::current()->action() == 'tag_template'){ 
		echo HTML::script("assets/js/typeahead.bundle.min.js");
		echo HTML::script("assets/js/bootstrap-tagsinput.min.js");
	}if(Request::current()->action() == 'preference'){
		echo HTML::script("assets/plugins/tinytoggle/js/tiny-toggle.js");
	}if(Request::current()->action() == 'trainer'){
		echo HTML::script("assets/plugins/jquery.searchable-1.0.0.min.js");
		echo HTML::script("assets/plugins/jQuery.dotdotdot-master/src/jquery.dotdotdot.js");
	}
	if(Request::current()->action() == 'workoutrecord' || Request::current()->action() == 'myworkout' || Request::current()->action() == 'assignedplan'  || Request::current()->action() == 'workoutlog' || Request::current()->action() == 'myactioncalendar' || (Request::current()->controller() == 'Dashboard' && Request::current()->action() == 'index')  || (Request::current()->controller() == 'Exercise' && Request::current()->action() == 'index') || (Request::current()->action() == 'exerciselibrary' || Request::current()->action() == 'exerciserecord') || Request::current()->action() == 'myactionplans'){
		echo HTML::script("assets/js/jquery-ui.multidatespicker.js");
		echo HTML::script("assets/plugins/bootstrap-datetimepicker/js/moment-with-locales.js");
		echo HTML::script("assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js");
		echo HTML::script("assets/js/jquery.ui.touch-punch.min.js");
		echo HTML::script("assets/js/jquery.confirm.min.js");
	} if(Request::current()->action() == 'exerciseimages'){
		echo HTML::script("assets/js/jquery.confirm.min.js");
	}
	if(Helper_Common::currentUrl()=="networks/connections"){
		echo "\n"; echo HTML::script("assets/plugins/multi-select/multiple-select.js");
	}
	?>
</head>
<body>
	<?php if(isset($user) && $user->pk() && Request::current()->action() == 'exerciseimages'){
		require_once(APPPATH.'views/templates/front/template-imglibrary.php');
		require_once(APPPATH.'views/templates/front/imglib-imgeditor.php');
	} ?>
	<!--  START CONTENT WRAPPER -->
	<?php if(isset($content)) echo $content; ?>
	<!--  END PRIMARY CONTENT -->
	<?php // if(!isset($user) || (isset($user) && empty($user))){ ?>
	<!-- <div id="footer">
		<div class="container">
			<p class="text-muted credit"><a data-ajax="false" data-role="none" href="<?php //echo URL::base(TRUE).'index' ?>"><?php// echo $site_title;?></a><span style="float:right"><?php //echo $site_title;?> © <?php //echo date('Y');?></span></p>
		</div>
	</div> -->
	<?php //} ?>

	<?php if(isset($user) && $user->pk() && Request::current()->action() != 'exerciseimages'){
		if(Request::current()->action() != 'exerciserecord' && Request::current()->action() != 'exerciselibrary'){
			require_once(APPPATH.'views/templates/front/exercisecreate.php');
		}
		require_once(APPPATH.'views/templates/front/template-imglibrary.php');
		require_once(APPPATH.'views/templates/front/imglib-imgeditor.php');
	} ?>
	<?php if(isset($user) && $user->pk()){ ?>
	<div id="userModal" class="modal fade" role="dialog" tabindex="-1"></div>
	<div id="myprofileoptionimagemodal" class="modal fade bs-example-modal-sm" role="dialog" tabindex="-1"></div>
	<div id="myprofiledatepicker" class="modal fade bs-example-modal-sm" role="dialog" tabindex="-1"></div>
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
	<?php } ?>
	<!-- Modal -->
<div class="modal fade" id="myModalNew" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">jQuery backDetect Plugin Example</h4>
      </div>
      <div class="modal-body">
        <h3>Look forward to the future, not the past!</h3>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut lobortis vitae elit ac tempor. Vivamus vel lorem sit amet dui pulvinar posuere sit amet sit amet urna. Aliquam et auctor enim. Curabitur et magna viverra massa tempus laoreet nec ac urna. Aliquam porta tincidunt finibus. Pellentesque purus turpis, porta eu ex id, varius fermentum odio. Suspendisse potenti. Sed non luctus magna, et suscipit sem. </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-danger" data-dismiss="modal">Ok</button>
      </div>
    </div>
  </div>
</div>
</body>
<?php  
	if($allowTour){
		echo HTML::script('assets/js/bootstrap-tour.js'); 
	}
?>
<?php if(isset($js)): 
	foreach ($js as $jsfile): 
		echo html::script($jsfile); 
	endforeach;?>
<?php endif; ?>
<?php 
    if($_SERVER['REMOTE_ADDR'] == '192.168.0.254' && Request::current()->action() == 'workoutrecord'){
		echo HTML::script("assets/js/pages/front/".strtolower(Request::current()->action())."1.js"); 
	}else{
		if(file_exists("assets/js/pages/front/".strtolower(Request::current()->action()).".js")) 
			echo HTML::script("assets/js/pages/front/".strtolower(Request::current()->action()).".js"); 
		else if(file_exists("assets/js/pages/front/".strtolower(Request::current()->controller()).".js"))
			echo HTML::script("assets/js/pages/front/".strtolower(Request::current()->controller()).".js"); 
	}
	/*if(file_exists("assets/js/pages/front/".strtolower(Request::current()->action()).".js")) 
		echo HTML::script("assets/js/pages/front/".strtolower(Request::current()->action()).".js"); 
	else if(file_exists("assets/js/pages/front/".strtolower(Request::current()->controller()).".js"))
		echo HTML::script("assets/js/pages/front/".strtolower(Request::current()->controller()).".js");*/
?>
<?php 
	echo HTML::script("assets/js/script_ft.js");
?>
<?php if(isset($user) && $user->pk() && Request::current()->action() != 'connections'){ ?>
<script>
$(document).ready(function(){
	if($('small.autoshownotification').length>0){ 
		setInterval(function(){get_notify();},5000);
	}
});
</script>
<?php } ?>
<script>(function(a,b,c){if(c in b&&b[c]){var d,e=a.location,f=/^(a|html)$/i;a.addEventListener("click",function(a){d=a.target;while(!f.test(d.nodeName))d=d.parentNode;"href"in d&&(d.href.indexOf("http")||~d.href.indexOf(e.host))&&(a.preventDefault(),e.href=d.href)},!1)}})(document,window.navigator,"standalone")</script>
</html>
<?php } ?>