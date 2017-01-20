<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Site_Index extends Controller_Site_Website
{
   public function _construct()
   {
      parent::__construct($request, $response);
   }
   public function action_index()
   {
		
		$slug_id = $this->request->param('site_id');
		$sitesmodel = ORM::factory('admin_sites');
      if ($slug_id != '') {
         $sites = $this->_get_sites($slug_id);
         if (!$sites) {
            Auth::instance()->logout();
            $this->redirect();
         }
         $site_id = $sites["id"];
         // setting dynamic site id for users dated on 19th april 2016
		if(Session::instance()->get('current_site_id')!='' && Session::instance()->get('current_site_id')!=$site_id && Auth::instance()->logged_in()){
			 $sitesmodel->updateUserLastLogin($site_id,Auth::instance()->get_user()->pk());
		 }
         Session::instance()->set('current_site_id', $site_id);
         Session::instance()->set('current_site_name', $sites['name']);
         Session::instance()->set('current_site_slug', $sites['slug']);
         Session::instance()->set('current_site_agelimit', $sites['min_agelimit']);
         $page_slug = $this->request->param('slug_name');
         $siteModel = ORM::factory('site');
         if (Auth::instance()->logged_in() && !Helper_Common::is_admin()) {
            if ((($page_slug != 'questions' && $page_slug != 'logout') || $page_slug == '') && Auth::instance()->logged_in()) {
               $list = $siteModel->checkanswers($site_id, Auth::instance()->get_user()->pk());
               if (isset($list) && is_array($list) && count($list) > 0) {
               } else {
                  $this->redirect('site/' . $slug_id . '/questions');
               }
            }
         }
         if (!empty($page_slug)) {
            if ($page_slug == 'logout') {
				$userId = $this->globaluser->pk();
				/******************* Activity Feed *********************/
				$activity_feed = array();
				$activity_feed["feed_type"]   	= 10; // This get from feed_type table
				$activity_feed["action_type"]  	= 13;  // This get from action_type table  
				$activity_feed["type_id"]    	= $activity_feed["user"]  = $userId; // user id
				$activity_feed["site_id"]  		= $this->current_site_id;
				Helper_Common::createActivityFeed($activity_feed);
				/******************* Activity Feed *********************/
				/******************* Activity Feed Browser*********************/
				$activity_feed = array();
				$browserObj = new Helper_Browser();
				$browserName= $browserObj->getBrowser();
				$deviceName = $browserObj->getPlatform();
				$deviceInfo = $browserObj->__toString();
				$activity_feed["feed_type"]   	= 31; // This get from feed_type table
				$activity_feed["action_type"]  	= 13;  // This get from action_type table  
				$activity_feed["type_id"]    	= $activity_feed["user"]  = $userId; // user id
				$activity_feed["site_id"]  		= $this->current_site_id;
				$activity_feed["json_data"]  	= json_encode(array('device'=>$deviceName,'browser'=>$browserName,'userfrom'=>$this->session->get('user_from')));
				$activity_feed["extra_info"]	= $deviceInfo;
				Helper_Common::createActivityFeed($activity_feed);
				/******************* Activity Feed Browser*********************/
				Auth::instance()->logout();
				$this->redirect('/site/' . $slug_id);
            } else {
				if ($page_slug == 'questions') {
					if (!Auth::instance()->logged_in()) {
						$this->redirect('/site/' . $slug_id);
					}
					if (Auth::instance()->logged_in()) {
						$answer_list = $siteModel->checkanswers($site_id, Auth::instance()->get_user()->pk());
					}
					$questionmodel = ORM::factory('admin_questions');
					$cquestionmodel = ORM::factory('admin_commonquestions');
					$commonQ  = $sites['common_question'];
					$question      = $questionmodel->getQuestions($site_id);
					if($commonQ==1){
						$question1      = $cquestionmodel->getQuestions();
						$question = array_merge($question1,$question);
					}
					$answerArray = array();
					foreach($question as $keys => $values){
						foreach($answer_list as $key1 => $value1){
							if($value1['sqid'] == $values['id']){
								$answerArray[$values['id']]['answer'] = $value1['answer'];
								$answerArray[$values['id']]['ansid'] = $value1['id'];
								if(empty($value1['answer']))
									$answerArray[$values['id']]['sqoid'][$value1['sqoid']] = $value1['sqoid'];
								else
									$answerArray[$values['id']]['sqoid'] = $value1['sqoid'];
							}
						}
					}
					if (empty($question) && is_array($question) && count($question) == 0) {
						$this->redirect($slug_id . '/dashboard/index');
					}
					$sitemodel         = ORM::factory('site');
					$this->template->content = View::factory('pages/Public/question');
					$this->template->content->question_data = $answerArray;
					if ($this->request->method() == HTTP_Request::POST) {
						$post = $_POST;
						if (is_array($post) && count($post) > 0) {
							foreach ($post as $k => $v) {
							   $insert  =  $update = array();
							   $type_flag = true;
							   $insert["user_id"] = Auth::instance()->get_user()->pk();
							   $insert["site_id"] = $site_id;
							   $keys              = explode("-", $k);
							   $key               = $keys[0];
							   if ($key == 1 || $key == 5) {
								  $insert["sqid"]   = str_replace("ans", '', $keys[1]);
								  $insert["answer"] = $v;
								  if(isset($answerArray[$insert["sqid"]]['answer']) && $answerArray[$insert["sqid"]]['answer'] == $insert["answer"]){
									 $type_flag = false; 
									 $insert["id"] = $answerArray[$insert["sqid"]]['ansid'];
								  }
							   } elseif (($key == 2 || $key == 4) && isset($v) && $v != '') {
								  $id              = explode("_", $v);
								  $insert["sqid"]  = $id[0];
								  $insert["sqoid"] = $id[1];
								  if(isset($answerArray[$insert["sqid"]]['sqoid'][$insert["sqoid"]]) && isset($answerArray[$insert["sqid"]]['sqoid'][$insert["sqoid"]]) && $answerArray[$insert["sqid"]]['sqoid'][$insert["sqoid"]] == $insert["sqoid"]){
									 $type_flag = false; 
									 $insert["id"] = $answerArray[$insert["sqid"]]['ansid'];
								  }elseif(isset($answerArray[$insert["sqid"]]['sqoid']) && !isset($answerArray[$insert["sqid"]]['sqoid'][$insert["sqoid"]])){
									  $siteModel->changStatuseans($insert['site_id'],$insert['sqid'],$insert['user_id']);
								  }
							   } elseif ($key == 3) {
								  foreach ($v as $a => $b) {
									 $id              = explode("_", $b);
									 $insert["sqid"]  = $id[0];
									 $insert["sqoid"] = $id[1];
									 if($a == '0')
										$siteModel->changStatuseans($insert['site_id'],$insert['sqid'],$insert['user_id']);
									 $results  = $siteModel->insertans($insert);
								  }
							   }
							   if ($key != 3 && isset($v) && $v != '') {
								 if($type_flag)
								 $results  = $siteModel->insertans($insert);
								 else
								 $results  = $siteModel->updateans($insert);
							   }
							}
							$this->redirect($slug_id . '/dashboard/index');
						}
					}
               } else {
					$this->template->content = View::factory('pages/public/dynamic');
               }
               $this->template->content->header = View::factory('templates/site/header')->set('data', $this->data);
               $this->template->content->footer = View::factory('templates/site/footer')->set('data', $this->data);
               $data                            = $this->_getDynamicpagecontent($site_id, $page_slug);
               $this->template->content->siteid = $site_id;
               $this->template->content->data   = $data;
            }
         } else {
            $this->render();
            $sliders                                   = $this->_get_slider($site_id);
            $partners                                  = $this->_get_partnerlogo($site_id);
            $content                                   = $this->_getHomepageUnique($site_id);
            $this->template->content->settinghome_url = $sitesmodel->getgeneraltable("site_id = '".$site_id."' ",'sitehomepages');
            $this->template->content->listsliders      = $sliders;
            $this->template->content->listpartners     = $partners;
            $this->template->content->sitecontent      = $content;
            $this->template->content->slug_id          = $slug_id;
            $this->template->content->siteid           = $site_id;
            $this->template->content->sitetitle        = $sites["name"];
            $homecontent                               = $this->_getHomepageUnique($site_id);
            $this->template->content->homecontent      = $homecontent;
            $blockcontent                              = $this->_get_site_block_content($site_id);
            $this->template->content->blockcontent     = $blockcontent;
            $testimonial_result                        = $this->_site_cms_homepage_testimonials($site_id);
            $this->template->content->tesimonials      = $testimonial_result;
            $this->template->content->tot_users        = $this->_get_site_number_of_users($site_id);
            $this->template->content->tot_trainers     = $this->_get_site_number_of_trainers($site_id);
            $this->template->content->tot_exercisesets = $this->_get_site_number_of_exercisesets($site_id);
            $this->template->content->siteurl          = $this->data['siteurl'];
            if (HTTP_Request::POST == $this->request->method()) {
               if (isset($_POST['login'])) {
                  if (!Helper_Common::isCookieEnable()) {
                     $this->redirect('/site/' . $slug_id . '?cookie=0&form=login');
                  }
                  $usermodel = ORM::factory('user');
                  $remember  = array_key_exists('remember', $this->request->post()) ? (bool) $this->request->post('remember') : FALSE;
                  $validator = $usermodel->validate_user_login(arr::extract($_POST, array(
                     'user_email',
                     'password'
                  )));
                  if ($validator->check()) {
                     $user = Auth::instance()->login($this->request->post('user_email'), $this->request->post('password'), $remember);
                     // If successful, redirect user
                     if ($user) {
                        $user_id      = $this->_get_current_user()->id;
                        $site_idvalue = $this->session->get('current_site_id');
                        $usermodel->updateSiteIdbyUser($site_idvalue, $user_id);
                        // update to user_sites
                        $update_str = 'last_login=now()';
                        $condtn_str = 'user_id=' . $user_id . ' and site_id=' . $site_id;
                        $usermodel->updateUserSites($update_str, $condtn_str);
                        $list = $siteModel->checkanswers($site_id, $user_id);
						$this->session->set('newly_loggedin',$user_id) ;
						$redirectUrl = $this->session->get('site_return_url');
                        if (isset($list) && is_array($list) && count($list) > 0) {
						   if(!empty($redirectUrl))
								$this->redirect($redirectUrl);
                           $this->redirect($slug_id . '/dashboard/index');
                        } else {
                           $this->redirect('site/' . $slug_id . '/questions');
                        }
                     } else {
                        $this->session->set('common_error', 'Username or password is invalid');
                        $this->redirect('/site/' . $slug_id);
                     }
                  } else {
                     //mdh check for user site
                     if($this->session->get('changeto_site_id') != ''){
                        $idsite=$this->session->get('changeto_site_id') ;
                        
                        $siteinfodetails= $this->_get_sitesby_id($idsite);
                        $this->session->set('current_site_id',$siteinfodetails['id']) ;
                        $this->session->set('current_site_slug',$siteinfodetails['slug']) ;
                        $this->session->set('current_site_name',$siteinfodetails['name']) ;
                        $siteallsettings = Helper_Common::selectgeneralfn('site_settings','*','site_id="'.$idsite.'"');
                        $language_details = Helper_Common::getlanguage(isset($siteallsettings[0]['language']) ? $siteallsettings[0]['language'] : 1 );
                        if ($language_details != null)
                           $this->session->set('lang', $language_details);
                        else
                           $this->session->set('lang', 'en');
                        I18n::lang('front-'.$idsite.'-'.$this->session->get('lang').'-'.strtolower(Request::current()->directory()).'-'.strtolower(Request::current()->controller()));
                     }else{
                        $this->data['error_messages'] = $validator->errors('errors/en');
                     }
                  }
                  if (isset($this->data['error_messages']) && !empty($this->data['error_messages'])) {
                     foreach ($this->data['error_messages'] as $keys => $value) {
                        $this->session->set($keys . '_header_error', $value);
                     }
					 $this->session->set('flush_user_email',$this->request->post('user_email'));
                     $this->redirect('/site/' . $slug_id);
                  }
               }
            }
         }
      } else {
         $this->redirect('');
      }
   }
   public function _getDynamicpagecontent($site_id, $page_slug)
   {
      $dynamicpage = ORM::factory('dynamicpage');
      $result      = $dynamicpage->get_page_content($site_id, $page_slug);
      return $result;
   }
   public function _get_sites($slug_id)
   {
      $hompage = ORM::factory('homepage');
      $result  = $hompage->get_sites($slug_id);
      return $result;
   }
   public function _get_sitesby_id($site_id)
   {
      $hompage = ORM::factory('homepage');
      $result  = $hompage->get_sitesby_id($site_id);
      return $result;
   }
   public function _get_slider($site_id)
   {
      $hompage = ORM::factory('homepage');
      $result  = $hompage->site_slider($site_id);
      return $result;
   }
   public function _get_partnerlogo($site_id)
   {
      $hompage = ORM::factory('homepage');
      $result  = $hompage->site_partnerlogo($site_id);
      return $result;
   }
   public function _getHomepageUnique($site_id)
   {
      $hompage = ORM::factory('homepage');
      $result  = $hompage->site_cms_homepage_content($site_id);
      return $result;
   }
   public function _get_site_block_content($site_id)
   {
      $hompage = ORM::factory('homepage');
      $result  = $hompage->get_site_block_content($site_id);
      return $result;
   }
   public function _site_cms_homepage_testimonials($site_id)
   {
      $hompage = ORM::factory('homepage');
      $result  = $hompage->site_cms_homepage_testimonials($site_id);
      return $result;
   }
   public function _get_site_number_of_users($site_id)
   {
      $hompage = ORM::factory('homepage');
      $result  = $hompage->get_site_number_of_users($site_id);
      return $result;
   }
   public function _get_site_number_of_trainers($site_id)
   {
      $hompage = ORM::factory('homepage');
      $result  = $hompage->get_site_number_of_trainers($site_id);
      return $result;
   }
   public function _get_site_number_of_exercisesets($site_id)
   {
      $hompage = ORM::factory('homepage');
      $result  = $hompage->get_site_number_of_exercisesets($site_id);
      return $result;
   }
} // End Site
