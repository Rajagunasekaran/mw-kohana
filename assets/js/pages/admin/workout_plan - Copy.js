$(document).ready(function() {
   $('.checkboxchoosen').hide();
   $(".listwkouts").show();
   $('.checkhidden').attr('checked', false);
   $('.item_workout_click').prop('onclick', null).off('click');
});

function choose_makesamplewkout(arg, foldid = 0) {
   switch (arg) {
      case "mywkout":
         break;
      case "mysharedwkout":
         break;
      default:
         $("#choose_sample").modal("show");
         return false;
         break;
   }
   //$("#f_method").val(arg);
   $.ajax({
      url: siteUrl + "workout/getwkouts",
      method: 'post',
      data: {
         action: arg,
         foldid: foldid
      },
      success: function(content) {
         //alert(content)
         if (content) {
            $('#listwkouts').modal("show");
            $('.getwkoutlists').html(content);
            $('#listwkouts').modal();
         } else {
            $('#listwkouts').modal("hide");
            $('.getwkoutlists').html("<center>No Data Found</center>");
         }
      }
   });
}

function checklist() {
   var method = $("#f_method_action").val();
   var val = [];
   $(':checkbox:checked').each(function(i) {
      val[i] = $(this).val();
   });
   if (val.length == 0) {
      alert("Please select workouts...!");
      return false;
   }
   createsample(val, method);
}

function createsample(wkoutid, method) {
   var r = confirm("Are you confirm copy to sample workout?");
   if (r) {
      $.ajax({
         url: siteUrl + "workout/cpytosample",
         type: 'POST',
         data: {
            workouts: wkoutid,
            f_method: method
         },
         success: function(data) {
            $('#listwkouts').modal("hide");
            $("#choose_sample").modal("hide");
            window.location.href = siteUrl + "workout/sample";
         }
      });
   }
}

function changeTosaveIcon() {};

function createFolderModel(fid, method, foldid) {
   $('#FolderModal').html();
   $.ajax({
      url: siteUrl_frontend + "/search/getmodelTemplate",
      data: {
         action: 'workoutFolder',
         method: method, // on donne la chaîne de caractère tapée dans le champ de recherche
         id: fid,
         foldid: foldid,
         fromAdmin: true,
      },
      success: function(content) {
         $('#FolderModal').html(content);
         $('#FolderModal').modal();
      }
   });
}

function getworkoutpreview(wkoutId) {
   $('#myModal').html();
   $.ajax({
      url: siteUrl_frontend + "/search/getmodelTemplate",
      data: {
         action: 'previewworkout',
         method: 'preview', // on donne la chaîne de caractère tapée dans le champ de recherche
         id: wkoutId,
         foldid: '0',
         fromAdmin: true,
      },
      success: function(content) {
         $('#myModal').html(content);
         $('#myModal').modal();
      }
   });
}

