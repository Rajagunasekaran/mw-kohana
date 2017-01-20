<?php defined('SYSPATH') or die('No direct script access.');


class Controller_Admin_Setlanguage extends Controller_Admin_Website {
	public function _Construct() {
		parent::__construct($request, $response);
	}
	public function action_index(){
		$this->redirect('admin/setlanguage/browse');
	}
	/*public function action_create()
   {
	  $authid  = $this->globaluser->pk();
	  $this->template->title = 'Create Attribute Language';
      $this->render();
      if ($this->request->method() == HTTP_Request::POST) {
         $post   = $this->request->post();
         $object = Validation::factory($post);
         $object->rule('nameattribute', 'not_empty')
				->rule('siteid', 'not_empty')
				->rule('status', 'not_empty');
         if ($object->check()) {
            $langattr_id = $post['langattr_id'];
            if (isset($langattr_id) && $langattr_id != '') {
               if (isset($post) && count($post) > 0) {
                  $updateStr = '';
                  foreach ($post as $key => $value) {
                     if ($key != 'submit') {
                        $updateStr .= $key . " = '" . $value . "',";
                     }
                  }
                  $updateStr = rtrim($updateStr, ',');
               }
               $condtnStr = "langattr_id=" . $langattr_id;
               $this->template->content->success = 'Attribute Name updated successfully!!!';
            } else {
               $template_id = $smtpmodel->insertEmailTemplate($post);
               $this->template->content->success = 'Attribute Name creation successfully!!!';
            }
            $this->template->content->template_details = $smtpmodel->getEmailTemplate('*', 'template_id=' . $template_id);
         } else {
            $errors = $object->errors('admin_user');
            $this->template->content->errors = $errors;
         }
      }
   }*/
	public function action_browse()
    {
      $setlangmodel       = ORM::factory('admin_setlanguage');
	  $usermodel          = ORM::factory('admin_user');
      $this->template->title = 'Browse Language'; 
      $this->render();
      $authid  = $this->globaluser->pk();
      $roleid                          = $usermodel->user_role_load_by_name('Register');
      $siteid                          = Session::instance()->get('current_site_id');
      $adminworkoutmodel               = ORM::factory('admin_workouts');
	  //fetch folder list
	  /*$files = scandir(\"\\versatile-25\xampp\htdocs\workspace\myworkout\application\i18n\sample\\");
	foreach($files as $file) {
	  echo "============".$file;
	}
	  die();*/
	  
     /* $dataall                         = $setlangmodel->getAttributeslanguage($authid, $siteid, $roleid, '', '');
	  if (isset($_POST) && count($_POST) > 0) {
         //print_r($_POST);
         $attridlang = $this->request->post('attrname');
         
         $this->template->content->template_details = $setlangmodel->filtersetlanguage($attridlang);
      } else {
         $lim                                = (isset($_REQUEST["lim"])) ? $_REQUEST["lim"] : 10;
         $cnt                                = count($dataall); //echo $cnt;
         $pagination                         = pagination::factory(array(
            'total_items' => $cnt,
            'items_per_page' => $lim,
            //'auto_hide'         => TRUE,
            'first_page_in_url' => TRUE
         ));
         //echo $pagination->items_per_page; 
         if (isset($_REQUEST['page'])) {
            $page_number = $_REQUEST['page'];
         } else {
            $page_number = 1;
         }
         $offset = $lim * ($page_number - 1);
         // Pass controller and action names explicitly to $pagination object
         $pagination->route_params(array(
            'controller' => $this->request->controller(),
            'action' => $this->request->action()
         ));
         $this->template->content->lim              = $lim;
         $this->template->content->template_details = $dataall;
      }
      $this->template->content->language_details = $dataall;*/
      //$this->template->content->usertags           = ORM::factory('admin_workouts')->get_user_created_tags($authid, 2);
      //$this->template->content->roleid             = $roleid;
      //$this->template->content->user_access_array  = $subscribermodel->get_user_access_by_condtn('*');
      /*$this->template->js_bottom                   = array(
         'assets/js/pages/admin/subscribers.js'
      );*/
    }
}
