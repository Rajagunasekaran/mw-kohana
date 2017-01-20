	<div id="imgeditormodel" class="modal fade imgeditormodel" role="dialog">
		<div class="modal-dialog modal-md model-editor">
		<!-- Modal content-->
			<div class="modal-content aligncenter">
				<div class="modal-header aligncenter">
					<div class="row">
						<div class="popup-title">
							<div class="col-xs-2">
								<a href="javascript:void(0);" title="Back" data-dismiss="modal" class="triangle">
									<i class="fa fa-caret-left iconsize"></i>
								</a>
							</div>
							<div class="col-xs-8">Image Editor</div>
							<div class="col-xs-2"> </div>
						</div>
					</div>
				</div>
				<div class="modal-body">
				<form action="#" method="post">
					<div class="aligncenter" style="margin: 0 auto 0 auto">
						<!-- Content -->
						<div class="image-editor">
							<div class="row">
								<div class="col-md-12 col-xs-12 docs-buttons">
									<!-- <h3 class="page-header">Toolbar:</h3> -->
									<div class="btn-group">
										<button type="button" class="btn btn-primary" data-method="zoom" data-option="0.1" title="Zoom In">
											<span class="docs-tooltip" data-toggle="tooltip" title="Zoom In">
												<span class="fa fa-search-plus"></span>
											</span>
										</button>
										<button type="button" class="btn btn-primary" data-method="zoom" data-option="-0.1" title="Zoom Out">
											<span class="docs-tooltip" data-toggle="tooltip" title="Zoom Out">
												<span class="fa fa-search-minus"></span>
											</span>
										</button>
									</div>

									<div class="btn-group">
										<button type="button" class="btn btn-primary" data-method="rotate" data-option="-45" title="Rotate Left">
											<span class="docs-tooltip" data-toggle="tooltip" title="Rotate Left">
												<span class="fa fa-rotate-left"></span>
											</span>
										</button>
										<button type="button" class="btn btn-primary" data-method="rotate" data-option="45" title="Rotate Right">
											<span class="docs-tooltip" data-toggle="tooltip" title="Rotate Right">
												<span class="fa fa-rotate-right"></span>
											</span>
										</button>
									</div>

									<div class="btn-group">
										<button type="button" class="btn btn-primary" data-method="scaleX" data-option="-1" title="Flip Horizontal">
											<span class="docs-tooltip" data-toggle="tooltip" title="Flip Horizontal">
												<span class="fa fa-arrows-h"></span>
											</span>
										</button>
										<button type="button" class="btn btn-primary" data-method="scaleY" data-option="-1" title="Flip Vertical">
											<span class="docs-tooltip" data-toggle="tooltip" title="Flip Vertical">
												<span class="fa fa-arrows-v"></span>
											</span>
										</button>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-12 col-xs-12">
									<!-- <h3 class="page-header">Demo:</h3> -->
									<div class="img-container">
										<img id="image" src="" alt="Import Picture">
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-12 col-xs-12 docs-buttons">
									<!-- <h3 class="page-header">Toolbar:</h3> -->
									<div class="btn-group">
										<button type="button" class="btn btn-primary" data-method="move" data-option="-10" data-second-option="0" title="Move Left">
											<span class="docs-tooltip" data-toggle="tooltip" title="Move Left">
												<span class="fa fa-arrow-left"></span>
											</span>
										</button>
										<button type="button" class="btn btn-primary" data-method="move" data-option="10" data-second-option="0" title="Move Right">
											<span class="docs-tooltip" data-toggle="tooltip" title="Move Right">
												<span class="fa fa-arrow-right"></span>
											</span>
										</button>
										<button type="button" class="btn btn-primary" data-method="move" data-option="0" data-second-option="-10" title="Move Up">
											<span class="docs-tooltip" data-toggle="tooltip" title="Move Up">
												<span class="fa fa-arrow-up"></span>
											</span>
										</button>
										<button type="button" class="btn btn-primary" data-method="move" data-option="0" data-second-option="10" title="Move Down">
											<span class="docs-tooltip" data-toggle="tooltip" title="Move Down">
												<span class="fa fa-arrow-down"></span>
											</span>
										</button>
									</div>

									<div class="btn-group">
										<button type="button" class="btn btn-primary crop-btn" data-method="getCroppedCanvas" title="Crop">
											<span class="docs-tooltip" data-toggle="tooltip" title="Crop">
												<span class="fa fa-check"></span>
											</span>
										</button>
										<button type="button" class="btn btn-primary" data-method="reset" title="Reset">
											<span class="docs-tooltip" data-toggle="tooltip" title="Reset">
												<span class="fa fa-retweet"></span>
											</span>
										</button>
									</div>

									<div class="uploadimage-dragndrop" id="dragndropimage">
										<div class=""><span class="fa fa-upload fa-3x"></span></div>
										<div class="uploadimage-text">Drag image here to upload</div>
									</div>

									<div class="prefer-text">Or, if you prefer...</div>

									<div class="btn-group">
										<label class="btn btn-primary btn-upload" for="inputImage" title="Upload Image File">
											<input type="file" class="sr-only" id="inputImage" name="file" accept="image/*">
											<input type="hidden" id="inputImgName" name="inputImgName" value="">
											<input type="hidden" id="imgtagid" name="imgtagid" value="">
											<input type="hidden" id="hiddenid" name="hiddenid" value="">
											<input type="hidden" id="imgname" name="imgname" value="">
											<span class="docs-tooltip" data-toggle="tooltip" title="Upload Image File">
												<span class="fa fa-upload"></span>&nbsp;&nbsp;Upload Image
											</span>
										</label>
									</div>
								</div><!-- /.docs-buttons -->
							</div>

							<div class="row">
								<div class="col-md-12 col-xs-12">
									<!-- <h3 class="page-header">Preview:</h3> -->
									<div class="docs-preview clearfix">
										<div class="img-preview preview-md"></div>
										<div class="img-preview preview-sm preivew-circle"></div>
									</div>
								</div>
							</div>

							<!-- Show the image list in modal -->
							<div class="row">
								<div class="col-md-12 col-xs-12">
									<div class="imglist-title">
										<h4 class="activedatacol">Image List</h4>
										<div class="imglist-search">
											<input type="search" id="imglist-srch" name="imglist-srch" class="form-control input-sm" placeholder="Find a file..." />
											<span class="imgsrchclear fa fa-remove" style="display: none;"></span>
										</div>
										<hr>
										<div class="img-list" id="imglist"></div>
									</div>
								</div>
							</div>

						</div>
					</div>
				</form>
				</div>
				<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Show the cropped image in modal -->
	<div class="modal fade docs-cropped" id="getCroppedCanvasModal" aria-hidden="true" aria-labelledby="getCroppedCanvasTitle" role="dialog" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header aligncenter">
					<div class="row">
						<div class="popup-title">
							<div class="col-xs-2">
								<a href="javascript:void(0);" title="Back" class="triangle">
									<i class="fa fa-caret-left iconsize"></i>
								</a>
							</div>
							<div class="col-xs-8">Cropped Image</div>
							<div class="col-xs-2"> </div>
						</div>
					</div>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<div class="row">
							<div class="col-xs-12">
								<div class="cropped-img"></div>
							</div>
						</div>
					</div>          
					<div class="form-group">
						<div class="row">
							<div class="col-xs-12">
								<small class="crop-msg"></small>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default triangle">Close</button>
					<button class="btn btn-primary" id="download"><i class="fa fa-refresh fa-spin hide"></i>Save &amp; Upload</button>
				</div>
			</div>
		</div>
	</div><!-- /.modal -->
	
	<script type="text/javascript">
	$('.crop-msg').text('').removeClass('crop-error crop-success').hide();
	$('#download').prop('disabled', false);
	$(function () {
		$(document).on('click','#download',function(){
			$('#download').prop('disabled', true);
			$('#download i').removeClass('hide');
			var imgdata='';
			imgdata = $(this).attr('data-cropped');
			var filename = $('#inputImgName').val();
			var imgname = filename.split('.');
			UploadPic(imgdata,imgname[0]);
		});
		function UploadPic(dataurl,filename) {
			$.ajax({
				type: 'POST',
				url: "<?php echo URL::site('exercise/upload'); ?>",
				data: {"imgData": dataurl,"imgName":filename},
				success: function (msg) {
					var msgval=JSON.parse(msg);
					if(msgval[0]==1){
						var imgtagid = $('#imgtagid').val();
						var hiddenid = $('#hiddenid').val();
						var imgname = $('#imgname').val();
						if( msgval[1] != '' && imgtagid != '' && hiddenid != '' && imgname != ''){
							$('#'+imgtagid).attr('src','/assets/images/dynamic/exercise/img/'+msgval[2]);
							$('#'+hiddenid).val('assets/images/dynamic/exercise/img/'+msgval[2]);
							if(msgval[3] !='' ){
								$('#'+imgname).val(msgval[3]);
							}
							$('#'+hiddenid).closest('.form-group').removeClass('has-error').addClass('has-success')
								.find('small').attr('data-fv-result','VALID').hide();
						}
						$('#download').attr('data-cropped','');
						if(hiddenid == 'xru_featImage'){
							$('#xrRecInsertForm').formValidation('revalidateField', 'xru_featImage');
						} else {
							$('#xrRecInsertForm').formValidation('revalidateField', 'seqImg[]');
						}
						$('.crop-msg').text('Image saved successfully!').addClass('crop-success').removeClass('crop-error').show();
					}else{
						$('.crop-msg').text('Your upload triggered the error!').addClass('crop-error').removeClass('crop-success').show();
					}
					$('#download i').addClass('hide');
				},
				error: function(err){
					console.log(err);
					$('#download i').addClass('hide');
					$('.crop-msg').text('Please crop the image and try to save!').addClass('crop-error').removeClass('crop-success').show();
				}
			});
		}
		$(document).on('click','.img-itemname',function(){
			var imgname = $('#imgname').val();
			$('#'+imgname).val($(this).attr('data-itemname'));
		});
		$(document).on('click','#getCroppedCanvasModal .triangle', function() {
			$('#getCroppedCanvasModal').modal('hide');
			$('#download').attr('data-cropped','');
		});    
		$( document ).ready(function() {
			var imgscr = $('#image').attr('src');
			if(!imgscr){
				$('.crop-btn').prop('disabled', true);
			}
		});
		function get_search_imglist(imgsrchval){
			$.ajax({
				url: "<?php echo URL::site('ajax/get_update_imglist'); ?>",
				type: 'POST',
				dataType: 'json',
				data: {"imgsearch": imgsrchval,"request":"search"},
				success: function (searchimglist) {
					$('#imglist').html('');
					$('#imglist').html(searchimglist);
				}
			});
		}
		$( document ).on('keyup','#imglist-srch',function() {
			var value = $(this).val().trim();
			var t = $(this);
			t.next('span').toggle(Boolean(t.val()));
			var imgsrchval=$('#imglist-srch').val();
			get_search_imglist(imgsrchval);
		});
	// searchbox clear
		$(".imgsrchclear").hide($(this).prev('input').val());
		$(".imgsrchclear").click(function () {
			$(this).prev('input').val('').focus();
			$(this).hide();
			get_search_imglist('');
		});
	});
</script>