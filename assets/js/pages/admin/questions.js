$(".questionreq").tinyToggle();
$(".questionreq1").tinyToggle();

$(document).ready(function () {
	$('.tt').on('click',function (e) {
		var switchOn;
		var switchVal;
		switchOn = $(this).find('i').hasClass('tt-switch-on');
		switchVal = $(this).find('input').val();
		var s  = switchVal.split("_");
		
		if (s.length>1) {
			var rq = 0;
			if(switchOn == true && $("#isrequired"+s[0]).val()==0) {
				//alert('Req checked-'+switchVal);
				rq = 1;
			}
			$("#isrequired"+s[0]).val(rq);
			//alert("Rq---------"+rq+"#########################"+$("#isrequired"+s[0]).val())
			
			$.ajax({
			url : siteUrl+"questions/questionupdates",
				cache: false,
				type: "POST",
				data : {
					id : s[0],
					req : rq,
					type: 'required'
				},
				success : function(content){
					
				}
			});
			
		}else{
			var st = 1;
			if(switchOn == true) {
				//alert('checked-'+switchVal);
				st=0;	
			}
			//alert("St---------"+st)
			$.ajax({
			url : siteUrl+"questions/questionupdates",
				cache: false,
				type: "POST",
				data : {
					id : switchVal,
					type:"status",
					status:st
				},
				success : function(content){
					
				}
			});
		}
		return false;
	});
	
	
});
/*
if ($('.qaction').length) {
   $(".qaction").chosen({
      disable_search: true
   });
}
if ($('.moteactions').length) {
   $(".moteactions").chosen({
      disable_search: true
   });
}

if ($('#answer_field').length) {
   $("#answer_field").chosen({
      disable_search: true
   });
}
*/
$("#qselectall").click(function() {
   $('.qselect').not(this).prop('checked', this.checked);
});
$(".qselect").click(function() {
   if ($(".qselect").length == $(".qselect:checked").length) {
      $("#qselectall").attr("checked", "checked");
   } else {
      $("#qselectall").removeAttr("checked");
   }
});
$(".checkselect").click(function(e) {
   var chk = $(this).closest("tr").find("input:checkbox").get(0);
   if (e.target != chk) {
      chk.checked = !chk.checked;
   }
});



/*sequence sorting*/
$('#tabledrop').sortable({
	tolerance: 'pointer',
	revert: 'invalid',
	placeholder: '<tr class="sortableListsHint"><td colspan="5" style="display:block;width:100%;border:1px solid #ededed;height:25px;background-color:#ededed; border-radius: 3px;"></td></tr>',
	group: 'serialization',
	forceHelperSize: true,
	forcePlaceholderSize: true,
	handle: '.tab-panel-draggable',
	axis: 'y',
	stop: function(event, ui) {
		console.log("stop");
		//var data = group.sortable("serialize").get();
		var data = []; 
		$('#tabledrop tr').each( function(e) {
			//data.push( $(this).attr('id')  + '=' + ( $(this).index() + 1 ) );
			data.push( $(this).attr('id') );
		});
		//alert(data)
		$.ajax({
			url : siteUrl+"questions/changequestionorder",
			cache: false,
			type: "POST",
			data : {
				data : data
			},
			success : function(content){
				window.location.reload();
			}
		});
	}
}).disableSelection();
/*sequence sorting*/

