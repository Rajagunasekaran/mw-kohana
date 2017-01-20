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
                           Browse Subscribers
                        </h1>
						
                        <ol class="breadcrumb">
                            <li>
                                <i class="fa fa-dashboard"></i>  <a href="<?php echo URL::base().'admin/index'; ?>">Dashboard</a>
                            </li>
                            <li class="active">
                                <i class="fa fa-edit"></i> Subscribers List
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
				<?php if(isset($template_details) && count($template_details)>0) { 
					$gender_array = Helper_Common::genderArray();
				?>
                <div class="col-lg-12">
                        <h2 class="col-lg-6">Subscribers List</h2>
						<div class="col-lg-6"><a class="btn btn-default" href="<?php echo URL::base().'admin/user/create';?>" style="float:right;">Add Subscriber</a></div>
                        <div class="table-responsive col-lg-12">
                            <table class="table table-bordered table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email / Mobile</th>
										<th>Gender</th>
										<th>Edit</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
								<?php foreach($template_details as $key => $value) { 
									$user_email = '';
									if(isset($value['user_email']) && $value['user_email']!='' && isset($value['user_mobile']) && $value['user_mobile']!='') {
										$user_email = $value['user_email'].' / '.$value['user_mobile'];
									} else if(isset($value['user_email']) && $value['user_email']!='') {
										$user_email = $value['user_email'];
									} else if(isset($value['user_mobile']) && $value['user_mobile']!='') {
										$user_email = $value['user_mobile'];
									}
									?>
								    <tr>
                                        <td><?php echo $value['user_fname'].' '.$value['user_lname']; ?></td>
										<td><?php echo $user_email; ?></td>
										<td><?php echo ucfirst($gender_array[$value['user_gender']]); ?></td>
										<td><a href="<?php echo URL::base().'admin/user/create/'.$value['id']; ?>"><i class="fa fa-edit"></i></a></td>
                                        <td><a href="<?php echo URL::base().'admin/user/browseuser/'.$value['id']; ?>"><i class="fa fa-remove"></i></a></td>
                                    </tr>

								<?php } ?>	
									
                                </tbody>
                            </table>
                        </div>
                    </div>
				<?php } else { echo "No Records Found..."; }?>
				</div>
                <!-- /.row -->
				
				
            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    

</body>

