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
                           "<?php echo ucfirst($site_name);?>" <?php echo (isset($site_language['Page'])) ? $site_language['Page'] : 'Page'; ?> <?php echo (isset($site_language['List'])) ? $site_language['List'] : 'List'; ?>
                        </h1>
						
                        <ol class="breadcrumb">
                            <li>
                                <i class="fa fa-dashboard"></i>  <a href="<?php echo URL::base().'admin/index'; ?>"><?php echo (isset($site_language['Dashboard'])) ? $site_language['Dashboard'] : 'Dashboard'; ?></a>
                            </li>
                            <li class="active">
                                <i class="fa fa-edit"></i> <?php echo (isset($site_language['Page'])) ? $site_language['Page'] : 'Page'; ?> <?php echo (isset($site_language['List'])) ? $site_language['List'] : 'List'; ?>
                            </li>
                        </ol>
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
                <div class="row">
				<?php //print_r($SmtpDetails); ?>
				<?php if(isset($page_details) && count($page_details)>0) { 
				$statusArray = Helper_Common::emailTemplateStatusArray();
				?>
                <div class="col-lg-12">
                        <h2 class="col-xs-7 col-sm-7 col-lg-6 no-margin-top"><?php echo (isset($site_language['Page'])) ? $site_language['Page'] : 'Page'; ?> <?php echo (isset($site_language['List'])) ? $site_language['List'] : 'List'; ?></h2>
						<?php if(Helper_Common::hasAccess('Create Pages')) { ?>
							<div class="col-xs-5 col-sm-5 col-lg-6"><a class="btn btn-default" href="<?php echo URL::base().'admin/cms/create/'.$site_id;?>" style="float:right;"><?php echo (isset($site_language['Add'])) ? $site_language['Add'] : 'Add'; ?> <?php echo (isset($site_language['Page'])) ? $site_language['Page'] : 'Page'; ?></a></div>
						<?php } ?>
                        <div class="table-responsive col-sm-12 col-lg-12">
                            <table class="table table-bordered table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th><?php echo (isset($site_language['Title'])) ? $site_language['Title'] : 'Title'; ?></th>
                                        <th><?php echo (isset($site_language['Slug'])) ? $site_language['Slug'] : 'Slug'; ?></th>
                                        <th><?php echo (isset($site_language['Page'])) ? $site_language['Page'] : 'Page'; ?> <?php echo (isset($site_language['Link'])) ? $site_language['Link'] : 'Link'; ?></th>
										<th><?php echo (isset($site_language['Status'])) ? $site_language['Status'] : 'Status'; ?></th>
										<?php if(Helper_Common::hasAccess('Modify Pages')) { ?>
											<th><?php echo (isset($site_language['Action'])) ? $site_language['Status'] : 'Action'; ?></th>
										<?php } ?>
									</tr>
                                </thead>
                                <tbody>
								<?php
									foreach($page_details as $key => $value) { ?>
									<tr>
                                        <td><?php echo $value['page_title']; ?></td>
										<td><?php echo $value['page_slug']; ?></td>
                                       <td><a target="_blank" href="<?php echo URL::base(true).'site/'.$value['slug']."/".$value['page_slug'];?>"><?php echo (isset($site_language['View'])) ? $site_language['View'] : 'View'; ?> <?php echo (isset($site_language['Page'])) ? $site_language['Page'] : 'Page'; ?></a></td>
										<td><?php echo $statusArray[$value['status']]; ?></td>
										<?php if(Helper_Common::hasAccess('Modify Pages')) { ?>
											<td>
												<select  class="form-control selectAction" onchange="pageAction(this.value,'<?php echo $value['page_id'];?>');">
													<option value=""><?php //echo (isset($site_language['Action'])) ? $site_language['Status'] : 'Action'; ?></option>
													<option value="edit">Edit Page</option>
													<option value="delete">Delete Page</option>
												</select>
											</td>
										<?php } ?>
									</tr>

								<?php } ?>	
									
                                </tbody>
                            </table>
                        </div>
                    </div>
				<?php } else { echo "<div class='col-lg-12'>No Records Found...</div>"; }?>
				</div>
                <!-- /.row -->
				
				
            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->
	<input type="hidden" name="site_id" id="site_id" value="<?php echo $site_id;?>" />
    <!-- jQuery -->
    

</body>

