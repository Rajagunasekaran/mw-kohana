var tagarry = [];
$.ajax({
	url: siteUrl + 'ajax/tagnames?user_from=admin&cp=' + user_allow_page,
	dataType: 'json',
	async: false,
	encode: true,
	cache: false
}).done(function(data) {
	var taglist = [];
	if (data) {
		$.each(data.tagnames, function(i, val) {
			taglist.push({
				id: i,
				val: val
			});
		});
		tagarry = taglist;
	}
});
var tagnames = new Bloodhound({
	datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
	queryTokenizer: Bloodhound.tokenizers.whitespace,
	local: $.map(tagarry, function(tagname) {
		return {
			id: tagname.id,
			name: tagname.val
		};
	})
});
tagnames.initialize();
$('input.xru_Tags, input.xrtag-input').tagsinput({
	typeaheadjs: [{
		highlight: true,
	}, {
		name: 'tagnames',
		displayKey: 'name',
		valueKey: 'name',
		source: tagnames.ttAdapter()
	}],
	freeInput: true
});
$('input.exercisetags').tagsinput({
	itemValue: 'id',
	itemText: 'name',
	typeaheadjs: [{
		highlight: true,
	}, {
		name: 'tagnames',
		displayKey: 'name',
		source: tagnames.ttAdapter()
	}],
	freeInput: true
});
// if ($('.exerciseaction').length) {
// 	$(".exerciseaction").chosen({
// 		disable_search: true
// 	});
// }
// if ($('.exeselect').length) {
// 	$(".exeselect").chosen({
// 		disable_search: true
// 	});
// }
$(document).on('click', '.trigger-imgopt', function() {
	$('#btn_imgpreview').attr('onclick', "triggerImgPreviewModal('" + $(this).attr('data-imgtagid') + "');");
	$('#btn_imgclear').attr('data-clearid', $(this).attr('id'));
	$('#imageoption-modal').modal();
	if ($("#imginsert-btn").length) {
		$('#imginsert-btn').attr('data-imgtagid', $(this).attr('data-imgtagid'));
		$('#imginsert-btn').attr('data-hidnimgid', $(this).attr('data-hidnimgid'));
	}
});

function triggerImgPreviewModal(elemid) {
	var imgurl = $('#' + elemid).attr('src');
	if (imgurl != undefined) {
		$('#preview_featimg').html('<img alt="Feature Image" class="Preview_image" id="preview-featimg" src="' + imgurl + '"/>');
	} else {
		$('#preview_featimg').html('<i class="fa fa-file-image-o prevfeat"></i>');
	}
	$('#imageoption-modal').modal('hide');
	$('#previewimg-modal').modal();
}
$(document).on('click', 'a.edit-img', function(e) {
	e.preventDefault();
	e.stopImmediatePropagation();
	if ($('#parentFolderId').length && $('#subFolderId').length) {
		$('#parentFolderId').val('');
		$('#subFolderId').val('');
		triggerAjaxImgLibrary();
	}
	$('#imageoption-modal').modal('hide');
	$('#popupimglibrary-modal').modal({
		keyboard: "false",
		backdrop: "static"
	});
});
$(document).on('click', '.click-prev', function(e) {
	e.preventDefault();
	e.stopImmediatePropagation();
	var clicked = $(e.target);
	var uid = clicked.closest('tr').attr("id");
	//var xrID = '985';
	var uid = uid.split('-');
	var xrID = uid[1]; //alert(xrID);
	var title = $("#row-" + xrID + " .ex_name").text();
	emptyRecordData();
	//getXRcisepreviewOfDay(xrID, 0, $(this).text());
	getXRcisepreviewOfDay(xrID, 0, title);
});
$('body').on('change', '#musprim', function(e) {
	changeTargetOption(e.target);
	var target_id = $('#musprim').val();
	var target_html = $('#musprim :selected').html();
	var target = $('li.list_muscle.muscle_id-' + target_id);
	targetMuscle(target_html, target_id);
	targetHiLiteToggle(target);
});
$(document).on('click', 'li.list_muscle', function(e) {
	var target_vals = targetHiLiteToggle(e.target);
	var target_id = target_vals[0];
	var target_html = target_vals[1];
	targetMuscle(target_html, target_id);
});

function targetHiLiteToggle(x) {
	var target = $(x);
	var visible_btn = $('.visible');
	if (target.is('.xr_target_selected')) {
		targetHiLiteOff(target, visible_btn);
		var target_html = '';
		var target_id = 0;
	} else {
		var target_vals = targetHiLiteOn(target, visible_btn);
		var target_id = target_vals[0];
		var target_html = target_vals[1];
	}
	return [target_id, target_html];
}

function targetHiLiteOff(target, visible_btn) {
	$('li.list_muscle').removeClass('xr_target_selected');
	visible_btn.removeClass('activeFilter');
	$('input[name="target[]"]').val('');
}

function targetHiLiteOn(target, visible_btn) {
	$('li.list_muscle').removeClass('xr_target_selected');
	var target_id = target.attr('class').split('-').pop();
	var target_html = target.html();
	visible_btn.addClass('activeFilter');
	target.addClass('xr_target_selected');
	$('input[name="target[]"]').val(target_id);
	return [target_id, target_html];
}

