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
	$('.save-icon-button').html('<a data-ajax="false" class="btn btn-default activedatacol" href="javascript:void(0)" style="background-color:#fff" onclick="return confirmPopup();">more</a>');
	$('.titlechoosen').addClass("col-xs-5").removeClass("col-xs-7");
	$('.editchoosenIconOne').hide();
	$('.editchoosenIconTwo').show();
	if ($('.createworkout').hasClass("hide")) $('.createworkout').removeClass("hide");
	if ($('.showexercisetitleTwo').hasClass("hide")) {
		$('.showexercisetitleTwo').removeClass("hide");
		$('.showexercisetitleOne').addClass("hide");
	}
	if ($('.listoptionpop')) {
		$('a.editchoosenIconTwo').addClass('hide');
		$('i.listoptionpop').removeClass('hide');
	}
	if ($('.selectdropdownTwo').hasClass("hide")) {
		$('.selectdropdownTwo').removeClass("hide");
		$('.selectdropdownOne').addClass("hide");
	}
	if ($('.activateLink')) {
		$('div.activateLink').addClass('pointers').removeClass('labelcol');
		$('div').removeClass('activateLink');
	}
	$('a.datadetail').removeClass("datacol");
	$('a.datadetail').addClass('activedatacol');
	if ($('.sTreeBase')) {
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
				// console.log(e.target, li);
				if(li.attr("data-inner-cnt")>1){
					li.attr("data-inner-cnt", li.attr("data-inner-cnt")-1);
					var li_new = li;
					var target = $(e.target), parentdiv = target.closest('div.navimgdet2');
					var lisetid = parentdiv.attr('id'), lisetelem = parentdiv;
					//this.copyHelper = li.clone().insertAfter(li.addClass('targetli'));
					this.copyHelper = li_new.clone()
						.find('.navimgdet2#' + lisetid).each(function(){
							if ($(this).next('hr').length > 0)
			               $(this).next('hr').andSelf().remove();
			            else
			               $(this).prev('hr').andSelf().remove();
						}).end()
						.insertAfter(li_new.addClass('targetli'));
					//console.log($(li_new).html());
					//this.copyHelper = li.clone().insertAfter(li);
					//return li.clone();
					// li.find('.navimgdet2').not('#' + lisetid).remove();
					li.find('.navimgdet2').not('#' + lisetid).remove().end()
					.andSelf().find('hr').remove();
					return li.clone();
				}else{
					return li.addClass('targetli');
				}
			},
			stop: function (e, li) {
				//console.log(li.item);
				if(li.item.attr("data-inner-cnt")>1){
					this.copyHelper = null;
				}else{
				}
				var dataArr = [];
				var dataArrNew = {};
				var prev = ''; var next = ''; var cur =''; 
				var results = {};
				var positions = {};
				$( "ul#sTree2 li" ).each(function(i, lival){
					dataArr.push($( "ul#sTree2 li" ).eq(i).attr('data-id'));
					//dataArrNew[$( "ul#sTree2 li" ).eq(i).attr('data-id')] = [];
				});
				dataArr.forEach(function(item, index) { 
					if ((index > 0 && dataArr[index-1] == item) || (index < dataArr.length+1 && dataArr[index+1] == item)) {
						results[item] = (results[item] || 0) + 1;
						(positions[item] || (positions[item] = [])).push(index);
					}
				});
				console.log(positions);
				$( "ul#sTree2 li" ).each(function(i, lival){
					var keyval = $( "ul#sTree2 li" ).eq(i).attr('data-id');
					if(positions[keyval] != undefined && $.inArray(i,positions[keyval]) !='-1'){
						for(var keynew =1 ; keynew < positions[keyval].length ; keynew++){
							//alert(positions[keyval][keynew]);
							$( "ul#sTree2 li" ).eq(positions[keyval][keynew])
							$( "ul#sTree2 li" ).eq(positions[keyval][0]).find('.exercisesetdiv').append('<hr>');
							curele = $( "ul#sTree2 li" ).eq(positions[keyval][keynew]).find('.exercisesetdiv').html();
							newOrder = $( "ul#sTree2 li" ).eq(positions[keyval][0]).find('input.seq_order_combine_up').val();
							oldOrder = $( "ul#sTree2 li" ).eq(positions[keyval][keynew]).find('input.seq_order_combine_up').val();
							console.log('==>'+oldOrder+'====>'+newOrder);
							var re5 = new RegExp(oldOrder + '_', 'g');
							curele = curele.replace(re5, newOrder+'_');
							//console.log(curele);
							$( "ul#sTree2 li" ).eq(positions[keyval][0]).find('.exercisesetdiv').append(curele);
							$( "ul#sTree2 li" ).eq(positions[keyval][keynew]).remove();
						}
						delete positions[keyval];
					}
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
					console.log('==>'+litagId+'====>'+litagIdHiddenval);
					$('#'+litagId+'_hidden').val(newadddiv.slice(0, -1));
				});
				var curEle = ''; var curOrder = 1; var replaceId = 1;var replaceval = '';
				var oldcombineorder =0;
				$( "ul#sTree2 li" ).each(function(i, lival){
					var oldOrder = $( "ul#sTree2 li" ).eq(i).find('.seq_order_combine_up').val();
					
					oldcombineorder = i+1;
					$(lival).find('.seq_order_combine_up').val(oldcombineorder);
					livalId = $( "ul#sTree2 li" ).eq(i).attr('id').replace('_'+oldOrder+'_','_'+oldcombineorder+'_');
					console.log(oldOrder+'===>'+livalId+'===>'+oldcombineorder+'====>'+$( "ul#sTree2 li" ).eq(i).attr('id'));
					$(lival).attr('id',livalId);
					$(lival).attr('data-orderval',i+1);
					$(lival).attr('data-inner-cnt',$( "ul#sTree2 li" ).eq(i).find('div.navimgdet2').length);
					if($( "ul#sTree2 li" ).eq(i).find('div.navimgdet2').length >1)
						$(lival).attr('data-module','item_sets');
					else
						$(lival).attr('data-module','item_set');
					
					var inlineString = $(lival).html();
					var re5 = new RegExp("_" + oldOrder + '_', 'g');
					inlineString = inlineString.replace(re5, '_'+oldcombineorder+'_');
					var re6 = new RegExp(oldOrder + '_', 'g');
					inlineString = inlineString.replace(re6, oldcombineorder+'_');
					$( "ul#sTree2 li" ).eq(i).html(inlineString);
				});
				/*$( "ul#sTree2 li" ).each(function(i, lival){
					if($(lival).attr('data-id')){
						if(i==0)
							prev = $( "ul#sTree2 li" ).eq(i).attr('data-id');
						else
							prev = $( "ul#sTree2 li" ).eq(i-1).attr('data-id');
						if(i>0){
							cur = $( "ul#sTree2 li" ).eq(i).attr('data-id');
							if(cur == prev){
								if($( "ul#sTree2 li").eq(i-1).hasClass('targetli')){
									$( "ul#sTree2 li" ).eq(i).find('.exercisesetdiv').prepend('<hr>');
									curele = $( "ul#sTree2 li" ).eq(i-1).find('.exercisesetdiv').html();
									newOrder = $( "ul#sTree2 li" ).eq(i).find('input.seq_order_combine_up').val();
									oldOrder = $( "ul#sTree2 li" ).eq(i-1).find('input.seq_order_combine_up').val();
									console.log('==>'+oldOrder+'====>'+newOrder);
									var re5 = new RegExp(oldOrder + '_', 'g');
									curele = curele.replace(re5, newOrder+'_');
									$( "ul#sTree2 li" ).eq(i).find('.exercisesetdiv').prepend(curele);
									var newadddiv = '';
									$( "ul#sTree2 li" ).eq(i).find('.exercisesetdiv div.navimgdet2').each(function(x,divimage){
										newadddiv = newadddiv.concat($(divimage).attr('data-id')+',');
									});
									litagId = $( "ul#sTree2 li" ).eq(i).attr('id');
									litagIdHiddenval = $('#'+litagId+'_hidden').val();
									console.log('==>'+litagId+'====>'+litagIdHiddenval);
									$('#'+litagId+'_hidden').val(newadddiv.slice(0, -1));
									$( "ul#sTree2 li" ).eq(i-1).remove();
								}else if($( "ul#sTree2 li" ).eq(i).hasClass('targetli')){
									$( "ul#sTree2 li" ).eq(i-1).find('.exercisesetdiv').append('<hr>');
									curele = $( "ul#sTree2 li" ).eq(i).find('.exercisesetdiv').html();
									newOrder = $( "ul#sTree2 li" ).eq(i).find('input.seq_order_combine_up').val();
									oldOrder = $( "ul#sTree2 li" ).eq(i-1).find('input.seq_order_combine_up').val();
									console.log('==>'+oldOrder+'====>'+newOrder);
									var re5 = new RegExp(oldOrder + '_', 'g');
									curele = curele.replace(re5, newOrder+'_');
									$( "ul#sTree2 li" ).eq(i-1).find('.exercisesetdiv').append(curele);
									var newadddiv = '';
									$( "ul#sTree2 li" ).eq(i).find('.exercisesetdiv div.navimgdet2').each(function(x,divimage){
										newadddiv = newadddiv.concat($(divimage).attr('data-id')+',');
									});
									litagId = $( "ul#sTree2 li" ).eq(i-1).attr('id');
									litagIdHiddenval = $('#'+litagId+'_hidden').val();
									console.log('==>'+litagId+'====>'+litagIdHiddenval);
									$('#'+litagId+'_hidden').val(newadddiv.slice(0, -1));
									$( "ul#sTree2 li" ).eq(i).remove();
								}
							}
							console.log(prev);
						}else{
							next = $( "ul#sTree2 li" ).eq(i+1).attr('data-id');
							cur = $( "ul#sTree2 li" ).eq(i).attr('data-id');
							if(cur ==next){
								if($( "ul#sTree2 li").eq(i+1).hasClass('targetli')){
									$( "ul#sTree2 li" ).eq(i).find('.exercisesetdiv').append('<hr>');
									curele = $( "ul#sTree2 li" ).eq(i+1).find('.exercisesetdiv').html();
									newOrder = $( "ul#sTree2 li" ).eq(i).find('input.seq_order_combine_up').val();
									oldOrder = $( "ul#sTree2 li" ).eq(i+1).find('input.seq_order_combine_up').val();
									var re5 = new RegExp(oldOrder + '_', 'g');
									curele = curele.replace(re5, newOrder+'_');
									$( "ul#sTree2 li" ).eq(i).find('.exercisesetdiv').append(curele);
									var newadddiv = '';
									$( "ul#sTree2 li" ).eq(i).find('.exercisesetdiv div.navimgdet2').each(function(x,divimage){
										newadddiv = newadddiv.concat($(divimage).attr('data-id')+',');
									});
									litagId = $( "ul#sTree2 li" ).eq(i).attr('id');
									litagIdHiddenval = $('#'+litagId+'_hidden').val();
									console.log('==>'+litagId+'====>'+litagIdHiddenval);
									$('#'+litagId+'_hidden').val(newadddiv.slice(0, -1));
									$( "ul#sTree2 li" ).eq(i+1).remove();
								}else if($( "ul#sTree2 li" ).eq(i).hasClass('targetli')){
									$( "ul#sTree2 li" ).eq(i+1).find('.exercisesetdiv').prepend('<hr>');
									curele = $( "ul#sTree2 li" ).eq(i).find('.exercisesetdiv').html();
									newOrder = $( "ul#sTree2 li" ).eq(i+1).find('input.seq_order_combine_up').val();
									oldOrder = $( "ul#sTree2 li" ).eq(i).find('input.seq_order_combine_up').val();
									console.log('==>'+oldOrder+'====>'+newOrder);
									var re5 = new RegExp(oldOrder + '_', 'g');
									curele = curele.replace(re5, newOrder+'_');
									//console.log(curele);
									$( "ul#sTree2 li" ).eq(i+1).find('.exercisesetdiv').prepend(curele);
									var newadddiv = '';
									$( "ul#sTree2 li" ).eq(i).find('.exercisesetdiv div.navimgdet2').each(function(x,divimage){
										newadddiv = newadddiv.concat($(divimage).attr('data-id')+',');
									});
									litagId = $( "ul#sTree2 li" ).eq(i+1).attr('id');
									litagIdHiddenval = $('#'+litagId+'_hidden').val();
									console.log('==>'+litagId+'====>'+litagIdHiddenval);
									$('#'+litagId+'_hidden').val(newadddiv.slice(0, -1));
									$( "ul#sTree2 li" ).eq(i).remove();
								}
							}
						}
					}
				});*/
				$("ul#sTree2 li div.navimgdet2").each(function(i, item) {
					$(item).find('.seq_order_up').val(i+1);
					//$( "ul#sTree2 li" ).eq(i).removeClass('targetli');
				});
				var curEle = ''; var curOrder = 1; var replaceId = 1;var replaceval = '';
				var oldcombineorder =0;
				/*$( "ul#sTree2 li" ).each(function(i, lival){
					var oldOrder = $( "ul#sTree2 li" ).eq(i).find('.seq_order_combine_up').val();
					oldcombineorder = oldcombineorder + $( "ul#sTree2 li" ).eq(i).find('div.navimgdet2').length;
					$(lival).find('.seq_order_combine_up').val(oldcombineorder);
					livalId = $( "ul#sTree2 li" ).eq(i).attr('id').replace('_'+oldOrder+'_','_'+oldcombineorder+'_');
					console.log(oldOrder+'===>'+livalId+'===>'+oldcombineorder+'====>'+$( "ul#sTree2 li" ).eq(i).attr('id'));
					$(lival).attr('id',livalId);
					$(lival).attr('data-orderval',i+1);
					$(lival).attr('data-inner-cnt',$( "ul#sTree2 li" ).eq(i).find('div.navimgdet2').length);
					if($( "ul#sTree2 li" ).eq(i).find('div.navimgdet2').length >1)
						$(lival).attr('data-module','item_sets');
					else
						$(lival).attr('data-module','item_set');
					
					var inlineString = $(lival).html();
					if(i==0){
					console.log(inlineString);
						var re5 = new RegExp("_" + oldOrder + '"', 'g');
						inlineString = inlineString.replace(re5, '_'+oldcombineorder+'_');
						console.log(inlineString);
					}
					/*if($(lival).hasClass('targetlicombin')){
						curEle = i;
						$(lival).find('div.navimgdet2').each(function(j, divval){
							$(divval).find('.seq_order_up').val(curOrder);
							curOrder++;
							replaceId = $(divval).find('.seq_order_up').val();
							elemId = $(divval).attr('data-id').split('_')[1];
							replaceval = replaceId+'_'+elemId;
							$(divval).attr('id','set_id_'+replaceval);
							$(divval).attr('data-id',replaceval);
							$(divval).find('.navbarmenu input').attr('data-keyval',replaceval);
							//console.log(replaceval);
						});
					}else if(curEle < i && curEle != 1){
						$(lival).find('div.navimgdet2').each(function(j, div){
							$(div).find('.seq_order_up').val(curOrder);
							curOrder++;
						});
					}else{
						$(lival).find('div.navimgdet2').each(function(j, div){
							curOrder = $(div).find('.seq_order_up').val();
						});
					}*/
				//});
			}
			/*stop:function(event, ui) {
				var sortedIDs = $( "ul#sTree2" ).sortable( "toArray",{attribute: 'data-id'} );
				var z = 1;
				for (var j = 0; j < sortedIDs.length; j++) {
					x = j + 1;
					var liTagDataid = sortedIDs[j];
					$('#goal_order_' + liTagDataid).val(x);
				}
			},*/
		}).disableSelection();
	}
   $('div.innerpage').show();
};

