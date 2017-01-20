<?php defined('SYSPATH') OR die('No direct access allowed.');?>
    
	<!--- Top nav && left nav--->
	<?php echo $topnav.$leftnav;?>
	<div id="page-wrapper">
	<!--- Top nav && left nav --->
      <!-- Content Wrapper. Contains page content -->
      <div class="container-fluid">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1 class="page-header"> "<?php echo $site_name; ?>" - <?php echo (isset($site_language['Slider Create'])) ? $site_language['Slider Create'] : 'Slider Create'; ?></h1>
          <ol class="breadcrumb">
            <li><a href="<?php echo URL::site('admin/dashboard'); ?>"><i class="fa fa-dashboard"></i> <?php echo (isset($site_language['Dashboard'])) ? $site_language['Dashboard'] : 'Dashboard'; ?></a></li>
            <li><a href="<?php echo URL::site('admin/dashboard'); ?>"><?php echo (isset($site_language['Content Management'])) ? $site_language['Content Management'] : 'Content Management'; ?></a></li>
			<li><a href="<?php echo URL::site('admin/dashboard');?>"><?php echo (isset($site_language['Promo Page'])) ? $site_language['Promo Page'] : 'Promo Page'; ?></a></li>
			<li><a href="<?php echo URL::site('admin/dashboard');?>"><?php echo (isset($site_language['Slider Settings'])) ? $site_language['Slider Settings'] : 'Slider Settings'; ?></a></li>
			<li class="active"><i class="fa fa-edit"></i> <?php echo (isset($site_language['Create Slider'])) ? $site_language['Create Slider'] : 'Create Slider'; ?></li>
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
						  <label class="col-sm-3 control-label" for="s_title"><?php echo (isset($site_language['Slider'])) ? $site_language['Slider'] : 'Slider'; ?> <?php echo (isset($site_language['Title'])) ? $site_language['Title'] : 'Title'; ?></label>
						  <div class="col-sm-5">
							<input type="text" placeholder="<?php echo (isset($site_language['Slider'])) ? $site_language['Slider'] : 'Slider'; ?> <?php echo (isset($site_language['Title'])) ? $site_language['Title'] : 'Title'; ?>" required="true" class="form-control" name="s_title" value="<?php echo (isset($SliderDetails->s_title) ? $SliderDetails->s_title : '');?>" >
						  </div>
						</div>
						
						
						
		<div class="form-group">
						  <label class="col-sm-3 control-label" for="s_title"><?php echo (isset($site_language['Slider'])) ? $site_language['Slider'] : 'Slider'; ?> <?php echo (isset($site_language['Content'])) ? $site_language['Content'] : 'Content'; ?></label>
						  <div class="col-sm-5">
							<input type="text" placeholder="<?php echo (isset($site_language['Slider'])) ? $site_language['Slider'] : 'Slider'; ?> <?php echo (isset($site_language['Content'])) ? $site_language['Content'] : 'Content'; ?>" required="true" class="form-control" name="s_content" value="<?php echo (isset($SliderDetails->s_content) ? $SliderDetails->s_content : '');?>" >
						  </div>
						</div>				
						
						
						<div class="form-group">
						  <label class="col-sm-3 control-label" for="s_url"><?php echo (isset($site_language['Slider'])) ? $site_language['Slider'] : 'Slider'; ?> <?php echo (isset($site_language['Target Url'])) ? $site_language['Target Url'] : 'Target Url'; ?></label>
						  <div class="col-sm-5">
							<input type="text" placeholder="<?php echo (isset($site_language['Slider'])) ? $site_language['Slider'] : 'Slider'; ?> <?php echo (isset($site_language['Target Url'])) ? $site_language['Target Url'] : 'Target Url'; ?>" required="true" class="form-control" name="s_url" value="<?php echo (isset($SliderDetails->s_url) ? $SliderDetails->s_url : 'http://');?>" >
						  </div>
						</div>
						