function targetMuscle(target_html, target_id) {
	t_id = parseInt(target_id);
	switch (t_id) {
		case 1: //Abs
			$("#exercisemarkimage img").attr("src", siteUrl_frontend + 'assets/img/anatomy/anatomy_06.jpg');
			break;
		case 2: //abductors
			$("#exercisemarkimage img").attr("src", siteUrl_frontend + 'assets/img/anatomy/anatomy_16.jpg');
			break;
		case 3: //adductors
			$("#exercisemarkimage img").attr("src", siteUrl_frontend + 'assets/img/anatomy/anatomy_07.jpg');
			break;
		case 4: //biceps
			$("#exercisemarkimage img").attr("src", siteUrl_frontend + 'assets/img/anatomy/anatomy_04.jpg');
			break;
		case 5: //calves
			$("#exercisemarkimage img").attr("src", siteUrl_frontend + 'assets/img/anatomy/anatomy_18.jpg');
			break;
		case 6: //chest
			$("#exercisemarkimage img").attr("src", siteUrl_frontend + 'assets/img/anatomy/anatomy_03.jpg');
			break;
		case 7: //Forearm
			$("#exercisemarkimage img").attr("src", siteUrl_frontend + 'assets/img/anatomy/anatomy_05.jpg');
			break;
		case 8: //glutes
			$("#exercisemarkimage img").attr("src", siteUrl_frontend + 'assets/img/anatomy/anatomy_15.jpg');
			break;
		case 9: //hams
			$("#exercisemarkimage img").attr("src", siteUrl_frontend + 'assets/img/anatomy/anatomy_17.jpg');
			break;
		case 10: //lats
			$("#exercisemarkimage img").attr("src", siteUrl_frontend + 'assets/img/anatomy/anatomy_11.jpg');
			break;
		case 11: //low back
			$("#exercisemarkimage img").attr("src", siteUrl_frontend + 'assets/img/anatomy/anatomy_14.jpg');
			break;
		case 12: //mid back
			$("#exercisemarkimage img").attr("src", siteUrl_frontend + 'assets/img/anatomy/anatomy_13.jpg');
			break;
		case 13: //neck
			$("#exercisemarkimage img").attr("src", siteUrl_frontend + 'assets/img/anatomy/anatomy_01.jpg');
			break;
		case 14: //quads
			$("#exercisemarkimage img").attr("src", siteUrl_frontend + 'assets/img/anatomy/anatomy_08.jpg');
			break;
		case 15: //shoulders
			$("#exercisemarkimage img").attr("src", siteUrl_frontend + 'assets/img/anatomy/anatomy_02.jpg');
			break;
		case 16: //traps
			$("#exercisemarkimage img").attr("src", siteUrl_frontend + 'assets/img/anatomy/anatomy_10.jpg');
			break;
		case 17: //triceps
			$("#exercisemarkimage img").attr("src", siteUrl_frontend + 'assets/img/anatomy/anatomy_12.jpg');
			break;
		case 18: //feet
			$("#exercisemarkimage img").attr("src", siteUrl_frontend + 'assets/img/anatomy/anatomy_09.jpg');
			break;
		default:
			$("#exercisemarkimage img").attr("src", siteUrl_frontend + 'assets/img/anatomy/anatomy.jpg');
	}
	var target = $('#musprim option[value="' + target_id + '"]');
	changeTargetOption(target);
}

function changeTargetOption(x) {
	var target = $(x);
	thisOne = target.val();
	//$('#musprim>option').prop('selected',false);	
	//$('#musprim option[value="' + thisOne + '"]').prop("selected", true);
	$("#musprim").select2("val", thisOne); //set the value
}

