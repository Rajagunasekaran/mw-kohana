	<!--- Top nav && left nav--->
	<?php echo $topnav.$leftnav;?>
	<!--- Top nav && left nav --->
	<!-- Content Wrapper. Contains page content -->
	<?php 
	$template_id = $template_name = $subject = $body = $status = $smtp = ''; /// print_r($variable_details); die;
	if(isset($variable_details) && count($variable_details)>0) { 
		$variable_id	  = $variable_details[0]['variable_id'];
		$name 			  = $variable_details[0]['name'];
		$variable_content = $variable_details[0]['variable_content'];
		$status 		  = $variable_details[0]['status'];
	} 
	$statusArray = Helper_Common::emailTemplateStatusArray();
	
	?>
	<div id="page-wrapper">
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-12">
					<h1 class="page-header"><?php echo "\"".ucfirst($site_name)."\" "; ?> <?php echo (isset($site_language['Email Variable'])) ? $site_language['Email Variable'] : 'Email Variable'; ?></h1>
					<ol class="breadcrumb">
						<li>
							<i class="fa fa-dashboard"></i>  <a href="<?php echo URL::base().'admin/dashboard'; ?>"><?php echo (isset($site_language['Dashboard'])) ? $site_language['Dashboard'] : 'Dashboard'; ?></a>
						</li>
						<li class="active">
							<i class="fa fa-edit"></i> <?php echo (isset($site_language['Create'])) ? $site_language['Create'] : 'Create'; ?> <?php echo (isset($site_language['Email Variable'])) ? $site_language['Email Variable'] : 'Email Variable'; ?>
						</li>
					</ol>
				</div>
			</div>
		
			<?php if(isset($errors) && count($errors)>0) { 
			$labelArray = array(
				'name' => 'Template Name',
				'variable_content'=> 'Body'
			);
			//print_r($labelArray);die;
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
			<?php } 
			
			if(isset($error) && $error!='') {  ?>
				<div class="row">
					<div class="col-lg-12">
						<div class="alert alert alert-danger">
						  <i class="fa fa-exclamation-triangle"></i><span><?php echo $error;?></span>
						</div>
					</div>
				</div>
			<?php } 
			
			?>
			
			<div class="row">
				<div class="col-lg-12">
					 <form method="post" role="form" action="">
                            <div class="form-group">
                                <label><?php echo  "Email variable";?></label>
                                <input type="text" name="name" class="form-control" value="<?php if(isset($name)){echo $name; }?>">
                            </div>
							<div class="form-group">
                                <label><?php echo 'Content'; ?></label>
                               <?php //echo $editor->editor('body',''); ?>
							  <textarea name ="variable_content" class="form-control" ><?php if(isset($variable_content)){echo $variable_content;}?></textarea> 
                            </div>
							<button class="btn btn-default"  name="submit" type="submit"><?php echo 'Save'; ?></button>
							
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

