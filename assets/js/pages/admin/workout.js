$("#wkoutselectall").click(function() {
   $('.wkoutselect').not(this).prop('checked', this.checked);
});
$(".wkoutselect").click(function() {
   if ($(".wkoutselect").length == $(".wkoutselect:checked").length) {
      $("#wkoutselectall").attr("checked", "checked");
   } else {
      $("#wkoutselectall").removeAttr("checked");
   }
});
$(".checkselect").click(function(e) {
   var chk = $(this).closest("tr").find("input:checkbox").get(0);
   if (e.target != chk) {
      chk.checked = !chk.checked;
   }
});
$(document).ready(function() {
   // add multiple select / deselect functionality
   $("#agerange").slider({
      range: true,
      min: 15,
      max: 122,
      values: [15, 122],
      slide: function(event, ui) {
         $("#setagerange").val(ui.values[0] + "-" + ui.values[1]);
         $("#setage").html(ui.values[0] + " - " + ui.values[1]);
      }
   });
    $("input.checkboxdrag").bootstrapSwitch("size", "small");$("input.checkboxdrag").bootstrapSwitch("onText", " ");$("input.checkboxdrag").bootstrapSwitch("offText", " ");$("input.checkboxdrag").on("switchChange.bootstrapSwitch",function (event, state) {if(state === true){$("div.assign_group").removeClass("hide");}else { $("div.assign_group").addClass("hide");$("label#is_share_option").css("color","#666666"); }});$("div#sharedate").multiDatesPicker({dateFormat: "dd M yy",dayNamesMin: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"], onSelect: function(datestring, dp) {var selecteddate = $("div#sharedate").multiDatesPicker("getDates"); $("#sharedates").val(selecteddate); var seltext = convertText(selecteddate);
	$("div#sharedates_text").html(seltext); }});
   $('#tag_id').on('change', function(e) {
      var tags = $("#tag_id").val();
      $.ajax({
         url: siteUrl + "workout/gettaguser",
         method: 'post',
         data: {
            tag_id: tags
         },
         success: function(content) {
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
	//alert("dfgdg dfgd fg dfgd fg dfgdf");
   var val = $(this).val();
   var id = $(this).attr("id");
   switch (val) {
      case "sampleDefault":
         var data = new Array();
         $(".wkoutselect").each(function() {
            if ($(this).prop('checked') == true) {
               var id = $(this).attr("id");
               data.push($(this).val());
            }
         });
         $("#setsampledefaultModal").modal("show");
         $("select.de_sample_id").val(data);
         $("select.de_sample_id").select2();
         break;
      case "addworkouts":
         createNewworkout();
         break;
      case "addsampleworkouts":
         choose_makesamplewkout('');
         break;
      case "shareworkout":
         check_options("shareModal");
         break;
	  case "deletesampleworkouts": 
		var data = new Array();
		var folder_arr = new Array();
		$(".wkoutselect").each(function() {
		   if ($(this).prop('checked') == true) {
			  var id = $(this).attr("id"); //alert($(this).val());
			  data.push($(this).val());
			  var folder_id = $("#folder_"+$(this).val()).val(); //alert(folder_id);
			  if(folder_id !=''){
				folder_arr.push(folder_id);	
			  }	
		   }
		});
		
		if (data.length > 0) {
			   delete_sample(data,folder_arr, 'multiple');
            } else {
               alert("Please select workout plans");
            }
		break;
		
	  case "deleteworkouts":
		 var data = new Array();
		$(".wkoutselect").each(function() {
		   if ($(this).prop('checked') == true) {
			  var id = $(this).attr("id");
			  data.push($(this).val());
		   }
		});
		
		if (data.length > 0) {
		   delete_workouts(data, 'multiple');
		} else {
		   alert("Please select workout plans");
		}
	  break;
	  
      case "tagworkouts":
         var data = new Array();
         $(".wkoutselect").each(function() {
            if ($(this).prop('checked') == true) {
               var id = $(this).attr("id");
               data.push($(this).val());
            }
         });
         if (data.length > 0) {
            $('.moteactions').val('').trigger('liszt:updated');
            tagworkout('multiple', data);
         } else {
            alert("Please select workout plans");
         }
         break;
      case "cpytosample":
         var data = new Array();
         $(".wkoutselect").each(function() {
            if ($(this).prop('checked') == true) {
               var id = $(this).attr("id");
               data.push($(this).val());
            }
         });
         if (data.length > 0) {
            cpytosample(data,0,"mywkout");
         } else {
            alert("Please select workout plans");
         }
         break;
		case "cpytodefault":
         var data = new Array();
         $(".wkoutselect").each(function() {
            if ($(this).prop('checked') == true) {
               var id = $(this).attr("id");
               data.push($(this).val());
            }
         });
         if (data.length > 0) {
            cpytosample(data,1,"mywkout");
         } else {
            alert("Please select workout plans");
         }
         break;
		
		case "cpysharedSample":
         var data = new Array();
         $(".wkoutselect").each(function() {
            if ($(this).prop('checked') == true) {
               var id = $(this).attr("id");
               data.push($(this).val());
            }
         });
         if (data.length > 0) {
            cpytosample(data,0,"mysharedwkout");
         } else {
            alert("Please select workout plans");
         }
         break;
		case "cpysharedDefault":
         var data = new Array();
         $(".wkoutselect").each(function() {
            if ($(this).prop('checked') == true) {
               var id = $(this).attr("id");
               data.push($(this).val());
            }
         });
         if (data.length > 0) {
            cpytosample(data,1,"mysharedwkout");
         } else {
            alert("Please select workout plans");
         }
         break;
		
      case "reportEmail":
	    if($("#wkt_type").val()=='sample'){
			type='sample';
			if($("#default_type").val()=='1')
				type = 'default';
		}else if($("#wkt_type").val()=='shared'){
			type='shared';
		}else if($("#wkt_type").val()=='wkout'){
			type='wkout';
		}
		var data = new Array();
        $(".wkoutselect").each(function() {
            if ($(this).prop('checked') == true) {
               var id = $(this).attr("id");
               data.push($(this).val());
            }
        });
		$('#EmailModal div.response').text('');
		$('#EmailModal input#wkoutIds').val(data);
		$('#EmailModal input#wkouttype').val(type);
        $('#EmailModal').modal('toggle');
         break;
	  case "exportall" :
		    $("#exportModal input#selected_ids").val('');
			$('div.response').html('');
			$("#exportModal").modal("show");
	  break;
	  case "exportselected" :
			var data = new Array();
			$(".wkoutselect").each(function() {
				if ($(this).prop('checked') == true) {
				   var id = $(this).attr("id");
				   data.push($(this).val());
				}
			});
			$('div.response').html('');
			$("#exportModal input#selected_ids").val('');
			if (data.length > 0) {
				$("#selected_ids").val(data);
				$("#exportModal").modal("show");
			}else{
				alert("Please select Workout(s)");
			}
	  break;
      case "Excel":
	    var data = new Array();
        $(".wkoutselect").each(function() {
            if ($(this).prop('checked') == true) {
               var id = $(this).attr("id");
               data.push($(this).val());
            }
        });
		if($("#wkt_type").val()=='sample'){
			type='sample';
			if($("#default_type").val()=='1')
				type = 'default';
		}else if($("#wkt_type").val()=='shared'){
			type='shared';
		}else if($("#wkt_type").val()=='wkout'){
			type='wkout';
		}
         location.href = siteUrl + "workout/get_report_as_excel/?type="+type+"&wsid="+data;
         break;
      case "PDF":
	    var data = new Array();
        $(".wkoutselect").each(function() {
            if ($(this).prop('checked') == true) {
               var id = $(this).attr("id");
               data.push($(this).val());
            }
        });
		if($("#wkt_type").val()=='sample'){
			type='sample';
			if($("#default_type").val()=='1')
				type = 'default';
		}else if($("#wkt_type").val()=='shared'){
			type='shared';
		}else if($("#wkt_type").val()=='wkout'){
			type='wkout';
		}
        window.open(siteUrl + "workout/get_report_as_pdf/?type="+type+"&wsid="+data, '_blank');
        break;
      case "reportEmail1":
         $('#EmailModal').modal('toggle');
         break;
      case "Excel1":
		 ex_url= siteUrl + "workout/get_report_as_excel1/?d=0";
		 window.open(ex_url, '_blank');
         break;
      case "PDF1":
			var data = new Array();
			$(".wkoutselect").each(function() {
            if ($(this).prop('checked') == true) {
               var id = $(this).attr("id");
               data.push($(this).val());
            }
         });
		 if (data && data.length>0) {
			window.open(siteUrl + "workout/get_report_as_pdf1/?d=0&wsid="+data, '_blank');
		 }else{
			alert("Please select liseted items to export");
		 }
         break;
		case "Excel2":
		 ex_url= siteUrl + "workout/get_report_as_excel2";
		 window.open(ex_url, '_blank');
         break;
		case "PDF2":
			var data = new Array();
			$(".wkoutselect").each(function() {
            if ($(this).prop('checked') == true) {
               var id = $(this).attr("id");
               data.push($(this).val());
            }
         });
		 if (data && data.length>0) {
			window.open(siteUrl + "workout/get_report_as_pdf2/?wsid="+data, '_blank');
		 }else{
			alert("Please select liseted items to export");
		 }
         break;
      default:
         break;
   }
   $(".moteactions").select2('val', '');
});
function listexport(arg){
	if($("#wkt_type").val()=='sample'){
		type='sample';
		if($("#default_type").val()=='1')
			type = 'default';
	}else if($("#wkt_type").val()=='shared'){
		type='shared';
	}else if($("#wkt_type").val()=='wkout'){
		type='wkout';
	}
	var datauids = ($("#selected_ids").val()) ? $("#selected_ids").val() : '';
	data = '?wsid='+datauids+'&type='+type;
	if (arg=='pdf') {
		window.open(siteUrl + "workout/get_report_as_pdf/"+data, '_blank');
	}else if (arg=='excel') {
		location.href = siteUrl + "workout/get_report_as_excel/"+data;
	}
	$("#selected_ids").val('');
	$("#selected_roles").val('');
}
function email_workout_report_submit() {
   if ($.trim($('#email_address').val()) == '') {
      $('.form-error').html('Please enter email address');
      return false;
   }
   $.ajax({
      url: siteUrl + 'workout/report_email',
      data: $('#email_report_frm').serialize(),
      type: 'POST',
      async: false,
      success: function(data) {
         if (data == 1) {
            $('#email_address').val('');
            $('.response').html('Report sent successfully.');
            $('.response').css('color', 'green');
            setTimeout(function() {
               $('.response').html('');
               $('#EmailModal').modal('hide');
            }, 5000);
         } else if (data == 'no_data') {
            $('#email_address').val('');
            $('.response').html('No data found.');
            $('.response').css('color', 'red');
         } else {
            $('#email_address').val('');
            $('.response').html('Oops! Try again later.');
            $('.response').css('color', 'red');
         }
         return false;
      }
   });
   return false;
}

function email_workout_report_submit1() {
   if ($.trim($('#email_address').val()) == '') {
      $('.form-error').html('Please enter email address');
      return false;
   }
   $.ajax({
      url: siteUrl + 'workout/report_email1',
      data: $('#email_report_frm').serialize(),
      type: 'POST',
      async: false,
      success: function(data) {
         if (data == 1) {
            $('#email_address').val('');
            $('.response').html('Report sent successfully.');
            $('.response').css('color', 'green');
            setTimeout(function() {
               $('.response').html('');
               $('#EmailModal').modal('hide');
            }, 5000);
         } else if (data == 'no_data') {
            $('#email_address').val('');
            $('.response').html('No data found.');
            $('.response').css('color', 'red');
         } else {
            $('#email_address').val('');
            $('.response').html('Oops! Try again later.');
            $('.response').css('color', 'red');
         }
         return false;
      }
   });
   return false;
}

function get_recipient(obj) {
   var res = obj.value;
   //alert(selectdata);
   var data = JSON.parse(selectdata);
   $("#subscr_code").val(res);
   if ($("#" + res + "_id").val() == '') {
      var subscription_list = '';
   } else {
      var subscription_list = ($("#" + res + "_id").val()).split(',');
   }
   if (data[res].length > 0) {
      $("#subscriber_id option").remove();
      for (var i = 0; i < data[res].length; i++) {
         id = data[res][i].id;
         name = data[res][i].user_fname + " " + data[res][i].user_lname;
         if (subscription_list.length > 0 && $.inArray(id, subscription_list) == -1) {
            $('#subscriber_id').append($('<option>', {
               value: id,
               text: name
            }));
         } else if (subscription_list.length == 0) {
            $('#subscriber_id').append($('<option>', {
               value: id,
               text: name
            }));
         }
      }
   }
}
$('.resetserach').on("click", function() {
   $('.searchtext').val('');
   $(':checkbox').prop('checked', false).removeAttr('checked');
   $(':checkbox').prop('checked', false).removeAttr('checked');
   $('.checked').removeClass('checked');
   $('select#fsortby').val('3');
   var $form = $('.top-srch-frm');
   $form.find('[name="pageval"]').val($(this).text());
   $form.attr("action", $(this).attr('href'));
   $form.submit();
});

function fiters_user() {
   $("#recipientsfilterModal").modal("show");
   $("select.subscribername").select2().bind('change', function() {
      //alert("======innerpopup====="+$(this).val());
   });
   $("select.tag_id").select2();
   $("select.gender").select2();
}

function insertfilterrecipients() {
   var setagerange = $("#setagerange").val();
   var gender = $("#gender").val();
   var subscriberid = $("#subscribername").val();
   var str = $("#subscriber_id").val();
   if (str) {
      for (var f = 0; f < str.length; f++) {
         subscriberid.push(str[f]);
      }
   }
   $.ajax({
      url: siteUrl + "workout/filtersubscribers",
      method: 'post',
      data: {
         subscriberid: subscriberid,
         gender: gender,
         setagerange: setagerange
      },
      success: function(content) {
         $("#recipientsfilterModal").modal("hide");
         var JSONArray = $.parseJSON(content);
         if (JSONArray.length > 0) {
            var data = [];
            for (var i = 0; i < JSONArray.length; i++) {
               id = JSONArray[i].id;
               text = JSONArray[i].text;
               data.push(id);
            }
            $("select.subscriber_id").val(data); //Set Selct2 val By VSS56
            $("select.subscriber_id").select2();
            //select2-search-field
            $("select#subscribername").select2('val', ['All']);
            $("select#gender").select2('val', ['All']);
            $("select#setagerange").select2('val', ['All']);
            $("select#tag_id").select2('val', ['All']);
         } else {
            $("select#subscribername").select2('val', ['All']);
         }
      }
   });
}
/*
function  get_append_data(){
	var str = $("#sub_id").val().split(",");
	var subscriberid = new Array();
	for(var f=0; f<str.length; f++){
		//data.push(str[f]);
		subscriberid.push(str[f]);
	}
	if ($("#sub_id").val()!="") {
		$.ajax({
			url: siteUrl + "workout/filtersubscribers",
			method: 'post',
			data: {
				subscriberid: subscriberid,
				gender: "",
				setagerange: ''
			},
			success: function(content) {
				alert(content)
				var JSONArray = $.parseJSON(content);
				if (JSONArray.length > 0) {
					//$("#subscriber_id").multiselect("refresh");
					var data = [];
					for (var i = 0; i < JSONArray.length; i++) {
						id = JSONArray[i].id;
						text = JSONArray[i].name;
						data.push(id);
						//data.push({id:id, text:text});
						
						//alert(text)
						//alert($("#s2id_subscriber_id ul .select2-search-field").find('li .remove'+id));
						
						//$("#s2id_subscriber_id ul .select2-search-field").before('<li class="select2-search-choice remove'+id+'"><div>'+text+'</div><a class="select2-search-choice-close" tabindex="-1" onclick="remove_tag('+id+')" href="#"></a></li>');
						
					}
					//$("select.subscriber_id").val(data);
					//$("select.subscriber_id").select2();
					
					
					
					
					
				}
			}
		});
	}
}

function remove_tag(id){
	var str = $("#sub_id").val().split(",");
	var subscriberid = new Array();
	for(var f=0; f<str.length; f++){
		if (id!=str[f]) {
			subscriberid.push(str[f]);
		}else{
			console.log(("#s2id_subscriber_id ul .select2-search-field li .remove"+id+" "))
			$("#s2id_subscriber_id ul .select2-search-field li .remove"+id+" ").remove();
		}
	}
	$("#sub_id").val(subscriberid);
	//get_append_data();
}
*/
function goto_action(wkoutid, folder_id, arg) {
	console.log("Goto Action--->"+arg+"\nWkout--->"+wkoutid+"\nFolder--->"+folder_id);
   $(".wkoutaction").select2('val', '');
   // $(".wkoutaction").val("").trigger('chosen:updated');
   if (arg == 'duplicate') {
      $("#workout_id").val(wkoutid);
      $("#workout_type").val("");
      $("#duplicatewkout").submit();
   }
	else if (arg == 'sharedduplicate') {
      $("#workout_id").val(wkoutid);
      $("#workout_type").val("shared");
      $("#duplicatewkout").submit();
   }
	else if (arg == 'sample duplicate') {
      $("#workout_id").val(wkoutid);
      $("#workout_type").val("sample");
		$("#default_status").val(0);
      $("#duplicateModal").modal("show");
      //$("#duplicatewkout").submit();
   }
	else if (arg == 'default duplicate') {
      $("#workout_id").val(wkoutid);
      $("#workout_type").val("default");
		$("#default_status").val(1);
      $("#duplicateModal").modal("show");
      //$("#duplicatewkout").submit();
   }
	else if (arg == "sampleDefault") {
      $("#setsampledefaultModal").modal("show");
      $("select.de_sample_id").val(wkoutid);
      $("select.de_sample_id").select2();
   } else if (arg == 'print') {
      var url = siteUrl + "print/workouts/?type=workout&id=" + wkoutid;
      window.open(url, '_blank');
   } else if (arg == 'sampleprint') {
      var url = siteUrl + "print/workouts/?type=sampleworkout&id=" + wkoutid;
      window.open(url, '_blank');
   } else if (arg == 'share') {
	    var data = new Array();				
		$('input.checkboxdrag').bootstrapSwitch('state', false);
		$('div#sharedate').multiDatesPicker('resetDates', 'picked');
		$("#sharedates").val('');
		$("div#sharedates_text").html('');
		data.push(wkoutid);
		$("select.wkout_id").val(data);
		$("select.wkout_id").select2();
		$("select.subscriber_id").select2('val', ['All']);
		$("textarea#message").val('');
		$("select.subscriber_id").select2();
		$("#shareModal").modal('show');
   } else if (arg == 'delete') {
      delete_workouts(wkoutid, 'single');
   } else if (arg == 'deletesample') { 
     delete_sample(wkoutid, folder_id, 'single');
   } else if(arg == 'deletedefault'){
     delete_default(wkoutid, folder_id, 'single');
   } else if(arg == 'deleteshared'){
     delete_shared(wkoutid, folder_id, 'single');
   }
	else if (arg == 'tag') {
      tagworkout('single', wkoutid);
   } else if (arg == 'cpytosample') {
      cpytosample(wkoutid,0,"mywkout");
   } else if (arg == 'sampledefaulthide'){
	  defaulthideWkout(wkoutid,0);
   }else if (arg == 'cpytodefault') {
      cpytosample(wkoutid,1,"mywkout");
   }
	else if (arg == 'cpysharedSample') {
      cpytosample(wkoutid,0,"mysharedwkout");
   }
	else if (arg == 'cpysharedDefault') {
      cpytosample(wkoutid,1,"mysharedwkout");
   }
	
	else if (arg == 'edit') {
      window.location.href = siteUrl + "workout/edit/" + wkoutid + "?act=edit";
   } else if (arg == 'sampleedit') {
	  var default_status = $("#default_type").val();
      window.location.href = siteUrl + "workout/sampleedit/" + wkoutid + "?act=edit&d="+default_status;
   } else if (arg == 'Preview') {
      getworkoutpreview(wkoutid);
   }else if (arg == 'more' || arg == 'sample_more') {
      $("#moreModal").modal('show');
      $("select.wk_status").val('1');
      ///	$("select.featured").val('0');
      $("select.wk_status").select2();
      assign_status_val(wkoutid, arg);
      //$("select.featured").val('1');
      $("select.featured").select2();
      $('.wkoutid').val(wkoutid);
   }else if (arg=="export") {
		enableExport(wkoutid,'');
	}else if (arg=="sampleexport") {
		enableExport(wkoutid,'sample');
	}else if (arg=="sharedexport") {
		enableExport(wkoutid,'shared');
	}
}


function delete_workouts(wkoutid, action_type){ //alert(action_type);
	
	var r = confirm("Are you sure to delete?");
      if (r) {
         $.ajax({
            url: siteUrl + "workout/removeworkout",
            method: 'post',
            data: {
               wkout_id: wkoutid,
			   action_type:action_type
            },
            success: function(content) {
			   if (action_type == 'multiple') {
					 $.each(wkoutid, function(index, value) {
						$('#row-' + value).remove();
					 });
				} else {
					$('#row-' + wkoutid).remove();
				}
            }
         });
      }
	
}	

function delete_default(wkoutid,folder_id,action_type){
	var cnf = confirm("Are you sure to delete?");
	if (cnf) {
		$.ajax({
			url: siteUrl + "workout/removeDefaultworkout",
			method: 'post',
			data: {
			   wkout_id: wkoutid,
			   action_type:action_type,
			   folder_id:folder_id
			},
			success: function(content) {
			    if (action_type == 'multiple') {
					 $.each(wkoutid, function(index, value) {
						$('#row-' + value).remove();
					 });
				} else {
					$('#row-' + wkoutid).remove();
				}
			}
		});
	}
	
}
function delete_sample(wkoutid,folder_id,action_type){
	var cnf = confirm("Are you sure to delete?");
	if (cnf) {
		$.ajax({
			url: siteUrl + "workout/removeSampleworkout",
			method: 'post',
			data: {
			   wkout_id: wkoutid,
			   action_type:action_type,
			   folder_id:folder_id
			},
			success: function(content) {
			    if (action_type == 'multiple') {
					 $.each(wkoutid, function(index, value) {
						$('#row-' + value).remove();
					 });
				} else {
					$('#row-' + wkoutid).remove();
				}
			}
		});
	}
	
}	
function delete_shared(wkoutid,folder_id,action_type){
	var cnf = confirm("Are you sure to delete?");
	if (cnf) {
		$.ajax({
			url: siteUrl + "workout/removeSharedworkout",
			method: 'post',
			data: {
			   wkout_id: wkoutid,
			   action_type:action_type,
			   folder_id:folder_id
			},
			success: function(content) {
			    if (action_type == 'multiple') {
					 $.each(wkoutid, function(index, value) {
						$('#row-' + value).remove();
					 });
				} else {
					$('#row-' + wkoutid).remove();
				}
			}
		});
	}
	
}

function set_sample_submit() {
   var de_sample_id = $("#de_sample_id").val();
   if (!de_sample_id) {
      $(".errormsg").html("Please select sample workout records");
      return false;
   } else {
      $(".errormsg").html("");
   }
   //alert(de_sample_id+"----"+de_sample_id.length)
   $("#workout_type").val("sample");
   $("#default_status").val(1);
   $('#setexedefaultModal').modal('hide');
   $("#workout_id").val(de_sample_id);
   $("#duplicatewkout").submit()
   return false;
   /*
   $.ajax({
   	url: siteUrl + "exercise/defaultUnitData",
   	type: 'POST',
   	dataType: 'json',
   	data: {
   		unit_id:exe_id,
   		//site_id:site_id
   	},
   	success: function(data) {
   		$('#setexedefaultModal').modal('hide');
   	}
   });
   */
}
function defaulthideWkout(wkoutid,default_status) {
   var wkoutids = new Array();
   if (!$.isArray(wkoutid)) {
      wkoutids.push(wkoutid);
   } else {
      wkoutids = wkoutid;
   }
	var asa = (default_status==0)?"sample":"default";
   var r = confirm("Are you Hide this "+asa+" workout?");
   if (r) {
      $.ajax({
         url: siteUrl + "workout/defaulthide",
         type: 'POST',
         data: {
            workouts: wkoutids,
            f_method: "defaultwkout",
			default_status:default_status
         },
         success: function(data) {
            if (data) {
					var pp = (default_status!=0)?"?d=1":'';
               window.location.href = siteUrl + "workout/sample"+pp;
            }
         }
      });
   }
}

function cpytosample(wkoutid,default_status,f_method) {
   var wkoutids = new Array();
   if (!$.isArray(wkoutid)) {
      wkoutids.push(wkoutid);
   } else {
      wkoutids = wkoutid;
   }
	var asa = (default_status==0)?"sample":"default";
   var r = confirm("Are you confirm copy to "+asa+" workout?");
   if (r) {
      $.ajax({
         url: siteUrl + "workout/cpytosample",
         type: 'POST',
         data: {
            workouts: wkoutids,
            f_method: f_method,
				default_status:default_status
         },
         success: function(data) {
            if (data) {
					var pp = (default_status!=0)?"?d=1":'';
               window.location.href = siteUrl + "workout/sample"+pp;
            }
         }
      });
   }
}

function tagworkout(type, wkoutid) {
   $('#cwkid').val(wkoutid);
   $('#tagmodal').modal('show');
   $.ajax({
      url: siteUrl + "ajax/getwkouttags",
      type: 'GET',
      dataType: 'json',
      data: {
         wkoutid: wkoutid
      },
      success: function(data) {
         if (data.success) {
            $('#tagnames').val(data.user_tags);
            tag_dropdown_workout('#tagnames', data.tags);
         }
      }
   });
}

function tag_dropdown_workout(element, datasource) {
   $(element).select2({
      tags: true,
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

function extract_preselected_ids(element) {
   var preselected_ids = [];
   var delimiter = ',';
   if (element.val()) {
      if (element.val().indexOf(delimiter) != -1) {
         $.each(element.val().split(delimiter), function() {
            preselected_ids.push({
               id: this
            });
         });
      } else {
         preselected_ids.push({
            id: element.val()
         });
      }
   }
   //alert(preselected_ids)
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
         });
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
$(".addwkouttags").click(function() {
   var wkoutid = $('#cwkid').val();
   var wid = wkoutid.split(',');
   var tags = $('#tagnames').val();
   var selecteddatas = $('#tagnames').select2('data');
   var tagstext = '';
   var a = [];
   $.each(selecteddatas, function(key, value) {
      a.push(value.text);
   });
   tagstext = a.join(', ');
   $('#wkouttable tr#row-' + wkoutid).find(".tagsection").html('');
   createupdatetags(wkoutid, tags);
});

function createupdatetags(wkoutid, tags) {
   //console.log(tags+"#####"+wkoutid)
   $.ajax({
      url: siteUrl + "ajax/WkoutAdduUdateTags",
      type: 'POST',
      dataType: 'json',
      data: {
         wkoutid: wkoutid,
         tags: tags
      },
      success: function(data) {
         if (data.success) {
            var JSONArray = data.tags;
            if (JSONArray.length > 0) {
               for (var i = 0; i < JSONArray.length; i++) {
                  $('#wkouttable tr#row-' + JSONArray[i].wkout_id).find(".tagsection").html(JSONArray[i].tag_title);
               }
               $("select.subscriber_id").val(data);
               $("select.subscriber_id").select2();
            }
            $('#tagmodal').modal('hide');
         }
      }
   });
}

function check_options(arg) {
	var data = new Array();
	$('input.checkboxdrag').bootstrapSwitch('state', false);
	$('div#sharedate').multiDatesPicker('resetDates', 'picked');
	$("#sharedates").val('');
	$("div#sharedates_text").html('');
    $(".wkoutselect").each(function() {
		if ($(this).prop('checked') == true) {
			data.push($(this).val());
		}
    });
    $("select.wkout_id").val(data);
    $("select.wkout_id").select2();
	$("select.subscriber_id").select2('val', ['All']);
	$("textarea#message").val('');
    $("select.subscriber_id").select2();
    $("#" + arg).modal('show');
}

function assign_status_val(wkoutid, page) {
   $.ajax({
      url: siteUrl + "workout/assign_status_val",
      type: 'POST',
      dataType: 'json',
      data: {
         wkoutid: wkoutid,
         page: page
      },
      success: function(data) {
         //alert(data.featured);
         //$("select.featured").val(data.featured);
         $("select.wk_status").val(data.status_id).trigger("change");
         $("select.featured").val(data.featured).trigger("change");
      }
   });
}
$(document).on('click', '.pagination a', function() {
   var $form = $('.top-srch-frm');
   $form.find('[name="pageval"]').val($(this).text());
   $form.attr("action", $(this).attr('href'));
   $form.submit();
   return false;
});

function updatestatus(page) {
   var wkoutid = $('.wkoutid').val();
   var wk_status = $('.wk_status').select2("val");
   var featured = $('.featured').select2("val");
   //var wkoutfilter = $('.wkfilter_val').val(); // alert(wkoutfilter);
   var method = '';
   $.ajax({
      url: siteUrl + "workout/WkoutUpdateStatus",
      type: 'POST',
      dataType: 'json',
      data: {
         wkoutid: wkoutid,
         wk_status: wk_status,
         featured: featured,
         page: page
      },
      success: function() {
         //alert("success");
         $("#moreModal").modal('hide');
         // window.location = self.location;
         //location.reload(false);
         $(".advnce-srch-frm").submit();
      }
   });
}

function saveshare() {
   var wkout_id = $("#wkout_id").val();
   var subscriber_id = $("#subscriber_id").val();
   var messageval = $("#message").val();
   var message = '';
   if (!wkout_id)
      message = "Please select atleast one Workout Plan.";
   if (!subscriber_id)
      message += "<br>Please choose atleast one Recipient.";
   if (!messageval)
      message += "<br>Please Enter Message.";
   if(($('input#is_share_assing[type="checkbox"]:checked').length > 0 && $('input#sharedates').val() =='') || message != ''){
	    if($('input#is_share_assing[type="checkbox"]:checked').length > 0 && $('input#sharedates').val() ==''){
			$('label#is_share_option').css('color',"red");
			message += "<br>Schedule Assignments option was selected, but no date(s) are highlighted.";
		}
		var contentHtml = '<div class="vertical-alignment-helper"><div class="modal-dialog modal-md"><div class="modal-content aligncenter"><form data-ajax="false" action="" method="post"><div class="modal-body opt-body"><div class="opt-row-detail"><div class="col-xs-12 pointer"><div class="col-xs-12">'+message+'</div></div></div></div><div class="modal-footer"><button data-role="none" data-ajax="false" type="button" data-dismiss="modal" class="btn btn-default">ok</button></div></form></div></div></div>';
		$('div#myOptionsModal').html(contentHtml).modal();
	   return false;
   }
   $('#shareModal').modal('hide');
   var from_wkout = $("#from_wkout").val();
   $.ajax({
      url: siteUrl + "workout/saveshare",
      method: 'post',
      data: {
         wkout_id: wkout_id,
         subscriber_id: subscriber_id,
         param: from_wkout,
         message: messageval,
		 wkout_dates : $('input#sharedates').val(),
		 is_share_assing : ($('input#is_share_assing[type="checkbox"]:checked').length > 0 ? 'on' : 'off')
      },
      success: function(content) {
         $("select.wkout_id").select2('val', ['All']);
         $("select.subscriber_id").select2('val', ['All']);
         $("#message").val('');
         if ($(".wkoutselect").length == $(".wkoutselect:checked").length) {
            $("#wkoutselectall").attr("checked", "checked");
         } else {
            $("#wkoutselectall").removeAttr("checked");
         }
      }
   });
}
function getsampleTemplateOfExerciseRecordAction(exerciseSetId) {
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
         content = strReplaceAll(content, 'getRelatedRecords', 'getsampleRelatedRecords');
         content = strReplaceAll(content, 'getExercisepreviewOfDay', 'getsampleExercisepreviewOfDay');
         $('#myOptionsModal').html(content);
         $('#myOptionsModal').modal();
      }
   });
}

function getsampleExercisepreviewOfDay(exerciseId, wkoutId) {
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
         content = strReplaceAll(content, 'getRelatedRecords', 'getsampleRelatedRecords');
         $('#FolderModal').html(content);
         $('#FolderModal').modal();
      }
   });
}

