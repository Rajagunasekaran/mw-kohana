function emptyRecordData(){
	if($('.activeCell').length){ $('.xrRecordData').empty().addClass('hide'); }
}
function emptyFilterData(){
	$('.data').empty();
	if( $('.reset_this').is('.shows') ) {
		$('.reset_this').removeClass('shows').addClass('hide'); 
	}else{}
}
$('div.bannermsg').fadeOut(12000);

var filtermanager = $('.gallery-div'),
fileList = filtermanager.find('.data');

var tagarry=[];
$.ajax({
	url: siteUrl_frontend+'ajax/tagnames',
	dataType : 'json',
	async: false,
	encode: true,
	cache: false
}).done(function (data) {
	var taglist=[];
	if(data){
		$.each(data.tagnames,function(i, val){
			taglist.push(val); 
		});
		tagarry = taglist;
	}
});
var tagnames = new Bloodhound({
	datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
	queryTokenizer: Bloodhound.tokenizers.whitespace,
	local: $.map(tagarry, function (tagname) {
		return {
			name: tagname
		};
	})
});
tagnames.initialize();
$('input.xru_Tags, input.fltrtag-input, input.xrtag-input').tagsinput({
	typeaheadjs: [{
		  highlight: true,
	},{
		name: 'tagnames',
		displayKey: 'name',
		valueKey: 'name',
		source: tagnames.ttAdapter()
	}],
	freeInput: true
});

