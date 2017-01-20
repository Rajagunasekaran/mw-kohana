	<style type="text/css">

	</style>
    <!--- Top nav && left nav--->
	<?php echo $topnav.$leftnav; ?>
	<!--- Top nav && left nav --->
      <!-- Content Wrapper. Contains page content -->
      <div id="page-wrapper">
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                           <?php $session = Session::instance();
							if($session->get('current_site_name')!=''){echo '"'.$session->get('current_site_name').'"';}?> Manage Language
                        </h1>
                        <ol class="breadcrumb">
                            <li>
                                <i class="fa fa-dashboard"></i>  <a href="<?php echo URL::base().'admin/index'; ?>">Dashboard</a>
                            </li>
                            <li class="active">
                                <i class="fa fa-edit"></i> Manage Language
                            </li>
                        </ol>
                    </div>
                </div>
				
                <!-- /.row -->
				<div class="row" id="mes_suc" style="display:none;">
					<div class="col-lg-12">
						<div class="alert alert-success">
						  <i class="fa fa-check"></i><span></span>
						</div>
					</div>
				</div>
                <div>
					<div class="panel panel-default">
                      <div class="panel-heading">Import Language Items</div>
                      <div class="panel-body">
                      		 <input type="hidden" name="language_id" id="language_id" value="<?php echo $language_id?>"> 
                      		 <input type="hidden" name="siteid" id="siteid" value="<?php echo $siteid?>">
							 <form enctype="multipart/form-data" action="<?php echo URL::base().'admin/language/browse'; ?>" method="POST">
                                <div class="col-lg-12 languagerow">
                                    <div class="col-lg-6 innersetting">
                                        <div class="col-lg-6 lbl1">Selected language</div>
                                        <div class="col-lg-6 lbl2">
                                             <?php echo $current_langue['name'];?>
                                        </div>
                                    </div>
								</div>
								<div class="col-lg-12 languagerow">
                                	<p class="importhelp">Now you can import your own language descriptions for site labels. Please <a href="<?php echo URL::base().'admin/language/download_template' ?>">click here</a> to download the template file for language items. You can import your language items below</p>
                                </div>
								<div class="col-lg-12 languagerow imgupload">
                                    <div class="col-lg-6 innersetting">
                                        <div class="col-lg-6 lbl1">File </div>
                                        <div class="col-lg-6 lbl1">
											 <input  type="file" name="uploadedfile" accept=".xlsx,.xls"  required="" aria-required="true">
                                        </div>
                                    </div>
								</div>
								<div class="col-lg-12 innersetting">
									<div class="col-lg-2 lan-svae-button">
										<button class="btn btn-primary" id="timezonesubmit" name="submit" type="submit">Save</button>
									</div>
								</div>	
								<?php 
									if($session->get('flash_success') !=''){
								?>
								<div class="col-lg-12 upload-s-msg alert alert-success">
									<?php echo $session->get('flash_success');
											$session->set('flash_success', '');?>
								</div>
								<?php }else if($session->get('flash_error') !='') { ?>
									<div class="col-lg-12 upload-s-msg  alert alert-danger">
										<?php echo $session->get('flash_error'); 
										$session->set('flash_error', '');?>
									</div>
								<?php } 
								?>
                            </form>			
                      </div>
                    </div>
                   
				</div>
				<div>
					<div class="col-lg-6"> </div>
					<div class="col-lg-6">
						<a class="btn btn-default" href="javascript:void(0);" onclick='createNewLangVal()' style="float:right;">Add Language Record</a>
					</div>	
				</div>	
				
				<div style="margin-top:10px;">
                    <?php if(isset($languagedata) && count($languagedata)>0) { ?>
                            <div class="table-responsive col-lg-12">
                                <table class="table table-bordered table-hover table-striped dataTable" id='wkouttable'>
                                    <thead>
                                        <tr>
                                            <th class="chkbox-header"><input type="checkbox"  name="row_index[]" id="select-all" /></th>		
                                            <th>Key</th>		
                                            <th>Value</th>	
                                            <th>Edit</th>	
                                        </tr>
                                    </thead>
                                    <tbody id="table-content-contnr">
										<?php  //print_r($languagedata); die;
											foreach($languagedata as $key => $value) {
										?>
											<tr id="row-<?php echo $value['id'];?>">
												
												<td class="tabl-chkbox checkselect"><input type="checkbox" name="row_index[]" class="chkbox-item exe_select" value="<?php echo $value['id'];?>" /></td>
												<td class='tabl-chkbox checkselect'>
													<div><?php echo $value['language_key'];?></div>
												</td>
												<td class='tabl-chkbox checkselect'>
													<div><?php echo $value['value'];?></div>
												</td>
												<td class='tabl-chkbox checkselect'>
													<div>
														<select name="languageaction" id="<?php echo $value['id'];?>" class="languageaction selectAction">
															<option value="">Choose Action</option>
															<option value="edit">Edit</option>
															<option value="delete">Delete</option>
														</select>
													</div>
												</td>
													
											</tr> <?php } ?>
                                    </tbody>
                                </table>
                            </div>
					<?php } else { echo "No Records Found..."; }?>
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->
    <!-- jQuery -->