function getExerciseLibraryByajax() {
	if ($('#search-exercise-library').length) {
		$('#search-exercise-library').autocomplete({
			source: function(requete, reponse) { // les deux arguments représentent les données nécessaires au plugin
				$.ajax({
					url: siteUrl_frontend + 'search/scanrecords',
					dataType: 'json', // on spécifie bien que le type de données est en JSON
					data: {
						action: 'exerciselibrary',
						title: $('#search-exercise-library').val(), // on donne la chaîne de caractère tapée dans le champ de recherche
						maxRows: 5
					},
					success: function(donnee) {
						if (donnee) {
							reponse($.map(donnee, function(item) {
								return {
									url: item.weburl,
									titre: item.titre,
									color: item.color
								}
							}));
						}

					}
				});
			},
			select: function(event, ui) {
				window.location = ui.item.weburl;
			}
		}).data("ui-autocomplete")._renderItem = function(ul, item) {
			if (item.color.length)
				return $("<li>").append("<a href='" + item.url + "' target='_parent'><div class='col-xl-6 colorchoosen'><i class='glyphicon' style='" + item.color + "'></i></div><div class='col-xl-6'>" + item.titre + "</div></a>").appendTo(ul);
			else
				return $("<li>").append("<a href='" + item.url + "' target='_parent'>" + item.titre + "</div></a>").appendTo(ul);
		};
	}
}
$(document).on('click', '.filter_sub_btn', function(e) {
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

function getXRcisepreviewOfDay(exerciseId, wkoutId, title) {
	$('#xrciseprev-modal').html();
	$.ajax({
		url: siteUrl_frontend + "search/getmodelTemplate",
		data: {
			action: 'previewExerciseOfDay',
			method: 'preview',
			id: exerciseId,
			foldid: wkoutId
		},
		success: function(content) { //alert("Success");
			$('#xrciseprev-modal').html(content);
			$('.xrtitle').text('Preview - ' + title);
			$('#xrciseprev-modal').modal();
		}
	});
}
$(document).ready(function() {
	//$('.exerciseaction').style.css('width','200px');
	// $( ".exerciseaction" ).css( "width", '200px');	
	$('.selectActions').select2();
	$(".pageexebrowse .refine").insertAfter(".pageexebrowse .dataTables_filter label");
	$(".pageexebrowse .advance-search-contnr").insertAfter(".pageexebrowse .dataTables_wrapper  .dataTables_filter");
	var add_exe = $(".pageexebrowse .Add_Exercise_Record").attr('href');
	$('<select name="moteactions[]" id="moteactions" class="form-control selectAction right exerciseaction"> <option value="">Actions</option> <option value="' + add_exe + '">Create New Exercise Record</option>  <option value="duplicate">Duplicate this record</option>  <option value="delete">Delete this record</option>  <option value="tag">Tag this record</option> <option value="share">Share this record </option> <option value="feedback">Feedback for this record</option><option value="reportEmail">Email</option><option value="Excel">Excel</option><option value="PDF">PDF</option> </select>').appendTo('.pageexebrowse .dataTables_length').select2({
		minimumResultsForSearch: -1
	});
	$("#moteactions").change(function() {
		var val = $(this).val();
		var id = $(this).attr("id");
		//console.log(val+"-----------------")
		switch (val) {
			case "create":
				//var roleid = $('#roleid').val();
				location.href = siteUrl + "exercise/create";
				break;
			case "default":
				var data = [];
				$(".exe_select").each(function() {
					if ($(this).prop('checked') == true) {
						var id = $(this).attr("id");
						data.push($(this).val());
					}
				});
				//alert(data+"------------------")
				set_default(data, 1);
				break;
			case "sample":
				var data = [];
				$(".exe_select").each(function() {
					if ($(this).prop('checked') == true) {
						var id = $(this).attr("id");
						data.push($(this).val());
					}
				});
				//alert(data+"-----------------aaaa-")
				set_default(data, 2);
				break;
			case "myexercise":
				var data = [];
				$(".exe_select").each(function() {
					if ($(this).prop('checked') == true) {
						var id = $(this).attr("id");
						data.push($(this).val());
					}
				});
				//alert(data+"-----------------aaaa-")
				set_default(data, 0);
				break;
			case "tag":
				var data = new Array();
				$(".exe_select").each(function() {
					if ($(this).prop('checked') == true) {
						var id = $(this).attr("id");
						data.push($(this).val());
					}
				});
				//alert(data.toSource());
				if (data.length > 0) {
					$('.moteactions').val('').trigger('liszt:updated');
					tagworkout('multiple', data);
				} else {
					alert("Please select exercise record(s)");
				}
				break;
			case "delete":
				var data = new Array();
				$(".exe_select").each(function() {
					if ($(this).prop('checked') == true) {
						var id = $(this).attr("id");
						data.push($(this).val());
					}
				});
				if (data.length > 0) {
					$('.moteactions').val('').trigger('liszt:updated');
					triggerexerciseaction('delete', data, 'multiple');
				} else {
					alert("Please select exercise record(s)");
				}
				break;
			case "duplicate":
				var data = new Array();
				$(".exe_select").each(function() {
					if ($(this).prop('checked') == true) {
						var id = $(this).attr("id");
						data.push($(this).val());
					}
				});
				if (data.length > 0) {
					$('.moteactions').val('').trigger('liszt:updated');
					if ($('#xrdefault').length > 0 && ($('#xrdefault').val() == 0 || $('#xrdefault').val() == 1 || $('#xrdefault').val() == 2 || $('#xrdefault').val() == 'all')) {
						triggerDuplicateModal(data, 'multiple');
					} else {
						triggerexerciseaction('copy', data, 'multiple');
					}
				} else {
					alert("Please select exercise record(s)");
				}
				break;
			case "copythis":
				var data = new Array();
				$(".exe_select").each(function() {
					if ($(this).prop('checked') == true) {
						var id = $(this).attr("id");
						data.push($(this).val());
					}
				});
				if (data.length > 0) {
					$('.moteactions').val('').trigger('liszt:updated');
					triggerexerciseaction('de_copy', data, 'multiple');
				} else {
					alert("Please select exercise record(s)");
				}
				break;
			case "reportEmail":
				var data = new Array();
				$("input.exe_select").each(function() {
					if ($(this).prop('checked') == true) {
						var id = $(this).attr("id");
						data.push($(this).val());
					}
				});
				console.log(data);
				$('#EmailModal input#exe').val(data);
				$('#EmailModal').modal('toggle');
				break;
			case "Excel":
				//var roleid = $('#roleid').val();
				location.href = siteUrl + "exercise/get_exercise_report_as_excel/";
				break;
			case "PDF":
				//var roleid = $('#roleid').val();
				window.open(siteUrl + "exercise/get_exerciselist_pdf/", '_blank');
				break;
			case "export":
				$("#exportModal input#selected_exe").val('');
				$('div.response').html('');
				$("#exportModal").modal("show");
				break;
			case "exportselected":
				$('div.response').html('');
				$("#exportModal input#selected_exe").val('');
				var data = new Array();
				$(".exe_select").each(function() {
					if ($(this).prop('checked') == true) {
						var id = $(this).attr("id");
						data.push($(this).val());
					}
				});
				if (data.length > 0) {
					$("#selected_exe").val(data);
					$("#exportModal").modal("show");
				} else {
					alert("Please select exercise record(s)");
				}
				break;
			case "exportdefault":
				$('div.response').html('');
				$("#selected_exe").val('')
				$("#exportModal").modal("show");
				break;
			case "exportselecteddefault":
				$('div.response').html('');
				$("#exportModal input#selected_exe").val('');
				var data = new Array();
				$(".exe_select").each(function() {
					if ($(this).prop('checked') == true) {
						var id = $(this).attr("id");
						data.push($(this).val());
					}
				});
				if (data.length > 0) {
					$("#selected_exe").val(data)
					$("#exportModal").modal("show");
				} else {
					alert("Please select exercise record(s)");
				}
				break;
			case "share":
				check_exrciseoptions();
				break;
			default:
				break;
		}
		$("#moteactions").select2('val', '');
	});
	$(document).on('change', '.ex-single-action', function() {
		var val = $(this).val();
		var id = $(this).attr("id"); // alert(id);
		switch (val) {
			case "edit":
				triggerXrUnitEdit(id);
				break;
			case "tag":
				tagworkout('single', id);
				break;
			case "sampledefaulthide":
				defaulthideExercise(id, 0);
				break;
			case "duplicate": //alert($('#xrdefault').val())
				if ($('#xrdefault').length > 0 && ($('#xrdefault').val() == 0 || $('#xrdefault').val() == 1 || $('#xrdefault').val() == 2 || $('#xrdefault').val() == 'all')) {
					triggerDuplicateModal(id, 'single');
				} else {
					triggerexerciseaction('copy', id, 'single');
				}
				break;
			case "copythis":
				triggerexerciseaction('de_copy', id, 'single');
				break;
			case "view":
				$("#xrunitdata-modal").modal();
				xrUnitRecord(id);
				break;
			case "view_related":
				getRelatedRecords(id);
				break;
			case "delete":
				triggerexerciseaction('delete', id, 'single');
				break;
			case "default":
				var data = [];
				data.push(id);
				set_default(data, 1);
				break;
			case "sample":
				var data = [];
				data.push(id);
				set_default(data, 2);
				break;
			case "myexercise":
				var data = [];
				data.push(id);
				set_default(data, 0);
				break;
			case "rate":
				rate_xr(id);
				break;
			case "share":
				var data_id = [];
				var data_title = [];
				var xr_title = $(this).closest('tr').find('td.ex_name').text();
				data_id.push(id);
				data_title.push(xr_title);
				triggerShareExerciseModal(data_id, data_title, 'single');
				break;
			case "more":
				$("#moreModal").modal('show');
				assign_status_val(id);
				//$("select.unit_status").val('1');
				$("select.unit_status").select2();
				$("select.featured").select2();
				$('.unitid').val(id);
				break;
			default:
				break;
		}
		$(".ex-single-action").select2('val', '');
		// $(".ex-single-action").val("").trigger('chosen:updated');
	});
});
var $xrids = '';
var $act_type = '';

function triggerDuplicateModal(ids, actionType) {
	$xrids = ids;
	$act_type = actionType;
	$('#xr-duplicateModal').modal();
}
$(document).on('click', '.duplicate-xrrec', function() {
	$('#xr-duplicateModal').modal('hide');
	var checkedopt = $('input[name="exercise_type"]:checked').val();
	var id = $xrids;
	var actionType = $act_type;
	if (checkedopt === 'sample') {
		triggerexerciseaction(checkedopt, id, actionType);
	} else if (checkedopt === 'default') {
		triggerexerciseaction(checkedopt, id, actionType);
	} else {
		triggerexerciseaction('copy', id, actionType);
	}
})

function listexport(arg, edefault) {
	var data = ($("#selected_exe").val()) ? "?exe=" + $("#selected_exe").val() : '';
	if (data) {
		data = data + "&default=" + edefault;
	} else {
		data = "?default=" + edefault
	} //alert(data);
	//if (edefault==0) {
	if (arg == 'pdf') {
		window.open(siteUrl + "exercise/get_exerciselist_pdf/" + data, '_blank');
	} else if (arg == 'excel') {
		location.href = siteUrl + "exercise/get_exercise_report_as_excel/" + data;
	}
	//}
	$("#selected_exe").val('');
}

/*$(".type_chkbx").tinyToggle({
	type:    'square'
	//palette: 'green', 
	//size:    'small'
});*/
//rate_xr(872);
$(document).ready(function() {
	$('.type_chkbx').iCheck({
		checkboxClass: 'icheckbox_square-green',
		radioClass: 'iradio_square-green',
		increaseArea: '20%'
	});
});
$('.fetch-record').click(function() {
	$('.tab-pane').css("display", "block"); // alert('css');
	$(".advnce-srch-frm").submit();
})

function assign_status_val(unitid) {
	$.ajax({
		url: siteUrl + "exercise/assign_status_val",
		type: 'POST',
		dataType: 'json',
		data: {
			unitid: unitid
		},
		success: function(data) {
			//alert(data.status_id);
			//$("select.featured").val(data.featured);
			$("select.unit_status").val(data.status_id).trigger("change");
			$("select.featured").val(data.featured).trigger("change");
		}
	});
}
$('.resetserach').on("click", function() { //alert('test');
	$('.searchtext').val('');
	$('.searchclear').hide();
	$(':checkbox').prop('checked', false).removeAttr('checked');
	$(':checkbox').prop('checked', false).removeAttr('checked');
	//$('#advnce-srch-frm .checked').attr('aria-checked', false);	
	//$('#advnce-srch-frm .checked').prop('checked', false);
	$('.checked').removeClass('checked');
	targetMuscle('', '');
	var $form = $('.advnce-srch-frm')
	$form.find('[name="pageval"]').val($(this).text());
	$form.attr("action", $(this).attr('href'));
	$form.submit();
});
$(document).on('keyup', '#xr_filter_search input.searchtext', function(e) {
	e.preventDefault();
	var value = $(this).val().trim();
	var t = $(this);
	t.next('span').toggle(Boolean(t.val()));
});
$("#xr_filter_search .searchclear").click(function() {
	$(this).hide().prev('input').val('').focus();
});

function updatestatus() {
	var unitid = $('.unitid').val();
	var unit_status = $('.unit_status').select2("val");
	var featured = $('.featured').select2("val");
	//var wkoutfilter = $('.wkfilter_val').val(); // alert(wkoutfilter);
	$.ajax({
		url: siteUrl + "exercise/exercise_update_status",
		type: 'POST',
		dataType: 'json',
		data: {
			unitid: unitid,
			unit_status: unit_status,
			featured: featured
		},
		success: function(data) { //alert(data);
			// window.location = self.location;
			$(".advnce-srch-frm").submit();
		}
	});
}

function rate_xr(id) {
	$.ajax({
		url: siteUrl + "exercise/rateXrData",
		type: 'POST',
		dataType: 'json',
		data: {
			unit_id: id,
		},
		success: function(data) {
			if (data.content) {
				$('#rateModal').modal('show');
				$(".ratetitle").html(data.title);
				$('.ratebody').html(data.content);
			} else {
				alert("No data found")
			}
		}
	});
}

function approve_by(rid) {
	//$("#approve"+rid).hide();
	var r = confirm("Are you sure to Approve?");
	if (r) {
		$.ajax({
			url: siteUrl + "exercise/approveRateXrData",
			type: 'POST',
			dataType: 'json',
			data: {
				rate_id: rid,
			},
			success: function(data) {
				$("#approve" + rid).hide();
			}
		});
	}
}

function set_default_submit() {
	var exe_id = $("#de_exe_id").val();
	//var site_id = $("#site_id").val();
	if (!exe_id) {
		$(".errormsg").html("Please select exercise records");
		return false;
	} else {
		$(".errormsg").html("");
	}
	//alert(exe_id+"----"+exe_id.length)
	/*
	if (exe_id.length > 1) {
		triggerexerciseaction('default', exe_id, 'multiple');
	} else {
		triggerexerciseaction('default', exe_id, 'single');
	}*/

	var dt = $("#default_status").val();
	var role = $("#role").val();
	// alert(dt+"----"+role+"----"+$("#role").length)
	if (role == 1) {
		if (dt == 1) {
			triggerexerciseaction('default', exe_id, 'multiple');
			$('#setexedefaultModal').modal('hide');
		} else if (dt == 2) {
			triggerexerciseaction('sample', exe_id, 'multiple');
			$('#setexesampleModal').modal('hide');
		}
	} else {
		if (dt == 2) {
			triggerexerciseaction('sample-copy', exe_id, 'multiple');
			$('#setexesampleModal').modal('hide');
		}
	}
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



function set_default(ids, st) {
	console.log(ids.length + "-------zz--------" + ids + "----------" + typeof ids + "----")
	if (st == 1) {
		$('#setexedefaultModal').modal('show');
	} else if (st == 2) {
		$('#setexesampleModal').modal('show');
	}
	$("#default_status").val(st)
	$("select.de_exe_id").select2('val', ['All']);
	$("select.site_id").select2('val', ['All']);
	$("select.de_exe_id").val(ids);
	$("select.de_exe_id").select2();
	$("select.de_exe_id").select2('close');
	$("select.site_id").select2();
}

function xrUnitRecord(xrID) { //alert(xrUnitRecord);
	$.ajax({
		url: siteUrl + "ajax/exerciseUnitData",
		type: 'POST',
		dataType: 'json',
		data: {
			xr_id: xrID,
		},
		success: function(data) {
				//var unitrec=JSON.parse(data);
				//var items = [];
				$('.xrRecordData').html('');
				$.each(data, function(key, val) {
					val.forEach(function(f) {
						var xr_id = f.id;
						var xr_title = f.title;
						var featimg = f.featimg;
						var status = f.status;
						var access = f.access;
						var user = f.user;
						if (f.xrtype != '' && f.xrtype != null) {
							var xrtype = f.xrtype;
						} else {
							var xrtype = '-';
						}
						if (f.muscle != '' && f.muscle != null) {
							var muscle = f.muscle;
						} else {
							var muscle = '-';
						}
						if (f.equip != '' && f.equip != null) {
							var equip = f.equip;
						} else {
							var equip = '-';
						}
						if (f.mech != '' && f.mech != null) {
							var mech = f.mech;
						} else {
							var mech = '-';
						}
						if (f.level != '' && f.level != null) {
							var level = f.level;
						} else {
							var level = '-';
						}
						if (f.force != '' && f.force != null) {
							var force = f.force;
						} else {
							var force = '-';
						}
						var preview = f.path;
						var unitRec = '<div class="xr_data activeCell" id=' + xr_id + '">';
						unitRec += '<div class="row"><div class="xrunittop border-work">';
						unitRec += '<div class="col-sm-5 col-xs-5 thumb_img"><img src="' + featimg + '" /></div>';
						unitRec += '<div class="col-sm-7 col-xs-7"><div class="xr_unit xrtitle">' + xr_title + '</div></div>';
						unitRec += '';
						unitRec += '</div></div>';
						unitRec += '<div class="xrunitdata row">';
						unitRec += '<div id="feat_img" class="hide">' + featimg + '</div>';
						unitRec += '<div id="preview_img" class="hide">' + preview + '</div>';
						unitRec += '<div id="unit_id" class="hide">' + xr_id + '</div>';
						unitRec += '<div id="status" class="hide">' + status + '</div>';
						unitRec += '<div id="access" class="hide">' + access + '</div>';
						unitRec += '<div id="user" class="hide">' + user + '</div>';
						unitRec += '<div class="clearboth"></div>';
						unitRec += '<div class="border-work"><div class="xrdata_label col-xs-5">Type of Activity:</div><div class="xrdata_value col-sm-7">' + xrtype + '</div></div>';
						unitRec += '<div class="border-work"><div class="xrdata_label col-xs-5">Primary Muscle:</div><div class="xrdata_value col-sm-7">' + muscle + '</div></div>';
						unitRec += '<div class="border-work"><div class="xrdata_label col-xs-5">Equipment:</div><div class="xrdata_value col-sm-7">' + equip + '</div></div>';
						unitRec += '<div class="border-work"><div class="xrdata_label col-xs-5">Mechanics:</div><div class="xrdata_value col-sm-7">' + mech + '</div></div>';
						unitRec += '<div class="border-work"><div class="xrdata_label col-xs-5">Level:</div><div class="xrdata_value col-sm-7">' + level + '</div></div>';
						unitRec += '<div class="border-work"><div class="xrdata_label col-xs-5">Force:</div><div class="xrdata_value col-sm-7">' + force + '</div></div>';
						unitRec += '</div>';
						unitRec += '</div>';
						file = $(unitRec);
						file.appendTo('.xrRecordData');
						unitRec = '';
						$('#xrid').val(xr_id);
						$('#unitdata-title').text('Exercise Record - ' + xr_title);
					});
				});
			} // END success
	}); // END ajax
}

function getRelatedRecords(xrid) {
	if ($('#myOptionsModalExerciseRecord_more').length) modalName = 'myOptionsModalExerciseRecord_more';
	else modalName = 'myOptionsModalExerciseRecord';
	$('#' + modalName).html();
	$.ajax({
		url: siteUrl_frontend + "search/getmodelTemplate",
		data: {
			action: 'relatedRecords',
			method: 'relatedRecords',
			id: xrid,
			modelType: modalName
		},
		success: function(content) {
			$('#' + modalName).html(content);
			$('#' + modalName).modal();
		}
	});
}

function getRelatedRecordsMore(xrid, start, lim) {
	$.ajax({
		url: siteUrl_frontend + "search/getRelatedXrRecordsMore",
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
						response += '" src=\'' + JSONArray[i].img + '\'';
						response += '/>';
					} else {
						response += '<i style="font-size:50px;" class="fa fa-file-image-o datacol"></i>';
					}
					response += '</div>';
					response += '<div class="col-xs-6 "><b>' + JSONArray[i].title + '</b></div>';
					response += '<div class="col-xs-5"><a title="" href="javascript:void(0);"><i class="fa fa-sign-in iconsize datacol"></i></a></div>';
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

function triggerXrUnitEdit(xruid) { //alert(id);
	window.location = siteUrl + "exercise/create/" + xruid + "?act=lib";
}

function triggerexerciseaction(action, id, action_type) {
	console.log(id);
	var action_txt = '';
	var corrent_action = action;
	if (action == 'copy') {
		action_txt = 'duplicate';
	} else if (action == 'de_copy') {
		action_txt = 'copy';
	} else if (action == 'delete') {
		action_txt = 'delete';
	} else if (action == 'default') {
		action_txt = 'set default';
	} else if (action == 'sample') {
		action_txt = 'set sample';
	} else if (action == 'sample-copy') {
		action_txt = 'set sample';
		var corrent_action = action;
		action = 'sample';
	}
	//alert(id.toSource());
	if (confirm('Are you sure, want to ' + action_txt + ' this record?')) {
		var table = $('.dataTable').DataTable();
		$.ajax({
			url: siteUrl + "ajax/exactions",
			type: 'POST',
			dataType: 'json',
			data: {
				id: id,
				action: action,
				action_type: action_type
			},
			success: function(data) {
				if (data.success) {
					if (corrent_action == "delete") {
						if (action_type == 'multiple') {
							$.each(id, function(index, value) {
								$('#row-' + value).remove();
							});
						} else {
							$('#row-' + id).remove();
						}
					}
					if (corrent_action == "copy") {
						$(".subscribeselect").prop('checked', false);
						location.reload();
					} else if (corrent_action == "delete") {
						$(".chkbox-item").prop('checked', false);
						window.location.href = window.location.href;
					} else if (corrent_action == "default") {
						window.location.href = siteUrl + "exercise/sample?d=1";
					} else if (corrent_action == "sample") {
						window.location.href = siteUrl + "exercise/sample?d=2";
					} else if (corrent_action == "sample-copy") {
						window.location.href = siteUrl + "exercise/sample/";
					}
				}
			}
		});
	}
}
$(document).on('click', '.click-previmg', function() {
	var imgurl = $(this).attr('data-url');
	$('#preview-featimg').attr('src', '/' + imgurl);
	$('#previewimg-modal').modal();
});

function deleteRecord(id) {
	$("#deleteModalBtn").click();
	$("#user_idjs").val(id);
}

function deleteUser() {
	var id = $('#user_idjs').val();
	$.ajax({
		url: siteUrl + 'sites/deleteSites',
		type: 'POST',
		dataType: 'json',
		data: {
			'id': id
		},
		success: function(data) {
			if (data.success) {
				$('.del-sucess .alert-success span').text(data.message);
				$('.del-sucess').show();
				$('#row-' + id).remove();
				$("#noDelete").click();
			}
		}
	});
}

function tagworkout(type, id) {
	$('#cwkid').val(id);
	$('#tagmodal').modal('show');
	$.ajax({
		url: siteUrl + "ajax/getexercisetags",
		type: 'GET',
		dataType: 'json',
		data: {
			exerciseid: id
		},
		success: function(data) {
			if (data.success) {
				$('#tagnames').val(data.user_tags);
				tag_dropdown_execrcise('#tagnames', data.tags);
			}
		}
	});
}

function tag_dropdown_execrcise(element, datasource) {
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
			//alert($(element).val()+"---"+$(element).id)
			//var data = { id: element.val(), text: element.val() };callback(data);
			var preselected_ids = extract_preselected_ids(element); // alert(datasource);
			var preselections = find_preselections(preselected_ids, datasource);
			callback(preselections);
		}
	});
}

function extract_preselected_ids(element) {
	var preselected_ids = [];
	var delimiter = ',';
	if (element.val()) {
		//alert(element.val())
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
// find all objects with the pre-selected IDs
// preselected_ids: array of IDs
function find_preselections(preselected_ids, datasource) { //alert('preselections');
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
$(".addextags").click(function() {
	var exerciseid = $('#cwkid').val();
	var wid = exerciseid.split(',');
	var tags = $('#tagnames').val();
	var selecteddatas = $('#tagnames').select2('data');
	var tagstext = '';
	var a = [];
	$.each(selecteddatas, function(key, value) {
		a.push(value.text);
	});
	tagstext = a.join(', ');
	$('#exercise-table tr#row-' + exerciseid).find("td.tagsection").text(tagstext);
	createupdatetags(exerciseid, tags);
});

function createupdatetags(exerciseid, tags) {
	//console.log(tags+"#####"+exerciseid)
	$.ajax({
		url: siteUrl + "ajax/ExerciseAdduUdateTags",
		type: 'POST',
		dataType: 'json',
		data: {
			exerciseid: exerciseid,
			tags: tags
		},
		success: function(data) {
			if (data.success) {
				var jsondata = data.tags;
				if (jsondata.length > 0) {
					jsondata.forEach(function(obj, index) {
						$('#exercise-table tr#row-' + obj.unit_id).find("td.tagsection").text(obj.tag_title);
					});
					/* $("select.subscriber_id").val(data);
					$("select.subscriber_id").select2();*/
				} else {
					var unitid = exerciseid.split(',');
					if (unitid.length > 0) {
						unitid.forEach(function(obj, index) {
							$('#exercise-table tr#row-' + obj).find("td.tagsection").text('');
						});
					}
				}
				$('#tagmodal').modal('hide');
				setTimeout(function() {
					$('.ajax-info-alert').prepend('<div class="row bannermsg"><div class="col-xs-12"><div class="banner alert alert-success"><i class="fa fa-check"></i><span>' + __('Exercise record tagged successfully') + ' !!!</span></div></div></div>');
					$('.ajax-info-alert').show();
				}, 200);
			} else {
				setTimeout(function() {
					$('.ajax-info-alert').prepend('<div class="row bannermsg"><div class="col-xs-12"><div class="banner alert alert-danger"><i class="fa fa-remove"></i><span>' + __('Error occurred while sharing') + ' !!!</span></div></div></div>');
					$('.ajax-info-alert').show();
				}, 200);
			}
			setTimeout(function() {
				$('.ajax-info-alert .bannermsg').fadeOut(10000);
			}, 250);
		}
	});
}

function emptyRecordData() {
	if ($('.activeCell').length) {
		$('.xrRecordData').empty().addClass('hide');
	}
}

function exercise_email() {
	if ($.trim($('#email_address').val()) == '') {
		$('.form-error').html('Please enter email address');
		return false;
	}
	$.ajax({
		url: siteUrl + 'exercise/report_email/',
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
//$('.pagination a').click(function(){alert($(this).text());
$(document).on('click', '.pagination a', function() {
	var $form = $('.advnce-srch-frm')
	$form.find('[name="pageval"]').val($(this).text());
	$form.attr("action", $(this).attr('href'));
	$form.submit();
	return false;
});
//$('form.advnce-srch-frm :input').change(){
$(document).on('change', 'form.advnce-srch-frm :input', function() {
	$(this).closest('form').find('[name="pageval"]').val(1);
});

function defaulthideExercise(unitid, default_status) {
	var r = confirm("Are you Hide this sample Exercise Record?");
	if (r) {
		$.ajax({
			url: siteUrl + "exercise/defaulthide",
			type: 'POST',
			data: {
				unitid: unitid,
				f_method: "defaultExercise",
				default_status: default_status
			},
			success: function(data) {
				if (data) {
					window.location.href = siteUrl + "exercise/sample";
				}
			}
		});
	}
}

function check_exrciseoptions() {
	var data_id = [];
	var data_title = [];
	$(".exe_select").each(function() {
		if ($(this).prop('checked') == true) {
			var id = $(this).attr("id");
			data_id.push($(this).val());
			var xr_title = $(this).closest('tr').find('td.ex_name').text();
			data_title.push(xr_title);
		}
	});
	if (data_id.length > 0 && data_title.length > 0) {
		triggerShareExerciseModal(data_id, data_title, 'multiple');
	} else {
		alert("Please select exercise record(s)");
	}
}

function triggerShareExerciseModal(xruid, xrtitle, opt = '') {
	$.ajax({
		url: siteUrl_frontend + "ajax/getAjaxExerciseShareHtml",
		type: 'post',
		data: {
			action: 'shareExercise',
			xrid: xruid,
			title: xrtitle,
			actFrom: 'page',
			reqFrom: 'admin',
			option: opt
		},
		success: function(content) {
			var ajaxData = JSON.parse(content);
			$('#sharexrcise-modal').empty();
			if (ajaxData.content != '') {
				$('#sharexrcise-modal').html(ajaxData.content);
				$("input#xr_user_names").select2({
					placeholder: "Search Users",
					minimumInputLength: 2,
					multiple: true,
					ajax: {
						url: siteUrl_frontend + 'search/getajax/',
						data: function(term, page) {
							return {
								title: term,
								siteids: $("input#xr_site_names").val(),
								maxRows: 5,
								action: "getusers"
							};
						},
						results: function(data, page) {
							return {
								results: data
							};
						}
					}
				});
				$("input#xr_site_names").select2({
					placeholder: "Search Sites",
					minimumInputLength: 2,
					multiple: true,
					ajax: {
						url: siteUrl_frontend + 'search/getajax/',
						data: function(term, page) {
							return {
								title: term,
								siteids: $("input#xr_site_names").val(),
								maxRows: 5,
								action: "getsites"
							};
						},
						results: function(data, page) {
							return {
								results: data
							};
						}
					}
				}).select2("data", [{
					"id": ajaxData.siteid,
					"text": ajaxData.sitename
				}]);
				$('#myOptionsModalExerciseRecord').modal('hide');
				$('#sharexrcise-modal').modal();
			}
		}
	});
}

function checkValidXrShareInfo() {
	if ($('input#xr_user_names').val() != '') {
		shareExerciseRecordAdmin();
		return true;
	} else $('div.share-errormsg').html('Please choose atleast one Recipient').removeClass('hide');
	return false;
}

function checkValidAdminXrShareInfo() {
	if ($('input#xr_user_names').val() != '' && $('input#xr_site_names').val() != '') {
		shareExerciseRecordAdmin();
		return true;
	} else {
		if ($('input#xr_site_names').val() == '') $('div.share-errormsg').html('Please choose atleast one Site').removeClass('hide');
		else $('div.share-errormsg').html('Please choose atleast one Recipient').removeClass('hide')
	};
	return false;
}

function shareExerciseRecordAdmin() {
	$.ajax({
		url: siteUrl + 'ajax/shareExerciseRecordFromPage',
		method: 'post',
		data: $('#form_shareExercise').serialize() + '&action=sharing',
		success: function(content) {
			var response = JSON.parse(content);
			if (response.msg == 'success') {
				$('#sharexrcise-modal').modal('hide');
				setTimeout(function() {
					$('.ajax-info-alert').prepend('<div class="row bannermsg"><div class="col-xs-12"><div class="banner alert alert-success"><i class="fa fa-check"></i><span>' + __('Exercise record, shared successfully') + ' !!!</span></div></div></div>');
					$('.ajax-info-alert').show();
				}, 200);
			} else {
				setTimeout(function() {
					$('.ajax-info-alert').prepend('<div class="row bannermsg"><div class="col-xs-12"><div class="banner alert alert-danger"><i class="fa fa-remove"></i><span>' + __('Error occurred while sharing') + ' !!!</span></div></div></div>');
					$('.ajax-info-alert').show();
				}, 200);
			}
			setTimeout(function() {
				$('.ajax-info-alert .bannermsg').fadeOut(10000);
			}, 250);
		}
	});
}

$(document).on('click keyup', '.bootstrap-tagsinput', function() {
	var textheight = 0;
	var headheight = 0;
	if ($('#exercisetags').is(':visible')) {
		textheight = $('#exercisetags .filter_column')[0].scrollHeight || 0;
		headheight = $('#exercisetags .filter_heading')[0].scrollHeight || 0;
		if ($('.tt-menu.tt-open').is(':visible')) {
			$('#exercisetags .filterTitle').animate({
				'min-height': (textheight + headheight + 20)
			}, 150);
		}
		$('#exercisetags .twitter-typeahead').bind('typeahead:close', function(ev, suggestion) {
			ev.preventDefault();
			if (ev.handler !== true) {
				ev.handler = true;
				$('#exercisetags .filterTitle').animate({
					'min-height': textheight + headheight
				}, 150);
			}
		});
	}
});