$(function(){
	
	$('body').on('click','li.list_muscle',function(e) {
		var target_vals = targetHiLiteToggle(e.target);
		var target_id = target_vals[0];
		var target_html = target_vals[1];
		targetMuscle(target_html,target_id);
	});
	$('body').on('click','#xr_filter_toggle', function(e) {
		var target = $(e.target);
		var target_status = target.attr('data-class');
		console.log(target_status+"------------");
		switch(target_status){
			case 'fetch_this':
				// Hide the FILTERS container
					$('#xr_filter_toggle').addClass('hide');
					$('.gallery-contnr').hide();
				// Show the RESET FILTERS button
					$('.reset_this').removeClass('hide').addClass('shows'); 
				// Trigger click the form submit button
					e.preventDefault();
					$('#saveTabsBtn2').trigger('click');
				break;
			case 'filter_this':
				emptyFilterData();
				emptyRecordData();
				// target.attr('data-class','fetch_this').html('Fetch Records');
				if($('.data').html()=='')
					$('.gallery-contnr').show();				
				break;
		}
	});
	$('body').on('change','#musprim', function(e) {
		//var visible_btn = $('.visible');
		changeTargetOption(e.target);
		var target_id = $('#musprim').val();
		var target_html = $('#musprim :selected').html();
		var target = $('li.list_muscle.muscle_id-'+target_id);
		targetMuscle(target_html,target_id);
		targetHiLiteToggle(target);
	});
	$('body').on('click','.reset_this',function (e) {
		var target = $(e.target);
		var title = 'gallery-contnr';
		console.log(title);
		if( $('.xr_target_selected').length > 0) {
		   $('.xr_target_selected').closest('li').trigger('click');
		}
		$('.'+title+' input[type=checkbox]').prop('checked', false);
		
		activeFilterBtns(title);
		emptyFilterData();
		emptyRecordData();
		$('.searchtext').val('');	
		$('.searchclear').hide();	
		e.preventDefault();
		$('.gallery-empty-row').removeClass('hide');
		$('#xr_filter_toggle').removeClass('hide');
		$('.nothingfound').hide();
		fileList.empty().hide();
		$('.gallery-div').removeClass('active');
	})
	$('body').on('click','.filter_sub_btn',function (e) {
		var target = $(e.target);
		var title = target.closest('.bodycontent').attr("class").split(" ")[2];
		console.log(title);
		if( target.is('.select_all') ){
			$('.'+title+' input[type=checkbox]').prop('checked', true);
			activeFilterBtns(title);
		}else{
			if( title == 'exerciselib' ){
				if( $('.xr_target_selected').length > 0) {
				   $('.xr_target_selected').closest('li').trigger('click');
				}
			}else{
				$('.'+title+' input[type=checkbox]').prop('checked', false);
				activeFilterBtns(title);
			}
		}
	});
	$('.clk-btn').click(function() {
		var divClass = $(this).attr('data-div');
		$('.clk-btn').removeClass('active');
		$(this).addClass('active');
		$('.common-class').hide();
		$('.exercise-nav-index').hide();
		$('.gallery-div,.xrwrapper-div').removeClass('active');
		fileList.empty().hide();
		$('#btn_revert1').trigger('click');
		if(divClass=='xrwrappers') {
			$('.'+divClass).show();
			$('.xrwrapper-div').addClass('active');
			$('.xrwrapper-div').removeClass('hide');
			$('.search-form-2').hide();
			$('.xrwrappers-header-row').removeClass('hide');
			$('.gallery-header-row').addClass('hide');
		} else {
			$('.search-form-2').show();	
			$('.gallery-div').removeClass('hide');
			$('.search-header-row').removeClass('hide');
			$('.gallery-empty-row').removeClass('hide');
			$('.gallery-header-row').removeClass('hide');
		}
	});
	var currLoc = window.location;
	console.log(currLoc.hash);
	if(currLoc.hash=='#record-gallery' || currLoc.hash=='#create-record'){
		$('.common-class').hide();
		$('.exercise-nav-index').hide();
		$('.gallery-div,.xrwrapper-div').removeClass('active');
		fileList.empty().hide();
	}
	if(currLoc.hash=='#record-gallery'){
		$('#RecGall').addClass('active');
		$('.search-form-2').show();	
		$('.gallery-div').removeClass('hide');
		$('.search-header-row').removeClass('hide');
		$('.gallery-empty-row').removeClass('hide');
		$('.gallery-header-row').removeClass('hide');
	}
	if(currLoc.hash=='#create-record'){
		$('#CrtRec').addClass('active');
		$('.xrwrappers').show();
		$('.xrwrapper-div').addClass('active');
		$('.xrwrapper-div').removeClass('hide');
		$('.search-form-2').hide();
		$('.xrwrappers-header-row').removeClass('hide');
		$('.gallery-header-row').addClass('hide');
	}
	$('.exerciselib-back').click(function() {
		$('.search-form-2').show();
		$('.clk-btn').removeClass('active');
		$('.exercise-nav-index').show();
		$('.search-header-row').addClass('hide');
		$('.common-class').hide();
		$('.gallery-header-row, .gallery-empty-row').addClass('hide');
		$('.nothingfound').hide();
		fileList.empty().hide();
		$('.searchtext').val('');		
		$('.searchclear').hide();
		$('.xrwrapper-div, .gallery-div').removeClass('active');
		$('.xrwrapper-div, .gallery-div').addClass('hide');
		$('#xr_filter_toggle').removeClass('hide');
		$('#xr_filter_reset').removeClass('shows');
		$('#xr_filter_reset').addClass('hide');
		$('#musprim>option').prop('selected',false);
		$('.gallery-contnr input[type=checkbox]').prop('checked', false);
		$('#btn_revert1').trigger('click');
	});
	$('body').on('click','#btn_revert1',function(e){
		e.preventDefault();
		resetNewXRciseDataFromMobile();
		var clicked =$('#introclear');
		clearImg(clicked);
		$('#xrRecInsertForm').bootstrapWizard('show',0);
		$('#messageContainer').addClass('hide');
	});
	$('body').on('click', '.img_clear', function(e){
		e.preventDefault();
		clicked = $('#'+$(this).attr('data-clearid'));
		if(confirm('Are you sure, want to clear this image?')){
			clearImg(clicked);
		}
		$('#imageoption-modal').modal('hide');
	});
	$('.info-icon').attr('title', function(){
		return $(this).next('.tooltip').remove().text()
	});
	$('#intro, #details').tooltip();
});
function activeFilterBtns(title){
	if( title != 'bodycontent'){
		var visible_btn = $('.visible');
		var numChkd	= $('.'+title+' input:checked').length;
		if ( numChkd > 0 ) {
			visible_btn.addClass('activeFilter');
		}else{
			visible_btn.removeClass('activeFilter');
		}	
	}else{
		$('.activeFilter').removeClass('activeFilter');
	}
}
function closeMenuTab(tabMenuSwitch) {
	if( tabMenuSwitch.is('.show') ) {
		tabMenuSwitch.removeClass('show').addClass('hide');
	}else{
		tabMenuSwitch.addClass('hide');	
	}
	return false;
}
function toggleMenuTab(tabMenuSwitch) {
	switch( tabMenuSwitch.attr('class') )
	{
		case 'accordian_menu hide':
				openMenuTab(tabMenuSwitch);
		break;
		case 'accordian_menu show':
				closeMenuTab(tabMenuSwitch);
		break;
		default:
				closeMenuTab(tabMenuSwitch);
	}
}		
function targetHiLiteToggle(x) {
	var target = $(x);
	var visible_btn = $('.visible');
	if( target.is('.xr_target_selected') ){
		targetHiLiteOff(target,visible_btn);
		var target_html	= '';
		var target_id = 0;
	}else{
		var target_vals = targetHiLiteOn(target,visible_btn);
		var target_id = target_vals[0];
		var target_html = target_vals[1];
	}
	return [target_id,target_html];
}
function targetHiLiteOff(target,visible_btn) {
	$('li.list_muscle').removeClass('xr_target_selected');
	visible_btn.removeClass('activeFilter');
	$('input[name="target[]"]').val('');
}
function targetHiLiteOn(target,visible_btn) {
	$('li.list_muscle').removeClass('xr_target_selected');
	var target_id 	= target.attr('class').split('-').pop();
	var target_html	= target.html();
	visible_btn.addClass('activeFilter');
	target.addClass('xr_target_selected');
	$('input[name="target[]"]').val(target_id);
	return  [target_id,target_html];	
}
function targetMuscle(target_html,target_id){
	t_id = parseInt(target_id); 
	switch (t_id) {
		case 1:		//Abs
			$("#exercisemarkimage img").attr("src",siteUrl_frontend+'assets/img/anatomy/anatomy_06.jpg');
			break;
		case 2:		//abductors
			$("#exercisemarkimage img").attr("src",siteUrl_frontend+'assets/img/anatomy/anatomy_16.jpg');
			break;
		case 3:		//adductors
			$("#exercisemarkimage img").attr("src",siteUrl_frontend+'assets/img/anatomy/anatomy_07.jpg');
			break;
		case 4:		//biceps
			$("#exercisemarkimage img").attr("src",siteUrl_frontend+'assets/img/anatomy/anatomy_04.jpg');
			break;
		case 5:		//calves
			$("#exercisemarkimage img").attr("src",siteUrl_frontend+'assets/img/anatomy/anatomy_18.jpg');
			break;
		case 6:		//chest
			$("#exercisemarkimage img").attr("src",siteUrl_frontend+'assets/img/anatomy/anatomy_03.jpg');
			break;
		case 7:		//Forearm
			$("#exercisemarkimage img").attr("src",siteUrl_frontend+'assets/img/anatomy/anatomy_05.jpg');
			break;
		case 8:		//glutes
			$("#exercisemarkimage img").attr("src",siteUrl_frontend+'assets/img/anatomy/anatomy_15.jpg');
			break;
		case 9:		//hams
			$("#exercisemarkimage img").attr("src",siteUrl_frontend+'assets/img/anatomy/anatomy_17.jpg');
			break;
		case 10:	//lats
			$("#exercisemarkimage img").attr("src",siteUrl_frontend+'assets/img/anatomy/anatomy_11.jpg');
			break;
		case 11:	//low back
			$("#exercisemarkimage img").attr("src",siteUrl_frontend+'assets/img/anatomy/anatomy_14.jpg');
			break;
		case 12:	//mid back
			$("#exercisemarkimage img").attr("src",siteUrl_frontend+'assets/img/anatomy/anatomy_13.jpg');
			break;
		case 13:	//neck
			$("#exercisemarkimage img").attr("src",siteUrl_frontend+'assets/img/anatomy/anatomy_01.jpg');
			break;
		case 14:	//quads
			$("#exercisemarkimage img").attr("src",siteUrl_frontend+'assets/img/anatomy/anatomy_08.jpg');
			break;
		case 15:	//shoulders
			$("#exercisemarkimage img").attr("src",siteUrl_frontend+'assets/img/anatomy/anatomy_02.jpg');
			break;
		case 16:	//traps
			$("#exercisemarkimage img").attr("src",siteUrl_frontend+'assets/img/anatomy/anatomy_10.jpg');
			break;
		case 17:	//triceps
			$("#exercisemarkimage img").attr("src",siteUrl_frontend+'assets/img/anatomy/anatomy_12.jpg');
			break;
		case 18:	//feet
			$("#exercisemarkimage img").attr("src",siteUrl_frontend+'assets/img/anatomy/anatomy_09.jpg');
			break;
		default:
			$("#exercisemarkimage img").attr("src",siteUrl_frontend+'assets/img/anatomy/anatomy.jpg');
	}
	var target = $('#musprim option[value="' + target_id + '"]');
	changeTargetOption(target);
}
function changeTargetOption(x) {
	var target = $(x);
	thisOne = target.val();
	$('#musprim>option').prop('selected',false);	
	$('#musprim option[value="' + thisOne + '"]').prop("selected", true);
}
function isNumberKey(evt, act) {
	var keyCode = (evt.which?evt.which:(evt.keyCode?evt.keyCode:0));
	if(act && act == 'codePromo')
		if ((keyCode == 44) || (keyCode == 46)) return false;
	if(act && act == 'trackStats' && keyCode > 36 && keyCode < 41) return true;
	if ((keyCode == 8) || (keyCode == 9) || (keyCode == 46)) return true;
	if ((keyCode < 48) || (keyCode > 57) || (keyCode == 46) || (keyCode == 34) || (keyCode == 37)) return false;
	return true;
}
function strip_tags(input, allowed) {
	allowed = (((allowed || '') + '')
	.toLowerCase()
	.match(/<[a-z][a-z0-9]*>/g) || [])
	.join(''); // making sure the allowed arg is a string containing only tags in lowercase (<a><b><c>)
	var tags = /<\/?([a-z][a-z0-9]*)\b[^>]*>/gi,
	commentsAndPhpTags = /<!--[\s\S]*?-->|<\?(?:php)?[\s\S]*?\?>/gi;
	return input.replace(commentsAndPhpTags, '')
	.replace(tags, function($0, $1) {
	  return allowed.indexOf('<' + $1.toLowerCase() + '>') > -1 ? $0 : '';
	});
}
function resetNewXRciseDataFromMobile() {	
/* Clear Data from New XR inputs, etc */	
	$('#xrRecInsertForm')
		.find(':radio, :checkbox').removeAttr('checked').end()
		.find('textarea, :text, select').val('');
	$('ul#seq_list').empty();

	$('ul#seq_list').append('<li class="seq_order=1 seq-panel"><div class="row subseq-title"><div class="col-xs-2 aligncenter"><span class="seq-move fa fa-bars bluecol"></span></div><div class="col-xs-8 aligncenter"><span class="seq_title">Sequence 1</span></div><div class="col-xs-2 aligncenter"><span class="seq_remove right remove_seq seq_btn fa fa-times bluecol" data-class="remove_seq"></span></div></div><hr><div class="seq_content"><div class="seq_img form-group form-group_seq img-div"><div class="col-xs-4"><label class="control-label" for="seq_img1"><span>Sequence Image</span></label></div><div class="col-xs-5"><span class="img_thmb"><img alt="Feature Image" class="uploaded_image_thmb" id="seq-feature1" src="'+siteUrl_frontend+'assets/images/icons/icon_grey-62.png"><span class="hoverCell"></span></span><input type="hidden" value="" id="seq_img1" name="seqImg[]" class="img_selected"></div><div class="col-xs-3"></div><div class="col-xs-3 img-opt"><i class="fa fa-ellipsis-h iconsize trigger-imgopt bluecol" id="seqclear1" data-imgtagid="seq-feature1" data-hidnimgid="seq_img1" href="'+siteUrl_frontend+'assets/images/icons/icon_grey-62.png"></i></div></div><hr><div class="seq_desc form-group"><div class="col-xs-4"><label class="control-label" for="seqDesc1"><span>Sequence Description <img class="info-icon" src="'+siteUrl_frontend+'assets/images/icons/information.png"><span id="tooltip1" class="tooltip"><span>This information will scroll vertically.<br>The count of sequences per exercise is unlimited.</span></span></span></label></div><div class="col-xs-8"><textarea id="seqDesc1" placeholder="No content. Click to update." class="seq_desc[] form-control" name="seqDesc[]"></textarea></div></div></div></li>');

	$('.info-icon').attr('title', function(){
		return $(this).next('.tooltip').remove().text();
	});
	$('#intro, #details').tooltip();
	return false;
}
function clearImg(clicked) {
	/* Clear Image from Seq or Feat_img*/	
	var noImgUrl = clicked.attr('href');
	var startPoint;	
	if( clicked.closest('div').parent('div').is('.seq_img') ){
		// clear "this" sequence image in the [Details] tab
		startPoint = clicked.closest('div').parent('.seq_img');
	}else{
		// clear "this" feat_img in the [Intro] tab
		startPoint = clicked.closest('.tab-pane');
	}
	startPoint
		.find('.uploaded_image_thmb').attr('src',noImgUrl).end()
		.find('.img_selected').val('').end()
		.find('.img_preview').attr('src',noImgUrl).end();
	$('#preview_featimg').html('<i class="fa fa-file-image-o prevfeat"></i>');
	$('#xrRecInsertForm').data('formValidation').resetForm();
	return false;
}
$('.seqerror').hide();
$('.seqerror small').text('');
var icon = siteUrl_frontend+'assets/images/icons/icon_grey-62.png'; // placeholder icon for no/empty images
var info = siteUrl_frontend+'assets/images/icons/information.png'; // placeholder icon for no/empty images
$("body").on("click", ".seq_btn", function (e) {
	var arrow = $(e.target).attr("data-class");		
	if (arrow == "add_seq") {} else {
		var clicked_li = $(e.target).closest("ul#seq_list>li");
		var clicked_seq = $(clicked_li).index()+1;
	}
	switch (arrow) {
		case "add_seq":
			e.preventDefault();
			var last = $('ul#seq_list>li').length; // count <li>'s
			var order = parseInt(last)+1; // Seq_order = count<li>'s +1
			var imgflag=false;
			var descflag=false;
			$('input[name="seqImg[]"]').each(function () {
				if($(this).val()!=''){
					imgflag=true;
				}else{
					imgflag=false;
				}
			});
			$('textarea[name="seqDesc[]"]').each(function () {
				if($(this).val()!=''){
					descflag=true;
				}else{
					descflag=false;
				}
			});
			if(imgflag || descflag || !last){
				$('.seqerror small').text('');
				$('.seqerror').hide();
				newSeq(icon,order,info);
			}else{
				$('.seqerror small').text('Please fill the above sequence(s) and then try to add new sequence.');
				$('.seqerror').show();
			}
			break;
		case "remove_seq":
			deleteItem(e.target, clicked_li, clicked_seq);
			// $("ul#seq_list>li:last").find(".arrow_down").removeClass("arrow_down").addClass("arrow_down_dead").attr("data-class","arrow_down_dead");
			break;
		case "arrow_up":
			// swapPrev(e.target, clicked_li, clicked_seq);
			break;
		case "arrow_down":
			// swapNext(e.target, clicked_li, clicked_seq);
			break;
		default:
			var replaceX = clicked_li;
			var replaceY = next_seq;
			var replaceX1 = next_li;
			var replaceY1 = clicked_seq;
			change = replaceY1;
	}
});

