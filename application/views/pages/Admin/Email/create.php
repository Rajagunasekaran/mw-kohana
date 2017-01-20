	<!--- Top nav && left nav--->
	<?php echo $topnav.$leftnav;?>
	<!--- Top nav && left nav --->
	<!-- Content Wrapper. Contains page content -->
	<?php 
	$template_id = $template_name = $subject = $body = $status = $smtp = '';
	if(isset($template_details) && count($template_details)>0) { 
		$template_id	= $template_details[0]['template_id'];
		$template_name 	= $template_details[0]['template_name'];
		$subject 		= $template_details[0]['subject'];
		$body 			= $template_details[0]['body'];
		$status 		= $template_details[0]['status'];
		$smtp 			= $template_details[0]['smtp_id'];
	} 
	$statusArray = Helper_Common::emailTemplateStatusArray();
	
	?>
	<div id="page-wrapper">
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-12">
					<h1 class="page-header"><?php echo "\"".ucfirst($site_name)."\" "; if($template_id!='') { echo (isset($site_language['Edit'])) ? $site_language['Edit'] : 'Edit'; } else { echo (isset($site_language['Create'])) ? $site_language['Create'] : 'Create'; } ?> <?php echo (isset($site_language['Email Template'])) ? $site_language['Email Template'] : 'Email Template'; ?></h1>
					<ol class="breadcrumb">
						<li>
							<i class="fa fa-dashboard"></i>  <a href="<?php echo URL::base().'admin/dashboard'; ?>"><?php echo (isset($site_language['Dashboard'])) ? $site_language['Dashboard'] : 'Dashboard'; ?></a>
						</li>
						<li class="active">
							<i class="fa fa-edit"></i> <?php if($template_id!='') { echo (isset($site_language['Edit'])) ? $site_language['Edit'] : 'Edit'; } else { echo (isset($site_language['Create'])) ? $site_language['Create'] : 'Create'; } ?> <?php echo (isset($site_language['Email Template'])) ? $site_language['Email Template'] : 'Email Template'; ?>
						</li>
					</ol>
				</div>
			</div>
			<?php if(isset($errors) && count($errors)>0) { 
			$labelArray = array(
									'template_name'	=> 'Template Name',
									'subject'		=> 'Subject',
									'body'			=> 'Body',
									'smtp_id'		=> 'SMTP'
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
			<?php if(isset($template_name) && count($template_name)>0) { ?>
				<div class="row">
					<div class="col-lg-12">
						<div class="form-group">
							<select class="form-control selectAction" onchange="loadTemplate(this.value);">
								<option value="">New Template</option>
								<?php foreach($template_name_array as $key => $value){ ?>
									<option value="<?php echo $value['template_id'];?>"<?php if($value['template_id']==$template_id) { echo 'selected'; } ?>><?php echo $value['template_name'];?></option>
								<?php } ?>
							</select>
						</div>
					</div>
				</div>
			<?php } ?>
			<div class="row">
				<div class="col-lg-12">
					 <form method="post" role="form" action="">
                            <div class="form-group">
                                <label><?php echo (isset($site_language['Template Name'])) ? $site_language['Template Name'] : 'Template Name'; ?></label>
                                <input type="text" name="template_name" class="form-control" value="<?php echo $template_name;?>">
                            </div>
							<div class="form-group">
                                <label><?php echo (isset($site_language['Subject'])) ? $site_language['Subject'] : 'Subject'; ?></label>
                                <input type="text" name="subject" class="form-control" value="<?php echo $subject;?>">
                            </div>
							<div class="form-group">
                                <label><?php echo (isset($site_language['Body'])) ? $site_language['Body'] : 'Body'; ?></label>
                               <?php echo $editor->editor('body',$body); ?>
                            </div>
							<div class="form-group">
								<label><?php echo (isset($site_language['Status'])) ? $site_language['Status'] : 'Status'; ?></label>
								<select name="status" class="form-control selectAction">
									<?php foreach($statusArray as $key => $value){ 
										if($key!=3) { ?>
											<option value="<?php echo $key;?>"<?php if($key==$status) { echo 'selected'; } ?>><?php echo $value;?></option>
										<?php } 
									} ?>
								</select>
							</div>
							<?php if(isset($smtp_array) && count($smtp_array)>0) { ?>
								<div class="form-group">
									<label><?php echo (isset($site_language['SMTP'])) ? $site_language['SMTP'] : 'SMTP'; ?></label>
									<select name="smtp_id" class="form-control selectAction" >
										<option value=""> Select </option>
										<?php foreach($smtp_array as $key => $value){ ?>
											<option value="<?php echo $value['smtp_id'];?>"<?php if($value['smtp_id']==$smtp) { echo 'selected'; } ?>><?php echo $value['smtp_user'];?></option>
										<?php } ?>
									</select>
								</div>
							<?php } ?>
							
							<?php
							if($subject && $body){ ?>
								<div class="form-group">
									<?php
									$str = $subject." ".$body;
									preg_match_all("/\[(.*?)\]/", $str, $matches);
									$cons = array_unique($matches[0]);
									echo "Variables : ";
									foreach($cons as $k=>$v){
										echo "$v&nbsp;&nbsp;&nbsp;&nbsp;";
									}
									?>
								</div>
								<?php
							}
							?>
							
							
							
							<button class="btn btn-default" name="submit" type="submit"><?php echo (isset($site_language['Save'])) ? $site_language['Save'] : 'Save'; ?></button>
							<a class="btn btn-default" href="<?php echo URL::base().'admin/email/templatename/'.$site_id;?>"><?php echo (isset($site_language['Template List'])) ? $site_language['Template List'] : 'Template List'; ?></a>
							<input type="hidden" name="template_id" value="<?php echo $template_id;?>" />
							<input type="hidden" name="site_id" value="<?php echo $site_id;?>" />
						</form>
				</div>
			</div>
			
		</div>
		<!-- /.container-fluid -->
	</div>
	<!-- /#page-wrapper -->
	</div>
	<!-- /#wrapper -->
	<script type="text/javascript">
	function loadTemplate(tempId) {
		window.location.href='<?php echo URL::base().'admin/email/create/'.$site_id.'/'; ?>'+tempId;
	}
	</script>
</body>

