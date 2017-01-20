	<!--- Top nav && left nav--->
	<?php echo $topnav.$leftnav; ?>
	<?php require_once(APPPATH.'views/templates/admin/usermodal.php');?>
	<?php echo $imgeditor2; ?>
	<!--- Top nav && left nav --->
      <!-- Content Wrapper. Contains page content -->


<?php
$site = Helper_Common::get_active_sites();

if(isset($site) && is_array($site) && count($site)>0 && $current_site_id==1){
	$site =  implode(",",$site);
}else{
	$site = $current_site_id;
}
?>
<style type="text/css">
	
.ms-choice {
	background: none;
	border: none;
	color: #42ABFF;
	/*margin-top:-15px; */
}
.dropdowna {
	border:0px solid red;
	margin-top:5px;
}

.ms-drop.bottom {
   box-shadow: 0 4px 5px hsla(0, 0%, 0%, 0.15);
   top: 100%;
	margin-top:2px;
	width:100%;
}
.ms-parent{
	width:102%;
}
/*
.aamoteactions .ms-choice {
	background: none;
	border: none;
	color: #42ABFF;
	margin-top:-15px;
}
.aamoteactions .ms-drop.bottom {
   box-shadow: 0 4px 5px hsla(0, 0%, 0%, 0.15);
   top: 100%;
	margin-top:20px;
	width:100%;
}
*/

.userstatus_checkbox,.css-checkbox {
	position:absolute; z-index:-1000; left:-1000px; overflow: hidden; clip: rect(0 0 0 0); height:1px; width:1px; margin:-1px; padding:0; border:0;
	
}

.userstatus_checkbox + span.css-label,.css-checkbox + label.css-label {
	padding-left:20px;
	height:14px; 
	display:inline-block;
	line-height:14px;
	background-repeat:no-repeat;
	background-position: 0 0;
	font-size:14px;
	vertical-align:middle;
	cursor:pointer;
}

.userstatus_checkbox:checked + span.css-label ,.css-checkbox:checked + label.css-label {
	background-position: 0 -14px;
}

span.css-label,label.css-label {
background-image:url('<?php  echo URL::base(TRUE); ?>assets/css/images/checkIcon.png');
-webkit-touch-callout: none;
-webkit-user-select: none;
-khtml-user-select: none;
-moz-user-select: none;
-ms-user-select: none;
user-select: none;
}

/*
.ms-choice > div {
   background: hsla(0, 0%, 0%, 0) url("multiple-select.png") no-repeat scroll left top;
	background:none;
	color: #2698f1;
	content: "\f0d7";
	font-family: 'FontAwesome';
	font-size: 22px;
	
	height: 25px;
	position: absolute;
	right: 0;
	top: 0;
	width: 20px;
}
.ms-choice > div.open {
	background: hsla(0, 0%, 0%, 0) url("multiple-select.png") no-repeat scroll right top;
}
*/
.ms-drop ul > li label.optgroup{
	width:0%;
	margin-left: -12px;
}
.ms-drop ul > li label{
	max-width:10%;
	width:102%;
}
#shs_filter >div > div .ms-parent{
	width:102px;
}

</style>
<?php
if(Helper_Common::is_trainer())
{
		?>
		<style type='text/css'>
			.panel-title{
				font-size: 14px;
			}
		</style>
		<?php
	}
