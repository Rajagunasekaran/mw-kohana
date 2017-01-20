<?php defined('SYSPATH') OR die('No direct access allowed.'); 
$current_url = Helper_Common::currentAdminUrl();
$session = Session::Instance();
$allowTour = $allowNotify = true;
$user_allow_tour = $session->get('user_allow_tour');
$user_allow_edit_notify = $session->get('user_allow_edit_notify');
if($user_allow_tour=='disallow')
	$allowTour = false;
if($user_allow_edit_notify == 'disallow')
	$allowNotify = false;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="<?php echo "\n"; echo $meta_description ?>" />
<meta name="KEYWORDS" content="<?php echo "\n"; echo $meta_keywords ?>" />
<link rel="shortcut icon" href="<?php echo URL::site().'assets/img/mw_favicon.png'; ?>" />
<title><?php echo "\n"; echo $title; ?></title>
<script type='text/javascript'>
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
	var $currentElement;
	siteUrl='<?php  echo URL::base_lang().'admin/'; ?>';
	var user_from = '<?php  echo 'admin'; ?>';
	siteUrl_frontend =  '<?php echo URL::base_lang(TRUE); ?>';
	var siteName='<?php echo (!empty($session->get('current_site_name')) ? $session->get('current_site_name') : 'site'); ?>';
	var siteAgeLimit='<?php echo (!empty($session->get('current_site_agelimit')) ? $session->get('current_site_agelimit') : '18'); ?>';
	//alert(siteUrl);
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
	var isMobile = false; //initiate as false
	// device detection
	if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent) 
		|| /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0,4))){ isMobile = true;
	}
</script>
<?php
echo "\n"; echo HTML::style("assets/css/bootstrap.css"); 
echo "\n"; echo HTML::style("assets/css/font-awesome.min.css");
echo "\n"; echo HTML::style("assets/css/bootstrap.min.css"); 
echo "\n"; echo HTML::style("assets/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css"); 
echo "\n"; echo HTML::style("assets/css/jquery-ui.css"); 
//echo "\n"; echo HTML::style("assets/css/font-awesome.css"); 
echo "\n"; echo HTML::style("assets/plugins/morris/morris.css"); 
//echo "\n"; echo HTML::style("assets/css/jquery.mobile-1.3.0.min.css");
echo "\n"; echo HTML::style("assets/css/sb-admin.css"); 
echo "\n"; echo HTML::style("assets/css/jquery.dataTables.min.css"); 
echo "\n"; echo HTML::style("assets/plugins/chosen/chosen.css");
echo "\n"; echo HTML::style("assets/css/ionicons.min.css"); 
echo "\n"; echo HTML::style("assets/plugins/select2-bootstrap-theme-master/select2.css"); 
echo "\n"; echo HTML::style("assets/plugins/select2-bootstrap-theme-master/select2-bootstrap.css"); 
echo "\n"; echo HTML::style("assets/css/bootstrap-switch.css");
if($current_url=='admin/workout/browse' || $current_url=='admin/workout/sample' || $current_url=='admin/exercise/browse'|| $current_url=='admin/workout/edit' || $current_url=='admin/exercise/sample' || $current_url=='admin/workout/sampleedit'
	|| $current_url=='admin/workout/shared' || $current_url=='admin/workout/sharededit'
	) {
	echo "\n"; echo HTML::style("assets/css/bootstrap-tagsinput.css");
	echo "\n"; echo HTML::style('assets/css/pages/admin/workout_plan.css');
	echo "\n"; echo HTML::style('assets/css/pages/admin/exercise_set.css');
}
if($current_url=='admin/exercise/browse' || $current_url=='admin/exercise/sample') {
	echo "\n"; echo HTML::style('assets/css/pages/admin/exercise.css');
	echo "\n"; echo HTML::style("assets/css/bootstrap-tagsinput.css");
}	
if($current_url=='admin/exercise/create' || $current_url=='admin/subscriber/browse' || $current_url=='admin/dashboard' || $current_url=='admin/dashboard/index' || $current_url=='admin/manager/browse' || $current_url=='admin/trainer/browse' || $current_url=='admin/dashboard/sharerecords' ) {
	echo "\n"; echo HTML::style("assets/plugins/multi-select/multiple-select.css");
	echo "\n"; echo HTML::style('assets/css/pages/admin/workout_plan.css');
	echo "\n"; echo HTML::style('assets/css/pages/admin/exercise_set.css');
	echo "\n"; echo HTML::style("assets/css/bootstrap-tagsinput.css");
	echo "\n"; echo HTML::style("assets/plugins/cropper/dist/cropper.min.css");
	echo "\n"; echo HTML::style("assets/plugins/cropper/demo/css/main.css"); 	
}
if($current_url=='admin/workout/edit' || $current_url=='admin/workout/sampleedit' || $current_url=='admin/workout/browse' || $current_url=='admin/workout/sample'
	|| $current_url=='admin/workout/shared' || $current_url=='admin/workout/sharededit'
	){
	echo "\n"; echo HTML::style("assets/css/keyboard.css"); 
	echo "\n"; echo HTML::style("assets/plugins/cropper/dist/cropper.min.css");
	echo "\n"; echo HTML::style("assets/plugins/cropper/demo/css/main.css"); 	
}
if(isset($css)): 
	foreach ($css as $cssfile): 
		echo "\n"; echo html::style($cssfile); 
	endforeach; 
