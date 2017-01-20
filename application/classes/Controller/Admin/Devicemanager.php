<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Devicemanager extends Controller_Admin_Website {

	public function _Construct() {
		parent::__construct($request, $response);	
	}

	public function action_create()
	{    
	    $user =  Auth::instance()->get_user();
		$this->template->title = 'Create Device';
		$this->render();
		$device_id = $this->request->param('id'); 
		$smtpmodel = ORM::factory('admin_smtp');
		$siteid = Session::instance()->get('current_site_id');
		$this->template->content->site_id = $siteid; 
		
		if(isset($device_id) && $device_id!='') {
			$this->template->content->device_details = $smtpmodel->getDevice('*','id='.$device_id);
		}
		
		
		if($this->request->method()==HTTP_Request::POST) {
			$post = $this->request->post(); //print_r($post);
			$object = Validation::factory($post);
			$object = Validation::factory($post);
			$object->rule('name', 'not_empty');
			if ($object->check()) {
				if(isset($device_id) && $device_id!='') {
					if(isset($post) && count($post)>0) {
						$updateStr = '';
						foreach($post as $key => $value) {
							if($key!='submit') {
								$updateStr  .=  $key." = '".$value."',";
							}
						}
						$updateStr = rtrim($updateStr,','); 
					}
					$condtnStr = "id=".$device_id; 
					$smtpmodel->updateDevice($updateStr,$condtnStr);
					$this->template->content->device_details = $smtpmodel->getDevice('*','id='.$device_id);
					$this->template->content->success = 'Device updated successfully !';
				}else{	
					$device_id = $smtpmodel->insertDevice($post); //echo "template_id".$template_id; die;
					if($device_id > 0 ){
						$this->template->content->success = 'Device created successfully !';
					}else{
						$this->template->content->success ='Device Already exists.';
					} 	
				}	
			}else{
				$errors = $object->errors('admin_smtp');
				$this->template->content->errors = $errors;
			}	
		}	
		
	}  
	public function action_browse()
	{    
	    $this->template->title = 'Browse Device';
		$device_id = $this->request->param('id');
		$user =  Auth::instance()->get_user();
		$this->render();
		$smtp_model = ORM::factory('admin_smtp');
		if(isset($device_id) && $device_id !=''){
			$updateStr = " Status = 2";
			$condtnStr = "id=".$device_id; 
			$smtp_model->updateDevice($updateStr,$condtnStr);
			$this->template->content->success = 'Device Deleted successfully!';
		}	
		$siteid = Session::instance()->get('admin_smtp');	
		$this->template->content->site_id = $siteid;
		$this->template->content->device_details = $smtp_model->getDevice('*','status !=2');
	}  
}
