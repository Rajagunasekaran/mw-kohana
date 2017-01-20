<div class="imageeditor-template">
	<div id="popupimgeditor-model" class="modal fade imgeditormodel" role="dialog" tabindex="-1">
		<div class="vertical-alignment-helper">
			<div class="modal-dialog modal-md model-editor">
				<div class="modal-content aligncenter">
					<div class="modal-header aligncenter">
						<div class="row">
							<div class="popup-title">
								<div class="col-xs-2">
									<a href="javascript:void(0);" class="triangle confirm" title="<?php echo __('Back'); ?>" data-onclick="$('#popupimgeditor-model').modal('hidecustom');" data-allow="<?php echo (Helper_Common::getAllowAllAccessByUser((Session::instance()->get('user_allow_page') ? Session::instance()->get('user_allow_page') : '1'), 'is_confirm_image_hidden') ? 'false' : 'true'); ?>" data-notename="hide_confirm_image" data-text="Clicking BACK or CANCEL will discard any changes. Clicking MORE will display record options such as SAVE. Continue with exiting?" data-ajax="false" data-role="none">
										<i class="fa fa-caret-left iconsize"></i>
									</a>
								</div>
								<div class="col-xs-8"><?php echo __('Image Editor'); ?></div>
								<div class="col-xs-2">
									<button type="button" class="btn btn-default activedatacol trigger_crop" id="trigger_crop_top" onclick="triggerEditingOption(this);" data-prefix="" data-ajax="false" data-role="none"><?php echo __('more'); ?></button>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-body">
						<form action="#" method="post" data-ajax="false" data-role="none">
							<div class="aligncenter" style="margin: 0 auto 0 auto">
								<div class="image-editor">
									<div class="row">
										<div class="col-md-12 col-xs-12 docs-buttons">
											<div class="btn-group">
												<button type="button" class="btn btn-primary" data-method="zoom" data-option="0.1" title="<?php echo __('Zoom In'); ?>" data-ajax="false" data-role="none">
													<span class="fa fa-search-plus"></span>
												</button>
												<button type="button" class="btn btn-primary" data-method="zoom" data-option="-0.1" title="<?php echo __('Zoom Out'); ?>" data-ajax="false" data-role="none">
													<span class="fa fa-search-minus"></span>
												</button>
											</div>
											<div class="btn-group">
												<button type="button" class="btn btn-primary" data-method="rotate" data-option="-45" title="<?php echo __('Rotate Left'); ?>" data-ajax="false" data-role="none">
													<span class="fa fa-rotate-left"></span>
												</button>
												<button type="button" class="btn btn-primary" data-method="rotate" data-option="45" title="<?php echo __('Rotate Right'); ?>" data-ajax="false" data-role="none">
													<span class="fa fa-rotate-right"></span>
												</button>
											</div>
											<div class="btn-group">
												<button type="button" class="btn btn-primary" data-method="scaleX" data-option="-1" title="<?php echo __('Flip Horizontal'); ?>" data-ajax="false" data-role="none">
													<span class="fa fa-arrows-h"></span>
												</button>
												<button type="button" class="btn btn-primary" data-method="scaleY" data-option="-1" title="<?php echo __('Flip Vertical'); ?>" data-ajax="false" data-role="none">
													<span class="fa fa-arrows-v"></span>
												</button>
											</div>
										</div><!-- /.docs-buttons -->
									</div>

									<div class="row">
										<div class="col-md-12 col-xs-12">
											<div class="img-container">
												<img id="image" src="" alt="<?php echo __('Image Loading'); ?>...">
												<div class="preloader"></div>
											</div>
										</div>
									</div>

									<div class="row">
										<div class="col-md-12 col-xs-12 docs-buttons">
											<div class="btn-group">
												<button type="button" class="btn btn-primary" data-method="move" data-option="-10" data-second-option="0" title="<?php echo __('Move Left'); ?>" data-ajax="false" data-role="none">
													<span class="fa fa-arrow-left"></span>
												</button>
												<button type="button" class="btn btn-primary" data-method="move" data-option="10" data-second-option="0" title="<?php echo __('Move Right'); ?>" data-ajax="false" data-role="none">
													<span class="fa fa-arrow-right"></span>
												</button>
												<button type="button" class="btn btn-primary" data-method="move" data-option="0" data-second-option="-10" title="<?php echo __('Move Up'); ?>" data-ajax="false" data-role="none">
													<span class="fa fa-arrow-up"></span>
												</button>
												<button type="button" class="btn btn-primary" data-method="move" data-option="0" data-second-option="10" title="<?php echo __('Move Down'); ?>" data-ajax="false" data-role="none">
													<span class="fa fa-arrow-down"></span>
												</button>
											</div>
											<div class="btn-group hide">
												<button type="button" class="btn btn-primary crop-btn" data-method="getCroppedCanvas" title="<?php echo __('Crop'); ?>" data-ajax="false" data-role="none">
													<span class="fa fa-check"></span>
												</button>
												<button type="button" class="btn btn-primary crop-reset" data-method="reset" title="<?php echo __('Reset'); ?>" data-ajax="false" data-role="none">
													<span class="fa fa-retweet"></span>
												</button>
												<input type="hidden" id="inputImgName" name="inputImgName" value="">
												<input type="hidden" id="imgtagid" name="imgtagid" value="">
												<input type="hidden" id="hiddenid" name="hiddenid" value="">
												<input type="hidden" id="imgname" name="imgname" value="">
											</div>
										</div><!-- /.docs-buttons -->
									</div>

									<div class="row">
										<div class="col-md-12 col-xs-12">
											<div class="docs-preview clearfix">
												<div class="img-preview preview-md" title="<?php echo __('Image Preview'); ?>"></div>
												<div class="img-preview preview-sm preivew-circle" title="<?php echo __('Image Preview'); ?>"></div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default confirm" data-onclick="$('#popupimgeditor-model').modal('hidecustom');" data-allow="<?php echo (Helper_Common::getAllowAllAccessByUser((Session::instance()->get('user_allow_page') ? Session::instance()->get('user_allow_page') : '1'), 'is_confirm_image_hidden') ? 'false' : 'true'); ?>" data-notename="hide_confirm_image" data-text="Clicking BACK or CANCEL will discard any changes. Clicking MORE will display record options such as SAVE. Continue with exiting?" data-ajax="false" data-role="none" style="margin-right: 20px;"><?php echo __('Cancel'); ?></button>
						<button type="button" class="btn btn-default activedatacol trigger_crop" id="trigger_crop_btm" onclick="triggerEditingOption(this);" data-prefix="" data-ajax="false" data-role="none" style="margin-right: 10px;"><?php echo __('more'); ?></button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>