<?php echo HTML::script('assets/js/jscolor.js'); ?>
<!--Color: <input class="jscolor" value="ab2567">	-->	
					

						
						<div class="form-group">
						  <label class="col-sm-3 control-label" for="tile_color"><?php echo (isset($site_language['Slider'])) ? $site_language['Slider'] : 'Slider'; ?> <?php echo (isset($site_language['Title'])) ? $site_language['Title'] : 'Title'; ?> <?php echo (isset($site_language['Color'])) ? $site_language['Color'] : 'Color'; ?> </label>
						  <div class="col-sm-5">
							<input type="text" placeholder="<?php echo (isset($site_language['Slider'])) ? $site_language['Slider'] : 'Slider'; ?> <?php echo (isset($site_language['Title'])) ? $site_language['Title'] : 'Title'; ?> <?php echo (isset($site_language['Color'])) ? $site_language['Color'] : 'Color'; ?>"  name="tile_color" value="<?php echo (isset($SliderDetails->tile_color) ? $SliderDetails->tile_color : '005a6e');?>"class="jscolor form-control" >
						  </div>
						</div>
						
						<div class="form-group">
						  <label class="col-sm-3 control-label" for="conten_color"><?php echo (isset($site_language['Slider'])) ? $site_language['Slider'] : 'Slider'; ?> <?php echo (isset($site_language['Content'])) ? $site_language['Content'] : 'Content'; ?> <?php echo (isset($site_language['Color'])) ? $site_language['Color'] : 'Color'; ?></label>
						  <div class="col-sm-5">
							<input type="text" placeholder="<?php echo (isset($site_language['Slider'])) ? $site_language['Slider'] : 'Slider'; ?> <?php echo (isset($site_language['Content'])) ? $site_language['Content'] : 'Content'; ?> <?php echo (isset($site_language['Color'])) ? $site_language['Color'] : 'Color'; ?>" name="content_color" value="<?php echo (isset($SliderDetails->content_color) ? $SliderDetails->content_color : '005a6e');?>" class="jscolor form-control"  >
						  </div>
						</div>
						
						
						
						
		<div class="form-group">
						  <label class="col-sm-3 control-label" for="conten_color"><?php echo (isset($site_language['Slider'])) ? $site_language['Slider'] : 'Slider'; ?> <?php echo (isset($site_language['Content'])) ? $site_language['Content'] : 'Content'; ?> <?php echo (isset($site_language['Background'])) ? $site_language['Background'] : 'Background'; ?> <?php echo (isset($site_language['Color'])) ? $site_language['Color'] : 'Color'; ?></label>
						  <div class="col-sm-5">
							<input type="text" placeholder="<?php echo (isset($site_language['Slider'])) ? $site_language['Slider'] : 'Slider'; ?> <?php echo (isset($site_language['Content'])) ? $site_language['Content'] : 'Content'; ?> <?php echo (isset($site_language['Background'])) ? $site_language['Background'] : 'Background'; ?> <?php echo (isset($site_language['Color'])) ? $site_language['Color'] : 'Color'; ?>" class="form-control demo" data-horizontal="true" id="demo_forceformat" name="content_bgcolor" value="<?php echo (isset($SliderDetails->content_bgcolor) ? $SliderDetails->content_bgcolor : '');?>" >
						  </div>
						</div>				
						
						<div class="form-group">
						  <label class="col-sm-3 control-label" ><?php echo (isset($site_language['Slider'])) ? $site_language['Slider'] : 'Slider'; ?> <?php echo (isset($site_language['Content'])) ? $site_language['Content'] : 'Content'; ?> <?php echo (isset($site_language['Border'])) ? $site_language['Border'] : 'Border'; ?> </label>
						  <div class="col-sm-5">
							<input type="text"  placeholder="<?php echo (isset($site_language['Slider'])) ? $site_language['Slider'] : 'Slider'; ?> <?php echo (isset($site_language['Content'])) ? $site_language['Content'] : 'Content'; ?> <?php echo (isset($site_language['Border'])) ? $site_language['Border'] : 'Border'; ?> "class="form-control" name="content_border" value="<?php echo (isset($SliderDetails->content_border) ? $SliderDetails->content_border : '1px 2px #000000');?>"  >
							
						  </div>
						</div>
															
		<div class="form-group">
						  <label class="col-sm-3 control-label" ><?php echo (isset($site_language['Slider'])) ? $site_language['Slider'] : 'Slider'; ?> <?php echo (isset($site_language['Text Shadow'])) ? $site_language['Text Shadow'] : 'Text Shadow'; ?> </label>
						  <div class="col-sm-5">
							<input type="text" placeholder="<?php echo (isset($site_language['Slider'])) ? $site_language['Slider'] : 'Slider'; ?> <?php echo (isset($site_language['Text Shadow'])) ? $site_language['Text Shadow'] : 'Text Shadow'; ?> "   class="form-control demo colorpicker-element"  name="text_shadow" value="<?php echo (isset($SliderDetails->text_shadow) ? $SliderDetails->text_shadow : '1px 2px #000000');?>"  >
							
						  </div>
						</div>
												
							<script>
	function setTextColor(picker) {
		document.getElementsByTagName('body')[0].style.color = '#' + picker.toString()
	}
	</script>
					
					
					
					
					
					
					
					
					
						
						<div class="form-group">
						  <label class="col-sm-3 control-label" for=photo"><?php echo (isset($site_language['Slider'])) ? $site_language['Slider'] : 'Slider'; ?> <?php echo (isset($site_language['Image'])) ? $site_language['Image'] : 'Image'; ?></label>
						  <div class="col-sm-4">
							<input type="file" id="photo"  name="sliderphoto">
							<p class="help-block">(Allowed image types are jpg, jpeg, bmp, png, gif.)</p>
						  </div>
						  <?php if(!empty($SliderDetails->s_image)){
								$sliderImage = 'assets/uploads/manage/homepage/slider/'.$SliderDetails->s_image;
						  ?>
							  <div class="col-sm-3">
								<img alt="<?php echo (isset($SliderDetails->s_title) ? $SliderDetails->s_title : '');?>" src="<?php echo URL::base().(file_exists($sliderImage) ?  $sliderImage : 'assets/images/no-images.jpg');?>" class="img-responsive img-square" width="100px">
								<input type="hidden" name="hidden-sliderid" value="<?php echo (isset($SliderDetails->id) ? base64_encode($SliderDetails->id) : '');?>">
							  </div>
						  <?php } ?>
						</div>
						<div class="form-group">
							<label class="col-sm-3 col-xs-3 control-label" for="status"><?php echo (isset($site_language['Status'])) ? $site_language['Status'] : 'Status'; ?></label>
							<div class="radio col-sm-3 col-lg-1 col-xs-3">
								<label><input type="radio" <?php echo (!isset($SliderDetails->is_active) || (isset($SliderDetails->is_active) && $SliderDetails->is_active=='1') ? 'checked=""' : '');?> value="1" name="status">Active</label>
							</div>
							<div class="radio col-sm-3 col-lg-1 col-xs-3">
								<label><input type="radio" <?php echo ((isset($SliderDetails->is_active) && $SliderDetails->is_active=='0') ? 'checked=""' : '');?> value="0" name="status">Inactive</label>
							</div>
						</div>
						<div class="box-footer">
							<div class="col-sm-3">&nbsp;</div>
							<button class="btn btn-default " type="submit" id="saveslider" style="margin-right:20px;"><?php echo (isset($site_language['Save'])) ? $site_language['Save'] : 'Save'; ?></button>
						</div><!-- /.box-footer -->
					 </div><!-- /.box-body -->
				  </div>
				</div>
			  </div>
			</div>
		  </div>
        </section><!-- /.content -->
		</form>
      </div><!-- /.content-wrapper -->
     
    </div><!-- ./wrapper -->
