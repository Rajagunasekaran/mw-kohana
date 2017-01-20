function showmoreXrdetail(){
	$('#showmorexr').addClass('hide');
	$('#showmorexrdetails').removeClass('hide');
	$('#hidemorexr').removeClass('hide');
	setDynamicHeight();
}
function hidemoreXrdetail(){
	$('#showmorexr').removeClass('hide');
	$('#showmorexrdetails').addClass('hide');
	$('#hidemorexr').addClass('hide');
	setDynamicHeight();
}
function increment(myInputid) {
  myInput = $('#'+myInputid).val();
  myInput = (+myInput + 1) || 0;
  if($('#decIcon').hasClass('datacol') && myInput > 0)
		$('#decIcon').removeClass('datacol');
  $('#'+myInputid).val(myInput);
}
function decrement(myInputid) {
  myInput = $('#'+myInputid).val();
  if(myInput > 0){
	if($('#decIcon').hasClass('datacol'))
		$('#decIcon').removeClass('datacol');
	myInput = (myInput - 1) || 0;
  }else{
	myInput = 0;
	$('#decIcon').addClass('datacol');
  }
  $('#'+myInputid).val(myInput);
}
function profileChange(method){
	$('#userModalActions').html();
	$.ajax({
		url : siteUrl+"search/getmodelTemplate",
		data : {
			action : 'profileactions',
			method :  method,
			modelType : 'userModalActions'
		},
		success : function(content){
			$('#userModalActions').html(content);
			$('#userModalActions').modal();
		}
	});
}
function getTagOfRecord(xrId){
	$('#myOptionsModalExerciseRecord').html();
	$.ajax({
		url : siteUrl+"search/getmodelTemplate",
		data : {
			action : 'relatedRecords',
			method :  'tagRecord',
			id    : xrId,
			modelType : 'myOptionsModalExerciseRecord',
			editFlag: true
		},
		success : function(content){
			$('#myOptionsModalExerciseRecord').html(content);
			$('#myOptionsModalExerciseRecord').modal();
		}
	});
}
function getRelatedRecords(xrid, EditFlag, oldxrId, order){
	modalName = 'myOptionsModalExerciseRecord';
	if($('div#exerciselib-model').length)
		EditFlag = true;
	$('#'+modalName).html();
	$.ajax({
		url : siteUrl+"search/getmodelTemplate",
		data : {
			action : 'relatedRecords',
			method : 'relatedRecords',
			xrid : xrid,
			id : oldxrId,
			modelType : modalName,
			editFlag : EditFlag,
			goalOrder : order
		},
		success : function(content){
			$('#'+modalName).html(content);
			$('#'+modalName).modal();
			$('div.modal-body').scrollTop('0');
		}
	});
}
function getRelatedRecordsMore(xrid, oldxrid, order, start, lim, event){
	if(xrid == '' || xrid == undefined || oldxrid == '' || oldxrid == undefined){
		return false;
	}
	var EditFlag = $("#relatedexc").attr('data-id');
	$.ajax({
		url : siteUrl+"search/getRelatedXrRecordsMore",
		data : {
			id	   : xrid,
			start  : start,
			lim    : lim
		},beforeSend: function () {
			setTimeout(function(){ $('#loading-indicator').show()}, 10);
     	},
		success : function(content){
			var JSONArray = $.parseJSON( content );
			var response = '';
			if(JSONArray.length>0) {
			 start = parseInt(start) + parseInt(lim);
			 for(var i=0; i<JSONArray.length; i++){
				response += '<div data-type="'+JSONArray[i].xrtype+'" class="row itemxr" '+(i=='9' ? 'id="view_more" data-order="'+order+'" data-start="'+start+'" data-limit="'+lim+'" data-xrid="'+xrid+'" data-oldxrid="'+oldxrid+'"' : '')+'><div class="mobpadding"><div class="border full">';
				response += '<div class="col-xs-3 ">';
				if(JSONArray[i].img){				
					response += '<img width="60px;" id="exerciselibimg" class="img-thumbnail" style="cursor:pointer;';
					response += '" src=\''+JSONArray[i].img+'\'';
					response += '/>';
				}else{
					response += '<i style="font-size:50px;" class="fa fa-file-image-o datacol"></i>';
				}
				response += '</div>';
				response += '<div class="col-xs-7" style="border-right:1px solid #eee;padding-left:0px;"><b>'+JSONArray[i].title+'</b><div class="item-info">'+JSONArray[i].xrtype+'</div></div>';
				response += '<div class="col-xs-2 aligncenter"><a href="javascript:void(0);" '+(EditFlag ? 'onclick="insertFromRelatedToXrSet('+"'"+oldxrid+"','"+JSONArray[i].xr_id+"','"+order+"'"+');"' : 'onclick="return false"')+'><i class="fa fa-sign-in iconsize '+(EditFlag ? '' : 'datacol')+'"></i></a></div>';
				response += '</div>';
				response += '</div><input type="hidden" value="'+(JSONArray[i].img != '' ? JSONArray[i].img : '')+'" name="popup_hidden_exerciseset_img'+JSONArray[i].xr_id+'" id="popup_hidden_exerciseset_image_opt'+JSONArray[i].xr_id+'"/><input type="hidden" value="'+JSONArray[i].title+'" name="popup_hidden_exerciseset_title'+JSONArray[i].xr_id+'" id="popup_hidden_exerciseset_title_opt'+JSONArray[i].xr_id+'"/></div>';
			 }
			 $("#view_more").removeAttr('data-start');
			 $("#view_more").removeAttr('data-limit');
			 $("#view_more").removeAttr('data-xrid');
			 $("#view_more").removeAttr('data-oldxrid');
			 $("#view_more").removeAttr('data-order');
			 $("#view_more").attr('id','view_more_prev');
			 $("#relatedexc").append(response);
			 sendAjax = true;
		  }else{
			 $("#view_more").remove();
		  }	
		  $('#loading-indicator').hide();
		}
	});
	$('#loading-indicator').hide();
}
function rateToXRRecords(xrId){
	$('#myOptionsModalExerciseRecord').html();
	$.ajax({
		url : siteUrl+"search/getmodelTemplate",
		data : {
			action : 'relatedRecords',
			method :  'tagRecord',
			id    : xrId,
			modelType : 'myOptionsModalExerciseRecord'
		},
		success : function(content){
			$('#myOptionsModalExerciseRecord').html(content);
			$('#myOptionsModalExerciseRecord').modal();
		}
	});
}
function openSequencePopup(xrId){
	$('#myOptionsModalExerciseRecord').html();
	$.ajax({
		url : siteUrl+"search/getmodelTemplate",
		data : {
			action : 'relatedRecords',
			method :  'sequenceRecords',
			id    : xrId,
			modelType : 'myOptionsModalExerciseRecord'
		},
		success : function(content){
			$('#myOptionsModalExerciseRecord').html(content);
			$('#myOptionsModalExerciseRecord').modal();
		}
	});
}
function openSequencePopupMore(xrId,start,lim){
	$.ajax({
		url : siteUrl+"search/getSequenceMore",
		data : {
			id : xrId,
			start : start,
			lim : lim
		},
		success : function(content){
			 var JSONArray = $.parseJSON( content );
			 var response = '';
			if(JSONArray.length>0) {
			 for(var i=0; i<JSONArray.length; i++){
			 var seqOrder = start+i;
				response += '<div class="row" style="margin-bottom:10px;"><div class="mobpadding">';
				response += '<label for="workout-name" class="control-label">Sequence '+seqOrder+'</label>';
				response += '<div class="border full"><div class="col-xs-3" style="border-right:1px solid #ddd;border-radius:0px">';
				if(JSONArray[i].img){
					response += '<img width="50px;" id="exerciselibimg" class="img-thumbnail" style="cursor:pointer;';
					response += '" src=\'../../../'+JSONArray[i].img+'\'';
					response += '/>';
				}else{
					response += '<i style="font-size:50px;" class="fa fa-file-image-o datacol"></i>';
				}
				response += '</div>';
				response += '<div class="col-xs-9">'+JSONArray[i].img_title+'</div>';
				response += '</div>';
				response += '</div></div>';
				
			 }
			 start = start+lim;
			 response += '<div id="view_more" class="row"><div class="mobpadding"><div class="border full">';
			 response += '<div class="col-xs-12"><center><a data-role="none" data-ajax="false" class="pointers" onclick="openSequencePopupMore('+xrId+','+start+','+lim+')"><i class="fa fa-chevron-down"></i> Show More Records</a></center></div></div></div></div>';	
			 $("#view_more").remove();
			 $("#relatedexc").append(response).css('overflow-y', 'scroll');
		  }else{
			 $("#view_more").remove();
		  }		  
		}
	});
}
function openVideoPopup(url){
	if(url !=''){
		$('#myOptionsModalExerciseRecord').html();
		$.ajax({
			url : siteUrl+"search/getmodelTemplate",
			data : {
				action : 'xrRecordactions',
				method :  YouTubeUrlNormalize(url), 
				modelType : 'myOptionsModalExerciseRecord'
			},
			success : function(content){
				$('#myOptionsModalExerciseRecord').html(content);
				$('#myOptionsModalExerciseRecord').modal();
			}
		});
	}
}
var getVidId = function(url)
{
	var vidId;
	if(url.indexOf("youtube.com/watch?v=") !== -1)//https://m.youtube.com/watch?v=e3S9KINoH2M
	{
		vidId = url.substr(url.indexOf("youtube.com/watch?v=") + 20);
	}
	else if(url.indexOf("youtube.com/watch/?v=") !== -1)//https://m.youtube.com/watch/?v=e3S9KINoH2M
	{
		vidId = url.substr(url.indexOf("youtube.com/watch/?v=") + 21);
	}
	else if(url.indexOf("youtu.be") !== -1)
	{
		vidId = url.substr(url.indexOf("youtu.be") + 9);
	}
	else if(url.indexOf("www.youtube.com/embed/") !== -1)
	{
		vidId = url.substr(url.indexOf("www.youtube.com/embed/") + 22);
	}
	else if(url.indexOf("?v=") !== -1)// http://m.youtube.com/?v=tbBTNCfe1Bc
	{
		vidId = url.substr(url.indexOf("?v=")+3, 11);
	}
	else
	{
		console.warn("YouTubeUrlNormalize getVidId not a youTube Video: "+url);
		vidId = null;
	}

	if(vidId.indexOf("&") !== -1)
	{
		vidId = vidId.substr(0, vidId.indexOf("&") );
	}
	return vidId;
};

