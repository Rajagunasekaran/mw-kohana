<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Site_Contact extends Controller_Site_Website
{
   public function _construct()
   {
      parent::__construct($request, $response);
   }
   public function action_index()
   {
	  $slug_id = $this->request->param('site_id');
      if ($slug_id != '') {
         $sites = $this->_get_sites($slug_id);
         if (!$sites) {
            Auth::instance()->logout();
            $this->redirect();
         }
         $site_id = $sites["id"];
         // setting dynamic site id for users dated on 19th april 2016
         Session::instance()->set('current_site_id', $site_id);
         Session::instance()->set('current_site_name', $sites['name']);
         Session::instance()->set('current_site_slug', $sites['slug']);
         $page_slug = $this->request->param('slug_name');
         $siteModel = ORM::factory('site');
			$smtpmodel = ORM::factory('admin_smtp');
         if (Auth::instance()->logged_in() && !Helper_Common::is_admin()) {
            if ((($page_slug != 'questions' && $page_slug != 'logout') || $page_slug == '') && Auth::instance()->logged_in()) {
               $list = $siteModel->checkanswers($site_id, Auth::instance()->get_user()->pk());
               if (isset($list) && is_array($list) && count($list) > 0) {
               } else {
                  $this->redirect('site/' . $slug_id . '/questions');
               }
            }
         }
			$this->render();
			if ($this->request->method() == HTTP_Request::POST) {
				$post 	= $_POST;
				$role	= Helper_Common::get_role("admin");
				$admin  = Helper_Common::get_role_by_users($role, '');// Admin & Super Admin Role For All sites
				$role   = Helper_Common::get_role("manager");
				$manager= Helper_Common::get_role_by_users($role, $site_id);   // Only For Site Managers 
				$users 	= array();
				if($admin && $sites["is_contact"]==1){
					$users = $admin;
				}
				else if($manager && $sites["is_contact"]==0){
				   //$users = $manager;
					foreach($manager as $k=>$v){
						if($v["contact_status"]==1){
							$users[] = $v;
						}
					}
				}
				$post['dated'] = Helper_Common::get_default_datetime();
				$result = DB::insert('sitecontact', array_keys($post) )->values(array_values($post))->execute();
				if(isset($result) && count($users)>0){
					$contact_id = $result[0];
					$xrc = 0;
					foreach($users as $k=>$v){
						$con = array();
						$con["siteid"]    = $site_id;
						$con["contact_id"]= $contact_id;
						$con["userid"]    = $v["id"];
						//$con["email"]    = $v["email"];
						$res = DB::insert('sitecontact_mapping', array_keys($con) )->values(array_values($con))->execute();
						$messageArray ='';
						if(isset($res)){
							/*********************************Send Mail Box Email to Super Admin Role Users & Site Managers********************/
							$templateArray = $smtpmodel->getSendingMailTemplate(array('type_name' => 'Contact Us','site_id' => $this->data['siteidpk']));
							if(is_array($templateArray) && !empty($templateArray) && !empty($templateArray['smtp_id'])){
								$templateArray['subject'] = str_replace(array('[Site]'),array($sites["name"]),$templateArray['subject']);
								$templateArray['body'] = str_replace(array('[Site]','[fname]','[lname]','[email]','[phone]','[message]'),array($sites["name"],$post["firstname"],$post["lastname"],$post["email"],$post["phone"],$post["message"]),$templateArray['body']);
								if($xrc==0){
									DB::update('sitecontact')->set(array('subject' =>$templateArray['subject']))->set(array('mailcontent' =>$templateArray['body']))
									->where('id', '=', $contact_id)->execute();
								}
								$xrc++;
								$messageArray = array('subject'	=> $templateArray['subject'],
														  'from' 	=> (!empty($templateArray['smtp_replyto']) ? $templateArray['smtp_replyto'] : 'info@myworkouts.com'),
														  'fromname'=> (!empty($templateArray['smtp_from']) ? $templateArray['smtp_from'] : 'My workouts'),
														  'to'		=> $v["user_email"],
														  'cc'      => "prabakaran@versatile-soft.com",
														  'ccname'      => "Developer", 
														  'replyto'	=> (!empty($templateArray['smtp_replyto']) ? $templateArray['smtp_replyto'] : 'info@myworkouts.com'),
														  'toname'	=> ucfirst(strtolower($v['user_fname'])).' '.ucfirst(strtolower($v['user_lname'])),
														  'body'	=> ORM::factory('admin_smtp')->merge_keywords($templateArray['body'],$this->session->get('current_site_id')),
														  'type'	=> 'text/html');
								$hostAddress = explode("://",$templateArray['smtp_host']);
								$emailMailer = Email::dynamicMailer('smtp',array(
														  'hostname'   => trim($hostAddress['1']), 
														  'port' 	   => $templateArray['smtp_port'], 
														  'username'   => $templateArray['smtp_user'],   
														  'password'   => Helper_Common::decryptPassword($templateArray['smtp_pass']),
														  'encryption' => trim($hostAddress['0'])
														  )
											  );
							}else
								$emailMailer = Email::dynamicMailer('',array());
							if( is_array($messageArray) && false) {
								Email::sendBysmtp($emailMailer,$messageArray); 
							}
							/*********************************Send Mail Box Email to Super Admin Role Users & Site Managers********************/
						}
					}
					$this->session->set('flash_success', 'Thanks for contacting us we will reply to you soon!!!');
				}else{
					$this->session->set('flash_error', 'Thanks for contacting us we will reply to you soon!!!');
				}
			}
      } else {
         $this->redirect('');
      }
   }
} // End Site
