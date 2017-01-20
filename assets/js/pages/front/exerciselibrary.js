$('div.bannermsg').fadeOut(12000);
/*for get the current site url and site name*/
var currLoc = window.location;
var urlpathname = currLoc.pathname;
var urlpath = urlpathname;
urlpath.indexOf(1);
urlpath.toLowerCase();
urlpath = urlpath.split('/')[1];
if (urlpath != 'exercise') {
	var site_name = '/' + urlpath + '/';
} else {
	var site_name = '/';
}
/* END for get the current site url and site name*/
var filtermanager = $('.gallery-div'),
	fileList = filtermanager.find('ul.data');
var recordcount = 0;
var fltr_limitcnt = 0;
var folderhit = 0;

/*for tag*/
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
$('input.xrtag-input, input.mdl_fltrtag-input').tagsinput({
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
$('input.xrtag-input').tagsinput('input').blur(function() {
	$('input.xrtag-input').tagsinput('add', $(this).val());
	$(this).val('');
});
if($('input.mdl_fltrtag-input').length){
	$('input.mdl_fltrtag-input').tagsinput('input').blur(function() {
		$('input.mdl_fltrtag-input').tagsinput('add', $(this).val());
		$(this).val('');
	});
}
$(function() {
	$('body').on('click', 'li.list_muscle', function(e) {
		var target_vals = targetHiLiteToggle(e.target);
		var target_id = target_vals[0];
		var target_html = target_vals[1];
		targetMuscle(target_html, target_id);
	});
	$('body').on('change', '#musprim', function(e) {
		changeTargetOption(e.target);
		var target_id = $('#musprim').val();
		var target_html = $('#musprim :selected').html();
		var target = $('li.list_muscle.muscle_id-' + target_id);
		targetMuscle(target_html, target_id);
		targetHiLiteToggle(target);
	});
	$('body').on('click', '#xr_filter_toggle', function(e) {
		var target = $(e.target);
		var target_status = target.attr('data-class');
		console.log(target_status);
		switch (target_status) {
			case 'fetch_this':
				filtermanager.removeClass('active');
				$('#xr_filter_toggle, .gallery-empty-row, .gallery-contnr, .sorting-div').hide();
				$('#xr_filter_reset').show();
				$('.show-searchfilter').removeClass('filter-open').addClass('filter-close');
				$('.show-searchfilter .filter-i').removeClass('fa-caret-up').addClass('fa-caret-down');
				if ($('form#filterDataForm input.searchtext').val() == '') {
					$('select[name=sortby]').val('date_created');
				}
				e.preventDefault();
				$('#btn_filtersubmit').trigger('click');
				setTimeout(function() {
					$('form#filterDataForm input.searchtext').focus();
				}, 300);
				break;
			default:
				break;
		}
	});
	$('body').on('click', '#xr_filter_reset', function(e) {
		e.preventDefault();
		var target = $(e.target);
		var title = 'gallery-contnr';
		if ($('.xr_target_selected').length > 0) {
			$('.xr_target_selected').closest('li').trigger('click');
		}
		$('.' + title + ' input[type=checkbox]').prop('checked', false);
		$('input.exercisetags').tagsinput('removeAll');
		$('input.exercisetags').tagsinput('refresh');
		$('select[name=sortby]').val('asc');
		$('.' + title).addClass('active');
		activeFilterBtns(title);
		fileList.empty().hide();
		$('#xr_filter_reset, .searchclear, .nothingfound').hide();
		$('#xr_filter_toggle, .gallery-empty-row').show();
		$('form#filterDataForm input.searchtext').val('');
		setTimeout(function() {
			$('form#filterDataForm input.searchtext').focus();
		}, 300);
	});
	$('body').on('click', '.filter_sub_btn', function(e) {
		var target = $(e.target);
		var title = target.closest('.bodycontent').attr("class").split(" ")[1];
		console.log(title);
		if (target.is('.select_all')) {
			$('.' + title + ' input[type=checkbox]').prop('checked', true);
			activeFilterBtns(title);
		} else {
			if (title == 'exerciselib') {
				if ($('.xr_target_selected').length > 0) {
					$('.xr_target_selected').closest('li').trigger('click');
				}
			} else {
				$('.' + title + ' input[type=checkbox]').prop('checked', false);
				activeFilterBtns(title);
			}
		}
	});
});

$(document).on('click', '.show-searchfilter', function(ev) {
	ev.preventDefault();
	$('.gallery-contnr, .sorting-div').show();
	$('.show-searchfilter').removeClass('filter-close').addClass('filter-open');
	$('.show-searchfilter .filter-i').removeClass('fa-caret-down').addClass('fa-caret-up');
	$('.nothingfound').hide();
	if ($(this).attr('data-class') == 'filter_this') {
		$('#xr_filter_reset').hide();
		$('#xr_filter_toggle').show();
		fileList.hide();
	}
	if (filtermanager.hasClass('active')) {
		filtermanager.removeClass('active');
		$('.gallery-contnr, .sorting-div').hide();
		$('.show-searchfilter').removeClass('filter-open').addClass('filter-close');
		$('.show-searchfilter .filter-i').removeClass('fa-caret-up').addClass('fa-caret-down');
		if ($('ul.data li').length > 0) {
			$('#xr_filter_toggle, .gallery-empty-row').hide();
			$('#xr_filter_reset').show();
			fileList.show();
		} else {
			$('.gallery-empty-row').show();
		}
	} else {
		filtermanager.addClass('active').show();
		$('.gallery-empty-row').hide();
	}
	setTimeout(function() {
		$('form#filterDataForm input.searchtext').focus();
	}, 300);
	setDynamicHeight(); // call from script_ft.js
});

function resetAllFilters() {
	if ($('.xr_target_selected').length > 0) {
		$('.xr_target_selected').closest('li').trigger('click');
	}
	$('.gallery-contnr input[type=checkbox]').prop('checked', false);
	$('input.exercisetags').tagsinput('removeAll');
	$('input.exercisetags').tagsinput('refresh');
	$('select[name=sortby]').val('asc');
	$('.searchclear, .nothingfound').hide();
	$('form#filterDataForm input.searchtext').val('');
	setTimeout(function() {
		$('form#filterDataForm input.searchtext').focus();
	}, 300);
}

function closeFiltersContainer() {
	filtermanager.removeClass('active');
	$('.gallery-contnr, .sorting-div').hide();
	$('.show-searchfilter').removeClass('filter-open').addClass('filter-close');
	$('.show-searchfilter .filter-i').removeClass('fa-caret-up').addClass('fa-caret-down');
	if ($('ul.data li').length > 0) {
		$('#xr_filter_toggle, .gallery-empty-row').hide();
		$('#xr_filter_reset').show();
		fileList.show();
	} else {
		$('#xr_filter_toggle, .gallery-empty-row').show();
		$('#xr_filter_reset').hide();
		fileList.hide();
	}
	setTimeout(function() {
		$('form#filterDataForm input.searchtext').focus();
	}, 300);
}

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
			$("#exercisemarkimage img").attr("src", siteUrl + 'assets/img/anatomy/anatomy_06.jpg');
			break;
		case 2: //abductors
			$("#exercisemarkimage img").attr("src", siteUrl + 'assets/img/anatomy/anatomy_16.jpg');
			break;
		case 3: //adductors
			$("#exercisemarkimage img").attr("src", siteUrl + 'assets/img/anatomy/anatomy_07.jpg');
			break;
		case 4: //biceps
			$("#exercisemarkimage img").attr("src", siteUrl + 'assets/img/anatomy/anatomy_04.jpg');
			break;
		case 5: //calves
			$("#exercisemarkimage img").attr("src", siteUrl + 'assets/img/anatomy/anatomy_18.jpg');
			break;
		case 6: //chest
			$("#exercisemarkimage img").attr("src", siteUrl + 'assets/img/anatomy/anatomy_03.jpg');
			break;
		case 7: //Forearm
			$("#exercisemarkimage img").attr("src", siteUrl + 'assets/img/anatomy/anatomy_05.jpg');
			break;
		case 8: //glutes
			$("#exercisemarkimage img").attr("src", siteUrl + 'assets/img/anatomy/anatomy_15.jpg');
			break;
		case 9: //hams
			$("#exercisemarkimage img").attr("src", siteUrl + 'assets/img/anatomy/anatomy_17.jpg');
			break;
		case 10: //lats
			$("#exercisemarkimage img").attr("src", siteUrl + 'assets/img/anatomy/anatomy_11.jpg');
			break;
		case 11: //low back
			$("#exercisemarkimage img").attr("src", siteUrl + 'assets/img/anatomy/anatomy_14.jpg');
			break;
		case 12: //mid back
			$("#exercisemarkimage img").attr("src", siteUrl + 'assets/img/anatomy/anatomy_13.jpg');
			break;
		case 13: //neck
			$("#exercisemarkimage img").attr("src", siteUrl + 'assets/img/anatomy/anatomy_01.jpg');
			break;
		case 14: //quads
			$("#exercisemarkimage img").attr("src", siteUrl + 'assets/img/anatomy/anatomy_08.jpg');
			break;
		case 15: //shoulders
			$("#exercisemarkimage img").attr("src", siteUrl + 'assets/img/anatomy/anatomy_02.jpg');
			break;
		case 16: //traps
			$("#exercisemarkimage img").attr("src", siteUrl + 'assets/img/anatomy/anatomy_10.jpg');
			break;
		case 17: //triceps
			$("#exercisemarkimage img").attr("src", siteUrl + 'assets/img/anatomy/anatomy_12.jpg');
			break;
		case 18: //feet
			$("#exercisemarkimage img").attr("src", siteUrl + 'assets/img/anatomy/anatomy_09.jpg');
			break;
		default:
			$("#exercisemarkimage img").attr("src", siteUrl + 'assets/img/anatomy/anatomy.jpg');
	}
	var target = $('#musprim option[value="' + target_id + '"]');
	changeTargetOption(target);
}

