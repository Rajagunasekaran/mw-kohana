<div id="wrap-index">
  <div class="tour-step tour-step-one"></div>
  <!-- Login header nav !-->
  <?php echo $topHeader;?>
  <?php $user = Auth::instance()->get_user();?>
  <div class="container" id="home">
	<div class="row">
		<?php $session = Session::instance();
			if ($session->get('success')): ?>
		  <div class="banner success alert alert-success">
			 <a data-ajax="false" data-role="none" href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<?php echo $session->get_once('success') ?>
		  </div>
		  <br>
		 <?php endif ?>
	</div>
	<div class="row pointers tour-step tour-step-three " <?php if(isset($exerciseDay['unit_id'])) { ?> onclick="getTemplateOfExerciseRecordAction('<?php echo $exerciseDay['unit_id'];?>','');" <?php } ?>>
		<div class="span12 centercell">
			<div class="span7">
				<div class="col-xs-3">
					<?php if(isset($exerciseDay['img_url']) && !empty($exerciseDay['img_url']) && file_exists($exerciseDay['img_url'])) {?>
					<img src="<?php echo URL::base().$exerciseDay['img_url']; ?>" alt="..." class="img-thumbnail" width="50px;">
					<?php }else{ ?>
					<i class="fa fa-picture-o iconsize"></i>
					<?php } ?>
				</div>
				<div class="col-xs-8">
                    <div class="alignleft">
                        <b><?php echo __('Exercise of the Day'); ?></b><br><?php if(isset($exerciseDay['title'])) { echo '<span class="activedatacol">'.$exerciseDay['title'].'</span>';}else { 'Title of the Exercise Record'; } ?>
                    </div>
                </div>
			</div>
		</div>
	</div>
	<?php if($user->user_profile!=1){ ?>
	<hr>
	<div class="row">
		<div class="span12 centercell">
			<a data-ajax="false" data-role="none" href="javascript:void(0)" title="Shortcuts" class="activedatacol" onclick="showShortcuts();" >
				<div class="span7">
					<div class="col-xs-3 tour-step tour-step-four">
						<i class="fa fa-crosshairs iconsize orangeicon"></i>
					</div>
					<div class="col-xs-8 alignleft">
						<?php echo __('Shortcuts'); ?>
					</div>
				</div>
			</a>
		</div>
	</div>
	<?php } ?>
	<hr>
	<div class="row">
		<div class="span12 centercell">
			<a data-ajax="false" data-role="none" href="<?php echo URL::base(TRUE).'exercise/myactionplans/'.date('Y-m-d'); ?>" title="Training Diary" class="activedatacol">
				<div class="span7">
					<div class="col-xs-3 tour-step tour-step-five">
						<i class="fa fa-calendar iconsize2"></i>
					</div>
					<div class="col-xs-8 alignleft">
						<?php echo __('Training Diary'); ?>
						<?php if(isset($todayPlans) && count($todayPlans)>0){ ?>
							<span class="actioncount"><?php echo '&nbsp;&nbsp;Today : '.count($todayPlans).'&nbsp;&nbsp;';?></span>
						<?php } ?>
					</div>
				</div>
			</a>
		</div>
	</div>
	<hr>
	<div class="row">
		<div class="span12 centercell">
			<a class="activedatacol" data-ajax="false" data-role="none" href="<?php echo URL::base(TRUE).'exercise/index/'; ?>" title="<?php echo __('Workout Plans'); ?>">
				<div class="span7">
					<div class="col-xs-3 tour-step tour-step-six">
						<i class="fa fa-folder-open-o iconsize2"></i>
					</div>
					<div class="col-xs-8 alignleft">
						<?php echo __('Workout Plans'); ?> <?php if(isset($overallcnt) && $overallcnt>0){ ?>
							<span class="actioncount"><?php echo '&nbsp;&nbsp;'.$overallcnt.'&nbsp;&nbsp;';?></span>
						<?php } ?>
					</div>
				</div>
			</a>
		</div>
	</div>
	<?php if($user->user_profile!=1){ ?>
	<hr>
	<div class="row">
		<div class="span12 centercell">
			<a class="activedatacol" data-ajax="false" data-role="none" href="<?php echo URL::base(TRUE).'exercise/exerciselibrary/'; ?>" title="<?php echo __('Exercise Library'); ?>">
				<div class="span7">
					<div class="col-xs-3 tour-step tour-step-ten">
						<i class="fa fa-book iconsize2"></i>
					</div>
					<div class="col-xs-8 alignleft">
						<?php echo __('Exercise Library'); ?><?php if(isset($sharedxrunreadcnt) && $sharedxrunreadcnt>0){ ?>
							<span class="actioncount"><?php echo '&nbsp;&nbsp;'.$sharedxrunreadcnt.'&nbsp;&nbsp;';?></span>
						<?php } ?>
					</div>
				</div>
			</a>
		</div>
	</div>
	<?php } ?>
	<br>
  </div>
  <input type="hidden" id="newlyAddedXr" name="newlyAddedXr" value="0"/>
  <div id="myModal" class="modal fade" role="dialog" tabindex="-1"></div>
  <div id="ExerciseModal" class="modal fade" role="dialog" tabindex="-1"></div>
  <div id="mypopupModal" class="modal fade bs-example-modal-sm" role="dialog" tabindex="-1"></div>
  <div id="myOptionsModalAjax" class="modal fade bs-example-modal-sm" role="dialog" tabindex="-1"></div>
  <div id="myOptionsModalExerciseRecord" class="modal fade bs-example-modal-sm" role="dialog" tabindex="-1"></div>
  <div id="myOptionsModal" class="modal fade bs-example-modal-sm" role="dialog" tabindex="-1"></div>
  <div id="FolderModal" class="modal fade" role="dialog" tabindex="-1"></div>