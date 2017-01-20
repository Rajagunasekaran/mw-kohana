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
$('input.xru_Tags').tagsinput({
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
$('.info-icon').attr('title', function() {
	return $(this).next('.tooltip').text();
});
$('#xrRecInsertForm .tab-content').tooltip();
$('.seqerror').hide();
$('.seqerror small').text('');
WizardFormValidation(); /*call form validation*/
if ($('#xrRecInsertForm #requestflag').length && $('#xrRecInsertForm #requestflag').val() == 'dashboard') {
	$('#btn_trgrsave .change-text').text('Save & Close');
	$('#btn_addrecord').parent('div').removeClass('hide');
}
$('#exercisecreate-modal').on('show.bs.modal', function() {
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
		helper: function(e, ui) {
			ui.children().each(function() {
				$(this).width($(this).width());
			});
			return ui;
		},
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
});