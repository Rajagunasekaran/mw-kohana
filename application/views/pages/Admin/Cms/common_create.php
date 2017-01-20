	<!--- Top nav && left nav--->
	<?php echo $topnav.$leftnav;?>
	<!--- Top nav && left nav --->
	<!-- Content Wrapper. Contains page content -->
	<?php 
	$page_id = $page_title = $page_slug = $page_content = $status = '';
	if(isset($page_details) && count($page_details)>0) { 
		$page_id		= $page_details[0]['page_id'];
		$page_title 	= $page_details[0]['page_title'];
		$page_slug 		= $page_details[0]['page_slug'];
		$page_content	= $page_details[0]['page_content'];
		$status 		= $page_details[0]['status'];
	} 
	$statusArray = Helper_Common::emailTemplateStatusArray();
	?>
	<div id="page-wrapper">
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-12">
					<h1 class="page-header"><?php  echo "\"".ucfirst($site_name)."\" "; if($page_id!='') { echo (isset($site_language['Edit'])) ? $site_language['Edit'] : 'Edit'; } else { echo (isset($site_language['Create'])) ? $site_language['Create'] : 'Create'; } ?> <?php echo (isset($site_language['Page'])) ? $site_language['Page'] : 'Page'; ?></h1>
					<ol class="breadcrumb">
						<li>
							<i class="fa fa-dashboard"></i>  <a href="<?php echo URL::base().'admin/dashboard'; ?>"><?php echo (isset($site_language['Dashboard'])) ? $site_language['Dashboard'] : 'Dashboard'; ?></a>
						</li>
						<li class="active">
							<i class="fa fa-edit"></i> <?php if($page_id!='') { echo (isset($site_language['Edit'])) ? $site_language['Edit'] : 'Edit'; } else { echo (isset($site_language['Create'])) ? $site_language['Create'] : 'Create'; } ?> <?php echo (isset($site_language['Page'])) ? $site_language['Page'] : 'Page'; ?>
						</li>
					</ol>
				</div>
			</div>
			<?php if(isset($errors) && count($errors)>0) { 
			$labelArray = array(
									'page_title'	=> 'Title',
									'page_slug'		=> 'Slug',
									'page_content'	=> 'Content'
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
			<div class="row">
				<div class="col-lg-12">
					 <form method="post" role="form" action="">
                            <div class="form-group">
                                <label><?php echo (isset($site_language['Title'])) ? $site_language['Title'] : 'Title'; ?></label>
                                <input type="text" name="page_title" class="form-control" value="<?php echo $page_title;?>" onblur="getSlugVal(this.value);">
                            </div>
							<div class="form-group">
                                <label><?php echo (isset($site_language['Slug'])) ? $site_language['Slug'] : 'Slug'; ?></label>
                                <input type="text" name="page_slug" id="page_slug" class="form-control" value="<?php echo $page_slug;?>">
                            </div>
							<div class="form-group">
                                <label><?php echo (isset($site_language['Content'])) ? $site_language['Content'] : 'Content'; ?></label>
										  <textarea id="page_content" name='page_content'><?php echo (isset($page_content))?$page_content:''; ?></textarea>
                               <?php //echo $editor->editor('page_content',$page_content); ?>
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
							<button class="btn btn-default" name="submit" type="submit"><?php echo (isset($site_language['Save'])) ? $site_language['Save'] : 'Save'; ?></button>
							<a class="btn btn-default" href="<?php echo URL::base().'admin/cms/common_pagelist/'.$site_id;?>"><?php echo (isset($site_language['Page'])) ? $site_language['Page'] : 'Page'; ?> <?php echo (isset($site_language['List'])) ? $site_language['List'] : 'List'; ?></a>
							<input type="hidden" name="page_id" value="<?php echo $page_id;?>" />
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
	function getSlugVal(title) {
		title = title.split(' ').join('-');
		title = title.toLowerCase(); 
		$('#page_slug').val(title);
	}
	</script>
</body>

