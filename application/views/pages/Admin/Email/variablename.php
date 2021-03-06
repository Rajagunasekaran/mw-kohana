	<!--- Top nav && left nav--->
	<?php echo $topnav.$leftnav;?>
	<!--- Top nav && left nav --->
      <!-- Content Wrapper. Contains page content -->
      <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            "<?php echo ucfirst($site_name); ?>" <?php echo (isset($site_language['Email variable List'])) ? $site_language['Email variable List'] : 'Email variable List'; ?>
                        </h1>
                        <ol class="breadcrumb">
                            <li>
                                <i class="fa fa-dashboard"></i>  <a href="<?php echo URL::base().'admin/index'; ?>"><?php echo (isset($site_language['Dashboard'])) ? $site_language['Dashboard'] : 'Dashboard'; ?></a>
                            </li>
                            <li class="active">
                                <i class="fa fa-edit"></i> <?php echo (isset($site_language['Email variable List'])) ? $site_language['Email variable List'] : 'Email variable List'; ?>
                            </li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->
				<?php if(isset($success) && $success!='') {  ?>
				<div class="row">
					<div class="col-lg-12">
						<div class="alert alert-success">
						  <i class="fa fa-check"></i><span><?php echo $success;?></span>
						</div>
					</div>
				</div>
			<?php } ?>
				
				<div class="row">
					<div class="col-lg-12">
						<div  id="share_msg" class="">
							
						</div>
					</div>
				</div>
			
                <div class="row">
				<?php //print_r($SmtpDetails); ?>
				<?php if(isset($template_variables) && count($template_variables)>0) { 
				$statusArray = Helper_Common::emailTemplateStatusArray();
				?>
				
                <div class="col-lg-12">
                        <h2>Email Variable List</h2>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th><?php echo (isset($site_language['Template Name'])) ? $site_language['Variable Name'] : 'Variable Name'; ?></th>
                                        <th><?php echo (isset($site_language['Content'])) ? $site_language['Content'] : 'Content'; ?></th>
										 <th><?php echo (isset($site_language['Created by'])) ? $site_language['Created by'] : 'Created by'; ?></th>
										<?php //if(Helper_Common::hasAccess('Modify variable')) { ?>
											<th><?php echo (isset($site_language['Action'])) ? $site_language['Action'] : 'Action'; ?></th>
										<?php// } ?>
									</tr>
                                </thead>
                                <tbody>
								<?php // print_r($template_variables); die;
									foreach($template_variables as $key => $value) { 
								?>
								
                                    <tr>
                                        <td><?php echo $value['name']; ?></td>
										<td><?php echo htmlspecialchars_decode($value['variable_content'],ENT_QUOTES); ?></td>
										<td><?php echo $value['user_fname']." ".$value['user_lname']; ?></td>
										<?php if(Helper_Common::hasAccess('Modify Template')) { ?>
											<td>
												<select  class="form-control selectAction" onchange="variableAction(this.value,'<?php echo $value['variable_id'];?>');">
													<option value="">Choose Action</option>
														<option value="edit" <?php if($loginUserId != $value['id']) { echo "disabled=disabled"; echo 'class="disabled"';}?> >Edit Variable</option>
														<option value="delete" <?php if($loginUserId != $value['id']) { echo "disabled=disabled"; }?> >Delete Variable</option>
												</select>
											</td>
										<?php } ?>
									</tr>
								<?php } ?>	
									
                                </tbody>
                            </table>
                        </div>
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
    <input type="hidden" id="site_id" value="<?php echo $site_id;?>" />

</body>

<!-- Share Template -->


<div id="shareTemplateModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog">
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title">Share this template</h4>
	  </div>
	  <div class="modal-body"> 
			<input type="hidden" name="template_id" id="template_id" value="">
			 <div class="form-group">
				<label for="workout-name" class="control-label">Select Sites:</label>
				<?php
				$fetch_field  = "id, name";
				$fetch_condtn = "is_active = 1 and is_deleted=0";
				$listsites = Model::instance('Model/admin/user')->get_table_details_by_condtn('sites',$fetch_field,$fetch_condtn);

				?>
				<select id='share_site_id' class="site_id form-control fullwidth select2" style="width: 100%;" multiple style="width:350px;" tabindex="4">
					<option value=""></option>
					<?php
					if($listsites){
						foreach($listsites as $k=>$v){
							echo "<option value='".$v["id"]."'>".$v["name"]."</option>";
						}
					}
					?>
				</select>
			</div>
	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn btn-primary" onclick="share_template();" >Share</button>
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	  </div>
	</div>
  </div>
</div>




<!-- Share Template -->
<script type="text/javascript">
	function share_template(){
			//alert("share_template");
			var share_site_id = $("#share_site_id").val(); //alert(site_id);
			var site_id = $("#site_id").val(); //alert(site_id);
			var template_id = $('#template_id').val();
			$.ajax({
				url: siteUrl + "email/share_template",
				type: 'POST',
				dataType: 'json',
				data: {
					share_site_id:share_site_id,
					site_id:site_id,
					template_id:template_id
				},
				success: function(data) {
					if(data > 0){
						$('#share_msg').html("Template shared successfully.");
						$('#share_msg').removeClass('alert alert-danger');
						$('#share_msg').addClass('alert alert-success');
					}else{
						$('#share_msg').html("Template already exist.");
						$('#share_msg').removeClass('alert alert-success');
						$('#share_msg').addClass('alert alert-danger');
					}	
					$('#shareTemplateModal').modal('hide');
				}
			});
			
	}	
</script>

