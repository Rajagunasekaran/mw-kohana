<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>

	<!--- Top nav && left nav--->
	<?php echo $topnav.$leftnav;?>
	<div id="page-wrapper">
	<!--- Top nav && left nav --->
      <!-- Content Wrapper. Contains page content -->
      <div class="container-fluid">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1 class="page-header">"<?php echo ucfirst($site_name);?>" -  <?php echo (isset($site_language['Slide'])) ? $site_language['Slide'] : 'Slide'; ?> <?php echo (isset($site_language['Details'])) ? $site_language['Details'] : 'Details'; ?></h1>
          <ol class="breadcrumb">
            <li><a href="<?php echo URL::site('admin/dashboard'); ?>"><i class="fa fa-dashboard"></i> <?php echo (isset($site_language['Dashboard'])) ? $site_language['Dashboard'] : 'Dashboard'; ?></a></li>
            <li><a href="<?php echo URL::site('admin/dashboard'); ?>"><?php echo (isset($site_language['Content Management'])) ? $site_language['Content Management'] : 'Content Management'; ?></a></li>
			<li><a href="<?php echo URL::site('admin/dashboard');?>"><?php echo (isset($site_language['Promo Page'])) ? $site_language['Promo Page'] : 'Promo Page'; ?></a></li>
			<li><a href="<?php echo URL::site('admin/dashboard');?>"><?php echo (isset($site_language['Slider Settings'])) ? $site_language['Slider Settings'] : 'Slider Settings'; ?></a></li>
            <li class="active"><?php echo (isset($site_language['Browse Slider'])) ? $site_language['Browse Slider'] : 'Browse Slider'; ?></li>
          </ol>
        </section>

        <!-- Main content -->
		<form action="" method="post">
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
               <div class="box-header">
				  <div>
						<div>
							<div class="createbtn">
								<a class="btn btn-default" style="margin-bottom:10px;" href="<?php echo URL::site('admin/sites/slidercreate/'.$site_id);?>"><i class="fa fa-plus-square"></i> <?php echo (isset($site_language['Create Slider'])) ? $site_language['Create Slider'] : 'Create Slider'; ?></a>
							</div>
						</div>
				  </div>
                </div><!-- /.box-header -->
				<?php $session = Session::instance();
					if ($session->get('flash_success')): ?>
				   <div class="banner alert alert-success">
					<i class="fa fa-check"></i><span><?php echo $session->get_once('flash_success') ?></span>
				  </div>
				<?php endif ?>
                <div class="box-body">
                  <table class="table table-bordered table-striped" id="reports">
                    <thead>
                      <tr>
                        <th><?php echo (isset($site_language['ID'])) ? $site_language['ID'] : 'ID'; ?></th>
						<th><?php echo (isset($site_language['Title'])) ? $site_language['Title'] : 'Title'; ?></th>
						<th><?php echo (isset($site_language['Target Url'])) ? $site_language['Target Url'] : 'Target Url'; ?></th>
						<th><?php echo (isset($site_language['Image'])) ? $site_language['Image'] : 'Image'; ?></th>
                        <th><?php echo (isset($site_language['Active'])) ? $site_language['Active'] : 'Active'; ?></th>
                        <th><?php echo (isset($site_language['Action'])) ? $site_language['Action'] : 'Action'; ?></th>
					  </tr>
                    </thead>
                    <tbody>
					 <?php if(isset($Sliderlist) && !empty($Sliderlist)){ 
								foreach($Sliderlist as $keys => $values){ 
					 ?>
							  <tr id="row-<?php echo $values->id; ?>">
								<td><?php echo $values->id;?></td>
								<td><?php echo $values->s_title;?></td>
								<td><?php echo $values->s_url;?></td>
								<td>
									<?php
									if(!empty($values->s_image)){
									
									 $sliderImage = 'assets/uploads/manage/homepage/slider/'.$values->s_image;?>
									<img alt="<?php echo $values->s_title ;?>" src="<?php echo URL::base().(file_exists($sliderImage) ?  $sliderImage : 'assets/images/no-images.jpg');?>" class="img-responsive img-squre" width="100px">
									<?php }?>
								</td>
								<td>
									<img src="<?php echo URL::base().'assets/images/'.(!empty($values->is_active) ? 'accept.png' : 'cancel.png');?>" class="user-image" alt="<?php echo (!empty($values->is_active) ? 'In active' : 'Active');?>">
								</td>
								<td>
									<select  class="form-control selectAction" onchange="sliderAction(this.value,'<?php echo $values->id;?>');">
										<option value=""><?php echo (isset($site_language['Action'])) ? $site_language['Action'] : 'Action'; ?></option>
										<option value="edit">Edit Slider</option>
										<option value="delete">Delete Slider</option>
									</select>
								</td>
							  </tr>
					<?php 	}
						  }
					?>
					
                    </tbody>
                  </table>
				  <a style="display:none" class="delete_slider">&nbsp;</a>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
		</form>
      </div><!-- /.content-wrapper -->
     
      <!-- Add the sidebar's background. This div must be placed
           immediately after the control sidebar -->
    </div><!-- ./wrapper -->

<style>
.dataTables_length {
	margin: 10px 0;
}
</style>
<script type="text/javascript">
	function sliderAction(optVal,tempId){
		if(optVal!='') {
			if(optVal=='edit') {
				location.href = "<?php echo URL::site('admin/sites/slider_edit').'/';?>"+tempId;
			} else if(optVal=='delete') {
				$('.delete_slider').attr('id','');
				$('.delete_slider').attr('id',tempId);
				$('.delete_slider').click();
			}
		$(".selectAction").select2("val", "");
		}
	}
</script>
	