/*uploader section scripts starts*/
var progresshead = $('#progress-head'),
	uploadhead = $('#uploader-head'),
	uploaddiv = $('.upload-div'),
	headerprogress = $('.header-progress');

function initSimpleUploadMethod() {
	console.log('init SimpleUpload');
	var errFilename = '';
	var btnupload = document.getElementById('image_upload'),
		progressBar = document.getElementById('progressBar'),
		progressbarOuter = document.getElementById('progressbarOuter'),
		divBoxcover = document.getElementById('uploadListing'),
		msgBoxcover = document.getElementById('uploadError');
	var imguploader = new ss.SimpleUpload({
		button: btnupload,
		url: siteUrl + 'exercise/uploadImg?action=upload',
		name: 'uploadfile',
		dropzone: 'dragndropimage',
		multipart: true,
		hoverClass: 'hover',
		focusClass: 'focus',
		responseType: 'json',
		maxSize: 2560,
		allowedExtensions: ["jpg", "jpeg", "png"],
		onSizeError: function(filename, fileSize) {
			errFilename += '<b>' + filename + '</b>, ';
			msgBoxcover.innerHTML = errFilename.slice(0, -2) + ' file(s) size is too large. (max file size 2560kb)';
			return false;
		},
		onExtError: function(filename, extension) {
			msgBoxcover.innerHTML = 'Extension "<b>' + extension + '</b>" not allowed, please choose jpg, jpeg and png file.';
			return false;
		},
		onChange: function() {
			errFilename = '';
		},
		startXHR: function() {
			uploadhead.addClass('hide');
			uploaddiv.addClass('hide');
			progresshead.removeClass('hide');
			headerprogress.removeClass('hide');
			progressbarOuter.style.display = 'block'; // make progress bar visible
			this.setProgressBar(progressBar);
		},
		onSubmit: function() {
			msgBoxcover.innerHTML = ''; // empty the message box
			var self = this;
			self.setData({
				upfolder: $('#uploadfolderId').val(),
				currfolder: $('#currentFolderId').val(),
				parentfolder: $('#parentFolderId').val(),
				subfolder: $('#subFolderId').val(),
				replaceflag: $('#replaceflag').val(),
				imageid: $('#curr_imgid').val()
			});
		},
		onComplete: function(filename, response) {
			progressbarOuter.style.display = 'none'; // hide progress bar when upload is completed
			if (!response) {
				msgBoxcover.innerHTML = 'Unable to upload file';
				return;
			}
			if (response.success === false) {
				uploadhead.removeClass('hide');
				uploaddiv.removeClass('hide');
				progresshead.addClass('hide');
				headerprogress.addClass('hide');
				$('#uploadListing').addClass('hide').empty();
				msgBoxcover.innerHTML = response.divImage;
				return;
			}
			if (response.success === true) {
				divBoxcover.innerHTML = $('#uploadListing').html() + response.divImage;
				$('#uploadListing').removeClass('hide');
			} else {
				if (response.msg) {
					msgBoxcover.innerHTML = escapeTags(response.msg);
				} else {
					uploadhead.removeClass('hide');
					uploaddiv.removeClass('hide');
					progresshead.addClass('hide');
					headerprogress.addClass('hide');
					$('#uploadListing').addClass('hide').empty();
					msgBoxcover.innerHTML = 'An error occurred and the upload failed.';
				}
			}
		},
		onError: function() {
			progressbarOuter.style.display = 'none';
			msgBoxcover.innerHTML = 'Unable to upload file';
			uploadhead.removeClass('hide');
			uploaddiv.removeClass('hide');
			progresshead.addClass('hide');
			headerprogress.addClass('hide');
			$('#uploadListing').addClass('hide').empty();
		}
	});
}
$(document).ready(function() {
	document.getElementById('image_upload').addEventListener('click', function() {
		document.getElementById('files').click();
	});
});
$(document).on('click', '#progressBack', function() {
	uploadhead.removeClass('hide');
	uploaddiv.removeClass('hide');
	progresshead.addClass('hide');
	headerprogress.addClass('hide');
	$('#uploadListing').addClass('hide');
});
/*uploader section scripts end*/

