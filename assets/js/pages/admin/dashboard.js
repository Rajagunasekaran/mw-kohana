var pathname = window.location.pathname;
var oldvalue = '';
function show_others(arg){
	$("#listothers").html("");
	$.ajax({
		url: siteUrl+"ajax/getuserdetails",
		method: 'post',
		data: {	userids: arg	},
		success: function(content) {
			$("#othersModal").modal("show");
         var JSONArray = $.parseJSON( content );
			if(JSONArray.length>0) {
				var data = [];
				var str = '';
				for(var i=0; i<JSONArray.length; i++){
					str += '<div class="row" style=\'cursor: pointer\' id=\'viewuser_'+JSONArray[i].id+'\' onclick="viewusers('+JSONArray[i].id+')" title="Click here to view '+JSONArray[i].user_fname+" "+JSONArray[i].user_lname+'">';
					str += '<div class="col-xs-2"></div>';
					str += '<div class="col-xs-2" style=\'border:1px solid #ededed;border-right:none;padding:0px 0px 0px 5px;\'><i class="fa fa-user" style="font-size:50px;"></i></div>';
					str += '<div class="col-xs-6" style=\'border:1px solid #ededed;border-left:none;display: table-cell;padding:15px 0px 5px 5px;height:52px;\'>'+(JSONArray[i].user_fname+" "+JSONArray[i].user_lname).capitalizeFirstLetter()+'</div>';
					str += '<div class="col-xs-2"></div>';
					str += '</div><br>';
				}
				
				$("#listothers").html(str);
			}		
		}
	});
}
String.prototype.capitalizeFirstLetter = function() {
    return this.charAt(0).toUpperCase() + this.slice(1);
}
function viewusers(arg){
	showUserModel(arg,1);
}
var tmpVal ='';
function get_activesite_trainers(){
	if ($("#shs_sites").val()==null) {
		$('#sh_stats').html("<p style='color:#FF0000;border:0px solid red;text-align:center;margin-top:25px;font-weight:bold;'>Please Choose Sites...</p>");
		return false;
	}
	if (tmpVal == $('#shs_sites').val()) {
		return false;
	}
	tmpVal = $('#shs_sites').val();
	//alert(tmpVal)
	$.ajax({
		url: siteUrl+"dashboard/get_trainers",
		method: 'post',
		data: {	
			siteid : tmpVal
		},
		success: function(content) {
			//alert(content)
			if (content) {
				$(".shs_bythis").html(content);
				$('#shs_bythis').multipleSelect({
					single: true
				});
				tempVal ='';
				get_sharestats();
			}else{
				$(".shs_bythis").html("");
				$('#sh_stats').html("<p style='color:#FF0000;border:0px solid red;text-align:center;margin-top:25px;font-weight:bold;'>Here there is no trainer , So please Choose another site...</p>");
				return false;
			}
		}
	});
}


if (pathname=="/admin/dashboard")
{
	if ($("#trainer_check").val()!="" && $("#manager_check").val()=="") {
		get_sharestats();
	}
}
var tempVal ='';
function get_sharestats(){
	if ($("#manager_check").val().length!=0)
	{
		if ($("#shs_bythis").val()==null) {
			$('#sh_stats').html("<p style='color:#FF0000;border:0px solid red;text-align:center;margin-top:25px;font-weight:bold;'>Please Choose Trainers...</p>");
			return false;
		}
		//This Condition for to avoid Multi ajax request 
		if (tempVal == $('#shs_bythis').val()) {
			
			return false;
		}
	}
	tempVal = $('#shs_bythis').val();
	if ($("#trainer_check").val()!="") {
		var user = $("#shs_bythis").val();
		$("#sh_stats").html("<p style='color:#3D8B3D;border:0px solid green;text-align:center;margin-top:25px;font-weight:bold;'>Loading...</p>");
		//console.log("Current Site ID--->"+$('#shs_sites').val())
		$.ajax({
			url: siteUrl+"dashboard/sharestats",
			method: 'post',
			data: {
				siteid  : $('#shs_sites').val(),
				user_id : user
			},
			success: function(content) {
				if (content) {
					$("#sh_stats").html(content);
					if ($("#manager_check").val().length!=0)
					{
						//console.log("Manager Check--->"+$("#manager_check").val()+"-----"+$("#manager_check").val().length);
						$('.accordion-toggle').on('click', function() {
							//console.log("Check I")
							$('.chevron_toggleable').removeClass('fa-chevron-up').addClass('fa-chevron-down');
							//console.log("Check II")
							$(this).find('i').closest('.chevron_toggleable').toggleClass('fa-chevron-down fa-chevron-up');
						});
						$('.collapse').on('show.bs.collapse', function () {
							$(this).closest("table").find(".collapse.in").not(this).collapse('toggle');
						});
					}
				}else{
					$('#sh_stats').html("<p style='color:#FF0000;border:0px solid red;text-align:center;margin-top:25px;font-weight:bold;'>No data found</p>");
				}
				
	
				
			}
		});
	}
}


