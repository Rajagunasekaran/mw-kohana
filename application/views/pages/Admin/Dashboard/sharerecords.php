<!--- Top nav && left nav--->
<?php echo $topnav.$leftnav;?>
<?php require_once(APPPATH.'views/templates/admin/usermodal.php');?>
<!--- Top nav && left nav --->
<!-- Content Wrapper. Contains page content -->
<div id="page-wrapper">
	<div class="container-fluid">
		<!-- Page Heading -->
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Trainer :<?php echo $trainer_stats_title;?></h1>
				<ol class="breadcrumb">
					<li><i class="fa fa-dashboard"></i>  <a href="<?php echo URL::base().'admin/index'; ?>">Dashboard</a></li>
					<li class="active"><i class="fa fa-edit"></i> Trainer Shared Records</li>
				</ol>
         </div>
		</div>
      <!-- /.row -->
<?php /*********************************************** Edit Section **************************************************/ ?>
<input type='hidden' id='trainer_check' class='trainer_check' value='<?php echo (Helper_Common::is_trainer() || Helper_Common::is_manager() || Helper_Common::is_admin())?true:false; ?>'>	
<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title"><i class="fa fa-share-alt fa-fw"></i> <?php echo (isset($site_language['Shared Records'])) ? $site_language['Shared Records'] : 'Shared Records'; ?>
			<button class='btn btn-primary sh_filter_btn'   data-toggle="collapse" data-target="#sh_filter" >Options</button></h3>
			
		</div>
		<div class="panel-body">
			<div class="row padding5 collapse in"  id='sh_filter' style='border: 0px solid red;background-color:#F5F5F5;margin-top:-25px  ' >
				<div class='col-md-2 feedfilter'>
					<select placeholder="Choose Status" name="sh_bythis" id="sh_bythis" class="form-control amoteactions" onchange='updateSharerecord()' >
						<option value="">Most Recent</option>
						<option value="1">Today</option>
						<option value="2" <?php echo ($param==1)?"selected='selected'":'';?> >This Week</option>
						<option value="3" <?php echo ($param==2)?"selected='selected'":'';?>>This Month</option>
						<option value="5">This Fortnight</option>
						<option value="4" <?php echo ($param==3)?"selected='selected'":'';?>>Custom Date</option>
					</select>
				</div>
				<div class='col-md-2 feedfilter  shcusdate'>
					<input type="text" name="sh_fromdate" id="sh_fromdate"  placeholder="Select From Date" data-format="dd/MM/yyyy"  required="true" class="form-control fromdatepicker" onchange='updateSharerecord()'
					value = "<?php echo ($fdate)?$fdate:''; ?>"	
					>
				</div>
				<div class='col-md-2 feedfilter shcusdate'>
					<input type="text" name="sh_todate" id="sh_todate"   placeholder="Select To Date"  data-format="dd/MM/yyyy"  required="true" class="form-control todatepicker"  onchange='updateSharerecord()'
					value = "<?php echo ($tdate)?$tdate:''; ?>"	
					>
				</div>				
			</div>
			<div class='sh_feed table-responsive' id='sh_feed'>
				<p style='color:#3D8B3D;border:0px solid red;text-align:center;margin-top:25px;font-weight:bold;'>Loading.....</p>
			</div>
		</div>
	</div>
	
<?php /*********************************************** Edit Section **************************************************/ ?>			
	</div>
</div>
<input type='hidden' value='<?php echo $userid; ?>' id='user_id'  >
<div id="xrprev-modal" class="modal fade" role="dialog"></div>
<?php require_once(APPPATH.'views/templates/admin/workoutdetails.php');?> 
<?php require_once(APPPATH.'views/templates/admin/deniedpermission.php');?>
<?php require_once(APPPATH.'views/pages/Admin/Workout/workout_modals.php');?>