function changeTargetOption(x) {
	var target = $(x);
	thisOne = target.val();
	$('#musprim>option').prop('selected', false);
	$('#musprim option[value="' + thisOne + '"]').prop("selected", true);
}

/* Disable/Enable Search Input */
function enableSearch() {
	$('.searchtext').removeAttr('disabled', 'disabled');
	return false;
}

function disableSearch() {
	$('.searchtext').attr('disabled', 'disabled');
	return false;
}
$(document).on('shown.bs.modal', '.modal', function() {
	$(document.body).addClass('modal-open');
	disableSearch();
}).on('hidden.bs.modal', '.modal', function() {
	$(document.body).removeClass('modal-open');
	$('.modal:visible').length && $(document.body).addClass('modal-open');
	enableSearch();
});
/* Search Input (from FILTERS page) */
filtermanager.find('form#filterDataForm input.searchtext').on('form#filterDataForm input.searchtext', function(e) {
	e.preventDefault();
	var value = $(this).val().trim();
	var t = $(this);
	t.next('span').toggle(Boolean(t.val()));
	if (value.length) {
		filtermanager.addClass('searching'); /* Show Searching "Progress" */
		$('#btn_filtersubmit').trigger('click');
	} else {
		filtermanager.removeClass('searching');
		fileList.empty();
		$('#btn_filtersubmit').trigger('click');
	}
	filtermanager.removeClass('active');
	$('#xr_filter_toggle, .gallery-contnr, .sorting-div').hide();
	$('#xr_filter_reset').show();
	$('.show-searchfilter').removeClass('filter-open').addClass('filter-close');
	$('.show-searchfilter .filter-i').removeClass('fa-caret-up').addClass('fa-caret-down');
}).on('keyup', function(e) {
	e.preventDefault();
	$('select[name=sortby]').val('asc');
	var searchinput = $(this);
	if (e.charCode == 0 && e.keyCode == 0) {
		return false;
	} else if (e.keyCode == 27) { /* escape button */
		if (searchinput.length > 0) {
			$('#btn_filtersubmit').trigger('click');
		} else {}
	}
});
/* Search Input (from FILTERS page) */
filtermanager.find('select#sortby').on('change', function(e) {
	filtermanager.removeClass('active');
	$('#xr_filter_toggle, .gallery-contnr, .sorting-div').hide();
	$('#xr_filter_reset').show();
	$('.show-searchfilter').removeClass('filter-open').addClass('filter-close');
	$('.show-searchfilter .filter-i').removeClass('fa-caret-up').addClass('fa-caret-down');
	$('#btn_filtersubmit').trigger('click');
	setTimeout(function() {
		$('form#filterDataForm input.searchtext').focus();
	}, 300);
});
/*submit for fetch the exercise records*/
$('#filterDataForm').submit(function(e) {
	e.preventDefault();
	e.stopImmediatePropagation();
	fltr_limitcnt = 0;
	processFilterData('init'); // process form filter data
});

