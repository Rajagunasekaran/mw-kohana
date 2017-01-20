$(document).ready(function() {
	var chartshow = false;
	setpast2weeks(); //set the past two weeks date in textbox form morris-profiledata.js
	$(".toggle").click(function() {
		$input = $(this);
		$target = $("#" + $input.attr("data-toggle"));
		$target.slideToggle("slow", function() {
			if ($target.attr("id") == "detail-9" && !chartshow) {
				updateCardioChart();
				chartshow = true;
			}
		});
		if ($input.find(".col-xs-2 i").attr("class") == "fa fa-chevron-down pull-right") $input.find(".col-xs-2 i").removeClass("fa fa-chevron-down pull-right").addClass("fa fa-chevron-up pull-right");
		else $input.find(".col-xs-2 i").removeClass("fa fa-chevron-up pull-right").addClass("fa fa-chevron-down pull-right");
	});
	$('#malefemalespan').hide();
	$('#malefemaleedit').click(function() {
		$('#malefemalespan').show();
	});
	$('i.showalldetail').click(function() {
		var alltag = $(this).attr('data-id');
		$('#' + alltag + '-more').removeClass('hide');
		$('#' + alltag + '-less').addClass('hide');
		$(this).hide();
	});
	$('i.showeditdetail').click(function() {
		var edittag = $(this).attr('data-id');
		$('#' + edittag + '-edit').toggleClass('hide');
		$('#' + edittag + '-label').toggleClass('hide');
		$(this).toggleClass('fa-times fa-pencil');
		if ($(this).parent().find('#phoneno-label').length) {
			$('i.change-user_mobile').toggleClass('hide');
			var phoneno = $(this).attr('data-value');
			$('#phoneno-edit input').val(phoneno);
			setTimeout(function() {
				$('#phoneno-edit input').focus();
			}, 200);
		} else if ($(this).parent().find('#device-label').length) {
			$('i.change-user_device').toggleClass('hide');
			var userdevice = $(this).attr('data-value').split(',');
			$('#device-edit select').select2('val', userdevice);
			setTimeout(function() {
				$('#device-edit select').focus();
			}, 200);
		} else if ($(this).parent().find('#birthdate-label').length) {
			$('i.change-user_birthdate').toggleClass('hide');
			var birthdate = $(this).attr('data-value');
			$('#birthdate-edit input').val(birthdate);
			setTimeout(function() {
				$('#birthdate-edit input').focus();
			}, 200);
		}
	});
	/*measurements*/
	$('i.add_height').click(function() {
		var edittag = $(this).attr('data-id');
		$('#' + edittag + '-edit').toggleClass('hide');
		$(this).toggleClass('fa-times fa-pencil');
		if ($(this).parent().find('#' + edittag + '-label').length) {
			$('i.change-user_' + edittag).toggleClass('hide');
			$('#' + edittag + '-edit input').val($(this).attr('data-value'));
			$('#' + edittag + '-label').text($(this).attr('data-value'));
			setTimeout(function() {
				$('#' + edittag + '-edit input').focus();
			}, 200);
		}
	});
	$('span.add_weight, i.add_weight').click(function() {
		var edittag = $(this).attr('data-id');
		$('#' + edittag + '-edit').toggleClass('hide');
		$('.add_weight').toggleClass('hide');
		$('.weight-diff').toggleClass('hide');
		if ($(this).parent().find('#' + edittag + '-label').length) {
			$('i.change-user_' + edittag + '').toggleClass('hide');
			$('#' + edittag + '-edit input').val($(this).attr('data-value'));
			$('#' + edittag + '-label').text($(this).attr('data-value'));
			setTimeout(function() {
				$('#' + edittag + '-edit input').focus();
			}, 200);
		}
	});
	/*initial question*/
	$('i.add_initheight, i.add_initweight').click(function() {
		var edittag = $(this).attr('data-id');
		$('#' + edittag + '-edit').toggleClass('hide');
		$(this).toggleClass('fa-times fa-pencil');
		if ($(this).parent().find('#' + edittag + '-label').length) {
			$('i.change-user_' + edittag).toggleClass('hide');
			$('#' + edittag + '-edit input').val($(this).attr('data-value'));
			$('#' + edittag + '-label').text($(this).attr('data-value'));
			setTimeout(function() {
				$('#' + edittag + '-edit input').focus();
			}, 200);
		}
	});
	/*gender*/
	$('#gender-edit input[name=user_gender]').change(function() {
		var genval = $('#gender-edit input[name=user_gender]:checked').val();
		if (genval != '') {
			updateUserProfile(genval, 'gender');
		}
	});
	/*dob*/
	if (user_from == 'front') {
		$('i.change-user_dob').click(function() {
			birthDayPopup($('#user_birthdate'));
		});
		$('input#user_birthdate').change(function() {
			var dobval = $('input#user_birthdate').val();
			if (dobval != '') {
				updateUserProfile(dobval, 'birthdate');
			}
		});
	} else if (user_from == 'admin') {
		$("#user_birthdate").datepicker({
			dateFormat: 'dd M yy',
			changeMonth: true,
			changeYear: true,
			defaultDate: $(this).val(),
			onSelect: function(selected, evnt) {
				var dobch_val = $(this).val();
				if (dobch_val != '') {
					var dob = new Date(convertDate(dobch_val));
					var today = new Date();
					var dayDiff = Math.ceil(today - dob) / (1000 * 60 * 60 * 24 * 365);
					var age = parseInt(dayDiff);
					if (age <= user_siteage) {
						var message = 'Minimum age for using the <b>' + user_sitename + '</b>\'s my workouts platform is <b>' + user_siteage + '</b>';
						$('<li/>').html(message).appendTo('#validation-errors');
						$('.modal-validerror').addClass('hide');
						$('#errorMessage-modal').modal('show');
						$('#profileactions').formValidation('revalidateField', 'user_birthdate');
					} else {
						$('#profileactions').formValidation('revalidateField', 'user_birthdate');
					}
				}
			}
		});
		$('i.change-user_birthdate').click(function() {
			var dobval = $('input#user_birthdate').val();
			if (dobval != '') {
				updateUserProfile(dobval, 'birthdate');
			}
		});
		$("#cardiodatefrom").datepicker({
			dateFormat: "dd-mm-yy",
			changeMonth: true,
			changeYear: true,
			defaultDate: $(this).val(),
			onSelect: function() {
				$('#cordio-filter #filterby').val(4);
				custmode = 1;
				updateCardioChart();
			}
		});
		$("#cardiodateto").datepicker({
			dateFormat: "dd-mm-yy",
			changeMonth: true,
			changeYear: true,
			defaultDate: $(this).val(),
			onSelect: function() {
				$('#cordio-filter #filterby').val(4);
				custmode = 1;
				updateCardioChart();
			}
		});
	}
	/*mobile*/
	$('i.change-user_mobile').click(function() {
		var mobileval = $('#phoneno-edit input').val();
		if (mobileval != '') {
			updateUserProfile(mobileval, 'phone no');
		}
	});
	/*height/weight-measurement*/
	$('i.change-user_height').click(function() {
		var heightval = $('#height-edit input').val();
		if (heightval != '') {
			updateUserProfile(heightval, 'height');
		} else {
			alert('Please enter you height!!!');
		}
	});
	$('i.change-user_weight').click(function() {
		var weightval = $('#weight-edit input').val();
		if (weightval != '') {
			updateUserProfile(weightval, 'weight');
		} else {
			alert('Please enter you weight!!!');
		}
	});
	/*height/weight-initial questions*/
	$('i.change-user_initheight').click(function() {
		var initheightval = $('#initheight-edit input').val();
		if (initheightval != '') {
			updateUserProfile(initheightval, 'initheight');
		} else {
			alert('Please enter you height!!!');
		}
	});
	$('i.change-user_initweight').click(function() {
		var initweightval = $('#initweight-edit input').val();
		if (initweightval != '') {
			updateUserProfile(initweightval, 'initweight');
		} else {
			alert('Please enter you weight!!!');
		}
	});
	$('#device-edit select').select2({
		placeholder: "Select Devices",
		allowClear: true
	});
	$('i.change-user_device').click(function() {
		var device = $('#device-edit select').val();
		if (device != '') {
			updateUserProfile(device, 'device');
		} else {
			alert('Please select any one device!!!');
		}
	});
});