function newSeq(icon,order,ACCESS_INDEX_1){
	var addIMGicon 	= icon;	// url for icon file
	var seqORDER 	= order;	// ORDER ID of :last <li>
	var infoIcon 	= info;	// info icon file
	var carbon = '';
	carbon+='<li class="seq_order='+seqORDER+'" seq-panel>';
		carbon+='<div class="row subseq-title">';
			carbon+='<div class="col-xs-2 aligncenter">';
				carbon+='<span class="seq-move fa fa-bars bluecol"></span>';
			carbon+='</div>';
			carbon+='<div class="col-xs-8 aligncenter">';
				carbon+='<span class="seq_title">Sequence '+seqORDER+'</span>';
			carbon+='</div>';
			carbon+='<div class="col-xs-2 aligncenter">';
				carbon+='<span class="seq_remove right remove_seq seq_btn fa fa-times bluecol" data-class="remove_seq"></span>';
			carbon+='</div>';
		carbon+='</div>';
		carbon+='<hr>';
		carbon+='<div class="seq_content">';
			carbon+='<div class="seq_img form-group form-group_seq img-div">';
				carbon+='<div class="col-xs-4">';
					carbon+='<label class="control-label" for="seq_img'+seqORDER+'">';
					carbon+='<span>Sequence Image</span>';
					carbon+='</label>';
				carbon+='</div>';
				carbon+='<div class="col-xs-5">';
					carbon+='<span class="img_thmb">';
						carbon+='<img id="seq-feature'+seqORDER+'" src="'+addIMGicon+'" class="uploaded_image_thmb" alt="Feature Image">';
						carbon+='<span class="hoverCell"></span>';
					carbon+='</span>';
					carbon+='<input type="hidden" class="img_selected" name="seqImg[]" id="seq_img'+seqORDER+'" value="">';
				carbon+='</div>';
				carbon+='<div class="col-xs-3"></div>';
				carbon+='<div class="col-xs-3 img-opt">';
					carbon+='<i class="fa fa-ellipsis-h iconsize trigger-imgopt bluecol" id="seqclear'+seqORDER+'" data-imgtagid="seq-feature'+seqORDER+'" data-hidnimgid="seq_img'+seqORDER+'" href="'+siteUrl_frontend+'assets/images/icons/icon_grey-62.png"></i>';
				carbon+='</div>';
			carbon+='</div> <!-- /END .seq_img -->';
			// SEQ_DESC
			carbon+='<div class="seq_desc form-group">';
				carbon+='<div class="col-xs-4">';
					carbon+='<label class="control-label" for="seqDesc'+seqORDER+'">';
						carbon+='<span>Sequence Description</span>';
					carbon+='</label>';
				carbon+='</div>';
				carbon+='<div class="col-xs-8">';
					carbon+='<textarea name="seqDesc[]" id="seqDesc'+seqORDER+'" class="seq_desc[] form-control" placeholder="No content. Click to update."></textarea>';
				carbon+='</div>';
			carbon+='</div> <!-- /END seq_desc -->';
		carbon+='</div> <!-- /END seq_content -->';
	carbon+='</li>';	
	var count_li = $("ul#seq_list>li").length;// Append the <li> 
	// Append DUPLICATE li:last as NEW li:last
	if($('ul#seq_list>small.seqli-error').length){
		$('ul#seq_list').empty();
	}
	$(carbon).appendTo('#seq_list');
	var focuselem = 'seqDesc' + seqORDER;
	document.getElementById(focuselem).focus();
}
function deleteItem(x, y, z) {
	var eTarget = x;
	var clicked_li = y;
	var clicked_seq = z;
	var count_li = $("ul#seq_list>li").length;
	if (confirm('Are you sure? NOTE: This content will not be deleted from the database until you click "Save" button to update the entire record.')) {
		if (clicked_li.is('li:last-child')) {
			clicked_li.remove();
		} else {
			if (clicked_li.is('li:first-child')) {
				clicked_li.addClass("next_li").nextAll().addClass("next_li");
			} else {
				clicked_li.prev().nextAll().addClass("next_li");
			}
			$("ul#seq_list>li:last").removeClass('next_li');
			$(".next_li").each(function () {

				var eTarget1 = $(this).find(".seq_btn");
				var clicked_li1 = eTarget1.closest("li");
				var clicked_int1 = clicked_li1.removeClass('next_li');
				var clicked_seq1 = clicked_int1.attr('class').split("=").pop();
				
				swapNext(eTarget1, clicked_li1, clicked_seq1);
			});
			// Then remove last <li>
			$("ul#seq_list>li:last").remove();
		}
	}
	return false;
}
function swapPrev(x, y, z) {
	var eTarget = x;
	var clicked_li = y;
	var clicked_seq = z;
	var prev_li = $(eTarget).closest("li").prev();
	var prev_seq = $(prev_li).attr("class").split("=").pop();
	var replaceX = clicked_li;
	var replaceY = prev_seq;
	var replaceX1 = prev_li;
	var replaceY1 = clicked_seq;
	var clicked_textarea = $(replaceX).find('textarea').val();// clicked_textarea		
	$(replaceX).find('textarea').html(clicked_textarea);// update clicked_textarea		
	var swap_textarea = $(replaceX1).find('textarea').val();// swap_textarea		
	$(replaceX1).find('textarea').html(swap_textarea);// update clicked_textarea		
	var clicked_content = $(replaceX).find(".seq_content").html();
	var swap_content = $(replaceX1).find(".seq_content").html();
	$(replaceX).find(".seq_content").html(swap_content);
	$(replaceX1).find(".seq_content").html(clicked_content);
}
function swapNext(x, y, z) {
	var eTarget = x;
	var clicked_li = y;
	var clicked_seq = z;
	var next_li = $(eTarget).closest("li").next();
	var next_seq = $(next_li).attr("class").split("=").pop();
	var replaceX = clicked_li;
	var replaceY = next_seq;
	var replaceX1 = next_li;
	var replaceY1 = clicked_seq;
	var clicked_textarea = $(replaceX).find('textarea').val();// clicked_textarea
	$(replaceX).find('textarea').html(clicked_textarea);// update clicked_textarea
	var swap_textarea = $(replaceX1).find('textarea').val();// swap_textarea
	$(replaceX1).find('textarea').html(swap_textarea);// update clicked_textarea
	var clicked_content = $(replaceX).find(".seq_content").html();
	var swap_content = $(replaceX1).find(".seq_content").html();
	$(replaceX).find(".seq_content").html(swap_content);
	$(replaceX1).find(".seq_content").html(clicked_content);
}
$(document).on('click','a.edit-img',function(e){	
	e.preventDefault();
	e.stopImmediatePropagation();
	if($('#parentFolderId').length && $('#subFolderId').length){
		$('#parentFolderId').val('');
		$('#subFolderId').val('');
		triggerAjaxImgLibrary();
	}	
	$('#imageoption-modal').modal('hide');
	$('#popupimglibrary-modal').modal({keyboard:"false", backdrop:"static"});
});
$('#xrRecInsertForm').submit(function (ev) {
	$('#xrRecInsertForm').formValidation('revalidateField', 'xru_featImage');
	if(ValidateMediaContentFields($('#details.tab-pane'))){
		return true;
	}
	return false;
});
$(document).on('click', '.search-filter', function(ev){
	ev.preventDefault();
	var divClass = $('.clk-btn.active').attr('data-div');
	$('.common-class').hide();
	$('.collapse').slideUp();
	$('.'+divClass).show();
	fileList.empty().hide();
	$('.nothingfound').hide();
	$('#btn_revert1').trigger('click');
	if($(this).attr('data-class')=='filter_this'){
		emptyFilterData();
		emptyRecordData();
		if($('.data').html()=='')
			$('.gallery-contnr').show();
		$('#xr_filter_toggle').removeClass('hide');
	}
	if(divClass=='gallery-contnr') {
		$('.gallery-header-row').removeClass('hide');	
		if($('.gallery-div').hasClass('active')){
			$('.gallery-div').removeClass('active');
			$('.'+divClass).hide();
			$('.gallery-empty-row').removeClass('hide');
		}
		else{
			$('.gallery-div').addClass('active');
			$('.gallery-div').removeClass('hide');
			$('.gallery-empty-row').addClass('hide');
			$('.search-form-2').show();
			$('.xrwrappers-header-row').addClass('hide');
		}		
	}
});

