	<!--- Top nav && left nav--->
	<?php echo $topnav.$leftnav;?>
	<!--- Top nav && left nav --->
      <!-- Content Wrapper. Contains page content -->
      <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                           <?php $session = Session::instance();
							if($session->get('current_site_name')!=''){echo '"'.$session->get('current_site_name').'"';}?> Browse Subscribers
                        </h1>
						
                        <ol class="breadcrumb">
                            <li>
                                <i class="fa fa-dashboard"></i>  <a href="<?php echo URL::base().'admin/index'; ?>">Dashboard</a>
                            </li>
                            <li class="active">
                                <i class="fa fa-edit"></i> Language Attributes List 
								
                            </li>
                        </ol>
                    </div>
                </div>
				<div id="mes_suc" class="row" style="display:none;">
					<div class="col-lg-12">
						<div class="alert alert-success">
						  <i class="fa fa-check"></i><span></span>
						</div>
					</div>
				</div>
                <!-- /.row -->
				<?php if(isset($success) && $success!='') {  ?>
				<div class="row">
					<div class="col-lg-12">
						<div class="alert alert-success">
						  <i class="fa fa-check"></i><span><?php echo $success;?></span>
						</div>
					</div>
				</div>
			<?php } ?>
				<!--div class="row">
				<div class="col-sm-12 advancesubscribersearch">
				<div style="border: 1px solid rgb(221, 221, 221); display:none;" class="advance-search-contnr">
						<div class="col-lg-12">
							<h3>Advanced Search</h3>
							<form class="" action="<?php echo URL::base().'admin/subscriber/browse';?>" method="post">
								<div class="form-group">
									<label for="recipient-name" class="control-label">Filter by Age (between):<span id='setage'>15 - 122</span></label>
									<input type='hidden' id='setagerange' name='setagerange' value='15-122'><div id="agerange"></div>										
								</div>
								<div class="subscribefetchbtn topsearchbtn">
									<!--<button class="btn btn-default" type="button" onclick="getAdvanceSearchRecords()">Fetch Records</button>-->
									<!--input type="hidden" value="" name="pageval">
									<input type="submit" value="Fetch Records" id="subscribersubmit" id="subscribersubmit" class="btn btn-default btncol fetch-record">
									<input type="reset" class="btn btn-default" id="Reset" value="Reset" onclick="window.location.href='<?php echo URL::base()."admin/subscriber/browse"; ?>'"/>
								</div>
							</form>
							
						</div>
				</div>
				</div>
				</div-->
				<div class="row">
				<div class="col-lg-12">
				
						<?php if(isset($template_details) && count($template_details)>0) { ?>
                        <div class="table-responsive subscriber-dynamiclist">
                            <table id="suscribeTable" class="table table-bordered table-hover table-striped <?php echo ($datatable==0)?"":"dataTable"; ?> "> 
                                <thead>
                                    <tr>
                                        <th class="chkbox-header"><input type="checkbox"  name="row_index[]" id="select-all" /></th>
										<th>Attributes Name</th>                                        
										<th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach($template_details as $key => $value) { ?>	
                                    <tr id="row-<?php echo $value['id'];?>">
                                        <td class="tabl-chkbox checkselect"><input type="checkbox" name="row_index[]" class="chkbox-item subscribeselect" value="<?php echo $value['id'];?>" /></td> 
										<td><?php echo  isset($value['attribute_name']) ? $value['attribute_name'] : ''; ?> </td>
										<td><?php echo  isset($value['status']) && $value['status'] == 0 ? "Active" : 'In-Active'; ?> </td>
                                    </tr>
                                <?php } ?>	
                                </tbody>
                            </table>
                        </div><div class="exercise_tbl_pg" > <?php echo (isset($pagination))?$pagination:''; ?> </div>
                    <?php } else { echo "No Records Found..."; }?>
                    </div>
					</div>
                </div>
            <!-- /.row -->
				
				
            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->
    
	 