/*
$("#tabledrop").addClass('sTreeBaseQuestion');
$("#tabledrop").addClass('bgC4Question');
var groups = $( "#tabledrop" ).sortable({
	placeholder: '<tr class="sortableListsHint"><td colspan="5" style="display:block;width:100%;border:1px solid #ededed;height:25px;background-color:#ededed; border-radius: 3px;"></td></tr>',
	groups: 'serialization',
	itemSelector:'tr',
	containerSelector : 'tbody',
	delay: 500,
	onDrop: function ($item, container, _super) {
		var data = groups.sortable("serialize").get();
		//alert(data)
		_super($item, container);
		//alert("Row Result  ---"+data);
		
		$.ajax({
			url : siteUrl+"questions/changequestionorder",
			cache: false,
			type: "POST",
			data : {
				data : data
			},
			success : function(content){
				window.location.reload();
			}
		});
		
	}
});
*/
function goto_qaction(sqid, arg) {
	//console.log("Test Doc------------------------"+arg)
	$(".qaction").select2('val', '');
	//$(".qaction").val("").trigger('chosen:updated');
	if (arg == 'delete') {
      var r = confirm("Are you sure to delete?");
      if (r) {
         $.ajax({
            url: siteUrl + "questions/removequestion",
            method: 'post',
            data: {
               id: sqid
            },
            success: function(content) {
               $("tr#row-" + sqid).remove();
					var seq = $('#qseq').val();
					seq = parseInt(seq)-1;
					$('#qseq').val(seq);
					window.location.reload();
            }
         });
      }
   }else if (arg == 'edit') {
		$("#questionsModal").modal("show");
		$('#questions').val($('#q_'+sqid).html());
		$('#questions').focus();
		
		//$('#answer_field').val($('#answer_field'+sqid).val());
		$("#answer_field").select2('val', $('#answer_field'+sqid).val());
		//$('#answer_field').trigger("chosen:updated");
		
		var val =parseInt($('#answer_field'+sqid).val());
		console.log("Edit Answer fields----"+val+"---------type of----"+typeof(val))
		
		if (val==5) {
			
			$(".input_slider").show();
			$('#min_val').val($('#min_val'+sqid).val());
			$('#max_val').val($('#max_val'+sqid).val());
		}else if (val==1 || val==2) {
			$(".placeholder").show();
			//alert($('#placeholder_text'+sqid).val())
			$('#placeholder_text').val($('#placeholder_text'+sqid).val());
		}
		else{
			$(".placeholder").hide();
			$(".input_slider").hide();
			$('#min_val').val('');
			$('#max_val').val('');
		}
		
		$("#isrequired").prop('checked', ($("#isrequired"+sqid).val()==1)?true:false );
		
		$('#sqid').val(sqid);
	}
	else if (arg == 'option') {
		
		$("#questionoptionsModal").modal("show");
		$("#option").show();
		$("#option").val('');
		$("#option").focus();
		$(".btn-primary").show();
		//$('#questions').val($('#q_'+sqid).html());
		$('#sqid').val(sqid);
		$('#qid').html($('#q_'+sqid).html());
		$.ajax({
			url: siteUrl + "questions/getquestionoption",
			method: 'post',
			data: {
				id: sqid
			},
			success: function(content) {
				//alert(content);
				$("#sTreequestion").html('');
				$("#sTreequestion").addClass('sTreeBaseQuestion');
				//$("#sTreequestion").addClass('bgC4Question');
				if (content) {
					var JSONArray = $.parseJSON(content);
					if (JSONArray.length > 0) {
						var str = '';
						var j=0;
						for (var i = 0; i < JSONArray.length; i++) {
							j++;
							str += "<li id='option"+JSONArray[i].id+"_"+j+"' class='bgC4Question' data-id='option"+JSONArray[i].id+"_"+j+"' >";
							str += "<i class='fa  fa fa-bars panel-drag'        style='cursor:all-scroll;' ></i>&nbsp;&nbsp;&nbsp;";
							//str += "<i class='fa  fa-pencil-square-o' style='cursor:pointer' onclick='editoption(\""+JSONArray[i].option+"\","+sqid+","+JSONArray[i].id+")'></i>&nbsp;&nbsp;&nbsp;";
							//str += "<i class='fa  fa fa-trash'        style='cursor:pointer' onclick='deleteoption("+sqid+","+JSONArray[i].id+","+j+")'></i>&nbsp;&nbsp;&nbsp;";
							str += "<input type='text' class='form-control input-sm' value='"; 
							str += JSONArray[i].option;
							str += "' style='width:90%;margin-top:-29px;margin-left:23px;'";
							str += 'onkeypress="return qoptionevent(event,this,'+JSONArray[i].id+')"';
							str += "/>";
							str += "<button  class=\"btn btn-danger add-more-qualifications\" type=\"button\" onclick='deleteoption("+sqid+","+JSONArray[i].id+","+j+")' style=\"margin-left: 90%;margin-top: -50px\">-</button>";
							str += "</li>";
						}
						$("#seq").val(JSONArray.length);
						if (str) {
							//alert(str)
							$("#sTreequestion").html(str);
						}
					}
					
					
					/*sequence sorting*/
					$('#sTreequestion').sortable({
						tolerance: 'pointer',
						revert: 'invalid',
						placeholder: 'bgC4Question dropspace',
						group: 'serialization',
						forceHelperSize: true,
						forcePlaceholderSize: true,
						handle: '.panel-drag',
						axis: 'y',
						start: function(event, ui) {
							//console.log("Start");
						},
						change: function(event, ui) {
							//console.log("Change");
						},
						update: function(event, ui) {
							//console.log("sdfsdf");
						},
						stop: function(event, ui) {
							//console.log("stop");
							//var data = group.sortable("serialize").get();
							var data = []; 
							$('#sTreequestion li').each( function(e) {
								//data.push( $(this).attr('id')  + '=' + ( $(this).index() + 1 ) );
								data.push( $(this).attr('id') );
							});
							//alert(data)
							$.ajax({
								url : siteUrl+"questions/changeorder",
								cache: false,
								type: "POST",
								data : {
									id : sqid,
									data : data
								},
								success : function(content){
									//window.location.reload();
									$(".orderoption").html("Your Option order was changed Successfully!!!");
									setTimeout(function(){$(".orderoption").html('')},2000);
								}
							});
						}
					}).disableSelection();
					/*sequence sorting*/
					
					
					
					
					
					/*
					var group = $( "#sTreequestion" ).sortable({
						placeholder: '<li class="sortableListsHint" style="display:block;width:100%;border:1px solid #ededed;height:25px;background-color:#ededed; border-radius: 3px;"></li>',
						group: 'serialization',
						itemSelector:'li',
						containerSelector : 'ul',
						delay: 500,
						onDrop: function ($item, container, _super) {
						  var data = group.sortable("serialize").get();
						  //alert(data)
						  _super($item, container);
						  if($('li').parent("ul").hasClass("bgC4_ul")){
								$('ul.bgC4_ul').hide();
								$('ul.bgC4_ul li').remove();
							}
							if($('li').parent("ul").hasClass("bgC4_ul_parent")){
								$('ul.bgC4_ul_parent').hide();
								$('ul.bgC4_ul_parent li').remove();
							}
							//alert("Result  ---"+data);
							$.ajax({
								url : siteUrl+"questions/changeorder",
								cache: false,
								type: "POST",
								data : {
									id : sqid,
									data : data
								},
								success : function(content){
									//window.location.reload();
									$(".orderoption").html("Your Option order was changed Successfully!!!");
									setTimeout(function(){$(".orderoption").html('')},2000);
								}
							});
						}
					});
					*/
					
				}
			}
		});
	}
	
}

