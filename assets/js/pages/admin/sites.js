function convertToSlug(Text)
{
    return Text
        .toLowerCase()
        .replace(/[^\w ]+/g,'')
        .replace(/ +/g,'-')
        ;
}
function confirmdeletesite(id){
	console.log("Will be deleted " + id);
}

function deletesite(id)
{   
    if(confirm('Are you sure, want to delete this record?')){ 
		$.ajax({
			url: siteUrl+"sites/deletesite",
			type: 'POST',
			dataType: 'json',
			data:{'id':id},
			success:function(data){ 
				if(data.success) {	
				    $('#row-'+id).remove();
					$('.del-sucess .alert-success span').text(data.message); 
					$('.del-sucess').show();
				}
			}
		});
    }
	/*$.ajax({
		url: siteUrl+"sites/deletesite",
		type: 'POST',
		dataType: 'json',
		data:{'id':id},
		success:function(data){ 
			if(data.success) {	
				if(data.count > 0){
						
						var script = '<div id="commonmodalbox" class="modal fade" role="dialog"><div class="modal-dialog"><div class="modal-content"><div class="modal-body"><button type="button" class="close" data-dismiss="modal">&times;</button><p>Already manager assigned to this Site</p><p>Are you sure yow want to delete?</p><button type="button" class="btn btn-danger" id="yesDelete" onclick="confirmdeletesite('+id+')">Yes</button><button type="button" class="btn btn-primary" id="noDelete" data-dismiss="modal">No</button></div></div></div></div>';
						$(".commonmodalboxarea").html(script);
						
				}else{
					confirmdeletesite(id);
				}
			}
		}
	});*/
}

/*
function duplicate_site(id)
{
	$.ajax({
		url: siteUrl+"sites/duplicate_site",
		type: 'POST',
		dataType: 'json',
		data:{'id':id},
		success:function(data){ 
			if(data.success) {	
				if(data.count > 0){
						
						
						
				}else{
					confirmdeletesite(id);
				}
			}
		}
	});
}
*/
//$("#name").keyup(function(){
$(document).on('keyup', '#name', function (){
        var Text = $(this).val();        
        $("#slug").val(convertToSlug(Text));        
});
$("#name").focusout(function() {
     var Text = $(this).val();        
     $("#slug").val(convertToSlug(Text));        
  }).blur(function() {   
     var Text = $(this).val();        
     $("#slug").val(convertToSlug(Text));        
  });

if($(".siteaction").length) {
	
	$('.siteaction').select2({
		minimumResultsForSearch: -1
	});

}

