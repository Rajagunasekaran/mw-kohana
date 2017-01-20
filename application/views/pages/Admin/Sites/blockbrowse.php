<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
	<!--- Top nav && left nav--->
	<?php echo $topnav.$leftnav;?>
	<!--- Top nav && left nav --->
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper" id="page-wrapper">
	  <div class="container-fluid">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1 class="page-header"> "<?php echo ucfirst($site_name);?>"  <?php echo (isset($site_language['Block'])) ? $site_language['Block'] : 'Block'; ?> <?php echo (isset($site_language['Details'])) ? $site_language['Details'] : 'Details'; ?></h1>
          <ol class="breadcrumb">
            <li><a href="<?php echo URL::site('admin/dashboard'); ?>"><i class="fa fa-dashboard"></i> <?php echo (isset($site_language['Home'])) ? $site_language['Home'] : 'Home'; ?></a></li>
            <li><a href="<?php echo URL::site('admin/site/');?>"><?php echo (isset($site_language['Sites'])) ? $site_language['Sites'] : 'Sites'; ?></a></li>
            
			<li class="active"><?php echo (isset($site_language['Browse Block'])) ? $site_language['Browse Block'] : 'Browse Block'; ?></li>
          </ol>
        </section>

        <!-- Main content -->
		<form action="<?php echo URL::site(Request::current()->uri()); ?>" method="post">
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
				  <div>
						<div>
							<div class="createbtn">
								<a class="btn btn-default" style="margin-bottom:10px;" href="<?php echo URL::site('admin/sites/blockcreate/'.$site_id);?>"><i class="fa fa-plus-square"></i> <?php echo (isset($site_language['Create Block'])) ? $site_language['Create Block'] : 'Create Block'; ?></a>
							</div>
						</div>
						<div>
						</div>
				  </div>
                </div><!-- /.box-header -->
				<?php $session = Session::instance();
					if ($session->get('flash_success')): ?>
				  <div class="banner alert alert-success">
					<i class="fa fa-check"></i><span> <?php echo $session->get_once('flash_success') ?></span>
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
						<th><?php echo (isset($site_language['Description'])) ? $site_language['Description'] : 'Description'; ?></th>
                        <th><?php echo (isset($site_language['Active'])) ? $site_language['Active'] : 'Active'; ?></th>
                        <th><?php echo (isset($site_language['Action'])) ? $site_language['Action'] : 'Action'; ?></th>
                      </tr>
                    </thead>
                    <tbody>
					 <?php if(isset($BlockDetails) && !empty($BlockDetails)){ 
								foreach($BlockDetails as $keys => $values){ 
					 ?>
							  <tr id="row-<?php echo $values->id; ?>">
								<td><?php echo $values->id;?></td>
								<td><?php echo $values->b_title ;?></td>
								<td><?php echo $values->b_url;?></td>
								<td>
									<?php if(!empty($values->b_image)){ $sliderImage = 'assets/uploads/manage/homepage/block/'.$values->b_image;?>
									<img alt="<?php echo $values->b_title ;?>" src="<?php echo URL::base().(file_exists($sliderImage) ?  $sliderImage : 'assets/images/no-images.jpg');?>" class="img-responsive img-squre" width="50px">
									<?php }?>
								</td>
								<td><?php echo $values->b_description;?></td>
								<td>
									<img src="<?php echo URL::base().'assets/images/'.(!empty($values->is_active) ? 'accept.png' : 'cancel.png');?>" class="user-image" alt="<?php echo (!empty($values->is_active) ? 'In active' : 'Active');?>">
								</td>
								<td>
									<select  class="form-control selectAction" onchange="blockAction(this.value,'<?php echo $values->id;?>');">
										<option value=""><?php echo (isset($site_language['Action'])) ? $site_language['Action'] : 'Action'; ?></option>
										<option value="edit">Edit Block</option>
										<option value="delete">Delete Block</option>
									</select>
								</td>
							  </tr>
					<?php 	}
						  }
					?>
                    </tbody>
                  </table>
				  <a style="display:none" class="delete_block">&nbsp;</a>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
		</form>
		</div>
      </div><!-- /.content-wrapper -->
      
      
	<script type="text/javascript">
	function blockAction(optVal,tempId){
		if(optVal!='') {
			if(optVal=='edit') {
				location.href = "<?php echo URL::site('admin/sites/blockedit').'/';?>"+tempId;
				$(".selectAction").select2("val", "");
			} else if(optVal=='delete') {
				$('.delete_block').attr('id','');
				$('.delete_block').attr('id',tempId);
				$('.delete_block').click();
				$(".selectAction").select2("val", "");
			}
		}
	}
</script>

