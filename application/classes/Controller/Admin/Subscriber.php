<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Admin_Subscriber extends Controller_Admin_Website
{
    public function before()
	{
		parent::before();
		$user_from = (isset($_GET['user_from']) && $_GET['user_from'] != '' ? $_GET['user_from'] : 'admin');
		Session::instance()->set('user_from',$user_from);
	}
	public function __construct(Request $request, Response $response)
	{
		parent::__construct($request, $response);
	}
   public function action_create()
   {
      $smtpmodel             = ORM::factory('admin_smtp');
      $this->template->title = 'Create Subscribers';
      $this->render();
      /*$template_id = $this->request->param('id');
      if(isset($template_id) && $template_id!='') {
      $this->template->content->template_details = $smtpmodel->getEmailTemplate('*','template_id='.$template_id);
      }*/
      if ($this->request->method() == HTTP_Request::POST) {
         $post   = $this->request->post();
         //print_r($post);
         $object = Validation::factory($post);
         $object->rule('user_fname', 'not_empty')->rule('user_lname', 'not_empty')->rule('user_email', 'not_empty')->rule('user_email', 'Controller_Admin_User::userEmailCheck', array(
            $post['user_email'],
            ':validation'
         ))->rule('password', 'not_empty');
         if ($object->check()) {
            $template_id = $post['template_id'];
            if (isset($template_id) && $template_id != '') {
               if (isset($post) && count($post) > 0) {
                  $updateStr = '';
                  foreach ($post as $key => $value) {
                     if ($key != 'submit') {
                        $updateStr .= $key . " = '" . $value . "',";
                     }
                  }
                  $updateStr = rtrim($updateStr, ',');
               }
               $condtnStr = "template_id=" . $template_id;
               $smtpmodel->updateEmailTemplate($updateStr, $condtnStr);
               $this->template->content->success = 'Template updation successfull';
            } else {
               $template_id                      = $smtpmodel->insertEmailTemplate($post);
               $this->template->content->success = 'Template creation successfull';
            }
            $this->template->content->template_details = $smtpmodel->getEmailTemplate('*', 'template_id=' . $template_id);
         } else {
            $errors                          = $object->errors('admin_user');
            $this->template->content->errors = $errors;
         }
      }
   }
   public static function userEmailCheck($user_email, Validation $validation)
   {
      if (stripos($user_email, '@')) {
         if ((bool) DB::select(array(
            DB::expr('COUNT(*)'),
            'total_count'
         ))->from('users')->where('user_email', '=', $user_email)->execute()->get('total_count')) {
            $query = DB::select('user_access')->from('users')->where('user_email', '=', $user_email)->execute();
            $list  = $query->as_array();
            if (isset($list) && count($list) > 0) {
               if ($list[0]['user_access'] == 6) {
                  // not yet confirmed
                  $validation->error('user_email', 'Email already exists but it is unconfirmed.');
               } else if ($list[0]['user_access'] == 1) {
                  // confirmed
                  $validation->error('user_email', 'Email already exists');
               }
            }
            //$validation->error('user_email', 'userEmailOrPhoneNotUnique');
         } else {
            return true;
         }
      } else {
         $justNums = preg_replace("/[^0-9]/", '', $user_email);
         //eliminate leading 1 if its there
         if (strlen($justNums) == 11)
            $justNums = preg_replace("/^1/", '', $justNums);
         if (is_numeric($justNums)) {
            $query = DB::select('user_access')->from('users')->where('user_mobile', '=', $user_email)->execute();
            $list  = $query->as_array();
            if (isset($list) && count($list) > 0) {
               if ($list[0]['user_access'] == 6) {
                  // not yet confirmed
                  $validation->error('user_email', 'Phone number already exists but it is unconfirmed.');
               } else if ($list[0]['user_access'] == 1) {
                  // confirmed
                  $validation->error('user_email', 'Phone number already exists');
               }
            }
            return true;
         }
         $validation->error('user_email', 'Email / Phone number is invalid.');
      }
   }
   public function action_browse()
   {
      $subscribermodel       = ORM::factory('admin_subscriber');
      $usermodel             = ORM::factory('admin_user');
      $this->template->title = 'Browse Subscribers'; //echo "USER ID" .$this->session->get('auth_user');exit;
      $this->render();
      $this->template->content->editor = Ckeditor::instance();
      $authid                          = $this->globaluser->pk();
      $roleid                          = $usermodel->user_role_load_by_name('Register');
      $siteid                          = Session::instance()->get('current_site_id');
      $adminworkoutmodel               = ORM::factory('admin_workouts');
      $dataall                         = $subscribermodel->get_site_subscribers($authid, $siteid, $roleid, '', '');
      if (isset($_POST) && count($_POST) > 0) {
         //print_r($_POST);
         $subscriberid = $this->request->post('subscribername');
         if ($subscriberid) {
            $subscriberid = implode(",", array_filter($subscriberid));
         }
         $gender   = $this->request->post('gender');
         $agerange = $this->request->post('setagerange');
         $from     = '';
         $to       = '';
         if ($agerange) {
            $agerange = explode("-", $agerange);
            $from     = $agerange[0];
            $to       = $agerange[1];
         }
         $this->template->content->template_details = $subscribermodel->filtersubscriber($subscriberid, $gender, $from, $to);
         $this->template->content->datatable        = 1;
      } else {
         $this->template->content->datatable = 1; //0;
         /***Pagination****/
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
         //$this->template->content->pagination = $pagination;
         $this->template->content->lim              = $lim;
         /***Pagination****/
         //$this->template->content->template_details  = $subscribermodel->get_site_subscribers($authid, $siteid, $roleid,$pagination->items_per_page, $offset);
         $this->template->content->template_details = $dataall;
      }
      $this->template->content->subscriber_details = $dataall;
      $this->template->content->usertags           = ORM::factory('admin_workouts')->get_user_created_tags($authid, 2);
		
      
		if (Helper_Common::is_admin())
			$this->template->content->workout_details    = $adminworkoutmodel->getWorkoutDetailsByUser($this->globaluser->pk(), '', '');
      else
         $this->template->content->workout_details    = $adminworkoutmodel->getWorkoutDetailsByUser($this->globaluser->pk(), '', $siteid);
		
		
      $this->template->content->status_array		= $usermodel->get_table_details_by_condtn('user_status','*');
		$this->template->content->roleid             = $roleid;
      $this->template->content->user_access_array  = $subscribermodel->get_user_access_by_condtn('*');
      $this->template->js_bottom                   = array(
         'assets/js/pages/admin/subscribers.js'
      );
   }
   public function action_deleteSubscriber()
   {
      if ($this->request->is_ajax())
         $this->auto_render = FALSE;
      $usermodel = ORM::factory('admin_subscriber');
      $user_id   = $_POST['id'];
      if (isset($user_id) && $user_id != '') {
         $updateStr = 'deleted=1';
         $condtnStr = 'id=' . $user_id;
         $usermodel->update_user($updateStr, $condtnStr);
         $data['success'] = true;
         $data['message'] = 'User Deleted Successfully';
         $this->response->body(json_encode($data));
      }
   }
}
