<div class="imagelib-template">
	<?php Session::instance()->set('imgchecked', 0); ?>
	<!-- template image library -->
	<input type="hidden" tabindex="-1" name="triggerid" id="triggerid" value=""/>
	<div id="mdl_popupimglibrary-modal" class="modal fade popup-mdl" role="dialog" tabindex="-1">
		<div class="vertical-alignment-helper">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<div class="row">
							<div class="popup-title">
								<div class="col-xs-2">
									<a href="javascript:void(0);" title="<?php echo __('Back'); ?>" class="triangle modalBack" onclick="$('#mdl_popupimglibrary-modal').modal('hidecustom');" data-ajax="false" data-role="none">
										<i class="fa fa-caret-left iconsize"></i>
									</a>
								</div>
								<div class="col-xs-8"><?php echo __('Image Library'); ?></div>
								<div class="col-xs-2"></div>
							</div>
						</div>
					</div>
					<div class="modal-body">
						<div class="row bannermsg" id="bannermsg" style="display: none;">
							<div class="col-sm-12 col-xs-12 col-md-12 banner temp-banner success"></div>
						</div>
						<form method="post" id="mdl_imglibrary_form" enctype="multipart/form-data" data-ajax="false" data-role="none">
							<!-- img uploader start -->
							<div class="mdl_uploader-section hide" id="mdl_ImageUploader">    
								<div class="row" id="mdl_uploader-head">
									<div class="page-head">
										<div class="col-xs-3 aligncenter">
											<a href="javascript:void(0);" id="mdl_uploaderBack" title="<?php echo __('Back'); ?>" data-ajax="false" data-role="none">
												<i class="fa fa-caret-left iconsize"></i>
											</a>
										</div>
										<div class="col-xs-6 aligncenter centerheight page-title"><?php echo __('Upload Images'); ?></div>
										<div class="col-xs-3 aligncenter">
											<!-- <a href="javascript:void(0);" id="mdl_progressNext" title="<?php //echo __('Back'); ?>" data-ajax="false" data-role="none">
												<i class="fa fa-caret-right iconsize"></i>
											</a> -->
										</div>
									</div>
								</div>
								<div class="row hide" id="mdl_progress-head">
									<div class="page-head">
										<div class="col-xs-3 aligncenter">
											<a href="javascript:void(0);" id="mdl_progressBack" title="<?php echo __('Back'); ?>" data-ajax="false" data-role="none">
												<i class="fa fa-caret-left iconsize"></i>
											</a>
										</div>
										<div class="col-xs-6 aligncenter centerheight page-title"><?php echo __('Processing Uploads'); ?></div>
										<div class="col-xs-3 aligncenter"></div>
									</div>
								</div>
								<hr>
								<div class="uploader-div">
									<div class="row">
										<div class="col-md-12 col-xs-12 text-center">
											<div class="listing-header header-toggle mdl_header-progress hide">
												<div class="row">
													<div class="col-xs-12">
														<div class="col-xs-9 header-cell aligncenter" title="<?php echo __('Progress Status'); ?>">
															<i class="fa fa-clock-o iconsize2 activedatacol col-xs-3"></i>
															<div id="mdl_progressbarOuter" class="progress progress-striped active col-xs-9" style="display:none;">
																<div id="mdl_progressBar" class="progress-bar progress-bar-success"  role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
															</div>
														</div>
														<div class="col-xs-3 header-cell aligncenter datacol">
															<i class="fa fa-list-ul iconsize2 datacol"></i>
														</div>
													</div>
												</div>
												<hr>
											</div>
											<div class="mdl_upload-div">
												<div class="uploadimage-dragndrop" id="mdl_dragndropimage">
													<div class=""><span class="fa fa-upload fa-3x"></span></div>
													<div class="uploadimage-text"><?php echo __('Drag image here to upload'); ?></div>
												</div>
												<div class="input-file">
													<input class="imageupload" id="mdl_files" type="file" name="images[]" multiple="" accept="image/*" data-ajax="false" data-role="none">
												</div>
												<div class="prefer-text"><?php echo __('Or, if you prefer'); ?>...</div>
												<button class="btn btn-primary" title="<?php echo __('Upload Image File'); ?>" id="mdl_image_upload" type="button" data-ajax="false" data-role="none">
													<span class="fa fa-upload"></span>&nbsp;&nbsp;<?php echo __('Upload Image'); ?>
												</button>
												<div id="mdl_uploadError" class="upload-error"></div>
											</div>
											<ul id="mdl_uploadListing" class="img-listing"></ul>
										</div>
									</div>
									<input name="mdl_uploadfolderId" id="mdl_uploadfolderId" type="hidden" value="0">
									<div class="clear"></div>
								</div>
							</div><!-- img uploader end -->
							<!-- img folder start-->
							<div class="mdl_folder-section" id="mdl_ImageFolders"></div>
							<!-- img folder end -->
							<!-- dynamic xr img id go here-->
							<div id="data_XrImgs" class="hide"></div>
							<!-- croppted img data -->
							<div class="mdl_imgDataUrl">
								<input type="hidden" id="mdl_croppedData" name="mdl_croppedData" value=""/>
								<input type="hidden" id="mdl_replaceflag" value=""/>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default modalBack" onclick="$('#mdl_popupimglibrary-modal').modal('hidecustom');" data-ajax="false" data-role="none"><?php echo __('Close'); ?></button>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- preview image modal -->
	<div id="mdl_popupimgprev-modal" class="modal fade" role="dialog" tabindex="-1">
		<div class="vertical-alignment-helper">
			<div class="modal-dialog">
				<div class="modal-content aligncenter">
					<div class="modal-header">
						<div class="mobpadding">
							<div class="border">
								<div class="col-xs-2">
									<a href="javascript:void(0);" title="<?php echo __('Back'); ?>" class="triangle" onclick="$('#mdl_popupimgprev-modal').modal('hidecustom');" data-ajax="false" data-role="none">
										<i class="fa fa-caret-left iconsize"></i>
									</a>
								</div>
								<div class="col-xs-8 optionpoptitle"><?php echo __('Preview Image'); ?></div>
								<div class="col-xs-2 mdl_preview-opt"><button class="btn btn-default activedatacol" data-toggle="modal" data-target="#mdl_popupimgact-modal" data-ajax="false" data-role="none"><?php echo __('more'); ?></button></div>
							</div>
						</div>
					</div>
					<div class="modal-body" id="mdl_preview_libimg">
						<i class="fa fa-file-image-o prevfeat"></i>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" onclick="$('#mdl_popupimgprev-modal').modal('hidecustom');" data-ajax="false" data-role="none"><?php echo __('Close'); ?></button>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- main image action modal -->
	<div id="mdl_popupimgact-modal" class="modal fade" role="dialog" tabindex="-1">
		<div class="vertical-alignment-helper">
			<div class="modal-dialog modal-md">
				<div class="modal-content">
					<div class="modal-header">
						<div class="mobpadding">
							<div class="border">
								<div class="col-xs-2">
									<a href="javascript:void(0);" title="<?php echo __('Back'); ?>" onclick="$('#mdl_popupimgact-modal').modal('hidecustom');" class="triangle" data-ajax="false" data-role="none">
										<i class="fa fa-chevron-left"></i>
									</a>
								</div>
								<div class="col-xs-8 optionpoptitle"><?php echo __('Options for this Image'); ?></div>
								<div class="col-xs-2"></div>
							</div>
						</div>
					</div>
					<div class="modal-body opt-body">
						<input type="hidden" id="mdl_curr_imgid" name="curr_imgid" value=""/>
						<div class="opt-row-detail">
							<button class="btn btn-default" id="mdl_preview-btn" onclick="popuptriggerImgPrevModal(this);" value="preview" type="button" style="width:100%" data-ajax="false" data-role="none">
								<div class="col-xs-12 pointer">
									<div class="col-xs-3"><i class="fa fa-eye iconsize"></i></div>
									<div class="col-xs-9"><?php echo __('Preview'); ?></div>
								</div>
							</button>
						</div>
						<div class="opt-row-detail">
							<button class="btn btn-default" id="mdl_editimg-btn" onclick="popuptriggerImgEditorModal();" value="edit-image" type="button" style="width:100%" data-ajax="false" data-role="none">
								<div class="col-xs-12 pointer">
									<div class="col-xs-3"><i class="fa fa-picture-o iconsize"></i></div>
									<div class="col-xs-9"><?php echo __('Edit Image'); ?></div>
								</div>
							</button>
						</div>
						<div class="opt-row-detail">
							<button class="btn btn-default" id="mdl_imginsert-btn" name="mdl_imginsert-btn" value="insertimg" type="button" style="width:100%" data-ajax="false" data-role="none">
								<div class="col-xs-12 pointer">
									<div class="col-xs-3"><i class="fa fa-sign-in iconsize"></i></div>
									<div class="col-xs-9"><?php echo __('Insert this Image'); ?></div>
								</div>
							</button>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" onclick="$('#mdl_popupimgact-modal').modal('hidecustom');" data-ajax="false" data-role="none"><?php echo __('Close'); ?></button>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- choose folder modal -->
	<div id="mdl_popupfldrslct-modal" class="modal fade" role="dialog" tabindex="-1">
		<div class="vertical-alignment-helper">
			<div class="modal-dialog modal-md">
				<div class="modal-content">
					<div class="modal-header">
						<div class="mobpadding">
							<div class="border">
								<div class="col-xs-2">
									<a href="javascript:void(0);" title="<?php echo __('Back'); ?>" onclick="$('#mdl_popupfldrslct-modal').modal('hidecustom');" class="triangle" data-ajax="false" data-role="none">
										<i class="fa fa-chevron-left"></i>
									</a>
								</div>
								<div class="col-xs-8 optionpoptitle"><?php echo __('Select Folder To Upload Images'); ?></div>
								<div class="col-xs-2"></div>
							</div>
						</div>
					</div>
					<div class="modal-body opt-body">
						<div id="mdl_chooseFolder">
							<div class="opt-row-detail">
								<button type="button" class="btn btn-default" id="4" style="width:100%" data-ajax="false" data-role="none">
									<div class="col-xs-12 pointer">
										<div class="col-xs-3"><i class="fa fa-folder-o iconsize2 folderselect datacol"></i></div>
										<div class="col-xs-9 folderselect datacol"><?php echo __('Profile Images'); ?></div>
									</div>
								</button>
							</div>
							<div class="opt-row-detail">
								<button type="button" class="btn btn-default mdl_folder-select" id="5" style="width:100%" data-ajax="false" data-role="none">
									<div class="col-xs-12 pointer">
										<div class="col-xs-3"><i class="fa fa-folder-o iconsize2 folderselect activedatacol"></i></div>
										<div class="col-xs-9 folderselect activedatacol"><?php echo __('Exercise Images'); ?></div>
									</div>
								</button>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default pull-right" onclick="$('#mdl_popupfldrslct-modal').modal('hidecustom');" data-ajax="false" data-role="none"><?php echo __('Close'); ?></button>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- image filtering modal -->
	<div id="mdl_popupfilteract-modal" class="modal fade" role="dialog" tabindex="-1">
		<div class="vertical-alignment-helper">
			<div class="modal-dialog modal-md">
				<div class="modal-content">
					<form action="" id="mdl_filteract-form" data-ajax="false" data-role="none">
						<div class="modal-header">
							<div class="mobpadding">
								<div class="border">
									<div class="col-xs-2">
										<a href="javascript:void(0);" title="<?php echo __('Back'); ?>" onclick="$('#mdl_popupfilteract-modal').modal('hidecustom');" class="triangle" data-ajax="false" data-role="none">
											<i class="fa fa-chevron-left"></i>
										</a>
									</div>
									<div class="col-xs-8 optionpoptitle"><?php echo __('Options for Filtering'); ?></div>
									<div class="col-xs-2"></div>
								</div>
							</div>
						</div>
						<div class="modal-body opt-body">
							<div class="opt-row-detail">
								<div class="row">
									<div class="col-xs-12"><label class="control-label"><?php echo __('Image Title'); ?>:</label></div>
									<div class="col-xs-12">
										<input type="text" class="form-control" id="mdl_fltrtitle-input" value="" placeholder="Image Title" data-ajax="false" data-role="none"/>
									</div>
								</div>
							</div>
							<div class="opt-row-detail">
								<div class="row">
									<div class="col-xs-12"><label class="control-label"><?php echo __('Tags'); ?>:</label></div>
									<div class="col-xs-12" data-enhance="false" data-role="none" data-ajax="false">
										<input type="text" class="form-control mdl_fltrtag-input" value="" placeholder="Tags" data-role="tagsinput" data-ajax="false"/>
									</div>
								</div>
							</div>
							<div class="opt-row-detail">
								<div class="row">
									<div class="col-sm-12"><label class="control-label"><?php echo __('Sort By'); ?>:</label></div>
									<div class="col-sm-12">
										<div class="dropdown selectdropdownTwo filtersort-select">
											<select class="mdl_fltrsort-select" id="mdl_fltrsort-select" data-ajax="false" data-role="none">
												<option value="asc">A-Z</option>
												<option value="desc">Z-A</option>
												<option value="date_created">Created (Most Rescent)</option>
												<option value="date_modified">Modified (Most Rescent)</option>
											</select>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button class="btn btn-default pull-left" id="mdl_btn-filterreset" value="reset" type="button" data-ajax="false" data-role="none"><?php echo __('Reset'); ?></button>
							<button class="btn btn-default pull-right" id="mdl_btn-filterfetch" value="fetch" type="submit" data-ajax="false" data-role="none"><?php echo __('Fetch'); ?></button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<!-- crop action modal -->
	<div id="mdl_popupfinalact-modal" class="modal fade" role="dialog" style="z-index: 9991;" tabindex="-1">
		<div class="vertical-alignment-helper">
			<div class="modal-dialog modal-md">
				<div class="modal-content">
					<div class="modal-header">
						<div class="mobpadding">
							<div class="border">
								<div class="col-xs-2">
									<a href="javascript:void(0);" title="<?php echo __('Back'); ?>" onclick="$('#mdl_popupfinalact-modal').modal('hidecustom');" class="triangle" data-ajax="false" data-role="none">
										<i class="fa fa-chevron-left"></i>
									</a>
								</div>
								<div class="col-xs-8 optionpoptitle"><?php echo __('Options for Editing'); ?></div>
								<div class="col-xs-2"></div>
							</div>
						</div>
					</div>
					<div class="modal-body opt-body">
						<div class="opt-row-detail">
							<button class="btn btn-default" id="mdl_btn_saveclose" onclick="popuptriggerUpdateImage(this);" value="saveclose" type="button" style="width:100%" data-ajax="false" data-role="none">
								<div class="col-xs-12 pointer">
									<div class="col-xs-3"><i class="fa fa-save iconsize"></i></div>
									<div class="col-xs-9"><?php echo __('Save & Close'); ?></div>
								</div>
							</button>
						</div>
						<div class="opt-row-detail">
							<button class="btn btn-default" id="mdl_btn_savecontn" onclick="popuptriggerUpdateImage(this);" value="savecontinue" type="button" style="width:100%" data-ajax="false" data-role="none">
								<div class="col-xs-12 pointer">
									<div class="col-xs-3"><i class="fa fa-pencil-square-o iconsize"></i></div>
									<div class="col-xs-9"><?php echo __('Save & Continue Editing'); ?></div>
								</div>
							</button>
						</div>
						<div class="opt-row-detail">
							<button class="btn btn-default confirm" id="mdl_btn_revertdata" data-onclick="popuptriggerImgDataRevert('revert');" value="imgdata" type="button" data-allow="<?php echo (Helper_Common::getAllowAllAccessByUser((Session::instance()->get('user_allow_page') ? Session::instance()->get('user_allow_page') : '1'), 'is_confirm_image_hidden') ? 'false' : 'true'); ?>" data-notename="hide_confirm_image" data-text="This will discard any changes on this record. Do you want to SAVE or Continue with exiting?" style="width:100%" data-ajax="false" data-role="none">
								<div class="col-xs-12 pointer">
									<div class="col-xs-3"><i class="fa fa-refresh iconsize"></i></div>
									<div class="col-xs-9"><?php echo __('Reset / Revert to Saved'); ?></div>
								</div>
							</button>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" onclick="$('#mdl_popupfinalact-modal').modal('hidecustom');" data-ajax="false" data-role="none"><?php echo __('Close'); ?></button>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript">
	/*uploader section scripts starts*/
	var mdl_progresshead = $('#mdl_progress-head'),
		mdl_uploadhead = $('#mdl_uploader-head'),
		mdl_uploaddiv = $('.mdl_upload-div'),
		mdl_headerprogress = $('.mdl_header-progress');
	function initSimpleUpload() {
		console.log('init SimpleUpload');
		var mdl_errFilename = '';
		var mdl_btnupload = document.getElementById('mdl_image_upload'),
			mdl_progressBar = document.getElementById('mdl_progressBar'),
			mdl_progressbarOuter = document.getElementById('mdl_progressbarOuter'),
			mdl_divBoxcover = document.getElementById('mdl_uploadListing'),
			mdl_msgBoxcover = document.getElementById('mdl_uploadError');
		var mdl_imguploader = new ss.SimpleUpload({
			button: mdl_btnupload,
			url: siteUrl + 'exercise/uploadImg?action=upload',
			name: 'uploadfile',
			dropzone:'mdl_dragndropimage',
			multipart: true,
			hoverClass: 'hover',
			focusClass: 'focus',
			responseType: 'json',
			maxSize: 2560,
			allowedExtensions: ["jpg", "jpeg", "png"],
			onSizeError: function(filename, fileSize) {
				mdl_errFilename += '<b>'+filename+'</b>, ';
				mdl_msgBoxcover.innerHTML = mdl_errFilename.slice(0, -2) + ' file(s) size is too large. (max file size 2560kb)';
				return false;
			},
			onExtError: function(filename, extension) {
				mdl_msgBoxcover.innerHTML = 'Extension "<b>' + extension + '</b>" not allowed, please choose jpg, jpeg and png file.';
				return false;
			},
			onChange: function() {
				mdl_errFilename = '';
			},
			startXHR: function() {
				mdl_uploadhead.addClass('hide');
				mdl_uploaddiv.addClass('hide');
				mdl_progresshead.removeClass('hide');
				mdl_headerprogress.removeClass('hide');
				mdl_progressbarOuter.style.display = 'block'; // make progress bar visible
				this.setProgressBar( mdl_progressBar );
			},
			onSubmit: function() {
				mdl_msgBoxcover.innerHTML = ''; // empty the message box
				var self = this;
				self.setData({
					upfolder:$('#mdl_uploadfolderId').val(), currfolder:$('#mdl_currentFolderId').val(), parentfolder:$('#mdl_parentFolderId').val(), subfolder:$('#mdl_subFolderId').val(), replaceflag:$('#mdl_replaceflag').val(), imageid:$('#mdl_curr_imgid').val(), uploadfrom:'template'
				});
			},
			onComplete: function( filename, response ) {
				mdl_progressbarOuter.style.display = 'none'; // hide progress bar when upload is completed
				if ( !response ) {
					mdl_msgBoxcover.innerHTML = 'Unable to upload file';
					return;
				}
				if ( response.success === false ) {
					mdl_uploadhead.removeClass('hide');
					mdl_uploaddiv.removeClass('hide');
					mdl_progresshead.addClass('hide');
					mdl_headerprogress.addClass('hide');
					$('#mdl_uploadListing').addClass('hide').empty();
					mdl_msgBoxcover.innerHTML = response.divImage;
					return;
				}
				if ( response.success === true ) {
					mdl_divBoxcover.innerHTML = $('#mdl_uploadListing').html()+response.divImage;
					$('#mdl_uploadListing').removeClass('hide');
				} else {
					if ( response.msg ) {
						mdl_msgBoxcover.innerHTML = escapeTags( response.msg );
					} else {
						mdl_msgBoxcover.innerHTML = 'An error occurred and the upload failed.';
					}
				}
			},
			onError: function() {
				mdl_progressbarOuter.style.display = 'none';
				mdl_msgBoxcover.innerHTML = 'Unable to upload file';
				mdl_uploadhead.removeClass('hide');
				mdl_uploaddiv.removeClass('hide');
				mdl_progresshead.addClass('hide');
				mdl_headerprogress.addClass('hide');
				$('#mdl_uploadListing').addClass('hide').empty();
			}
		});
		$('#mdl_popupimglibrary-modal').on('hidden.bs.modal', function(){
			mdl_imguploader.destroy();
		});
	}
	$(document).ready(function(){
		document.getElementById('mdl_image_upload').addEventListener('click',function(){
			document.getElementById('mdl_files').click();
		});
	});
	$(document).on('click','#mdl_progressBack',function(){
		mdl_uploadhead.removeClass('hide');
		mdl_uploaddiv.removeClass('hide');
		mdl_progresshead.addClass('hide');
		mdl_headerprogress.addClass('hide');
		$('#mdl_uploadListing').addClass('hide');
	});
	$(document).on('click','#mdl_progressNext',function(){
		mdl_uploadhead.addClass('hide');
		mdl_uploaddiv.addClass('hide');
		mdl_progresshead.removeClass('hide');
		mdl_headerprogress.removeClass('hide');
		$('#mdl_uploadListing').removeClass('hide');
	});
	/*uploader section scripts end*/

	/*for tag*/
	if(!$('form#xrRecInsertForm').length && !$('.exercise-nav-index').length && !$('#record-gallery').length && !$('form#imglibrary_form').length){
		var tagarry = [];
		$.ajax({
			url: siteUrl + 'ajax/tagnames?user_from=front&cp='+user_allow_page,
			dataType: 'json',
			async: false,
			encode: true,
			cache: false
		}).done(function(data) {
			var taglist = [];
			if (data) {
				$.each(data.tagnames, function(i, val) {
					taglist.push({
						id: i,
						val: val
					});
				});
				tagarry = taglist;
			}
		});
		var tagnames = new Bloodhound({
			datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
			queryTokenizer: Bloodhound.tokenizers.whitespace,
			local: $.map(tagarry, function(tagname) {
				return {
					id: tagname.id,
					name: tagname.val
				};
			})
		});
		tagnames.initialize();
		$('input.mdl_fltrtag-input').tagsinput({
			typeaheadjs: [{
				highlight: true,
			}, {
				name: 'tagnames',
				displayKey: 'name',
				valueKey: 'name',
				source: tagnames.ttAdapter()
			}],
			freeInput: true
		});
		$('input.mdl_fltrtag-input').tagsinput('input').blur(function() {
			$('input.mdl_fltrtag-input').tagsinput('add', $(this).val());
			$(this).val('');
		});
	}
	/*folder section scripts starts*/
	$('#mdl_popupfilteract-modal').on('shown.bs.modal', function(){
		$('#mdl_fltrtitle-input').focus();
	});
	function popupajaxInsertActivityfeed (method, type) {
		var imgid = $('#mdl_curr_imgid').val();
		if(imgid){
			$.post(siteUrl + 'ajax/ajaxInsertActivityfeed', {'actid': imgid, 'method': method, 'type': type}, function(){});
		}
	}
	function popuptriggerImgPrevModal(elem){
		var imgurl = $(elem).attr('data-itemurl');
		if(imgurl!=undefined && imgurl!=''){
			$('#mdl_preview_libimg').html('<img alt="<?php echo __('Preview Image'); ?>" class="Preview_image" id="mdl_previewlibimg" src="' + siteUrl + imgurl + '"/>');
			$('#mdl_preview-btn').attr('data-itemurl', $(elem).attr('data-itemurl'));
		}else{
			$('#mdl_preview_libimg').html('<i class="fa fa-file-image-o prevfeat"></i>');
			$('#mdl_preview-btn').attr('data-itemurl','');
		}
		$('#mdl_popupimgprev-modal .mdl_preview-opt button').removeClass('hide');
		$('#mdl_popupimgact-modal').modal('hide');
		$('#mdl_popupimgprev-modal').modal();
		setTimeout(function(){
			popupajaxInsertActivityfeed('previewed', 'image');
		}, 350);
	}
	function popuptriggerImgOptionModal(elem){
		var imgurl = $(elem).attr('data-itemurl');
		$('#mdl_preview-btn').attr('data-itemurl',imgurl);	
		$('#mdl_popupimgact-modal').modal();
		$('#mdl_replaceflag').val('');
	}
	$(document).on('click','#mdl_btn-filterreset',function(){
		$('#mdl_filteract-form')[0].reset();
		$('input.mdl_fltrtag-input').tagsinput('removeAll');
		$('input.mdl_fltrtag-input').tagsinput('refresh');
	});
	var mdl_imgcount = 0;
	var mdl_itemcnt = 0;
	var mdl_limitcnt = 0;
	var mdl_fltr_limitcnt = 0;
	var mdl_uploadimgList = $('#mdl_uploadListing');
	popuptriggerAjaxImgLibrary('init');
	function popuptriggerAjaxImgLibrary(param){
		mdl_limitcnt = 0;
		$('#bannermsg').hide();
		$('.temp-banner').empty();
		$.ajax({
			url: siteUrl + 'ajax/getAjaxImgLibraryHtml' + (param == 'init' ? '?user_from=front&cp='+user_allow_page : ''),
			type: 'post',
			async: false,
			encode: true,
			cache: false,
			data: {fid:$('#mdl_parentFolderId').val(), subfid:$('#mdl_subFolderId').val()},
			success: function(data) {
				var ajaxData = JSON.parse(data);
				$('#mdl_ImageFolders').empty();
				if(ajaxData.content){
					$('#mdl_ImageFolders').html(ajaxData.content);
				}
				if($("#triggerid").val() == 4){
					if($("#mdl_subfolder-div .mdl_folderclk-btn").length > 0){
						$("#mdl_subfolder-div .mdl_folderclk-btn").each(function(){
							if($(this).attr('id') == 4){
								$(this).addClass('f-child');
								$(this).find(".folderclick").removeClass('datacol').addClass('activedatacol');
							}else if($(this).attr('id') == 5){
								$(this).removeClass('f-child');
								$(this).find(".folderclick").removeClass('activedatacol').addClass('datacol');
							}
						});
					}
				}else{
					if($("#mdl_subfolder-div .mdl_folderclk-btn").length > 0){
						$("#mdl_subfolder-div .mdl_folderclk-btn").each(function(){
							if($(this).attr('id') == 4){
								$(this).removeClass('f-child');
								$(this).find(".folderclick").removeClass('activedatacol').addClass('datacol');
							}else if($(this).attr('id') == 5){
								$(this).addClass('f-child');
								$(this).find(".folderclick").removeClass('datacol').addClass('activedatacol');
							}
						});
					}
				}
			}
		});
	}
	function popuptriggerUpdateImage(obj){
		$('#bannermsg').hide();
		$('#popupimgeditor-model').modal('hidecustom');
		$('#mdl_popupfinalact-modal').modal('hidecustom');
		mdl_limitcnt = 0;
		$.ajax({
			url: siteUrl + 'ajax/getAjaxImgLibraryHtml',
			type: 'post',
			async: false,
			encode: true,
			cache: false,
			data: {fid:$('#mdl_parentFolderId').val(), subfid:$('#mdl_subFolderId').val(), croppedData:$('#mdl_croppedData').val(), curr_imgid:$('#mdl_curr_imgid').val(), process:$(obj).val()},
			success: function(data) {
				var ajaxData = JSON.parse(data);
				$('#mdl_ImageFolders').empty();
				$('#bannermsg').show();
				if(ajaxData.message.flag) {
					$('.temp-banner').text(ajaxData.message.msg).addClass('success');
				}else{
					$('.temp-banner').text(ajaxData.message.msg).addClass('error');
				}
				if(ajaxData.content){
					$('#mdl_ImageFolders').html(ajaxData.content);
					$('#mdl_uploadfolderId').val('');
					$('.mdl_folder-section').removeClass('hide');
					$('.mdl_img-lib-folder').removeClass('hide');
					$('.mdl_uploader-section').addClass('hide');
					mdl_uploadimgList.empty();
					$('#mdl_progressBack').trigger('click');
				}
				if(ajaxData.imgid!='' && ajaxData.imgid!=undefined){
					$('#mdl_curr_imgid').val(ajaxData.imgid);
					console.log(ajaxData.imgid);
					if(ajaxData.saveaction=='savecontinue'){
						var objimg = $('#'+ajaxData.imgid+'.imgRecord');
						$currentElement = objimg.find('.mdl_thumb-img');
						setTimeout(function(){ 
							popuptriggerImgEditorModal();
						}, 800);
					}else{
					}
				}
				$('div.bannermsg').fadeOut(12000);
			}
		});
	}
	$(document).on('click', '.mdl_folderclk-btn.f-parent', function(){
		$('#mdl_parentFolderId').val('');
		var folderid = $(this).attr('id');
		if(folderid == 1){
			if($("#triggerid").val() == 4){
				$('#mdl_parentFolderId').val(folderid);
				$('#mdl_subFolderId').val(4);
			}else{
				$('#mdl_parentFolderId').val(folderid);
				$('#mdl_subFolderId').val(5);
			}
		}else{
			$('#mdl_parentFolderId').val(folderid);
		}
		popuptriggerAjaxImgLibrary();
	});
	$(document).on('click', '.mdl_folderclk-btn.f-child', function(){
		$('#mdl_subFolderId').val('');
		var subfolderid = $(this).attr('id');
		$('#mdl_subFolderId').val(subfolderid);
		popuptriggerAjaxImgLibrary();
	});
	$(document).on('click', '#mdl_folderBack', function(){
		if($('#mdl_parentFolderId').val()==1){
			$('#mdl_subFolderId').val('');
			$('#mdl_parentFolderId').val('');
		}
		else if($('#mdl_subFolderId').val()!=''){
			$('#mdl_subFolderId').val('');
		}
		else if($('#mdl_parentFolderId').val()!=''){
			$('#mdl_parentFolderId').val('');
		}
		popuptriggerAjaxImgLibrary();
	});
	function popupresetImgData(){
		$('.mdl_imgDataUrl').find('input').each(function(){
			$(this).val('');
		});
	}
	function popuptriggerImgDataRevert(act){
		popupresetImgData();
		$('#mdl_popupfinalact-modal').modal('hidecustom');
		$('.crop-reset').trigger('click');
		var mdl_imgid = $('#mdl_curr_imgid').val();
		if(act == 'revert' && mdl_imgid != ''){
			setTimeout(function(){
				popupajaxInsertActivityfeed('exited', 'image');
			}, 350);
		}
	}
	$(document).on('click','#mdl_uploaderBack',function() {
		$('#mdl_uploadfolderId').val('');
		popuptriggerAjaxImgLibrary();
		$('.mdl_folder-section').removeClass('hide');
		$('.mdl_img-lib-folder').removeClass('hide');
		$('.mdl_uploader-section').addClass('hide');
		mdl_uploadimgList.empty();
	});
	/*initial load on page*/
	function popupfetchMoreRecords() {
		var mdl_folderlib = $('.mdl_img-lib-folder');
		var mdl_imgList = $('#mdl_img_listing');
		mdl_limitcnt = mdl_limitcnt+10;
		if($('#mdl_parentFolderId').val() == ''){ var fid = 0 }else{ var fid = $('#mdl_parentFolderId').val(); }
		if($('#mdl_subFolderId').val() == ''){ var subfid = 0 }else{ var subfid = $('#mdl_subFolderId').val(); }
		var searchTags = '';
		$.ajax({
			url: siteUrl + 'ajax/getAjaxShowMoreImages',
			type: 'GET',
			data: {
				fid: fid, 
				subfid: subfid,
				slimit: mdl_limitcnt,
				elimit: 10
			},
			encode: true,
			cache: false,
			success: function(data) {
				var imgresultss = [JSON.parse(data)];
				if (imgresultss) {
					mdl_imgList.find('.filtering').removeClass('filtering');
					if(popuprenderToImg(popupfilterImgRecords(imgresultss))){
						if(mdl_imgcount > 10 && mdl_itemcnt == 10){
							mdl_loadAjaxSend = true;
						}
					}
				}
			},
			error: function (data){
				console.log(JSON.stringify(data));
			}
		});
	}
	/*proccessing for filtering*/
	$('#mdl_filteract-form').submit(function(ev) {
		ev.preventDefault();
		ev.stopImmediatePropagation();
		if(ev.handled !== true) {
			ev.handled = true;
			$('#mdl_img_listing').scrollTop(0);
			mdl_fltr_limitcnt = 0;
			popupfetchFilteredRecords('init');
			$('#mdl_popupfilteract-modal').modal('hide');
		}
	});
	function popupfetchFilteredRecords(opt){
		var mdl_folderlib = $('.mdl_img-lib-folder');
		var mdl_imgList = $('#mdl_img_listing');
		var searchText = $('#mdl_fltrtitle-input').val(); // Filter : Search Input Text    
		var searchTag = $("input.mdl_fltrtag-input").tagsinput('items'); // Filter : Search Tag
		var searchsort = $("select#mdl_fltrsort-select").val(); // Filter : Search sort
		if($('#mdl_parentFolderId').val()==''){ var parentfolderid =  0 }else{ var parentfolderid =  $('#mdl_parentFolderId').val(); } // Filter : Parent Folder Id
		if($('#mdl_subFolderId').val()==''){ var subfolderid =  0 }else{ var subfolderid =  $('#mdl_subFolderId').val(); } // Filter : Sub Folder Id
		var searchTags='';
		searchTag.toString();
		$.each( searchTag, function( i, val ) {
			if(val!=''){
				searchTags += "'"+ val +"'";
			}
			if(i!=searchTag.length-1){
				searchTags += ', ';
			}
		});
		/*fetching imglist*/
		var dataToSend = {
			search_title   :searchText,
			search_tag     :searchTags,
			search_sort    :searchsort,
			fid            :parentfolderid,
			subfid         :subfolderid,
			slimit		   :mdl_fltr_limitcnt,
			elimit		   :10
		}; //console.log(dataToSend)
		$.ajax({
			type: 'POST',
			url: siteUrl + 'ajax/imgFilter',
			data: dataToSend,
			encode: true,
			cache: false,
			success: function(data) {
				var imgresponse = [JSON.parse(data)]; // console.log(response)
				if (imgresponse) {
					if(opt == 'init'){
						mdl_imgList.empty().addClass('hide');
						mdl_imgList.addClass('filtering');
					}
					if(popuprenderToImg(popupfilterImgRecords(imgresponse))){
						if(mdl_imgcount > 10 && mdl_itemcnt == 10){
							mdl_loadAjaxSend = true;
							mdl_imgList.addClass('filtering');
						}
					}
				}
			}
		});
		return true;
	}
	function popupfilterImgRecords(records) {
		/* TEST for DATA in ARRAY */
		var imgdemo = records; 
		var imgs;
		var flag = 0;
		mdl_imgcount = imgdemo[0].items.rescnt;
		mdl_itemcnt = imgdemo[0].items.itemcnt;
		for(var j=0;j<imgdemo.length;j++){
			flag = 1; // flag if RESPONSE contains data
			imgs = imgdemo[j].items.itemlist;
			break;
		}
		imgs = flag ? imgs : []; // if no content, imgdemo=0, otherwise imgdemo=[array]
		// console.log(imgs) // console.log(tags)
		return [imgs];
	}
	function popuprenderToImg(data) {
		var mdl_folderlib = $('.mdl_img-lib-folder');
		var mdl_imgList = $('#mdl_img_listing');
		var filteredImgFiles = [];
		var filteredTags = [];
		if(Array.isArray(data[0])) {
			data[0].forEach(function (d) {
				filteredImgFiles.push(d);
			});
		}
		/* Empty the old result and make the new one */
		if(!filteredImgFiles.length) {
			if (mdl_imgList.find('li.imgRecord').length) {
				mdl_folderlib.find('.nothingfound').hide();
			} else {
				mdl_folderlib.find('.nothingfound').show();
			}
			return false;
		} else {
			mdl_folderlib.find('.nothingfound').hide();
			filteredImgFiles.forEach(function(f) {
				if(f.img_url!='' && f.img_url!=null){
					var testedimg = f.img_url;
					var dummyicom = '';
				}else{
					var testedimg = '';
					var dummyicom = '<i class="fa fa-file-image-o datacol" style="font-size:50px;"></i>';
				}
				var attribute = 'data-itemid="'+f.img_id+'" data-itemname="'+f.img_title+'" data-itemurl="'+testedimg+'" data-itemtype="folder"';
				var rec = '<li class="imgRecord" id="'+f.img_id+'">';
					rec += '<div class="imgRecordDataFrame col-xs-12 col-sm-12">';
						rec += '<a href="javascript:void(0);" class="col-xs-10 col-sm-10 imgFrame-left" data-ajax="false" data-role="none">';
							rec += '<div class="col-xs-4 col-sm-4 mdl_thumb-img" '+attribute+' onclick="popuptriggerImgPrevModal(this);"'+(testedimg != '' ? ' style="background-image: url('+siteUrl + testedimg+');"' : '')+ '>'+dummyicom+'</div>';
							rec += '<div class="col-xs-8 col-sm-8 mdl_img-itemname" '+attribute+' onclick="popuptriggerImgOptionModal(this);">';
								rec += '<div class="altimgtitle break-img-name">'+f.img_title+'</div><div class="item-info">'+f.default+'</div>';
								filteredTags = f.taglist;
								var i=0; var tags=''; var taglist='';
								filteredTags.forEach(function(t) {
									if(f.img_id == t.img_id){
										if(i==0){
											tags += t.tag_title; taglist += t.tag_title; 
										}else{
											tags += ', '+t.tag_title; taglist += ','+t.tag_title;
										}
										i++;
									}
								});
								if(tags != ''){
									rec += '<div class="img-tags"><span class="info-bold">' + __('Tags') + ': </span>'+tags+'</div>';
								}
							rec += '</div>';
						rec += '</a>';
						rec += '<a href="javascript:void(0);" class="col-xs-2 col-sm-2 insert-this-img text-center imgFrame-right" '+attribute+' title="'+__('Insert this Image')+'" data-ajax="false" data-role="none"><div class="col-xs-12 col-sm-12"><i class="fa fa-sign-in iconsize2"></i></div></a>';
					rec += '</div>';
				rec += '</li>';
				var file = $(rec);
				file.appendTo(mdl_imgList);
			});
		}
		// Show the generated elements
		mdl_imgList.removeClass('hide');
		return true;
	}
	function popuptriggerSelectFolderModal(){
		$('#mdl_replaceflag').val('');
		if($('#mdl_parentfolder-div').hasClass('hide')==false){
			if($("#triggerid").val() == 4){
				$('#mdl_uploadfolderId').val(4);
				$('#mdl_subFolderId').val(4);
			}else{
				$('#mdl_uploadfolderId').val(5);
				$('#mdl_subFolderId').val(5);
			}
			$('#mdl_parentFolderId').val(1);
			$('#mdl_currentFolderId').val(1);
			popuptriggerUploader();
			return false;
		}
		if($('#mdl_subfolder-div').hasClass('hide')==false){
			$('#mdl_popupfldrslct-modal').modal();
			if($("#triggerid").val() == 4){
				if($("#mdl_chooseFolder .opt-row-detail button.btn-default").length > 0){
					$("#mdl_chooseFolder .opt-row-detail button.btn-default").each(function(keyi,valuej){
						if(keyi == 0){
							$(this).addClass('mdl_folder-select');
							$(this).find(".folderselect").removeClass('datacol').addClass('activedatacol');
						}else if(keyi == 1){
							$(this).removeClass('mdl_folder-select');
							$(this).find(".folderselect").removeClass('activedatacol').addClass('datacol');
						}
					});
				}
			}else{
				if($("#mdl_chooseFolder .opt-row-detail button.btn-default").length > 0){
					$("#mdl_chooseFolder .opt-row-detail button.btn-default").each(function(keyi,valuej){
						if(keyi == 0){
							$(this).removeClass('mdl_folder-select');
							$(this).find(".folderselect").removeClass('activedatacol').addClass('datacol');
						}else if(keyi == 1){
							$(this).addClass('mdl_folder-select');
							$(this).find(".folderselect").removeClass('datacol').addClass('activedatacol');
						}
					});
				}
			}
			return false;
		}
		else{
			popuptriggerUploader();
			$('#mdl_uploadfolderId').val($('#mdl_currentFolderId').val());
			return false;
		}
	}
	$(document).on('click', 'button.mdl_folder-select', function(){
		if($("#triggerid").val() == 4){
			$('#mdl_uploadfolderId').val(4);
		}else{
			$('#mdl_uploadfolderId').val(5);
		}
		if($('#mdl_currentFolderId').val()!=''){
			$('#mdl_subFolderId').val($(this).attr('id'));
		}else{
			$('#mdl_parentFolderId').val(1);
			$('#mdl_subFolderId').val($(this).attr('id'));
			$('#mdl_currentFolderId').val(1)
		}
		popuptriggerUploader();
		$('#mdl_popupfldrslct-modal').modal('hide');
	});
	function popuptriggerUploader(){
		$('.mdl_folder-section').addClass('hide');
		$('.mdl_uploader-section').removeClass('hide');
	}
	function popuptriggerImgEditorModal(){
		$('#mdl_popupimgact-modal').modal('hide');
		$('#mdl_popupimgprev-modal').modal('hide');
		$('#popupimgeditor-model').modal();
		$('.trigger_crop').attr('data-prefix', 'mdl_');
		setTimeout(function(){
			popupajaxInsertActivityfeed('opened', 'image');
		}, 350);
	}
	$(document).on('hidden.bs.modal', '#popupimgeditor-model.modal', function () {
		$('#mdl_croppedData').val('');
	});
	$(document).on('click','.mdl_thumb-img, .mdl_img-itemname, .mdl_upload-imgrow, .insert-this-img',function(){
		popuptriggerImgDataRevert('reset');
		var check = $(this).attr('data-itemtype');
		if(check=="upload"){
			$('.mdl_preview-opt>i').addClass('hide');
		}else{
			$('.mdl_preview-opt>i').removeClass('hide');
		}
		$('#mdl_curr_imgid').val($(this).attr('data-itemid'));
		if($(this).attr('data-itemurl')!=undefined && $(this).attr('data-itemurl')!=''){
			$('#mdl_imginsert-btn').attr('data-itemurl', $(this).attr('data-itemurl'));
			$('#mdl_imginsert-btn').prop('disabled',false);
		}else{
			$('#mdl_imginsert-btn').prop('disabled',true);
		}
		$('#mdl_replaceflag').val('');
	});
	$(document).on('click','#mdl_imginsert-btn, .insert-this-img',function(){
		var imgtagid = $('#data_XrImgs').attr('data-imgtagid');
		var hidnimgid = $('#data_XrImgs').attr('data-hidnimgid');
		var imglink = $(this).attr('data-itemurl');
		if($("#triggerid").length > 0 && $("#triggerid").val() == 4){
			imgtagid = 'profile_im';
			hidnimgid = imgtagid;
			$('#'+hidnimgid).attr('date-imgid', $('#mdl_curr_imgid').val());
		}
		if(imglink!=undefined && imglink!=''){
			$('#'+imgtagid).attr('src', siteUrl + imglink);
		}else{
			$('#'+imgtagid).attr('src', siteUrl + 'assets/images/icons/picture-icon.png');
		}
		$('#'+hidnimgid).val($('#mdl_curr_imgid').val());
		if(hidnimgid == 'xru_featImage'){
			$('#xrRecInsertForm').formValidation('revalidateField', 'xru_featImage');
		}else{
		}
		$('#mdl_popupimgact-modal').modal('hide');
		$('#mdl_popupimgprev-modal').modal('hide');
		$('#mdl_popupimglibrary-modal').modal('hide');
		if($("#triggerid").val() == 4){
			$("#userModal").modal('show');
		}
	});
	$('form#mdl_imglibrary_form').keypress( function( ev ) {
		var code = ev.keyCode || ev.which;
		if( code === 13 ) {
			ev.preventDefault();
			return false; 
		}
	});
	function popuptriggerResetTemplate () {
		$('#mdl_subFolderId').val('');
		$('#mdl_parentFolderId').val('');
		$('#mdl_uploaderBack').trigger('click');
		$('#mdl_progressBack').trigger('click');
	}
	$('body').on('hidden.bs.modal', '#mdl_popupimglibrary-modal', function(){
		popuptriggerResetTemplate();
	});
	function popuptriggerShowMoreImage() {
		var mdl_imgList = $('#mdl_img_listing');
		if (mdl_imgList.hasClass('filtering')) {
			mdl_fltr_limitcnt = mdl_fltr_limitcnt+10;
			popupfetchFilteredRecords('showmore');
		} else {
			popupfetchMoreRecords();
		}
		return false;
	}
	var mdl_loadAjaxSend = true;
	function popupAutoShowMore(){
		var x = 1,
			mdl_loopcnt = getAjaxSendCount();
		while (x <= mdl_loopcnt){
			mdl_loadAjaxSend = false;
			setTimeout(function() {
				if ($("#mdl_img_listing").is(":visible") && $("#mdl_img_listing").find("li.imgRecord").length && getBrowserZoomLevel() < 100) {
					popuptriggerShowMoreImage();
				}
			}, 250);
			x = x + 1;
		}
		return;
	}
	$(window).resize(function(ev) {
		if ($("#mdl_img_listing").length && $("#mdl_img_listing").is(':visible') && $("#mdl_img_listing").find('li.imgRecord').length && !$("#mdl_img_listing").hasVScrollBar()) {
			mdl_loadAjaxSend = false;
			setTimeout(function() {
				ev.preventDefault();
				if (ev.handled !== true) {
					ev.handled = true;
					popuptriggerShowMoreImage();
				}
			}, 200);
		}
	});
	/*folder section scripts end*/
	</script>
</div>