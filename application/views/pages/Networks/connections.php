<div id="wrap-index">
	<!-- Login header nav !-->
	<?php
	$adminusermodel	= ORM::factory('admin_user');
	echo $topHeader;?>
	<div class="container" id="home">
		<div class="row"><?php
			$session = Session::instance();
			if ($session->get('success')): ?>
				<div class="banner success alert alert-success">
					<a data-ajax="false" data-role="none" href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					<?php echo $session->get_once('success') ?>
				</div><?php
			elseif ($session->get('error')): ?>
				<div class="banner danger alert alert-danger">
					<a data-ajax="false" data-role="none" href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					<?php echo $session->get_once('error') ?>
				</div><?php
			endif?>
		</div>
		<!--- Content Listing --->
		<div class="row">
			<div class="border">
				<div class="col-xs-3 aligncenter">
					<?php
					$back = URL::base(TRUE);
					if(isset($param) && $param!=""){
						$back = URL::base(TRUE).'networks/connections';
					}
					?>
					<a data-ajax='false' data-role="none" href="<?php echo $back; ?>">
					<i class="fa fa-caret-left iconsize"></i>
				</a>
				</div>
				<div class="col-xs-6 aligncenter"><b>Connections<b></div>
				<div id="save-icon-button" class="col-xs-3"></div>
			</div>
		</div>
		<hr>
<!-- Content Starts Here -->

<style type="text/css">
	.ms-select-all span {
    color: #42abff;
    margin-left: 10px;
}
.ms-drop ul > li label span {
    color: #42abff !important;
    margin-left: 8px !important;
	 cursor: pointer;
	 
}
.ms-drop ul > li label input {
    margin-bottom:7px !important;
	 cursor: pointer;
	 border-radius: 5px;
}
.ms-choice {
	background: none;
	border: none;
	color: #42ABFF;
	/*margin-top:-15px; */
}
.ms-drop.bottom {
   box-shadow: 0 4px 5px hsla(0, 0%, 0%, 0.15);
   top: 100%;
	margin-top:2px;
	width:100%;
}
.ms-parent{
	width:102%;
}
.ui-checkbox input, .ui-radio input {
    height: 10px;
    left: -3px;
    margin-bottom: 0;
    margin-left: 0;
    margin-right: 0;
    margin-top: -7px;
    outline-color: -moz-use-text-color !important;
    outline-style: none !important;
    outline-width: 0 !important;
    position: absolute;
    top: 50%;
    width: 10px;
    z-index: 1;
}

.userstatus_checkbox,.css-checkbox {
	position:absolute; z-index:-1000; left:-1000px; overflow: hidden; clip: rect(0 0 0 0); height:1px; width:1px; margin:-1px; padding:0; border:0;
	
}


.userstatus_checkbox + span.css-label,.css-checkbox + label.css-label {
	padding-left:20px;
	height:14px; 
	display:inline-block;
	line-height:14px;
	background-repeat:no-repeat;
	background-position: 0 0;
	font-size:14px;
	vertical-align:middle;
	cursor:pointer;
}
.userstatus_checkbox:checked + span.css-label ,.css-checkbox:checked + label.css-label {
	background-position: 0 -14px;
}
span.css-label,label.css-label {
	background-image:url('<?php  echo URL::base(TRUE); ?>../assets/css/images/checkIcon.png');
	-webkit-touch-callout: none;
	-webkit-user-select: none;
	-khtml-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;
}

.css-label{
	 margin-left: 23px;
}


</style>
<?php