/*folder section scripts starts*/
$('div.bannermsg').fadeOut(12000);
$('#popupfilteract-modal').on('shown.bs.modal', function() {
	$('#fltrtitle-input').focus();
});
$('#popupimgstatus-modal').on('shown.bs.modal', function() {
	$('#imgchecked-status').focus();
});
var curimgelem;
var folderlib = $('.img-lib-folder');
var imgList = $('#img_listing');
var uploadimgList = $('#uploadListing');
var urlroot = window.location;

function ajaxInsertActivityfeed(method, type) {
	var imgid = $('#curr_imgid').val();
	if (imgid) {
		$.post(siteUrl + 'ajax/ajaxInsertActivityfeed', {
			'actid': imgid,
			'method': method,
			'type': type
		}, function() {});
	}
}

function triggerImgPrevModal(elem) {
	var imgurl = $(elem).attr('data-itemurl');
	var imgname = $(elem).attr('data-itemname');
	if (imgname != undefined && imgname != '') {
		$('#preview-imgname').text(imgname);
	} else {
		$('#preview-imgname').text('');
	}
	if (imgurl != undefined && imgurl != '') {
		$('#preview_libimg').html('<img alt="' + __("Preview Image") + '" class="Preview_image" id="previewlibimg" src="' + siteUrl + imgurl + '"/>');
		$('#preview-btn').attr('data-itemurl', $(elem).attr('data-itemurl'));
	} else {
		$('#preview_libimg').html('<i class="fa fa-file-image-o prevfeat"></i>');
		$('#preview-btn').attr('data-itemurl', '');
	}
	$('#popupimgact-modal').modal('hide');
	$('#popupimgprev-modal').modal();
	curimgelem = elem;
	setTimeout(function() {
		ajaxInsertActivityfeed('previewed', 'image');
	}, 350);
}