?>
<input type='hidden' id='week_starts_on' class='week_starts_on' name='week_starts_on' value='<?php	echo (isset($site_week_starts_on) && $site_week_starts_on!='')?$site_week_starts_on:1;?>' >
<div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                           <?php 
                           echo I18n::get('Dashboard');
                           //echo (isset($site_language['Dashboard'])) ? $site_language['Dashboard'] : 'Dashboard'; ?> <small><?php echo I18n::get('Welcome to My Workouts!!!');?></small>
                        </h1>
                    </div>
                </div>
                <!-- /.row -->
					 <input type='hidden' id='trainer_check' value='<?php echo (Helper_Common::is_trainer() || Helper_Common::is_manager() || Helper_Common::is_admin())?true:false; ?>'>
					<input type='hidden' id='manager_check' value='<?php echo (Helper_Common::is_admin() || Helper_Common::is_manager())?true:false; ?>'>
					<?php if(Helper_Common::hasAccess('View Statistics')) { ?>
					<?php if(Helper_Common::hasAccess('Graph Sections')) { ?>
					
					<div class="row chart" >
                     <div class="col-lg-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <div class="">
									<h3 class="panel-title"><i class="fa fa-bar-chart-o fa-fw"></i> <?php echo (Helper_Common::is_trainer() ?  I18n::get('Shared Records') : I18n::get('Subscribers') );?></h3>
									<label class="left rightactions dropdowna panel_headerset_status" id="dropdownsubscibe" >
										<?php
										if(!Helper_Common::is_trainer())
										{
											$fetch_field = 'id, status';
											$result = Model::instance('Model/admin/user')->get_table_details('user_status',$fetch_field);
											?>
											<select placeholder="Choose Status" name="refstatus[]"  id="refstatus" class="bordernone fa-blue panel-margin" onchange='updatemorrischart()'  >
												<!--option value="">User Status</option-->
												<?php
												if($result){
													$i=0;
													foreach($result as $k=>$v){
														$sele = "";
														if($i==0){
															$sele = "selected='selected'";	
														}
														echo '<option '.$sele.' value="'.$v["id"].'" >'.$v["status"].'</option>';
														$i++;
													}
												}	?>
											</select>
											<?php
										}
										else
										{
											?>
											<select placeholder="Choose Status" name="refstatus" multiple="multiple" id="refstatus" class="bordernone fa-blue panel-margin" onchange="refstatuschange();">
												<option value="all" selected="selected">All</option>
												<option value="workouts">Workouts</option>
												<option value="exercises">Exercises</option>
											</select>
											<?php
										}
										?>
									</label>
									<div class="pull-right panel-margin" style="float:right"><i class="fa fa-refresh fa-blue " onclick="updatemorrischart()" aria-hidden="true"></i></div>
								</div>  										  
                            </div>
                            <div class="panel-body" style="height:355px">
								
								<div class="col-lg-12 clearfix chartdrop">
								    <!--div class="left rightactions">
										<select name="reftype" id="reftype" class="form-control mmoteactions" onchange='updatemorrischart()'>
											<option value="subscribers" selected='selected'>Subscribers</option>
											<option value="logged_in">Logged In </option>
										</select>
									</div-->
									<!--div class="left rightactions">
										<?php
										$fetch_field = 'id, status';
										$result = Model::instance('Model/admin/user')->get_table_details('user_status',$fetch_field);
										?>
										<select placeholder="Choose Status" name="refstatus[]" id="refstatus" class=" fa-blue" onchange='updatemorrischart()' >
											<option value="">User Status</option>
											<?php
											if($result){
												foreach($result as $k=>$v){
													echo '<option value="'.$v["id"].'" >'.$v["status"].'</option>';
												}
											}	?>
										</select>
									</div-->
									<div class="left rightactions">
										<select placeholder="Choose Status" name="bythis" id="bythis" class="bordernone  panel-margin fa-blue" onchange='updatemorrischart()' >
											<option value="">Most Recent</option>
											<option value="1">Today</option>
											<option value="2">This Week</option>
											<option value="3">This Month</option>
											<option value="5">This Fortnight</option>
											<option value="4">Custom Date</option>
										</select>
									</div>
									<div class="row left rightactions cusdate">
										<input type="text" name="fromdate" id="fromdate" placeholder="Select From Date" data-format="dd/MM/yyyy"  required="true" class="bordernone fromdatepicker fa-blue" onchange='changebythis()'>
									</div>
									<div class="row left rightactions cusdate">
									    <input type="text" name="todate" id="todate"   placeholder="Select To Date"  data-format="dd/MM/yyyy"  required="true" class="bordernone todatepicker fa-blue"  onchange='changebythis()'>
									</div>
									<!--div   class="left rightactions">
										<a href="javascript:void(0)" class="btn btn-default fa-blue" onclick="updatemorrischart()"><?php echo (isset($site_language['Show Chart'])) ? $site_language['Show Chart'] : 'Show Chart'; ?></a>
									</div-->
									
								</div>
								<div id="charts" style="height:338px" ></div>										  
								<script type='text/javascript'>var dataset = '';</script>
                     </div>
                  </div>
                    </div>
						<div class="col-lg-6">
							<div class="panel panel-default">
								<div class="panel-heading">
									<div class="">
										<h3 class="panel-title"><i class="fa fa-bar-chart-o fa-fw"></i> <?php echo I18n::get('Unique Logins'); //echo (isset($site_language['Activity Report'])) ? $site_language['Activity Report'] : 'Activity Report'; ?></h3>
										<label class="left rightactions dropdowna panel_headerset_status" id='dropdownunique' >
											<?php
											$fetch_field = 'id, status';
											$result = Model::instance('Model/admin/user')->get_table_details('user_status',$fetch_field);
											?>
											<select placeholder="Choose Status" name="refstatus[]" id="refstatus1" class="bordernone fa-blue panel-margin" onchange='updatemorrischart1()' >
												<!--option value="">User Status</option-->
												<?php
												if($result){
													$i=0;
													foreach($result as $k=>$v){
														$sele = "";
														if($i==0){
															$sele = "selected='selected'";	
														}
														echo '<option '.$sele.' value="'.$v["id"].'" >'.$v["status"].'</option>';
														$i++;
													}
												}	?>
											</select>
										</label>
										<div class="pull-right panel-margin" style="float:right"><i class="fa fa-refresh fa-blue " onclick="updatemorrischart1()" aria-hidden="true"></i></div>
									</div>
                            </div>
                            <div class="panel-body" style="height:355px">
								
								<div class="col-lg-12 clearfix chartdrop">
								    <!--div class="left rightactions">
										<select name="reftype" id="reftype" class="form-control mmoteactions" onchange='updatemorrischart()'>
											<option value="subscribers" selected='selected'>Subscribers</option>
											<option value="logged_in">Logged In </option>
										</select>
									</div-->
									<div class="left rightactions choosedropdown hide">
										<?php
										$fetch_field = 'id, status';
										$result = Model::instance('Model/admin/user')->get_table_details('user_status',$fetch_field);
										?>
										<select placeholder="Choose Status" name="refstatus[]" id="refstatus1" class="bordernone fa-blue panel-margin" onchange='updatemorrischart1()' >
											<option value="">User Status</option>
											<?php
											if($result){
												$i=0;
												foreach($result as $k=>$v){
													$sele = "";
													if($i==0){
														$sele = "selected='selected'";	
													}
													echo '<option '.$sele.' value="'.$v["id"].'" >'.$v["status"].'</option>';
													$i++;
												}
											}	?>
										</select>
									</div>
									<div class="left rightactions choosedropdowns">
										<select placeholder="Choose Status" name="bythis" id="bythis1" class="bordernone fa-blue panel-margin" onchange='updatemorrischart1()' >
											<option value="">Most Recent</option>
											<option value="1">Today</option>
											<option value="2">This Week</option>
											<option value="3">This Month</option>
											<option value="5">This Fortnight</option>
											<option value="4">Custom Date</option>
										</select>
									</div>
									<div class="left rightactions cusdate1">
										<input type="text" name="fromdate" id="fromdate1" placeholder="Select From Date" data-format="dd/MM/yyyy"  required="true" class="bordernone fromdatepicker fa-blue" onchange='changebythis1();'>
									</div>
									<div   class="left rightactions cusdate1">
									    <input type="text" name="todate" id="todate1"   placeholder="Select To Date"  data-format="dd/MM/yyyy"  required="true" class="bordernone todatepicker fa-blue"  onchange='changebythis1();'>
									</div>
									<!--div   class="left rightactions">
										<a href="javascript:void(0)" class="btn btn-default fa-blue" onclick="updatemorrischart1()"><?php echo (isset($site_language['Show Chart'])) ? $site_language['Show Chart'] : 'Show Chart'; ?></a>
									</div-->
									
								</div>
								
								        <div id="charts2" style="height:338px" ></div>
										  
										  <script type='text/javascript'>
								var dataset = '';
										
								</script>
                            </div>
                        </div>
                    </div>
            </div>
					 <?php }
					 
				} ?>
				