endif; 
//echo "\n"; echo HTML::script('assets/js/jquery.js');
if(isset($head)): 
	foreach ($head as $head_content):
		echo "\n"; echo $head_content;
	endforeach;
endif;
if(isset($js_top)): 
	foreach ($js_top as $jsfile): 
		echo "\n"; echo html::script($jsfile); 
	endforeach;
endif;
echo "\n"; echo HTML::script('assets/js/jquery.js'); 
echo "\n"; echo HTML::script('assets/js/jquery-ui.js');
if($current_url=='admin/workout/browse' || $current_url=='admin/workout/sample' || $current_url=='admin/workout/edit' || $current_url=='admin/workout/sampleedit'
	|| $current_url=='admin/workout/shared' || $current_url=='admin/workout/sharededit'
	) {
	echo "\n"; echo HTML::script("assets/js/jquery-ui.multidatespicker.js");
	echo "\n"; echo HTML::script("assets/js/formValidation.min.js"); 
	echo "\n"; echo HTML::script("assets/js/bootstrap-validate.min.js");
	echo "\n"; echo HTML::script("assets/js/SimpleAjaxUploader.js");
	echo "\n"; echo HTML::script("assets/plugins/cropper/dist/cropper.min.js"); 
	echo "\n"; echo HTML::script("assets/plugins/cropper/demo/js/imglib-main.js");
}
?>
</head>
<!--  START CONTENT WRAPPER -->
<?php
if(isset($content)){
	echo "\n"; echo $content; 
}



echo "\n"; echo HTML::script('assets/plugins/bootstrap-datetimepicker/js/moment-with-locales.js'); 
echo "\n"; echo HTML::script('assets/js/bootstrap.min.js'); 
//echo "\n"; echo HTML::script('assets/js/jquery.mobile-1.4.5.min.js');
echo "\n"; echo HTML::script('assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js');
echo "\n"; echo HTML::script('assets/plugins/chosen/chosen.jquery.js'); 
echo "\n"; echo HTML::script('assets/plugins/select2-bootstrap-theme-master/select2.js');
echo "\n"; echo HTML::script('assets/js/jquery.dataTables.min.js');
echo "\n"; echo HTML::script('assets/js/bootstrap-switch.js');
echo "\n"; echo HTML::script('assets/plugins/morris/morris.min.js'); 
echo "\n"; echo HTML::script('assets/plugins/morris/raphael.min.js');
if(isset($js_bottom)): 
	foreach ($js_bottom as $jsfile): 
		echo "\n"; echo html::script($jsfile);
	endforeach;
