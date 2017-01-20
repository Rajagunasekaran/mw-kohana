<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<!--- Top nav && left nav--->
<?php echo $topnav.$leftnav;?>
<!--- Top nav && left nav --->
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" id="page-wrapper">
	<div class="container-fluid">
	<!-- Content Header (Page header) -->
		<section class="content-header">
			<h1 class="page-header">"<?php echo ucfirst($site_name);?>" <?php echo (isset($site_language['Social Post'])) ? $site_language['Social Post'] : 'Social Post';?> <?php echo (isset($site_language['Content'])) ? $site_language['Content'] : 'Content';?></h1>
			<ol class="breadcrumb">
				<li><a href="<?php echo URL::site('admin/dashboard'); ?>"><i class="fa fa-dashboard"></i> <?php echo __('Home');//echo (isset($site_language['Home'])) ? $site_language['Home'] : 'Home';?></a></li>
				<li> <a href="<?php echo URL::site('admin/sites'); ?>"><?php echo __('Sites');//echo (isset($site_language['Sites'])) ? $site_language['Sites'] : 'Sites';?></a></li>
				<li> <a href="<?php echo URL::site('admin/sites/');?>"><?php echo ucfirst($site_name);?></a></li>
				<li> <?php echo __('Social Page');//echo (isset($site_language['Homepage  Settings'])) ? $site_language['Homepage  Settings'] : 'Homepage  Settings';?></li>
			</ol>
		</section>
		<div class="row">
			<div class="col-lg-12">
				<div id="errors" class="form-group">
					<?php if (isset($error_messages) && count($error_messages)>0): ?>
						<div class="message_stack" style="text-align:center">
							<ul>
								<?php foreach ($error_messages as $error_message): ?>
									<li><?php echo $error_message; ?></li>
								<?php endforeach ?>
							</ul>
						</div>
					<?php endif; ?>
					<?php $session = Session::instance();
					if ($session->get('flash_success')): ?>
						<div class="banner alert alert-success">
							<i class="fa fa-check"></i><span><?php echo $session->get_once('flash_success') ?></span>
						</div>
					<?php elseif ($session->get('flash_error')): ?>
						<div class="banner alert alert-danger">
							<i class="fa fa-times"></i><span><?php echo $session->get_once('flash_error') ?></span>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>	
		<!-- Main content -->
		<div class="row">
			<div class="col-lg-12">
				<form action="<?php //echo Request::current()->uri(); //URL::site(Request::current()->uri()); ?>" method="post"  enctype="multipart/form-data">
					<div class="form-group">
						<label for="social_post_content"><?php echo (isset($site_language['Title'])) ? $site_language['Title'] : 'Title';?></label>
						<input id="site_title" name='site_title' value ="<?php echo (isset($BlockDetails['title'])) ? $BlockDetails['title'] : ''; ?>" class="form-control input-sm">
					</div>
					<div class="form-group">
						<label for="site_image"><?php echo (isset($site_language['Site Image'])) ? $site_language['Site Image'] : 'Site Image';?></label>
						<div class="image-content">
							<input type="file" id="site_image" name="site_image" value=""/>
							<?php if(isset($BlockDetails['site_image']) && $BlockDetails['site_image'] != ''){
								$siteimage = 'assets/uploads/logo/'.$BlockDetails['site_image']; ?>
								<img src="<?php echo URL::base_lang().(file_exists($siteimage) ?  $siteimage : 'assets/images/no-images.jpg');?>" class="img-responsive img-square socialpost-img" width="100px">
								<i class="fa fa-times-circle-o rmv-socialpost-img" data-siteid="<?php echo(isset($BlockDetails['site_id'])) ? $BlockDetails['site_id'] : '0'; ?>" data-src="<?php echo (file_exists($siteimage) ?  $siteimage : ''); ?>" style="cursor:pointer;" aria-hidden="true"></i>
							<?php } ?>
						</div> 
					</div>
					<div class="form-group">
						<label for="social_post_content"><?php echo (isset($site_language['Description'])) ? $site_language['Description'] : 'Description';?></label>
						<textarea id="social_post_content" name='social_post_content'><?php echo (isset($BlockDetails['description'])) ? $BlockDetails['description'] : ''; ?></textarea>
					</div>
					<div class="form-group">
						<label for="video"><?php echo(isset($site_language['Video'])) ? $site_language['Video'] : 'Video'; ?></label>
						<div class="video-content">	
							<div class="radio-inline">
								<label><input type="radio" name="statusvideo" <?php echo (isset($BlockDetails['video_status']) && $BlockDetails['video_status'] == '1') ? 'checked=""' : ''; ?> value="1" required="true">Active</label>
							</div>
							<div class="radio-inline">
								<label><input type="radio" name="statusvideo" <?php echo (isset($BlockDetails['video_status']) && $BlockDetails['video_status'] == '2') ? 'checked=""' : ''; ?> value="2" required="true">Inactive</label>
							</div>
							<textarea id="video" class="form-control" name="video" rows="5" cols="80"><?php echo (isset($BlockDetails['video'])) ? $BlockDetails['video'] : ''; ?></textarea>
							<p class="help-block">Example: http://www.youtube.com/embed/W7qWa52k-nE</p>
						</div>
					</div>
					<div class="form-group">
						<div class="button-content">
							<button class="btn btn-default" type="submit" id="saveblock"><?php echo (isset($site_language['Save'])) ? $site_language['Save'] : 'Save';?></button>
						</div>
					</div><!-- /.box-footer -->
				</form>
			</div>
		</div>
	</div>
</div><!-- /.content-wrapper -->
<!-- Add the sidebar's background. This div must be placed immediately after the control sidebar -->
<div class="control-sidebar-bg"></div>
</div><!-- ./wrapper -->
</body>