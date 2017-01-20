<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
	<?php echo $topnav.$leftnav;?>
	<!--- Top nav && left nav --->
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper" id="page-wrapper">
        <div class="container-fluid">
		<!-- Content Header (Page header) -->
			<section class="content-header">
			  <h1 class="page-header"> "<?php echo ucfirst($site_name);?>" <?php echo (isset($site_language['Block Create'])) ? $site_language['Block Create'] : 'Block Create'; ?></h1>
			  <ol class="breadcrumb">
				<li><a href="<?php echo URL::site('admin/dashboard'); ?>"><i class="fa fa-dashboard"></i> <?php echo (isset($site_language['Home'])) ? $site_language['Home'] : 'Home'; ?></a></li>
				<li> <a href="<?php echo URL::site('admin/site/');?>"><?php echo (isset($site_language['Sites'])) ? $site_language['Sites'] : 'Sites'; ?></a></li>
				
				 <li> <a href="<?php echo URL::site('admin/site/');?>"><?php echo ucfirst($site_name);?></a></li>
				
				<li><a href="<?php echo URL::site('admin/site/blockbrowse/'.base64_encode($site_id));?>"> <?php echo (isset($site_language['Site Blocks'])) ? $site_language['Site Blocks'] : 'Site Blocks'; ?></a></li>
			   
				<li class="active"><?php echo (isset($site_language['Create Block'])) ? $site_language['Create Block'] : 'Create Block'; ?></li>
			  </ol>
			</section>
		
			<div class="row">
				<div class="col-lg-12">
					<div id="errors" class="form-group">
						<?php if (isset($error_messages) && count($error_messages)>0): ?>
						<div class="banner alert alert-danger">
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
					<form action="<?php echo URL::site(Request::current()->uri()); ?>" method="post"  enctype="multipart/form-data"><!-- class="form-horizontal" -->		
						<div class="form-group">
						  <label  for="b_title"><?php echo (isset($site_language['Block'])) ? $site_language['Block'] : 'Block'; ?> <?php echo (isset($site_language['Title'])) ? $site_language['Title'] : 'Title'; ?></label>
							<input type="text" placeholder="<?php echo (isset($site_language['Block'])) ? $site_language['Block'] : 'Block'; ?> <?php echo (isset($site_language['Title'])) ? $site_language['Title'] : 'Title'; ?>" required="true" class="form-control" name="b_title" value="<?php echo (isset($BlockDetails->b_title) ? $BlockDetails->b_title : '');?>" >
						</div>
						<div class="form-group">
						  <label for="b_url"><?php echo (isset($site_language['Block'])) ? $site_language['Block'] : 'Block'; ?> <?php echo (isset($site_language['Target Url'])) ? $site_language['Target Url'] : 'Target Url'; ?></label>
						  
						  <input type="text" placeholder="<?php echo (isset($site_language['Block'])) ? $site_language['Block'] : 'Block'; ?> <?php echo (isset($site_language['Target Url'])) ? $site_language['Target Url'] : 'Target Url'; ?>" required="true" class="form-control" name="b_url" value="<?php echo (isset($BlockDetails->b_url) ? $BlockDetails->b_url : 'http://');?>" >
						  
						</div>
						<div class="form-group">
						  <label for="b_description"><?php echo (isset($site_language['Block'])) ? $site_language['Block'] : 'Block'; ?> <?php echo (isset($site_language['Description'])) ? $site_language['Description'] : 'Description'; ?></label>
							<!-- <textarea id="blockdescription" name="b_description" rows="10" cols="80"><?php //echo (isset($BlockDetails->b_description) ? $BlockDetails->b_description : '');?></textarea> -->
							<?php //echo $editor->editor('b_description',$BlockDetails->b_description); ?>
							<textarea id="b_description" name='b_description'><?php echo (isset($BlockDetails))?$BlockDetails->b_description:''; ?></textarea>
						</div>
						<div class="form-group">
						  <label for=photo"><?php echo (isset($site_language['Block'])) ? $site_language['Block'] : 'Block'; ?> <?php echo (isset($site_language['Image'])) ? $site_language['Image'] : 'Image'; ?></label>
						  
						  <input type="file" id="photo"  name="blockphoto">
							<p class="help-block">(Allowed image types are jpg, jpeg, bmp, png, gif.)</p>
						  
						  <?php if(!empty($BlockDetails->b_image)){
								$sliderImage = 'assets/uploads/manage/homepage/block/'.$BlockDetails->b_image;
						  ?>
							  <div class="col-sm-3">
								<img alt="<?php echo (isset($BlockDetails->b_title) ? $BlockDetails->b_title : '');?>" src="<?php echo URL::base().(file_exists($sliderImage) ?  $sliderImage : 'assets/images/no-images.jpg');?>" class="img-responsive img-square" width="100px">
								<input type="hidden" name="hidden-blockid" value="<?php echo (isset($BlockDetails->id) ? base64_encode($BlockDetails->id) : '');?>">
							  </div>
						  <?php } ?>
						</div>
						<div class="form-group">
							<label  for="status"><?php echo (isset($site_language['Status'])) ? $site_language['Status'] : 'Status'; ?></label>
							<div class="radio">
								<label><input type="radio" <?php echo (!isset($BlockDetails->is_active) || (isset($BlockDetails->is_active) && $BlockDetails->is_active=='1') ? 'checked=""' : '');?> value="1" name="status">Active</label>
							</div>
							<div class="radio">
								<label><input type="radio" <?php echo ((isset($BlockDetails->is_active) && $BlockDetails->is_active=='0') ? 'checked=""' : '');?> value="0" name="status">Inactive</label>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-3">&nbsp;</div>
							<button class="btn btn-default " type="submit" id="saveblock" style="margin-right:20px;"><?php echo (isset($site_language['Save'])) ? $site_language['Save'] : 'Save'; ?></button>
						</div><!-- /.box-footer -->
					 <!-- /.content -->
					</form>
				</div>
			</div>			
		</div> <!-- /.container-fluid -->
	  </div><!-- /.content-wrapper -->
      
      <!-- Add the sidebar's background. This div must be placed
           immediately after the control sidebar -->
      <div class="control-sidebar-bg"></div>
    </div><!-- ./wrapper -->
</body>
