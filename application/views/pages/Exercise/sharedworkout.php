<div id="wrap-index">
  <!-- Login header nav !-->
  <?php echo $topHeader;?>
  <div class="container" id="home">
	<form action="" method="post" id="otherworkoutForm">
	<div class="row">
		<?php $session = Session::instance();
			if ($session->get('success')): ?>
		  <div class="banner success alert alert-success">
		    <a data-ajax="false" data-role="none" href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<?php echo $session->get_once('success') ?>
		  </div>
		 <?php endif ?>
	</div>
	<div class="row">
		<div class="border">
			<div class="col-xs-3 aligncenter">
				<a data-ajax="false" data-role="none" href="<?php echo (!empty($parentFolder) ? URL::base(TRUE).'exercise/sharedworkout/'.(!empty($parentFolder['parent_folder_id']) ? $parentFolder['parent_folder_id'] : '') : URL::base(TRUE).'exercise/index/'); ?>">
					<i class="fa fa-caret-left iconsize"></i>
				</a>
			</div>
			<div class="col-xs-6 aligncenter">
				<?php echo (isset($parentFolder['parent_folder_name']) && !empty($parentFolder['parent_folder_name']) ? __('Workout Folder').':<br>'.$parentFolder['parent_folder_name'] : __('Shared Workout Plans'));?>
			</div>
			<div class="col-xs-3" id="save-icon-button">
			</div>
		</div>
	</div>
	<?php if(isset($parentFolder) && !empty($parentFolder)){ ?>
		<div class="row">
			<div class="mobpadding">
				<a data-ajax="false" data-role="none" class="foldericon" href="<?php echo URL::base(TRUE).'exercise/sharedworkout'; ?>">
				<div class="border full">
					<div class="col-xs-1"></div>
					<div class="colorchoosen col-xs-2">
						<i class="fa fa-folder-open-o"></i>
					</div>
					<div class="col-xs-5 editrecordlist">
						<?php echo __('Parent Folder'); ?>:<br><span><?php echo __('Shared Workout Plans'); ?></span>
					</div>
					<div class="col-xs-3" id="save-icon-button">
					</div>
				</div>
				</a>
			</div>
		</div>
		<br>
	<?php } ?>
	<?php if(isset($myworkouts) && count($myworkouts) > 0){ 
			$border = 0;
			foreach($myworkouts as $keys => $values){
				$userimageid 	= Helper_Common::profile_photo($values['userid']);
				if(isset($values['wkout_share_id']) && ($values['wkout_share_id'] != '0' )){
	?>
				<div class="row">
					<div class="border full">
						<div>
							<span class="border-msg" style="padding: 10px;">
								<?php if($userimageid != ''){ ?>
									<img width="50" height="50" date-imgid="'.$userdetails->avatarid.'" id="profile_im" src="<?php echo URL::base().$userimageid;?>" />
								<?php }else { ?>
									<i class="fa fa-user iconsize datacol"></i>
								<?php } ?>
								&nbsp;&nbsp;&nbsp;<span class=""><span class="pointers activedatacol"><?php echo ucfirst($values['user_fname']).' '.ucfirst($values['user_lname']);?></span> <b><?php echo __('Shared a Workout Plan'); ?></b><br><span style=" font-size: .8em;padding-left: 36px;"><?php echo Helper_Common::time_ago($values['created_date']);?></span></span>
							</span>
							<span class="border-msg full" style="padding: 10px;">
								<?php echo ucfirst($values['shared_msg']);?>
							</span>
							<div class="mobpadding border" style="padding:10px">
								<div class="titlechoosen col-xs-9 alignleft border full pointers" onclick="getworkoutpreview('<?php echo $values['wkout_share_id'];?>');">
									<div><?php echo $values['wkout_title'] ;?></div>
									<br>
									<div class="wkoutfocus"><?php echo ucfirst($values['wkout_focus']);?></div>
								</div>
								<div class="iconfont col-xs-3 border full">
									<i class="btn fa fa-ellipsis-h iconsize" onclick="getTemplateOfWorkoutAction('<?php echo $values['wkout_share_id'];?>','<?php echo $values['wksid'];?>','<?php echo addslashes($values['wkout_title']) ;?>');"></i>
								</div>
							</div>
						</div>
					</div>
				</div>
				<br>
	<?php  
			$border++;	}else{ 
	?>
				<div class="row">
					<div class="border full">
						<div>
							<span class="border-msg" style="padding: 10px;">
								<?php if($userimageid != ''){ ?>
									<img width="50" height="50" date-imgid="'.$userdetails->avatarid.'" id="profile_im" src="<?php echo URL::base().$userimageid;?>" />
								<?php }else { ?>
									<i class="fa fa-user iconsize datacol"></i>
								<?php } ?>&nbsp;&nbsp;&nbsp;<span class=""><span class="pointers activedatacol"><?php echo ucfirst($values['user_fname']).' '.ucfirst($values['user_lname']);?></span> <b><?php echo __('Shared a Workout Plan'); ?></b><br><span style=" font-size: .8em;padding-left: 36px;"><?php echo Helper_Common::time_ago($values['created_date']);?></span></span>
							</span>
							<span class="border-msg full" style="padding: 10px;">
								<?php echo ucfirst($values['shared_msg']);?>
							</span>
							<div class="mobpadding border" style="padding:10px">
								<div class="col-xs-9 border full">
									<div class="colorchoosen col-xs-2">
										<a data-ajax="false" data-role="none" href="<?php echo URL::base(TRUE).'exercise/sharedworkout/'.$values['folder_id']; ?>"><i class="fa fa-folder-o"></i></a>
									</div>
									<div class="titlechoosen col-xs-9">
										<a data-ajax="false" data-role="none" href="<?php echo URL::base(TRUE).'exercise/sharedworkout/'.$values['folder_id']; ?>" title="<?php echo ucfirst($values['folder_title']);?>">
											<?php echo ucfirst($values['folder_title'])."<br>";?>
											<span class="navimgdet3"><?php echo(($values['totalRecords'] > 0 )? (($values['totalRecords'] > 1 ) ? __('Records').' : '.$values['totalRecords'] : __('Record').' : '.$values['totalRecords']) : __('Record').' : 0');?></span>
										</a>
									</div>
								</div>
								<div class="iconfont col-xs-3 border full">
									<button data-ajax="false" data-role="none" type="submit" name="f_method" class="btn" value="copy_folder_<?php echo $values['folder_id'];?>"><i class="fa fa-files-o iconsize"></i></button>
								</div>
							</div>
						</div>
					</div>
				</div>
				<br>
	<?php 		}
			} 
		}else{
	?>
		<div class="row">
			<div class="border full">
				<div class="col-xs-3 aligncenter">
				</div>
				<div class="col-xs-6 aligncenter">
					<?php echo __('No records Found');?>!!!
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
  <div id="myModal" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static"></div>
  <div id="FolderModal" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static"></div>
  <div id="relatedexceModal" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static"></div>
  <div id="myOptionsModalExerciseRecord" class="modal fade bs-example-modal-sm" role="dialog" data-keyboard="false" data-backdrop="static"></div>  
  <?php if(!empty($shareWkoutId)){ ?>
 <script>
	$(document).ready(function (){
		setTimeout(function(){ getworkoutpreview('<?php echo $shareWkoutId;?>')}, 300);
	});
 </script>
<?php } ?>