/* Disable/Enable Search Input */
function enableSearch(){
	// enable search input
	$('.searchtext').removeAttr('disabled','disabled');
	return false;
}
function disableSearch(){
	// disable search input
	$('.searchtext').attr('disabled','disabled');
	return false;
}
$(document).on('show.bs.modal', '.modal', function () { 
  	$(document.body).addClass('modal-open');
  	disableSearch();
})
.on('hidden.bs.modal', '.modal', function () { 
  	$(document.body).removeClass('modal-open');
   $('.modal:visible').length && $(document.body).addClass('modal-open');
  	enableSearch();
});

function getExerciseLibraryByajax(){
	if($('#search-exercise-library').length){
		$('#search-exercise-library').autocomplete({
			source : function(requete, reponse){ // les deux arguments représentent les données nécessaires au plugin
				$.ajax({
					type: 'POST',
					url: siteUrl_frontend+'ajax/exerciseRecordGallery',
					data : {
						action : 'exerciselibrary',
						searchval : $('#search-exercise-library').val(),
						maxRows : 5
					},
					encode: true,
					cache: false,
					success : function(donnee){
						if(donnee){
							var donee = [JSON.parse(donnee)];
							var filearr = [];
							$.each(donee, function(i, file) {
								filearr = file.items;
							});
							reponse($.map(filearr, function(items){
								return {
									xr_id: items.id,
									name: items.name,
									featimg: items.featimg,
									previmg: items.previmg,
									related: items.related
								}
							}));
						}
					}
				});
			},
			select: function( event, ui ) {
				event.preventDefault();
				window.location="/exercise/exerciselibrary/"+ui.item.xr_id+"/#create-record";
			}
		}).data( "ui-autocomplete" )._renderItem = function( ul, item ) {	
			return $( "<li>" ).append( "<div class='col-xl-12'>" + item.name + "</div>" ).appendTo( ul );
		};
	}
}