function addquestions(){
	var q    = $('#questions').val();
	var sqid = $('#sqid').val();
	var seq = $('#qseq').val();
	var isreq = ($("#isrequired").is(':checked'))?1:0;
	var val = parseInt($("#answer_field").val());
	var placeholder_text = ($('#placeholder_text').val() && (val==1 || val==2 ))?$('#placeholder_text').val():'';
	
	seq = parseInt(seq)+1;
	if (!q) {
		alert("Please enter your question")
		return false;
	}
	//alert($("#answer_field").val())
	if (val==5) {
		var min = parseInt($("#min_val").val());
		var max = parseInt($("#max_val").val());
		//alert(min+"---"+max)
		if (!min)
		{
			alert("Please enter min value")
			return false;
		}
		else if (!max)
		{
			alert("Please enter max value")
			return false;
		}
		else if (min && max && min>=max){
			alert("Please enter min & max values")
			return false;
		}
	}
	
	//alert(placeholder_text)
	
	//return false;
	
	var answer_field  = (val)?val:4;
	
	$.ajax({
		url: siteUrl + "questions/add_edit_questions",
		method: 'post',
		data: {
			question: q,
			isreq:isreq,
			seq:seq,
			answer_field:answer_field,
			min_val : (min)?min:'',
			max_val : (max)?max:'',
			placeholder_text: placeholder_text,
			id: (sqid)?sqid:''
		},
		success: function(content) {
			$("#questionsModal").modal("hide");
			if (sqid) {
				$('#q_'+sqid).html(q);
				$('#answer_field_'+sqid).val(answer_field);
			}
			$('#questions').val('');
			window.location.reload();
		}
	});
}
function addquestionoptions(){
	var option    = $('#option').val();
	var sqid = $('#sqid').val();
	var seq = $('#seq').val();
	seq = parseInt(seq)+1;
	
	if (!option) {
		alert("Please enter your options")
		return false;
	}
	$('#option').val('');
	$.ajax({
		url: siteUrl + "questions/add_questionoptions",
		method: 'post',
		data: {
			sqid: sqid,
			option: option,
			sequence: seq
		},
		success: function(content) {
			var str = '';
			/*
			str += "<li id='option"+content+"' >";
			str += "<i class='fa  fa fa-bars'        style='cursor:all-scroll;' ></i>&nbsp;&nbsp;&nbsp;";
			str += "<i class='fa  fa-pencil-square-o' style='cursor:pointer' onclick='editoption(\""+option+"\","+sqid+","+content+")'></i>&nbsp;&nbsp;&nbsp;";
			str += "<i class='fa  fa fa-trash'        style='cursor:pointer' onclick='deleteoption("+sqid+","+content+")'></i>&nbsp;&nbsp;&nbsp;";
			str += option;
			str += "</li>";
			*/
			
			str += "<li id='option"+content+"_"+seq+"' class='bgC4 item_workout_noclick'  data-module=\"item_workout\"   data-id='option"+content+"_"+seq+"' >";
			str += "<i class='fa  fa fa-bars panel-drag'        style='cursor:all-scroll;' ></i>&nbsp;&nbsp;&nbsp;";
			//str += "<i class='fa  fa-pencil-square-o' style='cursor:pointer' onclick='editoption(\""+option+"\","+sqid+","+content+")'></i>&nbsp;&nbsp;&nbsp;";
			//str += "<i class='fa  fa fa-trash'        style='cursor:pointer' onclick='deleteoption("+sqid+","+content+")'></i>&nbsp;&nbsp;&nbsp;";
			str += "<input type='text' class='form-control input-sm' value='";
			str += option;
			str += "' style='width:90%;margin-top:-29px;margin-left:23px;' ";
			str += 'onkeypress="return qoptionevent(event,this,'+content+')"';
			str += "/>";
			str += "<button class=\"btn btn-danger\" onclick='deleteoption("+sqid+","+content+","+seq+")'  type=\"button\" style=\"margin-left: 90%;margin-top: -50px\">-</button>";
			str += "</li>";
			
			
			$("#sTreequestion").append(str);
			$('#seq').val(seq);
			$('#option').val('');
			//$("#questionoptionsModal").modal("hide");
			$(".orderoption").html("Your Option was updated Successfully!!!");
			setTimeout(function(){$(".orderoption").html('')},2000);
			//window.location.reload();
			
			$( "#sTreequestion" ).sortable({
				placeholder: "ui-state-highlight"
			});
			
			/*sequence sorting*/
			$('#sTreequestion').sortable({
				tolerance: 'pointer',
				revert: 'invalid',
				placeholder: 'bgC4Question dropspace',
				group: 'serialization',
				forceHelperSize: true,
				forcePlaceholderSize: true,
				handle: '.panel-drag',
				axis: 'y',
				start: function(event, ui) {
					//console.log("Start");
				},
				change: function(event, ui) {
					//console.log("Change");
				},
				update: function(event, ui) {
					//console.log("sdfsdf");
				},
				stop: function(event, ui) {
					//console.log("stop");
					//var data = group.sortable("serialize").get();
					var data = []; 
					$('#sTreequestion li').each( function(e) {
						//data.push( $(this).attr('id')  + '=' + ( $(this).index() + 1 ) );
						data.push( $(this).attr('id') );
					});
					//alert(data)
					$.ajax({
						url : siteUrl+"questions/changeorder",
						cache: false,
						type: "POST",
						data : {
							id : sqid,
							data : data
						},
						success : function(content){
							//window.location.reload();
							$(".orderoption").html("Your Option order was changed Successfully!!!");
							setTimeout(function(){$(".orderoption").html('')},2000);
						}
					});
				}
			}).disableSelection();
			/*sequence sorting*/
			
			/*
			var group = $( "#sTreequestion" ).sortable({
				placeholder: '<li class="sortableListsHint" style="display:block;width:100%;border:1px solid #ededed;height:25px;background-color:#ededed; border-radius: 3px;"></li>',
				group: 'serialization',
				itemSelector:'li',
				containerSelector : 'ul',
				delay: 500,
				onDrop: function ($item, container, _super) {
				  var data = group.sortable("serialize").get();
				  //alert(data)
				  _super($item, container);
				  if($('li').parent("ul").hasClass("bgC4_ul")){
						$('ul.bgC4_ul').hide();
						$('ul.bgC4_ul li').remove();
					}
					if($('li').parent("ul").hasClass("bgC4_ul_parent")){
						$('ul.bgC4_ul_parent').hide();
						$('ul.bgC4_ul_parent li').remove();
					}
					//alert("Result  ---"+data);
					$.ajax({
						url : siteUrl+"questions/changeorder",
						cache: false,
						type: "POST",
						data : {
							id : sqid,
							data : data
						},
						success : function(content){
							//window.location.reload();
							$(".orderoption").html("Your Option order was changed Successfully!!!");
							setTimeout(function(){$(".orderoption").html('')},2000);
						}
					});
				}
			});
			*/
			
		}
	});
}

