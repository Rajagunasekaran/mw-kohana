<!--div id="userModal"  class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static"-->
    
		<?php /*echo HTML::script('assets/js/jquery.flot.js');
			  echo HTML::script('assets/js/jquery.flot.resize.js');
			  echo HTML::script('assets/js/jquery.flot.pie.min.js');*/
		?>
	<!--div id="user-1" class="modal-dialog">	
        <div class="modal-content">
            <div class="modal-header">
				<div class="row">
					<div class="title-header">
						<div class="col-xs-3">
							<a data-role="none" onclick="$('.sucessmsg').html('').hide();" data-ajax="false" href="javascript:void(0);" class="triangle" data-dismiss="modal">
								<i class="fa fa-caret-left iconsize"></i>
							</a>
						</div>
						<div class="col-xs-6 aligncenter">
							<b>My Account</b>
						</div>
						<div class="col-xs-3 save-icon-button">
							<button onclick="$('.sucessmsg').html('').hide();" data-ajax="false" data-role="none" type="button" class="close" data-dismiss="modal">&times;</button>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-body">
				<div class="container-fluid aligncenter">
					<div class="row sucessmsg" style="display:none;color:green;"></div><br>
				</div>
                <div class="container-fluid">
					<div class="row">
						<div class="col-lg-5">
							<p><img src="<?php echo URL::site('assets/img/user_placeholder.png');?>" width="150" height="150"/></p>
						</div>
						<div class="col-lg-7">
							<h3 style="margin-top:0;" class="user_name"></h3>
							<p><strong>Role : </strong><span class="user_role"></span></p>
							<p class="rgstr_flds user_age_contnr"><strong>Age : </strong><span class="user_age"></span></p>
							<p class="rgstr_flds user_dob_contnr"><strong>Birthdate : </strong><span class="user_dob"></span></p>
							<p class="rgstr_flds user_tag_contnr"><strong>Tags : </strong>
								<span class="user_tags"></span>
							</p>
							<p>
								<div class="dropdown">
								  <button data-ajax="false" data-role="none" class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Action
								  <span class="caret"></span></button>
								  <ul class="dropdown-menu">
									<li><a data-ajax="false" data-role="none" href="javascript:void(0);" onclick="profileChange('profileedit');">Edit Profile</a></li>
									<li><a data-ajax="false" data-toggle="modal" data-role="none" href="javascript:void(0);" style="color:gray" data-target="#editAbout">Edit About Me</a></li>
									<li><a data-ajax="false" data-toggle="modal" style="color:gray" data-role="none" href="javascript:void(0);" data-target="#updateBio">Update Bio</a></li>
									<li><a data-ajax="false" data-role="none" href="javascript:void(0);" onclick="profileChange('profilecancel');">Cancel my account</a></li>
									<li><a data-ajax="false" data-toggle="modal" style="color:gray" data-role="none" href="javascript:void(0);" data-target="#myConnections">My Connections</a></li>
								  </ul>
								</div>
							</p>
						</div>
					</div>
					<div class="row" style='clear:both;' id="morris-donutabove" >
						<div>
							<div class="panel panel-default">
								<div class="panel-body">
									<div id="placeholder" style="width:100%;height: 400px;margin-bottom:10px;"></div>
									<div id="chartLegend"></div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="row">
						<div>
							<div class="feed_row" style='overflow: auto; height:350px;'>
								<div class="panel panel-default">
									<div class="panel-heading">
										<h3 class="panel-title"><i class="fa fa-rss fa-fw"></i> Activity Feed</h3>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
            </div>
			<div class="modal-footer">
			  <button data-role="none" data-ajax="false" type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
        </div>
	</div>
	<div id="user-2" class="hide"></div-->
<!--/div-->
<?php //echo $imglibrary; ?>
<div id="myModel" class="modal fade bs-example-modal-sm" role="dialog" data-keyboard="false" data-backdrop="static"></div>
<script>
$(document).ready(function (){
	$('input').click(function(event) {
		event.stopPropagation();
	});
});
function confirmCancel(){
	$.ajax({
	   url: siteUrl + "ajax/profile/",
	   async:false,
	   data: {
		  method : 'cancel',
	   },
	   success: function(donnee) {
		console.log(donnee);
		  if(donnee.trim() == 'success'){
				$('#cancelConf').modal('hide');
				$('.sucessmsg').html('Request of proceed with this cancellation request was mailed to your Email').show();
			}
	   }
	});
}
function profileChange(method){
	$.ajax({
		url : siteUrl+"search/getmodelTemplate",
		data : {
			action : 'profileactions',
			method :  method,
			modelType : 'myModel'
		},
		success : function(content){
			$('#user-2').append(content);
			$('#user-2').removeClass('hide');
			$('#user-1').addClass('hide');
			if($("#dobch"))$("#dobch").mobipick();
		}
	});
}
</script>