function triggerImgOptionModal(elem) {
	var imgurl = $(elem).attr('data-itemurl');
	var imgname = $(elem).attr('data-itemname');
	if (imgname != undefined && imgname != '') {
		$('#preview-btn').attr('data-itemname', imgname);
		$('span.imgTitle').text(imgname);
	} else {
		$('#preview-btn').attr('data-itemname', '');
		$('span.imgTitle').text('');
	}
	if (imgurl != undefined && imgurl != '') {
		$('#preview-btn').attr('data-itemurl', imgurl);
	} else {
		$('#preview-btn').attr('data-itemurl', '');
	}
	$('#popupimgact-modal').modal();
	$('#replaceflag').val('');
}
$(document).on('click', '#prevmdloption', function() {
	triggerImgOptionModal(curimgelem);
});
var tagarry = [];
$.ajax({
	url: siteUrl + 'ajax/tagnames?user_from=front&cp=' + user_allow_page,
	dataType: 'json',
	async: false,
	encode: true,
	cache: false
}).done(function(data) {
	var taglist = [];
	if (data) {
		$.each(data.tagnames, function(i, val) {
			taglist.push(val);
		});
		tagarry = taglist;
	}
});
var tagnames = new Bloodhound({
	datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
	queryTokenizer: Bloodhound.tokenizers.whitespace,
	local: $.map(tagarry, function(tagname) {
		return {
			name: tagname
		};
	})
});
tagnames.initialize();
$('input.fltrtag-input, input.imgdata-tag, input.imgtag-input, input.mdl_fltrtag-input').tagsinput({
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
$('input.fltrtag-input').tagsinput('input').blur(function() {
	$('input.fltrtag-input').tagsinput('add', $(this).val());
	$(this).val('');
});
$('input.imgdata-tag').tagsinput('input').blur(function() {
	$('input.imgdata-tag').tagsinput('add', $(this).val());
	$(this).val('');
});
$('input.imgtag-input').tagsinput('input').blur(function() {
	$('input.imgtag-input').tagsinput('add', $(this).val());
	$(this).val('');
});
if ($('input.mdl_fltrtag-input').length) {
	$('input.mdl_fltrtag-input').tagsinput('input').blur(function() {
		$('input.mdl_fltrtag-input').tagsinput('add', $(this).val());
		$(this).val('');
	});
}
$(document).on('click', '#btn-filterreset', function() {
	$('#filteract-form')[0].reset();
	$('input.fltrtag-input').tagsinput('removeAll');
	$('input.fltrtag-input').tagsinput('refresh');
});

function triggerImageData(opt) {
	$('#popupimgact-modal').modal('hide');
	$('#popupimgprev-modal').modal('hide');
	$('.img-lib-folder').addClass('hide');
	$('.exercise-lib-imgdata').removeClass('hide');
	$('.uploader-section').addClass('hide');
	$('.folder-section').removeClass('hide');
	$('#imgdataBack').attr('data-databack', opt);
	var cururl = window.location.pathname;
	$('#imgdataBack').attr('data-backurl', cururl);
	setTimeout(function() {
		ajaxInsertActivityfeed('opened', 'image data');
	}, 350);
}

function resetImgDataForm() {
	$('#image_data_form').children().find('input,select,textarea').each(function() {
		$(this).val('');
	});
	$('.imgDataUrl').find('input').each(function() {
		$(this).val('');
	});
	$('input.imgdata-tag, input.imgtag-input').tagsinput('removeAll');
	$('input.imgdata-tag, input.imgtag-input').tagsinput('refresh');
}

function triggerImgDataRevert() {
	resetImgDataForm();
	$('#popupfinalact-modal').modal('hidecustom');
	$('.crop-reset').trigger('click');
	var curimgid = $('#curr_imgid').val();
	if (curimgid != '') {
		var objimgrow = $('#' + curimgid + '.imgRecord');
		var objdata = objimgrow.find('.img-itemname');
		$('#imgdata-title').val(objdata.attr('data-itemname'));
		$('#imgdata-status').val(1);
		var tags = $('#img_tags' + objdata.attr('data-itemid')).val();
		$('input.imgdata-tag').tagsinput('add', tags);
		if ($('#image_data_form').is(':visible')) {
			var type = 'image data';
		} else if ($('#popupimgeditor-model').is(':visible')) {
			var type = 'image';
		}
		setTimeout(function() {
			ajaxInsertActivityfeed('exited', type);
		}, 350);
	}
}
$(document).on('click', '.imgcheck-opt', function() {
	if (imgList.is(':visible') || uploadimgList.is(':visible')) {
		$(".checkbox-checker").toggle('slow');
		$(".header-toggle1, .header-toggle2").toggle();
		$(".checkboxcolor input:checkbox").prop('checked', false);
		$('.check-image>i').removeClass('checked');
		$('.checked-opt').removeClass('active');
	} else {
		return false;
	}
});

function checkAllItems(selector) {
	$('.tag-imgname').text('');
	if (imgList.is(':visible')) {
		if ($(selector).hasClass('checked')) {
			$("#img_listing input:checkbox").prop('checked', false);
			$(selector).removeClass('checked');
		} else {
			$("#img_listing input:checkbox").prop('checked', true);
			$(selector).addClass('checked');
		}
		if ($('#img_listing .checkboxcolor label input[type="checkbox"]:checked').length > 0) {
			$('.check-header .checked-opt').addClass('active');
		} else {
			$('.check-header .checked-opt').removeClass('active');
		}
	} else if (uploadimgList.is(':visible')) {
		if ($(selector).hasClass('checked')) {
			$("#uploadListing input:checkbox").prop('checked', false);
			$(selector).removeClass('checked');
		} else {
			$("#uploadListing input:checkbox").prop('checked', true);
			$(selector).addClass('checked');
		}
		if ($('#uploadListing .checkboxcolor label input[type="checkbox"]:checked').length > 0) {
			$('.header-progress-opt .checked-opt').addClass('active');
		} else {
			$('.header-progress-opt .checked-opt').removeClass('active');
		}
	}
	return false;
}
$(document).on('change', '.checkboxcolor label input[type="checkbox"]', function() {
	if ($('.checkboxcolor label input[type="checkbox"]:checked').length > 0) {
		$('.checked-opt').addClass('active');
	} else {
		$('.checked-opt').removeClass('active');
	}
	$('.tag-imgname').text('');
});
$(document).on('click', '.checked-opt', function() {
	if ($(this).hasClass('active')) {
		$('#popupchekdact-modal').modal();
		var checkedimg = new Array();
		$('form input[name="check_act[]"]').each(function() {
			if (this.checked) {
				checkedimg.push($(this).val());
			} else {}
		});
		getCommonImgTags(checkedimg);
	} else {
		if ($('.checkboxcolor label input[type="checkbox"]:checked').length > 0) {} else {
			alert('Please select the image(s) before do this action!!!');
		}
		return false;
	}
});

function getCommonImgTags(imageids) {
	$('input.imgtag-input').tagsinput('removeAll');
	$('input.imgtag-input').tagsinput('refresh');
	$.ajax({
		url: siteUrl + 'ajax/ajaxGetImageCommonTags',
		type: 'GET',
		dataType: 'json',
		data: {
			imgids: imageids
		},
		success: function(data) {
			if (data.success) {
				$('input.imgtag-input').tagsinput('add', data.img_tags);
			}
		},
		error: function(data) {
			console.log(JSON.stringify(data.img_tags));
		}
	});
}

function triggerImageDataBack() {
	var optdiv = $('#imgdataBack').attr('data-databack');
	if (optdiv == 'uploader') {
		$('.folder-section').addClass('hide');
		$('.uploader-section').removeClass('hide');
	} else {
		$('.folder-section').removeClass('hide');
		$('.img-lib-folder').removeClass('hide');
		$('.exercise-lib-imgdata').addClass('hide');
		$('.uploader-section').addClass('hide');
	}
	history.pushState('', 'My Workouts - Images', $('#imgdataBack').attr('data-backurl'));
}

var imgcount = 0;
var itemcnt = 0;
var limitcnt = 0;
var fltr_limitcnt = 0;
/*initial load on page*/
function fetchMoreRecords() {
	limitcnt = limitcnt + 10;
	$.ajax({
		url: siteUrl + 'ajax/getAjaxShowMoreImages',
		type: 'GET',
		data: {
			fid: $('#parentFolderId').val(),
			subfid: $('#subFolderId').val(),
			slimit: limitcnt,
			elimit: 10
		},
		encode: true,
		cache: false,
		success: function(data) {
			var imgresultss = [JSON.parse(data)];
			if (imgresultss) {
				imgList.find('.filtering').removeClass('filtering');
				if (renderToImg(filterImgRecords(imgresultss))) {
					if (imgcount > 10 && itemcnt == 10) {
						loadAjaxSend = true;
					}
				}
			}
		},
		error: function(data) {
			console.log(JSON.stringify(data));
		}
	});
	return true;
}
/*proccessing for filtering*/
$('#filteract-form').submit(function(e) {
	imgList.scrollTop(0);
	fltr_limitcnt = 0;
	e.preventDefault();
	e.stopImmediatePropagation();
	fetchFilteredRecords('init');
	$('#popupfilteract-modal').modal('hide');
});

function fetchFilteredRecords(opt) {
	var searchText = $('#fltrtitle-input').val(); // Filter : Search Input Text    
	var searchTag = $("input.fltrtag-input").tagsinput('items'); // Filter : Search Tag
	var searchsort = $("select#fltrsort-select").val(); // Filter : Search sort
	if ($('#parentFolderId').val() == '') {
		var parentfolderid = 0
	} else {
		var parentfolderid = $('#parentFolderId').val();
	} // Filter : Parent Folder Id
	if ($('#subFolderId').val() == '') {
		var subfolderid = 0
	} else {
		var subfolderid = $('#subFolderId').val();
	} // Filter : Sub Folder Id
	var searchTags = '';
	searchTag.toString();
	$.each(searchTag, function(i, val) {
		if (val != '') {
			searchTags += "'" + val + "'";
		}
		if (i != searchTag.length - 1) {
			searchTags += ', ';
		}
	});
	/*fetching imglist*/
	var dataToSend = {
		search_title: searchText,
		search_tag: searchTags,
		search_sort: searchsort,
		fid: parentfolderid,
		subfid: subfolderid,
		slimit: fltr_limitcnt,
		elimit: 10
	}; //console.log(dataToSend)
	$.ajax({
		type: 'POST',
		url: siteUrl + 'ajax/imgFilter',
		data: dataToSend,
		encode: true,
		cache: false,
		success: function(data) {
			var imgresponse = [JSON.parse(data)]; // console.log(response)
			if (imgresponse) {
				if (opt == 'init') {
					imgList.empty().addClass('hide');
					imgList.addClass('filtering');
				}
				if (renderToImg(filterImgRecords(imgresponse))) {
					if (imgcount > 10 && itemcnt == 10) {
						loadAjaxSend = true;
						imgList.addClass('filtering');
					}
				}
			}
		}
	});
	return true;
}

function filterImgRecords(records) {
	/* TEST for DATA in ARRAY */
	var demo = records;
	var imgs;
	var flag = 0;
	imgcount = demo[0].items.rescnt;
	itemcnt = demo[0].items.itemcnt;
	for (var j = 0; j < demo.length; j++) {
		flag = 1; // flag if RESPONSE contains data
		imgs = demo[j].items.itemlist;
		break;
	}
	imgs = flag ? imgs : []; // if no content, demo=0, otherwise demo=[array]
	// console.log(imgs) // console.log(tags)
	return [imgs];
}

function renderToImg(data) {
	var filteredFiles = [];
	var filteredTags = [];
	if (Array.isArray(data[0])) {
		data[0].forEach(function(d) {
			filteredFiles.push(d);
		});
	}
	/* Empty the old result and make the new one */
	if (!filteredFiles.length) {
		if (imgList.find('li.imgRecord').length) {
			folderlib.find('.nothingfound').hide();
		} else {
			folderlib.find('.nothingfound').show();
		}
		return false;
	} else {
		folderlib.find('.nothingfound').hide();
		filteredFiles.forEach(function(f) {
			if ($('.header-toggle2.check-header').is(':visible')) {
				var display = 'block';
			} else {
				var display = 'none';
			}
			if (f.img_url != '' && f.img_url != null) {
				var testedimg = f.img_url;
				var dummyicom = '';
			} else {
				var testedimg = '';
				var dummyicom = '<i class="fa fa-file-image-o datacol" style="font-size:50px;"></i>';
			}
			var attribute = 'data-itemid="' + f.img_id + '" data-itemname="' + f.img_title + '" data-itemurl="' + testedimg + '" data-itemtype="folder"';
			var rec = '<li class="imgRecord" id="' + f.img_id + '">';
			rec += '<div class="imgRecordDataFrame col-xs-12 col-sm-12">';
			rec += '<a href="javascript:void(0);" class="col-xs-12 col-sm-12 imgFrame-full" data-ajax="false" data-role="none">';
			rec += '<div class="checkbox-checker col-xs-2 col-sm-2" style="display: ' + display + ';"><div class="checkboxcolor">';
			rec += '<label><input data-role="none" data-ajax="false" type="checkbox" class="checkhidden" name="check_act[]" value="' + f.img_id + '">';
			rec += '<span class="cr checkbox-circle"><i class="cr-icon fa fa-check"></i></span>';
			rec += '</label></div></div>';
			rec += '<div class="col-xs-3 col-sm-3 thumb-img" ' + attribute + ' onclick="triggerImgPrevModal(this);"' + (testedimg != '' ? ' style="background-image: url(' + siteUrl + testedimg + ');"' : '') + '>' + dummyicom + '</div>';
			rec += '<div class="col-xs-7 col-sm-7 img-itemname" ' + attribute + ' onclick="triggerImgOptionModal(this);">';
			rec += '<div class="altimgtitle break-img-name">' + f.img_title + '</div><div class="item-info">' + f.default+'</div>';
			filteredTags = f.taglist;
			var i = 0;
			var tags = '';
			var taglist = '';
			filteredTags.forEach(function(t) {
				if (f.img_id == t.img_id) {
					if (i == 0) {
						tags += t.tag_title;
						taglist += t.tag_title;
					} else {
						tags += ', ' + t.tag_title;
						taglist += ',' + t.tag_title;
					}
					i++;
				}
			});
			if (tags != '') {
				rec += '<div class="img-tags"><span class="info-bold">' + __('Tags') + ': </span>' + tags + '</div>';
			}
			rec += '<input type="hidden" id="img_tags' + f.img_id + '" value="' + taglist + '"/>';
			rec += '</div>';
			rec += '</a>';
			rec += '</div>';
			rec += '</li>';
			var file = $(rec);
			file.appendTo(imgList);
		});
	}
	// Show the generated elements
	imgList.removeClass('hide');
	return true;
}

if (urlroot.hash == '#upload-image') {
	$('#replaceflag').val('');
	if ($('#parentfolder-div').hasClass('hide') == false) {
		$('#popupfldrslct-modal').modal();
	} else if ($('#subfolder-div').hasClass('hide') == false) {
		$('#popupfldrslct-modal').modal();
	}
}
$(document).on('hidden.bs.modal', '#popupfldrslct-modal', function() {
	if (urlroot.hash != '') {
		history.pushState('', 'My Workouts - Images', urlroot.pathname);
	}
});

function triggerSelectFolderModal() {
	$('#replaceflag').val('');
	if ($('#parentfolder-div').hasClass('hide') == false) {
		$('#popupfldrslct-modal').modal();
		return false;
	} else if ($('#subfolder-div').hasClass('hide') == false) {
		$('#popupfldrslct-modal').modal();
		return false;
	} else {
		triggerUploader();
		var currLoc = window.location.pathname;
		$('#uploaderBack').attr('href', currLoc);
		$('#uploadfolderId').val($('#currentFolderId').val());
	}
	enableImageOptions();
}
$(document).on('click', 'button.folder-select', function() {
	$('#uploadfolderId').val($(this).attr('id'));
	var currLoc = window.location.pathname;
	currLoc = currLoc.replace(/\/+$/, '');
	$('#uploaderBack').attr('href', currLoc);
	if ($('#currentFolderId').val() != '') {
		history.pushState('', '', currLoc + '/' + $(this).attr('id'));
		$('#subFolderId').val($(this).attr('id'));
	} else {
		history.pushState('', '', currLoc + '/1/' + $(this).attr('id'));
		$('#parentFolderId').val(1);
		$('#subFolderId').val($(this).attr('id'));
		$('#currentFolderId').val(1);
	}
	triggerUploader();
	enableImageOptions();
	$('#popupfldrslct-modal').modal('hide');
});

function triggerImgReplace() {
	if (confirm('Are you sure, you want to replace this image?')) {
		triggerUploader();
		$('#replaceflag').val('replace');
		var currLoc = window.location.pathname;
		$('#uploaderBack').attr('href', currLoc);
		$('#uploadfolderId').val($('#currentFolderId').val());
		$('#popupimgact-modal').modal('hide');
		$('#popupimgprev-modal').modal('hide');
		enableImageOptions();
		return true;
	}
	return false;
}
enableImageOptions();

function enableImageOptions() {
	var parentid = $('#parentFolderId').val();
	var subid = $('#subFolderId').val();
	if (parentid == 1) {
		$('#popupimgact-modal .allowedit').removeClass('hide');
	} else {
		$('#popupimgact-modal .allowedit').addClass('hide');
	}
	if (subid == 4) {
		$('#popupimgact-modal .allowopt').addClass('hide');
	} else {
		$('#popupimgact-modal .allowopt').removeClass('hide');
	}
}

function triggerUploader() {
	initSimpleUploadMethod();
	$('.folder-section').addClass('hide');
	$('.uploader-section').removeClass('hide');
}

function triggerImgEditorModal() {
	$('#popupimgact-modal').modal('hide');
	$('#popupimgprev-modal').modal('hide');
	$('#popupimgeditor-model').modal();
	$('.trigger_crop').attr('data-prefix', '');
	setTimeout(function() {
		ajaxInsertActivityfeed('opened', 'image');
	}, 350);
}

function triggerImgTagModal() {
	$('#popupimgact-modal').modal('hide');
	$('#popupimgprev-modal').modal('hide');
	$('#popupimgtag-modal').modal();
}

function triggerCheckedTag() {
	$('#popupchekdact-modal').modal('hide');
	$('#popupimgtag-modal').modal();
}

function triggerCheckedStatus() {
	$('#popupchekdact-modal').modal('hide');
	$('#popupimgstatus-modal').modal();
}

function triggerChangeStatus() {
	if ($('#imgchecked-status').val() != '' && $('#imgchecked-status').val() != 1) {
		if (confirm('Selected image(s) may currently be used by other exercise records. Are you sure you wish to continue?')) {
			return true;
		}
	} else if ($('#imgchecked-status').val() == 1) {
		return true;
	} else {
		alert('Please select any one option!!!');
	}
	return false;
}

function triggerShowMoreImage() {
	if (imgList.hasClass('filtering')) {
		fltr_limitcnt = fltr_limitcnt + 10;
		fetchFilteredRecords('showmore');
	} else {
		fetchMoreRecords();
	}
	return false;
}

function triggerImgDuplicate() {
	if (confirm('Are you sure, want to duplicate this image?')) {
		return true;
	}
	return false;
}

function triggerImgDelete() {
	if (confirm('Are you sure, want to delete this image?')) {
		return true;
	}
	return false;
}
var loadAjaxSend = true;
$(document).ready(function() {
	if (imgList.length) {
		imgList.bind('scroll', function(ev) {
			$('html, body').animate({
				scrollTop: imgList.position().top
			}, 'slow');
			var scrollTop = Math.round($(this).scrollTop());
			var scrollHeight = $(this)[0].scrollHeight;
			// console.log(scrollTop + $(this).innerHeight() + '===' + scrollHeight);
			if (loadAjaxSend) {
				if (scrollTop + $(this).innerHeight() == scrollHeight || scrollTop + $(this).innerHeight() == scrollHeight - 1 || scrollTop + $(this).innerHeight() == scrollHeight + 1) {
					loadAjaxSend = false;
					setTimeout(function() {
						ev.preventDefault();
						if (ev.handled !== true) {
							ev.handled = true;
							triggerShowMoreImage();
						}
					}, 200);
				}
			}
		});
		if (getBrowserZoomLevel() < 100) {
			AutoShowMore();
		}
	}
});

function AutoShowMore() {
	var x = 1,
		loopcnt = getAjaxSendCount();
	while (x <= loopcnt) {
		loadAjaxSend = false;
		setTimeout(function() {
			if (imgList.is(':visible') && imgList.find('li.imgRecord').length && getBrowserZoomLevel() < 100) {
				triggerShowMoreImage();
			}
		}, 200);
		x = x + 1;
	}
	return;
}

$(window).resize(function(ev) {
	if (imgList.length && imgList.is(':visible') && imgList.find('li.imgRecord').length && !imgList.hasVScrollBar()) {
		loadAjaxSend = false;
		setTimeout(function() {
			ev.preventDefault();
			if (ev.handled !== true) {
				ev.handled = true;
				triggerShowMoreImage();
			}
		}, 200);
	}
});

$(document).on('click', '.thumb-img, .img-itemname, .upload-imgrow', function() {
	resetImgDataForm();
	var check = $(this).attr('data-itemtype');
	if (check == "upload") {
		$('#prevmdloption').addClass('hide');
		$('#imgdata-btn').attr('onclick', "triggerImageData('uploader')");
		$('#imgraplace-btn').parent().addClass('hide');
	} else {
		$('#prevmdloption').removeClass('hide');
		$('#imgdata-btn').attr('onclick', "triggerImageData('folder')");
		//$('#imgraplace-btn').parent().removeClass('hide');
	}
	$('#btn-inserttag').attr('data-imgid', $(this).attr('data-itemid'));
	$('#curr_imgid').val($(this).attr('data-itemid'));
	$('#imgdata-title').val($(this).attr('data-itemname'));
	$('#imgdata-status').val(1);
	var tags = $('#img_tags' + $(this).attr('data-itemid')).val();
	$('input.imgdata-tag').tagsinput('add', tags);
	$('input.imgtag-input').tagsinput('add', tags);
});
$('form#imglibrary_form').keypress(function(ev) {
	var code = ev.keyCode || ev.which;
	if (code === 13) {
		ev.preventDefault();
		return false;
	}
});
$(document).on('hidden.bs.modal', '#popupimgeditor-model.modal', function() {
	history.pushState('', 'My Workouts - Images', window.location.pathname);
	$('#croppedData').val('');
});
/*folder section scripts end*/

/*save and save & continue action*/
if (editimgid != '' && editimgid != undefined) {
	if (saveaction == 'editImgData') {
		$('body').append('<div id="fade-out" class="modal-backdrop fade in"></div>');
		var objimgrow = $('#' + editimgid + '.imgRecord');
		var objdata = objimgrow.find('.img-itemname');
		$('#imgdata-title').val(objdata.attr('data-itemname'));
		$('#imgdata-status').val(1);
		var tags = $('#img_tags' + objdata.attr('data-itemid')).val();
		$('input.imgdata-tag').tagsinput('add', tags);
		triggerImageData('folder');
		$('body #fade-out').remove();
	} else if (saveaction == 'editImg') {
		$('body').append('<div id="fade-out" class="modal-backdrop fade in"></div>');
		var objimgrow = $('#' + editimgid + '.imgRecord');
		$currentElement = objimgrow.find('.thumb-img');
		setTimeout(function() {
			triggerImgEditorModal();
			$('body #fade-out').remove();
		}, 800);
	} else {

	}
}
if (allowTour) {
	/*bootstrap tour*/
	(function() {
		var tour = new Tour({
			storage: false,
			template: "<div class='popover tour'><div class='arrow'></div><h3 class='popover-title'></h3><div class='popover-content'></div><nav class='popover-navigation'><div class='btn-group'><button class='btn btn-sm btn-default' data-role='prev' data-ajax='false'>Prev</button><button class='btn btn-sm btn-default' data-role='next' data-ajax='false'>Next</button></div><button class='btn btn-sm btn-default btn-end' data-role='end' data-ajax='false'>End tour</button></nav><div class='popover-content-custom'><input type='checkbox' name='hide_tour' value='1' onclick='notifyUpdate(this);' id='hide_tour' data-ajax='false' data-role='none'/> <label for='hide_tour'>Don't show this dialog again</label></div></div>",
			onEnd: function() {}
		});
		tour.addSteps([
			{
				element: ".tour-step.tour-step-uploadimg",
				placement: "bottom",
				title: "Upload Images",
				content: "Upload the images."
			}, {
				element: ".tour-step.tour-step-1",
				placement: "bottom",
				title: "My Images",
				content: "My Images contains all the images which is uploaded by user."
			}, {
				element: ".tour-step.tour-step-2",
				placement: "bottom",
				title: "Sample Images",
				content: "Sample Images shows all the sample images for the sites which belongs to user."
			}, {
				element: ".tour-step.tour-step-3",
				placement: "top",
				title: "Shared Images",
				content: "Shared Images contains the images which is shared by others."
			}, {
				element: ".tour-step.tour-step-4",
				placement: "bottom",
				title: "Profile Images",
				content: "Profile Images contains user uploaded profile images."
			}, {
				element: ".tour-step.tour-step-5",
				placement: "top",
				title: "Exerciese Images",
				content: "Exerciese Images contains the user uploaded exercise images."
			}, {
				element: ".tour-step.tour-step-6",
				placement: "left",
				title: "Search Images",
				content: "Search the image records with filters."
			}, {
				element: ".tour-step.tour-step-7",
				placement: "top",
				title: "Preivew Images",
				content: "Click the images to preivew."
			}, {
				element: ".tour-step.tour-step-8",
				placement: "top",
				title: "Options for the images",
				content: "Click the image name to open the options modal."
			}, {
				element: ".tour-step.tour-step-9",
				placement: "bottom",
				title: "Upload Images",
				content: "Click here to upload images."
			}, {
				element: ".tour-step.tour-step-10",
				placement: "left",
				title: "Choose images for action",
				content: "Click here to choose the images and do the actions.",
				onNext: function(tour) {
					$(".checkopt-header .imgcheck-opt").trigger('click');
				}
			}, {
				element: ".tour-step.tour-step-11",
				placement: "bottom",
				title: "Select Images",
				content: "Click here to select the images and do the actions."
			}, {
				element: ".tour-step.tour-step-12",
				placement: "bottom",
				title: "Options for Selected Images",
				content: "Click here to open the option modal for selected images."
			}, {
				element: ".tour-step.tour-step-13",
				placement: "left",
				title: "Reset the Image Selection Options",
				content: "Click here to choose the images and do the actions.",
				onHide: function(tour) {
					$(".check-header .imgcheck-opt").trigger('click');
				}
			}
		]);
		tour.init(); //Initialize the tour
		tour.start(); // Start the tour
	}());
}