function getExerciseSetpreview(exerciseSetId, wkoutId, selector) {
   $('#FolderModal').html();
   $.ajax({
      url: siteUrl_frontend + "/search/getmodelTemplate",
      data: {
         action: 'previewExercise',
         method: 'preview', // on donne la chaîne de caractère tapée dans le champ de recherche
         id: exerciseSetId,
         foldid: wkoutId,
         fromAdmin: true,
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
      url: siteUrl_frontend + "/search/getmodelTemplate",
      data: {
         action: 'previewExerciseOfDay',
         method: 'preview', // on donne la chaîne de caractère tapée dans le champ de recherche
         id: exerciseId,
         foldid: wkoutId,
         fromAdmin: true,
      },
      success: function(content) {
         $('#FolderModal').html(content);
         $('#FolderModal').modal();
      }
   });
}

function createNewworkout() {
   $('#myModal').html();
   $.ajax({
      url: siteUrl_frontend + "/search/getmodelTemplate",
      data: {
         action: 'createNewworkout',
         method: 'add', // on donne la chaîne de caractère tapée dans le champ de recherche
         id: 0,
         foldid: 0,
         fromAdmin: true,
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
   $('#wrkoutcolor').val(selectedid);
   $('#wrkoutcolortxt').val(selectedclr);
}

function editWorkoutRecord(elem) {
   $('#FolderModal').html('');
   var goalOrder = '';
   var datavaljson = '';
   if ($('div#itemsetnew_' + elem).length) {
      var goal_id = img_url = '';
      if ($('div#itemsetnew_' + elem + " .activelinkpopup").attr("disabled")) return false;
      var goalOrder = elem.split('_')[1];
      var dataForm = $('div#itemsetnew_' + elem + ' input').serializeArray();
      img_url = '';
      if ($('div#itemsetnew_' + elem + ' .navimage img').length) {
         if ($('div#itemsetnew_' + elem + ' .navimage img').attr('src') != '') {
            var img_url = $('div#itemsetnew_' + elem + ' .navimage img').attr('src').replace(siteUrl_frontend, '');
            img_url = img_url.replace('../../../', '');
         }
      }
      elem = '';
   }
   var datavaljson = getInputDetailsByForm(dataForm, img_url, goal_id, 1);
   $.ajax({
      url: siteUrl_frontend + "/search/getmodelTemplate",
      data: {
         action: 'createExercise',
         method: 'create',
         id: '0',
         foldid: '0',
         goalOrder: goalOrder,
         dataval: datavaljson,
         fromAdmin: true,
      },
      success: function(content) {
         $('#FolderModal').html(content);
         $('#FolderModal').modal();
      }
   });
}

function createworkout(elem) {
   $('#s_row_count').val(elem.id);
   $('#FolderModal').html();
   $.ajax({
      url: siteUrl_frontend + "/search/getmodelTemplate",
      data: {
         action: 'createExercise',
         method: 'create', // on donne la chaîne de caractère tapée dans le champ de recherche
         id: $('#wkout_id').val(),
         foldid: '0',
         fromAdmin: true,
      },
      success: function(content) {
         $('#FolderModal').html(content);
         $('#FolderModal').modal();
      }
   });
}

function closeModelwindow(myModel) {
   if (typeof(myModel) == 'undefined' || myModel.trim() == '') myModel = 'myModal';
   $('#' + myModel).modal('hide');
   if (myModel == 'exerciselib-model') {
      if ($('#exercise_unit').val() == '0' || $('#exercise_unit').val() == '') {
         $('#exerciselib').bootstrapSwitch('state', false);
      }
   } else $('#' + myModel).html('');
}

function addnewExercise(elem) {
   $('.errormsg').hide();
   var modaldata = $('#createExercise').serializeArray();
   var allowCls = false;
   var exerciseUnit = '';
   $(modaldata).each(function(i, field) {
      //console.log(field.name+'====>'+field.value+'====');
      if (field.name == 'exercise_title_hidden') {
         if (field.value != "") {
            $('#itemsetnew_' + elem).find('.navimgdet1').html('<b>' + field.value + '</b>');
            allowCls = true;
         } else {
            $('.errormsg').text('Exercise Title not empty').removeClass('hide').show();
            return false;
         }
      }
      if (field.name == 'exercise_unit_hidden' && field.value != "") {
         exerciseUnit = field.value;
      }
      newvariable = field.name.replace("_hidden", "");
      $('#' + newvariable + '_new_' + elem).val(field.value);
      //console.log('==========>'+newvariable);
      var updatedText = '';
      if ($('#itemsetnew_' + elem + ' a.' + newvariable + '_div').length && $('#createExercise span.' + newvariable).length) {
         updatedText = $('#createExercise span.' + newvariable).html();
         if (newvariable == 'exercise_rest' && updatedText.trim() != '') $('#itemsetnew_' + elem + ' a.' + newvariable + '_div').html(updatedText + ' rest');
         else $('#itemsetnew_' + elem + ' a.' + newvariable + '_div').html(updatedText);
      }
   });
   if (exerciseUnit.trim() != '0') {
      if ($('#createExercise span#exerciselibimg img').length && $('#createExercise span#exerciselibimg img').attr('src') != '') $('#itemsetnew_' + elem + ' .navimage').html('<img width="50px;" src="' + $('#createExercise span#exerciselibimg img').attr('src') + '"  class="img-responsive pointers">');
      else $('#itemsetnew_' + elem + ' .navimage').html('<i class="fa fa-file-image-o pointers" style="font-size:50px;">');
      $('#itemsetnew_' + elem + ' .navimage').attr("onclick", "getTemplateOfExerciseRecordAction('" + exerciseUnit + "',this);");
   } else {
      $('#itemsetnew_' + elem + ' .navimage').html('<i class="fa fa-pencil-square" style="font-size:50px;">');
   }
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
      $('#FolderModal').modal('hide');
      $('#goal_order_' + elem).val(0);
      $('i.listoptionpoppopup ').removeClass('hide');
   }
   return true;
};

function addnewSet() {
   var last = $('#s_row_count_xr').val();
   if ($('div#itemsetnew_0_' + last).find('.navimgdet1').text() == 'Click_to_Edit') {
      alert('Please fill the above empty set and then try to add new set.');
      return false;
   }
   var count = parseInt(last) + 1;
   if ($('div#itemsetnew_0_' + count).find('.navimgdet1').text() != 'Click_to_Edit') {
      $('#scrollablediv-len ul').append('<li data-id="new_0_' + count + '" data-module="item_set_new" class="bgC4 item_add_wkout_noclick" id="itemSetnew_0_0_' + count + '"><div id="itemsetnew_0_' + count + '" class="row createworkout"><input type="hidden" value="' + count + '" class="seq_order_up" name="goal_order_new[]" id="goal_order_new_0_' + count + '"><input type="hidden" value="0" name="goal_remove_new[]" id="goal_remove_new_0_' + count + '"><div class="mobpadding"><div class="border full"><div style="display:none;" class="checkboxchoosen popupchoosen col-xs-2"><div style="font-size:20px;" class="checkboxcolor"><label><input type="checkbox" name="exercisesets[]" onclick="enablePopupButtons();" value="new_0_' + count + '" data-ajax="false" data-role="none" class="checkhiddenpopup"><span class="cr checkbox-circle"><i class="cr-icon fa fa-check"></i></span></label></div></div><div class="col-xs-8 navdescrip"><div class="col-xs-4 activelinkpopup navimage"><i style="font-size:50px;" class="fa fa-pencil-square"></i></div><div style="height:50px" class="pointers activelinkpopup datacol" onclick="editWorkoutRecord(' + "'0_" + count + "'" + ');"><div class="activelinkpopup navimagedetails"><div class="navimgdet1"><b>Click_to_Edit</b></div><div class="navimgdet2"><a class="datadetail exercise_time_div" href="javascript:void(0);" data-role="none" data-ajax="false"></a><a class="datadetail exercise_distance_div" href="javascript:void(0);" data-role="none" data-ajax="false"></a><a class="datadetail exercise_repetitions_div" href="javascript:void(0);" data-role="none" data-ajax="false"></a><a class="datadetail exercise_resistance_div" href="javascript:void(0);" data-role="none" data-ajax="false"></a></div><div class="navimgdet3"><a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail exercise_rate_div"></a>&nbsp;<a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail exercise_angle_div"></a>&nbsp;<a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail exercise_innerdrive_div"></a>&nbsp;<a data-ajax="false" data-role="none" href="javascript:void(0);" class="datadetail exercise_rest_div"></a></div><div class="navimgdet4"></div></div></div></div><div class="col-xs-2 navbarmenu"><a data-ajax="false" class="pointers editchoosenIconTwo editchoosenIconTwoPopup hide" href="javascript:void(0);"><i class="fa fa-bars panel-draggable" style="font-size:25px;"></i></a><i class="fa fa-ellipsis-h iconsize listoptionpoppopup" onclick="getTemplateOfExerciseSetAction(' + "'0_" + count + "','link'" + ');"></i><input type="hidden" value="" name="exercise_title_new[]" id="exercise_title_new_0_' + count + '"><input type="hidden" value="0" name="exercise_unit_new[]" id="exercise_unit_new_0_' + count + '"><input type="hidden" value="" name="exercise_resistance_new[]" id="exercise_resistance_new_0_' + count + '"><input type="hidden" value="" name="exercise_unit_resistance_new[]" id="exercise_unit_resistance_new_0_' + count + '"><input type="hidden" value="" name="exercise_repetitions_new[]" id="exercise_repetitions_new_0_' + count + '"><input type="hidden" value="" name="exercise_time_new[]" id="exercise_time_new_0_' + count + '"><input type="hidden" value="" name="exercise_distance_new[]" id="exercise_distance_new_0_' + count + '"><input type="hidden" value="" name="exercise_unit_distance_new[]" id="exercise_unit_distance_new_0_' + count + '"><input type="hidden" value="" name="exercise_rate_new[]" id="exercise_rate_new_0_' + count + '"><input type="hidden" value="" name="exercise_unit_rate_new[]" id="exercise_unit_rate_new_0_' + count + '"><input type="hidden" value="" name="exercise_innerdrive_new[]" id="exercise_innerdrive_new_0_' + count + '"><input type="hidden" value="" name="exercise_angle_new[]" id="exercise_angle_new_0_' + count + '"><input type="hidden" value="" name="exercise_unit_angle_new[]" id="exercise_unit_angle_new_0_' + count + '"><input type="hidden" value="" name="exercise_rest_new[]" id="exercise_rest_new_0_' + count + '"><input type="hidden" value="" name="exercise_remark_new[]" id="exercise_remark_new_0_' + count + '"><input type="hidden" value="" name="primary_time_new[]" class="exercise_priority_hidden" id="primary_time_new_0_' + count + '"><input type="hidden" value="" name="primary_dist_new[]" class="exercise_priority_hidden" id="primary_dist_new_0_' + count + '"><input type="hidden" value="" name="primary_reps_new[]" class="exercise_priority_hidden" id="primary_reps_new_0_' + count + '"><input type="hidden" value="" name="primary_resist_new[]" class="exercise_priority_hidden" id="primary_resist_new_0_' + count + '"><input type="hidden" value="" name="primary_rate_new[]" class="exercise_priority_hidden" id="primary_rate_new_0_' + count + '"><input type="hidden" value="" name="primary_angle_new[]" class="exercise_priority_hidden" id="primary_angle_new_0_' + count + '"><input type="hidden" value="" name="primary_rest_new[]" class="exercise_priority_hidden" id="primary_rest_new_0_' + count + '"><input type="hidden" value="" name="primary_int_new[]" class="exercise_priority_hidden" id="primary_int_new_0_' + count + '"></div></div></div></div></li>');
   } else {
      alert('Please fill the above empty set and then try to add new set.');
   }
   if (count > 3) $('#scrollablediv-len').addClass('scrollablediv');
   $('#s_row_count_xr').val(count);
   editWorkoutRecord('0_' + count);
}

function getpopupexercisesetTemplate(thisobj, wkoutId, exerciseId, type) {
   if (!$(thisobj).hasClass('activateLink')) {
      $('#mypopupModal').html();
      $.ajax({
         url: siteUrl_frontend + "/search/getmodelTemplate",
         data: {
            action: 'workoutExercise',
            method: type, // on donne la chaîne de caractère tapée dans le champ de recherche
            id: wkoutId,
            foldid: exerciseId,
            modelType: 'mypopupModal',
            fromAdmin: true,
         },
         success: function(content) {
            $('#mypopupModal').html(content);
            $('.checkboxdrag[type="checkbox"]').bootstrapSwitch('size', 'small');
            $('.checkboxdrag[type="checkbox"]').bootstrapSwitch('onText', ' ');
            $('.checkboxdrag[type="checkbox"]').bootstrapSwitch('offText', ' ');
            $('#mypopupModal').modal();
         }
      });
   }
}

function insertExtraToParentHidden(Model) {
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
      //console.log(field.name+'=='+field.value);
      if (field.value == 'on' || field.value == 'off' || field.name.indexOf("_unit_") == 8) {
         if (field.name != 'exerciselib' && field.name != 'innerdrive' && field.name.indexOf("_unit_") != 8) {
            primaryField = field.name.replace('_hidden', '');
            if (field.value.trim() == 'on') {
               ashstrick = '<span class="ashstrick">*</span> ';
            } else {
               ashstrick = '';
            }
         }
         //console.log(primaryField);
      } else {
         if (field.name != 'exercise_unit' && (field.value.trim() == 0 || field.value.trim() == '' || field.value.trim() == '00:00:00' || field.value.trim() == "00:00")) {
            field.value = ashstrick = oldData = '';
         }
         var checkPresentdiv = false;
         if (field.name.indexOf("exercise_") != -1) {
            var existUnit = field.name.replace('exercise_', 'exercise_unit_');
            checkPresentdiv = $('#workoutexercise select#' + existUnit).length;
         }
         //console.log(field.name.indexOf("exercise_")+'====>'+field.name+'===>'+checkPresentdiv);
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
            } else if (field.name == 'exercise_unit' && field.value == 0) {
               if (field.value.trim() == 0) {
                  $('#exerciselibimg').empty();
                  $('#exerciselibimg').append('<i class="fa fa-pencil-square datacol" style="font-size:50px;">');
               }
               $('#exercise_unit_hidden').val(field.value);
            } else if (field.name == 'exercise_repetitions') {
               if (ashstrick != '') $('span .ashstrick').remove();
               if (field.value != '') $('.' + field.name).html(ashstrick + field.value + ' reps');
               else $('.' + field.name).html('');
            } else if (field.name == 'exercise_innerdrive') {
               if (ashstrick != '') $('span .ashstrick').remove();
               if (field.value != '') {
                  matchesval = document.getElementById("innerdrive").options[field.value].text;
                  if (matchesval != 'Select') {
                     var regExp = /\(([^)]+)\)/;
                     var matches = regExp.exec(matchesval);
                     $('.' + field.name).html(ashstrick + matches[1] + ' Int');
                  } else $('.' + field.name).html('');
               } else $('.' + field.name).html('');
            } else {
               if (ashstrick != '') $('span .ashstrick').remove();
               $('.' + field.name).html(ashstrick + field.value);
            }
            if ($('.' + field.name + '_hidden')) {
               if (field.value != '' && ashstrick != '') {
                  if (primaryField != '' && $('#primary_' + primaryField)) {
                     $('.exercise_priority_hidden').val('0');
                     $('#primary_' + primaryField).val(1);
                  }
               } else {
                  if ($('#primary_' + primaryField)) $('#primary_' + primaryField).val(0);
               }
               $('.' + field.name + '_hidden').val(field.value);
            }
         } else {
            //console.log('else'+ashstrick);
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
                  if (primaryField != '' && $('#primary_' + primaryField)) {
                     $('.exercise_priority_hidden').val('0');
                     $('#primary_' + primaryField).val(1);
                  }
               } else {
                  if ($('#primary_' + primaryField)) $('#primary_' + primaryField).val(0);
               }
               if (ashstrick != '') $('span .ashstrick').remove();
               $('.' + field.name).html(ashstrick + field.value);
               $('.' + field.name + '_hidden').val(field.value);
               $('.' + existUnit + '_hidden').val(unit_value);
               var appendId = field.name.replace('unit_', '');
               $('.' + appendId).append(' ' + unit_val);
               return true;
            }
         }
      }
   });
   if (trueFlag) {
      $('#' + Model).modal('hide');
   }
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
   } else if ($('div#itemsetnew_0_1').find('.navimgdet1').text() == 'Click_to_Edit') {
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
      url: siteUrl_frontend + "/search/getmodelTemplate",
      data: {
         action: 'workoutColor',
         method: '', // on donne la chaîne de caractère tapée dans le champ de recherche
         id: '',
         foldid: '',
         dataval: dataval,
         fromAdmin: true,
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
      console.log(selectedclr);
      if (selectedclr != 'wrkoutcolor') $('#wrkoutcolortext').removeClass(selectedclr).addClass($('#wrkoutcolortxt').val());
      else $('#wrkoutcolortext').addClass('wrkoutcolor').addClass($('#wrkoutcolortxt').val());
      $('.colormodelpopup').removeClass('hide');
      closeModelwindow('FolderModal');
   }
}

