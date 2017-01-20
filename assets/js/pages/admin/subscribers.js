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


$(".checkselect").click(function(e) {
   var chk = $(this).closest("tr").find("input:checkbox").get(0);
   if (e.target != chk) {
      chk.checked = !chk.checked;
   }
});


 $("select.subscribername").select2();
 $("select.tag_id").select2();
$("select.gender").select2();


$(document).ready(function() {
	$('#tag_id').on('change', function(e) {
      var tags = $("#tag_id").val();
      //alert(tags)
      //SELECT * FROM `user_tags` WHERE `tag_id` IN ( 5, 7, 8 ) group by user_id
      $.ajax({
         url: siteUrl + "workout/gettaguser",
         method: 'post',
         data: {
            tag_id: tags
         },
         success: function(content) {
            //alert(content+"=====Result");
            var JSONArray = $.parseJSON(content);
            if (JSONArray.length > 0) {
               $("select.subscribername").select2('val', ['All']);
               var data = [];
               for (var i = 0; i < JSONArray.length; i++) {
                  id = JSONArray[i].user_id;
                  data.push(id);
               }
               $("select.subscribername").val(data);
               $("select.subscribername").select2();
            }
         }
      });
   });
});

$('.moteactions').on('change', function(e) {
	var val = $(this).val();
   var id = $(this).attr("id");
	$('.moteactions').val('').trigger('liszt:updated');
	
	switch (val) {
		case "addsubscriber":
			window.location.href= siteUrl+"user/create/register";
			break;
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
	  case "export" :
			$("#exportModal input#selected_roles").val($('#roleid').val());
		    $("#exportModal input#selected_ids").val('');
			$('div.response').html('');
			$("#exportModal").modal("show");
	  break;
	  case "exportselected" :
			$("#exportModal input#selected_roles").val($('#roleid').val());
			$('div.response').html('');
			$("#exportModal input#selected_ids").val('');
			var data = new Array();
			$("input.chkbox-item").each(function() {
				if ($(this).prop('checked') == true) {
					var id = $(this).attr("id");
					data.push($(this).val());
				}
			});
			if (data.length > 0) {
				$("#selected_ids").val(data);
				$("#exportModal").modal("show");
			}else{
				alert("Please select user(s)");
			}
	  break;
	  case "reportEmail":
	       $('#EmailModal').modal('toggle');
	  break;
	  case "Excel":
	     var roleid = $('#roleid').val();
		 location.href = siteUrl+"user/get_user_report_as_excel/"+roleid;
	  break;
	  case "PDF":
	     var roleid = $('#roleid').val();
		 window.open( siteUrl+"user/get_userlist_pdf/"+roleid, '_blank');
	  break;
	  case "sendemail":
			var checkedusers = new Array();
			if($('input[name="row_index[]"]').is(':checked')){
				$('input[name="row_index[]"]').each(function () {
					if(this.checked){
						checkedusers.push($(this).val());
					} 
				});	
				send_multiple_email(checkedusers);
			}else{
				alert('Please Select Anyone From List.');
			}
	  break;
      default:
         break;
   }
   $(".moteactions").val("");
});
function listexport(arg){
	var datauids = ($("#selected_ids").val()) ? $("#selected_ids").val() : '';
	var datarids = ($("#selected_roles").val()) ? $("#selected_roles").val() : '';
	data = datarids+'?uid='+datauids;
	if (arg=='pdf') {
		window.open(siteUrl + "user/get_userlist_pdf/"+data, '_blank');
	}else if (arg=='excel') {
		location.href = siteUrl + "user/get_user_report_as_excel/"+data;
	}
	$("#selected_ids").val('');
	$("#selected_roles").val('');
}
$('.subscriberaction').on('change', function(e) {
	var val = $(this).val();
   var id = $(this).attr("id");
	$(".subscriberaction").val("");
	switch (val) {
		case "1":
			update_user_status(id, val);
			break;
		case "2":
			update_user_status(id, val);
			break;
		case "3":
			update_user_status(id, val);
			break;
		case "4":
			update_user_status(id, val);
			break;
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
	$(".subscriberaction").select2("val", "");
});

function send_email(type, userid) {
	$.ajax({
      url: siteUrl + "ajax/get_useremail_byid",
      type: 'post',
      dataType: 'json',
      data: {
         userid: userid
      },
      success: function(data) {
         if (data.email) {
			 $('#currentmail').val(data.email);
			 $('.sendto').text(data.email);
			 $('#emailmodal').modal('show');
         }else{
			 alert('User dont have email id');
		 }
      }
   });
   
}
$(".senduseremail").click(function() {
   var emailsubject = $('.emailsubject').val();
   var emailmessage = CKEDITOR.instances['emailmessage'].getData();
   var currentmail = $('#currentmail').val();
   if(emailsubject !='' && emailmessage!=''){
		if(currentmail.indexOf(',') === -1){
			send_emailto_selected_user(emailsubject, emailmessage,currentmail);
		}else{
			send_emailto_multiple_user(emailsubject, emailmessage,currentmail);
		}
   }else if(emailsubject ==''){
	    alert('Please Enter Subject to User.'); 
   }else if(emailmessage ==''){
	    alert('Please Enter Messsage to User.'); 
   }
});
function send_emailto_selected_user(emailsubject, emailmessage,currentmail) {
   $.ajax({
	  beforeSend: function(){
		  $(".senduseremail").text("Sending Email...");
		  $('.senduseremail').attr('disabled', 'disabled');
	  },
      url: siteUrl + "ajax/send_emailto_selected_user",
      type: 'POST',
      dataType: 'json',
      data: {
         emailsubject: emailsubject,
		 emailmessage: emailmessage,
         currentmail: currentmail
      },
      success: function(data) {
         if (data.success) {
			$('.emailsubject').val('');
			CKEDITOR.instances['emailmessage'].setData('');
			$('.successsend').show();
			$('.successsend').text(data.message);
			window.setTimeout(function(){
				$('.senduseremail').prop("disabled", false);
				$(".senduseremail").text("Send Email");
				$('#emailmodal').modal('hide');
				$('.successsend').text('');
				$('.successsend').hide('');
			 }, 5000);
         }
      }
   });
}
function send_multiple_email(userids) {
	$.ajax({
      url: siteUrl + "ajax/get_multible_useremail_byid",
      type: 'post',
      dataType: 'json',
      data: {
         userids: userids
      },
      success: function(data) {
         if (data.email) {
			 $('#currentmail').val(data.email);
			 $('.sendto').text(data.email);
			 $('#emailmodal').modal('show');
         }else{
			 alert('User dont have email id');
		 }
      }
   });
   
}
function send_emailto_multiple_user(emailsubject, emailmessage,currentmail) {
   $.ajax({
	  beforeSend: function(){
		  $(".senduseremail").text("Sending Email...");
		  $('.senduseremail').attr('disabled', 'disabled');
	  },
      url: siteUrl + "ajax/send_emailto_multiple_user",
      type: 'POST',
      dataType: 'json',
      data: {
         emailsubject: emailsubject,
		 emailmessage: emailmessage,
         currentmail: currentmail
      },
      success: function(data) {
         if (data.success) {
			$('.emailsubject').val('');
			CKEDITOR.instances['emailmessage'].setData('');
			$('.successsend').show();
			$('.successsend').text(data.message);
			window.setTimeout(function(){
				$('.senduseremail').prop("disabled", false);
				$(".senduseremail").text("Send Email");
				$('#emailmodal').modal('hide');
				$('.successsend').text('');
				$('.successsend').hide('');
			 }, 2000);
         }
      }
   });
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
            $("#userstatus").select2("val", data.user_status);//.attr('disabled',true);
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
   //tagstext = $('#userstatus').select2('data').text;
	if (status==1)
		var tagstext = "Active";
	else if (status==2)
		var tagstext = "Suspended";
	else if (status==3)
		var tagstext = "Expired";
	else if (status==4)
		var tagstext = "Remove";
		
	//alert(tagstext+"\n\n"+uid+"\n\n"+status)	
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
            //$('#statusmodal').modal('hide');
            $('#suscribeTable tr#row-' + uid).find(".user-status").text(tagstext);
				
			if(status == 4){ 
				$('#row-'+uid).remove();
			}
			//window.location.reload();
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