function processFilterData(opt) {
	// Filter : Search Input Text
	var searchText = $('form#filterDataForm input.searchtext').val();
	var folderid = $('form#filterDataForm input[name="XrFolderId"]').val();
	// Filter : Target Muscle
	var target_muscle = $('form select[name="musprim"]').val();
	// Filter : Exercise Type(s)
	var exercise_type = new Array();
	$('form input[name="exercisetypes[]"]').each(function() {
		if (this.checked) {
			exercise_type.push($(this).val());
		} else {}
	});
	// Filter : Equipment Item(s)
	var equipment = new Array();
	$('form input[name="exerciseequips[]"]').each(function() {
		if (this.checked) {
			equipment.push($(this).val());
		} else {}
	});
	// Filter : Training Level
	var train_level = new Array();
	$('form input[name="exerciselevels[]"]').each(function() {
		if (this.checked) {
			train_level.push($(this).val());
		} else {}
	});
	// Filter : Sport
	var sport_type = new Array();
	$('form input[name="exercisesports[]"]').each(function() {
		if (this.checked) {
			sport_type.push($(this).val());
		} else {}
	});
	// Filter : Force Movement
	var force = new Array();
	$('form input[name="exerciseactions[]"]').each(function() {
		if (this.checked) {
			force.push($(this).val());
		} else {}
	});
	// Filter : Associated Tags
	var tagsitem = $('form input[name="exercisetags"]').tagsinput('items');
	var tags = new Array();
	$.each(tagsitem, function(i, t) {
		tags.push(t.id);
	});
	if ((searchText != '' || searchText == '') && (target_muscle != '' || exercise_type != '' || equipment != '' || train_level != '' || sport_type != '' || force != '' || tags != '')) {
		$('select[name=sortby]').val('date_created');
	}
	var sortby = $('form select[name="sortby"]').val();
	/*FETCH RECORDS*/
	var dataToSend = {
		searchval: searchText,
		sortby: sortby,
		folderid: folderid,
		musprim: target_muscle,
		type: exercise_type,
		equip: equipment,
		level: train_level,
		sport: sport_type,
		force: force,
		tags: tags,
		slimit: fltr_limitcnt,
		elimit: 10
	};
	// console.log(dataToSend)
	$.ajax({
		type: 'POST',
		url: siteUrl + 'ajax/exerciseRecordGallery',
		data: dataToSend,
		encode: true,
		cache: false,
		success: function(data) {
			var response = [JSON.parse(data)];
			if (opt == 'init') {
				fileList.empty().hide();
				sendajaxFlag = true;
				if (getBrowserZoomLevel() < 100) {
					AutoShowMore('onclick');
				}
			}
			if (response) {
				if (render(filterRecords(response), searchText)) {
					if (recordcount == 10 && opt == 'showmore') {
						sendajaxFlag = true;
					}
					fileList.show(); // Show the generated elements
					if (opt != 'showmore') {
						fileList.scrollTop(0);
					}
				}
			}
		}
	});
	return true;
}

