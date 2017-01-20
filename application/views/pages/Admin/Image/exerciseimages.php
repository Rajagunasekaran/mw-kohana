<!--- Top nav && left nav--->
<?php echo $topnav.$leftnav; echo $imgeditor2; $showflag = true;
	$userid = Auth::instance()->get_user()->pk();
	Session::instance()->set('imgchecked', 0);
?>
<!--- Top nav && left nav --->
<!-- Content Wrapper. Contains page content -->
<div id="page-wrapper">
	<div class="container-fluid">
		<!-- Page Heading -->
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">
					 <?php echo __('Image Upload Settings'); ?>
				</h1>
				<ol class="breadcrumb">
					 <li>
						  <i class="fa fa-dashboard"></i>  <a href="<?php echo URL::base().'admin/index'; ?>"><?php echo __('Dashboard'); ?></a>
					 </li>
					 <li class="active">
						  <i class="fa fa-edit"></i> <?php echo __('Image Upload Settings'); ?>
					 </li>
				</ol>
			</div>
		</div>
		<div class="row">
			<h2 class="col-lg-6">
				<?php if(isset($folderitem['rescnt']) && $folderitem['rescnt']>0) { echo $folderitem['rescnt']; }?> 
				 Image Record(s)</h2>
		</div>
		<?php $session = Session::instance();
		if ($session->get('success')): ?>
			<div class="row">
				<div class="col-lg-12">
					<div class="alert alert-success">
						<i class="fa fa-check"></i><span><?php echo $session->get_once('success') ?></span>
					</div>
				</div>
			</div>
		<?php endif;
		if ($session->get('error')): ?>
			<div class="row">
				<div class="col-lg-12">
					<div class="alert alert-success">
					  <i class="fa fa-check"></i><span><?php echo $session->get_once('error') ?></span>
					</div>
				</div>
			</div>
		<?php endif; ?>
		<!-- /.row -->
		<div class="row">
			<div class="col-lg-12">
				<!--<div id="home" class="container">-->
				<div id="home" class="">
					<form method="post" id="imglibrary_form" enctype="multipart/form-data">
						<!-- img uploader start -->
						<div class="uploader-section hide" id="ImageUploader">    
							<div class="row" id="uploader-head">
								<div class="page-head">
									<div class="col-xs-3 aligncenter">
										<a href="<?php echo URL::base(TRUE).'admin/image/exerciseimages/'; ?>" id="uploaderBack" title="<?php echo __('Back'); ?>">
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
															<div id="progressBar" class="progress-bar progress-bar-success"  role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
														</div>
													</div>
													<div class="col-xs-3 header-cell aligncenter imgcheck-opt">
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
											<a href="<?php echo URL::base(TRUE).'admin/image/exerciseimages/'; echo (!empty($foldername) && count($foldername) > 0 && !empty($parentFolderId) && !empty($subFolderId)) ? $parentFolderId : ''; ?>" title="<?php echo __('Back'); ?>">
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
											<div class="filter-search<?php echo (!empty($foldername) && $foldername[0]['folder_id']!=1 ? '' : ' hide'); ?>" title="<?php echo __('Search Images'); ?>"> 
												<a href="#" data-toggle="modal" data-target="#popupfilteract-modal" data-ajax="false" data-role="none"><i class="fa fa-search iconsize2"></i></a>
											</div>
										</div>
									</div>
								</div>
								<hr>
								<div id="imgupload-link" class="<?php if(!empty($folderitem['itemlist']) || (isset($foldername) && ($parentFolderId==2 || $parentFolderId==6 || $parentFolderId==0))){ echo "hide"; }?>">
									<div class="row">
										<a href="#ImageUploader" id="triggere-uploader" class="upload-image <?php if(!empty($partentfolder) && count($partentfolder) > 0){echo 'fmain';}if(!empty($subfolders) && count($subfolders) > 0){echo 'smain';}?>" title="<?php echo __('Upload Images'); ?>" onclick="triggerSelectFolderModal();">
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
											<div class="row">
												<a href="<?php if(empty($value['countval']) || $value['countval'] == 0){ echo 'javascript:void(0);'; }else{ echo URL::base(TRUE).'admin/image/exerciseimages/'.$value['folder_id']; } ?>" id="<?php echo $value['folder_id']; ?>" class="folderclk-btn">
													<div class="col-xs-12">
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
											<div class="row">
												<a href="<?php if($parentFolderId!=1 && $exerciseimgcnt==0){ echo 'javascript:void(0);'; }else{ echo URL::base(TRUE).'admin/image/exerciseimages/'.$parentFolderId.'/'.$subvalue['folder_id']; } ?>" id="<?php echo $subvalue['folder_id']; ?>" class="folderclk-btn">
													<div class="col-xs-12">
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
									if($folderitem['itemlist'][0]['parentfolder_id']==1){ ?>
										<div class="listing-header header-toggle1 checkopt-header">
											<div class="row">
												<div class="col-xs-12">
													<a href="#ImageUploader" id="" class="upload-image" title="<?php echo __('Upload Images'); ?>" onclick="triggerSelectFolderModal();">
														<div class="col-xs-9 header-cell aligncenter">
															<i class="fa fa-plus iconsize2 activedatacol"></i>
														</div>
													</a>
													<div class="col-xs-3 header-cell aligncenter imgcheck-opt" title="<?php echo __('Select Images'); ?>">
														<i class="fa fa-list-ul iconsize2 activedatacol"></i>
													</div>
												</div>
											</div>
											<hr>
										</div>
										<div class="listing-header header-toggle2 check-header" style="display: none;">
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
									<?php } ?>
									<input type="hidden" id="filter_fid" name="filter_fid" value="<?php echo $folderitem['itemlist'][0]['parentfolder_id']; ?>">
									<input type="hidden" id="filter_subfid" name="filter_subfid" value="<?php echo $folderitem['itemlist'][0]['subfolder_id']; ?>">
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
														<div class="col-sm-3 col-xs-3 thumb-img" <?php echo $attributes; ?> onclick="triggerImgPrevModal(this,<?php echo $values['parentfolder_id'];?>,'<?php echo (($values['user_id'] == $userid) ? '1' : ''); ?>');" 
														<?php if(!empty($values['img_url']) && file_exists($values['img_url'])){
																echo 'style="background-image: url('.URL::base_lang(TRUE).$values['img_url'].');">';
															} else { ?>
																><i class="fa fa-file-image-o datacol" style="font-size:50px;"></i>
															<?php } ?>
														</div>
														<div class="col-sm-7 col-xs-7 img-itemname" <?php echo $attributes; ?> onclick="triggerImgOptionModal(this, <?php echo $values['parentfolder_id']; ?>, '<?php echo (($values['user_id'] == $userid) ? '1' : ''); ?>');">
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
									<div class="nothingfound aligncenter" style="display: none;">
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
											<div class="col-xs-6 aligncenter centerheight" id="page-title"><?php echo __('Image Data'); ?></div>
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
												<input type="text" tabindex="1" class="form-control" id="imgdata-title" value="" name="imgdata-title" placeholder="Image Title" />
											</div>
										</div>
									</div>
									<?php if(isset($exerciseStatus) && count($exerciseStatus)>0) { ?>
										<div class="form-group">
											<div class="row">
												<div class="col-sm-4"><label class="control-label"><?php echo __('Status'); ?>:</label></div>
												<div class="col-sm-8">
													<div class="dropdown selectdropdownTwo">
														<select tabindex="2" class="selectAction" id="imgdata-status" name="imgdata-status">
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
											<div class="col-xs-8" data-enhance="false" data-role="none" data-ajax="false">
												<input type="text" tabindex="3" class="form-control imgdata-tag" name="imgdata-tag" value="" placeholder="Tags" data-role="tagsinput" data-ajax="false"/>
											</div>
										</div>
									</div>
								</div>
							</div><!-- img data end -->
						</div><!-- img folder end -->

						<!-- preview image modal -->
						<div id="popupimgprev-modal" class="modal fade" role="dialog">
							<input name="hidenFoldId" id="hidenFoldId" type="hidden" value="0">
							<input name="hidenAllowFlag" id="hidenAllowFlag" type="hidden" value="">
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
							<input name="hiddenFoldId" id="hiddenFoldId" type="hidden" value="0">
							<input name="hiddenAllowFlag" id="hiddenAllowFlag" type="hidden" value="">
							<div class="vertical-alignment-helper">
								<div class="modal-dialog">
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
											<?php if($parentFolderId==2){ ?>
											<div class="opt-row-detail hidedefault">
												<button class="btn btn-default" id="imghide-btn" onclick="defaulthideImage(<?php echo $parentFolderId; ?>);" value="hide" type="button" style="width:100%" data-ajax="false" data-role="none">
													<div class="col-xs-12 pointer">
														<div class="col-xs-3"><i class="fa fa-eye-slash iconsize"></i></div>
														<div class="col-xs-9"><?php echo __('Hide'); ?></div>
													</div>
												</button>
											</div>
											<?php } ?>
											<div class="opt-row-detail">
												<button class="btn btn-default" id="preview-btn" onclick="triggerImgPrevModal(this);" value="preview" type="button" style="width:100%" data-ajax="false" data-role="none">
													<div class="col-xs-12 pointer">
														<div class="col-xs-3"><i class="fa fa-eye iconsize"></i></div>
														<div class="col-xs-9"><?php echo __('Preview'); ?></div>
													</div>
												</button>
											</div>
											<div class="opt-row-detail hideOptions">
												<button class="btn btn-default" id="editimg-btn" onclick="triggerImgEditorModal();" value="edit-image" type="button" style="width:100%" data-ajax="false" data-role="none">
													<div class="col-xs-12 pointer">
														<div class="col-xs-3"><i class="fa fa-picture-o iconsize"></i></div>
														<div class="col-xs-9"><?php echo __('Edit Image'); ?></div>
													</div>
												</button>
											</div>
											<div class="opt-row-detail hideOptions">
												<button class="btn btn-default" id="imgdata-btn" onclick="" value="edit-data" type="button" style="width:100%" data-ajax="false" data-role="none">
													<div class="col-xs-12 pointer">
														<div class="col-xs-3"><i class="fa fa-pencil-square-o iconsize"></i></div>
														<div class="col-xs-9"><?php echo __('Edit Data'); ?></div>
													</div>
												</button>
											</div>
											<?php //if($subFolderId!=4){ ?>
											<div class="opt-row-detail allowopt">
												<button class="btn btn-default" id="imgtag-btn" onclick="triggerImgTagModal();" value="tag" type="button" style="width:100%" data-ajax="false" data-role="none">
													<div class="col-xs-12 pointer">
														<div class="col-xs-3"><i class="fa fa-tag iconsize"></i></div>
														<div class="col-xs-9"><?php echo __('Tag'); ?></div>
													</div>
												</button>
											</div>
											<div class="opt-row-detail allowopt">
												<button class="btn btn-default" id="imgduplicate-btn" onclick="triggerImgDuplicateToModal();" value="duplicate" type="button" style="width:100%" data-ajax="false" data-role="none">
													<div class="col-xs-12 pointer">
														<div class="col-xs-3"><i class="fa fa-files-o iconsize"></i></div>
														<div class="col-xs-9"><?php echo __('Duplicate'); ?></div>
													</div>
												</button>
											</div>
											<?php //if((Helper_Common::is_admin() || Helper_Common::is_manager() || Helper_Common::is_trainer()) && !empty($parentFolderId) && $parentFolderId==1){ ?>
											<div class="opt-row-detail allowcopy-s">
												<button class="btn btn-default" id="imgcopysample-btn" name="copyimg" value="2" type="submit" onclick="return triggerImgCopy('sample');" style="width:100%" data-ajax="false" data-role="none">
													<div class="col-xs-12 pointer">
														<div class="col-xs-3"><i class="fa fa-files-o iconsize"></i></div>
														<div class="col-xs-9"><?php echo __('Copy to Sample'); ?></div>
													</div>
												</button>
											</div>
											<?php //} if(Helper_Common::is_admin() && !empty($parentFolderId) && ($parentFolderId==1 || $parentFolderId==2)){ ?>
											<div class="opt-row-detail allowcopy-d">
												<button class="btn btn-default" id="imgcopydefault-btn" name="copyimg" value="6" type="submit" onclick="return triggerImgCopy('default');" style="width:100%" data-ajax="false" data-role="none">
													<div class="col-xs-12 pointer">
														<div class="col-xs-3"><i class="fa fa-files-o iconsize"></i></div>
														<div class="col-xs-9"><?php echo __('Copy to Default'); ?></div>
													</div>
												</button>
											</div>
											<?php //} 
											//} // end subfolderId!=4 ?>
											<div class="opt-row-detail hideOptions">
												<button class="btn btn-default" id="imgraplace-btn" onclick="triggerImgReplace();" value="replace" type="button" style="width:100%" data-ajax="false" data-role="none">
													<div class="col-xs-12 pointer">
														<div class="col-xs-3"><i class="fa fa-mail-reply iconsize"></i></div>
														<div class="col-xs-9"><?php echo __('Replace'); ?></div>
													</div>
												</button>
											</div>
											<div class="opt-row-detail hideOptions">
												<button class="btn btn-default" id="imgdelete-btn" name="delete_btn" value="delete" type="submit" onclick="return triggerImgDelete();" style="width:100%" data-ajax="false" data-role="none">
													<div class="col-xs-12 pointer">
														<div class="col-xs-3"><i class="fa fa-times iconsize"></i></div>
														<div class="col-xs-9"><?php echo __('Delete'); ?></div>
													</div>
												</button>
											</div>
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
								<div class="modal-dialog">
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
								<div class="modal-dialog">
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
													<div class="col-xs-12" data-enhance="false" data-role="none" data-ajax="false">
														<input type="text" class="form-control imgtag-input" name="imgtag-input" value="" placeholder="Tags" data-role="tagsinput" data-ajax="false"/>
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
								<div class="modal-dialog">
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
															 <div class="dropdown selectdropdownTwo">
																<select tabindex="2" class="selectAction" id="imgchecked-status" name="imgchecked-status" data-ajax="false" data-role="none">
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
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<div class="mobpadding">
												<div class="border">
													<div class="col-xs-2">
														<a href="javascript:void(0);" title="<?php echo __('Back'); ?>" onclick="$('#popupfinalact-modal').modal('hidecustom');" class="triangle" data-ajax="false" data-role="none">
															<i class="fa fa-chevron-left"></i>
														</a>
													</div>
													<div class="col-xs-8 optionpoptitle"><?php echo __('Options for Editing'); ?></div>
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
								<div class="modal-dialog">
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

						<!-- duplicate images to other folder-->
						<div id="popupimgduplicateopt-modal" class="modal fade" role="dialog">
							<div class="vertical-alignment-helper">
								<div class="modal-dialog">
									<div class="modal-content aligncenter">
										<div class="modal-header">
											<div class="mobpadding">
												<div class="border">
													<div class="col-xs-2">
														<a href="javascript:void(0);" title="<?php echo __('Back'); ?>" class="triangle" onclick="$('#popupimgduplicateopt-modal').modal('hidecustom');" data-ajax="false" data-role="none">
															<i class="fa fa-caret-left iconsize"></i>
														</a>
													</div>
													<div class="col-xs-8 optionpoptitle"><?php echo __('Duplicate this Image'); ?></div>
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
												<button class="btn btn-default" id="imgcopytomyimage-btn" name="duplicateimg" value="1" type="submit" style="width:100%" data-ajax="false" data-role="none">
													<div class="col-xs-12 pointer">
														<div class="col-xs-3"><i class="fa fa-files-o iconsize"></i></div>
														<div class="col-xs-9"><?php echo __('Duplicate to My Images'); ?></div>
													</div>
												</button>
											</div>
											<?php if(Helper_Common::is_admin() || Helper_Common::is_manager() || Helper_Common::is_trainer()){ ?>
											<div class="opt-row-detail">
												<button class="btn btn-default" id="imgcopytosample-btn" name="duplicateimg" value="2" type="submit" style="width:100%" data-ajax="false" data-role="none">
													<div class="col-xs-12 pointer">
														<div class="col-xs-3"><i class="fa fa-files-o iconsize"></i></div>
														<div class="col-xs-9"><?php echo __('Duplicate to Sample Images'); ?></div>
													</div>
												</button>
											</div>
											<?php }if(Helper_Common::is_admin()){ ?>
											<div class="opt-row-detail">
												<button class="btn btn-default" id="imgcopytodefault-btn" name="duplicateimg" value="6" type="submit" style="width:100%" data-ajax="false" data-role="none">
													<div class="col-xs-12 pointer">
														<div class="col-xs-3"><i class="fa fa-files-o iconsize"></i></div>
														<div class="col-xs-9"><?php echo __('Duplicate to Default Images'); ?></div>
													</div>
												</button>
											</div>
											<?php } ?>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-default" onclick="$('#popupimgduplicateopt-modal').modal('hidecustom');" data-ajax="false" data-role="none"><?php echo __('Close'); ?></button>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="imgDataUrl">
							<input type="hidden" id="croppedData" name="croppedData" value=""></button>
							<input type="hidden" id="replaceflag" value=""></button>
						</div>
					</form>
				</div>

				<!-- image filtering modal -->
				<div id="popupfilteract-modal" class="modal fade" role="dialog">
					<div class="vertical-alignment-helper">
						<div class="modal-dialog">
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
													<input type="text" class="form-control" id="fltrtitle-input" value="" placeholder="Image Title" data-ajax="false" data-role="none"/>
												</div>
											</div>
										</div>
										<div class="opt-row-detail">
											<div class="row">
												<div class="col-xs-12"><label class="control-label"><?php echo __('Tags'); ?>:</label></div>
												<div class="col-xs-12" data-enhance="false" data-role="none" data-ajax="false">
													<input type="text" class="form-control fltrtag-input" value="" placeholder="Tags" data-role="tagsinput" data-ajax="false"/>
												</div>
											</div>
										</div>
										<div class="opt-row-detail">
											<div class="row">
												<div class="col-sm-12"><label class="control-label"><?php echo __('Sort By'); ?>:</label></div>
												<div class="col-sm-12">
													<div class="dropdown selectdropdownTwo">
														<select class="selectAction" id="fltrsort-select" style="width:50% !important;">
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

			</div>
		</div>
	</div>
	<!-- /.container-fluid -->
	</div>
<!-- /#page-wrapper -->
</div>
<!-- /#wrapper -->
<input type="hidden" name="saveaction" value="<?php if(isset($saveaction)){echo $saveaction;}?>" id="saveaction" />
<input type="hidden" name="saveactionid" value="<?php if(isset($saveactionid)){echo $saveactionid;}?>" id="saveactionid" />
<!-- jQuery 
<!--<script type="text/javascript" src="http://versatile-25:82/assets/js/bootstrap-tagsinput.min.js"></script>-->
<script>
var progresshead = $('#progress-head'),
uploadhead = $('#uploader-head'),
uploaddiv = $('.upload-div'),
headerprogress = $('.header-progress');
function initSimpleUploadMethod() {
	console.log('init SimpleUpload');
	var errFilename = '';
	var btnupload = document.getElementById('image_upload'),
		progressBar = document.getElementById('progressBar'),
		progressbarOuter = document.getElementById('progressbarOuter'),
		divBoxcover = document.getElementById('uploadListing'),
		msgBoxcover = document.getElementById('uploadError');
	var imguploader = new ss.SimpleUpload({
		button: btnupload,
		url: "<?php echo URL::base(TRUE).'admin/image/uploadImg?action=upload'; ?>",
		name: 'uploadfile',
		dropzone: 'dragndropimage',
		multipart: true,
		hoverClass: 'hover',
		focusClass: 'focus',
		responseType: 'json',
		maxSize: 2560,
		allowedExtensions: ["jpg", "jpeg", "png"],
		onSizeError: function(filename, fileSize) {
			errFilename += '<b>' + filename + '</b>, ';
			msgBoxcover.innerHTML = errFilename.slice(0, -2) + ' file(s) size is too large. (max file size 2560kb)';
			return false;
		},
		onExtError: function(filename, extension) {
			msgBoxcover.innerHTML = 'Extension "<b>' + extension + '</b>" not allowed, please choose jpg, jpeg and png file.';
			return false;
		},
		onChange: function() {
			errFilename = '';
		},
		startXHR: function() {
			uploadhead.addClass('hide');
			uploaddiv.addClass('hide');
			progresshead.removeClass('hide');
			headerprogress.removeClass('hide');
			progressbarOuter.style.display = 'block'; // make progress bar visible
			this.setProgressBar(progressBar);
		},
		onSubmit: function() {
			msgBoxcover.innerHTML = ''; // empty the message box
			var self = this;
			self.setData({
				upfolder: $('#uploadfolderId').val(),
				currfolder: $('#currentFolderId').val(),
				parentfolder: $('#parentFolderId').val(),
				subfolder: $('#subFolderId').val(),
				replaceflag: $('#replaceflag').val(),
				imageid: $('#curr_imgid').val()
			});
		},
		onComplete: function(filename, response) {
			progressbarOuter.style.display = 'none'; // hide progress bar when upload is completed
			if (!response) {
				msgBoxcover.innerHTML = 'Unable to upload file';
				return;
			}
			if (response.success === false) {
				uploadhead.removeClass('hide');
				uploaddiv.removeClass('hide');
				progresshead.addClass('hide');
				headerprogress.addClass('hide');
				$('#uploadListing').addClass('hide').empty();
				msgBoxcover.innerHTML = response.divImage;
				return;
			}
			if (response.success === true) {
				divBoxcover.innerHTML = $('#uploadListing').html() + response.divImage;
				$('#uploadListing').removeClass('hide');
			} else {
				if (response.msg) {
					msgBoxcover.innerHTML = escapeTags(response.msg);
				} else {
					uploadhead.removeClass('hide');
					uploaddiv.removeClass('hide');
					progresshead.addClass('hide');
					headerprogress.addClass('hide');
					$('#uploadListing').addClass('hide').empty();
					msgBoxcover.innerHTML = 'An error occurred and the upload failed.';
				}
			}
		},
		onError: function() {
			progressbarOuter.style.display = 'none';
			msgBoxcover.innerHTML = 'Unable to upload file';
			uploadhead.removeClass('hide');
			uploaddiv.removeClass('hide');
			progresshead.addClass('hide');
			headerprogress.addClass('hide');
			$('#uploadListing').addClass('hide').empty();
		}
	});
}
$(document).ready(function(){
	document.getElementById('image_upload').addEventListener('click',function(){
		document.getElementById('files').click();
	});
});
$(document).on('click','#progressBack',function(){
	uploadhead.removeClass('hide');
	uploaddiv.removeClass('hide');
	progresshead.addClass('hide');
	headerprogress.addClass('hide');
	$('#uploadListing').addClass('hide');
});
/*uploader section scripts end*/

/*folder section scripts starts*/
$('div.bannermsg').fadeOut(12000);
$('#popupfilteract-modal').on('shown.bs.modal', function(){
	$('#fltrtitle-input').focus();
});
$('#popupimgstatus-modal').on('shown.bs.modal', function(){
	$('#imgchecked-status').focus();
});
var curimgelem;
var folderlib = $('.img-lib-folder');
var imgList = $('#img_listing');
var uploadimgList = $('#uploadListing');

function ajaxInsertActivityfeed (method, type) {
	var imgid = $('#curr_imgid').val();
	if(imgid){
		$.post(siteUrl + 'ajax/ajaxInsertActivityfeed', {
			'actid': imgid,
			'method': method,
			'type': type
		}, function() {});
	}
}
function triggerImgPrevModal(elem,folderId,allowFlag) {
	var imgurl = $(elem).attr('data-itemurl');
	var imgname = $(elem).attr('data-itemname');
	if (imgname != undefined && imgname != '') {
		$('#preview-imgname').text(imgname);
	} else {
		$('#preview-imgname').text('');
	}
	if (imgurl != undefined && imgurl != '') {
		$('#preview_libimg').html('<img alt="'+__("Preview Image")+'" class="Preview_image" id="previewlibimg" src="' + siteUrl_frontend + imgurl + '"/>');
		$('#preview-btn').attr('data-itemurl', $(elem).attr('data-itemurl'));
	} else {
		$('#preview_libimg').html('<i class="fa fa-file-image-o prevfeat"></i>');
		$('#preview-btn').attr('data-itemurl', '');
	}
	if(folderId == undefined || folderId == '')
		folderId = $('input#hiddenFoldId').val();
	if(allowFlag == undefined || allowFlag == '')
		allowFlag = $('input#hiddenAllowFlag').val();
	$('input#hidenFoldId').val(folderId);
	$('input#hidenAllowFlag').val(allowFlag);
	$('#popupimgact-modal').modal('hide');
	$('#popupimgprev-modal').modal();
	curimgelem = elem;
	setTimeout(function(){
		ajaxInsertActivityfeed('previewed', 'image');
	}, 350);
}
function triggerImgOptionModal(elem, folderId, allowFlag) {
	var imgurl = $(elem).attr('data-itemurl');
	var imgname = $(elem).attr('data-itemname');
	if (imgname != undefined && imgname != '') {
		$('#preview-btn').attr('data-itemname', imgname);
		$('span.imgTitle').text(imgname);
	} else {
		$('#preview-btn').attr('data-itemname', '');
		$('span.imgTitle').text('');
	}
	if (imgurl != undefined && imgurl != '') {
		$('#preview-btn').attr('data-itemurl', imgurl);
	} else {
		$('#preview-btn').attr('data-itemurl', '');
	}
	<?php if($parentFolderId == '2'){ ?>
	if(folderId == '6'){
		$('.hidedefault').removeClass('hide');
	}else{
		$('.hidedefault').addClass('hide');
	}
	<?php } ?>
	if(allowFlag){
		$('.hideOptions').removeClass('hide');
	}else{
		$('.hideOptions').addClass('hide');
	}
	$('#popupimgact-modal').modal();
	$('input#hiddenFoldId').val(folderId);
	$('input#hiddenAllowFlag').val(allowFlag);
	$('#replaceflag').val('');
}
$(document).on('click', '#prevmdloption', function(){
	var folderId = $('input#hidenFoldId').val();
	var allowFlag = $('input#hidenAllowFlag').val();
	triggerImgOptionModal(curimgelem, folderId, allowFlag);
});

$(document).on('click','#btn-filterreset',function(){
	$('#filteract-form')[0].reset();
	$('input.fltrtag-input').tagsinput('removeAll');
	$('input.fltrtag-input').tagsinput('refresh');
});
function triggerImageData(opt){
	$('#popupimgact-modal').modal('hide');
	$('#popupimgprev-modal').modal('hide');
	$('.img-lib-folder').addClass('hide');
	$('.exercise-lib-imgdata').removeClass('hide');
	$('.uploader-section').addClass('hide');
	$('.folder-section').removeClass('hide');
	$('#imgdataBack').attr('data-databack',opt);
	var cururl = location.pathname;
	$('#imgdataBack').attr('data-backurl', cururl);
	setTimeout(function(){
		ajaxInsertActivityfeed('opened', 'image data');
	}, 350);
}
function resetImgDataForm() {
	$('#image_data_form').children().find('input,select,textarea').each(function() {
		$(this).val('');
	});
	$('.imgDataUrl').find('input').each(function() {
		$(this).val('');
	});
	$('input.imgdata-tag, input.imgtag-input').tagsinput('removeAll');
	$('input.imgdata-tag, input.imgtag-input').tagsinput('refresh');
}
function triggerImgDataRevert() {
	resetImgDataForm();
	$('#popupfinalact-modal').modal('hidecustom');
	$('.crop-reset').trigger('click');
	var curimgid = $('#curr_imgid').val();
	if (curimgid != '') {
		var objimgrow = $('#' + curimgid + '.imgRecord');
		var objdata = objimgrow.find('.img-itemname');
		$('#imgdata-title').val(objdata.attr('data-itemname'));
		$('select#imgdata-status').select2('val', '1');
		var tags = $('#img_tags' + objdata.attr('data-itemid')).val();
		$('input.imgdata-tag').tagsinput('add', tags);
		if($('#image_data_form').is(':visible')){
			var type = 'image data';
		}else if($('#popupimgeditor-model').is(':visible')){
			var type = 'image';
		}
		setTimeout(function(){
			ajaxInsertActivityfeed('exited', type);
		}, 350);
	}
}
$(document).on('click', '.imgcheck-opt', function() {
	if (imgList.is(':visible') || uploadimgList.is(':visible')) {
		$(".checkbox-checker").toggle('slow');
		$(".header-toggle1, .header-toggle2").toggle();
		$(".checkboxcolor input:checkbox").prop('checked', false);
		$('.check-image>i').removeClass('checked');
		$('.checked-opt').removeClass('active');
	} else {
		return false;
	}
});
function checkAllItems(selector) {
	$('.tag-imgname').text('');
	if (imgList.is(':visible')) {
		if ($(selector).hasClass('checked')) {
			$("#img_listing input:checkbox").prop('checked', false);
			$(selector).removeClass('checked');
		} else {
			$("#img_listing input:checkbox").prop('checked', true);
			$(selector).addClass('checked');
		}
		if ($('#img_listing .checkboxcolor label input[type="checkbox"]:checked').length > 0) {
			$('.check-header .checked-opt').addClass('active');
		} else {
			$('.check-header .checked-opt').removeClass('active');
		}
	} else if (uploadimgList.is(':visible')) {
		if ($(selector).hasClass('checked')) {
			$("#uploadListing input:checkbox").prop('checked', false);
			$(selector).removeClass('checked');
		} else {
			$("#uploadListing input:checkbox").prop('checked', true);
			$(selector).addClass('checked');
		}
		if ($('#uploadListing .checkboxcolor label input[type="checkbox"]:checked').length > 0) {
			$('.header-progress-opt .checked-opt').addClass('active');
		} else {
			$('.header-progress-opt .checked-opt').removeClass('active');
		}
	}
	return false;
}
$(document).on('change', '.checkboxcolor label input[type="checkbox"]', function() {
	if ($('.checkboxcolor label input[type="checkbox"]:checked').length > 0) {
		$('.checked-opt').addClass('active');
	} else {
		$('.checked-opt').removeClass('active');
	}
	$('.tag-imgname').text('');
});
$(document).on('click', '.checked-opt', function() {
	if ($(this).hasClass('active')) {
		$('#popupchekdact-modal').modal();
		var checkedimg = new Array();
		$('form input[name="check_act[]"]').each(function() {
			if (this.checked) { checkedimg.push($(this).val()); } else {}
		});
		getCommonImgTags(checkedimg);
	} else {
		if ($('.checkboxcolor label input[type="checkbox"]:checked').length > 0) {} else {
			alert('Please select the image(s) before do this action!!!');
		}
		return false;
	}
});
function getCommonImgTags(imageids) {
	$('input.imgtag-input').tagsinput('removeAll');
	$('input.imgtag-input').tagsinput('refresh');
	$.ajax({
		url: siteUrl + 'ajax/ajaxGetImageCommonTags',
		type: 'GET',
		dataType: 'json',
		data: {
			imgids: imageids
		},
		success: function(data) {
			if (data.success) {
				$('input.imgtag-input').tagsinput('add', data.img_tags);
			}
		},
		error: function (data){
			console.log(JSON.stringify(data.img_tags));
		}
	});
}
function triggerImageDataBack() {
	var optdiv = $('#imgdataBack').attr('data-databack');
	if(optdiv=='uploader'){
		$('.folder-section').addClass('hide');
		$('.uploader-section').removeClass('hide');
	}else{
		$('.folder-section').removeClass('hide');
		$('.img-lib-folder').removeClass('hide');
		$('.exercise-lib-imgdata').addClass('hide');
		$('.uploader-section').addClass('hide');
	}
	history.pushState('', 'My Workouts - Images', $('#imgdataBack').attr('data-backurl'));
}

var imgcount = 0;
var itemcnt = 0;
var limitcnt = 0;
var fltr_limitcnt = 0;
/*initial load on page*/
function fetchMoreRecords() {
	limitcnt = limitcnt + 10;
	$.ajax({
		url: siteUrl + 'ajax/getAjaxShowMoreImages',
		type: 'GET',
		data: {
			fid: $('#parentFolderId').val(),
			subfid: $('#subFolderId').val(),
			slimit: limitcnt,
			elimit: 10
		},
		encode: true,
		cache: false,
		success: function(data) {
			var imgresultss = [JSON.parse(data)];
			if (imgresultss) {
				imgList.find('.filtering').removeClass('filtering');
				if (renderToImg(filterImgRecords(imgresultss))) {
					if (imgcount > 10 && itemcnt == 10) {
						loadAjaxSend = true;
					}
				}
			}
		},
		error: function(data) {
			console.log(JSON.stringify(data));
		}
	});
	return true;
}
$('#filteract-form').submit(function(e) {
	imgList.scrollTop(0);
	fltr_limitcnt = 0;
	e.preventDefault();
	e.stopImmediatePropagation();
	fetchFilteredRecords('init');
	$('#popupfilteract-modal').modal('hide');
});
function fetchFilteredRecords(opt){
	var searchText = $('#fltrtitle-input').val(); // Filter : Search Input Text    
	var searchTag = $("input.fltrtag-input").tagsinput('items'); // Filter : Search Tag
	var searchsort = $("select#fltrsort-select").val(); // Filter : Search sort
	if ($('#parentFolderId').val() == '') {
		var parentfolderid = 0
	} else {
		var parentfolderid = $('#parentFolderId').val();
	} // Filter : Parent Folder Id
	if ($('#subFolderId').val() == '') {
		var subfolderid = 0
	} else {
		var subfolderid = $('#subFolderId').val();
	} // Filter : Sub Folder Id
	var searchTags = '';
	searchTag.toString();
	$.each(searchTag, function(i, val) {
		if (val != '') {
			searchTags += "'" + val + "'";
		}
		if (i != searchTag.length - 1) {
			searchTags += ', ';
		}
	});
	/*fetching imglist*/
	var dataToSend = {
		search_title	:searchText,
		search_tag		:searchTags,
		search_sort		:searchsort,
		fid				:parentfolderid,
		subfid			:subfolderid,
		slimit			:fltr_limitcnt,
		elimit			:10
	}; //console.log(dataToSend)
	$.ajax({
		type: 'POST',
		url: siteUrl + 'ajax/imgFilter',
		data: dataToSend,
		encode: true,
		cache: false,
		success: function(data) {
			var imgresponse = [JSON.parse(data)]; //console.log(imgresponse)
			if (imgresponse) {
				if (opt == 'init') {
					imgList.empty().addClass('hide');
					imgList.addClass('filtering');
				}
				if (renderToImg(filterImgRecords(imgresponse))) {
					if (imgcount > 10 && itemcnt == 10) {
						loadAjaxSend = true;
						imgList.addClass('filtering');
					}
				}
			}
		}
	});
	return true;
}
function filterImgRecords(records) {
	/* TEST for DATA in ARRAY */
	var imgdemo = records; 
	var imgs;
	var flag = 0;
	imgcount = imgdemo[0].items.rescnt;
	itemcnt = imgdemo[0].items.itemcnt;
	for(var j = 0; j < imgdemo.length; j++){
		flag = 1; // flag if RESPONSE contains data
		imgs = imgdemo[j].items.itemlist;
		break;
	}
	imgs = flag ? imgs : []; // if no content, imgdemo=0, otherwise imgdemo=[array]
	// console.log(imgs) // console.log(tags)
	return [imgs];
}
function renderToImg(data) {
	var filteredFiles = [];
	var filteredTags = [];
	if (Array.isArray(data[0])) {
		data[0].forEach(function(d) {
			filteredFiles.push(d);
		});
	}
	/* Empty the old result and make the new one */
	if (!filteredFiles.length) {
		if (imgList.find('li.imgRecord').length) {
			folderlib.find('.nothingfound').hide();
		} else {
			folderlib.find('.nothingfound').show();
		}
		return false;
	} else {
		folderlib.find('.nothingfound').hide();
		filteredFiles.forEach(function(f) {
			if ($('.header-toggle2.check-header').is(':visible')) {
				var display = 'block';
			} else {
				var display = 'none';
			}
			if (f.img_url != '' && f.img_url != null) {
				var testedimg = f.img_url;
				var dummyicom = '';
			} else {
				var testedimg = '';
				var dummyicom = '<i class="fa fa-file-image-o datacol" style="font-size:50px;"></i>';
			}
			var attribute = 'data-itemid="' + f.img_id + '" data-itemname="' + f.img_title + '" data-itemurl="' + testedimg + '" data-itemtype="folder"';
			var rec = '<li class="imgRecord" id="' + f.img_id + '">';
			rec += '<div class="imgRecordDataFrame col-xs-12 col-sm-12">';
			rec += '<a href="javascript:void(0);" class="col-xs-12 col-sm-12 imgFrame-full" data-ajax="false" data-role="none">';
			rec += '<div class="checkbox-checker col-xs-2 col-sm-2" style="display: ' + display + ';"><div class="checkboxcolor">';
			rec += '<label><input data-role="none" data-ajax="false" type="checkbox" class="checkhidden" name="check_act[]" value="' + f.img_id + '">';
			rec += '<span class="cr checkbox-circle"><i class="cr-icon fa fa-check"></i></span>';
			rec += '</label></div></div>';
			rec += '<div class="col-xs-3 col-sm-3 thumb-img" ' + attribute + ' onclick="triggerImgPrevModal(this);"' + (testedimg != '' ? ' style="background-image: url(' + siteUrl_frontend + testedimg + ');"' : '') + '>' + dummyicom + '</div>';
			var userid = '<?php echo $userid; ?>';
			var allow_flag = "''";
			if(f.user_id == userid){
				allow_flag = '1';
			}
			rec += '<div class="col-xs-7 col-sm-7 img-itemname" ' + attribute + ' onclick="triggerImgOptionModal(this, '+f.parentfolder_id+', '+allow_flag+');">';
			rec += '<div class="altimgtitle break-img-name">' + f.img_title + '</div><div class="item-info">'+f.default+'</div>';
			filteredTags = f.taglist;
			var i = 0;
			var tags = '';
			var taglist = '';
			filteredTags.forEach(function(t) {
				if (f.img_id == t.img_id) {
					if (i == 0) {
						tags += t.tag_title;
						taglist += t.tag_title;
					} else {
						tags += ', ' + t.tag_title;
						taglist += ',' + t.tag_title;
					}
					i++;
				}
			});
			if (tags != '') {
				rec += '<div class="img-tags"><span class="info-bold">' + __('Tags') + ': </span>' + tags + '</div>';
			}
			rec += '<input type="hidden" id="img_tags' + f.img_id + '" value="' + taglist + '"/>';
			rec += '</div>';
			rec += '</a>';
			rec += '</div>';
			rec += '</li>';
			var file = $(rec);
			file.appendTo(imgList);
		});
	}
	// Show the generated elements
	imgList.removeClass('hide');
	return true;
}
function triggerSelectFolderModal() {
	$('#replaceflag').val('');
	if ($('#parentfolder-div').hasClass('hide') == false) {
		$('#popupfldrslct-modal').modal();
		return false;
	} else if ($('#subfolder-div').hasClass('hide') == false) {
		$('#popupfldrslct-modal').modal();
		return false;
	} else {
		triggerUploader();
		var currLoc = window.location.pathname;
		$('#uploaderBack').attr('href', currLoc);
		if($('#parentFolderId').val() != '2')
			$('#uploadfolderId').val($('#currentFolderId').val());
	}
	enableImageOptions();
}
$(document).on('click', 'button.folder-select', function(){
	$('#uploadfolderId').val($(this).attr('id'));
	var currLoc = window.location.pathname;
	currLoc = currLoc.replace(/\/+$/,'');
	$('#uploaderBack').attr('href',currLoc);
	if($('#currentFolderId').val()!=''){
		history.pushState('', '', currLoc+'/'+$(this).attr('id'));
		$('#subFolderId').val($(this).attr('id'));
	}else{
		history.pushState('', '', currLoc+'/1/'+$(this).attr('id'));
		$('#parentFolderId').val(1);
		$('#subFolderId').val($(this).attr('id'));
		$('#currentFolderId').val(1)
	}
	triggerUploader();
	enableImageOptions();
	$('#popupfldrslct-modal').modal('hide');
});
function triggerImgReplace(){
	if(confirm('Are you sure, want to replace this image?')){
		triggerUploader();
		$('#replaceflag').val('replace');
		var currLoc = window.location.pathname;
		$('#uploaderBack').attr('href',currLoc);
		$('#uploadfolderId').val($('#currentFolderId').val());
		$('#popupimgact-modal').modal('hide');
		$('#popupimgprev-modal').modal('hide');
	}
	else{

	}
	enableImageOptions();
}
enableImageOptions();
function enableImageOptions() {
	var parentid = $('#parentFolderId').val();
	var subid = $('#subFolderId').val();
	if(subid == 4){
		$('#popupimgact-modal .allowopt').addClass('hide');
		$('#popupimgact-modal .allowcopy-s, #popupimgact-modal .allowcopy-d').addClass('hide');
	}else{
		$('#popupimgact-modal .allowopt').removeClass('hide');
		if('<?php echo (Helper_Common::is_admin() || Helper_Common::is_manager() || Helper_Common::is_trainer()) ?>' && parentid == 1){
			$('#popupimgact-modal .allowcopy-s').removeClass('hide');
		}else{
			$('#popupimgact-modal .allowcopy-s').addClass('hide');
		}
		if('<?php echo Helper_Common::is_admin() ?>' && (parentid == 1 || parentid == 2)){
			$('#popupimgact-modal .allowcopy-d').removeClass('hide');
		}else{
			$('#popupimgact-modal .allowcopy-d').addClass('hide');
		}
	}
}
function triggerUploader(){
	initSimpleUploadMethod();
	$('.folder-section').addClass('hide');
	$('.uploader-section').removeClass('hide');	
}
function triggerImgEditorModal(){
	$('#popupimgact-modal').modal('hide');
	$('#popupimgprev-modal').modal('hide');
	$('#popupimgeditor-model').modal();
	$('.trigger_crop').attr('data-prefix', '');
	setTimeout(function(){
		ajaxInsertActivityfeed('opened', 'image');
	}, 350);
}
function triggerImgTagModal(){
	$('#popupimgact-modal').modal('hide');
	$('#popupimgprev-modal').modal('hide');
	$('#popupimgtag-modal').modal();
}
function triggerImgTagModal(){
	$('#popupimgact-modal').modal('hide');
	$('#popupimgprev-modal').modal('hide');
	$('#popupimgtag-modal').modal();
}
function triggerCheckedTag(){
	$('#popupchekdact-modal').modal('hide');
	$('#popupimgtag-modal').modal();
}
function triggerCheckedStatus(){
	$('#popupchekdact-modal').modal('hide');
	$('#popupimgstatus-modal').modal();
}
function triggerImgDuplicateToModal(){
	var folderId = $('input#hiddenFoldId').val();
	$('#popupimgact-modal').modal('hide');
	$('#popupimgduplicateopt-modal').modal();
}
function triggerChangeStatus() {
	if ($('#imgchecked-status').val() != '' && $('#imgchecked-status').val() != 1) {
		if (confirm('Selected image(s) may currently be used by other exercise records. Are you sure you wish to continue?')) {
			return true;
		}
	} else if ($('#imgchecked-status').val() == 1) {
		return true;
	} else {
		alert('Please select any one option!!!');
	}
	return false;
}
function triggerShowMoreImage() {
	if (imgList.hasClass('filtering')) {
		fltr_limitcnt = fltr_limitcnt+10;
		fetchFilteredRecords('showmore');
	} else {
		fetchMoreRecords();
	}
	return false;
}
function triggerImgCopy(type) {
	if(confirm('Are you sure, want to copy this image to '+type+' images?')) {
		return true;
	}
	return false;
}
function triggerImgDelete() {
	if (confirm('Are you sure, want to delete this image?')) {
		return true;
	}
	return false;
}
var loadAjaxSend = true;
$(document).ready(function(){
	if(imgList.length){
		imgList.bind('scroll',function(ev){
			$('html, body').animate({
				scrollTop: imgList.position().top
			}, 'slow');
			var scrollTop = Math.round($(this).scrollTop());
			var scrollHeight = $(this)[0].scrollHeight;
			// console.log(scrollTop + $(this).innerHeight() + '===' + scrollHeight);
			if (loadAjaxSend) {
				if (scrollTop + $(this).innerHeight() == scrollHeight || scrollTop + $(this).innerHeight() == scrollHeight - 1 || scrollTop + $(this).innerHeight() == scrollHeight + 1) {
					loadAjaxSend = false;
					setTimeout(function() {
						ev.preventDefault();
						if (ev.handled !== true) {
							ev.handled = true;
							triggerShowMoreImage();
						}
					}, 200);
				}
			}
		});
		if (getBrowserZoomLevel() < 100) {
			AutoShowMore();
		}
	}
});
function AutoShowMore() {
	var x = 1,
		loopcnt = getAjaxSendCount();
	while (x <= loopcnt) {
		loadAjaxSend = false;
		setTimeout(function() {
			if (imgList.is(':visible') && imgList.find('li.imgRecord').length && getBrowserZoomLevel() < 100) {
				triggerShowMoreImage();
			}
		}, 200);
		x = x + 1;
	}
	return;
}
$(window).resize(function(ev) {
	if (imgList.length && imgList.is(':visible') && imgList.find('li.imgRecord').length && !imgList.hasVScrollBar()) {
		loadAjaxSend = false;
		setTimeout(function() {
			ev.preventDefault();
			if (ev.handled !== true) {
				ev.handled = true;
				triggerShowMoreImage();
			}
		}, 200);
	}
});
$(document).on('click', '.thumb-img, .img-itemname, .upload-imgrow', function() {
	resetImgDataForm();
	var check = $(this).attr('data-itemtype');
	if (check == "upload") {
		$('#prevmdloption').addClass('hide');
		$('#imgdata-btn').attr('onclick', "triggerImageData('uploader')");
		var hiddenFoldId = $('input#hiddenFoldId').val();
		if(hiddenFoldId == ''){
			$('.hideOptions').removeClass('hide');
		}
		$('#imgraplace-btn').parent().addClass('hide');
	} else {
		$('#prevmdloption').removeClass('hide');
		$('#imgdata-btn').attr('onclick', "triggerImageData('folder')");
		var hiddenFoldId = $('input#hiddenFoldId').val();
		if(hiddenFoldId == ''){
			$('.hideOptions').removeClass('hide');
			//$('#imgraplace-btn').parent().removeClass('hide');
		}
	}
	$('#btn-inserttag').attr('data-imgid', $(this).attr('data-itemid'));
	$('#curr_imgid').val($(this).attr('data-itemid'));
	$('#imgdata-title').val($(this).attr('data-itemname'));
	$('select#imgdata-status').select2('val', '1');
	var tags = $('#img_tags' + $(this).attr('data-itemid')).val();
	$('input.imgdata-tag').tagsinput('add', tags);
	$('input.imgtag-input').tagsinput('add', tags);
});
$('form#imglibrary_form').keypress( function( ev ) {
	var code = ev.keyCode || ev.which;
	if( code === 13 ) {
		ev.preventDefault();
		return false; 
	}
});
$(document).on('hidden.bs.modal', '#popupimgeditor-model.modal', function() {
	history.pushState('', 'My Workouts - Images', window.location.pathname);
	$('#croppedData').val('');
});
function defaulthideImage(FolderId) {
   var imageid = $('input#curr_imgid').val();
   var r = confirm("Are you Hide this sample Image Record?");
   if (r) {
      $.ajax({
         url: siteUrl + "image/defaulthide",
         type: 'POST',
         data: {
            imageid: imageid,
            f_method: "defaultImage",
			FolderId:FolderId
         },
         success: function(data) {
            if (data) {
               window.location.href = window.location.href;
			   
            }
         }
      });
   }
}
</script>	