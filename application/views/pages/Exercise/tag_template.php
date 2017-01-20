<div id="wrap-index">
  <!-- Login header nav !-->
<?php echo $topHeader;?>
  <div class="container" id="home">
	<form action="" method="post" id="exercise_taginsert">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<div class="mobpadding">
						<div class="border">
							<div class="col-xs-2">
								<a href="#" title="Back" data-dismiss="modal" class="triangle">
									<i class="fa fa-chevron-left"></i>
								</a>
							</div>
							<div class="col-xs-8 optionpoptitle"><?php echo __('Exercise Tags'); ?></div>
							<div class="col-xs-2"></div>
						</div>
					</div>
				</div>
				<div class="modal-body opt-body">
					<div class="opt-row-detail">
						<div class="row">
							<div class="col-xs-12"><label class="control-label"><?php echo __('Tags'); ?>:</label></div>
							<div class="col-xs-12">
								<input type="text" class="form-control xrtag-input" name="xrtag-input" value="" data-role="tagsinput"/>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="xrunitid" id="xrunit_id" value="" />
					<button type="submit" class="btn btn-default pull-left" id="btn-insertxrtag" name="f_method" value="xr-tagging"><?php echo __('Insert'); ?></button>
					<button type="button" class="btn btn-default pull-right" data-dismiss="modal"><?php echo __('Close'); ?></button>
				</div>
			</div>
		</div>
	</form>
</div>
<!-- Modal -->
<div id="myModal" class="modal fade bs-example-modal-sm" role="dialog" data-keyboard="false" data-backdrop="static"></div>
<div id="myOptionsModal" class="modal fade bs-example-modal-sm" role="dialog" data-keyboard="false" data-backdrop="static"></div>
<div id="myOptionsModalExerciseRecord" class="modal fade bs-example-modal-sm" role="dialog" data-keyboard="false" data-backdrop="static"></div>