function filterRecords(records) {
	var demo = records; // var response = [data] returned from search
	var flag = 0;
	for (var j = 0; j < demo.length; j++) {
		flag = 1; // flag if response contains data
		demo = demo[j].items;
		recordcount = demo[0].itemcount;
		folderhit = demo[0].folder;
		break;
	}
	demo = flag ? demo : []; // if no content, demo=0, otherwise demo=[array]
	return demo;
}

function render(data, searchtext) {
	/* test for data arrays */
	var filteredFiles = [];
	if (Array.isArray(data)) {
		data.forEach(function(d, i) {
			if (i != 0) {
				filteredFiles.push(d);
			}
		});
	}
	$('.gallery-empty-row').hide();
	/* Empty the old result and make the new one */
	if (!filteredFiles.length) {
		if (fileList.find('li.xrRecord').length) {
			filtermanager.find('.nothingfound').hide();
		} else {
			filtermanager.find('.nothingfound').show();
		}
		return false;
	} else {
		filtermanager.find('.nothingfound').hide();
		filteredFiles.forEach(function(f) {
			var xr_id = escapeHTML(f.id);
			var name = escapeHTML(f.name);
			if (folderhit != '' && folderhit != undefined) {
				var foldername = folderhit;
			} else {
				var foldername = 0;
			}
			var rec = '<li class="xrRecord" id="' + xr_id + '" data-related="' + f.related + '" data-folder="' + f.folderid + '">';
			rec += '<div class="xrRecordDataFrame col-xs-12 col-sm-12">';
			var attribute = 'data-xrname="' + name + '"';
			var shared_info = '';
			if (f.folderid == 3) {
				shared_info = '<div class="item-info"><span class="item-info-bold">Shared By: </span><span class="activecolcor"><strong> ' + f.user_name + ' </strong></span><span> (' + f.shared_by + ')</span></div>';
			}
			rec += '<a href="javascript:void(0);" class="col-xs-10 col-sm-10 xrFrame-left" onclick="getXrImageAndRecordOpt(' + xr_id + ', this, ' + '\'hideopt\'' + ')" ' + attribute + ' data-ajax="false" data-role="none">';
			if (f.featimg.trim() == '') {
				rec += '<div class="col-xs-3 col-sm-3 thumb_img noimg-icon"><i class="fa fa-file-image-o datacol" style="font-size:50px;"></i></div>';
			} else {
				rec += '<div class="col-xs-3 col-sm-3 thumb_img" style="background-image: url(' + f.featimg + ');"></div>';
			}
			rec += '<div class="col-xs-9 col-sm-9"><span class="xrRecord-title break-xr-name">' + highlightText(name, searchtext) + '</span><div class="item-info">' + f.default+'</div>' + shared_info + '</div></a>';
			rec += '<a href="javascript:void(0);" class="col-xs-2 col-sm-2 text-center xrFrame-right" onclick="getXrImageAndRecordOpt(' + xr_id + ', this, ' + '\'showopt\'' + ');" ' + attribute + ' data-ajax="false" data-role="none"><div class="col-xs-12 col-sm-12"><i class="fa fa-ellipsis-h iconsize"></i></div></a>';
			var tagsarr = [];
			f.tags.forEach(function(t) {
				tagsarr.push(t.tag_title);
			});
			rec += '<input type="hidden" id="XrRecTags' + xr_id + '" value="' + tagsarr.join() + '">';
			rec += '</div>';
			rec += '</li>';
			var file = $(rec);
			file.appendTo(fileList);
		});
	}
	setDynamicHeight(); // call from script_ft.js
	return true;
}
// for highlighting the search text in list
function preg_quote(str) {
	return (str + '').replace(/([\\\.\+\*\?\[\^\]\$\(\)\{\}\=\!\<\>\|\:])/g, "\\$1");
}

