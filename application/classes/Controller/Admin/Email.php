<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Email extends Controller_Admin_Website {

	public function _Construct() {
		parent::__construct($request, $response);
	} 
	public function action_create()
	{
		if(!Helper_Common::hasAccess('Create Template') || !Helper_Common::hasAccess('Modify Template')) {
			$this->session->set('denied_permission','1');
			$this->redirect('admin/dashboard');
		}
		$smtpmodel = ORM::factory('admin_smtp');
		$this->template->title 	= 'Create Email';
		$url_params = $this->request->param('id');
		if(strpos($url_params,'/')) {
			list($site_id,$template_id) = explode('/',$url_params);
		} else {
			$site_id = $url_params;
		}
		$this->render();
		$this->template->content->editor = Ckeditor::instance();
		$this->template->content->template_name_array = $smtpmodel->getEmailTemplate('template_id,template_name','site_id='.$site_id.' and status!=3');
		$this->template->content->smtp_array = $smtpmodel->getSmtpDetails('smtp_id,	smtp_user','site_id='.$site_id.' and smtp_active=1');
		
		//$template_id = $this->request->param('id');
		if(isset($template_id) && $template_id!='') {
			$this->template->content->template_details = $smtpmodel->getEmailTemplate('*','template_id='.$template_id);
		}
		if(isset($site_id) && $site_id!='') {
			$Sitelist= ORM::factory('Sites')->where('id','=',$site_id)->find();
			$this->template->content->site_id = $site_id; 
			$this->template->content->site_name = $Sitelist->name; 
		}
		if($this->request->method()==HTTP_Request::POST) {
			$post = $this->request->post();
			$object = Validation::factory($post);
			$object->rule('template_name', 'not_empty')
					->rule('subject', 'not_empty')
					->rule('body', 'not_empty')
					->rule('smtp_id', 'not_empty');
			if ($object->check()) {
				$template_id = $post['template_id'];
				if(isset($template_id) && $template_id!='') {
					if(isset($post) && count($post)>0) {
						$updateStr = '';
						foreach($post as $key => $value) {
							if($key!='submit') {
								if($key == 'subject' || $key == 'body')
									$value = addslashes($value);
								$updateStr  .=  $key." = '".$value."',";
							}
						}
						$updateStr = rtrim($updateStr,',');
					}
					$condtnStr = "template_id=".$template_id;
					$smtpmodel->updateEmailTemplate($updateStr,$condtnStr);
					$this->template->content->success = 'Template updation successfull';
				} else {
					$template_id = $smtpmodel->insertEmailTemplate($post);
					$this->template->content->success = 'Template creation successfull';
				}
				$this->template->content->template_details = $smtpmodel->getEmailTemplate('*','template_id='.$template_id);
			} else {
				$errors = $object->errors('admin_smtp');
				$this->template->content->errors = $errors;
			}
		}
		
	}
	
	public function action_emailvariables(){
		
		if(!Helper_Common::hasAccess('Manage Templates')) {
			$this->session->set('denied_permission','1');
			$this->redirect('admin/dashboard');
		}
		$this->template->title = 'Email Variables';
		$smtpmodel = ORM::factory('admin_smtp');
		$this->render();
		$url_params = $this->request->param('id');
		if(strpos($url_params,'/')) {
			list($site_id,$var_id) = explode('/',$url_params);
		} else {
			$site_id = $url_params;
		}
		$loginUser = Auth::instance()->get_user();
		if(isset($loginUser) && count($loginUser)>0) {
			$loginUserId = $loginUser->id;
			$fromEmail = $loginUser->user_email;
			$fromName = $loginUser->user_fname;
			$this->template->content->loginUserId = $loginUserId; 
		}	
		if(isset($var_id) && $var_id!='') {
			$this->template->content->variable_details = $smtpmodel->getEmailvariable('*','variable_id='.$var_id);
		}
		
		if($this->request->method()==HTTP_Request::POST) {
			$post = $this->request->post();
			$object = Validation::factory($post);
			$object->rule('name', 'not_empty')
					->rule('variable_content', 'not_empty');
			if ($object->check()) {
				if(isset($var_id) && $var_id!='') {
					if(isset($post) && count($post)>0) {
						$updateStr = '';
						foreach($post as $key => $value) {
							if($key!='submit') {
								if($key == 'name' || $key == 'variable_content'){
									$updateStr  .=  $key." = '".htmlspecialchars($value,ENT_QUOTES)."',";
								}else{	
									$updateStr  .=  $key." = '".$value."',";
								}	
							}
						}
						$updateStr = rtrim($updateStr,',');
					}
					$condtnStr = "variable_id= ".$var_id;
					$smtpmodel->updateEmailVariable($updateStr,$condtnStr);
					$this->template->content->success = 'Email variable updated successfully';
				}else{
					$var_id = $smtpmodel->insertEmailvariable($post,$site_id,$loginUserId);
					if($var_id == 0 ){
						$this->template->content->error = 'Variable already exist';
					}else{		
						$this->template->content->success = 'Variable created successfully !';
					}	
			    }	
				$this->template->content->variable_details = $smtpmodel->getEmailvariable('*','variable_id='.$var_id);
			} else {
				$errors = $object->errors('admin_smtp');
				$this->template->content->errors = $errors;
			}
		}
		
		
		$url_params = $this->request->param('id');
		if(strpos($url_params,'/')) {
			list($site_id,$template_id) = explode('/',$url_params);
		} else {
			$site_id = $url_params;
		}
		//echo $site_id;exit;
		if(isset($site_id) && $site_id!='') {
			$Sitelist= ORM::factory('Sites')->where('id','=',$site_id)->find();
			$this->template->content->site_id = $site_id; 
			$this->template->content->site_name = $Sitelist->name; 
		}
		$emailmodel = ORM::factory('admin_smtp');
		$this->template->content->editor = Ckeditor::instance();
	}	
	
	public function action_share_template(){
		$smtpmodel = ORM::factory('admin_smtp');
		if(isset($_POST) && count($_POST)>0) {
			$share_site_id = $_POST["share_site_id"];  // print_r($unit_id); die;
			$site_id  = $_POST["site_id"];
			$template_id  = $_POST["template_id"]; 
			$result = $smtpmodel->sharetEmailTemplate($share_site_id, $site_id, $template_id);
			echo $result; die;
		}	
		
	}	
	
	public function action_smtp()
	{
		if(!Helper_Common::hasAccess('Modify SMTP Settings') || !Helper_Common::hasAccess('Create SMTP Settings')) {
			$this->session->set('denied_permission','1');
			$this->redirect('admin/dashboard');
		}
		$this->template->title = 'SMTP FORM';
		$smtpmodel = ORM::factory('admin_smtp');
		$this->render();
		$url_params = $this->request->param('id');
		if(strpos($url_params,'/')) {
			list($site_id,$id) = explode('/',$url_params);
		} else {
			$site_id = $url_params;
		}
		//$id = urldecode($this->request->param('id'));
		if(isset($site_id) && $site_id!='') {
			$Sitelist= ORM::factory('Sites')->where('id','=',$site_id)->find();
			$this->template->content->site_id = $site_id; 
			$this->template->content->site_name = $Sitelist->name; 
		}
		$condtn = '1';
		if (HTTP_Request::POST == $this->request->method()){
			$post = $this->request->post();
			$object = Validation::factory($post);
			$object->rule('smtphost', 'not_empty')
					->rule('smtpport', 'not_empty')
					->rule('smtpuser', 'not_empty')
					->rule('smtppass', 'not_empty')
					->rule('smtpfrom', 'not_empty')
					->rule('smtpreplyto', 'not_empty')
					->rule('smtpreplyto', 'email');
			if ($object->check()) {
				$id = $this->request->post('smptid');
				$smtpmodelTemp['smtphost'] 		=	$this->request->post('smtphost');
				$smtpmodelTemp['smtpport'] 		=	$this->request->post('smtpport');
				$smtpmodelTemp['smtpuser'] 		=	$this->request->post('smtpuser');
				$smtpmodelTemp['smtppass'] 		=	Helper_Common::encryptPassword($this->request->post('smtppass'));
				$smtpmodelTemp['smtpfrom'] 		=	$this->request->post('smtpfrom');
				$smtpmodelTemp['smtpreplyto'] 	=	$this->request->post('smtpreplyto');
				$smtpmodelTemp['site_id'] 	=	$this->request->post('site_id');
				if($id!='') {
					$this->template->content->SmtpUpdate = $smtpmodel->updateSmtp($smtpmodelTemp,$id);
					$this->template->content->success = 'SMTP updation successfull';
				} else {
					$id = $smtpmodel->insertSmtpData($smtpmodelTemp);
					$this->template->content->success = 'SMTP insertion successfull';
				}
			} else {
				$errors = $object->errors('admin_smtp');
				$this->template->content->errors = $errors;
			}
		}
		if(isset($id) && $id!='') {
			$condtn = 'smtp_id='.$id.' and site_id='.$site_id ;
			$this->template->content->SmtpGet = $smtpmodel->getSmtpDetails('*',$condtn);
		}
	}
	public function action_templatename()
	{
		$this->template->title = 'Template Name';
		$this->render();
		$tempmodel = ORM::factory('admin_smtp');
		$url_params = $this->request->param('id');
		if(strpos($url_params,'/')) {
			list($site_id,$template_id) = explode('/',$url_params);
		} else {
			$site_id = $url_params;
		}
		//$template_id = $this->request->param('id');
		if(isset($site_id) && $site_id!='') {
			$Sitelist= ORM::factory('Sites')->where('id','=',$site_id)->find();
			$this->template->content->site_id = $site_id; 
			$this->template->content->site_name = $Sitelist->name; 
		}
		if(isset($template_id) && $template_id!='') {
			$updateStr = 'status=3';
			$condtnStr = 'template_id='.$template_id;
			$tempmodel->updateEmailTemplate($updateStr,$condtnStr);
			$this->template->content->success = 'Email Template Deleted Successfully';
		}
		$this->template->content->template_details = $tempmodel->getEmailTemplate('*','site_id='.$site_id.' and status!=3');
	}
	
	
	public function action_variablename(){
		$this->template->title = 'Variable Name';
		$this->render();
		$tempmodel = ORM::factory('admin_smtp');
		$url_params = $this->request->param('id');
		$user_list = $this->action_get_user_list(); //echo "user_list =".$user_list;
		if(strpos($url_params,'/')) {
			list($site_id,$var_id) = explode('/',$url_params);
		} else {
			$site_id = $url_params;
		}
		//$template_id = $this->request->param('id');
		if(isset($site_id) && $site_id!='') {
			$Sitelist= ORM::factory('Sites')->where('id','=',$site_id)->find();
			$this->template->content->site_id = $site_id; 
			$this->template->content->site_name = $Sitelist->name; 
		}
		if(isset($var_id) && $var_id!='') {
			$updateStr = 'status=1';
			$condtnStr = 'variable_id='.$var_id;
			$tempmodel->updateEmailVariable($updateStr,$condtnStr);
			$this->template->content->success = 'Email Variable Deleted Successfully';
		}
		$this->template->content->template_variables = $tempmodel->getEmailvariable('emv.*,usr. id, usr.user_fname, usr.user_lname', 'emv.created_by in ('.$user_list.') and emv.status!=1');
		//print_r($template_details); die;
	}	
	
	public function action_get_user_list(){
		$tempmodel = ORM::factory('admin_smtp');
		$loginUser = Auth::instance()->get_user();
		if(isset($loginUser) && count($loginUser)>0) {
			$loginUserId = $loginUser->id;
			$fromEmail = $loginUser->user_email;
			$fromName = $loginUser->user_fname;
			$this->template->content->loginUserId = $loginUserId; 
		}
		$admin_users = '';
		$admin_user_arr = $tempmodel->get_admin_user();
		if(!empty($admin_user_arr)){
			foreach ($admin_user_arr as $keys=>$values ){
				$admin_users .= $values['user_id'].',';
			}	
		}	
		$user_list = $admin_users.$loginUserId; 
		return $user_list;	
	}	
	
	public function action_smtpsettings()
	{
		$this->template->title = 'SMTP Settings';
		$tempmodel = ORM::factory('admin_smtp');
		$this->render();
		$url_params = $this->request->param('id');
		if(strpos($url_params,'/')) {
			list($site_id,$id) = explode('/',$url_params);
		} else {
			$site_id = $url_params;
		}
		if(isset($site_id) && $site_id!='') {
			$Sitelist= ORM::factory('Sites')->where('id','=',$site_id)->find();
			$this->template->content->site_id = $site_id; 
			$this->template->content->site_name = $Sitelist->name; 
		}
		//$id = urldecode($this->request->param('id'));
		if(isset($id) && $id!='') {
			$tempmodel->deleteSmtp($id,$site_id);	
			$this->template->content->success = 'SMTP deleted successfully';
		}
		$this->template->content->SmtpDetails =  $tempmodel->getSmtpDetails('*','site_id='.$site_id.' and smtp_active = "1"');
	}
	public function action_templatetype()
	{
		if(!Helper_Common::hasAccess('Manage Templates')) {
			$this->session->set('denied_permission','1');
			$this->redirect('admin/dashboard');
		}
		$this->template->title = 'Template Type';
		$this->render();
		$url_params = $this->request->param('id');
		if(strpos($url_params,'/')) {
			list($site_id,$template_id) = explode('/',$url_params);
		} else {
			$site_id = $url_params;
		}
		//echo $site_id;exit;
		if(isset($site_id) && $site_id!='') {
			$Sitelist= ORM::factory('Sites')->where('id','=',$site_id)->find();
			$this->template->content->site_id = $site_id; 
			$this->template->content->site_name = $Sitelist->name; 
		}
		$emailmodel = ORM::factory('admin_smtp');
		
		$this->template->content->template_name_array = $emailmodel->getEmailTemplate('template_id,template_name','site_id='.$site_id.' and status!=3');
		if (HTTP_Request::POST == $this->request->method()){
			$post = $this->request->post();
			if(isset($post['template_map']) && count($post['template_map'])>0) {
				foreach($post['template_map'] as $key => $value) {
					$updateStr = 'template_id='.$value['template_id'].',site_id='.$site_id;
					$condtnStr = 'type_id='.$value['type_id'];
					$emailmodel->updateEmailTemplateType($updateStr,$condtnStr);
				}
			}
			if(isset($post['staticlable']) && count($post['staticlable'])>0) {
				foreach($post['staticlable'] as $key => $value) {
					$value['type_name'] = $value['type_id'];
					$value['template_id'] =$value['template_id'];
					$value['site_id'] =$site_id;
					$emailmodel->insertEmailTemplateType($value);
				}
			}
			$this->template->content->success = 'Template type updation successfull';
		}
		$this->template->content->template_type_array = $emailmodel->getEmailTemplateType('*','site_id='.$site_id);
		//echo "<pre>";print_r($this->template->content->template_type_array);echo "</pre>"; 
		$template_type_array_new = $emailmodel->getEmailTemplateType('type_name','site_id='.$site_id);
		//echo "<pre>";print_r($template_type_array_new);echo "</pre>"; 
		$em_res = $emailmodel->array_value_recursive('type_name',$template_type_array_new);
		//echo "<pre>";print_r($em_res);echo "</pre>"; exit;
		$this->template->content->template_type_array_new = $em_res;
	}
	
	
	
	public function action_testemail()
	{
		
		$this->template->title = 'Test Email';
		$emailmodel = ORM::factory('admin_smtp');
		$this->render();
		$site_id = $this->request->param('id');
		if(isset($site_id) && $site_id!='') {
			$Sitelist= ORM::factory('Sites')->where('id','=',$site_id)->find();
			$this->template->content->site_id = $site_id; 
			$this->template->content->site_name = $Sitelist->name; 
		}
		$loginUser = Auth::instance()->get_user();
		if(isset($loginUser) && count($loginUser)>0) {
			$loginUserId = $loginUser->id;
			$fromEmail = $loginUser->user_email;
			$fromName = $loginUser->user_fname;
		}
		if (HTTP_Request::POST == $this->request->method()){
			$post = $this->request->post();
			if(isset($post) && count($post)>0) {
				if(isset($post['add-email'])) {
					$object = Validation::factory($post);
					$object->rule('test_email', 'not_empty')
							->rule('test_email', 'email');
					if($object->check()){
						$post['user_id'] = $loginUserId;
						$id = $emailmodel->insertTestEmail($post);
						$this->template->content->success = 'Test email added successfully';
					} else {
						$errors = $object->errors('admin_smtp');
						$this->template->content->errors = $errors;
					}
				} else if(isset($post['send-email'])) {
						$object = Validation::factory($post);
						$object->rule('test_email_array', 'not_empty')
								->rule('template_id', 'not_empty');
						if($object->check()) {
							if(is_array($post['test_email_array']) && count($post['test_email_array'])>0) {
								$errFlag = 0;
								foreach($post['test_email_array'] as $key => $value) {
									if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
									  $errFlag = 1;
									}
								}
								if($errFlag) {
									$errArray = array('test_email_array' => 'Test Email must be an email address');
									$this->template->content->errors = $errArray;
								} else {
									//$to_array = implode(',',$post['test_email_array']);
									$to_array = $post['test_email_array'];
									$template_id = $post['template_id'];
									$templateArray = $emailmodel->getSMTPbyMailTemplate(array('et.template_id' => $template_id));
									if(isset($templateArray) && count($templateArray)>0) {
										/*****************Code for email merge key*********************************/
										
										/*$url_params = $this->request->param('id');
										if(strpos($url_params,'/')) {
											list($site_id,$template_id) = explode('/',$url_params);
										} else {
											$site_id = $url_params;
										}*/
										$user_list = $this->action_get_user_list();
										
										$template_body_Array = $emailmodel->merge_keywordsByuser($templateArray['body'],$user_list);
										/*****************Code for email merge key*********************************/
										if(is_array($templateArray) && !empty($templateArray) && !empty($templateArray['smtp_id'])){
											$hostAddress = explode("://",$templateArray['smtp_host']);
											$emailMailer = Email::dynamicMailer('smtp',array(
																							  'hostname'   => $hostAddress['1'], 
																							  'port' 	   => $templateArray['smtp_port'], 
																							  'username'   => $templateArray['smtp_user'],   
																							  'password'   => Helper_Common::decryptPassword($templateArray['smtp_pass']),
																							  'encryption' => $hostAddress['0']
																							  )
																				);
										}else
											
											$emailMailer = Email::dynamicMailer('',array());
											foreach($to_array as $to_mail){
												$messageArray = array('subject'	=> $templateArray['subject'],
													  'from' 	=> (!empty($fromEmail) ? $fromEmail : 'admin@myworkout.com'),
													  'fromname'=> (!empty($templateArray['smtp_from']) ? $templateArray['smtp_from'] : 'My workouts'),
													  'to'		=> $to_mail,
													  'toname'	=> 'Test Email',
													  'replyto'	=> (!empty($templateArray['smtp_replyto']) ? $templateArray['smtp_replyto'] : (!empty($templateArray['smtp_from']) ? $templateArray['smtp_from'] : 'admin@myworkout.com')),
													  'body'	=> $template_body_Array,
													  'type'	=> 'text/html');
												Email::sendBysmtp($emailMailer,$messageArray);
											}
											$this->template->content->success = "Test Email sent successfully";
									} else {
										$errArray = array('test_email_array' => 'Something went wrong');
										$this->template->content->errors = $errArray;	
									}
								}
							}
						} else {
							$errors = $object->errors('admin_smtp');
							$this->template->content->errors = $errors;
						}
				}
			}
		}
		$this->template->content->test_email_array = $emailmodel->getTestEmails('test_email_id,test_email','user_id='.$loginUserId.' and site_id='.$site_id);
		$this->template->content->template_name_array = $emailmodel->getEmailTemplate('template_id,template_name','site_id ='.$site_id.' and status!=3');
	    $this->template->js_bottom = array('assets/js/bootstrap-tagsinput.min.js','assets/js/pages/admin/email.js');
		$this->template->css = array('assets/css/bootstrap-tagsinput.css');

	}
	public function action_delivery()
	{
		
		$this->template->title = 'SET DELIVERY FORM';
		$deliverymodel = ORM::factory('admin_smtp');
		$this->render();
		$url_params = $this->request->param('id');
		if(strpos($url_params,'/')) {
			list($site_id,$id) = explode('/',$url_params);
		} else {
			$site_id = $url_params;
		}
		if(isset($site_id) && $site_id!='') {
			$Sitelist= ORM::factory('Sites')->where('id','=',$site_id)->find();
			$this->template->content->site_id = $site_id; 
			$this->template->content->site_name = $Sitelist->name; 
		}
		if(isset($id) && $id!='') {
			$id = urldecode($id);
		}
		
		$condtn = '1';
		if (HTTP_Request::POST == $this->request->method()){
			$post = $this->request->post();
			
			$object = Validation::factory($post);
			$object->rule('d_title', 'not_empty')
					->rule('d_template', 'not_empty')
					->rule('d_rightaway', 'not_empty')
					->rule('d_senddate', 'not_empty');
			if ($object->check()) {
				$id = $this->request->post('d_id');
				$date = Helper_Common::get_default_datetime();
				$deliverymodelTemp['delivery_name'] 	=	$this->request->post('d_title');
				$deliverymodelTemp['template_id'] 		=	$this->request->post('d_template');
				$deliverymodelTemp['is_rightaway'] 		=	$this->request->post('d_rightaway');
				$deliverymodelTemp['send_date'] 		=	date("Y-m-d",strtotime($this->request->post('d_senddate')));
				$deliverymodelTemp['triggerby_days'] 	=	$this->request->post('d_days');
				$deliverymodelTemp['triggerby_hours'] 	=	$this->request->post('d_hoursminutues');
				$deliverymodelTemp['is_active'] 		=	$this->request->post('d_status');
				$deliverymodelTemp['site_id'] 			=	$this->request->post('site_id');
				$deliverymodelTemp['created_date'] 		=	$date;
				$deliverymodelTemp['modified_date']		=	$date;
				if($id!='') {
					$deliverymodel->updateDelivery($deliverymodelTemp,$id);
					$this->template->content->success = 'Delivery updation successfull';
				} else {
					$id = $deliverymodel->insertDeliveryData($deliverymodelTemp);
					$this->template->content->success = 'Delivery insertion successfull';
				}
			} else {
				$errors = $object->errors('admin_smtp');
				$this->template->content->errors = $errors;
			}
		}
		if(isset($id) && $id!='') {
			$condtn = 'delivery_id='.$id ;
			$this->template->content->DeliveryGet = $deliverymodel->getDeliveryDetails('*',$condtn);
		}
		$this->template->content->emailTemplateArray = $deliverymodel->getEmailTemplate('*','site_id ='.$site_id.' and status!=3');
	}
	public function action_deliverysettings()
	{
		$this->template->title = 'Delivery Settings';
		$deliverymodel = ORM::factory('admin_smtp');
		$this->render();
		$url_params = $this->request->param('id');
		if(strpos($url_params,'/')) {
			list($site_id,$id) = explode('/',$url_params);
		} else {
			$site_id = $url_params;
		}
		if(isset($site_id) && $site_id!='') {
			$Sitelist= ORM::factory('Sites')->where('id','=',$site_id)->find();
			$this->template->content->site_id = $site_id; 
			$this->template->content->site_name = $Sitelist->name; 
		}
		//$id = urldecode($this->request->param('id'));
		if(isset($id) && $id!='') {
			$id = urldecode($id);
			$deliverymodel->deleteDelivery($id);	
			$this->template->content->success = 'Delivery deleted successfully';
		}
		$this->template->content->deliveryDetails =  $deliverymodel->getDeliveryDetails('*','et.site_id ='.$site_id.' and  is_delete = "0"');
	}
}
	
