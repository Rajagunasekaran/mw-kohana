<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Users extends Controller_Website {

	public function _construct() {
         parent::__construct($request, $response);
   }
	public function action_checklog(){
		
	}
	public function action_addrating(){
		$userModel 		   = ORM::factory('userprofile');
		if ($this->request->method() == HTTP_Request::POST)
		{
			$post   = $this->request->post();
			$post["ratedby"] = $this->globaluser->pk();
			
			if($userModel->check_rate($post["userid"],$this->globaluser->pk())){
				
			}else{
				if($userModel->addrating($post)){
					$ratings = $userModel->get_user_ratings($post["userid"]);
					if($ratings){
						$ratings = $ratings[0]["rate"];
						echo $ratings;
						$this->session->set('success','Your rating was added successfully!!!');
					}else{
						
					}
				}else{
					
				}
			}
			$this->session->set('error','You have already rated this user...!');
			echo false;
			
		}
		exit;
	}
	public function action_viewtrainerprofile()
	{
		if ($this->request->method() == HTTP_Request::POST)
		{
			$post   = $this->request->post();
			$uid = $post["userid"];
			$specialties = array(
									"1"	=>	"Body transformation",
									"2"	=>	"Boxing",
									"3"	=>	"Core Strength",
									"4"	=>	"Weight Loss",
									"5"	=>	"Functional Training",
									"6"	=>	"Small Group Training",
									"7"	=>	"Strength",
									"8"	=>	"Pre/Post Pregnancy",
									"9"	=>	"Rehab/Physio",
									"10"	=>	"Martial Arts",
									"11"	=>	"Sports Specific Training"
							   );
			$siteid			   = ($this->session->get('current_site_id') ? $this->session->get('current_site_id') : '0');
			$userModel 		   = ORM::factory('userprofile');
			$subscribermodel = ORM::factory('admin_subscriber');
			$adminusermodel = ORM::factory('admin_user');
			$authid = $this->globaluser->pk();
			$roleid = $adminusermodel->user_role_load_by_name('Trainer');
			$userdata = $userModel->get_trainer_profile($uid, $siteid, $roleid);
			if($userdata){
				foreach($userdata as $k=>$v){
					if($v["qualifications"])
						$v["qualifications"] = explode("#,#",$v["qualifications"]);
					if($v["achievements"])
						$v["achievements"] = explode("#,#",$v["achievements"]);
					if($v["otherspecialties"])
						$v["otherspecialties"] = explode("#,#",$v["otherspecialties"]);
					//For Specialities
					if($v["specialties"]){
						$spl = explode("#,#",$v["specialties"]);
						$temp = array();
						foreach($spl as $s=>$d){
							$temp[] = (isset($specialties[$d]))?$specialties[$d]:$d;					
						}
						$v["specialties"] = $temp;
					}
					//For Profile Image
					$img = URL::base().'assets/img/user_placeholder.png';
					if(isset($v["profile_img"])  &&  $v["profile_img"]!=""){
						$getImg = $adminusermodel->get_users_profile_image($v["profile_img"]);
						if(file_exists($getImg["img_url"])){
							$img = URL::base().$getImg["img_url"];
						}
					}
					$v["profile_img"] = $img;
					
					$userdata[$k] = $v;
				}
			}
			//echo "<pre>";print_r($userdata); die;
			$userdata = $userdata[0];
			
			$name  =  ($v["firstname"])?$v["firstname"]:$v["user_fname"];
			$name .=  " ";
			$name .=  ($v["lastname"])?$v["lastname"]:$v["user_lname"];
			
			$str = "";
			$str .= "<div class='vertical-alignment-helper'><div class='modal-dialog'>
			<div class='modal-content'>
					<div class='modal-header'>
						<button type='button' class='close' data-dismiss='modal'>&times;</button>
						<h4 class='modal-title'>Personal Trainer</h4>
					</div>
					<div class='modal-body'>
						<div class='col-xs-12'>
							<div class='card_thumb hovercard_thumb' style='border:none;'>
								<div class=\"col-xs-5 useravatar row-no-padding\">
									<img class=\"img-responsive\" width=\"125px\" alt=\"\" src='".$v["profile_img"]."'>
								</div>
								<div class=\"col-xs-7 card_thumb-content row-no-padding alignleft\">
									<div class=\"datacol\"><h4>";
										$str .= ($v["firstname"])?$v["firstname"]:$v["user_fname"];
										$str .= " ";
										$str .= ($v["lastname"])?$v["lastname"]:$v["user_lname"];
										
									$str .="</h4></div>
									<a class=\"card_thumb-buttons\" href=\"#\" ><span class='fa fa-comment-o activedatacol'></span></a>
								</div>
							</div>	
							<!--div class='card hovercard'>
								<div class='card-background'>
									<img class='card-bkimg' alt='' src='".$userdata["profile_img"]."'>
								</div>
								<div class='useravatar'>
									<img alt='' src='".$userdata["profile_img"]."'>
								</div>
								<div class='card-info'> <span class='card-title' >$name</span> </div>
							</div-->
							<div class='btn-pref btn-group btn-group-justified btn-group-lg showtrainertab' role='group'>
								<div class='btn-group' role='group'>
									<button type='button' id='traineruser' class='btn-pref btn btn-default' href='#trainer_tab1' data-toggle='tab'>
										<i class='fa fa-user' aria-hidden='true'></i>
										<div class=''>Profile</div>
									</button>
								</div>
								<div class='btn-group' role='group'>
									<button type='button' id='specialties' class='btn-pref btn btn-default' href='#trainer_tab2' data-toggle='tab'>
										<i class='fa fa-th-list' aria-hidden='true'></i>
										<div class=''>Specialties</div>
									</button>
								</div>
								<div class='btn-group' role='group'>
									<button type='button' id='availabilities' class='btn-pref btn btn-default' href='#trainer_tab3' data-toggle='tab'>
										<i class='fa fa-calendar' aria-hidden='true'></i>
										<div class=''>Availabilites</div>
									</button>
								</div>
							</div>
							<!-- Profile Content(s) -->
							<div class='well'>
								<div class='tab-content'>
									<div class='tab-pane fade in' id='trainer_tab1'>
										<h4>Business</h4>
										<div class='dot5'>".$userdata["business"]."</div>
										<h4>Qualifications</h4>
										<ul class='pro_ul'>";
										if($userdata["qualifications"]){
											foreach($userdata["qualifications"] as $k=>$v){
												if($v){
								  $str.="<li>$v</li>";
												}
											}
										}
							  $str.="</ul>
										<h4 >Background &amp; Achievements</h4>
										<p class='dot5'>".$userdata["background"]."</p>
										<ul class='pro_ul'>
										";
										if($userdata["achievements"]){
											foreach($userdata["achievements"] as $k=>$v){
												if($v){
								  $str.="<li>$v</li>";
												}
											}
										}
							  $str.="</ul>
									</div>
									<div class='tab-pane fade in' id='trainer_tab2'>
										<h3 style='color:#000;'>Specialties</h3>
										<ul class='pro_ul'>";
										if($userdata["specialties"]){
											foreach($userdata["specialties"] as $k=>$v){
								  $str.="<li>$v</li>";
											}
										}
							  if(isset($userdata["otherspecialties"]) && !empty($userdata["otherspecialties"])){
										if($userdata["otherspecialties"]){
											foreach($userdata["otherspecialties"] as $k=>$v){
											if(!empty($v))
												$str .="<li>$v</li>";
											}
										}
							  }
							  $str .="</ul></div>
									<div class='tab-pane fade in' id='trainer_tab3'>
										<h3 style='color:#000;'>Availibilities &amp; Bookings</h3>
										<div> bookings calendar modules </div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class='modal-footer'> <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button> </div>
				</div>
			</div></div></div>
			<script type='text/javascript'>
			$(function() {
				$('.showtrainertab .btn').click(function () { $('.showtrainertab .btn').removeClass('btn-primary').addClass('btn-default'); $('.showtrainertab i.fa').css('color','#1b9af7'); $('.showtrainertab .tab').addClass('active'); $(this).removeClass('btn-default').addClass('btn-primary'); $(this).find('i.fa').css('color','#fffff'); //$('.dot5').dotdotdot();
				});	$('.showtrainertab button#traineruser').trigger('click');
				});
			</script>
			";
			echo $str;
			exit;
		}
	}
	public function action_trainer()
	{
		if (!Auth::instance()->logged_in()) {
			if($this->request->param('site_name')){
				$this->redirect(URL::site(NULL, 'http').'site/'.$this->request->param('site_name'));
			}
		}
		$specialties = array(
								"1"	=>	"Body transformation",
								"2"	=>	"Boxing",
								"3"	=>	"Core Strength",
								"4"	=>	"Weight Loss",
								"5"	=>	"Functional Training",
								"6"	=>	"Small Group Training",
								"7"	=>	"Strength",
								"8"	=>	"Pre/Post Baby",
								"9"	=>	"Rehab/Physio",
								"10"	=>	"Martial Arts",
								"11"	=>	"Sports Specific Training"
						  );
		$siteid			   = ($this->session->get('current_site_id') ? $this->session->get('current_site_id') : '0');
		$userModel 		   = ORM::factory('userprofile');
		$this->template->title = 'Site Trainers';
		$this->render();
		$subscribermodel = ORM::factory('admin_subscriber');
		$adminusermodel = ORM::factory('admin_user');
		$authid = $this->globaluser->pk();
		
		$roleid[] = $adminusermodel->user_role_load_by_name('Admin');
		$roleid[] = $adminusermodel->user_role_load_by_name('Manager');
		$roleid[] = $adminusermodel->user_role_load_by_name('Trainer');
		$roleid = implode(",",$roleid);
		
		$userdata = $userModel->get_site_trainer_with_profile($siteid, $roleid , $authid);
		//echo "<pre>";print_R($userdata);die;
		if($userdata){
			foreach($userdata as $k=>$v){
				$ratings = $userModel->get_user_ratings($v["userid"]);
				if($ratings)
					$v["rating"] = $ratings[0]["rate"];
				if($v["qualifications"])
					$v["qualifications"] = explode("#,#",$v["qualifications"]);
				if($v["achievements"])
					$v["achievements"] = explode("#,#",$v["achievements"]);
				//For Specialities
				if($v["specialties"]){
					$spl = explode("#,#",$v["specialties"]);
					$temp = array();
					foreach($spl as $s=>$d){
						$temp[] = (isset($specialties[$d]))?$specialties[$d]:$d;					
					}
					$v["specialties"] = $temp;
				}
				//For Profile Image
				$img = URL::base().'assets/img/user_placeholder.png';
				if(isset($v["profile_img"])  &&  $v["profile_img"]!=""){
					$getImg = $adminusermodel->get_users_profile_image($v["profile_img"]);
					if(file_exists($getImg["img_url"])){
						$img = URL::base().$getImg["img_url"];
					}
				}
				$v["profile_img"] = $img;
				
				$userdata[$k] = $v;
			}
		}
		$this->template->content->userdata = $userdata;
		//die;
	}
	
} // End Search
