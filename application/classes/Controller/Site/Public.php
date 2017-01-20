<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Site_Question extends Controller_Site_Website {

	public function _construct() {
         parent::__construct($request, $response);
    }
		
	public function action_index()
	{
		$this->template->title = 'Public Dashboard';
		$this->render();
		$this->template->css = array('assets/plugins/iCheck/square/blue.css');
		$this->template->js = array('assets/plugins/iCheck/icheck.js');
	}
}