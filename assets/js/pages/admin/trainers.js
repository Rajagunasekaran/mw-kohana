$("#select-all").click(function() {
   $('input:checkbox').not(this).prop('checked', this.checked);
});


$(".subscribeselect").click(function() {
   if ($(".subscribeselect").length == $(".subscribeselect:checked").length) {
      $("#select-all").attr("checked", "checked");
   } else {
      $("#select-all").removeAttr("checked");
   }
});


if ($('.traineractions').length) {
   $(".traineractions").chosen({
      disable_search: true
   });
}

$(".checkselect").click(function(e) {
   var chk = $(this).closest("tr").find("input:checkbox").get(0);
   if (e.target != chk) {
      chk.checked = !chk.checked;
   }
});

$('.moteactions').on('change', function(e) {
	var val = $(this).val();
   var id = $(this).attr("id");
	$('.moteactions').val('').trigger('liszt:updated');
	switch (val) {
      case "shareworkout":
			var data = new Array();
         $(".subscribeselect").each(function() {
            if ($(this).prop('checked') == true) {
               var id = $(this).attr("id");
               data.push($(this).val());
            }
         });
         if (data.length > 0) {
            $("#shareModal").modal('show');
				$("#subscriber_id").val(data);
				$("select.wkout_id").select2();
         } else {
            alert("Please select subscribers")
         }
         break;
      case "tagusers":
         var data = new Array();
         $(".subscribeselect").each(function() {
            if ($(this).prop('checked') == true) {
               var id = $(this).attr("id");
               data.push($(this).val());
            }
         });
         if (data.length > 0) {
            taguser('multiple', data);
         } else {
            alert("Please select subscribers")
         }
         break;
      default:
         break;
   }
   $(".moteactions").val("").trigger('chosen:updated');
});

$('.subscriberaction').on('change', function(e) {
	var val = $(this).val();
   var id = $(this).attr("id");
	$(".subscriberaction").val("").trigger('chosen:updated');
	switch (val) {
      case "editprofile":
         window.location.href = siteUrl + "user/edit/" + id;
         break;
      case "taguser":
         taguser('single', id);
         break;
      case "sendemail":
         send_email('single', id);
         break;
      case "editstatus":
         changeuserstatus('single', id);
			break;
		case "shareworkout":
			$("#shareModal").modal('show');
			var data = new Array();
			data.push(id);
			$("#subscriber_id").val(data);
			$("select.wkout_id").val(data);
			$("select.wkout_id").select2();
			break;
      default:
         break;
   }
});

function send_email(type, userid) {
   $('#emailmodal').modal('show');
}

function taguser(type, userid) {
   $('#curid').val(userid);
   $('#tagmodal').modal('show');
   $.ajax({
      url: siteUrl + "ajax/getusertags",
      type: 'GET',
      dataType: 'json',
      data: {
         userid: userid
      },
      success: function(data) {
         if (data.success) {
            $('#tagnames').val(data.user_tags);
            tag_dropdown_user('#tagnames', data.tags);
         }
      }
   });
   //tag_dropdown_user('.tagnames', siteUrl+"ajax/getusertags",userid);
}

function changeuserstatus(type, userid) {
   $('#curid').val(userid);
   $('#statusmodal').modal('show');
   $.ajax({
      url: siteUrl + "ajax/getuserstatus",
      type: 'GET',
      dataType: 'json',
      data: {
         userid: userid
      },
      success: function(data) {
         if (data.success) {
            single_dropdown('#userstatus', data.user_status_all);
            $("#userstatus").select2("val", data.user_status);
         }
      }
   });
}
$(".addusertags").click(function() {
   var uid = $('#curid').val();
   var tags = $('#tagnames').val();
   var selecteddatas = $('#tagnames').select2('data');
   var tagstext = '';
   var a = [];
   $.each(selecteddatas, function(key, value) {
      a.push(value.text);
   });
   tagstext = a.join(', ');
   //$('#suscribeTable tr#row-' + uid).find(".tagsection").text(tagstext);
   $('#suscribeTable tr#row-' + uid).find(".tagsection").html('');
	createupdatetags(uid, tags);
	
});