function getsampleRelatedRecords(xrid, EditFlag, oldxrId) {
   modalName = 'myOptionsModalExerciseRecord';
   if ($('div#exerciselib-model').length) EditFlag = true;
   $('#' + modalName).html();
   $.ajax({
      url: siteUrl_frontend + "search/getmodelTemplate",
      data: {
         action: 'relatedRecords',
         method: 'relatedRecords',
         xrid: xrid,
         id: oldxrId,
         modelType: modalName,
         editFlag: EditFlag,
      },
      success: function(content) {
         $('#' + modalName).html(content);
         $('#' + modalName).modal();
      }
   });
}

function getRateFromUser(xrId) {
   $('#myOptionsModalExerciseRecord').html();
   $.ajax({
      url: siteUrl_frontend + "search/getmodelTemplate/",
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

$('.fetch-record').click(function() {
   $('.tab-pane').css("display", "block"); // alert('css');
   $(".advnce-srch-frm").submit();
})
$(document).ready(function() {
   $('.type_chkbx').iCheck({
      checkboxClass: 'icheckbox_square-green',
      radioClass: 'iradio_square-green',
      increaseArea: '20%'
   });
});
$('body').on('click', '.filter_sub_btn', function(e) {
   var target = $(e.target);
   var title = target.closest('.bodycontent').attr("class").split(" ")[1];
   console.log(title);
   if (target.is('.select_all')) {
      //$('.'+title+' input[type=checkbox]').prop('checked', true);
      //$('.'+title+' input[type=checkbox]').next().addClass('tt-check-square').removeClass('tt-uncheck-square').css('color','#009933');
      $('.' + title + ' input[type=checkbox]').parent().addClass('checked');
      $('.' + title + ' input[type=checkbox]').parent().attr('aria-checked', true);
      $('.' + title + ' input[type=checkbox]').prop('checked', true);
      activeFilterBtns(title);
   } else {
      if (title == 'exerciselib') {
         if ($('.xr_target_selected').length > 0) {
            $('.xr_target_selected').closest('li').trigger('click');
         }
      } else {
         //$('.'+title+' input[type=checkbox]').prop('checked', false);
         //$('.'+title+' input[type=checkbox]').next().addClass('tt-uncheck-square').removeClass('tt-check-square').css('color','#999999');
         $('.' + title + ' input[type=checkbox]').parent().removeClass('checked');
         $('.' + title + ' input[type=checkbox]').parent().attr('aria-checked', false);
         $('.' + title + ' input[type=checkbox]').prop('checked', false);
         activeFilterBtns(title);
      }
   }
});

function activeFilterBtns(title) {
   if (title != 'bodycontent') {
      var visible_btn = $('.visible');
      var numChkd = $('.' + title + ' input:checked').length;
      if (numChkd > 0) {
         visible_btn.addClass('activeFilter');
      } else {
         visible_btn.removeClass('activeFilter');
      }
   } else {
      $('.activeFilter').removeClass('activeFilter');
   }
}


function choose_makesamplewkout(arg,foldid){
	switch(arg){
		case "mywkout":
			 break;
		case "mysharedwkout":
			 break;
		default:
			 $("#choose_sample").modal("show");
			 return false;
			 break;
   };
   //$("#f_method").val(arg);
	$.ajax({
		url: siteUrl + "workout/getwkouts",
		method: 'post',
		data: {
			action: arg,
			foldid: (foldid)?foldid:0
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
	var default_status = $("#default_type").val();
	var r = confirm("Are you confirm copy to sample workout?");
    if (r) {
        $.ajax({
            url: siteUrl + "workout/cpytosample",
            type: 'POST',
            data: {
                workouts: wkoutid,
                f_method: method,
					 fromAdmin : true,
					 default_status:default_status
            },
            success: function(data) {
                $('#listwkouts').modal("hide");
                $("#choose_sample").modal("hide");
               window.location.href = siteUrl + "workout/sample";
            }
        });
    }
}