/* Search Input (from FILTERS page) */
filtermanager.find('input.searchtext').on('input.searchtext', function(e){
	e.preventDefault();
	e.stopImmediatePropagation();
	var value = $(this).val().trim();
	var t = $(this);
	t.next('span').toggle(Boolean(t.val()));
	if(value.length) {
		/* Empty any "Selected" record data */
		emptyRecordData();
		/* Show Searching "Progress" */
		filtermanager.addClass('searching');
		/* Trigger Submitting the Filter Form */
		e.preventDefault();
		$('#saveTabsBtn2').trigger('click');
		$('.gallery-contnr').hide();
	} else {
		filtermanager.removeClass('searching');
		emptyFilterData();
		emptyRecordData();
		$('#saveTabsBtn2').trigger('click');
	}
}).on('keyup', function(e){
	e.preventDefault();
	$('.fetch_this').trigger('click');
	/* Clicking 'ESC' button */
	var searchinput = $(this);
	//this will void fake keypresses
	if(e.charCode == 0 && e.keyCode == 0) {
		return false;
	}else if ( e.keyCode == 27 /* escape button */ ) {
		if(searchinput.length>0){
			e.preventDefault()
			$('#saveTabsBtn2').trigger('click');
		}else{}
	}
});

/* Search Input (from FILTERS page) */
filtermanager.find('select#sortby').on('change', function(e){
	var $opt = $(this).find("option:selected");
	var $span = $('<span>').addClass('tester').text($opt.text());

	$span.css({
		'font-family': $opt.css('font-family'),
		'font-style': $opt.css('font-style'),
		'font-weight': $opt.css('font-weight'),
		'font-size': $opt.css('font-size')
	});

	$('body').append($span);
	$(this).width($span.width() + 30);
	$span.remove();

	$('#saveTabsBtn2').trigger('click');
});
$('#filterDataForm').submit(function(e) {
	e.preventDefault();
	e.stopImmediatePropagation();
	/* PROCESS FILTER FORM DATA */		
	// Filter : Search Input Text
	var searchText = $('form input[name="autosearch"]').val();	
	var sortby     = $('form select[name="sortby"]').val();
	
	// Filter : Target Muscle
	var target_muscle = $('form select[name="musprim"]').val();	
	// Filter : Exercise Type(s)
	var exercise_type = new Array();
	$('form input[name="exercisetypes[]"]').each(function () {
		if(this.checked){ exercise_type.push($(this).val()); }else{}
	});	
	// Filter : Equipment Item(s)
	var equipment = new Array();
	$('form input[name="exerciseequips[]"]').each(function () {
		if(this.checked){ equipment.push($(this).val()); }else{}
	});	
	// Filter : Training Level
	var train_level = new Array();
	$('form input[name="exerciselevels[]"]').each(function () {
		if(this.checked){ train_level.push($(this).val()); }else{}
	});	
	// Filter : Sport
	var sport_type = new Array();
	$('form input[name="exercisesports[]"]').each(function () {
		if(this.checked){ sport_type.push($(this).val()); }else{}
	});	
	// Filter : Force Movement
	var force = new Array();
	$('form input[name="exerciseactions[]"]').each(function () {
		if(this.checked){ force.push($(this).val()); }else{}
	});
	/*FETCH RECORDS*/										
	var dataToSend = {
		searchval	:searchText,
		sortby		:sortby,
		musprim		:target_muscle,
		type		:exercise_type,
		equip		:equipment,
		level		:train_level,
		sport		:sport_type,
		force		:force
		/*tags:'"+tags+"'*/
	};
	// console.log(dataToSend)
	$.ajax({
		type: 'POST',
		url: siteUrl_frontend+'ajax/exerciseRecordGallery',
		data: dataToSend,
		encode: true,
		cache: false
	}).done(function (data) {
		var response = [JSON.parse(data)];
		/* Keyboard Search Input */
		filtermanager.find('input.searchtext').on('input.searchtext', function(e){
			e.preventDefault();
			e.stopImmediatePropagation();
			var value = $(this).val().trim();
			if(value.length) {
				emptyRecordData();/* Empty any "Selected" record data */
				filtermanager.addClass('searching');/* Show Searching "Progress" */
				e.preventDefault();/* Trigger Submitting the Filter Form */
				$('#saveTabsBtn2').trigger('click');
			} else {
				filtermanager.removeClass('searching');
				emptyFilterData();
				emptyRecordData();
				e.preventDefault();
				$('#saveTabsBtn2').trigger('click');
			}
		}).on('keyup', function(e){
			/* Clicking 'ESC' button */
			var searchinput = $(this);
			//this will void fake keypresses
			if(e.charCode == 0 && e.keyCode == 0) {
				return false;
			}else if ( e.keyCode == 27 /* escape button */ ) {
				if(searchinput.length>0){
					e.preventDefault();
					$('#saveTabsBtn2').trigger('click');
				}else{}
			}
		});
		render(filterRecords(data));
		function filterRecords(recordIDs) {
			/* TEST for DATA in ARRAY */
			var	demo = response; // var response = [data] returned from search
			var	flag = 0;
			for(var j=0;j<demo.length;j++){
				flag = 1; // flag if RESPONSE contains data
				demo = demo[j].items;
				break;
			}
			demo = flag ? demo : []; // if no content, demo=0, otherwise demo=[array]
			return demo;
		}
		function render(data) {
			/* RENDERING DATA */
			/* TEST FLAGS for DATA ARRAYS */
			var filteredFiles = [];
			if(Array.isArray(data)) {
				data.forEach(function (d) {
					filteredFiles.push(d);
				});
			}
			/* Empty the old result and make the new one */
			fileList.empty().hide();
			if(!filteredFiles.length) {
				filtermanager.find('.nothingfound').show();
			} else {
				filtermanager.find('.nothingfound').hide();
				filteredFiles.forEach(function(f) {
					var xr_id = escapeHTML(f.id);
					var name = escapeHTML(f.name);
					if(f.featimg.trim() == ''){
					  thumb = '<div class="thumb_img click-previmg col-sm-3" style="text-align: center;display: block;width: 25%;"><i class="fa fa-file-image-o datacol" style="font-size:50px;"></i></div>';
					}else{
					  thumb = '<div class="thumb_img click-previmg col-xs-3" style="background-image: url(' + f.featimg + ');" data-url="'+f.previmg+'"></div>';
					}
					var rec = '<li class="xrRecord" id="' + xr_id + '" data-related="'+f.related+'">';
						rec += '<div class="xrRecordDataFrame xrrec col-xs-12 col-xs-12">';
							rec += thumb;
							rec += '<div class="col-xs-7 xrRecord-title click-prev"><span class="name">'+ name +'</span></div>';
							rec += '<div class="col-xs-2 xrRecord-opt"><i class="fa fa-ellipsis-h iconsize"></i></div>';
							var tagsarr=[];
							f.tags.forEach(function(t) {
								tagsarr.push(t.tag_title) ;
							});
							rec += '<input type="hidden" id="XrRecTags'+xr_id+'" value="'+tagsarr.join()+'">';
						rec += '</div>';
					rec += '</li>';
					var file = $(rec);
					file.appendTo(fileList);
				});
			}
			// Show the generated elements
			fileList.show();
			$('.gallery-empty-row').addClass('hide');
		}
		$('.gallery-div').on('click', 'li.xrRecord i.fa-ellipsis-h', function(e){
			e.preventDefault();
			e.stopImmediatePropagation();
			var clicked = $(e.target);
			var xrID = clicked.closest('li').attr("id");
			var relatcount = clicked.closest('li').attr("data-related");
			emptyRecordData();
			if(relatcount > 0){
				$('#btn_viewrelated').attr('onclick', "getRelatedRecords('"+xrID+"')");
				$('#btn_viewrelated').prop('disabled', false).removeClass('datacol');
			}else{
				$('#btn_viewrelated').prop('disabled', true).addClass('datacol');
			}
			$('#xrciselibact-modal').modal();
			xrUnitRecord(clicked, xrID);
			/* Get unit Record Data */
			function xrUnitRecord(clicked, xrID){
				$.ajax({
					url:siteUrl_frontend+'ajax/exerciseUnitData',
					type: 'POST',
					data: 'xr_id='+xrID,
					success: function(data) {
						var unitrec=JSON.parse(data);
						var items = [];
						$.each(unitrec, function(key,val) {
							val.forEach(function(f) {
								var	xr_id = f.id;
								var	xr_title = f.title;
								var	featimg = f.featimg;
								var	status = f.status;
								var	access = f.access;
								var user = f.user;
								if(f.xrtype!=''&&f.xrtype!=null){var xrtype = f.xrtype;}else{var xrtype = '-';}
								if(f.muscle!=''&&f.muscle!=null){var muscle = f.muscle;}else{var muscle = '-';}
								if(f.equip!=''&&f.equip!=null){var equip = f.equip;}else{var equip = '-';}
								if(f.mech!=''&&f.mech!=null){var mech = f.mech;}else{var mech = '-';}
								if(f.level!=''&&f.level!=null){var level = f.level;}else{var level = '-';}
								if(f.force!=''&&f.force!=null){var force = f.force;}else{var force = '-';}
								var preview = f.path;													   
								var unitRec = '<div class="xr_data activeCell" id=' + xr_id + '">';								
									unitRec+= '<div class="row"><div class="xrunittop border-work">';
										unitRec+= '<div class="col-sm-5 col-xs-5 thumb_img" style="background-image: url(' + featimg +');"></div>';
										unitRec+= '<div class="col-sm-7 col-xs-7"><div class="xr_unit xrtitle">' +xr_title + '</div></div>';
										unitRec+= '';
									unitRec+= '</div></div>';								
									unitRec+= '<div class="xrunitdata row">';
										unitRec+= '<div id="feat_img" class="hide">' + featimg +'</div>';
										unitRec+= '<div id="preview_img" class="hide">' + preview +'</div>';
										unitRec+= '<div id="unit_id" class="hide">' + xr_id + '</div>';
										unitRec+= '<div id="status" class="hide">' + status +'</div>';
										unitRec+= '<div id="access" class="hide">' + access +'</div>';
										unitRec+= '<div id="user" class="hide">' + user +'</div>';
										unitRec+= '<div class="clearboth"></div>';
										unitRec+= '<div class="border-work"><div class="xrdata_label col-xs-5">Type of Activity:</div><div class="xrdata_value col-sm-7">' + xrtype + '</div></div>';
										unitRec+= '<div class="border-work"><div class="xrdata_label col-xs-5">Primary Muscle:</div><div class="xrdata_value col-sm-7">' + muscle + '</div></div>';
										unitRec+= '<div class="border-work"><div class="xrdata_label col-xs-5">Equipment:</div><div class="xrdata_value col-sm-7">' + equip + '</div></div>';
										unitRec+= '<div class="border-work"><div class="xrdata_label col-xs-5">Mechanics:</div><div class="xrdata_value col-sm-7">' + mech + '</div></div>';
										unitRec+= '<div class="border-work"><div class="xrdata_label col-xs-5">Level:</div><div class="xrdata_value col-sm-7">' + level + '</div></div>';
										unitRec+= '<div class="border-work"><div class="xrdata_label col-xs-5">Force:</div><div class="xrdata_value col-sm-7">' + force + '</div></div>';
									unitRec+= '</div>';
								unitRec+= '</div>';							
								file 	= $(unitRec);								
								file.appendTo('.xrRecordData');
								$('#xrid').val(xr_id);
								$('.xrRecordData').removeClass('hide');
								$('#unitdata-title').text('Exercise Record - '+xr_title);
							});
						});
					} // END success
				}); // END ajax
			}// END function xrUnitRecord
			$('#xrunit_id').val(xrID);
			var unittags=$('#XrRecTags'+xrID).val();
			$('input.xrtag-input').tagsinput('removeAll');
			$('input.xrtag-input').tagsinput('refresh');
			$('input.xrtag-input').tagsinput('add', unittags);
		});		
		$('.gallery-div').on('click', '.click-prev', function(e){
			e.preventDefault();
			e.stopImmediatePropagation();
			var clicked = $(e.target);
			var xrID = clicked.closest('li').attr("id");
			emptyRecordData();
			getXRcisepreviewOfDay(xrID, 0, $(this).text());
		});
		function getXRcisepreviewOfDay(exerciseId, wkoutId, title){
			$('#xrciseprev-modal').html();
			$.ajax({
				url : siteUrl_frontend+'search/getmodelTemplate',
				data : {
					action : 'previewExerciseOfDay',
					method :  'preview',
					id : exerciseId,
					foldid : wkoutId
				},
				success : function(content){
					$('#xrciseprev-modal').html(content);
					$('.xrtitle').text('Preview - '+title);
					$('#xrciseprev-modal').modal();
				}
			});
		}
		/* ESCAPE HTML from String */
		function escapeHTML(text) {			
			return text.replace(/\&/g,'&amp;').replace(/\</g,'&lt;').replace(/\>/g,'&gt;');
		}		
		/* Btye Size Converter */			
		function bytesToSize(bytes) {
			var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
			if (bytes == 0) return '0 Bytes';
			var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
			return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
		}
	});	// END .ajax .done function	
}); // END FUNCTION submit()

