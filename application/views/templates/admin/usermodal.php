<?php echo HTML::script('assets/js/ad_script.js'); ?>
<div id="userModal" class="modal fade" role="dialog"></div>
<div id="editthisaccount" class="modal fade bs-example-modal-sm" role="dialog" data-keyboard="false" data-backdrop="static"></div>
<div id="mypopupModal" class="modal fade" role="dialog"></div>





<div id="mdl_common_popupimgprev-modal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content aligncenter">
			<div class="modal-header">
				<div class="row">
					<div class="popup-title">
						<div class="col-xs-2">
							<a href="javascript:void(0);" title="Back" class="triangle" onclick="$('#mdl_common_popupimgprev-modal').modal('hide');" data-ajax="false" data-role="none">
								<i class="fa fa-caret-left iconsize"></i>
							</a>
						</div>
						<div class="col-xs-8">Preview Image</div>
						<div class="col-xs-2 mdl_common_preview-opt"><!--i class="fa fa-ellipsis-h iconsize" data-toggle="modal" data-target="#mdl_popupimgact-modal"></i--></div>
					</div>
				</div>
			</div>
			<div class="modal-body" id="mdl_common_preview_libimg">
				<i class="fa fa-file-image-o prevfeat"></i>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" onclick="$('#mdl_common_popupimgprev-modal').modal('hide');" data-ajax="false" data-role="none">Close</button>
			</div>
		</div>
	</div>
</div>
	
<div id="statusmodal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog">
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title">Change User Status</h4>
	  </div>
	  <div class="modal-body">            
			  <div class="form-group">
				<label for="recipient-name" class="control-label">Choose Status:</label>
				<input type="hidden" class="form-control userstatus" name="userstatus" placeholder="Choose Status" value="" id="userstatus" style="width:100%" />
			 </div>
	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		<button type="button" class="btn btn-primary changeuserstatus">Save changes</button>
	  </div>
	</div>
  </div>
</div>
 
<div id="tagmodal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog">
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title">Tag User</h4>
	  </div>
	  <div class="modal-body">            
			  <div class="form-group">
				<label for="recipient-name" class="control-label">Choose Tags:</label>
				<input type="hidden" class="form-control tagnames" name="tagnames" placeholder="Choose Tags" value="" id="tagnames" style="width:100%" />
			   
			 </div>
	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		<button type="button" class="btn btn-primary addusertags">Save changes</button>
	  </div>
	</div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="shareModal" tabindex="-1" role="dialog" aria-labelledby="shareModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Share Workout</h4>
		  <?php
		  
		  ?>
      </div>
      <div class="modal-body">
        <form>
           <div class="form-group">
            <label for="workout-name" class="control-label">Workout(s):</label>           
            <!--input  type='text' class="form-control wkout_id" id="wkout_id" name= 'wkout_id'-->
				<select   id='wkout_id' class="wkout_id form-control select2-hidden-accessible" style="width: 100%;" multiple style="width:350px;" tabindex="4">
					<option value=""></option>
					<?php
					if(isset($workout_details) && count($workout_details)>0) {
						foreach($workout_details as $key => $value) {
							?>
							<option value="<?php echo $value['wkout_id'];?>"><?php echo $value['wkout_title'];?></option>
							<?php
						}
					}?>
				</select>
			</div>
          <div class="form-group">
            <label for="message-text" class="control-label">Message:</label>
            <textarea class="form-control" id="message"></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
				<input type='hidden' id='subscriber_id' >
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick='saveshare()'>Share</button>
      </div>
    </div>
  </div>
</div>	 
	 
<input type="hidden" name="curid" id="curid">
<div id="emailmodal" class="modal fade" tabindex="-1" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Send Email to <span id="expended" class="sendto"></span></h4>
			<div class="row">
				<div class="mobpadding">
					<div class="border full aligncenter opendiv" onclick="toggleDivEmail();">
						<i id="expendeddiv" class="fa fa-caret-up iconsize"></i>
					</div>
				</div>
			</div>
			<div class="alert alert-success statusmessage successsend" ></div>
          </div>
          <div class="modal-body">            
                  <div class="form-group">
                    <label for="recipient-name" class="control-label">Subject:</label>
                    <input type="text" class="form-control emailsubject" name="emailsubject" placeholder="Enter Subject" id="emailsubject"  />
                   
                 </div>
                  <div class="form-group">
                    <label for="recipient-name" class="control-label">Message:</label>
					<?php echo $editor->editor('emailmessage'); ?>
                   
                 </div>
				 <div class="form-group">
                   <input type="hidden" value="" name="currentmail"  id="currentmail" />
                   
                 </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary senduseremail">Send Email</button>
          </div>
        </div>
      </div>
    </div>


<?php /**************************Preview Question Moadl***************/ ?>
<style type="text/css">
#public li label { padding-left: 10px; }
.iradio_square-blue { float: left; max-width: 15%; width: 24px; }
.rightlabel { float: left; max-width: 85%; }
#previewans .questionlist li { clear: both;  margin-left:30px; }
#previewans .questionlist { list-style-type: lower-alpha; padding-left: 0px; font-weight:bold; }
#public h2 { font-size: 24px; }
.iradio_square-blue { top: 3px; }
@media only screen and (max-width :480px) {
	.main-wrapper { padding: 0px; }
}
.previewallbody{
	height:350px;
	overflow-x: hidden;
}
</style>
<div class="modal fade" id="previewquestionansModal" tabindex="-1" role="dialog" aria-labelledby="previewquestionansModal">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<!--button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button-->
				<h4 class="modal-title" id="titleLabel">"<?php echo $current_site_name; ?>" Question Preview</span></h4>
			</div>
			<div class="modal-body row ">
				<div class="col-sm-12 previewallbody">
					<div  id='previewans'>
						
						
						
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>				
			</div>
		</div>
	</div>
</div>

<?php echo $imglibrary; ?>
<script type="text/javascript">
	function profileChange(method){
	$('#userModalActions').html();
		$.ajax({
			url : siteUrl_frontend+"search/getmodelTemplate",
			data : {
				action : 'profileactions',
				method :  method,
				modelType : 'userModalActions'
			},
			success : function(content){
				$('#userModalActions').html(content);
				$('#userModalActions').modal();
			}
		});
	}
</script>