function fixToolTipColor() {
   //grab the bg color from the tooltip content - set top border of pointer to same
   $('.ui-tooltip-pointer-down-inner').each(function() {
      var bWidth = $('.ui-tooltip-pointer-down-inner').css('borderTopWidth');
      var bColor = $(this).parents('.ui-slider-tooltip').css('backgroundColor')
      $(this).css('border-top', bWidth + ' solid ' + bColor);
   });
}

function getexercisesetTemplateAjaxEdit(wkoutId, exerciseId, type) {
   $('#myOptionsModalAjax').html();
   var dataForm = $('form#createExercise input').serializeArray();
   var goal_id = $('input#goal_id_hidden').val();
   var datavaljson = getInputDetailsByForm(dataForm, '', goal_id, 2);
   $.ajax({
      url: siteUrl_frontend + "/search/getmodelTemplate",
      data: {
         action: 'workoutExercise',
         method: type, // on donne la chaîne de caractère tapée dans le champ de recherche
         id: 0,
         foldid: 0,
         goalOrder: exerciseId,
         modelType: 'myOptionsModalAjax',
         fromAdmin: true,
         dataval: datavaljson,
      },
      success: function(content) {
         $('#myOptionsModalAjax').html(content);
         $('.checkboxdrag[type="checkbox"]').bootstrapSwitch('size', 'small');
         $('.checkboxdrag[type="checkbox"]').bootstrapSwitch('onText', ' ');
         $('.checkboxdrag[type="checkbox"]').bootstrapSwitch('offText', ' ');
         $('#myOptionsModalAjax').modal();
      }
   });
}