if ($("#sh_bythis").val()!=4) {
	$('#sh_fromdate').datepicker({	dateFormat: 'dd/mm/yy',	defaultDate :'-14d'	});
	$("#sh_fromdate").datepicker('setDate','-14');
	$('#sh_todate').datepicker({	dateFormat: 'dd/mm/yy',	});
	$("#sh_todate").datepicker().datepicker("setDate", new Date());
	var shmode = 0;
	var shcus = 0;
}else{
	$("#sh_fromdate").datepicker();
	$("#sh_todate").datepicker();
	var shmode=1;
	var shcus= -1;
}

//console.log("pathname-------------"+pathname);
//if (pathname=="/admin/dashboard/sharerecords/1" || pathname=="/admin/dashboard/sharerecords/2" || pathname=="/admin/dashboard/sharerecords/3") {
if (pathname.match(/sharerecords/g)) {
	updateSharerecord();	
}

function updateSharerecord()
{
	$('#sh_feed').html("<p style='color:#3D8B3D;border:0px solid red;text-align:center;margin-top:25px;font-weight:bold;'>Loading.....</p>");
	var bythis = $("#sh_bythis").val();
	if (shmode==1 && !bythis)
	{
		$("#sh_bythis").val(4).trigger("chosen:updated");
		$("#sh_bythis").focus();
		bythis=4;
	}
	else
	{
		shmode=0;
	}
	//alert(bythis)
	if(bythis)
	{
		if (bythis!=4)
		{
			$(".shcusdate").hide();
			shcus = 0;
		}
		else
		{
			//alert("sssssss---------------")
			shcus++;
			$(".shcusdate").show();
			if (shcus==1 && shmode!=1)
			{
				//console.log("BYTHIS---------"+bythis)
				$("#sh_fromdate").val('');
				$("#sh_todate").val('')
				$('#sh_feed').html("<p style='color:#FF0000;border:0px solid red;text-align:center;margin-top:25px;font-weight:bold;'>Please check your custom dates</p>");
				return false;
			}
			else if($("#a_fromdate").val()=='' || $("#a_todate").val()=='')
			{
				$('#sh_feed').html("<p style='color:#FF0000;border:0px solid red;text-align:center;margin-top:25px;font-weight:bold;'>Please check your custom dates</p>");
				return false;
			}
		}
	}
	else
	{
		$(".shcusdate").show();
		$('#sh_fromdate').datepicker({	dateFormat: 'dd/mm/yy',	defaultDate :'-14d'	});
		$("#sh_fromdate").datepicker('setDate','-14');
		$('#sh_todate').datepicker({	dateFormat: 'dd/mm/yy',	});
		$("#sh_todate").datepicker().datepicker("setDate", new Date());
	}
	var fromdate = $("#sh_fromdate").val();
	var todate = $("#sh_todate").val();
	//$('#sh_feed').html(fromdate+"============"+todate);
	//console.log("sharedrecordsupdate------>"+sharedrecordsupdate)
	if ($("#trainer_check").val()!="")
	{
		$.ajax(
		{
			url: siteUrl+"dashboard/sharedrecordsupdate",
			method: 'post',
			data: {	
				fdate : 	fromdate,
				tdate : 	todate,
				by		:	bythis,
				user_id : $("#user_id").val()
			},
			success: function(content)
			{
				if (content)
				{
					$('.dataTable').DataTable().destroy();
					$("#sh_feed").html(content);
					$('.shareDataTable').DataTable();
					$(".dataTables_filter").hide();
					$(".dataTables_length").hide();
				}
				else
				{
					$('#sh_feed').html("<p style='color:#FF0000;border:0px solid red;text-align:center;margin-top:25px;font-weight:bold;'>No data found</p>");
				}
				
			}
		});
	}
	
}
/*************************/
var actmode = 0;
function changeby(){
	actmode = 1;
	//alert(actmode+"----------------ACT Mode--------------"+bythis)
	updateActityfeed()
}
if ($('.amoteactions').length) {	$(".amoteactions").chosen({	disable_search: true	});	}
$('#a_fromdate').datepicker({	dateFormat: 'dd/mm/yy',	defaultDate :'-14d'	});
$("#a_fromdate").datepicker('setDate','-14');
$('#a_todate').datepicker({	dateFormat: 'dd/mm/yy',	});
$("#a_todate").datepicker().datepicker("setDate", new Date());
var acus = 0;
function updateActityfeed(){
	var bythis = $("#a_bythis").val();
	//alert(actmode+"--ACT--"+bythis)
	if (actmode==1 && !bythis) {
		$("#a_bythis").val(4).trigger("chosen:updated");
		$("#a_bythis").focus();
		bythis=4;
	}else{
		actmode=0;
	}
	//console.log(acus+"--------ACUS---------BYTHIS------"+bythis+"-------------actmode--------"+actmode)
	if(bythis){
		if (bythis!=4) {
			$(".acusdate").hide();
			acus = 0;
		}else{
			acus++;
			$(".acusdate").show();
			if (acus==1 && actmode!=1) {
				$("#a_fromdate").val('');
				$("#a_todate").val('')
				$('#act_feed').html("<p style='color:#FF0000;border:0px solid red;text-align:center;margin-top:25px;font-weight:bold;'>Please check your custom dates</p>");
				return false;
			}else if($("#a_fromdate").val()=='' || $("#a_todate").val()==''){
				$('#act_feed').html("<p style='color:#FF0000;border:0px solid red;text-align:center;margin-top:25px;font-weight:bold;'>Please check your custom dates</p>");
				return false;
			}
		}
	}else{
		//alert(actmode+"---------------")
		$(".acusdate").show();
		$('#a_fromdate').datepicker({	dateFormat: 'dd/mm/yy',	defaultDate :'-14d'	});
		$("#a_fromdate").datepicker('setDate','-14');
		$('#a_todate').datepicker({	dateFormat: 'dd/mm/yy',	});
		$("#a_todate").datepicker().datepicker("setDate", new Date());
	}
	//return false;
	var users = ($("#users").val())?$("#users").val():'';
	//console.log("Users-----------"+users)
	if (users.length>0) {
		$("#af_userids").val(users);
		$("#tf_userids").val(users);
	}else{
		$("#af_userids").val('');
		$("#tf_userids").val('');	
	}
	var feedtype = $("#a_feedtype").val();
	
	var fromdate = $("#a_fromdate").val();
	var todate = $("#a_todate").val();
	
	$("#af_limit").val(50);
	$("#af_showmore").val(0);
	var limit = $("#af_limit").val();
	var offset = $("#af_showmore").val();
	var userids = $("#af_userids").val();
	if (!userids) {
		$('#af_all').val(0);
		$('#act_feed').html("<p style='color:#FF0000;border:0px solid red;text-align:center;margin-top:25px;font-weight:bold;'>Please choose users</p>");
		return false;
	}
	var site = $("#af_site").val();
	//console.log("updateActityfeed ----- userids: "+userids+", offset:"+offset+", limit:"+limit+", site:"+site+", fdate : "+fromdate+", tdate :"+ todate+", by:"+bythis)
	actmode = 0;
	$.ajax({
		url: siteUrl+"dashboard/getfeeddetails?user_from=admin",
		method: 'post',
		data: {	userids: userids, offset:offset, limit:limit, site:site,
					fdate : fromdate,
					tdate : todate,
					by:bythis,
					feedtype:feedtype
		},
		success: function(content) {
			if (content) {
				$("#act_feed").html(content);
				$('div.activityinner').attr('style', 'height:262px;overflow-y:scroll');
			}else{
				$('#act_feed').html("<p style='color:#FF0000;border:0px solid red;text-align:center;margin-top:25px;font-weight:bold;'>No data found</p>");
			}
			
			//$('#show_btn').animate({scrollBottom: $("#show_btn").offset().top},'slow');
			
			/*
			var WH = $(window).height();  
			var SH = $('body').prop("scrollHeight");
			//console.log(WH+"------------"+SH)
			$('html, body').stop().animate({scrollTop: SH-WH}, 1000);
			*/
			if (!content) {
				$("#show_btn").hide();
			}
		}
	});
}