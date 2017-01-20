<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
	<!--- Top nav && left nav--->
	<?php echo $topnav.$leftnav;?>
	<!--- Top nav && left nav --->
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper" id="page-wrapper">
        <div class="container-fluid">
		<!-- Content Header (Page header) -->
        <section class="content-header">
          <h1 class="page-header"> "<?php echo ucfirst($site_name);?>"  <?php echo (isset($site_language['Create Testimonial'])) ? $site_language['Create Testimonial'] : 'Create Testimonial';?></h1>
          <ol class="breadcrumb">
           <li><a href="<?php echo URL::site('admin/dashboard'); ?>"><i class="fa fa-dashboard"></i> <?php echo (isset($site_language['Home'])) ? $site_language['Home'] : 'Home';?></a></li>
			 <li> <a href="<?php echo URL::site('admin/site/');?>"><?php echo (isset($site_language['Sites'])) ? $site_language['Sites'] : 'Sites';?></a></li>
			 
			 <li> <a href="<?php echo URL::site('admin/site/');?>"><?php echo ucfirst($site_name);?></a></li>
            <li><a href="<?php echo URL::site('admin/site/testimonialbrowse/'.$site_id);?>"> <?php echo (isset($site_language['Browse Testimonials'])) ? $site_language['Browse Testimonials'] : 'Browse Testimonials';?></a></li>
           
			<li class="active"><?php echo (isset($site_language['Create Testimonial'])) ? $site_language['Create Testimonial'] : 'Create Testimonial';?></li>
          </ol>
        </section>
		
		<div class="row">
			<div class="col-lg-12">
				<div id="errors" class="form-group">
					<?php if (isset($error_messages) && count($error_messages)>0): ?>
					<div class="banner alert alert-error">
							<?php foreach ($error_messages as $error_message): ?>
								<i class="fa fa-exclamation-triangle"></i><span><?php echo $error_message; ?></span>
							<?php endforeach ?>
						
					</div>
					<?php endif; ?>
				</div>
			</div>
		</div>	
        <!-- Main content -->
		<div class="row">
			<div class="col-lg-12">
				<form action="<?php echo URL::site(Request::current()->uri()); ?>" method="post" enctype="multipart/form-data"> <!-- class="form-horizontal" -->
					<div class="form-group">
					  <label  for="b_title"><?php echo (isset($site_language['Testimonial User'])) ? $site_language['Testimonial User'] : 'Testimonial User';?></label>
						<input type="text" placeholder="<?php echo (isset($site_language['Testimonial User'])) ? $site_language['Testimonial User'] : 'Testimonial User';?>" required="true" class="form-control" name="t_user" value="<?php echo (isset($BlockDetails->t_user) ? $BlockDetails->t_user : '');?>" >
					</div>
					<div class="form-group">
					  <label for="b_title"><?php echo (isset($site_language['Testimonial'])) ? $site_language['Testimonial'] : 'Testimonial';?> <?php echo (isset($site_language['Title'])) ? $site_language['Title'] : 'Title';?></label>
						<input type="text" placeholder="<?php echo (isset($site_language['Testimonial'])) ? $site_language['Testimonial'] : 'Testimonial';?> <?php echo (isset($site_language['Title'])) ? $site_language['Title'] : 'Title';?>" required="true" class="form-control" name="t_title" value="<?php echo (isset($BlockDetails->t_title) ? $BlockDetails->t_title : '');?>" >
					</div>
					
					<div class="form-group">
					  <label for="t_description"><?php echo (isset($site_language['Testimonial'])) ? $site_language['Testimonial'] : 'Testimonial';?> <?php echo (isset($site_language['Description'])) ? $site_language['Description'] : 'Description';?></label>
						<!-- <textarea id="blockdescription" name="t_description" rows="10" cols="80"><?php //echo (isset($BlockDetails->t_description) ? $BlockDetails->t_description : '');?></textarea> -->
						<textarea id="t_description" name='t_description'><?php echo (isset($BlockDetails))?$BlockDetails->t_description:''; ?></textarea>
					</div>
					
					<div class="form-group">
						<label for="status"><?php echo (isset($site_language['Status'])) ? $site_language['Status'] : 'Status';?></label>
						<div class="radio">
							<label><input type="radio" <?php echo (!isset($BlockDetails->is_active) || (isset($BlockDetails->is_active) && $BlockDetails->is_active=='1') ? 'checked=""' : '');?> value="1" name="status">Active</label>
						</div>
						<div class="radio">
							<label><input type="radio" <?php echo ((isset($BlockDetails->is_active) && $BlockDetails->is_active=='0') ? 'checked=""' : '');?> value="0" name="status">Inactive</label>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-3">&nbsp;</div>
						<button class="btn btn-default" type="submit" id="saveblock" style="margin-right:20px;"><?php echo (isset($site_language['Save'])) ? $site_language['Save'] : 'Save'; ?></button>
					</div><!-- /.box-footer -->
				</form>
			</div>	
		</div>	 <!-- Main content -->
		</div>
      </div><!-- /.content-wrapper -->
     
      <!-- Add the sidebar's background. This div must be placed
           immediately after the control sidebar -->
      <div class="control-sidebar-bg"></div>
    </div><!-- ./wrapper -->
</body>
