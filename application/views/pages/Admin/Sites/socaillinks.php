<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>

	<!--- Top nav && left nav--->
	<?php echo $topnav.$leftnav;?>
	<!--- Top nav && left nav --->
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper" id="page-wrapper">
        <div class="container-fluid">
		<!-- Content Header (Page header) -->
        <section class="content-header">
          <h1 class="page-header">"<?php echo ucfirst($site_name);?>" <?php echo (isset($site_language['Promo Page'])) ? $site_language['Promo Page'] : 'Promo Page';?> <?php echo (isset($site_language['Content'])) ? $site_language['Content'] : 'Content';?></h1>
          <ol class="breadcrumb">
            <li><a href="<?php echo URL::site('admin/dashboard'); ?>"><i class="fa fa-dashboard"></i> <?php echo __('Home');//echo (isset($site_language['Home'])) ? $site_language['Home'] : 'Home';?></a></li>
            <li> <a href="<?php echo URL::site('admin/sites'); ?>"><?php echo __('Sites');//echo (isset($site_language['Sites'])) ? $site_language['Sites'] : 'Sites';?></a></li>
           <li> <a href="<?php echo URL::site('admin/sites/');?>"><?php echo ucfirst($site_name);?></a></li>
			 <li>   <?php echo __('Social Links');//echo (isset($site_language['Homepage  Settings'])) ? $site_language['Homepage  Settings'] : 'Homepage  Settings';?></li>
			
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
					<?php endif ?>
				</div>
			</div>
		</div>	
		
        <!-- Main content -->
		
		<div class="row">
				<div class="col-lg-12">
		
		<form action="<?php //echo Request::current()->uri(); //URL::site(Request::current()->uri()); ?>" method="post"  enctype="multipart/form-data">			
			
			<div class="form-group">
              <label  for="site_name"><?php echo (isset($site_language['Social'])) ? $site_language['Social'] : 'Social';?> <?php echo (isset($site_language['Facebook'])) ? $site_language['Facebook'] : 'Facebook';?> <?php echo (isset($site_language['Link'])) ? $site_language['Link'] : 'Link';?>: *</label>
              
                <input type="site_name" placeholder="<?php echo (isset($site_language['Social'])) ? $site_language['Social'] : 'Social';?> <?php echo (isset($site_language['Facebook'])) ? $site_language['Facebook'] : 'Facebook';?> <?php echo (isset($site_language['Link'])) ? $site_language['Link'] : 'Link';?>" id="social_facebook_url" name="social_facebook_url" class="form-control" value="<?php echo (isset($BlockDetails->social_facebook_url) ? $BlockDetails->social_facebook_url : '');?>">
                <div class="radio">
					<label><input type="radio" name="statusfb" <?php echo ( (isset($BlockDetails->facebook_status) && $BlockDetails->facebook_status=='1') ? 'checked=""' : ''); ?>  value="1" required="true" >Active</label>
			  	</div>
			  	<div class="radio">
					<label><input type="radio" name="statusfb" <?php echo ((isset($BlockDetails->facebook_status) && $BlockDetails->facebook_status=='2') ? 'checked=""' : ''); ?> value="2" required="true" >Inactive</label>
			  	</div>
             
            </div>
			<div class="form-group">
              <label  for="site_name"><?php echo (isset($site_language['Social'])) ? $site_language['Social'] : 'Social';?> <?php echo (isset($site_language['Instagram'])) ? $site_language['Instagram'] : 'Instagram';?> <?php echo (isset($site_language['Link'])) ? $site_language['Link'] : 'Link';?>: *</label>
              
                <input type="site_name" placeholder="<?php echo (isset($site_language['Social'])) ? $site_language['Social'] : 'Social';?> <?php echo (isset($site_language['Instagram'])) ? $site_language['Instagram'] : 'Instagram';?> <?php echo (isset($site_language['Link'])) ? $site_language['Link'] : 'Link';?>" id="social_twitter_url" name="social_twitter_url" class="form-control" value="<?php echo (isset($BlockDetails->social_twitter_url) ? $BlockDetails->social_twitter_url : '');?>">
                <div class="radio ">
					<label><input type="radio" name="statusinsta" <?php echo ( (isset($BlockDetails->instagram_status) && $BlockDetails->instagram_status=='1') ? 'checked=""' : ''); ?> value="1" required="true" >Active</label>
			  	</div>
			  	<div class="radio ">
					<label><input type="radio" name="statusinsta" <?php echo ((isset($BlockDetails->instagram_status) && $BlockDetails->instagram_status=='2') ? 'checked=""' : ''); ?> value="2" required="true" >Inactive</label>
			  	</div>
              
            </div>				  
				  
				  
			<div class="form-group">
              <label for="site_name"><?php echo (isset($site_language['Social'])) ? $site_language['Social'] : 'Social';?> <?php echo (isset($site_language['Pinterest'])) ? $site_language['Pinterest'] : 'Pinterest';?> <?php echo (isset($site_language['Link'])) ? $site_language['Link'] : 'Link';?>: *</label>
             
                <input type="site_name" placeholder="<?php echo (isset($site_language['Social'])) ? $site_language['Social'] : 'Social';?> <?php echo (isset($site_language['Pinterest'])) ? $site_language['Pinterest'] : 'Pinterest';?> <?php echo (isset($site_language['Link'])) ? $site_language['Link'] : 'Link';?>" id="social_linkedin_url" name="social_linkedin_url" class="form-control" 
value="<?php echo (isset($BlockDetails->social_linkedin_url) ? $BlockDetails->social_linkedin_url : '');?>">
				<div class="radio ">
					<label><input type="radio" name="statuspinterest" <?php echo ((isset($BlockDetails->pinterest_status) && $BlockDetails->pinterest_status=='1') ? 'checked=""' : ''); ?> value="1" required="true" >Active</label>
			  	</div>
			  	<div class="radio">
					<label><input type="radio" name="statuspinterest" <?php echo ((isset($BlockDetails->pinterest_status) && $BlockDetails->pinterest_status=='2') ? 'checked=""' : ''); ?> value="2" required="true" >Inactive</label>
			  	</div>
              
            </div>	
						
						
			<div class="form-group">
              <label  for="site_name"><?php echo (isset($site_language['Footer'])) ? $site_language['Footer'] : 'Footer';?> <?php echo (isset($site_language['Content'])) ? $site_language['Content'] : 'Content';?>: *</label>
             
                <input type="site_name" required="true" placeholder="<?php echo (isset($site_language['Footer'])) ? $site_language['Footer'] : 'Footer';?> <?php echo (isset($site_language['Content'])) ? $site_language['Content'] : 'Content';?>" id="social_linkedin_url" name="footer_content" class="form-control" value="<?php echo (isset($BlockDetails->footer_content) ? $BlockDetails->footer_content : '');?>">
              
              </div>
						
			<div class="form-group">
				<div class="col-lg-12 toppadding10">
					<button class="btn btn-default" type="submit" id="saveblock" style="margin-right:20px;"><?php echo (isset($site_language['Save'])) ? $site_language['Save'] : 'Save';?></button>
				</div>
			</div><!-- /.box-footer -->
		</form>
	</div>

	</div>	
		
       </div>
	  </div><!-- /.content-wrapper -->
      
      <!-- Add the sidebar's background. This div must be placed
           immediately after the control sidebar -->
      <div class="control-sidebar-bg"></div>
    </div><!-- ./wrapper -->
</body>