function highlightText(data, search) {
	if (search != '') {
		return data.replace(new RegExp("(" + preg_quote(search) + ")", 'gi'), "<span class='highlight'>$1</span>");
	}
	return data;
}

function getXRcisepreviewOfDay(exerciseId, wkoutId) {
	$('#xrciseprev-modal').html('');
	$.ajax({
		url: siteUrl + 'search/getmodelTemplate',
		data: {
			action: 'previewExerciseOfDay',
			method: 'preview',
			id: exerciseId,
			foldid: wkoutId
		},
		success: function(content) {
			$('#xrciseprev-modal').html(content);
			$('#xrciseprev-modal').modal();
		}
	});
}
/* ESCAPE HTML from String */
function escapeHTML(text) {
	return text.replace(/\&/g, '&amp;').replace(/\</g, '&lt;').replace(/\>/g, '&gt;');
}
// searchbox clear
$(".searchclear").click(function() {
	$(this).hide().prev('input').val('').focus();
	fileList.empty().hide();
	$('#xr_filter_reset, .nothingfound, .gallery-contnr, .sorting-div').hide();
	$('#xr_filter_toggle, .gallery-empty-row').show();
	$('.show-searchfilter').removeClass('filter-open').addClass('filter-close');
	$('.show-searchfilter .filter-i').removeClass('fa-caret-up').addClass('fa-caret-down');
});