function createupdatetags(uid, tags) {
   $.ajax({
      url: siteUrl + "ajax/UserAdduUdateTags",
      type: 'POST',
      dataType: 'json',
      data: {
         userid: uid,
         tags: tags
      },
      success: function(data) {
         if (data.success) {
				var JSONArray = data.tags;
            if (JSONArray.length > 0) {
               for (var i = 0; i < JSONArray.length; i++) {
                  $('#suscribeTable tr#row-' + JSONArray[i].user_id).find(".tagsection").html(JSONArray[i].tag_title);
               }
               $("select.subscriber_id").val(data);
               $("select.subscriber_id").select2();
            }
            $('#tagmodal').modal('hide');
         }
      }
   });
}
$(".changeuserstatus").click(function() {
   var uid = $('#curid').val();
   var userstatus = $('#userstatus').val();
   update_user_status(uid, userstatus);
});

function update_user_status(uid, status) {
   tagstext = $('#userstatus').select2('data').text;
   $.ajax({
      url: siteUrl + "ajax/UserUpdateStatus",
      type: 'POST',
      dataType: 'json',
      data: {
         userid: uid,
         status: status
      },
      success: function(data) {
         if (data.success) {
            $('#statusmodal').modal('hide');
            $('#suscribeTable tr#row-' + uid).find(".user-status").text(tagstext);
         }
      }
   });
}

function single_dropdown(element, datasource) {
   $(element).select2({
      createSearchChoice: function(term) {
         return {
            id: $.trim(term),
            text: $.trim(term)
         };
      },
      data: datasource
   });
}

function tag_dropdown_user(element, datasource) {
   $(element).select2({
      tags: true,
      /*minimumInputLength:1,			*/
      tokenSeparators: [','],
      createSearchChoice: function(term) {
         return {
            id: $.trim(term),
            text: $.trim(term)
         };
      },
      data: datasource,
      initSelection: function(element, callback) {
         var preselected_ids = extract_preselected_ids(element);
         var preselections = find_preselections(preselected_ids, datasource);
         callback(preselections);
      }
   });
}

function v(e) {
   return "<div>" + e.title + "</div>"
}

function m(e) {
   return e.title
}

function extract_preselected_ids(element) {
   var preselected_ids = [];
   var delimiter = ',';
   if (element.val()) {
      if (element.val().indexOf(delimiter) != -1) $.each(element.val().split(delimiter), function() {
         preselected_ids.push({
            id: this
         });
      });
      else preselected_ids.push({
         id: element.val()
      });
   }
   return preselected_ids;
};
// find all objects with the pre-selected IDs
// preselected_ids: array of IDs
function find_preselections(preselected_ids, datasource) {
   var pre_selections = []
   for (index in datasource)
      for (id_index in preselected_ids) {
         var objects = find_object_with_attr(datasource[index], {
            key: 'id',
            val: preselected_ids[id_index].id
         })
         if (objects.length > 0) pre_selections = pre_selections.concat(objects);
      }
   return pre_selections;
};

function find_object_with_attr(object, attr) {
   var objects = [];
   for (var index in object) {
      if (!object.hasOwnProperty(index)) // make sure object has a property. Otherwise, skip to next object.
         continue;
      if (object[index] && typeof object[index] == 'object') { // recursive call into children objects.
         objects = objects.concat(find_object_with_attr(object[index], attr));
      } else if (index == attr['key'] && object[attr['key']] == attr['val']) objects.push(object);
   }
   return objects;
}



function saveshare() {
   var wkout_id = $("#wkout_id").val();
   var subscriber_id = $("#subscriber_id").val();
   var message = $("#message").val();
   if (!wkout_id) {
      alert("please select any one workouts...")
      return false;
   }
   if (!subscriber_id) {
      alert("please select any one subscriber...")
      return false;
   }
   if (!message) {
      alert("please enter message...")
      return false;
   }
   $('#shareModal').modal('hide');
   $.ajax({
      url: siteUrl + "workout/saveshare",
      method: 'post',
      data: {
         wkout_id: wkout_id,
         subscriber_id: subscriber_id,
			param: "subscriber",
         message: message
      },
      success: function(content) {
         //$('.chosen-select option:selected').removeAttr("selected");
         //$('.chosen-select').trigger('chosen:updated');
         $("select.wkout_id").select2('val', ['All']);
         $("#message").val('');
         if ($(".subscribeselect").length == $(".subscribeselect:checked").length) {
            $("#select-all").attr("checked", "checked");
         } else {
            $("#select-all").removeAttr("checked");
         }
      }
   });
}