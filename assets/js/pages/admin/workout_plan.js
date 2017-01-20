var checked= 1;
$(document).on('click', '.checkboxcolor label input.checkhiddenpopup[type="checkbox"]', function() {
	if($(this).prop("checked") == true && typeof($(this).attr('data-check')) == 'undefined'){
		$(this).attr('data-check',checked);
		checked++;
	}
});
$(document).ready(function() {
   $('.checkboxchoosen').hide();
   $('.checkhidden').attr('checked', false);
   $('.item_workout_click').prop('onclick', null).off('click');
});

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
}

function updateLiItems(){
   var dataArr = [];
   var dataArrNew = {};
   var prev = ''; var next = ''; var cur =''; 
   var results = {};
   var positions = {};
   $( "ul#sTree3 li" ).each(function(i, lival){
      if($.isNumeric( $( "ul#sTree3 li" ).eq(i).attr('data-id') ))
         dataArr.push($( "ul#sTree3 li" ).eq(i).attr('data-id'));
      else
         dataArr.push($( "ul#sTree3 li" ).eq(i).attr('data-title'));
   });
   dataArr.forEach(function(item, index) { 
      if ((index > 0 && dataArr[index-1] == item) || (index < dataArr.length+1 && dataArr[index+1] == item)) {
         results[item] = (results[item] || 0) + 1;
         (positions[item] || (positions[item] = [])).push(index);
      }
   });
   $( "ul#sTree3 li" ).each(function(i, lival){
      var keyval = $( "ul#sTree3 li" ).eq(i).attr('data-id');
      var keytitle = $( "ul#sTree3 li" ).eq(i).attr('data-title');
      if(positions[keyval] != undefined && $.inArray(i,positions[keyval]) != '-1' && $.isNumeric( $( "ul#sTree3 li" ).eq(i).attr('data-id') )){
         var removedCount = 0;
         for(var keynew = 1 ; keynew < positions[keyval].length; keynew++){
            $( "ul#sTree3 li" ).eq(positions[keyval][0]).find('.exercisesetdiv').append('<hr>');
            curele = $( "ul#sTree3 li" ).eq(positions[keyval][keynew - removedCount]).find('.exercisesetdiv').html();
            newOrder = $( "ul#sTree3 li" ).eq(positions[keyval][0]).find('input.seq_order_combine_up').val();
            oldOrder = $( "ul#sTree3 li" ).eq(positions[keyval][keynew - removedCount]).find('input.seq_order_combine_up').val();
            var re5 = new RegExp(oldOrder + '_', 'g');
            curele = curele.replace(re5, newOrder+'_');
            $( "ul#sTree3 li" ).eq(positions[keyval][0]).find('.exercisesetdiv').append(curele);
            $( "ul#sTree3 li" ).eq(positions[keyval][keynew - removedCount]).remove();
            removedCount++;
         }
         delete positions[keyval];
      }else if(positions[keytitle] != undefined && $.inArray(i,positions[keytitle]) != '-1'){
         var removedCount = 0;
         for(var keynew = 1 ; keynew < positions[keytitle].length ; keynew++){
            $( "ul#sTree3 li" ).eq(positions[keytitle][0]).find('.exercisesetdiv').append('<hr>');
            curele = $( "ul#sTree3 li" ).eq(positions[keytitle][keynew - removedCount]).find('.exercisesetdiv').html();
            newOrder = $( "ul#sTree3 li" ).eq(positions[keytitle][0]).find('input.seq_order_combine_up').val();
            oldOrder = $( "ul#sTree3 li" ).eq(positions[keytitle][keynew - removedCount]).find('input.seq_order_combine_up').val();
            var re5 = new RegExp(oldOrder + '_', 'g');
            curele = curele.replace(re5, newOrder+'_');
            $( "ul#sTree3 li" ).eq(positions[keytitle][0]).find('.exercisesetdiv').append(curele);
            $( "ul#sTree3 li" ).eq(positions[keytitle][keynew - removedCount]).remove();
            removedCount++;
         }
         delete positions[keytitle];
      }
   });
   var curEle = replaceval = ''; var curOrder = replaceId = 1;
   var oldcombineorder = 0;
   $( "ul#sTree3 li" ).each(function(i, lival){
      var oldOrder = $( "ul#sTree3 li" ).eq(i).find('.seq_order_combine_up').val();
      var unitId = $( "ul#sTree3 li" ).eq(i).attr('data-id');
      oldcombineorder = i+1;
      $(lival).find('.seq_order_combine_up').val(oldcombineorder);
      livalId = $( "ul#sTree3 li" ).eq(i).attr('id').replace('_'+oldOrder+'_','_'+oldcombineorder+'_');
      $(lival).attr('id',livalId);
      $(lival).attr('data-orderval',i+1);
      $(lival).attr('data-inner-cnt',$( "ul#sTree3 li" ).eq(i).find('div.navimgdet2').length);
      if($( "ul#sTree3 li" ).eq(i).find('div.navimgdet2').length >1)
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
      $( "ul#sTree3 li" ).eq(i).html(inlineString);
      $( "ul#sTree3 li" ).eq(i).find('div.navimage').removeAttr("onclick");
      if($.isNumeric(unitId))
         $( "ul#sTree3 li" ).eq(i).find('div.navimage').attr("onclick", "getTemplateOfExerciseRecordAction('" + oldcombineorder + '_' + unitId + "',this,'" + oldcombineorder + "');");
   });
   $("ul#sTree3 li div.navimgdet2").each(function(i, item) {
      $(item).find('.seq_order_up').val(i+1);
   });
   $( "ul#sTree3 li" ).each(function(i, lival){
      var newadddiv = '';
      $( "ul#sTree3 li" ).eq(i).find('.exercisesetdiv div.navimgdet2').each(function(x,divimage){
         newadddiv = newadddiv.concat($(divimage).attr('data-id')+',');
      });
      litagId = $( "ul#sTree3 li" ).eq(i).attr('id');
      litagIdHiddenval = $('#'+litagId+'_hidden').val();
      $('#'+litagId+'_hidden').val(newadddiv.slice(0, -1));
   });
}

function createFolderModel(fid, method, foldid) {
   $('#FolderModal').html();
   $.ajax({
      url: siteUrl_frontend + "search/getmodelTemplate",
      data: {
         fromAdmin: true,
         action: 'workoutFolder',
         method: method,
         id: fid,
         foldid: foldid
      },
      success: function(content) {
         $('#FolderModal').html(content);
         $('#FolderModal').modal();
      }
   });
}

function getworkoutpreview(wkoutId) {
	var type = '';
	if ($("#wkt_type").val() == 'sample') {
		type = 'previewsample';
	} else if ($("#wkt_type").val() == 'shared') {
		type = 'previewshared';
	} else if ($("#wkt_type").val() == 'wkout') {
		type = 'wkout';
	}
   $('#myModal').html();
   $.ajax({
      url: siteUrl_frontend + "search/getmodelTemplate",
      data: {
         fromAdmin: true,
         action: 'previewworkout',
         method: 'preview',
         id: wkoutId,
         foldid: '0',
		   type: type
      },
      success: function(content) {
         $('#myModal').html(content);
         $('#myModal').modal();
      }
   });
}
function getExerciseSetpreview(exerciseSetId, wkoutId, selector) {
	var type = '';
	if ($("#wkt_type").val() == 'sample') {
		type = 'sample';
	} else if ($("#wkt_type").val() == 'shared') {
		type = 'share';
	}
	$('#FolderModal').html();
	$.ajax({
		url: siteUrl_frontend + "search/getmodelTemplate",
		data: {
			fromAdmin: true,
         action: 'previewExercise',
			method: 'preview',
			id: exerciseSetId,
			foldid: wkoutId,
			type: type
		},
		success: function(content) {
			$('#FolderModal').html(content);
			$('#FolderModal').modal();
		}
    });
}

function getExercisepreviewOfDay(exerciseId, wkoutId) {
	$('#FolderModal').html();
   $.ajax({
      url: siteUrl_frontend + "search/getmodelTemplate",
      data: {
         fromAdmin: true,
         action: 'previewExerciseOfDay',
         method: 'preview',
         id: exerciseId,
         foldid: wkoutId
      },
      success: function(content) {
         $('#FolderModal').html(content);
         $('#FolderModal').modal();
      }
   });
}

function createNewworkout() {
	var type = ($("#wkt_type").val()) ? $("#wkt_type").val() : '';
	$('#myModal').html();
   $.ajax({
      url: siteUrl_frontend + "search/getmodelTemplate",
      data: {
         fromAdmin: true,
         action: 'createNewworkout',
         method: 'addworkout',
         id: 0,
         foldid: 0,
		   type: type
      },
      success: function(content) {
         $('#myModal').html(content);
         $('#myModal').modal();
      }
   });
}

function selectcolor(selector) {
   selectedclr = $(selector).attr('class').split(' ').pop();
   $('.colorcircle').removeClass('activecircle');
   selectedid = $(selector, '.choosenclr').text();
   $(selector).addClass('activecircle');
   selectedclrnew = $($('#wrkoutcolortext')).attr('class').split(' ').pop();
   if(selectedclr != 'activecircle')
		$('#wrkoutcolortext').removeClass(selectedclrnew).addClass(selectedclr);
   $('#wrkoutcolor').val(selectedid);
   $('.navbar-collapse-colors').removeClass('in');
}

function editWorkoutRecord(elem, method, setId) {
   if (setId == undefined)
      setId = '';
   $('div.createworkout div.border').removeClass('new-item');
   var addOptions = '';
   if (method.indexOf('#') >= 0) {
      methodArr = method.split("#");
      addOptions = methodArr[1];
      method = method.replace('#' + addOptions, '');
   }
   if (method == '')
      method = 'edit';
   $('#FolderModal').html('');
   var methodpreview = false;
   if ($('.editmode').is(":visible") == true && method == "preview" && $('#editxr').is(":visible") == true) {
      method = 'edit';
   } else if (method == "preview") {
      method = 'preview';
      methodpreview = true;
   }
   if (method == 'action-edit') {
      method = 'edit';
      var elementsToRemove = [];
      for (var i = 0; i < $('div.modal-backdrop').length; i++) {
         if ($('div.modal-backdrop')) {
            elementsToRemove.push($('div.modal-backdrop')[i]);
         }
      }
      for (var i = 1; i < elementsToRemove.length; i++) {
         elementsToRemove[i].parentNode.removeChild(elementsToRemove[i]);
      }
   }
   var goalOrder = '';
   var wkoutId = '0';
   var datavaljson = '';
   if ($('li#itemSet_' + wkoutId + '_' + elem).length && $('div#itemset_' + elem).length) {
      var goal_id = elem;
      var img_url = '';
      if ($('div#itemset_' + elem + " .activelink").attr("disabled"))
         return false;
      var goalOrder = $('div#itemset_' + elem + ' input#goal_order_combine_' + elem).val();
      var dataForm = $('div#itemset_' + elem + ' .navbarmenu input').map(function() {
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
   } else if ($('div#itemset_' + elem).length) {
      var goal_id = elem;
      var img_url = '';
      if ($('div#itemset_' + elem + " .activelink").attr("disabled"))
         return false;
      var goalOrder = $('div#itemset_' + elem + ' input#goal_order_' + elem).val();
      var dataForm = $('div#itemset_' + elem + ' .navbarmenu input').map(function() {
         return {
            name: $(this).attr('name'),
            value: $(this).attr('value'),
            keyval: $(this).attr('data-keyval')
         }
      }).get();
      if ($('div#itemset_' + elem + ' .navimage img').length && $('div#itemset_' + elem + ' .navimage img').attr('src') != '') {
         var img_url = $('div#itemset_' + elem + ' .navimage img').attr('src').replace(siteUrl_frontend, '');
         img_url = img_url.replace('../../../', '');
      }
   }
   var datavaljson = getInputDetailsByForm(dataForm, img_url, goal_id, 1);
   $.ajax({
      url: siteUrl_frontend + "search/getmodelTemplate",
      data: {
         action: 'createExercise',
         method: method,
         id: '0',
         foldid: elem,
         goalOrder: goalOrder,
         xrsetid: setId,
         dataval: datavaljson,
         modelType: 'FolderModal',
         addOptions: addOptions
      },
      success: function(content) {
         $('#FolderModal').html(content);
         if ($('.xrsets-tab .nav-tabs.setlist-tab > li').length <= 1) {
            $('.xrsets-tab').hide();
         }
         $('#FolderModal').modal();
      }
   });
}
function editWorkoutRecordOld(elem,method){
	$('div.createworkout div.border').removeClass('new-item');
	var addOptions = '';
	if(method.indexOf('#') >= 0){
		methodArr 	= method.split("#");
		addOptions 	= methodArr[1];
		method = method.replace('#'+addOptions,'');
	}
	$('#FolderModal').html('');
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
				var img_url	= $('div#itemsetnew_'+elem+' .navimage img').attr('src').replace(siteUrl_frontend,'');
				img_url		= img_url.replace('../../../','');
			}
		}
		elem	  = '';
	}
	var datavaljson = getInputDetailsByForm(dataForm, img_url, goal_id , 1);
	$.ajax({
		url : siteUrl_frontend+"search/getmodelTemplate",
		data : {
			action : 'createExercise',
			method :  'create',
			id : '0',
			foldid : '0',
			goalOrder : goalOrder,
			dataval : datavaljson,
			modelType : 'FolderModal',
			addOptions:addOptions,
		},
		success : function(content){
			$('#FolderModal').html(content);
			$('#FolderModal').modal();
		}
	});
}

