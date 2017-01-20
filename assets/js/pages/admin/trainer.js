if ($('.traineractions').length) {
   $(".traineractions").chosen({
      disable_search: true
   });
}

function deleteTrainer() {
    
	$.ajax({
                url: siteUrl + "admin/ajax/deleteSubscriber",
                type: 'POST',
                dataType: 'json',
                data:{'id':id},
                success:function(data){
                    if(data.success) {
                        $('.del-sucess .alert-success span').text(data.message);
                        $('.del-sucess').show();
                        $('#row-'+id).remove();
                        $("#noDelete").click();
                    }
                }
            });
	}
/*$('.traineractions').on('change', function(e) {
	var val = $(this).val();
    var id = $(this).attr("id");
	$(".traineractions").val("").trigger('chosen:updated');
	switch (val) {
		case "traineredit":
		window.location.href = siteUrl + "user/edit/" + id;
		break;
		
		case "trainerdelete":
		$.ajax({
			url: siteUrl+"user/changeManagerStatus",
			type: 'POST',
			dataType: 'json',
			data:{'userId':id,'statusId':'4'},
			success:function(data){
				if(data.success) {
					$('.del-sucess span').text(data.message);
					$('.del-sucess').show();
				} 
			}
		});
		break;

		default:
		break;
   }
});*/

function changeuserTrainerstatus(type, userid) {
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
$(".changeuserstatus").click(function() {
   var uid = $('#curid').val();
   var userstatus = $('#userstatus').val();
   //update_user_status(uid, userstatus);
	changeTrainerStatus(uid,userstatus);
});
function changeTrainerStatus(userId,statusId) {
	if(statusId != ''){
		if(isNaN(statusId)){
			if(statusId.indexOf('-') === -1){
				send_email('single', userId);
				$(".selectAction").select2("val", "");
			}
			else{
				var editid = statusId.split("-");
				//alert(editid)
				if (editid[0]=="edit") {
					location.href = siteUrl+"user/edit/"+editid[1];	
				}else if (editid[0]=="editstatus"){
					changeuserTrainerstatus('single', editid[1]);
				}else if (editid[0]=="profile") {
					location.href = siteUrl+"user/profile/"+editid[1];	
				}else if (editid[0]=="remove_profile") {
					var r = confirm("Are you sure to remove prom profile?");
					if (r){
						//location.href = siteUrl+"user/remove_profile/"+editid[1];
						$.ajax({
							url: siteUrl+"user/remove_profile/",
							type: 'POST',
							data:{'userid':userId},
							success:function(data){
								if (data) {
									location.href = siteUrl+"trainer/trainer_profile";
									/*
									$("#row-"+userId).remove();
									$(".ajax_msg").fadeIn();
									$(".ajax_msg").html('<i class="fa fa-check"></i><span>Promo profile was removed successfully...!</span>');
									setTimeout(function(){
										$(".ajax_msg").html('');
										$(".ajax_msg").fadeOut();
									},3500);
									*/
								}
							}
						});
					}
				}
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
						$('#statusmodal').modal('hide');
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
$('#traineractions').on('change', function(e) {
	var val = $(this).val();
   var id = $(this).attr("id");
	
	switch (val) {
      case "addtrainer":
		 location.href = siteUrl+"user/create/trainer";
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
   $(".selectAction").select2("val", "");
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
			 }, 2000);
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
				$('.successsend').hide('');
			 }, 2000);
         }
      }
   });
}