<?php if(Helper_Common::is_trainer() || Helper_Common::is_manager()    || Helper_Common::is_admin() ){
	/*
	?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title"><i class="fa fa-share-alt fa-fw"></i> <?php echo (isset($site_language['Shared Records'])) ? $site_language['Shared Records'] : 'Shared Records'; ?>
			<button class='btn btn-primary sh_filter_btn'  data-toggle="collapse" data-target="#sh_filter" >Options</button></h3>
			
		</div>
		<div class="panel-body">
			<div class="row padding5 collapse"  id='sh_filter' style='border: 0px solid red;background-color:#F5F5F5;margin-top:-25px  ' >
				<div class='col-md-2 feedfilter'>
					<select placeholder="Choose Status" name="sh_bythis" id="sh_bythis" class="form-control amoteactions" onchange='updateSharerecord()' >
						<option value="">Most Recent</option>
						<option value="1">Today</option>
						<option value="2">This Week</option>
						<option value="3">This Month</option>
						<option value="5">This Fortnight</option>
						<option value="4">Custom Date</option>
					</select>
				</div>
				<div class='col-md-2 feedfilter  shcusdate'>
					<input type="text" name="sh_fromdate" id="sh_fromdate" placeholder="Select From Date" data-format="dd/MM/yyyy"  required="true" class="form-control fromdatepicker" onchange='updateSharerecord()'>
				</div>
				<div class='col-md-2 feedfilter shcusdate'>
					<input type="text" name="sh_todate" id="sh_todate"   placeholder="Select To Date"  data-format="dd/MM/yyyy"  required="true" class="form-control todatepicker"  onchange='updateSharerecord()'>
				</div>				
			</div>
			<div class='sh_feed table-responsive' id='sh_feed'>
				<p style='color:#3D8B3D;border:0px solid red;text-align:center;margin-top:25px;font-weight:bold;'>Loading.....</p>
			</div>
		</div>
	</div>
	<?php
	*/
	
	$log_site =  $current_site_id;
	
	$role       = Helper_Common::get_role("trainer");
	if(Helper_Common::is_admin()){
		$active_sites=array();
		$active_sites = Helper_Common::get_active_sites();
		$usersiteres = Helper_Common::get_active_sites_withname();
		$current_site_id = implode(",",$active_sites);
	}
	else if(Helper_Common::is_manager()){
	 $usersiteres = $current_site_id;
		/*$active_sites=array();
		$usersiteres = Model::instance('Model/admin/user')->get_user_sites(Auth::instance()->get_user()->pk());
		if($usersiteres){
			foreach($usersiteres as $k=>$v){
				$active_sites[] = $v["site_id"];
			}
			$current_site_id = implode(",",$active_sites);
		}*/
		
	}
	$sitetrainer    = Helper_Common::get_role_by_users($role, $current_site_id);
	//echo "<pre>";print_R($sitetrainer); die;
	?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title"><i class="fa fa-share-alt fa-fw"></i> <?php echo (isset($site_language['Shared Records Stats'])) ? $site_language['Shared Records Stats'] : 'Shared Records Stats'; ?>
			<?php
			if(!Helper_Common::is_trainer()){
			?>
			<button class='btn btn-primary af_filter_btn'  data-toggle="collapse" data-target="#shs_filter" >Options</button>
			<?php } ?>
			</h3>
			<div class="pull-right panel-margin" style="float:right">
				<i class='fa fa-refresh fa-blue' onclick='tempVal="";get_sharestats()'></i>
			</div>
		</div>
		<div class="panel-body">
			<?php
			if(!Helper_Common::is_trainer()){
			?>
			<div class="row padding5 collapse"  id='shs_filter' style='border: 0px solid red;background-color:#F5F5F5;margin-top:-25px  ' >
				<?php
					if(Helper_Common::is_admin())
					{
						?>
				<div class='col-md-2 feedfilter'>
					
						<select placeholder="Choose Site" name="shs_sites" id="shs_sites"  onchange='get_activesite_trainers()' >
							<option value='all'
							<?php
							if( $log_site==1){
								echo  "selected='selected'";		
							}
							?>
							>All</option>
							<?php
							if(isset($usersiteres) && count($usersiteres)>0 && is_array($usersiteres))
							{
								foreach($usersiteres as $key => $value)
								{	
									$select = "";
									if( $log_site!=1 && $log_site==$value["site_id"] && !Helper_Common::is_admin()){
										$select = "selected='selected'";	
									}
									elseif( $log_site==1 && $log_site==$value["site_id"] && !Helper_Common::is_admin()){
										$select = "selected='selected'";	
									}
									
									?>
									<option <?php echo $select; ?>  value="<?php echo $value['site_id'];?>" title='<?php echo ucfirst($value['name']); ?>'><?php
									//echo ucfirst($value['name']);
									echo (strlen($value['name']) > 30)? substr(ucfirst($value['name']),0,30)."...":ucfirst($value['name']);
									?></option><?php
								}
								//echo '</optgroup>';
							}
							?>	
						</select>
						
				</div>
				<?php
					}else{
						  //echo "<p class='fa-blue'>".ucfirst($value['name'])."</p>";
						  echo "<input type='hidden' name=\"shs_sites\" id=\"shs_sites\" value='".$log_site."'>";
					}
					?>
				<div class='col-md-2 feedfilter shs_bythis'>
					<select placeholder="Choose Trainers" name="shs_bythis" id="shs_bythis"  onchange='get_sharestats()' >
						<option value='all'>All</option>
						<?php
						if(isset($sitetrainer) && count($sitetrainer)>0 && is_array($sitetrainer))
						{
							
							//echo '<optgroup label="Select All Trainers" selected=\'selected\'>';
							foreach($sitetrainer as $key => $value)
							{	//$select = "";
								//$select = "selected='selected'";	
								/*
								$select = "";
								if( in_array($value['id'],$user_array) ){
									$select = "selected='selected'";	
								}
								*/
								?>
								<option    value="<?php echo $value['id'];?>"><?php echo ucfirst($value['user_fname'].' '.$value['user_lname']); ?></option><?php
							}
							//echo '</optgroup>';
						}
						?>	
					</select>
				</div>			
			</div>
			<?php } ?>
			<div class='sh_stats table-responsive' id='sh_stats'>
				<p style='color:#3D8B3D;border:0px solid red;text-align:center;margin-top:25px;font-weight:bold;'>Loading.....</p>
			</div>
		</div>
	</div>
<?php }	