endif;
if($current_url=='admin/exercise/create' || $current_url=='admin/subscriber/browse') {
	//echo "\n";echo HTML::script("assets/js/jquery-sortable-lists.min.js");
	echo "\n"; echo HTML::script("assets/plugins/cropper/dist/cropper.min.js"); 
	echo "\n"; echo HTML::script("assets/plugins/cropper/demo/js/imglib-main.js");
	echo "\n"; echo HTML::script("assets/js/typeahead.bundle.min.js"); 
	echo "\n"; echo HTML::script("assets/js/bootstrap-tagsinput.min.js");
	echo "\n"; echo HTML::script("assets/js/jquery.bootstrap.wizard.min.js");
	echo "\n"; echo HTML::script("assets/js/jquery.ui.touch-punch.min.js");	 
	echo "\n"; echo HTML::script("assets/js/formValidation.min.js"); 
	echo "\n"; echo HTML::script("assets/js/bootstrap-validate.min.js"); 
	echo "\n"; echo HTML::script("assets/js/SimpleAjaxUploader.js");
	if($current_url =='admin/exercise/create'){
		echo "\n"; echo HTML::script('assets/js/pages/admin/create_exercise.js');
	}
}elseif($current_url=='admin/dashboard' || $current_url=='admin/dashboard/index' ||  $current_url=='admin/dashboard/sharerecords') { 
	echo "\n"; echo HTML::script("assets/plugins/multi-select/multiple-select.js");
	echo "\n"; echo HTML::script("assets/plugins/cropper/dist/cropper.min.js"); 
	echo "\n"; echo HTML::script("assets/plugins/cropper/demo/js/imglib-main.js");
	echo "\n"; echo HTML::script("assets/js/typeahead.bundle.min.js"); 
	echo "\n"; echo HTML::script("assets/js/bootstrap-tagsinput.min.js");
	echo "\n"; echo HTML::script("assets/js/jquery.bootstrap.wizard.min.js");
	echo "\n"; echo HTML::script("assets/js/jquery.ui.touch-punch.min.js");	 
	echo "\n"; echo HTML::script("assets/js/formValidation.min.js"); 
	echo "\n"; echo HTML::script("assets/js/bootstrap-validate.min.js"); 
	echo "\n"; echo HTML::script("assets/js/SimpleAjaxUploader.js");
	
	echo "\n"; echo HTML::script('assets/js/pages/admin/dashboard.js'); 
	echo "\n"; echo HTML::script('assets/js/pages/admin/subscribers.js'); 
	echo "\n"; echo HTML::script('assets/plugins/morris/morris-data.js');
	echo "\n"; echo HTML::script('assets/js/pages/admin/workout_plan.js');
	echo "\n"; echo HTML::script('assets/js/pages/admin/workoutrecord.js');
}else if($current_url=='admin/workout/browse' || $current_url=='admin/workout/sample' || $current_url=='admin/workout/shared' ){
	echo "\n"; echo HTML::script('assets/js/typeahead.bundle.min.js');
	echo "\n"; echo HTML::script("assets/js/bootstrap-tagsinput.min.js");
	echo "\n"; echo HTML::script('assets/js/pages/admin/workout.js');
	echo "\n"; echo HTML::script('assets/js/pages/admin/workout_plan.js');
}else if($current_url=='admin/workout/edit' || $current_url=='admin/workout/sampleedit' || $current_url=='admin/workout/sharededit'){
	echo "\n"; echo HTML::script('assets/js/pages/admin/workoutrecord.js');
	echo "\n"; echo HTML::script('assets/js/typeahead.bundle.min.js');
	echo "\n"; echo HTML::script("assets/js/bootstrap-tagsinput.min.js");
	echo "\n"; echo HTML::script("assets/js/jquery.ui.touch-punch.min.js");
	
}else if($current_url=='admin/exercise/browse' || $current_url=='admin/exercise/sample') {
	echo "\n"; echo HTML::script('assets/js/typeahead.bundle.min.js');
	echo "\n"; echo HTML::script("assets/js/bootstrap-tagsinput.min.js");
	echo "\n"; echo HTML::script('assets/js/pages/admin/exercise.js');
	echo "\n"; echo HTML::script("assets/js/pages/admin/script_ad1.js");
}else if($current_url=='admin/image/exerciseimages') {
	echo "\n"; echo HTML::script('assets/js/typeahead.bundle.min.js');
	echo "\n"; echo HTML::script('assets/js/pages/admin/image.js');	
}else if($current_url=='admin/questions' || $current_url=='admin/questions/index') {
	//echo HTML::script("assets/js/jquery-sortable-lists.min.js");
	echo "\n"; echo HTML::script('assets/js/pages/admin/questions.js');
	echo "\n"; echo HTML::script("assets/js/jquery.ui.touch-punch.min.js");
	echo HTML::script("assets/js/jquery-ui.js");
}else if($current_url=='admin/commonquestions' || $current_url=='admin/commonquestions/index') {
	//echo HTML::script("assets/js/jquery-sortable-lists.min.js");
	echo "\n"; echo HTML::script('assets/js/pages/admin/commonquestions.js');
	echo "\n"; echo HTML::script("assets/js/jquery.ui.touch-punch.min.js");
	echo HTML::script("assets/js/jquery-ui.js");
}

else if($current_url=='admin/manager/browse' ){
		echo "\n"; echo HTML::script("assets/js/jquery.ui.touch-punch.min.js");
}
if($current_url=='admin/workout/edit' || $current_url=='admin/workout/sampleedit' || $current_url=='admin/workout/browse' || $current_url=='admin/workout/sample'){
	echo "\n"; echo HTML::script('assets/js/jquery.keyboard.js');
	echo "\n"; echo HTML::script('assets/js/jquery.keyboard.extension-mobile.js');
	echo "\n"; echo HTML::script('assets/js/underscore-min.js');
}		
echo "\n"; echo HTML::script('assets/js/pages/admin/mailbox.js');	
echo "\n"; echo HTML::script("assets/js/jquery.confirm.min.js");
echo "\n"; echo HTML::script('assets/js/jquery.flot.js');
echo "\n"; echo HTML::script('assets/js/jquery.flot.resize.js');
echo "\n"; echo HTML::script('assets/js/jquery.flot.pie.min.js');
echo "\n"; echo HTML::script('assets/plugins/morris/morris-profiledata.js');
echo "\n"; echo HTML::script('assets/plugins/tinymce/js/tinymce/tinymce.min.js');
echo "\n"; echo HTML::script('assets/js/script.js');
?>
<div id="commonmodal" class="modalarea"></div>
<div id="commonmodal1" class="modalarea"></div>

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
					<div class="row"><div id="validation-errors" class="col-xs-12"></div></div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" onclick="$('#errorMessage-modal').modal('hide');" data-ajax="false" data-role="none"><?php echo __('Close'); ?></button>
				</div>
			</div>
		</div>
	</div>
</div>
</body>
</html>