function createworkout(elem) {
   $('#s_row_count').val(elem.id);
   $('#FolderModal').html();
   $.ajax({
      url: siteUrl_frontend + "search/getmodelTemplate",
      data: {
         fromAdmin: true,
         action: 'createExercise',
         method: 'create',
         id: 0,
         foldid: '0'
      },
      success: function(content) {
         $('#FolderModal').html(content);
         $('#FolderModal').modal();
      }
   });
}
function editExercise(selector, order) {
	if (!validateXrSets())
		return false;
	var setids = $('#' + selector + '_hidden').val().split(',');
	$('div.createworkout div.border').removeClass('new-item');
	$('#' + selector + ' .errormsg').empty();
	var oldUnitId = $('#createExercise input[name="goal_id_hidden"]').val();
	var newUnitIdbefore = oldUnitId.replace(order + '_', '');
	var newUnitId = oldUnitId.replace(order + '_', '');
	var xrtitleVal = '';
	var modaldata = $('#createExercise input').map(function () {
		return {
			name: $(this).attr('name'),
			value: $(this).attr('value'),
			keyval: $(this).attr('data-keyval'),
			goalid: $(this).attr('data-goalid')
		}
	}).get();
	var allowCls = false;
	$(modaldata).each(function (i, field) {
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
				if ($('#createExercise span#exerciselibimg img').length && $('#createExercise span#exerciselibimg img').attr('src') != '')
					$('#' + selector + ' .navimage').html('<img width="75px;" src="' + $('#createExercise span#exerciselibimg img').attr('src') + '"  class="img-responsive pointers">');
				else
					$('#' + selector + ' .navimage').html('<i class="fa fa-file-image-o pointers" style="font-size:50px;">');
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
				.html(function (i, htmltext) {
					var regex = new RegExp(oldsetid, 'g');
					return htmltext.replace(regex, newsetid);
				})
				.insertAfter('#' + selector + ' .navimagedetails .navimgdet2:last');
			$('<hr>').insertBefore('#' + selector + ' .navimagedetails #set_id_' + field.keyval);
			$('#' + selector + '_hidden').val(setids + ',' + field.keyval);
			setids.push(field.keyval);
		}
		
		setTimeout(function () {
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
					if (setids[i] == field.keyval)
						setids.splice(i, 1);
				}
				$('#' + selector + '_hidden').val(setids.join(','));
			}
			/*update hidden value*/
			newvariable = field.name.replace("_hidden", "");
			newkeyval = field.keyval.replace(order + '_', '');
			$('div#set_id_' + field.keyval + ' #' + newvariable + '_' + newkeyval).val(field.value);
			var updatedText = '';
			if ($('#' + selector + ' div#set_id_' + field.keyval + ' a.' + newvariable + '_div').length && $('#createExercise div#set_' + field.keyval + ' span.' + newvariable).length) {
				updatedText = $('#createExercise div#set_' + field.keyval + ' span.' + newvariable).html().trim();
				if (updatedText != '<span class="inactivedatacol">Click to modify</span>') {
					if (newvariable == 'exercise_rest' && updatedText.trim() != '') {
						$('#' + selector + ' div#set_id_' + field.keyval + ' a.' + newvariable + '_div').html(updatedText + ' rest');
					} else
						$('#' + selector + ' div#set_id_' + field.keyval + ' a.' + newvariable + '_div').html(updatedText);
				} else {
					$('#' + selector + ' div#set_id_' + field.keyval + ' a.' + newvariable + '_div').html('');
				}
			}
			$('#' + selector + ' div.exercisesetdiv hr').removeClass('hide');
			$('#' + selector + ' div#set_id_' + field.keyval + '').removeClass('hide');
		}, 120);
	});
	setTimeout(function () {
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
		}
	}, 150);
	if (allowCls) {
		$('#' + selector + ' div.border').addClass('new-item');
		$('#FolderModal').modal('hidecustom');
	}
	setTimeout(function () {
		if (newUnitIdbefore != newUnitId && ($.isNumeric(newUnitIdbefore) || $.isNumeric(newUnitId))) {
			var oldUnit_id = oldUnitId.replace(order + '_', '');
			var wkout_id = $('#wkout_id').val();
			var selectornew = 'li#' + selector;
			var inlineString = $(selectornew).html();
			if (newUnitId == 0) {
				newUnitId = '0_' + $('#wkout_id').val();
			}
			var re1 = new RegExp('_' + oldUnitId, 'g');
			inlineString = inlineString.replace(re1, '_' + order + '_' + newUnitId);
			var re2 = new RegExp(oldUnitId + ']', 'g');
			inlineString = inlineString.replace(re2, order + '_' + newUnitId + ']');
			var re3 = new RegExp('"' + oldUnitId + '"', 'g');
			inlineString = inlineString.replace(re3, '"' + order + '_' + newUnitId + '"');
			var re5 = new RegExp("'" + oldUnitId + "'", 'g');
			inlineString = inlineString.replace(re5, "'" + order + '_' + newUnitId + "'");
			var re4 = new RegExp(oldUnit_id + ',this', 'g');
			inlineString = inlineString.replace(re4, newUnitId + ',this');
			$(selectornew).html(inlineString);
			if ($(selectornew).length) {
				var xrtitle = 'input.exercise_title_xr_hidden';
				var xrtag = 'input.exercise_unit_xr_hidden';
			}

			$(selectornew + ' .navimgdet1').html('<b>' + xrtitleVal + '</b>');
			$(selectornew + ' ' + xrtag).val(order + '_' + newUnitId);
			$(selectornew + ' .navimage').removeAttr('onclick');
			$(selectornew + ' ' + xrtitle).val(xrtitleVal);
			$(selectornew).attr('data-title', base64_encode(xrtitleVal));
			if ($.isNumeric(newUnitId))
				$(selectornew + ' .navimage').attr("onclick", "getTemplateOfExerciseRecordAction('" + order + '_' + newUnitId + "',this,'" + order + "');");
			$(selectornew + ' div.navimgdet2').each(function(){
				var setid = $(this).attr('data-id').replace(order+'_','');
				$(this).find('.listoptionpoppopup').attr("onclick", "getTemplateOfExerciseSetAction('" + order + '_'+ newUnitId + "','"+ setid + "','link');");
			});
			$(selectornew).attr("data-id", newUnitId);
			$(selectornew).attr("id", selector.replace(oldUnitId, order + '_' + newUnitId));
		}
		updateLiItems();
	}, 170);
	return false;
}
function addnewExercise(elem) {
	if (!validateXrSets())
		return false;
	$('.errormsg').hide();
	var setids = $('input#itemSet_' + elem + '_hidden').val().split(',');
	var order = $('li#itemSet_' + elem + ' input.seq_order_combine_up').val();
	var setDataid = $('li#itemSet_' + elem).attr('data-id');
	var oldUnitId = $('#createExercise input[name="goal_id_hidden"]').val();
	var newUnitIdbefore = oldUnitId.replace(order + '_', '');
	var newUnitId = oldUnitId.replace(order + '_', '');
	var dataUnitid = order + '_' + setDataid;
	var xrtitleVal = '';
	var allowCls = false;
	var modaldata = $('#createExercise input').map(function () {
		return {
			name: $(this).attr('name'),
			value: $(this).attr('value'),
			keyval: $(this).attr('data-keyval'),
			goalid: $(this).attr('data-goalid')
		}
	}).get();
	var exercise_unit_xr_hidden = order + '_' + $('li#itemSet_' + elem).attr('data-id');
	$(modaldata).each(function (i, field) {
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
				if ($('#createExercise span#exerciselibimg img').length && $('#createExercise span#exerciselibimg img').attr('src') != '')
					$('li#itemSet_' + elem + ' .navimage').html('<img width="75px;" src="' + $('#createExercise span#exerciselibimg img').attr('src') + '"  class="img-responsive pointers">');
				else
					$('li#itemSet_' + elem + ' .navimage').html('<i class="fa fa-file-image-o pointers" style="font-size:50px;">');
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
				.html(function (i, htmltext) {
					var regex = new RegExp(oldsetid, 'g');
					return htmltext.replace(regex, newsetid);
				})
				.find('i.listoptionpoppopup').attr("onclick", "getTemplateOfExerciseSetAction('" + dataUnitid + "','" + newsetid + "','link');").removeClass('hide').end()
				.insertAfter('li#itemSet_' + elem + ' .navimagedetails .navimgdet2:last');
			$('<hr>').insertBefore('li#itemSet_' + elem + ' .navimagedetails #set_id_' + field.keyval);
			$('input#itemSet_' + elem + '_hidden').val(setids + ',' + field.keyval);
			setids.push(field.keyval);
		}
	   
		setTimeout(function () {
			 //adding deleted flag
			if (field.name.indexOf('removed_set') !== -1) {
				if ($('div#set_id_' + field.keyval).next('hr').length > 0)
					$('div#set_id_' + field.keyval).next('hr').andSelf().remove();
				else
					$('div#set_id_' + field.keyval).prev('hr').andSelf().remove();
				for (var i = setids.length - 1; i >= 0; i--) {
					if (setids[i] == field.keyval)
						setids.splice(i, 1);
				}
				$('input#itemSet_' + elem + '_hidden').val(setids.join(','));
			}
			/*update hidden value*/
			newvariable = field.name.replace("_hidden", "");
			newkeyval = field.keyval.replace(order + '_', '');
			if (field.name != 'exercise_unit_hidden')
				$('div#set_id_' + field.keyval + ' #' + newvariable + '_' + newkeyval).val(field.value);
			var updatedText = '';
			if ($('li#itemSet_' + elem + ' div#set_id_' + field.keyval + ' a.' + newvariable + '_div').length && $('#createExercise div#set_' + field.keyval + ' span.' + newvariable).length) {
				updatedText = $('#createExercise div#set_' + field.keyval + ' span.' + newvariable).html().trim();
				if (updatedText != '<span class="inactivedatacol">Click to modify</span>') {
					if (newvariable == 'exercise_rest' && updatedText.trim() != '')
						$('#itemSet_' + elem + ' div#set_id_' + field.keyval + ' a.' + newvariable + '_div').html(updatedText + ' rest');
					else
						$('li#itemSet_' + elem + ' div#set_id_' + field.keyval + ' a.' + newvariable + '_div').html(updatedText);
				} else {
					$('li#itemSet_' + elem + ' div#set_id_' + field.keyval + ' a.' + newvariable + '_div').html('');
				}
			}
		}, 120);
	});
	setTimeout(function () {
		for (var i = 0; i < setids.length; i++) {
			var flag = false;
			var setId = setids[i];
			$('#itemSet_' + elem + ' div#set_id_' + setId + ' .listoptionpoppopup').removeClass('hide');
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
		$('#FolderModal').modal('hidecustom');
	}
	$('#itemSet_' + elem + ' div.exercisesetdiv div.navimgdet2').removeClass('hide');
	$('#itemSet_' + elem + ' div.exercisesetdiv hr').removeClass('hide');
	setTimeout(function () {
		var oldUnit_id = oldUnitId.replace(order + '_', '');
		var wkout_id = 0;
		var selectornew = 'li#itemSet_' + elem;
		var inlineString = $(selectornew).html();
		var inlineXrString = $(selectornew + ' div.exercisesetdiv').html();
		var inlineHiddenval = $(selectornew + ' input#itemSet_' + elem + '_hidden').val();
		if (newUnitId == 0) {
			newUnitId = '0_0';
		}
		var re1 = new RegExp('_' + oldUnitId, 'g');
		inlineString = inlineString.replace(re1, '_' + order + '_' + newUnitId);
		var re2 = new RegExp(oldUnitId + ']', 'g');
		inlineString = inlineString.replace(re2, order + '_' + newUnitId + ']');
		var re3 = new RegExp('"' + oldUnitId + '"', 'g');
		inlineString = inlineString.replace(re3, '"' + order + '_' + newUnitId + '"');
		var re5 = new RegExp("'" + oldUnitId + "'", 'g');
		inlineString = inlineString.replace(re5, "'" + order + '_' + newUnitId + "'");
		var re4 = new RegExp(oldUnit_id + ',this', 'g');
		inlineString = inlineString.replace(re4, newUnitId + ',this');
		$(selectornew).html(inlineString);
		if ($(selectornew).length) {
			var xrtitle = 'input.exercise_title_xr_hidden';
			var xrtag = 'input.exercise_unit_xr_hidden';
		}
		$(selectornew + ' .navimage').removeAttr('onclick');
		$(selectornew).attr("data-id", newUnitId);
		var replacedId = 'itemSet_' + elem.replace(oldUnitId, order + '_' + newUnitId);
		$(selectornew).attr("id", replacedId);
		$('li#' + replacedId + ' div.exercisesetdiv').html(inlineXrString);
		$('li#' + replacedId + ' input#' + replacedId + '_hidden').val(inlineHiddenval);
		$('li#' + replacedId + ' .navimgdet1').html('<b>' + xrtitleVal + '</b>');
		$('li#' + replacedId).attr('data-title', base64_encode(xrtitleVal));
		$('li#' + replacedId + ' ' + xrtag).val(order + '_' + newUnitId);
		$('li#' + replacedId + ' ' + xrtitle).val(xrtitleVal);
		if ($.isNumeric(newUnitId)) {
			$('li#' + replacedId + ' .navimage').attr("onclick", "getTemplateOfExerciseRecordAction('" + order + '_' + newUnitId + "',this,'" + order + "');");
		}
		$('li#'+replacedId + ' div.navimgdet2').each(function(){
			var setid = $(this).attr('data-id').replace(order+'_','');
			$(this).find('.listoptionpoppopup').attr("onclick", "getTemplateOfExerciseSetAction('" + order + '_'+ newUnitId + "','"+ setid + "','link');");
		});
		updateLiItems();
	}, 170);
	setDynamicHeight();
	return false;
}
function addnewExerciseOld(elem) {
   $('.errormsg').hide();
	$('div.createworkout div.border').removeClass('new-item');
   var modaldata = $('#createExercise').serializeArray();
   var allowCls = false;
   $(modaldata).each(function(i, field) {
      if (field.name == 'exercise_title_hidden') {
         if (field.value != "") {
            $('#itemsetnew_' + elem).find('.navimgdet1').html('<b>' + field.value + '</b>');
            allowCls = true;
         } else {
            $('.errormsg').text('Exercise Title not empty').removeClass('hide').show();
            return false;
         }
      }
      if (field.name == 'exercise_unit_hidden') {
         if (field.value != "") {
            if ($('#createExercise span#exerciselibimg img').length && $('#createExercise span#exerciselibimg img').attr('src') != '') $('#itemsetnew_' + elem + ' .navimage').html('<img width="50px;" src="' + $('#createExercise span#exerciselibimg img').attr('src') + '"  class="img-responsive pointers">');
            else $('#itemsetnew_' + elem + ' .navimage').html('<i class="fa fa-file-image-o pointers" style="font-size:50px;">');
            $('#itemsetnew_' + elem + ' .navimage').attr("onclick", "getTemplateOfExerciseRecordAction('" + field.value + "',this);");
         } else {
            $('#itemsetnew_' + elem + ' .navimage').html('<i class="fa fa-pencil-square" style="font-size:50px;">');
         }
      }
      newvariable = field.name.replace("_hidden", "");
      $('#' + newvariable + '_new_' + elem).val(field.value);
      var updatedText = '';
      if ($('#itemsetnew_' + elem + ' a.' + newvariable + '_div').length && $('#createExercise span.' + newvariable).length) {
         updatedText = $('#createExercise span.' + newvariable).html().trim();
		 if(updatedText !='<span class="inactivedatacol">Click to modify</span>'){
			 if (newvariable == 'exercise_rest' && updatedText.trim() != '') $('#itemsetnew_' + elem + ' a.' + newvariable + '_div').html(updatedText + ' rest');
			 else $('#itemsetnew_' + elem + ' a.' + newvariable + '_div').html(updatedText);
	     }else{
			$('#itemsetnew_' + elem + ' a.' + newvariable + '_div').html('');
		 }
      }
   });
   $('#itemsetnew_' + elem + ' .listoptionpop').attr("onclick", "getTemplateOfExerciseSetActionBycreate('" + elem + "','myModal');").removeClass('hide');
   var flag = false;
   if ($('#itemsetnew_' + elem + ' a.exercise_time_div').html().trim() != '') {
      flag = true;
   }
   if ($('#itemsetnew_' + elem + ' a.exercise_distance_div').html().trim() != '') {
      var inHtml = $('#itemsetnew_' + elem + ' a.exercise_distance_div').html();
      if (flag && inHtml.trim() != '') $('#itemsetnew_' + elem + ' a.exercise_distance_div').html(' /// ' + inHtml);
      else $('#itemsetnew_' + elem + ' a.exercise_distance_div').html(inHtml);
      flag = true;
   }
   if ($('#itemsetnew_' + elem + ' a.exercise_repetitions_div').html().trim() != '') {
      var inHtml = $('#itemsetnew_' + elem + ' a.exercise_repetitions_div').html();
      if (flag && inHtml.trim() != '') $('#itemsetnew_' + elem + ' a.exercise_repetitions_div').html(' /// ' + inHtml);
      else $('#itemsetnew_' + elem + ' a.exercise_repetitions_div').html(inHtml);
      flag = true;
   }
   if ($('#itemsetnew_' + elem + ' a.exercise_resistance_div').html().trim() != '') {
      var inHtml = $('#itemsetnew_' + elem + ' a.exercise_resistance_div').html();
      if (flag && inHtml.trim() != '') $('#itemsetnew_' + elem + ' a.exercise_resistance_div').html(' /// ' + inHtml);
      else $('#itemsetnew_' + elem + ' a.exercise_resistance_div').html(inHtml);
   }
   if (allowCls) {
      $('#goal_order_' + elem).val(0);
      if ($('ul#sTree3 #itemsetnew_' + elem).parent('li').hasClass('hide')) $('ul#sTree3 #itemsetnew_' + elem).parent('li').removeClass('hide');
      if ($('#scrollablediv-len ul#sTree3').hasClass('hide')) $('#scrollablediv-len ul#sTree3').removeClass('hide');
      $('i.listoptionpoppopup ').removeClass('hide');
		$('#itemsetnew_' + elem + ' div.border').addClass('new-item');
		litagscrollcnt = $('#itemsetnew_' + elem).parent('li').index();
		if(litagscrollcnt == '0')
			$('div#scrollablediv-len ul').scrollTop($('div#scrollablediv-len ul li:first').position().top);
		else if($('#scrollablediv-len ul li').length == litagscrollcnt)
			$('div#scrollablediv-len ul').scrollTop($('div#scrollablediv-len ul li:last').position().top);
		else
			$('div#scrollablediv-len ul').scrollTop($('div#scrollablediv-len ul li:nth-child('+litagscrollcnt+')').position().top - $('div#scrollablediv-len ul li:first').position().top);
		$('#FolderModal').modal('hidecustom');
   }
	setDynamicHeight();
   return true;
};

function createNewExerciseSet(selector, move, xroption) {
	if (xroption === undefined) {
		$('div.createworkout div.border').removeClass('new-item');
		var wkoutid = 0;
		if ($('#scrollablediv-len li'))
			var last = $('#scrollablediv-len li').length;
		else
			var last = 0;
		if (move == 'last') {
			var goalorder = last + 1;
		} else if (move == 'down' || move == 'up') {
			var goalorder = 1;
		}
		if ($('div.createworkout').find('.navimgdet1').text() != 'Click_to_Edit') {
			var valuexr = parseInt($('#newlyAddedXr').val()) + 1;
			var count = parseInt(last) + 1;
			$('#s_row_count').val(count);
			$('#s_row_count_flag').val(parseInt($('#s_row_count_flag').val()) + 1);
			$('#newlyAddedXr').val(valuexr);
			var li_element = '<li data-orderval="' + count + '" data-role="none" class="bgC4 hide" data-inner-cnt="1" data-title="" data-id="new_' + valuexr + '" data-module="item_set" id="itemSet_' + wkoutid + '_' + count + '_new_' + valuexr + '"><div id="itemset_' + count + '_new_' + valuexr + '" class="row createworkout"><input type="hidden" class="seq_order_combine_up" id="goal_order_combine_' + count + '_new_' + valuexr + '" name="goal_order_combine[' + count + '_new_' + valuexr + ']" value="' + count + '"><div class="mobpadding"><div class="border full new-item"><div class="checkboxchoosen popupchoosen col-xs-1 row-no-padding" style="display:none;"><div class="checkboxcolor" style="font-size:20px;"><label><input id="checkbox_col_new_' + valuexr + '" class="checkhiddenpopup" data-role="none" data-ajax="false" value="' + count + '_new_' + valuexr + '" type="checkbox" onclick="enablePopupButtons();" name="exercisesets[]"/><span class="cr checkbox-circle"><i class="cr-icon fa fa-check"></i></span></label></div></div><div class="col-xs-8 navdescrip row-no-padding"><div class="col-xs-4 activelinkpopup navimage row-no-padding"><i class="fa fa-pencil-square" style="font-size:50px;"></i></div><div class="col-xs-8 pointers activelinkpopup datacol row-no-padding"><div class="activelinkpopup navimagedetails"><div class="navimgdet1" onclick="editWorkoutRecord(' + "'" + count + "_new_" + valuexr + "','create'" + ');"><b>Click_to_Edit</b></div><div class="exercisesetdiv"><div data-id="' + count + '_new_' + valuexr + '" class="navimgdet2" id="set_id_' + count + '_new_' + valuexr + '"><input type="hidden" class="seq_order_up" id="goal_order_new_' + valuexr + '" name="goal_order[new_' + valuexr + ']" value="' + count + '"/><input type="hidden" id="goal_remove_new_' + valuexr + '" name="goal_remove[new_' + valuexr + ']" value="0"/><div class="xrsets col-xs-9"><a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail activedatacol exercise_time_div"></a><a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail activedatacol exercise_distance_div"></a><a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail activedatacol exercise_repetitions_div"></a><a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail activedatacol exercise_resistance_div"></a><a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail activedatacol exercise_rate_div"></a><a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail activedatacol exercise_angle_div"></a><a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail activedatacol exercise_innerdrive_div"></a><a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail activedatacol exercise_rest_div"></a></div><div class="col-xs-1 navbarmenu row-no-padding"><a data-ajax="false" class="pointers editchoosenIconTwoPopup hide" href="javascript:void(0);"><i class="fa fa-bars panel-draggable" style="font-size:25px;"></i></a><i class="fa fa-ellipsis-h iconsize listoptionpoppopup hide" onclick="getTemplateOfExerciseSetAction(\'' + count + '_new_' + valuexr + '\',\'new_' + valuexr + '\',\'link\');"></i><input type="hidden" data-keyval="' + count + '_new_' + valuexr + '" id="exercise_title_new_' + valuexr + '" class="exercise_title_xr_hidden" name="exercise_title[new_' + valuexr + ']" value=""/><input type="hidden" class="exercise_unit_xr_hidden" data-keyval="' + count + '_new_' + valuexr + '" id="exercise_unit_new_' + valuexr + '" name="exercise_unit[new_' + valuexr + ']" value="0"/><input type="hidden" data-keyval="' + count + '_new_' + valuexr + '"  id="exercise_resistance_new_' + valuexr + '" name="exercise_resistance[new_' + valuexr + ']" value=""/><input type="hidden" data-keyval="' + count + '_new_' + valuexr + '" id="exercise_unit_resistance_new_' + valuexr + '" name="exercise_unit_resistance[new_' + valuexr + ']" value=""/><input type="hidden" data-keyval="' + count + '_new_' + valuexr + '" id="exercise_repetitions_new_' + valuexr + '" name="exercise_repetitions[new_' + valuexr + ']" value=""/><input type="hidden" data-keyval="' + count + '_new_' + valuexr + '" id="exercise_time_new_' + valuexr + '" name="exercise_time[new_' + valuexr + ']" value=""/><input type="hidden" data-keyval="' + count + '_new_' + valuexr + '" id="exercise_distance_new_' + valuexr + '" name="exercise_distance[new_' + valuexr + ']" value=""/><input type="hidden" data-keyval="' + count + '_new_' + valuexr + '" id="exercise_unit_distance_new_' + valuexr + '" name="exercise_unit_distance[new_' + valuexr + ']" value=""/><input type="hidden" data-keyval="' + count + '_new_' + valuexr + '" id="exercise_rate_new_' + valuexr + '" name="exercise_rate[new_' + valuexr + ']" value=""/><input type="hidden" data-keyval="' + count + '_new_' + valuexr + '" id="exercise_unit_rate_new_' + valuexr + '" name="exercise_unit_rate[new_' + valuexr + ']" value=""/><input type="hidden" data-keyval="' + count + '_new_' + valuexr + '" id="exercise_innerdrive_new_' + valuexr + '" name="exercise_innerdrive[new_' + valuexr + ']" value=""/><input type="hidden" data-keyval="' + count + '_new_' + valuexr + '" id="exercise_angle_new_' + valuexr + '" name="exercise_angle[new_' + valuexr + ']" value=""/><input type="hidden" data-keyval="' + count + '_new_' + valuexr + '" id="exercise_unit_angle_new_' + valuexr + '" name="exercise_unit_angle[new_' + valuexr + ']" value=""/><input type="hidden" data-keyval="' + count + '_new_' + valuexr + '" id="exercise_rest_new_' + valuexr + '" name="exercise_rest[new_' + valuexr + ']" value=""/><input type="hidden" data-keyval="' + count + '_new_' + valuexr + '" id="exercise_remark_new_' + valuexr + '" name="exercise_remark[new_' + valuexr + ']" value=""/><input type="hidden" data-keyval="' + count + '_new_' + valuexr + '" id="primary_time_new_' + valuexr + '" class="exercise_priority_hidden" name="primary_time[new_' + valuexr + ']" value=""/><input type="hidden" data-keyval="' + count + '_new_' + valuexr + '" id="primary_dist_new_' + valuexr + '" class="exercise_priority_hidden" name="primary_dist[new_' + valuexr + ']" value=""/><input type="hidden" data-keyval="' + count + '_new_' + valuexr + '" id="primary_reps_new_' + valuexr + '" class="exercise_priority_hidden" name="primary_reps[new_' + valuexr + ']" value=""/><input type="hidden" data-keyval="' + count + '_new_' + valuexr + '" id="primary_resist_new_' + valuexr + '" class="exercise_priority_hidden" name="primary_resist[new_' + valuexr + ']" value=""/><input type="hidden" data-keyval="' + count + '_new_' + valuexr + '" id="primary_rate_new_' + valuexr + '" class="exercise_priority_hidden" name="primary_rate[new_' + valuexr + ']" value=""/><input type="hidden" data-keyval="' + count + '_new_' + valuexr + '" id="primary_angle_new_' + valuexr + '" class="exercise_priority_hidden" name="primary_angle[new_' + valuexr + ']" value=""/><input type="hidden" data-keyval="' + count + '_new_' + valuexr + '" id="primary_rest_new_' + valuexr + '" class="exercise_priority_hidden" name="primary_rest[new_' + valuexr + ']" value=""/><input type="hidden" data-keyval="' + count + '_new_' + valuexr + '" id="primary_int_new_' + valuexr + '" class="exercise_priority_hidden" name="primary_int[new_' + valuexr + ']" value=""/></div></div></div><input id="itemSet_' + wkoutid + '_' + count + '_new_' + valuexr + '_hidden" value="' + count + '_new_' + valuexr + '" type="hidden"></div></div></div></div></div></li>';
			if (move == 'down')
				$('#scrollablediv-len ul li:eq(' + goalorder + ')').after(li_element);
			else if (move == 'up')
				$('#scrollablediv-len ul li:eq(' + goalorder + ')').before(li_element);
			else
				$('#scrollablediv-len ul').append(li_element);
			if ($('#scrollablediv-len li').length > 3 && !$('#scrollablediv-len').hasClass('scrollablediv')) {
				$('#scrollablediv-len').addClass('scrollablediv');
			}
			if ($('#scrollablediv-len li').length == '1')
				$('.sTreeBase').show();
			$("ul#sTree3 li div.navimgdet2").each(function (i, item) {
				$(item).find('.seq_order_up').val(i + 1);
			});
			editWorkoutRecord(count + "_new_" + valuexr, "create");
		}
	} else {
		var wkoutid = 0;
		var lielement = $('li#itemSet_' + wkoutid + '_' + selector);
		currOrder = $('li#itemSet_' + wkoutid + '_' + selector).attr('data-inner-cnt');
		unitId = $('li#itemSet_' + wkoutid + '_' + selector).attr('data-id');
		goalorder = $('div#itemset_' + selector + ' input#goal_order_combine_' + selector).val();
		xrtitle = $('li#itemSet_' + wkoutid + '_' + selector + ' div#set_id_' + xroption + ' input.exercise_title_xr_hidden').val();
		$('li#itemSet_' + wkoutid + '_' + selector).attr('data-inner-cnt', parseInt(currOrder) + 1);
		var divArray = [];
		var divArrayAll = [];
		$('div#itemset_' + selector + ' div.exercisesetdiv div').each(function (i, item) {
			if ($(item).attr('id'))
				divArray.push($(item).attr('id'));
			divArrayAll.push($(item).attr('id'));
		});
		currPos = divArrayAll.indexOf('set_id_' + xroption);
		last = divArray.length;

		if (move == 'last') var xrorder = last + 1;
		else if (move == 'down' || move == 'up') var xrorder = currPos + 1;

		var valuexr = parseInt($('#newlyAddedXr').val()) + 1;
		var count = goalorder;
		$('#newlyAddedXr').val(valuexr);
		var xr_element = (move == 'down' || move == 'last' ? '<hr class="hide">' : '') + '<div data-id="' + count + '_new_' + valuexr + '" class="navimgdet2 hide" id="set_id_' + count + '_new_' + valuexr + '"><input type="hidden" class="seq_order_up" id="goal_order_new_' + valuexr + '" name="goal_order[new_' + valuexr + ']" value="' + count + '"/><input type="hidden" id="goal_remove_new_' + valuexr + '" name="goal_remove[new_' + valuexr + ']" value="0"/><div class="xrsets col-xs-9"><a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail activedatacol exercise_time_div"></a><a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail activedatacol exercise_distance_div"></a><a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail activedatacol exercise_repetitions_div"></a><a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail activedatacol exercise_resistance_div"></a><a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail activedatacol exercise_rate_div"></a><a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail activedatacol exercise_angle_div"></a><a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail activedatacol exercise_innerdrive_div"></a><a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail activedatacol exercise_rest_div"></a></div><div class="col-xs-1 navbarmenu row-no-padding"><a data-ajax="false" class="pointers editchoosenIconTwoPopup hide" href="javascript:void(0);" style="cursor: move; display: inline;"><i class="fa fa-bars panel-draggable" style="font-size:25px;"></i></a><i class="fa fa-ellipsis-h iconsize listoptionpoppopup"></i><input type="hidden" data-keyval="' + count + '_new_' + valuexr + '" id="exercise_title_new_' + valuexr + '" class="exercise_title_xr_hidden" name="exercise_title[new_' + valuexr + ']" value="' + xrtitle + '"/><input type="hidden" data-keyval="' + count + '_new_' + valuexr + '" id="exercise_unit_new_' + valuexr + '" name="exercise_unit[new_' + valuexr + ']" class="exercise_unit_xr_hidden" value="' + count + '_' + unitId + '"/><input type="hidden" data-keyval="' + count + '_new_' + valuexr + '"  id="exercise_resistance_new_' + valuexr + '" name="exercise_resistance[new_' + valuexr + ']" value=""/><input type="hidden" data-keyval="' + count + '_new_' + valuexr + '" id="exercise_unit_resistance_new_' + valuexr + '" name="exercise_unit_resistance[new_' + valuexr + ']" value=""/><input type="hidden" data-keyval="' + count + '_new_' + valuexr + '" id="exercise_repetitions_new_' + valuexr + '" name="exercise_repetitions[new_' + valuexr + ']" value=""/><input type="hidden" data-keyval="' + count + '_new_' + valuexr + '" id="exercise_time_new_' + valuexr + '" name="exercise_time[new_' + valuexr + ']" value=""/><input type="hidden" data-keyval="' + count + '_new_' + valuexr + '" id="exercise_distance_new_' + valuexr + '" name="exercise_distance[new_' + valuexr + ']" value=""/><input type="hidden" data-keyval="' + count + '_new_' + valuexr + '" id="exercise_unit_distance_new_' + valuexr + '" name="exercise_unit_distance[new_' + valuexr + ']" value=""/><input type="hidden" data-keyval="' + count + '_new_' + valuexr + '" id="exercise_rate_new_' + valuexr + '" name="exercise_rate[new_' + valuexr + ']" value=""/><input type="hidden" data-keyval="' + count + '_new_' + valuexr + '" id="exercise_unit_rate_new_' + valuexr + '" name="exercise_unit_rate[new_' + valuexr + ']" value=""/><input type="hidden" data-keyval="' + count + '_new_' + valuexr + '" id="exercise_innerdrive_new_' + valuexr + '" name="exercise_innerdrive[new_' + valuexr + ']" value=""/><input type="hidden" data-keyval="' + count + '_new_' + valuexr + '" id="exercise_angle_new_' + valuexr + '" name="exercise_angle[new_' + valuexr + ']" value=""/><input type="hidden" data-keyval="' + count + '_new_' + valuexr + '" id="exercise_unit_angle_new_' + valuexr + '" name="exercise_unit_angle[new_' + valuexr + ']" value=""/><input type="hidden" data-keyval="' + count + '_new_' + valuexr + '" id="exercise_rest_new_' + valuexr + '" name="exercise_rest[new_' + valuexr + ']" value=""/><input type="hidden" data-keyval="' + count + '_new_' + valuexr + '" id="exercise_remark_new_' + valuexr + '" name="exercise_remark[new_' + valuexr + ']" value=""/><input type="hidden" data-keyval="' + count + '_new_' + valuexr + '" id="primary_time_new_' + valuexr + '" class="exercise_priority_hidden" name="primary_time[new_' + valuexr + ']" value=""/><input type="hidden" data-keyval="' + count + '_new_' + valuexr + '" id="primary_dist_new_' + valuexr + '" class="exercise_priority_hidden" name="primary_dist[new_' + valuexr + ']" value=""/><input type="hidden" data-keyval="' + count + '_new_' + valuexr + '" id="primary_reps_new_' + valuexr + '" class="exercise_priority_hidden" name="primary_reps[new_' + valuexr + ']" value=""/><input type="hidden" data-keyval="' + count + '_new_' + valuexr + '" id="primary_resist_new_' + valuexr + '" class="exercise_priority_hidden" name="primary_resist[new_' + valuexr + ']" value=""/><input type="hidden" data-keyval="' + count + '_new_' + valuexr + '" id="primary_rate_new_' + valuexr + '" class="exercise_priority_hidden" name="primary_rate[new_' + valuexr + ']" value=""/><input type="hidden" data-keyval="' + count + '_new_' + valuexr + '" id="primary_angle_new_' + valuexr + '" class="exercise_priority_hidden" name="primary_angle[new_' + valuexr + ']" value=""/><input type="hidden" data-keyval="' + count + '_new_' + valuexr + '" id="primary_rest_new_' + valuexr + '" class="exercise_priority_hidden" name="primary_rest[new_' + valuexr + ']" value=""/><input type="hidden" data-keyval="' + count + '_new_' + valuexr + '" id="primary_int_new_' + valuexr + '" class="exercise_priority_hidden" name="primary_int[new_' + valuexr + ']" value=""/></div></div>' + (move == 'up' ? '<hr class="hide">' : '');
		if (move == 'down')
			$(xr_element).insertAfter('div#itemset_' + selector + ' div.exercisesetdiv div#set_id_' + xroption);
		else if (move == 'up')
			$(xr_element).insertBefore('div#itemset_' + selector + ' div.exercisesetdiv div#set_id_' + xroption);
		else
			$('div#itemset_' + selector + ' div.exercisesetdiv').append(xr_element);
		$('div#itemset_' + selector + ' div.exercisesetdiv div#set_id_' + count + '_new_' + valuexr + '  .listoptionpoppopup').attr("onclick", "getTemplateOfExerciseSetAction('" + selector + "','" + 'new_' + valuexr + "','link');");
		var setidSelector = $('div#itemset_' + selector + ' input#itemSet_' + wkoutid + '_' + selector + '_hidden');
		var setIds = setidSelector.val();
		setidSelector.val(setIds + ',' + count + '_new_' + valuexr);
		$("ul#sTree3 li div.navimgdet2").each(function (i, item) {
			$(item).find('.seq_order_up').val(i + 1);
		});
		editWorkoutRecord(selector, 'create', count + "_new_" + valuexr);
	}
}
function createNewExerciseSetOld(selector, move) {
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
      var li_element = '<li data-id="new_0_' + count + '" data-module="item_set_new" class="bgC4 item_add_wkout_noclick hide" id="itemSetnew_0_0_' + count + '"><div id="itemsetnew_0_' + count + '" class="row createworkout"><input type="hidden" value="' + count + '" class="seq_order_up" name="goal_order_new[]" id="goal_order_new_0_' + count + '"><input type="hidden" value="0" name="goal_remove_new[]" id="goal_remove_new_0_' + count + '"><div class="mobpadding"><div class="border full new-item"><div style="display:none;" class="checkboxchoosen popupchoosen col-xs-2"><div style="font-size:20px;" class="checkboxcolor"><label><input type="checkbox" name="exercisesets[]" onclick="enablePopupButtons();" value="new_0_' + count + '" data-ajax="false" data-role="none" class="checkhiddenpopup"><span class="cr checkbox-circle"><i class="cr-icon fa fa-check"></i></span></label></div></div><div class="col-xs-8 navdescrip"><div class="col-xs-4 activelinkpopup navimage"><i style="font-size:50px;" class="fa fa-pencil-square"></i></div><div class="col-xs-8 pointers activelinkpopup datacol" onclick="editWorkoutRecord(' + "'new_0_" + count + "','create'" + ');"><div class="activelinkpopup navimagedetails"><div class="navimgdet1"><b>Click_to_Edit</b></div><div class="navimgdet2"><a class="datadetail exercise_time_div" href="javascript:void(0);" data-role="none" data-ajax="false"></a><a class="datadetail exercise_distance_div" href="javascript:void(0);" data-role="none" data-ajax="false"></a><a class="datadetail exercise_repetitions_div" href="javascript:void(0);" data-role="none" data-ajax="false"></a><a class="datadetail exercise_resistance_div" href="javascript:void(0);" data-role="none" data-ajax="false"></a></div><div class="navimgdet3"><a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail exercise_rate_div"></a>&nbsp;<a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail exercise_angle_div"></a>&nbsp;<a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail exercise_innerdrive_div"></a>&nbsp;<a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail exercise_rest_div"></a></div><div class="navimgdet4"></div></div></div></div><div class="col-xs-2 navbarmenu"><a data-ajax="false" class="pointers editchoosenIconTwo editchoosenIconTwoPopup hide" href="javascript:void(0);"><i class="fa fa-bars panel-draggable" style="font-size:25px;"></i></a><i class="fa fa-ellipsis-h iconsize listoptionpoppopup" onclick="getTemplateOfExerciseSetAction(' + "'0_" + count + "','link'" + ');"></i><input type="hidden" value="" name="exercise_title_new[]" id="exercise_title_new_0_' + count + '"><input type="hidden" value="0" name="exercise_unit_new[]" id="exercise_unit_new_0_' + count + '"><input type="hidden" value="" name="exercise_resistance_new[]" id="exercise_resistance_new_0_' + count + '"><input type="hidden" value="" name="exercise_unit_resistance_new[]" id="exercise_unit_resistance_new_0_' + count + '"><input type="hidden" value="" name="exercise_repetitions_new[]" id="exercise_repetitions_new_0_' + count + '"><input type="hidden" value="" name="exercise_time_new[]" id="exercise_time_new_0_' + count + '"><input type="hidden" value="" name="exercise_distance_new[]" id="exercise_distance_new_0_' + count + '"><input type="hidden" value="" name="exercise_unit_distance_new[]" id="exercise_unit_distance_new_0_' + count + '"><input type="hidden" value="" name="exercise_rate_new[]" id="exercise_rate_new_0_' + count + '"><input type="hidden" value="" name="exercise_unit_rate_new[]" id="exercise_unit_rate_new_0_' + count + '"><input type="hidden" value="" name="exercise_innerdrive_new[]" id="exercise_innerdrive_new_0_' + count + '"><input type="hidden" value="" name="exercise_angle_new[]" id="exercise_angle_new_0_' + count + '"><input type="hidden" value="" name="exercise_unit_angle_new[]" id="exercise_unit_angle_new_0_' + count + '"><input type="hidden" value="" name="exercise_rest_new[]" id="exercise_rest_new_0_' + count + '"><input type="hidden" value="" name="exercise_remark_new[]" id="exercise_remark_new_0_' + count + '"><input type="hidden" value="" name="primary_time_new[]" class="exercise_priority_hidden" id="primary_time_new_0_' + count + '"><input type="hidden" value="" name="primary_dist_new[]" class="exercise_priority_hidden" id="primary_dist_new_0_' + count + '"><input type="hidden" value="" name="primary_reps_new[]" class="exercise_priority_hidden" id="primary_reps_new_0_' + count + '"><input type="hidden" value="" name="primary_resist_new[]" class="exercise_priority_hidden" id="primary_resist_new_0_' + count + '"><input type="hidden" value="" name="primary_rate_new[]" class="exercise_priority_hidden" id="primary_rate_new_0_' + count + '"><input type="hidden" value="" name="primary_angle_new[]" class="exercise_priority_hidden" id="primary_angle_new_0_' + count + '"><input type="hidden" value="" name="primary_rest_new[]" class="exercise_priority_hidden" id="primary_rest_new_0_' + count + '"><input type="hidden" value="" name="primary_int_new[]" class="exercise_priority_hidden" id="primary_int_new_0_' + count + '"></div></div></div></div></li>';
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

function getpopupexercisesetTemplate(thisobj, wkoutId, exerciseId, type) {
   if (!$(thisobj).hasClass('activateLink')) {
   	if (type != 'title')
			$('#exerciselib-template').remove();
      $('#mypopupModal').html();
      $.ajax({
         url: siteUrl_frontend + "search/getmodelTemplate",
         data: {
            fromAdmin: true,
            action: 'workoutExercise',
            method: type,
            id: wkoutId,
            foldid: exerciseId,
            modelType: 'mypopupModal'
         },
         success: function(content) {
            $('#mypopupModal').html(content);
            $('.checkboxdrag[type="checkbox"]').bootstrapSwitch('size', 'small');
            $('.checkboxdrag[type="checkbox"]').bootstrapSwitch('onText', ' ');
            $('.checkboxdrag[type="checkbox"]').bootstrapSwitch('offText', ' ');
            if (type == 'title')
					$('#mypopupModal').modal('hidecustom');
				else
					$('#mypopupModal').modal();
         }
      });
      if (type == 'title')
			createExerciseFromXrLibrary('');
   }
}

function insertExtraToParentHidden(Model, elem) {
   if (Model.trim() == '')
		Model = 'myModal';
	$('.unitnormal').hide();
	$('.inputnormal').hide();
	if ($('.error').hasClass('hide'))
		$('.error').addClass('hide')
	trueFlag = true;
	formdata = $('#workoutexercise').serializeArray();
	var ashstrick = primaryField = '';
	var oldData = '';
	if ($('.checkboxdrag').is(':checked') && $('.checkboxdrag').attr('id') != 'exerciselib') {
		ashstrick = '<span class="ashstrick">*</span> ';
	}
   $(formdata).each(function(i, field) {
      if (field.value == 'on' || field.value == 'off' || field.name.indexOf("_unit_") == 8) {
         if (field.name != 'exerciselib' && field.name != 'innerdrive' && field.name.indexOf("_unit_") != 8) {
				primaryField = field.name.replace('_hidden', '');
				if (field.value.trim() == 'on') {
					ashstrick = '<span class="ashstrick">*</span> ';
				} else {
					ashstrick = '';
				}
			}
      } else {
         if (field.name != 'exercise_unit' && (field.value.trim() == 0 || field.value.trim() == '' || field.value.trim() == '00:00:00' || field.value.trim() == "00:00")) {
				field.value = ashstrick = oldData = '';
			}
			var checkPresentdiv = false;
			if (field.name.indexOf("exercise_") != -1) {
				var existUnit = field.name.replace('exercise_', 'exercise_unit_');
				checkPresentdiv = $('#workoutexercise select#' + existUnit).length;
			}
			if (!checkPresentdiv) {
				// not present
				if (field.name == 'exercise_title') {
					if (field.value.trim() == '') {
						$('.inputnormal').show();
						$('.error').removeClass('hide');
						trueFlag = false;
						return false;
					} else {
						$('#exercise_title_hidden_text').text(field.value);
						$('#exercise_title_hidden').val(field.value);
					}
					$('div#clone-tab').removeClass('hide');
				} else if (field.name == 'exercise_unit') {
					$('#exerciselibimg').empty();
					if (field.value == 0) {
						$('#exerciselibimg').append('<i class="fa fa-pencil-square datacol" style="font-size:50px;">');
					} else if (field.value > 0 && $('#exercise_unit_img').val() == '') {
						$('#exerciselibimg').append('<i class="fa fa-file-image-o pointers" style="font-size:50px;">');
					} else {
						$('#exerciselibimg').append('<img style="padding-right:10px;" width="75px;" src="' + $('#exercise_unit_img').val() + '"  />');
					}
					$('#exercise_unit_hidden').val(field.value);
            } else if (field.name == 'exercise_repetitions') {
					if (ashstrick != '')
						$('div#set_' + elem + ' span .ashstrick').remove();
					if (field.value != '')
						$('div#set_' + elem + ' .' + field.name).html(ashstrick + '<span>' + field.value + '</span> reps');
					else
						$('div#set_' + elem + ' .' + field.name).html('<span class="inactivedatacol">Click to modify</span>');
				} else if (field.name == 'exercise_innerdrive') {
					if (ashstrick != '')
						$('div#set_' + elem + ' span .ashstrick').remove();
					if (field.value != '') {
						matchesval = document.getElementById("innerdrive").options[field.value].text;
						if (matchesval != 'Select') {
							var regExp = /\(([^)]+)\)/;
							var matches = regExp.exec(matchesval);
							$('div#set_' + elem + ' .' + field.name).html(ashstrick + '<span>' + matches[1] + '</span> Int');
						} else
							$('div#set_' + elem + ' .' + field.name).html('<span class="inactivedatacol">Click to modify</span>');
					} else {
						$('div#set_' + elem + ' .' + field.name).html('<span class="inactivedatacol">Click to modify</span>');
					}
				} else {
               if (ashstrick != '')
						$('div#set_' + elem + ' span .ashstrick').remove();
					if (field.value != '')
						$('div#set_' + elem + ' .' + field.name).html(ashstrick + '<span>' + field.value + '</span>');
					else
						$('div#set_' + elem + ' .' + field.name).html(ashstrick + '<span class="inactivedatacol">Click to modify</span>');
            }
            if ($('div#set_' + elem + ' .' + field.name + '_hidden')) {
					if (field.value != '' && ashstrick != '') {
						if (primaryField != '' && $('div#set_' + elem + ' #primary_' + primaryField)) {
							$('div#set_' + elem + ' .exercise_priority_hidden').val('0');
							$('div#set_' + elem + ' #primary_' + primaryField).val(1);
						}
					} else {
						if ($('div#set_' + elem + ' #primary_' + primaryField))
							$('div#set_' + elem + ' #primary_' + primaryField).val(0);
					}
					if (field.name == 'exercise_innerdrive' && ((!$('div#set_' + elem + ' .exercise_innerdrive_hidden').val() > 0 && field.value != '') || ($('div#set_' + elem + ' .exercise_innerdrive_hidden').val() > 0 && field.value == ''))) {
						var actioncount = $('div#set_' + elem + ' span#showcountXrvariable').html().trim();
						if (field.value != '') {
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
					$('div#set_' + elem + ' .' + field.name + '_hidden').val(field.value);
				}
         } else {
            unit_val = $('#workoutexercise #' + existUnit + ' option:selected').text();
				unit_value = $('#workoutexercise #' + existUnit + ' option:selected').val();
				if (unit_val == 'choose')
					unit_val = '';
				if (field.value == '' && unit_val != '') {
					$('.inputnormal').show();
					$('.error').removeClass('hide');
					trueFlag = false;
					return false;
				} else if (field.value != '' && unit_val == '') {
					$('.unitnormal').show();
					$('.error').removeClass('hide');
					trueFlag = false;
					return false;
				} else {
               if (field.value != '' && ashstrick != '') {
						if (primaryField != '' && $('div#set_' + elem + ' #primary_' + primaryField)) {
							$('div#set_' + elem + ' .exercise_priority_hidden').val('0');
							$('div#set_' + elem + ' #primary_' + primaryField).val(1);
						}
					} else {
						if ($('div#set_' + elem + ' #primary_' + primaryField))
							$('div#set_' + elem + ' #primary_' + primaryField).val(0);
					}
               if (ashstrick != '')
						$('div#set_' + elem + ' span .ashstrick').remove();
					if (field.value != '')
						$('div#set_' + elem + ' .' + field.name).html(ashstrick + '<span>' + field.value + '</span>');
					else
						$('div#set_' + elem + ' .' + field.name).html(ashstrick + '<span class="inactivedatacol">Click to modify</span>');

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
				   $('div#set_' + elem + ' .' + field.name + '_hidden').val(field.value);
					$('div#set_' + elem + ' .' + existUnit + '_hidden').val(unit_value);
					var appendId = field.name.replace('unit_', '');
					$('div#set_' + elem + ' .' + appendId).append(' ' + unit_val);
               return true;
            }
         }
      }
   });
   if (trueFlag) {
      $('#' + Model).modal('hide');
   }
   $("ul#sTree3 li div.navimgdet2").each(function (i, item) {
		$(item).find('.seq_order_up').val(i + 1);
	});
   return false;
}

function createworkoutSubmit() {
   $('.errormsg').text('').hide();
   var title = $('#wkout_title').val();
   var color = $('#wrkoutcolor').val();
   var focus = $('#wkout_focus').val();
   if (title == '') {
      $('.errormsg').text('Workout Title should not empty').removeClass('hide').show();
      return false;
   } else if (color == "") {
      $('.errormsg').text('Workout Color should not empty').removeClass('hide').show();
      return false;
   } else if (focus == "") {
      $('.errormsg').text('Overall Focus should not empty').removeClass('hide').show();
      return false;
   } else if ($('#scrollablediv-len ul#sTree3 li').length == 0) {
      $('.errormsg').text('Please fill the below empty set and then try to add new set.').removeClass('hide').show();
      return false;
   } else {
      return true;
   }
}

function getWorkoutColorModel() {
   var dataval = {
      'wkout_title': $('#wkout_title').val(),
      'color_title': $('#wrkoutcolortext').attr('class').split(' ').pop(),
      'wrkoutcolor': $('#wrkoutcolor').val()
   };
   $('#FolderModal').html();
   $.ajax({
      url: siteUrl_frontend + "search/getmodelTemplate",
      data: {
         fromAdmin: true,
         action: 'workoutColor',
         method: '',
         id: '',
         foldid: '',
         dataval: dataval
      },
      success: function(content) {
         $('#FolderModal').html(content);
         $('#FolderModal').modal();
      }
   });
}

function insertInputdata() {
   $('.errormsg').text('').hide();
   var title = $('#wrkoutname').val();
   var color = $('#wrkoutcolor').val();
   if (title == '') {
      $('.errormsg').text('Workout Title should not empty').removeClass('hide').show();
      return false;
   } else if (color == "") {
      $('.errormsg').text('Workout Color should not empty').removeClass('hide').show();
      return false;
   } else {
      $('#wkout_title').val(title);
      $('.wkout_title').text(title);
      $('#wrkoutcolor').val(color);
      selectedclr = $($('#wrkoutcolortext')).attr('class').split(' ').pop();
      if (selectedclr != 'wrkoutcolor') $('#wrkoutcolortext').removeClass(selectedclr).addClass($('#wrkoutcolortxt').val());
      else $('#wrkoutcolortext').addClass('wrkoutcolor').addClass($('#wrkoutcolortxt').val());
      $('.colormodelpopup').removeClass('hide');
      closeModelwindow('FolderModal');
   }
   return false;
}

function fixToolTipColor() {
   //grab the bg color from the tooltip content - set top border of pointer to same
   $('.ui-tooltip-pointer-down-inner').each(function() {
      var bWidth = $('.ui-tooltip-pointer-down-inner').css('borderTopWidth');
      var bColor = $(this).parents('.ui-slider-tooltip').css('backgroundColor')
      $(this).css('border-top', bWidth + ' solid ' + bColor);
   });
}

function getexercisesetTemplateAjaxEdit(elem, goalOrder, type) {
	$('div.createworkout div.border').removeClass('new-item');
   $('#myOptionsModalAjax').html();
   var dataForm = $('form#createExercise input').map(function () {
		return {
			name: $(this).attr('name'),
			value: $(this).attr('value'),
			keyval: $(this).attr('data-keyval')
		}
	}).get();
   var goal_id = $('input#goal_id_hidden').val();
   var img_url = '';
   if ($('span#exerciselibimg img').length)
   	var img_url = $('span#exerciselibimg img').attr('src').replace(siteUrl_frontend, '');
	if(type != 'title')
		$('#exerciselib-template').remove();
   var datavaljson = getInputDetailsByForm(dataForm, img_url, goal_id, 2);
   $.ajax({
      url: siteUrl_frontend + "search/getmodelTemplate",
      data: {
         fromAdmin: true,
         action: 'workoutExercise',
         method: type,
         id: 0,
         foldid: goal_id,
			xrsetid: elem,
         modelType: 'myOptionsModalAjax',
         goalOrder: goalOrder,
         dataval: datavaljson
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

function getTemplateOfWorkoutAction(wkoutId, wksid, title) {
   $('#myModal').html('');
   $.ajax({
      url: siteUrl_frontend + "search/getmodelTemplate",
      data: {
         fromAdmin: true,
         action: 'workoutaction',
         method: 'action',
         id: wkoutId,
         foldid: wksid,
         title: title,
         type: 'workoutfolder'
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
      url: siteUrl_frontend + "search/getmodelTemplate",
      data: {
         fromAdmin: true,
         action: 'addAssignWorkouts',
         method: 'wkoutLogCal',
         id: wkoutid,
         date: '',
         type: 'wkoutAssigncal'
      },
      success: function(content) {
         $('#myModal').html(content);
         $('#myModal').modal();
      }
   });
}
function checkallItems(selector) {
   if ($(selector).hasClass('checked')) {
      $("input:checkbox").prop('checked', false);
      $(selector).removeClass('checked');
   } else {
      $("input:checkbox").prop('checked', true);
      $(selector).addClass('checked');
   }
   if ($('.checkboxcolor label input[type="checkbox"]:checked').length > 0) {
      $('button i.allowActive').removeClass('datacol');
      $('button i.allowActive').addClass('activecol');
   } else {
      $('button i.allowActive').addClass('datacol');
      $('button i.allowActive').removeClass('activecol');
   }
   return false;
}
function checkallItemspopup(selector) {
	if ($(selector).hasClass('checked')) {
		$("ul#sTree3 .checkboxcolor label input.checkhiddenpopup:checkbox").prop('checked', false);
		$(selector).removeClass('checked');
	} else {
		var checked = 1;
		$("ul#sTree3 .checkboxcolor label input.checkhiddenpopup:checkbox").each(function (i, field) {
			$(this).prop('checked', true);
			$(this).attr('data-check', checked);
			checked++;
		});
		$(selector).addClass('checked');
	}
	if ($('ul#sTree3 .checkboxcolor label input.checkhiddenpopup[type="checkbox"]:checked').length > 0) {
		$('div.optionmenupopup button i.allowActive').removeClass('datacol');
		$('div.optionmenupopup button i.allowActive').addClass('activecol');
	} else {
		$('div.optionmenupopup button i.allowActive').addClass('datacol');
		$('div.optionmenupopup button i.allowActive').removeClass('activecol');
	}
	$('div.createworkout div.border').removeClass('new-item');
	return false;
}
function checkallItemspopupOld(selector) {
   if ($(selector).hasClass('checked')) {
      $("ul#sTree3 input:checkbox").prop('checked', false);
      $(selector).removeClass('checked');
   } else {
		var checked = 1;
      $("ul#sTree3 input:checkbox").each(function(i, field) {
			$(this).prop('checked', true);
			$(this).attr('data-check',checked);
			checked++;
      });
      $(selector).addClass('checked');
   }
   if($('.checkboxcolor label input[type="checkbox"]:checked').length > 0){
      $('button i.allowActive').removeClass('datacol');
      $('button i.allowActive').addClass('activecol');
   } else {
      $('button i.allowActive').addClass('datacol');
      $('button i.allowActive').removeClass('activecol');
   }
	$('div.createworkout div.border').removeClass('new-item');
   return false;
}
function editExercistSets(selector) {
	$(selector).addClass('hide');
	$('#refreshpopup').removeClass('hide');
	$('#createwkoutpopup').addClass('hide');
	$('.optionmenupopup div.allowhide').removeClass('hide');
	$('.popupchoosen').show();
	$('#FolderModal').modal('hide');
	$('a.editchoosenIconTwoPopup').removeClass('hide');
	$('i.listoptionpoppopup').addClass('hide');
	$('.activelinkpopup').attr('disabled', 'disabled');
	if ($('ul#sTree3')) {
		$("ul#sTree3").sortable({
			tolerance: 'pointer',
			revert: 'invalid',
			cursor: "move",
			forceHelperSize: true,
			forcePlaceholderSize: true,
			placeholder: "sortableListsHint",
			axis: 'y',
			handle: '.panel-draggable',
			helper: function (e, li) {
				if (li.attr("data-inner-cnt") > 1) {
					li.attr("data-inner-cnt", li.attr("data-inner-cnt") - 1);
					var li_new = li;
					var target = $(e.target),
						parentdiv = target.closest('div.navimgdet2');
					var lisetid = parentdiv.attr('id'),
						lisetelem = parentdiv;
					this.copyHelper = li_new.clone()
						.find('.navimgdet2#' + lisetid).each(function() {
							if ($(this).next('hr').length > 0)
								$(this).next('hr').andSelf().remove();
							else
								$(this).prev('hr').andSelf().remove();
						}).end()
						.insertAfter(li_new);
					li.find('.navimgdet2').not('#' + lisetid).remove().end()
						.andSelf().find('hr').remove();
					return li.clone();
				} else {
					return li;
				}
			},
			stop: function (e, li) {
				if(li.item.attr("data-inner-cnt")>1){
					this.copyHelper = null;
				}
				updateLiItems();
			}
		}).disableSelection();
	}
	setDynamicHeight();
	getWkoutEditInstruction();
	$('input.checkhiddenpopup:checkbox').attr('checked', false);
	$('div.createworkout div.border').removeClass('new-item');
	$('div.optionmenu button.btn').removeClass('checked');
	if ($('button i.allowActive').hasClass('activecol')) {
		$('button i.allowActive').removeClass('activecol');
		$('button i.allowActive').addClass('datacol');
	}
	return false;
}
function editExercistSetsOld(selector) {
   $(selector).addClass('hide');
   $('#refreshpopup').removeClass('hide');
   $('#createwkoutpopup').addClass('hide');
   $('.optionmenupopup div.allowhide').removeClass('hide');
   $('.popupchoosen').show();
   $('a.editchoosenIconTwoPopup').removeClass('hide');
   $('i.listoptionpoppopup').addClass('hide');
   $('.activelinkpopup').attr('disabled', 'disabled');
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
	setDynamicHeight();
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
function getWkoutEditInstruction() {
   $('#myOptionsModal').html();
   $.ajax({
      url: siteUrl_frontend + "search/getmodelTemplate",
      data: {
         action: 'editNotification',
         modelType: 'myOptionsModal',
			fromAdmin: true,
      },
      success: function(content) {
         $('#myOptionsModal').html(content);
			if(content !='')
				$('#myOptionsModal').modal();
      }
   });
}

function editWorkoutrefresh(selector) {
   $(selector).addClass('hide');
   $('#editxrpopup').removeClass('hide');
   $('.optionmenupopup div.allowhide').addClass('hide');
   $('#createwkoutpopup').removeClass('hide');
   $('.popupchoosen').hide();
   $('.editchoosenIconTwoPopup').addClass('hide');
   $('i.listoptionpoppopup').removeClass('hide');
   $('.activelinkpopup').removeAttr('disabled');
   $('input.checkhiddenpopup:checkbox').attr('checked', false);
	$('div.createworkout div.border').removeClass('new-item');
	$('div.optionmenu button.btn').removeClass('checked');
	if (!$('.editmode').hasClass('hide'))
		$('.editmode').addClass('hide');
   return false;
}

function enableButtons() {
   if ($('.checkboxcolor label input[type="checkbox"]:checked').length > 0) {
      $('button i.allowActive').removeClass('datacol');
      $('button i.allowActive').addClass('activecol');
   } else {
      $('button i.allowActive').addClass('datacol');
      $('button i.allowActive').removeClass('activecol');
   }
}

function enablePopupButtons() {
   if ($('.checkboxcolor label input.checkhiddenpopup[type="checkbox"]:checked').length > 0) {
      $('button i.allowActive').removeClass('datacol');
      $('button i.allowActive').addClass('activecol');
   } else {
      $('button i.allowActive').addClass('datacol');
      $('button i.allowActive').removeClass('activecol');
   }
}

function getInputDetailsByForm(dataForm, img_url, elem, type) {
   var dataval = {};
	dataval['img_url'] = img_url;
	dataval['goal_id'] = elem;
	dataval['goal_title'] = '';
	dataval['goal_unit_id'] = '';
	dataval['setdetails'] = {};
   if (type == 1)
   	var requArr = { 'exercise_title': "goal_title", 'exercise_unit': "goal_unit_id", 'exercise_resistance': "goal_resist", 'exercise_unit_resistance': "goal_resist_id", 'exercise_repetitions': "goal_reps", 'exercise_time': "goal_time", 'exercise_distance': "goal_dist", 'exercise_unit_distance': "goal_dist_id", 'exercise_rate': "goal_rate", 'exercise_unit_rate': "goal_rate_id", 'exercise_innerdrive': "goal_int_id", 'exercise_angle': "goal_angle", 'exercise_unit_angle': "goal_angle_id", 'exercise_rest': "goal_rest", 'exercise_remark': "goal_remarks", 'primary_time': "primary_time", 'primary_dist': "primary_dist", 'primary_reps': "primary_reps", 'primary_resist': "primary_resist", 'primary_rate': "primary_rate", 'primary_angle': "primary_angle", 'primary_rest': "primary_rest", 'primary_int': "primary_int", 'removed_set': 'removed_set' };
   else
   	var requArr = { 'exercise_title_': "goal_title", 'exercise_unit_': "goal_unit_id", 'exercise_resistance_': "goal_resist", 'exercise_unit_resistance_': "goal_resist_id", 'exercise_repetitions_': "goal_reps", 'exercise_time_': "goal_time", 'exercise_distance_': "goal_dist", 'exercise_unit_distance_': "goal_dist_id", 'exercise_rate_': "goal_rate", 'exercise_unit_rate_': "goal_rate_id", 'exercise_innerdrive_': "goal_int_id", 'exercise_angle_': "goal_angle", 'exercise_unit_angle_': "goal_angle_id", 'exercise_rest_': "goal_rest", 'exercise_remark_': "goal_remarks", 'primary_time': "primary_time", 'primary_dist': "primary_dist", 'primary_reps': "primary_reps", 'primary_resist': "primary_resist", 'primary_rate': "primary_rate", 'primary_angle': "primary_angle", 'primary_rest': "primary_rest", 'primary_int': "primary_int" };
   var datavalcombine = {};
	$(dataForm).each(function (i, field) {
		if (datavalcombine[field.keyval] == undefined) {
			datavalcombine[field.keyval] = {};
		}
		if (datavalcombine[field.keyval] != undefined) {
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
					} else if (requArr[key] == 'goal_title' || (requArr[key] == 'goal_unit_id' && (field.name.indexOf('exercise_unit[') == 0 || field.name.indexOf('exercise_unit_hidden') == 0 || field.name.indexOf('exercise_unit[') == 0))) {
						if (requArr[key] == 'goal_title')
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

function doCopyWorkoutSubmit() {
   if ($('.checkboxcolor label input[type="checkbox"]:checked').length > 0) {
      return true;
   }
   return false;
}

function doDeleteWorkoutSubmit() {
   if ($('.checkboxcolor label input[type="checkbox"]:checked').length > 0) {
      if (confirm('Are you sure want to delete selected Workout records from My Workout Plans?')) {
         return true;
      }
   }
   return false;
}

function getXrImageRecords(xrid) {
   modalName = 'myOptionsModalExerciseRecord';
   $('#' + modalName).html();
   $.ajax({
      url: siteUrl_frontend + "search/getmodelTemplate",
      data: {
         fromAdmin: true,
         action: 'relatedRecords',
         method: 'previewimage',
         id: xrid,
         modelType: modalName
      },
      success: function(content) {
         $('#' + modalName).html(content);
         $('#' + modalName).modal();
      }
   });
}

function getXrSeqImgPreview(xrid, seqId) {
   modalName = 'myOptionsModalExerciseRecord_more';
   $('#' + modalName).html();
   $.ajax({
      url: siteUrl_frontend + "search/getmodelTemplate",
      data: {
         fromAdmin: true,
         action: 'relatedRecords',
         method: 'previewimageSeq', // on donne la cha?e de caract?e tap? dans le champ de recherche
         id: xrid,
         foldid: seqId,
         modelType: modalName
      },
      success: function(content) {
         $('#' + modalName).html(content);
         $('#' + modalName).modal();
      }
   });
}

function getTemplateOfExerciseRecordAction(exerciseSetId, selector, order) {
	var xrsetId = $(selector).closest('li').attr('data-id');
	var titlediv = 'div#itemset_' + exerciseSetId + ' div.navimgdet1 b';
	$('#myOptionsModal').html();
	var selecterId = $(selector).closest('li').attr('id');
	var editFlag = '';
	if($('li#'+selecterId+' input#'+selecterId+'_hidden').length > 0)
		editFlag = true;
    $.ajax({
      url: siteUrl_frontend + "search/getmodelTemplate",
      data: {
         fromAdmin: true,
         action: 'exerciserecordaction',
         method: 'action',
         id: '',
         foldid: exerciseSetId.replace(order + '_', ''),
		 xrid: xrsetId,
		 allowTag: true,
         editFlag: editFlag,
         modelType: 'myOptionsModal',
         title: getTitlestrip(titlediv),
		 goalOrder: order
      },
      success: function(content) {
         $('#myOptionsModal').html(content);
         $('#myOptionsModal').modal();
      }
   });
}
function insertTagOfRecord(xrId) {
	$('#myOptionsModalExerciseRecord').html();
	$.ajax({
		url: siteUrl_frontend + "search/getmodelTemplate",
		data: {
			action: 'relatedRecords',
			method: 'tagRecord',
			id: xrId,
			modelType: 'myOptionsModalExerciseRecord',
			editFlag: true
		},
		success: function (content) {
			$('#myOptionsModalExerciseRecord').html(content);
			$('#myOptionsModalExerciseRecord').modal();
		}
	});
}
function deleteExerciseSet() {
	if ($("input.checkhiddenpopup:checkbox:checked").length > 0 && confirm('Deleting this Exercise Set will not be saved until all updates to the Workout Plan have been confirmed.')) {
		$("input.checkhiddenpopup:checkbox:checked").each(function () {
			curOrder = $('#goal_order_combine_' + $(this).val()).val();
			var selectorId = $(this).parentNth(7).attr('id');
			totalxr = $('li#' + selectorId).attr('data-inner-cnt');
			if (selectorId.indexOf('new') >= 0) {
				$('li#' + selectorId).remove();
				$('#s_row_count').val($('#scrollablediv-len li').length);
			} else {
				inputcurCount = $('li#' + selectorId + ' input#' + selectorId + '_hidden').val();
				inputcurCountArr = inputcurCount.split(',');
				for (var i = 0; i < inputcurCountArr.length; i++) {
					if (inputcurCountArr[i].indexOf('new') == -1)
						$('div.removedIds').append('<input type="hidden" name="goal_remove[' + inputcurCountArr[i].replace(curOrder + '_', '') + ']" value="1"/>');
				}
				$('li#' + selectorId).remove();
				$('#s_row_count').val($('#scrollablediv-len li').length);
			}
			$('#s_row_count').val($('#scrollablediv-len li').length);
		});
		updateLiItems();
		enablePopupButtons();
	}
}
function deleteExerciseSetOld() {
   if ($("input.checkhiddenpopup:checkbox:checked").length > 0 && confirm('Deleting this Exercise Set will not be saved until all updates to the Workout Plan have been confirmed.')) {
      $("input.checkhiddenpopup:checkbox:checked").each(function() {
         curOrder = $('#goal_order_' + $(this).val()).val();
         var selectorId = $(this).parentNth(6).attr('id');
         if (selectorId.indexOf('new') >= 0) {
            $('div#' + selectorId).parent('li').remove();
         }
         $('input.seq_order_up').each(function(i, field) {
            if (curOrder < field.value) {
               inputId = $(this).attr('id');
               $('input#' + inputId).val(field.value - 1);
            }
         });
         var count = $('#s_row_count_xr').val();
         $('#s_row_count_xr').val(count - 1);
      });
      enablePopupButtons();
   }
}
function createCopyExerciseCombineSet(selector, move, xroption) {
	var wkoutid = 0;
	if ($('li#itemSet_' + wkoutid + '_' + selector).length) {
		var selectorDiv = $('li#itemSet_' + wkoutid + '_' + selector);
		var selectorId = 'div#itemset_' + selector;
		var currentXrDiv = $('div#set_id_' + xroption);
		var goalOrder = $('div#itemset_' + selector + ' input.seq_order_combine_up').val();
		var setidSelector = $('div#itemset_' + selector + ' input#itemSet_' + wkoutid + '_' + selector + '_hidden');
	}
	var divArray = [];
	var divArrayAll = [];
	$('div#itemset_' + selector + ' div.exercisesetdiv div').each(function (i, item) {
		if ($(item).attr('id'))
			divArray.push($(item).attr('id'));
		divArrayAll.push($(item).attr('id'));
	});
	currPos = divArrayAll.indexOf('set_id_' + xroption);
	last = divArray.length;

	if (move == 'last')
		var xrorder = last + 1;
	else if (move == 'down' || move == 'up')
		var xrorder = currPos + 1;

	var inlineString = $(currentXrDiv).html();
	var valuexr = parseInt($('#newlyAddedXr').val()) + 1;
	$('#newlyAddedXr').val(valuexr);
	var xroptionNew = goalOrder + '_new_' + valuexr;
	var unitId = xroption.replace(goalOrder + '_', '');
	var re1 = new RegExp(xroption, 'g');
	inlineString = inlineString.replace(re1, xroptionNew);
	var re2 = new RegExp(xroption + ']', 'g');
	inlineString = inlineString.replace(re2, xroptionNew + ']');
	var re3 = new RegExp('"' + xroption + '"', 'g');
	inlineString = inlineString.replace(re3, '"' + xroptionNew + '"');
	var re5 = new RegExp("'" + xroption + "'", 'g');
	inlineString = inlineString.replace(re5, "'" + xroptionNew + "'");
	var re4 = new RegExp(xroption + ',this', 'g');
	inlineString = inlineString.replace(re4, xroptionNew + ',this');
	var re6 = new RegExp(unitId + ']', 'g');
	inlineString = inlineString.replace(re6, 'new_' + valuexr + ']');
	var re7 = new RegExp('_' + unitId + '"', 'g');
	inlineString = inlineString.replace(re7, '_new_' + valuexr + '"');
	var xr_element = (move == 'down' || move == 'last' ? '<hr>' : '') + '<div id="set_id_' + goalOrder + '_new_' + valuexr + '" class="navimgdet2" data-id="' + goalOrder + '_new_' + valuexr + '">' + inlineString + '</div>' + (move == 'up' ? '<hr>' : '');
	if (move == 'down') {
		$(xr_element).insertAfter('div#itemset_' + selector + ' div.exercisesetdiv div#set_id_' + xroption);
	} else if (move == 'up') {
		$(xr_element).insertBefore('div#itemset_' + selector + ' div.exercisesetdiv div#set_id_' + xroption);
	} else
		$('div#itemset_' + selector + ' div.exercisesetdiv').append(xr_element);
	$('div#itemset_' + selector + ' div.exercisesetdiv div#set_id_' + goalOrder + '_new_' + valuexr + '  .listoptionpoppopup').attr("onclick", "getTemplateOfExerciseSetAction('" + selector + "','" + 'new_' + valuexr + "','link');");
	var setIds = setidSelector.val();
	$("ul#sTree3 li div.navimgdet2").each(function (i, item) {
		$(item).find('.seq_order_up').val(i + 1);
	});
	setidSelector.val(setIds + ',' + goalOrder + '_new_' + valuexr);
}
function createCopyExerciseSet(move) {
	$("input.checkhiddenpopup:checkbox:checked").each(function (i, field) {
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
			$(activeSets).each(function (i, sets) {
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
						.html(function (i, htmltext) {
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
						.html(function (i, htmltext) {
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
			var wkoutid = 0;
			setLength = $('ul#sTree3 li').length,
				goalOrder = parseInt(setLength) + 1,
				count = parseInt($('#s_row_count_flag').val()) + 1;
			$('#s_row_count').val(goalOrder);
			$('#s_row_count_flag').val(count);
			var oldUnitDataId = unitdataid,
				newUnitDataId = goalOrder + '_' + checkedSetDataid,
				selector = 'itemSet_' + wkoutid + '_' + newUnitDataId,
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
				.find('.exercisesetdiv .navimgdet2').each(function () {
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
					.find('.listoptionpoppopup').attr("onclick", "getTemplateOfExerciseSetAction('" + newUnitDataId + "','" + newsetid + "','link');").end()
					.html(function (i, htmltext) {
						var regex1 = new RegExp(oldorder_setid, 'g');
						htmltext = htmltext.replace(regex1, neworder_setid);
						var regex2 = new RegExp(oldsetid, 'g');
						htmltext = htmltext.replace(regex2, newsetid);
						return htmltext;
					});
					setids.push(neworder_setid);
					$('#newlyAddedXr').val(incid);
				}).end()
				.html(function (i, htmltext) {
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
				.appendTo($("ul#sTree3"));
			$('input#' + selector + '_hidden').val(setids.join(','));
		}
	});
	updateLiItems();
	enableButtons();
}

function createCopyExerciseSetOld(selector, move) {
   var wkoutid = 0;
	$('div.optionmenu button.btn').removeClass('checked');
	$('div.createworkout div.border').removeClass('new-item');
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
					 $('#itemsetnew_' + wkoutid + '_' + count + ' .navimgdet1').html("<b>" + titleName + "</b>");
					 if ($('#scrollablediv-len li').length > 3 && !$('#scrollablediv-len').hasClass('scrollablediv')) {
					    $('#scrollablediv-len').addClass('scrollablediv');
					 }
					 if ($('#scrollablediv-len li').length == '1') $('.sTreeBase').show();
				  } else {
					 alert('Please fill the above empty set and then try to add new set.');
				  }
				  $('#' + litagvariable + ' .editchoosenIconTwo').removeClass('hide');
				  $('#' + litagvariable + ' .listoptionpoppopup').addClass("hide");
				  $('#' + litagvariable + ' .listoptionpoppopup').attr("onclick", "getTemplateOfExerciseSetAction('" + 'new_' + wkoutid + '_' + count + "','link');");
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
				$('li#' + litagvariable + ' .listoptionpoppopup').addClass("hide");
				if (selectorIdinner.indexOf("new_") > 0)
					$('li#' + litagvariable + ' .listoptionpoppopup').attr("onclick", "getTemplateOfExerciseSetAction('"+'new_'+ wkoutid + '_' + updatedCnt + "','link');");
         }
      }
	  }
	  $('div#loading-indicator').hide();
	  $('div#scrollablediv-len ul').scrollTop($('div#scrollablediv-len ul li:nth-child('+litagscrollcnt+')').position().top - $('div#scrollablediv-len ul li:first').position().top);
	  enablePopupButtons();
     $('#s_row_count_xr').val($('#scrollablediv-len li').length);
   } else {
      if (!isNaN(selector) && $('div#itemset_' + wkoutid + '_' + selector).length) {
         var selectorDiv = $('div#itemset_' + wkoutid + '_' + selector);
         var selectorId = 'div#itemset_' + wkoutid + '_' + selector;
         var inputdata = selector;
         var current = $('div#itemset_' + wkoutid + '_' + selector).parent('li').index();
      } else if ($('div#itemset' + selector).length) {
         var selectorDiv = $('div#itemset' + selector);
         var selectorId = 'div#itemset' + selector;
         if (selector.indexOf("new_") < 0) var inputdata = selector.split('_')[2];
         else var inputdata = 'new_' + selector.replace('new_', '');
         var current = $('div#itemset' + selector).parent('li').index();
      } else if ($('div#itemsetnew_' + selector).length) {
         var selectorDiv = $('div#itemsetnew_' + selector);
         var selectorId = 'div#itemsetnew_' + selector;
         var inputdata = 'new_' + selector.replace('new_', '');
         var current = $('div#itemsetnew_' + selector).parent('li').index();
      }
      var curCnt = current;
      var last = $('#scrollablediv-len li').length;
      if ($('div.navimgdet1').text() == 'Click_to_Edit') {
         alert('Please fill the above empty set and then try to add new set.');
         return false;
      }
      if(move == 'last'){
			count	= last - 1; 
			current = count;
	  }else if (move == 'down') var count = current + 1;
      else var count = current;
      $('#s_row_count_xr').val(parseInt(last) + 1);
      var inlineString = selectorDiv.html();
      inlineString = inlineString.replace(/col-xs-2/gi, 'col-xs-aa');
      inlineString = inlineString.replace(/col-xs-8/gi, 'col-xs-bb');
      inlineString = inlineString.replace(/col-xs-4/gi, 'col-xs-cc');
      var dataForm = $(selectorId + ' .navbarmenu input').serializeArray();
      var titleName = '';
      var updatedCnt = parseInt($('#s_row_count_flag').val()) + 1;
	   $('#s_row_count_flag').val(updatedCnt);
	   var replaceFlag = true;
      $(dataForm).each(function(i, field) {
         inputNameArr = field.name.split("[")[0];
         if (inputNameArr == 'exercise_title' || inputNameArr == "exercise_title_new") {
            inputValue = field.value;
            titleName = field.value;
         } else inputValue = field.value;
         inputName = inputNameArr + '_new[]';
         if (!isNaN(inputdata)) {
            var fieldname = escapeRegExp(field.name);
            var re3 = new RegExp(fieldname, 'g');
            inlineString = inlineString.replace(re3, inputName);
         } else {
				if(replaceFlag){
					inputcurCountnew = selectorId.split('_')[1] + '_' + updatedCnt;
					inputcurCount = selectorId.split('new_')[1];
					var inputcurCount = escapeRegExp(inputcurCount);
					var re2 = new RegExp(inputcurCount, 'g');
					inlineString = inlineString.replace(re2, inputcurCountnew);
					var replaceFlag = false;
				}
         }
      });
      inlineString = inlineString.replace('"' + inputdata + '"', '"new_' + wkoutid + '_' + updatedCnt + '"');
      var re5 = new RegExp("_" + inputdata + '"', 'g');
      inlineString = inlineString.replace(re5, '_new_' + wkoutid + '_' + updatedCnt + '"');
      var re5 = new RegExp("'" + inputdata + "'", 'g');
      inlineString = inlineString.replace(re5, "'new_" + wkoutid + '_' + updatedCnt + "'");
      inlineString = inlineString.replace(/col-xs-aa/g, 'col-xs-2');
      inlineString = inlineString.replace(/col-xs-bb/g, 'col-xs-8');
      inlineString = inlineString.replace(/col-xs-cc/g, 'col-xs-4');
      if ($('div.createworkout').find('.navimgdet1').text() != 'Click_to_Edit') {
         if (move == 'down') $('#scrollablediv-len ul li:eq(' + current + ')').after('<li id="itemSetnew_' + wkoutid + '_0_' + updatedCnt + '" class="bgC4 item_add_wkout_noclick new-item" data-module="item_set_new" data-id="new_' + wkoutid + '_' + updatedCnt + '"><div class="row createworkout" id="itemsetnew_' + wkoutid + '_' + updatedCnt + '">' + inlineString + '</div></li>');
			else if(move == 'last') $('#scrollablediv-len ul li:eq(' + count + ')').after('<li id="itemSetnew_' + wkoutid + '_0_' + updatedCnt + '" class="bgC4 item_add_wkout_noclick new-item" data-module="item_set_new" data-id="new_' + wkoutid + '_' + updatedCnt + '"><div class="row createworkout" id="itemsetnew_' + wkoutid + '_' + updatedCnt + '">' + inlineString + '</div></li>');
         else $('#scrollablediv-len ul li:eq(' + count + ')').before('<li id="itemSetnew_' + wkoutid + '_0_' + updatedCnt + '" class="bgC4 item_add_wkout_noclick new-item" data-module="item_set_new" data-id="new_' + wkoutid + '_' + updatedCnt + '"><div class="row createworkout" id="itemsetnew_' + wkoutid + '_' + updatedCnt + '">' + inlineString + '</div></li>');
			var litagscrollcnt = (count == 0 ? count + 1 : count);
         $('#goal_remove_new_' + wkoutid + '_' + updatedCnt).attr('name', 'goal_remove_new[]');
         $('#goal_order_new_' + wkoutid + '_' + updatedCnt).attr('name', 'goal_order_new[]');
         if (move == 'down') $('#goal_order_new_' + wkoutid + '_' + updatedCnt).val(updatedCnt);
         if ($('#scrollablediv-len li').length > 3 && !$('#scrollablediv-len').hasClass('scrollablediv')) {
            $('#scrollablediv-len').addClass('scrollablediv');
         }
         if ($('#scrollablediv-len li').length == '1') $('.sTreeBase').show();
      } else {
         alert('Please fill the above empty set and then try to add new set.');
      }
      $('div#itemsetnew_' + wkoutid + '_' + updatedCnt + ' .listoptionpoppopup').attr("onclick", "getTemplateOfExerciseSetAction('" + wkoutid + '_' + updatedCnt + "','link');");
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
					$('li#'+ litagvariable + ' div.border').addClass('new-item');
            if (originalCnt > 3 && !$('#scrollablediv-len').hasClass('scrollablediv')) $('#scrollablediv-len').addClass('scrollablediv');
            if ($('#scrollablediv-len li').length == '1') $('.sTreeBase').show();
            $('#goal_order_' + xrIdval).val(updatedCnt);
				$('li#' + litagvariable + ' .editchoosenIconTwo').addClass('hide');
				$('li#' + litagvariable + ' .listoptionpoppopup').removeClass("hide");
				if (selectorIdinner.indexOf("new_") > 0)
					$('li#'+ litagvariable + ' .listoptionpoppopup').attr("onclick","getTemplateOfExerciseSetAction('"+'new_'+wkoutid+'_'+updatedCnt+"','link');");
         }
      }
      $('div#loading-indicator').hide();
		$('div#scrollablediv-len ul').scrollTop($('div#scrollablediv-len ul li:nth-child('+litagscrollcnt+')').position().top - $('div#scrollablediv-len ul li:first').position().top);
		$('#s_row_count_xr').val($('#scrollablediv-len li').length);
   }
}

function escapeRegExp(stringToGoIntoTheRegex) {
   return stringToGoIntoTheRegex.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
}

function getTemplateOfExerciseSetActionBycreate(exerciseSetId, link) {
   $('#FolderModal').html();
   var wkoutid = 0;
   var goalOrder = $('li#itemSet_' + wkoutid + '_' + exerciseSetId + ' input#goal_order_combine_' + exerciseSetId).val();
   $.ajax({
      url: siteUrl_frontend + "search/getmodelTemplate",
      data: {
			fromAdmin: true,
         action: 'exercisesetaction',
         method: 'createNewWrkout',
         id: wkoutid,
         foldid: exerciseSetId,
         xrid: $('#exercise_unit_new_' + exerciseSetId).val(),
         modelType: 'FolderModal',
         goalOrder: goalOrder
      },
      success: function(content) {
         $('#FolderModal').html(content);
         $('#FolderModal').modal();
      }
   });
}
function getTemplateOfExerciseSetAction(exerciseSetId, selectedSetId, link) {
	$('#FolderModal').html();
	var wkoutid = 0;
	var goalOrder = $('li#itemSet_' + wkoutid + '_' + exerciseSetId + ' input#goal_order_combine_' + exerciseSetId).val();
	var editFlag = '';
	if ($('li#itemSet_' + wkoutid + '_' + exerciseSetId).attr('data-id').indexOf('new') >= 0)
		var editFlag = true;
	var titlediv = 'div#itemset_' + exerciseSetId + ' div.navimgdet1 b';
	var xrid = $('#exercise_unit_' + selectedSetId).val();

	$.ajax({
		url: siteUrl_frontend + "search/getmodelTemplate",
		data: {
			action: 'exercisesetaction',
			method: 'createNewWrkout',
			id: wkoutid,
			foldid: exerciseSetId,
			xrsetid: selectedSetId,
			xrid: (xrid.indexOf('new') === -1 ? xrid.replace(goalOrder + '_', '') : ''),
			modelType: 'FolderModal',
			goalOrder: goalOrder,
			title: getTitlestrip(titlediv),
			editFlag: editFlag
		},
		success: function (content) {
			$('#FolderModal').html(content);
			$('#FolderModal').modal();
		}
	});
}

function doDeleteProcess(type, selector, targetId) {
	var wkoutid = 0;
	$('div.createworkout div.border').removeClass('new-item');
	$('div.optionmenu button.btn').removeClass('checked');
	if (type == 'exerciseset') {
		if (confirm('Deleting this Exercise Set will not be saved until all updates to the Workout Plan have been confirmed.')) {
			var targetset = $('li#itemSet_' + wkoutid + '_' + selector +' div.exercisesetdiv div#set_id_' + targetId);
			if (targetset.length) {
				var goalOrder = $('li#itemSet_' + wkoutid + '_' + selector + ' input.seq_order_combine_up').val();
				if (targetId.indexOf('_new') === -1) 
					$('div.removedIds').append('<input type="hidden" name="goal_remove[' + targetId.replace(goalOrder + '_', '') + ']" value="1"/>');
				if (targetset.next('hr').length > 0)
				targetset.next('hr').andSelf().remove();
			 else
				targetset.prev('hr').andSelf().remove();
				var incrId = $('li#itemSet_' + wkoutid + '_' + selector).attr('data-inner-cnt');
				$('li#itemSet_' + wkoutid + '_' + selector).attr('data-inner-cnt', parseInt(incrId) - 1);
				var inputsetVal = $('input#itemSet_' + wkoutid + '_' + selector + '_hidden').val();
				var inputUpdateVal = removeCommaWithValue(inputsetVal, targetId);
				$('input#itemSet_' + wkoutid + '_' + selector + '_hidden').val(inputUpdateVal);
				if($('div#itemset_' + selector + ' div.exercisesetdiv div.navimgdet2').length < 1){
					$('li#itemSet_' + wkoutid + '_' + selector).remove();
					$('#s_row_count').val($('#scrollablediv-len li').length);
				}else{
					$("ul#sTree3 li div.navimgdet2").each(function(i, item) {
						$(item).find('.seq_order_up').val(i+1);
					});
				}
				enablePopupButtons();
			}
			updateLiItems();
			closeModelwindow('FolderModal');
			return false;
		}
	} else {
		if (confirm('Are you sure want to delete this Workout records from My Workout Plans?')) {
			return true;
		}
	}
	return false;
}

function doDeleteProcessOld(type, selector) {
   var wkoutid = 0;
	$('div.createworkout div.border').removeClass('new-item');
	$('div.optionmenu button.btn').removeClass('checked');
   if (type == 'exerciseset') {
      if (confirm('Deleting this Exercise Set will not be saved until all updates to the Workout Plan have been confirmed.')) {
         if ($('div#itemsetnew_' + selector).length) {
            curOrder = $('#goal_order_new_' + selector).val();
            $('input.seq_order_up').each(function(i, field) {
               if (curOrder < field.value) {
                  inputId = $(this).attr('id');
                  $('input#' + inputId).val(field.value - 1);
               }
            });
            $('div#itemsetnew_' + selector).parent('li').remove();
            enablePopupButtons();
         }
         closeModelwindow('myOptionsModalAjax');
         closeModelwindow('myOptionsModal');
         return false;
      }
   } else {
      if (confirm('Are you sure want to delete this Workout records from My Workout Plans?')) {
         return true;
      }
   }
   return false;
}

function clearInputField(inputField) {
   if (inputField == 'exercise_time')
   	$('#' + inputField).val('00:00:00');
   else if (inputField == 'exercise_rest')
   	$('#' + inputField).val('00:00');
   else
   	$('#' + inputField).val('');
   if ($('select.dropdown'))
   	$('select.dropdown').val(0);
   $('.checkboxdrag').bootstrapSwitch('state', false)
}

function toggleDivTitle() {
   if ($('#expendeddiv').hasClass('fa-caret-up')) {
      $('#expendeddiv').removeClass('fa-caret-up');
      $('#expendeddiv').addClass('fa-caret-down');
      $("#expended").slideUp("slow", function() {
         if ($("#scrollablediv-len")) setDynamicHeight();
      });
   } else if ($('#expendeddiv').hasClass('fa-caret-down')) {
      $('#expendeddiv').removeClass('fa-caret-down');
      $('#expendeddiv').addClass('fa-caret-up');
      $("#expended").slideDown("slow", function() {
         if ($("#scrollablediv-len")) setDynamicHeight();
      });
   }
}

function changeFolderName() {
   if ($('#update_folder').hasClass('activedatacol')) {
      return true;
   }
   return false;
}

function enableSavebutton() {
   if (!$('#update_folder').hasClass('activedatacol')) {
      $('#update_folder').removeClass('datacol');
      $('#update_folder').addClass('activedatacol');
      $('#update_folder_icon').removeClass('datacol');
      $('#update_folder_icon').addClass('activedatacol');
   }
}

function confirmPopup() {
   if (createworkoutSubmit()) {
      $('#myOptionsModal').html();
      $.ajax({
         url: siteUrl_frontend + "search/getmodelTemplate",
         data: {
            fromAdmin: true,
            action: 'confirmWorkoutPopup',
            method: 'confirm', // on donne la cha?e de caract?e tap? dans le champ de recherche
            id: '',
            foldid: '',
            modelType: 'myOptionsModal'
         },
         success: function(content) {
            $('#myOptionsModal').html(content);
            $('#myOptionsModal').modal();
         }
      });
   }
   return false;
}

function confirmwkout(model, flag) {
   if (flag.trim() == '2') {
	  window.location.reload();
   } else {
      $('#save_edit').val(flag);
      $('form#createNewworkout').submit();
   }
}

function shareThisWorkout(wkoutid, foldid, title) {
   $('#myModal').html();
   $.ajax({
      url: siteUrl_frontend + "search/getmodelTemplate",
      data: {
         fromAdmin: true,
         action: 'shareOptions',
         id: wkoutid,
         type: 'sharewkout',
         title: title,
         modelType: 'myModal'
      },
      success: function(content) {
         $('#myModal').html(content);
         $('#myModal').modal();
      }
   });
}

function getRecipientByajaxold() {
   $('input#wkout_user_names').select2({
      placeholder: "Search Users",
      minimumInputLength: 2,
      multiple: true,
      ajax: {
         url: siteUrl_frontend + "search/getajax/",
         data: function(term, page) {
            return {
               title: term,
               maxRows: 5,
               fromAdmin: true,action: 'getusers',
            };
         },
         results: function(data, page) {
            return {
               results: data
            };
         }
      }
   });
}

function getRecipientByajaxold() {
   if ($('#wkout_user_names').length) {
      var nameArr = $('#wkout_user_names').val().split(',');
      var name = nameArr[nameArr.length - 1];
      $('#wkout_user_names').autocomplete({
         source: function(requete, reponse) {
            $.ajax({
               url: siteUrl_frontend + "search/getajax/",
               dataType: 'json',
               data: {
                  fromAdmin: true,
                  action: 'getusers',
                  title: name,
                  maxRows: 5,
                  olditem: ''
               },
               success: function(donnee) {
                  if (donnee) {
                     reponse($.map(donnee, function(item) {
                        return {
                           id: item.id,
                           name: item.name
                        }
                     }));
                  }
               }
            });
         },
         select: function(event, ui) {
            $('#wkout_user_names').html('<div class="ui-autocomplete-multiselect ui-state-default ui-widget">' + ui.item.name + '</div>');
         }
      }).data("ui-autocomplete")._renderItem = function(ul, item) {
         return $("<li>").append("<div class='col-xl-12'>" + item.name + "</div>").appendTo(ul);
      };
   }
}

function checkValidInfo() {
   if ($('input#wkout_user_names').val() != '')
   	return true;
   else
   	$('div.errormsg').html('Please choose atleast one Recipient').removeClass('hide');
   return false;
}

function checkValidAdminInfo() {
   if ($('input#wkout_user_names').val() != '' && $('input#wkout_site_names').val() != '')
   	return true;
   else {
      if ($('input#wkout_site_names').val() == '')
      	$('div.errormsg').html('Please choose atleast one Site').removeClass('hide');
      else
      	$('div.errormsg').html('Please choose atleast one Recipient').removeClass('hide')
   };
   return false;
}

function getRateFromUser(xrId) {
   $('#myOptionsModalExerciseRecord').html();
   $.ajax({
      url: siteUrl_frontend + "search/getmodelTemplate/",
      data: {
         fromAdmin: true,
         action: 'relatedRecords',
         method: 'xrrate',
         id: xrId,
         foldid: 0,
         modelType: "myOptionsModalExerciseRecord"
      },
      success: function(content) {
         $('#myOptionsModalExerciseRecord').html(content);
         $('#myOptionsModalExerciseRecord').modal();
      }
   });
}
function insertFromRelatedToXrSet(oldinsertId, unit_id, order) {
	var oldUnit_id = oldinsertId.replace(order + '_', '');
	var xrtitleVal = $('#popup_hidden_exerciseset_title_opt' + unit_id).val();
	var xrimg = $('#popup_hidden_exerciseset_image_opt' + unit_id).val();
	var wkout_id = '0';
	var selector = 'li#itemSet_' + wkout_id + '_' + oldinsertId;
	var inlineString = $(selector).html();
	var re1 = new RegExp('_' + oldinsertId, 'g');
	inlineString = inlineString.replace(re1, '_' + order + '_' + unit_id);
	var re2 = new RegExp(oldinsertId + ']', 'g');
	inlineString = inlineString.replace(re2, order + '_' + unit_id + ']');
	var re3 = new RegExp('"' + oldinsertId + '"', 'g');
	inlineString = inlineString.replace(re3, '"' + order + '_' + unit_id + '"');
	var re5 = new RegExp("'" + oldinsertId + "'", 'g');
	inlineString = inlineString.replace(re5, "'" + order + '_' + unit_id + "'");
	var re4 = new RegExp(oldUnit_id + ',this', 'g');
	inlineString = inlineString.replace(re4, unit_id + ',this');
	$(selector).html(inlineString);
	if ($(selector).length) {
		var xrtitle = 'input.exercise_title_xr_hidden';
		var xrtag = 'input.exercise_unit_xr_hidden';
	}
	$(selector + ' .navimgdet1').html('<b>' + xrtitleVal + '</b>');
	$(selector + ' .navimage').removeAttr('onclick');
	$(selector + ' ' + xrtag).val(order + '_' + unit_id);
	$(selector + ' ' + xrtitle).val(xrtitleVal);
	if (xrimg != '')
		$(selector + ' .navimage').html('<img width="75px;" src="' + xrimg + '"  class="img-responsive pointers" />');
	else
		$(selector + ' .navimage').html('<i class="fa fa-file-image-o pointers" style="font-size:50px;">');
	$(selector + ' .navimage').attr("onclick", "getTemplateOfExerciseRecordAction('" + order + '_' + unit_id + "',this,'" + order + "');");
	$(selector).attr("data-id", unit_id);
	$(selector).attr("id", 'itemSet_' + wkout_id + '_' + order + '_' + unit_id);
	$('#myOptionsModalExerciseRecord_option').modal('hide');
	$('#myOptionsModalExerciseRecord').modal('hide');
	updateLiItems();
	return true;
}

function enableExport(xrid, type) {
   $('#FolderModal').html();
   $.ajax({
      url: siteUrl_frontend + "search/getmodelTemplate/",
      data: {
         fromAdmin: true,
		 	action: 'exportActionOptions',
         method: 'action',
         id: xrid,
         foldid: 0,
         modelType: "FolderModal",
         type: type
      },
      success: function(content) {
         $('#FolderModal').html(content);
         $('#FolderModal').modal();
      }
   });
}

function sendByEmail(alldetailinput) {
   var main_arrayvalues = new Array();
   var exportid_val = $(alldetailinput).attr("data-exportid");
   var exporttype_val = $(alldetailinput).attr("data-exporttype");
   var flagtype_val = $(alldetailinput).attr("data-flagtype");
   main_arrayvalues.push({
      'exportid': exportid_val,
      'exporttype': exporttype_val,
      'flag_val': flagtype_val,
	  'fromAdmin' : true
   });
   $.post(siteUrl_frontend + "export/generateWkoutTemp", {
      "fetchallvalues": main_arrayvalues
   }, function(success) {
      var response = $.parseJSON(success);
      closeModelwindow('FolderModal');
      closeModelwindow('');
      $('.success').html('Export Mail sent Successfully!!!').show();
   });
   return true;
}
function checkTitleExist(selector) {
	if ($('li#itemSet_' + selector).length && !$('li#itemSet_' + selector).hasClass('hide')) {
		curOrder = $('li#itemSet_' + selector + ' input.seq_order_combine_up').val();
		var modaldata = $('#createExercise div.tab-content.set-tab div.tab-pane');
		modaldata.each(function (i, tab) {
			var setId = $(this).attr('data-setid');
			var tabinputs = $(tab).find('input').not('input[type="radio"]');
			tabinputs.each(function (j, input) {
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
				if ($('li#itemSet_' + selector + ' div.exercisesetdiv div#set_id_' + setId).next('hr').length > 0)
					$('li#itemSet_' + selector + ' div.exercisesetdiv div#set_id_' + setId).next('hr').andSelf().remove();
				else
					$('li#itemSet_' + selector + ' div.exercisesetdiv div#set_id_' + setId).prev('hr').andSelf().remove();
				$('li#itemSet_' + selector + ' div.exercisesetdiv div#set_id_' + setId).remove();
				inputVals = $('li#itemSet_' + selector + ' input#itemSet_' + selector + '_hidden').val();
				var inputVals = removeCommaWithValue(inputVals, setId);
				$('li#itemSet_' + selector + ' input#itemSet_' + selector + '_hidden').val(inputVals);

				var incrId = $('li#itemSet_' + selector).attr('data-inner-cnt');
				$('li#itemSet_' + selector).attr('data-inner-cnt', parseInt(incrId) - 1);

				if ($('li#itemSet_' + selector).attr('data-inner-cnt').length == 0) {
					$('li#itemSet_' + selector).remove();
					$('#s_row_count').val($('#scrollablediv-len li').length);
				}
				var xrcount = $('#newlyAddedXr').val();
				if (xrcount > 0)
					$('#newlyAddedXr').val(xrcount - 1);
			}
		});
	} else if ($('li#itemSet_' + selector).length && $('li#itemSet_' + selector).hasClass('hide')) {
		totalxr = $('li#itemSet_' + selector).attr('data-inner-cnt');
		$('li#itemSet_' + selector).remove();
		$('#s_row_count').val($('#scrollablediv-len li').length);
		var xrcount = $('#newlyAddedXr').val();
		if (xrcount > 0)
			$('#newlyAddedXr').val(parseInt(xrcount) - parseInt(totalxr));
	}
	$("ul#sTree3 li div.navimgdet2").each(function (i, item) {
		$(item).find('.seq_order_up').val(i + 1);
	});
	enablePopupButtons();
	return false;
}
function addAssignWorkoutsByDate(){
	closeModelwindow('');
}
/*opens xr filter modal for create a xr-rec*/
function createExerciseFromXrLibrary(type){
	$.ajax({
		url : siteUrl_frontend+"search/getmodelTemplate/",
		data : {
			action : 'exerciseLibrary',
			requestFrom:'dashboard',
			fromAdmin : true,
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
function createCopyXrPopup(){
	if ($('.checkboxcolor label input[type="checkbox"]:checked').length > 0) {
		$('#FolderModal').html();
		$.ajax({
			url: siteUrl_frontend + "search/getmodelTemplate",
			data: {
				fromAdmin: true,
				action: 'xrsettoolbaraction',
				method: 'copy',
				id: '',
				foldid: '',
				modelType : "FolderModal"
			},
			success: function(content) {
				$('#FolderModal').html(content);
				$('#FolderModal').modal();
			}
		});
	}
	return false;
}
/*opens option modal for create a xr-rec*/
function createExercise(xrid, type){
	xrLibCreateExercise();
}
$(document).on('click', 'div.modal a.datadetail', function (e) {
	e.stopPropagation();
	var selectorDataId = $(this).parentNth(10).attr('data-id');
	var selectorDataOrder = $(this).parentNth(10).attr('data-orderval');
	var selectorId = selectorDataOrder + '_' + selectorDataId;
	var selectorSetId = $(this).parentNth(2).attr('data-id');
	if ($(this).hasClass('exercise_repetitions_div'))
		editWorkoutRecord(selectorId, 'edit#openlink-reps', selectorSetId);
	else if ($(this).hasClass('exercise_time_div'))
		editWorkoutRecord(selectorId, 'edit#openlink-time', selectorSetId);
	else if ($(this).hasClass('exercise_distance_div'))
		editWorkoutRecord(selectorId, 'edit#openlink-dist', selectorSetId);
	else if ($(this).hasClass('exercise_resistance_div'))
		editWorkoutRecord(selectorId, 'edit#openlink-resist', selectorSetId);
	else if ($(this).hasClass('exercise_rate_div'))
		editWorkoutRecord(selectorId, 'edit#openlink-rate', selectorSetId);
	else if ($(this).hasClass('exercise_angle_div'))
		editWorkoutRecord(selectorId, 'edit#openlink-angle', selectorSetId);
	else if ($(this).hasClass('exercise_innerdrive_div'))
		editWorkoutRecord(selectorId, 'edit#openlink-int', selectorSetId);
	else if ($(this).hasClass('exercise_rest_div'))
		editWorkoutRecord(selectorId, 'edit#openlink-rest', selectorSetId);
});