if(!Helper_Common::is_trainer())
	{
					 ?>
					 
					 
                <div class="row infostats">
                  <div class="col-xs-6 col-sm-3">
							<a href="<?php echo URL::base(TRUE)."admin/subscriber/browse"; ?>" title='View Details for Subscribers'>
								<span class="pull-left"><?php echo /*(isset($site_language['Recent Subscribers'])) ? $site_language['Recent Subscribers'] : 'Recent Subscribers';*/ I18n::get('Recent Subscribers'); ?></span><br>
								<span class="pull-right"><i class="fa fa-lg fa-arrow-circle-right"></i></span>
								<span class="dashboard_cnt" ><?php echo $subscriberCount;?></span>
								
								<div class="clearfix"></div>
							</a>
						</div>
						<?php
						//$plan_count = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_gendata','count(*) as totalCount','status_id!=4 and site_id='.$current_site_id);
						
						
						$plan_count = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_gendata','count(*) as totalCount','status_id!=4 and site_id in ('.$site.')');
						if(isset($plan_count[0]['totalCount']) && $plan_count[0]['totalCount']!='') {
							$totalCount = $plan_count[0]['totalCount'];
						} else {
							$totalCount = 0;
						}
						?>
                  <div class="col-xs-6 col-sm-3 panel-green">
							<a href="<?php echo URL::base(TRUE)."admin/workout/browse"; ?>"  title='View Details for Workout Plans'>
								<span class="pull-left"><?php echo /*(isset($site_language['Recent Workouts'])) ? $site_language['Recent Workouts'] : 'Recent Workouts';*/ I18n::get('Recent Workouts'); ?></span>
								<br><span class="pull-right"><i class="fa fa-lg fa-arrow-circle-right"></i></span>
								<span class="dashboard_cnt" ><?php echo $totalCount;?></span>
								<div class="clearfix"></div>
							</a>
                  </div>
                  <div class="col-xs-6 col-sm-3 panel-yellow">
							<a href="#">
								<span class="pull-left"><?php echo /*(isset($site_language['Assigned Workouts'])) ? $site_language['Assigned Workouts'] : 'Assigned Workouts';*/ I18n::get('Assigned Workouts'); ?></span>
								<br><span class="pull-right"><i class="fa fa-lg fa-arrow-circle-right"></i></span>
								<span class="dashboard_cnt" ><?php echo $assignCount;?></span>
                        <div class="clearfix"></div>
                     </a>
                  </div>
						<?php
						echo $site;
$plan_count = Model::instance('Model/admin/user')->get_table_details_by_condtn('wkout_log_gendata','count(*) as totalCount','status_id!=4 and site_id in ('.$site.')'); 
						if(isset($plan_count[0]['totalCount']) && $plan_count[0]['totalCount']!='') {
							$totalCount = $plan_count[0]['totalCount'];
						} else {
							$totalCount = 0;
						}?>
                  <div class="col-xs-6 col-sm-3 panel-red">
							<a href="#">
								<span class="pull-left"><?php echo /*(isset($site_language['Logged Journals'])) ? $site_language['Logged Journals'] : 'Logged Journals';*/ I18n::get('Logged Journals'); ?></span>
								<br><span class="pull-right"><i class="fa fa-lg fa-arrow-circle-right"></i></span>
								<span class="dashboard_cnt" ><?php echo $totalCount;?></span>								
								<div class="clearfix"></div>
                     </a>
                  </div>
               </div>
					 <!-- /.row -->
				<?php
	}
					 ?>
				
				<?php if(Helper_Common::hasAccess('View Activity Feed')) { ?>
				<div class="row" id='af'>
					<div class="col-lg-12">
						<div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-rss fa-fw"></i> <?php echo (isset($site_language['Activity Feed'])) ? $site_language['Activity Feed'] : 'Activity Feed'; ?><button class='btn btn-primary af_filter_btn'  data-toggle="collapse" data-target="#af_filter" >Options</button></h3>
										  
                            </div>
                            <?php
								$current_user_id               = Auth::instance()->get_user()->pk();
								
								$role 	=  Helper_Common::get_role("manager");
								$manager =  Helper_Common::get_role_by_users($role,$site);
								
								$role 	=  Helper_Common::get_role("trainer");
								$trainer =  Helper_Common::get_role_by_users($role,$site);
								
								$role 				  =  Helper_Common::get_role("register");
								$subscriber			  =  Helper_Common::get_role_by_users($role,$site);
	
								
								if(Helper_Common::is_manager() || Helper_Common::is_admin()){
									$userids = array();
									$users = array();
									if(Helper_Common::is_admin()){
										$role 	=  Helper_Common::get_role("admin");
										$adminuser 	=  Helper_Common::get_role_by_users($role,$site);
										if(isset($adminuser) && is_array($adminuser))
											$users = array_merge($users,$adminuser);
									}
									if(isset($manager) && is_array($manager))
										$users = array_merge($users,$manager);
									
									if(isset($trainer) && is_array($trainer))
										$users = array_merge($users,$trainer);
										
									if(isset($subscriber) && is_array($subscriber))
										$users = array_merge($users,$subscriber);
									
									if(isset($users) && is_array($users) && count($users)>0){
										foreach($users as $k=>$v){
											if(!isset($userids[$v["id"]])){
												$userids[$v["id"]] = $v["id"];
											}
										}
									}
									$userids = implode(",",$userids);
								}elseif(Helper_Common::is_trainer()){
									$userids = array();
									$users = ($subscriber)? $subscriber : "";
									if(isset($users) && is_array($users) && count($users)>0){
										foreach($users as $k=>$v){
											if(!isset($userids[$v["id"]])){
												$userids[$v["id"]] = $v["id"];
											}
										}
									}
									$userids = $user->id.",".implode(",",$userids);
								}else{
									$userids = '';
								}
								
								if(Helper_Common::is_trainer()){
									$userids = $current_user_id;
								}
								
								
								//$userids = $current_user_id;
								//print_r($userids); exit;		
								$user_array = explode(",",$userids);
								$offset = 0;
								$limit = 50;
								$filter["fdate"] = date('d/m/Y', strtotime('-1 week', time()));
								$filter["tdate"] = date('d/m/Y', time());
								$var             = $filter["fdate"];
								$date            = str_replace('/', '-', $var);
								$filter["fdate"] = date('Y-m-d', strtotime($date));
								$var             = $filter["tdate"];
								$date            = str_replace('/', '-', $var);
								$filter["tdate"] = date('Y-m-d', strtotime($date));
								//echo "<pre>";print_R($filter);
								$feed_details_all = Model::instance('Model/admin/user')->get_feed_details($userids,$site,$filter,'','');
								$feed_details = Model::instance('Model/admin/user')->get_feed_details($userids,$site,$filter,$limit,$offset);
						?>
						<div class="panel-body">
							<input type='hidden' id='af_limit' value='<?php echo $limit; ?>'>
							<input type='hidden' id='af_all' value='<?php echo count($feed_details_all); ?>'>
							<input type='hidden' id='af_showmore' value='<?php echo $offset; ?>'>
							<input type='hidden' id='af_userids' value='<?php echo $userids; ?>'>
							<input type='hidden' id='tf_userids' value='<?php echo $userids; ?>'>
							<input type='hidden' id='af_site' value='<?php echo $site; ?>'>
							<div class="row padding5 collapse"  id='af_filter' style='border: 0px solid red;background-color:#F5F5F5;margin-top:-25px  ' >
								<div class='col-md-12 '><b>Activity Feed Filters</b></div>
								<?php
									if(!Helper_Common::is_trainer()){
									?>
								<div class='col-md-2 feedfilter '>
<style type='text/css'>
.aamoteactions{
	border:0px solid red;
	margin-top: -2px;
}
.aamoteactions > div > ul{
	overflow-x: hidden;
}
.aamoteactions > div > ul > li .ms-select-all > label > span{
	display: none;
}
.aamoteactions > div > ul > li > label{
	width:0%;
	margin-left: 10px;
}



</style>
									
									<select placeholder="Choose Status" id='users' multiple="true"    class=" bordernone fa-blue aamoteactions" style="width: 100%;"  style="width:350px;" tabindex="4" onchange='updateActityfeed()'>
										<!--option value="">Choose Users</option--><?php //users form-control fullwidth
										if(isset($manager) && count($manager)>0 && is_array($manager)	&& (Helper_Common::is_manager() || Helper_Common::is_admin()))
										{
											echo '<optgroup label="Managers">';
											foreach($manager as $key => $value)
											{
												$select = "";
												if( /*$current_user_id==$value['id']*/ in_array($value['id'],$user_array) ){
													$select = "selected='selected'";	
												}
												?>
												<option <?php echo $select; ?> value="<?php echo $value['id'];?>"><?php echo $value['user_fname'].' '.$value['user_lname']; ?></option><?php
											}
											echo '</optgroup>';
										}
										if(isset($trainer) && count($trainer)>0 && is_array($trainer)	&& (Helper_Common::is_manager() || Helper_Common::is_admin() || Helper_Common::is_trainer() ))
										{
											echo '<optgroup label="Trainers">';
											foreach($trainer as $key => $value)
											{
												$select = "";
												if( /*$current_user_id==$value['id']*/ in_array($value['id'],$user_array) ){
													$select = "selected='selected'";	
												}
												?>
												<option <?php echo $select; ?> value="<?php echo $value['id'];?>"><?php echo $value['user_fname'].' '.$value['user_lname']; ?></option><?php
											}
											echo '</optgroup>';
										}
										if(isset($subscriber) && is_array($subscriber) && count($subscriber)>0	&& (Helper_Common::is_manager() || Helper_Common::is_admin() || Helper_Common::is_trainer() )	)
										{
											echo '<optgroup label="Subscribers">';
											foreach($subscriber as $key => $value)
											{
												$select = "";
												if( /*$current_user_id==$value['id']*/ in_array($value['id'],$user_array) ){
													$select = "selected='selected'";	
												}
												?>
												<option <?php echo $select; ?> value="<?php echo $value['id'];?>"><?php echo $value['user_fname'].' '.$value['user_lname']; ?></option><?php
											}
											echo '</optgroup>';
										}?>
									</select>
									
									
								</div>
								<?php } else{
									echo "<input type='hidden' id='users' value='".$userids."'>";
								}?>
								<div class='col-md-2 feedfilter '>
									<?php
									$feedtype = array();
									$i=0;
									$feedtype[$i]["feed_type"]  = 2;		$feedtype[$i]["feed_title"] = "Workout plan feeds";			$i++;
									$feedtype[$i]["feed_type"]  = 15;	$feedtype[$i]["feed_title"] = "Sample workout plan feeds";	$i++;
									$feedtype[$i]["feed_type"]  = 12;	$feedtype[$i]["feed_title"] = "Shared workout plan feeds";	$i++;
									$feedtype[$i]["feed_type"]  = 13;	$feedtype[$i]["feed_title"] = "Assigned workout plan feeds";$i++;
									$feedtype[$i]["feed_type"]  = 11;	$feedtype[$i]["feed_title"] = "Journal workout plan feeds";	$i++;
									$feedtype[$i]["feed_type"]  = 5;		$feedtype[$i]["feed_title"] = "Exercise record feeds";		$i++;
									$feedtype[$i]["feed_type"]  = '9,16';$feedtype[$i]["feed_title"] = "Image feeds";					$i++;	?>
									<select placeholder="Choose Feed Type" name="a_feedtype" id="a_feedtype" class="form-control amoteactions" onchange='updateActityfeed()'>
										<option value="">Choose Feed Type</option><?php
										foreach($feedtype as $k=>$v){
											echo "<option value='".$v["feed_type"]."'>".$v["feed_title"]."</option>";
										}?>
									</select>
								</div>
								<div class='col-md-2 feedfilter'>
									<select placeholder="Choose Status" name="a_bythis" id="a_bythis" class="form-control amoteactions" onchange='updateActityfeed()' >
										<option value="">Most Recent</option>
										<option value="1">Today</option>
										<option value="2">This Week</option>
										<option value="3">This Month</option>
										<option value="5">This Fortnight</option>
										<option value="4">Custom Date</option>
									</select>
								</div>
								<div class='col-md-2 feedfilter  acusdate'>
									<input type="text" name="a_fromdate" id="a_fromdate" placeholder="Select From Date" data-format="dd/MM/yyyy"  required="true" class="form-control fromdatepicker" onchange='changeby()'>
								</div>
								<div class='col-md-2 feedfilter acusdate'>
									<input type="text" name="a_todate" id="a_todate"   placeholder="Select To Date"  data-format="dd/MM/yyyy"  required="true" class="form-control todatepicker"  onchange='changeby()'>
								</div>
								<div class='col-md-2 feedfilter '>
									<a href="javascript:void(0)" class="btn btn-info" onclick="updateActityfeed()"><?php echo (isset($site_language['Show Feeds'])) ? $site_language['Show Feeds'] : 'Show Feeds'; ?></a>
								</div>
							</div>
								<br>	
							<!-- Filter End -->
							<?php
							?>
							<div class="activityinner" <?php echo ( (isset($feed_details) && count($feed_details)>0) ? 'style="height:262px;overflow-y:scroll"' : ''); ?> >
								<div class="list-group" id='act_feed'>
											<?php
											if(isset($feed_details) && count($feed_details)>0 && !empty($feed_details)) {
												$cnt=0;
												foreach($feed_details as $key => $value) {
													$cnt++;
													echo Helper_Activityfeed::activity_index($value);
												}
											}else{
												echo "<p style='color:#FF0000;border:0px solid red;text-align:center;margin-top:25px;font-weight:bold;'>No data found</p>";
											}
											?>
								</div>
							</div>
								</div><script>
								$(document).ready(function(){
									$(".activityinner").bind("scroll", function(e){ if($(this).scrollTop() + $(this).innerHeight()>=$(this)[0].scrollHeight){ if($("div#act_feed").length){show_more(e);}}});
								});</script>
                  </div>
					</div>
				</div>
				<?php } ?>
			</div>
         <!-- /.container-fluid -->
		</div>
		<!-- /#page-wrapper -->
   </div>
   <!-- /#wrapper -->