function displaysliderval(lable, elem) {
	$("#" + lable).text($(elem).val());
	$(elem).attr('title', $(elem).val());
}

function isNumber(evt) {
	evt = (evt) ? evt : window.event;
	var charCode = (evt.which) ? evt.which : evt.keyCode;
	if (charCode > 31 && (charCode < 48 || charCode > 57)) {
		return false;
	}
	return true;
}
var UCFirst = function(string) {
	return string.charAt(0).toUpperCase() + string.slice(1);
}

function updateUserProfile(value, type) {
	$('#profileactions .modal-body .bannermsg').remove();
	var user_id = $('#profileactions input#user_id').val();
	var data = {
		'user_id': user_id,
		'is_front': (user_from == 'front' ? 1 : 0)
	};
	data[type] = value;
	if (type == 'device') {
		data['qid'] = $('.change-user_device').attr('data-qid');
	}
	$.ajax({
		url: (user_from == 'front' ? siteUrl : siteUrl_frontend) + 'ajax/updateUserProfileInst',
		method: 'post',
		dataType: 'json',
		data: data,
		success: function(response) {
			if (response[0]) {
				setTimeout(function() {
					var typename = '';
					if (type == 'initheight') {
						typename = 'Height';
					} else if (type == 'initweight') {
						typename = 'Weight';
					} else {
						typename = type;
					}
					$('#profileactions .modal-body').prepend('<div class="row bannermsg"><div class="col-xs-12 banner success">' + __(UCFirst(typename) + ' updated successfully') + ' !!!</div></div>');
				}, 200);
				if (type == 'gender') {
					var gentext = $('#gender-edit input[name=user_gender]:checked').attr('data-gentext');
					$('#gender-edit').parent().find('i.showeditdetail').toggleClass('fa-times fa-pencil');
					$('#gender-edit').toggleClass('hide');
					$('#gender-label').text(gentext).toggleClass('hide');
				} else if (type == 'birthdate' && user_from == 'admin') {
					var birthdate = $('#birthdate-edit input').val();
					$('i.change-user_birthdate').toggleClass('hide');
					$('#birthdate-edit').parent().find('i.showeditdetail').toggleClass('fa-times fa-pencil').attr('data-value', birthdate);
					$('#birthdate-edit').toggleClass('hide');
					if (response[5] != undefined) {
						$('#birthdate-label').text(response[5]).toggleClass('hide');
					}
				} else if (type == 'phone no') {
					var mobileno = $('#phoneno-edit input').val();
					$('i.change-user_mobile').toggleClass('hide');
					$('#phoneno-edit').parent().find('i.showeditdetail').toggleClass('fa-times fa-pencil').attr('data-value', mobileno);
					$('#phoneno-edit').toggleClass('hide');
					$('#phoneno-label').text(mobileno).toggleClass('hide');
				} else if (type == 'height') {
					var height = $('#height-edit input').val();
					$('#height-label').text(height);
					$('i.change-user_height').toggleClass('hide');
					$('#height-edit').parent().find('i.add_height').toggleClass('fa-times fa-pencil').attr('data-value', height);
					$('#height-edit').toggleClass('hide');
					if (response[1] != undefined) {
						$('#detail-2 .user-bmi').text(response[1]);
					}
				} else if (type == 'weight') {
					var weight = $('#weight-edit input').val();
					$('#weight-label').text(weight);
					$('i.change-user_weight').toggleClass('hide');
					$('#weight-edit').parent().find('.add_weight').toggleClass('hide').attr('data-value', weight);;
					$('#weight-edit').toggleClass('hide');
					$('.weight-diff').toggleClass('hide');
					if (response[1] != undefined) {
						$('#detail-2 .user-bmi').text(response[1]);
					} else {
						$('#detail-2 .user-bmi').text('n/a');
					}
					if (response[2] != undefined) {
						$('#detail-2 .weight-diff').text(' ' + response[2]);
					}
					if (response[3] != undefined) {
						$('#detail-2 .weight-unit').text(' ' + response[3]);
					}
				} else if (type == 'initheight') {
					var initheight = $('#initheight-edit input').val();
					$('#initheight-label').text(initheight);
					$('i.change-user_initheight').toggleClass('hide');
					$('#initheight-edit').parent().find('i.add_initheight').toggleClass('fa-times fa-pencil').attr('data-value', initheight);
					$('#initheight-edit').toggleClass('hide');
				} else if (type == 'initweight') {
					var initweight = $('#initweight-edit input').val();
					$('#initweight-label').text(initweight);
					$('i.change-user_initweight').toggleClass('hide');
					$('#initweight-edit').parent().find('i.add_initweight').toggleClass('fa-times fa-pencil').attr('data-value', initweight);
					$('#initweight-edit').toggleClass('hide');
					if (response[3] != undefined) {
						$('#detail-3 .initweight-unit').text(' ' + response[3]);
					}
				} else if (type == 'device') {
					var deviceid = $('#device-edit select').val();
					$('i.change-user_device').toggleClass('hide');
					$('#device-edit').parent().find('i.showeditdetail').toggleClass('fa-times fa-pencil').attr('data-value', deviceid.join(','));
					$('#device-edit').toggleClass('hide');
					$('#device-label').toggleClass('hide');
					if (response[4] != undefined) {
						$('#detail-3 #device-label').text(response[4]);
					}
				}
			} else {
				setTimeout(function() {
					$('#profileactions .modal-body').prepend('<div class="row bannermsg"><div class="col-xs-12 banner success">' + __('Error occured while updating') + ' !!!</div></div>');
				}, 200);
			}
			setTimeout(function() {
				$('#profileactions .modal-body .bannermsg').fadeOut(10000);
			}, 250);
		}
	});
	return;
}