// searchbox clear
$(".searchclear").hide($(this).prev('input').val());
$(".searchclear").click(function () {
	$(this).prev('input').val('').focus();
	$(this).hide();
	fileList.empty().hide();
	$('.nothingfound').hide();
	$('.gallery-empty-row').removeClass('hide');
	$('#xr_filter_toggle').removeClass('hide');
	$('#xr_filter_reset').removeClass('shows');
	$('#xr_filter_reset').addClass('hide');
});
/*trigger the preview img modal*/
function triggerImgPreviewModal(elemid){
	var imgurl = $('#'+elemid).attr('src');
	if(imgurl!=undefined){
		$('#preview_featimg').html('<img alt="Feature Image" class="Preview_image" id="preview-featimg" src="'+imgurl+'"/>');
	}else{
		$('#preview_featimg').html('<i class="fa fa-file-image-o prevfeat"></i>');
	}
	$('#imageoption-modal').modal('hide');
	$('#previewimg-modal').modal();
}
/*trigger unit creation form*/
function triggerXrUnitCreate(){
	$("#xrunitdata-modal").modal('hide');
	$("#xrcisesaveopt-modal").modal('hide');
	$("#xrciselibact-modal").modal('hide');
	$("#xrunitinsert-modal").modal();
	$('.reset_this').trigger('click');
	$('.xrwrapper-div').addClass('active');
	$('.xrwrapper-div').removeClass('hide');
	$('.xrwrappers').show();
	$('.gallery-div').removeClass('active');
	$('.gallery-div').addClass('hide');
	$('.search-form-2').hide();
	$('.xrwrappers-header-row').removeClass('hide');
	$('.gallery-header-row').addClass('hide');
	$('.gallery-empty-row').addClass('hide');
	$('#btn_revert1').trigger('click');
	$('#messageContainer').addClass('hide');
	window.location.reload();//="admin/exercise/create";
	return false;
}
function triggerXrUnitEdit() {
	var xruid=$('#xrid').val();
	//window.location="admin/exercise/create/"+xruid+"";
	window.location.reload();
	return false;
}
function triggerXrUnitDataModal() {
	$("#xrciselibact-modal").modal('hide');
	$("#xrunitdata-modal").modal();
}
/*wizard form validation*/
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
		}
		else if (parseVimeoUrl(url)!=false) {
			console.log(parseVimeoUrl(url))
			return true;
		}
		else if (parseYoutubeUrl(url)!=false) {
			console.log(parseYoutubeUrl(url))
			return true;
		}
		else{
			return false;
		}
	}
};
WizardFormValidation();
function WizardFormValidation(){
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
			},xru_type: {
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
			xru_access: {
				validators: {
					notEmpty: {
						message: 'The access is required'
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
			xru_descbr: {
				validators: {
					notEmpty: {
						message: 'The description cannot be empty'
					}
				}
			},
			xru_musprim: {
				validators: {
					notEmpty: {
						message: 'The main muscles is required'
					}
				}
			},
			xru_equip: {
				validators: {
					notEmpty: {
						message: 'The equipment is required'
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
	})
	.on('success.form.fv', function(e) {
		e.preventDefault();// Prevent form submission
		// Some instances you can use are
		var $form = $(e.target), // The form instance
			fv    = $(e.target).data('formValidation'); // FormValidation instance
		fv.defaultSubmit();
	})
	.on('success.field.fv', function(e, data) {
		$('#messageContainer').addClass('hide');
	})
	.on('err.field.fv', function(e, data) {
		$('#messageContainer').removeClass('hide');
	})
	.bootstrapWizard({
		tabClass: 'nav nav-pills',
		onTabClick: function(tab, navigation, index) {
			return validateTab(index);
		},
		onNext: function(tab, navigation, index) {
			var numTabs    = $('#xrRecInsertForm').find('.tab-pane').length,
				isValidTab = validateTab(index - 1);
			if (!isValidTab) {
				return false;
			}
			return true;
		},
		onPrevious: function(tab, navigation, index) {
			return validateTab(index + 1);
		}
	});
}
function validateTab(index) {
	var fv = $('#xrRecInsertForm').data('formValidation'), // FormValidation instance
		$tab = $('#xrRecInsertForm').find('.tab-pane').eq(index);// The current tab
	// Validate the container
	fv.validateContainer($tab);
	var isValidStep = fv.isValidContainer($tab);
	var isValidMedia = ValidateMediaContent($tab);
	var isValidMediaField = ValidateMediaContentFields($tab);
	if ((isValidStep === false || isValidStep === null) || isValidMedia === false  || isValidMediaField === false) {
		return false; // Do not jump to the target tab
	}
	return true;
}
function ValidateMediaContentFields($tab){
	var seqli = $($tab).find('ul#seq_list>li').length;
	var imgflag=false;
	var descflag=false;
	$($tab).find('input[name="seqImg[]"]').each(function () {
		if($(this).val()!=''){
			imgflag=true;
		}else{
			imgflag=false;
		}
	});
	$($tab).find('textarea[name="seqDesc[]"]').each(function () {
		if($(this).val()!=''){
			descflag=true;
		}else{
			descflag=false;
		}
	});
	if(imgflag || descflag || !seqli){
		$('.seqerror small').text('');
		$('.seqerror').hide();
	}else{
		$('.seqerror small').text('Please enter at least any one field in the above sequence.');
		$('.seqerror').show();
		return false;
	}
	return true;
}
function ValidateMediaContent($tab){
	var seqli = $('ul#seq_list>li').length;
	if(!seqli){
		$('ul#seq_list').empty().append('<small class="help-block seqli-error"><i class="fa fa-info-circle"></i>Please add at least one sequence...</small>');
		return false;
	}
	return true;
}
$(document).on('click', '.click-previmg', function(){
	var imgurl = $(this).attr('data-url');
	if(imgurl!=undefined){
		$('#preview_featimg').html('<img alt="Feature Image" class="Preview_image" id="preview-featimg" src="/'+imgurl+'"/>');
	}else{
		$('#preview_featimg').html('<i class="fa fa-file-image-o prevfeat"></i>');
	}
	$('#previewimg-modal').modal();
});
$(document).on('click', '#btn_viewrelated', function(){	
	$('#xrciselibact-modal').modal('hide');
	$('#xrunitdata-modal').modal('hide');
});
$(document).on('click', '.trigger-imgopt', function(){
	$('#btn_imgpreview').attr('onclick', "triggerImgPreviewModal('"+$(this).attr('data-imgtagid')+"');");
	$('#btn_imgclear').attr('data-clearid', $(this).attr('id'));
	$('#imageoption-modal').modal();
	if($("#imginsert-btn").length){
		$('#imginsert-btn').attr('data-imgtagid', $(this).attr('data-imgtagid'));
		$('#imginsert-btn').attr('data-hidnimgid', $(this).attr('data-hidnimgid'));
	}
});
function triggerXrFormReset(){
	$('#btn_revert1').trigger('click');
	$('#xrcisesaveopt-modal').modal('hide');
	$('#messageContainer').addClass('hide');
	window.location.reload();
}
function triggerXrFormSave(){
	$('#btn_saveclose').trigger('click');
	$('#xrcisesaveopt-modal').modal('hide');
}
function triggerXrFormSaveEdit(){
	$('#btn_savecontn').trigger('click');
	$('#xrcisesaveopt-modal').modal('hide');
}
function triggerduplicateRecord(){
	if(confirm('Are you sure, want to duplicate this record?')){
		return true;
	}
	return false;
}
function triggerdeleteRecord(){
	if(confirm('Are you sure, want to delete this record?')){
		return true;
	}
	return false;
}
function triggerXrTagModal(){
	$('#xrciselibact-modal').modal('hide');
	$('#xrunitdata-modal').modal('hide');
	$('#xrtagging-modal').modal();
}
function getRelatedRecords(xrid){
	if($('#myOptionsModalExerciseRecord_more').length)
		modalName = 'myOptionsModalExerciseRecord_more';
	else
		modalName = 'myOptionsModalExerciseRecord';
	$('#'+modalName).html();
	$.ajax({
		url : siteUrl_frontend+"search/getmodelTemplate",
		data : {
			action 	: 'relatedRecords',
			method 	:  'relatedRecords', // on donne la chaÃ®ne de caractÃ¨re tapÃ©e dans le champ de recherche
			id 		: xrid,
			modelType : modalName
		},
		success : function(content){
			$('#'+modalName).html(content);
			$('#'+modalName).modal();
		}
	});
}
function getRelatedRecordsMore(xrid,start,lim){
  $.ajax({
		url : siteUrl_frontend+"search/getRelatedXrRecordsMore",
		data : {
			id		: xrid,
			start	: start,
			lim	: lim
		},
		success : function(content){
			 var JSONArray = $.parseJSON( content );
			 var response = '';
			if(JSONArray.length>0) {
			 for(var i=0; i<JSONArray.length; i++){
				//alert(JSONArray[i].unit_id)
				response += '<div class="row"><div class="mobpadding"><div class="border full">';
				response += '<div class="col-xs-3 ">';
				if(JSONArray[i].img){				
					response += '<img width="50px;" id="exerciselibimg" class="img-thumbnail" style="cursor:pointer;';
					response += '" src=\'../../../'+JSONArray[i].img+'\'';
					response += '/>';
				}else{
					response += '<i style="font-size:50px;" class="fa fa-file-image-o datacol"></i>';
				}
				response += '</div>';
				response += '<div class="col-xs-6 "><b>'+JSONArray[i].title+'</b></div>';
				response += '<div class="col-xs-5"><a title="" href="javascript:void(0);"><i class="fa fa-ellipsis-h iconsize"></i></a></div>';
				response += '</div>';
				response += '</div></div>';
				
			 }
			 start = start+lim;
			 response += '<div id="view_more" class="row"><div class="mobpadding"><div class="border full">';
			 response += '<div class="col-xs-12"><center><a data-role="none" data-ajax="false" class="pointers" onclick="getRelatedRecordsMore('+xrid+','+start+','+lim+')"><i class="fa fa-chevron-down"></i> Show More Records</a></center></div></div></div></div>';	
			 $("#view_more").remove();
			 $("#relatedexc").append(response).css('overflow-y', 'scroll');
		  }else{
			 $("#view_more").remove();
		  }		  
		}
	});
}

function closeModelwindow(myModel){
	if(typeof(myModel) == 'undefined' || myModel.trim() == '' )
		myModel = 'myModal';
	$('#'+myModel).modal('hide');
	if(myModel == 'exerciselib-model'){
		if($('#exercise_unit').val() == '0' || $('#exercise_unit').val() == ''){
			$('#exerciselib').bootstrapSwitch('state', false);
		}
	}else
		$('#'+myModel).html('');
}



var panelList = $('#seq_list');
panelList.sortable({
	tolerance: 'pointer',
  	revert: 'invalid',
  	placeholder: 'seq-panel dropspace',
	forceHelperSize: true,
  	handle: '.seq-move', 
  	update: function() {
    	$('.seq-panel', panelList).each(function(index, elem) {
	      var $listItem = $(elem),
	         newIndex = $listItem.index();
    	});
  	}
});