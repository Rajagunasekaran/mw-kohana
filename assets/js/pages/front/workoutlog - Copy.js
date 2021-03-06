function selectcolor(selector) {
	selectedclr = $(selector).attr('class').split(' ').pop();
	$('.colorcircle').removeClass('activecircle');
	selectedid = $(selector,'.choosenclr').text();
	$(selector).addClass('activecircle');
	selectedclrnew = $('#wrkoutcolortext').attr('class').split(' ').pop();
	if(selectedclr != 'activecircle')
		$('#wrkoutcolortext').removeClass(selectedclrnew).addClass(selectedclr);
	$('#wrkoutcolor').val(selectedid);
	$('.navbar-collapse-colors').removeClass('in');
}
function changeTosaveIcon() {
	var elementsToRemove = [];
	for (var i = 0; i < $('div.modal-backdrop').length; i++) {
		if ($('div.modal-backdrop')) {
			elementsToRemove.push($('div.modal-backdrop')[i]);
		}
	}
	for (var i = 1; i < elementsToRemove.length; i++) {
		elementsToRemove[i].parentNode.removeChild(elementsToRemove[i]);
	}
	$('.editmode').removeClass('hide');
	var wkout_status = $('#wkout_status_hidden').val();
	var wkoutStatus = '';
	if(wkout_status == 1)
		wkoutStatus = 'fa-check-square-o greenicon';
	else if (wkout_status == 2)
		wkoutStatus = 'fa-minus-square-o pinkicon';
	else if (wkout_status == 3)
		wkoutStatus = 'fa-exclamation-circle';
	if(wkoutStatus != '')
		$('.save-icon-button-journal').html('<a data-ajax="false" class="btn" href="javascript:void(0)" style="background-color:#fff" onclick="return confirmPopup();" ><i class="fa '+wkoutStatus+'" style="font-size:30px;" ></i></a>');
	else
		$('.save-icon-button-journal').html('<a data-ajax="false" class="btn btn-default activedatacol" href="javascript:void(0)" style="background-color:#fff" onclick="return confirmPopup();" >more</a>');
	$('.titlechoosen').addClass("col-xs-5").removeClass("col-xs-7");
	$('.editchoosenIconOne').hide();
	$('.editchoosenIconTwo').show();
	if ($('.createworkout').hasClass("hide")) $('.createworkout').removeClass("hide");
	if ($('.showexercisetitleTwo').hasClass("hide")) {
		$('.showexercisetitleTwo').removeClass("hide");
		$('.showexercisetitleOne').addClass("hide");
	}
	if($('.assigndatetwo').hasClass("hide")){
		$('.assigndatetwo').removeClass("hide");
		$('.assigndateone').addClass("hide");
	}
	if($('.listoptionpop')){
		$('a.editchoosenIconTwo').addClass('hide');
		$('i.listoptionpop').removeClass('hide');
	}
	if($('.selectdropdownTwo').hasClass("hide")){
		$('.selectdropdownTwo').removeClass("hide");
		$('.selectdropdownOne').addClass("hide");
	}
	if($('.activateLink')){
		$('div.activateLink').addClass('pointers').removeClass('labelcol');
		$('div').removeClass('activateLink');
	}
	$('a.datadetail').removeClass("datacol");
	$('a.datadetail').addClass('activedatacol');
	if($('.sTreeBase')){
		$('.sTreeBase').attr('id', 'sTree2');
		$("ul#sTree2").sortable({
			tolerance: 'pointer',
			revert: 'invalid',
			cursor: "move",
			forceHelperSize: true,
			forcePlaceholderSize: true,
			placeholder: "sortableListsHint",
			axis: 'y',
			handle: '.panel-draggable',
			helper: function (e, li) {
				if(li.attr("data-inner-cnt") > 1){
					li.attr("data-inner-cnt", li.attr("data-inner-cnt") - 1);
					var li_new = li;
					var target = $(e.target), parentdiv = target.closest('div.navimgdet2');
					var lisetid = parentdiv.attr('id'), lisetelem = parentdiv;
					this.copyHelper = li_new.clone()
						.find('.navimgdet2#' + lisetid).each(function(){
							if ($(this).next('hr').length > 0)
			               $(this).next('hr').andSelf().remove();
			            else
			               $(this).prev('hr').andSelf().remove();
						}).end()
						.insertAfter(li_new);
					li.find('.navimgdet2').not('#' + lisetid).remove().end()
					.andSelf().find('hr').remove();
					return li.clone();
				}else{
					return li;
				}
			},
			stop: function (e, li) {
				if(li.item.attr("data-inner-cnt") > 1){
					this.copyHelper = null;
				}
				updateLiItems();
			}
		}).disableSelection();
	}
   $('div.innerpage').show();
}
function updateLiItems(){
	var dataArr = [];
	var dataArrNew = {};
	var prev = ''; var next = ''; var cur =''; 
	var results = {};
	var positions = {};
	$( "ul#sTree2 li" ).each(function(i, lival){
		if($.isNumeric( $( "ul#sTree2 li" ).eq(i).attr('data-id') ))
			dataArr.push($( "ul#sTree2 li" ).eq(i).attr('data-id'));
		else
			dataArr.push($( "ul#sTree2 li" ).eq(i).attr('data-title'));
	});
	dataArr.forEach(function(item, index) { 
		if ((index > 0 && dataArr[index - 1] == item) || (index < dataArr.length + 1 && dataArr[index + 1] == item)) {
			results[item] = (results[item] || 0) + 1;
			(positions[item] || (positions[item] = [])).push(index);
		}
	});
	$( "ul#sTree2 li" ).each(function(i, lival){
		var keyval = $( "ul#sTree2 li" ).eq(i).attr('data-id');
		var keytitle = $( "ul#sTree2 li" ).eq(i).attr('data-title');
		if(positions[keyval] != undefined && $.inArray(i,positions[keyval]) != '-1' && $.isNumeric( $( "ul#sTree2 li" ).eq(i).attr('data-id') )){
			var removedCount = 0;
			for(var keynew = 1 ; keynew < positions[keyval].length ; keynew++){
				$( "ul#sTree2 li" ).eq(positions[keyval][0]).find('.exercisesetdiv').append('<hr>');
				curele = $( "ul#sTree2 li" ).eq(positions[keyval][keynew - removedCount]).find('.exercisesetdiv').html();
				newOrder = $( "ul#sTree2 li" ).eq(positions[keyval][0]).find('input.seq_order_combine_up').val();
				oldOrder = $( "ul#sTree2 li" ).eq(positions[keyval][keynew - removedCount]).find('input.seq_order_combine_up').val();
				var re5 = new RegExp(oldOrder + '_', 'g');
				curele = curele.replace(re5, newOrder+'_');
				$( "ul#sTree2 li" ).eq(positions[keyval][0]).find('.exercisesetdiv').append(curele);
				$( "ul#sTree2 li" ).eq(positions[keyval][keynew - removedCount]).remove();
				removedCount++;
			}
			delete positions[keyval];
		}else if(positions[keytitle] != undefined && $.inArray(i,positions[keytitle]) != '-1'){
			var removedCount = 0;
			for(var keynew = 1 ; keynew < positions[keytitle].length ; keynew++){
				$( "ul#sTree2 li" ).eq(positions[keytitle][0]).find('.exercisesetdiv').append('<hr>');
				curele = $( "ul#sTree2 li" ).eq(positions[keytitle][keynew - removedCount]).find('.exercisesetdiv').html();
				newOrder = $( "ul#sTree2 li" ).eq(positions[keytitle][0]).find('input.seq_order_combine_up').val();
				oldOrder = $( "ul#sTree2 li" ).eq(positions[keytitle][keynew - removedCount]).find('input.seq_order_combine_up').val();
				var re5 = new RegExp(oldOrder + '_', 'g');
				curele = curele.replace(re5, newOrder+'_');
				$( "ul#sTree2 li" ).eq(positions[keytitle][0]).find('.exercisesetdiv').append(curele);
				$( "ul#sTree2 li" ).eq(positions[keytitle][keynew - removedCount]).remove();
				removedCount++;
			}
			delete positions[keytitle];
		}
	});
	var curEle = replaceval = ''; var curOrder = replaceId = 1;
	var oldcombineorder = 0;
	$( "ul#sTree2 li" ).each(function(i, lival){
		var oldOrder = $( "ul#sTree2 li" ).eq(i).find('.seq_order_combine_up').val();
		var unitId = $( "ul#sTree2 li" ).eq(i).attr('data-id');
		oldcombineorder = i+1;
		$(lival).find('.seq_order_combine_up').val(oldcombineorder);
		livalId = $( "ul#sTree2 li" ).eq(i).attr('id').replace('_'+oldOrder+'_','_'+oldcombineorder+'_');
		$(lival).attr('id',livalId);
		$(lival).attr('data-orderval',i+1);
		$(lival).attr('data-inner-cnt',$( "ul#sTree2 li" ).eq(i).find('div.navimgdet2').length);
		if($( "ul#sTree2 li" ).eq(i).find('div.navimgdet2').length >1)
			$(lival).attr('data-module','item_sets');
		else
			$(lival).attr('data-module','item_set');
		
		var inlineString = $(lival).html();
		var re1 = new RegExp("_" + oldOrder + '_', 'g');
		inlineString = inlineString.replace(re1, '_'+oldcombineorder+'_');
		var re2 = new RegExp('"'+oldOrder + '_', 'g');
		inlineString = inlineString.replace(re2, '"'+oldcombineorder+'_');
		var re3 = new RegExp("'"+oldOrder + '_', 'g');
		inlineString = inlineString.replace(re3, "'"+oldcombineorder+'_');
		var re4 = new RegExp(oldOrder + '_' + unitId, 'g');
		inlineString = inlineString.replace(re4, oldcombineorder+'_'+unitId);
		$( "ul#sTree2 li" ).eq(i).html(inlineString);
		$( "ul#sTree2 li" ).eq(i).find('div.navimage').removeAttr("onclick");
		if($.isNumeric(unitId))
			$( "ul#sTree2 li" ).eq(i).find('div.navimage').attr("onclick", "getTemplateOfExerciseRecordAction('" + oldcombineorder + '_' + unitId + "',this,'" + oldcombineorder + "');");
	});
	$("ul#sTree2 li div.navimgdet2").each(function(i, item) {
		$(item).find('.seq_order_up').val(i+1);
	});
	$( "ul#sTree2 li" ).each(function(i, lival){
		var newadddiv = '';
		$( "ul#sTree2 li" ).eq(i).find('.exercisesetdiv div.navimgdet2').each(function(x,divimage){
			newadddiv = newadddiv.concat($(divimage).attr('data-id')+',');
		});
		litagId = $( "ul#sTree2 li" ).eq(i).attr('id');
		litagIdHiddenval = $('#'+litagId+'_hidden').val();
		$('#'+litagId+'_hidden').val(newadddiv.slice(0, -1));
	});
}
function confirmPopup() {
	if(createworkoutSubmit()){
		$('#myModal').html();
		if($('.listoptionpopcheck label input.checkhiddenstatus[type="checkbox"]').length > 0){
			var message = 'This record contains unmarked exercise sets. Saving will store this workout as incomplete until each exercise set is marked as completed or skipped. Would you like to continue?';
			$('#myModal').html();
			$.ajax({
				url : siteUrl+"search/getmodelTemplate",
				data : {
					action : 'custommessage',
					method : 'confirmpopup',
					id	   : '',
					foldid : '',
					type   : 'hide_confirm_xr_mark',
					modelType : 'myModal',
					remarks : message
				},
				success : function(content){
					if(content.trim() !=''){
						$('#myModal').html(content);
						$('#myModal').modal();
					}else{
						confirmPopupOk();
					}
				}
			});
			$('.listoptionpopcheck label span').css('border',"red 1px solid");
			return false;
		}
		$.ajax({
			url : siteUrl+"search/getmodelTemplate",
			data : {
				action : 'confirmWorkoutPopup',
				method : 'confirm',
				id : '',
				foldid : '',
				logid : $('#wkout_log_id').val(),
				type : 'logged',
			},
			success: function(content){
				$('#myModal').html(content);
				$('#myModal').modal();
			}
		});
	}
	return false;
}
function confirmPopupOk(){
	if(createworkoutSubmit()){
		$.ajax({
			url : siteUrl+"search/getmodelTemplate",
			data : {
				action : 'confirmWorkoutPopup',
				method : 'confirm',
				id : '',
				foldid : '',
				logid : $('#wkout_log_id').val(),
				type : 'logged',
			},
			success : function(content){
				$('#myModal').html(content);
				$('#myModal').modal();
			}
		});
	}
	return false;
}
function createworkoutSubmit(){
	if(!$('div#errormsgdivtag div.errormsgdiv').length){
		$('div#errormsgdivtag').append('<div class="banner errormsgdiv alert alert-danger" style="display:none;"><a data-ajax="false" data-role="none" href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><span class="errormsgspan"></span></div>');
	}
	$('div.errormsgdiv span.errormsgspan').text('');
	$('div.errormsgdiv').hide();
	var title = $('#wkout_title').val();
	var color = $('#wrkoutcolor').val();
	var focus = $('#wkout_focus').val();
	if(title == ''){
		$('div.errormsgdiv span.errormsgspan').text('Workout Title should not be empty');
		$('div.errormsgdiv').show();
	}else if(color=="" || color=="0"){
		$('div.errormsgdiv span.errormsgspan').text('Workout Color should not be empty');
		$('div.errormsgdiv').show();
	}else if(focus==""){
		$('div.errormsgdiv span.errormsgspan').text('Overall Focus should not be empty');
		$('div.errormsgdiv').show();
	}else if($('#scrollablediv-len ul#sTree2 li').length == 0){
		$('div.errormsgdiv span.errormsgspan').text('Please fill the below empty set and then try to add new set.');
		$('div.errormsgdiv').show();
	}else{
		return true;
	}
	return false;
}
function getInputDetailsByForm(dataForm, img_url, elem, type) {
   var dataval = {};
   dataval['img_url'] = img_url;
   dataval['goal_id'] = elem;
   dataval['goal_title'] = '';
   dataval['goal_unit_id'] = '';
   dataval['setdetails'] = {};
   if (type == 1) var requArr = {
      'exercise_title': "goal_title",'exercise_unit': "goal_unit_id",'exercise_resistance': "goal_resist",'exercise_unit_resistance': "goal_resist_id",'exercise_repetitions': "goal_reps",'exercise_time': "goal_time",'exercise_distance': "goal_dist",'exercise_unit_distance': "goal_dist_id",'exercise_rate': "goal_rate",'exercise_unit_rate': "goal_rate_id",'exercise_innerdrive': "goal_int_id",'exercise_angle': "goal_angle",'exercise_unit_angle': "goal_angle_id",'exercise_rest': "goal_rest",'exercise_remark': "goal_remarks",'primary_time': "primary_time",'primary_dist': "primary_dist",'primary_reps': "primary_reps",'primary_resist': "primary_resist",'primary_rate': "primary_rate",'primary_angle': "primary_angle",
      'primary_rest': "primary_rest",'primary_int': "primary_int",'removed_set': 'removed_set'
   };
   else var requArr = {
      'exercise_title_': "goal_title",'exercise_unit_': "goal_unit_id",'exercise_resistance_': "goal_resist",'exercise_unit_resistance_': "goal_resist_id",'exercise_repetitions_': "goal_reps",'exercise_time_': "goal_time",'exercise_distance_': "goal_dist",'exercise_unit_distance_': "goal_dist_id",'exercise_rate_': "goal_rate",'exercise_unit_rate_': "goal_rate_id",'exercise_innerdrive_': "goal_int_id",'exercise_angle_': "goal_angle",'exercise_unit_angle_': "goal_angle_id",'exercise_rest_': "goal_rest",'exercise_remark_': "goal_remarks",'primary_time': "primary_time",'primary_dist': "primary_dist",'primary_reps': "primary_reps",'primary_resist': "primary_resist",'primary_rate': "primary_rate",'primary_angle': "primary_angle",'primary_rest': "primary_rest",'primary_int': "primary_int"
   };
   var datavalcombine = {};
   $(dataForm).each(function(i, field) {
   	if(datavalcombine[field.keyval] == undefined){
   		datavalcombine[field.keyval] = {};
   	}
   	if(datavalcombine[field.keyval] != undefined){
   		for (var key in requArr) {
   			if (field.name.indexOf(key) >= 0) {
   				if (requArr[key] == 'goal_time') {
   					inputTimeArr = field.value.split(":");
   					datavalcombine[field.keyval]['goal_time_hh'] = inputTimeArr[0];
   					datavalcombine[field.keyval]['goal_time_mm'] = inputTimeArr[1];
   					datavalcombine[field.keyval]['goal_time_ss'] = inputTimeArr[2];
   				} else if (requArr[key] == 'goal_rest') {
   					inputRestArr = field.value.split(":");
   					datavalcombine[field.keyval]['goal_rest_mm'] = inputRestArr[0];
   					datavalcombine[field.keyval]['goal_rest_ss'] = inputRestArr[1];
   				} else if (requArr[key] == 'goal_title' || (requArr[key] == 'goal_unit_id' && (field.name.indexOf('exercise_unit[') == 0  || field.name.indexOf('exercise_unit_hidden') == 0 || field.name.indexOf('exercise_unit[') == 0))){
   					if(requArr[key] == 'goal_title')
   						dataval[requArr[key]] = field.value;
   					else
   						dataval[requArr[key]] = field.value.split('_')[1];
   				} else {
   					datavalcombine[field.keyval][requArr[key]] = field.value;
   				}
   			}
   		}
   		datavalcombine[field.keyval]['exercise_set_id'] = field.keyval;
   	}
   });
   dataval['setdetails'] = JSON.stringify(datavalcombine);
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

function getexercisesetTemplateAjaxEdit(elem, goalOrder, type) {
	$('div.createworkout div.border').removeClass('new-item');
   $('#myOptionsModalAjax').html();
   var wkoutlogid = $('#wkout_log_id').val();
   var dataForm = $('form#createExercise input').map(function() {
      return {
   		name: $(this).attr('name'),
   		value: $(this).attr('value'),
   		keyval: $(this).attr('data-keyval')
      }
	}).get();
   var goal_id = $('input#goal_id_hidden').val();
   var img_url = '';
   if ($('span#exerciselibimg img').length)
   	var img_url = $('span#exerciselibimg img').attr('src');
	if(type != 'title')
		$('#exerciselib-template').remove();
   var datavaljson = getInputDetailsByForm(dataForm, img_url, goal_id, 2);
   $.ajax({
      url: siteUrl + "search/getmodelTemplate",
      data: {
         action: 'workoutExercise',
         method: type,
         id: '',
         foldid: goal_id,
	    	xrsetid: elem,
		 	logid: wkoutlogid,
         modelType: 'myOptionsModalAjax',
         goalOrder: goalOrder,
         dataval: datavaljson,
		 	type: 'logged'
      },
      success: function(content) {
			$('#myOptionsModalAjax').html(content);
			$('.checkboxdrag[type="checkbox"]').bootstrapSwitch('size', 'small');
			$('.checkboxdrag[type="checkbox"]').bootstrapSwitch('onText', ' ');
			$('.checkboxdrag[type="checkbox"]').bootstrapSwitch('offText', ' ');
			if(type == 'title')
				$('#myOptionsModalAjax').modal('hidecustom');
			else
				$('#myOptionsModalAjax').modal();
      }
   });
	if(type == 'title')
		createExerciseFromXrLibrary('');	
}

function getexercisesetTemplate(thisobj, wkoutId, exerciseId, type){
	if(!$(thisobj).hasClass('activateLink')){
		if(type != 'title')
			$('#exerciselib-template').remove();
		$('#myModal').html();
		$.ajax({
			url : siteUrl+"search/getmodelTemplate",
			data : {
				action : 'workoutExercise',
				method : type,
				id : wkoutId,
				foldid : exerciseId,
				logid : $('#wkout_log_id').val(),
				type : 'logged',
			},
			success : function(content){
				$('#myModal').html(content);
				$('.checkboxdrag[type="checkbox"]').bootstrapSwitch('size','small');
				$('.checkboxdrag[type="checkbox"]').bootstrapSwitch('onText',' ');
				$('.checkboxdrag[type="checkbox"]').bootstrapSwitch('offText',' ');
				if(type == 'title')
					$('#myModal').modal('hidecustom');
				else
					$('#myModal').modal();
			}
		});
		if(type == 'title')
			createExerciseFromXrLibrary('');
	}
}

function getexercisesetTemplateAjax(thisobj, wkoutId, exerciseId, type){
	if(!$(thisobj).hasClass('activateLink')){
		if(type != 'title')
			$('#exerciselib-template').remove();
		$('#mypopupModal').html();
		$.ajax({
			url : siteUrl+"search/getmodelTemplate",
			data : {
				action : 'workoutExercise',
				method : type,
				id : wkoutId,
				foldid : exerciseId,
				modelType : 'mypopupModal',
				logid : $('#wkout_log_id').val(),
				type : 'logged',
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

function getpopupexercisesetTemplate(thisobj, wkoutId, exerciseId, type){
	if(!$(thisobj).hasClass('activateLink')){
		if(type != 'title')
			$('#exerciselib-template').remove();
		$('#mypopupModal').html();
		$.ajax({
			url : siteUrl+"search/getmodelTemplate",
			data : {
				action : 'workoutExercise',
				method : type,
				id : wkoutId,
				foldid : exerciseId,
				modelType : 'mypopupModal',
				logid : $('#wkout_log_id').val(),
				type : 'logged',
				ownEditWkFlag : $('#ownEditWkFlag').val()
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

function getWorkoutColorModel(wrkoutid){
	var dataval = {'wkout_title':$('#wrkoutname_hidden').val(),'color_title' : $('i.wrkoutcolor').attr('class').split(' ').pop(),'wrkoutcolor' : $('#wrkoutcolor').val()};
	$('#myModal').html();
	$.ajax({
		url : siteUrl+"search/getmodelTemplate",
		data : {
			action : 'workoutColor',
			method :  '',
			id	   : 0,
			foldid : 0,
			logid : $('#wkout_log_id').val(),
			type   : 'wkoutlog',
			dataval: dataval,
			date : $('#selected_date_hidden').val(),
		},
		success : function(content){
			$('#myModal').html(content);
			$('#myModal').modal();
		}
	});
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

function editWorkoutRecord(elem, method, setId) {
	if(setId == undefined)
		setId = '';
	$('div.createworkout div.border').removeClass('new-item');
	var addOptions = '';
	if(method.indexOf('#') >= 0){
		methodArr 	= method.split("#");
		addOptions 	= methodArr[1];
		method = method.replace('#'+addOptions,'');
	}
	if(method == '')
		method = 'edit';
	$('#myOptionsModal').html('');
	$('#myModal').html('');
	$('#myModal').modal('hide');
	var methodpreview = false;
	if($('.editmode').is(":visible") == true && method == "preview"){
		method = 'edit';
	}else if(method == "preview"){
		method = 'preview';
		methodpreview = true;
	}
	if(method == 'action-edit'){
		method = 'edit';
		var elementsToRemove = [];
		for (var i = 0; i < $('div.modal-backdrop').length; i++) {
			if ($('div.modal-backdrop')) {
				elementsToRemove.push($('div.modal-backdrop')[i]);
			}
		}
		for(var i = 1; i < elementsToRemove.length; i++) {
			elementsToRemove[i].parentNode.removeChild(elementsToRemove[i]);
		}
	}
	var goalOrder = '';
	var logid = $('#wkout_log_id').val();
	var datavaljson = '';
	if ($('li#itemSet_'+logid+'_'+elem).length && $('div#itemset_' + elem).length){
		var goal_id = elem;
		var img_url = '';
		if($('div#itemset_'+elem+" .activelink").attr("disabled"))
			return false;
		var goalOrder = $('div#itemset_'+elem+' input#goal_order_combine_'+elem).val();
		var dataForm = $('div#itemset_'+elem+' .navbarmenu input').map(function() {
			return {
				name: $(this).attr('name'),
				value: $(this).attr('value'),
				keyval: $(this).attr('data-keyval')
			}
		}).get();
		if ($('div#itemset_' + elem + ' .navimage img').length && $('div#itemset_' + elem + ' .navimage img').attr('src') != '') {
			var img_url = $('div#itemset_' + elem + ' .navimage img').attr('src').replace(siteUrl, '');
			img_url = img_url.replace('../../../', '');
		}
		$('li#itemSet_'+logid+'_'+elem+' div#itemset_'+elem+' .exercisesetdiv .navimgdet2').each(function(){
			var setid = $(this).attr('data-id');
			var new_setid = setid.replace(goalOrder + '_', '');
			if($('div#set_id_' + setId + ' input#markedstatus_'+new_setid).val() == '0'){
				$('div#set_id_' + setId + ' input#markedstatus_'+new_setid).val(1);
			}
		});
	}else if($('#itemset_'+elem).length){
		var goal_id = elem;
		var img_url ='';
		if($('div#itemset_'+elem+" .activelink").attr("disabled"))
			return false;
		var goalOrder = $('div#itemset_'+elem+' input#goal_order_'+elem).val();
		var dataForm = $('div#itemset_'+elem+' .navbarmenu input').map(function() {
			return {
				name: $(this).attr('name'),
				value: $(this).attr('value'),
				keyval: $(this).attr('data-keyval')
			}
		}).get();
		if ($('div#itemset_' + elem + ' .navimage img').length && $('div#itemset_' + elem + ' .navimage img').attr('src') != '') {
			var img_url = $('div#itemset_' + elem + ' .navimage img').attr('src').replace(siteUrl, '');
			img_url = img_url.replace('../../../', '');
		}
	}
	var datavaljson = getInputDetailsByForm(dataForm, img_url, goal_id, 1);
	$.ajax({
		url : siteUrl+"search/getmodelTemplate",
		data : {
			action : 'createExercise',
			method : method, //edit,create,preview
			id : '',
			foldid : elem,
			goalOrder : goalOrder,
			xrsetid : setId,
			logid : logid,
			type : 'logged',
			dataval : datavaljson,
			addOptions : addOptions,
		},
		success : function(content){
			$('#myOptionsModal').html(content);
			if ($('.xrsets-tab .nav-tabs.setlist-tab > li').length <= 1) {
            $('.xrsets-tab').hide();
         }
			$('#myOptionsModal').modal();
		}
	});
}
function getWorkoutsTitle(){
	if($('#e_tb_title').length){
		$('#e_tb_title').autocomplete({
			source : function(requete, reponse){
				$.ajax({
					url : siteUrl+"search/getajax",
					dataType : 'json',
					data : {
						action : 'workoutplan',
						title : $('#e_tb_title').val(),
						maxRows : 5
					},
					success : function(donnee){
						if(donnee){
							reponse($.map(donnee, function(item){
								return {
									wrkid: item.id,
									wrktitle: item.titre,
									color: item.color
								}
							}));
						}

					}
				});
			},
			select: function( event, ui ) {
			 	event.preventDefault();
				$('#e_tb_title').val(ui.item.wrktitle);
				$('#e_hid_titleid').val(ui.item.wrkid);
			},
		    focus: function(event, ui) {
		        event.preventDefault();
		        $("#e_tb_title").val(ui.item.wrktitle);
		    }
		}).data( "ui-autocomplete" )._renderItem = function( ul, item ) {
			if(item.color.length)
				return $( "<li>" ).append( "<div class='col-xl-6 colorchoosen'><i class='glyphicon' style='background-color:"+item.color+";'></i></div><div class='col-xl-6'>" + item.wrktitle + "</div>" ).appendTo( ul );
			else
				return $( "<li>" ).append( "<div class='col-xl-6'>" + item.wrktitle + "</div>" ).appendTo( ul );
      };
	}	
}

function createNewExerciseSet(selector, move, xroption) {
	if(xroption === undefined){
		$('div.createworkout div.border').removeClass('new-item');
		var wkoutlogid = $('#wkout_log_id').val();
		if ($('#scrollablediv-len li')) var last = $('#scrollablediv-len li').length;
		else var last = 0;
		if(move == 'last'){
			var goalorder = last + 1;
		}else if(move == 'down' || move == 'up'){
			var goalorder = $('div#itemset_' + selector).parent('li').index();
		}
		if ($('div.createworkout').find('.navimgdet1').text() != 'Click_to_Edit') {
			var valuexr = parseInt($('#newlyAddedXr').val()) + 1;
			var count = parseInt(last) + 1;
			$('#s_row_count').val(count);
			$('#s_row_count_flag').val(parseInt($('#s_row_count_flag').val()) + 1);
			$('#newlyAddedXr').val(valuexr);
			var li_element = '<li data-orderval="' + count + '" data-role="none" class="bgC4 hide" data-inner-cnt="1" data-title="" data-id="new_' + valuexr + '" data-module="item_set" id="itemSet_' + wkoutlogid + '_' + count + '_new_' + valuexr + '"><div id="itemset_' + count + '_new_' + valuexr + '" class="row createworkout"><input type="hidden" class="seq_order_combine_up" id="goal_order_combine_' + count + '_new_' + valuexr + '" name="goal_order_combine[' + count + '_new_' + valuexr + ']" value="' + count + '"><div class="mobpadding"><div class="border full new-item"><div class="checkboxchoosen col-xs-1 row-no-padding" style="display:none;"><div class="checkboxcolor" style="font-size:20px;"><label><input id="checkbox_col_new_'+ valuexr +'" class="checkhidden" data-role="none" data-ajax="false" value="' + count + '_new_' + valuexr + '" type="checkbox" onclick="enableButtons();" name="exercisesets[]"/><span class="cr checkbox-circle"><i class="cr-icon fa fa-check"></i></span></label></div></div><div class="col-xs-8 navdescrip row-no-padding"><div class="col-xs-4 activelink navimage datacol row-no-padding"><i class="fa fa-pencil-square" style="font-size:50px;"></i></div><div class="col-xs-8 pointers activelink datacol row-no-padding"><div class="activelink navimagedetails"><div class="navimgdet1" onclick="editWorkoutRecord(' + "'" + count + "_new_" + valuexr + "','create'" + ');"><b>Click_to_Edit</b></div><div class="exercisesetdiv"><div data-id="'+count+'_new_'+ valuexr +'" class="navimgdet2" id="set_id_'+count+'_new_'+ valuexr +'"><input type="hidden" class="seq_order_up" id="goal_order_new_' + valuexr + '" name="goal_order[new_' + valuexr +']" value="' + count + '"/><input type="hidden" id="goal_remove_new_' + valuexr + '" name="goal_remove[new_' + valuexr +']" value="0"/><div class="xrsets col-xs-9"><a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail activedatacol exercise_time_div"></a><a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail activedatacol exercise_distance_div"></a><a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail activedatacol exercise_repetitions_div"></a><a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail activedatacol exercise_resistance_div"></a><a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail activedatacol exercise_rate_div"></a><a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail activedatacol exercise_angle_div"></a><a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail activedatacol exercise_innerdrive_div"></a><a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail activedatacol exercise_rest_div"></a><br><a data-ajax="false" data-role="none" href="javascript:void(0);" style="text-decoration:none" class="datadetail exercise_intent_div activedatacol"></a><a data-ajax="false" data-role="none" href="javascript:void(0);" style="text-decoration:none" class="datadetail exercise_remarks_div activedatacol"></a></div><div class="navremarkdetails activelink" onclick="changeWkoutStatusExcise('+"'"+count+'_new_'+ valuexr+"'"+",'new_"+valuexr+"'"+',this);" style="clear: both;"><div id="'+"checkboxmark_new_"+valuexr+'" class="col-xs-1 checkboxmark listoptionpopcheck"><label><input onclick="enablestatusButtons('+"'new_"+valuexr+"'"+',0);" data-role="none" data-ajax="false"  type="checkbox" class="checkhiddenstatus" name="exercisestatus[]" value=""><span class="cr checkbox-circle" style="border-radius: 20%;"><i class="cr-icon fa fa-check"></i></span></label></div></div><div class="col-xs-1 navbarmenu row-no-padding"><a data-ajax="false" class="pointers editchoosenIconTwo hide" href="javascript:void(0);"><i class="fa fa-bars panel-draggable" style="font-size:25px;"></i></a><i id="markstatus_new_'+valuexr+'" class="fa fa-ellipsis-v iconsize listoptionpop" onclick="getTemplateOfExerciseSetAction(\''+ count + '_new_' + valuexr + '\',\'new_' + valuexr + '\',\'link\');"></i><input type="hidden" data-keyval="'+count+'_new_'+ valuexr +'" value="0" name="markedstatus[new_' + valuexr +']" id="markedstatus_new_' + valuexr + '"/><input type="hidden" data-keyval="'+count+'_new_'+ valuexr +'" value="0" name="edit_status[new_' + valuexr +']" id="edit_status_new_' + valuexr + '"/><input type="hidden" data-keyval="'+count+'_new_'+ valuexr +'" value="0" name="per_intent[new_' + valuexr +']" id="per_intent_new_' + valuexr + '"/><input type="hidden" data-keyval="'+count+'_new_'+ valuexr +'" value="0" name="per_remarks[new_' + valuexr +']" id="per_remarks_new_' + valuexr + '"/><input type="hidden" data-keyval="'+count+'_new_'+ valuexr +'" value="0" name="hide_notes_set[new_' + valuexr +']" id="hide_notes_set_new_' + valuexr + '"/><input type="hidden" data-keyval="'+count+'_new_'+ valuexr +'" id="exercise_title_new_' + valuexr + '" class="exercise_title_xr_hidden" name="exercise_title[new_' + valuexr +']" value=""/><input type="hidden" data-keyval="'+count+'_new_'+ valuexr +'" id="exercise_unit_new_' + valuexr + '" class="exercise_unit_xr_hidden" name="exercise_unit[new_' + valuexr +']" value="0"/><input type="hidden" data-keyval="'+count+'_new_'+ valuexr +'" id="exercise_resistance_new_' + valuexr + '" name="exercise_resistance[new_' + valuexr +']" value=""/><input type="hidden" data-keyval="'+count+'_new_'+ valuexr +'" id="exercise_unit_resistance_new_' + valuexr + '" name="exercise_unit_resistance[new_' + valuexr +']" value=""/><input type="hidden" data-keyval="'+count+'_new_'+ valuexr +'" id="exercise_repetitions_new_' + valuexr + '" name="exercise_repetitions[new_' + valuexr +']" value=""/><input type="hidden" data-keyval="'+count+'_new_'+ valuexr +'" id="exercise_time_new_' + valuexr + '" name="exercise_time[new_' + valuexr +']" value=""/><input type="hidden" data-keyval="'+count+'_new_'+ valuexr +'" id="exercise_distance_new_' + valuexr + '" name="exercise_distance[new_' + valuexr +']" value=""/><input type="hidden" data-keyval="'+count+'_new_'+ valuexr +'" id="exercise_unit_distance_new_' + valuexr + '" name="exercise_unit_distance[new_' + valuexr +']" value=""/><input type="hidden" data-keyval="'+count+'_new_'+ valuexr +'" id="exercise_rate_new_' + valuexr + '" name="exercise_rate[new_' + valuexr +']" value=""/><input type="hidden" data-keyval="'+count+'_new_'+ valuexr +'" id="exercise_unit_rate_new_' + valuexr + '" name="exercise_unit_rate[new_' + valuexr +']" value=""/><input type="hidden" data-keyval="'+count+'_new_'+ valuexr +'" id="exercise_innerdrive_new_' + valuexr + '" name="exercise_innerdrive[new_' + valuexr +']" value=""/><input type="hidden" data-keyval="'+count+'_new_'+ valuexr +'" id="exercise_angle_new_' + valuexr + '" name="exercise_angle[new_' + valuexr +']" value=""/><input type="hidden" data-keyval="'+count+'_new_'+ valuexr +'" id="exercise_unit_angle_new_' + valuexr + '" name="exercise_unit_angle[new_' + valuexr +']" value=""/><input type="hidden" data-keyval="'+count+'_new_'+ valuexr +'" id="exercise_rest_new_' + valuexr + '" name="exercise_rest[new_' + valuexr +']" value=""/><input type="hidden" data-keyval="'+count+'_new_'+ valuexr +'" id="exercise_remark_new_' + valuexr + '" name="exercise_remark[new_' + valuexr +']" value=""/><input type="hidden" data-keyval="'+count+'_new_'+ valuexr +'" id="primary_time_new_' + valuexr + '" class="exercise_priority_hidden" name="primary_time[new_' + valuexr +']" value=""/><input type="hidden" data-keyval="'+count+'_new_'+ valuexr +'" id="primary_dist_new_' + valuexr + '" class="exercise_priority_hidden" name="primary_dist[new_' + valuexr +']" value=""/><input type="hidden" data-keyval="'+count+'_new_'+ valuexr +'" id="primary_reps_new_' + valuexr + '" class="exercise_priority_hidden" name="primary_reps[new_' + valuexr +']" value=""/><input type="hidden" data-keyval="'+count+'_new_'+ valuexr +'" id="primary_resist_new_' + valuexr + '" class="exercise_priority_hidden" name="primary_resist[new_' + valuexr +']" value=""/><input type="hidden" data-keyval="'+count+'_new_'+ valuexr +'" id="primary_rate_new_' + valuexr + '" class="exercise_priority_hidden" name="primary_rate[new_' + valuexr +']" value=""/><input type="hidden" data-keyval="'+count+'_new_'+ valuexr +'" id="primary_angle_new_' + valuexr + '" class="exercise_priority_hidden" name="primary_angle[new_' + valuexr +']" value=""/><input type="hidden" data-keyval="'+count+'_new_'+ valuexr +'" id="primary_rest_new_' + valuexr + '" class="exercise_priority_hidden" name="primary_rest[new_' + valuexr +']" value=""/><input type="hidden" data-keyval="'+count+'_new_'+ valuexr +'" id="primary_int_new_' + valuexr + '" class="exercise_priority_hidden" name="primary_int[new_' + valuexr +']" value=""/></div></div></div><input id="itemSet_' + wkoutlogid + '_' + count + '_new_' + valuexr + '_hidden" value="'+count+'_new_'+ valuexr +'" type="hidden"></div></div></div></div></div></li>';
			if(move == 'down')
				$('#scrollablediv-len ul li:eq(' + goalorder + ')').after(li_element);
			else if(move == 'up')
				$('#scrollablediv-len ul li:eq(' + goalorder + ')').before(li_element);
			else
				$('#scrollablediv-len ul').append(li_element);
		  	if ($('#scrollablediv-len li').length > 3 && !$('#scrollablediv-len').hasClass('scrollablediv')) {
				$('#scrollablediv-len').addClass('scrollablediv');
		  	}
		  	if ($('#scrollablediv-len li').length == '1') $('.sTreeBase').show();
  			$("ul#sTree2 li div.navimgdet2").each(function(i, item) {
			 	$(item).find('.seq_order_up').val(i+1);
		  	});
		  	editWorkoutRecord(count + "_new_" + valuexr, "create");
	    }
	}else{
		var wkoutlogid = $('#wkout_log_id').val();
		var lielement = $('li#itemSet_'+wkoutlogid+'_'+selector);
		currOrder = $('li#itemSet_'+wkoutlogid+'_'+selector).attr('data-inner-cnt');
		unitId = $('li#itemSet_'+wkoutlogid+'_'+selector).attr('data-id');
		goalorder = $('div#itemset_'+selector+' input#goal_order_combine_'+selector).val();
		xrtitle = $('li#itemSet_'+wkoutlogid+'_'+selector+ ' div#set_id_'+xroption+ ' input.exercise_title_xr_hidden').val();
		$('li#itemSet_'+wkoutlogid+'_'+selector).attr('data-inner-cnt',parseInt(currOrder)+1);
		var divArray = [];
		var divArrayAll = [];
		$('div#itemset_'+selector+' div.exercisesetdiv div').each(function(i,item){
			if($(item).attr('id'))
				divArray.push($(item).attr('id'));
			divArrayAll.push($(item).attr('id'));
		});
		currPos = divArrayAll.indexOf('set_id_'+xroption);
		last	= divArray.length;
		
		if(move == 'last') var xrorder = last + 1;
		else if(move == 'down' || move == 'up') var xrorder = currPos + 1;
		
		var valuexr = parseInt($('#newlyAddedXr').val()) + 1;
		var count = goalorder;
		$('#newlyAddedXr').val(valuexr);
		var xr_element = (move == 'down' || move == 'last' ? '<hr class="hide">' : '')+'<div id="set_id_'+count+'_new_'+ valuexr +'" class="navimgdet2 hide" data-id="'+count+'_new_'+ valuexr +'"><input type="hidden" class="seq_order_up" id="goal_order_new_' + valuexr + '" name="goal_order[new_' + valuexr +']" value="' + count + '"/><input type="hidden" id="goal_remove_new_' + valuexr + '" name="goal_remove[new_' + valuexr +']" value="0"/><div class="xrsets col-xs-9"><a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail activedatacol exercise_time_div"></a><a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail activedatacol exercise_distance_div"></a><a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail activedatacol exercise_repetitions_div"></a><a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail activedatacol exercise_resistance_div"></a><a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail activedatacol exercise_rate_div"></a><a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail activedatacol exercise_angle_div"></a><a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail activedatacol exercise_innerdrive_div"></a><a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail activedatacol exercise_rest_div"></a><br><a data-ajax="false" data-role="none" href="javascript:void(0);" style="text-decoration:none" class="datadetail exercise_intent_div activedatacol"></a><a data-ajax="false" data-role="none" href="javascript:void(0);" style="text-decoration:none" class="datadetail exercise_remarks_div activedatacol"></a></div><div class="navremarkdetails activelink" onclick="changeWkoutStatusExcise('+"'"+count+'_'+unitId+"'"+",'new_"+valuexr+"'"+',this);" style="clear: both;"><div id="'+"checkboxmark_new_"+valuexr+'" class="col-xs-1 checkboxmark listoptionpopcheck"><label><input onclick="enablestatusButtons('+"'new_"+valuexr+"'"+',0);" data-role="none" data-ajax="false"  type="checkbox" class="checkhiddenstatus" name="exercisestatus[]" value=""><span class="cr checkbox-circle" style="border-radius: 20%;"><i class="cr-icon fa fa-check"></i></span></label></div></div><div class="col-xs-1 navbarmenu row-no-padding"><a data-ajax="false" class="pointers editchoosenIconTwo hide" href="javascript:void(0);"><i class="fa fa-bars panel-draggable" style="font-size:25px;"></i></a><i id="markstatus_new_'+valuexr+'" class="fa fa-ellipsis-v iconsize listoptionpop"></i><input type="hidden" data-keyval="'+count+'_new_'+ valuexr +'" value="0" name="markedstatus[new_' + valuexr +']" id="markedstatus_new_' + valuexr + '"/><input type="hidden" data-keyval="'+count+'_new_'+ valuexr +'" value="0" name="edit_status[new_' + valuexr +']" id="edit_status_new_' + valuexr + '"/><input type="hidden" data-keyval="'+count+'_new_'+ valuexr +'" value="0" name="per_intent[new_' + valuexr +']" id="per_intent_new_' + valuexr + '"/><input type="hidden" data-keyval="'+count+'_new_'+ valuexr +'" value="0" name="per_remarks[new_' + valuexr +']" id="per_remarks_new_' + valuexr + '"/><input type="hidden" data-keyval="'+count+'_new_'+ valuexr +'" value="0" name="hide_notes_set[new_' + valuexr +']" id="hide_notes_set_new_' + valuexr + '"/><input type="hidden" data-keyval="'+count+'_new_'+ valuexr +'" id="exercise_title_new_' + valuexr + '" class="exercise_title_xr_hidden" name="exercise_title[new_' + valuexr +']" value="'+xrtitle+'"/><input type="hidden" data-keyval="'+count+'_new_'+ valuexr +'" id="exercise_unit_new_' + valuexr + '" class="exercise_unit_xr_hidden" name="exercise_unit[new_' + valuexr +']" value="'+count+'_'+unitId+'"/><input type="hidden" data-keyval="'+count+'_new_'+ valuexr +'" id="exercise_resistance_new_' + valuexr + '" name="exercise_resistance[new_' + valuexr +']" value=""/><input type="hidden" data-keyval="'+count+'_new_'+ valuexr +'" id="exercise_unit_resistance_new_' + valuexr + '" name="exercise_unit_resistance[new_' + valuexr +']" value=""/><input type="hidden" data-keyval="'+count+'_new_'+ valuexr +'" id="exercise_repetitions_new_' + valuexr + '" name="exercise_repetitions[new_' + valuexr +']" value=""/><input type="hidden" data-keyval="'+count+'_new_'+ valuexr +'" id="exercise_time_new_' + valuexr + '" name="exercise_time[new_' + valuexr +']" value=""/><input type="hidden" data-keyval="'+count+'_new_'+ valuexr +'" id="exercise_distance_new_' + valuexr + '" name="exercise_distance[new_' + valuexr +']" value=""/><input type="hidden" data-keyval="'+count+'_new_'+ valuexr +'" id="exercise_unit_distance_new_' + valuexr + '" name="exercise_unit_distance[new_' + valuexr +']" value=""/><input type="hidden" data-keyval="'+count+'_new_'+ valuexr +'" id="exercise_rate_new_' + valuexr + '" name="exercise_rate[new_' + valuexr +']" value=""/><input type="hidden" data-keyval="'+count+'_new_'+ valuexr +'" id="exercise_unit_rate_new_' + valuexr + '" name="exercise_unit_rate[new_' + valuexr +']" value=""/><input type="hidden" data-keyval="'+count+'_new_'+ valuexr +'" id="exercise_innerdrive_new_' + valuexr + '" name="exercise_innerdrive[new_' + valuexr +']" value=""/><input type="hidden" data-keyval="'+count+'_new_'+ valuexr +'" id="exercise_angle_new_' + valuexr + '" name="exercise_angle[new_' + valuexr +']" value=""/><input type="hidden" data-keyval="'+count+'_new_'+ valuexr +'" id="exercise_unit_angle_new_' + valuexr + '" name="exercise_unit_angle[new_' + valuexr +']" value=""/><input type="hidden" data-keyval="'+count+'_new_'+ valuexr +'" id="exercise_rest_new_' + valuexr + '" name="exercise_rest[new_' + valuexr +']" value=""/><input type="hidden" data-keyval="'+count+'_new_'+ valuexr +'" id="exercise_remark_new_' + valuexr + '" name="exercise_remark[new_' + valuexr +']" value=""/><input type="hidden" data-keyval="'+count+'_new_'+ valuexr +'" id="primary_time_new_' + valuexr + '" class="exercise_priority_hidden" name="primary_time[new_' + valuexr +']" value=""/><input type="hidden" data-keyval="'+count+'_new_'+ valuexr +'" id="primary_dist_new_' + valuexr + '" class="exercise_priority_hidden" name="primary_dist[new_' + valuexr +']" value=""/><input type="hidden" data-keyval="'+count+'_new_'+ valuexr +'" id="primary_reps_new_' + valuexr + '" class="exercise_priority_hidden" name="primary_reps[new_' + valuexr +']" value=""/><input type="hidden" data-keyval="'+count+'_new_'+ valuexr +'" id="primary_resist_new_' + valuexr + '" class="exercise_priority_hidden" name="primary_resist[new_' + valuexr +']" value=""/><input type="hidden" data-keyval="'+count+'_new_'+ valuexr +'" id="primary_rate_new_' + valuexr + '" class="exercise_priority_hidden" name="primary_rate[new_' + valuexr +']" value=""/><input type="hidden" data-keyval="'+count+'_new_'+ valuexr +'" id="primary_angle_new_' + valuexr + '" class="exercise_priority_hidden" name="primary_angle[new_' + valuexr +']" value=""/><input type="hidden" data-keyval="'+count+'_new_'+ valuexr +'" id="primary_rest_new_' + valuexr + '" class="exercise_priority_hidden" name="primary_rest[new_' + valuexr +']" value=""/><input type="hidden" data-keyval="'+count+'_new_'+ valuexr +'" id="primary_int_new_' + valuexr + '" class="exercise_priority_hidden" name="primary_int[new_' + valuexr +']" value=""/></div></div>'+(move == 'up' ? '<hr class="hide">' : '');
		if(move == 'down')
			$(xr_element).insertAfter('div#itemset_'+selector+' div.exercisesetdiv div#set_id_'+xroption);
		else if(move == 'up')
			$(xr_element).insertBefore('div#itemset_'+selector+' div.exercisesetdiv div#set_id_'+xroption);
		else
			$('div#itemset_'+selector+' div.exercisesetdiv').append(xr_element);
		$('div#itemset_'+selector+' div.exercisesetdiv div#set_id_'+count+'_new_'+ valuexr +'  .listoptionpop').attr("onclick", "getTemplateOfExerciseSetAction('" + selector + "','"+'new_'+ valuexr + "','link');");
		var setidSelector= $('div#itemset_' + selector +' input#itemSet_' + wkoutlogid + '_' + selector+'_hidden');
		var setIds = setidSelector.val();
		setidSelector.val(setIds + ',' + count+'_new_'+ valuexr);
		$("ul#sTree2 li div.navimgdet2").each(function(i, item) {
			$(item).find('.seq_order_up').val(i+1);
		});
		editWorkoutRecord(selector, 'create', count + "_new_" + valuexr);
	}
   return false;
}

function createNewExerciseSetOld(selector, move, xroption){
	$('div.createworkout div.border').removeClass('new-item');
	var wkoutid = $('#wkout_log_id').val();
	if ($('#scrollablediv-len li')) var last = $('#scrollablediv-len li').length;
	else var last = 0;
	if(move == 'last'){
	   var goalorder = last + 1;
	}else if(move == 'down' || move == 'up'){
		if (selector.indexOf("new_") < 0) {
			if ($('div#itemset_' + wkoutid + '_' + selector).length) {
				var goalorder = $('div#itemset_' + wkoutid + '_' + selector).parent('li').index();
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
	if($('div.createworkout').find('.navimgdet1').text()!='Click_to_Edit'){
		var count = parseInt(last) + 1;
		$('#s_row_count').val(count);
		$('#s_row_count_flag').val(parseInt($('#s_row_count_flag').val()) + 1);
		var li_element = '<li id="itemSetnew_'+wkoutid+'_0_'+count+'" class="bgC4 item_add_wkout_noclick hide" data-module="item_set_new" data-id="new_'+wkoutid+'_'+count+'"><div class="row createworkout" id="itemsetnew_'+wkoutid+'_'+count+'"><input type="hidden" class="seq_order_up" id="goal_order_new_'+wkoutid+'_'+count+'" name="goal_order_new[]" value="'+count+'"/><input type="hidden" id="goal_remove_new_'+wkoutid+'_'+count+'" name="goal_remove_new[]" value="0"/><div class="mobpadding"><div class="border full"><div class="checkboxchoosen col-xs-1 row-no-padding" style="display:none;"><div class="checkboxcolor" style="font-size:20px;"><label><input class="checkhidden" data-role="none" data-ajax="false" value="new_'+wkoutid+'_'+count+'" type="checkbox" onclick="enableButtons();" name="exercisesets[]"/><span class="cr checkbox-circle"><i class="cr-icon fa fa-check"></i></span></label></div></div><div class="col-xs-8 navdescrip row-no-padding"><div class="col-xs-4 activelink navimage"><i class="fa fa-pencil-square" style="font-size:50px;"></i></div><div class="col-xs-8 pointers activelink datacol row-no-padding"><div class="activelink navimagedetails" onclick="editWorkoutRecord('+"'new_"+wkoutid+"_"+count+"','create'"+');"><div class="navimgdet1"><b>Click_to_Edit</b></div><div class="navimgdet2"><a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail activedatacol exercise_time_div"></a><a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail activedatacol exercise_distance_div"></a><a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail activedatacol exercise_repetitions_div"></a><a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail activedatacol exercise_resistance_div"></a></div><div class="navimgdet3"><a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail activedatacol exercise_rate_div"></a>&nbsp;<a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail activedatacol exercise_angle_div"></a>&nbsp;<a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail activedatacol exercise_innerdrive_div"></a>&nbsp;<a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail activedatacol exercise_rest_div"></a></div></div><div class="navremarkdetails activelink hide" onclick="changeWkoutStatusExcise('+"'new_"+wkoutid+"_"+count+"'"+',this);" style="clear: both;"><div class="navimgdet4" ><a data-ajax="false" data-role="none" style="text-decoration:none" href="javascript:void(0);" class="datadetail datacol exercise_intent_div"></a></div><div class="navimgdet5"><a data-ajax="false" style="text-decoration:none" data-role="none" href="javascript:void(0);" class="datadetail datacol exercise_remarks_div"></a></div></div></div></div><div id="'+"checkboxmark_new_"+wkoutid+"_"+count+'" class="col-xs-1 checkboxmark listoptionpopcheck"><label><input onclick="enablestatusButtons('+"'new_"+wkoutid+"_"+count+"'"+',0);" data-role="none" data-ajax="false"  type="checkbox" class="checkhiddenstatus" name="exercisestatus[]" value=""><span class="cr checkbox-circle" style="border-radius: 20%;"><i class="cr-icon fa fa-check"></i></span></label></div><div class="col-xs-1 navbarmenu row-no-padding"><a data-ajax="false" class="pointers editchoosenIconTwo hide" href="javascript:void(0);"><i class="fa fa-bars panel-draggable" style="font-size:25px;"></i></a><i id="markstatus_new_'+wkoutid+'_'+count+'" class="fa fa-ellipsis-v iconsize listoptionpop" onclick="getTemplateOfExerciseSetAction('+"'"+wkoutid+"_"+count+"','link'"+');"></i><input type="hidden" value="0" name="markedstatus_new[]" id="markedstatus_new_'+wkoutid+'_'+count+'"/><input type="hidden" value="0" name="edit_status_new[]" id="edit_status_new_'+wkoutid+'_'+count+'"/><input type="hidden" value="0" name="per_intent_new[]" id="per_intent_new_'+wkoutid+'_'+count+'"/><input type="hidden" value="0" name="per_remarks_new[]" id="per_remarks_new_'+wkoutid+'_'+count+'"/><input type="hidden" value="0" name="hide_notes_set_new[]" id="hide_notes_set_new_'+wkoutid+'_'+count+'"/><input type="hidden" id="exercise_title_new_'+wkoutid+'_'+count+'" name="exercise_title_new[]" value=""/><input type="hidden" id="exercise_unit_new_'+wkoutid+'_'+count+'" name="exercise_unit_new[]" value="0"/><input type="hidden" id="exercise_resistance_new_'+wkoutid+'_'+count+'" name="exercise_resistance_new[]" value=""/><input type="hidden" id="exercise_unit_resistance_new_'+wkoutid+'_'+count+'" name="exercise_unit_resistance_new[]" value=""/><input type="hidden" id="exercise_repetitions_new_'+wkoutid+'_'+count+'" name="exercise_repetitions_new[]" value=""/><input type="hidden" id="exercise_time_new_'+wkoutid+'_'+count+'" name="exercise_time_new[]" value=""/><input type="hidden" id="exercise_distance_new_'+wkoutid+'_'+count+'" name="exercise_distance_new[]" value=""/><input type="hidden" id="exercise_unit_distance_new_'+wkoutid+'_'+count+'" name="exercise_unit_distance_new[]" value=""/><input type="hidden" id="exercise_rate_new_'+wkoutid+'_'+count+'" name="exercise_rate_new[]" value=""/><input type="hidden" id="exercise_unit_rate_new_'+wkoutid+'_'+count+'" name="exercise_unit_rate_new[]" value=""/><input type="hidden" id="exercise_innerdrive_new_'+wkoutid+'_'+count+'" name="exercise_innerdrive_new[]" value=""/><input type="hidden" id="exercise_angle_new_'+wkoutid+'_'+count+'" name="exercise_angle_new[]" value=""/><input type="hidden" id="exercise_unit_angle_new_'+wkoutid+'_'+count+'" name="exercise_unit_angle_new[]" value=""/><input type="hidden" id="exercise_rest_new_'+wkoutid+'_'+count+'" name="exercise_rest_new[]" value=""/><input type="hidden" id="exercise_remark_new_'+wkoutid+'_'+count+'" name="exercise_remark_new[]" value=""/><input type="hidden" id="primary_time_new_'+wkoutid+'_'+count+'" class="exercise_priority_hidden" name="primary_time_new[]" value=""/><input type="hidden" id="primary_dist_new_'+wkoutid+'_'+count+'" class="exercise_priority_hidden" name="primary_dist_new[]" value=""/><input type="hidden" id="primary_reps_new_'+wkoutid+'_'+count+'" class="exercise_priority_hidden" name="primary_reps_new[]" value=""/><input type="hidden" id="primary_resist_new_'+wkoutid+'_'+count+'" class="exercise_priority_hidden" name="primary_resist_new[]" value=""/><input type="hidden" id="primary_rate_new_'+wkoutid+'_'+count+'" class="exercise_priority_hidden" name="primary_rate_new[]" value=""/><input type="hidden" id="primary_angle_new_'+wkoutid+'_'+count+'" class="exercise_priority_hidden" name="primary_angle_new[]" value=""/><input type="hidden" id="primary_rest_new_'+wkoutid+'_'+count+'" class="exercise_priority_hidden" name="primary_rest_new[]" value=""/><input type="hidden" id="primary_int_new_'+wkoutid+'_'+count+'" class="exercise_priority_hidden" name="primary_int_new[]" value=""/></div></div></div></div></li>';
		if(move == 'down')
			$('#scrollablediv-len ul li:eq(' + goalorder + ')').after(li_element);
		else if(move == 'up')
			$('#scrollablediv-len ul li:eq(' + goalorder + ')').before(li_element);
		else
			$('#scrollablediv-len ul').append(li_element);
		
		if($('#scrollablediv-len li').length > 3 && !$('#scrollablediv-len').hasClass('scrollablediv')){
			$('#scrollablediv-len').addClass('scrollablediv');
		}
		if($('#scrollablediv-len li').length == '1')	$('.sTreeBase').show();
		editWorkoutRecord(wkoutid+"_"+count,"create");
	}
	return false;
}

function editExercise(selector, order) {
   if(!validateXrSets())
	   return false;
   var setids = $('#' + selector + '_hidden').val().split(',');
   $('div.createworkout div.border').removeClass('new-item');
   $('#' + selector + ' .errormsg').empty();
   var oldUnitId = $('#createExercise input[name="goal_id_hidden"]').val();
   var newUnitIdbefore  =  oldUnitId.replace(order+'_','');
   var newUnitId = oldUnitId.replace(order+'_','');
   var xrtitleVal = '';
   var modaldata = $('#createExercise input').map(function() {
      return {
         name: $(this).attr('name'),
         value: $(this).attr('value'),
         keyval: $(this).attr('data-keyval'),
         goalid: $(this).attr('data-goalid')
      }
   }).get();
   var allowCls = false;
   $(modaldata).each(function(i, field) {
      if (field.name == 'exercise_title_hidden') {
         if (field.value != "" && field.value != undefined) {
            $('#' + selector + ' .navimgdet1').html('<b>' + field.value + '</b>');
            $('#' + selector).removeAttr('onclick');
            xrtitleVal = field.value;
				$('#' + selector).attr('data-title', base64_encode(field.value));
            allowCls = true;
         } else {
            $('.errormsg').text('Exercise Title should not be empty').removeClass('hide').show();
            return false;
         }
      }
      if (field.name == 'exercise_unit_hidden') {
			newUnitId = field.value;
         if (field.value != "" && field.value != '0') {
            if ($('#createExercise span#exerciselibimg img').length && $('#createExercise span#exerciselibimg img').attr('src') != '') $('#' + selector + ' .navimage').html('<img width="75px;" src="' + $('#createExercise span#exerciselibimg img').attr('src') + '"  class="img-responsive pointers">');
            else $('#' + selector + ' .navimage').html('<i class="fa fa-file-image-o pointers" style="font-size:50px;">');
            $('#' + selector + ' .navimage').attr("onclick", "getTemplateOfExerciseRecordAction('" + order + '_' + field.value + "',this,'" + order + "');");
         } else {
            $('#' + selector + ' .navimage').html('<i class="fa fa-pencil-square" style="font-size:50px;">');
         }
      }
      /*clone the set*/
      if (field.name.indexOf(field.keyval + '_from') !== -1) {
         var oldsetid = $('div#set_id_' + field.value + ' .seq_order_up').attr('id');
         oldsetid = oldsetid.replace('goal_order_', '');
         var newsetid = field.keyval.replace(order + '_', '');
         $('div#set_id_' + field.value).clone()
            .removeClass('hide deleted')
            .attr({
               'id': 'set_id_' + field.keyval,
               'data-id': field.keyval
            })
            .html(function(i, htmltext) {
               var regex = new RegExp(oldsetid, 'g');
               return htmltext.replace(regex, newsetid);
            })
            .insertAfter('#' + selector + ' .navimagedetails .navimgdet2:last');
         $('<hr>').insertBefore('#' + selector + ' .navimagedetails #set_id_' + field.keyval);
         $('#' + selector + '_hidden').val(setids + ',' + field.keyval);
         setids.push(field.keyval);
      }
      //adding deleted flag
      if (field.name.indexOf('removed_set') !== -1) {
         if ($('div#set_id_' + field.keyval).next('hr').length > 0)
            $('div#set_id_' + field.keyval).next('hr').andSelf().remove();
         else
            $('div#set_id_' + field.keyval).prev('hr').andSelf().remove();
         if (field.keyval.indexOf('_new') == -1) {
				$('div.removedIds').append('<input type="hidden" name="goal_remove[' + field.keyval.replace(order + '_', '') + ']" value="1"/>');
         }
         for (var i = setids.length - 1; i >= 0; i--) {
            if (setids[i] == field.keyval) setids.splice(i, 1);
         }
         $('#' + selector + '_hidden').val(setids.join(','));
      }

      setTimeout(function() {
         newvariable = field.name.replace("_hidden", "");
         newkeyval = field.keyval.replace(order + '_', '');
         $('div#set_id_' + field.keyval + ' #' + newvariable + '_' + newkeyval).val(field.value);
         var updatedText = '';
         if ($('#' + selector + ' div#set_id_' + field.keyval + ' a.' + newvariable + '_div').length && $('#createExercise div#set_' + field.keyval + ' span.' + newvariable).length) {
            updatedText = $('#createExercise div#set_' + field.keyval + ' span.' + newvariable).html().trim();
            if (updatedText != '<span class="inactivedatacol">Click to modify</span>') {
               if (newvariable == 'exercise_rest' && updatedText.trim() != '') {
                  $('#' + selector + ' div#set_id_' + field.keyval + ' a.' + newvariable + '_div').html(updatedText + ' rest');
               } else $('#' + selector + ' div#set_id_' + field.keyval + ' a.' + newvariable + '_div').html(updatedText);
            } else {
               $('#' + selector + ' div#set_id_' + field.keyval + ' a.' + newvariable + '_div').html('');
            }
         }
         $('#' + selector + ' div.exercisesetdiv hr').removeClass('hide');
         $('#' + selector + ' div#set_id_' + field.keyval + '').removeClass('hide');
      }, 120);
   });
   setTimeout(function() {
      for (var i = 0; i < setids.length; i++) {
         var flag = false;
         var setId = setids[i];
         if ($('#' + selector + ' div#set_id_' + setId + ' a.exercise_time_div').html().trim() != '') {
            flag = true;
         }
         if ($('#' + selector + ' div#set_id_' + setId + ' a.exercise_distance_div').html().trim() != '') {
            var inHtml = $('#' + selector + ' div#set_id_' + setId + ' a.exercise_distance_div').html();
            if (flag && inHtml.trim() != '') $('#' + selector + ' div#set_id_' + setId + ' a.exercise_distance_div').html(' /// ' + inHtml);
            else $('#' + selector + ' div#set_id_' + setId + ' a.exercise_distance_div').html(inHtml);
            flag = true;
         }
         if ($('#' + selector + ' div#set_id_' + setId + ' a.exercise_repetitions_div').html().trim() != '') {
            var inHtml = $('#' + selector + ' div#set_id_' + setId + ' a.exercise_repetitions_div').html();
            if (flag && inHtml.trim() != '') $('#' + selector + ' div#set_id_' + setId + ' a.exercise_repetitions_div').html(' /// ' + inHtml);
            else $('#' + selector + ' div#set_id_' + setId + ' a.exercise_repetitions_div').html(inHtml);
            flag = true;
         }
         if ($('#' + selector + ' div#set_id_' + setId + ' a.exercise_resistance_div').html().trim() != '') {
            var inHtml = $('#' + selector + ' div#set_id_' + setId + ' a.exercise_resistance_div').html();
            if (flag && inHtml.trim() != '') $('#' + selector + ' div#set_id_' + setId + ' a.exercise_resistance_div').html(' /// x ' + inHtml);
            else $('#' + selector + ' div#set_id_' + setId + ' a.exercise_resistance_div').html(inHtml);
            flag = true;
         }
         if ($('#' + selector + ' div#set_id_' + setId + ' a.exercise_rate_div').html().trim() != '') {
            var inHtml = $('#' + selector + ' div#set_id_' + setId + ' a.exercise_rate_div').html();
            if (flag && inHtml.trim() != '') $('#' + selector + ' div#set_id_' + setId + ' a.exercise_rate_div').html(' /// x ' + inHtml);
            else $('#' + selector + ' div#set_id_' + setId + ' a.exercise_rate_div').html(inHtml);
            flag = true;
         }
         if ($('#' + selector + ' div#set_id_' + setId + ' a.exercise_angle_div').html().trim() != '') {
            var inHtml = $('#' + selector + ' div#set_id_' + setId + ' a.exercise_angle_div').html();
            if (flag && inHtml.trim() != '') $('#' + selector + ' div#set_id_' + setId + ' a.exercise_angle_div').html(' /// x ' + inHtml);
            else $('#' + selector + ' div#set_id_' + setId + ' a.exercise_angle_div').html(inHtml);
            flag = true;
         }
         if ($('#' + selector + ' div#set_id_' + setId + ' a.exercise_innerdrive_div').html().trim() != '') {
            var inHtml = $('#' + selector + ' div#set_id_' + setId + ' a.exercise_innerdrive_div').html();
            if (flag && inHtml.trim() != '') $('#' + selector + ' div#set_id_' + setId + ' a.exercise_innerdrive_div').html(' /// x ' + inHtml);
            else $('#' + selector + ' div#set_id_' + setId + ' a.exercise_innerdrive_div').html(inHtml);
            flag = true;
         }
         if ($('#' + selector + ' div#set_id_' + setId + ' a.exercise_rest_div').html().trim() != '') {
            var inHtml = $('#' + selector + ' div#set_id_' + setId + ' a.exercise_rest_div').html();
            if (flag && inHtml.trim() != '') $('#' + selector + ' div#set_id_' + setId + ' a.exercise_rest_div').html(' /// x ' + inHtml);
            else $('#' + selector + ' div#set_id_' + setId + ' a.exercise_rest_div').html(inHtml);
         }
		 	if (allowCls) {
		 		var xrsetid = order + '_' + $('#' + selector).attr('data-id');
		 		var new_setid = setId.replace(order + '_', '');
				if($('div#set_id_' + setId + ' input#markedstatus_'+new_setid).val() == 0){
					$('div#set_id_' + setId + ' input#markedstatus_'+new_setid).val(1);
					ChangeWkoutStatusWorkouts('1', xrsetid, new_setid);
				}
	     	}
      }
   }, 150);
   if (allowCls) {
		$('#' + selector + ' div.border').addClass('new-item');
		$('#myOptionsModal').modal('hidecustom');
		$('#myModal').modal('hidecustom');
   }
    setTimeout(function() {
		if(newUnitIdbefore!=newUnitId && ($.isNumeric(newUnitIdbefore) || $.isNumeric(newUnitId))){
		   var oldUnit_id = oldUnitId.replace(order+'_','');
		   var wkoutlogid = $('#wkout_log_id').val();
		   var selectornew = 'li#' + selector;
		   var inlineString = $(selectornew).html();
		   if(newUnitId == 0){
				newUnitId = '0_'+$('#wkout_log_id').val();
		   }
		   var re1 = new RegExp('_'+oldUnitId, 'g');
		   inlineString = inlineString.replace(re1, '_'+order+'_'+newUnitId);
		   var re2 = new RegExp(oldUnitId+']', 'g');
		   inlineString = inlineString.replace(re2, order+'_'+newUnitId+']');
		   var re3 = new RegExp('"'+oldUnitId+'"', 'g');
		   inlineString = inlineString.replace(re3, '"'+order+'_'+newUnitId+'"');
		   var re5 = new RegExp("'"+oldUnitId+"'", 'g');
		   inlineString = inlineString.replace(re5, "'"+order+'_'+newUnitId+"'");
		   var re4 = new RegExp(oldUnit_id+',this', 'g');
		   inlineString = inlineString.replace(re4, newUnitId+',this');
		   $(selectornew).html(inlineString);
		   if ($(selectornew).length) {
			  var xrtitle = 'input.exercise_title_xr_hidden';
			  var xrtag	  = 'input.exercise_unit_xr_hidden';
		   }
		   $(selectornew + ' .navimgdet1').html('<b>' + xrtitleVal + '</b>');
		   $(selectornew + ' ' + xrtag).val(order+'_'+newUnitId);
		   $(selectornew + ' .navimage').removeAttr('onclick');
		   $(selectornew + ' ' + xrtitle).val(xrtitleVal);
		   $(selectornew).attr('data-title', base64_encode(xrtitleVal));
		   if($.isNumeric(newUnitId))
			   $(selectornew + ' .navimage').attr("onclick", "getTemplateOfExerciseRecordAction('" + order + '_'+ newUnitId + "',this,'"+order+"');");
			$(selectornew + ' div.navimgdet2').each(function(){
				var setid = $(this).attr('data-id').replace(order+'_','');
				$(this).find('.listoptionpoppopup').attr("onclick", "getTemplateOfExerciseSetAction('" + order + '_'+ newUnitId + "','"+ setid + "','link');");
			});
		   $(selectornew).attr("data-id",newUnitId);
		   $(selectornew).attr("id",selector.replace(oldUnitId, order+'_'+newUnitId));
		}
		updateLiItems();
	}, 170);
   return false;
}

function editExerciseold(selector,order){
	$('div.createworkout div.border').removeClass('new-item');
	$('#'+selector+' .errormsg').empty();
	var modaldata=$('#createExercise').serializeArray();
	var allowCls = false;
	$(modaldata).each(function(i, field){
		if(field.name == 'exercise_title_hidden'){
			if(field.value != ""){
				$('#'+selector+' .navimgdet1').html('<b>'+field.value+'</b>');
				$('#'+selector).removeAttr('onclick');
				allowCls = true;
			}else{
				$('.errormsg').text('Exercise Title not empty').removeClass('hide').show();
				return false;
			}
		}
		if(field.name == 'exercise_unit_hidden'){
			if (field.value != "" && field.value > 0) {
				if($('#createExercise span#exerciselibimg img').length && $('#createExercise span#exerciselibimg img').attr('src').trim() != '')
					$('#'+selector+' .navimage').html('<img width="75px;" src="'+$('#createExercise span#exerciselibimg img').attr('src')+'"  class="img-responsive pointers"/>');
				else
					$('#'+selector+' .navimage').html('<i class="fa fa-file-image-o pointers" style="font-size:50px;">');
				$('#'+selector+' .navimage').attr("onclick","getTemplateOfExerciseRecordAction('"+field.value+"',this);");
			}else{
				$('#'+selector+' .navimage').html('<i class="fa fa-pencil-square" style="font-size:50px;">');
			}
		}
		newvariable = field.name.replace("_hidden", "");
		$('#'+newvariable+'_'+xrsetId).val(field.value);
		var updatedText = '';
		if($('#'+selector+' a.'+newvariable+'_div').length && $('#createExercise span.'+newvariable).length){
			updatedText = $('#createExercise span.'+newvariable).html().trim();
			if(updatedText !='<span class="inactivedatacol">Click to modify</span>'){
				if(newvariable == 'exercise_rest' && updatedText.trim() != ''){
					$('#'+selector+' a.'+newvariable+'_div').html(updatedText+' rest');
				}else
					$('#'+selector+' a.'+newvariable+'_div').html(updatedText);
				if(updatedText != '')
					$('#'+selector+' a.'+newvariable+'_div').parent('div').removeClass('hide');
			}else{
				$('#'+selector+' a.'+newvariable+'_div').html('');
			 }
		}
	});
	var flag = false;
	if($('#'+selector+' a.exercise_time_div').html().trim() != ''){		
		flag = true;
	}
	if($('#'+selector+' a.exercise_distance_div').html().trim() != ''){
		var inHtml = $('#'+selector+' a.exercise_distance_div').html();
		if(flag && inHtml.trim() !='')
			$('#'+selector+' a.exercise_distance_div').html(' /// '+inHtml);
		else
			$('#'+selector+' a.exercise_distance_div').html(inHtml);
		flag = true;
	}	
	if($('#'+selector+' a.exercise_repetitions_div').html().trim() != ''){
		var inHtml = $('#'+selector+' a.exercise_repetitions_div').html();
		if(flag && inHtml.trim() !='')
			$('#'+selector+' a.exercise_repetitions_div').html(' /// '+inHtml);
		else
			$('#'+selector+' a.exercise_repetitions_div').html(inHtml);
		flag = true;
	}
	if($('#'+selector+' a.exercise_resistance_div').html().trim() != ''){
		var inHtml = $('#'+selector+' a.exercise_resistance_div').html();
		if(flag && inHtml.trim() !='')
			$('#'+selector+' a.exercise_resistance_div').html(' /// '+inHtml);
		else
			$('#'+selector+' a.exercise_resistance_div').html(inHtml);
	}
	if($('#'+selector+' a.exercise_intent_div').html().trim() != ''){
		var inHtml = $('#'+selector+' a.exercise_intent_div').html();
		if(inHtml.trim() !='')
			$('#'+selector+' a.exercise_intent_div').html(inHtml);
		else
			$('#'+selector+' a.exercise_intent_div').html(inHtml);
	}
	if($('#'+selector+' a.exercise_remarks_div').html().trim() != ''){
		var inHtml = $('#'+selector+' a.exercise_remarks_div').html();
		if(inHtml.trim() !='')
			$('#'+selector+' a.exercise_remarks_div').html(+inHtml);
		else
			$('#'+selector+' a.exercise_remarks_div').html(inHtml);
	}
	if(allowCls){
		$('#' + selector + ' div.border').addClass('new-item');
		if($('input#markedstatus_'+xrsetId).val() == 0){
			$('input#markedstatus_'+xrsetId).val(1);
			ChangeWkoutStatusWorkouts('1',xrsetId);
		}
		$('#myOptionsModal').modal('hidecustom');
	}
	return false;
}
function addnewExercise(elem) {
	if(!validateXrSets())
	   return false;
   $('.errormsg').hide();
   var setids = $('input#itemSet_' + elem +'_hidden').val().split(',');
   var order = $('li#itemSet_' + elem +' input.seq_order_combine_up').val();
   var setDataid = $('li#itemSet_' + elem).attr('data-id');
   var oldUnitId = $('#createExercise input[name="goal_id_hidden"]').val();
   var newUnitIdbefore = oldUnitId.replace(order+'_','');
   var newUnitId = oldUnitId.replace(order+'_','');
   var dataUnitid = order + '_' + setDataid;
   var xrtitleVal = '';
   var allowCls = false;
   var modaldata = $('#createExercise input').map(function() {
		return {
			name: $(this).attr('name'),
			value: $(this).attr('value'),
			keyval: $(this).attr('data-keyval'),
			goalid: $(this).attr('data-goalid')
		}
	}).get();
	var exercise_unit_xr_hidden = order + '_' + $('li#itemSet_' + elem).attr('data-id');
   $(modaldata).each(function(i, field) {
      if (field.name == 'exercise_title_hidden') {
         if (field.value != "" && field.value != undefined) {
            $('li#itemSet_' + elem).find('.navimgdet1').html('<b>' + field.value + '</b>');
            allowCls = true;
				xrtitleVal = field.value;
         } else {
            $('.errormsg').text('Exercise Title should not be empty').removeClass('hide').show();
            return false;
         }
      }
      if (field.name == 'exercise_unit_hidden') {
		 	newUnitId = field.value;
         $('div#set_id_' + field.keyval + ' input#exercise_unit' + '_' + field.keyval.replace(order + '_', '')).val(order + '_' + field.value);
         if (field.value != "" && field.value > 0) {
            if ($('#createExercise span#exerciselibimg img').length && $('#createExercise span#exerciselibimg img').attr('src') != '') $('li#itemSet_' + elem + ' .navimage').html('<img width="75px;" src="' + $('#createExercise span#exerciselibimg img').attr('src') + '"  class="img-responsive pointers">');
            else $('li#itemSet_' + elem + ' .navimage').html('<i class="fa fa-file-image-o pointers" style="font-size:50px;">');
            $('li#itemSet_' + elem + ' .navimage').attr("onclick", "getTemplateOfExerciseRecordAction('" + order + '_' + field.value + "',this,'" + order + "');");
         } else {
            $('li#itemSet_' + elem + ' .navimage').html('<i class="fa fa-pencil-square" style="font-size:50px;">');
         }
      }
      /*clone the set*/
      if (field.name.indexOf(field.keyval + '_from') !== -1) {
         var oldsetid = $('div#set_id_' + field.value + ' .seq_order_up').attr('id');
         oldsetid = oldsetid.replace('goal_order_', '');
         var newsetid = field.keyval.replace(order + '_', '');
         $('div#set_id_' + field.value).clone()
            .removeClass('hide deleted')
            .attr({
               'id': 'set_id_' + field.keyval,
               'data-id': field.keyval
            })
            .html(function(i, htmltext) {
               var regex = new RegExp(oldsetid, 'g');
               return htmltext.replace(regex, newsetid);
            })
            .find('i.listoptionpop').attr("onclick", "getTemplateOfExerciseSetAction('" + dataUnitid + "','" + newsetid + "','link');").removeClass('hide').end()
            .insertAfter('li#itemSet_' + elem + ' .navimagedetails .navimgdet2:last');
         $('<hr>').insertBefore('li#itemSet_' + elem + ' .navimagedetails #set_id_' + field.keyval);
         $('input#itemSet_' + elem + '_hidden').val(setids + ',' + field.keyval);
         setids.push(field.keyval);
      }
      //adding deleted flag
      if (field.name.indexOf('removed_set') !== -1) {
         if ($('div#set_id_' + field.keyval).next('hr').length > 0)
            $('div#set_id_' + field.keyval).next('hr').andSelf().remove();
         else
            $('div#set_id_' + field.keyval).prev('hr').andSelf().remove();
         for (var i = setids.length - 1; i >= 0; i--) {
            if (setids[i] == field.keyval) setids.splice(i, 1);
         }
         $('input#itemSet_' + elem + '_hidden').val(setids.join(','));
      }
      setTimeout(function() {
         newvariable = field.name.replace("_hidden", "");
         newkeyval = field.keyval.replace(order + '_', '');
         if (field.name != 'exercise_unit_hidden')
            $('div#set_id_' + field.keyval + ' #' + newvariable + '_' + newkeyval).val(field.value);
         var updatedText = '';
         if ($('li#itemSet_' + elem + ' div#set_id_' + field.keyval + ' a.' + newvariable + '_div').length && $('#createExercise div#set_' + field.keyval + ' span.' + newvariable).length) {
            updatedText = $('#createExercise div#set_' + field.keyval + ' span.' + newvariable).html().trim();
            if (updatedText != '<span class="inactivedatacol">Click to modify</span>') {
               if (newvariable == 'exercise_rest' && updatedText.trim() != '')
                  $('li#itemSet_' + elem + ' div#set_id_' + field.keyval + ' a.' + newvariable + '_div').html(updatedText + ' rest');
               else 
               	$('li#itemSet_' + elem + ' div#set_id_' + field.keyval + ' a.' + newvariable + '_div').html(updatedText);
            } else {
               $('li#itemSet_' + elem + ' div#set_id_' + field.keyval + ' a.' + newvariable + '_div').html('');
            }
         }
      }, 120);
   });
   setTimeout(function() {
      for (var i = 0; i < setids.length; i++) {
         var flag = false;
         var setId = setids[i];
         $('#itemSet_' + elem + ' div#set_id_' + setId + ' .listoptionpop').removeClass('hide');
         if ($('#itemSet_' + elem + ' div#set_id_' + setId + ' a.exercise_time_div').html().trim() != '') {
            flag = true;
         }
         if ($('#itemSet_' + elem + ' div#set_id_' + setId + ' a.exercise_distance_div').html().trim() != '') {
            var inHtml = $('#itemSet_' + elem + ' div#set_id_' + setId + ' a.exercise_distance_div').html();
            if (flag && inHtml.trim() != '') $('#itemSet_' + elem + ' div#set_id_' + setId + ' a.exercise_distance_div').html(' /// ' + inHtml);
            else $('#itemSet_' + elem + ' div#set_id_' + setId + ' a.exercise_distance_div').html(inHtml);
            flag = true;
         }
         if ($('#itemSet_' + elem + ' div#set_id_' + setId + ' a.exercise_repetitions_div').html().trim() != '') {
            var inHtml = $('#itemSet_' + elem + ' div#set_id_' + setId + ' a.exercise_repetitions_div').html();
            if (flag && inHtml.trim() != '') $('#itemSet_' + elem + ' div#set_id_' + setId + ' a.exercise_repetitions_div').html(' /// ' + inHtml);
            else $('#itemSet_' + elem + ' div#set_id_' + setId + ' a.exercise_repetitions_div').html(inHtml);
            flag = true;
         }
         if ($('#itemSet_' + elem + ' div#set_id_' + setId + ' a.exercise_resistance_div').html().trim() != '') {
            var inHtml = $('#itemSet_' + elem + ' div#set_id_' + setId + ' a.exercise_resistance_div').html();
            if (flag && inHtml.trim() != '') $('#itemSet_' + elem + ' div#set_id_' + setId + ' a.exercise_resistance_div').html(' /// ' + inHtml);
            else $('#itemSet_' + elem + ' div#set_id_' + setId + ' a.exercise_resistance_div').html(inHtml);
            flag = true;
         }
         if ($('#itemSet_' + elem + ' div#set_id_' + setId + ' a.exercise_rate_div').html().trim() != '') {
            var inHtml = $('#itemSet_' + elem + ' div#set_id_' + setId + ' a.exercise_rate_div').html();
            if (flag && inHtml.trim() != '') $('#itemSet_' + elem + ' div#set_id_' + setId + ' a.exercise_rate_div').html(' /// x ' + inHtml);
            else $('#itemSet_' + elem + ' div#set_id_' + setId + ' a.exercise_rate_div').html(inHtml);
            flag = true;
         }
         if ($('#itemSet_' + elem + ' div#set_id_' + setId + ' a.exercise_angle_div').html().trim() != '') {
            var inHtml = $('#itemSet_' + elem + ' div#set_id_' + setId + ' a.exercise_angle_div').html();
            if (flag && inHtml.trim() != '') $('#itemSet_' + elem + ' div#set_id_' + setId + ' a.exercise_angle_div').html(' /// x ' + inHtml);
            else $('#itemSet_' + elem + ' div#set_id_' + setId + ' a.exercise_angle_div').html(inHtml);
            flag = true;
         }
         if ($('#itemSet_' + elem + ' div#set_id_' + setId + ' a.exercise_innerdrive_div').html().trim() != '') {
            var inHtml = $('#itemSet_' + elem + ' div#set_id_' + setId + ' a.exercise_innerdrive_div').html();
            if (flag && inHtml.trim() != '') $('#itemSet_' + elem + ' div#set_id_' + setId + ' a.exercise_innerdrive_div').html(' /// x ' + inHtml);
            else $('#itemSet_' + elem + ' div#set_id_' + setId + ' a.exercise_innerdrive_div').html(inHtml);
            flag = true;
         }
         if ($('#itemSet_' + elem + ' div#set_id_' + setId + ' a.exercise_rest_div').html().trim() != '') {
            var inHtml = $('#itemSet_' + elem + ' div#set_id_' + setId + ' a.exercise_rest_div').html();
            if (flag && inHtml.trim() != '') $('#itemSet_' + elem + ' div#set_id_' + setId + ' a.exercise_rest_div').html(' /// x ' + inHtml);
            else $('#itemSet_' + elem + ' div#set_id_' + setId + ' a.exercise_rest_div').html(inHtml);
         }
         if (allowCls) {
         	var xrsetid = order + '_' + $('li#itemSet_' + elem).attr('data-id');
				var new_setid = setId.replace(order + '_', '');
				if($('div#set_id_' + setId + ' input#markedstatus_'+new_setid).val() == 0){
					$('div#set_id_' + setId + ' input#markedstatus_'+new_setid).val(1);
					enablestatusButtons(new_setid, 0);
				}
	     	}
      }
   }, 150);
   if (allowCls) {
      if ($('li#itemSet_' + elem).hasClass('hide')) $('li#itemSet_' + elem).removeClass('hide');
      if ($('#scrollablediv-len ul').hasClass('hide')) $('#scrollablediv-len ul').removeClass('hide');
      $('#itemSet_' + elem + ' div.border').addClass('new-item');
      litagscrollcnt = $('li#itemSet_' + elem).index();
      if (litagscrollcnt == '0')
         $('div#scrollablediv-len ul').scrollTop($('div#scrollablediv-len ul li:first').position().top);
      else if ($('#scrollablediv-len ul li').length == litagscrollcnt)
         $('div#scrollablediv-len ul').scrollTop($('div#scrollablediv-len ul li:last').position().top);
      else
         $('div#scrollablediv-len ul').scrollTop($('div#scrollablediv-len ul li:nth-child(' + litagscrollcnt + ')').position().top - $('div#scrollablediv-len ul li:first').position().top);
      $('#myOptionsModal').modal('hidecustom');
   }
   $('#itemSet_' + elem + ' div.exercisesetdiv div.navimgdet2').removeClass('hide');
   $('#itemSet_' + elem + ' div.exercisesetdiv hr').removeClass('hide');
   setTimeout(function() {
	   var oldUnit_id = oldUnitId.replace(order+'_','');
	   var wkoutlogid = $('#wkout_log_id').val();
	   var selectornew = 'li#itemSet_' + elem;
	   var inlineString = $(selectornew).html();
	   var inlineXrString = $(selectornew+' div.exercisesetdiv').html();
	   var inlineHiddenval = $(selectornew+' input#'+selectornew+'_hidden').val();
	   if(newUnitId == 0){
			newUnitId = '0_'+$('#wkout_log_id').val();
	   }
	   var re1 = new RegExp('_'+oldUnitId, 'g');
	   inlineString = inlineString.replace(re1, '_'+order+'_'+newUnitId);
	   var re2 = new RegExp(oldUnitId+']', 'g');
	   inlineString = inlineString.replace(re2, order+'_'+newUnitId+']');
	   var re3 = new RegExp('"'+oldUnitId+'"', 'g');
	   inlineString = inlineString.replace(re3, '"'+order+'_'+newUnitId+'"');
	   var re5 = new RegExp("'"+oldUnitId+"'", 'g');
	   inlineString = inlineString.replace(re5, "'"+order+'_'+newUnitId+"'");
	   var re4 = new RegExp(oldUnit_id+',this', 'g');
	   inlineString = inlineString.replace(re4, newUnitId+',this');
	   $(selectornew).html(inlineString);
	   if ($(selectornew).length) {
		  var xrtitle = 'input.exercise_title_xr_hidden';
		  var xrtag	  = 'input.exercise_unit_xr_hidden';
	   }

	   $(selectornew + ' .navimage').removeAttr('onclick');
	   $(selectornew).attr("data-id",newUnitId);
	   var replacedId = 'itemSet_' + elem.replace(oldUnitId, order+'_'+newUnitId);
	   $(selectornew).attr("id",replacedId);
	   $('li#'+replacedId + ' div.exercisesetdiv').html(inlineXrString);
	   $('li#'+replacedId + ' input#'+replacedId+'_hidden').val(inlineHiddenval);
	   $('li#'+replacedId + ' .navimgdet1').html('<b>' + xrtitleVal + '</b>');
	   $('li#'+replacedId).attr('data-title', base64_encode(xrtitleVal));
	   $('li#'+replacedId + ' ' + xrtag).val(order+'_'+newUnitId);
	   $('li#'+replacedId + ' ' + xrtitle).val(xrtitleVal);
	   if($.isNumeric(newUnitId)){
			$('li#'+replacedId + ' .navimage').attr("onclick", "getTemplateOfExerciseRecordAction('" + order + '_'+ newUnitId + "',this,'"+order+"');");
	   }
		$('li#'+replacedId + ' div.navimgdet2').each(function(){
			var setid = $(this).attr('data-id').replace(order+'_','');
			$(this).find('.listoptionpop').attr("onclick", "getTemplateOfExerciseSetAction('" + order + '_'+ newUnitId + "','"+ setid + "','link');");
		});
		updateLiItems();
	}, 170);
   return false;
}
function addnewExerciseOld(elem){
	$('.errormsg').hide();
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
		if(field.name == 'exercise_unit_hidden' && field.value !=""){
			if(field.value !=""){
				if($('#createExercise span#exerciselibimg img').length && $('#createExercise span#exerciselibimg img').attr('src') != '')
					$('#itemsetnew_'+elem+' .navimage').html('<img width="75px;" src="'+$('#createExercise span#exerciselibimg img').attr('src')+'"  class="img-responsive pointers" />');
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
	$('#itemsetnew_'+elem+' .listoptionpop').attr("onclick","getTemplateOfExerciseSetActionBycreate('"+elem+"','myModal');").removeClass('hide');
	
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
	if($('#itemsetnew_'+elem+' a.exercise_intent_div').html().trim() != ''){
		var inHtml = $('#itemsetnew_'+elem+' a.exercise_intent_div').html();
		if(inHtml.trim() !='')
			$('#itemsetnew_'+elem+' a.exercise_intent_div').html(inHtml);
		else
			$('#itemsetnew_'+elem+' a.exercise_intent_div').html(inHtml);
	}
	if($('#itemsetnew_'+elem+' a.exercise_remarks_div').html().trim() != ''){
		var inHtml = $('#itemsetnew_'+elem+' a.exercise_remarks_div').html();
		if(inHtml.trim() !='')
			$('#itemsetnew_'+elem+' a.exercise_remarks_div').html(+inHtml);
		else
			$('#itemsetnew_'+elem+' a.exercise_remarks_div').html(inHtml);
	}
	if(allowCls){
		$('#myOptionsModal').html('');
		$('#goal_order_'+elem).val(0);
		if($('#itemsetnew_'+elem).parent('li').hasClass('hide'))
			$('#itemsetnew_'+elem).parent('li').removeClass('hide');
		if($('#scrollablediv-len ul').hasClass('hide'))
			$('#scrollablediv-len ul').removeClass('hide');
		if($('input#markedstatus_new_'+elem).val() == 0){
			$('input#markedstatus_new_'+elem).val(1);
			enablestatusButtons('new_'+elem,0);
		}
		$('#itemsetnew_' + elem + ' div.border').addClass('new-item');
		litagscrollcnt = $('#itemsetnew_' + elem).parent('li').index();
		if(litagscrollcnt == '0')
			$('div#scrollablediv-len ul').scrollTop($('div#scrollablediv-len ul li:first').position().top);
		else if($('#scrollablediv-len ul li').length == litagscrollcnt)
			$('div#scrollablediv-len ul').scrollTop($('div#scrollablediv-len ul li:last').position().top);
		else
			$('div#scrollablediv-len ul').scrollTop($('div#scrollablediv-len ul li:nth-child('+litagscrollcnt+')').position().top - $('div#scrollablediv-len ul li:first').position().top);
		$('#myOptionsModal').modal('hidecustom');
	}
	return false;
}
function insertExtraToParentHidden(Model, elem){
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
					$('div#clone-tab').removeClass('hide');
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
						$('div#set_'+elem+' span .ashstrick').remove();
					if(field.value != '')
						$('div#set_'+elem+' .'+field.name).html(ashstrick+'<span>'+field.value+'</span> reps');
					else
						$('div#set_'+elem+' .'+field.name).html('<span class="inactivedatacol">Click to modify</span>');
				}else if(field.name == 'exercise_innerdrive'){
					if(ashstrick !='') $('div#set_'+elem+' span .ashstrick').remove();
					if(field.value != ''){
						matchesval = document.getElementById("innerdrive").options[field.value].text;
						if(matchesval != 'Select'){
							var regExp = /\(([^)]+)\)/;
							var matches = regExp.exec(matchesval);
							$('div#set_'+elem+' .'+field.name).html(ashstrick+'<span>'+matches[1]+'</span> Int');
						}else
							$('div#set_'+elem+' .'+field.name).html('<span class="inactivedatacol">Click to modify</span>');
					}else{
						$('div#set_'+elem+' .'+field.name).html('<span class="inactivedatacol">Click to modify</span>');
					}
				}else{
					if(ashstrick !='')	$('div#set_'+elem+' span .ashstrick').remove();
					if(field.value != '')
						$('div#set_'+elem+' .'+field.name).html(ashstrick+'<span>'+field.value+'</span>');
					else
						$('div#set_'+elem+' .' + field.name).html(ashstrick + '<span class="inactivedatacol">Click to modify</span>');
				}
				if($('div#set_'+elem+' .'+field.name+'_hidden')){
					if(field.value !='' && ashstrick !=''){
						if(primaryField!='' && $('div#set_'+elem+' #primary_'+primaryField)){
							$('div#set_'+elem+' .exercise_priority_hidden').val('0');
							$('div#set_'+elem+' #primary_'+primaryField).val(1);
						}
					}else{
						if($('div#set_'+elem+' #primary_'+primaryField))
							$('div#set_'+elem+' #primary_'+primaryField).val(0);
					}
					if(field.name == 'exercise_innerdrive' && ((!$('div#set_'+elem+' .exercise_innerdrive_hidden').val() > 0 && field.value!='') || ($('div#set_'+elem+' .exercise_innerdrive_hidden').val() > 0 && field.value==''))){
						var actioncount = $('div#set_'+elem+' span#showcountXrvariable').html().trim();
						if(field.value != ''){
							var newactioncount = parseInt(actioncount)+1;
							$('div#set_'+elem+' span#showcountXrvariable').html(newactioncount);
							if(newactioncount>0 && $('div#set_'+elem+' span#showcountXrvariable').hasClass('hide'))
								$('div#set_'+elem+' span#showcountXrvariable').removeClass('hide');
						}else if(parseInt(actioncount) > 0){
							var newactioncount = parseInt(actioncount)-1; 
							$('div#set_'+elem+' span#showcountXrvariable').html(newactioncount);
							if(newactioncount==0 && !$('div#set_'+elem+' span#showcountXrvariable').hasClass('hide')) 
								$('div#set_'+elem+' span#showcountXrvariable').addClass('hide');
						}
					}
					$('div#set_'+elem+' .'+field.name+'_hidden').val(field.value);
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
						if(primaryField!='' && $('div#set_'+elem+' #primary_'+primaryField)){
							$('div#set_'+elem+' .exercise_priority_hidden').val('0');
							$('div#set_'+elem+' #primary_'+primaryField).val(1);
						}
					}else{
						if($('div#set_'+elem+' #primary_'+primaryField))
							$('div#set_'+elem+' #primary_'+primaryField).val(0);
					}
					if(ashstrick !='')
						$('div#set_'+elem+' span .ashstrick').remove();
					if(field.value != '')
						$('div#set_'+elem+' .'+field.name).html(ashstrick+'<span>'+field.value+'</span>');
					else
						$('div#set_'+elem+' .' + field.name).html(ashstrick + '<span class="inactivedatacol">Click to modify</span>');
					
					if ((field.name == 'exercise_angle' && ((!$('div#set_' + elem + ' .exercise_angle_hidden').val() > 0 && field.value != '') || ($('div#set_' + elem + ' .exercise_angle_hidden').val() > 0 && field.value == ''))) || ((field.name == 'exercise_rate' && ((!$('div#set_' + elem + ' .exercise_rate_hidden').val() > 0 && field.value != '') || ($('div#set_' + elem + ' .exercise_rate_hidden').val() > 0 && field.value == ''))))) {
						var actioncount = $('div#set_' + elem + ' span#showcountXrvariable').html().trim();
						if (field.value != '' && (unit_value != '0' || unit_value != '')) {
							var newactioncount = parseInt(actioncount) + 1;
							$('div#set_' + elem + ' span#showcountXrvariable').html(newactioncount);
							if (newactioncount > 0 && $('div#set_' + elem + ' span#showcountXrvariable').hasClass('hide'))
								$('div#set_' + elem + ' span#showcountXrvariable').removeClass('hide');
						} else if (parseInt(actioncount) > 0) {
							var newactioncount = parseInt(actioncount) - 1;
							$('div#set_' + elem + ' span#showcountXrvariable').html(newactioncount);
							if (newactioncount == 0 && !$('div#set_' + elem + ' span#showcountXrvariable').hasClass('hide'))
								$('div#set_' + elem + ' span#showcountXrvariable').addClass('hide');
						}
					}
					$('div#set_'+elem+' .'+field.name+'_hidden').val(field.value);
					$('div#set_'+elem+' .'+existUnit+'_hidden').val(unit_value);
					var appendId = field.name.replace('unit_','');
					$('div#set_'+elem+' .'+appendId).append(' '+unit_val);
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

function getTemplateOfExerciseSetAction(exerciseSetId, selectedSetId, link){
	$('#myModal').html();
	var wkoutlogid = $('#wkout_log_id').val();
	var goalOrder = $('li#itemSet_' + wkoutlogid + '_' + exerciseSetId + ' input#goal_order_combine_' + exerciseSetId).val();
	var titlediv = '';
	if(exerciseSetId.indexOf('new') >= 0)
		titlediv = 'div#itemset'+ exerciseSetId + ' div.navimgdet1 b';
	else
		titlediv = 'div#itemset_'+wkoutlogid+'_'+ exerciseSetId + ' div.navimgdet1 b';	
	var xrid = $('#exercise_unit_' + selectedSetId).val();
	$.ajax({
		url : siteUrl+"search/getmodelTemplate",
		data : {
			action : 'exercisesetaction',
			method : 'action', 
			id : '',
			foldid : exerciseSetId,
			xrsetid : selectedSetId,
			xrid : (xrid.indexOf('new') === -1 ? xrid.replace(goalOrder+'_','') : ''),
			modelType : link,
			goalOrder : goalOrder,
			title : getTitlestrip(titlediv),
			logid : $('#wkout_log_id').val(),
			editFlag : ($('.editmode').is(":visible") ? true : ''),
			type : 'logged',
		},
		success : function(content){
			$('#myModal').html(content);
			$('#myModal').modal();
		}
	});
}
function getTemplateOfExerciseSetActionBycreate(elem,link){
	$('#myModal').html();
	var goalOrder = $('div#itemset'+elem+' input#goal_order_'+elem).val();
	var titlediv = 'div#itemset'+ elem + ' div.navimgdet1 b';
	$.ajax({
		url : siteUrl+"search/getmodelTemplate",
		data : {
			action : 'exercisesetaction',
			method : 'action-create', 
			id : elem,
			foldid : elem,
			modelType : link,
			goalOrder : goalOrder,
			xrid : $('#exercise_unit_new_'+elem).val(),
			logid : $('#wkout_log_id').val(),
			title : getTitlestrip(titlediv),
			editFlag : ($('.editmode').is(":visible") ? true : ''),
			type : 'logged',
		},
		success : function(content){
			$('#myModal').html(content);
			$('#myModal').modal();
		}
	});
}
function getTemplateOfExerciseSetActionByprev(exerciseSetId, link, goalOrder){
	var wkoutid = '';
	var titlediv = '';
	if(exerciseSetId.indexOf('new') >= 0)
		titlediv = 'div#itemset'+ exerciseSetId + ' div.navimgdet1 b';
	else
		titlediv = 'div#itemset_'+$('#wkout_log_id').val()+'_'+ exerciseSetId + ' div.navimgdet1 b';	
	$('#myOptionsModalAjax').html();
	$.ajax({
		url : siteUrl+"search/getmodelTemplate",
		data : {
			action : 'exercisesetaction',
			method : 'action-edit', 
			id : wkoutid,
			foldid : exerciseSetId,
			modelType : link,
			goalOrder : goalOrder,
			xrid : $('#exercise_unit_'+exerciseSetId).val(),
			title : getTitlestrip(titlediv),
			logid : $('#wkout_log_id').val(),
			editFlag : ($('.editmode').is(":visible") ? true : ''),
			type : 'logged',
		},
		success : function(content){
			$('#myOptionsModalAjax').html(content);
			$('#myOptionsModalAjax').modal();
		}
	});
}
function fixToolTipColor(){
	//grab the bg color from the tooltip content - set top border of pointer to same
	$('.ui-tooltip-pointer-down-inner').each(function(){
		var bWidth = $('.ui-tooltip-pointer-down-inner').css('borderTopWidth');
		var bColor = $(this).parents('.ui-slider-tooltip').css('backgroundColor')
		$(this).css('border-top', bWidth+' solid '+bColor);
	});	
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
function getExercisepreviewOfDay(exerciseId, wkoutId){
	$('#myOptionsModal').html();
	$.ajax({
		url : siteUrl+"search/getmodelTemplate",
		data : {
			action : 'previewExerciseOfDay',
			method :  'preview',
			id : exerciseId,
			foldid : wkoutId,
			logid : $('#wkout_log_id').val(),
			type   : 'logged',
			modelType: 'myOptionsModal'
		},
		success : function(content){
			$('#myOptionsModal').html(content);
			$('#myOptionsModal').modal();
		}
	});
}
function getworkoutpreview(wkoutId){
		closeModelwindow('');
}
function getTemplateOfExerciseAction(exerciseSetId,link){
	$('#myModal').html();
	$.ajax({
		url : siteUrl+"search/getmodelTemplate",
		data : {
			action : 'exercisesetaction',
			method : 'action', 
			id : '',
			foldid : exerciseSetId,
			modelType : link,
			xrid  : $('#exercise_unit_'+exerciseSetId).val(),
			logid : $('#wkout_log_id').val(),
			type : 'logged',
		},
		success : function(content){
			$('#myModal').html(content);
			$('#myModal').modal();
		}
	});
}
function getTemplateOfExerciseRecordAction(exerciseSetId, selector, order){
	if(!$(selector).attr('disabled')){
		var xrsetId = $(selector).closest('li').attr('data-id');
		var titlediv = 'div#itemset_'+ exerciseSetId + ' div.navimgdet1 b';
		$('#myModal').html();
		$.ajax({
			url : siteUrl+"search/getmodelTemplate",
			data : {
				action : 'exerciserecordaction',
				method : 'action', 
				id : '',
				foldid : exerciseSetId.replace(order+'_',''),
				xrid : xrsetId,
				logid : $('#wkout_log_id').val(),
				type : 'logged',
				title : getTitlestrip(titlediv),
				editFlag : ($('.editmode').is(":visible") ? true : ''),
				allowTag : true,
				goalOrder : order
			},
			success : function(content){
				$('#myModal').html(content);
				$('#myModal').modal();
			}
		});
	}
}
function getTemplateOfWorkoutAction(){
	$('#myModal').html();
	$.ajax({
		url : siteUrl+"search/getmodelTemplate",
		data : {
			action : 'workoutaction',
			method :  'action',
			id : '',
			foldid : '',
			logid : $('#wkout_log_id').val(),
			type : 'logged',
		},
		success: function(content){
			$('#myModal').html(content);
			$('#myModal').modal();
		}
	});
}
function doDeleteProcess(type, selector, targetId) {
   var wkoutlogid = $('#wkout_log_id').val();
	$('div.createworkout div.border').removeClass('new-item');
	$('div.optionmenu button.btn').removeClass('checked');
   if (type == 'exerciseset') {
      if (confirm('Deleting this Exercise Set will not be saved until all updates to the Workout Plan have been confirmed.')) {
         var targetset = $('li#itemSet_' + wkoutlogid + '_' + selector +' div.exercisesetdiv div#set_id_' + targetId);
         if (targetset.length) {
				var goalOrder = $('li#itemSet_' + wkoutlogid + '_' + selector +' input.seq_order_combine_up').val();
				if (targetId.indexOf('_new') === -1) 
					$('div.removedIds').append('<input type="hidden" name="goal_remove[' + targetId.replace(goalOrder + '_','') + ']" value="1"/>');
            if (targetset.next('hr').length > 0)
	            targetset.next('hr').andSelf().remove();
	         else
	            targetset.prev('hr').andSelf().remove();
				var incrId = $('li#itemSet_' + wkoutlogid + '_' + selector).attr('data-inner-cnt');
				$('li#itemSet_' + wkoutlogid + '_' + selector).attr('data-inner-cnt', parseInt(incrId) - 1);
				var inputsetVal = $('input#itemSet_' + wkoutlogid + '_' + selector + '_hidden').val();
				var inputUpdateVal = removeCommaWithValue(inputsetVal, targetId);
				$('input#itemSet_' + wkoutlogid + '_' + selector + '_hidden').val(inputUpdateVal);
				if($('div#itemset_' + selector + ' div.exercisesetdiv div.navimgdet2').length < 1){
					$('li#itemSet_' + wkoutlogid + '_' + selector).remove();
					$('#s_row_count').val($('#scrollablediv-len li').length);
					updateLiItems();
				}else{
					$("ul#sTree2 li div.navimgdet2").each(function(i, item) {
						$(item).find('.seq_order_up').val(i+1);
					});
				}
            enableButtons();
         }
         if ($('.editmode').hasClass('hide')) {
				changeTosaveIcon();
				closeModelwindow('myModalExerciseSetAct');
			}
			closeModelwindow('myOptionsModalAjax');
			closeModelwindow('myOptionsModal');
         return false;
      }
   } else if (type == 'workoutplan') {
      if (confirm('Deleting this Workout plan will not be saved until all updates to the My Workout Plans have been confirmed.')) {
         return true;
      }
   }
   return false;
}
function doDeleteProcessOld(type, selector){
	var logid = $('#wkout_log_id').val();
	$('div.createworkout div.border').removeClass('new-item');
	$('div.optionmenu button.btn').removeClass('checked');
	if(type == 'exerciseset'){
		if(confirm('Deleting this Exercise Set will not be saved until all updates to the Workout Plan have been confirmed.')){
			if (!isNaN(selector) && $('div#itemset_'+logid+'_'+selector).length){
				$('div.removedIds').append('<input type="hidden" name="goal_remove['+selector+']" value="1"/>');
				curOrder = $('#goal_order_'+selector).val();
				$('input.seq_order_up').each(function(i, field){
					if(curOrder < field.value){
						inputId = field.name.replace('[','_');
						inputId = inputId.replace(']','');
						$('input#'+inputId).val(field.value-1);
					}
				});
				$('div#itemset_'+logid+'_'+selector).parent('li').remove();
				$('#s_row_count').val($('#scrollablediv-len li').length);
				enableButtons();
			}else if($('div#itemset'+selector).length){
				if(selector.indexOf('new')>= 0){
					curOrder = $('#goal_order_'+selector).val();
					$('input.seq_order_up').each(function(i, field){
						if(curOrder < field.value){
							inputId = $(this).attr('id');
							$('input#'+inputId).val(field.value-1);
						}
					});
					$('div#itemset'+selector).parent('li').remove();
					$('#s_row_count').val($('#scrollablediv-len li').length);
					enableButtons();
				}else{
					inputcurCountArr = selector.split('_');
					inputcurCount	 = inputcurCountArr[inputcurCountArr.length-1];
					$('div.removedIds').append('<input type="hidden" name="goal_remove['+inputcurCount+']" value="1"/>');
					curOrder = $('#goal_order_'+selector).val();
					$('input.seq_order_up').each(function(i, field){
						if(curOrder < field.value){
							inputId = $(this).attr('id');
							$('input#'+inputId).val(field.value-1);
						}
					});
					$('div#itemset'+selector).parent('li').remove();
					$('#s_row_count').val($('#scrollablediv-len li').length);
					enableButtons();
				}
			}else if($('div#itemsetnew_'+selector).length){
				curOrder = $('#goal_order_new_'+selector).val();
				$('input.seq_order_up').each(function(i, field){
					if(curOrder < field.value){
						inputId = $(this).attr('id');
						$('input#'+inputId).val(field.value-1);
					}
				});
				$('div#itemsetnew_'+selector).parent('li').remove();
				$('#s_row_count').val($('#scrollablediv-len li').length);
				enableButtons();
			}
			if($('.editmode').hasClass('hide')){
				changeTosaveIcon();
				closeModelwindow('myModalExerciseSetAct');
			}
			closeModelwindow('myOptionsModalAjax');
			closeModelwindow('myOptionsModal');
			return false;
		}
	}else if(type == 'workoutplan'){
		if(confirm('Deleting this Workout plan will not be saved until all updates to the My Workout Plans have been confirmed.')){
			return true;
		}
	}
	return false;
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
function getExerciseSetpreview(exerciseSetId, wkoutId,selector){
	if(!$(selector).attr("disabled")){
		$('#myModal').html();
		$.ajax({
			url : siteUrl+"search/getmodelTemplate",
			data : {
				action : 'previewExercise',
				method : 'enablepreview',
				id : exerciseSetId,
				foldid : wkoutId,
				logid : $('#wkout_log_id').val(),
				ownWkFlag : $('#ownWkFlag').val(),
				type : 'logged'
			},
			success : function(content){
				$('#myModal').html(content);
				$('#myModal').modal();
			}
		});
	}
}
// by G.R
function getWkoutEditInstruction() {
	if(allowNotify){
	   $('#myOptionsModal').html();
	   $.ajax({
		  url: siteUrl + "search/getmodelTemplate",
		  data: {
			 action: 'editNotification',
			 modelType: 'myOptionsModal',
		  },
		  success: function(content) {
			 $('#myOptionsModal').html(content);
			 if(content !='')
				$('#myOptionsModal').modal();
		  }
	   });
	}
}
function editExercistSets(selector){
	$('.editmode').addClass('hide');
	$('.editmodesets').removeClass('hide');
	$(selector).addClass('hide');
	$('#refresh').removeClass('hide');
	$('#createwkout').addClass('hide');
	$('.optionmenu div.allowhide').removeClass('hide');
	$('.checkboxchoosen').show();
	$('a.editchoosenIconTwo').removeClass('hide');
	$('i.listoptionpop').addClass('hide');
	$('div.listoptionpopcheck').addClass('hide');
	$('.activelink').attr('disabled','disabled');
	getWkoutEditInstruction();
	$('input.checkhidden:checkbox').attr('checked', false);
	$('div.createworkout div.border').removeClass('new-item');
	$('div.optionmenu button.btn').removeClass('checked');
	if($('button i.allowActive').hasClass('activecol')){
		$('button i.allowActive').removeClass('activecol');
		$('button i.allowActive').addClass('datacol');
	}
	return false;
}
function editWorkout(selector){
	$('.editmode').removeClass('hide');
	$('.editmodesets').addClass('hide');
	$(selector).addClass('hide');
	$('#editxr').removeClass('hide');
	$('.optionmenu div.allowhide').addClass('hide');
	$('#createwkout').removeClass('hide');
	$('.checkboxchoosen').hide();
	$('a.editchoosenIconTwo').addClass('hide');
	$('i.listoptionpop').removeClass('hide');
	$('div.listoptionpopcheck').removeClass('hide');
	$('.activelink').removeAttr('disabled');
	$('input.checkhidden:checkbox').attr('checked',false);
	$('div.createworkout div.border').removeClass('new-item');
	$('div.optionmenu button.btn').removeClass('checked');
	return false;
}
function checkallItems(selector){
	if($(selector).hasClass('checked')){
		$(".checkboxcolor label input.checkhidden:checkbox").prop('checked', false);
		$(selector).removeClass('checked');
	}else{
		var checked = 1;
      $(".checkboxcolor label input.checkhidden:checkbox").each(function(i, field) {
			$(this).prop('checked', true);
			$(this).attr('data-check',checked);
			checked++;
      });
      $(selector).addClass('checked');
	}
	if($('.checkboxcolor label input.checkhidden[type="checkbox"]:checked').length > 0){
		$('button i.allowActive').removeClass('datacol');
		$('button i.allowActive').addClass('activecol');
	}else{
		$('button i.allowActive').addClass('datacol');
		$('button i.allowActive').removeClass('activecol');
	}
	$('div.createworkout div.border').removeClass('new-item');
	return false;
}
function allowOptions(selector){
	$(selector).addClass('hide');
	$('.optionmenu div').removeClass('hide');
	$('#checkboxbutton').removeClass('hide');
}
function saveLogWorkouts(flag){
	$('#myOptionsModal').html();
	$.ajax({
		url : siteUrl+"search/getmodelTemplate",
		data : {
			action : 'workoutLogConfirm',
			method : 'action',
			logid  : $('#wkout_log_id').val(),
			foldid : '',
			id : '',
			type : 'loggedwkout',
			goalOrder : flag,
			modelType : 'myOptionsModal',
			intensity : $('#per_intent_hidden').val(),
			remarks : $('#per_remarks_hidden').val()
		},
		success : function(content){
			if(content.trim() !=''){
				$('#myOptionsModal').html(content);
				$('#myOptionsModal').modal();
			}else{
				confirmLogDetails(flag);
			}
		}
	});
	return false;
}
function changeWkoutStatusExcise(selector, xrset, elem){
	var intensity  = $('#per_intent_'+xrset).val();
	var remarks    = $('#per_remarks_'+xrset).val();
	var flag       = $('#markedstatus_'+xrset).val();
	var mainDiv    = $('li#itemSet_'+$('#wkout_log_id').val()+ '_' + selector);
	if(!$(elem).attr('disabled')){
		if(mainDiv.find('.navimgdet1').text()!='Click_to_Edit'){
			$('#myOptionsModal').html();
			$.ajax({
				url : siteUrl+"search/getmodelTemplate",
				data : {
					action : 'workoutLogConfirm',
					method : 'action',
					logid  : $('#wkout_log_id').val(),
					foldid : selector,
					xrsetid : xrset,
					id : '',
					type : 'loggedexerciseconf',
					goalOrder : flag,
					modelType : 'myOptionsModal',
					intensity : intensity,
					remarks : remarks,
				},
				success : function(content){
					$('#myOptionsModal').html(content);
					$('#myOptionsModal').modal();
				}
			});
		}else{
			alert('Please fill the above empty set and then try again.');
			return false;
		}
	}
	return false;
}

function ChangeWkoutStatusExercise(flag,xrset,selector){
	var intensity = $('#per_intent_'+xrset);
	var remarks = $('#per_remarks_'+xrset);
	if(!$(selector).attr('disabled')){
		if($('div#itemsetnew_'+xrset).find('.navimgdet1').text()!='Click_to_Edit'){
			$('#myOptionsModal').html();
			$.ajax({
				url : siteUrl+"search/getmodelTemplate",
				data : {
					action : 'workoutLogConfirm',
					method : 'action',
					logid  : $('#wkout_log_id').val(),
					foldid : xrset,
					id : '',
					type : 'loggedexerciseconf',
					goalOrder : flag,
					modelType : 'myOptionsModal',
					intensity : intensity.val(),
					remarks : remarks.val()
				},
				success : function(content){
					$('#myOptionsModal').html(content);
					$('#myOptionsModal').modal();
				}
			});
		}else{
			alert('Please fill the above empty set and then try again.');
			return false;
		}
	}
	return false;
}
function ChangeWkoutStatusWorkouts(flag, selector, xrset){
	var wkoutlogid = $('#wkout_log_id').val();
	var targetset = $('li#itemSet_' + wkoutlogid + '_' + selector);
	if(targetset.find('.navimgdet1').text() != 'Click_to_Edit'){
		enablestatusButtons(xrset, parseInt(flag) - 1);
		$('div#myOptionsModal').modal('hidecustom');
		$('div#myModal').modal('hidecustom');
	}
}

function createCopyExerciseCombineSet(selector, move, xroption) {
	var wkoutlogid = $('#wkout_log_id').val();
 	if ($('li#itemSet_' + wkoutlogid + '_' + selector).length) {
		var selectorDiv = $('li#itemSet_' + wkoutlogid + '_' + selector);
		var selectorId = 'div#itemset_' + selector;
		var currentXrDiv = $('div#set_id_' + xroption);
		var goalOrder = $('div#itemset_' + selector +' input.seq_order_combine_up').val();
		var setidSelector = $('div#itemset_' + selector +' input#itemSet_' + wkoutlogid + '_' + selector+'_hidden');
	}
	var divArray = [];
	var divArrayAll = [];
	$('div#itemset_'+selector+' div.exercisesetdiv div').each(function(i,item){
		if($(item).attr('id'))
			divArray.push($(item).attr('id'));
		divArrayAll.push($(item).attr('id'));
	});
	currPos = divArrayAll.indexOf('set_id_'+xroption);
	last = divArray.length;
	
	if(move == 'last')
		var xrorder = last + 1;
	else if(move == 'down' || move == 'up')
		var xrorder = currPos + 1;
		
 	var inlineString = $(currentXrDiv).html();
	var valuexr = parseInt($('#newlyAddedXr').val()) + 1;
	$('#newlyAddedXr').val(valuexr);
	var xroptionNew = goalOrder+'_new_'+valuexr;
	var unitId = xroption.replace(goalOrder+'_','');
	var re1 = new RegExp(xroption, 'g');
	inlineString = inlineString.replace(re1, xroptionNew);
	var re2 = new RegExp(xroption+']', 'g');
	inlineString = inlineString.replace(re2, xroptionNew+']');
	var re3 = new RegExp('"'+xroption+'"', 'g');
	inlineString = inlineString.replace(re3, '"'+xroptionNew+'"');
	var re5 = new RegExp("'"+xroption+"'", 'g');
	inlineString = inlineString.replace(re5, "'"+xroptionNew+"'");
	var re4 = new RegExp(xroption+',this', 'g');
	inlineString = inlineString.replace(re4, xroptionNew+',this');
	var re6 = new RegExp(unitId+']', 'g');
	inlineString = inlineString.replace(re6, 'new_'+valuexr+']');
	var re7 = new RegExp('_'+unitId+'"', 'g');
	inlineString = inlineString.replace(re7, '_new_'+valuexr+'"');
	var re8 = new RegExp("'"+unitId+"'", 'g');
	inlineString = inlineString.replace(re8, "'"+'new_'+valuexr+"'");
	var xr_element = (move == 'down' || move == 'last' ? '<hr>' : '')+'<div id="set_id_'+goalOrder+'_new_'+ valuexr +'" class="navimgdet2" data-id="'+goalOrder+'_new_'+ valuexr +'">'+inlineString +'</div>' +(move == 'up' ? '<hr>' : '');
	if(move == 'down'){
		$(xr_element).insertAfter('div#itemset_'+selector+' div.exercisesetdiv div#set_id_'+xroption);
	}else if(move == 'up'){
		$(xr_element).insertBefore('div#itemset_'+selector+' div.exercisesetdiv div#set_id_'+xroption);
	}else
		$('div#itemset_'+selector+' div.exercisesetdiv').append(xr_element);
	$('div#itemset_'+selector+' div.exercisesetdiv div#set_id_'+goalOrder+'_new_'+ valuexr +'  .listoptionpop').attr("onclick", "getTemplateOfExerciseSetAction('" + selector + "','"+'new_'+ valuexr + "','link');");
	var setIds = setidSelector.val();
	$("ul#sTree2 li div.navimgdet2").each(function(i, item) {
		$(item).find('.seq_order_up').val(i+1);
	});
	setidSelector.val(setIds + ',' + goalOrder+'_new_'+ valuexr);
}

function createCopyExerciseSet(move) {
   $("input.checkhidden:checkbox:checked").each(function(i, field) {
      var unitdataid = $(field).val(),
         incid = $('#newlyAddedXr').val(),
         checkedSetItem = $(this).closest('li'),
         checkedSetItemId = $(checkedSetItem).attr('id'),
         checkedSetDataid = $(checkedSetItem).attr('data-id'),
         checkedSetOrder = $(checkedSetItem).attr('data-orderval'),
         innerSetDiv = $(checkedSetItem).find('div.exercisesetdiv');
      if (move != 'last') {
         var setids = $('#' + checkedSetItemId + '_hidden').val().split(','),
            activeSets = $(innerSetDiv).find('.navimgdet2').not('.deleted'),
            firstset = $(activeSets).first();
         if (activeSets.length < 1) {
            return false;
         }
         $(activeSets).each(function(i, sets) {
            incid = parseInt(incid) + 1;
            var oldsetid = $(sets).find('.seq_order_up').attr('id');
            oldsetid = oldsetid.replace('goal_order_', '');
            var order_setid = checkedSetOrder + '_new_' + incid,
               newsetid = 'new_' + incid;
            if (move == 'up') {
               $(sets).clone()
                  .attr({
                     'id': 'set_id_' + order_setid,
                     'data-id': order_setid
                  })
                  .html(function(i, htmltext) {
                     var regex = new RegExp(oldsetid, 'g');
                     return htmltext.replace(regex, newsetid);
                  })
                  .insertBefore(firstset);
               $('<hr>').insertAfter('li#' + checkedSetItemId + ' .navimagedetails #set_id_' + order_setid);
            } else if (move == 'down') {
               $(sets).clone()
                  .attr({
                     'id': 'set_id_' + order_setid,
                     'data-id': order_setid
                  })
                  .html(function(i, htmltext) {
                     var regex = new RegExp(oldsetid, 'g');
                     return htmltext.replace(regex, newsetid);
                  })
                  .insertAfter('li#' + checkedSetItemId + ' .navimagedetails .navimgdet2:last');
               $('<hr>').insertBefore('li#' + checkedSetItemId + ' .navimagedetails #set_id_' + order_setid);
            }
            $('input#' + checkedSetItemId + '_hidden').val(setids + ',' + order_setid);
            setids.push(order_setid);
            $('#newlyAddedXr').val(incid);
         });
      } else {
         var wkoutlogid = $('#wkout_log_id').val(),
            setLength = $('ul#sTree2 li').length,
            goalOrder = parseInt(setLength) + 1,
            count = parseInt($('#s_row_count_flag').val()) + 1;
         $('#s_row_count').val(goalOrder);
         $('#s_row_count_flag').val(count);
         var oldUnitDataId = unitdataid,
            newUnitDataId = goalOrder + '_' + checkedSetDataid,
            selector = 'itemSet_' + wkoutlogid + '_' + newUnitDataId,
            setids = [];
         if ($('div#itemset_' + oldUnitDataId).find('.navimgdet1').text() == 'Click_to_Edit') {
            alert('Please fill the above empty set and then try to add new set.');
            return false;
         }
         $(checkedSetItem).clone()
            .attr({
               'id': selector,
               'data-orderval': goalOrder
            })
            .find('.seq_order_combine_up').val(goalOrder).end()
            .find('.navimage').attr("onclick", "getTemplateOfExerciseRecordAction('" + newUnitDataId + "',this,'" + goalOrder + "');").end()
            .find('.exercisesetdiv .navimgdet2').each(function() {
               if ($(this).hasClass('deleted')) {
                  $(this).remove();
                  return true;
               }
               incid = parseInt(incid) + 1;
               var oldsetid = $(this).find('.seq_order_up').attr('id');
               oldsetid = oldsetid.replace('goal_order_', '');
               var newsetid = 'new_' + incid,
                  oldorder_setid = $(this).attr('data-id'),
                  neworder_setid = goalOrder + '_new_' + incid;
               $(this).attr({
                     'id': 'set_id_' + neworder_setid,
                     'data-id': neworder_setid
                  })
                  .find('.listoptionpop').attr("onclick", "getTemplateOfExerciseSetAction('" + newUnitDataId + "','" + newsetid + "','link');").end()
                  .html(function(i, htmltext) {
                     var regex1 = new RegExp(oldorder_setid, 'g');
                     htmltext = htmltext.replace(regex1, neworder_setid);
                     var regex2 = new RegExp(oldsetid, 'g');
                     htmltext = htmltext.replace(regex2, newsetid);
                     return htmltext;
                  });
               setids.push(neworder_setid);
               $('#newlyAddedXr').val(incid);
            }).end()
            .html(function(i, htmltext) {
               var regex1 = new RegExp('_' + oldUnitDataId, 'g');
               htmltext = htmltext.replace(regex1, '_' + newUnitDataId);
               var regex2 = new RegExp(oldUnitDataId + ']', 'g');
               htmltext = htmltext.replace(regex2, newUnitDataId + ']');
               var regex3 = new RegExp('"' + oldUnitDataId + '"', 'g');
               htmltext = htmltext.replace(regex3, '"' + newUnitDataId + '"');
               var regex4 = new RegExp("'" + oldUnitDataId + "'", 'g');
               htmltext = htmltext.replace(regex4, "'" + newUnitDataId + "'");
               return htmltext;
            })
            .appendTo($("ul#sTree2"));
         $('input#' + selector + '_hidden').val(setids.join(','));
      }
   });
   updateLiItems();
}
function createCopyExerciseSetOld(selector,move){
	var logid = $('#wkout_log_id').val();
	$('div.optionmenu button.btn').removeClass('checked');
	$('div.createworkout div.border').removeClass('new-item');
	if(selector == ''){
		var checkedArray = new Object();
	  var checkedArrayNew = new Object();
     $("input.checkhidden:checkbox:checked").each(function(i,field) {
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
				  $('#s_row_count').val(parseInt(last) + 1);
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
				  inlineString = inlineString.replace('"' + inputdata + '"', '"new_' + logid + '_' + count + '"');
				  var re5 = new RegExp("_" + inputdata + '"', 'g');
				  inlineString = inlineString.replace(re5, '_new_' + logid + '_' + count + '"');
				  var re5 = new RegExp("'" + inputdata + "'", 'g');
				  inlineString = inlineString.replace(re5, "'new_" + logid + '_' + count + "'");
				  inlineString = inlineString.replace(/col-xs-aa/g, 'col-xs-2');
				  inlineString = inlineString.replace(/col-xs-bb/g, 'col-xs-8');
				  inlineString = inlineString.replace(/col-xs-cc/g, 'col-xs-4');
				  if ($('div.createworkout').find('.navimgdet1').text() != 'Click_to_Edit') {
					 if(move == 'down'){
						$('#scrollablediv-len ul li:eq(' + updatedCnt + ')').after('<li id="itemSetnew_' + logid + '_0_' + count + '" class="bgC4 item_add_wkout_noclick new-item" data-module="item_set_new" data-id="new_' + logid + '_' + count + '"><div class="row createworkout" id="itemsetnew_' + logid + '_' + count + '">' + inlineString + '</div></li>');
					 }else if(move == 'last'){
						$('#scrollablediv-len ul li:eq(' + updatedCnt + ')').after('<li id="itemSetnew_' + logid + '_0_' + count + '" class="bgC4 item_add_wkout_noclick new-item" data-module="item_set_new" data-id="new_' + logid + '_' + count + '"><div class="row createworkout" id="itemsetnew_' + logid + '_' + count + '">' + inlineString + '</div></li>');
					 }else{
						$('#scrollablediv-len ul li:eq(' + updatedCnt + ')').before('<li id="itemSetnew_' + logid + '_0_' + count + '" class="bgC4 item_add_wkout_noclick new-item" data-module="item_set_new" data-id="new_' + logid + '_' + count + '"><div class="row createworkout" id="itemsetnew_' + logid + '_' + count + '">' + inlineString + '</div></li>');
					 }
					 var litagvariable = 'itemSetnew_' + logid + '_' + count;
					 $('#goal_remove_new_' + logid + '_' + count).attr('name', 'goal_remove_new[]');
					 $('#goal_order_new_' + logid + '_' + count).attr('name', 'goal_order_new[]');
					 $('#' + litagvariable + ' .navimgdet1').html("<b>" + titleName + "</b>");
					 $('#exercise_title_new_' + logid + '_' + count).val(titleName);
					 console.log('#exercise_title_new_' + logid + '_' + count);
					 if ($('#scrollablediv-len li').length > 3 && !$('#scrollablediv-len').hasClass('scrollablediv')) $('#scrollablediv-len').addClass('scrollablediv');
					 if ($('#scrollablediv-len li').length == '1') $('.sTreeBase').show();
				  } else {
					 alert('Please fill the above empty set and then try to add new set.');
				  }
				  $('#' + litagvariable + ' .editchoosenIconTwo').removeClass('hide');
				  $('#' + litagvariable + ' .listoptionpop').attr("class","fa fa-ellipsis-v iconsize listoptionpop hide");
				  $('#' + litagvariable + ' .listoptionpop').attr("onclick","getTemplateOfExerciseSetAction('"+'new_'+logid+'_'+count+"','link');");
				  enablestatusButtons('new_'+logid+'_'+count,'2');
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
            var updatedCnt = parseInt(i) + 1;
            if (selectorIdinner.indexOf("new_") < 0) {
               var xrIdval = xrId;
               var litagId = logid + '_' + xrIdval + '_' + updatedCnt;
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
				$('#scrollablediv-len ul').insertAt(liTagCnt,'<li id="'+(selectorIdinner.indexOf("new_") < 0 ? 'itemSet_' : 'itemSet' )+ litagId + '" class="bgC4 item_add_wkout_noclick" data-module="'+(selectorIdinner.indexOf("new_") < 0 ? 'item_set' : 'item_set_new' )+'" data-id="' + xrIdval + '">' + inlineString + '</li>');
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
				$('li#' + litagvariable + ' .listoptionpop').attr("class","fa fa-ellipsis-v iconsize listoptionpop hide");
				if (selectorIdinner.indexOf("new_") > 0)
					$('li#' + litagvariable + ' .listoptionpop').attr("onclick","getTemplateOfExerciseSetAction('"+'new_'+logid+'_'+updatedCnt+"','link');");
         }
      }
	  }
	  $('div#loading-indicator').hide();
	  $('div#scrollablediv-len ul').scrollTop($('div#scrollablediv-len ul li:nth-child('+litagscrollcnt+')').position().top - $('div#scrollablediv-len ul li:first').position().top);
	  enableButtons();
     $('#s_row_count').val($('#scrollablediv-len li').length);
	}else{
		if (!isNaN(selector) && $('div#itemset_'+logid+'_'+selector).length){
			var selectorDiv = $('div#itemset_'+logid+'_'+selector);
			var selectorId  = 'div#itemset_'+logid+'_'+selector;
			var inputdata   = selector;
			var current 	= $('div#itemset_'+logid+'_'+selector).parent('li').index();
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
		$('#s_row_count').val(parseInt(last)+1);
		var inlineString = $(selectorDiv).html();
		inlineString = inlineString.replace(/col-xs-2/gi,'col-xs-aa');
		inlineString = inlineString.replace(/col-xs-8/gi,'col-xs-bb');
		inlineString = inlineString.replace(/col-xs-4/gi,'col-xs-cc');
		var dataForm = $(selectorId+' .navbarmenu input').serializeArray();
		var titleName = '';
		var updatedCnt = parseInt($('#s_row_count_flag').val()) + 1;
	   $('#s_row_count_flag').val(updatedCnt);
		var replaceFlag = true;
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
		inlineString = inlineString.replace('"'+inputdata+'"','"new_'+logid+'_'+updatedCnt+'"');
		var re5 = new RegExp("_"+inputdata+'"','g');
		inlineString = inlineString.replace(re5,'_new_'+logid+'_'+updatedCnt+'"');
		var re5 = new RegExp("'"+inputdata+"'",'g');
		inlineString = inlineString.replace(re5,"'new_"+logid+'_'+updatedCnt+"'");
		inlineString = inlineString.replace(/col-xs-aa/g,'col-xs-2');
		inlineString = inlineString.replace(/col-xs-bb/g,'col-xs-8');
		inlineString = inlineString.replace(/col-xs-cc/g,'col-xs-4');
		if($('div.createworkout').find('.navimgdet1').text()!='Click_to_Edit'){
			if(move == 'down') $('#scrollablediv-len ul li:eq('+current+')').after('<li id="itemSetnew_'+logid+'_0_'+updatedCnt+'" class="bgC4 item_add_wkout_noclick new-item" data-module="item_set_new" data-id="new_'+logid+'_'+updatedCnt+'"><div class="row createworkout" id="itemsetnew_'+logid+'_'+updatedCnt+'">'+inlineString+'</div></li>');
			else if(move == 'last')	$('#scrollablediv-len ul li:eq('+count+')').after('<li id="itemSetnew_'+logid+'_0_'+updatedCnt+'" class="bgC4 item_add_wkout_noclick new-item" data-module="item_set_new" data-id="new_'+logid+'_'+updatedCnt+'"><div class="row createworkout" id="itemsetnew_'+logid+'_'+updatedCnt+'">'+inlineString+'</div></li>');
			else	$('#scrollablediv-len ul li:eq('+count+')').before('<li id="itemSetnew_'+logid+'_0_'+updatedCnt+'" class="bgC4 item_add_wkout_noclick new-item" data-module="item_set_new" data-id="new_'+logid+'_'+updatedCnt+'"><div class="row createworkout" id="itemsetnew_'+logid+'_'+updatedCnt+'">'+inlineString+'</div></li>');
			var litagscrollcnt = (count == 0 ? count + 1 : count);
			$('#goal_remove_new_'+logid+'_'+updatedCnt).attr('name', 'goal_remove_new[]');
			$('#goal_order_new_'+logid+'_'+updatedCnt).attr('name', 'goal_order_new[]');
			if($('#scrollablediv-len li').length > 3 && !$('#scrollablediv-len').hasClass('scrollablediv')) $('#scrollablediv-len').addClass('scrollablediv');
			if($('#scrollablediv-len li').length == '1')	$('.sTreeBase').show();
		}else{
			alert('Please fill the above empty set and then try to add new set.');
		}
		$('#itemsetnew_'+logid+'_'+updatedCnt+' .editchoosenIconTwo').addClass('hide');
		$('#itemsetnew_'+logid+'_'+updatedCnt+' .listoptionpop').attr("class","fa fa-ellipsis-v iconsize listoptionpop");
		$('#itemsetnew_'+logid+'_'+updatedCnt+' .listoptionpop').attr("onclick","getTemplateOfExerciseSetAction('"+'new_'+logid+'_'+updatedCnt+"','link');");
		var lists = $("#scrollablediv-len ul li");
		$('div#loading-indicator').show();
		$("#scrollablediv-len ul").empty();
		var originalCnt = 0;
		if(originalCnt < lists.length){
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
				inlineString = inlineString.replace(/col-xs-2/gi,'col-xs-aa');
				inlineString = inlineString.replace(/col-xs-8/gi,'col-xs-bb');
				inlineString = inlineString.replace(/col-xs-4/gi,'col-xs-cc');
				inlineString = inlineString.replace(/new-item/g, '');
				var updatedCnt = parseInt(i) + 1;
				if(selectorIdinner.indexOf("new_") < 0){
					var xrIdval = xrId;
					var litagId = logid+'_'+xrIdval+'_'+updatedCnt;
				}else{
					var xrIdArr = xrId.split('_');
					var xrIdvalOrder = xrIdArr[xrIdArr.length - 2]+'_'+xrIdArr[xrIdArr.length - 1];
					var xrIdval = xrId.replace(xrIdvalOrder,xrIdArr[xrIdArr.length - 2]+'_'+updatedCnt);
					var litagId = xrId.replace(xrIdvalOrder,xrIdArr[xrIdArr.length - 2]+'_0_'+updatedCnt);
					var xrId 	= escapeRegExp(xrId);
					var re1 	= new RegExp(xrId,'g');
					inlineString= inlineString.replace(re1,xrIdval);
					var xrIdnew 	= escapeRegExp(xrId.replace('new_',''));
					var re2 		= new RegExp(xrIdnew,'g');
					inlineString= inlineString.replace(re2,xrIdval.replace('new_',''));
				}
				var titleNameCont = '';
				inlineString = inlineString.replace(/col-xs-aa/g,'col-xs-2');
				inlineString = inlineString.replace(/col-xs-bb/g,'col-xs-8');
				inlineString = inlineString.replace(/col-xs-cc/g,'col-xs-4');
				$('#scrollablediv-len ul').insertAt(liTagCnt,'<li id="'+(selectorIdinner.indexOf("new_") < 0 ? 'itemSet_' : 'itemSet' )+ litagId + '" class="bgC4 item_add_wkout_noclick" data-module="'+(selectorIdinner.indexOf("new_") < 0 ? 'item_set' : 'item_set_new' )+'" data-id="' + xrIdval + '">' + inlineString +'</li>');
				if (selectorIdinner.indexOf("new_") < 0)
					var litagvariable = 'itemSet_' + litagId;
				else
					var litagvariable = 'itemSet' + litagId;
				if(newItem !='')
					$('li#' + litagvariable + ' div.border').addClass('new-item');
				if(originalCnt >3 && !$('#scrollablediv-len').hasClass('scrollablediv'))
					$('#scrollablediv-len').addClass('scrollablediv');
				if($('#scrollablediv-len li').length == '1')	$('.sTreeBase').show();
				$('#goal_order_'+xrIdval).val(updatedCnt);
				$('li#' + litagvariable + ' .editchoosenIconTwo').addClass('hide');
				$('li#' + litagvariable + ' .listoptionpop').attr("class","fa fa-ellipsis-v iconsize listoptionpop");
				if (selectorIdinner.indexOf("new_") > 0)
					$('li#' + litagvariable + ' .listoptionpop').attr("onclick","getTemplateOfExerciseSetAction('"+'new_'+logid+'_'+updatedCnt+"','link');");
				enablestatusButtons('new_'+logid+'_'+updatedCnt,'2');
			}
		}
		$('div#loading-indicator').hide();
		$('div#scrollablediv-len ul').scrollTop($('div#scrollablediv-len ul li:nth-child('+litagscrollcnt+')').position().top - $('div#scrollablediv-len ul li:first').position().top);
		$('#s_row_count').val($('#scrollablediv-len li').length);
	}
}
function enableButtons(){
	if($('.checkboxcolor label input.checkhidden[type="checkbox"]:checked').length > 0){
		$('button i.allowActive').removeClass('datacol');
		$('button i.allowActive').addClass('activecol');
	}else{
		$('button i.allowActive').addClass('datacol');
		$('button i.allowActive').removeClass('activecol');
	}
}
(function($) {
	$.fn.parentNth = function(n) {
		var el = $(this);
		for(var i = 0; i < n; i++)
			el = el.parent();
		
		return el;
	};
})(jQuery);

function deleteExerciseSet() {
   if (confirm('Deleting this Exercise Set will not be saved until all updates to the Workout Plan have been confirmed.')) {
		$("input.checkhidden:checkbox:checked").each(function() {
			var curOrder = $('#goal_order_combine_' + $(this).val()).val();
			var selectorId = $(this).parentNth(7).attr('id');
			var totalxr = $('li#' + selectorId).attr('data-inner-cnt');
			if (selectorId.indexOf('new') >= 0) {
				$('li#' + selectorId).remove();
			} else {
				var inputcurCount = $('li#' + selectorId + ' input#'+selectorId+'_hidden').val();
				var inputcurCountArr = inputcurCount.split(',');
				for(var i=0; i < inputcurCountArr.length; i++){
					if(inputcurCountArr[i].indexOf('new') == -1)
						$('div.removedIds').append('<input type="hidden" name="goal_remove[' + inputcurCountArr[i].replace(curOrder + '_', '') + ']" value="1"/>');
				}
				$('li#' + selectorId).remove();
			}
			$('#s_row_count').val($('#scrollablediv-len li').length);
			// var xrcount = $('#newlyAddedXr').val();
			// if(xrcount > 0)
			// 	$('#newlyAddedXr').val(parseInt(xrcount)- parseInt(totalxr));
	 	});
   }
	updateLiItems();
	enableButtons();
}
function deleteExerciseSetOld(){
	if(confirm('Deleting this Exercise Set will not be saved until all updates to the Assigned Plan have been confirmed.')){
		$("input.checkhidden:checkbox:checked").each(function(i, fid){
			curOrder = $('#goal_order_'+$(this).val()).val();
			var selectorId = $(this).parentNth(6).attr('id');
			if(selectorId.indexOf('new')>= 0){
				$('div#'+selectorId).parent('li').remove();
				$('#s_row_count').val($('#scrollablediv-len li').length);
			}else{
				inputcurCountArr = selectorId.split('_');
				inputcurCount    = inputcurCountArr[inputcurCountArr.length - 1];
				$('div.removedIds').append('<input type="hidden" name="goal_remove['+inputcurCount+']" value="1"/>');
				$('div#'+selectorId).parent('li').remove();
				$('#s_row_count').val($('#scrollablediv-len li').length);
			}
			$('input.seq_order_up').each(function(i, field){
				if(curOrder < field.value){
					inputId = $(this).attr('id');
					$('input#'+inputId).val(field.value-1);
				}
			});
		});
		enableButtons();
	}
}
function confirmLogDetails(flag){
	var formData = $('#addAssignWorkouts').serializeArray();
	$('.errormsglogged').hide();
	$('#save_edit').val(flag);
	$(formData).each(function(i, field){
		if(field.name == 'slider-1')
			$('#per_intent_hidden').val(field.value);	
		else if(field.name == 'note_wkout_remarks'){
			$('#per_remarks_hidden').val(field.value);
		}
	});
	setTimeout(function(){$('#form-workoutrec').submit();}, 200);
}
function confirmExerciseDetails(xrsetid, xrid, flag){
	var formData = $('#addAssignWorkouts').serializeArray();
	$('.errormsglogged').hide();
	var wkoutlogId  = $('#wkout_log_id').val();
	var selector    = 'li#itemSet_'+wkoutlogId+ '_' + xrsetid;
	var per_remarks = $('#per_remarks_'+xrid);
	var mareked_st  = $('#markedstatus_'+xrid);
	var notesFlag   = $('#hide_notes_set_'+xrid);
	var mareked_stus= $('#markstatus_'+xrid);
	var per_intent  = $('#per_intent_'+xrid);
	var order = $(selector).find('.seq_order_combine_up').val();
	var order_setid = order + xrid;
	$(formData).each(function(i, field){
		if(field.name == 'slider-1'){
			per_intent.val(field.value);
			matchesval = document.getElementById("note_wkout_intensity").options[field.value].text;
			if(matchesval != 'Select'){
				var regExp = /\(([^)]+)\)/;
				var matches = regExp.exec(matchesval);
				$(selector+' div#set_id_'+order_setid+' a.exercise_intent_div').html(matches[1]+' Perceived Intensity');
				$(selector+' div#set_id_'+order_setid+' div.navremarkdetails').removeClass('hide');
			}else
				$(selector+' div#set_id_'+order_setid+' a.exercise_intent_div').html('');
		}else if(field.name == 'note_wkout_remarks'){
			per_remarks.val(field.value);
			enablestatusButtons(xrid, parseInt(flag)-1);
			closeModelwindow('myOptionsModalOpt');
			closeModelwindow('myOptionsModal');
			closeModelwindow('');
			$(selector+' div#set_id_'+order_setid+' a.exercise_remarks_div').html(field.value);
			if(field.value != '' && $(selector+' div#set_id_'+order_setid+' div.navremarkdetails').hasClass('hide'))
				$(selector+' div#set_id_'+order_setid+' div.navremarkdetails').removeClass('hide');
		}else if(field.name == 'is_hide_note_set' && field.value=='1'){
			notesFlag.val(field.value);
		}
	});
}
function skipExerciseNotes(xrid, flag, model){
	closeModelwindow(model);
	closeModelwindow('');
}
function skipExerciseNotesToLog(xrid, flag, model){
	var mareked_st = $('#markedstatus_'+xrid);
	var mareked_stus = $('#markstatus_'+xrid);
	mareked_st.val(flag);
	enablestatusButtons(xrid, parseInt(flag)-1);
	closeModelwindow(model);
	closeModelwindow('');
}
function skipWorkoutNotesToLog(flag,model){
	$('#save_edit').val(flag);
	$('#form-workoutrec').submit();
}
function getXrImageRecords(xrid){
	modalName = 'myOptionsModalExerciseRecord';
	$('#'+modalName).html();
	$.ajax({
		url : siteUrl+"search/getmodelTemplate",
		data : {
			action : 'relatedRecords',
			method : 'previewimage',
			id : xrid,
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
			method : 'previewimageSeq',
			id : xrid,
			foldid : seqId,
			modelType : modalName,
		},
		success : function(content){
			$('#'+modalName).html(content);
			$('#'+modalName).modal();
		}
	});
}
function escapeRegExp(stringToGoIntoTheRegex) {
    return stringToGoIntoTheRegex.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
}
function addAssignWorkouts(wkoutLogid){
	$('#myModal').html();
	$.ajax({
		url : siteUrl+"search/getmodelTemplate",
		data : {
			action : 'addAssignWorkouts',
			method : 'wkoutLogCal',
			id : 0,
			fid : 0,
			logid :wkoutLogid,
			date : $('#selected_date_hidden').val(),
			type : 'wkoutLogCal',
			modelType : 'myModal',
		},
		success : function(content){
			$('#myModal').html(content);
			$('#myModal').modal();
		}
	});
}
function addDatetoAssign(date,ModelType){
	var daterel = date;
	date = date.replace(' ','-');
	date = date.replace(' ','-');
	var title =$('#wrkoutname_hidden').val();
	title = title.replace(date,'');
	formdata = $('form#addAssignWorkouts').serializeArray();
	$(formdata).each(function(i, field){
		if(field.name == 'selected_date'){
			dateval = field.value;
			dateval = dateval.replace(' ','-');
			dateval = dateval.replace(' ','-');
			$('#wrkoutname_hidden').val(title.trim()+' '+dateval);
			$('.wrkoutname_hidden').html('<b>'+title.trim()+' '+dateval+'</b>');
			$('#'+field.name+'_hidden_text').text(field.value);
			$('#'+field.name+'_hidden').val(field.value);
		}
	});
	closeModelwindow(ModelType);
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

function insertFromRelatedToXrSet(oldinsertId , unit_id, order) {
   var oldUnit_id = oldinsertId.replace(order+'_','');
   if ($('.editmode').hasClass('hide')) changeTosaveIcon();
   var xrtitleVal = $('#popup_hidden_exerciseset_title_opt'+unit_id).val();
   var xrimg = $('#popup_hidden_exerciseset_image_opt'+unit_id).val();
   var wkout_log_id = $('#wkout_log_id').val();
   var selector = 'li#itemSet_' + wkout_log_id + '_' + oldinsertId;
   var inlineString = $(selector).html();
   var re1 = new RegExp('_'+oldinsertId, 'g');
   inlineString = inlineString.replace(re1, '_'+order+'_'+unit_id);
   var re2 = new RegExp(oldinsertId+']', 'g');
   inlineString = inlineString.replace(re2, order+'_'+unit_id+']');
   var re3 = new RegExp('"'+oldinsertId+'"', 'g');
   inlineString = inlineString.replace(re3, '"'+order+'_'+unit_id+'"');
   var re5 = new RegExp("'"+oldinsertId+"'", 'g');
   inlineString = inlineString.replace(re5, "'"+order+'_'+unit_id+"'");
   var re4 = new RegExp(oldUnit_id+',this', 'g');
   inlineString = inlineString.replace(re4, unit_id+',this');
   $(selector).html(inlineString);
   if ($(selector).length) {
      var xrtitle = 'input.exercise_title_xr_hidden';
      var xrtag = 'input.exercise_unit_xr_hidden';
   }
   $(selector + ' .navimgdet1').html('<b>' + xrtitleVal + '</b>');
   $(selector).attr('data-title', base64_encode(xrtitleVal));
   $(selector + ' .navimage').removeAttr('onclick');
   $(selector + ' ' + xrtag).val(order+'_'+unit_id);
   $(selector + ' ' + xrtitle).val(xrtitleVal);
   if (xrimg != '') $(selector + ' .navimage').html('<img width="75px;" src="' + xrimg + '"  class="img-responsive pointers" />');
   else $(selector + ' .navimage').html('<i class="fa fa-file-image-o pointers" style="font-size:50px;">');
   $(selector + ' .navimage').attr("onclick", "getTemplateOfExerciseRecordAction('" + order + '_'+ unit_id + "',this,'"+order+"');");
   $(selector).attr("data-id",unit_id);
   $(selector).attr("id",'itemSet_' + wkout_log_id + '_' + order+'_'+unit_id);
   $('#myOptionsModalExerciseRecord_option').modal('hide');
   $('#myOptionsModalExerciseRecord').modal('hide');
   updateLiItems();
   return true;
}

function getTemplateOfAssignActionByJournal(wkoutId, wkoutlogId , assignedDate , assignedby){
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
			type	: 'assignedCalPage',
			date   : assignedDate
		},
		success : function(content){
			$('#myModal').html(content);
			$('#myModal').modal();
		}
	});
}
function addLogWorkouts(wkoutlogId, wkoutId, date, method){
	$('#myOptionsModal').html();
	if(method == 'wkoutlog'){
		var type = 'logged';
	}else if(method == 'wkoutassign'){
		var type = 'wkoutAssignCal';
	}
	$.ajax({
		url : siteUrl+"search/getmodelTemplate/",
		data : {
			action : 'addAssignWorkouts',
			method :  method,
			id : wkoutlogId,
			logid :wkoutlogId,
			date   : date,
			type   : type,
			modelType : "myOptionsModal"
		},
		success : function(content){
			$('#myOptionsModal').html(content);
			$('#myOptionsModal').modal();
		}
	});
	return false;
}
function jounalOptionsConfirm(fid,date,method){	
	formdata = $('form#addAssignWorkouts').serializeArray();
	$(formdata).each(function(i, field){
		if(field.name == 'selected_date'){
			date = field.value;
		}
	});
	$('#myOptionsModalExerciseRecord').html();
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
			modelType : 'myOptionsModalExerciseRecord',
		},
		success : function(content){
			$('#myOptionsModalExerciseRecord').html(content);
			$('#myOptionsModalExerciseRecord').modal();
		}
	});
	return false;
}
function enableExport(logId,type){
	$('#myOptionsModal').html();
	$.ajax({
		url : siteUrl+"search/getmodelTemplate/",
		data : {
			action : 'exportActionOptions',
			method : 'action',
			id : logId,
			foldid : 0,
			modelType : "myOptionsModal",
			type : type
		},
		success : function(content){
			$('#myOptionsModal').html(content);
			$('#myOptionsModal').modal();
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
		closeModelwindow('myOptionsModal');
		closeModelwindow('');
		$('div#successmsgdiv').html('<div class="banner success alert alert-success"><a data-ajax="false" data-role="none" href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><span class="successmsg">Export Mail sent Successfully!!!</span></div>');
	});
	return true;
}
function doDeleteLogProcess(){
	if(confirm('Are you sure want to delete this Journal?'))
		return true;
	return false;
}
function insertTagOfRecord(xrId){
	$('#myOptionsModalExerciseRecord').html();
	$.ajax({
		url : siteUrl+"search/getmodelTemplate",
		data : {
			action : 'relatedRecords',
			method :  'tagRecord',
			id    : xrId,
			modelType : 'myOptionsModalExerciseRecord',
			editFlag : true
		},
		success : function(content){
			$('#myOptionsModalExerciseRecord').html(content);
			$('#myOptionsModalExerciseRecord').modal();
		}
	});
}
function enablestatusButtons(xrid, flag){
	var curFlag 	= 0;
	var iconFlag 	= '';
	if(parseInt(xrid) == xrid || xrid.indexOf('new_')>= 0)
		var xrid = xrid;
	else
		var xrid = 'new_'+xrid;
	console.log(flag);
	if(flag == '1' || flag == '0')
		curFlag = parseInt(flag) + 1;
	else if(flag == '2')
		curFlag = parseInt(flag) % 2;
	if(curFlag == '1')
		iconFlag = ' fa-check-square-o greenicon pointers ' ;
	else if(curFlag == '2')
		iconFlag = ' fa-minus-square-o pinkicon pointers ';
	console.log(curFlag+'--->'+iconFlag+'===>'+xrid);
	if(curFlag == '1' || curFlag == '2'){
		$('div#checkboxmark_'+xrid).html('<i onclick="enablestatusButtons('+"'"+xrid+"'"+','+"'"+curFlag+"'"+');" class="fa '+iconFlag+' iconsize listoptionpopcheck" ></i>');
		changeTosaveIcon();
	}else{
		$('div#checkboxmark_'+xrid).html('<label><input onclick="enablestatusButtons('+"'"+xrid+"'"+','+"'"+curFlag+"'"+');" data-role="none" data-ajax="false"  type="checkbox" class="checkhiddenstatus" name="exercisestatus[]" value="'+xrid+'"><span class="cr checkbox-circle" style="border-radius: 20%;"><i class="cr-icon fa fa-check"></i></span></label>');
		changeTosaveIcon();
	}
	var mareked_st = $('#markedstatus_'+xrid);
	var mareked_stus = $('#markstatus_'+xrid);	
	mareked_st.val(curFlag);
}
function addWorkoutLogNotes(flag, selector, xrset){
	var intensity  = $('#per_intent_'+xrset).val();
	var remarks    = $('#per_remarks_'+xrset).val();
	var notesFlag  = $('#hide_notes_set_'+xrset).val();
	var mainDiv    = $('li#itemSet_' + $('#wkout_log_id').val() + '_' + selector);
	if(mainDiv.find('.navimgdet1').text()!='Click_to_Edit' && notesFlag == '0'){
		$('#myOptionsModalOpt').html();
		$.ajax({
			url : siteUrl+"search/getmodelTemplate",
			data : {
				action : 'workoutLogConfirm',
				method : 'action',
				logid : '0',
				foldid : selector,
				xrsetid : xrset,
				id : '',
				type : 'loggedexercise',
				goalOrder : flag,
				modelType : 'myOptionsModalOpt',
				intensity : intensity,
				remarks : remarks,
			},
			success : function(content){
				if(content.trim() !=''){
					$('#myOptionsModalOpt').html(content);
					$('#myOptionsModalOpt').modal();
				}else{
					confirmExerciseDetails(xrset,flag);
				}
			}
		});
	}else{
		alert('Please fill the above empty set and then try again.');
		return false;
	}
	return false;
}
function checkTitleExist(selector) {
   if ($('li#itemSet_' + selector).length && !$('li#itemSet_' + selector).hasClass('hide')) {
      curOrder = $('li#itemSet_' + selector+' input.seq_order_combine_up').val();
		var modaldata = $('#createExercise div.tab-content.set-tab div.tab-pane');
		modaldata.each(function(i, tab) {
			var setId = $(this).attr('data-setid');
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
			if ((!valid || valid) && $('li#itemSet_' + selector+' div.exercisesetdiv div#set_id_'+setId).hasClass('hide')) {
				if ($('li#itemSet_' + selector+' div.exercisesetdiv div#set_id_'+setId).next('hr').length > 0)
				   $('li#itemSet_' + selector+' div.exercisesetdiv div#set_id_'+setId).next('hr').andSelf().remove();
				else
				   $('li#itemSet_' + selector+' div.exercisesetdiv div#set_id_'+setId).prev('hr').andSelf().remove();
				inputVals = $('li#itemSet_' + selector+' input#itemSet_' + selector+'_hidden').val();
				var inputVals = removeCommaWithValue(inputVals,setId);
				$('li#itemSet_' + selector+' input#itemSet_' + selector+'_hidden').val(inputVals);
				
				var incrId  = $('li#itemSet_' + selector).attr('data-inner-cnt');
				$('li#itemSet_' + selector).attr('data-inner-cnt', parseInt(incrId)-1);
				
				if($('li#itemSet_' + selector).attr('data-inner-cnt').length == 0){
					$('li#itemSet_' + selector).remove();
					$('#s_row_count').val($('#scrollablediv-len li').length);
				}
				var xrcount = $('#newlyAddedXr').val();
				if(xrcount > 0)
					$('#newlyAddedXr').val(xrcount-1);
			}
		});
	}else if ($('li#itemSet_' + selector).length && $('li#itemSet_' + selector).hasClass('hide')) {
		totalxr = $('li#itemSet_' + selector).attr('data-inner-cnt');
		$('li#itemSet_' + selector).remove();
		$('#s_row_count').val($('#scrollablediv-len li').length);
		var xrcount = $('#newlyAddedXr').val();
		if(xrcount > 0)
			$('#newlyAddedXr').val(parseInt(xrcount) - parseInt(totalxr));
	}
	$("ul#sTree2 li div.navimgdet2").each(function(i, item) {
		$(item).find('.seq_order_up').val(i+1);
	});
	enableButtons();
	return false;
}
$(document).on('click', 'div a.datadetail',function(e){
	if($('.editmode').hasClass('hide')) changeTosaveIcon();
	e.stopPropagation();
	var selectorDataId = $(this).parentNth(10).attr('data-id');
	var selectorDataOrder = $(this).parentNth(10).attr('data-orderval');
	var selectorId = selectorDataOrder+'_'+selectorDataId;
	var selectorSetId = $(this).parentNth(2).attr('data-id');
	if($(this).hasClass('exercise_repetitions_div'))
		editWorkoutRecord(selectorId,'preview#openlink-reps',selectorSetId);
	else if($(this).hasClass('exercise_time_div'))
		editWorkoutRecord(selectorId,'preview#openlink-time',selectorSetId);
	else if($(this).hasClass('exercise_distance_div'))
		editWorkoutRecord(selectorId,'preview#openlink-dist',selectorSetId);
	else if($(this).hasClass('exercise_resistance_div'))
		editWorkoutRecord(selectorId,'preview#openlink-resist',selectorSetId);
	else if($(this).hasClass('exercise_rate_div'))
		editWorkoutRecord(selectorId,'preview#openlink-rate',selectorSetId);
	else if($(this).hasClass('exercise_angle_div'))
		editWorkoutRecord(selectorId,'preview#openlink-angle',selectorSetId);
	else if($(this).hasClass('exercise_innerdrive_div'))
		editWorkoutRecord(selectorId,'preview#openlink-int',selectorSetId);
	else if($(this).hasClass('exercise_rest_div'))
		editWorkoutRecord(selectorId,'preview#openlink-rest',selectorSetId);
});
/*opens option modal for create a xr-rec*/
function createExercise(xrid, type){
	xrLibCreateExercise();
}
function createCopyXrPopup(){
	if ($('.checkboxcolor label input[type="checkbox"]:checked').length > 0) {
		$('#myModal').html();
		$.ajax({
			url: siteUrl + "search/getmodelTemplate",
			data: {
				action: 'xrsettoolbaraction',
				method: 'copy',
				id: '',
				foldid: ''
			},
			success: function(content) {
				$('#myModal').html(content);
				$('#myModal').modal();
			}
		});
	}
	return false;
}
/*opens xr filter modal for create a xr-rec*/
function createExerciseFromXrLibrary(type){
	$.ajax({
		url : siteUrl+"search/getmodelTemplate/",
		data : {
			action : 'exerciseLibrary',
			requestFrom : 'dashboard'
		},
		success : function(content){
			$('#exerciselib-template').remove();
			$('body').append(content);
			if($('#exerciselib-model').length){
				setTimeout(function(){
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