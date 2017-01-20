<!--- Top nav && left nav--->
<?php echo $topnav.$leftnav;?>
<!--- Top nav && left nav --->
<!-- Content Wrapper. Contains page content -->
<div id="page-wrapper">
	<div class="container-fluid">
		<!-- Page Heading -->
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Common Questions</h1>
				<ol class="breadcrumb">
					<li><i class="fa fa-dashboard"></i>  <a href="<?php echo URL::base().'admin/index'; ?>">Dashboard</a></li>
					<li class="active"><i class="fa fa-question-circle"></i> Questions</li>
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
	<?php $session = Session::instance();
		if ($session->get('success')): ?>
	  <div class="banner success" style="text-align:center;color:green;">
		<?php echo $session->get_once('success') ?>
	  </div>
	 <?php endif ?>
</div>
		
<?php /*************************************************************************************************/ ?>
<div class="row">
	<div class="col-lg-12">
		<h2 class="col-xs-6 col-sm-6 col-lg-6 no-margin-top questionlisttitle"><?php echo (isset($site_language['Common Questions List'])) ? $site_language['Common Questions List'] : 'Common Questions List'; ?></h2>
		<div class="col-xs-6 col-sm-6 col-lg-6 prev-quest">
			<div class="row">
				
				<div class="col-xs-12 prev-quest-btn">
					<?php
					/*
					if(isset($questions) && is_array($questions) && count($questions)>0){ ?>
					<a class="btn btn-default" href="javascript:void(0);" onclick='previewall()' style="float:left;">
					<i class="fa fa-eye"></i>
					<?php echo (isset($site_language['Preview Questions'])) ? $site_language['Preview Questions'] : 'Preview'; ?></a>
					<?php }
					*/
					?>
					<a class="btn btn-default" href="javascript:void(0);" onclick='$("#questionsModal").modal("show");$("#sqid").val("");$("#questions").val("");' style="float:right;">
					<i class="fa fa-plus"></i>
					<?php echo (isset($site_language['Add Questions'])) ? $site_language['Add Questions'] : 'Questions'; ?></a>
				</div>
			</div>
			
		</div>
		<div class="table-responsive col-sm-12 col-lg-12">
			<?php if(isset($questions) && is_array($questions) && count($questions)>0){ ?>
			<table class="table table-bordered table-hover table-striped">
				<thead>
					<tr>
						<th style='text-align:center' class='chkbox-header' ><input type="checkbox" name="qselectall" id='qselectall' /></th>
						<th style='text-align:center;width:60%'><?php echo (isset($site_language['Site Question'])) ? $site_language['Site Question'] : 'SiteQuestion'; ?></th>
						<th style='text-align:center;'><?php echo (isset($site_language['Answer Field'])) ? $site_language['Answer Field'] : 'Answer Field'; ?></th>
						<!--th style='text-align:center'><?php echo (isset($site_language['Site qpreview'])) ? $site_language['Site qpreview'] : 'Question Preview'; ?></th-->
						<th style='text-align:center'><?php echo (isset($site_language['Site qreq'])) ? $site_language['Site qreq'] : 'Required'; ?></th>
						<th style='text-align:center'><?php echo (isset($site_language['Site qstatus'])) ? $site_language['Site qstatus'] : 'Status'; ?></th>
						<th style='text-align:center'><?php echo (isset($site_language['Site qadded'])) ? $site_language['Site qadded'] : 'Added'; ?></th>
						
						<th style='text-align:center'><?php echo (isset($site_language['Site qmodified'])) ? $site_language['Site qmodified'] : 'Modified'; ?></th>
						
						<th style='text-align:center'><?php echo (isset($site_language['Action'])) ? $site_language['Action'] : 'Action'; ?></th>
					</tr>
				</thead>
				<tbody id='tabledrop'>
					<?php
					
						foreach($questions as $k=>$value){
							?>
							<tr id="row-<?php echo $value['id'];?>" class="bgC4 item_workout_noclick" data-module="item_workout" data-id='question_<?php echo $value['id'];?>'  style='cursor:all-scroll;'>
								<td class='tabl-chkbox checkselect' style='text-align:center'>
									<input type="checkbox" name="qselect[]" id="qselect[]" class="qselect" value="<?php echo $value['id'];?>" />
								</td>
								<td id='q_<?php echo $value['id'];?>'><?php echo $value["question"]; ?></td>
								
								<td><?php
								if($value["answer_field"]==1)
									echo "Text Box";
								elseif($value["answer_field"]==2)
									echo "Select Box";
								elseif($value["answer_field"]==3)
									echo "Check Box";
								elseif($value["answer_field"]==4)
									echo "Radio Button";
								elseif($value["answer_field"]==5)
									echo "Input Slider";
								?></td>
								
								
								<!--td  align="center"><a href='javascript:void(0);' onclick="preview(<?php echo $value['id']; ?>)">Preview</a></td-->
								
								<td  align="center">
									<input class="questionreq" id="mybutton" type="checkbox" data-tt-size="big" data-tt-palette="blue" value="<?php echo $value['id']."_".$value['isrequired']; ?>" <?php if($value['isrequired']==1	){?>checked='checked'<?php } ?> >
								</td>
								<td  align="center">
									<input class="questionreq1" id="mybutton1" type="checkbox" data-tt-size="big" data-tt-palette="blue" value="<?php echo $value['id']; ?>" <?php if($value['status']==0){?>checked='checked'<?php } ?>>
								</td>
								
								<td class="dateformatted" align="center"><?php echo date("j M Y",strtotime($value['added']));?></td>
								<td class="dateformatted" align="center"><?php echo date("j M Y",strtotime($value['modified']));?></td>
								<td >
									<input type="hidden" name="placeholder_text<?php echo $value['id'];?>" id="placeholder_text<?php echo $value['id'];?>" value="<?php echo ($value["answer_field"]==1 || $value["answer_field"]==2)?$value['placeholder_text']:'';?>" />
									<input type="hidden" name="answer_field<?php echo $value['id'];?>" id="answer_field<?php echo $value['id'];?>" value="<?php echo $value['answer_field'];?>" />
									<input type="hidden" name="isrequired<?php echo $value['id'];?>" id="isrequired<?php echo $value['id'];?>" value="<?php echo $value['isrequired'];?>" />
									<input type="hidden" name="min_val<?php echo $value['id'];?>" id="min_val<?php echo $value['id'];?>" value="<?php echo $value['min_val'];?>" />
									<input type="hidden" name="max_val<?php echo $value['id'];?>" id="max_val<?php echo $value['id'];?>" value="<?php echo $value['max_val'];?>" />
									<select name="qaction[]" id="<?php echo $value['id'];?>" class="qaction selectAction" onchange="goto_qaction('<?php echo $value['id'];?>',this.value)" >
										<option value="">select an option</option>
										<option value="edit">Edit</option>
										<?php if($value['answer_field']!=5 && $value['answer_field']!=1){ ?>
										<option value="option">Add / Edit Options</option>
										<?php } ?>
										<option value="delete">Delete</option>
									</select>
								</td><td class='tabl-chkbox checkselect tab-panel-draggable' style='text-align:center'>
									<i class="fa fa-bars tab-panel-draggable"></i></td>								
							</tr>
							<?php
							$sequence = $value["sequence"];
						}
					?>
				</tbody>
			</table>
			<div class="exercise_tbl_pg" > <?php echo $pagination; ?> </div>
			<?php
		}else{
				echo "No questions found....";
				$sequence = 0;
			}?>
			<input type="hidden" id='qseq' value='<?php echo $sequence;?>'>
		</div>
	</div>
</div>
<?php /*************************************************************************************************/ ?>			
	</div>
</div>


<?php require_once(APPPATH.'views/pages/Admin/Commonquestions/questions_modals.php');?>    
