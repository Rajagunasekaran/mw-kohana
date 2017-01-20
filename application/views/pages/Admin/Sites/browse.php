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
                           <?php echo (isset($site_language['Browse Sites'])) ? $site_language['Browse Sites'] : 'Browse Sites'; ?>
                        </h1>
						
                        <ol class="breadcrumb">
                            <li>
                                <i class="fa fa-dashboard"></i>  <a href="<?php echo URL::base().'admin/index'; ?>"><?php echo (isset($site_language['Dashboard'])) ? $site_language['Dashboard'] : 'Dashboard'; ?></a>
                            </li>
                            <li class="active">
                                <i class="fa fa-edit"></i> <?php echo (isset($site_language['Sites List'])) ? $site_language['Sites List'] : 'Sites List'; ?>
                            </li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->
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
				<div class="col-lg-12">
                        <h2 class="col-xs-7 col-sm-7 col-lg-6 no-margin-top"><?php echo (isset($site_language['Sites List'])) ? $site_language['Sites List'] : 'Sites List'; ?></h2>
						<div class="col-xs-5 col-sm-5 col-lg-6"><a class="btn btn-default" href="<?php echo URL::base().'admin/sites/create';?>" style="float:right;"><?php echo (isset($site_language['Add Sites'])) ? $site_language['Add Sites'] : 'Add Sites'; ?></a></div>
                        <?php if(isset($template_details) && count($template_details)>0) { ?>
                            <div class="table-responsive col-sm-12 col-lg-12">
                                <table class="table table-bordered table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <td><input type="checkbox" name="siteselectall" id="siteselectall"></td>
                                            <th><?php echo (isset($site_language['Site ID'])) ? $site_language['Site ID'] : 'Site ID'; ?></th>
                                            <th><?php echo (isset($site_language['Site Name'])) ? $site_language['Site Name'] : 'Site Name'; ?></th>
                                            <th><?php echo (isset($site_language['Site Link'])) ? $site_language['Site Link'] : 'Site Link'; ?></th>
                                            <th><?php echo (isset($site_language['Status'])) ? $site_language['Status'] : 'Status'; ?></th>
                                            <th><?php echo (isset($site_language['Last Updated'])) ? $site_language['Last Updated'] : 'Last Updated'; ?></th>
                                            <th><?php echo (isset($site_language['Action'])) ? $site_language['Action'] : 'Action'; ?></th>
                                           
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                        foreach($template_details as $key => $value) { 
                                    ?>
                                        <tr id="row-<?php echo $value['id'];?>">
                                            <td><input type="checkbox" name="sites[]" id="<?php echo $value['id'];?>" class="siteselect"></td>
                                            <td><?php echo $value['id'];?></td>
                                            <td><?php echo $value['name']; ?></td>
                                            <td><a href="<?php echo URL::base(true).'site/'.$value['slug'];?>" target="_blank">View Site</a></td>
                                            <td><?php echo($value['is_active'] ? "Active" : "Not Active"); ?></td>
                                            <td><?php echo date("j D Y g:i a",strtotime($value['modified_at'])); ?></td>
                                            
                                            <td>
                                            	<select id="<?php echo $value['id'];?>" name="siteaction" class="siteaction form-control" >
                                                	<option value="" selected="selected"></option>                                                
                                                    <option value="managesite">Switch to Site</option>
                                                    <option value="editsite">Edit Site</option>
													<?php if($value['id']!=1){?>
                                                    <option value="deletesite">Delete Site</option>
													<?php }?>
                                                    <option value="duplicatesite">Duplicate Site</option>
                                                </select>
                                            </td>
                                            <!--<td><a href="<?php echo URL::base().'admin/sites/edit/'.$value['id']; ?>"><i class="fa fa-edit"></i></a></td>
                                            <td><a onclick="deleteRecord('<?php echo $value['id'];?>')" href="javascript:void(0);"><i class="fa fa-remove"></i></a></td>-->
                                        </tr>
                                    <?php } ?>										
                                    </tbody>
                                </table>
                            </div>
                        <?php } else { echo "No Records Found..."; }?>
                    </div>				
				</div>
                <div id="sitefilter-modal" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
                    <div class="vertical-alignment-helper">
                        <div class="modal-dialog modal-md">
                            <div class="modal-content aligncenter">
                                <div class="modal-header">
                                    <div class="row">
                                        <div class="popup-title">
                                            <div class="col-xs-2">
                                                <a href="javascript:void(0);" data-dismiss="modal" class="triangle" data-ajax="false" data-role="none">
                                                    <i class="fa fa-chevron-left iconsize"></i>
                                                </a>
                                            </div>
                                            <div class="col-xs-8 optionpoptitle"><?php echo __('Create Site From Existing'); ?></div>
                                            <div class="col-xs-2"></div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <form class="top-sitesrch-frm" id="form-sitefilter" method="post">
                                            <div class="col-xs-12">
                                                <div id="site_filter_search" class="">
                                                    <input type="text" id="sitename-filter" name="autosearch" class="searchtext form-control input-sm" placeholder="Search for sites..." value=""  tabindex="1">
                                                    <span class="searchclear fa fa-remove" style="display: none;"></span>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 filter-bttm">
                                                <div class="col-xs-8 filter_exe">
                                                    <label for="fsortby">Sort By :</label>
                                                    <select id="fsortby" name="fsortby" class="fsortby selectAction" style="width:200px;">
                                                        <option value="1" selected>A-Z</option>
                                                        <option value="2">Z-A</option>
                                                        <option value="3">Created (Most Recent)</option>
                                                        <option value="4">Modified (Most Recent)</option>   
                                                    </select>
                                                </div>
                                                <div class="col-xs-2 topsearchbtn">
                                                    <input type="hidden" name="pageval" value="">
                                                    <input type="button" class="btn btn-primary fetch-record" id="getsiteresult" onclick="siteSearchFilter();" value="Search">
                                                </div>
                                                <div class="col-xs-2 topsearchbtn">
                                                    <input type="button" class="btn btn-default resetserach" onclick="emptyFilterData();" value="Reset">
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="modal-body">
                                    <div class="row info-text">
                                        <div class="col-sm-12 col-xs-12 text-center">
                                            <div>Search here to see the sites.</div>
                                        </div>
                                    </div>
                                    <ul id="site-list" class="site-list"></ul>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal" data-ajax="false" data-role="none"><?php echo __('Close'); ?></button>
                                </div>
                            </div>
                         </div>
                    </div>
                </div>
                <script type="text/javascript">
                    $(document).ready(function(){
                        <?php if(isset($_GET['get']) && $_GET["get"]=='exists'){ ?>
                            $('#sitefilter-modal').modal();
                            $('#sitename-filter').focus();
                        <?php } ?>
                    });
                </script>
                <!-- /.row -->
				
				
            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->
    <div class="commonmodalboxarea"></div>  