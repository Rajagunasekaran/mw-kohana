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
$('input.fltrtag-input, input.imgdata-tag, input.imgtag-input').tagsinput({
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
var saveaction = $('#saveaction').val();
var saveactionid = $('#saveactionid').val();
var editimgid = '';
if (saveactionid) {
	editimgid = saveactionid;
	$('#curr_imgid').val(editimgid);
}
if (editimgid != '') {
	if (saveaction == 'editImgData') {
		var objimgrow = $('#' + editimgid + '.imgRecord');
		var objdata = objimgrow.find('.img-itemname');
		$('#imgdata-title').val(objdata.attr('data-itemname'));
		$('#imgdata-status').val(1);
		var tags = $('#img_tags' + objdata.attr('data-itemid')).val();
		$('input.imgdata-tag').tagsinput('add', tags);
		triggerImageData('folder');
	} else if (saveaction == 'editImg') {
		var objimgrow = $('#' + editimgid + '.imgRecord');
		$currentElement = objimgrow.find('.img-itemname');
		setTimeout(triggerImgEditorModal, 1000);
	} else {

	}
}