$( ".toggletitle" ).click(function() { 
    if(typeof $(this).attr('aria-expanded') === 'undefined') {
	   $(this).find('i').css({'display':'none'});
    }
    else if($(this).attr('aria-expanded') == 'true'){
	   $(this).find('i').css({'display':'block'});
    }
    else{
	   $(this).find('i').css({'display':'none'});
    }
   
});
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
							$('.alert-success span').text(data.message);
							$('#mes_suc').show();
						}
						if(data.timeforamt) {
							$('.LocalTime').text(data.timeforamt);
						}
					}
		});
    }else{
		if(country == ''){
			alert('Please Select Contryname.');
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
    var time_format = $("#timeformataction").val();
	var time_to_send_email = $("#timeofday").val();
    $.ajax({
                url: siteUrl + "settings/add_or_update_settings",
                type: 'POST',
                dataType: 'json',
                data:{'time_format':time_format,'time_to_send_email':time_to_send_email,'update':'time_format'},
                success:function(data){
                    if(data.success) {
                        $('.alert-success span').text(data.message);
                        $('#mes_suc').show();
                    }
					if(data.timeforamt) {
							$('.LocalTime').text(data.timeforamt);
					}
					if(data.ajaxoption) {
							$('#timeofday').empty().append(data.ajaxoption);
							$('#timeofday').select2();
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
    var date_format = $("#dateformataction").val();
	var time_to_send_email = $("#timeofday").val();
    $.ajax({
                url: siteUrl + "settings/add_or_update_settings",
                type: 'POST',
                dataType: 'json',
                data:{'date_format':date_format,'time_to_send_email':time_to_send_email,'update':'date_format'},
                success:function(data){
                    if(data.success) {
                        $('.alert-success span').text(data.message);
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
    var Networksaction = $("#Networksaction").val();
	var Assignmentreminder = $("#Assignmentreminder").val();
	//var Assignmentmissed = $("#Assignmentmissed").val();
    var Assignmentmissed = $('input[name="Assignmentmissed"]:checked').val();
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
                        $('.alert-success span').text(data.message);
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
                        $('.alert-success span').text(data.message);
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
                        $('.alert-success span').text(data.message);
                        $('#mes_suc').show();
                    }
                }
    });
});
/***********************************Messages from My Workouts team**********************************************/
/***********************************what time of day to send**********************************************/
$( "#timeofday" ).change(function() { 
    var time_to_send_email = $(this).val();
    $.ajax({
                url: siteUrl + "settings/add_or_update_settings",
                type: 'POST',
                dataType: 'json',
                data:{'time_to_send_email':time_to_send_email,'sendmail':'sendmail','update':'time_to_send_email'},
                success:function(data){
                    if(data.success) {
                        $('.alert-success span').text(data.message);
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
                        $('.alert-success span').text(data.message);
                        $('#mes_suc').show();
                    }
                }
    });
});
/***********************************Measurements**********************************************/
/*$( "#timezoneaction" ).change(function() { 
    var timezone = $(this).val();
    $.ajax({
                url: siteUrl + "settings/add_or_update_settings",
                type: 'POST',
                dataType: 'json',
                data:{'timezone':timezone,'update':'timezone'},
                success:function(data){
                    if(data.success) {
                        $('.alert-success span').text(data.message);
                        $('#mes_suc').show();
                    }
                }
    });
});
$( "#timeformataction" ).change(function() { 
    var time_format = $(this).val();
    $.ajax({
                url: siteUrl + "settings/add_or_update_settings",
                type: 'POST',
                dataType: 'json',
                data:{'time_format':time_format,'update':'time_format'},
                success:function(data){
                    if(data.success) {
                        $('.alert-success span').text(data.message);
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
                        $('.alert-success span').text(data.message);
                        $('#mes_suc').show();
                        window.location.href = siteUrl_frontend+data.langname+"/admin/settings/preference_settings";
						//location.reload(true);
                    }
                }
    });
});
$( "#weekaction" ).change(function() { 
    var week_sarts_on = $(this).val();
	var time_to_send_email = $("#timeofday").val();
	$.ajax({
                url: siteUrl + "settings/add_or_update_settings",
                type: 'POST',
                dataType: 'json',
                data:{'week_sarts_on':week_sarts_on,'time_to_send_email':time_to_send_email,'update':'week_sarts_on'},
                success:function(data){
                    if(data.success) {
                        $('.alert-success span').text(data.message);
                        $('#mes_suc').show();
                    }
                }
    });
});
/*
$( "#measurementsaction" ).change(function() { 
    var measurements = $(this).val();
	var time_to_send_email = $("#timeofday").val();
	$.ajax({
                url: siteUrl + "settings/add_or_update_settings",
                type: 'POST',
                dataType: 'json',
                data:{'measurements':measurements,'time_to_send_email':time_to_send_email,'update':'measurements'},
                success:function(data){
                    if(data.success) {
                        $('.alert-success span').text(data.message);
                        $('#mes_suc').show();
                    }
                }
    });
});*/
$( "#notificationsaction" ).change(function() { 
	var notifications = $(this).val();
	var time_to_send_email = $("#timeofday").val();
	$.ajax({
                url: siteUrl + "settings/add_or_update_settings",
                type: 'POST',
                dataType: 'json',
                data:{'notifications':notifications,'time_to_send_email':time_to_send_email,'update':'notifications'},
                success:function(data){
                    if(data.success) {
                        $('.alert-success span').text(data.message);
                        $('#mes_suc').show();
                    }
                }
    });
});


///$(".tiny-toggle").toggle(alert("toggle"));
$('integrationsaction').trigger('click'); 

$(document).click(function(){   
    $('#checkp').toggle(
        function () { 
            $('.check').attr('Checked','Checked'); 
        },
        function () { 
            $('.check').removeAttr('Checked'); 
        }
    );
});

/*
$( ".integrationsaction" ).on( "Check", function() {
	consol.log("click");
});*/
$(".integrationsaction").tinyToggle({          
  onCheck: function() { //alert("Checked");
			var device_integrations = $(this).val();
			var time_to_send_email = $("#timeofday").val();
			$.ajax({
						url: siteUrl + "settings/add_or_update_settings",
						type: 'POST',
						dataType: 'json',
						data:{'type':"add",'device_integrations':device_integrations,'time_to_send_email':time_to_send_email,'update':'device_integrations'},
						success:function(data){
							if(data.success) {
								$('.alert-success span').text(data.message);
								$('#mes_suc').show();
							}
						}
			});
		},
		onUncheck: function() { //alert("Uncheck");
			var device_integrations = $(this).val();
			var time_to_send_email = $("#timeofday").val();
			$.ajax({
						url: siteUrl + "settings/add_or_update_settings",
						type: 'POST',
						dataType: 'json',
						data:{'type':"delete",'device_integrations':device_integrations,'time_to_send_email':time_to_send_email,'update':'device_integrations'},
						success:function(data){
							if(data.success) {
								$('.alert-success span').text(data.message);
								$('#mes_suc').show();
							}
						}
			});
		}
  
  //onClick: function(obj) { console.log("onClick", "TinyToggle   was clicked!"); }
       
});

//$( ".integrationsaction" ).click(function() {  //alert("test");
function integrationsaction(){ //alert("Test");

    var device_integrations = $(this).val();
	var time_to_send_email = $("#timeofday").val();
	$.ajax({
                url: siteUrl + "settings/add_or_update_settings",
                type: 'POST',
                dataType: 'json',
                data:{'device_integrations':device_integrations,'time_to_send_email':time_to_send_email,'update':'device_integrations'},
                success:function(data){
                    if(data.success) {
                        $('.alert-success span').text(data.message);
                        $('#mes_suc').show();
                    }
                }
    });
}
	//});
$( document ).ready(function() {
	
	$("[name='my-checkbox']").bootstrapSwitch();
	
	$(document).on('click', '.fa-chevron-down', function () {
       $(this).removeClass("fa fa-chevron-down").addClass("fa fa-chevron-up");
    });
	
	 $(document).on('click', '.fa-chevron-up', function () {
       $(this).removeClass("fa fa-chevron-up").addClass("fa fa-chevron-down");
    });
	
});