//preview images slides
function getXrImageAndRecordOpt(xrid, elem, showopt) {
	$('#exercise_taginsert #xrunit_id').val(xrid);
	var unittags = $('#XrRecTags' + xrid).val();
	$('input.xrtag-input').tagsinput('removeAll');
	$('input.xrtag-input').tagsinput('refresh');
	$('input.xrtag-input').tagsinput('add', unittags);
	var xrname = $(elem).attr('data-xrname');
	var xrfolderid = $(elem).closest('li.xrRecord').attr('data-folder');
	modalName = 'xrciselibact-modal';
	$('#' + modalName).html('');
	$.ajax({
		url: siteUrl + "search/getmodelTemplate",
		data: {
			action: 'relatedRecords',
			method: 'previewimage',
			id: xrid,
			modelType: modalName,
			addOptions: 'addForPage',
			foldid: xrfolderid,
			showOptions: showopt
		},
		success: function(content) {
			$('#' + modalName).html(content);
			$('#' + modalName).modal();
			$('span.xrrecordTitle').html(xrname);
		}
	});
}

function showmoreXrImageAndRecordOpt(xrId) {
	$('#xrciselibact-modal .more-option').toggle();
}

function getRateForXrModalFromUser(xrId) {
	$('#rateexrcise-modal').html();
	$.ajax({
		url: siteUrl + "search/getmodelTemplate/",
		data: {
			action: 'relatedRecords',
			method: 'xrrate',
			id: xrId,
			foldid: 0,
			modelType: "rateexrcise-modal"
		},
		success: function(content) {
			$('#rateexrcise-modal').html(content);
			$('#rateexrcise-modal form#xrrate').prepend('<input type="hidden" name="submitfrom" value="' + $('#XrFolderId').val() + '">');
			$('#rateexrcise-modal').modal();
		}
	});
}

function triggerShareExerciseModal(xruid, xrtitle) {
	$.ajax({
		url: siteUrl + "ajax/getAjaxExerciseShareHtml",
		type: 'post',
		data: {
			action: 'shareExercise',
			xrid: xruid,
			title: xrtitle,
			actFrom: 'page',
			reqFrom: 'front'
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
						url: siteUrl + 'search/getajax/',
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
						url: siteUrl + 'search/getajax/',
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
				$('#sharexrcise-modal form#form_shareExercise').prepend('<input type="hidden" name="submitfrom" value="' + $('#XrFolderId').val() + '">');
				$('#sharexrcise-modal').modal();
			}
		}
	});
}

