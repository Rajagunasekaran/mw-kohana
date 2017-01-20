var checked= 1;
$(document).on('click', '.checkboxcolor label input.checkhiddenpopup[type="checkbox"]', function() {
	console.log($(this).attr('data-check'));
	if($(this).prop("checked") == true && typeof($(this).attr('data-check')) == 'undefined'){
		$(this).attr('data-check',checked);
		checked++;
	}
});
function changeCalenderView(options){
	var calendar = $('#calendar').calendar(options);
	$('.btn-group button[data-calendar-nav]').each(function() {
		var $this = $(this);
		$this.click(function() {
			calendar.navigate($this.data('calendar-nav'));
		});
	});

	$('.btn-group button[data-calendar-view]').each(function() {
		var $this = $(this);
		$this.click(function() {
			calendar.view($this.data('calendar-view'));
		});
	});
	setTimeout(function() {
		var downbox = $(document.createElement('div')).attr('id', 'cal-day-tick').attr('class', 'cal-day-tick-today').html('<i class="icon-chevron-down fa fa-chevron-down"></i>');
		var caldate1 = $('#getdate').val();
		var calcurdate1 = $('div.cal-day-today span').attr('data-cal-date');
		if(caldate1 == calcurdate1){
			$("div.cal-day-today").addClass("cal-day-today-auto");
			downbox.show().appendTo($("div.cal-day-today"));
			$('.cal-day-tick-today').trigger("click");
		}else{
			$('span.pull-right[data-cal-date="'+caldate1+'"]').parent().addClass("cal-day-today-auto");
			downbox.show().appendTo($('span.pull-right[data-cal-date="'+caldate1+'"]').parent());
			$('span.pull-right[data-cal-date="'+caldate1+'"]').parent().trigger("click");
			$('span.pull-right[data-cal-date="'+caldate1+'"]').parent().addClass("greyclrcal");
		}
	},100);
}
$(document).ready(function () {
	 resizeDiv();
	 $(".calendardate").click(function(){
		resizeDiv();
	 });
	 $(".calerdarprev").click(function(){
		resizeDiv();
	 });
	 $(".calerdarnext").click(function(){
		resizeDiv();
	 });
});
window.onresize = function(event) {
   resizeDiv();
}

function resizeDiv() {
	if ($( window ).width() < 700) {
		$('#calendar .cal-row-head .cal-cell1:nth-child(1)').text('Sun');
		$('#calendar .cal-row-head .cal-cell1:nth-child(2)').text('Mon');
		$('#calendar .cal-row-head .cal-cell1:nth-child(3)').text('Tue');
		$('#calendar .cal-row-head .cal-cell1:nth-child(4)').text('Wed');
		$('#calendar .cal-row-head .cal-cell1:nth-child(5)').text('Thu');
		$('#calendar .cal-row-head .cal-cell1:nth-child(6)').text('Fri');
		$('#calendar .cal-row-head .cal-cell1:nth-child(7)').text('Sat');
	}
	else {
		$('#calendar .cal-row-head .cal-cell1:nth-child(1)').text('Sunday');
		$('#calendar .cal-row-head .cal-cell1:nth-child(2)').text('Monday');
		$('#calendar .cal-row-head .cal-cell1:nth-child(3)').text('Tuesday');
		$('#calendar .cal-row-head .cal-cell1:nth-child(4)').text('Wednesday');
		$('#calendar .cal-row-head .cal-cell1:nth-child(5)').text('Thursday');
		$('#calendar .cal-row-head .cal-cell1:nth-child(6)').text('Friday');
		$('#calendar .cal-row-head .cal-cell1:nth-child(7)').text('Saturday');
	}
}
function getAssignedWorkoutsByajax(){
	if($('#search-workplan').length){
		$('#search-workplan').autocomplete({
			source : function(requete, reponse){ 
				$.ajax({
					url : siteUrl+"search/getajax/",
					dataType : 'json',
					data : {
						action : 'assingedworkoutplan',
						title : $('#search-workplan').val(),
						maxRows : 5
					},
					success : function(donnee){
						if(donnee){
							reponse($.map(donnee, function(item){
								return {
									url: item.weburl,
									titre: item.titre,
									color: item.color
								}
							}));
						}

					}
				});
			},

			select: function( event, ui ) {
				window.location = ui.item.weburl;
			}
		}).data( "ui-autocomplete" )._renderItem = function( ul, item ) {
			if(item.color.length)
				return $( "<li>" ).append( "<a href='"+item.url+"' target='_parent'><div class='col-xl-6 colorchoosen'><i class='glyphicon' style='"+item.color+"'></i></div><div class='col-xl-6'>" + item.titre + "</div></a>" ).appendTo( ul );
			else
				return $( "<li>" ).append( "<a href='"+item.url+"' target='_parent'>" + item.titre + "</div></a>" ).appendTo( ul );
		 };
	}
}
function isNumberKey(evt, act) {
	var keyCode = (evt.which?evt.which:(evt.keyCode?evt.keyCode:0));
	if(act && act == 'codePromo')
		if ((keyCode == 44) || (keyCode == 46)) return false;
	if(act && act == 'trackStats' && keyCode > 36 && keyCode < 41) return true;
	if ((keyCode == 8) || (keyCode == 9) || (keyCode == 46)) return true;
	if ((keyCode < 48) || (keyCode > 57) || (keyCode == 46) || (keyCode == 34) || (keyCode == 37)) return false;
	return true;
}

function strip_tags(input, allowed) {
	allowed = (((allowed || '') + '')
	.toLowerCase()
	.match(/<[a-z][a-z0-9]*>/g) || [])
	.join(''); // making sure the allowed arg is a string containing only tags in lowercase (<a><b><c>)
	var tags = /<\/?([a-z][a-z0-9]*)\b[^>]*>/gi,
	commentsAndPhpTags = /<!--[\s\S]*?-->|<\?(?:php)?[\s\S]*?\?>/gi;
	return input.replace(commentsAndPhpTags, '')
	.replace(tags, function($0, $1) {
	  return allowed.indexOf('<' + $1.toLowerCase() + '>') > -1 ? $0 : '';
	});
}

function confirmAssignDate(date){
	closeModelwindow('FolderModal');
	/*$('#FolderModal').html();
	$.ajax({
		url : siteUrl+"search/getmodelTemplate/",
		data : {
			action : 'confirmAssignDate',
			method :  'action',
			id : 0,
			foldid : 0,
			date   : date,
			modelType : 'FolderModal',
		},
		success : function(content){
			$('#FolderModal').html(content);
			$('#FolderModal').modal();
		}
	});*/
}
function confirmDateOption(date){
	$('#FolderModal').html();
	$.ajax({
		url : siteUrl+"search/getmodelTemplate/",
		data : {
			action : 'confirmAssignDate',
			method :  'assign',
			id : 0,
			foldid : 0,
			date   : date,
			modelType : 'FolderModal'
		},
		success : function(content){
			$('#FolderModal').html(content);
			$('#FolderModal').modal();
		}
	});
}

