<?php defined('SYSPATH') OR die('No direct access allowed.');
//echo '<pre>';print_r($BlockDetails);echo '</pre>';
 ?>
<body class="hold-transition skin-blue sidebar-mini">
    <style type="text/css">
		.jscolor{float: left;margin-right: 5px;width: 75%;}
	</style>
	<div class="wrapper">
	<!--- Top nav && left nav--->
	<?php echo $topnav.$leftnav;?>
	<!--- Top nav && left nav --->
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper" id="page-wrapper">
        <div class="container-fluid">
		<!-- Content Header (Page header) -->
        <section class="content-header">
          <h1 class="page-header">"<?php echo ucfirst($site_name);?>" <?php echo (isset($site_language['Advanced  CSS'])) ? $site_language['Advanced  CSS'] : 'Advanced  CSS';?></h1>
          <ol class="breadcrumb">
            <li><a href="<?php echo URL::site('admin/dashboard'); ?>"><i class="fa fa-dashboard"></i> <?php echo (isset($site_language['Home'])) ? $site_language['Home'] : 'Home';?></a></li>
            <li> <a href="<?php echo URL::site('admin/site'); ?>"><?php echo (isset($site_language['Sites'])) ? $site_language['Sites'] : 'Sites';?></a></li>
           <li> <a href="<?php echo URL::site('admin/site/');?>"><?php echo ucfirst($site_name);?></a></li>
			 <li>   <?php echo (isset($site_language['Advanced  CSS'])) ? $site_language['Advanced  CSS'] : 'Advanced  CSS';?></li>
			
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
						<!-- <div class="form-group">
							<label class="col-sm-3 control-label" for="">Background Color</label>
							<div class="col-sm-9">
								<input type="text" value="<?php //echo (isset($BlockDetails->bg_color) && $BlockDetails->bg_color!='') ? $BlockDetails->bg_color : '1b9af7';?>"  class="form-control jscolor" id="bg_color" name="bg_color">
								<button type="button" class="btn btn-default" onclick="resetVal(this);">Reset</button>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label" for="">Font Color</label>
							<div class="col-sm-9">
								<input type="text" value="<?php //echo (isset($BlockDetails->font_color) && $BlockDetails->font_color!='')  ? $BlockDetails->font_color : '333';?>" class="form-control jscolor" id="font_color" name="font_color">
								<button type="button" class="btn btn-default" onclick="resetVal(this);">Reset</button>
							</div>
						</div> -->
						<div class="form-group">
						  <label class="col-sm-3 control-label" for="t_description"><?php echo (isset($site_language['CSS'])) ? $site_language['CSS'] : 'CSS';?></label>
						  <div class="col-sm-9">
							<textarea id="advanced_css" name="advanced_css" rows="10" cols="80"><?php echo (isset($BlockDetails->advanced_css) ? $BlockDetails->advanced_css : '');?></textarea>
						  </div>
						</div>
						<div class="box-footer">
							<div class="col-sm-3">&nbsp;</div>
							<button class="btn btn-default" type="submit" id="saveblock" style="margin-right:20px;"><?php echo (isset($site_language['Save'])) ? $site_language['Save'] : 'Save';?></button>
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
	
<script type="text/javascript">
function resetVal(ele) {
	var prevId = $(ele).prev().attr('id');
	if(prevId=='bg_color') {
		$(ele).prev().val('1b9af7');
		$(ele).prev().css('background-color','#1b9af7');
	} else {
		$(ele).prev().val('333');
		$(ele).prev().css('background-color','#333');
	}
}
</script>