function preview(sqid){
	$("#questionpreviewModal").modal("show");
	$('#pqid').html($('#q_'+sqid).html());
	$.ajax({
		url: siteUrl + "questions/getquestionoption",
		method: 'post',
		data: {
			id: sqid
		},
		success: function(content) {
			if (content) {
				var JSONArray = $.parseJSON(content);
				if (JSONArray.length > 0) {
					var str = '';
					for (var i = 0; i < JSONArray.length; i++) {
						str += "<li id='option"+JSONArray[i].id+"'>";
						str += JSONArray[i].option;
						str += "</li>";
					}
					if (str) {
						$("#psTree2").html(str);
					}
				}
			}
		}
	});
}


function previewall(){
	$("#previewall").html('');
	$("#previewquestionsModal").modal("show");
	$.ajax({
		url: siteUrl + "questions/getallquestionoption",
		method: 'post',
		data: {},
		success: function(content) {
			if (content) {
				$("#previewall").html(content);
				//alert($('.mact').length)
				//if ($('.mact').length) {
					//$(".mact").chosen({
						//disable_search: true
					//});
				//}
				
			}
		}
	});
}


function deleteoption(sqid,optid,wseq){
	var r = confirm("Are you sure to delete this option?");
	if (r) {
		var seq = $('#seq').val();
		$("#option"+optid+"_"+wseq).remove();
		$.ajax({
			url: siteUrl + "questions/removequestionoption",
			method: 'post',
			data: {
				id: sqid,
				oid: optid
			},
			success: function(content) {
				if (content) {
					
					seq = parseInt(seq)-1;
					$('#seq').val(seq);
					$(".orderoption").html("Your Option order was removed Successfully!!!");
					setTimeout(function(){$(".orderoption").html('')},2000);
				}
			}
		});
	}
}
function call_child(obj){
	var val = parseInt(obj.value);
	if(val==5){
		$(".input_slider").show();
	}else if(val==1 || val==2){
		$(".placeholder").show();
	}else{
		$(".placeholder").hide();
		$(".input_slider").hide();
	}
}
function editoption(opt,sqid,id){
	$("#sqid").val(sqid);
	$("#oid").val(id);
	$("#editoption").val(opt);
	$("#editoptionModal").modal("show");
}