var YouTubeUrlNormalize = function(url)
{
	var rtn = url;
	if(url)
	{
		var vidId = getVidId(url);
		if(vidId)
		{
			rtn = "https://www.youtube.com/embed/"+vidId;
		}
		else
		{
			rtn = url;
		}
	}

	return rtn;
};

YouTubeUrlNormalize.getThumbnail = function(url, num)
{
	var rtn, vidId = getVidId(url);
	if(vidId)
	{
		if(!isNaN(num) && num <= 4 && num >= 0)
		{
			rtn = "http://img.youtube.com/vi/"+vidId+"/"+num+".jpg";
		}
		else
		{
			rtn = "http://img.youtube.com/vi/"+getVidId(url)+"/default.jpg";
		}
	}
	else
	{
		return null;
	}
	return rtn;
};

YouTubeUrlNormalize.getFullImage = function(url)
{
	var vidId = getVidId(url);
	if(vidId)
	{
		return "http://img.youtube.com/vi/"+vidId+"/0.jpg";
	}
	else
	{
		return null;
	}
};

if ( typeof exports !== "undefined" ) {
	module.exports = YouTubeUrlNormalize;
}
else if ( typeof define === "function" ) {
	define( function () {
		return YouTubeUrlNormalize;
	} );
}
else {
	window.YouTubeUrlNormalize = YouTubeUrlNormalize;
}
/*fit the bootstrap modal height*/
function setModalMaxHeight(element) {
	if($(element).length > 1){
		$(element).each( function(index, element) {
			if($(element).attr('id') == 'mdl_popupimglibrary-modal' || $(element).attr('id') == 'exerciselib-model' || $(element).find('.modal-body').attr('id') == 'relatedexc'){
				return false;
			}
			this.$element     = $(element);  
			this.$content     = this.$element.find('.modal-content');
			var borderWidth   = this.$content.outerHeight() - this.$content.innerHeight();
			var dialogMargin  = $(window).width() < 768 ? 20 : 60;
			var contentHeight = $(window).height() - (dialogMargin + borderWidth);
			var headerHeight  = this.$element.find('.modal-header').outerHeight() || 0;
			var footerHeight  = this.$element.find('.modal-footer').outerHeight() || 0;
			var maxHeight     = contentHeight - (headerHeight + footerHeight);
			this.$element.find('.modal-body').css({
				'max-height': maxHeight,
				'overflow-y': 'auto',
				'overflow-x': 'hidden',
				'-webkit-overflow-scrolling': 'auto'
			});
		});
	}else{
		if($(element).attr('id') == 'mdl_popupimglibrary-modal' || $(element).attr('id') == 'exerciselib-model' || $(element).find('.modal-body').attr('id') == 'relatedexc'){
			return false;
		}
		this.$element     = $(element);  
		this.$content     = this.$element.find('.modal-content');
		var borderWidth   = this.$content.outerHeight() - this.$content.innerHeight();
		var dialogMargin  = $(window).width() < 768 ? 20 : 60;
		var contentHeight = $(window).height() - (dialogMargin + borderWidth);
		var headerHeight  = this.$element.find('.modal-header').outerHeight() || 0;
		var footerHeight  = this.$element.find('.modal-footer').outerHeight() || 0;
		var maxHeight     = contentHeight - (headerHeight + footerHeight);
		this.$element.find('.modal-body').css({
			'max-height': maxHeight,
			'overflow-y': 'auto',
			'overflow-x': 'hidden',
			'-webkit-overflow-scrolling': 'auto'
		});
	}
}
$(document).on('show.bs.modal','.modal', function() {
	$(this).show(); 
	setModalMaxHeight(this);
	function hasHtml5Validation () {
	  //Check if validation supported && not safari
	  return (typeof document.createElement('input').checkValidity === 'function') && 
		(!(navigator.userAgent.search("Safari") >= 0 || ("standalone" in window.navigator && window.navigator.standalone)) && navigator.userAgent.search("Chrome") < 0);
	}

	$('form').submit(function(){
		if(!hasHtml5Validation())
		{
			var isValid = true;
			var $inputs = $(this).find('[required]');
			$inputs.each(function(){
				var $input = $(this);
				$input.removeClass('invalid');
				if(!$.trim($input.val()).length)
				{
					isValid = false;
					$input.addClass('invalid');                 
				}
			});
			if(!isValid)
			{
				return false;
			}
		}
	});
});
$(window).resize(function() {
	if ($('.modal.in').length != 0) {
		setModalMaxHeight($('.modal.in'));
	}
});
$('.modal').on('shown.bs.modal', function() {
	setTimeout(function(){ 
		$('input:not(.modal input)').blur();
		$('textarea:not(.modal textarea)').blur();
	}, 10);
	$('div.modal-body').scrollTop('0');
});
// avoid the scrollbar on tag and timepicker popup
$(document).on('click keyup', '.bootstrap-tagsinput', function() {
	if ($('.modal-body.opt-body .bootstrap-tagsinput').is(':visible')) {
		$(this).closest('.modal-body.opt-body').removeAttr('style');
		if ($(this).closest('#relatedexc').length && $(this).closest('#relatedexc').is(':visible')) {
			$('#relatedexc').addClass('noscroll');
		}
	}
});
$(document).on('click focus', '.bootstrap-timepicker', function() {
	if ($('.modal-body .bootstrap-timepicker').is(':visible')) {
		$(this).closest('.modal-body').removeAttr('style');
	}
});
$('.modal').on('hidden.bs.modal', function () {
	$("body").css("padding-right","0");
});
//prevent scroll problem when model open
$(document).ready(function(){
	if(!$('div#loading-indicator').length)
		var loader = $('body').append('<div id="loading-indicator" style="display:none" class="modal-backdrop-new fade in"></div>');
	$(document).ajaxSend(function(event, request, settings) {
		if(settings.url.indexOf('?')>=0)
			settings.url = settings.url+ '&user_from=front&cp='+user_allow_page;
		else
			settings.url = settings.url+ '?user_from=front&cp='+user_allow_page;
		//console.log("DATA-------->"+settings.data)
		if(settings.data && settings.data.match(/loader=hide/g) || settings.url && settings.url.match(/loader=hide/g)) {
			return;
		}else{
			$('#loading-indicator').show();
		}
		
	});
	$(document).ajaxComplete(function(event, request, settings) {
		setDynamicHeight();
		$('#loading-indicator').hide();
	});
	if($('.modal')){
		$('.modal').on('hidden.bs.modal', function (e) {
			$('div.modal-body').scrollTop('0');
			if($('.modal').hasClass('in')) {
				$('body').addClass('modal-open');
			}
		});
		$('.modal').on('shown.bs.modal', function (e) {
			$('div.modal-body').scrollTop('0');
			if($('input.onlynumber').length > 0 && $('input.onlynumber').is(':visible')) {
				var customLayoutTemp = ["1 2 3 4", "5 6 7 8", "9 0 {dec} {b}", "{clear} {accept}"];
				if($('input#exercise_repetitions').length){
					var customLayoutTemp = ["1 2 3 4", "5 6 7 8", "9 0 {b}", "{clear} {accept}"];
				}
				$('input.onlynumber').keyboard({
					layout: "custom",
					openOn : 'focus',
					restrictInput : true, // Prevent keys not in the displayed keyboard from being typed in
					preventPaste : true,  // prevent ctrl-v and right click
					resetDefault: false,
					userClosed: false, // keyboard open until user closes with accept or cancel
					autoAccept: true, // required for userClosed: true
					customLayout: {
						"default": customLayoutTemp
					},
					usePreview: false,
					beforeVisible: $.proxy(self._onKeyboardBeforeVisible, self),
					create: $.proxy(self._onCreateKeyboard, self)
				}).addMobile();
				setTimeout(function(){ $('input.onlynumber').focus()}, 125);
			}
			if($('input.time-picker1').length)
				$('input.time-picker1').focus();
			else if($('input.time-picker2').length)
				$('input.time-picker2').focus();
		});
	}
});
function loadimage(element){
	$(element).css('width', 'auto');
}
function openTopPopup(method){
	modalName = 'topheaderpopup';
	$('#'+modalName).html();
	$.ajax({
		url : siteUrl+"search/getmodelTemplate",
		data : {
			action : 'staticpages',
			method :  method+'-cms',
			modelType : modalName
		},
		success : function(content){
			$('#'+modalName).html(content);
			$('#'+modalName).modal();
		}
	});
}
$(document).click(function(e) {
	if(!$('div').hasClass('tour-backdrop')){
		if (!$(e.target).parents().is('.navbar-collapse') || $(e.target).is('a')) {
			$('.navbar-collapse').collapse('hide');	    
		}
		if (!$(e.target).parents().is('.navbar-collapse-colors') || $(e.target).is('a')) {
			$('.navbar-collapse-colors').collapse('hide');	    
		}
	}
});
function showUserModel() {
		var showFeed = 1;
		$.get(siteUrl + "search/getmodelTemplate", {"action": "profileactions", "method": "profiledetails", "fromAdmin": 0}, function(response){
			if(response) {
				$('#userModal').html(response);
				$('.rgstr_flds').hide();
				$.ajax({
					url: siteUrl + "ajax/getFeedDetails",
					type: 'POST',
					dataType: 'json',
					data: {'id': $("#edit_userid").val(), 'is_front': 1},
					beforeSend: function () {
						setTimeout(function(){ $('#loading-indicator').show() }, 10);
					},
					success:function(data){
						if(data.success) {
							if($("#mdl_curr_imgid").length > 0 && $("#mdl_curr_imgid").val() != ''){
								$("#mdl_curr_imgid").val('');
							}
							if(typeof data.user_name != 'undefined') {
								$('.user_name').html(data.user_name);
							}
							if(typeof data.role_name != 'undefined') {
								$('.user_role').html(data.role_name);
							}
							if(typeof data.feed_details != 'undefined' && showFeed==1) {
								$('.feed_row .panel-default').append(data.feed_details);
								$('.feed_row').show();
							}
							if(typeof data.profiledetails != 'undefined') {
								$('div#tab1').append(data.profiledetails);
							}
							if(typeof data.profilereports != 'undefined') {
								$('div#tab3').append(data.profilereports);
							}
							if(typeof data.strengthreport != 'undefined') {
								$('div#strength-report').append(data.strengthreport);
							}
							//alert(data.user_dob+"============dob====");
							if(typeof data.user_dob != 'undefined') {
								$('.user_dob').html(data.user_dob);
								$('.user_dob_contnr').show();
							}
							if(typeof data.user_age != 'undefined') {
								$('.user_age').html(data.user_age);
								$('.user_age_contnr').show();
							}
							if(typeof data.user_phone != 'undefined') {
								$('.user_gender').html(data.user_gender);
								$('.user_gender_contnr').show();
							}
							if(typeof data.user_phone != 'undefined') {
								$('.user_phone').html(data.user_phone);
								$('.user_phone_contnr').show();
							}
							if(typeof data.user_tags != 'undefined') {
								$('.user_tags').html(data.user_tags);
								$('.user_tag_contnr').show();
							}
							if(typeof data.user_bmi != 'undefined') {
								$('.user_bmi').html(data.user_bmi);
								$('.user_bmi_contnr').show();
							}
							$("#user_id").val($("#edit_userid").val());
							$("#placeholder").html("");
							var infodataarray = data.contentchart;
							$("#morris-donutabove,#placeholder").hide();
							if(infodataarray != ''){
								$("#emptyplaceholder").remove();
								$("#morris-donutabove .panel-body").css("height","auto");
								$("#morris-donutabove #chartLegend").show();
								setTimeout( function(){
										renderGraphic(infodataarray);
								},1000);
								$("#morris-donutabove #chartLegend,#morris-donutabove,#placeholder").show();
							}else{
								$("#emptyplaceholder").remove();
								$("#morris-donutabove #chartLegend,#placeholder").hide();
								$("#morris-donutabove .panel-body").append("<span id='emptyplaceholder'>No journal entries have been saved yet to produce chart results</span>").css("height","25%");
								$("#morris-donutabove").show();
							}
							$('#loading-indicator').hide();
						}
					}
				});
				$('#userModal').modal();
				$('#userModal button#user').click();
			}
		});
}
function renderGraphic(infodataarray){
	$("#placeholder").unbind();
	$.plot('#placeholder', infodataarray, {
		series: {
			  pie: { 
				show: true,
				radius: 3/4,
				label: {
				  show: true,
				  radius: 3/4,
				  formatter: formatter,
				  background: {
						opacity: 0.5,
						color: '#000'
					}
				}
			  }
		},
		legend: {
				noColumns: 3,
				container: $("#chartLegend"),
				labelFormatter: leformatter,
				show: true
		},
		  grid: {
				hoverable: true,
			clickable: false
		  }
	});
}
function formatter(label, series) {
  return "<div style='font-size:8pt; text-align:center; padding:1px;z-index:1;'>" + label  + "</div>";
}
function leformatter(label, series) {
  return "<div style='font-size:8pt;padding:1px;z-index:1;'>" + label + " " +series.data[0][1] + "%</div>";
}
function show_more(event){
	event.preventDefault();
	var allRec = parseInt($("#af_all").val());
	var limit = parseInt($("#af_limit").val());
	var offset = $("#af_showmore").val();
	console.log(allRec+'==>'+limit+'===>'+offset);
	offset = parseInt(limit)+parseInt(offset);
	console.log(offset+'==>');
	if (event.handled !== true && (offset < allRec)) {
		event.handled = true;
		var userids = $("#af_userids").val();
		var site = $("#af_site").val();
		$("#af_showmore").val(offset);
		$.ajax({
			url: siteUrl+"ajax/getmorefeeddetails",
			method: 'post',
			data: {	userids: userids,offset:offset, limit:limit, site:site	},
			success: function(content) {
				$("#act_feed").append(content);
				if (!content) {
					$("#show_btn").hide();
				}
			}
		});
	}
	return false;
}
function viewwkout(wkoutid){
	$.ajax({
		url : siteUrl_frontend+"search/getmodelTemplate",
		data : {
			action : 'previewworkout',
			method :  'preview', 
			id : wkoutid,
			foldid : '0',
		},
		success : function(content){
			 content = content.replace('margin:auto;', "");
			$('#wkoutdetailsModal').html(content);
			$('#wkoutdetailsModal').modal();
		}
	});
}
function usereditsubmitForm(){
	var fusername_val = $("#fusernamech").val();
	var lusername_val = $("#lusernamech").val();
	var dobch_val = $("#dobch").val();
	var idavatardata = $('#profile_im').attr("date-imgid");
	var useridpro = $("#edit_userid").val();
	var usergender = $("input.user_gender[type='radio']:checked").val();
	var userphone = $("input#usernamephone").val();
	var alldatalist = [];
	alldatalist.push({"fnameuserch":fusername_val,"lnameuserch":lusername_val,"dobch":dobch_val,"avatar_id":idavatardata,"useridprofile":useridpro,"usergender":usergender,"userphone":userphone} );
	$.post(siteUrl+"search/usersavergen",{"action":"profile","post_form":alldatalist},function(res){
		$("#username_"+useridpro).html("").html(fusername_val+" "+lusername_val);
		showUserModel(useridpro,1);
	});
	return false;
}
console.log(siteName+'->'+siteAgeLimit);
var user_siteage = siteAgeLimit;
var user_sitename = siteName;
function changeeditpro(){
	$('#userModal').html('');
	var current_clickid = $("#user_id").val();
	$.get(siteUrl+"search/getmodelTemplate",{"action":"profileactions","method": "profileedit","id":current_clickid},function(response){
		if(response) {
			$('#userModal').html(response);
			if($("#dobch")){
				var mpFrom = $("#dobch").bind( "change", function() {
					var dobch_val = $( this ).val();
					if(dobch_val != ''){
						var dob = new Date(dobch_val);
						var today = new Date();
						var dayDiff = Math.ceil(today - dob) / (1000 * 60 * 60 * 24 * 365);
						var age = parseInt(dayDiff);
						if(age <= user_siteage){
							var message = 'Minimum age for using the <b>'+user_sitename+'</b>\'s my workouts platform is <b>'+user_siteage+'</b>';
							$('<li/>').html(message).appendTo('#validation-errors');
							$('.modal-validerror').addClass('hide');
							$('#errorMessage-modal').modal('show');
							$('#profileactions').formValidation('revalidateField', 'dobch');
						}else{
							$('#profileactions').formValidation('revalidateField', 'dobch');
						}
					}
				});
			}
			$("input.onlynumberallowed").keypress(function(event) {
				// Allow only backspace and delete
				 if((event.which != 8 && isNaN(String.fromCharCode(event.which)) || event.which == 32)){
					   event.preventDefault(); //stop character from entering input
				 }
			});
			if($('#profile_im').attr("date-imgid") != ''){
				$('#mdl_curr_imgid').val($('#profile_im').attr("date-imgid"));
			}
			$('#backpopup_search').attr("onclick","");
			$('#backpopup_search').attr("onclick","showUserModel('"+current_clickid+"',1)");
			getProfileImgOptionModal();
			$(document).on('click','a.edit-imgnew',function(e){
				if ($('#profile_im').attr("date-imgid")=='') {
					$('a#btn_profileimgedit').trigger('click');
				}else{
					var profileimg = $(this).find('img').attr('src');
					$('#myprofileoptionimagemodal #btn_profileimgprev').attr('data-itemurl', profileimg);
					$('#myprofileoptionimagemodal').modal();
				}
			});
			$(document).on('click','a#btn_profileimgedit',function(e){
				$("#userModal").modal('hide');
				$('#myprofileoptionimagemodal').modal('hide');
				e.preventDefault();
				e.stopImmediatePropagation();
				$("#triggerid").val(4);
				if($('#mdl_parentFolderId').length && $('#mdl_subFolderId').length){
					popuptriggerAjaxImgLibrary();
				}	
				if($("#triggerid").val() == 4){
					$('button.mdl_folder-select').attr("id", 4);
				}
				$('#mdl_popupimglibrary-modal').modal();
				initSimpleUpload();
			});
			checkFormdata();
		}
	});
}
function getProfileImgOptionModal(){
	$('#myprofileoptionimagemodal').html('');
	$.ajax({
		url : siteUrl+"search/getmodelTemplate",
		data : {
			action : 'profileimgoption',
			modal: 'myprofileoptionimagemodal'
		},
		success : function(content){
			$('#myprofileoptionimagemodal').html(content);
		}
	});
}
function profileImgPrevModal(elem){
	var imgurl = $(elem).attr('data-itemurl');
	if(imgurl!=undefined && imgurl!=''){
		$('#mdl_preview_libimg').html('<img alt="'+__('Preview Image')+'" class="Preview_image" id="mdl_previewlibimg" src="'+imgurl+'"/>');
	}else{
		$('#mdl_preview_libimg').html('<i class="fa fa-file-image-o prevfeat"></i>');
	}
	$('#mdl_popupimgprev-modal .mdl_preview-opt button').addClass('hide');
	$('#mdl_popupimgprev-modal').modal();
	var profileimgid = $('#profile_im').attr('date-imgid');
	if(profileimgid){
		$.post(siteUrl + 'ajax/ajaxInsertActivityfeed', {'actid': profileimgid, 'method': 'previewed', 'type': 'image'}, function(){});
	}
}
$('body').on('click', '#btn_profileimgclear', function(e) {
	e.preventDefault();
	var noImgUrl = $(this).attr('href');
	if (confirm('Are you sure, want to clear this profile image?')) {
		$('#userModal img#profile_im').attr('src', noImgUrl);
		$('#profile_im').attr("date-imgid", '');
		$('#myprofileoptionimagemodal').modal('hide');
	}
	return false;
});
function checkFormdata(){
	FormValidation.Validator.ageLimitch = {
		validate: function(validator, $field, options) {
			var dobch_val = $field.val();
			if(dobch_val != ''){
				var dob = new Date(dobch_val);
				var today = new Date();
				var dayDiff = Math.ceil(today - dob) / (1000 * 60 * 60 * 24 * 365);
				var age = parseInt(dayDiff);
				if(age <= user_siteage)
					return false;
				else
					return true;
			}
		}
	};
	$('#profileactions').formValidation({
		framework: 'bootstrap',
		fields: {
			dobch: {
				validators: {
					notEmpty: {
						message: 'The date of birth is required'
					},
					ageLimitch: {
						message: 'Minimum age for using the <b>'+user_sitename+'</b>\'s my workouts platform is <b>'+user_siteage+'</b>'
					}
				}
			}
		}
	}).on('success.form.fv', function(e) {return usereditsubmitForm();});
}
$(document).on('click','.modalBack',function(e){	
	e.preventDefault();
	e.stopImmediatePropagation();
	if($("#triggerid").val() == 4){
		var useridpro = $("#edit_userid").val();
		$('#userModal').modal();
	}
});
$(document).ready(function(){
	$('#mdl_popupimglibrary-modal').on('hidden.bs.modal', function(){
		if($("#triggerid").val() == 4){
			var useridpro = $("#edit_userid").val();
			$('#userModal').modal();
		}
	});
});
function convertDate(d){
 var parts = d.split(" ");
 var months = {Jan: "01", Feb: "02",Mar: "03",Apr: "04",May: "05",Jun: "06",Jul: "07",Aug: "08",Sep: "09",Oct: "10",Nov: "11",Dec: "12"};
 return parts[2]+"-"+months[parts[1]]+"-"+parts[0];
}
/*
(function () { 
    var minutes = true; // change to false if you'd rather use seconds
    var interval = minutes ? 60000 : 1000; 
    var IDLE_TIMEOUT = 25; // 3 minutes in this example
	var IDLE_TIMEOUT_FORM = 1; // 3 minutes in this example
    var idleCounter = 0;
    document.onclick = document.onmousemove = document.onkeypress = function () {
        idleCounter = 0;
    };
    window.setInterval(function () {
		console.log('==idleCounter>'+idleCounter);
        if (++idleCounter >= IDLE_TIMEOUT) {
            $.ajax({
				url : siteUrl+"ajax/refresh",
				success : function(content){
				}
			});
        }
		if (++idleCounter >= IDLE_TIMEOUT_FORM && (typeof confirmPopup === 'function')) {
			var onclickFun = $('.save-icon-button a').attr('onclick');
			//console.log('==>'+onclickFun);
			//if(typeof onclickFun != 'undefined'  && onclickFun.indexOf('confirmPopup') >0)
				//confirmPopup();
        }
    }, interval);
	
	// external link
	var a = document.getElementsByTagName('a');
    var b = a.length;
    while(b--){
        a[b].onclick = function(e){
			var onclickFun = $('.save-icon-button a').attr('onclick');
			console.log(this.href+'===>'+onclickFun);
			if(this.href !='javascript:void(0);' && typeof onclickFun != 'undefined' && onclickFun.indexOf('confirmPopup') >0){
				if(this.href.indexOf('/')>0){
					//e.preventDefault();
					//confirmPopup();
				}
			}else{
				var onclickFun = $(this).attr('onclick');
				console.log(onclickFun);
				if(typeof onclickFun != 'undefined')
					eval(onclickFun);
			}
        };
    }
}());
*/
function getTitlestrip(elem){
	var title = $(elem).html();
	if(typeof title != 'undefined')
		return title.replace(/'/g, "\\'");
}
/* $('body').bind('click', function(e) {
    if($(e.target).closest('.navbar-toggle').length == 0) {
        // click happened outside of .navbar, so hide
        var opened = $('.navbar-collapse').hasClass('collapse in');
        if ( opened === true ) {
            $('.navbar-collapse').collapse('hide');
        }
    }
}); */

//set dynamic height for the divs
function setDynamicHeight () {
	var $element = ''; var topheight = 0;
	if($('div#trainer_profile_modal div.modal-body').is(':visible')){
	 	$element = $('div#trainer_profile_modal div.modal-body');
		var topheight = $('div#trainer_profile_modal div.modal-body').position().top;
	}else if($('ul#contact-list.sTreeBase').is(':visible')){
		$element = $('ul#contact-list.sTreeBase');
		var topheight = $('ul#contact-list.sTreeBase').offset().top
	}else if($('ul.sTreeBase').is(':visible') && !$('.scrollablepadd ul.sTreeBase').is(':visible') && $('li').hasClass('item_parent_noclick') !==true){
		$element = $('ul.sTreeBase');
		var topheight = $('ul.sTreeBase').offset().top;
	}else if($('#record-gallery ul.data').is(':visible')){ //exercise lib page - record list
		$element = $('#record-gallery ul.data');
		var topheight = $('#record-gallery ul.data').offset().top - 50;
		if( navigator.userAgent.match(/iPhone|iPad|iPod/i) ) {
			var topheight = $('#record-gallery ul.data').offset().top - 20;
		}
	}else if($('#record-gallery .gallery-contnr').is(':visible')){ //exercise lib page - filter
		$element = $('#record-gallery .gallery-contnr');
		var topheight = $('#record-gallery .gallery-contnr').offset().top - 50;
		if( navigator.userAgent.match(/iPhone|iPad|iPod/i) ) {
			var topheight = $('#record-gallery .gallery-contnr').offset().top - 20;
		}
	}else if($('#img_listing.img-listing').is(':visible')){  //img lib page
		$element = $('#img_listing.img-listing');
		var topheight = $('#img_listing.img-listing').offset().top - 50;
		if( navigator.userAgent.match(/iPhone|iPad|iPod/i) ) {
			var topheight = $('#img_listing.img-listing').offset().top - 15;
		}
	}else if($('#xrRecInsertForm').is(':visible') && !$('#exercisecreate-modal').is(':visible')){ //exercise create page
		$element = $('#xrRecInsertForm .tab-content');
		var topheight = $('#xrRecInsertForm .tab-content').offset().top - 30;
		if( navigator.userAgent.match(/iPhone|iPad|iPod/i) ) {
			var topheight = $('#xrRecInsertForm .tab-content').offset().top + 30;
		}
	}else if(!$('div.modal div.scrollablepadd ul.sTreeBase').length && $('.scrollablepadd ul.sTreeBase').is(':visible')){
		$element = $('.scrollablepadd ul.sTreeBase');
		var topheight = $('.scrollablepadd ul.sTreeBase').offset().top;
	}else if($('div.modal div.scrollablepadd ul.sTreeBase').closest('.modal-body').length){
	 	$element = $('div.modal div.scrollablepadd ul.sTreeBase');
		if($('div.modal div#expended').is(':visible'))
			var topheight = $('div.modal div#expended').innerHeight() + 360;
		else
			var topheight = 360;
	}else if($('ul.sTreeBase.scrollwkout').length){
	 	$element = $('ul.sTreeBase.scrollwkout');
		var topheight = $('ul.sTreeBase.scrollwkout').offset().top;
	}
	if($element!=''){
		if($('.scrollablepadd ul.sTreeBase').closest('.modal-body').length){
			var heightlimit = topheight;
		}else if($('ul#contact-list.sTreeBase').is(':visible')){
			var heightlimit = topheight + 50;
		}else if($('ul.sTreeBase').hasClass('scrollwkout')){
			var heightlimit = topheight + 90;
		}else{
			var heightlimit = topheight + 100;
		}
		if($($element).hasClass('sTreeBase')){
			$element.css({
				'min-height': 'calc(100vh - ' + heightlimit + 'px)',
				'background-color': '#eee'
			});
		}
		$element.css({
			'max-height': 'calc(100vh - ' + heightlimit + 'px)',
			'overflow-y': 'auto',
			'overflow-x': 'hidden',
			'display' : 'inline-block',
			'width': '100%', 
			'height': '100%',
			'-webkit-overflow-scrolling': 'auto'
		});
	}
}
$(document).ready(function(){
	setDynamicHeight();
});
$(window).resize(function() {	
	setDynamicHeight();
});
$('.modal').on('shown.bs.modal', function() {
	setDynamicHeight();
});
$('.modal').on('hidden.bs.modal', function() {
	setDynamicHeight();
});
function showXrVariables(elem){
	var varid = $(elem).attr('data-varid');
	if($('#' + varid + ' div.hideadvance').hasClass('hide'))
		$('#' + varid + ' div.hideadvance').removeClass('hide');
	$('#' + varid + ' div.hideadvance').show();
}
function hideXrVariables(elem){
	var varid = $(elem).attr('data-varid');
	if($('#' + varid + ' div.hideadvance').hasClass('hide'))
		$('#' + varid + ' div.hideadvance').removeClass('hide');
	$('#' + varid + ' div.hideadvance').hide();
}

$(document).on('click','a.confirm, button.confirm', function(e){
	e.preventDefault();
	e.stopImmediatePropagation();
	$('div.errormsg').html('');
	if (e.handled !== true && $.confirm != undefined) {
		e.handled = true;
		var modalType = $(this).closest('div.vertical-alignment-helper').parent().attr('id');
		var hrefUrl = $(this).attr('href');
		var textmessage = $(this).attr('data-text');
		var onclickFun = $(this).attr('data-onclick');
		var allowconfirm = $(this).attr('data-allow');
		var allowconfirmname = $(this).attr('data-notename');
		if(eval(allowconfirm)){
			$.confirm({
				text: textmessage,
				title: "Confirmation required",
				confirm: function(button) {
					if(modalType !=''  && typeof modalType != 'undefined'){
						if(typeof onclickFun != 'undefined'){
							if(onclickFun.indexOf('addAssignWorkoutsByDate') !== -1){
								var elementsToRemove = [];
								for (var i = 0; i < $('div.modal-backdrop').length; i++) {
								  	if ($('div.modal-backdrop')) {
								 		elementsToRemove.push($('div.modal-backdrop')[i]);
								   }
								}
								for (var i = 1; i < elementsToRemove.length; i++) {
								  	elementsToRemove[i].parentNode.removeChild(elementsToRemove[i]);
								}
								eval(onclickFun);
							}else{
								eval(onclickFun);
								setTimeout(closeModelwindowCustom(modalType),100);
							}
						}else{
							closeModelwindowCustom(modalType);
						}
					}else if(hrefUrl!='' && typeof hrefUrl != 'undefined'){
						if(hrefUrl.indexOf('javascript:void(0)') !== -1 && typeof onclickFun != 'undefined'){
							eval(onclickFun);
						}else{
							window.location = hrefUrl;
						}
					}
				},
				cancel: function(button) {
				},
				confirmButton: "Yes",
				cancelButton: "No",
				post: true,
				confirmtype : allowconfirmname,
				confirmButtonClass: "btn-default activedatacol",
				cancelButtonClass: "btn-default",
				dialogClass: "modal-dialog modal-md"
			});
		}else{
			if(modalType !=''  && typeof modalType != 'undefined'){
				if(typeof onclickFun != 'undefined'){
					if(onclickFun.indexOf('addAssignWorkoutsByDate') !== -1){
						var elementsToRemove = [];
						for (var i = 0; i < $('div.modal-backdrop').length; i++) {
						  if ($('div.modal-backdrop')) {
							 elementsToRemove.push($('div.modal-backdrop')[i]);
						  }
						}
						for (var i = 1; i < elementsToRemove.length; i++) {
						  elementsToRemove[i].parentNode.removeChild(elementsToRemove[i]);
						}
						eval(onclickFun);
					}else{
						eval(onclickFun);
						setTimeout(closeModelwindowCustom(modalType),100);
					}
					
				}else{
					closeModelwindowCustom(modalType);
				}
			}else if(hrefUrl!='' && typeof hrefUrl != 'undefined'){
				if(hrefUrl.indexOf('javascript:void(0)') !== -1 && typeof onclickFun != 'undefined'){
					eval(onclickFun);
				}else{
					window.location = hrefUrl;
				}
			}
		}
	}
});
function closeModelwindow(myModel) {
	if (typeof(myModel) == 'undefined' || myModel.trim() == '') {
		myModel = 'myModal';
	}
	$('#' + myModel).modal('hide');
	if(myModel == 'exerciselib-model' || myModel == 'exercisecreate-modal'){
		if ($('#exercise_unit').val() == '0' || $('#exercise_unit').val() == '') {
			$('#exerciselib').bootstrapSwitch('state', false);
		}
	}else if(myModel != 'myModalFurther' && myModel != 'exercisecreate-modal' && myModel!='xrcisesaveopt-modal' && myModel!='popupimgeditor-model' && myModel!='popupfinalact-modal' && myModel!='mdl_popupfinalact-modal'){
		$('#'+myModel).html('');
	}
}
function closeModelwindowCustom(myModel){
	if(typeof(myModel) == 'undefined' || myModel.trim() == '' ){
		myModel = 'myModal';
	}
	$('#'+myModel).modal('hidecustom');
	if(myModel == 'exerciselib-model' || myModel == 'exercisecreate-modal'){
		if ($('#exercise_unit').val() == '0' || $('#exercise_unit').val() == '') {
			$('#exerciselib').bootstrapSwitch('state', false);
		}
	}else if(myModel != 'myModalFurther' && myModel != 'exercisecreate-modal' && myModel!='xrcisesaveopt-modal' && myModel!='popupimgeditor-model' && myModel!='popupfinalact-modal' && myModel!='mdl_popupfinalact-modal'){
		$('#'+myModel).html('');
	}
}
/*contact us script*/
function contactUsModal(){
	$('#userModal').html();
	$.ajax({
		url : siteUrl+"ajax/getStaticTemplate",
		data : {
			action : 'contactus',
			method :  'contactus',
			modelType : 'contactusModal'
		},
		success : function(content){
			var ajaxcontent = JSON.parse(content);
			$('#userModal').html(ajaxcontent);
			$('#userModal').modal();
		}
	});
}
$(document).on('submit', '#contactusModal', function(e) {
	e.preventDefault();
	e.stopImmediatePropagation();
	contactUsSubmit(this);
});
function contactUsSubmit(elem) {
	var formData = new FormData($(elem)[0]);
	$.ajax({
		url : siteUrl+"ajax/contactus",
		type : 'post',
		data : formData,
		cache: false,
		contentType: false,
		processData: false,
		success : function(content){
			var ajaxcontent = JSON.parse(content);
			if(ajaxcontent.data){
				$('#home.container > .bannermsg').remove();
				$('#home.container').prepend('<div class="row bannermsg"><div class="col-sm-12 col-xs-12 col-md-12 banner success alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Thanks for contacting us, we will reply to you soon !!!</div></div>');
				$('#userModal').modal('hide');
				$('#home.container > .bannermsg').fadeOut(10000);
			}else{
				$('#userModal .page_content > .bannermsg').remove();
				if(ajaxcontent.msg!=''){
					$('#userModal .page_content').prepend('<div class="bannermsg"><div class="col-sm-12 col-xs-12 col-md-12 banner errors alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+ajaxcontent.msg+'</div></div>');
				}else{
					$('#userModal .page_content').prepend('<div class="bannermsg"><div class="col-sm-12 col-xs-12 col-md-12 banner errors alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Error occured while sending e-mail !!!</div></div>');
				}
			}
		}
	});
}
$(document).on('change', "input:file[name=image]", function(e) {
	if(e.target.files.length){
		$("span.fileUpload-name").text(e.target.files[0].name);
	}
});
/*contact us script*/
/*bootstrap modal backdrop overlay*/
$(document).ready(function () {
	var zIndexnew = 0;
	var zIndexold = 1;
	$(document).on({
		'show.bs.modal': function () {
			var zIndex = Math.max.apply(null, Array.prototype.map.call(document.querySelectorAll('div.modal'), function(el) {
			  return +el.style.zIndex;
			})) + 10;
			if(zIndexnew ==0 && zIndexold ==1){
				zIndexnew = 1;
				zIndexold = 0;
			}else
				zIndexnew = 0;
			$(this).css('z-index', zIndex - zIndexnew);
			setTimeout(function() {
				$('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 2).addClass('modal-stack');
			}, 0);
		},
		'hidden.bs.modal': function(e) {
			if ($('.modal:visible').length > 0) {
				// restore the modal-open class to the body element, so that scrolling works
				// properly after de-stacking a modal.
				setTimeout(function() {
					$(document.body).addClass('modal-open');
				}, 0);
			}
		}
	}, '.modal');
	$('#errorMessage-modal').on('hidden.bs.modal', function(){
		$('#errorMessage-modal #validation-errors').html('');
	});
});
function hideHomeScreen(){
	$.ajax({
		url : siteUrl+"ajax/updateAddtoHome/",
		dataType : 'json',
		data : {
			action : 'addtohome',
		},
		success : function(donnee){
		}
	});
}
function notifyUpdate(selector){
	console.log($(selector).is(":checked"));
	type = $(selector).attr('name');
	$.ajax({
		url : siteUrl+"ajax/updateHide/",
		dataType : 'json',
		async : false,
		data : {
			action  : 'updateTour',
			type    : type,
			checkedFlag : $(selector).is(":checked")
		},
		success : function(donnee){
			console.log($('div.in .confirm[data-notename="'+type+'"]'));
			$('div.in .confirm[data-notename="'+type+'"]').each(function() {
				$(this).attr('data-allow',($(selector).is(":checked") == true ? 'false' : 'true'));
			});			
		}
	});
}
$(document).ready(function(){
	function hasHtml5Validation () {
	  //Check if validation supported && not safari
	  return (typeof document.createElement('input').checkValidity === 'function') && 
		(!(navigator.userAgent.search("Safari") >= 0 || ("standalone" in window.navigator && window.navigator.standalone)) && navigator.userAgent.search("Chrome") < 0);
	}

	$('form').submit(function(){
		if(!hasHtml5Validation())
		{
			var isValid = true;
			var $inputs = $(this).find('[required]');
			$inputs.each(function(){
				var $input = $(this);
				$input.removeClass('invalid');
				if(!$.trim($input.val()).length)
				{
					isValid = false;
					$input.addClass('invalid');                 
				}
			});
			if(!isValid)
			{
				return false;
			}
		}
	});
});
function birthDayPopup(selector){
	var cardio_select = $(selector).attr('data-datefor');
	var datetype = (cardio_select != undefined) ? cardio_select : '';
	$.ajax({
		url : siteUrl+"search/getmodelTemplate/",
		data : {
			action : 'confirmAssignDate',
			method : 'dateofbirth', 
			id : '',
			foldid : 0,
			date : $(selector).val(),
			type : datetype
		},
		success : function(content){
			$('#myprofiledatepicker').html(content);
			$('#myprofiledatepicker').modal();
			$("#myprofiledatepicker input#selected_date").bind( "change", function() {
				var dobch_val = $( this ).val();
				if(dobch_val != ''){
					var dob = new Date(dobch_val);
					var today = new Date();
					var dayDiff = Math.ceil(today - dob) / (1000 * 60 * 60 * 24 * 365);
					var age = parseInt(dayDiff);
					if(age <= user_siteage && !$('#userModal #cordio-filter .filter-container').is(':visible')){
						var message = 'Minimum age for using the <b>'+user_sitename+'</b>\'s my workouts platform is <b>'+user_siteage+'</b>';
						$('#myprofiledatepicker div.error').html(message)
						if($('#myprofiledatepicker div.error').hasClass('hide'))
						$('#myprofiledatepicker div.error').removeClass('hide');
					}else{
						if(!$('#myprofiledatepicker div.error').hasClass('hide'))
							$('#myprofiledatepicker div.error').addClass('hide').html('');
					}
				}
			});
		}
	});
}
function updateBirthDate(date_type){
	selectedDate = $('#myprofiledatepicker input#selected_date').val();
	if($('#myprofiledatepicker div.error').hasClass('hide')){
		closeModelwindow('myprofiledatepicker');
		if ($('#userModal input#dobch').is(':visible')) {
			$('#userModal input#dobch').val(selectedDate);
		} else if ($('#userModal span#birthdate-label').is(':visible')) { // refer userProfile.js
			if (selectedDate != '') {
				var d = new Date(selectedDate);
				var user_dobval = [d.getFullYear(), pad(d.getMonth() + 1), pad(d.getDate())].join('-');
				$('#userModal span#birthdate-label').text(user_dobval);
				$('#userModal input#user_birthdate').val(user_dobval).trigger('change');
			}
		} else if($('#userModal #cordio-filter .filter-container').is(':visible') && date_type != ''){
			if (selectedDate != '') {
				var d = new Date(selectedDate);
				var cardio_date = [pad(d.getDate()), pad(d.getMonth() + 1), d.getFullYear()].join('-');
				if(date_type == 'from'){
					$('#cardiodatefrom').val(cardio_date).trigger('change');
				}else{
					$('#cardiodateto').val(cardio_date).trigger('change');
				}
				$('#cordio-filter #filterby').val(4);
			}
		}
		return true;
	}
	return false;	
}
function pad(n) {
	return n < 10 ? '0' + n : n
}
jQuery.fn.insertAt = function(index, element) {
  var lastIndex = this.children().size()
  if (index < 0) {
    index = Math.max(0, lastIndex + 1 + index)
  }
  this.append(element)
  if (index < lastIndex) {
    this.children().eq(index).before(this.children().last())
  }
  return this;
}

/*collapse on scroll -workout*/
var ts;
$(document).bind('touchstart', 'form#createNewworkout .modal-body form#form-workoutrec', function (e){
	ts = e.originalEvent.touches[0].clientY;
});
$(document).bind('touchend', 'form#createNewworkout .modal-body form#form-workoutrec', function (e){
	if($('form#createNewworkout .modal-body').is(':visible')) {
		getTouchPositionAndToggle(e);
	}else if ($('form#form-workoutrec').is(':visible')) {
		getTouchPositionAndToggle(e);
	}
});
function getTouchPositionAndToggle(e){
	if($(e.target).closest('div.modal-header').length || $(e.target).closest('div.modal-footer').length){
		// || $(e.target).parent().attr('id') == 'scrollablediv-len' || $(e.target).hasClass('sTreeBase') || $(e.target).closest('ul').hasClass('sTreeBase')
		return;
	}
	var te = e.originalEvent.changedTouches[0].clientY;
	if(ts > te+5){
		if($('#expendeddiv').hasClass('fa-caret-up')){
			$('#expendeddiv').removeClass('fa-caret-up');
			$('#expendeddiv').addClass('fa-caret-down');
			$( "#expended" ).slideUp( "slow", function() {
				if($("#scrollablediv-len")){
					setDynamicHeight();
					$('ul.sTreeBase').scrollTop(0);
				}
			});
		}
	}else if(ts < te-5 && $(e.target).parent().attr('id') != 'scrollablediv-len' && !$(e.target).hasClass('sTreeBase') && !$(e.target).closest('ul').hasClass('sTreeBase')){
		if($('#expendeddiv').hasClass('fa-caret-down')){
			$('#expendeddiv').removeClass('fa-caret-down');
			$('#expendeddiv').addClass('fa-caret-up');
			$( "#expended" ).slideDown( "slow", function() {
				if($("#scrollablediv-len"))
					setDynamicHeight();
			});
		}
	}
}
/*collapse on scroll -workout*/
/*detect browser zoom level*/
function getBrowserZoomLevel() {
	var zoomlevel = Math.round(window.devicePixelRatio * 100);
	return zoomlevel;
}
function getAjaxSendCount() {
	var zoomlevel = getBrowserZoomLevel();
	// console.log(zoomlevel);
	var loopcount = 1;
	if(zoomlevel == 25){
		loopcount = 4;
	}else if (zoomlevel >= 30 && zoomlevel <= 39) {
		loopcount = 3;
	}else if (zoomlevel >= 40 && zoomlevel <= 50) {
		loopcount = 2;
	}
	return loopcount;
}
/*detect the element has scroll or not*/
(function($) {
	$.fn.hasVScrollBar = function() {
		return this.get(0).scrollHeight > this.get(0).clientHeight;
	}
})(jQuery);
/*detect any scroll in window*/
function hasScrollBar() {
	var w = window, d = w.document, c = d.compatMode;
	r = c && /CSS/.test(c) ? d.documentElement : d.body;
	if (typeof w.innerWidth == 'number') {
		return [ w.innerHeight > r.clientHeight, w.innerWidth > r.clientWidth ];
		return [ w.innerWidth > r.clientWidth, w.innerHeight > r.clientHeight ];
	} else {
		return [ r.scrollWidth > r.clientWidth, r.scrollHeight > r.clientHeight ];
	}
}
/*auto scroll for related records*/
function relatedAutoShowMore() {
	if ($("div#view_more").length) {
		var xrid = $("div#view_more").attr("data-xrid");
		var start = $("div#view_more").attr("data-start");
		var limit = $("div#view_more").attr("data-limit");
		var order = $("div#view_more").attr("data-order");
		var oldxrid = $("div#view_more").attr("data-oldxrid");
		var x = 1,
			loopcnt = getAjaxSendCount();
		while (x <= loopcnt) {
			sendAjax = false;
			setTimeout(function() {
				if ($("#relatedexc.modal-body").is(":visible") && $("#relatedexc.modal-body").find(".itemxr").length && getBrowserZoomLevel() < 100) {
					getRelatedRecordsMore(xrid, oldxrid, order, start, limit, '');
				}
			}, 200);
			x = x + 1;
		}
	}
	return;
}
$(window).resize(function(ev) {
	if ($("#relatedexc.modal-body").length && $("#relatedexc.modal-body").is(":visible") && $("#relatedexc.modal-body").find(".itemxr").length && !$("#relatedexc.modal-body").hasVScrollBar()) {
		if ($("div#view_more").length) {
			var xrid = $("div#view_more").attr("data-xrid");
			var start = $("div#view_more").attr("data-start");
			var limit = $("div#view_more").attr("data-limit");
			var oldxrid = $("div#view_more").attr("data-oldxrid");
			var order = $("div#view_more").attr("data-order");
			sendAjax = false;
			setTimeout(function() {
				getRelatedRecordsMore(xrid, oldxrid,order, start, limit, ev);
			}, 200);
		}
	}
});
function get_notify(){
	if($('small.autoshownotification').length>0){
		$.ajax({
			type: "GET",  
			url : siteUrl+"ajax/getnotify",
			data : {
				'loader':'hide',
				'action':'all',
				'siteslug':siteSlug,
				'siteurl':window.location.href
			},
			success: function(data) {
				var data = $.parseJSON( data );
				if(data.success){
					$('small.autoshownotification').html(data.chatnotify);
					if(data.chatnotify > 0){
						if(typeof search_users != 'undefined')
							eval(search_users('#####'));
					}else{
					}
					
				}else{
					$('#userModal').html(data.loginpopup).modal({backdrop: 'static', keyboard: false});
					$('small#ajaxnotifyone').removeClass('autoshownotification');
					$('small#ajaxnotifytwo').removeClass('autoshownotification');
				}
			}  
		});
	}
}
function convertText(myarr){
	var seltext = ''; var i;
	for(i=0; i < myarr.length; i++){ seltext += '<span class= "tag label label-info">'+myarr[i]+'</span> '; }
	if($('label#is_share_option').length>0 ){
		if(seltext == '')
			$('label#is_share_option').css('color',"red");
		else
			$('label#is_share_option').css('color',"#666666");
	}
	return seltext;
}

(function($){
 	var backDetectValues = {
 		frameLoaded: 0,
 		frameTry: 0,
 		frameTime: 0,
 		frameDetect: null,
 		frameSrc: null,
 		frameCallBack: null,
 		frameThis: null,
 		frameNavigator: window.navigator.userAgent,
 		frameDelay: 0,
 		frameDataSrc: '1x1.png'
 	};

	$.fn.backDetect = function(callback, delay) {
		backDetectValues.frameThis = this;
		backDetectValues.frameCallBack = callback;
		if(delay !== null){
			backDetectValues.frameDelay = delay;
		}
		if(backDetectValues.frameNavigator.indexOf('MSIE ') > -1 || backDetectValues.frameNavigator.indexOf('Trident') > -1){
			setTimeout(function(){
				$('<iframe src="1x1.png?loading" style="display:none;" id="backDetectFrame" onload="jQuery.fn.frameInit();"></iframe>').appendTo(backDetectValues.frameThis);
			}, backDetectValues.frameDelay);
		} else {
			setTimeout(function(){
				$('<iframe src="about:blank?loading" style="display:none;" id="backDetectFrame" onload="jQuery.fn.frameInit();"></iframe>').appendTo(backDetectValues.frameThis);
			}, backDetectValues.frameDelay);
		}	  
	};

	$.fn.frameInit = function(){
		backDetectValues.frameDetect = document.getElementById('backDetectFrame');
		if(backDetectValues.frameLoaded > 1){
			if(backDetectValues.frameLoaded == 2){
				backDetectValues.frameLoaded = 1;
				backDetectValues.frameCallBack.call(this);
				return false;
			}
		}
		backDetectValues.frameLoaded++;
		if(backDetectValues.frameLoaded == 1){
			backDetectValues.frameTime = setTimeout(function(){jQuery.fn.setupFrames();}, 500);
		}
    }; 

	$.fn.setupFrames = function(){
		clearTimeout(backDetectValues.frameTime);
		backDetectValues.frameSrc = backDetectValues.frameDetect.src;
		if(backDetectValues.frameLoaded == 1 && backDetectValues.frameSrc.indexOf("historyLoaded") == -1){
			if(backDetectValues.frameNavigator.indexOf('MSIE ') > -1 || backDetectValues.frameNavigator.indexOf('Trident') > -1){
				backDetectValues.frameDetect.src = backDetectValues.frameDataSrc + "?historyLoaded";
			} else {
					backDetectValues.frameDetect.src = "about:blank?historyLoaded";
			}
		}
	};

}(jQuery));
$(window).bind('load', function (e){
	$('body').backDetect(function(){
		$.confirm({
			text: 'Are you sure want to go previous page?',
			title: "Confirmation required",
			confirm: function(button) {
				$.mobile.back();
			},
			cancel: function(button) {},
			confirmButton: "Yes",
			cancelButton: "No",
			post: true,
			confirmtype : '',
			confirmButtonClass: "btn-default activedatacol",
			cancelButtonClass: "btn-default",
			dialogClass: "modal-dialog modal-md"
		});
	});
});

/*exercise set tab actions*/
$(document).on('click', '.delete-set', function() {
	if ($('.xrsets-tab .nav-tabs.setlist-tab > li').length > 1 && confirm('Do you want to delete this exercise set?')) {
		var xrsetid = $(this).closest('li').attr('data-setid');
		$('.tab-content.set-tab #set_' + xrsetid).append('<input type="hidden" data-keyval="' + xrsetid + '" id="removed_set_' + xrsetid + '" name="removed_set_' + xrsetid + '" value="1"/>').addClass('deleted');
		$(this).closest('li').remove();
		$('.xrsets-tab .nav-tabs.setlist-tab > li').each(function(i, li) {
			$(li).find('a').text('Set ' + (i + 1));
		});
		// Select last tab
		$('.xrsets-tab .nav-tabs.setlist-tab a:last').tab('show');
		if ($('.xrsets-tab .nav-tabs.setlist-tab > li').length <= 1) {
			$('.xrsets-tab').hide();
		}
	} else if ($('.xrsets-tab .nav-tabs.setlist-tab > li').length < 1) {
		alert('Exercise set must not be empty.');
	}
});
/*duplicate exercise set tab*/
function cloneXrsetTab() {
	var $div = $('.tab-content.set-tab > .tab-pane.active');
	var tabid = $div.attr('data-setid');
	var goalId = $('#createExercise input[name="goal_id_hidden"]').val();
	var incrId = $('div#itemset_' + goalId).parent('li').attr('data-inner-cnt');
	$('div#itemset_' + goalId).parent('li').attr('data-inner-cnt', parseInt(incrId) + 1);
	if (!validateXrSets('set_' + tabid))
		return false;
	if (confirm('Do you want to duplicate this exercise set?')) {
		var incid = $('#newlyAddedXr').val();
		var order = tabid.split('_')[0];
		incid = parseInt(incid) + 1;
		var li_len = $('.xrsets-tab .nav-tabs.setlist-tab > li').length;
		$div.clone()
			.removeClass('in active')
			.find('input.set-from').remove().end()
			.find('input[name^=xrtype]').each(function() {
				var xrtypeid = $(this).attr('id').split('_')[0];
				$(this).attr({
					'id': xrtypeid + '_new_' + incid,
					'name': 'xrtype_new_' + incid,
					'data-varid': 'set_' + order + '_new_' + incid
				});
			}).end()
			.attr({
				'id': 'set_' + order + '_new_' + incid,
				'data-setid': order + '_new_' + incid
			})
			.html(function(i, htmltext) {
				var regex = new RegExp(tabid, 'g');
				return htmltext.replace(regex, order + '_new_' + incid);
			})
			.append('<input type="hidden" class="set-from" data-keyval="' + order + '_new_' + incid + '" id="' + order + '_new_' + incid + '_from" name="' + order + '_new_' + incid + '_from" value="' + tabid + '"/>')
			.appendTo('.tab-content.set-tab');
		$('.xrsets-tab .nav-tabs.setlist-tab').append('<li class="" data-setid="' + order + '_new_' + incid + '"><a href="#set_' + order + '_new_' + incid + '" data-toggle="tab">Set ' + (parseInt(li_len) + 1) + '</a><i class="fa fa-times delete-set"></i></li>');
		if ($('.xrsets-tab .nav-tabs.setlist-tab > li').length > 1) {
			$('.xrsets-tab').show();
		}
		// Select last cloned tab
		$('.xrsets-tab .nav-tabs.setlist-tab a:last').tab('show');
		$('#newlyAddedXr').val(incid);
	} else {
		return;
	}
}
/*validate exercise set tabs*/
function validateXrSets(tabid) {
	if($('.tab-content.set-tab .set-tab-error').length){
		$('.tab-content.set-tab .set-tab-error').remove();
	}
	var valid = false;
	var modaldata = (tabid != '' && tabid != undefined) ? $('.tab-content.set-tab > .tab-pane#' + tabid).not('.deleted') : $('.tab-content.set-tab > .tab-pane').not('.deleted');
	modaldata.each(function(i, tab) {
		var tabindex = $('.tab-content.set-tab > .tab-pane').index($(tab));
		var tabinputs = $(tab).find('input').not('input[type="radio"]');
		tabinputs.each(function(j, input) {
			var inputname = $(input).attr('name');
			if (inputname == 'exercise_repetitions_hidden') {
				if ($(input).val() != '' && parseInt($(input).val()) != 0) {
					valid = true;
					return false;
				}
			} else if (inputname == 'exercise_resistance_hidden') {
				if ($(input).val() != '' && parseInt($(input).val()) != 0) {
					valid = true;
					return false;
				}
			} else if (inputname == 'exercise_time_hidden') {
				if ($(input).val() != '' && $(input).val() != '00:00:00') {
					valid = true;
					return false;
				}
			} else if (inputname == 'exercise_distance_hidden') {
				if ($(input).val() != '' && parseFloat($(input).val()) != 0) {
					valid = true;
					return false;
				}
			} else if (inputname == 'exercise_rate_hidden') {
				if ($(input).val() != '' && parseFloat($(input).val()) != 0) {
					valid = true;
					return false;
				}
			} else if (inputname == 'exercise_innerdrive_hidden') {
				if ($(input).val() != '' && parseFloat($(input).val()) != 0) {
					valid = true;
					return false;
				}
			} else if (inputname == 'exercise_angle_hidden') {
				if ($(input).val() != '' && parseFloat($(input).val()) != 0) {
					valid = true;
					return false;
				}
			} else if (inputname == 'exercise_rest_hidden') {
				if ($(input).val() != '' && $(input).val() != '00:00') {
					valid = true;
					return false;
				}
			} else if (inputname == 'exercise_remark_hidden') {
				if ($(input).val() != '') {
					valid = true;
					return false;
				}
			} else {
				valid = false;
				return true;
			}
		});
		if (!valid) {
			$('.tab-content.set-tab').prepend('<div class="set-tab-error text-center additioncol"><div class="col-xs-12 error-color">Please enter atleast any one Variable!</div></div>');
			$('.xrsets-tab .nav-tabs.setlist-tab a').eq(tabindex).tab('show');
		}
		return (valid) ? true : false;
	});
	return (valid) ? true : false;
}
function removeCommaWithValue(list, value) {
	return list.replace(new RegExp(",?" + value + ",?"), function(match) {
		var first_comma = match.charAt(0) === ',',
			second_comma;

		if (first_comma &&
			(second_comma = match.charAt(match.length - 1) === ',')) {
			return ',';
		}
		return '';
	});
}

function base64_encode(stringToEncode) { // eslint-disable-line camelcase
	//  discuss at: http://locutus.io/php/base64_encode/
	// original by: Tyler Akins (http://rumkin.com)
	// improved by: Bayron Guevara
	// improved by: Thunder.m
	// improved by: Kevin van Zonneveld (http://kvz.io)
	// improved by: Kevin van Zonneveld (http://kvz.io)
	// improved by: Rafal Kukawski (http://blog.kukawski.pl)
	// bugfixed by: Pellentesque Malesuada
	//   example 1: base64_encode('Kevin van Zonneveld')
	//   returns 1: 'S2V2aW4gdmFuIFpvbm5ldmVsZA=='
	//   example 2: base64_encode('a')
	//   returns 2: 'YQ=='
	//   example 3: base64_encode('? à la mode')
	//   returns 3: '4pyTIMOgIGxhIG1vZGU='
	if (typeof window !== 'undefined') {
		if (typeof window.btoa !== 'undefined') {
			return window.btoa(unescape(encodeURIComponent(stringToEncode)))
		}
	} else {
		return new Buffer(stringToEncode).toString('base64')
	}
	var b64 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/='
	var o1
	var o2
	var o3
	var h1
	var h2
	var h3
	var h4
	var bits
	var i = 0
	var ac = 0
	var enc = ''
	var tmpArr = []
	if (!stringToEncode) {
		return stringToEncode
	}
	stringToEncode = unescape(encodeURIComponent(stringToEncode))
	do {
		// pack three octets into four hexets
		o1 = stringToEncode.charCodeAt(i++)
		o2 = stringToEncode.charCodeAt(i++)
		o3 = stringToEncode.charCodeAt(i++)
		bits = o1 << 16 | o2 << 8 | o3
		h1 = bits >> 18 & 0x3f
		h2 = bits >> 12 & 0x3f
		h3 = bits >> 6 & 0x3f
		h4 = bits & 0x3f
		// use hexets to index into b64, and append result to encoded string
		tmpArr[ac++] = b64.charAt(h1) + b64.charAt(h2) + b64.charAt(h3) + b64.charAt(h4)
	} while (i < stringToEncode.length)
	enc = tmpArr.join('')
	var r = stringToEncode.length % 3
	return (r ? enc.slice(0, r - 3) : enc) + '==='.slice(r || 3)
}
function convArrToObj(array){
	var thisEleObj = new Object();
	if(typeof array == "object"){
		for(var i in array){
			var thisEle = convArrToObj(array[i]);
			thisEleObj[i] = thisEle;
		}
	}else {
		thisEleObj = array;
	}
	return thisEleObj;
}

(function($) {
	$.fn.parentNth = function(n) {
		var el = $(this);
		for(var i = 0; i < n; i++)
			el = el.parent();
		return el;
	};
})(jQuery);

$(document).on('submit', 'form#tagRecord', function(ev){
	ev.preventDefault();
	var unitid = $(this).find('input#unit_id').val();
	var tagsitem = $('input#tag_input').val();
	$.ajax({
		url : siteUrl+"ajax/addXrSetTag",
		method: 'post',
		type: 'json',
		data: {
			'unit_id': unitid,
			'xrtag-input': tagsitem
		},
		success: function(response){
			var result = JSON.parse(response);
			$('#myOptionsModalExerciseRecord').modal('hide');
			setTimeout(function(){
				var alerttype = (result.flag == 'true' ? 'success alert alert-success' : 'error alert alert-danger');
				var alertmsg = '<div class="banner ' + alerttype + '"><a data-ajax="false" data-role="none" href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+ result.msg +'</div>';
				if($('form#createNewworkout').is(':visible') && $('form#createNewworkout .modal-body').is(':visible')){
					$('form#createNewworkout .modal-body .banner.alert').remove();
					$('form#createNewworkout .modal-body').prepend(alertmsg);
				} else if($('form#previewworkout').is(':visible') && $('form#previewworkout .modal-body').is(':visible')){
					$('form#previewworkout .modal-body .banner.alert').remove();
					$('form#previewworkout .modal-body').prepend(alertmsg);
				} else if($('#errormsgdivtag').length){
					$('#errormsgdivtag .banner.alert').remove();
					$('#errormsgdivtag').append(alertmsg);
				}
			}, 200);
		}
	});
});