function checkValidXrShareInfo() {
	if ($('input#xr_user_names').val() != '') {
		return true;
	} else $('div.share-errormsg').html('Please choose atleast one Recipient').removeClass('hide');
	return false;
}

function checkValidAdminXrShareInfo() {
	if ($('input#xr_user_names').val() != '' && $('input#xr_site_names').val() != '') {
		return true;
	} else {
		if ($('input#xr_site_names').val() == '') $('div.share-errormsg').html('Please choose atleast one Site').removeClass('hide');
		else $('div.share-errormsg').html('Please choose atleast one Recipient').removeClass('hide')
	};
	return false;
}

function triggerduplicateRecord() {
	if (confirm('Are you sure, want to duplicate this record?')) {
		return true;
	}
	return false;
}

function triggerdeleteRecord() {
	if (confirm('Are you sure, want to delete this record?')) {
		return true;
	}
	return false;
}

function triggerXrTagModal() {
	$('#xrtagging-modal form#exercise_taginsert').find('input[name=submitfrom]').remove();
	$('#xrtagging-modal form#exercise_taginsert').prepend('<input type="hidden" name="submitfrom" value="' + $('#XrFolderId').val() + '">');
	$('#xrtagging-modal').modal();
}
var sendajaxFlag = true;
$(document).ready(function() {
	if (fileList.length) {
		fileList.bind('scroll', function(ev) {
			$('html, body').animate({
				scrollTop: fileList.position().top
			}, 'slow');
			var scrollTop = Math.round($(this).scrollTop());
			var scrollHeight = $(this)[0].scrollHeight;
			// console.log(scrollTop + $(this).innerHeight() + '===' + scrollHeight);
			if (sendajaxFlag) {
				if (scrollTop + $(this).innerHeight() == scrollHeight || scrollTop + $(this).innerHeight() == scrollHeight - 1 || scrollTop + $(this).innerHeight() == scrollHeight + 1) {
					sendajaxFlag = false;
					setTimeout(function() {
						ev.preventDefault();
						if (ev.handled !== true) {
							ev.handled = true;
							$('form#filterDataForm input.searchtext').blur();
							fltr_limitcnt = fltr_limitcnt + 10;
							processFilterData('showmore'); // process form filter data
						}
					}, 200);
				}
			}
		});
	}
	if ($('#XrFolderId').val() == '3' || $('#XrFolderId').val() == '0') {
		setTimeout(function() {
			$('#xr_filter_toggle').trigger('click');
		}, 100);
	} else {
		setTimeout(function() {
			$('form#filterDataForm input.searchtext').focus();
		}, 300);
	}
});

function AutoShowMore(act) {
	if (($('#XrFolderId').val() == '3' || $('#XrFolderId').val() == '0' || $('#XrFolderId').val() == '2') && act == 'onclick') {
		var x = 1,
			loopcnt = getAjaxSendCount();
		while (x <= loopcnt) {
			sendajaxFlag = false;
			setTimeout(function() {
				if (fileList.is(':visible') && fileList.find('li.xrRecord').length && getBrowserZoomLevel() < 100) {
					$('form#filterDataForm input.searchtext').blur();
					fltr_limitcnt = fltr_limitcnt + 10;
					processFilterData('showmore'); // process form filter data
				}
			}, 400);
			x = x + 1;
		}
	}
	return;
}

$(window).resize(function(ev) {
	if (fileList.length && fileList.is(':visible') && fileList.find('li.xrRecord').length && !fileList.hasVScrollBar()) {
		sendajaxFlag = false;
		setTimeout(function() {
			ev.preventDefault();
			if (ev.handled !== true) {
				ev.handled = true;
				$('form#filterDataForm input.searchtext').blur();
				fltr_limitcnt = fltr_limitcnt + 10;
				processFilterData('showmore'); // process form filter data
			}
		}, 200);
	}
});
$(document).on("keypress", ":input:not(textarea)", function(ev) {
	var code = ev.keyCode || ev.which;
	if (code === 13) {
		ev.preventDefault();
		return false;
	}
});

