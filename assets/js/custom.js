$(document).ready(function(){
	$("#sortable").sortable({
	update: function (event, ui) {
			var linkorder = "";
			$("#sortable li").each(function(i) {
				if (linkorder=='')
					linkorder = $(this).attr('id');
				else
					linkorder += "," + $(this).attr('id');
			});
	
			// POST to server using $.post or $.ajax
			$.ajax({
				data: {order:linkorder},
				type: 'POST',
				url: baseurl + "/quick_links/update_links_postion",
				success:function(data){
					set_rows_bg();
				}
			});
		}
	});
	
	$("#sort tbody").sortable({
	update: function (event, ui) {	
			var profileorder = "";
			$("#sort tbody tr").each(function(i) {
				if (profileorder=='')
					profileorder = $(this).attr('id');
				else
					profileorder += "," + $(this).attr('id');
			});
			
			reset_rows("#sort tbody tr");
			// POST to server using $.post or $.ajax
			$.ajax({
				data: {order:profileorder},
				type: 'POST',
				url: baseurl + "/office_link/update_profiles_postion",
				success:function(data){
					set_rows_bg();
				}
			});
		},
	helper: function(e, tr)
  {
    var $originals = tr.children();
    var $helper = tr.clone();
    $helper.children().each(function(index)
    {
      // Set helper cell sizes to match the original sizes
      $(this).width($originals.eq(index).width());
    });
    return $helper;
  },
	}).disableSelection();
	
});

$("#sortdoc tbody").sortable({
	update: function (event, ui) {	
			var profileorder = "";
			$("#sortdoc tbody tr").each(function(i) {
				if (profileorder=='')
					profileorder = $(this).attr('id');
				else
					profileorder += "," + $(this).attr('id');
			});
			
			reset_rows("#sortdoc tbody tr");
			// POST to server using $.post or $.ajax
			$.ajax({
				data: {order:profileorder},
				type: 'POST',
				url: baseurl + "/corporate_documents/update_postion",
				success:function(data){
					reset_rows("#sortdoc tbody tr");
				}
			});
		},
	helper: function(e, tr)
  {
    var $originals = tr.children();
    var $helper = tr.clone();
    $helper.children().each(function(index)
    {
      // Set helper cell sizes to match the original sizes
      $(this).width($originals.eq(index).width());
    });
    return $helper;
  },
	}).disableSelection();

$("#sort_offlink_cat tbody").sortable({
	update: function (event, ui) {	
			var profileorder = "";
			$("#sort_offlink_cat tbody tr").each(function(i) {
				if (profileorder=='')
					profileorder = $(this).attr('id');
				else
					profileorder += "," + $(this).attr('id');
			});
			
			reset_rows("#sort_offlink_cat tbody tr");
			// POST to server using $.post or $.ajax
			$.ajax({
				data: {order:profileorder},
				type: 'POST',
				url: baseurl + "/office_links/category_update_position",
				success:function(data){
					reset_rows("#sort_offlink_cat tbody tr");
				}
			});
		},
	helper: function(e, tr)
  {
    var $originals = tr.children();
    var $helper = tr.clone();
    $helper.children().each(function(index)
    {
      // Set helper cell sizes to match the original sizes
      $(this).width($originals.eq(index).width());
    });
    return $helper;
  },
	}).disableSelection();

$("#sort_offlink tbody").sortable({
	update: function (event, ui) {	
			var profileorder = "";
			$("#sort_offlink tbody tr").each(function(i) {
				if (profileorder=='')
					profileorder = $(this).attr('id');
				else
					profileorder += "," + $(this).attr('id');
			});
			
			reset_rows("#sort_offlink tbody tr");
			// POST to server using $.post or $.ajax
			$.ajax({
				data: {order:profileorder},
				type: 'POST',
				url: baseurl + "/office_links/officelinks_update_position",
				success:function(data){
					reset_rows("#sort_offlink tbody tr");
				}
			});
		},
	helper: function(e, tr)
  {
    var $originals = tr.children();
    var $helper = tr.clone();
    $helper.children().each(function(index)
    {
      // Set helper cell sizes to match the original sizes
      $(this).width($originals.eq(index).width());
    });
    return $helper;
  },
	}).disableSelection();
	
$( ".deletelink" ).click(function() {
		var linkid = $(this).attr("id");
		
		$.ajax({
				data: {linkid:linkid},
				type: 'POST',
				url: baseurl + "/quick_links/delete_link",
				success:function(data){
					$("li#"+linkid).remove();
					set_rows_bg();
				}
			});
});
$( ".delete_document" ).click(function() {
		var docid = $(this).attr("id");		
		$.ajax({
				data: {docid:docid},
				type: 'POST',
				url: baseurl + "/corporate_documents/delete_file",
				success:function(data){
					$("#sortdoc tr#"+docid).remove();
					reset_rows("#sortdoc tbody tr");
				}
			});
});
$( ".delete_category" ).click(function() {
		var categoryId = $(this).attr("id");		
		$.ajax({
				data: {categoryid:categoryId},
				type: 'POST',
				url: baseurl + "/office_links/delete_category",
				success:function(data){
					$("#sort_offlink_cat tr#"+categoryId).remove();
					reset_rows("#sort_offlink_cat tbody tr");
				}
			});
});
$( ".delete_office_link" ).click(function() {
		var id = $(this).attr("id");		
		$.ajax({
				data: {id:id},
				type: 'POST',
				url: baseurl + "/office_links/delete_office_link",
				success:function(data){
					$("#sort_offlink tr#"+id).remove();
					reset_rows("#sort_offlink tbody tr");
				}
			});
});

$("#filter_button").click(function() {
		var filterValue = $('#filter').val();
		if(filterValue!="")
		{
			var pagetype = $('#pagetype').val();
			switch(pagetype)
			{
				case "office_links":
					var urlBase = baseurl + '/office_links/browse/1/';
				break;
				case "browse_office_links":
					var cat = $('#cat').val();
					var urlBase = baseurl + '/office_links/category/'+cat+'/browse/1/';
				break;
				default:
					var urlBase = baseurl + '/corporate_documents/browse/1/';
				break;
			}
			
			window.location = urlBase+filterValue;
		} else {
			alert("Please input search criteria");
		}

		return false;
	});

	$('#filter_form').submit(function() {
		$('#filter_button').click();
		return false;
	});
	
function reset_rows(element)
{
	var x = 0;
	$(element).each(function(i) {
				$(this).removeClass('odd'); 
				$(this).removeClass('even'); 
				if(x%2 == 0) 
				{
					$(this).addClass('odd'); 
				}
				else 
				{
					$(this).addClass('even');
				}
				x++;
			});
}
function set_rows_bg()
{
	var x = 0;
	$("#sortable li").each(function(i) {
				$(this).removeClass('add'); 
				$(this).removeClass('even'); 
				if(x%2 == 0) 
				{
					$(this).addClass('add'); 
				}
				else 
				{
					$(this).addClass('even');
				}
				x++;
	});
}