function getTemplateOfWorkoutAction(wkoutId, wksid) {
   $('#myModal').html('');
   $.ajax({
      url: siteUrl_frontend + "/search/getmodelTemplate",
      data: {
         action: 'workoutaction',
         method: 'action', // on donne la chaîne de caractère tapée dans le champ de recherche
         id: wkoutId,
         foldid: wksid,
         type: 'workoutfolder',
         fromAdmin: true,
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
      url: siteUrl_frontend + "/search/getmodelTemplate",
      data: {
         action: 'addAssignWorkouts',
         method: 'action', // on donne la chaîne de caractère tapée dans le champ de recherche
         id: wkoutid,
         date: '2016-03-14',
         type: 'wkout',
         fromAdmin: true,
      },
      success: function(content) {
         $('#myModal').html(content);
         $('#myModal').modal();
      }
   });
}

function addLogWorkouts(wkoutid) {
   $('#myModal').html();
   $.ajax({
      url: siteUrl_frontend + "/search/getmodelTemplate",
      data: {
         action: 'workoutLogConfirm',
         method: 'action', // on donne la chaîne de caractère tapée dans le champ de recherche
         id: wkoutid,
         type: 'wkoutlog',
         fromAdmin: true,
      },
      success: function(content) {
         $('#myModal').html(content);
         $('#myModal').modal();
      }
   });
}
(function($) {
   $.fn.parentNth = function(n) {
      var el = $(this);
      for (var i = 0; i < n; i++) el = el.parent();
      return el;
   };
})(jQuery);

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

function editExercistSets(selector) {
   $(selector).addClass('hide');
   $('#refreshpopup').removeClass('hide');
   $('#createwkoutpopup').addClass('hide');
   $('.optionmenupopup div.allowhide').removeClass('hide');
   $('.popupchoosen').show();
   $('a.editchoosenIconTwoPopup').removeClass('hide');
   $('i.listoptionpoppopup').addClass('hide');
   $('.activelinkpopup').attr('disabled', 'disabled');
   var group = $("#sTree3").sortable({
      placeholder: '<li class="sortableListsHint" style="display:block;width:100%;border:1px solid #ededed;height:50px;background-color:#ededed; border-radius: 3px;"></li>',
      group: 'serialization',
      delay: 500,
      onDrop: function($item, container, _super) {
         var data = group.sortable("serialize").get();
         _super($item, container);
         console.log(data);
         var z = 1;
         for (var j = 0; j < data.length; j++) {
            //console.log(data[j]);
            for (var k = 0; k < data[j].length; k++) {
               x = k + 1;
               var liTagDataid = data[j][k].id;
               console.log(liTagDataid + "=====>" + data[j][k].id);
               if (data[j][k].module == 'item_set') {
                  $('#goal_order_' + liTagDataid).val(x);
               } else {
                  $('#goal_order_' + liTagDataid).val(x);
               }
            }
         }
      }
   });
   return false;
}

function editWorkoutPlans(selector) {
   $('.editmode').addClass('hide');
   $(selector).addClass('hide');
   $('#refresh').removeClass('hide');
   $('.createwkout').addClass('hide');
   $('.optionmenu div.allowhide').removeClass('hide');
   $('.checkboxchoosen').show();
   $('.editchoosenIconTwo').removeClass('hide');
   $('.editchoosenIconOne').addClass('hide');
   $('.activelink').attr('disabled', 'disabled');
   $('.sTreeBase').attr('id', 'sTree2');
   var group = $("#sTree2").sortable({
      placeholder: '<li class="sortableListsHint" style="display:block;width:100%;border:1px solid #ededed;height:50px;background-color:#ededed; border-radius: 3px;"></li>',
      group: 'serialization',
      delay: 500,
      onDrop: function($item, container, _super) {
         var data = group.sortable("serialize").get();
         _super($item, container);
         if ($('li').parent("ul").hasClass("bgC4_ul")) {
            $('ul.bgC4_ul').hide();
            $('ul.bgC4_ul li').remove();
         }
         if ($('li').parent("ul").hasClass("bgC4_ul_parent")) {
            $('ul.bgC4_ul_parent').hide();
            $('ul.bgC4_ul_parent li').remove();
         }
         //console.log(data);
         $.ajax({
            url: siteUrl_frontend + "/ajax/wkoutorder",
            cache: false,
            type: "POST",
            data: {
               action: 'seq_order',
               data: data,
               parentid: 0
            },
            success: function(content) {
               console.log(content);
               var parsed = JSON.parse(content);
               var arr = [];
               for (var x in parsed) {
                  $('.' + x).text(parsed[x]);
               }
               $('.jsmessage').html('Updated succesfully!!!').show();
            }
         });
      }
   });
   return false;
}

function editWorkout(selector) {
   $('.editmode').removeClass('hide');
   $('.editmodesets').addClass('hide');
   $(selector).addClass('hide');
   $('#editxr').removeClass('hide');
   $('.optionmenu div.allowhide').addClass('hide');
   $('.createwkout').removeClass('hide');
   $('.checkboxchoosen').hide();
   $('.editchoosenIconTwo').addClass('hide');
   $('.editchoosenIconOne').removeClass('hide');
   $('.activelink').removeAttr('disabled');
   $('input.checkhidden:checkbox').attr('checked', false);
   return false;
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
   var dataval = [];
   dataval['goal_time_hh'] = '';
   dataval['goal_time_mm'] = '';
   dataval['goal_time_ss'] = '';
   dataval['goal_rest_mm'] = '';
   dataval['goal_rest_ss'] = '';
   dataval['img_url'] = img_url;
   dataval['goal_id'] = elem;
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
      'primary_int': "primary_int"
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
      //console.log(field);
      //console.log(field.name+'===='+field.value);
      for (var key in requArr) {
         if (field.name.indexOf(key) >= 0) {
            //console.log("key " + key + " has value " + requArr[key]);
            if (requArr[key] == 'goal_time') {
               inputTimeArr = field.value.split(":");
               dataval['goal_time_hh'] = inputTimeArr[0];
               dataval['goal_time_mm'] = inputTimeArr[1];
               dataval['goal_time_ss'] = inputTimeArr[2];
            } else if (requArr[key] == 'goal_rest') {
               inputRestArr = field.value.split(":");
               dataval['goal_rest_mm'] = inputRestArr[0];
               dataval['goal_rest_ss'] = inputRestArr[1];
            } else {
               dataval[requArr[key]] = field.value;
            }
            delete requArr[key];
         }
      }
   });
   return convArrToObj(dataval);
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
      url: siteUrl_frontend + "/search/getmodelTemplate",
      data: {
         action: 'relatedRecords',
         method: 'previewimage', // on donne la chaîne de caractère tapée dans le champ de recherche
         id: xrid,
         modelType: modalName,
         fromAdmin: true,
      },
      success: function(content) {
         $('#' + modalName).html(content);
         $('#' + modalName).modal();
      }
   });
}