function getXrSeqImgPreview(xrid, seqId) {
	modalName = 'myOptionsModalExerciseRecord_more';
	$('#' + modalName).html('');
	$.ajax({
		url: siteUrl + "search/getmodelTemplate",
		data: {
			action: 'relatedRecords',
			method: 'previewimageSeq', // on donne la chaîne de caractère tapée dans le champ de recherche
			id: xrid,
			foldid: seqId,
			modelType: modalName,
		},
		success: function(content) {
			$('#' + modalName).html(content);
			$('#' + modalName).modal();
		}
	});
}
$(document).on('click keyup', '.bootstrap-tagsinput', function() {
	if ($('.gallery-contnr').is(':visible')) {
		if ($('.tt-menu.tt-open').is(':visible')) {
			$('.gallery-contnr').animate({
				scrollTop: $('.gallery-contnr')[0].scrollHeight
			}, 1000);
		}
	}
});

/*bootstrap tour*/
if (allowTour) {
	(function() {
		var tour = new Tour({
			storage: false,
			template: "<div class='popover tour'><div class='arrow'></div><h3 class='popover-title'></h3><div class='popover-content'></div><nav class='popover-navigation'><div class='btn-group'><button class='btn btn-sm btn-default' data-role='prev' data-ajax='false'>Prev</button><button class='btn btn-sm btn-default' data-role='next' data-ajax='false'>Next</button></div><button class='btn btn-sm btn-default btn-end' data-role='end' data-ajax='false'>End tour</button></nav><div class='popover-content-custom'><input type='checkbox' name='hide_tour' value='1' onclick='notifyUpdate(this);' id='hide_tour' data-ajax='false' data-role='none'/> <label for='hide_tour'>Don't show this dialog again</label></div></div>",
			onEnd: function() {}
		});
		tour.addSteps([
			{
				element: ".tour-step.tour-step-eleven",
				placement: "bottom",
				title: "Create a New Exercise",
				content: "Create a New Exercise."
			}, {
				element: ".tour-step.tour-step-myxr",
				placement: "bottom",
				title: "My Exercise",
				content: "My Exercise contains all the exercise which is created by user."
			}, {
				element: ".tour-step.tour-step-samplexr",
				placement: "bottom",
				title: "Sample Exercise",
				content: "Sample Exercise shows all the sample exercise for the sites which belongs to user."
			}, {
				element: ".tour-step.tour-step-sharexr",
				placement: "top",
				title: "Shared Exercise",
				content: "Shared Exercise contains the exercise which is shared by others."
			}, {
				element: ".tour-step.tour-step-twelve",
				placement: "top",
				title: "Create a New Exercise",
				content: "Here to fill the content for new exercise record."
			}, {
				element: ".tour-step.tour-step-thirteen",
				placement: "bottom",
				title: "Feature Image",
				content: "Click to edit the feature image."
			}, {
				element: ".tour-step.tour-step-fourteen",
				placement: "bottom",
				title: "Move to next tab",
				content: "Click next to move next tab and fill the fields."
			}, {
				element: ".tour-step.tour-step-fifteen",
				placement: "left",
				title: "Clear the Exercise Creation form",
				content: "Click here to clear the Exercise Creation form."
			}, {
				element: ".tour-step.tour-step-sixteen",
				placement: "left",
				title: "Save options for the exercise record",
				content: "Save options for the exercise record."
			}, {
				element: ".tour-step.tour-step-seventeen",
				placement: "bottom",
				title: "Search Exercise Records",
				content: "Search the exercise records here."
			}, {
				element: ".tour-step.tour-step-eighteen",
				placement: "left",
				title: "View Filters",
				content: "View filters."
			}, {
				element: ".tour-step.tour-step-nineteen",
				placement: "bottom",
				title: "Sort the Exercise Records",
				content: "Exercise record sorting."
			},
		]);
		tour.init(); //Initialize the tour
		tour.start(); // Start the tour
	}());
}