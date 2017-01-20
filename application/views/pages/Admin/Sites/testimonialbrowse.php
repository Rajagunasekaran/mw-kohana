<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>

    
	<!--- Top nav && left nav--->
	<?php echo $topnav.$leftnav;?>
	<!--- Top nav && left nav --->
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper" id="page-wrapper">
        <div class="container-fluid">
		<!-- Content Header (Page header) -->
        <section class="content-header">
          <h1 class="page-header"> "<?php echo ucfirst($site_name);?>" <?php echo (isset($site_language['Testimonial'])) ? $site_language['Testimonial'] : 'Testimonial';?> <?php echo (isset($site_language['Details'])) ? $site_language['Details'] : 'Details';?></h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> <?php echo (isset($site_language['Home'])) ? $site_language['Home'] : 'Home';?></a></li>
             <li><a href="<?php echo URL::site('admin/site/');?>"><?php echo (isset($site_language['Sites'])) ? $site_language['Sites'] : 'Sites';?></a></li>
            <li class="active"><?php echo (isset($site_language['Testimonial'])) ? $site_language['Testimonial'] : 'Testimonial';?> <?php echo (isset($site_language['Details'])) ? $site_language['Details'] : 'Details';?></li>
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
								<a class="btn btn-default" style="margin-bottom:10px;" href="<?php echo URL::site('admin/sites/testimonialcreate/'.$site_id);?>"><i class="fa fa-plus-square"></i> <?php echo (isset($site_language['Create Testimonial'])) ? $site_language['Create Testimonial'] : 'Create Testimonial';?></a>
							</div>
						</div>
						<div>
							
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
                        <th><?php echo (isset($site_language['ID'])) ? $site_language['ID'] : 'ID';?></th>
						<th><?php echo (isset($site_language['Title'])) ? $site_language['Title'] : 'Title';?></th>
						<th><?php echo (isset($site_language['Username'])) ? $site_language['Username'] : 'Username';?></th>
                        <th><?php echo (isset($site_language['Description'])) ? $site_language['Description'] : 'Description';?></th>
                        <th><?php echo (isset($site_language['Active'])) ? $site_language['Active'] : 'Active';?></th>
                        <th><?php echo (isset($site_language['Created Date'])) ? $site_language['Created Date'] : 'Created Date';?></th>
                        <th><?php echo (isset($site_language['Action'])) ? $site_language['Action'] : 'Action';?></th>
						
                      </tr>
                    </thead>
                    <tbody>
					 <?php if(isset($TestimonialDetails) && !empty($TestimonialDetails)){ 
								foreach($TestimonialDetails as $keys => $values){ 
					 ?>
							  <tr id="row-<?php echo $values->id; ?>">
								<td><?php echo $values->id;?></td>
								<td><?php echo $values->t_title ;?></td>
								<td><?php echo $values->t_user ;?></td>
								<td><?php echo $values->t_description;?></td>
								<td>
									<img src="<?php echo URL::base().'assets/images/'.(!empty($values->is_active) ? 'accept.png' : 'cancel.png');?>" class="user-image" alt="<?php echo (!empty($values->is_active) ? 'In active' : 'Active');?>">
								</td>
								<td><?php echo date('Y/m/d',strtotime($values->date_created));?></td>
								<td>
									<select  class="form-control selectAction" onchange="testimonialAction(this.value,'<?php echo $values->id;?>');">
										<option value=""><?php echo (isset($site_language['Action'])) ? $site_language['Action'] : 'Action';?></option>
										<option value="edit">Edit Testimonial</option>
										<option value="delete">Delete Testimonial</option>
									</select>
								</td>
							  </tr>
					<?php 	}
						  }
					?>
                    </tbody>
                  </table>
				   <a style="display:none" class="delete_testimonial">&nbsp;</a>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
		</form>
       </div>
	  </div><!-- /.content-wrapper -->
     
      <!-- Add the sidebar's background. This div must be placed
           immediately after the control sidebar -->
     



		<style>
		.dataTables_length {
    margin: 10px 0;
}
		</style>
	<script type="text/javascript">
	function testimonialAction(optVal,tempId){
		if(optVal!='') {
			if(optVal=='edit') {
				location.href = "<?php echo URL::site('admin/sites/testimonialedit').'/';?>"+tempId;
				$(".selectAction").select2("val", "");
			} else if(optVal=='delete') {
				$('.delete_testimonial').attr('id','');
				$('.delete_testimonial').attr('id',tempId);
				$('.delete_testimonial').click();
				$(".selectAction").select2("val", "");
			}
		}
	}
</script>