<div id="othersModal" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog">
	  <div class="modal-content">
		 <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title">Users</h4>
		 </div>
		 <div class="modal-body">            
					<div class="form-group">
						<div id='listothers' style='height:400px;overflow: auto;overflow-x: hidden;'>
							<div class="row" style='display:none;cursor: pointer' id='viewuser_0' onclic='viewusers(1)'>
								<div class="col-xs-2"></div>
								<div class="col-xs-2" style='border:1px solid #ededed;border-right:none;padding:0px 0px 0px 5px;'><i class="fa fa-user" style="font-size:50px;"></i></div>
								<div class="col-xs-6" style='border:1px solid #ededed;border-left:none;display: table-cell;padding:15px 0px 5px 5px;height:52px;'>User Not found</div>
								<div class="col-xs-2"></div>
							</div>
						</div>
					</div>
		 </div>
		 <div class="modal-footer"><input type="hidden" name="cwkid" id="cwkid">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		 </div>
	  </div>
	</div>
</div>	 

<div id="xrprev-modal" class="modal fade" role="dialog"></div>


	 
<a href="javascript:void(0);" class="showpopup" data-target="#userModal" data-toggle="modal" style="display:none;">&nbsp;</a>
<a href="javascript:void(0);" class="deniedpopup"  data-target="#deniedPermissionModal" data-toggle="modal" style="display:none;">&nbsp;</a>
    <!-- jQuery -->


