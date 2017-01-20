	<!--- Top nav && left nav--->
	<?php echo $topnav.$leftnav;?>
	<!--- Top nav && left nav --->
      <!-- Content Wrapper. Contains page content -->
	  <?php
		$rightwayArray = Helper_Common::getDeliveryStatusArray();
		$statusArray = Helper_Common::emailTemplateStatusArray();
	  ?>
      <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            "<?php echo ucfirst($site_name);?>" <?php echo (isset($site_language['Set Delivery Forms'])) ? $site_language['Set Delivery Forms'] : 'Set Delivery Forms'; ?>
                        </h1>
                        <ol class="breadcrumb">
                            <li>
                                <i class="fa fa-dashboard"></i>  <a href="<?php echo URL::base().'admin/index'; ?>"><?php echo (isset($site_language['Dashboard'])) ? $site_language['Dashboard'] : 'Dashboard'; ?></a>
                            </li>
                            <li class="active">
                                <i class="fa fa-edit"></i> <?php echo (isset($site_language['Set Delivery Forms'])) ? $site_language['Set Delivery Forms'] : 'Set Delivery Forms'; ?>
                            </li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->
				<?php if(isset($errors) && count($errors)>0) { 
				$labelArray = array(
									'smtphost'		=> 'SMTP Host',
									'smtpport'		=> 'SMTP Port',
									'smtpuser'		=> 'SMTP Username',
									'smtppass'		=> 'SMTP Password',
									'smtpfrom'		=> 'SMTP From',
									'smtpreplyto'	=> 'SMTP Reply To'
								);
				?>
				<div class="row">
					<div class="col-lg-12">
						<div class="alert alert-danger">
						  <?php foreach($errors as $key => $value) { 
							$msg = str_replace($key,$labelArray[$key],$value);
						  ?>
							<i class="fa fa-exclamation-triangle"></i><span><?php echo $msg; ?></span>
						  <?php } ?>
						</div>
					</div>
				</div>
				<?php } 
				if(isset($success) && $success!='') {  ?>
					<div class="row">
						<div class="col-lg-12">
							<div class="alert alert-success">
							  <i class="fa fa-check"></i><span><?php echo $success;?></span>
							</div>
						</div>
					</div>
				<?php } ?>
                <div class="row">
                    <div class="col-lg-6">

                        <form role="form" method="post" action="">

                            <div class="form-group">
                                <label><?php echo (isset($site_language['Title'])) ? $site_language['Title'] : 'Title'; ?></label>
                                <input type="text" required="true" class="form-control" value="<?php if(!empty($DeliveryGet)) { echo $DeliveryGet[0]['delivery_name']; } ?>" name="d_title">
                            </div>
							
							<div class="form-group">
                                <label><?php echo (isset($site_language['Template'])) ? $site_language['Template'] : 'Template'; ?></label>
                               <select name="d_template" required="true" class="form-control selectAction">
									<option value=""><?php echo (isset($site_language['Select'])) ? $site_language['Select'] : 'Select'; ?> <?php echo (isset($site_language['Template'])) ? $site_language['Template'] : 'Template'; ?></option>
									<?php foreach($emailTemplateArray as $key => $value){ ?>
											<option value="<?php echo $value['template_id'];?>"<?php if(isset($DeliveryGet) && $value['template_id']==$DeliveryGet[0]['template_id']) { echo 'selected'; } ?>><?php echo $value['template_name'];?></option>
									<?php } 
									 ?>
								</select>
                            </div>
							<div class="form-group">
								<label><?php echo (isset($site_language['Right away send'])) ? $site_language['Right away send'] : 'Right away send'; ?></label>
								<select name="d_rightaway" id="rightaway" class="form-control selectAction">
									<?php foreach($rightwayArray as $key => $value){ ?>
											<option value="<?php echo $key;?>"<?php if(isset($DeliveryGet) && $key==$DeliveryGet[0]['is_rightaway']) { echo 'selected'; } ?>><?php echo $value;?></option>
									<?php } 
									 ?>
								</select>
							</div>
							<div  class="input-append form-group">
                                <label><?php echo (isset($site_language['Send Date'])) ? $site_language['Send Date'] : 'Send Date'; ?></label>
                                <input id="datepicker" type="text" data-format="dd/MM/yyyy"  required="true" class="form-control add-on" value="<?php if(!empty($DeliveryGet)) { echo $DeliveryGet[0]['send_date']; } ?>" name="d_senddate">
                            </div>
							
							<div class="hiderightaway" <?php if(isset($DeliveryGet) && $DeliveryGet[0]['is_rightaway']=='1'){ ?>style="display:none"<?php } ?>>
							<div class="form-group">
                                <label><?php echo (isset($site_language['Days'])) ? $site_language['Days'] : 'Days'; ?></label>
                                <input type="text" class="form-control" value="<?php if(!empty($DeliveryGet)) { echo $DeliveryGet[0]['triggerby_days']; } ?>" name="d_days">
                            </div>
							
							<div id="timepicker" class="form-group input-append date">
                                <label><?php echo (isset($site_language['Hours/Minutes'])) ? $site_language['Hours/Minutes'] : 'Hours/Minutes'; ?></label>
                                <!-- <input type="text" data-format="hh:mm:ss"  class="form-control add-on" id="timepicker" value="<?php //if(!empty($DeliveryGet)) { echo $DeliveryGet[0]['triggerby_hours']; } ?>" name="d_hoursminutues">  -->
                            </div>
							<div class='input-group date' id='datetimepicker3'>
								<input type='text' class="form-control" value="<?php if(!empty($DeliveryGet)) { echo $DeliveryGet[0]['triggerby_hours']; } ?>" name="d_hoursminutues" />
								<span class="input-group-addon">
									<span class="glyphicon glyphicon-time"></span>
								</span>
							</div>
							</div>
							<div class="form-group">
                                <label><?php echo (isset($site_language['Status'])) ? $site_language['Status'] : 'Status'; ?></label>
                                <select name="d_status" class="form-control selectAction">
									<?php foreach($statusArray as $key => $value){ 
										if($key!=3) { ?>
											<option value="<?php echo $key;?>"<?php if(isset($DeliveryGet) && $key==$DeliveryGet[0]['is_active']) { echo 'selected'; }elseif($key =='0'){ echo 'selected';} ?>><?php echo $value;?></option>
										<?php } 
									} ?>
								</select>
                            </div>
                            <button type="submit" name="submit" class="btn btn-default"><?php echo (isset($site_language['Submit Button'])) ? $site_language['Submit Button'] : 'Submit Button'; ?></button>
							<a class="btn btn-default" href="<?php echo URL::base().'admin/email/deliverysettings/'.$site_id;?>"><?php echo (isset($site_language['Delivery List'])) ? $site_language['Delivery List'] : 'Delivery List'; ?></a>
							<input type="hidden" name="d_id" value="<?php if(!empty($DeliveryGet)) { echo $DeliveryGet[0]['delivery_id']; } ?>" />
							<input type="hidden" name="site_id" value="<?php if(!empty($site_id)) { echo $site_id; } ?>" />
                        </form>

                    </div>
                    
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
<script>
 /*$(document).ready(function(){
	rightaway = $('#rightaway').val();
	if(rightaway == '1'){
		$('.hiderightaway').hide();
	}else{
		$('.hiderightaway').show();
	}
	
	$('#datepicker').datetimepicker({
		pickTime: false
    });
	
	$('#timepicker').datetimepicker({
		pickDate: false
    });
	
	$('#rightaway').change(function(){
		if($(this).val() == '1'){
			$('.hiderightaway').hide();
		}else{
			$('.hiderightaway').show();
		}
	});
 });*/
 
 
</script>