<!--- Top nav && left nav--->
<?php echo $topnav.$leftnav;?>
<!--- Top nav && left nav --->
<!-- Content Wrapper. Contains page content -->
<div id="page-wrapper">
	<div class="container-fluid">
		<!-- Page Heading -->
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header"><?php echo $page_title;?></h1>
				<ol class="breadcrumb">
					<li><i class="fa fa-dashboard"></i>  <a href="<?php echo URL::base().'admin/index'; ?>">Dashboard</a></li>
					<li class="active"><i class="fa fa-exclamation-triangle"></i> <?php echo $page_title;?></li>
				</ol>
         </div>
		</div>
      <!-- /.row -->
		<input type='hidden' id='etype' value="<?php echo (Request::current()->param('id'))?Request::current()->param('id'):'';?>">
		<?php $session = Session::instance();
					if ($session->get('flash_success')){ ?>
				   <div class="banner alert alert-success">
					<?php echo $session->get_once('flash_success') ?>
				  </div>
				<?php }
                    if ($session->get('flash_error')){ ?>
				   <div class="banner alert alert-danger">
					<?php echo $session->get_once('flash_error') ?>
				  </div>
				<?php } ?>
<?php /*********************************************** Edit Section **************************************************/ ?>
<style type='text/css'>
.str_bold{
	font-weight: bold;
}
</style>
<div class="row"><?php
	if(isset($errorfeeds) && count($errorfeeds)>0) {	?>
		<div class="table-responsive col-lg-12 subscriber-dynamiclist">
			<table id="errorTable" class="table table-bordered table-hover table-striped dataTable">
				<thead>
					<tr>
						<th class="chkbox-header">
							<input type="checkbox"  name="row_index[]" id="select-all" />
						</th>
						<th>Error File</th>
						<!--th>Description</th-->
						<th>Error Type</th>
						<th>Line No</th>
						<th>Created</th>
						<th>Modified</th>
						<th>Status</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody><?php
					foreach($errorfeeds as $key => $value) { ?>
						<tr id="row-<?php echo $value['id'];?>" class="<?php echo ($value["read_status"]==0)?"str_bold":""; ?>">
							<td class="tabl-chkbox checkselect"><input type="checkbox" name="row_index[]" class="chkbox-item subscribeselect" value="<?php echo $value['id'];?>" /></td> 
						   <td><a href="javascript:void(0);" onclick='viewError("<?php echo $value['id'] ?>")' >
							<?php //echo $value['error_file'];
							$str = explode("myworkout",$value['error_file']);
							echo $str[1];
							?>
							</a></td>
							
							<!--td align='left' title='<?php echo $value['error_text']; ?>'>
								<?php
								//echo strlen($value['error_text']);
								//echo "<br>";
								echo (strlen($value['error_text'])<=35)?$value['error_text']:substr($value['error_text'],0,35)."....";
								?>
							</td-->
							<td align='center'><?php echo ($value["error_type"]==1)?"PHP":"Mysql"; ?></td>
							<td align='center'><?php echo $value['error_line']; ?></td>
							<td align='center'><?php echo date("j M Y h:i:s a",strtotime($value['created_date']));?></td>
							<td align='center'><?php echo date("j M Y h:i:s a",strtotime($value['modified_date']));?></td>
							<td align='center' id='tdck_<?php echo $value['id'] ?>'>
								<?php
								if($value['status']==0){
									?>
									<i class="fa fa-check error_check_css" title='Click here to change the status' id='ck_<?php echo $value['id'] ?>' onclick="update_error_feed('<?php echo $value['id'] ?>',1)"   ></i>
									<?php
								}else{
									?>
									<i class="fa fa-check error_check_css_fixed" title='Click here to change the status' id='ck_<?php echo $value['id'] ?>' onclick="update_error_feed('<?php echo $value['id'] ?>',0)"></i>
									<?php
								}
								?>
							</td>
							<td align='center'><i class='fa fa-blue fa-trash' onclick='remove_error(<?php echo $value["id"]; ?>,<?php echo (Request::current()->param('id'))?Request::current()->param('id'):''; ?>)' ></i>
							</td>
                  </tr><?php
					} ?>
				</tbody>
			</table>
		</div>				
		<?php
	} else { echo "No Records Found..."; }	?>
</div>
<?php /*********************************************** Edit Section **************************************************/ ?>			
	</div>
</div>






<div class="modal fade" id="errorviewModal" tabindex="-1" role="dialog" aria-labelledby="errorviewModalLabel">
  <div class="vertical-alignment-helper">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">View Error Feed</h4>
		</div>
      <div class="modal-body" id='viewerror'>
			<div class="form-group">
				Loading.....
			</div>
		</div>
    </div>
  </div>
  </div>
</div>