function qoptionevent(e,obj,oid){
	var unicode=e.charCode? e.charCode : e.keyCode
	if(unicode==13){
		updateOption(oid,obj.value);
	}
}
function updateOption(oid,option){
	var sqid = $('#sqid').val();
	$.ajax({
		url: siteUrl + "questions/update_questionoptions",
		method: 'post',
		data: {
			id: oid,
			option: option,
		},
		success: function(content) {
			if (content) {
				//goto_qaction(sqid, "option");
				$(".orderoption").html("Your Option was updated Successfully!!!");
				setTimeout(function(){$(".orderoption").html('')},2000);
			}
		}
	});
}


function updatequestionoptions(){
	var option    = $('#editoption').val();
	var oid = $('#oid').val();
	var sqid = $('#sqid').val();
	if (!option) {
		alert("Please enter your options")
		return false;
	}
	$('#editoption').val('');
	
	//alert($('#seq').val());
	var str = '';
	str += "<i class='fa  fa fa-bars'        style='cursor:all-scroll;' ></i>&nbsp;&nbsp;&nbsp;";
	str += "<i class='fa  fa-pencil-square-o' style='cursor:pointer' onclick='editoption(\""+option+"\","+sqid+","+oid+")'></i>&nbsp;&nbsp;&nbsp;";
	str += "<i class='fa  fa fa-trash'        style='cursor:pointer' onclick='deleteoption("+sqid+","+oid+","+$('#seq').val()+")'></i>&nbsp;&nbsp;&nbsp;";
	str += option;
	$("#option"+oid+"_"+$('#seq').val()).html(str);
	
	$.ajax({
		url: siteUrl + "questions/update_questionoptions",
		method: 'post',
		data: {
			id: oid,
			option: option,
		},
		success: function(content) {
			if (content) {
				$("#editoptionModal").modal("hide");
				//$("#questionoptionsModal").modal("hide");
				$('#editoption').val('');
				goto_qaction(sqid, "option");
				$(".orderoption").html("Your Option was updated Successfully!!!");
									setTimeout(function(){$(".orderoption").html('')},2000);
				
			}
		}
	});
}
function numbersonly(e){
	var unicode=e.charCode? e.charCode : e.keyCode
	if (unicode!=8){ //if the key isn't the backspace key (which we should allow)
	if (unicode<48||unicode>57) //if not a number
		return false //disable key press
	}
}

function addQevent(e,obj){
	var unicode=e.charCode? e.charCode : e.keyCode
	if(unicode==13){
		addquestions();
		obj.value='';
	}else if (unicode==27) {
		obj.value='';
	}
}
function optionevent(e,obj){
	var unicode=e.charCode? e.charCode : e.keyCode
	if(unicode==13){
		addquestionoptions();
		obj.value='';
	}else if (unicode==27) {
		//alert(obj.id)
		obj.value='';
	}
}
function editoptionevent(e,obj){
	var unicode=e.charCode? e.charCode : e.keyCode
	if(unicode==13){
		updatequestionoptions();
		obj.value='';
	}
}