function confirmPopup() {
	if(createworkoutSubmit()){
		$('#myModal').html();
		$.ajax({
		  url: siteUrl + "search/getmodelTemplate",
		  data: {
			 action: 'confirmWorkoutPopup',
			 method: 'confirm',
			 id: $('#wkout_id').val(),
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
		$('div.errormsgdiv span.errormsgspan').text('Workout Title should not empty');
		$('div.errormsgdiv').show();
	}else if(color=="" || color=="0"){
		$('div.errormsgdiv span.errormsgspan').text('Workout Color should not empty');
		$('div.errormsgdiv').show();
	}else if(focus==""){
		$('div.errormsgdiv span.errormsgspan').text('Overall Focus should not empty');
		$('div.errormsgdiv').show();
	}else if($('#scrollablediv-len ul#sTree2 li').length == 0){
		$('div.errormsgdiv span.errormsgspan').text('Please fill the below empty set and then try to add new set.');
		$('div.errormsgdiv').show();
	}else{
		return true;
	}
	return false;
}
function getexercisesetTemplateAjaxEdit(elem, goalOrder, type) {
	$('div.createworkout div.border').removeClass('new-item');
   $('#myOptionsModalAjax').html();
   var wkoutId = $('#wkout_id').val();
   var dataForm = $('form#createExercise input').map(function() {
      return {
   		name: $(this).attr('name'),
   		value: $(this).attr('value'),
   		keyval: $(this).attr('data-keyval')
      }
	}).get();
   var goal_id = $('input#goal_id_hidden').val();
   var img_url = '';
   if ($('span#exerciselibimg img').length) var img_url = $('span#exerciselibimg img').attr('src');
	if(type != 'title')
		$('#exerciselib-template').remove();
   var datavaljson = getInputDetailsByForm(dataForm, img_url, goal_id, 2);
   $.ajax({
      url: siteUrl + "search/getmodelTemplate",
      data: {
         action: 'workoutExercise',
         method: type,
         id: wkoutId,
         foldid: goal_id,
	      xrsetid: elem,
         modelType: 'myOptionsModalAjax',
         goalOrder: goalOrder,
         dataval: datavaljson,
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
			$('body div.container').append(content);
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

function getWorkoutColorModel(wrkoutid) {
   $('#myModal').html();
   $.ajax({
      url: siteUrl + "search/getmodelTemplate",
      data: {
         action: 'workoutColor',
         method: '',
         id: wrkoutid,
         foldid: ''
      },
      success: function(content) {
         $('#myModal').html(content);
         $('#myModal').modal();
      }
   });
}

function clearInputField(inputField) {
   if (inputField == 'exercise_time') $('#' + inputField).val('00:00:00');
   else if (inputField == 'exercise_rest') $('#' + inputField).val('00:00');
   else $('#' + inputField).val('');
   if ($('select.dropdown')) $('select.dropdown').val(0);
   $('.checkboxdrag').bootstrapSwitch('state', false)
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

function getInputDetailsByForm(dataForm, img_url, elem, type) {
   var dataval = {};
   dataval['img_url'] = img_url;
   dataval['goal_id'] = elem;
   dataval['goal_title'] ='';
   dataval['goal_unit_id'] = '';
   dataval['setdetails'] = {};
   if (type == 1) var requArr = {
      'exercise_title': "goal_title",
      'exercise_unit': "goal_unit_id",
      'exercise_resistance': "goal_resist",
      'exercise_unit_resistance': "goal_resist_id",
      'exercise_repetitions': "goal_reps",
      'exercise_time': "goal_time",
      'exercise_distance': "goal_dist",
      'exercise_unit_distance': "goal_dist_id",
      'exercise_rate': "goal_rate",
      'exercise_unit_rate': "goal_rate_id",
      'exercise_innerdrive': "goal_int_id",
      'exercise_angle': "goal_angle",
      'exercise_unit_angle': "goal_angle_id",
      'exercise_rest': "goal_rest",
      'exercise_remark': "goal_remarks",
      'primary_time': "primary_time",
      'primary_dist': "primary_dist",
      'primary_reps': "primary_reps",
      'primary_resist': "primary_resist",
      'primary_rate': "primary_rate",
      'primary_angle': "primary_angle",
      'primary_rest': "primary_rest",
      'primary_int': "primary_int",
      'removed_set': 'removed_set'
   };
   else var requArr = {
      'exercise_title_': "goal_title",
      'exercise_unit_': "goal_unit_id",
      'exercise_resistance_': "goal_resist",
      'exercise_unit_resistance_': "goal_resist_id",
      'exercise_repetitions_': "goal_reps",
      'exercise_time_': "goal_time",
      'exercise_distance_': "goal_dist",
      'exercise_unit_distance_': "goal_dist_id",
      'exercise_rate_': "goal_rate",
      'exercise_unit_rate_': "goal_rate_id",
      'exercise_innerdrive_': "goal_int_id",
      'exercise_angle_': "goal_angle",
      'exercise_unit_angle_': "goal_angle_id",
      'exercise_rest_': "goal_rest",
      'exercise_remark_': "goal_remarks",
      'primary_time': "primary_time",
      'primary_dist': "primary_dist",
      'primary_reps': "primary_reps",
      'primary_resist': "primary_resist",
      'primary_rate': "primary_rate",
      'primary_angle': "primary_angle",
      'primary_rest': "primary_rest",
      'primary_int': "primary_int"
   };
   $(dataForm).each(function(i, field) {
	  if(dataval['setdetails'][field.keyval] == undefined){
		  dataval['setdetails'][field.keyval] = [];
	  }
	  if(dataval['setdetails'][field.keyval] != undefined){
		  for (var key in requArr) {
			 if (field.name.indexOf(key) >= 0) {
				if (requArr[key] == 'goal_time') {
				   inputTimeArr = field.value.split(":");
				   dataval['setdetails'][field.keyval]['goal_time_hh'] = inputTimeArr[0];
				   dataval['setdetails'][field.keyval]['goal_time_mm'] = inputTimeArr[1];
				   dataval['setdetails'][field.keyval]['goal_time_ss'] = inputTimeArr[2];
				} else if (requArr[key] == 'goal_rest') {
				   inputRestArr = field.value.split(":");
				   dataval['setdetails'][field.keyval]['goal_rest_mm'] = inputRestArr[0];
				   dataval['setdetails'][field.keyval]['goal_rest_ss'] = inputRestArr[1];
				}else if(requArr[key] == 'goal_title' || (requArr[key] == 'goal_unit_id' && (field.name.indexOf('exercise_unit[') == 0  || field.name.indexOf('exercise_unit_hidden') == 0 || field.name.indexOf('exercise_unit_new[') == 0))){
					dataval[requArr[key]] = field.value;
				} else {
				   dataval['setdetails'][field.keyval][requArr[key]] = field.value;
				}
			 }
		  }
		  dataval['setdetails'][field.keyval]['exercise_set_id'] =field.keyval;
	  }
   });   
   return convArrToObj(dataval);
}

function editWorkoutRecord(elem, method,setId) {
	if(setId ==undefined)
		setId = '';
	$('div.createworkout div.border').removeClass('new-item');
   var addOptions = '';
   if(method.indexOf('#') >= 0){
		methodArr 	= method.split("#");
		addOptions 	= methodArr[1];
		method = method.replace('#'+addOptions,'');
		
   }
   if (method == '') method = 'edit';
   $('#myOptionsModal').html('');
   $('#myModal').html('');
   $('#myModal').modal('hide');
   var methodpreview = false;
   if ($('.editmode').is(":visible") == true && method == "preview" && $('#editxr').is(":visible") == true) { method = 'edit'; } else if (method == "preview") {
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
   var wkoutId = $('#wkout_id').val();
   var datavaljson = '';
   if ($('div#itemset_' + wkoutId + '_' + elem).length) {
      var goal_id = elem;
      var img_url = '';
      if ($('div#itemset_' + wkoutId + '_' + elem + " .activelink").attr("disabled") && methodpreview) return false;
      var goalOrder = $('div#itemset_' + wkoutId + '_' + elem + ' input#goal_order_combine_' + elem).val();
	  var dataForm = $('div#itemset_' + wkoutId + '_' + elem + ' .navbarmenu input').map(function() {
					  return {
						name: $(this).attr('name'),
						value: $(this).attr('value'),
						keyval: $(this).attr('data-keyval')
					  }
					}).get();
      if ($('div#itemset_' + wkoutId + '_' + elem + ' .navimage img').length && $('div#itemset_' + wkoutId + '_' + elem + ' .navimage img').attr('src') != '') {
         var img_url = $('div#itemset_' + wkoutId + '_' + elem + ' .navimage img').attr('src').replace(siteUrl, '');
         img_url = img_url.replace('../../../', '');
      }
   } else if ($('div#itemset' + elem).length) {
      var goal_id = img_url = '';
      if ($('div#itemset' + elem + " .activelink").attr("disabled") && methodpreview) return false;
      var goalOrder = $('div#itemset' + elem + ' input#goal_order_' + elem).val();
	  var dataForm = $('div#itemset' + elem + ' .navbarmenu input').map(function() {
					  return {
						name: $(this).attr('name'),
						value: $(this).attr('value'),
						keyval: $(this).attr('data-keyval')
					  }
					}).get();
      if ($('div#itemset' + elem + ' .navimage img').length && $('div#itemset' + elem + ' .navimage img').attr('src') != '') {
         var img_url = $('div#itemset' + elem + ' .navimage img').attr('src').replace(siteUrl, '');
         img_url = img_url.replace('../../../', '');
      }
   } else if ($('div#itemsetnew_' + elem).length && method == "create") {
      var goal_id = img_url = '';
      if ($('div#itemsetnew_' + elem + " .activelink").attr("disabled") && methodpreview) return false;
      var goalOrder = $('div#itemsetnew_' + elem + ' input#goal_order_new_' + elem).val();
      var dataForm = $('div#itemsetnew_' + elem + ' input').map(function() {
					  return {
						name: $(this).attr('name'),
						value: $(this).attr('value'),
						keyval: $(this).attr('data-keyval')
					  }
					}).get();
      img_url = '';
      if ($('div#itemsetnew_' + elem + ' .navimage img').length) {
         if ($('div#itemsetnew_' + elem + ' .navimage img').attr('src') != '') {
            var img_url = $('div#itemsetnew_' + elem + ' .navimage img').attr('src').replace(siteUrl, '');
            img_url = img_url.replace('../../../', '');
         }
      }
      elem = '';
   }
   var datavaljson = getInputDetailsByForm(dataForm, img_url, goal_id, 1);
   $.ajax({
      url: siteUrl + "search/getmodelTemplate",
      data: {
         action: 'createExercise',
         method: method,
         id: wkoutId,
         foldid: elem,
         goalOrder: goalOrder,
		 xrsetid:setId,
         dataval: datavaljson,
		 addOptions:addOptions,
      },
      success: function(content) {
         $('#myOptionsModal').html(content);
         if ($('.xrsets-tab .nav-tabs.setlist-tab > li').length <= 1) {
            $('.xrsets-tab').hide();
         }
         $('#myOptionsModal').modal();
      }
   });
}


function getWorkoutsTitle() {
   if ($('#e_tb_title').length) {
      $('#e_tb_title').autocomplete({
         source: function(requete, reponse) {
            $.ajax({
               url: siteUrl + "search/getajax",
               dataType: 'json',
               data: {
                  action: 'workoutplan',
                  title: $('#e_tb_title').val(),
                  maxRows: 5
               },
               success: function(donnee) {
                  if (donnee) {
                     reponse($.map(donnee, function(item) {
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
         select: function(event, ui) {
            event.preventDefault();
            $('#e_tb_title').val(ui.item.wrktitle);
            $('#e_hid_titleid').val(ui.item.wrkid);
         },
         focus: function(event, ui) {
            event.preventDefault();
            $("#e_tb_title").val(ui.item.wrktitle);
         }
      }).data("ui-autocomplete")._renderItem = function(ul, item) {
         if (item.color.length) return $("<li>").append("<div class='col-xl-6 colorchoosen'><i class='glyphicon' style='background-color:" + item.color + ";'></i></div><div class='col-xl-6'>" + item.wrktitle + "</div>").appendTo(ul);
         else return $("<li>").append("<div class='col-xl-6'>" + item.wrktitle + "</div>").appendTo(ul);
      };
   }
}

function createNewExerciseSet(selector, move) {
	$('div.createworkout div.border').removeClass('new-item');
   var wkoutid = $('#wkout_id').val();
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
   if ($('div.createworkout').find('.navimgdet1').text() != 'Click_to_Edit') {
      var count = parseInt(last) + 1;
      $('#s_row_count').val(count);
		$('#s_row_count_flag').val(parseInt($('#s_row_count_flag').val()) + 1);
		var li_element = '<li id="itemSetnew_' + wkoutid + '_0_' + count + '" class="bgC4 item_add_wkout_noclick hide" data-module="item_set_new" data-id="new_' + wkoutid + '_' + count + '"><div class="row createworkout" id="itemsetnew_' + wkoutid + '_' + count + '"><input type="hidden" class="seq_order_up" id="goal_order_new_' + wkoutid + '_' + count + '" name="goal_order_new[]" value="' + count + '"/><input type="hidden" id="goal_remove_new_' + wkoutid + '_' + count + '" name="goal_remove_new[]" value="0"/><div class="mobpadding"><div class="border full new-item"><div class="checkboxchoosen col-xs-1 row-no-padding" style="display:none;"><div class="checkboxcolor" style="font-size:20px;"><label><input class="checkhidden" data-role="none" data-ajax="false" value="new_' + wkoutid + '_' + count + '" type="checkbox" onclick="enableButtons();" name="exercisesets[]"/><span class="cr checkbox-circle"><i class="cr-icon fa fa-check"></i></span></label></div></div><div class="col-xs-8 navdescrip row-no-padding"><div class="col-xs-4 activelink navimage row-no-padding"><i class="fa fa-pencil-square" style="font-size:50px;"></i></div><div onclick="editWorkoutRecord(' + "'new_" + wkoutid + "_" + count + "','create'" + ');" class="col-xs-8 pointers activelink datacol row-no-padding"><div class="activelink navimagedetails"><div class="navimgdet1"><b>Click_to_Edit</b></div><div data-id="'+count+'_new_1" id="set_id_'+count+'_new_1" class="navimgdet2"><div class="xrsets col-xs-9"><a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail activedatacol exercise_time_div"></a><a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail exercise_distance_div"></a><a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail activedatacol exercise_repetitions_div"></a><a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail activedatacol exercise_resistance_div"></a><a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail activedatacol exercise_rate_div"></a>&nbsp;<a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail activedatacol exercise_angle_div"></a>&nbsp;<a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail activedatacol exercise_innerdrive_div"></a>&nbsp;<a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail activedatacol exercise_rest_div"></a></div><div class="col-xs-1 navbarmenu row-no-padding"><a data-ajax="false" class="pointers editchoosenIconTwo hide" href="javascript:void(0);"><i class="fa fa-bars panel-draggable" style="font-size:25px;"></i></a><i class="fa fa-ellipsis-h iconsize listoptionpop hide"></i><input type="hidden" data-keyval="'+count+'_new_1" id="exercise_title_new_' + wkoutid + '_' + count + '" name="exercise_title_new[]" value=""/><input type="hidden" data-keyval="'+count+'_new_1" id="exercise_unit_new_' + wkoutid + '_' + count + '" name="exercise_unit_new[]" value="0"/><input type="hidden" data-keyval="'+count+'_new_1"  id="exercise_resistance_new_' + wkoutid + '_' + count + '" name="exercise_resistance_new[]" value=""/><input type="hidden" data-keyval="'+count+'_new_1" id="exercise_unit_resistance_new_' + wkoutid + '_' + count + '" name="exercise_unit_resistance_new[]" value=""/><input type="hidden" data-keyval="'+count+'_new_1" id="exercise_repetitions_new_' + wkoutid + '_' + count + '" name="exercise_repetitions_new[]" value=""/><input type="hidden" data-keyval="'+count+'_new_1" id="exercise_time_new_' + wkoutid + '_' + count + '" name="exercise_time_new[]" value=""/><input type="hidden" data-keyval="'+count+'_new_1" id="exercise_distance_new_' + wkoutid + '_' + count + '" name="exercise_distance_new[]" value=""/><input type="hidden" data-keyval="'+count+'_new_1" id="exercise_unit_distance_new_' + wkoutid + '_' + count + '" name="exercise_unit_distance_new[]" value=""/><input type="hidden" data-keyval="'+count+'_new_1" id="exercise_rate_new_' + wkoutid + '_' + count + '" name="exercise_rate_new[]" value=""/><input type="hidden" data-keyval="'+count+'_new_1" id="exercise_unit_rate_new_' + wkoutid + '_' + count + '" name="exercise_unit_rate_new[]" value=""/><input type="hidden" data-keyval="'+count+'_new_1" id="exercise_innerdrive_new_' + wkoutid + '_' + count + '" name="exercise_innerdrive_new[]" value=""/><input type="hidden" data-keyval="'+count+'_new_1" id="exercise_angle_new_' + wkoutid + '_' + count + '" name="exercise_angle_new[]" value=""/><input type="hidden" data-keyval="'+count+'_new_1" id="exercise_unit_angle_new_' + wkoutid + '_' + count + '" name="exercise_unit_angle_new[]" value=""/><input type="hidden" data-keyval="'+count+'_new_1" id="exercise_rest_new_' + wkoutid + '_' + count + '" name="exercise_rest_new[]" value=""/><input type="hidden" data-keyval="'+count+'_new_1" id="exercise_remark_new_' + wkoutid + '_' + count + '" name="exercise_remark_new[]" value=""/><input type="hidden" data-keyval="'+count+'_new_1" id="primary_time_new_' + wkoutid + '_' + count + '" class="exercise_priority_hidden" name="primary_time_new[]" value=""/><input type="hidden" data-keyval="'+count+'_new_1" id="primary_dist_new_' + wkoutid + '_' + count + '" class="exercise_priority_hidden" name="primary_dist_new[]" value=""/><input type="hidden" data-keyval="'+count+'_new_1" id="primary_reps_new_' + wkoutid + '_' + count + '" class="exercise_priority_hidden" name="primary_reps_new[]" value=""/><input type="hidden" data-keyval="'+count+'_new_1" id="primary_resist_new_' + wkoutid + '_' + count + '" class="exercise_priority_hidden" name="primary_resist_new[]" value=""/><input type="hidden" data-keyval="'+count+'_new_1" id="primary_rate_new_' + wkoutid + '_' + count + '" class="exercise_priority_hidden" name="primary_rate_new[]" value=""/><input type="hidden" data-keyval="'+count+'_new_1" id="primary_angle_new_' + wkoutid + '_' + count + '" class="exercise_priority_hidden" name="primary_angle_new[]" value=""/><input type="hidden" data-keyval="'+count+'_new_1" id="primary_rest_new_' + wkoutid + '_' + count + '" class="exercise_priority_hidden" name="primary_rest_new[]" value=""/><input type="hidden" data-keyval="'+count+'_new_1" id="primary_int_new_' + wkoutid + '_' + count + '" class="exercise_priority_hidden" name="primary_int_new[]" value=""/></div></div></div></div></div></div></div></li>';
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
      editWorkoutRecord(wkoutid + "_" + count, "create");
   }
   return false;
}

function editExercise(selector) {
	var setids = $('#' + selector+'_hidden').val().split(',');
	$('div.createworkout div.border').removeClass('new-item');
	$('#' + selector + ' .errormsg').empty();
	var modaldata = $('#createExercise input').map(function() {
		return {
			name: $(this).attr('name'),
			value: $(this).attr('value'),
			keyval: $(this).attr('data-keyval'),
			goalid: $(this).attr('data-goalid')
		}
	}).get();
   var allowCls = false;
   console.log(modaldata);
   alert('test');
   $(modaldata).each(function(i, field) {
		  if (field.name == 'exercise_title_hidden') {
			 if (field.value != "") {
				$('#' + selector + ' .navimgdet1').html('<b>' + field.value + '</b>');
				$('#' + selector).removeAttr('onclick');
				allowCls = true;
			 } else {
				$('.errormsg').text('Exercise Title not empty').removeClass('hide').show();
				return false;
			 }
		  }
		  if (field.name == 'exercise_unit_hidden') {
			 if (field.value != "" && field.value !='0') {
				if ($('#createExercise span#exerciselibimg img').length && $('#createExercise span#exerciselibimg img').attr('src') != '') $('#' + selector + ' .navimage').html('<img width="75px;" src="' + $('#createExercise span#exerciselibimg img').attr('src') + '"  class="img-responsive pointers">');
				else $('#' + selector + ' .navimage').html('<i class="fa fa-file-image-o pointers" style="font-size:50px;">');
				$('#' + selector + ' .navimage').attr("onclick", "getTemplateOfExerciseRecordAction('" + field.value + "',this);");
			 } else {
				$('#' + selector + ' .navimage').html('<i class="fa fa-pencil-square" style="font-size:50px;">');
			 }
		  }
         /*clone the set*/
         if (field.name.indexOf('_new_from') !== -1) {
            $('div#set_id_' + field.value).clone()
            .removeClass('hide deleted')
            .attr({'id': 'set_id_' + field.keyval, 'data-id': field.keyval })
            .html(function(i, htmltext) {
               var regex = new RegExp(field.value, 'g');
               return htmltext.replace(regex, field.keyval);
            })
            .insertAfter('div#' + selector + ' .navimagedetails .navimgdet2:last');
            $('<hr>').insertBefore('div#' + selector + ' .navimagedetails #set_id_' + field.keyval);
            $('#' + selector+'_hidden').val(setids + ',' + field.keyval);
            setids.push(field.keyval);
         }
         //adding deleted flag
         if (field.name.indexOf('removed_set') !== -1) {
            $('div#set_id_' + field.keyval).addClass('hide deleted');
            if ($('div#set_id_' + field.keyval).next('hr').length > 0)
               $('div#set_id_' + field.keyval).next('hr').remove();
            else
               $('div#set_id_' + field.keyval).prev('hr').remove();
            if (field.keyval.indexOf('_new') !== -1) {
               $('div#set_id_' + field.keyval).remove();
            } else {
               $('div#set_id_' + field.keyval + ' .navbarmenu').append('<input type="hidden" data-keyval="' + field.keyval + '" id="removed_set_' + field.keyval + '" name="removed_set[' + field.keyval + ']" value="1"/>');
            }
            for (var i = setids.length - 1; i >= 0; i--) {
               if (setids[i] == field.keyval) setids.splice(i, 1);
            }
            $('#' + selector+'_hidden').val(setids.join(','));
         }
         
         setTimeout(function(){
   		  newvariable = field.name.replace("_hidden", "");
   		  $('div#set_id_'+field.keyval+' #' + newvariable + '_' + field.keyval).val(field.value);
   		  var updatedText = '';
   		  if ($('#' + selector + ' div#set_id_'+field.keyval+' a.' + newvariable + '_div').length && $('#createExercise div#set_'+field.keyval+' span.' + newvariable).length) {
   			 updatedText = $('#createExercise div#set_'+field.keyval+' span.' + newvariable).html().trim();
   			 if(updatedText !='<span class="inactivedatacol">Click to modify</span>'){
   				 if (newvariable == 'exercise_rest' && updatedText.trim() != '') {
   					$('#' + selector + ' div#set_id_'+field.keyval+' a.' + newvariable + '_div').html(updatedText + ' rest');
   				 } else $('#' + selector + ' div#set_id_'+field.keyval+' a.' + newvariable + '_div').html(updatedText);
   			 }else{
   				$('#' + selector + ' div#set_id_'+field.keyval+' a.' + newvariable + '_div').html('');
   			 }
   		  }
         }, 120);
   });
    console.log(setids);
	console.log(selector);
   setTimeout(function(){
      for (var i = 0; i < setids.length; i++) {
   	   var flag = false;
   	   var setId = setids[i];
   	   if ($('#' + selector + ' div#set_id_'+setId+' a.exercise_time_div').html().trim() != '') {
   		  flag = true;
   	   }
   	   if ($('#' + selector + ' div#set_id_'+setId+' a.exercise_distance_div').html().trim() != '') {
   		  var inHtml = $('#' + selector + ' div#set_id_'+setId+' a.exercise_distance_div').html();
   		  if (flag && inHtml.trim() != '') $('#' + selector + ' div#set_id_'+setId+' a.exercise_distance_div').html(' /// ' + inHtml);
   		  else $('#' + selector + ' div#set_id_'+setId+' a.exercise_distance_div').html(inHtml);
   		  flag = true;
   	   }
   	   if ($('#' + selector + ' div#set_id_'+setId+' a.exercise_repetitions_div').html().trim() != '') {
   		  var inHtml = $('#' + selector + ' div#set_id_'+setId+' a.exercise_repetitions_div').html();
   		  if (flag && inHtml.trim() != '') $('#' + selector + ' div#set_id_'+setId+' a.exercise_repetitions_div').html(' /// ' + inHtml);
   		  else $('#' + selector + ' div#set_id_'+setId+' a.exercise_repetitions_div').html(inHtml);
   		  flag = true;
   	   }
   	   if ($('#' + selector + ' div#set_id_'+setId+' a.exercise_resistance_div').html().trim() != '') {
   		  var inHtml = $('#' + selector + ' div#set_id_'+setId+' a.exercise_resistance_div').html();
   		  if (flag && inHtml.trim() != '') $('#' + selector + ' div#set_id_'+setId+' a.exercise_resistance_div').html(' /// x ' + inHtml);
   		  else $('#' + selector + ' div#set_id_'+setId+' a.exercise_resistance_div').html(inHtml);
   	   }
      }
   }, 300);
   if (allowCls) {
		$('#' + selector + ' div.border').addClass('new-item');
	   $('#myOptionsModal').modal('hidecustom');
	   $('#myModal').modal('hidecustom');
   }
   return false;
}

function addnewExercise(elem) {
   $('.errormsg').hide();
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
         if(field.value !="" && field.value > 0){
            if ($('#createExercise span#exerciselibimg img').length && $('#createExercise span#exerciselibimg img').attr('src') != '') $('#itemsetnew_' + elem + ' .navimage').html('<img width="75px;" src="' + $('#createExercise span#exerciselibimg img').attr('src') + '"  class="img-responsive pointers">');
            else $('#itemsetnew_' + elem + ' .navimage').html('<i class="fa fa-file-image-o pointers" style="font-size:50px;">');
            $('#itemsetnew_' + elem + ' .navimage').attr("onclick", "getTemplateOfExerciseRecordAction('" + field.value + "',this);");
         } else {
            $('#itemsetnew_' + elem + ' .navimage').html('<i class="fa fa-pencil-square" style="font-size:50px;">');
         }
      }
      /*clone the set*/
      if (field.name.indexOf('_new_from') !== -1) {
         $('div#set_id_' + field.value).clone()
         .removeClass('hide deleted')
         .attr({'id': 'set_id_' + field.keyval, 'data-id': field.keyval })
         .html(function(i, htmltext) {
            var regex = new RegExp(field.value, 'g');
            return htmltext.replace(regex, field.keyval);
         })
         .insertAfter('#itemsetnew_' + elem + ' .navimagedetails .navimgdet2:last');
         $('<hr>').insertBefore('#itemsetnew_' + elem + ' .navimagedetails #set_id_' + field.keyval);
         // $('#itemsetnew_' + elem + '_hidden').val(setids + ',' + field.keyval);
         // setids.push(field.keyval);
      }
      //adding deleted flag
      if (field.name.indexOf('removed_set') !== -1) {
         $('div#set_id_' + field.keyval).addClass('hide deleted');
         if ($('div#set_id_' + field.keyval).next('hr').length > 0)
            $('div#set_id_' + field.keyval).next('hr').remove();
         else
            $('div#set_id_' + field.keyval).prev('hr').remove();
         // if (field.keyval.indexOf('_new') !== -1) {
         $('div#set_id_' + field.keyval).remove();
         // }
         // for (var i = setids.length - 1; i >= 0; i--) {
         //    if (setids[i] == field.keyval) setids.splice(i, 1);
         // }
         // $('#' + selector+'_hidden').val(setids.join(','));
      }
      setTimeout(function(){
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
      }, 120);
   });
   setTimeout(function(){
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
   }, 150);
   if (allowCls) {
		$('#goal_order_' + elem).val(0);
      if ($('#itemsetnew_' + elem).parent('li').hasClass('hide')) $('#itemsetnew_' + elem).parent('li').removeClass('hide');
      if ($('#scrollablediv-len ul').hasClass('hide')) $('#scrollablediv-len ul').removeClass('hide');
		$('#itemsetnew_' + elem + ' div.border').addClass('new-item');
		litagscrollcnt = $('#itemsetnew_' + elem).parent('li').index();
		if(litagscrollcnt == '0')
			$('div#scrollablediv-len ul').scrollTop($('div#scrollablediv-len ul li:first').position().top);
		else if($('#scrollablediv-len ul li').length == litagscrollcnt)
			$('div#scrollablediv-len ul').scrollTop($('div#scrollablediv-len ul li:last').position().top);
		else
			$('div#scrollablediv-len ul').scrollTop($('div#scrollablediv-len ul li:nth-child('+litagscrollcnt+')').position().top - $('div#scrollablediv-len ul li:first').position().top);
      $('#myOptionsModal').modal('hidecustom');
	   $('#myModal').modal('hidecustom');
   }
   return false;
};

function insertExtraToParentHidden(Model, elem) {
   if (Model.trim() == '') Model = 'myModal';
   $('.unitnormal').hide();
   $('.inputnormal').hide();
   if ($('.error').hasClass('hide')) $('.error').addClass('hide')
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
                if(field.value == 0){
					$('#exerciselibimg').append('<i class="fa fa-pencil-square datacol" style="font-size:50px;">');
				}else if(field.value > 0 && $('#exercise_unit_img').val() == ''){
						$('#exerciselibimg').append('<i class="fa fa-file-image-o pointers" style="font-size:50px;">');
				}else{
					$('#exerciselibimg').append('<img style="padding-right:10px;" width="75px;" src="'+$('#exercise_unit_img').val()+'"  />');
				}
               $('#exercise_unit_hidden').val(field.value);
            } else if (field.name == 'exercise_repetitions') {
               if (ashstrick != '') $('div#set_'+elem+' span .ashstrick').remove();
               if (field.value != '') $('div#set_'+elem+' .' + field.name).html(ashstrick + '<span>' + field.value + '</span> reps');
               else $('div#set_'+elem+' .' + field.name).html('<span class="inactivedatacol">Click to modify</span>');
            } else if (field.name == 'exercise_innerdrive') {
               if (ashstrick != '') $('div#set_'+elem+' span .ashstrick').remove();
               if (field.value != '') {
                  matchesval = document.getElementById("innerdrive").options[field.value].text;
                  if (matchesval != 'Select') {
                     var regExp = /\(([^)]+)\)/;
                     var matches = regExp.exec(matchesval);
                     $('div#set_'+elem+' .' + field.name).html(ashstrick + '<span>' + matches[1] + '</span> Int');
                  } else $('div#set_'+elem+' .' + field.name).html('<span class="inactivedatacol">Click to modify</span>');
               } else { 
					$('div#set_'+elem+' .' + field.name).html('<span class="inactivedatacol">Click to modify</span>'); 
			  }
            } else {
               if (ashstrick != '') $('div#set_'+elem+' span .ashstrick').remove();
			   if(field.value !='')
				  $('div#set_'+elem+' .' + field.name).html(ashstrick + '<span>' + field.value + '</span>');
			   else
				  $('div#set_'+elem+' .' + field.name).html(ashstrick + '<span class="inactivedatacol">Click to modify</span>');
            }
            if ($('div#set_'+elem+' .' + field.name + '_hidden')) {
               if (field.value != '' && ashstrick != '') {
                  if (primaryField != '' && $('div#set_'+elem+' #primary_' + primaryField)) {
                     $('div#set_'+elem+' .exercise_priority_hidden').val('0');
                     $('div#set_'+elem+' #primary_' + primaryField).val(1);
                  }
               } else {
                  if ($('div#set_'+elem+' #primary_' + primaryField)) $('div#set_'+elem+' #primary_' + primaryField).val(0);
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
               $('div#set_'+elem+' .' + field.name + '_hidden').val(field.value);
            }
         } else {
            unit_val = $('#workoutexercise #' + existUnit + ' option:selected').text();
            unit_value = $('#workoutexercise #' + existUnit + ' option:selected').val();
            if (unit_val == 'choose') unit_val = '';
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
                  if (primaryField != '' && $('div#set_'+elem+' #primary_' + primaryField)) {
                     $('div#set_'+elem+' .exercise_priority_hidden').val('0');
                     $('div#set_'+elem+' #primary_' + primaryField).val(1);
                  }
               } else {
                  if ($('div#set_'+elem+' #primary_' + primaryField)) $('div#set_'+elem+' #primary_' + primaryField).val(0);
               }
               if (ashstrick != '') $('div#set_'+elem+' span .ashstrick').remove();
			   if(field.value != '')
				  $('div#set_'+elem+' .' + field.name).html(ashstrick + '<span>' + field.value + '</span>');
			   else
				  $('div#set_'+elem+' .' + field.name).html(ashstrick + '<span class="inactivedatacol">Click to modify</span>');
               
			   if((field.name == 'exercise_angle' && ((!$('div#set_'+elem+' .exercise_angle_hidden').val() > 0 && field.value!='') || ($('div#set_'+elem+' .exercise_angle_hidden').val() > 0 && field.value==''))) || ((field.name == 'exercise_rate' && ((!$('div#set_'+elem+' .exercise_rate_hidden').val() > 0 && field.value!='') || ($('div#set_'+elem+' .exercise_rate_hidden').val() > 0 && field.value==''))))){
				  var actioncount = $('div#set_'+elem+' span#showcountXrvariable').html().trim();
				  if(field.value != '' && (unit_value != '0' || unit_value != '')){
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
			   $('div#set_'+elem+' .' + field.name + '_hidden').val(field.value);
               $('div#set_'+elem+' .' + existUnit + '_hidden').val(unit_value);
               var appendId = field.name.replace('unit_', '');
               $('div#set_'+elem+' .' + appendId).append(' ' + unit_val);
               return true;
            }
         }
      }
   });
   if (trueFlag) {
      $('#' + Model).modal('hidecustom');
   }
   return false;
}

function getTemplateOfExerciseSetAction(exerciseSetId, selectedSetId, link) {
   $('#myModal').html();
   var wkoutid = $('#wkout_id').val();
   var goalOrder = $('div#itemset_' + wkoutid + '_' + exerciseSetId + ' input#goal_order_' + exerciseSetId).val();
   var titlediv = '';
   if(exerciseSetId.indexOf('new') >= 0)
		titlediv = 'div#itemset'+ exerciseSetId + ' div.navimgdet1 b';
   else
		titlediv = 'div#itemset_'+wkoutid+'_'+ exerciseSetId + ' div.navimgdet1 b';	
   $.ajax({
      url: siteUrl + "search/getmodelTemplate",
      data: {
         action: 'exercisesetaction',
         method: 'action',
         id: wkoutid,
         foldid: exerciseSetId,
		 xrsetid:selectedSetId,
         xrid: $('#exercise_unit_' + exerciseSetId).val(),
         modelType: link,
         goalOrder: goalOrder,
		 title:  getTitlestrip(titlediv),
		 editFlag: ($('.editmode').is(":visible") ? true : ''),
      },
      success: function(content) {
         $('#myModal').html(content);
         $('#myModal').modal();
      }
   });
}

function getTemplateOfExerciseSetActionBycreate(elem, link) {
   $('#myModal').html();
   var goalOrder = $('div#itemset' + elem + ' input#goal_order_' + elem).val();
   var titlediv = '';
   if(elem.indexOf('new') >= 0)
		titlediv = 'div#itemset'+ elem + ' div.navimgdet1 b';
   else
		titlediv = 'div#itemsetnew_'+ elem + ' div.navimgdet1 b';	
   $.ajax({
      url: siteUrl + "search/getmodelTemplate",
      data: {
         action: 'exercisesetaction',
         method: 'action-create',
         id: elem,
         foldid: elem,
         modelType: link,
         xrid: $('#exercise_unit_new_' + elem).val(),
		 title:  getTitlestrip(titlediv),
         goalOrder: goalOrder,
      },
      success: function(content) {
         $('#myModal').html(content);
         $('#myModal').modal();
      }
   });
}

function getTemplateOfExerciseSetActionByprev(exerciseSetId, link, goalOrder) {
	var wkoutid = $('#wkout_id').val();
	var titlediv = '';
	if(exerciseSetId.indexOf('new') >= 0)
		titlediv = 'div#itemset'+ exerciseSetId + ' div.navimgdet1 b';
	else
		titlediv = 'div#itemset_'+wkoutid+'_'+ exerciseSetId + ' div.navimgdet1 b';	
   $('#myOptionsModalAjax').html();
   $.ajax({
      url: siteUrl + "search/getmodelTemplate",
      data: {
         action: 'exercisesetaction',
         method: 'action-edit',
         id: wkoutid,
         foldid: exerciseSetId,
         modelType: link,
         xrid: $('#exercise_unit_' + exerciseSetId).val(),
		 title:  getTitlestrip(titlediv),
         goalOrder: goalOrder,
      },
      success: function(content) {
         $('#myOptionsModalAjax').html(content);
         $('#myOptionsModalAjax').modal();
      }
   });
}

function getTemplateOfExerciseRecordAction(exerciseSetId, selector) {
   if (!$(selector).attr('disabled')) {
      var xrsetId = $(selector).parents('li').attr('data-id').replace('new_', '');
	  var titlediv = '';
	  if($(selector).parents('li').attr('data-id').indexOf('new') >= 0)
		 titlediv = 'div#itemsetnew_'+ xrsetId + ' div.navimgdet1 b';
	  else
		 titlediv = 'div#itemset_'+$('#wkout_id').val()+'_'+ xrsetId + ' div.navimgdet1 b';	
      $('#myModal').html();
      $.ajax({
         url: siteUrl + "search/getmodelTemplate",
         data: {
            action: 'exerciserecordaction',
            method: 'action',
            id: $('#wkout_id').val(),
            foldid: exerciseSetId,
            xrid: xrsetId,
            allowTag: true,
			title:  getTitlestrip(titlediv),
			editFlag: ($('.editmode').is(":visible") ? true : ''),
         },
         success: function(content) {
            $('#myModal').html(content);
            $('#myModal').modal();
         }
      });
   }
}

function getTemplateOfWorkoutAction() {
   $('#myModal').html('');
   $.ajax({
      url: siteUrl + "search/getmodelTemplate",
      data: {
         action: 'workoutaction',
         method: 'action',
         id: $('#wkout_id').val(),
         foldid: ''
      },
      success: function(content) {
         $('#myModal').html(content);
         $('#myModal').modal();
      }
   });
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
(function($) {
   $.fn.parentNth = function(n) {
      var el = $(this);
      for (var i = 0; i < n; i++) el = el.parent();
      return el;
   };
})(jQuery);

function createCopyExerciseSet(selector, move) {
   var wkoutid = $('#wkout_id').val();
	$('div.optionmenu button.btn').removeClass('checked');
	$('div.createworkout div.border').removeClass('new-item');
   if (selector == '') {
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
				  $('#' + litagvariable + ' .listoptionpop').addClass("hide");
				  $('#' + litagvariable + ' .listoptionpop').attr("onclick", "getTemplateOfExerciseSetAction('" + 'new_' + wkoutid + '_' + count + "','link');");
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
            if (originalCnt > 3 && !$('#scrollablediv-len').hasClass('scrollablediv')) {
               $('#scrollablediv-len').addClass('scrollablediv');
            }
            if ($('#scrollablediv-len li').length == '1') $('.sTreeBase').show();
            $('#goal_order_' + xrIdval).val(updatedCnt);
				$('li#' + litagvariable + ' .editchoosenIconTwo').removeClass('hide');
				$('li#' + litagvariable + ' .listoptionpop').addClass("hide");
				if (selectorIdinner.indexOf("new_") > 0)
					$('li#' + litagvariable + ' .listoptionpop').attr("onclick", "getTemplateOfExerciseSetAction('" + 'new_' + wkoutid + '_' + updatedCnt + "','link');");
         }
      }
	  }
	  $('div#loading-indicator').hide();
	  $('div#scrollablediv-len ul').scrollTop($('div#scrollablediv-len ul li:nth-child('+litagscrollcnt+')').position().top - $('div#scrollablediv-len ul li:first').position().top);
	  enableButtons();
     $('#s_row_count').val($('#scrollablediv-len li').length);
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
      $('#s_row_count').val(parseInt(last) + 1);
      var inlineString = $(selectorDiv).html();
      inlineString = inlineString.replace(/col-xs-2/gi, 'col-xs-aa');
      inlineString = inlineString.replace(/col-xs-8/gi, 'col-xs-bb');
      inlineString = inlineString.replace(/col-xs-4/gi, 'col-xs-cc');
      var dataForm = $(selectorId + ' .navbarmenu input').serializeArray();
      var titleName = '';
		var replaceFlag = true;
	   var updatedCnt = parseInt($('#s_row_count_flag').val()) + 1;
	   $('#s_row_count_flag').val(updatedCnt);
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
					console.log('====>'+inputcurCount+'<====');
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
         if ($('#scrollablediv-len li').length > 3 && !$('#scrollablediv-len').hasClass('scrollablediv')) $('#scrollablediv-len').addClass('scrollablediv');
         if ($('#scrollablediv-len li').length == '1') $('.sTreeBase').show();
      } else {
         alert('Please fill the above empty set and then try to add new set.');
      }
		$('#itemsetnew_'+wkoutid+'_'+updatedCnt+' .editchoosenIconTwo').addClass('hide');
		$('#itemsetnew_'+wkoutid+'_'+updatedCnt+' .listoptionpop').removeClass("hide");
      $('#itemsetnew_'+wkoutid+'_'+updatedCnt+' .listoptionpop').attr("onclick", "getTemplateOfExerciseSetAction('" + 'new_' + wkoutid + '_' + updatedCnt + "','link');");
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
					$('li#'+ litagvariable + ' div.border').addClass('new-item');
            if (originalCnt > 3 && !$('#scrollablediv-len').hasClass('scrollablediv')) 	$('#scrollablediv-len').addClass('scrollablediv');
            if ($('#scrollablediv-len li').length == '1') $('.sTreeBase').show();
            $('#goal_order_' + xrIdval).val(updatedCnt);
				$('li#' + litagvariable + ' .editchoosenIconTwo').addClass('hide');
				$('li#' + litagvariable + ' .listoptionpop').removeClass("hide");
				if (selectorIdinner.indexOf("new_") > 0)
					$('li#' + litagvariable + ' .listoptionpop').attr("onclick", "getTemplateOfExerciseSetAction('"+ 'new_' + wkoutid + '_' + updatedCnt + "','link');");
         }
      }
		$('div#loading-indicator').hide();
		$('div#scrollablediv-len ul').scrollTop($('div#scrollablediv-len ul li:nth-child('+litagscrollcnt+')').position().top - $('div#scrollablediv-len ul li:first').position().top);
      $('#s_row_count').val($('#scrollablediv-len li').length);
   }
}

function deleteExerciseSet() {
   if (confirm('Deleting this Exercise Set will not be saved until all updates to the Workout Plan have been confirmed.')) {
      $("input.checkhidden:checkbox:checked").each(function() {
         curOrder = $('#goal_order_' + $(this).val()).val();
         var selectorId = $(this).parentNth(6).attr('id');
         if (selectorId.indexOf('new') >= 0) {
            $('div#' + selectorId).parent('li').remove();
            $('#s_row_count').val($('#scrollablediv-len li').length);
         } else {
            inputcurCountArr = selectorId.split('_');
            inputcurCount = inputcurCountArr[inputcurCountArr.length - 1];
            $('div.removedIds').append('<input type="hidden" name="goal_remove[' + inputcurCount + ']" value="1"/>');
            $('div#' + selectorId).parent('li').remove();
            $('#s_row_count').val($('#scrollablediv-len li').length);
         }
         $('input.seq_order_up').each(function(i, field) {
            if (curOrder < field.value) {
               inputId = $(this).attr('id');
               $('input#' + inputId).val(field.value - 1);
            }
         });
      });
      enableButtons();
   }
}

function doDeleteProcess(type, selector) {
   var wkoutid = $('#wkout_id').val();
	$('div.createworkout div.border').removeClass('new-item');
	$('div.optionmenu button.btn').removeClass('checked');
   if (type == 'exerciseset') {
      if (confirm('Deleting this Exercise Set will not be saved until all updates to the Workout Plan have been confirmed.')) {
         if (!isNaN(selector) && $('div#itemset_' + wkoutid + '_' + selector).length) {
            $('div.removedIds').append('<input type="hidden" name="goal_remove[' + selector + ']" value="1"/>');
            curOrder = $('#goal_order_' + selector).val();
            $('input.seq_order_up').each(function(i, field) {
               if (curOrder < field.value) {
                  inputId = $(this).attr('id');
                  $('input#' + inputId).val(field.value - 1);
               }
            });
            $('div#itemset_' + wkoutid + '_' + selector).parent('li').remove();
            $('#s_row_count').val($('#scrollablediv-len li').length);
            enableButtons();
         } else if ($('div#itemset' + selector).length) {
            if (selector.indexOf('new') >= 0) {
               curOrder = $('#goal_order_' + selector).val();
               $('input.seq_order_up').each(function(i, field) {
                  if (curOrder < field.value) {
                     inputId = $(this).attr('id');
                     $('input#' + inputId).val(field.value - 1);
                  }
               });
               $('div#itemset' + selector).parent('li').remove();
               $('#s_row_count').val($('#scrollablediv-len li').length);
               enableButtons();
            } else {
               inputcurCountArr = selector.split('_');
               inputcurCount = inputcurCountArr[inputcurCountArr.length - 1];
               $('div.removedIds').append('<input type="hidden" name="goal_remove[' + inputcurCount + ']" value="1"/>');
               curOrder = $('#goal_order_' + selector).val();
               $('input.seq_order_up').each(function(i, field) {
                  if (curOrder < field.value) {
                     inputId = $(this).attr('id');
                     $('input#' + inputId).val(field.value - 1);
                  }
               });
               $('div#itemset' + selector).parent('li').remove();
               $('#s_row_count').val($('#scrollablediv-len li').length);
               enableButtons();
            }
         } else if ($('div#itemsetnew_' + selector).length) {
            curOrder = $('#goal_order_new_' + selector).val();
            $('input.seq_order_up').each(function(i, field) {
               if (curOrder < field.value) {
                  inputId = $(this).attr('id');
                  $('input#' + inputId).val(field.value - 1);
               }
            });
            $('div#itemsetnew_' + selector).parent('li').remove();
            $('#s_row_count').val($('#scrollablediv-len li').length);
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

function toggleDivTitle() {
   if ($('#expendeddiv').hasClass('fa-caret-up')) {
      $('#expendeddiv').removeClass('fa-caret-up');
      $('#expendeddiv').addClass('fa-caret-down');
      $("#expended").slideUp("slow", function() {
         if ($("#scrollablediv-len"))
            setDynamicHeight();
      });
   } else if ($('#expendeddiv').hasClass('fa-caret-down')) {
      $('#expendeddiv').removeClass('fa-caret-down');
      $('#expendeddiv').addClass('fa-caret-up');
      $("#expended").slideDown("slow", function() {
         if ($("#scrollablediv-len"))
            setDynamicHeight();
      });
   }
}
// by G.R
function getWkoutEditInstruction() {
	if(allowNotify){
	   $('#myModal').html();
	   $.ajax({
		  url: siteUrl + "search/getmodelTemplate",
		  data: {
			 action: 'editNotification',
			 modelType: 'myModal',
		  },
		  success: function(content) {
			 $('#myModal').html(content);
			 if(content !='')
				$('#myModal').modal();
		  }
	   });
	}
}

function editExercistSets(selector) {
   $(selector).addClass('hide');
   $('#refresh').removeClass('hide');
   $('#createwkout').addClass('hide');
   $('.optionmenu div.allowhide').removeClass('hide');
   $('.checkboxchoosen').show();
   $('a.editchoosenIconTwo').removeClass('hide');
   $('i.listoptionpop').addClass('hide');
   $('.activelink').attr('disabled', 'disabled');
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

function editWorkout(selector) {  
   $(selector).addClass('hide');
   $('#editxr').removeClass('hide');
   $('.optionmenu div.allowhide').addClass('hide');
   $('#createwkout').removeClass('hide');
   $('.checkboxchoosen').hide();
   $('a.editchoosenIconTwo').addClass('hide');
   $('i.listoptionpop').removeClass('hide');
   $('.activelink').removeAttr('disabled');
   $('input.checkhidden:checkbox').attr('checked', false);
	$('div.createworkout div.border').removeClass('new-item');
	$('div.optionmenu button.btn').removeClass('checked');
   return false;
}

function checkallItems(selector) {
   if ($(selector).hasClass('checked')) {
      $("input:checkbox").prop('checked', false);
      $(selector).removeClass('checked');
   } else {
		var checked = 1;
      $("input:checkbox").each(function(i, field) {
			$(this).prop('checked', true);
			$(this).attr('data-check',checked);
			checked++;
      });
      $(selector).addClass('checked');
   }
   if ($('.checkboxcolor label input[type="checkbox"]:checked').length > 0) {
      $('button i.allowActive').removeClass('datacol');
      $('button i.allowActive').addClass('activecol');
   } else {
      $('button i.allowActive').addClass('datacol');
      $('button i.allowActive').removeClass('activecol');
   }
	$('div.createworkout div.border').removeClass('new-item');
   return false;
}

function addAssignWorkouts(wkoutid) {
   $('#myModal').html();
   $.ajax({
      url: siteUrl + "search/getmodelTemplate",
      data: {
         action: 'addAssignWorkouts',
         method: 'wrkout',
         id: wkoutid,
         date: '',
         type: 'wkoutAssignCal',
      },
      success: function(content) {
         $('#myModal').html(content);
         $('#myModal').modal();
      }
   });
}

function getExercisepreviewOfDay(exerciseId, wkoutId) {
   $('#myOptionsModal').html();
   $.ajax({
      url: siteUrl + "search/getmodelTemplate",
      data: {
         action: 'previewExerciseOfDay',
         method: 'preview',
         id: exerciseId,
         foldid: wkoutId,
         modelType: 'myOptionsModal'
      },
      success: function(content) {
         $('#myOptionsModal').html(content);
         $('#myOptionsModal').modal();
      }
   });
}

function getworkoutpreview(wkoutId) {
   closeModelwindow('');
}

function getXrImageRecords(xrid) {
   modalName = 'myOptionsModalExerciseRecord';
   $('#' + modalName).html();
   $.ajax({
      url: siteUrl + "search/getmodelTemplate",
      data: {
         action: 'relatedRecords',
         method: 'previewimage',
         id: xrid,
         modelType: modalName,
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
      url: siteUrl + "search/getmodelTemplate",
      data: {
         action: 'relatedRecords',
         method: 'previewimageSeq',
         id: xrid,
         foldid: seqId,
         modelType: modalName,
      },
      success: function(content) {
         $('#' + modalName).html(content);
         $('#' + modalName).modal();
      }
   });
}

function escapeRegExp(stringToGoIntoTheRegex) {
   return stringToGoIntoTheRegex.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
}

function getRateFromUser(xrId) {
   $('#myOptionsModalExerciseRecord').html();
   $.ajax({
      url: siteUrl + "search/getmodelTemplate/",
      data: {
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

function insertFromRelatedToXrSet(oldinsertId , unit_id) {
   if ($('.editmode').hasClass('hide')) changeTosaveIcon();
   var xrtitleVal = $('#popup_hidden_exerciseset_title_opt'+unit_id).val();
   var xrimg = $('#popup_hidden_exerciseset_image_opt'+unit_id).val();
   var wkout_id = $('#wkout_id').val();
   var selector = 'div#itemset_' + wkout_id + '_' + oldinsertId;
   if ($(selector).length) {
      var xrtag = '#exercise_unit_' + oldinsertId;
      var xrtitle = '#exercise_title_' + oldinsertId;
   } else {
      var selector = 'div#itemsetnew_' + oldinsertId;
      if ($(selector).length) {
         var xrtag = '#exercise_unit_new_' + oldinsertId;
         var xrtitle = '#exercise_title_new_' + oldinsertId;
      } else {
         var selector = 'div#itemset' + oldinsertId;
         if ($(selector).length) {
            var xrtag = '#exercise_unit_' + oldinsertId;
            var xrtitle = '#exercise_title_' + oldinsertId;
         }
      }
   }
   $(selector + ' .navimgdet1').html('<b>' + xrtitleVal + '</b>');
   $(selector + ' .navimage').removeAttr('onclick');
   $(selector + ' ' + xrtag).val(unit_id);
   $(selector + ' ' + xrtitle).val(xrtitleVal);
   if (xrimg != '') $(selector + ' .navimage').html('<img width="75px;" src="' + xrimg + '"  class="img-responsive pointers" />');
   else $(selector + ' .navimage').html('<i class="fa fa-file-image-o pointers" style="font-size:50px;">');
   $(selector + ' .navimage').attr("onclick", "getTemplateOfExerciseRecordAction('" + unit_id + "',this);");
   $('#myOptionsModalExerciseRecord_option').modal('hide');
   $('#myOptionsModalExerciseRecord').modal('hide');
   return true;
}

function insertTagOfRecord(xrId) {
   $('#myOptionsModalExerciseRecord').html();
   $.ajax({
      url: siteUrl + "search/getmodelTemplate",
      data: {
         action: 'relatedRecords',
         method: 'tagRecord',
         id: xrId,
         modelType: 'myOptionsModalExerciseRecord',
         editFlag: true
      },
      success: function(content) {
         $('#myOptionsModalExerciseRecord').html(content);
         $('#myOptionsModalExerciseRecord').modal();
      }
   });
}

function checkTitleExist(selector) {
   if ($('div#itemsetnew_' + selector).length) {
      curOrder = $('#goal_order_new_' + selector).val();
      $('input.seq_order_up').each(function(i, field) {
         if (curOrder < field.value) {
            inputId = $(this).attr('id');
            $('input#' + inputId).val(field.value - 1);
         }
      });
      $('div#itemsetnew_' + selector).parent('li').remove();
      $('#s_row_count').val($('#scrollablediv-len li').length);
      enableButtons();
   }
	return false;
}
$(document).ready(function(){
	$('div a.datadetail').click(function(e){
		if($('.editmode').hasClass('hide')) changeTosaveIcon();
		e.stopPropagation();
		var selectorId = $(this).parentNth(9).attr('data-id');
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