<div id="wrap-index">
<!-- Login header nav !-->
<?php echo $topHeader; $showflag = true; $userid = Auth::instance()->get_user()->pk(); Session::instance()->set('imgchecked', 0); ?>
<div class="container" id="home">
	<?php $session = Session::instance();
	if ($session->get('success')): ?>
		<div class="row bannermsg">
			<div class="col-sm-12 col-xs-12 col-md-12 banner success alert alert-success">
				<a data-ajax="false" data-role="none" href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<?php echo $session->get_once('success') ?>
			</div>
		</div>
	<?php endif;
	if ($session->get('error')): ?>
		<div class="row bannermsg">
			<div class="col-sm-12 col-xs-12 col-md-12 banner errors alert alert-danger">
				<a data-ajax="false" data-role="none" href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				<?php echo $session->get_once('error') ?>
			</div>
		</div>
	<?php endif; ?>
	<form method="post" id="imglibrary_form" enctype="multipart/form-data" data-ajax="false" data-role="none">
		<!-- img uploader start -->
		<div class="uploader-section hide" id="ImageUploader">
			<div class="row" id="uploader-head">
				<div class="page-head">
					<div class="col-xs-3 aligncenter">
						<a href="<?php echo URL::base(TRUE).'exercise/exerciseimages/'; ?>" id="uploaderBack" title="<?php echo __('Back'); ?>" data-ajax="false" data-role="none">
							<i class="fa fa-caret-left iconsize"></i>
						</a>
					</div>
					<div class="col-xs-6 aligncenter centerheight page-title"><?php echo __('Upload Images'); ?></div>
					<div class="col-xs-3 aligncenter"></div>
				</div>
			</div>
			<div class="row hide" id="progress-head">
				<div class="page-head">
					<div class="col-xs-3 aligncenter">
						<a href="javascript:void(0);" id="progressBack" title="<?php echo __('Back'); ?>" data-ajax="false" data-role="none">
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
						<div class="listing-header header-toggle1 header-progress hide">
							<div class="row">
								<div class="col-xs-12">
									<div class="col-xs-9 header-cell aligncenter" title="<?php echo __('Progress Status'); ?>">
										<i class="fa fa-clock-o iconsize2 activedatacol col-xs-3"></i>
										<div id="progressbarOuter" class="progress progress-striped active col-xs-9" style="display:none;">
											<div id="progressBar" class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
										</div>
									</div>
									<div class="col-xs-3 header-cell aligncenter imgcheck-opt" title="<?php echo __('Select Images'); ?>">
										<i class="fa fa-list-ul iconsize2 activedatacol"></i>
									</div>
								</div>
							</div>
							<hr>
						</div>
						<div class="listing-header header-toggle2 header-progress-opt" style="display: none;">
							<div class="row">
								<div class="col-xs-12">
									<div class="col-xs-4 header-cell aligncenter check-image" title="<?php echo __('Check All'); ?>">
										<i class="fa fa-check-circle-o iconsize2 activedatacol" onclick="checkAllItems(this);"></i>
									</div>
									<div class="col-xs-4 header-cell aligncenter checked-opt" title="<?php echo __('Options'); ?>">
										<i class="fa fa-ellipsis-h iconsize2 activedatacol"></i>
									</div>
									<div class="col-xs-4 header-cell aligncenter imgcheck-opt" title="<?php echo __('Hide Select'); ?>">
										<i class="fa fa-refresh iconsize2 activedatacol"></i>
									</div>
								</div>
							</div>
							<hr>
						</div>
						<div class="upload-div">
							<div class="uploadimage-dragndrop" id="dragndropimage">
								<div class=""><span class="fa fa-upload fa-3x"></span></div>
								<div class="uploadimage-text"><?php echo __('Drag image here to upload'); ?></div>
							</div>
							<div class="input-file">
								<input class="imageupload" id="files" type="file" name="images[]" multiple="" accept="image/*" data-ajax="false" data-role="none">
							</div>
							<div class="prefer-text"><?php echo __('Or, if you prefer'); ?>...</div>
							<button class="btn btn-primary" title="<?php echo __('Upload Image File'); ?>" id="image_upload" type="button" data-ajax="false" data-role="none">
								<span class="fa fa-upload"></span>&nbsp;&nbsp;<?php echo __('Upload Image'); ?>
							</button>
							<div id="uploadError" class="upload-error"></div>
						</div>
						<ul id="uploadListing" class="img-listing"></ul>
					</div>
				</div>
				<input name="uploadfolderId" id="uploadfolderId" type="hidden" value="0">
				<div class="clear"></div>
			</div>
		</div><!-- img uploader end -->

		<!-- img folder start-->
		<div class="folder-section" id="ImageFolders">
			<!-- forlder start -->
			<div class="img-lib-folder">
				<div class="row">
					<div class="page-head">
						<div class="col-xs-3 aligncenter">
							<a href="<?php echo (!empty($foldername) && count($foldername) > 0 && !empty($parentFolderId) && !empty($subFolderId) ? URL::base(TRUE).'exercise/exerciseimages/'.$parentFolderId : (!empty($foldername) && count($foldername) > 0 && !empty($parentFolderId) && empty($subFolderId) ? URL::base(TRUE).'exercise/exerciseimages/' : URL::base(TRUE).'dashboard/index/')); ?>" title="<?php echo __('Back'); ?>" data-ajax="false" data-role="none">
								<i class="fa fa-caret-left iconsize"></i>
							</a>
						</div>
						<div class="col-xs-6 aligncenter centerheight page-title">
							<?php if(!empty($foldername) && count($foldername) > 0){
								echo __(ucfirst($foldername[0]['folder_title']));
							}else{
								echo __('Images');
							}?>
							<input type="hidden" id="parentFolderId" name="parentFolderId" value="<?php echo (!empty($foldername)) ? ($parentFolderId==0 ? $foldername[0]['folder_id'] : $parentFolderId) : ''; ?>">
							<input type="hidden" id="subFolderId" name="subFolderId" value="<?php echo (!empty($foldername)) ? ($parentFolderId==1 && ($foldername[0]['folder_id']==4 || $foldername[0]['folder_id']==5)? $foldername[0]['folder_id'] : '') : ''; ?>">
							<input type="hidden" id="currentFolderId" name="currentFolderId" value="<?php echo (!empty($foldername)) ? $foldername[0]['folder_id'] : ''; ?>">
						</div>
						<div class="col-xs-3 aligncenter">
							<div class="filter-search<?php echo (!empty($foldername) && $foldername[0]['folder_id']!=1 ? '' : ' hide'); ?> tour-step tour-step-6" title="<?php echo __('Search Images'); ?>"> 
								<a href="#" data-toggle="modal" data-target="#popupfilteract-modal" data-ajax="false" data-role="none"><i class="fa fa-search iconsize2"></i></a>
							</div>
						</div>
					</div>
				</div>
				<hr>
				<div id="imgupload-link" class="<?php if(!empty($folderitem['itemlist']) || (isset($foldername) && ($parentFolderId==2 || $parentFolderId==6 || $parentFolderId==0))){ echo "hide"; }?>">
					<div class="row tour-step tour-step-uploadimg">
						<a href="javascript:void(0);" id="upload-image" class="upload-image" title="<?php echo __('Upload Images'); ?>" onclick="triggerSelectFolderModal();" data-ajax="false" data-role="none">
							<div class="col-xs-12">
								<div class="header-cell">
									<div class="col-xs-3 aligncenter">
										<i class="fa fa-plus iconsize2 activedatacol"></i>
									</div>
									<div class="col-xs-6 activedatacol">
										<?php echo __('Upload Images'); ?>
									</div>
									<div class="col-xs-3"></div>
								</div>
							</div>
						</a>
					</div>
					<hr>
				</div>
				<div id="parentfolder-div" class="<?php if(!empty($partentfolder) && count($partentfolder) > 0){ $showflag = false; }else{ echo "hide"; } ?>">
					<?php if(!empty($partentfolder) && count($partentfolder) > 0){ 
						$foldercount=count($partentfolder)-1;
						foreach($partentfolder as $key=>$value){ ?>
							<div class="row tour-step tour-step-<?php echo $value['folder_id']; ?>">
								<a href="<?php if(empty($value['countval']) || $value['countval'] == 0){ echo 'javascript:void(0);'; }else{ echo URL::base(TRUE).'exercise/exerciseimages/'.$value['folder_id']; } ?>" class="folderclk-btn" id="<?php echo $value['folder_id']; ?>" data-ajax="false" data-role="none">
									<div class="col-xs-12 page-head-row">
										<div class="col-xs-3 aligncenter">
											<i class="fa fa-folder-o iconsize2 <?php if(empty($value['countval']) || $value['countval'] == 0){ echo 'datacol'; }else{ echo 'activedatacol'; } ?>"></i>
										</div>
										<div class="col-xs-6 folderclick <?php if(empty($value['countval']) || $value['countval'] == 0){ echo 'datacol'; }else{ echo 'activedatacol'; } ?>">
											<?php echo __(ucfirst($value['folder_title'])).'&nbsp;('.number_format($value['countval']); ?>)
										</div>
										<div class="col-xs-3"></div>
									</div>
								</a>
							</div>
							<?php if($foldercount!=$key){ echo "<hr>"; }
						}
					} ?>
				</div>
				<div id="subfolder-div" class="<?php if(!empty($subfolders) && count($subfolders) > 0){ $showflag = false; }else{ echo "hide"; } ?>">
					<?php if(!empty($subfolders) && count($subfolders) > 0){
						$subfoldercount=count($subfolders)-1;
						foreach($subfolders as $subkey=>$subvalue){ ?>
							<div class="row tour-step tour-step-<?php echo $subvalue['folder_id']; ?>">
								<a href="<?php if($parentFolderId!=1 && $exerciseimgcnt==0){ echo 'javascript:void(0);'; }else{ echo URL::base(TRUE).'exercise/exerciseimages/'.$parentFolderId.'/'.$subvalue['folder_id']; ?>" id="<?php echo $subvalue['folder_id']; } ?>" class="folderclk-btn" data-ajax="false" data-role="none">
									<div class="col-xs-12 page-head-row">
										<div class="col-xs-3 aligncenter">
											<i class="fa fa-folder-o iconsize2 <?php if($parentFolderId!=1 && $exerciseimgcnt==0){ echo 'datacol'; }else{ echo 'activedatacol'; } ?>"></i>
										</div>
										<div class="col-xs-6 folderclick <?php if($parentFolderId!=1 && $exerciseimgcnt==0){ echo 'datacol'; }else{ echo 'activedatacol'; } ?>">
											<?php echo __(ucfirst($subvalue['folder_title'])).'&nbsp;('; if($subvalue['folder_id']==4){ echo number_format($profileimgcnt); }elseif($subvalue['folder_id']==5){ echo number_format($exerciseimgcnt); } ?>)
										</div>
										<div class="col-xs-3"></div>
									</div>
								</a>
							</div>
							<?php if($subfoldercount!=$subkey){ echo "<hr>"; }
						}
					} ?>
				</div>
				<!-- img item listing start-->
				<?php if(isset($folderitem['itemlist']) && count($folderitem['itemlist'])>0){ $item='';
					if($folderitem['itemlist'][0]['parentfolder_id']!=2 && $folderitem['itemlist'][0]['parentfolder_id']!=6){ ?>
					<div class="listing-header header-toggle1 checkopt-header">
						<div class="row">
							<div class="col-xs-12">
								<a href="javascript:void(0);" class="upload-image" title="<?php echo __('Upload Images'); ?>" onclick="triggerSelectFolderModal();" data-ajax="false" data-role="none">
									<div class="col-xs-9 header-cell aligncenter">
										<i class="fa fa-plus iconsize2 activedatacol tour-step tour-step-9"></i>
									</div>
								</a>
								<div class="col-xs-3 header-cell aligncenter imgcheck-opt" title="<?php echo __('Select Images'); ?>">
									<i class="fa fa-list-ul iconsize2 activedatacol tour-step tour-step-10"></i>
								</div>
							</div>
						</div>
						<hr>
					</div>
					<div class="listing-header header-toggle2 check-header" style="display: none;">
						<div class="row">
							<div class="col-xs-12">
								<div class="col-xs-4 header-cell aligncenter check-image" title="<?php echo __('Check All'); ?>">
									<i class="fa fa-check-circle-o iconsize2 activedatacol tour-step tour-step-11" onclick="checkAllItems(this);"></i>
								</div>
								<div class="col-xs-4 header-cell aligncenter checked-opt" title="<?php echo __('Options'); ?>">
									<i class="fa fa-ellipsis-h iconsize2 activedatacol tour-step tour-step-12"></i>
								</div>
								<div class="col-xs-4 header-cell aligncenter imgcheck-opt" title="<?php echo __('Hide Select'); ?>">
									<i class="fa fa-refresh iconsize2 activedatacol tour-step tour-step-13"></i>
								</div>
							</div>
						</div>
						<hr>
					</div>
					<?php } ?>
					<input type="hidden" id="filter_fid" name="filter_fid" value="<?php echo $folderitem['itemlist'][0]['parentfolder_id']; ?>">
					<input type="hidden" id="filter_subfid" name="filter_subfid" value="<?php echo $folderitem['itemlist'][0]['subfolder_id']; ?>">
					<div class="row"><div class="col-xs-12"><div class="col-xs-3 tour-step tour-step-7"></div><div class="col-xs-7 tour-step tour-step-8"></div></div></div>
					<ul class="img-listing" id="img_listing">
						<?php foreach($folderitem['itemlist'] as $keys => $values){ 
							$attributes = 'data-itemid="'.$values['img_id'].'" data-itemname="'.ucfirst($values['img_title']).'" data-itemurl="'.$values['img_url'].'" data-itemtype="folder"';
							?>
							<li class="imgRecord" id="<?php echo $values['img_id']; ?>">
								<div class="imgRecordDataFrame col-xs-12 col-sm-12">
									<a href="javascript:void(0);" class="col-xs-12 col-sm-12 imgFrame-full" data-ajax="false" data-role="none">
										<div class="checkbox-checker col-xs-2 col-sm-2" style="display: none;">
											<div class="checkboxcolor">
												<label>
													<input data-role="none" data-ajax="false" type="checkbox" class="checkhidden" name="check_act[]" value="<?php echo $values['img_id']; ?>">
													<span class="cr checkbox-circle"><i class="cr-icon fa fa-check"></i></span>
												</label>
											</div>
										</div>
										<div class="col-xs-3 col-sm-3 thumb-img" <?php echo $attributes; ?> onclick="triggerImgPrevModal(this);" 
											<?php if(!empty($values['img_url']) && file_exists($values['img_url'])){
												echo 'style="background-image: url('.URL::base().$values['img_url'].');">';
											} else { ?>
												><i class="fa fa-file-image-o datacol" style="font-size:50px;"></i>
											<?php } ?>
										</div>
										<div class="col-xs-7 col-sm-7 img-itemname" <?php echo $attributes; ?> onclick="triggerImgOptionModal(this);">
											<div class="altimgtitle break-img-name"><?php echo ucfirst($values['img_title']); ?></div>
											<div class="item-info"><?php echo $values['default']; ?></div>
											<?php $i=0; $tags = ''; $taglist = '';
											if(!empty($values['taglist']) && count($values['taglist'])>0){
												foreach($values['taglist'] as $tagkeys => $tagvalues){
													if($tagvalues['img_id'] == $values['img_id']){
														if($i==0){
															$tags .= $tagvalues['tag_title']; $taglist .= $tagvalues['tag_title'];
														} else {
															$tags .= ', '.$tagvalues['tag_title']; $taglist .= ','.$tagvalues['tag_title'];
														} ?>
													<?php $i++;
													}
												}
												if($tags != ''){ ?>
													<div class="img-tags"><span class="info-bold"><?php echo __('Tags'); ?>: </span><?php echo $tags; ?></div>
												<?php }
											} ?>
											<input type="hidden" id="img_tags<?php echo $values['img_id']; ?>" value="<?php echo $taglist; ?>">
										</div>
									</a>
								</div>
							</li>
						<?php } ?>
					</ul><!-- img item listing end -->
					<div class="nothingfound" style="display: none;">
						<div class="nofiles"></div>
						<span><?php echo __('No image files here'); ?>.</span>
					</div>
				<?php }elseif($showflag){ ?>
					<div class="nothingfound aligncenter">
						<div class="nofiles"></div>
						<span><?php echo __('No image files here'); ?>.</span>
					</div>
				<?php } ?>
			</div><!-- folder end -->

			<!-- img data start -->
			<div class="exercise-lib-imgdata hide">
				<div id="image_data_form">
					<div class="row">
						<div class="page-head">
							<div class="col-xs-3 aligncenter">
								<a href="javascript:void(0);" class="confirm" title="<?php echo __('Back'); ?>" id="imgdataBack" data-onclick="triggerImageDataBack();" data-databack="" data-backurl="" data-allow="<?php echo (Helper_Common::getAllowAllAccessByUser((Session::instance()->get('user_allow_page') ? Session::instance()->get('user_allow_page') : '1'), 'is_confirm_image_hidden') ? 'false' : 'true'); ?>" data-notename="hide_confirm_image" data-text="Clicking BACK or CANCEL will discard any changes. Clicking MORE will display record options such as SAVE. Continue with exiting?" data-ajax="false" data-role="none">
									<i class="fa fa-caret-left iconsize"></i>
								</a>
							</div>
							<div class="col-xs-6 aligncenter centerheight page-title"><?php echo __('Image Data'); ?></div>
							<div class="col-xs-3 aligncenter">
								<a href="javascript:void(0);" class="btn btn-default activedatacol" onclick="triggerEditingOption(this);" data-prefix="" data-ajax="false" data-role="none"><?php echo __('more'); ?></a>
							</div>
						</div>
					</div>
					<hr>
					<div class="form-group">
						<div class="row">
							<div class="col-sm-4"><label class="control-label"><?php echo __('Image Title'); ?>:</label></div>
							<div class="col-sm-8">
								<input type="text" tabindex="1" class="form-control" id="imgdata-title" value="" name="imgdata-title" placeholder="Image Title" data-ajax="false" data-role="none"/>
							</div>
						</div>
					</div>
					<?php if(isset($exerciseStatus) && count($exerciseStatus)>0) { ?>
					<div class="form-group">
						<div class="row">
							<div class="col-sm-4"><label class="control-label"><?php echo __('Status'); ?>:</label></div>
							<div class="col-sm-8">
								<div class="dropdown selectdropdownTwo imgstatus-select">
									<select tabindex="2" class="" id="imgdata-status" name="imgdata-status" data-ajax="false" data-role="none">
										<option value="">Select an option</option>
										<?php foreach($exerciseStatus as $key => $value) { ?>
											<option value="<?php echo $value['status_id']; ?>"<?php if(isset($exerciseArray['status_id'])&&$exerciseArray['status_id']==$value['status_id']) echo "selected";?>><?php echo $value['status_title']; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
						</div>
					</div>
					<?php } ?>
					<div class="form-group">
						<div class="row">
							<div class="col-sm-4"><label class="control-label"><?php echo __('Tags'); ?>:</label></div>
							<div class="col-sm-8">
								<div tabindex="3" data-enhance="false" data-role="none" data-ajax="false">
									<input type="text" class="form-control imgdata-tag" name="imgdata-tag" value="" placeholder="Tags" data-role="tagsinput" data-ajax="false"/>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div><!-- img data end -->
		</div><!-- img folder end -->

		<!-- preview image modal -->
		<div id="popupimgprev-modal" class="modal fade" role="dialog">
			<div class="vertical-alignment-helper">
				<div class="modal-dialog">
					<div class="modal-content aligncenter">
						<div class="modal-header">
							<div class="mobpadding">
								<div class="border">
									<div class="col-xs-2">
										<a href="javascript:void(0);" title="<?php echo __('Back'); ?>" class="triangle" onclick="$('#popupimgprev-modal').modal('hidecustom');" data-ajax="false" data-role="none">
											<i class="fa fa-caret-left iconsize"></i>
										</a>
									</div>
									<div class="col-xs-8 optionpoptitle"><?php echo __('Preview Image'); ?></div>
									<div class="col-xs-2 preview-opt"><button type="button" class="btn btn-default activedatacol" id="prevmdloption"><?php echo __('more'); ?></button></div>
								</div>
							</div>
							<hr>
							<div class="row">
								<div class="popup-title">
									<div class="col-xs-12" style="font-size: .9em;"><span id="preview-imgname" class="break-img-name recordtitle"></span></div>
								</div>
							</div>
						</div>
						<div class="modal-body" id="preview_libimg">
							<i class="fa fa-file-image-o prevfeat"></i>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" onclick="$('#popupimgprev-modal').modal('hidecustom');" data-ajax="false" data-role="none"><?php echo __('Close'); ?></button>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- main image action modal -->
		<div id="popupimgact-modal" class="modal fade" role="dialog">
			<div class="vertical-alignment-helper">
				<div class="modal-dialog modal-md">
					<div class="modal-content">
						<div class="modal-header">
							<div class="mobpadding">
								<div class="border">
									<div class="col-xs-2">
										<a href="javascript:void(0);" title="<?php echo __('Back'); ?>" onclick="$('#popupimgact-modal').modal('hidecustom');" class="triangle" data-ajax="false" data-role="none">
											<i class="fa fa-chevron-left"></i>
										</a>
									</div>
									<div class="col-xs-8 optionpoptitle"><?php echo __('Options for this Image'); ?></div>
									<div class="col-xs-2"></div>
								</div>
							</div>
							<hr>
							<div class="row">
								<div class="popup-title">
									<div class="col-xs-12" style="font-size: .9em;"><span class="break-img-name recordtitle imgTitle"></span></div>
								</div>
							</div>
						</div>
						<div class="modal-body opt-body">
							<input type="hidden" id="curr_imgid" name="curr_imgid" value=""/>
							<div class="opt-row-detail">
								<button class="btn btn-default" id="preview-btn" onclick="triggerImgPrevModal(this);" value="preview" type="button" style="width:100%" data-ajax="false" data-role="none">
									<div class="col-xs-12 pointer">
										<div class="col-xs-3"><i class="fa fa-eye iconsize"></i></div>
										<div class="col-xs-9"><?php echo __('Preview'); ?></div>
									</div>
								</button>
							</div>
							<?php //if($parentFolderId==1){ ?>
							<div class="opt-row-detail allowedit">
								<button class="btn btn-default" id="editimg-btn" onclick="triggerImgEditorModal();" value="edit-image" type="button" style="width:100%" data-ajax="false" data-role="none">
									<div class="col-xs-12 pointer">
										<div class="col-xs-3"><i class="fa fa-picture-o iconsize"></i></div>
										<div class="col-xs-9"><?php echo __('Edit Image'); ?></div>
									</div>
								</button>
							</div>
							<div class="opt-row-detail allowedit">
								<button class="btn btn-default" id="imgdata-btn" onclick="" value="edit-data" type="button" style="width:100%" data-ajax="false" data-role="none">
									<div class="col-xs-12 pointer">
										<div class="col-xs-3"><i class="fa fa-pencil-square-o iconsize"></i></div>
										<div class="col-xs-9"><?php echo __('Edit Data'); ?></div>
									</div>
								</button>
							</div>
							<?php //} if($subFolderId!=4){ ?>
							<div class="opt-row-detail allowopt">
								<button class="btn btn-default" id="imgtag-btn" onclick="triggerImgTagModal();" value="tag" type="button" style="width:100%" data-ajax="false" data-role="none">
									<div class="col-xs-12 pointer">
										<div class="col-xs-3"><i class="fa fa-tag iconsize"></i></div>
										<div class="col-xs-9"><?php echo __('Tag'); ?></div>
									</div>
								</button>
							</div>
							<div class="opt-row-detail allowopt">
								<button class="btn btn-default" id="imgduplicate-btn" name="duplicateimg" onclick="return triggerImgDuplicate();" value="duplicate" type="submit" style="width:100%" data-ajax="false" data-role="none">
									<div class="col-xs-12 pointer">
										<div class="col-xs-3"><i class="fa fa-files-o iconsize"></i></div>
										<div class="col-xs-9"><?php echo __('Duplicate this Images'); ?></div>
									</div>
								</button>
							</div>
							<?php //} if($parentFolderId==1){ ?>
							<div class="opt-row-detail allowedit">
								<button class="btn btn-default" id="imgraplace-btn" onclick="triggerImgReplace();" value="replace" type="button" style="width:100%" data-ajax="false" data-role="none">
									<div class="col-xs-12 pointer">
										<div class="col-xs-3"><i class="fa fa-mail-reply iconsize"></i></div>
										<div class="col-xs-9"><?php echo __('Replace'); ?></div>
									</div>
								</button>
							</div>
							<div class="opt-row-detail allowedit">
								<button class="btn btn-default" id="imgdelete-btn" name="delete_btn" onclick="return triggerImgDelete();" value="delete" type="submit" style="width:100%" data-ajax="false" data-role="none">
									<div class="col-xs-12 pointer">
										<div class="col-xs-3"><i class="fa fa-times iconsize"></i></div>
										<div class="col-xs-9"><?php echo __('Delete'); ?></div>
									</div>
								</button>
							</div>
							<?php //} ?>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" onclick="$('#popupimgact-modal').modal('hidecustom');" data-ajax="false" data-role="none"><?php echo __('Close'); ?></button>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- selected images action modal -->
		<div id="popupchekdact-modal" class="modal fade" role="dialog">
			<div class="vertical-alignment-helper">
				<div class="modal-dialog modal-md">
					<div class="modal-content">
						<div class="modal-header">
							<div class="mobpadding">
								<div class="border">
									<div class="col-xs-2">
										<a href="javascript:void(0);" title="<?php echo __('Back'); ?>" onclick="$('#popupchekdact-modal').modal('hidecustom');" class="triangle" data-ajax="false" data-role="none">
											<i class="fa fa-chevron-left"></i>
										</a>
									</div>
									<div class="col-xs-8 optionpoptitle"><?php echo __('Options for the Selected Images'); ?></div>
									<div class="col-xs-2"></div>
								</div>
							</div>
						</div>
						<div class="modal-body opt-body">
							<div class="opt-row-detail">
								<button class="btn btn-default" onclick="triggerCheckedTag();" value="tag" type="button" style="width:100%" data-ajax="false" data-role="none">
									<div class="col-xs-12 pointer">
										<div class="col-xs-3"><i class="fa fa-tag iconsize"></i></div>
										<div class="col-xs-9"><?php echo __('Tag'); ?></div>
									</div>
								</button>
							</div>
							<div class="opt-row-detail">
								<button class="btn btn-default" onclick="triggerCheckedStatus();" value="status" type="button" style="width:100%" data-ajax="false" data-role="none">
									<div class="col-xs-12 pointer">
										<div class="col-xs-3"><i class="fa fa-pencil-square iconsize"></i></div>
										<div class="col-xs-9"><?php echo __('Status'); ?></div>
									</div>
								</button>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" onclick="$('#popupchekdact-modal').modal('hidecustom');" data-ajax="false" data-role="none"><?php echo __('Close'); ?></button>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- image tag modal -->
		<div id="popupimgtag-modal" class="modal fade" role="dialog">
			<div class="vertical-alignment-helper">
				<div class="modal-dialog modal-md">
					<div class="modal-content">
						<div class="modal-header">
							<div class="mobpadding">
								<div class="border">
									<div class="col-xs-2">
										<a href="javascript:void(0);" title="<?php echo __('Back'); ?>" onclick="$('#popupimgtag-modal').modal('hidecustom');" class="triangle" data-ajax="false" data-role="none">
											<i class="fa fa-chevron-left"></i>
										</a>
									</div>
									<div class="col-xs-8 optionpoptitle"><?php echo __('Image Tags'); ?></div>
									<div class="col-xs-2"></div>
								</div>
							</div>
							<hr>
							<div class="row">
								<div class="popup-title">
									<div class="col-xs-12" style="font-size: .9em;"><span class="break-img-name recordtitle imgTitle tag-imgname"></span></div>
								</div>
							</div>
						</div>
						<div class="modal-body opt-body">
							<div class="opt-row-detail">
								<div class="row">
									<div class="col-xs-12"><label class="control-label"><?php echo __('Tags'); ?>:</label></div>
									<div class="col-xs-12">
										<div tabindex="1" data-enhance="false" data-role="none" data-ajax="false">
											<input type="text" class="form-control imgtag-input" name="imgtag-input" value="" placeholder="Tags" data-role="tagsinput" data-ajax="false"/>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="submit" class="btn btn-default pull-left" id="btn-inserttag" name="insertimgtag" value="inserttag" data-imgid="" data-ajax="false" data-role="none"><?php echo __('Insert'); ?></button>
							<button type="button" class="btn btn-default pull-right" onclick="$('#popupimgtag-modal').modal('hidecustom');" data-ajax="false" data-role="none"><?php echo __('Close'); ?></button>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- image status modal -->
		<div id="popupimgstatus-modal" class="modal fade" role="dialog">
			<div class="vertical-alignment-helper">
				<div class="modal-dialog modal-md">
					<div class="modal-content">
						<div class="modal-header">
							<div class="mobpadding">
								<div class="border">
									<div class="col-xs-2">
										<a href="javascript:void(0);" title="<?php echo __('Back'); ?>" onclick="$('#popupimgstatus-modal').modal('hidecustom');" class="triangle" data-ajax="false" data-role="none">
											<i class="fa fa-chevron-left"></i>
										</a>
									</div>
									<div class="col-xs-8 optionpoptitle"><?php echo __('Image Status'); ?></div>
									<div class="col-xs-2"></div>
								</div>
							</div>
						</div>
						<div class="modal-body opt-body">
							<div class="opt-row-detail">
								<?php if(isset($exerciseStatus) && count($exerciseStatus)>0) { ?>
									<div class="row">
										<div class="col-sm-12"><label class="control-label"><?php echo __('Status'); ?>:</label></div>
										<div class="col-sm-12">
											 <div class="dropdown selectdropdownTwo imgstatus-select">
												<select tabindex="1" class="" id="imgchecked-status" name="imgchecked-status" data-ajax="false" data-role="none">
													<option value="">Select an option</option>
													<?php foreach($exerciseStatus as $key => $value) { ?>
														<option value="<?php echo $value['status_id']; ?>"<?php if(isset($exerciseArray['status_id']) && $exerciseArray['status_id']==$value['status_id']) echo "selected"; ?>><?php echo $value['status_title']; ?></option>
													<?php } ?>
												</select>
											</div>
										</div>
									</div>
								<?php } ?>
							</div>
						</div>
						<div class="modal-footer">
							<button type="submit" class="btn btn-default pull-left" id="btn-changestatus" name="changeimgstatus" value="changestatus" onclick="return triggerChangeStatus();" data-imgid="" data-ajax="false" data-role="none"><?php echo __('Change'); ?></button>
							<button type="button" class="btn btn-default pull-right" onclick="$('#popupimgstatus-modal').modal('hidecustom');" data-ajax="false" data-role="none"><?php echo __('Close'); ?></button>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- save action modal -->
		<div id="popupfinalact-modal" class="modal fade" role="dialog">
			<div class="vertical-alignment-helper">
				<div class="modal-dialog modal-md">
					<div class="modal-content">
						<div class="modal-header">
							<div class="mobpadding">
								<div class="border">
									<div class="col-xs-2">
										<a href="javascript:void(0);" title="<?php echo __('Back'); ?>" onclick="$('#popupfinalact-modal').modal('hidecustom');" class="triangle" data-ajax="false" data-role="none">
											<i class="fa fa-chevron-left"></i>
										</a>
									</div>
									<div class="col-xs-8 optionpoptitle"><?php echo __('Options for Saving'); ?></div>
									<div class="col-xs-2"></div>
								</div>
							</div>
							<hr>
							<div class="row">
								<div class="popup-title">
									<div class="col-xs-12" style="font-size: .9em;"><span class="break-img-name recordtitle imgTitle"></span></div>
								</div>
							</div>
						</div>
						<div class="modal-body opt-body">
							<div class="opt-row-detail">
								<button class="btn btn-default" id="btn_saveclose" onclick="" value="saveclose" name="saveimgdata" type="submit" style="width:100%" data-ajax="false" data-role="none">
									<div class="col-xs-12 pointer">
										<div class="col-xs-3"><i class="fa fa-save iconsize"></i></div>
										<div class="col-xs-9"><?php echo __('Save & Close'); ?></div>
									</div>
								</button>
							</div>
							<div class="opt-row-detail">
								<button class="btn btn-default" id="btn_savecontn" onclick="" value="savecontinue" name="saveimgdata" type="submit" style="width:100%" data-ajax="false" data-role="none">
									<div class="col-xs-12 pointer">
										<div class="col-xs-3"><i class="fa fa-pencil-square-o iconsize"></i></div>
										<div class="col-xs-9"><?php echo __('Save & Continue Editing'); ?></div>
									</div>
								</button>
							</div>
							<div class="opt-row-detail">
								<button class="btn btn-default confirm" id="btn_revertdata" data-onclick="triggerImgDataRevert();" value="imgdata" type="button" data-allow="<?php echo (Helper_Common::getAllowAllAccessByUser((Session::instance()->get('user_allow_page') ? Session::instance()->get('user_allow_page') : '1'), 'is_confirm_image_hidden') ? 'false' : 'true'); ?>" data-notename="hide_confirm_image" data-text="This will discard any changes on this record. Do you want to SAVE or Continue with exiting?" style="width:100%" data-ajax="false" data-role="none">
									<div class="col-xs-12 pointer">
										<div class="col-xs-3"><i class="fa fa-refresh iconsize"></i></div>
										<div class="col-xs-9"><?php echo __('Reset / Revert to Saved'); ?></div>
									</div>
								</button>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" onclick="$('#popupfinalact-modal').modal('hidecustom');" data-ajax="false" data-role="none"><?php echo __('Close'); ?></button>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- choose folder modal -->
		<div id="popupfldrslct-modal" class="modal fade" role="dialog">
			<div class="vertical-alignment-helper">
				<div class="modal-dialog modal-md">
					<div class="modal-content">
						<div class="modal-header">
							<div class="mobpadding">
								<div class="border">
									<div class="col-xs-2">
										<a href="javascript:void(0);" title="<?php echo __('Back'); ?>" onclick="$('#popupfldrslct-modal').modal('hidecustom');" class="triangle" data-ajax="false" data-role="none">
											<i class="fa fa-chevron-left"></i>
										</a>
									</div>
									<div class="col-xs-8 optionpoptitle"><?php echo __('Select Folder To Upload Images'); ?></div>
									<div class="col-xs-2"></div>
								</div>
							</div>
						</div>
						<div class="modal-body opt-body">
							<div id="chooseFolder">
								<div class="opt-row-detail">
									<button type="button" class="btn btn-default folder-select" id="4" style="width:100%" data-ajax="false" data-role="none">
										<div class="col-xs-12 pointer">
											<div class="col-xs-3"><i class="fa fa-folder-o iconsize2 activedatacol"></i></div>
											<div class="col-xs-9 activedatacol"><?php echo __('Profile Images'); ?></div>
										</div>
									</button>
								</div>
								<div class="opt-row-detail">
									<button type="button" class="btn btn-default folder-select" id="5" style="width:100%" data-ajax="false" data-role="none">
										<div class="col-xs-12 pointer">
											<div class="col-xs-3"><i class="fa fa-folder-o iconsize2 activedatacol"></i></div>
											<div class="col-xs-9 activedatacol"><?php echo __('Exercise Images'); ?></div>
										</div>
									</button>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default pull-right" onclick="$('#popupfldrslct-modal').modal('hidecustom');" data-ajax="false" data-role="none"><?php echo __('Close'); ?></button>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="imgDataUrl">
			<input type="hidden" id="croppedData" name="croppedData" value=""/>
			<input type="hidden" id="replaceflag" value=""/>
		</div>
	</form>
</div>

<!-- image filtering modal -->
<div id="popupfilteract-modal" class="modal fade" role="dialog">
	<div class="vertical-alignment-helper">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<form action="" id="filteract-form" data-ajax="false" data-role="none">
					<div class="modal-header">
						<div class="mobpadding">
							<div class="border">
								<div class="col-xs-2">
									<a href="javascript:void(0);" title="<?php echo __('Back'); ?>" onclick="$('#popupfilteract-modal').modal('hidecustom');" class="triangle" data-ajax="false" data-role="none">
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
									<input type="text" tabindex="1" class="form-control" id="fltrtitle-input" value="" placeholder="Image Title" data-ajax="false" data-role="none"/>
								</div>
							</div>
						</div>
						<div class="opt-row-detail">
							<div class="row">
								<div class="col-xs-12"><label class="control-label"><?php echo __('Tags'); ?>:</label></div>
								<div class="col-xs-12">
									<div tabindex="2" data-enhance="false" data-role="none" data-ajax="false">
										<input type="text" class="form-control fltrtag-input" value="" placeholder="Tags" data-role="tagsinput" data-ajax="false"/>
									</div>
								</div>
							</div>
						</div>
						<div class="opt-row-detail">
							<div class="row">
								<div class="col-sm-12"><label class="control-label"><?php echo __('Sort By'); ?>:</label></div>
								<div class="col-sm-12">
									<div class="dropdown selectdropdownTwo filtersort-select">
										<select class="fltrsort-select" id="fltrsort-select" tabindex="3" data-ajax="false" data-role="none">
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
						<button class="btn btn-default pull-left" id="btn-filterreset" value="reset" type="button" data-ajax="false" data-role="none"><?php echo __('Reset'); ?></button>
						<button class="btn btn-default pull-right" id="btn-filterfetch" value="fetch" data-ajax="false" data-role="none"><?php echo __('Fetch'); ?></button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
/*save and save & continue action variables*/
var saveaction = '<?php echo isset($saveaction) && !empty($saveaction) ? $saveaction : ''; ?>';
var editimgid = '';
<?php if (isset($saveactionid)){ ?>
	editimgid = '<?php echo $saveactionid ?>';
	$('#curr_imgid').val(editimgid);
<?php } ?>
</script>