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
function showmoreXrdetail(){
	$('#showmorexr').addClass('hide');
	$('#showmorexrdetails').removeClass('hide');
	$('#hidemorexr').removeClass('hide');
}
function hidemoreXrdetail(){
	$('#showmorexr').removeClass('hide');
	$('#showmorexrdetails').addClass('hide');
	$('#hidemorexr').addClass('hide');
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
	$(document).scrollTop('0');
	if(!$('div#loading-indicator').length)
		var loader = $('body').append('<div id="loading-indicator" style="display:none" class="modal-backdrop-new fade in"></div>');
	$(document).ajaxSend(function(event, request, settings) {
		
		if(settings.url.indexOf('?')>=0)
			settings.url = settings.url+ '&user_from=admin&cp='+user_allow_page;
		else
			settings.url = settings.url+ '?user_from=admin&cp='+user_allow_page;
		$('#loading-indicator').show();
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
			if($('input.onlynumber').length > 0 && $('input.onlynumber').is(':visible') ) {
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
	
	if ( $( "#test_email_array" ).length ) {
		var parentElement = $(".select2contnr");
		$("#test_email_array").select2({
			/*tags: true,*/
			tokenSeparators: [",", " "],
			dropdownParent: parentElement
		});
	}
	
	
		$('.dataTable').DataTable({
			responsive: true,
			"aoColumnDefs" : [{
				'bSortable' : false,
				'aTargets' : [  'classactions' ]
			}]
			
		});
		
		setTimeout(function(){	$(".dataTables_filter").hide();},500);
		
	   $('div.dataTables_length select').select2();
	
	
	var parentElement = $(".select2contnr");
	$('#share_users,#workout_plans').select2({
		tags: true,
		tokenSeparators: [",", " "],
		dropdownParent: parentElement,
		dropdownCssClass: 'fullwidth'
	});
	$("#select-all").change(function(){
      $(".chkbox-item").prop('checked', $(this).prop("checked"));
    });
	$('#reports').DataTable();
	$('div.dataTables_length select').select2();

$(".delete_slider").click(function(){
		
		if(confirm("Are you sure you want to delete this?")){
		var id = $(this).attr("id");
		var table = $(this).attr("data-table");
		$("#row-"+id).remove();
			$.ajax({
						type: "post",
						
						url: siteUrl+"sites/sliderdelete",
						data: {id:id, table:table},		
						cache: false,						
						dataType: "html",
						success: function (response) {				
								
						}
					});
					
					}
					
					else{
        return false;
    }
	});
  
$(".delete_block").click(function(){
		
		if(confirm("Are you sure you want to delete this?")){
		var id = $(this).attr("id");
		var table = $(this).attr("data-table");
		
		$("#row-"+id).remove();
			$.ajax({
						type: "post",
						url: siteUrl+"sites/blockdelete",		
						data: {id:id, table:table},		
						cache: false,						
						dataType: "html",
						success: function (response) {				
								
						}
					});
					
					}
					
					else{
        return false;
    }
	});
	$(".delete_partner").click(function(){
		
		if(confirm("Are you sure you want to delete this?")){
		var id = $(this).attr("id");
		var table = $(this).attr("data-table");
		
		$("#row-"+id).remove();
			$.ajax({
						type: "post",
						url: siteUrl+"sites/partnerdelete",
						data: {id:id, table:table},		
						cache: false,						
						dataType: "html",
						success: function (response) {				
								
						}
					});
					
					}
					
					else{
        return false;
    }
	});
	$(".delete_testimonial").click(function(){
		
		if(confirm("Are you sure you want to delete this?")){
		var id = $(this).attr("id");
		var table = $(this).attr("data-table");
		
		$("#row-"+id).remove();
			$.ajax({
						type: "post",
						url: siteUrl+"sites/testimonialdelete",
						data: {id:id, table:table},		
						cache: false,						
						dataType: "html",
						success: function (response) {				
								
						}
					});
					
					}
					
					else{
        return false;
    }
	});
	$('#slug').on('keypress', function (event) {
		var regex = new RegExp("^[a-zA-Z0-9]+$");
		var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
		if (!regex.test(key)) {
		   event.preventDefault();
		   return false;
		}
	});
	
	rightaway = $('#rightaway').val();
	if(rightaway == '1'){
		$('.hiderightaway').hide();
	}else{
		$('.hiderightaway').show();
	}
	
	//$('#datepicker,.datepicker').datetimepicker({format: 'DD/MM/YYYY', inline: true});
	$('#datepicker').datepicker({ dateFormat: 'dd/mm/yy' });
	$('#datetimepicker3').datetimepicker({ format: 'LT'});
	
	
	$('#rightaway').change(function(){
		if($(this).val() == '1'){
			$('.hiderightaway').hide();
		}else{
			$('.hiderightaway').show();
		}
	});
	
   
	
});
function showTestEmailForm() {
	jQuery('#addEmailFormContnr').slideToggle();
}
$(".addsitetomanager").click(function(){ 
	sites = $('#mangersites').val();
	userid =  $('#curid').val();
	$.ajax({
		url: siteUrl+"ajax/addSitesToManager",
		type: 'POST',
		dataType: 'json',
		data:{userid:userid,sites:sites},
		success:function(data){
			if(data.success) {				
				$("#assignsitemanager").modal("hide");
				window.location.reload(true);
			}
		}
	});
});
function sitesmanage(id) {
	
	$('.message-row').hide();	
	$.ajax({
		url: siteUrl+"ajax/getSitesToManager",
		type: 'GET',
		dataType: 'json',
		data:{'userid':id},
		success:function(data){
			if(data.success) {
				$('#mangersites').val(data.user_sites);
				common_dropdown_multiple("#mangersites",data.source);
				
				
				var userinfo = data.userinfo; 
				$(".user_name").text(userinfo[0]["user_fname"] + " "+ userinfo[0]["user_lname"]);
				$(".user_email").text(userinfo[0]["user_email"]);
				$("#assignsitemanager").modal("show");
				$('#curid').val(id);
			}
		}
	});
	
}

function renderGraphic(infodataarray){
   $.plot('#placeholder', infodataarray, {
		series: {
			  pie: { 
				show: true,
				radius: 1,
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
				//show: true,
				/*labelBoxBorderColor: "none",*/
				noColumns: 3,
				container: $("#chartLegend"),
				labelFormatter: leformatter,
				show: true
				/*labelFormatter: function(label, series){
						return '<div style="font-size:8pt;padding:2px;color:#000000;">' + label + ' ' +series.data[0][1]+'%</div>';
				}*/
                   
		}
	});
}

function formatter(label, series) {
  return "<div style='font-size:8pt; text-align:center; padding:2px;'>" + label  + "</div>";
}
function leformatter(label, series) {
  return "<div style='font-size:8pt;padding:2px;'>" + label + " " +series.data[0][1] + "%</div>";
}

function preview_erexrcise(exerciseId,title) {
	var wkoutId = '';
	$('#xrciseprev-modal').html();
	$.ajax({
		url: siteUrl_frontend + "search/getmodelTemplate",
		data: {
			action: 'previewExerciseOfDay',
			method: 'preview',
			id: exerciseId,
			foldid: wkoutId,
			fromAdmin: true,
		},
		success: function(content) { //alert("Success");
			$('#xrprev-modal').html(content);
			$('.xrtitle').text('Preview - ' + title);
			$('#xrprev-modal').modal();
		}
	});
}
function opendeviceinfo(act_feed_id){
	$.ajax({
		url : siteUrl_frontend+"search/getmodelTemplate",
		data : {
			action : 'deviceinfo',
			method :  'preview',
			id : act_feed_id,
			foldid : '0',
			fromAdmin: true,
		},
		success : function(content){
			$('#wkoutdetailsModal').html(content);
			if(content !='')
				$('#wkoutdetailsModal').modal();
		}
	});
}
function toggleDivEmail() {
   if ($('#expendeddiv').hasClass('fa-caret-up')) {
      $('#expendeddiv').removeClass('fa-caret-up');
      $('#expendeddiv').addClass('fa-caret-down');
      $("#expended").slideUp("slow", function() {
         setDynamicHeight();
      });
   } else if ($('#expendeddiv').hasClass('fa-caret-down')) {
      $('#expendeddiv').removeClass('fa-caret-down');
      $('#expendeddiv').addClass('fa-caret-up');
      $("#expended").slideDown("slow", function() {
			setDynamicHeight();
      });
   }
}
function viewwkout(wkoutid,type){
	$.ajax({
		url : siteUrl_frontend+"search/getmodelTemplate",
		data : {
			action : 'previewworkout',
			method :  'preview',
			id : wkoutid,
			foldid : '0',
			type : type,
			fromAdmin: true,
		},
		success : function(content){
			$('#wkoutdetailsModal').html(content);
			$('#wkoutdetailsModal').modal();
		}
	});
}
function hide_basic_info()
{
	userlevel = $("#user_level").val();
	if(userlevel=="register"){
		$(".dateofbirth").show();
	}else{
		$(".dateofbirth").hide();
	}
}
if($("#user_level").length) hide_basic_info();
$( "#user_level" ).change(function() {
  hide_basic_info()
});
function submitAssignSite(){
	var form = $('#assign-site');
$.ajax({
		url: siteUrl+"user/assignSitesToManager",
		type: 'POST',
		dataType: 'json',
		data:form.serialize(),
		success:function(data){
			if(data.success) {
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
				if(typeof data.user_dob != 'undefined') {
					$('.user_dob').html(data.user_dob);
					$('.user_dob_contnr').show();
				}
				if(typeof data.user_age != 'undefined') {
					$('.user_age').html(data.user_age);
					$('.user_age_contnr').show();
				}
				if(typeof data.message != 'undefined') {
					
					$('.message-row .alert-success span').html(data.message);
					$('.message-row').show();
				}
				$('.showpopup').click();
			}
		}
		
	});
	
}
function changeManagerStatus(userId,statusId) {
	if(statusId != ''){
		if(isNaN(statusId)){
			if(statusId.indexOf('-') === -1){
				if(statusId=='sitesmanage'){
					sitesmanage(userId);
					$(".selectAction").select2("val", "");
				}else if(statusId=='contact_status'){
					contactmanage(userId);
					$(".selectAction").select2("val", "");
				}else{	
					send_email('single', userId);
					$(".selectAction").select2("val", "");
				}	
			}
			else{
				var editid = statusId.split("-");
				location.href = siteUrl+"user/edit/"+editid[1];
				$(".selectAction").select2("val", "");
			}
		}else{
			$.ajax({
				url: siteUrl+"ajax/UserUpdateStatus",
				type: 'POST',
				dataType: 'json',
				data:{'userid':userId,'status':statusId},
				success:function(data){
					if(data.success) {
						$('.del-sucess span').text(data.message);
						$('.del-sucess').show();
						var setstatue = "";
						if(statusId == 1){
							setstatue = "Active";
						}else if(statusId == 2){
							setstatue = "Suspended";
						}else if(statusId == 3){
							setstatue = "Expired";
						}
						else if(statusId == 4){
							//setstatue = "Remove";
							$('#row-'+userId).remove();
						}
							
						$('.statusupdate-'+userId).html(setstatue);
					} 
				}
			});
		$(".selectAction").select2("val", "");
		}
	}else{
		alert("Please Choose Your Action");
	}
}
function showAdvanceSearch() {
	$('.advance-search-contnr').slideToggle();
	if($('.search-filter').hasClass("fa-caret-down")){
		$('.search-filter').removeClass('fa-caret-down');
		$('.search-filter').addClass('fa-caret-up');
	}else{
		$('.search-filter').removeClass('fa-caret-up');
		$('.search-filter').addClass('fa-caret-down');
	}
}
/*$('select#sortby').on('change', function(e){
	getAdvanceSearchRecords();
});*/

function clearForm(oForm) {
    
  var elements = oForm.elements; 
    
  oForm.reset();

  for(i=0; i<elements.length; i++) {
      
	field_type = elements[i].type.toLowerCase();
	switch(field_type) {
	
		case "text": 
		case "password": 
		case "textarea":
	    case "hidden":	
			elements[i].value = ""; 
			break;
        
		case "radio":
		case "checkbox":
  			if (elements[i].checked) {
   				elements[i].checked = false; 
			}
			break;

		case "select-one":
		case "select-multi":
            		elements[i].selectedIndex = -1;
			break;

		default: 
			break;
	}
    }
}
var fileList = $('#exercise-table').find('.data');
/* Search Input (from FILTERS page) */
$(".searchclear").click(function () {
	$(this).prev('input').val('').focus();
	$(this).hide();
	fileList.empty().hide();
	$('.nothingfound').hide();
	$('.gallery-empty-row').removeClass('hide');
	$('#xr_filter_toggle').removeClass('hide');
	$('#xr_filter_reset').removeClass('shows');
	$('#xr_filter_reset').addClass('hide');
});
function getAdvanceSearchRecords() {
	var form = $('.advnce-srch-frm');
	$.ajax({
		url: siteUrl+"exercise/getAdvanceSearchExerciseRecords",
		type: 'POST',
		dataType: 'json',
		data:form.serialize(),
		success:function(data){ // alert (JSON.stringify(data));  
			$('.dataTable').DataTable().destroy();
			$('#table-content-contnr').html(data.message);
			$('.selectActions1').select2();
			//$('.dataTable').dataTable();
			
		}	
	});
}

  if($('#site_ids').length) $('#site_ids').select2();


function common_dropdown_multiple(element, datasource) {
        $(element).select2({
			multiple: true,
			/*minimumInputLength:1,			*/
			
			data:datasource,
	
			initSelection: function(element, callback){
            var preselected_ids = func_extract_preselected_ids(element); 
            var preselections = func_find_preselections(preselected_ids,datasource);
            callback(preselections);
        }
		});
		  
		  
	
    }
	
 function func_extract_preselected_ids(element){
        var preselected_ids = [];
        var delimiter = ',';
        if(element.val()) {
            if(element.val().indexOf(delimiter) != -1)            
                $.each(element.val().split(delimiter), function () {
                    preselected_ids.push({id: this});
                });
            else
                preselected_ids.push({id: element.val()});            
        }
        return preselected_ids;
    };
    
    // find all objects with the pre-selected IDs
    // preselected_ids: array of IDs
    function func_find_preselections(preselected_ids,datasource){
        var pre_selections = []
        for(index in datasource)
            for(id_index in preselected_ids) {
                var objects = func_find_object_with_attr(datasource[index], {key:'id', val:preselected_ids[id_index].id})
                if(objects.length > 0)
                    pre_selections = pre_selections.concat(objects);
            }
        return pre_selections;
    };
	function func_find_object_with_attr(object, attr) {
        var objects = [];
        for (var index in object) {
            if (!object.hasOwnProperty(index)) // make sure object has a property. Otherwise, skip to next object.
                continue;
            if (object[index] && typeof object[index] == 'object') { // recursive call into children objects.
                objects = objects.concat(func_find_object_with_attr(object[index], attr));
            }
            else if (index == attr['key'] && object[attr['key']] == attr['val'])
                objects.push(object);
        }
        return objects;
    }    

function templateAction(optVal,tempId){
	var siteId = $('#site_id').val();
	if(optVal!='') {
		if(optVal=='edit') {
			location.href = siteUrl+"email/create/"+siteId+'/'+tempId;
		}else if(optVal=='share'){
			$('#shareTemplateModal').modal('show');	
			$("select.site_id").select2('val', ['All']);
			$("select.site_id").select2();
			$("#template_id").val(tempId);
		}
		else if(optVal=='delete') {
			location.href = siteUrl+"email/templatename/"+siteId+'/'+tempId;
		}
	 $(".selectAction").select2("val", "");
	}
}

function deviceAction(optVal, deviceid){
	if(optVal!='') {
		if(optVal=='edit') {
			location.href = siteUrl+"devicemanager/create/"+deviceid;
		}else if(optVal=='delete') { 
			//$('#confirm').modal("show");
			$('#confirm').modal({ backdrop: 'static', keyboard: false })
			.one('click', '#delete', function (e) {
				 location.href = siteUrl+"devicemanager/browse/"+deviceid;
			});
			
			
		}
	 $(".selectAction").select2("val", "");
	}
}

function variableAction(optVal,varId){
	var siteId = $('#site_id').val();
	if(optVal!='') {
		if(optVal=='edit') {
			location.href = siteUrl+"email/emailvariables/"+siteId+'/'+varId;
		}else if(optVal=='share'){
			$('#shareTemplateModal').modal('show');	
			$("select.site_id").select2('val', ['All']);
			$("select.site_id").select2();
			$("#template_id").val(varId);
		}
		else if(optVal=='delete') {
			location.href = siteUrl+"email/variablename/"+siteId+'/'+varId;
		}
	 $(".selectAction").select2("val", "");
	}
}


function smtpAction(optVal,tempId){
	var siteId = $('#site_id').val();
	if(optVal!='') {
		if(optVal=='edit') {
			location.href = siteUrl+"email/smtp/"+siteId+'/'+tempId;
		} else if(optVal=='delete') {
			location.href = siteUrl+"email/smtpsettings/"+siteId+'/'+tempId;
		}
	$(".selectAction").select2("val", "");	
	}
}
function deliveryAction(optVal,tempId){
	var siteId = $('#site_id').val();
	if(optVal!='') {
		if(optVal=='edit') {
			location.href = siteUrl+"email/delivery/"+siteId+'/'+tempId;
		} else if(optVal=='delete') {
			location.href = siteUrl+"email/deliverysettings/"+siteId+'/'+tempId;
		}
	$(".selectAction").select2("val", "");
	}
}
function pageAction(optVal,tempId){
	var siteId = $('#site_id').val();
	if(optVal!='') {
		if(optVal=='edit') {
			location.href = siteUrl+"cms/create/"+siteId+'/'+tempId;
		} else if(optVal=='delete') {
			location.href = siteUrl+"cms/pagelist/"+siteId+'/'+tempId;
		}
	$(".selectAction").select2("val", "");
	}
}

function commonpageAction(optVal,tempId){
	var siteId = $('#site_id').val();
	if(optVal!='') {
		if(optVal=='edit') {
			location.href = siteUrl+"cms/common_create/"+siteId+'/'+tempId;
		} else if(optVal=='delete') {
			location.href = siteUrl+"cms/common_pagelist/"+siteId+'/'+tempId;
		}
	$(".selectAction").select2("val", "");
	}
}

if($(".selectAction").length) {
	$('.selectAction').select2({
		minimumResultsForSearch: -1
	});
}
function email_report_submit()
	{  
		if($.trim($('#email_address').val()) == '') {
			$('.form-error').html('Please enter email address');
			return false;
		} 
			$.ajax({
				url: siteUrl+'user/report_email',
				data: $('#email_report_frm').serialize(),
				type: 'POST',
				async: false,
				success: function(data) {
					if(data == 1) {
						$('#email_address').val('');
						$('.response').html('Report sent successfully.');
						$('.response').css('color','green');
						setTimeout(function(){
							$('.response').html('');
							$('#EmailModal').modal('hide');
						}, 5000);
					} else if(data == 'no_data') {
						$('#email_address').val('');
						$('.response').html('No data found.');
						$('.response').css('color','red');
					} else {
						$('#email_address').val('');
						$('.response').html('Oops! Try again later.');
						$('.response').css('color','red');
					}
					return false;
				}
			});
			return false;
	}


/*

 $(document).ajaxStart(function () {
	$.ajax({
		 type: "POST",
		 url: siteUrl+'admin/dashboard/checklogin',
		 async: false,
		 contentType: "application/json; charset=utf-8",
		 dataType: "json",
		 success: function (result) {
			  if (parseInt(result.d) == 0) {
					window.location.href = siteUrl;
			  }
		 },
		 Error: function (msg) {
			  window.location.href = siteUrl;
		 }
	});
});
*/
/*****************By Praba***********/
var aacus = 0;
function show_more(event){
	event.preventDefault();
	var bythis = $("#a_bythis").val();
	if(bythis){
		if (bythis!=4) {
			$(".acusdate").hide();
			aacus =0;
		}else{
			aacus++;
			$(".acusdate").show();
			if (aacus==1) {
				$("#a_fromdate").val('');
				$("#a_todate").val('')
				$('#act_feed').html("<p style='color:#FF0000;border:0px solid red;text-align:center;margin-top:25px;font-weight:bold;'>Please check your custom dates</p>");
				return false;
			}else if($("#a_fromdate").val()=='' || $("#a_todate").val()==''){
				$('#act_feed').html("<p style='color:#FF0000;border:0px solid red;text-align:center;margin-top:25px;font-weight:bold;'>Please check your custom dates</p>");
				return false;
			}
		}
	}else{
		$(".acusdate").show();
		
	}
	
	var popupFlag = false;
	if($('input#af_popup').length){
		var feedtype = $("#a_feedtype").val();
		var fromdate = $("#a_fromdate").val();
		var todate = $("#a_todate").val();
		var limit = $("#af_limit_popup").val();
		var offset = $("#af_showmore_popup").val();
		var userids = $("#af_userids_popup").val();
		var allRec = parseInt($("#af_all_popup").val());
		var site = $("#af_site_popup").val();
		var popupFlag = true;
	}else{
		var users = ($("input#users").val())?$("input#users").val():'';
		if ($("input#users").length) {
			$("input#af_userids").val(users);	
		}else{
			if($("input#tf_userids").length)
				$("input#af_userids").val($("input#tf_userids").val());	
		}
		var feedtype = $("#a_feedtype").val();
		var fromdate = $("#a_fromdate").val();
		var todate = $("#a_todate").val();
		var limit = $("#af_limit").val();
		var offset = $("#af_showmore").val();
		var userids = $("#af_userids").val();
		var allRec = parseInt($("#af_all").val());
		var site = $("#af_site").val();
	}
	if (!userids) {
		//$('#act_feed').html("<p style='color:#FF0000;border:0px solid red;text-align:center;margin-top:25px;font-weight:bold;'>Please choose users</p>");
		return false;
	}
	offset = parseInt(limit)+parseInt(offset);
	console.log("SHOW MORE ----- userids: "+userids+", offset:"+offset+", limit:"+limit+", site:"+site+", fdate : "+fromdate+", tdate :"+ todate+", by:"+bythis)
	if (event.handled !== true && (offset < allRec)) {
		$("#af_showmore").val(offset);
		event.handled = true;
		$.ajax({
			url: siteUrl+"dashboard/getfeeddetails",
			method: 'post',
			data: {	userids: userids,offset:offset, limit:limit, site:site,
					fdate : fromdate,
					tdate : todate,
					by:bythis,
					feedtype:feedtype,
					popupFlag:(popupFlag ? popupFlag : ''),
					is_front:(popupFlag ? popupFlag : '')
			},
			success: function(content) {
				if (!content) {
					$("#show_btn").hide();
				}else{
					if($('input#af_popup').length){
						$("#act_feed_popup").append(content);
					}else{
						$("#act_feed").append(content);
					}
				}
			}
		});
	}
	return false;
}


function remove_error(id,arg){
	var r = confirm("Are you remove this error?");
	if (r) {
		window.location.href = siteUrl + "dashboard/remove_error_feed/"+id+"?etype="+arg;
	}
}
function update_error_feed(id,status){
	var r = confirm("Are you sure to change the status?");
	if (r) {
		$('#errorviewModal').modal('hide');
		$.ajax({
			url: siteUrl + "dashboard/update_error_feed",
			method: 'post',
			data: {
				id:      id,
				status:  status
			},
			success: function(content) {
				window.location.reload();
				/*
				var str = '';
				if (status==1) {
					str = "<i class=\"fa fa-check error_check_css_fixed\" id='ck_"+id+"' onclick=\"update_error_feed("+id+",0)\"   title='Click here to change the status'></i>";
					//$("#ck_"+id).removeClass( "error_check_css" );$("#ck_"+id).addClass( "error_check_css_fixed" );
				}else{
					str = "<i class=\"fa fa-check error_check_css\" id='ck_"+id+"' onclick=\"update_error_feed("+id+",1)\" title='Click here to change the status'   ></i>";
					//$("#ck_"+id).removeClass( "error_check_css_fixed" );$("#ck_"+id).addClass( "error_check_css" );
				}
				$("#tdck_"+id).html(str);
				*/
			}
		});
	}
}
setTimeout(function(){
	$(".alert-success").fadeOut();
	$(".alert-danger").fadeOut();
},2500);

function viewError(id){
	$.ajax({
		url: siteUrl + "dashboard/view_error_feed",
		method: 'post',
		data: {
			id:      id,
		},
		success: function(content) {
			var obj = jQuery.parseJSON( content );
			if (obj.tot_e>0) {
				$('#e_unread').html(parseInt(obj.tot_e));
			}else{
				$('#e_unread').hide();
			}
			if (obj.tot_p>0) {
				$('#php_unread').html(parseInt(obj.tot_p));
			}else{
				$('#php_unread').hide();
			}
			if (obj.tot_m>0) {
				$('#mysql_unread').html(parseInt(obj.tot_m));
			}else{
				$('#mysql_unread').hide();
			}
			$("#errorviewModal").modal("show");
			$("#viewerror").html(obj.str);
			$("#row-"+id).removeClass("str_bold");
		}
	});
}

$(document).ready(function(){
	tinymce.init({
	  selector: 'textarea#page_content,textarea#home_page_content,textarea#b_description,textarea#t_description,textarea#social_post_content',
	  height: 500,
	  plugins: [
		'advlist autolink lists link image charmap print preview anchor',
		'searchreplace visualblocks code fullscreen',
		'insertdatetime media table contextmenu paste code'
	  ],
	  toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
	 
	});
});


function changeaction(arg){
	var id=$("#user_id").val();
	switch (arg) {
		case "status":
			changeuserstatus('single', id);
			break;
		case "questionans":
			getintialquestionnaire();
			break;
		case "taguser":
			taguser('single', id);
			break;
		case "sharewkout":
			$("#shareModal").modal('show');
			var data = new Array();
			data.push(id);
			$("#subscriber_id").val(data);
			$("select.wkout_id").val(data);
			$("select.wkout_id").select2();
			break;
		case "sendemail":
			var checkedusers = new Array();
			checkedusers.push(id);
			send_multiple_email(checkedusers);
			break;
		case "questionqns":
			previewans(id);
			break;
		default:
         break;
	}
}
function previewans(id){
	$("#previewquestionansModal").modal("show");
	$.ajax({
		url: siteUrl + "questions/getansweredquestions",
		method: 'post',
		data: { userid:id},
		success: function(content) {
			if (content) {
				$("#previewans").html(content);
			}
		}
	});
}

/*****************By Praba***********/
function showUserModel(userId,showFeed) {
	var showFeed = 1;
	$.get(siteUrl_frontend + "search/getmodelTemplate", {"action": "profileactions", "method": "profiledetails", "id":userId, "fromAdmin": 1}, function(response){
		if(response) {
			$('#userModal').html(response);
			$('.rgstr_flds').hide();
			$.ajax({
				url: siteUrl_frontend + "ajax/getFeedDetails",
				type: 'POST',
				dataType: 'json',
				data: {'id': userId, 'is_front': 0, 'popupFlag': true},
				beforeSend: function () {
					setTimeout(function(){ $('#loading-indicator').show() }, 10);
				},
				success:function(data){
					if(data.success) {
						if($("#curr_imgid").length > 0 && $("#curr_imgid").val() != ''){
							$("#curr_imgid").val('');
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

function common_popuptriggerImgPrevModal(elem){
	var imgurl = $(elem).attr('data-itemurl');
	if(imgurl!=undefined && imgurl!=''){
		$('#mdl_common_preview_libimg').html('<img alt="Feature Image" class="Preview_image" id="mdl_common_preview_libimg" src="'+siteUrl_frontend+imgurl+'"/>');
		$('#mdl_preview-btn').attr('data-itemurl',$(elem).attr('data-itemurl'));
	}else{
		$('#mdl_common_preview_libimg').html('<i class="fa fa-file-image-o prevfeat"></i>');
		$('#mdl_preview-btn').attr('data-itemurl','');
	}
	$('#mdl_common_popupimgprev-modal').modal();
}

$(document).on('click','#sitelogoin',function(e){
	e.preventDefault();
	e.stopImmediatePropagation();
	var site_di = $(this).attr('site_id');
	$.post(siteUrl+"sites/generalqueryset",{"action":"deletelogo","valuesiteid":site_di},function(){
			$("#sitelogoin,#imagelogosite").hide();
	});
	//alert("=========="+$(this).attr('site_id'));
});
// delete social post img
$(document).on('click','.rmv-socialpost-img',function(e){
	e.preventDefault();
	e.stopImmediatePropagation();
	var site_id = $(this).attr('data-siteid');
	var socialimg = $(this).attr('data-src');
	$.post(
		siteUrl+"sites/deleteSocialpageImg", {
			"action": "deleteimg",
			"fieldname": "site_image",
			"siteid": site_id,
			"imgroot": socialimg
		}, function(){
			$(".rmv-socialpost-img, .socialpost-img").hide();
		}
	);
});
$(document).ready(function(){
	$('body').bind('shown.bs.modal', '.modal', function() {
		$('body').addClass('modal-open');
	}).bind('hidden.bs.modal', '.modal', function() {
		$('body').removeClass('modal-open');
		if($('.modal:visible').length >0) $('body').addClass('modal-open');
	});
});
function loadimage(element){
	$(element).css('width', 'auto');
}

//set dynamic height for the divs
/*function setDynamicHeight () {
	var $element = ''; var topheight = 0;
	if($('#img_listing.img-listing').is(':visible')){
		$element = $('#img_listing.img-listing');
		var topheight = $('#img_listing.img-listing').offset().top;
	}
	if($element!=''){
		var heightlimit = topheight + 70;
		$element.css({
			'max-height': 'calc(100vh - '+heightlimit+'px)',
			'overflow-y': 'auto',
			'overflow-x': 'hidden'
		});
	}
}*/
//set dynamic height for the divs
function setDynamicHeight () {
	var $element = ''; var topheight = 0;
	if($('ul.sTreeBase').is(':visible') && !$('.scrollablepadd ul.sTreeBase').is(':visible') && $('li').hasClass('item_parent_noclick') !==true){
		$element = $('ul.sTreeBase');
		var topheight = $('ul.sTreeBase').offset().top;
	}else if($('#record-gallery ul.data').is(':visible')){ //exercise lib page - record list
		$element = $('#record-gallery ul.data');
		var topheight = $('#record-gallery ul.data').offset().top;
	}else if($('#record-gallery .gallery-contnr').is(':visible')){ //exercise lib page - filter
		$element = $('#record-gallery .gallery-contnr');
		var topheight = $('#record-gallery .gallery-contnr').offset().top;
	}else if($('#img_listing.img-listing').is(':visible')){  //img lib page
		$element = $('#img_listing.img-listing');
		var topheight = $('#img_listing.img-listing').offset().top - 80;
	}else if($('#xrRecInsertForm').is(':visible') && !$('#exercisecreate-modal').is(':visible')){ //exercise create page
		$element = $('#xrRecInsertForm .tab-content');
		var topheight = $('#xrRecInsertForm .tab-content').offset().top - 30;
	}else if(!$('div.modal div.scrollablepadd ul.sTreeBase').length && $('.scrollablepadd ul.sTreeBase').is(':visible')){
		$element = $('.scrollablepadd ul.sTreeBase');
		var topheight = $('.scrollablepadd ul.sTreeBase').offset().top;
	}else if($('div.modal div.scrollablepadd ul.sTreeBase').closest('.modal-body').length){
	 	$element = $('div.modal div.scrollablepadd ul.sTreeBase');
		if($('div.modal div#expended').is(':visible'))
			var topheight = $('div.modal div#expended').innerHeight() + 360;
		else
			var topheight = 360;
	}
	if($element!=''){
		if($('.scrollablepadd ul.sTreeBase').closest('.modal-body').length)
			var heightlimit = topheight + 80;
		else
			var heightlimit = topheight + 100;
		if($($element).hasClass('sTreeBase')){
			$element.css({
				'min-height': 'calc(100vh - '+heightlimit+'px)',
				'background-color': '#eee'
			});
		}
		$element.css({
			'max-height': 'calc(100vh - '+heightlimit+'px)',
			'overflow-y': 'auto',
			'overflow-x': 'hidden',
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
	var $divModal = $(this).find('div.vertical-alignment-helper');
	var topheightnavbar = $('nav.navbar').innerHeight() - 10;
	$divModal.css({'padding-top' : topheightnavbar+"px"});
});
$('.modal').on('hidden.bs.modal', function() {
	setDynamicHeight();
});
function showXrVariables(){
	if($('div.hideadvance').hasClass('hide'))
		$('div.hideadvance').removeClass('hide');
	$('div.hideadvance').show();
}
function hideXrVariables(){
	if($('div.hideadvance').hasClass('hide'))
		$('div.hideadvance').removeClass('hide');
	$('div.hideadvance').hide();
}
$(document).on('click','a.confirm, button.confirm', function(e){
	e.preventDefault();
	e.stopImmediatePropagation();
	$('div.errormsg').html('');
	if (e.handled !== true) {
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
							eval(onclickFun);
							setTimeout(closeModelwindowCustom(modalType),100);
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
					eval(onclickFun);
					setTimeout(closeModelwindowCustom(modalType),100);
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

$(document).ready(function(){
	$('#errorMessage-modal').on('hidden.bs.modal', function(){
		$('#errorMessage-modal #validation-errors').html('');
	})
	// Chosen touch support.
    if ($('.chosen-container').length > 0) {
      $('.chosen-container').on('touchstart', function(e){
        e.stopPropagation(); e.preventDefault();
        // Trigger the mousedown event.
        $(this).trigger('mousedown');
      });
    }
});
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
function getTitlestrip(elem){
	var title = $(elem).html();
	if(typeof title != 'undefined')
		return title.replace(/'/g, "\\'");
}
$(document).ready(function(){
	$('button.af_filter_btn').click(function(){
		if($(this).attr('aria-expanded') == 'false' || typeof($(this).attr('aria-expanded')) == 'undefined')
			$('div.activityinner').css('height','218px');
		else
			$('div.activityinner').css('height','262px');
	});
});

/*collapse on scroll -workout*/
var ts;
$(document).bind('touchstart', 'form#createNewworkout .modal-body form#form-workoutrec', function (e){
	ts = e.originalEvent.touches[0].clientY;
});
$(document).bind('touchend', 'form#createNewworkout .modal-body form#form-workoutrec', function (e){
	if($('form#createNewworkout .modal-body').is(':visible')){
		getTouchPositionAndToggle(e);
	}else if ($('form#form-workoutrec').is(':visible')) {
		getTouchPositionAndToggle(e);
	}
});
function getTouchPositionAndToggle(e){
	if($(e.target).parent().attr('id') == 'scrollablediv-len' || $(e.target).hasClass('sTreeBase') || $(e.target).closest('ul').hasClass('sTreeBase') 
		|| $(e.target).closest('div.modal-header').length || $(e.target).closest('div.modal-footer').length){
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

function resend_link(email,site){
	$.ajax({  
		 type: "POST",
		 data:{
			'email':email,'site':site
		 },
		 url : siteUrl+"user/resnedactivation",
		 success: function(dataString) {
			
			  if (dataString==true) {
					$(".act_resend").remove();
					$("#act_re").html("<p style='color:green'>Account activation sent '"+email+"', kindly check inbox and activate account</p>");
			  }
		 }  
	});
}

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
		var oldxrid = $("div#view_more").attr("data-oldxrid");
		var x = 1,
			loopcnt = getAjaxSendCount();
		while (x <= loopcnt) {
			sendAjax = false;
			setTimeout(function() {
				if ($("#relatedexc.modal-body").is(":visible") && $("#relatedexc.modal-body").find(".itemxr").length && getBrowserZoomLevel() < 100) {
					getRelatedRecordsMore(xrid, oldxrid, start, limit, '');
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
			sendAjax = false;
			setTimeout(function() {
				getRelatedRecordsMore(xrid, oldxrid, start, limit, ev);
			}, 200);
		}
	}
});
function pad(n) {
	return n < 10 ? '0' + n : n
}
function profileChange(method){
	$('#userModalActions').html();
	$.ajax({
		url : siteUrl_frontend+"search/getmodelTemplate",
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
function notifyUpdate(selector){
	console.log($(selector).is(":checked"));
	type = $(selector).attr('name');
	$.ajax({
		url : siteUrl_frontend+"ajax/updateHide/",
		dataType : 'json',
		async : false,
		data : {
			action  : 'updateTour',
			type    : type,
			checkedFlag : $(selector).is(":checked")
		},
		success : function(donnee){
			$('div.in .confirm[data-notename="'+type+'"]').each(function() {
				$(this).attr('data-allow',($(selector).is(":checked") == true ? 'false' : 'true'));
			});
			$('.confirm[data-notename="'+type+'"]').each(function() {
				$(this).attr('data-allow',($(selector).is(":checked") == true ? 'false' : 'true'));
			});
		}
	});
}

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
				if ($(input).val() != '' && parseFloat($(input).val()) != 0) {
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

function convArrToObj(array) {
   var thisEleObj = new Object();
   if (typeof array == "object") {
      for (var i in array) {
         var thisEle = convArrToObj(array[i]);
         thisEleObj[i] = thisEle;
      }
   } else {
      thisEleObj = array;
   }
   return thisEleObj;
}

(function($) {
   $.fn.parentNth = function(n) {
      var el = $(this);
      for (var i = 0; i < n; i++)
         el = el.parent();
      return el;
   };
})(jQuery);

function getRelatedRecords(xrid, EditFlag, oldxrId, order){
   modalName = 'myOptionsModalExerciseRecord';
   if($('div#exerciselib-model').length)
      EditFlag = true;
   $('#'+modalName).html();
   $.ajax({
      url : siteUrl_frontend+"search/getmodelTemplate",
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
function getRelatedRecordsMore(xrid, oldxrid, order, start, lim, event) {
   if (xrid == '' || xrid == undefined || oldxrid == '' || oldxrid == undefined) {
      return false;
   }
   var EditFlag = $("#relatedexc").attr('data-id');
   $.ajax({
      url: siteUrl_frontend + "search/getRelatedXrRecordsMore",
      data: {
         id: xrid,
         start: start,
         lim: lim
      },
      beforeSend: function() {
         setTimeout(function() {
            $('#loading-indicator').show()
         }, 10);
      },
      success: function(content) {
         var JSONArray = $.parseJSON(content);
         var response = '';
         if (JSONArray.length > 0) {
            start = parseInt(start) + parseInt(lim);
            for (var i = 0; i < JSONArray.length; i++) {
               response += '<div data-type="' + JSONArray[i].xrtype + '" class="row itemxr" ' + (i == '9' ? 'id="view_more" data-order="' + order + '" data-start="' + start + '" data-limit="' + lim + '" data-xrid="' + xrid + '" data-oldxrid="' + oldxrid + '"' : '') + '><div class="mobpadding"><div class="border full">';
               response += '<div class="col-xs-3 ">';
               if (JSONArray[i].img) {
                  response += '<img width="60px;" id="exerciselibimg" class="img-thumbnail" style="cursor:pointer;';
                  response += '" src=\'' + JSONArray[i].img + '\'';
                  response += '/>';
               } else {
                  response += '<i style="font-size:50px;" class="fa fa-file-image-o datacol"></i>';
               }
               response += '</div>';
               response += '<div class="col-xs-7" style="border-right:1px solid #eee;padding-left:0px;"><b>' + JSONArray[i].title + '</b><div class="item-info">' + JSONArray[i].xrtype + '</div></div>';
               response += '<div class="col-xs-2 aligncenter"><a href="javascript:void(0);" ' + (EditFlag ? 'onclick="insertFromRelatedToXrSet(' + "'" + oldxrid + "','" + JSONArray[i].xr_id + "','" + order + "'" + ');"' : 'onclick="return false"') + '><i class="fa fa-sign-in iconsize ' + (EditFlag ? '' : 'datacol') + '"></i></a></div>';
               response += '</div>';
               response += '</div><input type="hidden" value="' + (JSONArray[i].img != '' ? JSONArray[i].img : '') + '" name="popup_hidden_exerciseset_img' + JSONArray[i].xr_id + '" id="popup_hidden_exerciseset_image_opt' + JSONArray[i].xr_id + '"/><input type="hidden" value="' + JSONArray[i].title + '" name="popup_hidden_exerciseset_title' + JSONArray[i].xr_id + '" id="popup_hidden_exerciseset_title_opt' + JSONArray[i].xr_id + '"/></div>';
            }
            $("#view_more").removeAttr('data-start');
            $("#view_more").removeAttr('data-limit');
            $("#view_more").removeAttr('data-xrid');
            $("#view_more").removeAttr('data-oldxrid');
            $("#view_more").removeAttr('data-order');
            $("#view_more").attr('id', 'view_more_prev');
            $("#relatedexc").append(response);
            sendAjax = true;
         } else {
            $("#view_more").remove();
         }
         $('#loading-indicator').hide();
      }
   });
   $('#loading-indicator').hide();
}
function getTagOfRecord(xrId){
   $('#myOptionsModalExerciseRecord').html();
   $.ajax({
      url : siteUrl_frontend+"search/getmodelTemplate",
      data : {
         action : 'relatedRecords',
         method :  'tagRecord',
         id : xrId,
         modelType : 'myOptionsModalExerciseRecord',
         editFlag: true
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
		url : siteUrl_frontend+"search/getmodelTemplate",
		data : {
			action : 'relatedRecords',
			method : 'sequenceRecords',
			id : xrId,
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
		url : siteUrl_frontend+"search/getSequenceMore",
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
			url : siteUrl_frontend+"search/getmodelTemplate",
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
var getVidId = function(url) {
	var vidId;
	if(url.indexOf("youtube.com/watch?v=") !== -1) { //https://m.youtube.com/watch?v=e3S9KINoH2M
		vidId = url.substr(url.indexOf("youtube.com/watch?v=") + 20);
	} else if(url.indexOf("youtube.com/watch/?v=") !== -1) { //https://m.youtube.com/watch/?v=e3S9KINoH2M
		vidId = url.substr(url.indexOf("youtube.com/watch/?v=") + 21);
	} else if(url.indexOf("youtu.be") !== -1) {
		vidId = url.substr(url.indexOf("youtu.be") + 9);
	} else if(url.indexOf("www.youtube.com/embed/") !== -1) {
		vidId = url.substr(url.indexOf("www.youtube.com/embed/") + 22);
	} else if(url.indexOf("?v=") !== -1) { // http://m.youtube.com/?v=tbBTNCfe1Bc
		vidId = url.substr(url.indexOf("?v=")+3, 11);
	} else {
		console.warn("YouTubeUrlNormalize getVidId not a youTube Video: "+url);
		vidId = null;
	}
	if(vidId.indexOf("&") !== -1) {
		vidId = vidId.substr(0, vidId.indexOf("&") );
	}
	return vidId;
};

var YouTubeUrlNormalize = function(url) {
	var rtn = url;
	if(url) {
		var vidId = getVidId(url);
		if(vidId) {
			rtn = "https://www.youtube.com/embed/"+vidId;
		} else {
			rtn = url;
		}
	}
	return rtn;
};

YouTubeUrlNormalize.getThumbnail = function(url, num) {
	var rtn, vidId = getVidId(url);
	if(vidId) {
		if(!isNaN(num) && num <= 4 && num >= 0) {
			rtn = "http://img.youtube.com/vi/"+vidId+"/"+num+".jpg";
		} else {
			rtn = "http://img.youtube.com/vi/"+getVidId(url)+"/default.jpg";
		}
	} else {
		return null;
	}
	return rtn;
};

YouTubeUrlNormalize.getFullImage = function(url){
	var vidId = getVidId(url);
	if(vidId) {
		return "http://img.youtube.com/vi/"+vidId+"/0.jpg";
	} else {
		return null;
	}
};

if ( typeof exports !== "undefined" ) {
	module.exports = YouTubeUrlNormalize;
}
else if ( typeof define === "function" ) {
	define( function () {
		return YouTubeUrlNormalize;
	});
}
else {
	window.YouTubeUrlNormalize = YouTubeUrlNormalize;
}
$(document).on('submit', 'form#tagRecord', function(ev){
	ev.preventDefault();
	var unitid = $(this).find('input#unit_id').val();
	var tagsitem = $('input#tag_input').val();
	$.ajax({
		url : siteUrl_frontend+"ajax/addXrSetTag",
		method: 'post',
		type: 'json',
		data: {
			'unit_id': unitid,
			'xrtag-input': tagsitem
		},
		success: function(response){
			$('#myOptionsModalExerciseRecord').modal('hide');
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