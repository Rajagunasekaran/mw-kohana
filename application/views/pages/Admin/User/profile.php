	<!--- Top nav && left nav--->
	<?php echo $topnav.$leftnav;?>
	<!--- Top nav && left nav --->
      <!-- Content Wrapper. Contains page content -->
<?php
echo "\n"; echo HTML::style('assets/css/pages/admin/workout_plan.css');
echo "\n"; echo HTML::style('assets/css/pages/admin/imgupload.css');
echo "\n"; echo HTML::style("assets/plugins/cropper/dist/cropper.min.css");
echo "\n"; echo HTML::style("assets/plugins/cropper/demo/css/main.css"); 
echo "\n";echo HTML::script("assets/plugins/cropper/dist/cropper.min.js"); 
echo "\n"; echo HTML::script("assets/plugins/cropper/demo/js/imglib-main.js");
?>	
<link rel="stylesheet" href="http://awesome-bootstrap-checkbox.okendoken.com/demo/build.css"/>


<style type="text/css">
	* {
  .border-radius(0) !important;
}

#field {
    margin-bottom:20px;
}
</style>
<?php
$usermodel = ORM::factory('admin_user');
$datapost = array();
if(isset($_POST) && is_array($_POST) && count($_POST)>0){
	$_POST["profile_img"] = ($_POST["trainer_profile_image"])?$_POST["trainer_profile_image"]:'';
	$getImg = $usermodel->get_users_profile_image($_POST["trainer_profile_image"]);
	$img = URL::base(TRUE).'assets/img/user_placeholder.png';
	if(file_exists($getImg["img_url"])){
		$img = URL::base(TRUE).$getImg["img_url"];
	}
	$profileimg = $img;
	$datapost = $_POST;	
}else{
	$datapost = $profiledata;	
}
?>
<div id="mypopupModal" class="modal fade" role="dialog"></div>	
<?php
echo $imglibrary;
echo $imgeditor2; ?>

      <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            <?php $session = Session::instance();
							if($session->get('current_site_name')!=''){echo '"'.$session->get('current_site_name').'"';}?> <?php echo (isset($site_language['Update'])) ? $site_language['Update'] : 'Update'; ?> <?php echo (isset($site_language['User'])) ? $site_language['User'] : 'User'; ?>
                        </h1>
                        <ol class="breadcrumb">
                            <li>
                                <i class="fa fa-dashboard"></i>  <a href="<?php echo URL::base().'admin/index'; ?>"><?php echo (isset($site_language['Dashboard'])) ? $site_language['Dashboard'] : 'Dashboard'; ?></a>
                            </li>
									  <li>
                                  <a href="<?php echo URL::base().'admin/trainer/trainer_profile'; ?>"><?php echo (isset($site_language['Trainer Profile'])) ? $site_language['Trainer Profile'] : 'Trainer Profile'; ?></a>
                            </li>
                            <li class="active">
                                <i class="fa fa-edit"></i> <?php echo (isset($site_language['Update'])) ? $site_language['Update'] : 'Update'; ?> <?php echo (isset($site_language['User'])) ? $site_language['User'] : 'User'; ?>
                            </li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->
				<?php if(isset($errors) && count($errors)>0) { 
				$labelArray = array(
									'firstname'			=> 'First Name',
									'lastname'			=> 'Surname',
									'background'		=> 'Email or mobile number',
									'qualifications'	=> 'qualifications',
									'achievements'		=> 'achievements',
									'specialties'		=> 'specialties',
									'profile_img'		=> 'Profile Image'
								);
				?>
				<div class="row">
					<div class="col-lg-12">
						<div class="alert alert-danger">
						  <?php
						  //print_R($errors);
						  foreach($errors as $key => $value) { 
							//$msg = str_replace($key,$labelArray[$key],$value);
							?>
							<i class="fa fa-exclamation-triangle"></i><span><?php echo ucfirst($value); ?></span>
						  <?php } ?>
						</div>
					</div>
				</div>
				<?php } 
				if(isset($success) && $success!='') {  ?>
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
						
                        <form role="form" method="post" action="" id='profile_form' name='profile_form'>
							<div class="container-fluid">
								<div class="row">
									<div class="col-lg-6">
										<div class="form-group">
											<label><?php echo (isset($site_language['First Name'])) ? $site_language['First Name'] : 'First Name'; ?></label>
											<input type="text" class="form-control" value="<?php if(isset($datapost['firstname']) && $datapost['firstname']!='') { echo $datapost['firstname']; } ?>" name="firstname">
										</div>
									</div>
									<div class="col-lg-6">
										<div class="form-group">
											<label><?php echo (isset($site_language['Last Name'])) ? $site_language['Last Name'] : 'Last Name'; ?></label>
											<input type="text" class="form-control" value="<?php if(isset($datapost['lastname']) && $datapost['lastname']!='') { echo $datapost['lastname']; } ?>" name="lastname">
										</div>
									</div>
								</div>
									
								<div class="row">
									<div class="col-lg-12">
										<div class="form-group">
											<label><?php echo (isset($site_language['Business'])) ? $site_language['Business'] : 'Business'; ?></label>
											<input type="text" class="form-control" value="<?php if(isset($datapost['business']) && $datapost['business']!='') { echo $datapost['business']; } ?>" name="business" id="business" >
										</div>
									</div>
								</div>
								
								
								
								<!-- Profile Img -->
								
								<div class="row">
									<div class="col-lg-12">
										<div class="form-group">
											<label><?php echo (isset($site_language['Profile Image'])) ? $site_language['Profile Image'] : 'Profile Image'; ?></label>
											<!--input type="file"  value="" name="profile_img"-->
											
											<a href="javascript:void(0);" id="btn_userimgedit" class="pull-left btn btn-default edit-userimg cboxElement" style="width:100%" data-role="none" data-ajax="false">
												<img src="<?php echo ($profileimg)?$profileimg:'';?>" id="profile_userimg" date-imgid="<?php echo (isset($datapost["profile_img"]) && $datapost["profile_img"]!='')?$datapost["profile_img"]:''; ?>" class="prof-img">
												<div class="img-placeholder inactivedatacol">Click image to modify</div>
											</a>
											
										</div>
									</div>
								</div>
								<input type='hidden' id='trainer_profile_image' name='trainer_profile_image' value="<?php echo (isset($datapost["profile_img"]) && $datapost["profile_img"]!='')?$datapost["profile_img"]:'';  ?>">
								<!-- Profile Img -->
								
								
								
								
								
								<div class="row">
									<div class="col-lg-12">
										<div class="form-group">
											<label><?php echo (isset($site_language['Background'])) ? $site_language['Background'] : 'Background'; ?></label>
											<input type="text" class="form-control" value="<?php if(isset($datapost['background']) && $datapost['background']!='') { echo $datapost['background']; } ?>" name="background" id="background" >
										</div>
									</div>
								</div>
								
								
								
								<div class="row">
									<div class="col-lg-12">
										<div class="form-group">
											<label><?php echo (isset($site_language['Qualifications'])) ? $site_language['Qualifications'] : 'Qualifications'; ?></label>
											<?php
											//print_r($datapost["qualifications"]);
											?>
											<div id="qualifications_field">
												<?php
												$q_cnt = 1;
												if(isset($datapost["qualifications"]) && is_array($datapost["qualifications"]) && count($datapost["qualifications"])>0)
												{
													$i=0;	$q_cnt = count($datapost["qualifications"]);
													foreach($datapost["qualifications"] as $k=>$v)
													{
														$i++;
														
														?>
														<input autocomplete="off" value='<?php echo $v; ?>' class="form-control input-sm" id="qualifications_field<?php echo $i; ?>" name="qualifications[]" type="text" data-items="8" style='width:98%'  />
														<?php
														if(count($datapost["qualifications"])==$i){
															?>
															<button id="b1" class="btn add-more-qualifications" type="button" style='margin-left: 98%;margin-top: -50px'>+</button>
															<?php
														}
														else
														{
															?>
															<button id="qualifications_remove<?php echo $i; ?>" class="btn btn-danger qualifications_remove-me" type="button" style='margin-left: 98%;margin-top: -50px'>-</button>
															<?php
														}
														?>
														<div id="qualifications_field"></div>
														<?php
													}
												}
												else
												{	
													?>
													<input autocomplete="off" class="form-control input-sm" id="qualifications_field1" name="qualifications[]" type="text" data-items="8" style='width:98%'/>
													<button id="b1" class="btn add-more-qualifications" type="button" style='margin-left: 98%;margin-top: -50px'>+</button>
													<?php
													
												}
												?>
												<input type='hidden' id='q_next' value="<?php echo $q_cnt; ?>" autocomplete='OFF' >
												
											</div>
                
										</div>
									</div>
								</div>
								
								<div class="row">
									<div class="col-lg-12">
										<div class="form-group">
											<label><?php echo (isset($site_language['Achievements'])) ? $site_language['Achievements'] : 'Achievements'; ?></label>
											
											<div id="achievements_field">
												<?php
												$a_cnt = 1;
												if(isset($datapost["achievements"]) && is_array($datapost["achievements"]) && count($datapost["achievements"])>0)
												{
													$i=0;	$a_cnt = count($datapost["achievements"]);
													foreach($datapost["achievements"] as $k=>$v)
													{
														$i++;
														
														?>
														<input autocomplete="off" value='<?php echo $v; ?>' class="form-control input-sm" id="achievements_field<?php echo $i; ?>" name="achievements[]" type="text" data-items="8" style='width:98%'  />
														<?php
														if(count($datapost["achievements"])==$i){
															?>
															<button id="b1" class="btn add-more-achievements" type="button" style='margin-left: 98%;margin-top: -50px'>+</button>
															<?php
														}
														else
														{
															?>
															<button id="achievements_remove<?php echo $i; ?>" class="btn btn-danger achievements_remove-me" type="button" style='margin-left: 98%;margin-top: -50px'>-</button>
															<?php
														}
														?>
														<div id="achievements_field"></div>
														<?php
													}
												}
												else
												{	
													?>
													<input autocomplete="off" class="form-control input-sm" id="achievements_field1" name="achievements[]" type="text" data-items="8" style='width:98%'/>
													<button id="b1" class="btn add-more-achievements" type="button" style='margin-left: 98%;margin-top: -50px'>+</button>
													<?php
													
												}
												?>
												<input type='hidden' id='a_next' value="<?php echo $a_cnt; ?>" autocomplete='OFF' >
											</div>
                
										</div>
									</div>
								</div>
								
								
								<div class="row">
									<div class="col-lg-12">
										<div class="form-group">
											<label><?php echo (isset($site_language['Specialties'])) ? $site_language['Specialties'] : 'Specialties'; ?></label>
											
											<div class='row'>
												<div class="col-xs-12">
													<?php
													if(isset($specialties)){
														foreach($specialties as $k=>$v){
															$checked = '';
															if(isset($datapost["specialties"]) && in_array($k,$datapost["specialties"])){
																$checked = "checked";
															}
															
															?>
															<div class="col-sm-2">
																<div class="checkbox checkbox-primary">
																	<input type="checkbox" <?php echo $checked; ?>   name="specialties[]" value='<?php echo $k; ?>'>
																	<label for="checkbox2"><?php echo $v; ?></label>
																</div>
															</div>
															<?php
														}
													}
													?>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12">
										<div class="form-group">
											<label><?php echo (isset($site_language['Other Specialties'])) ? $site_language['Other Specialties'] : 'Other Specialties'; ?></label>
											<div id="otherspecialties_field">
												<?php
												$o_cnt = 1;
												if(isset($datapost["otherspecialties"]) && is_array($datapost["otherspecialties"]) && count($datapost["otherspecialties"])>0)
												{
													$i=0;	$o_cnt = count($datapost["otherspecialties"]);
													foreach($datapost["otherspecialties"] as $k=>$v)
													{
														$i++;
														
														?>
														<input autocomplete="off" value='<?php echo $v; ?>' class="form-control input-sm" id="otherspecialties_field<?php echo $i; ?>" name="otherspecialties[]" type="text" data-items="8" style='width:98%'  />
														<?php
														if(count($datapost["otherspecialties"])==$i){
															?>
															<button id="b1" class="btn add-more-otherspecialties" type="button" style='margin-left: 98%;margin-top: -50px'>+</button>
															<?php
														}
														else
														{
															?>
															<button id="otherspecialties_remove<?php echo $i; ?>" class="btn btn-danger otherspecialties_remove-me" type="button" style='margin-left: 98%;margin-top: -50px'>-</button>
															<?php
														}
														?>
														<div id="otherspecialties_field"></div>
														<?php
													}
												}
												else
												{	
													?>
													<input autocomplete="off" class="form-control input-sm" id="otherspecialties_field1" name="otherspecialties[]" type="text" data-items="8" style='width:98%'/>
													<button id="b1" class="btn add-more-otherspecialties" type="button" style='margin-left: 98%;margin-top: -50px'>+</button>
													<?php
													
												}
												?>
												<input type='hidden' id='o_next' value="<?php echo $o_cnt; ?>" autocomplete='OFF' >
											</div>
										</div>
									</div>
								</div> 
                                <div class="row">
									<div class="col-lg-12">
										<button type="submit"  name="submit" name="createuser" class="btn btn-default"><?php echo (isset($site_language['Save'])) ? $site_language['Save'] : 'Save'; ?></button>										
									</div>
								</div>
							</div>
                        </form>
					</div>
                    
                </div>
                <!-- /.row -->

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
<script type="text/javascript">
$(document).ready(function(){
	qualification();
	achievements();
	otherspecialties();
	$('.qualifications_remove-me').click(function(e){
		e.preventDefault();
		if(confirm('Are you sure want remove this input?')){
			var fieldNum = this.id.charAt(this.id.length-1);
			var fieldID = "#qualifications_field" + fieldNum;
			$(this).remove();
			$(fieldID).remove();
			$("#q_next").val(fieldNum);
		}
	});
	
	$('.achievements_remove-me').click(function(e){
		if(confirm('Are you sure want remove this input?')){
			e.preventDefault();
			var fieldNum = this.id.charAt(this.id.length-1);
			var fieldID = "#achievements_field" + fieldNum;
			$(this).remove();
			$(fieldID).remove();
			$("#a_next").val(fieldNum);
		}
	});
	$('.otherspecialties_remove-me').click(function(e){
		e.preventDefault();
		if(confirm('Are you sure want remove this input?')){
			var fieldNum = this.id.charAt(this.id.length-1);
			var fieldID = "#otherspecialties_field" + fieldNum;
			$(this).remove();
			$(fieldID).remove();
			$("#o_next").val(fieldNum);
		}
	});
});
function qualification(){
	var next = parseInt($("#q_next").val());
	$(".add-more-qualifications").click(function(e){
		e.preventDefault();
		var fieldcount = $("#q_next").val();
		if($('#qualifications_field'+fieldcount).val() !=''){
			var addto = "#qualifications_field" + next;
			var addRemove = "#qualifications_field" + (next);
			next = next + 1;
			var newIn = '<input autocomplete="off" class="input-sm form-control" id="qualifications_field' + next + '" name="qualifications[]" type="text" style=\'width:98%\'>';
			var newInput = $(newIn);
			var removeBtn = '<button id="qualifications_remove' + (next - 1) + '" class="btn btn-danger qualifications_remove-me"  style=\'margin-left: 98%;margin-top: -50px\'>-</button></div><div id="qualifications_field">';
			var removeButton = $(removeBtn);
			$(addto).after(newInput);
			$(addRemove).after(removeButton);
			$("#qualifications_field" + next).attr('data-source',$(addto).attr('data-source'));
			$("#count").val(next);  
			$('.qualifications_remove-me').click(function(e){
				e.preventDefault();
				var fieldNum = this.id.charAt(this.id.length-1);
				var fieldID = "#qualifications_field" + fieldNum;
				//console.log(this.id.length+"---------------------this.id.length")
				//console.log(fieldID+"---------------"+fieldNum+"---------------------fieldID-------fieldNum")
				$(this).remove();
				$(fieldID).remove();
				$("#q_next").val(fieldNum);
			});
			$("#q_next").val(next);
		}else{
			alert('Please enter the input');
		}
   });
}
function achievements(){
	var next = parseInt($("#a_next").val());
   $(".add-more-achievements").click(function(e){
		e.preventDefault();
		var fieldcount = $("#a_next").val();
		if($('#achievements_field'+fieldcount).val() !=''){
			var addto = "#achievements_field" + next;
			var addRemove = "#achievements_field" + (next);
			next = next + 1;
			var newIn = '<input autocomplete="off" class="input-sm form-control" id="achievements_field' + next + '" name="achievements[]" type="text" style=\'width:98%\'>';
			var newInput = $(newIn);
			var removeBtn = '<button id="achievements_remove' + (next - 1) + '" class="btn btn-danger achievements_remove-me" style=\'margin-left: 98%;margin-top: -50px\'>-</button></div><div id="achievements_field" >';
			var removeButton = $(removeBtn);
			$(addto).after(newInput);
			$(addRemove).after(removeButton);
			$("#achievements_field" + next).attr('data-source',$(addto).attr('data-source'));
			$("#count").val(next);  
			$('.achievements_remove-me').click(function(e){
				e.preventDefault();
				var fieldNum = this.id.charAt(this.id.length-1);
				var fieldID = "#achievements_field" + fieldNum;
				$(this).remove();
				$(fieldID).remove();
				$("#a_next").val(fieldNum);
			});
			$("#a_next").val(next);
		}else{
			alert('Please enter the input');
		}
   });
}
function otherspecialties(){
	var next = parseInt($("#o_next").val());
   $(".add-more-otherspecialties").click(function(e){
		e.preventDefault();
		var fieldcount = $("#o_next").val();
		if($('#otherspecialties_field'+fieldcount).val() !=''){
			var addto = "#otherspecialties_field" + next;
			var addRemove = "#otherspecialties_field" + (next);
			next = next + 1;
			var newIn = '<input autocomplete="off" class="input-sm form-control" id="otherspecialties_field' + next + '" name="otherspecialties[]" type="text" style=\'width:98%\'>';
			var newInput = $(newIn);
			var removeBtn = '<button id="otherspecialties_remove' + (next - 1) + '" class="btn btn-danger otherspecialties_remove-me" style=\'margin-left: 98%;margin-top: -50px\'>-</button></div><div id="otherspecialties_field" >';
			var removeButton = $(removeBtn);
			$(addto).after(newInput);
			$(addRemove).after(removeButton);
			$("#otherspecialties_field" + next).attr('data-source',$(addto).attr('data-source'));
			$("#count").val(next);  
			$('.otherspecialties_remove-me').click(function(e){
				e.preventDefault();
				var fieldNum = this.id.charAt(this.id.length-1);
				var fieldID = "#otherspecialties_field" + fieldNum;
				$(this).remove();
				$(fieldID).remove();
				$("#o_next").val(fieldNum);
			});
			$("#o_next").val(next);
		}else{
			alert('Please enter the input');
		}
   });
}
</script>
</body>