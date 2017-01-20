$('div.bannermsg').fadeOut(12000);
$(document).ready(function() {
	$('#xru_title').focus();
});
/*for tag*/
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
if ($('input.xru_Tags').length > 0 && $('input.xru_Tags').is(':visible')) {
	$('input.xru_Tags, input.mdl_fltrtag-input').tagsinput({
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
	$('input.xru_Tags').tagsinput('input').blur(function() {
		$('input.xru_Tags').tagsinput('add', $(this).val());
		$(this).val('');
	});
	if($('input.mdl_fltrtag-input').length){
		$('input.mdl_fltrtag-input').tagsinput('input').blur(function() {
			$('input.mdl_fltrtag-input').tagsinput('add', $(this).val());
			$(this).val('');
		});
	}
}
$('body').on('click', '#btn_revert', function(e) {
	e.preventDefault();
	var clicked = $('#introclear');
	resetNewXRciseData();
	exitEditSeqenceList($('#refreshseq'));
	clearImg(clicked);
	$('#messageContainer').addClass('hide');
});
$('body').on('click', '.img_clear', function(e) {
	e.preventDefault();
	var clearid = $(this).attr('data-clearid');
	clicked = $('#' + clearid);
	if (confirm('Are you sure, want to clear this image?')) {
		clearImg(clicked);
		$('#imageoption-modal').modal('hidecustom');
	}
	if (clearid == 'introclear') {
		$('#xrRecInsertForm').formValidation('revalidateField', 'xru_featImage');
	}
});

function resetNewXRciseData() {
	// Clear Data from New XR inputs, etc
	$('#xrRecInsertForm').find(':radio, :checkbox').removeAttr('checked').end().find('textarea, :text, select').val('');
	$('#xrRecInsertForm #xru_status').val(1);
	$('#xrRecInsertForm #list_muscles').val('');
	$('#xrRecInsertForm #list_equipments').val('');
	$('#xrRecInsertForm #xru_level').val(1);
	$('#xrRecInsertForm #xru_sports').val(2);
	$('#muscle_lists li, #equip_lists li').empty();
	$('.muscle-selectbox, .equip-selectbox').hide();
	// for muscle
	$('#muscle_lists li').append('<input type="hidden" name="xru_musprim" value="">');
	// for equipment
	$('#equip_lists li').append('<input type="hidden" name="xru_equip" value="">');
	// reset sequence
	$('#seq_list').empty();
	// reset tags
	$('input.xru_Tags').tagsinput('removeAll');
	$('input.xru_Tags').tagsinput('refresh');
	return false;
}

function clearImg(clicked) {
	// Clear Image from Seq or Feat_img
	var noImgUrl = clicked.attr('href');
	var startPoint = clicked.closest('.img-div');
	startPoint.find('.uploaded_image_thmb').attr('src', noImgUrl).end().find('.img_selected').val('').end().find('.img_preview').attr('src', siteUrl + noImgUrl).end();
	$('#preview_featimg').html('<i class="fa fa-file-image-o prevfeat"></i>');
	$('#xrRecInsertForm').data('formValidation').resetForm();
	return false;
}
/*trigger the preview img modal*/
function triggerImgPreviewModal(elemid) {
	var imgurl = $('#' + elemid).attr('src');
	if (imgurl != undefined && imgurl != '') {
		$('#preview_featimg').html('<img alt="' + __("Preview Image") + '" class="Preview_image" id="preview-featimg" src="' + imgurl + '"/>');
	} else {
		$('#preview_featimg').html('<i class="fa fa-file-image-o prevfeat"></i>');
	}
	$('#imageoption-modal').modal('hidecustom');
	$('#previewimg-modal').modal();
}
$('.info-icon').attr('title', function() {
	return $(this).next('.tooltip').text();
});
$('#xrRecInsertForm .tab-content').tooltip();
$('.seqerror').hide();
$('.seqerror small').text('');
var icon = siteUrl_frontend + 'assets/images/icons/picture-icon.png';
$("body").on("click", ".seq_btn", function(e) {
	var arrow = $(this).attr("data-class");
	switch (arrow) {
		case "add_seq":
			e.preventDefault();
			var last = $('#seq_list .seq-panel').length; // count <li>'s
			var order = parseInt(last) + 1; // Seq_order = count<li>'s +1
			var isValid = false;
			$('.seq-panel').each(function() {
				var descval = $(this).find('.img_selected').val();
				var imgval = $(this).find('.seq_desc').val();
				if (imgval != '' || descval != '') {
					isValid = true;
				} else {
					isValid = false;
					return false;
				}
			});
			if (isValid || !last) {
				$('.seqerror small').text('');
				$('.seqerror').hide();
				newSeq(icon, order);
			} else {
				$('.seqerror small').text('Please fill the below sequence(s) and then, try to add new sequence.');
				$('.seqerror').show();
			}
			break;
		default:
			break;
	}
});

function newSeq(icon, order) {
	var addIMGicon = icon; // url for icon file
	var seqORDER = order; // ORDER ID of :last <li>
	var carbon = '';
	carbon += '<li class="seq_order=' + seqORDER + ' seq-panel">';
	carbon += '<div class="row">';
	carbon += '<div class="mobpadding exersetcolumn-xr">';
	carbon += '<div class="border-xr full">';
	carbon += '<!--.seq_img -->';
	carbon += '<div class="col-xs-3 firstcell borderright">';
	carbon += '<div class="seq-check form-group checkbox-checker col-xs-4" style="display: none;">';
	carbon += '<div class="checkboxcolor">';
	carbon += '<label>';
	carbon += '<input type="checkbox" class="checkhidden" name="check_act[]" value="' + seqORDER + '" data-role="none" data-ajax="false"/>';
	carbon += '<span class="cr checkbox-circle"><i class="cr-icon fa fa-check"></i></span>';
	carbon += '</label>';
	carbon += '</div>';
	carbon += '</div>';
	carbon += '<div class="seq-img form-group img-div col-xs-12">';
	carbon += '<span class="img_thmb preCls datacol textcenter">';
	carbon += '<img id="seq-feature' + seqORDER + '" class="img-responsive img-thumbnail uploaded_image_thmb" tabindex="16" src="' + addIMGicon + '" alt="' + __("Sequence Image") + '">';
	carbon += '</span>';
	carbon += '<input type="hidden" class="img_selected" id="seq_img' + seqORDER + '" name="seqImg[]" value="">';
	carbon += '<div class="img-opt">';
	carbon += '<div class="trigger-imgopt" id="seqclear' + seqORDER + '" data-imgtagid="seq-feature' + seqORDER + '" data-hidnimgid="seq_img' + seqORDER + '" href="' + addIMGicon + '"></div>';
	carbon += '</div>';
	carbon += '</div>';
	carbon += '</div>';
	carbon += '<!--seq_desc -->';
	carbon += '<div class="col-xs-9 secondcell datacol">';
	carbon += '<div class="seq-desc form-group">';
	carbon += '<textarea id="seqDesc' + seqORDER + '" name="seqDesc[]" class="seq_desc form-control" tabindex="16" placeholder="' + __('No content, click to update') + '." data-ajax="false" data-role="none"></textarea>';
	carbon += '</div>';
	carbon += '</div>';
	carbon += '<div class="col-xs-2 aligncenter seq-sort hide">';
	carbon += '<span class="seq-move fa fa-arrows iconsize2"></span>';
	carbon += '</div>';
	carbon += '</div>';
	carbon += '</div>';
	carbon += '</div>';
	carbon += '</li>';
	$(carbon).appendTo('#seq_list');
	var focuselem = 'seqDesc' + seqORDER;
	document.getElementById(focuselem).focus();
}

function deleteSeqItem() {
	if ($("input.checkhidden:checkbox:checked").length > 0) {
		if (confirm('Are you sure? NOTE: This content will not be deleted from the database until you click "Save" button to update the entire record.')) {
			$("input.checkhidden:checkbox:checked").each(function() {
				var checkedval = $(this).val();
				$(this).closest('.seq-panel').remove();
			});
			var count_li = $("#seq_list .seq-panel").length;
			if (count_li > 0) {
				$("#seq_list .seq-panel").each(function() {
					$(this).removeClass();
					var $index = ($(this).index() + 1);
					$(this).prop("class", 'seq_order=' + $index + ' seq-panel');
					$(this).find('.checkhidden').val($index).end()
						.find('.img-thumbnail').attr('id', 'seq-feature' + $index).end()
						.find('.img_selected').attr('id', 'seq_img' + $index).end()
						.find('.trigger-imgopt').attr({
							'id': 'seqclear' + $index,
							'data-imgtagid': 'seq-feature' + $index,
							'data-hidnimgid': 'seq_img' + $index
						}).end()
						.find('.seq_desc').attr('id', 'seqDesc' + $index).end();
				});
			}
			var count_seqli = $("#seq_list .seq-panel").length;
			if (count_seqli <= 0) {
				exitEditSeqenceList($('#refreshseq'));
			}
		}
	} else {
		alert('Please select the sequence(s) before do this action!!!');
	}
	return false;
}
/*sequence sorting*/
var panelList = $('#xrRecInsertForm #seq_list');
panelList.sortable({
	tolerance: 'pointer',
	revert: 'invalid',
	placeholder: 'seq-panel dropspace',
	forceHelperSize: true,
	forcePlaceholderSize: true,
	handle: '.seq-move',
	axis: 'y',
	update: function(event, ui) {
		$('.seq-panel', panelList).each(function() {
			$(this).removeClass();
			var $index = ($(this).index() + 1);
			$(this).prop("class", 'seq_order=' + $index + ' seq-panel');
			$(this).find('.checkhidden').val($index).end()
				.find('.img-thumbnail').attr('id', 'seq-feature' + $index).end()
				.find('.img_selected').attr('id', 'seq_img' + $index).end()
				.find('.trigger-imgopt').attr({
					'id': 'seqclear' + $index,
					'data-imgtagid': 'seq-feature' + $index,
					'data-hidnimgid': 'seq_img' + $index
				}).end()
				.find('.seq_desc').attr('id', 'seqDesc' + $index).end();
		});
	}
});
panelList.disableSelection();
/*trigger image library modal and actions for imglib modal*/
$(document).on('click', 'a.edit-img', function(e) {
	e.preventDefault();
	if ($('#mdl_parentFolderId').length && $('#mdl_subFolderId').length) {
		$('#triggerid').val('');
		popuptriggerAjaxImgLibrary();
	}
	$('#imageoption-modal').modal('hidecustom');
	$('#mdl_popupimglibrary-modal').modal();
	initSimpleUpload();
});
$(document).on('click', '.trigger-imgopt', function() {
	if ($('#' + $(this).attr('data-hidnimgid')).val() != '') {
		$('#btn_imgpreview').attr('onclick', "triggerImgPreviewModal('" + $(this).attr('data-imgtagid') + "');");
		$('#btn_imgclear').attr('data-clearid', $(this).attr('id'));
		$('#imageoption-modal').modal();
		if ($("#data_XrImgs").length) {
			$('#data_XrImgs').attr('data-imgtagid', $(this).attr('data-imgtagid'));
			$('#data_XrImgs').attr('data-hidnimgid', $(this).attr('data-hidnimgid'));
		}
	} else {
		if ($("#data_XrImgs").length) {
			$('#data_XrImgs').attr('data-imgtagid', $(this).attr('data-imgtagid'));
			$('#data_XrImgs').attr('data-hidnimgid', $(this).attr('data-hidnimgid'));
		}
		$('a.edit-img').trigger('click');
	}
});
/*trigger image library modal and actions for imglib modal*/
function checkAllItems(selector) {
	var seqlist = $('#seq_list .seq-panel');
	if (seqlist.is(':visible')) {
		if ($(selector).hasClass('checked')) {
			$("#seq_list input:checkbox").prop('checked', false);
			$(selector).removeClass('checked');
		} else {
			$("#seq_list input:checkbox").prop('checked', true);
			$(selector).addClass('checked');
		}
		if ($('#seq_list .checkboxcolor label input[type="checkbox"]:checked').length > 0) {
			$('#xrRecInsertForm button i.allowActive').removeClass('datacol').addClass('activecol');
		} else {
			$('#xrRecInsertForm button i.allowActive').removeClass('activecol').addClass('datacol');
		}
	}
	return false;
}
$(document).on('change', '.checkboxcolor label input[type="checkbox"]', function() {
	if ($('.checkboxcolor label input[type="checkbox"]:checked').length > 0) {
		$('#xrRecInsertForm button i.allowActive').removeClass('datacol').addClass('activecol');
	} else {
		$('#xrRecInsertForm button i.allowActive').removeClass('activecol').addClass('datacol');
	}
});

function editSeqenceList(selector) {
	var count_seqli = $("#seq_list .seq-panel").length;
	if (count_seqli > 0) {
		$(selector).addClass('hide');
		$("#xrRecInsertForm .checkbox-checker").show();
		$("#xrRecInsertForm .checkboxcolor input:checkbox").prop('checked', false);
		$('#xrRecInsertForm button i.allowActive').removeClass('activecol').addClass('datacol');
		$('#xrRecInsertForm .optionmenu div.allowhide').removeClass('hide');
		$('#checkseq').removeClass('checked');
		$('#refreshseq').removeClass('hide');
		$('#addSeq').addClass('hide');
		$('.seq-sort').removeClass('hide');
		$('.seq-panel .firstcell .img-div').removeClass('col-xs-12').addClass('col-xs-8');
		$('.seq-panel .secondcell').removeClass('col-xs-9').addClass('col-xs-8');
		return false;
	} else {
		alert('Please add the sequence(s) before do this action!!!');
	}
}

function exitEditSeqenceList(selector) {
	$(selector).addClass('hide');
	$("#xrRecInsertForm .checkbox-checker").hide();
	$("#xrRecInsertForm .checkboxcolor input:checkbox").prop('checked', false);
	$('#xrRecInsertForm .optionmenu div.allowhide').addClass('hide');
	$('#checkseq').removeClass('checked');
	$('#editseq').removeClass('hide');
	$('#addSeq').removeClass('hide');
	$('.seq-sort').addClass('hide');
	$('.seq-panel .firstcell .img-div').removeClass('col-xs-8').addClass('col-xs-12');
	$('.seq-panel .secondcell').removeClass('col-xs-8').addClass('col-xs-9');
	return false;
}

function triggerXrFormReset() {
	$('#xrcisesaveopt-modal').modal('hidecustom');
	$('#messageContainer').addClass('hide');
	var unitid = $('#xrid').val();
	if (unitid != '') {
		$.post(siteUrl + 'ajax/ajaxInsertActivityfeed', {
			'actid': unitid,
			'method': 'exited',
			'type': 'exercise'
		}, function() {});
	}
	window.location.reload();
}

function triggerXrFormSave() {
	$('#xrcisesaveopt-modal').modal('hidecustom');
	$('#btn_saveclose').trigger('click');
}

function triggerXrFormSaveEdit() {
	$('#xrcisesaveopt-modal').modal('hidecustom');
	$('#btn_savecontn').trigger('click');
}

/*form validation*/
function parseYoutubeUrl(url) {
	var ytregExp = /^.*(?:(?:youtu\.be\/|v\/|vi\/|u\/\w\/|embed\/)|(?:(?:watch)?\?v(?:i)?=|\&v(?:i)?=))([^#\&\?]*).*/;
	return (url.match(ytregExp)) ? RegExp.$1 : false;
}

function parseVimeoUrl(url) {
	var vregExp = /(?:https?:\/\/(?:www\.)?)?vimeo.com\/(?:channels\/|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|)(\d+)(?:$|\/|\?)/,
		match = url.match(vregExp);
	return match ? match[3] : false;
}
FormValidation.Validator.videoUrl = {
	validate: function(validator, $field, options) {
		var url = $field.val();
		if (url === '') {
			return true;
		} else if (parseVimeoUrl(url) != false) {
			console.log(parseVimeoUrl(url))
			return true;
		} else if (parseYoutubeUrl(url) != false) {
			console.log(parseYoutubeUrl(url))
			return true;
		} else {
			return false;
		}
	}
};
WizardFormValidation();

function WizardFormValidation() {
	$('#xrRecInsertForm').formValidation({
		framework: 'bootstrap',
		excluded: ':disabled', // This option will not ignore invisible fields which belong to inactive panels
		fields: {
			xru_title: {
				validators: {
					notEmpty: {
						message: 'The title is required'
					}
				}
			},
			xru_type: {
				validators: {
					notEmpty: {
						message: 'The activity type is required'
					}
				}
			},
			xru_status: {
				validators: {
					notEmpty: {
						message: 'The status is required'
					}
				}
			},
			xru_featImage: {
				validators: {
					notEmpty: {
						message: 'The feature image is required'
					}
				}
			},
			xru_featVideo: {
				validators: {
					videoUrl: {
						message: 'The URL is not valid'
					}
				}
			},
			xru_mech: {
				validators: {
					notEmpty: {
						message: 'The mechanics type is required'
					}
				}
			},
			xru_level: {
				validators: {
					notEmpty: {
						message: 'The level is required'
					}
				}
			},
			xru_sports: {
				validators: {
					notEmpty: {
						message: 'The sports is required'
					}
				}
			},
			xru_force: {
				validators: {
					notEmpty: {
						message: 'The force is required'
					}
				}
			}
		}
	}).on('success.form.fv', function(e) {
		e.preventDefault(); // Prevent form submission
		// Some instances you can use are
		var $form = $(e.target), // The form instance
			fv = $(e.target).data('formValidation'); // FormValidation instance
		fv.defaultSubmit();
		$('#messageContainer').addClass('hide');
	}).on('success.field.fv', function(e, data) {
		$('#validation-errors').find('li[data-field="' + data.field + '"]').remove();
		$('#messageContainer').addClass('hide');
	}).on('err.field.fv', function(e, data) {
		// Get the messages of field
		var message = data.fv.getMessages(data.element);
		// Remove the field messages if they're already available
		$('#validation-errors').find('li[data-field="' + data.field + '"]').remove();
		$('<li/>').attr('data-field', data.field).html(message).appendTo('#validation-errors');
		$('#messageContainer').removeClass('hide');
		$('.modal-validerror').removeClass('hide');
		$('#errorMessage-modal').modal();
	}).on('err.form.fv', function(e, data) {
		$('#messageContainer').removeClass('hide');
	});
}

function isInArray(value, array) {
	return array.indexOf(value) > -1;
}

function blinkElement(selector) {
	$(selector).fadeOut('fast', function() {
		$(this).fadeIn('fast', function() {});
	});
}
/*muscle action*/
$(document).on('change', '#list_muscles', function() {
	var muscleval = $(this).val();
	var muscletext = $(this).find('option:selected').text();
	var musclecnt = $('#muscle_lists li > span.tag-item').length;
	if (musclecnt < 1) {
		$('#muscle_lists li').empty();
		var primselect = 'checked';
		var muscOth_val = '';
	} else {
		var primselect = '';
		var muscOth_val = '<input type="hidden" class="Othermuscle" name="chkdMusOth[]" value="' + muscleval + '">';
	}
	var muscleArr = [];
	$('#muscle_lists li > span.tag-item').each(function() {
		muscleArr.push($(this).attr('id'));
		if ($(this).attr('id') == muscleval) {
			blinkElement(this);
		} else {}
	});
	if (!isInArray(muscleval, muscleArr)) {
		$('#muscle_lists li').append('<span class="tag-item label label-info" id="' + muscleval + '"><label class="radio-primary"><input type="radio" name="xru_musprim" value="' + muscleval + '" ' + primselect + ' title="Primary Muscle" data-role="none" data-ajax="false">' + muscletext + '</label><span data-role="remove"></span>' + muscOth_val + '</span> ');
	} else {}
	$('#list_muscles').val('');
	$('.muscle-selectbox').hide();
});
// Remove muscle icon clicked
$(document).on('click', '#muscle_lists li > span.tag-item [data-role=remove]', function(event) {
	if ($('#muscle_lists li > span.tag-item').length > 1) {
		if ($(this).closest('span.tag-item').find('[type=radio]').is(':checked')) {
			$(this).closest('span.tag-item').remove();
			$('#muscle_lists li > span.tag-item:first-child').find('[type=radio]').prop('checked', true);
		} else {
			$(this).closest('span.tag-item').remove();
		}
	} else {
		$(this).closest('span.tag-item').remove();
		$('#muscle_lists li').empty();
		$('#muscle_lists li').append('<input type="hidden" name="xru_musprim" value="">');
	}
});
// change the other muscles
$(document).on('change', 'input[name=xru_musprim]', function() {
	$('#muscle_lists li > span.tag-item').each(function() {
		$(this).find('.Othermuscle').remove();
		var muscOth_val = '<input type="hidden" class="Othermuscle" name="chkdMusOth[]" value="' + $(this).attr('id') + '">';
		$(this).append($(muscOth_val));
	});
	$(this).closest('span.tag-item').find('.Othermuscle').remove();
});

function showMuscleSelectbox() {
	$('.muscle-selectbox').show();
	$('#list_muscles').val('').focus();
}

/*equipment action*/
$(document).on('change', '#list_equipments', function() {
	var equipval = $(this).val();
	var equiptext = $(this).find('option:selected').text();
	var equipcnt = $('#equip_lists li > span.tag-item').length;
	if (equipcnt < 1) {
		$('#equip_lists li').empty();
		var primselect = 'checked';
		var equipOth_val = '';
	} else {
		var primselect = '';
		var equipOth_val = '<input type="hidden" class="Otherequip" name="chkdEquipOth[]" value="' + equipval + '">';
	}
	var equipArr = [];
	$('#equip_lists li > span.tag-item').each(function() {
		equipArr.push($(this).attr('id'));
		if ($(this).attr('id') == equipval) {
			blinkElement(this);
		} else {}
	});
	if (!isInArray(equipval, equipArr)) {
		$('#equip_lists li').append('<span class="tag-item label label-info" id="' + equipval + '"><label class="radio-primary"><input type="radio" name="xru_equip" value="' + equipval + '" ' + primselect + ' title="Primary Equipment" data-role="none" data-ajax="false">' + equiptext + '</label><span data-role="remove"></span>' + equipOth_val + '</span> ');
	} else {}
	$('#list_equipments').val('');
	$('.equip-selectbox').hide();
});
// Remove muscle icon clicked
$(document).on('click', '#equip_lists li > span.tag-item [data-role=remove]', function(event) {
	if ($('#equip_lists li > span.tag-item').length > 1) {
		if ($(this).closest('span.tag-item').find('[type=radio]').is(':checked')) {
			$(this).closest('span.tag-item').remove();
			$('#equip_lists li > span.tag-item:first-child').find('[type=radio]').prop('checked', true);
		} else {
			$(this).closest('span.tag-item').remove();
		}
	} else {
		$(this).closest('span.tag-item').remove();
		$('#equip_lists li').empty();
		$('#equip_lists li').append('<input type="hidden" name="xru_equip" value="">');
	}
});
// change the other muscles
$(document).on('change', 'input[name=xru_equip]', function() {
	$('#equip_lists li > span.tag-item').each(function() {
		$(this).find('.Otherequip').remove();
		var equipOth_val = '<input type="hidden" class="Otherequip" name="chkdEquipOth[]" value="' + $(this).attr('id') + '">';
		$(this).append($(equipOth_val));
	});
	$(this).closest('span.tag-item').find('.Otherequip').remove();
});

function showEquipmentSelectbox() {
	$('.equip-selectbox').show();
	$('#list_equipments').val('').focus();
}

$(document).on('click keyup', '.tags-block .bootstrap-tagsinput', function() {
	if ($('#xrRecInsertForm').is(':visible')) {
		if ($('#xrRecInsertForm .tt-menu.tt-open').is(':visible')) {
			$('#xrRecInsertForm .tab-content').animate({
				scrollTop: $('#xrRecInsertForm .tab-content')[0].scrollHeight
			}, 1000);
		}
	}
});
$(document).on("keypress", ":input:not(textarea)", function(ev) {
	var code = ev.keyCode || ev.which;
	if (code === 13) {
		ev.preventDefault();
		return false;
	}
});
$(document).on('shown.bs.modal', '.modal', function() {
	$(document.body).addClass('modal-open');
}).on('hidden.bs.modal', '.modal', function() {
	$(document.body).removeClass('modal-open');
	$('.modal:visible').length && $(document.body).addClass('modal-open');
});