</body>

<div id="editmodal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog">
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title">Change Language Value</h4>
	  </div>
	  <div class="modal-body">            
			  <div class="form-group">
				<label for="recipient-name" class="control-label">value:</label>
				<input type="text" name="language_value" class="form-control" id="language_value" value=""/>
				<input type="hidden" name="language_id" id="language_id" value=""/>
			 </div>
	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn btn-primary  changelanguageval">Save changes</button>
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	  </div>
	</div>
  </div>
</div>
<div id="addmodal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog">
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title">Add Language Value</h4>
	  </div>
	  <div class="modal-body">       
			 <div class="form-group">
				<label for="recipient-name" class="control-label">Key:</label>
				<input type="text" name="language_key" class="form-control" id="language_key" required value=""/>
			 </div>
			 <div class="form-group"> 
				<label for="recipient-name" class="control-label">value:</label>
				<input type="text" name="language_value" class="form-control" id="add_language_value" required value=""/>
			 </div>
	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn btn-primary  addlanguageval">Add</button>
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	  </div>
	</div>
  </div>
</div>
<script type="text/javascript">
	$('#page_reload').click(function(){
		location.reload(true);
	});
	
	
	
	$('.languageaction').on('change', function(e) {
		//alert("test");
		var val = $(this).val();
		var id = $(this).attr("id");
		$(".selectAction").select2("val", "");
		//$(".editmodal").val("").trigger('chosen:updated');
		switch (val) {
			case "edit":
				editLanguageValue(id);
				break;
			case "delete":
				deleteLanguageValue(id);
				break;	
			default:
				break;
		}
	});
	function editLanguageValue(id) { //alert(id);
		$('#editmodal').modal('show');
		//language_id
		$("#language_id").val(id);
		 $.ajax({
			url: siteUrl + "ajax/getLanguageValue",
			type: 'POST',
			dataType: 'json',
			data: {
				 id: id
			},
			success: function(data) {
				if (data) { //alert(data);
							$("#language_value").val(data);
						/*
						$.each(data, function(data, obj) {
							console.log(obj["value"]);
							//alert (JSON.stringify(obj));  
						
					    });*/
				//	
				}
			}	
		 });
	}
	
	function deleteLanguageValue(id) {
		//language_id
		//$("#language_id").val(id);
		
		if(confirm('Are you sure, want to delete this record?')){ 
			var actiontype = "delete";
			language_val = '';
		 $.ajax({
			url: siteUrl + "ajax/upadateLanguageValue",
			type: 'POST',
			dataType: 'json',
			data: {
				 id: id,
				 actiontype: actiontype,
				 language_val: language_val
			},
			success: function() {
				$('#row-'+id).remove();
			}	
		 });
		}
		
	}	
	
	$('.changelanguageval').on('click', function(e) {
		//alert($("#language_value").val());
		var language_val = $("#language_value").val();
		var id           = $("#language_id").val();
		var actiontype   = "edit";
		language_val = htmlSpecialChars(language_val);
		$.ajax({
			url: siteUrl + "ajax/upadateLanguageValue",
			type: 'POST',
			dataType: 'json',
			data: {
				 language_val: language_val,
				 actiontype: actiontype,
				 id:id
			},
			success: function() {
				location.reload(true);
			}	
		});	
	});	
	
	function createNewLangVal(){
		$('#addmodal').modal('show');	
		$("#language_key").val('');
		$("#add_language_value").val('');
	}	
	$('#language_key').keypress(function(){ 
		$("#language_key").css( "border-color", "" );
	})
	$('#add_language_value').keypress(function(){ 
		$("#add_language_value").css( "border-color", "" );
	})
	$('.addlanguageval').on('click',function(e){
		var language_key 	= $("#language_key").val();
		var language_value  = htmlSpecialChars($("#add_language_value").val()); //alert(language_value);
		var language_id = $("#language_id").val();  
		var siteid = $("#siteid").val();  
		if(language_key == '' || language_value == ''){
			if(language_key == ''){
				$("#language_key").css( "border-color", "red" );
			}
			if(language_value == ''){
				$("#add_language_value").css( "border-color", "red" );
			}	
			return false;
		}	
		
		$.ajax({
			url: siteUrl + "ajax/addLanguageValue",
			type: 'POST',
			dataType: 'json',
			data: {
				 language_key: language_key,
				 language_val: language_value,
				 language_id: language_id,
				 siteid:siteid
			},
			success: function() {
				location.reload(true);
			}	
		});
	});
	
	
	function htmlSpecialChars(text) {
 
	  return text
	  .replace(/&/g, "&amp;")
	  .replace(/"/g, "&quot;")
	  .replace(/'/g, "&#039;")
	  .replace(/</g, "&lt")
	  .replace(/>/g, "&gt");
	 
	}
</script>