?>
<div class="container bootstrap snippet" style='border:0px solid pink'>
	<input type='hidden' id='chat_to' value='<?php echo ( $param!='' )?$param:""; ?>'>
	<?php if(isset($param) && empty($param)){?>
	<div class="col-xs-12 col-sm-offset-3 col-sm-6">
		<div class="panel panel-default">
		   <div class="row">
			  <div class="col-xs-12">
				 <div class="input-group c-search " >
						<input type="text" class="form-control" id="contact-list-search" style="height: 44px;" onkeyup='search_users(this.value)'>
						<span class="input-group-btn">
							<button class="btn btn-default ui-btn ui-shadow ui-corner-all" type="button"><span class="fa fa-search text-muted"></span></button>
						</span>
				</div>
				 
						
			  </div>
			 
		   </div>
			 
		</div>
		<div class="row">
			<div class="col-xs-12">
				<select placeholder="Choose Role" name="roles" multiple="false" id="roles" class="fa-blue panel-margin" style='margin: 10px 0px 0px 0px'
				onchange='if($("#contact-list-search").val()!=""){search_users($("#contact-list-search").val());}'
				>
					<option value="2,8,7" selected='selected'>All</option>
					<option value="2" >Admin</option>
					<option value="8" >Managers</option>
					<option value="7" >Trainers</option>
				</select>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-12 col-sm-offset-3 col-sm-6 bg-white">
			<ul class="friend-list " id='friend-list' >
				<?php
				$adminusermodel	= ORM::factory('admin_user');
				$users = (isset($userdata)?$userdata:'');
				$str = '';
				if(isset($users) && is_array($users) && count($users)>0)
				{
					foreach($users as $k=>$v)
					{
						//For Profile Image
						$img = URL::base().'assets/img/user_placeholder.png';
						if(isset($v["profile_img"])  &&  $v["profile_img"]!=""){
							$getImg = $adminusermodel->get_users_profile_image($v["profile_img"]);
							if(file_exists($getImg["img_url"])){
								$img = URL::base().$getImg["img_url"];
							}
						}
						$name = ($v["firstname"])?$v["firstname"]:'';
						$name .= " ";
						$name .= ($v["lastname"])?$v["lastname"]:'';
						$name = ucfirst($name);
						$sub_title = ($v["background"]!='' && strlen($v["background"])>25)? substr($v["background"],0,25)."...":$v["background"];
						$concat = $v["userid"]."#@#$name#@#$img#@#$sub_title";
						$newcnt = 0;
						//echo "<br>".empty($v['is_read']);
						//echo "-----------".$v["chat_req_userid"]."-----".$v["userid"];
						if(empty($v['is_read'])){
							$newcnt = ORM::factory('networks')->get_network_users_unread_count($v["chat_req_userid"],$v["userid"]);
							
						}
						$times = Helper_Common::time_ago($v['chat_req_on']);
						$str .= '<li class="bounceInDown '.(!empty($newcnt) ? 'activenew' : '').'" id="row_'.$v["userid"].'">
							<a href="javascript:void(0);" onclick="get_request_chats('.$v["userid"].',\''.$concat.'\',this)" data-disable="0" class="clearfix">
								<img src="'.$img.'" alt="" class="img-circle">
								<div class="friend-name"><strong>'.$name.'</strong></div>
								<div class="last-message text-muted">'.$sub_title.'</div>
								<!--small class="time text-muted">'.$times.'</small-->
								<small class="chat-alert label label-danger">'.(!empty($newcnt) ? $newcnt : '').'</small>
							</a>
						</li>';
					}
				}
				else
				{
					if(isset($search) && !empty($search))
						$str .="<li class=\"bounceInDown\">No results found for \"$search\"</li>";
					else
						$str .="<li class=\"bounceInDown\">No data found</li>";
				}
				echo $str;
				?>
			</ul>
		</div>
	</div>
	<?php }else{ ?>
	<div class="row">
		<div class="col-xs-12 col-sm-offset-3 col-sm-6 bg-white">
			<div class="chat-message">
				<ul class="friend-list" id='info' >
					<li class="active">
						<?php
						$name = '';
						$img = "/assets/img/user_placeholder.png";
						//print_r($touserdetails);
						if(isset($touserdetails) && !empty($touserdetails)){
							$name = $touserdetails["user_fname"]." ".$touserdetails["user_lname"];
							if(isset($touserdetails["avatarid"])  &&  $touserdetails["avatarid"]!="")
							{
								$getImg = $adminusermodel->get_users_profile_image($touserdetails["avatarid"]);
								if(file_exists($getImg["img_url"]))
								{
									$img = URL::base().$getImg["img_url"];
								}
							}
						}
						?>
						
						<a class="clearfix ui-link" href="javascript:void(0);">
							<img class="img-circle" id='info_img' alt="" src="<?php echo $img; ?>">
							<div class="friend-name"><strong id="info_name"><?php echo ucfirst($name); ?></strong></div>
							<div class="last-message text-muted" id="info_msg" ></div>
						</a>
						
					</li>
				</ul>
				<ul class="chat" id="chat"  style="vertical-align: bottom;">
					<?php
					echo ($userchat!='')?$userchat:'';
					?>
				</ul>
				<br>
				<div class='chat_div <?php if($chat==0){echo "hide"; }?>' id='chat_div'>
					<div class="col-xs-11">
					<textarea id='chat_msg' class="form-control no-shadow no-rounded added_txtarea" style='height:45px;border:1px solid #1B9AF7;resize: both !important;'></textarea>
					</div>
					<div class='col-xs-1'>
						<button class="btn fa-white" type="button" onclick='chat_msg()' id='chat_send' style="margin-top:20px;"><i class='fa fa-send'></i></button>
					</div>
				</div>
			</div>
		</div>        
	</div>
	<?php } ?>
</div>
	<!-- Content Ends Here -->
	</div>
</div>
<div id="sendrequest_modal" class="modal fade" role="dialog" tabindex="-1">
	<div class="vertical-alignment-helper">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Send a contact Request to:</h4>
				</div>
				<div class="modal-body">
					<ul class="friend-list" id='send_req' style='margin-left:0px'>
						<li class="active bounceInDown">
							<a href="javascript:void(0);" class="clearfix">
								<img src="http://bootdey.com/img/Content/user_1.jpg" id="req_img" alt="" class="img-circle">
								<div class="friend-name"><strong id='req_name'>John Doe</strong></div>
								<div class="last-message text-muted" id='req_msg'>Hello, Are you there?</div>
							</a>
						</li>
					</ul>
					<p style='font-weight: normal'>They will see this message when your request is sent.</p>
					<textarea style='border:1px solid #999;width: 100%;resize: none' maxlength='300' id='send_msg'></textarea>
				</div>
				<div class="modal-footer">
					<input type='hidden' id='creq_id'>
					<input type='hidden' id='creq_name'>
					<input type='hidden' id='creq_img'>
					<input type='hidden' id='creq_msg'>	
					<button type="button" class="btn btn-primary" onclick='submit_request()'><i class='fa fa-paper-plane-o fa-white' style='color:#fff;'></i> Send</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
</div>


<?php
if(!$param){
	?>
	<script type='text/javascript'>
	$('#roles').multipleSelect({
		single: true//selectAllText: '<span class="ckbox">User Roles</span>',
	});
	</script>
	<?php
}
?>