function getExerciseSetpreviewlog(goalId, wkoutlogId, wkoutId, userid, selector) {
	if (!$(selector).attr("disabled")) {
		$('#myModal').html();
		$.ajax({
			url: (user_from == 'front' ? siteUrl : siteUrl_frontend) + "search/getmodelTemplate/",
			data: {
				action: 'previewExercise',
				method: 'enablepreview',
				id: goalId,
				foldid: wkoutId,
				logid: wkoutlogId,
				ownWkFlag: userid,
				type: 'logged',
				requestFrom: 'userprofile',
				fromAdmin: (user_from == 'admin' ? 1 : 0)
			},
			success: function(content) {
				$('#myModal').html(content);
				$('#myModal button.allow-edit').hide();
				$('#myModal').modal();
			}
		});
	}
}
$(document).on('click', '#cardiochart div.morris-hover', function(ev) {
	ev.preventDefault();
	if (ev.handled !== true) {
		ev.handled = true;
		setTimeout(function() {
			var cardiodate = $('#cardiochart div.morris-hover').attr('data-cardiodate');
			if (cardiodate != '' && cardiodate != undefined) {
				getExerciseSetCardioVars(cardiodate);
			}
		}, 200);
	}
});

function getExerciseSetCardioVars(cardiodate) {
	$('#myModal').html();
	if (!cardiodate) {
		return false;
	}
	$.ajax({
		url: (user_from == 'front' ? siteUrl : siteUrl_frontend) + "ajax/cardioReportChartVars",
		type: 'POST',
		dataType: 'json',
		data: {
			cardiodate: cardiodate,
			userid: $('#profileactions input#user_id').val(),
			is_front: (user_from == 'front' ? 1 : 0)
		},
		success: function(content) {
			$('#myModal').html(content);
			$('#myModal').modal();
		}
	});
}

function redirectToCalendar(wkoutId, wkoutLogId, assignedDate, ownWkFlag) {
	$.ajax({
		url: (user_from == 'front' ? siteUrl : siteUrl_frontend) + "ajax/redirectToCalendarLogged",
		type: 'POST',
		dataType: 'json',
		data: {
			loader: 'hide',
			wkoutId: wkoutId,
			wkoutLogId: wkoutLogId,
			assignedDate: assignedDate,
			ownWkFlag: ownWkFlag,
		},
		success: function(response) {
			if (response.result) {
				window.location.href = response.url;
			}
		}
	});
}