$(document).on("change", ".siteaction", function() {
  	var val = $(this).val();
	var id = $(this).attr("id"); 
	$(".siteaction").select2("val", "");
	switch(val)
	{
		 case "editsite":
		 	window.location.href = siteUrl + "sites/edit/"+id;
		 break;
		  case "managesite":
		 	window.location.href = siteUrl + "index/switchsite/"+id;
		 break;
		 case "deletesite":
		 	deletesite(id);
		 break;
		 case "duplicatesite":
		 	var dis= '';
		 	if($('#sitefilter-modal').is(':visible')){
			 	dis = 'modal';
			 	$('#sitefilter-modal').modal('hide');
			}
			duplicatesite(id, dis);
		 break;
		 default:
		 break;
	}
});
function yesduplicate(dub_id){
	var sitename = $('#name').val();
	var slug = $('#slug').val();
	if(sitename != '' && slug != ''){
		$.ajax({
			beforeSend: function(){
				$("#yesDelete").text("Duplicating...");
				$('#yesDelete').attr('disabled', 'disabled');
			},
			url: siteUrl+"sites/site_duplicate",
			type: 'POST',
			dataType: 'json',
			async: false,
			data: {
			sitename: sitename,
			slug: slug,
			dub_id: dub_id
			},
			success: function(data) {
				console.log(data);
				if (data.success) {
					$('#name').val('');
					$('#slug').val('');
					$('.successsend').text(data.message);
					window.setTimeout(function(){
						$('#yesDelete').prop("disabled", false);
						$("#yesDelete").text("Duplicate now");
						$('#commonmodalbox').modal('hide');
						$('.successsend').text('');
					 }, 2000);
					if(data.newsite_id!=undefined && data.newsite_id!=''){
						window.location.href = 'edit/'+data.newsite_id;
					}else{
						window.location.reload(true);
					}
				}
			}
		});
	}else if(sitename == ''){
		$('#nameerror').text('fsdfsdfd').css({'color':'#d9534f'});
        setTimeout('$("#nameerror").hide()',2000);
		return false;
	}
	else if(slug == ''){
		$('#slugerror').text('fsdfsdfd').css({'color':'#d9534f'});
		setTimeout('$("#slugerror").hide()',2000);
		return false;
	}
}
function duplicatesite(id, dupfrm='') {
	front_siteUrl = siteUrl.replace('admin/', '');
	if(dupfrm=='modal'){
		var dismissact = 'onclick="reopenSiteFilter();"';
	}else{
		var dismissact = 'data-dismiss="modal"';
	}
	var script = '<div id="commonmodalbox" class="modal fade" role="dialog"><div class="vertical-alignment-helper"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" '+dismissact+'>&times;</button><h4 class="modal-title">Duplicate Site Confirmation</h4><p class="successsend"></p></div><div class="modal-body"><p>Please ener the Site Name</p><p><input type="text" class="form-control" value="" name="name" id="name"></p><p  id="nameerror"></p><p>Site Url</p><p><div class="input-group with-addon"><span class="input-group-addon" id="basic-addon3">'+front_siteUrl+'site/</span><input type="text" class="form-control" value="" id="slug" name="slug" aria-describedby="basic-addon3"></div></p><p  id="slugerror"></p></div><div class="modal-footer"><button type="button" class="btn btn-primary" id="yesDelete" onclick="yesduplicate('+id+')">Duplicate now</button><button type="button" class="btn btn-danger" id="noduplicate" '+dismissact+'>No</button></div></div></div></div></div>';
	$(".commonmodalboxarea").html(script);
	$("#commonmodalbox").modal('show');
}
$(document).ready(function(){
	if($('.activedeactivesite input').length){
		$('.activedeactivesite input').iCheck({
			checkboxClass: 'icheckbox_flat-blue',
			radioClass: 'iradio_flat-blue',
			increaseArea: '20%'
		});
	}
});
/*for site filter modal*/
var siteform = $('form#form-sitefilter');
siteform.find('input.searchtext').on('input.searchtext', function(e){
	e.preventDefault();
	e.stopImmediatePropagation();
	var value = $(this).val().trim();
	var t = $(this);
	t.next('span').toggle(Boolean(t.val()));
	if(value.length) {
		siteform.addClass('searching');
	} else {
		siteform.removeClass('searching');
	}
});
function siteSearchFilter(){
	var searchText = $('form#form-sitefilter input[name="autosearch"]').val();
	var sortby     = $('form#form-sitefilter select[name="fsortby"]').val();
	$.ajax({
		url: siteUrl+"sites/site_Filter",
		type: 'POST',
		dataType: 'json',
		data: {
			action: 'searchsite',
			searchText: searchText,
			sortby: sortby
		},
		success: function(data) {
			var jsonarr = data;
			emptySitesData();
			if(jsonarr!='' && jsonarr.length > 0){
				jsonarr.forEach(function(f, i) {
					if(i > 0){
						var cont = '<hr>';
					}else{
						var cont = '';
					}
					cont += '<li class="row site-item" id="'+f.id+'">';
	               cont += '<div class="col-sm-12 col-xs-12 site-row-in">';
	                  cont += '<div class="col-sm-7 col-xs-7 site-title text-left"><span class="site-name">'+f.name+'</span></div>';
	                  cont += '<div class="col-sm-5 col-xs-5">';
		                  cont += '<select id="'+f.id+'" name="siteaction" class="siteaction form-control">';
		                   	cont += '<option value="" selected="selected">Choose</option>';
		                     cont += '<option value="duplicatesite">Duplicate Site</option>';
		                  cont += '</select>';
	                  cont += '</div>';
	               cont += '</div>';
	           	cont += '</li>';
	           	var fileList = $(cont);
					fileList.appendTo($('#site-list'));
				});
				$('#site-list .siteaction').select2();
			}else{
				var cont = '<li class="row">';
               cont += '<div class="col-sm-12 col-xs-12 text-center">';
               	cont += '<div>No Sites Available</div>';
               cont += '</div>';
           	cont += '</li>';
           	var fileList = $(cont);
           	fileList.appendTo($('#site-list'));
			}
			$('.info-text').hide();
		}
	});
}
function reopenSiteFilter(){
	$('#commonmodalbox').modal('hide');
	$('#sitefilter-modal').modal();
}
function emptySitesData(){
	$('#site-list').empty();
}
function emptyFilterData(){
	$('#site-list').empty();
	$('form#form-sitefilter')[0].reset();
	$('select#fsortby').select2('val', 1);
	$('.searchclear').hide();
	$('.info-text').show();
	siteform.removeClass('searching');
}