function duplicateAssignDate(assignId,fid,date){
	$('#FolderModal').html();
	$.ajax({
		url : siteUrl+"search/getmodelTemplate/",
		data : {
			action : 'confirmAssignDate',
			method :  'addNewDate',
			id : 0,
			foldid : 0,
			assignid:assignId,
			date   : date,
			modelType :'FolderModal'
		},
		success : function(content){
			$('#FolderModal').html(content);
			$('#FolderModal').modal();
		}
	});
}
function getTemplateOfReAssignAction(wkoutId, wkoutAssignId, Assigndate){
	$('#myModal').html();
	$.ajax({
		url : siteUrl+"search/getmodelTemplate/",
		data : {
			action : 'reassignOptions',
			method :  'options',
			id : wkoutId,
			foldid : '',
			assignid : wkoutAssignId,
			type	: 'assigned',
			date : Assigndate
		},
		success : function(content){
			$('#myModal').html(content);
			$('#myModal').modal();
		}
	});
}
function getTemplateOfNewAssignAction(date){
	$('#FolderModalpopupOption').html();
	$.ajax({
		url : siteUrl+"search/getmodelTemplate/",
		data : {
			action : 'addAssignWorkouts',
			method :  'addNewDate',
			id : 0,
			foldid : 0,
			date   : date,
			type : 'wkoutAssignCal',
			modelType : "FolderModalpopupOption"
		},
		success : function(content){
			$('#FolderModalpopupOption').html(content);
			$('#FolderModalpopupOption').modal();
		}
	});
}
function addAssignWorkoutsByDate(date,folderid, wkoutid, method){
	var type = '';
	if(method !='dulicateWkoutLog'){
		if(method.trim() =='assigned-duplicate'){
			var type = 'duplicate';
			method   = 'assigned';
		}else if(method.trim() == 'logged-create'){
			var type = 'logged';
			method   = '';
		}else if(method.trim() == 'wkout' || method.trim() == 'workout'){
			var type = 'workout';
			method   = '';
		}else{
			if(method.trim() !='' && method.indexOf('-loggedwkout') >= 0){
				var method = method.replace('-loggedwkout','');
				var type = 'loggedwkout';
			}else if(method.trim() !='' && method.indexOf('-logged') >= 0){
				var method = method.replace('-logged','');
				var type = 'logged';
			}else if(method.trim() !='' && method.indexOf('-workout') >= 0){
				var method = method.replace('-workout','');
				var type = 'workout';
			}else if(method.trim() !='' && method.indexOf('-assign') >= 0){
				var method = method.replace('-assign','');
				var type = '';
			}
		}
		$('#FolderModal').html();
		$.ajax({
			url : siteUrl+"search/getmodelTemplate/",
			data : {
				action : 'addAssignWorkouts',
				method :  method,
				id : wkoutid,
				foldid : folderid,
				date   : date,
				modelType : "FolderModal",
				type : type
			},
			success : function(content){
				$('#FolderModal').html(content);
				$('#FolderModal').modal();
			}
		});
	}else{
		$('#FolderModal').html();
		$.ajax({
		  url: siteUrl + "search/getmodelTemplate",
		  data: {
			 action: 'addAssignWorkouts',
			 method: 'wkoutLogCal',
			 id: wkoutid,
			 date: $('input[id="selected_date"].min-date').val(),
			 modelType : "FolderModal",
			 type: 'dulicateWkoutLog'
		  },
		  success: function(content) {
			 $('#FolderModal').html(content);
			 $('#FolderModal').modal();
		  }
		});
	}
}
function addAssignWorkouts(wkoutAssignid,wkoutId,date){
	$('#FolderModal').html();
	$.ajax({
		url : siteUrl+"search/getmodelTemplate/",
		data : {
			action : 'addAssignWorkouts',
			method :  'action',
			id : wkoutId,
			assignid :wkoutAssignid,
			date   : date,
			type   : 'wkoutAssignCal',
			modelType : "FolderModal"
		},
		success : function(content){
			$('#FolderModal').html(content);
			$('#FolderModal').modal();
		}
	});
}
function addAssignWorkoutlogs(date){
	closeModelwindow('FolderModal');
	/*$('#FolderModal').html();
	$.ajax({
		url : siteUrl+"search/getmodelTemplate/",
		data : {
			action : 'confirmAssignDate',
			method :  'action', 
			id : 0,
			foldid : 0,
			date   : date,
			modelType : "FolderModal",
			type : '-loggedwkout'
		},
		success : function(content){
			$('#FolderModal').html(content);
			$('#FolderModal').modal();
		}
	});*/
}
function getworkoutpreview(wkoutId,editflag){
	$('#myModal').html();
	$.ajax({
		url : siteUrl+"search/getmodelTemplate/",
		data : {
			action : 'previewworkout',
			method :  'preview',
			id : wkoutId,
			foldid : '0',
			type : 'assigned',
			editFlag: editflag
		},
		success : function(content){
			$('#myModal').html(content);
			$('#myModal').modal();
		}
	});
}
function getExercisepreviewOfDay(exerciseId, wkoutId){
	$('#myOptionsModalExerciseRecord').html();
	$.ajax({
		url : siteUrl+"search/getmodelTemplate/",
		data : {
			action : 'previewExerciseOfDay',
			method :  'preview',
			id : exerciseId,
			foldid : wkoutId,
			type : 'assigned',
			modelType : "myOptionsModalExerciseRecord"
		},
		success : function(content){
			$('#myOptionsModalExerciseRecord').html(content);
			$('#myOptionsModalExerciseRecord').modal();
		}
	});
}
function getExerciseSetpreview(exerciseSetId, wkoutId){
	$('#myOptionsModalExerciseRecord').html();
	$.ajax({
		url : siteUrl+"search/getmodelTemplate/",
		data : {
			action : 'previewExercise',
			method :  'preview',
			id : exerciseSetId,
			foldid : wkoutId
		},
		success : function(content){
			$('#myOptionsModalExerciseRecord').html(content);
			$('#myOptionsModalExerciseRecord').modal();
		}
	});
}
function getTemplateOfAssignAction(wkoutId, wkoutAssignId , assignedDate , assignedby,title, markedState){
	$('#myModal').html();
	$.ajax({
		url : siteUrl+"search/getmodelTemplate/",
		data : {
			action : 'assignOptions',
			method :  'options',
			id : wkoutId,
			foldid : '',
			assignid : wkoutAssignId,
			ownWkFlag : assignedby,
			type	: 'assignedCal',
			date   : assignedDate,
			title   : title,
			editFlag : markedState,
		},
		success : function(content){
			$('#myModal').html(content);
			$('#myModal').modal();
		}
	});
}
function getAssignedwrkoutpreview(wkoutId, wkoutAssignId , assignedDate , assignedby){
	$('#myModalpreV').html();
	$.ajax({
		url : siteUrl+"search/getmodelTemplate/",
		data : {
			action : 'previewworkout',
			method :  'preview',
			id : '',
			foldid : '',
			assignid : wkoutAssignId,
			ownWkFlag : assignedby,
			type	: 'assigned',
			date   : assignedDate
		},
		success : function(content){
			$('#myModalpreV').html(content);
			$('#myModalpreV').modal();
		}
	});
}
function getLoggedwrkoutpreview(wkoutId, wkoutLogId , assignedDate , assignedby){
	$('#myModalpreV').html();
	$.ajax({
		url : siteUrl+"search/getmodelTemplate/",
		data : {
			action : 'previewworkout',
			method :  'preview',
			id : wkoutId,
			foldid : '',
			logid : wkoutLogId,
			ownWkFlag : assignedby,
			type	: 'logged',
			date   : assignedDate
		},
		success : function(content){
			$('#myModalpreV').html(content);
			$('#myModalpreV').modal();
		}
	});
}
function getExerciseSetpreview(exerciseSetId,wkoutAssignId, wkoutId, assignedby, modifiedby, selector){
	if(!$(selector).attr("disabled")){
		$('#myModal').html();
		$.ajax({
			url : siteUrl+"search/getmodelTemplate/",
			data : {
				action : 'previewExercise',
				method :  'enablepreview',
				id : exerciseSetId,
				foldid : wkoutId,
				assignid : wkoutAssignId,
				ownWkFlag : assignedby,
				ownEditWkFlag : modifiedby,
				type	: 'assigned'
			},
			success : function(content){
				$('#myModal').html(content);
				$('#myModal').modal();
			}
		});
	}
}
function getExerciseSetpreviewlog(exerciseSetId,wkoutlogId, wkoutId, assignedby, modifiedby, selector){
	if(!$(selector).attr("disabled")){
		$('#myModal').html();
		$.ajax({
			url : siteUrl+"search/getmodelTemplate/",
			data : {
				action : 'previewExercise',
				method :  'enablepreview',
				id : exerciseSetId,
				foldid : wkoutId,
				logid : wkoutlogId,
				ownWkFlag : assignedby,
				ownEditWkFlag : modifiedby,
				type	: 'logged'
			},
			success : function(content){
				$('#myModal').html(content);
				$('#myModal').modal();
			}
		});
	}
}
function getXrImageRecords(xrid){
	modalName = 'myOptionsModalExerciseRecord';
	$('#'+modalName).html();
	$.ajax({
		url : siteUrl+"search/getmodelTemplate/",
		data : {
			action : 'relatedRecords',
			method :  'previewimage',
			id 	   : xrid,
			modelType : modalName,
		},
		success : function(content){
			$('#'+modalName).html(content);
			$('#'+modalName).modal();
		}
	});
}
function getXrSeqImgPreview(xrid,seqId){
	modalName = 'myOptionsModalExerciseRecord_more';
	$('#'+modalName).html();
	$.ajax({
		url : siteUrl+"search/getmodelTemplate",
		data : {
			action : 'relatedRecords',
			method :  'previewimageSeq',
			id 	   : xrid,
			foldid	   : seqId,
			modelType : modalName,
		},
		success : function(content){
			$('#'+modalName).html(content);
			$('#'+modalName).modal();
		}
	});
}
function getTemplateOfExerciseRecordAction(exerciseSetId, selector){
	var modal = 'myModal';
	editFlag  = '';
	xrsetId = '';
	if(selector != ''){
		var xrsetId = $(selector).parents('li').attr('data-id').replace('new_','');
		var modal = 'myOptionsModalExerciseRecord';
		editFlag = true;
	}
	$('#'+modal).html();
	$.ajax({
		url : siteUrl+"search/getmodelTemplate/",
		data : {
			action : 'exerciserecordaction',
			method :  'action', 
			id : '',
			foldid : exerciseSetId,
			xrid   : xrsetId,
			modelType : modal,
			editFlag : editFlag,
			allowTag : true
		},
		success : function(content){
			$('#'+modal).html(content);
			$('#'+modal).modal();
		}
	});
}
function insertTagOfRecord(xrId){
	$('#userModal').html();
	$.ajax({
		url : siteUrl+"search/getmodelTemplate",
		data : {
			action : 'relatedRecords',
			method :  'tagRecord',
			id    : xrId,
			modelType : 'userModal',
			editFlag : true
		},
		success : function(content){
			$('#userModal').html(content);
			$('#userModal').modal();
		}
	});
}
function createAssignWorkoutsByDate(){

}
function openDateCalender(date,flag){
	if(flag !=0){
		$('.second-col').addClass('hide');
		$('.first-col').removeClass('hide');
	}else{
		$('.first-col').addClass('hide');
		$('.second-col').removeClass('hide');
	}
}
function getWorkoutColorModel(){
	var dataval = {'wkout_title':$('#wkout_title').val(),'color_title' : $('#wrkoutcolortext').attr('class').split(' ').pop(),'wrkoutcolor' : $('#wrkoutcolor').val()};
	$('#FolderModalpopupOption').html();
	$.ajax({
		url : siteUrl+"search/getmodelTemplate/",
		data : {
			action : 'workoutColor',
			method :  '',
			id	   : 0,
			foldid : 0,
			dataval: dataval,
			modelType: 'FolderModalpopupOption',
			type  : 'wkoutAssign',
			date : $('#selected_date_hidden').val(),
		},
		success : function(content){
			$('#FolderModalpopupOption').html(content);
			$('#FolderModalpopupOption').modal();
		}
	});
}
function createNewworkout() {
   $('#FolderModal').html();
   $.ajax({
      url: siteUrl + "search/getmodelTemplate",
      data: {
         action: 'createNewworkout',
         method: 'addworkout',
         id: 0,
         foldid: 0,
		 type : 'workout'
      },
      success: function(content) {
         $('#FolderModal').html(content);
         $('#FolderModal').modal();
      }
   });
}
function addNewworkoutAssign(date,foldid,fid,type){
	type = type.replace('-logged','');
	$("#FolderModal").html();
	$.ajax({	
		url : siteUrl+"search/getmodelTemplate/",
		data : {
			action : "createNewworkout",
			method :  "addworkoutAssign",
			id : fid,
			foldid : foldid,
			date:date,
			modelType : "FolderModal",
			type : type
		},
		success : function(content){
			$("#FolderModal").html(content);
			$("#FolderModal").modal();
		}
	});
}
function createNewworkoutAssign(date,foldid,fid,type){
	$("#FolderModal").html();
	$.ajax({	
		url : siteUrl+"search/getmodelTemplate/",
		data : {
			action : "previewworkout",
			method :  "addworkoutAssign",
			id : fid,
			foldid : foldid,
			date:date,
			modelType : "FolderModal",
			type : type
		},
		success : function(content){
			$("#FolderModal").html(content);
			$("#FolderModal").modal();
		}
	});
}
function getExerciseSetpreviewByType(exerciseSetId, wkoutId, type){
	$('#FolderModalpopup').html();
	$.ajax({
		url : siteUrl+"search/getmodelTemplate/",
		data : {
			action : 'previewExercise',
			method :  'preview',
			id : exerciseSetId,
			foldid : wkoutId,
			type : type
		},
		success : function(content){
			$('#FolderModalpopup').html(content);
			$('#FolderModalpopup').modal();
		}
	});
}
function getTemplateOfExerciseRecordActionByType(exerciseSetId){
	$('#FolderModalpopup').html();
	$.ajax({
		url : siteUrl+"search/getmodelTemplate/",
		data : {
			action : 'exerciserecordaction',
			method :  'action', 
			id : '',
			foldid : exerciseSetId,
			modelType : 'FolderModalpopup',
			allowTag: true
		},
		success : function(content){
			$('#FolderModalpopup').html(content);
			$('#FolderModalpopup').modal();
		}
	});
}
function selectcolor(selector){
	selectedclr = $(selector).attr('class').split(' ').pop();
	$('.colorcircle').removeClass('activecircle');
	selectedid = $(selector,'.choosenclr').text();
	$(selector).addClass('activecircle');
	selectedclrnew = $($('#wrkoutcolortext')).attr('class').split(' ').pop();
	if(selectedclr != 'activecircle')
		$('#wrkoutcolortext').removeClass(selectedclrnew).addClass(selectedclr);
	$('#wrkoutcolor').val(selectedid);
	$('.navbar-collapse-colors').removeClass('in');
}
function fixToolTipColor(){
	//grab the bg color from the tooltip content - set top border of pointer to same
	$('.ui-tooltip-pointer-down-inner').each(function(){
		var bWidth = $('.ui-tooltip-pointer-down-inner').css('borderTopWidth');
		var bColor = $(this).parents('.ui-slider-tooltip').css('backgroundColor')
		$(this).css('border-top', bWidth+' solid '+bColor);
	});	
}
(function($) {
	$.fn.parentNth = function(n) {
		var el = $(this);
		for(var i = 0; i < n; i++)
			el = el.parent();
		
		return el;
	};
})(jQuery);
function getInputDetailsByForm(dataForm, img_url, elem ,type){
	var dataval = [];
	dataval['goal_time_hh'] = '';
	dataval['goal_time_mm'] = '';
	dataval['goal_time_ss'] = '';
	dataval['goal_rest_mm'] = '';
	dataval['goal_rest_ss'] = '';
	dataval['img_url'] 		= img_url;
	dataval['goal_id'] 		= elem;
	if(type == 1)
	var requArr  = {'exercise_title':"goal_title",'exercise_unit':"goal_unit_id",'exercise_resistance':"goal_resist",'exercise_unit_resistance':"goal_resist_id",'exercise_repetitions':"goal_reps",'exercise_time':"goal_time",'exercise_distance':"goal_dist",'exercise_unit_distance':"goal_dist_id",'exercise_rate':"goal_rate",'exercise_unit_rate':"goal_rate_id",'exercise_innerdrive':"goal_int_id",'exercise_angle':"goal_angle",'exercise_unit_angle':"goal_angle_id",'exercise_rest':"goal_rest",'exercise_remark':"goal_remarks",'primary_time':"primary_time",'primary_dist':"primary_dist",'primary_reps':"primary_reps",'primary_resist':"primary_resist",'primary_rate':"primary_rate",'primary_angle':"primary_angle",'primary_rest':"primary_rest",'primary_int':"primary_int"};
	else
	var requArr  = {'exercise_title_':"goal_title",'exercise_unit_':"goal_unit_id",'exercise_resistance_':"goal_resist",'exercise_unit_resistance_':"goal_resist_id",'exercise_repetitions_':"goal_reps",'exercise_time_':"goal_time",'exercise_distance_':"goal_dist",'exercise_unit_distance_':"goal_dist_id",'exercise_rate_':"goal_rate",'exercise_unit_rate_':"goal_rate_id",'exercise_innerdrive_':"goal_int_id",'exercise_angle_':"goal_angle",'exercise_unit_angle_':"goal_angle_id",'exercise_rest_':"goal_rest",'exercise_remark_':"goal_remarks",'primary_time':"primary_time",'primary_dist':"primary_dist",'primary_reps':"primary_reps",'primary_resist':"primary_resist",'primary_rate':"primary_rate",'primary_angle':"primary_angle",'primary_rest':"primary_rest",'primary_int':"primary_int"};
	
	$(dataForm).each(function(i, field){
		for (var key in requArr) {
			if(field.name.indexOf(key)>= 0){
				if(requArr[key] == 'goal_time'){
					inputTimeArr = field.value.split(":");
					dataval['goal_time_hh'] = inputTimeArr[0];
					dataval['goal_time_mm'] = inputTimeArr[1];
					dataval['goal_time_ss'] = inputTimeArr[2];
				}else if(requArr[key] == 'goal_rest'){
					inputRestArr = field.value.split(":");
					dataval['goal_rest_mm'] = inputRestArr[0];
					dataval['goal_rest_ss'] = inputRestArr[1];
				}else{
					dataval[requArr[key]] = field.value;
				}
				delete requArr[key];
			}
		}
	});
	return convArrToObj(dataval);
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
function addDatetoAssign(date,ModelType){
	var daterel = date;
	date = date.replace(' ','-');
	date = date.replace(' ','-');
	var title =$('#wkout_title').val();
	title = title.replace(date,'');
	formdata = $('form#addAssignWorkouts').serializeArray();
	$(formdata).each(function(i, field){
		if(field.name == 'selected_date'){
			dateval = field.value;
			dateval = dateval.replace(' ','-');
			dateval = dateval.replace(' ','-');
			$('#wkout_title').val(title+' '+dateval);
			$('.wkout_title').html('<b>'+title+' '+dateval+'</b>');
			$('#'+field.name+'_hidden_text').text(field.value);
			$('#'+field.name+'_hidden').val(field.value);
		}
	});
	closeModelwindow(ModelType);
}

function editWorkoutRecord(elem,method){
	$('div.createworkout div.border').removeClass('new-item');
	var addOptions = '';
	if(method.indexOf('#') >= 0){
		methodArr 	= method.split("#");
		addOptions 	= methodArr[1];
		method = method.replace('#'+addOptions,'');
	}
	$('#FolderModalpopupOption').html('');
	var goalOrder = '';
	var datavaljson = '';
	elem = elem.replace('new_','');
	if($('div#itemsetnew_'+elem).length){
		var goal_id = img_url = '';
		if($('div#itemsetnew_'+elem+" .activelinkpopup").attr("disabled"))
			return false;
		var goalOrder = elem.split('_')[1];
		var dataForm = $('div#itemsetnew_'+elem+' input').serializeArray();
		img_url = '';
		if($('div#itemsetnew_'+elem+' .navimage img').length){
			if($('div#itemsetnew_'+elem+' .navimage img').attr('src') !=''){
				var img_url	= $('div#itemsetnew_'+elem+' .navimage img').attr('src').replace(siteUrl,'');
				img_url		= img_url.replace('../../../','');
			}
		}
		elem	  = '';
	}
	var datavaljson = getInputDetailsByForm(dataForm, img_url, goal_id , 1);
	$.ajax({
		url : siteUrl+"search/getmodelTemplate/",
		data : {
			action : 'createExercise',
			method :  'create',
			id : '0',
			foldid : '0',
			goalOrder : goalOrder,
			dataval : datavaljson,
			modelType : 'FolderModalpopupOption',
			addOptions:addOptions,
		},
		success : function(content){
			$('#FolderModalpopupOption').html(content);
			$('#FolderModalpopupOption').modal();
		}
	});
}
function getexercisesetTemplateAjaxEdit(wkoutId, exerciseId, type){
	$('div.createworkout div.border').removeClass('new-item');
	$('#myOptionsModalAjax').html();
	var dataForm = $('form#createExercise input').serializeArray();
	var goal_id		 = $('input#goal_id_hidden').val();
	var img_url  = '';
	if($('span#exerciselibimg img').length)
		var img_url	 = $('span#exerciselibimg img').attr('src');
	if(type != 'title')
		$('#exerciselib-template').remove();
	var datavaljson  = getInputDetailsByForm(dataForm, img_url, goal_id , 2);
	$.ajax({
		url : siteUrl+"search/getmodelTemplate/",
		data : {
			action : 'workoutExercise',
			method : type, 
			id	   : 0,
			foldid : 0,
			goalOrder : exerciseId,
			modelType : 'myOptionsModalAjax',
			
			dataval : datavaljson,
		},
		success : function(content){
			$('#myOptionsModalAjax').html(content);
			$('.checkboxdrag[type="checkbox"]').bootstrapSwitch('size','small');
			$('.checkboxdrag[type="checkbox"]').bootstrapSwitch('onText',' ');
			$('.checkboxdrag[type="checkbox"]').bootstrapSwitch('offText',' ');
			if(type == 'title')
				$('#myOptionsModalAjax').modal('hidecustom');
			else
				$('#myOptionsModalAjax').modal();
		}
	});
	if(type == 'title')
		createExerciseFromXrLibrary('');
}
function insertExtraToParentHidden(Model){
	if(Model.trim() == '')
		Model = 'myModal';
	$('.unitnormal').hide();
	$('.inputnormal').hide();
	if($('.error').hasClass('hide'))
		$('.error').addClass('hide')
	trueFlag = true;
	formdata = $('#workoutexercise').serializeArray();
	var ashstrick = primaryField = '';
	var oldData = '';
	if($('.checkboxdrag').is(':checked') && $('.checkboxdrag').attr('id') != 'exerciselib'){
		ashstrick = '<span class="ashstrick">*</span> ';
	}
	$(formdata).each(function(i, field){
		if(field.value == 'on' || field.value == 'off' || field.name.indexOf("_unit_") ==8){
			if(field.name != 'exerciselib' && field.name != 'innerdrive' && field.name.indexOf("_unit_") !=8){
				primaryField = field.name.replace('_hidden','');
				if(field.value.trim() == 'on'){
					ashstrick = '<span class="ashstrick">*</span> ';
				}else{
					ashstrick = '';
				}
			}
		}else{
			if(field.name != 'exercise_unit' && (field.value.trim() == 0 || field.value.trim() == '' || field.value.trim() == '00:00:00' || field.value.trim() == "00:00")){
				field.value = ashstrick = oldData = '';
			}
			var checkPresentdiv = false;
			if(field.name.indexOf("exercise_") != -1){
				var existUnit = field.name.replace('exercise_','exercise_unit_');
				checkPresentdiv = $('#workoutexercise select#'+existUnit).length;
			}
			if(!checkPresentdiv){
				// not present
				if(field.name == 'exercise_title'){
					if(field.value.trim() == ''){
						$('.inputnormal').show();
						$('.error').removeClass('hide');
						trueFlag = false;
						return false;
					}else{
						$('#exercise_title_hidden_text').text(field.value);
						$('#exercise_title_hidden').val(field.value);
					}
				}else if(field.name == 'exercise_unit'){
					$('#exerciselibimg').empty();
					if(field.value == 0){
						$('#exerciselibimg').append('<i class="fa fa-pencil-square datacol" style="font-size:50px;">');
					}else if(field.value > 0 && $('#exercise_unit_img').val() == ''){
						$('#exerciselibimg').append('<i class="fa fa-file-image-o pointers" style="font-size:50px;">');
					}else{
						$('#exerciselibimg').append('<img style="padding-right:10px;" width="75px;" src="'+$('#exercise_unit_img').val()+'"  />');
					}
					$('#exercise_unit_hidden').val(field.value);
				}else if(field.name == 'exercise_repetitions'){
					if(ashstrick !='')
						$('span .ashstrick').remove();
					if(field.value != '')
						$('.'+field.name).html(ashstrick+field.value+' reps');
					else
						$('.'+field.name).html('<span class="inactivedatacol">Click to modify</span>');
				}else if(field.name == 'exercise_innerdrive'){
					if(ashstrick !='') $('span .ashstrick').remove();
					if(field.value != ''){
						matchesval = document.getElementById("innerdrive").options[field.value].text;
						if(matchesval != 'Select'){
							var regExp = /\(([^)]+)\)/;
							var matches = regExp.exec(matchesval);
							$('.'+field.name).html(ashstrick+matches[1]+' Int');
						}else{ 
							$('.' + field.name).html('<span class="inactivedatacol">Click to modify</span>'); 
					   }
					}else{ 
						$('.' + field.name).html('<span class="inactivedatacol">Click to modify</span>'); 
				    }
				}else{
				   if (ashstrick != '') $('span .ashstrick').remove();
				   if(field.value!='')
					  $('.' + field.name).html(ashstrick + field.value);
				   else
					  $('.' + field.name).html(ashstrick + '<span class="inactivedatacol">Click to modify</span>');
				}
				if($('.'+field.name+'_hidden')){
					if(field.value !='' && ashstrick !=''){
						if(primaryField!='' && $('#primary_'+primaryField)){
							$('.exercise_priority_hidden').val('0');
							$('#primary_'+primaryField).val(1);
						}
					}else{
						if($('#primary_'+primaryField))
							$('#primary_'+primaryField).val(0);
					}
					if(field.name == 'exercise_innerdrive' && ((!$('.exercise_innerdrive_hidden').val() > 0 && field.value!='') || ($('.exercise_innerdrive_hidden').val() > 0 && field.value==''))){
					  var actioncount = $('span#showcountXrvariable').html().trim();
					  if(field.value != ''){
						 var newactioncount = parseInt(actioncount)+1;
						 $('span#showcountXrvariable').html(newactioncount);
						 if(newactioncount>0 && $('span#showcountXrvariable').hasClass('hide'))
							$('span#showcountXrvariable').removeClass('hide');
					  }else if(parseInt(actioncount) > 0){
						 var newactioncount = parseInt(actioncount)-1; 
						 $('span#showcountXrvariable').html(newactioncount);
						 if(newactioncount==0 && !$('span#showcountXrvariable').hasClass('hide')) 
							$('span#showcountXrvariable').addClass('hide');
					  }
					}
					$('.'+field.name+'_hidden').val(field.value);
				}
			}else{
				unit_val = $('#workoutexercise #'+existUnit+ ' option:selected').text();
				unit_value = $('#workoutexercise #'+existUnit+ ' option:selected').val();
				if(unit_val == 'choose')
					unit_val = '';
				if(field.value == '' && unit_val != ''){
					$('.inputnormal').show();
					$('.error').removeClass('hide');
					trueFlag = false;
					return false;
				}else if(field.value != '' && unit_val == ''){
					$('.unitnormal').show();
					$('.error').removeClass('hide');
					trueFlag = false;
					return false;
				}else{
					if(field.value !='' && ashstrick !=''){
						if(primaryField!='' && $('#primary_'+primaryField)){
							$('.exercise_priority_hidden').val('0');
							$('#primary_'+primaryField).val(1);
						}
					}else{
						if($('#primary_'+primaryField))
							$('#primary_'+primaryField).val(0);
					}
					if(ashstrick !='')
						$('span .ashstrick').remove();
					if(field.value)
						$('.'+field.name).html(ashstrick+field.value);
					else
						$('.' + field.name).html(ashstrick + '<span class="inactivedatacol">Click to modify</span>');
					if((field.name == 'exercise_angle' && ((!$('.exercise_angle_hidden').val() > 0 && field.value!='') || ($('.exercise_angle_hidden').val() > 0 && field.value==''))) || ((field.name == 'exercise_rate' && ((!$('.exercise_rate_hidden').val() > 0 && field.value!='') || ($('.exercise_rate_hidden').val() > 0 && field.value==''))))){
					  var actioncount = $('span#showcountXrvariable').html().trim();
					  console.log(field.name+'====>'+field.value);
					  if(field.value != '' && (unit_value != '0' || unit_value != '')){
						 var newactioncount = parseInt(actioncount)+1;
						 $('span#showcountXrvariable').html(newactioncount);
						 if(newactioncount>0 && $('span#showcountXrvariable').hasClass('hide'))
							$('span#showcountXrvariable').removeClass('hide');
					  }else if(parseInt(actioncount) > 0){
						 var newactioncount = parseInt(actioncount)-1; 
						 $('span#showcountXrvariable').html(newactioncount);
						 if(newactioncount==0 && !$('span#showcountXrvariable').hasClass('hide')) 
							$('span#showcountXrvariable').addClass('hide');
					  }
				    }
					$('.'+field.name+'_hidden').val(field.value);
					$('.'+existUnit+'_hidden').val(unit_value);
					var appendId = field.name.replace('unit_','');
					$('.'+appendId).append(' '+unit_val);
					return true;
				}
			}
		}
	});
	if(trueFlag){
		$('#'+Model).modal('hidecustom');
	}
	return false;
}
function createCopyExerciseSet(selector,move){
	var wkoutid   = 0;
	$('div.optionmenu button.btn').removeClass('checked');
	$('div.createworkout div.border').removeClass('new-item');
	var workoutType = $('input#type_method').val();
	if (selector == '') {
		var checkedArray = new Object();
	   var checkedArrayNew = new Object();
     $("input.checkhiddenpopup:checkbox:checked").each(function(i,field) {
		  var dataCheck = $(this).attr('data-check');
		  checkedArray[dataCheck] = $(this);
		  checkedArrayNew[i] = $(this);
	  });
	  console.log(checkedArray);
	  if ($('#scrollablediv-len li')) var last = $('#scrollablediv-len li').length;
	  else var last = 0;
	  var addedliTag = '';
	  var allowFlag = false;
	  var last = $('#scrollablediv-len li').length;
	  var updatedCnt   = (last > 0 ? last - 1 : 0);
	  var current = updatedCnt;
	  if(move == 'down'){
		  var maxKey = _.max(Object.keys(checkedArray), function (o) { return o;});
		  current = $(checkedArray[maxKey]).parentNth(6).parent('li').index();
		  var updatedCnt = current;
	  }else if(move == 'up'){
		  var minKey = _.min(Object.keys(checkedArray), function (o) { return o;});
		  current = $(checkedArray[minKey]).parentNth(6).parent('li').index();
		  updatedCnt = current;
	  }
	  console.log(updatedCnt);
	  var litagscrollcnt = updatedCnt + 1;
	  if(Object.keys(checkedArrayNew).length >0){
		  var i =0;
		  for (var key in checkedArrayNew) {
			  if (checkedArrayNew.hasOwnProperty(key)) {
				  inputdata = $(checkedArrayNew[key]).val();
				  if ($('div#' + last).find('.navimgdet1').text() == 'Click_to_Edit') {
					 alert('Please fill the above empty set and then try to add new set.');
					 return false;
				  }
				  var count = parseInt($('#s_row_count_flag').val()) + 1;
				  var goalorder = $('#scrollablediv-len li').length + 1;
				  $('#s_row_count_xr').val(parseInt(last) + 1);
				  $('#s_row_count_flag').val(count);
				  var inlineString = $(checkedArrayNew[key]).parentNth(6).html();
				  inlineString = inlineString.replace(/col-xs-2/g, 'col-xs-aa');
				  inlineString = inlineString.replace(/col-xs-8/g, 'col-xs-bb');
				  inlineString = inlineString.replace(/col-xs-4/g, 'col-xs-cc');
				  var selectorId = $(checkedArrayNew[key]).parentNth(6).attr('id');
				  var dataForm = $('#' + selectorId + ' .navbarmenu input').serializeArray();
				  var titleName = '';
				  var replaceFlag = true;
				  $(dataForm).each(function(i, field) {
					 inputNameArr = field.name.split("[")[0];
					 if (inputNameArr == 'exercise_title' || inputNameArr == "exercise_title_new") {
					    inputValue = field.value;
					    titleName = inputValue;
					 } else inputValue = field.value;
					 inputName = inputNameArr + '_new[]';
					 if (!isNaN(inputdata)) {
					    var fieldname = escapeRegExp(field.name);
					    var re3 = new RegExp(fieldname, 'g');
					    inlineString = inlineString.replace(re3, inputName);
					 } else {
						 if(replaceFlag){
							 inputcurCountnew = selectorId.split('_')[1] + '_' + count;
							 inputcurCount = selectorId.split('new_')[1];
							 var inputcurCount = escapeRegExp(inputcurCount);
							 var re2 = new RegExp(inputcurCount, 'g');
							 inlineString = inlineString.replace(re2, inputcurCountnew);
							 var replaceFlag = false;
						 }
					 }
				  });
				  inlineString = inlineString.replace('"' + inputdata + '"', '"new_' + wkoutid + '_' + count + '"');
				  var re5 = new RegExp("_" + inputdata + '"', 'g');
				  inlineString = inlineString.replace(re5, '_new_' + wkoutid + '_' + count + '"');
				  var re5 = new RegExp("'" + inputdata + "'", 'g');
				  inlineString = inlineString.replace(re5, "'new_" + wkoutid + '_' + count + "'");
				  inlineString = inlineString.replace(/col-xs-aa/g, 'col-xs-2');
				  inlineString = inlineString.replace(/col-xs-bb/g, 'col-xs-8');
				  inlineString = inlineString.replace(/col-xs-cc/g, 'col-xs-4');
				  if ($('div.createworkout').find('.navimgdet1').text() != 'Click_to_Edit') {
					 if(move == 'down'){
						$('#scrollablediv-len ul li:eq(' + updatedCnt + ')').after('<li id="itemSetnew_' + wkoutid + '_0_' + count + '" class="bgC4 item_add_wkout_noclick new-item" data-module="item_set_new" data-id="new_' + wkoutid + '_' + count + '"><div class="row createworkout" id="itemsetnew_' + wkoutid + '_' + count + '">' + inlineString + '</div></li>');
					 }else if(move == 'last'){
						$('#scrollablediv-len ul li:eq(' + updatedCnt + ')').after('<li id="itemSetnew_' + wkoutid + '_0_' + count + '" class="bgC4 item_add_wkout_noclick new-item" data-module="item_set_new" data-id="new_' + wkoutid + '_' + count + '"><div class="row createworkout" id="itemsetnew_' + wkoutid + '_' + count + '">' + inlineString + '</div></li>');
					 }else{
						$('#scrollablediv-len ul li:eq(' + updatedCnt + ')').before('<li id="itemSetnew_' + wkoutid + '_0_' + count + '" class="bgC4 item_add_wkout_noclick new-item" data-module="item_set_new" data-id="new_' + wkoutid + '_' + count + '"><div class="row createworkout" id="itemsetnew_' + wkoutid + '_' + count + '">' + inlineString + '</div></li>');
					 }
					 var litagvariable = 'itemSetnew_' + wkoutid + '_' + count;
					 $('#goal_remove_new_' + wkoutid + '_' + count).attr('name', 'goal_remove_new[]');
					 $('#goal_order_new_' + wkoutid + '_' + count).attr('name', 'goal_order_new[]');
					 if (move == 'down') $('#goal_order_new_' + wkoutid + '_' + count).val(count);
					 $('#exercise_title_new_' + wkoutid + '_' + count).val(titleName);
					 $('#' + litagvariable + ' .navimgdet1').html("<b>" + titleName + "</b>");
					 if ($('#scrollablediv-len li').length > 3 && !$('#scrollablediv-len').hasClass('scrollablediv')) $('#scrollablediv-len').addClass('scrollablediv');
					 if ($('#scrollablediv-len li').length == '1') $('.sTreeBase').show();
				  } else {
					 alert('Please fill the above empty set and then try to add new set.');
				  }
				  $('#' + litagvariable + ' .editchoosenIconTwo').removeClass('hide');
				  $('#' + litagvariable + ' .listoptionpoppopup').addClass("hide");
				  $('#' + litagvariable + ' .listoptionpoppopup').attr("onclick","getTemplateOfExerciseSetAction('"+'new_'+wkoutid+'_'+count+"','link');");
				  if(workoutType == 'logged')
						enablestatusButtons('new_'+wkoutid+'_'+count,'2');
				  i++;
				  updatedCnt++;
			   }
		  }
		  if(Object.keys(checkedArrayNew).length == i)
			  var allowFlag = true;
	  }
	  if(allowFlag){
	   var lists = $("#scrollablediv-len ul li");
		$('div#loading-indicator').show();
		$("#scrollablediv-len ul").empty();
      var originalCnt = 0;
      if (originalCnt < lists.length) {
         for (var i = originalCnt; i < lists.length; ++i) {
            var liTagCnt = i;
            var selectorIdinner = $(lists[i]).attr('id');
            var xrId = $(lists[i]).attr('data-id');
				var newItem = '';
				if($(lists[i]).hasClass('new-item')){
					$(lists[i]).removeClass('new-item')
					newItem = 'new-item';
				}
            var inlineString = $(lists[i]).html();
            inlineString = inlineString.replace(/col-xs-2/gi, 'col-xs-aa');
            inlineString = inlineString.replace(/col-xs-8/gi, 'col-xs-bb');
            inlineString = inlineString.replace(/col-xs-4/gi, 'col-xs-cc');
			inlineString = inlineString.replace(/new-item/g, '');
            var updatedCnt = i + 1;
            if (selectorIdinner.indexOf("new_") < 0) {
               var xrIdval = xrId;
               var litagId = wkoutid + '_' + xrIdval + '_' + updatedCnt;
            } else {
               var xrIdArr = xrId.split('_');
               var xrIdvalOrder = xrIdArr[xrIdArr.length - 2] + '_' + xrIdArr[xrIdArr.length - 1];
               var xrIdval = xrId.replace(xrIdvalOrder, xrIdArr[xrIdArr.length - 2] + '_' + updatedCnt);
               var litagId = xrId.replace(xrIdvalOrder, xrIdArr[xrIdArr.length - 2] + '_0_' + updatedCnt);
               var xrId = escapeRegExp(xrId);
               var re1 = new RegExp(xrId, 'g');
               inlineString = inlineString.replace(re1, xrIdval);
               var xrIdnew = escapeRegExp(xrId.replace('new_', ''));
               var re2 = new RegExp(xrIdnew, 'g');
               inlineString = inlineString.replace(re2, xrIdval.replace('new_', ''));
            }
            var titleNameCont = '';
            inlineString = inlineString.replace(/col-xs-aa/g, 'col-xs-2');
            inlineString = inlineString.replace(/col-xs-bb/g, 'col-xs-8');
            inlineString = inlineString.replace(/col-xs-cc/g, 'col-xs-4');
				$('#scrollablediv-len ul').insertAt(liTagCnt,'<li id="'+(selectorIdinner.indexOf("new_") < 0 ? 'itemSet_' : 'itemSet' )+ litagId + '" class="bgC4 item_add_wkout_noclick" data-module="'+(selectorIdinner.indexOf("new_") < 0 ? 'item_set' : 'item_set_new' )+'" data-id="' + xrIdval + '">' + inlineString +'</li>');
				if (selectorIdinner.indexOf("new_") < 0)
					var litagvariable = 'itemSet_' + litagId;
				else
					var litagvariable = 'itemSet' + litagId;
				if(newItem !='')
					$('li#' + litagvariable + ' div.border').addClass('new-item');
            if (originalCnt > 3 && !$('#scrollablediv-len').hasClass('scrollablediv')) $('#scrollablediv-len').addClass('scrollablediv');
            if ($('#scrollablediv-len li').length == '1') $('.sTreeBase').show();
            $('#goal_order_' + xrIdval).val(updatedCnt);
				$('li#' + litagvariable + ' .editchoosenIconTwo').removeClass('hide');
				$('li#' + litagvariable + ' .listoptionpoppopup').addClass("hide");
				if (selectorIdinner.indexOf("new_") > 0)
					$('li#' + litagvariable + ' .listoptionpoppopup').attr("onclick","getTemplateOfExerciseSetAction('"+'new_'+wkoutid+'_'+updatedCnt+"','link');");
         }
      }
	  }
	  $('div#loading-indicator').hide();
	  $('div#scrollablediv-len ul').scrollTop($('div#scrollablediv-len ul li:nth-child('+litagscrollcnt+')').position().top - $('div#scrollablediv-len ul li:first').position().top);
	  enablePopupButtons();
     $('#s_row_count_xr').val($('#scrollablediv-len li').length);
   }else{
		if (!isNaN(selector) && $('div#itemset_'+wkoutid+'_'+selector).length){
			var selectorDiv = $('div#itemset_'+wkoutid+'_'+selector);
			var selectorId  = 'div#itemset_'+wkoutid+'_'+selector;
			var inputdata   = selector;
			var current 	= $('div#itemset_'+wkoutid+'_'+selector).parent('li').index();
		}else if($('div#itemset'+selector).length){
			var selectorDiv = $('div#itemset'+selector);
			var selectorId  = 'div#itemset'+selector;
			if(selector.indexOf("new_") < 0)
				var inputdata   = selector.split('_')[2];
			else
				var inputdata   = 'new_'+selector.replace('new_','');
			var current 	= $('div#itemset'+selector).parent('li').index();
		}else if($('div#itemsetnew_'+selector).length){
			var selectorDiv = $('div#itemsetnew_'+selector);
			var selectorId  = 'div#itemsetnew_'+selector;
			var inputdata   = 'new_'+selector.replace('new_','');
			var current 	= $('div#itemsetnew_'+selector).parent('li').index();
		}
		var curCnt 		= current;	
		var last = $('#scrollablediv-len li').length;
		if($('div.navimgdet1').text()=='Click_to_Edit'){
			alert('Please fill the above empty set and then try to add new set.');
			return false;
		}
		if(move == 'last'){
			count	= last - 1; 
			current = count;
		}else if(move == 'down')
			var count = current+1;
		else
			var count = current;
		$('#s_row_count_xr').val(parseInt(last)+1);
		var inlineString = $(selectorDiv).html();
		inlineString = inlineString.replace(/col-xs-2/gi,'col-xs-aa');
		inlineString = inlineString.replace(/col-xs-8/gi,'col-xs-bb');
		inlineString = inlineString.replace(/col-xs-4/gi,'col-xs-cc');
		var dataForm = $(selectorId+' .navbarmenu input').serializeArray();
		var titleName = '';
		var replaceFlag = true;
		var updatedCnt = parseInt($('#s_row_count_flag').val()) + 1;
		$('#s_row_count_flag').val(updatedCnt);
		$(dataForm).each(function(i, field){
			inputNameArr = field.name.split("[")[0];
			if(inputNameArr == 'exercise_title' || inputNameArr == "exercise_title_new"){
				inputValue  = field.value;
				titleName  = field.value;
			}else
				inputValue  = field.value;
			inputName    = inputNameArr+'_new[]';
			if (!isNaN(inputdata)){
				var fieldname = escapeRegExp(field.name);
				var re3 = new RegExp(fieldname,'g');
				inlineString = inlineString.replace(re3,inputName);
			}else{
				if(replaceFlag){
					inputcurCountnew = selectorId.split('_')[1]+'_'+updatedCnt;
					inputcurCount = selectorId.split('new_')[1];
					var inputcurCount = escapeRegExp(inputcurCount);
					var re2 = new RegExp(inputcurCount,'g');
					inlineString = inlineString.replace(re2,inputcurCountnew);
					var replaceFlag = false;
				}
			}
		});
		inlineString = inlineString.replace('"'+inputdata+'"','"new_'+wkoutid+'_'+updatedCnt+'"');
		var re5 = new RegExp("_"+inputdata+'"','g');
		inlineString = inlineString.replace(re5,'_new_'+wkoutid+'_'+updatedCnt+'"');
		var re5 = new RegExp("'"+inputdata+"'",'g');
		inlineString = inlineString.replace(re5,"'new_"+wkoutid+'_'+updatedCnt+"'");
		inlineString = inlineString.replace(/col-xs-aa/g,'col-xs-2');
		inlineString = inlineString.replace(/col-xs-bb/g,'col-xs-8');
		inlineString = inlineString.replace(/col-xs-cc/g,'col-xs-4');
		if($('div.createworkout').find('.navimgdet1').text()!='Click_to_Edit'){
			if(move == 'down') $('#scrollablediv-len ul li:eq('+current+')').after('<li id="itemSetnew_'+wkoutid+'_0_'+updatedCnt+'" class="bgC4 item_add_wkout_noclick new-item" data-module="item_set_new" data-id="new_'+wkoutid+'_'+updatedCnt+'"><div class="row createworkout" id="itemsetnew_'+wkoutid+'_'+updatedCnt+'">'+inlineString+'</div></li>');
			else if(move =='last')	$('#scrollablediv-len ul li:eq('+count+')').after('<li id="itemSetnew_'+wkoutid+'_0_'+updatedCnt+'" class="bgC4 item_add_wkout_noclick new-item" data-module="item_set_new" data-id="new_'+wkoutid+'_'+updatedCnt+'"><div class="row createworkout" id="itemsetnew_'+wkoutid+'_'+updatedCnt+'">'+inlineString+'</div></li>');
			else	$('#scrollablediv-len ul li:eq('+count+')').before('<li id="itemSetnew_'+wkoutid+'_0_'+updatedCnt+'" class="bgC4 item_add_wkout_noclick new-item" data-module="item_set_new" data-id="new_'+wkoutid+'_'+updatedCnt+'"><div class="row createworkout" id="itemsetnew_'+wkoutid+'_'+updatedCnt+'">'+inlineString+'</div></li>');
			var litagscrollcnt = (count == 0 ? count + 1 : count);
			$('#goal_remove_new_'+wkoutid+'_'+updatedCnt).attr('name', 'goal_remove_new[]');
			$('#goal_order_new_'+wkoutid+'_'+updatedCnt).attr('name', 'goal_order_new[]');
			if(move == 'down') $('#goal_order_new_'+wkoutid+'_'+updatedCnt).val(updatedCnt);
			if($('#scrollablediv-len li').length >3 && !$('#scrollablediv-len').hasClass('scrollablediv')) $('#scrollablediv-len').addClass('scrollablediv');
			if(workoutType == 'logged')
				enablestatusButtons('new_'+wkoutid+'_'+updatedCnt,'2');
			if($('#scrollablediv-len li').length == '1') $('.sTreeBase').show();
		}else{
			alert('Please fill the above empty set and then try to add new set.');
		}
		$('#itemsetnew_'+wkoutid+'_'+updatedCnt+' .editchoosenIconTwo').addClass('hide');
		$('#itemsetnew_'+wkoutid+'_'+updatedCnt+' .listoptionpoppopup').removeClass("hide");
		$('#itemsetnew_'+wkoutid+'_'+updatedCnt+' .listoptionpoppopup').attr("onclick","getTemplateOfExerciseSetAction('"+'new_'+wkoutid+'_'+updatedCnt+"','link');");
		var lists = $("#scrollablediv-len ul li");
		$('div#loading-indicator').show();
		$("#scrollablediv-len ul").empty();
      var originalCnt = 0;
      if (originalCnt < lists.length) {
         for (var i = originalCnt; i < lists.length; ++i) {
            var liTagCnt = i;
            var selectorIdinner = $(lists[i]).attr('id');
				var newItem = '';
				if($(lists[i]).hasClass('new-item')){
					$(lists[i]).removeClass('new-item')
					newItem = 'new-item';
				}
            var xrId = $(lists[i]).attr('data-id');
            var inlineString = $(lists[i]).html();
				
            inlineString = inlineString.replace(/col-xs-2/gi, 'col-xs-aa');
            inlineString = inlineString.replace(/col-xs-8/gi, 'col-xs-bb');
            inlineString = inlineString.replace(/col-xs-4/gi, 'col-xs-cc');
				inlineString = inlineString.replace(/new-item/g, '');
            var updatedCnt = i + 1;
            if (selectorIdinner.indexOf("new_") < 0) {
               var xrIdval = xrId;
               var litagId = wkoutid + '_' + xrIdval + '_' + updatedCnt;
            } else {
               var xrIdArr = xrId.split('_');
               var xrIdvalOrder = xrIdArr[xrIdArr.length - 2] + '_' + xrIdArr[xrIdArr.length - 1];
               var xrIdval = xrId.replace(xrIdvalOrder, xrIdArr[xrIdArr.length - 2] + '_' + updatedCnt);
               var litagId = xrId.replace(xrIdvalOrder, xrIdArr[xrIdArr.length - 2] + '_0_' + updatedCnt);
               var xrId = escapeRegExp(xrId);
               var re1 = new RegExp(xrId, 'g');
               inlineString = inlineString.replace(re1, xrIdval);
               var xrIdnew = escapeRegExp(xrId.replace('new_', ''));
               var re2 = new RegExp(xrIdnew, 'g');
               inlineString = inlineString.replace(re2, xrIdval.replace('new_', ''));
            }
            var titleNameCont = '';
            inlineString = inlineString.replace(/col-xs-aa/g, 'col-xs-2');
            inlineString = inlineString.replace(/col-xs-bb/g, 'col-xs-8');
            inlineString = inlineString.replace(/col-xs-cc/g, 'col-xs-4');
				$('#scrollablediv-len ul').insertAt(liTagCnt,'<li id="'+(selectorIdinner.indexOf("new_") < 0 ? 'itemSet_' : 'itemSet' )+ litagId + '" class="bgC4 item_add_wkout_noclick" data-module="'+(selectorIdinner.indexOf("new_") < 0 ? 'item_set' : 'item_set_new' )+'" data-id="' + xrIdval + '">' + inlineString +'</li>');
				if (selectorIdinner.indexOf("new_") < 0)
					var litagvariable = 'itemSet_' + litagId;
				else
					var litagvariable = 'itemSet' + litagId;
				if(newItem !='')
					$('li#' + litagvariable + ' div.border').addClass('new-item');
            if (originalCnt > 3 && !$('#scrollablediv-len').hasClass('scrollablediv')) $('#scrollablediv-len').addClass('scrollablediv');
            if ($('#scrollablediv-len li').length == '1') $('.sTreeBase').show();
            $('#goal_order_' + xrIdval).val(updatedCnt);
				$('li#' + litagvariable + ' .editchoosenIconTwo').addClass('hide');
				$('li#' + litagvariable + ' .listoptionpoppopup').removeClass("hide");
				if (selectorIdinner.indexOf("new_") > 0)
					$('li#' + litagvariable + ' .listoptionpoppopup').attr("onclick","getTemplateOfExerciseSetAction('"+'new_'+wkoutid+'_'+updatedCnt+"','link');");
         }
      }
		$('div#loading-indicator').hide();
		$('div#scrollablediv-len ul').scrollTop($('div#scrollablediv-len ul li:nth-child('+litagscrollcnt+')').position().top - $('div#scrollablediv-len ul li:first').position().top);
	}
	
}
function escapeRegExp(stringToGoIntoTheRegex) {
	return stringToGoIntoTheRegex.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
}
function getTemplateOfExerciseSetAction(exerciseSetId,link){
	var type = $('#type_method').val();
	$('#FolderModalpopupOption').html();
	var wkoutid   = 0;
	var goalOrder = $('div#itemsetnew_'+wkoutid+'_'+exerciseSetId+' input#goal_order_new_'+wkoutid+'_'+exerciseSetId).val();
	$.ajax({
		url : siteUrl+"search/getmodelTemplate/",
		data : {
			action : 'exercisesetaction',
			method :  'createNewWrkout', 
			id : wkoutid,
			foldid : exerciseSetId,
			modelType : 'FolderModalpopupOption',
			xrid : $('#exercise_unit_new_'+exerciseSetId).val(),
			editFlag : true,
			goalOrder : goalOrder,
			type  : type,
		},
		success : function(content){
			$('#FolderModalpopupOption').html(content);
			$('#FolderModalpopupOption').modal();
		}
	});
}
function doDeleteProcess(type ,selector){
	var wkoutid   = 0;
	$('div.createworkout div.border').removeClass('new-item');
	$('div.optionmenu button.btn').removeClass('checked');
	if(type == 'exerciseset'){
		if(confirm('Deleting this Exercise Set will not be saved until all updates to the Workout Plan have been confirmed.')){
			if($('div#itemsetnew_'+selector).length){
				curOrder = $('#goal_order_new_'+selector).val();
				$('input.seq_order_up').each(function(i, field){
					if(curOrder < field.value){
						inputId = $(this).attr('id');
						$('input#'+inputId).val(field.value-1);
					}
				});
				$('div#itemsetnew_'+selector).parent('li').remove();
				enablePopupButtons();
			}
			closeModelwindow('myOptionsModalAjax');
			closeModelwindow('myOptionsModal');
			return false;
		}
	}else{
		if(confirm('Are you sure want to delete this Workout records from My Workout Plans?')){
			return true;
		}
	}
	return false;
}
function addnewExercise(elem){
	$('.errormsg').hide();
	$('div.createworkout div.border').removeClass('new-item');
	var modaldata=$('#createExercise').serializeArray();
	var allowCls = false;
	var exerciseUnit = '';
	$(modaldata).each(function(i, field){
		if(field.name == 'exercise_title_hidden'){
			if(field.value != ""){
				$('#itemsetnew_'+elem).find('.navimgdet1').html('<b>'+field.value+'</b>');
				allowCls = true;
			}else{
				$('.errormsg').text('Exercise Title not empty').removeClass('hide').show();
				return false;
			}
		}
		if(field.name == 'exercise_unit_hidden'){
			if(field.value !=""){
				if($('#createExercise span#exerciselibimg img').length && $('#createExercise span#exerciselibimg img').attr('src') != '')
					$('#itemsetnew_'+elem+' .navimage').html('<img width="75px;" src="'+$('#createExercise span#exerciselibimg img').attr('src')+'"  class="img-responsive pointers">');
				else
					$('#itemsetnew_'+elem+' .navimage').html('<i class="fa fa-file-image-o pointers" style="font-size:50px;">');
				$('#itemsetnew_'+elem+' .navimage').attr("onclick","getTemplateOfExerciseRecordAction('"+field.value+"',this);");
			}else{
				$('#itemsetnew_'+elem+' .navimage').html('<i class="fa fa-pencil-square" style="font-size:50px;">');
			}
		}
		newvariable = field.name.replace("_hidden", "");
		$('#'+newvariable+'_new_'+elem).val(field.value);
		var updatedText = '';
		if($('#itemsetnew_'+elem+' a.'+newvariable+'_div').length && $('#createExercise span.'+newvariable).length){
			updatedText = $('#createExercise span.'+newvariable).html().trim();
			if(updatedText !='<span class="inactivedatacol">Click to modify</span>'){
				if(newvariable == 'exercise_rest' && updatedText.trim() != '')
					$('#itemsetnew_'+elem+' a.'+newvariable+'_div').html(updatedText+' rest');
				else
					$('#itemsetnew_'+elem+' a.'+newvariable+'_div').html(updatedText);
			}else{
				$('#itemsetnew_'+elem+' a.'+newvariable+'_div').html('');
			}
		}
	});
	
	var flag = false;
	if($('#itemsetnew_'+elem+' a.exercise_time_div').html().trim() != ''){		
		flag = true;
	}
	if($('#itemsetnew_'+elem+' a.exercise_distance_div').html().trim() != ''){
		var inHtml = $('#itemsetnew_'+elem+' a.exercise_distance_div').html();
		if(flag && inHtml.trim() !='')
			$('#itemsetnew_'+elem+' a.exercise_distance_div').html(' /// '+inHtml);
		else
			$('#itemsetnew_'+elem+' a.exercise_distance_div').html(inHtml);
		flag = true;
	}	
	if($('#itemsetnew_'+elem+' a.exercise_repetitions_div').html().trim() != ''){
		var inHtml = $('#itemsetnew_'+elem+' a.exercise_repetitions_div').html();
		if(flag && inHtml.trim() !='')
			$('#itemsetnew_'+elem+' a.exercise_repetitions_div').html(' /// '+inHtml);
		else
			$('#itemsetnew_'+elem+' a.exercise_repetitions_div').html(inHtml);
		flag = true;
	}
	if($('#itemsetnew_'+elem+' a.exercise_resistance_div').html().trim() != ''){
		var inHtml = $('#itemsetnew_'+elem+' a.exercise_resistance_div').html();
		if(flag && inHtml.trim() !='')
			$('#itemsetnew_'+elem+' a.exercise_resistance_div').html(' /// '+inHtml);
		else
			$('#itemsetnew_'+elem+' a.exercise_resistance_div').html(inHtml);
	}
	if(allowCls){
		$('#goal_order_'+elem).val(0);
		if($('ul#sTree3 #itemsetnew_'+elem).parent('li').hasClass('hide'))$('ul#sTree3 #itemsetnew_'+elem).parent('li').removeClass('hide');
		if($('#scrollablediv-len ul#sTree3').hasClass('hide')) $('#scrollablediv-len ul#sTree3').removeClass('hide');
		$('#itemsetnew_' + elem + ' div.border').addClass('new-item');
		litagscrollcnt = $('#itemsetnew_' + elem).parent('li').index();
		if(litagscrollcnt == '0')
			$('div#scrollablediv-len ul').scrollTop($('div#scrollablediv-len ul li:first').position().top);
		else if($('#scrollablediv-len ul li').length == litagscrollcnt)
			$('div#scrollablediv-len ul').scrollTop($('div#scrollablediv-len ul li:last').position().top);
		else
			$('div#scrollablediv-len ul').scrollTop($('div#scrollablediv-len ul li:nth-child('+litagscrollcnt+')').position().top - $('div#scrollablediv-len ul li:first').position().top);
		var type = $('#type_method').val();
		if(type == 'logged'){
			if($('input#markedstatus_new_'+elem).val() == 0){
				$('input#markedstatus_new_'+elem).val(1);
				enablestatusButtons('new_'+elem,0);
			}
		}
		$('i.listoptionpoppopup ').removeClass('hide');
		$('#FolderModalpopupOption').modal('hidecustom');
	}
	return true;
};
function createNewExerciseSet(selector, move) {
	$('div.createworkout div.border').removeClass('new-item');
	var last = $('#s_row_count_xr').val();
   if(move == 'last'){
	   var goalorder = last + 1;
   }else if(move == 'down' || move == 'up'){
		if (selector.indexOf("new_") < 0) {
			if ($('div#itemset_0' + '_' + selector).length) {
				var goalorder = $('div#itemset_0' + '_' + selector).parent('li').index();
			} else if ($('div#itemsetnew_' + selector).length) {
				var goalorder = $('div#itemsetnew_' + selector).parent('li').index();
			}
		}else{
			if ($('div#itemset' + selector).length) {
				var goalorder = $('div#itemset'+ selector).parent('li').index();
			} else if ($('div#itemsetnew_' + selector).length) {
				var goalorder = $('div#itemsetnew_' + selector).parent('li').index();
			}			
		}
   }
	var type = $('#type_method').val();
	if ($('div.createworkout').find('.navimgdet1').text() != 'Click_to_Edit') {
		var count = parseInt(last) + 1;
		$('#s_row_count_xr').val(count);
		$('#s_row_count_flag').val(parseInt($('#s_row_count_flag').val()) + 1);
		var li_element = '<li data-id="new_0_'+count+'" data-module="item_set_new" class="bgC4 item_add_wkout_noclick hide" id="itemSetnew_0_0_'+count+'"><div id="itemsetnew_0_'+count+'" class="row createworkout"><input type="hidden" value="'+count+'" class="seq_order_up" name="goal_order_new[]" id="goal_order_new_0_'+count+'"><input type="hidden" value="0" name="goal_remove_new[]" id="goal_remove_new_0_'+count+'"><div class="mobpadding"><div class="border full new-item"><div style="display:none;" class="checkboxchoosen popupchoosen col-xs-2"><div style="font-size:20px;" class="checkboxcolor"><label><input type="checkbox" name="exercisesets[]" onclick="enablePopupButtons();" value="new_0_'+count+'" data-ajax="false" data-role="none" class="checkhiddenpopup"><span class="cr checkbox-circle"><i class="cr-icon fa fa-check"></i></span></label></div></div><div class="col-xs-8 navdescrip"><div class="col-xs-4 activelinkpopup navimage"><i style="font-size:50px;" class="fa fa-pencil-square"></i></div><div class="col-xs-8 pointers activelinkpopup datacol"><div class="activelinkpopup navimagedetails" onclick="editWorkoutRecord('+"'new_0_"+count+"','create'"+');"><div class="navimgdet1"><b>Click_to_Edit</b></div><div class="navimgdet2"><a class="datadetail exercise_time_div" href="javascript:void(0);" data-role="none" data-ajax="false"></a><a class="datadetail exercise_distance_div" href="javascript:void(0);" data-role="none" data-ajax="false"></a><a class="datadetail exercise_repetitions_div" href="javascript:void(0);" data-role="none" data-ajax="false"></a><a class="datadetail exercise_resistance_div" href="javascript:void(0);" data-role="none" data-ajax="false"></a></div><div class="navimgdet3"><a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail exercise_rate_div"></a>&nbsp;<a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail exercise_angle_div"></a>&nbsp;<a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail exercise_innerdrive_div"></a>&nbsp;<a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail exercise_rest_div"></a></div></div>'+(type == 'logged' ? '<div class="navremarkdetails activelinkpopup hide" onclick="changeWkoutStatusExcise('+"'new_0_"+count+"',"+'this);" style="clear: both;margin-left:33.3%"><div class="navimgdet4"><a data-ajax="false" data-role="none" href="javascript:void(0);" style="text-decoration:none" class="datacol exercise_intent_div"></a></div><div class="navimgdet5"><a data-ajax="false" data-role="none" href="javascript:void(0);" style="text-decoration:none" class="datacol exercise_remarks_div"></a></div></div>' : '')+'</div></div>'+(type == 'logged' ? '<div id="checkboxmark_new_0_'+count+'" class="col-xs-1 checkboxmark listoptionpopcheck"><label><input onclick="enablestatusButtons('+"'new_0_"+count+"',"+'0);" data-role="none" data-ajax="false"  type="checkbox" class="checkhiddenstatus" name="exercisestatus[]" value=""><span class="cr checkbox-circle" style="border-radius: 20%;"><i class="cr-icon fa fa-check"></i></span></label></div>' : '')+'<div class="col-xs-1 navbarmenu"><a data-ajax="false" class="pointers editchoosenIconTwo editchoosenIconTwoPopup hide" href="javascript:void(0);"><i class="fa fa-bars panel-draggable" style="font-size:25px;"></i></a>'+(type == 'logged' ? '<i class="fa fa-ellipsis-h iconsize listoptionpoppopup" id="markstatus_new_0_'+count+'" onclick="getTemplateOfExerciseSetAction('+"'0_"+count+"','link'"+');"></i><input type="hidden" value="0" name="markedstatus_new[]" id="markedstatus_new_0_'+count+'"/><input type="hidden" value="0" name="edit_status_new[]" id="edit_status_new_0_'+count+'"/><input type="hidden" value="0" name="per_intent_new[]" id="per_intent_new_0_'+count+'"/><input type="hidden" value="0" name="per_remarks_new[]" id="per_remarks_new_0_'+count+'"/><input type="hidden" value="0" name="hide_notes_set_new[]" id="hide_notes_set_new_0_'+count+'"/>' : '<i class="fa fa-ellipsis-h iconsize listoptionpoppopup" onclick="getTemplateOfExerciseSetAction('+"'0_"+count+"','link'"+');"></i>')+'<input type="hidden" value="" name="exercise_title_new[]" id="exercise_title_new_0_'+count+'"><input type="hidden" value="0" name="exercise_unit_new[]" id="exercise_unit_new_0_'+count+'"><input type="hidden" value="" name="exercise_resistance_new[]" id="exercise_resistance_new_0_'+count+'"><input type="hidden" value="" name="exercise_unit_resistance_new[]" id="exercise_unit_resistance_new_0_'+count+'"><input type="hidden" value="" name="exercise_repetitions_new[]" id="exercise_repetitions_new_0_'+count+'"><input type="hidden" value="" name="exercise_time_new[]" id="exercise_time_new_0_'+count+'"><input type="hidden" value="" name="exercise_distance_new[]" id="exercise_distance_new_0_'+count+'"><input type="hidden" value="" name="exercise_unit_distance_new[]" id="exercise_unit_distance_new_0_'+count+'"><input type="hidden" value="" name="exercise_rate_new[]" id="exercise_rate_new_0_'+count+'"><input type="hidden" value="" name="exercise_unit_rate_new[]" id="exercise_unit_rate_new_0_'+count+'"><input type="hidden" value="" name="exercise_innerdrive_new[]" id="exercise_innerdrive_new_0_'+count+'"><input type="hidden" value="" name="exercise_angle_new[]" id="exercise_angle_new_0_'+count+'"><input type="hidden" value="" name="exercise_unit_angle_new[]" id="exercise_unit_angle_new_0_'+count+'"><input type="hidden" value="" name="exercise_rest_new[]" id="exercise_rest_new_0_'+count+'"><input type="hidden" value="" name="exercise_remark_new[]" id="exercise_remark_new_0_'+count+'"><input type="hidden" value="" name="primary_time_new[]" class="exercise_priority_hidden" id="primary_time_new_0_'+count+'"><input type="hidden" value="" name="primary_dist_new[]" class="exercise_priority_hidden" id="primary_dist_new_0_'+count+'"><input type="hidden" value="" name="primary_reps_new[]" class="exercise_priority_hidden" id="primary_reps_new_0_'+count+'"><input type="hidden" value="" name="primary_resist_new[]" class="exercise_priority_hidden" id="primary_resist_new_0_'+count+'"><input type="hidden" value="" name="primary_rate_new[]" class="exercise_priority_hidden" id="primary_rate_new_0_'+count+'"><input type="hidden" value="" name="primary_angle_new[]" class="exercise_priority_hidden" id="primary_angle_new_0_'+count+'"><input type="hidden" value="" name="primary_rest_new[]" class="exercise_priority_hidden" id="primary_rest_new_0_'+count+'"><input type="hidden" value="" name="primary_int_new[]" class="exercise_priority_hidden" id="primary_int_new_0_'+count+'"></div></div></div></div></li>';
		if(move == 'down')
			$('#scrollablediv-len ul li:eq(' + goalorder + ')').after(li_element);
		else if(move == 'up')
			$('#scrollablediv-len ul li:eq(' + goalorder + ')').before(li_element);
		else
			$('#scrollablediv-len ul').append(li_element);
      if (count > 3) $('#scrollablediv-len').addClass('scrollablediv');      
      editWorkoutRecord('0_' + count,'create');
	}
}
function getpopupexercisesetTemplate(thisobj,wkoutId, exerciseId, type){
	if(!$(thisobj).hasClass('activateLink')){
		if(type != 'title')
			$('#exerciselib-template').remove();
		$('#mypopupModal').html();
		$.ajax({
			url : siteUrl+"search/getmodelTemplate/",
			data : {
				action : 'workoutExercise',
				method : type,
				id	   : wkoutId,
				foldid : exerciseId,
				modelType : 'mypopupModal'
			},
			success : function(content){
				$('#mypopupModal').html(content);
				$('.checkboxdrag[type="checkbox"]').bootstrapSwitch('size','small');
				$('.checkboxdrag[type="checkbox"]').bootstrapSwitch('onText',' ');
				$('.checkboxdrag[type="checkbox"]').bootstrapSwitch('offText',' ');
				if(type == 'title')
					$('#mypopupModal').modal('hidecustom');
				else
					$('#mypopupModal').modal();
			}
		});
		if(type == 'title')
			createExerciseFromXrLibrary('');	
	}
}
function changeTosaveIcon(){
}
// by G.R
function getWkoutEditInstruction() {
	if(allowNotify){
	   $('#mypopupModal').html();
	   $.ajax({
		  url: siteUrl + "search/getmodelTemplate",
		  data: {
			 action: 'editNotification',
			 modelType: 'mypopupModal',
		  },
		  success: function(content) {
			 $('#mypopupModal').html(content);
			 if(content !='')
				$('#mypopupModal').modal();
		  }
	   });
	}
}
function editExercistSets(selector){
	$(selector).addClass('hide');
	$('#refreshpopup').removeClass('hide');
	$('#createwkoutpopup').addClass('hide');
	$('.optionmenupopup div.allowhide').removeClass('hide');
	$('.popupchoosen').show();
	$('a.editchoosenIconTwoPopup').removeClass('hide');
	$('i.listoptionpoppopup').addClass('hide');
	$('i.listoptionpop').addClass('hide');
	$('div.listoptionpopcheck').addClass('hide');
	$('.activelinkpopup').attr('disabled','disabled');
	$("ul#sTree3").sortable({
		tolerance: 'pointer',
		revert: 'invalid',
		cursor: "move",
		forceHelperSize: true,
		forcePlaceholderSize: true,
		placeholder: "sortableListsHint",
		axis: 'y',
		handle: '.panel-draggable',
		stop:function(event, ui) {
			var sortedIDs = $( "ul#sTree3" ).sortable( "toArray",{attribute: 'data-id'} );
			var z = 1;
			for (var j = 0; j < sortedIDs.length; j++) {
				x = j + 1;
				var liTagDataid = sortedIDs[j];
				$('#goal_order_' + liTagDataid).val(x);
			}
			console.log(sortedIDs);
		},
	}).disableSelection();
	getWkoutEditInstruction();
	$('input.checkhiddenpopup:checkbox').attr('checked', false);
	$('div.createworkout div.border').removeClass('new-item');
	$('div.optionmenu button.btn').removeClass('checked');
	if($('button i.allowActive').hasClass('activecol')){
		$('button i.allowActive').removeClass('activecol');
		$('button i.allowActive').addClass('datacol');
	}
	return false;
}
function checkallItemspopup(selector){
	if($(selector).hasClass('checked')){
		$('.checkboxcolor label input.checkhiddenpopup[type="checkbox"]').prop('checked', false);
		$(selector).removeClass('checked');
	}else{
		var checked = 1;
      $('.checkboxcolor label input.checkhiddenpopup[type="checkbox"]').each(function(i, field) {
			$(this).prop('checked', true);
			$(this).attr('data-check',checked);
			checked++;
      });
      $(selector).addClass('checked');
	}
	if($('.checkboxcolor label input.checkhiddenpopup[type="checkbox"]:checked').length > 0){
		$('button i.allowActive').removeClass('datacol');
		$('button i.allowActive').addClass('activecol');
	}else{
		$('button i.allowActive').addClass('datacol');
		$('button i.allowActive').removeClass('activecol');
	}
	$('div.createworkout div.border').removeClass('new-item');
	return false;
}
function editWorkoutrefresh(selector){
	$(selector).addClass('hide');
	$('#editxrpopup').removeClass('hide');
	$('.optionmenupopup div.allowhide').addClass('hide');
	$('#createwkoutpopup').removeClass('hide');
	$('.popupchoosen').hide();
	$('.editchoosenIconTwoPopup').addClass('hide');
	$('i.listoptionpoppopup').removeClass('hide');
	$('i.listoptionpop').removeClass('hide');
	$('div.listoptionpopcheck').removeClass('hide');
	$('.activelinkpopup').removeAttr('disabled');
	$('input.checkhiddenpopup:checkbox').attr('checked',false);
	$('div.createworkout div.border').removeClass('new-item');
	$('div.optionmenu button.btn').removeClass('checked');
	return false;
}
function enablePopupButtons(){
	if($('.checkboxcolor label input.checkhiddenpopup[type="checkbox"]:checked').length > 0){
		$('button i.allowActive').removeClass('datacol');
		$('button i.allowActive').addClass('activecol');
	}else{
		$('button i.allowActive').addClass('datacol');
		$('button i.allowActive').removeClass('activecol');
	}
}
function deleteExerciseSet(){
	if($("input.checkhiddenpopup:checkbox:checked").length>0 && confirm('Deleting this Exercise Set will not be saved until all updates to the Workout Plan have been confirmed.')){
		$("input.checkhiddenpopup:checkbox:checked").each(function(){
			curOrder = $('#goal_order_'+$(this).val()).val();
			var selectorId = $(this).parentNth(6).attr('id');
			if(selectorId.indexOf('new')>= 0){
				$('div#'+selectorId).parent('li').remove();
			}
			$('input.seq_order_up').each(function(i, field){
				if(curOrder < field.value){
					inputId = $(this).attr('id');
					$('input#'+inputId).val(field.value-1);
				}
			});
			var count = $('#s_row_count_xr').val();
			$('#s_row_count_xr').val(count - 1);
		});
		enablePopupButtons();
	}
}
function createworkoutSubmit(){
	$('.errormsg').text('').hide();
	var title = $('#wkout_title').val();
	var color = $('#wrkoutcolor').val();
	var focus = $('#wkout_focus').val();
	if(title == ''){
		$('.errormsg').text('Workout Title should not empty').removeClass('hide').show();
	}else if(color==""){
		$('.errormsg').text('Workout Color should not empty').removeClass('hide').show();
	}else if(focus==""){
		$('.errormsg').text('Overall Focus should not empty').removeClass('hide').show();
	}else if($('#scrollablediv-len ul#sTree3 li').length == 0){
		$('.errormsg').text('Please fill the below empty set and then try to add new set.').removeClass('hide').show();
	}else{
		return true;
	}
	return false;
}
function clearInputField(inputField){
	if(inputField == 'exercise_time')
		$('#'+inputField).val('00:00:00');
	else if(inputField == 'exercise_rest')
		$('#'+inputField).val('00:00');
	else
		$('#'+inputField).val('');
	if($('select.dropdown'))
		$('select.dropdown').val(0);
	$('.checkboxdrag').bootstrapSwitch('state', false)
}
function toggleDivTitle(){
	if($('#expendeddiv').hasClass('fa-caret-up')){
		$('#expendeddiv').removeClass('fa-caret-up');
		$('#expendeddiv').addClass('fa-caret-down');
		$( "#expended" ).slideUp( "slow", function() {
			if($("#scrollablediv-len"))
				setDynamicHeight();
		});
	}else if($('#expendeddiv').hasClass('fa-caret-down')){
		$('#expendeddiv').removeClass('fa-caret-down');
		$('#expendeddiv').addClass('fa-caret-up');
		$( "#expended" ).slideDown( "slow", function() {
			if($("#scrollablediv-len"))
				setDynamicHeight();
		});
	}
}
function skipExerciseNotesToLog(xrid,flag,model){
	closeModelwindow(model);
}
function getjournalwrkoutpreview(wkoutId, wkoutLogId , assignedDate , assignedby){
	$('#myModalpreV').html();
	$.ajax({
		url : siteUrl+"search/getmodelTemplate/",
		data : {
			action : 'previewworkout',
			method :  'preview',
			id : wkoutId,
			foldid : '',
			logid : wkoutLogId,
			ownWkFlag : assignedby,
			type	: 'logged',
			date   : assignedDate
		},
		success : function(content){
			$('#myModalpreV').html(content);
			$('#myModalpreV').modal();
		}
	});
}
function getTemplateOfAssignActionByJournal(wkoutId, wkoutlogId , assignedDate , assignedby,title){
	$('#myModal').html();
	$.ajax({
		url : siteUrl+"search/getmodelTemplate/",
		data : {
			action : 'assignOptions',
			method :  'options',
			id : wkoutId,
			foldid : '',
			logid : wkoutlogId,
			ownWkFlag : assignedby,
			type	: 'assignedCal',
			date   : assignedDate,
			title   : title
		},
		success : function(content){
			$('#myModal').html(content);
			$('#myModal').modal();
		}
	});
}
function doDeleteLogProcess(){
	if(confirm('Are you sure want to delete this Journal?'))
		return true;
	return false;
}
function addLogWorkouts(wkoutlogId, wkoutId, date, method){
	$('#FolderModal').html();
	if(method == 'wkoutlog' || method == 'wkoutassign'){
		var type = (method == 'wkoutlog' ? 'logged' : 'wkoutAssignCal');
		if(method == 'wkoutassign')
			method = 'wkoutlog';
		$.ajax({
			url : siteUrl+"search/getmodelTemplate/",
			data : {
				action : 'addAssignWorkouts',
				method :  method,
				id : wkoutlogId,
				logid :wkoutlogId,
				date   : date,
				type   : type,
				modelType : "FolderModal"
			},
			success : function(content){
				$('#FolderModal').html(content);
				$('#FolderModal').modal();
			}
		});
	}else if(method == 'dulicateAssignWkoutLog'){
		$.ajax({
			url : siteUrl+"search/getmodelTemplate/",
			data : {
				action : 'addAssignWorkouts',
				method :  method,
				id : wkoutlogId,
				date   : date,
				type   : 'dulicateAssignWkoutLog',
				modelType : "FolderModal"
			},
			success : function(content){
				$('#FolderModal').html(content);
				$('#FolderModal').modal();
			}
		});
	}else{
		$.ajax({
			url : siteUrl+"search/getmodelTemplate/",
			data : {
				action : 'confirmAssignDate',
				method :  'action', 
				id : wkoutlogId,
				foldid : 0,
				date   : date,
				type   : 'dulicateWkoutLog',
				modelType : 'FolderModal',
			},
			success : function(content){
				$('#FolderModal').html(content);
				$('#FolderModal').modal();
			}
		});
	}
	return false;
}
function jounalOptionsConfirm(fid,date,method){	
	formdata = $('form#addAssignWorkouts').serializeArray();
	$(formdata).each(function(i, field){
		if(field.name == 'selected_date'){
			date = field.value;
		}
	});
	$('#FolderModalpopup').html();
	$.ajax({
		url : siteUrl+"search/getmodelTemplate",
		data : {
			action : 'workoutLogConfirm',
			method : method,
			logid  : 0,
			foldid : '',
			id     : fid,
			date   : date,
			type   : 'wkoutLogFromPrev',
			goalOrder : 0,
			modelType : 'FolderModalpopup',
		},
		success : function(content){
			$('#FolderModalpopup').html(content);
			$('#FolderModalpopup').modal();
		}
	});
	return false;
}
function confirmPopup(type){
	if(createworkoutSubmit()){
		$('#FolderModalpopupOption').html();
		$.ajax({
			url : siteUrl+"search/getmodelTemplate",
			data : {
				action : 'confirmWorkoutPopup',
				method : 'confirmpopup',
				id	   : '',
				foldid : '',
				modelType : 'FolderModalpopupOption',
				type  : type
			},
			success : function(content){
				$('#FolderModalpopupOption').html(content);
				$('#FolderModalpopupOption').modal();
			}
		});
	}
	return false;
}
function confirmwkout(model,flag){
	if (flag.trim() == '2') {
	  window.location.reload();
   } else {
      $('#save_edit').val(flag);
      $('form#createNewworkout').submit();
   }
}
function getRateFromUser(xrId){
	$('#myOptionsModalExerciseRecord').html();
	$.ajax({
		url : siteUrl+"search/getmodelTemplate/",
		data : {
			action : 'relatedRecords',
			method : 'xrrate',
			id : xrId,
			foldid : 0,
			modelType : "myOptionsModalExerciseRecord"
		},
		success : function(content){
			$('#myOptionsModalExerciseRecord').html(content);
			$('#myOptionsModalExerciseRecord').modal();
		}
	});
}
function sendByEmail(alldetailinput){
	var main_arrayvalues = new Array();
	var exportid_val = $(alldetailinput).attr("data-exportid");
	var exporttype_val = $(alldetailinput).attr("data-exporttype");
	var flagtype_val = $(alldetailinput).attr("data-flagtype");
	main_arrayvalues.push({'exportid':exportid_val,'exporttype':exporttype_val,'flag_val':flagtype_val});
	$.post( siteUrl+"export/generateWkoutTemp",{"fetchallvalues":main_arrayvalues},function(success){
		var response = $.parseJSON(success);
		closeModelwindow('FolderModal');
		closeModelwindow('');
		$('.success').html('Export Mail sent Successfully!!!').show();
	});
	return true;
}
function enableExport(logId,type){
	$('#FolderModal').html();
	$.ajax({
		url : siteUrl+"search/getmodelTemplate/",
		data : {
			action : 'exportActionOptions',
			method : 'action',
			id : logId,
			foldid : 0,
			modelType : "FolderModal",
			type : type
		},
		success : function(content){
			$('#FolderModal').html(content);
			$('#FolderModal').modal();
		}
	});
}
function addNewWorkoutOption(mymodal){
	$("#myModal").html();
	$.ajax({	
		url : siteUrl+"search/getmodelTemplate",
		data : {
			action : "workoutAddaction",
			modelType : "myModal",
		},
		success : function(content){
			$("#myModal").html(content);
			$("#myModal").modal();
		}
	});
}
function addNewWorkoutlogs(date,foldid,fid,type){
	var method =  "addworkoutLog";
	if(type == 'loggedwkout'){
		method = 'addworkoutLogwkout';
		type = '';
	}
	type = type.replace('-logged','');
	$("#FolderModal").html();
	$.ajax({	
		url : siteUrl+"search/getmodelTemplate",
		data : {
			action : "createNewworkout",
			method :  method,
			id : fid,
			foldid : foldid,
			date : date,
			modelType : "FolderModal",
			type : type,
		},
		success : function(content){
			$("#FolderModal").html(content);
			$("#FolderModal").modal();
		}
	});
}
function addWorkoutlogs(date,foldid,fid,type){
	if(type.indexOf('-loggedwkout') > 0){
		method = 'addworkoutLogwkout';
		type = type.replace('-loggedwkout','');
	}else{
		method = 'addworkoutLog';
		type = type.replace('-logged','');
	}
	$("#FolderModal").html();
	$.ajax({	
		url : siteUrl+"search/getmodelTemplate/",
		data : {
			action : "previewworkout",
			method :  method,
			id : fid,
			foldid : foldid,
			date:date,
			modelType : "FolderModal",
			type : type
		},
		success : function(content){
			$("#FolderModal").html(content);
			$("#FolderModal").modal();
		}
	});
}
function confirmLogDate(date){
	addAssignWorkoutlogs(date,'','');
}
function saveLogWorkouts(){
	$('.errormsg').text('').hide();
	var title = $('#wkout_title').val();
	var color = $('#wrkoutcolor').val();
	var focus = $('#wkout_focus').val();
	if(title == ''){
		$('.errormsg').text('Workout Title should not empty').removeClass('hide').show();
		return false;
	}else if(color==""){
		$('.errormsg').text('Workout Color should not empty').removeClass('hide').show();
		return false;
	}else if(focus==""){
		$('.errormsg').text('Overall Focus should not empty').removeClass('hide').show();
		return false;
	}else if($('#scrollablediv-len ul#sTree3 li').length == 0){
		$('.errormsg').text('Please fill the below empty set and then try to add new set.').removeClass('hide').show();
		return false;
	}else{
		$('#FolderModalpopup').html();
		$.ajax({
			url : siteUrl+"search/getmodelTemplate",
			data : {
				action : 'workoutLogConfirm',
				method : 'action', 
				logid  : 0,
				foldid : '',
				id 	   : 0,
				type   : 'wkoutlog',
				goalOrder : 0,
				modelType : 'FolderModalpopup',
				intensity : $('#per_intent_hidden').val(),
				remarks  : $('#per_remarks_hidden').val()
			},
			success : function(content){
				$('#FolderModalpopup').html(content);
				$('#FolderModalpopup').modal();
			}
		});
		return false;
	}
	return false;
}
function confirmLogDetails(flag){
	var formData = $('#addAssignWorkouts').serializeArray();
	$('.errormsglogged').hide();
	$(formData).each(function(i, field){
		if(field.name == 'slider-1')
			$('#per_intent_hidden').val(field.value);	
		else if(field.name == 'note_wkout_remarks'){
			$('#per_remarks_hidden').val(field.value);
			$('#f_type').val(flag);
			$('#createNewworkout').submit();
		}
	});
}
function getTemplateOfNewLogAction(date){
	$('#FolderModalpopupOption').html();
	$.ajax({
		url : siteUrl+"search/getmodelTemplate",
		data : {
			action : 'addAssignWorkouts',
			method :  'addNewDate', 
			id : 0,
			foldid : 0,
			date   : date,
			type : 'wkoutLogCal',
			modelType : "FolderModalpopupOption"
		},
		success : function(content){
			$('#FolderModalpopupOption').html(content);
			$('#FolderModalpopupOption').modal();
		}
	});
}
function insertToWkout(fid, type, method, date, title){
	if(method == 'workout'){
		var typeXr 	= siteUrl_Front+'exercise/workoutrecord/';
		var href 	= '';
		if(type == 'wrkout')
			href = typeXr+'startwkout/'+fid+'?act=edit'+( method != 'workout' ? '&date='+date : '');
		else if(type == 'sample')
			href = typeXr+'startsample/'+fid+'?act=edit'+( method != 'workout' ? '&date='+date : '');
		else if(type == 'shared')
			href = typeXr+'startshare/'+fid+'?act=edit'+( method != 'workout' ? '&date='+date : '');
		else if(type == 'assigned')
			href = typeXr+'startassign/'+fid+'?act=edit'+( method != 'workout' ? '&date='+date : '');
		else if(type == 'wkoutlog')
			href = typeXr+'startwklog/'+fid+'?act=edit'+( method != 'workout' ? '&date='+date : '');
		if(href != '')
			window.location.href = href;
	}else if( method == 'logged' || method =='loggedwkout' || method == ''){
		getOptionsPopup(fid,type,method,date,title);
	}
}
function getOptionsPopup(fid, type, method, date, title){
	var method = (method == 'workout' ? 'addworkout' : ( method == 'logged' || method =='loggedwkout' ? 'addworkoutLog' :(method == 'assign' ? 'addworkoutAssign' : method) ) );
	$('#FolderModalpopupOption').html();
	$.ajax({
		url : siteUrl+"search/getmodelTemplate",
		data : {
			action : 'actionplanOptions',
			method : method, 
			id : fid,
			foldid : '',
			date   : date,
			type : type,
			modelType : "FolderModalpopupOption",
			title : title
		},
		success : function(content){
			$('#FolderModalpopupOption').html(content);
			$('#FolderModalpopupOption').modal();
		}
	});
}
function ChangeWkoutStatusWorkouts(flag,xrset){
	if($('div#itemsetnew_'+xrset).find('.navimgdet1').text()!='Click_to_Edit'){
		enablestatusButtons(xrset,flag - 1);
		closeModelwindow('FolderModalpopupOption');
	}else{
		alert('Please fill the above empty set and then try again.');
		return false;
	}
}
function confirmExerciseDetails(xrid,flag){
	var formData = $('#addAssignWorkouts').serializeArray();
	$('.errormsglogged').hide();
	var wkoutlogId = '0';
	if(parseInt(xrid) == xrid || xrid.indexOf('new_')>= 0){
		var selector = 'itemset_'+wkoutlogId+'_'+xrid;
		var per_remarks = $('#per_remarks_'+xrid);
		var mareked_st  = $('#markedstatus_'+xrid);
		var mareked_stus= $('#markstatus_'+xrid);
		var per_intent  = $('#per_intent_'+xrid);
	}else{
		var selector = 'itemsetnew_'+xrid;
		var per_remarks = $('#per_remarks_new_'+xrid);
		var mareked_st  = $('#markedstatus_new_'+xrid);
		var mareked_stus= $('#markstatus_new_'+xrid);
		var per_intent  = $('#per_intent_new_'+xrid);
	}
	$(formData).each(function(i, field){
		if(field.name == 'slider-1'){
			per_intent.val(field.value);
			matchesval = document.getElementById("note_wkout_intensity").options[field.value].text;
			if(matchesval != 'Select'){
				var regExp = /\(([^)]+)\)/;
				var matches = regExp.exec(matchesval);
				$('div#'+selector+' a.exercise_intent_div').html(matches[1]+' Perceived Intensity');
				$('div#'+selector+' div.navremarkdetails').removeClass('hide');
			}else
				$('div#'+selector+' a.exercise_intent_div').html('');
		}else if(field.name == 'note_wkout_remarks'){
			per_remarks.val(field.value);
			mareked_st.val(flag);
			if(flag == '1'){
				mareked_stus.removeAttr('class').attr('class', 'fa fa-check-square-o iconsize greenicon listoptionpoppopup pointers');
			}else{
				mareked_stus.removeAttr('class').attr('class', 'fa fa-minus-square-o iconsize pinkicon listoptionpoppopup pointers');
			}
			$('div#'+selector+' a.exercise_remarks_div').html(field.value);
			if(field.value != '' && $('div#'+selector+' div.navremarkdetails').hasClass('hide'))
				$('div#'+selector+' div.navremarkdetails').removeClass('hide');
		}
	});
	closeModelwindow('myOptionsModalExerciseRecord');
	closeModelwindow('FolderModalpopupOption');
}
function changeWkoutStatusExcise(xrset,selector){
	if(parseInt(xrset) == xrset || xrset.indexOf('new_')>= 0){
		var intensity  = $('#per_intent_'+xrset).val();
		var remarks    = $('#per_remarks_'+xrset).val();
		var flag       = $('#markedstatus_'+xrset).val();
		var mainDiv    = $('div#itemset_'+$('#wkout_log_id').val()+'_'+xrset);
	}else{
		var intensity  = $('#per_intent_new_'+xrset).val();
		var remarks	   = $('#per_remarks_new_'+xrset).val();
		var flag       = $('#markedstatus_new_'+xrset).val();
		var mainDiv    = $('div#itemsetnew_'+xrset);
	}
	if(!$(selector).attr('disabled')){
		if(mainDiv.find('.navimgdet1').text()!='Click_to_Edit'){
			$('#FolderModalpopupOption').html();
			$.ajax({
				url : siteUrl+"search/getmodelTemplate",
				data : {
					action : 'workoutLogConfirm',
					method : 'action',
					logid  : '0',
					foldid : xrset,
					id 	   : '',
					type   : 'loggedexerciseconf',
					goalOrder : flag,
					modelType : 'FolderModalpopupOption',
					intensity : intensity,
					remarks  : remarks,
				},
				success : function(content){
					$('#FolderModalpopupOption').html(content);
					$('#FolderModalpopupOption').modal();
				}
			});
		}else{
			alert('Please fill the above empty set and then try again.');
			return false;
		}
	}
	return false;
}
function skipExerciseNotes(xrid,flag,model){
	if(parseInt(xrid) == xrid || xrid.indexOf('new_')>= 0){
		var mareked_st  = $('#markedstatus_'+xrid);
		var mareked_stus= $('#markstatus_'+xrid);
	}else{
		var mareked_st  = $('#markedstatus_new_'+xrid);
		var mareked_stus= $('#markstatus_new_'+xrid);
	}
	mareked_stus.removeAttr('class').attr('class', 'fa fa-minus-square-o iconsize pinkicon listoptionpoppopup pointers');
	mareked_st.val('2');
	closeModelwindow(model);
}
function insertFromRelatedToXrSet(oldinsertId , unit_id){
	var xrtitleVal  = $('#popup_hidden_exerciseset_title_opt'+unit_id).val();
	var xrimg    = $('#popup_hidden_exerciseset_image_opt'+unit_id).val();
	var wkout_id = '0';
	var selector = 'div#itemset_'+wkout_id+'_'+oldinsertId;
	if($(selector).length){
		var xrtag    = '#exercise_unit_'+oldinsertId;
		var xrtitle  = '#exercise_title_'+oldinsertId;
	}else{
		var selector = 'div#itemsetnew_'+oldinsertId;
		if($(selector).length){
			var xrtag    = '#exercise_unit_new_'+oldinsertId;
			var xrtitle  = '#exercise_title_new_'+oldinsertId;
		}else{
			var selector = 'div#itemset'+oldinsertId;
			if($(selector).length){
				var xrtag    = '#exercise_unit_'+oldinsertId;
				var xrtitle  = '#exercise_title_'+oldinsertId;
			}
		}
	}
	$(selector+' .navimgdet1').html('<b>'+xrtitleVal+'</b>');
	$(selector+' .navimage').removeAttr('onclick');
	$(selector+' '+xrtag).val(unit_id);
	$(selector+' '+xrtitle).val(xrtitleVal);
	if(xrimg != '')
		$(selector+' .navimage').html('<img width="75px;" src="'+xrimg+'"  class="img-responsive pointers" />');
	else
		$(selector+' .navimage').html('<i class="fa fa-file-image-o pointers" style="font-size:50px;">');
	$(selector+' .navimage').attr("onclick","getTemplateOfExerciseRecordAction('"+unit_id+"',this);");
	$('#myOptionsModalExerciseRecord_option').modal('hide');
	$('#myOptionsModalExerciseRecord').modal('hide');
	return true;
}
function gotoLogPage(page,Id){
	var selectedDate = $('input[name="selected_date"].min-date').val();
	if(typeof(selectedDate) =='undefined')
		var selectedDate = $('input[name="selected_date"].min-date-hidden').val();
	function pad(n){return n<10 ? '0'+n : n}
	var d = new Date(selectedDate);
	var selectedDateval = [pad(d.getDate()), pad(d.getMonth()+1), d.getFullYear()].join('-');
	window.location = siteUrl_Front+ "exercise/workoutlog/"+page+"/"+Id+"?act=edit&date="+selectedDateval;
}
function confirmOtherLogDate(date,id){
	$('#FolderModal').html();
	$.ajax({
		url : siteUrl+"search/getmodelTemplate/",
		data : {
			action : 'confirmAssignDate',
			method :  'action', 
			id : id,
			foldid : 0,
			date   : date,
			type   : 'dulicateWkoutLog',
			modelType : 'FolderModal',
		},
		success : function(content){
			$('#FolderModal').html(content);
			$('#FolderModal').modal();
		}
	});
}
function checkTitleExist(selector){
	if($('div#itemsetnew_'+selector).length){
		curOrder = $('#goal_order_new_'+selector).val();
		$('input.seq_order_up').each(function(i, field){
			if(curOrder < field.value){
				inputId = $(this).attr('id');
				$('input#'+inputId).val(field.value-1);
			}
		});
		$('div#itemsetnew_'+selector).parent('li').remove();
		$('#s_row_count_xr').val($('#scrollablediv-len ul#sTree3 li').length);
		enablePopupButtons();
	}
	return false;
}
function previewworkout(date,foldid,fid,type){
	$("#FolderModal").html();
	$.ajax({	
		url : siteUrl+"search/getmodelTemplate/",
		data : {
			action : "previewworkout",
			method :  "addworkout",
			id : fid,
			foldid : foldid,
			date:date,
			modelType : "FolderModal",
			type : type
		},
		success : function(content){
			$("#FolderModal").html(content);
			$("#FolderModal").modal();
		}
	});
}
function openBackwindow(myModel){
	addNewWorkoutOption();
}
function enablestatusButtons(xrid,flag){
	var curFlag 	= 0;
	var iconFlag 	= '';
	if(parseInt(xrid) == xrid || xrid.indexOf('new_')>= 0)
		var xrid = xrid;
	else
		var xrid = 'new_'+xrid;
	if(flag == '1' || flag == '0')
		curFlag = parseInt(flag) + 1;
	else if(flag == '2')
		curFlag = parseInt(flag) % 2;
	if(curFlag == '1')
		iconFlag = ' fa-check-square-o greenicon pointers ' ;
	else if(curFlag == '2')
		iconFlag = ' fa-minus-square-o pinkicon pointers ';
	console.log(curFlag+'--->'+iconFlag);
	if(curFlag == '1' || curFlag == '2'){
		$('div#checkboxmark_'+xrid).html('<i onclick="enablestatusButtons('+"'"+xrid+"'"+','+"'"+curFlag+"'"+');" class="fa '+iconFlag+' iconsize listoptionpopcheck" ></i>');
		changeTosaveIcon();
	}else{
		$('div#checkboxmark_'+xrid).html('<label><input onclick="enablestatusButtons('+"'"+xrid+"'"+','+"'"+curFlag+"'"+');" data-role="none" data-ajax="false"  type="checkbox" class="checkhiddenstatus" name="exercisestatus[]" value="'+xrid+'"><span class="cr checkbox-circle" style="border-radius: 20%;"><i class="cr-icon fa fa-check"></i></span></label>');
		changeTosaveIcon();
	}
	if(parseInt(xrid) == xrid || xrid.indexOf('new_')>= 0){
		var mareked_st  = $('#markedstatus_'+xrid);
	}else{
		var mareked_st  = $('#markedstatus_new_'+xrid);
	}
	mareked_st.val(curFlag);
}
function addWorkoutLogNotes(flag, xrset){
	if(parseInt(xrset) == xrset || xrset.indexOf('new_')>= 0){
		var intensity  = $('#per_intent_'+xrset).val();
		var remarks    = $('#per_remarks_'+xrset).val();
		var notesFlag  = $('#hide_notes_set_'+xrset).val();
		var flag       = $('#markedstatus_'+xrset).val();
		var mainDiv    = $('div#itemset_'+$('#wkout_log_id').val()+'_'+xrset);
	}else{
		var intensity  = $('#per_intent_new_'+xrset).val();
		var remarks	   = $('#per_remarks_new_'+xrset).val();
		var notesFlag  = $('#hide_notes_set_new_'+xrset).val();
		var flag       = $('#markedstatus_new_'+xrset).val();
		var mainDiv    = $('div#itemsetnew_'+xrset);
	}
	if(mainDiv.find('.navimgdet1').text()!='Click_to_Edit' && notesFlag == '0'){
		$('#myOptionsModalOpt').html();
		$.ajax({
			url : siteUrl+"search/getmodelTemplate",
			data : {
				action : 'workoutLogConfirm',
				method : 'action',
				logid  : '0',
				foldid : xrset,
				id 	   : '',
				type   : 'loggedexerciseconf',
				goalOrder : flag,
				modelType : 'myOptionsModalOpt',
				intensity : intensity,
				remarks  : remarks,
			},
			success : function(content){
				$('#myOptionsModalOpt').html(content);
				$('#myOptionsModalOpt').modal();
			}
		});
	}else{
		alert('Please fill the above empty set and then try again.');
		return false;
	}
	return false;
}
$(document).on('click', 'div#FolderModal a.datadetail', function(e){
	e.stopPropagation();
	var selectorId = $(this).parentNth(8).attr('data-id');
	selectorId = selectorId.replace('new_','');
	if($(this).hasClass('exercise_repetitions_div'))
		editWorkoutRecord(selectorId,'edit#openlink-reps');
	else if($(this).hasClass('exercise_time_div'))
		editWorkoutRecord(selectorId,'edit#openlink-time');
	else if($(this).hasClass('exercise_distance_div'))
		editWorkoutRecord(selectorId,'edit#openlink-dist');
	else if($(this).hasClass('exercise_resistance_div'))
		editWorkoutRecord(selectorId,'edit#openlink-resist');
	else if($(this).hasClass('exercise_rate_div'))
		editWorkoutRecord(selectorId,'edit#openlink-rate');
	else if($(this).hasClass('exercise_angle_div'))
		editWorkoutRecord(selectorId,'edit#openlink-angle');
	else if($(this).hasClass('exercise_innerdrive_div'))
		editWorkoutRecord(selectorId,'edit#openlink-int');
	else if($(this).hasClass('exercise_rest_div'))
		editWorkoutRecord(selectorId,'edit#openlink-rest');
});
$(document).on('click','div#preview-exercise .allow-edit', function(e){
	$('#confirm').modal();
});
function closeOptionwindow(){
	closeModelwindow('FolderModal');
}
/*opens option modal for create a xr-rec*/
function createExercise(xrid, type){
	xrLibCreateExercise();
}
/*opens xr filter modal for create a xr-rec*/
function createExerciseFromXrLibrary(type){
	$.ajax({
		url : siteUrl+"search/getmodelTemplate/",
		data : {
			action : 'exerciseLibrary',
			requestFrom:'dashboard'
		},
		success : function(content){
			$('#exerciselib-template').remove();
			$('body #wrap-index').append(content);
			if($('#exerciselib-model').length){
				setTimeout(function(){
					if(type != ''){
						$('#myOptionsModalExerciseRecord').modal('hide');
						$('#ref-flag').val(type);
						$('#act-flag').val('exercise');
						$('#exerciselib-model button.xrliboption').addClass('hide');
						$('#xr_filter_toggle').trigger('click');
					}
					$('#exerciselib-model').modal();
					$('#exerciselib-model').on('shown.bs.modal', function() {
						setTimeout(function(){
							setDynamicHeight();
						}, 200);
					});
				}, 200);
			};
		}
	});
}
/*opens the xr-rec create/edit modal*/
function createNewExercise(opt) {
	if(opt){
	}else{
		opt =false;
	}
	if($('#xrcisesaveopt-modal').is(':visible')){
		$('#xrcisesaveopt-modal').modal('hide');
	}
	if(opt=='edit'){
		xrrecid = $('#xrRecInsertForm #xrid').val();
		setTimeout(function(){
			$.post(siteUrl + 'ajax/ajaxInsertActivityfeed', {'actid': xrrecid, 'method': 'opened', 'type': 'exercise'}, function(){});
		}, 200);
	}else if(opt=='reset'){
		addreq = $('#xrRecInsertForm #xrid').attr('data-addtype');
		xrrecid = $('#xrRecInsertForm #xrid').attr('data-addid');
	}else{
		xrrecid = '';
	}
	$.ajax({
		url : siteUrl+"ajax/getAjaxExerciseCreateHtml",
		type: 'post',
		data : {
			xrid : '',
			action : 'createExercise',
			requestFrom: 'dashboard',
			actionFrom: 'exercise',
		},
		success : function(content){
			var ajaxData = JSON.parse(content);
			$('#exercisecreate-modal #xrRec-container').empty();
			if(ajaxData.content!=''){
				$('#exercisecreate-modal #xrRec-container').html(ajaxData.content);
				$('#exercisecreate-modal').modal();
			}
		}
	});
	$('#exercisecreate-modal').modal();
}
function insertToExerciseSet(xrId, type, image_url, title){
	getXrsetOptionsPopup(xrId, type, image_url, title);
}
function getXrsetOptionsPopup(fid, type, image_url, title){
	$('#preview-xr-modal-options').html();
	$.ajax({
		url : siteUrl+"search/getmodelTemplate",
		data : {
			action : 'actionplanOptions',
			method : 'options', 
			id : fid,
			foldid : '',
			type : type,
			modelType : "preview-xr-modal-options",
			title : title,
			requestFrom:'dashboard',
			actionFrom:'exercise'
		},
		success : function(content){
			$('#preview-xr-modal-options').html(content);
			$('#preview-xr-modal-options').modal();
		}
	});
}
function createCopyXrPopup(){
	if ($('.checkboxcolor label input[type="checkbox"]:checked').length > 0) {
		$('#mypopupModal').html();
		$.ajax({
			url: siteUrl + "search/getmodelTemplate",
			data: {
				action: 'xrsettoolbaraction',
				method: 'copy',
				id: '',
				foldid: '',
				modelType : "mypopupModal"
			},
			success: function(content) {
				$('#mypopupModal').html(content);
				$('#mypopupModal').modal();
			}
		});
	}
	return false;
}
function getTemplateOfExerciseSetActionBycreate(exerciseSetId, link) {
   $('#FolderModal').html();
   var wkoutid = 0;
   var goalOrder = $('div#itemsetnew_' + wkoutid + '_' + exerciseSetId + ' input#goal_order_new_' + wkoutid + '_' + exerciseSetId).val();
   $.ajax({
      url: siteUrl + "search/getmodelTemplate",
      data: {
         action: 'exercisesetaction',
         method: 'createNewWrkout',
         id: wkoutid,
         foldid: exerciseSetId,
         xrid: $('#exercise_unit_new_' + exerciseSetId).val(),
         modelType: 'FolderModal',
         goalOrder: goalOrder,
      },
      success: function(content) {
         $('#FolderModal').html(content);
         $('#FolderModal').modal();
      }
   });
}