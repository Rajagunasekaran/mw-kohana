<div id="wrap-index">
  <!-- Login header nav !-->
  <?php echo $topHeader;?>
  <div class="container" id="home">
	<form action="" method="post"  data-ajax="false" data-role="none">
	<div class="row">
		<?php $session = Session::instance();
			if ($session->get('success')): ?>
		  <div class="banner success alert alert-success">
		    <a data-ajax="false" data-role="none" data-ajax="false" data-role="none" href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<?php echo $session->get_once('success') ?>
		  </div>
		 <?php endif ?>
	</div>
	<div class="row">
		<div class="border">
			<div class="col-xs-3 aligncenter">
				<a data-ajax="false" data-role="none" href="<?php echo (isset($parentFolder['parent_folder_id']) ? URL::base(TRUE).'exercise/sampleworkout/'.(!empty($parentFolder['parent_folder_id']) ? $parentFolder['parent_folder_id'] : '') : URL::base(TRUE).'exercise/index/'); ?>">
					<i class="fa fa-caret-left iconsize"></i>
				</a>
			</div>
			<div class="col-xs-6 aligncenter">
				<?php echo (isset($parentFolder['parent_folder_name']) && !empty($parentFolder['parent_folder_name']) ? __('Sample Workout Folder').':<br>'.$parentFolder['parent_folder_name'] : __('Sample Workout Plans'));?>
			</div>
			<div class="col-xs-3" id="save-icon-button">
			</div>
		</div>
	</div>
	<?php if(isset($myworkouts) && count($myworkouts) > 0){ ?>
	<ul class="sTreeBase bgC4 col-xs-12 <?php if(isset($myworkouts) && count($myworkouts) >5){?> scrollwkout <?php } ?>" style="border:1px solid #ededed; <?php if(isset($myworkouts) && count($myworkouts) == 0){?> /*display:none;*/ <?php } ?> <?php if(isset($parentFolder) && !empty($parentFolder)){ ?>margin-top:81px; <?php } ?>" >
		<?php if(isset($parentFolder) && !empty($parentFolder)){ ?>
			<li class="row col-xs-12 bgC4 item_parent_noclick" data-id='itemparent_<?php echo $parentFolder['parent_folder_id'];?>' data-module="item_parent" id="itemparent_<?php echo $parentFolder['parent_folder_id'];?>" style="position: absolute; top: -100px;padding-left:0px;padding-right:0px;">
				<div class="row">
					<div class="mobpadding">
						<a data-ajax='false' data-role="none" class="foldericon" href="<?php echo (!empty($parentFolder) ? URL::base(TRUE).'exercise/sampleworkout/'.(!empty($parentFolder['parent_folder_id']) ? $parentFolder['parent_folder_id'] : '') : URL::base(TRUE).'exercise/index/'); ?>">
						<div class="border full">
							<div class="colorchoosen col-xs-2 alignright">	
								<i class="fa fa-folder-open-o"></i>
							</div>
							<div class="col-xs-7 editrecordlist">
								<?php echo __('Parent Folder'); ?>:<br><span><?php echo (isset($parentFolder['parent_folder_name']) && !empty($parentFolder['parent_folder_name']) ? $parentFolder['parent_folder_name'] : __('Sample Workout Plans'));?></span>
							</div>
							<div class="col-xs-3" id="save-icon-button"></div>
						</div>
						</a>
					</div>
				</div>
			</li>
		<?php } ?>
	<?php if(isset($myworkouts) && count($myworkouts) > 0){ 
			$border = 0;
			foreach($myworkouts as $keys => $values){
				$order = $keys+1;
				if(isset($values['wkout_sample_id']) && ($values['wkout_sample_id'] != '0' )){
				
	?>
			<li class="bgC4" data-module="item_sample_workout">
				<div class="row item_sample_workout_click">
					<div class="mobpadding">
						<div class="border full">
							<div class="row-pad editoption col-xs-9" style="cursor:pointer" onclick="getworkoutpreview('<?php echo $values['wkout_sample_id'];?>');">
								<div class="colorchoosen col-xs-2">
									<i class="colorcenternew glyphicon <?php echo $values['color_title'];?>"></i>
								</div>
								<div class="titlechoosen col-xs-9 wrapword">
									<div><?php echo $values['wkout_title'] ;?></div>
									<div class="wkoutfocus"><?php echo ucfirst($values['wkout_focus']);?></div>
								</div>
							</div>
							<div class="iconfont col-xs-3">
								<i class="btn fa fa-ellipsis-h iconsize" onclick="getTemplateOfWorkoutAction('<?php echo $values['wkout_sample_id'];?>','<?php echo $values['wksid'];?>','<?php echo addslashes($values['wkout_title']) ;?>');"></i>
							</div>
						</div>
					</div>
				</div>
			</li>
	<?php  
			$border++;	}else{ 
	?>		
			<li class="bgC4">
				<div class="row">
					<div class="mobpadding">
						<div class="border full">
							<div class="row-pad editoption col-xs-9" style="cursor:pointer">
								<a data-ajax="false" data-role="none" href="<?php echo URL::base(TRUE).'exercise/sampleworkout/'.$values['folder_id']; ?>">
									<div class="colorchoosen col-xs-2 alignright">
										<i class="fa fa-folder-o"></i>
									</div>
									<div class="foldertitle col-xs-9">
											<?php echo ucfirst($values['folder_title'])."<br>";?>
											<span class="navimgdet3"><?php echo(($values['totalRecords'] > 0 )? (($values['totalRecords'] > 1 ) ? __('Records').' : <span class="itemfolder_'.$values['folder_id'].'_'.$values['wksid'].'_'.$order.'">'.$values['totalRecords'].'</span>' : __('Record').' : <span class="itemfolder_'.$values['folder_id'].'_'.$values['wksid'].'_'.$order.'">'.$values['totalRecords'].'</span>') : __('Record').' : <span class="itemfolder_'.$values['folder_id'].'_'.$values['wksid'].'_'.$order.'">0</span>');?></span>
									</div>
								</a>
							</div>
							<div class="iconfont col-xs-3">
								<button data-ajax="false" data-role="none" name="f_method" class="btn" onclick="$('form').submit();" value="copy_folder_<?php echo $values['folder_id'];?>"><i class="fa fa-files-o iconsize"></i></button>
							</div>
						</div>
					</div>
				</div>
			</li>
	<?php 		}
			} 
		}
	?>
	</ul>
	<?php }else{ ?>
		<div class="row">
			<div class="border full">
				<div class="col-xs-3 aligncenter">
				</div>
				<div class="col-xs-6 aligncenter">
					<?php echo __('No records Found'); ?>!!!
				</div>
				<div class="col-xs-3">
				</div>
			</div>
		</div>
	<?php } ?>
	<br>
	<input type="hidden" value="<?php echo $parentFolderId;?>" name="parent_folder_id"/>
	 </form>
  </div>
  <div id="myModal" class="modal fade" role="dialog"></div>
  <div id="FolderModal" class="modal fade" role="dialog"></div>
  <div id="myOptionsModalExerciseRecord" class="modal fade bs-example-modal-sm" role="dialog" data-keyboard="false" data-backdrop="static"></div>  