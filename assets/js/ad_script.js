//prevent scroll problem when model open
$(document).ready(function(){
	$('.modal').on('hidden.bs.modal', function (e) {
		if($('.modal').hasClass('in')) {
			$('body').addClass('modal-open');
		}
	});
});
function convertDate(d){
 var parts = d.split(" ");
 var months = {
  Jan: "01",
  Feb: "02",
  Mar: "03",
  Apr: "04",
  May: "05",
  Jun: "06",
  Jul: "07",
  Aug: "08",
  Sep: "09",
  Oct: "10",
  Nov: "11",
  Dec: "12"
 };
 return parts[2]+"-"+months[parts[1]]+"-"+parts[0];
}
function usereditsubmitForm(){
	var fusername_val = $("#fusernamech").val();
	var lusername_val = $("#lusernamech").val();
	var dobch_val = $("#dobch").val();
	var idavatardata = $('#profile_im').attr("date-imgid");
	var useridpro = $("#edit_userid").val();
	var usergender = $("input.user_gender[type='radio']:checked").val();
	var userphone = $("input#usernamephone").val();
	var alldatalist = [];
	alldatalist.push({"fnameuserch":fusername_val,"lnameuserch":lusername_val,"dobch":dobch_val,"avatar_id":idavatardata,"useridprofile":useridpro,"usergender":usergender,"userphone":userphone} );
	window.console.log(alldatalist);
	$.post(siteUrl_frontend+"search/usersavergen",{"action":"profile","post_form":alldatalist},function(){
		//$('#editthisaccount').modal('show');
		$("#username_"+useridpro).html("").html(fusername_val+" "+lusername_val);
		showUserModel(useridpro,1);
	});
	return false;
}
console.log(siteName+'->'+siteAgeLimit);
var user_siteage = siteAgeLimit;
var user_sitename = siteName;
function changeeditpro(){
	var current_clickid = $("#user_id").val();
	$('#userModal').html('');
	$.get(siteUrl_frontend+"search/getmodelTemplate",{"action":"profileactions","method": "profileedit","id":current_clickid,"fromAdmin":1},function(response){
		if(response) {
			$('#userModal').html(response);
			$("input.onlynumberallowed").keypress(function(event) {
				// Allow only backspace and delete
				 if((event.which != 8 && isNaN(String.fromCharCode(event.which)) || event.which == 32)){
					   event.preventDefault(); //stop character from entering input
				 }
			});
			if($('#profile_im').attr("date-imgid") != ''){
				$('#mdl_curr_imgid').val($('#profile_im').attr("date-imgid"));
			}
			$("#edit_userid").val(current_clickid);
			getProfileImgOptionModal();
			$(document).on('click','a.edit-imgnew',function(e){
				if ($('#profile_im').attr("date-imgid")=='') {
					$('a#btn_profileimgedit').trigger('click');
				}else{
					var profileimg = $(this).find('img').attr('src');
					$('#mypopupModal #btn_profileimgprev').attr('data-itemurl', profileimg);
					$('#mypopupModal').modal();
				}
			});
			$("#dobch").datepicker({  
				dateFormat : 'dd M yy',
				changeMonth : true,
				changeYear : true,
				// yearRange: '1970:1997',
				// maxDate: '-1d',
				defaultDate :$("#dobch").val(),
				onSelect: function(selected, evnt) {
					var dobch_val = $( this ).val();
					if(dobch_val != ''){
						var dob = new Date(convertDate(dobch_val));
						var today = new Date();
						var dayDiff = Math.ceil(today - dob) / (1000 * 60 * 60 * 24 * 365);
						var age = parseInt(dayDiff);
						if(age <= user_siteage){
							var message = 'Minimum age for using the <b>'+user_sitename+'</b>\'s my workouts platform is <b>'+user_siteage+'</b>';
							$('<li/>').html(message).appendTo('#validation-errors');
							$('.modal-validerror').addClass('hide');
							$('#errorMessage-modal').modal('show');
							$('#profileactions').formValidation('revalidateField', 'dobch');
						}else{
							$('#profileactions').formValidation('revalidateField', 'dobch');
						}
					}
				}
			});
			checkFormdata();
		}
	});
}
function getProfileImgOptionModal(){
	$('#mypopupModal').html('');
	$.ajax({
		url : siteUrl_frontend+'search/getmodelTemplate?user_from=admin&cp='+user_allow_page,
		data : {
			action : 'profileimgoption',
			modal: 'mypopupModal'
		},
		success : function(content){
			$('#mypopupModal').html(content);
		}
	});
}
function checkFormdata(){
	FormValidation.Validator.ageLimitch = {
		validate: function(validator, $field, options) {
			//var dobch_val = $("#dobch").val();
			var dobch_val = $field.val();
			if(dobch_val != ''){
				var dob = new Date(convertDate(dobch_val));
				var today = new Date();
				var dayDiff = Math.ceil(today - dob) / (1000 * 60 * 60 * 24 * 365);
				var age = parseInt(dayDiff);
				if(age <= user_siteage)
					return false;
				else
					return true;
			}
		}
	};
	$('#profileactions').formValidation({
		framework: 'bootstrap',
		fields: {
			dobch: {
				validators: {
					 notEmpty: {
						message: 'The date of birth is required'
					},
					ageLimitch: {
						message: 'Minimum age for using the <b>'+user_sitename+'</b>\'s my workouts platform is <b>'+user_siteage+'</b>'
					}
				}
			},
			user_birthdate: {
				validators: {
					 notEmpty: {
						message: 'The date of birth is required'
					},
					ageLimitch: {
						message: 'Minimum age for using the <b>'+user_sitename+'</b>\'s my workouts platform is <b>'+user_siteage+'</b>'
					}
				}
			}
		}
	}).on('success.form.fv', function(e) { return usereditsubmitForm();});
}
$(document).on('click','.modalBack',function(e){	
	e.preventDefault();
	e.stopImmediatePropagation();
	if($("#triggerid").val() == 4){
		var useridpro = $("#edit_userid").val();
		$('#userModal').modal();
	}
});
$(document).ready(function(){
	$('#mdl_popupimglibrary-modal').on('hidden.bs.modal', function(){
		if($("#triggerid").val() == 4){
			var useridpro = $("#edit_userid").val();
			$('#userModal').modal();
		}
	});
});
getProfileImgOptionModal();
$(document).on('click','a.edit-userimg',function(e){
	if ($('#profile_userimg').attr("date-imgid")=='') {
		$('a#btn_profileimgedit').trigger('click');
	}else{
		var profileimg = $(this).find('img').attr('src');
		$('#mypopupModal #btn_profileimgprev').attr('data-itemurl', profileimg);
		$('#mypopupModal').modal();
	}
	return false;
});

