<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Cms extends Controller_Admin_Website {

	public function _Construct() {
		parent::__construct($request, $response);
	} 
	public function action_create()
	{
		if(!Helper_Common::hasAccess('Create Pages') || !Helper_Common::hasAccess('Modify Pages')) {
			$this->session->set('denied_permission','1');
			$this->redirect('admin/dashboard');
		}
		$cmsmodel = ORM::factory('admin_cms');
		$this->template->title 	= 'Create Page';
		$this->render();
		
		$this->template->content->editor = Ckeditor::instance();
		$url_params = $this->request->param('id');
		if(strpos($url_params,'/')) {
			list($site_id,$page_id) = explode('/',$url_params);
		} else {
			$site_id = $url_params;
		}
		if(isset($site_id) && $site_id!='') {
			$Sitelist= ORM::factory('Sites')->where('id','=',$site_id)->find();
			$this->template->content->site_id = $site_id; 
			$this->template->content->site_name = $Sitelist->name; 
		}
		//$page_id = $this->request->param('id');
		if($this->request->method()==HTTP_Request::POST) {
			$post = $this->request->post();
			
			$post["page_content"] = addslashes($post["page_content"]);
			
			$validation = Validation::factory($post);
			$validation->rule('page_title', 	'not_empty')
						->rule('page_slug', 	'not_empty')
						->rule('page_content',	'not_empty');
			if($validation->check()) {
				$page_id = $post['page_id'];
				if(isset($page_id) && $page_id!='') {
					if(isset($post) && count($post)>0) {
						$updateStr = '';
						foreach($post as $key => $value) {
							if($key!='submit') {
								$updateStr  .=  $key." = '".$value."',";
							}
						}
						$updateStr = rtrim($updateStr,',');
					}
					$condtnStr = "page_id=".$page_id;
					$cmsmodel->updatePage($updateStr,$condtnStr);
					$this->template->content->success = 'Page updation successfull';
				} else {
					$page_id = $cmsmodel->insertPage($post);
					$this->template->content->success = 'Page creation successfull';
				}
			} else {
				$errors = $validation->errors('admin_cms');
				$this->template->content->errors = $errors;
			}		
		}
		/*echo '<pre>';print_r($this->template);echo '</pre>';
		die();*/
		if(isset($page_id) && $page_id!='') {
			$this->template->content->page_details = $cmsmodel->getPage('*','site_id ='.$site_id.' and page_id='.$page_id);
		}
	}
	public function action_common_create()
	{
		if(!(Helper_Common::is_admin())) {
			$this->redirect("admin/cms/pagelist");
		}
		
		$cmsmodel = ORM::factory('admin_cms');
		$usermodel = ORM::factory('admin_user');
		$sitesmodel = ORM::factory('admin_sites');
		
		$this->template->title 	= 'Create Page';
		$this->render();
		
		$this->template->content->editor = Ckeditor::instance();
		$url_params = $this->request->param('id');
		if(strpos($url_params,'/')) {
			list($site_id,$page_id) = explode('/',$url_params);
		} else {
			$site_id = $url_params;
		}
		if(isset($site_id) && $site_id!='') {
			$Sitelist= ORM::factory('Sites')->where('id','=',$site_id)->find();
			$this->template->content->site_id = $site_id; 
			$this->template->content->site_name = $Sitelist->name; 
		}
		//$page_id = $this->request->param('id');
		if($this->request->method()==HTTP_Request::POST) {
			$post = $this->request->post();
			
			//print_r($post);
			//$post["page_content"] = str_replace("<pre>",'',$post["page_content"]);
			//$post["page_content"] = str_replace("</pre>",'',$post["page_content"]);
			//$post["page_content"] = htmlspecialchars_decode($post["page_content"]);
			
			$post["page_content"] = addslashes($post["page_content"]);
			//echo "<hr>";
			//print_r($post);
			//die;
			
			$validation = Validation::factory($post);
			$validation->rule('page_title', 	'not_empty')
						->rule('page_slug', 	'not_empty')
						->rule('page_content',	'not_empty');
			if($validation->check()) {
				$page_id = $post['page_id'];
				unset($post['page_id']);
				if(isset($page_id) && $page_id!='') {
					unset($post['site_id']);
					if(isset($post) && count($post)>0) {
						$updateStr = '';
						foreach($post as $key => $value) {
							if($key!='submit') {
								$updateStr  .=  $key." = '".$value."',";
							}
						}
						$updateStr = rtrim($updateStr,',');
					}
					
					
					$fetch_field  = ' page_slug '; $fetch_condtn = 'page_id="' . $page_id.'" and common_status=1 and onlyadmin=1';
					$result       = $usermodel->get_table_details_by_condtn('page', $fetch_field, $fetch_condtn);
					if($result){
						$result = $result[0];
						$condtnStr = 'page_slug="'.$result["page_slug"].'"';
					}else{
						$condtnStr = "page_id=".$page_id;
					}
					
					//print_r($updateStr);echo "<br><br><br>";echo $condtnStr;
					$cmsmodel->updatePage($updateStr,$condtnStr);
					$this->template->content->success = 'Page updation successfull';
				} else {
					$post["common_status"]=1;
					$post["onlyadmin"]=1;
					$page_id = $cmsmodel->insertPage($post);
					$this->template->content->success = 'Page creation successfull';
				}
			} else {
				$errors = $validation->errors('admin_cms');
				$this->template->content->errors = $errors;
			}		
		}
		//die;
		/*echo '<pre>';print_r($this->template);echo '</pre>';
		die();*/
		if(isset($page_id) && $page_id!='') {
			$this->template->content->page_details = $cmsmodel->getPage('*','site_id ='.$site_id.' and page_id='.$page_id);
		}
	}
	public function action_pagelist()
	{
		$this->template->title = 'Page List';
		$this->render();
		$tempmodel = ORM::factory('admin_cms');
		$url_params = $this->request->param('id');
		if(strpos($url_params,'/')) {
			list($site_id,$page_id) = explode('/',$url_params);
		} else {
			$site_id = $url_params;
		}
		if(isset($site_id) && $site_id!='') {
			$Sitelist= ORM::factory('Sites')->where('id','=',$site_id)->find();
			$this->template->content->site_id = $site_id; 
			$this->template->content->site_name = $Sitelist->name; 
		}
		//$page_id = $this->request->param('id');
		if(isset($page_id) && $page_id!='') {
			$updateStr = 'status=3';
			$condtnStr = 'page_id='.$page_id;
			$tempmodel->updatePage($updateStr,$condtnStr);
			$this->template->content->success = 'Page Deleted Successfully';
		}
		$this->template->content->page_details = $tempmodel->getsitePages($site_id,0);
	}
	public function action_common_pagelist()
	{
		if(!(Helper_Common::is_admin())) {
			$this->redirect("admin/cms/pagelist");
		}
		
		$this->template->title = 'Page List';
		$this->render();
		$tempmodel = ORM::factory('admin_cms');
		$usermodel = ORM::factory('admin_user');
		$sitesmodel = ORM::factory('admin_sites');
		$url_params = $this->request->param('id');
		if(strpos($url_params,'/')) {
			list($site_id,$page_id) = explode('/',$url_params);
		} else {
			$site_id = $url_params;
		}
		if(isset($site_id) && $site_id!='') {
			$Sitelist= ORM::factory('Sites')->where('id','=',$site_id)->find();
			$this->template->content->site_id = $site_id; 
			$this->template->content->site_name = $Sitelist->name; 
		}
		//$page_id = $this->request->param('id');
		if(isset($page_id) && $page_id!='') {
			
			
			$fetch_field  = ' page_slug '; $fetch_condtn = 'page_id="' . $page_id.'" and common_status=1 and onlyadmin=1';
			$result       = $usermodel->get_table_details_by_condtn('page', $fetch_field, $fetch_condtn);
			$result = ($result)?$result[0]:'';
			
			$updateStr = 'status=3 ';
			//$condtnStr = 'page_id='.$page_id;
			$condtnStr = 'page_slug="'.$result["page_slug"].'"';
			$tempmodel->updatePage($updateStr,$condtnStr);
			$this->template->content->success = 'Page Deleted Successfully';
		}
		
		//Here Over ride to create common pages
		if(isset($_GET["d"]) && $_GET["d"]==1){
			$surl = URL::base(true)."site/".$this->current_site_slug;
			if($site_id){
				foreach($this->common_slug as $slug=>$v){
					//Common Footer Menu
					$dat['title']   = ucfirst(str_replace("-"," ",$v));
					$dat['url']     = $surl."/".$v;
					$dat['site_id'] = $site_id;
					
					//print_r($dat); echo "<br>";
					
					$fetch_field  = ' * '; $fetch_condtn = 'url="' . $surl."/".$v.'" and site_id='.$site_id;
					$result       = $usermodel->get_table_details_by_condtn('sitefootermenu', $fetch_field, $fetch_condtn);
					if(!$result){
						$sitesmodel->insertFooterMenu($dat);
					}
					//Common Footer Menu
					$fetch_field  = ' * '; $fetch_condtn = 'page_slug="' . $v.'" and common_status=1';
					$result       = $usermodel->get_table_details_by_condtn('page', $fetch_field, $fetch_condtn);
										
					$fetch_field  = ' * '; $fetch_condtn = 'page_slug="' . $v.'" and common_status=1 and site_id='.$site_id;
					$ckres       = $usermodel->get_table_details_by_condtn('page', $fetch_field, $fetch_condtn);
					//print_r($result);echo "<br>"; die;
					//print_r($ckres);echo "<br>";
					if($result && empty($ckres)){
						$resultdata = $result[0];
						$resultdata["site_id"] = $site_id;
						$resultdata["onlyadmin"] = 1;
						//print_r($resultdata); echo "<br>";
						$tempmodel->insertPage($resultdata);
					}
					
				}
			}
		}
			
			//die;
			$pl = $tempmodel->getsitePages($site_id,1);
		
		$this->template->content->page_details = $pl;
	}
}
