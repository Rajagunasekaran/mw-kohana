<style type="text/css">
	.ocpLegacyBold {
		font-weight: 400;
		 font-size: 16px;
		 color: #337ab7;
	}
	.ocpArticleContent{
		margin:20px 0;
	}
	.ocpArticleContent p, .ocpArticleContent span {
		color: #363636;
		font-size: 14px;
		line-height: 1.286em;
		padding: 0;
	}
	.ocpArticleContent table td {
		margin: 0;
		padding: 4px 10px 4px 10px;
		vertical-align: top;
	}
	.ocpArticleContent table.banded > tbody > tr:nth-child(2) { background-color: #ffffff; }
	.ocpArticleContent table.banded > tbody > tr > td { padding-left: 0px; padding-right: 0px; }
	.ocpArticleContent table.banded .panel { margin-bottom: 0px; background-color: #CCCCCC; }
	.toggletitle { padding: 10px; font-size: 15px; } 
	.toggletitle:hover { cursor: pointer; }
	.ocpArticleContent table.banded .panel table tbody tr:last-child { border-bottom: medium none; }

	.ocpArticleContent table.banded tr:nth-child(2n), .ocpArticleContent table.noheader thead tr:first-child {
		background-color: #f3f3f3;
		border-bottom: 1px solid #ccc;
		border-top: 1px solid #ccc;
		padding: 0;
		vertical-align: top;
	}
	.ocpArticleContent table.banded tr:nth-child(2n+1) {
		background-color: #fff;
		border-bottom: 1px solid #ccc;
		padding: 0;
		vertical-align: top;
	}
	/*Roles Table*/
	.banded, .banded table { max-width: 600px; width: 100%; }
	.banded  td { width: 20%; }
	.banded tr td:first-child { width: 40%; }
	.banded tr td p { margin-top: 4px; margin-bottom: 0px; }

</style>
<!--- Top nav && left nav--->
<?php echo $topnav.$leftnav;
if(isset($roletype) && count($roletype)>0) {
	$catWise = array();
	foreach($roletype as $key => $value) {
		$catWise[$value['cat_name']][] = $value;
	}
}
if(isset($roleaccess) && count($roleaccess)>0) {
	$typeWise = array();
	foreach($roleaccess as $key => $value) {
		$typeWise[$value['access_type_id']][] = $value;
	}
}
if(isset($catWise) && count($catWise)>0 && isset($typeWise) && count($typeWise)>0) {
	foreach($catWise as $key => $value) {
		foreach($value as $catKey => $catValue) {
			if(isset($typeWise[$catValue['type_id']]) && count($typeWise[$catValue['type_id']])>0) {
				$catWise[$key][$catKey]['access'] = $typeWise[$catValue['type_id']];
			}
		}
	}
}
$session	= Session::instance();
?>
<!--- Top nav && left nav --->
<!-- Content Wrapper. Contains page content -->
<div id="page-wrapper">
	<div class="container-fluid">
		<!-- Page Heading -->
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Role Access Level Control</h1>
				<ol class="breadcrumb">
					<li>
						<i class="fa fa-dashboard"></i>  
						<a href="<?php echo URL::base().'admin/index'; ?>">Dashboard</a>
					</li>
					<li class="active">
						<i class="fa fa-edit"></i> Role Access Settings
					</li>
				</ol>
			</div>
		</div>
		<!-- /.row -->
		<div class="row msg-contnr" style="display:none;">
			<div class="col-lg-12">
				<div class="alert alert-success">
					<i class="fa fa-check"></i>
					<span>Role Access Successfully Modified</span>
				</div>
			</div>
		</div>
		<div class="row">				
			<div class="col-lg-12">
				<h2 class="commonheading">Role Access Settings</h2>
				<div class="table-responsive col-lg-12 ocpArticleContent">
					<table id="tblID0EABCACAAA" class="banded">
						<thead>
							<tr>
								<td>
									<p>
										<b class="ocpLegacyBold">Permission</b>
									</p>
								</td>
								<?php if(Helper_Common::is_admin()){ ?>
									<td>
										<p>
											<b class="ocpLegacyBold">Manager</b>
										</p>
									</td>
								<?php } ?>
								<td>
									<p>
										<b class="ocpLegacyBold">Trainer</b>
									</p>
								</td>
								<td>
									<p>
										<b class="ocpLegacyBold">Susbcriber</b>
									</p>
								</td>
							</tr>
						</thead>
						<?php if(isset($catWise) && count($catWise)>0) { ?>
							<tbody>
								<?php $i = 1;
								foreach($catWise as $key => $value) { ?>
									<tr>
										<td colspan="4">
											<div class="panel">
												<div data-toggle="collapse" data-target="#divIndex<?php echo $i;?>" class="toggletitle"><?php echo $key;?></div>
												<div id="divIndex<?php echo $i;?>" class="collapse">
													<?php if(is_array($value) && count($value)>0) { ?>
														<table>
															<tbody>
																<?php foreach($value as $typeKey => $typeVal) {
																	$managerAccess = $trainerAccess = $subscriberAccess = array();
																	if(isset($typeVal['access']) && count($typeVal['access'])>0) {
																		foreach($typeVal['access'] as $accessKey => $accessVal) {
																			if($accessVal['role_id']==8 && Helper_Common::is_admin()) {
																				$managerAccess[$typeVal['type_id']] = $accessVal['id'];
																			}elseif($accessVal['role_id']==7) {
																				$trainerAccess[$typeVal['type_id']] = $accessVal['id'];
																			}elseif($accessVal['role_id']==6) {
																				$subscriberAccess[$typeVal['type_id']] = $accessVal['id'];
																			}
																		}
																	}
																?>
																	<tr>
																		<td>
																			<p><?php echo $typeVal['type_name'];?></p>
																		</td>
																		<?php if(Helper_Common::is_admin()){ ?>
																			<td>
																				<input type="checkbox" class="tiny-toggle" <?php if(isset($managerAccess[$typeVal['type_id']])) { ?> checked="checked" data-access-id="<?php echo $managerAccess[$typeVal['type_id']];?>" <?php } ?> data-role-id="8" data-type-id="<?php echo $typeVal['type_id'];?>" data-tt-type="check" data-tt-size="medium">
																			</td>
																		<?php } ?>
																		<td>
																			<input type="checkbox"  class="tiny-toggle" <?php if(isset($trainerAccess[$typeVal['type_id']])) { ?> checked="checked" data-access-id="<?php echo $trainerAccess[$typeVal['type_id']];?>" <?php } ?> data-role-id="7" data-type-id="<?php echo $typeVal['type_id'];?>" data-tt-type="check" data-tt-size="medium">
																		</td>
																		<td>
																			<input type="checkbox" class="tiny-toggle" <?php if(isset($subscriberAccess[$typeVal['type_id']])) { ?> checked="checked" data-access-id="<?php echo $subscriberAccess[$typeVal['type_id']];?>" <?php } ?> data-role-id="6" data-type-id="<?php echo $typeVal['type_id'];?>" data-tt-type="check" data-tt-size="medium">
																		</td>
																	</tr>
																<?php } ?>
															</tbody>
														</table>
													<?php } ?>
												</div>
											</div>
										</td>
									</tr>
								<?php $i++;
								} ?>
							</tbody>
						<?php } ?>
					</table>
				</div>
			</div>				
		</div>
		<!-- /.row -->
	<input type="hidden" id="site_id" name="site_id" value="<?php echo $session->get('current_site_id');?>"/>
	</div>
	<!-- /.container-fluid -->
</div>
<!-- /#page-wrapper -->
</div>
<!-- /#wrapper -->
</body>