function getRelatedRecords(xrid) {
   if ($('#myOptionsModalExerciseRecord_more').length) modalName = 'myOptionsModalExerciseRecord_more';
   else modalName = 'myOptionsModalExerciseRecord';
   $('#' + modalName).html();
   $.ajax({
      url: siteUrl_frontend + "/search/getmodelTemplate",
      data: {
         action: 'relatedRecords',
         method: 'relatedRecords', // on donne la chaîne de caractère tapée dans le champ de recherche
         id: xrid,
         modelType: modalName,
         fromAdmin: true,
      },
      success: function(content) {
         $('#' + modalName).html(content);
         $('#' + modalName).modal();
      }
   });
}

function getRelatedRecordsMore(xrid, start, lim) {
   $.ajax({
      url: siteUrl_frontend + "/search/getRelatedXrRecordsMore",
      data: {
         id: xrid,
         start: start,
         lim: lim
      },
      success: function(content) {
         var JSONArray = $.parseJSON(content);
         var response = '';
         if (JSONArray.length > 0) {
            for (var i = 0; i < JSONArray.length; i++) {
               //alert(JSONArray[i].unit_id)
               response += '<div class="row"><div class="mobpadding"><div class="border full">';
               response += '<div class="col-xs-3 ">';
               if (JSONArray[i].img) {
                  response += '<img width="50px;" id="exerciselibimg" class="img-thumbnail" style="cursor:pointer;';
                  response += '" src=\'../../../' + JSONArray[i].img + '\'';
                  response += '/>';
               } else {
                  response += '<i style="font-size:50px;" class="fa fa-file-image-o datacol"></i>';
               }
               response += '</div>';
               response += '<div class="col-xs-6 "><b>' + JSONArray[i].title + '</b></div>';
               response += '<div class="col-xs-5"><a href="javascript:void(0);"><i class="fa fa-ellipsis-h iconsize"></i></a></div>';
               response += '</div>';
               response += '</div></div>';
            }
            start = start + lim;
            response += '<div id="view_more" class="row"><div class="mobpadding"><div class="border full">';
            response += '<div class="col-xs-12"><center><a data-role="none" data-ajax="false" class="pointers" onclick="getRelatedRecordsMore(' + xrid + ',' + start + ',' + lim + ')"><i class="fa fa-chevron-down"></i> Show More Records</a></center></div></div></div></div>';
            $("#view_more").remove();
            $("#relatedexc").append(response).css('overflow-y', 'scroll');
         } else {
            $("#view_more").remove();
         }
      }
   });
}

