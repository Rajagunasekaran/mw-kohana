<!--- Top nav && left nav--->
	<?php echo $topnav.$leftnav;?>
	<?php
	
echo $imglibrary;


	

?>

	
	<!--- Top nav && left nav --->
      <!-- Content Wrapper. Contains page content -->
      <?php if(isset($device_details) && count($device_details)>0) {
		foreach($device_details as $key => $value) {
			$device_id		= $value['id'];
			$device_name	= $value['name'];
			$Status			= $value['status'];
		}
	  } ?>
	  <div id="page-wrapper">
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                          <?php $session = Session::instance();
							if($session->get('current_site_name')!=''){echo '"'.$session->get('current_site_name').'"';}
							echo (isset($formtype) && $formtype=='edit')?" Modify Exercise Record":" Create Device";
							?>
                        </h1>
                        <ol class="breadcrumb">
                            <li>
                                <i class="fa fa-dashboard"></i>  <a href="<?php echo URL::base().'admin/index'; ?>">Dashboard</a>
                            </li>
                            <li class="active">
                                <i class="fa fa-edit"></i>  <?php
										  echo (isset($formtype) && $formtype=='edit')?" Modify Exercise Record":"Create Device";
										  ?>
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
						 <form method="post" role="form" action="">
								<div class="form-group">
									<label><?php echo (isset($site_language['Device Name'])) ? $site_language['Device Name'] : 'Device Name'; ?></label>
									<input type="text" name="name" class="form-control" value="<?php if(isset($device_name)){ echo $device_name; } ?>">
									<label><?php echo (isset($site_language['Status'])) ? $site_language['Status'] : 'Status'; ?></label>
									<select  class="form-control" name="status" onchange="deviceAction(this.value,<?php if(isset($device_details)){ echo $value['id']; } ?>);">
										<option value="0"<?php if(isset($Status)){ if($Status == 0){echo "Selected";}} ?>>Active</option>
										<option value="1" <?php if(isset($Status)){  if($Status == 1){echo "Selected";}} ?> > Deactive</option>
									</select> 
								</div>
								<button class="btn btn-default" name="submit" type="submit"><?php echo (isset($site_language['Save'])) ? $site_language['Save'] : 'Save'; ?></button>
								
							</form>
					</div>
				</div>
			</div>
		</div>	
			
<script type="text/javascript">

</script>
<?php /*********************************************************/ ?>
</div>
<?php /****************Ends Here*************************/ ?>

			
			</div>
            <!-- /.container-fluid -->
		</div>
        <!-- /#page-wrapper -->
	</div>
    <!-- /#wrapper -->
</body>
<script type="text/javascript">
$('#xrRecInsertForm .tab-content .tab-pane').scroll(function() {
	console.log("Admin")
	if ($(this).scrollTop()>0) {
		$('.pager.wizard').fadeOut();
	} else {
		$('.pager.wizard').fadeIn();
	}
});


</script>
<?php echo $imgeditor2; //require_once(APPPATH.'views/templates/front/imglib-imgeditor.php'); ?>