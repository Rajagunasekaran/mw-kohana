function addRow(rowIndex) {
	rowIndex = rowIndex*1;
	var rowele = '<div class="form-group" id="row-ele-'+(rowIndex+1)+'">'+
					'<div class="col-sm-4 col-xs-4"><input type="text" value="" class="form-control"  name="title[]" required></div>'+
					'<div class="col-sm-6 col-xs-6"><input type="text" value="" class="form-control"  name="url[]" required></div>'+
					'<div class="col-sm-2 col-xs-2 icon-contnr">'+
						'<a href="javascript:void(0);" onclick="addRow('+(rowIndex+1)+');"><i class="fa fa-plus-circle"></i></a>'+
						'<a href="javascript:void(0);" onclick="removeRow('+(rowIndex+1)+');"><i class="fa fa-minus-circle"></i></a>'+
					'</div>'+
				 '</div>';
	$(rowele).insertAfter("#row-ele-"+rowIndex);
}
function removeRow(rowIndex) {
	$('#row-ele-'+rowIndex).remove();
}