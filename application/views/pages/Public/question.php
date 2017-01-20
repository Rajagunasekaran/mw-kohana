<?php echo $header;
$session = Session::instance();
$bg_class = 'bg-class';
$font_class = 'font-class';
?>
<style>
	#public li label { padding-left: 10px; }
	.iradio_square-blue { float: left; max-width: 15%; width: 24px; }
	.rightlabel { float: left; max-width: 85%; }
	.questionlist li { clear: both; }
	.questionlist > li { padding-bottom: 24px; }
	.questionlist { list-style-type: none; padding-left: 0px;  }
	#public h2 { font-size: 24px; }
	.iradio_square-blue { top: 3px; }
	.panel-body.error { border: 1px solid #b94a48; }
	.selectlabel { max-width: 100%; width: 100%; }
	.help-block.with-errors { clear:both; color: #b94a48; }
	textarea { resize: none; }
	@media only screen and (max-width :650px) {
		.continuebut { width: 100%; }
	}
	@media only screen and (max-width :480px) {
		.main-wrapper { padding: 0px; }
	}
	.ui-btn-down-b {
		background: #f5f5f5 none repeat scroll 0 0;
		border: 1px solid #cccccc;
		color: #fff;
		font-weight: bold;
		text-shadow: 0 1px 0 #194b7e;
	}
	.ui-btn-up-b,.ui-btn-hover-b {
    background: #1B9AF7 repeat scroll 0 0;
    border: 1px solid #1B9AF7;
    color: hsl(0, 0%, 100%);
    font-weight: bold;
    text-shadow: 0 1px 0 hsl(210, 67%, 30%);
	}
	.q_title{
		color:#1B9AF7;
		font-weight: bold;
		font-size: 18px;
	}
</style>
<div class="main-wrapper after-nav">
<div id="wrap-index" >
   <!-- Login header nav !-->
   <div class="container" id="public">
      <div class="row">
         <div class="col-sm-12">
            <div class="page-header">
               <?php //print_r($question_data);die(); ?>
               <h2><?php echo __('Please choose the answer for the following Question'); ?>..</h2>
            </div>
            <div class="well well-sm"><?php echo __('Hi'); ?> <?php echo Auth::instance()->get_user()->user_fname; ?>,</div>
         </div>
      </div>
	  <form data-role="none" data-ajax="false" role="form" method="post" action="" id='ques' onsubmit='return validate();'>
      <div class="row">
         <div class="col-sm-12">
               <?php
					$commonQ  			= $common_question;
					$questionmodel      = ORM::factory('admin_questions');
					$cquestionmodel     = ORM::factory('admin_commonquestions');
					$question 			= $questionmodel->getQuestions($siteid);					
					if($commonQ==1){
						$question1      = $cquestionmodel->getQuestions();
					}
					$str = "";	$qno=1;
					$str .= '<div class="panel panel-info">';
					if(isset($question1) && is_array($question1) && count($question1) > 0){
						foreach( $question1 as $k=>$q){
							$error = ($q["isrequired"]==1)?'error_call':'';
							$req = ($q["isrequired"]==1)?'required_call':'';
							
							$options = $questionmodel->getQuestionOptions($q["id"]);
							$str .= "<div class=\"panel-heading\">".$qno++.". ".$q["question"];
							$str .= "</div>";
							$str .= "<div class=\"panel-body $error".$q["id"]."\">
										<ul class=\"questionlist\">";
							$af = $q["answer_field"];
							if($q["isrequired"]){
								$str .="<input type='hidden' value='".(isset($question_data[$q["id"]]['answer']) && !empty($question_data[$q["id"]]['answer']) ? $question_data[$q["id"]]['answer'] : (isset($question_data[$q["id"]]['sqoid']) && count($question_data[$q["id"]]['sqoid']) > 0  ? count($question_data[$q["id"]]['sqoid']) : '' ) )."' class='$req' id='q_".$q["id"]."' style='border:1px solid red;'>";
								$str .="<input type='hidden' value='".($qno-1)."' class='qno' id='qno_".$q["id"]."' >";
							}
							if($af==1){
								$str .= "<li>
								<textarea data-role='none' data-ajax='false' tabindex='".$q["id"]."' placeholder='".$q["placeholder_text"]."' type='text' class=\"form-control inputs \" id='square-radio-".$q["id"]."' name='$af-ans".$q["id"]."' style='width:100%' onkeyup='checkval(this,$af,".$q["id"].")'>".(isset($question_data[$q["id"]]['answer']) && !empty($question_data[$q["id"]]['answer']) ? $question_data[$q["id"]]['answer'] : '' )."</textarea></li>";
							}elseif($af==5){
								$avg = (isset($question_data[$q["id"]]['answer']) && !empty($question_data[$q["id"]]['answer']) ? $question_data[$q["id"]]['answer'] : round(($q["min_val"]+$q["max_val"])/2) );
								$str .= "<li><input data-role='slider' data-ajax='false' name=\"$af-ans".$q["id"]."\" class='slider' id=\"$af-ans".$q["id"]."\" min=\"".$q["min_val"]."\" max=\"".$q["max_val"]."\" value=\"$avg\" type=\"range\" data-highlight=\"true\" data-track-theme=\"b\" data-theme=\"b\" ></li>";
								$str .= "<script type='text/javascript'>
											$(document).on('change','#$af-ans".$q["id"]."',function(){ 
													$('#q_'+".$q["id"].").val(1);
											  });
											</script>";
							}
							
							if($options){
								if($af==2){
									$str .= "<li><div class=\"rightlabel selectlabel\">";
									$str .= "<select data-role='none' data-ajax='false' placeholder='".$q["placeholder_text"]."' id='square-radio-".$q["id"]."' name='$af-ans".$q["id"]."' class='form-control inputs selectpicker ' onchange='checkval(this,$af,".$q["id"].")' >";
									$str .= "<option value=''>".(($q["placeholder_text"])?$q["placeholder_text"]:'Choose')."</option>";
									foreach( $options as $op=>$o){
										$str .= "<option ".(isset($question_data[$q["id"]]['sqoid'][$o["id"]]) && !empty($question_data[$q["id"]]['sqoid'][$o["id"]]) && $question_data[$q["id"]]['sqoid'][$o["id"]] == $o["id"] ? 'selected="selected"' : '' )." value='".$q["id"]."_".$o["id"]."'>";
										$str .= "<label for=\"square-radio-1\">".$o["option"]."</label>";
										$str .= "</option>";
									}
									$str .= "</select>";
									$str .= "</div></li>";
								}elseif($af==3){
									foreach( $options as $op=>$o){
										$str .= "<li>
										<input data-role='none' data-ajax='false'  tabindex='".$q["id"]."_".$o["id"]."' type='checkbox' id='square-radio-".$q["id"]."_".$o["id"]."' ".(isset($question_data[$q["id"]]['sqoid'][$o["id"]]) && !empty($question_data[$q["id"]]['sqoid'][$o["id"]]) && $question_data[$q["id"]]['sqoid'][$o["id"]] == $o["id"] ? "checked" : (isset($question_data[$q["id"]]['answer']) && !empty($question_data[$q["id"]]['answer']) && $question_data[$q["id"]]['answer'] == $o["option"] ? "checked" : "" )  )." name='$af-ans".$q["id"]."[]' value='".$q["id"]."_".$o["id"]."'  onchange='checkval(this,$af,".$q["id"].")' class='inputs ckbox_".$q["id"]."'>
										<div class=\"rightlabel\"><label for=\"square-radio-1\">".$o["option"]."</label></div>
										</li>";
									}
								}elseif($af==4){
									foreach( $options as $op=>$o){
										$str .= "<li>";
										$str .= "<input ".(isset($question_data[$q["id"]]['sqoid'][$o["id"]]) && !empty($question_data[$q["id"]]['sqoid'][$o["id"]]) && $question_data[$q["id"]]['sqoid'][$o["id"]] == $o["id"] ? 'checked' : '' )." data-role='none' data-ajax='false' tabindex='".$q["id"]."_".$o["id"]."' type='radio' id='square-radio-".$q["id"]."_".$o["id"]."' name='$af-ans".$q["id"]."' value='".$q["id"]."_".$o["id"]."' onclick='checkval(this,$af,".$q["id"].")' class='inputs ckbox_".$q["id"]."'>";
										$str .= "<div class=\"rightlabel\"><label for=\"square-radio-1\">".$o["option"]."</label></div></li>";
									}
								}
							}
							
							$str .= "</ul>";
							if($q["isrequired"]==1){
								$str .= "<div class='help-block with-errors'>*".__('required')."</div>";
							}
							$str .="</div>";
						}
					}
					if(isset($question) && is_array($question) && count($question) > 0){
						$qno=($qno>0)?$qno:1;
						foreach( $question as $k=>$q){
							$error = ($q["isrequired"]==1)?'error_call':'';
							$req = ($q["isrequired"]==1)?'required_call':'';
							$options = $questionmodel->getQuestionOptions($q["id"]);
							$str .= "<div class=\"panel-heading\">".$qno++.". ".$q["question"];
							$str .= "</div>";
							$str .= "<div class=\"panel-body $error".$q["id"]."\"><ul class=\"questionlist\">";
							$af = $q["answer_field"];
							if($q["isrequired"]){
								$str .="<input type='hidden' value='".(isset($question_data[$q["id"]]['answer']) && !empty($question_data[$q["id"]]['answer']) ? $question_data[$q["id"]]['answer'] : (isset($question_data[$q["id"]]['sqoid']) && count($question_data[$q["id"]]['sqoid']) > 0  ? count($question_data[$q["id"]]['sqoid']) : '' ) )."' class='$req' id='q_".$q["id"]."' style='border:1px solid red;'>";
								$str .="<input type='hidden' value='".($qno-1)."' class='qno' id='qno_".$q["id"]."' >";
							}
							if($af==1){
								$str .= "<li><textarea data-role='none' data-ajax='false' placeholder='".$q["placeholder_text"]."'  tabindex='".$q["id"]."' type='text' class=\"form-control inputs \" id='square-radio-".$q["id"]."' name='$af-ans".$q["id"]."' value='' style='width:100%' onkeyup='checkval(this,$af,".$q["id"].")'>".(isset($question_data[$q["id"]]['answer']) && !empty($question_data[$q["id"]]['answer']) ? $question_data[$q["id"]]['answer'] : '' )."</textarea></li>";
							}elseif($af==5){
								$avg = (isset($question_data[$q["id"]]['answer']) && !empty($question_data[$q["id"]]['answer']) ? $question_data[$q["id"]]['answer'] : round(($q["min_val"]+$q["max_val"])/2) );
								$str .= "<li><input data-role='slider' data-ajax='false' name=\"$af-ans".$q["id"]."\" class='slider' id=\"$af-ans".$q["id"]."\" min=\"".$q["min_val"]."\" max=\"".$q["max_val"]."\" value=\"$avg\" type=\"range\" data-highlight=\"true\" data-track-theme=\"b\" data-theme=\"b\" ></li>";
								$str .= "<script type='text/javascript'>
											$(document).on('change','#$af-ans".$q["id"]."',function(){ 
													$('#q_'+".$q["id"].").val(1);
											  });
											</script>";
							}
							
							if($options){
								if($af==2){
									$str .= "<li><div class=\"rightlabel selectlabel\">";
									$str .= "<select data-role='none' data-ajax='false' id='square-radio-".$q["id"]."' name='$af-ans".$q["id"]."' class='form-control inputs selectpicker ' onchange='checkval(this,$af,".$q["id"].")' >";
									$str .= "<option value=''>".(($q["placeholder_text"])?$q["placeholder_text"]:'Choose')."</option>";
									foreach( $options as $op=>$o){
										$str .= "<option ".(isset($question_data[$q["id"]]['sqoid'][$o["id"]]) && !empty($question_data[$q["id"]]['sqoid'][$o["id"]]) && $question_data[$q["id"]]['sqoid'][$o["id"]] == $o["id"] ? 'selected="selected"' : '' )." value='".$q["id"]."_".$o["id"]."'>";
										$str .= "<label for=\"square-radio-1\">".$o["option"]."</label>";
										$str .= "</option>";
									}
									$str .= "</select>";
									$str .= "</div></li>";
								}elseif($af==3){
									foreach( $options as $op=>$o){
										$str .= "<li>
										<input data-role='none' data-ajax='false' ".(isset($question_data[$q["id"]]['sqoid'][$o["id"]]) && !empty($question_data[$q["id"]]['sqoid'][$o["id"]]) && $question_data[$q["id"]]['sqoid'][$o["id"]] == $o["id"] ? 'checked' : '' )." tabindex='".$q["id"]."_".$o["id"]."' type='checkbox' id='square-radio-".$q["id"]."_".$o["id"]."' name='$af-ans".$q["id"]."[]' value='".$q["id"]."_".$o["id"]."'  onchange='alert(\"dfdfdf\");checkval(this,$af,".$q["id"].")' class='inputs ckbox_".$q["id"]."'>
										<div class=\"rightlabel\"><label for=\"square-radio-1\">".$o["option"]."</label></div>
										</li>";
									}
								}elseif($af==4){
									foreach( $options as $op=>$o){
										$str .= "<li>";
										$str .= "<input data-role='none' data-ajax='false' ".(isset($question_data[$q["id"]]['sqoid'][$o["id"]]) && !empty($question_data[$q["id"]]['sqoid'][$o["id"]]) && $question_data[$q["id"]]['sqoid'][$o["id"]] == $o["id"] ? 'checked' : '' )."  tabindex='".$q["id"]."_".$o["id"]."' type='radio' id='square-radio-".$q["id"]."_".$o["id"]."' name='$af-ans".$q["id"]."' value='".$q["id"]."_".$o["id"]."' onclick='checkval(this,$af,".$q["id"].")' class='inputs ckbox_".$q["id"]."'>";
										$str .= "<div class=\"rightlabel\"><label for=\"square-radio-1\">".$o["option"]."</label></div></li>";
									}
								}
							}
							
							$str .= "</ul>";
							if($q["isrequired"]==1){
								$str .= "<div class='help-block with-errors'>*".__('required')."</div>";
							}
							
									$str .="</div>";
							
						}
					}
					echo $str;
					?>
               
            </div>
				<center>
					<button type="submit" data-role="none" data-ajax="false" onclick='validate()' class="btn btn-info continuebut" ><?php echo __('Continue'); ?></button>
				</center>
				
         </div>
      </div>
		</form>
   </div>
</div>	
<?php echo $footer;?>
<script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery(":checkbox").change(function(){
			var id = jQuery(this).attr('id');
		   return false;
		});
		
		jQuery('.questionlist input').iCheck({
		  checkboxClass: 'icheckbox_square-blue',
		  radioClass: 'iradio_square-blue',
		  increaseArea: '20%'
		});
		
	});
	function validate(){
		var i=0;
		jQuery(".inputs").each(function() {
			//alert(jQuery(this).attr("type")+"--------"+jQuery(this).attr("id"))
			if (jQuery(this).attr("type")=="checkbox" || jQuery(this).attr("type")=="radio") {
				//alert(jQuery(this).val())
				var d= jQuery(this).val().split("_");
				q = d[0];
				var t = new Array();
				jQuery('.ckbox_'+q).each(function () { 
					if (jQuery(this).prop("checked")) {
						t.push(jQuery(this).prop("checked"));	
					}
				});
				checkinput(t,q);
			}
		});
		var r=0; var str = "";
		jQuery(".required_call").each(function() {
			var sd = this.id.split("_");
			console.log(jQuery(this));
			if(jQuery(this).val()==""){
				//alert(jQuery("#qno_"+sd[1]).val()+"----"+sd+"========="+jQuery(this).val()+"======"+this.id)
				
				str += "<li>";
				str += "Question no "+jQuery("#qno_"+sd[1]).val()+" is required.";
				str += "</li>";
				
				jQuery(".error_call"+sd[1]).addClass("error");
				r++;
			}else{
				jQuery(".error_call"+sd[1]).removeClass("error");
			}
		});
		if(r==0){
			return true;
		}
		if (str!='') {
			jQuery("#errorMessage-modal").modal("show");
			jQuery("#validation-errors").html(str);
		}
		
		return false;
		
	}
	function checkinput(t,q){
		//alert(t+"---"+t.length+"--"+q)
		if (t.length>0) {
			jQuery("#q_"+q).val(1);
		}else{
			jQuery("#q_"+q).val('');
		}
	}
	function checkval(obj,af,id){
		var v = obj.value;
		if (af==1 ) {
			if (v!='') {
				jQuery("#q_"+id).val(1);
			}else{
				jQuery("#q_"+id).val('');
			}
		}else if (af==2 ) {
			if (v!='') {
				jQuery("#q_"+id).val(1);
			}else{
				jQuery("#q_"+id).val('');
			}
		}
	}		
</script>