var lt_currtime = getTime();
var arr_time = lt_currtime.split(':');
var lt_ff = arr_time[2].split(' ')[1];
var lt_ss = arr_time[2].split(' ')[0];
var lt_mm = arr_time[1];
var lt_hh = arr_time[0];
/***********************************Country Section**********************************************/
$( "#countryaction" ).change(function() { 
	if($(this).val()!=''){
		$.ajax({
			url: siteUrl + "settings/generate_timezone_list1",
			type: 'POST',
			dataType: 'json',
			data:{'country':$(this).val()},
			success:function(data){
				if(data.success) {
					$("#timezoneaction").empty().append(data.val);
				}
			}
		}); 
	}
});
/***********************************Timezone Section**********************************************/
$( "#timezonecancel" ).click(function() { 
	$(this).parent().parent().parent().prev().trigger('click');
})
$( "#timezonesubmit" ).click(function() {
	$('div.bannermsg .banner').empty();
	var country = $("#countryaction").val();
	var zone = $("#timezoneaction").val().replace(':', "|");
	var time_to_send_email = $("#timeofday").val();
	if(country!='' && zone!=''){
		var timezone = country +'||'+zone;
		$.ajax({
			url: siteUrl + "settings/add_or_update_settings",
			type: 'POST',
			dataType: 'json',
			data:{'timezone':timezone,'time_to_send_email':time_to_send_email,'update':'timezone'},
			success:function(data){
				if(data.success) {
					$('.banner.success').text(data.message);
					$('#mes_suc').show();
				}
				if(data.timeforamt) {
					lt_currtime = data.timeforamt;
					arr_time = lt_currtime.split(':');
					lt_ff = arr_time[2].split(' ')[1];
					lt_ss = arr_time[2].split(' ')[0];
					lt_mm = arr_time[1];
					lt_hh = arr_time[0];
				}
			}
		});
	}else{
		if(country == ''){
			alert('Please Select Country Name.');
		}
		if(zone == ''){
			alert('Please Select Your Timezone.');
		}
	}
});
/***********************************Timezone Section**********************************************/
/***********************************TimeFormat Section**********************************************/
$( "#timeformatcancel" ).click(function() { 
	$(this).parent().parent().parent().prev().trigger('click');
})
$( "#timeformatsubmit" ).click(function() {
	$('div.bannermsg .banner').empty();
	var time_format = $("#timeformataction").val();
	var time_to_send_email = $("#timeofday").val();
	$.ajax({
		url: siteUrl + "settings/add_or_update_settings",
		type: 'POST',
		dataType: 'json',
		data:{'time_format':time_format,'time_to_send_email':time_to_send_email,'update':'time_format'},
		success:function(data){
			if(data.success) {
				$('.banner.success').text(data.message);
				$('#mes_suc').show();
			}
			if(data.timeforamt) {
				lt_currtime = data.timeforamt;
				arr_time = lt_currtime.split(':');
				lt_ff = arr_time[2].split(' ')[1];
				lt_ss = arr_time[2].split(' ')[0];
				lt_mm = arr_time[1];
				lt_hh = arr_time[0];
			}
			if(data.ajaxoption) {
				$('#timeofday').empty().append(data.ajaxoption);
			}
		}
	});
});
/***********************************TimeFormat Section**********************************************/
/***********************************TimeFormat Section**********************************************/
$( "#dateformatcancel" ).click(function() { 
	$(this).parent().parent().parent().prev().trigger('click');
})
$( "#dateformatsubmit" ).click(function() {
	$('div.bannermsg .banner').empty();
	var date_format = $("#dateformataction").val();
	var time_to_send_email = $("#timeofday").val();
	$.ajax({
		url: siteUrl + "settings/add_or_update_settings",
		type: 'POST',
		dataType: 'json',
		data:{'date_format':date_format,'time_to_send_email':time_to_send_email,'update':'date_format'},
		success:function(data){
			if(data.success) {
				$('.banner.success').text(data.message);
				$('#mes_suc').show();
			}
			if(data.dateforamt) {
					$('.Localdate').text(data.dateforamt);
			}
		}
	});
});
/***********************************TimeFormat Section**********************************************/
/***********************************Updates and news**********************************************/
$( "#Updatescancel" ).click(function() { 
	$(this).parent().parent().parent().prev().trigger('click');
})
$( "#Updatessubmit" ).click(function() {
	$('div.bannermsg .banner').empty();
	var Networksaction = $("#Networksaction").val();
	var Assignmentreminder = $("#Assignmentreminder").val();
	var Assignmentmissed = $("#Assignmentmissed").val();
	var received = $("#received").val();
	var time_to_send_email = $("#timeofday").val();
	var Updates_news = Networksaction +'||'+Assignmentreminder+'||'+Assignmentmissed+'||'+received;
	$.ajax({
		url: siteUrl + "settings/add_or_update_settings",
		type: 'POST',
		dataType: 'json',
		data:{'Updates_news':Updates_news,'time_to_send_email':time_to_send_email,'update':'Updates_news'},
		success:function(data){
			if(data.success) {
				$('.banner.success').text(data.message);
				$('#mes_suc').show();
			}
		}
	});
});
/***********************************Updates and news**********************************************/
/***********************************Messages from other users**********************************************/
$( "#userscancel" ).click(function() { 
	$(this).parent().parent().parent().prev().trigger('click');
})
$( "#userssubmit" ).click(function() {
	$('div.bannermsg .banner').empty();
	var Sharing = $("#Sharing").val();
	var Invitation = $("#Invitation").val();
	var messages_users = Sharing +'||'+Invitation;
	var time_to_send_email = $("#timeofday").val();
	$.ajax({
		url: siteUrl + "settings/add_or_update_settings",
		type: 'POST',
		dataType: 'json',
		data:{'messages_users':messages_users,'time_to_send_email':time_to_send_email,'update':'messages_users'},
		success:function(data){
			if(data.success) {
				$('.banner.success').text(data.message);
				$('#mes_suc').show();
			}
		}
	});
});
/***********************************Messages from other users**********************************************/
/***********************************Messages from My Workouts team**********************************************/
$( "#Workoutscancel" ).click(function() { 
	$(this).parent().parent().parent().prev().trigger('click');
})
$( "#Workoutssubmit" ).click(function() {
	$('div.bannermsg .banner').empty();
	var special = $("#special").val();
	var Exercises = $("#Exercises").val();
	var messages_team = special +'||'+Exercises;
	var time_to_send_email = $("#timeofday").val();
	$.ajax({
		url: siteUrl + "settings/add_or_update_settings",
		type: 'POST',
		dataType: 'json',
		data:{'messages_team':messages_team,'time_to_send_email':time_to_send_email,'update':'messages_team'},
		success:function(data){
			if(data.success) {
				$('.banner.success').text(data.message);
				$('#mes_suc').show();
			}
		}
	});
});
/***********************************Messages from My Workouts team**********************************************/
/***********************************what time of day to send**********************************************/
$( "#timeofday" ).change(function() {
	$('div.bannermsg .banner').empty();
	var time_to_send_email = $(this).val();
	$.ajax({
		url: siteUrl + "settings/add_or_update_settings",
		type: 'POST',
		dataType: 'json',
		data:{'time_to_send_email':time_to_send_email,'sendmail':'sendmail','update':'time_to_send_email'},
		success:function(data){
			if(data.success) {
				$('.banner.success').text(data.message);
				$('#mes_suc').show();
			}
		}
	});
});
/***********************************what time of day to send**********************************************/
/***********************************Measurements**********************************************/
$( "#measurementcancel" ).click(function() { 
	$(this).parent().parent().parent().prev().trigger('click');
})
$( "#measurementsubmit" ).click(function() {
	$('div.bannermsg .banner').empty();
	var weightaction = $("#weightaction").val();
	var distanceaction = $("#distanceaction").val();
	var measurements = weightaction +'||'+distanceaction;
	var time_to_send_email = $("#timeofday").val();
	$.ajax({
		url: siteUrl + "settings/add_or_update_settings",
		type: 'POST',
		dataType: 'json',
		data:{'measurements':measurements,'time_to_send_email':time_to_send_email,'update':'measurements'},
		success:function(data){
			if(data.success) {
				$('.banner.success').text(data.message);
				$('#mes_suc').show();
			}
		}
	});
});
/***********************************Measurements**********************************************/
/*$( "#timezoneaction" ).change(function() {
	$('div.bannermsg .banner').empty();
	var timezone = $(this).val();
	$.ajax({
		url: siteUrl + "settings/add_or_update_settings",
		type: 'POST',
		dataType: 'json',
		data:{'timezone':timezone,'update':'timezone'},
		success:function(data){
			if(data.success) {
				$('.banner.success').text(data.message);
				$('#mes_suc').show();
			}
		}
	});
});
$( "#timeformataction" ).change(function() {
	$('div.bannermsg .banner').empty();
	var time_format = $(this).val();
	$.ajax({
		url: siteUrl + "settings/add_or_update_settings",
		type: 'POST',
		dataType: 'json',
		data:{'time_format':time_format,'update':'time_format'},
		success:function(data){
			if(data.success) {
				$('.banner.success').text(data.message);
				$('#mes_suc').show();
			}
		}
	});
});*/
$( "#languageaction" ).change(function() {
	var language = $(this).val();
	var time_to_send_email = $("#timeofday").val();
	$.ajax({
		url: siteUrl + "settings/add_or_update_settings",
		type: 'POST',
		dataType: 'json',
		data:{'language':language,'time_to_send_email':time_to_send_email,'update':'language'},
		success:function(data){
			if(data.success) {
				$('.banner.success').text(data.message);
				$('#mes_suc').show();
				location.reload(true);
			}
		}
	});
});
$( "#weekaction" ).change(function() {
	$('div.bannermsg .banner').empty();
	var week_sarts_on = $(this).val();
	var time_to_send_email = $("#timeofday").val();
	$.ajax({
		url: siteUrl + "settings/add_or_update_settings",
		type: 'POST',
		dataType: 'json',
		data:{'week_sarts_on':week_sarts_on,'time_to_send_email':time_to_send_email,'update':'week_sarts_on'},
		success:function(data){
			if(data.success) {
				$('.banner.success').text(data.message);
				$('#mes_suc').show();
			}
		}
	});
});
/*
$( "#measurementsaction" ).change(function() {
	$('div.bannermsg .banner').empty();
	var measurements = $(this).val();
	var time_to_send_email = $("#timeofday").val();
	$.ajax({
		url: siteUrl + "settings/add_or_update_settings",
		type: 'POST',
		dataType: 'json',
		data:{'measurements':measurements,'time_to_send_email':time_to_send_email,'update':'measurements'},
		success:function(data){
			if(data.success) {
				$('.banner.success').text(data.message);
				$('#mes_suc').show();
			}
		}
	});
});*/
$( "#notificationsaction" ).change(function() {
	$('div.bannermsg .banner').empty();
	var notifications = $(this).val();
	var time_to_send_email = $("#timeofday").val();
	$.ajax({
		url: siteUrl + "settings/add_or_update_settings",
		type: 'POST',
		dataType: 'json',
		data:{'notifications':notifications,'time_to_send_email':time_to_send_email,'update':'notifications'},
		success:function(data){
			if(data.success) {
				$('.banner.success').text(data.message);
				$('#mes_suc').show();
			}
		}
	});
});
$('.integrationsaction').bootstrapSwitch();
$(".deviceaction").tinyToggle();
$('.integrationsaction').on('switchChange.bootstrapSwitch', function (event, state) {
	if(state === true || state === false) {
		$('div.bannermsg .banner').empty();
		var device_integrations = $(this).val();
		var time_to_send_email = $("#timeofday").val();
		$('.integrationsaction').bootstrapSwitch('disabled', true);
		$.ajax({
			url: siteUrl + "settings/add_or_update_settings",
			type: 'POST',
			dataType: 'json',
			data:{'device_integrations':device_integrations,'device_state':state ,'time_to_send_email':time_to_send_email,'update':'device_integrations'},
			success:function(data){
				if(data.success) {
					$('.banner.success').text(data.message);
					$('#mes_suc').show();
				}
				$('.integrationsaction').bootstrapSwitch('disabled', false);
			}
		});
	}
});
var update_lt = setInterval(updateTime, 1000);
function updateTime() {
	var lt_timer;
	lt_ss++;
	if (lt_ss < 10) {
		lt_ss = '0' + lt_ss;
	}
	if (lt_ss == 60) {
		lt_ss = '00';
		lt_mm++;
		if (lt_mm < 10) {
			lt_mm = '0' + lt_mm;
		}
		if (lt_mm == 60) {
			lt_mm = '00';
			lt_hh++;
			if (lt_hh < 10) {
				lt_hh = '0' + lt_hh;
			}
			if (lt_hh == 24) {
				lt_hh = '00';
			}
		}
	}
	lt_hh = lt_hh % 12;
	lt_hh = lt_hh ? lt_hh : 12;
	lt_hh = lt_hh < 10 ? '0' + lt_hh : lt_hh;
	lt_timer = lt_hh+':'+lt_mm+':'+lt_ss+' '+lt_ff;
	$('.LocalTime').text(lt_timer);
}
$('#banded').on('show.bs.collapse', function (ev) {
	var target1 = $(ev.target).parent().parent().parent().attr('id');
	var target2 = $(ev.target).parent().attr('id');
	if (target1 == 'manageemailfrequency'){
		$('#banded .in').collapse('show');
		$('#banded #manageemailfrequency .in').collapse('hide');
	}else if(target2 == 'manageintegrationsaction'){
		$('#banded .in').collapse('show');
		$('#banded #manageintegrationsaction .in').collapse('hide');
	}else{
		$('#banded .in').collapse('hide');
	}
});
/*********** XR set Variables ******************/
$( "#commonsettingcancel" ).click(function() { 
	$(this).parent().parent().parent().prev().trigger('click');
})
$( "#commonsettingsubmit" ).click(function() {
	$('div.bannermsg .banner').empty();
	var xrsetvariableflag = $("#xrsetvariableaction").val();
	$.ajax({
		url: siteUrl + "settings/add_or_update_settings",
		type: 'POST',
		dataType: 'json',
		data:{'xrsetvariableflag':xrsetvariableflag,'update':'common_settings'},
		success:function(data){
			if(data.success) {
				$('.banner.success').text(data.message);
				$('#mes_suc').show();
			}
		}
	});
});
/*********** XR set Variables ******************/