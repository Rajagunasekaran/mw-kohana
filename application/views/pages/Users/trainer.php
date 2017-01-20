<div id="wrap-index">
	<!-- Login header nav !-->
	<?php
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
					<a data-ajax='false' data-role="none" href="<?php echo URL::base(TRUE).'dashboard/index/'; ?>">
					<i class="fa fa-caret-left iconsize"></i>
				</a>
				</div>
				<div class="col-xs-6 aligncenter"><b>Personal Trainers<b></div>
				<div id="save-icon-button" class="col-xs-3"></div>
			</div>
		</div>
		<hr>
		<div class="row">
         <div class="col-xs-12 col-sm-offset-3 col-sm-6">
            <div class="panel panel-default">
               <div class="row">
                  <div class="col-xs-12">
							
                     <div class="input-group c-search" >
								<input type="text" id="contact-list-search" class="form-control" >
								<span class="input-group-btn">
									<button type="button" class="btn btn-default"><span class="fa fa-search text-muted"></span></button>
                        </span>
                     </div>
							
                  </div>
               </div>
					<ul id="contact-list" class="sTreeBase list-group" style="border:1px solid #ededed;">
                  <?php
						if($userdata && is_array($userdata) && count($userdata)>0){
							foreach($userdata as $k=>$v){
								?>
								<li class="list-group-item">
								<div class="col-xs-12 usercard">
									<div class="card_thumb hovercard_thumb col-xs-12">
										<div class='round_div'  onclick='view_trainer_profile("<?php echo $v["userid"]?>")'>
											<div class="col-xs-3 useravatar">
												<img width="65px" alt="" src="<?php echo $v["profile_img"]; ?>">
											</div>
											<div class="col-xs-9 card_thumb-content alignleft">
												<div class="datacol"><strong>
													<?php
													echo ($v["firstname"])?$v["firstname"]:$v["user_fname"];
													echo " ";
													echo ($v["lastname"])?$v["lastname"]:$v["user_lname"];
													?>
												</strong></div>
												<?php
												// if($v["business"]){
													?>
													<p class="inactivedatacol dot"  id="dot1">
														<?php
															echo ($v["background"])?$v["background"]:$v["background"];
														?>
													</p>
													<?php
												// }?>
											
											</div>
										</div>
										<div class="col-xs-12 card_thumb-options alignleft">
											<a data-toggle="modal" class="card_thumb-buttons" href="javascript:void(0);" onclick='view_trainer_profile("<?php echo $v["userid"]?>")'><span class="fa fa-user activedatacol"></span></a>
											<!--<a data-toggle="modal" class="card_thumb-buttons" href="#modal_joe"><span class="fa fa-user activedatacol"></span></a>-->
											<a class="card_thumb-buttons" href="javascript:void(0);"><span class="fa fa-calendar activedatacol"></span></a>
											<a class="card_thumb-buttons" href="javascript:void(0);" <?php echo (!empty($v['chat_req_id']) ? '' : '');?> onclick="goto_contact('<?php echo URL::base(TRUE)."admin/mailbox" ?>')" ><span class="fa fa-comment-o activedatacol" ></span></a>
											<?php
											/*
											$r = ($v["rating"])?$v["rating"]:0;
											?>
											<div id="stars-exist-<?php echo $k;?>" class="starrr activedatacol stars-existing" data-id="<?php echo $v["userid"]; ?>" data-rating="<?php echo $r; ?>"></div>
											Rating
											<span class="count-existing stars-exist-<?php echo $k;?>"><?php echo $r; ?></span> star(s)  <?php //echo $v["userid"];
											*/
											?>
										</div>
										
									</div>
								</div>
								<div class="clearfix"></div>
								</li>
								<?php
							}
						}	?>
               </ul>
            </div>
         </div>
      </div>
	</div>
</div>
<div id="trainer_profile_modal" class="modal fade" role="dialog" tabindex="-1"></div>