<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
	<!--- Top nav && left nav--->
	<?php echo $topnav.$leftnav;?>
	<!--- Top nav && left nav --->
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper" id="page-wrapper">
        <div class="container-fluid">
		<!-- Content Header (Page header) -->
        <section class="content-header">
          <h1 class="page-header"> "<?php echo ucfirst($site_name);?>" <?php echo (isset($site_language['HomePage Partner Create'])) ? $site_language['HomePage Partner Create'] : 'HomePage Partner Create';?></h1>
          <ol class="breadcrumb">
            <li><a href="<?php echo URL::site('admin/dashboard'); ?>"><i class="fa fa-dashboard"></i> <?php echo (isset($site_language['Home'])) ? $site_language['Home'] : 'Home';?></a></li>
			 <li> <a href="<?php echo URL::site('admin/site/');?>"><?php echo (isset($site_language['Sites'])) ? $site_language['Sites'] : 'Sites';?></a></li>
			 
			 <li> <a href="<?php echo URL::site('admin/site/');?>"><?php echo ucfirst($site_name);?></a></li>
            <li><a href="<?php echo URL::site('admin/site/partnerbrowse/'.base64_encode($site_id));?>"> <?php echo (isset($site_language['Browse partner'])) ? $site_language['Browse partner'] : 'Browse partner';?></a></li>
           
			<li class="active"><?php echo (isset($site_language['Create partner'])) ? $site_language['Create partner'] : 'Create partner';?></li>
          </ol>
        </section>

        <!-- Main content -->
		<form action="<?php echo URL::site(Request::current()->uri()); ?>" method="post" class="form-horizontal" enctype="multipart/form-data">
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
					  <div class="box-body">
						<div id="errors" class="form-group">
							<?php if (isset($error_messages) && count($error_messages)>0): ?>
							<div class="banner alert alert-danger">
									<?php foreach ($error_messages as $error_message): ?>
									<i class="fa fa-exclamation-triangle"></i><span><?php echo $error_message; ?></span>
									<?php endforeach ?>
								
							</div>
							<?php endif; ?>
						</div>
						<div class="form-group">
						  <label class="col-sm-3 control-label" for="p_title"><?php echo (isset($site_language['Partner'])) ? $site_language['Partner'] : 'Partner';?> <?php echo (isset($site_language['Title'])) ? $site_language['Title'] : 'Title';?></label>
						  <div class="col-sm-5">
							<input type="text" placeholder="Partner Title" required="true" class="form-control" name="p_title" value="<?php echo (isset($PartnerDetails->p_title) ? $PartnerDetails->p_title : '');?>" >
						  </div>
						</div>
						<div class="form-group">
						  <label class="col-sm-3 control-label" for="p_url"><?php echo (isset($site_language['Partner'])) ? $site_language['Partner'] : 'Partner';?> <?php echo (isset($site_language['Target Url'])) ? $site_language['Target Url'] : 'Target Url';?></label>
						  <div class="col-sm-5">
							<input type="text" placeholder="Partner Target Url" required="true" class="form-control" name="p_url" value="<?php echo (isset($PartnerDetails->p_url) ? $PartnerDetails->p_url : 'http://');?>" >
						  </div>
						</div>
						<div class="form-group">
						  <label class="col-sm-3 control-label" for=photo"><?php echo (isset($site_language['Partner'])) ? $site_language['Partner'] : 'Partner';?> <?php echo (isset($site_language['Image'])) ? $site_language['Image'] : 'Image';?></label>
						  <div class="col-sm-4">
							<input type="file" id="photo"  name="partnerphoto">
							<p class="help-block">(Allowed image types are jpg, jpeg, bmp, png, gif.)</p>
						  </div>
						  <?php if(!empty($PartnerDetails->p_image)){
								$partnerImage = 'assets/uploads/manage/homepage/partner/'.$PartnerDetails->p_image;
						  ?>
							  <div class="col-sm-3">
								<img alt="<?php echo (isset($PartnerDetails->b_title) ? $PartnerDetails->b_title : '');?>" src="<?php echo URL::base().(file_exists($partnerImage) ?  $partnerImage : 'assets/images/no-images.jpg');?>" class="img-responsive img-square" width="100px">
								<input type="hidden" name="hidden-partnerid" value="<?php echo (isset($PartnerDetails->id) ? base64_encode($PartnerDetails->id) : '');?>">
							  </div>
						  <?php } ?>
						</div>
						<div class="form-group">
							<label class="col-sm-3 col-xs-3 control-label" for="status"><?php echo (isset($site_language['Status'])) ? $site_language['Status'] : 'Status';?>	</label>
							<div class="radio col-sm-3 col-lg-1 col-xs-3">
								<label><input type="radio" <?php echo (!isset($PartnerDetails->is_active) || (isset($PartnerDetails->is_active) && $PartnerDetails->is_active=='1') ? 'checked=""' : '');?> value="1" name="status">Active</label>
							</div>
							<div class="radio col-sm-3 col-lg-1 col-xs-3">
								<label><input type="radio" <?php echo ((isset($PartnerDetails->is_active) && $PartnerDetails->is_active=='0') ? 'checked=""' : '');?> value="0" name="status">Inactive</label>
							</div>
						</div>
						<div class="box-footer">
							<div class="col-sm-3">&nbsp;</div>
							<button class="btn btn-default" type="submit" id="savepartner" style="margin-right:20px;"><?php echo (isset($site_language['Save'])) ? $site_language['Save'] : 'Save'; ?></button>
						</div><!-- /.box-footer -->
					 </div><!-- /.box-body -->
				  </div>
				</div>
			  </div>
			</div>
		  </div>
        </section><!-- /.content -->
		</form>
		</div>
      </div><!-- /.content-wrapper -->
     
      <!-- Add the sidebar's background. This div must be placed
           immediately after the control sidebar -->
      <div class="control-sidebar-bg"></div>
    </div><!-- ./wrapper -->