function getTemplateOfXrRecordAction(exerciseSetId) {
   $('#myOptionsModalExerciseRecord_option').html();
   $.ajax({
      url: siteUrl_frontend + "/search/getmodelTemplate",
      data: {
         action: 'exerciserecordaction',
         method: 'action',
         id: $('#wkout_id').val(),
         foldid: exerciseSetId,
         modelType: 'myOptionsModalExerciseRecord_option',
         fromAdmin: true,
      },
      success: function(content) {
         $('#myOptionsModalExerciseRecord_option').html(content);
         $('#myOptionsModalExerciseRecord_option').modal();
      }
   });
}

function getTemplateOfExerciseRecordAction(exerciseSetId) {
   $('#myOptionsModal').html();
   $.ajax({
      url: siteUrl_frontend + "/search/getmodelTemplate",
      data: {
         action: 'exerciserecordaction',
         method: 'action',
         id: $('#wkout_id').val(),
         foldid: exerciseSetId,
         modelType: 'myOptionsModal',
         fromAdmin: true,
      },
      success: function(content) {
         $('#myOptionsModal').html(content);
         $('#myOptionsModal').modal();
      }
   });
}

function deleteExerciseSet() {
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

function createCopyExerciseSet(selector, move) {
   var wkoutid = 0;
   if (selector == '') {
      $("input.checkhiddenpopup:checkbox:checked").each(function() {
         inputdata = $(this).val();
         if ($('#scrollablediv-len li')) var last = $('#scrollablediv-len li').length;
         else var last = 0;
         if ($('div#' + last).find('.navimgdet1').text() == 'Click_to_Edit') {
            alert('Please fill the above empty set and then try to add new set.');
            return false;
         }
         var count = parseInt(last) + 1;
         var goalorder = $('#scrollablediv-len li').length + 1;
         $('#s_row_count_xr').val(count);
         var inlineString = $(this).parentNth(6).html();
         inlineString = inlineString.replace(/col-xs-2/g, 'col-xs-aa');
         inlineString = inlineString.replace(/col-xs-8/g, 'col-xs-bb');
         inlineString = inlineString.replace(/col-xs-4/g, 'col-xs-cc');
         var selectorId = $(this).parentNth(6).attr('id');
         //console.log(selectorId);
         var dataForm = $('#' + selectorId + ' .navbarmenu input').serializeArray();
         //console.log(dataForm);
         var titleName = '';
         $(dataForm).each(function(i, field) {
            inputNameArr = field.name.split("[")[0];
            if (inputNameArr == "exercise_title_new") {
               inputValue = field.value + '_copy';
               titleName = inputValue;
            } else inputValue = field.value;
            inputName = inputNameArr + '_new[]';
            if (!isNaN(inputdata)) {
               var fieldname = escapeRegExp(field.name);
               var re3 = new RegExp(fieldname, 'g');
               inlineString = inlineString.replace(re3, inputName);
            } else {
               inputcurCountnew = selectorId.split('_')[1] + '_' + count;
               inputcurCount = selectorId.split('new_')[1];
               var inputcurCount = escapeRegExp(inputcurCount);
               var re2 = new RegExp(inputcurCount, 'g');
               inlineString = inlineString.replace(re2, inputcurCountnew);
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
            $('#scrollablediv-len ul').append('<li id="itemSetnew_' + wkoutid + '_0_' + count + '" class="bgC4 item_add_wkout_noclick" data-module="item_set_new" data-id="new_' + wkoutid + '_' + count + '"><div class="row createworkout" id="itemsetnew_' + wkoutid + '_' + count + '">' + inlineString + '</div></li>');
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
         $('#itemsetnew_' + wkoutid + '_' + count + ' .listoptionpoppoup').attr("onclick", "getTemplateOfExerciseSetAction('" + wkoutid + '_' + count + "','link');");
         $('.scrollablediv').scrollTop($('.scrollablediv').prop('scrollHeight'));
      });
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
         else var inputdata = 'new_' + selector.split('new_')[1];
         var current = $('div#itemset' + selector).parent('li').index();
      } else if ($('div#itemsetnew_' + selector).length) {
         var selectorDiv = $('div#itemsetnew_' + selector);
         var selectorId = 'div#itemsetnew_' + selector;
         var inputdata = 'new_' + selector.split('new_')[1];
         var current = $('div#itemsetnew_' + selector).parent('li').index();
      }
      var curCnt = current;
      var last = $('#scrollablediv-len li').length;
      if ($('div.navimgdet1').text() == 'Click_to_Edit') {
         alert('Please fill the above empty set and then try to add new set.');
         return false;
      }
      if (last == current && move == 'down') var count = current;
      else if (move == 'down') var count = curCnt + 1;
      else var count = curCnt;
      $('#s_row_count_xr').val(parseInt(last) + 1);
      //console.log(inputdata+'===>'+current+'=====>'+curCnt+'===>'+count+'===>'+selectorId);		
      var inlineString = $(selectorDiv).html();
      inlineString = inlineString.replace(/col-xs-2/gi, 'col-xs-aa');
      inlineString = inlineString.replace(/col-xs-8/gi, 'col-xs-bb');
      inlineString = inlineString.replace(/col-xs-4/gi, 'col-xs-cc');
      var dataForm = $(selectorId + ' .navbarmenu input').serializeArray();
      //console.log(dataForm);
      var titleName = '';
      var updatedCnt = count + 1;
      $(dataForm).each(function(i, field) {
         inputNameArr = field.name.split("[")[0];
         if (inputNameArr == 'exercise_title' || inputNameArr == "exercise_title_new") {
            inputValue = field.value + '_copy';
            titleName = field.value + '_copy';
         } else inputValue = field.value;
         inputName = inputNameArr + '_new[]';
         if (!isNaN(inputdata)) {
            var fieldname = escapeRegExp(field.name);
            var re3 = new RegExp(fieldname, 'g');
            inlineString = inlineString.replace(re3, inputName);
         } else {
            inputcurCountnew = selectorId.split('_')[1] + '_' + updatedCnt;
            inputcurCount = selectorId.split('new_')[1];
            var inputcurCount = escapeRegExp(inputcurCount);
            var re2 = new RegExp(inputcurCount, 'g');
            inlineString = inlineString.replace(re2, inputcurCountnew);
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
         if (move == 'down') $('#scrollablediv-len ul li:eq(' + current + ')').after('<li id="itemSetnew_' + wkoutid + '_0_' + updatedCnt + '" class="bgC4 item_add_wkout_noclick" data-module="item_set_new" data-id="new_' + wkoutid + '_' + updatedCnt + '"><div class="row createworkout" id="itemsetnew_' + wkoutid + '_' + updatedCnt + '">' + inlineString + '</div></li>');
         else $('#scrollablediv-len ul li:eq(' + count + ')').before('<li id="itemSetnew_' + wkoutid + '_0_' + updatedCnt + '" class="bgC4 item_add_wkout_noclick" data-module="item_set_new" data-id="new_' + wkoutid + '_' + updatedCnt + '"><div class="row createworkout" id="itemsetnew_' + wkoutid + '_' + updatedCnt + '">' + inlineString + '</div></li>');
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
      $('#itemsetnew_' + wkoutid + '_' + count + ' .listoptionpop').attr("onclick", "getTemplateOfExerciseSetAction('" + wkoutid + '_' + count + "','link');");
      var lists = $("#scrollablediv-len ul li");
      if (move == 'down') {
         currentval = current + 1;
      } else {
         currentval = count + 1;
      }
      var originalCnt = updatedCnt;
      //console.log(currentval+'==asdfasfd===>'+count+'==asfdsfd=>'+lists.length);
      if (currentval < lists.length)
         for (var i = currentval; i < lists.length; ++i) {
            var liTagCnt = i - 1;
            //console.log($(lists[i]));
            var selectorIdinner = $(lists[i]).attr('id');
            var xrId = $(lists[i]).attr('data-id');
            var inlineString = $(lists[i]).html();
            inlineString = inlineString.replace(/col-xs-2/gi, 'col-xs-aa');
            inlineString = inlineString.replace(/col-xs-8/gi, 'col-xs-bb');
            inlineString = inlineString.replace(/col-xs-4/gi, 'col-xs-cc');
            $('#' + selectorIdinner).remove();
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
               var xrIdnew = escapeRegExp(xrId.split('new_')[1]);
               var re2 = new RegExp(xrIdnew, 'g');
               inlineString = inlineString.replace(re2, xrIdval.split('new_')[1]);
               console.log(xrIdArr.length + "===>" + xrIdvalOrder + '===>' + xrId + '===>' + litagId + '===>' + updatedCnt);
            }
            //console.log('===>'+xrId+'===>'+litagId+'===>'+updatedCnt);
            var dataForm = $('#' + selectorIdinner + ' .navbarmenu input').serializeArray();
            //console.log(litagId);
            var titleNameCont = '';
            inlineString = inlineString.replace(/col-xs-aa/g, 'col-xs-2');
            inlineString = inlineString.replace(/col-xs-bb/g, 'col-xs-8');
            inlineString = inlineString.replace(/col-xs-cc/g, 'col-xs-4');
            if (selectorIdinner.indexOf("new_") < 0) $('#scrollablediv-len ul li:eq(' + liTagCnt + ')').after('<li id="itemSet_' + litagId + '" class="bgC4 item_add_wkout_noclick" data-module="item_set" data-id="' + xrIdval + '">' + inlineString + '</li>');
            else $('#scrollablediv-len ul li:eq(' + liTagCnt + ')').after('<li id="itemSet' + litagId + '" class="bgC4 item_add_wkout_noclick" data-module="item_set_new" data-id="' + xrIdval + '">' + inlineString + '</li>');
            if (currentval > 3 && !$('#scrollablediv-len').hasClass('scrollablediv')) {
               $('#scrollablediv-len').addClass('scrollablediv');
            }
            if ($('#scrollablediv-len li').length == '1') $('.sTreeBase').show();
            $('#goal_order_' + xrIdval).val(updatedCnt);
         }
      if (titleName != '') {
         //console.log('===========>'+$('#exercise_title_new_'+wkoutid+'_'+originalCnt).val());
         $('#exercise_title_new_' + wkoutid + '_' + originalCnt).val(titleName);
         $('#itemsetnew_' + wkoutid + '_' + originalCnt + ' div.navimgdet1').html('<b>' + titleName + '</b>');
         if (move != 'down') {
            var currentvalnew = currentval + 1;
            $('#exercise_title_new_' + wkoutid + '_' + currentvalnew).val(titleName.replace('_copy', ''));
            $('#itemsetnew_' + wkoutid + '_' + currentvalnew + ' div.navimgdet1').html('<b>' + titleName.replace('_copy', '') + '</b>');
         }
      }
      $('.scrollablediv').scrollTop($('.scrollablediv').prop('scrollHeight'));
   }
}

function escapeRegExp(stringToGoIntoTheRegex) {
   return stringToGoIntoTheRegex.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
}

function confirmPopup() {
   if (createworkoutSubmit()) {
      $('#myOptionsModal').html();
      $.ajax({
         url: siteUrl_frontend + "search/getmodelTemplate",
         data: {
            action: 'confirmWorkoutPopup',
            method: 'confirm', // on donne la chaîne de caractère tapée dans le champ de recherche
            id: '',
            foldid: '',
            modelType: 'myOptionsModal',
            fromAdmin: true,
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
   if (flag.trim() != '0') {
      $('#save_edit').val(flag);
      $('form#createNewworkout').submit();
   } else {
      window.location.reload();
   }
}

function getTemplateOfExerciseSetAction(exerciseSetId, link) {
   $('#FolderModal').html();
   var wkoutid = 0;
   var goalOrder = $('div#itemsetnew_' + wkoutid + '_' + exerciseSetId + ' input#goal_order_new_' + wkoutid + '_' + exerciseSetId).val();
   $.ajax({
      url: siteUrl_frontend + "/search/getmodelTemplate",
      data: {
         action: 'exercisesetaction',
         method: 'createNewWrkout',
         id: wkoutid,
         foldid: exerciseSetId,
         modelType: 'FolderModal',
         goalOrder: goalOrder,
         fromAdmin: true,
      },
      success: function(content) {
         $('#FolderModal').html(content);
         $('#FolderModal').modal();
      }
   });
}

function doDeleteProcess(type, selector) {
   var wkoutid = 0;
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
   if (inputField == 'exercise_time') $('#' + inputField).val('00:00:00');
   else if (inputField == 'exercise_rest') $('#' + inputField).val('00:00');
   else $('#' + inputField).val('');
   if ($('select.dropdown')) $('select.dropdown').val(0);
   $('.checkboxdrag').bootstrapSwitch('state', false)
}

function toggleDivTitle() {
   if ($('#expendeddiv').hasClass('fa-caret-up')) {
      $('#expendeddiv').removeClass('fa-caret-up');
      $('#expendeddiv').addClass('fa-caret-down');
      $("#expended").slideUp("slow", function() {
         /*if($("#scrollablediv-len"))
         	$("#scrollablediv-len").css('max-height','500px');*/
      });
   } else if ($('#expendeddiv').hasClass('fa-caret-down')) {
      $('#expendeddiv').removeClass('fa-caret-down');
      $('#expendeddiv').addClass('fa-caret-up');
      $("#expended").slideDown("slow", function() {
         /*if($("#scrollablediv-len"))
         	$("#scrollablediv-len").css('max-height','300px');*/
      });
   }
}