$(document).ready(function(){
	$('.checkboxchoosen').hide();
	$('.titlechoosen').addClass("col-xs-7").removeClass("col-xs-5");
	$('.checkhidden').attr('checked',false);
});
function changeTosaveIcon(){
	$('#save-icon-button').html('<button class="btn" onclick="this.form.submit();" name="button" style="background-color:#fff" type="submit"><i class="fa fa-check-square-o" style="font-size:30px;"  data-toggle="collapse"></i></button>');
	$('.checkboxchoosen').show();
	$('.titlechoosen').addClass("col-xs-5").removeClass("col-xs-7");
	$('.editchoosenIconOne').hide();
	$('.editchoosenIconTwo').show();
};
function changeToorganizeIcon(){
	$('#save-icon-button').html('<i class="fa fa-navicon" style="font-size:30px;" class="navbar-toggle" onclick="changeTosaveIcon();" data-toggle="collapse" data-target=".navbar-collapse-actions"></i>');
	$('.checkboxchoosen').hide();
	$('.titlechoosen').addClass("col-xs-7").removeClass("col-xs-5");
	$('.editchoosenIconOne').show();
	$('.editchoosenIconTwo').hide();
};
function createFolderModel(fid,method,foldid){
	$('#FolderModal').html();
	$.ajax({
		url : siteUrl+"search/getmodelTemplate",
		data : {
			action : 'workoutFolder',
			method :  method,
			id : fid,
			foldid : foldid
		},
		success : function(content){
			$('#FolderModal').html(content);
			$('#FolderModal').modal();
		}
	});
}
function getworkoutpreview(wkoutId){
	$('#myModal').html();
	$.ajax({
		url : siteUrl+"search/getmodelTemplate",
		data : {
			action : 'previewworkout',
			method :  'previewshared', 
			id : wkoutId,
			foldid : '0',
			type : 'previewshared'
		},
		success : function(content){
			$('#myModal').html(content);
			$('#myModal').modal();
		}
	});
}
function getExercisepreviewOfDay(exerciseId, wkoutId){
	$('#FolderModal').html();
	$.ajax({
		url : siteUrl+"search/getmodelTemplate",
		data : {
			action : 'previewExerciseOfDay',
			method :  'preview',
			id : exerciseId,
			foldid : wkoutId
		},
		success : function(content){
			$('#FolderModal').html(content);
			$('#FolderModal').modal();
		}
	});
}
function getExerciseSetpreview(exerciseSetId, wkoutId){
	$('#FolderModal').html();
	$.ajax({
		url : siteUrl+"search/getmodelTemplate",
		data : {
			action : 'previewExercise',
			method :  'preview',
			id : exerciseSetId,
			foldid : wkoutId,
			type : 'share',
		},
		success : function(content){
			$('#FolderModal').html(content);
			$('#FolderModal').modal();
		}
	});
}
function getXrImageRecords(xrid){
	modalName = 'myOptionsModalExerciseRecord';
	$('#'+modalName).html();
	$.ajax({
		url : siteUrl+"search/getmodelTemplate",
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
function getTemplateOfExerciseRecordAction(exerciseSetId){
	$('#myOptionsModalExerciseRecord').html();
	$.ajax({
		url : siteUrl+"search/getmodelTemplate",
		data : {
			action : 'exerciserecordaction',
			method :  'action', 
			id : '0',
			foldid : exerciseSetId,
			modelType : 'myOptionsModalExerciseRecord',
			allowTag : true
		},
		success : function(content){
			$('#myOptionsModalExerciseRecord').html(content);
			$('#myOptionsModalExerciseRecord').modal();
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
function getTemplateOfWorkoutAction(wkoutId, wksid, title) {
	$('#myModal').html('');
	$.ajax({
	  url: siteUrl + "search/getmodelTemplate",
	  data: {
		 action: 'otherWorkoutAction',
		 method: 'action',
		 id: wkoutId,
		 foldid: wksid,
		 title: title,
		 type: 'shared'
	  },
	  success: function(content) {
		 $('#myModal').html(content);
		 $('#myModal').modal();
	  }
	});
}
function addAssignWorkouts(wkoutid) {
	$('#myModal').html();
	$.ajax({
	  url: siteUrl + "search/getmodelTemplate",
	  data: {
		 action: 'addAssignWorkouts',
		 method: 'shared',
		 id: wkoutid,
		 date: '',
		 type: 'wkoutAssignCal'
	  },
	  success: function(content) {
		 $('#myModal').html(content);
		 $('#myModal').modal();
	  }
	});
}
function doHideProcess(){
	if (confirm('Are you sure want to Delete this Workout record from Shared Workout Plans?')) {
		return true;
	}
	return false;
}
function enableExport(xrid,type){
	$('#FolderModal').html();
	$.ajax({
		url : siteUrl+"search/getmodelTemplate/",
		data : {
			action : 'exportActionOptions',
			method : 'action',
			id : xrid,
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
function confirmOtherLogDate(date,id){
	gotoLogPage('startshare',id,date)
}
function addAssignWorkoutsByDate(date,folderid, wkoutid, method){
	$('#myModal').html();
	$.ajax({
	  url: siteUrl + "search/getmodelTemplate",
	  data: {
		 action: 'addAssignWorkouts',
		 method: 'wkoutLogCal',
		 id: wkoutid,
		 date: $('#selected_date').val(),
		 type: 'shareWkoutLog'
	  },
	  success: function(content) {
		 $('#myModal').html(content);
		 $('#myModal').modal();
	  }
	});
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
function gotoLogPage(page,Id,date){
	var selectedDate = date;
	function pad(n){return n<10 ? '0'+n : n}
	var d = new Date(selectedDate);
	var selectedDateval = [pad(d.getDate()), pad(d.getMonth()+1), d.getFullYear()].join('-');
	var currLoc = window.location;
	var urlpathname = currLoc.pathname;
	var urlpath = urlpathname;
	urlpath.indexOf(1);
	urlpath.toLowerCase();
	urlpath = urlpath.split('/')[1];
	if(urlpath!='exercise'){
		var sitename = '/'+urlpath+'/';
	}else{
		var sitename = '/';
	}
	window.location = sitename+ "exercise/workoutlog/"+page+"/"+Id+"?act=edit&date="+selectedDateval;
}