<?php require_once(APPPATH.'views/templates/admin/workoutdetails.php');?> 
<?php require_once(APPPATH.'views/templates/admin/deniedpermission.php');?>
<?php require_once(APPPATH.'views/pages/Admin/Workout/workout_modals.php');?>

<?php

$session = Session::instance();
if($session->get_once('denied_permission')) { ?>
	<script type="text/javascript">
		$(document).ready(function() {
			$('.deniedpopup').click();
		});
	</script>
<?php } ?>
<script type='text/javascript'>
	$(function()
	{
		if(!$("#trainer_check").val())
		{
			$('#refstatus').multipleSelect({
            selectAllText: '<span class="ckbox">User Status</span>',
			});
		}
		else
		{
			<?php
			if(Helper_Common::is_trainer())
			{
				?>
				$('#refstatus').multipleSelect({
					single: true
				});
				<?php
			}
			else
			{
				?>
				$('#refstatus').multipleSelect({
					selectAllText: '<span class="ckbox">User Status</span>',
				});	
				<?php
			}
			?>
		}
		
		$('#refstatus1').multipleSelect({
			 selectAllText: '<span class="ckbox">User Status</span>',
		});
		  
		$('#users').multipleSelect({
			placeholder: "Choose Users",
			selectAllText: '<span class="ckbox">Choose Users</span>',
		});
		$('[data-name="selectGroup"]').prop("checked", true);
		
		$('#shs_bythis').multipleSelect({
			single: true
		});
		<?php
			if(Helper_Common::is_admin())
			{
				?>
				$('#shs_sites').multipleSelect({
					single: true
				});
				<?php
			}
		?>
		
	});
</script>