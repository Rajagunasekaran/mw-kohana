	<style type="text/css">
	.ocpLegacyBold {font-weight: 400;font-size: 16px;color: #337ab7;}
	.ocpArticleContent p, .ocpArticleContent span {color: #363636;font-size: 14px;line-height: 1.286em;padding: 0;}
	.ocpArticleContent .panel { margin-bottom: 0px; background-color: #CCCCCC; }
	.ocpArticleContent .panel .ocpArticleContent .panel{background-color: #e9e9e9;}
	.toggletitle { padding: 10px; font-size: 15px; } 
	.toggletitle:hover { cursor: pointer; }
	.select2-container{padding:0; width:100%;}
	.table-responsive {border-bottom: 1px solid #ccc;padding: 5px 0;}
	.table-responsive .table-responsive{padding:0;}
	.table-responsive .select2-container .select2-choice{border-radius:0;}
	.banded{ max-width: 600px; width: 100%; }
	.fa.fa-pencil { float: right; font-size: 20px; font-style: normal; font-weight: normal; }
	.fa.fa-pencil:hover {color:#23527c;}
	.innersetting{padding: 12px 0 5px;display: inline-block;position: relative;border-bottom: 1px solid #ccc;}
	.innersetting.last{border:none;}
	.innersetting .col-lg-6{padding:0px;display: inline-block; vertical-align: middle; line-height: 35px;}
	</style>
    <!--- Top nav && left nav--->
	<?php echo $topnav.$leftnav; ?>
	<!--- Top nav && left nav --->
      <!-- Content Wrapper. Contains page content -->
      <div id="page-wrapper">
            <div class="container-fluid">
                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                           <?php $session = Session::instance();
							if($session->get('current_site_name')!=''){echo '"'.$session->get('current_site_name').'"';}?> Preference Defaults
                        </h1>
                        <ol class="breadcrumb">
                            <li>
                                <i class="fa fa-dashboard"></i>  <a href="<?php echo URL::base().'admin/index'; ?>">Dashboard</a>
                            </li>
                            <li class="active">
                                <i class="fa fa-edit"></i> Preference Defaults Settings
                            </li>
                        </ol>
                    </div>
                </div>
				
                <!-- /.row -->
				<div class="row" id="mes_suc" style="display:none;">
					<div class="col-lg-12">
						<div class="alert alert-success">
						  <i class="fa fa-check"></i><span></span>
						</div>
					</div>
				</div>
                <div class="row">
					<form name="data_Upload" action ="">
						<div class="col-lg-12">
							<div class="col-lg-6 innersetting">
								<div class="col-lg-6">File</div>
								<div class="col-lg-6">
									<input id="file" type="file" name="file" accept=".xlsx,.xls"  required="" aria-required="true">
								</div>
							</div>
							<div class="col-lg-12 innersetting">
								<div class="col-lg-6">
									<button class="btn btn-primary" id="timezonesubmit" name="submit" type="submit">Save</button>
									&nbsp;&nbsp;
									<button class="btn btn-default" id="timezonecancel" name="cancel" type="submit">Cancel</button>
								</div>
							</div>	
						</div>
					</form>			
				</div>
				<div class="row" style="margin-top:10px;">
                    <?php if(isset($languagedata) && count($languagedata)>0) { ?>
                            <div class="table-responsive col-lg-12">
                                <table class="table table-bordered table-hover table-striped dataTable" id='wkouttable'>
                                    <thead>
                                        <tr>
                                            <th class='chkbox-header'><input type="checkbox" name="wkoutselectall" id='wkoutselectall' /></th>		
                                            <th>Key</th>		
                                            <th>Value</th>	
                                        </tr>
                                    </thead>
                                    <tbody id="table-content-contnr">
										<?php  //print_r($languagedata); die;
											foreach($languagedata as $key => $value) {
										?>
											<tr id="row-<?php echo $value['id'];?>">
												<td class='tabl-chkbox checkselect'>
																<input type="checkbox" name="wkoutselect[]" id="wkoutselect[]" class="wkoutselect" value="<?php echo $value['id'];?>" /></td>
												</td>
												<td class='tabl-chkbox checkselect'>
													<div><?php echo $value['key'];?></div>
												</td>
												<td class='tabl-chkbox checkselect'>
													<div><?php echo $value['value'];?></div>
												</td>
													
											</tr> <?php } ?>
                                    </tbody>
                                </table>
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

