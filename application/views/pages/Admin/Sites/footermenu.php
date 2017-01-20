<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<body class="hold-transition skin-blue sidebar-mini">
    <style type="text/css">
		.label-contnr label{text-align:center;}
		.icon-contnr i{color: #000;font-size: 30px;}
		.icon-contnr a {margin-right:5px;}
		@media(max-width:790px) {
			.icon-contnr i{font-size: 18px;}
			.icon-contnr a {margin-right:0;}
		}
	</style>
	<div class="wrapper">
	<!--- Top nav && left nav--->
	<?php echo $topnav.$leftnav;?>
	<!--- Top nav && left nav --->
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper" id="page-wrapper">
        <div class="container-fluid">
			<div class="row">
				<div class="col-lg-12">
					<!-- Content Header (Page header) -->
					<section class="content-header">
					  <h1 class="page-header">"<?php echo ucfirst($site_name);?>" <?php echo (isset($site_language['Footer Menu'])) ? $site_language['Footer Menu'] : 'Footer Menu';?></h1>
					  <ol class="breadcrumb">
						<li><a href="<?php echo URL::site('admin/dashboard'); ?>"><i class="fa fa-dashboard"></i>  <?php echo (isset($site_language['Home'])) ? $site_language['Home'] : 'Home';?></a></li>
						<li> <a href="<?php echo URL::site('admin/site'); ?>"><?php echo (isset($site_language['Sites'])) ? $site_language['Sites'] : 'Sites';?></a></li>
					   <li> <a href="<?php echo URL::site('admin/site/');?>"><?php echo ucfirst($site_name);?></a></li>
						 <li>    <?php echo (isset($site_language['Footer Menu'])) ? $site_language['Footer Menu'] : 'Footer Menu';?></li>
						
					  </ol>
					</section>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12">
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
										<div class="form-group label-contnr">
											<label class="col-sm-4 col-xs-4" for=""><?php echo (isset($site_language['Title'])) ? $site_language['Title'] : 'Title';?></label>
											<label class="col-sm-6 col-xs-6"><?php echo (isset($site_language['Url'])) ? $site_language['Url'] : 'Url';?></label>
										</div>
										<?php if(isset($footerMenu) && count($footerMenu)>0) { 
											$lastIndex = count($footerMenu);
											foreach($footerMenu as $key => $value) { 
												$rowIndex = $key+1;?>
												<div class="form-group" id="row-ele-<?php echo $rowIndex;?>">
													<div class="col-sm-4 col-xs-4"><input type="text" value="<?php echo $value['title'];?>" class="form-control"  name="title[]" required></div>
													<div class="col-sm-6 col-xs-6"><input type="text" value="<?php echo $value['url'];?>" class="form-control"  name="url[]" required></div>
													<div class="col-sm-2 col-xs-2 icon-contnr">
														<a href="javascript:void(0);" onclick="addRow('<?php echo $lastIndex;?>');"><i class="fa fa-plus-circle"></i></a>
														<a href="javascript:void(0);" onclick="removeRow('<?php echo $rowIndex;?>');"><i class="fa fa-minus-circle"></i></a>
													</div>
												</div>
											<?php }
										} else { ?>
											<div class="form-group" id="row-ele-1">
													<div class="col-sm-4"><input type="text" value="" class="form-control"  name="title[]" required></div>
													<div class="col-sm-6"><input type="text" value="" class="form-control"  name="url[]" required></div>
													<div class="col-sm-2 icon-contnr">
														<a href="javascript:void(0);" onclick="addRow('1');"><i class="fa fa-plus-circle"></i></a>
														<a href="javascript:void(0);" onclick="removeRow('1');"><i class="fa fa-minus-circle"></i></a>
													</div>
												</div>
										<?php } ?>
										<div class="form-group">
											<div class="col-sm-12">
												<button class="btn btn-default" type="submit" id="saveblock" style="margin-right:20px;">Save</button>
											</div>
										</div><!-- /.box-footer -->
										
									 </div><!-- /.box-body -->
								  </div>
								</div>
							  </div>
							</div>
						</section><!-- /.content -->
					</form>
				</div>
			</div>
		</div>
      </div><!-- /.content-wrapper -->
      
      <!-- Add the sidebar's background. This div must be placed
           immediately after the control sidebar -->
      <div class="control-sidebar-bg"></div>
    </div><!-- ./wrapper -->