function profileImgPrevModal(elem){
	var imgurl = $(elem).attr('data-itemurl');
	if(imgurl!=undefined && imgurl!=''){
		$('#mdl_preview_libimg').html('<img alt="'+__('Preview Image')+'" class="Preview_image" id="mdl_previewlibimg" src="'+imgurl+'"/>');
	}else{
		$('#mdl_preview_libimg').html('<i class="fa fa-file-image-o prevfeat"></i>');
	}
	$('#mdl_popupimgprev-modal .mdl_preview-opt button').addClass('hide');
	$('#mdl_popupimgprev-modal').modal();
	var profileimgid = $('#profile_im').attr('date-imgid');
	if(profileimgid){
		$.post(siteUrl + 'ajax/ajaxInsertActivityfeed', {'actid': profileimgid, 'method': 'previewed', 'type': 'image'}, function(){});
	}
}

$('body').on('click', '#btn_profileimgclear', function(e) {
	e.preventDefault();
	var noImgUrl = $(this).attr('href');
	if (confirm('Are you sure, want to clear this profile image?')) {
		if($('a#btn_userimgedit img#profile_userimg').is(':visible')){
			$('#profile_userimg').attr({'src': noImgUrl, 'date-imgid': ''});
			$('#trainer_profile_image').val('');
		}else{
			$('#userModal img#profile_im').attr('src', noImgUrl);
			$('#profile_im').attr('date-imgid', '');
		}
		$('#mypopupModal').modal('hide');
	}
	return false;
});

$(document).on('click','a#btn_profileimgedit',function(e){
	$("#userModal").modal('hide');
	$('#mypopupModal').modal('hide');
	e.preventDefault();
	e.stopImmediatePropagation();
	$("#triggerid").val(4);
	if($('a#btn_userimgedit img#profile_userimg').is(':visible')){
		$("#triggerid").attr({'data-imgtagid': 'profile_userimg', 'data-hiddenid': 'trainer_profile_image'});
	}else{
		$("#triggerid").attr('data-imgtagid', '');
	}
	if($('#mdl_parentFolderId').length && $('#mdl_subFolderId').length){
		popuptriggerAjaxImgLibrary();
	}	
	if($("#triggerid").val() == 4){
		$('button.mdl_folder-select').attr("id", 4);
	}
	$('#mdl_popupimglibrary-modal').modal();
	initSimpleUpload();
});