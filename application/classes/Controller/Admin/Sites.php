<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Admin_Sites extends Controller_Admin_Website
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
   public function action_index()
   {
      $this->redirect('admin/sites/browse');
   }
   public function action_browse()
   {
      $browse = (isset($_GET['get']) ? $_GET['get'] : '');
      if (Helper_Common::is_trainer() || Helper_Common::is_manager()) {
         $this->redirect('admin/dashboard');
         return;
      }
      $sitesmodel                = ORM::factory('admin_sites');
      $this->template->title     = 'Browse Sites';
      $this->template->js_bottom = array(
         'assets/js/pages/admin/sites.js'
      );
      $this->render();
      if(!empty($browse) && $browse == 'exists'){
         $this->template->content->template_details = $sitesmodel->get_existsSites_byAdmin();
      }else{
         $this->template->content->template_details = $sitesmodel->get_allSites_byAdmin();
      }
   }
   public function action_create()
   {
      $sitesmodel = ORM::factory('admin_sites');
      if (Helper_Common::is_trainer() || Helper_Common::is_manager()) {
         $this->redirect('admin/dashboard');
         return;
      }
      $this->template->title     = 'Create Sites';
      $this->template->css       = array(
         'assets/plugins/iCheck/flat/blue.css'
      );
      $this->template->js_bottom = array(
         'assets/plugins/iCheck/icheck.js',
         'assets/js/pages/admin/sites.js'
      );
      //$this->template->js_bottom =
      //array('assets/js/pages/admin/sites.js');
      $this->render();
      $this->template->content->device_integration = $sitesmodel->getAlldevice();
      $usermodel  = ORM::factory('admin_user');
      $sitesmodel = ORM::factory('admin_sites');
      $cmsmodel   = ORM::factory('admin_cms');
      if ($this->request->method() == HTTP_Request::POST) {
         $post   = $this->request->post();
         
      
         //print_r($post);echo "<hr>";die;
         
         $surl   = URL::base(true) . "site/" . $post["slug"];
         $object = Validation::factory($post);
         $object->rule('name', 'not_empty')->rule('name', 'Controller_Admin_Sites::siteNameCheck', array(
            $post['name'],
            ':validation'
         ));
         if ($object->check()) { //Validate required fields
            $sites = ORM::factory('admin_sites');
            $slug  = $this->request->post('slug');
            if (empty($slug))
               $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $this->request->post('name'));
            $sites->name             = $this->request->post('name');
            $sites->slug             = $slug;
            $sites->is_contact        = $this->request->post('contact');
            $sites->is_active        = $this->request->post('status');
            $sites->sample_workouts  = $this->request->post('sample_workouts');
            $sites->exercise_records = $this->request->post('exercise_records');
            $sites->sample_images    = $this->request->post('sample_images');
            $sites->min_agelimit     = $this->request->post('min_agelimit');
            $sites->common_question     = $this->request->post('common_question');
            $sites->created_at       = Helper_Common::get_default_datetime();
            $sites->save();
            $insertId = $sites->pk();
			$sites->insertduplicatePreference(array() ,$insertId);
            //Common Page Creation for New sites
            if ($insertId) {
               foreach ($this->common_slug as $slug => $v) {
                  //Common Footer Menu
                  $dat['title']   = ucfirst(str_replace("-", " ", $v));
                  $dat['url']     = $surl . "/" . $v;
                  $dat['site_id'] = $insertId; //7d8t4w2g
                  $sitesmodel->insertFooterMenu($dat);
                  //Common Footer Menu
                  $fetch_field  = ' * ';
                  $fetch_condtn = 'site_id=1 and page_slug="' . $v . '"
                  and common_status=1';
                  $result       = $usermodel->get_table_details_by_condtn('page', $fetch_field, $fetch_condtn);
                  //print_r($result[0]["page_title"]);echo "<br>";
                  if ($result) {
                     $resultdata              = $result[0];
                     $resultdata["site_id"]   = $insertId;
                     $resultdata["onlyadmin"] = 1;
                     $cmsmodel->insertPage($resultdata);
                  }
               }
               // 11 My 2016 Praba
               $checkexitecontact = $sites->get_site_footer_menu_contact($insertId,$this->contact_title);
               //if($post["contact"]==1){
                  if(!$checkexitecontact){
                     $dat['title']   = $this->contact_title;
                     $dat['url']     =  URL::base(true) . "site/".$post["slug"]."/" . $this->contact_slug;
                     $dat['site_id'] = $insertId; //7d8t4w2g
                     $sites->insertFooterMenu($dat);  
                  }
               /*}else{
                  if($checkexitecontact){
                     $sites->delete_site_footer_menu_contact($insertId,$this->contact_title);
                  }
               }
               */
               // 11 My 2016 Praba
               if(isset($post["device"])){
                  foreach($post["device"] as $did => $status){
                     //echo "<br>$did----$status---".$insertId;
                     $checkdevice = $sitesmodel->checkdevice($did,$insertId);
                     $deviceTable_name = "sitedevice";
                     if(isset($checkdevice) && is_array($checkdevice) && count($checkdevice)>0){
                        $updatedata = array();
                        $updatequery = "update $deviceTable_name set status = $status where site_id=".$insertId." and device_id = ".$did;
                        $query = DB::query(Database::UPDATE, $updatequery);                  
                        $query->execute();                  
                     }else{
                        $insertdata = array();
                        $insertdata["site_id"]   = $insertId;
                        $insertdata["device_id"] = $did;
                        $insertdata["status"]    = $status;
                        $result = DB::insert($deviceTable_name, array_keys($insertdata))->values(array_values($insertdata))->execute();
                     }
                     
                  }
               }
               
               
            }
            //echo $insertId;die;
            //Common Page Creation for New sites
            $userSitesData                  = array();
            $userSitesData['user_id']       = Auth::instance()->get_user()->pk();
            $userSitesData['site_id']       = $insertId;
            $userSitesData['status']        = 1;
            $currentDate                    = Helper_Common::get_default_datetime();
            $userSitesData['last_login']    = $currentDate;
            $userSitesData['modified_date'] = $currentDate;
            $userSiteId                     = $sites->insertUserSites($userSitesData);
            // create default mail templates for this site
            $smtpmodel                      = ORM::factory('admin_smtp');
            //$condtnStr = "(template_name='Forgot Password' and site_id=1) or
            //(template_name='Assign a Workout Record' and site_id=1) or
            //(template_name='notification - shared workout' and site_id=1) or
            //(template_name='Register' and site_id=1) or
            //(template_name='Password reset' and site_id=1)";
            $condtnStr                      = "site_id=1 and status!=3";
            $defaultTemplates               = $smtpmodel->getEmailTemplate('*', $condtnStr);
            $templateTypeArray              = array();
            if (isset($defaultTemplates) && count($defaultTemplates) > 0) {
               foreach ($defaultTemplates as $key => $value) {
                  $value['site_id']                       = $insertId;
                  $templateId                             = $smtpmodel->insertEmailTemplate($value);
                  $templateTypeArray[$key]['type_name']   = $value['template_name'];
                  $templateTypeArray[$key]['template_id'] = $templateId;
                  $templateTypeArray[$key]['site_id']     = $insertId;
               }
            }
            // create default template type
            if (isset($templateTypeArray) && count($templateTypeArray) > 0) {
               foreach ($templateTypeArray as $key => $value) {
                  $smtpmodel->insertEmailTemplateType($value);
               }
            }
            // Give permission for manager role
            $rolemodel       = ORM::factory('admin_roleaccess');
            $getAllRoleTypes = $rolemodel->getRoleAccessTypeByContn('id');
            if (isset($getAllRoleTypes) && count($getAllRoleTypes) > 0) {
               foreach ($getAllRoleTypes as $key => $value) {
                  $roleId                           = $value['id'];
                  $roleInputArray['role_id']        = 8;
                  $roleInputArray['site_id']        = $insertId;
                  $roleInputArray['access_type_id'] = $roleId;
                  $rolemodel->insertRoleAcces($roleInputArray);
               }
            }
			
            //create language folder using siteid dh
            //$insertId = '222';
            $dir ="application/i18n/admin/".$insertId;
            if(!is_dir($dir)) { 
               mkdir($dir,0777); 
               chmod($dir,0777);
            }
            Helper_common::recurse_copy("assets/langsample/admin/sample",$dir);
            $dir ="application/i18n/front/".$insertId;
            if(!is_dir($dir)) { 
               mkdir($dir,0777);
               chmod($dir,0777);
            }
            Helper_common::recurse_copy("assets/langsample/front/sample",$dir);
            $this->session->set('flash_success', 'New Site created
                successfully!!!');
            $this->redirect('admin/sites/browse');
         } else {
            $this->session->set('flash_error', 'New Site Not created');
            $this->redirect('admin/sites/create');
         }
      }
   }
   public function action_edit()
   {
      $sites_id              = $this->request->param('id');
      $sites                 = ORM::factory('admin_sites', $sites_id);
      // echo '<pre>';print_r($sites);
      $this->template->title = 'Manage Sites Update';
      $this->render();
      $this->template->content->device_integration = $sites->getAlldevice_Integrations($sites_id);
      
      $this->template->css          = array(
         'assets/plugins/iCheck/flat/blue.css'
      );
      
      $this->template->js_bottom          = array(
         'assets/plugins/iCheck/icheck.js'
      );
      
      if (HTTP_Request::POST == $this->request->method()) {
         $post        = new Validation($_POST);
         $currentDate = Helper_Common::get_default_datetime();
         //Upload File
         $slug        = $this->request->post('slug');
         if (empty($slug))
            $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $this->request->post('name'));
         $sites->name             = $this->request->post('name');
         $sites->slug             = strtolower($slug);
         $sites->is_contact       = $this->request->post('contact');
         $sites->is_active        = $this->request->post('status');
         $sites->sample_workouts  = $this->request->post('sample_workouts');
         $sites->exercise_records = $this->request->post('exercise_records');
         $sites->sample_images    = $this->request->post('sample_images');
         $sites->min_agelimit     = $this->request->post('min_agelimit');
         $sites->common_question  = $this->request->post('common_question');
         $sites->modified_at      = Helper_Common::get_default_datetime();
         $sites->save();
         
         
         
         if(isset($sites_id)){
            // 11 My 2016 Praba
            $checkexitecontact = $sites->get_site_footer_menu_contact($sites_id,$this->contact_title);
            //if($post["contact"]==1){
               if(!$checkexitecontact){
                  $dat['title']   = $this->contact_title;
                  $dat['url']     = URL::base(true) . "site/".$post["slug"]."/" . $this->contact_slug;
                  $dat['site_id'] = $sites_id; //7d8t4w2g
                  $sites->insertFooterMenu($dat);  
               }
               //die;
            /*}else{
               if($checkexitecontact){
                  $sites->delete_site_footer_menu_contact($sites_id,$this->contact_title);
               }
            }
            */
            // 11 My 2016 Praba
         }
         
         
         if(isset($post["device"])){
            foreach($post["device"] as $did => $status){
               //echo "<br>$did----$status---".$insertId;
               $checkdevice = $sites->checkdevice($did,$sites_id);
               $deviceTable_name = "sitedevice";
               if(isset($checkdevice) && is_array($checkdevice) && count($checkdevice)>0){
                  $updatedata = array();
                  $updatequery = "update $deviceTable_name set status = $status where site_id=".$sites_id." and device_id = ".$did;
                  $query = DB::query(Database::UPDATE, $updatequery);                  
                  $query->execute();                  
               }else{
                  $insertdata = array();
                  $insertdata["site_id"]   = $sites_id;
                  $insertdata["device_id"] = $did;
                  $insertdata["status"]    = $status;
                  $result = DB::insert($deviceTable_name, array_keys($insertdata))->values(array_values($insertdata))->execute();
               }
            }
         }
         
         
         
         $this->session->set('flash_success', 'Sites Updated
            successfully!!!');
         $this->redirect('admin/sites');
      }
      $this->template->content->sites = $sites;
   }
   public static function siteNameCheck($site_name, Validation $validation)
   {
      if ((bool) DB::select(array(
         DB::expr('COUNT(*)'),
         'total_count'
      ))->from('sites')->where('name', '=', $site_name)->where('is_deleted', '=', 0)->execute()->get('total_count')) {
         $validation->error('name', 'Site Name already exists');
      } else {
         return true;
      }
   }
   public function action_deletesite()
   {
      if ($this->request->is_ajax())
         $this->auto_render = FALSE;
      $sitesmodel = ORM::factory('admin_sites');
      if (isset($_POST['id']) && !empty($_POST['id'])) {
         $site_id   = $_POST['id'];
         $updateStr = 'is_deleted=1';
         $condtnStr = 'id=' . $site_id;
         $sitesmodel->update_sites($updateStr, $condtnStr);
         Helper_Common::updategeneralfn('user_sites','status= "4"','site_id = "'.$site_id.'" ');
         $data['success'] = true;
         $data['message'] = 'Site Deleted
         Successfully';
         $this->response->body(json_encode($data));
      }
   }
   public function action_deleteSites()
   {
      if ($this->request->is_ajax())
         $this->auto_render = FALSE;
      $sitesmodel = ORM::factory('admin_sites');
      $site_id    = $_POST['id'];
      if (isset($site_id) && $site_id != '') {
         $updateStr = 'is_deleted=1';
         $condtnStr = 'id=' . $site_id;
         $sitesmodel->update_sites($updateStr, $condtnStr);
         $data['success'] = true;
         $data['message'] = 'Site Deleted Successfully';
         $this->response->body(json_encode($data));
      }
   }
   public function action_deleteAssignedSites()
   {
      if ($this->request->is_ajax())
         $this->auto_render = FALSE;
      $assignsitesDel = ORM::factory('admin_assignsites');
      $site_id        = $_POST['id'];
      $userid         = $_POST['uid'];
      if (isset($site_id) && $site_id != '') {
         $assignsitesDel->removeSitesByUser($userid, $site_id);
         $data['success'] = true;
         $data['message'] = 'Site Removed
         Successfully';
         $this->response->body(json_encode($data));
      }
   }
   public function action_slidercreate()
   {
      if (!Helper_Common::hasAccess('Manage Home Page')) {
         $this->session->set('denied_permission', '1');
         $this->redirect('admin/dashboard');
      }
      $this->template->current_tab = 'Sliderbrowse';
      $sliderData                  = new Model_Siteslider();
      //$sliderData = ORM::factory('Site_cms_homepage_slider');
      $site_id                     = $this->request->param('id');
      $Sitelist                    = ORM::factory('Sites')->where('id', '=', $site_id)->find();
      $this->template->title       = $Sitelist->name . ' Slider Create';
      //$sliderData = array();
      if (HTTP_Request::POST == $this->request->method()) {
         $post                        = new Validation($_POST);
         $currentDate                 = Helper_Common::get_default_datetime();
         //Upload File
         $sliderData->s_title         = $this->request->post('s_title');
         $sliderData->s_content       = $this->request->post('s_content');
         $sliderData->s_url           = $this->request->post('s_url');
         $sliderData->content_bgcolor = $this->request->post('content_bgcolor');
         $sliderData->content_border  = $this->request->post('content_border');
         $sliderData->tile_color      = $this->request->post('tile_color');
         $sliderData->content_color   = $this->request->post('conten_color');
         $sliderData->text_shadow     = $this->request->post('text_shadow');
         $sliderData->site_id         = $site_id;
         $sliderData->is_active       = 1;
         if (isset($_FILES) && $_FILES['sliderphoto']['error'] == 0) {
            $file_name                 = $this->_save_image($_FILES['sliderphoto'], 1170, 400, DOCROOT . 'assets/uploads/manage/homepage/slider/');
            $sliderData->date_modified = $currentDate;
            $sliderData->s_image       = $file_name;
            $sliderData->save();
            // save new slider details
            //$homepage->insertSliderData($sliderData);
            // Reset values so form is not sticky
            $_POST = array();
            $this->session->set('flash_success', 'Homepage
            Slider created successfully!!!');
            $this->redirect('admin/Sites/sliderbrowse/' . base64_encode($site_id));
         } else {
            $this->data['error_messages'] = array(
               'Slider image should not
            empty!!!'
            );
         }
      }
      $this->render($this->template, 'pages/Admin/Sites/slidercreate');
      $this->template->content->SliderDetails = $sliderData;
      $this->template->content->site_id       = $site_id;
      $this->template->content->site_name     = $Sitelist->name;
   }
   public function action_sliderbrowse()
   {
      if (!Helper_Common::hasAccess('Manage Home Page')) {
         $this->session->set('denied_permission', '1');
         $this->redirect('admin/dashboard');
      }
      $this->template->current_tab = 'Sliderbrowse';
      $site_id                     = $this->request->param('id');
      $Sitelist                    = ORM::factory('Sites')->where('id', '=', $site_id)->find();
      $this->template->title       = $Sitelist->name . ' Slider Details';
      //$site_id = 1;
      $Sliderlist                  = ORM::factory('Siteslider')->where('is_delete', '=', '0')->where('site_id', '=', $site_id)->find_all()->as_array();
      //$Sliderlist= ORM::factory('Siteslider');
      $this->render($this->template, 'pages/Admin/Sites/slider_browse');
      $this->template->content->Sliderlist = $Sliderlist;
      $this->template->content->site_id    = $site_id;
      $this->template->content->site_name  = $Sitelist->name;
   }
   public function action_slider_edit()
   {
      if (!Helper_Common::hasAccess('Manage Home Page')) {
         $this->session->set('denied_permission', '1');
         $this->redirect('admin/dashboard');
      }
      $this->template->current_tab = 'Sliderbrowse';
      $sliderid                    = $this->request->param('id');
      $sliderData                  = ORM::factory('Siteslider', $sliderid);
      $Sitelist                    = ORM::factory('Sites')->where('id', '=', $sliderData->site_id)->find();
      if (HTTP_Request::POST == $this->request->method() && isset($_POST['hidden-sliderid']) && !empty($_POST['hidden-sliderid']) && base64_decode($_POST['hidden-sliderid']) == $sliderid) {
         $post                        = new Validation($_POST);
         $currentDate                 = Helper_Common::get_default_datetime();
         //Upload File
         $sliderData->s_title         = $this->request->post('s_title');
         $sliderData->s_content       = $this->request->post('s_content');
         $sliderData->s_url           = $this->request->post('s_url');
         $sliderData->tile_color      = $this->request->post('tile_color');
         $sliderData->content_color   = $this->request->post('content_color');
         $sliderData->text_shadow     = $this->request->post('text_shadow');
         $sliderData->content_border  = $this->request->post('content_border');
         $sliderData->content_bgcolor = $this->request->post('content_bgcolor');
         $sliderData->date_modified   = $currentDate;
         //Upload File
         $filename                    = '';
         if (isset($_FILES) && $_FILES['sliderphoto']['error'] == 0) {
            $filename = $this->_save_image($_FILES['sliderphoto'], 1170, 400, DOCROOT . 'assets/uploads/manage/homepage/slider/');
         }
         if (!empty($filename)) {
            $unlinkOldImage = DOCROOT . 'assets/uploads/manage/homepage/slider/' . $sliderData->s_image;
            if (file_exists($unlinkOldImage))
               unlink($unlinkOldImage);
            $sliderData->s_image = $filename;
         }
         $sliderData->save();
         // Reset values so form is not sticky
         $_POST = array();
         $this->session->set('flash_success', $Sitelist->name . ' Slider updated successfully!!!');
         $this->redirect('admin/sites/sliderbrowse/' . $sliderData->site_id);
      }
      $this->render($this->template, 'pages/Admin/Sites/slidercreate');
      $this->template->content->SliderDetails = $sliderData;
      $this->template->content->site_id       = $sliderData->id;
      $this->template->content->site_name     = $Sitelist->name;
   }
   /*
    * slider delete */
   public function action_sliderdelete()
   {
      $id = $this->request->post('id');
      if ($id) {
         //$sliderData = new Model_Siteslider($id);
         $sliderData            = ORM::factory('Siteslider', $id);
         $sliderData->is_delete = 1;
         $sliderData->save();
      }
      echo 1;
      exit;
   }
   protected function _save_image($image, $width, $height, $directory)
   {
      $extenstion = strrchr($image['name'], ".");
      if (!Upload::valid($image) OR !Upload::not_empty($image) OR !Upload::type($image, array(
         'jpg',
         'jpeg',
         'png',
         'gif'
      ))) {
         return FALSE;
      }
      if ($file = Upload::save($image, NULL, $directory)) {
         $filename = strtolower(Text::random('alnum', 20)) . '.' . $extenstion;
		 if(!empty($width) && !empty($height))
			Image::factory($file)->resize($width, $height, Image::NONE)->save($directory . $filename);
		 else
			Image::factory($file)->save($directory . $filename);
         // Delete the temporary file
         unlink($file);
         return $filename;
      }
      return FALSE;
   }
   public function action_blockbrowse()
   {
      if (!Helper_Common::hasAccess('Manage Home Page')) {
         $this->session->set('denied_permission', '1');
         $this->redirect('admin/dashboard');
      }
      $this->template->current_tab = 'Block';
      $site_id                     = $this->request->param('id');
      $Sitelist                    = ORM::factory('Sites')->where('id', '=', $site_id)->find();
      $BlockDetails                = ORM::factory('admin_Siteblock')->where('is_delete', '=', '0')->where('site_id', '=', $site_id)->find_all()->as_array();
      $this->render($this->template, 'pages/Admin/Sites/blockbrowse');
      $this->template->content->BlockDetails = $BlockDetails;
      $this->template->content->site_id      = $site_id;
      $this->template->content->site_name    = $Sitelist->name;
   }
   public function action_blockcreate()
   {
      if (!Helper_Common::hasAccess('Manage Home Page')) {
         $this->session->set('denied_permission', '1');
         $this->redirect('admin/dashboard');
      }
      $this->template->current_tab = 'Block';
      $blockData                   = new Model_admin_Siteblock();
      //$sliderData = ORM::factory('Site_cms_homepage_slider');
      $site_id                     = $this->request->param('id');
      $Sitelist                    = ORM::factory('Sites')->where('id', '=', $site_id)->find();
      $this->template->title       = $Sitelist->name . ' Blocks Create';
      //$sliderData = array();
      if (HTTP_Request::POST == $this->request->method()) {
         $post                     = new Validation($_POST);
         $currentDate              = Helper_Common::get_default_datetime();
         //Upload File
         $blockData->b_title       = $this->request->post('b_title');
         $blockData->b_description = $this->request->post('b_description');
         $blockData->b_url         = $this->request->post('b_url');
         $blockData->site_id       = $site_id;
         $blockData->is_active     = 1;
         if (isset($_FILES) && $_FILES['blockphoto']['error'] == 0) {
            echo $file_name = $this->_save_image($_FILES['blockphoto'], 273, 273, DOCROOT . 'assets/uploads/manage/homepage/block/');
            $blockData->date_modified = $currentDate;
            $blockData->b_image       = $file_name;
            $blockData->save();
            // save new slider details
            //$homepage->insertSliderData($sliderData);
            // Reset values so form is not sticky
            $_POST = array();
            $this->session->set('flash_success', 'Homepage
            block details created successfully!!!');
            $this->redirect('admin/sites/blockbrowse/' . $site_id);
         } else {
            $this->data['error_messages'] = array(
               'Block image should not
            empty!!!'
            );
         }
      }
      $this->render($this->template, 'pages/Admin/Sites/blockcreate');
      $this->template->content->editor       = Ckeditor::instance();
      $this->template->content->BlockDetails = $blockData;
      $this->template->content->site_id      = $site_id;
      $this->template->content->site_name    = $Sitelist->name;
   }
   /*
    * User edit */
   public function action_blockedit()
   {
      if (!Helper_Common::hasAccess('Manage Home Page')) {
         $this->session->set('denied_permission', '1');
         $this->redirect('admin/dashboard');
      }
      $this->template->current_tab = 'Block';
      $sliderid                    = $this->request->param('id');
      $blockData                   = ORM::factory('admin_Siteblock', $sliderid);
      $Sitelist                    = ORM::factory('Sites')->where('id', '=', $blockData->site_id)->find();
      if (HTTP_Request::POST == $this->request->method() && isset($_POST['hidden-blockid']) && !empty($_POST['hidden-blockid']) && base64_decode($_POST['hidden-blockid']) == $sliderid) {
         $post                     = new Validation($_POST);
         $currentDate              = Helper_Common::get_default_datetime();
         //Upload File
         $blockData->b_title       = $this->request->post('b_title');
         $blockData->b_description = $this->request->post('b_description');
         $blockData->b_url         = $this->request->post('b_url');
         $blockData->is_active     = $this->request->post('status');
         $blockData->date_modified = $currentDate;
         //Upload File
         $filename                 = '';
         if (isset($_FILES) && $_FILES['blockphoto']['error'] == 0) {
            $filename = $this->_save_image($_FILES['blockphoto'], 273, 273, DOCROOT . 'assets/uploads/manage/homepage/block/');
         }
         if (!empty($filename)) {
            $unlinkOldImage = DOCROOT . 'assets/uploads/manage/homepage/block/' . $blockData->b_image;
            if (file_exists($unlinkOldImage))
               unlink($unlinkOldImage);
            $blockData->b_image = $filename;
         }
         $blockData->save();
         // Reset values so form is not sticky
         $_POST = array();
         $this->session->set('flash_success', $Sitelist->name . ' Block updated successfully!!!');
         $this->redirect('admin/sites/blockbrowse/' . $blockData->site_id);
      }
      $this->render($this->template, 'pages/Admin/Sites/blockcreate');
      $this->template->content->BlockDetails = $blockData;
      $this->template->content->editor       = Ckeditor::instance();
      $this->template->content->site_id      = $blockData->site_id;
      $this->template->content->site_name    = $Sitelist->name;
   }
   /*
    * block delete */
   public function action_blockdelete()
   {
      $id = $this->request->post('id');
      if ($id) {
         //$sliderData = new Model_Siteslider($id);
         $blockData            = ORM::factory('admin_Siteblock', $id);
         $blockData->is_delete = 1;
         $blockData->save();
      }
      echo 1;
      exit;
   }
   public function action_partnerbrowse()
   {
      if (!Helper_Common::hasAccess('Manage Home Page')) {
         $this->session->set('denied_permission', '1');
         $this->redirect('admin/dashboard');
      }
      $this->template->current_tab = 'Partner';
      $site_id                     = $this->request->param('id');
      $Sitelist                    = ORM::factory('Sites')->where('id', '=', $site_id)->find();
      $partnerDetails              = ORM::factory('admin_Sitepartner')->where('is_delete', '=', '0')->where('site_id', '=', $site_id)->find_all()->as_array();
      $this->render($this->template, 'pages/Admin/Sites/partnerbrowse');
      $this->template->content->PartnerDetails = $partnerDetails;
      $this->template->content->site_id        = $site_id;
      $this->template->content->site_name      = $Sitelist->name;
   }
   public function action_partnercreate()
   {
      if (!Helper_Common::hasAccess('Manage Home Page')) {
         $this->session->set('denied_permission', '1');
         $this->redirect('admin/dashboard');
      }
      $this->template->current_tab = 'Partner';
      $partnerData                 = new Model_admin_Sitepartner();
      //$sliderData = ORM::factory('Site_cms_homepage_slider');
      $site_id                     = $this->request->param('id');
      $Sitelist                    = ORM::factory('Sites')->where('id', '=', $site_id)->find();
      $this->template->title       = 'Manage ' . $Sitelist->name . ' Partner Create';
      //$sliderData = array();
      if (HTTP_Request::POST == $this->request->method()) {
         $post                   = new Validation($_POST);
         $currentDate            = Helper_Common::get_default_datetime();
         //Upload File
         $partnerData->p_title   = $this->request->post('p_title');
         $partnerData->p_url     = $this->request->post('p_url');
         $partnerData->site_id   = $site_id;
         $partnerData->is_active = 1;
         if (isset($_FILES) && $_FILES['partnerphoto']['error'] == 0) {
            $file_name                 = $this->_save_image($_FILES['partnerphoto'], 1170, 400, DOCROOT . 'assets/uploads/manage/homepage/partner/');
            $partnerData->date_created = $currentDate;
            $partnerData->p_image      = $file_name;
            $partnerData->save();
            // save new slider details
            //$homepage->insertSliderData($sliderData);
            // Reset values so form is not sticky
            $_POST = array();
            $this->session->set('flash_success', $Sitelist->name . ' partner details created successfully!!!');
            $this->redirect('admin/sites/partnerbrowse/' . $site_id);
         } else {
            $this->data['error_messages'] = array(
               'Partner image should not
            empty!!!'
            );
         }
      }
      $this->render($this->template, 'pages/Admin/Sites/partnercreate');
      $this->template->content->PartnerDetails = $partnerData;
      $this->template->content->site_id        = $site_id;
      $this->template->content->site_name      = $Sitelist->name;
   }
   public function action_partneredit()
   {
      if (!Helper_Common::hasAccess('Manage Home Page')) {
         $this->session->set('denied_permission', '1');
         $this->redirect('admin/dashboard');
      }
      $this->template->current_tab = 'Partner';
      $partnerid                   = $this->request->param('id');
      $partnerData                 = ORM::factory('admin_Sitepartner', $partnerid);
      $Sitelist                    = ORM::factory('Sites')->where('id', '=', $partnerData->site_id)->find();
      if (HTTP_Request::POST == $this->request->method() && isset($_POST['hidden-partnerid']) && !empty($_POST['hidden-partnerid']) && base64_decode($_POST['hidden-partnerid']) == $partnerid) {
         $post                       = new Validation($_POST);
         $currentDate                = Helper_Common::get_default_datetime();
         //Upload File
         $partnerData->p_title       = $this->request->post('p_title');
         $partnerData->p_url         = $this->request->post('p_url');
         $partnerData->is_active     = $this->request->post('status');
         $partnerData->date_modified = $currentDate;
         //Upload File
         $filename                   = '';
         if (isset($_FILES) && $_FILES['partnerphoto']['error'] == 0) {
            //print_r($_FILES);
            $filename = $this->_save_image($_FILES['partnerphoto'], 1170, 400, DOCROOT . 'assets/uploads/manage/homepage/partner/');
         }
         if (!empty($filename)) {
            $unlinkOldImage = DOCROOT . 'assets/uploads/manage/homepage/partner/' . $partnerData->p_image;
            if (file_exists($unlinkOldImage))
               unlink($unlinkOldImage);
            $partnerData->p_image = $filename;
         }
         $partnerData->save();
         // Reset values so form is not sticky
         $_POST = array();
         $this->session->set('flash_success', $Sitelist->name . 'Homepage Partner updated successfully!!!');
         $this->redirect('admin/sites/partnerbrowse/' . $partnerData->site_id);
      }
      $this->render($this->template, 'pages/Admin/Sites/partnercreate');
      $this->template->content->PartnerDetails = $partnerData;
      $this->template->content->site_id        = $partnerData->site_id;
      $this->template->content->site_name      = $Sitelist->name;
   }
   public function action_partnerdelete()
   {
      $id = $this->request->post('id');
      if ($id) {
         //$sliderData = new Model_Siteslider($id);
         $partnerData            = ORM::factory('admin_Sitepartner', $id);
         $partnerData->is_delete = 1;
         $partnerData->save();
      }
      echo 1;
      exit;
   }
   public function action_testimonialcreate()
   {
      if (!Helper_Common::hasAccess('Manage Home Page')) {
         $this->session->set('denied_permission', '1');
         $this->redirect('admin/dashboard');
      }
      $this->template->current_tab = 'Testimonial';
      $site_id                     = $this->request->param('id');
      $Sitelist                    = ORM::factory('Sites')->where('id', '=', $site_id)->find();
      $blockData                   = ORM::factory('admin_Sitetestimonial');
      $this->template->title       = 'Manage ' . $Sitelist->name . ' Testimonial Create';
      if (HTTP_Request::POST == $this->request->method()) {
         $post                     = new Validation($_POST);
         $currentDate              = Helper_Common::get_default_datetime();
         //Upload File
         $blockData->t_title       = $this->request->post('t_title');
         $blockData->t_user        = $this->request->post('t_user');
         $blockData->t_description = $this->request->post('t_description');
         $blockData->is_active     = 1;
         $blockData->site_id       = $site_id;
         $blockData->date_created  = $currentDate;
         // save new slider details
         $blockData->save();
         // Reset values so form is not sticky
         $_POST = array();
         $this->session->set('flash_success', $Sitelist->name . ' Testimonial created successfully!!!');
         $this->redirect('admin/sites/testimonialbrowse/' . $site_id);
      }
      $this->render($this->template, 'pages/Admin/Sites/testimonialcreate');
      $this->template->content->editor       = Ckeditor::instance();
      $this->template->content->BlockDetails = $blockData;
      $this->template->content->site_id      = $site_id;
      $this->template->content->site_name    = $Sitelist->name;
   }
   public function action_testimonialedit()
   {
      if (!Helper_Common::hasAccess('Manage Home Page')) {
         $this->session->set('denied_permission', '1');
         $this->redirect('admin/dashboard');
      }
      $this->template->current_tab = 'Challenger';
      $testimonial_id              = $this->request->param('id');
      $blockData                   = ORM::factory('admin_Sitetestimonial', $testimonial_id);
      $Sitelist                    = ORM::factory('Sites')->where('id', '=', $blockData->site_id)->find();
      $this->template->title       = 'Manage ' . $Sitelist->name . ' HomePage Testimonial
      Create';
      if (HTTP_Request::POST == $this->request->method()) {
         $post                     = new Validation($_POST);
         $currentDate              = Helper_Common::get_default_datetime();
         //Upload File
         $blockData->t_title       = $this->request->post('t_title');
         $blockData->t_user        = $this->request->post('t_user');
         $blockData->t_description = $this->request->post('t_description');
         $blockData->is_active     = $this->request->post('status');
         $blockData->date_created  = $currentDate;
         // save new slider details
         $blockData->save();
         // Reset values so form is not sticky
         $_POST = array();
         $this->session->set('flash_success', $Sitelist->name . 'Homepage Challenger created successfully!!!');
         $this->redirect('admin/sites/testimonialbrowse/' . $blockData->site_id);
      }
      $this->render($this->template, 'pages/Admin/Sites/testimonialcreate');
      $this->template->content->editor       = Ckeditor::instance();
      $this->template->content->BlockDetails = $blockData;
      $this->template->content->site_id      = $blockData->site_id;
      $this->template->content->site_name    = $Sitelist->name;
   }
   /*
    * homepage Testimonial */
   public function action_testimonialbrowse()
   {
      if (!Helper_Common::hasAccess('Manage Home Page')) {
         $this->session->set('denied_permission', '1');
         $this->redirect('admin/dashboard');
      }
      $this->template->current_tab = 'Testimonial';
      $site_id                     = $this->request->param('id');
      $Sitelist                    = ORM::factory('Sites')->where('id', '=', $site_id)->find();
      $this->template->title       = 'Manage ' . $Sitelist->name . ' Challenger';
      $this->render($this->template, 'pages/Admin/Sites/testimonialbrowse');
      $testimonialDetails                          = ORM::factory('admin_Sitetestimonial')->where('is_delete', '=', '0')->where('site_id', '=', $site_id)->find_all()->as_array();
      $this->template->content->TestimonialDetails = $testimonialDetails;
      $this->template->content->site_id            = $site_id;
      $this->template->content->site_name          = $Sitelist->name;
   }
   /*
    * slider delete */
   public function action_testimonialdelete()
   {
      $id = $this->request->post('id');
      if ($id) {
         //$sliderData = new Model_Siteslider($id);
         $siteData            = ORM::factory('admin_Sitetestimonial', $id);
         $siteData->is_delete = 1;
         $siteData->save();
      }
      echo 1;
      exit;
   }
   public function action_homecontent()
   {
      if (!Helper_Common::hasAccess('Manage Home Page')) {
         $this->session->set('denied_permission', '1');
         $this->redirect('admin/dashboard');
      }
      $site_id                     = $this->request->param('id');
      $Sitelist                    = ORM::factory('Sites')->where('id', '=', $site_id)->find();
      $this->template->current_tab = 'Homecontent';
      $this->template->title       = $Sitelist->name . ' HomePage Content';
      $cmsDetail                   = ORM::factory('admin_Sitecms')->where('is_delete', '=', '0')->where('site_id', '=', $site_id)->find()->as_array();
      if (empty($cmsDetail)) {
         $cmsDetails = ORM::factory('admin_Sitecms');
      } else {
         $cmsDetails = ORM::factory('admin_Sitecms')->where('is_delete', '=', '0')->where('site_id', '=', $site_id)->find();
      }
      if (isset($cmsDetails->site_logo) && $cmsDetails->site_logo != '') {
         $logo_img = $cmsDetails->site_logo;
      }
      if (HTTP_Request::POST == $this->request->method()) {
         $post                            = new Validation($_POST);
         $currentDate                     = Helper_Common::get_default_datetime();
         //Upload File
         $cmsDetails->description         = $this->request->post('home_page_content'); //echo $cmsDetails->description; die;
         $cmsDetails->video               = $this->request->post('video');
         $cmsDetails->footer_content      = $this->request->post('footer_content');
         $cmsDetails->social_facebook_url = $this->request->post('social_facebook_url');
         $cmsDetails->social_twitter_url  = $this->request->post('social_twitter_url');
         $cmsDetails->social_linkedin_url = $this->request->post('social_linkedin_url');

         $cmsDetails->video_status = $this->request->post('statusvideo') != '' ? $this->request->post('statusvideo') : '0';
         $cmsDetails->facebook_status = $this->request->post('statusfb') != '' ? $this->request->post('statusfb') : '0';
         $cmsDetails->instagram_status = $this->request->post('statusinsta') != '' ? $this->request->post('statusinsta') : '0';
         $cmsDetails->pinterest_status = $this->request->post('statuspinterest') != '' ? $this->request->post('statuspinterest') : '0';

         $cmsDetails->site_id             = $site_id;
         $cmsDetails->date_modified       = $currentDate;
         $filename                        = '';
         if (isset($_FILES['site_logo']['error']) && $_FILES['site_logo']['error'] == '0') {
            $filename              = $this->_save_image($_FILES['site_logo'], 208, 137, DOCROOT . 'assets/uploads/logo/');
            $cmsDetails->site_logo = $filename;
         } else {
            $image_details = ORM::factory('admin_Sitecms')->gethomepagedetails('site_logo', 'site_id=' . $site_id);
            /*if (isset($image_details) && count($image_details) > 0) {
               $cmsDetails->site_logo = $image_details[0]['site_logo'];
            }*/
         }
         /*if(!empty($filename)){
         $unlinkOldImage =
         DOCROOT.'assets/uploads/logo/'.$cmsDetails->site_logo;
         if(file_exists($unlinkOldImage))
         unlink($unlinkOldImage);
         $cmsDetails->site_logo = $filename;
         }*/
         $cmsDetails->save();
         $this->session->set('flash_success', $Sitelist->name . ' Homepage content
         updated successfully!!!');
         $this->redirect('admin/sites/homecontent/'.$site_id);
      }
      $this->render($this->template, 'pages/Admin/Sites/homepagecontent');
      $this->template->content->editor       = Ckeditor::instance();
      $this->template->content->BlockDetails = $cmsDetails;
      $this->template->content->site_name    = $Sitelist->name;
   }
   // social page :G.R
   public function action_socialpage()
   {
      $site_id = $this->request->param('id');
      $user = Auth::instance()->get_user()->pk();
      $Sitelist = ORM::factory('Sites')->where('id', '=', $site_id)->find();
      $this->template->current_tab = 'Socialpage';
      $this->template->title       = $Sitelist->name . ' Social Post Content';
      $siteCms = ORM::factory('admin_Sitecms');
      if (HTTP_Request::POST == $this->request->method()) {
         // print_r($_FILES);exit;
         $post = new Validation($_POST);
         $currentDate = Helper_Common::get_default_datetime();
		 $socialcmsDetails['title']  	   = $this->request->post('site_title');
         $socialcmsDetails['description']  = $this->request->post('social_post_content');
         $socialcmsDetails['video']        = $this->request->post('video');
         $socialcmsDetails['video_status'] = $this->request->post('statusvideo') != '' ? $this->request->post('statusvideo') : '0';
         $socialcmsDetails['site_id']      = $site_id;
         $socialcmsDetails['user_id']      = $user;
         $socialcmsDetails['date_created'] = $socialcmsDetails['date_modified'] = $currentDate;
         //Upload File
		 if(isset($_FILES['site_image']['name']) && !empty($_FILES['site_image']['name'])){
			 $filename = '';
			 //echo "<pre>";print_r($_FILES);die();
			 if (isset($_FILES['site_image']['error']) && $_FILES['site_image']['error'] == '0') {
				$filename = $this->_save_image($_FILES['site_image'], 0, 0, DOCROOT . 'assets/uploads/logo/');
			 }
			 $socialcmsDetails['site_image'] = $filename;
		 }
		 $socialpostDetail = $siteCms->getsocialpagedetails('*', 'site_id='.$site_id.' AND is_delete=0');
         if(!empty($socialpostDetail) && count($socialpostDetail) > 0){
            $savetype = 'update';
         }else{
            $savetype = 'insert';
         }
         $saveres = $siteCms->savesocialpagedetails($socialcmsDetails, $savetype);
         if($saveres){
            $this->session->set('flash_success', $Sitelist->name . ' Social post content saved successfully !!!');
         }else{
            $this->session->set('flash_error', $Sitelist->name . ' Error occured while saving !!!');
         }
         $this->redirect('admin/sites/socialpage/'.$site_id);
      }
	  $socialpostDetail = $siteCms->getsocialpagedetails('*', 'site_id='.$site_id.' AND is_delete=0');
      $this->render($this->template, 'pages/Admin/Sites/socialpage');
      $this->template->content->editor       = Ckeditor::instance();
      $this->template->content->BlockDetails = $socialpostDetail;
      $this->template->content->site_name    = $Sitelist->name;
   }
   public function action_deleteSocialpageImg(){
      $post = $this->request->post();
      $action_set = $post['action'];
      $siteidval = $post['siteid'];
      $fieldname = $post['fieldname'];
      $imgroot = $post['imgroot'];
      $response_sys = '';  
      if($action_set == "deleteimg"){
         $update_query = $fieldname." = '' ";
         $wherecnd = 'site_id = "'.$siteidval.'" ';
         Helper_Common::updategeneralfn('sitesocaialpages', $update_query, $wherecnd);
         unlink($imgroot);
         $response_sys = 'success';
      }
      echo $response_sys;
      return false;
   }
   public function action_advanced_css()
   {
      if (!Helper_Common::hasAccess('Manage Home Page')) {
         $this->session->set('denied_permission', '1');
         $this->redirect('admin/dashboard');
      }
      $this->template->current_tab = 'Advancedcss';
      $site_id                     = $this->request->param('id');
      $Sitelist                    = ORM::factory('Sites')->where('id', '=', $site_id)->find();
      $cmsDetail                   = ORM::factory('admin_Sitecms')->where('is_delete', '=', '0')->where('site_id', '=', $site_id)->find()->as_array();
      if (empty($cmsDetail)) {
         $cmsDetails = ORM::factory('admin_Sitecms');
      } else {
         $cmsDetails = ORM::factory('admin_Sitecms')->where('is_delete', '=', '0')->where('site_id', '=', $site_id)->find();
      }
      if (HTTP_Request::POST == $this->request->method()) {
         $post                      = new Validation($_POST);
         $currentDate               = Helper_Common::get_default_datetime();
         //Upload File
         $cmsDetails->advanced_css  = $this->request->post('advanced_css');
         $cmsDetails->site_id       = $site_id;
         $cmsDetails->date_modified = $currentDate;
         $filename                  = '';
         $cmsDetails->save();
         $this->session->set('flash_success', $Sitelist->name . ' Advacned css
         updated successfully!!!');
         //$this->redirect('admin/site/homecontent/'.base64_encode($site_id));
      }
      $this->render($this->template, 'pages/Admin/Sites/advanced_css');
      $this->template->js_bottom             = array(
         'assets/js/jscolor.js'
      );
      $this->template->content->BlockDetails = $cmsDetails;
      $this->template->content->site_name    = $Sitelist->name;
   }
   public function action_footermenu()
   {
      if (!Helper_Common::hasAccess('Manage Home Page')) {
         $this->session->set('denied_permission', '1');
         $this->redirect('admin/dashboard');
      }
      $this->template->title = 'Footer Menu';
      $site_id               = $this->request->param('id');
      $Sitelist              = ORM::factory('Sites')->where('id', '=', $site_id)->find();
      $sitesmodel            = ORM::factory('admin_sites');
      $this->render();
      $this->template->content->site_name = $Sitelist->name;
      $this->template->js_bottom          = array(
         'assets/js/pages/admin/footermenu.js'
      );
      if (HTTP_Request::POST == $this->request->method()) {
         $postArray['title'] = $this->request->post('title');
         if (isset($postArray['title']) && count($postArray['title']) > 0) {
            $sitesmodel->deleteFooterMenu($site_id);
            $titleArray = $urlArray = array();
            $titleArray = $this->request->post('title');
            $urlArray   = $this->request->post('url');
            $i          = 0;
            for ($i = 0; $i < count($this->request->post('title')); $i++) {
               if (isset($titleArray[$i]) && $titleArray[$i] != '' && isset($urlArray[$i]) && $urlArray[$i] != '') {
                  $data            = array();
                  $data['title']   = $titleArray[$i];
                  $data['url']     = $urlArray[$i];
                  $data['site_id'] = $site_id;
                  $sitesmodel->insertFooterMenu($data);
               }
            }
            $this->session->set('flash_success', $Sitelist->name . ' footer menu
            saved successfully!!!');
         }
      }
      $this->template->content->footerMenu = $sitesmodel->get_site_footer_menu($site_id);
   }
   /*****************************Site Duplication Action
   /*Start***********************************/
   public function action_site_duplicate()
   {
      if ($this->request->is_ajax())
         $this->auto_render = FALSE;
      $sites = ORM::factory('admin_sites');
      $exercise = ORM::factory('admin_exercise');
      $slug  = $this->request->post('slug');
      $duplicate_id = $this->request->post('dub_id');
      if (empty($slug)){
         $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $this->request->post('name'));
      }
      $defaultsiteArray = $sites->getgeneraltable("id = '".$duplicate_id."' ",'sites');
      //echo "<pre>";print_r($defaultsiteArray);echo "</pre>";die();
      $sites->name       = $this->request->post('sitename');
      $sites->slug       = $slug;
      $sites->sample_workouts  = $defaultsiteArray[0]['sample_workouts'];
      $sites->exercise_records = $defaultsiteArray[0]['exercise_records'];
      $sites->sample_images    = $defaultsiteArray[0]['sample_images'];
      $sites->is_active        = $defaultsiteArray[0]['is_active'];
      $sites->from_site_id     = $duplicate_id;
      $sites->is_deleted       = $defaultsiteArray[0]['is_deleted'];
      $sites->is_contact       = $defaultsiteArray[0]['is_contact'];
      $sites->min_agelimit     = $defaultsiteArray[0]['min_agelimit'];
      $sites->common_question = $defaultsiteArray[0]['common_question'];
      $sites->created_at = Helper_Common::get_default_datetime();
      $sites->save();
      $insertId                       = $sites->pk();
      $userSitesData                  = array();
      $userSitesData['user_id']       = Auth::instance()->get_user()->pk();
      $userSitesData['site_id']       = $insertId;
      $userSitesData['status']        = 1;
      $currentDate                    = Helper_Common::get_default_datetime();
      $userSitesData['last_login']    = $currentDate;
      $userSitesData['modified_date'] = $currentDate;
      $userSiteId                     = $sites->insertUserSites($userSitesData);
      // create default mail templates for this site
      $smtpmodel                      = ORM::factory('admin_smtp');
      // create duplicate site device for this site - mdh
      $defaultsitedeviceArray = $sites->getgeneraltable("site_id = '".$duplicate_id."' ",'sitedevice');
      if (isset($defaultsitedeviceArray) && count($defaultsitedeviceArray) > 0) {
         foreach ($defaultsitedeviceArray as $key4 => $value4) {
            unset($value4['id']);
            $value4['site_id'] = $insertId;
            $sites->insertduplicategeneral($value4, 'sitedevice');
         }
      }
      //create duplicate template email template this site - mdh
      $etemplatetype = array();
      $defaulttemplateArray = $sites->getgeneraltable("site_id = '".$duplicate_id."' ",'email_template');
      if (isset($defaulttemplateArray) && count($defaulttemplateArray) > 0) {
         foreach ($defaulttemplateArray as $key0 => $value0) {
            $default_id0 =$value0['template_id'];
            unset($value0['template_id']);
            $value0['site_id'] = $insertId;
            $insertid0=$sites->insertduplicategeneral($value0, 'email_template');
            $etemplatetype[$default_id0]= $insertid0;
         }
      }
      // create duplicate sitequestions for this site - mdh
      $questtemplatetype = array();
      $defaultsitequestArray = $sites->getgeneraltable("site_id = '".$duplicate_id."' ",'sitequestions');
      if (isset($defaultsitequestArray) && count($defaultsitequestArray) > 0) {
         foreach ($defaultsitequestArray as $key5 => $value5) {
            $default_id1 =$value5['id'];
            unset($value5['id']);
            $value5['site_id'] = $insertId;
            $insertid1 = $sites->insertduplicategeneral($value5, 'sitequestions');
            $questtemplatetype[$default_id1]= $insertid1;
         }
      }
      if(is_array($questtemplatetype) && count($questtemplatetype)) { 
         foreach($questtemplatetype as $key_qo => $value_qo){
            $defaultquestioptArray = $sites->getgeneraltable("sqid = '".$key_qo."' ",'sitequestionoptions');
            if(is_array($defaultquestioptArray) && count($defaultquestioptArray) >0){
			    foreach($defaultquestioptArray as $key_qoption => $value_qoption){
				   unset($value_qoption['id']);
				   $value_qoption['sqid'] = $value_qo;
				   $sites->insertduplicategeneral($value_qoption, 'sitequestionoptions');
			    }
            }
         }
      }
      // create duplicate smtp for this site - mdh
      $defaultsmtpArray = $sites->getgeneraltable("site_id = '".$duplicate_id."' ",'smtp');
      if (isset($defaultsmtpArray) && count($defaultsmtpArray) > 0) {
         foreach ($defaultsmtpArray as $key1 => $value1) {
            unset($value1['smtp_id']);
            $value1['site_id'] = $insertId;
            $sites->insertduplicategeneral($value1, 'smtp');
         }
      }
	  // create duplicate sitesocial for this site
      $defaultsitesocialArray = $sites->getgeneraltable("site_id = '".$duplicate_id."' ",'sitesocaialpages');
      if (isset($defaultsitesocialArray) && count($defaultsitesocialArray) > 0) {
         foreach ($defaultsitesocialArray as $key6 => $value6) {
            unset($value6['id']);
            $value6['site_id'] = $insertId;
            $insertid1 = $sites->insertduplicategeneral($value6, 'sitesocaialpages');
         }
      }	  
      // create duplicate Email Template Type for this site - mdh
      $defaultemailttArray = $sites->getgeneraltable("site_id = '".$duplicate_id."' ",'email_template_type');
      if (isset($defaultemailttArray) && count($defaultemailttArray) > 0) {
         foreach ($defaultemailttArray as $key2 => $value2) {
            unset($value2['type_id']);
            $value2['site_id'] = $insertId;
            $sites->insertduplicategeneral($value2,'email_template_type');
         }
      }
      if(is_array($etemplatetype) && count($etemplatetype)) { 
        $sites->updateduplicategeneral($etemplatetype,'email_template_type',$insertId); 
      }
      // create duplicate Email Variable for this site - mdh
	   /*$user_list = $sites->get_adminuser_list();
      $defaultemailvray = $sites->getgeneraltable("site_id = '".$duplicate_id."' AND created_by not in ('".$user_list."') ",'email_variable');
	 echo "<pre>";print_r($defaultemailvray);echo "</pre>";
	  die();*/
	  $defaultemailvray = $sites->getgeneraltable("site_id = '".$duplicate_id."'",'email_variable');
      if (isset($defaultemailvray) && count($defaultemailvray) > 0) {
         foreach ($defaultemailvray as $key3 => $value3) {
            unset($value3['variable_id']);
            $value3['site_id'] = $insertId;
            $sites->insertduplicategeneral($value3,'email_variable');
         }
      }
      //create duplicate Sample Exercise record for this site - mdh
		
      /*$defaultexercisesample = $sampledataarray ='';
      $defaultexercisesample = $sites->getgeneraltable("site_id = '".$duplicate_id."' and default_status =2 and status_id != 4",'unit_gendata');
      if(is_array($defaultexercisesample) && count($defaultexercisesample) > 0){
         foreach($defaultexercisesample as $key_dex => $value_dex){
              $sampledataarray[] =  $value_dex['unit_id'];
         }
      } 
      if(is_array($sampledataarray) && count($sampledataarray) > 0){
        $exercise->copyDeleteExerciseRecById("exerciseRecord",$sampledataarray,$insertId,"sample","multiple"); 
      }
      */
      //create duplicate Sample Workouts record for this site - mdh
      /*wkout_sample_gendata
      wkout_sample_goal_gendata
      wkout_sample_goal_vars
      wkout_sample_seq*/
      // create duplicate page for this site
      $defaultPagesArray = $sites->getpagelist($duplicate_id);
      if (isset($defaultPagesArray) && count($defaultPagesArray) > 0) {
         foreach ($defaultPagesArray as $key => $value) {
            $sites->insertduplicatePage($value, $insertId);
         }
      }
      // create duplicate sitesliders for this site
      $defaultsliderArray = $sites->getsitesliderslist($duplicate_id);
      if (isset($defaultsliderArray) && count($defaultsliderArray) > 0) {
         foreach ($defaultsliderArray as $key => $value) {
            $sites->insertduplicateslider($value, $insertId);
         }
      }
      // create duplicate siteblocks for this site
      $defaultsiteblocksArray = $sites->getsitesiteblockslist($duplicate_id);
      if (isset($defaultsiteblocksArray) && count($defaultsiteblocksArray) > 0) {
         foreach ($defaultsiteblocksArray as $key => $value) {
            $sites->insertduplicateblocks($value, $insertId);
         }
      }
      // create duplicate sitepartners for this site
      $defaultsitepartnersArray = $sites->getsitesitepartnerslist($duplicate_id);
      if (isset($defaultsitepartnersArray) && count($defaultsitepartnersArray) > 0) {
         foreach ($defaultsitepartnersArray as $key => $value) {
            $sites->insertduplicatepartners($value, $insertId);
         }
      }
      // create duplicate sitetestimonials for this site
      $defaultsitetestimonialsArray = $sites->getsitesitetestimonialslist($duplicate_id);
      if (isset($defaultsitetestimonialsArray) && count($defaultsitetestimonialsArray) > 0) {
         foreach ($defaultsitetestimonialsArray as $key => $value) {
            $sites->insertduplicatetestimonials($value, $insertId);
         }
      }
      // create duplicate sitehomepages for this site
      $defaultsitehomepagesArray = $sites->getsitehomepageslist($duplicate_id);
      if (isset($defaultsitehomepagesArray) && count($defaultsitehomepagesArray) > 0) {
         foreach ($defaultsitehomepagesArray as $key => $value) {
            $sites->insertduplicatehomepages($value, $insertId);
         }
      }
      // create duplicate sitefootermenu for this site
      $defaultsitefootermenuArray = $sites->getsitefootermenulist($duplicate_id);
      if (isset($defaultsitefootermenuArray) && count($defaultsitefootermenuArray) > 0) {
         foreach ($defaultsitefootermenuArray as $key => $value) {
            $sites->insertduplicatefootermenu($value, $insertId, $duplicate_id);
         }
      }
      // create duplicate Preference Defaults Settings for this site
      $defaultsitePreferenceArray = $sites->getsitePreferencelist($duplicate_id);
      if (isset($defaultsitePreferenceArray) && count($defaultsitePreferenceArray) > 0) {
         foreach ($defaultsitePreferenceArray as $key => $value) {
            $sites->insertduplicatePreference($value, $insertId);
         }
      }
      // create duplicate Role Access Settings for this site
      $defaultsiteRoleAccessArray = $sites->getsiteRoleAccesslist($duplicate_id);
      if (isset($defaultsiteRoleAccessArray) && count($defaultsiteRoleAccessArray) > 0) {
         foreach ($defaultsiteRoleAccessArray as $key => $value) {
            $sites->insertduplicateRoleAccess($value, $insertId);
         }
      }
      //create language folder using siteid dh
      //$insertId = '222';
      $dir ="application/i18n/admin/".$insertId;
      if(!is_dir($dir)) { 
         mkdir($dir,0777); 
         chmod($dir,0777);
      }
      Helper_common::recurse_copy("assets/langsample/admin/sample",$dir);
      $dir ="application/i18n/front/".$insertId;
      if(!is_dir($dir)) { 
         mkdir($dir,0777);
         chmod($dir,0777);
      }
      Helper_common::recurse_copy("assets/langsample/front/sample",$dir);
      $response['success'] = true;
      $response['message'] = 'Site Duplicated Successfully';
      /** added by R.G **/
      $site = ORM::factory('Sites', $insertId);
      $usermodel  = ORM::factory('admin_user');
      //print_r($site);die;
      $user_sites = $usermodel->get_user_sites(Auth::instance()->get_user());
      $session = Session::instance();
      $session->set('user_sites', $user_sites);
      $session->set('current_site_id', $site->id);
      $session->set('current_site_name', $site->name); 
      $response['newsite_id'] = $site->id;
      $response['newsite_name'] = $site->name;
      /** END **/
      echo $this->request->response = json_encode($response);
   }
   /*****************************Site Duplication Action
   /*End***********************************/
   /*****START: dh general query functionality******/
   public function action_generalqueryset(){
      $post   = $this->request->post();
      $action_set = $post['action'];
      $siteidval = $post['valuesiteid'];
      $response_sys = '';  
      if($action_set == "deletelogo"){
         $update_query = "site_logo = '' ";
         $wherecnd = 'site_id = "'.$siteidval.'" ';
         Helper_Common::updategeneralfn('sitehomepages',$update_query,$wherecnd);
         unset($_FILES['site_logo']);
         $response_sys = 'success';
      }
      echo $response_sys;
      return false;
   }
   /*****END: dh general query functionality******/
   /*****START: dh sociallinks functionality******/
   public function action_sociallinks()
   {
      if (!Helper_Common::hasAccess('Manage Home Page')) {
         $this->session->set('denied_permission', '1');
         $this->redirect('admin/dashboard');
      }
      $site_id                     = $this->request->param('id');
      $Sitelist                    = ORM::factory('Sites')->where('id', '=', $site_id)->find();
      $this->template->current_tab = 'Homecontent';
      $this->template->title       = $Sitelist->name . ' Home page Social links';
      $cmsDetail                   = ORM::factory('admin_Sitecms')->where('is_delete', '=', '0')->where('site_id', '=', $site_id)->find()->as_array();
      if (empty($cmsDetail)) {
         $cmsDetails = ORM::factory('admin_Sitecms');
      } else {
         $cmsDetails = ORM::factory('admin_Sitecms')->where('is_delete', '=', '0')->where('site_id', '=', $site_id)->find();
      }
      
      if (HTTP_Request::POST == $this->request->method()) {
         $post                            = new Validation($_POST);
         $currentDate                     = Helper_Common::get_default_datetime();
        
         $cmsDetails->footer_content      = $this->request->post('footer_content');
         $cmsDetails->social_facebook_url = $this->request->post('social_facebook_url');
         $cmsDetails->social_twitter_url  = $this->request->post('social_twitter_url');
         $cmsDetails->social_linkedin_url = $this->request->post('social_linkedin_url');

         $cmsDetails->video_status = $this->request->post('statusvideo') != '' ? $this->request->post('statusvideo') : '0';
         $cmsDetails->facebook_status = $this->request->post('statusfb') != '' ? $this->request->post('statusfb') : '0';
         $cmsDetails->instagram_status = $this->request->post('statusinsta') != '' ? $this->request->post('statusinsta') : '0';
         $cmsDetails->pinterest_status = $this->request->post('statuspinterest') != '' ? $this->request->post('statuspinterest') : '0';

         $cmsDetails->site_id             = $site_id;
         $cmsDetails->date_modified       = $currentDate;
         $filename                        = '';
        
         $cmsDetails->save();
         $this->session->set('flash_success', $Sitelist->name . ' Homepage Footer links updated successfully!!!');
      }
      $this->render($this->template, 'pages/Admin/Sites/socaillinks');
      $this->template->content->BlockDetails = $cmsDetails;
      $this->template->content->site_name    = $Sitelist->name;
   }
   /******END: dh sociallinks functionality*********/
   public function action_site_Filter(){
      if ($this->request->is_ajax())
         $this->auto_render = FALSE;
      $postArray = $siteresult = '';
      $sites = ORM::factory('admin_sites');
      if (HTTP_Request::POST == $this->request->method()) {
         $action = $this->request->post('action');
         if(!empty($action) && $action == 'searchsite'){
            $postArray['seachtext'] = $this->request->post('searchText');
            $postArray['sortby'] = $this->request->post('sortby');
            $siteresult = $sites->get_existsSiteFilter_byAdmin($postArray);
         }
      }
      echo $this->request->response = json_encode($siteresult);
   }
}
